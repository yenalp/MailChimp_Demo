<?php namespace App\CustomValidator;

use Illuminate\Http\Request;

class MailChimpValidator extends BaseModelValidator
{
    public function rules()
    {
        return [
            'permission_reminder' => 'required',
            'email_type_option' => 'required',
            'name' => 'required',
            'campaign_defaults' => 'required',
            'campaign_defaults' => [
                'from_email' => 'required',
                'language' => 'required',
                'subject' => 'required',
                'from_name' => 'required',
            ],
            'contact' => 'required',
            'contact' => [
                'phone' => 'required',
                'city' => 'required',
                'address1' => 'required',
                'country' => 'required',
                'company' => 'required',
                'zip' => 'required',
                'state' => 'required',
                'address2' => 'required'
            ]

        ];
    }

    public function message()
    {
        return [
            'name' => 'Name id required'
        ];
    }
}
