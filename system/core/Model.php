<?php

namespace system\core;

class Model {

    var $db;
    var $error;
    private $fix = '';
    private $field = '*';
    private $where;
    private $join = [];
    private $order;
    private $limit;
    private $group;
    private $having;

    public function __construct() {
        $dbconfig = config('database');
        $this->db = new \PDO(sprintf('%s:host=%s;dbname=%s;charset=%s', $dbconfig['type'], $dbconfig['host'], $dbconfig['dbname'], $dbconfig['charset']
                ), $dbconfig['username'], $dbconfig['password']);
        $dbconfig['fix'] && $this->table = $dbconfig['fix'] . $this->table;
    }

    private function query() {
        $sql = sprintf('select %s from %s %s %s %s %s %s %s', $this->field, $this->table, implode(' ', $this->join), $this->where, $this->group, $this->having, $this->order, $this->limit
        );
        $this->initParam();
        return $this->db->query($sql);
    }

    private function initParam() {
        $this->field = '*';
        $this->where = null;
        $this->join = [];
        $this->group = null;
        $this->having = null;
        $this->order = null;
        $this->limit = null;
    }

    public function select() {
        $result = $this->query();
        if (FALSE === $result) {
            $this->setError();
            return false;
        }
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function count() {
        $this->field = 'count(*)';
        $result = $this->query();
        if (FALSE === $result) {
            $this->setError();
            return false;
        }
        return $result->fetch(\PDO::FETCH_ASSOC)['count(*)'];
    }

    public function find() {
        $result = $this->query();
        if (FALSE === $result) {
            $this->setError();
            return false;
        }
        return $result->fetch(\PDO::FETCH_ASSOC);
    }

    public function add($data) {
        $keys = array_keys($data);
        $values = array_values($data);
        $sql = sprintf('insert into %s(%s) values%s', $this->table, implode(',', $keys), '("' . implode('","', $values) . '")'
        );
        $result = $this->db->exec($sql);
        if (FALSE === $result) {
            $this->setError();
            return false;
        }
        return $result;
    }

    public function addAll($data) {
        $keys = null;
        $values = [];
        foreach ($data as $val) {
            !$keys && $keys = array_keys($val);
            $values[] = '("' . implode('","', $val) . '")';
        }
        $sql = sprintf('insert into %s(%s) values%s', $this->table, implode(',', $keys), implode(',', $values)
        );
        $result = $this->db->exec($sql);
        if (FALSE === $result) {
            $this->setError();
            return false;
        }
        return $result;
    }

    public function delete() {
        $sql = sprintf('delete from %s %s %s %s', $this->table, $this->where, $this->order, $this->limit);
        $result = $this->db->exec($sql);
        if (FALSE === $result) {
            $this->setError();
            return false;
        }
        return $result;
    }

        public function update($data, $flag = true) {
        $queryData = [];
        foreach ($data as $key => $val) {
            if($flag){
                $queryData[] = sprintf('%s="%s"', $key, $val);
            }else{
                $queryData[] = sprintf('%s=%s', $key, $val);
            }
        }
        $sql = sprintf('update %s set %s %s %s %s', 
                $this->table, 
                implode(',', $queryData), 
                $this->where, 
                $this->order, 
                $this->limit
        );
        $result = $this->db->exec($sql);
        if (FALSE === $result) {
            $this->setError();
            return false;
        }
        return $result;
    }

    public function table($table) {
        $this->table = $table;
        return $this;
    }

    public function field($field) {
        $this->field = $field;
        return $this;
    }

    public function where($where) {
        $this->where = 'where ';
        $queryData = [];
        foreach ($where as $key => $val) {
            if (is_array($val)) {
                list($condition, $value) = $val;
                if (is_array($condition)) {
                    list($condition1, $value1) = $condition;
                    list($condition2, $value2) = $value;
                    $queryData[] = sprintf('%s %s "%s"', $key, $condition1, $value1);
                    $queyrData[] = sprintf('%s %s "$s"', $key, $condition2, $value2);
                } else if ('between' == $condition) {
                    list($start, $end) = $value;
                    $queyrData[] = sprintf('%s %s %s and %s', $key, $condition, $start, $end);
                } else {
                    $queryData[] = sprintf('%s %s ("%s")', $key, $condition, $value);
                }
            } else {
                $queryData[] = sprintf('%s = "%s"', $key, $val);
            }
        }
        $this->where .= implode(' and ', $queryData);
        return $this;
    }

    public function join($table, $on, $type = 'inner') {
        $this->join[] = sprintf('%s join %s on %s', $type, $table, $on);
        return $this;
    }

    public function order($field, $type = 'asc') {
        if (is_array($field)) {
            $order = [];
            foreach ($field as $val) {
                list($column, $type) = $val;
                $order[] = sprintf('%s %s', $column, $type);
            }
            $this->order = sprintf('order by %s', implode(',', $order));
        } else {
            $this->order = sprintf('order by %s %s', $field, $type);
        }
        return $this;
    }

    public function limit($offset, $limit = null) {
        if (null === $limit) {
            $this->limit = sprintf('limit %s', $offset);
        } else {
            $this->limit = sprintf('limit %s,%s', $offset, $limit);
        }

        return $this;
    }

    public function group($field) {
        $this->group = sprintf('group by %s', $field);
        return $this;
    }

    public function having($having) {
        if (!$this->group) {
            return $this;
        }
        $this->having = sprintf('having %s', $having);
        return $this;
    }

    private function setError() {
        $this->error = $this->db->errorInfo()[2];
    }

}
