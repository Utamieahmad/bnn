@extends('layouts.base_layout')
@section('title', 'Data Sosialisasi')

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
            <h2>Data Sosialisasi <small></small></h2>
            <ul class="nav navbar-right panel_toolbox">
              <li class="" @php if(!in_array(121, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
                <a href="#" class="btn btn-lg btn-round btn-danger" data-toggle="modal" data-target="#modal_input_nihil">
                  <i class="fa fa-plus-circle"></i> Input Nihil
                </a>
              </li>
              <li class="" @php if(!in_array(121, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
                <a href="{{url('irtama/sosialisasi/add_irtama_sosialisasi')}}" class="btn btn-lg btn-round btn-primary">
                  <i class="fa fa-plus-circle c-yelow"></i> Tambah Data
                </a>
              </li>
              <li class="">
                <a href="{{route('print_page_irtama',['irtama_sosialisasi',$kondisi])}}" class="btn btn-lg btn-round btn-dark">
                    <i class="fa fa-print"></i> Cetak
                  </a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content ">
              @if (session('status'))
                    @php
                      $session= session('status');
                    @endphp

              <div class="alert alert-{{$session['status']}}">
                    {{ $session['message'] }}
                </div>
            @endif
            @include('_templateFilter.irtama_sosialisasi')
            <table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Surat Perintah</th>
                  <th>Lokasi</th>
                  <th>Satker</th>
                  <th>Tanggal laporan</th>
                  <th>Jumlah Peserta</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @if(count($sosialisasi))
                @php $i = $start_number; @endphp
                @foreach($sosialisasi as $d)
                <tr>
                  <td> {{$i}}</td>
                  <td> {{$d['sprin']}}</td>
                  <td> {{$d['lokasi']}}</td>
                  <td>
                    @php
                      $data_satker = $d['kode_satker'];
                      $id_satker = "";
                      if($data_satker){
                        $j = json_decode($data_satker,true);
                        $nama_satker = $j['nama'];
                      }else{
                        $nama_satker = "";
                      }
                    @endphp

                    {{$nama_satker}}
                  </td>
                  <td> {{ \Carbon\Carbon::parse($d['tgl_laporan'])->format('d/m/Y') }} </td>
                  <td> {{ $d['jumlah_peserta'] }} </td>
                  <td> {{($d['status'] == 'Y' ? 'Lengkap' : ($d['status'] == 'N' ? 'Tidak lengkap' : 'Tidak lengkap' ) )}}</td>
                  <td>
                    <a @php if(!in_array(121, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{url('irtama/sosialisasi/edit_irtama_sosialisasi/'.$d['id'])}}"><i class="fa fa-pencil"></i></a>
                    <button @php if(!in_array(121, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-action" onClick="delete_form(event,this)" data-target="{{$d['id']}}" ><i class="fa fa-trash"></i></button>
                  </td>
                </tr>
                @php $i = $i+1; @endphp
                @endforeach
                @else
                <tr>
                  <td colspan="8">
                    <div class="alert-messages alert-warning">

                      @if(isset($filter))
                        @if(count($filter) >0)
                           Data Sosialisasi  Yang Anda Cari Tidak Tersedia.
                        @else
                           Data Sosialisasi Tidak tersedia.
                        @endif
                      @else
                           Data Sosialisasi Tidak tersedia.
                      @endif
                    </div>
                  </td>
                </tr>
                @endif
              </tbody>
            </table>
            @if(count($sosialisasi) > 0)
              {!! $pagination !!}
            @endif


          </div>
        </div>
      </div>
    </div>
  </div>
</div>


@include('modal.modal_inputNihil')
@include('modal.modal_delete_form')
@endsection
