<?php

namespace Mike;

class Colorizer {

    // http://en.wikipedia.org/wiki/ANSI_escape_code
    const Reset  = "\033[0m";

    const Black  = 30;
    const Red    = 31;
    const Green  = 32;
    const Yellow = 33;
    const Blue   = 34;
    const Purple = 35;
    const Cyan   = 36;
    const White  = 37;

    const OnBlack  = 40;
    const OnRed    = 41;
    const OnGreen  = 42;
    const OnYellow = 43;
    const OnBlue   = 44;
    const OnPurple = 45;
    const OnCyan   = 46;
    const OnWhite  = 47;

    const Bold = 1;
    const Underline = 4;

    public function __call($method, $args) {
        $text = $args[0];
        $result = "\033[0";
        $prefix = '';
        foreach ($this->splitCamelCase($method) as $word) {
            $word = ucfirst($word);
            if ($word == 'On') {
                $prefix = 'On';
                continue;
            }
            $result .= ';';
            $constant = $prefix . $word;
            if (!defined('self::' . $constant)) {
                throw new \Exception("Unsupported color: $constant, in call: $method!");
            }
            $result .= constant('self::' . $constant);
            $prefix = '';
        }
        $result .= 'm';
        $result .= $text;
        $result .= self::Reset;
        return $result;
    }

    private function splitCamelCase($camelCasedString) {
        return preg_split(
            '/([[:upper:]][[:lower:]]+)/',
            $camelCasedString,
            null,
            PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
    }
}
