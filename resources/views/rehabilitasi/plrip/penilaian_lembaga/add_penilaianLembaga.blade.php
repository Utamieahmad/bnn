@extends('layouts.base_layout')
@section('title', 'Dir PLRIP : Tambah Penilaian Lembaga Rehabilitasi ')

@section('content')
    <div class="right_col" role="main">
        <div class="m-t-40">
            <div class="page-title">
                <div class="">
                    {!! (isset($breadcrumps) ? $breadcrumps : "" ) !!}
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="title_right">
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Form Tambah Penilaian Lembaga Rehabilitasi Direktorat Pascarehabilitasi</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                        @if(session('status'))
                            @php
                                $session= session('status');
                            @endphp
                            <div class="alert alert-{{$session['status']}}">
                                {{ $session['message'] }}
                            </div>
                         @endif
                        <form action="{{route('save_penilaian_lembaga_plrip')}}" class="form-horizontal" id="frm_add" method="post"  autocomplete="on">
                            <div class="form-body">
                                {{csrf_field()}}
                                <div class="form-group">
                                    <label for="nama" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Lembaga</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="" id="nama" name="nama" type="text" class="form-control" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="alamat" class="col-md-3 col-sm-3 col-xs-12 control-label">Alamat Lembaga</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="" id="alamat" name="alamat" type="text" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="alamat_kodepos" class="col-md-3 col-sm-3 col-xs-12 control-label">Kodepos</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="" id="alamat_kodepos" name="alamat_kodepos" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Alamat Kab/Kota</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="form-control select2" name="alamat_idkabkota">
                                           <option value="">Pilih Kabupaten</value>
                                            {!! dropdownLokasiKabupaten() !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <div class="col-sm-offset-1 col-md-offset-1 col-sm-11 col-xs-11 col-md-11">
                                        <h5>Penilaian Lembaga (Pilih Salah Satu Untuk Setiap Pertanyaan)</h5>
                                        <h5>4.&nbsp;&nbsp;&nbsp;Organisasi</h5>
                                        <label for="nilai_04_a">a.&nbsp;&nbsp;&nbsp;Adanya Struktur Organisasi</label>
                                        <div class="clearfix"></div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio radio m-l-10" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_04_a'] as $ka => $va)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$ka}}" name="nilai_04_a" >
                                                            <span>{{$va}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <label for="nilai_04_b m-t-10">b.&nbsp;&nbsp;&nbsp;Adanya laporan kegiatan layanan secara rutin</label>
                                        <div class="clearfix"></div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_04_b'] as $kb => $vb)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$kb}}" name="nilai_04_b">
                                                            <span>{{$vb}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <label for="nilai_04_c m-t-10">c.&nbsp;&nbsp;&nbsp;Adanya pencatatan dan pelaporan keuangan</label>
                                        <div class="clearfix"></div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_04_c'] as $kc => $vc)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$kc}}" name="nilai_04_c">
                                                            <span>{{$vc}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                

                                <div class="form-group">
                                    <div class="col-sm-offset-1 col-md-offset-1 col-sm-11 col-xs-11 col-md-11">
                                        <h5>5.&nbsp;&nbsp;&nbsp;SDM</h5>
                                        <label for="nilai_05_a">a.&nbsp;&nbsp;&nbsp;Penanggung Jawab Program dengan pengetahuan masalah adiksi Narkoba &amp; Rehabilitasi</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_05_a'] as $k5a => $v5c)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k5a}}" name="nilai_05_a">
                                                            <span>{{$v5c}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif

                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        
                                        <label for="nilai_05_b">b. Petugas Layanan terlatih adiksi</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio radio m-l-10" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_05_b'] as $k5b => $v5b)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k5b}}" name="nilai_05_b">
                                                            <span>{{$v5b}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif

                                            </div>
                                        </div>
                                        <div class="clearfix"></div>

                                        <label for="nilai_05_c" class="">c. Petugas penunjang</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_05_c'] as $k5c => $v5c)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k5c}}" name="nilai_05_c">
                                                            <span>{{$v5c}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        
                                        <label for="nilai_05_d">d. Surat Keputusan/perintah bertugas dari pimpinan terhadap petugas pelaksana</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio m-l-10 radio" id='buttons'>
                                                 @if($penilaian)
                                                    @foreach($penilaian['nilai_05_d'] as $k5d => $v5d)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k5d}}" name="nilai_05_d">
                                                            <span>{{$v5d}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif

                                            </div>
                                        </div>
                                        <div class="clearfix"></div>

                                        <label for="nilai_05_e">e. Petugas memiliki kompetensi asesmen</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_05_e'] as $k5e => $v5e)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k5e}}" name="nilai_05_e">
                                                            <span>{{$v5e}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                                
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        
                                        <label for="nilai_05_f">f. Petugas memiliki kompetensi terkait adiksi lainnya</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_05_f'] as $k5f => $v5f)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k5f}}" name="nilai_05_f">
                                                            <span>{{$v5f}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                                

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-1 col-md-offset-1 col-sm-11 col-xs-11 col-md-11">
                                        <h5>6.&nbsp;&nbsp;&nbsp;Perangkat Program</h5>
                                        <label for="nilai_06_a">a. Adanya jadwal kegiatan harian tertulis</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio radio m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_06_a'] as $k6a => $v6a)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k6a}}" name="nilai_06_a" >
                                                            <span>{{$v6a}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                                
                                            </div>
                                        </div>

                                        <label for="nilai_06_b">b. Adanya pencatatan dan pelaporan data klien</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_06_b'] as $k6b => $v6b)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k6b}}" name="nilai_06_b" >
                                                            <span>{{$v6b}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                               
                                            </div>
                                        </div>

                                        <label for="nilai_06_c">c. Khusus untuk lembaga yang menerima klien rujukan BNN/P/K: Adanya berita acara serah terima calon peserta program rehabilitasi</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_06_c'] as $k6c => $v6c)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k6c}}" name="nilai_06_c" >
                                                            <span>{{$v6c}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                                
                                            </div>
                                        </div>
                               
                                        <label for="nilai_06_d">d. Adanya lembar pernyataan dan persetujuan mengikuti program rehabilitasi untuk klien dan orang tua/keluarga klien</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_06_d'] as $k6d => $v6d)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k6d}}" name="nilai_06_d" >
                                                            <span>{{$v6d}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                                

                                            </div>
                                        </div>

                                        <label for="nilai_06_e">e. Adanya formulir yang dibutuhkan dalam layanan rehabilitasi</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_06_e'] as $k6e => $v6e)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k6e}}" name="nilai_06_e">
                                                            <span>{{$v6e}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>

                                        <label for="nilai_06_f">f. Adanya standar prosedur operasional pelaksanaan program rehabilitasi di lembaga</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_06_f'] as $k6f => $v6f)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k6f}}" name="nilai_06_f">
                                                            <span>{{$v6f}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>

                                        <label for="nilai_06_g">g. Adanya menu makanan yang memenuhi persyaratan gizi seimbang yang disesuaikan dengan kebutuhan klien</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_06_g'] as $k6g => $v6g)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k6g}}" name="nilai_06_g" >
                                                            <span>{{$v6g}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-1 col-md-offset-1 col-sm-11 col-xs-11 col-md-11">
                                        <h5>7.&nbsp;&nbsp;&nbsp;Program Layanan (Umum)</h5>

                                        <label for="nilai_07_a">a. Terdapat kegiatan Rekreasional</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_07_a'] as $k7a => $v7a)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k7a}}" name="nilai_07_a" >
                                                            <span>{{$v7a}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            

                                            </div>
                                        </div>

                                        <label for="nilai_07_b">b. Adanya rencana terapi atau rencana intervensi bagi klien yang diperbaharui sesuai perkembangan klien</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_07_b'] as $k7b => $v7b)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k7b}}" name="nilai_07_b">
                                                            <span>{{$v7b}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>

                                        <label for="nilai_07_c">c. Adanya pelaksanaan tes urin</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_07_c'] as $k7c => $v7c)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k7c}}" name="nilai_07_c">
                                                            <span>{{$v7c}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <label for="nilai_07_d">d. Adanya pemeriksaan kesehatan sesuai kebutuhan klien</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio radio m-l-10" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_07_d'] as $k7d => $v7d)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k7d}}" name="nilai_07_d">
                                                            <span>{{$v7d}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>

                                        <label for="nilai_07_e">e. Adanya Seminar / Psikoedukasi pencegahan kekambuhan dan pencegahan dampak buruk</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_07_e'] as $k7e => $v7e)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k7e}}" name="nilai_07_e">
                                                            <span>{{$v7e}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                                
                                            </div>
                                        </div>

                                        <label for="nilai_07_f">f. Pelaksanaan layanan dengan pendekatan individual</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                             @if($penilaian)
                                                    @foreach($penilaian['nilai_07_f'] as $k7f => $v7f)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k7f}}" name="nilai_07_f">
                                                            <span>{{$v7f}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <label for="nilai_07_g">g. Pelaksanaan layanan dengan pendekatan kelompok / keluarga</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_07_g'] as $k7g => $v7g)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k7g}}" name="nilai_07_g">
                                                            <span>{{$v7g}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                                

                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <label for="nilai_07_h">h. Pelayanan medis sesuai kebutuhan klien</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_07_h'] as $k7h => $v7h)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k7h}}" name="nilai_07_h">
                                                            <span>{{$v7h}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                
                                        <label for="nilai_07_i">i. Adanya jejaring (sistem rujukan) dengan layanan kesehatan dan layanan sosial lainnya sesuai kebutuhan klien</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_07_i'] as $k7i => $v7i)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k7i}}" name="nilai_07_i">
                                                            <span>{{$v7i}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <div class="col-sm-offset-1 col-md-offset-1 col-sm-11 col-xs-11 col-md-11">
                                        <h5>8. Perangkat Layanan (Khusus Rawat Inap)</h5>

                                        <label for="nilai_08_a">a. Terdapat kegiatan Rekreasional</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_08_a'] as $k8a => $v8a)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k8a}}" name="nilai_08_a">
                                                            <span>{{$v8a}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                                
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <label for="nilai_08_b">b. Adanya kegiatan keagamaan/rohani sesuai kebutuhan klien</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_08_b'] as $k8b => $v8b)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k8b}}" name="nilai_08_b">
                                                            <span>{{$v8b}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <label for="nilai_08_c">c. Adanya Kegiatan Vokasional</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_08_c'] as $k8c => $v8c)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k8c}}" name="nilai_08_c">
                                                            <span>{{$v8c}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <label for="nilai_08_d">d. Penyelenggaraan kegiatan berlangsung secara teratur sesuai jadwal tertulis</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_08_d'] as $k8d => $v8d)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k8d}}" name="nilai_08_d">
                                                            <span>{{$v8d}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                    
                                <div class="form-group">
                                    <div class="col-sm-offset-1 col-md-offset-1 col-sm-11 col-xs-11 col-md-11">
                                        <h5>9. Sarana dan Prasarana (Umum)</h5>
                                        <div class="clearfix"></div>
                                        <label for="nilai_09_a">a. Adanya ruang konseling</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_09_a'] as $k9a => $v9a)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k9a}}" name="nilai_09_a">
                                                            <span>{{$v9a}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>

                                        <label for="nilai_09_b">b. Adanya kamar mandi/toilet</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_09_b'] as $k9b => $v9b)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k9b}}" name="nilai_09_b">
                                                            <span>{{$v9b}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>

                                        <label for="nilai_09_c">c. Tersedia peralatan P3K dan alat kesehatan minimal serta obat-obatan</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_09_c'] as $k9c => $v9c)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k9c}}" name="nilai_09_c">
                                                            <span>{{$v9c}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif

                                            </div>
                                        </div>
                                        <div class="clearfix"></div>

                                        <label for="nilai_09_d">d. Sarana prasarana bangunan memenuhi standar kesehatan</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_09_d'] as $k9d => $v9d)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k9d}}" name="nilai_09_d">
                                                            <span>{{$v9d}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                        
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <div class="col-sm-offset-1 col-md-offset-1 col-sm-11 col-xs-11 col-md-11">
                                        <h5>10. Sarana dan Prasarana (Khusus Rawat Inap)</h5>
                                        <label for="nilai_10_a">a. Adanya ruang administrasi</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_10_a'] as $k10a => $v10a)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k10a}}" name="nilai_10_a">
                                                            <span>{{$v10a}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <label for="nilai_10_b">b. Adanya ruang periksa/klinik/poliklinik</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_10_b'] as $k10b => $v10b)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k10b}}" name="nilai_10_b">
                                                            <span>{{$v10b}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <label for="nilai_10_c">c. Adanya ruang rawat inap (dapat berupa asrama/barak)</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_10_c'] as $k10c => $v10c)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k10c}}" name="nilai_10_c">
                                                            <span>{{$v10c}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <label for="nilai_10_d">d. Adanya ruang makan</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_10_d'] as $k10d => $v10d)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k10d}}" name="nilai_10_d">
                                                            <span>{{$v10d}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <label for="nilai_10_e">e. Adanya ruang instruktur dan konselor</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_10_e'] as $k10e => $v10e)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k10e}}" name="nilai_10_e" >
                                                            <span>{{$v10e}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <label for="nilai_10_f">f. Adanya Ruangan Kelas</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_10_f'] as $k10f => $v10f)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k10f}}" name="nilai_10_f">
                                                            <span>{{$v10f}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <label for="nilai_10_g">g. Adanya ruang aktivitas</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_10_g'] as $k10g => $v10g)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k10g}}" name="nilai_10_g">
                                                            <span>{{$v10g}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <label for="nilai_10_h">h. Adanya fasilitas olah raga</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_10_h'] as $k10h => $v10h)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k10h}}" name="nilai_10_h" >
                                                            <span>{{$v10h}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <label for="nilai_10_i">i. Adanya tempat ibadah</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_10_i'] as $k10i => $v10i)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k10i}}" name="nilai_10_i">
                                                            <span>{{$v10i}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <label for="nilai_10_j">j. Adanya Dapur</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_10_j'] as $k10j => $v10j)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k10j}}" name="nilai_10_j" >
                                                            <span>{{$v10j}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <label for="nilai_10_k">k. Sarana prasarana bangunan memenuhi standar keamanan</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_10_k'] as $k10k => $v10k)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k10k}}" name="nilai_10_k">
                                                            <span>{{$v10j}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                

                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <div class="col-sm-offset-1 col-md-offset-1 col-sm-11 col-xs-11 col-md-11">
                                        <h5>11. Monitoring dan Evaluasi</h5>
                                        <label for="nilai_11_a">a. Adanya monitoring evaluasi perkembangan klien secara berkala</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_11_a'] as $k11a => $v11a)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k11a}}" name="nilai_11_a">
                                                            <span>{{$v11a}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>

                                        <label for="nilai_11_b">b. Hasil evaluasi perkembangaan klien dikomunikasikan pada klien</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_11_b'] as $k11b => $v11b)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k11b}}" name="nilai_11_b">
                                                            <span>{{$v11b}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="clearfix"></div>
                                        <label for="nilai_11_c">c. Adanya evaluasi kepuasan klien</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_11_c'] as $k11c => $v11c)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k11c}}" name="nilai_11_c">
                                                            <span>{{$v11c}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>

                                        <label for="nilai_11_d">d. Adanya monitoring dan evaluasi terhadap pelaksanaan program rehabilitasi</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_11_d'] as $k11d => $v11d)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k11d}}" name="nilai_11_d">
                                                            <span>{{$v11d}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>

                                        <label for="nilai_11_e">e. Adanya tindak lanjut dari hasil evaluasi pelaksanaan program rehabilitasi</label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="mt-rad io-list m-l-10 radio" id='buttons'>
                                                @if($penilaian)
                                                    @foreach($penilaian['nilai_11_e'] as $k11e => $v11e)
                                                        <label class="mt-radio col-md-4 col-xs-12 col-sm-4 col-xs-12 col-sm-4"> 
                                                            <input type="radio" value="{{$k11e}}" name="nilai_11_e">
                                                            <span>{{$v11e}}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"> </div>
                                <div class="x_title col-sm-offset-1 col-md-offset-1 col-sm-11 col-xs-11 col-md-11">
                                    <h5>Penilaian Kualitatif</h5>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label for="jumlah_pecandu_yang_direhab" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Pecandu yang direhabilitasi sejak awal program</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="" id="jumlah_pecandu_yang_direhab" name="jumlah_pecandu_yang_direhab" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="jumlah_pecandu_selesai" class="col-md-3 col-sm-3 col-xs-12 control-label">Jumlah Pecandu yang Selesai Program</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="" id="jumlah_pecandu_selesai" name="jumlah_pecandu_selesai" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                                    </div>
                                </div>

                                <div class="x_title col-sm-offset-1 col-md-offset-1 col-sm-11 col-xs-11 col-md-11">
                                    <h5>Jumlah Pecandu yang tidak selesai program</h5>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="form-group">
                                    <label for="jumlah_pecandu_tidakselesai_kabur" class="col-md-3 col-sm-3 col-xs-12 control-label">a. Kabur</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="" id="jumlah_pecandu_tidakselesai_kabur" name="jumlah_pecandu_tidakselesai_kabur" type="text" class="form-control"  onkeydown="numeric_only(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="jumlah_pecandu_tidakselesai_pulang" class="col-md-3 col-sm-3 col-xs-12 control-label">b. Pulang Paksa</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="" id="jumlah_pecandu_tidakselesai_pulang" name="jumlah_pecandu_tidakselesai_pulang" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="jumlah_pecandu_tidakselesai_dirujuk" class="col-md-3 col-sm-3 col-xs-12 control-label">c. Dirujuk</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="" id="jumlah_pecandu_tidakselesai_dirujuk" name="jumlah_pecandu_tidakselesai_dirujuk" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                                    </div>
                                </div>

                                <div class="x_title col-sm-offset-1 col-md-offset-1 col-sm-11 col-xs-11 col-md-11">
                                    <h5>Pecandu yang direhabilitasi berasal dari</h5>
                                </div>
                                <div class="clearfix"></div>

                                <div class="form-group">
                                    <label for="jumlah_asalpecandu_razia" class="col-md-3 col-sm-3 col-xs-12 control-label">a. Hasil Razia/Penjangkauan dari BNNP/K</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="" id="jumlah_asalpecandu_razia" name="jumlah_asalpecandu_razia" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="jumlah_asalpecandu_rujukanlembaga" class="col-md-3 col-sm-3 col-xs-12 control-label">b. Rujukan dari LSM/Lembaga Rehabilitasi Lainnya</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="" id="jumlah_asalpecandu_rujukanlembaga" name="jumlah_asalpecandu_rujukanlembaga" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="jumlah_asalpecandu_datangsendiri" class="col-md-3 col-sm-3 col-xs-12 control-label">c. Klien Datang Sendiri</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="" id="jumlah_asalpecandu_datangsendiri" name="jumlah_asalpecandu_datangsendiri" type="text" class="form-control" onkeydown="numeric_only(event,this)">
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>    

                            <div class="form-actions fluid">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3  col-sm-offset-3  col-xs-offset-3">
                                        <button type="submit" class="btn btn-success">SIMPAN</button>
                                        <a href="{{route('penilaian_lembaga_plrip')}}" class="btn btn-primary" type="button">BATAL</a>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection