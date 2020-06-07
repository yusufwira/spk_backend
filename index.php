

<?php
header("Access-Control-Allow-Origin: *");
require('connection.php');
$sql = "SELECT * from kriteria";
$result = $conn->query($sql);
?>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <style>
            table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
            }

            td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
            }

          
        </style>
    </head>
    <body>
        <select name="kriteria" id="kriteria">
            <option value="">Silahkan Pilih</option>
            <?php  
            if ($result->num_rows > 0) {		
                while ($obj = $result->fetch_assoc()) {
                    echo "<option value='".$obj['nama_kriteria']."'>".$obj['nama_kriteria']."</option>";
                }
            }
             ?>
        </select>      
        <div >
            <ul id="list">
                
            </ul>
        </div>
        <table id="tabel" >
            <thead>
            <tr id="head" >
                <th>Kriteria</th>
            </tr>
            </thead>
            <tbody id="body" >

            </tbody>

        </table>

        <button id=test>test</button>
        <button id=ahp>Perhitungan Bobot</button>

        <div >
            <h3>Normalisasi</h3>
            <ul id="normalisasi">
                
            </ul>
        </div>

        <div id="lamda" >
            <h3>Consistansy Ratio</h3>
            
        </div>
    </body>
    <script>
    var kriteria = {};
    var arraylist = [];
    var arraybobot =[];
    
      $("#kriteria").change(function () { 
            $("#list").empty();        
            var name = $(this).find('option:selected').val();
            arraylist.push(name);       
            name = {};
            arraybobot =[];
            var cobs=0;       
             for (let i = 0; i < arraylist.length; i++) {
                $("#list").append("<li>"+arraylist[i]+"</li>");  
                var arraycoba = [];
                var hitung = 1;                
                if(i == 0){
                    for (let j = 0; j < arraylist.length; j++) {
                        name= {
                            nama: arraylist[i],
                            bobot: hitung
                        }                      
                        arraycoba.push(name);
                        hitung = hitung + hitung;          
                    }     
                }
                else{
                  
                    for (let j = 0; j < arraylist.length; j++) {
                        name= {
                            nama: arraylist[i],
                            bobot: hitung/Math.pow(2,i)
                        }
                                           
                        arraycoba.push(name);  
                        hitung = hitung + hitung;             
                    } 
                }
                                                           
                //arraybobot[arraylist[i]]=arraycoba;   
                arraybobot.push(arraycoba)
                      
            }
            
            
              
        });
     
    $("#test").click(function () { 
        console.log(arraybobot);
        for (let i = 0; i < arraylist.length; i++) {
            $("#head").append("<th>"+arraylist[i]+"</th>");
            $("#body").append("<tr id='body"+arraylist[i]+"'><td>"+arraylist[i]+"</td></tr>");    
            for (let j = 0; j < arraybobot[i].length; j++) {
                $("#body"+arraylist[i]).append("<td>"+arraybobot[i][j].bobot+"</td>");
                //console.log("#body"+arraylist[i]+arraybobot[i][j].bobot)
            }
           
            
        }
    });

        
            
            
        

        
   

     $("#ahp").click(function () {       
         var normal = [];              
            for (let i = 0; i < arraylist.length; i++) {
                var hitung = 0;
                for (let j = 0; j < arraybobot[i].length; j++) {
                    hitung = hitung + arraybobot[i][j].bobot
                    
                }
                normal.push(hitung);
            }
        console.log(normal);
        var hasilhitung =0
        for(let i = 0; i < normal.length; i++){
            hasilhitung += normal[i];
        }
        console.log(hasilhitung);
        var hitungnormalisasi =0
        var normalisasi =[]
        
        for(let i = 0; i < normal.length; i++){
            hitungnormalisasi = normal[i]/hasilhitung;
            normalisasi.push(hitungnormalisasi);
            $("#normalisasi").append("<li>"+hitungnormalisasi+"</li>");             

        }
        console.log(normalisasi);

        arrJumlah =[];
        
        for (let z = 0; z < arraylist.length; z++) {
            var jumlah = 0
            for (let i = 0; i < arraylist.length; i++) {               
                var bobotop =0
                    for (let j = 0; j < arraybobot[i].length; j++) {
                        bobotop = arraybobot[i][j+z].bobot
                        console.log(bobotop)
                        jumlah += bobotop ;
                        break;
                    }                               
               
            } 
            arrJumlah.push(jumlah)                                           
        }

        console.log(arrJumlah); 

        var kali = 0;
        var lamda =0
        for(let i = 0; i < arrJumlah.length; i++){
            kali = arrJumlah[i]*normalisasi[i];
            lamda += kali

        }

        console.log(lamda); 
        $("#lamda").append("<p> Lamda Max = <b>"+lamda+"</b></p>");  
        var CI = (lamda - arraylist.length) / (arraylist.length -1);
        $("#lamda").append("<p> CI = <b>"+CI+"</b></p>");
        var CR = CI / 0.58;
        $("#lamda").append("<p> CR = <b>"+CR+"</b></p>");
       
        
      

    });
        
    </script>

</html>
