<div class="profile clearfix">
                    <div class="profile_pic">
                      @php
                        $img = "";
                        $photo = Request::session()->get('foto_pegawai');

                        if(isset($photo)){
                          $img = "data:image/png;base64,".$photo;
                        }else{
                          $img = '/assets/images/default-image.png';
                        }
                      @endphp
                        <img src="{{$img}}" alt="..." class="img-circle profile_img">
                    </div>
                    <div class="profile_info">
                        <!-- <span>Selamat Datang,</span> -->
                        <h2>{{ucwords(Request::session()->get('nama_pegawai'))}}</h2>
                        <span>{{ucwords(Request::session()->get('user_group'))}}</span>
                    </div>
                </div>

                <!-- /menu profile quick info -->

                <br />

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        <ul class="nav side-menu">
                          @php $menu = Session::get("menu"); @endphp
                            <li><a data-toggle="tooltip" data-placement="right" title="Beranda" href="{{URL('/')}}"><i class="fa fa-home" style="font-size:24px; color:#5C91FE"></i><span class="sm-side" style="vertical-align: super;"> Beranda </span><span class=""></span></a>
                            </li>
                            <li @php if(!in_array(111, $menu))  echo 'style="display:none;"'; @endphp><a data-toggle="tooltip" data-placement="right" title="Sekretariat Utama" href="javascript:;">
                              <!-- <i class="fa fa-microchip" > -->
                                <div class="fa"><img style="margin-left: -3px; width: 30px;" alt="Logo SIN-BNN" src="{{asset('assets/icon/sekretariat_utama.png')}}" class="img-responsive"></div>
                              </i><span class="sm-side" style="vertical-align: super;"> Sekretariat Utama </span><span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                  <li @php if(!in_array(112, $menu))  echo 'style="display:none;"'; @endphp>
                                    <a href="{{route('settama_umum')}}">Biro Umum</a>
                                  </li>
                                  <li @php if(!in_array(113, $menu))  echo 'style="display:none;"'; @endphp>
                                    <a href="{{route('settama_keuangan')}}">Biro Keuangan</a>
                                  </li>
                                  <li @php if(!in_array(114, $menu))  echo 'style="display:none;"'; @endphp>
                                    <a href="{{route('settama_kepegawaian')}}">Biro Kepegawaian dan Organisasi</a>
                                  </li>
                                  <li @php if(!in_array(115, $menu))  echo 'style="display:none;"'; @endphp>
                                    <a href="{{route('settama_perencanaan')}}">Biro Perencanaan</a>
                                  </li>
                                </ul>
                            </li>
                            <li @php if(!in_array(7, $menu))  echo 'style="display:none;"'; @endphp><a data-toggle="tooltip" data-placement="right" title="Inspektorat Utama" href="javascript:;">
                              <!-- <i class="fa fa-search"></i> -->
                              <div class="fa"><img style="margin-left: -3px; width: 30px;" alt="Logo SIN-BNN" src="{{asset('assets/icon/inspektorat_utama.png')}}" class="img-responsive"></div>
                              <span class="sm-side" style="vertical-align: super;"> Inspektorat Utama </span><span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li @php if(!in_array(117, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('irtama/audit/irtama_audit')}}">Laporan Hasil Audit</a></li>
                                    <li @php if(!in_array(118, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('irtama/ptl/irtama_ptl')}}">Pemantauan Tindak Lanjut</a></li>
                                    <li @php if(!in_array(119, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('irtama/riktu/irtama_riktu')}}">Audit dengan Tujuan Tertentu</a></li>
                                    <li @php if(!in_array(120, $menu))  echo 'style="display:none;"'; @endphp><a href="#">Reviu<span class="fa fa-chevron-down"></span></a>
                                      <ul class="nav child_menu">
                                          <li @php if(!in_array(126, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('irtama/reviu/irtama_lk')}}">Laporan Keuangan</a></li>
                                          <li @php if(!in_array(127, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('irtama/reviu/irtama_rkakl')}}">Rencana Kerja Anggaran Kementerian/Lembaga</a></li>
                                          <li @php if(!in_array(128, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('irtama/reviu/irtama_rkbmn')}}">Rencana Kebutuhan Barang Milik Negara</a></li>
                                          <li @php if(!in_array(129, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('irtama/reviu/irtama_lkip')}}">Laporan Kinerja Instansi Pemerintah</a></li>
                                      </ul>
                                    </li>
                                    <li @php if(!in_array(121, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('irtama/sosialisasi/irtama_sosialisasi')}}">Sosialisasi</a></li>
                                    <li @php if(!in_array(122, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('irtama/verifikasi/irtama_verifikasi')}}">Verifikasi</a></li>
                                    <li @php if(!in_array(123, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('irtama/sop/irtama_sop')}}">SOP dan Kebijakan</a></li>
                                    <li @php if(!in_array(124, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('irtama/penegakan/irtama_penegakan')}}">Penegakan Disiplin</a></li>
                                    <li @php if(!in_array(125, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('irtama/apel/irtama_apel')}}">Apel Senin &amp; Upacara Hari Besar Lainnya</a></li>
                                </ul>
                            </li>

                            <li @php if(!in_array(2, $menu))  echo 'style="display:none;"'; @endphp><a data-toggle="tooltip" data-placement="right" title="Pemberantasan" href="javascript:;">
                              <!-- <i class="fa fa-shield"></i> -->
                              <div class="fa"><img style="margin-left: -3px; width: 30px;" alt="Logo SIN-BNN" src="{{asset('assets/icon/pemberantasan.png')}}" class="img-responsive"></div>
                              <span class="sm-side" style="vertical-align: super;"> Pemberantasan </span><span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li @php if(!in_array(12, $menu))  echo 'style="display:none;"'; @endphp><a href="javascript:;">Direktorat Narkotika<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li @php if(!in_array(19, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pemberantasan/dir_narkotika/pendataan_lkn')}}">Pendataan LKN (Kasus, tersangka, penerapan pasal, dan barang bukti)</a></li>
                                            <li @php if(!in_array(20, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pemberantasan/dir_narkotika/pendataan_pemusnahan_ladangganja')}}">Pendataan Pemusnahan Ladang Tanaman Narkotika</a></li>
                                        </ul>
                                    </li>
                                    <li @php if(!in_array(13, $menu))  echo 'style="display:none;"'; @endphp><a href="javascript:;">Direktorat Psikotropika dan Prekursor<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li @php if(!in_array(21, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pemberantasan/dir_psikotropika/psi_pendataan_lkn')}}">Pendataan LKN (Kasus, tersangka, penerapan pasal, dan barang bukti)</a></li>
                                        </ul>
                                    </li>
                                    <li @php if(!in_array(14, $menu))  echo 'style="display:none;"'; @endphp><a href="javascript:;">Direktorat TPPU<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li @php if(!in_array(22, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pemberantasan/dir_tppu/pendataan_tppu')}}">Pendataan TPPU</a></li>
                                        </ul>
                                    </li>
                                    <li @php if(!in_array(15, $menu))  echo 'style="display:none;"'; @endphp><a href="javascript:;">Direktorat Intelijen<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li @php if(!in_array(23, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pemberantasan/dir_intelijen/pendataan_jaringan')}}">Pendataan Jaringan Narkoba yang Sudah Diungkap</a></li>
                                        </ul>
                                    </li>
                                    <li @php if(!in_array(16, $menu))  echo 'style="display:none;"'; @endphp><a href="javascript:;">Direktorat Wastahti<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li @php if(!in_array(24, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pemberantasan/dir_wastahti/pendataan_brgbukti')}}">Pendataan Pemusnahan Barang Bukti</a></li>
                                            <li @php if(!in_array(25, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pemberantasan/dir_wastahti/pendataan_tahanan')}}">Pendataan tahanan di BNN dan BNNP</a></li>
                                        </ul>
                                    </li>
                                    <li @php if(!in_array(17, $menu))  echo 'style="display:none;"'; @endphp><a href="javascript:;">Direktorat Penindakan dan Pengejaran<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li @php if(!in_array(26, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pemberantasan/dir_penindakan/pendataan_dpo')}}">Input Daftar Pencarian Orang (DPO)</a></li>
                                        </ul>
                                    </li>
                                    <li @php if(!in_array(18, $menu))  echo 'style="display:none;"'; @endphp><a href="javascript:;">Direktorat Interdiksi<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li @php if(!in_array(27, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pemberantasan/dir_interdiksi/pendataan_intdpo')}}">Pendataan LKN (Kasus, tersangka, penerapan pasal, dan barang bukti)</a></li>
                                        </ul>
                                    </li>
                                    <li @php if(!in_array(153, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pemberantasan/razia')}}">Razia</a>
                                    </li>
                                </ul>
                            </li>
                            <li @php if(!in_array(3, $menu))  echo 'style="display:none;"'; @endphp><a data-toggle="tooltip" data-placement="right" title="Rehabilitasi" href="javascript:;">
                              <!-- <i class="fa fa-heartbeat"></i> -->
                              <div class="fa"><img style="margin-left: -3px; width: 30px;" alt="Logo SIN-BNN" src="{{asset('assets/icon/rehabilitasi.png')}}" class="img-responsive"></div>
                              <span class="sm-side" style="vertical-align: super;"> Rehabilitasi </span><span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li @php if(!in_array(29, $menu))  echo 'style="display:none;"'; @endphp><a href="javascript:;">Direktorat PLRIP<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li @php if(!in_array(32, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('rehabilitasi/dir_plrip/informasi_lembaga_umum_plrip')}}">Informasi Umum Lembaga Rehabilitasi Instansi Pemerintah</a></li>
                                            <li @php if(!in_array(34, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('rehabilitasi/dir_plrip/dokumen_nspk_plrip')}}">Dokumen NSPK</a></li>
                                            <li @php if(!in_array(35, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('rehabilitasi/dir_plrip/kegiatan_pelatihan_plrip')}}">Kegiatan</a></li>
                                        </ul>
                                    </li>
                                    <li @php if(!in_array(30, $menu))  echo 'style="display:none;"'; @endphp><a href="javascript:;">Direktorat PLRKM<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li @php if(!in_array(37, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('rehabilitasi/dir_plrkm/informasi_lembaga_umum_plrkm')}}">Informasi Umum Lembaga Rehabilitasi Komponen Masyarakat</a></li>
                                            <li @php if(!in_array(39, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('rehabilitasi/dir_plrkm/dokumen_nspk_plrkm')}}">Dokumen NSPK</a></li>
                                            <li @php if(!in_array(40, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('rehabilitasi/dir_plrkm/kegiatan_pelatihan_plrkm')}}">Kegiatan</a></li>
                                        </ul>
                                    </li>
                                    <li @php if(!in_array(31, $menu))  echo 'style="display:none;"'; @endphp><a href="javascript:;">Direktorat Pascarehabilitasi<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li @php if(!in_array(45, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('rehabilitasi/dir_pasca/informasi_lembaga_umum_pascarehabilitasi')}}">Informasi Umum Lembaga</a></li>
                                            <li @php if(!in_array(46, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('rehabilitasi/dir_pasca/dokumen_nspk_pascarehabilitasi')}}">Dokumen NSPK</a></li>
                                            <li @php if(!in_array(47, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('rehabilitasi/dir_pasca/kegiatan_pelatihan_pascarehabilitasi')}}">Kegiatan</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li @php if(!in_array(4, $menu))  echo 'style="display:none;"'; @endphp><a data-toggle="tooltip" data-placement="right" title="Pencegahan" href="javascript:;">
                              <!-- <i class="fa fa-ban"></i> -->
                              <div class="fa"><img style="margin-left: -3px; width: 30px;" alt="Logo SIN-BNN" src="{{asset('assets/icon/pencegahan.png')}}" class="img-responsive"></div>
                              <span class="sm-side" style="vertical-align: super;"> Pencegahan </span><span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                  <li @php if(!in_array(49, $menu))  echo 'style="display:none;"'; @endphp><a href="javascript:;">Direktorat Advokasi<span class="fa fa-chevron-down"></span></a>
                                      <ul class="nav child_menu">
                                          <li @php if(!in_array(51, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pencegahan/dir_advokasi/pendataan_koordinasi')}}">Kegiatan Rapat Koordinasi</a></li>
                                          <li @php if(!in_array(52, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pencegahan/dir_advokasi/pendataan_jejaring')}}">Kegiatan Membangun Jejaring</a></li>
                                          <li @php if(!in_array(53, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pencegahan/dir_advokasi/pendataan_asistensi')}}">Kegiatan Asistensi</a></li>
                                          <li @php if(!in_array(55, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pencegahan/dir_advokasi/pendataan_intervensi')}}">Kegiatan Intervensi</a></li>
                                          <li @php if(!in_array(56, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pencegahan/dir_advokasi/pendataan_supervisi')}}">Kegiatan Supervisi</a></li>
                                          <li @php if(!in_array(57, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pencegahan/dir_advokasi/pendataan_monitoring')}}">Kegiatan Monitoring dan Evaluasi</a></li>
                                          <li @php if(!in_array(58, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pencegahan/dir_advokasi/pendataan_bimbingan')}}">Kegiatan Bimbingan Teknis</a></li>
                                          <li @php if(!in_array(59, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pencegahan/dir_advokasi/pendataan_sosialisasi')}}">Kegiatan KIE</a></li>
                                      </ul>
                                  </li>
                                  <li @php if(!in_array(50, $menu))  echo 'style="display:none;"'; @endphp><a href="javascript:;">Direktorat Diseminasi Informasi<span class="fa fa-chevron-down"></span></a>
                                      <ul class="nav child_menu">
                                          <li @php if(!in_array(60, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pencegahan/dir_diseminasi/pendataan_online')}}">Media Online</a></li>
                                          <li @php if(!in_array(61, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pencegahan/dir_diseminasi/pendataan_penyiaran')}}">Media Penyiaran</a></li>
                                          <li @php if(!in_array(62, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pencegahan/dir_diseminasi/pendataan_cetak')}}">Media Cetak</a></li>
                                          <li @php if(!in_array(63, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pencegahan/dir_diseminasi/pendataan_konvensional')}}">Media Konvensional</a></li>
                                      </ul>
                                  </li>
                                  <li @php if(!in_array(226, $menu))  echo 'style="display:none;"'; @endphp><a href="javascript:;">Deputi Cegah<span class="fa fa-chevron-down"></span></a>
                                      <ul class="nav child_menu">
                                          <li @php if(!in_array(227, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pencegahan/dep_cegah/data_aktivitas_sebaran')}}">Data Aktivitas Sebaran</a></li>
                                      </ul>
                                  </li>
                                </ul>
                            </li>
                            <li @php if(!in_array(5, $menu))  echo 'style="display:none;"'; @endphp><a data-toggle="tooltip" data-placement="right" title="Pemberdayaan Masyarakat" href="javascript:;">
                              <!-- <i class="fa fa-users"></i> -->
                              <div class="fa"><img style="margin-left: -3px; width: 30px;" alt="Logo SIN-BNN" src="{{asset('assets/icon/pemberdayaan_masyarakat.png')}}" class="img-responsive"></div>
                              <span class="sm-side" style="vertical-align: super;"> Pemberdayaan Masyarakat </span><span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                  <li @php if(!in_array(65, $menu))  echo 'style="display:none;"'; @endphp><a href="javascript:;">Direktorat Peran Serta Masyarakat<span class="fa fa-chevron-down"></span></a>
                                      <ul class="nav child_menu">
                                          <li @php if(!in_array(67, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pemberdayaan/dir_masyarakat/pendataan_tes_narkoba')}}">Tes Narkoba</a></li>
                                          <li @php if(!in_array(68, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pemberdayaan/dir_masyarakat/pendataan_anti_narkoba')}}">Pengembangan Kapasitas</a></li>
                                          <li @php if(!in_array(69, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pemberdayaan/dir_masyarakat/pendataan_pelatihan')}}">Bimbingan Teknis</a></li>
                                          <li @php if(!in_array(71, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pemberdayaan/dir_masyarakat/psm_supervisi')}}">Monitoring dan Evaluasi</a></li>
                                          <li @php if(!in_array(72, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pemberdayaan/dir_masyarakat/rapat_kerja_pemetaan')}}">Rapat Kerja Pemetaan</a></li>
                                      </ul>
                                  </li>
                                  <li @php if(!in_array(66, $menu))  echo 'style="display:none;"'; @endphp><a href="javascript:;">Direktorat Alternative Development<span class="fa fa-chevron-down"></span></a>
                                      <ul class="nav child_menu">
                                          <li @php if(!in_array(73, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pemberdayaan/dir_alternative/altdev_lahan_ganja')}}">Alih Fungsi Lahan Ganja</a></li>
                                          <li @php if(!in_array(74, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pemberdayaan/dir_alternative/altdev_alih_profesi')}}">Alih Jenis Profesi/Usaha</a></li>
                                          <li @php if(!in_array(75, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pemberdayaan/dir_alternative/altdev_kawasan_rawan')}}">Kawasan Rawan Narkoba</a></li>
                                          <li @php if(!in_array(76, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pemberdayaan/dir_alternative/altdev_monitoring')}}">Monitoring dan Evaluasi Kawasan Rawan Narkotika</a></li>
                                          <li @php if(!in_array(77, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('pemberdayaan/dir_alternative/altdev_sinergi')}}">Sinergi</a></li>
                                          <li @php if(!in_array(116, $menu))  echo 'style="display:none;"'; @endphp><a href="{{route('alv_rapat_kerja_pemetaan')}}">Rapat Kerja Pemetaan</a></li>
                                      </ul>
                                  </li>
                                </ul>
                            </li>
                            <li @php if(!in_array(6, $menu))  echo 'style="display:none;"'; @endphp><a data-toggle="tooltip" data-placement="right" title="Hukum dan Kerja Sama" href="javascript:;">
                              <!-- <i class="fa fa-balance-scale"></i> -->
                              <div class="fa"><img style="margin-left: -3px; width: 30px;" alt="Logo SIN-BNN" src="{{asset('assets/icon/hukum_kerjasama.png')}}" class="img-responsive"></div>
                              <span class="sm-side" style="vertical-align: super;"> Hukum dan Kerja Sama </span><span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                  <li @php if(!in_array(78, $menu))  echo 'style="display:none;"'; @endphp><a href="javascript:;">Direktorat Hukum<span class="fa fa-chevron-down"></span></a>
                                      <ul class="nav child_menu">
                                        <li @php if(!in_array(80, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('huker/dir_hukum/hukum_nonlitigasi')}}">Non Litigasi (Konsultasi)</a></li>
                                        <li @php if(!in_array(81, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('huker/dir_hukum/hukum_audiensi')}}">Audiensi (Konsultasi)</a></li>
                                        <li @php if(!in_array(82, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('huker/dir_hukum/hukum_pendampingan')}}">Pendampingan (Litigasi)</a></li>
                                        <li @php if(!in_array(83, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('huker/dir_hukum/hukum_prapradilan')}}">Pra Peradilan (Litigasi)</a></li>
                                        <li @php if(!in_array(84, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('huker/dir_hukum/hukum_perka')}}">Penyusunan Perka</a></li>
                                        <li @php if(!in_array(85, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('huker/dir_hukum/hukum_lainnya')}}">Kegiatan Lainnya</a></li>
                                      </ul>
                                  </li>
                                  <li @php if(!in_array(79, $menu))  echo 'style="display:none;"'; @endphp><a href="javascript:;">Direktorat Kerja Sama<span class="fa fa-chevron-down"></span></a>
                                      <ul class="nav child_menu">
                                          <li @php if(!in_array(88, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('huker/dir_kerjasama/kerjasama_bilateral')}}">Pertemuan</a></li>
                                          <li @php if(!in_array(89, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('huker/dir_kerjasama/kerjasama_kesepemahaman')}}">Nota Kesepahaman</a></li>
                                          <li @php if(!in_array(90, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('huker/dir_kerjasama/kerjasama_lainnya')}}">Kerja Sama Lainnya</a></li>
                                          <li @php if(!in_array(91, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('huker/dir_kerjasama/kerjasama_monev')}}">Monitoring dan Evaluasi Pelaksanaan Kerja Sama</a></li>
                                      </ul>
                                  </li>
                                </ul>
                            </li>
                            <li @php if(!in_array(109, $menu))  echo 'style="display:none;"'; @endphp>
                              <a data-toggle="tooltip" data-placement="right" title="Balai Besar" href="javascript:;">
                                <!-- <i class="fa fa-leanpub"></i> -->
                                <div class="fa"><img style="margin-left: -3px; width: 30px;" alt="Logo SIN-BNN" src="{{asset('assets/icon/balai_besar.png')}}" class="img-responsive"></div>
                                <span class="sm-side" style="vertical-align: super;"> Balai Besar </span><span class="fa fa-chevron-down"></span></a>
                               <ul class="nav child_menu">
                                    <li @php if(!in_array(110, $menu))  echo 'style="display:none;"'; @endphp><a href="{{route('data_magang')}}">Magang</a></li>
                                </ul>
                            </li>
                            <li @php if(!in_array(8, $menu))  echo 'style="display:none;"'; @endphp><a data-toggle="tooltip" data-placement="right" title="Balai Diklat" href="javascript:;">
                              <!-- <i class="fa fa-leanpub"></i> -->
                              <div class="fa"><img style="margin-left: -3px; width: 30px;" alt="Logo SIN-BNN" src="{{asset('assets/icon/balai_diklat.png')}}" class="img-responsive"></div>
                              <span class="sm-side" style="vertical-align: super;"> Balai Diklat </span><span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li @php if(!in_array(93, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('balai_diklat/pendidikan/pendidikan_pelatihan')}}">Pendidikan dan Pelatihan</a></li>
                                </ul>
                            </li>
                            <li @php if(!in_array(9, $menu))  echo 'style="display:none;"'; @endphp><a data-toggle="tooltip" data-placement="right" title="Balai Laboratorium Narkoba" href="javascript:;">
                              <!-- <i class="fa fa-flask"></i> -->
                              <div class="fa"><img style="margin-left: -3px; width: 30px;" alt="Logo SIN-BNN" src="{{asset('assets/icon/balai_laboratorium.png')}}" class="img-responsive"></div>
                              <span class="sm-side" style="vertical-align: super;"> Balai Laboratorium Narkoba </span><span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li @php if(!in_array(94, $menu))  echo 'style="display:none;"'; @endphp><a href="{{route('berkas_sampel')}}">Berkas Sampel</a></li>
                                </ul>
                            </li>
                            <li @php if(!in_array(10, $menu))  echo 'style="display:none;"'; @endphp><a data-toggle="tooltip" data-placement="right" title="Puslitdatin" href="javascript:;">
                              <!-- <i class="fa fa-microchip"></i> -->
                              <div class="fa"><img style="margin-left: -3px; width: 30px;" alt="Logo SIN-BNN" src="{{asset('assets/icon/puslitdatin.png')}}" class="img-responsive"></div>
                              <span class="sm-side" style="vertical-align: super;"> Puslitdatin </span><span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                  <li @php if(!in_array(95, $menu))  echo 'style="display:none;"'; @endphp><a href="javascript:;">Bidang Litbang<span class="fa fa-chevron-down"></span></a>
                                      <ul class="nav child_menu">
                                          <li @php if(!in_array(98, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('puslitdatin/bidang_litbang/survey')}}">Survey</a></li>
                                          <li @php if(!in_array(99, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('puslitdatin/bidang_litbang/survey_narkoba')}}">Survey Nasional Penyalahgunaan Narkoba</a></li>
                                          <li @php if(!in_array(100, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('puslitdatin/bidang_litbang/survey_narkoba_ketergantungan')}}">Survey Nasional Penyalahgunaan Narkoba Berdasarkan Tingkat Ketergantungan</a></li>
                                          <li @php if(!in_array(105, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('puslitdatin/bidang_litbang/riset_penyalahgunaan_narkoba')}}">Riset Operasional Penyalahgunaan Narkoba di Indonesia</a></li>
                                      </ul>
                                  </li>
                                  <li @php if(!in_array(96, $menu))  echo 'style="display:none;"'; @endphp><a href="javascript:;">Bidang TIK<span class="fa fa-chevron-down"></span></a>
                                      <ul class="nav child_menu">
                                          <li @php if(!in_array(106, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('puslitdatin/bidang_tik/pekerjaan_jaringan')}}">Pekerjaan Jaringan</a></li>
                                          <li @php if(!in_array(107, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('puslitdatin/bidang_tik/pengecekan_jaringan')}}">Pengecekan dan Pemeliharaan Jaringan LAN</a></li>
                                          <li @php if(!in_array(108, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('puslitdatin/bidang_tik/pengadaan_email')}}">Pembuatan Email BNN</a></li>
                                      </ul>
                                  </li>
                                  <li @php if(!in_array(97, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('puslitdatin/call_center')}}">Pusat Informasi (CC)<span class="fa"></span></a>
                                  </li>
                                </ul>
                            </li>
                            <li @php if(!in_array(11, $menu))  echo 'style="display:none;"'; @endphp><a data-toggle="tooltip" data-placement="right" title="Arahan Pimpinan" href="{{url('arahan/pimpinan/arahan_pimpinan')}}">
                              <!-- <i class="fa fa-id-badge"></i> -->
                              <div class="fa"><img style="margin-left: -3px; height: 30px;" alt="Logo SIN-BNN" src="{{asset('assets/icon/arahan_kepala_bnn.png')}}" class="img-responsive"></div>
                              <span class="sm-side" style="vertical-align: top;"> Arahan Pimpinan </span><span class=""></span></a>
                            <!--a style="vertical-align: top;"> Arahan Pimpinan </span><span class=""></span></a-->
                            </li>
                            <li @php if(!in_array(130, $menu))  echo 'style="display:none;"'; @endphp><a data-toggle="tooltip" data-placement="right" title="User Management" href="javascript:;">
                              <i class="fa fa-home" style="font-size:24px; color:#5C91FE"></i>
                              <!--div class="fa"><img style="margin-left: -1px; width: 20px;" alt="Logo SIN-BNN" src="{{asset('assets/icon/puslitdatin.png')}}" class="img-responsive"></div-->
                              <span class="sm-side" style="vertical-align: super;"> User Management </span><span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                  <li @php if(!in_array(132, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('user_management/user')}}">User<span class="fa"></span></a>
                                  </li>
                                  <li @php if(!in_array(133, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('user_management/group')}}">Role<span class="fa"></span></a>
                                  </li>
                                  <li @php if(!in_array(130, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('user_management/loginlog')}}">Login Log<span class="fa"></span></a>
                                  </li>
                                  <li @php if(!in_array(130, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('user_management/userlog')}}">Activity Log<span class="fa"></span></a>
                                  </li>
                                </ul>
                            </li>
                            <li @php if(!in_array(131, $menu))  echo 'style="display:none;"'; @endphp><a data-toggle="tooltip" data-placement="right" title="Master Data" href="javascript:;">
                              <i class="fa fa-home" style="font-size:24px; color:#5C91FE"></i>
                              <!--div class="fa"><img style="margin-left: -1px; width: 20px;" alt="Logo SIN-BNN" src="{{asset('assets/icon/puslitdatin.png')}}" class="img-responsive"></div-->
                              <span class="sm-side" style="vertical-align: super;"> Master Data </span><span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                  <li @php if(!in_array(134, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('master/instansi')}}">Master Instansi<span class="fa"></span></a>
                                  </li>
                                  <li @php if(!in_array(135, $menu))  echo 'style="display:none;"'; @endphp><a href="javascript:;">Master Wilayah<span class="fa fa-chevron-down"></span></a>
                                      <ul class="nav child_menu">
                                          <li @php if(!in_array(140, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('master/propinsi')}}">Master Propinsi</a></li>
                                          <li @php if(!in_array(141, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('master/kota')}}">Master Kota/Kabupaten</a></li>
                                          <!--li><a href="{{url('master/kecamatan')}}">Master Kecamatan</a></li-->
                                      </ul>
                                  </li>
                                  <li  @php if(!in_array(136, $menu))  echo 'style="display:none;"'; @endphp><a href="javascript:;">Master Kasus<span class="fa fa-chevron-down"></span></a>
                                      <ul class="nav child_menu">
                                          <li @php if(!in_array(142, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('master/jeniskasus')}}">Master Jenis Kasus</a></li>
                                          <li @php if(!in_array(143, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('master/jenisbarbuk')}}">Master Jenis Barang Bukti</a></li>
                                          <li @php if(!in_array(144, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('master/barangbukti')}}">Master Barang Bukti</a></li>
                                          <li @php if(!in_array(145, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('master/satuan')}}">Master Satuan</a></li>
                                      </ul>
                                  </li>
                                  <li @php if(!in_array(137, $menu))  echo 'style="display:none;"'; @endphp><a href="javascript:;">Master Media<span class="fa fa-chevron-down"></span></a>
                                      <ul class="nav child_menu">
                                          <li @php if(!in_array(146, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('master/mediaonline')}}">Master Media Online</a></li>
                                          <li @php if(!in_array(147, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('master/mediasosial')}}">Master Media Sosial</a></li>
                                          <li @php if(!in_array(148, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('master/mediacetak')}}">Master Media Cetak</a></li>
                                          <li @php if(!in_array(149, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('master/mediaruang')}}">Master Media Luar Ruang</a></li>
                                      </ul>
                                  </li>
                                  <li @php if(!in_array(138, $menu))  echo 'style="display:none;"'; @endphp><a href="javascript:;">Master Settama<span class="fa fa-chevron-down"></span></a>
                                      <ul class="nav child_menu">
                                          <li @php if(!in_array(150, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('master/bagian')}}">Master Bagian</a></li>
                                          <li @php if(!in_array(151, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('master/kegiatan')}}">Master Kegiatan</a></li>
                                      </ul>
                                  </li>
                                  <li @php if(!in_array(139, $menu))  echo 'style="display:none;"'; @endphp><a href="javascript:;">Master Dayamas<span class="fa fa-chevron-down"></span></a>
                                      <ul class="nav child_menu">
                                          <li @php if(!in_array(152, $menu))  echo 'style="display:none;"'; @endphp><a href="{{url('master/komoditi')}}">Master Komoditi</a></li>
                                      </ul>
                                  </li>
                                </ul>
                            </li>

                            <li @php if(!in_array(228, $menu))  echo 'style="display:none;"'; @endphp><a data-toggle="tooltip" data-placement="right" title="Keuangan" href="{{url('omspan/omspan')}}">
                              <!-- <i class="fa fa-id-badge"></i> -->
                              <div class="fa"><img style="margin-left: -3px; height: 24px; width: 18px;" alt="Logo SIN-BNN" src="{{asset('assets/icon/arahan_kepala_bnn.png')}}" class="img-responsive"></div>
                              <span class="sm-side" style="vertical-align: top;"> Keuangan </span><span class=""></span></a>
                            <!--a style="vertical-align: top;"> Arahan Pimpinan </span><span class=""></span></a-->
                            </li>


                        </ul>
                    </div>

                </div>
                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                <div class="sidebar-footer hidden-small">
                    <a data-toggle="tooltip" data-placement="top" title="Keluar Aplikasi" href="{{URL('/logout')}}">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>
                </div>
