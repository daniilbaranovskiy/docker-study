<?php

class FrontController
{
    public function run()
    {
        //Отримання URL та розділення його на елементи
        $url = $_SERVER['REQUEST_URI'];
        $url_elements = explode('/', $url);
        $url_elements = array_slice($url_elements, 2);
        //Визначення контролера та методу виклику
        if (!empty($url_elements) && !empty($url_elements[0])) {
            $controller = ucfirst($url_elements[0]) . 'Controller';
            $method = !empty($url_elements[1]) ? $url_elements[1] : "index";
        } else {
            $controller = "SiteController";
            $method = "index";
        }
        //Виклик контролера та методу
        if (class_exists($controller)) {
            $controller_object = new $controller();
            if (method_exists($controller, $method)) {
                /* @var $response Response */
                $response = $controller_object->$method();
                if ($response instanceof Response) {
                    echo $response->getText();
                }
            } else
                echo 'Error 404!';

        } else
            echo 'Error 404!';
    }
}