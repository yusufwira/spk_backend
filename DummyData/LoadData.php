<?php    



    // Load Data Dummy School Finder
    MasterData();
    // DummyAdminSekolah();
    // DummyInfoSekolah();
    // DummyEsktrkurikuler();
    // DummyDetailSekolah();    

    function MasterData(){
        $ekskul = ['Melukis','Drum Band','Tapak Suci','Karate,Taekwondo','Paduan Suara','Pramuka','Robotika',
        'Tari','Tataboga','Mengaji','Musik','Sepak Bola','Basket','Softball','Kerajinan Tangan',
        'Menjahit','Nasyid','Karawitan','Fotografi / Sinematografi','Mading','Paskibra','Komputer',
        'Korahanian','Bahasa','Drama/Teather','Multimedia', 'Tenis', 'Tenis Meja', 'Catur', 'Memanah', 'Berenag', 'Futsal'];

        require('../connection.php');
        $delete = $conn->prepare("DELETE FROM ekstrakurikuler ");
        $delete->execute();

        $stmt = $conn->prepare("INSERT INTO ekstrakurikuler (idekstrakurikuler, nama_eks) VALUES (?, ?)");
        $stmt->bind_param("is", $id, $nama);

        for ($i=0; $i < sizeof($ekskul) ; $i++) { 
            $id = $i+1;
            $nama = $ekskul[$i];
            $stmt->execute();
        }
    }
   

   
    function DummyAdminSekolah()
    {
        require('../connection.php');
        $stmt = $conn->prepare("INSERT INTO users (id_users,username, password,hak_akses, nama_user) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $id, $username, $passwod, $hak, $namaUser);
        for ($i=0; $i < 5 ; $i++) { 
            $id = '10'.$i;
            $username = 'dummy_sekolah_'.$i;
            $passwod = md5('sekolah');
            $hak = 'admin_sekolah';
            $namaUser = 'Admin Sekolah'.$id;
            if ($stmt->execute() == true) {
                echo $username. " Successfuly";
                echo '<br>';
            } else {
                echo $username. " does exist in database";
                echo '<br>';
            }
        }
        $stmt->close();
    }



    function DummyInfoSekolah()
    {
        require('../connection.php');
        $fh = fopen("sekolah.csv", "r");
        $header = fgetcsv($fh);
        $faq = [];
        while ( $data = fgetcsv($fh))   {
            $faq [ $data[0] ] = array_combine($header, $data);
        }
        fclose($fh);    
    
        // INSERT INFO SEKOLAH
        $stmt = $conn->prepare("INSERT INTO info_sekolah (npsn,nama_sekolah,alamat_sekolah,notelp_sekolah,kecamatan,agama,nama_kepala_sekolah,jam_sekolah,status_sekolah,keterangan_status_sekolah,users_id_users) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssi", $npsp, $namaSekolah, $alamat, $telp, $kecamatan, $agama, $namaKepalaSekolah, $jamSekolah, $statusSekolah, $keterangan, $idUser);
    
        // INSERT FOTO SEKOLAH
        $foto = $conn->prepare("INSERT INTO foto_sekolah (idfoto_sekolah,nama_foto, extention, info_sekolah_idinfo_sekolah) VALUES (?, ?, ?, ?)");
        $foto->bind_param("isss", $idFoto, $namaFoto, $ext, $npsp);
    
        for ($i=0; $i < sizeof($faq); $i++) { 
            $npsp = $faq[$i]['npsn'];
            $namaSekolah = $faq[$i]['nama_sekolah'];
            $alamat = $faq[$i]['alamat_sekolah'];
            $telp = $faq[$i]['notelp_sekolah'];
            $kecamatan = $faq[$i]['kecamatan'];
            $agama = $faq[$i]['agama'];
            $namaKepalaSekolah = $faq[$i]['nama_kepala_sekolah'];
            $jamSekolah = $faq[$i]['jam_sekolah'];
            $statusSekolah = $faq[$i]['status_sekolah'];
            $keterangan = $faq[$i]['keterangan_status_sekolah'];
            $idUser = '10'.$i;
            
            $idFoto = '100'.$i;
            $namaFoto = 'default';
            $ext = 'jpg';           
            
            if ($stmt->execute() == true) {                
                echo $faq[$i]['nama_sekolah']. " Successfuly";
                echo '<br>';
            } else {
                echo $faq[$i]['nama_sekolah']. " does exist in database";
                echo '<br>';
            }  
            
            $foto->execute();
        }    
        $stmt->close(); 
        $foto->close();
    }


    function DummyEsktrkurikuler()
    {
        require('../connection.php');
        $fh = fopen("ekstrakrikuler.csv", "r");
        $header = fgetcsv($fh);
        $faq = [];
        while ( $data = fgetcsv($fh))   {
            $faq [ $data[0] ] = array_combine($header, $data);
        }
        fclose($fh);    

        // INSERT
        $stmt = $conn->prepare("INSERT INTO ekstrakurikuler_has_info_sekolah (ekstrakurikuler_idekstrakurikuler,info_sekolah_idinfo_sekolah) VALUES (?, ?)");
        $stmt->bind_param("ii", $idEks, $idSekolah);

        for ($i=0; $i < sizeof($faq); $i++) { 
            $idEks = $faq[$i]['idEks'];
            $idSekolah = $faq[$i]['idSekolah'];        
            
            if ($stmt->execute() == true) {
                echo "Ekstrakurikuler ".$idSekolah." Successfuly";
                echo '<br>';
            } else {
                echo "Ekstrakurikuler ".$idSekolah." does exist in database";
                echo '<br>';
            }        
        }    
        $stmt->close();
    }


    function DummyDetailSekolah()
    {
        require('../connection.php');
        $fh = fopen("detailSekolah.csv", "r");
        $header = fgetcsv($fh);
        $faq = [];
        while ( $data = fgetcsv($fh))   {
            $faq [ $data[0] ] = array_combine($header, $data);
        }
        fclose($fh);    

        // INSERT
        $stmt = $conn->prepare("INSERT INTO info_sekolah_has_kriteria_detail (info_sekolah_has_kriteria_detail, kriteria_detail_iddetail_kriteria, nilai) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $idSekolah, $idDetai, $nilai);

        for ($i=0; $i < sizeof($faq); $i++) { 
            $idDetail = $faq[$i]['idDetail'];
            $idSekolah = $faq[$i]['idSekolah'];
            $nilai = $faq[$i]['nilai'];
            
            if ($stmt->execute() == true) {
                echo "Detail ".$idSekolah." Successfuly";
                echo '<br>';
            } else {
                echo "Detail ".$idSekolah." does exist in database";
                echo '<br>';
            }           
        }    
        $stmt->close();
    }



?>