<div class="">
  <form class="form-group filter" action="{{isset($route) ? route($route) : ''}}" method="post">
    <div class="row">
      {{csrf_field()}}
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="row">
        <?php
          $key = [
            'user' => 'Nama',
            'pelaksana' => 'Satker',
            'periode' => 'Waktu',
            'menu' => 'Menu',
            'event' => 'Event',

            // 'menu' => 'Menu',
            // 'event' => 'Event',
            // 'url' => 'URL',
            // 'ip' => 'URL',
            // 'user' => 'User',
            // 'periode' => 'Waktu',
          ];
        ?>

          <div class="col-md-6 col-sm-6 col-xs-12">
            <label for="tipe" class="control-label">Tipe</label>

            <select class="form-control select2"  name="tipe" onchange="formFilter(this)">
              <option value="">SEMUA</option>
              <option value="user" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'user') ? 'selected=selected' : '') :'')}}>Nama</option>
              <option value="pelaksana" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'pelaksana') ? 'selected=selected' : '') :'')}}>Satker</option>
              <option value="periode" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'periode') ? 'selected=selected' : '') :'')}}>Waktu</option>
              <option value="menu" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'menu') ? 'selected=selected' : '') :'')}}>Menu</option>
              <option value="event" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'event') ? 'selected=selected' : '') :'')}}>Event</option>

              <!-- <option value="menu" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'menu') ? 'selected=selected' : '') :'')}}>Menu</option>
              <option value="event" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'event') ? 'selected=selected' : '') :'')}}>Event</option>
              <option value="url" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'url') ? 'selected=selected' : '') :'')}}>URL</option>
              <option value="ip" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'ip') ? 'selected=selected' : '') :'')}}>IP</option>
              <option value="user" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'user') ? 'selected=selected' : '') :'')}}>User</option>
              <option value="periode" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'periode') ? 'selected=selected' : '') :'')}}>Waktu</option> -->
            </select>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12 keyword {{(isset($filter['tipe']) ? (($filter['tipe'] == 'periode' ) ? 'hide' : '') :'')}} div-wrap">
            <label for="tipe" class="control-label">Kata Kunci</label>
            <input class="form-control" name="kata_kunci" value="{{isset($filter) ? (isset($filter['kata_kunci'])?$filter['kata_kunci']:'') :''}}" {{ (isset($filter['kata_kunci']) || isset($filter['tipe'])) ?  '':'disabled=disabled'}}  />
          </div>

          {{-- <div class="col-md-6 col-sm-6 col-xs-12 pelaksana {{(isset($filter['tipe']) ? ((($filter['tipe'] == 'pelaksana' )  )  ? '' : 'hide') :'hide')}} div-wrap">
              <label for="tipe" class="control-label">Tipe</label>

              <select class="form-control select2"  name="pelaksana">
                <option>-- Pilih Instansi -- </option>
                @if(count($instansi))
                  @foreach($instansi as $k => $val)
                    <option value="{{$val['nm_instansi']}}" {{isset($filter) ? ( isset($filter['pelaksana'])? ($filter['pelaksana'] == $val['nm_instansi']? 'selected=selected':''): '' ): ''}}>{{$val['nm_instansi']}}</option>
                  @endforeach
                @endif
              </select>
            </div> --}}

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
          {{-- @elseif($s['tipe'] == 'pelaksana')
            {{  ( isset($s['pelaksana'])  ? $s['pelaksana'] .', ' :  $s['pelaksana'] .', ') }} --}}
          @else
            {{isset($s['kata_kunci']) ? $s['kata_kunci'] : ''}}
          @endif
        @else
          {{isset($s['kata_kunci']) ? $s['kata_kunci'] : ''}} =
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
