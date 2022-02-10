<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_gl_hitung_gaji extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function cek_masa_kerja($id_karyawan,$kode_kantor)
    {
      $data = $this->db->query("
        SELECT DATEDIFF(NOW(),tgl_diterima) AS masa_kerja
        FROM tb_karyawan
        WHERE id_karyawan = '".$id_karyawan."' AND kode_kantor = '".$kode_kantor."'
      ")->row();
    }

    function list_tunjangan_karyawan($id_karyawan,$kode_kantor,$periode)
    {
      $data = $this->db->query("
          SELECT id_tunjangan_karyawan,B.kode_tunjangan,C.id_tampung,B.nama_tunjangan,
            CASE WHEN B.kode_tunjangan NOT IN('KONSUL1','KONSUL2','LEMBUR','TINDAKAN','UPSELLING','UM','LIBUR') THEN
              COALESCE(C.nominal,A.nominal) ELSE COALESCE(C.nominal,0) END AS nominal,is_aktif
          FROM tb_tunjangan_karyawan A
          LEFT JOIN tb_tunjangan B
            ON A.id_tunjangan = B.id_tunjangan
          LEFT JOIN tb_d_tampung_gaji C
            ON B.kode_tunjangan = C.kode_akun AND A.id_karyawan = C.id_karyawan 
               AND C.periode = '".$periode."'
          WHERE A.id_karyawan = '".$id_karyawan."' 
      ")->result();

      return $data;
    }

    function list_potongan_karyawan($id_karyawan,$kode_kantor,$periode)
    {
      $data = $this->db->query("
          SELECT A.id_potongan_karyawan,C.id_tampung,nama_potongan,
            CASE WHEN B.kode_potongan NOT IN('LATE','ABSEN') THEN
              COALESCE(C.nominal,A.nominal) ELSE COALESCE(C.nominal,0) END AS nominal,is_aktif
          FROM tb_potongan_karyawan A
          LEFT JOIN tb_potongan B
            ON A.id_potongan = B.id_potongan 
          LEFT JOIN tb_d_tampung_gaji C
            ON B.kode_potongan = C.kode_akun AND A.id_karyawan = C.id_karyawan -- AND A.kode_kantor = C.kode_kantor
               AND C.periode = '".$periode."'
          WHERE A.id_karyawan = '".$id_karyawan."' -- AND A.kode_kantor = '".$kode_kantor."'
      ")->result();

      return $data;
    }

    function get_karyawan($id_karyawan,$kode_kantor)
    {
      $data = $this->db->query("
          SELECT A.id_karyawan,no_karyawan,nama_karyawan,nama_kantor,A.kode_kantor,D.nama_jabatan
          FROM tb_karyawan A
          LEFT JOIN tb_karyawan_jabatan B
            ON A.id_karyawan = B.id_karyawan AND A.kode_kantor = B.kode_kantor
          left join tb_jabatan D
            ON B.id_jabatan = D.id_jabatan AND B.kode_kantor = D.kode_kantor
          LEFT JOIN tb_kantor C
            ON A.kode_kantor = C.kode_kantor
          WHERE A.id_karyawan = '".$id_karyawan."' and A.kode_kantor = '".$kode_kantor."'
      ")->row();

      return $data;
    }

    function list_karyawan($cari)
    {
      $data = $this->db->query("
          SELECT A.*,D.nama_jabatan,DATEDIFF(NOW(),tgl_diterima) / 365 AS masa_kerja
          FROM tb_karyawan A
          LEFT JOIN tb_gaji_pokok B
            ON A.id_karyawan = B.id_karyawan -- AND A.kode_kantor = B.kode_kantor
          LEFT JOIN tb_karyawan_jabatan C
            ON A.id_karyawan = C.id_karyawan AND A.kode_kantor = C.kode_kantor
          LEFT JOIN tb_jabatan D
            ON C.id_jabatan = D.id_jabatan AND C.kode_kantor = D.kode_kantor
          WHERE B.id_karyawan IS NOT NULL AND isDefault = '1' AND A.isAktif = 'DITERIMA'
          AND nama_karyawan LIKE '%".$cari."%'
      ")->result();

      return $data;
    }

    function list_dokter()
    {
      $data = $this->db->query("
          SELECT A.*
          FROM tb_karyawan A
          LEFT JOIN tb_karyawan_jabatan C
            ON A.id_karyawan = C.id_karyawan AND A.kode_kantor = C.kode_kantor
          WHERE isDefault = '1' AND isDokter = 'DOKTER' 
      ")->result();

      return $data;
    }

    function hitung_upselling($tgl_from,$tgl_to,$periode)
    {
      $data = $this->db->query("
        INSERT INTO tb_d_tampung_gaji (
            id_karyawan,
            periode,
            kode_akun,
            nama_akun,
            jenis_akun,
            keterangan,
            nominal,
            user_ins,
            kode_kantor
          )
          SELECT A.id_karyawan,
                 '".$periode."' AS periode,
                 'UPSELLING' AS kode_akun,
                 'Fee Upselling' AS nama_akun,
                 'DEBIT' AS jenis_akun,
                 '' keterangan,
                 SUM(fee_upselling) fee_upselling,
                 '".$this->session->userdata('ses_id_karyawan')."' user_ins, 
                 A.kode_kantor
          FROM 
          (
            SELECT A.id_karyawan,A.kode_kantor,CASE WHEN B.isProduk = 'JASA' THEN
                   CASE WHEN tgl_h_penjualan IS NOT NULL THEN E.harga * 0.01
                 ELSE C.harga * 0.01 
                 END
              ELSE 5000 END AS fee_upselling
            FROM tb_upselling A
            LEFT JOIN tb_produk B
              ON A.id_produk = B.id_produk AND A.kode_kantor = B.kode_kantor
            LEFT JOIN tb_produk_harga_satuan_costumer C
              ON A.id_produk = C.id_produk AND A.kode_kantor = C.kode_kantor AND C.media = 'KLINIK' AND C.id_costumer = 'ONJKT2020060200002'
            LEFT JOIN tb_satuan_konversi D
              ON A.id_produk = D.id_produk AND A.kode_kantor = D.kode_kantor AND D.besar_konversi = '1'
            LEFT JOIN tb_satuan F 
              ON D.id_satuan = F.id_satuan AND D.kode_kantor = F.kode_kantor AND kode_satuan IN('PCS','SVC') 
            LEFT JOIN
            (
               SELECT tgl_h_penjualan,id_produk,(A.harga - (A.harga * A.diskon / 100)) * A.jumlah AS harga,A.kode_kantor,
              nama_diskon
               FROM tb_d_penjualan A
               LEFT JOIN tb_h_penjualan B
                 ON A.id_h_penjualan = B.id_h_penjualan AND A.kode_kantor = B.kode_kantor
               LEFT JOIN tb_h_diskon C
                 ON A.id_h_diskon = C.id_h_diskon AND A.kode_kantor = C.kode_kantor
               WHERE B.tgl_h_penjualan BETWEEN '".$tgl_from."' AND '".$tgl_to."'
               AND A.id_h_diskon <> '' AND isProduk = 'JASA' 
            ) E ON A.tanggal = E.tgl_h_penjualan AND A.id_produk = E.id_produk AND A.kode_kantor = E.kode_kantor
            WHERE tanggal BETWEEN '".$tgl_from."' AND '".$tgl_to."' AND B.isProduk IN('JASA','PRODUK') AND COALESCE(foto_bukti,'') <> ''
            AND F.id_satuan IS NOT NULL
          ) A GROUP BY A.id_karyawan,A.kode_kantor

      ");
    }

    function hitung_uang_makan($tgl_from,$tgl_to,$periode)
    {
        $this->db->query("
          INSERT INTO tb_d_tampung_gaji (
              id_karyawan,
              periode,
              kode_akun,
              nama_akun,
              jenis_akun,
              keterangan,
              nominal,
              user_ins,
              kode_kantor
            )
          SELECT A.id_karyawan,'".$periode."' AS periode,'UM' AS kode_akun,'Uang Makan' AS nama_akun,'DEBIT' AS jenis_akun,
                 '' AS ket,SUM(nominal) AS nominal ,'".$this->session->userdata('ses_id_karyawan')."' AS user_ins,'' AS kode_kantor
          FROM 
          (  
            SELECT A.id_karyawan,A.tanggal,COALESCE(B.nominal,0) AS nominal
            FROM
            (
              SELECT id_karyawan,tanggal
              FROM tb_jam_kerja_karyawan A
              WHERE nama_jam NOT IN('LIBUR','DINAS LUAR','CUTI','Izin','Sakit SKD','Sakit Tanpa SKD')
              AND tanggal BETWEEN '".$tgl_from."' AND '".$tgl_to."'
            ) A LEFT JOIN(
              SELECT A.id_karyawan,A.nominal
              FROM tb_tunjangan_karyawan A
              LEFT JOIN tb_tunjangan B
                 ON A.id_tunjangan = B.id_tunjangan
              WHERE kode_tunjangan = 'UM'
            ) B ON A.id_karyawan = B.id_karyawan
          ) A
          GROUP BY A.id_karyawan
        ");
    }

    function hitung_potongan_absen($id_karyawan,$kode_kantor,$tgl_from,$tgl_to,$periode)
    {
      $data = $this->db->query("
          INSERT INTO tb_d_tampung_gaji (
            id_karyawan,
            periode,
            kode_akun,
            nama_akun,
            jenis_akun,
            keterangan,
            nominal,
            user_ins,
            kode_kantor
          )
          SELECT id_karyawan,'".$periode."' AS periode,'ABSEN' AS kode,'Absensi' nama_akun,'KREDIT' AS jenis,CONCAT(total_hari - total_kerja,' hari') AS keterangan,besar_gaji - ((total_kerja/total_hari) * besar_gaji) AS potongan_absen,
                 '".$this->session->userdata('ses_id_karyawan')."' AS user_ins,kode_kantor
          FROM (
            SELECT A.id_karyawan,SUM(COALESCE(B.ctx_kerja,0)) total_kerja,SUM(COALESCE(A.ctx_hari,0)) AS total_hari,besar_gaji,A.kode_kantor
            FROM
            (
              SELECT id_karyawan,tanggal,kode_kantor,'1' AS ctx_hari
              FROM tb_jam_kerja_karyawan
              WHERE id_karyawan = '".$id_karyawan."' AND kode_kantor = '".$kode_kantor."'
              AND nama_jam NOT IN('LIBUR','DINAS LUAR','CUTI','Izin','Sakit SKD','Sakit Tanpa SKD') AND
              tanggal BETWEEN '".$tgl_from."' AND '".$tgl_to."'
            ) A LEFT JOIN
            (
              SELECT DISTINCT id_karyawan,kode_kantor,tanggal,'1' AS ctx_kerja
              FROM (
                SELECT C.id_karyawan,DATE_FORMAT(tanggal,'%Y-%m-%d') tanggal,C.kode_kantor
                FROM tb_log_absen A
                LEFT JOIN tb_att_employ B
                  ON A.id_cabang = B.id_cabang AND A.id_karyawan = B.id_empl
                LEFT JOIN tb_karyawan C
                  ON B.id_empl = C.id_mesin_empl AND B.kode_kantor = C.kode_kantor
                WHERE C.id_karyawan = '".$id_karyawan."' AND C.kode_kantor = '".$kode_kantor."'
                AND DATE_FORMAT(tanggal,'%Y-%m-%d') BETWEEN '".$tgl_from."' AND '".$tgl_to."'
              ) AA
            ) B ON A.id_karyawan = B.id_karyawan AND A.kode_kantor = B.kode_kantor AND A.tanggal = B.tanggal
            LEFT JOIN tb_gaji_pokok C 
              ON A.id_karyawan = C.id_karyawan AND A.kode_kantor = B.kode_kantor
            WHERE DATE_FORMAT(A.tanggal,'%Y-%m-%d') BETWEEN '".$tgl_from."' AND '".$tgl_to."'
            GROUP BY A.id_karyawan
          ) A
        ");
    }

    function hitung_konsul_pasien_baru($id_karyawan,$tgl_from,$tgl_to,$periode)
    {
      $this->db->query("
          INSERT INTO tb_d_tampung_gaji (
            id_karyawan,
            periode,
            kode_akun,
            nama_akun,
            jenis_akun,
            keterangan,
            nominal,
            user_ins,
            kode_kantor
          )
          SELECT id_dokter,'".$periode."' periode,'KONSUL1' kode_akun,'Pasien Baru' nama_akun,'DEBIT' jenis_akun,'' keterangan,SUM(INS) nominal,'".$this->session->userdata('ses_id_karyawan')."' user_ins,'' kode_kantor
          FROM (
              SELECT id_dokter,nama_karyawan,tgl_diterima,id_h_penjualan,id_costumer,tgl_h_penjualan,nama_jasa,nama_produk,total_produk,
            CASE WHEN LOCATE('DETOX',nama_jasa) > 0 AND (LOCATE('PEEL',nama_jasa) > 0 OR LOCATE('MICRODERMABRASI',nama_jasa) > 0) AND total_produk >= 10 AND masa_kerja >= 365 THEN 10000 
                 WHEN LOCATE('DETOX',nama_jasa) > 0 AND (LOCATE('PEEL',nama_jasa) > 0 OR LOCATE('MICRODERMABRASI',nama_jasa) > 0) AND total_produk >= 10 AND masa_kerja < 365 THEN 5000
            ELSE
                 CASE WHEN LOCATE('DETOX',nama_jasa) > 0 AND (LOCATE('PEEL',nama_jasa) > 0 OR LOCATE('MICRODERMABRASI',nama_jasa) > 0) AND total_produk >= 5 AND total_produk < 10 AND masa_kerja >= 365 THEN 5000 
                WHEN LOCATE('DETOX',nama_jasa) > 0 AND (LOCATE('PEEL',nama_jasa) > 0 OR LOCATE('MICRODERMABRASI',nama_jasa) > 0) AND total_produk >= 5 AND total_produk < 10 AND masa_kerja < 365 THEN 2500 
                 ELSE 0 END
            END AS INS
            FROM 
            (
                SELECT  id_dokter,nama_karyawan,tgl_diterima,id_h_penjualan,id_costumer,tgl_h_penjualan,
                        GROUP_CONCAT(nama_jasa SEPARATOR ', ') nama_jasa,
                        GROUP_CONCAT(nama_produk SEPARATOR ', ') nama_produk,
                        SUM(total_jasa) total_jasa,SUM(total_produk) total_produk,masa_kerja
                FROM
                (
            SELECT  id_dokter,nama_karyawan,tgl_diterima,id_h_penjualan,id_costumer,tgl_h_penjualan,
              CASE WHEN isProduk = 'JASA' THEN nama_produk ELSE '' END AS nama_jasa,
              CASE WHEN isProduk = 'PRODUK' THEN nama_produk ELSE '' END AS nama_produk,
              CASE WHEN isProduk = 'JASA' THEN jumlah ELSE '' END AS total_jasa,
              CASE WHEN isProduk = 'PRODUK' THEN jumlah ELSE '' END AS total_produk,masa_kerja
            FROM
            (
              SELECT A.id_dokter,D.nama_karyawan,tgl_diterima,A.id_h_penjualan,A.id_costumer,tgl_h_penjualan,GROUP_CONCAT(E.nama_produk SEPARATOR ', ') nama_produk,
               SUM(B.jumlah) AS jumlah,A.kode_kantor,E.isProduk,DATEDIFF(tgl_h_penjualan,tgl_diterima) AS masa_kerja
              FROM tb_h_penjualan A
              LEFT JOIN tb_d_penjualan B
                ON A.id_h_penjualan = B.id_h_penjualan AND A.kode_kantor = B.kode_kantor
              LEFT JOIN tb_costumer C 
                ON A.id_costumer = C.id_costumer AND A.kode_kantor = C.kode_kantor
              LEFT JOIN tb_karyawan D
                ON A.id_dokter = D.id_karyawan AND A.kode_kantor = D.kode_kantor
              LEFT JOIN tb_produk E
                ON B.id_produk = E.id_produk AND B.kode_kantor = E.kode_kantor
              WHERE A.tgl_h_penjualan BETWEEN '".$tgl_from."' AND '".$tgl_to."'
              AND id_dokter <> '' AND type_h_penjualan = 'KONSULTASI KECANTIKAN'
              AND tgl_h_penjualan = tgl_registrasi AND sts_penjualan IN ('SELESAI','PEMBAYARAN') 
              AND A.id_dokter = '".$id_karyawan."' AND E.isProduk IN('JASA','PRODUK') 
              AND E.nama_produk NOT IN('PAPER BAG BESAR','PAPER BAG SEDANG','PAPER BAG KECIL','SPON','MATERAI')
              GROUP BY A.id_dokter,D.nama_karyawan,tgl_diterima,A.id_h_penjualan,A.id_costumer,tgl_h_penjualan,E.isProduk
            ) A 
              ) A GROUP BY id_dokter,nama_karyawan,tgl_diterima,id_h_penjualan,id_costumer,tgl_h_penjualan,masa_kerja
            ) A
          ) A

      ");
    }

    function hitung_konsul_pasien_lama($id_karyawan,$tgl_from,$tgl_to,$periode)
    {
      $this->db->query("
          INSERT INTO tb_d_tampung_gaji (
            id_karyawan,
            periode,
            kode_akun,
            nama_akun,
            jenis_akun,
            keterangan,
            nominal,
            user_ins,
            kode_kantor
          )
          SELECT id_dokter,'".$periode."' periode,'KONSUL2' kode_akun,'Pasien Lama' nama_akun,'DEBIT' jenis_akun,'' keterangan,SUM(INS) nominal,'".$this->session->userdata('ses_id_karyawan')."' user_ins,'' kode_kantor
          FROM (
              SELECT id_dokter,nama_karyawan,tgl_diterima,id_h_penjualan,id_costumer,tgl_h_penjualan,nama_jasa,nama_produk,total_produk,
            CASE WHEN (LOCATE('DOKTER',jenis_tindakan) > 0 OR LOCATE('GABUNGAN',jenis_tindakan) > 0) AND total_produk >= 5 AND masa_kerja >= 365 THEN 5000
                 WHEN (LOCATE('DOKTER',jenis_tindakan) > 0 OR LOCATE('GABUNGAN',jenis_tindakan) > 0) AND total_produk >= 5 AND masa_kerja < 365 THEN 2000
                 WHEN (LOCATE('TERAPIS',jenis_tindakan) > 0 OR LOCATE('GABUNGAN',jenis_tindakan) > 0) AND total_produk >= 10 AND masa_kerja >= 365 THEN 5000
                 WHEN (LOCATE('TERAPIS',jenis_tindakan) > 0 OR LOCATE('GABUNGAN',jenis_tindakan) > 0) AND total_produk >= 10 AND masa_kerja < 365 THEN 2000
            ELSE 
                 CASE WHEN (LOCATE('DOKTER',jenis_tindakan) > 0 OR LOCATE('GABUNGAN',jenis_tindakan) > 0) AND total_produk < 5 AND masa_kerja >= 365 THEN 2500
                WHEN (LOCATE('DOKTER',jenis_tindakan) > 0 OR LOCATE('GABUNGAN',jenis_tindakan) > 0) AND total_produk < 5 AND masa_kerja < 365 THEN 1000
                WHEN (LOCATE('TERAPIS',jenis_tindakan) > 0 OR LOCATE('GABUNGAN',jenis_tindakan) > 0) AND total_produk < 10 AND masa_kerja >= 365 THEN 2500
                WHEN (LOCATE('TERAPIS',jenis_tindakan) > 0 OR LOCATE('GABUNGAN',jenis_tindakan) > 0) AND total_produk < 10 AND masa_kerja < 365 THEN 1000
                 ELSE 0 END
            END AS INS,jenis_tindakan
               FROM 
              (
                SELECT  id_dokter,nama_karyawan,tgl_diterima,id_h_penjualan,id_costumer,tgl_h_penjualan,
            GROUP_CONCAT(nama_jasa SEPARATOR ', ') nama_jasa,
            GROUP_CONCAT(nama_produk SEPARATOR ', ') nama_produk,
            GROUP_CONCAT(jenis_tindakan SEPARATOR ', ') jenis_tindakan,
            SUM(total_jasa) total_jasa,SUM(total_produk) total_produk,masa_kerja
                FROM
                (
            SELECT  id_dokter,nama_karyawan,tgl_diterima,id_h_penjualan,id_costumer,tgl_h_penjualan,
              CASE WHEN isProduk = 'JASA' THEN nama_produk ELSE '' END AS nama_jasa,
              CASE WHEN isProduk = 'PRODUK' THEN nama_produk ELSE '' END AS nama_produk,
              CASE WHEN isProduk = 'JASA' THEN jumlah ELSE '' END AS total_jasa,
              CASE WHEN isProduk = 'PRODUK' THEN jumlah ELSE '' END AS total_produk,masa_kerja,jenis_tindakan
            FROM
            (
              SELECT A.id_dokter,D.nama_karyawan,tgl_diterima,A.id_h_penjualan,A.id_costumer,tgl_h_penjualan,GROUP_CONCAT(E.nama_produk SEPARATOR ', ') nama_produk,
               SUM(B.jumlah) AS jumlah,A.kode_kantor,E.isProduk,DATEDIFF(tgl_h_penjualan,tgl_diterima) AS masa_kerja,COALESCE(jenis_tindakan,'') jenis_tindakan
              FROM tb_h_penjualan A
              LEFT JOIN tb_d_penjualan B
                ON A.id_h_penjualan = B.id_h_penjualan AND A.kode_kantor = B.kode_kantor
              LEFT JOIN tb_costumer C 
                ON A.id_costumer = C.id_costumer AND A.kode_kantor = C.kode_kantor
              LEFT JOIN tb_karyawan D
                ON A.id_dokter = D.id_karyawan AND A.kode_kantor = D.kode_kantor
              LEFT JOIN tb_produk E
                ON B.id_produk = E.id_produk AND B.kode_kantor = E.kode_kantor
              WHERE A.tgl_h_penjualan BETWEEN '".$tgl_from."' AND '".$tgl_to."'
              AND id_dokter <> '' AND type_h_penjualan = 'KONSULTASI KECANTIKAN'
              AND tgl_h_penjualan <> tgl_registrasi AND sts_penjualan IN ('SELESAI','PEMBAYARAN') 
              AND A.id_dokter = '".$id_karyawan."' AND E.isProduk IN('JASA','PRODUK')
              AND E.nama_produk NOT IN('PAPER BAG BESAR','PAPER BAG SEDANG','PAPER BAG KECIL','SPON','MATERAI','ADM KONSUL','BIAYA PENDAFTARAN')
              GROUP BY A.id_dokter,D.nama_karyawan,tgl_diterima,A.id_h_penjualan,A.id_costumer,tgl_h_penjualan,E.isProduk,COALESCE(jenis_tindakan,'')
            ) A 
              ) A GROUP BY id_dokter,nama_karyawan,tgl_diterima,id_h_penjualan,id_costumer,tgl_h_penjualan,masa_kerja
            ) A WHERE nama_jasa <> ''
          ) A
      ");
    }

    function total_tunjangan_karyawan($id_karyawan,$kode_kantor,$periode)
    {
      $data = $this->db->query("
        SELECT SUM(nominal) AS total 
        FROM tb_d_tampung_gaji
        WHERE id_karyawan = '".$id_karyawan."'
        AND periode = '".$periode."' AND jenis_akun = 'DEBIT'
      ")->row();

      if(!empty($data))
      {
        return $data->total;
      } else {
        return 0;
      }
    }

    function total_potongan_karyawan($id_karyawan,$kode_kantor,$periode)
    {
      $data = $this->db->query("
        SELECT SUM(nominal) AS total 
        FROM tb_d_tampung_gaji
        WHERE id_karyawan = '".$id_karyawan."'
        AND periode = '".$periode."' AND jenis_akun = 'KREDIT'
      ")->row();

      if(!empty($data))
      {
        return $data->total;
      } else {
        return 0;
      }
    }

    function insert_d_tampung_gaji($data)
    {
      $this->db->insert('tb_d_tampung_gaji',$data);
    }

    function delete_d_tampung($periode,$kode_akun)
    {
      $this->db->query("
          DELETE FROM tb_d_tampung_gaji
          WHERE periode = '".$periode."' AND kode_akun = '".$kode_akun."'
      ");
    }

    function reset_d_tampung($periode)
    {
      $this->db->query("
          DELETE FROM tb_d_tampung_gaji
          WHERE periode = '".$periode."' AND kode_akun NOT IN('LATE','ABSEN','KONSUL1','KONSUL2','LEMBUR','TINDAKAN','UPSELLING','UM','LIBUR')
      ");
    }

    function delete_d_tampung_by_id($id)
    {
      $this->db->query("
          DELETE FROM tb_d_tampung_gaji
          WHERE id_tampung = '".$id."'
      ");
    }

    function tarik_late_cabang($tgl_from,$tgl_to,$cabang,$id_karyawan)
    {
       $result = $this->db->query("
         CALL SP_REKAP_ABSEN('".$tgl_from."','".$tgl_to."',".$cabang.",'".$id_karyawan."');
       ");

      $res = $result->result();
      //mysqli_next_result( $this->db->conn_id );
      return $res;      
    }

    function tarik_lembur($tgl_from,$tgl_to,$cabang,$id_karyawan)
    {
       $result = $this->db->query("
         CALL SP_REKAP_ABSEN('".$tgl_from."','".$tgl_to."',".$cabang.",'".$id_karyawan."');
       ");

      $res = $result->result();
      //mysqli_next_result( $this->db->conn_id );
      return $res;      
    }

    function list_jam_kerja($id_karyawan,$tgl_from,$tgl_to,$kode_kantor)
    {
      $data = $this->db->query("
          SELECT A.id_karyawan,A.tanggal,jam_masuk,jam_keluar,A.kode_kantor,B.id_mesin_empl
          FROM tb_jam_kerja_karyawan A
          LEFT JOIN tb_karyawan B
            ON A.id_karyawan = B.id_karyawan AND A.kode_kantor = B.kode_kantor
          WHERE A.id_karyawan = '".$id_karyawan."' AND A.tanggal BETWEEN '".$tgl_from."' AND '".$tgl_to."'
          AND A.kode_kantor = '".$kode_kantor."' AND nama_jam NOT IN('LIBUR','DINAS LUAR','CUTI','Izin','Sakit SKD','Sakit Tanpa SKD')
      ")->result();

      return $data;
    }

    function detail_penggajian($id_karyawan,$tgl_from,$tgl_to)
    {
      $data = $this->db->query("
        SELECT id_dokter,nama_karyawan,no_faktur,nama_costumer,tgl_h_penjualan,nama_produk,nama_terapis,
               kode_kantor,isProduk,masa_kerja,id_h_diskon,COALESCE(nama_diskon,'') nama_diskon,jumlah_konversi,harga_konversi,optr_diskon,diskon,
               subtotal,
               CASE WHEN masa_kerja < 2 THEN 
              CASE WHEN LOCATE('INFUS',nama_produk) > 0 THEN 5 ELSE 3 END
               ELSE 
              CASE WHEN LOCATE('INFUS',nama_produk) > 0 OR LOCATE('THREADLIFT',nama_produk) > 0 OR LOCATE('BOTOX',nama_produk) > 0 OR LOCATE('FILLER',nama_produk) > 0 
             THEN 6 ELSE 5 END
          END AS fee_tindakan,
               CASE WHEN masa_kerja < 2 THEN 
              CASE WHEN LOCATE('INFUS',nama_produk) > 0 THEN (5 * subtotal) / 100 ELSE (3 * subtotal) / 100 END
               ELSE 
              CASE WHEN LOCATE('INFUS',nama_produk) > 0 OR LOCATE('THREADLIFT',nama_produk) > 0 OR LOCATE('BOTOX',nama_produk) > 0 OR LOCATE('FILLER',nama_produk) > 0 
            THEN (6 * subtotal) / 100 ELSE (5 * subtotal) / 100 END
          END AS fee_nominal
          
        FROM (
          SELECT id_dokter,nama_karyawan,no_faktur,nama_costumer,tgl_h_penjualan,nama_produk,nama_terapis,
                 kode_kantor,isProduk,masa_kerja,id_h_diskon,nama_diskon,jumlah_konversi,harga_konversi,optr_diskon,diskon,
                 jumlah_konversi * (harga_konversi - diskon_konversi) AS subtotal
          FROM (  
            SELECT A.id_dokter,D.nama_karyawan,T.nama_karyawan AS nama_terapis,A.no_faktur,nama_lengkap AS nama_costumer,tgl_h_penjualan,E.nama_produk,
                 A.kode_kantor,E.isProduk,DATEDIFF(tgl_h_penjualan,D.tgl_diterima)/365 AS masa_kerja,B.id_h_diskon,nama_diskon,
                 CASE WHEN B.status_konversi = '*' THEN
              B.jumlah * B.konversi
            ELSE
              B.jumlah / B.konversi
            END AS jumlah_konversi
            
            ,CASE WHEN B.status_konversi = '*' THEN
              (B.harga / 1.1) / B.konversi
            ELSE
              B.harga % B.konversi
            END AS harga_konversi
            
            ,CASE WHEN B.optr_diskon = '%' THEN
              ((B.harga / 1.1)/100) * B.diskon
            ELSE
              B.diskon
            END AS diskon_konversi,B.optr_diskon,B.diskon
            FROM tb_h_penjualan A
            LEFT JOIN tb_d_penjualan B
              ON A.id_h_penjualan = B.id_h_penjualan AND A.kode_kantor = B.kode_kantor
            LEFT JOIN tb_costumer C 
              ON A.id_costumer = C.id_costumer AND A.kode_kantor = C.kode_kantor
            LEFT JOIN tb_karyawan D
              ON A.id_dokter2 = D.id_karyawan AND A.kode_kantor = D.kode_kantor
            LEFT JOIN tb_karyawan T
              ON B.id_ass = T.id_karyawan AND B.kode_kantor = T.kode_kantor
            LEFT JOIN tb_produk E
              ON B.id_produk = E.id_produk AND B.kode_kantor = E.kode_kantor
            LEFT JOIN tb_h_diskon F
              ON B.id_h_diskon = F.id_h_diskon AND B.kode_kantor = F.kode_kantor
            WHERE A.tgl_h_penjualan BETWEEN '".$tgl_from."' AND '".$tgl_to."'
            AND id_dokter2 <> '' AND type_h_penjualan = 'KONSULTASI KECANTIKAN'
            AND sts_penjualan IN ('SELESAI','PEMBAYARAN') 
            AND A.id_dokter2 = '".$id_karyawan."' AND E.isProduk IN('JASA') 
            AND E.nama_produk NOT IN('ADM KONSUL','BIAYA PENDAFTARAN')
          ) A
        ) A ORDER BY tgl_h_penjualan,nama_costumer
      ")->result();

      return $data;
    }

    function detail_konsul_lama($id_karyawan,$tgl_from,$tgl_to)
    {
      $data = $this->db->query("

         SELECT id_dokter,nama_karyawan,tgl_diterima,id_h_penjualan,id_costumer,tgl_h_penjualan,nama_jasa,nama_produk,total_produk,masa_kerja / 365 AS masa_kerja,nama_costumer,
          CASE WHEN (LOCATE('DOKTER',jenis_tindakan) > 0 OR LOCATE('GABUNGAN',jenis_tindakan) > 0) AND total_produk >= 5 AND masa_kerja >= 365 THEN 5000
         WHEN (LOCATE('DOKTER',jenis_tindakan) > 0 OR LOCATE('GABUNGAN',jenis_tindakan) > 0) AND total_produk >= 5 AND masa_kerja < 365 THEN 2000
         WHEN (LOCATE('TERAPIS',jenis_tindakan) > 0 OR LOCATE('GABUNGAN',jenis_tindakan) > 0) AND total_produk >= 10 AND masa_kerja >= 365 THEN 5000
         WHEN (LOCATE('TERAPIS',jenis_tindakan) > 0 OR LOCATE('GABUNGAN',jenis_tindakan) > 0) AND total_produk >= 10 AND masa_kerja < 365 THEN 2000
          ELSE 
         CASE WHEN (LOCATE('DOKTER',jenis_tindakan) > 0 OR LOCATE('GABUNGAN',jenis_tindakan) > 0) AND total_produk < 5 AND masa_kerja >= 365 THEN 2500
        WHEN (LOCATE('DOKTER',jenis_tindakan) > 0 OR LOCATE('GABUNGAN',jenis_tindakan) > 0) AND total_produk < 5 AND masa_kerja < 365 THEN 1000
        WHEN (LOCATE('TERAPIS',jenis_tindakan) > 0 OR LOCATE('GABUNGAN',jenis_tindakan) > 0) AND total_produk < 10 AND masa_kerja >= 365 THEN 2500
        WHEN (LOCATE('TERAPIS',jenis_tindakan) > 0 OR LOCATE('GABUNGAN',jenis_tindakan) > 0) AND total_produk < 10 AND masa_kerja < 365 THEN 1000
         ELSE 0 END
          END AS INS,jenis_tindakan
             FROM 
            (
        SELECT  id_dokter,nama_karyawan,tgl_diterima,id_h_penjualan,id_costumer,tgl_h_penjualan,
          GROUP_CONCAT(nama_jasa SEPARATOR ', ') nama_jasa,
          GROUP_CONCAT(nama_produk SEPARATOR ', ') nama_produk,
          GROUP_CONCAT(jenis_tindakan SEPARATOR ', ') jenis_tindakan,
          SUM(total_jasa) total_jasa,SUM(total_produk) total_produk,masa_kerja,nama_costumer
        FROM
        (
          SELECT  id_dokter,nama_karyawan,tgl_diterima,id_h_penjualan,id_costumer,tgl_h_penjualan,
            CASE WHEN isProduk = 'JASA' THEN nama_produk ELSE '' END AS nama_jasa,
            CASE WHEN isProduk = 'PRODUK' THEN nama_produk ELSE '' END AS nama_produk,
            CASE WHEN isProduk = 'JASA' THEN jumlah ELSE '' END AS total_jasa,
            CASE WHEN isProduk = 'PRODUK' THEN jumlah ELSE '' END AS total_produk,masa_kerja,jenis_tindakan,nama_costumer
          FROM
          (
            SELECT A.id_dokter,D.nama_karyawan,tgl_diterima,A.id_h_penjualan,A.id_costumer,tgl_h_penjualan,GROUP_CONCAT(E.nama_produk SEPARATOR ', ') nama_produk,
             SUM(B.jumlah) AS jumlah,A.kode_kantor,E.isProduk,DATEDIFF(tgl_h_penjualan,tgl_diterima) AS masa_kerja,COALESCE(jenis_tindakan,'') jenis_tindakan,C.nama_lengkap AS nama_costumer
            FROM tb_h_penjualan A
            LEFT JOIN tb_d_penjualan B
        ON A.id_h_penjualan = B.id_h_penjualan AND A.kode_kantor = B.kode_kantor
            LEFT JOIN tb_costumer C 
        ON A.id_costumer = C.id_costumer AND A.kode_kantor = C.kode_kantor
            LEFT JOIN tb_karyawan D
        ON A.id_dokter = D.id_karyawan AND A.kode_kantor = D.kode_kantor
            LEFT JOIN tb_produk E
        ON B.id_produk = E.id_produk AND B.kode_kantor = E.kode_kantor
            WHERE A.tgl_h_penjualan BETWEEN '".$tgl_from."' AND '".$tgl_to."'
            AND id_dokter <> '' AND type_h_penjualan = 'KONSULTASI KECANTIKAN'
            AND tgl_h_penjualan <> tgl_registrasi AND sts_penjualan IN ('SELESAI','PEMBAYARAN') 
            AND A.id_dokter = '".$id_karyawan."' AND E.isProduk IN('JASA','PRODUK')
            AND E.nama_produk NOT IN('PAPER BAG BESAR','PAPER BAG SEDANG','PAPER BAG KECIL','SPON','MATERAI','ADM KONSUL','BIAYA PENDAFTARAN')
            GROUP BY A.id_dokter,D.nama_karyawan,tgl_diterima,A.id_h_penjualan,A.id_costumer,tgl_h_penjualan,E.isProduk,COALESCE(jenis_tindakan,''),C.nama_lengkap
          ) A 
            ) A GROUP BY id_dokter,nama_karyawan,tgl_diterima,id_h_penjualan,id_costumer,tgl_h_penjualan,masa_kerja,nama_costumer
          ) A WHERE nama_jasa <> ''
      ")->result();

      return $data;
    }

    function detail_konsul_baru($id_karyawan,$tgl_from,$tgl_to)
    {
      $data = $this->db->query("
        SELECT id_dokter,nama_karyawan,tgl_diterima,id_h_penjualan,id_costumer,tgl_h_penjualan,nama_jasa,nama_produk,total_produk,masa_kerja / 365 AS masa_kerja,nama_costumer,nama_costumer,
        CASE WHEN LOCATE('DETOX',nama_jasa) > 0 AND (LOCATE('PEEL',nama_jasa) > 0 OR LOCATE('MICRODERMABRASI',nama_jasa) > 0) AND total_produk >= 10 AND masa_kerja >= 365 THEN 10000 
       WHEN LOCATE('DETOX',nama_jasa) > 0 AND (LOCATE('PEEL',nama_jasa) > 0 OR LOCATE('MICRODERMABRASI',nama_jasa) > 0) AND total_produk >= 10 AND masa_kerja < 365 THEN 5000
        ELSE
       CASE WHEN LOCATE('DETOX',nama_jasa) > 0 AND (LOCATE('PEEL',nama_jasa) > 0 OR LOCATE('MICRODERMABRASI',nama_jasa) > 0) AND total_produk >= 5 AND total_produk < 10 AND masa_kerja >= 365 THEN 5000 
      WHEN LOCATE('DETOX',nama_jasa) > 0 AND (LOCATE('PEEL',nama_jasa) > 0 OR LOCATE('MICRODERMABRASI',nama_jasa) > 0) AND total_produk >= 5 AND total_produk < 10 AND masa_kerja < 365 THEN 2500 
       ELSE 0 END
        END AS INS
        FROM 
        (
      SELECT  id_dokter,nama_karyawan,tgl_diterima,id_h_penjualan,id_costumer,tgl_h_penjualan,
        GROUP_CONCAT(nama_jasa SEPARATOR ', ') nama_jasa,
        GROUP_CONCAT(nama_produk SEPARATOR ', ') nama_produk,
        SUM(total_jasa) total_jasa,SUM(total_produk) total_produk,masa_kerja,nama_costumer
      FROM
      (
        SELECT  id_dokter,nama_karyawan,tgl_diterima,id_h_penjualan,id_costumer,tgl_h_penjualan,
          CASE WHEN isProduk = 'JASA' THEN nama_produk ELSE '' END AS nama_jasa,
          CASE WHEN isProduk = 'PRODUK' THEN nama_produk ELSE '' END AS nama_produk,
          CASE WHEN isProduk = 'JASA' THEN jumlah ELSE '' END AS total_jasa,
          CASE WHEN isProduk = 'PRODUK' THEN jumlah ELSE '' END AS total_produk,masa_kerja,nama_costumer
        FROM
        (
          SELECT A.id_dokter,D.nama_karyawan,tgl_diterima,A.id_h_penjualan,A.id_costumer,tgl_h_penjualan,GROUP_CONCAT(E.nama_produk SEPARATOR ', ') nama_produk,
           SUM(B.jumlah) AS jumlah,A.kode_kantor,E.isProduk,DATEDIFF(tgl_h_penjualan,tgl_diterima) AS masa_kerja,C.nama_lengkap AS nama_costumer
          FROM tb_h_penjualan A
          LEFT JOIN tb_d_penjualan B
      ON A.id_h_penjualan = B.id_h_penjualan AND A.kode_kantor = B.kode_kantor
          LEFT JOIN tb_costumer C 
      ON A.id_costumer = C.id_costumer AND A.kode_kantor = C.kode_kantor
          LEFT JOIN tb_karyawan D
      ON A.id_dokter = D.id_karyawan AND A.kode_kantor = D.kode_kantor
          LEFT JOIN tb_produk E
      ON B.id_produk = E.id_produk AND B.kode_kantor = E.kode_kantor
      WHERE A.tgl_h_penjualan BETWEEN '".$tgl_from."' AND '".$tgl_to."'
          AND id_dokter <> '' AND type_h_penjualan = 'KONSULTASI KECANTIKAN'
          AND tgl_h_penjualan = tgl_registrasi AND sts_penjualan IN ('SELESAI','PEMBAYARAN')
          AND A.id_dokter = '".$id_karyawan."' AND E.isProduk IN('JASA','PRODUK')
          AND E.nama_produk NOT IN('PAPER BAG BESAR','PAPER BAG SEDANG','PAPER BAG KECIL','SPON','MATERAI')
          GROUP BY A.id_dokter,D.nama_karyawan,tgl_diterima,A.id_h_penjualan,A.id_costumer,tgl_h_penjualan,E.isProduk,C.nama_lengkap
        ) A 
          ) A GROUP BY id_dokter,nama_karyawan,tgl_diterima,id_h_penjualan,id_costumer,tgl_h_penjualan,masa_kerja,nama_costumer
        ) A
      ")->result();

      return $data;
    }

    function list_kantor()
    {
      $data = $this->db->query("
          SELECT * FROM tb_kantor
        ")->result();
      return $data;
    }

    function cek_d_tampung($id_karyawan,$periode,$kode_akun)
    {
        $data = $this->db->query("
            SELECT * FROM tb_d_tampung_gaji
            WHERE id_karyawan = '".$id_karyawan."' AND periode = '".$periode."' AND kode_akun = '".$kode_akun."'
         ")->result();

        return $data;
    }

    function update_d_tampung($id_karyawan,$periode,$kode_akun,$nominal)
    {
        $this->db->query("
            UPDATE tb_d_tampung_gaji
            SET nominal = nominal + ".$nominal."
            WHERE id_karyawan = '".$id_karyawan."' AND periode = '".$periode."' AND kode_akun = '".$kode_akun."'
         ");
    }

    function update_d_tampung2($id_karyawan,$periode,$kode_akun,$nominal)
    {
        $this->db->query("
            UPDATE tb_d_tampung_gaji
            SET nominal = ".$nominal."
            WHERE id_karyawan = '".$id_karyawan."' AND periode = '".$periode."' AND kode_akun = '".$kode_akun."'
         ");
    }

    function update_d_tampung_by_id($id_tampung,$nominal)
    {
        $this->db->query("
            UPDATE tb_d_tampung_gaji
            SET nominal = ".$nominal."
            WHERE id_tampung = '".$id_tampung."'
         ");
    }

    function insert_proses_tunjangan($periode)
    {
      $this->db->query("
          INSERT INTO tb_d_tampung_gaji (
            id_karyawan,
            periode,
            kode_akun,
            nama_akun,
            jenis_akun,
            keterangan,
            nominal,
            user_ins,
            kode_kantor
          )
          SELECT A.id_karyawan,'".$periode."' AS periode,COALESCE(kode_tunjangan,'') AS kode_akun,nama_tunjangan,
                 'DEBIT' AS jenis_akun,'' Keterangan,A.nominal,'".$this->session->userdata('ses_id_karyawan')."' user_ins,A.kode_kantor
          FROM tb_tunjangan_karyawan A
          LEFT JOIN tb_tunjangan B
            ON A.id_tunjangan = B.id_tunjangan
          LEFT JOIN tb_karyawan C
            ON A.id_karyawan = C.id_karyawan AND A.kode_kantor = C.kode_kantor
          WHERE A.is_aktif = '0' AND C.isDefault = '1' AND kode_tunjangan NOT IN('KONSUL1','KONSUL2','LEMBUR','TINDAKAN','UPSELLING','UM','LIBUR')
      ");
    }

    function insert_proses_potongan($periode)
    {
      $this->db->query("
          INSERT INTO tb_d_tampung_gaji (
            id_karyawan,
            periode,
            kode_akun,
            nama_akun,
            jenis_akun,
            keterangan,
            nominal,
            user_ins,
            kode_kantor
          )
          SELECT A.id_karyawan,'".$periode."' AS periode,COALESCE(kode_potongan,'') AS kode_akun,nama_potongan,
                 'KREDIT' AS jenis_akun,'' Keterangan,A.nominal,'".$this->session->userdata('ses_id_karyawan')."' user_ins,A.kode_kantor
          FROM tb_potongan_karyawan A
          LEFT JOIN tb_potongan B
            ON A.id_potongan = B.id_potongan
          LEFT JOIN tb_karyawan C
            ON A.id_karyawan = C.id_karyawan AND A.kode_kantor = C.kode_kantor
          WHERE A.is_aktif = '0' AND C.isDefault = '1'
          AND B.kode_potongan NOT IN('LATE','ABSEN')
      ");
    }

    function insert_header_payment($data)
    {
      $this->db->insert('tb_payment',$data);
    }

    function delete_payment($periode)
    {
      $this->db->query("DELETE FROM tb_payment WHERE periode = '".$periode."'");
    }

    function laporan_gaji($periode)
    {
      $data = $this->db->query("SELECT * FROM tb_payment
                                WHERE periode = '".$periode."'")->result();

      return $data;
    }

    function get_header_payment($id)
    {
      $data = $this->db->query("
          SELECT id_payment,
                  id_karyawan,
                  nama_karyawan,
                  no_karyawan,
                  jabatan,
                  tgl_payment,
                  periode,
                  gaji_pokok,
                  total_tunjangan,
                  total_potongan,
                  pph_21,
                  gaji_kotor,
                  gaji_bersih,
                  is_approve,
                  tgl_ins,
                  tgl_updt,
                  user_ins,
                  user_updt,
                  kode_kantor 
          FROM tb_payment
          WHERE id_payment = '".$id."'
      ")->row();

      return $data;
    }

    function get_detail_payment($id_karyawan,$periode)
    {
      $data = $this->db->query("
          SELECT id_tampung,
                id_karyawan,
                periode,
                kode_akun,
                nama_akun,
                jenis_akun,
                keterangan,
                nominal,
                tgl_ins,
                tgl_updt,
                user_ins,
                user_updt,
                kode_kantor
          FROM tb_d_tampung_gaji
          WHERE id_karyawan = '".$id_karyawan."' AND periode = '".$periode."'
      ")->result();

      return $data;
    }

}
