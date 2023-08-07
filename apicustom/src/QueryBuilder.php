<?php

class QueryBuilder
{

    protected $fields;
    protected $table;
    protected $type;
    protected $where;
    protected $params;


    public function __construct()
    {
        $this->params = [];
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

    public function getSql()
    {
        switch ($this->type) {
            case 'select':
                $sql = "SELECT {$this->fields} FROM {$this->table}";
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