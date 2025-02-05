<?php 
    class Controller{
        public function model($model , ...$args){
            require_once '../app/models/' . $model . '.php';
            return new $model(...$args);
        }

        public function view($view, $data = []){
            if(file_exists('../app/views/' . $view . '.php')){
                require_once '../app/views/' . $view . '.php';
            } else {
                die("This view does not exist!");
            }
        }
    }
?>