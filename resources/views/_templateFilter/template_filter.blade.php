
<div class="" id="filter_template">
  <form class="form-group filter" action="{{isset($route) ? route($route) : ''}}" method="post">
    <div class="row">
      {{csrf_field()}}
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="row">

          <div class="col-md-6 col-sm-6 col-xs-12">
            <label for="tipe" class="control-label">Tipe</label>

            <select class="form-control select2" {{(isset($javascript) ? ($javascript ? $javascript : ''): '')}} name="tipe" >
              <option value="">SEMUA</option>
              @if(isset($parameter))
                @if(count($parameter) > 0 )
                  @foreach($parameter as $key => $p)
                    <option value="{{$key}}" {{( isset($filter) ? ($key == $filter['selected'] ? 'selected=selected' : '') : '')}}>{{$p}}</option>
                  @endforeach
                @endif
              @endif

            </select>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12 keyword {{isset($filter) ? ( (($filter['selected'] == 'tgl_pelaksanaan') || ($filter['selected'] == 'periode') || ($filter['selected'] == 'dipa_anggaran') || ($filter['selected'] == 'kelengkapan')|| ($filter['selected'] == 'jenis_kegiatan')) ? 'hide':''): ''}}">
            <label for="tipe" class="control-label">Kata Kunci</label>
            <input class="form-control" name="kata_kunci" value="{{(isset($filter) ? ($filter['kata_kunci'] ? $filter['kata_kunci']  : ''): '')}}" {{(isset($filter) ? ($filter['kata_kunci'] ? '' : 'disabled=disabled'): 'disabled=disabled')}}/>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12 tgl-pelaksanaan {{isset($filter) ? ( $filter['selected'] == 'tgl_pelaksanaan' ? '':'hide'): 'hide'}}" >
              <label for="tipe" class="control-label">Tanggal Pelaksanaan</label>
              <div class='input-group date date_start row'>
                  <input type='text' name="tgl_pelaksanaan" class="form-control" value="{{isset($filter) ? (isset($filter['tgl_pelaksanaan']) ? $filter['tgl_pelaksanaan'] : '' ): ''}}" disabled/>
                  <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div>
          </div>

          <div class="col-md-6 col-sm-6 col-xs-12 dipa_anggaran {{isset($filter) ? ( $filter['selected'] == 'dipa_anggaran' ? '':'hide'): 'hide'}}" >
              <label for="tipe" class="control-label">Sumber Anggaran</label>

              <select class="form-control select2" name="dipa_anggaran" {{isset($filter) ? ( $filter['selected'] == 'dipa_anggaran' ? '':'disabled=disabled'): 'disabled=disabled'}}>
                <option value="DIPA" {{isset($filter)? (isset($filter['sumber_anggaran']) ? ($filter['sumber_anggaran'] == 'DIPA' ? 'selected=selected' : '') :''):''}} >DIPA</option>
                <option value="NONDIPA"  {{isset($filter)? (isset($filter['sumber_anggaran']) ? ($filter['sumber_anggaran'] == 'NONDIPA' ? 'selected=selected' : '') :''):''}} >NON DIPA</option>
              </select>
          </div>

          <div class="col-md-6 col-sm-6 col-xs-12 kelengkapan {{isset($filter) ? ( $filter['selected'] == 'kelengkapan' ? '':'hide'): 'hide'}}" >
              <label for="tipe" class="control-label">Status Kelengkapan</label>

              <select class="form-control select2" name="kelengkapan" {{isset($filter) ? ( $filter['selected'] == 'kelengkapan' ? '':'disabled=disabled'): 'disabled=disabled'}}>
                <option value="Y" {{isset($filter)? (isset($filter['kelengkapan']) ? ($filter['kelengkapan'] == 'Y' ? 'selected=selected' : '') :''):''}} >Lengkap</option>
                <option value="N"  {{isset($filter)? (isset($filter['kelengkapan']) ? ($filter['kelengkapan'] == 'N' ? 'selected=selected' : '') :''):''}} >Tidak Lengkap</option>
              </select>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12 jenis_kegiatan {{isset($filter) ? ( $filter['selected'] == 'jenis_kegiatan' ? '':'hide'): 'hide'}}" >
            <label for="tipe" class="control-label">Jenis Kegiatan</label>
            <select name="jenis_kegiatan" class="form-control select2"  {{isset($filter) ? ( $filter['selected'] == 'jenis_kegiatan' ? '':'disabled=disabled'): 'disabled=disabled'}}>
                <option>-- Jenis Kegiatan --</option>
                @if(isset($kegiatan))
                    @if(count($kegiatan))
                         @foreach($kegiatan as $kkey => $kval )
                           <option value="{{trim($kval->lookup_name)}}" {{ (  isset( $filter['jenis_kegiatan'])? (( $filter['jenis_kegiatan'] == $kval->lookup_name) ? 'selected=selected' :'') :''  )}}> {{trim($kval->lookup_name)}} </option>
                        @endforeach
                    @endif
                @endif
            </select>
          </div>
          </div>
        </div>
      <div class="clearfix"></div>
      <div class="col-md-6 col-sm-6 col-xs-12 tgl-periode   {{isset($filter) ? ( $filter['selected'] == 'periode' ? '':'hide'): 'hide'}} m-t-10">
        <div class="row">
          <div class="col-md-6 col-sm-6 col-xs-12">
            <label for="tipe" class="control-label">Mulai</label>
            <div class='input-group date date_start row'>
                <input type='text' name="tgl_from" class="form-control" value="{{isset($filter) ? (isset($filter['tgl_from']) ? $filter['tgl_from'] : '') :''}}"/>
                <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>

          </div>
          <div class="col-md-6 col-sm-6 col-xs-12">
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
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="row m-t-10 m-b-20">
          <div class="col-md-6 col-sm-6 col-xs-12">
            <label for="tipe" class="control-label">Urutan</label>
            <select class="form-control select2" name="order">
              <option value="desc" {{isset($filter) ? ( isset($filter['order'])? ($filter['order'] == 'desc' ? 'selected=selected':''): '' ): ''}}>Atas ke bawah</option>
              <option value="asc" {{isset($filter) ? ( isset($filter['order'])? ($filter['order'] == 'asc' ? 'selected=selected':''): '' ): ''}}>Bawah ke atas</option>
            </select>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <label for="tipe" class="control-label">Jumlah Per Halaman</label>
            <select class="form-control select2" name="limit">
              <option value="5" {{isset($filter) ? ( isset($filter['limit'])? ($filter['limit'] == '5' ? 'selected=selected':''): '' ): ''}}>5</option>
              <option value="10" {{isset($filter) ? ( isset($filter['limit'])? ($filter['limit'] == '10' ? 'selected=selected':''): '' ): ''}}>10</option>
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
  </form>

    <div class="m-b-20">

      @php
        $s = $filter_parameter['selected'];
      @endphp
      Menampilkan :
      <i>
      @if($s['selected'] == 'periode')
        {{$parameter[$s['selected']]}}  {{$s['tgl_from']}} - {{$s['tgl_to']}} ,
      @elseif($s['selected'] == 'dipa_anggaran')
        {{$parameter[$s['selected']]}}  {{$s['sumber_anggaran']}} ,
      @elseif($s['selected'] == 'kelengkapan')
        {{$parameter[$s['selected']]}}  {{$s['kelengkapan']}} ,
      @elseif($s['selected'] == 'jenis_kegiatan')
        {{$parameter[$s['selected']]}}  {{$s['jenis_kegiatan']}} ,
      @else
        @if(isset($s['kata_kunci']) && isset($parameter[$s['selected']]))
          {{$parameter[$s['selected']]}}  {{$s['kata_kunci']}} ,
        @endif
      @endif

      Urutan
      @if($s['order'] == 'desc')
        Atas ke bawah
      @elseif($s['order'] == 'asc')
        Bawah ke Atas
      @else
        Atas ke bawah
      @endif
      , Jumlah Per Halaman {{$s['limit']}}
      </i>
    </div>

</div>
