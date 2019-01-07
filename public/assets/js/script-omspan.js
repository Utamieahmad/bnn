$('document').ready(function(){
	hideLoading();
	$('[data-toggle="tooltip"]').tooltip();
	
	$('#menu_toggle').click();
	showHide();
	
	$('.dt-omspan').on('click', '.detail_up', function () {
		showLoading();
		console.log(TOKEN);
		console.log($('#periode').val());
		console.log($(this).attr('kd_instansi'));
		if($('#periode').val() == '') {
			alert('Periode tidak ditemukan');
			return false;
		}
		if($(this).attr('kd_instansi') == '') {
			alert('Kode satker tidak ditemukan');
			return false;
		}
		
		$.ajax({
			type: "post",
			url: BASE_URL + '/omspan/getpengelolaanup',
			data: {
				_token: $('#_token').val(),
				kdSatker : $(this).attr('kd_instansi'),
				periode : $('#periode').val()
			},
			success: function(data) {
				hideLoading();
                console.log(data);
				if(data.code=='200') {
					$('.div_up').show();
					$('.div_rekap').hide();
					var row = "";
					var tepat = 0;
					var terlambat = 0;
					var no = 1;
					$('.dt-up tbody').html("");
					for (i = 0;i < data.data.length;i++) {
						var gu = data.data[i].outstanding - data.data[i].nilai_total
						if(gu != 0) {
							gu = data.data[i].nilai_total;
						}
						if(data.data[i].status == 'TEPAT WAKTU') {
							tepat++;
						}else if(data.data[i].status == 'TERLAMBAT') {
							terlambat++;
						}
						row += "<tr>"+
                            "<td class='border_btm'>"+ no++ +"</td>"+
                            "<td class='border_btm'>"+data.data[i].kdsatker+"</td>"+
                            "<td class='border_btm'>"+data.data[i].instansiName+"</td>"+
                            "<td class='border_btm'>"+data.data[i].sumber_dana+"</td>"+
                            "<td class='border_btm'>"+data.data[i].jenis+"</td>"+
                            "<td class='border_btm'>"+data.data[i].tanggal+"</td>"+
                            "<td class='border_btm'>"+data.data[i].tanggal_diff+"</td>"+
                            "<td class='border_btm'>"+convertToRupiah(data.data[i].nilai_total)+"</td>"+
                            "<td class='border_btm'>"+convertToRupiah(data.data[i].outstanding)+"</td>"+
                            "<td class='border_btm'>"+convertToRupiah(gu)+"</td>"+
                            "<td class='border_btm'>"+Math.round((gu/data.data[i].outstanding) * 100)+"</td>"+
                            "<td class='border_btm'>"+data.data[i].status+"</td></tr>";
					}
					$('.dt-up tbody').html(row);
					$('.jml_tepat').html(tepat);
					$('.jml_terlambat').html(terlambat);
					$('.total').html(tepat + terlambat);
					$('.total_persen').html(parseFloat(tepat / (tepat + terlambat) *100).toFixed(2));
				} else {
					$('.div_up').show();
					$('.div_rekap').hide();
					$('.dt-up tbody').html('<tr><td colspan="12" align="center">No Data Found</td></tr>');
					alert(data.status);
				}
			},
            error: function(e) {
				hideLoading();
				console.log('error '+JSON.stringify(e));
			}
		});
	});
	
	$('.dt-omspan').on('click', '.detail_dkon', function () {
		showLoading();
		if($('#periode').val() == '') {
			alert('Periode tidak ditemukan');
			return false;
		}
		if($(this).attr('kd_instansi') == '') {
			alert('Kode satker tidak ditemukan');
			return false;
		}
		
		$.ajax({
			type: "post",
			url: BASE_URL + '/omspan/getdatakontrak',
			data: {
				_token: $('#_token').val(),
				kdSatker : $(this).attr('kd_instansi'),
				periode : $('#periode').val()
			},
			success: function(data) {
				hideLoading();
				if(data.code=='200') {
					$('.div_kontrak').show();
					$('.div_rekap').hide();
					var row = "";
					var tepat = 0;
					var terlambat = 0;
					var k = 1;
					var no = 1;
					var total = 0;
					var persen = 0;
					$('.dt-kontrak tbody').html("");
					for (i = 0;i < parseInt($('#periode').val());i++) {
						for (j = 0;j < data.data.length;j++) {
							if(('0'+k).slice(-2) == data.data[j].periode) {
								if(data.data[j].status != '-') {
									if(data.data[j].status == 'TEPAT WAKTU') {									
										tepat++;
									} else {
										terlambat++;
									}
								}
							}
						}
						total = tepat + terlambat;
						if(total != 0) {
							persen = (tepat/total) *100;
							row += "<tr>"+
								"<td class='border_btm'>"+ no++ +"</td>"+
								"<td class='border_btm'>"+data.data[0].kdsatker+"</td>"+
								"<td class='border_btm'>"+data.data[0].instansiName+"</td>"+
								"<td class='border_btm'></td>"+
								"<td class='border_btm'>"+('0'+k).slice(-2)+"</td>"+
								"<td class='border_btm'>"+tepat+"</td>"+
								"<td class='border_btm'>"+terlambat+"</td>"+
								"<td class='border_btm'>"+convertToRupiah(total)+"</td>"+
								"<td class='border_btm'>"+parseFloat(persen).toFixed(2)+"</td>"+
								"<td class='border_btm'></td></tr>";
						}
						k++;
						persen = 0;
					}
					$('.dt-kontrak tbody').html(row);
				} else {
					$('.div_kontrak').show();
					$('.div_rekap').hide();
					$('.dt-kontrak tbody').html('<tr><td colspan="12" align="center">No Data Found</td></tr>');
					alert(data.status);
				}
			},
            error: function(e) {
				hideLoading();
				console.log('error '+JSON.stringify(e));
			}
		});
	});
	
	$('.dt-omspan').on('click', '.detail_revisi', function () {
		showLoading();
		if($('#periode').val() == '') {
			alert('Periode tidak ditemukan');
			return false;
		}
		if($(this).attr('kd_instansi') == '') {
			alert('Kode satker tidak ditemukan');
			return false;
		}
		$.ajax({
			type: "post",
			url: BASE_URL + '/omspan/getrevisi',
			data: {
				_token: $('#_token').val(),
				kdSatker : $(this).attr('kd_instansi'),
				periode : $('#periode').val()
			},
			success: function(data) {
				hideLoading();
                console.log(data);
				if(data.code=='200') {
					$('.div_revisi').show();
					$('.div_rekap').hide();
					var row = "";
					var rev = 0;
					$('.dt-revisi tbody').html("");
					for (i = 0;i < data.data.length;i++) {
						if(data.data[i].nilai != 100) {
							rev = 1;
						}
						row += "<tr>"+
                            "<td class='border_btm'>"+i+"</td>"+
                            "<td class='border_btm'>"+data.data[i].periode+"</td>"+
                            "<td class='border_btm'>"+data.data[i].kdsatker+"</td>"+
                            "<td class='border_btm'>"+data.data[i].total_revisi+"</td>"+
                            "<td class='border_btm'></td>"+
                            "<td class='border_btm'></td></tr>";
					}
					if(rev != 0) {						
						$('.dt-revisi tbody').html(row);
					} else {
						$('.dt-revisi tbody').html('<tr><td colspan="6" align="center">No Data Found</td></tr>');
					}
				} else {
					$('.div_revisi').show();
					$('.div_rekap').hide();
					$('.dt-revisi tbody').html('<tr><td colspan="6" align="center">No Data Found</td></tr>');
					alert(data.status);
				}
			},
            error: function(e) {
				hideLoading();
				console.log('error '+JSON.stringify(e));
			}
		});
	});
	
	$('.dt-omspan').on('click', '.detail_retur', function () {
		showLoading();
		if($('#periode').val() == '') {
			alert('Periode tidak ditemukan');
			return false;
		}
		if($(this).attr('kd_instansi') == '') {
			alert('Kode satker tidak ditemukan');
			return false;
		}
		$.ajax({
			type: "post",
			url: BASE_URL + '/omspan/getretur',
			data: {
				_token: $('#_token').val(),
				kdSatker : $(this).attr('kd_instansi'),
				periode : $('#periode').val()
			},
			success: function(data) {
				hideLoading();
                console.log(data);
				if(data.code=='200') {
					$('.div_retur').show();
					$('.div_rekap').hide();
					var row = "";
					var persen = 0;
					var no = 1;
					$('.dt-retur tbody').html("");
					for (i = 0;i < data.data.length;i++) {
						if(data.data[i].jml_sp2d != 0) {
							persen = (data.data[i].jml_retur / data.data[i].jml_sp2d) * 100;
						} else {
							persen = 0;
						}
						row += "<tr>"+
                            "<td class='border_btm'>"+ no++ +"</td>"+
                            "<td class='border_btm'>"+data.data[i].kdsatker+"</td>"+
                            "<td class='border_btm'>"+data.data[i].instansiName+"</td>"+
                            "<td class='border_btm'></td>"+
                            "<td class='border_btm'>"+data.data[i].periode+"</td>"+
                            "<td class='border_btm'>"+data.data[i].jml_retur+"</td>"+
                            "<td class='border_btm'>"+data.data[i].jml_sp2d+"</td>"+
                            "<td class='border_btm'>"+parseFloat(persen).toFixed(2)+"</td>"+
                            "<td class='border_btm'>"+parseFloat(100 - persen).toFixed(2)+"</td></tr>";
					}
					$('.dt-retur tbody').html(row);
				} else {
					$('.dt-retur tbody').html('<tr><td colspan="9" align="center">No Data Found</td></tr>');
					alert(data.status);
				}
			},
            error: function(e) {
				hideLoading();
				console.log('error '+JSON.stringify(e));
			}
		});
	});
	
	$('.dt-omspan').on('click', '.detail_renkas', function () {
		showLoading();
		if($('#periode').val() == '') {
			alert('Periode tidak ditemukan');
			return false;
		}
		if($(this).attr('kd_instansi') == '') {
			alert('Kode satker tidak ditemukan');
			return false;
		}
		$.ajax({
			type: "post",
			url: BASE_URL + '/omspan/getrenkas',
			data: {
				_token: $('#_token').val(),
				kdSatker : $(this).attr('kd_instansi'),
				periode : $('#periode').val()
			},
			success: function(data) {
				hideLoading();
                console.log(data);
				if(data.code=='200') {
					$('.div_renkas').show();
					$('.div_rekap').hide();
					var row = "";
					var persen = 0;
					var no = 1;
					var k = 1;
					var tepat = 0;
					var terlambat = 0;
					$('.dt-renkas tbody').html("");
					for (i = 0;i < parseInt($('#periode').val());i++) {
						
						for (j = 0;j < data.data.length;j++) {
							if(('0'+k).slice(-2) == data.data[j].periode) {
								if(data.data[j].status != 'NULL') {
									if(data.data[j].status == 'TEPAT') {									
										tepat++;
									} else {
										terlambat++;
									}
								}
							}
						}
						total = tepat + terlambat;
						if(total != 0) {
							persen = (tepat/total) *100;
						}
						row += "<tr>"+
                            "<td class='border_btm'>"+k+"</td>"+
                            "<td class='border_btm'>"+data.data[0].kdsatker+"</td>"+
                            "<td class='border_btm'>"+('0'+k).slice(-2)+"</td>"+
                            "<td class='border_btm'>"+tepat+"</td>"+
                            "<td class='border_btm'>"+terlambat+"</td>"+
                            "<td class='border_btm'>"+convertToRupiah(total)+"</td>"+
                            "<td class='border_btm'>"+parseFloat(persen).toFixed(2)+"</td></tr>";
						k++;
						total = 0;
						persen = 0;
					}
					
					$('.dt-renkas tbody').html(row);
				} else {
					$('.div_renkas').show();
					$('.div_rekap').hide();
					$('.dt-renkas tbody').html('<tr><td colspan="7" align="center">No Data Found</td></tr>');
					alert(data.status);
				}
			},
            error: function(e) {
				hideLoading();
				console.log('error '+JSON.stringify(e));
			}
		});
	});
	
	$('.dt-omspan').on('click', '.detail_spm', function () {
		showLoading();
		if($('#periode').val() == '') {
			alert('Periode tidak ditemukan');
			return false;
		}
		if($(this).attr('kd_instansi') == '') {
			alert('Kode satker tidak ditemukan');
			return false;
		}
		$.ajax({
			type: "post",
			url: BASE_URL + '/omspan/getspm',
			data: {
				_token: $('#_token').val(),
				kdSatker : $(this).attr('kd_instansi'),
				periode : $('#periode').val()
			},
			success: function(data) {
				hideLoading();
                console.log(data);
				if(data.code=='200') {
					$('.div_spm').show();
					$('.div_rekap').hide();
					var row = "";
					var total_salah = 0;
					var total_spm = 0;
					var no = 1;
					var persen = 0;
					var nilai = 100;
					$('.dt-spm tbody').html("");
					for (i = 0;i <data.data.length;i++) {
						total_salah += data.data[i].akumulasi_salah;
						total_spm += data.data[i].total_spm;
						
						if(total_salah != 0) {
							persen = parseFloat((total_salah/total_spm) *100).toFixed(2);
							nilai = 100-persen;
						}
						row += "<tr>"+
                            "<td class='border_btm'>"+ no++ +"</td>"+
                            "<td class='border_btm'>"+data.data[i].kdsatker+"</td>"+
                            "<td class='border_btm'>"+data.data[i].instansiName+"</td>"+
                            "<td class='border_btm'>"+ data.data[i].periode +"</td>"+
                            "<td class='border_btm'>"+total_salah+"</td>"+
                            "<td class='border_btm'>"+total_spm+"</td>"+
                            "<td class='border_btm'>"+convertToRupiah(persen)+"</td>"+
                            "<td class='border_btm'>"+convertToRupiah(nilai)+"</td></tr>";
					}
					$('.dt-spm tbody').html(row);
				} else {
					$('.div_spm').show();
					$('.div_rekap').hide();
					$('.dt-spm tbody').html('<tr><td colspan="7" align="center">No Data Found</td></tr>');
					alert(data.status);
				}
			},
            error: function(e) {
				hideLoading();
				console.log('error '+JSON.stringify(e));
			}
		});
	});
	
	$('.dt-omspan').on('click', '.detail_hal3dipa', function () {
		showLoading();
		if($('#periode').val() == '') {
			alert('Periode tidak ditemukan');
			return false;
		}
		if($(this).attr('kd_instansi') == '') {
			alert('Kode satker tidak ditemukan');
			return false;
		}
		$.ajax({
			type: "post",
			url: BASE_URL + '/omspan/gethal3dipa',
			data: {
				_token: $('#_token').val(),
				kdSatker : $(this).attr('kd_instansi'),
				periode : $('#periode').val()
			},
			success: function(data) {
				hideLoading();
                console.log(data);
				if(data.code=='200') {
					$('.div_hal3dipa').show();
					$('.div_rekap').hide();
					$('.dt-hal3dipa tbody').html("");
					var no = 0;
					var row = "";
					var real = 0;
					var deviasi = 0;
					var deviasi_persen = 0;
					var akum_deviasi = 0;
					var rata_deviasi = 0;
					var rata_deviasi1 = 0;
					var nilai_akhir = 0;
					for (i = 0;i < parseInt($('#periode').val());i++) {
						no++;
						
						for (j = 0;j < data.realisasi.length;j++) {
							if(('0'+no).slice(-2) == data.realisasi[j].periode) {
								real = data.realisasi[j].jumlahRealisasi;
							}
						}
						deviasi = Math.abs(real - data.data[0]["ren_" + no.toString().padStart(2, '0')]);
						if(data.data[0]["ren_" + no.toString().padStart(2, '0')] != 0) {							
							deviasi_persen = (deviasi / data.data[0]["ren_" + no.toString().padStart(2, '0')]) * 100;
						} else {
							deviasi_persen = 0;
						}
						akum_deviasi = deviasi_persen + akum_deviasi;
						rata_deviasi = akum_deviasi / no;
						if(no == 1) {
							if(rata_deviasi != 0) {
								rata_deviasi1 = rata_deviasi;
							} else {
								rata_deviasi1 = 100;
							}
						}
						nilai_akhir = rata_deviasi1 - rata_deviasi;
						console.log(nilai_akhir);
						if(nilai_akhir < 0) {
							nilai_akhir = 0;
						}
						row += "<tr>"+
                            "<td class='border_btm'>"+no+"</td>"+
                            "<td class='border_btm'>"+data.data[0].kdsatker+"</td>"+
                            "<td class='border_btm'>"+data.data[0].instansiName+"</td>"+
                            "<td class='border_btm'></td>"+
                            "<td class='border_btm'>"+('0'+no).slice(-2)+"</td>"+
                            "<td class='border_btm'>"+convertToRupiah(data.data[0]["ren_" + no.toString().padStart(2, '0')])+"</td>"+
                            "<td class='border_btm'>"+convertToRupiah(real)+"</td>"+
                            "<td class='border_btm'>"+convertToRupiah(deviasi)+"</td>"+
                            "<td class='border_btm'>"+parseFloat(deviasi_persen).toFixed(2)+"</td>"+
                            "<td class='border_btm'>"+parseFloat(akum_deviasi).toFixed(2)+"</td>"+
                            "<td class='border_btm'>"+parseFloat(rata_deviasi).toFixed(2)+"</td>"+
                            "<td class='border_btm'>"+parseFloat(nilai_akhir).toFixed(2)+"</td></tr>";
						real = 0;
						deviasi = 0;
						deviasi_persen = 0;
						rata_deviasi = 0;
						nilai_akhir = 0;
					}
					$('.dt-hal3dipa tbody').html(row);
				} else {
					$('.dt-hal3dipa tbody').html('<tr><td colspan="12" align="center">No Data Found</td></tr>');
					alert(data.status);
				}
			},
            error: function(e) {
				hideLoading();
				console.log('error '+JSON.stringify(e));
			}
		});
	});
	
	$('.dt-omspan').on('click', '.detail_tagih', function () {
		showLoading();
		if($('#periode').val() == '') {
			alert('Periode tidak ditemukan');
			return false;
		}
		if($(this).attr('kd_instansi') == '') {
			alert('Kode satker tidak ditemukan');
			return false;
		}
		$.ajax({
			type: "post",
			url: BASE_URL + '/omspan/gettagihan',
			data: {
				_token: $('#_token').val(),
				kdSatker : $(this).attr('kd_instansi'),
				periode : $('#periode').val()
			},
			success: function(data) {
				hideLoading();
                console.log(data);
				if(data.code=='200') {
					$('.div_tagihan').show();
					$('.div_rekap').hide();
					var row = "";
					var tepat = 0;
					var terlambat = 0;
					var k = 1;
					var total = 0;
					var persen = 0;
					$('.dt-tagihan tbody').html("");
					for (i = 0;i < parseInt($('#periode').val());i++) {
						
						for (j = 0;j < data.data.length;j++) {
							if(('0'+k).slice(-2) == data.data[j].periode) {
								if(data.data[j].status != 'NULL') {
									if(data.data[j].status == 'TEPAT') {									
										tepat++;
									} else {
										terlambat++;
									}
								}
							}
						}
						total = tepat + terlambat;
						if(total != 0) {
							persen = (tepat/total) *100;
						}
						row += "<tr>"+
                            "<td class='border_btm'>"+k+"</td>"+
                            "<td class='border_btm'>"+data.data[0].kdsatker+"</td>"+
                            "<td class='border_btm'>"+('0'+k).slice(-2)+"</td>"+
                            "<td class='border_btm'>"+tepat+"</td>"+
                            "<td class='border_btm'>"+terlambat+"</td>"+
                            "<td class='border_btm'>"+convertToRupiah(total)+"</td>"+
                            "<td class='border_btm'>"+parseFloat(persen).toFixed(2)+"</td></tr>";
						k++;
						total = 0;
						persen = 0;
					}
					$('.dt-tagihan tbody').html(row);
				} else {
					$('.div_tagihan').show();
					$('.div_rekap').hide();
					$('.dt-tagihan tbody').html('<tr><td colspan="7" align="center">No Data Found</td></tr>');
					alert(data.status);
				}
			},
            error: function(e) {
				hideLoading();
				console.log('error '+JSON.stringify(e));
			}
		});
	});
	
	$('.dt-omspan').on('click', '.detail_realisasi', function () {
		showLoading();
		if($('#periode').val() == '') {
			alert('Periode tidak ditemukan');
			return false;
		}
		if($(this).attr('kd_instansi') == '') {
			alert('Kode satker tidak ditemukan');
			return false;
		}
		$.ajax({
			type: "post",
			url: BASE_URL + '/omspan/getrealisasi',
			data: {
				_token: $('#_token').val(),
				kdSatker : $(this).attr('kd_instansi'),
				periode : $('#periode').val()
			},
			success: function(data) {
				hideLoading();
                console.log(data);
				if(data.code=='200') {
					$('.div_realisasi').show();
					$('.div_rekap').hide();
					var row = "";
					var k = 1;
					var realisasi = 0;
					var persen = 0;
					var triwulan = "";
					$('.dt-realisasi tbody').html("");
					for (i = 0;i < parseInt($('#periode').val());i++) {
						
						for (j = 0;j < data.data.length;j++) {
							if(('0'+k).slice(-2) == data.data[j].periode) {
								realisasi += data.data[j].jumlahRealisasi;
							}
						}
						if(data.pagu != 0) {
							persen = (realisasi/data.pagu) * 100;
						}
						if(parseInt(k) <= 3) {
							triwulan = 15;
						} else if(parseInt(k) > 3 && parseInt(k) <=6) {
							triwulan = 40;
						} else if(parseInt(k) > 6 && parseInt(k) <=9) {
							triwulan = 60;
						} else {
							triwulan = 90;
						}
						row += "<tr>"+
                            "<td class='border_btm'>"+k+"</td>"+
                            "<td class='border_btm'>"+data.data[0].kdSatker+"</td>"+
							"<td class='border_btm'></td>"+
                            "<td class='border_btm'>"+('0'+k).slice(-2)+"</td>"+
                            "<td class='border_btm'>"+data.pagu+"</td>"+
                            "<td class='border_btm'>"+realisasi+"</td>"+
                            "<td class='border_btm'>"+parseFloat(persen).toFixed(2)+"</td>"+
                            "<td class='border_btm'>"+Math.round(persen/triwulan *100)+"</td></tr>";
						k++;
						persen = 0;
					}
					$('.dt-realisasi tbody').html(row);
				} else {
					$('.div_realisasi').show();
					$('.div_rekap').hide();
					$('.dt-realisasi tbody').html('<tr><td colspan="8" align="center">No Data Found</td></tr>');
					alert(data.status);
				}
			},
            error: function(e) {
				hideLoading();
				console.log('error '+JSON.stringify(e));
			}
		});
	});
	
	$('.dt-omspan').on('click', '.detail_rekon', function () {
		showLoading();
		if($('#periode').val() == '') {
			alert('Periode tidak ditemukan');
			return false;
		}
		if($(this).attr('kd_instansi') == '') {
			alert('Kode satker tidak ditemukan');
			return false;
		}
		$.ajax({
			type: "post",
			url: BASE_URL + '/omspan/getrekon',
			data: {
				_token: $('#_token').val(),
				kdSatker : $(this).attr('kd_instansi'),
				periode : $('#periode').val()
			},
			success: function(data) {
				hideLoading();
                console.log(data);
				if(data.code=='200') {
					$('.div_rekon').show();
					$('.div_rekap').hide();
					var row = "";
					var k = 1;
					var bulan = "";
					var tanggal = "";
					var status = "TIDAK ADA DATA";
					$('.dt-rekon tbody').html("");
					for (i = 0;i < parseInt($('#periode').val());i++) {
						
						for (j = 0;j < data.data.length;j++) {
							if(('0'+k).slice(-2) == data.data[j].periode) {
								bulan = data.data[j].periode;
								tanggal = data.data[j].tanggal_upload;
								status = data.data[j].status;
							}
						}
						
						row += "<tr>"+
                            "<td class='border_btm'>"+k+"</td>"+
                            "<td class='border_btm'>"+data.data[0].kdsatker+"</td>"+
                            "<td class='border_btm'>"+data.data[0].instansiName+"</td>"+
                            "<td class='border_btm'>"+data.data[0].kdkppn+"</td>"+
                            "<td class='border_btm'>"+bulan+"</td>"+
                            "<td class='border_btm'>"+tanggal+"</td>"+
                            "<td class='border_btm'>"+status+"</td></tr>";
						k++;
						bulan = "";
						tanggal = "";
						status = "TIDAK ADA DATA";
					}
					$('.dt-rekon tbody').html(row);
				} else {
					$('.div_rekon').show();
					$('.div_rekap').hide();
					$('.dt-rekon tbody').html('<tr><td colspan="7" align="center">No Data Found</td></tr>');
					alert(data.status);
				}
			},
            error: function(e) {
				hideLoading();
				console.log('error '+JSON.stringify(e));
			}
		});
	});
	
	$('.dt-omspan tbody tr td.on_hover')
	.on('mouseenter', function(){ $(this).addClass('hover'); })
	.on('mouseleave', function(){ $(this).removeClass('hover'); });
	
	$('.btn_kembali').click(function(){
		showHide();
		console.log(convertToRupiah(0));
	});
	
	function convertToRupiah(angka){
		var	reverse = angka.toString().split('').reverse().join(''),
		ribuan 	= reverse.match(/\d{1,3}/g);
		ribuan	= ribuan.join('.').split('').reverse().join('');
		return ribuan;
	}
	
	function showHide() {
		$('.div_up').hide();
		$('.div_spm').hide();
		$('.div_retur').hide();
		$('.div_revisi').hide();
		$('.div_tagihan').hide();
		$('.div_rekon').hide();
		$('.div_renkas').hide();
		$('.div_realisasi').hide();
		$('.div_hal3dipa').hide();
		$('.div_kontrak').hide();
		$('.div_rekap').show();
	}
	function showLoading() {
		$('#loader-wrapper').show();
		$('.main_container').addClass('pagx');
	}
	function hideLoading() {
		$('#loader-wrapper').hide();
		$('.main_container').removeClass('pagx');
	}
})