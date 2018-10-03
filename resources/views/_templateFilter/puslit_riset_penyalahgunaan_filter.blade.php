<div class="">
  <form class="form-group filter" action="{{isset($route) ? route($route) : ''}}" method="post">
    <div class="row">
      {{csrf_field()}}
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="row">
        <?php
          $key = [
            'periode' => 'Tahun',
            'lokasi' => 'Lokasi Riset',
            'judul' => 'Judul Penelitian',
            'kabupaten' => 'Propinsi',
            'jml_responden' => 'Jumlah Responden',
            'status' => 'Status'
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

          <div class="col-md-6 col-sm-6 col-xs-12 keyword {{( isset($filter['tipe']) ?  (( ($filter['tipe'] == 'judul')|| ($filter['tipe'] == 'lokasi'))  ? '' : 'hide')  :'' )}} div-wrap">
            <label for="tipe" class="control-label">Kata Kunci</label>
            <?php
              $kata_kunci = "";
              $tipe_keyword = ['judul','lokasi'];
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

          <div class="col-md-6 col-sm-6 col-xs-12 kabupaten {{(isset($filter['tipe']) ? (($filter['tipe'] == 'kabupaten') ? '' : 'hide') :'hide')}} div-wrap">
            <label for="tipe" class="control-label"> Propinsi</label>
              <select class="form-control select2" name="kabupaten">
              @foreach($propinsi as $p=> $pval)
                <option value="{{$pval['id_wilayah']}}" {{(isset($filter['kabupaten']) ? (($filter['kabupaten'] == $pval['id_wilayah']) ? 'selected=selected' : '') :'')}}>{{$pval['nm_wilayah']}}</option>
              @endforeach
            </select>
          </div>



          <div class="clearfix"></div>
          <div class="periode {{(isset($filter['tipe']) ? (($filter['tipe'] == 'periode') ? '' : 'hide') :'hide')}} div-wrap">
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Mulai</label>
              <div class='input-group date year-only row'>
                  <input type='text' name="year_from" class="form-control" value="{{isset($filter) ? (isset($filter['year_from']) ? ($filter['year_from']? $filter['year_from']  :'' ): '') :''}}"/>
                  <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div>

            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
              <label for="tipe" class="control-label">Selesai</label>
              <div class='input-group date year-only row'>
                  <input type='text' name="year_to" class="form-control" value="{{isset($filter) ? (isset($filter['year_to'])? ($filter['year_to'] ? $filter['year_to'] : ''):'') :''}}"/>
                  <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div>
            </div>

          </div>
        <div class="jml_responden {{(isset($filter['tipe']) ? (($filter['tipe'] == 'jml_responden') ? '' : 'hide') :'hide')}} div-wrap">
          <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
            <label for="tipe" class="control-label">Dari</label>
            <input type='text' name="jml_from" class="form-control" value="{{isset($filter) ? (isset($filter['jml_from']) ? ($filter['jml_from']? $filter['jml_from']  :'' ): '') :''}}" onkeydown="numeric_only(event,this)"/>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
            <label for="tipe" class="control-label">Sampai</label>
            <input type='text' name="jml_to" class="form-control" value="{{isset($filter) ? (isset($filter['jml_to'])? ($filter['jml_to'] ? $filter['jml_to'] : ''):'') :''}}" onkeydown="numeric_only(event,this)"/>
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
            {{isset($s['year_from']) ? $s['year_from'] : ''}}
            {{isset($s['year_from']) && isset($s['year_to'])  ? '-' : ''}}
            {{isset($s['year_to']) ?  $s['year_to'] .', ' : ', '}}
          @elseif( $s['tipe'] == 'jml_responden')
            {{isset($s['jml_from']) ? $s['jml_from'] : ''}}
            {{isset($s['jml_from']) && isset($s['jml_to'])  ? '-' : ''}}
            {{isset($s['jml_to']) ?  $s['jml_to'] .', ' : ', '}}
          @elseif($s['tipe'] == 'status')
            @if($s['status'] == 'Y')
              Lengkap ,
            @else
              Belum Lengkap ,
            @endif
          @elseif($s['tipe'] == 'lokasi')
            {{ $filter['lokasi'] }} ,
          @elseif($s['tipe'] == 'kabupaten')
            {{ ( $filter['kabupaten'] ? (getWilayahName($filter['kabupaten'],false))  : '')}} ,
          @elseif($s['tipe'] == 'judul')
            {{ $filter['judul'] }} ,
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
