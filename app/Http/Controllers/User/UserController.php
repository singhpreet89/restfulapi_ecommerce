<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserCollection;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(20);
        return UserCollection::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\UserStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();
        $data['admin'] = User::REGULAR_USER;

        $user = User::create($data);    // Create is used in Mass assignment while Save() can be used without mass assignment and in the Update method
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UserUpdateRequest  $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {   
        // TODO: Move these functions to the Service class
        if($request->has('name')) {
            $user->name = $request->name;
        }

        if($request->has('email') && $user->email != $request->email) {
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        }

        if($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        if($request->has('admin')) {
            if(!$user->isVerified()) {
                return response([
                    'message' => 'Not verified.',
                    'errors' => [
                        'admin' => [
                            'Only verified users can modify the admin field.'
                        ]
                    ]
                ], Response::HTTP_FORBIDDEN); 
            }
            $user->admin = $request->admin;
        }

        $user->save();
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return new UserResource($user);
    }
}
