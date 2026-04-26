<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSyllabusAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $languages = $this->input('languages_supported');
        if (! is_array($languages)) {
            $languages = [];
        }
        $languages = array_values(array_filter($languages, static fn ($v) => $v !== null && $v !== ''));

        $this->merge([
            'languages_supported' => $languages,
            'starter_code' => $this->filled('starter_code') ? $this->input('starter_code') : null,
            'test_cases' => $this->filled('test_cases') ? $this->input('test_cases') : null,
            'expected_output' => $this->filled('expected_output') ? $this->input('expected_output') : null,
            'time_limit' => $this->filled('time_limit') ? $this->input('time_limit') : null,
            'starts_on' => $this->filled('starts_on') ? $this->input('starts_on') : null,
            'ends_on' => $this->filled('ends_on') ? $this->input('ends_on') : null,
        ]);
    }

    public function rules(): array
    {
        $allowedLanguageIds = array_column(config('judge0.languages', []), 'id');

        return [
            'type' => ['required', 'string', Rule::in(['assignment', 'problem', 'quiz'])],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'starter_code' => ['nullable', 'string'],
            'test_cases' => ['nullable', 'string'],
            'expected_output' => ['nullable', 'string'],
            'time_limit' => ['nullable', 'integer', 'min:1', 'max:600'],
            'languages_supported' => ['nullable', 'array'],
            'languages_supported.*' => ['integer', Rule::in($allowedLanguageIds)],
            'starts_on' => ['nullable', 'date'],
            'ends_on' => ['nullable', 'date'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $start = $this->input('starts_on');
            $end = $this->input('ends_on');
            if ($start && $end && $end < $start) {
                $validator->errors()->add('ends_on', 'End date must be on or after the start date.');
            }
        });
    }
}
