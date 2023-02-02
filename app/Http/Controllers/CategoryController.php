<?php

namespace App\Http\Controllers;

use App\Http\Resources\Category\CategoryResource;
use App\Models\Category;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryController extends Controller
{

    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse | AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        try {
            $limit = $request->input('limit', 10);
            $products = QueryBuilder::for(Category::class)
                ->allowedFilters([
                    AllowedFilter::partial('name'),
                ])
                ->defaultSort('-created_at')
                ->allowedSorts('name', 'created_at')
                ->paginate($limit)
                ->appends(request()->query());

            return CategoryResource::collection($products)->additional($this->returnSuccessCollection(__('Categories list'),200));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
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
                'description' => 'string|max:60|nullable'
            ]
        );
    }
}
