<?php
	class M_akun extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		
		function get_no_karyawan()
		{
			$query = $this->db->query(
			"
				SELECT CONCAT(FRMTGL,ORD) AS no_karyawan
				FROM
				(
					SELECT CONCAT(Y,M) AS FRMTGL
					 ,CASE
						WHEN ORD >= 10 THEN CONCAT('00',CAST(ORD AS CHAR))
						WHEN ORD >= 100 THEN CONCAT('0',CAST(ORD AS CHAR))
						WHEN ORD >= 1000 THEN CAST(ORD AS CHAR)
						ELSE CONCAT('000',CAST(ORD AS CHAR))
						END AS ORD
					FROM
					(
						SELECT 
						CAST(LEFT(NOW(),4) AS CHAR) AS Y,
						CAST(MID(NOW(),6,2) AS CHAR) AS M,
						MID(NOW(),9,2) AS D,
						(MAX(CAST(RIGHT(no_karyawan,4) AS UNSIGNED)) + 1) AS ORD FROM tb_karyawan
						WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
					) AS A
				) AS AA
			"
			);
			
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function get_cek_login($user,$pass)
		{
			//$query = $this->db->get_where('tb_akun', array('id_akun' => $id_akun), $limit, $offset);
			$query = $this->db->get_where('tb_akun', array('user' => $user,'pass'=>$pass));
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function get_akun_id($id_akun)
		{
			//$query = $this->db->get_where('tb_akun', array('id_akun' => $id_akun), $limit, $offset);
			$query = $this->db->get_where('tb_akun', array('id_akun' => $id_akun,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function list_akun_limit($cari,$limit,$offset)
		{
			$query = $this->db->query("
										SELECT 
										A.id_akun
										,B.id_karyawan
										,C.id_jabatan
										,A.user
										,A.pass
										,A.pertanyaan1
										,A.jawaban1
										,A.pertanyaan2
										,A.jawaban2
										,A.ket_akun
										,B.nik_karyawan
										,B.nama_karyawan
										,B.avatar
										,B.avatar_url
										,C.nama_jabatan
									
										FROM tb_akun AS A
										LEFT JOIN tb_karyawan AS B ON A.id_karyawan = B.id_karyawan
										LEFT JOIN tb_jabatan AS C ON B.id_jabatan = C.id_jabatan
										".$cari." ORDER BY B.nama_karyawan ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_akun_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(id_akun) AS JUMLAH FROM tb_akun AS A
										LEFT JOIN tb_karyawan AS B ON A.id_karyawan = B.id_karyawan
										LEFT JOIN tb_jabatan AS C ON B.id_jabatan = C.id_jabatan ".$cari);
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function simpan
		(
			$id_karyawan
			,$pertanyaan1
			,$jawaban1
			,$pertanyaan2
			,$jawaban2
			,$user
			,$pass
			,$ket_akun
			,$kode_kantor
			,$user_updt
		)
		{
			$data = array
			(
			   'id_karyawan' => $id_karyawan,
			   'pertanyaan1' => $pertanyaan1,
			   'jawaban1' => $jawaban1,
			   'pertanyaan2' => $pertanyaan2,
			   'jawaban2' => $jawaban2,
			   'user' => $user,
			   'pass' => $pass,
			   'ket_akun' => $ket_akun,
			   'kode_kantor' => $kode_kantor,
			   'user_updt' => $user_updt
			);

			$this->db->insert('tb_akun', $data); 
		}
		
		function edit
		(
			$id_akun
			,$id_karyawan
			,$pertanyaan1
			,$jawaban1
			,$pertanyaan2
			,$jawaban2
			,$user
			,$pass
			,$ket_akun
			,$kode_kantor
			,$user_updt
		)
		{
			$id = date('ymdHis'); 
			$date = date('Y-m-d'); 
			$jam = date('Y-m-d H:i:s'); 
			$data = array
			(
			   'id_karyawan' => $id_karyawan,
			   'pertanyaan1' => $pertanyaan1,
			   'jawaban1' => $jawaban1,
			   'pertanyaan2' => $pertanyaan2,
			   'jawaban2' => $jawaban2,
			   'user' => $user,
			   'pass' => $pass,
			   'ket_akun' => $ket_akun,
			   'tgl_updt' => $jam,
			   'kode_kantor' => $kode_kantor,
			   'user_updt' => $user_updt
			);
			
			//$this->db->where('id_akun', $id_akun);
			$this->db->update('tb_akun', $data, array('id_akun' => $id_akun,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
		}
		
		function get_akun($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_akun', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
            if($query->num_rows() > 0)
            {
                return $query->num_rows();
            }
            else
            {
                return false;
            }
        }
		
		/*function get_login($user,$pass)
        {
            //$query = $this->db->get_where('tb_akun', array('user' => $user),'pass'=> $pass);
			$query = $this->db->query('SELECT * FROM tb_akun AS A
			LEFT JOIN tb_karyawan AS B ON A.id_karyawan = B.id_karyawan
			LEFT JOIN tb_jabatan AS C ON B.id_jabatan = C.id_jabatan 
			WHERE A.user = "'.$user.'" AND A.pass = "'.$pass.'"');
            
			if($query->num_rows() > 0)
            {
                return $query->row();
            }
            else
            {
                return false;
            }
        }*/
		
		function edit_password($id_karyawan,$user,$pass)
		{
			$query = "UPDATE tb_akun SET user ='".$user."',  pass = '".md5($pass)."'   WHERE id_karyawan = '".$id_karyawan."' AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ";
			$this->db->query($query);
		}
		
		function get_login($user,$pass)
        {
            //$query = $this->db->get_where('tb_akun', array('user' => $user),'pass'=> $pass);
			$query = $this->db->query("
				SELECT A.* 
					,A.tgl_insert AS tgl_ins
					,COALESCE(B.id_jabatan,'') AS id_jabatan
					,COALESCE(B.kode_dept,'') AS kode_dept
					,COALESCE(B.no_karyawan,'') AS no_karyawan
					,COALESCE(B.nik_karyawan,'') AS nik_karyawan
					,COALESCE(B.nama_karyawan,'') AS nama_karyawan
					,COALESCE(B.avatar,'') AS avatar
					,COALESCE(B.avatar_url,'') AS avatar_url
					,COALESCE(B.pnd,'') AS pnd
					,COALESCE(B.tlp,'') AS tlp
					,COALESCE(B.email,'') AS email
					,COALESCE(B.alamat,'') AS alamat
					,COALESCE(B.ket_karyawan,'') AS ket_karyawan
					,COALESCE(C.nama_jabatan,'') AS nama_jabatan
				FROM tb_akun AS A
				LEFT JOIN tb_karyawan AS B ON A.id_karyawan = B.id_karyawan -- AND A.kode_kantor = B.kode_kantor
				LEFT JOIN tb_jabatan AS C ON B.id_jabatan = C.id_jabatan -- AND A.kode_kantor = C.kode_kantor
				WHERE A.user = '".$user."' AND A.pass = '".$pass."'");
            
			if($query->num_rows() > 0)
            {
                return $query->row();
            }
            else
            {
                return false;
            }
        }
		
		function hapus($id)
		{
			$this->db->query("DELETE FROM tb_akun WHERE id_akun = ".$id." AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'");
		}
		
		function get_hak_akses_group1($id_jabatan,$kode_kantor)
		{
			$query = $this->db->query("SELECT 
										SUM(akses1) AS akses1,
										SUM(akses2) AS akses2,
										SUM(akses3) AS akses3,
										SUM(akses4) AS akses4,
										SUM(akses5) AS akses5
									FROM
									(
										SELECT
											CASE WHEN group1 = 1 THEN COUNT(C.id_fasilitas) ELSE 0 END as Akses1,
											CASE WHEN group1 = 2 THEN COUNT(C.id_fasilitas) ELSE 0 END as Akses2,
											CASE WHEN group1 = 3 THEN COUNT(C.id_fasilitas) ELSE 0 END as Akses3,
											CASE WHEN group1 = 4 THEN COUNT(C.id_fasilitas) ELSE 0 END as Akses4,
											CASE WHEN group1 = 5 THEN COUNT(C.id_fasilitas) ELSE 0 END as Akses5
										FROM tb_jabatan AS A
										LEFT JOIN tb_hak_akses AS B ON A.id_jabatan = B.id_jabatan
										LEFT JOIN tb_fasilitas AS C ON B.id_fasilitas = C.id_fasilitas
										WHERE A.id_jabatan = '".$id_jabatan."' AND A.kode_kantor = '".$kode_kantor."'
										GROUP BY group1
									) AS A");
            
			if($query->num_rows() > 0)
            {
                return $query->row();
            }
            else
            {
                return false;
            }
		}
		
		function get_hak_akses_group1_main_group($id_jabatan,$kode_kantor)
		{
			$query = $this->db->query("
									SELECT
										SUM(akses11) AS akses11,
										SUM(akses12) AS akses12,
										SUM(akses13) AS akses13,
										SUM(akses14) AS akses14,
										SUM(akses15) AS akses15,
										SUM(akses16) AS akses16,
										SUM(akses17) AS akses17,
										SUM(akses18) AS akses18,
										SUM(akses19) AS akses19,
										SUM(akses110) AS akses110,
										SUM(akses111) AS akses111,
										SUM(akses112) AS akses112,
										SUM(akses113) AS akses113,
										SUM(akses114) AS akses114,
										SUM(akses115) AS akses115,
										SUM(akses116) AS akses116,
										
										
										SUM(akses21) AS akses21,
										SUM(akses22) AS akses22,
										SUM(akses23) AS akses23,
										SUM(akses24) AS akses24,
										SUM(akses25) AS akses25,
										SUM(akses26) AS akses26,
										
										SUM(akses31) AS akses31,
										SUM(akses32) AS akses32,
										SUM(akses33) AS akses33,
										
										SUM(akses41) AS akses41,
										SUM(akses42) AS akses42
										
									FROM
									(
										SELECT  
											CASE WHEN (group1=1 AND main_group=1) THEN SUM(JUM_AKSES) ELSE 0 END AS akses11,
											CASE WHEN (group1=1 AND main_group=2) THEN SUM(JUM_AKSES) ELSE 0 END AS akses12,
											CASE WHEN (group1=1 AND main_group=3) THEN SUM(JUM_AKSES) ELSE 0 END AS akses13,
											CASE WHEN (group1=1 AND main_group=4) THEN SUM(JUM_AKSES) ELSE 0 END AS akses14,
											CASE WHEN (group1=1 AND main_group=5) THEN SUM(JUM_AKSES) ELSE 0 END AS akses15,
											CASE WHEN (group1=1 AND main_group=6) THEN SUM(JUM_AKSES) ELSE 0 END AS akses16,
											CASE WHEN (group1=1 AND main_group=7) THEN SUM(JUM_AKSES) ELSE 0 END AS akses17,
											CASE WHEN (group1=1 AND main_group=8) THEN SUM(JUM_AKSES) ELSE 0 END AS akses18,
											CASE WHEN (group1=1 AND main_group=9) THEN SUM(JUM_AKSES) ELSE 0 END AS akses19,
											CASE WHEN (group1=1 AND main_group=10) THEN SUM(JUM_AKSES) ELSE 0 END AS akses110,
											CASE WHEN (group1=1 AND main_group=11) THEN SUM(JUM_AKSES) ELSE 0 END AS akses111,
											CASE WHEN (group1=1 AND main_group=12) THEN SUM(JUM_AKSES) ELSE 0 END AS akses112,
											CASE WHEN (group1=1 AND main_group=13) THEN SUM(JUM_AKSES) ELSE 0 END AS akses113,
											CASE WHEN (group1=1 AND main_group=14) THEN SUM(JUM_AKSES) ELSE 0 END AS akses114,
											CASE WHEN (group1=1 AND main_group=15) THEN SUM(JUM_AKSES) ELSE 0 END AS akses115,
											CASE WHEN (group1=1 AND main_group=16) THEN SUM(JUM_AKSES) ELSE 0 END AS akses116,
											
											
											CASE WHEN (group1=2 AND main_group=1) THEN SUM(JUM_AKSES) ELSE 0 END AS akses21,
											CASE WHEN (group1=2 AND main_group=2) THEN SUM(JUM_AKSES) ELSE 0 END AS akses22,
											CASE WHEN (group1=2 AND main_group=3) THEN SUM(JUM_AKSES) ELSE 0 END AS akses23,
											CASE WHEN (group1=2 AND main_group=4) THEN SUM(JUM_AKSES) ELSE 0 END AS akses24,
											CASE WHEN (group1=2 AND main_group=5) THEN SUM(JUM_AKSES) ELSE 0 END AS akses25,
											CASE WHEN (group1=2 AND main_group=6) THEN SUM(JUM_AKSES) ELSE 0 END AS akses26,
											
											CASE WHEN (group1=3 AND main_group=1) THEN SUM(JUM_AKSES) ELSE 0 END AS akses31,
											CASE WHEN (group1=3 AND main_group=2) THEN SUM(JUM_AKSES) ELSE 0 END AS akses32,
											CASE WHEN (group1=3 AND main_group=3) THEN SUM(JUM_AKSES) ELSE 0 END AS akses33,
											
											CASE WHEN (group1=4 AND main_group=1) THEN SUM(JUM_AKSES) ELSE 0 END AS akses41,
											CASE WHEN (group1=4 AND main_group=2) THEN SUM(JUM_AKSES) ELSE 0 END AS akses42
											
											
										FROM  
											   (
												SELECT group1,main_group,COUNT(C.id_fasilitas) AS JUM_AKSES FROM tb_jabatan AS A
												LEFT JOIN tb_hak_akses AS B ON A.id_jabatan = B.id_jabatan
												LEFT JOIN
												(
													SELECT * FROM tb_fasilitas WHERE sub_group2 = '1'
												) AS C ON B.id_fasilitas = C.id_fasilitas
												-- LEFT JOIN tb_fasilitas AS C ON B.id_fasilitas = C.id_fasilitas
												WHERE A.id_jabatan = '".$id_jabatan."' AND A.kode_kantor = '".$kode_kantor."'
												GROUP BY group1,main_group
											   ) AS A
											GROUP BY group1,main_group
										) AS A;
	
			");
            
			if($query->num_rows() > 0)
            {
                return $query->row();
            }
            else
            {
                return false;
            }
		}
		
		function get_hak_akses_group1_main_group_sub_group($id_jabatan,$kode_kantor)
		{
			$query = $this->db->query("
									SELECT
										SUM(akses111) AS akses111,
										SUM(akses112) AS akses112,
										SUM(akses113) AS akses113,
										SUM(akses114) AS akses114,
										SUM(akses121) AS akses121,
										SUM(akses122) AS akses122,
										SUM(akses131) AS akses131,
										SUM(akses132) AS akses132,
										SUM(akses141) AS akses141,
										SUM(akses142) AS akses142,
										SUM(akses151) AS akses151,
										SUM(akses152) AS akses152,
										SUM(akses161) AS akses161,
										SUM(akses162) AS akses162,
										SUM(akses171) AS akses171,
										SUM(akses172) AS akses172,
										SUM(akses181) AS akses181,
										SUM(akses182) AS akses182,
										SUM(akses191) AS akses191,
										SUM(akses192) AS akses192,
										SUM(akses1101) AS akses1101,
										SUM(akses1102) AS akses1102,
										SUM(akses1111) AS akses1111,
										SUM(akses1112) AS akses1112,
										SUM(akses1121) AS akses1121,
										SUM(akses1122) AS akses1122,
										SUM(akses1131) AS akses1131,
										SUM(akses1132) AS akses1132,
										SUM(akses1141) AS akses1141,
										SUM(akses1142) AS akses1142,
										
										SUM(akses311) AS akses311,
										SUM(akses312) AS akses312,
										SUM(akses313) AS akses313,
										SUM(akses321) AS akses321,
										SUM(akses322) AS akses322,
										SUM(akses323) AS akses323,
										SUM(akses331) AS akses331,
										SUM(akses332) AS akses332,
										SUM(akses333) AS akses333,
										
										SUM(akses411) AS akses411,
										SUM(akses412) AS akses412,
										SUM(akses413) AS akses413,
										SUM(akses421) AS akses421,
										SUM(akses422) AS akses422,
										SUM(akses423) AS akses423
										
									FROM
									(
										SELECT  
											CASE WHEN (group1=1 AND main_group=1 AND sub_group=1) THEN SUM(JUM_AKSES) ELSE 0 END AS akses111,
											CASE WHEN (group1=1 AND main_group=1 AND sub_group=2) THEN SUM(JUM_AKSES) ELSE 0 END AS akses112,
											CASE WHEN (group1=1 AND main_group=1 AND sub_group=3) THEN SUM(JUM_AKSES) ELSE 0 END AS akses113,
											CASE WHEN (group1=1 AND main_group=1 AND sub_group=4) THEN SUM(JUM_AKSES) ELSE 0 END AS akses114,
											CASE WHEN (group1=1 AND main_group=2 AND sub_group=1) THEN SUM(JUM_AKSES) ELSE 0 END AS akses121,
											CASE WHEN (group1=1 AND main_group=2 AND sub_group=2) THEN SUM(JUM_AKSES) ELSE 0 END AS akses122,
											CASE WHEN (group1=1 AND main_group=3 AND sub_group=1) THEN SUM(JUM_AKSES) ELSE 0 END AS akses131,
											CASE WHEN (group1=1 AND main_group=3 AND sub_group=2) THEN SUM(JUM_AKSES) ELSE 0 END AS akses132,
											CASE WHEN (group1=1 AND main_group=4 AND sub_group=1) THEN SUM(JUM_AKSES) ELSE 0 END AS akses141,
											CASE WHEN (group1=1 AND main_group=4 AND sub_group=2) THEN SUM(JUM_AKSES) ELSE 0 END AS akses142,
											CASE WHEN (group1=1 AND main_group=5 AND sub_group=1) THEN SUM(JUM_AKSES) ELSE 0 END AS akses151,
											CASE WHEN (group1=1 AND main_group=5 AND sub_group=2) THEN SUM(JUM_AKSES) ELSE 0 END AS akses152,
											CASE WHEN (group1=1 AND main_group=6 AND sub_group=1) THEN SUM(JUM_AKSES) ELSE 0 END AS akses161,
											CASE WHEN (group1=1 AND main_group=6 AND sub_group=2) THEN SUM(JUM_AKSES) ELSE 0 END AS akses162,
											CASE WHEN (group1=1 AND main_group=7 AND sub_group=1) THEN SUM(JUM_AKSES) ELSE 0 END AS akses171,
											CASE WHEN (group1=1 AND main_group=7 AND sub_group=2) THEN SUM(JUM_AKSES) ELSE 0 END AS akses172,
											CASE WHEN (group1=1 AND main_group=8 AND sub_group=1) THEN SUM(JUM_AKSES) ELSE 0 END AS akses181,
											CASE WHEN (group1=1 AND main_group=8 AND sub_group=2) THEN SUM(JUM_AKSES) ELSE 0 END AS akses182,
											CASE WHEN (group1=1 AND main_group=9 AND sub_group=1) THEN SUM(JUM_AKSES) ELSE 0 END AS akses191,
											CASE WHEN (group1=1 AND main_group=9 AND sub_group=2) THEN SUM(JUM_AKSES) ELSE 0 END AS akses192,
											CASE WHEN (group1=1 AND main_group=10 AND sub_group=1) THEN SUM(JUM_AKSES) ELSE 0 END AS akses1101,
											CASE WHEN (group1=1 AND main_group=10 AND sub_group=2) THEN SUM(JUM_AKSES) ELSE 0 END AS akses1102,
											CASE WHEN (group1=1 AND main_group=11 AND sub_group=1) THEN SUM(JUM_AKSES) ELSE 0 END AS akses1111,
											CASE WHEN (group1=1 AND main_group=11 AND sub_group=2) THEN SUM(JUM_AKSES) ELSE 0 END AS akses1112,
											CASE WHEN (group1=1 AND main_group=12 AND sub_group=1) THEN SUM(JUM_AKSES) ELSE 0 END AS akses1121,
											CASE WHEN (group1=1 AND main_group=12 AND sub_group=2) THEN SUM(JUM_AKSES) ELSE 0 END AS akses1122,
											CASE WHEN (group1=1 AND main_group=13 AND sub_group=1) THEN SUM(JUM_AKSES) ELSE 0 END AS akses1131,
											CASE WHEN (group1=1 AND main_group=13 AND sub_group=2) THEN SUM(JUM_AKSES) ELSE 0 END AS akses1132,
											CASE WHEN (group1=1 AND main_group=14 AND sub_group=1) THEN SUM(JUM_AKSES) ELSE 0 END AS akses1141,
											CASE WHEN (group1=1 AND main_group=14 AND sub_group=2) THEN SUM(JUM_AKSES) ELSE 0 END AS akses1142,
											
											
											CASE WHEN (group1=3 AND main_group=1 AND sub_group=1) THEN SUM(JUM_AKSES) ELSE 0 END AS akses311,
											CASE WHEN (group1=3 AND main_group=1 AND sub_group=2) THEN SUM(JUM_AKSES) ELSE 0 END AS akses312,
											CASE WHEN (group1=3 AND main_group=1 AND sub_group=3) THEN SUM(JUM_AKSES) ELSE 0 END AS akses313,
											CASE WHEN (group1=3 AND main_group=2 AND sub_group=1) THEN SUM(JUM_AKSES) ELSE 0 END AS akses321,
											CASE WHEN (group1=3 AND main_group=2 AND sub_group=2) THEN SUM(JUM_AKSES) ELSE 0 END AS akses322,
											CASE WHEN (group1=3 AND main_group=2 AND sub_group=3) THEN SUM(JUM_AKSES) ELSE 0 END AS akses323,
											CASE WHEN (group1=3 AND main_group=3 AND sub_group=1) THEN SUM(JUM_AKSES) ELSE 0 END AS akses331,
											CASE WHEN (group1=3 AND main_group=3 AND sub_group=2) THEN SUM(JUM_AKSES) ELSE 0 END AS akses332,
											CASE WHEN (group1=3 AND main_group=3 AND sub_group=3) THEN SUM(JUM_AKSES) ELSE 0 END AS akses333,
											
											CASE WHEN (group1=4 AND main_group=1 AND sub_group=1) THEN SUM(JUM_AKSES) ELSE 0 END AS akses411,
											CASE WHEN (group1=4 AND main_group=1 AND sub_group=2) THEN SUM(JUM_AKSES) ELSE 0 END AS akses412,
											CASE WHEN (group1=4 AND main_group=1 AND sub_group=3) THEN SUM(JUM_AKSES) ELSE 0 END AS akses413,
											CASE WHEN (group1=4 AND main_group=2 AND sub_group=1) THEN SUM(JUM_AKSES) ELSE 0 END AS akses421,
											CASE WHEN (group1=4 AND main_group=2 AND sub_group=2) THEN SUM(JUM_AKSES) ELSE 0 END AS akses422,
											CASE WHEN (group1=4 AND main_group=2 AND sub_group=3) THEN SUM(JUM_AKSES) ELSE 0 END AS akses423
											
										FROM 
										   (
												SELECT group1,main_group,sub_group,COUNT(C.id_fasilitas) AS JUM_AKSES FROM tb_jabatan AS A
												LEFT JOIN tb_hak_akses AS B ON A.id_jabatan = B.id_jabatan
												LEFT JOIN
												(
													SELECT * FROM tb_fasilitas WHERE sub_group2 = '1'
												) AS C ON B.id_fasilitas = C.id_fasilitas
												-- LEFT JOIN tb_fasilitas AS C ON B.id_fasilitas = C.id_fasilitas
												WHERE A.id_jabatan = '".$id_jabatan."' AND A.kode_kantor = '".$kode_kantor."'
												GROUP BY group1,main_group,sub_group
										   ) AS A
										GROUP BY group1,main_group,sub_group
									) AS A");
            
			if($query->num_rows() > 0)
            {
                return $query->row();
            }
            else
            {
                return false;
            }
		}
	}
?>