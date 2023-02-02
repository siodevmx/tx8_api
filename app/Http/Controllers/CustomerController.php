<?php

namespace App\Http\Controllers;

use App\Http\Resources\User\UserResource;
use App\Mail\newCustomer;
use App\Mail\verifyEmail;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\UserVerification;
use App\Notifications\NewCustomerNotification;
use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Traits\ApiResponser;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
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
            $customers = QueryBuilder::for(User::role(['customer']))
                ->with('userDetails')
                ->allowedFilters([
                    AllowedFilter::scope('search'),
                    AllowedFilter::partial('status', 'userDetails.status'),
                    AllowedFilter::partial('identification_verified', 'userDetails.identification_verified')
                ])
                ->orWhere('email')
                ->defaultSort('-created_at')
                ->allowedSorts([
                    AllowedSort::field('name', 'userDetails.name'),
                    AllowedSort::field('created_at'),
                ])
                ->paginate($limit)
                ->appends(request()->query());

            return UserResource::collection($customers)->additional($this->returnSuccessCollection(__('Customers list'), 200));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $validator = $this->validateCustomer();
        if ($validator->fails()) {
            return $this->errorsResponse($validator->messages(), 422);
        } else {

            $admins = User::role(['admin'])->get();


            DB::beginTransaction();
            try {
                $customer = User::create([
                    'email' => $request['email'],
                    'password' => bcrypt($request['password']),
                ])->assignRole('customer');

                // Get image file
                $image = $request->file('identification');
                // Make an image name based on username and current timestamp
                $name = Str::slug($request['name']) . '_' . time();
                // Define folder path
                $folder = '/users/identification_validation';
                // Upload image
                $imageUrl = $this->uploadOne($image, $folder, $name);

                $userDetails = UserDetails::create([
                    'user_id' => $customer->id,
                    'name' => $request['name'],
                    'surnames' => $request['surnames'],
                    'phone' => $request['phone'],
                    'identification' => $imageUrl,
                ]);

                $userDetails->email = $customer->email;
                Mail::to($userDetails)->send(new newCustomer($userDetails));
                DB::commit();
                return $this->successResponse($customer, __('Customer created'), 'success_customer', 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->errorResponse($e->getMessage(), 500);
            }
        }
    }


    /**
     * Verifica los primeros datos del usuario
     * @return JsonResponse
     */
    public function checkFirstStep()
    {
        $validator = $this->validateCustomerData();
        if ($validator->fails()) {
            return $this->errorsResponse($validator->messages(), 422);
        }

        return $this->successResponse(null, __('Correct verification'), 'success_verification', 200);
    }

    /**
     * Get the authenticated Customer.
     * @return UserResource | JsonResponse
     */
    public function me()
    {
        try {
            $user = User::with('userDetails')->findOrFail(auth()->id());
            return UserResource::make($user)->additional($this->returnSuccessCollection(__('Customer details'), 200));
        } catch (ModelNotFoundException $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return UserResource | JsonResponse
     */
    public function show(String $id)
    {
        try {
            $user = User::with('userDetails')->whereHas('roles', function ($q) use ($id) {
                $q->where('name', 'customer');
            })->where('id', $id)->firstOrFail();

            return UserResource::make($user)->additional($this->returnSuccessCollection(__('Customer details'), 200));
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(__('Customer not found'), 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        //
    }

    public function sendVerifyEmail(Request $request)
    {
        $validator = Validator::make(request()->all(), [
                'email' => 'required|string|email',
            ]
        );

        if ($validator->fails()) {
            return $this->errorsResponse($validator->messages(), 422);
        } else {
            try {
                $email = $request['email'];
                $userCustomer = User::with('userDetails')->where('email', $email)->first();

                if (is_null($userCustomer)) {
                    return $this->errorResponse(__('Customer not found'), 404);
                } else {
                    // check if code has already sent
                    $userCurrentVerification = $userCustomer->userVerification;
                    $key = random_int(0, 999999);
                    $verification_code = str_pad($key, 6, 0, STR_PAD_LEFT);

                    //if is null send new code
                    if (is_null($userCurrentVerification)) {
                        UserVerification::create([
                            'user_id' => $userCustomer->id,
                            'verification_code' => $verification_code
                        ]);
                    } else {
                        //If the code exist, update the older one
                        $userCurrentVerification->verification_code = $verification_code;
                        $userCurrentVerification->save();
                    }
                    $userCustomer->verification_code = $verification_code;
                    Mail::to($userCustomer)->send(new verifyEmail($userCustomer));
                    return $this->successResponse(null, __('The code has been sent. please check your email'), 'success_sent_email', 201);
                }
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), 500);
            }
        }
    }

    public function verifyEmail(Request $request, int $verification_code)
    {
        $validator = Validator::make(request()->all(), [
                'email' => 'required|string|email',
            ]
        );

        if ($validator->fails()) {
            return $this->errorsResponse($validator->messages(), 422);
        } else {
            try {
                $email = $request['email'];
                $user = User::with('userDetails')->where('email', $email)->first();

                if (!is_null($user)) {
                    $userVerificationCode = UserVerification::where('verification_code', $verification_code)->first();

                    if (!is_null($userVerificationCode)) {
                        //Check whether the user owns the verification code
                        if ($user->id == $userVerificationCode->user_id) {
                            if ($user->is_email_verified == 1) {
                                return $this->successResponse(null, __('Email already verified'), 'already_email_verified');
                            }

                            $user->update(['is_email_verified' => 1]);
                            $userVerificationCode->delete();
                            return $this->successResponse(null, __('You have successfully verified your email address'), 'success_email_verified');
                        }
                    } else {
                        if ($user->is_email_verified == 1) {
                            return $this->successResponse(null, __('Email already verified'), 'already_email_verified');
                        }
                    }
                    return $this->errorResponse(__('Verification code is invalid'), 404);
                }
                return $this->errorResponse(__('Customer not found'), 404);
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), 500);
            }
        }
    }

    /**
     * @param int $id
     * Update the status of identificacion
     */
    public function verifyIdentification(Request $request, string $id)
    {

        $validator = $this->validateAllowOrDenny();
        if ($validator->fails()) {
            return $this->errorsResponse($validator->messages(), 422);
        } else {
            try {
                $user = User::with('userDetails')
                    ->whereHas('roles', function ($q) use ($id) {
                        $q->where('name', 'customer');
                    })->where('id', $id)->first();

                if (is_null($user)) {
                    return $this->errorResponse(__('Customer not found'), 404);
                } else {
                    $userResource = UserResource::make($user);
                    $userDetails = $userResource->userDetails;
                    $identification = $userResource->userDetails->identification;

                    // si ya se verificó.
                    if (is_null($identification)) {
                        return $this->successResponse(UserResource::make($user), __('Identification has already been verified'), 'success_verification', 201);
                    } // si no se ha verficado
                    else {
                        $deleted = $this->deleteOne($identification);
                        if ($deleted) {
                            $authorize = $request->input('authorize');
                            $userDetails->identification_verified = $authorize;
                            $userDetails->identification = null;
                            $userDetails->save();

                            $userUpdated = User::with('userDetails')
                                ->whereHas('roles', function ($q) use ($id) {
                                    $q->where('name', 'customer');
                                })->where('id', $id)->first();

                            return $this->successResponse(UserResource::make($userUpdated), __('verified ID'), 'success_verification_id', 201);
                        } else {
                            return $this->errorResponse(__('An error occurred while verifying the id'), 500);
                        }
                    }
                }
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), 500);
            }
        }
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
    private function validateCustomer()
    {
        return Validator::make(request()->all(), [
                'email' => 'required|string|email|unique:users,email',
                'identification' => 'required|image|mimes:jpeg,png,jpg|max:10240',
                'phone' => 'string|unique:user_details,phone'
            ]
        );
    }

    /**
     * Validate the request
     * @return mixed
     */
    private function validateCustomerData()
    {
        return Validator::make(request()->all(), [
                'password' => 'required|confirmed|min:8',
                'name' => 'required|string|max:255',
                'surnames' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users,email',
                'phone' => 'string|unique:user_details,phone',
            ]
        );
    }

    /**
     * Validate the request
     * @return mixed
     */
    private function validateAllowOrDenny()
    {
        return Validator::make(request()->all(), [
                'authorize' => 'required|boolean',
            ]
        );
    }


}
