<!-- Modal -->
<div class="modal fade" id="modal_add_pesertadiklat" role="dialog"
aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
  <div class="modal-content modal-color">
    <!-- Modal Header -->
    <div class="modal-header">
      <button type="button" class="close"
      data-dismiss="modal">
      <span aria-hidden="true" class="c-white">&times;</span>
      <span class="sr-only">Close</span>
    </button>
    <h4 class="modal-title c-white" id="myModalLabel">
      Form Tambah Peserta Balai Diklat
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">

    <form class="form-horizontal" role="form">

      <div class="form-group">
        <label class="control-label col-md-3" >NIP</label>
        <div class="col-sm-9">
          <input name="nip" value="" type="text" class="form-control" id="nama"/>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3" >Nama</label>
        <div class="col-sm-9">
          <input name="nama" value="" type="text" class="form-control" id="nama"/>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3" >Satuan Kerja</label>
        <div class="col-sm-9">
          <input name="satker" value="" type="text" class="form-control" id="nama"/>
        </div>
      </div>

      <div class="form-group">
        <label for="pangkat_golongan" class="control-label col-md-3">Pangkat</label>
        <div class="col-md-9" style="color:#f7f7f7">

          <label class="mt-radio col-md-6"> <input id="pangkat_golongan_3a" type="radio" value="3a" name="pangkat_golongan">
            <span>Penata Muda (III/a)</span>
          </label>

          <label class="mt-radio col-md-6"> <input id="pangkat_golongan_3a" type="radio" value="3b" name="pangkat_golongan">
            <span>Penata Muda Tk.I (III/b)</span>
          </label>

          <label class="mt-radio col-md-6"> <input id="pangkat_golongan_3c" type="radio" value="3c" name="pangkat_golongan">
            <span>Penata (III/c)</span>
          </label>

          <label class="mt-radio col-md-6"> <input id="pangkat_golongan_3d" type="radio" value="3d" name="pangkat_golongan">
            <span>Penata Tk. I (III/d)</span>
          </label>

          <label class="mt-radio col-md-6"> <input id="pangkat_golongan_4a" type="radio" value="4a" name="pangkat_golongan">
            <span>Pembina (IV/a)</span>
          </label>

          <label class="mt-radio col-md-6"> <input id="pangkat_golongan_4b" type="radio" value="4b" name="pangkat_golongan">
            <span>Pembina Tk. I (IV/b)</span>
          </label>

          <label class="mt-radio col-md-6"> <input id="pangkat_golongan_4d" type="radio" value="4d" name="pangkat_golongan">
            <span>Pembina Utama Madya (IV/d)</span>
          </label>

          <label class="mt-radio col-md-6"> <input id="pangkat_golongan_4e" type="radio" value="4e" name="pangkat_golongan">
            <span>Pembina Utama (IV/e)</span>
          </label>

          <span class="help-block"></span>
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
        <div class="col-md-9" style="color:#f7f7f7">
          <label class="mt-radio col-md-4"> <input type="radio" value="SMA/Sederajat" id="jenjang_pendidikan_SMA/Sederajat" name="jenjang_pendidikan">
            <span>SMA/Sederajat</span>
          </label>

          <label class="mt-radio col-md-4"> <input type="radio" value="D1" id="jenjang_pendidikan_D1" name="jenjang_pendidikan">
            <span>D1</span>
          </label>

          <label class="mt-radio col-md-4">  <input type="radio" value="D2" id="jenjang_pendidikan_D2" name="jenjang_pendidikan">
            <span>D2</span>
          </label>

          <label class="mt-radio col-md-4"> <input type="radio" value="D3" id="jenjang_pendidikan_D3" name="jenjang_pendidikan">
            <span>D3</span>
          </label>

          <label class="mt-radio col-md-4"> <input type="radio" value="D4" id="jenjang_pendidikan_D4" name="jenjang_pendidikan">
            <span>D4</span>
          </label>

          <label class="mt-radio col-md-4"> <input type="radio" value="S1" id="jenjang_pendidikan_S1" name="jenjang_pendidikan">
            <span>S1</span>
          </label>

          <label class="mt-radio col-md-4"> <input type="radio" value="S2" id="jenjang_pendidikan_S2" name="jenjang_pendidikan">
            <span>S2</span>
          </label>

          <label class="mt-radio col-md-4"> <input type="radio" value="S3" id="jenjang_pendidikan_S3" name="jenjang_pendidikan">
            <span>S3</span>
          </label>
          <span class="help-block"></span>
        </div>
      </div>

      

    </div>

    <!-- Modal Footer -->
    <div class="modal-footer">
      <button type="button" class="btn btn-default"
      data-dismiss="modal">
      Batal
    </button>
    <button type="submit" class="btn btn-success">
      Simpan
    </button>
  </div>
</form>
</div>
</div>
</div>
