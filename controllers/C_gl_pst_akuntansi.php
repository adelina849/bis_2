<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_gl_pst_akuntansi extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		//$this->load->model(array('M_berita','M_kat_berita','M_images'));
		$this->load->model(array('M_gl_lap_penjualan'));
		
	}
	
	public function index()
	{
		if(($this->session->userdata('ses_user_admin') == null) or ($this->session->userdata('ses_pass_admin') == null))
		{
			header('Location: '.base_url().'gl-admin-login');
		}
		else
		{
			$cek_ses_login = $this->M_gl_karyawan->get_karyawan_jabatan_row(" WHERE A.user = '".$this->session->userdata('ses_user_admin')."' AND A.pass = '".base64_encode(md5($this->session->userdata('ses_pass_admin_pure')))."'  AND A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ");
			
			if(!empty($cek_ses_login))
			{
				if((!empty($_GET['dari'])) && ($_GET['dari']!= "")  )
				{
					$dari = $_GET['dari'];
					$sampai = $_GET['sampai'];
				}
				else
				{
					$dari = date("Y-m-d");
					$sampai = date("Y-m-d");
				}
				
				if((!empty($_GET['kode_kantor'])) && ($_GET['kode_kantor']!= "")  )
				{
					$kode_kantor = str_replace("'","",$_GET['kode_kantor']);
				}
				else
				{
					$kode_kantor = "";
				}
				
				if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
				{
					$cari = "
							WHERE A.kode_kantor LIKE '%".$kode_kantor."%' 
							AND COALESCE(B.sts_penjualan,'') IN ('PEMBAYARAN','SELESAI') 
							AND COALESCE(C.isProduk,'') IN ('PRODUK','JASA')
							AND DATE(COALESCE(B.tgl_h_penjualan,NOW())) BETWEEN '".$dari."' AND '".$sampai."'
							AND 
							(
								COALESCE(B.no_faktur,'') LIKE '%".str_replace("'","",$_GET['cari'])."%' 
								OR COALESCE(B.no_costmer,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
								OR COALESCE(B.nama_costumer,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
								OR COALESCE(D.nama_karyawan,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
								OR COALESCE(E.nama_karyawan,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
								OR COALESCE(C.kode_produk,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
								OR COALESCE(C.nama_produk,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
							)";
							
					$cari_h = "
								WHERE A.kode_kantor LIKE '%".$kode_kantor."%' 
								AND COALESCE(A.sts_penjualan,'') IN ('PEMBAYARAN','SELESAI')
								AND DATE(COALESCE(A.tgl_h_penjualan,NOW())) BETWEEN '".$dari."' AND '".$sampai."'
								AND C.nominal > 0
								AND 
								(
									COALESCE(A.no_faktur,'') LIKE '%".str_replace("'","",$_GET['cari'])."%' 
									OR COALESCE(A.no_costmer,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR COALESCE(A.nama_costumer,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
								)
							";
				}
				else
				{
					$cari = "
							WHERE A.kode_kantor LIKE '%".$kode_kantor."%' 
							AND COALESCE(B.sts_penjualan,'') IN ('PEMBAYARAN','SELESAI') 
							AND COALESCE(C.isProduk,'') IN ('PRODUK','JASA')
							AND DATE(COALESCE(B.tgl_h_penjualan,NOW())) BETWEEN '".$dari."' AND '".$sampai."'
						";
						
					$cari_h = "
								WHERE A.kode_kantor LIKE '%".$kode_kantor."%' 
								AND COALESCE(A.sts_penjualan,'') IN ('PEMBAYARAN','SELESAI')
								AND DATE(COALESCE(A.tgl_h_penjualan,NOW())) BETWEEN '".$dari."' AND '".$sampai."'
								AND C.nominal > 0
							";
				}
				$order_by = "ORDER BY B.no_faktur DESC,A.isProduk ASC";
				
				
				
				/*
				$cari_deft = "
					WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' 
					-- AND COALESCE(B.sts_penjualan,'') IN ('SELESAI','PEMBAYARAN')
					AND COALESCE(B.sts_penjualan,'') = 'SELESAI'
					AND COALESCE(DATE(B.tgl_h_penjualan),'0000-00-00') BETWEEN '".$dari."' AND '".$sampai."' ";
					
				if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
				{
					$cari = "
							WHERE
							(
								COALESCE(BBB.nama_produk,'') LIKE '%".str_replace("'","",$_GET['cari'])."%' 
								OR COALESCE(BBB.kode_produk,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
							)";
				}
				else
				{
					$cari = "";
				}
				$order_by = "ORDER BY COALESCE(BBB.isProduk,'') ASC, COALESCE(BBB.nama_produk,'') ASC ,COALESCE(BBB.kode_produk,'') ASC";
				*/
				
				
				//UNTUK AKUMULASI INFO
					$jum_row = $this->M_gl_lap_penjualan->count_laporan_detail($cari)->JUMLAH;
				//UNTUK AKUMULASI INFO
				
				
				$this->load->library('pagination');
				//$config['first_url'] = base_url().'admin/jabatan?'.http_build_query($_GET);
				//$config['base_url'] = base_url().'admin/jabatan/';
				
				//$config['first_url'] = site_url('gl-admin-laporan-transaksi?'.http_build_query($_GET));
				$config['first_url'] = site_url('gl-admin-laporan-list-detail-penjualan-row?'.http_build_query($_GET));
				$config['base_url'] = site_url('gl-admin-laporan-list-detail-penjualan-row/');
				$config['total_rows'] = $jum_row;
				$config['uri_segment'] = 2;	
				//$config['uri_segment'] = $_GET['var_offset'];	
				$config['per_page'] = 100000;
				//$config['per_page'] = 10000;
				$config['num_links'] = 2;
				//$config['num_links'] = 20;
				//$config['suffix'] = '?' . http_build_query($_GET, '', "&");
				$config['suffix'] = '?' . http_build_query($_GET, '', "&");
				//$config['use_page_numbers'] = TRUE;
				//$config['page_query_string'] = false;
				//$config['query_string_segment'] = '';
				$config['first_page'] = 'Awal';
				$config['last_page'] = 'Akhir';
				$config['next_page'] = '&laquo;';
				$config['prev_page'] = '&raquo;';
				
				
				$config['full_tag_open'] = '<div><ul class="pagination">';
				$config['full_tag_close'] = '</ul></div>';
				$config['first_link'] = '&laquo; First';
				$config['first_tag_open'] = '<li class="prev page">';
				$config['first_tag_close'] = '</li>';
				$config['last_link'] = 'Last &raquo;';
				$config['last_tag_open'] = '<li class="next page">';
				$config['last_tag_close'] = '</li>';
				$config['next_link'] = 'Next &rarr;';
				$config['next_tag_open'] = '<li class="next page">';
				$config['next_tag_close'] = '</li>';
				$config['prev_link'] = '&larr; Previous';
				$config['prev_tag_open'] = '<li class="prev page">';
				$config['prev_tag_close'] = '</li>';
				$config['cur_tag_open'] = '<li class="active"><a href="">';
				$config['cur_tag_close'] = '</a></li>';
				$config['num_tag_open'] = '<li class="page">';
				$config['num_tag_close'] = '</li>';
				
				
				//inisialisasi config
				$this->pagination->initialize($config);
				$halaman = $this->pagination->create_links();
				
				$list_laporan_d_penjualan_row = $this->M_gl_lap_penjualan->list_laporan_detail($cari,$order_by,$config['per_page'],$this->uri->segment(2,0));
				
				
				//UNTUK AKUMULASI INFO
					if($config['per_page'] > $jum_row)
					{
						$jum_row_tampil = $jum_row;
					}
					else
					{
						$jum_row_tampil = $config['per_page'];
					}
					
					$offset = (integer) $this->uri->segment(2,0);
					$max_data = $offset + $jum_row_tampil;
					$offset = $offset + 1;
					if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
					{
						$sum_pesan = "Menampilkan ".$jum_row_tampil." Dari ".$jum_row." Data Pencarian ".str_replace("'","",$_GET['cari'])." dimulai dari data ke ".$offset." Sampai ".$max_data;
					}
					else
					{
						$sum_pesan = "Menampilkan ".$jum_row_tampil." Dari ".$jum_row." Data dimulai dari data ke ".$offset." Sampai ".$max_data;
					}
				//UNTUK AKUMULASI INFO
				
				//SUMMARY
					$sum_laporan_h_penjualan = $this->M_gl_lap_penjualan->count_h_penjualan($cari_h);
					//$sum_laporan_h_penjualan = $sum_laporan_h_penjualan->row();
				//SUMMARY
				
				$msgbox_title = " Laporan Transaksi Detail ";
				
				$list_kantor = $this->M_gl_pengaturan->get_data_kantor("");
				
				$data = array('page_content'=>'gl_pusat_akun_transaksi','halaman'=>$halaman, 'sum_pesan' => $sum_pesan,'msgbox_title' => $msgbox_title,'list_laporan_d_penjualan_row' => $list_laporan_d_penjualan_row, 'sum_laporan_h_penjualan' => $sum_laporan_h_penjualan,'list_kantor' => $list_kantor);
				$this->load->view('pusat/container',$data);
				
				
				//$data = array('page_content'=>'gl_pusat_alur_produk','msgbox_title' => $msgbox_title, 'list_alur_produk' => $list_alur_produk,'list_kantor' => $list_kantor);
				//$this->load->view('pusat/container',$data);
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/C_gl_pst_inventori.php */