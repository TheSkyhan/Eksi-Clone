<?php
require('header.php');
$login = isset($_SESSION["nick"]);
?>

<body>
    <div>

	<img src='https://images-cdn.fantasyflightgames.com/ffg_content/wfrp/Black%20Fire%20Pass/16_bookOgrudges_FWalls.png' width='132' height='45'>

	<div class="frst" style="width:800px;height:40px;position:absolute;left:125px;top:0px">
	    <a href="left.php?q=rastgele" target="left">rastgele</a>

	    <a href="left.php?q=bir gün" target="left">bir gün</a>

	    <?php if (!$login) { ?>
    	    <a href="goster.php?konu=sözlük hakkında en çok sorulan sorular" target="main">bu ne?</a>
	    <?php } ?>

	    <?php if ($login) { ?>
    	    <a href="left.php?q=ukte" target="left">ukte</a>
	    <?php } ?>

	    <?php if ($login) { ?>
    	    <a href="left.php?q=kenar" target="left">kenar</a> 
	    <?php } ?>

	    <a href="main.php?q=istatistikler" target="main">istatistik</a>

	    <?php if (!$login) { ?>
    	    <a href="kullanici.php" target="main" style="width:156px;">kullanıcı girişi</a>
	    <?php } ?>

	    <?php if (!$login) { ?>
    	    <a href="main.php?q=yeni kullanıcı" target="main">üye ol</a>
	    <?php } ?>

	    <?php if ($login) { ?>
    	    <a href="main.php?q=sub-etha" target="main">fasiliteler</a>
    	    <a href="kontrol.php" style="width: 105px;" target="main">panel</a>
	    <?php } ?>

	    <a href="main.php?q=iletişim" target="main">iletişim</a>

	    <?php if ($login) { ?>
    	    <a href="javascript:logout()" target="ust">çıkış</a>
    	    <a href="ben.php" target="main">ben</a>
	    <?php } ?>
	</div>

	<div class="frst" style="float:left;position:absolute;left:125px;top:20px;height:40px;">
	    <form action="goster.php" id="getir_ara" method="get" target="main">
		<a href="left.php?q=bugün" target="left">bugün</a>

		<a href="left.php?q=dün" target="left">dün</a>

		<?php if ($login) { ?>
    		<a href="left.php?q=badi" target="left">badi</a>
    		<a href="left.php?q=son" target="left">son</a>
		<?php } ?>
		<?php if (!$login) { ?>
    		<a href="left.php?q=gecenyil" target="left"><?php echo date("Y", strtotime("-1 year")); ?></a>
		<?php } ?>
		<?php if ($login) { ?>
    		<a href="main.php?q=?" target="main">kötü</a>
		<?php } ?>
		<a href="main.php?q=:)" target="main">iyiler</a>
		<input id="konu" type="text" name="konu" maxlength="255"/>
		<a href="javascript:formyolla('getir_ara').submit()">getir</a>
		<a href="javascript:arama()">ara</a>
	    </form>
	</div>
    </div>
</body>
</html>