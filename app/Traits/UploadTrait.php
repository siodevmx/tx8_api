<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


trait UploadTrait
{
    public function uploadOne(UploadedFile $uploadedFile, $folder, $filename = null)
    {
        $name = !is_null($filename) ? $filename : Str::random(25);
        $file = $uploadedFile->storeAs($folder, $name . '.' . $uploadedFile->getClientOriginalExtension(), 's3');
        return Storage::disk('s3')->path($file);
    }

    public function deleteOne($filename = null)
    {
        return Storage::disk('s3')->delete($filename);
    }
}
