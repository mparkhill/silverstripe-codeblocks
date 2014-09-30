# CodeBlocks

## Introduction

CodeBlocks can contain chunks of arbitary source code or text that you can 'embed' in a page's `HTMLEditorField` using a custom tinymce plugin.

The tinymce plugin provides a dropdown list of 'Active' `CodeBlock` DataObjects that can be inserted into a page. This injects a placeholder 
image element into the page, instead of the shortcode itself, to prevent the user from editing and seeing the shortcode syntax.

When the page is saved, the placeholder image is replaced with the shortcode. See: `code-blocks/model/CodeBlock.php` for the shortcode parser.

Shortcode placeholder images:

	<img src="/code-blocks/images/codeblock-150.png" width="150" height="44" alt="{CodeBlock.Name}" data-id="{CodeBlock.ID}">
 
Shortcode database format:

	[codeblock id="{CodeBlock.ID}"]{CodeBlock.Name}[/codeblock]

Shortcode parser output:

	<span class="ss-codeblock">{CodeBlock.Content}</span>


The plugin uses ajax to load the dropdown list of CodeBlocks from the database, filtered by {CodeBlock.Status} = 'Active'.
The ajax method is located in the javascript contained in the file: /code-blocks/tinymce-codeblocks/codeblock.html.
The ajax requested url is mapped to CodeBlockController via a custom route, see: /code-blocks/_config/routes.yml.

	ajax url: /api/codeblocks/getJson
	maps to Controller: /code-blocks/code/controllers/CodeBlockController.php::getJson()


## Installation

Add the following to your /mysite/config.php 

	// Register the 'CodeBlocks' plugin with tinymce
	HtmlEditorConfig::get('cms')->enablePlugins(array(
		'ss-codeblocks' => '../../../../code-blocks/javascript/tinymce-codeblock/editor_plugin.js'
	));

	// Place the 'Insert CodeBlock' button into tinymce's toolbar, next to the anchor button
	HtmlEditorConfig::get('cms')->insertButtonsAfter('anchor', 'ss-codeblocks');

	// Register a shortcode parser to render the shortcodes inserted by the ss-codeblock plugin
	ShortcodeParser::get('default')->register('codeblock', array('CodeBlock', 'codeblock_shortcode_handler'));
