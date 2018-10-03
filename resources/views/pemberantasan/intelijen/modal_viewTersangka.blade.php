<div class="modal fade" id="detailTersangka" role="dialog" aria-labelledby="detailTersangka" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content modal-color white-label">
      <!-- Modal Header -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true" class="c-white">×</span>
          <span class="sr-only">Close</span>
        </button>
        <h4 class="modal-title c-white" id="myModalLabel">
          Detail Tersangka
        </h4>
      </div>
      <!-- Modal Body -->
      <div class="modal-body">
        <div class="loading-content">
          Sedang Memuat ...
        </div>
        <form class="form-horizontal">
          <div class="form-group">
            <label class="control-label col-md-3 col-xs-12 col-sm-3 control-label" for="pasal">Pasal</label>
            <div class="col-sm-9 col-md-9 col-xs-12">
              <input type="text" disabled="disabled" class="form-control pasal" id="pasal">
            </div>
          </div>

          <div class="form-group">
            <label class=" col-md-3 col-xs-12 col-sm-3 control-label" for="nama_tersangka">Nama Tersangka</label>
            <div class="col-sm-9 col-md-9 col-xs-12">
              <input type="text" disabled="disabled"  class="form-control tersangka_nama" id="nama_tersangka">
            </div>
          </div>

          <div class="form-group">
            <label for="kode_jenisidentitas" class="col-md-3 col-xs-12 col-sm-3 control-label">Jenis Identitas</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="text"  disabled="disabled"  class="form-control kode_jenisidentitas" id="kode_jenisidentitas">
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label" for="no_identitas">Nomor Identitas</label>
            <div class="col-sm-9 col-md-9 col-xs-12">
              <input type="text"  disabled="disabled"  class="form-control no_identitas" id="no_identitas">
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label" for="alamat_tersangka">Alamat</label>
            <div class="col-sm-9 col-md-9 col-xs-12">
              <input type="text"  disabled="disabled" class="form-control tersangka_alamat" id="alamat_tersangka">
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Alamat Kabupaten/Kota</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="text"  disabled="disabled" class="form-control tersangka_alamat" id="tersangka_alamat">
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label" for="alamatktp_kodepos">Alamat Kodepos</label>
            <div class="col-sm-9 col-sm-9 col-xs-12">
              <input type="text" class="form-control alamatktp_kodepos" id="alamatktp_kodepos " disabled="disabled" >
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label" for="alamatdomisili">Alamat Domisili</label>
            <div class="col-sm-9 col-sm-9 col-xs-12">
              <input type="text" class="form-control alamatdomisili" id="alamatdomisili" disabled="disabled" >
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3">Alamat Domisili Kabupaten/Kota</label>
            <div class="col-sm-9 col-sm-9 col-xs-12">
              <input type="text"  disabled="disabled" class="form-control alamatdomisili_idkabkota" id="alamatdomisili_idkabkota">
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-3 control-label" for="alamatdomisili_kodepos">Alamat Domisili Kodepos</label>
            <div class="col-sm-9 col-sm-9 col-xs-12">
              <input type="text" class="form-control alamatdomisili_kodepos"  id="alamatdomisili_kodepos"  disabled="disabled" >
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label" for="alamatlainnya">Alamat Lainnya</label>
            <div class="col-sm-9 col-sm-9 col-xs-12">
              <input type="text" class="form-control alamatlainnya" id="alamatlainnya"  disabled="disabled" >
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Alamat Lainnya Kabupaten/Kota</label>
            <div class="col-md-9 col-sm-9 col-xs-12 ">
              <input class="alamatlainnya_idkabkota form-control" value=""/>
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label" for="alamatlainnya_kodepos">Alamat Lainnya Kodepos</label>
            <div class="col-sm-9 col-sm-9 col-xs-12">
              <input type="text" class="form-control alamatlainnya_kodepos" id="alamatlainnya_kodepos"  disabled="disabled">
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Kelamin</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="text"  disabled="disabled" class="form-control kode_jenis_kelamin" id="kode_jenis_kelamin">
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label" for="tersangka_tempat_lahir">Tempat Lahir</label>
            <div class="col-sm-9 col-md-9 col-xs-12">
              <input type="text" class="form-control tersangka_tempat_lahir" id="tersangka_tempat_lahir" disabled="disabled">
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Lahir</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="text" class="form-control tersangka_tanggal_lahir" id="tersangka_tanggal_lahir" disabled="disabled">
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Pendidikan Akhir</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="text"  disabled="disabled" class="form-control kode_pendidikan_akhir" id="kode_pendidikan_akhir">
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3">Pekerjaan</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="text" disabled="disabled" class="form-control kode_pekerjaan" id="kode_pekerjaan">
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3">Warga Negara</label>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <input type="text" disabled="disabled" class="form-control kode_warga_negara" id="kode_warga_negara">
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Negara</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" disabled="disabled" class="form-control kode_negara" id="kode_negara">
              </div>
            </div>

            <div class="form-group">
              <label for="kode_peran_tersangka" class="col-md-3 control-label">Peran Tersangka</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" disabled="disabled" class="form-control kode_peran_tersangka" id="kode_peran_tersangka">
              </div>
            </div>

          </form>

          <!-- Modal Footer -->
          <div class="modal-footer">
            <button type="button"  class="btn btn-default" data-dismiss="modal">
              Tutup
            </button>
          
        </form>
      </div>
    </div>
  </div>
</div>