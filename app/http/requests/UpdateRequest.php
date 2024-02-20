<?php

namespace App\Http\Requests;

use App\Framework\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'root_cname_target' => 'string|required',
            'sub_cname_target' => 'string|required',
            'pagerule_destination_url' => 'string|required'
        ];
    }
}