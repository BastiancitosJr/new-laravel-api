<?php

namespace App\Http\Requests\Resources\Create;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Rules\ValidRut;

class OperatorRequest extends FormRequest
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
            'username' => ['sometimes', 'required', 'unique:users'],
            'password' => ['required_with:username', 'min:5'],
            'name' => ['required'],
            'rut' => ['required', 'unique:operators', new ValidRut()],
            'name_area' => ['required'],
            'name_line' => ['required'],
        ];
    }

    /**
     * Custom error messages for validation.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'username.required' => 'El nombre de usuario es requerido',
            'username.unique' => 'El nombre de usuario ya ha sido registrado',
            'password.required_with' => 'La contraseña es requerida',
            'password.min' => 'La contraseña debe tener al menos :min caracteres',
            'name.required' => 'El nombre es requerido',
            'rut.required' => 'El RUT es requerido',
            'rut.unique' => 'El RUT ya ha sido registrado',
            'name_area.required' => 'El nombre del area es requerido',
            'name_line.required' => 'El nombre de la linea es requerido',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}
