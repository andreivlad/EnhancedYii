<?php
class StringHelper{
	/**
     * Remove diacritics from a string
     *
     * @param string $string The string with diacritics
     * @return string
     */
    private static function removeDiacritics($string='') {
		$_a = array( 'Ã ','Ã¨','Ã©','Ã‰','Ã¢','Ãª','Ã®','Ã´','Ã»','Ã‚','ÃŠ','ÃŽ','Ã”','Ã›','Ã¤','Ã«','Ã¯','Ã¶','Ã¼','Ã„','Ã‹','Ã�','Ã–','Ãœ','Ã¦','Ã†','Å“','Å’','Ã§','Ã‡','Äƒ','Ä‚','Ã®','ÃŽ','Ã¢','Ã‚','È™','È˜','È›','Èš' );
		$_b = array( 'a','e','e','E','a','e','i','o','u','A','E','I','O','U','a','e','i','o','u','A','E','I','O','U','ae','AE','ce','CE','c','C', 'a', 'A', 'i','I','a','A','s','S','t','T' );
		return str_replace($_a,$_b,$string);
    }
	 
	 
	 /**
     * Sanitize a string ready for url or variable
     *
     * @param string $string The string to be sanitized
     * @param string $separator The glue between the words (by default '-')
     * @return string
     */
	public static function makeUrl($string, $separator = '-'){
		$string = StringHelper::removeDiacritics($string);

		preg_match_all('/([a-z0-9]+)/i', $string, $matches);

		$words = $matches[1];

		if (!empty($words)) {
			return urlencode(strtolower(implode($separator,$words)));
		}
		return false;
	}
	
	/**
	 * 
	 * @param type $text
	 * @param type $length
	 * @param type $suffix
	 * @param type $isHTML
	 * @return string
	 */
	public static function truncate($text = '', $length = 100, $suffix = '&hellip;', $isHTML = true){
        $i = 0;
        $tags = array();
        if($isHTML){
            preg_match_all('/<[^>]+>([^<]*)/', $text, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
            foreach($m as $o){
                if($o[0][1] - $i >= $length)
                    break;
                $t = substr(strtok($o[0][0], " \t\n\r\0\x0B>"), 1);
                if($t[0] != '/')
                    $tags[] = $t;
                elseif(end($tags) == substr($t, 1))
                    array_pop($tags);
                $i += $o[1][1] - $o[0][1];
            }
        }
       
        $output = substr($text, 0, $length = min(strlen($text),  $length + $i)) . (count($tags = array_reverse($tags)) ? '</' . implode('></', $tags) . '>' : '');
       
        // Get everything until last space
        $one = substr($output, 0, strrpos($output, " "));
        // Get the rest
        $two = substr($output, strrpos($output, " "), (strlen($output) - strrpos($output, " ")));
        // Extract all tags from the last bit
        preg_match_all('/<(.*?)>/s', $two, $tags);
        // Add suffix if needed
        if (strlen($text) > $length) { $one .= '&nbsp;' . $suffix; } else { $one .= '&nbsp;read'; }
        // Re-attach tags
        $output = $one . implode($tags[0]);
       
        return $output;
    }
}

