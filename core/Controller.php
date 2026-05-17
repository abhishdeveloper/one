<?php

/*
 * Base Controller
 * Loads the models and views
 */

class Controller {
    // Load model
    public function model($model) {
        if (file_exists('../app/models/' . $model . '.php')) {
            require_once '../app/models/' . $model . '.php';
            return new $model();
        }
        die('Model ' . $model . ' does not exist.');
    }

    // Load view
    public function view($view, $data = []) {
        if (file_exists('../app/views/' . $view . '.php')) {
            require_once '../app/views/' . $view . '.php';
        } else {
            die('View ' . $view . ' does not exist.');
        }
    }
}
