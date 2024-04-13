<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Google\Client as GoogleClient;
use Google\Service\Sheets;

class SheetsApiController extends Controller
{
    protected $googleClient;

    public function __construct(GoogleClient $googleClient)
    {
        $this->googleClient = $googleClient;
    }

    public function testFunction()
    {
        return "testFunction used..";
    }
}
