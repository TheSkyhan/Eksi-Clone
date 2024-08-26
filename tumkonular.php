<?php
require_once('header.php');

$sql = "SELECT entry_baslik , COUNT(entry_baslik) as toplam
			FROM (SELECT entry_baslik, entry_giristarihi FROM tbl_entries 
			ORDER BY entry_giristarihi DESC) tablo
			WHERE 1
			GROUP BY entry_baslik 
			ORDER BY entry_giristarihi DESC LIMIT 100
			";

$result = mysql_query($sql, $con);
?>
<table border="0" width="90%" align="left" marginheight="0" marginwidth="0">
    <tr>
        <td align="left"
            style="font-size:8 pt;text-align:center;text-decoration:none;"><?php echo "son " . mysql_num_rows($result) . " başlık" ?></td>
    </tr>
    <?php
    while ($row = mysql_fetch_array($result)) {
	?>
        <tr>
    	<td align="left" class="left-menu">
    	    <a href="goster.php?konu=<?= urlencode($row["entry_baslik"]); ?>"
    	       target="main"> <?php echo $row["entry_baslik"] . " (" . $row["toplam"] . ")"; ?> </a><br/>
    	</td>
        </tr>
    <?php } ?>
</table>
<?php
    mysql_close($con);
?>
</body>