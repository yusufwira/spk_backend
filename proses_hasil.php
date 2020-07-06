<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: *');
require('connection.php');
require_once('proses_ahp_2.php');

require_once __DIR__ . '/vendor/autoload.php';
use Phpml\Math\Matrix;

// $nama = $_POST['nama'];
// $sekolah = $_POST['sekolah'];

$nama = json_decode($_POST['nama']);
$sekolah = json_decode($_POST['sekolah']);

// echo json_encode(sizeof($sekolah));


// membuat tabel auto matis
$arr_crit = array();
for ($i=0; $i < sizeof($nama); $i++) { 
    for ($j=0; $j < sizeof($nama); $j++) { 
        $sql2 = "SELECT k.nama_kriteria as kriteria_1, k2.nama_kriteria  as kriteria_2, kb.bobot FROM kriteria_bobot kb  INNER JOIN kriteria k on kb.kriteria_1 = k.idKriteria INNER JOIN kriteria k2 on kb.kriteria_2 = k2.idKriteria";
        $result2 = $conn->query($sql2);
        if ($result2->num_rows > 0) {		
            while ($obj = $result2->fetch_assoc()) {
                if($obj['kriteria_1'].'-'.$obj['kriteria_2'] == $nama[$i].'-'.$nama[$j]){                   
                   $arr_crit[$i][$j] = $obj['bobot'];               
                }
                else if($obj['kriteria_1'].'-'.$obj['kriteria_2'] == $nama[$j].'-'.$nama[$i] ){
                    $hasil = 1/$obj['bobot'];                
                    $arr_crit[$i][$j] = $hasil;
                    
                }                                  
            }
        }
    }
}


function get_table(Array $Arr, $kriterria){
    require('connection.php');
    $arr_alt = array();
    for ($i=0; $i < sizeof($Arr); $i++) { 
        for ($j=0; $j < sizeof($Arr); $j++) { 
            $sql2 = "SELECT s.nama_sekolah as sekolah_1, s2.nama_sekolah  as sekolah_2, sb.bobot FROM sekolah_bobot sb  INNER JOIN info_sekolah s on sb.sekolah_id_1 = s.idinfo_sekolah INNER JOIN info_sekolah s2 on sb.sekolah_id_2 = s2.idinfo_sekolah INNER JOIN kriteria k on sb.kriteria_id = k.idKriteria WHERE k.nama_kriteria ='".$kriterria."'";
            $result2 = $conn->query($sql2);
            if ($result2->num_rows > 0) {		
                while ($obj = $result2->fetch_assoc()) {
                    if($obj['sekolah_1'].'-'.$obj['sekolah_2'] == $Arr[$i].'-'.$Arr[$j]){                   
                        $arr_alt[$i][$j] = $obj['bobot'];               
                     }
                     else if($obj['sekolah_1'].'-'.$obj['sekolah_2'] == $Arr[$j].'-'.$Arr[$i] ){
                         $hasil = 1/$obj['bobot'];                
                         $arr_alt[$i][$j] = $hasil;
                         
                     }         
                }
            }
        }
    }
    return $arr_alt;
}

$hasil_jadi = array();
//perhitungan kriteria
$VE_Crit = proses_ahp($arr_crit);
$CR_Crit = Consistancy_Ratio($arr_crit,$VE_Crit);
// echo json_encode ($VE_Crit);
// echo json_encode ($CR_Crit);
// echo json_encode ('<br><br>');
// $hasil_jadi['VE_CRIT']['nama'] = $nama;
$hasil_jadi['VE_CRIT'] = $VE_Crit;
$hasil_jadi['CR_CRIT'] = $CR_Crit;

//perhitungan Alternatif
$VE_ALT = array();
for ($i=0; $i < sizeof($nama); $i++){
    $arrAlt = get_table($sekolah,$nama[$i]);
    $hasil_VE_ALT = proses_ahp($arrAlt);
    $VE_ALT[$nama[$i]][0] = $hasil_VE_ALT;
    $VE_ALT[$nama[$i]][1] = Consistancy_Ratio($arrAlt,$hasil_VE_ALT);
}
// echo json_encode ($VE_ALT);
// echo json_encode ('<br><br>');
$hasil_jadi['VE_ALT'] = $VE_ALT;


// WSM
$hasil = array();
for ($i=0; $i < sizeof($nama); $i++) { 
    for ($j=0; $j < sizeof($VE_ALT[$nama[$i]][0]); $j++) { 
       $hasil[$i][$j] = round($VE_ALT[$nama[$i]][0][$j] * $VE_Crit[$i],4);
    }
}
$wsm = new Matrix($hasil);
$result = array();
for ($i=0; $i < $wsm->getColumns(); $i++) { 
    $total_column= 0.0;
    foreach ($wsm->getColumnValues($i) as $Value) {
        $total_column += $Value;           
    }
    $result[$i]['nama'] = $sekolah[$i];
    $result[$i]['hasil'] = $total_column;
   
} 


rsort($result);
$hasil_jadi['Hasil_jadi'] = $result;
echo json_encode ($hasil_jadi);

// echo "<table style='boder: 1 px'>";
// for ($i=0; $i < sizeof($result) ; $i++) { 
//     $j = $i+1;
//    echo '<tr>';
//    echo '<td><b>#'.$j.'</b></td>';
//    if($i == 0){
//     echo '<td>'.$result[$i]['nama'].' <b>(Recomendasi)</b></td>';
//    }
//    else{
//     echo '<td>'.$result[$i]['nama'].'</td>';
//    }
  
//    echo '<td>'.$result[$i]['hasil'].'</td>';
//    echo '</tr>';
// }
// echo "</table>";











?>