@extends('Layouts.layoutsGlobal')
@section('content')
<div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="">
                  <ul class="page-breadcrumb breadcrumb">
                      <li>
                          System
                      </li>
                      <li>
                          User Management
                      </li>
                      <li class="active">
                          Data
                      </li>
                  </ul>
                <!--<h3>Fixed Sidebar <small> Just add class <strong>menu_fixed</strong></small></h3>-->
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                              <div class="x_panel">
                                <div class="x_title">
                                  <h2><i class="fa fa-align-left"></i> Collapsible / Accordion <small>Sessions</small></h2>
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

                                  <!-- start accordion -->
                                  <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                                    <div class="panel">
                                      <a class="panel-heading" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <h4 class="panel-title"> Profile </h4>
                                      </a>
                                      <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                        <div class="panel-body">
                                          <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="x_panel">
                                              <div class="x_title">
                                                <h2>Form Profile </h2><br>
                                                <div class="ln_solid"></div>
                                                <div class="clearfix"></div>
                                              </div>
                                              <div class="x_content">
                                                <br />
                                                <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">



                                              <div class="col-xs-6 col-md-3">

                                                <img src="http://placehold.it/380x500" alt="" class="img-rounded img-responsive" />

                                              </div>

                                               <div class="col-xs-6 col-md-9">
                                                  <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                      <input type="text"  required="required" value="Tes" class="form-control col-md-7 col-xs-12" >
                                                    </div>
                                                  </div>
                                                  <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Jabatan
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                      <input type="text"  required="required"  value=" Adminstrasi" class="form-control col-md-7 col-xs-12">
                                                    </div>
                                                  </div>
                                                  <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Email
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                      <input type="email"  required="required" value="a@gmail.com" class="form-control col-md-7 col-xs-12">
                                                    </div>
                                                  </div>
                                                  <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Phone
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                      <input type="text"  required="required" value="08976544566" class="form-control col-md-7 col-xs-12">
                                                    </div>
                                                  </div>
                                                    <div class="form-group">
                                                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Lokasi Kerja
                                                      </label>
                                                      <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text"  required="required" value="BNN Pusat" class="form-control col-md-7 col-xs-12">
                                                      </div>
                                                    </div>

                                                  <div class="form-group">
                                                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                                      <button class="btn btn-primary" type="button">Cancel</button>

                                                      <button type="submit" class="btn btn-success">Submit</button>
                                                    </div>
                                                  </div>

                                                </form>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="panel">
                                      <a class="panel-heading collapsed" role="tab" id="headingTwo" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <h4 class="panel-title">Reset Pasword</h4>
                                      </a>
                                      <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                        <div class="panel-body">
                                          <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="x_panel">
                                              <div class="x_title">
                                                <h2>Reset Password </h2><br>

                                              </div>
                                              <div class="ln_solid"></div>
                                              <div class="clearfix"></div>
                                              <div class="x_content">
                                                <br />
                                                <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                                                  <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Current
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                      <input type="password"  required="required" value="Tes" class="form-control col-md-7 col-xs-12" >
                                                    </div>
                                                  </div>
                                                  <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">New
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                      <input type="password"  required="required"  class="form-control col-md-7 col-xs-12">
                                                    </div>
                                                  </div>
                                                  <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Retype new
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                      <input type="password"  required="required"  class="form-control col-md-7 col-xs-12">
                                                    </div>
                                                  </div>

                                                  <div class="form-group">
                                                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                                      <button type="submit" class="btn btn-success">Save Changes</button>
                                                    </div>

                                                </form>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>

                                  </div>
                                  <!-- end of accordion -->


                                </div>
                              </div>
                            </div>

            </div>

          </div>
</div>
</div>
</div>
@endsection
