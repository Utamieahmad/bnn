<!-- Modal -->
<div class="modal fade" id="modal_home_datin" tabindex="-1" role="dialog"
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
        Menu Puslitdatin
      </h4>
    </div>
    @php $menu = Session::get("menu"); @endphp
    <!-- Modal Body -->
    <div class="modal-body">
      <ul class="">
        <li @php if(!in_array(95, $menu))  echo 'style="display:none;"'; @endphp><span style="color:white; font-size:16px;">Bidang Litbang</span>
            <ul class="">
                <li @php if(!in_array(98, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('puslitdatin/bidang_litbang/survey')}}">Survey</a></li>
                <li @php if(!in_array(99, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('puslitdatin/bidang_litbang/survey_narkoba')}}">Survey Nasional Penyalahgunaan Narkoba</a></li>
                <li @php if(!in_array(100, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('puslitdatin/bidang_litbang/survey_narkoba_ketergantungan')}}">Survey Nasional Penyalahgunaan Narkoba Berdasarkan Tingkat Ketergantungan</a></li>
                <li @php if(!in_array(105, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('puslitdatin/bidang_litbang/riset_penyalahgunaan_narkoba')}}">Riset Operasional Penyalahgunaan Narkoba di Indonesia</a></li>
            </ul>
        </li>
        <li @php if(!in_array(96, $menu))  echo 'style="display:none;"'; @endphp><span style="color:white; font-size:16px;">Bidang TIK</span>
            <ul class="">
                <li @php if(!in_array(106, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('puslitdatin/bidang_tik/pekerjaan_jaringan')}}">Pekerjaan Jaringan</a></li>
                <li @php if(!in_array(107, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('puslitdatin/bidang_tik/pengecekan_jaringan')}}">Pengecekan dan Pemeliharaan Jaringan LAN</a></li>
                <li @php if(!in_array(108, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('puslitdatin/bidang_tik/pengadaan_email')}}">Pembuatan Email BNN</a></li>
            </ul>
        </li>
        <li @php if(!in_array(97, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('puslitdatin/call_center')}}">Pusat Informasi (CC)<span class="fa"></span></a>
        </li>
      </ul>
    </div>
  </div>
</div>
</div>
