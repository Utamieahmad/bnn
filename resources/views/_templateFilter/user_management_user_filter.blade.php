<div class="">
  <form class="form-group filter" action="{{isset($route) ? route($route) : ''}}" method="post">
    <div class="row">
      {{csrf_field()}}
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="row">
        <?php
          $key = [
            'user_name' => 'Nama',
            'email' => 'Email',
            'nip' => 'NIP',
            'kepegawaian' => 'Status Kepegawaian',
            'wilayah_id' => 'Wilayah',
            'active_flag' => 'Status'
          ];

        ?>

          <div class="col-md-6 col-sm-6 col-xs-12">
            <label for="tipe" class="control-label">Tipe</label>

            <select class="form-control select2"  name="tipe" onchange="formFilter(this)">
              <option value="">SEMUA</option>
              <option value="user_name" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'user_name') ? 'selected=selected' : '') :'')}}>Nama</option>
              <option value="email" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'email') ? 'selected=selected' : '') :'')}}>Email</option>
              <option value="nip" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'nip') ? 'selected=selected' : '') :'')}}>NIP</option>
              <option value="wilayah_id" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'wilayah_id') ? 'selected=selected' : '') :'')}}>Wilayah</option>
              <option value="kepegawaian" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'kepegawaian') ? 'selected=selected' : '') :'')}}>Status Kepegawaian</option>
              <option value="active_flag" {{(isset($filter['tipe']) ? (($filter['tipe'] == 'active_flag') ? 'selected=selected' : '') :'')}}>Status</option>
            </select>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12 keyword {{(isset($filter['tipe']) ? ((($filter['tipe'] == 'active_flag') || ($filter['tipe'] == 'kepegawaian') || ($filter['tipe'] == 'wilayah_id')) ? 'hide' : '') :'')}} div-wrap">
            <label for="tipe" class="control-label">Kata Kunci</label>
            <input class="form-control" name="kata_kunci" value="{{isset($filter) ? (isset($filter['keyword'])?$filter['keyword']:'') :''}}" {{ (isset($filter['keyword']) || isset($filter['tipe'])) ?  '':'disabled=disabled'}}  />
          </div>

          <div class="col-md-6 col-sm-6 col-xs-12 active_flag {{isset($filter['tipe']) ? ( $filter['tipe'] == 'active_flag' ? '':'hide'): 'hide'}} div-wrap" >
              <label for="tipe" class="control-label">Status</label>

              <select class="form-control select2" name="active_flag">
                <option value="Y" {{isset($filter)? (isset($filter['active_flag']) ? ($filter['active_flag'] == 'Y' ? 'selected=selected' : '') :''):''}} >Aktif</option>
                <option value="N"  {{isset($filter)? (isset($filter['active_flag']) ? ($filter['active_flag'] == 'N' ? 'selected=selected' : '') :''):''}} >Tidak Aktif</option>
              </select>
          </div>

          <div class="col-md-6 col-sm-6 col-xs-12 kepegawaian {{isset($filter['tipe']) ? ( $filter['tipe'] == 'kepegawaian' ? '':'hide'): 'hide'}} div-wrap" >
              <label for="tipe" class="control-label">Status Kepegawaian</label>

              <select class="form-control select2" name="kepegawaian">
                <option value="PHL" {{isset($filter)? (isset($filter['kepegawaian']) ? ($filter['kepegawaian'] == 'PHL' ? 'selected=selected' : '') :''):''}} >PHL</option>
                <option value="PNS"  {{isset($filter)? (isset($filter['kepegawaian']) ? ($filter['kepegawaian'] == 'PNS' ? 'selected=selected' : '') :''):''}} >PNS</option>
              </select>
          </div>

          <div class="col-md-6 col-sm-6 col-xs-12 wilayah_id {{isset($filter['tipe']) ? ( $filter['tipe'] == 'wilayah_id' ? '':'hide'): 'hide'}} div-wrap" >
              <label for="tipe" class="control-label">Wilayah</label>

              <select class="form-control select2" name="wilayah_id">
                @foreach($wilayah['data'] as $w)
                  <option value="{{ $w['id_wilayah'] }}" {{isset($filter)? (isset($filter['wilayah_id']) ? ($filter['wilayah_id'] == $w['id_wilayah'] ? 'selected=selected' : '') :''):''}} >{{ $w['nm_wilayah'] }}</option>
                @endforeach
              </select>
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
        @if(isset($s['tipe']))
          @if($s['tipe'] == 'active_flag')
            {{( isset($s['active_flag'])  ?( ($s['active_flag'] == 'Y' ) ? 'Aktif , ' : 'Tidak Aktif , ') :  '' )}}
          @elseif($s['tipe'] == 'kepegawaian')
            {{( isset($s['kepegawaian'])  ?( ($s['kepegawaian'] == 'PNS' ) ? 'PNS , ' : 'PHL , ') :  '' )}}
          @elseif($s['tipe'] == 'wilayah_id')
            {{( isset($s['wilayah_id'])  ? $nm_wilayah[$s['wilayah_id']] . ' , ' :  '' )}}
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
