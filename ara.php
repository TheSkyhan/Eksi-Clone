<?php
require_once('header.php');

function strtolower_tr_ara($metin) {
    $pattern = "/\[youtube\][^\[\]]*\[\/youtube\]/";
    $count = preg_match_all($pattern, $metin, $matches);

    $metin = mb_convert_case($metin, MB_CASE_LOWER, "utf-8");

    foreach ($matches[0] as $eslesme) {
	$metin = str_replace(mb_convert_case($eslesme, MB_CASE_LOWER, "utf-8"), $eslesme, $metin);
    }
    return mysql_real_escape_string(htmlspecialchars($metin));
}

if (isset($_GET["konu"])) {

    $sql = "SELECT DISTINCT entry_baslik FROM tbl_entries WHERE entry_baslik REGEXP '[[:<:]]" . strtolower_tr_ara($_GET["konu"]) . "[[:>:]]' ORDER BY entry_baslik ASC LIMIT 100";
    //echo $sql;
    $result = mysql_query($sql, $con);
    ?>
    <table border="0" width="90%" align="left"> <?php
	if (mysql_num_rows($result) == 0) {
	    ?>
	    <tr>
		<td align="left">aramanıza uygun başlık bulunamadı</td>
	    </tr><?php } else { ?>
	    <tr>
		<td align="left">size uygun sonuçlarımız</td>
	    </tr><?php
	    while ($row = mysql_fetch_array($result)) {
		?>
	        <tr>
	    	<td align="left" class="left-menu">
	    	    <a href="goster.php?konu=<?= urlencode($row["entry_baslik"]); ?>"
	    	       target="main"><?php echo $row["entry_baslik"]; ?></a><br/>
	    	</td>
	        </tr>
	        <?php }
	        }?>
	    </table>
	    <?php
	    mysql_close($con);
	}
    ?>
</body>
</html>