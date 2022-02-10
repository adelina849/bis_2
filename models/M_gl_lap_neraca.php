<?php
	class M_gl_lap_neraca extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function get_kas
		(
			$kode_kantor
			,$id_bank
			,$ref
			,$cari
			,$tgl_sampai
		)
		{
			$query = "CALL PROC_SUM_JURNAL_2('".$kode_kantor."','".$id_bank."','".$ref."','".$cari."','".$tgl_sampai."');";
			
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
		
		function get_persediaan
		(
			$kode_kantor
			,$cari
			,$tgl_sampai
			,$jam
			,$menit
		)
		{
			$query = "CALL PROC_STOCK_PRODUK_PERSEDIAAN_2('".$kode_kantor."','".$cari."','".$tgl_sampai."','".$jam."','".$menit."');";
			
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
		
		function get_piutang_awal_tr
		(
			$kode_kantor
			,$tgl_sampai
			,$tgl_backup
		)
		{
			$query = "
			
				SELECT
					COALESCE(SUM(piutang_awal),0) As piutang_awal
					,COALESCE(SUM(bayar_piutang_awal),0) AS bayar_piutang_awal
					,COALESCE(SUM(SISA_PIUTANG_AWAL),0) AS SISA_PIUTANG_AWAL
					,COALESCE(SUM(SISA_PIUTANG_TR),0) AS SISA_PIUTANG_TR
					-- ,SUM(TABUNGAN) AS TABUNGAN
					-- ,SUM(SISA_TABUNGAN) AS SISA_TABUNGAN
					,0 AS TABUNGAN
					,0 AS SISA_TABUNGAN
				From
				(
					SELECT
						SUM(piutang_awal) As piutang_awal
						,SUM(bayar_piutang) AS bayar_piutang_awal
						,SUM(piutang_awal) - SUM(bayar_piutang) AS SISA_PIUTANG_AWAL
						,SUM(SISA_PIUTANG_TR) AS SISA_PIUTANG_TR
						-- ,SUM(TABUNGAN) AS TABUNGAN,SUM(SISA_TABUNGAN) AS SISA_TABUNGAN
						,0 AS TABUNGAN
						,0 AS SISA_TABUNGAN
					From
					(
						SELECT
							A.id_costumer , A.id_kat_costumer
							,A.no_costumer,A.nama_lengkap
							,CASE WHEN
								(
									CONCAT(left(COALESCE(A.tgl_piutang_awal,''),10),' ',right(A.tgl_ins,8)) <  CONCAT('".$tgl_sampai."',' ','23:59:59')
									AND
									CONCAT(left(COALESCE(A.tgl_piutang_awal,''),10),' ',right(A.tgl_ins,8)) >  '".$tgl_backup."'
								)
							THEN
								piutang_awal
							Else
								0
							END As piutang_awal
							,A.no_ktp , A.alamat_rumah_sekarang, A.hp
							, COALESCE(A.email_costumer,'') AS email_costumer
							, A.ket_costumer, A.kode_kantor
							,COALESCE(SUM(C.nominal),0)  AS BAYAR_PIUTANG
							,
							ABS(
								( (COALESCE(D.nominal_transaksi,0)) + (COALESCE(D.donasi,0)) + (COALESCE(D.biaya,0)) )
								-
								( (COALESCE(D.bayar_detail,0)) + (COALESCE(D.diskon,0)) + (COALESCE(D.nominal_retur,0)) )
							)
							AS SISA_PIUTANG_TR
							-- ,COALESCE(BB.TABUNGAN,0) AS TABUNGAN,COALESCE(BB.SISA_TABUNGAN,0) AS SISA_TABUNGAN
						FROM tb_costumer A
						Left Join
						(
							SELECT id_costumer,kode_kantor,nominal,isPiutang
							From tb_uang_masuk
							Where isPiutang = 1
							AND CONCAT(left(COALESCE(tgl_uang_masuk,''),10),' ',right(tgl_ins,8)) <  CONCAT('".$tgl_sampai."',' ','23:59:59')
							AND CONCAT(left(COALESCE(tgl_uang_masuk,''),10),' ',right(tgl_ins,8)) >  '".$tgl_backup."'
						) AS C ON A.id_costumer = C.id_costumer AND A.kode_kantor = C.kode_kantor AND C.isPiutang = 1
						Left Join
						(
							SELECT A.id_costumer,A.kode_kantor,A.kode_kantor_costumer
								,SUM(COALESCE(G.BAYAR,0)) AS bayar_detail -- SUM(A.bayar_detail) AS bayar_detail
								,SUM(COALESCE(C.TR,0)) AS nominal_transaksi
								,SUM(A.donasi) AS donasi
								,SUM(A.biaya) AS biaya
								,SUM(A.diskon) AS diskon
								,SUM(A.nominal_retur) + SUM(COALESCE(F.NOMINAL_DET_RTR,0)) AS nominal_retur
							From tb_h_penjualan AS A
							Inner Join
							(
								SELECT id_h_penjualan, kode_kantor, SUM(jumlah * (harga-diskon)) AS TR, SUM(jumlah * harga_dasar_ori) AS MDL
								From tb_d_penjualan
								GROUP BY  id_h_penjualan, kode_kantor
							) AS C ON A.id_h_penjualan = C.id_h_penjualan AND A.kode_kantor = C.kode_kantor
							Left Join
							(
								SELECT no_faktur_penjualan,SUM(NOMINAL_DET_RTR) AS NOMINAL_DET_RTR, kode_kantor
								From
								(
									SELECT A.id_h_penjualan,A.no_faktur_penjualan,(harga * jumlah) AS NOMINAL_DET_RTR,A.kode_kantor
										,B.id_produk
										,CASE
											WHEN B.status_konversi = '*' THEN
												B.jumlah * B.konversi
											Else
												B.jumlah / B.konversi
											END As jumlah_konversi
									FROM tb_h_retur AS A
									LEFT JOIN tb_d_retur AS B ON A.id_h_penjualan = B.id_h_penjualan AND A.kode_kantor = B.kode_kantor WHERE A.status_penjualan = 'RETUR-PENJUALAN'
								) AS BB GROUP BY no_faktur_penjualan,kode_kantor
							) AS F ON A.no_faktur = F.no_faktur_penjualan AND A.kode_kantor = F.kode_kantor
							Left Join
							(
								SELECT id_h_penjualan,kode_kantor,SUM(nominal) AS BAYAR
								From tb_d_penjualan_bayar
								GROUP BY id_h_penjualan,kode_kantor
							) AS G ON A.id_h_penjualan = G.id_h_penjualan AND A.kode_kantor = G.kode_kantor
							-- WHERE A.sts_penjualan NOT IN ('PENDING','DIBATALKAN')
							WHERE A.sts_penjualan = 'SELESAI'
							AND LEFT(A.ket_penjualan,7) <>'DIHAPUS' 
							AND COALESCE(C.TR,0) > 0
							AND (COALESCE(G.BAYAR,0)) < ((COALESCE(C.TR,0) + A.donasi + A.biaya) - (A.diskon + A.nominal_retur))
							AND CONCAT(left(COALESCE(A.tgl_h_penjualan,''),10),' ',right(A.tgl_ins,8)) <  CONCAT('".$tgl_sampai."',' ','23:59:59')
							AND CONCAT(left(COALESCE(A.tgl_h_penjualan,''),10),' ',right(A.tgl_ins,8)) >  '".$tgl_backup."'
							GROUP BY A.id_costumer,A.kode_kantor,A.kode_kantor_costumer
						) AS D ON A.id_costumer = D.id_costumer AND A.kode_kantor = D.kode_kantor_costumer AND (D.bayar_detail) < ((D.nominal_transaksi + D.donasi + D.biaya) - (D.diskon + D.nominal_retur))
						
						/*
						Left Join
						(

							SELECT
								(A.id_costumer) As id_costumer
								,(A.kode_kantor) AS kode_kantor
								,SUM(A.nominal) AS TABUNGAN
								,(COALESCE(B.BAYAR_PIUTANG_TABUNGAN,0)) AS BAYAR_PIUTANG_TABUNGAN
								,(COALESCE(C.BAYAR_TR_DENG_TABUNGAN,0)) AS BAYAR_TR_DENG_TABUNGAN
								,(COALESCE(D.BAYAR_PIUTANG_TABUNGAN_KELUAR,0)) AS BAYAR_PIUTANG_TABUNGAN_KELUAR
								,SUM(A.nominal) - ((COALESCE(B.BAYAR_PIUTANG_TABUNGAN,0)) + (COALESCE(C.BAYAR_TR_DENG_TABUNGAN,0)) + (COALESCE(D.BAYAR_PIUTANG_TABUNGAN_KELUAR,0))) AS SISA_TABUNGAN
							FROM tb_uang_masuk AS A
							Left Join
							(
								-- PEMBAYARAN PIUTANG DARI TABUNGAN
								SELECT id_costumer,kode_kantor,SUM(nominal) AS BAYAR_PIUTANG_TABUNGAN
								From tb_uang_masuk
								Where isTabungan = 1 And isPiutang = 1
								AND CONCAT(left(COALESCE(tgl_uang_masuk,''),10),' ',right(tgl_ins,8)) <  CONCAT('".$tgl_sampai."',' ','23:59:59')
								AND CONCAT(left(COALESCE(tgl_uang_masuk,''),10),' ',right(tgl_ins,8)) >  '".$tgl_backup."'
								GROUP BY id_costumer,kode_kantor
								-- PEMBAYARAN PIUTANG DARI TABUNGAN
							) AS B ON A.id_costumer = B.id_costumer AND A.kode_kantor = B.kode_kantor
							Left Join
							(
								-- PEMBAYARAN PENJUALAN DENGAN TABUNGAN
								SELECT id_costumer,kode_kantor,SUM(nominal) AS BAYAR_TR_DENG_TABUNGAN
								From tb_d_penjualan_bayar
								Where isTabungan = 1
								AND CONCAT(left(COALESCE(tgl_bayar,''),10),' ',right(tgl_ins,8)) <  CONCAT('".$tgl_sampai."',' ','23:59:59')
								AND CONCAT(left(COALESCE(tgl_bayar,''),10),' ',right(tgl_ins,8)) >  '".$tgl_backup."'
								GROUP BY id_costumer,kode_kantor
								-- PEMBAYARAN PENJUALAN DENGAN TABUNGAN
							) AS C ON A.id_costumer = C.id_costumer AND A.kode_kantor = C.kode_kantor
							Left Join
							(
								-- PEMBAYARAN PIUTANG DARI TABUNGAN UANG KELUAR
								SELECT id_costumer,kode_kantor,SUM(nominal) AS BAYAR_PIUTANG_TABUNGAN_KELUAR
								From tb_uang_keluar
								Where isTabungan = 1
								AND CONCAT(left(COALESCE(tgl_dikeluarkan,''),10),' ',right(tgl_ins,8)) <  CONCAT('".$tgl_sampai."',' ','23:59:59')
								AND CONCAT(left(COALESCE(tgl_dikeluarkan,''),10),' ',right(tgl_ins,8)) >  '".$tgl_backup."'
								GROUP BY id_costumer,kode_kantor
								-- PEMBAYARAN PIUTANG DARI TABUNGAN UANG KELUAR
							) AS D ON A.id_costumer = D.id_costumer AND A.kode_kantor = D.kode_kantor
							Where A.isTabungan = 1 And A.isPiutang = 0
							AND CONCAT(left(COALESCE(A.tgl_uang_masuk,''),10),' ',right(A.tgl_ins,8)) <  CONCAT('".$tgl_sampai."',' ','23:59:59')
							AND CONCAT(left(COALESCE(A.tgl_uang_masuk,''),10),' ',right(A.tgl_ins,8)) >  '".$tgl_backup."'
							Group By
							(A.id_costumer)
							,(A.kode_kantor)
							,(COALESCE(B.BAYAR_PIUTANG_TABUNGAN,0))
							,(COALESCE(C.BAYAR_TR_DENG_TABUNGAN,0))
							,(COALESCE(D.BAYAR_PIUTANG_TABUNGAN_KELUAR,0))
						) AS BB ON A.id_costumer = BB.id_costumer AND A.kode_kantor = BB.kode_kantor
						*/
						WHERE (A.no_costumer LIKE '%%' OR  A.nama_lengkap LIKE '%%')
						AND A.kode_kantor = '".$kode_kantor."'
						-- AND CONCAT(left(COALESCE(A.tgl_uang_masuk,''),10),' ',right(A.tgl_ins,8)) <  CONCAT('".$tgl_sampai."',' ','23:59:59')
						AND A.tgl_ins <  CONCAT('".$tgl_sampai."',' ','23:59:59')
						AND A.tgl_ins >  '".$tgl_backup."'
						Group By
							A.id_costumer , A.id_kat_costumer
							-- ,COALESCE(B.nama_kat_costumer,'')
							,A.no_costumer,A.nama_lengkap,A.piutang_awal
							,A.no_ktp , A.alamat_rumah_sekarang, A.hp
							, COALESCE(A.email_costumer,'')
							, A.ket_costumer
							-- ,COALESCE(BB.SISA_TABUNGAN,0)
						-- ORDER BY A.nama_lengkap ASC LIMIT 0,50
					) AS AA
					Union All
					SELECT
						tr_omset
						,tr_modal
						,tr_laba_kotor
						,tr_pemasukan
						,tr_pengeluaran
						,tr_laba_bersih
					FROM tb_backup_all WHERE kode_kantor = '".$kode_kantor."' AND back_id_kat = 'NERACAPIUTANG' AND back_time = '".$tgl_backup."'
				) AS AAA
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
	
	
		function get_hutang_awal
		(
			$kode_kantor
			,$tgl_sampai
		)
		{
			$query = "
			
				SELECT SUM(hutang_awal) - SUM(BAYAR_HUTANG) AS SISA_HUTANG_AWAL
				From
				(
					SELECT A.id_supplier,A.id_kat_supplier,A.kode_supplier,A.no_supplier,A.nama_supplier,A.pemilik_supplier,A.situ
					,A.siup,A.bidang,A.ket_supplier,A.avatar,A.avatar_url,A.email,A.tlp,A.alamat,A.limit_budget,A.allow_budget,A.bank,A.norek,A.hutang_awal
						, COALESCE(SUM(D.nominal),0) AS BAYAR_HUTANG

					FROM tb_supplier AS A
					Left Join
					(
						SELECT id_supplier,kode_kantor,SUM(nominal) AS nominal
						From tb_uang_keluar
						WHERE CONCAT(left(COALESCE(tgl_dikeluarkan,''),10),' ',right(tgl_ins,8)) <  CONCAT('".$tgl_sampai."',' ','23:59:59')
						GROUP BY id_supplier,kode_kantor
					) AS D ON A.id_supplier = D.id_supplier AND A.kode_kantor = D.kode_kantor

					WHERE (A.kode_supplier LIKE '%%' OR A.nama_supplier LIKE '%%') AND A.kode_kantor = '".$kode_kantor."'
					
					AND CONCAT(left(COALESCE(A.tgl_hutang_awal,''),10),' ',right(A.tgl_ins,8)) <  CONCAT('".$tgl_sampai."',' ','23:59:59')
					AND CONCAT(left(COALESCE(A.tgl_hutang_awal,''),10),' ',right(A.tgl_ins,8)) >  ''
					Group By
					A.id_supplier , A.id_kat_supplier, A.kode_supplier, A.no_supplier, A.nama_supplier, A.pemilik_supplier, A.situ
					,A.siup,A.bidang,A.ket_supplier,A.avatar,A.avatar_url,A.email,A.tlp,A.alamat,A.limit_budget,A.allow_budget,A.bank,A.norek,A.hutang_awal
				) AS AA
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
	
		
		
		function get_hutang_tr
		(
			$kode_kantor
			,$tgl_sampai
			,$tgl_backup
		)
		{
			/*
			SELECT SUM(HUTANG) AS HUTANG FROM
				(
				SELECT ABS(SUM(SISA)) AS HUTANG FROM
				(
				SELECT A.id_h_pembelian, A.no_h_pembelian,A.id_supplier,CASE WHEN A.tgl_jatuh_tempo = '0000-00-00' THEN '1900-01-01' ELSE COALESCE(A.tgl_jatuh_tempo,'1900-01-01') END AS tgl_jatuh_tempo_ori
				,TIMESTAMPDIFF(DAY, DATE(NOW()), CASE WHEN A.tgl_jatuh_tempo = '0000-00-00' THEN '1900-01-01' ELSE COALESCE(A.tgl_jatuh_tempo,'1900-01-01') END ) AS SISA_TEMPO
						,COALESCE(D.BAYAR,0) AS NOMINAL_BAYAR -- A.bayar_detail AS NOMINAL_BAYAR -- 
						,COALESCE(B.GRANDTOTAL,0) AS GRANDTOTAL -- A.nominal_transaksi AS GRANDTOTAL -- 
						,( (COALESCE(D.BAYAR,0) + A.pengurang +  (A.nominal_retur + COALESCE(E.NOMINAL_RET,0) ) ) -  (COALESCE(B.GRANDTOTAL,0) + A.biaya_tambahan)  ) AS SISA
						,CASE WHEN ( ( (COALESCE(D.BAYAR,0) + A.pengurang +  (A.nominal_retur + COALESCE(E.NOMINAL_RET,0) ) ) -  (COALESCE(B.GRANDTOTAL,0) + A.biaya_tambahan)  ) <= 1 ) OR (A.sts_pembelian = 'LUNAS') THEN
							'LUNAS'
						Else
							'BELUM LUNAS'
						END As STATUS_LUNAS
						,COALESCE(B.JUM,0) AS JUM
						,COALESCE(B.JUMACC,0) AS JUMACC
						,COALESCE(B.jumlah_beli,0) AS jumlah_beli
						-- ,COALESCE(B.jumlah_terima,0) AS jumlah_terima
						-- ,ROUND(COALESCE(B.jumlah_terima,0) / (COALESCE(B.jumlah_beli,0)/100)) PERSENTASE
						,A.tgl_h_pembelian,A.tgl_ins
						,A.biaya_tambahan,A.pengurang,(A.nominal_retur + COALESCE(E.NOMINAL_RET,0) ) AS nominal_retur
					FROM tb_h_pembelian AS A
				Left Join
				(
					SELECT
						id_h_pembelian
						,SUM(jumlah_beli) AS jumlah_beli
						-- ,SUM(jumlah_terima) As jumlah_terima
						,SUM(GRANDTOTAL) AS GRANDTOTAL
						,SUM(JUM) AS JUM
						,SUM(JUMACC) AS JUMACC
					From
					(
						SELECT A.id_h_pembelian,A.id_produk
							,CASE
								WHEN A.status_konversi = '*' THEN
									SUM(A.jumlah * A.konversi)
								Else
									SUM(A.jumlah / A.konversi)
								END As jumlah_beli
							-- ,COALESCE(B.jumlah_terima,0) AS jumlah_terima
							,CASE
							WHEN A.optr_diskon = '%' THEN
								SUM(A.jumlah * (A.harga - ((A.harga / 100) * A.diskon)))
							Else
								SUM(A.jumlah * (A.harga - A.diskon))
							End
							AS GRANDTOTAL
							,COUNT(A.id_h_pembelian) AS JUM
							,SUM(A.acc) AS JUMACC
						From tb_d_pembelian AS A
						
						Left Join
						(
							SELECT A.id_h_pembelian,COALESCE(B.id_produk,'') AS id_produk
								,CASE
									WHEN B.status_konversi = '*' THEN
										SUM(B.diterima_satuan_beli * konversi)
									Else
										SUM(B.diterima_satuan_beli / konversi)
									END As jumlah_terima
								,A.kode_kantor
							FROM tb_h_penerimaan AS A
							INNER JOIN tb_d_penerimaan AS B ON A.id_h_penerimaan = B.id_h_penerimaan AND A.kode_kantor = B.kode_kantor
							GROUP BY A.id_h_pembelian,COALESCE(B.id_produk,''),A.kode_kantor
						) AS B ON A.id_h_pembelian = B.id_h_pembelian AND A.id_produk = B.id_produk AND A.kode_kantor = B.kode_kantor
						
						WHERE A.kode_kantor = '".$kode_kantor."'
						GROUP BY A.id_h_pembelian,A.id_produk,A.status_konversi
						-- ,COALESCE(B.jumlah_terima,0)
						,A.optr_diskon
					) AS AA
					GROUP BY id_h_pembelian
				) AS B ON A.id_h_pembelian = B.id_h_pembelian
				Left Join
				(
					SELECT id_h_pembelian,kode_kantor,SUM(nominal) AS BAYAR
					From tb_d_pembelian_bayar
					WHERE kode_kantor = '".$kode_kantor."'
					GROUP BY id_h_pembelian,kode_kantor
				) AS D ON A.id_h_pembelian = D.id_h_pembelian AND A.kode_kantor = D.kode_kantor
				LEFT JOIN
				(
					SELECT A.id_h_penjualan,A.no_faktur,A.no_faktur_penjualan,A.kode_kantor,COALESCE(B.NOMINAL_RET,0) AS NOMINAL_RET
					FROM tb_h_retur AS A
					Left Join
					(
						SELECT id_h_penjualan,kode_kantor,SUM(jumlah * harga) AS NOMINAL_RET
						From tb_d_retur
						GROUP BY id_h_penjualan,kode_kantor
					) AS B ON A.id_h_penjualan = B.id_h_penjualan AND A.kode_kantor = B.kode_kantor
					WHERE A.kode_kantor = '".$kode_kantor."' 
					-- AND A.no_faktur_penjualan = '3020190600093';
				) AS E ON A.no_h_pembelian = E.no_faktur_penjualan AND A.kode_kantor = E.kode_kantor
					WHERE A.kode_kantor = '".$kode_kantor."' 
					AND CONCAT(left(COALESCE(A.tgl_h_pembelian,''),10),' ',right(A.tgl_ins,8)) <  CONCAT('".$tgl_sampai."',' ','23:59:59')
					AND CONCAT(left(COALESCE(A.tgl_h_pembelian,''),10),' ',right(A.tgl_ins,8)) >  '".$tgl_backup."'
					-- ORDER BY A.tgl_h_pembelian DESC,A.tgl_ins DESC,C.nama_supplier ASC
				) AS AA
				Union All
				SELECT back_nomina AS HUTANG FROM tb_backup_all WHERE back_id_kat = 'NERACAHUTANG' AND back_time = '".$tgl_backup."' AND kode_kantor = '".$kode_kantor."'
				) AS AA


			";
		*/
		
			$query = "
			
				SELECT SUM(HUTANG) AS HUTANG FROM
				(
				SELECT ABS(SUM(SISA)) AS HUTANG FROM
				(
				SELECT A.id_h_pembelian, A.no_h_pembelian,A.id_supplier,CASE WHEN A.tgl_jatuh_tempo = '0000-00-00' THEN '1900-01-01' ELSE COALESCE(A.tgl_jatuh_tempo,'1900-01-01') END AS tgl_jatuh_tempo_ori
				,TIMESTAMPDIFF(DAY, DATE(NOW()), CASE WHEN A.tgl_jatuh_tempo = '0000-00-00' THEN '1900-01-01' ELSE COALESCE(A.tgl_jatuh_tempo,'1900-01-01') END ) AS SISA_TEMPO
						,COALESCE(D.BAYAR,0) AS NOMINAL_BAYAR 
						,COALESCE(B.GRANDTOTAL,0) AS GRANDTOTAL
						,( (COALESCE(D.BAYAR,0) + A.pengurang +  (A.nominal_retur + COALESCE(E.NOMINAL_RET,0) ) ) -  (COALESCE(B.GRANDTOTAL,0) + A.biaya_tambahan)  ) AS SISA
						,CASE WHEN ( ( (COALESCE(D.BAYAR,0) + A.pengurang +  (A.nominal_retur + COALESCE(E.NOMINAL_RET,0) ) ) -  (COALESCE(B.GRANDTOTAL,0) + A.biaya_tambahan)  ) <= 1 ) OR (A.sts_pembelian = 'LUNAS') THEN
							'LUNAS'
						Else
							'BELUM LUNAS'
						END As STATUS_LUNAS
						,COALESCE(B.JUM,0) AS JUM
						,COALESCE(B.JUMACC,0) AS JUMACC
						,COALESCE(B.jumlah_beli,0) AS jumlah_beli
						,A.tgl_h_pembelian,A.tgl_ins
						,A.biaya_tambahan,A.pengurang,(A.nominal_retur + COALESCE(E.NOMINAL_RET,0) ) AS nominal_retur
					FROM tb_h_pembelian AS A
				Left Join
				(
					SELECT
						id_h_pembelian
						,SUM(jumlah_beli) AS jumlah_beli
						,SUM(GRANDTOTAL) AS GRANDTOTAL
						,SUM(JUM) AS JUM
						,SUM(JUMACC) AS JUMACC
					From
					(
						SELECT A.id_h_pembelian,A.id_produk
							,CASE
								WHEN A.status_konversi = '*' THEN
									SUM(A.jumlah * A.konversi)
								Else
									SUM(A.jumlah / A.konversi)
								END As jumlah_beli
							,CASE
							WHEN A.optr_diskon = '%' THEN
								SUM(A.jumlah * (A.harga - ((A.harga / 100) * A.diskon)))
							Else
								SUM(A.jumlah * (A.harga - A.diskon))
							End
							AS GRANDTOTAL
							,COUNT(A.id_h_pembelian) AS JUM
							,SUM(A.acc) AS JUMACC
						From tb_d_pembelian AS A
						WHERE A.kode_kantor = '".$kode_kantor."'
						GROUP BY A.id_h_pembelian,A.id_produk,A.status_konversi
						,A.optr_diskon
					) AS AA
					GROUP BY id_h_pembelian
				) AS B ON A.id_h_pembelian = B.id_h_pembelian
				Left Join
				(
					SELECT id_h_pembelian,kode_kantor,SUM(nominal) AS BAYAR
					From tb_d_pembelian_bayar
					WHERE kode_kantor = '".$kode_kantor."'
					GROUP BY id_h_pembelian,kode_kantor
				) AS D ON A.id_h_pembelian = D.id_h_pembelian AND A.kode_kantor = D.kode_kantor
				LEFT JOIN
				(
					SELECT A.id_h_penjualan,A.no_faktur,A.no_faktur_penjualan,A.kode_kantor,COALESCE(B.NOMINAL_RET,0) AS NOMINAL_RET
					FROM tb_h_retur AS A
					Left Join
					(
						SELECT id_h_penjualan,kode_kantor,SUM(jumlah * harga) AS NOMINAL_RET
						From tb_d_retur
						GROUP BY id_h_penjualan,kode_kantor
					) AS B ON A.id_h_penjualan = B.id_h_penjualan AND A.kode_kantor = B.kode_kantor
					WHERE A.kode_kantor = '".$kode_kantor."'
				) AS E ON A.no_h_pembelian = E.no_faktur_penjualan AND A.kode_kantor = E.kode_kantor
					WHERE A.kode_kantor = '".$kode_kantor."' 
					AND CONCAT(left(COALESCE(A.tgl_h_pembelian,''),10),' ',right(A.tgl_ins,8)) <  CONCAT('".$tgl_sampai."',' ','23:59:59')
					AND CONCAT(left(COALESCE(A.tgl_h_pembelian,''),10),' ',right(A.tgl_ins,8)) >  '".$tgl_backup."'
					AND A.sts_pembelian = 'SELESAI'
				) AS AA
				Union All
				SELECT back_nominal AS HUTANG FROM tb_backup_all WHERE back_id_kat = 'NERACAHUTANG' AND back_time = '".$tgl_backup."' AND kode_kantor = '".$kode_kantor."'
				) AS AA


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