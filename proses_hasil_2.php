<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: *');
require('connection.php');
require_once('proses_ahp_2.php');
//require_once('proses_jarak.php');

require_once __DIR__ . '/vendor/autoload.php';
use Phpml\Math\Matrix;

// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "ahp_auto_2";

// $conn = new mysqli($servername, $username, $password, $dbname);
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

//   $list_kriteria = ['Fasilitas','Akademis','Ekstrakurikuler','Biaya'];
//   $list_sekolah = ['SD Al-Muttaqien','SD RADEN PAKU','SD PAKIS JAYA'];
$list_kriteria = json_decode($_POST['nama']);
$list_sekolah = json_decode($_POST['sekolah']);



// membuat tabel auto matis
function get_table_kriteria($list_kriteria){
    require('connection.php');
    //$conn = new mysqli("localhost", "root", "", "ahp_auto_2");

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



function auto_data(){
    require('connection.php');
    //$conn = new mysqli("localhost", "root", "", "ahp_auto_2");

    $sql_sekolah = "SELECT * FROM info_sekolah";
    $result_infosekolah = $conn->query($sql_sekolah);
    $id_sekolah = array();
    $z =0;
    while ($sekolah = $result_infosekolah->fetch_assoc()){
        $id_sekolah[$z] = $sekolah['idinfo_sekolah'];
        $nama_sekolah[$z] = $sekolah['nama_sekolah'];
        $z++;
    }

    $temp_array_ahp = array();
    $temp_array_ahp['Fasilitas'] = array();
    $temp_array_ahp['Akademis'] = array();
    $temp_array_ahp['Ekstrakurikuler'] = array();
    $temp_array_ahp['Biaya'] = array();

    for ($i=0; $i < sizeof($id_sekolah); $i++) { 
        for ($j=0; $j <= $i; $j++) {             
            if( $id_sekolah[$i] == $id_sekolah[$j]){

            }
            else{               
                $internet_S1="";$dayaListrik_S1="";$luasTanah_S1="";$besarBangunan_S1="";$jumlahKelas_S1="";
                $jumlahKelasAc_S1 ="";$jumlahLab_S1="";$jumlahPerpus_S1="";
                $sql_detail_1 = "SELECT * FROM info_sekolah WHERE idinfo_sekolah =".$id_sekolah[$i];
                $result_infosekolah_1 = $conn->query($sql_detail_1);                
                while ($sekolah_1 = $result_infosekolah_1->fetch_assoc()){
                    // fasilitas
                    $internet_S1 = $sekolah_1 ['internet'];
                    $dayaListrik_S1 = $sekolah_1 ['daya_listrik'];  
                    $luasTanah_S1 = $sekolah_1 ['luas_tanah']; 
                    $besarBangunan_S1 = $sekolah_1['besar_bangunan'];
                    $jumlahKelas_S1 = $sekolah_1['jumlah_kelas'];
                    $jumlahKelasAc_S1 = $sekolah_1['jumlah_kelas_ac'];
                    $jumlahLab_S1 = $sekolah_1['jumlah_laboratorium'];
                    $jumlahPerpus_S1 = $sekolah_1['jumlah_perpustakaan'];  
                    
                    // akademis
                    $akreditasi_S1 = $sekolah_1['akreditasi'];
                    $kurikulum_S1 = $sekolah_1['kurikulum'];
                    $jumlah_guru_S1 = $sekolah_1['jumlah_guru'];

                    //biaya
                    $uang_gedung_S1 = $sekolah_1['uang_gedung'];
                    $uang_daftar_ulang_S1 = $sekolah_1['uang_daftar_ulang'];
                    $uang_spp_S1 = $sekolah_1['uang_spp'];
                    $uang_seragam_S1 = $sekolah_1['uang_seragam'];

                    // jarak
                    $koor_x_S1 = $sekolah_1['koordinat_X'];
                    $koor_y_S1 = $sekolah_1['koordinat_Y'];

                }
                $internet_S2="";$dayaListrik_S2="";$luasTanah_S2="";$besarBangunan_S2="";$jumlahKelas_S2="";
                $jumlahKelasAc_S2 ="";$jumlahLab_S2="";$jumlahPerpus_S2="";
                $sql_detail_2 = "SELECT * FROM info_sekolah WHERE idinfo_sekolah =".$id_sekolah[$j];
                $result_infosekolah_2 = $conn->query($sql_detail_2);                
                while ($sekolah_2 = $result_infosekolah_2->fetch_assoc()){
                    // fasilitas
                    $internet_S2 = $sekolah_2 ['internet'];
                    $dayaListrik_S2 = $sekolah_2 ['daya_listrik'];  
                    $luasTanah_S2 = $sekolah_2 ['luas_tanah']; 
                    $besarBangunan_S2 = $sekolah_2['besar_bangunan'];
                    $jumlahKelas_S2 = $sekolah_2['jumlah_kelas'];
                    $jumlahKelasAc_S2 = $sekolah_2['jumlah_kelas_ac'];
                    $jumlahLab_S2 = $sekolah_2['jumlah_laboratorium'];
                    $jumlahPerpus_S2 = $sekolah_2['jumlah_perpustakaan'];   
                    
                     // akademis
                     $akreditasi_S2 = $sekolah_2['akreditasi'];
                     $kurikulum_S2 = $sekolah_2['kurikulum'];
                     $jumlah_guru_S2 = $sekolah_2['jumlah_guru'];

                     //biaya
                    $uang_gedung_S2 = $sekolah_2['uang_gedung'];
                    $uang_daftar_ulang_S2 = $sekolah_2['uang_daftar_ulang'];
                    $uang_spp_S2 = $sekolah_2['uang_spp'];
                    $uang_seragam_S2 = $sekolah_2['uang_seragam'];

                    // jarak
                    $koor_x_S2 = $sekolah_2['koordinat_X'];
                    $koor_y_S2 = $sekolah_2['koordinat_Y'];
                }



             
                // Fasilitas                
                $point_fasilitas_S1 = 0;
                $point_fasilitas_S2 = 0;

                // internet
                if($internet_S1 == "ya" && $internet_S2 == "tidak"){
                    $point_fasilitas_S1++;
                }
                elseif($internet_S2 == "ya" && $internet_S1 == "tidak"){
                    $point_fasilitas_S2++;
                }

                // listrik
                if($dayaListrik_S1 > $dayaListrik_S2){
                    $point_fasilitas_S1++;                   
                }
                elseif($dayaListrik_S1 < $dayaListrik_S2){
                    $point_fasilitas_S2++;                    
                }

                // tanah terbuka
                if($luasTanah_S1 > $luasTanah_S2){
                    $point_fasilitas_S1++;                   
                }
                elseif($luasTanah_S1 < $luasTanah_S2){
                    $point_fasilitas_S2++;                   
                }
                // besar bangunan
                if($besarBangunan_S1 > $besarBangunan_S2){
                    $point_fasilitas_S1++;
                }
                elseif($besarBangunan_S1 < $besarBangunan_S2){
                    $point_fasilitas_S2++;
                }

                // jumlah Kelas
                if($jumlahKelas_S1 > $jumlahKelas_S2){
                    $point_fasilitas_S1++;
                }
                elseif($jumlahKelas_S1 < $jumlahKelas_S2){
                    $point_fasilitas_S2++;
                }

                // jumlahkelasac
                if($jumlahKelasAc_S1 > $jumlahKelasAc_S2){
                    $point_fasilitas_S1++;
                }
                elseif($jumlahKelasAc_S1 < $jumlahKelasAc_S2){
                    $point_fasilitas_S2++;
                }

                // jumlah lab
                if($jumlahLab_S1 > $jumlahLab_S2){
                    $point_fasilitas_S1++;
                }
                elseif($jumlahLab_S1 < $jumlahLab_S2){
                    $point_fasilitas_S2++;
                }

                // jumlah perpus
                if($jumlahPerpus_S1 > $jumlahPerpus_S2){
                    $point_fasilitas_S1++;                
                }
                elseif($jumlahPerpus_S2 > $jumlahPerpus_S1){
                    $point_fasilitas_S2++;
                }

                // /////////////////////////////////////////////////////////////////              
                $ress = abs($point_fasilitas_S1 - $point_fasilitas_S2);
                if($ress > 9){
                    $ress = 9;
                }
                elseif($ress == 0){
                    $ress = 1;
                }
                
                if($point_fasilitas_S1 > $point_fasilitas_S2){                                    
                    $fasilitas = array('sekolah_1' => $nama_sekolah[$i], 'sekolah_2' => $nama_sekolah[$j], 'bobot' => $ress);                   
                    array_push($temp_array_ahp['Fasilitas'] , $fasilitas);
                }
                elseif($point_fasilitas_S1 <= $point_fasilitas_S2){                                    
                    $fasilitas = array('sekolah_1' => $nama_sekolah[$j], 'sekolah_2' => $nama_sekolah[$i], 'bobot' => $ress);                
                    array_push($temp_array_ahp['Fasilitas'] , $fasilitas);
                  
                }
   



                // akademis
                if($akreditasi_S1 == "A"){
                    $akreditasi_S1 = 4;
                }
                elseif($akreditasi_S1 == "B"){
                    $akreditasi_S1 = 3;
                }
                elseif($akreditasi_S1 == "C"){
                    $akreditasi_S1 = 2;
                }
                elseif($akreditasi_S1 == "Tidak"){
                    $akreditasi_S1 = 1;
                }
                if($akreditasi_S2 == "A"){
                    $akreditasi_S2 = 4;
                }
                elseif($akreditasi_S2 == "B"){
                    $akreditasi_S2 = 3;
                }
                elseif($akreditasi_S2 == "C"){
                    $akreditasi_S2 = 2;
                }
                elseif($akreditasi_S2 == "Tidak"){
                    $akreditasi_S2 = 1;
                }

                $point_akademis_S1 = 0;
                $point_akademis_S2 = 0;
                if($akreditasi_S1 > $akreditasi_S2){
                    $point_akademis_S1++;
                }
                elseif($akreditasi_S1 < $akreditasi_S2){
                    $point_akademis_S2++;
                }
                if($kurikulum_S1 > $kurikulum_S2){
                    $point_akademis_S1++;
                }
                elseif($kurikulum_S1 < $kurikulum_S2){
                    $point_akademis_S2++;
                }
                if($jumlah_guru_S1 > $jumlah_guru_S2){
                    $point_akademis_S1++;
                }
                elseif($jumlah_guru_S1 < $jumlah_guru_S2){
                    $point_akademis_S2++;
                }
                
                $ress_akademis = abs($point_akademis_S1 - $point_akademis_S2)+1;
                if( $ress_akademis > 9){
                    $ress_akademis = 9;
                }
                              
                if($point_akademis_S1 > $point_akademis_S2){                  
                    $akademis = array('sekolah_1' => $nama_sekolah[$i], 'sekolah_2' => $nama_sekolah[$j], 'bobot' => $ress_akademis);                   
                    array_push($temp_array_ahp['Akademis'] , $akademis);
                }
                elseif($point_akademis_S1 <= $point_akademis_S2){                  
                    $akademis = array('sekolah_1' => $nama_sekolah[$j], 'sekolah_2' => $nama_sekolah[$i], 'bobot' => $ress_akademis);                   
                    array_push($temp_array_ahp['Akademis'] , $akademis);
                }
               
               




                // biaya
                $point_biaya_S1 = 0;
                $point_biaya_S2 = 0;
                if($uang_gedung_S1 > $uang_gedung_S2){             
                    $nilai = ceil($uang_gedung_S1/$uang_gedung_S2);
                    if($nilai == 0 || $nilai == 1){
                        $point_biaya_S1++;
                    }
                    else{
                        if($nilai > 9){
                            $point_biaya_S1 += 9;
                        }
                        else{
                            $point_biaya_S1 += $nilai;
                        }                        
                    }

                }
                elseif($uang_gedung_S1 < $uang_gedung_S2){             
                    $nilai = ceil($uang_gedung_S2/$uang_gedung_S1);
                    if($nilai == 0 || $nilai == 1){
                        $point_biaya_S2++;
                    }
                    else{
                        if($nilai > 9){
                            $point_biaya_S2 += 9;
                        }
                        else{
                            $point_biaya_S2 += $nilai;
                        }                        
                    }
                }

                if($uang_daftar_ulang_S1 > $uang_daftar_ulang_S2){             
                    $nilai = ceil($uang_daftar_ulang_S1/$uang_daftar_ulang_S2);
                    if($nilai == 0 || $nilai == 1){
                        $point_biaya_S1++;
                    }
                    else{
                        if($nilai > 9){
                            $point_biaya_S1 += 9;
                        }
                        else{
                            $point_biaya_S1 += $nilai;
                        }                        
                    }

                }
                elseif($uang_daftar_ulang_S1 < $uang_daftar_ulang_S2){             
                    $nilai = ceil($uang_daftar_ulang_S2/$uang_daftar_ulang_S1);
                    if($nilai == 0 || $nilai == 1){
                        $point_biaya_S2++;
                    }
                    else{
                        if($nilai > 9){
                            $point_biaya_S2 += 9;
                        }
                        else{
                            $point_biaya_S2 += $nilai;
                        }                        
                    }
                }

                if($uang_spp_S1 > $uang_spp_S2){             
                    $nilai = ceil($uang_spp_S1/$uang_spp_S2);
                    if($nilai == 0 || $nilai == 1){
                        $point_biaya_S1++;
                    }
                    else{
                        if($nilai > 9){
                            $point_biaya_S1 += 9;
                        }
                        else{
                            $point_biaya_S1 += $nilai;
                        }                        
                    }
                }
                elseif($uang_spp_S1 < $uang_spp_S2){             
                    $nilai = ceil($uang_spp_S2/$uang_spp_S1);
                    if($nilai == 0 || $nilai == 1){
                        $point_biaya_S2++;
                    }
                    else{
                        if($nilai > 9){
                            $point_biaya_S2 += 9;
                        }
                        else{
                            $point_biaya_S2 += $nilai;
                        }                        
                    }
                }


                if($uang_seragam_S1 > $uang_seragam_S2){             
                    $nilai = ceil($uang_seragam_S1/$uang_spp_S2);
                    if($nilai == 0 || $nilai == 1){
                        $point_biaya_S1++;
                    }
                    else{
                        if($nilai > 9){
                            $point_biaya_S1 += 9;
                        }
                        else{
                            $point_biaya_S1 += $nilai;
                        }                        
                    }
                }
                elseif($uang_seragam_S1 < $uang_seragam_S2){             
                    $nilai = ceil($uang_seragam_S2/$uang_spp_S1);
                    if($nilai == 0 || $nilai == 1){
                        $point_biaya_S2++;
                    }
                    else{
                        if($nilai > 9){
                            $point_biaya_S2 += 9;
                        }
                        else{
                            $point_biaya_S2 += $nilai;
                        }                        
                    }
                }                              
                $ress_biaya = abs($point_biaya_S1 - $point_biaya_S2);
                if( $ress_biaya > 9){
                    $ress_biaya = 9;
                }
                              
                if($point_biaya_S1 < $point_biaya_S2){                  
                    $biaya = array('sekolah_1' => $nama_sekolah[$i], 'sekolah_2' => $nama_sekolah[$j], 'bobot' => $ress_biaya);                   
                    array_push($temp_array_ahp['Biaya'] , $biaya);
                }
                elseif($point_biaya_S1 >= $point_biaya_S1){                  
                    $biaya = array('sekolah_1' => $nama_sekolah[$j], 'sekolah_2' => $nama_sekolah[$i], 'bobot' => $ress_biaya);                   
                    array_push($temp_array_ahp['Biaya'] , $biaya);
                }
               
       
                // ekstrakurukuler
                $point_ekstra_S1 = 0;
                $point_ekstra_S2 = 0;

                $sql_ektra_S1 = "SELECT count(*) as jumlah FROM `ekstrakurikuler_has_info_sekolah` WHERE info_sekolah_idinfo_sekolah =".$id_sekolah[$i];
                $result_ekstra_S1 = $conn->query($sql_ektra_S1);
                $jumla_ekstra_S1 = "";
                while ($ekstra_S1 = $result_ekstra_S1->fetch_assoc()){
                    $jumla_ekstra_S1 = $ekstra_S1['jumlah'];
                }

                $sql_ektra_S2 = "SELECT count(*) as jumlah FROM `ekstrakurikuler_has_info_sekolah` WHERE info_sekolah_idinfo_sekolah =".$id_sekolah[$j];
                $result_ekstra_S2 = $conn->query($sql_ektra_S2);
                $jumla_ekstra_S2 = "";
                while ($ekstra_S2 = $result_ekstra_S2->fetch_assoc()){
                    $jumla_ekstra_S2 = $ekstra_S2['jumlah'];
                }

              
               
                if($jumla_ekstra_S1 > $jumla_ekstra_S2){
                    $ress_ekstra = ceil($jumla_ekstra_S1/$jumla_ekstra_S2);
                }
                elseif($jumla_ekstra_S1 <= $jumla_ekstra_S2){
                    $ress_ekstra = ceil($jumla_ekstra_S2/$jumla_ekstra_S1);
                }
                
                if( $ress_ekstra > 9){
                    $ress_ekstra = 9;
                }
                              
                if($jumla_ekstra_S1 > $jumla_ekstra_S2){                  
                    $ektrakurikuler = array('sekolah_1' => $nama_sekolah[$i], 'sekolah_2' => $nama_sekolah[$j], 'bobot' => $ress_ekstra);                   
                    array_push($temp_array_ahp['Ekstrakurikuler'] , $ektrakurikuler);
                }
                elseif($jumla_ekstra_S1 <= $jumla_ekstra_S2){                  
                    $ektrakurikuler = array('sekolah_1' => $nama_sekolah[$j], 'sekolah_2' => $nama_sekolah[$i], 'bobot' => $ress_ekstra);                   
                    array_push($temp_array_ahp['Ekstrakurikuler'] , $ektrakurikuler);
                }

                // jarak
                
               
            }                             
        }
            
    }
    
    return $temp_array_ahp;
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




$tabel_kreteria = get_table_kriteria($list_kriteria);
$autos = auto_data();

$hasil_jadi = array();
$VE_Crit = proses_ahp($tabel_kreteria);
$CR_Crit = Consistancy_Ratio($tabel_kreteria,$VE_Crit);
$hasil_jadi['VE_CRIT'] = $VE_Crit;
$hasil_jadi['CR_CRIT'] = $CR_Crit;

$VE_ALT = array();
for ($i=0; $i < sizeof($list_kriteria); $i++){
    $arrAlt = get_table_sekolah($list_sekolah,$autos,$list_kriteria[$i]);
    $hasil_VE_ALT = proses_ahp($arrAlt);
    $VE_ALT[$list_kriteria[$i]][0] = $hasil_VE_ALT;
    $VE_ALT[$list_kriteria[$i]][1] = Consistancy_Ratio($arrAlt,$hasil_VE_ALT);
}
$hasil_jadi['VE_ALT'] = $VE_ALT;


$result = WSM($VE_Crit,$VE_ALT,$list_kriteria,$list_sekolah);
rsort($result);
$hasil_jadi['Hasil_jadi'] = $result;
echo json_encode($hasil_jadi)

?>






