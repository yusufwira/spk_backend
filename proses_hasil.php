<?php

$matrix = $_POST['crit'];
$nama = $_POST['nama'];

require_once('proses_ahp.php');


$tree = [];
$tree['kriteria']= proses_ahp($matrix,$nama);


?>