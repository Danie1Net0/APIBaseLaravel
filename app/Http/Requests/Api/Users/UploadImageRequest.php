<?php

namespace App\Http\Requests\Api\Users;

use App\Rules\MaxFileSize;
use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
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
            'path_image' => ['required', 'file', 'image', new MaxFileSize(3)]
        ];
    }
}
