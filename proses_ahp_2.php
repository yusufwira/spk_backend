<?php
    require_once __DIR__ . '/vendor/autoload.php';
    use Phpml\Math\Matrix;
    
 
    function proses_ahp(Array $matrixs){
        $matrix = new Matrix($matrixs);
        $arrayMatrix = $matrix->toArray();
        //MENGECEK APAKAH MATRIX CONSISTAN ATO TIDAK
        //penjumlahan setiap kolom step 1 normalisasi
        $array_total_column =[];
        for ($i=0; $i < $matrix->getColumns(); $i++) { 
            $total_column= 0.0;
            foreach ($matrix->getColumnValues($i) as $Value) {
                $total_column += $Value;           
            }
            $array_total_column[] = $total_column;
        }     

        //pembagian hasil penjumlahan pada masing masing bobot step 2 normalisasi
        $arr_total_rows = []; 
        $normalisasi = 0.0;
        $kosistancy = true;
        $new_matrix = [];
        for ($i=0; $i < $matrix->getRows(); $i++) {        
            $total_rows=0.0; 
            for ($j=0; $j < $matrix->getColumns(); $j++) {
                $normalisasi = $arrayMatrix[$i][$j]/$array_total_column[$j];
                $total_rows += $normalisasi;                
                $new_matrix[$i][$j] = $normalisasi;
            }

            //pengecekan apakah hasil dari setiap baris sama
            $check = $total_rows/$normalisasi;           
            if($check != $matrix->getColumns()){
                $kosistancy = false;
            }

        }       

        if($kosistancy == false){          
            $data = Iterasi($matrix, false, $arr = array());                     
            return $data;
        }
        else{
            $data = [];
            $Newmatrix = new Matrix($new_matrix);
            $Newmatrix_hasil = $Newmatrix->toArray();          
            for ($i=0; $i < $Newmatrix->getRows(); $i++) {                
                $data[] = $Newmatrix_hasil[$i][0];          
            }           
            return $data;
        }
    }
    
    // Iterasi perhitungan jika matrix tidak konsisten
    function Iterasi(Matrix $matrix, bool $check, array $vectorEigen){
        $sMatrix = $matrix->multiply($matrix);
        $arrayMatrix = $sMatrix->toArray();      
        $arr_count_rows= [];
        $total_count_row = 0.0;
        for ($i=0; $i < $sMatrix->getRows(); $i++) {         
            $count_rows= 0.0;
            for ($j=0; $j < $sMatrix->getColumns(); $j++) {               
                $count_rows += $arrayMatrix[$i][$j];
            }
            $arr_count_rows[] = $count_rows;
            $total_count_row += $count_rows;          
        }       

       
        $VE= [];
        $normalisasi = 0.0;     
        for ($i=0; $i < sizeof($arr_count_rows) ; $i++) { 
            $normalisasi = round($arr_count_rows[$i]/$total_count_row,4);
            $VE[] = $normalisasi;          
            
        }      
        $sVE= [];
        if($check == false && $vectorEigen == null){  
            return Iterasi($sMatrix, false, $VE);  
        }
        else if ($check == false && $vectorEigen != null){         
            for ($i=0; $i < sizeof($VE) ; $i++) { 
                $sVE[] = round(abs($VE[$i]-$vectorEigen[$i]),4);              
            }
            $checks = [];
            for ($i=0; $i < sizeof($sVE) ; $i++) { 
                if($sVE[$i] <= 0.0001){
                    $checks[] = 1;                   
                }
                else{
                    $checks[] = 0;
                }
                                 
            }

            if(in_array(0, $checks, TRUE)){
               return Iterasi($sMatrix, false, $VE);
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
          $lamdamax = ($array_total_column[$i]*$VE[$i]);
        }
        return $lamdamax;
     
        $CI = ($lamdamax -  $matrix->getRows()) - ($matrix->getRows() - 1);
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

        $CR = ($CI / $RI)*100;
        return $CR;
    }
    
    
  

    
    
?>