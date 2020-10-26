<?php
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Headers: *');
    require('connection.php');
    require_once('proses_ahp.php');
    require_once __DIR__ . '/vendor/autoload.php';
    use Phpml\Math\Matrix;

    //$matrix = [[1,1,0.5],[1,1,0.5],[2,2,1]];
    $matrix = [[1,1.65483871,0.7695],[0.604288499,1,0.465],[1.299545159,2.150537634,1]];
    $nama = ['YBBK', 'Raden', 'Alzhar'];
    $coba = proses_ahp($matrix,$nama);

    console_log($matrix);
    console_log($nama);


    
function console_log($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}
?>