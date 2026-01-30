<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model: Produk_model
 * Deskripsi: Handle CRUD produk
 */
class Produk_model extends CI_Model {

    /**
     * Get semua produk dengan status "bisa dijual"
     */
    public function get_produk_bisa_dijual() {
        $this->db->select('p.*, k.nama_kategori, s.nama_status');
        $this->db->from('produk p');
        $this->db->join('kategori k', 'p.kategori_id = k.id_kategori');
        $this->db->join('status s', 'p.status_id = s.id_status');
        $this->db->where('s.nama_status', 'bisa dijual');
        $this->db->order_by('p.id_produk', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Get produk by ID
     */
    public function get_by_id($id) {
        $this->db->select('p.*, k.nama_kategori, s.nama_status');
        $this->db->from('produk p');
        $this->db->join('kategori k', 'p.kategori_id = k.id_kategori');
        $this->db->join('status s', 'p.status_id = s.id_status');
        $this->db->where('p.id_produk', $id);
        return $this->db->get()->row();
    }

    /**
     * Insert produk baru
     */
    public function insert($data) {
        return $this->db->insert('produk', $data);
    }

    /**
     * Update produk
     */
    public function update($id, $data) {
        $this->db->where('id_produk', $id);
        return $this->db->update('produk', $data);
    }

    /**
     * Delete produk
     */
    public function delete($id) {
        $this->db->where('id_produk', $id);
        return $this->db->delete('produk');
    }

    /**
     * Get semua kategori untuk dropdown
     */
    public function get_all_kategori() {
        return $this->db->get('kategori')->result();
    }

    /**
     * Get semua status untuk dropdown
     */
    public function get_all_status() {
        return $this->db->get('status')->result();
    }
}