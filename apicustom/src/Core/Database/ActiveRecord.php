<?php

namespace App\Core\Database;

use App\Core\Core;
use App\Core\StaticCore;

class ActiveRecord
{
    protected array $fields = [];
    protected string $table;

    public function __construct()
    {
    }

    public function __set(string $name, $value): void
    {
        $this->fields[$name] = $value;
    }

    public function __get(string $name)
    {
        return $this->fields[$name];
    }

    public function __call(string $name, array $arguments)
    {
        switch ($name) {
            case 'save':
                $builder = new QueryBuilder();
                if (!empty($arguments[0])) {
                    $this->table = $arguments[0];
                }
                if (!empty($this->table)) {
                    $builder->insert($this->fields, $this->table);
                    Core::GetInstance()->GetDatabase()->execute($builder);
                } else
                    echo "Error";
                break;
        }
    }
}