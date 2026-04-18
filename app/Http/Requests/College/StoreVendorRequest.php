<?php

namespace App\Http\Requests\College;

use Illuminate\Foundation\Http\FormRequest;

class StoreVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:Training,Certification,Logistics,Other,Syllabus'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'regex:/^[0-9]+$/', 'max:20'],
            'address' => ['nullable', 'string'],
        ];
    }
}
