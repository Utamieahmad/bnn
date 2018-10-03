@extends('layouts.base_layout')
@section('title', 'Ubah Data User')

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
                    <h2>Form Ubah Data User</h2>
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
   <form action="{{URL('/user_management/update_user')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
        {{-- <input type="hidden" name="form_method" value="create"> --}}
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{$id}}">
        <div class="form-body">

        <div class="form-group">
            <label for="email" class="col-md-3 col-sm-3 col-xs-12 control-label">Email</label>
            <div class="col-md-3 col-sm-3 col-xs-6">
                <input value="{{ (isset($data_detail['email'])) ? $data_detail['email'] : '' }}" id="email" name="email" type="text" class="form-control" disabled>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-6">
                <a href="{{URL('/user_management/reset_email?id=' . $id)}}" class="btn btn-warning" type="button" data-toggle="modal" data-target="#modal_edit_email">Change Email</a>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Grup</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="meta-repeater">
                    <div data-repeater-list="meta_grup">
                        @if(count($group_ids) > 0)
                            @foreach($group_ids as $gindex => $gid)
                            <div data-repeater-item class="mt-repeater-item">
                                <div class="row mt-repeater-row">
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <select name="id" id="grup" class="form-control metaSatker" tabindex="-1" aria-hidden="true" required>
                                          <option value="">-- Pilih Grup --</option>
                                          @foreach($groups['data'] as $g)
                                          <option value="{{$g['group_id']}}" {{ ($gid == $g['group_id']) ? 'selected' : '' }}>{{$g['group_name']}}</option>
                                          @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-12">
                                        <a href="javascript:;" data-repeater-delete="" class="btn btn-danger">
                                            <i class="fa fa-close"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                        <div data-repeater-item class="mt-repeater-item">
                            <div class="row mt-repeater-row">
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <select name="id" id="grup" class="form-control metaSatker" tabindex="-1" aria-hidden="true" required>
                                      <option value="">-- Pilih Grup --</option>
                                      @foreach($groups['data'] as $g)
                                      <option value="{{$g['group_id']}}">{{$g['group_name']}}</option>
                                      @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger">
                                        <i class="fa fa-close"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add onCreatePendamping">
                        <i class="fa fa-plus"></i> Tambah Grup</a>
                </div>
            </div>
        </div>
        <br/>

        <div class="form-group">
            <label for="user_name" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{ (isset($data_detail['user_name'])) ? $data_detail['user_name'] : '' }}" id="user_name" name="user_name" type="text" class="form-control" disabled>
            </div>
        </div>

        <div class="form-group">
            <label for="wilayah" class="col-md-3 col-sm-3 col-xs-12 control-label">Wilayah</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{ (isset($data_detail['nm_wilayah'])) ? $data_detail['nm_wilayah'] : 'PUSAT' }}" id="wilayah" name="wilayah" type="text" class="form-control" disabled>
            </div>
        </div>

        <div class="form-group">
            <label for="nip" class="col-md-3 col-sm-3 col-xs-12 control-label">NIP</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{ (isset($data_detail['nip'])) ? $data_detail['nip'] : '' }}" id="nip" name="nip" type="text" class="form-control" disabled>
            </div>
        </div>

        <div class="form-group">
            <label for="jabatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jabatan</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{ (isset($data_simpeg['jabatan'])) ? $data_simpeg['jabatan'] : $data_detail['jabatan_pegawai'] }}" id="jabatan" name="jabatan" type="text" class="form-control" disabled>
            </div>
        </div>

        <div class="form-group">
            <label for="lokasi_kerja" class="col-md-3 col-sm-3 col-xs-12 control-label">Lokasi Kerja</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{ (isset($data_detail['lokasi_kerja'])) ? $data_detail['lokasi_kerja'] : '' }}" id="lokasi_kerja" name="lokasi_kerja" type="text" class="form-control" disabled>
            </div>
        </div>

        <div class="form-group">
            <label for="telp_pegawai" class="col-md-3 col-sm-3 col-xs-12 control-label">Handphone</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{ (isset($data_detail['telp_pegawai'])) ? $data_detail['telp_pegawai'] : '' }}" id="telp_pegawai" name="telp_pegawai" type="text" class="form-control" disabled>
            </div>
        </div>
        <div class="form-group">
            <label for="kepegawaian" class="col-md-3 col-sm-3 col-xs-12 control-label">Status Kepegawaian</label>
            @if($data_detail['nip'] == '')
            <div class="col-md-3 col-sm-3 col-xs-6">
                <input value="{{ ($data_detail['nip'] != '') ? 'PNS' : 'PHL' }}" id="kepegawaian" name="kepegawaian" type="text" class="form-control" disabled>
            </div>

            <div class="col-md-3 col-sm-3 col-xs-6">
                <a href="#" class="btn btn-warning" type="button" data-toggle="modal" data-target="#modal_edit_nip">Ganti Status</a>
            </div>
            @else
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{ ($data_detail['nip'] != '') ? 'PNS' : 'PHL' }}" id="kepegawaian" name="kepegawaian" type="text" class="form-control" disabled>
            </div>
            @endif
        </div>
        @if($data_detail['nip'] != '')
        <div class="form-group">
            <label for="nip" class="col-md-3 col-sm-3 col-xs-12 control-label">NIP</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="{{ (isset($data_detail['nip'])) ? $data_detail['nip'] : '' }}" id="nip" name="nip" type="text" class="form-control" disabled>
            </div>
        </div>
        @endif
        <div class="form-group">
            <label for="password" class="col-md-3 col-sm-3 col-xs-12 control-label">Reset Password</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <a href="{{URL('/user_management/reset_password?id=' . $id)}}" class="btn btn-warning" type="button">Kirim Link</a>
            </div>
        </div>

        <div class="form-group">
            <label for="password" class="col-md-3 col-sm-3 col-xs-12 control-label">Ubah Password</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <a href="" class="btn btn-warning" type="button" data-toggle="modal" data-target="#modal_new_password2">Ubah</a>
            </div>
        </div>


        <div class="form-group">
            <label for="active_flag" class="col-md-3 col-sm-3 col-xs-12 control-label">Status</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="checkbox" name="active_flag" id="active_flag" style="margin-top:12px;" {{ ($data_detail['active_flag'] == 'Y') ? 'checked=""' : '' }}>
            </div>
        </div>

    </div>
    <br/>
     <div class="form-actions fluid">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">KIRIM</button>
				<a href="{{route('user')}}" class="btn btn-primary" type="button">BATAL</a>
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
@include('modal.modal_user_management_edit_email')
@include('modal.modal_user_management_edit_nip')
@include('modal.modal_user_management_new_password2')
@endsection
