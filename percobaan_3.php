

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

        <div>
            <h3>Pengecekan Matrik Konsisten </h3>
            <div id="normalisasi">
            </div>
            
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
            }                                                   
        });
     
    $("#test").click(function () { 
        var ul = document.getElementById("data_ul");
        var items = ul.getElementsByTagName("li");
        // for (var i = 0; i < items.length; ++i) {
        //     console.log(items[i].getAttribute('data-value1'));
        // }

      
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
        console.log(result);
        $.ajax({
            type: "POST",
            data: {crit: result, nama: arraylist},
            url: "proses_hasil.php",
            success: function(data){
                //document.getElementById("normalisasi").innerHTML = data;
                console.log(data);
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
