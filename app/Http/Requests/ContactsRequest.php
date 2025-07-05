<?php

namespace App\Http\Requests;

use App\Rules\NameIsValid;
use App\Rules\PhoneIsValid;
use Illuminate\Foundation\Http\FormRequest;

class ContactsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $contactId = $this->route('id');
        
        return [
           'name'   => [ 'required', 'string', new NameIsValid()],
           'email'  => [ 'required', 'email', 'unique:contacts,email,' . $contactId ],
           'phone'  => [ 'required', 'string', new PhoneIsValid()],
        ];
    }
}
