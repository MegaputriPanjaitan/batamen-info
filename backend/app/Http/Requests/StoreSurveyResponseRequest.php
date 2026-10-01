<?php

namespace App\Http\Requests;

use App\Models\StaffMember;
use App\Models\SurveyResponse;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

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
        $serviceSlugs = array_keys(config('public_services'));

        return [
            'respondent_phone' => [
                'required',
                'string',
                'regex:/^08[0-9]{8,13}$/',
            ],
            'respondent_phone_hash' => ['required', 'string', 'size:64'],
            'service_slug' => ['required', 'string', Rule::in($serviceSlugs)],
            'staff_member_id' => ['required', 'integer', Rule::exists('staff_members', 'id')->where('is_active', true)],
            'ratings' => ['required', 'array', 'size:14'],
            'ratings.*.question_key' => ['required', 'string', 'distinct', 'regex:/^q([1-9]|1[0-4])$/'],
            'ratings.*.score' => ['required', 'integer', 'between:1,5'],
        ];
    }

    public function messages(): array
    {
        return [
            'respondent_phone.regex' => 'Masukkan nomor HP Indonesia yang valid, misalnya 081234567890.',
            'service_slug.required' => 'Pilih jenis layanan yang Anda terima.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $staffMember = StaffMember::query()->find($this->integer('staff_member_id'));

            if ($staffMember && ! in_array($this->string('service_slug')->toString(), $staffMember->service_slugs ?? [], true)) {
                $validator->errors()->add('staff_member_id', 'Petugas tersebut tidak melayani jenis layanan yang dipilih.');
            }

            if (! $validator->errors()->hasAny(['respondent_phone', 'service_slug', 'staff_member_id'])
                && SurveyResponse::query()
                    ->where('respondent_phone_hash', $this->input('respondent_phone_hash'))
                    ->where('service_slug', $this->input('service_slug'))
                    ->where('staff_member_id', $this->integer('staff_member_id'))
                    ->whereDate('survey_date', today())
                    ->exists()) {
                $validator->errors()->add('respondent_phone', 'Nomor HP ini sudah menilai petugas yang sama untuk layanan tersebut hari ini.');
            }
        });
    }

    protected function prepareForValidation(): void
    {
        $phone = preg_replace('/\D+/', '', (string) $this->input('respondent_phone')) ?? '';

        if (str_starts_with($phone, '62')) {
            $phone = '0'.substr($phone, 2);
        } elseif (str_starts_with($phone, '8')) {
            $phone = '0'.$phone;
        }

        $this->merge([
            'respondent_phone' => $phone,
            'respondent_phone_hash' => hash_hmac('sha256', $phone, (string) config('app.key')),
        ]);
    }
}
