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
        <section class="login_content">
          @if (session('message'))
          @php
          $session = session('message');
          $error = $session['status'];
          $message = is_array($session['message']) ? implode(',',$session['message']) : $session['message'];
          echo '<div class="alert alert-'.$error.'">'.$message.'</div>';
          @endphp
          @endif
          <form class="form-horizontal form-label-left input_mask" action="{{action('AuthenticationController@nip_process')}}" method="POST" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="form-group">
              <label for="meta_media" class="col-md-5 control-label">Status Pegawai</label>
              <div class="col-md-7" style="text-align:left;">
                <label class="mt-radio status_pegawai col-md-12"> <input selected type="radio" value="PHL" name="status_pegawai">
                  <span>PHL</span>
                </label>

                <label class="mt-radio status_pegawai col-md-12"> <input type="radio" value="PNS" name="status_pegawai">
                  <span>PNS/POLRI</span>
                </label>
              </div>
            </div>
            <div class="form-group has-feedback m-t-32 hide" id="input_pns">
              <input type="text" id="nip" class="form-control has-feedback-left input-login" placeholder="NIP" name="nip" value="" />
              <span class="fa fa-id-card form-control-feedback left" aria-hidden="true"></span>
            </div>

            <div id="input_phl">
              <div class="form-group has-feedback m-t-32" >
                <input type="text" id="nama_pegawai" class="form-control has-feedback-left input-login" placeholder="Nama" name="nama_pegawai" value="" />
                <span class="fa fa-id-card form-control-feedback left" aria-hidden="true"></span>
              </div>

              <div class="form-group has-feedback m-t-10" >
                <input type="text" id="jabatan_pegawai" class="form-control has-feedback-left input-login" placeholder="Jabatan" name="jabatan_pegawai" value="" />
                <span class="fa fa-id-card form-control-feedback left" aria-hidden="true"></span>
              </div>

              <div class="form-group has-feedback m-t-10" >
                <input type="text" id="telp_pegawai" class="form-control has-feedback-left input-login" placeholder="No Telp" name="telp_pegawai" value="" />
                <span class="fa fa-id-card form-control-feedback left" aria-hidden="true"></span>
              </div>

              <div class="form-group has-feedback m-t-10" >
                <input type="text" id="lokasi_kerja" class="form-control has-feedback-left input-login" placeholder="Lokasi Kerja" name="lokasi_kerja" value="" />
                <span class="fa fa-id-card form-control-feedback left" aria-hidden="true"></span>
              </div>

              <div class="fileinput fileinput-new m-t-10" data-provides="fileinput">
                <div class="input-group input-large">
                  <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                    <i class="fa fa-file fileinput-exists"></i>&nbsp;
                    <span class="fileinput-filename"> </span>
                  </div>
                  <span class="input-group-addon btn default btn-file">
                    <span class="fileinput-new"> Pilih Berkas </span>
                    <span class="fileinput-exists"> Ganti </span>
                    <input type="hidden" value="" name="file_laporan_kegiatan">
                    <input type="file" name="file_laporan_kegiatan_file">
                  </span>
                  <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
                </div>
              </div>

            </div>

            <div class="m-t-32">
              <input style="width: 75px;" type="submit" name="submit" class="btn btn-primary btn-round submit" value="Masuk "/>
              <a href="{{URL('/logout')}}" class="btn btn-primary btn-round f-14 m-b-14" type="button">Kembali</a>
            </div>

            <div class="clearfix"></div>

            <div class="m-t-32">

              <div class="clearfix"></div>
            </div>
          </form>
        </section>
      </div>

    </div>

  </div>
</div>

@endsection
