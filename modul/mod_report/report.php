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
                <td><a href='#' onclick='report()' style='text-decoration: none;'>Sales / Penjualan</a></td>
            </tr>
        </table>
        ";
	break;
}

?>

<script>
    function report() {
        window.open("modul/mod_report/selectDate.php", "popuppage", "width=300,height=300,toolbar=no,scrollbars=yes,location=no,statusbar=no,menubar=no,resizable=yes,fullscreen=no");
    }
</script>