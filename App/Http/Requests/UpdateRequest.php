<?php

namespace App\Http\Requests;

use App\Framework\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'root_cname_target' => 'required',
            'sub_cname_target' => 'required',
            'pagerule_forwarding_url' => 'required'
        ];
    }
}