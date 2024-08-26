<?php require_once('header.php');

$login = isset($_SESSION["nick"]);
if (!$login)
    die("herkesin kenarı kendine. giriş yapmayı unutma ;)");

$yazar = $_SESSION["nick"];

$sql = "SELECT entry_baslik
			FROM tbl_kenar 
			WHERE entry_yazar = '" . $yazar . "'
			ORDER BY entry_giristarihi ASC";

$result = mysql_query($sql, $con);
?>
<table border="0" width="90%" align="left"> <?php
    if (mysql_num_rows($result) == 0) {
        ?>
        <tr>
            <td align="left">kenarda duran entry'niz yok</td>
        </tr>
        <?php } else { ?>
        <tr>
            <td align="left" style="font-size:8 pt;;text-align:center;text-decoration:none;">kenarda duran
                entryleriniz
            </td>
        </tr><?php
        while ($row = mysql_fetch_array($result))
        {
            ?>
            <tr>
                <td align="left" class="left-menu">
                    <a href="goster.php?konu=<?php echo urlencode($row["entry_baslik"]);?>"
                       target="main"> <?php echo $row["entry_baslik"];?> </a><br/>
                </td>
            </tr>
            <?php }
    } ?>
</table>
<?php

mysql_close($con);

?>
</body>