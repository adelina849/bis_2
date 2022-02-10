<?php
	class M_dash extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function ST_PENERIMAAN_PERPERIODE()
		{
			//month(str_to_date(LEFT(A.INPOS_PERIODEMNTH,3),'%b'))
			$query = "
						SELECT CONCAT(AA.PERIODE,' | ',AA.DARI,' KOTAK') AS PERIODE,AA.NOMINAL 
						FROM
						(
							SELECT 
								month(str_to_date(LEFT(A.INPOS_PERIODEMNTH,3),'%b')) AS PERIODE_ORICONCAT		
								,CONCAT(A.INPOS_PERIODETHN,'-',A.INPOS_PERIODEMNTH) AS PERIODE
								,SUM(A.INPOS_NOMINAL) AS NOMINAL
								,COUNT(A.INPOS_ID) AS DARI
							FROM tb_tr_masuk_pos AS A
							LEFT JOIN tb_pos AS B ON A.POS_ID = B.POS_ID AND A.INPOS_KODEKANTOR = B.POS_KODEKANTOR
							LEFT JOIN tb_kat_pos AS C ON B.KPOS_ID = C.KPOS_ID AND A.INPOS_KODEKANTOR = C.KPOS_KODEKANTOR
							WHERE (A.INPOS_PERIODETHN) = YEAR(NOW()) AND C.KPOS_NAMA = 'Kotak Amal Baznas'
							GROUP BY CONCAT(A.INPOS_PERIODETHN,'-',A.INPOS_PERIODEMNTH)
						) AS AA
						ORDER BY PERIODE_ORICONCAT ASC;;
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
		
		function LIST_KOTAK_PER_KECAMATAN()
		{
			$query = "
						SELECT
							LEFT(COALESCE(D.name,''),3) KABKOT_FIX
							, COALESCE(D.name,'') KABKOT
							, COALESCE(E.name,'') KECAMATAN
							, COUNT(A.POS_ID) AS HITUNG
							, COALESCE(SUM(F.NOMINAL),0) AS NOMINAL
							, COALESCE(SUM(F.DARI),0) AS DARI
							, ROUND(COALESCE(SUM(F.DARI),0) / (COUNT(A.POS_ID)/100),0) AS PRSN
							, COALESCE( COALESCE(SUM(F.NOMINAL),0) / COALESCE(SUM(F.DARI),0), 0) AS RATA
						FROM tb_pos AS A
						LEFT JOIN tb_kat_pos AS B ON A.KPOS_ID = B.KPOS_ID AND A.POS_KODEKANTOR = B.KPOS_KODEKANTOR
						LEFT JOIN provinces AS C ON A.POS_PROV = C.id
						LEFT JOIN tb_kabkot AS D ON A.POS_KAB = D.id
						LEFT JOIN tb_kecamatan AS E ON A.POS_KEC = E.id
						LEFT JOIN
						(
							SELECT 
								A.POS_ID
								,month(str_to_date(LEFT(A.INPOS_PERIODEMNTH,3),'%b')) AS PERIODE_ORICONCAT		
								,CONCAT(A.INPOS_PERIODETHN,'-',A.INPOS_PERIODEMNTH) AS PERIODE
								,SUM(A.INPOS_NOMINAL) AS NOMINAL
								,COUNT(A.INPOS_ID) AS DARI
							FROM tb_tr_masuk_pos AS A
							LEFT JOIN tb_pos AS B ON A.POS_ID = B.POS_ID AND A.INPOS_KODEKANTOR = B.POS_KODEKANTOR
							LEFT JOIN tb_kat_pos AS C ON B.KPOS_ID = C.KPOS_ID AND A.INPOS_KODEKANTOR = C.KPOS_KODEKANTOR
							WHERE (A.INPOS_PERIODETHN) = YEAR(NOW()) AND A.INPOS_PERIODEMNTH = MONTHNAME(DATE_ADD(NOW(), INTERVAL -1 MONTH) ) AND C.KPOS_NAMA = 'Kotak Amal Baznas'
							GROUP BY A.POS_ID,CONCAT(A.INPOS_PERIODETHN,'-',A.INPOS_PERIODEMNTH)
						) AS F ON A.POS_ID = F.POS_ID
						GROUP BY LEFT(COALESCE(D.name,''),3),COALESCE(D.name,''),COALESCE(E.name,'')
						ORDER BY COALESCE(D.name,'')ASC,COALESCE(SUM(F.NOMINAL),0) DESC,COALESCE(E.name,'') ASC
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
		
		
		function LIST_KOTAK_PER_KECAMATAN_CARI($TAHUN,$BULAN)
		{
			$query = "
						SELECT
							LEFT(COALESCE(D.name,''),3) KABKOT_FIX
							, COALESCE(D.name,'') KABKOT
							, COALESCE(E.name,'') KECAMATAN
							, COUNT(A.POS_ID) AS HITUNG
							, COALESCE(SUM(F.NOMINAL),0) AS NOMINAL
							, COALESCE(SUM(F.DARI),0) AS DARI
							, ROUND(COALESCE(SUM(F.DARI),0) / (COUNT(A.POS_ID)/100),0) AS PRSN
							, COALESCE( COALESCE(SUM(F.NOMINAL),0) / COALESCE(SUM(F.DARI),0), 0) AS RATA
						FROM tb_pos AS A
						LEFT JOIN tb_kat_pos AS B ON A.KPOS_ID = B.KPOS_ID AND A.POS_KODEKANTOR = B.KPOS_KODEKANTOR
						LEFT JOIN provinces AS C ON A.POS_PROV = C.id
						LEFT JOIN tb_kabkot AS D ON A.POS_KAB = D.id
						LEFT JOIN tb_kecamatan AS E ON A.POS_KEC = E.id
						LEFT JOIN
						(
							SELECT 
								A.POS_ID
								,month(str_to_date(LEFT(A.INPOS_PERIODEMNTH,3),'%b')) AS PERIODE_ORICONCAT		
								,CONCAT(A.INPOS_PERIODETHN,'-',A.INPOS_PERIODEMNTH) AS PERIODE
								,SUM(A.INPOS_NOMINAL) AS NOMINAL
								,COUNT(A.INPOS_ID) AS DARI
							FROM tb_tr_masuk_pos AS A
							LEFT JOIN tb_pos AS B ON A.POS_ID = B.POS_ID AND A.INPOS_KODEKANTOR = B.POS_KODEKANTOR
							LEFT JOIN tb_kat_pos AS C ON B.KPOS_ID = C.KPOS_ID AND A.INPOS_KODEKANTOR = C.KPOS_KODEKANTOR
							
							-- WHERE (A.INPOS_PERIODETHN) = YEAR(NOW()) AND A.INPOS_PERIODEMNTH = MONTHNAME(DATE_ADD(NOW(), INTERVAL -1 MONTH) )
							WHERE (A.INPOS_PERIODETHN) = '".$TAHUN."' AND A.INPOS_PERIODEMNTH = '".$BULAN."' AND C.KPOS_NAMA = 'Kotak Amal Baznas'
							GROUP BY A.POS_ID,CONCAT(A.INPOS_PERIODETHN,'-',A.INPOS_PERIODEMNTH)
						) AS F ON A.POS_ID = F.POS_ID
						GROUP BY LEFT(COALESCE(D.name,''),3),COALESCE(D.name,''),COALESCE(E.name,'')
						ORDER BY COALESCE(D.name,'')ASC,COALESCE(SUM(F.NOMINAL),0) DESC,COALESCE(E.name,'') ASC
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