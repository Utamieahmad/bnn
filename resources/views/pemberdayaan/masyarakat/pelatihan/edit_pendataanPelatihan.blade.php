
@extends('layouts.base_layout')
@section('title', 'Ubah Data Bimbingan Teknis')

@section('content')
    <div class="right_col withAnggaran" role="main">
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
                    <h2>Form Ubah Data Bimbingan Teknis Narkoba Direktorat Peran Serta Masyarakat</h2>
                    <div class="clearfix"></div>
                </div>
                    <div class="x_content">
                    <br />
                     @if (session('status'))
                            @php
                                $session= session('status');
                            @endphp
                            <div class="alert alert-{{$session['status']}}">
                                {{ $session['message'] }}
                            </div>
                        @endif
                        <form action="{{URL('/pemberdayaan/dir_masyarakat/update_pendataan_pelatihan')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{$id}}">
                            <div class="form-body">

                            <div class="form-group">
                                <label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Pelaksanaan</label>
                                <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                                  @if($data_detail['data']['tgl_pelaksanaan'])
                                  <input type='text' name="tgl_pelaksanaan" value="{{ \Carbon\Carbon::parse($data_detail['data']['tgl_pelaksanaan'] )->format('d/m/Y') }}" class="form-control" required/>
                                  @else
                                  <input type="text" name="tgl_pelaksanaan" value="" class="form-control" required/>
                                  @endif
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="pelaksana" class="col-md-3 col-sm-3 col-xs-12 control-label">Pelaksana</label>
                                <input type="hidden" name="id_pelaksana" class="id_pelaksana" value="{{$data_detail['data']['idpelaksana']}}"/>
                                <div class="col-md-6">
                                    <select name="idpelaksana" id="idpelaksana" class="form-control select2 selectPelaksana" tabindex="-1" aria-hidden="true" required>
                                      {{-- <option value="">-- Pilih Pelaksana --</option> --}}
                                      @foreach($instansi as $in)
                                      <option value="{{$in['id_instansi']}}" {{($in['id_instansi'] == $data_detail['data']['idpelaksana']) ? 'selected="selected"':""}} >{{$in['nm_instansi']}}</option>
                                      @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="sasaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Sasaran</label>
                                <div class="col-md-4">
                                    <div class="mt-radio-list radio" >
                                        @if(count($sasaran) > 0 )
                                            @foreach($sasaran as $s => $val)
                                                <label class="mt-radio col-md-9">
                                                    <input type="radio" value="{{$s}}" name="sasaran" {{($data_detail['data']['kodesasaran'] == $s) ? 'checked="checked"':""}}>
                                                    <span>{{$val}}</span>
                                                </label>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="instansi" class="col-md-3 control-label">Instansi</label>
                                <div class="col-md-8">
                                    <div class="mt-repeater">
                                        <div data-repeater-list="group-c">
                                          @foreach(json_decode($data_detail['data']['meta_instansi'],true) as $r1 => $c1)
                                            <div data-repeater-item="" class="mt-repeater-item">
                                                <div class="row mt-repeater-row">
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <label class="control-label">Nama Instansi</label>
                                                        <input name="group-c[{{$r1}}][list_nama_instansi]" value="{{$c1['list_nama_instansi']}}" type="text" class="form-control"> </div>
                                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                                        <label class="control-label">Jumlah Peserta</label>
                                                        <input name="group-c[{{$r1}}][list_jumlah_peserta]" value="{{$c1['list_jumlah_peserta']}}" type="text" class="form-control col-md-7 col-xs-12 numeric" onKeydown="numeric(event)"> </div>
                                                    <div class="col-md-1">
                                                        <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                                            <i class="fa fa-close"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                          @endforeach
                                        </div>
                                        <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                                            <i class="fa fa-plus"></i> Tambah Instansi</a>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="lokasi_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Kegiatan</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control select2 " name="lokasi_kegiatan_idkabkota">
                                      <option value="">-- Pilih Kabupaten --</option>
                                        @foreach($propkab['data'] as $keyGroup => $jenis )
                                        <optgroup label="{{$keyGroup}}">
                                          @foreach($jenis as $key => $val)
                                          <option value="{{$key}}" {{($key == $data_detail['data']['lokasi_kegiatan_idkabkota']) ? 'selected="selected"':""}} >{{$val}}</option>
                                          @endforeach
                                        </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="lokasi_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Alamat Lokasi Kegiatan </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input value="{{$data_detail['data']['lokasi_kegiatan']}}" id="lokasi_kegiatan" name="lokasi_kegiatan" type="text" class="form-control">
                                </div>
                            </div>



                            <div class="form-group">
                                <label for="panitia_monev" class="col-md-3 col-sm-3 col-xs-12 control-label">Panitia</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input value="{{$data_detail['data']['panitia_monev']}}" id="panitia_monev" name="panitia_monev" type="text" class="form-control">
                                </div>
                            </div>


                            <?php

                                $materi = $data_detail['data']['materi'];
                                $narasumber = $data_detail['data']['narasumber'];
                                $materi = json_decode($materi,true);
                                $narasumber = json_decode($narasumber,true);


                            ?>

                            <div class="form-group m-t-20">
                                <label for="" class="col-md-3 col-sm-3 col-xs-12 control-label">Materi</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="mt-repeater">
                                        <div data-repeater-list="materi">
                                            @if(is_array($materi))
                                                @if(count($materi)>0)
                                                    @for($x = 0; $x < count($materi);$x++)
                                                        <div data-repeater-item="" class="mt-repeater-item">
                                                            <div class="row mt-repeater-row">
                                                                <div class="col-md-5 col-sm-5 col-xs-12">
                                                                    <label class="control-label">Narasumber</label>
                                                                    <input name="group-materi[{{$x}}][narasumber]" value="{{$narasumber[$x]}}" type="text" class="form-control" > </div>
                                                                <div class="col-md-4 col-sm-4 col-xs-12">
                                                                    <div class="row">
                                                                        <label class="control-label">Judul Materi</label>
                                                                        <textarea name="group-materi[{{$x}}][materi]" class="form-control">{{$materi[$x]}} </textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-1 col-sm-1 col-xs-12">
                                                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                                                        <i class="fa fa-close"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endfor
                                                @endif
                                            @elseif ($data_detail['data']['materi'])
                                                <div data-repeater-item="" class="mt-repeater-item">
                                                    <div class="row mt-repeater-row">
                                                        <div class="col-md-5 col-sm-5 col-xs-12">
                                                            <label class="control-label">Narasumber cc</label>
                                                            <input name="group-materi[0][narasumber]" value="{{$data_detail['data']['narasumber']}}" type="text" class="form-control" > </div>
                                                        <div class="col-md-4 col-sm-4 col-xs-12">
                                                            <div class="row">
                                                                <label class="control-label">Judul Materi</label>
                                                                <textarea name="group-materi[0][materi]" class="form-control">{{$data_detail['data']['materi']}} </textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1 col-sm-1 col-xs-12">
                                                            <a href="javascript:;" data-repeater-delete="$data_detail['data']['materi']" class="btn btn-danger mt-repeater-delete">
                                                                <i class="fa fa-close"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div data-repeater-item="" class="mt-repeater-item">
                                                    <div class="row mt-repeater-row">
                                                        <div class="col-md-5 col-sm-5 col-xs-12">
                                                            <label class="control-label">Narasumber tt</label>
                                                            <input name="group-materi[0][narasumber]" value="" type="text" class="form-control" > </div>
                                                        <div class="col-md-4 col-sm-4 col-xs-12">
                                                            <div class="row">
                                                                <label class="control-label">Judul Materi</label>
                                                                <textarea name="group-materi[0][materi]" class="form-control"> </textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1 col-sm-1 col-xs-12">
                                                            <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                                                <i class="fa fa-close"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                                            <i class="fa fa-plus"></i> Tambah Materi</a>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="kodesumberanggaran" class="col-md-3 control-label">Sumber Anggaran</label>
                                <div class="col-md-4">
                                    <div class="mt-radio-list radio">
                                        @if(count($kode_anggaran))
                                            @php $i = 0;@endphp
                                            @foreach($kode_anggaran as $k => $kval)
                                                @php $i = $i+1; @endphp
                                                <label class="mt-radio col-md-9">
                                                    <input type="radio"  value="{{$k}}" name="kodesumberanggaran" {{($data_detail['data']['kodesumberanggaran'] == $k) ? 'checked="checked"':""}} id="anggaran{{$i}}">
                                                    <span>{{$kval}}</span>
                                                </label>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="PilihAnggaran" {{(($data_detail['data']['kodesumberanggaran'] == 'DIPA') ? '' : 'style=display:none;') }}>
                                <label for="sasaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Anggaran</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control select2 selectAnggaran" name="kd_anggaran" id="kd_anggaran" style="width:100%">
                                        <option value="">-- Pilih Anggaran --</option>
                                    </select>
                                </div>
                            </div>



                            <div class="form-group" id="DetailAnggaran"  {{ ($data_detail['data']['kodesumberanggaran'] == 'DIPA') ? : 'style=display:none'}} >
                                <label for="kodeSatker" class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
                                <input type="hidden" name="asatker_code" id="kodeSatker" value="">
                                <input type="hidden" name="kode_anggaran" id="kode_anggaran" value="{{( isset($data_anggaran['kode_anggaran']) ? $data_anggaran['kode_anggaran'] : '')}}">
                                <div class="col-md-6 col-sm-6 col-xs-12" id="hasil">
                                  <table class="table table-striped nowrap">
                                    @if($data_detail['data']['kodesumberanggaran'] == 'DIPA')
                                        @if($data_detail['data']['anggaran_id'] )
                                            @if(count($data_anggaran) > 0)
                                                @php $d = $data_anggaran; @endphp
                                                <tr><td>Kode Anggaran</td><td>{{$d['kode_anggaran']}}</td></tr>
                                                <tr><td>Sasaran</td><td>{{$d['sasaran']}}</td></tr>
                                                <tr><td>Target Output</td><td>{{$d['target_output']}}</td></tr>
                                                <tr><td>Satuan Output</td><td>{{$d['satuan_output']}}</td></tr>
                                                <tr><td>Tahun</td><td>{{$d['tahun']}}</td></tr>
                                            @endif
                                        @endif
                                    @endif
                                  </table>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="hasil_yang_dicapai" class="col-md-3 control-label">Hasil yang dicapai</label>
                                <div class="col-md-5">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="input-group input-large">
                                            <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                                                <i class="fa fa-file fileinput-exists"></i>&nbsp;
                                                <span class="fileinput-filename"> </span>
                                            </div>
                                            <span class="input-group-addon btn default btn-file">
                                                <span class="fileinput-new"> Pilih Berkas </span>
                                                <span class="fileinput-exists"> Ganti </span>
                                                <input type="file" name="file_upload"> </span>
                                            <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
                                        </div>
                                    </div>
                                    <span class="help-block" style="color:white">
                                        @if ($data_detail['data']['file_upload'])
                                            @if (\Storage::exists('PsmPelatihanPenggiat/'.$data_detail['data']['file_upload']))
                                                Lihat File : <a  target="_blank" class="link_file" href="{{\Storage::url('PsmPelatihanPenggiat/'.$data_detail['data']['file_upload'])}}">{{$data_detail['data']['file_upload']}}</a>
                                            @endif
                                        @endif

                                    </span>
                                </div>
                            </div>

                        </div>

                         <div class="form-actions fluid">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <button type="submit" class="btn btn-success">KIRIM</button>
    								<a href="{{route('pendataan_pelatihan')}}" class="btn btn-primary" type="button">BATAL</a>
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
