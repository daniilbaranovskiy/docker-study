<?php

class QueryBuilder
{
    //Оголошення властивостей класу
    protected $fields;
    protected $type;
    protected $table;
    protected $where;
    protected $params;
    protected $values;
    protected $set;
    protected $joins = [];

    //Конструктор класу
    public function __construct()
    {
        $this->params = [];
    }
    //Цей метод дозволяє додавати різні типи JOIN операцій до запиту.
    public function join($table, $on, $type = 'INNER')
    {
        $this->joins[] = [
            'table' => $table,
            'on' => $on,
            'type' => $type
        ];
        return $this;
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
    //Метод getSql(Цей метод повертає побудований SQL-запит згідно з налаштуваними параметрами. Залежно від типу запиту (у цьому випадку "select" або "update"), будується відповідний SQL-запит.
    //У випадку використання умови WHERE, вона додається до SQL-запиту.)
    public function getSql()
    {
        switch ($this->type) {
            case "select":
                $sql = "SELECT {$this->fields} FROM {$this->table}";
                foreach ($this->joins as $join) {
                    $sql .= " {$join['type']} JOIN {$join['table']} ON {$join['on']}";
                }
                if (!empty($this->where))
                    $sql .= " WHERE $this->where";
                return $sql;
                break;
            case "insert":
                $sql = "INSERT INTO {$this->table} {$this->fields} VALUES {$this->values}";
                return $sql;
                break;
            case "update":
                $sql = "UPDATE {$this->table} SET {$this->set}";
                if (!empty($this->where))
                    $sql .= " WHERE $this->where";
                return $sql;
                break;
            case "delete":
                $sql = "DELETE FROM {$this->table}";
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

    //Метод insert призначений для побудови SQL-запиту типу INSERT INTO для вставки даних в таблицю бази даних.
    public function insert($data)
    {
        $this->type = "insert";
        //Тут створюється рядок, який містить імена стовпців, до яких будуть вставлені дані.
        $columns = implode(',', array_keys($data));
        //У цьому рядку створюється рядок з позначеннями параметрів для підготовлених запитів.
        $values = implode(',', array_map(function ($value) {
            return ':' . $value;
        }, array_keys($data)));
        //Тут зберігається рядок з іменами стовпців, отриманими на попередньому кроці.
        $this->fields = "($columns)";
        //Тут зберігається рядок з позначеннями параметрів для підготовлених запитів.
        $this->values = "($values)";
        //Тут зберігаються дані, які будуть вставлені в таблицю бази даних. Вони зберігаються у властивості $params,
        $this->params = $data;
        return $this;
    }

    //Метод update призначений для побудови SQL-запиту для оновлення даних в таблицю бази даних.
    public function update($data)
    {
        $this->type = "update";
        //Створюється порожній масив, який буде містити частини запиту для оновлення даних.
        $set_parts = [];
        //Проходимо по переданому масиву $data, який містить дані для оновлення.
        foreach ($data as $key => $value) {
            //Додаємо частину запиту "column_name = :column_name" для кожної пари "ключ-значення" в масив $set_parts.
            $set_parts[] = "{$key} = :{$key}";
            $this->params[$key] = $value;
        }
        //Об'єднуємо всі частини запиту, які містяться у масиві $set_parts, в один рядок, розділений комами.
        $this->set = implode(', ', $set_parts);
        return $this;
    }

    // //Метод delete призначений для видалення даних з таблиці бази даних.
    public function delete()
    {
        $this->type = "delete";
        return $this;
    }

    //Метод getParams(Цей метод повертає масив параметрів, який буде використовуватися для підготовки запиту з використанням об'єкта PDO.)
    public function getParams()
    {
        return $this->params;
    }
}