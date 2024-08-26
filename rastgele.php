<?php

require('header.php');

$sql = "SELECT entry_baslik, count(entry_baslik) as cnt
			FROM tbl_entries
			GROUP BY entry_baslik
			ORDER BY RAND( ) 
			LIMIT 50 ";


$result = mysql_query($sql, $con);


?>
<table border="0" width="90%" align="left">
    <tr>
        <td style="font-size:8 pt;;text-align:center;keep-words">rastgele.. (50 başlık)</td>
    </tr><?php
    while ($row = mysql_fetch_array($result))
    {
        ?>
        <tr>
            <td align="left" class="left-menu">
                <a href="goster.php?konu=<?php echo urlencode($row["entry_baslik"]);?>"
                   target="main"> <?php echo $row["entry_baslik"] . " (".$row['cnt'].")";?> </a><br/>
            </td>
        </tr>
        <?php }?>
</table>
<?php

mysql_close($con);

?>
</body>
</html>