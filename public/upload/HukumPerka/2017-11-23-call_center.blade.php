@extends('layouts.base_layout')

@section('content')
	<div class="right_col" role="main">
          <div class="m-t-40">
            <div class="page-title">
              <div class="title_left">
                  <h3><i class="fa fa-volume-control-phone c-yelow"></i> CALL CENTER <small> <strong>PUSLITDATIN</strong></small></h3>
              </div>
              <div class="title_right">
                  <ul class="page-breadcrumb breadcrumb pull-right">
                      <li>
                          Beranda
                      </li>
                      <li>
                          <a href="javascript:;" class="c-grey">Puslitdatin</a>
                      </li>
                      <li class="active">
                          Call Center
                      </li>
                  </ul>
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
                             <div class="">
                                 <h4>Data Call Center</h4>
                             </div>
                             <div class="ln_solid"></div>
                             <div class="x_content">
                             @if(count($data_call_center))
                                 <table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
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
                                  @php $i = 1; @endphp
                                  @foreach($data_call_center as $d)
                                      <tr>
                                          <td> {{$i}}</td>
                                          <td> {{$d->pengirim}}</td>
                                          <td> {{$d->penerima}}</td>
                                          <td> {{$d->subjek}} </td>
                                          <td> {{$d->konten}} </td>
                                          <td> {{$d->lampiran}} </td>
                                          <td> {{$d->waktuterima}} </td>
                                          <td> {{$d->waktuinput}} </td>
                                    @php $i = $i+1; @endphp
                                    @endforeach
                                      </tr> 
                                  </tbody>
                                </table>
                            @else
                              <div class="alert alert-warning">
                                Data Kasus belum tersedia.
                              </div>
                            @endif
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
