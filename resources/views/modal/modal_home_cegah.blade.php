<!-- Modal -->
<div class="modal fade" id="modal_home_cegah" tabindex="-1" role="dialog"
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
        Menu Pencegahan
      </h4>
    </div>
    @php $menu = Session::get("menu"); @endphp
    <!-- Modal Body -->
    <div class="modal-body">
      <ul class="">
        <li @php if(!in_array(49, $menu))  echo 'style="display:none;"'; @endphp><span style="color:white; font-size:16px;">Direktorat Advokasi</span>
            <ul class="">
                <li @php if(!in_array(51, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pencegahan/dir_advokasi/pendataan_koordinasi')}}">Kegiatan Rapat Koordinasi</a></li>
                <li @php if(!in_array(52, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pencegahan/dir_advokasi/pendataan_jejaring')}}">Kegiatan Membangun Jejaring</a></li>
                <li @php if(!in_array(53, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pencegahan/dir_advokasi/pendataan_asistensi')}}">Kegiatan Asistensi</a></li>
                <li @php if(!in_array(55, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pencegahan/dir_advokasi/pendataan_intervensi')}}">Kegiatan Intervensi</a></li>
                <li @php if(!in_array(56, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pencegahan/dir_advokasi/pendataan_supervisi')}}">Kegiatan Supervisi</a></li>
                <li @php if(!in_array(57, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pencegahan/dir_advokasi/pendataan_monitoring')}}">Kegiatan Monitoring dan Evaluasi</a></li>
                <li @php if(!in_array(58, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pencegahan/dir_advokasi/pendataan_bimbingan')}}">Kegiatan Bimbingan Teknis</a></li>
                <li @php if(!in_array(59, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pencegahan/dir_advokasi/pendataan_sosialisasi')}}">Kegiatan KIE</a></li>
            </ul>
        </li>
        <li @php if(!in_array(50, $menu))  echo 'style="display:none;"'; @endphp><span style="color:white; font-size:16px;">Direktorat Diseminasi Informasi</span>
            <ul class="">
                <li @php if(!in_array(60, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pencegahan/dir_diseminasi/pendataan_online')}}">Media Online</a></li>
                <li @php if(!in_array(61, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pencegahan/dir_diseminasi/pendataan_penyiaran')}}">Media Penyiaran</a></li>
                <li @php if(!in_array(62, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pencegahan/dir_diseminasi/pendataan_cetak')}}">Media Cetak</a></li>
                <li @php if(!in_array(63, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pencegahan/dir_diseminasi/pendataan_konvensional')}}">Media Konvensional</a></li>
            </ul>
        </li>
      </ul>
    </div>
  </div>
</div>
</div>
