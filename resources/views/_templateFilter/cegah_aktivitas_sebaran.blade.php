<div class="">
  <form class="form-group filter" action="{{isset($route) ? route($route) : ''}}" method="post">
      <div class="row" style="width: 100%;">
      {{csrf_field()}}
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
        <?php
          $key = [
            'pelaksana' => 'Instansi',
            'periode' => 'Tanggal LKN',
            'no_lap' => 'Nomor Kasus',
            // 'BrgBukti' => 'Barang Bukti',
            'status_kelengkapan' => 'Status',
          ];

        ?>

          <div class="col-md-3 col-sm-3 col-md-12">
            <label for="tipe" class="control-label">Pembuat</label>
            <input type="text" name="pembuat" class="form-control"/>
          </div>

          <div class="col-md-3 col-sm-3 col-xs-12">
            <label for="tipe" class="control-label">Satker</label>
            <input class="form-control" name="satker" type="text"/>
          </div>
            
          <div class="col-md-3 col-sm-3 col-xs-12">
            <label for="tipe" class="control-label">Wilayah</label>
            <input class="form-control" name="satker" type="text"/>
          </div>
            
          <div class="col-md-3 col-sm-3 col-xs-12">
            <label for="tipe" class="control-label">Media</label>
            <input class="form-control" name="satker" type="text"/>
          </div>
            
          <div class="col-md-3 col-sm-3 col-xs-12">
            <label for="tipe" class="control-label">Tanggal</label>
            <div class="input-group date date_start row">
                <input type='text' name="tgl_from" class="form-control" value="{{isset($filter) ? (isset($filter['tgl_from']) ? $filter['tgl_from'] : '') :''}}"/>
                <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
          </div>          
          <div class="col-md-3 col-sm-3 col-xs-12">
            <label for="tipe" class="control-label">&nbsp;</label>
            <input class="form-control" name="satker" type="text"/>
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
  </form>
  <div class="m-b-20">
    <div class="m-b-20">
      @if(isset($filter))
       @php
        $s = $filter;

      @endphp
         Menampilkan:
        <i>
        {{ isset($s['tipe']) ? (isset($key[$s['tipe']]) ?$key[$s['tipe']] .' = ': '')  : ''}}
        @if( isset($s['tipe']))
          @if( $s['tipe'] == 'periode')
            {{isset($s['tgl_from']) ? $s['tgl_from'] : ''}}
            {{isset($s['tgl_from']) && isset($s['tgl_to'])  ? '-' : ''}}
            {{isset($s['tgl_to']) ?  $s['tgl_to'] .', ' : ', '}}
          @elseif($s['tipe'] == 'pelaksana')
            {{  ( isset($s['pelaksana'])  ? $s['pelaksana'] .', ' :  $s['pelaksana'] .', ') }}
          @elseif($s['tipe'] == 'BrgBukti')
            {{  ( isset($BrgBukti[$s['BrgBukti']])  ? $BrgBukti[$s['BrgBukti']] .', ' :  $s['BrgBukti'] .', ') }}
           @elseif($s['tipe'] == 'status_kelengkapan')
            {{( isset($s['status_kelengkapan'])  ?( ($s['status_kelengkapan'] == 'Y' ) ? 'Lengkap' .' ,' : 'Belum Lengkap'.' ,') :  '' )}}
          @else
            {{isset($s['keyword']) ? $s['keyword'] .', ' : ''}}
          @endif
        @else
          {{isset($s['keyword']) ? $s['keyword'] .' = ': ''}}
        @endif

       Urutan =

        @if(isset($s['order']))
          @if($s['order'] == 'desc')
            Bawah ke Atas   ,
          @elseif($s['order'] == 'asc')
            Atas ke bawah  ,
          @else
            Bawah ke Atas ,
          @endif
        @endif
        Jumlah Per Halaman =
        @if(isset($s['limit']))
          {{$s['limit']}}
        @endif
        </i>
      @endif
    </div>

  </div>
</div>