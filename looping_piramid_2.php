<?php
    for ($i=0; $i < 5 ; $i++) { 
        for ($j=0; $j <= $i; $j++) { 
            echo $i;
            echo $j;
        }
        echo "<br>";
    }
    echo "<br>";
    echo "<br>";
    echo "<br>";
    for ($i=1; $i < 6 ; $i++) { 
        for ($j=1; $j <= $i; $j++) { 
            echo $i;
            echo $j;
            echo "<br>";
        }
        
    }

    echo "<br>";
    echo "<br>";
    echo "<br>";
    echo "<br>";
    echo "<br>";
    echo "<br>";

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ahp_auto_2";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql_sekolah = "SELECT * FROM info_sekolah";
    $result_infosekolah = $conn->query($sql_sekolah);
    $id_sekolah = array();
    $z =0;
    while ($sekolah = $result_infosekolah->fetch_assoc()){
        $id_sekolah[$z] = $sekolah['idinfo_sekolah'];
        $nama_sekolah[$z] = $sekolah['nama_sekolah'];
        $z++;
    }

    //echo json_encode($id_sekolah);
    $temp_array_ahp = array();
    $temp_array_ahp['fasilitas'] = array();
    $temp_array_ahp['akademis'] = array();
    $temp_array_ahp['ekstrakurikuler'] = array();
    $temp_array_ahp['biaya'] = array();
    for ($i=0; $i < sizeof($id_sekolah); $i++) { 
        for ($j=0; $j <= $i; $j++) {             
            if( $id_sekolah[$i] == $id_sekolah[$j]){

            }
            else{
                echo "<b>".$nama_sekolah[$i]."(".$id_sekolah[$i].") dan ".$nama_sekolah[$j]."(".$id_sekolah[$j].") </b>";
                echo "<br>";
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
                }




                echo "<b> Fasilitas </b><br>";
                
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
                
                echo $point_fasilitas_S1." ".$point_fasilitas_S2;
                echo "<br>";
                $ress = abs($point_fasilitas_S1 - $point_fasilitas_S2);
                if($ress > 9){
                    $ress = 9;
                }
                elseif($ress == 0){
                    $ress = 1;
                }
                
                if($point_fasilitas_S1 > $point_fasilitas_S2){                  
                    echo $nama_sekolah[$i]." <b>".$ress."</b> Kali lebih baik dari ".$nama_sekolah[$j];
                    $fasilitas = array('sekolah_1' => $nama_sekolah[$i], 'sekolah_2' => $nama_sekolah[$j], 'bobot' => $ress);
                   
                    array_push($temp_array_ahp['fasilitas'] , $fasilitas);
                }
                if($point_fasilitas_S1 < $point_fasilitas_S2){                  
                    echo $nama_sekolah[$j]." <b>".$ress."</b> Kali  lebih baik dari ".$nama_sekolah[$i];
                    $fasilitas = array('sekolah_1' => $nama_sekolah[$j], 'sekolah_2' => $nama_sekolah[$i], 'bobot' => $ress);
                
                    array_push($temp_array_ahp['fasilitas'] , $fasilitas);
                  
                }
                echo "<br>";
                echo "<br>";



                echo "<b> Akademis </b><br>";
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
                echo $point_akademis_S1." ".$point_akademis_S2;
                echo "<br>";
                $ress_akademis = abs($point_akademis_S1 - $point_akademis_S2)+1;
                if( $ress_akademis > 9){
                    $ress_akademis = 9;
                }
                              
                if($point_akademis_S1 > $point_akademis_S2){                  
                    echo $nama_sekolah[$i]." <b>". $ress_akademis."</b> Kali lebih baik dari ".$nama_sekolah[$j];
                }
                if($point_akademis_S1 < $point_akademis_S2){                  
                    echo $nama_sekolah[$j]." <b>".  $ress_akademis."</b> Kali  lebih baik dari ".$nama_sekolah[$i];
                }
                if($point_akademis_S1 == $point_akademis_S2){                  
                    echo $nama_sekolah[$j]." sama dengan ".$nama_sekolah[$i];
                }
                echo "<br>";
                echo "<br>";




                echo "<b>Biaya</b> <br>";
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
                echo $point_biaya_S1." ".$point_biaya_S2;
                echo "<br>";
                $ress_biaya = abs($point_biaya_S1 - $point_biaya_S2);
                if( $ress_biaya > 9){
                    $ress_biaya = 9;
                }
                              
                if($point_biaya_S1 < $point_biaya_S2){                  
                    echo $nama_sekolah[$i]." <b>". $ress_biaya."</b> Kali lebih baik dari ".$nama_sekolah[$j];
                }
                elseif($point_biaya_S1 >= $point_biaya_S1){                  
                    echo $nama_sekolah[$j]." <b>".  $ress_biaya."</b> Kali  lebih baik dari ".$nama_sekolah[$i];
                }
                echo "<br>";
                echo "<br>";

       
                echo "<b>ektrakurikuler</b> <br>";
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

              
                echo $jumla_ekstra_S1." ".$jumla_ekstra_S2;
                echo "<br>";
                if($jumla_ekstra_S1 > $jumla_ekstra_S2){
                    $ress_ekstra = ceil($jumla_ekstra_S1/$jumla_ekstra_S2);
                }
                elseif($jumla_ekstra_S1 <= $jumla_ekstra_S2){
                    $ress_ekstra = ceil($jumla_ekstra_S2/$jumla_ekstra_S1);
                }
                
                if( $ress_ekstra > 9){
                    $ress_ekstra = 9;
                }
                              
                if($jumla_ekstra_S1 < $jumla_ekstra_S2){                  
                    echo $nama_sekolah[$i]." <b>". $ress_ekstra."</b> Kali lebih baik dari ".$nama_sekolah[$j];
                }
                elseif($jumla_ekstra_S1 >= $jumla_ekstra_S2){                  
                    echo $nama_sekolah[$j]." <b>".  $ress_ekstra."</b> Kali  lebih baik dari ".$nama_sekolah[$i];
                }

                echo "<br>";
                echo "<br>";
                echo "<br>";

              
            }
            
        }
       
    }

    //echo json_encode($temp_array_ahp);


    //percobaan tabel
    $array_sekolah = ['SD Al-Muttaqien','SD YBPK-3','SD PAKIS JAYA'];
    $kriteria = $temp_array_ahp['fasilitas'];
    echo "<table style='border: 1px solid black'> ";
    echo "<tr> <th> Fasilitas </th>";
    for ($i=0; $i <sizeof($array_sekolah) ; $i++) { 
        echo "<th>".$array_sekolah[$i]."</th>";
    }
    echo "</tr>";
    for ($i=0; $i < sizeof($array_sekolah) ; $i++) {         
        echo "<tr>";
        echo "<td>".$array_sekolah[$i]."</td>";
        for ($j=0; $j < sizeof($array_sekolah); $j++) {             
            if($array_sekolah[$i] == $array_sekolah[$j]){
                echo "<td>1</td>";
            }
            else{
               for ($y=0; $y <sizeof($kriteria); $y++) { 
                   if($array_sekolah[$i]==$kriteria[$y]['sekolah_1'] && $array_sekolah[$j]==$kriteria[$y]['sekolah_2']){
                        echo "<td>".$kriteria[$y]['bobot']."</td>";
                   }
                   else if($array_sekolah[$j]==$kriteria[$y]['sekolah_1'] && $array_sekolah[$i]==$kriteria[$y]['sekolah_2']){
                    $bagi = 1/$kriteria[$y]['bobot']; 
                    echo "<td>".$bagi."</td>";
               }
               }
            }
        }  
        echo "</tr>";      
    }
    echo "</table'>";

    echo json_encode($temp_array_ahp['fasilitas']);
   
?>