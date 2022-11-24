<?php

namespace App\Http\Requests\Cryptocurrency;

use App\Enums\Cryptocurrency\EnumCoin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CoinCurrentPriceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'coin' => ['required', 'string', Rule::in(array_column(EnumCoin::cases(), 'value'))],
        ];
    }
}
