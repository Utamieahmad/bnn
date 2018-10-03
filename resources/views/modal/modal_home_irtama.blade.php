<!-- Modal -->
<div class="modal fade" id="modal_home_irtama" tabindex="-1" role="dialog"
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
        Menu Inspektorat Utama
      </h4>
    </div>
    @php $menu = Session::get("menu"); @endphp
    <!-- Modal Body -->
    <div class="modal-body">
      <ul class="">
          <li @php if(!in_array(117, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('irtama/audit/irtama_audit')}}">Laporan Hasil Audit</a></li>
          <li @php if(!in_array(118, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('irtama/ptl/irtama_ptl')}}">Pemantauan Tindak Lanjut</a></li>
          <li @php if(!in_array(119, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('irtama/riktu/irtama_riktu')}}">Audit dengan Tujuan Tertentu</a></li>
          <li @php if(!in_array(120, $menu))  echo 'style="display:none;"'; @endphp><span style="color:white; font-size:16px;">Reviu</span>
            <ul class="">
                <li @php if(!in_array(126, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('irtama/reviu/irtama_lk')}}">Laporan Keuangan</a></li>
                <li @php if(!in_array(127, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('irtama/reviu/irtama_rkakl')}}">Rencana Kerja Anggaran Kementerian/Lembaga</a></li>
                <li @php if(!in_array(128, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('irtama/reviu/irtama_rkbmn')}}">Rencana Kebutuhan Barang Milik Negara</a></li>
                <li @php if(!in_array(129, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('irtama/reviu/irtama_lkip')}}">Laporan Kinerja Instansi Pemerintah</a></li>
            </ul>
          </li>
          <li @php if(!in_array(121, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('irtama/sosialisasi/irtama_sosialisasi')}}">Sosialisasi</a></li>
          <li @php if(!in_array(122, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('irtama/verifikasi/irtama_verifikasi')}}">Verifikasi</a></li>
          <li @php if(!in_array(123, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('irtama/sop/irtama_sop')}}">SOP dan Kebijakan</a></li>
          <li @php if(!in_array(124, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('irtama/penegakan/irtama_penegakan')}}">Penegakan Disiplin</a></li>
          <li @php if(!in_array(125, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('irtama/apel/irtama_apel')}}">Apel Senin &amp; Upacara Hari Besar Lainnya</a></li>
      </ul>
    </div>
  </div>
</div>
</div>
