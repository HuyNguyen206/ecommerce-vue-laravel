<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressStoreRequest extends FormRequest
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
            'name' => 'required',
            'address_1' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
            'country_id' => 'required|exists:countries,id',
            'is_default' => ''
        ];
    }

    protected function prepareForValidation()
    {
       $this->merge([
          'is_default' => $this->boolean('is_default')
       ]);
    }
}
