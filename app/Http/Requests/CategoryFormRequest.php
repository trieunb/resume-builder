<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CategoryFormRequest extends Request
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
        $name = 'required|max:30';

        if ($this->has('id')) $name .= '|unique:categories,name,'.$this->has('id');

        return [
            'name' => $name
        ];
    }
}