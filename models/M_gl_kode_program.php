<?php
	class M_gl_kode_program extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_kode_program_limit($cari,$order_by,$limit,$offset)
		{
			//$query = $this->db->query("SELECT * FROM tb_kode_program ".$cari." ".$order_by." LIMIT ".$offset.",".$limit);
			
			$query = "SELECT * FROM tb_kode_program ".$cari." ".$order_by." LIMIT ".$offset.",".$limit." ;";
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
		
		function count_kode_program_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(id_kd_prog) AS JUMLAH FROM tb_kode_program ".$cari);
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
			$kd_prog,
			$prog,
			$ashnaf,
			$uraian,
			$user_ins,
			$kode_kantor
		)
		{
			$strquery = "
				INSERT INTO tb_kode_program
				(
					id_kd_prog,
					kd_prog,
					prog,
					ashnaf,
					uraian,
					tgl_ins,
					tgl_updt,
					user_ins,
					user_updt,
					kode_kantor
				)
				VALUES
				(
					(
						SELECT CONCAT('".$this->session->userdata('isLocal')."','".$kode_kantor."',FRMTGL,ORD) AS id_kd_prog
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
								COALESCE(MAX(CAST(RIGHT(id_kd_prog,5) AS UNSIGNED)) + 1,1) AS ORD
								From tb_kode_program
								WHERE DATE(tgl_ins) = DATE(NOW())
								AND kode_kantor = '".$kode_kantor."'
							) AS A
						) AS AA
					),
					
					'".$kd_prog."',
					'".$prog."',
					'".$ashnaf."',
					'".$uraian."',
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
			$id_kd_prog ,
			$kd_prog,
			$prog,
			$ashnaf,
			$uraian,
			$user_updt,
			$kode_kantor
		)
		{
			$strquery = "
					UPDATE tb_kode_program SET
						kd_prog = '".$kd_prog."',
						prog = '".$prog."',
						ashnaf = '".$ashnaf."',
						uraian = '".$uraian."',
						tgl_updt = NOW(),
						user_updt = '".$user_updt."'
					WHERE kode_kantor = '".$kode_kantor."' AND id_kd_prog  = '".$id_kd_prog 
					."'
					";
					
			/*SIMPAN DAN CATAT QUERY*/
				$this->M_gl_log->simpan_query($strquery);
			/*SIMPAN DAN CATAT QUERY*/
		}
		
		function hapus($cari)
		{
			/*HAPUS JABATAN*/
				$strquery = "DELETE FROM tb_kode_program ".$cari." ";
			/*HAPUS JABATAN*/
			
			/*SIMPAN DAN CATAT QUERY*/
				$this->M_gl_log->simpan_query($strquery);
			/*SIMPAN DAN CATAT QUERY*/
		}
		
		function get_kode_program($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_kode_program', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
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