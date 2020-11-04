<?php

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: *');
require('connection.php');
require_once('proses_ahp_2.php');
require_once __DIR__ . '/vendor/autoload.php';
use Phpml\Math\Matrix;
$list_kriteria = json_decode($_POST['nama']);
$list_sekolah = json_decode($_POST['sekolah']);
$jarak = json_decode($_POST['jarak']);

// $list_kriteria = ["Fasilitas","Akademis","Biaya"];
// $list_sekolah = ["SD JAC School","SD Al Islam","SD VITA","SD Katolik Kristus Raja"];
// $jarak = ["1.525","3.797","12.931","2.952","20.01","5.718","2.259","6.863","2.502"];



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


function get_table_subkriteria($kriteria){
    require('connection.php');
    
    $arr_subcrit = array();
    $sql = "SELECT * FROM subkriteria_bobot sb WHERE sb.sub_2 in (SELECT kd.iddetail_kriteria FROM kriteria k INNER JOIN kriteria_detail kd on k.idkriteria=kd.kriteria_idkriteria WHERE k.nama_kriteria = '$kriteria')";
    $result = $conn->query($sql);
    while ($obj = $result->fetch_assoc()) {        
        $arr_subcrit[$obj['sub_1']][$obj['sub_2']] = $obj['bobot'];
        if($obj['sub_1'] != $obj['sub_2']){
            $arr_subcrit[$obj['sub_2']][$obj['sub_1']] = 1/$obj['bobot'];
        }
    }
    $arr_subcrit_2 = array();
    $temp_key = array();
    $i=0;
    foreach ($arr_subcrit as $key => $value) {     
        array_push($temp_key, $key);       
        for ($j=$temp_key[0]; $j < sizeof($value)+$temp_key[0] ; $j++) {             
            $arr_subcrit_2[$i][] = $value[$j];
        }
        
        $i++;
    }

    // var_dump(sizeof($value)+$temp_key[0]);
    // var_dump($arr_subcrit);
    return $arr_subcrit_2;
}


function auto_data($jarak, $list_sekolah ){
    require('connection.php');
    // require_once('proses_ahp_2.php');
    $sql_sekolah = "SELECT * FROM info_sekolah where status_sekolah = 'Tervalidasi'";
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
        $nama_kriteria = $kriteria['nama_kriteria'];;
        $hasil_data[$kriteria['nama_kriteria']] = array(); 
        $hasil_jadi[$kriteria['nama_kriteria']] = array(); 
        
        if($id_kriteria != 3 && $id_kriteria != 4){
            $sql_subkriteria = "SELECT * FROM kriteria_detail kd where kd.kriteria_idkriteria =". $id_kriteria;
            $result_subkriteria = $conn->query($sql_subkriteria);
            while ($subkriteria = $result_subkriteria->fetch_assoc()){
               
                $list_subkriteria[$nama_kriteria][] = $subkriteria['detail'];
                for ($i=0; $i < sizeof($id_sekolah); $i++) { 
                    for ($j=0; $j <= $i; $j++) {
                        if($id_sekolah[$i] != $id_sekolah[$j]){
                            $n_s1 = "";
                            $n_s2 = "";

                            $sql_s1 = "SELECT * FROM info_sekolah s INNER JOIN info_sekolah_has_kriteria_detail sk on s.npsn=sk.info_sekolah_idinfo_sekolah INNER JOIN kriteria_detail kd on sk.kriteria_detail_iddetail_kriteria= kd.iddetail_kriteria WHERE s.npsn= 	
                            ".$id_sekolah[$i]." AND kd.iddetail_kriteria = ". $subkriteria['iddetail_kriteria'];
                            $result_s1 = $conn->query($sql_s1);
                            while ($s1 = $result_s1->fetch_assoc()){
                                // echo "Nama Sekolah =".$nama_sekolah[$i]." nilai=". $s1['nilai'];
                                // echo "<br>";
                                $n_s1 =  $s1['nilai'];
                                if($n_s1 == 0){
                                    $n_s1 = 1;
                                }
                            }

                            $sql_s2 = "SELECT * FROM info_sekolah s INNER JOIN info_sekolah_has_kriteria_detail sk on s.npsn=sk.info_sekolah_idinfo_sekolah INNER JOIN kriteria_detail kd on sk.kriteria_detail_iddetail_kriteria= kd.iddetail_kriteria WHERE s.npsn= 	
                            ".$id_sekolah[$j]." AND kd.iddetail_kriteria = ". $subkriteria['iddetail_kriteria'];
                            $result_s2 = $conn->query($sql_s2);
                            while ($s2 = $result_s2->fetch_assoc()){
                                // echo "Nama Sekolah =".$nama_sekolah[$j]." nilai=". $s2['nilai'];
                                // echo "<br>";
                                $n_s2 =  $s2['nilai'];
                                if($n_s2 == 0){
                                    $n_s2 = 1;
                                }
                            }

                            $hasil = $n_s1/$n_s2;

                            if($id_kriteria == 5){
                               
                                $temp = array('sekolah_1' => $nama_sekolah[$i], 'sekolah_2' => $nama_sekolah[$j], 'bobot' => $n_s2/$n_s1);                   
                                $hasil_data[$kriteria['nama_kriteria']][$subkriteria['detail']][] = $temp;
                                $temp = array('sekolah_1' => $nama_sekolah[$j], 'sekolah_2' => $nama_sekolah[$i], 'bobot' => $n_s1/$n_s2);                   
                                $hasil_data[$kriteria['nama_kriteria']][$subkriteria['detail']][] = $temp;
                            }
                            else{
                               
                                $temp = array('sekolah_1' => $nama_sekolah[$i], 'sekolah_2' => $nama_sekolah[$j], 'bobot' => $n_s1/$n_s2);                   
                                $hasil_data[$kriteria['nama_kriteria']][$subkriteria['detail']][] = $temp;
                                $temp = array('sekolah_1' => $nama_sekolah[$j], 'sekolah_2' => $nama_sekolah[$i], 'bobot' => $n_s2/$n_s1);                   
                                $hasil_data[$kriteria['nama_kriteria']][$subkriteria['detail']][] = $temp;
                            }                            
                        }                        
                    }
                }                                                                                                 
            }                        
        }
        elseif($id_kriteria == 3){
            for ($i=0; $i < sizeof($id_sekolah); $i++) { 
                for ($j=0; $j <= $i; $j++) {
                    $n_s1 = 0;
                    $n_s2 = 0;
                    if($id_sekolah[$i] != $id_sekolah[$j]){
                        $sql_ekstra_1= "SELECT * FROM ekstrakurikuler e INNER JOIN ekstrakurikuler_has_info_sekolah es on e.idekstrakurikuler=es.ekstrakurikuler_idekstrakurikuler WHERE es.info_sekolah_idinfo_sekolah =".$id_sekolah[$i];
                        $result_ekstra_1 = $conn->query($sql_ekstra_1);
                        $n_s1 = $result_ekstra_1->num_rows;

                        $sql_ekstra_2= "SELECT * FROM ekstrakurikuler e INNER JOIN ekstrakurikuler_has_info_sekolah es on e.idekstrakurikuler=es.ekstrakurikuler_idekstrakurikuler WHERE es.info_sekolah_idinfo_sekolah =".$id_sekolah[$j];
                        $result_ekstra_2 = $conn->query($sql_ekstra_2);
                        $n_s2 = $result_ekstra_2->num_rows;

                        $rating_1 = Rating($id_sekolah[$i], "Ekstrakurikuler");
                        $rating_2 = Rating($id_sekolah[$j], "Ekstrakurikuler");

                        // if($rating_1 > $rating_2){
                        //     $n_s1 += (substr($rating_1,0,1) - substr($rating_2,0,1))+1;
                        // }
                        // elseif ($rating_1 < $rating_2){
                        //     $n_s2 += (substr($rating_2,0,1) - substr($rating_1,0,1))+1;
                        // }
                        // else{
                        //     $n_s1 += 0;
                        //     $n_s2 += 0;
                        // }
                        // echo $nama_sekolah[$i] ." = ". $n_s1;
                        // echo "<br>";
                        // echo $nama_sekolah[$j] ." = ". $n_s2;
                        // echo "<br><br>";
                       
                        $temp = array('sekolah_1' => $nama_sekolah[$i], 'sekolah_2' => $nama_sekolah[$j], 'bobot' => $n_s1/$n_s2);                   
                        $hasil_data[$kriteria['nama_kriteria']][] = $temp;
                        $temp = array('sekolah_1' => $nama_sekolah[$j], 'sekolah_2' => $nama_sekolah[$i], 'bobot' => $n_s2/$n_s1);                   
                        $hasil_data[$kriteria['nama_kriteria']][] = $temp;
                    }
                }
            }
        }
        elseif($id_kriteria == 4){
            for ($i=0; $i < sizeof($id_sekolah); $i++) { 
                for ($j=0; $j <= $i; $j++) {
                    $n_s1 = 0;
                    $n_s2 = 0;
                    if($id_sekolah[$i] != $id_sekolah[$j]){                        
                        $n_s1 = $jarak[$i];
                        $n_s2 = $jarak[$j];

                        // print_r($jarak[$i].' '.$jarak[$j].'<br>');
                        // print_r($n_s1/$n_s2.'<br>');
                        // print_r($n_s2/$n_s1.'<br>');
                     
                        $temp = array('sekolah_1' => $nama_sekolah[$i], 'sekolah_2' => $nama_sekolah[$j], 'bobot' => $n_s1/$n_s2);                   
                        $hasil_data[$kriteria['nama_kriteria']][] = $temp;
                        $temp = array('sekolah_1' => $nama_sekolah[$j], 'sekolah_2' => $nama_sekolah[$i], 'bobot' => $n_s2/$n_s1);                   
                        $hasil_data[$kriteria['nama_kriteria']][] = $temp;
                    }
                }
            }
        }



        //$list_sekolah = ["SD YBPK-3", "SD RADEN PAKU 2","SD MAJU JAYA", "SD Al Azhar Kepala Gading"];
        

        if($id_kriteria != 3 && $id_kriteria != 4){
            $subtable = get_table_subkriteria($nama_kriteria);
            // die();
            $VE_Crit = proses_ahp($subtable);
            //console_log($nama_kriteria);
            //console_log($VE_Crit);
            $VE_alts = array();
            for ($i=0; $i < sizeof($list_subkriteria[$nama_kriteria]); $i++) { 
                $datas = $hasil_data[$nama_kriteria][$list_subkriteria[$nama_kriteria][$i]];
                $coba_res = get_table_sekolah($list_sekolah,$datas);
                $hasil_VE_ALT = proses_ahp($coba_res);
                $CR_sub = Consistancy_Ratio($coba_res,$hasil_VE_ALT);
               // console_log( $hasil_VE_ALT);
                $VE_alts[$list_subkriteria[$nama_kriteria][$i]][0] = $hasil_VE_ALT; 
                $hasil_jadi[$nama_kriteria][$list_subkriteria[$nama_kriteria][$i]][0] = $hasil_VE_ALT; 
                $hasil_jadi[$nama_kriteria][$list_subkriteria[$nama_kriteria][$i]][1] = $CR_sub;                            
            }  
            
            //console_log( $VE_alts);
            $result = WSM($VE_Crit,$VE_alts,$list_subkriteria[$nama_kriteria],$list_sekolah);
            //console_log($result);
            for ($i=0; $i < sizeof($result) ; $i++) { 
                //console_log($result[$i]['hasil']);
                $hasil_jadi[$nama_kriteria][0][] = $result[$i]['hasil'];
            }                              
        }
        elseif($id_kriteria == 3 || $id_kriteria == 4){
           // console_log($nama_kriteria);
            $datas = $hasil_data[$nama_kriteria];
            $tabel = get_table_sekolah($list_sekolah,$datas);
            $hasil_VE_ALT = proses_ahp($tabel);
            $CR_sub = Consistancy_Ratio($tabel,$hasil_VE_ALT);
            //console_log($hasil_VE_ALT);
            $hasil_jadi[$nama_kriteria][0] = $hasil_VE_ALT;
            $hasil_jadi[$nama_kriteria][1] = $CR_sub;
        }
        
        
        
    }
    
    //console_log($hasil_jadi);
    return $hasil_jadi;

}

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



function get_table_sekolah($array_sekolah,$temp_array_ahp){
    $arr_sekolah = array();
    $kriteria = $temp_array_ahp;
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












$hasil_jadi = array();

$crit = get_table_kriteria($list_kriteria);
$VE_Crit = proses_ahp($crit);
$CR_Crit = Consistancy_Ratio($crit,$VE_Crit);
//console_log($VE_Crit);
$hasil_jadi['VE_CRIT'] = $VE_Crit;
$hasil_jadi['CR_CRIT'] = $CR_Crit;

$VE_ALT = array();
$autos = auto_data($jarak, $list_sekolah );
//console_log($autos);
$hasil_jadi['VE_ALT'] = $autos;



$result = WSM($VE_Crit,$autos,$list_kriteria,$list_sekolah);
//console_log($result);
rsort($result);

$hasil_jadi['Hasil_jadi'] = $result;
echo json_encode($hasil_jadi);




function console_log($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}
?>