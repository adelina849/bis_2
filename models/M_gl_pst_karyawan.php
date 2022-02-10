<?php
	class M_gl_pst_karyawan extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_karyawan_limit($cari,$limit,$offset)
		{
			
			$query = "
				SELECT
					A.id_karyawan,
					A.id_jabatan,
					A.no_karyawan,
					A.nik_karyawan,
					A.nama_karyawan,
					A.pnd,
					A.tlp,
					A.email,
					A.tmp_lahir,
					A.tgl_lahir,
					A.kelamin,
					A.sts_nikah,
					A.avatar,
					A.avatar_url,
					A.alamat,
					A.ket_karyawan,
					A.isAktif,
					A.alasan_phk,
					A.tgl_phk,
					A.tgl_diterima,
					DATEDIFF(DATE(NOW()),COALESCE(A.tgl_diterima,'')) AS lama_kerja,
					A.isDokter,
					A.lamar_via,
					A.nilai_ujian,
					A.ket_hasil_ujian,
					A.user,
					A.pass,
					A.tgl_updt_pass,
					A.tgl_ins,
					A.tgl_updt,
					A.user_ins,
					A.user_updt,
					A.kode_kantor,
					
					SUBSTRING(MAX(CONCAT(A.tgl_dari,A.nama_jabatan)),11,50) AS nama_jabatan,
					MAX(A.tgl_dari) AS tgl_dari,
					A.nama_kantor,
					A.pemilik,
					A.alamat_kantor,
					A.tlp_kantor,
					A.USIA,
					A.NOMINAL_PINJAM,
					A.NOMINAL_BAYAR
					
				
				FROM
				(
					SELECT 
						A.*
						,COALESCE(B.nama_jabatan,'') AS nama_jabatan
						,CASE
							WHEN COALESCE(B.tgl_dari,'') = '' THEN
								DATE(A.tgl_ins)
							ELSE
								COALESCE(B.tgl_dari,'')
							END
							AS tgl_dari
						,COALESCE(C.nama_kantor,'') AS nama_kantor
						,COALESCE(C.pemilik,'') AS pemilik
						,COALESCE(C.alamat,'') AS alamat_kantor
						,COALESCE(C.tlp,'') AS tlp_kantor
						,CASE 
							WHEN (YEAR(CURDATE()) - YEAR(A.tgl_lahir)) < 150 THEN 
								(YEAR(CURDATE()) - YEAR(A.tgl_lahir))
							ELSE
								0
							END
						AS USIA
						,COALESCE(D.NOMINAL_PINJAM,0) AS NOMINAL_PINJAM
						,COALESCE(E.NOMINAL_BAYAR,0) AS NOMINAL_BAYAR
					FROM tb_karyawan AS A
					LEFT JOIN
					(
						/*
						SELECT A.* 
							,COALESCE(B.nama_jabatan,'') AS nama_jabatan
							,COALESCE(B.hirarki,0) AS hirarki
						*/
						SELECT A.id_karyawan,A.tgl_dari,A.kode_kantor
							,A.tgl_ins
							,COALESCE(B.nama_jabatan,'') AS nama_jabatan
							,COALESCE(B.hirarki,0) AS hirarki
						FROM tb_karyawan_jabatan AS A
						LEFT JOIN tb_jabatan AS B ON A.id_jabatan = B.id_jabatan AND A.kode_kantor = B.kode_kantor
						WHERE A.status = 'LULUS' 
						-- AND A.id_karyawan = 'OFCJRT2020030200001'
						-- OFCJRT2020030200001
						-- ORDER BY COALESCE(B.hirarki,0) DESC,A.tgl_ins DESC
						GROUP BY A.id_karyawan,A.tgl_dari,A.kode_kantor,A.tgl_ins,COALESCE(B.nama_jabatan,''),COALESCE(B.hirarki,0)
						-- HAVING MAX(A.tgl_ins)
						ORDER BY A.tgl_ins ASC
						-- LIMIT 0,1
						-- LIMIT 1
					) AS B ON A.id_karyawan = B.id_karyawan AND A.kode_kantor = B.kode_kantor
					LEFT JOIN tb_kantor AS C ON A.kode_kantor = C.kode_kantor
					LEFT JOIN (SELECT id_karyawan,kode_kantor,SUM(nominal) AS NOMINAL_PINJAM FROM tb_uang_keluar GROUP BY id_karyawan,kode_kantor) AS D ON A.id_karyawan = D.id_karyawan AND A.kode_kantor = D.kode_kantor
					LEFT JOIN (SELECT id_karyawan,kode_kantor,SUM(nominal) AS NOMINAL_BAYAR FROM tb_uang_masuk GROUP BY id_karyawan,kode_kantor) AS E ON A.id_karyawan = E.id_karyawan AND A.kode_kantor = E.kode_kantor
					
					".$cari." 
				) AS A
				GROUP BY
				A.id_karyawan,
					A.id_jabatan,
					A.no_karyawan,
					A.nik_karyawan,
					A.nama_karyawan,
					A.pnd,
					A.tlp,
					A.email,
					A.tmp_lahir,
					A.tgl_lahir,
					A.kelamin,
					A.sts_nikah,
					A.avatar,
					A.avatar_url,
					A.alamat,
					A.ket_karyawan,
					A.isAktif,
					A.alasan_phk,
					A.tgl_phk,
					A.tgl_diterima,
					A.isDokter,
					A.lamar_via,
					A.nilai_ujian,
					A.ket_hasil_ujian,
					A.user,
					A.pass,
					A.tgl_updt_pass,
					A.tgl_ins,
					A.tgl_updt,
					A.user_ins,
					A.user_updt,
					A.kode_kantor,
					
					
					A.nama_kantor,
					A.pemilik,
					A.alamat_kantor,
					A.tlp_kantor,
					A.USIA,
					A.NOMINAL_PINJAM,
					A.NOMINAL_BAYAR
				ORDER BY A.tgl_ins DESC LIMIT ".$offset.",".$limit;
			
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
		
		function count_karyawan_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(A.id_karyawan) AS JUMLAH FROM tb_karyawan AS A ".$cari);
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