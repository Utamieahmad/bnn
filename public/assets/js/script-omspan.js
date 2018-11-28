$('document').ready(function(){
	$('#menu_toggle').click();
	//$('#datatable-responsive').DataTable({
	//	fixedHeader: true
	//});
	
	$('.div_rekap').show();
	$('.div_up').hide();
	
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
		//$.ajax({
		//	type: "get",
		//	headers: {
		//		Authorization : 'Bearer '+ TOKEN
		//	},
		//	url: BASE_URL + '/api/getpengelolaanup/'+ $(this).attr('kd_instansi')+'/'+$('#periode').val(),
		//	success: function(data) {
        //        console.log(data)
		//	},
        //    error: function() {
		//	}
		//});
		$.ajax({
			type: "post",
			url: BASE_URL + '/omspan/getpengelolaanup',
			//headers: {
			//	Authorization : TOKEN
			//},
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
					$('.dt-up tbody').html('<tr><td colspan="12" align="center">No Data Found</td></tr>');
					alert(data.status);
				}
			},
            error: function(e) {
				console.log('error '+JSON.stringify(e));
			}
		});
	});
	
	$('.btn_kembali').click(function(){
		$('.div_up').hide();
		$('.div_rekap').show();
	});
	
	function convertToRupiah(angka){
		var	reverse = angka.toString().split('').reverse().join(''),
		ribuan 	= reverse.match(/\d{1,3}/g);
		ribuan	= ribuan.join('.').split('').reverse().join('');
		return ribuan;
	}
})