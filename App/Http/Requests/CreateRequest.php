<?php

namespace App\Http\Requests;

use Framework\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'domain' => 'required',
            'root_cname_target' => 'required',
            'sub_cname_target' => 'required',
            'pagerule_url' => 'required',
            'pagerule_full_url' => 'required',
            'pagerule_forwarding_url' => 'required',
        ];
    }
}