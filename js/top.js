function popup(url) {
    var width = 650;
    var height = 400;
    var left = (screen.width - width) / 2;
    var top = (screen.height - height) / 2;
    var params = 'width=' + width + ', height=' + height;
    params += ', top=' + top + ', left=' + left;
    params += ', directories=0';
    params += ', location=0';
    params += ', menubar=0';
    params += ', resizable=0';
    params += ', scrollbars=0';
    params += ', status=0';
    params += ', toolbar=0';
    newwin = window.open(url, 'windowname5', params);
    if (window.focus) {
        newwin.focus()
    }
    return false;
}

function formyolla(formname) {
    str = document.getElementById('konu').value.toString();
    if (!(/^\s*$/).test(str)) {
        return document.getElementById(formname);
    } else {
        alert("boşluk mu getireyim? :)");
    }
}
function arama() {
    str = document.getElementById('konu').value.toString();
    if (!(/^\s*$/).test(str)) {
        var urlString = "left.php?q=ara&konu=" + document.getElementById('konu').value;
        //var urlString = "ara.php?konu=" + document.getElementById('konu').value;
        window.open(urlString, "left");
    } else {
        alert("lütfen ara kısmına boş şeyler girmeyiniz :)");
    }
}
function logout() {

    if (confirm("Sözlükten çıkmak istediğinize emin misiniz?")) {
        popup('terk.php');
    }
}

var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-713453-5']);
_gaq.push(['_trackPageview']);

(function () {
    var ga = document.createElement('script');
    ga.type = 'text/javascript';
    ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(ga, s);
})();