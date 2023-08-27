<?php

namespace app\controllers;

use app\models\Main;

class MainController extends AppController
{

    public function indexAction()
    {
        $model = new Main();
//        $res = $model->query("CREATE TABLE posts SELECT * FROM guest_book_db.users");
        $posts = $model->findAll();
        $post = $model->findOne('User2');
        $data1 = $model->findBySql("SELECT * FROM {$model->table} ORDER BY id DESC LIMIT 2");
        $data2 = $model->findBySql("SELECT * FROM {$model->table} WHERE {$model->pk} LIKE ?",
                             ['%23%']);
        debug($data2);
        $title = 'PAGE TITLE';
        $this->set(compact('title', 'posts'));
    }
}