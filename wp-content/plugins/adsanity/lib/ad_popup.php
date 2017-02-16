<?php
// this file contains the contents of the popup window
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Insert AdSanity Ad</title>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo home_url( '/wp-includes/js/tinymce/tiny_mce_popup.js' ); ?>"></script>
<style type="text/css" src="<?php echo home_url( '/wp-includes/js/tinymce/themes/advanced/skins/wp_theme/dialog.css' ); ?>"></style>
<style type="text/css">
	#adsanity-dialog { }
	#adsanity-dialog div{ padding: 5px 0; height: 20px;}
	#adsanity-dialog label { display: block; float: left; margin: 0 8px 0 0; width: 80px; }
	#adsanity-dialog select, #adsanity-dialog input { display: block; float: right; width: 100px; padding: 3px 5px;}
	#adsanity-dialog select { width: 112px; }
	#adsanity-dialog #insert { display: block; line-height: 24px; text-align: center; margin: 10px 0 0 0; float: right; text-decoration: none;}
</style>

<script type="text/javascript">
	var ButtonDialog = {
		local_ed : 'ed',
		init : function(ed) {
			ButtonDialog.local_ed = ed;
			tinyMCEPopup.resizeToInnerSize();
		},
		insert : function insertButton(ed) {

			// Try and remove existing style / blockquote
			tinyMCEPopup.execCommand('mceRemoveNode', false, null);

			// set up variables to contain our input values
			var ad_id = jQuery('#adsanity-dialog select#ad-id').val();

			// set up variables to contain our input values
			var align = jQuery('#adsanity-dialog select#align').val();

			var output = '';

			// setup the output of our shortcode
			output = '[adsanity ';
				output += 'id=' + ad_id + ' ';
				output += 'align=' + align + ' ';
			output += ' /]';

			tinyMCEPopup.execCommand('mceReplaceContent', false, output);

			// Return
			tinyMCEPopup.close();
		}
	};
	tinyMCEPopup.onInit.add(ButtonDialog.init, ButtonDialog);
</script>

</head>
<body>
	<div id="adsanity-dialog">
		<form action="/" method="get" accept-charset="utf-8">
			<div>
				<label for="ad-id">Select an ad</label>
				<select name="ad-id" id="ad-id"><?php echo $ads ?></select>
			</div>
			<div>
				<label for="align">Align</label>
				<select name="align" id="align">
					<option value="alignnone">None</option>
					<option value="alignleft">Left</option>
					<option value="aligncenter">Center</option>
					<option value="alignright">Right</option>
				</select>
			</div>
			<div>
				<a href="javascript:ButtonDialog.insert(ButtonDialog.local_ed)" id="insert" style="display: block; line-height: 24px;">Insert</a>
			</div>
		</form>
	</div>
</body>
</html>
