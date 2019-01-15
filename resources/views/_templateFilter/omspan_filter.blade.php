<div class="">
	<form class="form-group filter" action="{{isset($route) ? route($route) : ''}}" method="post">
	<div id="loader-wrapper">
        <div id="loader"></div>
    </div>
    <div class="row">
      {{csrf_field()}}
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
          <div class="col-md-4 col-sm-4 col-xs-12">
            <label for="kode_satker" class="control-label">Kode Satker</label>
			<input class="form-control" name="kode_satker" value="" />
          </div>
          <div class="col-md-4 col-sm-4 col-xs-12 keyword div-wrap">
            <label for="periode" class="control-label">Sampai Dengan</label>
            <select class="form-control select2"  name="periode">
              <option value="01">Januari</option>
              <option value="02">Februari</option>
              <option value="03">Maret</option>
              <option value="04">April</option>
              <option value="05">Mei</option>
              <option value="06">Juni</option>
              <option value="07">Juli</option>
              <option value="08">Agustus</option>
              <option value="09">September</option>
              <option value="10">Oktober</option>
              <option value="11">November</option>
              <option value="12">Desember</option>
            </select>
          </div>
		  <div class="col-md-4 col-sm-4 col-xs-12 keyword div-wrap">
            <label for="filter_indikator" class="control-label">Filter Indikator</label>
            <select class="form-control select2"  name="filter_indikator">
              <option value="-">-- Semua Indikator --</option>
              <option value="1">Pengelolaan UP</option>
              <option value="2">Data Kontrak</option>
              <option value="3">Kesalahan SPM</option>
              <option value="4">Retur SP2D</option>
              <option value="5">Hal III Dipa</option>
              <option value="6">Revisi Dipa</option>
              <option value="7">Penyelesaian Tagihan</option>
              <option value="8">Rekon LPJ</option>
              <option value="9">Renkas</option>
              <option value="10">Realisasi</option>
            </select>
          </div>
        </div>


        <div class="clearfix"></div>
        <div class="m-t-10 m-b-20">
          <div class="row">		  
            <div class="col-sm-6 col-md-6 col-xs-12">
				<div class="clearfix"></div>
                <div class="m-t-10">
                  <input type="submit" class="btn btn-success btn-search" value="Cari" name="cari"/>
                  <a href="{{isset($route) ? route($route) : ''}}" class="btn btn-primary">Hapus</a>
                </div>
              </div>
            </div>
          </div>

      </div>
    </div>
  </form>

  <div class="m-b-20">
    <div class="m-b-20">
     
    </div>

  </div>
</div>
