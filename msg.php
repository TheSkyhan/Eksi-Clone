<?php
require_once('header.php');
if (isset($_POST["gönder"])) {
    ?>

    <script type='text/javascript'>
        setTimeout('self.close()', 4100);
        var milisec = 1;
        var seconds = 4;

        function display() {


    	if (milisec <= 0) {
    	    milisec = 9;
    	    seconds -= 1;
    	}
    	if (seconds <= -1) {
    	    milisec = 0;
    	    seconds += 1;
    	} else
    	    milisec -= 1
    	document.title = seconds + "." + milisec + " saniye sonra bu pencere kendi kendini imha edecektir :)";
    	setTimeout('display()', 100);
        }
        display();
    </script>
    <?php } ?>

    <?php
    $login = isset($_SESSION["nick"]);
    if (!$login)
	die("siteye giriş yapmadan mesaj atamazsınız");

    $yazar = $_SESSION["nick"];
    if (isset($_POST["gönder"])) {

	$sql = "SELECT user_nick FROM tbl_users WHERE user_nick = '" . mysql_real_escape_string($_POST["kime"]) . "'";
	$result = mysql_query($sql, $con);
	if (mysql_num_rows($result) == 0)
	    die("böyle bir kullanıcı yok malesef bi daha bak bakim?");

	$sql = "INSERT INTO tbl_mesaj (`msg_msgid` ,`msg_kimden` ,`msg_kime` ,`msg_icerik`,`msg_zaman`)
    VALUES (NULL ,  '" . mysql_real_escape_string($_POST["nick"]) . "',  '" . mysql_real_escape_string($_POST["kime"]) . "',  '" . mysql_real_escape_string($_POST["msg"]) . "', '" . date('Y-m-d H:i:s') . "')";
	$result = mysql_query($sql, $con);
	if ($result) {
	    echo "mesajınız başarıyla gönderildi";
	    mysql_close($con);
	} else {
	    echo "bir problem oluştu ve mesajınız gönderilemedi" . mysql_error();
	    mysql_close($con);
	}
    }
    ?>
    <table width="100%" border="0">
        <tr>
        <form id="hatirlat" name="hatirlat" method="post">
    	<tr>
    	    <td align="left">kime:<input type="text" id="kime" name="kime" size="20" value="<?php echo $_GET["kime"]; ?>"/>
    	    </td>
    	</tr>
    	<input type="hidden" id="nick" name="nick" value="<?php echo $yazar; ?>"/>
	<tr>
	    <td><textarea id="msg" name="msg" rows="10%" cols="40%" maxlength="1000"><?php if (isset($_GET["msgid"]))
                    echo "(#" . $_GET["msgid"] . ")"; ?></textarea></td>
	</tr>
	<tr>
	    <td align="left"><input type="submit" name="gönder" value="gönder"/></td>
	</tr>
    </form>
</tr>
</table>
</body>