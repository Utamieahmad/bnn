<div class="modal fade modal-default" id="modal_edit_peserta" tabindex="-1" role="dialog"aria-labelledby="myModalLabel" aria-hidden="true">
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
                <div class="loading-content">
                    <p> Sedang Memuat .... </p>
                </div>
                <form action="{{route('update_peserta_pelatihan')}}" method="POST" class="form-horizontal"  id="modal_edit_form" >
                    {{csrf_field()}}
                    <input type="hidden" name="id" value="" class="id"/>
                    <input type="hidden" name="index" class="index" value=""/>
                    <div class="form-group">
                        <label class="control-label col-md-3" >NIP</label>
                        <div class="col-sm-9">
                            <input name="nip" value="" type="text" class="form-control nip" onKeydown="numeric_only(event,this)"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3" >Nama</label>
                        <div class="col-sm-9">
                            <input name="nama" value="" type="text" class="form-control nama" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3" >Satuan Kerja</label>
                        <div class="col-sm-9">
                            <input name="satker" value="" type="text" class="form-control satker" required/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pangkat_golongan" class="control-label col-md-3">Pangkat</label>
                        <div class='col-md-7 col-sm-7 col-xs-12'>
                            <div class="radio">
                                @if($pangkat)
                                    @foreach($pangkat as $pkey=>$pvalue)
                                        <label class="mt-radio col-md-9 pangkat_golongan"> <input type="radio" value="{{$pkey}}" name="pangkat_golongan" class="pangkat_golongan">
                                        <span>{{$pvalue}} </span>
                                        </label>
                                    @endforeach
                                @endif
                            </div>
                        </div> 
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3" >Jabatan</label>
                        <div class="col-sm-9">
                            <input name="jabatan" value="" type="text" class="form-control jabatan" id="jabatan"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="jenjang_pendidikan" class="control-label col-md-3">Jenjang Pendidikan</label>
                        <div class='col-md-7 col-sm-7 col-xs-12'>
                            <div class="radio">
                                @if($pendidikan)
                                    @foreach($pendidikan as $pkey=>$pvalue)
                                        <label class="mt-radio col-md-9 jenjang_pendidikan"> <input type="radio" value="{{$pkey}}" name="jenjang_pendidikan" class="jenjang_pendidikan">
                                        <span>{{$pvalue}} </span>
                                        </label>
                                    @endforeach
                                @endif
                            </div>
                        </div> 
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="alert alert-warning alert-modal">
                    Peserta Pelatihan Gagal Diperbarui
                </div>
                <div class="alert alert-success alert-modal">
                    Peserta Pelatihan Berhasil Diperbarui
                </div>
                <button type="button" class="btn btn-default" data-dismiss="modal" > Batal </button>
                <button type="submit" class="btn btn-success" onClick="submit_modal_update(event,this,'modal_edit_form')"> Kirim </button>
            </div>
        </div>
    </div>
</div>
