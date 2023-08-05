<?php

class QueryBuilder
{
    //Оголошення властивостей класу
    protected $fields;
    protected $type;
    protected $table;
    protected $where;
    protected $params;

    //Конструктор класу
    public function __construct()
    {
        $this->params = [];
    }
    //Метод select(Цей метод встановлює тип запиту як "select" та визначає поля для вибірки. Параметр $fields може бути рядком з переліком полів, розділених комами, або масивом полів.
    //Після визначення полів, вони зберігаються у властивості $fields. Метод повертає об'єкт $this для підтримки ланцюжкового інтерфейсу.)
    public function select($fields = "*")
    {
        $this->type = "select";
        $fields_string = $fields;
        if (is_array($fields))
            $fields_string = implode(",", $fields);
        $this->fields = $fields_string;
        return $this;
    }
    //Метод from(Цей метод встановлює таблицю, з якої будуть вибиратися дані. Властивість $table зберігає ім'я таблиці.
    //Метод також повертає об'єкт $this для підтримки ланцюжкового інтерфейсу.)
    public function from($table)
    {
        $this->table = $table;
        return $this;
    }
    //Метод getSql(Цей метод повертає побудований SQL-запит згідно з налаштуваними параметрами. Залежно від типу запиту (у цьому випадку "select"), будується відповідний SQL-запит.
    //У випадку використання умови WHERE, вона додається до SQL-запиту.)
    public function getSql()
    {
        switch ($this->type) {
            case "select":
                $sql = "SELECT {$this->fields} FROM {$this->table}";
                if (!empty($this->where))
                    $sql .= " WHERE $this->where";
                return $sql;
                break;
        }
    }
    //Метод where(
    //Цей метод додає умову WHERE до SQL-запиту. Вхідний параметр $where є асоціативним масивом, де ключі представляють імена полів, а значення - значення для умови.
    //Метод генерує умову WHERE, додаючи імена полів та параметри до масиву $where_parts, а також додає відповідні параметри до масиву $params, який використовується для підготовки запиту з використанням об'єкта PDO.)
    public function where($where)
    {
        $where_parts = [];
        foreach ($where as $key => $value) {
            $where_parts[] = "{$key} = :{$key}";
            $this->params[$key] = $value;
        }
        $this->where = implode('AND', $where_parts);
        return $this;
    }

    //Метод getParams(Цей метод повертає масив параметрів, який буде використовуватися для підготовки запиту з використанням об'єкта PDO.)
    public function getParams()
    {
        return $this->params;
    }
}