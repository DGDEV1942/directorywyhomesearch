jQuery(document).ready(function($) {
	var img = new Image();
	img.src = adsanity.loading;

	$('#auth-button').click(function() {
		$('#auth-button').attr("disabled","disabled").after('&nbsp;&nbsp;Authorizing, please wait. <img src="' + adsanity.loading + '" alt="loading" style="vertical-align: middle;" />');
		$('#adsanity-authorization-form').submit();
		return false;
	});
});
