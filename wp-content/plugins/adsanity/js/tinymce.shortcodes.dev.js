(function() {
	tinymce.create('tinymce.plugins.adsanity', {
		init : function(ed, url) {
			// Single Ad
			ed.addCommand('adsanity_ad', function() {
				ed.windowManager.open({
					file : url + '/../lib/ad_popup.php', // file that contains HTML for our modal window
					width : 220 + parseInt(ed.getLang('button.delta_width', 0)), // size of our window
					height : 240 + parseInt(ed.getLang('button.delta_height', 0)), // size of our window
					inline : 1
				}, {
					plugin_url : url
				});
			});
			ed.addButton('adsanity_ad', {title : 'Insert Ad', cmd : 'adsanity_ad', image: url + '/../images/shortcode-ad-icon.png' });

			// Ad Group
			ed.addCommand('adsanity_ad_group', function() {
				ed.windowManager.open({
					file : url + '/../lib/ad_group_popup.php', // file that contains HTML for our modal window
					width : 220 + parseInt(ed.getLang('button.delta_width', 0)), // size of our window
					height : 240 + parseInt(ed.getLang('button.delta_height', 0)), // size of our window
					inline : 1
				}, {
					plugin_url : url
				});
			});
			ed.addButton('adsanity_ad_group', {title : 'Insert Ad Group', cmd : 'adsanity_ad_group', image: url + '/../images/shortcode-ad-group-icon.png' });
		},

		getInfo : function() {
			return {
				longname : 'AdSanity',
				author : 'Pixel Jar',
				authorurl : 'http://www.pixeljar.net',
				infourl : 'http://www.adsanityplugin.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	// first parameter is the button ID and must match ID elsewhere
	// second parameter must match the first parameter of the tinymce.create() function above
	tinymce.PluginManager.add('AdSanity', tinymce.plugins.adsanity);

})();
