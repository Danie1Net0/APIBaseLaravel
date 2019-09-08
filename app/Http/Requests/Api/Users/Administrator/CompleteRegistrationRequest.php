<?php

namespace App\Http\Requests\Api\Users\Administrator;

use Illuminate\Foundation\Http\FormRequest;

class CompleteRegistrationRequest extends FormRequest
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
            'email'            => ['required', 'string', 'email'],
            'name'             => ['string', 'required', 'max:50'],
            'last_name'        => ['string', 'required', 'max:50'],
            'password'         => ['required', 'string', 'min:8', 'max:20', 'confirmed'],
            'activation_token' => ['required', 'string']
        ];
    }
}
