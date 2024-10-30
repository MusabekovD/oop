<?php

namespace core\admin\controller;

use core\base\controller\BaseController;
use core\admin\model\Model;

class IndexController extends BaseController
{
    protected function inputData()
    {
        $db = Model::instance();

        $table = 'articles';

        $res = $db->delete($table, [
            'where' => ['id' => 10]
            //where method is not working
        ]);

        exit('id =' . $res['id'] . ' Name = ' . $res['name']);
    }
}
