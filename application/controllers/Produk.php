<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Produk extends CI_Controller {

     public function __construct() {
        parent::__construct();
        $this->load->model('Api_model');
        $this->load->model('Produk_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('form_validation');
    }

    /**
     * Halaman utama - List produk dengan status "bisa dijual"
     */
    public function index() {
        $data['produk'] = $this->Produk_model->get_produk_bisa_dijual();
        $this->load->view('produk/index', $data);
    }

    /**
     * Halaman tambah produk
     */
    public function tambah() {
        $data['kategori'] = $this->Produk_model->get_all_kategori();
        $data['status'] = $this->Produk_model->get_all_status();
        $this->load->view('produk/tambah', $data);
    }

    /**
     * Simpan produk baru
     */
    public function simpan() {
        // Validasi form
        $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'required', [
            'required' => 'Nama produk harus diisi'
        ]);
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric', [
            'required' => 'Harga harus diisi',
            'numeric' => 'Harga harus berupa angka'
        ]);
        $this->form_validation->set_rules('kategori_id', 'Kategori', 'required');
        $this->form_validation->set_rules('status_id', 'Status', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Validasi gagal, kembali ke form
            $this->tambah();
        } else {
            // Validasi sukses, simpan data
            $data = [
                'nama_produk' => $this->input->post('nama_produk'),
                'harga' => $this->input->post('harga'),
                'kategori_id' => $this->input->post('kategori_id'),
                'status_id' => $this->input->post('status_id')
            ];

            if ($this->Produk_model->insert($data)) {
                $this->session->set_flashdata('success', 'Produk berhasil ditambahkan!');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan produk!');
            }
            
            redirect('produk');
        }
    }

    /**
     * Halaman edit produk
     */
    public function edit($id) {
        $data['produk'] = $this->Produk_model->get_by_id($id);
        
        if (!$data['produk']) {
            $this->session->set_flashdata('error', 'Produk tidak ditemukan!');
            redirect('produk');
        }

        $data['kategori'] = $this->Produk_model->get_all_kategori();
        $data['status'] = $this->Produk_model->get_all_status();
        $this->load->view('produk/edit', $data);
    }

    /**
     * Update produk
     */
    public function update($id) {
        // Validasi form
        $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'required', [
            'required' => 'Nama produk harus diisi'
        ]);
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric', [
            'required' => 'Harga harus diisi',
            'numeric' => 'Harga harus berupa angka'
        ]);
        $this->form_validation->set_rules('kategori_id', 'Kategori', 'required');
        $this->form_validation->set_rules('status_id', 'Status', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Validasi gagal, kembali ke form edit
            $this->edit($id);
        } else {
            // Validasi sukses, update data
            $data = [
                'nama_produk' => $this->input->post('nama_produk'),
                'harga' => $this->input->post('harga'),
                'kategori_id' => $this->input->post('kategori_id'),
                'status_id' => $this->input->post('status_id')
            ];

            if ($this->Produk_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Produk berhasil diupdate!');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate produk!');
            }
            
            redirect('produk');
        }
    }

    /**
     * Hapus produk
     */
    public function hapus($id) {
        if ($this->Produk_model->delete($id)) {
            $this->session->set_flashdata('success', 'Produk berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus produk!');
        }
        
        redirect('produk');
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