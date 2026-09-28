<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSurveyResponseRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'staff_member_id' => ['required', 'integer', Rule::exists('staff_members', 'id')->where('is_active', true)],
            'ratings' => ['required', 'array', 'size:14'],
            'ratings.*.question_key' => ['required', 'string', 'distinct', 'regex:/^q([1-9]|1[0-4])$/'],
            'ratings.*.score' => ['required', 'integer', 'between:1,5'],
        ];
    }
}
