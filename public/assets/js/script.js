$('.select2').select2();

var AnggaranXML = '';
var $idPelaksanaPendataanPelatihan = "";
//modal wastahti BrgBukti
$(document).ready(function(){
  localStorage.removeItem("nama_jaringan");
  localStorage.removeItem("bnn_radio");
  $('.wastahtiModalBrgBukti').on('click', function(){
    var id = $(this).attr('data-id');
    var idBrgBukti = $(this).attr('data-idBrgBukti');
    var nmBrgBukti = $(this).attr('data-nmBrgBukti');
    var jmlBrgBukti = $(this).attr('data-jmlBrgBukti');
    var kodeSatuan = $(this).attr('data-kodeSatuan');
    var keperluanLab = $(this).attr('data-keperluanLab');
    var keperluanDiklat = $(this).attr('data-keperluanDiklat');
    var keperluanIptek = $(this).attr('data-keperluanIptek');
    var jumlahDimusnahkan = $(this).attr('data-jumlahDimusnahkan');
    var tglPemusnahan = $(this).attr('data-tglPemusnahan');
    var lokasi = $(this).attr('data-lokasi');

    $('#modal_edit_brgbukti').find('#id').val(id);
    $('#modal_edit_brgbukti').find('#id_brgbukti').val(idBrgBukti);
    $('#modal_edit_brgbukti').find('#nm_brgbukti').val(nmBrgBukti);
    $('#modal_edit_brgbukti').find('#nm_satuan').val(kodeSatuan);
    $('#modal_edit_brgbukti').find('#jumlah_barang_bukti').val(jmlBrgBukti);
    $('#modal_edit_brgbukti').find('#keperluan_lab').val(keperluanLab);
    $('#modal_edit_brgbukti').find('#keperluan_diklat').val(keperluanDiklat);
    $('#modal_edit_brgbukti').find('#keperluan_iptek').val(keperluanIptek);
    $('#modal_edit_brgbukti').find('#jumlah_dimusnahkan').val(jumlahDimusnahkan);
    $('#modal_edit_brgbukti').find('#tgl_pemusnahan').val(tglPemusnahan);
    $('#modal_edit_brgbukti').find('#lokasi').val(lokasi);
    $('#modal_edit_brgbukti').modal('show');
  });
});

$(document).ready(function(){

  $('.addRapat').on('click', function(){
    var url = $(this).attr('data-url');

    document.getElementById("frm_add_perka").action = url;

    $("#frm_add_perka").submit();

  });

  $('.editPerkaFinalisasi').on('click', function(){
    document.getElementById("form_modalFinalisasiPerka").reset();
    var id = $(this).attr('data-id');
    var url = $(this).attr('data-url');

    $.ajax({
      'url': BASE_URL+ '/api/'+'perkafinalisasi'+'/'+ id, // diganti pakai url api yang ditentukan
      'type':'get',
      'headers': {
        'Authorization' : 'Bearer '+ TOKEN
      },
      'success' : function(data){
        console.log(data.data);
        $('#form_modalFinalisasiPerka').attr('action', url);
        $('#form_modalFinalisasiPerka').find('#finalisasi_id').val(data.data.id);
        $('#form_modalFinalisasiPerka').find('#tanggal').val(data.data.tanggal);
        $('#form_modalFinalisasiPerka').find('#no_sprint_peserta').val(data.data.no_sprint_peserta);
        $('#form_modalFinalisasiPerka').find('#no_sgas_peserta').val(data.data.no_sgas_peserta);
        $('#form_modalFinalisasiPerka').find('#no_sprint_nasum').val(data.data.no_sprint_nasum);

        if(data.data.meta_narasumber)
        {
          var metaNarasumber = JSON.parse(data.data.meta_narasumber);

          if(metaNarasumber.length > 0)
            $('#narasumber_repeater_finalisasi').find('.mt-repeater-before-item').html('');

          for(var i = 0; i < metaNarasumber.length; i++)
          {

            var element =
            '<div data-repeater-item="" class="mt-repeater-item">'+
            '<div class="row mt-repeater-row">'+
              '<div class="col-md-5 col-sm-5 col-xs-12">'+
                   '<label class="control-label">Narasumber</label>'+
                   '<input value="'+ metaNarasumber[i].narasumber_finalisasi +'" name="meta_narsum_materi[' + i + '][narasumber_finalisasi]" class="form-control" type="text"> </div>'+
               '<div class="col-md-6 col-sm-6 col-xs-12">'+
                   '<label class="control-label">Materi</label>'+
                   '<input value="'+ metaNarasumber[i].materi_finalisasi +'" name="meta_narsum_materi[' + i + '][materi_finalisasi]" class="form-control" type="text"> </div>'+
               '<div class="col-md-1 col-sm-1 col-xs-12">'+
                   '<a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">'+
                       '<i class="fa fa-close"></i>'+
                   '</a>'+
                '</div>'+
            '</div>'+
            '</div>';

            var prev = $('#narasumber_repeater_finalisasi').find('.mt-repeater-before-item').html();

            $('#narasumber_repeater_finalisasi').find('.mt-repeater-before-item').html(prev + element);


          }
        }
        else
        {
            var element =
            '<div data-repeater-item="" class="mt-repeater-item">'+
            '<div class="row mt-repeater-row">'+
              '<div class="col-md-5 col-sm-5 col-xs-12">'+
                   '<label class="control-label">Narasumber</label>'+
                   '<input value="" name="meta_narsum_materi[0][narasumber_finalisasi]" class="form-control" type="text"> </div>'+
               '<div class="col-md-6 col-sm-6 col-xs-12">'+
                   '<label class="control-label">Materi</label>'+
                   '<input value="" name="meta_narsum_materi[0][materi_finalisasi]" class="form-control" type="text"> </div>'+
               '<div class="col-md-1 col-sm-1 col-xs-12">'+
                   '<a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">'+
                       '<i class="fa fa-close"></i>'+
                   '</a>'+
                '</div>'+
            '</div>'+
            '</div>';

            $('#narasumber_repeater_finalisasi').find('.mt-repeater-before-item').html(element);
        }

        if(data.data.meta_peserta)
        {
          var metaPeserta = JSON.parse(data.data.meta_peserta);

          if(metaPeserta.length > 0)
            $('#peserta_repeater_finalisasi').find('.mt-repeater-before-item').html('');

          for(var i = 0; i < metaPeserta.length; i++)
          {

            var element =
            '<div data-repeater-item="" class="mt-repeater-item">'+
            '<div class="row mt-repeater-row">'+
              '<div class="col-md-5 col-sm-5 col-xs-12">'+
                   '<label class="control-label">Nama Instansi</label>'+
                   '<input value="'+ metaPeserta[i].nama_finalisasi +'" name="meta_peserta[' + i + '][nama_finalisasi]" class="form-control" type="text"> </div>'+
               '<div class="col-md-6 col-sm-6 col-xs-12">'+
                   '<label class="control-label">Jumlah Peserta</label>'+
                   '<input value="'+ metaPeserta[i].jumlah_finalisasi +'" name="meta_peserta[' + i + '][jumlah_finalisasi]" class="form-control" type="text"> </div>'+
               '<div class="col-md-1 col-sm-1 col-xs-12">'+
                   '<a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">'+
                       '<i class="fa fa-close"></i>'+
                   '</a>'+
                '</div>'+
            '</div>'+
            '</div>';

            var prev = $('#form_modalFinalisasiPerka').find('.mt-repeater-before-item').html();

            $('#peserta_repeater_finalisasi').find('.mt-repeater-before-item').html(prev + element);


          }
        }
        else
        {
            var element =
            '<div data-repeater-item="" class="mt-repeater-item">'+
            '<div class="row mt-repeater-row">'+
              '<div class="col-md-5 col-sm-5 col-xs-12">'+
                   '<label class="control-label">Nama Instansi</label>'+
                   '<input value="" name="meta_peserta[0][nama_harmonisasi]" class="form-control" type="text"> </div>'+
               '<div class="col-md-6 col-sm-6 col-xs-12">'+
                   '<label class="control-label">Jumlah Peserta</label>'+
                   '<input value="" name="meta_peserta[0][jumlah_harmonisasi]" class="form-control" type="text"> </div>'+
               '<div class="col-md-1 col-sm-1 col-xs-12">'+
                   '<a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">'+
                       '<i class="fa fa-close"></i>'+
                   '</a>'+
                '</div>'+
            '</div>'+
            '</div>';

            $('#peserta_repeater_finalisasi').find('.mt-repeater-before-item').html(prev + element);
        }


        if(data.data.laporan != null)
        {
          var directory = $('#form_modalFinalisasiPerka').find('#laporan a').attr('href');
          $('#form_modalFinalisasiPerka').find('#laporan a').attr('href', directory + data.data.laporan);
          $('#form_modalFinalisasiPerka').find('#laporan a').html(data.data.laporan);
          $('#form_modalFinalisasiPerka').find('#laporan').show();
        }

        $('#finalisasi_perka').modal('show');
      },
      'error':function(e){
        console.log('error '+JSON.stringify(e));
      }
    });
  });

  $('.editPerkaHarmonisasi').on('click', function(){
    document.getElementById("form_modalHarmonisasiPerka").reset();
    var id = $(this).attr('data-id');
    var url = $(this).attr('data-url');

    $.ajax({
      'url': BASE_URL+ '/api/'+'perkaharmonisasi'+'/'+ id, // diganti pakai url api yang ditentukan
      'type':'get',
      'headers': {
        'Authorization' : 'Bearer '+ TOKEN
      },
      'success' : function(data){

        $('#form_modalHarmonisasiPerka').attr('action', url);
        $('#form_modalHarmonisasiPerka').find('#harmonisasi_id').val(data.data.id);
        $('#form_modalHarmonisasiPerka').find('#tanggal').val(data.data.tanggal);
        $('#form_modalHarmonisasiPerka').find('#no_sprint_peserta').val(data.data.no_sprint_peserta);
        $('#form_modalHarmonisasiPerka').find('#no_sgas_peserta').val(data.data.no_sgas_peserta);
        $('#form_modalHarmonisasiPerka').find('#no_sprint_nasum').val(data.data.no_sprint_nasum);

        if(data.data.meta_narasumber)
        {
          var metaNarasumber = JSON.parse(data.data.meta_narasumber);

          if(metaNarasumber.length > 0)
            $('#narasumber_repeater').find('.mt-repeater-before-item').html('');

          for(var i = 0; i < metaNarasumber.length; i++)
          {

            var element =
            '<div data-repeater-item="" class="mt-repeater-item">'+
            '<div class="row mt-repeater-row">'+
              '<div class="col-md-5 col-sm-5 col-xs-12">'+
                   '<label class="control-label">Narasumber</label>'+
                   '<input value="'+ metaNarasumber[i].narasumber_harmonisasi +'" name="meta_narsum_materi[' + i + '][narasumber_harmonisasi]" class="form-control" type="text"> </div>'+
               '<div class="col-md-6 col-sm-6 col-xs-12">'+
                   '<label class="control-label">Materi</label>'+
                   '<input value="'+ metaNarasumber[i].materi_harmonisasi +'" name="meta_narsum_materi[' + i + '][materi_harmonisasi]" class="form-control" type="text"> </div>'+
               '<div class="col-md-1 col-sm-1 col-xs-12">'+
                   '<a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">'+
                       '<i class="fa fa-close"></i>'+
                   '</a>'+
                '</div>'+
            '</div>'+
            '</div>';

            var prev = $('#narasumber_repeater').find('.mt-repeater-before-item').html();

            $('#narasumber_repeater').find('.mt-repeater-before-item').html(prev + element);


          }
        }
        else
        {
            var element =
            '<div data-repeater-item="" class="mt-repeater-item">'+
            '<div class="row mt-repeater-row">'+
              '<div class="col-md-5 col-sm-5 col-xs-12">'+
                   '<label class="control-label">Narasumber</label>'+
                   '<input value="" name="meta_narsum_materi[0][narasumber_harmonisasi]" class="form-control" type="text"> </div>'+
               '<div class="col-md-6 col-sm-6 col-xs-12">'+
                   '<label class="control-label">Materi</label>'+
                   '<input value="" name="meta_narsum_materi[0][materi_harmonisasi]" class="form-control" type="text"> </div>'+
               '<div class="col-md-1 col-sm-1 col-xs-12">'+
                   '<a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">'+
                       '<i class="fa fa-close"></i>'+
                   '</a>'+
                '</div>'+
            '</div>'+
            '</div>';

            $('#narasumber_repeater').find('.mt-repeater-before-item').html(element);
        }

        if(data.data.meta_peserta)
        {
          var metaPeserta = JSON.parse(data.data.meta_peserta);

          if(metaPeserta.length > 0)
            $('#peserta_repeater').find('.mt-repeater-before-item').html('');

          for(var i = 0; i < metaPeserta.length; i++)
          {

            var element =
            '<div data-repeater-item="" class="mt-repeater-item">'+
            '<div class="row mt-repeater-row">'+
              '<div class="col-md-5 col-sm-5 col-xs-12">'+
                   '<label class="control-label">Nama Instansi</label>'+
                   '<input value="'+ metaPeserta[i].nama_harmonisasi +'" name="meta_peserta[' + i + '][nama_harmonisasi]" class="form-control" type="text"> </div>'+
               '<div class="col-md-6 col-sm-6 col-xs-12">'+
                   '<label class="control-label">Jumlah Peserta</label>'+
                   '<input value="'+ metaPeserta[i].jumlah_harmonisasi +'" name="meta_peserta[' + i + '][jumlah_harmonisasi]" class="form-control" type="text"> </div>'+
               '<div class="col-md-1 col-sm-1 col-xs-12">'+
                   '<a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">'+
                       '<i class="fa fa-close"></i>'+
                   '</a>'+
                '</div>'+
            '</div>'+
            '</div>';

            var prev = $('#peserta_repeater').find('.mt-repeater-before-item').html();

            $('#peserta_repeater').find('.mt-repeater-before-item').html(prev + element);


          }
        }
        else
        {
            var element =
            '<div data-repeater-item="" class="mt-repeater-item">'+
            '<div class="row mt-repeater-row">'+
              '<div class="col-md-5 col-sm-5 col-xs-12">'+
                   '<label class="control-label">Nama Instansi</label>'+
                   '<input value="" name="meta_peserta[0][nama_harmonisasi]" class="form-control" type="text"> </div>'+
               '<div class="col-md-6 col-sm-6 col-xs-12">'+
                   '<label class="control-label">Jumlah Peserta</label>'+
                   '<input value="" name="meta_peserta[0][jumlah_harmonisasi]" class="form-control" type="text"> </div>'+
               '<div class="col-md-1 col-sm-1 col-xs-12">'+
                   '<a href="javascript:;" data-repeater-delete="" class="btn btn-danger mt-repeater-delete">'+
                       '<i class="fa fa-close"></i>'+
                   '</a>'+
                '</div>'+
            '</div>'+
            '</div>';

            $('#peserta_repeater').find('.mt-repeater-before-item').html(prev + element);
        }


        if(data.data.laporan != null)
        {
          var directory = $('#form_modalHarmonisasiPerka').find('#laporan a').attr('href');
          $('#form_modalHarmonisasiPerka').find('#laporan a').attr('href', directory + data.data.laporan);
          $('#form_modalHarmonisasiPerka').find('#laporan a').html(data.data.laporan);
          $('#form_modalHarmonisasiPerka').find('#laporan').show();
        }

        $('#harmonisasi_perka').modal('show');
      },
      'error':function(e){
        console.log('error '+JSON.stringify(e));
      }
    });
  });

  $('.editPerkaDraftAwal').on('click', function(){
    document.getElementById("form_modalDraftPerka").reset();
    var id = $(this).attr('data-id');
    var url = $(this).attr('data-url');

    $.ajax({
      'url': BASE_URL+ '/api/'+'perkadraftawal'+'/'+ id, // diganti pakai url api yang ditentukan
      'type':'get',
      'headers': {
        'Authorization' : 'Bearer '+ TOKEN
      },
      'success' : function(data){
        console.log(data.data);
        $('#form_modalDraftPerka').attr('action', url);
        $('#form_modalDraftPerka').find('#draft_id').val(data.data.id);
        $('#form_modalDraftPerka').find('#tanggal').val(data.data.tanggal);
        $('#form_modalDraftPerka').find('#no_sprint').val(data.data.no_sprint);
        $('#form_modalDraftPerka').find('#jml_peserta').val(data.data.jml_peserta);

        if(data.data.laporan != null)
        {
          var directory = $('#form_modalDraftPerka').find('#laporan a').attr('href');
          $('#form_modalDraftPerka').find('#laporan a').attr('href', directory + data.data.laporan);
          $('#form_modalDraftPerka').find('#laporan a').html(data.data.laporan);
          $('#form_modalDraftPerka').find('#laporan').show();
        }

        $('#draft_perka').modal('show');
      },
      'error':function(e){
        console.log('error '+JSON.stringify(e));
      }
    });
  });

  $('.tambahBbNonNonnarkotika').on('click', function(){
    var url = $(this).attr('data-url');
    document.getElementById("form_modalNonnarkotika").reset();
    document.getElementById("form_modalNonnarkotika").action = url;
  });

  $('.editBbNonnarkotika').on('click', function(){
    document.getElementById("form_modalNonnarkotika").reset();
    var id = $(this).attr('data-id');
    var url = $(this).attr('data-url');
    // console.log(url);
    $.ajax({
      'url': BASE_URL+ '/api/'+'kasusbrgbukti'+'/'+ id, // diganti pakai url api yang ditentukan
      'type':'get',
      'headers': {
        'Authorization' : 'Bearer '+ TOKEN
      },
      'success' : function(data){
        console.log(data.data);
        $('#add_modalNonnarkotika').find('form').attr('action', url);
        $('#add_modalNonnarkotika').find('#bbId').val(data.data.kasus_barang_bukti_id);
        $('#add_modalNonnarkotika').find('#keterangan').val(data.data.keterangan);
        $('#add_modalNonnarkotika').find('#jenisKasus').val(data.data.id_brgbukti).trigger('change');
        $('#add_modalNonnarkotika').find('#jumlah_barang_bukti').val(data.data.jumlah_barang_bukti);
        $('#add_modalNonnarkotika').find('#kode_satuan_barang_bukti').val(data.data.kode_satuan_barang_bukti).trigger('change');
        $('#add_modalNonnarkotika').modal('show');
      },
      'error':function(e){
        console.log('error '+JSON.stringify(e));
      }
    });
  });

  $('.tambahBbPrekursor').on('click', function(){
    var url = $(this).attr('data-url');
    document.getElementById("form_modalPrekursor").reset();
    document.getElementById("form_modalPrekursor").action = url;
  });

  $('.editBbPrekursor').on('click', function(){
    document.getElementById("form_modalPrekursor").reset();
    var id = $(this).attr('data-id');
    var url = $(this).attr('data-url');
    // console.log(url);
    $.ajax({
      'url': BASE_URL+ '/api/'+'buktiprekursor'+'/'+ id, // diganti pakai url api yang ditentukan
      'type':'get',
      'headers': {
        'Authorization' : 'Bearer '+ TOKEN
      },
      'success' : function(data){
        console.log(data.data);
        $('#add_modalPrekursor').find('form').attr('action', url);
        $('#add_modalPrekursor').find('#bbId').val(data.data.barangbukti_nonnarkotika_id);
        $('#add_modalPrekursor').find('#jenisKasus').val(data.data.id_brgbukti).trigger('change');
        $('#add_modalPrekursor').find('#jumlah_barang_bukti').val(data.data.jumlah_barang_bukti);
        $('#add_modalPrekursor').find('#kode_satuan_barang_bukti').val(data.data.kode_satuan_barang_bukti).trigger('change');
        $('#add_modalPrekursor').modal('show');
      },
      'error':function(e){
        console.log('error '+JSON.stringify(e));
      }
    });
  });

  $('.tambahBbNarkotika').on('click', function(){
    var url = $(this).attr('data-url');
    document.getElementById("form_modalNarkotika").reset();
    document.getElementById("form_modalNarkotika").action = url;
  });

  $('.editBbNarkotika').on('click', function(){
    document.getElementById("form_modalNarkotika").reset();
    var id = $(this).attr('data-id');
    var url = $(this).attr('data-url');
    // console.log(url);
    $.ajax({
      'url': BASE_URL+ '/api/'+'kasusbrgbukti'+'/'+ id, // diganti pakai url api yang ditentukan
      'type':'get',
      'headers': {
        'Authorization' : 'Bearer '+ TOKEN
      },
      'success' : function(data){
        console.log(data.data);
        $('#add_modalNarkotika').find('form').attr('action', url);
        $('#add_modalNarkotika').find('#bbId').val(data.data.kasus_barang_bukti_id);
        $('#add_modalNarkotika').find('#jenisKasus').val(data.data.id_brgbukti).trigger('change');
        $('#add_modalNarkotika').find('#jumlah_barang_bukti').val(data.data.jumlah_barang_bukti);
        $('#add_modalNarkotika').find('#kode_satuan_barang_bukti').val(data.data.kode_satuan_barang_bukti).trigger('change');
        $('#add_modalNarkotika').modal('show');
      },
      'error':function(e){
        console.log('error '+JSON.stringify(e));
      }
    });
  });

  $('.editBbAdiktif').on('click', function(){
    document.getElementById("form_modalAdiktif").reset();
    var id = $(this).attr('data-id');
    var url = $(this).attr('data-url');
    // console.log(url);
    $.ajax({
      'url': BASE_URL+ '/api/'+'kasusbrgbukti'+'/'+ id, // diganti pakai url api yang ditentukan
      'type':'get',
      'headers': {
        'Authorization' : 'Bearer '+ TOKEN
      },
      'success' : function(data){
        console.log(data.data);
        $('#add_modalAdiktif').find('form').attr('action', url);
        $('#add_modalAdiktif').find('#bbId').val(data.data.kasus_barang_bukti_id);
        $('#add_modalAdiktif').find('#jenisKasus').val(data.data.id_brgbukti).trigger('change');
        $('#add_modalAdiktif').find('#jumlah_barang_bukti').val(data.data.jumlah_barang_bukti);
        $('#add_modalAdiktif').find('#kode_satuan_barang_bukti').val(data.data.kode_satuan_barang_bukti).trigger('change');
        $('#add_modalAdiktif').modal('show');
      },
      'error':function(e){
        console.log('error '+JSON.stringify(e));
      }
    });
  });

  $('.tambahAset').on('click', function(){
    var url = $(this).attr('data-url');
    document.getElementById("form_tersangka").reset();
    document.getElementById("form_modalAsetbangunan").reset();
    document.getElementById("form_modalAsetbangunan").action = url;
    document.getElementById("form_modalAsetbarang").reset();
    document.getElementById("form_modalAsetbarang").action = url;
    document.getElementById("form_modalAsetlogam").reset();
    document.getElementById("form_modalAsetlogam").action = url;
    document.getElementById("form_modalAsetrekening").reset();
    document.getElementById("form_modalAsetrekening").action = url;
    document.getElementById("form_modalAsetsurat").reset();
    document.getElementById("form_modalAsetsurat").action = url;
    document.getElementById("form_modalAsettanah").reset();
    document.getElementById("form_modalAsettanah").action = url;
    document.getElementById("form_modalAsetuang").reset();
    document.getElementById("form_modalAsetuang").action = url;
  });

  $('.editAset').on('click', function(){
    document.getElementById("form_modalAsetbangunan").reset();
    document.getElementById("form_modalAsetbangunan").action = url;
    document.getElementById("form_modalAsetbarang").reset();
    document.getElementById("form_modalAsetbarang").action = url;
    document.getElementById("form_modalAsetlogam").reset();
    document.getElementById("form_modalAsetlogam").action = url;
    document.getElementById("form_modalAsetrekening").reset();
    document.getElementById("form_modalAsetrekening").action = url;
    document.getElementById("form_modalAsetsurat").reset();
    document.getElementById("form_modalAsetsurat").action = url;
    document.getElementById("form_modalAsettanah").reset();
    document.getElementById("form_modalAsettanah").action = url;
    document.getElementById("form_modalAsetuang").reset();
    document.getElementById("form_modalAsetuang").action = url;
    var id = $(this).attr('data-id');
    var url = $(this).attr('data-url');
    var idAset = $(this).attr('data-idAset');
    // console.log(url);
    $.ajax({
      'url': BASE_URL+ '/api/'+'buktinonnarkotika'+'/'+ id, // diganti pakai url api yang ditentukan
      'type':'get',
      'headers': {
        'Authorization' : 'Bearer '+ TOKEN
      },
      'success' : function(data){
        console.log(data.data);
        $(idAset).find('form').attr('action', url);
        $(idAset).find('#AsetId').val(id);
        $(idAset).find('#jenis_aset').val(data.data.kode_jenisaset).trigger('change');
        $(idAset).find('#nama_aset').val(data.data.nama_aset);
        $(idAset).find('#jumlah_barang_bukti_aset').val(data.data.jumlah_barang_bukti_aset);
        $(idAset).find('#kode_satuan_barang_bukti_aset').val(data.data.kode_satuan_barang_bukti_aset).trigger('change');
        $(idAset).find('#nilai_aset').val(data.data.nilai_aset);
        $(idAset).find('#keterangan').val(data.data.keterangan);
        $(idAset).modal('show');
      },
      'error':function(e){
        console.log('error '+JSON.stringify(e));
      }
    });
  });

  $('.tambahTersangka').on('click', function(){
    var url = $(this).attr('data-url');
    document.getElementById("form_tersangka").reset();
    document.getElementById("form_tersangka").action = url;
  });

  $('.deleteTersangka').on('click', function(){

  });

  $('.editTersangka').on('click', function(){
    document.getElementById("form_tersangka").reset();
    var id = $(this).attr('data-id');
    var url = $(this).attr('data-url');
    // console.log(url);
    $.ajax({
      'url': BASE_URL+ '/api/'+'tersangka'+'/'+ id, // diganti pakai url api yang ditentukan
      'type':'get',
      'headers': {
        'Authorization' : 'Bearer '+ TOKEN
      },
      'success' : function(data){
        var date = new Date(data.data.tersangka_tanggal_lahir);
        var month = date.getMonth()+1;
        var tersangka_tanggal_lahir = date.getDate() + "/" + month + "/" + date.getFullYear();
        console.log(data.data);
        $('#add_modaltersangka').find('#form_tersangka').attr('action', url);
        $('#add_modaltersangka').find('#tersangka_id').val(data.data.tersangka_id);
        $('#add_modaltersangka').find('#pasal').val(data.data.pasal);
        $('#add_modaltersangka').find('#nama_tersangka').val(data.data.tersangka_nama);
        $('#add_modaltersangka').find('#no_identitas').val(data.data.no_identitas);
        $('#add_modaltersangka').find('#kode_jenisidentitas_'+data.data.kode_jenisidentitas).attr('checked', 'true');
        $('#add_modaltersangka').find('#alamat_tersangka').val(data.data.tersangka_alamat);
        $('#add_modaltersangka').find('#alamatktp_idkabkota').val(data.data.alamatktp_idkabkota).trigger('change');
        $('#add_modaltersangka').find('#alamatktp_kodepos').val(data.data.alamatktp_kodepos);
        $('#add_modaltersangka').find('#alamatdomisili').val(data.data.alamatdomisili);
        $('#add_modaltersangka').find('#alamatdomisili_idkabkota').val(data.data.alamatdomisili_idkabkota).trigger('change');
        $('#add_modaltersangka').find('#alamatdomisili_kodepos').val(data.data.alamatdomisili_kodepos);
        $('#add_modaltersangka').find('#alamatlainnya').val(data.data.alamatlainnya);
        $('#add_modaltersangka').find('#alamatlainnya_kodepos').val(data.data.alamatlainnya_kodepos);
        $('#add_modaltersangka').find('#alamatlainnya_idkabkota').val(data.data.alamatlainnya_idkabkota).trigger('change');
        $('#add_modaltersangka').find('#kode_jenis_kelamin_'+data.data.kode_jenis_kelamin).attr('checked', 'true');
        $('#add_modaltersangka').find('#tersangka_tempat_lahir').val(data.data.tersangka_tempat_lahir);
        $('#add_modaltersangka').find('#tersangka_tanggal_lahir').val(tersangka_tanggal_lahir);
        $('#add_modaltersangka').find('#kode_pendidikan_akhir_'+data.data.kode_pendidikan_akhir).attr('checked', 'true');
        $('#add_modaltersangka').find('#kode_pekerjaan_'+data.data.kode_pekerjaan).attr('checked', 'true');
        $('#add_modaltersangka').find('#kode_warga_negara_'+data.data.kode_warga_negara).val(data.data.kode_warga_negara).trigger('change');
        $('#add_modaltersangka').find('#kode_negara').val(data.data.kode_negara).trigger('change');
        $('#add_modaltersangka').find('#kode_peran_tersangka_'+data.data.kode_peran_tersangka).attr('checked', 'true');
        $('#add_modaltersangka').modal('show');
      },
      'error':function(e){
        console.log('error '+JSON.stringify(e));
      }
    });
  })
});

$(document).ready(function(){
  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth()+1; //January is 0!

  var yyyy = today.getFullYear();
  if(dd<10){
    dd='0'+dd;
  }
  if(mm<10){
    mm='0'+mm;
  }
  var today = dd+'/'+mm+'/'+yyyy;
  $('.tanggal').val(today);


  setTimeout(function() {
    $('.alert').hide('slow');
  }, 3000);
  $('.button-delete').click(function(e){
    $id  = $(this).data('target');
    $type  = $(this).data('type');
    TITLE = $(this).attr('data-url');
    $parent = $(this).attr('data-parent');
    $parent_id = $(this).attr('data-parent_id');
    $('.modal .target_id').val($id);
    $index = $(this).parents('tr').index();
    $('#modalDelete .target_index').val($index);
    $('#modalDelete .target_type').val($type);
    $('#modalDelete .target_parent').val($parent);
    $('#modalDelete .target_parent_id').val($parent_id);
    $('#modalDelete').modal('show');
  });
  $('input[type="checkbox"].flat').iCheck({
    checkboxClass: 'icheckbox_flat',
    increaseArea: '20%' // optional
  });
  // $('.confirm').click(function(id){
  // 	$id = $('.target_id').val();
  // 	$.ajax({
  //          'url': BASE_URL+ '/api/kasus/'+ id, // diganti pakai url api yang ditentukan
  //          'type':'delete',
  //          'data':{'id_kasus':$id},
  //          'success' : function(data){

  //           console.log(data);
  //          },
  //          'error':function(e){
  //           console.log('error '+JSON.stringify(e));
  //          }
  //     });
  // });

  $(function () {
    $('.tanggal').datetimepicker({
      format: 'DD/MM/YYYY',
      maxDate : new Date(),
    });

    $('.tanggal input').on('click',function(e){
        $(this).parent('.tanggal').data("DateTimePicker").show();
    });

    $('.all_date').datetimepicker({
      format: 'DD/MM/YYYY',
    });

    $('.periodebulan').datetimepicker({
      format: 'MM/YYYY',
      maxDate : new Date(),
    });

    $('.periodebulan input').on('click',function(){
        $('.periodebulan').data("DateTimePicker").show();
    });
    $('.all_date input').on('click',function(){
        $('.all_date').data("DateTimePicker").show();
    });

    $('.periodebulan input').on('keydown',function(){
        return false;
    });
    $('.all_date input').on('keydown',function(){
        return false;
    });

    $('.periodebulan_awal').datetimepicker({
      format: 'MM/YYYY'
    });



    $('.periodebulan_akhir').datetimepicker({
      format: 'MM/YYYY'
    });



    $('.datepicker-only,.date_start').datetimepicker({
      format: 'DD/MM/YYYY',
      maxDate : new Date(),
    });

    $('.date_end').datetimepicker({
      format: 'DD/MM/YYYY'
    });

    $('.datepicker-only input').on('click',function(){
        $('.datepicker-only').data("DateTimePicker").show();
    });

      $('.date_end input').on('click',function(e){
          $(this).parent('.date_end').data("DateTimePicker").show();
      });


      $('.date_start  input').on('click',function(e){
         $(this).parent('.date_start').data("DateTimePicker").show();
      });

      $('input[name=tgl_from]').on('change',function(e){
        console.log('tes');
        $('.date_end').datetimepicker({
          format: 'DD/MM/YYYY',
          minDate : $(this).datepicker('getDate'),
        });
      });//ssssss
    $('.datepicker-only input , .date_start  input, .date_end  input').on('keydown',function(){
        return false;
    });
    $('.tanggal input').on('keydown',function(){
        return false;
    });

    $('.birthDate').datetimepicker({
      format: 'DD/MM/YYYY',
      maxDate : new Date(),
      viewMode: 'years',
    });

    $(".birthDate").on("dp.change", function (e) {
      $date = new Date(e.date);
      $d = $date.getDate();
      $m = $date.getMonth();
      console.log('aa '+$m);
      $y = $date.getFullYear();
      $newDate= $y+'-'+($m +1 )+'-'+$d;
      $age = getAge($newDate);
      $('.age').val($age);

    });

    $('.birthDate input').on('click',function(){
        $('.birthDate').data("DateTimePicker").show();

    });

    $('.birthDate input').on('keydown',function(){
        return false;
    });


    $('.time-only').datetimepicker({
      format : "HH:mm",
    });

    $('.time-only input').on('click',function(){
        $('.time-only').data("DateTimePicker").show();
    });

    $('.time-only input').on('keydown',function(){
        return false;
    });

    $('.year-only').datetimepicker({
      format : "YYYY",
      maxDate : new Date(),
    });

    $('.year-only input').on('click',function(e){
        $(this).parent('.year-only').data("DateTimePicker").show();
    });

    $('.year-only input').on('keydown',function(){
        return false;
    });

    $('.tgl_tahan_start').datetimepicker({
      format: 'DD/MM/YYYY',
      maxDate : new Date(),
    });

    $(".tgl_tahan_start").on("dp.change", function (e) {
      var date1 = $("#tgl_masuk").val().split("/");
      var startDate = new Date(date1[2]+'-'+date1[1]+'-'+date1[0]);
      var date2 = $("#tgl_keluar").val().split("/");
      var endDate   = new Date(date2[2]+'-'+date2[1]+'-'+date2[0]);
      var diff_date = (endDate.getTime() - startDate.getTime());
      var years = diff_date/31536000000;
      var months = (diff_date % 31536000000)/2628000000;
      var days = ((diff_date % 31536000000) % 2628000000)/86400000;
      var hasil = '';
      if (Math.floor(years)>0){
        hasil = hasil+Math.floor(years)+' tahun ';
      }
      if (Math.floor(months)>0){
        hasil = hasil+Math.floor(months)+' bulan ';
      }
      if (Math.floor(days)>0){
        hasil = hasil+Math.floor(days)+' hari';
      }
      if (days) {
        $("#lama_penahanan").val(hasil);
      }
    });

    $('.tgl_tahan_end').datetimepicker({
      format: 'DD/MM/YYYY',
      maxDate : new Date(),
    });

    $(".tgl_tahan_end").on("dp.change", function (e) {
      var date1 = $("#tgl_masuk").val().split("/");
      var startDate = new Date(date1[2]+'-'+date1[1]+'-'+date1[0]);
      var date2 = $("#tgl_keluar").val().split("/");
      var endDate   = new Date(date2[2]+'-'+date2[1]+'-'+date2[0]);
      var diff_date = (endDate.getTime() - startDate.getTime());
      var years = diff_date/31536000000;
      var months = (diff_date % 31536000000)/2628000000;
      var days = ((diff_date % 31536000000) % 2628000000)/86400000;
      var hasil = '';
      if (Math.floor(years)>0){
        hasil = hasil+Math.floor(years)+' tahun ';
      }
      if (Math.floor(months)>0){
        hasil = hasil+Math.floor(months)+' bulan ';
      }
      if (Math.floor(days)>0){
        hasil = hasil+Math.floor(days)+' hari';
      }
      if (days) {
        $("#lama_penahanan").val(hasil);
      }
    });
  });

  $(function () {
    $('.tanggal-publish').datetimepicker({
         format: 'DD/MM/YYYY HH:mm:ss',
         // debug: true
    });
  });

  $('.noSearch').select2({
    minimumResultsForSearch: -1
  });

  $('.selectPropinsi').on('change', function(){
    var idPropinsi = $(this).val();
    $.ajax({
      'url': BASE_URL+ '/api/filterwilayah/'+ idPropinsi, // diganti pakai url api yang ditentukan
      'type':'get',
      // 'data':{'id_kasus':id},
      'success' : function(data){
        $('.selectKabupaten').html("");
        $('.selectKabupaten').html("<option>-- Pilih Kabupaten --</option>");
        $.each(data.data, function(key, value) {
          // console.log('test');
          $('.selectKabupaten')
          .append($("<option></option>")
          .attr("value",value.id_wilayah)
          .text(value.nm_jnswilayah+ " " +value.nm_wilayah));

        });
      },
      'error':function(e){
        console.log('error '+JSON.stringify(e));
      }
    });
  });

  $(document).on('change', '.selectPropinsimeta', function(){
    var idPropinsi = $(this).val();
    var name = $(this).attr("name");
    var metaid = name.replace("propinsi", "kabupaten");
    $.ajax({
      'url': BASE_URL+ '/api/filterwilayah/'+ idPropinsi, // diganti pakai url api yang ditentukan
      'type':'get',
      // 'data':{'id_kasus':id},
      'success' : function(data){
        console.log('.selectKabupatenmeta[name="'+metaid+'"]');
        $('.selectKabupatenmeta[name="'+metaid+'"]').html("");
        $('.selectKabupatenmeta[name="'+metaid+'"]').html("<option>-- Pilih Kabupaten --</option>");
        $.each(data.data, function(key, value) {
          // console.log('test');
          $('.selectKabupatenmeta[name="'+metaid+'"]')
          .append($("<option></option>")
          .attr("value",value.id_wilayah)
          .text(value.nm_jnswilayah+ " " +value.nm_wilayah));

        });
      },
      'error':function(e){
        console.log('error '+JSON.stringify(e));
      }
    });
  });

  var FormRepeater = function () {

    return {
      //main function to initiate the module
      init: function () {
        $('.mt-repeater').each(function(){
          $(this).repeater({
            show: function () {
              $(this).slideDown();
              // $('.date-picker').datepicker({
              //   rtl: App.isRTL(),
              //   orientation: "left",
              //   autoclose: true
              // });



            },

            hide: function (deleteElement) {
              if(confirm('Apakah anda yakin ingin menghapus elemen ini?')) {
                $(this).slideUp(deleteElement);
              }
            },

            ready: function (setIndexes) {

            }

          });
        });
      }

    };

  }();

  jQuery(document).ready(function() {
    FormRepeater.init();
  });

  // var next = 1;
  //   $(".add-more").click(function(e){
  //       e.preventDefault();
  //       var addto = "#field" + next;
  //       var addRemove = "#field" + (next);
  //       next = next + 1;
  //       var newIn = '<input autocomplete="off" class="input form-control" id="field' + next + '" name="field' + next + '" type="text">';
  //       var newInput = $(newIn);
  //       var removeBtn = '<button id="remove' + (next - 1) + '" class="btn btn-danger remove-me" >-</button></div><div id="field">';
  //       var removeButton = $(removeBtn);
  //       $(addto).after(newInput);
  //       $(addRemove).after(removeButton);
  //       $("#field" + next).attr('data-source',$(addto).attr('data-source'));
  //       $("#count").val(next);

  //           $('.remove-me').click(function(e){
  //               e.preventDefault();
  //               var fieldNum = this.id.charAt(this.id.length-1);
  //               var fieldID = "#field" + fieldNum;
  //               $(this).remove();
  //               $(fieldID).remove();
  //           });
  //   });

  var d = new Date();
  var month = d.getMonth();
  var year = d.getFullYear();
  var bulan_romawi = [];
  bulan_romawi['0'] = 'I';
  bulan_romawi['1'] = 'II';
  bulan_romawi['2'] = 'III';
  bulan_romawi['3'] = 'IV';
  bulan_romawi['4'] = 'V';
  bulan_romawi['5'] = 'VI';
  bulan_romawi['6'] = 'VII';
  bulan_romawi['7'] = 'VIII';
  bulan_romawi['8'] = 'IX';
  bulan_romawi['9'] = 'X';
  bulan_romawi['10'] = 'XI';
  bulan_romawi['11'] = 'XII';
  $('#noLKN3').val(bulan_romawi[month]);
  $('#noLKN4').val(year);



  $('#pelaksana').on('change', function(){
    var valPelaksana = $('#select2-pelaksana-container').html();
    var noLKN0 = $("#noLKN0").val();
    var noLKN1 = $("#noLKN1").val();
    var noLKN2 = $("#noLKN2").val();
    var noLKN3 = $("#noLKN3").val();
    var noLKN4 = $("#noLKN4").val();
    var noLKN5 = $("#noLKN5").val(valPelaksana);
    // var noLKN1_finall;
    // if (noLKN1.length == 1) {
    //     noLKN1_finall = '000'+ noLKN1;
    // }
    // if (noLKN1.length == 2) {
    //     noLKN1_finall = '00'+ noLKN1;
    // }
    // if (noLKN1.length == 3) {
    //     noLKN1_finall = '0'+ noLKN1;
    // }
    // if (noLKN1.length == 4) {
    //     noLKN1_finall = noLKN1;
    // }
    $("#pelaksanaGenerate").val(noLKN0+"/"+noLKN1+"/"+noLKN2+"/"+noLKN3+"/"+noLKN4+"/"+valPelaksana);
    // console.log(valPelaksana);
  });

  $('#noLKN0, #noLKN1, #noLKN3, #noLKN4, #noLKN5').on('keyup', function(){
    var noLKN0 = $("#noLKN0").val();
    var noLKN1 = $("#noLKN1").val();
    var noLKN2 = $("#noLKN2").val();
    var noLKN3 = $("#noLKN3").val();
    var noLKN4 = $("#noLKN4").val();
    var noLKN5 = $("#noLKN5").val();
    // var noLKN1_finall;
    // if (noLKN1.length == 1) {
    //     noLKN1_finall = '000'+ noLKN1;
    // }
    // if (noLKN1.length == 2) {
    //     noLKN1_finall = '00'+ noLKN1;
    // }
    // if (noLKN1.length == 3) {
    //     noLKN1_finall = '0'+ noLKN1;
    // }
    // if (noLKN1.length == 4) {
    //     noLKN1_finall = noLKN1;
    // }
    $("#pelaksanaGenerate").val(noLKN0+"/"+noLKN1+"/"+noLKN2+"/"+noLKN3+"/"+noLKN4+"/"+noLKN5);
  });

  $('#noLKN2').on('change', function(){
    var noLKN0 = $("#noLKN0").val();
    var noLKN1 = $("#noLKN1").val();
    var noLKN2 = $("#noLKN2").val();
    var noLKN3 = $("#noLKN3").val();
    var noLKN4 = $("#noLKN4").val();
    var noLKN5 = $("#noLKN5").val();
    // var noLKN1_finall;
    // if (noLKN1.length == 1) {
    //     noLKN1_finall = '000'+ noLKN1;
    // }
    // if (noLKN1.length == 2) {
    //     noLKN1_finall = '00'+ noLKN1;
    // }
    // if (noLKN1.length == 3) {
    //     noLKN1_finall = '0'+ noLKN1;
    // }
    // if (noLKN1.length == 4) {
    //     noLKN1_finall = noLKN1;
    // }
    $("#pelaksanaGenerate").val(noLKN0+"/"+noLKN1+"/"+noLKN2+"/"+noLKN3+"/"+noLKN4+"/"+noLKN5);
  });


  $right_col = $('.right_col');
  if($right_col.hasClass('balai-besar')){
    $val = $('.group-radio input[type="radio"]:checked').val();
    localStorage.setItem("bnn_radio", $val);
  }else if($right_col.hasClass('edit_settama')){
    $target = $('.list_pelaksana option:selected').data('target');
    // getBagianPelaksana(null,$target);
  }else if($right_col.hasClass('withAnggaran')) {
    $idPelaksanaPendataanPelatihan = $('.id_pelaksana').val();
  }else if($right_col.hasClass('has-paste')) {
    $('input[name="email"]').on("paste", function(e) {
        event.preventDefault();
        var pastedData = e.originalEvent.clipboardData.getData('text');
        var patt1 = /(@|.com|.co.id|gmail|ymail)/g;
        rt = pastedData.replace(patt1, '');
        $(this).val(rt);
    });
  }

  if($right_col.hasClass('num-paste')) {
    $('.decimal_num').on("paste", function(e) {
        event.preventDefault();
        var pastedData = e.originalEvent.clipboardData.getData('text');
        var patt1 = /(,)/g;
        rt = pastedData.replace(patt1, '.');
        $(this).val(rt);
    });
  }
  if($right_col.hasClass('mSelect') == true)  {
    setTimeout(function(){$('.mSelect2').select2();},600);
  }
});

function deleteData() {

  var id = $('.modal .target_id').val();
  var parent = $('.modal .target_parent').val();
  var parent_id = $('.modal .target_parent_id').val();

  var $trail = {};
  $trail['audit_menu'] = $('.modal .audit_menu').val();
  $trail['audit_event'] = 'delete';
  $trail['audit_value'] = JSON.stringify({"delete_id":id});
  $trail['audit_url'] = $('.modal .audit_url').val();
  $trail['audit_ip_address'] = $('.modal .audit_ip_address').val();
  $trail['audit_user_agent'] = $('.modal .audit_user_agent').val();

  console.log(parent);
  $.ajax({
    'url': BASE_URL+ '/api/'+TITLE+'/'+ id, // diganti pakai url api yang ditentukan
    'type':'delete',
    'headers': {
      'Authorization' : 'Bearer '+ TOKEN
    },
    'success' : function(data){
      $trail['audit_message'] = '';
        $.ajax({
          'url': BASE_URL+ '/api/'+parent+'/'+ parent_id, // diganti pakai url api yang ditentukan
          'type':'put',
          'headers': {
            'Authorization' : 'Bearer '+ TOKEN
          }
        });
        $.ajax({
          'type':'get',
          'url': BASE_URL+ '/api/audittrail/', // diganti pakai url api yang ditentukan

          'data': $trail,
          'headers': {
            'Authorization' : 'Bearer '+ TOKEN
          }
        });
        location.reload();
    },
    'error':function(e){
      $trail['audit_message'] = JSON.stringify(e);
      console.log('error '+JSON.stringify(e));
      $.ajax({
        'type':'get',
        'url': BASE_URL+ '/api/audittrail/', // diganti pakai url api yang ditentukan

        'data': $trail,
        'headers': {
          'Authorization' : 'Bearer '+ TOKEN
        }
      });
    }
  });
}

function deleteDataModal() {

  var id = $('.modal .target_id').val();
  var parent = $('.modal .target_parent').val();
  var parent_id = $('.modal .target_parent_id').val();

  console.log(id);
  $.ajax({
    'url': BASE_URL+ '/api/'+TITLE+'/'+ id, // diganti pakai url api yang ditentukan
    'type':'delete',
    'headers': {
      'Authorization' : 'Bearer '+ TOKEN
    },
    'success' : function(data){
        $.ajax({
          'url': BASE_URL+ '/api/'+parent+'/'+ parent_id, // diganti pakai url api yang ditentukan
          'type':'put',
          'headers': {
            'Authorization' : 'Bearer '+ TOKEN
          }
        });
      location.reload();
    },
    'error':function(e){
      console.log('error '+JSON.stringify(e));
    }
  });
}

function submit_form(ev){
  ev.preventDefault();
  $message = '';
  $password = $('input[name="password"]').val();
  $password_confirmation = $('input[name="password_confirmation"]').val();
  $email = $('input[name="email"]').val();
  $length = $password.length;
  if($email == ""){
    $message = 'Email harus diisi.';
  }else if($password == ""){
    $message = 'Kata sandi harus diisi.';
  }else if( $password_confirmation == ""){
    $message = 'Konfirmasi password harus diisi.';
  }else if($password != $password_confirmation){
    $message = 'Kata sandi yang Anda masukkan tidak sama.';
  }else if($length < 6){
    $message = 'Kata sandi minimal 6 karakter.';
  }else{
    $message = '';
  }
  if($message){
    $('.message-validation').html($message);
    $('.message-validation').show('slow');
    setTimeout(function() {
      $('.message-validation').hide('slow');
    }, 3000);
  }else{
    $form = $('form#form-submit')[0];
    $form.submit();
  }

}

function display_form(e){
  $('.change_password').show('slow');
  $(e).hide('slow');

}

function save_password(ev,e){
  ev.preventDefault();
  $old_password = $('input[name="old_password"]').val();
  $password = $('input[name="password"]').val();
  $password_confirmation = $('input[name="password_confirmation"]').val();
  $email = $('input[name="email"]').val();
  $user_id = $('input[name="user_id"]').val();
  $_token = $('input[name="_token"]').val();
  $token = $('input[name="token"]').val();
  $length = $password.length;
  $message = '';
  if($old_password == ""){
    $message = 'Kata sandi lama harus diisi.';
  }else if($password == ""){
    $message = 'Kata sandi baru harus diisi.';
  }else if( $password_confirmation == ""){
    $message = 'Konfirmasi password harus diisi.';
  }else if($password != $password_confirmation){
    $message = 'Kata sandi yang Anda masukkan tidak sama.';
  }else if($length < 6){
    $message = 'Kata sandi minimal 6 karakter.';
  }else{
    $message = '';
  }
  if($message){
    $('.message-validation').html($message);
    $('.message-validation').show('slow');
    setTimeout(function() {
      $('.message-validation').hide('slow');
    }, 3000);
  }else{
    $.ajax({
      'url': BASE_URL + '/api/password/edit', // diganti pakai url api yang ditentukan
      'type':'post',
      'headers': {'X-CSRF-TOKEN': $_token,'Authorization':'Bearer '+$token},
      'data':{'new_password':$password,'old_password':$old_password,'user_id':$user_id,'email':$email},
      'success' : function(data){
        if(data.code == 200){
          $('.message-validation').html(data.comment);
          $('.message-validation').show('slow');
          setTimeout(function() {
            $('.message-validation').hide('slow');
          }, 3000);
        }else{
          $('.message-validation-success').html(data.comment);
          $('.message-validation-success').show('slow');
          setTimeout(function() {
            $('.message-validation-success').hide('slow');
            $('.btn_change_password').show('slow');
            $(e).parents('.change_password').hide('slow');
          }, 3000);
        }
      },
      'error':function(e){
        console.log('error '+JSON.stringify(e));
      }
    });
  }
}

function cancel_save_password(ev,e){
  ev.preventDefault();
  $('.btn_change_password').show('slow');
  $(e).parents('.change_password').hide('slow');

}

function upload_form(ev,e){
  if(ev.type == "click"){
    $('.button-upload').trigger('click');
    $input = $('input[name="photo"]').val();
    $_token = $('input[name="_token"]').val();
  }else if(ev.type == "change"){
    var file_data = $('input[name="photo"]').prop('files')[0];
    var file_name = file_data.name;
    var form_data = new FormData();
    form_data.append('file', file_data);
    $('.photo_temp').val(file_name);
    $.ajax({
      'url':'/upload_photo', // diganti pakai url api yang ditentukan
      'type':'POST',
      'headers': {'X-CSRF-TOKEN': $_token},
      'data' : form_data,
      'success' : function(data){
        if(data.status == 'success'){
          $('.image-profile').attr('src',data.data.path);
          $('.image-process').hide();
          $('.btn-edit-profile').show();
        }else{

        }
      },
      'error':function(e){
        console.log('error '+JSON.stringify(e));
      },
      'beforeSend': function(){
        $('.loader').show();
      },
      'complete': function(){
        $('.loader').hide();
      },
      'contentType': false,       // The content type used when sending data to the server.
      'cache': false,             // To unable request pages to be cached
      'processData': false,
    });
  }else{
    ev.preventDefault();
  }
}
function cancel_upload_form(ev,e){
  $(e).parents('.image-process').hide();
  $('.btn-edit-profile').show();
}
function edit_profile(ev,e){
  $('.image-process').show();
  $(e).hide();
}
function numeric(ev) {
  $numeric = $('.numeric').val();
  // $numeric = $(this).val();
  $length = $numeric.length;
  if($length == 0 && ev.keyCode == 48) {
    ev.preventDefault();
  }else if((ev.keyCode >= 48 && ev.keyCode <= 57) ||(ev.keyCode >= 37 && ev.keyCode <= 40) || ev.keyCode== 8 || ev.keyCode== 9) {
    return true;
  }else{
    ev.preventDefault();
  }
}


function numeric_only(ev,e) {
  $numeric = $(e).val();
  $length = $numeric.length;
  console.log($numeric.charAt(0));
  if((($length == 0 && ev.keyCode) == 48)) {
    ev.preventDefault();
  }else if((ev.keyCode >= 48 && ev.keyCode <= 57) ||(ev.keyCode >= 37 && ev.keyCode <= 40) || ev.keyCode== 8 || ev.keyCode== 9 ) {
    return true;
  }else{
    ev.preventDefault();
  }
}
function reformatNumber(ev,e){
  if(ev.type == "click"){
    $value = $(e).val();
    $value = $value.replace(/,/g , "");
    $(e).val($value);
  }else{
    $value = parseInt($(e).val());
    $value = $value.toLocaleString();
    $(e).val($value);
  }
}
  var loader = "";
$(document).ready(function(){

  loader+='<div class="sk-fading-circle">';
  loader+='<div class="sk-circle1 sk-circle"></div>';
  loader+='<div class="sk-circle2 sk-circle"></div>';
  loader+='<div class="sk-circle3 sk-circle"></div>';
  loader+='<div class="sk-circle4 sk-circle"></div>';
  loader+='<div class="sk-circle5 sk-circle"></div>';
  loader+='<div class="sk-circle6 sk-circle"></div>';
  loader+='<div class="sk-circle7 sk-circle"></div>';
  loader+='<div class="sk-circle8 sk-circle"></div>';
  loader+='<div class="sk-circle9 sk-circle"></div>';
  loader+='<div class="sk-circle10 sk-circle"></div>';
  loader+='<div class="sk-circle11 sk-circle"></div>';
  loader+='<div class="sk-circle12 sk-circle"></div>';
  loader+='</div>';
  var idpelaksana = $('.selectPelaksana').val();
  if(idpelaksana){
    idpelaksana = idpelaksana;
  }else{
    idpelaksana = $idPelaksanaPendataanPelatihan;
  }

  if (idpelaksana){
    $kode_anggaran = $('#kode_anggaran').val();
    console.log('kode_anggaran '+$kode_anggaran);
    $.ajax({
      'url': BASE_URL+ '/api/getsatkerbyid/'+ idpelaksana, // diganti pakai url api yang ditentukan
      'type':'get',
      // 'data':{'id_kasus':id},
      'success' : function(data){

            var satkercode = data.data[0].satker_code;
            $('#kodeSatker').val(satkercode);
            $.ajax({
              'url': SOA_URL +'anggaran/bysatkerrs?kodesatker='+ satkercode, // diganti pakai url api yang ditentukan
              'type':'get',
              'dataType': 'xml',
              'beforeSend': function(){
                $('.selectAnggaran').parent('div').append(loader);
              },
              'complete': function(){
                $('.sk-fading-circle').remove();

              },
              'success' : function(xml){
                AnggaranXML = xml;
                $('.selectAnggaran').html("");
                $('.selectAnggaran').html("<option value=''>-- Pilih Anggaran --</option>");

                $(xml).find('MonevAnggaran').each(function(){

                    var sID    = $(this).find('DataID').text();
                    var sKode  = $(this).find('KodeAnggaran').text();
                    var sNama  = $(this).find('Sasaran').text();
                    var sTahun = $(this).find('Tahun').text();

                    var selected = false;
                    if($kode_anggaran){
                      if($kode_anggaran == sKode){
                        selected =true;
                      }else{
                        selected = false;
                      }
                    }else{
                      selected = false;
                    }


                    $('.selectAnggaran')
                    .append($("<option></option>")
                    .attr("value",sID)
                    .attr('selected',selected)
                    .text(sKode + ' - ' +sNama +' ('+ sTahun +')'));
                });

              },
              'error':function(e){
                console.log('error '+JSON.stringify(e));
                  $('.selectAnggaran').html("");
                  $('.selectAnggaran').html("<option value=''>-- Pilih Anggaran --</option>");
              }
            });

      },
      'error':function(e){
        console.log('error '+JSON.stringify(e));
        $('.selectAnggaran').html("");
        $('.selectAnggaran').html("<option value=''>-- Pilih Anggaran --</option>");
      }
    });
  }

  var satkercode = $('#kodeSatker').val();
  var idanggaran = $('#aid_anggaran').val();

  if(satkercode){
    $.ajax({
      'url': SOA_URL +'anggaran/bysatkerrs?kodesatker='+ satkercode, // diganti pakai url api yang ditentukan
      'type':'get',
      'dataType': 'xml',
      // 'data':{'id_kasus':id},
      'success' : function(xml){
        AnggaranXML = xml;
        $('.selectAnggaran').html("");
        $('.selectAnggaran').html("<option value=''>-- Pilih Anggaran --</option>");

        $(xml).find('MonevAnggaran').each(function(){
            var sID    = $(this).find('DataID').text();
            var sKode  = $(this).find('KodeAnggaran').text();
            var sNama  = $(this).find('Sasaran').text();
            var sTahun = $(this).find('Tahun').text();
            $('.selectAnggaran')
            .append($("<option></option>")
            .attr("value",sID)
            .text(sKode + ' - ' +sNama +' ('+ sTahun +')'));
        });

        if(idanggaran) {
          $('.selectAnggaran').val(idanggaran).trigger('change');
        }
      },
      'error':function(e){
        console.log('error '+JSON.stringify(e));
          $('.selectAnggaran').html("");
          $('.selectAnggaran').html("<option value=''>-- Pilih Anggaran --</option>");
      }
    });
  }

    $("#anggaran1, #anggaran2").click(function(){
          if (this.id == "anggaran2") {
              $("#PilihAnggaran").hide();
              $("#DetailAnggaran").hide();
          } else {
              $("#PilihAnggaran").show();
              $("#DetailAnggaran").show();
          }
      });



});

  $('.selectPelaksana').on('change', function(){
    var idpelaksana = $(this).val();
    $.ajax({
      'url': BASE_URL+ '/api/getsatkerbyid/'+ idpelaksana, // diganti pakai url api yang ditentukan
      'type':'get',
      // 'data':{'id_kasus':id},
      'success' : function(data){

            var satkercode = data.data[0].satker_code;
            $('#kodeSatker').val(satkercode);
            $.ajax({
              'url': SOA_URL +'anggaran/bysatkerrs?kodesatker='+ satkercode, // diganti pakai url api yang ditentukan
              'type':'get',
              'dataType': 'xml',
              'beforeSend': function(){
                $('.selectAnggaran').parent('div').append(loader);
              },
              'complete': function(){
                $('.sk-fading-circle').remove();

              },
              'success' : function(xml){
                AnggaranXML = xml;
                $('.selectAnggaran').html("");
                $('.selectAnggaran').html("<option value=''>-- Pilih Anggaran --</option>");

                $(xml).find('MonevAnggaran').each(function(){
                    var sID    = $(this).find('DataID').text();
                    var sKode  = $(this).find('KodeAnggaran').text();
                    var sNama  = $(this).find('Sasaran').text();
                    var sTahun = $(this).find('Tahun').text();
                    $('.selectAnggaran')
                    .append($("<option></option>")
                    .attr("value",sID)
                    .text(sKode + ' - ' +sNama +' ('+ sTahun +')'));
                });

              },
              'error':function(e){
                console.log('error '+JSON.stringify(e));
                  $('.selectAnggaran').html("");
                  $('.selectAnggaran').html("<option value=''>-- Pilih Anggaran --</option>");
              }
            });

      },
      'error':function(e){
        console.log('error '+JSON.stringify(e));
        $('.selectAnggaran').html("");
        $('.selectAnggaran').html("<option value=''>-- Pilih Anggaran --</option>");
      }
    });
  });


  $('.selectAnggaran').on('change', function(){

    var dataID = $(this).val();

    var arr = $(AnggaranXML).find('MonevAnggaran').filter(function() { return $(this).find('DataID').text() == dataID; });

    var hasil = '<table class="table table-striped nowrap">'+
                '<tr><td>Kode Anggaran</td><td>'+ $(arr).find('KodeAnggaran').text() +'</td></tr>'+
                '<tr><td>Sasaran</td><td>'+ $(arr).find('Sasaran').text() +'</td></tr>'+
                // '<tr><td>Pagu</td><td>'+ $(arr).find('Pagu').text() +'</td></tr>'+
                '<tr><td>Target Output</td><td>'+ $(arr).find('TargetOutput').text() +'</td></tr>'+
                '<tr><td>Satuan Output</td><td>'+ $(arr).find('SatuanOutput').text() +'</td></tr>'+
                '<tr><td>Tahun</td><td>'+ $(arr).find('Tahun').text() +'</td></tr>'+
                // '<tr><td>Wilayah</td><td>'+ $(arr).find('IDWilayah').text() +'</td></tr>'+
                '</table>'+
                '<input type="hidden" name="akode_anggaran" value="'+ $(arr).find('KodeAnggaran').text() +'" />'+
                '<input type="hidden" name="asasaran" value="'+ $(arr).find('Sasaran').text() +'" />'+
                '<input type="hidden" name="apagu" value="'+ $(arr).find('Pagu').text() +'" />'+
                '<input type="hidden" name="atarget_output" value="'+ $(arr).find('TargetOutput').text() +'" />'+
                '<input type="hidden" name="asatuan_output" value="'+ $(arr).find('SatuanOutput').text() +'" />'+
                '<input type="hidden" name="atahun" value="'+ $(arr).find('Tahun').text() +'" />'+
                '<input type="hidden" name="arefid_anggaran" value="'+ $(arr).find('DataID').text() +'" />';
    $('#hasil').html(hasil);

  });

  $(document).ready(function(){
    $('input[type=radio][name=status_pegawai]').on('change', function() {
      if (this.value == 'PHL') {
        $("#input_pns").addClass('hide');
        $("#input_phl").removeClass('hide');
      }
      else if (this.value == 'PNS') {
        $("#input_pns").removeClass('hide');
        $("#input_phl").addClass('hide');
      }

    });
  });
  if(typeof TOTAL_PAGES !== 'undefined'){
    $('#pagination-demo').twbsPagination({
      totalPages: TOTAL_PAGES,
      visiblePages: 5,
      startPage: CURRENT_PAGE,
      onPageClick: function (event, page) {
        $('#page-content').text('Page ' + page);
        if (page != CURRENT_PAGE) {
          window.location.href = CURRENT_URL+'?page='+page;
        }
      }
    });
    $('#pagination-footer').twbsPagination({
      totalPages: TOTAL_PAGES,
      visiblePages: 5,
      startPage: CURRENT_PAGE,
      onPageClick: function (event, page) {
        $('#page-content').text('Page ' + page);
        if (page != CURRENT_PAGE) {
          window.location.href = CURRENT_URL+'?page='+page;
        }
      }
    });
  }


function delete_row(ev,e){
  ev.preventDefault();
  $action = $(e).parents('form').attr('action');
  current_page = $('input[name="current_page"]').val();
  console.log('current page'+current_page);
  console.log('action '+$action);
  $id = $('.target_id').val();
  $type = $('.target_type').val();
  $parent_id = $('.parent_id').val();
   $_token = $('input[name="_token"]').val();
  console.log($id);
  console.log('parent '+$parent_id);

  $.ajax({
    'url': $action, // diganti pakai url api yang ditentukan
    'type':'POST',
    'headers': {'X-CSRF-TOKEN': $_token},
    'data' : {'id': $id,'page':current_page,'parent_id':$parent_id},
    'beforeSend': function(){
      $(e).parent('.modal-footer').hide('slow');
      $('.modal-body .content').hide('slow');

      $('.modal-footer-loading').show('slow');
    },
    'complete': function(){
      // $('.modal-footer-loading').addClass('hidden');

    },
    'success' : function(data){
      console.log('data '+ JSON.stringify(data) + ' //');
      if(data.code == 200){
         // if($type == "not-ajax"){
         //    $('.modal-footer-loading').hide('slow',function(){
         //       $('.modal-body .alert-message').html('<div class="alert alert-success">Data berhasil dihapus</div>').show('');
         //       setTimeout(function() {
         //          $('.modal').modal('hide');
         //          location.reload();
         //       }, 1000);
         //   });
         // }else{
           $index = $('.target_index').val();
           $current_number = $('tbody tr:eq(0) td:eq(0)').html();
           $current_number = parseInt($current_number);
           // $('tbody tr:eq('+$index+')').remove();
           // ajax after delete
           $data_return = "";
           if(data.data_return != ""){
              $data_return = data.data_return;
           }else{
             $data_return = "Data tidak ditemukan";
           }
           $('tbody').slideUp();
           $('tbody').html($data_return);
           $('.pagination-wrap').html(data.pagination);
           $('tbody').slideDown();

           // $('tbody tr').each(function(index){  $('tbody tr:eq('+index+') td:eq(0)').html(index +  $current_number)  });


           $('.modal-footer-loading').hide('slow',function(){
             $('.modal-body .alert-message').html('<div class="alert alert-success">Data berhasil dihapus</div>').show('');
             setTimeout(function() {
               $('.modal').modal('hide');
               $('.modal-body .alert-message').hide('slow');
               $('.modal-body .content').show();
                $('.modal-footer').show('slow');


             }, 1000);
           });
           location.reload();
         // }
      }else{
          $('.modal-body').hide('slow',function(){
          $('.modal-body .content').hide();
          $('.modal-body .alert-message').html('<div class="alert alert-warning">Data gagal dihapus</div>').show('');
            // $('.modal-body').html('<div class="alert alert-warning">Data gagal dihapus</div>');
            $('.modal-body').show('slow');
            setTimeout(function() {
              $('.modal').modal('hide');
              $('.modal-body .alert-message').hide('slow');
              $('.modal-body .content').show();
             $('.modal-footer').show('slow');

              // location.reload();
            }, 1000);
          });
      }
    },
    'error':function(e){
      console.log('error '+JSON.stringify(e));
    }
  });
}

function phone(ev,e) {
  $numeric = $(e).val();
  $length = $numeric.length;
  if((ev.keyCode >= 48 && ev.keyCode <= 57) ||(ev.keyCode >= 37 && ev.keyCode <= 40) || ev.keyCode== 8 || ev.keyCode== 9 ) {
    return true;
  }else{
    ev.preventDefault();
  }
}

function submit_modal(ev,e,idmodal="",type=""){
  if(idmodal){
    $modal = idmodal;
  }else{
    $modal = 'modal_form';
  }
  ev.preventDefault();
  $(e).attr('disabled','disabled');
  $action = $('form#'+$modal).attr('action');
  $data = $('form#'+$modal).serialize();
  $_token = $('input[name="_token"]').val();
  $parent_id = $('.parent_id').val();
  console.log('parent_id '+$parent_id);
  $.ajax({
   'url': $action, // diganti pakai url api yang ditentukan
   'type':'POST',
   'headers': {'X-CSRF-TOKEN': $_token},
   'data' : $data+'&parent_id='+$parent_id,
   'beforeSend': function(){
      $(e).html('Menyimpan ....');
   },
   'complete': function(){
      $(e).html('Kirim');
      $('.modal input[type="radio"]').prop('checked',false);
      $('.modal input[type="checkbox"]').prop('checked',false);
      $('.modal input[type="text"]').each(function(i){
         if($(this).attr('name') != '_token'){
            $(this).val('');
         }
      });
      $('input[type="email"]').each(function(i){
         $(this).val('');
      });

   },

   'success' : function(data){
      console.log(data);

      if( data.status == 'success' || data.status == 'sukses'){
         $td_length = $('tbody tr:eq(0) td').size();;
         if($td_length <= 1){
            $('tbody tr:eq(0)').remove();
         }
         if(data.data_return){
          // $('tbody').find('tr').remove().end().append(data.data_return);
          $('tbody').find('tr').remove().end().append(data.data_return);
         }

         if(data.pagination){
          $('.pagination-wrap').html('').html(data.pagination);
         }
         // if(type== 'ajax'){
            // $(e).attr('disabled',false);
            // index = $('.index').val();
            // $td_length = $('tbody tr:eq(0) td').size();;
            // console.log('td_length '+$td_length);
            // if($td_length <= 1){
            //   $('tbody tr:eq(0)').remove();
            // }
            // $current_number = $('tbody tr:eq(0) td:eq(0)').html();
            // $current_number = parseInt($current_number);
            // $($html).insertAfter('tbody tr:eq('+index+')');
            // $('tbody tr:eq('+index+')').remove();
            // $('tbody tr').each(function(index){  $('tbody tr:eq('+index+') td:eq(0)').html(index + $current_number)  });
            // $('.button-delete').bind('click');

         // }else{
            // console.log('disini');
            // $('tbody').prepend($html);
            // $('tbody tr').each(function(index){  $('tbody tr:eq('+index+') td:eq(0)').html(index + 1)  });
         // }

         // if($('tbody tr').length > 10){
         //    $('tbody tr:last-child').remove();
         // }


         $('.alert-success.alert-modal').show('slow',function(){
            setTimeout(function() {
               $('.modal').modal('hide');
               $('.alert').hide();

            // location.reload();
            }, 1000);
            $(e).attr('disabled',false);

         });
      }else{
         $(e).attr('disabled',false);
         $('.alert-warning.alert-modal').show('slow',function(){
            setTimeout(function() {
                  $('.modal').modal('hide');
                  $('.alert').hide();
               // location.reload();
               }, 1000);
            });
            $(e).attr('disabled',false);

         }
      },
      'error':function(e){
         console.log('error '+JSON.stringify(e));
      }
   });
}

function open_modalEditPesertaAlihProfesi(ev,e,uri="",id_form=""){
  ev.preventDefault();
  if(uri){
    $uri = uri;
  }else{
    $uri = '/pemberdayaan/dir_alternative/edit_peserta_alih_fungsi/';
  }
   if(id_form){
      $id_form = id_form;
   }else{
      $id_form = 'modal_form';
   }
   $id = $(e).data('target');
   $index = $(e).parents('tr').index();
   $('.index').val($index);
   $('input[name="id"]').val($id);
   $uri += $id;
   $('#modal_edit_peserta').modal("show");
   $action = $('form#'+$id_form).attr('action');
   $_token = $('input[name="_token"]').val();
   $.ajax({
      'url': $uri, // diganti pakai url api yang ditentukan
      'type':'GET',
      'headers': {'X-CSRF-TOKEN': $_token},
      'complete': function(){
         $('.loading-content').hide('fast');
         $('#modal_edit_form').show('slow');
      },
      'success' : function(data){
        console.log('data');
      console.log(data.status);
         if(data.status == 'success'){
            $d = data.data;
            console.log($d);
            $radio = ['kodeasalprofesi','kodejenisidentitas','jenis_kelamin','kodependidikanterakhir','kodepenghasilankotor'];

            $.each($d, function(i,val){
               $type = $('.'+i).attr('type');
               if(($.inArray(i,$radio) > -1) && (val != '')){
                  $('.'+i+'[value="'+val+'"]').prop('checked',true);
               }else if($type== "radio"){
                  $('.'+i+'[value="'+val+'"]').prop('checked',true);
               }else {
                  $('.'+i).val(val);
               }
            });

         }else{
            $('.loading-content').html('<div class="alert alert-warning">Data Gagal Ditampilkan.</div>');
         }
      },
    'error':function(e){
      // console.log('error '+JSON.stringify(e));
    }
  });

}


function getAge(dateString) {
  var today = new Date();
  var birthDate = new Date(dateString);
  var age = today.getFullYear() - birthDate.getFullYear();
  console.log(birthDate.getMonth());

  var m = today.getMonth() - birthDate.getMonth();
  if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
      age = age - 1;
  }

  return age;
}
// function delete_row2(ev,e){
//    ev.preventDefault();
//    $action = $(e).parents('form').attr('action');
//    $id = $('.target_id').val();
//    $_token = $('input[name="_token"]').val();

//    $.ajax({
//       'url': $action, // diganti pakai url api yang ditentukan
//       'type':'POST',
//       'headers': {'X-CSRF-TOKEN': $_token},
//       'data' : {'id': $id},
//       'beforeSend': function(){
//          $(e).parent('.modal-footer').hide('slow');
//          $('.modal-body .content').hide('slow');
//          $('.modal-footer-loading').show('slow');
//       },
//       'complete': function(){
//       // $('.modal-footer-loading').addClass('hidden');

//       },
//       'success' : function(data){
//       console.log('data '+data + ' //');
//          if(data.code == 200){
//             $index = $('.target_index').val();
//             $('tbody tr:eq('+$index+')').remove();
//             $('tbody tr').each(function(index){  $('tbody tr:eq('+index+') td:eq(0)').html(index + 1)  });
//             // $('.modal-body').hide('slow',function(){
//             //   $('.modal-body .content').hide();
//             //   $('.modal-body .alert').html('<div class="alert-success">Data berhasil dihapus</div>').removeClass('hidden');
//             //   // $('.modal-body').html('<div class="alert alert-success">Data berhasil dihapus</div>');
//             //   $('.modal-body').show('slow');
//             //    setTimeout(function() {
//             //     // $('.modal').modal('hide');
//             //     $('.modal-body .alert').addClass('hidden');
//             //     $('.modal-body .content').show();
//             //     // location.reload();
//             //   }, 1000);
//             // });

//             $('.modal-footer-loading').hide('slow',function(){
//                $('.modal-body .alert-message').html('<div class="alert alert-success">Data berhasil dihapus</div>').show('');
//                setTimeout(function() {
//                   $('.modal').modal('hide');
//                   $('.modal-body .alert-message').hide('slow');
//                   $('.modal-body .content').show();
//                   $('.modal-footer').show('slow');

//                   // location.reload();
//                }, 1000);
//             });
//          }else{
//             $('.modal-body').hide('slow',function(){
//                $('.modal-body .content').hide();
//                $('.modal-body .alert-message').html('<div class="alert alert-warning">Data gagal dihapus</div>').show('');
//                // $('.modal-body').html('<div class="alert alert-warning">Data gagal dihapus</div>');
//                $('.modal-body').show('slow');
//                setTimeout(function() {
//                   $('.modal').modal('hide');
//                   $('.modal-body .alert-message').hide('slow');
//                   $('.modal-body .content').show();
//                   $('.modal-footer').show('slow');

//                   // location.reload();
//                }, 1000);
//             });
//          }
//       },
//       'error':function(e){
//       console.log('error '+JSON.stringify(e));
//       }
//    });
// }
function ajax_delete(ev, e){
   $id  = $(e).data('target');
   $('.modal .target_id').val($id);
   $index = $(e).parents('tr').index();
   $('#modalDelete .target_index').val($index);
   $('#modalDelete').modal('show');
};

function get_page(ev,e,current_page){
   ev.preventDefault();
   page = "";

   page = current_page;
   $('input[name="current_page"]').val(page);
   console.log(page);
   $url = $(e).attr('href');
   console.log($url);
   $_token = $('input[name="_token"]').val();
   $parent_id = $('.parent_id').val();
   console.log($parent_id);

   $.ajax({
      'url': $url, // diganti pakai url api yang ditentukan
      'type':'GET',
      'headers': {'X-CSRF-TOKEN': $_token},
      'data':{'parent_id':$parent_id,'page':page},
      'beforeSend': function(){

      },
      'complete': function(){

      },
      'success' : function(data){
         console.log(data);
         if(data.status == "success"){
            $(e).parent('li').siblings().removeClass('active').end().addClass('active');
            $('tbody').hide('slow').html('').append(data.data).show('slow');
            $('.pagination-wrap').html(data.pagination);
         }else{
            alert('failed to get data');
         }
      },
      'error':function(e){
         console.log('error '+JSON.stringify(e));
      }
   });
}

function delete_form(ev,e){
  $id  = $(e).data('target');
  console.log($id);
  $('#modalDeleteForm .target_id').val($id);
  $('#modalDeleteForm').modal('show');
}
function delete_row_form(ev,e){

  ev.preventDefault();
  $action = $(e).parents('form').attr('action');
  console.log($action);
  $id = $('.target_id').val();
  console.log('id '+$id);
  $_token = $('input[name="_token"]').val();
  $.ajax({
    'url': $action, // diganti pakai url api yang ditentukan
    'type':'POST',
    'headers': {'X-CSRF-TOKEN': $_token},
    'data' : {'id': $id},
    'beforeSend': function(){
      $(e).parent('.modal-footer').hide('slow');
      $('.modal-body .content').hide('slow');

      $('.modal-footer-loading').show('slow');
    },
    'complete': function(){
      // $('.modal-footer-loading').addClass('hidden');

    },
    'success' : function(data){
      console.log(data);
      if(data.code == 200 || (data.code == 200 && data.status != 'error')){
            $('.modal-footer-loading').hide('slow',function(){
               $('.modal-body .alert-message').html('<div class="alert alert-success">Data berhasil dihapus</div>').show('');
               setTimeout(function() {
                  $('.modal').modal('hide');
                  $('.modal-body .alert-message').hide('slow');
                  $('.modal-body .content').show();
                  $('.modal-footer').show('slow');
                  location.reload();
               }, 1000);
           });
      }else{
          $('.modal-body').hide('slow',function(){
          $('.modal-body .content').hide();
          $('.modal-body .alert-message').html('<div class="alert alert-warning">Data gagal dihapus</div>').show('');
            // $('.modal-body').html('<div class="alert alert-warning">Data gagal dihapus</div>');
            $('.modal-body').show('slow');
            setTimeout(function() {
              $('.modal').modal('hide');
              $('.modal-body .alert-message').hide('slow');
              $('.modal-body .content').show();
              $('.modal-footer').show('slow');
              location.reload();
            }, 1000);
          });
      }
    },
    'error':function(e){
      console.log('error '+JSON.stringify(e));
    }
  });
}


function delete_row_peserta(ev,e){
  $id  = $(e).data('target');
  $type  = $(e).data('type');
  $('.modal .target_id').val($id);
  $index = $(e).parents('tr').index();
  $('#modalDelete .target_index').val($index);
  $('#modalDelete .target_type').val($type);
  $('#modalDelete').modal('show');
}




function submit_modal_update(ev,e,idmodal="",type=""){
  if(idmodal){
    $modal = idmodal;
  }else{
    $modal = 'modal_form';
  }
  ev.preventDefault();
  $(e).attr('disabled','disabled');
  $action = $('form#'+$modal).attr('action');
  $data = $('form#'+$modal).serialize();
  $_token = $('input[name="_token"]').val();
  index = $('.index').val();
  console.log('index '+index);
  $.ajax({
   'url': $action, // diganti pakai url api yang ditentukan
   'type':'POST',
   'headers': {'X-CSRF-TOKEN': $_token},
   'data' : $data,
   'beforeSend': function(){
      $(e).html('Menyimpan ....');
   },
   'complete': function(){
      $(e).html('Kirim');
      // $('input[type="radio"]').prop('checked',false);
      // $('input[type="checkbox"]').prop('checked',false);
      // $('input[type="text"]').each(function(i){
      //    if($(this).attr('name') != '_token'){
      //       $(this).val('');
      //    }
      // });
      $('input[type="email"]').each(function(i){
         $(this).val('');
      });

   },

   'success' : function(data){
      console.log(data);
      $html = data.data_return;
      console.log($html );
      if(data.status == 'success'){
         // if(type== 'ajax'){
            // $(e).attr('disabled',false);
            // index = $('.index').val();
            // $td_length = $('tbody tr:eq(0) td').size();;
            // console.log('td_length '+$td_length);
            // if($td_length <= 1){
            //   $('tbody tr:eq(0)').remove();
            // }
            $current_number = $('tbody tr:eq(0) td:eq(0)').html();
            $current_number = parseInt($current_number);
            if(data.data_return){
              $('tbody tr:eq('+index+')').find('td').remove().end().html(data.data_return);
            }else{
              $($html).insertAfter('tbody tr:eq('+index+')');
              $('tbody tr:eq('+index+')').remove();
            }

            $('tbody tr').each(function(index){  $('tbody tr:eq('+index+') td:eq(0)').html(index + $current_number)  });

            // $('.button-delete').bind('click');

         // }else{
            // console.log('disini');
            // $('tbody').prepend($html);
            // $('tbody tr').each(function(index){  $('tbody tr:eq('+index+') td:eq(0)').html(index + 1)  });
         // }

         // if($('tbody tr').length > 10){
         //    $('tbody tr:last-child').remove();
         // }


         $('.alert-success.alert-modal').show('slow',function(){
            setTimeout(function() {
               $('.modal').modal('hide');
               $('.alert').hide();

            // location.reload();
            }, 1000);
            $(e).attr('disabled',false);

         });
      }else{
         $('.alert-warning.alert-modal').show('slow',function(){
            setTimeout(function() {
                  $('.modal').modal('hide');
                  $('.alert').hide();
               // location.reload();
               }, 1000);
            });
            $(e).attr('disabled',false);

         }
      },
      'error':function(e){
         console.log('error '+JSON.stringify(e));
      }
   });
}


function decimal(evt,ele) {
  // $numeric = $(e).val();
  // $length = $numeric.length;
  // var regex = /^\d+(,\d{0,2})?$/;
  // if((ev.keyCode >= 48 && ev.keyCode <= 57) ||(ev.keyCode >= 37 && ev.keyCode <= 40) || ev.keyCode== 8 || ev.keyCode== 9|| ev.keyCode== 190 ) {
  //   if( !regex.test($numeric) ) {
  //     return false;
  //   }else{
  //     return true;
  //   }
  // }else{
  //   ev.preventDefault();
  // }
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var value = ele.value + key;
  var regex = /^\d+(,\d{0,2})?$/;
  // var regex = /^(0*100{1,1}\.?((?<=\.)0*)?%?$)|(^0*\d{0,2}\.?((?<=\.)\d{0,2})?%?)$/;
  if( !regex.test(value)) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}


function dasarkegiatan(e) {
  $value = $(e).val();
  if ($value == 'sprint' ) {
    $(".no_sprint").removeClass("hide");
    $(".no_spk").addClass("hide");
    $("#no_sprint").attr('required',true);
    $("#no_spk").removeAttr("required");
  } else {
    $(".no_sprint").addClass("hide");
    $(".no_spk").removeClass("hide");
    $("#no_sprint").removeAttr("required");
    $("#no_spk").attr('required',true);
  }
}

// function kelengkapan(e) {
//   $value = $(e).val();
//   if ($value == 'lengkap' ) {
//     $(".ket_lengkap").removeClass("hide");
//     $(".ket_tdk_lengkap").addClass("hide");
//   } else {
//     $(".ket_lengkap").removeClass("hide");
//     $(".ket_tdk_lengkap").addClass("hide");
//   }
// }

// function kesesuaian(e) {
//   $value = $(e).val();
//   if ($value == 'sesuai' ) {
//     $(".ket_sesuai").removeClass("hide");
//     $(".ket_tdk_sesuai").addClass("hide");
//   } else {
//     $(".ket_sesuai").removeClass("hide");
//     $(".ket_tdk_sesuai").addClass("hide");
//   }
// }

function select_instansi(ev,e,val){
  if(val == true){
    $('.bnn-radio').show().removeClass('hide');
    $('.non-bnn-radio').hide().addClass('hide');
    // $('.non-bnn-radio').find('input').attr('disabled',true);
    // $('.bnn-radio input, .bnn-radio select').attr('disabled',false);

  }else{
    $('.bnn-radio').hide().addClass('hide');
    $('.non-bnn-radio').show().removeClass('hide');
    console.log($(e).val());
    $current = $(e).val();
    $storage = localStorage.getItem('bnn_radio');
    console.log($storage);
    if($current != $storage){
      $('.mt-repeater-item.default').removeClass('hide').find('input').val('');
      $('.mt-repeater-item.current-item').addClass('hide');
    }else{
      $('.mt-repeater-item.default').addClass('hide').find('input').val('');
      $('.mt-repeater-item.current-item').removeClass('hide');
    }
    // $('.non-bnn-radio').find('input').attr('disabled',false);
    // $('.bnn-radio input, .bnn-radio select').attr('disabled',true);
  }
}
$('#jenis_aset').on('change', function(){
  var jenis = $(this).val();
  if(jenis=="ASET_BARANG"){
    $('#kode_jenisaset').val(jenis);
    $('#kode_kelompokaset').val('KEL_ASET_BERGERAK');
  } else if(jenis=="ASET_TANAH") {
    $('#kode_jenisaset').val(jenis);
    $('#kode_kelompokaset').val('KEL_ASET_TDKBERGERAK');
  } else if(jenis=="ASET_BANGUNAN") {
    $('#kode_jenisaset').val(jenis);
    $('#kode_kelompokaset').val('KEL_ASET_TDKBERGERAK');
  } else if(jenis=="ASET_LOGAMMULIA") {
    $('#kode_jenisaset').val(jenis);
    $('#kode_kelompokaset').val('KEL_ASET_TDKBERGERAK');
  } else if(jenis=="ASET_UANGTUNAI") {
    $('#kode_jenisaset').val(jenis);
    $('#kode_kelompokaset').val('KEL_ASET_UANG');
  } else if(jenis=="ASET_REKENING") {
    $('#kode_jenisaset').val(jenis);
    $('#kode_kelompokaset').val('KEL_ASET_UANG');
  } else if(jenis=="ASET_SURATBERHARGA") {
    $('#kode_jenisaset').val(jenis);
    $('#kode_kelompokaset').val('KEL_ASET_TDKBERGERAK');
  }
});

function pilih_sasaran(e){
  $val = $(e).val();
  if($val == ''){
    $('.hasil_penilaian').addClass('hide');
  }else{
    $('.hasil_penilaian').removeClass('hide');
    $('.hasil_penilaian .mt-repeater-item:gt(0)').slideUp('slow').remove();
    $('.hasil_penilaian input').val('');
    $('.hasil_penilaian select option').prop('selected',false).end().eq(0).prop('selected',true);
    $('.hasil_penilaian .select2-selection__rendered').html('-- Hasil Penilaian --');

  }

}

$('.selectSatker').on('change', function(event,a){

  var satker = $(this).val();
  $.ajax({
    'url': SOA_URL +'simpeg/penyidikbysatker?unit_id='+ satker, // diganti pakai url api yang ditentukan
    'type':'get',
    // 'data':{'id_kasus':id},
    'success' : function(data){
      $html = "";
      // $('.selectPenyidik').last().html("");
      $html  += "<option value=''>-- Pilih Penyidik --</option>";
      if(data.data){

        $.each(data.data.pegawai, function(key, value) {
          $html  +=  '<option value="'+value.nama+'">'+value.nama+'</option>';
        });
        if(a == 'last'){
          $('.selectPenyidik').last().html('').append($html);
        }else{
          $('.selectPenyidik').html('').append($html);
        }
      }else{

      }
    },
    'error':function(e){
      console.log('error '+JSON.stringify(e));
      $('.selectPenyidik').html("");
      $('.selectPenyidik').html("<option value=''>-- Pilih Penyidik --</option>");
    }
  });
});

$('.selectSatkerPntia').on('change', function(event,a){

  var satker = $(this).val();
  $.ajax({
    'url': SOA_URL +'simpeg/staffBySatker?unit_id='+ satker, // diganti pakai url api yang ditentukan
    'type':'get',
    // 'data':{'id_kasus':id},
    'success' : function(data){
      $html = "";
      // $('.selectPanitia').last().html("");
      $html  += "<option value=''>-- Pilih Panitia --</option>";
      if(data.data){

        $.each(data.data.pegawai, function(key, value) {
          $html  +=  '<option value="'+value.nama+'">'+value.nama+'</option>';
        });
        if(a == 'last'){
          $('.selectPanitia').last().html('').append($html);
        }else{
          $('.selectPanitia').html('').append($html);
        }
      }else{

      }
    },
    'error':function(e){
      console.log('error '+JSON.stringify(e));
      $('.selectPanitia').html("");
      $('.selectPanitia').html("<option value=''>-- Pilih Panitia --</option>");
    }
  });
});

function execSatker(ev,e){
  $('.selectSatker').trigger('change',['last']);
}

function execSatkerPanitia(ev,e){
  $('.selectSatkerPntia').trigger('change',['last']);
}

function submit_temuanPTL(ev,e){

}

function getKabupaten(e){
  $val = $(e).val();
  console.log($val);
  $_token = $('input[name="_token"]').val();
  if($val){
    $.ajax({
      'url': '/pilih_kabupaten', // diganti pakai url api yang ditentukan
      'type':'GET',
      'headers': {'X-CSRF-TOKEN': $_token},
      'data' : {'id': $val},
      'beforeSend': function(){
       $('.i.ajax-spinner').show();
      },
      'complete': function(){
        // $('.modal-footer-loading').addClass('hidden');
        $('.i.ajax-spinner').hide();

      },
      'success' : function(data){
        $html = "";
        if(data.status == 'success'){
          for($i = 0 ; $i < data.data.length; $i++){
            $d = data.data;
            $html+= '<option value="'+$d[$i].id_wilayah+'">'+$d[$i].nm_wilayah+'</option>';
          }
        }else{
          $html = "";
        }
        $('.nama_kabupaten option').remove();
        $('.nama_kabupaten').append($html);
      },
      'error':function(e){
        console.log('error '+JSON.stringify(e));
      }
    });
  }else{
    $html = '<option value="">-- Pilih Kabupaten --</option>';
    $('.nama_kabupaten').remove();
    $('.nama_kabupaten').append($html);
  }
}


function keterlibatanJaringan(param){
  $jaringan = $('.nama_jaringan_terkait');
  $item = localStorage.getItem("nama_jaringan");
  $temp =$jaringan.val() ;
  console.log($item);
  if( ($item == "") && ($temp != $item)){
    localStorage.setItem("nama_jaringan", $temp);
  }
  if(param == true){
    $jaringan.attr('readonly',false);

    $jaringan.val($item) ;
  }else{
    $jaringan.attr('readonly',true);
    $jaringan.val('') ;
  }
}
function open_modalEditPeserta(ev,e,$uri ="",$id=""){
   ev.preventDefault();
   $id = $(e).data('target');

   if($uri){
    $uri = $uri;
   }else{
    $uri = '/get_detail_tersangka';
   }
   $_token = $('input[name="_token"]').val();
   $.ajax({
      'url': $uri, // diganti pakai url api yang ditentukan
      'type':'GET',
      'data' : {'id':$id},
      'headers': {'X-CSRF-TOKEN': $_token},
      'complete': function(){
         $('.loading-content').hide('fast');
      },
      'beforeSend': function(){
         $('.loading-content').show('fast');
      },
      'success' : function(data){
          console.log(data.data);
          if(data.status == 'success'){
            if(data.data ){
              $d = data.data;

              $.each($d, function(i,val){
                console.log(val);
                $('.'+i).val(val);
              });
              $('#detailTersangka').modal('show');
            }else{
              $('.loading-content').html('<div class="alert alert-warning">Data Gagal Ditampilkan.</div>');
            }

          }else{
            $('.loading-content').html('<div class="alert alert-warning">Data Gagal Ditampilkan.</div>');
          }
      },
    'error':function(e){
      console.log('error '+JSON.stringify(e));
    }
  });

}

function show_text(e){
  $checked = $(e).prop('checked');
  if($checked){
    $('.layanan_pendekatan_lain').removeClass('hide').attr('disabled',false);

  }else{
    $('.layanan_pendekatan_lain').addClass('hide').attr('disabled',true).val('');
  }
}

function jenisRujukan(e){
  $(e).parents('.group').find('.nomor').removeClass('hide').end().siblings().find('.nomor').addClass('hide').end().find('.nomor input').attr('disabled',true);
  $(e).parents('.group').find('input').attr('disabled',false);
}

function getBagianPelaksana(e,id){
  $id= "";
  if(id){
    $id = id;
  }else{
      $id = $(e).find('option:selected').attr('data-target');
  }
  $pelaksana = $('select.pelaksana');
  $options = "";
  $.ajax({
    'url': BASE_URL+ '/api/pelaksana_bagian?type=pelaksana&id_lookup_parent='+$id, // diganti pakai url api yang ditentukan
    'type':'get',
    'complete':function(){
      getJenisKegiatan($id);
    },
    'success' : function(data){
     if((data.code == 200) && (data.status != "error") ){
      $d = data.data;
      if($d.length){
        for($i = 0  ; $i < $d.length ; $i++){
          $slug = $d[$i].slug;
          $slug = $slug.trim();
          $id_settama_lookup = $d[$i].id_settama_lookup;
          $lookup_name = $d[$i].lookup_name;
          $options += '<option value="'+$id_settama_lookup+'" data-target="'+$id_settama_lookup+'">'+$lookup_name+'</option>';
        }
      }
     }else{
        $options = '<option value=""> -- Pilih Bagian --</option>';
     }
     $pelaksana.html('').append($options);
    },
    'error':function(e){
      console.log('error '+JSON.stringify(e));
    }
  });
}
function getJenisKegiatan($id){
  $pelaksana = $('select.jenis_kegiatan');
  $options = "";
  $.ajax({
    'url': BASE_URL+ '/api/settama_jenis_kegiatan/'+$id, // diganti pakai url api yang ditentukan
    'type':'get',
    'success' : function(data){
      console.log(data);
     if((data.code == 200) && (data.status != "error") ){
      $d = data.data;
      if($d.length){
        for($i = 0  ; $i < $d.length ; $i++){
          $slug = $d[$i].slug;
          $options += '<option value="'+$d[$i].id_lookup+'" data-target="'+$d[$i].id_lookup+'">'+$d[$i].lookup_name+'</option>';
        }
      }
     }else{
        $options = '<option value=""> -- Pilih Bagian --</option>';
     }
     $pelaksana.html('').append($options);
    },
    'error':function(e){
      console.log('error '+JSON.stringify(e));
    }
  });
}


function validateForm(ev,e){
  ev.preventDefault();
    var fileInput = document.getElementById('file-type');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.pdf)$/i;
    $valid = false;
    if(filePath){
      if(!allowedExtensions.exec(filePath)){
          $error = 'Upload File dalam Format PDF.';
          $('span.error').html($error);
          $valid = false;
      }else{
            $('span.error').html('');
          $valid = true;
      }


    }else{
      $valid = true;
    }
    if($valid == true){
        // alert('submit');
        $('#multipart-form')[0].submit();
    }else{
      return false;
    }
}

function jenis_jaringan(e){
  $this = $(e);
  $id = $this.data('id');
  $hide = $this.data('hide');
    $('#'+$id).show();
    $('#'+$hide).hide();
}

// function jenis_jaringan(e){
//   $this = $(e);
//   $id = $this.data('id');
//   $hide = $this.data('hide');
//   if($this.attr('checked') == true){
//     $('#'+$id).show();
//   }else{
//     $('#'+$id).hide();
//   }
// }


function openModalBidangAudit(e, ev){
  $this = $(e);
  $tipe = $this.parents('.panel-parent').find('input[name="tipe"]').val();
  console.log( 'tipe '+$tipe);
  $('#modal_bidang_audit .tipe').val($tipe);
  $('#modal_bidang_audit').modal('show');
}



function editRekomendasi(ev,e,id_param){
  $this = $(e);
  $html = "";
  // console.log($this.data('id'));
  $_token = $('input[name="_token"]').val();
  $tipe = $this.parents('.tab-wrap').find('input[name="tipe"]').val();
  // console.log($tipe);
  if(id_param){
    $id = id_param;
  }else{
    $id = $this.data('id');
  }
  console.log('ajax '+$id);
  $('.modal').find('input[name="tipe"]').val($tipe);
  $.ajax({
    'url': "/ajax_irtama_rekomendasi/"+$id, // diganti pakai url api yang ditentukan
    'type':'get',
    'headers': {'X-CSRF-TOKEN': $_token},
    'data':{'id':$id,'tipe':$tipe},
    'success' : function(data){
      console.log(data);
      if(data.status != 'error' && (data.code == 200 )){
        if(data.data){
          inputs =['nilai_tindak_lanjut','status','id_rekomendasi']
          $d = data.data;
          $.each($d, function(i,val){
            // console.log(i);
            if($.inArray(i,inputs) == -1) {
              $('.'+i).html(val);
            }else if($('select[name="'+i+'"]').is('select') == true ){
              $('select[name="'+i+'"] option[value="'+val+'"]').prop('selected','selected');
              $('select[name="'+i+'"]').select2();
            }
            else{
              $('.'+i).val(val);
            }
          });
          $bukti =$d.bukti;
          $bukti = JSON.parse($bukti);

          // removpeater
          $html += '<div data-repeater-list="meta_bukti">';
          if($bukti){
            console.log($bukti.length);
            if($bukti.length > 0 ){
              for($i = 0 ; $i < $bukti.length ; $i++){
                $b = $bukti[$i];
                if($b){
                  $filename = $b;
                }else{
                  $filename = "";
                }

                $html += '<div data-repeater-item="" class="mt-repeater-item">';
                $html += '<div class="row mt-repeater-row">';
                $html += '<div class="col-md-10 col-sm-10 col-xs-12">';
                $html += '<div class="fileinput fileinput-new" data-provides="fileinput">';
                $html += '<div class="input-group input-large">';
                $html += '<div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">';
                $html += '<i class="fa fa-file fileinput-exists"></i>';
                $html += '<span class="fileinput-filename"> </span>';
                $html += '</div>';
                $html += '<span class="input-group-addon btn default btn-file">';
                $html += '<span class="fileinput-new m-b-0" > Pilih Berkas </span>';
                $html += '<span class="fileinput-exists m-b-0"> Ganti </span>';
                $html += '<input type="file" name="meta_bukti[$i][bukti]" value="'+$filename+'" onChange="pilih_berkas(event,this)"> </span>';
                $html += '<span>';
                $html += '<input type="hidden" name="meta_bukti[$i][filename]" value="'+$filename+'"> </span>';
                $html += '<a href="#" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>';
                $html += '</div>';
                $html += '</div>';
                $html += '</div>';
                $html += '<div class="col-md-1 col-sm-1 col-xs-12">';
                $html += '<div class="row">';
                $html += '<a href="javascript:;" data-repeater-delete="" class="btn btn-danger meta-repeater-delete">';
                $html += '<i class="fa fa-close"></i>';
                $html += '</a>';
                $html += '</div>';
                $html += '</div>';
                $html += '<div class="col-md-12 col-xs-12 col-sm-12 file_link">'
                $html += '<span class="help-block white">';
                $html += 'Lihat File : <a  target="_blank" class="link_file" href="/'+$d.file_path+'/'+$filename+'">'+$filename+'</a>';
                $html += '</span>';
                $html += '</div>';
                $html += '</div>';
                $html += '</div>';
              }
              $('.meta-repeater').find('div').remove().end().prepend($html);
            }
          }
          $html += '</div>';
        }else{
          // jika data kosong
        }
      }else{
        // jika error
      }
      $('.meta-repeater').repeater({
          initEmpty: true,
          show: function () {
              $(this).slideDown();
          },
          hide: function (deleteElement) {
              if(confirm('Are you sure you want to delete this element?')) {
                  $(this).slideUp(deleteElement);
              }
          },
      });
      $('#modal_edit_ptl').modal('show');
    },
    'error':function(e){
      console.log('error '+JSON.stringify(e));
    }
  });
}

function cleanRepeater(){
  setTimeout(function() {
    $('.file_link:last').remove();
  },0);

}

function pilih_berkas(ev,e){
  $(e).parents('.mt-repeater-row').find(".file_link").remove();
  // $val = $(e).prop("files")[0]['name'];
  // $val = $val.split('\\');

  // console.log($val);

}

function submitModalRekomendasi(ev,e){
  ev.preventDefault();
  $id = $('.id_rekomendasi').val();
  console.log($id);
  $action = $(e).attr('action');
  console.log('action '+$action);
  $data = $('form#form_modal_edit_ptl').serialize();
  $.ajax({
    'url': $action, // diganti pakai url api yang ditentukan
    'type':'post',
    'headers': {'X-CSRF-TOKEN': $_token},
    'data':{'id':$id,'tipe':$tipe,'data':$data},
    'beforeSend': function(){
      $('.btn-cancel, .btn-confirm').attr('disabled','disabled');
    },
    'success' : function(data){
      console.log(data);
      $messages = "";
      $('.btn-cancel, .btn-confirm').attr('disabled',false);
      if(data.status == 'success'){
        $messages = '<div class="alert-messages alert-success text-left">'+data.message+'</div>'
      }else{
        $messages = '<div class="alert-messages alert-error  text-left">'+data.message+'</div>'
      }
      // editRekomendasi("","",$id);
      $('.modal .messages').html($messages).show('slow');
      setTimeout(function() {
        $('.modal .messages').html($messages).hide('slow');
      }, 1500);
    },
    'error':function(e){
      console.log('error '+JSON.stringify(e));
    }
  });
}

function changeDataPtl(e){
  $this = $(e);
  if($this.is(':checked')){
    $this.val('Y');
  }else{
    $this.val('N');
  }
}

var id_deleted_rekomendasi=[];
function delete_rekomendasi(e){
    $this  = $(e);
    id_deleted_rekomendasi.push($this.data('target'));
    $('.id_deleted').val(id_deleted_rekomendasi);
    console.log($this.data('target'));
}
function editLhaBidang(ev,e){
  $id = $(e).data('id');
  console.log('id '+$id);
   $_token = $('input[name="_token"]').val();
  $uri = '/get_detail_irtama_bidang';
  $.ajax({
    'url': $uri, // diganti pakai url api yang ditentukan
    'type':'GET',
    'headers': {'X-CSRF-TOKEN': $_token},
    'data' : {id:$id},
    'complete': function(){
       // $('.loading-content').hide('fast');
       // $('#modal_edit_form').show('slow');
    },
    'success' : function(data){
      console.log(data);
      obj = JSON.parse(data);
      console.log(obj.bidang);
      console.log(obj.rekomendasi);
      console.log('file_path '+obj.file_path);
       // if(data.status == 'success'){
          $d = obj.bidang;
          $.each($d, function(i,val){
             $type = $('.'+i).attr('type');
             if($type== "radio" || $type== "checkbox"){
                $('.'+i+'[value="'+val+'"]').prop('checked',true);
             }else if($('.'+i).is('select')){
                $('select option[value="'+val+'"]').attr('selected','selected');
                $('.'+i).select2();
                console.log('select option[value="'+val+'"]');
             }else {
                $('.'+i).val(val);
             }
          });
          console.log(obj.rekomendasi.length);
          $r= obj.rekomendasi;
          $html = "";
          $id_rekomendasi=[];
          if($r.length > 0 ){
            for($j = 0 ; $j < $r.length ; $j++){
              $v = $r[$j];
              console.log($v);

              $html += '<div data-repeater-item="" class="mt-repeater-item">';
              $html += '<div class="row mt-repeater-row">';
              $html += '<div class="col-md-5 col-sm-5 col-xs-12">';
              $html += '<label class="control-label">Judul Rekomendasi</label>';
              $html += '<input name="meta_rekomendasi['+$j+'][judul_rekomendasi]" type="text" class="form-control judul_rekomendasi" value="'+$v.judul_rekomendasi+'">';
              $html += '</div>';
              $html += '<div class="col-md-6 col-sm-6 col-xs-12">';
              $html += '<label class="control-label">Kode Rekomendasi</label>';
              $html += '<select name="meta_rekomendasi['+$j+'][kode_rekomendasi]" id="kode_rekomendasi['+$j+']" class="form-control" tabindex="-1" value="'+$v.kode_rekomendasi+'">';
              $.ajax({
                'async': false,
                'url': '/api/getrekomendasi', // diganti pakai url api yang ditentukan
                'type':'GET',
                'headers': {'X-CSRF-TOKEN': $_token},
                'success' : function(data2){
                    $.each(data2.data, function(key, value) {
                      $html  +=  '<option value="'+ value.kd_rekomendasi +'" ';
                      if(value.kd_rekomendasi == $v.kode_rekomendasi) {
                          $html  +=  'selected="selected" '
                      }
                      $html  +=  '>'+ value.kd_rekomendasi +' - '+ value.nama_rekomendasi +'</option>';
                    });
                  }
                });
              $html += '</select>';
              $html += '</div>';
              $html += '<div class="col-md-5 col-sm-5 col-xs-12">';
              $html += '<label class="control-label">Nilai Rekomendasi</label>';
              $html += '<input name="meta_rekomendasi['+$j+'][nilai_rekomendasi]" type="text" class="form-control nilai_rekomendasi" value="'+$v.nilai_rekomendasi+'" onKeydown="numeric_only(event,this)">';
              $html += '</div>';
              $html += '<div class="col-md-6 col-sm-6 col-xs-12">';
              $html += '<label class="control-label">Audit Penanggung jawab</label>';
              $html += '<input name="meta_rekomendasi['+$j+'][penanggung_jawab]" type="text" class="form-control penanggung_jawab" value="'+$v.penanggung_jawab+'">';
              $html += '</div>';
              $html += '<div class="col-md-1 col-sm-1 col-xs-12">';
              $html += '<input type="hidden" name="meta_rekomendasi['+$j+'][id_rekomendasi]" value="'+$v.id_rekomendasi+'" />';
              $html += '<button href="#" data-repeater-delete="" data-target="'+$v.id_rekomendasi+'" class="btn btn-danger mt-repeater-delete" onClick="delete_rekomendasi(this)">';
              $html += '<i class="fa fa-close"></i>';
              $html += '</button>';
              $html += '</div>';
              $html += '</div>';
              $html += '</div>';

            }
            $('.penanggung_jawab').html($html);
          }else{
              $html += '<div data-repeater-item="" class="mt-repeater-item">';
              $html += '<div class="row mt-repeater-row">';
              $html += '<div class="col-md-5 col-sm-5 col-xs-12">';
              $html += '<label class="control-label">Judul Rekomendasi</label>';
              $html += '<input name="meta_rekomendasi[0][judul_rekomendasi]" type="text" class="form-control judul_rekomendasi" value="">';
              $html += '</div>';
              $html += '<div class="col-md-6 col-sm-6 col-xs-12">';
              $html += '<label class="control-label">Kode Rekomendasi</label>';
              $html += '<input name="meta_rekomendasi[0][kode_rekomendasi]" type="text" class="form-control kode_rekomendasi" value="">';
              $html += '</div>';
              $html += '<div class="col-md-5 col-sm-5 col-xs-12">';
              $html += '<label class="control-label">Nilai Rekomendasi</label>';
              $html += '<input name="meta_rekomendasi[0][nilai_rekomendasi]" type="text" class="form-control nilai_rekomendasi" value="" onKeydown="numeric_only(event,this)">';
              $html += '</div>';
              $html += '<div class="col-md-6 col-sm-6 col-xs-12">';
              $html += '<label class="control-label">Audit Penanggung jawab</label>';
              $html += '<input name="meta_rekomendasi[0][penanggung_jawab]" type="text" class="form-control penanggung_jawab" value="">';
              $html += '</div>';
              $html += '<div class="col-md-1 col-sm-1 col-xs-12">';
              $html += '<input type="hidden" name="meta_rekomendasi[0][id_rekomendasi]" value="" />';
              $html += '<button href="#" data-repeater-delete="" data-target="" class="btn btn-danger mt-repeater-delete" onClick="delete_rekomendasi(this)">';
              $html += '<i class="fa fa-close"></i>';
              $html += '</button>';
              $html += '</div>';
              $html += '</div>';
              $html += '</div>';
              $('.penanggung_jawab').html($html);
          }

          if(obj.file_path){
            $('.span-link').removeClass('hide');
            $('.link_file').attr('href',obj.file_path).html($d.bukti_temuan);
          }
          $('#modal_edit_bidang_audit').modal('show');
           $('.mt-repeater').repeater();
       // }else{
       //    $('.loading-content').html('<div class="alert alert-warning">Data Gagal Ditampilkan.</div>');
       // }
      },
    'error':function(e){
    // console.log('error '+JSON.stringify(e));
     }
  });
}

function deleteLhaBidang(ev,e){
  $form = $('#deleteBidang');
  console.log($(e).data('id'));
  $form.find('.target_id').val($(e).data('id'));
  $form.modal('show');
}

function showPeriode(e){
  $val = $(e).val();
  console.log( 'val '+$val);
  $('input[name="kata_kunci"]').val('');
  if($val){
    $('input[name="kata_kunci"]').attr('disabled',false);
  }else{
    $('input[name="kata_kunci"]').attr('disabled',true);

  }
  if($val == 'periode' ){
    $('.tgl-periode').removeClass('hide').slideDown('slow');
    $('.keyword').slideUp('slow').addClass('hide').find('input').val('');
    $('.kelengkapan').slideUp('slow').addClass('hide').find('input').val('');
    $('.tgl-pelaksanaan').slideUp('slow').addClass('hide').find('input').val('');
    $('.dipa_anggaran').slideUp('slow').addClass('hide').find('input').val('');
    $('select[name="kelengkapan"]').attr('disabled',true);
    $('select[name="dipa_anggaran"]').attr('disabled',true);
    $('.jenis_kegiatan').addClass('hide').attr('disabled',true).find('select').slideUp('slow');
  }else if($val == 'tgl_pelaksanaan'){
    $('.tgl-periode').slideUp('slow').addClass('hide').find('input').val('');
    $('.tgl-pelaksanaan').removeClass('hide').slideDown('slow');
    $('.kelengkapan').slideUp('slow').addClass('hide').find('input').val('');
    $('.keyword').slideUp('slow').addClass('hide').find('input').val('');
    $('.dipa_anggaran').slideUp('slow').addClass('hide').find('input').val('');
    $('select[name="kelengkapan"]').attr('disabled',true);
    $('select[name="dipa_anggaran"]').attr('disabled',true);
    $('.jenis_kegiatan').addClass('hide').attr('disabled',true).find('select').slideUp('slow');
  }else if($val == 'dipa_anggaran'){
    $('.dipa_anggaran').removeClass('hide').slideDown('slow');
    $('.kelengkapan').slideUp('slow').addClass('hide').find('input').val('');
    $('.tgl-periode').slideUp('slow').addClass('hide').find('input').val('');
    $('.keyword').slideUp('slow').addClass('hide').find('input').val('');
    $('.tgl-pelaksanaan').slideUp('slow').addClass('hide').find('input').val('');
    $('select[name="kelengkapan"]').attr('disabled',true);
    $('select[name="dipa_anggaran"]').attr('disabled',false);
    $('.jenis_kegiatan').addClass('hide').attr('disabled',true).find('select').slideUp('slow');
  }else if($val == 'kelengkapan'){
    $('.kelengkapan').removeClass('hide').slideDown('slow');
    $('.dipa_anggaran').slideUp('slow').addClass('hide').find('input').val('');
    $('.tgl-periode').slideUp('slow').addClass('hide').find('input').val('');
    $('.keyword').slideUp('slow').addClass('hide').find('input').val('');
    $('.tgl-pelaksanaan').slideUp('slow').addClass('hide').find('input').val('');
    $('select[name="dipa_anggaran"]').attr('disabled',true);
    $('select[name="kelengkapan"]').attr('disabled',false);
    $('.jenis_kegiatan').addClass('hide').attr('disabled',true).find('select').slideUp('slow');
  }else if($val == 'jenis_kegiatan'){
    $('.jenis_kegiatan').removeClass('hide').slideDown('slow').find('select').attr('disabled',false);
    $('.kelengkapan').slideUp('slow').addClass('hide').find('input').val('');
    $('.dipa_anggaran').slideUp('slow').addClass('hide').find('input').val('');
    $('.tgl-periode').slideUp('slow').addClass('hide').find('input').val('');
    $('.keyword').slideUp('slow').addClass('hide').find('input').val('');
    $('.tgl-pelaksanaan').slideUp('slow').addClass('hide').find('input').val('');
    $('select[name="dipa_anggaran"]').attr('disabled',true);
  }else{
    $('.keyword').removeClass('hide').slideDown('slow');
    $('.tgl-periode').slideUp('slow').addClass('hide').find('input').val('');
    $('.tgl-pelaksanaan').slideUp('slow').addClass('hide').find('input').val('');
    $('.dipa_anggaran').slideUp('slow').addClass('hide').find('input').val('');
    $('.kelengkapan').slideUp('slow').addClass('hide').find('input').val('');
    $('select[name="kelengkapan"]').attr('disabled',true);
    $('select[name="dipa_anggaran"]').attr('disabled',true);
    $('.jenis_kegiatan').addClass('hide').attr('disabled',true).find('select').slideUp('slow');
  }
}


function formFilter(e){
  $filter = $('.filter');
  $this = $(e);
  $val = $this.val();
  console.log($val);
  $wrap = $('.div-wrap');
  if($val){
    $class  = $('.div-wrap'+'.'+$val);
    if($wrap.hasClass($val)){
      $class.removeClass('hide').slideDown('slow');
      $wrap.not('.'+$val).slideUp('slow').addClass('hide');
      $('input[name="kata_kunci"]').val('').attr('disabled',true);
    }else{
      $wrap.not('.keyword').slideUp('slow').addClass('hide');
      $('.keyword').removeClass('hide').slideDown('slow');
      $('input[name="kata_kunci"]').attr('disabled',false).val('');
      if($val== 'luas'){
          $('input[name="kata_kunci"]').attr('onKeydown','numeric_only(event,this)');
      }else{
          $('input[name="kata_kunci"]').removeAttr('onKeydown');
      }
    }
  }else{
    $wrap.not('.keyword').slideUp('slow').addClass('hide');
    $('.keyword').removeClass('hide').slideDown('slow');
    $('input[name="kata_kunci"]').val('').attr('disabled',true);
  }

}


/** ini jangan ilang lagi **/
function dropdownSatker($id,a){


  $.ajax({
    'url': URL_SIMPEG+'simpeg/staffBySatker?unit_id='+ $id, // diganti pakai url api yang ditentukan
    'type':'get',
    // 'data':{'id_kasus':id},
    'success' : function(data){
      console.log(data);
      $html = "";
      // $('.selectPenyidik').last().html("");
      $html  += "<option value=''>-- Pilih Penyidik --</option>";
      if(data.data){

        $.each(data.data.pegawai, function(key, value) {
          $html  +=  '<option value="'+value.nama+'">'+value.nama+'</option>';
        });
        console.log($html);
        if(a == 'last'){
          $('.irtama_satker').last().html($html);
        }else{
          $('.irtama_satker').html($html);
        }
        $('.select2').select2();
      }
    },
    'error':function(e){
      console.log('error '+JSON.stringify(e));
      $('.selectSatker').html("");
      $('.selectSatker').html("<option value=''>-- Pilih Penyidik --</option>");
    }
  });
}

$('.onCreateRepeater').click(function(event){
  event.preventDefault();
  setTimeout(function(){$('.metaSatker').select2();},500);
  length = $('.metaSatker').length;

  if(length == 4){
   $(this).attr('disabled','disabled');
  }

});

$('.onCreatePendamping').click(function(event){
  event.preventDefault();
  setTimeout(function(){$('.metaSatker').select2();},500);

});

$('.onCreateDataSidang').click(function(event){
  event.preventDefault();
  setTimeout(function(){$(".metaDate").last().datetimepicker({
    format: 'DD/MM/YYYY',
    maxDate : new Date(),
  });},500);

});


  $('.meta-repeater').repeater({
      show: function () {
          $(this).slideDown();
      },
      hide: function (deleteElement) {
          // event.preventDefault();
          if(confirm('Are you sure you want to delete this element?')) {
              $(this).slideUp(deleteElement);
          }
          enableButton();
      },

      ready: function (setIndexes) {
        $('.metaSatker').select2();

      }

  });

function loadIdentitasPendamping(element)
{
  var closest = $('select[name="meta_didampingi[list_nip]"]');

  var id = $(element).find(":selected").val();

  $.ajax({
    'url': SOA_URL +'simpeg/staffBySatker?unit_id='+ id,
    'type':'get',
    'success' : function(data){

        $html = "";

        $html  += "<option value=''>-- Pilih Identitas --</option>";
        if(data.data){

          $.each(data.data.pegawai, function(key, value) {
            $html  +=  '<option value="'+value.nip+'">' + value.nip + ' - '+value.nama+'</option>';
          });

          closest.html($html);

          closest.select2();
        }

      },
      'error':function(e){
        console.log('error '+JSON.stringify(e));
      }
    });
}

function loadIdentitasPendampingList(element)
{
  var index = parseInt($(element).attr('name').substring(16, 17));

  console.log(index);

  var closest = $('select[name="meta_pendamping[' + index + '][list_nip]"]');

  var id = $(element).find(":selected").val();

  $.ajax({
    'url': SOA_URL +'simpeg/staffBySatker?unit_id='+ id,
    'type':'get',
    'success' : function(data){

        $html = "";

        $html  += "<option value=''>-- Pilih Identitas --</option>";
        if(data.data){
          console.log(data.data);
          $.each(data.data.pegawai, function(key, value) {
            $html  +=  '<option value="'+value.nip+'">' + value.nip + ' - '+value.nama+'</option>';
          });

          closest.html($html);

          closest.select2();
        }

      },
      'error':function(e){
        console.log('error '+JSON.stringify(e));
      }
    });
}

function loadIdentitasPelaksanaList(element)
{
  var index = parseInt($(element).attr('name').substring(15, 16));

  console.log(index);

  var closest = $('select[name="meta_pelaksana[' + index + '][list_nip]"]');

  var id = $(element).find(":selected").val();

  $.ajax({
    'url': SOA_URL +'simpeg/staffBySatker?unit_id='+ id,
    'type':'get',
    'success' : function(data){

        $html = "";

        $html  += "<option value=''>-- Pilih Identitas --</option>";
        if(data.data){
          console.log(data.data);
          $.each(data.data.pegawai, function(key, value) {
            $html  +=  '<option value="'+value.nip+'">' + value.nip + ' - '+value.nama+'</option>';
          });

          closest.html($html);

          closest.select2();
        }

      },
      'error':function(e){
        console.log('error '+JSON.stringify(e));
      }
    });
}

 // function getMetaSatker(e){
 //  $val  = $(e).val();
 //  $nip = $(e).find(':selected').data('nip');
 //  anggota_collection.push({'nip':$nip,'nama':$val});
 //  stringify = JSON.stringify(anggota_collection);
 //  collection = stringify.toString();
 //  $('.anggota_collection').val(collection);
 // }
 function selectDataPtl(e){
  $this = $(e);
  if($this.is(':checked')){
    $this.val('Y').prop('checked',true);
  }else{
    $this.val("N").prop('checked',false);

  }

 }
 function enableButton(e){
  length = $('.metaSatker').length;
  if(length <= 5){
   $('.onCreateRepeater').attr('disabled',false);
  }
}
 function satkerPengendali(e,className){
  pengendali= [];
  $val = $(e).val();
  $nip = $(e).find(':selected').data('nip');
  pengendali.push({'nip':$nip,'nama':$val});
  stringify = JSON.stringify(pengendali);
  collection = stringify.toString();
  $('.'+className).val(collection);
 }



function submit_update_bidang(ev,e){
  ev.preventDefault();
  $('.btn-submit').attr('disabled','disabled');
  $action = $(e).attr('action');
  console.log($action);
  $data = $(e).serialize();
  $_token = $('input[name="_token"]').val();
  $.ajax({
   'url': $action, // diganti pakai url api yang ditentukan
   'type':'POST',
   'headers': {'X-CSRF-TOKEN': $_token},
   'data' : {'data':$data},
   'beforeSend': function(){
      $(e).html('Menyimpan ....');
   },
   'complete': function(){
   },
   'success': function(data){
        console.log(data);
   },
   'error' : function(){

   }
  });

}

/** ini jangan ilang lagi **/
var anggota_collection= [];
var $arr_satker =new Object();
function irtamaAudit(e,ev){
  ev.preventDefault();
  $this = $(e);
  length = $('.metaSatker').length;
  for($i = 0 ; $i < length ; $i++){
    $meta = $('.metaSatker').eq($i);
    $val  = $meta.val();
    $nip = $meta.find(':selected').data('nip');
    anggota_collection.push({'nip':$nip,'nama':$val});
  }
  stringify = JSON.stringify(anggota_collection);
  collection = stringify.toString();
  $('.anggota_collection').val(collection);
  $nama_satker = $('#nama_satker');
  $satker = $nama_satker.find('option:selected').val();
  $id_satker = $nama_satker.find('option:selected').data('id');
  $arr_satker.id_satker = $id_satker;
  $arr_satker.satker = $satker;
  $('.list_satker').val(JSON.stringify($arr_satker));
  $('form#frm_add')[0].submit();
}


function clearInput(e,className){
  $class= $('.'+className);
  $class.find('input').val('');
}


function satker_code(e){
  $this = $(e);
  $nama = $this.val();
  $id = $this.find('option:selected').data('id');
  $arr_satker.nama = $nama;
  $arr_satker.id = $id;
  $('.list_satker').val(JSON.stringify($arr_satker));
}


function submit_modalAlihProfesi(ev,e,idmodal="",type=""){
  if(idmodal){
    $modal = idmodal;
  }else{
    $modal = 'modal_form';
  }
  ev.preventDefault();
  $(e).attr('disabled','disabled');
  $action = $('form#'+$modal).attr('action');
  $data = $('form#'+$modal).serialize();
  $_token = $('input[name="_token"]').val();
  $parent_id = $('.parent_id').val();
  index = 1;
  console.log('parent_id '+$parent_id);
  $.ajax({
   'url': $action, // diganti pakai url api yang ditentukan
   'type':'POST',
   'headers': {'X-CSRF-TOKEN': $_token},
   'data' : $data+'&parent_id='+$parent_id,
   'beforeSend': function(){
      $(e).html('Menyimpan ....');
   },
   'complete': function(){
      $(e).html('Kirim');
      $('.modal input[type="radio"]').prop('checked',false);
      $('.modal input[type="checkbox"]').prop('checked',false);
      $('.modal input[type="text"]').each(function(i){
         if($(this).attr('name') != '_token'){
            $(this).val('');
         }
      });
      $('input[type="email"]').each(function(i){
         $(this).val('');
      });

   },

   'success' : function(data){
      console.log(data);

      if(data.status == 'success'){
         location.reload();
      }else{
         $(e).attr('disabled',false);
         $('.alert-warning.alert-modal').show('slow',function(){
            setTimeout(function() {
                  $('.modal').modal('hide');
                  $('.alert').hide();
               // location.reload();
               }, 1000);
            });
            $(e).attr('disabled',false);

         }
      },
      'error':function(e){
         console.log('error '+JSON.stringify(e));
      }
   });
}

function kegiatanjenis(e) {
  $value = $(e).val();
  if ($value == 'pemasangan_jaringan' ) {
    $(".nota_dinas").removeClass("hide");
    $(".jenis_jaringan").addClass("hide");
  } else {
    $(".nota_dinas").addClass("hide");
    $(".jenis_jaringan").removeClass("hide");
  }
}

function kuota_jenis(e) {
  $value = $(e).val();
  if ($value == 'limited' ) {
    $(".limit_kuota").removeClass("hide");
  } else {
    $(".limit_kuota").addClass("hide");
  }
}

function cekjaringan(e) {
  $value = $(e).val();
  if ($value == 'baik' ) {
    $(".ket_baik_jaringan").removeClass("hide");
    $(".ket_tdk_jaringan").addClass("hide");
  } else {
    $(".ket_baik_jaringan").addClass("hide");
    $(".ket_tdk_jaringan").removeClass("hide");
  }
}

function cekip(e) {
  $value = $(e).val();
  if ($value == 'baik' ) {
    $(".ket_baik_ip").removeClass("hide");
    $(".ket_tdk_ip").addClass("hide");
  } else {
    $(".ket_baik_ip").addClass("hide");
    $(".ket_tdk_ip").removeClass("hide");
  }
}

function cekping(e) {
  $value = $(e).val();
  if ($value == 'baik' ) {
    $(".ket_baik_ping").removeClass("hide");
    $(".ket_tdk_ping").addClass("hide");
  } else {
    $(".ket_baik_ping").addClass("hide");
    $(".ket_tdk_ping").removeClass("hide");
  }
}

function cekswitch(e) {
  $value = $(e).val();
  if ($value == 'baik' ) {
    $(".ket_baik_switch").removeClass("hide");
    $(".ket_tdk_switch").addClass("hide");
  } else {
    $(".ket_baik_switch").addClass("hide");
    $(".ket_tdk_switch").removeClass("hide");
  }
}

function cekmanageable(e) {
  $value = $(e).val();
  if ($value == 'baik' ) {
    $(".ket_baik_manageable").removeClass("hide");
    $(".ket_tdk_manageable").addClass("hide");
  } else {
    $(".ket_baik_manageable").addClass("hide");
    $(".ket_tdk_manageable").removeClass("hide");
  }
}

function cekkabel(e) {
  $value = $(e).val();
  if ($value == 'baik' ) {
    $(".ket_baik_kabel").removeClass("hide");
    $(".ket_tdk_kabel").addClass("hide");
  } else {
    $(".ket_baik_kabel").addClass("hide");
    $(".ket_tdk_kabel").removeClass("hide");
  }
}

function cekwireless(e) {
  $value = $(e).val();
  if ($value == 'baik' ) {
    $(".ket_baik_wireless").removeClass("hide");
    $(".ket_tdk_wireless").addClass("hide");
  } else {
    $(".ket_baik_wireless").addClass("hide");
    $(".ket_tdk_wireless").removeClass("hide");
  }
}

function mSelect2(e){
  $this = $(e);
  setTimeout(function() {
    $this.parents('.mt-repeater').find('.mSelect2:last').select2();
  }, 500);
}

function validate_emailname(evt,ele) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var value = ele.value + key;
  var regex = /[^@]$/;
  if( !regex.test(value) || value.match(/.com/g)|| value.match(/.co.id/g)) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }else{
    theEvent.returnValue = true;
  }

}
function precentage_decimal(evt,ele) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var value = ele.value + key;
  var regex = /^\d+(,\d{0,2})?$/;
  // var regex = /^(0*100{1,1}\.?((?<=\.)0*)?%?$)|(^0*\d{0,2}\.?((?<=\.)\d{0,2})?%?)$/;
  if( !regex.test(value)) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}

function decimal_number(evt,ele) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var value = ele.value + key;
  var regex = /^\d+(\.\d{0,2})?$/;
  if( !regex.test(value)) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}



function getAnggaran($idpelaksana){
  idpelaksana = $idpelaksana;
  $.ajax({
    'url': BASE_URL+ '/api/getsatkerbyid/'+ idpelaksana, // diganti pakai url api yang ditentukan
    'type':'get',
    // 'data':{'id_kasus':id},
    'success' : function(data){

          var satkercode = data.data[0].satker_code;
          $('#kodeSatker').val(satkercode);
          $.ajax({
            'url': SOA_URL +'anggaran/bysatkerrs?kodesatker='+ satkercode, // diganti pakai url api yang ditentukan
            'type':'get',
            'dataType': 'xml',
            'beforeSend': function(){
              $('.selectAnggaran').parent('div').append(loader);
            },
            'complete': function(){
              $('.sk-fading-circle').remove();

            },
            'success' : function(xml){
              AnggaranXML = xml;
              $('.selectAnggaran').html("");
              $('.selectAnggaran').html("<option value=''>-- Pilih Anggaran --</option>");

              $(xml).find('MonevAnggaran').each(function(){
                  var sID    = $(this).find('DataID').text();
                  var sKode  = $(this).find('KodeAnggaran').text();
                  var sNama  = $(this).find('Sasaran').text();
                  var sTahun = $(this).find('Tahun').text();
                  $('.selectAnggaran')
                  .append($("<option></option>")
                  .attr("value",sID)
                  .text(sKode + ' - ' +sNama +' ('+ sTahun +')'));
              });

            },
            'error':function(e){
              console.log('error '+JSON.stringify(e));
                $('.selectAnggaran').html("");
                $('.selectAnggaran').html("<option value=''>-- Pilih Anggaran --</option>");
            }
          });

    },
    'error':function(e){
      console.log('error '+JSON.stringify(e));
      $('.selectAnggaran').html("");
      $('.selectAnggaran').html("<option value=''>-- Pilih Anggaran --</option>");
    }
  });
}

$(document).ready(function() {
  $('input[type=radio][name=jenis_media]').change(function() {
      var id = $(this).attr('data-id');
      var nama = $(this).attr('data-nama');
      $.ajax({
        'url': BASE_URL +'/api/getmedia/mediasosial/'+ id,
        'type':'get',
        // 'data':{'id_kasus':id},
        'success' : function(data){
          $html = '<label for="meta_media" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis '+nama+'</label>'+
                  '<div class="col-md-4 col-sm-4 col-xs-12">';
          if(data.data){

            $.each(data.data, function(key, value) {
              $html  +=  '<label class="mt-checkbox col-md-6 col-sm-6 col-xs-12">'+
                            '<input type="checkbox" value="'+value.value_media+'" name="meta_media[]">'+
                            '<span>'+value.nama_media+'</span>'+
                          '</label>';
            });
            $html  +=  '</div>';
            $('#jenisMediaOnline').html($html);

          }else{

          }
        },
        'error':function(e){
          console.log('error '+JSON.stringify(e));
          $('#jenisMediaOnline').html("");
          $('#jenisMediaOnline').html('<label for="meta_media" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis '+nama+'</label>'+
                  '<div class="col-md-4 col-sm-4 col-xs-12"></div>');
        }
      });
  });


    $('input[type=radio][name=kode_jenis_media]').change(function() {
        var id = $(this).attr('data-id');
        var nama = $(this).attr('data-nama');
        $.ajax({
          'url': BASE_URL +'/api/getmedia/mediaruang/'+ id,
          'type':'get',
          // 'data':{'id_kasus':id},
          'success' : function(data){
            $html = '<label for="meta_media" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis '+nama+'</label>'+
                    '<div class="col-md-6 col-sm-6 col-xs-12">';
            if(data.data){

              $.each(data.data, function(key, value) {
                $html  +=  '<label class="mt-radio col-md-4 col-sm-4 col-xs-12">'+
                              '<input type="checkbox" value="'+value.value_media+'" name="meta_media[]">'+
                              '<span>'+value.nama_media+'</span>'+
                            '</label>';
              });
              $html  +=  '</div>';
              $('#jenisMediaCetak').html($html);

            }else{

            }
          },
          'error':function(e){
            console.log('error '+JSON.stringify(e));
            $('#jenisMediaCetak').html("");
            $('#jenisMediaCetak').html('<label for="meta_media" class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis '+nama+'</label>'+
                    '<div class="col-md-6 col-sm-6 col-xs-12"></div>');
          }
        });
    });

    $('.selectPropinsimeta').trigger('change');
});

var $first_label = "baik";
function showHideLabel(e,val,classname,classhide){
  $keterangan = "";
  $val = val;
  $classname = classname;
  $classhide = classhide;
  $this = $(e);
  $target = $classname+'_'+$val;
  $target_hide = $classname+'_'+$classhide;
  // $keterangan = $('.'+$classname).find('input').val();
  $this.parents('.parent').find('input[type="text"]').val('');
  $('.'+$classname).removeClass('hide');
  $('.'+$target).removeClass('hide');
  $('.'+$target_hide ).addClass('hide');
}

function autocheck(e) {
  $this = $(e);
  $val = $this.val();
  $parent = $this.data('parent');
  $kode = $this.data('kode');
  $id = $this.attr('id');
  if ($this.is(':checked')) {
    $('#'+$kode+$parent).prop('checked', true);
    if ($parent != '0') {
      // autocheck('#'+$kode+$parent);
      $this = $('#'+$kode+$parent);
      $parent = $this.data('parent');
      $kode = $this.data('kode');
      $('#'+$kode+$parent).prop('checked', true);
    }
    $('input[data-parent="'+$val+'"][data-kode="'+$kode+'"]').prop('checked', true);
    $('input[data-parent="'+$val+'"][data-kode="'+$kode+'"]').each(function(){
      $kode = $(this).data('kode');
      $val = $(this).val();
      $('input[data-parent="'+$val+'"][data-kode="'+$kode+'"]').prop('checked', true);
    });
  } else {
    $('#'+$id).prop('checked', false);
  }

}

function save_password2(ev,e){
  ev.preventDefault();
  // $old_password = $('input[name="old_password"]').val();
  $password = $('input[name="password"]').val();
  $password_confirmation = $('input[name="password_confirmation"]').val();
  // $email = $('input[name="email"]').val();
  $user_id = $('input[name="user_id"]').val();
  $_token = $('input[name="_token"]').val();
  $token = $('input[name="token"]').val();
  $length = $password.length;
  $message = '';
  if($password == ""){
    $message = 'Kata sandi baru harus diisi.';
  }else if( $password_confirmation == ""){
    $message = 'Konfirmasi password harus diisi.';
  }else if($password != $password_confirmation){
    $message = 'Kata sandi yang Anda masukkan tidak sama.';
  }else if($length < 6){
    $message = 'Kata sandi minimal 6 karakter.';
  }else{
    $message = '';
  }
  if($message){
    $('.message-validation').html($message);
    $('.message-validation').show('slow');
    setTimeout(function() {
      $('.message-validation').hide('slow');
    }, 3000);
    alert($message);
    $('#modal_new_password2').modal('hide');
  }else{
    $.ajax({
      'url': BASE_URL + '/api/password/edit2', // diganti pakai url api yang ditentukan
      'type':'post',
      'headers': {'X-CSRF-TOKEN': $_token,'Authorization':'Bearer '+$token},
      'data':{'new_password':$password,'user_id':$user_id},
      'success' : function(data){
        if(data.code == 200){
          $('.message-validation').html(data.comment);
          $('.message-validation').show('slow');
          setTimeout(function() {
            $('.message-validation').hide('slow');
          }, 3000);
          alert(data.comment);
          $('#modal_new_password2').modal('hide');
        }else{console.log(data);
          $('.message-validation-success').html(data.comment);
          $('.message-validation-success').show('slow');
          setTimeout(function() {
            $('.message-validation-success').hide('slow');
            $('.btn_change_password').show('slow');
            $(e).parents('.change_password').hide('slow');
          }, 3000);
          alert(data.comment);
          $('#modal_new_password2').modal('hide');
        }
      },
      'error':function(e){
        console.log('error '+JSON.stringify(e));
      }
    });
  }
}
