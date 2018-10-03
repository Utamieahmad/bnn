@extends('layouts.base_layout')

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
                            $message = is_array($session['message'])? implode(',',$session['message']) : $session['message'];
                            echo '<div class="alert alert-'.$error.'">'.$message.'</div>';
                        @endphp
                    @endif

                <h3 class="c-login">Lupa Kata Sandi</h3>
                <form class="form-horizontal form-label-left input_mask" action="{{action('AuthenticationController@forgot_password')}}" method="POST">

                    <p class="c-login c-login p-l-10 p-r-10">Link untuk memperbarui password akan dikirim melalui email yang Anda isikan ke dalam form di bawah ini.</p>
                    {{csrf_field()}}
                    <div class="form-group has-feedback m-t-32">
                        <input type="text" class="form-control has-feedback-left input-login" placeholder="Email" name="email" value="{{ old('email') }}" />
                        <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
                    </div>



                    <div class="clearfix"></div>
                    <div class="form-group has-feedback m-t-20 text-center">
                        <input type="submit" name="submit" class="btn btn-primary btn-round submit" value="Kirim Reset Link"/>
                    </div>
                    <div class="m-t-32">

                        <div class="clearfix"></div>

                        <div class="m-t-10 p-l-20 p-r-20">
                            <div class="form-group m-t-10 text-center">
            								<a href="{{route('login')}}" class="btn btn-primary btn-round" type="button">BATAL</a>
                            </div>
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
