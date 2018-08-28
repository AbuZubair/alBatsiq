<html>
<head>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <link rel="stylesheet" href="../../css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script>
  $( function() {
    $( "#datepicker" ).datepicker();
	$( "#datepicker2" ).datepicker();
  } );
  
  function preview() {
    var res = document.getElementById("datepicker").value,
        resto = document.getElementById("datepicker2").value,
        tem = res.split("/"),
        temto = resto.split("/");
   // alert(tem[1]+"-"+tem[0]+"-"+tem[2]);
     window.close();
    window.open("showSales.php?from="+ res+"&to="+resto+"&page=1", "popuppage");
   
    }
</script>

<style>

    table{
	color: #000;
	border-width: 1px;
	border-style: solid;
	border-collapse: collapse;
	padding: 0.5em;
	max-width: 100%;
    }
    table th, table td{
        border: 1px solid #ddd;
        padding: 8px;
    }

    table th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
    }

    table tr:nth-child(even){background-color: #f2f2f2;}

    table tr:hover {background-color: #ddd;}

    #btnok{
        background-color: #008CBA;
        position:absolute;
       
        margin-top:10px;
        padding:5px 15px;
    }

</style>

</head>
<body>
    <div>
        <table border="2">
            <tr>
                <td>From</td>
                <td>
                    <input type="text" id="datepicker" name="tgl1">
                </td>
            </tr>
            <tr>
                <td>To</td>
                <td>
                    <input type="text" id="datepicker2" name="tgl2">
                </td>
            </tr>
        </table>

        <button id="btnok" onclick="preview()">Preview</button>
    </div>
</body>
</html>