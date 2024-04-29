<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParseFileRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => 'required|file',
            'source_type' => 'required|string|in:CCNX,SomeOther',
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'File is required.',
            'file.file' => 'Uploaded file is not valid.',
            'source_type.required' => 'Source type is required.',
            'source_type.string' => 'Source type must be a string.',
            'source_type.in' => 'Invalid source type.',
        ];
    }
}
