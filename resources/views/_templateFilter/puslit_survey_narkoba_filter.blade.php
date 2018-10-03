<div class="">
  <form class="form-group filter" action="{{isset($route) ? route($route) : ''}}" method="post">
    <div class="row">
      {{csrf_field()}}
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="row">
        <?php
          $key = [
            'periode' => 'Tahun',
            'jml_responden' => 'Jumlah Responden',
            'angka' => 'Angka Kematian',
            'jns_narkoba' => 'Jenis Narkoba',
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


          <div class="col-md-6 col-sm-6 col-xs-12 status {{(isset($filter['tipe']) ? (($filter['tipe'] == 'status') ? '' : 'hide') :'hide')}} div-wrap">
            <label for="tipe" class="control-label"> Status</label>
             <select class="form-control select2 " name="status">
                <option>-- Pilih Status --</option>
                <option value="Y" {{(isset($filter['status']) ? (($filter['status'] == 'Y') ? 'selected=selected' : '') :'')}}>Lengkap</option>
                <option value="N" {{(isset($filter['status']) ? (($filter['status'] == 'N') ? 'selected=selected' : '') :'')}}>Belum Lengkap</option>

            </select>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12 jns_narkoba {{(isset($filter['tipe']) ? (($filter['tipe'] == 'jns_narkoba') ? '' : 'hide') :'hide')}} div-wrap">
            <label for="tipe" class="control-label"> Jenis Narkoba</label>
             <select class="form-control select2" id="jenisKasus" name="jns_narkoba" >
                <option>-- Jenis Narkoba -- </option>
                @if(isset($jenisBrgBuktiNarkotika))
                  @foreach($jenisBrgBuktiNarkotika as $keyGroup => $jenis )
                    <optgroup label="{{$keyGroup}}">
                      @foreach($jenis as $key => $val)
                      <option value="{{preg_replace('/\s+/', '', $key)}}" {{( isset($filter['jns_narkoba']) ? (($filter['jns_narkoba'] == $key) ? 'selected=selected' : '') : '') }}>{{$val}}</option>
                      @endforeach
                    </optgroup>
                  @endforeach
                @endif
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

        <div class="angka {{(isset($filter['tipe']) ? (($filter['tipe'] == 'angka') ? '' : 'hide') :'hide')}} div-wrap">
          <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
            <label for="tipe" class="control-label">Dari</label>
            <input type='text' name="angka_from" class="form-control" value="{{isset($filter) ? (isset($filter['angka_from']) ? ($filter['angka_from']? $filter['angka_from']  :'' ): '') :''}}" onkeydown="numeric_only(event,this)"/>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
            <label for="tipe" class="control-label">Sampai</label>
            <input type='text' name="angka_to" class="form-control" value="{{isset($filter) ? (isset($filter['angka_to'])? ($filter['angka_to'] ? $filter['angka_to'] : ''):'') :''}}" onkeydown="numeric_only(event,this)"/>
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
          @elseif( $s['tipe'] == 'angka')
            {{isset($s['angka_from']) ? $s['angka_from'] : ''}}
            {{isset($s['angka_from']) && isset($s['angka_to'])  ? '-' : ''}}
            {{isset($s['angka_to']) ?  $s['angka_to'] .', ' : ', '}}
          @elseif($s['tipe'] == 'status')
            @if($s['status'] == 'Y')
              Lengkap ,
            @else
              Belum Lengkap ,
            @endif
          @elseif($s['tipe'] == 'jns_narkoba')
            @if($s['jns_narkoba'])
              {!! getDetailBarangBukti($s['jns_narkoba']) !!} ,
            @endif
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
