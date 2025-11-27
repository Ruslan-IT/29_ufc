<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestEvent extends FormRequest
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
	        'scheme_id' => 'required',
	        'date_start' => 'required|date',
	        'date_end' => 'required|date|after:date_start',
        ];
    }

	public function messages()
	{
		return [
			'*.required' => 'Это поле обязательное',
			'name.max' => 'Кол-во символов более 255',
			'image.mimes' => 'Формат файла не поддерживается',
			'image.max' => 'Размер файла слишком большой',
			'date_end.after' => 'Дата/время завершения события должна быть меньше даты/время начала события'
		];
	}
}
