<?php
require_once('header.php');
if (isset($_GET["q"])) {
    $sayfa = $_GET["q"];

    switch ($sayfa) {
	case "sub-etha":
	    echo "fasilite mi? fasilite ne arar la bazarda";
	    break;

	case "kontrol merkezi":
	    require("kontrol.php");
	    break;

	case "iletişim":
	    require("iletisim.php");
	    break;

	case "ben":
	    echo "ben get verisi aldım";
	    break;

	case "?":
	    require("saibe.php");
	    break;

	case ":)":
	    require("sukela.php");
	    break;

	case "yeni kullanıcı":
	    require("yeni_kullanici.php");
	    break;

	case "istatistikler":
	    require("istatistikler.php");
	    break;

	default:
	    echo "ben main default action.";
    }
} else {


    $sql = "SELECT entry_baslik
				FROM tbl_entries
				ORDER BY RAND( ) 
				LIMIT 1 ";


    $result = mysql_query($sql, $con);
    $row = mysql_fetch_array($result);
    print "<script>";
    print "window.location='goster.php?konu=" . urlencode($row["entry_baslik"]) . "'";
    print "</script>";
    mysql_close($con);
}
?>
</body>
</html>