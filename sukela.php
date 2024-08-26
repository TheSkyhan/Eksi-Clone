<?php
    require_once('header.php');


$sql = "SELECT *
			FROM tbl_entries
			WHERE entry_iyi > 0
			ORDER BY RAND( ) 
			LIMIT 1 ";


$result = mysql_query($sql, $con);

if (mysql_num_rows($result) > 0) {
    $row = mysql_fetch_array($result);

    echo '<script>window.location="goster.php?konu=' . $row["entry_baslik"] . '&msgid=' . $row["entry_id"] . '"</script>';
}

mysql_close($con);
?>
</body>