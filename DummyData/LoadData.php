<?php    



    // Load Data Dummy School Finder
    
    DummyInfoSekolah();
    MasterData();

    function MasterData(){
        $ekskul = ['Melukis','Drum Band','Tapak Suci','Karate','Taekwondo','Paduan Suara','Pramuka','Robotika',
        'Tari','Tataboga','Mengaji','Musik','Sepak Bola','Basket','Softball','Kerajinan Tangan',
        'Menjahit','Nasyid','Karawitan','Fotografi / Sinematografi','Mading','Paskibra','Komputer',
        'Korahanian','Bahasa','Drama/Teather','Multimedia', 'Tenis', 'Tenis Meja', 'Catur', 'Memanah', 'Renang', 
        'Futsal', 'Kolintang', 'Gamelang'];

        require('../connection.php');
        $delete = $conn->prepare("DELETE FROM ekstrakurikuler ");
        $delete->execute();

        $stmt = $conn->prepare("INSERT INTO ekstrakurikuler (idekstrakurikuler, nama_eks, keterangan) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $id, $nama, $ket);

        for ($i=0; $i < sizeof($ekskul) ; $i++) { 
            $id = $i+1;
            $nama = $ekskul[$i];
            $ket = 'Tervalidasi';
            $stmt->execute();
        }
    }  
    
    function DummyInfoSekolah(){
        DeleteDummyInfoSeskolah();
        AddDummyInfoSekolah();
        DummyDetailSekolah();
        DummyEsktrkurikuler();
    }

    function DeleteDummyInfoSeskolah()
    {
        require('../connection.php');


        // DELETE RATING
        $rating = $conn->prepare("DELETE FROM rating ");
        $rating->execute();

        // DELETE REVIEW
        $review = $conn->prepare("DELETE FROM review ");
        $review->execute();


        // DELETE FOTO SEKOLAH
        $deleteFoto = $conn->prepare("DELETE FROM foto_sekolah ");
        $deleteFoto->execute();

         // DELETE Sekolah has Esktra
        $ekstra = $conn->prepare("DELETE FROM ekstrakurikuler_has_info_sekolah ");
        $ekstra->execute();

        // DELETE Info SEKOLAH detail
        $detail = $conn->prepare("DELETE FROM info_sekolah_has_kriteria_detail ");
        $detail->execute();

        // DELETE INFO SEKOLAH
        $deleteInfo = $conn->prepare("DELETE FROM info_sekolah ");
        $deleteInfo->execute();

         // DELETE ADMIN SEKOLAH
         $deleteUser = $conn->prepare("DELETE FROM users WHERE hak_akses != 'Super_Admin'");
         $deleteUser->execute();

        //  print_r($deleteInfo->execute());

    }

    function AddDummyInfoSekolah()
    {
        require('../connection.php');
        $fh = fopen("sekolah.csv", "r");
        $header = fgetcsv($fh);
        $faq = [];
        while ( $data = fgetcsv($fh))   {
            $faq [ $data[0] ] = array_combine($header, $data);
        }
        fclose($fh);         


        // INSERT ADMIN SEKOLAH
        $admin = $conn->prepare("INSERT INTO users (id_users,username, password,hak_akses, nama_user, photo) VALUES (?, ?, ?, ?, ?, ?)");
        $admin->bind_param("isssss", $id, $username, $passwod, $hak, $namaUser,  $photo);
    
        // INSERT INFO SEKOLAH
        $stmt = $conn->prepare("INSERT INTO info_sekolah (npsn,nama_sekolah,alamat_sekolah,notelp_sekolah,kecamatan,agama,nama_kepala_sekolah,jam_sekolah,status_sekolah,keterangan_status_sekolah,users_id_users) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssi", $npsp, $namaSekolah, $alamat, $telp, $kecamatan, $agama, $namaKepalaSekolah, $jamSekolah, $statusSekolah, $keterangan, $idUser);
    
        // INSERT FOTO SEKOLAH
        $foto = $conn->prepare("INSERT INTO foto_sekolah (idfoto_sekolah,nama_foto, extention, info_sekolah_idinfo_sekolah) VALUES (?, ?, ?, ?)");
        $foto->bind_param("isss", $idFoto, $namaFoto, $ext, $npsp);
    
        for ($i=0; $i < sizeof($faq); $i++) { 
            $id = '10'.$i;
            $username = $faq[$i]['npsn'];
            $passwod = md5('sekolah');
            $hak = 'admin_sekolah';
            $namaUser = 'Admin Sekolah'.$id;
            $photo = 'default.jpg';


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
            $namaFoto = '1';
            $ext = 'jpg';   
            
            if ($admin->execute() == true) {
                echo $username. " Successfuly";
                echo '<br>';
            } else {
                echo $username. " does exist in database";
                echo '<br>';
            }
            
            if ($stmt->execute() == true) {                
                echo $faq[$i]['nama_sekolah']. " Successfuly";
                echo '<br>';
            } else {
                echo $faq[$i]['nama_sekolah']. " does exist in database";
                echo '<br>';
            }  
            
            $foto->execute();
        }    
        $admin->close();
        $stmt->close(); 
        $foto->close();
    }


    function DummyEsktrkurikuler()
    {
        require('../connection.php');
        $fh = fopen("ekstrakurikuler.csv", "r");
        $header = fgetcsv($fh);
        $faq = [];
        while ( $data = fgetcsv($fh))   {
            $faq [ $data[0] ] = array_combine($header, $data);
        }
        fclose($fh);    

        // print_r($faq);

        // // INSERT
        $stmt = $conn->prepare("INSERT INTO ekstrakurikuler_has_info_sekolah (ekstrakurikuler_idekstrakurikuler,info_sekolah_idinfo_sekolah) VALUES (?, ?)");
        $stmt->bind_param("ii", $idEks, $IdSekolah);

        for ($i=0; $i < sizeof($faq); $i++) { 
            $idEks = $faq[$i]['idEks'];
            $IdSekolah = $faq[$i]['IdSekolah'];        
            
            if ($stmt->execute() == true) {
                echo "Ekstrakurikuler ".$IdSekolah." Successfuly";
                echo '<br>';
            } else {
                echo "Ekstrakurikuler ".$IdSekolah." does exist in database";
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
        $details = $conn->prepare("INSERT INTO info_sekolah_has_kriteria_detail (info_sekolah_idinfo_sekolah, kriteria_detail_iddetail_kriteria, nilai) VALUES (?, ?, ?)");
        $details->bind_param("iss", $idSekolah, $idDetail, $nilai);

        for ($i=0; $i < sizeof($faq); $i++) { 
            $idSekolah = $faq[$i]['idSekolah'];
            $idDetail = $faq[$i]['idDetail'];            
            $nilai = $faq[$i]['nilai'];
            
            if ($details->execute() == true) {
                echo "Detail ".$idSekolah." Successfuly";
                echo '<br>';
            } else {
                echo "Detail ".$idSekolah." does exist in database ".$conn->error;
                echo '<br>';
            }           
        }    
        $details->close();
    }



?>