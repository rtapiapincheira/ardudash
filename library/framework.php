<?php

function make_script($filename, $folder = 'assets') {
    $str = <<<OES
    <script type="application/javascript" src="$folder/$filename"></script>
OES;
    return $str."\n";
}

function make_link($filename, $folder = 'assets') {
    $str = <<<EOS
     <link type="text/css" rel="stylesheet" href="$folder/$filename" />
EOS;
    return $str."\n";
}

function startsWith($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function endsWith($haystack, $needle) {
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}