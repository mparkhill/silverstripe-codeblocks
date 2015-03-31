(function() {

	var availableLangs = ['en'];
	if(jQuery.inArray(tinymce.settings.language, availableLangs) != -1) {
		tinymce.PluginManager.requireLangPack("ss_insert_codeblock");
	}

	var each = tinymce.each;
	tinymce.create('tinymce.plugins.InsertCodeBlock', {
		getInfo : function() {
			return {
				longname : 'Button to insert CodeBlocks',
				author : 'Michael Parkhill',
				authorurl : 'http://www.siverstripe.com/',
				infourl : 'http://www.silverstripe.com/',
				version : "1.0"
			};
		},
		init : function(ed, url) {
			ed.addCommand('mceInsertCodeBlock', function() {
				ed.windowManager.open({
					file : url + '/codeblock.html',
					width : 320 + parseInt(ed.getLang('codeblock.delta_width', 0)),
					height : 120 + parseInt(ed.getLang('codeblock.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			ed.addButton('ss_insert_codeblock', {
				title : 'Insert Code Block',
				cmd : 'mceInsertCodeBlock',
				image : url + '/../../images/codeblock-16.png'
			});

			ed.onSaveContent.add(function(ed, o) {
				var content = jQuery(o.content);
				content.find('.ss-codeblock').each(function() {
					var el = jQuery(this);
					console.log(el);
					var shortCode = '[codeblock id="' + el.data('id') + '"]' + el.attr('title') + '[/codeblock]';
					el.replaceWith(shortCode);
				});
				o.content = jQuery('<div />').append(content).html(); // Little hack to get outerHTML string
			});

			var shortTagRegex = /(.?)\[codeblock(.*?)\](.+?)\[\/\s*codeblock\s*\](.?)/gi;

			ed.onBeforeSetContent.add(function(ed, o) {
				var matches = null, content = o.content;
				var prefix, suffix, attributes, attributeString, title;
				var attrs, attr;
				var imgEl;
				// Match various parts of the embed tag
				while((matches = shortTagRegex.exec(content))) {
					prefix = matches[1];
					suffix = matches[4];
					if(prefix === '[' && suffix === ']') {
						continue;
					}
					attributes = {};
					// Remove quotation marks and trim.
					attributeString = matches[2].replace(/['"]/g, '').replace(/(^\s+|\s+$)/g, '');

					// Extract the attributes and values into a key-value array (or key-key if no value is set)
					attrs = attributeString.split(/\s+/);
					for(attribute in attrs) {
						attr = attrs[attribute].split('=');
						if(attr.length == 1) {
							attributes[attr[0]] = attr[0];
						} else {
							attributes[attr[0]] = attr[1];
						}
					}

					title = matches[3];

					imgEl = jQuery('<img/>').attr({
						 'src': 'code-blocks/images/codeblock-150.png'
						,'title': title
						,'class': 'ss-codeblock'
						,'width': 150
						,'height': 48
					});

					jQuery.each(attributes, function (key, value) {
						imgEl.attr('data-' + key, value);
					});

					content = content.replace(matches[0], prefix + (jQuery('<div/>').append(imgEl).html()) + suffix);
				}
				o.content = content;
			});
		}
	});
	tinymce.PluginManager.add("ss_insert_codeblock", tinymce.plugins.InsertCodeBlock);
})();