<?php
/*******************************************
Spanish gb_lang file.

Translated by Leonardo Juszkiewicz (X.Cyclop)
x.cyclop@yahoo.com
*******************************************/
if ( eregi("gblang_es.php", $_SERVER["PHP_SELF"]) ) {
  header("location: ../gbconfig.php");
  exit();
}

# You can modify/translate some YapGB messages here (mainly error messages).
# Plese not that this interface is experimental and could change dramatically
# in future releases.
# I'd like to know whether you find it usefull or if you think of a better
# way of letting you edit YapGB messages.
#

# These configure the text of the next and previous page links.
$strNext = "[SIGUIENTE]";
$strPrev = "[ANTERIOR]";

# Error messages
$strError = "<font color=\"red\">ERROR</font>";
$strErrorNameRequired = "&iexcl;Olvidaste escribir tu nombre! Por favor int&eacute;ntalo de nuevo.";
$strErrorMessageRequired = "&iexcl;Olvidate escribir tu mensaje! Por favor int&eacute;ntalo de nuevo.";
$strErrorEmailRequired = "Se requiere un e-mail. Por favor escribe un e-mail v&aacute;lido.";
$strErrorUrlRequired = "Se requiere una direcci&oacute;n URL. Por favor escribe una direcci&oacute;n URL v&aacute;lida.";
$strErrorEmailIncorrect = "El e-mail no es v&aacute;lido. Por favor escribe un e-mail v&aacute;lido.";
$strErrorUrlIncorrect = "La direcci&oacute:n URL no es v&aacute;lida. Por favor corr&iacute;gelo.";
$strErrorMailFuncDisabled = "&iexcl;La funci&oacute;n mail() no est&aacute; habilitada!";
$strErrorOpeningFile = "&iexcl;Ocurri&oacute; un error al intentar acceder al archivo!";
$strErrorWrongPassword = "W&iexcl;Nombre de administrador o contrase&ntilde;a incorrecta!";
$strErrorNothingToDelete = "&iexcl;No hay nada para borrar!<br />Por favor <a href=\"javascript:history.go(-1);\">regresa.</a> e int&eacute;ntalo de nuevo.";
$strErrorDateNotReceived = "&iexcl;El campo de fecha no fue recibido!";
$strErrorDateNotMatch = "&iexcl;La fecha no coincide!";
$strErrorNoTrustedReferer = "&iexcl;Lo sentimos! No puedes escribir desde un sitio externo.";
$strErrorWrongConfirmationCode = "&iexcl;El c&oacute;digo de confirmaci&oacute;n no coincide! Por favor int&eacute;ntlao de nuevo.";

# I'd recommend you to edit this and let the user know how long he/she will have to wait
# before being able to post again. Let's say you configured (in gbconfig.php)
# $cfgSecondsToWait to 60 (so the user should wait a minute before posting again).
# You could set this variable to:
# $strErrorAlreadyPosted = "Sorry, you have to wait a minute before being able to post again!";
$strErrorAlreadyPosted = "&iexcl;Tienes que esperar antes de escribir de nuevo! Int&eacute;ntalo de nuevo m&aacute;s tarde.";


# Administration
$strGoToAdministration = "Regresar al panel de administraci&oacute;n";
$strAdminModifyDelete = "Administraci&oacute;n: modificar/eliminar entrada";

# Showed when an entry is succesfully deleted from the guestbook.
$strEntryDeleted = "&iexcl;La entrada se elimin&oacute; satisfactoriamente!";

$strPoweredBy = "Powered by";

# Not used yet (well, just if you show a message after saving user's post,
# but you have to configure it by editing yapgb.php code).
$strSignSuccesful = "&iexcl;Gracias por firmar mi libro de visitas!";
$strGoToBook = "Regresar al libro de visitas";

?>