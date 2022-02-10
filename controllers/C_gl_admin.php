<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_gl_admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model(array('M_gl_dashboard','M_gl_stock_produk','M_gl_costumer','M_gl_lap_pembelian','M_gl_surat'));
	}
	
	public function index()
	{
		if(($this->session->userdata('ses_user_admin') == null) or ($this->session->userdata('ses_pass_admin') == null))
		{
			header('Location: '.base_url().'gl-admin-login');
		}
		else
		{
			
			$cek_ses_login = $this->M_gl_karyawan->get_karyawan_jabatan_row(" WHERE A.user = '".$this->session->userdata('ses_user_admin')."' AND A.pass = '".base64_encode(md5($this->session->userdata('ses_pass_admin_pure')))."' AND A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ");
			
			if(!empty($cek_ses_login))
			{
				$data_kantor = $this->M_gl_pengaturan->get_data_kantor(" WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'");
				$data_kantor = $data_kantor->row();
				
				
				
				//LOG AKTIFITAS TERAKHIR
				
				$cari_log_aktifitas = "WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ";
				$order_by_log_aktifitas = "ORDER BY A.waktu DESC";
				$list_log_aktifitas = $this->M_gl_dashboard->list_log_aktifitas_limit($cari_log_aktifitas,$order_by_log_aktifitas,15,0);
				
				//LOG AKTIFITAS TERAKHIR
				
				//STATISTIK KUNJUNGAN
				$list_statistik_kunjungan = $this->M_gl_dashboard->statistik_pengajuan_proposal($this->session->userdata('ses_kode_kantor'));
				//STATISTIK KUNJUNGAN
				
				// SURAT
					$cari_surat = "WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
							AND A.in_out = 'IN'
							AND DATE(A.tgl_masuk) BETWEEN DATE(CONCAT(YEAR(NOW()),'-',MONTH(NOW()),'-01')) AND DATE(CONCAT(YEAR(NOW()),'-',MONTH(NOW()),'-',DATE_FORMAT(LAST_DAY(NOW()),'%d') ))
							";
					
					$order_by = "ORDER BY tgl_ins DESC";
					$list_surat = $this->M_gl_surat->list_surat_limit($cari_surat,$order_by,300,0);
				// SURAT
				
				// DATA JUMLAH KARYAWAN
					$jum_karyawan = $this->M_gl_dashboard->jum_karyawan($this->session->userdata('ses_kode_kantor'));
					if(!empty($jum_karyawan))
					{
						$jum_karyawan = $jum_karyawan->JUM_KARYAWAN;
					}
					else
					{
						$jum_karyawan = 0;
					}
				// DATA JUMLAH KARYAWAN
				
				// DATA JUMLAH DEPARTEMEN
					$jum_departement = $this->M_gl_dashboard->jum_departemen($this->session->userdata('ses_kode_kantor'));
					if(!empty($jum_departement))
					{
						$jum_departement = $jum_departement->JUM_DEPT;
					}
					else
					{
						$jum_departement = 0;
					}
				// DATA JUMLAH DEPARTEMEN
				
				// DATA JUMLAH JABATAN
					$jum_jabatan = $this->M_gl_dashboard->jum_jabatan($this->session->userdata('ses_kode_kantor'));
					if(!empty($jum_jabatan))
					{
						$jum_jabatan = $jum_jabatan->JUM_JABATAN;
					}
					else
					{
						$jum_jabatan = 0;
					}
				// DATA JUMLAH JABATAN
				
				$data = array('page_content'=>'gl_admin_dashboard','data_kantor' => $data_kantor
				//,'data_jumlah_pasien' => $data_jumlah_pasien
				//,'data_jumlah_dokter' => $data_jumlah_dokter
				//,'data_jumlah_kunjungan' => $data_jumlah_kunjungan
				//,'list_diskon_promo' => $list_diskon_promo
				,'list_statistik_kunjungan' => $list_statistik_kunjungan
				,'jum_karyawan' => $jum_karyawan
				,'jum_departement' => $jum_departement
				,'jum_jabatan' => $jum_jabatan
				,'list_surat' => $list_surat
				,'list_log_aktifitas' => $list_log_aktifitas);
				$this->load->view('admin/container',$data);
				
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
	
	function ubah_data_kantor()
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
				//$this->M_gl_pengaturan->do_upload_global($lokasi,$nama_file,$cek_bfr);
				
				if (empty($_FILES['foto']['name']))
				{
					$this->M_gl_pengaturan->ubah_data_kantor
					(
						$_POST['id_kantor'],
						$this->session->userdata('ses_kode_kantor'),
						$_POST['SITU'],
						$_POST['SIUP'],
						$_POST['nama_kantor'],
						$_POST['pemilik'],
						$_POST['kota'],
						$_POST['alamat'],
						$_POST['tlp'],
						$_POST['sejarah'],
						$_POST['ket_kantor'],
						"",
						"",
						$this->session->userdata('ses_id_karyawan')
					);
				}
				else
				{
					/*DAPATKAN DATA KANTOR*/
						$data_kantor = $this->M_gl_pengaturan->get_data_kantor(" WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'");
						$data_kantor = $data_kantor->row();
					/*DAPATKAN DATA KANTOR*/
					
					/*AMBIL EXT*/
						$path = $_FILES['foto']['name'];
						$ext = pathinfo($path, PATHINFO_EXTENSION);
					/*AMBIL EXT*/

					/*PROSES UPLOAD*/
						$lokasi_gambar_disimpan = 'assets/global/images/';					
						$this->M_gl_pengaturan->do_upload_global($lokasi_gambar_disimpan,'KTR'.$_POST['id_kantor'].'.'.$ext,$data_kantor->img_kantor);
					/*PROSES UPLOAD*/					
					
					
					$this->M_gl_pengaturan->ubah_data_kantor
					(
						$_POST['id_kantor'],
						$this->session->userdata('ses_kode_kantor'),
						$_POST['SITU'],
						$_POST['SIUP'],
						$_POST['nama_kantor'],
						$_POST['pemilik'],
						$_POST['kota'],
						$_POST['alamat'],
						$_POST['tlp'],
						$_POST['sejarah'],
						$_POST['ket_kantor'],
						'KTR'.$_POST['id_kantor'].'.'.$ext,//$_POST['img_kantor'],
						$lokasi_gambar_disimpan,
						$this->session->userdata('ses_id_karyawan')
					);
				}
				
				/* CATAT AKTIFITAS EDIT*/
				if($this->session->userdata('catat_log') == 'Y')
				{
					$this->M_gl_log->simpan_log
					(
						$this->session->userdata('ses_id_karyawan'),
						'UPDATE',
						'Melakukan perubahan data kantor '.$_POST['kode_kantor'].' | '.$_POST['nama_kantor'],
						$this->M_gl_pengaturan->getUserIpAddr(),
						gethostname(),
						$this->session->userdata('ses_kode_kantor')
					);
				}
				/* CATAT AKTIFITAS EDIT*/
				
				header('Location: '.base_url().'gl-admin');
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/c_admin.php */