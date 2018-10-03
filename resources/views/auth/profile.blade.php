@extends('layouts.base_layout')
@section('title', 'My Profile')

@section('content')
<div class="right_col" role="main">
    <div class="m-t-40">
        <div class="page-title">
            <div class="">
                <ul class="page-breadcrumb breadcrumb">
                    <li> Profile </li>
                </ul>
            <!--<h3>Fixed Sidebar <small> Just add class <strong>menu_fixed</strong></small></h3>-->
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Form Profile </h2>
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
                        <div class="col-xs-12 col-md-3 col-sm-3">
                            <form method="POST" action="{{url('upload_photo')}}" onSubmit="upload_form(event,this)" class="form-photo" enctype="multipart/form-data">
                                {!! csrf_field()!!}
                                <div class="image-wrap">
                                  @php
                                    $img = "";
                                    $photo = Request::session()->get('foto_pegawai');

                                    if(isset($photo)){
                                      $img = "data:image/png;base64,".$photo;
                                    }else{
                                      $img = '/assets/images/default-image.png';
                                    }
                                  @endphp
                                    <img src="{{$img}}" alt="" class="img-rounded img-responsive image-profile" width="240"/>
                                </div>
                                <div class="fileupload fileupload-new row m-t-10" data-provides="fileupload">
                                    <div class="loader"><span class="loading"> Loading image.... </span> </div>
                                    <input type="file" name="photo" class="hidden button-upload" onChange="upload_form(event,this)"/>
                                    <div class="image-process">
                                        <div class="col-xs-7 col-md-7 col-sm-7">

                                            <input type="text" name="photo_temp" class="form-control photo_temp" />
                                        </div>
                                        <div class="col-xs-3 col-md-3 col-sm-3 group-button">
                                            <button class="btn btn-success btn-file" onClick="upload_form(event,this)">Pilih File </button>
                                            <button class="btn btn-warning btn-file" onClick="cancel_upload_form(event,this)">Cancel </button>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-12 col-xs-12 col-sm-12">
                                        <button class="btn btn-info btn-file btn-edit-profile" onClick="edit_profile(event,this)">Edit Profile </button>
                                    </div> --}}
                                </div>
                            </form>
                        </div>
                        <div class="col-xs-12 col-md-9 col-sm-12">
                            <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12"><p class="p_profile">{{ucwords($data_profile->user_name)}}</p></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Email</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12"><p class="p_profile">{{$data_profile->email}} &nbsp; &nbsp;<a href="#" class="btn btn-xs btn-round btn-primary" data-toggle="modal" data-target="#modal_edit_email" style="margin-top:-5px;">Ubah</a></p></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Status Kepegawaian</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12"><p class="p_profile">{{($data_additional->nip != '') ? 'PNS' : 'PHL'}}</p></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NIP</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12"><p class="p_profile">
                                    @if($data_additional->nip == '')
                                        <a href="#" class="btn btn-xs btn-round btn-primary" data-toggle="modal" data-target="#modal_edit_nip">Tambah</a>
                                    @else
                                        {{ ucwords($data_additional->nip) }}
                                    @endif
                                    </p></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Jabatan</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12"><p class="p_profile">{{$data_additional->jabatan_pegawai}}</p></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Lokasi Kerja</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12"><p class="p_profile">{{$data_additional->lokasi_kerja}}</p></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Phone</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12"><p class="p_profile">{{$data_additional->telp_pegawai}}</p></div>
                                </div>



                                <!-- <div class="form-group">
                                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                        <button class="btn btn-primary" type="button">Batal</button>
                                        <button type="submit" class="btn btn-success">Simpan</button>
                                    </div>
                                </div> -->
                            </form>

                            <div class="col-xs-12 col-md-3 col-sm-3">
                            </div>

                            <div class="col-xs-12 col-md-12 col-sm-12">
                                <div class="button-change-password">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name"></label>
                                    <div class="col-xs-6 col-md-9 col-sm-9">
                                        <button class="btn btn-transparent btn_change_password"  onClick="display_form(this)"> Ubah Password</button>
                                    </div>
                                </div>
                                <div class="change_password mt-20">
                                    <form method="POST" action="#" class="form-horizontal form-label-left">
                                        {{ csrf_field() }}
                                        {!! web_token() !!}
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group">
                                                    <h2 class="text-center"> Ubah Password </h2>
                                                </div>
                                                <div class="message-validation alert alert-error">

                                                </div>
                                                <div class="message-validation-success alert alert-success">

                                                </div>
                                                <div class="loader-wrap">
                                                    <div class="loader"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Password Lama</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12"><input type="password" value="" name="old_password" class="form-control col-md-7 col-xs-12"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Password Baru</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12"><input type="password" value="" name="password" class="form-control col-md-7 col-xs-12"></div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Konfirmasi Password</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12"><input type="password" value="" name="password_confirmation" class="form-control col-md-7 col-xs-12"></div>
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" name="user_id" value="{{$data_profile->user_id}}"/>
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name"></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="submit" name="cancel_form" class="btn btn-primary" value="Batal" onClick="cancel_save_password(event,this)"/>
                                                        <input type="submit" name="submit_form" class="btn btn-success" value="Simpan" onClick="save_password(event,this)"/>
                                                    </div>
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
        </div>
    </div>
</div>

@include('modal.modal_profile_edit_email')
@include('modal.modal_profile_edit_nip')
@endsection
