<?php

namespace App\Services;

class FileOperationService
{
    public static function storeFile(string $directory , string $fileName): void
    {
        $directory = __DIR__."/../../storage/app/public/$directory";
        if(!is_dir($directory)){
            mkdir($directory);
            chmod($directory , 0777);
        }

        if(!is_file("$directory/$fileName")){

        }
    }

    /**
     * @param string $path
     * @return bool
     */
    public static function deleteImage(string $path): bool
    {
        if (!is_file(__DIR__ . '/../../storage/app/public/' . $path)) return true;
        if (is_file(__DIR__ . '/../../storage/app/public/' . $path)) {
            unlink(__DIR__ . '/../../storage/app/public/' . $path);

            return true;
        }

        return false;
    }
}
