@extends('layouts.base_layout')
@section('title', 'Ubah Data Dokumen NSPK')

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
                        <h2>Form Ubah Dokumen NSPK Direktorat PLRKM</h2>
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
                        <form action="{{route('update_dokumen_nspk_plrkm')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
                            <input type="hidden" name="id" value="{{$data->id}}">
                            {{csrf_field()}}
                            <div class="form-body">
                                

                                <div class="form-group">
                                    <label for="tgl_pembuatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Pengesahan</label>
                                    <div class='col-md-6 col-sm-6 col-xs-12 input-group date datepicker-only'>
                                        <input type='text' name="tgl_pembuatan" class="form-control" value="{{( $data->tgl_pembuatan ? \Carbon\Carbon::parse($data->tgl_pembuatan)->format('d/m/Y') : '' )}}" required/>
                                        <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="nama_nspk" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama NSPK</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{$data->nama_nspk}}" id="nama_nspk" name="nama_nspk" type="text" class="form-control" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="nomor_nsdpk" class="col-md-3 col-sm-3 col-xs-12 control-label">Nomor NSPK</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{$data->nomor_nsdpk}}" id="nomor_nsdpk" name="nomor_nsdpk" type="text" class="form-control" >
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="peruntukan" class="col-md-3 col-sm-3 col-xs-12 control-label">Peruntukan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input value="{{$data->peruntukan}}" id="peruntukan" name="peruntukan" type="text" class="form-control" >
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="file_nspk" class="col-md-3 control-label">Dokumen NSPK</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="input-group input-large">
                                                <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                                                    <i class="fa fa-file fileinput-exists"></i>&nbsp;
                                                    <span class="fileinput-filename"> </span>
                                                </div>
                                                <span class="input-group-addon btn default btn-file">
                                                    <span class="fileinput-new"> Pilih Berkas </span>
                                                    <span class="fileinput-exists"> Ganti </span>
                                                    <input type="file" name="file_nspk"> </span>
                                                <a href="#" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
                                            </div>
                                        </div>
                                         <div class="row">
                                                <div class="col-sm-12 col-xs-12 col-md-12">
                                                    <span class="help-block white">
                                                        @if (isset($data->file_nspk))
                                                            @if (File::exists($file_path.$data->file_nspk))
                                                                <div class="col-md-11 col-sm-11 col-xs-11">
                                                                    Lihat File : <a  target="_blank" class="link_file" href="{{url($file_path.$data->file_nspk)}}">{{$data->file_nspk}}</a>
                                                                </div>
                                                                <div class="col-md-1 col-xs-1 col-sm-1">
                                                                    <a class='btn btn-download' href="{{url($file_path.$data->file_nspk)}}" download="{{$data->file_nspk}}" ><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                             <div class="form-actions fluid">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                        <button type="submit" class="btn btn-success">SIMPAN</button>
                                        <a href="{{route('dokumen_nspk_plrkm')}}" class="btn btn-primary" type="button">BATAL</a>
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
