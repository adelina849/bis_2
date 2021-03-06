<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_gl_admin_disposisi extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model(array('M_gl_pengajuan','M_gl_costumer','M_gl_kode_program'));
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
				
				if((!empty($_GET['status_disposisi'])) && ($_GET['status_disposisi']!= "")  )
				{
					if($_GET['status_disposisi'] == "ALL")
					{
						$status_disposisi = "";
					}
					elseif($_GET['status_disposisi'] == "DISPOSISI")
					{
						$status_disposisi = " AND A.kode_program <> ''";
					}
					elseif($_GET['status_disposisi'] == "BELUM")
					{
						$status_disposisi = " AND A.kode_program = ''";
					}
					else
					{
						$status_disposisi = "";
					}
					
				}
				else
				{
					$status_disposisi = "";
				}
				
				if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
				{
					$cari = "WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' 
							AND DATE(A.tgl_ins) BETWEEN '".$dari."' AND '".$sampai."'
							".$status_disposisi."
							AND 
								(
									no_reg LIKE '%".str_replace("'","",$_GET['cari'])."%' 
									OR no_kode LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR no_surat LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR COALESCE(B.nama_lengkap,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR COALESCE(C.nama_lengkap,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
								)";
				}
				else
				{
					$cari = "WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ".$status_disposisi." 
							AND DATE(A.tgl_ins) BETWEEN '".$dari."' AND '".$sampai."'";
				}
				
				$order_by = " ORDER BY A.tgl_ins DESC";
				
				$this->load->library('pagination');
				//$config['first_url'] = base_url().'admin/jabatan?'.http_build_query($_GET);
				//$config['base_url'] = base_url().'admin/jabatan/';
				
				$config['first_url'] = site_url('gl-admin-disposisi?'.http_build_query($_GET));
				$config['base_url'] = site_url('gl-admin-disposisi/');
				$config['total_rows'] = $this->M_gl_pengajuan->count_pengajuan_limit($cari)->JUMLAH;
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
				
				$list_pengajuan = $this->M_gl_pengajuan->list_pengajuan_limit($cari,$order_by,$config['per_page'],$this->uri->segment(2,0));
				
				$msgbox_title = " Disposisi Pengajuan";
				
				$data = array('page_content'=>'gl_admin_disposisi_pengajuan','halaman'=>$halaman,'list_pengajuan'=>$list_pengajuan,'msgbox_title' => $msgbox_title);
				$this->load->view('admin/container',$data);
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
	
	public function proses_ajuan()
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
				if((!empty($_GET['id'])) && ($_GET['id']!= "")  )
				{
					$id_pengajuan = $_GET['id'];
				}
				else
				{
					$id_pengajuan = "";
				}
				
				$cari = "WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' AND (A.id_pengajuan) = '".$id_pengajuan."'";
				
				$order_by = " ORDER BY A.tgl_ins DESC";
				$data_pengajuan = $this->M_gl_pengajuan->list_pengajuan_limit($cari,$order_by,1,0);
				
				if(!empty($data_pengajuan))
				{
					$data_pengajuan = $data_pengajuan->row();
					$get_data_pengajuan = $data_pengajuan;
					
					
					$cari_kode_program = "WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' AND isAktif = 'YA'";
					$order_by_kode_program = "ORDER BY kd_prog ASC";
					$list_kode_program = $this->M_gl_kode_program->list_kode_program_limit($cari_kode_program,$order_by_kode_program,100000,0);
					$data = array('page_content'=>'gl_admin_view_disposisi','get_data_pengajuan'=>$get_data_pengajuan,'list_kode_program' => $list_kode_program);
					$this->load->view('admin/page/gl_admin_view_disposisi.html',$data);
				}
				else
				{
					header('Location: '.base_url().'gl-admin-disposisi');
				}
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
	
	public function print_proses_ajuan()
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
				if((!empty($_GET['id'])) && ($_GET['id']!= "")  )
				{
					$id_pengajuan = $_GET['id'];
				}
				else
				{
					$id_pengajuan = "";
				}
				
				$cari = "WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' AND (A.id_pengajuan) = '".$id_pengajuan."'";
				
				$order_by = " ORDER BY A.tgl_ins DESC";
				$data_pengajuan = $this->M_gl_pengajuan->list_pengajuan_limit($cari,$order_by,1,0);
				
				if(!empty($data_pengajuan))
				{
					$data_pengajuan = $data_pengajuan->row();
					$get_data_pengajuan = $data_pengajuan;
					
					
					$cari_kode_program = "WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'";
					$order_by_kode_program = "ORDER BY kd_prog ASC";
					$list_kode_program = $this->M_gl_kode_program->list_kode_program_limit($cari_kode_program,$order_by_kode_program,100000,0);
					$data = array('page_content'=>'gl_admin_print_disposisi','get_data_pengajuan'=>$get_data_pengajuan,'list_kode_program' => $list_kode_program);
					$this->load->view('admin/page/gl_admin_print_disposisi.html',$data);
				}
				else
				{
					header('Location: '.base_url().'gl-admin-disposisi');
				}
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
	
	public function acc_pengajuan()
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
				if((!empty($_GET['status_acc'])) && ($_GET['status_acc']!= "")  )
				{
					if($_GET['status_acc'] == "ALL")
					{
						$status_acc = "";
					}
					elseif($_GET['status_acc'] == "ACC")
					{
						$status_acc = " AND A.nominal > 0";
					}
					elseif($_GET['status_acc'] == "BELUM")
					{
						$status_acc = " AND A.nominal <= 0";
					}
					else
					{
						$status_acc = "";
					}
					
				}
				else
				{
					$status_acc = "";
				}
				
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
							AND A.kode_program <> ''
							".$status_acc."
							AND DATE(A.tgl_ins) BETWEEN '".$dari."' AND '".$sampai."'
							AND 
								(
									no_reg LIKE '%".str_replace("'","",$_GET['cari'])."%' 
									OR no_kode LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR no_surat LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR COALESCE(B.nama_lengkap,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR COALESCE(C.nama_lengkap,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
								)";
				}
				else
				{
					$cari = "WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' AND A.kode_program <> ''
					".$status_acc."
					AND DATE(A.tgl_ins) BETWEEN '".$dari."' AND '".$sampai."'
					";
				}
				
				$order_by = " ORDER BY A.tgl_ins DESC";
				
				$this->load->library('pagination');
				//$config['first_url'] = base_url().'admin/jabatan?'.http_build_query($_GET);
				//$config['base_url'] = base_url().'admin/jabatan/';
				
				$config['first_url'] = site_url('gl-admin-disposisi-acc?'.http_build_query($_GET));
				$config['base_url'] = site_url('gl-admin-disposisi-acc/');
				$config['total_rows'] = $this->M_gl_pengajuan->count_pengajuan_limit($cari)->JUMLAH;
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
				
				$list_pengajuan = $this->M_gl_pengajuan->list_pengajuan_limit($cari,$order_by,$config['per_page'],$this->uri->segment(2,0));
				
				$msgbox_title = " Acc/Persetujuan Pengajuan Proposal";
				
				$data = array('page_content'=>'gl_admin_disposisi_pengajuan_acc','halaman'=>$halaman,'list_pengajuan'=>$list_pengajuan,'msgbox_title' => $msgbox_title);
				$this->load->view('admin/container',$data);
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
	
	public function penyerahan_pengajuan()
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
				if((!empty($_GET['status_acc'])) && ($_GET['status_acc']!= "")  )
				{
					if($_GET['status_acc'] == "ALL")
					{
						$status_acc = "";
					}
					elseif($_GET['status_acc'] == "DISALURKAN")
					{
						$status_acc = " AND A.tgl_penyerahan <> '0000-00-00'";
					}
					elseif($_GET['status_acc'] == "BELUM")
					{
						$status_acc = " AND A.tgl_penyerahan = '0000-00-00'";
					}
					else
					{
						$status_acc = "";
					}
					
				}
				else
				{
					$status_acc = "";
				}
				
				
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
						AND DATE(A.tgl_ins) BETWEEN '".$dari."' AND '".$sampai."'
							AND A.kode_program <> ''
							AND A.nominal >0
							".$status_acc."
							AND 
								(
									no_reg LIKE '%".str_replace("'","",$_GET['cari'])."%' 
									OR no_kode LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR no_surat LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR COALESCE(B.nama_lengkap,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR COALESCE(C.nama_lengkap,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
								)";
				}
				else
				{
					$cari = "WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
						AND DATE(A.tgl_ins) BETWEEN '".$dari."' AND '".$sampai."'
						AND A.kode_program <> ''
						AND A.nominal >0
						".$status_acc."
					";
				}
				
				$order_by = " ORDER BY A.tgl_ins DESC";
				
				$this->load->library('pagination');
				//$config['first_url'] = base_url().'admin/jabatan?'.http_build_query($_GET);
				//$config['base_url'] = base_url().'admin/jabatan/';
				
				$config['first_url'] = site_url('gl-admin-disposisi-penyerahan?'.http_build_query($_GET));
				$config['base_url'] = site_url('gl-admin-disposisi-penyerahan/');
				$config['total_rows'] = $this->M_gl_pengajuan->count_pengajuan_limit($cari)->JUMLAH;
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
				
				$list_pengajuan = $this->M_gl_pengajuan->list_pengajuan_limit($cari,$order_by,$config['per_page'],$this->uri->segment(2,0));
				
				$msgbox_title = " Penyerahan Penyaluran Proposal";
				
				$data = array('page_content'=>'gl_admin_disposisi_pengajuan_penyaluran','halaman'=>$halaman,'list_pengajuan'=>$list_pengajuan,'msgbox_title' => $msgbox_title);
				$this->load->view('admin/container',$data);
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
	
	function edit_1_field()
	{
		$id = $_POST['id_pengajuan'];
		$berdasarkan = $_POST['berdasarkan'];
		$isi = $_POST['isi'];
		$this->M_gl_pengajuan->edit_1_field($berdasarkan,$isi,$id);
		echo"BERHASIL";
	}
	
	function edit_1_field_2()
	{
		$id = $_POST['id_pengajuan'];
		$berdasarkan = $_POST['berdasarkan'];
		$isi = $_POST['isi'];
		$this->M_gl_pengajuan->edit_1_field($berdasarkan,$isi,$id);
		//echo"BERHASIL";
		
		$cari = "WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' AND (A.id_pengajuan) = '".$id."'";
		
		$order_by = " ORDER BY A.tgl_ins DESC";
		$data_pengajuan = $this->M_gl_pengajuan->list_pengajuan_limit($cari,$order_by,1,0);
		
		if(!empty($data_pengajuan))
		{
			$data_pengajuan = $data_pengajuan->row();
			
			echo $data_pengajuan->prog.'|'.$data_pengajuan->ashnaf.'|'.$data_pengajuan->uraian.'|'.$data_pengajuan->no_prog.'|';
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/C_gl_admin_disposisi.php */