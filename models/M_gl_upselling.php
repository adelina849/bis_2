<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_gl_upselling extends CI_Model
{

    public $table = 'tb_upselling';
    public $id = 'id_upselling';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // datatables
    function json() {
        $this->datatables->select('id_upselling,tanggal,id_costumer,id_produk,foto_bukti,tgl_ins,tgl_updt,user_ins,user_updt,kode_kantor');
        $this->datatables->from('tb_upselling');
        //add this line for join
        //$this->datatables->join('table2', 'tb_upselling.field = table2.field');
        $this->datatables->add_column('action', anchor(site_url('c_gl_admin_upselling/read/$1'),'Read')." | ".anchor(site_url('c_gl_admin_upselling/update/$1'),'Update')." | ".anchor(site_url('c_gl_admin_upselling/delete/$1'),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'), 'id_upselling');
        return $this->datatables->generate();
    }

    function list_karyawan($cari)
    {
      $data = $this->db->query("
          SELECT A.*,D.nama_jabatan,DATEDIFF(NOW(),tgl_diterima) / 365 AS masa_kerja
          FROM tb_karyawan A
          LEFT JOIN tb_gaji_pokok B
            ON A.id_karyawan = B.id_karyawan AND A.kode_kantor = B.kode_kantor
          LEFT JOIN tb_karyawan_jabatan C
            ON A.id_karyawan = C.id_karyawan AND A.kode_kantor = C.kode_kantor
          LEFT JOIN tb_jabatan D
            ON C.id_jabatan = D.id_jabatan AND C.kode_kantor = D.kode_kantor
          WHERE isDefault = '1' AND A.isAktif = 'DITERIMA'
          AND nama_karyawan LIKE '%".$cari."%' AND A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
      ")->result();

      return $data;
    }

    // get all
    function get_all()
    {
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }

    // get data by id
    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    function list_upselling($id_karyawan,$kode_kantor,$periode)
    {
        $data = $this->db->query("
            SELECT id_upselling,A.id_produk,A.id_costumer,tanggal,nama_produk,no_costumer,nama_lengkap,isProduk,foto_bukti,
                   A.kode_kantor_cust
            FROM tb_upselling A
            LEFT JOIN tb_produk B
              ON A.id_produk = B.id_produk AND A.kode_kantor = B.kode_kantor
            LEFT JOIN tb_costumer C
              ON A.id_costumer = C.id_costumer AND A.kode_kantor_cust = C.kode_kantor
            WHERE A.id_karyawan = '".$id_karyawan."' AND A.kode_kantor = '".$kode_kantor."' AND DATE_FORMAT(A.tanggal,'%Y-%m') = '".$periode."'
        ")->result();

        return $data;
    }

    function list_pasien($cari,$cari_kantor)
    {
        $data = $this->db->query("
            SELECT id_costumer,no_costumer,nama_lengkap,kode_kantor
            FROM tb_costumer
            WHERE nama_lengkap LIKE '%".$cari."%'
            ".$cari_kantor."
            AND no_costumer <> '' AND nama_lengkap <> ''
            ORDER BY nama_lengkap
            LIMIT 20
        ")->result();

        return $data;
    }

    function list_produk($cari)
    {
        $data = $this->db->query("
            SELECT id_produk,kode_produk,nama_produk,isProduk
            FROM tb_produk
            WHERE isProduk IN('PRODUK','JASA') 
            AND kode_kantor = 'JKT'
            AND nama_produk LIKE '%".$cari."%'
            ORDER BY nama_produk,isProduk
            LIMIT 20
        ")->result();

        return $data;
    }

    function list_cabang()
    {
        $data = $this->db->query("
            SELECT kode_kantor FROM tb_kantor
        ")->result();

        return $data;
    }
    
    
    // insert data
    function insert($data)
    {
        $this->db->insert($this->table, $data);
    }

    // update data
    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    // delete data
    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }

}

/* End of file M_gl_upselling.php */
/* Location: ./application/models/M_gl_upselling.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2020-12-07 05:12:52 */
/* http://harviacode.com */