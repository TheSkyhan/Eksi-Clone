<?php
    require_once('header.php');
$baslangic = strtotime("2016-03-08");
$bitis = strtotime(date("Y-m-d")) + 86399;

$random = mt_rand($baslangic, $bitis);

$bugun = date("Y-m-d", $random);

//echo $bugün;
$bugunstr = $bugun . " 00:00:00";
$yarinstr = $bugun . " 23:59:59";


$sql = "SELECT entry_baslik , COUNT(entry_baslik) as toplam
			FROM (SELECT entry_baslik, entry_giristarihi FROM tbl_entries 
			WHERE entry_giristarihi BETWEEN '" . $bugunstr . "' AND '" . $yarinstr . "' 
			ORDER BY entry_giristarihi DESC) tablo
			WHERE 1
			GROUP BY entry_baslik 
			ORDER BY entry_giristarihi DESC
			";

$result = mysql_query($sql, $con);
?>
<table border="0" width="90%" align="left">
    <tr>
        <td style="font-size:8 pt;text-align:center;"><?php echo date("d-m-Y", strtotime($bugun)) . " .. (" . mysql_num_rows($result) . " başlık)"?></td>
    </tr><?php
    while ($row = mysql_fetch_array($result))
    {
        ?>
        <tr>
            <td align="left" class="left-menu">
                <a href="goster.php?konu=<?=urlencode($row["entry_baslik"]);?>"
                   target="main"> <?php echo $row["entry_baslik"] . " (" . $row["toplam"] . ")";?> </a><br/>
            </td>
        </tr>
        <?php } ?>
</table>
<?php

mysql_close($con);

?>
</body>