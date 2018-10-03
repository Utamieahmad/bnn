<div class="">
  <form class="form-group filter" action="{{isset($route) ? route($route) : ''}}" method="post">
    <div class="row">
      {{csrf_field()}}
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="row">
        <?php
          $key = [
            'instansi' => 'Nama Instansi',
            'periode' => 'Tanggal Tes',
            'jml_peserta' => 'Jumlah Peserta',
            'sasaran' => 'Sasaran',
            'kode_anggaran' => 'Kode Anggaran',
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
          <div class="col-md-6 col-sm-6 col-xs-12 instansi {{(isset($filter['tipe']) ? (($filter['tipe'] == 'instansi') ? '' : 'hide') :'hide')}} div-wrap">
            <label for="tipe" class="control-label">Nama Instansi</label>
            <select name="instansi" id="idpelaksana" class="form-control select2 selectPelaksana" tabindex="-1" aria-hidden="true">
              <option>-- Nama Instansi --</option>
              @foreach($instansi as $in)
                <option value="{{$in['nm_instansi']}}"  {{(isset($filter['instansi']) ? (($filter['instansi'] == $in['nm_instansi']) ? 'selected=selected' : '') :'')}}> {{$in['nm_instansi']}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12 sasaran {{(isset($filter['tipe']) ? (($filter['tipe'] == 'sasaran') ? '' : 'hide') :'hide')}} div-wrap">
            <label for="tipe" class="control-label">Sasaran</label>
             <select class="form-control select2 " name="sasaran">
                <option>-- Pilih Sasaran --</option>
                @if(isset($sasaran))
                    @if(count($sasaran)>0)
                        @foreach($sasaran as $sa =>$sval)
                            <option value="{{$sval}}" {{(isset($filter['sasaran']) ? (($filter['sasaran'] == $sval) ? 'selected=selected' : '') :'')}} >{{$sval}}</option>
                        @endforeach
                    @endif
                @endif
            }
            }
            </select>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12 kode_anggaran {{(isset($filter['tipe']) ? (($filter['tipe'] == 'kode_anggaran') ? '' : 'hide') :'hide')}} div-wrap">
            <label for="tipe" class="control-label">Kode Anggaran</label>
             <select class="form-control select2 " name="kode_anggaran">
                <option>-- Pilih Kode Anggaran --</option>
                <option value="DIPA" {{(isset($filter['kode_anggaran']) ? (($filter['kode_anggaran'] == 'DIPA') ? 'selected=selected' : '') :'')}}>Dipa</option>
                <option value="NONDIPA" {{(isset($filter['kode_anggaran']) ? (($filter['kode_anggaran'] == 'NONDIPA') ? 'selected=selected' : '') :'')}}>Non Dipa</option>

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

          <div class="col-md-6 col-sm-6 col-xs-12 nama_satker {{(isset($filter['tipe']) ? (($filter['tipe'] == 'nama_satker') ? '' : 'hide') :'hide')}} div-wrap">
            <label for="tipe" class="control-label">Nama Satker</label>
            <select class="form-control select2" name="nama_satker">
              <option>-- Pilih Satker --</option>
              @if(isset($satker))
                @if(count($satker) > 0 )
                  @foreach($satker as $k=>$val)
                    <option value="{{$k}}" {{isset($filter) ? (isset($filter['keyword'])? ( ($filter['keyword'] == $k) ? 'selected=selected' : ''):'') :''}}>{{$val}}</option>
                  @endforeach
                @endif
              @endif

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

          <div class="jml_peserta {{(isset($filter['tipe']) ? (($filter['tipe'] == 'jml_peserta') ? '' : 'hide') :'hide')}} div-wrap">
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Mulai</label>
              <input class="form-control" name="jml_from" value="{{isset($filter) ? (isset($filter['jml_from'])?$filter['jml_from']:'') :''}}" {{isset($filter['jml_from']) ? ($filter['jml_from'] ? '':'disabled=disabled') :''}} onkeydown="numeric_only(event,this)" />
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Sampai</label>
              <input class="form-control" name="jml_to" value="{{isset($filter) ? (isset($filter['jml_to'])?$filter['jml_to']:'') :''}}" {{isset($filter['jml_to']) ? ($filter['jml_to'] ? '':'disabled=disabled') :''}}  onkeydown="numeric_only(event,this)"  />
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
          @elseif( $s['tipe'] == 'jml_peserta')
            {{isset($s['jml_from']) ? $s['jml_from'] : ''}}
            {{isset($s['jml_from']) && isset($s['jml_to'])  ? '-' : ''}}
            {{isset($s['jml_to']) ?  $s['jml_to'] .', ' : ', '}}
          @elseif($s['tipe'] == 'status')
            @if($s['status'] == 'Y')
              Lengkap ,
            @else
              Belum Lengkap ,
            @endif
          @else
            {{ isset($s[$s['tipe']]) ? $s[$s['tipe']] : ''}} ,
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
