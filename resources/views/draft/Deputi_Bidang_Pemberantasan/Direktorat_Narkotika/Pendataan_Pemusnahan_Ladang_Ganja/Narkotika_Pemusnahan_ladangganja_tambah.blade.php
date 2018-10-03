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
                      Pendataan Pemusnahan Ladang Ganja
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
                    <h2>Form Design </h2>
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" ><h2>Penyidik</h2>
                        </label>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >No. Sprint Penyelidikan
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Tanggal Penyelidikan
                        </label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Provinsi - Kabupaten/Kota </label>
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Kecamatan
                        </label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Kelurahan
                        </label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Desa/Dusun
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Koordinat Latitud
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" >Koordinat Longitud
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" >Luas Lahan Ganja
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                          </div>
                          <label class="control-label col-md-2 col-sm-2 col-xs-12" >Satuan m2
                          </label>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" ><h2>Pemusnahan</h2>
                          </label>
                        </div>

                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" >No. Sprint Pemusnahan
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" >Tanggal Pemusnahan
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" > Luas Lahan Ganja yang Di Musnahkan
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" > Luas Lahan Ganja yang Di Musnahkan
                          </label>
                          <div class="col-md-3 col-sm-3 col-xs-12">
                            <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                          </div>
                          <label class="control-label col-md-2 col-sm-1 col-xs-12" >Satuan m2
                          </label>
                        </div>

                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" >Hasi Laporan Hasil Pelaksanaan
                          </label>
                          <div class="col-md-3 col-sm-3 col-xs-12">
                            <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                          </div>
                        </div>



                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                          <button type="submit" class="btn btn-success">SUMBIT</button>
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
