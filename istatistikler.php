<?php
    require_once('header.php');

$sql = "SELECT COUNT(entry_id) AS toplam FROM tbl_entries WHERE 1";
$result = mysql_query($sql, $con);
$row = mysql_fetch_array($result);

$toplamEntry = $row["toplam"];

$sql = "SELECT DISTINCT entry_baslik FROM tbl_entries WHERE 1";
$result = mysql_query($sql, $con);

$toplamBaslik = mysql_num_rows($result);

$sql = "SELECT COUNT(user_email) AS kullanici FROM tbl_users WHERE 1";
$result = mysql_query($sql, $con);
$row = mysql_fetch_array($result);

$toplamKullanici = $row["kullanici"];
$baslikBasiEntry = floor($toplamEntry / $toplamBaslik);
$yazarBasiBaslik = floor($toplamBaslik / $toplamKullanici);
$yazarBasiEntry = floor($toplamEntry / $toplamKullanici);

$gecenhafta = strtotime(date("Y-m-d")) - 604800;
$gecenhaftabugun = date("Y-m-d", $gecenhafta);
$baslangicstr = $gecenhaftabugun . " 00:00:00";
$bitisstr = date("Y-m-d");

$sql = "SELECT entry_yazar, COUNT(entry_yazar) as toplam
			FROM tbl_entries 
			WHERE `entry_giristarihi` BETWEEN '" . $baslangicstr . "' AND '" . $bitisstr . "'
			GROUP BY entry_yazar
			HAVING toplam > 0
			ORDER BY toplam DESC
			LIMIT 0 , 10";
$result = mysql_query($sql, $con);
$numrows = mysql_num_rows($result);
$row = mysql_fetch_array($result);


?>
<table width="50%" align="left" border="0">
    <th colspan="2">Sözlüğümüzle ilgili rakamsal veriler</th>
    <tr>
        <td width="50%">Sözlükteki Toplam Entry Sayısı:</td>
        <td width="50%"><?php echo $toplamEntry;?></td>
    </tr>
    <tr>
        <td width="50%">Sözlükteki Toplam Başlık Sayısı:</td>
        <td width="50%"><?php echo $toplamBaslik;?></td>
    </tr>
    <tr>
        <td width="50%">Sözlükteki Toplam Kullanıcı Sayısı:</td>
        <td width="50%"><?php echo $toplamKullanici;?></td>
    </tr>
    <tr>
        <td width="50%">Başlık Başına Düşen Entry Sayısı:</td>
        <td width="50%"><?php echo $baslikBasiEntry;?></td>
    </tr>
    <tr>
        <td width="50%">Yazar Başına Düşen Başlık Sayısı:</td>
        <td width="50%"><?php echo $yazarBasiBaslik;?></td>
    </tr>
    <tr>
        <td width="50%">Yazar Başına Düşen Entry Sayısı:</td>
        <td width="50%"><?php echo $yazarBasiEntry;?></td>
    </tr>

    <tr>
        <td width="50%" rowspan="<?php echo $numrows;?>" style="vertical-align:top;">Son 7 Günde En Çok Entry Giren 10 Yazar:
        </td>
        <td width="50%" class="left-menu"><a href="goster.php?konu=<?php echo $row["entry_yazar"];?>"
                                             target="main"><?php echo $row["entry_yazar"];?></a><?php echo " - " . $row["toplam"] . " entry";?>
        </td>
    </tr>
    <?php while ($row = mysql_fetch_array($result)) { ?>
    <tr>
        <td width="50%" class="left-menu"><a href="goster.php?konu=<?php echo $row["entry_yazar"];?>"
                                             target="main"><?php echo $row["entry_yazar"];?></a><?php echo " - " . $row["toplam"] . " entry";?>
        </td>
    </tr><?php }?>

    <?php
    $sql = "SELECT entry_baslik, entry_id , entry_iyi
			FROM tbl_entries 
			WHERE entry_iyi > 0
			ORDER BY entry_iyi DESC
			LIMIT 0 , 10";
    $result = mysql_query($sql, $con);
    $numrows = mysql_num_rows($result);
    $row = mysql_fetch_array($result);
    ?>
    <tr>
        <td width="50%" rowspan="<?php echo $numrows?>" style="vertical-align:top;">En Beğenilen 10 Entry:</td>
        <td width="50%" class="left-menu"><a
                href="goster.php?konu=<?php echo $row["entry_baslik"];?>&msgid=<?php echo $row["entry_id"];?>"
                target="main"><?php echo $row["entry_baslik"] . " / #" . $row["entry_id"] . " / " . $row["entry_iyi"] . " oy";?></a>
        </td>
    </tr>
    <?php while ($row = mysql_fetch_array($result)) { ?>
    <tr>
        <td width="50%" class="left-menu"><a
                href="goster.php?konu=<?php echo $row["entry_baslik"];?>&msgid=<?php echo $row["entry_id"];?>"
                target="main"><?php echo $row["entry_baslik"] . " / #" . $row["entry_id"] . " / " . $row["entry_iyi"] . " oy";?></a>
        </td>
    </tr><?php }?>

    <?php
    $sql = "SELECT entry_baslik, entry_id, entry_kotu
			FROM tbl_entries 
			WHERE entry_kotu > 0
			ORDER BY entry_kotu DESC
			LIMIT 0 , 10";
    $result = mysql_query($sql, $con);
    $numrows = mysql_num_rows($result);
    $row = mysql_fetch_array($result);
    ?>
    <tr>
        <td width="50%" rowspan="<?php echo $numrows?>" style="vertical-align:top;">En Kötülenen 10 Entry:</td>
        <td width="50%" class="left-menu"><a
                href="goster.php?konu=<?php echo $row["entry_baslik"];?>&msgid=<?php echo $row["entry_id"];?>"
                target="main"><?php echo $row["entry_baslik"] . " / #" . $row["entry_id"] . " / " . $row["entry_kotu"] . " oy";?></a>
        </td>
    </tr>
    <?php while ($row = mysql_fetch_array($result)) { ?>
    <tr>
        <td width="50%" class="left-menu"><a
                href="goster.php?konu=<?php echo $row["entry_baslik"];?>&msgid=<?php echo $row["entry_id"];?>"
                target="main"><?php echo $row["entry_baslik"] . " / #" . $row["entry_id"] . " / " . $row["entry_kotu"] . " oy";?></a>
        </td>
    </tr><?php }?>

    <?php
    $sql = "SELECT entry_baslik, COUNT(entry_id) as toplam
			FROM tbl_entries 
			GROUP BY entry_baslik
			ORDER BY toplam DESC
			LIMIT 0 , 10";
    $result = mysql_query($sql, $con);
    $numrows = mysql_num_rows($result);
    $row = mysql_fetch_array($result);
    ?>
    <tr>
        <td width="50%" rowspan="<?php echo $numrows?>" style="vertical-align:top;">En Çok Entry İçeren 10 Başlık:</td>
        <td width="50%" class="left-menu"><a href="goster.php?konu=<?php echo $row["entry_baslik"];?>"
                                             target="main"><?php echo $row["entry_baslik"] . " / " . $row["toplam"] . " entry";?></a>
        </td>
    </tr>
    <?php while ($row = mysql_fetch_array($result)) { ?>
    <tr>
        <td width="50%" class="left-menu"><a href="goster.php?konu=<?php echo $row["entry_baslik"];?>"
                                             target="main"><?php echo $row["entry_baslik"] . " / " . $row["toplam"] . " entry";?></a>
        </td>
    </tr><?php }?>


    <?php
    $sql = "SELECT `entry_yazar`,count(`entry_id`) AS toplam
	FROM `tbl_entries` 
	WHERE 1 
	GROUP BY `entry_yazar` 
	ORDER BY toplam DESC LIMIT 0 , 10";

    $result = mysql_query($sql, $con);
    $numrows = mysql_num_rows($result);
    $row = mysql_fetch_array($result);
    ?>
    <tr>
        <td width="50%" rowspan="<?php echo $numrows?>" style="vertical-align:top;">En Çok Entry Giren 10 Kullanıcı:</td>
        <td width="50%" class="left-menu"><a href="goster.php?konu=<?php echo $row["entry_yazar"];?>"
                                             target="main"><?php echo $row["entry_yazar"] . " / " . $row["toplam"] . " entry";?></a>
        </td>
    </tr>
    <?php while ($row = mysql_fetch_array($result)) { ?>
    <tr>
        <td width="50%" class="left-menu"><a href="goster.php?konu=<?php echo $row["entry_yazar"];?>"
                                             target="main"><?php echo$row["entry_yazar"] . " / " . $row["toplam"] . " entry";?></a>
        </td>
    </tr><?php }?>

    <?php
    $sql = "SELECT `user_nick`
	FROM `tbl_users` 
	WHERE 1 
	ORDER BY user_kayittarihi DESC 
	LIMIT 0 , 10";

    $result = mysql_query($sql, $con);
    $numrows = mysql_num_rows($result);
    $row = mysql_fetch_array($result);
    ?>
    <tr>
        <td width="50%" rowspan="<?php echo $numrows?>" style="vertical-align:top;">En Son Kaydolan 10 Kullanıcı:</td>
        <td width="50%" class="left-menu"><a href="goster.php?konu=<?php echo $row["user_nick"];?>"
                                             target="main"><?php echo $row["user_nick"];?></a></td>
    </tr>
    <?php while ($row = mysql_fetch_array($result)) { ?>
    <tr>
        <td width="50%" class="left-menu"><a href="goster.php?konu=<?php echo $row["user_nick"];?>"
                                             target="main"><?php echo $row["user_nick"];?></a></td>
    </tr><?php } ?>


    <?php
    $sql = "SELECT SUBSTRING( entry_giristarihi, 1, 10 ) AS tarih, COUNT( * ) AS toplam
			FROM tbl_entries
			GROUP BY tarih
			ORDER BY tarih DESC
			LIMIT 0 , 7";
    $result = mysql_query($sql, $con);
    $numrows = mysql_num_rows($result);
    $row = mysql_fetch_array($result);
    ?>
    <tr>
        <td width="50%" rowspan="<?php echo $numrows?>" style="vertical-align:top;">Son 7 Günde Girilen Entry Sayısı:</td>
        <td width="50%" class="left-menu"><a href="goster.php?konu=<?php echo $row["tarih"];?>"
                                             target="main"><?php echo $row["tarih"] . " / " . $row["toplam"] . " entry";?></a>
        </td>
    </tr>
    <?php while ($row = mysql_fetch_array($result)) { ?>
    <tr>
        <td width="50%" class="left-menu"><a href="goster.php?konu=<?php echo $row["tarih"];?>"
                                             target="main"><?php echo $row["tarih"] . " / " . $row["toplam"] . " entry";?></a>
        </td>
    </tr><?php }?>

    <?php
    $sql = "SELECT user_cinsiyet, COUNT(user_cinsiyet) as toplam
			FROM tbl_users
			GROUP BY user_cinsiyet
			ORDER BY toplam DESC";
    $result = mysql_query($sql, $con);
    $numrows = mysql_num_rows($result);
    $row = mysql_fetch_array($result);
    $cinsiyet = array();
    $cinsiyet["e"] = "erkek";
    $cinsiyet["k"] = "kadın";
    $cinsiyet["d"] = "şey";
    ?>
    <tr>
        <td width="50%" rowspan="<?php echo $numrows?>" style="vertical-align:top;">Cinsiyete Göre Kullanıcı Dağılımı:</td>
        <td width="50%"><?php echo $cinsiyet[$row["user_cinsiyet"]] . " / " . $row["toplam"] . " kullanıcı";?></td>
    </tr>
    <?php while ($row = mysql_fetch_array($result)) { ?>
    <tr>
        <td width="50%"><?php echo $cinsiyet[$row["user_cinsiyet"]] . " / " . $row["toplam"] . " kullanıcı";?></td>
    </tr><?php }?>

    <?php
    $sql = "SELECT entry_baslik, COUNT(entry_id) as toplam
			FROM tbl_entries
			WHERE entry_baslik = entry_yazar
			GROUP BY entry_baslik
			ORDER BY toplam DESC
			LIMIT 0,10";
    $result = mysql_query($sql, $con);
    $numrows = mysql_num_rows($result);
    $row = mysql_fetch_array($result);

    ?>
    <tr>
        <td width="50%" rowspan="<?php echo $numrows?>" style="vertical-align:top;">Kendine En Çok Entry Giren 10 Yazar:</td>
        <td width="50%" class="left-menu"><a href="goster.php?konu=<?php echo $row["entry_baslik"];?>"
                                             target="main"><?php echo $row["entry_baslik"];?></a><?php echo " / " . $row["toplam"] . " entry";?>
        </td>
    </tr>
    <?php while ($row = mysql_fetch_array($result)) { ?>
    <tr>
        <td width="50%" class="left-menu"><a href="goster.php?konu=<?php echo $row["entry_baslik"];?>"
                                             target="main"><?php echo $row["entry_baslik"];?></a><?php echo " / " . $row["toplam"] . " entry";?>
        </td>
    </tr><?php }?>

    <?php
    $sql = "SELECT SUBSTRING(user_dogumtarihi,1,4) as yil, COUNT(*) as toplam
			FROM tbl_users
			GROUP BY yil
			ORDER BY yil ASC";
    $result = mysql_query($sql, $con);
    $numrows = mysql_num_rows($result);
    $row = mysql_fetch_array($result);

    ?>
    <tr>
        <td width="50%" rowspan="<?php echo $numrows?>" style="vertical-align:top;">Kaç Kişi Ne Zaman Doğmuş?:</td>
        <td width="50%"><?php echo $row["yil"] . " / " . $row["toplam"] . " kişi";?></td>
    </tr>
    <?php while ($row = mysql_fetch_array($result)) { ?>
    <tr>
        <td width="50%"><?php echo $row["yil"] . " / " . $row["toplam"] . " kişi";?></td>
    </tr><?php }?>

    <?php
    $sql = "SELECT entry_yazar, COUNT(entry_yazar) as toplam
			FROM tbl_ukte
			GROUP BY entry_yazar
			ORDER BY toplam DESC
			LIMIT 0,10";
    $result = mysql_query($sql, $con);
    $numrows = mysql_num_rows($result);
    $row = mysql_fetch_array($result);

    ?>
    <tr>
        <td width="50%" rowspan="<?php echo $numrows?>" style="vertical-align:top;">En Çok Ukte Veren 10 Yazar:</td>
        <td width="50%" class="left-menu"><a href="goster.php?konu=<?php echo $row["entry_yazar"];?>"
                                             target="main"><?php echo $row["entry_yazar"];?></a><?php echo " - " . $row["toplam"] . " ukte";?>
        </td>
    </tr>
    <?php while ($row = mysql_fetch_array($result)) { ?>
    <tr>
        <td width="50%" class="left-menu"><a href="goster.php?konu=<?php echo $row["entry_yazar"];?>"
                                             target="main"><?php echo $row["entry_yazar"];?></a><?php echo " - " . $row["toplam"] . " ukte";?>
        </td>
    </tr><?php }?>

    <?php
    $sql = "SELECT isp_kim, COUNT(isp_kim) as toplam
			FROM tbl_ispiyon
			GROUP BY isp_kim
			ORDER BY toplam DESC
			LIMIT 0,10";
    $result = mysql_query($sql, $con);
    $numrows = mysql_num_rows($result);
    $row = mysql_fetch_array($result);


    ?>
    <tr>
        <td width="50%" rowspan="<?php echo $numrows?>" style="vertical-align:top;">En Çok İspiyonlayan 10 Yazar:</td>
        <td width="50%" class="left-menu"><a href="goster.php?konu=<?php echo $row["isp_kim"];?>"
                                             target="main"><?php echo $row["isp_kim"];?></a><?php echo " / " . $row["toplam"] . " ispiyon";?></a>
        </td>
    </tr>
    <?php while ($row = mysql_fetch_array($result)) { ?>
    <tr>
        <td width="50%" class="left-menu"><a href="goster.php?konu=<?php echo $row["isp_kim"];?>"
                                             target="main"><?php echo $row["isp_kim"];?></a><?php echo " / " . $row["toplam"] . " ispiyon";?></a>
        </td>
    </tr><?php } mysql_close($con);?>

</table>