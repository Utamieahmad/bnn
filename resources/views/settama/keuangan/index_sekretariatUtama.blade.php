@extends('layouts.base_layout')
@section('title', 'Kegiatan Biro Keuangan Sekretariat Utama')

@section('content')
  <div class="right_col" role="main">
    <div class="m-t-40">
      <div class="page-title">
        <div class="">
          {!! (isset($breadcrumps) ? $breadcrumps : "" ) !!}
        </div>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">

      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Data Kegiatan Biro Keuangan Sekretariat Utama<small></small></h2>
            <ul class="nav navbar-right panel_toolbox">
              <li class="" @php if(!in_array(113, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
                <a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
                  <i class="fa fa-plus-circle"></i> Input Nihil
                </a>
              </li>
              <li class="" @php if(!in_array(113, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
                <a href="{{route('add_settama_keuangan')}}" class="btn btn-lg btn-round btn-primary">
                  <i class="fa fa-plus-circle c-yelow"></i> Tambah Data
                </a>
              </li>
              <li class="">
                @if(isset($data) && isset($current_page))
                  <a href="{{route('print_settama_keuangan',[$route_name,$kondisi])}}" class="btn btn-lg btn-round btn-dark">
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
              @include('_templateFilter.template_filter',$filter_parameter)

              <table id="datatable-responsive" class="table table-striped dt-responsive nowrap col-left col-left2" cellspacing="0" width="100%">
                <thead>
                      <tr>
                        <th>No</th>
                        <th>No Rujukan</th>
                        <th>Tanggal Pelaksanaan</th>
                        <th>Jenis Kegiatan</th>
                        <th>Sumber Anggaran</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                </thead>
              <tbody>
                @if(count($data))
                  @php $i = $start_number; @endphp
                    @foreach($data as $d)
                      <tr>
                        <td> {{$i}}</td>
                        <td> {{$d->no_rujukan}}</td>
                        <td> {{ ($d->tgl_mulai ? date('d/m/Y',strtotime($d->tgl_mulai)) : '')}} - {{($d->tgl_selesai ? date('d/m/Y',strtotime($d->tgl_selesai)) : '')}} </td>
                        <td> {{$d->nama_jenis_kegiatan}} </td>
                        <td> {{$d->sumber_anggaran}} </td>
                        <td>  @if($d->status == 'Y')
                                Lengkap
                              @elseif($d->status == 'N')
                                Tidak Lengkap
                              @endif </td>
                        <td>
                          <a @php if(!in_array(113, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{route('edit_settama_keuangan',$d->id_settama)}}"><i class="fa fa-pencil"></i></a>
                          <button @php if(!in_array(113, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-delete-form" data-target="{{$d->id_settama}}" onClick="delete_form(event,this)"><i class="fa fa-trash"></i></button>
                        </td>
                      </tr>
                    @php $i = $i+1; @endphp
                  @endforeach
                @else
                <tr>
                  <td colspan="7">
                    <div class="alert-messages alert-warning">
                      @if(isset($filter))
                        @if($filter['selected'])
                          Data Kegiatan Biro Keuangan Sekretariat Utama Yang Anda Cari Tidak Tersedia.
                        @else
                          Data Kegiatan Biro Keuangan Sekretariat Utama Tidak Tersedia.
                        @endif
                      @else
                          Data Kegiatan Biro Keuangan Sekretariat Utama Tidak Tersedia.
                      @endif
                    </div>
                  </td>
                </tr>
                @endif

              </tbody>
              </table>
              @if(count($data))
                <div class="pagination_wrap">
                  {!! $pagination !!}
                </div>
              @endif
          </div>
        </div>
      </div>
    </div>
  </div>


@include('modal.modal_delete_form')
@include('modal.modal_input_nihil')
@endsection
