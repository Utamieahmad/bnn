@extends('layouts.base_layout')
@section('title', 'Ubah Data Audit Laporan Hasil Audit')

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
              <h2>Form Ubah Data Audit Laporan Hasil Audit Inspektorat Utama</h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <br />
              <?php
                $name = "";
                $nip = "";
              ?>
               @if (session('status'))
                  @php
                    $session= session('status');
                  @endphp
                  <div class="alert alert-{{$session['status']}}">
                      {{ $session['message'] }}
                  </div>
              @endif

              @if (session('status_rekomendasi'))
                  @php
                    $session= session('status_rekomendasi');
                  @endphp
                  <div class="alert alert-{{$session['bidang_status']}}">
                      {{ $session['bidang_message'] }}
                  </div>

                  @if($session['error_rekomendasi_update'])
                     <div class="alert alert-error">
                      Error Update Rekomendasi : {{$session['error_rekomendasi_update']}}
                     </div>
                  @endif

                  @if($session['error_rekomendasi_new'])
                    Error Data Baru Rekomendasi : {{$session['error_rekomendasi_new']}}
                  @endif

                  @if($session['rekomendasi_delete'])
                    Error Delete Rekomendasi : {{$session['rekomendasi_delete']}}
                  @endif
              @endif


              <form action="{{route('update_irtama_audit')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on" onSubmit="irtamaAudit(this,event)">
                {{ csrf_field() }}

                <input type="hidden" name="satker_id" value="{{$satker_id}}">
                <input type="hidden" name="id" value="{{$data->id_lha}}">
                <div class="form-body">

                  <div class="form-group">
                    <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Nomor LHA</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input value="{{isset($data) ? $data->nomor_lha : ''}}" id="periode" name="nomor_lha" type="text" class="form-control">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="tahun_anggaran" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal LHA</label>
                    <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                      <input type='text' name="tanggal_lha" class="form-control col-md-7 col-xs-12 datetimepicker" value="{{isset($data) ? ( $data->tanggal_lha ? date('d/m/Y',strtotime($data->tanggal_lha)) : '') : ''}}"/>
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="nama_satker" class="col-md-3 control-label">Nama Satker</label>
                    @php
                    $nama_satker = $data->nama_satker;
                    $j = json_decode($nama_satker);
                    @endphp
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <select name="nama_satker" id="nama_satker" class="select2 form-control" tabindex="-1" aria-hidden="true">
                        <option selected="selected" value="">-- Pilih Satker--</option>
                        @if(count($satker))
                          @foreach($satker as $s => $sval)
                            <option value="{{$sval->nama}}" data-id="{{$sval->id}}" {{($j->id_satker == $sval->id ? 'selected=selected' :'' )}} >{{$sval->nama}}</option>
                          @endforeach
                        @endif

                      </select>
                    </div>
                    <input type="hidden" class="list_satker" name="list_satker" value=""/>
                  </div>

                  <div class="form-group">
                    <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun Anggaran</label>
                    <div class='col-md-6 col-sm-6 col-xs-6 input-group date year-only'>
                        <input type='text' name="tahun_anggaran" class="form-control" value="{{isset($data) ? $data->tahun_anggaran : ''}}"/>
                        <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                  </div>

                  <div class="clearfix"></div>
                    <div class="">
                      <div class="col-md-3 col-sm-3 col-xs-12 text-right">
                        <h2>Periode</h2>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12">

                      </div>
                    </div>
                    <div class="clearfix"></div>

                  <div class="form-group">
                      <label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">&nbsp;</label>

                      <div class='col-md-6 col-sm-6 col-xs-12'>
                        <div class="row">
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="row">
                              <label for="tgl_pelaksanaan" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Mulai</label>
                              <div class='col-md-12 col-sm-12 col-xs-12 input-group date date_start'>
                                  <input type='text' name="tgl_mulai" class="form-control" value="{{isset($data) ? ( $data->tgl_mulai ? date('d/m/Y',strtotime($data->tgl_mulai)) : '') : ''}}"/>
                                  <span class="input-group-addon">
                                  <span class="glyphicon glyphicon-calendar"></span>
                                  </span>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="row">
                              <label for="tgl_pelaksanaan" class="col-md-12 col-sm-12 col-xs-12 text-left">Tanggal Selesai</label>
                              <div class='col-md-12 col-sm-12 col-xs-12 input-group date date_end'>
                                  <input type='text' name="tgl_selesai" class="form-control" value="{{isset($data) ? ( $data->tgl_selesai ? date('d/m/Y',strtotime($data->tgl_selesai)) : '') : ''}}"/>
                                  <span class="input-group-addon">
                                  <span class="glyphicon glyphicon-calendar"></span>
                                  </span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
                    <div class="clearfix"></div>
                    <div class="">
                      <div class="col-md-3 col-sm-3 col-xs-12 text-right">
                        <h2>Peran</h2>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12">

                      </div>
                    </div>
                    <div class="clearfix"></div>

                  <div class="form-group">
                    <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Pengendali Mutu</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <?php
                      $mutu = [];
                      if(isset($data)){
                        if($data->pengendali_mutu){
                          $mutu = json_decode($data->pengendali_mutu,false);
                          if(count($mutu)>0){
                            $nip = $mutu[0]->nip;
                          }else{
                            $nip = "";
                          }
                        }
                      }else{
                        $nip = "";
                      }
                      ?>

                      <select data-tags="true" class="form-control select2" id="pengendali_mutu" name="satker_pengendali_mutu" onChange="satkerPengendali(this,'pengendali_mutu')">
                        <option value="">-- Pilih Pengendali Mutu --</option>

                        @if(isset($pegawai))
                          @if(count($pegawai) > 0)
                            @foreach($pegawai as $pkey => $pval)
                              <option value="{{$pval->nama}}" data-nip="{{$pval->nip}}" {{ ($pval->nip == $nip )? 'selected=selected' : ''}}>{{$pval->nama}}</option>
                            @endforeach
                          @endif
                        @endif

                      </select>
                      <input type="hidden" name="pengendali_mutu" class="pengendali_mutu" value=""/>
                    </div>
                  </div>


                  <div class="form-group">
                    <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Pengendali Teknis</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <?php
                      if(isset($data)){
                        if($data->pengendali_teknis){
                          $mutu = json_decode($data->pengendali_teknis,false);
                          if(count($mutu)>0){
                            $nip = $mutu[0]->nip;
                          }else{
                            $nip = "";
                          }
                        }
                      }else{
                        $nip = "";
                      }
                      ?>
                      <select data-tags="true" class="form-control select2" id="pengendali_mutu" name="satker_pengendali_teknis" onChange="satkerPengendali(this,'pengendali_teknis')">
                        <option value="">-- Pilih Pengendali Teknis --</option>
                         @if(isset($pegawai))
                          @if(count($pegawai) > 0)
                            @foreach($pegawai as $p2key => $p2val)
                              <option value="{{$p2val->nama}}" data-nip="{{$p2val->nip}}" {{ ($p2val->nip == $nip )? 'selected=selected' : ''}}>{{$p2val->nama}}</option>
                            @endforeach
                          @endif
                        @endif
                      </select>
                      <input type="hidden" name="pengendali_teknis" class="pengendali_teknis" value=""/>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="periode" class="col-md-3 col-sm-3 col-xs-12 control-label">Ketua Tim</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                       <?php
                      if(isset($data)){
                        if($data->ketua_tim){
                          $mutu = json_decode($data->ketua_tim,false);
                          if(count($mutu)>0){
                            $nip = $mutu[0]->nip;
                          }else{
                            $nip = "";
                          }
                        }
                      }else{
                        $nip = "";
                      }
                      ?>
                      <select data-tags="true" class="form-control select2" id="pengendali_mutu" name="satker_ketua_tim" onChange="satkerPengendali(this,'ketua_tim')">
                        <option value="">-- Pilih Ketua Tim --</option>
                        @if(isset($pegawai))
                          @if(count($pegawai) > 0)
                            @foreach($pegawai as $p3key => $p3val)
                              <option value="{{$p3val->nama}}" data-nip="{{$p3val->nip}}" {{ ($p3val->nip == $nip )? 'selected=selected' : ''}}>{{$p3val->nama}}</option>
                            @endforeach
                          @endif
                        @endif
                      </select>
                      <input type="hidden" name="ketua_tim" class="ketua_tim" value=""/>
                    </div>
                  </div>

                  <div class="form-group">
                  <label for="instansi" class="col-md-3 control-label">Anggota</label>
                  <?php
                    $anggota =[];
                    if(isset($data)){
                      if($data->meta_tim_anggota){
                        $meta = json_decode($data->meta_tim_anggota,false);
                        if(count($meta)>0){
                          foreach($meta as $m => $mval){
                            if(isset($mval->nip)){
                              $anggota[] = trim($mval->nip);
                            }
                          }
                        }else{
                          $anggota =[];
                        }
                      }
                    }else{
                      $anggota =[];
                    }

                    ?>

                    
                  <div class="col-md-6 col-sm-6 col-xs-12 m-t-10" >
                      <div class="meta-repeater">
                          <div data-repeater-list="coll_anggota">
                              @if(count($anggota)>0)
                                @for($i = 0 ; $i < count($anggota) ; $i++)
                                <div data-repeater-item="" class="mt-repeater-item">
                                    <div class="row mt-repeater-row">
                                        <div class="col-md-11">
                                              <select data-tags="true" class="form-control metaSatker" id="meta_anggota[][nama_anggota]" name="meta_anggota[][nama_anggota]" onchange="getMetaSatker(this)">
                                                <option value="" {{ count($anggota) >0 ? '' : 'selected=selected'}}>-- Pilih Anggota --</option>
                                                @if(isset($pegawai))
                                                  @if(count($pegawai) > 0)
                                                    @foreach($pegawai as $p2key => $p2val)
                                                      <option value="{{$p2val->nama}}" data-nip="{{$p2val->nip}}" {{ ($p2val->nip == $anggota[$i] )? 'selected=selected' : ''}}>{{$p2val->nama}}</option>
                                                    @endforeach
                                                  @endif
                                                @endif
                                              </select>
                                        </div>
                                        <div class="col-md-1">
                                            <button data-repeater-delete="" class="btn btn-danger mt-repeater-delete onDeleteRepeater m-0">
                                                <i class="fa fa-close"></i></button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endfor
                              @else
                                <div data-repeater-item="" class="mt-repeater-item">
                                    <div class="row mt-repeater-row">
                                        <div class="col-md-11">
                                              <select data-tags="true" class="form-control metaSatker" id="meta_anggota[][nama_anggota]" name="meta_anggota[][nama_anggota]" onchange="getMetaSatker(this)">
                                                <option value="" selected="selected">-- Pilih Anggota --</option>
                                                @if(isset($pegawai))
                                                  @if(count($pegawai) > 0)
                                                    @foreach($pegawai as $p2key => $p2val)
                                                      <option value="{{$p2val->nama}}" data-nip="{{$p2val->nip}}" >{{$p2val->nama}}</option>
                                                    @endforeach
                                                  @endif
                                                @endif
                                              </select>
                                        </div>
                                        <div class="col-md-1">
                                            <button data-repeater-delete="" class="btn btn-danger mt-repeater-delete onDeleteRepeater m-0">
                                                <i class="fa fa-close"></i></button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                              @endif
                          </div>
                          <button data-repeater-create="" class="btn btn-info mt-repeater-add onCreateRepeater" >
                              <i class="fa fa-plus"></i> Tambah Anggota</button>
                      </div>
                  </div>
                  <input type="hidden" name="meta_anggota" class="anggota_collection"/>
                  </div>

                <div class="form-group">
                  <label for="meta_kriminalitas" class="col-md-3 col-sm-3 col-xs-12 control-label">Data PTL</label>
                  <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="checkbox">
                      <label class="mt-checkbox col-md-4 col-sm-4 col-xs-12">
                        <input type="checkbox" value="{{isset($data) ? $data->data_ptl : ''}}" onClick="changeDataPtl(this)" name="data_ptl" {{isset($data) ? ($data->data_ptl == 'Y' ? 'checked=checked' : '')  : ''}} >
                        <span>&nbsp; Ya</span>
                      </label>
                    </div>
                  </div>
                </div>



              </div>
              </div>
              <div class="form-actions fluid">
                <div class="row">
                  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" class="btn btn-success">SIMPAN</button>
                    <a href="{{route('irtama_audit')}}" class="btn btn-primary" type="button">BATAL</a>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>


          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
              <div class="x_content">
                <div class="x_title">
                  <h2>Bidang Audit</h2>
                  <div class="clearfix"></div>
                </div>

                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#kinerja" id="profile-tab" role="tab" data-toggle="tab" aria-expanded="true">Kinerja</a>
                    </li>
                    <li role="presentation" class=""><a href="#keuangan" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Keuangan</a>
                    </li>
                    <li role="presentation" class=""><a href="#sdm" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">SDM</a>
                    </li>
                    <li role="presentation" class=""><a href="#sarana_prasarana" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Sarana dan Prasarana</a>
                    </li>

                  </ul>
                  <div id="myTabContent" class="tab-content">
                    @include('irtama.audit.tab_kinerja')
                    @include('irtama.audit.tab_keuangan')
                    @include('irtama.audit.tab_sdm')
                    @include('irtama.audit.tab_sarana_prasarana')
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>

@include ('modal.modal_input_rekomendasi_audit')
@include ('modal.modal_edit_rekomendasi')

<div class="modal fade bs-modal-sm" tabindex="-1" role="dialog" id="deleteBidang" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
          <form action="{{route('delete_bidang_lha')}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
            {{csrf_field()}}
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Hapus Data</h4>
        </div>
        <div class="modal-body">
          <div class="content">
            Apakah Anda ingin menghapus data ini ?
          </div>
          <div class="alert-message">

          </div>
        </div>
        <input type="hidden" class="target_id" value=""/>
        <div class="modal-footer">
          <input type="hidden" class="location_reload" value="{{'/'.\Request::route()->getPrefix().'/'.\Request::route()->getName()}}"/>
          <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
          <button type="button" class="btn btn-primary confirm" onclick="delete_row_form(event,this)">Ya</button>
        </div>
        <div class="modal-footer-loading alert">
          <p> Loading ... </p>
        </div>

      </form>
    </div>
  </div>
</div>
@endsection
