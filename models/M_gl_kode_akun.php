<?php
	class M_gl_kode_akun extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_kode_akun_limit($cari,$limit,$offset)
		{
			$query = $this->db->query("SELECT * FROM tb_kode_akun ".$cari." ORDER BY tgl_ins DESC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_kode_akun_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(id_kode_akun) AS JUMLAH FROM tb_kode_akun ".$cari);
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
			$kode_akun_induk,
			$target,
			$kode_akun,
			$nama_kode_akun,
			$ket_kode_akun,
			$user_ins,
			$kode_kantor
		)
		{
			$strquery = "
				INSERT INTO tb_kode_akun
				(
					id_kode_akun,
					kode_akun_induk,
					target,
					kode_akun,
					nama_kode_akun,
					ket_kode_akun,
					tgl_ins,
					tgl_updt,
					user_ins,
					user_updt,
					kode_kantor

				)
				VALUES
				(
					(
						SELECT CONCAT('".$this->session->userdata('isLocal')."','".$kode_kantor."',FRMTGL,ORD) AS id_kode_akun
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
								COALESCE(MAX(CAST(RIGHT(id_kode_akun,5) AS UNSIGNED)) + 1,1) AS ORD
								From tb_kode_akun
								WHERE DATE(tgl_ins) = DATE(NOW())
								AND kode_kantor = '".$kode_kantor."'
							) AS A
						) AS AA
					),
					'".$kode_akun_induk."',
					'".$target."',
					'".$kode_akun."',
					'".$nama_kode_akun."',
					'".$ket_kode_akun."',
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
			$id_kode_akun,
			$kode_akun_induk,
			$target,
			$kode_akun,
			$nama_kode_akun,
			$ket_kode_akun,
			$user_updt,
			$kode_kantor
		)
		{
			$strquery = "
					UPDATE tb_kode_akun SET
						kode_akun_induk = '".$kode_akun_induk."',
						target = '".$target."',
						kode_akun = '".$kode_akun."',
						nama_kode_akun = '".$nama_kode_akun."',
						ket_kode_akun = '".$ket_kode_akun."',
						tgl_updt = NOW(),
						user_updt = '".$user_updt."'
					WHERE kode_kantor = '".$kode_kantor."' AND id_kode_akun = '".$id_kode_akun
					."'
					";
					
			/*SIMPAN DAN CATAT QUERY*/
				$this->M_gl_log->simpan_query($strquery);
			/*SIMPAN DAN CATAT QUERY*/
		}
		
		function hapus($berdasarkan,$id_kode_akun)
		{
			/*HAPUS JABATAN*/
				$strquery = "DELETE FROM tb_kode_akun WHERE ".$berdasarkan." = '".$id_kode_akun."' AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ;";
			/*HAPUS JABATAN*/
			
			/*SIMPAN DAN CATAT QUERY*/
				$this->M_gl_log->simpan_query($strquery);
			/*SIMPAN DAN CATAT QUERY*/
		}
		
		function get_kode_akun($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_kode_akun', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
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