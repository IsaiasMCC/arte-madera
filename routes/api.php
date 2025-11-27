<?php

use App\Http\Controllers\PagoFacilController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/pagofacil/callback', [PagoFacilController::class, 'urlCallback'])->name('pagofacil.callback');