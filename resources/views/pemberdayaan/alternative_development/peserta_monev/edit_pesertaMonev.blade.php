<div class="modal fade" id="modal_edit_peserta" tabindex="-1" role="dialog"aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-color">
        <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true" class="c-white">&times;</span>
                <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title c-white" id="myModalLabel">
                    Form Edit Peserta Alih Fungsi Lahan Ganja
                </h4>
            </div>
            <div class="modal-body">
                <div class="loading-content">
                    <p> Sedang Memuat .... </p>
                </div>
                <form action="{{route('update_peserta_monev')}}" method="POST" class="form-horizontal"  id="modal_edit_form" >
                    {{csrf_field()}}
                    <input type="hidden" name="id" class="id" value=""/>
                    <input type="hidden" name="index" class="index" value=""/>
                    
                    <div class="form-body">
                        <div class="form-group">
                            <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama</label>
                            <div class='col-md-9 col-sm-9 col-xs-12 input-group'>
                                <input type='text' name="nama" value="" class="form-control nama" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tempat Lahir</label>
                            <div class='col-md-9 col-sm-9 col-xs-12 input-group'>
                                <input type='text' name="tempat_lahir" value="" class="form-control tempat_lahir" />
                            </div>
                        </div>
                         <div class="form-group">
                            <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Lahir</label>
                            <div class='col-md-9 col-sm-9 col-xs-12 input-group birthDate'>
                                <input type='text' name="tgl_lahir" value="" class="form-control tgl_lahir" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Usia</label>
                            <div class='col-md-9 col-sm-9 col-xs-12 input-group'>
                                <input type='text' readonly name="usia" value="" class="form-control usia age" onKeydown="numeric_only(event,this)"/>
                            </div>
                        </div>
                         <div class="form-group">
                            <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Identitas</label>
                            <div class='col-md-9 col-sm-9 col-xs-12 input-group'>
                                <div class="radio">
                                    @if($jenis_identitas)
                                        @foreach($jenis_identitas as $ikey=>$ivalue)
                                            <label class="mt-radio col-md-9 kodejenisidentitas"> <input type="radio" value="{{$ikey}}" name="kodejenisidentitas" class="kodejenisidentitas">
                                            <span>{{$ivalue}} </span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Nomor Identitas</label>
                            <div class='col-md-9 col-sm-9 col-xs-12 input-group'>
                                <input type='text' name="no_identitas" value="" class="form-control no_identitas" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Alamat Sesuai KTP</label>
                            <div class='col-md-9 col-sm-9 col-xs-12 input-group'>
                                <input type='text' name="alamat_rumah" value="" class="form-control alamat_rumah" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Profesi</label>
                            <div class='col-md-9 col-sm-9 col-xs-12 input-group'> 
                                <div class="radio">
                                    @if($monev_profesi)
                                        @foreach($monev_profesi as $lkey=>$lvalue)
                                            <label class="mt-radio col-md-9"> <input type="radio" value="{{$lkey}}" name="kode_profesi" class="kode_profesi" class="kode_profesi">
                                            <span>{{$lvalue}} </span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Kelamin</label>
                            <div class='col-md-9 col-sm-9 col-xs-12 input-group'>
                                <div class="radio">
                                    <label class="mt-radio col-md-9"> <input type="radio" value="P" name="jenis_kelamin" class="jenis_kelamin">
                                    <span>Perempuan </span>
                                    </label>
                                    <label class="mt-radio col-md-9"> <input type="radio" value="L" name="jenis_kelamin" class="jenis_kelamin">
                                    <span>Laki-Laki </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Pendidikan Terakhir</label>
                            <div class='col-md-9 col-sm-9 col-xs-12 input-group'>
                                <div class="radio">
                                    @if($pendidikan)
                                        @foreach($pendidikan as $pkey=>$pvalue)
                                            <label class="mt-radio col-md-9"> <input type="radio" value="{{$pkey}}" name="kodependidikanterakhir" class="kodependidikanterakhir">
                                            <span>{{$pvalue}} </span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Pelatihan Yang Diikuti</label>
                            <div class='col-md-9 col-sm-9 col-xs-12 input-group'>
                                <div class="radio">
                                    @if($profesi_pelatihan)
                                        @foreach($profesi_pelatihan as $mkey=>$mvalue)
                                            <label class="mt-radio col-md-9"> <input type="radio" value="{{$mkey}}" name="kodepelatihanyangdiikuti" class="kodepelatihanyangdiikuti">
                                            <span>{{$mvalue}} </span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="alert alert-warning alert-modal">
                    Data Peserta Gagal Diperbarui
                </div>
                <div class="alert alert-success alert-modal">
                    Data Peserta Berhasil Diperbarui
                </div>
                <button type="button" class="btn btn-default" data-dismiss="modal" > Batal </button>
                <button type="submit" class="btn btn-success" onClick="submit_modal_update(event,this,'modal_edit_form')"> Kirim </button>
            </div>
        </div>
    </div>
</div>
