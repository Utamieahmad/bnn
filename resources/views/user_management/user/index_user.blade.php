@extends('layouts.base_layout')
@section('title', 'Data User')

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
                            <h2>Data User<small></small></h2>
                            <ul class="nav navbar-right panel_toolbox">
                            <li class="" @php if(!in_array(132, Session::get("cancreate")))  echo 'style="display:none;"'; @endphp>
                            <a href="{{url('user_management/add_user')}}" class="btn btn-lg btn-round btn-primary">
                            <i class="fa fa-plus-circle c-yelow"></i> Tambah Data
                            </a>
                            </li>
                            <li class="">
                                <a href="{{route('print_user',['print_user',$kondisi])}}" class="btn btn-lg btn-round btn-dark">
                                <i class="fa fa-print"></i> Cetak
                            </a>
                            </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content ">
                                @include('_templateFilter.user_management_user_filter')
                                <table id="datatable-responsive" class="table table-striped dt-responsive nowrap col-left" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>NIP</th>
                                            <th>Wilayah</th>
                                            <th>Status Kepegawaian</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                <tbody>
                                    @if(count($data))
                                        @php $i = $start_number; @endphp
                                        @foreach($data as $d)
                                            <tr>
                                                <td> {{$i}} </td>
                                                <td> {{ $d->user_name }} </td>
                                                <td> {{ $d->email }} </td>
                                                <td> {{ $d->nip }} </td>
                                                <td> {{ ($d->wilayah_id != '') ? $nm_wilayah[$d->wilayah_id] : '' }} </td>
                                                <td>
                                                    @if($d->nip == '')
                                                        PHL
                                                    @else
                                                        PNS
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($d->active_flag == 'Y')
                                                        Aktif
                                                    @elseif($d->active_flag == 'N')
                                                        Tidak Aktif
                                                    @endif
                                                </td>
                                                <td>
                                                    <a @php if(!in_array(132, Session::get("canedit")))  echo 'style="display:none;"'; @endphp href="{{url('user_management/edit_user/'.$d->user_id)}}"><i class="fa fa-pencil"></i></a>
                                                    <button @php if(!in_array(132, Session::get("candelete")))  echo 'style="display:none;"'; @endphp type="button" class="btn btn-primary button-delete" data-target="{{$d->user_id}}"  onClick="delete_form(event,this)"><i class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        @php $i = $i+1; @endphp
                                        @endforeach
                                    @else
                                    <tr>
                                      <td colspan="8">
                                        <div class="alert-messages alert-warning">
                                          @if(isset($filter))
                                            @if(isset($filter['selected']))
                                              Data User Yang Anda Cari Belum Tersedia.
                                            @else
                                              Data User Belum Tersedia.
                                            @endif
                                          @else
                                              Data User Belum Tersedia.
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
    </div>

            @include('modal.modal_delete_form')
            @include('modal.modal_input_nihil')
            @endsection
