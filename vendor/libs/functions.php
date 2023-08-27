<?php

function debug($data) {
    echo '<hr>';
    echo '<pre>'. print_r($data, true) .'</pre>';
}