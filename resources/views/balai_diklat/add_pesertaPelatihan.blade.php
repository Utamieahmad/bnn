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
                    Form Tambah Peserta Pelatihan
                </h4>
            </div>
                <form action="{{route('save_peserta_pelatihan')}}" method="POST" class="form-horizontal"  id="modal_add_form" >
                    <div class="modal-body">
                            {{csrf_field()}}
                            <input type="hidden" class="parent_id" name="parent_id" value="{{isset($data->id) ? $data->id : ""}}">
                            <div class="form-group">
                                <label class="control-label col-md-3" >NIP*)</label>
                                <div class="col-sm-9">
                                    <input name="nip" value="" type="text" class="form-control" id="nip" onKeydown="numeric_only(event,this)" required/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3" >Nama*</label>
                                <div class="col-sm-9">
                                    <input name="nama" value="" type="text" class="form-control" id="nama" required/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3" >Satuan Kerja*</label>
                                <div class="col-sm-9">
                                    <input name="satker" value="" type="text" class="form-control" id="nama" required/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="pangkat_golongan" class="control-label col-md-3">Pangkat</label>
                                <div class='col-md-7 col-sm-7 col-xs-12'>
                                    <div class="radio">
                                        @if($pangkat)
                                            @foreach($pangkat as $pkey=>$pvalue)
                                                <label class="mt-radio col-md-9"> <input type="radio" value="{{$pkey}}" name="pangkat_golongan" class="pangkat_golongan">
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
                                    <input name="jabatan" value="" type="text" class="form-control" id="nama"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="jenjang_pendidikan" class="control-label col-md-3">Jenjang Pendidikan</label>
                                <div class='col-md-7 col-sm-7 col-xs-12'>
                                    <div class="radio">
                                        @if($pendidikan)
                                            @foreach($pendidikan as $pkey=>$pvalue)
                                                <label class="mt-radio col-md-9"> <input type="radio" value="{{$pkey}}" name="jenjang_pendidikan" class="jenjang_pendidikan">
                                                <span>{{$pvalue}} </span>
                                                </label>
                                            @endforeach
                                        @endif
                                    </div>
                                </div> 
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" > Batal </button>
                        <button type="submit" class="btn btn-success"> Kirim </button>
                    </div>
                </form>
            </div>
    </div>
</div>
