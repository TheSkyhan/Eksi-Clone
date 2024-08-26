<?php
require_once('header.php');

if (isset($_FILES['eksi']) && isset($_SESSION["nick"])) {
    if (is_uploaded_file($_FILES['eksi']['tmp_name'])) {
	$file = $_FILES['eksi']['tmp_name'];
	$zip = zip_open($file);
	if (is_resource($zip)) {
	    $resource = zip_read($zip);
	    $name = zip_entry_name($resource);

	    $string = "";
	    while ($data = zip_entry_read($resource)) {
		$string .= $data;
	    }
	    $doc = new DOMDocument();
	    $doc->loadXML(preg_replace('/&#x[0-1]?[0-9A-E]/', ' ', $string));
	    $xml = simplexml_load_string($doc->saveXML());
	    foreach ($xml->entries->entry as $entry) {
		$baslik = mysql_real_escape_string($entry->attributes()->title->__toString());
		$zaman = mysql_real_escape_string(date("Y-m-d H:i:s", strtotime($entry->attributes()->date->__toString())));
		$entry = $entry->__toString();
		$text = mysql_real_escape_string($entry);
		$yazar = $_SESSION["nick"];

		if (!preg_match("/^\s*$/", $text)) {

		    $sqlsirabul = "	SELECT MAX(entry_sirano) AS sirano
							FROM tbl_entries
							WHERE entry_baslik = '" . $baslik . "'";

		    $snc = mysql_query($sqlsirabul, $con);

		    if (mysql_num_rows($snc) == 0) {
			$sirano = 1;
		    } else {
			$row = mysql_fetch_array($snc);
			$sirano = $row["sirano"] + 1;
		    }
		    
		    $sql = "INSERT INTO
					tbl_entries (entry_id, entry_sirano, entry_baslik, entry_text, entry_yazar, entry_giristarihi, entry_sonedittarihi, entry_iyi, entry_kotu, entry_eksi) 
					VALUES (NULL, " . $sirano . ", '" . $baslik . "', '" . $text . "', '" . $yazar . "', '" . $zaman . "', NULL , '', '', 1)";
		    mysql_query($sql, $con);
		    mysql_query("DELETE FROM tbl_ukte WHERE entry_baslik='" . $baslik . "'", $con);

		    mysql_query("DELETE FROM tbl_kenar WHERE entry_baslik='" . $baslik . "' AND entry_yazar='" . $yazar . "' AND entry_text ='" . $text . "'", $con);
		}
	    }
	    zip_close($zip);
	    echo "entryleriniz başarıyla sisteme aktarıldı.";
	}
    }
}
?>
<form enctype="multipart/form-data" method="post">
    <table width="50%" border="0">
	<h2 align="left">
	    <?php
	    if (isset($_GET["yazar"]))
		$yazar = htmlspecialchars($_GET["yazar"]);
	    else
		$yazar = htmlspecialchars($_SESSION["nick"]);
	    $words = explode(" ", $yazar);
	    foreach ($words as $baslik)
		echo "<a href=\"goster.php?konu=$baslik\" target=\"main\" style=\"text-decoration:none;color:#000;\" onmouseover='this.style.background=\"yellow\";' onmouseout='this.style.background=\"none\";'>$baslik</a> ";
	    ?>
	</h2>
	<?php if(isset($_SESSION["nick"]) && empty($_GET["yazar"])) { ?>
	<tr>
	    <td align="center">ekşiden geldim abey zipimi alıver: <input type="file" name="eksi"></td>
	</tr>
	<tr>
	    <td align="center"><input type="submit" name="submit" value="Upload Et"></td>
	</tr>
	<?php } ?>
	<tr>
	    <td align="center">kullanıcı aktivitesi</td>
	</tr>
	<?php
	$sql = "SELECT COUNT(entry_id) AS toplam FROM tbl_entries WHERE entry_yazar = '" . mysql_real_escape_string($yazar) . "'";
	$result = mysql_query($sql, $con);
	$row = mysql_fetch_array($result);
	$toplamEntry = $row["toplam"];

	$bugun = date("Y-m-d");
	$birayonce = strtotime($bugun) - 2592000;
	$biraystr = date("Y-m-d H:i:s", $birayonce);

	$sql = "SELECT COUNT(entry_id) AS toplam FROM tbl_entries WHERE entry_yazar = '" . mysql_real_escape_string($yazar) . "' AND entry_giristarihi BETWEEN '" . $biraystr . "' AND '" . $bugun . "'";
	$result = mysql_query($sql, $con);
	$row = mysql_fetch_array($result);
	$birayToplam = $row["toplam"];

	$bugun = date("Y-m-d");
	$birhaftaonce = strtotime($bugun) - 604800;
	$birhaftastr = date("Y-m-d H:i:s", $birhaftaonce);

	$sql = "SELECT COUNT(entry_id) AS toplam FROM tbl_entries WHERE entry_yazar = '" . mysql_real_escape_string($yazar) . "' AND entry_giristarihi BETWEEN '" . $birhaftastr . "' AND '" . $bugun . "'";
	$result = mysql_query($sql, $con);
	$row = mysql_fetch_array($result);
	$birhaftaToplam = $row["toplam"];

	$bugun = date("Y-m-d H:i:s");
	$bugunstr = date("Y-m-d 00:00:00");

	$sql = "SELECT COUNT(entry_id) AS toplam FROM tbl_entries WHERE entry_yazar = '" . mysql_real_escape_string($yazar) . "' AND entry_giristarihi BETWEEN '" . $bugunstr . "' AND '" . $bugun . "'";
	$result = mysql_query($sql, $con);
	$row = mysql_fetch_array($result);
	$bugunToplam = $row["toplam"];

	$sql = "SELECT MAX(entry_giristarihi) AS sonentry FROM tbl_entries WHERE entry_yazar = '" . mysql_real_escape_string($yazar) . "'";
	$result = mysql_query($sql, $con);
	$row = mysql_fetch_array($result);
	$songiri = $row["sonentry"];
	$songiri = date("d-m-Y H:i:s", strtotime($songiri));
	?>
	<tr>
	    <td>
		toplam entry sayısı: <?php echo $toplamEntry; ?><br/>
		son bir aydaki entry sayısı : <?php echo $birayToplam; ?><br/>
		son bir haftadaki entry sayısı : <?php echo $birhaftaToplam; ?><br/>
		bugünkü entry sayısı : <?php echo $bugunToplam; ?><br/>
		son entry girişi : <?php echo $songiri; ?><br/>
	    </td>
	</tr>

	<tr>
	    <td align="center">son entry'leri</td>
	</tr>

	<tr>
	    <td class="left-menu">
		<?php
		$sql = "SELECT entry_baslik, entry_id
				FROM (
					SELECT entry_baslik, entry_giristarihi, entry_id
					FROM tbl_entries
					WHERE  `entry_yazar` =  '" . mysql_real_escape_string($yazar) . "'
					ORDER BY entry_giristarihi DESC
				)tablo LIMIT 0,10";

		$result = mysql_query($sql, $con);

		while ($row = mysql_fetch_array($result)) {
		    ?> <a href="goster.php?konu=<?php echo $row["entry_baslik"]; ?>&msgid=<?php echo $row["entry_id"]; ?>"
    		   target="main"><?php echo $row["entry_baslik"] . " / #" . $row["entry_id"]; ?></a><br/><?php
		   }
		   ?>
	    </td>
	</tr>
	<tr>
	    <td align="center">en beğenilenler</td>
	</tr>

	<tr>
	    <td class="left-menu">
		<?php
		$sql = "SELECT entry_baslik, entry_id, entry_iyi
				FROM (
					SELECT entry_baslik, entry_giristarihi, entry_id, entry_iyi
					FROM tbl_entries
					WHERE  `entry_yazar` =  '" . mysql_real_escape_string($yazar) . "' AND entry_iyi > 10
					ORDER BY entry_giristarihi DESC
				)tablo LIMIT 0,10";

		$result = mysql_query($sql, $con);
		while ($row = mysql_fetch_array($result)) {
		    ?> <a href="goster.php?konu=<?php echo $row["entry_baslik"]; ?>&msgid=<?php echo $row["entry_id"]; ?>"
    		   target="main"><?php echo $row["entry_baslik"] . " / #" . $row["entry_id"] . " / " . $row["entry_iyi"] . " oy"; ?></a>
    		<br/><?php
		}
		?>
	    </td>
	<tr>
	<tr>
	    <td align="center">en kötüler</td>
	</tr>
	<tr>
	    <td class="left-menu">
		<?php
		$sql = "SELECT entry_baslik, entry_id, entry_kotu
				FROM (
					SELECT entry_baslik, entry_giristarihi, entry_id, entry_kotu
					FROM tbl_entries
					WHERE  `entry_yazar` =  '" . mysql_real_escape_string($yazar) . "' AND entry_kotu > 10
					ORDER BY entry_giristarihi DESC
				)tablo LIMIT 0,10";

		$result = mysql_query($sql, $con);
		while ($row = mysql_fetch_array($result)) {
		    ?>
    		<a href="goster.php?konu=<?php echo $row["entry_baslik"]; ?>&msgid=<?php echo $row["entry_id"]; ?>"
    		   target="main"><?php echo $row["entry_baslik"] . " / #" . $row["entry_id"] . " / " . $row["entry_kotu"] . " oy"; ?></a>
    		<br/><?php
		}
		?>
	    </td>
	</tr>
	<tr>
	    <td align="center">ukteler</td>
	</tr>
	<tr>
	    <td class="left-menu">
		<?php
		$sql = "
					SELECT entry_baslik
					FROM tbl_ukte
					WHERE  `entry_yazar` =  '" . mysql_real_escape_string($yazar) . "'
					ORDER BY entry_giristarihi DESC
					LIMIT 0,10";

		$result = mysql_query($sql, $con);
		while ($row = mysql_fetch_array($result)) {
		    ?>
    		<a href="goster.php?konu=<?php echo urlencode($row["entry_baslik"]); ?>"
    		   target="main"><?php echo $row["entry_baslik"]; ?></a><br/><?php
		   }
		   mysql_close($con);
		   ?>
    </table>
</form>
</body>