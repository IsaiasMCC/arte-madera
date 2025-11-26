<?php

namespace App\Http\Controllers;

use App\Models\DetallePago;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PagoFacilController extends Controller
{
    private $tokenService = "51247fae280c20410824977b0781453df59fad5b23bf2a0d14e884482f91e09078dbe5966e0b970ba696ec4caf9aa5661802935f86717c481f1670e63f35d504a62547a9de71bfc76be2c2ae01039ebcb0f74a96f0f1f56542c8b51ef7a2a6da9ea16f23e52ecc4485b69640297a5ec6a701498d2f0e1b4e7f4b7803bf5c2eba";
    private $tokenSecret = "0C351C6679844041AA31AF9C";
    private $baseUrl = "https://masterqr.pagofacil.com.bo/api/services/v2";

    /**
     * Obtener el Bearer Token (se cachea por 3 horas)
     */
    private function getBearerToken()
    {
        return Cache::remember('pagofacil_bearer_token', 3600, function () { // 1 hora de cache

            $response = Http::withHeaders([
                'tcTokenService' => $this->tokenService,
                'tcTokenSecret' => $this->tokenSecret,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post($this->baseUrl . '/login');

            Log::info("Login PagoFácil", [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if (!$response->successful()) {
                Log::error("Error al autenticar con PagoFácil", [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception("No se pudo autenticar con PagoFácil: " . $response->body());
            }

            $data = $response->json();

            // El token está en values.accessToken según tu respuesta
            $token = $data['values']['accessToken'] ?? null;

            if (!$token) {
                Log::error("Token no encontrado en respuesta", ['data' => $data]);
                throw new \Exception("Token no encontrado en la respuesta de login");
            }

            Log::info("Token obtenido exitosamente", ['token_preview' => substr($token, 0, 20) . '...']);
            return $token;
        });
    }

    public function generarQR(Request $request)
    {
        try {
            $pago = Pago::findOrFail($request->pago_id);
            $pedido = $pago->pedido;
            $usuario = $pedido->user;

            $monto = $pedido->saldoPendiente();

            if ($monto <= 0) {
                return response()->json(["error" => "El pedido ya está pagado."]);
            }

            // Preparar detalle de orden
            $orderDetail = [];
            foreach ($pedido->detalles as $index => $detalle) {
                $orderDetail[] = [
                    "serial" => $index + 1,
                    "product" => $detalle->producto->nombre,
                    "quantity" => $detalle->cantidad,
                    "price" => (float) $detalle->subtotal,
                    "discount" => 0,
                    "total" => (float) ($detalle->subtotal * $detalle->cantidad)
                ];
            }

            // Obtener token
            $bearerToken = $this->getBearerToken();

            // Generar QR
            $resp = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $bearerToken
                ])
                ->post($this->baseUrl . '/generate-qr', [
                    "paymentMethod" => 4,
                    "clientName" => $usuario->name ?? "Cliente",
                    "documentType" => 1,
                    "documentId" => $usuario->ci ?? "0",
                    "phoneNumber" => $usuario->telefono ?? "00000000",
                    "email" => $usuario->email,
                    "paymentNumber" => "PED-" . $pedido->id . "-" . time(),
                    "amount" => (float) $monto,
                    "currency" => 2,
                    "clientCode" => "11001",
                    "callbackUrl" => "https://google.com",
                    "orderDetail" => $orderDetail
                ]);

            if (!$resp->successful()) {
                Log::error("Error PagoFácil generate-qr", [
                    'status' => $resp->status(),
                    'body' => $resp->body()
                ]);
                return response()->json([
                    "error" => "Error al generar QR",
                    "status" => $resp->status(),
                    "details" => $resp->json()
                ]);
            }

            $data = $resp->json();
            Log::info("Respuesta generate-qr", $data);

            // Extraer datos según la respuesta real que vimos
            $transaccionId = $data['values']['transactionId'] ?? null;
            $qrBase64 = $data['values']['qrBase64'] ?? null;

            if (!$transaccionId || !$qrBase64) {
                return response()->json([
                    "error" => "Respuesta incompleta de PagoFácil",
                    "data" => $data
                ]);
            }

            // Guardar transacción en BD
            // $pago->transaccion_qr = $transaccionId;
            $pago->save();

            return response()->json([
                "qr" => "data:image/png;base64," . $qrBase64,
                "transaccion" => $transaccionId
            ]);
        } catch (\Exception $e) {
            Log::error("Excepción en generarQR", [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return response()->json([
                "error" => "Error interno: " . $e->getMessage()
            ], 500);
        }
    }

    public function consultarEstado(Request $request)
    {
        try {
            $bearerToken = $this->getBearerToken();

            Log::info("Consultando estado - Request", [
                'transactionId' => $request->tnTransaccion,
                'url' => $this->baseUrl . '/query-transaction'
            ]);

            // Es POST y usa pagofacilTransactionId en el body
            $resp = Http::withHeaders([
                'Authorization' => 'Bearer ' . $bearerToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post($this->baseUrl . '/query-transaction', [
                'pagofacilTransactionId' => $request->tnTransaccion
            ]);

            Log::info("Respuesta consulta estado", [
                'status' => $resp->status(),
                'body' => $resp->json()
            ]);

            if (!$resp->successful()) {
                return response()->json([
                    "error" => "Error al consultar estado",
                    "status" => $resp->status(),
                    "details" => $resp->json()
                ]);
            }

            $data = $resp->json();

            // El estado viene en values.paymentStatus
            $paymentStatus = $data['values']['paymentStatus'] ?? null;

            // Mapear el ID del estado a texto legible
            $estadoTexto = "DESCONOCIDO";
            switch ($paymentStatus) {
                case 1:
                    $estadoTexto = "PENDIENTE";
                    break;
                case 2:
                    $estadoTexto = "COMPLETADO";
                    break;
                case 3:
                    $estadoTexto = "RECHAZADO";
                    break;
                case 4:
                    $estadoTexto = "ANULADO";
                    break;
            }

            return response()->json([
                "estado" => $estadoTexto,
                "paymentStatus" => $paymentStatus,
                "data" => $data
            ]);
        } catch (\Exception $e) {
            Log::error("Excepción consultarEstado", [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                "error" => "Error: " . $e->getMessage()
            ], 500);
        }
    }

    public function urlCallback(Request $request)
    {
        try {
            Log::info("Callback PagoFácil recibido", $request->all());

            // Obtener datos del callback
            $transaccionId = $request->input('transactionId')
                ?? $request->input('TransactionId')
                ?? $request->input('id');

            $estado = $request->input('status')
                ?? $request->input('state');

            $pago = Pago::where('transaccion_qr', $transaccionId)->first();

            if (!$pago) {
                Log::warning("Pago no encontrado para transacción: " . $transaccionId);
                return response()->json(["error" => "Pago no encontrado"], 404);
            }

            // Estados que indican pago completado (ajusta según documentación)
            $estadosCompletados = ['COMPLETED', 'PAGADO', 'APPROVED', 'SUCCESS'];

            if (in_array(strtoupper($estado), $estadosCompletados)) {
                $pedido = $pago->pedido;
                $monto = $pedido->saldoPendiente();

                // Registrar el pago
                DetallePago::create([
                    'pago_id' => $pago->id,
                    'fecha' => now()->format('Y-m-d'),
                    'hora' => now()->format('H:i:s'),
                    'monto' => $monto,
                    'saldo' => 0
                ]);

                $pago->monto = $pago->detallePagos()->sum('monto');
                $pago->save();

                Log::info("Pago procesado exitosamente", [
                    'pago_id' => $pago->id,
                    'monto' => $monto
                ]);
            }

            return response()->json(["success" => true]);
        } catch (\Exception $e) {
            Log::error("Error en callback: " . $e->getMessage());
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
}
