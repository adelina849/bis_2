<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_gl_admin_pengajuan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		//$this->load->model(array('M_berita','M_kat_berita','M_images'));
		$this->load->model(array('M_gl_pengajuan','M_gl_costumer'));
		
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
							AND DATE(A.tgl_ins) BETWEEN '".$dari."' AND '".$sampai."'
							AND 
								(
									no_reg LIKE '%".str_replace("'","",$_GET['cari'])."%' 
									OR no_kode LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR no_surat LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR COALESCE(B.nama_lengkap,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR COALESCE(C.nama_lengkap,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR COALESCE(B.alamat_rumah_sekarang,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR COALESCE(C.alamat_rumah_sekarang,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR COALESCE(C.KEC_MUSTAHIK,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR COALESCE(B.KEC_PENGAJU,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
								)";
				}
				else
				{
					$cari = "WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' 
							AND DATE(A.tgl_ins) BETWEEN '".$dari."' AND '".$sampai."'";
				}
				
				$order_by = " ORDER BY A.tgl_ins DESC";
				
				$this->load->library('pagination');
				//$config['first_url'] = base_url().'admin/jabatan?'.http_build_query($_GET);
				//$config['base_url'] = base_url().'admin/jabatan/';
				
				$config['first_url'] = site_url('gl-admin-pengajuan?'.http_build_query($_GET));
				$config['base_url'] = site_url('gl-admin-pengajuan/');
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
				
				$get_no_pengajuan = $this->M_gl_pengajuan->get_no_pengajuan($this->session->userdata('ses_kode_kantor'));
				if(!empty($get_no_pengajuan))
				{
					$get_no_pengajuan = $get_no_pengajuan->NO_REG_FORMAT;
				}
				else
				{
					$get_no_pengajuan = 0;
				}
				
				$get_no_kode = $this->M_gl_pengajuan->get_no_kode_per_jenis($this->session->userdata('ses_kode_kantor'),'B');
				if(!empty($get_no_kode))
				{
					$get_no_kode = 'B.'.$get_no_kode->NO_KODE_FORMAT;
				}
				else
				{
					$get_no_kode = 0;
				}
				
				$msgbox_title = " Pengajuan Bantuan Via Proposal Ajuan";
				
				$data = array('page_content'=>'gl_admin_pengajuan','halaman'=>$halaman,'list_pengajuan'=>$list_pengajuan,'msgbox_title' => $msgbox_title,'get_no_pengajuan' => $get_no_pengajuan,'get_no_kode' => $get_no_kode);
				$this->load->view('admin/container',$data);
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
	
	public function tambah_mustahik()
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
				$get_md5_id_pengajuan = ($this->uri->segment(2,0));
				$cari_pengajuan = 
				"
					WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' AND MD5(id_pengajuan) = '".$get_md5_id_pengajuan."';
				";
				$hasil_cek_pengajuan = $this->M_gl_pengajuan->cek_pengajuan($cari_pengajuan);
				if(!empty($hasil_cek_pengajuan))
				{
					$hasil_cek_pengajuan = $hasil_cek_pengajuan->row();
					
					if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
					{
						$cari = $_GET['cari'];
					}
					else
					{
						$cari = "";
					}
					
					$query_list_costumer_pengajuan = 
					"
						SELECT
							A.*
							,COALESCE(B.id_costumer,'') AS CEK_COSTUMER
							,COALESCE(C.nama_kat_costumer,'') AS nama_kat_costumer
						FROM tb_costumer AS A
						LEFT JOIN tb_pengajuan_tambah_mustahik AS B ON A.id_costumer = B.id_costumer AND MD5(B.id_pengajuan) = '".$get_md5_id_pengajuan."'
						LEFT JOIN tb_kat_costumer AS C ON A.kode_kantor = C.kode_kantor AND A.id_kat_costumer = C.id_kat_costumer
						WHERE (A.nik LIKE '%".$cari."%' OR A.nama_lengkap LIKE '%".$cari."%')
						ORDER BY B.tgl_ins DESC,A.nama_lengkap ASC
						LIMIT 0,1000
					";
					$list_costumer = $this->M_gl_pengaturan->view_query_general($query_list_costumer_pengajuan);
					
					
					
					
					$msgbox_title = " Data Mustahik Tambahan";
					$kode_kantor = $this->session->userdata('ses_kode_kantor');
					
					$data = array('page_content'=>'gl_admin_pengajuan_tambah_mustahik','msgbox_title' => $msgbox_title,'list_costumer' => $list_costumer,'kode_kantor' => $kode_kantor,'hasil_cek_pengajuan' => $hasil_cek_pengajuan);
					$this->load->view('admin/container',$data);
				}
				else
				{
					header('Location: '.base_url().'gl-admin-pengajuan');
				}
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
	
	function simpan_tambah_mustahik()
	{
		$id_costumer = $_POST['id_costumer'];
		$id_pengajuan = $_POST['id_pengajuan'];
		
		$query_cek = "SELECT * FROM tb_pengajuan_tambah_mustahik WHERE id_pengajuan = '".$id_pengajuan."' AND id_costumer = '".$id_costumer."'; ";
		$hasil_cek = $this->M_gl_pengaturan->view_query_general($query_cek);
		
		if(!empty($hasil_cek))
		{
			$hasil_cek = $hasil_cek->row();
			$query_hapus = "DELETE FROM tb_pengajuan_tambah_mustahik WHERE id_pengajuan = '".$id_pengajuan."' AND id_costumer = '".$id_costumer."'; ";
			
			$this->M_gl_pengaturan->exec_query_general($query_hapus);
			echo'BERHASIL';
		}
		else
		{
			$query_simpan = "
								INSERT INTO tb_pengajuan_tambah_mustahik
								(
									id_pengajuan,
									id_costumer,
									tgl_ins,
									user_ins,
									kode_kantor
								)
								value
								(
									'".$id_pengajuan."',
									'".$id_costumer."',
									NOW(),
									'',
									''
								);";
			
			$this->M_gl_pengaturan->exec_query_general($query_simpan);
			echo'BERHASIL';
			//return false;
		}
	}
	
	function ajax_getKode()
	{
		$get_no_kode = $this->M_gl_pengajuan->get_no_kode_per_jenis($this->session->userdata('ses_kode_kantor'),$_POST['jenis']);
		if(!empty($get_no_kode))
		{
			$kode_murnis = $get_no_kode->NO_KODE_FORMAT;
			$get_no_kode = $_POST['jenis'].'.'.$get_no_kode->NO_KODE_FORMAT;
			echo $get_no_kode.'/'.date("m").'-'.$kode_murnis;
		}
		else
		{
			$get_no_kode = 0;
			echo $get_no_kode.'/'.date("m").'-'.$get_no_kode;
		}
	}
	
	function list_pengaju_proposal()
	{	
		if((!empty($_POST['cari'])) && ($_POST['cari']!= "")  )
		{
			
			$cari = "WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' 
					AND (
								A.no_costumer LIKE '%".str_replace("'","",$_POST['cari'])."%' 
								OR A.nama_lengkap LIKE '%".str_replace("'","",$_POST['cari'])."%' 
								OR A.nik LIKE '%".str_replace("'","",$_POST['cari'])."%' 
						)";
			/*
			$cari = "
					WHERE A.kode_kantor = '".str_replace("'","",$_POST['kode_kantor'])."' AND (A.no_costumer LIKE '%".str_replace("'","",$_POST['cari'])."%' OR A.nama_lengkap LIKE '%".str_replace("'","",$_POST['cari'])."%' OR A.hp LIKE '%".str_replace("'","",$_POST['cari'])."%' OR A.hp_pnd LIKE '%".str_replace("'","",$_POST['cari'])."%' )
			";
			*/
		}
		else
		{
			$cari = "WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'";
			//$cari = "WHERE A.kode_kantor = '".str_replace("'","",$_POST['kode_kantor'])."'";
		}
	
		
		$list_costumer = $this->M_gl_costumer->list_costumer_biasa_history_ajuan_mustahik($cari,$_POST['limit'],$_POST['offset']);
		//-- list_supplier_limit($cari,$_POST['limit'],$_POST['offset']);
		
		if(!empty($list_costumer))
		{
			echo '<div class="box-body table-responsive no-padding">';
			echo'<table width="100%" id="table_list_produk" class="table table-hover">';
				echo '<thead>
<tr>';
						if($this->session->userdata('ses_kode_kantor') == "PST")
						{
							echo '<th width="5%">NO</th>';
							echo '<th width="15%">FOTO</th>';
							echo '<th width="65%">CUSTOMER/CABANG</th>';
							echo '<th width="15%">PILIH</th>';
						}
						else
						{
							echo '<th width="5%">NO</th>';
							echo '<th width="15%">FOTO</th>';
							echo '<th width="65%">BIODATA</th>';
							echo '<th width="15%">PILIH</th>';
						}
							
				echo '</tr>
</thead>';
				$list_result = $list_costumer->result();
				//$no =$this->uri->segment(2,0)+1;
				$no = 1;
				echo '<tbody>';
				foreach($list_result as $row)
				{
					//echo'<tr id="tr_'.$no.'">';
					echo'<tr id="tr_list_costumer-'.$no.'">';
					echo'<td>'.$no.'</td>';
					if($row->avatar == "")
					{
						$src = base_url().'assets/global/costumer/loading.gif';
						echo '<td><img id="IMG_'.$no.'"  width="100%" style="border:1px solid #C8C8C8; padding:5px; float:left;" src="'.$src.'" />
						</td>';
					}
					else
					{
						//$src = base_url().'assets/global/costumer/'.$row->avatar;
						$src = base_url().''.$row->avatar_url.''.$row->avatar;
						echo '<td><img id="IMG_'.$no.'"  width="100%" style="border:1px solid #C8C8C8; padding:5px; float:left;" src="'.$src.'" />
						</td>';
					}
					
					if($this->session->userdata('ses_kode_kantor') == "PST")
					{
						echo'<td>
								<b>NO : </b>'.$row->no_costumer.' 
								<br/> <b>NAMA : </b>'.$row->nama_lengkap.' 
								<br/> <b>JENIS : </b>'.$row->nama_kat_costumer.' 
								<br/> <b>No Tlp : </b>'.$row->hp.' / '.$row->hp_pnd.' 
								<br/>
								<!-- <br/> <div style="color:red;"><b>CABANG : </b>'.$row->kode_kantor.'</div> -->
								<br/> <b>PENGAJU : </b>'.$row->AJUAN.' ('.$row->TGL_AJUAN.' )
								<br/> <b>MUSTAHIK : </b>'.$row->ASHNAF.' ('.$row->TGL_ASHNAF.' )
							</td>';
					}
					else
					{
						echo'<td>
								<b>NO : </b>'.$row->no_costumer.' 
								<br/> <b>NIK : </b>'.$row->nik.' 
								<br/> <b>NAMA : </b>'.$row->nama_lengkap.' 
								<br/> <b>JENIS : </b>'.$row->nama_kat_costumer.' 
								<br/> <b>No Tlp : </b>'.$row->hp.' / '.$row->hp_pnd.' 
								<br/> <b>Alamat : </b>'.$row->alamat_rumah_sekarang.'
								<br/>
								<!-- <br/> <div style="color:red;"><b>CABANG : </b>'.$row->kode_kantor.'</div> -->
								<br/> <b>PENGAJU : </b>'.$row->AJUAN.' ('.$row->TGL_AJUAN.' )
								<br/> <b>MUSTAHIK : </b>'.$row->ASHNAF.' ('.$row->TGL_ASHNAF.' )
							</td>';
					}
						
					echo'<td>
							<button type="button" onclick="insert_pengaju('.$no.')" class="btn btn-primary btn-sm" data-dismiss="modal">Pilih</button>
						</td>';
						
					echo'<input type="hidden" id="get_tr_2_'.$no.'" name="get_tr_2_'.$no.'" value="tr_list_pasien-'.$no.'" />';
						
	echo'<input type="hidden" id="url_fix_'.$no.'" name="url_fix_'.$no.'" value="'.$src.'" />';
	echo'<input type="hidden" id="id_costumer_2_'.$no.'" name="id_costumer_2_'.$no.'" value="'.$row->id_costumer.'" />';
	echo'<input type="hidden" id="nik_2_'.$no.'" name="nik_2_'.$no.'" value="'.$row->nik.'" />';
	echo'<input type="hidden" id="id_kat_costumer_2_'.$no.'" name="id_kat_costumer_2_'.$no.'" value="'.$row->id_kat_costumer.'" />';
	echo'<input type="hidden" id="nama_kat_costumer_2_'.$no.'" name="nama_kat_costumer_2_'.$no.'" value="'.$row->nama_kat_costumer.'" />';
	echo'<input type="hidden" id="no_costumer_2_'.$no.'" name="no_costumer_2_'.$no.'" value="'.$row->no_costumer.'" />';
	echo'<input type="hidden" id="nama_lengkap_2_'.$no.'" name="nama_lengkap_2_'.$no.'" value="'.$row->nama_lengkap.'" />';
	echo'<input type="hidden" id="jenis_kelamin_2_'.$no.'" name="jenis_kelamin_2_'.$no.'" value="'.$row->jenis_kelamin.'" />';
	echo'<input type="hidden" id="pendidikan_2_'.$no.'" name="pendidikan_2_'.$no.'" value="'.$row->pendidikan.'" />';
	echo'<input type="hidden" id="pekerjaan_2_'.$no.'" name="pekerjaan_2_'.$no.'" value="'.$row->pekerjaan.'" />';
	echo'<input type="hidden" id="hp_2_'.$no.'" name="hp_2_'.$no.'" value="'.$row->hp.'" />';
	echo'<input type="hidden" id="email_costumer_2_'.$no.'" name="email_costumer_2_'.$no.'" value="'.$row->email_costumer.'" />';
	echo'<input type="hidden" id="tgl_registrasi_2_'.$no.'" name="tgl_registrasi_2_'.$no.'" value="'.$row->tgl_registrasi.'" />';
	echo'<input type="hidden" id="alamat_rumah_sekarang_2_'.$no.'" name="alamat_rumah_sekarang_2_'.$no.'" value="'.$row->alamat_rumah_sekarang.'" />';
						
					echo'</tr>';
					$no++;
				}
				
				echo '</tbody>';
			echo'</table>';
			echo'</div>';
		}
		else
		{
			echo "TIDAK ADA DATA YANG DITAMPILKAN";
		}
	}
	
	function list_mustahik_proposal()
	{	
		if((!empty($_POST['cari'])) && ($_POST['cari']!= "")  )
		{
			
			$cari = "WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' 
					AND (
								A.no_costumer LIKE '%".str_replace("'","",$_POST['cari'])."%' 
								OR A.nama_lengkap LIKE '%".str_replace("'","",$_POST['cari'])."%' 
								OR A.nik LIKE '%".str_replace("'","",$_POST['cari'])."%' 
						)";
			
			
			/*
			$cari = "
					WHERE A.kode_kantor = '".str_replace("'","",$_POST['kode_kantor'])."' AND (A.no_costumer LIKE '%".str_replace("'","",$_POST['cari'])."%' OR A.nama_lengkap LIKE '%".str_replace("'","",$_POST['cari'])."%' OR A.hp LIKE '%".str_replace("'","",$_POST['cari'])."%' OR A.hp_pnd LIKE '%".str_replace("'","",$_POST['cari'])."%' )
			";
			*/
		}
		else
		{
			$cari = "WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'";
			//$cari = "WHERE A.kode_kantor = '".str_replace("'","",$_POST['kode_kantor'])."'";
		}
	
		
		$list_costumer = $this->M_gl_costumer->list_costumer_biasa_history_ajuan_mustahik($cari,$_POST['limit'],$_POST['offset']);
		//-- list_supplier_limit($cari,$_POST['limit'],$_POST['offset']);
		
		if(!empty($list_costumer))
		{
			echo '<div class="box-body table-responsive no-padding">';
			echo'<table width="100%" id="table_list_produk" class="table table-hover">';
				echo '<thead>
<tr>';
						if($this->session->userdata('ses_kode_kantor') == "PST")
						{
							echo '<th width="5%">NO</th>';
							echo '<th width="15%">FOTO</th>';
							echo '<th width="65%">CUSTOMER/CABANG</th>';
							echo '<th width="15%">PILIH</th>';
						}
						else
						{
							echo '<th width="5%">NO</th>';
							echo '<th width="15%">FOTO</th>';
							echo '<th width="65%">BIODATA</th>';
							echo '<th width="15%">PILIH</th>';
						}
							
				echo '</tr>
</thead>';
				$list_result = $list_costumer->result();
				//$no =$this->uri->segment(2,0)+1;
				$no = 1;
				echo '<tbody>';
				foreach($list_result as $row)
				{
					//echo'<tr id="tr_'.$no.'">';
					echo'<tr id="tr_list_costumer-'.$no.'">';
					echo'<td>'.$no.'</td>';
					if($row->avatar == "")
					{
						$src = base_url().'assets/global/costumer/loading.gif';
						echo '<td><img id="IMG_'.$no.'"  width="100%" style="border:1px solid #C8C8C8; padding:5px; float:left;" src="'.$src.'" />
						</td>';
					}
					else
					{
						//$src = base_url().'assets/global/costumer/'.$row->avatar;
						$src = base_url().''.$row->avatar_url.''.$row->avatar;
						echo '<td><img id="IMG_'.$no.'"  width="100%" style="border:1px solid #C8C8C8; padding:5px; float:left;" src="'.$src.'" />
						</td>';
					}
					
					if($this->session->userdata('ses_kode_kantor') == "PST")
					{
						echo'<td>
								<b>NO CUSTOMER : </b>'.$row->no_costumer.' 
								<br/> <b>NAMA : </b>'.$row->nama_lengkap.' 
								<br/> <b>JENIS : </b>'.$row->nama_kat_costumer.' 
								<br/> <b>No Tlp : </b>'.$row->hp.' / '.$row->hp_pnd.' 
								<br/>
								<!-- <br/> <div style="color:red;"><b>CABANG : </b>'.$row->kode_kantor.'</div> -->
								<br/> <b>PENGAJU : </b>'.$row->AJUAN.' ('.$row->TGL_AJUAN.' )
								<br/> <b>MUSTAHIK : </b>'.$row->ASHNAF.' ('.$row->TGL_ASHNAF.' )
							</td>';
					}
					else
					{
						echo'<td>
								<b>NO : </b>'.$row->no_costumer.' 
								<br/> <b>NIK : </b>'.$row->nik.' 
								<br/> <b>NAMA : </b>'.$row->nama_lengkap.' 
								<br/> <b>JENIS : </b>'.$row->nama_kat_costumer.' 
								<br/> <b>No Tlp : </b>'.$row->hp.' / '.$row->hp_pnd.'
								<br/> <b>Alamat : </b>'.$row->alamat_rumah_sekarang.'
								<br/>								
								<!-- <br/> <div style="color:red;"><b>CABANG : </b>'.$row->kode_kantor.'</div> -->
								<br/> <b>PENGAJU : </b>'.$row->AJUAN.' ('.$row->TGL_AJUAN.' )
								<br/> <b>MUSTAHIK : </b>'.$row->ASHNAF.' ('.$row->TGL_ASHNAF.' )
							</td>';
					}
						
					echo'<td>
							<button type="button" onclick="insert_mustahik('.$no.')" class="btn btn-primary btn-sm" data-dismiss="modal">Pilih</button>
						</td>';
						
					echo'<input type="hidden" id="get_tr_3_'.$no.'" name="get_tr_3_'.$no.'" value="tr_list_pasien-'.$no.'" />';
						
	echo'<input type="hidden" id="url_fix_'.$no.'" name="url_fix_'.$no.'" value="'.$src.'" />';
	echo'<input type="hidden" id="id_costumer_3_'.$no.'" name="id_costumer_3_'.$no.'" value="'.$row->id_costumer.'" />';
	echo'<input type="hidden" id="nik_3_'.$no.'" name="nik_3_'.$no.'" value="'.$row->nik.'" />';
	echo'<input type="hidden" id="id_kat_costumer_3_'.$no.'" name="id_kat_costumer_3_'.$no.'" value="'.$row->id_kat_costumer.'" />';
	echo'<input type="hidden" id="nama_kat_costumer_3_'.$no.'" name="nama_kat_costumer_3_'.$no.'" value="'.$row->nama_kat_costumer.'" />';
	echo'<input type="hidden" id="no_costumer_3_'.$no.'" name="no_costumer_3_'.$no.'" value="'.$row->no_costumer.'" />';
	echo'<input type="hidden" id="nama_lengkap_3_'.$no.'" name="nama_lengkap_3_'.$no.'" value="'.$row->nama_lengkap.'" />';
	echo'<input type="hidden" id="jenis_kelamin_3_'.$no.'" name="jenis_kelamin_3_'.$no.'" value="'.$row->jenis_kelamin.'" />';
	echo'<input type="hidden" id="pendidikan_3_'.$no.'" name="pendidikan_3_'.$no.'" value="'.$row->pendidikan.'" />';
	echo'<input type="hidden" id="pekerjaan_3_'.$no.'" name="pekerjaan_3_'.$no.'" value="'.$row->pekerjaan.'" />';
	echo'<input type="hidden" id="hp_3_'.$no.'" name="hp_3_'.$no.'" value="'.$row->hp.'" />';
	echo'<input type="hidden" id="email_costumer_3_'.$no.'" name="email_costumer_3_'.$no.'" value="'.$row->email_costumer.'" />';
	echo'<input type="hidden" id="tgl_registrasi_3_'.$no.'" name="tgl_registrasi_3_'.$no.'" value="'.$row->tgl_registrasi.'" />';
	echo'<input type="hidden" id="alamat_rumah_sekarang_3_'.$no.'" name="alamat_rumah_sekarang_3_'.$no.'" value="'.$row->alamat_rumah_sekarang.'" />';
						
					echo'</tr>';
					$no++;
				}
				
				echo '</tbody>';
			echo'</table>';
			echo'</div>';
		}
		else
		{
			echo "TIDAK ADA DATA YANG DITAMPILKAN";
		}
	}
	
	public function simpan_test()
	{
		echo $_POST['id_costumer_pengaju'];
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
					
					$this->M_gl_pengajuan->edit
					(
						$_POST['stat_edit'],
						$_POST['id_costumer_pengaju'],
						$_POST['id_costumer_ashnaf'],
						$_POST['no_reg'],
						$_POST['no_kode'],
						$_POST['tgl_terima'],
						$_POST['no_surat'],
						$_POST['tgl_surat'],
						$_POST['jenis_pengajuan'],
						$_POST['perihal'],
						$_POST['isLembaga'],
						$this->session->userdata('ses_id_karyawan'),
						$this->session->userdata('ses_kode_kantor'),
						$_POST['no_tlp_pengaju'] //no_hp
					);
					
					
					/* CATAT AKTIFITAS EDIT*/
					if($this->session->userdata('catat_log') == 'Y')
					{
						$this->M_gl_log->simpan_log
						(
							$this->session->userdata('ses_id_karyawan'),
							'UPDATE',
							'Melakukan perubahan data Pengajuan '.$_POST['no_reg'].' | '.$_POST['no_kode'],
							$this->M_gl_pengaturan->getUserIpAddr(),
							gethostname(),
							$this->session->userdata('ses_kode_kantor')
						);
					}
					/* CATAT AKTIFITAS EDIT*/
				}
				else
				{
					$this->M_gl_pengajuan->simpan
					(
						$_POST['id_costumer_pengaju'],
						$_POST['id_costumer_ashnaf'],
						$_POST['no_reg'],
						$_POST['no_kode'],
						$_POST['tgl_terima'],
						$_POST['no_surat'],
						$_POST['tgl_surat'],
						$_POST['jenis_pengajuan'],
						$_POST['perihal'],
						$_POST['isLembaga'],
						$this->session->userdata('ses_id_karyawan'),
						$this->session->userdata('ses_kode_kantor'),
						$_POST['no_tlp_pengaju'] //no_hp
					
					);
					
					/* CATAT AKTIFITAS EDIT*/
					if($this->session->userdata('catat_log') == 'Y')
					{
						$this->M_gl_log->simpan_log
						(
							$this->session->userdata('ses_id_karyawan'),
							'INSERT',
							'Melakukan penambahan/input data Pengajuan '.$_POST['no_reg'].' | '.$_POST['no_kode'],
							$this->M_gl_pengaturan->getUserIpAddr(),
							gethostname(),
							$this->session->userdata('ses_kode_kantor')
						);
					}
					/* CATAT AKTIFITAS EDIT*/
					
				}
				header('Location: '.base_url().'gl-admin-pengajuan');
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
	
	public function hapus()
	{
		$id_pengajuan = ($this->uri->segment(2,0));
		$data_pengajuan = $this->M_gl_pengajuan->get_pengajuan('id_pengajuan',$id_pengajuan);
		if(!empty($data_pengajuan))
		{
			$data_pengajuan = $data_pengajuan->row();
			$this->M_gl_pengajuan->hapus('id_pengajuan',$id_pengajuan);
			
			/* CATAT AKTIFITAS HAPUS*/
			if($this->session->userdata('catat_log') == 'Y')
			{
				$this->M_gl_log->simpan_log
				(
					$this->session->userdata('ses_id_karyawan'),
					'DELETE',
					'Melakukan penghapusan data pengajuan '.$data_pengajuan->no_reg.' | '.$data_pengajuan->no_kode,
					$this->M_gl_pengaturan->getUserIpAddr(),
					gethostname(),
					$this->session->userdata('ses_kode_kantor')
				);
			}
			/* CATAT AKTIFITAS HAPUS*/
		}
		
		
		header('Location: '.base_url().'gl-admin-pengajuan');
	}
	
	function cek_satuan()
	{
		$hasil_cek = $this->M_gl_satuan->get_satuan('kode_satuan',$_POST['kode_satuan']);
		if(!empty($hasil_cek))
		{
			$hasil_cek = $hasil_cek->row();
			echo $hasil_cek->kode_satuan;
		}
		else
		{
			return false;
		}
	}

	public function print_bukti_registrasi()
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
				$id_pengajuan = $this->uri->segment(3,0);
				$cari = "WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' AND A.id_pengajuan = '".$id_pengajuan."'";
				$order_by = " ORDER BY A.tgl_ins DESC";
				$get_data_pengajuan = $this->M_gl_pengajuan->list_pengajuan_limit($cari,$order_by,1,0);
				if(!empty($get_data_pengajuan))
				{
					$get_data_pengajuan = $get_data_pengajuan->row();
					
					$msgbox_title = " Rincian Pengeluaran";
					
					$data = array('page_content'=>'gl_admin_print_bukti_register_ajuan','get_data_pengajuan'=>$get_data_pengajuan);
					
					//$this->load->view('admin/container',$data);
					$this->load->view('admin/page/gl_admin_print_bukti_register_ajuan.html',$data);
				}
				else
				{
					header('Location: '.base_url().'gl-admin-pengajuan');
				}
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/C_gl_admin_pengajuan.php */