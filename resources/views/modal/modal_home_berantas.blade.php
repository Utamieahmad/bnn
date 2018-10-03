<!-- Modal -->
<div class="modal fade" id="modal_home_berantas" tabindex="-1" role="dialog"
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
        Menu Pemberantasan
      </h4>
    </div>
    @php $menu = Session::get("menu"); @endphp
    <!-- Modal Body -->
    <div class="modal-body">
      <ul class="">
        <li @php if(!in_array(12, $menu))  echo 'style="display:none;"'; @endphp><span style="color:white; font-size:16px;">Direktorat Narkotika</span>
            <ul class="">
                <li @php if(!in_array(19, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pemberantasan/dir_narkotika/pendataan_lkn')}}">Pendataan LKN (Kasus, tersangka, penerapan pasal, dan barang bukti)</a></li>
                <li @php if(!in_array(20, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pemberantasan/dir_narkotika/pendataan_pemusnahan_ladangganja')}}">Pendataan Pemusnahan Ladang Tanaman Narkotika</a></li>
            </ul>
        </li>
        <li @php if(!in_array(13, $menu))  echo 'style="display:none;"'; @endphp><span style="color:white; font-size:16px;">Direktorat Psikotropika dan Prekursor</span>
            <ul class="">
                <li @php if(!in_array(21, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pemberantasan/dir_psikotropika/psi_pendataan_lkn')}}">Pendataan LKN (Kasus, tersangka, penerapan pasal, dan barang bukti)</a></li>
            </ul>
        </li>
        <li @php if(!in_array(14, $menu))  echo 'style="display:none;"'; @endphp><span style="color:white; font-size:16px;">Direktorat TPPU</span>
            <ul class="">
                <li @php if(!in_array(22, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pemberantasan/dir_tppu/pendataan_tppu')}}">Pendataan TPPU</a></li>
            </ul>
        </li>
        <li @php if(!in_array(15, $menu))  echo 'style="display:none;"'; @endphp><span style="color:white; font-size:16px;">Direktorat Intelijen</span>
            <ul class="">
                <li @php if(!in_array(23, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pemberantasan/dir_intelijen/pendataan_jaringan')}}">Pendataan Jaringan Narkoba yang Sudah Diungkap</a></li>
            </ul>
        </li>
        <li @php if(!in_array(16, $menu))  echo 'style="display:none;"'; @endphp><span style="color:white; font-size:16px;">Direktorat Wastahti</span>
            <ul class="">
                <li @php if(!in_array(24, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pemberantasan/dir_wastahti/pendataan_brgbukti')}}">Pendataan Pemusnahan Barang Bukti</a></li>
                <li @php if(!in_array(25, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pemberantasan/dir_wastahti/pendataan_tahanan')}}">Pendataan Tahanan di BNN dan BNNP</a></li>
            </ul>
        </li>
        <li @php if(!in_array(17, $menu))  echo 'style="display:none;"'; @endphp><span style="color:white; font-size:16px;">Direktorat Penindakan dan Pengejaran</span>
            <ul class="">
                <li @php if(!in_array(26, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pemberantasan/dir_penindakan/pendataan_dpo')}}">Input Daftar Pencarian Orang (DPO)</a></li>
            </ul>
        </li>
        <li @php if(!in_array(18, $menu))  echo 'style="display:none;"'; @endphp><span style="color:white; font-size:16px;">Direktorat Interdiksi</span>
            <ul class="">
                <li @php if(!in_array(27, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('pemberantasan/dir_interdiksi/pendataan_intdpo')}}">Pendataan LKN (Kasus, tersangka, penerapan pasal, dan barang bukti)</a></li>
            </ul>
        </li>
      </ul>
    </div>
  </div>
</div>
</div>
