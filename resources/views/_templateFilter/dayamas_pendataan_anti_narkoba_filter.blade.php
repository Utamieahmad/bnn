<div class="">
  <form class="form-group filter" action="{{isset($route) ? route($route) : ''}}" method="post">
    <div class="row">
      {{csrf_field()}}
      <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="row">
        <?php
          $key = [
            'pelaksana' => 'Pelaksana',
            'periode' => 'Tanggal Pelaksanaan',
            'asal_penggiat' => 'Asal Penggiat',
            'jenis_kegiatan' => 'Jenis Kegiatan',
            'jumlah_peserta' => 'Jumlah Peserta',
            'materi' => 'Materi',
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

          <div class="col-md-6 col-sm-6 col-xs-12  keyword {{(isset($filter['tipe']) ? (($filter['tipe'] == 'materi') ? '' : 'hide') :'hide')}} div-wrap">
            <label for="tipe" class="control-label">Kata Kunci</label>
            <input class="form-control" name="kata_kunci" value="{{isset($filter) ? (isset($filter['materi'])? $filter['materi']:'') :''}}" {{isset($filter['materi']) ? ($filter['materi'] ? '':'disabled=disabled') :'disabled=disabled'}}  />
          </div>

          <div class="col-md-6 col-sm-6 col-xs-12 pelaksana {{(isset($filter['tipe']) ? (($filter['tipe'] == 'pelaksana') ? '' : 'hide') :'hide')}} div-wrap">
            <label for="tipe" class="control-label">Pelaksana</label>
            <select name="pelaksana" id="idpelaksana" class="form-control select2" tabindex="-1" aria-hidden="true">
              <option>-- Pelaksana --</option>
              @foreach($instansi as $in)
                <option value="{{$in['nm_instansi']}}"  {{(isset($filter['pelaksana']) ? (($filter['pelaksana'] == $in['nm_instansi']) ? 'selected=selected' : '') :'')}}> {{$in['nm_instansi']}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12 asal_penggiat {{(isset($filter['tipe']) ? (($filter['tipe'] == 'asal_penggiat') ? '' : 'hide') :'hide')}} div-wrap">
            <label for="tipe" class="control-label">Asal Penggiat</label>
             <select class="form-control select2 " name="asal_penggiat">
                <option>-- Pilih Penggiat --</option>
                @if(isset($asal_penggiat))
                    @if(count($asal_penggiat)>0)
                        @foreach($asal_penggiat as $sa =>$sval)
                            <option value="{{$sa}}" {{(isset($filter['asal_penggiat']) ? (($filter['asal_penggiat'] == $sa) ? 'selected=selected' : '') :'')}} >{{$sval}}</option>
                        @endforeach
                    @endif
                @endif
              }
            }
            </select>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12 jenis_kegiatan {{(isset($filter['tipe']) ? (($filter['tipe'] == 'jenis_kegiatan') ? '' : 'hide') :'hide')}} div-wrap">
            <label for="tipe" class="control-label">Asal Penggiat</label>
             <select class="form-control select2 " name="jenis_kegiatan">
                <option>-- Pilih Penggiat --</option>
                @if(isset($jenis_kegiatan_antinarkoba))
                        @if(count($jenis_kegiatan_antinarkoba))
                            @foreach($jenis_kegiatan_antinarkoba as $akey => $avalue)
                                <option value="{{$akey}}" {{(isset($filter['jenis_kegiatan']) ? (($filter['jenis_kegiatan'] == $akey) ? 'selected=selected' : '') :'')}}> {{$avalue}}</option>
                            @endforeach
                        @endif
                    @endif
              }
            }
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
          <div class="jumlah_peserta {{(isset($filter['tipe']) ? (($filter['tipe'] == 'jumlah_peserta') ? '' : 'hide') :'hide')}} div-wrap">
              <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
                <label for="tipe" class="control-label">Mulai</label>
                <input class="form-control" name="jml_from" value="{{isset($filter) ? (isset($filter['jml_from'])?$filter['jml_from']:'') :''}}" {{isset($filter['jml_from']) ? ($filter['jml_from'] ? '':'disabled=disabled') :''}}  />
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12 m-t-10">
                <label for="tipe" class="control-label">Sampai</label>
                <input class="form-control" name="jml_to" value="{{isset($filter) ? (isset($filter['jml_to'])?$filter['jml_to']:'') :''}}" {{isset($filter['jml_to']) ? ($filter['jml_to'] ? '':'disabled=disabled') :''}}  />
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


    </div>

  </div>
</div>
