@extends('Layouts.layoutsGlobal')

@section('content')
<div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <ul class="page-breadcrumb breadcrumb">

                  <li>
                      Direktorat Narkotika
                  </li>
                  <li >
                      Pendataan LKN (Kasus, tersangka, penerapan pasal, dan barang bukti)
                  </li>
                  <li class="active">
                      Tambah
                  </li>
              </ul>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <div>
                    <i class="fa fa-plus-square"></i> Form Entri</div>
                    <div class="ln_solid"></div>
                    <div class="clearfix"></div>
                  </div>

                    <form  data-parsley-validate class="form-horizontal">
                      <div class="form-group">
                        <div class="col-md-2" >
                        <label  for="first-name">Tanggal LKN
                        </label>
                      </div>
                        <div class="col-md-3">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>

                      <div class="form-group">
                      <label class="col-md-2">Pelakasana</label>
                      <div class="col-md-6">
                        <select class="form-control">
                          <option>Choose option</option>
                          <option>Option one</option>
                          <option>Option two</option>
                          <option>Option three</option>
                          <option>Option four</option>
                        </select>
                      </div>
                      </div>

                      <div class="form-group">
                        <div class="col-md-2">
                          <label >Nomor LKN
                          </label>
                        </div>
                          <div class="col-md-1" >
                          <input name="vlkn_tipe" value="LKN" class="form-control" readonly>
                          </div>
                          <div class="col-md-1" >
                          <input name="vlkn_no" class="form-control mask_number" value="1234" maxlength="4">
                          </div>
                          <div class="col-md-2">
                            <select class="form-control">
                              <option>Choose option</option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                          <div class="col-md-1">
                          <input value="x" readonly name="vlkn_bulan" class="form-control">
                          </div>
                          <div class="col-md-1">
                          <input value="2017" readonly name="vlkn_tahun" value="2016" class="form-control">
                          </div>
                          <div class="col-md-2">
                           <input readonly name="vlkn_wilayah" value="BNN PUSAT" class="form-control">
                          </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-2">
                        <label hidden="" >Nomor LKN
                        </label>
                      </div>
                        <div class="col-md-5">
                          <input type="text" id="last-name" name="last-name" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-md-2">
                        <label  >Penanggung Jawab (Penyidik)
                        </label>
                        </div>
                        <div class="col-md-4">
                          <input type="text" required="required" class="form-control col-md-6">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-2">
                        <label  >Tanggal Kejadian
                        </label>
                        </div>
                        <div class="col-md-3">
                          <input type="text" required="required" class="form-control col-md-6">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-2">
                        <label  >TKP Kasus
                        </label>
                        </div>
                        <div class="col-md-4">
                          <input type="text" required="required" class="form-control col-md-6">
                        </div>
                      </div>

                    <div class="form-group">
                      <div class="col-md-2">
                      <label hidden="" >Provinsi</label>
                    </div>
                      <div class="col-md-3">
                        <select class="form-control">
                          <option>Provinsi</option>
                          <option>Option one</option>
                          <option>Option two</option>
                          <option>Option three</option>
                          <option>Option four</option>
                        </select>
                      </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-2">
                        <label hidden="" >Kabupaten / kota</label>
                      </div>
                        <div class="col-md-3">
                          <select class="form-control">
                            <option>Kabupaten / Kota </option>
                            <option>Option one</option>
                            <option>Option two</option>
                            <option>Option three</option>
                            <option>Option four</option>
                          </select>
                        </div>
                        </div>

                        <div class="form-group">
                          <div class="col-md-2">
                          <label  >Modus Operandi
                          </label>
                          </div>
                          <div class="col-md-4">
                            <input type="text" required="required" class="form-control col-md-6">
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-2">
                          <label  >Negara Sumber Narkotika</label>
                        </div>
                          <div class="col-md-3">
                            <select class="form-control">
                              <option>Negara </option>
                              <option>Option one</option>
                              <option>Option two</option>
                              <option>Option three</option>
                              <option>Option four</option>
                            </select>
                          </div>
                          </div>

                          <div class="form-group">
                            <div class="col-md-2">
                            <label  >Rute Asal
                            </label>
                            </div>
                            <div class="col-md-4">
                              <input type="text" required="required" class="form-control col-md-6">
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-md-2">
                            <label  >Rute Transit
                            </label>
                            </div>
                            <div class="col-md-4">
                              <input type="text" required="required" class="form-control col-md-6">
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-md-2">
                            <label  >Rute Tujuan
                            </label>
                            </div>
                            <div class="col-md-4">
                              <input type="text" required="required" class="form-control col-md-6">
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-md-2">
                            <label  >Kelompok Kasus
                            </label>
                          </div>
                            <div class="col-md-3">
                              <select class="form-control">
                                <option>Kelompok Kasus </option>
                                <option>Option one</option>
                                <option>Option two</option>
                                <option>Option three</option>
                                <option>Option four</option>
                              </select>
                            </div>
                            </div>

                            <div class="form-group">
                              <div class="col-md-2">
                              <label  >Jenis Kasus</label>
                            </div>
                              <div class="col-md-3">
                                <select class="form-control">
                                  <option>Jenis Kasus </option>
                                  <option>Option one</option>
                                  <option>Option two</option>
                                  <option>Option three</option>
                                  <option>Option four</option>
                                </select>
                              </div>
                              </div>

                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                          <button type="submit" class="btn btn-success">INPUT TERSANGKA DAN BARANG BUKTI</button>
                          <button class="btn btn-primary" type="button">Cancel</button>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
            </div>
</div>
@endsection
