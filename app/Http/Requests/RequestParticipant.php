<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestParticipant extends FormRequest
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
	        'name' => 'required|max:255',
	        'image' => 'mimes:jpeg,png,bmp,tiff|max:2048',
	        'image_large' => 'mimes:jpeg,png,bmp,tiff|max:2048'
        ];
    }

	public function messages()
	{
		return [
			'*.required' => 'Это поле обязательное',
			'name.max' => 'Кол-во символов более 255',
			'image.mimes' => 'Формат файла не поддерживается',
			'image.max' => 'Размер файла слишком большой',
			'image_large.mimes' => 'Формат файла не поддерживается',
			'image_large.max' => 'Размер файла слишком большой'
		];
	}
}
