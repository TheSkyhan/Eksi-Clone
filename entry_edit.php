<?php require_once('header.php'); ?>
<script type='text/javascript'>
    function insertAtCursor(myField, myValue) {
//IE support
        if (document.selection) {
            myField.focus();
            sel = document.selection.createRange();
            sel.text = myValue;
        }
//MOZILLA/NETSCAPE support
        else if (myField.selectionStart || myField.selectionStart == '0') {
            var startPos = myField.selectionStart;
            var endPos = myField.selectionEnd;
            myField.value = myField.value.substring(0, startPos)
                    + myValue
                    + myField.value.substring(endPos, myField.value.length);
        } else {
            myField.value += myValue;
        }
    }

</script>
<?php
$login = isset($_SESSION["nick"]);
if (!$login)
    die("siteye giriş yapmadan düzenleme olmaz");
$islem = $_GET["islem"];
$yazar = $_SESSION["nick"];
if ($islem != "düzenle" && $islem != "ispiyon") {
    ?>
    <script type='text/javascript'>
        setTimeout('self.close()', 4000);


        var milisec = 1;
        var seconds = 4;
        function display() {


    	if (milisec <= 0) {
    	    milisec = 9;
    	    seconds -= 1;
    	}
    	if (seconds <= -1) {
    	    milisec = 0;
    	    seconds += 1;
    	} else
    	    milisec -= 1
    	document.title = seconds + "." + milisec + " saniye sonra bu pencere kendi kendini imha edecektir :)";
    	setTimeout('display()', 100);
        }
        display();
    </script>
    <?php
}

function strtolower_tr($metin) {
    $pattern = "/\[youtube\][^\[\]]*\[\/youtube\]/";
    $count = preg_match_all($pattern, $metin, $matches);

    $metin = mb_convert_case($metin, MB_CASE_LOWER, "utf-8");

    foreach ($matches[0] as $eslesme) {

	$metin = str_replace(mb_convert_case($eslesme, MB_CASE_LOWER, "utf-8"), $eslesme, $metin);
    }
    return $metin;
}

if (isset($_POST["submit"])) {
    echo"<script>opener.parent.frames['main'].location.reload();</script>";
    $sql = "UPDATE tbl_entries
    SET entry_text='" . mysql_real_escape_string($_POST["entry_text"]) . "' , entry_sonedittarihi ='" . mysql_real_escape_string($_POST["zaman"]) . "'
    WHERE entry_id=" . intval($_POST["id"]) . " AND entry_yazar = '".  mysql_real_escape_string($yazar)."'";

    $result = mysql_query($sql, $con);
    if (isset($_POST['baslik_duzenle'])) {
	$sql = "UPDATE tbl_entries
    SET entry_baslik='" . mysql_real_escape_string(strtolower_tr($_POST["baslik_duzenle"])) . "' , entry_sonedittarihi ='" . mysql_real_escape_string($_POST["zaman"]) . "'
    WHERE entry_baslik='" . mysql_real_escape_string($_POST["baslik"]) . "'";
	$result = mysql_query($sql, $con);
    }
    if ($result)
	echo "entry başarıyla editlendi";
    mysql_close($con);
}

if (isset($_POST["ispiyon"])) {

    $sql = "INSERT INTO tbl_ispiyon (`isp_ispid`, `isp_entryid`, `isp_kim`, `isp_neden`)
    VALUES (NULL, " . intval($_POST["id"]) . ", '" . mysql_real_escape_string($yazar) . "', '" . mysql_real_escape_string($_POST["ispiyon_text"]) . "')";
    $result = mysql_query($sql, $con);
    if ($result)
	echo intval($_POST["id"]) . " numaralı entry'yi başarıyla ispiyonladınız. Aman sahibi duymasın :)";
    else
	echo "ispiyon sırasında hata oluştu tekrar deneyin";

    mysql_close($con);
}

else if (isset($_GET["id"])) {
    $islem = $_GET["islem"];
    $msgid = intval($_GET["id"]);


    switch ($islem) {
	case "sil":

	    $sql = "SELECT entry_yazar FROM tbl_entries
    WHERE entry_id ='" . $msgid . "'";
	    $result = mysql_query($sql, $con);
	    $row = mysql_fetch_array($result);
	    if (!$_SESSION['mod'] and $row["entry_yazar"] != $yazar)
		die("bu entry sizin değil ayıp değil mi cık cık!");


	    $sql = "SELECT entry_baslik, entry_sirano FROM tbl_entries
    WHERE entry_id ='" . $msgid . "'";

	    $result = mysql_query($sql, $con);
	    $row = mysql_fetch_array($result);

	    $baslik = mysql_real_escape_string($row["entry_baslik"], $con);
	    $sirano = $row["entry_sirano"];


	    $sql = "DELETE FROM tbl_entries
    WHERE entry_id ='" . $msgid . "'";
	    $result = mysql_query($sql, $con);

	    $sql = "UPDATE tbl_entries
    SET entry_sirano=entry_sirano-1 
    WHERE entry_baslik='" . $baslik . "' AND entry_sirano > " . $sirano;

	    $result = mysql_query($sql, $con);
	    if ($result) {
		echo "entry başarıyla silindi";
	    }
	    echo "<script>opener.parent.frames['main'].location.reload();</script>";
	    break;

	case "uktesil":

	    $sql = "SELECT entry_yazar FROM tbl_ukte
    WHERE entry_id ='" . $msgid . "'";
	    $result = mysql_query($sql, $con);
	    $row = mysql_fetch_array($result);
	    if ($row["entry_yazar"] != $yazar)
		die("bu entry sizin değil ayıp değil mi cık cık!");

	    $sql = "DELETE FROM tbl_ukte
    WHERE entry_id ='" . $msgid . "'";
	    $result = mysql_query($sql, $con);
	    if ($result) {
		echo "ukte başarıyla silindi";
	    }

	    echo "<script>opener.parent.frames['main'].location.reload();</script>";
	    break;

	case "şükela":
	    $sql = "UPDATE tbl_entries
    SET entry_iyi=entry_iyi+1 
    WHERE entry_id=" . $msgid;

	    $result = mysql_query($sql, $con);
	    if ($result) {
		echo "bu entry yi şükela olarak belirlediniz.";
	    }
	    break;

	case "şaibeli":
	    $sql = "UPDATE tbl_entries
    SET entry_kotu=entry_kotu+1 
    WHERE entry_id=" . $msgid;

	    $result = mysql_query($sql, $con);
	    if ($result) {
		echo "bu entry yi şaibeli olarak belirlediniz.";
	    }
	    break;

	case "düzenle":

	    $sql = "SELECT entry_yazar FROM tbl_entries
    WHERE entry_id ='" . $msgid . "'";
	    $result = mysql_query($sql, $con);
	    $row = mysql_fetch_array($result);
	    if (!$_SESSION['mod'] and $row["entry_yazar"] != $yazar)
		die("bu entry sizin değil ayıp değil mi cık cık!");

	    $sql = "SELECT entry_baslik, entry_text
    FROM tbl_entries 
    WHERE entry_id=" . $msgid;

	    $result = mysql_query($sql, $con);
	    if ($result) {
		$row = mysql_fetch_array($result);
		$text = $row["entry_text"];
		$baslik = mysql_real_escape_string($row["entry_baslik"], $con);
		?>
		<h2><?= $baslik; ?></h2>
		<br/>
		<p align="right">
		    <input name="button" type="button"
			   onclick="insertAtCursor(document.entry_ekle.entry_text, '[pdf][/pdf]')" value="pdf"
			   title="nota pdf ekleme"/>
		    <input name="button" type="button"
			   onclick="insertAtCursor(document.entry_düzenle.entry_text, '[img][/img]')" value="resim"
			   title="resim ekleme"/>
		    <input name="button" type="button"
			   onclick="insertAtCursor(document.entry_düzenle.entry_text, '[youtube][/youtube]')"
			   value="youtube" title="youtube linki"/>
		    <input name="button" type="button"
			   onclick="insertAtCursor(document.entry_düzenle.entry_text, '(bkz: )')" value="(bkz: )"
			   title="bakınız verme"/>
		    <input name="button" type="button"
			   onclick="insertAtCursor(document.entry_düzenle.entry_text, '``')" value="''"
			   title="akıllı bakınız verme"/>
		    <input name="button" type="button" onclick="insertAtCursor(document.entry_düzenle.entry_text, '`:`')"
			   value="*" title="görünmez bakınız verme"/>
		    <input name="button" type="button"
			   onclick="insertAtCursor(document.entry_düzenle.entry_text, '-----spoiler-----\r\n\r\n\r\n\r\n\r\n-----spoiler-----')"
			   value="--spoiler--" title="spoiler verme"/>
		</p>

		<form id="entry_düzenle" name="entry_düzenle" method="post" action="entry_edit.php">
		    <input type="hidden" id="id" name="id" value="<?= $msgid; ?>"/>
		    <input type="hidden" id="baslik" name="baslik" value="<?= $baslik; ?>"/>
		    <?php if ($_SESSION['mod']) { ?>
		        <input type="text" id="baslik_duzenle" name="baslik_duzenle" value="<?= $baslik; ?>" size="75"/>
		    <?php } ?>
		    <input type="hidden" id="zaman" name="zaman" value="<?= date("Y-m-d H:i:s"); ?>"/>
		    <textarea id="entry_text" name="entry_text" rows="10" cols="70" maxlength="65535"><?= $text ?></textarea>
		    <br/>
		    <input type="submit" name="submit" value="böyle olsun"/>
		</form>
		<?php
		}

		break;

		case "ispiyon":
		?>
		<b>Şu anda ispiyonculuk yapmak üzeresiniz ;)<br/>Bize de nedenini yazar mısınız?</b>
		<form id="entry_ispiyon" name="entry_ispiyon" method="post" action="entry_edit.php">
		    <input type="hidden" id="id" name="id" value="<?= $msgid; ?>"/>
		    <textarea id="ispiyon_text" name="ispiyon_text" rows="10" cols="70"></textarea> <br/>
		    <input type="submit" name="ispiyon" value="ispitle"/>
		</form>
		<?php
		break;

		default:
		    echo "yapacak bisey bulamadim valla abey";
	    }

	    mysql_close($con);
    }
    ?>
</body>