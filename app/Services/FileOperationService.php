<?php

namespace App\Services;

use App\Http\Requests\UploadImageRequest;
use Illuminate\Support\Str;

class FileOperationService
{

    /**
     * @param string $directoryName
     * @return void
     */
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



    public function uploadFileTemporary(UploadImageRequest $request)
    {
        $timeStamp = date('Y_m_d_H');
        $this->makeDirectory('tmp/' . $timeStamp);
        $filePath = explode('/' , $request->file('image')->store("public/tmp/$timeStamp"));

        $imagePath = storage_path("/app/public/tmp/$timeStamp/". $filePath[count($filePath) - 1]);

        $imageType = pathinfo($imagePath , PATHINFO_EXTENSION);
//        return $imagePath;
        $imageContent = file_get_contents($imagePath);
//        return $imageContent;
        return "data:image/$imageType;base64," . base64_encode($imageContent);
//        return asset("/storage/tmp/$timeStamp/". $filePath[count($filePath) - 1]);
    }


    /**
     * @param object $class
     * @param string $collectionName
     * @param array $imagesToStore
     * @param array $imagesToKeep
     * @param array $errors
     * @return void
     */
    public function removeOldImagesAndStoreNew(
        object $class ,
        string $collectionName ,
        array $imagesToStore,
        array $imagesToKeep,
        array &$errors
    ): void
    {
        // Removing Old Images
        $class->images()->delete();

        // Store New Images From Base64

//       $class
        $this->storeImages($imagesToStore , $collectionName , $class);

        $mainImage = $imagesToKeep[0] ?? null;

        $mainImage = $class->load(
            [
                'images' => function($query) use ($mainImage , $collectionName){
                $query->where('file_name' , $mainImage);
                $query->take(1);
            }
            ]
        );

        if ($mainImage) {
            //TODO Remove all old images except the main image
            $classImages = $class->getMedia($collectionName);
            $keptImages = array_merge(
                $imagesToKeep,
                $imagesToStore
            );

            foreach ($classImages as $classImage) {

                $imageName = $classImage->file_name;

                if (
                    !in_array($imageName, $keptImages) && $imageName != $mainImage->file_name
                ) {
                    $classImage->delete();
                }
            }
        }

        else {
            $errors['keep_images'] = 'Must Keep At Least The Main Image';
        }
    }

    /**
     * @param array $imagesToStore
     * @param string $collectionName
     * @param object $class
     * @return void
     */
    public function storeImages(array $imagesToStore , string $collectionName , object $class): void
    {

        foreach ($imagesToStore as $image) {
            $class->addMedia(
                storage_path('app/public/tmp/' . date('Y_m_d_H') . "/$image")
            )
                ->toMediaCollection($collectionName);
        }
    }

    /**
     * Store Image From Request
     * @param object $class
     * @param string $collectionName
     * @param string $fileName
     * @param string|null $storedFileName
     * @return void
     */
    public function storeImageFromRequest(
        object $class,
        string $collectionName = 'default',
        string $fileName = 'img' ,
        string $storedFileName = null
    ): object
    {
        return json_decode($class
            ->addMediaFromRequest($fileName)
            ->usingFileName($storedFileName ?: Str::random().'.png')
            ->toMediaCollection($collectionName));
    }

    public function removeImage($image): void
    {
        if($image){
            $image->delete();
        }
    }
}
