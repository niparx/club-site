<?php
/*******************************************
Dutch gb_lang file.
Translated by Christian Gabriel
*******************************************/
if ( eregi("gblang_de.php", $_SERVER["PHP_SELF"]) ) {
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
$strNext = "[N&auml;chste]";
$strPrev = "[Vorherige;]";

# Error messages
$strError = "<font color=\"red\">Fehler</font>";
$strErrorNameRequired = "Bitte geben Sie Ihren Namen an!";
$strErrorMessageRequired = "Bitte tragen Sie Ihre Nachricht ein!";
$strErrorEmailRequired = "Eine g&uuml;ltige Emailadresse ist erforderlich.";
$strErrorUrlRequired = "Eine g&uuml;ltige Webadresse ist erforderlich!";
$strErrorEmailIncorrect = "Die angegebene Mailadresse ist ung&uuml;ltig!";
$strErrorUrlIncorrect = "Die angegebene Webadresse ist ung&uuml;ltig!";
$strErrorMailFuncDisabled = "Die mail() Funktion ist nicht verf&uuml;gbar!";
$strErrorOpeningFile = "Fehler beim &Ouml;ffnen der Datei!";
$strErrorWrongPassword = "Falsches Passwort oder Admin Name!";
$strErrorNothingToDelete = "Nichts zum l&ouml;schen!<br>Please <a href=\"javascript:history.go(-1);\">zur&uuml;ck.</a> und versuchen Sie es noch einmal.";
$strErrorDateNotReceived = "Keine Daten erhalten!";
$strErrorDateNotMatch = "Zeitangabe ist ungleich!";
$strErrorNoTrustedReferer = "You are not allowed to post from an external site. Sorry!<br /> Es ist Ihnen nicht gestattet, von einer externen Seite in das G&auml;stebuch zu schreiben";
$strErrorWrongConfirmationCode = "Der Best&auml;tigungscode wurde nicht richtig angegeben. Bitte noch einmal versuchen!";

# I'd recommend you to edit this and let the user know how long he/she will have to wait
# before being able to post again. Let's say you configured (in gbconfig.php)
# $cfgSecondsToWait to 60 (so the user should wait a minute before posting again).
# You could set this variable to:
# $strErrorAlreadyPosted = "Sorry, you have to wait a minute before being able to post again!";
$strErrorAlreadyPosted = "Bitte warten Sie einige Zeit mit Ihrem n&auml;chsten Eintrag.";


# Administration
$strGoToAdministration = "Zur&uuml;ck zur Admin Konsole";
$strAdminModifyDelete = "Admin: Eintrag editieren/l&ouml;schen";

# Showed when an entry is succesfully deleted from the guestbook.
$strEntryDeleted = "Eintrag erfolgreich gel&ouml;scht!";

$strPoweredBy = "Powered by";

# Not used yet (well, just if you show a message after saving user's post,
# but you have to configure it by editing yapgb.php code).
$strSignSuccesful = "Vielen Dank f&uuml;r Ihren Eintrag in das G&auml;stebuch!";
$strGoToBook = "Zur&uuml;ck zum G&auml;stebuch";

?>
