<?php
	class M_gl_acc_buku_besar extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_acc_buku_besar($kode_kantor,$dari,$sampai)
		{
			$query = 
				"
				SELECT
					AA.kode_kantor
					,AA.kode_akun
					,AA.nama_kode_akun
					,AA.id_bank
					,COALESCE(BB.nama_bank,'') AS nama_bank
					,COALESCE(BB.norek,'') AS norek
					,AA.tgl_uang_masuk
					,AA.tgl_ins
					,AA.no_ref
					,AA.nama_ref
					,AA.nominal
				FROM
				(
					SELECT
						A.kode_kantor
						,COALESCE(B.kode_akun,'') AS kode_akun
						,COALESCE(B.nama_kode_akun,'') AS nama_kode_akun
						,A.id_bank
						,A.tgl_uang_masuk
						,A.tgl_ins
						,A.no_bukti AS no_ref
						,A.nama_uang_masuk AS nama_ref
						,A.nominal
					FROM tb_uang_masuk AS A
					LEFT JOIN tb_kode_akun AS B ON A.kode_kantor = B.kode_kantor AND A.id_kat_uang_masuk = B.id_kode_akun
					WHERE A.kode_kantor = '".$kode_kantor."' AND A.tgl_uang_masuk BETWEEN '".$dari."' AND '".$sampai."'
					-- ORDER BY A.tgl_ins DESC;
					UNION ALL
					SELECT
						A.kode_kantor
						,COALESCE(B.kode_akun,'') AS kode_akun
						,COALESCE(B.nama_kode_akun,'') AS nama_kode_akun
						,A.id_bank
						,A.tgl_diterima
						,A.tgl_ins
						,A.no_uang_keluar AS no_ref
						,A.nama_uang_keluar AS nama_ref
						,A.nominal
					FROM tb_uang_keluar AS A
					LEFT JOIN tb_kode_akun AS B ON A.kode_kantor = B.kode_kantor AND A.id_kat_uang_keluar = B.id_kode_akun
					WHERE A.kode_kantor = '".$kode_kantor."' AND A.tgl_diterima BETWEEN '".$dari."' AND '".$sampai."'
					-- ORDER BY A.tgl_ins DESC;
					UNION ALL
					
					
					SELECT
						A.kode_kantor
						,COALESCE(C.kode_akun,'') AS kode_akun
						,COALESCE(C.nama_kode_akun,'') AS nama_kode_akun
						,A.id_bank
						,COALESCE(DATE(B.tgl_h_penjualan),'1900-01-01') AS nama_kode_akun
						,A.tgl_ins
						,COALESCE(B.no_faktur,'') AS no_ref
						,CONCAT('Penjualan dari ', COALESCE(B.nama_costumer,'')) AS nama_ref
						,A.nominal
					FROM tb_d_penjualan_bayar AS A
					INNER JOIN tb_h_penjualan AS B ON A.kode_kantor = B.kode_kantor AND A.id_h_penjualan = B.id_h_penjualan
					LEFT JOIN tb_kode_akun AS C ON A.kode_kantor = C.kode_kantor AND C.target = 'PENJUALAN'
					WHERE B.sts_penjualan IN ('SELESAI','PEMBAYARAN')
					AND A.kode_kantor = '".$kode_kantor."' AND COALESCE(DATE(B.tgl_h_penjualan),'1900-01-01') BETWEEN '".$dari."' AND '".$sampai."'
					
					
					
					UNION ALL
					SELECT
						A.kode_kantor
						,COALESCE(C.kode_akun,'') AS kode_akun
						,COALESCE(C.nama_kode_akun,'') AS nama_kode_akun
						,A.id_bank
						,COALESCE(DATE(B.tgl_h_pembelian),'1900-01-01') AS nama_kode_akun
						,A.tgl_ins
						,COALESCE(B.no_h_pembelian,'') AS no_ref
						,CONCAT('Pembayaran PO Ke ', COALESCE(D.nama_supplier,'')) AS nama_ref
						,A.nominal
					FROM tb_d_pembelian_bayar AS A
					INNER JOIN tb_h_pembelian AS B ON A.kode_kantor = B.kode_kantor AND A.id_h_pembelian = B.id_h_pembelian
					LEFT JOIN tb_kode_akun AS C ON A.kode_kantor = C.kode_kantor AND C.target = 'PEMBELIAN'
					LEFT JOIN tb_supplier AS D ON A.kode_kantor = D.kode_kantor AND B.id_supplier = D.id_supplier
					WHERE B.sts_pembelian = 'SELESAI'
					AND A.kode_kantor = '".$kode_kantor."' AND COALESCE(DATE(B.tgl_h_pembelian),'1900-01-01') BETWEEN '".$dari."' AND '".$sampai."'
				) AS AA
				LEFT JOIN tb_bank AS BB ON AA.kode_kantor = BB.kode_kantor AND AA.id_bank = BB.id_bank
				WHERE AA.kode_akun <> ''
				ORDER BY kode_akun ASC,tgl_ins ASC
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