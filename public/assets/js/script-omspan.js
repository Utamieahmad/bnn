$('document').ready(function(){
	$('#menu_toggle').click();
	showHide();
	
	$('.dt-omspan').on('click', '.detail_up', function () {
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
                console.log(data);
				if(data.code=='200') {
					$('.div_up').show();
					$('.div_rekap').hide();
					var row = "";
					var tepat = 0;
					var terlambat = 0;
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
                            "<td>"+i+"</td>"+
                            "<td>"+data.data[i].kdsatker+"</td>"+
                            "<td>"+data.data[i].instansiName+"</td>"+
                            "<td>"+data.data[i].sumber_dana+"</td>"+
                            "<td>"+data.data[i].jenis+"</td>"+
                            "<td>"+data.data[i].tanggal+"</td>"+
                            "<td>"+data.data[i].tanggal_diff+"</td>"+
                            "<td>"+convertToRupiah(data.data[i].nilai_total)+"</td>"+
                            "<td>"+convertToRupiah(data.data[i].outstanding)+"</td>"+
                            "<td>"+convertToRupiah(gu)+"</td>"+
                            "<td>"+Math.round((gu/data.data[i].outstanding) * 100)+"</td>"+
                            "<td>"+data.data[i].status+"</td>";
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
				console.log('error '+JSON.stringify(e));
			}
		});
	});
	
	$('.dt-omspan').on('click', '.detail_dkon', function () {
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
                //console.log(data);
				if(data.code=='200') {
					$('.div_kontrak').show();
					$('.div_rekap').hide();
					var row = "";
					var tepat = 0;
					var terlambat = 0;
					var k = 1;
					var total = 0;
					var persen = 0;
					$('.dt-kontrak tbody').html("");
					for (i = 0;i < parseInt($('#periode').val());i++) {
						//console.log(i);
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
						//console.log(tepat);
						//console.log(terlambat);
						total = tepat + terlambat;
						if(total != 0) {
							persen = (tepat/total) *100;
							row += "<tr>"+
								"<td>"+k+"</td>"+
								"<td>"+data.data[0].kdsatker+"</td>"+
								"<td>"+data.data[0].instansiName+"</td>"+
								"<td></td>"+
								"<td>"+('0'+k).slice(-2)+"</td>"+
								"<td>"+tepat+"</td>"+
								"<td>"+terlambat+"</td>"+
								"<td>"+convertToRupiah(total)+"</td>"+
								"<td>"+parseFloat(persen).toFixed(2)+"</td>"+
								"<td></td>";
						}
						k++;
						//tepat = 0;
						//terlambat = 0;
						//total = 0;
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
				console.log('error '+JSON.stringify(e));
			}
		});
	});
	
	$('.dt-omspan').on('click', '.detail_revisi', function () {
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
                            "<td>"+i+"</td>"+
                            "<td>"+data.data[i].periode+"</td>"+
                            "<td>"+data.data[i].kdsatker+"</td>"+
                            "<td>"+data.data[i].total_revisi+"</td>"+
                            "<td></td>"+
                            "<td></td>";
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
				console.log('error '+JSON.stringify(e));
			}
		});
	});
	
	$('.dt-omspan').on('click', '.detail_retur', function () {
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
                console.log(data);
				if(data.code=='200') {
					$('.div_retur').show();
					$('.div_rekap').hide();
					var row = "";
					var persen = 0;
					$('.dt-retur tbody').html("");
					for (i = 0;i < data.data.length;i++) {
						if(data.data[i].jml_sp2d != 0) {
							persen = (data.data[i].jml_retur / data.data[i].jml_sp2d) * 100;
						} else {
							persen = 0;
						}
						row += "<tr>"+
                            "<td>"+i+"</td>"+
                            "<td>"+data.data[i].kdsatker+"</td>"+
                            "<td>"+data.data[i].instansiName+"</td>"+
                            "<td></td>"+
                            "<td>"+data.data[i].periode+"</td>"+
                            "<td>"+data.data[i].jml_retur+"</td>"+
                            "<td>"+data.data[i].jml_sp2d+"</td>"+
                            "<td>"+parseFloat(persen).toFixed(2)+"</td>"+
                            "<td>"+parseFloat(100 - persen).toFixed(2)+"</td>";
					}
					$('.dt-retur tbody').html(row);
				} else {
					$('.dt-retur tbody').html('<tr><td colspan="9" align="center">No Data Found</td></tr>');
					alert(data.status);
				}
			},
            error: function(e) {
				console.log('error '+JSON.stringify(e));
			}
		});
	});
	
	$('.dt-omspan').on('click', '.detail_renkas', function () {
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
                            "<td>"+k+"</td>"+
                            "<td>"+data.data[0].kdsatker+"</td>"+
                            "<td>"+('0'+k).slice(-2)+"</td>"+
                            "<td>"+tepat+"</td>"+
                            "<td>"+terlambat+"</td>"+
                            "<td>"+convertToRupiah(total)+"</td>"+
                            "<td>"+parseFloat(persen).toFixed(2)+"</td>";
						k++;
						total = 0;
						persen = 0;
					}
					
					//for (i = 0;i < parseInt($('#periode').val());i++) {
					//	no++;
					//	if($('#periode').val() == data.data[i].kdsatker)
					//	row += "<tr>"+
                    //        "<td>"+no+"</td>"+
                    //        "<td>"+data.data[i].kdsatker+"</td>"+
                    //        "<td>0"+no+"</td>"+
                    //        "<td></td>"+
                    //        "<td></td>"+
                    //        "<td></td>"+
                    //        "<td></td>";
					//}
					$('.dt-renkas tbody').html(row);
				} else {
					$('.div_renkas').show();
					$('.div_rekap').hide();
					$('.dt-renkas tbody').html('<tr><td colspan="7" align="center">No Data Found</td></tr>');
					alert(data.status);
				}
			},
            error: function(e) {
				console.log('error '+JSON.stringify(e));
			}
		});
	});
	
	$('.dt-omspan').on('click', '.detail_spm', function () {
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
                            "<td>"+ no++ +"</td>"+
                            "<td>"+data.data[i].kdsatker+"</td>"+
                            "<td>"+data.data[i].instansiName+"</td>"+
                            "<td>"+ data.data[i].periode +"</td>"+
                            "<td>"+total_salah+"</td>"+
                            "<td>"+total_spm+"</td>"+
                            "<td>"+convertToRupiah(persen)+"</td>"+
                            "<td>"+convertToRupiah(nilai)+"</td>";
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
				console.log('error '+JSON.stringify(e));
			}
		});
	});
	
	$('.dt-omspan').on('click', '.detail_hal3dipa', function () {
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
                console.log(data);
				if(data.code=='200') {
					$('.div_hal3dipa').show();
					$('.div_rekap').hide();
					$('.dt-hal3dipa tbody').html("");
					var no = 0;
					var row = "";
					var key_ren = "";
					for (i = 0;i < parseInt($('#periode').val());i++) {
						no++;
						key_ren = "ren_"+('0'+no).slice(-2);
						row += "<tr>"+
                            "<td>"+no+"</td>"+
                            "<td>"+data.data[0].kdsatker+"</td>"+
                            "<td>"+data.data[0].instansiName+"</td>"+
                            "<td></td>"+
                            "<td>0"+no+"</td>"+
                            "<td>"+data.data[0]["ren_" + no.toString().padStart(2, '0')]+"</td>"+
                            "<td></td>"+
                            "<td></td>"+
                            "<td></td>"+
                            "<td></td>"+
                            "<td></td>"+
                            "<td></td>";
					}
					$('.dt-hal3dipa tbody').html(row);
				} else {
					$('.dt-hal3dipa tbody').html('<tr><td colspan="12" align="center">No Data Found</td></tr>');
					alert(data.status);
				}
			},
            error: function(e) {
				console.log('error '+JSON.stringify(e));
			}
		});
	});
	
	$('.dt-omspan').on('click', '.detail_tagih', function () {
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
                            "<td>"+k+"</td>"+
                            "<td>"+data.data[0].kdsatker+"</td>"+
                            "<td>"+('0'+k).slice(-2)+"</td>"+
                            "<td>"+tepat+"</td>"+
                            "<td>"+terlambat+"</td>"+
                            "<td>"+convertToRupiah(total)+"</td>"+
                            "<td>"+parseFloat(persen).toFixed(2)+"</td>";
						k++;
						//tepat = 0;
						//terlambat = 0;
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
				console.log('error '+JSON.stringify(e));
			}
		});
	});
	
	$('.dt-omspan').on('click', '.detail_realisasi', function () {
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
							//else {
							//	realisasi = 0;
							//}
						}
						if(data.pagu != 0) {
							persen = (realisasi/data.pagu) * 100;
						}
						if(parseInt(k) <= 3) {
							triwulan = 15;
						} else if(parseInt(k) > 3 && parseInt(k) <=6) {
							triwulan = 45;
						} else if(parseInt(k) > 6 && parseInt(k) <=9) {
							triwulan = 60;
						} else {
							triwulan = 90;
						}
						row += "<tr>"+
                            "<td>"+k+"</td>"+
                            "<td>"+data.data[0].kdSatker+"</td>"+
							"<td></td>"+
                            "<td>"+('0'+k).slice(-2)+"</td>"+
                            "<td>"+data.pagu+"</td>"+
                            "<td>"+realisasi+"</td>"+
                            "<td>"+parseFloat(persen).toFixed(2)+"</td>"+
                            "<td>"+Math.round(persen/triwulan *100)+"</td>";
						k++;
						//realisasi = 0;
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
})