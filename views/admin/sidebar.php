
		
		<style><!-- 
        SPAN.searchword { background-color:yellow; }
        // -->
			.tooltip
			{
				/*Terserah desainnya bagaimana~*/
				background:#303030;
				padding:10px;
				color:#f0f0f0;
				border-radius:10px;
				-moz-border-radius:10px;
				width:200px;
				text-align:center;

				/*yang ini penting!*/
				position:absolute; 
			}
			
			.tooltip:before
			{
				/*wajib!*/
				content:"";
				position:absolute;
			 
				/*ini nih cara buat segitiga tanpa gambar dgn CSS*/
				border:10px solid transparent;
				border-bottom:10px solid #303030;
			 
				/*supaya lokasi segitiganya rapi*/
				top:-20px;
				left:10px;
			}
			
			.tooltip{display:none;} /*dalam keadaan biasa tidak tampil*/
			a.link:hover .tooltip{display:block;} /*ketika mouse diarahkan ke a.link, tooltip ditampilkan*/
        </style>
        <script src="<?=base_url();?>assets/global/js/searchhi.js" type="text/javascript" language="JavaScript"></script>
        <script language="JavaScript"><!--
        function loadSearchHighlight()
        {
          SearchHighlight();
          document.searchhi.h.value = searchhi_string;
          if( location.hash.length > 1 ) location.hash = location.hash;
        }
        // -->
        </script>
    <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="<?php echo $this->session->userdata('ses_avatar_url');?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p><?php echo $this->session->userdata('ses_nama_karyawan');?></p>
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
          <!-- search form -->
          <form method="get" name="searchhi" class="sidebar-form" action="#">
            <div class="input-group">
              <input type="text" name="h" onkeyup="oWhichSubmit(this)" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
		  <ul class="sidebar-menu" data-widget="tree">
			<!-- CEK HAK AKSES FROM DATABASE -->
				<?php
					/*$akses_group1 = $this->m_akun->get_hak_akses_group1($this->session->userdata('ses_id_jabatan'));
					$akses_group1_main_group = $this->m_akun->get_hak_akses_group1_main_group($this->session->userdata('ses_id_jabatan'));
					$akses_group1_main_group_sub_main = $this->m_akun->get_hak_akses_group1_main_group_sub_group($this->session->userdata('ses_id_jabatan'));*/
				?>
			<!-- CEK HAK AKSES FROM DATABASE -->
		  
            <li class="header">MAIN NAVIGATION</li>
            <!-- <li class="active treeview"> -->
			<li id="1_dashboard" class="treeview">
              <a href="<?php echo base_url()?>gl-admin"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
            </li>
			
			<!-- CEK AKSES LEVEL 1 MAIN 2 DATA DASAR (BASIS DATA) -->
			
				<?php
				if( ($this->session->userdata('ses_akses_lvl1_2') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
				{
				?>
				<li id="2_basis_data" class="treeview">
				  <a href="#">
					<i class="fa fa-laptop"></i> 
						<span>Data Dasar (Basis Data)</span>
					<i class="fa fa-angle-left pull-right"></i>
				  </a>
				  <ul class="treeview-menu">
					
						<?php
						if( ($this->session->userdata('ses_akses_lvl2_21') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
						{
						?>
						<li id="21_basis_data_karyawan">
						  <a href="#"><i class="fa fa-folder"></i> Data Karyawan <i class="fa fa-angle-left pull-right"></i></a>
						  
						  <ul class="treeview-menu">
								
								<?php
								if( ($this->session->userdata('ses_akses_lvl3_212') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
								{
								?>
								<li id="212_basis_data_karyawan_departement"><a href="<?php echo base_url();?>gl-admin-departement"><i class="fa fa-edit"></i> Departemen </a></li>
								<?php
								}
								?>
						  
						  
								<?php
								if( ($this->session->userdata('ses_akses_lvl3_211') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
								{
								?>
								<li id="211_basis_data_karyawan_jabatan"><a href="<?php echo base_url();?>gl-admin-jabatan"><i class="fa fa-edit"></i> Jabatan </a></li>
								<?php
								}
								?>
								
								
								
								<?php
								if( ($this->session->userdata('ses_akses_lvl3_213') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
								{
								?>
								<li id="213_basis_data_karyawan_recruitment"><a href="<?php echo base_url();?>gl-admin-recruitment"><i class="fa fa-edit"></i> Recruitment </a></li>
								<?php
								}
								?>
								
								<?php
								if( ($this->session->userdata('ses_akses_lvl3_214') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
								{
								?>
								<li id="214_basis_data_karyawan_training"><a href="<?php echo base_url()?>gl-admin-training"><i class="fa fa-edit"></i> Training </a></li>
								<?php
								}
								?>
								
								<?php
								if( ($this->session->userdata('ses_akses_lvl3_215') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
								{
								?>
								<li id="215_basis_data_karyawan_promosi"><a href="<?php echo base_url()?>gl-admin-promosi"><i class="fa fa-edit"></i>Mutasi, Promosi & Demosi </a></li>
								<?php
								}
								?>
							
								<?php
								if( ($this->session->userdata('ses_akses_lvl3_216') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
								{
								?>
								<li id="216_basis_data_karyawan_karyawan"><a href="<?=base_url()?>gl-admin-data-karyawan"><i class="fa fa-edit"></i> Data Karyawan </a></li>
								<?php
								}
								?>
								
								<?php
								if( ($this->session->userdata('ses_akses_lvl3_217') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
								{
								?>
								<li id="217_basis_data_karyawan_sop"><a href="<?=base_url()?>gl-admin-sop"><i class="fa fa-edit"></i> SOP </a></li>
								<?php
								}
								?>
								
								<?php
								if( ($this->session->userdata('ses_akses_lvl3_218') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
								{
								?>
								<li id="218_basis_data_karyawan_reward">
									<a href="#"><i class="fa fa-edit"></i>Reward & Punishment<i class="fa fa-angle-left pull-right"></i></a>
									<ul class="treeview-menu">
										<?php
										if( ($this->session->userdata('ses_akses_lvl4_2182') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
										{
										?>
											<li id="2182_basis_data_karyawan_reward_peraturan"><a href="<?=base_url()?>gl-admin-peraturan"><i class="fa fa-edit"></i> Peraturan </a></li>
										<?php
										}
										?>
										
										<?php
										if( ($this->session->userdata('ses_akses_lvl4_2183') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
										{
										?>
											<li id="2183_basis_data_karyawan_reward_punishment"><a href="<?=base_url()?>gl-admin-punishment"><i class="fa fa-edit"></i> Punishment </a></li>
										<?php
										}
										?>
										
										<?php
										if( ($this->session->userdata('ses_akses_lvl4_2184') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
										{
										?>
											<!--<li id="2184_basis_data_karyawan_reward_reward"><a href="<?=base_url()?>admin-karyawan"><i class="fa fa-edit"></i> Reward </a></li>-->
										<?php
										}
										?>
										
									</ul>
								</li>
								<?php
								}
								?>
							
								<?php
								if( ($this->session->userdata('ses_akses_lvl3_219') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
								{
								?>
								<li id="219_basis_data_karyawan_akun">
									<a href="<?=base_url()?>gl-admin-akun"><i class="fa fa-edit"></i>Pemberian Akun</a>
								</li>
								<?php
								}
								?>
							
						  </ul>
						</li>
						<?php
						}
						?>
						
						
						
						<?php
						if( ($this->session->userdata('ses_akses_lvl2_10') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
						{
						?>
						<li id="210_media_transaksi"><a href="<?php echo base_url();?>gl-admin-media-transaksi"><i class="fa fa-edit"></i> Media Transaksi </a></li>
						<?php
						}
						?>
						
					
						
						<?php
						if( ($this->session->userdata('ses_akses_lvl2_23') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
						{
						?>
						<li id="23_basis_data_pasien">
						  <a href="#"><i class="fa fa-users"></i> Mustahik/Muzaki <i class="fa fa-angle-left pull-right"></i></a>
						  <ul class="treeview-menu">
						  
								<?php
								if( ($this->session->userdata('ses_akses_lvl3_231') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
								{
								?>
								<li id="231_basis_data_pasien_kategori"><a href="<?php echo base_url();?>gl-admin-kategori-pasien"><i class="fa fa-edit"></i> Kategori </a></li>
								<?php
								}
								?>
							
								<?php
								if( ($this->session->userdata('ses_akses_lvl3_232') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
								{
								?>
								<li id="232_basis_data_pasien_pasien"><a href="<?=base_url()?>gl-admin-pasien"><i class="fa fa-user"></i> Data</a></li>
								
								<?php
								}
								?>
								
						  </ul>
						</li>
						<?php
						}
						?>
				  </ul>
				</li>
				<?php
				}
				?>
			<!-- CEK AKSES LEVEL 1 MAIN 2 DATA DASAR (BASIS DATA) -->
			
			<?php
				if( ($this->session->userdata('ses_akses_lvl2_25') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
				{
				?>
				<li id="25_basis_data_keuangan">
				  <a href="#"><i class="fa fa-folder"></i> Akun Keuangan <i class="fa fa-angle-left pull-right"></i></a>
				  <ul class="treeview-menu">
				  
						<?php
						if( ($this->session->userdata('ses_akses_lvl3_251') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
						{
						?>
						<li id="251_basis_data_keuangan_bank"><a href="<?php echo base_url();?>gl-bank"><i class="fa fa-edit"></i> Data Bank </a></li>
						<?php
						}
						?>
					
						<?php
						if( ($this->session->userdata('ses_akses_lvl3_252') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
						{
						?>
						<li id="252_basis_data_keuangan_kodeAkuntansi"><a href="<?=base_url()?>gl-kode-akuntansi"><i class="fa fa-edit"></i> Kode Akuntansi </a></li>
						
						<li id="253_basis_data_keuangan_kodeProgram"><a href="<?=base_url()?>gl-kode-program"><i class="fa fa-edit"></i> Kode Program </a></li>
						
						<?php
						}
						?>
						
						
						
				  </ul>
				</li>
				<?php
				}
			?>
			
			<?php
						if( ($this->session->userdata('ses_akses_lvl2_26') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
						{
						?>
						<li id="26_basis_data_assets">
						  <a href="#"><i class="fa fa-folder"></i> Assets/Pinjaman <i class="fa fa-angle-left pull-right"></i></a>
						  <ul class="treeview-menu">
						  
								<?php
								if( ($this->session->userdata('ses_akses_lvl3_261') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
								{
								?>
								<li id="261_basis_data_assets_kategori"><a href="<?php echo base_url();?>gl-admin-kategori-assets"><i class="fa fa-edit"></i> Kategori Assets/Pinjaman </a></li>
								<?php
								}
								?>
							
								<?php
								if( ($this->session->userdata('ses_akses_lvl3_262') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
								{
								?>
								<li id="262_basis_data_assets_assets"><a href="<?php echo base_url();?>gl-admin-assets-pinjaman"><i class="fa fa-edit"></i> Data Assets/Pinjaman </a></li>
								<?php
								}
								?>
								
								<?php
								if( ($this->session->userdata('ses_akses_lvl3_263') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
								{
								?>
								<li id="263_basis_data_assets_ruangan"><a href="<?php echo base_url();?>gl-admin-ruangan-tempat-penyimpanan-barang"><i class="fa fa-edit"></i> Penyimpanan/Ruangan </a></li>
								<?php
								}
								?>
								
						  </ul>
						</li>
						<?php
						}
						?>
			
			<?php
			if( ($this->session->userdata('ses_akses_lvl1_4') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
			{
			?>
				<li class="treeview" id="4_surat">
					<a href="#">
						<i class="fa fa-envelope"></i> <span>Surat</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
					
						<?php
						if( ($this->session->userdata('ses_akses_lvl2_41') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
						{
						?>
							<li id="41_surat_masuk">
								<a href="<?php echo base_url();?>gl-admin-surat-masuk"><i class="fa fa-circle-o"></i> Surat Masuk</a>
							</li>
						<?php
						}
						?>
						
						<?php
						if( ($this->session->userdata('ses_akses_lvl2_42') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
						{
						?>
							<li  id="42_surat_keluar"><a href="<?php echo base_url();?>gl-admin-surat-keluar"><i class="fa fa-circle-o"></i> Surat Keluar</a>
							</li>
						<?php
						}
						?>
						
					</ul>
				</li>
			<?php
				}
			?>
				
				
			<?php
			if( ($this->session->userdata('ses_akses_lvl1_5') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
			{
			?>
			<li class="treeview" id="5_transaksi">
				<a href="#">
					<i class="fa fa-money"></i> <span>Transaksi</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						<small id="sb_jum_tr" class="label pull-right bg-red">0</small>
					</span>
					
				</a>
			  <ul class="treeview-menu">
						<?php
						if( ($this->session->userdata('ses_akses_lvl2_51') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
						{
						?>
							<li id="59_transaksi_Pemasukan"><a href="<?=base_url()?>gl-admin-pemasukan"><i class="fa fa-sign-in"></i> Pemasukan	</a></li>
						<?php
						}
						?>
						
						<?php
						if( ($this->session->userdata('ses_akses_lvl2_52') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
						{
						?>
							<li id="510_transaksi_cek_pasien"><a href="<?=base_url()?>gl-admin-cek-pasien"><i class="fa fa-file-word-o"></i> Cek Pasien</a></li>
						
							<li id="510_transaksi_ajuan"><a href="<?=base_url()?>gl-admin-pengajuan"><i class="fa fa-file-word-o"></i> Registrasi Proposal</a></li>
						<?php
						}
						?>
						
						<?php
						if( ($this->session->userdata('ses_akses_lvl2_53') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
						{
						?>
							<li id="510_transaksi_disposisi">
								<a href="<?=base_url()?>gl-admin-disposisi">
									<i class="fa fa-file-archive-o"></i> 
									<span> Disposisi Ajuan </span>
									<span class="pull-right-container">
									  <small id="sb_pemeriksaan_dokter" class="label pull-right bg-red">0</small>
									</span>
								</a>
							</li>
							
						<?php
						}
						?>
						
						<?php
						if( ($this->session->userdata('ses_akses_lvl2_54') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
						{
						?>
							<li id="510_transaksi_disposisi_acc">
								<a href="<?=base_url()?>gl-admin-disposisi-acc">
									<i class="fa fa-file-archive-o"></i> 
									<span> ACC Pengajuan </span>
									<span class="pull-right-container">
									  <small id="sb_pembayaran" class="label pull-right bg-red">0</small>
									</span>
								</a>
							</li>
							
						<?php
						}
						?>
						
						<?php
						if( ($this->session->userdata('ses_akses_lvl2_55') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
						{
						?>
							<li id="510_transaksi_disposisi_penyerahan">
								<a href="<?=base_url()?>gl-admin-disposisi-penyerahan">
									<i class="fa fa-file-archive-o"></i> 
									<span> Penyerahan Ajuan </span>
									<span class="pull-right-container">
									  <small id="sb_perawatan_lanjut" class="label pull-right bg-red">0</small>
									</span>
								</a>
							</li>
							
						<?php
						}
						?>
				</ul>
			</li>
			<?php
			}
			?>
			
			
			<?php
			if( ($this->session->userdata('ses_akses_lvl1_6') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
			{
			?>
			<li class="treeview" id="6_laporan">
				<a href="#">
					<i class="fa fa-file-text"></i> <span>Laporan</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
					
				</a>
			  <ul class="treeview-menu">
						<?php
						if( ($this->session->userdata('ses_akses_lvl2_61') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
						{
						?>
							<li id="61_laporan_ajuan"><a href="<?=base_url()?>gl-admin-view-lap-ajuan"><i class="fa fa-files-o"></i> Pelayanan Ajuan	</a></li>
							
							<li id="62_laporan_detail"><a href="<?=base_url()?>gl-admin-view-lap-detail"><i class="fa fa-files-o"></i> Laporan Detail	</a></li>
							
							<li id="63_laporan_pengloaan_surat"><a href="<?=base_url()?>gl-admin-view-lap-pengelolaan-surat"><i class="fa fa-files-o"></i> Laporan Surat	</a></li>
							
						<?php
						}
						?>
				</ul>
			</li>
			<?php
			}
			?>
			
			<!-- CEK AKSES LEVEL 1 MAIN 3 PENGATURAN APLIKASI -->
				<?php
				//if( ($this->session->userdata('ses_akses_lvl1_3') > 0) or ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
				if( ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi') )
				{
				?>
					<li id="3_pengaturan_aplikasi" class="treeview">
						<a href="<?php echo base_url();?>gl-admin-pengaturan-aplikasi"><i class="fa fa-wrench"></i> <span>Pengaturan Aplikasi</span></a>
					</li>
				<?php
				}
				?>
			<!-- CEK AKSES LEVEL 1 MAIN 3 PENGATURAN APLIKASI -->
			
			
			
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>