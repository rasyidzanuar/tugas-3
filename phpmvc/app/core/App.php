<?php
class App {
    protected $controller = 'Home';
    protected $method = 'index';
    protected $params = [];
    
    public function __construct() {
        $url = $this->parse_URL();
        // Controller
        if (isset($url[0])) {
            if (file_exists('../app/controllers/' . $url[0] . '.php')) {
                $this->controller = $url[0];
                unset($url[0]); // unset the first element of the array
            }
        }
        
        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;
        
        // Method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1]; // set the method
                unset($url[1]); // unset the second element of the array
            }
        }
        
        // Params
        if (!empty($url)) {
            $this->params = array_values($url); // re-index the array
        }
        
        // Call a callback with array of params
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parse_url()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/'); // remove the trailing slash
            $url = filter_var($url, FILTER_SANITIZE_URL); // remove illegal characters from the URL
            $url = explode('/', $url); // split the URL into an array
            return $url;
        }
    }
} 