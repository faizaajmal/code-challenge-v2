<?php

namespace App\Support\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *   schema="UserStoreRequest",
 *   description="Create new user",
 *   required={"name", "email", "password"},
 *   @OA\Property(
 *      property="name",
 *      type="string",
 *      example="Jane Doe",
 *      description="User name",
 *      minLength=1,
 *      maxLength=191,
 *   ),
 *  @OA\Property(
 *      property="nick_name",
 *      type="string",
 *      example="JaneDoe",
 *      description="Nick name",
 *      minLength=3,
 *      maxLength=30,
 *   ),
 *   @OA\Property(
 *      property="email",
 *      type="string",
 *      minLength=1,
 *      maxLength=191,
 *      description="User email",
 *      example="JaneDoe@email.com",
 *   ),
 *   @OA\Property(
 *      property="password",
 *      type="string",
 *      minLength=1,
 *      maxLength=191,
 *      description="User Password",
 *      example="correct horse battery staple",
 *   ),
 * )
 */
class UserStoreRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name'      => 'required|string|max:191|min:1',
            'nick_name' => 'sometimes|string|max:30|min:5|unique:users,nick_name',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:8|max:191',
        ];
    }
}
