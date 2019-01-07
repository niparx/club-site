<?php
/*******************************************
Italian gb_lang file.

Translated by Roberto Casolari (aka) FRUIT
*******************************************/
if ( eregi("gblang_it.php", $_SERVER["PHP_SELF"]) ) {
  header("location: ../gbconfig.php");
  exit();
}

# You can modify/translate some YapGB messages here (mainly error messages).
# Plese not that this interface is experimental and could change dramatically
# in future releases.
# I'd like to know whether you find it usefull or if you think of a better
# way of letting you edit YapGB messages.
#

# Untranslated
# (maybe because these strings were added after the translation was created/updated)
$strErrorWrongConfirmationCode = "Confirmation code does not match! Please go back and try again.";

# These configure the text of the next and previous page links.
$strNext = "[AVANTI]";
$strPrev = "[INDIETRO]";

# Error messages
$strError = "<font color=\"red\">ERRORE</font>";
$strErrorNameRequired = "Hai dimenticato di scrivere il tuo nome! Torna indietro e riprova.";
$strErrorMessageRequired = "Hai dimenticato di scrivere il tuo messaggio! Torna indietro e riprova.";
$strErrorEmailRequired = "Un indirizzo e-mail &#232; richiesto. Torna indietro e riprova.";
$strErrorUrlRequired = "Un indirizzo web &#232; richiesto. Torna indietro e riprova.";
$strErrorEmailIncorrect = "L'indirizzo e-mail non &#232; valido. Torna indietro e correggilo.";
$strErrorUrlIncorrect = "L'indirizzo web non &#232; valido. Torna indietro e correggilo.";
$strErrorMailFuncDisabled = "La funzione mail() non &#232; disponibile!";
$strErrorOpeningFile = "C'&#232; stato un errore provando ad accedere al file!";
$strErrorWrongPassword = "Nome utente o password sbagliata!";
$strErrorNothingToDelete = "Niente da cancellare!<br><a href=\"javascript:history.go(-1);\">Torna indietro</a> e riprova.";
$strErrorDateNotReceived = "Il campo data non &#232; stato ricevuto!";
$strErrorDateNotMatch = "Data non corrisponde!";
$strErrorNoTrustedReferer = "Non sei abilitato a postare da un sito esterno. Spiacente!";

# I'd recommend you to edit this and let the user know how long he/she will have to wait
# before being able to post again. Let's say you configured (in gbconfig.php)
# $cfgSecondsToWait to 60 (so the user should wait a minute before posting again).
# You could set this variable to:
# $strErrorAlreadyPosted = "Sorry, you have to wait a minute before being able to post again!";
$strErrorAlreadyPosted = "Devi attendere prima di mettere un altro messaggio! Prova pi&#249; tardi.";


# Administration
$strGoToAdministration = "Torna al pannello di controllo";
$strAdminModifyDelete = "Admin: modifica/elimina riga";

# Showed when an entry is succesfully deleted from the guestbook.
$strEntryDeleted = "Record eliminato correttamente!";

$strPoweredBy = "Powered by";

# Not used yet (well, just if you show a message after saving user's post,
# but you have to configure it by editing yapgb.php code).
$strSignSuccesful = "Grazie per aver firmato il guestbook!";
$strGoToBook = "Torna al guestbook";


?>
