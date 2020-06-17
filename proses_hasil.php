<?php

require('connection.php');
require_once('proses_ahp_2.php');
require_once __DIR__ . '/vendor/autoload.php';
use Phpml\Math\Matrix;

$matrix = $_POST['crit'];
$nama = $_POST['nama'];
$sekolah = $_POST['sekolah'];


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
            $sql2 = "SELECT s.nama_sekolah as sekolah_1, s2.nama_sekolah  as sekolah_2, sb.bobot FROM sekolah_bobot sb  INNER JOIN sekolah s on sb.id_sekolah_1 = s.idSekolah INNER JOIN sekolah s2 on sb.id_sekolah_2 = s2.idSekolah INNER JOIN kriteria k on sb.id_kriteria = k.idKriteria WHERE k.nama_kriteria = '".$kriterria."'";
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

//perhitungan kriteria
$VE_Crit = proses_ahp($arr_crit);
$CR_Crit = Consistancy_Ratio($arr_crit,$VE_Crit);
echo json_encode ($VE_Crit);
echo json_encode ($CR_Crit);
echo json_encode ('<br><br>');

//perhitungan Alternatif
$VE_ALT = array();
for ($i=0; $i < sizeof($nama); $i++){
    $arrAlt = get_table($sekolah,$nama[$i]);
    $hasil_VE_ALT = proses_ahp($arrAlt);
    $VE_ALT[$nama[$i]][0] = $hasil_VE_ALT;
    $VE_ALT[$nama[$i]][1] = Consistancy_Ratio($arrAlt,$VE_ALT[$nama[$i]][0]);
}
echo json_encode ($VE_ALT);
echo json_encode ('<br><br>');


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
    $result[] = $total_column;
} 


rsort($result);
echo  json_encode ($result);

echo "<table style='boder: 1 px'>";
for ($i=0; $i < sizeof($result) ; $i++) { 
    $j = $i+1;
   echo '<tr>';
   echo '<td><b>#'.$j.'</b></td>';
   if($i == 0){
    echo '<td>'.$sekolah[$i].' <b>(Recomendasi)</b></td>';
   }
   else{
    echo '<td>'.$sekolah[$i].'</td>';
   }
  
   echo '<td>'.$result[$i].'</td>';
   echo '</tr>';
}
echo "</table>";











?>