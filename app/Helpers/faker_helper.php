<?php
require_once APPPATH . 'Libraries/Faker/src/autoload.php';

use Faker\Factory;  

function getFaker(){
    return Factory::create();
}