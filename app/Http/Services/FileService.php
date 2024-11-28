<?php
namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class FileService {
    public function updateImage($model, $request) {
        $manager = new ImageManager(new Driver());
        $file = $request->file('thumbnail');

        if(!$file) return response()->json(['error' => 'File image not found'], 404);

        $ext = $file->getClientOriginalExtension();
        $image = $manager->read($file);

        if(!empty($model->image)) {
            $currentImage = public_path() . $model->image;
            if(file_exists($currentImage)) {
                unlink($currentImage);
            }
        }

        $image->crop(
            $request->width,
            $request->height,
            $request->left,
            $request->top
        );

        $nameImage = time() . '.' . $ext;
        $image->save(public_path() . '/files/' . $nameImage);
        $model->image = '/files/' . $nameImage;

        return $model;
    }
}
