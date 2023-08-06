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
$query->from('news')->insert(['title' => 'intest', 'text' => 'intext', 'date' => '2023-08-06 14:00:00']);
//Виконання запиту до бд
$rows = $database->execute($query);
////Створення об'єкту класу FrontController
//$front_controller = new FrontController();
////Виклик методу run
//$front_controller->run();