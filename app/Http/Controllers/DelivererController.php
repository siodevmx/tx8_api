<?php

namespace App\Http\Controllers;

use App\Mail\newDeliverer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Traits\ApiResponser;

class DelivererController extends Controller
{
    use ApiResponser;

    public function __construct()
    {
        $this->middleware(['role:admin']);
    }

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
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = $this->validateDeliverer();
            if ($validator->fails()) {
                return $this->errorResponse($validator->messages(), 422);
            }

            $temp_password = Str::random(8);
            $deliverer = User::create([
                'name' => $request['name'],
                'surnames' => $request['surnames'],
                'password' => bcrypt($temp_password),
                'email' => $request['email'],
                'phone' => $request['phone']
            ]);

            $deliverer->temp_pass = $temp_password;
            $deliverer->assignRole('deliverer');

            Mail::to($deliverer)->send(new newDeliverer($deliverer));

            return $this->successResponse($deliverer, 'Deliverer created', 'success_deliverer', 201);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Validate the request
     * @return mixed
     */
    private function validateDeliverer()
    {
        return Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'surnames' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'phone' => 'required|numeric|digits:10|unique:users,phone'
        ]);
    }
}
