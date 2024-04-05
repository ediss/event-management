<?php

namespace App\Http\Requests\Attendee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttendeeRequest extends FormRequest
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
            'user_id' => [
                'required',
                Rule::unique('attendees')->where('event_id', $this->event->id)
            ],
        ];
    }

    public function messages():array
    {
        $event = $this->event->name;
        return [
            'user_id.unique' => "Allready attend Event $event"
        ];
        
    }
    

}
