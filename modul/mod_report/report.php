<?php
switch($_GET[act]){
	case "report";
        echo"

        <div id='titlepage'>
                <div class = 'title-container'>
                    <div class = 'txttitle'>
                        <h1>Report</h1>
                    </div>
                </div>
        </div>

        <table width='100%'>
            <tr>
                <td><a href='#' onclick='report1()' style='text-decoration: none;'>Sales / Penjualan</a></td>    
            </tr>
            <tr>
                <td><a href='#' onclick='report2()' style='text-decoration: none;'>POR / Pembelian</a></td>
            </tr>
        </table>
        ";
	break;
}

?>

<script>
    function report1() {
        window.open("modul/mod_report/selectDate.php", "popuppage", "toolbar=no,scrollbars=yes,location=no,statusbar=no,menubar=no,resizable=yes,fullscreen=no");
    }
    function report2() {
        window.open("modul/mod_report/selectDate2.php", "popuppage", "toolbar=no,scrollbars=yes,location=no,statusbar=no,menubar=no,resizable=yes,fullscreen=no");
    }
</script>