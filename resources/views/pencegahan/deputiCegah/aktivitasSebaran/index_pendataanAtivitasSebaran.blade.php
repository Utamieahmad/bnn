@extends('layouts.base_layout')
@section('title', 'Data Aktivitas Sebaran')

@section('content')

<div class="right_col" role="main">
    <div class="m-t-40">
        <div class="page-title">
            <div class="">
                {!! (isset($breadcrumps) ? $breadcrumps : "" ) !!}
            </div>
        </div>        

        <div class="clearfix"></div>

        <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Data Aktivitas Sebaran<small></small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li class="" @php if(!in_array(53, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
                                <a href="{{url('pencegahan/dir_advokasi/add_pendataan_asistensi')}}" class="btn btn-lg btn-round btn-primary">
                                    <i class="fa fa-print"></i> Print Out
                                </a>
                            </li>
                            <li class="" @php if(!in_array(53, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
                                <a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
                                    <i class="fa fa-file-pdf-o"></i> PDF
                                </a>
                            </li>                            
                            <li class="">
                                <a href="{{URL('/pencegahan/dir_advokasi/printasistensi?'.$forprint)}}" class="btn btn-lg btn-round btn-success">
                                    <i class="fa fa-file-excel-o"></i> Excel
                                </a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content ">
                        @if(session('status'))
                        @php
                        $session= session('status');
                        @endphp
                        <div class="alert alert-{{$session['status']}}">
                            {{ $session['message'] }}
                        </div>
                        @endif                        
                        <div style="overflow-x:auto;">
                            @include('_templateFilter.cegah_aktivitas_sebaran')
                            <table id="example" class="table table-striped dt-responsive nowrap text-left" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Pembuat</th>
                                        <th>Satker</th>
                                        <th>Wilayah</th>
                                        <th style="width: 10%;">Tanggal</th>
                                        <th>Media</th>
                                        <th>Paket</th>
                                        <th>Anggaran</th>
                                        <th>Sasaran</th>
                                        <th>Jml Sebaran</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($data_aktivitas))
                                    @php $i = $start_number; @endphp
                                    @foreach($data_aktivitas as $row)
                                        <tr>
                                            <td> {{$i}}</td>
                                            <td> {{$row['userName']}}</td>
                                            <td> {{$row['userSatker']}}</td>
                                            <td> {{$row['userWilayah']}}</td>                                            
                                            <td> {{date_format(date_create($row['tglact']), "d-m-Y")}}</td>
                                            <td> {{$row['nmmedia']}}</td>
                                            <td> {{$row['paket']}}</td>
                                            <td> {{$row['anggaran']}}</td>
                                            <td> {{$row['sasaran']}}</td>
                                            <td> {{$row['jmlsebar']}}</td>
                                            <td> {{$row['actstat']}}</td>
                                        </tr>
                                    @php $i = $i+1; @endphp
                                    @endforeach
                                    @else
                                        <tr>
                                            <td colspan="12">
                                                <div class="alert-messages alert-warning">
                                                    @if(isset($filter))
                                                    @if(count($filter) >0)
                                                    Data Aktivitas Sebaran Yang Anda Cari Tidak Tersedia.
                                                    @else
                                                    Data Aktivitas Sebaran Tidak Tersedia.
                                                    @endif
                                                    @else
                                                    Data Aktivitas Sebaran Tidak Tersedia.
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>              
                                    @endif
                                </tbody>
                            </table>                            
                        </div>
                        @if(count($data_aktivitas) > 0)
                            {!! $pagination !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('modal.modal_delete_form')
@endsection