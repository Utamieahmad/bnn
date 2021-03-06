@extends('layouts.base_layout')
@section('title', 'Data Kegiatan')

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
            <h2>Data Kegiatan Direktorat PascaRehabilitasi<small></small></h2>
            <ul class="nav navbar-right panel_toolbox">
              {{-- <li class=""> --}}
              <li class="" @php if(!in_array(47, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
                <a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
                  <i class="fa fa-plus-circle"></i> Input Nihil
                </a>
              </li>
              {{-- <li class=""> --}}
              <li class="" @php if(!in_array(47, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
                <a href="{{route('add_kegiatan_pelatihan_pascarehabilitasi')}}" class="btn btn-lg btn-round btn-primary">
                  <i class="fa fa-plus-circle c-yelow"></i> Tambah Data
                </a>
              </li>
              <li class="">
                @if(count($data) && isset($current_page))
                  <a href="{{route('print_page_pascarehabilitasi',[$route_name,$kondisi])}}" class="btn btn-lg btn-round btn-dark">
                    <i class="fa fa-print"></i> Cetak
                  </a>
                @endif
              </li>
  						<li class="">
  						<a href="#" class="btn btn-lg btn-round btn-success" data-toggle="modal" data-target="#modal_report_excel" onClick="reportExcel(event,this)" data-url="{{url('rehabilitasi/print_page_rehabilitasi/downloadPascaKegiatan')}}">
  								<i class="fa fa-file"></i> Excel
  							</a>
  						</li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content ">
            <br/>
            @if(session('status'))
                @php
                    $session= session('status');
                @endphp
                <div class="alert alert-{{$session['status']}}">
                    {{ $session['message'] }}
                </div>
             @endif
              @include('_templateFilter.rehab_kegiatan_filter')
              <table id="datatable-responsive" class="table table-striped dt-responsive nowrap col-left col-left2" cellspacing="0" width="100%">
                <thead>
                      <tr>
                        <th>No</th>
                        <th>Pelaksana</th>
                        <th>Judul</th>
                        <th>Nomor Surat Perintah</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Jumlah Peserta</th>
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
                        <td> {{$d->nm_instansi}}</td>
                        <td> {{$d->tema}}</td>
                        <td> {{$d->nomor_sprint}} </td>
                        <td> {{ $d->tgl_dilaksanakan_mulai ? date('d/m/Y',strtotime($d->tgl_dilaksanakan_mulai)) : ''}} </td>
                        <td> {{ $d->tgl_dilaksanakan_selesai ? date('d/m/Y',strtotime($d->tgl_dilaksanakan_selesai)) :''}} </td>
                        <td> {{number_format($d->jumlah_peserta)}}</td>
                        <td> {{( ($d->status == 'Y') ? 'Lengkap' : 'Tidak Lengkap')}} </td>
                        {{--<td>
                          <a href="{{route('edit_kegiatan_pelatihan_pascarehabilitasi',$d->id)}}"><i class="fa fa-pencil"></i></a>
                          <button type="button" class="btn btn-primary button-delete-form"   data-target="{{$d->id}}" onClick="delete_form(event,this)"><i class="fa fa-trash"></i></button>
                        </td>--}}
                        <td>
                          <a @php if(!in_array(47, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{route('edit_kegiatan_pelatihan_pascarehabilitasi',$d->id)}}"><i class="fa fa-pencil"></i></a>
                          <button @php if(!in_array(47, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-delete-form"   data-target="{{$d->id}}" onClick="delete_form(event,this)"><i class="fa fa-trash"></i></button>
                        </td>
                      </tr>
                    @php $i = $i+1; @endphp
                    @endforeach
                    @else
                        <tr>
                          <td colspan="9">
                            <div class="alert-messages alert-warning">
                              @if(isset($filter))
                                  @if(count($filter) >0)
                                    Data Kegiatan Direktorat PascaRehabilitasi Yang Anda Cari Tidak Tersedia.
                                  @else
                                    Data Kegiatan Direktorat PascaRehabilitasi Tidak Tersedia.
                                  @endif
                                @else
                                    Data Kegiatan Direktorat PascaRehabilitasi Tidak Tersedia.
                                @endif

                            </div>
                          </td>
                        </tr>
                      @endif
                </tbody>
              </table>
              @if(count($data))
                {!! $pagination !!}
              @endif


          </div>
        </div>
      </div>
    </div>
  </div>

@include('modal.modal_delete_form')
@include('modal.modal_inputNihil')
@include('modal.modal_report_excel')

@endsection
