<?php

	Class C_admin_login extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->helper(array('captcha','array'));
            $this->load->library(array('form_validation'));
            $this->config->load('cap');
		}
		
		function index()
		{
			// $this->auto_remove_captcha();
			// $user = $this->input->post('user');
			// $pass = $this->input->post('pass');
			
            // if($this->validasi_input_captcha() == false)
            // {
                // $data = array();
				
				// $text = ucfirst(random_element($this->config->item('captcha_word')));
					// $number = random_element($this->config->item('captcha_num'));
					// $word = $text.$number;

					// $expiration = time()-300; //batas waktu 5 menit
					// $this->db->query('DELETE FROM tb_captcha WHERE captcha_time < '.$expiration);
					// //konfigurasi captcha
					// $vals = array(
						// 'font_path' => 'system/fonts/texb.ttf',
						// 'img_path' => './assets/captcha/',
						// 'img_url' => base_url().'assets/captcha/',
						// 'img_width' => '235',
						// 'img_height' => 50,
						// 'word' => $word,
						// 'expiration' => $expiration,
						// 'time' => date('Y-m-d H:i:s')
						// );
					
					// $cap = create_captcha($vals);
					// $data['captcha_img'] = $cap['image'];
					
					 // $captcha = array(   'captcha_id' => '',
										// 'captcha_time' => date('Y-m-d H:i:s'),
										// 'ip_address' => $this->input->ip_address(),
										// 'word' => "TEST"
										// );
					 // $query = $this->db->insert_string('tb_captcha',$captcha);
					// $this->db->query($query);	
					
					
					// $this->db->query('OPTIMIZE TABLE `tb_captcha` ');
                    // $this->load->view('admin/login.html',$data);
                    
            // }
            // else
            // {
                    // $this->cek_login();
            // }
			
			
			// if(($this->session->userdata('user_admin') == null) or ($this->session->userdata('pass_admin') == null))
			// {
				// //redirect('index.php/admin-login','location');
				// header('Location: '.base_url().'admin-login');
			// }
			// else
			// {
				// $data_login = $this->M_a_akun->cek_login($this->session->userdata('user_admin'),md5(base64_decode($this->session->userdata('pass_admin'))),'0');
				// if (!empty($data_login))
				// {
                    // $listKategori = $this->M_kat_produk->listKategori();
                    // $listPrduk = $this->M_produk->listproduk('','');
					// $data = array('page_content'=>'adm_produk','title'=>'Elga Network | Dashboard Admin Produk','listKategori'=>$listKategori,'listPrduk'=>$listPrduk);
					// $this->load->view('admin/container',$data);
				// //}
				// // else
				// // {
					// // header('Location: '.base_url().'admin-login');
				// // }
				
				$this->load->view('admin/login.html');
				////}
			////}
		}
        
        public function cek_login()
        {
            $user = htmlentities($_POST['user'], ENT_QUOTES, 'UTF-8');
            $pass = htmlentities($_POST['pass'], ENT_QUOTES, 'UTF-8');
            $data_login = $this->M_akun->get_login($user,md5($pass));
    		if(!empty($data_login))
    		{
                if ($data_login->avatar <> "")
                {
                    $src = $data_login->avatar_url;
                }
                else
                {
                	$src = base_url().'assets/global/users/loading.gif';
                }
				
				$user = array(
					'ses_user_admin'  => $user,
					'ses_pass_admin'  => base64_encode($pass),
					'ses_pass_admin_pure'  => ($pass),
					'ses_id_karyawan' => $data_login->id_karyawan,
					'ses_id_jabatan' => $data_login->id_jabatan,
					'ses_nama_jabatan' => $data_login->nama_jabatan,
					'ses_nama_karyawan' => $data_login->nama_karyawan,
					'ses_kode_kantor' => $data_login->kode_kantor,
					'ses_avatar_url' => $src,
					'ses_tgl_ins' => $data_login->tgl_ins
				);
				
    			$this->session->set_userdata($user);
    			//redirect('index.php/admin','location');
				
				
				//CEK AKSES GROUP 1
				$cek_group1 = $this->M_akun->get_hak_akses_group1($data_login->id_jabatan,$data_login->kode_kantor);
				if(!empty($cek_group1))
				{
					$ses_group1 = array
					(
						'group1_akses1' => $cek_group1->akses1
						,'group1_akses2' => $cek_group1->akses2
						,'group1_akses3' => $cek_group1->akses3
						,'group1_akses4' => $cek_group1->akses4
						,'group1_akses5' => $cek_group1->akses5
					);
					$this->session->set_userdata($ses_group1);
				}
				//CEK AKSES GROUP 1
				
				//CEK AKSES MAIN GROUP
				$cek_main_group = $this->M_akun->get_hak_akses_group1_main_group($data_login->id_jabatan,$data_login->kode_kantor);
				if(!empty($cek_main_group))
				{
					$ses_main_group = array
					(
						'main_group_akses11' => $cek_main_group->akses11
						,'main_group_akses12' => $cek_main_group->akses12
						,'main_group_akses13' => $cek_main_group->akses13
						,'main_group_akses14' => $cek_main_group->akses14
						,'main_group_akses15' => $cek_main_group->akses15
						,'main_group_akses16' => $cek_main_group->akses16
						,'main_group_akses17' => $cek_main_group->akses17
						,'main_group_akses18' => $cek_main_group->akses18
						,'main_group_akses19' => $cek_main_group->akses19
						,'main_group_akses110' => $cek_main_group->akses110
						,'main_group_akses111' => $cek_main_group->akses111
						,'main_group_akses112' => $cek_main_group->akses112
						,'main_group_akses113' => $cek_main_group->akses113
						,'main_group_akses114' => $cek_main_group->akses114
						,'main_group_akses115' => $cek_main_group->akses115
						,'main_group_akses116' => $cek_main_group->akses116
						
						,'main_group_akses21' => $cek_main_group->akses21
						,'main_group_akses22' => $cek_main_group->akses22
						,'main_group_akses23' => $cek_main_group->akses23
						,'main_group_akses24' => $cek_main_group->akses24
						,'main_group_akses25' => $cek_main_group->akses25
						,'main_group_akses26' => $cek_main_group->akses26
						
						,'main_group_akses31' => $cek_main_group->akses31
						,'main_group_akses32' => $cek_main_group->akses32
						,'main_group_akses33' => $cek_main_group->akses33
						
						,'main_group_akses41' => $cek_main_group->akses41
						,'main_group_akses42' => $cek_main_group->akses42
						
					);
					$this->session->set_userdata($ses_main_group);
				}
				//CEK AKSES MAIN GROUP
				
				
				//CEK AKSES SUB GROUP
				$cek_sub_group = $this->M_akun->get_hak_akses_group1_main_group_sub_group($data_login->id_jabatan,$data_login->kode_kantor);
				if(!empty($cek_sub_group))
				{
					$ses_sub_group = array
					(
						'sub_group_akses111' => $cek_sub_group->akses111
						,'sub_group_akses112' => $cek_sub_group->akses112
						,'sub_group_akses113' => $cek_sub_group->akses113
						,'sub_group_akses114' => $cek_sub_group->akses114
						,'sub_group_akses121' => $cek_sub_group->akses121
						,'sub_group_akses122' => $cek_sub_group->akses122
						,'sub_group_akses131' => $cek_sub_group->akses131
						,'sub_group_akses132' => $cek_sub_group->akses132
						,'sub_group_akses141' => $cek_sub_group->akses141
						,'sub_group_akses142' => $cek_sub_group->akses142
						,'sub_group_akses151' => $cek_sub_group->akses151
						,'sub_group_akses152' => $cek_sub_group->akses152
						,'sub_group_akses161' => $cek_sub_group->akses161
						,'sub_group_akses162' => $cek_sub_group->akses162
						,'sub_group_akses171' => $cek_sub_group->akses171
						,'sub_group_akses172' => $cek_sub_group->akses172
						,'sub_group_akses181' => $cek_sub_group->akses181
						,'sub_group_akses182' => $cek_sub_group->akses182
						,'sub_group_akses191' => $cek_sub_group->akses191
						,'sub_group_akses192' => $cek_sub_group->akses192
						,'sub_group_akses1101' => $cek_sub_group->akses1101
						,'sub_group_akses1102' => $cek_sub_group->akses1102
						,'sub_group_akses1111' => $cek_sub_group->akses1111
						,'sub_group_akses1112' => $cek_sub_group->akses1112
						,'sub_group_akses1121' => $cek_sub_group->akses1121
						,'sub_group_akses1122' => $cek_sub_group->akses1122
						,'sub_group_akses1131' => $cek_sub_group->akses1131
						,'sub_group_akses1132' => $cek_sub_group->akses1132
						,'sub_group_akses1141' => $cek_sub_group->akses1141
						,'sub_group_akses1142' => $cek_sub_group->akses1142
						
						,'sub_group_akses311' => $cek_sub_group->akses311
						,'sub_group_akses312' => $cek_sub_group->akses312
						,'sub_group_akses313' => $cek_sub_group->akses313
						,'sub_group_akses321' => $cek_sub_group->akses321
						,'sub_group_akses322' => $cek_sub_group->akses322
						,'sub_group_akses323' => $cek_sub_group->akses323
						,'sub_group_akses331' => $cek_sub_group->akses331
						,'sub_group_akses332' => $cek_sub_group->akses332
						,'sub_group_akses333' => $cek_sub_group->akses333
						
						,'sub_group_akses411' => $cek_sub_group->akses411
						,'sub_group_akses412' => $cek_sub_group->akses412
						,'sub_group_akses413' => $cek_sub_group->akses413
						,'sub_group_akses421' => $cek_sub_group->akses421
						,'sub_group_akses422' => $cek_sub_group->akses422
						,'sub_group_akses423' => $cek_sub_group->akses423
						
					);
					$this->session->set_userdata($ses_sub_group);
				}
				//CEK AKSES SUB GROUP
				
				header('Location: '.base_url().'admin');
    		}
    		else
    		{
    			//redirect('index.php/login','location');
				header('Location: '.base_url().'admin-login');
    		}
        }
        
        public function validasi_input_captcha()
        {
            $this->form_validation->set_rules('captcha','Captcha','required|callback_check_captcha');
            return ($this->form_validation->run() == false)? False : true;
        }
        
        
        function logout()
		{
			$this->session->unset_userdata('ses_user_admin');
			$this->session->unset_userdata('ses_pass_admin');
			$this->session->unset_userdata('ses_id_karyawan');
            $this->session->unset_userdata('ses_id_jabatan');
			$this->session->unset_userdata('ses_nama_jabatan');
			$this->session->unset_userdata('ses_nama_karyawan');
			$this->session->unset_userdata('ses_avatar_url');
			$this->session->unset_userdata('ses_tgl_ins');
			
			//UNSET SESSION GROUP1
				$this->session->unset_userdata('group1_akses1');
				$this->session->unset_userdata('group1_akses2');
				$this->session->unset_userdata('group1_akses3');
				$this->session->unset_userdata('group1_akses4');
				$this->session->unset_userdata('group1_akses5');
			//UNSET SESSION GROUP1
			
			//UNSET MAIN GROUP
				$this->session->unset_userdata('main_group_akses11');
				$this->session->unset_userdata('main_group_akses12');
				$this->session->unset_userdata('main_group_akses13');
				$this->session->unset_userdata('main_group_akses14');
				$this->session->unset_userdata('main_group_akses15');
				$this->session->unset_userdata('main_group_akses16');
				$this->session->unset_userdata('main_group_akses17');
				$this->session->unset_userdata('main_group_akses18');
				$this->session->unset_userdata('main_group_akses19');
				$this->session->unset_userdata('main_group_akses110');
				$this->session->unset_userdata('main_group_akses111');
				$this->session->unset_userdata('main_group_akses112');
				$this->session->unset_userdata('main_group_akses113');
				$this->session->unset_userdata('main_group_akses114');
				$this->session->unset_userdata('main_group_akses115');
				$this->session->unset_userdata('main_group_akses116');
				$this->session->unset_userdata('main_group_akses21');
				$this->session->unset_userdata('main_group_akses22');
				$this->session->unset_userdata('main_group_akses23');
				$this->session->unset_userdata('main_group_akses24');
				$this->session->unset_userdata('main_group_akses25');
				$this->session->unset_userdata('main_group_akses31');
				$this->session->unset_userdata('main_group_akses32');
				$this->session->unset_userdata('main_group_akses33');
				$this->session->unset_userdata('main_group_akses41');
				$this->session->unset_userdata('main_group_akses42');
			//UNSET MAIN GROUP
			
			//UNSET SUB GROUP
				$this->session->unset_userdata('sub_group_akses111');
				$this->session->unset_userdata('sub_group_akses112');
				$this->session->unset_userdata('sub_group_akses113');
				$this->session->unset_userdata('sub_group_akses114');
				$this->session->unset_userdata('sub_group_akses121');
				$this->session->unset_userdata('sub_group_akses122');
				$this->session->unset_userdata('sub_group_akses131');
				$this->session->unset_userdata('sub_group_akses132');
				$this->session->unset_userdata('sub_group_akses141');
				$this->session->unset_userdata('sub_group_akses142');
				$this->session->unset_userdata('sub_group_akses151');
				$this->session->unset_userdata('sub_group_akses152');
				$this->session->unset_userdata('sub_group_akses161');
				$this->session->unset_userdata('sub_group_akses162');
				$this->session->unset_userdata('sub_group_akses171');
				$this->session->unset_userdata('sub_group_akses172');
				$this->session->unset_userdata('sub_group_akses181');
				$this->session->unset_userdata('sub_group_akses182');
				$this->session->unset_userdata('sub_group_akses191');
				$this->session->unset_userdata('sub_group_akses192');
				$this->session->unset_userdata('sub_group_akses1101');
				$this->session->unset_userdata('sub_group_akses1102');
				$this->session->unset_userdata('sub_group_akses1111');
				$this->session->unset_userdata('sub_group_akses1112');
				$this->session->unset_userdata('sub_group_akses1121');
				$this->session->unset_userdata('sub_group_akses1122');
				$this->session->unset_userdata('sub_group_akses1131');
				$this->session->unset_userdata('sub_group_akses1132');
				$this->session->unset_userdata('sub_group_akses1141');
				$this->session->unset_userdata('sub_group_akses1142');
				$this->session->unset_userdata('sub_group_akses311');
				$this->session->unset_userdata('sub_group_akses312');
				$this->session->unset_userdata('sub_group_akses313');
				$this->session->unset_userdata('sub_group_akses321');
				$this->session->unset_userdata('sub_group_akses322');
				$this->session->unset_userdata('sub_group_akses323');
				$this->session->unset_userdata('sub_group_akses331');
				$this->session->unset_userdata('sub_group_akses332');
				$this->session->unset_userdata('sub_group_akses333');
				$this->session->unset_userdata('sub_group_akses411');
				$this->session->unset_userdata('sub_group_akses412');
				$this->session->unset_userdata('sub_group_akses413');
				$this->session->unset_userdata('sub_group_akses421');
				$this->session->unset_userdata('sub_group_akses422');
				$this->session->unset_userdata('sub_group_akses423');
			//UNSET SUB GROUP

			
			
			//redirect('index.php/login','location');
			header('Location: '.base_url().'admin-login');
		}
        
        
        
        
        function auto_remove_captcha()
		{
			list($usec,$sec) = explode(" ",microtime());
			$now = ((float)$usec + (float)$sec);
			$expiration = 60;//10menit
			$captcha_dir = @opendir("./assets/captcha/");
			while($filename = @readdir($captcha_dir))
			{
				if($filename != "." and $filename != ".." and $filename != "index.php")
				{
					$name = str_replace(".jpg","",$filename);
					if($name+$expiration < $now)
					{
						@unlink("./assets/captcha/".$filename);
					}
				}
			}
			@closedir($captcha_dir);
			//redirect(base_url(),'localtion');
		}
        
        function check_captcha()
		{
			//batas waktu
			$expiration = time()-300;
			//hapus berkas cptcha yang kadaluarsa dalam direktori
			//hapus data captcah yang kadaluarsa pada database
			$this->db->query("DELETE FROM tb_captcha where captcha_time < ".$expiration);
			//$this->db->query("DELETE FROM tb_captcha");
			$sql = "select count(*) as count from tb_captcha where word = ? and ip_address = ? and captcha_time > ?";
			$binds = array($this->input->post('captcha'),$this->input->ip_address(),$expiration);
			
			$query = $this->db->query($sql,$binds);
			$row = $query->row();
			
			if($row->count == 0)
			{
				$this->form_validation->set_message('check_captcha','Captcha yang anda masukan salah atau sudah kadaluarsa');
				return false;
			}
			else
			{
				return true;
				
			}
		}
	}

?>