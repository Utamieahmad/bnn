
<div class="modal fade" id="data_survey_narkoba" role="dialog"
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
      Form Tambah Data Per Provinsi Dir Puslitdatin
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">
    <form action="" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
      <input type="hidden" name="form_method" value="create">
      <div class="form-body">

        <div class="form-group">
          <label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Nama Provinsi</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <select class="form-control select2" name="id_provinsi">
              <option>-- Pilih Propinsi -- </option>
              @if($propinsi)
                @foreach($propinsi as $p)
                  <option value="{{$p->id_wilayah}}" {{ ( isset($data->id_provinsi) ? ($p->id_wilayah == $data->id_provinsi) ? 'selected="selected"':"" : "")}} > {{$p->nm_wilayah}}</option>
                @endforeach
              @endif
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Angka Prevalensi (%)</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
              <input value="" id="tahun" name="tahun" type="text" class="form-control">
          </div>
        </div>

        <div class="form-group">
          <label for="tgl_pelaksanaan" class="col-md-3 col-sm-3 col-xs-12 control-label">Angka Absolut/Setara</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
              <input value="" id="tahun" name="tahun" type="text" class="form-control">
          </div>
        </div>

        <div class="clear"></div>
        <br/>
        <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-3"> Proyeksi Provinsi </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
          </div>
        </div>
        <div class="clear"></div>
        <br/>

        <div class="form-group">
          <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +1</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
              <input value="" id="prevalensi_thn1" name="prevalensi_thn1" type="text" class="form-control numeric" onKeydown="decimal(event,this)">
          </div>
      </div>

      <div class="form-group">
          <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +2</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
              <input value="" id="prevalensi_thn2" name="prevalensi_thn2" type="text" class="form-control numeric" onKeydown="decimal(event,this)">
          </div>
      </div>

      <div class="form-group">
          <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +3</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
              <input value="" id="prevalensi_thn3" name="prevalensi_thn3" type="text" class="form-control numeric" onKeydown="decimal(event,this)">
          </div>
      </div>

      <div class="form-group">
          <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +4</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
              <input value="" id="prevalensi_thn4" name="prevalensi_thn4" type="text" class="form-control numeric" onKeydown="decimal(event,this)">
          </div>
      </div>

      <div class="form-group">
          <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +5</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
              <input value="" id="prevalensi_thn5" name="prevalensi_thn5" type="text" class="form-control numeric" onKeydown="decimal(event,this)">
          </div>
      </div>

        <div class="clear"></div>
        <br/>
        <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-3"> Kerugian </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
          </div>
        </div>
        <div class="clear"></div>
        <br/>

        <div class="form-group">
          <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +1</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
              <input value="" id="prevalensi_thn1" name="prevalensi_thn1" type="text" class="form-control numeric" onKeydown="decimal(event,this)">
          </div>
      </div>

      <div class="form-group">
          <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +2</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
              <input value="" id="prevalensi_thn2" name="prevalensi_thn2" type="text" class="form-control numeric" onKeydown="decimal(event,this)">
          </div>
      </div>

      <div class="form-group">
          <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +3</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
              <input value="" id="prevalensi_thn3" name="prevalensi_thn3" type="text" class="form-control numeric" onKeydown="decimal(event,this)">
          </div>
      </div>

      <div class="form-group">
          <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +4</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
              <input value="" id="prevalensi_thn4" name="prevalensi_thn4" type="text" class="form-control numeric" onKeydown="decimal(event,this)">
          </div>
      </div>

      <div class="form-group">
          <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +5</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
              <input value="" id="prevalensi_thn5" name="prevalensi_thn5" type="text" class="form-control numeric" onKeydown="decimal(event,this)">
          </div>
      </div>

        <div class="clear"></div>
        <br/>
        <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-3"> Proyeksi Kerugian </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
          </div>
        </div>
        <div class="clear"></div>
        <br/>

        <div class="form-group">
          <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +1</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
              <input value="" id="prevalensi_thn1" name="prevalensi_thn1" type="text" class="form-control numeric" onKeydown="decimal(event,this)">
          </div>
      </div>

      <div class="form-group">
          <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +2</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
              <input value="" id="prevalensi_thn2" name="prevalensi_thn2" type="text" class="form-control numeric" onKeydown="decimal(event,this)">
          </div>
      </div>

      <div class="form-group">
          <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +3</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
              <input value="" id="prevalensi_thn3" name="prevalensi_thn3" type="text" class="form-control numeric" onKeydown="decimal(event,this)">
          </div>
      </div>

      <div class="form-group">
          <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +4</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
              <input value="" id="prevalensi_thn4" name="prevalensi_thn4" type="text" class="form-control numeric" onKeydown="decimal(event,this)">
          </div>
      </div>

      <div class="form-group">
          <label for="jumlah_responden" class="col-md-3 col-sm-3 col-xs-12 control-label">Tahun +5</label>
          <div class="col-md-9 col-sm-9 col-xs-12">
              <input value="" id="prevalensi_thn5" name="prevalensi_thn5" type="text" class="form-control numeric" onKeydown="decimal(event,this)">
          </div>
      </div>

      </div>
      </form>

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
</div>
</div>
