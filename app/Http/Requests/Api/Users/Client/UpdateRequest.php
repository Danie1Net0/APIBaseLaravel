<?php

namespace App\Http\Requests\Api\Users\Client;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name'      => ['string', 'required', 'max:50'],
            'last_name' => ['string', 'required', 'max:50'],
            'city'      => ['string', 'required', 'max:35'],
            'state'     => ['string', 'required', 'min:2', 'max:2']
        ];
    }
}
