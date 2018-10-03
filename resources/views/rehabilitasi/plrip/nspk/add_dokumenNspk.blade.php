@extends('layouts.base_layout')
@section('title', 'Tambah Data Dokumen NSPK')

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
                    <h2>Form Tambah Dokumen NSPK Direktorat PLRIP</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form action="{{route('save_dokumen_nspk_plrip')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
                        {{csrf_field()}}
                        <div class="form-body">
                            

                            <div class="form-group">
                                <label for="tgl_pembuatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Pengesahan</label>
                                <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                                    <input type='text' name="tgl_pembuatan" class="form-control" value="" required/>
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nama_nspk" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama NSPK</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input value="" id="nama_nspk" name="nama_nspk" type="text" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nomor_nsdpk" class="col-md-3 col-sm-3 col-xs-12 control-label">Nomor NSPK</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input value="" id="nomor_nsdpk" name="nomor_nsdpk" type="text" class="form-control" >
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="peruntukan" class="col-md-3 col-sm-3 col-xs-12 control-label">Peruntukan</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input value="" id="peruntukan" name="peruntukan" type="text" class="form-control" >
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
                                </div>
                            </div>
                        </div>
                         <div class="form-actions fluid">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <button type="submit" class="btn btn-success">SIMPAN</button>
                                    <a href="{{route('dokumen_nspk_plrip')}}" class="btn btn-primary" type="button">BATAL</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
