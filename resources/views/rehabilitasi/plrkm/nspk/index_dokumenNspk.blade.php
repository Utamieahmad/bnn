@extends('layouts.base_layout')
@section('title', 'Data Dokumen NSPK')

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
            <h2>Data Input Dokumen NSPK<small></small></h2>
            <ul class="nav navbar-right panel_toolbox">
              {{-- <li class=""> --}}
              <li class="" @php if(!in_array(39, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
                <a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
                  <i class="fa fa-plus-circle"></i> Input Nihil
                </a>
              </li>
              {{-- <li class=""> --}}
              <li class="" @php if(!in_array(39, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
                <a href="{{route('add_dokumen_nspk_plrkm')}}" class="btn btn-lg btn-round btn-primary">
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
            @include('_templateFilter.rehab_nspk_filter')
              <table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                      <tr>
                        <th>No</th>
                        <th>Tanggal Pengesahan</th>
                        <th>Nama NSPK</th>
                        <th>No. NSPK</th>
                        <th>Peruntukan</th>
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
                    <td width="20%"> {{\Carbon\Carbon::parse($d->tgl_pembuatan)->format('d/m/Y')}}</td>
                    <td width="32%"> {{$d->nama_nspk}} </td>
                    <td width="32%"> {{$d->nomor_nsdpk}} </td>
                    <td> {{$d->peruntukan}} </td>
                    <td> {{( ($d->status == 'Y') ? 'Lengkap' : 'Tidak Lengkap')}} </td>
                    {{--<td>
                      <a href="{{route('edit_dokumen_nspk_plrkm',$d->id)}}"><i class="fa fa-pencil"></i></a>
                      <button type="button" class="btn btn-primary button-delete-form" data-target="{{$d->id}}" onClick="delete_form(event,this)"><i class="fa fa-trash"></i></button>
                    </td>--}}
                    <td>
                      <a @php if(!in_array(39, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{route('edit_dokumen_nspk_plrkm',$d->id)}}"><i class="fa fa-pencil"></i></a>
                      <button @php if(!in_array(39, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-delete-form" data-target="{{$d->id}}" onClick="delete_form(event,this)"><i class="fa fa-trash"></i></button>
                    </td>
                  </tr>
                @php $i = $i+1; @endphp
                @endforeach
                @else
                  <tr>
                    <td colspan="7">
                      <div class="alert-messages alert-warning">
                        @if(isset($filter))
                            @if(count($filter) >0)
                              Data Dokumen NSPK PLRKM Yang Anda Cari Tidak Tersedia.
                            @else
                              Data Dokumen NSPK PLRKM Tidak tersedia.
                            @endif
                          @else
                              Data Dokumen NSPK PLRKM Tidak tersedia.
                          @endif

                      </div>
                    </td>
                  </tr>
                @endif
              </tbody>
              </table>
                @if(count($data) > 0)
                  {!! $pagination !!}
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
