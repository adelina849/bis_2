<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_gl_admin_uang_masuk extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		//$this->load->model(array('M_berita','M_kat_berita','M_images'));
		$this->load->model(array('M_gl_uang_masuk','M_gl_bank','M_gl_kode_akun','M_gl_costumer'));
		
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
					$cari = 
						"
							WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' 
							AND DATE(A.tgl_uang_masuk) BETWEEN '".$dari."' AND '".$sampai."'
							AND (
									A.no_bukti LIKE '%".str_replace("'","",$_GET['cari'])."%' 
									OR A.nama_uang_masuk LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR A.terima_dari LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR A.diterima_oleh LIKE '%".str_replace("'","",$_GET['cari'])."%'
								)
						";
				}
				else
				{
					$cari = 
						"
							WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
							AND DATE(A.tgl_uang_masuk) BETWEEN '".$dari."' AND '".$sampai."'
						";
				}
				
				$order_by = " ORDER BY A.tgl_ins DESC ";
				
				$jum_row = $this->M_gl_uang_masuk->count_uang_masuk_limit($cari)->JUMLAH;
				
				$this->load->library('pagination');
				//$config['first_url'] = base_url().'admin/jabatan?'.http_build_query($_GET);
				//$config['base_url'] = base_url().'admin/jabatan/';
				
				$config['first_url'] = site_url('gl-admin-pemasukan?'.http_build_query($_GET));
				$config['base_url'] = site_url('gl-admin-pemasukan/');
				$config['total_rows'] = $jum_row;
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
				
				//DATA BANK
				$cari_bank = " WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ";
				$list_bank = $this->M_gl_bank->list_bank_limit($cari_bank,1000,0);
				//DATA BANK
				
				//DAPATKAN NO UANG masuk
				$get_no_uang_masuk = $this->M_gl_uang_masuk->get_no_uang_masuk($this->session->userdata('ses_kode_kantor'));
				if(!empty($get_no_uang_masuk))
				{
					$get_no_uang_masuk = $get_no_uang_masuk->row();
					$get_no_uang_masuk = $get_no_uang_masuk->no_bukti;
				}
				else
				{
					$get_no_uang_masuk = "";
				}
				//DAPATKAN NO UANG masuk
					
				
				$list_uang_masuk = $this->M_gl_uang_masuk->list_uang_masuk_limit($cari,$order_by,$config['per_page'],$this->uri->segment(2,0));
				
				
				//UNTUK AKUMULASI INFO
					if($config['per_page'] > $jum_row)
					{
						$jum_row_tampil = $jum_row;
					}
					else
					{
						$jum_row_tampil = $config['per_page'];
					}
					
					$offset = (integer) $this->uri->segment(2,0);
					$max_data = $offset + $jum_row_tampil;
					$offset = $offset + 1;
					if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
					{
						$sum_pesan = "Menampilkan ".$jum_row_tampil." Dari ".$jum_row." Data Pencarian ".str_replace("'","",$_GET['cari'])." dimulai dari data ke ".$offset." Sampai ".$max_data;
					}
					else
					{
						$sum_pesan = "Menampilkan ".$jum_row_tampil." Dari ".$jum_row." Data dimulai dari data ke ".$offset." Sampai ".$max_data;
					}
				//UNTUK AKUMULASI INFO
				
				//SUMAARY LIST
					$sum_uang_masuk = $this->M_gl_uang_masuk->sum_uang_masuk_limit($cari);
					if(!empty($sum_uang_masuk))
					{
						$sum_uang_masuk = $sum_uang_masuk->row();
						$sum_uang_masuk = $sum_uang_masuk->NOMINAL;
					}
					else
					{
						$sum_uang_masuk = 0;
					}
				//SUMAARY LIST
				
				$msgbox_title = " Pemasukan/Uang Masuk";
				
				$data = array('page_content'=>'gl_admin_uang_masuk','halaman'=>$halaman,'list_uang_masuk'=>$list_uang_masuk,'msgbox_title' => $msgbox_title,'sum_pesan' => $sum_pesan,'list_bank'=>$list_bank,'get_no_uang_masuk'=>$get_no_uang_masuk,'sum_uang_masuk'=>$sum_uang_masuk);
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
					$this->M_gl_uang_masuk->edit
					(
						$_POST['stat_edit'],
						$_POST['id_kat_uang_masuk'],
						$_POST['id_costumer'],
						'', //$_POST['id_supplier'],
						$_POST['id_bank'],
						'', //$_POST['id_retur_penjualan'],
						'', //$_POST['id_retur_pembelian'],
						'', //$_POST['id_karyawan'],
						'', //$_POST['id_d_assets'],
						$_POST['no_bukti'],
						$_POST['nama_uang_masuk'],
						$_POST['terima_dari'],
						$_POST['diterima_oleh'],
						$_POST['untuk'],
						str_replace(",","",$_POST['nominal']) , //$_POST['nominal'],
						$_POST['ket_uang_masuk'],
						$_POST['tgl_uang_masuk'],
						'', //$_POST['isTabungan'],
						'', //$_POST['isPiutang'],
						'', //$_POST['noPinjamanCos'],
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
							'Melakukan perubahan data uang masuk '.$_POST['no_bukti'].' | '.$_POST['nama_uang_masuk'],
							$this->M_gl_pengaturan->getUserIpAddr(),
							gethostname(),
							$this->session->userdata('ses_kode_kantor')
						);
					}
					/* CATAT AKTIFITAS EDIT*/
				}
				else
				{
					$this->M_gl_uang_masuk->simpan
					(
						
						$_POST['id_kat_uang_masuk'],
						$_POST['id_costumer'],
						'', //$_POST['id_supplier'],
						$_POST['id_bank'],
						'', //$_POST['id_retur_penjualan'],
						'', //$_POST['id_retur_pembelian'],
						'', //$_POST['id_karyawan'],
						'', //$_POST['id_d_assets'],
						$_POST['no_bukti'],
						$_POST['nama_uang_masuk'],
						$_POST['terima_dari'],
						$_POST['diterima_oleh'],
						$_POST['untuk'],
						str_replace(",","",$_POST['nominal']) , //$_POST['nominal'],
						$_POST['ket_uang_masuk'],
						$_POST['tgl_uang_masuk'],
						'', //$_POST['isTabungan'],
						'', //$_POST['isPiutang'],
						'', //$_POST['noPinjamanCos'],
						$this->session->userdata('ses_id_karyawan'),
						$this->session->userdata('ses_kode_kantor')
						
					
					
					);
				}
				header('Location: '.base_url().'gl-admin-pemasukan');
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
	
	public function hapus()
	{
		$id_uang_masuk = ($this->uri->segment(2,0));
		$data_uang_masuk = $this->M_gl_uang_masuk->get_uang_masuk('MD5(id_uang_masuk)',$id_uang_masuk);
		if(!empty($data_uang_masuk))
		{
			$data_uang_masuk = $data_uang_masuk->row();
			$this->M_gl_uang_masuk->hapus('MD5(id_uang_masuk)',$id_uang_masuk);
			
			/* CATAT AKTIFITAS HAPUS*/
			if($this->session->userdata('catat_log') == 'Y')
			{
				$this->M_gl_log->simpan_log
				(
					$this->session->userdata('ses_id_karyawan'),
					'DELETE',
					'Melakukan penghapusan data uang masuk '.$data_uang_masuk->no_bukti.' | '.$data_uang_masuk->nama_uang_masuk,
					$this->M_gl_pengaturan->getUserIpAddr(),
					gethostname(),
					$this->session->userdata('ses_kode_kantor')
				);
			}
			/* CATAT AKTIFITAS HAPUS*/
		}
		
		header('Location: '.base_url().'gl-admin-pemasukan');
	}
	
	function cek_uang_masuk()
	{
		$hasil_cek = $this->M_gl_uang_masuk->get_uang_masuk('no_bukti',$_POST['no_bukti']);
		if(!empty($hasil_cek))
		{
			$hasil_cek = $hasil_cek->row();
			echo $hasil_cek->no_bukti;
		}
		else
		{
			return false;
		}
	}
	
	
	function list_muzaki_popup()
	{	
		if((!empty($_POST['cari'])) && ($_POST['cari']!= "")  )
		{
			$cari = "
					WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' 
					AND (
							A.nama_lengkap LIKE '%".str_replace("'","",$_POST['cari'])."%' 
							OR A.nik LIKE '%".str_replace("'","",$_POST['cari'])."%'
						)";
		}
		else
		{
			$cari = " 
						WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
					";
		}
		
		$list_costumer = $this->M_gl_costumer->list_costumer_biasa($cari,30,0);
		//list_kode_akun_limit($cari,$_POST['limit'],$_POST['offset']);
		//($cari,$_POST['limit'],$_POST['offset']);
		
		if(!empty($list_costumer))
		{
			echo '<div class="box-body table-responsive no-padding">';
			echo'<table width="100%" id="table_list_produk" class="table table-hover">';
				echo '<thead>
<tr>';
							echo '<th width="5%">NO</th>';
							//echo '<th width="10%">FOTO</th>';
							echo '<th width="65%">DATA</th>';
							echo '<th width="15%">PILIH</th>';
				echo '</tr>
</thead>';
				$list_result = $list_costumer->result();
				//$no =$this->uri->segment(2,0)+1;
				$no = 1;
				echo '<tbody>';
				foreach($list_result as $row)
				{
					//echo'<tr id="tr_'.$no.'">';
					echo'<tr id="tr_list_produk-'.$no.'">';
					
						echo'<td>'.$no.'</td>';
						echo'<td>
								<b>NIK : </b>'.$row->nik.' 
								<br/> <b>NAMA : </b>'.$row->nama_lengkap.' 
								<br/> <b>JENIS : </b>'.$row->nama_kat_costumer.'
								<br/> <b>KETERANGAN : </b>'.$row->KECAMATAN2.','.$row->DESA2.','.$row->alamat_rumah_sekarang.'
							</td>';
						echo'<td>
								<button type="button" onclick="insert_muzaki('.$no.')" class="btn btn-primary btn-sm"  data-dismiss="modal" >Pilih</button>
							</td>';
						
						//echo'<input type="hidden" id="get_trmuzaki_1_'.$no.'" name="get_trmuzaki_1_'.$no.'" value="tr_list_muzaki-'.$no.'" />';
						echo'<input type="hidden" id="id_costumer_1_'.$no.'" name="id_costumer_1_'.$no.'" value="'.$row->id_costumer.'" />';
						echo'<input type="hidden" id="nik_1_'.$no.'" name="nik_1_'.$no.'" value="'.$row->nik.'" />';
						echo'<input type="hidden" id="nama_lengkap_1_'.$no.'" name="nama_lengkap_1_'.$no.'" value="'.$row->nama_lengkap.'" />';
						
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
}

/* End of file welcome.php */
/* Location: ./application/controllers/c_admin_jabatan.php */