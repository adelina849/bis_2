<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_gl_admin_lap_mutasi extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		//$this->load->model(array('M_berita','M_kat_berita','M_images'));
		$this->load->model(array('M_gl_h_mutasi'));
		
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
				
				if((!empty($_GET['jenis_mutasi'])) && ($_GET['jenis_mutasi']!= "")  )
				{
					$jenis_mutasi = $_GET['jenis_mutasi'];
				}
				else
				{
					$jenis_mutasi = "";
				}
				
				if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
				{
					$cari = "
							WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' AND COALESCE(DATE(B.tgl_h_penjualan),'') BETWEEN '".$dari."' AND '".$sampai."'
							AND COALESCE(B.status_penjualan,'') LIKE '%".$jenis_mutasi."%'
							AND (
								
								COALESCE(C.nama_kantor,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
								OR COALESCE(B.no_faktur,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
								OR COALESCE(D.kode_produk,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
								OR COALESCE(D.nama_produk,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
								)
							";
				}
				else
				{
					$cari = "
						WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' 
						AND COALESCE(DATE(B.tgl_h_penjualan),'') BETWEEN '".$dari."' AND '".$sampai."' 
						AND COALESCE(B.status_penjualan,'') LIKE '%".$jenis_mutasi."%'
						";
				}
				
				$order_by = "ORDER BY A.tgl_ins DESC";
				
				$list_h_mutasi = $this->M_gl_h_mutasi->list_lap_detail_mutasi($cari,$order_by,1000000,0);
			
				$msgbox_title = " Laporan Mutasi Produk";
				
				$data = array('page_content'=>'gl_admin_lap_h_mutasi','list_h_mutasi'=>$list_h_mutasi,'msgbox_title' => $msgbox_title);
				$this->load->view('admin/container',$data);
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
	
	function list_mutasi_dari_cabang_lain()
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
				
				
				if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
				{
					$cari = "
							WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' AND COALESCE(DATE(B.tgl_h_penjualan),'') BETWEEN '".$dari."' AND '".$sampai."'
							AND COALESCE(B.status_penjualan,'') = 'MUTASI-OUT'
							AND (
								A.kode_kantor_mutasi LIKE '%".str_replace("'","",$_GET['cari'])."%' 
								OR COALESCE(C.nama_kantor,'') LIKE '%".str_replace("'","",$_GET['cari'])."%')
								OR COALESCE(B.no_faktur,'') LIKE '%".str_replace("'","",$_GET['cari'])."%')
								OR COALESCE(D.kode_produk,'') LIKE '%".str_replace("'","",$_GET['cari'])."%')
								OR COALESCE(D.nama_produk,'') LIKE '%".str_replace("'","",$_GET['cari'])."%')
							";
				}
				else
				{
					$cari = "
						WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' 
						AND COALESCE(DATE(B.tgl_h_penjualan),'') BETWEEN '".$dari."' AND '".$sampai."' 
						AND COALESCE(B.status_penjualan,'') = 'MUTASI-OUT'
						";
				}
				
				$order_by = "ORDER BY A.tgl_ins DESC";
				
				$list_h_mutasi = $this->M_gl_h_mutasi->list_lap_detail_mutasi($cari,$order_by,1000000,0);
			
				$msgbox_title = " Laporan Mutasi Produk";
				
				$data = array('page_content'=>'gl_admin_lap_h_mutasi_dari_cabang_lain','list_h_mutasi'=>$list_h_mutasi,'msgbox_title' => $msgbox_title);
				$this->load->view('admin/container',$data);
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/C_gl_admin_lap_mutasi.php */