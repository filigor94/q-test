<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'author_id' => 'required|integer',
            'title' => 'required|string|min:1|max:255',
            'release_date' => 'nullable|string',
            'description' => 'nullable|string|min:1|max:255',
            'isbn' => 'required|string',
            'format' => 'nullable|string',
            'number_of_pages' => 'nullable|integer',
        ];
    }
}
