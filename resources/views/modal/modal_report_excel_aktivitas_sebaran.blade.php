<!-- Modal -->
<div class="modal fade" id="modal_report_excel" tabindex="-1" role="dialog"
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
      Report Data By Date
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">

    <form class="form-horizontal" action="" method="post" role="form" id="form_report_excel">
      {{csrf_field()}}
      <div class="form-group">
        <label  class="control-label col-md-3" for="tanggal">Tanggal Awal</label>
        <div class="col-sm-9">
          <input type="text" class="form-control tanggal" name="date_from" id="date_from"/>
        </div>
      </div>
      <div class="form-group">
        <label  class="control-label col-md-3" for="tanggal">Tanggal Akhir</label>
        <div class="col-sm-9">
          <input type="text" class="form-control tanggal" name="date_to" id="date_to"/>
        </div>
      </div>

    <!-- Modal Footer -->
    <div class="modal-footer">
      <button type="button" class="btn btn-default"
      data-dismiss="modal">
      Batal
    </button>
    <button type="submit" class="btn btn-success">
      Unduh
    </button>
  </div>
</form>
</div>
</div>
</div>
