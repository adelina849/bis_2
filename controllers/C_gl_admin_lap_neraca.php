<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_gl_admin_lap_neraca extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		//$this->load->model(array('M_berita','M_kat_berita','M_images'));
		$this->load->model(array('M_gl_log_aktifitas','M_gl_lap_neraca'));
		
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
				
				
				$msgbox_title = " Laporan Neraca Perusahaan";
				
				
				
				$data = array('page_content'=>'gl_admin_lap_neraca','msgbox_title' => $msgbox_title);
				$this->load->view('admin/container',$data);
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
	
	function get_kas()
	{
		$hasil_cek = $this->M_gl_lap_neraca->get_kas
													(
														$this->session->userdata('ses_kode_kantor')
														,$_POST['id_bank']
														,''
														,$_POST['cari']
														,$_POST['tgl_sampai']
													);
		if(!empty($hasil_cek))
		{
			$hasil_cek = $hasil_cek->row();
			$hasil_cek = $hasil_cek->DEBIT - $hasil_cek->KREDIT;
		}
		else
		{
			$hasil_cek = 0;
		}
		
		echo $hasil_cek;
	}
	
	function get_stock_persediaan()
	{
		$hasil_cek = $this->M_gl_lap_neraca->get_persediaan
													(
														$this->session->userdata('ses_kode_kantor')
														,''
														,$_POST['tgl_sampai']
														,'23'
														,'59'
													);
		if(!empty($hasil_cek))
		{
			$hasil_cek = $hasil_cek->row();
			$hasil_cek = $hasil_cek->NOMINAL_STOCK;
		}
		else
		{
			$hasil_cek = 0;
		}
		
		echo $hasil_cek;
	}
	
	function get_piutang_awal_tr()
	{
		$hasil_cek = $this->M_gl_lap_neraca->get_piutang_awal_tr
													(
														$this->session->userdata('ses_kode_kantor')
														,$_POST['tgl_sampai']
														,'1990-01-01 00:00:00'
													);
		if(!empty($hasil_cek))
		{
			$hasil_cek = $hasil_cek->row();
			$hasil_cek = $hasil_cek->SISA_PIUTANG_AWAL ."|".$hasil_cek->SISA_PIUTANG_TR;
		}
		else
		{
			$hasil_cek = "0|0";
		}
		
		echo $hasil_cek;
	}
	
	function get_hutang_awal()
	{
		$hasil_cek = $this->M_gl_lap_neraca->get_hutang_awal
													(
														$this->session->userdata('ses_kode_kantor')
														,$_POST['tgl_sampai']
													);
		if(!empty($hasil_cek))
		{
			$hasil_cek = $hasil_cek->row();
			$hasil_cek = $hasil_cek->SISA_HUTANG_AWAL;
		}
		else
		{
			$hasil_cek = "0";
		}
		
		echo $hasil_cek;
	}
	
	function get_hutang_tr()
	{
		$hasil_cek = $this->M_gl_lap_neraca->get_hutang_tr
													(
														$this->session->userdata('ses_kode_kantor')
														,$_POST['tgl_sampai']
														,'1990-01-01 00:00:00'
													);
		if(!empty($hasil_cek))
		{
			$hasil_cek = $hasil_cek->row();
			$hasil_cek = $hasil_cek->HUTANG;
		}
		else
		{
			$hasil_cek = "0";
		}
		
		echo $hasil_cek;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/C_gl_admin_lap_neraca.php */