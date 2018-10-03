@extends('layouts.base_layout')
@section('title', 'Dir PLRIP : Penilaian Lembaga Rehabilitasi ')

@section('content')
  <div class="right_col" role="main">
    <div class="m-t-40">
      <div class="page-title">
        <div class="">
          {!! (isset($breadcrumps) ? $breadcrumps : "" ) !!}
        </div>
      </div>

      <div class="clearfix"></div>

      <div class="clearfix"></div>

      <div class="row">
      
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Data Penilaian Lembaga PLRIP<small></small></h2>
              <ul class="nav navbar-right panel_toolbox">
                <li class="">
                  <a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
                    <i class="fa fa-plus-circle"></i> Input Nihil
                  </a>
                </li> 
                <li class="">
                  <a href="{{route('add_penilaian_lembaga_plrip')}}" class="btn btn-lg btn-round btn-primary">
                    <i class="fa fa-plus-circle c-yelow"></i> Tambah Data
                  </a>
                </li>
                <li class="">
                  @if(count($data) && isset($current_page))
                    <a href="{{route('print_page_pascarehabilitasi',[$route_name,$current_page])}}" class="btn btn-lg btn-round btn-dark">
                      <i class="fa fa-print"></i> Cetak
                    </a>
                  @endif
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
              @if(count($data)) 
                
                <table id="datatable-responsive" class="table table-striped dt-responsive nowrap col-left" cellspacing="0" width="100%">
                  <thead>
                        <tr>
                          <th> No </th>
                          <th>Nama Lembaga</th>
                          <th>Alamat</th>
                          <th>Kode Pos</th>
                          <th>Actions</th>
                        </tr>
                  </thead>
                <tbody>
                  @php $i = $start_number; @endphp
                  @foreach($data as $d)
                    <tr>
                      <td> {{$i}}</td>
                      <td> {{$d->nama}}</td>
                      <td> {{$d->alamat}}</td>
                      <td> {{$d->alamat_kodepos}} </td>
                      <td>
                        <a href="{{route('edit_penilaian_lembaga_plrip',$d->id)}}"><i class="fa fa-pencil"></i></a>
                        <button type="button" class="btn btn-primary button-delete-form" data-target="{{$d->id}}" onClick="delete_form(event,this)"><i class="fa fa-trash"></i></button>
                      </td>
                    </tr>
                  @php $i = $i+1; @endphp
                  @endforeach
                </tbody>  
                </table>
              @else
                <div class="alert alert-warning">
                  Data Penilaian Lembaga PLRIP belum tersedia.
                </div>
              @endif

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@include('modal.modal_delete_form')
@include('modal.modal_inputNihil')

@endsection
