@extends('layouts.base_layout')
@section('title', 'Ubah Data Kegiatan Monitoring Evaluasi')

@section('content')
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah2').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    function readURL3(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah3').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
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
                    <h2>Form Ubah Data Kegiatan Monitoring Evaluasi Direktorat Advokasi</h2>
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
    <form action="{{URL('/pencegahan/dir_advokasi/update_pendataan_monitoring')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
    {{ csrf_field() }}
    <input type="hidden" name="id" value="{{$id}}">
    <div class="form-body">

          <div class="form-group">
              <label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Pelaksanaan</label>
              <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                @if($data_detail['data']['tgl_pelaksanaan'] != "kosong")
                <input type='text' required name="tgl_pelaksanaan" value="{{ \Carbon\Carbon::parse($data_detail['data']['tgl_pelaksanaan'] )->format('d/m/Y') }}" class="form-control" />
                @else
                <input type="text" required name="tgl_pelaksanaan" value="" class="form-control" />
                @endif
                  <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div>
          </div>

          <div class="form-group">
              <label for="pelaksana" class="col-md-3 col-sm-3 col-xs-12 control-label">Pelaksana</label>
              <div class="col-md-6">
                  <select name="idpelaksana" required id="idpelaksana" class="form-control select2 selectPelaksana" tabindex="-1" aria-hidden="true">
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
                  <div class="mt-radio-list">
                      <label class="mt-radio col-md-9"> <input type="radio" {{($data_detail['data']['kodesasaran'] == 'INSTITUSI_PEMERINTAH') ? 'checked="checked"':""}} value="INSTITUSI_PEMERINTAH" name="sasaran">
                      <span>Institusi Pemerintah</span>
                      </label>

                      <label class="mt-radio col-md-9"> <input type="radio" {{($data_detail['data']['kodesasaran'] == 'INSTITUSI_SWASTA') ? 'checked="checked"':""}} value="INSTITUSI_SWASTA" name="sasaran">
                      <span>Institusi Swasta</span>
                      </label>

                      <label class="mt-radio col-md-9"> <input type="radio" {{($data_detail['data']['kodesasaran'] == 'LINGKUNGAN_PENDIDIKAN') ? 'checked="checked"':""}} value="LINGKUNGAN_PENDIDIKAN" name="sasaran">
                      <span>Lingkungan Pendidikan</span>
                      </label>

                      <label class="mt-radio col-md-9"> <input type="radio" {{($data_detail['data']['kodesasaran'] == 'LINGKUNGAN_MASYARAKAT') ? 'checked="checked"':""}} value="LINGKUNGAN_MASYARAKAT" name="sasaran">
                      <span>Lingkungan Masyarakat</span>
                      </label>
                  </div>
              </div>
          </div>

          <div class="form-group">
              <label for="instansi" class="col-md-3 control-label">Instansi</label>
              <div class="col-md-8">
                  <div class="mt-repeater">
                      <div data-repeater-list="group-c">
                        @if($data_detail['data']['meta_instansi'])
                          @foreach(json_decode($data_detail['data']['meta_instansi'],true) as $r1 => $c1)
                            <div data-repeater-item="" class="mt-repeater-item">
                                <div class="row mt-repeater-row">
                                    <div class="col-md-4">
                                        <label class="control-label">Nama Instansi</label>
                                        <input name="group-c[{{$r1}}][list_nama_instansi]" value="{{$c1['list_nama_instansi']}}" type="text" class="form-control"> </div>
                                    <div class="col-md-5">
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
                        @else
                          <div data-repeater-item="" class="mt-repeater-item">
                              <div class="row mt-repeater-row">
                                  <div class="col-md-4">
                                      <label class="control-label">Nama Instansi</label>
                                      <input name="group-c[0][list_nama_instansi]" type="text" class="form-control"> </div>
                                  <div class="col-md-5">
                                      <label class="control-label">Jumlah Peserta</label>
                                      <input name="group-c[0][list_jumlah_peserta]" type="text" class="form-control col-md-7 col-xs-12 numeric" onKeydown="numeric(event)"> </div>
                                  <div class="col-md-1">
                                      <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                          <i class="fa fa-close"></i>
                                      </a>
                                  </div>
                              </div>
                          </div>
                        @endif
                      </div>
                      <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                          <i class="fa fa-plus"></i> Tambah Instansi</a>
                  </div>
              </div>
          </div>

          <div class="form-group">
              <label for="lokasi_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Kegiatan </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$data_detail['data']['lokasi_kegiatan']}}" id="lokasi_kegiatan" name="lokasi_kegiatan" type="text" class="form-control">
              </div>
          </div>

          <div class="form-group">
              <label for="lokasi_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Kabupaten</label>
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
            <label for="instansi" class="col-md-3 col-sm-3 col-xs-12 control-label">&nbsp;</label>
            <div class="col-md-8 col-sm-8 col-xs-12">
              <?php
                $arr_narasumber = [];
                $json_narasumber = "";
                if($data_detail['data']['meta_nasum_materi']){
                  $arr_narasumber = json_decode($data_detail['data']['meta_nasum_materi'],true);
                }
              ?>
                <div class="mt-repeater">
                    <div data-repeater-list="meta_nasum_materi">
                        <div data-repeater-item="" class="mt-repeater-item">
                          @if(count($arr_narasumber) > 0)
                            @foreach($arr_narasumber as $k => $s)
                              <div class="row mt-repeater-row">
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <label class="control-label">Narasumber</label>
                                        <input name="meta_nasum_materi[{{$k}}][narasumber]" type="text" value="{{$s['narasumber']}}" class="form-control"> </div>
                                    <div class="col-md-5 col-sm-5 col-xs-12">
                                      <div class="row">
                                          <label class="control-label">Materi yang disampaikan</label>
                                          <textarea name="meta_nasum_materi[{{$k}}][materi]" type="text" class="form-control col-md-7 col-xs-12 ">{{$s['materi']}}</textarea> </div>
                                      </div>
                                    <div class="col-md-1 col-sm-1 col-xs-12">
                                        <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                            <i class="fa fa-close"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                          @else
                              <div class="row mt-repeater-row">
                                  <div class="col-md-4 col-sm-4 col-xs-12">
                                      <label class="control-label">Narasumber</label>
                                      <input name="narasumber[0][narasumber]" type="text" value="{{$data_detail['data']['narasumber']}}" class="form-control"> </div>
                                  <div class="col-md-5 col-sm-5 col-xs-12">
                                    <div class="row">
                                        <label class="control-label">Materi yang disampaikan</label>
                                        <textarea name="narasumber[0][materi]" type="text" class="form-control col-md-7 col-xs-12 ">{{$data_detail['data']['materi']}}</textarea> </div>
                                    </div>
                                  <div class="col-md-1 col-sm-1 col-xs-12">
                                      <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                          <i class="fa fa-close"></i>
                                      </a>
                                  </div>
                              </div>
                          @endif
                        </div>
                    </div>
                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                        <i class="fa fa-plus"></i> Tambah </a>
                </div>
            </div>
        </div>

	        <div class="form-group">
	            <label for="uraian_singkat" class="col-md-3 col-sm-3 col-xs-12 control-label">Uraian Singkat Materi</label>
	            <div class="col-md-6 col-sm-6 col-xs-12">
	                <textarea id="uraian_singkat" rows="3" name="uraian_singkat" type="text" class="form-control col-md-7 col-xs-12">{{$data_detail['data']['uraian_singkat']}}</textarea>
	            </div>
	        </div>

          <div class="form-group">
              <label for="panitia_monev" class="col-md-3 col-sm-3 col-xs-12 control-label">Panitia</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <input value="{{$data_detail['data']['panitia_monev']}}" id="panitia_monev" name="panitia_monev" type="text" class="form-control">
              </div>
          </div>

          <div class="form-group">
              <label for="kodesumberanggaran" class="col-md-3 control-label">Sumber Anggaran</label>
              <div class="col-md-4">
                  <div class="mt-radio-list">
                    <label class="mt-radio col-md-9"> <input {{($data_detail['data']['kodesumberanggaran'] == 'DIPA') ? 'checked="checked"':""}} type="radio" value="DIPA" name="kodesumberanggaran" id="anggaran1">
                    <span>Dipa</span>
                    </label>
                    <label class="mt-radio col-md-9"> <input {{($data_detail['data']['kodesumberanggaran'] == 'NONDIPA') ? 'checked="checked"':""}} type="radio" value="NONDIPA" name="kodesumberanggaran" id="anggaran2">
                    <span>Non Dipa</span>
                    </label>
                  </div>
              </div>
          </div>

          @if($data_detail['data']['anggaran_id'] != '')
      			<div class="form-group" id="PilihAnggaran">
      				<label for="sasaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Anggaran</label>
      				<div class="col-md-6 col-sm-6 col-xs-12">
      					<select class="form-control select2 selectAnggaran" name="kd_anggaran" id="kd_anggaran" style="width:100%">
      						<option value="">-- Pilih Anggaran --</option>
      					</select>
      				</div>
      			</div>

            <div class="form-group" id="DetailAnggaran" >
                <label for="kodeSatker" class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
                <input type="hidden" name="asatker_code" id="kodeSatker" value="{{$data_anggaran['data']['satker_code']}}">
        				<input type="hidden" id="kode_anggaran" value="{{$data_anggaran['data']['kode_anggaran']}}">
                <input type="hidden" name="aid_anggaran" id="aid_anggaran" value="{{$data_anggaran['data']['refid_anggaran']}}">
                <div class="col-md-6 col-sm-6 col-xs-12" id="hasil">
                  <table class="table table-striped nowrap">
                      <tr><td>Kode Anggaran</td><td>{{$data_anggaran['data']['kode_anggaran']}}</td></tr>
                      <tr><td>Sasaran</td><td>{{$data_anggaran['data']['sasaran']}}</td></tr>
                      {{-- <tr><td>Pagu</td><td>{{$data_anggaran['data']['pagu']}}</td></tr> --}}
                      <tr><td>Target Output</td><td>{{$data_anggaran['data']['target_output']}}</td></tr>
                      <tr><td>Satuan Output</td><td>{{$data_anggaran['data']['satuan_output']}}</td></tr>
                      <tr><td>Tahun</td><td>{{$data_anggaran['data']['tahun']}}</td></tr>
                      {{-- <tr><td>Wilayah</td><td>{{$data_anggaran['data']['satker_code']}}</td></tr> --}}
                  </table>
                </div>
            </div>
          @else

            <div class="form-group" id="PilihAnggaran" style="display:none">
              <label for="sasaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Anggaran</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control select2 selectAnggaran" name="kd_anggaran" id="kd_anggaran" style="width:100%">
                  <option value="">-- Pilih Anggaran --</option>
                </select>
              </div>
            </div>

            <div class="form-group" id="DetailAnggaran" style="display:none">
              <label for="kodeSatker" class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
              <input type="hidden" name="asatker_code" id="kodeSatker" value="">
              <div class="col-md-6 col-sm-6 col-xs-12" id="hasil">

              </div>
            </div>
          @endif

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
                      @if (!empty($data_detail['data']['file_upload']))
                          lihat file : <a style="color:yellow" href="{{\Storage::url('AdvokasiMonev/'.$data_detail['data']['file_upload'])}}">{{$data_detail['data']['file_upload']}}</a>
                      @endif
                  </span>
              </div>
          </div>

           <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Foto</label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                  @if ($data_detail['data']['foto1'])
                      <img src="data:image/png;base64,{{$data_detail['data']['foto1']}}" id="blah" style="width:100%;height:150px;" />
                  @else
                      <img src="{{asset('assets/images/NoImage.gif')}}" id="blah" style="width:100%;height:150px;" />
                  @endif
              </div>
              <div class="col-md-3 col-sm-3 col-xs-12">
                  @if ($data_detail['data']['foto2'])
                      <img src="data:image/png;base64,{{$data_detail['data']['foto2']}}" id="blah2" style="width:100%;height:150px;" />
                  @else
                      <img src="{{asset('assets/images/NoImage.gif')}}" id="blah2" style="width:100%;height:150px;" />
                  @endif
              </div>
              <div class="col-md-3 col-sm-3 col-xs-12">
                  @if ($data_detail['data']['foto3'])
                      <img src="data:image/png;base64,{{$data_detail['data']['foto3']}}" id="blah3" style="width:100%;height:150px;" />
                  @else
                      <img src="{{asset('assets/images/NoImage.gif')}}" id="blah3" style="width:100%;height:150px;" />
                  @endif
              </div>
          </div>

          <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"  >&nbsp;</label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                  <input type='file' name="foto1" onchange="readURL(this);" />
                  <input type="text" name="foto1_old" hidden value="{{$data_detail['data']['foto1']}}"/>
              </div>
              <div class="col-md-3 col-sm-3 col-xs-12">
                  <input type='file' name="foto2" onchange="readURL2(this);" />
                  <input type="text" name="foto2_old" hidden value="{{$data_detail['data']['foto2']}}"/>
              </div>
              <div class="col-md-3 col-sm-3 col-xs-12">
                  <input type='file' name="foto3" onchange="readURL3(this);" />
                  <input type="text" name="foto3_old" hidden value="{{$data_detail['data']['foto3']}}"/>
              </div>
          </div>

    </div>
    <div class="form-actions fluid">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">KIRIM</button>
								<a href="{{route('pendataan_monitoring')}}" class="btn btn-primary" type="button">BATAL</a>
            </div>
        </div>
    </div>
</form>
                </div>
            </div>
            </div>

            </div>
        </div>
    </div>
@endsection
