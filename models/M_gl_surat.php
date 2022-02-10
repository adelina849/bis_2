<?php
	class M_gl_surat extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_karyawan_tag($kode_kantor,$id_surat,$cari)
		{
			$query = "
				SELECT 
					A.*
					,COALESCE(B.JUMLAH,0) AS JUMLAH
				FROM tb_karyawan AS A
				LEFT JOIN
				(
					SELECT id_karyawan,COUNT(id_tag_surat) AS JUMLAH 
					FROM tb_tag_surat
					WHERE MD5(id_surat) = '".$id_surat."'
					AND kode_kantor = '".$kode_kantor."'
					GROUP BY id_karyawan
				) AS B ON A.id_karyawan = B.id_karyawan
				WHERE (A.no_karyawan LIKE '%".$cari."%' OR A.nama_karyawan LIKE '%".$cari."%')
				ORDER BY  COALESCE(B.JUMLAH,0) DESC,A.nama_karyawan ASC
			";
			
			$query = $this->db->query($query);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function simpan_tag_surat($id_surat,$id_karyawan,$kode_kantor)
		{
			
			$query = "INSERT INTO tb_tag_surat
			 (
				 id_tag_surat
				 ,id_surat 
				 ,id_karyawan 
				 ,tgl_ins
				 ,kode_kantor
			 )
			 Values
			 (
				 (
					 SELECT CONCAT('".$this->session->userdata('isLocal')."','".$kode_kantor."',FRMTGL,ORD) AS id_tag_surat
					 From
					 (
						 SELECT CONCAT(Y,M) AS FRMTGL
						  ,CASE
							 WHEN (ORD >= 10 AND ORD < 99) THEN CONCAT('000',CAST(ORD AS CHAR))
							 WHEN (ORD >= 100 AND ORD < 999) THEN CONCAT('00',CAST(ORD AS CHAR))
							 WHEN (ORD >= 1000 AND ORD < 9999) THEN CONCAT('0',CAST(ORD AS CHAR))
							 WHEN ORD >= 10000 THEN CAST(ORD AS CHAR)
							 ELSE CONCAT('0000',CAST(ORD AS CHAR))
							 END As ORD
						 From
						 (
							 SELECT
							 CAST(LEFT(NOW(),4) AS CHAR) AS Y,
							 CAST(MID(NOW(),6,2) AS CHAR) AS M,
							 MID(NOW(),9,2) AS D,
							 COALESCE(MAX(CAST(RIGHT(id_tag_surat,5) AS UNSIGNED)) + 1,1) AS ORD
							 From tb_tag_surat
							 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
							 AND kode_kantor = '".$kode_kantor."'
						 ) AS A
					 ) AS AA
				 )
				 ,'".$id_surat."'
				 ,'".$id_karyawan."'
				 ,NOW()
				 ,'".$kode_kantor."'
			 )";
			 
			/*SIMPAN DAN CATAT QUERY*/
				$this->M_gl_log->simpan_query($query);
			/*SIMPAN DAN CATAT QUERY*/
		}
		
		
		function hapus_tag_surat($id_surat,$id_karyawan)
		{
			$query = "DELETE FROM tb_tag_surat WHERE id_surat = '".$id_surat."' AND id_karyawan = '".$id_karyawan."' AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ;";
			
			//$this->db->query("DELETE FROM tb_hak_akses WHERE id_jabatan = ".$id_jabatan." AND id_fasilitas = ".$id_fasilitas." AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ;");
			
			/*SIMPAN DAN CATAT QUERY*/
				$this->M_gl_log->simpan_query($query);
			/*SIMPAN DAN CATAT QUERY*/
		}
		
		function get_tag_surat($cari)
		{
			$query = "SELECT * FROM tb_tag_surat ".$cari;
			$query = $this->db->query($query);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function list_surat_limit($cari,$order_by,$limit,$offset)
		{
			$query = "
					SELECT 
						A.id_surat,
						A.kat_surat,
						A.no_surat,
						A.in_out,
						A.perihal,
						A.dari,
						A.kepada,
						A.tgl_masuk,
						A.nama_acara,
						A.tempat_acara,
						A.tgl_acara,
						A.ket_surat,
						A.tgl_ins,
						A.tgl_updt,
						A.user_ins,
						A.user_updt,
						A.kode_kantor
						,MAX(COALESCE(C.img_nama,'')) AS img_nama
						,MAX(COALESCE(C.img_file,'')) AS img_file
						,MAX(COALESCE(C.img_url,'')) AS img_url
						,MAX(COALESCE(C.ket_img,'')) AS ket_img
						,COALESCE(D.PIC,'') AS PIC
					FROM tb_surat AS A 
					LEFT JOIN tb_images AS C ON A.id_surat = C.id AND A.kode_kantor = C.kode_kantor AND C.group_by = 'suratin'
					LEFT JOIN
					(
						SELECT A.id_surat,A.kode_kantor, GROUP_CONCAT(DISTINCT B.nama_karyawan ORDER BY B.nama_karyawan ASC SEPARATOR ',') AS PIC
						FROM tb_tag_surat AS A
						LEFT JOIN tb_karyawan AS B ON A.id_karyawan = B.id_karyawan
						GROUP BY A.id_surat,A.kode_kantor
					) AS D ON A.kode_kantor = D.kode_kantor AND A.id_surat = D.id_surat
					".$cari." 
					GROUP BY
						A.id_surat,
						A.kat_surat,
						A.no_surat,
						A.in_out,
						A.perihal,
						A.dari,
						A.kepada,
						A.tgl_masuk,
						A.nama_acara,
						A.tempat_acara,
						A.tgl_acara,
						A.ket_surat,
						A.tgl_ins,
						A.tgl_updt,
						A.user_ins,
						A.user_updt,
						A.kode_kantor,
						COALESCE(D.PIC,'')
					".$order_by." LIMIT ".$offset.",".$limit;
			
			$query = $this->db->query($query);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_surat_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(A.id_surat) AS JUMLAH FROM tb_surat AS A ".$cari);
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
		
			$kat_surat,
			$no_surat,
			$in_out,
			$perihal,
			$dari,
			$kepada,
			$tgl_masuk,
			$nama_acara,
			$tempat_acara,
			$tgl_acara,
			$ket_surat,
			$user_ins,
			$kode_kantor

		)
		{
			$strquery = "
				INSERT INTO tb_surat
				(
					id_surat,
					kat_surat,
					no_surat,
					in_out,
					perihal,
					dari,
					kepada,
					tgl_masuk,
					nama_acara,
					tempat_acara,
					tgl_acara,
					ket_surat,
					tgl_ins,
					tgl_updt,
					user_ins,
					user_updt,
					kode_kantor
				)
				VALUES
				(
					(
						SELECT CONCAT('".$in_out."','".$kode_kantor."',FRMTGL,ORD) AS id_surat
						From
						(
							SELECT CONCAT(Y,M,D) AS FRMTGL
							 ,CASE
								WHEN (ORD >= 10 AND ORD < 99) THEN CONCAT('000',CAST(ORD AS CHAR))
								WHEN (ORD >= 100 AND ORD < 999) THEN CONCAT('00',CAST(ORD AS CHAR))
								WHEN (ORD >= 1000 AND ORD < 9999) THEN CONCAT('0',CAST(ORD AS CHAR))
								WHEN ORD >= 10000 THEN CAST(ORD AS CHAR)
								ELSE CONCAT('0000',CAST(ORD AS CHAR))
								END As ORD
							From
							(
								SELECT
								CAST(LEFT(NOW(),4) AS CHAR) AS Y,
								CAST(MID(NOW(),6,2) AS CHAR) AS M,
								MID(NOW(),9,2) AS D,
								COALESCE(MAX(CAST(RIGHT(id_surat,5) AS UNSIGNED)) + 1,1) AS ORD
								From tb_surat
								WHERE DATE(tgl_ins) = DATE(NOW())
								AND in_out = '".$in_out."'
								AND kode_kantor = '".$kode_kantor."'
							) AS A
						) AS AA
					),
					'".$kat_surat."',
					'".$no_surat."',
					'".$in_out."',
					'".$perihal."',
					'".$dari."',
					'".$kepada."',
					'".$tgl_masuk."',
					'".$nama_acara."',
					'".$tempat_acara."',
					'".$tgl_acara."',
					'".$ket_surat."',
					NOW(),
					NOW(),
					'".$user_ins."',
					'',
					'".$kode_kantor."'
				)
			";
			
			/*SIMPAN DAN CATAT QUERY*/
				$this->M_gl_log->simpan_query($strquery);
			/*SIMPAN DAN CATAT QUERY*/
		}
		
		function edit
		(
			$id_surat,
			$kat_surat,
			$no_surat,
			$in_out,
			$perihal,
			$dari,
			$kepada,
			$tgl_masuk,
			$nama_acara,
			$tempat_acara,
			$tgl_acara,
			$ket_surat,
			$user_updt,
			$kode_kantor
		)
		{
			$strquery = "
					UPDATE tb_surat SET
						kat_surat = '".$kat_surat."',
						no_surat = '".$no_surat."',
						in_out = '".$in_out."',
						perihal = '".$perihal."',
						dari = '".$dari."',
						kepada = '".$kepada."',
						tgl_masuk = '".$tgl_masuk."',
						nama_acara = '".$nama_acara."',
						tempat_acara = '".$tempat_acara."',
						tgl_acara = '".$tgl_acara."',
						ket_surat = '".$ket_surat."',
						tgl_updt = NOW(),
						user_updt = '".$user_updt."'
					WHERE kode_kantor = '".$kode_kantor."' AND id_surat = '".$id_surat
					."'
					";
					
			/*SIMPAN DAN CATAT QUERY*/
				$this->M_gl_log->simpan_query($strquery);
			/*SIMPAN DAN CATAT QUERY*/
		}
		
		function hapus($cari)
		{
			/*HAPUS JABATAN*/
				$strquery = "DELETE FROM tb_surat ".$cari."";
			/*HAPUS JABATAN*/
			
			/*SIMPAN DAN CATAT QUERY*/
				$this->M_gl_log->simpan_query($strquery);
			/*SIMPAN DAN CATAT QUERY*/
		}
		
		
		function get_surat($cari)
		{
			$query = "SELECT * FROM tb_surat ".$cari;
			$query = $this->db->query($query);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
	}
?>