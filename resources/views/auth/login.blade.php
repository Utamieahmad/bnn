@extends('layouts.base_layout')
@section('title','Login')
@section('content')
<div>
    <div class="login_wrapper">
        <div class="animate form login_form">
        <div class="col-sm-6 col-xs-12 bg-blue-bnn-1" style="display:grid; border-radius:8px 0 0 8px;">
            <div class="m-center p-20">
                <img src="assets/images/BNN-LOGO-BIG.png" class="img-responsive img-center">
                <h1 class="f-w-bold"><span class="c-yelow">S</span><span class="c-white">ISTEM</span> <br><span class="c-yelow">I</span><span class="c-white">NFORMASI</span> <br><span class="c-yelow">N</span><span class="c-white">ARKOBA</span></h1>
                <p class="c-blue-dark text-center">Badan Narkotika Nasional <br>Republik Indonesia</p>
            </div>
        </div>
        <div class="col-sm-6 col-xs-12 p-0">
            <section class="login_content">
                    @if (session('message'))
                        @php
                            $session = session('message');
                            $error = $session['status'];
                            $message = is_array($session['message']) ? implode('<br>',$session['message']) : $session['message'];
                            echo '<div class="alert alert-'.$error.'">'.$message.'</div>';
                        @endphp
                    @endif
                <form class="form-horizontal form-label-left input_mask" action="{{action('AuthenticationController@login_process')}}" method="POST">
                    <p class="c-login">Masuk ke Akun Anda</p>
                    {{csrf_field()}}
                    <div class="form-group has-feedback m-t-32">
                        <input type="text" class="form-control has-feedback-left input-login" placeholder="Email" name="email" value="{{ old('email') }}" />
                        <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input value="" type="password" class="form-control has-feedback-left input-login" placeholder="Kata Sandi"  name="password"/>
                        <span class="fa fa-lock form-control-feedback left" aria-hidden="true"></span>
                    </div>
                    <div class="m-t-32">
                        <input type="submit" name="submit" class="btn btn-primary btn-round submit" value="Masuk "/>


                    </div>

                    <div class="clearfix"></div>

                    <div class="m-t-32">

                        <div class="clearfix"></div>

                        <div class="m-t-5 p-l-20 p-r-20">
                        <a class="reset_pass" href="{{url('forgot_password')}}" >Lupa Kata Sandi ?</a>
                            <!-- <p class="f-11 f-w-300 c-login">©2017 All Rights Reserved. Badan Narkotika Nasional. Privacy and Terms</p> -->
                        </div>
                    </div>
                </form>
            </section>
        </div>

        </div>

    </div>
</div>

@endsection
