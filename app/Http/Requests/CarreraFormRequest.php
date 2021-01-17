<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarreraFormRequest extends FormRequest
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
            //
            'nombre' => 'required|min:5|max:50',
            //'nombre' => 'required',
        ];
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
            'nombre.unique' => 'El nombre de la carrera ya está en uso.',
            'nombre.required' => 'El nombre de la carrera no puede estar vacío.',
            'nombre.min' => 'El nombre de la carrera debe tener 5 caracteres mínimo.',
            'nombre.max' => 'El nombre de la carrera debe tener 50 caracteres máximo.',
            //'nombre' => 'required',
        ];
    }
}
