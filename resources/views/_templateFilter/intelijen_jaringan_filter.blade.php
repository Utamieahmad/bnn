<div class="">
  <form class="form-group filter" action="{{isset($route) ? route($route) : ''}}" method="post">
    <div class="row">
      {{csrf_field()}}
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="row">
        <?php
          $key = [
            'nomor_lkn' => 'Nomor LKN',
            'jenisjaringan' => 'Jenis Jaringan',
            // 'keterlibatan_jaringan' => 'Keterlibatan Jaringan',
            'nama_jaringan' => 'Nama Jaringan',
            'status' => 'Status',
          ];

        ?>

          <div class="col-md-6 col-sm-6 col-xs-12">
            <label for="tipe" class="control-label">Tipe</label>

            <select class="form-control select2"  name="tipe" onchange="formFilter(this)">
              <option value="">SEMUA</option>
              @foreach($key as $k=>$v)
                <option value="{{$k}}" {{( isset($filter['tipe']) ? ( ( $filter['tipe'] ==$k)  ? 'selected=selected' : '') : '')}}>{{$v}}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-6 col-sm-6 col-xs-12 keyword {{(isset($filter['tipe']) ? ((($filter['tipe'] == 'nomor_lkn' ) || ($filter['tipe'] == 'nama_jaringan') )  ? '' : 'hide') :'')}} div-wrap">
            <label for="tipe" class="control-label">Kata Kunci</label>
            <input class="form-control" name="kata_kunci" value="{{isset($filter) ? (isset($filter['keyword'])?$filter['keyword']:'') :''}}" {{ (isset($filter['keyword']) || isset($filter['tipe'])) ?  '':'disabled=disabled'}}  />
          </div>

          <div class="col-md-6 col-sm-6 col-xs-12 status {{(isset($filter['tipe']) ? ((($filter['tipe'] == 'status' )  )  ? '' : 'hide') :'hide')}} div-wrap">
              <label for="tipe" class="control-label">Tipe</label>

              <select class="form-control select2"  name="status">
                <option>-- Pilih Status -- </option>
                <option value="N" {{isset($filter) ? ( isset($filter['status_kelengkapan'])? ($filter['status_kelengkapan'] == 'N' ? 'selected=selected':''): '' ): ''}}> Belum Lengkap</option>
                <option value="Y" {{isset($filter) ? ( isset($filter['status_kelengkapan'])? ($filter['status_kelengkapan'] == 'Y' ? 'selected=selected':''): '' ): ''}}> Lengkap</option>

              </select>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12 jenisjaringan {{(isset($filter['tipe']) ? ((($filter['tipe'] == 'jenisjaringan' )  )  ? '' : 'hide') :'hide')}} div-wrap">
              <label for="tipe" class="control-label">Tipe Jaringan</label>
              <select class="form-control select2"  name="jenisjaringan">
                <option>-- Pilih Jaringan -- </option>
                <option value="Nasional" {{isset($filter) ? ( isset($filter['jenisjaringan'])? ($filter['jenisjaringan'] == 'Nasional' ? 'selected=selected':''): '' ): ''}}> Nasional</option>
                <option value="Internasional" {{isset($filter) ? ( isset($filter['jenisjaringan'])? ($filter['jenisjaringan'] == 'Internasional' ? 'selected=selected':''): '' ): ''}}> Internasional</option>

              </select>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12 keterlibatan_jaringan {{(isset($filter['tipe']) ? ((($filter['tipe'] == 'keterlibatan_jaringan' )  )  ? '' : 'hide') :'hide')}} div-wrap">
              <label for="tipe" class="control-label">Tipe Keterlibatan</label>
              <select class="form-control select2"  name="keterlibatan_jaringan">
                <option>-- Pilih Keterlibatan -- </option>
                <option value="N" {{isset($filter) ? ( isset($filter['keterlibatan_jaringan'])? ($filter['keterlibatan_jaringan'] == 'N' ? 'selected=selected':''): '' ): ''}}> Tidak</option>
                <option value="Y" {{isset($filter) ? ( isset($filter['keterlibatan_jaringan'])? ($filter['keterlibatan_jaringan'] == 'Y' ? 'selected=selected':''): '' ): ''}}> Ya</option>

              </select>
            </div>



        </div>


        <div class="clearfix"></div>
          <div class="row periode {{(isset($filter['tipe']) ? (($filter['tipe'] == 'periode') ? '' : 'hide') :'hide')}} div-wrap">
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Mulai</label>
              <div class='input-group date date_start row'>
                  <input type='text' name="tgl_from" class="form-control" value="{{isset($filter) ? (isset($filter['tgl_from']) ? $filter['tgl_from'] : '') :''}}"/>
                  <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div>

            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Selesai</label>
              <div class='input-group date date_end row'>
                  <input type='text' name="tgl_to" class="form-control" value="{{isset($filter) ? (isset($filter['tgl_to'])?$filter['tgl_to']:'') :''}}"/>
                  <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div>
            </div>

          </div>

          <div class="clearfix"></div>
          <div class="m-t-10 m-b-20">
          <div class="row">
            <div class="col-sm-6 col-md-6 col-xs-12">
              <label for="tipe" class="control-label">Urutan</label>
              <select class="form-control select2" name="order">
                <option value="desc" {{isset($filter) ? ( ($filter['order'] =='desc') ? 'selected=selected' : '' ) :''}}>Bawah ke Atas</option>
                <option value="asc" {{isset($filter) ? ( ($filter['order'] =='asc') ? 'selected=selected' : '' ) :''}}>Atas ke Bawah</option>
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
          @elseif($s['tipe'] == 'status')
            {{( isset($s['status'])  ?( ($s['status'] == 'Y' ) ? 'Lengkap' .' ,' : 'Belum Lengkap'.' ,') :  '' )}}
          @elseif($s['tipe'] == 'jenisjaringan')
            {{( isset($s['jenisjaringan'])  ?( ($s['jenisjaringan'] == 'Internasional' ) ? 'Internasional' .' ,' : 'Nasional'.' ,') :  '' )}}
          @elseif($s['tipe'] == 'keterlibatan_jaringan')
            {{( isset($s['keterlibatan_jaringan'])  ?( ($s['keterlibatan_jaringan'] == 'Y' ) ? 'Ya' .' ,' : 'Tidak'.' ,') :  '' )}}
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
