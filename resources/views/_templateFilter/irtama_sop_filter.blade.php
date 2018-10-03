<div class="">
  <form class="form-group filter" action="{{isset($route) ? route($route) : ''}}" method="post">
    <div class="row">
      {{csrf_field()}}
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="row">
        <?php
          $key = [
            'sprin' => 'Surat Perintah',
            'periode' => 'Tanggal Surat Perintah',
            'jenis_sop_kebijakan' => 'Jenis SOP',
            'status' => 'Status',
          ];
          $f = $filter;
        ?>

          <div class="col-md-6 col-sm-6 col-xs-12">
            <label for="tipe" class="control-label">Tipe</label>

            <select class="form-control select2"  name="tipe" onchange="formFilter(this)">
              <option value="">SEMUA</option>
              @foreach($key as $k=>$v)
                <option value="{{$k}}" {{( isset($f['tipe']) ? ( ( $f['tipe'] ==$k)  ? 'selected=selected' : '') : '')}}>{{$v}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12 keyword {{(isset($filter['tipe']) ? ((($filter['tipe'] == 'sprin' )  )  ? '' : 'hide') :'')}} div-wrap">
            <label for="tipe" class="control-label">Kata Kunci</label>
            <input class="form-control" name="kata_kunci" value="{{isset($filter) ? (isset($filter['keyword'])?$filter['keyword']:'') :''}}" {{isset($filter['keyword']) ? ($filter['keyword'] ? '':'disabled=disabled') :'disabled=disabled'}}  />
          </div>

          <div class="col-md-6 col-sm-6 col-xs-12 jenis_sop_kebijakan {{(isset($filter['tipe']) ? (($filter['tipe'] == 'jenis_sop_kebijakan') ? '' : 'hide') :'hide')}} div-wrap">
            <label for="tipe" class="control-label">Jenis Kebijakan</label>
            <select class="form-control select2" name="jenis_sop_kebijakan">
              <option>-- Pilih Jenis --</option>
              <option value="SOP" {{isset($filter) ? ( isset($filter['keyword'])? ($filter['keyword'] == 'SOP' ? 'selected=selected':''): '' ): ''}}>SOP</option>
              <option value="Pedoman" {{isset($filter) ? ( isset($filter['keyword'])? ($filter['keyword'] == 'Pedoman' ? 'selected=selected':''): '' ): ''}}>Pedoman</option>
              <option value="Surat Edaran" {{isset($filter) ? ( isset($filter['keyword'])? ($filter['keyword'] == 'Surat Edaran' ? 'selected=selected':''): '' ): ''}}>Surat Edaran</option>
              <option value="Dll" {{isset($filter) ? ( isset($filter['keyword'])? ($filter['keyword'] == 'Dll' ? 'selected=selected':''): '' ): ''}}>Dll</option>
            </select>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12 status {{(isset($filter['tipe']) ? ((($filter['tipe'] == 'status' )  )  ? '' : 'hide') :'hide')}} div-wrap">
              <label for="tipe" class="control-label">Tipe</label>

              <select class="form-control select2"  name="status">
                <option>-- Pilih Status -- </option>
                <option value="N" {{isset($filter) ? ( isset($filter['keyword'])? ($filter['keyword'] == 'N' ? 'selected=selected':''): '' ): ''}}> Belum Lengkap</option>
                <option value="Y" {{isset($filter) ? ( isset($filter['keyword'])? ($filter['keyword'] == 'Y' ? 'selected=selected':''): '' ): ''}}> Lengkap</option>

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
          <!-- <div class="jumlah_peserta {{(isset($filter['tipe']) ? (($filter['tipe'] == 'jumlah_peserta') ? '' : 'hide') :'hide')}} div-wrap row">
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Jumlah Dari</label>
              <input type='text' name="jumlah_from" class="form-control" value="{{isset($filter) ? (isset($filter['jumlah_from']) ? $filter['jumlah_from'] : '') :''}}"/>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Jumlah Sampai</label>
              <input type='text' name="jumlah_to" class="form-control" value="{{isset($filter) ? (isset($filter['jumlah_to'])?$filter['jumlah_to']:'') :''}}"/>
            </div>

          </div> -->

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
        {{ isset($s['tipe']) ? (isset($key[$s['tipe']]) ?$key[$s['tipe']] .' = ': '')  : ''}}
        @if( isset($s['tipe']))
          @if( $s['tipe'] == 'periode')
            {{isset($s['tgl_from']) ? $s['tgl_from'] : ''}}
            {{isset($s['tgl_from']) && isset($s['tgl_to'])  ? '-' : ''}}
            {{isset($s['tgl_to']) ?  $s['tgl_to'] .', ' : ', '}}
          @elseif(isset($s['jenis_sop_kebijakan']))
           {{$s['jenis_sop_kebijakan']}}
          @elseif($s['tipe'] == 'status')
            @if($s['keyword'] == 'Y')
              Lengkap ,
            @else
              Belum Lengkap ,
            @endif
          @else
            {{isset($s['keyword']) ? $s['keyword'] .', ' : ''}}
          @endif
        @else
          {{isset($s['keyword']) ? $s['keyword'] .' = ': ''}}
        @endif

       Urutan =

        @if(isset($s['order']))
          @if($s['order'] == 'desc')
            Atas ke bawah   ,
          @elseif($s['order'] == 'asc')
            Bawah ke atas  ,
          @else
            Atas ke bawah ,
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
