<?php

namespace App\Http\Controllers;

use App\Http\Resources\Category\CategoryResource;
use App\Models\Category;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $category = Category::get();
        return CategoryResource::collection($category)->additional($this->returnSuccessCollection(__('Categories list'),200));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = $this->validateCategory();
        if ($validator->fails()) {
            return $this->errorsResponse($validator->messages(), 422);
        } else {
            try {
                $category = Category::create([
                    'name' => $request['name'],
                    'description' => $request['description'],
                ]);

                return $this->successResponse($category, __('Category created'),'success_category', 201);
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), 500);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }

    /**
     * Validate the request
     * @return mixed
     */
    private function validateCategory()
    {
        return Validator::make(request()->all(), [
                'name' => 'required|string|max:50|unique:categories,name',
                'description' => 'string|max:60'
            ]
        );
    }
}
