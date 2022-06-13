<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Http\Resources\Products\ProductResource;
use App\Models\HourPrice;
use App\Models\Nomenclature;
use App\Models\NomenclatureProduct;
use App\Models\Product;
use App\Models\User;
use App\Notifications\NewProductNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Notification;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Traits\ApiResponser;
use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
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
            $products = QueryBuilder::for(Product::class)
                ->with('nomenclatures')
                ->allowedFilters([
                    AllowedFilter::partial('status'),
                    AllowedFilter::partial('type'),
                    AllowedFilter::partial('name'),
                ])
                ->defaultSort('-created_at')
                ->allowedSorts('name', 'created_at')
                ->paginate($limit)
                ->appends(request()->query());

            return ProductResource::collection($products)->additional($this->returnSuccessCollection(__('Products list'),200));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse | AnonymousResourceCollection
     */
    public function searchInApp(Request $request)
    {
        try {
            $products = [];
            $filter = $request->input('filter');
            if ($filter) {
                $filterName = $filter['name'];
                if ($filterName) {
                    $products = QueryBuilder::for(Product::class)
                        ->with('nomenclatures')
                        ->allowedFilters(['name'])
                        ->get();
                }
            }

            return ProductResource::collection($products)->additional($this->returnSuccessCollection(__('Products list'),200));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function mostPopular()
    {
        $products = Product::with('nomenclatures')->get();
        return ProductResource::collection($products)->additional($this->returnSuccessCollection(__('Most popular product list'),200));
    }


    /**
     * Store a newly created thumbnail in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function storeProductThumbnail(Request $request): JsonResponse
    {
        $validator = $this->validateProductThumbnail();

        if ($validator->fails()) {
            return $this->errorsResponse($validator->messages(), 422);
        } else {


            $thumbnail = $request->file('thumbnail');
            $name = Str::uuid();
            $folder = '/products';
            $thumbnailUrl = $this->uploadOne($thumbnail, $folder, $name);

            return $this->successResponse($thumbnailUrl, __('Product created'), 'success_thumbnail_created', 201);
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

        $validator = $this->validateProduct();
        if ($validator->fails()) {
            return $this->errorsResponse($validator->messages(), 422);
        } else {
            DB::beginTransaction();
            try {
                // Store product
                $product = Product::create([
                    'name' => $request['name'],
                    'description' => $request['description'],
                    'category_id' => $request['category_id'],
                    'status' => $request['status'],
                    'slug' => Str::slug($request['name']),
                    'type' => $request['type']
                ]);

                $variants = $request->input('variants');
                foreach ($variants as &$variant) {
                    $variant['product_id'] = $product->id;
                    $nomenclatureProduct = new NomenclatureProduct();
                    $nomenclatureProduct->fill($variant);
                    $nomenclatureProduct->save();

                    $hasTime = $variant['has_time'] ?? false;
                    $hoursPrices = $variant['hours_price'] ?? [];

                    if ($hasTime && !empty($hoursPrices)) {
                        foreach ($hoursPrices as &$hourPrice) {
                            $hourPrice['nomenclature_product_id'] = $nomenclatureProduct->id;
                            $hPrice = new HourPrice();
                            $hPrice->fill($hourPrice);
                            $hPrice->save();
                        }
                    }
                    $variant = $nomenclatureProduct;
                    $nomenclatureProduct = null;
                }

                $data = [
                    'product' => ProductResource::make($product->load('nomenclatures'))
                ];

//                NewMessage::dispatch('Producto registrado');
//                $admins = User::role(['admin'])->get();
//                Notification::send($admins, new newProductNotification($data));
                DB::commit();
                return $this->successResponse($data, __('Product created'), 'success_product_created', 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->errorResponse($e->getMessage(), 500);
            }
        }
    }


    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return ProductResource | JsonResponse
     */
    public function show(string $id)
    {
        try {
            $product = Product::with('nomenclatures')->findOrFail($id);
            return ProductResource::make($product)->additional($this->returnSuccessCollection(__('Product details'),200));
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(__('Product not found'), 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return JsonResponse | AnonymousResourceCollection
     */
    public function productsByCategory(Request $request)
    {
        $validator = $this->validateCategoryId();
        if ($validator->fails()) {
            return $this->errorsResponse($validator->messages(), 422);
        } else {
            try {
                $categoryId = $request['category_id'];
                $products = Product::where('products.category_id', $categoryId)
                    ->with('nomenclatures')
                    ->get();
                return ProductResource::collection($products)->additional($this->returnSuccessCollection(__('Products list by category'),200));
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), 500);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }

    /**
     * Validate the request
     * @return mixed
     */
    private function validateProduct()
    {
        return Validator::make(request()->all(), [
                'name' => 'required|string|max:128|unique:products,name',
                'description' => 'required|string|max:128',
                'category_id' => 'required',
                'status' => 'required'
            ]
        );
    }

    private function validateProductThumbnail()
    {
        return Validator::make(request()->all(), [
                'name' => 'required|string|max:128|unique:products,name',
                'description' => 'required|string|max:128',
                'category_id' => 'required',
                'status' => 'required',
                'thumbnail' => 'max:5120'
            ]
        );
    }

    private function validateCategoryId()
    {
        return Validator::make(request()->all(), [
                'category_id' => 'required'
            ]
        );
    }
}
