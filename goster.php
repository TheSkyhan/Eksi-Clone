<?php require_once('header.php'); ?>
<head>
    <meta name="description"
          content="<?php echo htmlspecialchars($_GET["konu"]) . " hakkında sözlük yazarlarının düşündükleri"; ?>"/>
    <meta name="keywords" content="
    <?php
    $konu = explode(" ", $_GET["konu"]);
    foreach ($konu as $kelime)
	echo htmlspecialchars($kelime) . " ";
    ?>
	  "/>
    <title><?php echo htmlspecialchars($_GET["konu"]) . " - Kadim Sözlük"; ?></title>

</head>
<body onload="parent.document.title = document.title;">
    <script language="javascript">

        function Show(id) {
            document.getElementById(id).style.visibility = "visible";
        }
        function Hide(id) {
            document.getElementById(id).style.visibility = "hidden";
        }
        function goBottom() {
            window.location = "#bottom";
        }
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
        function popup(url) {
            var width = 650;
            var height = 400;
            var left = (screen.width - width) / 2;
            var top = (screen.height - height) / 2;
            var params = 'width=' + width + ', height=' + height;
            params += ', top=' + top + ', left=' + left;
            params += ', directories=0';
            params += ', location=0';
            params += ', menubar=0';
            params += ', resizable=0';
            params += ', scrollbars=0';
            params += ', status=0';
            params += ', toolbar=0';
            newwin = window.open(url, 'windowname5', params);
            if (window.focus) {
                newwin.focus()
            }
            return false;
        }

        function copyAdress(id, baslik) {
            prompt(id + " numaralı entry\'nin adresi", "http://www.kadimsozluk.com/goster.php?konu=" + encodeURIComponent(baslik) + "&msgid=" + id);
        }
    </script>

    <?php
    $yazar = $_SESSION["nick"];
    $login = isset($_SESSION["nick"]);

    $_SESSION["adres"] = $_SERVER["QUERY_STRING"];

    function strtolower_tr($metin) {
	$patterns = array(
	    
	    '@[^"](http|https|ftp)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(:[a-zA-Z0-9]*)?/?([a-zA-Z0-9\-\._\?\,\'/\\\+&amp;%\$#\=~])*[^\.\,\)\(\s]@',
	    "/\[youtube\][^\[\]]*\[\/youtube\]/i",
	    "/\[img\][^\[\]]*[jpg|png|bmp|jpeg|gif]\[\/img\]/i",
	    "/\[pdf\][^\[\]]*[.pdf]\[\/pdf\]/i"
	);
	$bulundu = false;
	foreach ($patterns as $pattern) {
	    $count = preg_match_all($pattern, $metin, $matches);
	    if ($count) {
		$bulundu = true;
		$metin = mb_convert_case($metin, MB_CASE_LOWER, "utf-8");
		foreach ($matches[0] as $eslesme) {
		    $metin = str_replace(mb_convert_case($eslesme, MB_CASE_LOWER, "utf-8"), $eslesme, $metin);
		}
	    }
	}
	if(!$bulundu)
	    $metin = mb_convert_case($metin, MB_CASE_LOWER, "utf-8");
	return $metin;
    }

    if (isset($_POST['baslik'])) {
	//strip entry ekledikten sonra konu yönlendiğinde doğru gelebilsin diye

	$_GET["konu"] = strtolower_tr($_POST['baslik']);
	$baslik = mysql_real_escape_string($_POST['baslik']);
	$text = mysql_real_escape_string($_POST["entry_text"]);

	$zaman = $_POST["zaman"];

	if ($_REQUEST["submit"] == "yolla") {
	    if (preg_match("/^\s*$/", $text))
		die("boş entry mi? bir daha düşün :)");

	    $sqlsirabul = "	SELECT MAX(entry_sirano) AS sirano
							FROM tbl_entries
							WHERE entry_baslik = '" . $baslik . "'";

	    $snc = mysql_query($sqlsirabul, $con);

	    if (mysql_num_rows($snc) == 0)
		$sirano = 1;
	    else {
		$row = mysql_fetch_array($snc);
		$sirano = $row["sirano"] + 1;
	    }

	    if ($login) {
		$sql = "INSERT INTO
					tbl_entries (entry_id, entry_sirano, entry_baslik, entry_text, entry_yazar, entry_giristarihi, entry_sonedittarihi, entry_iyi, entry_kotu) 
					VALUES (NULL, " . $sirano . ", '" . $baslik . "', '" . $text . "', '" . $yazar . "', '" . $zaman . "', NULL , '', '')";

		mysql_query("DELETE FROM tbl_ukte WHERE entry_baslik='" . $baslik . "'", $con);

		mysql_query("DELETE FROM tbl_kenar WHERE entry_baslik='" . $baslik . "' AND entry_yazar='" . $yazar . "' AND entry_text ='" . $text . "'", $con);
	    }
	}
	//kenar
	else if ($_REQUEST["submit"] == "kenarda dursun") {
	    if (preg_match("/^\s*$/", $text))
		die("boş şeyleri kenarda tutmanın ne anlamı var yeğenim :)");

	    $sqlexist = "SELECT entry_baslik, entry_yazar
						FROM tbl_kenar
						WHERE entry_yazar = '" . $yazar . "' AND entry_baslik = '" . $baslik . "'";

	    $res = mysql_query($sqlexist, $con);

	    //olan kenar mı değişecek yoksa yeni kenar entry mi girilecek
	    if (mysql_num_rows($res) == 0) {
		$sql = "INSERT INTO tbl_kenar (entry_id, entry_baslik, entry_text, entry_yazar, entry_giristarihi, entry_sonedittarihi) VALUES (NULL, '" . $baslik . "', '" . $text . "', '" . $yazar . "', '" . $zaman . "', NULL)";
	    } else {
		$sql = "UPDATE tbl_kenar SET entry_text = '" . $text . "' WHERE entry_baslik ='" . $baslik . "' AND entry_yazar ='" . $yazar . "'";
	    }
	}
	//kenarda durmasın
	else {
	    $sql = "DELETE FROM tbl_kenar WHERE entry_yazar = '" . $yazar . "' AND entry_baslik ='" . $baslik . "'";
	}

	$result = mysql_query($sql, $con);

	if (!$result) {
	    die("entry eklenemedi lütfen daha sonra tekrar deneyin" . mysql_error());
	}
	echo "<script language='javascript'>goBottom()</script>"; //window.location='#bottom'
    }


    if (isset($_GET["konu"]) or isset($_GET["msgid"])) {

	$konu = strtolower_tr(htmlspecialchars_decode($_GET["konu"]));

	if ((isset($_GET["yazar"]))) {
	    $sql = "SELECT *
				FROM tbl_entries 
				WHERE entry_baslik = '" . mysql_real_escape_string($konu) . "' AND entry_yazar IN (SELECT badi_kimle FROM tbl_badi WHERE badi_yazar = '" . $yazar . "') ORDER BY entry_sirano";
	} else if (isset($_GET["zaman"])) {
	    $sql = "SELECT *
				FROM tbl_entries 
				WHERE entry_baslik = '" . mysql_real_escape_string($konu) . "' AND entry_giristarihi >= '" . mysql_real_escape_string($_GET["zaman"]) . "' ORDER BY entry_sirano";
	} else if (isset($_GET["msgid"])) {
	    $sql = "SELECT *
				FROM tbl_entries 
				WHERE entry_id =" . intval($_GET["msgid"]);
	} else if (isset($_GET["kw"])) {

	    $sql = "SELECT *
					FROM tbl_entries 
					WHERE entry_baslik = '" . mysql_real_escape_string($konu) . "' AND entry_text LIKE '%" . mysql_real_escape_string(strtolower_tr($_GET["kw"])) . "%' ORDER BY entry_sirano";
	} else {
	    $sql = "SELECT *
					FROM tbl_entries 
					WHERE entry_baslik = '" . mysql_real_escape_string($konu) . "' ORDER BY entry_sirano";
	}

	$result = mysql_query($sql, $con);
	?>
        <table width="100%" border="0" style="border-color:green;">

    	<tr>
    	<h2 align="left" colspan="3" style="WORD-BREAK:keep-words;">
		<?php
		$words = explode(" ", htmlspecialchars($konu));
		foreach ($words as $baslik)
		    echo '<a href="goster.php?konu=' . $baslik . '" target="main">' . $baslik . '</a> ';
		?>
    	    <div class="fb-like" 
    		 data-href="<?php echo 'http://www.kadimsozluk.com/goster.php?konu=' . $konu; ?>" 
    		 data-layout="button_count" 
    		 data-action="like" 
    		 data-show-faces="true" 
    		 data-share="true">
    	    </div>
    	</h2>
        </tr>
	<?php
	//entry yazılmamış ama ukte var mı yok mu
	if (mysql_num_rows($result) == 0) {
	    $sql = "SELECT *
    FROM tbl_ukte 
    WHERE entry_baslik = '" . $konu . "'";

	    $res = mysql_query($sql, $con);

	    if (mysql_num_rows($res) == 1 && $login) {
		$row = mysql_fetch_array($res);
		?>
	        <tr border="0">
	    	<td width="85%" align="justify" style="word-break: keep-words;">
	    	    bu konuyla ilgili bir tanım girilmemiş<br/><br/>
			<?php echo "ukteyi " . $row["entry_yazar"] . " diye biri " . date("d-m-Y H:i:s", strtotime($row["entry_giristarihi"])) . " 'de vermiş. demiş ki, '" . $row["entry_text"] . "' falan filan."; ?>
	    	    <br/>
	        <center>
		    <?php if ($row["entry_yazar"] == $yazar) { ?>
			<input name="button" type="button"
			       onclick="popup('entry_edit.php?islem=uktesil&id=<?php echo $row["entry_id"] ?>');" value="ukteyi sil"
			       title="sil"/>
			   <?php } ?>
	    	<input name="button" type="button"
	    	       onclick="popup('msg.php?kime=<?php echo $row["entry_yazar"]; ?>&msgid=<?php echo $row["entry_id"]; ?>');"
	    	       value="msg" title="yazara mesaj atın"/>
	        </center>
	    </td>
	    <td width="15%" align="right" style="WORD-BREAK:KEEP-WORDS;vertical-align:top;">
	        <form action="goster.php" method="get">
	    	<fieldset style="overflow:hidden;white-space:nowrap">
	    	    <legend style="font-size:x-small">başlık içinde ara</legend>
	    	    <input type="hidden" id="konu" name="konu" value="<?php echo $konu; ?>"/>
	    	    <input type="text" id="kw" name="kw" style="width:125px"/>
	    	    <input style="width:30px" title="başlık içinde ara" type="submit" value="ara"/>
	    	</fieldset>
	        </form>
	    </td>
	    </tr>
	    <?php
	} else {
	    ?>
	    <tr border="0">
	        <td width="85%" align="justify" style="WORD-BREAK:KEEP-WORDS;">
	    	bu konuyla ilgili bir tanım girilmemiş<br/><br/>
	    	şunları mı demek istemiştiniz?
	    	<div class="left-menu">
			<?php
			$sql = "SELECT DISTINCT entry_baslik FROM tbl_entries WHERE 1";
			$res = mysql_query($sql, $con);
			$alakalilar = array();
			while ($row = mysql_fetch_array($res)) {
			    $dist = levenshtein($baslik, $row['entry_baslik']);
			    $alakalilar [$dist][] = $row['entry_baslik'];
			}
			$count = 0;

			for ($d = 0; $d < 5; $d++) {
			    if (count($alakalilar[$d])) {

				foreach ($alakalilar[$d] as $bas) {
				    ?>
				    <a href="goster.php?konu=<?php echo urlencode($bas); ?>"target="main"> <?php echo $bas; ?> </a>

				    <?php
				    $count++;
				}
			    }
			    if ($count > 10)
				break;
			}
			?>
	    	</div>
		    <?php if ($login) { ?>
		<center><a href="ukte_ekle.php?konu=<?php echo $konu; ?>" target="main"
			   style="padding:2px;background:#c0c0c0;color:#000;border:#cc0000;font-family:Verdana;font-size:9pt;text-decoration:none;horizontal-align:center;">biri
			bu başlığı doldursun</a></center><?php } ?>
	    </td>
	    <td width="15%" align="right" style="word-break:keep-words;vertical-align:top;">
	        <form action="goster.php" method="get">
	    	<fieldset style="overflow:hidden;white-space:nowrap">
	    	    <legend style="font-size:x-small">başlık içinde ara</legend>
	    	    <input type="hidden" id="konu" name="konu" value="<?php echo $konu; ?>"/>
	    	    <input type="text" id="kw" name="kw" style="width:125px"/>
	    	    <input style="width:30px" title="başlık içinde ara" type="submit" value="ara"/>
	    	</fieldset>
	        </form>
	    </td>
	    </tr>
	    <?php
	}
    } else {
	//tümünü göster fasitilesi için
	$entryGoster = mysql_num_rows($result);

	$toplamEntry = mysql_num_rows(mysql_query("SELECT entry_id FROM tbl_entries WHERE entry_baslik ='" . $konu . "'", $con));


	$cnt = 1;
	while ($row = mysql_fetch_array($result)) {
	    $muser = $row["entry_yazar"];
	    ?>
	    <tr onmouseover="Show('<?php echo "td" . $cnt; ?>');" onmouseout="Hide('<?php echo "td" . $cnt; ?>');">
	        <td width="3%" align="right" rowspan="3" style="vertical-align:top;">
		    <?php echo $row["entry_sirano"] . "." ?>
	        </td>
	        <td class="left-menu" width="85%" align="justify"
	    	style="WORD-BREAK:keep-words;vertical-align:top;"<?php if ($cnt > 1) echo "colspan='2'"; ?>>
			<?php
			$text = $row["entry_text"];
			$text = htmlspecialchars(strtolower_tr($text));


			//print_r($matches);

			$pattern = "/`:[^`]*`/";
			$count = preg_match_all($pattern, $text, $matches);

			foreach ($matches[0] as $eslesme) {
			    $bkztext = substr($eslesme, 2, (strlen($eslesme) - 3));
			    //$bkztext = str_replace("'","\'",$bkztext);

			    $yenibkz = "<a href='goster.php?konu=" . urlencode($bkztext) . "' target='main' " . "title='" . $bkztext . "'>" . "*" . "</a>";

			    $text = str_replace($eslesme, $yenibkz, $text);
			}
			//print_r($matches);


			foreach ($matches as $i => $value) {
			    unset($matches[$i]);
			}

			$pattern = "/`[^`]*`/";
			$count = preg_match_all($pattern, $text, $matches);
			foreach ($matches[0] as $eslesme) {
			    $bkztext = substr($eslesme, 1, (strlen($eslesme) - 2));
			    //$bkztext = str_replace("'","\'",$bkztext);

			    $yenibkz = '<a href="goster.php?konu=' . urlencode($bkztext) . '" target="main">' . $bkztext . '</a>';

			    $text = str_replace($eslesme, $yenibkz, $text);
			}

			foreach ($matches as $i => $value) {
			    unset($matches[$i]);
			}

			$pattern = "/\(bkz:\s[^()]*\)/";
			$count = preg_match_all($pattern, $text, $matches);

			foreach ($matches[0] as $eslesme) {
			    $bkztext = substr($eslesme, 6, (strlen($eslesme) - 7));
			    //$bkztext = str_replace("'","\'",$bkztext);

			    $yenibkz = "(bkz: <a href='goster.php?konu=" . urlencode($bkztext) . "' target='main'>" . $bkztext . "</a>)";

			    $text = str_replace($eslesme, $yenibkz, $text);
			}

			foreach ($matches as $i => $value) {
			    unset($matches[$i]);
			}

			$pattern = "@\[youtube\][^\[\]]*\[\/youtube\]@i";
			$count = preg_match_all($pattern, $text, $matches);

			foreach ($matches[0] as $eslesme) {
			    if (stripos($eslesme, "&") > 0)
				$bkztext = substr($eslesme, stripos($eslesme, "=") + 1, stripos($eslesme, "&") - stripos($eslesme, "=") - 1);
			    else
				$bkztext = substr($eslesme, stripos($eslesme, "=") + 1, strrpos($eslesme, "[") - stripos($eslesme, "=") - 1);


			    //embed disabled olanları da açıyor
			    //$yenibkz = '<iframe width="480" height="320" src="http://www.youtube.com/watch_popup?v=' .$bkztext. '" frameborder="0" allowTransparency="true" autoplay="no"></iframe>';

			    $yenibkz = '<br/><iframe title="YouTube video player" width="480" height="320" src="http://www.youtube.com/embed/' . trim($bkztext) . '" frameborder="0" allowfullscreen></iframe>';
			    $text = str_replace($eslesme, $yenibkz, $text);
			}

			//resim ekleme
			foreach ($matches as $i => $value) {
			    unset($matches[$i]);
			}
			
			$pattern = "/\[img\][^\[\]]*[jpg|png|bmp|jpeg|gif]\[\/img\]/i";
			$count = preg_match_all($pattern, $text, $matches);

			foreach ($matches[0] as $eslesme) {
			    $bkztext = substr($eslesme, 5, strlen($eslesme) - 11);

			    $yenibkz = '<br/><img src="' . trim($bkztext) . '" alt="' . $row["entry_baslik"] . '" width="320" height="320"/>';

			    $text = str_replace($eslesme, $yenibkz, $text);
			}

			foreach ($matches as $i => $value) {
			    unset($matches[$i]);
			}
			$pattern = "/\[pdf\][^\[\]]*[.pdf]\[\/pdf\]/i";
			$count = preg_match_all($pattern, $text, $matches);

			foreach ($matches[0] as $eslesme) {
			    $bkztext = substr($eslesme, 5, strlen($eslesme) - 11);

			    $yenibkz = '<br/><iframe src="http://docs.google.com/gview?url=' . urlencode(trim($bkztext)) . '&embedded=true" style="width:600px; height:600px;" frameborder="0"></iframe>';

			    $text = str_replace($eslesme, $yenibkz, $text);
			}
			//url ekleme
			foreach ($matches as $i => $value) {
			    unset($matches[$i]);
			}
			
			$pattern = '@[^"](http|https|ftp)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(:[a-zA-Z0-9]*)?/?([a-zA-Z0-9\-\._\?\,\'/\\\+&amp;%\$#\=~])*[^\.\,\)\(\s]@';
			$count = preg_match_all($pattern, $text, $matches);
			foreach ($matches[0] as $eslesme) {
			    $bkztext = substr($eslesme, 0, strlen($eslesme));

			    $yenibkz = '<a href="' . trim($bkztext) . '" target = "_blank">' . $bkztext . '</a>';

			    $text = str_replace($eslesme, $yenibkz, $text);
			}
			//satır atlama
			foreach ($matches as $i => $value) {
			    unset($matches[$i]);
			}

			$pattern = array('/\r\n/', '/\r/', '/\n/');
			$replacement = '<br/>';
			$text = preg_replace($pattern, $replacement, $text);

			if (isset($_GET["kw"])) {
			    $pattern = strtolower_tr($_GET["kw"]);
			    $replacement = '<font style="background-color:red">' . $pattern . '</font>';
			    $text = str_replace($pattern, $replacement, $text);
			}
			/*			 * *  boşlukları htmlde göstermek için  ** */
			/*
			  $pattern = '/\s/';
			  $replacement = '&nbsp';
			  $text = preg_replace($pattern,$replacement,$text);
			 */


			echo $text;
			?>
	        </td>
		<?php if ($cnt == 1) { ?>
		    <td width="12%" align="right" style="WORD-BREAK:KEEP-WORDS;vertical-align:top;">
			<form action="goster.php" method="get">
			    <fieldset style="overflow:hidden;white-space:nowrap">
				<legend style="font-size:x-small">başlık içinde ara</legend>
				<input type="hidden" id="konu" name="konu" value="<?php echo $konu; ?>"/>
				<input type="text" id="kw" name="kw" style="width:125px"/>
				<input style="width:30px" title="başlık içinde ara" type="submit" value="ara"/>
				<br/>
				<?php
				$mesaj = "SELECT MAX(msg_zaman) as tarih FROM tbl_mesaj WHERE msg_kime ='" . $yazar . "'";
				$snc = mysql_query($mesaj, $con);
				$zaman = mysql_fetch_array($snc);
				$sonmesajtarihi = $zaman["tarih"];

				$logintime = "SELECT user_sonmesajokuma FROM tbl_users WHERE user_nick ='" . $yazar . "'";
				$snc = mysql_query($logintime, $con);
				$zaman = mysql_fetch_array($snc);
				$sonlogin = $zaman["user_sonmesajokuma"];

				if ($sonmesajtarihi > $sonlogin) {
				    ?>
		    		<a href="kontrol.php?islem=mesaj" target="main"
		    		   style="text-decoration:none;background-color:maroon;color:yellow">yeni mesajınız
		    		    var!!</a>
				<?php } ?>
			    </fieldset>
			</form>
		    </td>
		<?php } ?>
	    </tr>
	    <tr onmouseover="Show('<?php echo "td" . $cnt; ?>');" onmouseout="Hide('<?php echo "td" . $cnt; ?>');">
	        <td align="right" style="WORD-BREAK:BREAK-ALL;" colspan="2">
		    <?php
		    echo "(";
		    echo "<a href=\"goster.php?konu=$muser\" target=\"main\" style=\"text-decoration:none;color:#000;\" onmouseover='this.style.background=\"yellow\";' onmouseout='this.style.background=\"none\";'>$muser</a>";
		    if ($row["entry_sonedittarihi"] > $row["entry_giristarihi"]) {
			echo " " . date("d-m-Y H:i:s", strtotime($row["entry_giristarihi"])) . " ~ " . date("d-m-Y H:i:s", strtotime($row["entry_sonedittarihi"])) . ")";
		    } else {
			echo " " . date("d-m-Y H:i:s", strtotime($row["entry_giristarihi"])) . ")";
		    }
		    ?>
	        </td>
	    </tr>
	    <tr onmouseover="Show('<?php echo "td" . $cnt; ?>');" onmouseout="Hide('<?php echo "td" . $cnt; ?>');">

	        <td align="right" colspan="3">
	    	<div id="<?php echo "td" . $cnt; ?>" style="visibility:hidden;">
	    	    <a href="javascript:copyAdress('<?php echo $row["entry_id"] ?>','<?php echo $row["entry_baslik"]; ?>');"
	    	       style="text-decoration:none;color:purple;"><?php echo "#" . $row["entry_id"]; ?> </a>
			   <?php if ($login && ($row["entry_yazar"] != $yazar)) { ?>
			    <input name="button" type="button"
				   onclick="popup('entry_edit.php?islem=şükela&id=<?php echo $row["entry_id"]; ?>');" value="göğzel"
				   title="iyi oy verin"/>
			    <input name="button" type="button"
				   onclick="popup('entry_edit.php?islem=şaibeli&id=<?php echo $row["entry_id"]; ?>');" value="ı ıh"
				   title="kötü oy verin"/>
			    <input name="button" type="button"
				   onclick="popup('msg.php?kime=<?php echo $row["entry_yazar"]; ?>&msgid=<?php echo $row["entry_id"]; ?>');"
				   value="msg" title="yazara mesaj atın"/>
			    <input name="button" type="button"
				   onclick="window.open('ben.php?yazar=<?php echo $row["entry_yazar"]; ?>', 'main');" value="kim bu?"
				   title="yazar hakkında bilgi"/>
			    <input name="button" type="button"
				   onclick="popup('entry_edit.php?islem=ispiyon&id=<?php echo $row["entry_id"] ?>');" value="ispitle"
				   title="entry ispiyonlama"/>
			    <input name="button" type="button"
				   onclick="popup('kontrol.php?islem=badiler&badiisim=<?php echo $row["entry_yazar"] ?>');"
				   value="badim ol" title="badi ekleme aparatı"/>
			       <?php } ?>
			       <?php if (!$login) { ?>
			    <input name="button" type="button"
				   onclick="window.open('iletisim.php?sikayet_konusu=<?php echo $row["entry_id"] . " numaralı entry içeriği"; ?>', 'main')"
				   value="şikayet" title="şikayet et"/><?php } ?>
	    	    <input name="button" type="button"
	    		   onclick="popup('http://www.facebook.com/sharer.php?u=' + encodeURIComponent('http://www.kadimsozluk.com/goster.php?konu=<?php echo $row["entry_baslik"]; ?>&msgid=<?php echo $row["entry_id"]; ?>') + '&t=' + encodeURIComponent('Kadim Sözlük'));"
	    		   value="fesbuk" title="facebook'ta paylaş"/>
			       <?php if ($login && (($row["entry_yazar"] == $yazar) || $_SESSION['mod'])) { ?>
			    <input name="button" type="button"
				   onclick="if (confirm('Entry\'nizi silmek üzeresiniz bunu yapmak istediğinizden emin misiniz?')) {
		                                               popup('entry_edit.php?islem=sil&id=<?php echo $row["entry_id"] ?>')
		                                           }
		                                           ;"
				   value="sil" title="sil"/>
			    <input name="button" type="button"
				   onclick="popup('entry_edit.php?islem=düzenle&id=<?php echo $row["entry_id"] ?>');" value="düzenle"
				   title="düzenle"/><?php } ?>


	    	</div>
	        </td>

	    </tr>
	    <?php
	    $cnt++;
	}
	?>
    <?php } ?>

    <tr width="100%">
    <a id="bottom"></a>
    <td width="100%" colspan="3" align="left">
        <br/>
	<?php if ($entryGoster < $toplamEntry) { ?>
	<center>
	    <button onclick="location.href = 'goster.php?konu=<?php echo $konu; ?>'">tümünü göster</button>
	</center><?php } ?>
    <?php if ($login) { ?>
	<br/><br/><br/>
	<input name="button" type="button" onclick="insertAtCursor(document.entry_ekle.entry_text, '[pdf][/pdf]')"
	       value="pdf" title="nota pdf ekleme"/>
	<input name="button" type="button" onclick="insertAtCursor(document.entry_ekle.entry_text, '[img][/img]')"
	       value="resim" title="resim ekleme"/>
	<input name="button" type="button" onclick="insertAtCursor(document.entry_ekle.entry_text, '[youtube][/youtube]')"
	       value="youtube" title="youtube linki"/>
	<input name="button" type="button" onclick="insertAtCursor(document.entry_ekle.entry_text, '(bkz: )')"
	       value="(bkz: )" title="bakınız verme"/>
	<input name="button" type="button" onclick="insertAtCursor(document.entry_ekle.entry_text, '``')" value="' '"
	       title="akıllı bakınız verme"/>
	<input name="button" type="button" onclick="insertAtCursor(document.entry_ekle.entry_text, '`:`')" value="*"
	       title="görünmez bakınız verme"/>
	<input name="button" type="button"
	       onclick="insertAtCursor(document.entry_ekle.entry_text, '-----spoiler-----\r\n\r\n\r\n\r\n\r\n-----spoiler-----')"
	       value="--spoiler--" title="spoiler verme"/>
	</td>
	</tr>
	<tr>
	    <td width="100%" colspan="3">
		<form id="entry_ekle" name="entry_ekle" method="post" action="goster.php">
		    <input type="hidden" id="baslik" name="baslik" value="<?php echo $konu; ?>"/>
		    <input type="hidden" id="zaman" name="zaman" value="<?php echo date("Y-m-d H:i:s"); ?>"/>
		    <textarea id="entry_text" name="entry_text" rows="10%" cols="90%" maxlength="65535"><?php
			$sqlexist = "SELECT entry_text
												FROM tbl_kenar
												WHERE entry_yazar = '" . $yazar . "' AND entry_baslik = '" . $konu . "'";
			$snc = mysql_query($sqlexist, $con);

			//olan kenar mı değişecek yoksa yeni kenar entry mi girilecek
			if (mysql_num_rows($snc) == 1) {
			    $kenarsil = true;
			    $row = mysql_fetch_array($snc);
			    echo $row["entry_text"];
			}
			?></textarea> <br/>
		    <input type="submit" name="submit" value="yolla"/>
		    <input type="submit" name="submit" value="kenarda dursun"/>
		    <?php if ($kenarsil) echo '<input type="submit" name="submit" value="durmasın" />' ?>
		</form>
	    </td>
	</tr><?php } ?>
    </table>
    <?php
    mysql_close($con);
} else {
    echo "konuyu alamadim";
}
?>
</body>