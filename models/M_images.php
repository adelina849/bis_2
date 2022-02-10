<?php
	class M_images extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		
		function list_images_limit($cari,$limit,$offset)
		{
			$query = $this->db->query("
										SELECT A.*, COALESCE(B.KIMG_NAMA,'') AS KIMG_NAMA
										FROM tb_images AS A
										LEFT JOIN tb_kat_images AS B ON A.KIMG_ID = B.KIMG_ID AND A.IMG_KODEKANTOR = B.KIMG_KODEKANTOR
										".$cari." ORDER BY A.IMG_DTINS DESC 
										LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_images_limit($cari)
		{
			$query = $this->db->query("
										SELECT COUNT(A.IMG_ID) AS JUMLAH  
										FROM tb_images AS A
										LEFT JOIN tb_kat_images AS B ON A.KIMG_ID = B.KIMG_ID AND A.IMG_KODEKANTOR = B.KIMG_KODEKANTOR
										".$cari
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
		
		function simpan(
			$KIMG_ID,
			$IMG_GRUP,
			$ID,
			$IMG_KODE,
			$IMG_NAMA,
			$IMG_KET,
			$IMG_FILE,
			$IMG_LINK,
			$IMG_USERINS,
			$IMG_USERUPDT,
			$IMG_KODEKANTOR
		)
		{
			
			$query = "
				INSERT INTO tb_images
				(
					IMG_ID,
					KIMG_ID,
					IMG_GRUP,
					ID,
					IMG_KODE,
					IMG_NAMA,
					IMG_KET,
					IMG_FILE,
					IMG_LINK,
					IMG_USERINS,
					IMG_USERUPDT,
					IMG_DTINS,
					IMG_DTUPDT,
					IMG_KODEKANTOR
				)
				VALUES
				(
					(
						SELECT CONCAT('IMG',FRMTGL,ORD) AS IMG_ID
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
								COALESCE(MAX(CAST(RIGHT(IMG_ID,5) AS UNSIGNED)) + 1,1) AS ORD
								From tb_images
								-- WHERE DATE_FORMAT(IMG_DTINS,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
								WHERE DATE(IMG_DTINS) = DATE(NOW())
								AND IMG_KODEKANTOR = '".$IMG_KODEKANTOR."'
							) AS A
						) AS AA
					),
					'".$KIMG_ID."',
					'".$IMG_GRUP."',
					'".$ID."',
					'".$IMG_KODE."',
					'".$IMG_NAMA."',
					'".$IMG_KET."',
					'".$IMG_FILE."',
					'".$IMG_LINK."',
					'".$IMG_USERINS."',
					'".$IMG_USERUPDT."',
					NOW(),
					NOW(),
					'".$IMG_KODEKANTOR."'
				)
			";
			$query = $this->db->query($query);
			
		}
		
		function edit_with_image
		(
			$IMG_ID,
			$KIMG_ID,
			$IMG_GRUP,
			$ID,
			$IMG_KODE,
			$IMG_NAMA,
			$IMG_KET,
			$IMG_FILE,
			$IMG_LINK,
			$IMG_USERUPDT,
			$IMG_KODEKANTOR
		)
		{
			$query = "
					UPDATE tb_images SET
						KIMG_ID = '".$KIMG_ID."',
						IMG_GRUP = '".$IMG_GRUP."',
						ID = '".$ID."',
						IMG_KODE = '".$IMG_KODE."',
						IMG_NAMA = '".$IMG_NAMA."',
						IMG_KET = '".$IMG_KET."',
						IMG_FILE = '".$IMG_FILE."',
						IMG_LINK = '".$IMG_LINK."',
						IMG_USERUPDT = '".$IMG_USERUPDT."',
						IMG_DTUPDT = NOW()
					WHERE IMG_KODEKANTOR = '".$IMG_KODEKANTOR."' AND IMG_ID = '".$IMG_ID."'
					";
			$query = $this->db->query($query);
		}
		
		function edit_no_image
		(
			$IMG_ID,
			$KIMG_ID,
			$IMG_GRUP,
			$ID,
			$IMG_KODE,
			$IMG_NAMA,
			$IMG_KET,
			$IMG_USERUPDT,
			$IMG_KODEKANTOR
		)
		{
			$query = "
					UPDATE tb_images SET
						KIMG_ID = '".$KIMG_ID."',
						IMG_GRUP = '".$IMG_GRUP."',
						ID = '".$ID."',
						IMG_KODE = '".$IMG_KODE."',
						IMG_NAMA = '".$IMG_NAMA."',
						IMG_KET = '".$IMG_KET."',
						IMG_USERUPDT = '".$IMG_USERUPDT."',
						IMG_DTUPDT = NOW()
					WHERE IMG_KODEKANTOR = '".$IMG_KODEKANTOR."' AND IMG_ID = '".$IMG_ID."'
					";
			$query = $this->db->query($query);
		}
		
		function hapus($IMG_ID)
		{
			$this->db->query("DELETE FROM tb_images WHERE IMG_ID = '".$IMG_ID."' AND IMG_KODEKANTOR = '".$this->session->userdata('ses_kode_kantor')."' ;");
		}
		
		/*function get_images($id,$group_by,$berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_images', array('id'=>$id,'group_by'=>$group_by,$berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
            if($query->num_rows() > 0)
            {
                return $query;
            }
            else
            {
                return false;
            }
        }*/
		
		function get_images($cari)
		{
			$query = "SELECT * FROM tb_images ".$cari."";
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
		
		function get_images_byId($IMG_ID,$IMG_KODEKANTOR)
		{
			$query = "SELECT * FROM tb_images WHERE IMG_ID = '".$IMG_ID."' AND IMG_KODEKANTOR = '".$IMG_KODEKANTOR."'";
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
		
		function do_upload($IMG_NAME,$IMG_NAME_BFR)
		{
			$this->load->library('upload');

			if($IMG_NAME_BFR != '')
			{
				@unlink('./assets/global/images/'.$IMG_NAME_BFR);
			}
			
			if (!empty($_FILES['foto']['name']))
			{
				$config['upload_path'] = 'assets/global/images/';
				$config['allowed_types'] = 'gif|jpg|jpeg|png';
				//$config['max_size']	= '2024';
				//$config['max_widtd']  = '300';
				//$config['max_height']  = '300';
				$config['file_name']	= $IMG_NAME;
				$config['overwrite']	= true;
				

				$this->upload->initialize($config);

				//Upload file 1
				if ($this->upload->do_upload('foto'))
				{
					$hasil = $this->upload->data();
				}
			}
		}
	}
?>