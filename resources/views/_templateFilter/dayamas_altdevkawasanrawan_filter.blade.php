<div class="">
  <form class="form-group filter" action="{{isset($route) ? route($route) : ''}}" method="post">
    <div class="row">
      {{csrf_field()}}
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="row">
        <?php
          $key = [
            'periode' => 'Tanggal Pelaksanaan',
            'pelaksana' => 'Pelaksana',
            'lokasi' => 'Lokasi',
            'geografis' => 'Geografis',
            'luas' => 'Luas',
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

          <div class="col-md-6 col-sm-6 col-xs-12 keyword {{(isset($filter['tipe']) ? (($filter['tipe'] == 'lokasi') ? '' : 'hide') :'')}} div-wrap">
            <label for="tipe" class="control-label">Kata Kunci</label>
            <input class="form-control" name="kata_kunci" value="{{isset($filter) ? (isset($filter['lokasi']) ? $filter['lokasi']: (isset($filter['luas']) ? $filter['luas'] : '')) :''}}" {{isset($filter['lokasi']) || isset($filter['luas']) ? ($filter['lokasi'] || $filter['luas'] ? '':'disabled=disabled') :'disabled=disabled'}} {{ (isset($filter['luas']) ? 'onkeydown=numeric_only(event,this)' : '') }}/>
          </div>

          <div class="col-md-6 col-sm-6 col-xs-12 geografis {{(isset($filter['tipe']) ? (($filter['tipe'] == 'geografis') ? '' : 'hide') :'hide')}} div-wrap">
            <label for="tipe" class="control-label">Jenis Geografis</label>
            <select class="form-control select2"  name="geografis">
              <option>-- Pilih Jenis Geografis</option>
              @if(isset($jenis_geografis) )
                @if(count($jenis_geografis) > 0)
                  @foreach($jenis_geografis as $pkey => $pval)
                    <option value="{{$pkey}}" {{( isset($filter['geografis']) ? ( ($filter['geografis'] == $pkey) ? 'selected=selected' : '' ) : '' )}}> {{$pval}}</option>
                  @endforeach
                @endif
              @endif
            </select>
          </div>

          <div class="col-md-6 col-sm-6 col-xs-12 pelaksana {{(isset($filter['tipe']) ? (($filter['tipe'] == 'pelaksana') ? '' : 'hide') :'hide')}} div-wrap">
            <label for="tipe" class="control-label">Pelaksana</label>
            @php
              $id_pelaksana = null;
              if(isset($filter['tipe'])){
                if( isset ($filter['pelaksana']) ){
                  $id_pelaksana = $filter['pelaksana'];
                }else{
                  $id_pelaksana = null;
                }

              }else{
                $id_pelaksana = null;
              }
            @endphp
            <select class="form-control select2"  name="pelaksana">
              <option>-- Pilih Pelaksana</option>
              {!! dropdownPelaksana($id_pelaksana ) !!}
            </select>
          </div>




          <div class="col-md-6 col-sm-6 col-xs-12 status {{(isset($filter['tipe']) ? (($filter['tipe'] == 'status') ? '' : 'hide') :'hide')}} div-wrap">
            <label for="tipe" class="control-label"> Status</label>
             <select class="form-control select2 " name="status">
                <option>-- Pilih Status --</option>
                <option value="Y" {{(isset($filter['status']) ? (($filter['status'] == 'Y') ? 'selected=selected' : '') :'')}}>Lengkap</option>
                <option value="N" {{(isset($filter['status']) ? (($filter['status'] == 'N') ? 'selected=selected' : '') :'')}}>Belum Lengkap</option>

            </select>
          </div>

          <div class="clearfix"></div>
          <div class="periode {{(isset($filter['tipe']) ? (($filter['tipe'] == 'periode') ? '' : 'hide') :'hide')}} div-wrap">
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Mulai</label>
              <div class='input-group date date_start row'>
                  <input type='text' name="tgl_from" class="form-control" value="{{isset($filter) ? (isset($filter['tgl_from']) ? ($filter['tgl_from']? $filter['tgl_from']  :'' ): '') :''}}"/>
                  <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div>

            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Selesai</label>
              <div class='input-group date date_end row'>
                  <input type='text' name="tgl_to" class="form-control" value="{{isset($filter) ? (isset($filter['tgl_to'])? ($filter['tgl_to'] ? $filter['tgl_to'] : ''):'') :''}}"/>
                  <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div>
            </div>

          </div>

          <div class="luas {{(isset($filter['tipe']) ? (($filter['tipe'] == 'luas') ? '' : 'hide') :'hide')}} div-wrap">
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Dari</label>
              <input type='text' name="luas_from" class="form-control" value="{{isset($filter) ? (isset($filter['luas_from']) ? ($filter['luas_from']? $filter['luas_from']  :'' ): '') :''}}" onkeydown="numeric_only(event,this)"/>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Sampai</label>
              <input type='text' name="luas_to" class="form-control" value="{{isset($filter) ? (isset($filter['luas_to'])? ($filter['luas_to'] ? $filter['luas_to'] : ''):'') :''}}" onkeydown="numeric_only(event,this)"/>
            </div>

          </div>
        </div>


        <div class="clearfix"></div>
        <div class="m-t-10 m-b-20">
          <div class="row">
            <div class="col-sm-6 col-md-6 col-xs-12">
              <label for="tipe" class="control-label">Urutan</label>
              <select class="form-control select2" name="order">
                <option value="desc"  {{(isset($filter['order']) ? (($filter['order'] == 'desc') ? 'selected=selected' : '') :'')}}>Bawah ke Atas</option>
                <option value="asc"  {{(isset($filter['order']) ? (($filter['order'] == 'asc') ? 'selected=selected' : '') :'')}}>Atas ke Bawah</option>
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

        {{ isset($s['tipe']) ? (isset($key[$s['tipe']]) ?$key[$s['tipe']] .' =' : '')  : ''}}
        @if( isset($s['tipe']))
          @if( $s['tipe'] == 'periode')
            {{isset($s['tgl_from']) ? $s['tgl_from'] : ''}}
            {{isset($s['tgl_from']) && isset($s['tgl_to'])  ? '-' : ''}}
            {{isset($s['tgl_to']) ?  $s['tgl_to'] .', ' : ', '}}
          @elseif($s['tipe'] == 'status')
            @if($s['status'] == 'Y')
              Lengkap ,
            @else
              Belum Lengkap ,
            @endif
          @elseif($s['tipe'] == 'geografis')
            {{( isset($jenis_geografis[$filter['geografis']]) ? $jenis_geografis[$filter['geografis']] : $filter['geografis'] ) }},

          @elseif($s['tipe'] == 'pelaksana')
            {{ getInstansiName($filter['pelaksana']) }} ,
          @elseif($s['tipe'] == 'lokasi')
            {{$filter['lokasi']}} ,
          @elseif( $s['tipe'] == 'luas')
            {{isset($s['luas_from']) ? $s['luas_from'] : ''}}
            {{isset($s['luas_from']) && isset($s['luas_to'])  ? '-' : ''}}
            {{isset($s['luas_to']) ?  $s['luas_to'] .', ' : ', '}}
          @endif
        @endif

        Urutan =

        @if(isset($s['order']))
          @if($s['order'] == 'desc')
            Bawah ke Atas
          @elseif($s['order'] == 'asc')
            Atas ke Bawah
          @else
            Bawah ke Atas
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
