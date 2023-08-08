<?php
global $CoreParams;

require_once('../config/config.php');
# Підключення файлів
#require_once ('../src/FrontController.php');
#include () - якщо файлу не існує просто не підключить
#require - якщо файлу не існує - помилка
#include_once() - одноразове підключення з once

# spl_autoload - примусово запускає підключення певного класу
# якщо клас ще не оголошено, запускається spl_autoload_register
spl_autoload_register(function ($className) {
    $path = "../src/{$className}.php";
    if (file_exists($path)) {
        require_once($path);
    }
});


$database = new Database($CoreParams['Database']['Host'],
    $CoreParams['Database']['Username'],
    $CoreParams['Database']['Password'],
    $CoreParams['Database']['Database']);

$database->connect();

$query = new QueryBuilder();
// Приклад запиту SELECT
$query->from("news")
    ->select(["title","text"])
    ->where(['id'=>5]);
$rows = $database->execute($query);
var_dump($rows);
// Приклад запиту INSERT
$dataToInsert = array(
    'title' => 'Sample Title',
    'text' => 'Sample Text',
    'date' => '2023-01-01 00:00:00'  // Поточна дата та час у форматі MySQL
);
$query->insert($dataToInsert)
    ->from("news");
$database->execute($query);

// Приклад запиту UPDATE
$dataToUpdate = array(
    'title' => 'Updated Title',
    'text' => 'Updated Text',
    'date' => date('Y-m-d H:i:s')  // Поточна дата та час у форматі MySQL
);
$query->update($dataToUpdate)
    ->from("news")
    ->where(['id' => 5]);
$database->execute($query);

// Приклад запиту DELETE
$query->delete()
    ->from("news")
    ->where(['id' => 5]);
$database->execute($query);


// Приклад запиту SELECT з JOIN
$query->select(['news.title', 'categories.name AS category_name'])
    ->from('news')
    ->join('categories', 'news.category_id = categories.id')
    ->where(['news.id' => 5]);