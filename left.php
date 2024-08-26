<?php require_once('header.php'); ?>

<style type="text/css">
    body {
        overflow-x: hidden;
    }
</style>

<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-713453-5']);
    _gaq.push(['_trackPageview']);

    (function () {
        var ga = document.createElement('script');
        ga.type = 'text/javascript';
        ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ga, s);
    })();

</script>

</head>
<body onload="fixDiv();">

    <script language="javascript">

        window.onresize = fixDiv;
        window.onscroll = fixPos;

        var show = 0;

        function fixDiv() {
            var slidingDiv = document.getElementById("acilsusam");
            slidingDiv.style.left = (document.body.clientWidth - 15) + "px";
            show = 0;
        }

        function fixPos() {
            var slidingDiv = document.getElementById("acilsusam");
            slidingDiv.style.left = (document.body.clientWidth - 15) + "px";
            slidingDiv.style.top = (document.body.scrollTop + 45) + "px";
            show = 0;
        }
        function slideLeft() {
            var slidingDiv = document.getElementById("acilsusam");
            var stopPosition = 10;

            if (parseInt(slidingDiv.style.left) > stopPosition) {
                slidingDiv.style.left = parseInt(slidingDiv.style.left) + -10 + "px";
                setTimeout(slideLeft, 1);
            }
            show = 1;
        }
        function slideRight() {
            var slidingDiv = document.getElementById("acilsusam");
            var stopPosition = (document.body.clientWidth - 15);

            if (parseInt(slidingDiv.style.left) < stopPosition) {
                slidingDiv.style.left = parseInt(slidingDiv.style.left) + 10 + "px";
                setTimeout(slideRight, 1);
            }
            show = 0;
        }

    </script>

    <div id="acilsusam" >

	<form action="left.php" id="sr" method="get">

	    <table border="0" cellpadding="0" cellspacing="0">

		<tr>
		    <td class="aup">&nbsp;</td>

		    <td id="amain" rowspan="3" class="amain">

			<input type="hidden" name="a" value="sr"/>
			<fieldset style="font-size:9pt;white-space:nowrap;">
			    <legend>arama</legend>
			    <table style="font-size:9pt;" border="0" cellpadding="0" cellspacing="0">
				<tr>
				    <td>şey</td>
				    <td><input type="text" name="şey" size="10" value=""/></td>
				</tr>

				<tr>
				    <td>yazarı</td>
				    <td><input type="text" name="yazar" size="10" value=""/></td>
				</tr>

			    </table>
			</fieldset>

			<fieldset style="font-size:9pt;white-space:nowrap;">
			    <legend>sıra şekli</legend>
			    <table style="font-size:9pt;">
				<tr>
				    <td style="white-space:nowrap"><input type="radio" name="sıralama" value="alfabetik"
									  checked="checked"/>alfabetik
				    </td>
				    <td style="white-space:nowrap"><input type="radio" name="sıralama" value="rasgele"/>rasgele
				    </td>
				<tr>
				    <td style="white-space:nowrap"><input type="radio" name="sıralama" value="kronolojik"/>kronolojik
				    </td>
				    <td style="white-space:nowrap"><input type="radio" name="sıralama" value="popüler"/>popüler
				    </td>
				</tr>
			    </table>
			</fieldset>

			<fieldset style="font-size:9pt;white-space:nowrap;text-align:center">
			    <legend>şu gün</legend>
			    <select name="gün">
				<option value=""></option>
				<?for ($i = 1; $i < 32; $i++) { ?>
				<option value="<?= $i; ?>"><?= $i; ?></option>
				<? }?>
			    </select>
			    <select name="ay">
				<option value=""></option>
				<?for ($i = 1; $i < 13; $i++) { ?>
				<option value="<?= $i; ?>"><?= $i; ?></option>
				<? }?>
			    </select>
			    <select name="yıl">
				<option value=""></option>
				<?for ($i = 2012; $i > 2010; $i--) { ?>
				<option value="<?= $i; ?>"><?= $i; ?></option>
				<? }?>
			    </select>
			</fieldset>

			<fieldset style="font-size:9pt;white-space:nowrap;">
			    <legend>tercihler</legend>
			    <input type="checkbox" name="güzel" value="evet"/>güzelinden olsun
			</fieldset>
			<br/>

			<div style="text-align:center">

			    <input type="submit" name="gönder" value="ara bakim"/></div>

		    </td>
		</tr>

		<tr>
		    <td style="background-color: #cccccc;
			border: 1px outset;
			border-right: 0;
			padding: 4px;
			line-height: 8pt;
			font-weight: bold;
			font-size: 8pt;
			cursor: pointer;
			text-align: center;" onclick="if (!show) {
                                    slideLeft();
                                } else {
                                    slideRight();
                                }
                                ;">1<br/>1<br/>8<br/><br/>r<br/>e<br/>h<br/>b<br/>e<br/>r
		    </td>
		</tr>

		<tr>
		    <td class="abot">&nbsp;</td>
		</tr>
		</div>
	    </table></form></div>
    <?php

    function strtolower_tr($metin) {
	$pattern = "/\[youtube\][^\[\]]*\[\/youtube\]/";
	$count = preg_match_all($pattern, $metin, $matches);

	$metin = mb_convert_case($metin, MB_CASE_LOWER, "utf-8");

	foreach ($matches[0] as $eslesme) {

	    $metin = str_replace(mb_convert_case($eslesme, MB_CASE_LOWER, "utf-8"), $eslesme, $metin);
	}
	return $metin;
    }

    if (isset($_GET["q"])) {

	$sayfa = $_GET["q"];

	if ($sayfa == null)
	    echo "sanırım null";


	switch ($sayfa) {
	    case "ara":
		require("ara.php");
		break;

	    case "rastgele":
		require("rastgele.php");
		break;

	    case "bir gün":
		require("birgun.php");
		break;

	    case "ukte":
		require("ukte.php");
		break;

	    case "kenar":
		require("kenar.php");
		break;

	    case "bugün":
		require("bugun.php");
		break;

	    case "dün":
		require("dun.php");
		break;

	    case "badi":
		require("badi.php");
		break;

	    case "son":
		require("son.php");
		break;

	    case "gecenyil":
		require("gecenyil.php");
		break;

	    case "index":
		require("tumkonular.php");
		break;

	    default:
		echo "ben leftin default action";
	}
    } else if (isset($_GET["gönder"])) {

	if (strtolower_tr($_GET["şey"]) == "")
	    $aramatxt = "*";
	else
	    $aramatxt = strtolower_tr($_GET["şey"]);

	$sql = "SELECT entry_baslik, toplam, max
				FROM (SELECT entry_baslik,entry_giristarihi,MAX(entry_iyi) AS max , COUNT( entry_baslik ) AS toplam
 
						FROM tbl_entries 
						WHERE entry_baslik 
						REGEXP '[[:<:]]" . mysql_real_escape_string($aramatxt) . "[[:>:]]'
						";
	if ($_GET["gün"] != null && $_GET["ay"] != null && $_GET["yıl"] != null) {
	    $tarih1 = $_GET["yıl"] . "-" . $_GET["ay"] . "-" . $_GET["gün"] . " 00:00:00";
	    $tarih2 = $_GET["yıl"] . "-" . $_GET["ay"] . "-" . $_GET["gün"] . " 23:59:59";
	    $sql = $sql . " AND entry_giristarihi BETWEEN '" . mysql_real_escape_string($tarih1) . "' AND '" . mysql_real_escape_string($tarih2) . "'";
	}
	if ($_GET["yazar"] != null) {
	    $sql = $sql . " AND entry_yazar ='" . mysql_real_escape_string($_GET["yazar"]) . "'";
	}
	$sql = $sql . " GROUP BY entry_baslik";
	if (isset($_GET["güzel"]) && $_GET["güzel"] == "evet") {
	    $sql = $sql . " ORDER BY max DESC, entry_giristarihi DESC ";
	} else
	    $sql = $sql . " ORDER BY entry_giristarihi DESC";

	$sql = $sql . ") tablo ";

	$sql = $sql . " GROUP BY entry_baslik";

	switch ($_GET["sıralama"]) {

	    case "alfabetik":
		if (isset($_GET["güzel"]) && $_GET["güzel"] == "evet")
		    $sql = $sql . " ORDER BY max DESC, entry_baslik ASC";
		else
		    $sql = $sql . " ORDER BY entry_baslik ASC";
		break;

	    case "rasgele":
		if (isset($_GET["güzel"]) && $_GET["güzel"] == "evet")
		    $sql = $sql . " ORDER BY max DESC, RAND()";
		else
		    $sql = $sql . " ORDER BY RAND()";
		break;

	    case "kronolojik":
		//if(isset($_GET["güzel"]) && $_GET["güzel"] == "evet")
		//	$sql = $sql . " entry_giristarihi DESC";
		//else
		if (isset($_GET["güzel"]) && $_GET["güzel"] == "evet")
		    $sql = $sql . " ORDER BY max DESC, entry_giristarihi DESC";
		else
		    $sql = $sql . " ORDER BY entry_giristarihi DESC";
		break;

	    case "popüler":
		//if(isset($_GET["güzel"]) && $_GET["güzel"] == "evet")
		//	$sql = $sql . " toplam DESC";
		//else
		if (isset($_GET["güzel"]) && $_GET["güzel"] == "evet")
		    $sql = $sql . " ORDER BY max DESC, toplam DESC";
		else
		    $sql = $sql . " ORDER BY toplam DESC";
		break;
	}

	$sql .= " LIMIT 100";
	//echo $sql;
	$result = mysql_query($sql, $con);
	?>
        <table border="0" width="100%"> 
	    <?php
	    if (mysql_num_rows($result) == 0) {
		?>
		<tr>
		    <td align="left">aramanıza uygun başlık bulunamadı</td>
		</tr>
	    <?php } else { ?>
		<tr>
		    <td align="left">size uygun sonuçlarımız</td>
		</tr>
		<?php
		while ($row = mysql_fetch_array($result)) {
		    ?>
	    	<tr>
	    	    <td align="left">
	    		<a href="goster.php?konu=<?php echo $row["entry_baslik"]; ?>" target="main"
	    		   style="word-break:keep-words;text-decoration:none;color:#000;"
	    		   onmouseover='this.style.background = "yellow";'
	    		   onmouseout='this.style.background = "none";'><?php echo $row["entry_baslik"]; ?></a><br/>
	    	    </td>
	    	</tr>
		<?php }
	    }
	    ?>
        </table>
	<?php
	mysql_close($con);
    } else {
	require("tumkonular.php");
    }
    ?>
</html>