@extends('layouts.base_layout')
@section('title', 'Tambah Data User')

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
                    <h2>Form Tambah Data User</h2>
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
   <form action="{{URL('/user_management/input_user')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
        {{-- <input type="hidden" name="form_method" value="create"> --}}
            {{ csrf_field() }}
        <div class="form-body">

        <div class="form-group" >
            <label for="instansi" class="col-md-3 col-sm-3 col-xs-12 control-label">Pelaksana</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="instansi" id="instansi" class="form-control select2 selectPelaksana" tabindex="-1" aria-hidden="true" required>
                  <option value="">-- Pilih Pelaksana --</option>
                  @foreach($instansi as $in)
                  <option value="{{$in['id_instansi']}}">{{$in['nm_instansi']}}</option>
                  @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="email" class="col-md-3 col-sm-3 col-xs-12 control-label">Email</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input value="" id="email" name="email" type="text" class="form-control" required>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Grup</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="meta-repeater">
                    <div data-repeater-list="meta_grup">
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
                    </div>
                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add onCreatePendamping">
                        <i class="fa fa-plus"></i> Tambah Grup</a>
                </div>
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
@endsection
