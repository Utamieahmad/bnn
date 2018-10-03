<div role="tabpanel" class="tab-pane fade panel-parent" id="sdm" aria-labelledby="profile-tab">
  <div class="tools pull-right">
    <!-- <button class="btn btn-success" data-toggle="modal" data-target="#modal_bidang_audit">Tambah Temuan</button> -->
    <button class="btn btn-success" onClick="openModalBidangAudit(this,event)">Tambah Temuan</button>
  </div>

  <table class="table table-striped table-bordered table-hover table-checkable" id="grid_tersangka">
    <thead>
      <tr role="row" class="heading">
        <th> Judul Temuan </th>
        <th> Kode Temuan </th>
        <th> Bukti Temuan </th>
        <th> Kriteria </th>
        <th> Sebab </th>
        <th> Akibat </th>
        <th> Tanggapan </th>
        <th> Actions </th>
      </tr>
    </thead>
    <tbody>
      @if(isset($sdm))
        @if(count($sdm) > 0 )
          @foreach($sdm as $k)
            <tr>
              <td>{{$k->judul_temuan}}</td>
              <td>{{$k->kode_temuan}}</td>
              <td>{{$k->bukti_temuan}}</td>
              <td>{{$k->kriteria}}</td>
              <td>{{$k->sebab}}</td>
              <td>{{$k->akibat}}</td>
              <td>{{$k->tanggapan}}</td>
              <td class="actionTable">
                <button class="btn btn-action" data-id ="{{$k->id_lha_bidang}}" onClick="editLhaBidang(event,this)"><i class="fa fa-pencil"></i></button>
                <button class="btn btn-action" data-id ="{{$k->id_lha_bidang}}"   onClick="deleteLhaBidang(event,this)"><i class="fa fa-trash"></i></button>
                <div class="clearfix"></div>
              </td>
            </tr>
          @endforeach

        @else
          <tr>
            <td colspan="8">
                <div class="alert-messages alert-warning">
                  Data Bidang  SDM Belum Tersedia
                </div>
            </td>
          </tr>
          @endif
      @endif
    </tbody>

  </table>
  <input type="hidden" name="tipe" value="sdm"/>
</div>
