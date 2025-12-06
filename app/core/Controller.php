<?php

class Controller {
    /**
     * Memuat file view dan mengirimkan data.
     * @param string $view Nama file view di dalam folder 'templates'.
     * @param array $data Data yang akan diekstrak menjadi variabel di view.
     */
    public function view($view, $data = [])
    {
        // Ekstrak data agar bisa diakses sebagai variabel di view
        extract($data);
        
        // Path ini sudah benar, menggunakan APPROOT dari root proyek ke folder templates
        require_once APPROOT . '/templates/' . $view . '.php'; 
    }

    /**
     * Memuat file model dan menginstansiasinya.
     * @param string $model Nama file model di dalam folder 'models'.
     * @return object Instance dari kelas model.
     */
    public function model($model)
    {
        // === PERBAIKAN DI SINI ===
        // Path yang benar adalah dari APPROOT langsung ke folder app/models
        require_once APPROOT . '/app/models/' . $model . '.php';
        return new $model;
    }
}
