<?php
require_once('includes/validation_functions.php');
require_once('includes/phpmailer/class.phpmailer.php');
require_once('includes/phpmailer/class.smtp.php');
require_once('includes/sendemail.php');
// require_once('includes/analyticstracking.php');

$card_one		= 'unchecked';
$card_two		= 'unchecked';
$card_three		= 'unchecked';
$card_four		= 'unchecked';
$card_five		= 'unchecked';
$card_six		= 'unchecked';

// Setting these variables as empty right away so PHP doesn't get upset about undeclared variables. May remain unset or may be triggered by logic and assigned real values.
$preview = "";
$success = "";
$errors = "";

$image_file		= array("http://simmons.hul.harvard.edu/sites/default/files/ecard/bookhours1.jpg", "http://simmons.hul.harvard.edu/sites/default/files/ecard/snug.jpg", "http://simmons.hul.harvard.edu/sites/default/files/ecard/snowbell.jpg", "http://simmons.hul.harvard.edu/sites/default/files/ecard/coqdor.jpg", "http://simmons.hul.harvard.edu/sites/default/files/ecard/hollishill.jpg", "http://simmons.hul.harvard.edu/sites/default/files/ecard/holly.jpg");

$image_credit	= array("Illustration from <a href=\"http://pds.lib.harvard.edu/pds/view/10614144\"><em>Horae ad us um Romanum</em></a>, late 15th century French manuscript. Houghton Library.", "<a href=\"http://hollis.harvard.edu/fullrecordinnerframe.ashx?itemid=%7Cmisc/via%7Colvwork82103&imageid=HBS.BAKER.TC:1891&embed=false\">Trade card</a> from Union Pacific Tea Company, late 19th century. Baker Library, Harvard Business School.", "<a href=\"http://hollis.harvard.edu/fullrecordinnerframe.ashx?itemid=%7Cmisc/via%7Colvwork20007583&imageid=RAD.SCHL:375298&embed=false\">Engraving</a> of a young woman standing in the snow, pulling the servants' bell at the front of a house. Schlesinger Library on the History of Women in America, Radcliffe Institute.", "<a href=\"http://via.lib.harvard.edu/via/deliver/deepLinkItem?recordId=olvwork172884&componentId=FHCL.HOUGH:84527\">Watercolor of Ballets Russes</a>, 1914. Harvard Theatre Collection.", "A prospect of <a href=\"http://pds.lib.harvard.edu/pds/view/18029357\">Hollis Hall</a> in Cambridge, 1800. Harvard University Archives.", "<a href=\"http://pds.lib.harvard.edu/pds/view/30873561?n=69&imagesize=1200&jp2Res=.25&printThumbnails=no\">Drawing</a> from an album by Edward Lear, 19th century. Houghton Library.");


if(isset($_POST['preview']) || isset($_POST['send'])) {
		$friendfirst = trim($_POST["friend_first_name"]);
		$toemail = trim($_POST["friend_email"]);
		$firstname = trim($_POST["first_name"]);
		$fromemail = trim($_POST["email"]);
		$greeting = nl2br($_POST["greeting"]);
		// strip_tags prevents transmission of code from textarea, including links. Sorry spammers!
		$greeting = strip_tags($greeting, '<br><i><em><strong>');
		$image = $_POST["image"];
		$pos = "";
		
		if ($image == 'card_1') {
			$card_one = 'checked';
			$pos = 0;
		} 
		else if ($image == 'card_2') {
			$card_two = 'checked';
			$pos = 1;
		} 
		else if ($image == 'card_3') {
			$card_three = 'checked';
			$pos = 2;

		} 
		else if ($image == 'card_4') {
			$card_four = 'checked';
			$pos = 3;
		} 
		else if ($image == 'card_5') {
			$card_five = 'checked';
			$pos = 4;
		} 
		else if ($image == 'card_6') {
			$card_six = 'checked';
			$pos = 5;
		} 
		
		
		// User input Errors
		$errors = array();
		if (!has_presence($greeting)) {
			$errors['greeting_blank'] = "Please provide a greeting for your E-Card.";
		}
		if (!has_max_length($greeting, 500)) {
			$errors['greeting'] = "Please limit your greeting to 500 characters.";
		} 
		if (!has_presence($friendfirst)) {
			$errors['friendfirst_blank'] = "Please provide your friend's name.";
		}

		if (!has_presence($toemail)) {
			$errors['toemail_blank'] = "Please provide your friend's email address.";
		}
		if (!has_presence($firstname)) {
			$errors['firstname_blank'] = "Please provide your first name.";
		}
		if (!has_presence($fromemail)) {
			$errors['fromemail_blank'] = "Please provide your email address.";
		}
		if (!has_presence($image)) {
			$errors['image_blank'] = "Please select an image for your E-Card.";
		}
		// Error message (could print here, but do it lower down instead)
		// print form_errors($errors);

		// No Errors
		if(empty($errors)) {
		// Select image file & credit text
		$html_image 	= $image_file[$pos];
		$html_credit 	= $image_credit[$pos];

		// BUILD EMAIL
		$html_email = "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n";
		$html_email .= "<html>\n";
		$html_email .= "<head>\n";
		$html_email .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n";
		$html_email .= "<title>Harvard Library Holiday Card from " . $firstname."</title>\n";
		$html_email .= "<style type=\"text/css\">\n";
		$html_email .= "body {margin:0; padding:0;}\n";
		$html_email .= "a, a:hover, a:visited, a:focus {color: #a51c30;}\n";
		$html_email .= "tbody {background: #FFF;}\n";	
		$html_email .= "<!-- Media Queries -->\n";
		$html_email .= "@media only screen and (max-width: 660px) { table.container { width: 480px !important;}}\n";
		$html_email .= "@media only screen and (max-width: 510px) { table.container { width: 100% !important;}}\n";
		$html_email .= "<!-- End Media Queries -->\n";
		$html_email .= "</style>\n";
		$html_email .= "</head>\n";
		$html_email .= "<body bgcolor=\"#FFFFFF\">\n";
		$html_email .= "<!--Background table -->\n";
		$html_email .= "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#FFF\">\n";
		$html_email .= "<tr>\n";
		$html_email .= "<td>\n";
		$html_email .= "<table class=\"container\" style=\"width: 640px;\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#FFF\">\n";
		$html_email .= "<tr>\n";
		$html_email .= "<td class=\"header_container\" style=\"display:block!important; max-width:600px!important; margin: 0 auto!important; clear: both!important;\">\n";
		$html_email .= "<div class=\"content\" style=\"padding: 15px; max-width: 600px; display: block;\">\n";
		$html_email .= "<table bgcolor=\"#FFF\">\n";
		$html_email .= "<tr>\n";
		$html_email .= "<td>\n";
		$html_email .= "<a href=\"http://lib.harvard.edu\"><img src=\"http://library.harvard.edu/sites/all/themes/HarvardLibraryPortalTheme/logo.png\"></a>\n";
		$html_email .= "</td>\n";
		$html_email .= "</tr>\n";
		$html_email .= "</table>\n";
		$html_email .= "</div>\n";
		$html_email .= "</td>\n";
		$html_email .= "</tr>\n";
		$html_email .= "<tr>\n";
		$html_email .= "<td>\n";
		$html_email .= "<table class=\"container\">\n";
		$html_email .= "<tr>\n";
		$html_email .= "<td class=\"container\" bgcolor=\"#a51c30\" style=\"padding:15px;\">\n";
		$html_email .= "<p align=\"center\"><img src=".$html_image." style=\"width:inherit; max-width:100%;height:auto;\"></p>\n";
		$html_email .= "<p class=\"lead\" style=\"font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 17px; color: #FFF; margin-bottom: 10px; font-weight: normal; line-height: 135%; \">\n";
		$html_email .= $greeting . "\n";
		$html_email .= "</p>\n";
		$html_email .= "</td>\n";
		$html_email .= "</tr>\n";
		$html_email .= "</table>\n";
		$html_email .= "</td>\n";
		$html_email .= "</tr>\n";
		$html_email .= "</table>\n";
		$html_email .= "<!--Close background table -->\n";
		$html_email .= "<p align=\"center\" style=\"font-size: 10px; margin-top: 20px;\">Trouble viewing this card? Make sure images are enabled.<p/>\n";
		$html_email .= "<p align=\"center\" style=\"font-size: 10px; margin-top: 20px;\">Image: " . $html_credit."<p/>\n";
		$html_email .= "<p align=\"center\" style=\"font-size: 10px; margin-top: 20px;\">Send your own <a href=\"http://lib.harvard.edu/sites/all/themes/HarvardLibraryPortalTheme/ecard/ecard.php\">Harvard Library E-Card</a>.<p/>\n";
		$html_email .= "</body>\n";
		$html_email .= "</html>\n";

			if (isset($_POST['preview'])) {
				// SHOW PREVIEW
				$preview = "<div class='preview'>Please find a preview of your card below. Note that the card has not been sent. To send the card, scroll to the <a href=\"#previewbtn\">bottom of this form</a> and click \"Send Your Harvard Library E-Card.\" The information you entered into the form is still there.</div><div class=\"divider\"></div>";
				$preview .= $html_email . "<div class=\"divider\"></div>";
			}
			else if (isset($_POST['send'])) {
				// SEND EMAIL
				send_email();
				$success = "<div class='send'>Thanks, {$firstname}. Your E-Card has been sent.</div>";
			}
		}
	} 
	else {
		$friendfirst = "";
		$toemail = "";
		$firstname = "";
		$fromemail = "";
		$image = "";
		$greeting = "";
	}
?>

<html lang="en">
<head>
	<title>Send a Harvard Library E-Card</title>
	<link rel="stylesheet" type="text/css" href="ecard_form.css">
	<script language="javascript" type="text/javascript" src="./libraries/jquery-1.10.2.js"></script>
	<script language="javascript" type="text/javascript">
	   $(document).ready(function() {
	   	   $("input[name='image']").change(function() {
	   	   		var card = $(this).attr('value');
	   	   		$("textarea").text($("span#" + card).html());
	   	   });	
		});
</script>
</head>
<body>
<div class="outerwrapper">
	<div class="wrapper">
	<div class="myacctellusholder"></div>
	<div class="logo"><a href="http://lib.harvard.edu"><img alt="Harvard Library Logo" src="http://library.harvard.edu/sites/all/themes/HarvardLibraryPortalTheme/logo.png"></a></div>
	<div class="divider"></div>
	<div class="breadcrumbs"> 
		<p><a href="http://lib.harvard.edu" title="Home">Home</a> > Harvard Library E-Card </p>
	</div>
	<?php print $preview; ?>
	<?php print $success; ?>
	<?php print form_errors($errors); ?>
		<div class="container" align="center">
		<h1>Send a Holiday E-Card from the Harvard Library</h1>
		<div class="form_box">
			<div align="left" class="form_inner">
				To send your card:<br />
					<ol>
						<li>Select one of the images below from the Harvard Library collection.</li>
						<li>Enter your personal message along with the "from" and "to" information.</li>
						<li><em>Optional</em>: Click "Preview Your E-Card" to see how your card will look before sending.</li>
						<li>Click "Send Your Harvard Library E-Card."</li>
					</ol><br />
				<form action="ecard.php" method="post">
					<p><label for="image_select" style="font-size: 14px;">Select an image below</label><span class="crimson">*</span>
											<table id="image_select">
												<tr>
													<td>
														<img src="http://simmons.hul.harvard.edu/sites/default/files/ecard/bookhours1_280.jpg">
														<input class="radio" type="radio" name="image" value="card_1"
														<?php print $card_one; ?>/> <span id="card_1">Wishing you and yours the glories of the season.</span><br /><em>Image from a 15th century French book of hours, Houghton Library</em>.
													</td>
													<td>
														<img src="http://simmons.hul.harvard.edu/sites/default/files/ecard/snug_280.jpg">
														<input class="radio" type="radio" name="image" value="card_2"
														<?php print $card_two; ?>/><span id="card_2">May you and your loved ones be all snug in your beds this holiday season.</span><br /><em>Image from a 19th century trade card, Baker Library</em>.
													</td>
												</tr>
												<tr>
													<td>
														<img src="http://simmons.hul.harvard.edu/sites/default/files/ecard/snowbell_280.jpg">
														<input class="radio" type="radio" name="image" value="card_3"
														<?php print $card_three; ?>/> <span id="card_3">Sending the warmest wishes to you this holiday.</span><br /><em>Image from a 19th century engraving, Schlesinger Library</em>.
													</td>
													<td>
														<img src="http://simmons.hul.harvard.edu/sites/default/files/ecard/coqdor_280.jpg">
														<input class="radio" type="radio" name="image" value="card_4"
														<?php print $card_four; ?>/><span id="card_4">May your holiday be bright!</span><br /><em>Image from a 1914 watercolor in the Ballets Russes collection, Houghton Library</em>.
													</td>
												</tr>
												<tr>
													<td>
														<img src="http://simmons.hul.harvard.edu/sites/default/files/ecard/hollishill_280.jpg">
														<input class="radio" type="radio" name="image" value="card_5"
														<?php print $card_five; ?>/> <span id="card_5">From our hall to yours, the very best wishes this holiday season!</span><br /><em>Image from an 1800 drawing of Hollis Hall, Harvard University Archives.</em>
													</td>
													<td>
														<img src="http://simmons.hul.harvard.edu/sites/default/files/ecard/holly_280.jpg">
														<input class="radio" type="radio" name="image" value="card_6"
														<?php print $card_six; ?>/><span id="card_6">Have a happy, holly holiday!</span><br /><em>Image from a 19th century drawing by Edward Lear, Houghton Library.</em>
													</td>
												</tr>
											</table>
					</p>
					<p class="greeting">Enter a personal greeting (max. 500 characters)<textarea name="greeting"><?php print $greeting; ?></textarea></p>

					<a href="http://lib.harvard.edu"><img class="shield" alt="Harvard Library Logo" src="http://library.harvard.edu/sites/all/themes/HarvardLibraryPortalTheme/logo.png"></a>
				
					<p><label for="friend_first_name">Your Friend's First Name</label><span class="crimson">*</span>
						<input id="friend_first_name" type="text" name="friend_first_name" value="<?php print $friendfirst;?>" title="Type the recipient's name here." />
					</p>
					<p><label for="friend_email">Your Friend's Email Address</label><span class="crimson">*</span>
						<input id="friend_email" type="email" name="friend_email" value="<?php print $toemail;?>" title="Type the recipient's email address here; be sure it's a valid email address (john@example.com)." />
					</p>
					<p><label for="sender_first_name">Your First Name</label><span class="crimson">*</span>
						<input id="sender_first_name" type="text" name="first_name" value="<?php print $firstname;?>" title="Type your first name here."/>
					</p>
					<p><label for="sender_email">Your Email Address</label><span class="crimson">*</span>
						<input id="sender_email" type="email" name="email" value="<?php print $fromemail;?>" title="Type your email address here; be sure it's a valid email address (myname@example.com)."/>
					</p>
					<p>
					<input id="previewbtn" class="button" type="submit" name="preview" value="Preview Your E-Card (Optional)" />
					<input class="button" type="submit" name="send" value="Send Your Harvard Library E-Card" />
					</p>
				</form>
				<p><span class="crimson">*</span>Required field</p><br/>
			</div>
		</div>
	</div>
	</div>
<div class="footer1">
		<a href="lib.harvard.edu"><img alt="Harvard University" border="0" height="56" src="http://lib.harvard.edu/sites/all/themes/HarvardLibraryPortalTheme/images/logo-footer.gif" style="padding-left: 50px;" title="Harvard University" width="230"></a>
		<div class="footaddress"><p>Harvard University</p><p>Cambridge, MA 02138</p><p>617.495.1000 | <a href="http://library.harvard.edu/report-problem" title="Feedback" target="_blank">Feedback</a></p></div>
	</div>
	<div class="footer2">
		<ul class="menu">
			<li><a class="first" href="http://www.trademark.harvard.edu/trademark_protection/notice.php" target="_blank" title="Trademark Notice">Trademark Notice</a></li>
			<li><a href="http://www.harvard.edu/reporting-copyright-infringements" target="_blank" title="Report a Copyright Infringement">Report a Copyright Infringement</a></li>
			<li><a href="http://lib.harvard.edu/privacy-statement" title="Privacy Statement">Privacy Statement</a></li>
			<li><a href="http://www.accessibility.harvard.edu/" target="_blank" title="Accessibility">Accessibility</a></li>
			<li><a href="sitemap" title="Sitemap">Sitemap</a></li>
			<li><a href="http://www.harvard.edu" target="_blank" title="Harvard University"><span class="last">Harvard University</span></a></li>
		</ul>
		<p>Copyright 2013 The President and Fellows of Harvard College</p><br /></div>
</div>
</body>
</html>