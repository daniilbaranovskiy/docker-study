<?php

class Database
{
    //Оголошення властивостей класу
    protected $host;
    protected $username;
    protected $password;
    protected $dbname;
    protected PDO $pdo;

    //Конструктор класу(Конструктор отримує дані для підключення до бази даних і ініціалізує властивості класу.)
    public function __construct($host, $username, $password, $dbname)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
    }

    //Метод getConnectionString(Цей метод повертає рядок, який містить рядок підключення до бази даних. Використовується при створенні об'єкту PDO.)
    public function getConnectionString()
    {
        return "mysql:host={$this->host};dbname={$this->dbname}";
    }

    //Метод connect(Цей метод створює об'єкт PDO і встановлює з'єднання з базою даних, використовуючи дані підключення з властивостей класу.)
    public function connect()
    {
        $this->pdo = new PDO($this->getConnectionString(), $this->username, $this->password);
    }

    //Метод getPDO(Цей метод повертає об'єкт PDO, який може використовуватися для виконання запитів до бази даних.)
    public function getPDO()
    {
        return $this->pdo;
    }

    //Метод execute(Цей метод виконує SQL-запит, побудований за допомогою об'єкту $builder класу QueryBuilder.
    //Він використовує об'єкт PDO для підготовки та виконання запиту, підставляючи необхідні значення параметрів в запит.)
    public function execute(QueryBuilder $builder)
    {
        $sth = $this->pdo->prepare($builder->getSql());
        $params = $builder->getParams();
        foreach ($params as $key => $value)
            $sth->bindValue($key, $value);
        $sth->execute();
        return $sth->fetchAll();
    }
}