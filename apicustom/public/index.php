<?php
global $CoreParams;
require_once('../config/config.php');
//Реєструє задану функцію як реалізацію методу __autoload()
spl_autoload_register(function ($className) {
    $newClassName = str_replace('\\', '/', $className);
    if (stripos($newClassName, 'App/') === 0) {
        $newClassName = substr($newClassName, 4);
    }
    $path = "../src/{$newClassName}.php";
    //Перевіряє існування вказаного файлу чи каталогу
    if (file_exists($path))
        require_once $path;
});
$core = \App\Core\Core::GetInstance();
$core->init();
$core->run();
$core->done();
//Побудова запиту до бд
/*$query = new QueryBuilder();
$query->from('news')
    ->join('categories', 'news.category_id = categories.id', 'INNER')
    ->select(['news.title', 'categories.name'])use App\Core\Database\Database;
use App\Core\FrontController;

    ->where(['category_id' => 1]);
//Виконання запиту до бд
$rows = $database->execute($query);
var_dump($rows);*/
$record = new \App\Models\News();
$record->title = 'Title';
$record->text = 'Text';
$record->date = '2023-08-11 19:00:00';
$record->category_id = '1';
$record->save();