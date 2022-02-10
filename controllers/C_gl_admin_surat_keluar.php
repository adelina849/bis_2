<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_gl_admin_surat_keluar extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		//$this->load->model(array('M_berita','M_kat_berita','M_images'));
		$this->load->model(array('M_gl_surat'));
		
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
				
				if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
				{
					$cari = "WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' 
							AND A.in_out = 'OUT'
							AND DATE(A.tgl_masuk) BETWEEN '".$dari."' AND '".$sampai."'
							AND (
									A.no_surat LIKE '%".str_replace("'","",$_GET['cari'])."%' 
									OR A.dari LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR A.perihal LIKE '%".str_replace("'","",$_GET['cari'])."%'
								)";
				}
				else
				{
					$cari = "WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
							AND A.in_out = 'OUT'
							AND DATE(A.tgl_masuk) BETWEEN '".$dari."' AND '".$sampai."'
							";
				}
				
				$this->load->library('pagination');
				//$config['first_url'] = base_url().'admin/jabatan?'.http_build_query($_GET);
				//$config['base_url'] = base_url().'admin/jabatan/';
				
				$config['first_url'] = site_url('gl-admin-surat-keluar?'.http_build_query($_GET));
				$config['base_url'] = site_url('gl-admin-surat-keluar/');
				$config['total_rows'] = $this->M_gl_surat->count_surat_limit($cari)->JUMLAH;
				$config['uri_segment'] = 2;	
				$config['per_page'] = 30;
				$config['num_links'] = 2;
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
				
				$order_by = "ORDER BY tgl_ins DESC";
				$list_surat = $this->M_gl_surat->list_surat_limit($cari,$order_by,$config['per_page'],$this->uri->segment(2,0));
				
				$msgbox_title = " Surat keluar";
				
				$data = array('page_content'=>'gl_admin_surat_keluar','halaman'=>$halaman,'list_surat'=>$list_surat,'msgbox_title' => $msgbox_title);
				$this->load->view('admin/container',$data);
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
	
	public function simpan()
	{
		if(($this->session->userdata('ses_user_admin') == null) or ($this->session->userdata('ses_pass_admin') == null))
		{
			header('Location: '.base_url().'gl-admin-login');
		}
		else
		{
			$cek_ses_login = $this->M_gl_karyawan->get_karyawan_jabatan_row(" WHERE A.user = '".$this->session->userdata('ses_user_admin')."' AND A.pass = '".base64_encode(md5($this->session->userdata('ses_pass_admin_pure')))."';");
			
			if(!empty($cek_ses_login))
			{
				if (!empty($_POST['stat_edit']))
				{	
					$this->M_gl_surat->edit
					(
						$_POST['stat_edit'],
						$_POST['kat_surat'],
						$_POST['no_surat'],
						$_POST['in_out'],
						$_POST['perihal'],
						$_POST['dari'],
						$_POST['kepada'],
						$_POST['tgl_masuk'],
						$_POST['nama_acara'],
						$_POST['tempat_acara'],
						$_POST['tgl_acara'],
						$_POST['ket_surat'],
						$this->session->userdata('ses_id_karyawan'),
						$this->session->userdata('ses_kode_kantor')
						
						
					);
					
					/* CATAT AKTIFITAS EDIT*/
					if($this->session->userdata('catat_log') == 'Y')
					{
						$this->M_gl_log->simpan_log
						(
							$this->session->userdata('ses_id_karyawan'),
							'UPDATE',
							'Melakukan perubahan data Surat keluar '.$_POST['no_surat'].' | '.$_POST['perihal'],
							$this->M_gl_pengaturan->getUserIpAddr(),
							gethostname(),
							$this->session->userdata('ses_kode_kantor')
						);
					}
					/* CATAT AKTIFITAS EDIT*/
				}
				else
				{
					$this->M_gl_surat->simpan
					(
						$_POST['kat_surat'],
						$_POST['no_surat'],
						$_POST['in_out'],
						$_POST['perihal'],
						$_POST['dari'],
						$_POST['kepada'],
						$_POST['tgl_masuk'],
						$_POST['nama_acara'],
						$_POST['tempat_acara'],
						$_POST['tgl_acara'],
						$_POST['ket_surat'],
						$this->session->userdata('ses_id_karyawan'),
						$this->session->userdata('ses_kode_kantor')
					);
				}
				header('Location: '.base_url().'gl-admin-surat-keluar');
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
	
	public function hapus()
	{
		$id = ($this->uri->segment(2,0));
		
		$cari = " WHERE MD5(id_surat) = '".$id."' AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ";
		$data_surat = $this->M_gl_surat->get_surat($cari);
		if(!empty($data_surat))
		{
			$data_surat = $data_surat->row();
			$this->M_gl_surat->hapus($cari);
			
			/* CATAT AKTIFITAS HAPUS*/
			if($this->session->userdata('catat_log') == 'Y')
			{
				$this->M_gl_log->simpan_log
				(
					$this->session->userdata('ses_id_karyawan'),
					'DELETE',
					'Melakukan penghapusan data Surat keluar '.$data_surat->no_surat.' | '.$data_surat->perihal,
					$this->M_gl_pengaturan->getUserIpAddr(),
					gethostname(),
					$this->session->userdata('ses_kode_kantor')
				);
			}
			/* CATAT AKTIFITAS HAPUS*/
		}
		
		header('Location: '.base_url().'gl-admin-surat-keluar');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/c_admin_jabatan.php */