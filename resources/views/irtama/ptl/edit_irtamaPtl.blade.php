@extends('layouts.base_layout')
@section('title', 'Ubah Data Pemantauan Tindak Lanjut')

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
          <h2>Form Ubah Data Pemantauan Tindak Lanjut Inspektorat Utama</h2>
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
          <form action="{{route('update_irtama_ptl')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{(isset($data) ? $data->id_ptl : '')}}">

            <div class="form-body">

              <div class="form-group">
                <label for="no_sprin" class="col-md-3 col-sm-3 col-xs-12 control-label">No LHA</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <p class="form-p"> {{(isset($data) ? $data->nomor_lha :'') }}</p>
                </div>
              </div>

              <div class="form-group">
                <label for="no_sprin" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal LHA</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <p class="form-p"> {{(isset($data) ? ($data->tanggal_lha ? date('d/m/Y',strtotime($data->tanggal_lha)) : '')  :'') }}</p>
                </div>
              </div>

              <div class="form-group">
                <label for="no_sprin" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Satker</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  @php $satker_name = "" ; @endphp
                  @if($data->nama_satker)
                    @php
                      $j = json_decode($data->nama_satker);
                      $satker_name = $j->satker;
                    @endphp
                  @endif
                 
                  <p class="form-p"> {{$satker_name }} </p>
                </div>
              </div>

              <div class="form-group">
                <label for="no_sprin" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun Anggaran Satker</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <p class="form-p"> {{(isset($data) ? $data->tahun_anggaran :'') }}</p>
                </div>
              </div>

              <div class="form-group">
                <label for="no_sprin" class="col-md-3 col-sm-3 col-xs-12 control-label"><h4> Periode <h4></label>
                <div class="col-md-6 col-sm-6 col-xs-12">

                </div>
              </div>

              <div class="form-group">
                <label for="no_sprin" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Mulai</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <p class="form-p"> {{( isset($data) ? ( $data->tgl_mulai ? date('d/m/Y',strtotime($data->tgl_mulai)) : '' ) :'') }}</p>
                </div>
              </div>


              <div class="form-group">
                <label for="no_sprin" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Selesai</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <p class="form-p"> {{(isset($data) ? ( $data->tgl_selesai ? date('d/m/Y',strtotime($data->tgl_selesai)) : '' ) :'') }}</p>
                </div>
              </div>



              <div class="form-group">
                <label for="no_sprin" class="col-md-3 col-sm-3 col-xs-12 control-label"><h4> Peran <h4></label>
                <div class="col-md-6 col-sm-6 col-xs-12">

                </div>
              </div>

              <div class="form-group">
                <label for="no_sprin" class="col-md-3 col-sm-3 col-xs-12 control-label">Pengendali Mutu</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <?php
                    $nama = "";
                    if(isset($data)){
                      if($data->pengendali_mutu){
                        $decode = json_decode($data->pengendali_mutu,false);
                        if(isset($decode[0])){
                          $nama = $decode[0]->nama;
                        }else{
                          $nama = "";
                        }
                      }else{
                        $nama = "";
                      }
                    }else{
                      $nama = "";
                    }
                  ?>
                  <p class="form-p">{{$nama}}</p>
                </div>
              </div>

              <div class="form-group">
                <label for="no_sprin" class="col-md-3 col-sm-3 col-xs-12 control-label">Pengendali Teknis</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                   <?php
                    $nama = "";
                    if(isset($data)){
                      if($data->pengendali_teknis){
                        $decode = json_decode($data->pengendali_teknis,false);
                        if(isset($decode[0])){
                          $nama = $decode[0]->nama;
                        }else{
                          $nama = "";
                        }
                      }else{
                        $nama = "";
                      }
                    }else{
                      $nama = "";
                    }
                  ?>
                  <p class="form-p">{{$nama}}</p>
                </div>
              </div>

              <div class="form-group">
                <label for="no_sprin" class="col-md-3 col-sm-3 col-xs-12 control-label">Ketua Tim</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <?php
                    $nama = "";
                    if(isset($data)){
                      if($data->ketua_tim){
                        $decode = json_decode($data->ketua_tim,false);
                        if(isset($decode[0])){
                          $nama = $decode[0]->nama;
                        }else{
                          $nama = "";
                        }
                      }else{
                        $nama = "";
                      }
                    }else{
                      $nama = "";
                    }
                  ?>
                  <p class="form-p">{{$nama}}</p>
                </div>
              </div>

              <div class="form-group">
                <label for="no_sprin" class="col-md-3 col-sm-3 col-xs-12 control-label">Anggota</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <?php
                    $nama = "";
                    if(isset($data)){
                      if($data->ketua_tim){
                        $decode = json_decode($data->meta_tim_anggota,false);
                        if(count($decode) > 0 ){
                          $nama = "<ul class='p-l-10'>";
                          for($i = 0; $i < count($decode); $i++){
                            $nama .= "<li>".$decode[$i]->nama."</li>";
                          }
                          $nama .= "</ul>";
                        }
                      }else{
                        $nama .= "";
                      }
                    }else{
                      $nama .= "";
                    }
                    
                  ?>
                  <p class="form-p">{!! $nama !!}</p>
                </div>
              </div>

          </form>
          <div class="col-md-12 col-sm-12 col-xs-12 m-t-20">
            <div class="x_panel">
              <div class="x_content">

                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#kinerja" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Kinerja</a>
                    </li>
                    <li role="presentation" class=""><a href="#keuangan" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Keuangan</a>
                    </li>
                    <li role="presentation" class=""><a href="#sdm" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">SDM</a>
                    </li>
                    <li role="presentation" class=""><a href="#sarana" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Sarana dan Prasarana</a>
                    </li>
                  </ul>

                  <div id="myTabContent" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active in" id="kinerja" aria-labelledby="home-tab">
                      <div class="tools">
                        @include('irtama.ptl.table_tab_content.kinerja')
                      </div>
                    </div>

                    <div role="tabpanel" class="tab-pane fade" id="keuangan" aria-labelledby="home-tab">
                      <div class="tools">
                        @include('irtama.ptl.table_tab_content.keuangan')
                      </div>
                    </div>

                    <div role="tabpanel" class="tab-pane fade" id="sdm" aria-labelledby="home-tab">
                      <div class="tools">
                       @include('irtama.ptl.table_tab_content.sdm')
                      </div>
                    </div>

                    <div role="tabpanel" class="tab-pane fade" id="sarana" aria-labelledby="home-tab">
                      <div class="tools">
                        @include('irtama.ptl.table_tab_content.sarana_prasarana')
                      </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@include('irtama.ptl.table_tab_content.modal_ptl')
@include('modal.modal_delete_form')
@endsection
