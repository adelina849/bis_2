<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_gl_admin_lap_pengajuan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		//$this->load->model(array('M_berita','M_kat_berita','M_images'));
		$this->load->model(array('M_gl_pengajuan'));
		
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
					
					$cari = "
						WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
						AND DATE(A.tgl_terima) BETWEEN '".$dari."' AND '".$sampai."'
						
						AND
						(
							COALESCE(B.KEC_PENGAJU,'') LIKE '%".$_GET['cari']."%'
							OR COALESCE(C.KEC_MUSTAHIK,'') LIKE '%".$_GET['cari']."%'
						)
				
					";
				}
				else
				{
					$dari = date("Y-m-d");
					$sampai = date("Y-m-d");
					
					$cari = "
						WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
						AND DATE(A.tgl_terima) BETWEEN '".$dari."' AND '".$sampai."'
				
					";
				}
				
				
				
				
				
				$order_by = " ORDER BY A.tgl_ins DESC";
				$list_pengajuan = false; //$this->M_gl_pengajuan->list_pengajuan_limit($cari,$order_by,1000000,0);
				
				$list_by_jenis_bantuan = $this->M_gl_pengajuan->list_lap_by_group_sum($cari,'A.jenis_pengajuan');
				
				$list_by_ashnaf = $this->M_gl_pengajuan->list_lap_by_group_sum($cari,'D.ashnaf');
				
				$list_by_kode_program = $this->M_gl_pengajuan->list_lap_by_group_sum($cari,'A.kode_program');
				
				$msgbox_title = " Laporan Pelayanan Ajuan Proposal/Dokumen";
				
				$data = array('page_content'=>'gl_admin_lap_pengajuan','list_pengajuan'=>$list_pengajuan,'msgbox_title' => $msgbox_title,'list_by_jenis_bantuan' => $list_by_jenis_bantuan,'list_by_ashnaf' => $list_by_ashnaf,'list_by_kode_program' => $list_by_kode_program);
				$this->load->view('admin/container',$data);
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
	
	public function export_excel()
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
				
				$cari = "
						WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
						AND DATE(A.tgl_terima) BETWEEN '".$dari."' AND '".$sampai."'
				
				";
				
				
				
				$order_by = " ORDER BY A.tgl_ins DESC";
				$list_pengajuan = $this->M_gl_pengajuan->list_pengajuan_limit($cari,$order_by,1000000,0);
				
				$list_by_jenis_bantuan = $this->M_gl_pengajuan->list_lap_by_group_sum($cari,'A.jenis_pengajuan');
				
				$list_by_ashnaf = $this->M_gl_pengajuan->list_lap_by_group_sum($cari,'B.ashnaf');
				
				$list_by_kode_program = $this->M_gl_pengajuan->list_lap_by_group_sum($cari,'A.kode_program');
				
				$msgbox_title = " Laporan Pelayanan Ajuan Proposal/Dokumen";
				
				$data = array('page_content'=>'gl_admin_lap_pengajuan','list_pengajuan'=>$list_pengajuan,'msgbox_title' => $msgbox_title,'list_by_jenis_bantuan' => $list_by_jenis_bantuan,'list_by_ashnaf' => $list_by_ashnaf,'list_by_kode_program' => $list_by_kode_program);
				//$this->load->view('admin/container',$data);
				
				$this->load->view('admin/page/gl_admin_excel_lap_pengajuan.html',$data);
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
	
	public function detail()
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
					
					$cari = "
						WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
						AND DATE(A.tgl_terima) BETWEEN '".$dari."' AND '".$sampai."'
						
						AND
						(
							COALESCE(B.KEC_PENGAJU,'') LIKE '%".$_GET['cari']."%'
							OR COALESCE(C.KEC_MUSTAHIK,'') LIKE '%".$_GET['cari']."%'
							OR COALESCE(B.nama_lengkap,'') LIKE '%".$_GET['cari']."%'
							OR COALESCE(C.nama_lengkap,'') LIKE '%".$_GET['cari']."%'
							OR COALESCE(A.no_reg,'') LIKE '%".$_GET['cari']."%'
						)
				
					";
				}
				else
				{
					$dari = date("Y-m-d");
					$sampai = date("Y-m-d");
					
					$cari = "
						WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
						AND DATE(A.tgl_terima) BETWEEN '".$dari."' AND '".$sampai."'
				
					";
				}
				
				
				
				$order_by = " ORDER BY A.tgl_ins DESC";
				$list_pengajuan = $this->M_gl_pengajuan->list_pengajuan_limit($cari,$order_by,1000000,0);
				
				$msgbox_title = " Laporan Pelayanan Ajuan Proposal/Dokumen Detail";
				
				$data = array('page_content'=>'gl_admin_lap_pengajuan_detail','list_pengajuan'=>$list_pengajuan,'msgbox_title' => $msgbox_title);
				$this->load->view('admin/container',$data);
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
	
	public function export_excel_detail()
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
					
					$cari = "
						WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
						AND DATE(A.tgl_terima) BETWEEN '".$dari."' AND '".$sampai."'
						
						AND
						(
							COALESCE(B.KEC_PENGAJU,'') LIKE '%".$_GET['cari']."%'
							OR COALESCE(C.KEC_MUSTAHIK,'') LIKE '%".$_GET['cari']."%'
							OR COALESCE(B.nama_lengkap,'') LIKE '%".$_GET['cari']."%'
							OR COALESCE(C.nama_lengkap,'') LIKE '%".$_GET['cari']."%'
							OR COALESCE(A.no_reg,'') LIKE '%".$_GET['cari']."%'
						)
				
					";
				}
				else
				{
					$dari = date("Y-m-d");
					$sampai = date("Y-m-d");
					
					$cari = "
						WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
						AND DATE(A.tgl_terima) BETWEEN '".$dari."' AND '".$sampai."'
				
					";
				}
				
				
				
				$order_by = " ORDER BY A.tgl_ins DESC";
				$list_pengajuan = $this->M_gl_pengajuan->list_pengajuan_limit($cari,$order_by,1000000,0);
				
				$msgbox_title = " Laporan Pelayanan Ajuan Proposal/Dokumen Detail";
				
				$data = array('page_content'=>'gl_admin_lap_pengajuan','list_pengajuan'=>$list_pengajuan,'msgbox_title' => $msgbox_title);
				//$this->load->view('admin/container',$data);
				
				$this->load->view('admin/page/gl_admin_excel_lap_pengajuan_detail.html',$data);
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}

	public function laporan_surat()
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
				if((!empty($_GET['in_out'])) && ($_GET['in_out']!= "")  )
				{
					if($_GET['in_out'] == 'MASUK')
					{
						$in_out = 'IN';
					}
					else
					{
						$in_out = 'OUT';
					}
				}
				else
				{
					$in_out = 'IN';
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
					$cari = $_GET['cari'];
				}
				else
				{
					$cari = '';
				}					
				
				$query = "
							SELECT 
								tgl_masuk AS tgl_diterima 
								,tgl_acara AS tgl_surat
								,no_surat
								,tempat_acara
								,perihal
								,dari
								,ket_surat
							FROM tb_surat 
							WHERE 
								in_out = '".$in_out."'
								AND DATE(tgl_ins) BETWEEN '".$dari."' AND '".$sampai."'
								AND 
								(
									no_surat LIKE '%".$cari."%'
									OR tempat_acara LIKE '%".$cari."%'
									OR perihal LIKE '%".$cari."%'
									OR dari LIKE '%".$cari."%'
								)
							GROUP BY 
								tgl_masuk
								,tgl_acara
								,no_surat
								,tempat_acara
								,perihal
								,dari
								,ket_surat
							ORDER BY tgl_ins DESC
							;
				";
				
				$list_surat = $this->M_gl_pengaturan->view_query_general($query);
				
				if($in_out == 'MASUK')
				{
					$laporan_title = " Laporan Pengelolaan Surat Masuk";
				}
				else
				{
					$laporan_title = " Laporan Pengelolaan Surat Keluar";
				}	
				
				$msgbox_title = " Laporan pengelolaan surat masuk dan keluar";
				
				
				$data = array('page_content'=>'gl_admin_lap_surat','list_surat'=>$list_surat,'laporan_title'=>$laporan_title,'msgbox_title' => $msgbox_title,'in_out' => $in_out);
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
/* Location: ./application/controllers/C_gl_admin_lap_pengajuan.php */