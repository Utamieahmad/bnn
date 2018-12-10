$('document').ready(function(){
	hideLoading();
	$('.btn-search').click(function(){
		showLoading();
	})
	function showLoading() {
		$('#loader-wrapper').show();
		$('.main_container').addClass('pagx');
	}
	function hideLoading() {
		$('#loader-wrapper').hide();
		$('.main_container').removeClass('pagx');
	}
});