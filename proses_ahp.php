<?php
    require_once __DIR__ . '/vendor/autoload.php';
    use Phpml\Math\Matrix;
    
    //  $matrix = new Matrix($_POST['crit']);
    //  $nama = $_POST['nama'];
    function proses_ahp(Array $matrixs, Array $nama, $keterangan){
        //pembuatan tabel
        $matrix = new Matrix($matrixs);
        $arrayMatrix = $matrix->toArray();
        echo "<h2>".$keterangan."</h2>";
        echo "<table style='border: 1px solid black; border-collapse: collapse;'>";
        echo "<tr style='border: 1px solid black;'>
        <th style='border: 1px solid black;'></th>";
        for ($i=0; $i < count($nama) ; $i++) { 
            echo "<th style='border: 1px solid black;'>".$nama[$i]."</th>";
        }
        echo "</tr>";
        for ($i=0; $i < $matrix->getRows(); $i++) {
            echo "<tr style='border: 1px solid black'>";
            echo "<td style='border: 1px solid black;'>".$nama[$i]."</td>";
            for ($j=0; $j < $matrix->getColumns(); $j++) { 
                echo "<td style='color:red; border: 1px solid black;'>".round($arrayMatrix[$i][$j],4)."</td>";
            }
            echo "</tr>";
        }

        //MENGECEK APAKAH MATRIX CONSISTAN ATO TIDAK

        //penjumlahan setiap kolom step 1 normalisasi
        echo "<tr>";
        $array_total_column =[];
        echo "<td style='color:blue'></td>";
        for ($i=0; $i < $matrix->getColumns(); $i++) { 
            $total_column= 0.0;
            foreach ($matrix->getColumnValues($i) as $Value) {
                $total_column += $Value;           
            }
            $array_total_column[] = $total_column;            
            echo "<td style='color:blue'>".round($total_column,4)."</td>";
        }
        echo "</tr>";
        echo "</table>";
        echo "<br>";

        //pembagian hasil penjumlahan pada masing masing bobot step 2 normalisasi
        echo "<table style='border: 1px solid black; border-collapse: collapse;'>";
        echo "<tr style='border: 1px solid black;'>
        <th style='border: 1px solid black;'></th>";
        for ($i=0; $i < count($nama) ; $i++) { 
            echo "<th style='border: 1px solid black;'>".$nama[$i]."</th>";
        }        
        $arr_total_rows = []; 
        $normalisasi = 0.0;
        $kosistancy = true;
        $new_matrix = [];
        for ($i=0; $i < $matrix->getRows(); $i++) { 
            echo "<tr style='border: 1px solid black'>";
            echo "<td style='border: 1px solid black;'>".$nama[$i]."</td>";
            $total_rows=0.0; 
            for ($j=0; $j < $matrix->getColumns(); $j++) {
                $normalisasi = $arrayMatrix[$i][$j]/$array_total_column[$j];
                $total_rows += $normalisasi;  
                echo "<td style='color:green; border: 1px solid black;' width=70px>".round($normalisasi,4)."</td>";
                $new_matrix[$i][$j] = $normalisasi;
            }

            //pengecekan apakah hasil dari setiap baris sama
            $check = $total_rows/$normalisasi;
            echo "</tr>";
            if($check != $matrix->getColumns()){
                $kosistancy = false;
            }

        }
        echo "</table>";

        if($kosistancy == false){
            echo "Matrix Tidak Konsisten";
            echo "<br>";
            $data = Iterasi($matrix, false, $arr = array(), $nama);
            echo "<br>";
            echo "Hasil VE";
            echo "<table style='border: 1px solid black; border-collapse: collapse;'>";
            for ($i=0; $i < sizeof($data); $i++) {
                echo "<tr style='border: 1px solid black;'>";
                echo "<td style='border: 1px solid black;'>".$nama[$i]."</td>";
                echo "<td style='border: 1px solid black;'>".$data[$i]."</td>";            
                echo "</tr>";                  
            }
            echo "</table>";
            echo "<br>";
            return $data;
        }
        else{
            $data = [];
            echo "Matrix Konsisten";
            echo "<br>";
            $Newmatrix = new Matrix($new_matrix);
            $Newmatrix_hasil = $Newmatrix->toArray();
            echo "<br>";
            echo "Hasil VE";
            echo "<table style='border: 1px solid black; border-collapse: collapse;'>";
            for ($i=0; $i < $Newmatrix->getRows(); $i++) { 
                echo "<tr style='border: 1px solid black;'>";
                echo "<td style='border: 1px solid black;'>".$nama[$i]."</td>";
                echo "<td style='border: 1px solid black;'>".$Newmatrix_hasil[$i][0]."</td>";            
                echo "</tr>"; 
                $data[] = $Newmatrix_hasil[$i][0];          
            }
            echo "</table>";
            echo "<br>";
            return $data;
        }
    }


    


    // Iterasi perhitungan jika matrix tidak konsisten
    function Iterasi(Matrix $matrix, bool $check, array $vectorEigen, array $nama){
        $sMatrix = $matrix->multiply($matrix);
        $arrayMatrix = $sMatrix->toArray();
        echo "<br>";
        echo "<table style='border: 1px solid black; border-collapse: collapse;'>";
        echo "<tr style='border: 1px solid black;'>
        <th style='border: 1px solid black;'></th>";
        for ($i=0; $i < count($nama) ; $i++) { 
            echo "<th style='border: 1px solid black;'>".$nama[$i]."</th>";
        }   
        $arr_count_rows= [];
        $total_count_row = 0.0;
        for ($i=0; $i < $sMatrix->getRows(); $i++) { 
            echo "<tr style='border: 1px solid black;'>";
            echo "<td style= 'border: 1px solid black;'>".$nama[$i]."</td>";
            $count_rows= 0.0;
            for ($j=0; $j < $sMatrix->getColumns(); $j++) { 
                echo "<td style='color:red; border: 1px solid black;'>".round($arrayMatrix[$i][$j],4)."</td>";
                $count_rows += $arrayMatrix[$i][$j];
            }
            $arr_count_rows[] = $count_rows;
            $total_count_row += $count_rows;
            echo "</tr>";
        }
        echo "</table>";

       
        $VE= [];
        $normalisasi = 0.0;
        echo "Vector Eigen";
        echo "<br>";
        for ($i=0; $i < sizeof($arr_count_rows) ; $i++) { 
            $normalisasi = round($arr_count_rows[$i]/$total_count_row,4);
            $VE[] = $normalisasi;
            echo $normalisasi;
            echo "<br>";
            
        }
        echo "<br>";
        $sVE= [];
        if($check == false && $vectorEigen == null){
  
            return Iterasi($sMatrix, false, $VE, $nama);
  
        }
        else if ($check == false && $vectorEigen != null){
            echo "Delta VE";
            echo "<br>";
            for ($i=0; $i < sizeof($VE) ; $i++) { 
                $sVE[] = round(abs($VE[$i]-$vectorEigen[$i]),4);
                echo $VE[$i]." - ".$vectorEigen[$i]." = ".round(abs($VE[$i]-$vectorEigen[$i]),4);
                echo "<br>";
            }
            $checks = [];
            for ($i=0; $i < sizeof($sVE) ; $i++) { 
                if($sVE[$i] <= 0.001){
                    $checks[] = 1;                   
                }
                else{
                    $checks[] = 0;
                }
                                 
            }

            if(in_array(0, $checks, TRUE)){
               return Iterasi($sMatrix, false, $VE, $nama);
            }
   
            return $VE;
            
        }
        
        
         

    }


    function Consistancy_Ratio(Array $matrixs, Array $VE){
        $matrix = new Matrix($matrixs);
       
        $array_total_column =[];
        for ($i=0; $i < $matrix->getColumns(); $i++) { 
            $total_column= 0.0;
            foreach ($matrix->getColumnValues($i) as $Value) {
                $total_column += $Value;           
            }
            $array_total_column[] = $total_column;
        }
        $lamdamax = 0.0;
        for($i=0; $i < sizeof($VE); $i++) {           
          $lamdamax += ($array_total_column[$i]*$VE[$i]);
        }
        // return $lamdamax;
     
        $CI = ($lamdamax -  $matrix->getRows())/($matrix->getRows()-1);
        $RI = 0.0;
        if( $matrix->getRows() == 2){
            $RI = 0;
        }
        else if( $matrix->getRows() == 3){
            $RI = 0.58;
        }
        else if( $matrix->getRows() == 4){
            $RI = 0.9;
        }
        else if( $matrix->getRows() == 5){
            $RI = 1.12;
        }
        else if( $matrix->getRows() == 6){
            $RI = 1.24;
        }
        else if( $matrix->getRows() == 7){
            $RI = 1.32;
        }
        else if( $matrix->getRows() == 8){
            $RI = 1.41;
        }
        else if( $matrix->getRows() == 9){
            $RI = 1.45;
        }
        else if( $matrix->getRows() == 10){
            $RI = 1.49;
        }

        // var_dump($CI."/".$RI);die();
        if ($RI == 0) {
            $CR = 0;
        }
        else{
            $CR = $CI/$RI;
        }
        return $CR*100;
    }
    
    
  

    
    
?>