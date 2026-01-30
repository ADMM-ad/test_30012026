<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model: Api_model
 * Deskripsi: Handle fetch data dari API dan insert ke database
 */
class Api_model extends CI_Model {

    /**
     * Fetch data dari API eksternal
     * @param string $url - URL API
     * @param string $username - Username untuk auth
     * @param string $password - Password MD5 untuk auth
     * @return array - Response dari API
     */
    public function fetch_api($url, $username, $password) {
        $ch = curl_init();
        
        // Setup cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'username' => $username,
            'password' => $password
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1); // Force HTTP/1.1
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            curl_close($ch);
            return [
                'success' => false,
                'message' => 'cURL Error: ' . curl_error($ch)
            ];
        }
        
        curl_close($ch);
        
        if ($http_code != 200) {
            return [
                'success' => false,
                'message' => 'HTTP Error: ' . $http_code
            ];
        }
        
        $data = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'message' => 'JSON Error: ' . json_last_error_msg()
            ];
        }
        
        return [
            'success' => true,
            'data' => $data
        ];
    }

    /**
     * Insert atau update kategori
     * @param string $nama_kategori
     * @return int - id_kategori
     */
    public function insert_kategori($nama_kategori) {
        // Cek apakah kategori sudah ada
        $existing = $this->db->get_where('kategori', ['nama_kategori' => $nama_kategori])->row();
        
        if ($existing) {
            return $existing->id_kategori;
        }
        
        // Insert kategori baru
        $this->db->insert('kategori', ['nama_kategori' => $nama_kategori]);
        return $this->db->insert_id();
    }

    /**
     * Insert atau update status
     * @param string $nama_status
     * @return int - id_status
     */
    public function insert_status($nama_status) {
        // Cek apakah status sudah ada
        $existing = $this->db->get_where('status', ['nama_status' => $nama_status])->row();
        
        if ($existing) {
            return $existing->id_status;
        }
        
        // Insert status baru
        $this->db->insert('status', ['nama_status' => $nama_status]);
        return $this->db->insert_id();
    }

    /**
     * Insert atau update produk
     * @param array $data_produk
     * @return bool
     */
    public function insert_produk($data_produk) {
        // Cek apakah produk sudah ada (by id_produk dari API)
        $existing = $this->db->get_where('produk', ['id_produk' => $data_produk['id_produk']])->row();
        
        if ($existing) {
            // Update produk
            $this->db->where('id_produk', $data_produk['id_produk']);
            return $this->db->update('produk', [
                'nama_produk' => $data_produk['nama_produk'],
                'harga' => $data_produk['harga'],
                'kategori_id' => $data_produk['kategori_id'],
                'status_id' => $data_produk['status_id']
            ]);
        } else {
            // Insert produk baru
            return $this->db->insert('produk', $data_produk);
        }
    }

    /**
     * Proses import data dari API ke database
     * @param array $api_data - Data dari API
     * @return array - Result
     */
    public function import_data($api_data) {
        $inserted = 0;
        $updated = 0;
        $errors = 0;
        
        if (!isset($api_data['data']) || !is_array($api_data['data'])) {
            return [
                'success' => false,
                'message' => 'Format data API tidak valid'
            ];
        }
        
        foreach ($api_data['data'] as $item) {
            try {
                // Get atau insert kategori
                $kategori_id = $this->insert_kategori($item['kategori']);
                
                // Get atau insert status
                $status_id = $this->insert_status($item['status']);
                
                // Prepare data produk
                $produk_data = [
                    'id_produk' => $item['id_produk'],
                    'nama_produk' => $item['nama_produk'],
                    'harga' => $item['harga'],
                    'kategori_id' => $kategori_id,
                    'status_id' => $status_id
                ];
                
                // Check if exists
                $existing = $this->db->get_where('produk', ['id_produk' => $item['id_produk']])->row();
                
                if ($existing) {
                    $updated++;
                } else {
                    $inserted++;
                }
                
                // Insert atau update produk
                $this->insert_produk($produk_data);
                
            } catch (Exception $e) {
                $errors++;
            }
        }
        
        return [
            'success' => true,
            'inserted' => $inserted,
            'updated' => $updated,
            'errors' => $errors,
            'total' => count($api_data['data'])
        ];
    }
}