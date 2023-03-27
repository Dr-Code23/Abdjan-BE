<?php

namespace App\Services;

use App\Http\Requests\UploadImageRequest;

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

    public function makeDirectory(string $directoryName): void
    {
        $directory =storage_path('app/public/' . $directoryName);
        if(!is_dir($directory)){
            mkdir($directory , recursive: true);
            chmod($directory , 0777);
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


    /**
     * @param UploadImageRequest $request
     * @return string
     */
    public function uploadFileTemporary(UploadImageRequest $request): string
    {
        $timeStamp = date('Y_m_d_H');
        $this->makeDirectory('tmp/' . $timeStamp);
        $filePath = explode('/' , $request->file('image')->store("public/tmp/$timeStamp"));

        return $filePath[count($filePath) - 1];
    }
}
