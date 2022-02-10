<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_gl_admin_acc_buku_besar extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		//$this->load->model(array('M_berita','M_kat_berita','M_images'));
		$this->load->model(array('M_gl_acc_buku_besar'));
		
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
				
				
				
				
				
				$list_acc_buku_besar = $this->M_gl_acc_buku_besar->list_acc_buku_besar($this->session->userdata('ses_kode_kantor'),$dari,$sampai);
				
				
				$msgbox_title = " Laporan Buku Besar ";
				
				$data = array('page_content'=>'gl_admin_lap_acc_buku_besar','msgbox_title' => $msgbox_title,'list_acc_buku_besar' => $list_acc_buku_besar);
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
/* Location: ./application/controllers/c_admin_jabatan.php */

