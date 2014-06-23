The Harvard Library communications team has produce a PHP-based form that allows users to select an image of an item from the Harvard Library collection and send a personalized holiday ecard to a specified email address. This ecard application has been made available to users to send Winter Holiday, Valentine’s Day, and Harvard Commencement cards.

The code for the application is available for open download, pulling, and forking at the following github repository: https://github.com/tdodson/ecard-generator.

The webform asks the user for basic information: from email, to email, sender name, image selection, and greeting. This data is stored in PHP variables in the main file, ecard.php, using the _POST method (with some basic transformations to strip most HTML tags, etc.). 

Ecard.php also contains scripting to check for user input errors (blank fields, exceeding maximum characters for message, etc.). A function in valdiation_functions.php is called to display the list of errors that the user needs to correct before previewing or sending the ecard.

The code of the HTML email to be sent is built up by populating the $html_email variable using a series of .= operators.

Upon completing the form, the user may send an email or preview the email to be sent. Selecting “preview” creates a new div in the page that outputs (prints) the contents of the $html_email variable.

If the “send” option is selected, the variables representing user input are handed off to sendemail.php which also contains the settings for the SMTP server that will send the email defined as a PHP object according to the structure required by the PHPMailer library (https://github.com/Synchro/PHPMailer). The application then calls the PHPMailer Send() function to send the email.

Finally, when the email is sent, sendmail.php logs the time when the send() function was run and whether it was successful, writing the results to “./log.txt.” To protect the privacy of card senders and recipients, only the time and success/failure of the sent message is recorder (i.e., not email addresses, message content, etc.).

Thomas Dodson
Web Designer and Developer
Harvard Library
thomas_dodson[at]harvard[dot]edu
