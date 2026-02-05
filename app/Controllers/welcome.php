<?php

namespace App\Controllers;

class welcome extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }
}
