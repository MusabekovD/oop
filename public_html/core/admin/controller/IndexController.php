<?php

namespace core\admin\controller;

use core\base\controller\BaseController;
use core\admin\model\Model;

class IndexController extends BaseController
{
    protected function inputData()
    {
        $db = Model::instance();

        $table = 'teachers';
        $color = ['red', 'blue', 'black'];

        $res = $db->get($table, [
            'fields' => ['id', 'name'],
            'where' => ['name' => 'masha', 'dasha'],
            'operand' => ['IN', 'LIKE', '<>', '='],
            'condition' => ['AND'],
            'order' => ['name'],
            'order_direction' => ['DESC'],
            'limit' => '1',
        ]);
        exit("I'm admin panel");
    }
}
