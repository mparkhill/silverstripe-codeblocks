<?php
/**
 * CodeBlocks are containers of arbitary markup, text or script that can be 'embedded' in page content via TinyMCE
 * using its custom tinymce toolbar widget.
 *
 * Use the "advertfortinymce" tinymce plugin to select a CodeBlock in the CMS and insert it as a shortcode into your content.
 * This is then translated into an image placeholder and parsed when the page is rendered and replaced with the contents of the 
 * 'Content' text field from the CodeBlock DataObject.
 * 
 * Shortcodes are translated for tinymce into an <img> element, so users can't edit the shortcode itself,
 * but in the database they look like this: [codeblock id={CodeBlock.ID}]CodeBlock.Name[/codeblock]
 *
 * @author Michael Parkhill <mike@silverstripe.com>
 */
class CodeBlock extends DataObject {

	/**
	 * @var array
	 */
	public static $db = array(
		'Name' => 'varchar',
		'Content' => 'Text',
		'Status' => "Enum('Active, Archive', 'Active')",
	);

	/**
	 * @var array
	 */	
	public static $summary_fields = array(
		"Name",
		"Status"
	);

	/**
	 * @dod why singleton('CodeBlock')->dbObject('Status')->enumValues() instead of $this->Status?
	 * @return FieldList
	 */
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->addFieldToTab('Root.Main', new HeaderField('CodeBlockHead', 'Code Block Administration'));
		$fields->addFieldToTab('Root.Main', new LiteralField('CodeBlockIntro', '<p>"CodeBlocks" are containers of arbitary HTML, text or javascript that can be "embedded" in page content via each page\'s WYSIWYG editor.</p>'));
		$fields->addFieldToTab('Root.Main', new TextField('Name'));
		$fields->addFieldToTab('Root.Main', new TextareaField('Content'));
		$fields->addFieldToTab('Root.Main', $statusField = new DropdownField("Status", "Status", $this->dbObject('Status')->enumValues()));
		$statusField->setDescription('<strong>Note:</strong> This only controls the appearance of this code-block within the editor itself.');
		return $fields;
	}

	/**
	 * Shortcode parser callback to replace "[codeblock id=n]CodeBlock Name[/codeblock]" with CodeBlock.Content contents 
	 * Note: any content passed in via the $content parameter will be ignored.
	 *
	 * @param array $arguments Any parameters attached to the shortcode as an associative array (keys are lower-case).
	 * @param string $content Any content enclosed within the shortcode (if it is an enclosing shortcode). Note that
	 * any content within this will not have been parsed, and can optionally be fed back into the parser.
	 * @param ShortcodeParser The ShortcodeParser instance used to parse the content.
	 * @return mixed (null | string)
	 * @see \ShortcodeParser
	 */
	public static function codeblock_shortcode_handler($arguments, $content = null, $parser = null) {
		// return null if the id argument is not valid
		if (!isset($arguments['id']) || !is_numeric($arguments['id'])) {
			return;
		}
		
		// Load the Advertisement DataObject using the id from $arguments array, else return null if it was not found
		if (!($codeBlock = DataObject::get_by_id('CodeBlock', $arguments['id']))) {
			return;
		}

		// create the html and return it
		return sprintf('<span class="ss-codeblock">%s</span>', $codeBlock->Content);
	}
}