<div class="">
  <form class="form-group filter" action="{{isset($route) ? route($route) : ''}}" method="post">
    <div class="row">
      {{csrf_field()}}
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="row">
        <?php
          $key = [
            'pelaksana' => 'Pelaksana',
            'periode' => 'Tanggal',
            'materi' => 'Materi',
            'narasumber' => 'Narasumber',
            'anggaran' => 'Sumber Anggaran',
            'kelengkapan' => 'Status Kelengkapan',
          ];
        ?>

          <div class="col-md-6 col-sm-6 col-xs-12">
            <label for="tipe" class="control-label">Tipe</label>

            <select class="form-control select2"  name="tipe" onchange="formFilter(this)">
              <option value="">SEMUA</option>
              <option value="pelaksana" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'pelaksana') ? 'selected=selected' : '') :'')}}>Pelaksana</option>
              <option value="periode" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'periode') ? 'selected=selected' : '') :'')}}>Waktu</option>
              <option value="materi" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'materi') ? 'selected=selected' : '') :'')}}>Materi</option>
              <option value="narasumber" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'narasumber') ? 'selected=selected' : '') :'')}}>Narasumber</option>
              {{-- <option value="anggaran" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'anggaran') ? 'selected=selected' : '') :'')}}>Sumber Anggaran</option> --}}
              <option value="kelengkapan" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'kelengkapan') ? 'selected=selected' : '') :'')}}>Status Kelengkapan</option>
            </select>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12 keyword {{(isset($filter['tipe']) ? ((($filter['tipe'] == 'pelaksana') || ($filter['tipe'] == 'periode') || ($filter['tipe'] == 'anggaran') || ($filter['tipe'] == 'kelengkapan') ) ? 'hide' : '') :'')}} div-wrap">
            <label for="tipe" class="control-label">Kata Kunci</label>
            <input class="form-control" name="kata_kunci" value="{{isset($filter) ? (isset($filter['keyword'])?$filter['keyword']:'') :''}}" {{ (isset($filter['keyword']) || isset($filter['tipe'])) ?  '':'disabled=disabled'}}  />
          </div>

          <div class="col-md-6 col-sm-6 col-xs-12 pelaksana {{(isset($filter['tipe']) ? (($filter['tipe'] == 'pelaksana') ? '' : 'hide') :'hide')}} div-wrap">
            <label for="tipe" class="control-label">Pelaksana</label>
            <select class="form-control select2" name="pelaksana" id="pelaksana" onchange="getnama(this);">
              <option>-- Pilih Pelaksana --</option>
                @php
                  $instansi = globalinstansi();
                @endphp
                @foreach($instansi as $in)
                <option value="{{$in['nm_instansi']}}" {{isset($filter) ? (isset($filter['pelaksana'])? ( ($filter['pelaksana'] == $in['nm_instansi']) ? 'selected=selected' : ''):'') :''}} data-nm="{{$in['nm_instansi']}}">{{$in['nm_instansi']}}</option>
                @endforeach
            </select>
            <input name="namapelaksana" id="namapelaksana" type="hidden" value="{{isset($filter['pelaksana']) ? $filter['pelaksana'] : ''}}"/>
          </div>
          <script>
            function getnama(nama){
              var option = $('option:selected', nama).attr('data-nm');
              //alert(option);
              $('#namapelaksana').val(option);
            }
          </script>

          {{-- <div class="col-md-6 col-sm-6 col-xs-12 anggaran {{isset($filter['tipe']) ? ( $filter['tipe'] == 'anggaran' ? '':'hide'): 'hide'}} div-wrap" >
              <label for="tipe" class="control-label">Sumber Anggaran</label>
              <select class="form-control select2" name="anggaran">
                <option value="DIPA" {{isset($filter)? (isset($filter['anggaran']) ? ($filter['anggaran'] == 'DIPA' ? 'selected=selected' : '') :''):''}} >Dipa</option>
                <option value="NONDIPA"  {{isset($filter)? (isset($filter['anggaran']) ? ($filter['anggaran'] == 'NONDIPA' ? 'selected=selected' : '') :''):''}} >Non Dipa</option>
              </select>
          </div> --}}

          <div class="col-md-6 col-sm-6 col-xs-12 kelengkapan {{isset($filter['tipe']) ? ( $filter['tipe'] == 'kelengkapan' ? '':'hide'): 'hide'}} div-wrap" >
              <label for="tipe" class="control-label">Status Kelengkapan</label>
              <select class="form-control select2" name="kelengkapan">
                <option value="Y" {{isset($filter)? (isset($filter['kelengkapan']) ? ($filter['kelengkapan'] == 'Y' ? 'selected=selected' : '') :''):''}} >Lengkap</option>
                <option value="N"  {{isset($filter)? (isset($filter['kelengkapan']) ? ($filter['kelengkapan'] == 'N' ? 'selected=selected' : '') :''):''}} >Tidak Lengkap</option>
              </select>
          </div>

          <div class="clearfix"></div>
          <div class="periode {{(isset($filter['tipe']) ? (($filter['tipe'] == 'periode') ? '' : 'hide') :'hide')}} div-wrap">
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Mulai</label>
              <div class='input-group date tanggal-publish row'>
                  <input type='text' name="waktu_from" class="form-control" value="{{isset($filter) ? (isset($filter['waktu_from']) ? $filter['waktu_from'] : '') :''}}"/>
                  <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div>

            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Selesai</label>
              <div class='input-group date tanggal-publish row'>
                  <input type='text' name="waktu_to" class="form-control" value="{{isset($filter) ? (isset($filter['waktu_to'])?$filter['waktu_to']:'') :''}}"/>
                  <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div>
            </div>

          </div>
        </div>


        <div class="clearfix"></div>
        <div class="m-t-10 m-b-20">
          <div class="row">
            <div class="col-sm-6 col-md-6 col-xs-12">
              <label for="tipe" class="control-label">Urutan</label>
              <select class="form-control select2" name="order">
                <option value="desc" {{isset($filter) ? ( isset($filter['order'])? ($filter['order'] == 'desc' ? 'selected=selected':''): '' ): ''}}>Atas ke bawah</option>
                <option value="asc" {{isset($filter) ? ( isset($filter['order'])? ($filter['order'] == 'asc' ? 'selected=selected':''): '' ): ''}}>Bawah ke atas</option>
              </select>
            </div>
            <div class="col-sm-6 col-md-6 col-xs-12">
              <label for="tipe" class="control-label">Jumlah Per Halaman</label>
              <select class="form-control select2" name="limit">
                <option value="5" {{isset($filter) ? ( isset($filter['limit'])? ($filter['limit'] == '5' ? 'selected=selected':''): '' ): ''}}>5</option>
                <option value="10" {{isset($filter) ? ( isset($filter['limit'])? ($filter['limit'] == '10' ? 'selected=selected':''): 'selected=selected' ): 'selected=selected'}}>10</option>
                <option value="25" {{isset($filter) ?( isset($filter['limit'])? ($filter['limit'] == '25' ? 'selected=selected':''): ''): ''}}>25</option>
                <option value="50" {{isset($filter) ? ( isset($filter['limit'])? ($filter['limit'] == '50' ? 'selected=selected':''): ''): ''}}>50</option>
                <option value="100" {{isset($filter) ? ( isset($filter['limit'])? ($filter['limit'] == '100' ? 'selected=selected':''): ''): ''}}>100</option>
              </select>
              <div class="clearfix"></div>
                <div class="m-t-10">
                  <input type="submit" class="btn btn-success btn-search" value="Cari" name="cari"/>
                  <a href="{{isset($route) ? route($route) : ''}}" class="btn btn-primary">Hapus</a>
                </div>
              </div>
            </div>
          </div>

      </div>
    </div>
  </form>

  <div class="m-b-20">
    <div class="m-b-20">
      @php
        $s = $filter;

      @endphp

      @if(isset($s))
         Menampilkan:
        <i>
        {{ isset($s['tipe']) ? (isset($key[$s['tipe']]) ?$key[$s['tipe']] : '')  : ''}} =
        @if( isset($s['tipe']))
          @if( $s['tipe'] == 'periode')
            {{isset($s['waktu_from']) ? $s['waktu_from'] : ''}} - {{isset($s['waktu_to']) ? $s['waktu_to'] : ''}}
          @elseif($s['tipe'] == 'pelaksana')
            {{  ( isset($s['pelaksana'])  ? $s['pelaksana'] :  '' ) }}
          @elseif($s['tipe'] == 'anggaran')
            {{  ( isset($s['anggaran'])  ? $s['anggaran'] :  '' ) }}
          @elseif($s['tipe'] == 'kelengkapan')
            {{( isset($s['kelengkapan'])  ?( ($s['kelengkapan'] == 'Y' ) ? 'Lengkap' : 'Belum Lengkap') :  '' )}}
          @else
            {{isset($s['keyword']) ? $s['keyword'] : ''}}
          @endif
        @else
          {{isset($s['keyword']) ? $s['keyword'] : ''}} =
        @endif

        , Urutan =

        @if(isset($s['order']))
          @if($s['order'] == 'desc')
            Atas ke bawah
          @elseif($s['order'] == 'asc')
            Bawah ke atas
          @else
            Atas ke bawah
          @endif
        @endif
        , Jumlah Per Halaman =
        @if(isset($s['limit']))
          {{$s['limit']}}
        @endif
        </i>
      @endif

    </div>

  </div>
</div>
