<?php
/**
 * ModelAdmin for CodeBlock objects
 *
 * @author Michael Parkhill <mike@silverstripe.com>
 */
class CodeBlockAdmin extends ModelAdmin {

	/**
	 * @var string
	 */	
	public static $url_segment = 'code-blocks';

	/**
	 * @var string
	 */	
	public static $menu_title = 'Code Blocks';

	/**
	 * @var string
	 */	
	public static $menu_icon = "code-blocks/images/codeblock-16-bw.png";

	/**
	 * @var array
	 */	
	static $managed_models = array(
		'CodeBlock',
	);
}