<div class="">
  <form class="form-group filter" action="{{isset($route) ? route($route) : ''}}" method="post">
    <div class="row">
      {{csrf_field()}}
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="row">
        <?php
          $key = [
            'nomor_sprint' => 'No. Surat Perintah',
            'nama_kegiatan' => 'Nama Kegiatan',
            'tanggal_pelaksanaan' => 'Tanggal Pelaksanaan',
            'kodesumberanggaran' => 'Sumber Anggaran',
            'status' => 'Status',
          ];

        ?>

          <div class="col-md-6 col-sm-6 col-xs-12">
            <label for="tipe" class="control-label">Tipe</label>

            <select class="form-control select2"  name="tipe" onchange="formFilter(this)">
              <option value="">SEMUA</option>
              <option value="nomor_sprint" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'nomor_sprint') ? 'selected=selected' : '') :'')}}>No. Surat Perintah</option>
              <option value="nama_kegiatan" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'nama_kegiatan') ? 'selected=selected' : '') :'')}}>Nama Kegiatan</option>
              <option value="tanggal_pelaksanaan" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'tanggal_pelaksanaan') ? 'selected=selected' : '') :'')}}>Tanggal Pelaksanaan</option>
              <option value="kodesumberanggaran" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'kodesumberanggaran') ? 'selected=selected' : '') :'')}}>Sumber Anggaran</option>
              <option value="status" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'status') ? 'selected=selected' : '') :'')}}>Status Kelengkapan</option>
            </select>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12 keyword {{(isset($filter['tipe']) ? ((($filter['tipe'] == 'kodesumberanggaran') || ($filter['tipe'] == 'tanggal_pelaksanaan') || ($filter['tipe'] == 'status')) ? 'hide' : '') :'')}} div-wrap">
            <label for="tipe" class="control-label">Kata Kunci</label>
            <input class="form-control" name="kata_kunci" value="{{isset($filter) ? (isset($filter['keyword'])?$filter['keyword']:'') :''}}" {{ (isset($filter['keyword']) || isset($filter['tipe'])) ?  '':'disabled=disabled'}}  />
          </div>

          <div class="col-md-6 col-sm-6 col-xs-12 kodesumberanggaran {{isset($filter['tipe']) ? ( $filter['tipe'] == 'kodesumberanggaran' ? '':'hide'): 'hide'}} div-wrap" >
              <label for="tipe" class="control-label">Jenis Kerja Sama</label>

              <select class="form-control select2" name="kodesumberanggaran">
                <option value="DIPA" {{isset($filter)? (isset($filter['kodesumberanggaran']) ? ($filter['kodesumberanggaran'] == 'DIPA' ? 'selected=selected' : '') :''):''}} >DIPA</option>
                <option value="NONDIPA"  {{isset($filter)? (isset($filter['kodesumberanggaran']) ? ($filter['kodesumberanggaran'] == 'NONDIPA' ? 'selected=selected' : '') :''):''}} >NON DIPA</option>
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
          <div class="tanggal_pelaksanaan {{(isset($filter['tipe']) ? (($filter['tipe'] == 'tanggal_pelaksanaan') ? '' : 'hide') :'hide')}} div-wrap">
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
          @if( $s['tipe'] == 'tanggal_pelaksanaan' )
            {{isset($s['tgl_from']) ? $s['tgl_from'] : ''}}
            {{isset($s['tgl_from']) && isset($s['tgl_to'])  ? '-' : ''}}
            {{isset($s['tgl_to']) ?  $s['tgl_to'] .', ' : ', '}}
         @elseif($s['tipe'] == 'nomor_sprint')
            {{  ( isset($nomor_sprint[$s['keyword']])  ? $nomor_sprint[$s['keyword']] .' , ' :  $s['keyword'] .', ' ) }}
         @elseif($s['tipe'] == 'nama_kegiatan')
            {{  ( isset($nama_kegiatan[$s['keyword']])  ? $nama_kegiatan[$s['keyword']] .' , ' :  $s['keyword'] .' , ' ) }}
          @elseif($s['tipe'] == 'status')
            {{( isset($s['status'])  ?( ($s['status'] == 'Y' ) ? 'Lengkap , ' : 'Belum Lengkap , ') :  '' )}}
          @elseif($s['tipe'] == 'kodesumberanggaran')
            @if (isset($s['kodesumberanggaran']))
              @if($s['kodesumberanggaran'] == 'DIPA')
                {{ 'DIPA' .' , ' }}
              @elseif($s['kodesumberanggaran'] == 'NONDIPA')
                {{ 'NON DIPA' .' , ' }}
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
