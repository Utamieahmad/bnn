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

              <div class="title_right">
                   <h3>Form Elements</h3>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Form Design <small>different form elements</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">Settings 1</a>
                          </li>
                          <li><a href="#">Settings 2</a>
                          </li>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <form  data-parsley-validate class="form-horizontal form-label-left">

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Tanggal LKN
                        </label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Pelaksana</label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Nomor LKN
                        </label>
                        <div class="col-md-1 col-sm-1 col-xs-12">
                          <input type="text"  value="LKN" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                          <select class="form-control">
                            <option>Choose option</option>
                            <option>Option one</option>
                            <option>Option two</option>
                            <option>Option three</option>
                            <option>Option four</option>
                          </select>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-12">
                          <input type="text"  value="X" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-12">
                          <input type="text"  value="2017" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                          <input type="text"  value="BNN PUSAT" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"  >
                        </label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Penanggung Jawab (Penyidik)
                        </label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Tanggal Kejadian
                        </label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"  >TKP Kasus
                        </label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Modus Operandi
                        </label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Negara Sumber Narkotika</label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Jalur Masuk</label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Rute Asal
                        </label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12" >
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Rute Transit
                        </label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Rute Tujuan
                        </label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Kelompok Kasus</label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Kasus</label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <select class="form-control">
                            <option>Choose option</option>
                            <option>Option one</option>
                            <option>Option two</option>
                            <option>Option three</option>
                            <option>Option four</option>
                          </select>
                        </div>
                      </div>
                      <div class="ln_solid"></div>
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
        </div>
@endsection
