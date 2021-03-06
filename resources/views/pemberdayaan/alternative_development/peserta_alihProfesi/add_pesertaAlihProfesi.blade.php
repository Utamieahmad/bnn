<div class="modal fade modal-default" id="modal_peserta" tabindex="-1" role="dialog"aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-color">
        <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true" class="c-white">&times;</span>
                <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title c-white" id="myModalLabel">
                    Form Tambah Peserta Alih Jenis Profesi
                </h4>
            </div>
            <div class="modal-body">
                <form action="{{route('save_peserta_alih_profesi')}}" method="POST" class="form-horizontal"  id="modal_add_form" >
                    <input type="hidden" name="idparent" value="{{isset($data->id) ? $data->id : ""}}">
                    {{csrf_field()}}
                    <div class="form-body">
                        <div class="form-group">
                            <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama</label>
                            <div class='col-md-7 col-sm-7 col-xs-12 input-group'>
                                <input type='text' name="nama" value="" class="form-control" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tempat Lahir</label>
                            <div class='col-md-7 col-sm-7 col-xs-12 input-group'>
                                <input type='text' name="tempat_lahir" value="" class="form-control" />
                            </div>
                        </div>
                         <div class="form-group">
                            <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal Lahir</label>
                            <div class='col-md-7 col-sm-7 col-xs-12 input-group birthDate'>
                                <input type='text' name="tgl_lahir" value="" class="form-control" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Usia</label>
                            <div class='col-md-7 col-sm-7 col-xs-12 input-group'>
                                <input type='text' readonly name="usia" value="" class="form-control age" onKeydown="numeric_only(event,this)"/>
                            </div>
                        </div>
                         <div class="form-group">
                            <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Identitas</label>
                            <div class='col-md-7 col-sm-7 col-xs-12 input-group'>
                                <div class="radio">
                                    @if($jenis_identitas)
                                        @foreach($jenis_identitas as $ikey=>$ivalue)
                                            <label class="mt-radio col-md-9"> <input type="radio" value="{{$ikey}}" name="kodejenisidentitas" id="">
                                            <span>{{$ivalue}} </span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Nomor Identitas</label>
                            <div class='col-md-7 col-sm-7 col-xs-12 input-group'>
                                <input type='text' name="no_identitas" value="" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Alamat Sesuai KTP</label>
                            <div class='col-md-7 col-sm-7 col-xs-12 input-group'>
                                <input type='text' name="alamat_rumah" value="" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Profesi</label>
                            <div class='col-md-7 col-sm-7 col-xs-12 input-group'>
                                <div class="radio">
                                    @if($lahan_profesi)
                                        @foreach($lahan_profesi as $lkey=>$lvalue)
                                            <label class="mt-radio col-md-9"> <input type="radio" value="{{$lkey}}" name="kode_profesi" class="kode_profesi" id="">
                                            <span>{{$lvalue}} </span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Kelamin</label>
                            <div class='col-md-7 col-sm-7 col-xs-12 input-group'>
                                <div class="radio">
                                    <label class="mt-radio col-md-9"> <input type="radio" value="P" name="jenis_kelamin" id="">
                                    <span>Perempuan </span>
                                    </label>
                                    <label class="mt-radio col-md-9"> <input type="radio" value="L" name="jenis_kelamin" id="">
                                    <span>LAKI-LAKI </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Pendidikan Terakhir</label>
                            <div class='col-md-7 col-sm-7 col-xs-12 input-group'>
                                <div class="radio">
                                    @if($pendidikan)
                                        @foreach($pendidikan as $pkey=>$pvalue)
                                            <label class="mt-radio col-md-9"> <input type="radio" value="{{$pkey}}" name="kodependidikanterakhir" id="">
                                            <span>{{$pvalue}} </span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="tgl_kegiatan" class="col-md-3 col-sm-3 col-xs-12 control-label">Capaian</label>
                            <div class='col-md-7 col-sm-7 col-xs-12 input-group'>
                                <textarea class="form-control" name="capaian"> </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-default" data-dismiss="modal" > Batal </button>
                        <button type="submit" class="btn btn-success" > SIMPAN </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
