<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
      
            return [
                'name' => 'sometimes|string|max:255',
                'username' => [
                    'sometimes',
                    'string',
                    'max:255',
                    Rule::unique('users')->ignore($this->user()->id),
                ],
                'email' => [
                    'sometimes',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($this->user()->id),
                ],
                'password' => 'sometimes|string|min:8|confirmed',
            ];
        
    }
}
