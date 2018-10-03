<!-- Modal -->
<div class="modal fade" id="modal_home_master" tabindex="-1" role="dialog"
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
        Menu Master Data
      </h4>
    </div>
    @php $menu = Session::get("menu"); @endphp
    <!-- Modal Body -->
    <div class="modal-body">
      <ul class="">
        <li @php if(!in_array(134, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('master/instansi')}}">Master Instansi<span class="fa"></span></a>
        </li>
        <li @php if(!in_array(135, $menu))  echo 'style="display:none;"'; @endphp><span style="color:white; font-size:16px;">Master Wilayah</span>
            <ul class="">
                <li @php if(!in_array(140, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('master/propinsi')}}">Master Propinsi</a></li>
                <li @php if(!in_array(141, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('master/kota')}}">Master Kota/Kabupaten</a></li>
                <!--li><a href="{{url('master/kecamatan')}}">Master Kecamatan</a></li-->
            </ul>
        </li>
        <li  @php if(!in_array(136, $menu))  echo 'style="display:none;"'; @endphp><span style="color:white; font-size:16px;">Master Kasus</span>
            <ul class="">
                <li @php if(!in_array(142, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('master/jeniskasus')}}">Master Jenis Kasus</a></li>
                <li @php if(!in_array(143, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('master/jenisbarbuk')}}">Master Jenis Barang Bukti</a></li>
                <li @php if(!in_array(144, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('master/barangbukti')}}">Master Barang Bukti</a></li>
                <li @php if(!in_array(145, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('master/satuan')}}">Master Satuan</a></li>
            </ul>
        </li>
        <li @php if(!in_array(137, $menu))  echo 'style="display:none;"'; @endphp><span style="color:white; font-size:16px;">Master Media</span>
            <ul class="">
                <li @php if(!in_array(146, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('master/mediaonline')}}">Master Media Online</a></li>
                <li @php if(!in_array(147, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('master/mediasosial')}}">Master Media Sosial</a></li>
                <li @php if(!in_array(148, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('master/mediacetak')}}">Master Media Cetak</a></li>
                <li @php if(!in_array(149, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('master/mediaruang')}}">Master Media Luar Ruang</a></li>
            </ul>
        </li>
        <li @php if(!in_array(138, $menu))  echo 'style="display:none;"'; @endphp><span style="color:white; font-size:16px;">Master Settama</span>
            <ul class="">
                <li @php if(!in_array(150, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('master/bagian')}}">Master Bagian</a></li>
                <li @php if(!in_array(151, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('master/kegiatan')}}">Master Kegiatan</a></li>
            </ul>
        </li>
        <li @php if(!in_array(139, $menu))  echo 'style="display:none;"'; @endphp><span style="color:white; font-size:16px;">Master Dayamas</span>
            <ul class="">
                <li @php if(!in_array(152, $menu))  echo 'style="display:none;"'; @endphp><a class="modalmenu" href="{{url('master/komoditi')}}">Master Komoditi</a></li>
            </ul>
        </li>
      </ul>
    </div>
  </div>
</div>
</div>
