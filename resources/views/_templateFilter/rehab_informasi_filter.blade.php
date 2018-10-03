<div class="">
  <form class="form-group filter" action="{{isset($route_name) ? route($route_name) : ''}}" method="post">
    <div class="row">
      {{csrf_field()}}
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="row">
        <?php
          $key = [
            'nama' => 'Nama Lembaga',
            'alamat' => 'Alamat',
            'cp_nama' => 'Contact Person',
            'bentuk_layanan' => 'Bentuk Layanan',
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

          <div class="col-md-6 col-sm-6 col-xs-12 keyword {{(isset($filter['tipe']) ? ((($filter['tipe'] == 'nama' ) || ($filter['tipe'] == 'alamat') || ($filter['tipe'] == 'cp_nama') )  ? '' : 'hide') :'')}} div-wrap">
            <label for="tipe" class="control-label">Kata Kunci</label>
            <?php
              $kata_kunci = "";
              $tipe_keyword = ['nama','alamat','cp_nama'];
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

          <div class="col-md-6 col-sm-6 col-xs-12 bentuk_layanan {{(isset($filter['tipe']) ? ((($filter['tipe'] == 'bentuk_layanan' )  )  ? '' : 'hide') :'hide')}} div-wrap">
              <label for="tipe" class="control-label">Bentuk Layanan</label>

              <select class="form-control select2"  name="bentuk_layanan">
                <option>-- Pilih Bentuk Layanan --</option>
                <option value="ris" {{isset($filter) ? ( isset($filter['bentuk_layanan'])? ($filter['bentuk_layanan'] == 'ris' ? 'selected=selected':''): '' ): ''}}> Rawat Inap Sosial</option>
                <option value="rjs" {{isset($filter) ? ( isset($filter['bentuk_layanan'])? ($filter['bentuk_layanan'] == 'rjs' ? 'selected=selected':''): '' ): ''}}> Rawat Jalan Sosial</option>
                <option value="rim" {{isset($filter) ? ( isset($filter['bentuk_layanan'])? ($filter['bentuk_layanan'] == 'rim' ? 'selected=selected':''): '' ): ''}}> Rawat Inap Medis</option>
                <option value="rjm" {{isset($filter) ? ( isset($filter['bentuk_layanan'])? ($filter['bentuk_layanan'] == 'rjm' ? 'selected=selected':''): '' ): ''}}> Rawat Jalan Medis</option>

              </select>
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
          @if(isset($s['bentuk_layanan']))
           {{ isset($bentuk_layanan[$s['bentuk_layanan']]) ? $bentuk_layanan[$s['bentuk_layanan']]: $s['bentuk_layanan'] }}
          @elseif($s['tipe'] == 'status')
            {{( isset($s['status'])  ?( ($s['status'] == 'Y' ) ? 'Lengkap' .' ,' : 'Belum Lengkap'.' ,') :  '' )}}
          @else
            {{isset($filter['tipe']) ?( isset($filter[$filter['tipe']]) ? $filter[$filter['tipe']] .',': '')  :''}}
          @endif
        @else
          {{isset($s['keyword']) ? $s['keyword'] .' , ': ''}}
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
