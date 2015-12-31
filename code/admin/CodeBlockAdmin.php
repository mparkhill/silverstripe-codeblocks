<?php
/**
 * ModelAdmin for CodeBlock objects
 *
 * @author Michael Parkhill <mike@silverstripe.com>
 */
class CodeBlockAdmin extends ModelAdmin
{

    /**
     * @var string
     */
    private static $url_segment = 'code-blocks';

    /**
     * @var string
     */
    private static $menu_title = 'Code Blocks';

    /**
     * @var string
     */
    private static $menu_icon = "code-blocks/images/codeblock-16-bw.png";

    /**
     * @var array
     */
    private static $managed_models = array(
        'CodeBlock'
    );
}
