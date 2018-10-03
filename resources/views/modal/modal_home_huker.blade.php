<!-- Modal -->
<div class="modal fade" id="modal_home_huker" tabindex="-1" role="dialog"
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
        Menu Hukum dan Kerjasama
      </h4>
    </div>
    @php $menu = Session::get("menu"); @endphp
    <!-- Modal Body -->
    <div class="modal-body">
      <ul class="">
        <li @php if(!in_array(78, $menu))  echo 'style="display:none;"'; @endphp><span style="color:white; font-size:16px;">Direktorat Hukum</span>
            <ul class="">
              <li @php if(!in_array(80, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('huker/dir_hukum/hukum_nonlitigasi')}}">Non Litigasi (Konsultasi)</a></li>
              <li @php if(!in_array(81, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('huker/dir_hukum/hukum_audiensi')}}">Audiensi (Konsultasi)</a></li>
              <li @php if(!in_array(82, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('huker/dir_hukum/hukum_pendampingan')}}">Pendampingan (Litigasi)</a></li>
              <li @php if(!in_array(83, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('huker/dir_hukum/hukum_prapradilan')}}">Pra Peradilan (Litigasi)</a></li>
              <li @php if(!in_array(84, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('huker/dir_hukum/hukum_perka')}}">Penyusunan Perka</a></li>
              <li @php if(!in_array(85, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('huker/dir_hukum/hukum_lainnya')}}">Kegiatan Lainnya</a></li>
            </ul>
        </li>
        <li @php if(!in_array(79, $menu))  echo 'style="display:none;"'; @endphp><span style="color:white; font-size:16px;">Direktorat Kerja Sama</span>
            <ul class="">
              <li @php if(!in_array(88, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('huker/dir_kerjasama/kerjasama_bilateral')}}">Pertemuan</a></li>
              <li @php if(!in_array(89, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('huker/dir_kerjasama/kerjasama_kesepemahaman')}}">Nota Kesepahaman</a></li>
              <li @php if(!in_array(90, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('huker/dir_kerjasama/kerjasama_lainnya')}}">Kerja Sama Lainnya</a></li>
              <li @php if(!in_array(91, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('huker/dir_kerjasama/kerjasama_monev')}}">Monitoring dan Evaluasi Pelaksanaan Kerja Sama</a></li>
            </ul>
        </li>
      </ul>
    </div>
  </div>
</div>
</div>
