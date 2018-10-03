@extends('layouts.base_layout')

@section('content')
<div>
  <div class="login_wrapper">
    <div class="animate form login_form">
      <!-- <div class="col-sm-6 col-xs-12 bg-blue-bnn-1" style="display:grid; border-radius:8px 0 0 8px;">
        <div class="m-center p-20">
          <img src="assets/images/BNN-LOGO-BIG.png" class="img-responsive img-center">
          <h1 class="f-w-bold"><span class="c-yelow">S</span><span class="c-white">ISTEM</span> <br><span class="c-yelow">I</span><span class="c-white">NFORMASI</span> <br><span class="c-yelow">N</span><span class="c-white">ARKOBA</span></h1>
          <p class="c-blue-dark text-center">Badan Narkotika Nasional <br>Republik Indonesia</p>
        </div>
      </div> -->
      <div class="col-md-3">

      </div>
      <div class="col-sm-6 col-xs-12 p-0">
        @if (session('message'))
            @php
                $session = session('message');
                $error = $session['status'];
                $message = is_array($session['message']) ? implode(',',$session['message']) : $session['message'];
                echo '<div class="alert alert-'.$error.'">'.$message.'</div>';
            @endphp
        @endif
          <form class="form-horizontal form-label-left input_mask" action="{{URL('/registeruser')}}" method="POST" enctype="multipart/form-data">
            {{csrf_field()}}

            <div id="input_phl">
              <div class="form-group has-feedback m-t-32">
  							<label class="control-label col-md-3 col-sm-3 col-xs-12"  >Email</label>
  							<div class="col-md-9 col-sm-9 col-xs-12">
  								<input type="email" name="email" value="" class="form-control col-md-7 col-xs-12">
  							</div>
  						</div>

              <div class="form-group has-feedback m-t-32">
  							<label class="control-label col-md-3 col-sm-3 col-xs-12">Pelaksana</label>
  							<div class="col-md-9 col-sm-9 col-xs-12">
  								<select class="form-control select2" id="instansi" name="instansi" required>
  									<option value="">-- Pilih Instansi --</option>
  								@foreach($instansi as $w)
  									<option value="{{$w['id_instansi']}}">{{$w['nm_instansi']}}</option>
  								@endforeach
  								</select>
  							</div>
  						</div>

              <div class="form-group has-feedback m-t-32">
  							<label class="control-label col-md-3 col-sm-3 col-xs-12"  >Menu</label>
  							<div class="col-md-9 col-sm-9 col-xs-12" style="color:white">
                  <label class="mt-checkbox col-md-12 col-sm-12 col-xs-12">
                    <input type="checkbox" value="23" name="meta_menu[]">
                    Pemberantasan
                  </label>
                  <label class="mt-checkbox col-md-12 col-sm-12 col-xs-12">
                    <input type="checkbox" value="24" name="meta_menu[]">
                    Rehabilitasi
                  </label>
                  <label class="mt-checkbox col-md-12 col-sm-12 col-xs-12">
                    <input type="checkbox" value="22" name="meta_menu[]">
                    Pencegahan
                  </label>
                  <label class="mt-checkbox col-md-12 col-sm-12 col-xs-12">
                    <input type="checkbox" value="25" name="meta_menu[]">
                    Pemberdayaan Masyarakat
                  </label>
                  <label class="mt-checkbox col-md-12 col-sm-12 col-xs-12">
                    <input type="checkbox" value="7" name="meta_menu[]">
                    Hukum dan Kerjasama
                  </label>
                  <label class="mt-checkbox col-md-12 col-sm-12 col-xs-12">
                    <input type="checkbox" value="47" name="meta_menu[]">
                    Inspektorat Utama
                  </label>
                  <label class="mt-checkbox col-md-12 col-sm-12 col-xs-12">
                    <input type="checkbox" value="55" name="meta_menu[]">
                    Balai Besar
                  </label>
                  <label class="mt-checkbox col-md-12 col-sm-12 col-xs-12">
                    <input type="checkbox" value="26" name="meta_menu[]">
                    Balai Diklat
                  </label>
                  <label class="mt-checkbox col-md-12 col-sm-12 col-xs-12">
                    <input type="checkbox" value="27" name="meta_menu[]">
                    Balai Laboratorium Narkoba
                  </label>
                  <label class="mt-checkbox col-md-12 col-sm-12 col-xs-12">
                    <input type="checkbox" value="56" name="meta_menu[]">
                    Puslitdatin
                  </label>
                  <label class="mt-checkbox col-md-12 col-sm-12 col-xs-12">
                    <input type="checkbox" value="57" name="meta_menu[]">
                    Sekretariat Utama
                  </label>
  							</div>
  						</div>

            </div>

        {{-- <section class="login_content"> --}}
            <div class="m-t-32" style="text-align: center;padding-bottom: 6px;">
              <input type="submit" name="submit" class="btn btn-primary btn-round submit" value="Tambah "/>
            </div>

            </div>
          </form>
        {{-- </section> --}}
      </div>

    </div>

  </div>
</div>

@endsection
