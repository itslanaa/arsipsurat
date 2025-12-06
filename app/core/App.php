<?php

class App {
    protected $controller = 'Dashboard';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseURL();

        // **Controller**
        // Cek apakah ada controller dengan nama yang sesuai di URL
        if (isset($url[0])) {
            if (file_exists(APPROOT . '/app/controllers/' . ucfirst($url[0]) . '.php')) {
                $this->controller = ucfirst($url[0]);
                unset($url[0]);
            }
        }

        require_once APPROOT . '/app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // **Method**
        // Cek apakah ada method dengan nama yang sesuai di URL
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // **Parameters**
        // Ambil sisa URL sebagai parameter
        if (!empty($url)) {
            $this->params = array_values($url);
        }

        // Jalankan controller & method, serta kirimkan params jika ada
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
}
