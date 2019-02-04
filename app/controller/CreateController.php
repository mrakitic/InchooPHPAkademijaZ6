<?php

class CreateController
{
    public function create()
    {
        $view = new View();
        $view-> render('create');
    }
}