<?php require_once('header.php');

$birayonce = strtotime(date("Y-m-d")) - 2592000;

$bugun = date("Y-m-d", $birayonce);
$bugunstr = $bugun . " 00:00:00";

$bugun = date("Y-m-d");
$yarinstr = $bugun . " 23:59:59";

$nick = $_SESSION["nick"];


$sql = "SELECT entry_baslik, entry_giristarihi FROM
			(SELECT entry_baslik, entry_giristarihi
			FROM tbl_entries 
			WHERE entry_giristarihi BETWEEN '" . $bugunstr . "' AND '" . $yarinstr . "'  AND entry_yazar = '" . $nick . "'
			ORDER BY entry_giristarihi DESC ) tablo
			GROUP BY entry_baslik ORDER BY entry_giristarihi DESC";

$result = mysql_query($sql, $con);
?>
<table border="0" width="90%" align="left">
    <tr>
        <td align="left"
            style="font-size:8 pt;text-align:center;"><? echo "senden sonra" . " .. (" . mysql_num_rows($result) . " başlık)";?></td>
    </tr>
    <?
    while ($row = mysql_fetch_array($result))
    {
        ?>
        <tr>
            <td align="left" class="left-menu">
                <a href="goster.php?konu=<?=urlencode($row["entry_baslik"]);?>&zaman=<?=$row["entry_giristarihi"];?>"
                   target="main"> <?php echo $row["entry_baslik"];?> </a><br/>
            </td>
        </tr>
        <? }?>
</table>
<?

mysql_close($con);

?>
</body>