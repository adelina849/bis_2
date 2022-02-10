<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_gl_admin_hitung_gaji extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('M_gl_hitung_gaji','M_gl_gaji_pokok','M_akun','M_tunjangan_karyawan','M_potongan_karyawan'));
        $this->load->library('form_validation');
	       $this->load->library('datatables');
    }

    public function index()
    {
        

        if(($this->session->userdata('ses_user_admin') == null) or ($this->session->userdata('ses_pass_admin') == null))
        {
          header('Location: '.base_url().'gl-admin-login');
        }
        else
        {
          $cek_ses_login = $this->M_gl_karyawan->get_karyawan_jabatan_row(" WHERE A.user = '".$this->session->userdata('ses_user_admin')."' AND A.pass = '".base64_encode(md5($this->session->userdata('ses_pass_admin_pure')))."'  AND A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ");

          if(!empty($cek_ses_login))
          {

            $id_karyawan = $this->uri->segment(2,0);
            $kode_kantor = $this->uri->segment(3,0);
            $periode = $this->uri->segment(4,0);

            if($periode == '')
            {
               $periode = date('Y-m');
            }

            $header = $this->M_gl_hitung_gaji->get_karyawan($id_karyawan,$kode_kantor);
            $list_kantor = $this->M_gl_hitung_gaji->list_kantor();

            $list_tunjangan = $this->M_gl_hitung_gaji->list_tunjangan_karyawan($id_karyawan,$kode_kantor,$periode);
            $list_potongan = $this->M_gl_hitung_gaji->list_potongan_karyawan($id_karyawan,$kode_kantor,$periode);

            $data = array(
                      'page_content'=>'c_gl_hitung_gaji/gl_hitung_gaji',
                      'header'=>$header,
                      'list_tunjangan' => $list_tunjangan,
                      'list_potongan' => $list_potongan,
                      'list_kantor' => $list_kantor,
                      'id_karyawan' => $id_karyawan,
                      'periode' => $periode,
                    );
            $this->load->view('pusat/container',$data);
          }
          else
          {
            header('Location: '.base_url().'gl-admin-login');
          }
        }
    }

    public function list_karyawan()
    {
      $cari = $this->input->post('cari');

      $data = $this->M_gl_hitung_gaji->list_karyawan($cari);

      $no=1;
      //echo 'dodol';
      foreach ($data as $row) {
        echo '<tr>';
        echo '<input type="hidden" id="kode_kantor_'.$no.'" value="'.$row->kode_kantor.'" />';
        echo '<td><input type="hidden" id="id_karyawan_'.$no.'" value="'.$row->id_karyawan.'" />'.$no.'</td>';
        echo '<td><input type="hidden" id="no_karyawan_'.$no.'" value="'.$row->no_karyawan.'" />'.$row->no_karyawan.'</td>';
        echo '<td><input type="hidden" id="nama_karyawan_'.$no.'" value="'.$row->nama_karyawan.'" />'.$row->nama_karyawan.'</td>';
        echo '<td><input type="hidden" id="nama_kantor_'.$no.'" value="'.$row->nama_kantor.'" />'.$row->nama_kantor.'</td>';
        echo '<td><button type="button" class="btn btn-info" data-dismiss="modal" onclick="pilihKaryawan('.$no.')">Pilih</button></td>';
        echo '</tr>';
        $no++;
      }
    }

    public function hitung_potongan_absensi()
    {
      $periode = $this->input->post('periode');
      $tgl_to = $this->input->post('tgl_to');

      //echo $periode;

      $tgl_conv = date_create($tgl_to.' first day of last month');

      $tgl_from = $tgl_conv->format('Y-m-24'); //2011-02

      $this->M_gl_hitung_gaji->delete_d_tampung($periode,'ABSEN');

      $data = $this->M_gl_hitung_gaji->list_karyawan('');

      foreach ($data as $row) {
        $this->M_gl_hitung_gaji->hitung_potongan_absen($row->id_karyawan,$row->kode_kantor,$tgl_from,$tgl_to,$periode);  
      }

      echo 'ok';
    }

    public function hitung_upselling()
    {
      $periode = $this->input->post('periode');
      $tgl_to = $this->input->post('tgl_to');

      //echo $periode;

      $tgl_conv = date_create($tgl_to.' first day of last month');

      $tgl_from = $tgl_conv->format('Y-m-24'); //2011-02

      $this->M_gl_hitung_gaji->delete_d_tampung($periode,'UPSELLING');

      $this->M_gl_hitung_gaji->hitung_upselling($tgl_from,$tgl_to,$periode);

      echo 'ok';
      
    }

    public function hitung_uang_makan()
    {
      $periode = $this->input->post('periode');
      $tgl_to = $this->input->post('tgl_to');

      //echo $periode;

      $tgl_conv = date_create($tgl_to.' first day of last month');

      $tgl_from = $tgl_conv->format('Y-m-24'); //2011-02

      $this->M_gl_hitung_gaji->delete_d_tampung($periode,'UM');

      $this->M_gl_hitung_gaji->hitung_uang_makan($tgl_from,$tgl_to,$periode);

      echo 'ok';
    }

    public function hitung_potongan_late()
    {
      $periode = $this->input->post('periode');
      //$kode_kantor = $this->input->post('kode_kantor');
      $tgl_to = $this->input->post('tgl_to');
      $tgl_conv = date_create($tgl_to.' first day of last month');
      $tgl_from = $tgl_conv->format('Y-m-24'); //2011-02

      $this->M_gl_hitung_gaji->delete_d_tampung($periode,'LATE');

      $list_kode_kantor = array('CJR','BDG','JKT','KNG','CPNS','SBY','OLN');

      for ($i = 0; $i < count($list_kode_kantor); $i++) {
          
        $kode_kantor = $list_kode_kantor[$i];

        $cabang = '0';
        if($kode_kantor == 'CJR') $cabang = '0';
        if($kode_kantor == 'BDG') $cabang = '1';
        if($kode_kantor == 'JKT') $cabang = '2';
        if($kode_kantor == 'KNG') $cabang = '3';
        if($kode_kantor == 'CPNS') $cabang = '4';
        if($kode_kantor == 'SBY') $cabang = '5';
        if($kode_kantor == 'OLN') $cabang = '6';

        //tarik data terlambat per periode
        $data_absen = $this->M_gl_hitung_gaji->tarik_late_cabang($tgl_from,$tgl_to,$cabang,'');
        //print_r($data_absen);

        $data = $this->M_gl_hitung_gaji->list_karyawan('');

        //print_r($data);
        
        
        foreach ($data as $row) {
          $id_karyawan = $row->id_karyawan;
          $id_mesin_empl = $row->id_mesin_empl;
          $jenis_karyawan = $row->isDokter;
          
          

          $list_jam_kerja = $this->M_gl_hitung_gaji->list_jam_kerja($id_karyawan,$tgl_from,$tgl_to,$kode_kantor);

          foreach ($list_jam_kerja as $row2) {

            //print_r($data_absen);
            foreach ($data_absen as $row3) {
              if($row3->tanggal == $row2->tanggal && $row3->id_karyawan == $row2->id_mesin_empl && $row3->kode_kantor == $row2->kode_kantor)
              {
                if($row3->num == '1')
                 {
                   $jam_masuk_aktual = $row3->jam;
                   $jam_masuk = $row2->jam_masuk;

                   $j_aktual = strtotime($row3->tanggal." ".$jam_masuk_aktual);
                   $j_masuk = strtotime($row3->tanggal." ".$jam_masuk);

                   $late = round(($j_aktual - $j_masuk) / 60,2);
                   $late_murni = 0;

                   
                   if($jenis_karyawan == 'MANAGEMENT')
                   {
                      $late_murni = $late - 10;
                   } else if($jenis_karyawan == 'DOKTER')
                   {
                      $late_murni = $late - 15;
                   } else {
                      $late_murni = $late - 5;
                   }

                   $nilai = floor(($late_murni / 5) +1);

                   

                   if($nilai < 0) $nilai = 0;
                   

                   if($nilai >= 12) $nilai = 4;

                   $nominal = $nilai * 5000;
                   
                   if($id_karyawan == '9')
                   {
                     echo 'telat '.$late;
                     echo '<br>';
                     echo 'nilai'.$nilai;
                     echo '<br>'; 
                   }

                   if($late_murni > 0)
                   { 
                    $cek = $this->M_gl_hitung_gaji->cek_d_tampung($id_karyawan,$periode,'LATE');
                    if(empty($cek))
                    {
                      $post = array(
                          'id_karyawan' => $id_karyawan,
                          'periode' => $periode,
                          'kode_akun' => 'LATE',
                          'nama_akun' => 'Terlambat',
                          'jenis_akun' => 'KREDIT',
                          'keterangan' => '',
                          'nominal' => $nominal,
                          'user_ins' => $this->session->userdata('ses_id_karyawan'),
                          'kode_kantor' => $kode_kantor
                      );

                      $this->M_gl_hitung_gaji->insert_d_tampung_gaji($post);
                    } else {
                      $this->M_gl_hitung_gaji->update_d_tampung($id_karyawan,$periode,'LATE',$nominal);
                    }
                  }
                      
                 }
              }
            }
          }

        }
      }
      //echo 'ok';
    }

    public function hitung_lembur()
    {
      $periode = $this->input->post('periode');
      //$kode_kantor = $this->input->post('kode_kantor');
      $tgl_to = $this->input->post('tgl_to');
      $tgl_conv = date_create($tgl_to.' first day of last month');
      $tgl_from = $tgl_conv->format('Y-m-24'); //2011-02

      $this->M_gl_hitung_gaji->delete_d_tampung($periode,'LEMBUR');

      $list_kode_kantor = array('CJR','BDG','JKT','KNG','CPNS','SBY','OLN');

      for ($i = 0; $i < count($list_kode_kantor); $i++) {
          
        $kode_kantor = $list_kode_kantor[$i];

        $cabang = '0';
        if($kode_kantor == 'CJR') $cabang = '0';
        if($kode_kantor == 'BDG') $cabang = '1';
        if($kode_kantor == 'JKT') $cabang = '2';
        if($kode_kantor == 'KNG') $cabang = '3';
        if($kode_kantor == 'CPNS') $cabang = '4';
        if($kode_kantor == 'SBY') $cabang = '5';
        if($kode_kantor == 'OLN') $cabang = '6';

        //tarik data terlambat per periode
        $data_absen = $this->M_gl_hitung_gaji->tarik_lembur($tgl_from,$tgl_to,$cabang,'');
        //print_r($data_absen);

        $data = $this->M_gl_hitung_gaji->list_karyawan('');

        //print_r($data);
        
        
        foreach ($data as $row) {
          $id_karyawan = $row->id_karyawan;
          $id_mesin_empl = $row->id_mesin_empl;
          $jenis_karyawan = $row->nama_jabatan;
          $masa_kerja = $row->masa_kerja;

          $list_jam_kerja = $this->M_gl_hitung_gaji->list_jam_kerja($id_karyawan,$tgl_from,$tgl_to,$kode_kantor);

          foreach ($list_jam_kerja as $row2) {

            //print_r($data_absen);
            foreach ($data_absen as $row3) {
              if($row3->tanggal == $row2->tanggal && $row3->id_karyawan == $row2->id_mesin_empl && $row3->kode_kantor == $row2->kode_kantor)
              {
                if(($row3->num == '2' && $row3->jam >= '18:00') || ($row3->num == '3' && $row3->jam >= '18:00'))
                {
                   $jam_keluar_aktual = $row3->jam;
                   $jam_keluar = $row2->jam_keluar;

                   $j_aktual = strtotime($row3->tanggal." ".$jam_keluar_aktual);
                   $j_keluar = strtotime($row3->tanggal." ".$jam_keluar);

                   //ambil nilai lembur per 30 menit = 1 point;
                   $lembur = floor(($j_aktual - $j_keluar) / 1800);
                   $lembur_murni = 0;

                   //echo ' aktual keluar: '.$jam_keluar_aktual;
                   //echo ' jam keluar: '.$jam_keluar;
                   //echo ' lembur: '.$lembur;
                   $nilai=0;
                   
                   if($lembur > 0 && $jenis_karyawan != 'MANAGEMENT')
                   { 
                    if($jenis_karyawan == 'DOKTER' && $masa_kerja < 1)
                    {
                      $nilai = 10000;  //setengah dari 20rb/jam
                    } else if($jenis_karyawan == 'DOKTER' && $masa_kerja >= 1)
                    {
                      $nilai = 20000;  //setengah dari 40rb/jam
                    } else if($jenis_karyawan == 'APOTEKER')
                    {
                      $nilai = 20000;
                    } else {
                      
                    }

                    $nominal = $nilai * $lembur;

                    $cek = $this->M_gl_hitung_gaji->cek_d_tampung($id_karyawan,$periode,'LEMBUR');
                    if(empty($cek))
                    {
                      $post = array(
                          'id_karyawan' => $id_karyawan,
                          'periode' => $periode,
                          'kode_akun' => 'LEMBUR',
                          'nama_akun' => 'Ins Lembur',
                          'jenis_akun' => 'DEBIT',
                          'keterangan' => '',
                          'nominal' => $nominal,
                          'user_ins' => $this->session->userdata('ses_id_karyawan'),
                          'kode_kantor' => $kode_kantor
                      );

                      $this->M_gl_hitung_gaji->insert_d_tampung_gaji($post);
                    } else {
                      $this->M_gl_hitung_gaji->update_d_tampung($id_karyawan,$periode,'LEMBUR',$nominal);
                    }
                  }
                      
                 }
              }
            }
          }

        }
      }
      //echo 'ok';
    }

    public function hitung_konsultasi_pasien_baru()
    {
      $periode = $this->input->post('periode');
      //$kode_kantor = $this->input->post('kode_kantor');
      $tgl_to = $this->input->post('tgl_to');
      $tgl_conv = date_create($tgl_to.' first day of last month');
      $tgl_from = $tgl_conv->format('Y-m-24'); //2011-02

      //HAPUS DLU JIKA SUDAH ADA DATA
      $this->M_gl_hitung_gaji->delete_d_tampung($periode,'KONSUL1');

      $list_dokter = $this->M_gl_hitung_gaji->list_dokter();


      //INSENTIF PASIEN BARU
      foreach ($list_dokter as $row) {
          $this->M_gl_hitung_gaji->hitung_konsul_pasien_baru($row->id_karyawan,$tgl_from,$tgl_to,$periode);
      }

      echo 'ok';
    }

    public function hitung_konsultasi_pasien_lama()
    {
      $periode = $this->input->post('periode');
      //$kode_kantor = $this->input->post('kode_kantor');
      $tgl_to = $this->input->post('tgl_to');
      $tgl_conv = date_create($tgl_to.' first day of last month');
      $tgl_from = $tgl_conv->format('Y-m-24'); //2011-02

      //HAPUS DLU JIKA SUDAH ADA DATA
      $this->M_gl_hitung_gaji->delete_d_tampung($periode,'KONSUL2');

      $list_dokter = $this->M_gl_hitung_gaji->list_dokter();


      //INSENTIF PASIEN BARU
      foreach ($list_dokter as $row) {
          $this->M_gl_hitung_gaji->hitung_konsul_pasien_lama($row->id_karyawan,$tgl_from,$tgl_to,$periode);
      }

      echo 'ok';
    }

    public function hitung_tindakan_dokter()
    {
      $id_karyawan = $this->input->post('id_karyawan');
      $periode = $this->input->post('periode');
      $nominal = $this->input->post('nominal');

      $cek = $this->M_gl_hitung_gaji->cek_d_tampung($id_karyawan,$periode,'TINDAKAN');

      if(empty($cek))
      {
        $post = array(
            'id_karyawan' => $id_karyawan,
            'periode' => $periode,
            'kode_akun' => 'TINDAKAN',
            'nama_akun' => 'Ins Tindakan',
            'jenis_akun' => 'DEBIT',
            'keterangan' => '',
            'nominal' => $nominal,
            'user_ins' => $this->session->userdata('ses_id_karyawan'),
            'kode_kantor' => $this->session->userdata('ses_kode_kantor')
        );

        $this->M_gl_hitung_gaji->insert_d_tampung_gaji($post);
      } else {
        $this->M_gl_hitung_gaji->update_d_tampung2($id_karyawan,$periode,'TINDAKAN',$nominal);
      }

      echo 'ok';
    }


    public function update_tunjangan()
    {
      $id = $this->input->post('id_tunjangan');
      $id_karyawan = $this->input->post('id_karyawan');
      $periode = $this->input->post('periode');
      $kode_tunjangan = $this->input->post('kode_tunjangan');
      $nama_tunjangan = $this->input->post('nama_tunjangan');
      $id_tampung = $this->input->post('id_tampung');
      $nominal = $this->input->post('nominal');
      $tgl = date('Y-m-d H:i:s');

      if($id_tampung == '')
      {
        $data = array(
            'id_karyawan' => $id_karyawan,
            'periode' => $periode,
            'kode_akun' => $kode_tunjangan,
            'nama_akun' => $nama_tunjangan,
            'jenis_akun' => 'DEBIT',
            'keterangan' => '',
            'nominal' => $nominal,
            'user_ins' => $this->session->userdata('ses_id_karyawan'),
            'kode_kantor' => $this->session->userdata('ses_kode_kantor')
          );

        $this->M_gl_hitung_gaji->insert_d_tampung_gaji($data);

      } else {
        $this->M_gl_hitung_gaji->update_d_tampung_by_id($id_tampung,$nominal);  
      }

    
      // $data = array(
      //   'nominal' => $this->input->post('nominal',TRUE),
      //   'tgl_updt' => $tgl,
      // );

      // $this->M_tunjangan_karyawan->update($id, $data);
    }

    public function hapus_tunjangan()
    {
      $id = $this->input->post('id_tunjangan');
      $id_tampung = $this->input->post('id_tampung');
      $this->M_tunjangan_karyawan->delete($id);

      if($id_tampung != '')
      {
        $this->M_gl_hitung_gaji->delete_d_tampung_by_id($id_tampung);
      } 
    }

    public function setaktif_tunjangan()
    {
      $id = $this->input->post('id_tunjangan');
      $is_aktif = $this->input->post('is_aktif');

      $tgl = date('Y-m-d H:i:s');

      $data = array(
        'is_aktif' => $is_aktif,
        'tgl_updt' => $tgl,
      );

      $this->M_tunjangan_karyawan->update($id, $data); 
    }

    public function update_potongan()
    {
      $id = $this->input->post('id_potongan');
      $id_tampung = $this->input->post('id_tampung');
      $nominal = $this->input->post('nominal');
      $tgl = date('Y-m-d H:i:s');

      $this->M_gl_hitung_gaji->update_d_tampung_by_id($id_tampung,$nominal);

      // $data = array(
      //   'nominal' => $this->input->post('nominal',TRUE),
      //   'tgl_updt' => $tgl,
      // );

      // $this->M_potongan_karyawan->update($id, $data);
    }

    public function hapus_potongan()
    {
      $id = $this->input->post('id_potongan');
      $id_tampung = $this->input->post('id_tampung');
      $this->M_potongan_karyawan->delete($id);

      if($id_tampung != '')
      {
        $this->M_gl_hitung_gaji->delete_d_tampung_by_id($id_tampung);
      } 
    }



    public function setaktif_potongan()
    {
      $id = $this->input->post('id_potongan');
      $is_aktif = $this->input->post('is_aktif');

      $tgl = date('Y-m-d H:i:s');

      $data = array(
        'is_aktif' => $is_aktif,
        'tgl_updt' => $tgl,
      );

      $this->M_potongan_karyawan->update($id, $data); 
    }

    public function proses_gaji()
    {
      $periode = $this->input->post('periode');
      $tgl = date('Y-m-d');

      $this->M_gl_hitung_gaji->reset_d_tampung($periode);
      $this->M_gl_hitung_gaji->delete_payment($periode);

      //TARIK DATA TUNJANGAN DARI MASTER
      $this->M_gl_hitung_gaji->insert_proses_tunjangan($periode);

      //TARIK DATA POTONGAN DARI MASTER
      $this->M_gl_hitung_gaji->insert_proses_potongan($periode);   

      $list_karyawan = $this->M_gl_hitung_gaji->list_karyawan('');

      foreach ($list_karyawan as $row) {

        $gaji_pokok = $this->M_gl_gaji_pokok->get_gaji_pokok($row->id_karyawan,$row->kode_kantor);
        $total_tunjangan = $this->M_gl_hitung_gaji->total_tunjangan_karyawan($row->id_karyawan,$row->kode_kantor,$periode);
        $total_potongan = $this->M_gl_hitung_gaji->total_potongan_karyawan($row->id_karyawan,$row->kode_kantor,$periode);
        
        $gaji_kotor = $gaji_pokok+$total_tunjangan;
        $gaji_bersih = $gaji_kotor - $total_potongan;

        $data = array(
            'id_karyawan' => $row->id_karyawan,
            'nama_karyawan' => $row->nama_karyawan,
            'no_karyawan' => $row->no_karyawan,
            'jabatan' => $row->nama_jabatan,
            'tgl_payment' => $tgl,
            'periode' => $periode,
            'gaji_pokok' => $gaji_pokok,
            'total_tunjangan' => $total_tunjangan,
            'total_potongan' => $total_potongan,
            'pph_21' => '0',
            'gaji_kotor' => $gaji_kotor,
            'gaji_bersih' => $gaji_bersih,
            'is_approve' => '0',
            'user_ins' => $this->session->userdata('ses_id_karyawan'),
            'kode_kantor' => $row->kode_kantor,
          );
        $this->M_gl_hitung_gaji->insert_header_payment($data);
      }

    }

    public function laporan()
    {
      if(($this->session->userdata('ses_user_admin') == null) or ($this->session->userdata('ses_pass_admin') == null))
      {
        header('Location: '.base_url().'gl-admin-login');
      }
      else
      {
        $periode = $this->uri->segment(2,0);

        $laporan = $this->M_gl_hitung_gaji->laporan_gaji($periode);

        $data = array(
                      'page_content'=>'c_gl_hitung_gaji/laporan_gaji',
                      'data' => $laporan,
                      'periode' => $periode,
                    );
        $this->load->view('pusat/container',$data);
      }
    }

    public function detail_penggajian()
    {
      $id_karyawan = $this->uri->segment(2,0);
      $tgl_to = $this->uri->segment(3,0);
      $periode = $this->uri->segment(4,0);

      $tgl_conv = date_create($tgl_to.' first day of last month');
      $tgl_from = $tgl_conv->format('Y-m-24'); //2011-02

      $list_data = $this->M_gl_hitung_gaji->detail_penggajian($id_karyawan,$tgl_from,$tgl_to);

      $data = array(
                      'page_content'=>'c_gl_hitung_gaji/detail_penggajian',
                      'id_karyawan' => $id_karyawan,
                      'periode' => $periode,
                      'tgl_to' => $tgl_to,
                      'data' => $list_data,
                    );
        $this->load->view('pusat/container',$data);
    }

    

    public function detail_konsul_baru()
    {
      $id_karyawan = $this->uri->segment(2,0);
      $tgl_to = $this->uri->segment(3,0);
      $periode = $this->uri->segment(4,0);

      $tgl_conv = date_create($tgl_to.' first day of last month');
      $tgl_from = $tgl_conv->format('Y-m-24'); //2011-02

      $list_data = $this->M_gl_hitung_gaji->detail_konsul_baru($id_karyawan,$tgl_from,$tgl_to);

      $data = array(
                      'page_content'=>'c_gl_hitung_gaji/detail_konsul_baru',
                      'id_karyawan' => $id_karyawan,
                      'periode' => $periode,
                      'tgl_to' => $tgl_to,
                      'data' => $list_data,
                    );
        $this->load->view('pusat/container',$data);
    }

    public function detail_konsul_lama()
    {
      $id_karyawan = $this->uri->segment(2,0);
      $tgl_to = $this->uri->segment(3,0);
      $periode = $this->uri->segment(4,0);

      $tgl_conv = date_create($tgl_to.' first day of last month');
      $tgl_from = $tgl_conv->format('Y-m-24'); //2011-02

      $list_data = $this->M_gl_hitung_gaji->detail_konsul_lama($id_karyawan,$tgl_from,$tgl_to);

      $data = array(
                      'page_content'=>'c_gl_hitung_gaji/detail_konsul_lama',
                      'id_karyawan' => $id_karyawan,
                      'periode' => $periode,
                      'tgl_to' => $tgl_to,
                      'data' => $list_data,
                    );
        $this->load->view('pusat/container',$data);
    }

    public function export_excel()
    {
      $periode = $this->uri->segment(2,0);
      $laporan = $this->M_gl_hitung_gaji->laporan_gaji($periode);

      $data = array(
                    'data' => $laporan,
                    'periode' => $periode,
                  );
      $this->load->view('pusat/page/c_gl_hitung_gaji/excel_laporan_gaji.html',$data);

    }

    public function cetak_excel_detail_tindakan()
    {
      $id_karyawan = $this->uri->segment(2,0);
      $tgl_to = $this->uri->segment(3,0);
      $periode = $this->uri->segment(4,0);

      $tgl_conv = date_create($tgl_to.' first day of last month');
      $tgl_from = $tgl_conv->format('Y-m-24'); //2011-02

      $list_data = $this->M_gl_hitung_gaji->detail_penggajian($id_karyawan,$tgl_from,$tgl_to);

      $data = array(
                      'id_karyawan' => $id_karyawan,
                      'periode' => $periode,
                      'data' => $list_data,
                    );
      $this->load->view('pusat/page/c_gl_hitung_gaji/excel_detail_tindakan.html',$data);
    }

    public function excel_konsul_baru()
    {
      $id_karyawan = $this->uri->segment(2,0);
      $tgl_to = $this->uri->segment(3,0);
      $periode = $this->uri->segment(4,0);

      $tgl_conv = date_create($tgl_to.' first day of last month');
      $tgl_from = $tgl_conv->format('Y-m-24'); //2011-02

      $list_data = $this->M_gl_hitung_gaji->detail_konsul_baru($id_karyawan,$tgl_from,$tgl_to);

      $data = array(
                      'page_content'=>'c_gl_hitung_gaji/detail_konsul_baru',
                      'id_karyawan' => $id_karyawan,
                      'periode' => $periode,
                      'tgl_to' => $tgl_to,
                      'data' => $list_data,
                    );

      $this->load->view('pusat/page/c_gl_hitung_gaji/excel_konsul_baru.html',$data);
    }

    public function excel_konsul_lama()
    {
      $id_karyawan = $this->uri->segment(2,0);
      $tgl_to = $this->uri->segment(3,0);
      $periode = $this->uri->segment(4,0);

      $tgl_conv = date_create($tgl_to.' first day of last month');
      $tgl_from = $tgl_conv->format('Y-m-24'); //2011-02

      $list_data = $this->M_gl_hitung_gaji->detail_konsul_lama($id_karyawan,$tgl_from,$tgl_to);

      $data = array(
                      'page_content'=>'c_gl_hitung_gaji/detail_konsul_lama',
                      'id_karyawan' => $id_karyawan,
                      'periode' => $periode,
                      'tgl_to' => $tgl_to,
                      'data' => $list_data,
                    );

      $this->load->view('pusat/page/c_gl_hitung_gaji/excel_konsul_lama.html',$data);
    }

    public function cetak_slip_gaji()
    {
      $this->load->library('fpdf');

      $id_payment = $this->uri->segment(2,0);

      $header = $this->M_gl_hitung_gaji->get_header_payment($id_payment);

      $detail = $this->M_gl_hitung_gaji->get_detail_payment($header->id_karyawan,$header->periode);

      $pdf = new FPDF("P","mm","A4");
      $pdf->AddPage();
      
      $pdf->SetFont("Arial","B","10");

      //buat border luar
      $pdf->Line(18,12,135,12);   //garis horizontal atas
      $pdf->Line(18,30,135,30);   //garis horizontal atas 2


      $pdf->SetXY(20, 15);
      $pdf->Cell(110,5,'Glafidsya Medika',0,1,'C');
      $pdf->setXY(20,20);
      $pdf->Cell(110,5,'Jl. Perintis Kemerdekaan No. 17 (0263) 281374',0,1,'C');
      $pdf->setXY(20,25);
      $pdf->Cell(110,5,'SLIP GAJI',0,1,'C');

      $pdf->SetFont("Arial","","10");
      $pdf->setXY(20,35);
      $pdf->Cell(100,5,'Tanggal ',0,1,'L');
      $pdf->setXY(40,35);
      $pdf->Cell(100,5,': '.$header->tgl_payment.'',0,1,'L');

      $pdf->setXY(20,40);
      $pdf->Cell(100,5,'Nama ',0,1,'L');
      $pdf->setXY(40,40);
      $pdf->Cell(100,5,': '.$header->nama_karyawan.' ',0,1,'L');

      $pdf->setXY(20,45);
      $pdf->Cell(100,5,'Jabatan ',0,1,'L');
      $pdf->setXY(40,45);
      $pdf->Cell(100,5,': '.$header->jabatan.' ',0,1,'L');

      $pdf->setXY(20,50);
      $pdf->Cell(100,5,'Bulan ',0,1,'L');
      $pdf->setXY(40,50);
      $pdf->Cell(100,5,': '.$header->periode.' ',0,1,'L');

      //LIST PENDAPATAN
      $pdf->setXY(80,60);
      $pdf->SetFont("Arial","B","10");
      $pdf->Cell(100,5,'Pendapatan ',0,1,'L');
      $pdf->Line(80,65,130,65);   //garis vertikal kiri
      
      $pdf->SetFont("Arial","","10");
      $pdf->setXY(20,70);
      $pdf->Cell(50,5,'Gaji Pokok' ,0,1,'L');
      $pdf->setXY(80,70);
      $pdf->Cell(10,5,'Rp',0,1,'L');      
      $pdf->setXY(90,70);
      $pdf->Cell(40,5, number_format($header->gaji_pokok),0,1,'R');        

      $startY = 70;
      $incY = 5;

      foreach ($detail as $d) {
        if($d->jenis_akun == 'DEBIT')
        {

          $pdf->setXY(20,$startY + $incY);
          $pdf->Cell(50,5, $d->nama_akun ,0,1,'L');
          $pdf->setXY(80,$startY + $incY);
          $pdf->Cell(10,5,'Rp',0,1,'L');      
          $pdf->setXY(90,$startY + $incY);

          if($d->nominal == '0')
          {
            $pdf->Cell(40,5, '-',0,1,'R');
          } else {
            $pdf->Cell(40,5, number_format($d->nominal),0,1,'R');  
          }
          

          $incY += 5;
        }
      }

      $incY += 3;

      $pdf->Line(80,$startY + $incY,130,$startY + $incY );

      $incY += 2;

      $pdf->SetFont("Arial","B","10");
      $pdf->setXY(80,$startY + $incY);
      $pdf->Cell(10,5,'Rp',0,1,'L');
      $pdf->setXY(90,$startY + $incY);
      $pdf->Cell(40,5, number_format($header->total_tunjangan+$header->gaji_pokok),0,1,'R');
      $pdf->SetFont("Arial","","10");

      $incY +=10;

      //LIST POTONGAN
      $pdf->setXY(80,$startY + $incY);
      $pdf->SetFont("Arial","B","10");
      $pdf->Cell(100,5,'Potongan ',0,1,'L');
      $pdf->SetFont("Arial","","10");

      $incY +=5;
      $pdf->Line(80,$startY + $incY,130,$startY + $incY);

      $incY +=4;

      foreach ($detail as $d) {
        if($d->jenis_akun == 'KREDIT')
        {
          $pdf->setXY(20,$startY + $incY);
          $pdf->Cell(50,5, $d->nama_akun ,0,1,'L');
          $pdf->setXY(80,$startY + $incY);
          $pdf->Cell(10,5,'Rp',0,1,'L');      
          $pdf->setXY(90,$startY + $incY);
          
          if($d->nominal == '0')
          {
            $pdf->Cell(40,5, '-',0,1,'R');
          } else {
            $pdf->Cell(40,5, number_format($d->nominal),0,1,'R');  
          }

          $incY += 5;
        }
      }

      $pdf->SetFont("Arial","B","10");
      $pdf->Line(80,$startY + $incY,130,$startY + $incY );


      $incY += 2;
      $pdf->setXY(80,$startY + $incY);
      $pdf->Cell(10,5,'Rp',0,1,'L');
      $pdf->setXY(90,$startY + $incY);
      $pdf->Cell(40,5, number_format($header->total_potongan),0,1,'R');


      $incY +=10;

      $pdf->setXY(20,$startY + $incY);
      $pdf->Cell(50,5, 'Total' ,0,1,'L');
      $pdf->setXY(80,$startY + $incY);
      $pdf->Cell(10,5,'Rp',0,1,'L');
      $pdf->setXY(90,$startY + $incY);
      $pdf->Cell(40,5, number_format($header->gaji_pokok+$header->total_tunjangan-$header->total_potongan),0,1,'R');

      $incY +=5;

      $pdf->setXY(20,$startY + $incY);
      $pdf->Cell(50,5, 'Pajak PPh 21' ,0,1,'L');
      $pdf->setXY(80,$startY + $incY);
      $pdf->Cell(10,5,'Rp',0,1,'L');      
      $pdf->setXY(90,$startY + $incY);

      if($header->pph_21 == '0')
      {
        $pdf->Cell(40,5, '-',0,1,'R');
      } else {
        $pdf->Cell(40,5, number_format($header->pph_21),0,1,'R');  
      }

      
      $incY +=10;

      $pdf->setXY(20,$startY + $incY);
      $pdf->Cell(50,5, 'TOTAL GAJI YANG DITERIMA' ,0,1,'L');
      $pdf->setXY(80,$startY + $incY);
      $pdf->Cell(10,5,'Rp',1,1,'L');      
      $pdf->setXY(90,$startY + $incY);
      $pdf->Cell(40,5, number_format($header->gaji_bersih),1,1,'R');      

      $pdf->SetFont("Arial","","10");
      
      //APPROVAL
      $incY +=10;

      $pdf->setXY(20,$startY + $incY);
      $pdf->Cell(50,5, 'Menyetujui, ' ,0,1,'L');

      $incY +=5;
      $pdf->setXY(20,$startY + $incY);
      $pdf->Cell(50,5, 'Direktur Utama, ' ,0,1,'L');
      $pdf->setXY(90,$startY + $incY);
      $pdf->Cell(50,5, 'Accounting, ' ,0,1,'L');



      $incY +=15;
      $pdf->setXY(20,$startY + $incY);
      $pdf->Cell(50,5, 'dr. Reza Gladys ' ,0,1,'L');
      $pdf->setXY(90,$startY + $incY);
      $pdf->Cell(50,5, 'Hetty Sigarlaki' ,0,1,'L');

      $pdf->Line(18,12,18,$startY + $incY+10);   //garis vertikal kiri
      $pdf->Line(135,12,135,$startY + $incY+10);   //garis vertikal kanan
      $pdf->Line(18,$startY + $incY+10,135,$startY + $incY+10);   //garis horizontal bawah

      $pdf->Output();
    }
}
