<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
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
            'message' => 'string|nullable',
            'group_id' => 'required_without:receiver_id|nullable|integer|exists:groups,id',
            'receiver_id' => 'required_without:group_id|nullable|integer|exists:users,id',
            'attachments' => 'nullable|array|max:10',
            'attachments.*' => 'file|mimes:jpeg,jpg,png,gif,mp4,webm,ogg,mp3,wav,flac,txt,pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar|max:1024000',
        ]; 
    }
}
