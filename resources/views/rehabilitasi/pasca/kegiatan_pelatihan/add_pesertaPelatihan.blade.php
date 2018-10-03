<div class="modal fade modal-default" id="modal_add_peserta" tabindex="-1" role="dialog"aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-color">
        <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true" class="c-white">&times;</span>
                <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title c-white" id="myModalLabel">
                    Form Ubah Peserta Pelathian
                </h4>
            </div>
            <div class="modal-body">
                 <form action="{{route('add_peserta_pelatihan_pascarehabilitasi')}}" method="POST" class="form-horizontal"  id="modal_add_form" >
                    {{csrf_field()}}
                    <input type="hidden" name="id_header" value="{{$data->id}}"/>
                    <div class="form-group">
                        <label class="control-label col-md-3" >Intitusi</label>
                        <div class="col-sm-9">
                            <input name="asal_instansilembaga" value="" type="text" class="form-control asal_instansilembaga"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3" >Nama Peserta</label>
                        <div class="col-sm-9">
                            <input name="nama_peserta" value="" type="text" class="form-control nama_peserta"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3" >NIP/NRP/KTP</label>
                        <div class="col-sm-9">
                            <input name="nomor_identitas" value="" type="text" class="form-control nomor_identitas"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3" >Jenis Kelamin</label>
                        <div class="col-sm-9">
                            <div class="radio">
                                <label class="mt-radio col-md-9"> <input type="radio" value="L" name="kode_jeniskelamin" class="kode_jeniskelamin" >
                                <span>Laki-Laki </span>
                                </label>
                                <label class="mt-radio col-md-9"> <input type="radio" value="P" name="kode_jeniskelamin" class="kode_jeniskelamin" >
                                <span>Perempuan </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3" >Nomor HP</label>
                        <div class="col-sm-9">
                            <input name="nomor_hp" value="" type="text" class="form-control nomor_hp" onKeydown="phone(event,this)"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3" >Email Peserta</label>
                        <div class="col-sm-9">
                            <input name="email_peserta" value="" type="email" class="form-control email_peserta"/>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <div class="alert alert-warning alert-modal">
                    Peserta Pelatihan Pasca Rehabilitasi Gagal Ditambahkan
                </div>
                <div class="alert alert-success alert-modal">
                    Peserta Pelatihan Pasca Rehabilitasi Berhasil Diperbarui
                </div>
                <button type="button" class="btn btn-default" data-dismiss="modal" > Batal </button>
                <button type="submit" class="btn btn-success" onClick="submit_modal(event,this,'modal_add_form')"> Kirim </button>
            </div>
        </div>
    </div>
</div>
