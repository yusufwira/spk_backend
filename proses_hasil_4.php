<?php

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: *');
require('connection.php');
require_once('proses_ahp.php');



require_once __DIR__ . '/vendor/autoload.php';
use Phpml\Math\Matrix;


function auto_data($jarak){
    require('connection.php');
    $sql_sekolah = "SELECT * FROM info_sekolah";
    $result_infosekolah = $conn->query($sql_sekolah);
    $id_sekolah = array();
    $z =0;
    while ($sekolah = $result_infosekolah->fetch_assoc()){
        $id_sekolah[$z] = $sekolah['npsn'];
        $nama_sekolah[$z] = $sekolah['nama_sekolah'];
        $z++;
    }

    $sql_kriteria = "SELECT * FROM kriteria k ";
    $result_kriteria = $conn->query($sql_kriteria);   
    
    while ($kriteria = $result_kriteria->fetch_assoc()){
        $id_kriteria = $kriteria['idkriteria'];
        $nama_kriteria = $kriteria['nama_kriteria'];
        // echo json_encode($kriteria['nama_kriteria']);
        // echo "<br>";
        $hasil_data[$kriteria['nama_kriteria']] = array(); 
        for ($i=0; $i < sizeof($id_sekolah); $i++) { 
            for ($j=0; $j <= $i; $j++) {
                $n_s1 = 0;
                $n_s2 = 0;
                if( $id_sekolah[$i] == $id_sekolah[$j]){
    
                }
                else{
                    if($id_kriteria == 3){
                        $sql_ekstra_1= "SELECT * FROM ekstrakurikuler e INNER JOIN ekstrakurikuler_has_info_sekolah es on e.idekstrakurikuler=es.ekstrakurikuler_idekstrakurikuler WHERE es.info_sekolah_idinfo_sekolah =".$id_sekolah[$i];
                        $result_ekstra_1 = $conn->query($sql_ekstra_1);
                        $n_s1 = $result_ekstra_1->num_rows;

                        $sql_ekstra_2= "SELECT * FROM ekstrakurikuler e INNER JOIN ekstrakurikuler_has_info_sekolah es on e.idekstrakurikuler=es.ekstrakurikuler_idekstrakurikuler WHERE es.info_sekolah_idinfo_sekolah =".$id_sekolah[$j];
                        $result_ekstra_2 = $conn->query($sql_ekstra_2);
                        $n_s2 = $result_ekstra_2->num_rows;

                        $rating_1 = Rating($id_sekolah[$i], "Ekstrakurikuler");
                        $rating_2 = Rating($id_sekolah[$j], "Ekstrakurikuler");

                        if($rating_1 > $rating_2){
                            $n_s1 += (substr($rating_1,0,1) - substr($rating_2,0,1))+1;
                        }
                        elseif ($rating_1 < $rating_2){
                            $n_s2 += (substr($rating_2,0,1) - substr($rating_1,0,1))+1;
                        }
                        else{
                            $n_s1 += 0;
                            $n_s2 += 0;
                        }                                          
                        
                    }
                    elseif($id_kriteria == 4){
                        //array_push($cc_L, $jarak[$i] ." == ". $jarak[$j]);
                        //echo ($jarak[$i] ." == ". $jarak[$j]);
                        if($jarak[$i] > $jarak[$j]){
                            $n_s1 = 2;
                        }
                        elseif($jarak[$i] < $jarak[$j]){
                            $n_s2 = 2;
                        }
                        else{
                            $n_s1 = 0;
                            $n_s2 = 0;
                        }

                        $rating_1 = Rating($id_sekolah[$i], "Lokasi");
                        $rating_2 = Rating($id_sekolah[$j], "Lokasi");

                        if($rating_1 > $rating_2){
                            $n_s1 += (substr($rating_1,0,1) - substr($rating_2,0,1))+1;
                        }
                        elseif ($rating_1 < $rating_2){
                            $n_s2 += (substr($rating_2,0,1) - substr($rating_1,0,1))+1;
                        }
                        else{
                            $n_s1 += 0;
                            $n_s2 += 0;
                        }
                        
                    }
                    else{
                        $sql_detail_1 = "SELECT * FROM info_sekolah s INNER JOIN info_sekolah_has_kriteria_detail sd on s.npsn=sd.info_sekolah_idinfo_sekolah INNER JOIN kriteria_detail kd on sd.kriteria_detail_iddetail_kriteria = kd.iddetail_kriteria INNER JOIN kriteria k on kd.kriteria_idkriteria = k.idkriteria WHERE s.npsn = ".$id_sekolah[$i]." AND k.idkriteria =".$id_kriteria ;
                        $result_infosekolah_1 = $conn->query($sql_detail_1);   
                        $arr_detail_sekolah_1 = array(); 
                        $y = 0;
                        while ($sekolah_1 = $result_infosekolah_1->fetch_assoc()){
                            $arr_detail_sekolah_1[$i][$y] = $sekolah_1['nilai'];
                            $y++;
                        }


                        $sql_detail_2 = "SELECT * FROM info_sekolah s INNER JOIN info_sekolah_has_kriteria_detail sd on s.npsn=sd.info_sekolah_idinfo_sekolah INNER JOIN kriteria_detail kd on sd.kriteria_detail_iddetail_kriteria = kd.iddetail_kriteria INNER JOIN kriteria k on kd.kriteria_idkriteria = k.idkriteria WHERE s.npsn = ".$id_sekolah[$j]." AND k.idkriteria =".$id_kriteria ;
                        $result_infosekolah_2 = $conn->query($sql_detail_2);   
                        $arr_detail_sekolah_2 = array();
                        $y = 0;
                        while ($sekolah_2 = $result_infosekolah_2->fetch_assoc()){
                            $arr_detail_sekolah_2[$j][$y] = $sekolah_2['nilai'];
                            $y++;
                        } 

                
                        $rating_1 = Rating($id_sekolah[$i], $nama_kriteria);
                        $rating_2 = Rating($id_sekolah[$j], $nama_kriteria);

                        if($rating_1 > $rating_2){
                            $n_s1 += (substr($rating_1,0,1) - substr($rating_2,0,1))+1;
                        }
                        elseif ($rating_1 < $rating_2){
                            $n_s2 += (substr($rating_2,0,1) - substr($rating_1,0,1))+1;
                        }
                        else{
                            $n_s1 += 0;
                            $n_s2 += 0;
                        }                                          

                        $rest_s1 = array();
                        $rest_s2 = array();
                        $rest_s1 = $arr_detail_sekolah_1[$i];
                        $rest_s2 = $arr_detail_sekolah_2[$j];

                        if($id_kriteria == 5){
                            for ($g=0; $g < sizeof($rest_s1) ; $g++) { 
                                if($rest_s1[$g] > $rest_s2[$g]){
                                    $n_s2 += 1;
                                }
                                elseif ($rest_s1[$g] < $rest_s2[$g]){
                                    $n_s1 += 1;
                                }
                                else{
                                    $n_s1 += 0;
                                    $n_s2 += 0;
                                }
                            }
                        }
                        else{
                            for ($g=0; $g < sizeof($rest_s1) ; $g++) { 
                                if($rest_s1[$g] > $rest_s2[$g]){
                                    $n_s1 += 1;
                                }
                                elseif ($rest_s1[$g] < $rest_s2[$g]){
                                    $n_s2 += 1;
                                }
                                else{
                                    $n_s1 += 0;
                                    $n_s2 += 0;
                                }
                            }
                        }
                       
                    }
                    
                    
                    $ress = abs($n_s1 - $n_s2)+ 1;
                    if($ress > 9){
                        $ress = 9;
                    }
                    elseif($ress == 0){
                        $ress = 1;
                    }

                    /////////////////////////////////////////////
                    if($n_s1 > $n_s2){                                    
                        $temp = array('sekolah_1' => $nama_sekolah[$i], 'sekolah_2' => $nama_sekolah[$j], 'bobot' => $ress);                   
                        array_push($hasil_data[$kriteria['nama_kriteria']] , $temp);
                    }
                    elseif($n_s1 <= $n_s2){                                    
                        $temp = array('sekolah_1' => $nama_sekolah[$j], 'sekolah_2' => $nama_sekolah[$i], 'bobot' => $ress);                
                        array_push($hasil_data[$kriteria['nama_kriteria']] , $temp);
                      
                    }                                
                    
                }  
                
                
            }           
        }
    }

    return $hasil_data;

}

function get_table_kriteria($list_kriteria){
    require('connection.php');
    $arr_crit = array();
    for ($i=0; $i < sizeof($list_kriteria); $i++) { 
        for ($j=0; $j < sizeof($list_kriteria); $j++) { 
            $sql2 = "SELECT k.nama_kriteria as kriteria_1, k2.nama_kriteria  as kriteria_2, kb.bobot FROM kriteria_bobot kb  INNER JOIN kriteria k on kb.kriteria_1 = k.idKriteria INNER JOIN kriteria k2 on kb.kriteria_2 = k2.idKriteria";
            $result2 = $conn->query($sql2);
            if ($result2->num_rows > 0) {		
                while ($obj = $result2->fetch_assoc()) {
                    if($obj['kriteria_1'].'-'.$obj['kriteria_2'] == $list_kriteria[$i].'-'.$list_kriteria[$j]){                   
                       $arr_crit[$i][$j] = $obj['bobot'];               
                    }
                    else if($obj['kriteria_1'].'-'.$obj['kriteria_2'] == $list_kriteria[$j].'-'.$list_kriteria[$i] ){
                        $hasil = 1/$obj['bobot'];                
                        $arr_crit[$i][$j] = $hasil;                        
                    }                                  
                }
            }
        }
    }
    
    return  $arr_crit;
}


function get_table_sekolah($array_sekolah,$temp_array_ahp,$nama_kriteria){
    $arr_sekolah = array();
    $kriteria = $temp_array_ahp[$nama_kriteria];
    for ($i=0; $i < sizeof($array_sekolah) ; $i++) {         
        for ($j=0; $j < sizeof($array_sekolah); $j++) {             
            if($array_sekolah[$i] == $array_sekolah[$j]){
                $arr_sekolah[$i][$j] = 1;
   
            }
            else{               
               for ($y=0; $y <sizeof($kriteria); $y++) {             
                    if($array_sekolah[$i]==$kriteria[$y]['sekolah_1'] && $array_sekolah[$j]==$kriteria[$y]['sekolah_2']){     
                        $arr_sekolah[$i][$j] = $kriteria[$y]['bobot'];                       
                    }
                    else if($array_sekolah[$j]==$kriteria[$y]['sekolah_1'] && $array_sekolah[$i]==$kriteria[$y]['sekolah_2']){
                        $bagi = 1/$kriteria[$y]['bobot']; 
                        $arr_sekolah[$i][$j] = $bagi;                   
                    }
               }
            }
        }                
    }   
    return $arr_sekolah;
    
}



function WSM($VE_Crit,$VE_ALT,$list_kriteria,$list_sekolah){
    $hasil = array();
    for ($i=0; $i < sizeof($list_kriteria); $i++) { 
        for ($j=0; $j < sizeof($VE_ALT[$list_kriteria[$i]][0]); $j++) { 
            $hasil[$i][$j] = round($VE_ALT[$list_kriteria[$i]][0][$j] * $VE_Crit[$i],4);
        }
    }

    $wsm = new Matrix($hasil);
    $result = array();
    for ($i=0; $i < $wsm->getColumns(); $i++) { 
        $total_column= 0.0;
        foreach ($wsm->getColumnValues($i) as $Value) {
            $total_column += $Value;           
        }
        $result[$i]['hasil'] = $total_column;
        $result[$i]['nama'] = $list_sekolah[$i];
        
        
        
    } 
    return $result;
}

/////////////////////////////////////////////////////////////////////////
function Rating($idSekolah, $nama_crit){
    require('connection.php');
    $sql_kriteria = "SELECT * FROM kriteria";
    $result_kriteria = $conn->query($sql_kriteria);
    $j =0;
    $arr_data = array();
    while ($obj_kriteria = $result_kriteria->fetch_assoc()) {
        $idkriteria = addslashes(htmlentities($obj_kriteria['idkriteria'])); 
        $sql = "SELECT * FROM rating r INNER JOIN kriteria k on r.kriteria_idkriteria = k.idkriteria WHERE r.info_sekolah_id =".$idSekolah." AND k.idkriteria =".$idkriteria;
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {		       
            $i=0;
            $hasil = 0;
            while ($obj = $result->fetch_assoc()) {
                $i += addslashes(htmlentities($obj['rating']));                
            }
            $hasil = number_format($i/$result->num_rows,1);
        } else {  
            $hasil = 0;
        
        }
        $arr_data[$obj_kriteria['nama_kriteria']] =  $hasil;
        $j++;

    }

    return  $arr_data[$nama_crit];
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////

//$list_kriteria = json_decode($_POST['nama']);
//$list_sekolah = json_decode($_POST['sekolah']);
$list_kriteria = ["Fasilitas","Akademis","Ekstrakurikuler","Lokasi","Biaya"];
$list_sekolah = ["SD YBPK-3", "SD RADEN PAKU 2","SD MAJU JAYA", "SD Al Azhar Kepala Gading"];

//$jarak = json_decode($_POST['jarak']);
$jarak = ["4.491","14.819","3.391","20.34"];


$tabel_kreteria = get_table_kriteria($list_kriteria);
$autos = auto_data($jarak);

 $hasil_jadi = array();
 $VE_Crit = proses_ahp($tabel_kreteria,$list_kriteria);
 $CR_Crit = Consistancy_Ratio($tabel_kreteria,$VE_Crit);
 $hasil_jadi['VE_CRIT'] = $VE_Crit;
 $hasil_jadi['CR_CRIT'] = $CR_Crit;
;

$VE_ALT = array();
for ($i=0; $i < sizeof($list_kriteria); $i++){
    $arrAlt = get_table_sekolah($list_sekolah,$autos,$list_kriteria[$i]);
  
    $hasil_VE_ALT = proses_ahp($arrAlt, $list_sekolah);
    $VE_ALT[$list_kriteria[$i]][0] = $hasil_VE_ALT;
    $VE_ALT[$list_kriteria[$i]][1] = Consistancy_Ratio($arrAlt,$hasil_VE_ALT);
}
$hasil_jadi['VE_ALT'] = $VE_ALT;



$result = WSM($VE_Crit,$VE_ALT,$list_kriteria,$list_sekolah);
rsort($result);
$hasil_jadi['Hasil_jadi'] = $result;

echo json_encode($hasil_jadi)








?>
