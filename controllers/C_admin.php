<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model(array('M_dash'));
	}
	
	public function index()
	{
		if(($this->session->userdata('ses_user_admin') == null) or ($this->session->userdata('ses_pass_admin') == null))
		{
			header('Location: '.base_url().'admin-login');
		}
		else
		{
			$cek_ses_login = $this->M_akun->get_cek_login($this->session->userdata('ses_user_admin'),md5(base64_decode($this->session->userdata('ses_pass_admin'))));
			
			if(!empty($cek_ses_login))
			{
				$ST_PENERIMAAN_PERPERIODE = $this->M_dash->ST_PENERIMAAN_PERPERIODE();
				
				if((!empty($_GET['tahun'])) && ($_GET['tahun']!= "")   && (!empty($_GET['bulan'])) && ($_GET['bulan']!= "")  )
				{
					$LIST_KOTAK_PER_KECAMATAN = $this->M_dash->LIST_KOTAK_PER_KECAMATAN_CARI($_GET['tahun'],$_GET['bulan']);
				}
				else
				{
					$LIST_KOTAK_PER_KECAMATAN = $this->M_dash->LIST_KOTAK_PER_KECAMATAN();
				}
				
				$data = array('page_content'=>'admin_dashboard','ST_PENERIMAAN_PERPERIODE' => $ST_PENERIMAAN_PERPERIODE,'LIST_KOTAK_PER_KECAMATAN'=>$LIST_KOTAK_PER_KECAMATAN);
				$this->load->view('admin/container',$data);
				//echo "Hallo World";
			}
			else
			{
				header('Location: '.base_url().'admin-login');
			}
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/c_admin.php */