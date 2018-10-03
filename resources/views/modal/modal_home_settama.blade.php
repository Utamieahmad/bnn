<!-- Modal -->
<div class="modal fade" id="modal_home_settama" tabindex="-1" role="dialog"
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
        Menu Sekretariat Utama
      </h4>
    </div>
    @php $menu = Session::get("menu"); @endphp
    <!-- Modal Body -->
    <div class="modal-body">
      <ul class="">
        <!-- <li @php if(!in_array(112, $menu))  echo 'style="display:none;"'; @endphp>
          <a href="{{-- route('sekretariat_utama') --}}">Sekretariat Utama</a>
        </li> -->
        <li @php if(!in_array(112, $menu))  echo 'style="display:none;"'; @endphp >
          <a class="modalmenu" href="{{route('settama_umum')}}">Biro Umum</a>
        </li>
        <li @php if(!in_array(113, $menu))  echo 'style="display:none;"'; @endphp>
          <a class="modalmenu" href="{{route('settama_keuangan')}}">Biro Keuangan</a>
        </li>
        <li @php if(!in_array(114, $menu))  echo 'style="display:none;"'; @endphp>
          <a class="modalmenu" href="{{route('settama_kepegawaian')}}">Biro Kepegawaian dan Organisasi</a>
        </li>
        <li @php if(!in_array(115, $menu))  echo 'style="display:none;"'; @endphp>
          <a class="modalmenu" href="{{route('settama_perencanaan')}}">Biro Perencanaan</a>
        </li>
      </ul>
    </div>
  </div>
</div>
</div>
