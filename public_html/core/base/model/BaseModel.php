<?php

namespace core\base\model;

use core\base\controller\SingleTone;
use core\base\exceptions\DbException;

class BaseModel extends BaseModelMethods
{
    use SingleTone;

    protected $db;

    private function __construct()
    {
        $this->db = @new \mysqli(HOST, USER, PASS, DB_NAME);

        if ($this->db->connect_error) {
            throw new DbException('Error connection in database:' . $this->db->connect_errno . ' ' . $this->db->connect_error);
        }

        $this->db->query('SET NAMES UTF8');
    }

    final public function query($query, $crud = 'r', $return_id = false)
    {
        $result = $this->db->query($query);

        if ($this->db->affected_rows === -1) {
            throw new DbException('Error in SQL: ' . $query . ' - ' . $this->db->errno . ' ' . $this->db->error);
        }

        switch ($crud) {
            case 'r':
                if ($result->num_rows) {
                    $res = [];

                    for ($i = 0; $i < $result->num_rows; $i++) {
                        $res[] = $result->fetch_assoc();
                    }
                    return $res;
                }
                return false;
                break;

            case 'c':

                if ($return_id) return $this->db->insert_id;

                return true;
                break;

            default:
                return true;
                break;
        }
    }

    final public function get($table, $set = [])
    {

        $fields = $this->createFields($set, $table);

        $order = $this->createOrder($set, $table);

        $where = $this->createWhere($set, $table);

        if (!$where) $new_where = true;
        else $new_where  = false;

        $join_arr = $this->createJoin($set, $table, $new_where);

        $fields .= $join_arr['fileds'];
        $join = $join_arr['join'];
        $where .= $join_arr['where'];

        $fields = rtrim($fields, ',');


        $limit = $set['limit'] ? 'LIMIT ' . $set['limit'] : '';

        $query = "SELECT $fields FROM $table $join $where $order $limit";
        return $this->query($query);
    }

}
