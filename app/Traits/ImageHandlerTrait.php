<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

trait ImageHandlerTrait
{
    /**
     * Guarda una imagen en disco 'public' y devuelve el nombre del archivo.
     *
     * @param  \Illuminate\Http\UploadedFile  $image
     * @param  string  $folder
     * @param  int  $quality
     * @return string
     */
    public function storeImage($image, $folder = 'students', $quality = 5)
    {
        $imageName = time() . '.webp';

        // Usar el ImageManager con el driver 'gd' o 'imagick'
        $manager = new ImageManager(new Driver());

        $img = $manager->read($image->getRealPath());
        $encoded = $img->toWebp($quality);

        Storage::disk('public')->put("{$folder}/{$imageName}", (string) $encoded);

        return $imageName;
    }

    /**
     * Elimina una imagen del disco 'public'.
     *
     * @param string|null $imageName
     * @param string $folder
     * @return void
     */
    public function deleteImage($imageName, $folder = 'students')
    {
        $path = "{$folder}/{$imageName}";
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
