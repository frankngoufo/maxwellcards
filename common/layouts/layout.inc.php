<?php
//Layouts are used in several pages with similar designs. They help avoid
//repeating modules across several pages. 

require_once(PATH_COMMON_MODULE."module.inc.php");

class Layout extends Module {
	public function __construct($page) {
		parent::__construct($page);
	}

	public function get_section($class, $modules, $tag="div") {
		$section = "";
		
		//normalize
		if (!is_array($modules)) {
			$modules = array($modules);
		}

		if (count($modules) == 0)
		return "";

		foreach ($modules as $content) {
			$section .= empty($content) ? "" : $content;
		}

		if (empty($section))
		return "";

		return <<<EOD
<$tag class="$class">
$section
</$tag><!--$class-->
EOD;
	}
}