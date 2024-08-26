<?php require_once('header.php');

$login = isset($_SESSION["nick"]);
if (!$login)
    die("siteye giriş yapmamışsınız badilerinizi bulamıyorum");

$yazar = $_SESSION["nick"];


$sql = "SELECT DISTINCT entry_baslik
			FROM tbl_entries 
			WHERE entry_yazar IN (SELECT badi_kimle FROM tbl_badi WHERE badi_yazar = '" . $yazar . "')
			ORDER BY entry_giristarihi DESC";

$result = mysql_query($sql, $con);

?>
<table border="0" width="90%" align="left">
    <tr>
        <td align="left" style="font-size:8 pt;;text-align:center;text-decoration:none;">badilerinizden gelenler</td>
    </tr>
    <?php
    while ($row = mysql_fetch_array($result))
    {
        ?>
        <tr>
            <td align="left" class="left-menu">
                <a href="goster.php?konu=<?php echo urlencode($row["entry_baslik"]);?>&yazar=badiler"
                   target="main"> <?php echo $row["entry_baslik"];?> </a><br/>
            </td>
        </tr>
        <?php }?>
</table>
<?php

mysql_close($con);
?>
</body>