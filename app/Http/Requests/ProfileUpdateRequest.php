<?php

namespace App\Http\Requests;

use App\Enums\UserLevel;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($this->user()->id)],
            'place_of_work' => ['nullable', 'string', 'max:255'],
            'level' => ['nullable|integer'],
            'country' => ['nullable', 'string', 'max:255'],
            'tech_stack' => ['nullable', 'string', 'max:255'],
            'age' => ['nullable', 'integer', 'min:0'],
            'about' => ['nullable', 'string'],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
