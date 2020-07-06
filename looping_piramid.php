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
    $dbname = "tugas_akhir";

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
        $z++;
    }
    //echo json_encode($id_sekolah);


    for ($i=0; $i < sizeof($id_sekolah); $i++) { 
        for ($j=0; $j <= $i; $j++) { 
            echo $id_sekolah[$i].$id_sekolah[$j];
        }
        echo "<br>";
    }
?>