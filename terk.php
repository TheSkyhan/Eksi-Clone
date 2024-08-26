<?php
session_start();
session_destroy();
?>
<html>
    <head>
	<meta name="description" content="Kadim Sözlük" />
	<meta name="keywords" content="kolektif,sözlük" />
	<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="css/sozluk.css" />
	<link rel="stylesheet" type="text/css" href="css/jquery-ui.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
    </head>
    <body>
	<script type='text/javascript'>
	    setTimeout('self.close()', 4000);
	    opener.parent.frames["ust"].location.reload();
	    opener.parent.frames["main"].location.reload();

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
    </head>
    <p align="left">sözlükten çıkış yaptınız. yine bekleriz :)</p>
</body>