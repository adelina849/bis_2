<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
//$route['default_controller'] = 'C_gl_public_home';
$route['default_controller'] = 'C_gl_login/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//GLAFIDSYA
	//NOTOFIKASI PASIEN
		$route['gl-admin-notofikasi-pasien'] = "C_gl_admin_pengaturan/notifikasi_pasien";
		$route['gl-admin-notofikasi-pasien/(:any)'] = 'C_gl_admin_pengaturan/notifikasi_pasien';
		
		$route['gl-admin-notofikasi-pasien-sound'] = "C_gl_admin_pengaturan/notifikasi_pasien_jumlah_saja";
		$route['gl-admin-notofikasi-pasien-sound/(:any)'] = 'C_gl_admin_pengaturan/notifikasi_pasien_jumlah_saja';
		
		$route['gl-admin-notofikasi-sum-transaksi'] = "C_gl_admin_pengaturan/get_notifikasi_transaksi";
		$route['gl-admin-notofikasi-sum-transaksi/(:any)'] = 'C_gl_admin_pengaturan/get_notifikasi_transaksi';
	//NOTOFIKASI PASIEN
	
	
	//LOGIN
		$route['gl-admin-cek-login'] = "C_gl_login/cek_login";
		$route['gl-admin-cek-login/(:any)'] = 'C_gl_login/cek_login';
		
		$route['gl-admin-login'] = "C_gl_login/index";
		$route['gl-admin-login/(:any)'] = 'C_gl_login/index';
		
		$route['gl-admin-logout'] = "C_gl_login/logout";
		$route['gl-admin-logout/(:any)'] = 'C_gl_login/logout';
		
		$route['gl-admin'] = "C_gl_admin/index";
		$route['gl-admin/(:any)'] = 'C_gl_admin/index';
		
		$route['gl-admin-ubah-profile-kantor'] = "C_gl_admin/ubah_data_kantor";
		$route['gl-admin-ubah-profile-kantor/(:any)'] = 'C_gl_admin/ubah_data_kantor';
		
		$route['gl-profile-simpan'] = "C_gl_admin_profile/simpan";
		$route['gl-profile-simpan/(:any)'] = 'C_gl_admin_profile/simpan';
	//LOGIN
	
	//BASIS DATA
		//KARYAWAN
			//JABATAN
				$route['gl-admin-jabatan'] = "C_gl_admin_jabatan/index";
				$route['gl-admin-jabatan/(:any)'] = 'C_gl_admin_jabatan/index';
				
				$route['gl-admin-jabatan-simpan'] = "C_gl_admin_jabatan/simpan";
				$route['gl-admin-jabatan-simpan/(:any)'] = 'C_gl_admin_jabatan/simpan';
				
				$route['gl-admin-jabatan-hapus'] = "C_gl_admin_jabatan/hapus";
				$route['gl-admin-jabatan-hapus/(:any)'] = 'C_gl_admin_jabatan/hapus';
				
				//HAK AKSES
					$route['gl-admin-jabatan-hak-akses'] = "C_gl_admin_jabatan_akses/index";
					$route['gl-admin-jabatan-hak-akses/(:any)'] = 'C_gl_admin_jabatan_akses/index';
					$route['gl-admin-jabatan-hak-akses/(:any)/(:any)'] = 'C_gl_admin_jabatan_akses/index';
					
					$route['gl-admin-jabatan-hak-akses-cek-akses-jabatan'] = "C_gl_admin_jabatan_akses/cek_hak_akses";
					$route['gl-admin-jabatan-hak-akses-cek-akses-jabatan/(:any)'] = 'C_gl_admin_jabatan_akses/cek_hak_akses';
				//HAK AKSES
			//JABATAN
			
			//DEPARTEMENT
				$route['gl-admin-departement'] = "C_gl_admin_dept/index";
				$route['gl-admin-departement/(:any)'] = 'C_gl_admin_dept/index';
				
				$route['gl-admin-departement-simpan'] = "C_gl_admin_dept/simpan";
				$route['gl-admin-departement-simpan/(:any)'] = 'C_gl_admin_dept/simpan';
				
				$route['gl-admin-departement-hapus'] = "C_gl_admin_dept/hapus";
				$route['gl-admin-departement-hapus/(:any)'] = 'C_gl_admin_dept/hapus';
			//DEPARTEMENT
			
			//RECRUITMENT
				$route['gl-admin-recruitment'] = "C_gl_admin_req/index";
				$route['gl-admin-recruitment/(:any)'] = 'C_gl_admin_req/index';
				
				$route['gl-admin-recruitment-simpan'] = "C_gl_admin_req/simpan";
				$route['gl-admin-recruitment-simpan/(:any)'] = 'C_gl_admin_req/simpan';
				
				$route['gl-admin-recruitment-hapus'] = "C_gl_admin_req/hapus";
				$route['gl-admin-recruitment-hapus/(:any)'] = 'C_gl_admin_req/hapus';
				
				$route['gl-admin-recruitment-cek-no-karyawan'] = "C_gl_admin_req/cek_karyawan";
				$route['gl-admin-recruitment-cek-no-karyawan/(:any)'] = 'C_gl_admin_req/cek_karyawan';
				
				$route['gl-admin-recruitment-proses'] = "C_gl_admin_req/ubah_proses_karyawan_req";
				$route['gl-admin-recruitment-proses/(:any)'] = 'C_gl_admin_req/ubah_proses_karyawan_req';
			//RECRUITMENT
			
			//TRAINING
				$route['gl-admin-training'] = "C_gl_admin_training/index";
				$route['gl-admin-training/(:any)'] = 'C_gl_admin_training/index';
				
				$route['gl-admin-training-simpan'] = "C_gl_admin_training/simpan";
				$route['gl-admin-training-simpan/(:any)'] = 'C_gl_admin_training/simpan';
				
				$route['gl-admin-training-hapus'] = "C_gl_admin_training/hapus";
				$route['gl-admin-training-hapus/(:any)'] = 'C_gl_admin_training/hapus';
				
					//EVENT TRAINING
						$route['gl-admin-training-event'] = "C_gl_admin_training_event/index";
						$route['gl-admin-training-event/(:any)'] = 'C_gl_admin_training_event/index';
						
						$route['gl-admin-training-event-simpan'] = "C_gl_admin_training_event/simpan";
						$route['gl-admin-training-event-simpan/(:any)'] = 'C_gl_admin_training_event/simpan';
						
						$route['gl-admin-training-event-hapus'] = "C_gl_admin_training_event/hapus";
						$route['gl-admin-training-event-hapus/(:any)'] = 'C_gl_admin_training_event/hapus';
						$route['gl-admin-training-event-hapus/(:any)/(:any)'] = 'C_gl_admin_training_event/hapus';
						
						$route['gl-admin-training-event-json-cek-peserta'] = "C_gl_admin_training_event/cek_table_peserta_training";
						$route['gl-admin-training-eventjson-cek-peserta/(:any)'] = 'C_gl_admin_training_event/cek_table_peserta_training';
						
						
						
							//EVENT TRAINING - PESERTA
								$route['gl-admin-training-event-peserta'] = "C_gl_admin_training_event_peserta/index";
								$route['gl-admin-training-event-peserta/(:any)'] = 'C_gl_admin_training_event_peserta/index';
								$route['gl-admin-training-event-peserta/(:any)/(:any)'] = 'C_gl_admin_training_event_peserta/index';
								
								$route['gl-admin-training-event-peserta-cek'] = "C_gl_admin_training_event_peserta/cek_peserta";
								$route['gl-admin-training-event-peserta-cek/(:any)'] = 'C_gl_admin_training_event_peserta/cek_peserta';
								
								$route['gl-admin-training-event-peserta-cek-nilai'] = "C_gl_admin_training_event_peserta/cek_nilai";
								$route['gl-admin-training-event-peserta-cek-nilai/(:any)'] = 'C_gl_admin_training_event_peserta/cek_nilai';
							//EVENT TRAINING - PESERTA
					//EVENT TRAINING
			//TRAINING
			
			//MUTASI,PROMOSI,ROTASI DAN DEMOSI
				$route['gl-admin-promosi'] = "C_gl_admin_karyawan_jabatan/index";
				$route['gl-admin-promosi/(:any)'] = 'C_gl_admin_karyawan_jabatan/index';
				
				$route['gl-admin-promosi-simpan'] = "C_gl_admin_karyawan_jabatan/simpan";
				$route['gl-admin-promosi-simpan/(:any)'] = 'C_gl_admin_karyawan_jabatan/simpan';
				
				$route['gl-admin-promosi-hapus'] = "C_gl_admin_karyawan_jabatan/hapus";
				$route['gl-admin-promosi-hapus/(:any)'] = 'C_gl_admin_karyawan_jabatan/hapus';
				
				$route['gl-admin-promosi-list-karyawan'] = "C_gl_admin_karyawan_jabatan/list_karyawan";
				$route['gl-admin-promosi-list-karyawan/(:any)'] = 'C_gl_admin_karyawan_jabatan/list_karyawan';
				
				$route['gl-admin-promosi-cek-kode-promosi'] = "C_gl_admin_karyawan_jabatan/cek_karyawan_jabatan";
				$route['gl-admin-promosi-cek-kode-promosi/(:any)'] = 'C_gl_admin_karyawan_jabatan/cek_karyawan_jabatan';
				
				$route['gl-admin-promosi-cek-nilai'] = "C_gl_admin_karyawan_jabatan/ubah_proses_karyawan_promo";
				$route['gl-admin-promosi-cek-nilai/(:any)'] = 'C_gl_admin_karyawan_jabatan/ubah_proses_karyawan_promo';
			//MUTASI,PROMOSI,ROTASI DAN DEMOSI
			
			//DATA KARYAWAN
				$route['gl-admin-data-karyawan'] = "C_gl_admin_data_karyawan/index";
				$route['gl-admin-data-karyawan/(:any)'] = 'C_gl_admin_data_karyawan/index';
				
				$route['gl-admin-data-karyawan-resign'] = "C_gl_admin_data_karyawan/list_karyawan_resign";
				$route['gl-admin-data-karyawan-resign/(:any)'] = 'C_gl_admin_data_karyawan/list_karyawan_resign';
				
				$route['gl-admin-data-karyawan-simpan'] = "C_gl_admin_data_karyawan/simpan";
				$route['gl-admin-data-karyawan-simpan/(:any)'] = 'C_gl_admin_data_karyawan/simpan';
				
				$route['gl-admin-data-karyawan-hapus'] = "C_gl_admin_data_karyawan/hapus";
				$route['gl-admin-data-karyawan-hapus/(:any)'] = 'C_gl_admin_data_karyawan/hapus';
				
				$route['gl-admin-data-karyawan-cek-no-karyawan'] = "C_gl_admin_data_karyawan/cek_karyawan";
				$route['gl-admin-data-karyawan-cek-no-karyawan/(:any)'] = 'C_gl_admin_data_karyawan/cek_karyawan';
				
				$route['gl-admin-data-karyawan-proses'] = "C_gl_admin_data_karyawan/ubah_proses_karyawan_req";
				$route['gl-admin-data-karyawan-proses/(:any)'] = 'C_gl_admin_data_karyawan/ubah_proses_karyawan_req';
				
				$route['gl-admin-data-karyawan-detail'] = "C_gl_admin_data_karyawan/detail";
				$route['gl-admin-data-karyawan-detail/(:any)'] = 'C_gl_admin_data_karyawan/detail';
				$route['gl-admin-data-karyawan-detail/(:any)/(:any)'] = 'C_gl_admin_data_karyawan/detail';
				$route['gl-admin-data-karyawan-detail/(:any)/(:any)/(:any)'] = 'C_gl_admin_data_karyawan/detail';
				
				$route['gl-admin-data-karyawan-detail-keluarga'] = "C_gl_admin_data_karyawan/list_keluarga";
				$route['gl-admin-data-karyawan-detail-keluarga/(:any)'] = 'C_gl_admin_data_karyawan/list_keluarga';
				
				$route['gl-admin-data-karyawan-detail-keluarga-simpan'] = "C_gl_admin_data_karyawan/simpan_keluarga";
				$route['gl-admin-data-karyawan-detail-keluarga-simpan/(:any)'] = 'C_gl_admin_data_karyawan/simpan_keluarga';
				
				$route['gl-admin-data-karyawan-detail-keluarga-cek'] = "C_gl_admin_data_karyawan/cek_keluarga";
				$route['gl-admin-data-karyawan-detail-keluarga-cek/(:any)'] = 'C_gl_admin_data_karyawan/cek_keluarga';
				
				$route['gl-admin-data-karyawan-detail-keluarga-hapus'] = "C_gl_admin_data_karyawan/hapus_keluarga";
				$route['gl-admin-data-karyawan-detail-keluarga-hapus/(:any)'] = 'C_gl_admin_data_karyawan/hapus_keluarga';
				$route['gl-admin-data-karyawan-detail-keluarga-hapus/(:any)/(:any)'] = 'C_gl_admin_data_karyawan/hapus_keluarga';
				
				$route['gl-admin-data-karyawan-detail-sekolah'] = "C_gl_admin_data_karyawan/list_sekolah";
				$route['gl-admin-data-karyawan-detail-sekolah/(:any)'] = 'C_gl_admin_data_karyawan/list_sekolah';
				
				$route['gl-admin-data-karyawan-detail-sekolah-simpan'] = "C_gl_admin_data_karyawan/simpan_sekolah";
				$route['gl-admin-data-karyawan-detail-sekolah-simpan/(:any)'] = 'C_gl_admin_data_karyawan/simpan_sekolah';
				
				$route['gl-admin-data-karyawan-detail-sekolah-hapus'] = "C_gl_admin_data_karyawan/hapus_sekolah";
				$route['gl-admin-data-karyawan-detail-sekolah-hapus/(:any)'] = 'C_gl_admin_data_karyawan/hapus_sekolah';
				$route['gl-admin-data-karyawan-detail-sekolah-hapus/(:any)/(:any)'] = 'C_gl_admin_data_karyawan/hapus_sekolah';
				
				$route['gl-admin-data-karyawan-detail-training'] = "C_gl_admin_data_karyawan/list_training";
				$route['gl-admin-data-karyawan-detail-training/(:any)'] = 'C_gl_admin_data_karyawan/list_training';
				
				$route['gl-admin-data-karyawan-detail-phk'] = "C_gl_admin_data_karyawan/phk";
				$route['gl-admin-data-karyawan-detail-phk/(:any)'] = 'C_gl_admin_data_karyawan/phk';
				$route['gl-admin-data-karyawan-detail-phk/(:any)/(:any)'] = 'C_gl_admin_data_karyawan/phk';
				
				$route['gl-admin-data-karyawan-detail-riwayat-jabatan'] = "C_gl_admin_data_karyawan/list_riwayat_jabatan";
				$route['gl-admin-data-karyawan-detail-riwayat-jabatan/(:any)'] = 'C_gl_admin_data_karyawan/list_riwayat_jabatan';
				
				$route['gl-admin-data-karyawan-detail-riwayat-punishment'] = "C_gl_admin_data_karyawan/list_riwayat_punishment";
				$route['gl-admin-data-karyawan-detail-riwayat-punishment/(:any)'] = 'C_gl_admin_data_karyawan/list_riwayat_punishment';
				
			//DATA KARYAWAN
			
			//SOP
				$route['gl-admin-sop'] = "C_gl_admin_sop/index";
				$route['gl-admin-sop/(:any)'] = 'C_gl_admin_sop/index';
				
				$route['gl-admin-sop-simpan'] = "C_gl_admin_sop/simpan";
				$route['gl-admin-sop-simpan/(:any)'] = 'C_gl_admin_sop/simpan';
				
				$route['gl-admin-sop-hapus'] = "C_gl_admin_sop/hapus";
				$route['gl-admin-sop-hapus/(:any)'] = 'C_gl_admin_sop/hapus';
			//SOP
			
			//REWARD & PUNISHMENT
				//PERATURAN
					$route['gl-admin-peraturan'] = "C_gl_admin_peraturan/index";
					$route['gl-admin-peraturan/(:any)'] = 'C_gl_admin_peraturan/index';
					
					$route['gl-admin-peraturan-simpan'] = "C_gl_admin_peraturan/simpan";
					$route['gl-admin-peraturan-simpan/(:any)'] = 'C_gl_admin_peraturan/simpan';
					
					$route['gl-admin-peraturan-hapus'] = "C_gl_admin_peraturan/hapus";
					$route['gl-admin-peraturan-hapus/(:any)'] = 'C_gl_admin_peraturan/hapus';
				//PERATURAN
				
				//PUNISHMENT
					$route['gl-admin-punishment'] = "C_gl_admin_punish/index";
					$route['gl-admin-punishment/(:any)'] = 'C_gl_admin_punish/index';
					
					$route['gl-admin-punishment-simpan'] = "C_gl_admin_punish/simpan";
					$route['gl-admin-punishment-simpan/(:any)'] = 'C_gl_admin_punish/simpan';
					
					$route['gl-admin-punishment-hapus'] = "C_gl_admin_punish/hapus";
					$route['gl-admin-punishment-hapus/(:any)'] = 'C_gl_admin_punish/hapus';
					
					$route['gl-admin-punishment-list-karyawan'] = "C_gl_admin_punish/list_karyawan";
					$route['gl-admin-punishment-list-karyawan/(:any)'] = 'C_gl_admin_punish/list_karyawan';
					
					$route['gl-admin-punishment-list-peraturan'] = "C_gl_admin_punish/list_peraturan";
					$route['gl-admin-punishment-list-peraturan/(:any)'] = 'C_gl_admin_punish/list_peraturan';
				//PUNISHMENT
			//REWARD & PUNISHMENT
			
			//PEMBERIAN AKUN
				$route['gl-admin-akun'] = "C_gl_admin_akun/index";
				$route['gl-admin-akun/(:any)'] = 'C_gl_admin_akun/index';
				
				$route['gl-admin-akun-accept'] = "C_gl_admin_akun/beri_akun";
				$route['gl-admin-akun-accept/(:any)'] = 'C_gl_admin_akun/beri_akun';
				
				$route['gl-admin-akun-cek'] = "C_gl_admin_akun/cek_karyawan";
				$route['gl-admin-akun-cek/(:any)'] = 'C_gl_admin_akun/cek_karyawan';
			//PEMBERIAN AKUN
			
		//KARYAWAN
		
		//PRODUK DAN JASA
			//KATEGORI PRODUK DAN JASA
				$route['gl-admin-kategori-produk-jasa'] = "C_gl_admin_kat_produk/index";
				$route['gl-admin-kategori-produk-jasa/(:any)'] = 'C_gl_admin_akun/index';
				
				$route['gl-admin-kategori-produk-jasa-simpan'] = "C_gl_admin_kat_produk/simpan";
				$route['gl-admin-kategori-produk-jasa-simpan/(:any)'] = 'C_gl_admin_kat_produk/simpan';
				
				$route['gl-admin-kategori-produk-jasa-hapus'] = "C_gl_admin_kat_produk/hapus";
				$route['gl-admin-kategori-produk-jasa-hapus/(:any)'] = 'C_gl_admin_kat_produk/hapus';
				
				$route['gl-admin-kategori-produk-jasa-cek'] = "C_gl_admin_kat_produk/cek_kat_produk";
				$route['gl-admin-kategori-produk-jasa-cek/(:any)'] = 'C_gl_admin_kat_produk/cek_kat_produk';
			//KATEGORI PRODUK DAN JASA
			
			//SATUAN PRODUK DAN JASA
				$route['gl-admin-satuan-produk-jasa'] = "C_gl_admin_satuan/index";
				$route['gl-admin-satuan-produk-jasa/(:any)'] = 'C_gl_admin_satuan/index';
				
				$route['gl-admin-satuan-produk-jasa-simpan'] = "C_gl_admin_satuan/simpan";
				$route['gl-admin-satuan-produk-jasa-simpan/(:any)'] = 'C_gl_admin_satuan/simpan';
				
				$route['gl-admin-satuan-produk-jasa-hapus'] = "C_gl_admin_satuan/hapus";
				$route['gl-admin-satuan-produk-jasa-hapus/(:any)'] = 'C_gl_admin_satuan/hapus';
				
				$route['gl-admin-satuan-produk-jasa-cek'] = "C_gl_admin_satuan/cek_satuan";
				$route['gl-admin-satuan-produk-jasa-cek/(:any)'] = 'C_gl_admin_satuan/cek_satuan';
			//SATUAN PRODUK DAN JASA
			
			//PRODUK DAN JASA
				$route['gl-admin-produk-jasa'] = "C_gl_admin_produk/index";
				$route['gl-admin-produk-jasa/(:any)'] = 'C_gl_admin_produk/index';
				
				$route['gl-admin-produk-jasa-simpan'] = "C_gl_admin_produk/simpan";
				$route['gl-admin-produk-jasa-simpan/(:any)'] = 'C_gl_admin_produk/simpan';
				
				$route['gl-admin-produk-jasa-hapus'] = "C_gl_admin_produk/hapus";
				$route['gl-admin-produk-jasa-hapus/(:any)'] = 'C_gl_admin_produk/hapus';
				
				$route['gl-admin-produk-jasa-list-supplier'] = "C_gl_admin_produk/list_supplier";
				$route['gl-admin-produk-jasa-list-supplier/(:any)'] = 'C_gl_admin_produk/list_supplier';
				
				$route['gl-admin-produk-jasa-isNotActive'] = "C_gl_admin_produk/cek_isNotActive";
				$route['gl-admin-produk-jasa-isNotActive/(:any)'] = 'C_gl_admin_produk/cek_isNotActive';
				
				$route['gl-admin-produk-jasa-isReport'] = "C_gl_admin_produk/cek_isReport";
				$route['gl-admin-produk-jasa-isReport/(:any)'] = 'C_gl_admin_produk/cek_isReport';
				
				$route['gl-admin-satuan-produk-jasa-kategori'] = "C_gl_admin_produk/list_kategori_produk";
				$route['gl-admin-satuan-produk-jasa-kategori/(:any)'] = 'C_gl_admin_produk/list_kategori_produk';
				
				$route['gl-admin-satuan-produk-jasa-kategori-simpan'] = "C_gl_admin_produk/cek_isNotActiveKategori";
				$route['gl-admin-satuan-produk-jasa-kategori-simpan(:any)'] = 'C_gl_admin_produk/cek_isNotActiveKategori';
				
				$route['gl-admin-produk-jasa-hpp-jasa'] = "C_gl_admin_hpp_jasa/index";
				$route['gl-admin-produk-jasa-hpp-jasa/(:any)'] = 'C_gl_admin_hpp_jasa/index';
				$route['gl-admin-produk-jasa-hpp-jasa/(:any)/(:any)'] = 'C_gl_admin_hpp_jasa/index';
				
				$route['gl-admin-produk-jasa-hpp-jasa-simpan'] = "C_gl_admin_hpp_jasa/simpan";
				$route['gl-admin-produk-jasa-hpp-jasa-simpan/(:any)'] = 'C_gl_admin_hpp_jasa/simpan';
				
				$route['gl-admin-produk-jasa-hpp-jasa-hapus'] = "C_gl_admin_hpp_jasa/hapus";
				$route['gl-admin-produk-jasa-hpp-jasa-hapus/(:any)'] = 'C_gl_admin_hpp_jasa/hapus';
				$route['gl-admin-produk-jasa-hpp-jasa-hapus/(:any)/(:any)'] = 'C_gl_admin_hpp_jasa/hapus';
				
				$route['gl-admin-produk-jasa-hpp-jasa-list-produk'] = "C_gl_admin_hpp_jasa/list_produk";
				$route['gl-admin-produk-jasa-hpp-jasa-list-produk/(:any)'] = 'C_gl_admin_hpp_jasa/list_produk';
				
				$route['gl-admin-produk-jasa-hpp-jasa-list-assets'] = "C_gl_admin_hpp_jasa/list_assets";
				$route['gl-admin-produk-jasa-hpp-jasa-list-assets/(:any)'] = 'C_gl_admin_hpp_jasa/list_assets';
			//PRODUK DAN JASA
		//PRODUK DAN JASA
		
		//PASIEN
			//KATEGORI PASIEN
				$route['gl-admin-kategori-pasien'] = "C_gl_admin_kat_costumer/index";
				$route['gl-admin-kategori-pasien/(:any)'] = 'C_gl_admin_kat_costumer/index';
				
				$route['gl-admin-kategori-pasien-simpan'] = "C_gl_admin_kat_costumer/simpan";
				$route['gl-admin-kategori-pasien-simpan/(:any)'] = 'C_gl_admin_kat_costumer/simpan';
				
				$route['gl-admin-kategori-pasien-hapus'] = "C_gl_admin_kat_costumer/hapus";
				$route['gl-admin-kategori-pasien-hapus/(:any)'] = 'C_gl_admin_kat_costumer/hapus';
				
				$route['gl-admin-kategori-pasien-cek'] = "C_gl_admin_kat_costumer/cek_kat_costumer";
				$route['gl-admin-kategori-pasien-cek/(:any)'] = 'C_gl_admin_kat_costumer/cek_kat_costumer';
				
			//KATEGORI PASIEN
			
			//PASIEN
				$route['gl-admin-pasien'] = "C_gl_admin_costumer/index";
				$route['gl-admin-pasien/(:any)'] = 'C_gl_admin_costumer/index';
				
				$route['gl-admin-pasien-service'] = "C_gl_admin_costumer_service/index";
				$route['gl-admin-pasien-service/(:any)'] = 'C_gl_admin_costumer_service/index';
				
				$route['gl-admin-pasien-service-kirim-sms'] = "C_gl_admin_costumer_service/kirim_sms";
				$route['gl-admin-pasien-service-kirim-sms/(:any)'] = 'C_gl_admin_costumer_service/kirim_sms';
				
				$route['gl-admin-pasien-detail'] = "C_gl_admin_costumer/detail";
				$route['gl-admin-pasien-detail/(:any)'] = 'C_gl_admin_costumer/detail';
				$route['gl-admin-pasien-detail/(:any)/(:any)'] = 'C_gl_admin_costumer/detail';
				
				$route['gl-admin-pasien-simpan'] = "C_gl_admin_costumer/simpan";
				$route['gl-admin-pasien-simpan/(:any)'] = 'C_gl_admin_costumer/simpan';
				
				$route['gl-admin-pasien-hapus'] = "C_gl_admin_costumer/hapus";
				$route['gl-admin-pasien-hapus/(:any)'] = 'C_gl_admin_costumer/hapus';
				
				$route['gl-admin-pasien-cek'] = "C_gl_admin_costumer/cek_costumer";
				$route['gl-admin-pasien-cek/(:any)'] = 'C_gl_admin_costumer/cek_costumer';
				
				$route['gl-admin-pasien-cek-nama'] = "C_gl_admin_costumer/cek_costumer_by_nama";
				$route['gl-admin-pasien-cek-nama(:any)'] = 'C_gl_admin_costumer/cek_costumer_by_nama';
				
				$route['gl-admin-pasien-cek-default'] = "C_gl_admin_costumer/cek_isDefault";
				$route['gl-admin-pasien-cek-default/(:any)'] = 'C_gl_admin_costumer/cek_isDefault';
				
				$route['gl-admin-pasien-upgrade'] = "C_gl_admin_costumer/view_upgrade";
				$route['gl-admin-pasien-upgrade/(:any)'] = 'C_gl_admin_costumer/view_upgrade';
				$route['gl-admin-pasien-upgrade/(:any)/(:any)'] = 'C_gl_admin_costumer/view_upgrade';
				
				$route['gl-admin-pasien-upgrade-simpan'] = "C_gl_admin_costumer/simpan_upgrade_member";
				$route['gl-admin-pasien-upgrade-simpan/(:any)'] = 'C_gl_admin_costumer/simpan_upgrade_member';
				$route['gl-admin-pasien-upgrade-simpan/(:any)/(:any)'] = 'C_gl_admin_costumer/simpan_upgrade_member';
				
				$route['gl-admin-pasien-upgrade-hapus'] = "C_gl_admin_costumer/hapus_upgrade_member";
				$route['gl-admin-pasien-upgrade-hapus/(:any)'] = 'C_gl_admin_costumer/hapus_upgrade_member';
				$route['gl-admin-pasien-upgrade-hapus/(:any)/(:any)'] = 'C_gl_admin_costumer/hapus_upgrade_member';
			//PASIEN
		//PASIEN
		
		//SUPPLIER
			//KATEGORI SUPPLIER
				$route['gl-admin-kategori-supplier'] = "C_gl_admin_kat_supplier/index";
				$route['gl-admin-kategori-supplier/(:any)'] = 'C_gl_admin_kat_supplier/index';
				
				$route['gl-admin-kategori-supplier-simpan'] = "C_gl_admin_kat_supplier/simpan";
				$route['gl-admin-kategori-supplier-simpan/(:any)'] = 'C_gl_admin_kat_supplier/simpan';
				
				$route['gl-admin-kategori-supplier-hapus'] = "C_gl_admin_kat_supplier/hapus";
				$route['gl-admin-kategori-supplier-hapus/(:any)'] = 'C_gl_admin_kat_supplier/hapus';
				
				$route['gl-admin-kategori-supplier-cek'] = "C_gl_admin_kat_supplier/cek_kat_supplier";
				$route['gl-admin-kategori-supplier-cek/(:any)'] = 'C_gl_admin_kat_supplier/cek_kat_supplier';
			//KATEGORI SUPPLIER
			
			//SUPPLIER
				$route['gl-admin-supplier'] = "C_gl_admin_supplier/index";
				$route['gl-admin-supplier/(:any)'] = 'C_gl_admin_supplier/index';
				
				$route['gl-admin-supplier-simpan'] = "C_gl_admin_supplier/simpan";
				$route['gl-admin-supplier-simpan/(:any)'] = 'C_gl_admin_supplier/simpan';
				
				$route['gl-admin-supplier-hapus'] = "C_gl_admin_supplier/hapus";
				$route['gl-admin-supplier-hapus/(:any)'] = 'C_gl_admin_supplier/hapus';
				
				$route['gl-admin-supplier-cek'] = "C_gl_admin_supplier/cek_supplier";
				$route['gl-admin-supplier-cek/(:any)'] = 'C_gl_admin_supplier/cek_supplier';
				
				$route['gl-admin-supplier-alias-produk'] = "C_gl_admin_supplier/view_alias_produk_supplier";
				$route['gl-admin-supplier-alias-produk/(:any)'] = 'C_gl_admin_supplier/view_alias_produk_supplier';
				$route['gl-admin-supplier-alias-produk/(:any)/(:any)'] = 'C_gl_admin_supplier/view_alias_produk_supplier';
				
				$route['gl-admin-supplier-alias-produk-simpan'] = "C_gl_admin_supplier/alias_produk_supplier_simpan";
				$route['gl-admin-supplier-alias-produk-simpan/(:any)'] = 'C_gl_admin_supplier/alias_produk_supplier_simpan';
				$route['gl-admin-supplier-alias-produk-simpan/(:any)/(:any)'] = 'C_gl_admin_supplier/alias_produk_supplier_simpan';
				
				$route['gl-admin-supplier-alias-produk-hapus'] = "C_gl_admin_supplier/alias_produk_supplier_hapus";
				$route['gl-admin-supplier-alias-produk-hapus/(:any)'] = 'C_gl_admin_supplier/alias_produk_supplier_hapus';
				$route['gl-admin-supplier-alias-produk-hapus/(:any)/(:any)'] = 'C_gl_admin_supplier/alias_produk_supplier_hapus';
			//SUPPLIER
		//SUPPLIER
		
		//DATA BANK
			$route['gl-bank'] = "C_gl_admin_bank/index";
			$route['gl-bank/(:any)'] = 'C_gl_admin_bank/index';
			
			$route['gl-bank-simpan'] = "C_gl_admin_bank/simpan";
			$route['gl-bank-simpan/(:any)'] = 'C_gl_admin_bank/simpan';
			
			$route['gl-bank-hapus'] = "C_gl_admin_bank/hapus";
			$route['gl-bank-hapus/(:any)'] = 'C_gl_admin_bank/hapus';
			
			$route['gl-bank-cek'] = "C_gl_admin_bank/cek_bank";
			$route['gl-bank-cek/(:any)'] = 'C_gl_admin_bank/cek_bank';
		//DATA BANK
		
		//KODE AKUNTANSI
			$route['gl-kode-akuntansi'] = "C_gl_admin_kode_akun/index";
			$route['gl-kode-akuntansi/(:any)'] = 'C_gl_admin_kode_akun/index';
			
			$route['gl-kode-akuntansi-simpan'] = "C_gl_admin_kode_akun/simpan";
			$route['gl-kode-akuntansi-simpan/(:any)'] = 'C_gl_admin_kode_akun/simpan';
			
			$route['gl-kode-akuntansi-hapus'] = "C_gl_admin_kode_akun/hapus";
			$route['gl-kode-akuntansi-hapus/(:any)'] = 'C_gl_admin_kode_akun/hapus';
			
			$route['gl-kode-akuntansi-cek'] = "C_gl_admin_kode_akun/cek_kode_akun";
			$route['gl-kode-akuntansi-cek/(:any)'] = 'C_gl_admin_kode_akun/cek_kode_akun';
		//KODE AKUNTANSI
		
		//KODE PROGRAM
			$route['gl-kode-program'] = "C_gl_admin_kode_program/index";
			$route['gl-kode-program/(:any)'] = 'C_gl_admin_kode_program/index';
			
			$route['gl-kode-program-simpan'] = "C_gl_admin_kode_program/simpan";
			$route['gl-kode-program-simpan/(:any)'] = 'C_gl_admin_kode_program/simpan';
			
			$route['gl-kode-program-hapus'] = "C_gl_admin_kode_program/hapus";
			$route['gl-kode-program-hapus/(:any)'] = 'C_gl_admin_kode_program/hapus';
			
			$route['gl-kode-program-cek'] = "C_gl_admin_kode_program/cek_kode_program";
			$route['gl-kode-program-cek/(:any)'] = 'C_gl_admin_kode_program/cek_kode_program';
		//KODE PROGRAM
		
		//ASSETS
			//KATEGORI ASSETS
				$route['gl-admin-kategori-assets'] = "C_gl_admin_kat_assets/index";
				$route['gl-admin-kategori-assets/(:any)'] = 'C_gl_admin_kat_assets/index';
				
				$route['gl-admin-kategori-assets-simpan'] = "C_gl_admin_kat_assets/simpan";
				$route['gl-admin-kategori-assets-simpan/(:any)'] = 'C_gl_admin_kat_assets/simpan';
				
				$route['gl-admin-kategori-assets-hapus'] = "C_gl_admin_kat_assets/hapus";
				$route['gl-admin-kategori-assets-hapus/(:any)'] = 'C_gl_admin_kat_assets/hapus';
				
				$route['gl-admin-kategori-assets-cek'] = "C_gl_admin_kat_assets/cek_kat_assets";
				$route['gl-admin-kategori-assets-cek/(:any)'] = 'C_gl_admin_kat_assets/cek_kat_assets';
			//KATEGORI ASSETS
			
			//ASSTES/PINJAMAN
				$route['gl-admin-assets-pinjaman'] = "C_gl_admin_d_assets/index";
				$route['gl-admin-assets-pinjaman/(:any)'] = 'C_gl_admin_d_assets/index';
				
				$route['gl-admin-assets-pinjaman-simpan'] = "C_gl_admin_d_assets/simpan";
				$route['gl-admin-assets-pinjaman-simpan/(:any)'] = 'C_gl_admin_d_assets/simpan';
				
				$route['gl-admin-assets-pinjaman-hapus'] = "C_gl_admin_d_assets/hapus";
				$route['gl-admin-assets-pinjaman-hapus/(:any)'] = 'C_gl_admin_d_assets/hapus';
				
				$route['gl-admin-assets-pinjaman-cek'] = "C_gl_admin_d_assets/cek_d_assets";
				$route['gl-admin-assets-pinjaman-cek/(:any)'] = 'C_gl_admin_d_assets/cek_d_assets';
			//ASSTES/PINJAMAN
			
			//RUANGAN, GEDUNG ATAU TEMPAT PENYIMPANAN BARANG
				$route['gl-admin-ruangan-tempat-penyimpanan-barang'] = "C_gl_admin_gedung/index";
				$route['gl-admin-ruangan-tempat-penyimpanan-barang/(:any)'] = 'C_gl_admin_gedung/index';
				
				$route['gl-admin-ruangan-tempat-penyimpanan-barang-simpan'] = "C_gl_admin_gedung/simpan";
				$route['gl-admin-ruangan-tempat-penyimpanan-barang-simpan/(:any)'] = 'C_gl_admin_gedung/simpan';
				
				$route['gl-admin-ruangan-tempat-penyimpanan-barang-hapus'] = "C_gl_admin_gedung/hapus";
				$route['gl-admin-ruangan-tempat-penyimpanan-barang-hapus/(:any)'] = 'C_gl_admin_gedung/hapus';
				
				$route['gl-admin-ruangan-tempat-penyimpanan-barang-cek'] = "C_gl_admin_gedung/cek_gedung";
				$route['gl-admin-ruangan-tempat-penyimpanan-barang-cek/(:any)'] = 'C_gl_admin_gedung/cek_gedung';
			//RUANGAN, GEDUNG ATAU TEMPAT PENYIMPANAN BARANG
			
		//ASSETS
		
		//PENYAKIT
			$route['gl-admin-penyakit'] = "C_gl_admin_penyakit/index";
			$route['gl-admin-penyakit/(:any)'] = 'C_gl_admin_penyakit/index';
			
			$route['gl-admin-penyakit-simpan'] = "C_gl_admin_penyakit/simpan";
			$route['gl-admin-penyakit-simpan/(:any)'] = 'C_gl_admin_penyakit/simpan';
			
			$route['gl-admin-penyakit-hapus'] = "C_gl_admin_penyakit/hapus";
			$route['gl-admin-penyakit-hapus/(:any)'] = 'C_gl_admin_penyakit/hapus';
			
			$route['gl-admin-penyakit-cek'] = "C_gl_admin_penyakit/cek_penyakit";
			$route['gl-admin-penyakit-cek/(:any)'] = 'C_gl_admin_penyakit/cek_penyakit';
		//PENYAKIT
		
		//PENYEDIA ASURANSI
			$route['gl-admin-penyedia-asuransi'] = "C_gl_admin_penyedia_asuransi/index";
			$route['gl-admin-penyedia-asuransi/(:any)'] = 'C_gl_admin_penyedia_asuransi/index';
			
			$route['gl-admin-penyedia-asuransi-simpan'] = "C_gl_admin_penyedia_asuransi/simpan";
			$route['gl-admin-penyedia-asuransi-simpan/(:any)'] = 'C_gl_admin_penyedia_asuransi/simpan';
			
			$route['gl-admin-penyedia-asuransi-hapus'] = "C_gl_admin_penyedia_asuransi/hapus";
			$route['gl-admin-penyedia-asuransi-hapus/(:any)'] = 'C_gl_admin_penyedia_asuransi/hapus';
			
			$route['gl-admin-penyedia-asuransi-cek'] = "C_gl_admin_penyedia_asuransi/cek_penyedia_asuransi";
			$route['gl-admin-penyedia-asuransi-cek/(:any)'] = 'C_gl_admin_penyedia_asuransi/cek_penyedia_asuransi';
		//PENYEDIA ASURANSI
		
		//MEDIA TRANSAKSI
			$route['gl-admin-media-transaksi'] = "C_gl_admin_media_transaksi/index";
			$route['gl-admin-media-transaksi/(:any)'] = 'C_gl_admin_media_transaksi/index';
			
			$route['gl-admin-media-transaksi-simpan'] = "C_gl_admin_media_transaksi/simpan";
			$route['gl-admin-media-transaksi-simpan/(:any)'] = 'C_gl_admin_media_transaksi/simpan';
			
			$route['gl-admin-media-transaksi-hapus'] = "C_gl_admin_media_transaksi/hapus";
			$route['gl-admin-media-transaksi-hapus/(:any)'] = 'C_gl_admin_media_transaksi/hapus';
			
			$route['gl-admin-media-transaksi-cek-media'] = "C_gl_admin_media_transaksi/cek_media_transaksi";
			$route['gl-admin-media-transaksi-cek-media/(:any)'] = 'C_gl_admin_media_transaksi/cek_media_transaksi';
			
			$route['gl-admin-media-transaksi-set-default'] = "C_gl_admin_media_transaksi/cek_isDefault";
			$route['gl-admin-media-transaksi-set-default/(:any)'] = 'C_gl_admin_media_transaksi/cek_isDefault';
		//MEDIA TRANSAKSI
	//BASIS DATA
	
	//PENGATURAN APLIKASI
		$route['gl-admin-pengaturan-aplikasi'] = "C_gl_admin_pengaturan/index";
		$route['gl-admin-pengaturan-aplikasi/(:any)'] = 'C_gl_admin_pengaturan/index';
		
		$route['gl-admin-pengaturan-aplikasi-update'] = "C_gl_admin_pengaturan/updatePengaturan";
		$route['gl-admin-pengaturan-aplikasi-update/(:any)'] = 'C_gl_admin_pengaturan/updatePengaturan';
		
		$route['gl-admin-pengaturan-aplikasi-backup-database'] = "C_gl_admin_pengaturan/backup_database";
		$route['gl-admin-pengaturan-aplikasi-backup-database/(:any)'] = 'C_gl_admin_pengaturan/backup_database';
	//PENGATURAN APLIKASI
	
	//PENGATURAN OPERASIONAL
		//SATUAN KONVERSI
			$route['gl-admin-satuan-konversi'] = "C_gl_admin_satuan_konversi/index";
			$route['gl-admin-satuan-konversi/(:any)'] = 'C_gl_admin_satuan_konversi/index';
			
			$route['gl-admin-satuan-konversi-simpan'] = "C_gl_admin_satuan_konversi/simpan";
			$route['gl-admin-satuan-konversi-simpan/(:any)'] = 'C_gl_admin_satuan_konversi/simpan';
		//SATUAN KONVERSI
		
		//HARGA DASAR SATUAN
			$route['gl-admin-harga-dasar-satuan'] = "C_gl_admin_harga_dasar_satuan/index";
			$route['gl-admin-harga-dasar-satuan/(:any)'] = 'C_gl_admin_harga_dasar_satuan/index';
			
			$route['gl-admin-harga-dasar-satuan-simpan'] = "C_gl_admin_harga_dasar_satuan/simpan";
			$route['gl-admin-harga-dasar-satuan-simpan/(:any)'] = 'C_gl_admin_harga_dasar_satuan/simpan';
		//HARGA DASAR SATUAN
		
		//HARGA PASIEN
			$route['gl-admin-harga-pasien'] = "C_gl_admin_harga_pasien/index";
			$route['gl-admin-harga-pasien/(:any)'] = 'C_gl_admin_harga_pasien/index';
			
			$route['gl-admin-harga-pasien-simpan'] = "C_gl_admin_harga_pasien/simpan";
			$route['gl-admin-harga-pasien-simpan/(:any)'] = 'C_gl_admin_harga_pasien/simpan';
		//HARGA PASIEN
		
		//DISKON & PROMO
			//DISKON & PROMO
				$route['gl-admin-diskon-promo'] = "C_gl_admin_promo_diskon/index";
				$route['gl-admin-diskon-promo/(:any)'] = 'C_gl_admin_promo_diskon/index';
				
				$route['gl-admin-diskon-promo-cek'] = "C_gl_admin_promo_diskon/cek_h_diskon";
				$route['gl-admin-diskon-promo-cek/(:any)'] = 'C_gl_admin_promo_diskon/cek_h_diskon';
				
				$route['gl-admin-diskon-promo-simpan'] = "C_gl_admin_promo_diskon/simpan";
				$route['gl-admin-diskon-promo-simpan/(:any)'] = 'C_gl_admin_promo_diskon/simpan';
				
				$route['gl-admin-diskon-promo-hapus'] = "C_gl_admin_promo_diskon/hapus";
				$route['gl-admin-diskon-promo-hapus/(:any)'] = 'C_gl_admin_promo_diskon/hapus';
				
				$route['gl-admin-diskon-promo-list-produk'] = "C_gl_admin_promo_diskon/list_produk";
				$route['gl-admin-diskon-promo-list-produk/(:any)'] = 'C_gl_admin_promo_diskon/list_produk';
				
				$route['gl-admin-diskon-promo-list-satuan-produk'] = "C_gl_admin_promo_diskon/tampilkan_satuan_produk";
				$route['gl-admin-diskon-promo-list-satuan-produk/(:any)'] = 'C_gl_admin_promo_diskon/tampilkan_satuan_produk';
				
				$route['gl-admin-diskon-promo-list-hari-aktif'] = "C_gl_admin_promo_diskon/list_hari_diskon";
				$route['gl-admin-diskon-promo-list-hari-aktif/(:any)'] = 'C_gl_admin_promo_diskon/list_hari_diskon';
				
				$route['gl-admin-diskon-promo-list-hari-aktif-simpan'] = "C_gl_admin_promo_diskon/cek_isNotActiveHari";
				$route['gl-admin-diskon-promo-list-hari-aktif-simpan/(:any)'] = 'C_gl_admin_promo_diskon/cek_isNotActiveHari';
				
				//DETAIL DISKON & PROMO
					$route['gl-admin-produks-diskon-promo'] = "C_gl_admin_d_diskon/index";
					$route['gl-admin-produks-diskon-promo/(:any)'] = 'C_gl_admin_d_diskon/index';
					
					$route['gl-admin-produks-diskon-promo-simpan'] = "C_gl_admin_d_diskon/simpan";
					$route['gl-admin-produks-diskon-promo-simpan/(:any)'] = 'C_gl_admin_d_diskon/simpan';
					
					$route['gl-admin-produks-diskon-promo-hapus'] = "C_gl_admin_d_diskon/hapus";
					$route['gl-admin-produks-diskon-promo-hapus/(:any)'] = 'C_gl_admin_d_diskon/hapus';
					$route['gl-admin-produks-diskon-promo-hapus/(:any)/(:any)'] = 'C_gl_admin_d_diskon/hapus';
					
					$route['gl-admin-produks-diskon-promo-list-produk'] = "C_gl_admin_d_diskon/list_produk";
					$route['gl-admin-produks-diskon-promo-list-produk/(:any)'] = 'C_gl_admin_d_diskon/list_produk';
					
					$route['gl-admin-produks-diskon-promo-list-satuan-produk'] = "C_gl_admin_d_diskon/tampilkan_satuan_produk";
					$route['gl-admin-produks-diskon-promo-list-satuan-produk/(:any)'] = 'C_gl_admin_d_diskon/tampilkan_satuan_produk';
				//DETAIL DISKON & PROMO
			//DISKON & PROMO
			
			//FEE
				$route['gl-admin-pengaturan-fee'] = "C_gl_admin_h_fee/index";
				$route['gl-admin-pengaturan-fee/(:any)'] = 'C_gl_admin_h_fee/index';
				
				$route['gl-admin-pengaturan-fee-cek'] = "C_gl_admin_h_fee/cek_h_fee";
				$route['gl-admin-pengaturan-fee-cek/(:any)'] = 'C_gl_admin_h_fee/cek_h_fee';
				
				$route['gl-admin-pengaturan-fee-simpan'] = "C_gl_admin_h_fee/simpan";
				$route['gl-admin-pengaturan-fee-simpan/(:any)'] = 'C_gl_admin_h_fee/simpan';
				
				$route['gl-admin-pengaturan-fee-hapus'] = "C_gl_admin_h_fee/hapus";
				$route['gl-admin-pengaturan-fee-hapus/(:any)'] = 'C_gl_admin_h_fee/hapus';
				
				$route['gl-admin-pengaturan-fee-list-combo-posisi-fee'] = "C_gl_admin_h_fee/list_kat_h_fee_ditampilkan_di_combobox";
				$route['gl-admin-pengaturan-fee-list-combo-posisi-fee/(:any)'] = 'C_gl_admin_h_fee/list_kat_h_fee_ditampilkan_di_combobox';
			//FEE
		//DISKON & PROMO
		
		//UPLOAD EXCEL
			$route['gl-admin-pengaturan-upload-excel'] = "C_gl_admin_upload_excel/index";
			$route['gl-admin-pengaturan-upload-excel/(:any)'] = 'C_gl_admin_upload_excel/index';
			
			$route['gl-admin-pengaturan-upload-excel-proses-pasien'] = "C_gl_admin_upload_excel/proses_excel_pasien";
			$route['gl-admin-pengaturan-upload-excel-proses-pasien/(:any)'] = 'C_gl_admin_upload_excel/proses_excel_pasien';
			
			$route['gl-admin-pengaturan-upload-excel-produk-hpp'] = "C_gl_admin_upload_excel/view_upload_produk_hpp";
			$route['gl-admin-pengaturan-upload-excel-produk-hpp/(:any)'] = 'C_gl_admin_upload_excel/view_upload_produk_hpp';
			
			$route['gl-admin-pengaturan-upload-excel-produk-hpp-proses'] = "C_gl_admin_upload_excel/proses_upload_produk_hpp";
			$route['gl-admin-pengaturan-upload-excel-produk-hpp-proses/(:any)'] = 'C_gl_admin_upload_excel/proses_upload_produk_hpp';
			
			$route['gl-admin-pengaturan-upload-excel-produk-pasien'] = "C_gl_admin_upload_excel/view_upload_produk_harga_jaul";
			$route['gl-admin-pengaturan-upload-excel-produk-pasien/(:any)'] = 'C_gl_admin_upload_excel/view_upload_produk_harga_jaul';
			
			$route['gl-admin-pengaturan-upload-excel-produk-pasien-proses'] = "C_gl_admin_upload_excel/proses_upload_produk_harga_jaul";
			$route['gl-admin-pengaturan-upload-excel-produk-pasien-proses/(:any)'] = 'C_gl_admin_upload_excel/proses_upload_produk_harga_jaul';
			
			$route['gl-admin-pengaturan-upload-excel-supplier'] = "C_gl_admin_upload_excel/view_upload_supplier";
			$route['gl-admin-pengaturan-upload-excel-supplier/(:any)'] = 'C_gl_admin_upload_excel/view_upload_supplier';
			
			$route['gl-admin-pengaturan-upload-excel-supplier-proses'] = "C_gl_admin_upload_excel/proses_upload_supplier";
			$route['gl-admin-pengaturan-upload-excel-supplier-proses/(:any)'] = 'C_gl_admin_upload_excel/proses_upload_supplier';
			
			$route['gl-admin-pengaturan-upload-excel-stock'] = "C_gl_admin_upload_excel/view_upload_stock_produk";
			$route['gl-admin-pengaturan-upload-excel-stock/(:any)'] = 'C_gl_admin_upload_excel/view_upload_stock_produk';
			
			$route['gl-admin-pengaturan-upload-excel-stock-sample'] = "C_gl_admin_upload_excel/view_excel_contoh_stock";
			$route['gl-admin-pengaturan-upload-excel-stock-sample/(:any)'] = 'C_gl_admin_upload_excel/view_excel_contoh_stock';
			
			$route['gl-admin-pengaturan-upload-excel-stock-proses'] = "C_gl_admin_upload_excel/proses_upload_stock_produk";
			$route['gl-admin-pengaturan-upload-excel-stock-proses/(:any)'] = 'C_gl_admin_upload_excel/proses_upload_stock_produk';
			
			$route['gl-admin-pengaturan-upload-excel-stock-proses-selesai'] = "C_gl_admin_upload_excel/proses_2_upload_stock_produk";
			$route['gl-admin-pengaturan-upload-excel-stock-proses-selesai/(:any)'] = 'C_gl_admin_upload_excel/proses_2_upload_stock_produk';
			
			$route['gl-admin-pengaturan-upload-excel-produk-hpp-jasa'] = "C_gl_admin_upload_excel/view_upload_kebutuhan_jasa";
			$route['gl-admin-pengaturan-upload-excel-produk-hpp-jasa/(:any)'] = 'C_gl_admin_upload_excel/view_upload_kebutuhan_jasa';
			
			$route['gl-admin-pengaturan-upload-excel-produk-hpp-jasa-proses'] = "C_gl_admin_upload_excel/proses_upload_kebutuhan_jasa";
			$route['gl-admin-pengaturan-upload-excel-produk-hpp-jasa-proses/(:any)'] = 'C_gl_admin_upload_excel/proses_upload_kebutuhan_jasa';
		//UPLOAD EXCEL
	//PENGATURAN OPERASIONAL
	
	//TRANSAKSI
		//PEMBELIAN
			$route['gl-admin-purchase-order'] = "C_gl_admin_h_pembelian/index";
			$route['gl-admin-purchase-order/(:any)'] = 'C_gl_admin_h_pembelian/index';
			
			$route['gl-admin-purchase-order-load-d-pembelian'] = "C_gl_admin_h_pembelian/list_d_pembelian";
			$route['gl-admin-purchase-order-load-d-pembelian/(:any)'] = 'C_gl_admin_h_pembelian/list_d_pembelian';
			
			$route['gl-admin-purchase-order-list-produk'] = "C_gl_admin_h_pembelian/list_produk";
			$route['gl-admin-purchase-order-list-produk/(:any)'] = 'C_gl_admin_h_pembelian/list_produk';
			
			$route['gl-admin-purchase-order-simpan-h-awal'] = "C_gl_admin_h_pembelian/simpan_h_pembelian_awal";
			$route['gl-admin-purchase-order-simpan-h-awal/(:any)'] = 'C_gl_admin_h_pembelian/simpan_h_pembelian_awal';
			
			$route['gl-admin-purchase-order-ubah-h-awal'] = "C_gl_admin_h_pembelian/ubah_h_pembelian_awal";
			$route['gl-admin-purchase-order-ubah-h-awal/(:any)'] = 'C_gl_admin_h_pembelian/ubah_h_pembelian_awal';
			
			$route['gl-admin-purchase-order-simpan-d-awal'] = "C_gl_admin_h_pembelian/simpan_d_pembelian_awal";
			$route['gl-admin-purchase-order-simpan-d-awal/(:any)'] = 'C_gl_admin_h_pembelian/simpan_d_pembelian_awal';
			
			$route['gl-admin-purchase-order-combo-list-satuan'] = 'C_gl_admin_h_pembelian/combo_list_satuan';
			$route['gl-admin-purchase-order-combo-list-satuan/(:any)'] = 'C_gl_admin_h_pembelian/combo_list_satuan';
			
			$route['gl-admin-purchase-order-combo-satuan-harga'] = 'C_gl_admin_h_pembelian/combo_satuan_harga';
			$route['gl-admin-purchase-order-combo-satuan-harga/(:any)'] = 'C_gl_admin_h_pembelian/combo_satuan_harga';
			
			$route['gl-admin-purchase-order-ubah-d-pembelian'] = 'C_gl_admin_h_pembelian/ubah_d_pembelian';
			$route['gl-admin-purchase-order-ubah-d-pembelian/(:any)'] = 'C_gl_admin_h_pembelian/ubah_d_pembelian';
			
			$route['gl-admin-purchase-order-list-supplier'] = 'C_gl_admin_h_pembelian/list_supplier';
			$route['gl-admin-purchase-order-list-supplier/(:any)'] = 'C_gl_admin_h_pembelian/list_supplier';
			
			$route['gl-admin-purchase-order-simpan-bayar'] = 'C_gl_admin_h_pembelian/simpan_d_pembelian_bayar_awal';
			$route['gl-admin-purchase-order-simpan-bayar/(:any)'] = 'C_gl_admin_h_pembelian/simpan_d_pembelian_bayar_awal';
			
			$route['gl-admin-purchase-order-ubah-bayar'] = 'C_gl_admin_h_pembelian/ubah_d_pembelian_bayar_awal';
			$route['gl-admin-purchase-order-ubah-bayar/(:any)'] = 'C_gl_admin_h_pembelian/ubah_d_pembelian_bayar_awal';
			
			$route['gl-admin-purchase-order-batal'] = 'C_gl_admin_h_pembelian/batal';
			$route['gl-admin-purchase-order-batal/(:any)'] = 'C_gl_admin_h_pembelian/batal';
			
			$route['gl-admin-purchase-order-list-pembelian'] = 'C_gl_admin_h_pembelian/list_h_pembelian';
			$route['gl-admin-purchase-order-list-pembelian/(:any)'] = 'C_gl_admin_h_pembelian/list_h_pembelian';
			
			$route['gl-admin-purchase-order-hapus-all'] = 'C_gl_admin_h_pembelian/hapus_all';
			$route['gl-admin-purchase-order-hapus-all/(:any)'] = 'C_gl_admin_h_pembelian/hapus_all';
			
			$route['gl-admin-purchase-order-print-po'] = 'C_gl_admin_h_pembelian/print_po';
			$route['gl-admin-purchase-order-print-po/(:any)'] = 'C_gl_admin_h_pembelian/print_po';
		//PEMBELIAN
		
		//PENERIMAAN PRODUK
			$route['gl-admin-purchase-order-terima'] = "C_gl_admin_h_pembelian/list_h_pembelian_penerimaan";
			$route['gl-admin-purchase-order-terima/(:any)'] = 'C_gl_admin_h_pembelian/list_h_pembelian_penerimaan';
			
			$route['gl-admin-purchase-order-terima-tgl-exp'] = "C_gl_admin_h_pembelian/list_tgl_expired";
			$route['gl-admin-purchase-order-terima-tgl-exp/(:any)'] = 'C_gl_admin_h_pembelian/list_tgl_expired';
			
			$route['gl-admin-purchase-order-terima-surat-jalan'] = "C_gl_admin_h_penerimaan/index";
			$route['gl-admin-purchase-order-terima-surat-jalan/(:any)'] = 'C_gl_admin_h_penerimaan/index';
			
			$route['gl-admin-purchase-order-terima-surat-jalan-simpan'] = "C_gl_admin_h_penerimaan/simpan";
			$route['gl-admin-purchase-order-terima-surat-jalan-simpan/(:any)'] = 'C_gl_admin_h_penerimaan/simpan';
			
			$route['gl-admin-purchase-order-terima-surat-jalan-hapus'] = "C_gl_admin_h_penerimaan/hapus";
			$route['gl-admin-purchase-order-terima-surat-jalan-hapus/(:any)'] = 'C_gl_admin_h_penerimaan/hapus';
			$route['gl-admin-purchase-order-terima-surat-jalan-hapus/(:any)/(:any)'] = 'C_gl_admin_h_penerimaan/hapus';
			
			$route['gl-admin-purchase-order-terima-surat-jalan-list-data-produk'] = "C_gl_admin_h_penerimaan/list_produk_terima";
			$route['gl-admin-purchase-order-terima-surat-jalan-list-data-produk/(:any)'] = 'C_gl_admin_h_penerimaan/list_produk_terima';
			
			$route['gl-admin-purchase-order-terima-surat-jalan-simpan-terima-produk'] = "C_gl_admin_h_penerimaan/simpan_d_penerimaan";
			$route['gl-admin-purchase-order-terima-surat-jalan-simpan-terima-produk/(:any)'] = 'C_gl_admin_h_penerimaan/simpan_d_penerimaan';
			
			$route['gl-admin-purchase-order-terima-detail-produk'] = "C_gl_admin_h_penerimaan/view_d_penerimaan";
			$route['gl-admin-purchase-order-terima-detail-produk/(:any)'] = 'C_gl_admin_h_penerimaan/view_d_penerimaan';
			
			$route['gl-admin-purchase-order-terima-surat-jalan-list-data-produk-terima'] = "C_gl_admin_d_penerimaan/index";
			$route['gl-admin-purchase-order-terima-surat-jalan-list-data-produk-terima/(:any)'] = "C_gl_admin_d_penerimaan/index";
			
			$route['gl-admin-purchase-order-terima-cek-pusat'] = "C_gl_admin_d_penerimaan/cek_d_penerimaan_dari_pusat";
			$route['gl-admin-purchase-order-terima-cek-pusat/(:any)'] = "C_gl_admin_d_penerimaan/cek_d_penerimaan_dari_pusat";
			
		//PENERIMAAN PRODUK
		
		//PENDAFTARAN PASIEN
			$route['gl-admin-pendaftaran-pasien'] = "C_gl_admin_h_penjualan/index";
			$route['gl-admin-pendaftaran-pasien/(:any)'] = 'C_gl_admin_h_penjualan/index';
			
			$route['gl-admin-pendaftaran-pasien-rekam-medis'] = "C_gl_admin_h_penjualan/rekam_medis";
			$route['gl-admin-pendaftaran-pasien-rekam-medis/(:any)'] = 'C_gl_admin_h_penjualan/rekam_medis';
			$route['gl-admin-pendaftaran-pasien-rekam-medis/(:any)/(:any)'] = 'C_gl_admin_h_penjualan/rekam_medis';
			
			$route['gl-admin-pendaftaran-pasien-rekam-medis-foto'] = "C_gl_admin_h_penjualan/tampilkan_gamar_rekam_medis";
			$route['gl-admin-pendaftaran-pasien-rekam-medis-foto/(:any)'] = 'C_gl_admin_h_penjualan/tampilkan_gamar_rekam_medis';
			
			$route['gl-admin-pendaftaran-pasien-paket'] = "C_gl_admin_h_penjualan/list_h_diskon_paket";
			$route['gl-admin-pendaftaran-pasien-paket/(:any)'] = 'C_gl_admin_h_penjualan/list_h_diskon_paket';
			
			$route['gl-admin-pendaftaran-pasien-list'] = "C_gl_admin_h_penjualan/list_pasien";
			$route['gl-admin-pendaftaran-pasien-list/(:any)'] = 'C_gl_admin_h_penjualan/list_pasien';
			
			$route['gl-admin-pendaftaran-pasien-scan'] = "C_gl_admin_h_penjualan/scan_pasien";
			$route['gl-admin-pendaftaran-pasien-scan/(:any)'] = 'C_gl_admin_h_penjualan/scan_pasien';
			
			$route['gl-admin-pendaftaran-pasien-get-antrian'] = "C_gl_admin_h_penjualan/get_no_antrian";
			$route['gl-admin-pendaftaran-pasien-get-antrian/(:any)'] = 'C_gl_admin_h_penjualan/get_no_antrian';
			
			$route['gl-admin-pendaftaran-dokter-list'] = "C_gl_admin_h_penjualan/list_dokter";
			$route['gl-admin-pendaftaran-dokter-list/(:any)'] = 'C_gl_admin_h_penjualan/list_dokter';
			
			$route['gl-admin-pendaftaran-dokter-list-tersedia'] = "C_gl_admin_h_penjualan/list_dokter_tersedia";
			$route['gl-admin-pendaftaran-dokter-list-tersedia/(:any)'] = 'C_gl_admin_h_penjualan/list_dokter_tersedia';
			
			$route['gl-admin-pendaftaran-therapist-list'] = "C_gl_admin_h_penjualan/list_therapist";
			$route['gl-admin-pendaftaran-therapist-list/(:any)'] = 'C_gl_admin_h_penjualan/list_therapist';
			
			$route['gl-admin-pendaftaran-therapist-list-2'] = "C_gl_admin_h_penjualan/list_therapist2";
			$route['gl-admin-pendaftaran-therapist-list-2/(:any)'] = 'C_gl_admin_h_penjualan/list_therapist2';
			
			$route['gl-admin-pendaftaran-print-antrian'] = "C_gl_admin_h_penjualan/print_antrian";
			$route['gl-admin-pendaftaran-print-antrian/(:any)'] = 'C_gl_admin_h_penjualan/print_antrian';
			
			$route['gl-admin-pendaftaran-print-faktur'] = "C_gl_admin_h_penjualan/print_faktur";
			$route['gl-admin-pendaftaran-print-faktur/(:any)'] = 'C_gl_admin_h_penjualan/print_faktur';
			
			$route['gl-admin-pendaftaran-print-surat-jalan'] = "C_gl_admin_h_penjualan/print_surat_jalan";
			$route['gl-admin-pendaftaran-print-surat-jalan/(:any)'] = 'C_gl_admin_h_penjualan/print_surat_jalan';
			
			$route['gl-admin-pendaftaran-print-surat-jalan-cek-pusat'] = "C_gl_admin_h_penjualan/print_surat_jalan_cek_dari_pusat";
			$route['gl-admin-pendaftaran-print-surat-jalan-cek-pusat/(:any)'] = "C_gl_admin_h_penjualan/print_surat_jalan_cek_dari_pusat";
			
			$route['gl-admin-pendaftaran-print-antrian-ulang'] = "C_gl_admin_h_penjualan/print_ulang";
			$route['gl-admin-pendaftaran-print-antrian-ulang/(:any)'] = 'C_gl_admin_h_penjualan/print_ulang';
			
			$route['gl-admin-pendaftaran-pendaftaran-list'] = "C_gl_admin_h_penjualan/list_pendaftaran";
			$route['gl-admin-pendaftaran-pendaftaran-list/(:any)'] = 'C_gl_admin_h_penjualan/list_pendaftaran';
			
			$route['gl-admin-pendaftaran-pasien-hapus'] = "C_gl_admin_h_penjualan/hapus_pendaftaran";
			$route['gl-admin-pendaftaran-pasien-hapus/(:any)'] = 'C_gl_admin_h_penjualan/hapus_pendaftaran';
		//PENDAFTARAN PASIEN
		
		//PROSES PEMERIKSAAN DOKTER
			$route['gl-admin-pemeriksaan-dokter'] = "C_gl_admin_pedaftaran_proses_dokter/index";
			$route['gl-admin-pemeriksaan-dokter/(:any)'] = 'C_gl_admin_pedaftaran_proses_dokter/index';
			
			$route['gl-admin-pemeriksaan-dokter-ajax-update-antrian'] = "C_gl_admin_pedaftaran_proses_dokter/get_ajax_update_pendaftaran";
			$route['gl-admin-pemeriksaan-dokter-ajax-update-antrian/(:any)'] = 'C_gl_admin_pedaftaran_proses_dokter/get_ajax_update_pendaftaran';
			
			$route['gl-admin-pemeriksaan-dokter-proses'] = "C_gl_admin_pedaftaran_proses_dokter/proses_pendaftaran_by_dokter";
			$route['gl-admin-pemeriksaan-dokter-proses/(:any)'] = 'C_gl_admin_pedaftaran_proses_dokter/proses_pendaftaran_by_dokter';
			
			$route['gl-admin-pemeriksaan-dokter-proses-list-d-penjualan'] = "C_gl_admin_pedaftaran_proses_dokter/list_tampilkan_d_penjualan";
			$route['gl-admin-pemeriksaan-dokter-proses-list-d-penjualan/(:any)'] = 'C_gl_admin_pedaftaran_proses_dokter/list_tampilkan_d_penjualan';
			
			$route['gl-admin-pemeriksaan-dokter-proses-harga-ubah-combo'] = "C_gl_admin_pedaftaran_proses_dokter/combo_satuan_harga";
			$route['gl-admin-pemeriksaan-dokter-proses-harga-ubah-combo/(:any)'] = 'C_gl_admin_pedaftaran_proses_dokter/combo_satuan_harga';
			
			$route['gl-admin-pemeriksaan-dokter-proses-list-produk'] = "C_gl_admin_pedaftaran_proses_dokter/list_produk";
			$route['gl-admin-pemeriksaan-dokter-proses-list-produk/(:any)'] = 'C_gl_admin_pedaftaran_proses_dokter/list_produk';
			
			$route['gl-admin-pemeriksaan-dokter-proses-scan-produk'] = "C_gl_admin_pedaftaran_proses_dokter/scan_produk";
			$route['gl-admin-pemeriksaan-dokter-proses-scan-produk/(:any)'] = 'C_gl_admin_pedaftaran_proses_dokter/scan_produk';
			
			$route['gl-admin-pemeriksaan-dokter-proses-simpan-d-penjualan-awal'] = "C_gl_admin_pedaftaran_proses_dokter/simpan_d_penjualan";
			$route['gl-admin-pemeriksaan-dokter-proses-simpan-d-penjualan-awal/(:any)'] = 'C_gl_admin_pedaftaran_proses_dokter/simpan_d_penjualan';
			
			$route['gl-admin-pemeriksaan-dokter-proses-ubah-d-penjualan'] = "C_gl_admin_pedaftaran_proses_dokter/ubah_d_penjualan";
			$route['gl-admin-pemeriksaan-dokter-proses-ubah-d-penjualan/(:any)'] = 'C_gl_admin_pedaftaran_proses_dokter/ubah_d_penjualan';
			
			$route['gl-admin-pemeriksaan-dokter-proses-diskon-akumulasi'] = "C_gl_admin_pedaftaran_proses_dokter/cek_diskon_akumulasi";
			$route['gl-admin-pemeriksaan-dokter-proses-diskon-akumulasi/(:any)'] = 'C_gl_admin_pedaftaran_proses_dokter/cek_diskon_akumulasi';
			
			$route['gl-admin-pemeriksaan-dokter-proses-selesai-transaksi'] = "C_gl_admin_pedaftaran_proses_dokter/selesai_transaksi";
			$route['gl-admin-pemeriksaan-dokter-proses-selesai-transaksi/(:any)'] = 'C_gl_admin_pedaftaran_proses_dokter/selesai_transaksi';
			
			$route['gl-admin-pemeriksaan-dokter-proses-pilih-paket'] = "C_gl_admin_pedaftaran_proses_dokter/cek_dan_masukan_paket";
			$route['gl-admin-pemeriksaan-dokter-proses-pilih-paket/(:any)'] = 'C_gl_admin_pedaftaran_proses_dokter/cek_dan_masukan_paket';
			
			$route['gl-admin-pemeriksaan-dokter-proses-pilih-media-transaksi'] = "C_gl_admin_pedaftaran_proses_dokter/ubah_h_penjualan_media_transaksi";
			$route['gl-admin-pemeriksaan-dokter-proses-pilih-media-transaksi/(:any)'] = 'C_gl_admin_pedaftaran_proses_dokter/ubah_h_penjualan_media_transaksi';
		//PROSES PEMERIKSAAN DOKTER
		
		//PROSES PEMBAYARAN
			$route['gl-admin-pembayaran'] = "C_gl_admin_pedaftaran_proses_pembayaran/index";
			$route['gl-admin-pembayaran/(:any)'] = 'C_gl_admin_pedaftaran_proses_pembayaran/index';
			
			$route['gl-admin-pemeriksaan-dokter-ajax-update-antrian-pembayaran'] = "C_gl_admin_pedaftaran_proses_pembayaran/get_ajax_update_pendaftaran";
			$route['gl-admin-pemeriksaan-dokter-ajax-update-antrian-pembayaran/(:any)'] = 'C_gl_admin_pedaftaran_proses_pembayaran/get_ajax_update_pendaftaran';
		//PROSES PEMBAYARAN
		
		//PENJUALAN LANGSUNG
			$route['gl-admin-penjualan-langsung'] = "C_gl_admin_penjualan_langsung/index";
			$route['gl-admin-penjualan-langsung/(:any)'] = 'C_gl_admin_penjualan_langsung/index';
			
			$route['gl-admin-penjualan-langsung-proses'] = "C_gl_admin_penjualan_langsung/proses_awal_penjualan_langsung";
			$route['gl-admin-penjualan-langsung-proses/(:any)'] = 'C_gl_admin_penjualan_langsung/proses_awal_penjualan_langsung';
		//PENJUALAN LANGSUNG
		
		//PERAWATN LANJUT
			$route['gl-admin-perawatan-lanjut'] = "C_gl_admin_pedaftaran_proses_pembayaran/perawatan_lanjut";
			$route['gl-admin-perawatan-lanjut/(:any)'] = 'C_gl_admin_pedaftaran_proses_pembayaran/perawatan_lanjut';
			
			$route['gl-admin-perawatan-lanjut-ajax-list'] = "C_gl_admin_pedaftaran_proses_pembayaran/get_ajax_update_perawatan_lanjut";
			$route['gl-admin-perawatan-lanjut-ajax-list/(:any)'] = 'C_gl_admin_pedaftaran_proses_pembayaran/get_ajax_update_perawatan_lanjut';
			
			$route['gl-admin-perawatan-lanjut-selesai'] = "C_gl_admin_pedaftaran_proses_pembayaran/selesai_transaksi";
			$route['gl-admin-perawatan-lanjut-selesai/(:any)'] = 'C_gl_admin_pedaftaran_proses_pembayaran/selesai_transaksi';
		//PERAWATN LANJUT
		
		//STATUS THERAPIST
			$route['gl-admin-status-therapist'] = "C_gl_admin_status_therapist/index";
			$route['gl-admin-status-therapist/(:any)'] = 'C_gl_admin_status_therapist/index';
			
			$route['gl-admin-status-therapist-lits-status'] = "C_gl_admin_status_therapist/list_status_therapist_ajax";
			$route['gl-admin-status-therapist-lits-status/(:any)'] = 'C_gl_admin_status_therapist/list_status_therapist_ajax';
			
			$route['gl-admin-status-dokter'] = "C_gl_admin_status_therapist/status_dokter";
			$route['gl-admin-status-dokter/(:any)'] = 'C_gl_admin_status_therapist/status_dokter';
			
			$route['gl-admin-status-dokter-list-status'] = "C_gl_admin_status_therapist/list_status_dokter_ajax";
			$route['gl-admin-status-dokter-list-status/(:any)'] = 'C_gl_admin_status_therapist/list_status_dokter_ajax';
		//STATUS THERAPIST
		
		//MUTASI OUT
			$route['gl-admin-mutasi-out'] = "C_gl_admin_mutasi_out/index";
			$route['gl-admin-mutasi-out/(:any)'] = 'C_gl_admin_mutasi_out/index';
			
			$route['gl-admin-mutasi-out-list-d-mutasi'] = "C_gl_admin_mutasi_out/list_d_mutasi";
			$route['gl-admin-mutasi-out-list-d-mutasi/(:any)'] = 'C_gl_admin_mutasi_out/list_d_mutasi';
			
			$route['gl-admin-mutasi-out-list-penjualan'] = "C_gl_admin_mutasi_out/list_h_penjualan";
			$route['gl-admin-mutasi-out-list-penjualan/(:any)'] = 'C_gl_admin_mutasi_out/list_h_penjualan';
			
			$route['gl-admin-mutasi-out-list-kantor'] = "C_gl_admin_mutasi_out/list_kantor";
			$route['gl-admin-mutasi-out-list-kantor/(:any)'] = 'C_gl_admin_mutasi_out/list_kantor';
			
			$route['gl-admin-mutasi-out-list-produk'] = "C_gl_admin_mutasi_out/list_produk";
			$route['gl-admin-mutasi-out-list-produk/(:any)'] = 'C_gl_admin_mutasi_out/list_produk';
			
			$route['gl-admin-mutasi-out-simpan-awal'] = "C_gl_admin_mutasi_out/simpan_h_mutasi_awal";
			$route['gl-admin-mutasi-out-simpan-awal/(:any)'] = 'C_gl_admin_mutasi_out/simpan_h_mutasi_awal';
			
			$route['gl-admin-mutasi-out-simpan-d-mutasi'] = "C_gl_admin_mutasi_out/simpan_d_mutasi";
			$route['gl-admin-mutasi-out-simpan-d-mutasi/(:any)'] = 'C_gl_admin_mutasi_out/simpan_d_mutasi';
			
			$route['gl-admin-mutasi-out-ubah-d-mutasi'] = "C_gl_admin_mutasi_out/ubah_d_mutasi";
			$route['gl-admin-mutasi-out-ubah-d-mutasi/(:any)'] = 'C_gl_admin_mutasi_out/ubah_d_mutasi';
			
			$route['gl-admin-mutasi-out-batal'] = "C_gl_admin_mutasi_out/batal";
			$route['gl-admin-mutasi-out-batal/(:any)'] = 'C_gl_admin_mutasi_out/batal';
			
			$route['gl-admin-mutasi-out-selesai'] = "C_gl_admin_mutasi_out/simpan_selesai";
			$route['gl-admin-mutasi-out-selesai/(:any)'] = 'C_gl_admin_mutasi_out/simpan_selesai';
			
			$route['gl-admin-mutasi-out-hapus-all'] = "C_gl_admin_mutasi_out/hapus_all";
			$route['gl-admin-mutasi-out-hapus-all/(:any)'] = 'C_gl_admin_mutasi_out/hapus_all';
			
			$route['gl-admin-mutasi-out-list-h-mutasi'] = "C_gl_admin_mutasi_out/list_h_mutasi";
			$route['gl-admin-mutasi-out-list-h-mutasi/(:any)'] = 'C_gl_admin_mutasi_out/list_h_mutasi';
			
			$route['gl-admin-mutasi-out-print-faktur'] = "C_gl_admin_mutasi_out/print_faktur_mutasi_out";
			$route['gl-admin-mutasi-out-print-faktur/(:any)'] = 'C_gl_admin_mutasi_out/print_faktur_mutasi_out';
			
		//MUTASI OUT
		
		//MUTASI in
			$route['gl-admin-mutasi-in'] = "C_gl_admin_mutasi_in/index";
			$route['gl-admin-mutasi-in/(:any)'] = 'C_gl_admin_mutasi_in/index';
			
			$route['gl-admin-mutasi-in-list-h-mutasi'] = "C_gl_admin_mutasi_in/list_h_mutasi";
			$route['gl-admin-mutasi-in-list-h-mutasi/(:any)'] = 'C_gl_admin_mutasi_in/list_h_mutasi';
		//MUTASI in
		
		
		//RETUR SUPPLIER/PEMBELIAN
			$route['gl-admin-retur-supplier'] = "C_gl_admin_retur_pembelian/index";
			$route['gl-admin-retur-supplier/(:any)'] = 'C_gl_admin_retur_pembelian/index';
			
			$route['gl-admin-retur-supplier-list-pembelian'] = "C_gl_admin_retur_pembelian/list_h_pembelian";
			$route['gl-admin-retur-supplier-list-pembelian/(:any)'] = 'C_gl_admin_retur_pembelian/list_h_pembelian';
			
			$route['gl-admin-retur-supplier-list-supplier'] = "C_gl_admin_retur_pembelian/list_supplier";
			$route['gl-admin-retur-supplier-list-supplier/(:any)'] = 'C_gl_admin_retur_pembelian/list_supplier';
			
			$route['gl-admin-retur-supplier-list-produk'] = "C_gl_admin_retur_pembelian/list_produk";
			$route['gl-admin-retur-supplier-list-produk/(:any)'] = 'C_gl_admin_retur_pembelian/list_produk';
			
			$route['gl-admin-retur-supplier-simpan-h-retur-awal'] = "C_gl_admin_retur_pembelian/simpan_h_retur_awal";
			$route['gl-admin-retur-supplier-simpan-h-retur-awal/(:any)'] = 'C_gl_admin_retur_pembelian/simpan_h_retur_awal';
			
			$route['gl-admin-retur-supplier-simpan-d-retur'] = "C_gl_admin_retur_pembelian/simpan_d_retur";
			$route['gl-admin-retur-supplier-simpan-d-retur/(:any)'] = 'C_gl_admin_retur_pembelian/simpan_d_retur';
			
			$route['gl-admin-retur-supplier-ubah-d-retur'] = "C_gl_admin_retur_pembelian/ubah_d_retur";
			$route['gl-admin-retur-supplier-ubah-d-retur/(:any)'] = 'C_gl_admin_retur_pembelian/ubah_d_retur';
			
			$route['gl-admin-retur-supplier-batal'] = "C_gl_admin_retur_pembelian/batal";
			$route['gl-admin-retur-supplier-batal/(:any)'] = 'C_gl_admin_retur_pembelian/batal';
			
			$route['gl-admin-retur-supplier-selesai'] = "C_gl_admin_retur_pembelian/simpan_selesai";
			$route['gl-admin-retur-supplier-selesai/(:any)'] = 'C_gl_admin_retur_pembelian/simpan_selesai';
			
			$route['gl-admin-retur-supplier-list-h-retur'] = "C_gl_admin_retur_pembelian/list_h_retur";
			$route['gl-admin-retur-supplier-list-h-retur/(:any)'] = 'C_gl_admin_retur_pembelian/list_h_retur';
			
			$route['gl-admin-retur-supplier-list-d-retur'] = "C_gl_admin_retur_pembelian/list_d_retur";
			$route['gl-admin-retur-supplier-list-d-retur/(:any)'] = 'C_gl_admin_retur_pembelian/list_d_retur';
		//RETUR SUPPLIER/PEMBELIAN
		
		//PENGELUARAN
			$route['gl-admin-pengeluaran'] = "C_gl_admin_uang_keluar/index";
			$route['gl-admin-pengeluaran/(:any)'] = 'C_gl_admin_uang_keluar/index';
			
			$route['gl-admin-pengeluaran-tambah'] = "C_gl_admin_uang_keluar/tambah_biaya";
			$route['gl-admin-pengeluaran-tambah/(:any)'] = 'C_gl_admin_uang_keluar/tambah_biaya';
			$route['gl-admin-pengeluaran-tambah/(:any)/(:any)'] = 'C_gl_admin_uang_keluar/tambah_biaya';
			
			$route['gl-admin-pengeluaran-simpan'] = "C_gl_admin_uang_keluar/simpan";
			$route['gl-admin-pengeluaran-simpan/(:any)'] = 'C_gl_admin_uang_keluar/simpan';
			
			$route['gl-admin-pengeluaran-hapus'] = "C_gl_admin_uang_keluar/hapus";
			$route['gl-admin-pengeluaran-hapus/(:any)'] = 'C_gl_admin_uang_keluar/hapus';
			
			$route['gl-admin-pengeluaran-list-kode-akun-coa'] = "C_gl_admin_uang_keluar/list_akun_coa";
			$route['gl-admin-pengeluaran-list-kode-akun-coa/(:any)'] = 'C_gl_admin_uang_keluar/list_akun_coa';
			
			$route['gl-admin-pengeluaran-list-kode-akun-coa2'] = "C_gl_admin_uang_keluar/list_akun_coa2";
			$route['gl-admin-pengeluaran-list-kode-akun-coa2/(:any)'] = 'C_gl_admin_uang_keluar/list_akun_coa2';
			
			$route['gl-admin-pengeluaran-cek-no-bukti'] = "C_gl_admin_uang_keluar/cek_uang_keluar";
			$route['gl-admin-pengeluaran-cek-no-bukti/(:any)'] = 'C_gl_admin_uang_keluar/cek_uang_keluar';
		//PENGELUARAN
		
		//PEMASUKAN
			$route['gl-admin-pemasukan'] = "C_gl_admin_uang_masuk/index";
			$route['gl-admin-pemasukan/(:any)'] = 'C_gl_admin_uang_masuk/index';
			
			$route['gl-admin-pemasukan-simpan'] = "C_gl_admin_uang_masuk/simpan";
			$route['gl-admin-pemasukan-simpan/(:any)'] = 'C_gl_admin_uang_masuk/simpan';
			
			$route['gl-admin-pemasukan-hapus'] = "C_gl_admin_uang_masuk/hapus";
			$route['gl-admin-pemasukan-hapus/(:any)'] = 'C_gl_admin_uang_masuk/hapus';
			
			$route['gl-admin-pemasukan-cek-no-bukti'] = "C_gl_admin_uang_masuk/cek_uang_masuk";
			$route['gl-admin-pemasukan-cek-no-bukti/(:any)'] = 'C_gl_admin_uang_masuk/cek_uang_masuk';
		//PEMASUKAN
	//TRANSAKSI
	
	
	//LAPORAN
		$route['gl-admin-laporan-transaksi'] = "C_gl_admin_lap_h_penjualan/index";
		$route['gl-admin-laporan-transaksi/(:any)'] = 'C_gl_admin_lap_h_penjualan/index';
		
		$route['gl-admin-laporan-transaksi-detail-tindakan-diskon'] = "C_gl_admin_lap_h_penjualan/detail_tindakan_diskon";
		$route['gl-admin-laporan-transaksi-detail-tindakan-diskon/(:any)'] = 'C_gl_admin_lap_h_penjualan/detail_tindakan_diskon';
		
		$route['gl-admin-laporan-transaksi-detail-tindakan-diskon-excel'] = "C_gl_admin_lap_h_penjualan/excel_detail_tindakan_diskon";
		$route['gl-admin-laporan-transaksi-detail-tindakan-diskon-excel/(:any)'] = 'C_gl_admin_lap_h_penjualan/excel_detail_tindakan_diskon';
		
		$route['gl-admin-laporan-apoteker'] = "C_gl_admin_lap_apoteker/index";
		$route['gl-admin-laporan-apoteker/(:any)'] = 'C_gl_admin_lap_apoteker/index';
		
		$route['gl-admin-laporan-apoteker-list-ajax'] = "C_gl_admin_lap_apoteker/list_ajax_apoteker";
		$route['gl-admin-laporan-apoteker-list-ajax/(:any)'] = 'C_gl_admin_lap_apoteker/list_ajax_apoteker';
		
		$route['gl-admin-laporan-apoteker-hapus'] = "C_gl_admin_lap_apoteker/hapus_d_penjualan_apoteker";
		$route['gl-admin-laporan-apoteker-hapus/(:any)'] = 'C_gl_admin_lap_apoteker/hapus_d_penjualan_apoteker';
		
		$route['gl-admin-laporan-apoteker-list-ajax-ubah-status-ready'] = "C_gl_admin_lap_apoteker/ubah_status_ready";
		$route['gl-admin-laporan-apoteker-list-ajax-ubah-status-ready/(:any)'] = 'C_gl_admin_lap_apoteker/ubah_status_ready';
		
		$route['gl-admin-laporan-apoteker-front-desk'] = "C_gl_admin_lap_apoteker/front_desk";
		$route['gl-admin-laporan-apoteker-front-desk/(:any)'] = 'C_gl_admin_lap_apoteker/front_desk';
		
		$route['gl-admin-laporan-apoteker-front-desk-list-ajax'] = "C_gl_admin_lap_apoteker/list_ajax_apoteker_front_desk";
		$route['gl-admin-laporan-apoteker-front-desk-list-ajax/(:any)'] = 'C_gl_admin_lap_apoteker/list_ajax_apoteker_front_desk';
		
		$route['gl-admin-laporan-apoteker-front-desk-list-ajax-isTerima'] = "C_gl_admin_lap_apoteker/ubah_status_terima";
		$route['gl-admin-laporan-apoteker-front-desk-list-ajax-isTerima/(:any)'] = 'C_gl_admin_lap_apoteker/ubah_status_terima';
		
		$route['gl-admin-laporan-transaksi-general'] = "C_gl_admin_lap_h_penjualan/list_h_penjualan_general";
		$route['gl-admin-laporan-transaksi-general/(:any)'] = 'C_gl_admin_lap_h_penjualan/list_h_penjualan_general';
		
		$route['gl-admin-laporan-detail-penjualan'] = "C_gl_admin_lap_h_penjualan/rekam_medis_lengkap";
		$route['gl-admin-laporan-detail-penjualan/(:any)'] = 'C_gl_admin_lap_h_penjualan/rekam_medis_lengkap';
		
		$route['gl-admin-laporan-hapus-penjualan'] = "C_gl_admin_lap_h_penjualan/hapus_h_penjulan";
		$route['gl-admin-laporan-hapus-penjualan/(:any)'] = 'C_gl_admin_lap_h_penjualan/hapus_h_penjulan';
		
		$route['gl-admin-laporan-list-detail-penjualan'] = "C_gl_admin_lap_h_penjualan/list_d_penjualan";
		$route['gl-admin-laporan-list-detail-penjualan/(:any)'] = 'C_gl_admin_lap_h_penjualan/list_d_penjualan';
		
		$route['gl-admin-laporan-list-detail-penjualan-row'] = "C_gl_admin_lap_h_penjualan/list_d_penjualan_row";
		$route['gl-admin-laporan-list-detail-penjualan-row/(:any)'] = 'C_gl_admin_lap_h_penjualan/list_d_penjualan_row';
		
		$route['gl-admin-laporan-list-detail-penjualan-row-excel'] = "C_gl_admin_lap_h_penjualan/excel_list_d_penjualan_row";
		$route['gl-admin-laporan-list-detail-penjualan-row-excel/(:any)'] = 'C_gl_admin_lap_h_penjualan/excel_list_d_penjualan_row';
		
		$route['gl-admin-laporan-list-detail-penjualan-pengeluarn-produk'] = "C_gl_admin_lap_h_penjualan/list_d_produk_keluar";
		$route['gl-admin-laporan-list-detail-penjualan-pengeluarn-produk/(:any)'] = 'C_gl_admin_lap_h_penjualan/list_d_produk_keluar';
		
		$route['gl-admin-laporan-list-detail-penjualan-fee'] = "C_gl_admin_lap_h_penjualan/list_d_penjualan_fee";
		$route['gl-admin-laporan-list-detail-penjualan-fee/(:any)'] = 'C_gl_admin_lap_h_penjualan/list_d_penjualan_fee';
		
		$route['gl-admin-laporan-stock-produk'] = "C_gl_admin_lap_stock_produk/index";
		$route['gl-admin-laporan-stock-produk/(:any)'] = 'C_gl_admin_lap_stock_produk/index';
		
		
		$route['gl-admin-analisa-order'] = "C_gl_admin_lap_stock_produk/view_analisa_order";
		$route['gl-admin-analisa-order/(:any)'] = 'C_gl_admin_lap_stock_produk/view_analisa_order';
		
		$route['gl-admin-analisa-order-excel'] = "C_gl_admin_lap_stock_produk/excel_view_analisa_order";
		$route['gl-admin-analisa-order-excel/(:any)'] = 'C_gl_admin_lap_stock_produk/excel_view_analisa_order';
		
		$route['gl-admin-laporan-rata-produk-terjual'] = "C_gl_admin_lap_stock_produk/view_lap_rata_penjualan";
		$route['gl-admin-laporan-rata-produk-terjual/(:any)'] = 'C_gl_admin_lap_stock_produk/view_lap_rata_penjualan';
		
		$route['gl-admin-laporan-rata-produk-terjual-excel'] = "C_gl_admin_lap_stock_produk/excel_view_lap_rata_penjualan";
		$route['gl-admin-laporan-rata-produk-terjual-excel/(:any)'] = 'C_gl_admin_lap_stock_produk/excel_view_lap_rata_penjualan';
		
		$route['gl-admin-laporan-stock-produk-excel'] = "C_gl_admin_lap_stock_produk/excel_stock_produk";
		$route['gl-admin-laporan-stock-produk-excel/(:any)'] = 'C_gl_admin_lap_stock_produk/excel_stock_produk';
		
		$route['gl-admin-laporan-stock-produk-detail-penjualan'] = "C_gl_admin_lap_stock_produk/detail_penjualan";
		$route['gl-admin-laporan-stock-produk-detail-penjualan/(:any)'] = 'C_gl_admin_lap_stock_produk/detail_penjualan';
		$route['gl-admin-laporan-stock-produk-detail-penjualan/(:any)/(:any)'] = 'C_gl_admin_lap_stock_produk/detail_penjualan';
		
		$route['gl-admin-laporan-stock-produk-detail-penerimaan'] = "C_gl_admin_lap_stock_produk/detail_penerimaan";
		$route['gl-admin-laporan-stock-produk-detail-penerimaan/(:any)'] = 'C_gl_admin_lap_stock_produk/detail_penerimaan';
		$route['gl-admin-laporan-stock-produk-detail-penerimaan/(:any)/(:any)'] = 'C_gl_admin_lap_stock_produk/detail_penerimaan';
		
		$route['gl-admin-laporan-stock-produk-detail-mutasiin'] = "C_gl_admin_lap_stock_produk/detail_mutasi_in";
		$route['gl-admin-laporan-stock-produk-detail-mutasiin/(:any)'] = 'C_gl_admin_lap_stock_produk/detail_mutasi_in';
		$route['gl-admin-laporan-stock-produk-detail-mutasiin/(:any)/(:any)'] = 'C_gl_admin_lap_stock_produk/detail_mutasi_in';
		
		$route['gl-admin-laporan-stock-produk-detail-mutasiout'] = "C_gl_admin_lap_stock_produk/detail_mutasi_out";
		$route['gl-admin-laporan-stock-produk-detail-mutasiout/(:any)'] = 'C_gl_admin_lap_stock_produk/detail_mutasi_out';
		$route['gl-admin-laporan-stock-produk-detail-mutasiout/(:any)/(:any)'] = 'C_gl_admin_lap_stock_produk/detail_mutasi_out';
		
		$route['gl-admin-laporan-stock-produk-detail-histori-produk'] = "C_gl_admin_lap_stock_produk/detail_histori_produk";
		$route['gl-admin-laporan-stock-produk-detail-histori-produk/(:any)'] = 'C_gl_admin_lap_stock_produk/detail_histori_produk';
		$route['gl-admin-laporan-stock-produk-detail-histori-produk/(:any)/(:any)'] = 'C_gl_admin_lap_stock_produk/detail_histori_produk';
		
		$route['gl-admin-laporan-stock-produk-detail-retur-pembelian'] = "C_gl_admin_lap_stock_produk/detail_retur_pembelian";
		$route['gl-admin-laporan-stock-produk-detail-retur-pembelian/(:any)'] = 'C_gl_admin_lap_stock_produk/detail_retur_pembelian';
		$route['gl-admin-laporan-stock-produk-detail-retur-pembelian/(:any)/(:any)'] = 'C_gl_admin_lap_stock_produk/detail_retur_pembelian';
		
		$route['gl-admin-laporan-transaksi-pembayaran'] = "C_gl_admin_d_penjualan_bayar/index";
		$route['gl-admin-laporan-transaksi-pembayaran/(:any)'] = 'C_gl_admin_d_penjualan_bayar/index';
		$route['gl-admin-laporan-transaksi-pembayaran/(:any)/(:any)'] = 'C_gl_admin_d_penjualan_bayar/index';
		
		$route['gl-admin-laporan-transaksi-pembayaran-simpan'] = "C_gl_admin_d_penjualan_bayar/simpan";
		$route['gl-admin-laporan-transaksi-pembayaran-simpan/(:any)'] = 'C_gl_admin_d_penjualan_bayar/simpan';
		
		$route['gl-admin-laporan-transaksi-pembayaran-hapus'] = "C_gl_admin_d_penjualan_bayar/hapus";
		$route['gl-admin-laporan-transaksi-pembayaran-hapus/(:any)'] = 'C_gl_admin_d_penjualan_bayar/hapus';
		$route['gl-admin-laporan-transaksi-pembayaran-hapus/(:any)/(:any)'] = 'C_gl_admin_d_penjualan_bayar/hapus';
		
		$route['gl-admin-laporan-permintaan-cabang'] = "C_gl_admin_lap_request_cabang/index";
		$route['gl-admin-laporan-permintaan-cabang/(:any)'] = 'C_gl_admin_lap_request_cabang/index';
		
		$route['gl-admin-laporan-mutasi-produk'] = "C_gl_admin_lap_mutasi/index";
		$route['gl-admin-laporan-mutasi-produk/(:any)'] = "C_gl_admin_lap_mutasi/index";
		
		$route['gl-admin-laporan-mutasi-produk-dari-cabang-lain'] = "C_gl_admin_lap_mutasi/list_mutasi_dari_cabang_lain";
		$route['gl-admin-laporan-mutasi-produk-dari-cabang-lain/(:any)'] = "C_gl_admin_lap_mutasi/list_mutasi_dari_cabang_lain";
		
		$route['gl-admin-laporan-permintaan-cabang-proses'] = "C_gl_admin_lap_request_cabang/proses_permintaan_cabang";
		$route['gl-admin-laporan-permintaan-cabang-proses/(:any)'] = 'C_gl_admin_lap_request_cabang/proses_permintaan_cabang';
		$route['gl-admin-laporan-permintaan-cabang-proses/(:any)/(:any)'] = 'C_gl_admin_lap_request_cabang/proses_permintaan_cabang';
		$route['gl-admin-laporan-permintaan-cabang-proses/(:any)/(:any)/(:any)'] = 'C_gl_admin_lap_request_cabang/proses_permintaan_cabang';
		
		$route['gl-admin-laporan-pembelian'] = "C_gl_admin_lap_h_pembelian/index";
		$route['gl-admin-laporan-pembelian/(:any)'] = 'C_gl_admin_lap_h_pembelian/index';
		
		$route['gl-admin-laporan-pembelian-detail-produk'] = "C_gl_admin_lap_h_pembelian/list_ajax_detail_pembelian";
		$route['gl-admin-laporan-pembelian-detail-produk/(:any)'] = 'C_gl_admin_lap_h_pembelian/list_ajax_detail_pembelian';
		
		$route['gl-admin-laporan-pembelian-hapus'] = "C_gl_admin_lap_h_pembelian/hapus_pembelian";
		$route['gl-admin-laporan-pembelian-hapus/(:any)'] = 'C_gl_admin_lap_h_pembelian/hapus_pembelian';
		
		$route['gl-admin-laporan-pembelian-pembayaran'] = "C_gl_admin_d_pembelian_bayar/index";
		$route['gl-admin-laporan-pembelian-pembayaran/(:any)'] = 'C_gl_admin_d_pembelian_bayar/index';
		$route['gl-admin-laporan-pembelian-pembayaran/(:any)/(:any)'] = 'C_gl_admin_d_pembelian_bayar/index';
		
		$route['gl-admin-laporan-pembelian-pembayaran-simpan'] = "C_gl_admin_d_pembelian_bayar/simpan";
		$route['gl-admin-laporan-pembelian-pembayaran-simpan/(:any)'] = 'C_gl_admin_d_pembelian_bayar/simpan';
		
		$route['gl-admin-laporan-pembelian-pembayaran-hapus'] = "C_gl_admin_d_pembelian_bayar/hapus";
		$route['gl-admin-laporan-pembelian-pembayaran-hapus/(:any)'] = 'C_gl_admin_d_pembelian_bayar/hapus';
		$route['gl-admin-laporan-pembelian-pembayaran-hapus/(:any)/(:any)'] = 'C_gl_admin_d_pembelian_bayar/hapus';
		
		$route['gl-admin-laporan-pembelian-lap-detail-produk-order'] = "C_gl_admin_lap_h_pembelian/tampilkan_lap_detail_pembelian_order";
		$route['gl-admin-laporan-pembelian-lap-detail-produk-order/(:any)'] = 'C_gl_admin_lap_h_pembelian/tampilkan_lap_detail_pembelian_order';
		
		$route['gl-admin-laporan-pembelian-lap-detail-produk-penerimaan'] = "C_gl_admin_lap_h_pembelian/tampilkan_lap_detail_pembelian_order_terima";
		$route['gl-admin-laporan-pembelian-lap-detail-produk-penerimaan/(:any)'] = 'C_gl_admin_lap_h_pembelian/tampilkan_lap_detail_pembelian_order_terima';
		
		$route['gl-admin-laporan-keuangan'] = "C_gl_admin_lap_keuangan/index";
		$route['gl-admin-laporan-keuangan/(:any)'] = 'C_gl_admin_lap_keuangan/index';
		
		$route['gl-admin-laporan-jurnal'] = "C_gl_admin_lap_keuangan/jurnal";
		$route['gl-admin-laporan-jurnal/(:any)'] = 'C_gl_admin_lap_keuangan/jurnal';
		
		$route['gl-admin-laporan-buku-besar'] = "C_gl_admin_acc_buku_besar/index";
		$route['gl-admin-laporan-buku-besar/(:any)'] = 'C_gl_admin_acc_buku_besar/index';
		
		$route['gl-admin-laporan-log-aktifitas'] = "C_gl_admin_log_aktifitas/index";
		$route['gl-admin-laporan-log-aktifitas/(:any)'] = 'C_gl_admin_log_aktifitas/index';
		
		$route['gl-admin-laporan-neraca'] = "C_gl_admin_lap_neraca/index";
		$route['gl-admin-laporan-neraca/(:any)'] = 'C_gl_admin_lap_neraca/index';
		
		$route['gl-admin-laporan-neraca-get-kas'] = "C_gl_admin_lap_neraca/get_kas";
		$route['gl-admin-laporan-neraca-get-kas/(:any)'] = 'C_gl_admin_lap_neraca/get_kas';
		
		$route['gl-admin-laporan-neraca-get-stock'] = "C_gl_admin_lap_neraca/get_stock_persediaan";
		$route['gl-admin-laporan-neraca-get-stock/(:any)'] = 'C_gl_admin_lap_neraca/get_stock_persediaan';
		
		$route['gl-admin-laporan-neraca-get-sisa-piutang-awal-tr'] = "C_gl_admin_lap_neraca/get_piutang_awal_tr";
		$route['gl-admin-laporan-neraca-get-sisa-piutang-awal-tr/(:any)'] = 'C_gl_admin_lap_neraca/get_piutang_awal_tr';
		
		$route['gl-admin-laporan-neraca-get-hutang-awal'] = "C_gl_admin_lap_neraca/get_hutang_awal";
		$route['gl-admin-laporan-neraca-get-hutang-awal/(:any)'] = 'C_gl_admin_lap_neraca/get_hutang_awal';
		
		$route['gl-admin-laporan-neraca-get-hutang-transaksi'] = "C_gl_admin_lap_neraca/get_hutang_tr";
		$route['gl-admin-laporan-neraca-get-hutang-transaksi/(:any)'] = 'C_gl_admin_lap_neraca/get_hutang_tr';
		
	//LAPORAN
	
	
$route['gl-admin-images'] = "C_gl_admin_images/index";
$route['gl-admin-images/(:any)'] = 'C_gl_admin_images/index';
$route['gl-admin-images/(:any)/(:any)'] = 'C_gl_admin_images/index';
$route['gl-admin-images/(:any)/(:any)/(:any)'] = 'C_gl_admin_images/index';

$route['gl-admin-images-simpan'] = "C_gl_admin_images/simpan";
$route['gl-admin-images-simpan/(:any)'] = 'C_gl_admin_images/simpan';
$route['gl-admin-images-simpan/(:any)/(:any)'] = 'C_gl_admin_images/simpan';
$route['gl-admin-images-simpan/(:any)/(:any)/(:any)'] = 'C_gl_admin_images/simpan';

$route['gl-admin-images-hapus'] = "C_gl_admin_images/hapus";
$route['gl-admin-images-hapus/(:any)'] = 'C_gl_admin_images/hapus';
$route['gl-admin-images-hapus/(:any)/(:any)'] = 'C_gl_admin_images/hapus';
$route['gl-admin-images-hapus/(:any)/(:any)/(:any)'] = 'C_gl_admin_images/hapus';


	//PUSAT
		$route['gl-pusat-login-view'] = "C_gl_pst_login/index";
		$route['gl-pusat-login-view/(:any)'] = 'C_gl_pst_login/index';
		
		$route['gl-pusat-login-view-cek-login'] = "C_gl_pst_login/cek_login";
		$route['gl-pusat-login-view-cek-login/(:any)'] = 'C_gl_pst_login/cek_login';
		
		$route['gl-pusat-dashboard'] = "C_gl_pst_dash/index";
		$route['gl-pusat-dashboard/(:any)'] = 'C_gl_pst_dash/index';
		
		//KARYAWAN
			$route['gl-pusat-data-karyawan'] = "C_gl_pst_karyawan/index";
			$route['gl-pusat-data-karyawan/(:any)'] = 'C_gl_pst_karyawan/index';
			
			$route['gl-pusat-data-karyawan-detail'] = "C_gl_pst_karyawan/detail";
			$route['gl-pusat-data-karyawan-detail/(:any)'] = 'C_gl_pst_karyawan/detail';
		//KARYAWAN
		
		//PASIEN
			$route['gl-pusat-pasien'] = "C_gl_pst_costumer/index";
			$route['gl-pusat-pasien/(:any)'] = 'C_gl_pst_costumer/index';
			
			
			$route['gl-pusat-pasien-detail'] = "C_gl_pst_costumer/detail";
			$route['gl-pusat-pasien-detail/(:any)'] = 'C_gl_pst_costumer/detail';
			$route['gl-pusat-pasien-detail/(:any)/(:any)'] = 'C_gl_pst_costumer/detail';
		//PASIEN
		
		//PRODUK
			//KATEGORI PRODUK
				$route['gl-pusat-kategori-produk-jasa'] = "C_gl_pst_kat_produk/index";
				$route['gl-pusat-kategori-produk-jasa/(:any)'] = 'C_gl_pst_kat_produk/index';
				
				$route['gl-pusat-kategori-produk-jasa-simpan'] = "C_gl_pst_kat_produk/simpan";
				$route['gl-pusat-kategori-produk-jasa-simpan/(:any)'] = 'C_gl_pst_kat_produk/simpan';
				
				$route['gl-pusat-kategori-produk-jasa-hapus'] = "C_gl_pst_kat_produk/hapus";
				$route['gl-pusat-kategori-produk-jasa-hapus/(:any)'] = 'C_gl_pst_kat_produk/hapus';
				
				$route['gl-pusat-kategori-produk-jasa-cek'] = "C_gl_pst_kat_produk/cek_kat_produk";
				$route['gl-pusat-kategori-produk-jasa-cek/(:any)'] = 'C_gl_pst_kat_produk/cek_kat_produk';
			//KATEGORI PRODUK
			
			//SATUAN
				$route['gl-pusat-satuan-produk-jasa'] = "C_gl_pst_satuan/index";
				$route['gl-pusat-satuan-produk-jasa/(:any)'] = 'C_gl_pst_satuan/index';
				
				$route['gl-pusat-satuan-produk-jasa-simpan'] = "C_gl_pst_satuan/simpan";
				$route['gl-pusat-satuan-produk-jasa-simpan/(:any)'] = 'C_gl_pst_satuan/simpan';
				
				$route['gl-pusat-satuan-produk-jasa-hapus'] = "C_gl_pst_satuan/hapus";
				$route['gl-pusat-satuan-produk-jasa-hapus/(:any)'] = 'C_gl_pst_satuan/hapus';
				
				$route['gl-pusat-satuan-produk-jasa-cek'] = "C_gl_pst_satuan/cek_satuan";
				$route['gl-pusat-satuan-produk-jasa-cek/(:any)'] = 'C_gl_pst_satuan/cek_satuan';
			//SATUAN
			
			//MEDIA TRANSAKSI
				$route['gl-pusat-media-produk-jasa'] = "C_gl_pst_media_transaksi/index";
				$route['gl-pusat-media-produk-jasa/(:any)'] = 'C_gl_pst_media_transaksi/index';
				
				$route['gl-pusat-media-produk-jasa-simpan'] = "C_gl_pst_media_transaksi/simpan";
				$route['gl-pusat-media-produk-jasa-simpan/(:any)'] = 'C_gl_pst_media_transaksi/simpan';
				
				$route['gl-pusat-media-produk-jasa-hapus'] = "C_gl_pst_media_transaksi/hapus";
				$route['gl-pusat-media-produk-jasa-hapus/(:any)'] = 'C_gl_pst_media_transaksi/hapus';
				
				$route['gl-pusat-media-produk-jasa-cek'] = "C_gl_pst_media_transaksi/cek_media_transaksi";
				$route['gl-pusat-media-produk-jasa-cek/(:any)'] = 'C_gl_pst_media_transaksi/cek_media_transaksi';
			//MEDIA TRANSAKSI
			
			//PRODUK
				$route['gl-pusat-produk-jasa'] = "C_gl_pst_produk/index";
				$route['gl-pusat-produk-jasa/(:any)'] = 'C_gl_pst_produk/index';
				
				$route['gl-pusat-produk-jasa-simpan'] = "C_gl_pst_produk/simpan";
				$route['gl-pusat-produk-jasa-simpan/(:any)'] = 'C_gl_pst_produk/simpan';
				
				$route['gl-pusat-produk-jasa-hapus'] = "C_gl_pst_produk/hapus";
				$route['gl-pusat-produk-jasa-hapus/(:any)'] = 'C_gl_pst_produk/hapus';
				
				$route['gl-pusat-produk-jasa-cek'] = "C_gl_pst_produk/cek_media_transaksi";
				$route['gl-pusat-produk-jasa-cek/(:any)'] = 'C_gl_pst_produk/cek_media_transaksi';
			//PRODUK
		//PRODUK
		
		//PENGATURAN HARGA
			//SATUAN KONVERSI
				$route['gl-pusat-satuan-konversi'] = "C_gl_pst_satuan_konversi/index";
				$route['gl-pusat-satuan-konversi/(:any)'] = 'C_gl_pst_satuan_konversi/index';
				
				$route['gl-pusat-satuan-konversi-simpan'] = "C_gl_pst_satuan_konversi/simpan";
				$route['gl-pusat-satuan-konversi-simpan/(:any)'] = 'C_gl_pst_satuan_konversi/simpan';
			//SATUAN KONVERSI
			
			//HARGA DASAR
				$route['gl-pusat-harga-dasar-satuan'] = "C_gl_pst_harga_dasar_satuan/index";
				$route['gl-pusat-harga-dasar-satuan/(:any)'] = 'C_gl_pst_harga_dasar_satuan/index';
				
				$route['gl-pusat-harga-dasar-satuan-simpan'] = "C_gl_pst_harga_dasar_satuan/simpan";
				$route['gl-pusat-harga-dasar-satuan-simpan/(:any)'] = 'C_gl_pst_harga_dasar_satuan/simpan';
			//HARGA DASAR
			
			//HARGA JUAL KE PASIEN
				$route['gl-pusat-harga-pasien'] = "C_gl_pst_harga_pasien/index";
				$route['gl-pusat-harga-pasien/(:any)'] = 'C_gl_pst_harga_pasien/index';
				
				$route['gl-pusat-harga-pasien-simpan'] = "C_gl_pst_harga_pasien/simpan";
				$route['gl-pusat-harga-pasien-simpan/(:any)'] = 'C_gl_pst_harga_pasien/simpan';
			//HARGA JUAL KE PASIEN
		//PENGATURAN HARGA
		
		//INVENTORI
			$route['gl-pusat-inventori-flow'] = "C_gl_pst_inventori/index";
			$route['gl-pusat-inventori-flow/(:any)'] = 'C_gl_pst_inventori/index';
			
			$route['gl-pusat-inventori-produk-terlaris'] = "C_gl_pst_inventori/inv_analisa_produk_terlaris";
			$route['gl-pusat-inventori-produk-terlaris/(:any)'] = 'C_gl_pst_inventori/inv_analisa_produk_terlaris';
			
			$route['gl-pusat-inventori-po-sisa'] = "C_gl_pst_inventori/inv_analisa_po_belum_full";
			$route['gl-pusat-inventori-po-sisa/(:any)'] = 'C_gl_pst_inventori/inv_analisa_po_belum_full';
			
			$route['gl-pusat-inventori-produk-stock'] = "C_gl_pst_inventori/inv_list_stock_produk";
			$route['gl-pusat-inventori-produk-stock/(:any)'] = 'C_gl_pst_inventori/inv_list_stock_produk';
			
			$route['gl-pusat-inventori-produk-stock-limit'] = "C_gl_pst_inventori/inv_list_stock_produk_limit";
			$route['gl-pusat-inventori-produk-stock-limit/(:any)'] = 'C_gl_pst_inventori/inv_list_stock_produk_limit';
			
			$route['gl-pusat-inventori-produk-stock-expired'] = "C_gl_pst_inventori/inv_list_tgl_expired";
			$route['gl-pusat-inventori-produk-stock-expired/(:any)'] = 'C_gl_pst_inventori/inv_list_tgl_expired';
		//INVENTORI
		
		//AKUNTANSI
			$route['gl-pusat-akuntansi-transaksi'] = "C_gl_pst_akuntansi/index";
			$route['gl-pusat-akuntansi-transaksi/(:any)'] = 'C_gl_pst_akuntansi/index';
			
			$route['gl-pusat-laporan-transaksi'] = "C_gl_pst_lap_penjualan/index";
			$route['gl-pusat-laporan-transaksi/(:any)'] = 'C_gl_pst_lap_penjualan/index';
			
			$route['gl-pusat-laporan-transaksi-produk'] = "C_gl_pst_lap_penjualan/produk_terjual";
			$route['gl-pusat-laporan-transaksi-produk/(:any)'] = 'C_gl_pst_lap_penjualan/produk_terjual';
			
			$route['gl-pusat-laporan-transaksi-pendapatan'] = "C_gl_pst_lap_penjualan/laporan_pendapatan";
			$route['gl-pusat-laporan-transaksi-pendapatan/(:any)'] = 'C_gl_pst_lap_penjualan/laporan_pendapatan';
			
			$route['gl-pusat-galeri-foto-pasien'] = "C_gl_pst_lap_penjualan/galeri_foto_pasien";
			$route['gl-pusat-galeri-foto-pasien/(:any)'] = "C_gl_pst_lap_penjualan/galeri_foto_pasien";
			
			
		//AKUNTANSI
		
		//AKTIFITAS
			$route['gl-pusat-aktifitas-log'] = "C_gl_pst_log_aktifitas/index";
			$route['gl-pusat-aktifitas-log/(:any)'] = 'C_gl_pst_log_aktifitas/index';
			
			$route['gl-pusat-aktifitas-request-perubahan'] = "C_gl_pst_req_ubah/index";
			$route['gl-pusat-aktifitas-request-perubahan/(:any)'] = 'C_gl_pst_req_ubah/index';
		//AKTIFITAS
	//PUSAT
	
	
	
	//ROUTE PENGGAJIAN IRMAN
	//GAJI POKOK
	$route['gl-admin-gapok'] = "C_gl_admin_gaji_pokok/index";
	$route['gl-admin-gapok-input'] = "C_gl_admin_gaji_pokok/create";
	$route['gl-admin-gapok-edit'] = "C_gl_admin_gaji_pokok/update";
	$route['gl-admin-gapok-edit/(:any)'] = "C_gl_admin_gaji_pokok/update";

	//JAM KERJA
	$route['gl-admin-jamkerja'] = "C_gl_admin_jam_kerja/index";
	$route['gl-admin-jamkerja-input'] = "C_gl_admin_jam_kerja/create";
	$route['gl-admin-jamkerja-edit'] = "C_gl_admin_jam_kerja/update";
	$route['gl-admin-jamkerja-edit/(:any)'] = "C_gl_admin_jam_kerja/update";

	//JAM KERJA KARYAWAN
	$route['gl-admin-jamkerja-karyawan'] = "C_gl_admin_jamkerja_karyawan/index";
	$route['gl-admin-jamkerja-karyawan/(:any)'] = "C_gl_admin_jamkerja_karyawan/index";
	$route['gl-admin-jamkerja-karyawan/(:any)/(:any)'] = "C_gl_admin_jamkerja_karyawan/index";
	$route['gl-admin-jamkerja-karyawan/(:any)/(:any)/(:any)'] = "C_gl_admin_jamkerja_karyawan/index";



	//TUNJANGAN
	$route['gl-admin-tunjangan'] = "C_gl_admin_tunjangan/index";
	$route['gl-admin-tunjangan-input'] = "C_gl_admin_tunjangan/create";
	$route['gl-admin-tunjangan-edit'] = "C_gl_admin_tunjangan/update";
	$route['gl-admin-tunjangan-edit/(:any)'] = "C_gl_admin_tunjangan/update";

	$route['gl-admin-tunjangan-karyawan'] = "C_gl_admin_tunjangan_karyawan/index";
	$route['gl-admin-tunjangan-karyawan/(:any)'] = "C_gl_admin_tunjangan_karyawan/index";
	$route['gl-admin-tunjangan-karyawan/(:any)/(:any)'] = "C_gl_admin_tunjangan_karyawan/index";
	$route['gl-admin-tunjangan-karyawan-input'] = "C_gl_admin_tunjangan_karyawan/create";
	$route['gl-admin-tunjangan-karyawan-edit'] = "C_gl_admin_tunjangan_karyawan/update";
	$route['gl-admin-tunjangan-karyawan-edit/(:any)'] = "C_gl_admin_tunjangan_karyawan/update";

	//POTONGAN
	$route['gl-admin-potongan'] = "C_gl_admin_potongan/index";
	$route['gl-admin-potongan-input'] = "C_gl_admin_potongan/create";
	$route['gl-admin-potongan-edit'] = "C_gl_admin_potongan/update";
	$route['gl-admin-potongan-edit/(:any)'] = "C_gl_admin_potongan/update";

	$route['gl-admin-potongan-karyawan'] = "C_gl_admin_potongan_karyawan/index";
	$route['gl-admin-potongan-karyawan/(:any)'] = "C_gl_admin_potongan_karyawan/index";
	$route['gl-admin-potongan-karyawan/(:any)/(:any)'] = "C_gl_admin_potongan_karyawan/index";
	$route['gl-admin-potongan-karyawan-input'] = "C_gl_admin_potongan_karyawan/create";
	$route['gl-admin-potongan-karyawan-edit'] = "C_gl_admin_potongan_karyawan/update";
	$route['gl-admin-potongan-karyawan-edit/(:any)'] = "C_gl_admin_potongan_karyawan/update";

	//HITUNG GAJI
	$route['gl-admin-hitung-gaji'] = "C_gl_admin_hitung_gaji/index";
	$route['gl-admin-hitung-gaji/(:any)'] = "C_gl_admin_hitung_gaji/index";
	$route['gl-admin-hitung-gaji/(:any)/(:any)'] = "C_gl_admin_hitung_gaji/index";
	$route['gl-admin-hitung-gaji/(:any)/(:any)/(:any)'] = "C_gl_admin_hitung_gaji/index";

	$route['laporan-gaji/(:any)'] = "C_gl_admin_hitung_gaji/laporan";
	$route['cetak-excel/(:any)'] = "C_gl_admin_hitung_gaji/export_excel";
	$route['cetak-slip-gaji/(:any)'] = "C_gl_admin_hitung_gaji/cetak_slip_gaji";
	$route['cetak-slip-gaji-all/(:any)'] = "C_gl_admin_hitung_gaji/cetak_slip_gaji_all";

	$route['detail-data-penggajian/(:any)'] = "C_gl_admin_hitung_gaji/detail_penggajian";
	$route['detail-data-penggajian/(:any)/(:any)'] = "C_gl_admin_hitung_gaji/detail_penggajian";
	$route['detail-data-penggajian/(:any)/(:any)/(:any)'] = "C_gl_admin_hitung_gaji/detail_penggajian";

	$route['cetak-excel-detail-tindakan/(:any)'] = "C_gl_admin_hitung_gaji/cetak_excel_detail_tindakan";
	$route['cetak-excel-detail-tindakan/(:any)/(:any)'] = "C_gl_admin_hitung_gaji/cetak_excel_detail_tindakan";
	$route['cetak-excel-detail-tindakan/(:any)/(:any)/(:any)'] = "C_gl_admin_hitung_gaji/cetak_excel_detail_tindakan";

	

	//ROUTE PENGGAJIAN IRMAN
	
	
	
	//SURAT MASUK
		$route['gl-admin-surat-masuk'] = "C_gl_admin_surat/index";
		$route['gl-admin-surat-masuk/(:any)'] = 'C_gl_admin_surat/index';
		
		$route['gl-admin-surat-masuk-simpan'] = "C_gl_admin_surat/simpan";
		$route['gl-admin-surat-masuk-simpan/(:any)'] = 'C_gl_admin_surat/simpan';
		
		$route['gl-admin-surat-masuk-hapus'] = "C_gl_admin_surat/hapus";
		$route['gl-admin-surat-masuk-hapus/(:any)'] = 'C_gl_admin_surat/hapus';
		
		$route['gl-admin-tag-surat'] = "C_gl_admin_surat/tag_surat";
		$route['gl-admin-tag-surat/(:any)'] = 'C_gl_admin_surat/tag_surat';
		$route['gl-admin-tag-surat/(:any)/(:any)'] = 'C_gl_admin_surat/tag_surat';
		$route['gl-admin-tag-surat/(:any)/(:any)/(:any)'] = 'C_gl_admin_surat/tag_surat';
	//SURAT MASUK
	
	//SURAT KLEUAR
		$route['gl-admin-surat-keluar'] = "C_gl_admin_surat_keluar/index";
		$route['gl-admin-surat-keluar/(:any)'] = 'C_gl_admin_surat_keluar/index';
		
		$route['gl-admin-surat-keluar-simpan'] = "C_gl_admin_surat_keluar/simpan";
		$route['gl-admin-surat-keluar-simpan/(:any)'] = 'C_gl_admin_surat_keluar/simpan';
		
		$route['gl-admin-surat-keluar-hapus'] = "C_gl_admin_surat_keluar/hapus";
		$route['gl-admin-surat-keluar-hapus/(:any)'] = 'C_gl_admin_surat_keluar/hapus';
	//SURAT KLEUAR
				
//GLAFIDSYA


//BAZNAS
	$route['gl-admin-cek-pasien'] = "C_gl_admin_cek_pasien/index";
	$route['gl-admin-cek-pasien/(:any)'] = 'C_gl_admin_cek_pasien/index';
	
	$route['gl-admin-pengajuan'] = "C_gl_admin_pengajuan/index";
	$route['gl-admin-pengajuan/(:any)'] = 'C_gl_admin_pengajuan/index';
	
	$route['gl-admin-pengajuan-tambah-mustahik'] = "C_gl_admin_pengajuan/tambah_mustahik";
	$route['gl-admin-pengajuan-tambah-mustahik/(:any)'] = 'C_gl_admin_pengajuan/tambah_mustahik';
	$route['gl-admin-pengajuan-tambah-mustahik/(:any)/(:any)'] = 'C_gl_admin_pengajuan/tambah_mustahik';
	
	$route['gl-admin-pengajuan-simpan'] = "C_gl_admin_pengajuan/simpan";
	$route['gl-admin-pengajuan-simpan/(:any)'] = 'C_gl_admin_pengajuan/simpan';
	
	$route['gl-admin-pengajuan-hapus'] = "C_gl_admin_pengajuan/hapus";
	$route['gl-admin-pengajuan-hapus/(:any)'] = 'C_gl_admin_pengajuan/hapus';
	
	
	$route['gl-admin-disposisi'] = "C_gl_admin_disposisi/index";
	$route['gl-admin-disposisi/(:any)'] = 'C_gl_admin_disposisi/index';
	
	$route['gl-admin-disposisi-proses'] = "C_gl_admin_disposisi/proses_ajuan";
	$route['gl-admin-disposisi-proses/(:any)'] = 'C_gl_admin_disposisi/proses_ajuan';
	
	$route['gl-admin-disposisi-proses-print'] = "C_gl_admin_disposisi/print_proses_ajuan";
	$route['gl-admin-disposisi-proses-print/(:any)'] = 'C_gl_admin_disposisi/print_proses_ajuan';
	
	$route['gl-admin-disposisi-acc'] = "C_gl_admin_disposisi/acc_pengajuan";
	$route['gl-admin-disposisi-acc/(:any)'] = 'C_gl_admin_disposisi/acc_pengajuan';
	
	$route['gl-admin-disposisi-penyerahan'] = "C_gl_admin_disposisi/penyerahan_pengajuan";
	$route['gl-admin-disposisi-penyerahan/(:any)'] = 'C_gl_admin_disposisi/penyerahan_pengajuan';
	
	$route['gl-admin-view-lap-ajuan'] = "C_gl_admin_lap_pengajuan/index";
	$route['gl-admin-view-lap-ajuan/(:any)'] = 'C_gl_admin_lap_pengajuan/index';
	
	$route['gl-admin-view-lap-ajuan-excel'] = "C_gl_admin_lap_pengajuan/export_excel";
	$route['gl-admin-view-lap-ajuan-excel/(:any)'] = 'C_gl_admin_lap_pengajuan/export_excel';
	
	$route['gl-admin-view-lap-detail'] = "C_gl_admin_lap_pengajuan/detail";
	$route['gl-admin-view-lap-detail/(:any)'] = 'C_gl_admin_lap_pengajuan/detail';
	
	$route['gl-admin-view-lap-detail-excel'] = "C_gl_admin_lap_pengajuan/export_excel_detail";
	$route['gl-admin-view-lap-detail-excel/(:any)'] = 'C_gl_admin_lap_pengajuan/export_excel_detail';
	
//BAZNAS


