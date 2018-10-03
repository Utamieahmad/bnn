<div class="modal fade bs-modal-sm" tabindex="-1" role="dialog" id="modalDelete" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
          <form action="{{isset($delete_route) ? route($delete_route) : ''}}" class="form-horizontal" id="frm_add" method="post" enctype="multipart/form-data" autocomplete="on">
            {{csrf_field()}}
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel2">Hapus Data</h4>
            </div>
            <div class="modal-body">
              <div class="content">
                Apakah Anda ingin menghapus data ini ?
              </div>
              <div class="alert-message">

              </div>
            </div>
            <input type="hidden" class="target_id" value=""/>
            <input type="hidden" class="target_index" value=""/>
            <input type="hidden" class="target_type" value=""/>
            <input type="hidden" class="parent_id" value="{{$data->id}}"/>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
              <button type="button" class="btn btn-primary confirm" onclick="delete_row(event,this)">Ya</button>
            </div>
            <div class="modal-footer-loading alert">
              <p> Loading ... </p>
            </div>

          </form>
    </div>
  </div>
</div>