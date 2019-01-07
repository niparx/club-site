<?php
if ( eregi("gblang_en.php", $_SERVER["PHP_SELF"]) ) {
  header("location: ../gbconfig.php");
  exit();
}

# You can modify/translate some YapGB messages here (mainly error messages).
# Plese not that this interface is experimental and could change dramatically
# in future releases.
# If you want your guestbook interface to be in a language other than English,
# you'll need to edit your theme files.
# I'd like to know whether you find it usefull or if you think of a better
# way of letting you edit YapGB messages.

# These configure the text of the next and previous page links.
$strNext = "[NEXT]";
$strPrev = "[PREV]";

# Error messages
$strError = "<font color=\"red\">ERROR</font>";
$strErrorNameRequired = "You forgot to write your name! Please try again.";
$strErrorMessageRequired = "You forgot to write your message! Please try again.";
$strErrorEmailRequired = "An email address is required. Please enter a valid email address.";
$strErrorUrlRequired = "An url address is required. Please enter a valid url address.";
$strErrorEmailIncorrect = "The email address is not valid. Plesae enter a valid email address.";
$strErrorUrlIncorrect = "The url address is not valid. Please correct it.";
$strErrorMailFuncDisabled = "The mail() function is not available!";
$strErrorOpeningFile = "There was an error while trying to access the file!";
$strErrorWrongPassword = "Wrong password or admin name!";
$strErrorNothingToDelete = "Nothing to delete!<br>Please <a href=\"javascript:history.go(-1);\">go back.</a> and try again.";
$strErrorDateNotReceived = "Date field was not received!";
$strErrorDateNotMatch = "Date does not match!";
$strErrorNoTrustedReferer = "You are not allowed to post from an external site. Sorry!";
$strErrorWrongConfirmationCode = "Confirmation code does not match! Please try again.";

# I'd recommend you to edit this and let the user know how long he/she will have to wait
# before being able to post again. Let's say you configured (in gbconfig.php)
# $cfgSecondsToWait to 60 (so the user should wait a minute before posting again).
# You could set this variable to:
# $strErrorAlreadyPosted = "Sorry, you have to wait a minute before being able to post again!";
$strErrorAlreadyPosted = "You have to wait before posting again! Try again later.";


# Administration
$strGoToAdministration = "Go back to admin panel";
$strAdminModifyDelete = "Admin: modify/delete entry";

# Showed when an entry is succesfully deleted from the guestbook.
$strEntryDeleted = "Entry deleted succesfully!";

$strPoweredBy = "Powered by";

# Not used yet (well, just if you show a message after saving user's post,
# but you have to configure it by editing yapgb.php code).
$strSignSuccesful = "Thanks for signing my guestbook!";
$strGoToBook = "Go back to guestbook";

?>