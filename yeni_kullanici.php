<?php
require_once('header.php');
if (isset($_POST["gönder"])) {
    if (preg_match("/^\s*$/", $_POST["nick"]))
        die("boş nick girdiniz lütfen tekrar deneyin");

    if (!preg_match("/^[^@]*@[^@]*\.[^@]*$/", $_POST["mail"]))
        die("geçersiz bir e-mail adresi girdiniz");
    
    if (!preg_match("/^[a-z0-9]+[a-z0-9\s]+$/", trim($_POST["nick"])))
        die("geçersiz bir nick girdiniz");

    if (strlen($_POST["pwd"]) < 4)
        die("Lütfen şifrenizi 4 karakterden daha uzun seçin");

    if ($_POST["pwd"] != $_POST["pwd_tekrar"])
        die("girdiğiniz şifreler eşleşmiyor bir daha kontrol edin");


    $kayittarihi = $_POST ["yıl"] . "-" . $_POST ["ay"] . "-" . $_POST ["gün"];
    $sql = "INSERT INTO tbl_users (`user_nick`, `user_email`, `user_pwd`, `user_dogumtarihi`, `user_kayittarihi`, `user_cinsiyet`)
			VALUES ('" . mysql_real_escape_string(trim($_POST["nick"])) . "', '" . mysql_real_escape_string(trim($_POST["mail"])) . "', '" . mysql_real_escape_string(md5($_POST["pwd"])) . "', '" . mysql_real_escape_string($kayittarihi) . "', '" . date("Y-m-d") . "', '" . mysql_real_escape_string($_POST["cinsiyet"]) . "')";

    $result = mysql_query($sql, $con);

    if (!$result)
        echo mysql_error();
    else
        echo "kullanıcı kaydınız başarıyla yapıldı şimdi <font color='red'><a href='kullanici.php' target='main'>buraya</a></font> tıklayarak giriş yapabilirsiniz";
    mysql_close($con);
}
else {
    ?>
<form name="user" id="user" method="post" action="yeni_kullanici.php">
    sözlükte kullanacağınız nick: (1-255 karakter)<br/>
    <input type="text" name="nick" id="nick" style="width:210px;" maxlength="255"><br/>
    şifre: (4-25 karakter)<br/>
    <input type="password" name="pwd" id="pwd" style="width:210px;" maxlength="25"><br/>
    şifre (tekrar):<br/>
    <input type="password" name="pwd_tekrar" id="pwd_tekrar" style="width:210px;" maxlength="25"><br/>
    e-mail adresiniz:<br/>
    <input type="text" name="mail" id="mail" style="width:210px;"><br/>
    doğum tarihiniz:<br/>
    <select name="gün">
        <?php for ($i = 1; $i < 32; $i++) { ?>
        <option value="<?=$i;?>"><?=$i;?></option>
        <?php } ?>
    </select>
    <select name="ay">
        <?php for ($i = 1; $i < 13; $i++) { ?>
        <option value="<?=$i;?>"><?=$i;?></option>
        <?php } ?>
    </select>
    <select name="yıl">
        <?php for ($i = intval(date("Y")); $i > 1900; $i--) { ?>
        <option value="<?=$i;?>"><?=$i;?></option>
        <?php } ?>
    </select>
    <br/>
    cinsiyet: <br/>
    <select name="cinsiyet">
        <option value="k">kadın</option>
        <option value="e">erkek</option>
        <option value="d">şey</option>
    </select>
    <br/>
    <br/>
    <input type="submit" name="gönder" value="kayıt olayım artık"><br/><br/>
    <a href="sifreyolla.php" target="main" style="WORD-BREAK:keep-words;text-decoration:none;color:#000;"
       onmouseover='this.style.background="yellow";' onmouseout='this.style.background="none";'>unutuyom ben ya</a><br/>
    <a href="yeni_kullanici.php" target="main" style="WORD-BREAK:keep-words;text-decoration:none;color:#000;"
       onmouseover='this.style.background="yellow";' onmouseout='this.style.background="none";'>sözlüğü çok sevdim
        katılabilir miyim?</a>
</form>
<?php } ?>
</body>