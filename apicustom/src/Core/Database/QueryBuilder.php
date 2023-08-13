<?php

namespace App\Core\Database;
class QueryBuilder
{

    protected $fields;
    protected $table;
    protected $type;
    protected $where;
    protected $params;
    protected $joins;


    public function __construct()
    {
        $this->params = [];
        $this->joins = [];
    }

    public function select($fields = "*")
    {
        $this->type = "select";
        $fields_string = $fields;
        if (is_array($fields))
            $fields_string = implode(", ", $fields);
        $this->fields = $fields_string;
        return $this;
    }

    public function from($table)
    {
        $this->table = $table;
        return $this;
    }

    public function insert($data)
    {
        $this->type = "insert";
        $this->fields = implode(', ', array_keys($data));
        $this->params = $data;
        return $this;
    }

    public function update($data)
    {
        $this->type = "update";
        $this->fields = implode(', ', array_map(function ($field) {
            return $field . ' = :' . $field;
        }, array_keys($data)));
        $this->params = $data;
        return $this;
    }

    public function delete()
    {
        $this->type = "delete";
        $this->params = []; // Очищуємо параметри для DELETE-запиту
        return $this;
    }

    public function join($table, $on)
    {
        $this->joins[] = "JOIN {$table} ON {$on}";
        return $this;
    }

    public function leftJoin($table, $on)
    {
        $this->joins[] = "LEFT JOIN {$table} ON {$on}";
        return $this;
    }

    public function rightJoin($table, $on)
    {
        $this->joins[] = "RIGHT JOIN {$table} ON {$on}";
        return $this;
    }

    public function getSql()
    {
        switch ($this->type) {
            case 'select':
                $sql = "SELECT {$this->fields} FROM {$this->table}";
                if (!empty($this->where))
                    $sql .= " WHERE {$this->where}";
                return $sql;
                break;
            case 'insert':
                $sql = "INSERT INTO {$this->table} ({$this->fields}) VALUES (:"
                    . implode(', :', array_keys($this->params)) . ")";
                return $sql;
                break;
            case 'update':
                $sql = "UPDATE {$this->table} SET {$this->fields}";
                if (!empty($this->where))
                    $sql .= " WHERE {$this->where}";
                return $sql;
                break;
            case 'delete':
                $sql = "DELETE FROM {$this->table}";
                if (!empty($this->where))
                    $sql .= " WHERE {$this->where}";
                return $sql;
                break;

        }
    }

    public function where($where)
    {
        /*if (is_array($where)){

        }*/
        $where_parts = [];
        foreach ($where as $key => $value) {
            $where_parts [] = "{$key} = :{$key}";
            $this->params[$key] = $value;
        }
        $this->where = implode(' AND ', $where_parts);
        return $this;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}