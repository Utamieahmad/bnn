<!-- Modal -->
<div class="modal fade" id="modal_home_rehab" tabindex="-1" role="dialog"
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
        Menu Rehabilitasi
      </h4>
    </div>
    @php $menu = Session::get("menu"); @endphp
    <!-- Modal Body -->
    <div class="modal-body">
      <ul class="">
        <li @php if(!in_array(29, $menu))  echo 'style="display:none;"'; @endphp><span style="color:white; font-size:16px;">Direktorat PLRIP</span>
            <ul class="">
                <li @php if(!in_array(32, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('rehabilitasi/dir_plrip/informasi_lembaga_umum_plrip')}}">Informasi Umum Lembaga Rehabilitasi Instansi Pemerintah</a></li>
                <li @php if(!in_array(34, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('rehabilitasi/dir_plrip/dokumen_nspk_plrip')}}">Dokumen NSPK</a></li>
                <li @php if(!in_array(35, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('rehabilitasi/dir_plrip/kegiatan_pelatihan_plrip')}}">Kegiatan</a></li>
            </ul>
        </li>
        <li @php if(!in_array(30, $menu))  echo 'style="display:none;"'; @endphp><span style="color:white; font-size:16px;">Direktorat PLRKM</span>
            <ul class="">
                <li @php if(!in_array(37, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('rehabilitasi/dir_plrkm/informasi_lembaga_umum_plrkm')}}">Informasi Umum Lembaga Rehabilitasi Komponen Masyarakat</a></li>
                <li @php if(!in_array(39, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('rehabilitasi/dir_plrkm/dokumen_nspk_plrkm')}}">Dokumen NSPK</a></li>
                <li @php if(!in_array(40, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('rehabilitasi/dir_plrkm/kegiatan_pelatihan_plrkm')}}">Kegiatan</a></li>
            </ul>
        </li>
        <li @php if(!in_array(31, $menu))  echo 'style="display:none;"'; @endphp><span style="color:white; font-size:16px;">Direktorat Pascarehabilitasi</span>
            <ul class="">
                <li @php if(!in_array(45, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('rehabilitasi/dir_pasca/informasi_lembaga_umum_pascarehabilitasi')}}">Informasi Umum Lembaga</a></li>
                <li @php if(!in_array(46, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('rehabilitasi/dir_pasca/dokumen_nspk_pascarehabilitasi')}}">Dokumen NSPK</a></li>
                <li @php if(!in_array(47, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('rehabilitasi/dir_pasca/kegiatan_pelatihan_pascarehabilitasi')}}">Kegiatan</a></li>
            </ul>
        </li>
      </ul>
    </div>
  </div>
</div>
</div>
