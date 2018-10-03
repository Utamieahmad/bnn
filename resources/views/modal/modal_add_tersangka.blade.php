
<div class="modal fade" id="add_modaltersangka" role="dialog"
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
      Form Tambah Tersangka
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">
    <form id="form_tersangka" class="form-horizontal form-label-left" role="form" action="{{URL('/pemberantasan/input_tersangka')}}" method="post">
      {{ csrf_field() }}
      <input type="hidden" name="id" value="{{$id}}">
      <input type="hidden" name="url" value="{{\Request::path()}}">
      <input type="hidden" name="tersangka_id" id="tersangka_id" value="">
      <div class="form-group">
        <label  class="control-label col-md-3" for="pasal">Pasal</label>
        <div class="col-sm-9">
          <input type="text" name="pasal" class="form-control" id="pasal"/>
        </div>
      </div>

      <div class="form-group">
        <label  class="col-md-3 control-label" for="nama_tersangka">Nama Tersangka</label>
        <div class="col-sm-9">
          <input type="text" name="tersangka_nama" class="form-control" id="nama_tersangka"/>
        </div>
      </div>

      <div class="form-group">
        <label for="kode_jenisidentitas" class="col-md-3 control-label">Jenis Identitas</label>
        <div class="col-md-9" style="color:#f7f7f7">
          <label class="mt-radio col-md-4">  <input type="radio" id="kode_jenisidentitas_KTP" value="KTP" name="kode_jenisidentitas">
            <span>KTP</span>
          </label>

          <label class="mt-radio col-md-4"> <input type="radio" id="kode_jenisidentitas_SIM" value="SIM" name="kode_jenisidentitas">
            <span>SIM</span>
          </label>

          <label class="mt-radio col-md-4"> <input type="radio" id="kode_jenisidentitas_KITAS" value="KITAS" name="kode_jenisidentitas">
            <span>KITAS</span>
          </label>

          <label class="mt-radio col-md-4"> <input type="radio" id="kode_jenisidentitas_KITAP" value="KITAP" name="kode_jenisidentitas">
            <span>KITAP</span>
          </label>

          <label class="mt-radio col-md-4"> <input type="radio" id="kode_jenisidentitas_PASSPORT" value="PASSPORT" name="kode_jenisidentitas">
            <span>Passport</span>
          </label>

          <label class="mt-radio col-md-4"> <input type="radio" id="kode_jenisidentitas_KARTU_PELAJAR" value="KARTU_PELAJAR" name="kode_jenisidentitas">
            <span>Kartu Pelajar</span>
          </label>

          <label class="mt-radio col-md-5"> <input type="radio" id="kode_jenisidentitas_KARTU_MAHASISWA" value="KARTU_MAHASISWA" name="kode_jenisidentitas">
            <span>Kartu Mahasiswa</span>
          </label>
        </div>
      </div>

      <div class="form-group">
        <label  class="col-md-3 control-label" for="no_identitas">Nomor Identitas</label>
        <div class="col-sm-9">
          <input type="text" name="no_identitas" class="form-control " id="no_identitas"/>
        </div>
      </div>

      <div class="form-group">
        <label  class="col-md-3 control-label" for="alamat_tersangka">Alamat</label>
        <div class="col-sm-9">
          <input type="text" name="tersangka_alamat" class="form-control" id="alamat_tersangka"/>
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-3 control-label">Alamat Kabupaten/Kota</label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <select class="form-control select2" id="alamatktp_idkabkota" name="alamatktp_idkabkota" style="width:100%">
            <option value="">-- Pilih Kabupaten --</option>
              @if(isset($propkab))
                @foreach($propkab['data'] as $keyGroup => $jenis )
                <optgroup label="{{$keyGroup}}">
                  @foreach($jenis as $key => $val)
                  <option value="{{$key}}">{{$val}}</option>
                  @endforeach
                </optgroup>
                @endforeach
              @endif
          </select>
        </div>
      </div>

      <div class="form-group">
        <label  class="col-md-3 control-label" for="alamatktp_kodepos">Alamat Kodepos</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="alamatktp_kodepos" name="alamatktp_kodepos"/>
        </div>
      </div>

      <div class="form-group">
        <label  class="col-md-3 control-label" for="alamatdomisili">Alamat Domisili</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="alamatdomisili" name="alamatdomisili"/>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3">Alamat Domisili Kabupaten/Kota</label>
        <div class="col-sm-9">
          <select class="form-control select2 selectKabupaten" id="alamatdomisili_idkabkota" name="alamatdomisili_idkabkota" style="width:100%">
            <option value="">-- Pilih Kabupaten --</option>
              @if(isset($propkab))
                @foreach($propkab['data'] as $keyGroup => $jenis )
                <optgroup label="{{$keyGroup}}">
                  @foreach($jenis as $key => $val)
                  <option value="{{$key}}">{{$val}}</option>
                  @endforeach
                </optgroup>
                @endforeach
                @endif
          </select>
        </div>
      </div>

      <div class="form-group">
        <label  class="col-md-3 control-label" for="alamatdomisili_kodepos">Alamat Domisili Kodepos</label>
        <div class="col-sm-9">
          <input type="text" class="form-control numeric" onKeydown="numeric(event)" id="alamatdomisili_kodepos" name="alamatdomisili_kodepos"/>
        </div>
      </div>

      <div class="form-group">
        <label  class="col-md-3 control-label" for="alamatlainnya">Alamat Lainnya</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="alamatlainnya" name="alamatlainnya"/>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3">Alamat Lainnya Kabupaten/Kota</label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <select class="form-control select2 selectKabupaten" id="alamatlainnya_idkabkota" name="alamatlainnya_idkabkota" style="width:100%">
            <option value="">-- Pilih Kabupaten --</option>
              @if(isset($propkab))
                @foreach($propkab['data'] as $keyGroup => $jenis )
                <optgroup label="{{$keyGroup}}">
                  @foreach($jenis as $key => $val)
                  <option value="{{$key}}">{{$val}}</option>
                  @endforeach
                </optgroup>
                @endforeach
              @endif
          </select>
        </div>
      </div>

      <div class="form-group">
        <label  class="col-md-3 control-label" for="alamatlainnya_kodepos">Alamat Lainnya Kodepos</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="alamatlainnya_kodepos" name="alamatlainnya_kodepos"/>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3">Jenis Kelamin</label>
        <div class="col-md-9" style="color:#f7f7f7">
          <label class="mt-radio col-md-4"> <input type="radio" id="kode_jenis_kelamin_P" value="P" name="kode_jenis_kelamin">
            <span>Perempuan</span>
          </label>

          <label class="mt-radio col-md-3"> <input type="radio" id="kode_jenis_kelamin_L" value="L" name="kode_jenis_kelamin"><span> Laki-Laki</span>
          </label>
          <span class="help-block"></span>
        </div>
      </div>

      <div class="form-group">
        <label  class="col-md-3 control-label" for="tersangka_tempat_lahir">Tempat Lahir</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="tersangka_tempat_lahir" name="tersangka_tempat_lahir"/>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3">Tanggal Lahir</label>
        <div class='col-md-9 col-sm-9 col-xs-12 input-group date tanggal'>
          <input type='text' class="form-control" id="tersangka_tanggal_lahir" name="tersangka_tanggal_lahir"/>
          <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
          </span>
        </div>
      </div>

      <!-- <div class="form-group">
        <label class="col-md-3 control-label" for="tersangka_usia">Usia</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="tersangka_usia" name="tersangka_usia"/>
        </div>
      </div> -->

      <div class="form-group">
        <label class="control-label col-md-3">Pendidikan Akhir</label>
        <div class="col-md-9" style="color:#f7f7f7">
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
        <div class="col-md-9" style="color:#f7f7f7">

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
            <span> Polisi</span>
          </label>

          <label class="mt-radio col-md-4"> <input id="kode_pekerjaan_PLJ" type="radio" value="PLJ" name="kode_pekerjaan">
            <span>Pelajar</span>
          </label>

          <label class="mt-radio col-md-4"> <input id="kode_pekerjaan_NLY" type="radio" value="NLY" name="kode_pekerjaan">
            <span>Nelayan</span>
          </label>

          <label class="mt-radio col-md-4"> <input id="kode_pekerjaan_SNM" type="radio" value="SNM" name="kode_pekerjaan">
            <span>Seniman</span>
          </label>

          <label class="mt-radio col-md-4"> <input id="kode_pekerjaan_NRP" type="radio" value="NRP" name="kode_pekerjaan">
            <span>Narapidana</span>
          </label>

          <label class="mt-radio col-md-4"> <input id="kode_pekerjaan_SPR" type="radio" value="SPR" name="kode_pekerjaan">
            <span>Sopir</span>
          </label>

          <label class="mt-radio col-md-4"> <input id="kode_pekerjaan_PLT" type="radio" value="PLT" name="kode_pekerjaan">
            <span>Pelaut</span>
          </label>
          <span class="help-block"></span>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3">Warga Negara</label>
        <div class="col-md-9" style="color:#f7f7f7">
          <label class="mt-radio col-md-12"> <input type="radio" value="WNI" id="kode_warga_negara_WNI" name="kode_warga_negara">
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
          <select name="kode_negara" id="kode_negara" class="form-control select2" style="width:100%">
              @foreach($negara as $n)
                <option value="{{$n->kode}}" {{(isset($data['kode_negara']) ? ($n->kode == $data['kode_negara'] ? 'selected=selected' : '') :'')}}>{{$n->nama_negara}}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="kode_peran_tersangka" class="col-md-3 control-label">Peran Tersangka</label>
          <div class="col-md-9" style="color:#f7f7f7">
            <label class="mt-radio col-md-12"> <input type="radio" id="kode_peran_tersangka_1" value="1" name="kode_peran_tersangka">
              <span>Produksi</span>
            </label>

            <label class="mt-radio col-md-12"> <input type="radio" id="kode_peran_tersangka_2" value="2" name="kode_peran_tersangka">
              <span>Distribusi</span>
            </label>

            <label class="mt-radio col-md-12"> <input type="radio" id="kode_peran_tersangka_3" value="3" name="kode_peran_tersangka">
              <span>Kultivasi</span>
            </label>

            <label class="mt-radio col-md-12"> <input type="radio" id="kode_peran_tersangka_4" value="4" name="kode_peran_tersangka">
              <span>Konsumsi</span>
            </label>
            <span class="help-block"></span>
          </div>
        </div>

      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" onclick="submitForm(form)" class="btn btn-default"
        data-dismiss="modal">
        Batal
      </button>
      <button type="submit" class="btn btn-success">
        Kirim
      </button>
    </div>
  </form>
</div>
</div>
</div>
