<?php
    require_once('header.php');
$bugun = date("Y-m-d");
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
<table border="0" width="90%" align="left" marginheight="0" marginwidth="0">
    <tr>
        <td align="left"
            style="font-size:8pt;text-align:center;text-decoration:none;"><?php echo date("d-m-Y", strtotime($bugun)) . " .. (" . mysql_num_rows($result) . " başlık)"?></td>
    </tr>
    <?php
    while ($row = mysql_fetch_array($result))
    {
        ?>
        <tr>
            <td align="left" class="left-menu">
                <a href="goster.php?konu=<?php echo urlencode($row["entry_baslik"]);?>&zaman=<?php echo date('Y-m-d');?>"
                   target="main"> <?php echo $row["entry_baslik"] . " (" . $row["toplam"] . ")";?> </a><br/>
            </td>
        </tr>
        <?php }?>
</table>
<?

mysql_close($con);

?>
</body>