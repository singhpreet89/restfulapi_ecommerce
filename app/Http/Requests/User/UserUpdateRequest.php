<?php

namespace App\Http\Requests\User;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        /** 
         * ! Laravel will generate an exception that this email already exists, when the user sends an Update request using his old email address as the new email address
         * Validation: Email must be unique, but the user sending the PUT/PATCH request can update his own email with his existing email 
         * If the new email is same as that of the previous email, then the User should to do so, because the PUT/PATCH request is IDEMPOTENT.
         */

        // Log::info($request->user);
        // Log::info(User::findOrFail($request->user));
        // Log::info(User::findOrFail($request->user)->name);
        $user = User::findOrFail($request->user);

        return [
            'name' => 'sometimes|required',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|min:6|confirmed',
            'admin' => 'sometimes|required|in:' . User::ADMIN_USER . ',' . User::REGULAR_USER,
        ];
    }
}
