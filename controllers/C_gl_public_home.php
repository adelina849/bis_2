<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_gl_public_home extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		//$this->load->model(array('M_dash'));
	}
	
	public function index()
	{
		//$data = array('page_content'=>'gl_admin_dashboard');
		//$this->load->view('admin/container',$data);
		$data_kantor = $this->M_gl_pengaturan->get_data_kantor("WHERE isViewClient = 0");
		$data = array('data_kantor'=>$data_kantor);
		$this->load->view('public/home/gl_public_home.html',$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/C_gl_public_home.php */