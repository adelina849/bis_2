<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_gl_admin_cek_pasien extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		//$this->load->model(array('M_berita','M_kat_berita','M_images'));
		$this->load->model(array('M_gl_pst_satuan','M_gl_satuan'));
		
	}
	
	function index()
	{
		
		//$arr = array('data', 'data2' => 'more data', 'data3' => array('even', 'more', 'data'));
		//$arr = "https://api.rssekarwangi.co.id/baznas/token";
		
		$file = "http://d.yimg.com/autoc.finance.yahoo.com/autoc?query=yahoo&callback=YAHOO.Finance.SymbolSuggest.ssCallback";
		
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: application/json');
		header('x-username: baznas_user');
		header('x-password: 85Dmec5S5g');
		header('Content-type: application/json');
		//echo json_encode($arr);
		
		//$file = "http://d.yimg.com/autoc.finance.yahoo.com/autoc?query=yahoo&callback=YAHOO.Finance.SymbolSuggest.ssCallback";
		$data = file_get_contents($file);
		$data = mb_substr($data, strpos($data, '{'));
		$data = mb_substr($data, 0, -1);
		$result = json_decode($data, true);
		//print_r($result['ResultSet']['Result'][0]);
		print_r($result['ResultSet']);
	}
	
	
	public function index_ori()
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
				
				$msgbox_title = 'Cek Status Pasien';
				
				$data = array('page_content'=>'gl_admin_cek_pasien','msgbox_title' => $msgbox_title);
				$this->load->view('admin/container',$data);
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
	
	function cek_status_pasien()
	{
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/c_admin_jabatan.php */