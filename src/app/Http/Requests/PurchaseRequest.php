<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $item = $this->route('item');

        if ($item) {
            $address = session("purchase_address.{$item->id}");

            if ($address) {
                $this->merge([
                    'postcode' => $address['postcode'] ?? null,
                    'address'  => $address['address'] ?? null,
                ]);
            }
        }
    }


    public function rules()
    {
        return [
            'payment_method' => 'required|in:convenience,card',
            'postcode' => 'required',
            'address' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'payment_method.required' => '支払い方法を選択してください。',
            'payment_method.in' => '支払い方法が正しくありません。',
            'postcode.required' => '配送先住所が設定されていません。',
            'address.required' => '配送先住所が設定されていません。',
        ];
    }
}
