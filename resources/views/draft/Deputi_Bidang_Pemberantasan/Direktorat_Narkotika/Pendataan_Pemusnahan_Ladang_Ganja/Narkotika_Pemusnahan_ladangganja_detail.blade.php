@extends('Layouts.layoutsGlobal')

@section('content')
<div class="right_col" role="main">
          <div class="m-t-40">
            <div class="page-title">
              <div class="">
                  <ul class="page-breadcrumb breadcrumb">
                      <li>
                          Home
                      </li>
                      <li>
                          Direktorat Narkotika
                      </li>
                      <li class="active">
                          Pendataan LKN (Kasus, tersangka, penerapan pasal, dan barang bukti)
                      </li>
                  </ul>
                <!--<h3>Fixed Sidebar <small> Just add class <strong>menu_fixed</strong></small></h3>-->
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Data Kasus</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li class="">
                            <a href="#" class="btn btn-lg btn-round btn-danger">
                                <i class="fa fa-trash"></i> Hapus
                            </a>
                        </li>
                        <li class="">
                            <a href="{{route('Narkotika_Pemusnahan_ladangganja_edit')}}" class="btn btn-lg btn-round btn-primary">
                                <i class="fa fa-pencil"></i> Edit Data
                            </a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content ">
                     <div class="col-md-12 col-sm-12 col-xs-12 p-t-10 p-b-20">
                         <div class="p-10" style="border: 1px solid;">
                             <div class="">
                                 <h4>Personal Info</h4>
                             </div>
                             <div class="ln_solid"></div>
                             <div class="">
                                 <table class="table table-hover table-condensed">
                                  <tbody>
                                    <tr>
                                      <th scope="row">Tanggal Masuk</th>
                                      <td>26 September 2017</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Tempat Rehab</th>
                                      <td>A' Bulo Sibatang Makassar - PROPINSI SULAWESI SELATAN</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Sumber Pasien</th>
                                      <td>Proses Hukum</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">No. Rekam Medis</th>
                                      <td>A0123456789</td>
                                    </tr>
                                  </tbody>
                                </table>
                             </div>
                         </div>
                     </div>
                      <div class="col-md-6 col-sm-6 col-xs-12 p-t-10 p-b-20">
                          <div class="p-10" style="border: 1px solid;">
                             <div class="">
                                 <h4>General Info</h4>
                             </div>
                             <div class="ln_solid"></div>
                             <div class="">
                                 <table class="table table-hover table-condensed">
                                  <tbody>
                                    <tr>
                                      <th scope="row">Nama Klien</th>
                                      <td>Fulan</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Jenis Identitas</th>
                                      <td>KTP</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">No. Identitas</th>
                                      <td>1234567890</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Jenis Kelamin</th>
                                      <td>LAKI-LAKI</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Alamat</th>
                                      <td>Jl. M.T. Haryono No. 11 Cawang, Jakarta Timur</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Kodepos</th>
                                      <td>123456</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Tempat Lahir</th>
                                      <td>Jakarta</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Tanggal Lahir</th>
                                      <td>1 September 1990</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Umur</th>
                                      <td>27</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Pendidikan Akhir</th>
                                      <td>SLTA</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Pekerjaan</th>
                                      <td>Karyawan Swasta</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Warga Negara</th>
                                      <td>Indonesia</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Negara Asal</th>
                                      <td>Indonesia</td>
                                    </tr>
                                  </tbody>
                                </table>
                             </div>
                          </div>
                     </div>

                     <div class="col-md-6 col-sm-6 col-xs-12 p-t-10 p-b-20">
                         <div class="p-10" style="border: 1px solid;">
                             <div class="">
                                <h4>Medical Info</h4>
                             </div>
                             <div class="ln_solid"></div>
                             <div class="">
                                 <table class="table table-hover table-condensed">
                                  <tbody>
                                    <tr>
                                      <th scope="row">Status Rawat</th>
                                      <td>Belum Pernah</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Tanggal Keluar</th>
                                      <td>KTP</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Lama Rehabilitasi</th>
                                      <td>1234567890</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Proses Rawat</th>
                                      <td>LAKI-LAKI</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Penyakit Penyerta</th>
                                      <td>Jl. M.T. Haryono No. 11 Cawang, Jakarta Timur</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Narkoba yang digunakan Pertama Kali</th>
                                      <td>123456</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Usia Pertama Kali Pakai Narkoba</th>
                                      <td>Jakarta</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Narkoba Utama yang Digunakan Satu Tahun Terakhir</th>
                                      <td>1 September 1990</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Cara Pakai Narkoba Utama Satu Tahun Terakhir</th>
                                      <td>27</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Jumlah Uang Klaim Yang Diterima</th>
                                      <td>SLTA</td>
                                    </tr>

                                  </tbody>
                                </table>
                             </div>
                         </div>
                     </div>

                  </div>
                </div>
              </div>
            </div>



          </div>
        </div>
@endsection
