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
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <label for="tipe" class="control-label">Sasaran</label>
                        <input class="form-control" name="sasaran" value="{{isset($filter) ? (isset($filter['sasaran']) ? $filter['sasaran'] : '') :''}}" type="text"/>
                        <input type="hidden" name="pembuat" value="" class="form-control"/>
                        <input class="form-control" name="satker" value="" class="form-control" type="hidden"/>
                        <input class="form-control" name="wilayah" value="" type="hidden"/>
                        <input class="form-control" name="media" value="" type="hidden"/>
                        <input type="hidden" name="tglactstart" class="form-control" value=""/>
                        <input type="hidden" name="tglactend" class="form-control" value=""/>
                        <input class="form-control" name="jmlsebarstart" value="" type="hidden"/>
                        <input class="form-control" name="jmlsebarend" value="" type="hidden"/>                        
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <label for="tipe" class="control-label">Anggaran</label>
                        <select class="form-control select2" name="anggaran">
                            <option value="" {{isset($filter) ? ( isset($filter['anggaran'])? ($filter['anggaran'] == '' ? 'selected=selected':''): '' ): ''}}>Semua</option>                            
                            <option value="Dipa" {{isset($filter) ?( isset($filter['anggaran'])? ($filter['anggaran'] == 'Dipa' ? 'selected=selected':''): ''): ''}}>Dipa</option>                            
                            <option value="Nondipa" {{isset($filter) ?( isset($filter['anggaran'])? ($filter['anggaran'] == 'Nondipa' ? 'selected=selected':''): ''): ''}}>Nondipa</option>                            
                        </select>
                    </div>
                    <div class="col-sm-4 col-md-4 col-xs-12">
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
                            <input type="submit" class="btn btn-action btn-primary" value="Cari" name="cari" style="width: 100px;">
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
            Catatan : <i>*Cetak PDF dan Excel tidak dapat melebihi 150 data</i><br>
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