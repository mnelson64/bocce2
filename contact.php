<?php
include "application_top.php";
$tmp_array = explode("\\",__FILE__);
if (count($tmp_array) < 2) {
	$tmp_array = explode("/",__FILE__); 
} 
$myFileName = $tmp_array[count($tmp_array) - 1];
$pageID=8;

if ($_POST['submit']) {	

$first_name=$_POST['first_name'];
$last_name=$_POST['last_name'];
$email=$_POST['email'];
$phone=$_POST['phone'];
$information_requested=$_POST['comment'];

if (trim($email) != '') {

	$xheaders .= "From: $email\r\n"; 
	//$xheaders .= "X-Mailer: PHP\n"; // mailer
	$xheaders .= "X-Priority: 6\n"; // Urgent Message!
	$xheaders.= "Content-Type: text/html; charset=iso-8859-1\n";


	$sub='Contact Request From an Bocce Club Site Visitor';
	
	$message1="<TABLE  cellspacing=2  cellpadding=5  width=500   border=1 bordercolorlight=#666666 style='font-family:Arial, Helvetica, sans-serif;font-size:12px'><td  colspan=2 align=center><b>Contact Request</b><br></td></tr>";
	$message1.="<TR><TD  ><strong>First Name </strong></TD><TD>&nbsp;<strong>Last Name</strong></TD></TR>";
	$message1.="<TR><TD  >".$first_name." </TD><TD>&nbsp;".$last_name."</TD></TR>";
	$message1.="<TR><TD  ><strong>Phone</strong></TD><TD>&nbsp;<strong>E-mail Address</strong></TD></TR>";
	$message1.="<TR><TD  >".$phone." </TD><TD  >".$email." </TD></TR>";
	$message1.="<TR><TD  colspan='2'><strong>Question/Comment</strong></TD></TR>";
	$message1.="<TR><TD  colspan='2'>".nl2br($information_requested)."</TD></TR></table>";
	
	if ($_POST['recipient'] == 9999) {
		$toAddress = "jrmisasi@sbcglobal.net";
	} elseif($_POST['recipient'] == 'bcf') {
		$toAddress = "spicylogfrogs@gmail.com";
	} else {
		$toAddress = $boardArray[$_POST['recipient']]['email'];
	}
	
	//mail("michael.nelson@techeffex.com",$sub,stripslashes($message1),$xheaders);
	mail($toAddress,$sub,stripslashes($message1),$xheaders);
	
	header("Location: ".$path."Thanks-For-Your-Message/");
	//echo $toAddress;
	exit;
}
} // if POST

$content_query=sprintf("SELECT * FROM pages WHERE pageID = '%s'",$pageID);
$content_set = mysql_query($content_query) or die(mysql_error());
$row_content_set = mysql_fetch_assoc($content_set);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?=SITE_NAME?>: <?=$row_content_set['pageTitle']?></title>
<meta name="description" content="<?=$row_content_set['pageDescription']?>" />
<meta name="keywords" content="<?=$row_content_set['pageKeywords']?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1.0,width=device-width">
<link href="<?=$path?>site.css" rel="stylesheet" type="text/css" />
<?php include "include/jquery.php";?>
<?php include "include/analytics.php"?>
</head>

<body class="sub">

<div id="main">
	<?php include "header.php";?>
    
  <div id="mainContentWrapper">
        <div id="viewPort">
        </div>
        <div id="subLeft">
            <h1><?=stripslashes($row_content_set['pageHeading'])?></h1>
            <?=stripslashes($row_content_set['pageContent'])?>
            <div id="contactForm">
				<?php if ($msg == 2) { ?>
                <div class="alert">Thank you. We have received your message. We will be in touch shortly.</div>
                <?php } ?>
                <div class="helpText">*Required fields</div>
                <form name="form1" id="form1" action="<?=$path?>Contact/" method="post" onSubmit="return checkForm();" >
                	<div  class="contactFormItem" id="contactFormToAddress">
                        <div class="label">Send to Who?</div>
                        <select name="recipient">
                        	<option value="9999">General Question</option>
                        	<?php foreach ($boardArray as $key => $boardMember) {?>
                            <option value="<?=$key?>"><?=$boardMember['name']?> - <?=$boardMember['title']?></option>
                           	<?php } ?>
                            <option value="bcf">Rich Gravelle - Bocce For A Cure</option>
                        </select>
                    </div>
                    <div  class="contactFormItem">
                        <div class="label">First Name*</div>
                        <input name="first_name" type="text" id="first_name"  required aria-required="true" onClick="clearError(this.id)"  onKeyDown="clearError(this.id)">
                    </div>
                    <div  class="contactFormItem">
                        <div class="label">Last Name</div>
                        <input name="last_name" type="text" id="last_name"  >
                    </div>
                    <div  class="contactFormItem">
                        <div class="label">Email Address*</div>
                        <input name="email" type="email" id="email"   required aria-required="true" onClick="clearError(this.id)"  onKeyDown="clearError(this.id)">
                    </div>
                    
                    <div  class="contactFormItem">
                        <div class="label">Phone Number</div>
                        <input name="phone" type="text" id="phone"  >
                    </div>
                    <div class="contactFormItem" id="contactFormComment">
                        <div class="label">Question or Comment</div>
                        <textarea name="comment"  rows="6" id="comment"  class="inputText"></textarea>
                    </div>
                    <div class="contactFormItem" id="contactFormButton">
                    <input name="submit" type="submit" value="Submit" id="submit">
                    </div>
                </form>
              </div>
        </div>
        <div id="subRight">
        <?php include "sideBoxRight.php";?></div>
          
  </div>
    <?php include "footer.php";?>
    
</div><!-- end main-->
<script type="text/javascript">
<!--
function checkForm() {
	if($("#first_name").val() == '' ) {
		alert("Please enter your first name")
		$("#first_name").addClass('hilightError');
		$("#first_name").focus();
		return false
	}
	if($("#email").val() == '' ) {
		alert("Please enter your email address")
		$("#email").addClass('hilightError');
		$("#email").focus();
		return false;
	}
	var str=$("#email").val();
	var AtTheRate= str.indexOf("@");
	var DotSap= str.lastIndexOf(".");
	if (AtTheRate==-1 || DotSap ==-1)
	{
		alert("Please enter a valid email address");
		$("#email").addClass('hilightError');
		$("#email").focus();
		return false;
	}
	else
	{
		if( AtTheRate > DotSap )
		{
		alert("Please enter a valid email address");
		$("#email").addClass('hilightError');
		$("#email").focus();
		return false;
		}
	}
}

function clearError(id) {
    $("#"+id).removeClass('hilightError');
    return false;	
    
}

-->
</script>
</body>
</html>