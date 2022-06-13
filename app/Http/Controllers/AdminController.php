<?php

namespace App\Http\Controllers;

use App\Mail\newAdmin;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Mail;


class AdminController extends Controller
{
    use ApiResponser;

    public function __construct()
    {
        $this->middleware(['role:admin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Display a listing of notifications
     *
     * @return JsonResponse
     */
    public function getNotifications()
    {
        try {
            $notifications = auth()->user()->unreadNotifications;
            return $this->successResponse($notifications, 'Notifications', 'success_notification', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function markNotification(Request $request)
    {
        $notificationId = request('notification_id');
        $userUnreadNotification = auth()->user()
            ->unreadNotifications
            ->where('id', $notificationId)
            ->first();

        if ($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
        }
    }

    /**
     * Store a newly created Admin in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $validator = $this->validateAdmin();
        if ($validator->fails()) {
            return $this->errorsResponse($validator->messages(), 422);
        } else {
            DB::beginTransaction();
            try {
                $temp_password = Str::random(8);
                $userAdmin = User::create([
                    'email' => $request['email'],
                    'password' => bcrypt($temp_password),
                ])->assignRole('admin');

                $userDetails = UserDetails::create([
                    'id' => $userAdmin->id,
                    'name' => $request['name'],
                    'surnames' => $request['surnames'],
                    'phone' => $request['phone'],
                ]);

                $userDetails->email = $userAdmin->email;
                $userDetails->temp_pass = $temp_password;

                Mail::to($userDetails)->send(new newAdmin($userDetails));
                DB::commit();
                return $this->successResponse($userAdmin, __('Admin created'), 'success_admin', 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->errorResponse($e->getMessage(), 500);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
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
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Validate the request
     * @return mixed
     */
    private function validateAdmin()
    {
        return Validator::make(request()->all(), [
                'name' => 'required|string|max:255',
                'surnames' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users,email',
                'phone' => 'required|numeric|digits:10|unique:user_details,phone'
            ]
        );
    }
}
