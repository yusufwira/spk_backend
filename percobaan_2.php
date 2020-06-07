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
        <table>
            <thead >
            <tr id="head">
            </tr>
            </thead>
            <tbody>
            <tr id='body'></tr>
            </tbody>
        </table>
            
    </body>
    <script>
        var list_kriteria = [];
         $("#kriteria").change(function () { 
            var choose = $(this).find('option:selected').val();
            list_kriteria.push(choose);
            console.log(list_kriteria);
            $("th").remove();
    
            for (let index = 0; index < list_kriteria.length; index++) {                
                $('#head').append("<th>"+list_kriteria[index]+"</th>")
                for (let j = 0; j < list_kriteria.length; j++) {                   
                    $('#body').append("<td>"+list_kriteria[index]+"</td>")
                }
            }
            
         });

         $("#coba").click(function () { 
             alert("baa");
         });

    </script>
</html>
