<div class="">
  <form class="form-group filter" action="{{isset($route) ? route($route) : ''}}" method="post">
    <div class="row">
      {{csrf_field()}}
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="row">
        <?php
          $key = [
            'objek_reviu' => 'Objek Reviu',
            'no_sprint' => 'No. Surat Perintah',
            'ketua_tim' => 'Ketua Tim',
            'hasil_reviu' => 'Hasil Reviu',
            'status' => 'Status',
          ];

          $hasil_reviu = [
            'lap_realisasi' => 'Laporan Realisasi Anggaran',
            'neraca' => 'Neraca',
            'lap_operasional' => 'Laporan Operasional',
            'lap_perubahan' =>'Laporan Perubahan Ekuitas' ,
            'catatan_lap' => 'Catatan Atas Laporan Keuangan',
          ];

          $object_reviu =[
              'uappa' => 'UAPPA',
              'uappa_e1' =>  'UAPPA-E1',
              'uappa_w' => 'UAPPA-W',
              'uakpa' => 'UAKPA'
          ];
        ?>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <label for="tipe" class="control-label">Tipe</label>
            <select class="form-control select2"  name="tipe" onchange="formFilter(this)">
              <option value="">SEMUA</option>
              @if(count($key) >0)
                @foreach($key as $k => $val)
                  <option value="{{$k}}" {{(isset($filter['tipe']) ? (($filter['tipe'] == $k) ? 'selected=selected' : '') :'')}}> {{$val}}</option>
                @endforeach
              @endif
            </select>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12 keyword {{(isset($filter['tipe']) ? ((($filter['tipe'] == 'objek_reviu') || ($filter['tipe'] == 'hasil_reviu') || ($filter['tipe'] == 'status') ) ? 'hide' : '') :'')}} div-wrap">
            <label for="tipe" class="control-label">Kata Kunci</label>
            <input class="form-control" name="kata_kunci" value="{{isset($filter) ? (isset($filter['keyword'])?$filter['keyword']:'') :''}}" {{isset($filter['keyword']) ? ($filter['keyword'] ? '':'disabled=disabled') :'disabled=disabled'}}  />
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12 status {{(isset($filter['tipe']) ? ((($filter['tipe'] == 'status' )  )  ? '' : 'hide') :'hide')}} div-wrap">
            <label for="tipe" class="control-label">Tipe</label>

            <select class="form-control select2"  name="status">
              <option>-- Pilih Status -- </option>
              <option value="N" {{isset($filter) ? ( isset($filter['status'])? ($filter['status'] == 'N' ? 'selected=selected':''): '' ): ''}}> Belum Lengkap</option>
              <option value="Y" {{isset($filter) ? ( isset($filter['status'])? ($filter['status'] == 'Y' ? 'selected=selected':''): '' ): ''}}> Lengkap</option>

            </select>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
          <div class="col-md-6 col-sm-6 col-xs-12 hasil_reviu {{(isset($filter['tipe']) ? (($filter['tipe'] == 'hasil_reviu') ? '' : 'hide') :'hide')}} div-wrap"  onChange="clearInput(this,'hasil_reviu')">
            <label for="tipe" class="control-label">Hasil Reviu</label>

            <select class="form-control select2" name="hasil_reviu">
              <option>-- Pilih Hasil --</option>
              @foreach($hasil_reviu as $h=>$hval)
                <option value="{{$h}}" {{isset($filter) ? ( isset($filter['hasil_reviu'])? ($filter['hasil_reviu'] == $h ? 'selected=selected':''): '' ): ''}}>{{$hval}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12 hasil_reviu {{(isset($filter['tipe']) ? (($filter['tipe'] == 'hasil_reviu') ? '' : 'hide') :'hide')}} div-wrap">
            <label for="tipe" class="control-label">Nilai Hasil Reviu</label>
            <input class="form-control" name="hasil_reviu_value" value="{{isset($filter) ? (isset($filter['hasil_reviu_value'])?$filter['hasil_reviu_value']:'') :''}}" {{isset($filter['hasil_reviu_value']) ? ($filter['hasil_reviu_value'] ? '':'disabled=disabled') :''}}  />
          </div>
        </div>

        <div class="clearfix"> </div>



        <div class="row m-t-10">
          <div class="col-md-6 col-sm-6 col-xs-12 objek_reviu {{(isset($filter['tipe']) ? (($filter['tipe'] == 'objek_reviu') ? '' : 'hide') :'hide')}} div-wrap" onChange="clearInput(this,'objek_reviu')">
            <label for="tipe" class="control-label">Nama Objek</label>
            <select class="form-control select2" name="objek_reviu">
              <option>-- Pilih Objek --</option>
              @if(count($object_reviu) > 0 )
                @foreach($object_reviu as $o => $rev)
                  <option value="{{$o}}" {{(isset($filter['objek_reviu']) ? (($filter['objek_reviu'] == $o) ? 'selected=selected' : '') :'')}}>{{$rev}}</option>
                @endforeach
              @endif
              </select>

          </div>
          <div class="col-md-6 col-sm-6 col-xs-12 objek_reviu {{(isset($filter['tipe']) ? (($filter['tipe'] == 'objek_reviu') ? '' : 'hide') :'hide')}} div-wrap">
            <label for="tipe" class="control-label">Nilai Objek</label>
            <input class="form-control" name="objek_value" value="{{isset($filter) ? (isset($filter['objek_value'])?$filter['objek_value']:'') :''}}" {{isset($filter['objek_value']) ? ($filter['objek_value'] ? '':'disabled=disabled') :''}}  />
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
        {{ isset($s['tipe']) ? (isset($key[$s['tipe']]) ? $key[$s['tipe']] : '')  : ''}}
        @if( isset($s['tipe']))
          @php
            $t = $s['tipe'];
          @endphp
          @if($t == 'hasil_reviu')
            =  {{( isset($s[$t]) ? (  isset($hasil_reviu[$s[$t]]) ? $hasil_reviu[$s[$t]] : $s[$t] ) : '' )}}
            @if(isset($s[$t]))
              @if(isset($s['hasil_reviu_value']))
                , Nilai Hasil Reviu : {{$s['hasil_reviu_value']}}
              @endif
            @endif
          @elseif($t == 'objek_reviu')
            =  {{( isset($s[$t]) ? (  isset($object_reviu[$s[$t]]) ? $object_reviu[$s[$t]] : $s[$t] ) : '' )}}
            @if(isset($s[$t]))
              @if(isset($s['objek_value']))
                , Nilai Hasil Reviu = {{$s['objek_value']}}
              @endif
            @endif
          @elseif($t == 'status')
            = {{isset($s['status']) ? ( ($s['status'] == 'N') ? 'Belum Lengkap' : 'Lengkap' ) : ''}}
          @elseif($t == 'no_sprint' || $t ==  'ketua_tim')
             = {{isset($s['keyword']) ? $s['keyword'] : ''}}
          @endif
        @else

        @endif

        , Urutan =

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
