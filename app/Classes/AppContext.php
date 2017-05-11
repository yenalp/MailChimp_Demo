<?php

namespace App\Classes;

use App\Formatters\BaseFormatter;

class AppContext
{
    const SOURCE_API = 'API';
    const SOURCE_CLI = 'CLI';
    const SOURCE_TESTING = 'TESTING';

    const ROLE_ADMIN = 'ADMIN';

    public $source = null;

    public $user = null;
    public $request = null;

    public $requestFormatter = null;
    public $responseFormatter = null;

    protected $accessLevelMap = [
        'SUPER_ADMIN' => 1,
        'SYSTEM' => 1,
        'ADMIN' => 2
    ];

    public function __construct($sourceName)
    {
        $this->source = $sourceName;
        $this->requestFormatter = new \App\Http\Formatters\ApiFormatter();
        $this->responseFormatter = new \App\Http\Formatters\ApiFormatter();
    }

    public function getResponseFormatter()
    {
        return $this->responseFormatter;
    }

    public function getRequestFormatter()
    {
        return $this->requestFormatter;
    }

    public function setFormatters($reqFormatter, $resFormatter)
    {
        $this->requestFormatter = $reqFormatter;
        $this->responseFormatter = $resFormatter;
    }

    public function getRole()
    {
        if ($this->user->user_type === 'ADMIN' || $this->user->user_type === 'SUPER_ADMIN') {
            return AppContext::ROLE_ADMIN;
        }
        
        return AppContext::ROLE_PUBLIC;
    }

    public function getAccessLevel()
    {
        return $this->accessLevelMap[$this->getRole()];
    }
}
