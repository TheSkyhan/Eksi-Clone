<?php session_start(); require "header.php" ?>
<html>
    <head>
	<meta name="description" content="Kadim Sözlük" />
	<meta name="keywords" content="kolektif,sözlük" />
	<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="css/sozluk.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.min.js"></script>
    </head>
    <body>
	<script language="javascript">
	    function goPage(url) {
		window.location = "goster.php?konu=" + url.toString();
	    }
	</script>

	<?php
	$login = isset($_SESSION["nick"]);
	if (!$login)
	    die("siteye giriş yapmadan ukte vermek olmaz");

	function strtolower_tr($metin) {
	    $pattern = "/\[youtube\][^\[\]]*\[\/youtube\]/";
	    $count = preg_match_all($pattern, $metin, $matches);

	    $metin = mb_convert_case($metin, MB_CASE_LOWER, "utf-8");

	    foreach ($matches[0] as $eslesme) {

		$metin = str_replace(mb_convert_case($eslesme, MB_CASE_LOWER, "utf-8"), $eslesme, $metin);
	    }
	    return $metin;
	}

	if (isset($_POST["mesaj"])) {

	    $subject = $_POST["konu"];
	    $yazar = isset($_SESSION["nick"]) ? $_SESSION["nick"] : (isset($_COOKIE["nick"]) ? $_COOKIE["nick"] : null);
	    $zaman = date("Y-m-d H:i:s");

	    $sql = "INSERT INTO tbl_ukte (entry_id, entry_baslik, entry_text, entry_yazar, entry_giristarihi, entry_sonedittarihi) VALUES (NULL, '" . $subject . "', '" . mysql_real_escape_string(strtolower_tr($_POST["mesaj"]), $con) . "', '" . $yazar . "', '" . $zaman . "', NULL)";

	    $res = mysql_query($sql, $con);
	    if (!$res)
		echo "ekleyemedim";
	    echo "<script language=javascript>goPage('" . $subject . "')</script>";
	    mysql_close($con);
	}
	?>
	<table width="80%" border="0">
	    <tr>
	    <h2 align="left" style="WORD-BREAK:keep-words;">
		<?php
		echo "<a href=\"goster.php?konu=" . $_GET["konu"] . "\" target=\"main\" style=\"text-decoration:none;color:#000;\" onmouseover='this.style.background=\"yellow\";' onmouseout='this.style.background=\"none\";'>" . $_GET['konu'] . "</a> ";
		?>
	    </h2>
	</tr>
	<tr>
        <form id="ukte" name="ukte" method="post" action="ukte_ekle.php">
            <tr>
                <td>bu başlığın doldurulma isteğine doldurmak isteyenler için not düşmek istediğiniz bir şeyler ("bu bi
                    bitki ama bilemedim", "ne olduğunu biliyorum ama kılım" gibi) varsa buyrun
                </td>
            </tr>
            <tr>
                <td align="left"><textarea rows="5" cols="80" id="mesaj" name="mesaj" maxlength="65535"></textarea></td>
            </tr>
            <tr>
                <td align="left">
                    <input type="submit" id="konu" value="gönder"/>
                    <button onclick="location.href = 'goster.php?konu=<?= strtolower_tr($_GET["konu"]) ?>'">vazgeçtim
                    </button>
                    <input type="hidden" name="konu" value="<?= $_GET["konu"] ?>"/>
                </td>
            </tr>
        </form>
    </tr>
</table>
</body>