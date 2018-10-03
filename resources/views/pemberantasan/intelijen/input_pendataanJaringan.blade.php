@extends('layouts.base_layout')
@section('title', 'Tambah Pendataan Jaringan')
@section('content')
<div class="right_col" role="main">
  <div class="m-t-40">
    <div class="page-title">
      <div class="">
        <div class="">
        {!! (isset($breadcrumps) ? $breadcrumps : "" ) !!}
      </div>
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
          <h2>Form Tambah Pendataan LKN Direktorat Narkotika</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br />
          <form data-parsley-validate class="form-horizontal form-label-left" action="#" method="post" >
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{$id}}">
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Tanggal LKN</label>
              <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                @if($data_kasus['data']['kasus_tanggal'] != "kosong")
                <input type='text' name="tanggalLKN" value="{{ \Carbon\Carbon::parse($data_kasus['data']['kasus_tanggal'] )->format('d/m/Y') }}" class="form-control" disabled/>
                @else
                <input type="text" name="tanggalLKN" value="" class="form-control" disabled/>
                @endif
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Pelaksana</label>
              <div class="col-md-6 col-sm-6 col-xs-12">

                <select class="form-control select2" id="pelaksana" name="pelaksana" disabled>
                  @foreach($instansi as $wil)
                  <option value="{{$wil['id_instansi']}}" {{($wil['id_instansi'] == $data_kasus['data']['id_instansi']) ? 'selected="selected"':""}} >{{$wil['nm_instansi']}}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group group-lkn">
              @php
              if(strlen($data_kasus['data']['kasus_no']) > "20"){
                $lkn = splitLKN($data_kasus['data']['kasus_no']);
              } else {
                $lkn = [
                0 => "",
                1 => "",
                2 => "",
                3 => "",
                4 => "",
                5 => ""
                ];
              }
              @endphp

              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Nomor LKN</label>
              <div class="col-md-2 col-sm-1 col-xs-1">
                <input type="text" name="noLKN[0]" id="noLKN0" value="{{(($lkn[0] != "")? $lkn[0] : "LKN")}}"  class="form-control" readonly>
              </div>
              <div class="col-md-2 col-sm-1 col-xs-1">
                <input type="text" name="noLKN[1]" id="noLKN1" value="{{(($lkn[1] != "")? $lkn[1] : "")}}" class="form-control" disabled>
              </div>
              <div class="col-md-2 col-sm-2 col-xs-2">
                <select class="form-control" name="noLKN[2]" id="noLKN2" disabled>
                  <option value="P2" {{($lkn[2] == 'P2') ? 'selected' : ''}}>P2</option>
                  <option value="INTD" {{($lkn[2] == 'INTD') ? 'selected' : ''}}>INTD</option>
                  <option value="NAR" {{($lkn[2] == 'NAR') ? 'selected' : ''}}>NAR</option>
                  <option value="TPPU" {{($lkn[2] == 'TPPU') ? 'selected' : ''}}>TPPU</option>
                  <option value="BRNTS" {{($lkn[2] == 'BRNTS') ? 'selected' : ''}}>BRNTS</option>
                </select>
              </div>
              <div class="col-md-2 col-sm-1 col-xs-1">
                <input type="text" name="noLKN[3]" id="noLKN3" value="{{(isset($lkn[3])? $lkn[3] : "")}}"   class="form-control" readonly>
              </div>
              <div class="col-md-1 col-sm-1 col-xs-1">
                <input type="text" name="noLKN[4]" id="noLKN4" value="{{(isset($lkn[4])? $lkn[4] : "")}}"  class="form-control" readonly>
              </div>
              <div class="col-md-2 col-sm-2 col-xs-2 last-child">
                <input type="text" name="noLKN[5]" id="noLKN5" value="{{(isset($lkn[5])? $lkn[5] : "")}}"  class="form-control" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"  ></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="pelaksanaGenerate" name="kasus_no" value="{{$data_kasus['data']['kasus_no']}}"   class="form-control col-md-7 col-xs-12" disabled>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Penanggung Jawab (Penyidik)</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="penyidik" value="{{$data_kasus['data']['nama_penyidik']}}"  class="form-control col-md-7 col-xs-12" disabled>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Tanggal Kejadian</label>
              <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                @if($data_kasus['data']['kasus_tanggal'] != "kosong")
                <input type='text' name="tanggalKejadian" value="{{ \Carbon\Carbon::parse($data_kasus['data']['kasus_tanggal'])->format('d/m/Y') }}" class="form-control" disabled/>
                @else
                <input type="text" name="" value="" class="form-control" />
                @endif
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"  >TKP Kasus</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="tkp" value="{{$data_kasus['data']['kasus_tkp']}}"  class="form-control col-md-7 col-xs-12" disabled>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control select2 selectPropinsi" name="propinsi" disabled>
                  <option value="">-- Pilih Provinsi -- </option>
                  @foreach($propinsi['data'] as $p)
                  <option value="{{$p['id_wilayah']}}" {{($p['id_wilayah'] == $data_kasus['data']['kasus_tkp_idprovinsi']) ? 'selected="selected"':""}}> {{$p['nm_wilayah']}}</p>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control select2 selectKabupaten" name="kabupaten" disabled>
                    <option value="">-- Pilih Kabupaten --</option>
                    @if($kabupaten != "kosong")

                    @foreach($kabupaten['data'] as $k)
                    <option value="{{$k['id_wilayah']}}"  {{($k['id_wilayah'] == $data_kasus['data']['kasus_tkp_idkabkota']) ? 'selected="selected"':""}}> {{$k['nm_wilayah']}}</p>
                      @endforeach
                    @endif
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Modus Operandi</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" name="modus" value="{{$data_kasus['data']['modus_operandi']}}" class="form-control col-md-7 col-xs-12" disabled>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Negara Sumber Narkotika</label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control select2" name="negaraSumber" disabled>
                      <option value="">-- Pilih Negara --</option>
                      @foreach($negara as $n)
                      <option value="{{$n->kode}}"  {{($data_kasus['data']['kode_negarasumbernarkotika'] == $n->kode) ? 'selected="selected"':""}}> {{$n->nama_negara}}</p>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Jalur Masuk</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <select class="form-control select2 noSearch" name="jalurMasuk" disabled>
                        <option>Perlintasan Batas Darat</option>
                        <option>Perlintasan Batas Laut</option>
                        <option>Perlintasan Batas Udara</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Rute Asal</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" name="ruteAsal" value="{{$data_kasus['data']['rute_asal']}}" class="form-control col-md-7 col-xs-12" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Rute Transit</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" name="ruteTransit" value="{{$data_kasus['data']['rute_transit']}}"  class="form-control col-md-7 col-xs-12" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Rute Tujuan</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" name="ruteTujuan" value="{{$data_kasus['data']['rute_tujuan']}}"  class="form-control col-md-7 col-xs-12" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Kasus</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <select class="form-control select2" name="jenisKasus" disabled>
                        <option value="">-- Jenis Kasus -- </option>
                        @foreach($jenisKasus['data'] as $keyGroup => $jenis )
                        <optgroup label="{{$keyGroup}}">
                          @foreach($jenis as $key => $val)
                          <option value="{{$key}}" {{($data_kasus['data']['kasus_jenis'] == $key) ? 'selected="selected"':""}}>{{$val}}</option>
                          @endforeach
                        </optgroup>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Kelompok Kasus</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <select class="form-control select2 noSearch" name="kelompokKasus" disabled>
                        <option value="">-- Kelompok Kasus -- </option>
                        <option value="TPPU" {{($data_kasus['data']['kasus_kelompok'] == "TPPU") ? 'selected="selected"':""}}>TPPU</option>
                        <option value="NARKOTIKA" {{($data_kasus['data']['kasus_kelompok'] == "NARKOTIKA") ? 'selected="selected"':""}}>NARKOTIKA</option>
                      </select>
                    </div>
                  </div>

                </form>
              </div>

              <div class="x_content" style="min-height:200px;">

                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#tersangka" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Tersangka</a>
                    </li>
                    <li role="presentation" class=""><a href="#barang_bukti_narkotika" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Barang Bukti Narkotika</a>
                    </li>
                    <li role="presentation" class=""><a href="#barang_bukti_prekursor" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Barang Bukti Prekursor</a>
                    </li>
                    <li role="presentation" class=""><a href="#barang_bukti_aset" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Barang Bukti Aset</a>
                    </li>
                    <li role="presentation" class=""><a href="#barang_bukti_nonnarkotika" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Barang Bukti Non Narkotika</a>
                    </li>

                  </ul>
                  <div id="myTabContent" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active in" id="tersangka" aria-labelledby="home-tab">
                      <div class="tools pull-right m-b-10">
                        <!-- <button class="btn btn-success" data-toggle="modal" data-target="#add_modaltersangka">TAMBAH TERSANGKA</button> -->
                      </div>

                      <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                        <thead>
                          <tr role="row" class="heading">
                            <th > No </th>
                            <th width="25%"> Nama Tersangka </th>
                            <th width="15%"> Warga Negara </th>
                            <th width="15%"> Peran Tersangka </th>
                            <th width="15%"> Pendidikan Akhir </th>
                            <th width="15%"> Pekerjaan </th>
                            <th width="10%"> Actions </th>
                          </tr>
                        </thead>
                        @if(count($tersangka['data']))
                        <tbody>
                          @foreach($tersangka['data'] as $t)
                          @php $i = 1;@endphp
                          <tr>
                            <td>{{$i}}</td>
                            <td>{{$t['tersangka_nama']}}</td>
                            <td>{{$t['kode_warga_negara']}}</td>
                            <td>{{$t['kode_peran_tersangka']}}</td>
                            <td>{{$t['kode_pendidikan_akhir']}}</td>
                            <td>{{$t['kode_pekerjaan']}}</td>
                            <!-- <td class="actionTable">
                              <a href=""><i class="fa fa-pencil"></i></a>
                              <a href="#"><i class="fa fa-trash"></i></a>
                            </td> -->
                            <td>
                              <button class="btn btn-action"  data-target="{{$t['tersangka_id']}}" onClick="open_modalEditPeserta(event,this)"><i class="fa fa-search f-18"></i></button>
                            </td>
                          </tr>
                          @php $i = $i+1;@endphp
                          @endforeach
                          @else
                          <tr>
                            <td colspan="7">
                              <div class="">
                                Data Tersangka belum tersedia.
                              </div>
                            </td>
                          </tr>
                          @endif
                        </tbody>
                      </table>

                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="barang_bukti_narkotika" aria-labelledby="profile-tab">
                      <div class="tools pull-right m-b-10">
                        <!-- <button class="btn btn-success" data-toggle="modal" data-target="#add_modalNarkotika">TAMBAH BARANGBUKTI NARKOTIKA</button> -->
                      </div>
                      <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                        <thead>
                          <tr role="row" class="heading">
                            <th width="10%"> No </th>
                            <th width="10%"> Jenis Barang Bukti </th>
                            <th width="15%"> Nama Barang Bukti </th>
                            <th width="20%"> Jumlah Barang Bukti </th>
                            <!-- <th width="5%"> Actions </th> -->
                          </tr>
                        </thead>
                        @if(count($brgBuktiNarkotika['data']))
                        <tbody>
                          @php $i = 1; @endphp
                          @foreach($brgBuktiNarkotika['data'] as $brgBukti)
                          <tr>
                            <td>{{$i}}</td>
                            <td>{{$brgBukti['nm_jnsbrgbukti']}}</td>
                            <td>{{$brgBukti['nm_brgbukti']}}</td>
                            <td>{{$brgBukti['jumlah_barang_bukti']}} ( {{$brgBukti['kode_satuan_barang_bukti']}} )</td>
                            <!-- <td class="actionTable">
                              <a href=""><i class="fa fa-pencil"></i></a>
                              <a href="#"><i class="fa fa-trash"></i></a>
                            </td> -->
                          </tr>
                          @php $i = $i+1; @endphp
                          @endforeach
                          @else
                          <tr>
                            <td colspan="7">
                              <div class="">
                                Data barang bukti belum tersedia.
                              </div>
                            </td>
                          </tr>
                          @endif
                        </tbody>

                      </table>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="barang_bukti_prekursor" aria-labelledby="profile-tab">
                      <div class="tools pull-right m-b-10">
                        <!-- <button class="btn btn-success" data-toggle="modal" data-target="#add_modalPrekursor">TAMBAH BARANGBUKTI PREKURSOR</button> -->
                      </div>
                      <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                        <thead>
                          <tr role="row" class="heading">
                            <th width="2%"> No </th>
                            <th width="5%"> Jenis Barang Bukti </th>
                            <th width="15%"> Nama Barang Bukti </th>
                            <th width="20%"> Jumlah Barang Bukti </th>
                            <!-- <th width="10%"> Actions </th> -->
                          </tr>
                        </thead>
                        @if(count($brgBuktiPrekursor['data']))
                          @php $i = 1; @endphp
                          @foreach($brgBuktiPrekursor['data'] as $brgBukti)
                          <tr>
                            <td>{{$i}}</td>
                            <td>{{$brgBukti['nm_jnsbrgbukti']}}</td>
                            <td>{{$brgBukti['nm_brgbukti']}}</td>
                            <td>{{$brgBukti['jumlah_barang_bukti']}} ( {{$brgBukti['kode_satuan_barang_bukti']}} )</td>
                            <!-- <td class="actionTable">
                              <a href=""  ><i class="fa fa-pencil"></i></a>
                              <a href="#"><i class="fa fa-trash"></i></a>
                            </td> -->
                          </tr>
                          @php $i = $i+1; @endphp
                          @endforeach
                         @else
                          <tr>
                            <td colspan="7">
                              <div class="">
                                Data barang bukti belum tersedia.
                              </div>
                            </td>
                          </tr>
                          @endif
                      </table>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="barang_bukti_aset" aria-labelledby="profile-tab">
                      <div class="tools pull-right m-b-10">

                        <!-- <button class="btn btn-success" data-toggle="modal" data-target="#add_modalAsetbarang">TAMBAH ASET BARANG</button> -->

                      </div>
                      <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                        <thead>
                          <tr role="row" class="heading">
                            <th width="5%"> No </th>
                            <th width="25%"> Nama Aset </th>
                            <th width="5%"> Jumlah </th>
                            <th width="25%"> Konversi (Rupiah) </th>
                            <th width="20%"> Keterangan </th>
                            <!-- <th width="10%"> Actions </th> -->
                          </tr>
                        </thead>
                        @if(count($brgBuktiAsetBarang['data']))
                        <tbody>
                          @php $i = 1; @endphp
                          @foreach($brgBuktiAsetBarang['data'] as $val)
                          <tr>
                            <td>{{$i}}</td>
                            <td>{{$val['nama_aset']}}</td>
                            <td>{{$val['jumlah_barang_bukti_aset']}}</td>
                            <td>{{$val['nilai_aset']}}</td>
                            <td>{{$val['keterangan']}}</td>
                            <!-- <td class="actionTable">
                              <a href=""  ><i class="fa fa-pencil"></i></a>
                              <a href="#"><i class="fa fa-trash"></i></a>
                            </td> -->
                          </tr>
                          @php $i = $i+1; @endphp
                          @endforeach
                        @else
                          <tr>
                            <td colspan="7">
                              <div class="">
                                Data barang bukti belum tersedia.
                              </div>
                            </td>
                          </tr>
                        @endif
                        </tbody>

                      </table>

                      <div class="tools pull-right m-b-10">

                        <!-- <button class="btn btn-success" data-toggle="modal" data-target="#add_modalAsettanah">TAMBAH ASET TANAH</button> -->

                      </div>
                      <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                        <thead>
                          <tr role="row" class="heading">
                            <th width="5%"> No </th>
                            <th width="25%"> Nama Aset </th>
                            <th width="5%"> Jumlah </th>
                            <th width="25%"> Konversi (Rupiah) </th>
                            <th width="20%"> Keterangan </th>
                            <!-- <th width="10%"> Actions </th> -->
                          </tr>
                        </thead>
                        @if(count($brgBuktiAsetTanah['data']))
                        <tbody>
                          @php $i = 1; @endphp
                          @foreach($brgBuktiAsetTanah['data'] as $val)
                          <tr>
                            <td>{{$i}}</td>
                            <td>{{$val['nama_aset']}}</td>
                            <td>{{$val['jumlah_barang_bukti_aset']}}</td>
                            <td>{{$val['nilai_aset']}}</td>
                            <td>{{$val['keterangan']}}</td>
                            <!-- <td class="actionTable">
                              <a href=""  ><i class="fa fa-pencil"></i></a>
                              <a href="#"><i class="fa fa-trash"></i></a>
                            </td> -->
                          </tr>
                          @php $i = $i+1; @endphp
                          @endforeach
                          @else
                          <tr>
                            <td colspan="7">
                              <div class="">
                                Data barang bukti belum tersedia.
                              </div>
                            </td>
                          </tr>
                        @endif
                        </tbody>
                      </table>

                      <div class="tools pull-right m-b-10">

                        <!-- <button class="btn btn-success" data-toggle="modal" data-target="#add_modalAsetbangunan">TAMBAH ASET BANGUNAN</button> -->

                      </div>
                      <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                        <thead>
                          <tr role="row" class="heading">
                            <th width="5%"> No </th>
                            <th width="25%"> Nama Aset </th>
                            <th width="5%"> Jumlah </th>
                            <th width="25%"> Konversi (Rupiah) </th>
                            <th width="20%"> Keterangan </th>
                           <!--  <th width="10%"> Actions </th> -->
                          </tr>
                        </thead>
                        @if(count($brgBuktiAsetBangunan['data']))
                        <tbody>
                          @php $i = 1; @endphp
                          @foreach($brgBuktiAsetBangunan['data'] as $val)
                          <tr>
                            <td>{{$i}}</td>
                            <td>{{$val['nama_aset']}}</td>
                            <td>{{$val['jumlah_barang_bukti_aset']}}</td>
                            <td>{{$val['nilai_aset']}}</td>
                            <td>{{$val['keterangan']}}</td>
                            <!-- <td class="actionTable">
                              <a href=""  ><i class="fa fa-pencil"></i></a>
                              <a href="#"><i class="fa fa-trash"></i></a>
                            </td> -->
                          </tr>
                          @php $i = $i+1; @endphp
                          @endforeach
                       @else
                          <tr>
                            <td colspan="7">
                              <div class="">
                                Data barang bukti belum tersedia.
                              </div>
                            </td>
                          </tr>
                        @endif
                        </tbody>
                      </table>

                      <div class="tools pull-right m-b-10">

                        <!-- <button class="btn btn-success" data-toggle="modal" data-target="#add_modalAsetlogam">TAMBAH ASET LOGAM MULIA</button> -->

                      </div>
                      <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                        <thead>
                          <tr role="row" class="heading">
                            <th width="5%"> No </th>
                            <th width="25%"> Nama Aset </th>
                            <th width="5%"> Jumlah </th>
                            <th width="25%"> Konversi (Rupiah) </th>
                            <th width="20%"> Keterangan </th>
                            <!-- <th width="10%"> Actions </th> -->
                          </tr>
                        </thead>
                        @if(count($brgBuktiAsetLogam['data']))
                        <tbody>
                          @php $i = 1; @endphp
                          @foreach($brgBuktiAsetLogam['data'] as $val)
                          <tr>
                            <td>{{$i}}</td>
                            <td>{{$val['nama_aset']}}</td>
                            <td>{{$val['jumlah_barang_bukti_aset']}}</td>
                            <td>{{$val['nilai_aset']}}</td>
                            <td>{{$val['keterangan']}}</td>
                            <!-- <td class="actionTable">
                              <a href=""  ><i class="fa fa-pencil"></i></a>
                              <a href="#"><i class="fa fa-trash"></i></a>
                            </td> -->
                          </tr>
                          @php $i = $i+1; @endphp
                          @endforeach
                       @else
                          <tr>
                            <td colspan="7">
                              <div class="">
                                Data barang bukti belum tersedia.
                              </div>
                            </td>
                          </tr>
                        @endif
                        </tbody>
                      </table>

                      <div class="tools pull-right m-b-10">

                        <!-- <button class="btn btn-success" data-toggle="modal" data-target="#add_modalAsetuang">TAMBAH ASET UANG TUNAI</button> -->

                      </div>
                      <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                        <thead>
                          <tr role="row" class="heading">
                            <th width="5%"> No </th>
                            <th width="25%"> Nama Aset </th>
                            <th width="5%"> Jumlah </th>
                            <th width="25%"> Konversi (Rupiah) </th>
                            <th width="20%"> Keterangan </th>
                            <!-- <th width="10%"> Actions </th> -->
                          </tr>
                        </thead>
                        @if(count($brgBuktiAsetUang['data']))
                        <tbody>
                          @php $i = 1; @endphp
                          @foreach($brgBuktiAsetUang['data'] as $val)
                          <tr>
                            <td>{{$i}}</td>
                            <td>{{$val['nama_aset']}}</td>
                            <td>{{$val['jumlah_barang_bukti_aset']}}</td>
                            <td>{{$val['nilai_aset']}}</td>
                            <td>{{$val['keterangan']}}</td>
                            <!-- <td class="actionTable">
                              <a href=""  ><i class="fa fa-pencil"></i></a>
                              <a href="#"><i class="fa fa-trash"></i></a>
                            </td> -->
                          </tr>
                          @php $i = $i+1; @endphp
                          @endforeach
                        @else
                          <tr>
                            <td colspan="7">
                              <div class="">
                                Data barang bukti belum tersedia.
                              </div>
                            </td>
                          </tr>
                        @endif
                        </tbody>
                      </table>

                      <div class="tools pull-right m-b-10">

                        <!-- <button class="btn btn-success" data-toggle="modal" data-target="#add_modalAsetrekening">TAMBAH ASET REKENING</button> -->

                      </div>
                      <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                        <thead>
                          <tr role="row" class="heading">
                            <th width="5%"> No </th>
                            <th width="25%"> Nama Aset </th>
                            <th width="5%"> Jumlah </th>
                            <th width="25%"> Konversi (Rupiah) </th>
                            <th width="20%"> Keterangan </th>
                            <!-- <th width="10%"> Actions </th> -->
                          </tr>
                        </thead>
                        @if(count($brgBuktiAsetRekening['data']))
                        <tbody>
                          @php $i = 1; @endphp
                          @foreach($brgBuktiAsetRekening['data'] as $val)
                          <tr>
                            <td>{{$i}}</td>
                            <td>{{$val['nama_aset']}}</td>
                            <td>{{$val['jumlah_barang_bukti_aset']}}</td>
                            <td>{{$val['nilai_aset']}}</td>
                            <td>{{$val['keterangan']}}</td>
                            <!-- <td class="actionTable">
                              <a href=""  ><i class="fa fa-pencil"></i></a>
                              <a href="#"><i class="fa fa-trash"></i></a>
                            </td> -->
                          </tr>
                          @php $i = $i+1; @endphp
                          @endforeach
                        @else
                          <tr>
                            <td colspan="7">
                              <div class="">
                                Data barang bukti belum tersedia.
                              </div>
                            </td>
                          </tr>
                        @endif
                        </tbody>

                      </table>

                      <div class="tools pull-right m-b-10">

                        <button class="btn btn-success" data-toggle="modal" data-target="#add_modalAsetsurat">TAMBAH ASET SURAT BERHARGA</button>

                      </div>
                      <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                        <thead>
                          <tr role="row" class="heading">
                            <th width="5%"> No </th>
                            <th width="25%"> Nama Aset </th>
                            <th width="5%"> Jumlah </th>
                            <th width="25%"> Konversi (Rupiah) </th>
                            <th width="20%"> Keterangan </th>
                            <!-- <th width="10%"> Actions </th> -->
                          </tr>
                        </thead>
                        @if(count($brgBuktiAsetSurat['data']))
                        <tbody>
                          @php $i = 1; @endphp
                          @foreach($brgBuktiAsetSurat['data'] as $val)
                          <tr>
                            <td>{{$i}}</td>
                            <td>{{$val['nama_aset']}}</td>
                            <td>{{$val['jumlah_barang_bukti_aset']}}</td>
                            <td>{{$val['nilai_aset']}}</td>
                            <td>{{$val['keterangan']}}</td>
                            <!-- <td class="actionTable">
                              <a href=""  ><i class="fa fa-pencil"></i></a>
                              <a href="#"><i class="fa fa-trash"></i></a>
                            </td> -->
                          </tr>
                          @php $i = $i+1; @endphp
                          @endforeach
                        @else
                          <tr>
                            <td colspan="7">
                              <div class="">
                                Data barang bukti belum tersedia.
                              </div>
                            </td>
                          </tr>
                        @endif
                        </tbody>

                      </table>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="barang_bukti_nonnarkotika" aria-labelledby="profile-tab">
                      <div class="tools pull-right m-b-10">
                        <!-- <button class="btn btn-success" data-toggle="modal" data-target="#add_modalNonnarkotika">TAMBAH BARANGBUKTI NON NARKOTIKA</button> -->
                      </div>

                      <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
                        <thead>
                          <tr role="row" class="heading">
                            <th width="2%"> No </th>
                            <th width="15%"> Nama Barang Bukti </th>
                            <th width="20%"> Jumlah Barang Bukti </th>
                            <!-- <th width="10%"> Actions </th> -->
                          </tr>
                        </thead>
                        @if(count($brgBuktiNonNarkotika['data']))
                        <tbody>
                          @php $i = 1; @endphp
                          @foreach($brgBuktiNonNarkotika['data'] as $brgBukti)
                          <tr>
                            <td>{{$i}}</td>
                            <td>{{$brgBukti['keterangan']}}</td>
                            <td>{{$brgBukti['jumlah_barang_bukti']}} ( {{$brgBukti['kode_satuan_barang_bukti']}} )</td>
                            <!-- <td class="actionTable">
                              <a href=""  ><i class="fa fa-pencil"></i></a>
                              <a href="#"><i class="fa fa-trash"></i></a>
                            </td> -->
                          </tr>
                          @php $i = $i+1; @endphp
                          @endforeach
                        @else
                          <tr>
                            <td colspan="7">
                              <div class="">
                                Data barang bukti belum tersedia.
                              </div>
                            </td>
                          </tr>
                        @endif
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

              </div>

              <div class="x_content">
                <hr/>
                <h2>Data Jaringan Narkotika</h2>
                <form class="form-horizontal form-label-left" action="{{URL('/pemberantasan/dir_intelijen/input_pendataan_jaringan')}}" enctype="multipart/form-data" method="post">
                  {{ csrf_field() }}

                  {{-- <div class="form-group">
                    <label class="control-label col-md-3">Keterlibatan Jaringan</label>
                    <div class="col-md-9">
                      <div class="radio">
                        <label class="mt-radio col-md-2"> <input checked type="radio" id="keterlibatan_jaringan_ya" value="Y" name="keterlibatan_jaringan">
                          <span>Ya</span>
                        </label>
                        <label class="mt-radio col-md-2"> <input type="radio" id="keterlibatan_jaringan_tidak" value="T" name="keterlibatan_jaringan">
                          <span>Tidak</span>
                        </label>
                      </div>
                    </div>
                  </div> --}}

                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Jaringan</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" name="nama_jaringan" value="" class="form-control col-md-7 col-xs-12">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Komandan Jaringan</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" name="nama_komandan_jaringan" value="" class="form-control col-md-7 col-xs-12">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3">Jaringan</label>
                    <div class="col-md-9">
                      <div class="radio">
                        <label class="mt-radio col-md-2"> <input data-id="jaringanNasional" data-hide="jaringanInternasional" type="radio" id="kode_jenisjaringan_nas" value="Nasional" name="kode_jenisjaringan" onClick="jenis_jaringan(this)">
                          <span>Nasional</span>
                        </label>
                        <label class="mt-radio col-md-2"> <input data-id="jaringanInternasional" data-hide="jaringanNasional" type="radio" id="kode_jenisjaringan_inter" value="Internasional" name="kode_jenisjaringan"  onClick="jenis_jaringan(this)">
                          <span>Internasioal</span>
                        </label>
                      </div>
                    </div>
                  </div>

                  <div style="display: none" id="jaringanNasional">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Provinsi Asal Wilayah Jaringan</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <select class="form-control select2 selectPropinsi" name="asal_wilayah_idprovinsi">
                          <option value="">-- Pilih Provinsi -- </option>
                          @foreach($propinsi['data'] as $p)
                          <option value="{{$p['id_wilayah']}}" {{($p['id_wilayah'] == $data_kasus['data']['kasus_tkp_idprovinsi']) ? 'selected="selected"':""}}> {{$p['nm_wilayah']}}</p>
                            @endforeach
                          </select>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Kab/Kota Asal Wilayah Jaringan</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="form-control select2 selectKabupaten" name="asal_wilayah_idkabkota">
                            <option value="">-- Pilih Kabupaten --</option>
                            @if($kabupaten != "kosong")

                            @foreach($kabupaten['data'] as $k)
                            <option value="{{$k['id_wilayah']}}"  {{($k['id_wilayah'] == $data_kasus['data']['kasus_tkp_idkabkota']) ? 'selected="selected"':""}}> {{$k['nm_wilayah']}}</p>
                              @endforeach
                              @endif
                            </select>
                          </div>
                        </div>
                      </div>

                      <div style="display: none" id="jaringanInternasional">
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Asal Negara Jaringan</label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="form-control select2" name="asal_negara_jaringan">
                              <option value="">-- Pilih Negara --</option>
                              @foreach($negara as $n)
                              <option value="{{$n->kode}}"  {{($data_kasus['data']['kode_negarasumbernarkotika'] == $n->kode) ? 'selected="selected"':""}}> {{$n->nama_negara}}</p>
                                @endforeach
                              </select>
                            </div>
                          </div>
                      </div>

                        <div class="form-group">
                          <label class="control-label col-md-3">Keterlibatan Jaringan Lain</label>
                          <div class="col-md-9">
                            <div class="radio">
                              <label class="mt-radio col-md-2"> <input type="radio" id="keterkaitan_jaringan_lain_ya" value="Y" name="keterkaitan_jaringan_lain">
                                <span>Ya</span>
                              </label>
                              <label class="mt-radio col-md-2"> <input type="radio" id="keterkaitan_jaringan_lain_tidak" value="T" name="keterkaitan_jaringan_lain">
                                <span>Tidak</span>
                              </label>
                              <span class="help-block"></span>
                            </div>
                          </div>
                        </div>

                        {{-- <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Jaringan Terkait</label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="nama_jaringan_terkait" value="" class="form-control col-md-7 col-xs-12">
                          </div>
                        </div> --}}

                        <div class="form-group">
                            <label for="instansi" class="col-md-3 control-label">Nama Jaringan Terkait</label>
                            <div class="col-md-9">
                                <div class="mt-repeater">
                                    <div data-repeater-list="nama_jaringan_terkait">
                                        <div data-repeater-item="" class="mt-repeater-item">
                                            <div class="row mt-repeater-row">
                                                <div class="col-md-4">
                                                    <label class="control-label">Nama Jaringan</label>
                                                    <input name="nama_jaringan_terkait[0][nama_jaringan]" type="text" class="form-control"> </div>
                                                <div class="col-md-4">
                                                    <label class="control-label">Peran Jaringan</label>
                                                    <select class="form-control" name="nama_jaringan_terkait[0][peran_jaringan]">
                                                      <option value="">-- Peran Jaringan --</option>
                                                      <option value="penyandang_dana">Penyandang Dana</option>
                                                      <option value="pengendali">Pengendali</option>
                                                      <option value="kurir">Kurir</option>
                                                      <option value="pengawas">Pengawas / Checker</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-1">
                                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">
                                                        <i class="fa fa-close"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="javascript:;" data-repeater-create="" class="btn btn-info mt-repeater-add">
                                        <i class="fa fa-plus"></i> Tambah Jaringan</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="file_upload" class="col-md-3 control-label">File Upload</label>
                            <div class="col-md-6">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="input-group input-large">
                                        <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                                            <i class="fa fa-file fileinput-exists"></i>&nbsp;
                                            <span class="fileinput-filename"> </span>
                                        </div>
                                        <span class="input-group-addon btn default btn-file">
                                            <span class="fileinput-new"> Pilih Berkas </span>
                                            <span class="fileinput-exists"> Ganti </span>
                                            <input type="file" name="file_upload"> </span>
                                        <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
                                    </div>
                                </div>
                                <span class="help-block">
                                </span>
                            </div>
                        </div>

                        <input type="hidden" name="nomor_lkn" value="{{$data_kasus['data']['kasus_no']}}">
                        <input type="hidden" name="id_kasus" value="{{$data_kasus['data']['kasus_id']}}">
                        <div class="form-group m-t-20">
                          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="submit" class="btn btn-success">KIRIM</button>
                            <a href="{{route('pendataan_jaringan')}}" class="btn btn-primary" type="button">BATAL</a>
                          </div>
                        </div>
                      </form>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
          {{--  @include('modal.modal_add_tersangka')
          @include('modal.modal_add_narkotika')
          @include('modal.modal_add_prekursor')
          @include('modal.modal_add_asetbarang')
          @include('modal.modal_add_asettanah')
          @include('modal.modal_add_asetbangunan')
          @include('modal.modal_add_asetlogam')
          @include('modal.modal_add_asetuang')
          @include('modal.modal_add_asetrekening')
          @include('modal.modal_add_asetsurat')
          @include('modal.modal_add_nonnarkotika') --}}
          @include('pemberantasan.intelijen.modal_viewTersangka')
          @endsection
