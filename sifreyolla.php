<?php
require_once('header.php');

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if (isset($_POST["gönder"])) {
    $mail = $_POST["mail"];
    $nick = $_POST["nick"];
    $headers = 'From: Kadim Sözlük  <cakma@yalan.com>';

    $pattern = "/^[^@]*@[^@]*\.[^@]*$/";
    if (!preg_match($pattern, $_POST["mail"]))
	die("lütfen geçerli bir e-mail adresi girin ki sizi muhattap alabilelim :)");


    $sql = "SELECT user_pwd,user_email
				FROM tbl_users
				WHERE user_nick = '" . mysql_real_escape_string($nick) . "' AND user_email ='" . mysql_real_escape_string($mail) . "'";

    $result = mysql_query($sql, $con);
    if (mysql_num_rows($result) == 0) {
	mysql_close($con);
	die("bu nickte veya mail adresinde bir kullanıcı bulunamadı");
    } else {
	$new_pwd = generateRandomString();
	$sql = "UPDATE tbl_users SET user_pwd = '" . md5($new_pwd) . "' WHERE user_nick = '" . mysql_real_escape_string($nick) . "' AND user_email ='" . mysql_real_escape_string($mail) . "'";
	$result = mysql_query($sql, $con);
	echo "Şifreniz: " . $new_pwd . "<br/> olarak değiştirildi. Lütfen şifrenizi ilk girişinizde değiştirin.";

	if ($sent)
	    echo "mail başarıyla gönderildi";
	mysql_close($con);
    }
}
?>
<table width="50%" border="0">
    <tr>
    <form id="hatirlat" name="hatirlat" method="post">
	<tr>
	    <td>e-mail adresiniz: (*)</td>
	    <td align="left"><input type="text" id="mail" name="mail" size="50"/></td>
	</tr>
	<tr>
	    <td>nickiniz:</td>
	    <td align="left"><input type="text" id="nick" name="nick" size="50"/></td>
	</tr>
	<tr>
	    <td></td>
	    <td align="left"><input type="submit" name="gönder" value="gönder"/></td>
	</tr>
    </form>
</tr>
</table>
</body>