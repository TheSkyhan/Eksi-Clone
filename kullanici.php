<?php
require 'header.php';

if (isset($_POST['gonder'])) {

    $sql = "SELECT user_email, user_pwd, user_nick, user_mod

			FROM tbl_users

			WHERE user_email = '" . mysql_real_escape_string($_POST["mail"]) . "' AND user_pwd ='" . mysql_real_escape_string(md5($_POST["pwd"])) . "' ";



    $result = mysql_query($sql, $con);



    if (mysql_num_rows($result) == 1) {

	$row = mysql_fetch_array($result);

	$_SESSION["nick"] = $row["user_nick"];

	$_SESSION['mod'] = $row['user_mod'];



	mysql_close($con);
	?>

	<script language="JavaScript">

	    function yonlendir(adres) {

		if (adres != '') {

		    var url = 'goster.php?' + adres;

		    parent.frames["main"].location.href = url;

		} else {

		    parent.frames["main"].location.href = 'main.php';

		}

		parent.frames["ust"].location.reload();

	    }



	    function login() {

		yonlendir('<?= $_SESSION["adres"]; ?>');

		//window.open("/phpfreechat-1.3/index.php", "mywindow","location=0,status=1,scrollbars=1, width=800,height=600");

	    }

	    login();

	</script><?php
    } else {

	echo "<font color='red'>atma recep din kardeşiyiz</font>";

	mysql_close($con);
    }
}
?>

<body>

    <form name="user" method="post">

	e-mail adresi<br/><input type="text" name="mail" id="mail" style="width:210px"/><br/>

	şifre<br/><input type="password" name="pwd" id="pwd" style="width:210px" maxlength="25"/><br/>

	<input type='hidden' name='hid' value="ok"/>

	<input type="submit" name="gonder" value="gir bakem"/><br/>

    </form>

    <div class="left-menu">

	<a href="sifreyolla.php" target="main">unutuyom ben ya</a><br/>

	<a href="yeni_kullanici.php" target="main">sözlüğü çok sevdim katılabilir miyim?</a>

    </div>

</body>

</html>
