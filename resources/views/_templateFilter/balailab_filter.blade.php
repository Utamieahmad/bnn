<div class="">
  <form class="form-group filter" action="{{isset($route) ? route($route) : ''}}" method="post">
    <div class="row">
      {{csrf_field()}}
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="row">
        <?php

          $key = [
            'no_lab' => 'No Lab',
            'no_surat_permohonan' => 'No Surat Permohonan',
            'no_lp' => 'No Laporan',
            'tanggal_terima' => 'Tanggal Terima',
            'nama_instansi' => 'Instansi',
            'provinsi' => 'Posisi Berkas',
            'jenis_berkas' => 'Jenis Berkas',
            'status_berkas' => 'Status Berkas',
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

          <div class="col-md-6 col-sm-6 col-xs-12 keyword {{(isset($filter['tipe']) ? (( ($filter['tipe'] == 'no_lab') || ($filter['tipe'] == 'no_surat_permohonan')|| ($filter['tipe'] == 'no_lp') || ($filter['tipe'] == 'nama_instansi') || ($filter['tipe'] == 'provinsi') ) ? '' : 'hide') :'')}} div-wrap">
            <label for="tipe" class="control-label">Kata Kunci</label>
            <?php
              $kata_kunci = "";
              $tipe_keyword = ['no_lab','no_surat_permohonan','no_lp','nama_instansi','provinsi'];
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
            <input class="form-control" name="kata_kunci" value="{{$kata_kunci}}" {{($kata_kunci ? '' : 'disabled=disabled')}}  />
          </div>

          <div class="col-md-6 col-sm-6 col-xs-12 status {{(isset($filter['tipe']) ? (($filter['tipe'] == 'status') ? '' : 'hide') :'hide')}} div-wrap">
            <label for="tipe" class="control-label"> Status</label>
             <select class="form-control select2 " name="status">
                <option>-- Pilih Status --</option>
                <option value="Y" {{(isset($filter['status']) ? (($filter['status'] == 'Y') ? 'selected=selected' : '') :'')}}>Lengkap</option>
                <option value="N" {{(isset($filter['status']) ? (($filter['status'] == 'N') ? 'selected=selected' : '') :'')}}>Belum Lengkap</option>

            </select>
          </div>
          <?php
            $jenis_berkas = [
              'Projustitia'=>'Projustitia',
            ];
          ?>
          <div class="col-md-6 col-sm-6 col-xs-12 jenis_berkas {{(isset($filter['tipe']) ? (($filter['tipe'] == 'jenis_berkas') ? '' : 'hide') :'hide')}} div-wrap">
            <label for="tipe" class="control-label"> Jenis Berkas</label>

             <select class="form-control select2 " name="jenis_berkas">
                <option>-- Pilih Berkas --</option>
                @foreach ($jenis_berkas as $j => $b)
                  <option value="{{$j}}" {{(isset($filter['jenis_berkas']) ? (($filter['jenis_berkas'] == $j) ? 'selected=selected' : '') :'')}}> {{$b}} </option>
                @endforeach
            </select>
          </div>

          <?php
            $status_berkas = [
              'Analisa Setuju'=>'Analisa Setuju',
            ];
          ?>
          <div class="col-md-6 col-sm-6 col-xs-12 status_berkas {{(isset($filter['tipe']) ? (($filter['tipe'] == 'status_berkas') ? '' : 'hide') :'hide')}} div-wrap">
            <label for="tipe" class="control-label"> Status Berkas</label>

             <select class="form-control select2 " name="status_berkas">
                <option>-- Status Berkas --</option>
                @foreach ($status_berkas as $s => $br)
                  <option value="{{$s}}" {{(isset($filter['status_berkas']) ? (($filter['status_berkas'] == $s) ? 'selected=selected' : '') :'')}}> {{$br}} </option>
                @endforeach
            </select>
          </div>

          <div class="clearfix"></div>
          <div class="tanggal_terima {{(isset($filter['tipe']) ? (($filter['tipe'] == 'tanggal_terima') ? '' : 'hide') :'hide')}} div-wrap">
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Mulai</label>
              <div class='input-group date date_start row'>
                  <input type='text' name="tanggal_terima_start" class="form-control" value="{{isset($filter['tanggal_terima_start']) ? \Carbon\Carbon::parse($filter['tanggal_terima_start'])->format('d/m/Y') :''}}"/>
                  <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div>

            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Selesai</label>
              <div class='input-group date date_end row'>
                  <input type='text' name="tanggal_terima_end" class="form-control" value="{{isset($filter['tanggal_terima_end']) ? \Carbon\Carbon::parse($filter['tanggal_terima_end'])->format('d/m/Y') :''}}"/>
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
          @if( $s['tipe'] == 'tanggal_terima')
            {{isset($s['tanggal_terima_start']) ? $s['tanggal_terima_start'] : ''}}
            {{isset($s['tanggal_terima_start']) && isset($s['tanggal_terima_end'])  ? '-' : ''}}
            {{isset($s['tanggal_terima_end']) ?  $s['tanggal_terima_end'] .', ' : ', '}}
          @elseif($s['tipe'] == 'status')
            @if($s['status'] == 'Y')
              Lengkap ,
            @else
              Belum Lengkap ,
            @endif
          @else
            {{isset($filter['tipe']) ?( isset($filter[$filter['tipe']]) ? $filter[$filter['tipe']] .',': '')  :''}}
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
