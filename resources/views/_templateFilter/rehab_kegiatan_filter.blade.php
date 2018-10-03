<div class="">
  <form class="form-group filter" action="{{isset($route_name) ? route($route_name) : ''}}" method="post">
    <div class="row">
      {{csrf_field()}}
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="row">
        <?php
          $key = [
            'pelaksana' => 'Pelaksana',
            'tema' => 'Judul',
            'nomor_sprint' => 'Nomor Surat Perintah',
            'periode_start' => 'Tanggal Mulai',
            'periode_end' => 'Tanggal Selesai',
            'jumlah_peserta' => 'Jumlah Peserta',
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

          <div class="col-md-6 col-sm-6 col-xs-12 keyword {{(isset($filter['tipe']) ? ((($filter['tipe'] == 'tema' ) || ($filter['tipe'] == 'nomor_sprint') )  ? '' : 'hide') :'')}} div-wrap">
            <label for="tipe" class="control-label">Kata Kunci</label>
            <?php
              $kata_kunci = "";
              $tipe_keyword = ['tema','nomor_sprint'];
              if(isset($filter['tipe'])){
                if(in_array($filter['tipe'],$tipe_keyword)){
                  $kata_kunci = $filter[$filter['tipe']];
                }else{
                  $kata_kunci = "";
                }
              }else{
                $kata_kunci = "";
              }


            ?>
            <input class="form-control" name="kata_kunci" value="{{$kata_kunci}}" {{($kata_kunci ? '' : 'disabled=disabled')}} />
          </div>

          <div class="col-md-6 col-sm-6 col-xs-12 status {{(isset($filter['tipe']) ? ((($filter['tipe'] == 'status' )  )  ? '' : 'hide') :'hide')}} div-wrap">
              <label for="tipe" class="control-label">Tipe</label>

              <select class="form-control select2"  name="status">
                <option>-- Pilih Status -- </option>
                <option value="N" {{isset($filter) ? ( isset($filter['status'])? ($filter['status'] == 'N' ? 'selected=selected':''): '' ): ''}}> Belum Lengkap</option>
                <option value="Y" {{isset($filter) ? ( isset($filter['status'])? ($filter['status'] == 'Y' ? 'selected=selected':''): '' ): ''}}> Lengkap</option>

              </select>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12 pelaksana {{(isset($filter['tipe']) ? ((($filter['tipe'] == 'pelaksana' )  )  ? '' : 'hide') :'hide')}} div-wrap">
              <label for="tipe" class="control-label">Tipe</label>

              <select class="form-control select2"  name="pelaksana">
                <option>-- Pilih Instansi -- </option>
                @if(count($instansi))
                  @foreach($instansi as $k => $val)
                    <option value="{{$val['nm_instansi']}}" {{isset($filter) ? ( isset($filter['pelaksana'])? ($filter['pelaksana'] == $val['nm_instansi']? 'selected=selected':''): '' ): ''}}>{{$val['nm_instansi']}}</option>
                  @endforeach
                @endif
              </select>
            </div>

        </div>

        <div class="clearfix"></div>
          <div class="row periode_start {{(isset($filter['tipe']) ? (($filter['tipe'] == 'periode_start') ? '' : 'hide') :'hide')}} div-wrap">
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Mulai</label>
              <div class='input-group date date_start row'>
                  <input type='text' name="start_from" class="form-control" value="{{isset($filter) ? (isset($filter['start_from']) ? $filter['start_from'] : '') :''}}"/>
                  <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div>

            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Selesai</label>
              <div class='input-group date date_end row'>
                  <input type='text' name="start_to" class="form-control" value="{{isset($filter) ? (isset($filter['start_to'])?$filter['start_to']:'') :''}}"/>
                  <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div>
            </div>

          </div>

          <div class="clearfix"></div>
          <div class="row periode_end {{(isset($filter['tipe']) ? (($filter['tipe'] == 'periode_end') ? '' : 'hide') :'hide')}} div-wrap">
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Mulai</label>
              <div class='input-group date date_start row'>
                  <input type='text' name="end_from" class="form-control" value="{{isset($filter) ? (isset($filter['end_from']) ? $filter['end_from'] : '') :''}}"/>
                  <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div>

            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Selesai</label>
              <div class='input-group date date_end row'>
                  <input type='text' name="end_to" class="form-control" value="{{isset($filter) ? (isset($filter['end_to'])?$filter['end_to']:'') :''}}"/>
                  <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div>
            </div>

          </div>

          <div class="clearfix"></div>
          <div class="jumlah_peserta {{(isset($filter['tipe']) ? (($filter['tipe'] == 'jumlah_peserta') ? '' : 'hide') :'hide')}} div-wrap row">
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Jumlah Dari</label>
              <input type='text' name="jumlah_from" class="form-control" value="{{isset($filter) ? (isset($filter['jumlah_from']) ? $filter['jumlah_from'] : '') :''}}"/>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Jumlah Sampai</label>
              <input type='text' name="jumlah_to" class="form-control" value="{{isset($filter) ? (isset($filter['jumlah_to'])?$filter['jumlah_to']:'') :''}}"/>
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
                  <a href="{{isset($route_name) ? route($route_name) : ''}}" class="btn btn-primary">Hapus</a>
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
          @if( $s['tipe'] == 'periode_start')
            {{isset($s['start_from']) ? $s['start_from'] : ''}}
            {{isset($s['start_from']) && isset($s['start_to'])  ? '-' : ''}}
            {{isset($s['start_to']) ?  $s['start_to'] .', ' : ', '}}
          @elseif( $s['tipe'] == 'periode_end')
            {{isset($s['end_from']) ? $s['end_from'] : ''}}
            {{isset($s['end_from']) && isset($s['end_to'])  ? '-' : ''}}
            {{isset($s['end_to']) ?  $s['end_to'] .', ' : ', '}}
          @elseif( $s['tipe'] == 'jumlah_peserta')
            {{isset($s['jumlah_from']) ? $s['jumlah_from'] : ''}}
            {{isset($s['jumlah_from']) && isset($s['jumlah_to'])  ? '-' : ''}}
            {{isset($s['jumlah_to']) ?  $s['jumlah_to'] .', ' : ', '}}
          @elseif($s['tipe'] == 'pelaksana')
            {{  ( isset($s['pelaksana'])  ? $s['pelaksana'] .', ' :  $s['pelaksana'] .', ') }}
          @elseif($s['tipe'] == 'status')
            {{( isset($s['status'])  ?( ($s['status'] == 'Y' ) ? 'Lengkap' .' ,' : 'Belum Lengkap'.' ,') :  '' )}}
          @else
            {{isset($filter['tipe']) ?( isset($filter[$filter['tipe']]) ? $filter[$filter['tipe']] .',': '')  :''}}
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
