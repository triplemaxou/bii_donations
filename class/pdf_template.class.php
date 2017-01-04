<?php

class pdf_template extends global_class {

	// <editor-fold desc="Font">
	function font() {
		$return = "Din";
		$font = $this->font;
		if ($font != "") {
			$return = $font;
		}
		return $return;
	}

	function font_face() {
		return $this->font();
	}

	// </editor-fold>
	// <editor-fold desc="PDML">

	static function pdml_head($title, $subject, $author = "BiilinkAgency") {
		$text = "<pdml><head><title>$title</title><subject>$subject></subject><keywords></keywords><author>$author</author></head><body>";
		return $text;
	}

	static function pdml_end() {
		$text = "</body></pdml>";
		return $text;
	}

	static function pdml_newpage($content="") {
		$text = "<page>$content</page>";
		return $text;
	}

	static function pdml_div($content = "", $top = "0cm", $left = "0cm", $height = "100%") {
		$text = "<div top='$top' left='$left' height='$height' >$content</div>";
		return $text;
	}

	static function pdml_text($text = "", $align = "left", $width="100%", $font_face = "arial", $font_size="12px", $color="#fff") {
		$pdml = "<font face='$font_face' size='$font_size' color='$color'><cell width='$width' align='$align' >$text</cell></font>";
		return $pdml;
	}

	static function displayPDML($title, $subject, $content,$echo = false) {
		$text = static::pdml_head($title, $subject).$content.static::pdml_end();
		if($echo){
			echo $text;
		}
		return $text;
	}

// </editor-fold>
}

// fin de la classe