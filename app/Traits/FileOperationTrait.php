<?php

namespace App\Traits;


trait FileOperationTrait
{
    use StringTrait;

    /**
     * Write A Json File For Testing.
     */
    public function writeAFileForTesting(string $directory, string $file_name, string $data): void
    {
        if (config('test.store_response')) {
            if (!is_dir(__DIR__ . '/../../tests/responsesExamples/' . $directory)) {
                mkdir(__DIR__ . '/../../tests/responsesExamples/' . $directory, recursive: true);
            }
            $handle = fopen(__DIR__ . '/../../tests/responsesExamples/' . $directory . "/$file_name" . '.json', 'w');
            fwrite($handle, $data);
            fclose($handle);
        }
    }

    public function deleteImage(string $path): bool
    {
        if (!is_file(__DIR__ . '/../../storage/app/public/' . $path)) return true;
        if (is_file(__DIR__ . '/../../storage/app/public/' . $path)) {
            unlink(__DIR__ . '/../../storage/app/public/' . $path);

            return true;
        }

        return false;
    }
}
