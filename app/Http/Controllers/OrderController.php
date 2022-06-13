<?php

namespace App\Http\Controllers;

use App\Models\Nomenclature;
use App\Models\NomenclatureProduct;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;


class OrderController extends Controller
{

    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    public function validateCart(Request $request)
    {
        $requested_variants = $request->input('requested_variants');

        $db_variants = [];
        $some_without_stock = false;

        foreach ($requested_variants as $requested_variant) {

            $variant = NomenclatureProduct::find($requested_variant['id']);

            if ($variant) {
                $nomenclature = Nomenclature::find($variant->nomenclature_id);
                $product = Product::find($variant->product_id);
                $variantsWithInfo = [
                    'variant_id' => $requested_variant['id'],
                    'product_name' => $product->name,
                    'nomenclature_name' => $nomenclature->name,
                    'nomenclature_value' => $variant->nomenclature_value,
                    'price' => $variant->price,
                    'app_quantity' => (int)$requested_variant['quantity'],
                    'current_stock' => (int)$variant->stock,
                    'in_stock' => $requested_variant['quantity'] <= $variant->stock
                ];
                $db_variants[] = $variantsWithInfo;
            }
        }


        $requested_variants_length = count($requested_variants);
        $db_variants_length = count($db_variants);
        $code_name = '';


        if ($db_variants_length > 0) {
            foreach ($db_variants as $db_variant) {
                if (!$db_variant['in_stock']) {
                    $some_without_stock = true;
                    break;
                }
            }
        }


        if ($db_variants_length <= 0) {
            $message = 'Uno o más de tus productos ya se han agotado.';
            $code_name = 'empty_list';
        } elseif ($requested_variants_length > $db_variants_length) {
            $message = 'Uno o más de tus productos ya se han agotado.';
            $code_name = 'incomplete_list';
        } elseif ($some_without_stock) {
            $message = 'La cantidad de productos se han actualizado.';
            $code_name = 'changed_list';
        } else {
            $message = 'Productos completos';
            $code_name = 'full_list';
        }


        return $this->successResponse($db_variants, $message, $code_name, 201);
    }
}
