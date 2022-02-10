<?php
	class M_gl_stock_produk extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_stock_produk($kode_kantor,$cari,$order_by,$offset,$limit,$tgl,$jam,$menit,$v_status_min)
		{
			$query = $this->db->query("CALL PROC_STOCK_PRODUK_2('". $kode_kantor ."','". $cari ."','". $order_by ."','". $offset ."','". $limit ."','". $tgl ."','". $jam ."','". $menit ."','".$v_status_min."')");
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function list_analisa_order($kode_kantor,$cari,$order_by,$offset,$limit,$tgl,$jam,$menit,$v_status_min)
		{
			$query = $this->db->query("CALL PROC_ANALISA_ORDER_2('". $kode_kantor ."','". $cari ."','". $order_by ."','". $offset ."','". $limit ."','". $tgl ."','". $jam ."','". $menit ."','".$v_status_min."')");
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		
		function count_stock_produk($cari)
		{
			$query = $this->db->query("SELECT COUNT(id_produk) AS JUMLAH FROM tb_produk WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' AND isProduk IN ('PRODUK','CONSUMABLE') AND (kode_produk LIKE  '%".$cari."%' OR nama_produk LIKE  '%".$cari."%' OR produksi_oleh LIKE  '%".$cari."%')");
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function sum_stock_produk($kode_kantor,$cari,$tgl,$jam,$menit)
		{
			$query = $this->db->query("CALL PROC_STOCK_PRODUK_PERSEDIAAN_2('". $kode_kantor ."','". $cari ."','". $tgl ."','". $jam ."','". $menit ."')");
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function list_laporan_detail_penjualan($cari,$order_by,$limit,$offset)
		{
			$query = 
					"
						SELECT
							A.*
							,COALESCE(B.no_faktur,'') AS no_faktur
							,COALESCE(B.no_antrian,'') AS no_antrian
							,COALESCE(B.no_costmer,'') AS no_costumer
							,COALESCE(B.nama_costumer,'') AS nama_costumer
							,COALESCE(B.tgl_h_penjualan,'') AS tgl_h_penjualan
							,COALESCE(B.type_h_penjualan,'') AS type_h_penjualan
						FROM tb_d_penjualan AS A
						INNER JOIN tb_h_penjualan AS B ON A.kode_kantor = B.kode_kantor AND A.id_h_penjualan = B.id_h_penjualan
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
		
		function count_laporan_detail_penjualan($cari)
		{
			$query = 
					"
						SELECT COUNT(A.id_d_penjualan) AS JUMLAH
						FROM tb_d_penjualan AS A
						INNER JOIN tb_h_penjualan AS B ON A.kode_kantor = B.kode_kantor AND A.id_h_penjualan = B.id_h_penjualan
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
		
		function list_laporan_detail_penerimaan($cari,$order_by,$limit,$offset)
		{
			$query = 
					"
						SELECT 
							A.*
							,COALESCE(B.no_surat_jalan,'') AS no_surat_jalan
							,COALESCE(B.nama_pengirim,'') AS nama_pengirim
							,COALESCE(B.cara_pengiriman,'') AS cara_pengiriman
							,COALESCE(DATE(B.tgl_terima),'0000-00-00') AS tgl_terima
							,COALESCE(C.no_h_pembelian,'') AS NO_PO
							,COALESCE(D.nama_supplier,'') AS nama_supplier
						FROM tb_d_penerimaan AS A
						INNER JOIN tb_h_penerimaan AS B ON A.kode_kantor = B.kode_kantor AND A.id_h_penerimaan = B.id_h_penerimaan
						LEFT JOIN tb_h_pembelian AS C ON A.kode_kantor = B.kode_kantor AND B.id_h_pembelian = C.id_h_pembelian
						LEFT JOIN tb_supplier AS D ON A.kode_kantor = D.kode_kantor AND C.id_supplier = D.id_supplier
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
		
		function count_laporan_detail_penerimaan($cari)
		{
			$query = 
					"
						SELECT 
							COUNT(A.id_d_penerimaan) AS JUMLAH
						FROM tb_d_penerimaan AS A
						INNER JOIN tb_h_penerimaan AS B ON A.kode_kantor = B.kode_kantor AND A.id_h_penerimaan = B.id_h_penerimaan
						LEFT JOIN tb_h_pembelian AS C ON A.kode_kantor = B.kode_kantor AND B.id_h_pembelian = C.id_h_pembelian
						LEFT JOIN tb_supplier AS D ON A.kode_kantor = D.kode_kantor AND C.id_supplier = D.id_supplier
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
		
		function list_laporan_detail_mutasi($cari,$order_by,$limit,$offset)
		{
			$query = 
					"
						SELECT
							A.*
							,COALESCE(B.no_faktur,'') AS no_faktur
							,COALESCE(B.no_faktur_penjualan,'') AS no_faktur_penjualan
							,COALESCE(DATE(B.tgl_h_penjualan),'') AS tgl_h_penjualan
							,COALESCE(B.status_penjualan,'') AS status_penjualan
							,COALESCE(B.sts_penjualan,'') AS sts_penjualan
							,
							CASE 
							WHEN A.status_konversi = '*' THEN
								A.jumlah * A.konversi
							ELSE
								A.jumlah / A.konversi
							END AS mutasi_fix
						FROM tb_d_mutasi AS A
						INNER JOIN tb_h_mutasi AS B ON A.kode_kantor = B.kode_kantor AND A.id_h_penjualan = B.id_h_penjualan
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
		
		function count_laporan_detail_mutasi($cari)
		{
			$query = 
					"
						SELECT
							COUNT(A.id_d_penjualan) AS JUMLAH
						FROM tb_d_mutasi AS A
						INNER JOIN tb_h_mutasi AS B ON A.kode_kantor = B.kode_kantor AND A.id_h_penjualan = B.id_h_penjualan
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
	
		function list_laporan_detail_retur($cari,$order_by,$limit,$offset)
		{
			$query = 
					"
						SELECT
							A.*
							,COALESCE(B.no_faktur,'') AS no_faktur
							,COALESCE(B.no_faktur_penjualan,'') AS no_faktur_penjualan
							,COALESCE(DATE(B.tgl_h_penjualan),'') AS tgl_h_penjualan
							,COALESCE(B.status_penjualan,'') AS status_penjualan
							,COALESCE(B.sts_penjualan,'') AS sts_penjualan
							,COALESCE(C.kode_supplier,'') AS kode_supplier
							,COALESCE(C.nama_supplier,'') AS nama_supplier
							,
							CASE 
								WHEN A.status_konversi = '*' THEN
									A.jumlah * A.konversi
								ELSE
									A.jumlah / A.konversi
								END AS retur_fix
						FROM tb_d_retur AS A
						INNER JOIN tb_h_retur AS B ON A.kode_kantor = B.kode_kantor AND A.id_h_penjualan = B.id_h_penjualan
						LEFT JOIN tb_supplier AS C ON A.kode_kantor = C.kode_kantor AND B.id_supplier = C.id_supplier
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
		
		function count_laporan_detail_retur($cari)
		{
			$query = 
					"
						SELECT
							COUNT(A.id_d_penjualan) AS JUMLAH
						FROM tb_d_retur AS A
						INNER JOIN tb_h_retur AS B ON A.kode_kantor = B.kode_kantor AND A.id_h_penjualan = B.id_h_penjualan
						LEFT JOIN tb_supplier AS C ON A.kode_kantor = C.kode_kantor AND B.id_supplier = C.id_supplier
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
	
		function list_histori_produk($kode_kantor,$id_produk,$dari,$sampai)
		{
			$query =
				"
					SELECT
						A.DATE_LIST
						,COALESCE(B.BELI,0) AS BELI
						,COALESCE(C.TERJUAL,0) AS TERJUAL
						,COALESCE(D.MUTASI_IN,0) AS MUTASI_IN
						,COALESCE(D.MUTASI_OUT,0) AS MUTASI_OUT
						,COALESCE(D.BERTAMBAH_GUDANG,0) AS BERTAMBAH_GUDANG
						,COALESCE(D.BERKURANG_GUDANG,0) AS BERKURANG_GUDANG
						,COALESCE(D.BERTAMBAH_TOKO,0) AS BERTAMBAH_TOKO
						,COALESCE(D.BERKURANG_TOKO,0) AS BERKURANG_TOKO
						,COALESCE(E.RETUR_PEMBELIAN,0) AS RETUR_PEMBELIAN
						,COALESCE(E.RETUR_PENJUALAN,0) AS RETUR_PENJUALAN
					From
					(
						select * from
						(select adddate('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) AS DATE_LIST from
						 (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t0,
						 (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t1,
						 (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t2,
						 (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t3,
						 (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t4) v
						where DATE_LIST BETWEEN DATE('".$dari."') AND DATE('".$sampai."')
					) AS A
					Left Join
					(
						SELECT DT_TERIMA,id_produk,SUM(BELI) AS BELI
						FROM
						(
							SELECT
								DATE(B.tgl_terima) As DT_TERIMA
								,A.id_produk
								,CASE WHEN (A.status_konversi = '*') THEN
									SUM(A.diterima_satuan_beli * A.konversi)
								Else
									SUM(A.diterima_satuan_beli / A.konversi)
								END As BELI
							FROM tb_d_penerimaan AS A
							INNER JOIN tb_h_penerimaan AS B ON A.kode_kantor = B.kode_kantor AND A.id_h_penerimaan = B.id_h_penerimaan
							WHERE A.id_produk = '".$id_produk."' AND A.kode_kantor = '".$kode_kantor."'
							GROUP BY B.tgl_terima,A.id_produk,A.status_konversi
						) AS BELI
						GROUP BY DT_TERIMA,id_produk
					) AS B ON A.DATE_LIST = B.DT_TERIMA
					Left Join
					(
						SELECT DT_PENJUALAN,id_produk,SUM(TERJUAL) AS TERJUAL
						FROM
						(
							SELECT
								DATE(A.tgl_h_penjualan) As DT_PENJUALAN
								,B.id_produk
								,CASE WHEN (B.status_konversi = '*') THEN
									SUM(B.jumlah * B.konversi)
								Else
									SUM(B.jumlah / B.konversi)
								END As TERJUAL
							FROM tb_h_penjualan AS A
							INNER JOIN tb_d_penjualan AS B ON A.id_h_penjualan = B.id_h_penjualan
							AND A.kode_kantor = B.kode_kantor
							WHERE B.id_produk = '".$id_produk."' AND A.kode_kantor = '".$kode_kantor."' AND COALESCE(A.sts_penjualan,'') = 'SELESAI' 
							-- AND B.isReady = 1
							GROUP BY A.tgl_h_penjualan,B.id_produk,B.status_konversi
						) AS TERJUAL
						GROUP BY DT_PENJUALAN,id_produk
					) AS C ON A.DATE_LIST = C.DT_PENJUALAN
					Left Join
					(
						SELECT
							DATE(tgl_h_penjualan) As DT_MUTASI
							,id_produk
							,SUM(MUTASI_IN) AS MUTASI_IN
							,SUM(MUTASI_OUT) AS MUTASI_OUT
							,SUM(BERTAMBAH_GUDANG) AS BERTAMBAH_GUDANG
							,SUM(BERKURANG_GUDANG) AS BERKURANG_GUDANG
							,SUM(BERTAMBAH_TOKO) AS BERTAMBAH_TOKO
							,SUM(BERKURANG_TOKO) AS BERKURANG_TOKO
						From
						(
							SELECT
								A.tgl_h_penjualan
								,B.id_produk
								,CASE WHEN A.status_penjualan = 'MUTASI-IN' THEN
									CASE WHEN (B.status_konversi = '*') THEN
										SUM(B.jumlah * B.konversi)
									Else
										SUM(B.jumlah / B.konversi)
									End
								Else
									0
								END As MUTASI_IN
								,CASE WHEN A.status_penjualan = 'MUTASI-OUT' THEN
									CASE WHEN (B.status_konversi = '*') THEN
										SUM(B.jumlah * B.konversi)
									Else
										SUM(B.jumlah / B.konversi)
									End
								Else
									0
								END As MUTASI_OUT
								,CASE WHEN A.status_penjualan = 'Mutasi-Toko-Gudang' THEN
									CASE WHEN (B.status_konversi = '*') THEN
										SUM(B.jumlah * B.konversi)
									Else
										SUM(B.jumlah / B.konversi)
									End
								Else
									0
								END As BERTAMBAH_GUDANG
								,CASE WHEN (A.status_penjualan = 'Mutasi-Toko') OR ( (A.status_penjualan <> 'Mutasi-Toko-Gudang' AND A.status_penjualan <> 'Mutasi-Toko' AND A.status_penjualan <> 'Mutasi-Toko-Toko' AND A.status_penjualan <> 'Mutasi-Toko Luar-Toko' AND A.status_penjualan <> 'MUTASI-IN' AND A.status_penjualan <> 'MUTASI-OUT' AND A.status_penjualan <> 'STOCK-OPNAME')) THEN
									CASE WHEN (A.status_penjualan = 'Mutasi-Toko') THEN
										CASE WHEN (B.status_konversi = '*') THEN
											SUM(B.jumlah_req * B.konversi)
										Else
											SUM(B.jumlah_req / B.konversi)
										End
									Else
										CASE WHEN (B.status_konversi = '*') THEN
											SUM(B.jumlah * B.konversi)
										Else
											SUM(B.jumlah / B.konversi)
										End
									End
								Else
									0
								END As BERKURANG_GUDANG
								,CASE WHEN (A.status_penjualan = 'Mutasi-Toko Luar-Toko') OR (A.status_penjualan = 'Mutasi-Toko') THEN
									CASE WHEN (A.status_penjualan = 'Mutasi-Toko') THEN
										CASE WHEN (B.status_konversi = '*') THEN
											SUM(B.jumlah_req * B.konversi)
										Else
											SUM(B.jumlah_req / B.konversi)
										End
									Else
										CASE WHEN (B.status_konversi = '*') THEN
											SUM(B.jumlah * B.konversi)
										Else
											SUM(B.jumlah / B.konversi)
										End
									End
								Else
									0
								END As BERTAMBAH_TOKO
								,CASE WHEN (A.status_penjualan = 'Mutasi-Toko-Toko') OR (A.status_penjualan = 'Mutasi-Toko-Gudang') THEN
									CASE WHEN (B.status_konversi = '*') THEN
										SUM(B.jumlah * B.konversi)
									Else
										SUM(B.jumlah / B.konversi)
									End
								Else
									0
								END As BERKURANG_TOKO
							FROM tb_h_mutasi AS A
							INNER JOIN tb_d_mutasi AS B ON A.id_h_penjualan = B.id_h_penjualan AND A.kode_kantor = B.kode_kantor
							WHERE B.id_produk = '".$id_produk."' AND A.kode_kantor = '".$kode_kantor."' AND COALESCE(A.sts_penjualan,'') = 'SELESAI'
							GROUP BY A.tgl_h_penjualan,B.id_produk,A.status_penjualan
						) AS AA
						GROUP BY DATE(tgl_h_penjualan),id_produk
					) AS D ON A.DATE_LIST = D.DT_MUTASI
					Left Join
					(
						SELECT DATE(tgl_h_penjualan) AS DT_RETUR, id_produk, SUM(RETUR_PEMBELIAN) AS RETUR_PEMBELIAN, SUM(RETUR_PENJUALAN) AS RETUR_PENJUALAN
						From
						(
							SELECT
								A.tgl_h_penjualan
								,B.id_produk
								,CASE WHEN A.status_penjualan = 'RETUR-PEMBELIAN' THEN
									CASE WHEN (B.status_konversi = '*') THEN
										SUM(B.jumlah * B.konversi)
									Else
										SUM(B.jumlah / B.konversi)
									End
								Else
									0
								END As RETUR_PEMBELIAN
								,CASE WHEN A.status_penjualan = 'RETUR-PENJUALAN' THEN
									CASE WHEN (B.status_konversi = '*') THEN
										SUM(B.jumlah * B.konversi)
									Else
										SUM(B.jumlah / B.konversi)
									End
								Else
									0
								END As RETUR_PENJUALAN
							FROM tb_h_retur AS A
							INNER JOIN tb_d_retur AS B ON A.id_h_penjualan = B.id_h_penjualan AND A.kode_kantor = B.kode_kantor
							WHERE B.id_produk = '".$id_produk."' AND A.kode_kantor = '".$kode_kantor."' AND COALESCE(A.sts_penjualan,'') = 'SELESAI'
							GROUP BY A.tgl_h_penjualan,B.id_produk,A.status_penjualan
						) AS AA
						GROUP BY tgl_h_penjualan, id_produk
					) AS E ON A.DATE_LIST = E.DT_RETUR;
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
	
		function list_rata_produk_terjual($dari,$sampai,$cari,$order_by,$limit,$offset)
		{
			$query = 
					"
						SELECT id_produk,kode_produk,nama_produk,satuan_jual,SUM(jumlah_konversi_format) AS jumlah,(SUM(jumlah) / (DATEDIFF('".$sampai."','".$dari."')+1)) AS RATA
						FROM
						(
							SELECT
								A.*
								,
								CASE WHEN A.status_konversi = '*' THEN
									A.jumlah * A.konversi
								ELSE
									A.jumlah / A.konversi
								END AS jumlah_konversi_format
								,COALESCE(B.no_faktur,'') AS no_faktur
								,COALESCE(B.no_costmer,'') AS no_costumer
								,COALESCE(B.nama_costumer,'') AS nama_costumer
								,COALESCE(B.sts_penjualan,'') AS sts_penjualan
								,COALESCE(DATE(B.tgl_h_penjualan),'0000-00-00') AS tgl_h_penjualan
								,COALESCE(C.kode_produk,'') AS kode_produk
								,COALESCE(C.nama_produk,'') AS nama_produk
							FROM tb_d_penjualan AS A
							INNER JOIN tb_h_penjualan AS B ON A.id_h_penjualan = B.id_h_penjualan AND A.kode_kantor = B.kode_kantor
							LEFT JOIN tb_produk AS C ON A.id_produk = C.id_produk AND A.kode_kantor = C.kode_kantor
						) AS AA
						
						".$cari."
						
						GROUP BY id_produk,kode_produk,nama_produk,satuan_jual
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
		
		function count_rata_produk_terjual($cari)
		{
			$query = 
					"
						SELECT COUNT(id_produk) AS JUMLAH
						FROM
						(
							SELECT id_produk,kode_produk,nama_produk,satuan_jual,SUM(jumlah_konversi_format) AS jumlah,(SUM(jumlah_konversi_format) / DATEDIFF('2020-08-05','2020-08-03')) AS RATA
							FROM
							(
								SELECT
									A.*
									,
									CASE WHEN A.status_konversi = '*' THEN
										A.jumlah * A.konversi
									ELSE
										A.jumlah / A.konversi
									END AS jumlah_konversi_format
									,COALESCE(B.no_faktur,'') AS no_faktur
									,COALESCE(B.no_costmer,'') AS no_costumer
									,COALESCE(B.nama_costumer,'') AS nama_costumer
									,COALESCE(B.sts_penjualan,'') AS sts_penjualan
									,COALESCE(DATE(B.tgl_h_penjualan),'0000-00-00') AS tgl_h_penjualan
									,COALESCE(C.kode_produk,'') AS kode_produk
									,COALESCE(C.nama_produk,'') AS nama_produk
								FROM tb_d_penjualan AS A
								INNER JOIN tb_h_penjualan AS B ON A.id_h_penjualan = B.id_h_penjualan AND A.kode_kantor = B.kode_kantor
								LEFT JOIN tb_produk AS C ON A.id_produk = C.id_produk AND A.kode_kantor = C.kode_kantor
							) AS AA
							".$cari."
							GROUP BY id_produk,kode_produk,nama_produk,satuan_jual
						) AS AA
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
	}
?>