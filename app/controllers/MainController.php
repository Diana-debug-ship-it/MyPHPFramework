<?php

namespace app\controllers;

class Main extends AppController {

//    public $layout = 'main';

    public function indexAction()
    {
//        echo 111;
//        $this->layout = false;
//        $this->layout = 'default';
//        $this->view = 'test';

        $name = 'AAAAAAAAAAAAAAAndrey';
        $hi = "Hello";
        $color = [
            'white'=>'белый',
            'black'=>'черный'
        ];
        $this->set(compact('name', 'hi', 'color'));
        //$this->set(['name'=>$name, 'hi'=>'Hello']);
    }
}