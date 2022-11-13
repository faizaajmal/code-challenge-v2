<?php

namespace App\Support\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *   schema="NicknameUpdateRequest",
 *   description="Nickname Update Request Body",
 *   @OA\Property(
 *      property="nick_name",
 *      type="string",
 *      example="Jane Doe",
 *      description="Nick name",
 *      minLength=3,
 *      maxLength=30,
 *   ),
 * )
 *
 * Get the validation rules that apply to the request.
 *
 * @return array
 */
class NicknameUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nick_name' => 'sometimes|string|max:30|min:3|unique:users,nick_name,' . request()->route('user')->id,
        ];
    }
}
