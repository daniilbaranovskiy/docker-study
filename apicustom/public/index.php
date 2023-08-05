<?php
global $CoreParams;
require_once('../config/config.php');
//Реєструє задану функцію як реалізацію методу __autoload()
spl_autoload_register(function ($className) {
    $path = "../src/{$className}.php";
    //Перевіряє існування вказаного файлу чи каталогу
    if (file_exists($path))
        require_once $path;
});
//Створення об'єкта підключення до бд
$database = new Database($CoreParams['Database']['Host'],
    $CoreParams['Database']['Username'],
    $CoreParams['Database']['Password'],
    $CoreParams['Database']['Database']);
//Встановлення з'єднання з бд
$database->connect();
//Побудова запиту до бд
$query = new QueryBuilder();
$query->from('news')
    ->select(["title", "text"])
    ->where(['id' => 5]);
//Виконання запиту до бд
$rows = $database->execute($query);
var_dump($rows);
////Створення об'єкту класу FrontController
//$front_controller = new FrontController();
////Виклик методу run
//$front_controller->run();