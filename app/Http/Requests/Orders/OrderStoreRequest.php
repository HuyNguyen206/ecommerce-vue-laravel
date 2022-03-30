<?php

namespace App\Http\Requests\Orders;

use App\Models\Address;
use App\Rules\ValidShippingMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderStoreRequest extends FormRequest
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
           'address_id' => ['required', 'exists:addresses,id', function($attribute, $value, $fail){
                if (!$this->user()->addresses()->where('id',$value)->exists()) {
                    $fail('This address doesnt belong to you');
                }
            }],
            'shipping_method_id' => ['required', Rule::exists('shipping_methods', 'id'),
                new ValidShippingMethod($this->address_id)]
        ];
    }
}
