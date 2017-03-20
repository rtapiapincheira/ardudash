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