<?php
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Headers: *');
    require('connection.php');
    require_once('proses_ahp_2.php');

    require_once __DIR__ . '/vendor/autoload.php';
    use Phpml\Math\Matrix;

    $nama = json_decode($_POST['nama']);
    $sekolah = json_decode($_POST['sekolah']);
 

    echo json_encode($nama);

?>