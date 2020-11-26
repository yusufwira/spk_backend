

<?php
header("Access-Control-Allow-Origin: *");
require('connection.php');




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
            $sql = "SELECT * from kriteria";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {		
                while ($obj = $result->fetch_assoc()) {
                    if ($obj['nama_kriteria'] != 'Lokasi') {
                        echo "<option value='".$obj['nama_kriteria']."'>".$obj['nama_kriteria']."</option>";
                    }                   
                }
            }
           
             ?>
        </select>  

        <select name="sekolah" id="sekolah">
            <option value="">Silahkan Pilih</option>
            <?php  
            $sql3 = "SELECT * from info_sekolah";
            $result3 = $conn->query($sql3);
            if ($result3->num_rows > 0) {		
                while ($obj = $result3->fetch_assoc()) {
                    echo "<option value='".$obj['nama_sekolah']."'>".$obj['nama_sekolah']."</option>";
                }
            }           
             ?>
        </select> 
        <ul id="data_ul" style="display: none">
        <?php
            $sql2 = "SELECT k.nama_kriteria as kriteria_1, k2.nama_kriteria  as kriteria_2, kb.bobot FROM kriteria_bobot kb  INNER JOIN kriteria k on kb.kriteria_1 = k.idKriteria INNER JOIN kriteria k2 on kb.kriteria_2 = k2.idKriteria";
            $result2 = $conn->query($sql2);
              if ($result2->num_rows > 0) {		
                while ($obj = $result2->fetch_assoc()) {
                    echo "<li id='data' data-value1='".$obj['kriteria_1']."-".$obj['kriteria_2']."' data-value2='".$obj['bobot']."'>".$obj['kriteria_1']."-".$obj['kriteria_2']."</li>";
                    //echo "a";
                }
             }
        ?>
    </ul>
        
           
        <div >
            <ul id="list_kriteria">
                
            </ul>
            <ul id="list_sekolah">
                
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

        <div>
            <h3>Hasil Perhitungan</h3>
            <div id="normalisasi">
            </div>
            
        </div>
    </body>
    <script>
    var kriteria = {};
    var arraylist = [];
    var arraysekolah = [];
    
      $("#kriteria").change(function () { 
            $("#list_kriteria").empty();        
            var name = $(this).find('option:selected').val();
            arraylist.push(name);                    
             for (let i = 0; i < arraylist.length; i++) {
                $("#list_kriteria").append("<li>"+arraylist[i]+"</li>");                        
            }                                                   
        });

        $("#sekolah").change(function () { 
            $("#list_sekolah").empty();        
            var name = $(this).find('option:selected').val();
            arraysekolah.push(name);            
             for (let i = 0; i < arraysekolah.length; i++) {
                $("#list_sekolah").append("<li>"+arraysekolah[i]+"</li>");                        
            }                                                   
        });


     
    $("#test").click(function () { 
        var ul = document.getElementById("data_ul");
        var items = ul.getElementsByTagName("li");
      
        for (let i = 0; i < arraylist.length; i++) {          
            var name = $("#body"+arraylist[i]).attr("name");
            $("#head").append("<th>"+arraylist[i]+"</th>");
             $("#body").append("<tr id='body"+arraylist[i]+"'><td>"+arraylist[i]+"</td></tr>");    
            for (let j = 0; j < arraylist.length; j++) {
                for (var y = 0; y < items.length; ++y) {
                   
                    if(items[y].getAttribute('data-value1') == arraylist[i]+"-"+arraylist[j]){
                         $("#body"+arraylist[i]).append("<td name='row="+arraylist[i]+" col="+arraylist[j]+"'>"+items[y].getAttribute('data-value2')+"</td>");
                         console.log("done");
                    }
                    else if(items[y].getAttribute('data-value1') == arraylist[j]+"-"+arraylist[i]){
                        $("#body"+arraylist[i]).append("<td name='row="+arraylist[i]+" col="+arraylist[j]+"'>"+1/items[y].getAttribute('data-value2')+"</td>");
                    }
                }                                          
            }                       
         }                          
    });



    $("#ahp").click(function () { 
        var result = tableToArray(document.getElementsByTagName('table')[0]);
        $.ajax({
            type: "POST",
            data: { nama: arraylist, sekolah:arraysekolah},
            url: "proses_hasil_5_manual.php",
            success: function(data){
                document.getElementById("normalisasi").innerHTML = data;
                // console.log(data);
            }
        });
        
     });


     function tableToArray(table) {
        var result = []
        var rows = table.rows;
        var cells, t;
        

        // Iterate over rows
        for (var i=1, iLen=rows.length; i<iLen; i++) {
            cells = rows[i].cells;
            t = [];

            // Iterate over cells
            for (var j=1, jLen=cells.length; j<jLen; j++) {
            t.push(cells[j].textContent);
            }
            result.push(t);
        }
        return result;
        
        
    }

        
    </script>

</html>
