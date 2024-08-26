<?php
require_once('header.php');

if (isset($_POST["gönder"])) {
    $to = 'baransakallioglu@gmail.com';
    $subject = $_POST["konu"];
    $message = $_POST["mesaj"];
    $headers = 'From: Kadim Sözlük ' . $_POST["adres"] . "\r\n";

    $pattern = "/^\s*$/";
    if (preg_match($pattern, $subject))
	die("boş konu girdiniz lütfen konunuzu belirleyip gelin :)");

    if (preg_match($pattern, $message))
	die("bir mesaj yazın ki cevap atabilelim di mi? :)");

    $pattern = "/^[^@]*@[^@]*\.[^@]*$/";
    if (!preg_match($pattern, $_POST["adres"]))
	die("lütfen geçerli bir e-mail adresi girin ki sizi muhattap alabilelim :)");

    mail($to, $subject, $message, $headers);

    echo "mail başarıyla gönderildi";
} else {
    ?>
    <table width="50%" border="0">
        <tr>
        <form id="mail" name="mail" method="post">
    	<tr>
    	    <td colspan="2" align="justify">Kadim Sözlük ile ilgili düşünce görüş önerilerinizi bildirmek, merak
    		ettiğiniz sorulara yanıt bulmak için bu formu kullanabilirsiniz. <br/>(*)
    		ile olan kısımların doldurulması zorunludur.<br/></td>
    	</tr>
    	<tr>
    	    <td>e-mail adresiniz: (*)</td>
    	    <td align="left"><input type="text" id="adres" name="adres" size="50"/></td>
    	</tr>
    	<tr>
    	    <td>konu: (*)</td>
    	    <td align="left"><input type="text" id="konu" name="konu" size="50"/
    				    value="<?= $_GET["sikayet_konusu"]; ?>">
	    </td>
	</tr>
	<tr>
	    <td style="vertical-align:top;">mesaj: (*)</td>
	    <td align="left"><textarea rows="10" cols="50" id="mesaj" name="mesaj"></textarea></td>
	</tr>
	<tr>
	    <td></td>
	    <td align="left"><input type="submit" name="gönder" value="gönder"/></td>
	</tr>
    </form>
</tr>
</table><?php } ?>
</body>