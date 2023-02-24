<?php
	class M_gl_pengajuan extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_pengajuan_limit($cari,$order_by,$limit,$offset)
		{
			$query = "
						SELECT
							DISTINCT
							A.*
							
							,COALESCE(B.nik,'') AS NIK_PENGAJU
							,COALESCE(B.nama_lengkap,'') AS NAMA_PENGAJU
							,COALESCE(B.nama_perusahaan,'') AS nama_perusahaan_pengaju
							
							-- ,CONCAT(COALESCE(B.alamat_rumah_sekarang,''),', ',COALESCE(B.DESA_PENGAJU,''),', ',COALESCE(B.KEC_PENGAJU,'')) AS ALAMAT_PENGAJU
							
							,CONCAT(UCASE(LEFT(
							CONCAT(COALESCE(B.alamat_rumah_sekarang,''),', ',COALESCE(B.DESA_PENGAJU,''),', ',COALESCE(B.KEC_PENGAJU,''))
							, 1)), LCASE(SUBSTRING(
													CONCAT(COALESCE(B.alamat_rumah_sekarang,''),', ',COALESCE(B.DESA_PENGAJU,''),', ',COALESCE(B.KEC_PENGAJU,''))
													, 2))) AS ALAMAT_PENGAJU
							
							,COALESCE(A.no_hp,B.hp) AS HP_PENGAJU
							,COALESCE(B.JENIS_PENGAJU,'') AS JENIS_PENGAJU
							,COALESCE(B.isLembaga,'') AS PENGAJU_ISLEMBAGA
							
							
							
							,COALESCE(C.nik,'') AS NIK_MUSTAHIK
							,COALESCE(C.nama_lengkap,'') AS NAMA_MUSTAHIK
							,COALESCE(C.nama_perusahaan,'') AS nama_perusahaan_mustahik
							,COALESCE(C.KEC_MUSTAHIK,'') AS KEC_MUSTAHIK
							
							-- ,CONCAT(COALESCE(C.alamat_rumah_sekarang,''),', ',COALESCE(C.DESA_MUSTAHIK,''),', ',COALESCE(C.KEC_MUSTAHIK,'')) AS ALAMAT_MUSTAHIK
							,CONCAT(UCASE(LEFT(
							CONCAT(COALESCE(C.alamat_rumah_sekarang,''),', ',COALESCE(C.DESA_MUSTAHIK,''),', ',COALESCE(C.KEC_MUSTAHIK,''))
							, 1)), LCASE(SUBSTRING(
													CONCAT(COALESCE(C.alamat_rumah_sekarang,''),', ',COALESCE(C.DESA_MUSTAHIK,''),', ',COALESCE(C.KEC_MUSTAHIK,''))
													, 2))) AS ALAMAT_MUSTAHIK
							
							,COALESCE(C.hp,'') AS HP_MUSTAHIK
							,COALESCE(C.JENIS_MUSTAHIK,'') AS JENIS_MUSTAHIK
							,COALESCE(C.isLembaga,'') AS MUSTAHIK_ISLEMBAGA
							
							,(COALESCE(D.img_nama,'')) AS img_nama
							,(COALESCE(D.img_file,'')) AS img_file
							,(COALESCE(D.img_url,'')) AS img_url
							,(COALESCE(D.ket_img,'')) AS ket_img
							
							,(COALESCE(E.prog,'')) AS prog
							,(COALESCE(E.ashnaf,'')) AS ashnaf
							,(COALESCE(E.uraian,'')) AS uraian
							,LEFT((COALESCE(E.kd_prog,'')),1) AS no_prog
							,CASE WHEN A.isLembaga = 'YA' THEN
								A.jumlah_penerima
							ELSE
								0
							END AS PENERIMA_LEMBAGA
							,CASE WHEN A.isLembaga = 'TIDAK' THEN
								A.jumlah_penerima
							ELSE
								0
							END AS PENERIMA_PERORANGAN
							
						FROM tb_pengajuan AS A
						LEFT JOIN
						(
							SELECT
								DISTINCT
								A.*
								,COALESCE(B.name,'') AS KEC_PENGAJU
								,COALESCE(C.name,'') AS DESA_PENGAJU
								,COALESCE(D.nama_kat_costumer,'') AS JENIS_PENGAJU
							FROM tb_costumer AS A
							LEFT JOIN 
							(
								SELECT DISTINCT id,kabkot_id,name
								FROM tb_kecamatan
								GROUP BY id,kabkot_id,name
							) AS B ON A.kecamatan = B.id
							LEFT JOIN 
							(
								SELECT DISTINCT id, kec_id, name
								FROM tb_desa
								GROUP BY id, kec_id, name
							) AS C ON A.desa = C.id
							LEFT JOIN 
							(
								SELECT DISTINCT kode_kantor,id_kat_costumer,nama_kat_costumer
								FROM tb_kat_costumer
								GROUP BY kode_kantor,id_kat_costumer,nama_kat_costumer
							) AS D ON A.kode_kantor = D.kode_kantor AND A.id_kat_costumer = D.id_kat_costumer
						) AS B ON A.kode_kantor = B.kode_kantor AND A.id_costumer_pangaju = B.id_costumer
						LEFT JOIN
						(
							SELECT
								DISTINCT
								A.*
								,COALESCE(B.name,'') AS KEC_MUSTAHIK
								,COALESCE(C.name,'') AS DESA_MUSTAHIK
								,COALESCE(D.nama_kat_costumer,'') AS JENIS_MUSTAHIK
							FROM tb_costumer AS A
							LEFT JOIN 
							(
								SELECT DISTINCT id,kabkot_id,name
								FROM tb_kecamatan
								GROUP BY id,kabkot_id,name
							) AS B ON A.kecamatan = B.id
							LEFT JOIN 
							(
								SELECT DISTINCT id, kec_id, name
								FROM tb_desa
								GROUP BY id, kec_id, name
							) AS C ON A.desa = C.id
							LEFT JOIN 
							(
								SELECT DISTINCT kode_kantor,id_kat_costumer,nama_kat_costumer
								FROM tb_kat_costumer
								GROUP BY kode_kantor,id_kat_costumer,nama_kat_costumer
							) AS D ON A.kode_kantor = D.kode_kantor AND A.id_kat_costumer = D.id_kat_costumer
						) AS C ON A.kode_kantor = C.kode_kantor AND A.id_costumer_ashnaf = C.id_costumer
						LEFT JOIN 
						(
							SELECT 
								id,kode_kantor,group_by
								,MAX(COALESCE(img_nama,'')) AS img_nama
								,MAX(COALESCE(img_file,'')) AS img_file
								,MAX(COALESCE(img_url,'')) AS img_url
								,MAX(COALESCE(ket_img,'')) AS ket_img
							FROM
							tb_images
							GROUP BY id,kode_kantor,group_by
						) AS D ON A.id_pengajuan = D.id AND A.kode_kantor = D.kode_kantor AND D.group_by = 'ajuan'
						LEFT JOIN 
						(
							SELECT DISTINCT kode_kantor,kd_prog,prog,ashnaf,uraian
							FROM tb_kode_program
							WHERE isAktif = 'YA'
							GROUP BY kode_kantor,kd_prog,prog,ashnaf,uraian
						) AS E ON A.kode_kantor = E.kode_kantor AND A.kode_program = E.kd_prog
						".$cari."
						".$order_by."
						LIMIT ".$offset.",".$limit."
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
		
		function count_pengajuan_limit($cari)
		{
			$query = "
						SELECT 
							DISTINCT
							COUNT(A.id_pengajuan) AS JUMLAH
						FROM tb_pengajuan AS A
						LEFT JOIN
						(
							SELECT
								A.*
								,COALESCE(B.name,'') AS KEC_PENGAJU
								,COALESCE(C.name,'') AS DESA_PENGAJU
							FROM tb_costumer AS A
							LEFT JOIN 
							(
								SELECT DISTINCT id,kabkot_id,name
								FROM tb_kecamatan
								GROUP BY id,kabkot_id,name
							) AS B ON A.kecamatan = B.id
							LEFT JOIN 
							(
								SELECT DISTINCT id, kec_id, name
								FROM tb_desa
								GROUP BY id, kec_id, name
							) AS C ON A.desa = C.id
							LEFT JOIN 
							(
								SELECT DISTINCT kode_kantor,id_kat_costumer,nama_kat_costumer
								FROM tb_kat_costumer
								GROUP BY kode_kantor,id_kat_costumer,nama_kat_costumer
							) AS D ON A.kode_kantor = D.kode_kantor AND A.id_kat_costumer = D.id_kat_costumer
						) AS B ON A.kode_kantor = B.kode_kantor AND A.id_costumer_pangaju = B.id_costumer
						LEFT JOIN
						(
							SELECT
								A.*
								,COALESCE(B.name,'') AS KEC_MUSTAHIK
								,COALESCE(C.name,'') AS DESA_MUSTAHIK
							FROM tb_costumer AS A
							LEFT JOIN 
							(
								SELECT DISTINCT id,kabkot_id,name
								FROM tb_kecamatan
								GROUP BY id,kabkot_id,name
							) AS B ON A.kecamatan = B.id
							LEFT JOIN 
							(
								SELECT DISTINCT id, kec_id, name
								FROM tb_desa
								GROUP BY id, kec_id, name
							) AS C ON A.desa = C.id
							LEFT JOIN 
							(
								SELECT DISTINCT kode_kantor,id_kat_costumer,nama_kat_costumer
								FROM tb_kat_costumer
								GROUP BY kode_kantor,id_kat_costumer,nama_kat_costumer
							) AS D ON A.kode_kantor = D.kode_kantor AND A.id_kat_costumer = D.id_kat_costumer
						) AS C ON A.kode_kantor = C.kode_kantor AND A.id_costumer_ashnaf = C.id_costumer
						".$cari."
						";
			$query = $this->db->query($query);
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
			$id_costumer_pangaju,
			$id_costumer_ashnaf,
			$no_reg,
			$no_kode,
			$tgl_terima,
			$no_surat,
			$tgl_surat,
			$jenis_pengajuan,
			$perihal,
			$isLembaga,
			$user_ins,
			$kode_kantor,
			$no_hp
		)
		{
			$strquery = "
				INSERT INTO tb_pengajuan
				(
					id_pengajuan,
					id_costumer_pangaju,
					id_costumer_ashnaf,
					no_reg,
					no_kode,
					tgl_terima,
					no_surat,
					tgl_surat,
					jenis_pengajuan,
					perihal,
					isLembaga,
					tgl_ins,
					tgl_updt,
					user_ins,
					user_updt,
					kode_kantor,
					no_hp
				)
				VALUES
				(
					(
						SELECT CONCAT('".$this->session->userdata('isLocal')."','".$kode_kantor."',FRMTGL,ORD) AS id_pengajuan
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
								COALESCE(MAX(CAST(RIGHT(id_pengajuan,5) AS UNSIGNED)) + 1,1) AS ORD
								From tb_pengajuan
								WHERE DATE(tgl_ins) = DATE(NOW())
								AND kode_kantor = '".$kode_kantor."'
							) AS A
						) AS AA
					),
					'".$id_costumer_pangaju."',
					'".$id_costumer_ashnaf."',
					'".$no_reg."',
					'".$no_kode."',
					'".$tgl_terima."',
					'".$no_surat."',
					'".$tgl_surat."',
					'".$jenis_pengajuan."',
					'".$perihal."',
					'".$isLembaga."',
					NOW(),
					NOW(),
					'".$user_ins."',
					'',
					'".$kode_kantor."',
					'".$no_hp."'
				)
			";
			
			/*SIMPAN DAN CATAT QUERY*/
				$this->M_gl_log->simpan_query($strquery);
			/*SIMPAN DAN CATAT QUERY*/
		}
		
		function edit
		(
		
			$id_pengajuan,
			$id_costumer_pangaju,
			$id_costumer_ashnaf,
			$no_reg,
			$no_kode,
			$tgl_terima,
			$no_surat,
			$tgl_surat,
			$jenis_pengajuan,
			$perihal,
			$isLembaga,
			$user_updt,
			$kode_kantor,
			$no_hp
		)
		{
			$strquery = "
					UPDATE tb_pengajuan SET
						id_costumer_pangaju = '".$id_costumer_pangaju."',
						id_costumer_ashnaf = '".$id_costumer_ashnaf."',
						no_reg = '".$no_reg."',
						no_kode = '".$no_kode."',
						tgl_terima = '".$tgl_terima."',
						no_surat = '".$no_surat."',
						tgl_surat = '".$tgl_surat."',
						jenis_pengajuan = '".$jenis_pengajuan."',
						perihal = '".$perihal."',
						isLembaga = '".$isLembaga."',
						tgl_updt = NOW(),
						user_updt = '".$user_updt."',
						no_hp = '".$no_hp."'
					WHERE kode_kantor = '".$kode_kantor."' AND id_pengajuan = '".$id_pengajuan
					."'
					";
					
			/*SIMPAN DAN CATAT QUERY*/
				$this->M_gl_log->simpan_query($strquery);
			/*SIMPAN DAN CATAT QUERY*/
		}
		
		function hapus($berdasarkan,$id_pengajuan)
		{
			/*HAPUS JABATAN*/
				$strquery = "DELETE FROM tb_pengajuan WHERE ".$berdasarkan." = '".$id_pengajuan."' AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ;";
			/*HAPUS JABATAN*/
			
			/*SIMPAN DAN CATAT QUERY*/
				$this->M_gl_log->simpan_query($strquery);
			/*SIMPAN DAN CATAT QUERY*/
		}
		
		function edit_1_field($berdasarkan,$isi,$id)
		{
			/*HAPUS JABATAN*/
				$query = "UPDATE tb_pengajuan SET ".$berdasarkan." = '".$isi."' WHERE id_pengajuan = '".$id."' AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'";
				$strquery = $query;
			/*HAPUS JABATAN*/
			
			/*SIMPAN DAN CATAT QUERY*/
				$this->M_gl_log->simpan_query($strquery);
			/*SIMPAN DAN CATAT QUERY*/
		}
		
		function get_pengajuan($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_pengajuan', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
            if($query->num_rows() > 0)
            {
                return $query;
            }
            else
            {
                return false;
            }
        }
		
		function get_no_pengajuan($kode_kantor)
        {
			$query = "SELECT MAX(no_reg + '0.00') + 1 AS NO_REG_FORMAT FROM tb_pengajuan WHERE kode_kantor = '".$kode_kantor."' AND YEAR(tgl_ins) = YEAR(NOW()) ;";
			
            //$query = $this->db->get_where('tb_pengajuan', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
			
			$query = $this->db->query($query);
            if($query->num_rows() > 0)
            {
                return $query->row();
            }
            else
            {
                return false;
            }
        }
		
		function get_no_kode_per_jenis($kode_kantor,$jenis)
        {
			$query = "SELECT MAX(no_reg + '0.00') + 1 AS NO_KODE_FORMAT FROM tb_pengajuan WHERE kode_kantor = '".$kode_kantor."' AND LEFT(no_kode,1) = '".$jenis."' AND YEAR(tgl_ins) = YEAR(NOW()) ;";
			
            //$query = $this->db->get_where('tb_pengajuan', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
			
			$query = $this->db->query($query);
            if($query->num_rows() > 0)
            {
                return $query->row();
            }
            else
            {
                return false;
            }
        }
		
		function list_lap_by_group_sum($cari,$kategori)
		{
			$query = "
			
						SELECT
							".$kategori."
							,COUNT(A.id_pengajuan) AS CNT
						FROM tb_pengajuan AS A
						LEFT JOIN tb_kode_program AS D ON A.kode_kantor = D.kode_kantor AND A.kode_program = D.kd_prog
						
						LEFT JOIN
						(
							SELECT
								A.*
								,COALESCE(B.name,'') AS KEC_PENGAJU
								,COALESCE(C.name,'') AS DESA_PENGAJU
								,COALESCE(D.nama_kat_costumer,'') AS JENIS_PENGAJU
							FROM tb_costumer AS A
							LEFT JOIN tb_kecamatan AS B ON A.kecamatan = B.id
							LEFT JOIN tb_desa AS C ON A.desa = C.id
							LEFT JOIN tb_kat_costumer AS D ON A.kode_kantor = D.kode_kantor AND A.id_kat_costumer = D.id_kat_costumer
						) AS B ON A.kode_kantor = B.kode_kantor AND A.id_costumer_pangaju = B.id_costumer
						LEFT JOIN
						(
							SELECT
								A.*
								,COALESCE(B.name,'') AS KEC_MUSTAHIK
								,COALESCE(C.name,'') AS DESA_MUSTAHIK
								,COALESCE(D.nama_kat_costumer,'') AS JENIS_MUSTAHIK
							FROM tb_costumer AS A
							LEFT JOIN tb_kecamatan AS B ON A.kecamatan = B.id
							LEFT JOIN tb_desa AS C ON A.desa = C.id
							LEFT JOIN tb_kat_costumer AS D ON A.kode_kantor = D.kode_kantor AND A.id_kat_costumer = D.id_kat_costumer
						) AS C ON A.kode_kantor = C.kode_kantor AND A.id_costumer_ashnaf = C.id_costumer
						
						".$cari."
						GROUP BY ".$kategori."
						ORDER BY COUNT(A.id_pengajuan) DESC
						;
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
	}
?>