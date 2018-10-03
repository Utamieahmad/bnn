@extends('layouts.base_layout')
@section('title', 'Pusat Informasi (CC)')
@section('content')
  <div class="right_col" role="main">
          <div class="m-t-40">
            <div class="page-title">
              <div class="title_left">
                  <h3><i class="fa fa-volume-control-phone c-yelow"></i> PUSAT INFORMASI <small> <strong>PUSLITDATIN</strong></small></h3>
              </div>
              <div class="title_right">
                  <div class="pull-right">
                    {!! $breadcrumps !!}
                  </div>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title" style="border-bottom:none;">
                    <div class="flex">
                      <ul class="list-inline widget_profile_box">
                        <li>
                        <img alt="Logo SIN-BNN" src="{{asset('assets/images/BNN-LOGO-BIG.png')}}" class="img-circle profile_img">
                          
                        </li>
                        <li>
                          <a>
                            <i class="fa fa-volume-control-phone"></i>
                          </a>
                        </li>
                      </ul>
                    </div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content ">
                     <div class="col-md-12 col-sm-12 col-xs-12 p-t-10 p-b-20">
                         <div class="p-10" >
                             <div class="col-md-6 col-xs-6 col-xs-12">
                                 <h4>Pusat Informasi (CC)</h4>
                             </div>
                              <div class="col-md-6 col-xs-6 col-xs-12 text-right">
                                  <a href="{{route('print_page_puslitdatin',['call_center',$kondisi])}}" class="btn btn-lg btn-round btn-dark">
                                    <i class="fa fa-print"></i> Cetak
                                  </a>
                              </div>
                            <div class="clearfix"></div>
                             <div class="ln_solid"></div>
                             <div class="x_content">
                                @include('_templateFilter.puslit_callcenter_filter')
                                @php
                                  $i = $start_number;
                                @endphp
                                 <table id="datatable-responsive" class="table table-striped dt-responsive nowrap text-left" cellspacing="0" width="100%">
                                  <thead>
                                    <tr class="c-yelow bg-blue-navy">
                                      <th>ID</th>
                                      <th>Pengirim</th>
                                      <th>Penerima</th>
                                      <th>Subject</th>
                                      <th>Isi Konten </th>
                                      <th>Lampiran</th>
                                      <th>Tanggal Terima</th>
                                      <th>Tanggal Input</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                    @if(count($data))
                                      @foreach($data as $d)
                                        <tr>
                                            <td> {{$i}}</td>
                                            <td> {{$d->pengirim}}</td>
                                            <td> {{$d->penerima}}</td>
                                            <td> {{$d->subjek}} </td>
                                            <td> {{$d->konten}} </td>
                                            <td> {{$d->lampiran}} </td>
                                            <td> {{( $d->waktuterima ? date('d/m/Y H:i:s',strtotime($d->waktuterima)) : ''  )}} </td>
                                            <td> {{( $d->waktuinput ? date('d/m/Y H:i:s',strtotime($d->waktuinput)) : ''  )}} </td>
                                        </tr> 
                                        @php
                                          $i = $i+1;
                                        @endphp
                                      @endforeach
                                    @else
                                      <tr>
                                        <td colspan="8">
                                          <div class="alert-messages alert-warning">
                                            @if(isset($filter))
                                              @if(count($filter) >0)
                                                Data Pusat Informasi (CC) Yang Anda Cari Belum Tersedia.
                                              @else
                                                Data Pusat Informasi (CC) Belum Tersedia.
                                              @endif
                                            @else
                                                Data Pusat Informasi (CC) Belum Tersedia.
                                            @endif
                                            
                                          </div>
                                        </td>
                                      </tr>
                                    @endif
                                  </tbody>
                                </table>

                              <div class="pagination_wrap">
                                @if(count($data))
                                  {!! $pagination !!}
                                @endif
                              </div>
                           </div>
                       </div>
                   </div>
                  

                 </div>
                </div>
              </div>
            </div>


          </div>
<!-- Add a comment to this line -->
       </div>
@endsection
