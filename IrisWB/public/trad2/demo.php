<?php
$lang='fr_FR.utf8';
$filename = 'default';
putenv("LC_ALL=$lang");
setlocale(LC_ALL, $lang);
bindtextdomain($filename, './locale');

bind_textdomain_codeset($filename, "UTF-8");
textdomain($filename);
?>

<br>
<?php
echo gettext("thank-you");
?>
<br>
<?php
echo _("hi");
?>
<br>
<?php
printf(_("Pi is something like %01.2f"), pi());
echo _("thank-you")." "._("bye");
?>
<br>
<?php
echo _("LOL");
?>

