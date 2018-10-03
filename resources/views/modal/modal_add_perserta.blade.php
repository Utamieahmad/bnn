<!-- Modal -->
<div class="modal fade" id="modal_add_peserta" role="dialog"
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
      Form Tambah Peserta
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">

    <form action="{{URL('pemberdayaan/dir_masyarakat/input_peserta')}}" id="formPeserta" method="post" class="form-horizontal" role="form">
      {{ csrf_field() }}
      <input type="hidden" name="id" value="{{$id}}">
      <input type="hidden" name="peserta_id" value="">

      <div class="form-group">
        <label for="kode_jenisidentitas" class="col-md-3 control-label">Jenis Identitas</label>
        <div class="col-md-9 col-xs-12 col-sm-9 white2 radio">
          <label class="mt-radio col-md-6 col-sm-6 col-xs-12">  <input checked="" type="radio" id="kode_jenisidentitas_KTP" value="KTP" name="kode_jenis_identitas">
            <span>KTP</span>
          </label>

          <label class="mt-radio col-md-6 col-sm-6 col-xs-12"> <input type="radio" id="kode_jenisidentitas_SIM" value="SIM" name="kode_jenis_identitas">
            <span>SIM</span>
          </label>

          <label class="mt-radio col-md-6 col-sm-6 col-xs-12"> <input type="radio" id="kode_jenisidentitas_KITAS" value="KITAS" name="kode_jenis_identitas">
            <span>KITAS</span>
          </label>

          <label class="mt-radio col-md-6 col-sm-6 col-xs-12"> <input type="radio" id="kode_jenisidentitas_KITAP" value="KITAP" name="kode_jenis_identitas">
            <span>KITAP</span>
          </label>

          <label class="mt-radio col-md-6 col-sm-6 col-xs-12"> <input type="radio" id="kode_jenisidentitas_PASSPORT" value="PASSPORT" name="kode_jenis_identitas">
            <span>Passport</span>
          </label>

          <label class="mt-radio col-md-6 col-sm-6 col-xs-12"> <input type="radio" id="kode_jenisidentitas_KARTU_PELAJAR" value="KARTU_PELAJAR" name="kode_jenis_identitas">
            <span>Kartu Pelajar</span>
          </label>

          <label class="mt-radio col-md-6 col-sm-6 col-xs-12"> <input type="radio" id="kode_jenisidentitas_KARTU_MAHASISWA" value="KARTU_MAHASISWA" name="kode_jenis_identitas">
            <span>Kartu Mahasiswa</span>
          </label>

          <label class="mt-radio col-md-6 col-sm-6 col-xs-12"> <input type="radio" id="kode_jenisidentitas_TANPA_ID" value="KARTU_MAHASISWA" name="kode_jenis_identitas">
            <span>Tanpa ID Pengenal</span>
          </label>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3" >Nomor Identitas</label>
        <div class="col-sm-9">
          <input name="no_identitas" value="" type="text" class="form-control" id="no_identitas"/>
        </div>
      </div>

      <div class="form-group">
        <label  class="control-label col-md-3" >Nama/Initial</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="peserta_inisial" name="peserta_inisial"/>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3">Jenis Kelamin</label>
        <div class="col-md-9 col-sm-9 col-xs-12 white2 radio">
          <label class="mt-radio col-md-4"> <input type="radio" id="kode_jenis_kelamin_P" value="P" name="kode_jenis_kelamin">
            <span>Perempuan</span>
          </label>

          <label class="mt-radio col-md-3"> <input type="radio" id="kode_jenis_kelamin_L" value="L" name="kode_jenis_kelamin"><span> Laki-Laki</span>
          </label>
          <span class="help-block"></span>
        </div>
      </div>

      <div class="form-group">
        <label  class="control-label col-md-3" for="nip_nrp">Tempat Lahir</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="peserta_tempat_lahir" name="peserta_tempat_lahir"/>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3">Tanggal Lahir</label>
        <div class='col-md-9 col-sm-9 col-xs-12 input-group date birthDate'>
          <input type='text' class="form-control" name="peserta_tanggal_lahir"/>
          <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
          </span>
        </div>
      </div>

      <div class="form-group">
        <label  class="control-label col-md-3" for="jabatan">Usia</label>
        <div class="col-sm-9">
          <input type="text" readonly="readonly" class="form-control age" id="peserta_usia" name="peserta_usia"/>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3">Pendidikan Akhir</label>
        <div class="col-md-9 col-sm-9 col-xs-12 white2 radio">
          <label class="mt-radio col-md-4"> <input type="radio" value="SD" id="kode_pendidikan_akhir_SD" name="kode_pendidikan_akhir">
            <span>SD</span>
          </label>

          <label class="mt-radio col-md-4"> <input type="radio" value="SLTP" id="kode_pendidikan_akhir_SLTP" name="kode_pendidikan_akhir">
            <span>SLTP</span>
          </label>

          <label class="mt-radio col-md-4">  <input type="radio" value="SLTA" id="kode_pendidikan_akhir_SLTA" name="kode_pendidikan_akhir">
            <span>SLTA</span>
          </label>

          <label class="mt-radio col-md-4"> <input type="radio" value="PT" id="kode_pendidikan_akhir_PT" name="kode_pendidikan_akhir">
            <span>Perguruan Tinggi</span>
          </label>

          <label class="mt-radio col-md-4"> <input type="radio" value="PTSKL" id="kode_pendidikan_akhir_PTSKL" name="kode_pendidikan_akhir">
            <span>Putus Sekolah</span>
          </label>

          <label class="mt-radio col-md-4"> <input type="radio" value="TDSKL" id="kode_pendidikan_akhir_TDSKL" name="kode_pendidikan_akhir">
            <span>Tidak Sekolah</span>
          </label>
          <span class="help-block"></span>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3">Pekerjaan</label>
        <div class="col-md-9 col-sm-9 col-xs-12 white2 radio">

          <label class="mt-radio col-md-4"> <input id="kode_pekerjaan_TNI" type="radio" value="TNI" name="kode_pekerjaan">
            <span>TNI</span>
          </label>

          <label class="mt-radio col-md-4"> <input id="kode_pekerjaan_TANI" type="radio" value="TANI" name="kode_pekerjaan">
            <span>TANI</span>
          </label>

          <label class="mt-radio col-md-4"> <input id="kode_pekerjaan_PNS" type="radio" value="PNS" name="kode_pekerjaan">
            <span>PNS</span>
          </label>

          <label class="mt-radio col-md-4"> <input id="kode_pekerjaan_SWT" type="radio" value="SWT" name="kode_pekerjaan">
            <span>Swasta</span>
          </label>

          <label class="mt-radio col-md-4"> <input id="kode_pekerjaan_WST" type="radio" value="WST" name="kode_pekerjaan">
            <span>Wiraswasta</span>
          </label>

          <label class="mt-radio col-md-4"> <input id="kode_pekerjaan_MHS" type="radio" value="MHS" name="kode_pekerjaan">
            <span>Mahasiswa</span>
          </label>

          <label class="mt-radio col-md-4"> <input id="kode_pekerjaan_BRH" type="radio" value="BRH" name="kode_pekerjaan">
            <span>Buruh</span>
          </label>

          <label class="mt-radio col-md-4"> <input id="kode_pekerjaan_PNG" type="radio" value="PNG" name="kode_pekerjaan">
            <span>Pengangguran</span>
          </label>

          <label class="mt-radio col-md-4"> <input id="kode_pekerjaan_POL" type="radio" value="POL" name="kode_pekerjaan">
            <span> Polri</span>
          </label>

          <label class="mt-radio col-md-4"> <input id="kode_pekerjaan_PLJ" type="radio" value="PLJ" name="kode_pekerjaan">
            <span>Pelajar</span>
          </label>
          <span class="help-block"></span>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3">Kode Warga Negara</label>
        <div class="col-md-9 col-sm-9 col-xs-12 white2 radio">
          <label class="mt-radio col-md-12"> <input checked="" type="radio" value="WNI" id="kode_warga_negara_WNI" name="kode_warga_negara">
            <span>WNI</span>
          </label>

          <label class="mt-radio col-md-12"> <input type="radio" value="WNA" id="kode_warga_negara_WNA" name="kode_warga_negara">
            <span>WNA</span>
          </label>
          <span class="help-block"></span>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3">Kode Negara</label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <select name="kode_negara" class="form-control select2" style="width:100%">
            <option value=""> -- Pilih Negara -- </option>
            @foreach($negara as $n)
                <option value="{{$n->kode}}" > {{$n->nama_negara}}</option>
            @endforeach
          </select>
          <span class="help-block"></span>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3">Jenis narkoba yg disalahgunakan.</label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <select name="id_brgbukti" id="id_brgbukti" class="form-control select2" style="width:100%">
            <option value=""> -- Pilih Barang Bukti -- </option>
            @foreach($jenisBrgBuktiNarkotika['data'] as $keyGroup => $jenis )
            <optgroup label="{{$keyGroup}}">
              @foreach($jenis as $key => $val)
              <option value="{{$key}}">{{$val}}</option>
              @endforeach
            </optgroup>
            @endforeach
          </select>
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
