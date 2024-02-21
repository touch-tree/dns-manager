<?php

namespace App\Http\Requests;

use App\Framework\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'domain' => 'string|required',
            'root_cname_target' => 'string|required',
            'sub_cname_target' => 'string|required',
            'pagerule_url' => 'string|required',
            'pagerule_full_url' => 'string|required',
            'pagerule_destination_url' => 'string|required',
        ];
    }
}