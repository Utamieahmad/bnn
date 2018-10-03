
<div class="modal fade" id="modal_data_rekomendasi_audit" role="dialog"
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
      Data Rekomendasi Audit
    </h4>
  </div>
  <!-- Modal Body -->
  <div class="modal-body">
    <div class="tools pull-right" style="margin-bottom:15px;">
      <button class="btn btn-success" data-toggle="modal" data-target="#modal_input_rekomendasi_audit">Tambah Rekomendasi</button>
    </div>
    <table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%" style="color:#f7f7f7">
      <thead>
        <tr>
          <th>Judul Rekomendasi</th>
          <th>Kode Rekomendasi</th>
          <th>Nilai Rekomendasi</th>
          <th >Audit Penanggung Jawab</th>
          <th class="hide">Status</th>
          <th class="hide">Bukti yang Sudah Tindak Lanjut</th>
          <th class="hide">Bukti Tindak Lanjut</th>
        </tr>
      </thead>
      <tbody>
        
        <tr>
          <td> a </td>
          <td> b </td>
          <td> c </td>
          <td> d </td>
          <td class="hide"> e </td>
          <td class="hide"> f </td>
          <td class="hide"> g </td>
        </tr>
        
      </tbody>
    </table>

      <!-- Modal Footer -->
      
  </form>
</div>
</div>
</div>
@include ('modal.modal_input_rekomendasi_audit')