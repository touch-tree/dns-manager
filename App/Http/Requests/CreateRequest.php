<?php

namespace App\Http\Requests;

use App\Framework\Http\FormRequest;

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
            'pagerule_destination_url' => 'required',
        ];
    }
}