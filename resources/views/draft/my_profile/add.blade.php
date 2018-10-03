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
                    <h2>Form Design <small>different form elements</small></h2><br>
                    <div class="ln_solid"></div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                      <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Group</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
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
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Wilayah</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">User Login
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text"  required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Username
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="last-name" name="last-name" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Password</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="middle-name" class="form-control col-md-7 col-xs-12" type="password" name="middle-name">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Deskripsi
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea name="name" rows="5" cols="80" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text"> </textarea>
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
@endsection
