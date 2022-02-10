<?php
	class M_gl_h_penerimaan extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		
		
		function list_h_penerimaan_limit($cari,$limit,$offset)
		{
			$query =
			"
				SELECT A.*,B.*,COALESCE(C.JUMLAH,0) AS JUMLAH FROM tb_h_penerimaan AS A 
				LEFT JOIN tb_gedung AS B ON A.id_gedung = B.id_gedung AND A.kode_kantor = B.kode_kantor
				LEFT JOIN
				(
					SELECT id_h_penerimaan,kode_kantor,COUNT(id_produk) AS JUMLAH
					FROM tb_d_penerimaan
					GROUP BY id_h_penerimaan,kode_kantor
				) AS C ON A.id_h_penerimaan = C.id_h_penerimaan AND A.kode_kantor = C.kode_kantor
				".$cari." ORDER BY A.tgl_ins DESC LIMIT ".$offset.",".$limit."
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
		
		function count_h_penerimaan_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(id_h_penerimaan) AS JUMLAH FROM tb_h_penerimaan AS A 
				LEFT JOIN tb_gedung AS B ON A.id_gedung = B.id_gedung AND A.kode_kantor = B.kode_kantor ".$cari);
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function list_d_penerimaan($id_h_pembelian,$id_h_penerimaan,$cari,$kode_kantor)
		{
			$query = 
			"
				 -- SELECT * FROM
				SELECT A.id_d_pembelian, A.id_h_pembelian, A.id_produk,A.kode_produk,A.nama_produk,A.kode_satuan
					 ,A.BL_JUMLAH,A.BL_HARGA,A.BL_STATUS_KONVERSI,A.BL_KONVERSI,A.STN_DFL
					-- ,COALESCE(B.id_d_penerimaan,'') AS id_d_penerimaan
					,SUM(COALESCE(B.TRM_DITERIMA,0)) AS TRM_DITERIMA
					,SUM(COALESCE(B.TRM_SATUAN_BELI,0)) AS TRM_SATUAN_BELI
					,SUM(COALESCE(C.TRM_SATUAN_BELI,0)) AS TLH_TRM_SATUAN_BELI
					,A.BL_JUMLAH - (SUM(COALESCE(B.TRM_SATUAN_BELI,0)) + SUM(COALESCE(C.TRM_SATUAN_BELI,0))) AS SISA
					,COALESCE(B.TRM_BESAR_KONVERSI,0) AS TRM_BESAR_KONVERSI
					,COALESCE(B.TRM_STATUS_KONVERSI,'*') AS TRM_STATUS_KONVERSI
					,COALESCE(B.tgl_exp,'') AS TGL_EXP
				From

				(
					SELECT A.id_d_pembelian,A.id_h_pembelian,A.id_produk,A.jumlah AS BL_JUMLAH,A.kode_satuan,A.harga AS BL_HARGA
						  ,A.status_konversi AS BL_STATUS_KONVERSI,A.konversi AS BL_KONVERSI,A.tgl_ins
						  ,COALESCE(B.kode_produk,'') AS kode_produk
						  ,COALESCE(B.nama_produk,'') AS nama_produk
						  ,COALESCE(B.STN_DFL,'') AS STN_DFL
					  FROM tb_d_pembelian AS A
					  Left Join
					  (
						  SELECT A.id_produk,A.kode_produk,A.nama_produk
							-- ,COALESCE(B.kode_satuan,'') AS STN_DFL
							,'' AS STN_DFL
						  From tb_produk AS A
						  -- LEFT JOIN tb_satuan AS B ON A.id_satuan = B.id_satuan AND A.kode_kantor = B.kode_kantor
						  WHERE A.kode_kantor = '".$kode_kantor."' AND (A.kode_produk LIKE '%".$cari."%' OR A.nama_produk LIKE '%".$cari."%')
						  GROUP BY A.id_produk,A.kode_produk,A.nama_produk
					  ) AS B ON A.id_produk = B.id_produk
					  WHERE A.kode_kantor = '".$kode_kantor."' AND id_h_pembelian = '".$id_h_pembelian."'
					  GROUP BY A.id_d_pembelian,A.id_h_pembelian,A.id_produk,A.jumlah,A.kode_satuan,A.harga,A.status_konversi,A.konversi
						  ,COALESCE(B.kode_produk,'')
						  ,COALESCE(B.nama_produk,'')
						  ,COALESCE(B.STN_DFL,'')
				) AS A
				Left Join
				(
					  SELECT B.id_d_penerimaan,B.id_d_pembelian,B.diterima AS TRM_DITERIMA,B.konversi AS TRM_BESAR_KONVERSI
						  ,B.diterima_satuan_beli AS TRM_SATUAN_BELI, B.status_konversi AS TRM_STATUS_KONVERSI, B.tgl_exp
					  FROM tb_h_penerimaan AS A
					  INNER JOIN tb_d_penerimaan AS B ON A.id_h_penerimaan = B.id_h_penerimaan AND B.kode_kantor = '".$kode_kantor."'
					  WHERE A.kode_kantor = '".$kode_kantor."' AND A.id_h_penerimaan LIKE '%".$id_h_penerimaan."%'  AND A.id_h_pembelian = '".$id_h_pembelian."'
					  GROUP BY B.id_d_penerimaan,B.id_d_pembelian,B.diterima,B.konversi,B.diterima_satuan_beli, B.status_konversi
				) AS B ON A.id_d_pembelian = B.id_d_pembelian
				 Left Join
				(
					  SELECT B.id_d_pembelian
						  ,SUM(COALESCE(B.diterima,0)) AS TRM_DITERIMA
						  ,(B.konversi) AS TRM_BESAR_KONVERSI
						  ,SUM(COALESCE(B.diterima_satuan_beli,0)) AS TRM_SATUAN_BELI
						  ,B.status_konversi AS TRM_STATUS_KONVERSI
						  ,B.tgl_exp
					  FROM tb_h_penerimaan AS A
					  INNER JOIN tb_d_penerimaan AS B ON A.id_h_penerimaan = B.id_h_penerimaan AND B.kode_kantor = '".$kode_kantor."'
					  WHERE A.kode_kantor = '".$kode_kantor."' AND A.id_h_penerimaan <> '".$id_h_penerimaan."'  AND A.id_h_pembelian = '".$id_h_pembelian."'
					  GROUP BY B.id_d_pembelian,B.konversi, B.status_konversi
				) AS C ON A.id_d_pembelian = C.id_d_pembelian
				WHERE (A.kode_produk LIKE '%".$cari."%' OR A.nama_produk LIKE '%".$cari."%')
				GROUP BY A.id_d_pembelian, A.id_h_pembelian, A.id_produk,A.kode_produk,A.nama_produk,A.kode_satuan,A.BL_JUMLAH,A.BL_HARGA,A.BL_STATUS_KONVERSI,A.BL_KONVERSI,A.STN_DFL
				-- ,COALESCE(B.id_d_penerimaan,'')
				,COALESCE(B.TRM_BESAR_KONVERSI,0)
				,COALESCE(B.TRM_STATUS_KONVERSI,'*')
				ORDER BY A.nama_produk ASC
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
		
		function list_d_penerimaan_pakai_alias($id_supplier,$id_h_pembelian,$id_h_penerimaan,$cari,$kode_kantor)
		{
			$query = 
			"
				 -- SELECT * FROM
				SELECT A.id_d_pembelian, A.id_h_pembelian, A.id_produk
					
					,A.kode_produk,A.nama_produk
					,COALESCE(D.kode_produk,'') AS kode_produk_alias
					,COALESCE(D.nama_produk,'') AS nama_produk_alias
					
					,A.kode_satuan
					,A.BL_JUMLAH,A.BL_HARGA,A.BL_STATUS_KONVERSI,A.BL_KONVERSI,A.STN_DFL
					-- ,COALESCE(B.id_d_penerimaan,'') AS id_d_penerimaan
					,SUM(COALESCE(B.TRM_DITERIMA,0)) AS TRM_DITERIMA
					,SUM(COALESCE(B.TRM_SATUAN_BELI,0)) AS TRM_SATUAN_BELI
					,SUM(COALESCE(C.TRM_SATUAN_BELI,0)) AS TLH_TRM_SATUAN_BELI
					,A.BL_JUMLAH - (SUM(COALESCE(B.TRM_SATUAN_BELI,0)) + SUM(COALESCE(C.TRM_SATUAN_BELI,0))) AS SISA
					,COALESCE(B.TRM_BESAR_KONVERSI,0) AS TRM_BESAR_KONVERSI
					,COALESCE(B.TRM_STATUS_KONVERSI,'*') AS TRM_STATUS_KONVERSI
					,COALESCE(B.tgl_exp,'') AS TGL_EXP
					,A.tgl_ins
				From

				(
					SELECT A.id_d_pembelian,A.id_h_pembelian,A.id_produk,A.jumlah AS BL_JUMLAH,A.kode_satuan,A.harga AS BL_HARGA
						  ,A.status_konversi AS BL_STATUS_KONVERSI,A.konversi AS BL_KONVERSI,A.tgl_ins
						  ,COALESCE(B.kode_produk,'') AS kode_produk
						  ,COALESCE(B.nama_produk,'') AS nama_produk
						  ,COALESCE(B.STN_DFL,'') AS STN_DFL
						  
					  FROM tb_d_pembelian AS A
					  Left Join
					  (
						  SELECT A.id_produk,A.kode_produk,A.nama_produk
							-- ,COALESCE(B.kode_satuan,'') AS STN_DFL
							,'' AS STN_DFL
						  From tb_produk AS A
						  -- LEFT JOIN tb_satuan AS B ON A.id_satuan = B.id_satuan AND A.kode_kantor = B.kode_kantor
						  WHERE A.kode_kantor = '".$kode_kantor."' AND (A.kode_produk LIKE '%".$cari."%' OR A.nama_produk LIKE '%".$cari."%')
						  GROUP BY A.id_produk,A.kode_produk,A.nama_produk
					  ) AS B ON A.id_produk = B.id_produk
					  WHERE A.kode_kantor = '".$kode_kantor."' AND id_h_pembelian = '".$id_h_pembelian."'
					  GROUP BY A.id_d_pembelian,A.id_h_pembelian,A.id_produk,A.jumlah,A.kode_satuan,A.harga,A.status_konversi,A.konversi
						  ,COALESCE(B.kode_produk,'')
						  ,COALESCE(B.nama_produk,'')
						  ,COALESCE(B.STN_DFL,'')
				) AS A
				Left Join
				(
					  SELECT B.id_d_penerimaan,B.id_d_pembelian,B.diterima AS TRM_DITERIMA,B.konversi AS TRM_BESAR_KONVERSI
						  ,B.diterima_satuan_beli AS TRM_SATUAN_BELI, B.status_konversi AS TRM_STATUS_KONVERSI, B.tgl_exp
					  FROM tb_h_penerimaan AS A
					  INNER JOIN tb_d_penerimaan AS B ON A.id_h_penerimaan = B.id_h_penerimaan AND B.kode_kantor = '".$kode_kantor."'
					  WHERE A.kode_kantor = '".$kode_kantor."' AND A.id_h_penerimaan LIKE '%".$id_h_penerimaan."%'  AND A.id_h_pembelian = '".$id_h_pembelian."'
					  GROUP BY B.id_d_penerimaan,B.id_d_pembelian,B.diterima,B.konversi,B.diterima_satuan_beli, B.status_konversi
				) AS B ON A.id_d_pembelian = B.id_d_pembelian
				 Left Join
				(
					  SELECT B.id_d_pembelian
						  ,SUM(COALESCE(B.diterima,0)) AS TRM_DITERIMA
						  ,(B.konversi) AS TRM_BESAR_KONVERSI
						  ,SUM(COALESCE(B.diterima_satuan_beli,0)) AS TRM_SATUAN_BELI
						  ,B.status_konversi AS TRM_STATUS_KONVERSI
						  ,B.tgl_exp
					  FROM tb_h_penerimaan AS A
					  INNER JOIN tb_d_penerimaan AS B ON A.id_h_penerimaan = B.id_h_penerimaan AND B.kode_kantor = '".$kode_kantor."'
					  WHERE A.kode_kantor = '".$kode_kantor."' AND A.id_h_penerimaan <> '".$id_h_penerimaan."'  AND A.id_h_pembelian = '".$id_h_pembelian."'
					  GROUP BY B.id_d_pembelian,B.konversi, B.status_konversi
				) AS C ON A.id_d_pembelian = C.id_d_pembelian
				
				LEFT JOIN tb_alias_produk AS D ON D.kode_kantor = '".$kode_kantor."' AND A.id_produk = D.id_produk AND D.grup = 'PURCHASE' AND D.id_supplier = '".$id_supplier."'
				
				WHERE (A.kode_produk LIKE '%".$cari."%' OR A.nama_produk LIKE '%".$cari."%')
				GROUP BY A.id_d_pembelian, A.id_h_pembelian, A.id_produk,A.kode_produk,A.nama_produk,A.kode_satuan,A.BL_JUMLAH,A.BL_HARGA,A.BL_STATUS_KONVERSI,A.BL_KONVERSI,A.STN_DFL
				-- ,COALESCE(B.id_d_penerimaan,'')
				,COALESCE(B.TRM_BESAR_KONVERSI,0)
				,COALESCE(B.TRM_STATUS_KONVERSI,'*')
				ORDER BY A.nama_produk ASC
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
		
		function list_d_penerimaan_cek_dari_pusat($id_h_pembelian,$id_h_penerimaan,$cari,$kode_kantor)
		{
			$query = 
			"
				 -- SELECT * FROM
				SELECT A.id_d_pembelian, A.id_h_pembelian, A.id_produk,A.kode_produk,A.nama_produk,A.kode_satuan
					 ,A.BL_JUMLAH,A.BL_HARGA,A.BL_STATUS_KONVERSI,A.BL_KONVERSI,A.STN_DFL
					-- ,COALESCE(B.id_d_penerimaan,'') AS id_d_penerimaan
					,SUM(COALESCE(B.TRM_DITERIMA,0)) AS TRM_DITERIMA
					,SUM(COALESCE(B.TRM_SATUAN_BELI,0)) AS TRM_SATUAN_BELI
					,SUM(COALESCE(C.TRM_SATUAN_BELI,0)) AS TLH_TRM_SATUAN_BELI
					,A.BL_JUMLAH - (SUM(COALESCE(B.TRM_SATUAN_BELI,0)) + SUM(COALESCE(C.TRM_SATUAN_BELI,0))) AS SISA
					,COALESCE(B.TRM_BESAR_KONVERSI,0) AS TRM_BESAR_KONVERSI
					,COALESCE(B.TRM_STATUS_KONVERSI,'*') AS TRM_STATUS_KONVERSI
					,COALESCE(B.tgl_exp,'') AS TGL_EXP
				From

				(
					SELECT A.id_d_pembelian,A.id_h_pembelian,A.id_produk,A.jumlah AS BL_JUMLAH,A.kode_satuan,A.harga AS BL_HARGA
						  ,A.status_konversi AS BL_STATUS_KONVERSI,A.konversi AS BL_KONVERSI,A.tgl_ins
						  ,COALESCE(B.kode_produk,'') AS kode_produk
						  ,COALESCE(B.nama_produk,'') AS nama_produk
						  ,COALESCE(B.STN_DFL,'') AS STN_DFL
					  FROM tb_d_pembelian AS A
					  Left Join
					  (
						  SELECT A.id_produk,A.kode_produk,A.nama_produk
							-- ,COALESCE(B.kode_satuan,'') AS STN_DFL
							,'' AS STN_DFL
						  From tb_produk AS A
						  -- LEFT JOIN tb_satuan AS B ON A.id_satuan = B.id_satuan AND A.kode_kantor = B.kode_kantor
						  WHERE A.kode_kantor = '".$kode_kantor."' AND (A.kode_produk LIKE '%".$cari."%' OR A.nama_produk LIKE '%".$cari."%')
						  GROUP BY A.id_produk,A.kode_produk,A.nama_produk
					  ) AS B ON A.id_produk = B.id_produk
					  WHERE A.kode_kantor = '".$kode_kantor."' AND id_h_pembelian = '".$id_h_pembelian."'
					  GROUP BY A.id_d_pembelian,A.id_h_pembelian,A.id_produk,A.jumlah,A.kode_satuan,A.harga,A.status_konversi,A.konversi
						  ,COALESCE(B.kode_produk,'')
						  ,COALESCE(B.nama_produk,'')
						  ,COALESCE(B.STN_DFL,'')
				) AS A
				Left Join
				(
					  SELECT B.id_d_penerimaan,B.id_d_pembelian,B.diterima AS TRM_DITERIMA,B.konversi AS TRM_BESAR_KONVERSI
						  ,B.diterima_satuan_beli AS TRM_SATUAN_BELI, B.status_konversi AS TRM_STATUS_KONVERSI, B.tgl_exp
					  FROM tb_h_penerimaan AS A
					  INNER JOIN tb_d_penerimaan AS B ON A.id_h_penerimaan = B.id_h_penerimaan AND B.kode_kantor = '".$kode_kantor."'
					  WHERE A.kode_kantor = '".$kode_kantor."' AND A.id_h_penerimaan LIKE '%".$id_h_penerimaan."%'  AND A.id_h_pembelian = '".$id_h_pembelian."'
					  GROUP BY B.id_d_penerimaan,B.id_d_pembelian,B.diterima,B.konversi,B.diterima_satuan_beli, B.status_konversi
				) AS B ON A.id_d_pembelian = B.id_d_pembelian
				 Left Join
				(
					  SELECT B.id_d_pembelian
						  ,SUM(COALESCE(B.diterima,0)) AS TRM_DITERIMA
						  ,(B.konversi) AS TRM_BESAR_KONVERSI
						  ,SUM(COALESCE(B.diterima_satuan_beli,0)) AS TRM_SATUAN_BELI
						  ,B.status_konversi AS TRM_STATUS_KONVERSI
						  ,B.tgl_exp
					  FROM tb_h_penerimaan AS A
					  INNER JOIN tb_d_penerimaan AS B ON A.id_h_penerimaan = B.id_h_penerimaan AND B.kode_kantor = '".$kode_kantor."'
					  WHERE A.kode_kantor = '".$kode_kantor."' AND A.id_h_penerimaan <> '".$id_h_penerimaan."'  AND A.id_h_pembelian = '".$id_h_pembelian."'
					  GROUP BY B.id_d_pembelian,B.konversi, B.status_konversi
				) AS C ON A.id_d_pembelian = C.id_d_pembelian
				WHERE (A.kode_produk LIKE '%".$cari."%' OR A.nama_produk LIKE '%".$cari."%')
				GROUP BY A.id_d_pembelian, A.id_h_pembelian, A.id_produk,A.kode_produk,A.nama_produk,A.kode_satuan,A.BL_JUMLAH,A.BL_HARGA,A.BL_STATUS_KONVERSI,A.BL_KONVERSI,A.STN_DFL
				-- ,COALESCE(B.id_d_penerimaan,'')
				,COALESCE(B.TRM_BESAR_KONVERSI,0)
				,COALESCE(B.TRM_STATUS_KONVERSI,'*')
				ORDER BY A.nama_produk ASC
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
		
		function simpan
		(
			$id_h_pembelian,
			$id_gedung,
			$no_surat_jalan,
			$nama_pengirim,
			$cara_pengiriman,
			$diterima_oleh,
			$biaya_kirim,
			$tgl_kirim,
			$tgl_terima,
			$ket_h_penerimaan,
			$user_ins,
			$kode_kantor
		)
		{
			$strquery = "
				INSERT INTO tb_h_penerimaan
				(
					id_h_penerimaan,
					id_h_pembelian,
					id_gedung,
					no_surat_jalan,
					nama_pengirim,
					cara_pengiriman,
					diterima_oleh,
					biaya_kirim,
					tgl_kirim,
					tgl_terima,
					ket_h_penerimaan,
					tgl_ins,
					tgl_updt,
					user_updt,
					user_ins,
					kode_kantor

				)
				VALUES
				(
					(
						SELECT CONCAT('".$this->session->userdata('isLocal')."','".$kode_kantor."',FRMTGL,ORD) AS id_h_penerimaan
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
								COALESCE(MAX(CAST(RIGHT(id_h_penerimaan,5) AS UNSIGNED)) + 1,1) AS ORD
								From tb_h_penerimaan
								WHERE DATE(tgl_ins) = DATE(NOW())
								AND kode_kantor = '".$kode_kantor."'
							) AS A
						) AS AA
					),
					
					'".$id_h_pembelian."',
					'".$id_gedung."',
					'".$no_surat_jalan."',
					'".$nama_pengirim."',
					'".$cara_pengiriman."',
					'".$diterima_oleh."',
					'".$biaya_kirim."',
					'".$tgl_kirim."',
					'".$tgl_terima."',
					'".$ket_h_penerimaan."',
					NOW(),
					NOW(),
					'',
					'".$user_ins."',
					'".$kode_kantor."'
				)
			";
			
			/*SIMPAN DAN CATAT QUERY*/
				$this->M_gl_log->simpan_query($strquery);
			/*SIMPAN DAN CATAT QUERY*/
		}
		
		function edit
		(
			$id_h_penerimaan,
			$id_h_pembelian,
			$id_gedung,
			$no_surat_jalan,
			$nama_pengirim,
			$cara_pengiriman,
			$diterima_oleh,
			$biaya_kirim,
			$tgl_kirim,
			$tgl_terima,
			$ket_h_penerimaan,
			$user_updt,
			$kode_kantor
		)
		{
			$strquery = "
					UPDATE tb_h_penerimaan SET
					
						id_h_pembelian = '".$id_h_pembelian."',
						id_gedung = '".$id_gedung."',
						no_surat_jalan = '".$no_surat_jalan."',
						nama_pengirim = '".$nama_pengirim."',
						cara_pengiriman = '".$cara_pengiriman."',
						diterima_oleh = '".$diterima_oleh."',
						biaya_kirim = '".$biaya_kirim."',
						tgl_kirim = '".$tgl_kirim."',
						tgl_terima = '".$tgl_terima."',
						ket_h_penerimaan = '".$ket_h_penerimaan."',
						tgl_updt = NOW(),
						user_updt = '".$user_updt."'
					WHERE kode_kantor = '".$kode_kantor."' AND id_h_penerimaan = '".$id_h_penerimaan
					."'
					";
					
			/*SIMPAN DAN CATAT QUERY*/
				$this->M_gl_log->simpan_query($strquery);
			/*SIMPAN DAN CATAT QUERY*/
		}
		
		function hapus($berdasarkan,$id_h_penerimaan)
		{
			/*HAPUS JABATAN*/
				$strquery = "DELETE FROM tb_h_penerimaan WHERE ".$berdasarkan." = '".$id_h_penerimaan."' AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ;";
			/*HAPUS JABATAN*/
			
			/*SIMPAN DAN CATAT QUERY*/
				$this->M_gl_log->simpan_query($strquery);
			/*SIMPAN DAN CATAT QUERY*/
		}
		
		function hapus_d_penerimaan($berdasarkan,$id_h_penerimaan)
		{
			/*HAPUS JABATAN*/
				$strquery = "DELETE FROM tb_d_penerimaan WHERE ".$berdasarkan." = '".$id_h_penerimaan."' AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ;";
			/*HAPUS JABATAN*/
			
			/*SIMPAN DAN CATAT QUERY*/
				$this->M_gl_log->simpan_query($strquery);
			/*SIMPAN DAN CATAT QUERY*/
		}
		
		function get_h_penerimaan($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_h_penerimaan', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
            if($query->num_rows() > 0)
            {
                return $query;
            }
            else
            {
                return false;
            }
        }
		
		function get_h_penerimaan_cari($cari)
        {
            $query =
			"
				SELECT * FROM tb_h_penerimaan ".$cari."
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
		
		function get_h_penerimaan_cari_with_id_supplier($cari)
        {
            $query =
			"
				SELECT 
					A.*
					,COALESCE(B.id_supplier,'') AS id_supplier
				FROM tb_h_penerimaan AS A
				LEFT JOIN tb_h_pembelian AS B ON A.id_h_pembelian = B.id_h_pembelian AND A.kode_kantor = B.kode_kantor ".$cari."
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
	
		function list_penerimaan_pembelian_sj($cari,$order_by)
		{
			$query =
			"
				SELECT
					A.*
					,COALESCE(B.no_h_pembelian,'') AS no_h_pembelian
					,COALESCE(C.kode_supplier,'') AS kode_supplier
					,COALESCE(C.nama_supplier,'') AS nama_supplier
				FROM tb_h_penerimaan AS A
				LEFT JOIN tb_h_pembelian AS B ON A.id_h_pembelian = B.id_h_pembelian AND A.kode_kantor = B.kode_kantor
				LEFT JOIN tb_supplier AS C ON A.kode_kantor = C.kode_kantor AND B.id_supplier = C.id_supplier
				".$cari."
				".$order_by."
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
		
		function get_surat_jalan_atau_faktur_penjualan($cari)
        {
            //$query = $this->db->get_where('tb_satuan', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
			
			$query =
			"
				SELECT * FROM tb_h_penjualan ".$cari."
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