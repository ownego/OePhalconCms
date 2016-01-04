<?php

namespace App\Modules\Backend\Controllers;

class ErrorController extends BaseController
{
    public function initialize()
    {
        $this->tag->setTitle('Oops!');
    }

    public function show404Action()
    {

    }

    public function show401Action()
    {

    }

    public function show500Action()
    {

    }

}