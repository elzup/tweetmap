<?php

function escape_js_string($str)
{
    return str_replace(array('"', '”', '“', "\n"), array('\\"', '\\"', '\\"', ' '), $str);
}
