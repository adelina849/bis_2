<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_gl_admin_lap_stock_produk extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		//$this->load->model(array('M_berita','M_kat_berita','M_images'));
		$this->load->model(array('M_gl_stock_produk','M_gl_produk','M_gl_pst_inventori'));
		
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
				
				if((!empty($_GET['kode_kantor'])) && ($_GET['kode_kantor']!= "")  )
				{
					$kode_kantor = str_replace("'","",$_GET['kode_kantor']);
				}
				else
				{
					$kode_kantor = $this->session->userdata('ses_kode_kantor');
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
					$cari = str_replace("'","",$_GET['cari']);
				}
				else
				{
					$cari = "";
				}
				
				$jum_row = $this->M_gl_stock_produk->count_stock_produk($cari)->JUMLAH; //KODE KANTOR MASIH AMBIL DRI SESI
				
				$this->load->library('pagination');
				
				$config['first_url'] = site_url('gl-admin-laporan-stock-produk?'.http_build_query($_GET));
				$config['base_url'] = site_url('gl-admin-laporan-stock-produk/');
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
				
				IF(!empty($this->session->userdata('ses_hirarki')))
				{
				    $ses_hirarki = $this->session->userdata('ses_hirarki');
				}
				else
				{
				    $ses_hirarki = 0;
				}
				
				//$list_kantor = $this->M_gl_pengaturan->get_data_kantor("");
				$list_kantor = $this->M_gl_pengaturan->get_data_kantor2("WHERE CASE WHEN ".$ses_hirarki." = 1 THEN isViewClient IN (0,1) ELSE isViewClient = 0 END");
				
				//$list_stock_produk = $this->M_gl_stock_produk->list_stock_produk($kode_kantor,$cari,' PROD.nama_produk ASC',$this->uri->segment(2,0),$config['per_page'],$sampai,'23','59','');
				
				//list_stock_produk($kode_kantor,$dari,$sampai,$offset,$limit,$cari)
				
				$list_stock_produk = $this->M_gl_pst_inventori->list_stock_produk($kode_kantor,$dari,$sampai,$this->uri->segment(2,0),$config['per_page'],$cari);
				
				//$sum_stock_produk = $this->M_gl_stock_produk->sum_stock_produk($this->session->userdata('ses_kode_kantor'),$cari,$sampai,'23','59');
				
				//($cari,$config['per_page'],$this->uri->segment(2,0));
				
				$msgbox_title = " Stock/Persediaan Produk ";
				
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
				
				
				if($this->session->userdata('ses_kode_kantor') == 'PST')
				{
					
					$data = array('page_content'=>'gl_admin_stock_produk','halaman'=>$halaman,'list_stock_produk'=>$list_stock_produk,'msgbox_title' => $msgbox_title, 'sum_pesan' => $sum_pesan,'list_kantor' => $list_kantor,'kode_kantor' => $kode_kantor);
					$this->load->view('admin/container',$data);
				}
				else
				{
					//$list_kantor = false;
					$data = array('page_content'=>'gl_admin_stock_produk','halaman'=>$halaman,'list_stock_produk'=>$list_stock_produk,'msgbox_title' => $msgbox_title, 'sum_pesan' => $sum_pesan,'list_kantor' => $list_kantor,'kode_kantor' => $kode_kantor);
					$this->load->view('admin/container',$data);
				}
				
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
	
	public function view_analisa_order()
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
				if((!empty($_GET['sampai'])) && ($_GET['sampai']!= "")  )
				{
					$sampai = $_GET['sampai'];
				}
				else
				{
					$sampai = date("Y-m-d");
				}
				
				if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
				{
					$cari = str_replace("'","",$_GET['cari']);
				}
				else
				{
					$cari = "";
				}
				
				$jum_row = $this->M_gl_stock_produk->count_stock_produk($cari)->JUMLAH;
				
				$this->load->library('pagination');
				
				$config['first_url'] = site_url('gl-admin-analisa-order?'.http_build_query($_GET));
				$config['base_url'] = site_url('gl-admin-analisa-order/');
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
				
				$list_stock_produk = $this->M_gl_stock_produk->list_analisa_order($this->session->userdata('ses_kode_kantor'),$cari,' PROD.nama_produk ASC',$this->uri->segment(2,0),$config['per_page'],$sampai,'23','59','');
				
				//$sum_stock_produk = $this->M_gl_stock_produk->sum_stock_produk($this->session->userdata('ses_kode_kantor'),$cari,$sampai,'23','59');
				
				//($cari,$config['per_page'],$this->uri->segment(2,0));
				
				$msgbox_title = " Analisa Order Produk ";
				
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
				
				$data = array('page_content'=>'gl_admin_analisa_order','halaman'=>$halaman,'list_stock_produk'=>$list_stock_produk,'msgbox_title' => $msgbox_title, 'sum_pesan' => $sum_pesan);
				$this->load->view('admin/container',$data);
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
	
	public function view_lap_rata_penjualan()
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
					$cari = "
							WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' 
							AND COALESCE(sts_penjualan,'') IN ('SELESAI','PEMBAYARAN')
							AND isProduk <> 'JASA'
							AND COALESCE(DATE(tgl_h_penjualan),'0000-00-00') BETWEEN '".$dari."' AND '".$sampai."'
							AND id_produk <> ''
							AND (kode_produk LIKE '%".str_replace("'","",$_GET['cari'])."%' OR nama_produk LIKE '%".str_replace("'","",$_GET['cari'])."%')
					";
					
					
				}
				else
				{
					$cari = "
							WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' 
							AND COALESCE(sts_penjualan,'') IN ('SELESAI','PEMBAYARAN')
							AND isProduk <> 'JASA'
							AND COALESCE(DATE(tgl_h_penjualan),'0000-00-00') BETWEEN '".$dari."' AND '".$sampai."'
							AND id_produk <> ''
							AND (kode_produk LIKE '%%' OR nama_produk LIKE '%%')
					";
				}
				
				$order_by = "ORDER BY nama_produk ASC";
				$jum_row = $this->M_gl_stock_produk->count_rata_produk_terjual($cari)->JUMLAH;
				
				$this->load->library('pagination');
				
				$config['first_url'] = site_url('gl-admin-laporan-rata-produk-terjual?'.http_build_query($_GET));
				$config['base_url'] = site_url('gl-admin-laporan-rata-produk-terjual/');
				$config['total_rows'] = $jum_row;
				$config['uri_segment'] = 2;	
				$config['per_page'] = 100;
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
				
				$list_rata_produk_terjual = $this->M_gl_stock_produk->list_rata_produk_terjual($dari,$sampai,$cari,$order_by,$config['per_page'],$this->uri->segment(2,0));
				
				//$sum_stock_produk = $this->M_gl_stock_produk->sum_stock_produk($this->session->userdata('ses_kode_kantor'),$cari,$sampai,'23','59');
				
				//($cari,$config['per_page'],$this->uri->segment(2,0));
				
				$msgbox_title = " Rata-rata produk terjual ";
				
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
				
				$data = array('page_content'=>'gl_admin_rata_produk_terjual','halaman'=>$halaman,'list_rata_produk_terjual'=>$list_rata_produk_terjual,'msgbox_title' => $msgbox_title, 'sum_pesan' => $sum_pesan);
				$this->load->view('admin/container',$data);
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
	
	
	public function detail_penjualan()
	{
		if(($this->session->userdata('ses_user_admin') == null) or ($this->session->userdata('ses_pass_admin') == null))
		{
			header('Location: '.base_url().'gl-admin-login');
		}
		else
		{
			/*
			$cek_ses_login = $this->M_gl_karyawan->get_karyawan_jabatan_row(" WHERE A.user = '".$this->session->userdata('ses_user_admin')."' AND A.pass = '".base64_encode(md5($this->session->userdata('ses_pass_admin_pure')))."'  AND A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ");
			
			if(!empty($cek_ses_login))
			{
			*/
				$id_produk = $this->uri->segment(2,0);
				$data_produk = $this->M_gl_produk->get_produk_with_kode_kantor($_GET['kode_kantor'],'MD5(id_produk)',$id_produk);
				if(!empty($data_produk))
				{
					$data_produk = $data_produk->row();
					
					
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
					
					if((!empty($_GET['kode_kantor'])) && ($_GET['kode_kantor']!= "")  )
					{
						$kode_kantor = $_GET['kode_kantor'];
					}
					else
					{
						$kode_kantor = '';
					}
				
					if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
					{
						$cari = " WHERE A.kode_kantor = '".$kode_kantor."' 
						AND B.sts_penjualan NOT IN ('PENDING','DIBATALKAN')
						AND LEFT(B.ket_penjualan,7) <> 'DIHAPUS'
						-- AND A.isReady = 1
						AND MD5(A.id_produk) = '".$id_produk."'
						-- AND COALESCE(DATE(B.tgl_h_penjualan),'1900-01-01') BETWEEN '".$dari."' AND '".$sampai."'
						AND A.tgl_ins > '".$dari." 00:00:00'
						AND A.tgl_ins <= '".$sampai." 23:59:00'
						AND COALESCE(B.sts_penjualan,'') = 'SELESAI'
						AND (
								COALESCE(B.no_faktur,'') = '".str_replace("'","",$_GET['cari'])."'
								OR COALESCE(B.no_costmer,'') = '".str_replace("'","",$_GET['cari'])."'
								OR COALESCE(C.nama_costumer,'') = '".str_replace("'","",$_GET['cari'])."'
							)";
					}
					else
					{
						$cari = " WHERE A.kode_kantor = '".$kode_kantor."'
						AND B.sts_penjualan NOT IN ('PENDING','DIBATALKAN')
						AND LEFT(B.ket_penjualan,7) <> 'DIHAPUS'
						-- AND A.isReady = 1						
						AND MD5(A.id_produk) = '".$id_produk."'
						-- AND COALESCE(DATE(B.tgl_h_penjualan),'1900-01-01') BETWEEN '".$dari."' AND '".$sampai."'
						AND A.tgl_ins > '".$dari." 00:00:00'
						AND A.tgl_ins <= '".$sampai." 23:59:00'
						AND COALESCE(B.sts_penjualan,'') = 'SELESAI'
						";
					}
					
					
					$order_by = " ORDER BY A.tgl_ins DESC";
					
					$jum_row = $this->M_gl_stock_produk->count_laporan_detail_penjualan($cari)->JUMLAH;
					
					$this->load->library('pagination');
					//$config['first_url'] = base_url().'admin/jabatan?'.http_build_query($_GET);
					//$config['base_url'] = base_url().'admin/jabatan/';
					
					$config['first_url'] = site_url('gl-admin-laporan-stock-produk-detail-penjualan/'.$id_produk.'?'.http_build_query($_GET));
					$config['base_url'] = site_url('gl-admin-laporan-stock-produk-detail-penjualan/'.$id_produk);
					$config['total_rows'] = $jum_row;
					$config['uri_segment'] = 3;	
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
					
					$list_detail_penjualan = $this->M_gl_stock_produk->list_laporan_detail_penjualan($cari,$order_by,$config['per_page'],$this->uri->segment(3,0));
					
					$msgbox_title = " Laporan Detail Penjualan Produk ".$data_produk->nama_produk;
					
					
					if($config['per_page'] > $jum_row)
					{
						$jum_row_tampil = $jum_row;
					}
					else
					{
						$jum_row_tampil = $config['per_page'];
					}
					
					$offset = (integer) $this->uri->segment(3,0);
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
					
					$data = array('page_content'=>'gl_admin_stock_produk_detail_penjualan','halaman'=>$halaman,'list_detail_penjualan'=>$list_detail_penjualan,'msgbox_title' => $msgbox_title,'sum_pesan' => $sum_pesan,'data_produk'=>$data_produk);
					$this->load->view('admin/container',$data);
				}
				else
				{
					header('Location: '.base_url().'gl-admin-laporan-stock-produk');
				}
			/*
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
			*/
		}
	}

	function detail_penerimaan()
	{
		if(($this->session->userdata('ses_user_admin') == null) or ($this->session->userdata('ses_pass_admin') == null))
		{
			header('Location: '.base_url().'gl-admin-login');
		}
		else
		{
			/*
			$cek_ses_login = $this->M_gl_karyawan->get_karyawan_jabatan_row(" WHERE A.user = '".$this->session->userdata('ses_user_admin')."' AND A.pass = '".base64_encode(md5($this->session->userdata('ses_pass_admin_pure')))."'  AND A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ");
			
			if(!empty($cek_ses_login))
			{
			*/
				$id_produk = $this->uri->segment(2,0);
				$data_produk = $this->M_gl_produk->get_produk_with_kode_kantor($_GET['kode_kantor'],'MD5(id_produk)',$id_produk);
				if(!empty($data_produk))
				{
					$data_produk = $data_produk->row();
					
					
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
					
					if((!empty($_GET['kode_kantor'])) && ($_GET['kode_kantor']!= "")  )
					{
						$kode_kantor = $_GET['kode_kantor'];
					}
					else
					{
						$kode_kantor = '';
					}
				
					if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
					{
						$cari = " WHERE A.kode_kantor = '".$kode_kantor."' 
						AND MD5(A.id_produk) = '".$id_produk."'
						-- AND COALESCE(DATE(B.tgl_terima),'1900-01-01') BETWEEN '".$dari."' AND '".$sampai."'
						AND COALESCE(DATE(B.tgl_ins),'1900-01-01') BETWEEN '".$dari."' AND '".$sampai."'
						AND (
								COALESCE(B.no_surat_jalan,'') = '".str_replace("'","",$_GET['cari'])."'
								OR COALESCE(B.nama_pengirim,'') = '".str_replace("'","",$_GET['cari'])."'
								OR COALESCE(C.no_h_pembelian,'') = '".str_replace("'","",$_GET['cari'])."'
								OR COALESCE(D.nama_supplier,'') = '".str_replace("'","",$_GET['cari'])."'
							)";
					}
					else
					{
						$cari = " WHERE A.kode_kantor = '".$kode_kantor."' 
						AND MD5(A.id_produk) = '".$id_produk."'
						-- AND COALESCE(DATE(B.tgl_terima),'1900-01-01') BETWEEN '".$dari."' AND '".$sampai."'
						AND COALESCE(DATE(B.tgl_ins),'1900-01-01') BETWEEN '".$dari."' AND '".$sampai."'
						";
					}
					
					
					$order_by = " ORDER BY A.tgl_ins DESC";
					
					$jum_row = $this->M_gl_stock_produk->count_laporan_detail_penerimaan($cari)->JUMLAH;
					
					$this->load->library('pagination');
					//$config['first_url'] = base_url().'admin/jabatan?'.http_build_query($_GET);
					//$config['base_url'] = base_url().'admin/jabatan/';
					
					$config['first_url'] = site_url('gl-admin-laporan-stock-produk-detail-penerimaan/'.$id_produk.'?'.http_build_query($_GET));
					$config['base_url'] = site_url('gl-admin-laporan-stock-produk-detail-penerimaan/'.$id_produk);
					$config['total_rows'] = $jum_row;
					$config['uri_segment'] = 3;	
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
					
					$list_detail_penerimaan = $this->M_gl_stock_produk->list_laporan_detail_penerimaan($cari,$order_by,$config['per_page'],$this->uri->segment(3,0));
					
					$msgbox_title = " Laporan Detail Penerimaan/Pembelian Produk ".$data_produk->nama_produk;
					
					
					if($config['per_page'] > $jum_row)
					{
						$jum_row_tampil = $jum_row;
					}
					else
					{
						$jum_row_tampil = $config['per_page'];
					}
					
					$offset = (integer) $this->uri->segment(3,0);
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
					
					$data = array('page_content'=>'gl_admin_stock_produk_detail_penerimaan','halaman'=>$halaman,'list_detail_penerimaan'=>$list_detail_penerimaan,'msgbox_title' => $msgbox_title,'sum_pesan' => $sum_pesan,'data_produk'=>$data_produk);
					$this->load->view('admin/container',$data);
				}
				else
				{
					//header('Location: '.base_url().'gl-admin-laporan-stock-produk');
				}
			/*
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
			*/
		}
	}
	
	
	function detail_mutasi_in()
	{
		if(($this->session->userdata('ses_user_admin') == null) or ($this->session->userdata('ses_pass_admin') == null))
		{
			header('Location: '.base_url().'gl-admin-login');
		}
		else
		{
			/*
			$cek_ses_login = $this->M_gl_karyawan->get_karyawan_jabatan_row(" WHERE A.user = '".$this->session->userdata('ses_user_admin')."' AND A.pass = '".base64_encode(md5($this->session->userdata('ses_pass_admin_pure')))."'  AND A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ");
			
			if(!empty($cek_ses_login))
			{
			*/
				$id_produk = $this->uri->segment(2,0);
				$data_produk = $this->M_gl_produk->get_produk_with_kode_kantor($_GET['kode_kantor'],'MD5(id_produk)',$id_produk);
				if(!empty($data_produk))
				{
					$data_produk = $data_produk->row();
					
					
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
					
					if((!empty($_GET['kode_kantor'])) && ($_GET['kode_kantor']!= "")  )
					{
						$kode_kantor = $_GET['kode_kantor'];
					}
					else
					{
						$kode_kantor = '';
					}
				
					if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
					{
						$cari = " 
							WHERE A.kode_kantor = '".$kode_kantor."'
							AND COALESCE(B.sts_penjualan,'') = 'SELESAI'
							AND COALESCE(B.status_penjualan,'') = 'MUTASI-IN'
							AND MD5(A.id_produk) = '".$id_produk."'
							AND COALESCE(DATE(B.tgl_h_penjualan),'1900-01-01') BETWEEN '".$dari."' AND '".$sampai."'
							AND (
									COALESCE(B.no_faktur,'') = '".str_replace("'","",$_GET['cari'])."'
								)
						";
					}
					else
					{
						$cari = " 
							WHERE A.kode_kantor = '".$kode_kantor."'
							AND COALESCE(B.sts_penjualan,'') = 'SELESAI'
							AND COALESCE(B.status_penjualan,'') = 'MUTASI-IN'
							AND MD5(A.id_produk) = '".$id_produk."'
							AND COALESCE(DATE(B.tgl_h_penjualan),'1900-01-01') BETWEEN '".$dari."' AND '".$sampai."'
						";
					}
					
					
					$order_by = " ORDER BY A.tgl_ins DESC";
					
					$jum_row = $this->M_gl_stock_produk->count_laporan_detail_mutasi($cari)->JUMLAH;
					
					$this->load->library('pagination');
					//$config['first_url'] = base_url().'admin/jabatan?'.http_build_query($_GET);
					//$config['base_url'] = base_url().'admin/jabatan/';
					
					$config['first_url'] = site_url('gl-admin-laporan-stock-produk-detail-mutasiin/'.$id_produk.'?'.http_build_query($_GET));
					$config['base_url'] = site_url('gl-admin-laporan-stock-produk-detail-mutasiin/'.$id_produk);
					$config['total_rows'] = $jum_row;
					$config['uri_segment'] = 3;	
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
					
					$list_detail_mutasiin = $this->M_gl_stock_produk->list_laporan_detail_mutasi($cari,$order_by,$config['per_page'],$this->uri->segment(3,0));
					
					$msgbox_title = " Laporan Detail Mutasi IN/Masuk Produk ".$data_produk->nama_produk;
					
					
					if($config['per_page'] > $jum_row)
					{
						$jum_row_tampil = $jum_row;
					}
					else
					{
						$jum_row_tampil = $config['per_page'];
					}
					
					$offset = (integer) $this->uri->segment(3,0);
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
					
					$data = array('page_content'=>'gl_admin_stock_produk_detail_mutasiin','halaman'=>$halaman,'list_detail_mutasiin'=>$list_detail_mutasiin,'msgbox_title' => $msgbox_title,'sum_pesan' => $sum_pesan,'data_produk'=>$data_produk);
					$this->load->view('admin/container',$data);
				}
				else
				{
					header('Location: '.base_url().'gl-admin-laporan-stock-produk');
				}
			
			/*
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
			*/
		}
	}

	function detail_mutasi_out()
	{
		if(($this->session->userdata('ses_user_admin') == null) or ($this->session->userdata('ses_pass_admin') == null))
		{
			header('Location: '.base_url().'gl-admin-login');
		}
		else
		{
			/*
			$cek_ses_login = $this->M_gl_karyawan->get_karyawan_jabatan_row(" WHERE A.user = '".$this->session->userdata('ses_user_admin')."' AND A.pass = '".base64_encode(md5($this->session->userdata('ses_pass_admin_pure')))."'  AND A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ");
			
			if(!empty($cek_ses_login))
			{
			*/
				$id_produk = $this->uri->segment(2,0);
				$data_produk = $this->M_gl_produk->get_produk_with_kode_kantor($_GET['kode_kantor'],'MD5(id_produk)',$id_produk);
				if(!empty($data_produk))
				{
					$data_produk = $data_produk->row();
					
					
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
					
					if((!empty($_GET['kode_kantor'])) && ($_GET['kode_kantor']!= "")  )
					{
						$kode_kantor = $_GET['kode_kantor'];
					}
					else
					{
						$kode_kantor = '';
					}
				
					if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
					{
						$cari = " 
							WHERE A.kode_kantor = '".$kode_kantor."'
							AND COALESCE(B.sts_penjualan,'') = 'SELESAI'
							AND COALESCE(B.status_penjualan,'') = 'MUTASI-OUT'
							AND MD5(A.id_produk) = '".$id_produk."'
							AND COALESCE(DATE(B.tgl_h_penjualan),'1900-01-01') BETWEEN '".$dari."' AND '".$sampai."'
							AND (
									COALESCE(B.no_faktur,'') = '".str_replace("'","",$_GET['cari'])."'
								)
						";
					}
					else
					{
						$cari = " 
							WHERE A.kode_kantor = '".$kode_kantor."'
							AND COALESCE(B.sts_penjualan,'') = 'SELESAI'
							AND COALESCE(B.status_penjualan,'') = 'MUTASI-OUT'
							AND MD5(A.id_produk) = '".$id_produk."'
							AND COALESCE(DATE(B.tgl_h_penjualan),'1900-01-01') BETWEEN '".$dari."' AND '".$sampai."'
						";
					}
					
					
					$order_by = " ORDER BY A.tgl_ins DESC";
					
					$jum_row = $this->M_gl_stock_produk->count_laporan_detail_mutasi($cari)->JUMLAH;
					
					$this->load->library('pagination');
					//$config['first_url'] = base_url().'admin/jabatan?'.http_build_query($_GET);
					//$config['base_url'] = base_url().'admin/jabatan/';
					
					$config['first_url'] = site_url('gl-admin-laporan-stock-produk-detail-mutasiout/'.$id_produk.'?'.http_build_query($_GET));
					$config['base_url'] = site_url('gl-admin-laporan-stock-produk-detail-mutasiout/'.$id_produk);
					$config['total_rows'] = $jum_row;
					$config['uri_segment'] = 3;	
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
					
					$list_detail_mutasiout = $this->M_gl_stock_produk->list_laporan_detail_mutasi($cari,$order_by,$config['per_page'],$this->uri->segment(3,0));
					
					$msgbox_title = " Laporan Detail Mutasi Out/Pemakaian Produk ".$data_produk->nama_produk;
					
					
					if($config['per_page'] > $jum_row)
					{
						$jum_row_tampil = $jum_row;
					}
					else
					{
						$jum_row_tampil = $config['per_page'];
					}
					
					$offset = (integer) $this->uri->segment(3,0);
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
					
					$data = array('page_content'=>'gl_admin_stock_produk_detail_mutasiout','halaman'=>$halaman,'list_detail_mutasiout'=>$list_detail_mutasiout,'msgbox_title' => $msgbox_title,'sum_pesan' => $sum_pesan,'data_produk'=>$data_produk);
					$this->load->view('admin/container',$data);
				}
				else
				{
					header('Location: '.base_url().'gl-admin-laporan-stock-produk');
				}
			/*
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
			*/
		}
	}

	function detail_retur_pembelian()
	{
		if(($this->session->userdata('ses_user_admin') == null) or ($this->session->userdata('ses_pass_admin') == null))
		{
			header('Location: '.base_url().'gl-admin-login');
		}
		else
		{
			/*
			$cek_ses_login = $this->M_gl_karyawan->get_karyawan_jabatan_row(" WHERE A.user = '".$this->session->userdata('ses_user_admin')."' AND A.pass = '".base64_encode(md5($this->session->userdata('ses_pass_admin_pure')))."'  AND A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ");
			
			if(!empty($cek_ses_login))
			{
			*/
				$id_produk = $this->uri->segment(2,0);
				$data_produk = $this->M_gl_produk->get_produk_with_kode_kantor($_GET['kode_kantor'],'MD5(id_produk)',$id_produk);
				if(!empty($data_produk))
				{
					$data_produk = $data_produk->row();
					
					
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
					
					if((!empty($_GET['kode_kantor'])) && ($_GET['kode_kantor']!= "")  )
					{
						$kode_kantor = $_GET['kode_kantor'];
					}
					else
					{
						$kode_kantor = '';
					}
				
					if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
					{
						$cari = " 
							WHERE A.kode_kantor = '".$kode_kantor."'
							AND COALESCE(B.sts_penjualan,'') = 'SELESAI'
							AND COALESCE(B.status_penjualan,'') = 'RETUR-PEMBELIAN'
							AND MD5(A.id_produk) = '".$id_produk."'
							AND COALESCE(DATE(B.tgl_h_penjualan),'1900-01-01') BETWEEN '".$dari."' AND '".$sampai."'
							AND (
									COALESCE(B.no_faktur,'') = '".str_replace("'","",$_GET['cari'])."'
									OR COALESCE(C.kode_supplier,'') = '".str_replace("'","",$_GET['cari'])."'
									OR COALESCE(B.nama_supplier,'') = '".str_replace("'","",$_GET['cari'])."'
								)
						";
					}
					else
					{
						$cari = " 
							WHERE A.kode_kantor = '".$kode_kantor."'
							AND COALESCE(B.sts_penjualan,'') = 'SELESAI'
							AND COALESCE(B.status_penjualan,'') = 'RETUR-PEMBELIAN'
							AND MD5(A.id_produk) = '".$id_produk."'
							AND COALESCE(DATE(B.tgl_h_penjualan),'1900-01-01') BETWEEN '".$dari."' AND '".$sampai."'
						";
					}
					
					
					$order_by = " ORDER BY A.tgl_ins DESC";
					
					$jum_row = $this->M_gl_stock_produk->count_laporan_detail_retur($cari)->JUMLAH;
					
					$this->load->library('pagination');
					//$config['first_url'] = base_url().'admin/jabatan?'.http_build_query($_GET);
					//$config['base_url'] = base_url().'admin/jabatan/';
					
					$config['first_url'] = site_url('gl-admin-laporan-stock-produk-detail-retur-pembelian/'.$id_produk.'?'.http_build_query($_GET));
					$config['base_url'] = site_url('gl-admin-laporan-stock-produk-detail-retur-pembelian/'.$id_produk);
					$config['total_rows'] = $jum_row;
					$config['uri_segment'] = 3;	
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
					
					$list_detail_retur_pembelian = $this->M_gl_stock_produk->list_laporan_detail_retur($cari,$order_by,$config['per_page'],$this->uri->segment(3,0));
					
					$msgbox_title = " Laporan Detail Retur Ke Supplier/Pembelian Produk ".$data_produk->nama_produk;
					
					
					if($config['per_page'] > $jum_row)
					{
						$jum_row_tampil = $jum_row;
					}
					else
					{
						$jum_row_tampil = $config['per_page'];
					}
					
					$offset = (integer) $this->uri->segment(3,0);
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
					
					$data = array('page_content'=>'gl_admin_stock_produk_detail_retur_pembelian','halaman'=>$halaman,'list_detail_retur_pembelian'=>$list_detail_retur_pembelian,'msgbox_title' => $msgbox_title,'sum_pesan' => $sum_pesan,'data_produk'=>$data_produk);
					$this->load->view('admin/container',$data);
				}
				else
				{
					header('Location: '.base_url().'gl-admin-laporan-stock-produk');
				}
			/*
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
			*/
		}
	}
	
	function detail_histori_produk()
	{
		if(($this->session->userdata('ses_user_admin') == null) or ($this->session->userdata('ses_pass_admin') == null))
		{
			header('Location: '.base_url().'gl-admin-login');
		}
		else
		{
			/*
			$cek_ses_login = $this->M_gl_karyawan->get_karyawan_jabatan_row(" WHERE A.user = '".$this->session->userdata('ses_user_admin')."' AND A.pass = '".base64_encode(md5($this->session->userdata('ses_pass_admin_pure')))."'  AND A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ");
			
			if(!empty($cek_ses_login))
			{
			*/
				$id_produk = $this->uri->segment(2,0);
				$data_produk = $this->M_gl_produk->get_produk_with_kode_kantor($_GET['kode_kantor'],'MD5(id_produk)',$id_produk);
				if(!empty($data_produk))
				{
					$data_produk = $data_produk->row();
					
					
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
					
					if((!empty($_GET['kode_kantor'])) && ($_GET['kode_kantor']!= "")  )
					{
						$kode_kantor = $_GET['kode_kantor'];
					}
					else
					{
						$kode_kantor = '';
					}
					
					
					$list_detail_histori = $this->M_gl_stock_produk->list_histori_produk($kode_kantor,$data_produk->id_produk,$dari,$sampai);
					
					$msgbox_title = " Laporan History Produk ".$data_produk->nama_produk;
					
					$data = array('page_content'=>'gl_admin_stock_produk_detail_histori_produk','list_detail_histori'=>$list_detail_histori,'msgbox_title' => $msgbox_title,'data_produk'=>$data_produk);
					$this->load->view('admin/container',$data);
				}
				else
				{
					header('Location: '.base_url().'gl-admin-laporan-stock-produk');
				}
			/*
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
			*/
		}
	}
	
	
	public function excel_stock_produk()
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
				/*
				if((!empty($_GET['kode_kantor'])) && ($_GET['kode_kantor']!= "")  )
				{
					$kode_kantor = str_replace("'","",$_GET['kode_kantor']);
				}
				else
				{
					$kode_kantor = $this->session->userdata('ses_kode_kantor');
				}
				
				if((!empty($_GET['sampai'])) && ($_GET['sampai']!= "")  )
				{
					$sampai = $_GET['sampai'];
				}
				else
				{
					$sampai = date("Y-m-d");
				}
				
				
				
				$data_kantor = $this->M_gl_pengaturan->get_data_kantor(" WHERE kode_kantor = '".$kode_kantor."'");
				$list_stock_produk = $this->M_gl_stock_produk->list_stock_produk($kode_kantor,"",' PROD.nama_produk ASC',0,10000,$sampai,'23','59','');
				*/
				
				
				if((!empty($_GET['kode_kantor'])) && ($_GET['kode_kantor']!= "")  )
				{
					$kode_kantor = str_replace("'","",$_GET['kode_kantor']);
				}
				else
				{
					$kode_kantor = $this->session->userdata('ses_kode_kantor');
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
					$cari = str_replace("'","",$_GET['cari']);
				}
				else
				{
					$cari = "";
				}
				
				$list_stock_produk = $this->M_gl_pst_inventori->list_stock_produk($kode_kantor,$dari,$sampai,0,30000,$cari);
				$data_kantor = $this->M_gl_pengaturan->get_data_kantor(" WHERE kode_kantor = '".$kode_kantor."'");
				
				//$sum_stock_produk = $this->M_gl_stock_produk->sum_stock_produk($this->session->userdata('ses_kode_kantor'),$cari,$sampai,'23','59');
				
				//($cari,$config['per_page'],$this->uri->segment(2,0));
				
				$data = array('page_content'=>'gl_admin_excel_stock','list_stock_produk'=>$list_stock_produk,'data_kantor' => $data_kantor);
				$this->load->view('admin/page/gl_admin_excel_stock.html',$data);
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
	
	public function excel_view_lap_rata_penjualan()
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
					$cari = "
							WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' 
							AND COALESCE(sts_penjualan,'') IN ('SELESAI','PEMBAYARAN')
							AND isProduk <> 'JASA'
							AND COALESCE(DATE(tgl_h_penjualan),'0000-00-00') BETWEEN '".$dari."' AND '".$sampai."'
							AND id_produk <> ''
							AND (kode_produk LIKE '%".str_replace("'","",$_GET['cari'])."%' OR nama_produk LIKE '%".str_replace("'","",$_GET['cari'])."%')
					";
					
					
				}
				else
				{
					$cari = "
							WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' 
							AND COALESCE(sts_penjualan,'') IN ('SELESAI','PEMBAYARAN')
							AND isProduk <> 'JASA'
							AND COALESCE(DATE(tgl_h_penjualan),'0000-00-00') BETWEEN '".$dari."' AND '".$sampai."'
							AND id_produk <> ''
							AND (kode_produk LIKE '%%' OR nama_produk LIKE '%%')
					";
				}
				
				$order_by = "ORDER BY nama_produk ASC";
				
				$list_rata_produk_terjual = $this->M_gl_stock_produk->list_rata_produk_terjual($dari,$sampai,$cari,$order_by,10000,0);
				
				//$sum_stock_produk = $this->M_gl_stock_produk->sum_stock_produk($this->session->userdata('ses_kode_kantor'),$cari,$sampai,'23','59');
				
				//($cari,$config['per_page'],$this->uri->segment(2,0));
				
				
				
				$data = array('page_content'=>'gl_admin_excel_rata_produk','list_rata_produk_terjual'=>$list_rata_produk_terjual);
				//$this->load->view('admin/container',$data);
				$this->load->view('admin/page/gl_admin_excel_rata_produk.html',$data);
			}
			else
			{
				header('Location: '.base_url().'gl-admin-login');
			}
		}
	}
	
	public function excel_view_analisa_order()
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
				if((!empty($_GET['sampai'])) && ($_GET['sampai']!= "")  )
				{
					$sampai = $_GET['sampai'];
				}
				else
				{
					$sampai = date("Y-m-d");
				}
				
				
				
				$list_stock_produk = $this->M_gl_stock_produk->list_analisa_order($this->session->userdata('ses_kode_kantor'),"",' PROD.nama_produk ASC',0,10000,$sampai,'23','59','');
				
				//$sum_stock_produk = $this->M_gl_stock_produk->sum_stock_produk($this->session->userdata('ses_kode_kantor'),$cari,$sampai,'23','59');
				
				//($cari,$config['per_page'],$this->uri->segment(2,0));
				
				$data = array('page_content'=>'gl_admin_excel_analisa_order','list_stock_produk'=>$list_stock_produk);
				$this->load->view('admin/page/gl_admin_excel_analisa_order.html',$data);
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