<?php
$files = array('public/favicon-32x32.png','public/favicon-16x16.png','public/favicon.ico');
foreach ($files as $f) {
    echo $f.' '.(file_exists($f) ? filesize($f) : 'missing').PHP_EOL;
}
?>