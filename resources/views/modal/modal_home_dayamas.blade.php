<!-- Modal -->
<div class="modal fade" id="modal_home_dayamas" tabindex="-1" role="dialog"
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
        Menu Pemberdayaan Masyarakat
      </h4>
    </div>
    @php $menu = Session::get("menu"); @endphp
    <!-- Modal Body -->
    <div class="modal-body">
      <ul class="">
        <li @php if(!in_array(65, $menu))  echo 'style="display:none;"'; @endphp><span style="color:white; font-size:16px;">Direktorat Peran Serta Masyarakat</span>
            <ul class="">
                <li @php if(!in_array(67, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pemberdayaan/dir_masyarakat/pendataan_tes_narkoba')}}">Tes Narkoba</a></li>
                <li @php if(!in_array(68, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pemberdayaan/dir_masyarakat/pendataan_anti_narkoba')}}">Pengembangan Kapasitas</a></li>
                <li @php if(!in_array(69, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pemberdayaan/dir_masyarakat/pendataan_pelatihan')}}">Bimbingan Teknis</a></li>
                <li @php if(!in_array(71, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pemberdayaan/dir_masyarakat/psm_supervisi')}}">Monitoring dan Evaluasi</a></li>
                <li @php if(!in_array(72, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pemberdayaan/dir_masyarakat/rapat_kerja_pemetaan')}}">Rapat Kerja Pemetaan</a></li>
            </ul>
        </li>
        <li @php if(!in_array(66, $menu))  echo 'style="display:none;"'; @endphp><span style="color:white; font-size:16px;">Direktorat Alternative Development</span>
            <ul class="">
                <li @php if(!in_array(73, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pemberdayaan/dir_alternative/altdev_lahan_ganja')}}">Alih Fungsi Lahan Ganja</a></li>
                <li @php if(!in_array(74, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pemberdayaan/dir_alternative/altdev_alih_profesi')}}">Alih Jenis Profesi/Usaha</a></li>
                <li @php if(!in_array(75, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pemberdayaan/dir_alternative/altdev_kawasan_rawan')}}">Kawasan Rawan Narkoba</a></li>
                <li @php if(!in_array(76, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pemberdayaan/dir_alternative/altdev_monitoring')}}">Monitoring dan Evaluasi Kawasan Rawan Narkotika</a></li>
                <li @php if(!in_array(77, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pemberdayaan/dir_alternative/altdev_sinergi')}}">Sinergi</a></li>
                <li @php if(!in_array(116, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{route('alv_rapat_kerja_pemetaan')}}">Rapat Kerja Pemetaan</a></li>
            </ul>
        </li>
      </ul>
    </div>
  </div>
</div>
</div>
