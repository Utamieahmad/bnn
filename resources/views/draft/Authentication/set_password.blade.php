
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
                    <h2>Reset Password </h2><br>
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
@endsection
