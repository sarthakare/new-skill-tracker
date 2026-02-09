<?php

namespace App\Http\Requests\College;

use Illuminate\Foundation\Http\FormRequest;

class AssignEventUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'role' => ['required', 'in:Event Admin,Trainer,Judge,Coordinator,Participant'],
        ];
    }
}
