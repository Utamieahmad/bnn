@extends('layouts.base_layout')
@section('title', 'Ubah Pendataan Razia')
@section('content')
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah2').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    function readURL3(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah3').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<div class="right_col" role="main">
    <div class="m-t-40">
        <div class="page-title">
            <div class="">
                {!! (isset($breadcrumps) ? $breadcrumps : '' )!!}
                </ul>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="title_right">
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Form Ubah Pendataan Razia</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    @if (session('status'))
                    @php
                    $session= session('status');
                    @endphp

                    <div class="alert alert-{{$session['status']}}">
                        {{ $session['message'] }}
                    </div>
                    @endif
                    <form  data-parsley-validate class="form-horizontal form-label-left" action="{{URL('/pemberantasan/update_razia')}}" enctype="multipart/form-data" method="post" >
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{$id}}">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" >Tanggal Razia</label>
                            <div class='col-md-6 col-sm-6 col-xs-12 input-group date tanggal'>
                                @if($data_razia['data']['tgl_razia'] != "")
                                <input type='text' name="tgl_razia" value="{{ \Carbon\Carbon::parse($data_razia['data']['tgl_razia'] )->format('d/m/Y') }}" class="form-control" />
                                @else
                                <input type="text" name="tgl_razia" value="" class="form-control" />
                                @endif
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Lokasi</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="lokasi" value="{{$data_razia['data']['lokasi']}}" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Uraian Singkat</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="uraian_singkat" value="{{$data_razia['data']['uraian_singkat']}}" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Jumlah Dirazia</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input value="{{$data_razia['data']['jumlah_dirazia']}}" id="jumlah_dirazia" name="jumlah_dirazia" type="text" class="form-control numeric" onkeydown="numeric(event)">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Jumlah Terindikasi</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input value="{{$data_razia['data']['jumlah_terindikasi']}}" id="jumlah_terindikasi" name="jumlah_terindikasi" type="text" class="form-control numeric" onkeydown="numeric(event)">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Jumlah Ditemukan</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input value="{{$data_razia['data']['jumlah_ditemukan']}}" id="jumlah_ditemukan" name="jumlah_ditemukan" type="text" class="form-control numeric" onkeydown="numeric(event)">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Keterangan Lainnya</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="keterangan_lainnya" value="{{$data_razia['data']['keterangan_lainnya']}}"  class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"  >Foto</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                @if ($data_razia['data']['foto1'])
                                    <img src="data:image/png;base64,{{$data_razia['data']['foto1']}}" id="blah" style="width:100%;height:150px;" />
                                @else
                                    <img src="{{asset('assets/images/NoImage.gif')}}" id="blah" style="width:100%;height:150px;" />
                                @endif                                
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">                                
                                @if ($data_razia['data']['foto2'])
                                    <img src="data:image/png;base64,{{$data_razia['data']['foto2']}}" id="blah2" style="width:100%;height:150px;" />
                                @else
                                    <img src="{{asset('assets/images/NoImage.gif')}}" id="blah2" style="width:100%;height:150px;" />
                                @endif
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">                                
                                @if ($data_razia['data']['foto3'])
                                    <img src="data:image/png;base64,{{$data_razia['data']['foto3']}}" id="blah3" style="width:100%;height:150px;" />
                                @else
                                    <img src="{{asset('assets/images/NoImage.gif')}}" id="blah3" style="width:100%;height:150px;" />
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"  >&nbsp;</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type='file' name="foto1" onchange="readURL(this);" />
                                <input type="text" name="foto1_old" hidden value="{{$data_razia['data']['foto1']}}"/>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type='file' name="foto2" onchange="readURL2(this);" />
                                <input type="text" name="foto2_old" hidden value="{{$data_razia['data']['foto2']}}"/>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type='file' name="foto3" onchange="readURL3(this);" />
                                <input type="text" name="foto3_old" hidden value="{{$data_razia['data']['foto3']}}"/>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" class="btn btn-success">KIRIM</button>
                                <a href="{{route('razia')}}" class="btn btn-primary" type="button">BATAL</a>
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
