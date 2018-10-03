<div class="">
  <form class="form-group filter" action="{{isset($route) ? route($route) : ''}}" method="post">
    <div class="row">
      {{csrf_field()}}
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="row">
        <?php
          $key = [
            'jenis_kerjasama' => 'Jenis Kerja Sama',
            'nama_instansi' => 'Instansi Mitra',
            'nomor_sprint' => 'Nomor MOU/PKS',
            'tgl_ttd' => 'Tanggal TTD',
            'tgl_berakhir' => 'Tanggal Berakhir',
            'status' => 'Status',
          ];

        ?>

          <div class="col-md-6 col-sm-6 col-xs-12">
            <label for="tipe" class="control-label">Tipe</label>

            <select class="form-control select2"  name="tipe" onchange="formFilter(this)">
              <option value="">SEMUA</option>
              <option value="jenis_kerjasama" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'jenis_kerjasama') ? 'selected=selected' : '') :'')}}>Jenis Kerja Sama</option>
              <option value="nama_instansi" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'nama_instansi') ? 'selected=selected' : '') :'')}}>Instansi Mitra</option>
              <option value="nomor_sprint" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'nomor_sprint') ? 'selected=selected' : '') :'')}}>Nomor MOU/PKS</option>
              <option value="tgl_ttd" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'tgl_ttd') ? 'selected=selected' : '') :'')}}>Tanggal TTD</option>
              <option value="tgl_berakhir" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'tgl_berakhir') ? 'selected=selected' : '') :'')}}>Tanggal Berakhir</option>
              <option value="status" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'status') ? 'selected=selected' : '') :'')}}>Status Kelengkapan</option>
            </select>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12 keyword {{(isset($filter['tipe']) ? ((($filter['tipe'] == 'jenis_kerjasama') || ($filter['tipe'] == 'tgl_ttd') || ($filter['tipe'] == 'tgl_berakhir') || ($filter['tipe'] == 'status')) ? 'hide' : '') :'')}} div-wrap">
            <label for="tipe" class="control-label">Kata Kunci</label>
            <input class="form-control" name="kata_kunci" value="{{isset($filter) ? (isset($filter['keyword'])?$filter['keyword']:'') :''}}" {{ (isset($filter['keyword']) || isset($filter['tipe'])) ?  '':'disabled=disabled'}}  />
          </div>

          <div class="col-md-6 col-sm-6 col-xs-12 jenis_kerjasama {{isset($filter['tipe']) ? ( $filter['tipe'] == 'jenis_kerjasama' ? '':'hide'): 'hide'}} div-wrap" >
              <label for="tipe" class="control-label">Jenis Kerja Sama</label>

              <select class="form-control select2" name="jenis_kerjasama">
                <option value="Nota Kesepahaman" {{isset($filter)? (isset($filter['jenis_kerjasama']) ? ($filter['jenis_kerjasama'] == 'Nota Kesepahaman' ? 'selected=selected' : '') :''):''}} >Nota Kesepahaman</option>
                <option value="Perjanjian Kerja Sama"  {{isset($filter)? (isset($filter['jenis_kerjasama']) ? ($filter['jenis_kerjasama'] == 'Perjanjian Kerja Sama' ? 'selected=selected' : '') :''):''}} >Perjanjian Kerja Sama</option>
                <option value="Letter of Intent"  {{isset($filter)? (isset($filter['jenis_kerjasama']) ? ($filter['jenis_kerjasama'] == 'Letter of Intent' ? 'selected=selected' : '') :''):''}} >Letter of Intent</option>
              </select>
          </div>

          <div class="col-md-6 col-sm-6 col-xs-12 status {{isset($filter['tipe']) ? ( $filter['tipe'] == 'status' ? '':'hide'): 'hide'}} div-wrap" >
              <label for="tipe" class="control-label">Status Kelengkapan</label>

              <select class="form-control select2" name="status">
                <option value="Y" {{isset($filter)? (isset($filter['status']) ? ($filter['status'] == 'Y' ? 'selected=selected' : '') :''):''}} >Lengkap</option>
                <option value="N"  {{isset($filter)? (isset($filter['status']) ? ($filter['status'] == 'N' ? 'selected=selected' : '') :''):''}} >Tidak Lengkap</option>
              </select>
          </div>

          <div class="clearfix"></div>
          <div class="tgl_ttd {{(isset($filter['tipe']) ? (($filter['tipe'] == 'tgl_ttd') ? '' : 'hide') :'hide')}} div-wrap">
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Mulai</label>
              <div class='input-group date date_start row'>
                  <input type='text' name="tgl_from_ttd" class="form-control" value="{{isset($filter) ? (isset($filter['tgl_from_ttd']) ? $filter['tgl_from_ttd'] : '') :''}}"/>
                  <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div>

            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Selesai</label>
              <div class='input-group date date_end row'>
                  <input type='text' name="tgl_to_ttd" class="form-control" value="{{isset($filter) ? (isset($filter['tgl_to_ttd'])?$filter['tgl_to_ttd']:'') :''}}"/>
                  <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div>
            </div>

          </div>

          <div class="tgl_berakhir {{(isset($filter['tipe']) ? (($filter['tipe'] == 'tgl_berakhir') ? '' : 'hide') :'hide')}} div-wrap">
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Mulai</label>
              <div class='input-group date date_start row'>
                  <input type='text' name="tgl_from_berakhir" class="form-control" value="{{isset($filter) ? (isset($filter['tgl_from_berakhir']) ? $filter['tgl_from_berakhir'] : '') :''}}"/>
                  <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div>

            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Selesai</label>
              <div class='input-group date date_end row'>
                  <input type='text' name="tgl_to_berakhir" class="form-control" value="{{isset($filter) ? (isset($filter['tgl_to_berakhir'])?$filter['tgl_to_berakhir']:'') :''}}"/>
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
          @if( $s['tipe'] == 'tgl_ttd' )
            {{isset($s['tgl_from_ttd']) ? $s['tgl_from_ttd'] : ''}}
            {{isset($s['tgl_from_ttd']) && isset($s['tgl_to_ttd'])  ? '-' : ''}}
            {{isset($s['tgl_to_ttd']) ?  $s['tgl_to_ttd'] .', ' : ', '}}
          @elseif( $s['tipe'] == 'tgl_berakhir' )
            {{isset($s['tgl_from_berakhir']) ? $s['tgl_from_berakhir'] : ''}}
            {{isset($s['tgl_from_berakhir']) && isset($s['tgl_to_berakhir'])  ? '-' : ''}}
            {{isset($s['tgl_to_berakhir']) ?  $s['tgl_to_berakhir'] .', ' : ', '}}
         @elseif($s['tipe'] == 'nomor_sprint')
            {{  ( isset($nomor_sprint[$s['keyword']])  ? $nomor_sprint[$s['keyword']] .' , ' :  $s['keyword'] .', ' ) }}
         @elseif($s['tipe'] == 'nama_instansi')
            {{  ( isset($nama_instansi[$s['keyword']])  ? $nama_instansi[$s['keyword']] .' , ' :  $s['keyword'] .' , ' ) }}
          @elseif($s['tipe'] == 'status')
            {{( isset($s['status'])  ?( ($s['status'] == 'Y' ) ? 'Lengkap , ' : 'Belum Lengkap , ') :  '' )}}
          @elseif($s['tipe'] == 'jenis_kerjasama')
            @if (isset($s['jenis_kerjasama']))
              @if($s['jenis_kerjasama'] == 'Nota Kesepahaman')
                {{ 'Nota Kesepahaman' .' , ' }}
              @elseif($s['jenis_kerjasama'] == 'Perjanjian Kerja Sama')
                {{ 'Perjanjian Kerja Sama' .' , ' }}
              @elseif($s['jenis_kerjasama'] == 'Letter of Intent')
                {{ 'Letter of Intent'. ' , ' }}
              @endif
            @endif
          @else
            {{isset($s['keyword']) ? $s['keyword'] .' , ' : ''}}
          @endif
        @else
          {{isset($s['keyword']) ? $s['keyword'] .' = ': ''}}
        @endif

        Urutan =

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
