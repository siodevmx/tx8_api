<?php

namespace App\Http\Controllers;

use App\Http\Resources\Image\ImageResource;
use App\Models\Image;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;
use App\Traits\UploadTrait;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ImageController extends Controller
{

    use ApiResponser, UploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse | AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        try {
            $limit = $request->input('limit', 10);
            $images = QueryBuilder::for(Image::class)
                ->allowedFilters([
                    AllowedFilter::partial('name'),
                ])
                ->defaultSort('-created_at')
                ->allowedSorts('name')
                ->paginate($limit)
                ->appends(request()->query());

            return ImageResource::collection($images)->additional($this->returnSuccessCollection(__('Images list'), 200));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(Request $request)
    {

        $validator = $this->validateImage();
        if ($validator->fails()) {
            return $this->errorsResponse($validator->messages(), 422);
        }
        try {
            // Store Image
            $image = $request->file('image');

            $name = Str::uuid();
            $folder = '/images';
            $imageUrl = $this->uploadOne($image, $folder, $name);
            $imageSize = Storage::disk('s3')->size($imageUrl);

            $image = Image::create([
                'name' => $request['name'],
                'size' => $imageSize,
                'path' => $imageUrl
            ]);

            return $this->successResponse($image, __('Image created'), 'success_image_created', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }

    }

    /**
     * Update a resource in storage.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $id)
    {

        // Find Image
        $image = Image::find($id);

        if (is_null($image)) {
            return $this->errorResponse(__('Image not found'), 404);
        }

        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:128',
        ]);

        if ($validator->fails()) {
            return $this->errorsResponse($validator->messages(), 422);
        } else {
            try {
                // Find Image
                $image = Image::find($id);
                $image->name = $request->input('name');
                $image->save();

                return $this->successResponse($image, __('Image updated'), 'success_image_updated', 201);
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), 500);
            }
        }
    }

    /**
     * Delete a resource from storage.
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        try {
            $image = Image::find($id);

            if (is_null($image)) {
                return $this->errorResponse(__('Image not found'), 404);
            } else {
                $image_path = $image->path;
                $deleted = $this->deleteOne($image_path);
                if ($deleted) {
                    $image->delete();
                    return $this->successResponse(null, __('Image deleted'), 'success_image_deleted', 201);
                } else {
                    return $this->errorResponse(__('An error occurred while deleting the image'), 500);
                }
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }


    /**
     * Validate the resource before save it.
     *
     */
    private function validateImage()
    {
        return Validator::make(request()->all(), [
                'name' => 'required|string|max:128',
                'image' => 'required|max:5120'
            ]
        );
    }


}
