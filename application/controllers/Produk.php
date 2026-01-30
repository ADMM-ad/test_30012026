<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Produk extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Api_model');
        $this->load->library('session');
        $this->load->helper('url'); 
    }

    /**
     * Halaman utama (sementara untuk testing import)
     */
    public function index() {
        echo "<h1>Halaman Produk</h1>";
        echo "<p><a href='" . site_url('produk/import_api') . "'>Klik untuk Import Data dari API</a></p>";
    }

    /**
     * Import data dari API
     */
    public function import_api() {
       
         $api_url = 'https://recruitment.fastprint.co.id/tes/api_tes_programmer';  
        $username = 'tesprogrammer300126C14';                                   
        $password = '62d31365e6f375181dc7da56ff549f3e';                              
        
        
        // Fetch data dari API
        $api_response = $this->Api_model->fetch_api($api_url, $username, $password);
        
        if (!$api_response['success']) {
            echo "<h3>Error: " . $api_response['message'] . "</h3>";
            echo "<a href='" . base_url('produk') . "'>Kembali</a>";
            return;
        }
        
        // Import data ke database
        $result = $this->Api_model->import_data($api_response['data']);
        
        if ($result['success']) {
            echo "<h3>✅ Import Berhasil!</h3>";
            echo "<p>Inserted: {$result['inserted']}</p>";
            echo "<p>Updated: {$result['updated']}</p>";
            echo "<p>Total: {$result['total']}</p>";
            if ($result['errors'] > 0) {
                echo "<p>Errors: {$result['errors']}</p>";
            }
        } else {
            echo "<h3>❌ Import Gagal!</h3>";
            echo "<p>" . $result['message'] . "</p>";
        }
        
        echo "<br><a href='" . base_url('produk') . "'>Kembali</a>";
    }
}