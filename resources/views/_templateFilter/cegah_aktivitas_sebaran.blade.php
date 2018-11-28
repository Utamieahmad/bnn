<div class="formSearch" style="display:none;">
    <form class="form-group filter" action="{{isset($route) ? route($route) : ''}}" method="post">
        <div class="row" style="width: 100%;">
            {{csrf_field()}}
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <?php
                    $key = [
                        'pelaksana' => 'Instansi',
                        'periode' => 'Tanggal LKN',
                        'no_lap' => 'Nomor Kasus',
                        // 'BrgBukti' => 'Barang Bukti',
                        'status_kelengkapan' => 'Status',
                    ];
                    ?>

                    <div class="col-md-3 col-sm-3 col-md-12">
                        <label for="tipe" class="control-label">Pembuat</label>
                        <input type="text" name="pembuat" value="{{isset($filter) ? (isset($filter['userName']) ? $filter['userName'] : '') :''}}" class="form-control"/>
                    </div>

                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <label for="tipe" class="control-label">Satker</label>
                        <input class="form-control" name="satker" value="{{isset($filter) ? (isset($filter['userSatker']) ? $filter['userSatker'] : '') :''}}" class="form-control" type="text"/>
                    </div>

                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <label for="tipe" class="control-label">Wilayah</label>
                        <input class="form-control" name="wilayah" value="{{isset($filter) ? (isset($filter['userWilayah']) ? $filter['userWilayah'] : '') :''}}" type="text"/>
                    </div>

                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <label for="tipe" class="control-label">Media</label>
                        <input class="form-control" name="media" value="{{isset($filter) ? (isset($filter['nmmedia']) ? $filter['nmmedia'] : '') :''}}" type="text"/>
                    </div>

                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <label for="tipe" class="control-label">Tanggal Mulai</label>
                        <div class="input-group date date_start row">
                            <input type='text' name="tglactstart" class="form-control" value="{{isset($filter) ? (isset($filter['tglactstart']) ? $filter['tglactstart'] : '') :''}}"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>          
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <label for="tipe" class="control-label">Tanggal akhir</label>
                        <div class="input-group date date_start row">
                            <input type='text' name="tglactend" class="form-control" value="{{isset($filter) ? (isset($filter['tglactend']) ? $filter['tglactend'] : '') :''}}"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>                    
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <label for="tipe" class="control-label">Jumlah sebaran Mulai</label>
                        <input class="form-control" name="jmlsebarstart" value="{{isset($filter) ? (isset($filter['jmlsebarstart']) ? $filter['jmlsebarstart'] : '') :''}}" type="text"/>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <label for="tipe" class="control-label">Jumlah sebaran Akhir</label>
                        <input class="form-control" name="jmlsebarend" value="{{isset($filter) ? (isset($filter['jmlsebarend']) ? $filter['jmlsebarend'] : '') :''}}" type="text"/>
                    </div>                    

                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <label for="tipe" class="control-label">sasaran</label>
                        <input class="form-control" name="sasaran" value="{{isset($filter) ? (isset($filter['sasaran']) ? $filter['sasaran'] : '') :''}}" type="text"/>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <label for="tipe" class="control-label">Anggaran</label>
                        <select class="form-control select2" name="anggaran">
                            <option value="" {{isset($filter) ? ( isset($filter['anggaran'])? ($filter['anggaran'] == '' ? 'selected=selected':''): '' ): ''}}>Semua</option>                            
                            <option value="Dipa" {{isset($filter) ?( isset($filter['anggaran'])? ($filter['anggaran'] == 'Dipa' ? 'selected=selected':''): ''): ''}}>Dipa</option>                            
                            <option value="Nondipa" {{isset($filter) ?( isset($filter['anggaran'])? ($filter['anggaran'] == 'Nondipa' ? 'selected=selected':''): ''): ''}}>Nondipa</option>                            
                        </select>
                    </div>
                    <div class="col-sm-3 col-md-3 col-xs-12">
                        <label for="tipe" class="control-label">Jumlah Per Halaman</label>
                        <select class="form-control select2" name="limit">
                            <option value="5" {{isset($filter) ? ( isset($filter['limit'])? ($filter['limit'] == '5' ? 'selected=selected':''): '' ): ''}}>5</option>
                            <option value="10" {{isset($filter) ? ( isset($filter['limit'])? ($filter['limit'] == '10' ? 'selected=selected':''): '' ): ''}}>10</option>
                            <option value="25" {{isset($filter) ?( isset($filter['limit'])? ($filter['limit'] == '25' ? 'selected=selected':''): ''): ''}}>25</option>
                            <option value="50" {{isset($filter) ? ( isset($filter['limit'])? ($filter['limit'] == '50' ? 'selected=selected':''): ''): ''}}>50</option>
                            <option value="100" {{isset($filter) ? ( isset($filter['limit'])? ($filter['limit'] == '100' ? 'selected=selected':''): ''): ''}}>100</option>
                        </select>                                                
                    </div>                    
                    <div class="col-sm-3 col-md-3 col-xs-12">
                        <label for="tipe" class="control-label">&nbsp;</label>
                        <div class="m-t-3">
                            <input type="submit" class="btn btn-action btn-primary" value="Cari" name="cari" style="width: 239px;">
                            <!--input type="submit" class="btn btn-success btn-search" value="Cari" name="cari" style="width: 499px;"-->
                        </div>
                        <div class="clearfix"></div>
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
                Jumlah Per Halaman =
                @if(isset($s['limit']))
                {{$s['limit']}}
                @endif
            </i>
            @endif
        </div>

    </div>
</div>