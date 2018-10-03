@extends('Layouts.layoutsLogin')

@section('content')
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <div class="col-sm-6 col-xs-12 bg-blue-bnn-1" style="display:grid; border-radius:8px 0 0 8px;">
            <div class="m-center p-20">
              <img src="images/BNN-LOGO-BIG.png" class="img-responsive img-center">
              <h1 class="f-w-bold"><span class="c-yelow">SISTEM </span><br>INFORMASI <br>NARKOBA</h1>
              <p class="c-blue-dark text-center">Badan Narkotika Nasional <br>Republik Indonesia</p>
            </div>
          </div>
          <div class="col-sm-6 col-xs-12 p-0">
            <section class="login_content">
            <form class="form-horizontal form-label-left input_mask">
              <p class="c-login">Login To Your Account</p>
              <div class="form-group has-feedback m-t-32">
                <input type="text" class="form-control has-feedback-left" placeholder="Username" required="" />
                <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
              </div>
              <div class="form-group has-feedback">
                <input type="password" class="form-control has-feedback-left" placeholder="Password" required="" />
                <span class="fa fa-lock form-control-feedback left" aria-hidden="true"></span>
              </div>
              <div class="m-t-32">
                <a class="btn btn-lg btn-primary btn-round submit" href="{{route('home')}}">Log in</a>
                <a class="reset_pass" href="{{route('forgot_password')}}" >Forgot password?</a>
              </div>

              <div class="clearfix"></div>

              <div class="m-t-32">
                <!-- <p class="change_link">New to site?
                  <a href="#signup" class="to_register"> Create Account </a>
                </p> -->

                <div class="clearfix"></div>
                <br />

                <div class="m-t-20 p-l-20 p-r-20">
                  <p class="f-11 f-w-300 c-login">©2017 All Rights Reserved. Badan Narkotika Nasional. Privacy and Terms</p>
                </div>
              </div>
            </form>
          </section>
          </div>

        </div>

      </div>
    </div>
  @endsection
