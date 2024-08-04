<?
require('includes/application_top.php');
/*echo"<pre>";
print_r($_REQUEST);*/
//exit;
$country = 99;
if(isset($_POST['Submit'])&& $_POST['Submit']=="Submit"){
	

    $company_name = $_POST['company_name'];
    $contact_name = $_POST['contact_name'];
    $e_mail = $_POST['e_mail'];
    $website = $_POST['website']; 
    $phone = $_POST['phone'];
    $fax = $_POST['fax']; 
    $street = $_POST['street'];
    $town_city = $_POST['town_city'];
    $state = $_POST['state'];
    $country = $_POST['country'];
    $postal_code = $_POST['postal_code'];
    $comment = $_POST['comment']; 



	
		if( $_SESSION['security_code'] == $_POST['key'] && !empty($_SESSION['security_code'] ) ) {
			$to=STORE_OWNER_EMAIL_ADDRESS;
			$from = trim($e_mail);
			$subject = "Contact Us From Shivalikbimetals.com - date - " . date("d M, Y");
			
				$message = "<table cellspacing=\"2\" cellpadding=\"4\" border=\"0\" width=\"100%\">";
				
				$message = "<table cellspacing=\"2\" cellpadding=\"4\" border=\"0\" width=\"100%\">";
				$message .= "<tr>";
				$message .= "<td colspan=\"2\"><font face=\"verdana\" size=\"2\" color=\"#0861AD\"><b>";
				$message .= $subject. "<br>";
				$message .= "</b></font>";
				$message .= "</td>";
				$message .= "</tr>";
				
			if(isset($_POST['company_name']) && $company_name!="")
			{			
				$message .= "<tr>";
				$message .= "<td width=\"40%\"><font face=\"verdana\" size=\"2\">Company Name : ";
				$message .= "</font></td>";
				$message .= "<td width=\"60%\"><font face=\"verdana\" size=\"2\">".trim($company_name);
				$message .= "</font></td>";
				$message .= "</tr>";
			}	
			
			if(isset($_POST['contact_name']) && $contact_name!="")
			{			
				$message .= "<tr>";
				$message .= "<td width=\"40%\"><font face=\"verdana\" size=\"2\">Contact Name  : ";
				$message .= "</font></td>";
				$message .= "<td width=\"60%\"><font face=\"verdana\" size=\"2\">".trim($contact_name);
				$message .= "</font></td>";
				$message .= "</tr>";
			}
			
			if(isset($_POST['e_mail']) && $e_mail!="")
			{	
				$message .= "<tr>";
				$message .= "<td width=\"40%\"><font face=\"verdana\" size=\"2\">E-Mail : ";
				$message .= "</font></td>";
				$message .= "<td width=\"60%\"><font face=\"verdana\" size=\"2\">".trim($e_mail);
				$message .= "</font></td>";
				$message .= "</tr>";
			}
		
			if(isset($_POST['website']) && $website!="")
			{			
				$message .= "<tr>";
				$message .= "<td width=\"40%\"><font face=\"verdana\" size=\"2\">Website : ";
				$message .= "</font></td>";
				$message .= "<td width=\"60%\"><font face=\"verdana\" size=\"2\">".trim($website);
				$message .= "</font></td>";
				$message .= "</tr>";
			}	
			
			if(isset($_POST['phone']) && $phone!="")
			{			
				$message .= "<tr>";
				$message .= "<td width=\"40%\"><font face=\"verdana\" size=\"2\">Phone  : ";
				$message .= "</font></td>";
				$message .= "<td width=\"60%\"><font face=\"verdana\" size=\"2\">".trim($phone);
				$message .= "</font></td>";
				$message .= "</tr>";
			}
			
			if(isset($_POST['fax']) && $fax!="")
			{	
				$message .= "<tr>";
				$message .= "<td width=\"40%\"><font face=\"verdana\" size=\"2\">Fax : ";
				$message .= "</font></td>";
				$message .= "<td width=\"60%\"><font face=\"verdana\" size=\"2\">".trim($fax);
				$message .= "</font></td>";
				$message .= "</tr>";
			}	
			if(isset($_POST['street']) && $street!="")
			{			
				$message .= "<tr>";
				$message .= "<td width=\"40%\"><font face=\"verdana\" size=\"2\">Address : ";
				$message .= "</font></td>";
				$message .= "<td width=\"60%\"><font face=\"verdana\" size=\"2\">".trim($company_name);
				$message .= "</font></td>";
				$message .= "</tr>";
			}	
			
			if(isset($_POST['town_city']) && $town_city!="")
			{			
				$message .= "<tr>";
				$message .= "<td width=\"40%\"><font face=\"verdana\" size=\"2\">Town/City  : ";
				$message .= "</font></td>";
				$message .= "<td width=\"60%\"><font face=\"verdana\" size=\"2\">".trim($town_city);
				$message .= "</font></td>";
				$message .= "</tr>";
			}
			
			if(isset($_POST['state']) && $state!="")
			{	
				$message .= "<tr>";
				$message .= "<td width=\"40%\"><font face=\"verdana\" size=\"2\">State : ";
				$message .= "</font></td>";
				$message .= "<td width=\"60%\"><font face=\"verdana\" size=\"2\">".trim($state);
				$message .= "</font></td>";
				$message .= "</tr>";
			}	
			
			if(isset($_POST['postal_code']) && $postal_code!="")
			{			
				$message .= "<tr>";
				$message .= "<td width=\"40%\"><font face=\"verdana\" size=\"2\">Postal Code  : ";
				$message .= "</font></td>";
				$message .= "<td width=\"60%\"><font face=\"verdana\" size=\"2\">".trim($postal_code);
				$message .= "</font></td>";
				$message .= "</tr>";
			}
			
			if(isset($_POST['country']) && $country!="")
			{			
				$message .= "<tr>";
				$message .= "<td width=\"40%\"><font face=\"verdana\" size=\"2\">Country : ";
				$message .= "</font></td>";
				$message .= "<td width=\"60%\"><font face=\"verdana\" size=\"2\">".country($country);
				$message .= "</font></td>";
				$message .= "</tr>";
			}	
			
			if(isset($_POST['comment']) && $comment!="")
			{	
				$message .= "<tr>";
				$message .= "<td width=\"40%\" valign=\"top\"><font face=\"verdana\" size=\"2\">Comment : ";
				$message .= "</font></td>";
				$message .= "<td width=\"60%\"><font face=\"verdana\" size=\"2\">".nl2br(trim($comment));
				$message .= "</font></td>";
				$message .= "</tr>";
			}
						
				$message .= "</table>";
				
				$sefrom = 'shivalik@shivalikbimetals.com';
				$mail = new PHPMailer();
				$mail->SetFrom($sefrom);
				$mail->AddReplyTo($from);
				$mail->addCC(CC_EMAIL_ADDRESS);
				$mail->addCC(CC_STORE_OWNER_EMAIL_ADDRESS);
				$mail->AddAddress($to);
				$mail->WordWrap = 50;         
				$mail->IsHTML(true);                                    
				$mail->Subject =$subject;		
				$mail->MsgHTML($message);
				$mail->AltBody = $message;
				$mail->AddCustomHeader('Return-path:'.STORE_OWNER_EMAIL_ADDRESS);
				$result = $mail->Send();
					

				/*$mail = new htmlMimeMail();
				$mail->setReturnPath($from);
				$mail->setFrom(STORE_OWNER_EMAIL_ADDRESS);
				
				//$mail->setFrom($from);
				//$mail->setFrom(STORE_OWNER_EMAIL_ADDRESS);
				$mail->setCc(CC_EMAIL_ADDRESS);
				$mail->setSubject($subject);
				$mail->setHtml($message);
				$result = $mail->send(array($to));
				
				*/
				//$result = send_mail($to, $from, STORE_ORDER_CC, '', $subject, $message);
				
				if($result){
						$msg = "Thank you for your submission.<br>We will contact you soon.";
					}else{
						$msg = "There was some error in sending the mail. Please try again.";
					}
					$str_url= "message=".urlencode($msg);
				header("Location: contact-us.php?".$str_url);
		   exit;
	   }
	   else
	   {
	  	 $err_msg = "Sorry, you have provided an invalid security code.";
	   }



}

if(isset($_GET['message']))
{
$msg=urldecode($_GET['message']);

}






$pageId = 31;

$pageDataquery = mysql_query("select * FROM tbl_cms WHERE cms_id='".$pageId."'") or die("Error: ". mysql_error());
$pageDetails   = mysql_fetch_assoc($pageDataquery);
$pageUrl       = fun_db_output($pageDetails['page_url']);
/*if($pageUrl)
{
	header("location:".$pageUrl);
	exit();
}*/


$pageName      = fun_db_output($pageDetails['title']);
$pageDesc      = fun_db_output($pageDetails['description']);
if($pageDetails['page_title'])
	$page_title=$pageDetails['page_title'];
else
	$page_title=TITLE;

if($pageDetails['page_description'])
	$page_description=$pageDetails['page_description'];
else
	$page_description=TITLE;

if($pageDetails['page_keywords'])
	$page_keywords=$pageDetails['page_keywords'];
else
	$page_keywords=TITLE;
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?=$page_title?></title>
<meta name="keywords" content="<?=$page_keywords;?>">
<meta name="description" content="<?=$page_description;?>">
<base href="<?php echo SITE_CATALOG ?>">
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="SpryAssets/inner.css" rel="stylesheet" type="text/css" />
<script language="JavaScript">
function check(theform)
{
	if(theform.contact_name.value=="")
	{
		alert("Error !\nPlease enter your  Name.");
		theform.contact_name.focus();
		return false;
	}
	if(theform.e_mail.value=="")
	{
		alert("Error !\nPlease enter your Email.");
		theform.e_mail.focus();
		return false;
	}
	else
	{ if(theform.e_mail.value.indexOf("@",1)== -1) 
	  {
		alert ("Error !\nEnter a valid E-mail Address.");
		theform.e_mail.value="";
		theform.e_mail.focus();
		return false;
	  }
	}


	if(theform.key.value==""){
		alert("Please enter the Security Code");
		theform.key.focus();
		return false;
	}

	return true;
}
//-->
</script>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="js/fadeslideshow.js">

/***********************************************
* Ultimate Fade In Slideshow v2.0- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
***********************************************/
</script>
</head>

<body>
<div class="main">
  <div class="inner-main">
    <div class="gray-main">
      <? include('header-flex.php');?>
    </div>
    <div class="inner-banner"><img src="images/about-us-banner.jpg" width="1019" height="199" /></div>
    <div class="inner-page-main">
      
        <div class="part1-inner">
        <!-- <div style="border-top:1px solid #dadada; margin-bottom:18px; "></div> -->
        <h1> Related Party Transactions </h1> <br>
        <table style="border: medium none" width="100%" bgcolor="#dbdbdb" border="0" cellpadding="5" cellspacing="1">
	<tbody>
		<tr>
			<td width="50%" align="left" bgcolor="f2f2f2"><b>Title</b></td>
			<td width="50%" align="left" bgcolor="f2f2f2"><b>Download</b></td>
		</tr>
		<tr>
			<td align="left" bgcolor="#FFFFFF"><a href="images/pdf/RPTDISCLOSURE30092021.pdf" target="_blank" style="color: #333333">Disclosure On Related Party Transactions For The Half Year Ended 30th September 2021</a></td>
			<td align="left" bgcolor="#FFFFFF"><a href="images/pdf/RPTDISCLOSURE30092021.pdf" target="_blank"><img src="images/download.png" align="absmiddle" /> Download</a></td>
		</tr>

		<tr>
			<td align="left" bgcolor="#FFFFFF"><a href="images/pdf/Disclosure-of-Related-Party-Transaction_31.03.2021.pdf" target="_blank" style="color: #333333">Disclosure On Related Party Transactions For The Half Year Ended 31st March 2021</a></td>
			<td align="left" bgcolor="#FFFFFF"><a href="images/pdf/Disclosure-of-Related-Party-Transaction_31.03.2021.pdf" target="_blank"><img src="images/download.png" align="absmiddle" /> Download</a></td>
		</tr>
		<tr>
			<td align="left" bgcolor="#FFFFFF"><a href="images/pdf/Disclosure-of-Related-Party-Transaction_30.09.2020.pdf" target="_blank" style="color: #333333">Disclosure On Related Party Transactions For The Half Year Ended 30th September 2020</a></td>
			<td align="left" bgcolor="#FFFFFF"><a href="images/pdf/Disclosure-of-Related-Party-Transaction_30.09.2020.pdf" target="_blank"><img src="images/download.png" align="absmiddle" /> Download</a></td>
		</tr>

        <tr>
			<td align="left" bgcolor="#FFFFFF"><a href="images/pdf/Disclosure-of-Related-Party-Transaction_31.03.2020.pdf" target="_blank" style="color: #333333">Disclosure On Related Party Transactions For The Half Year Ended 31st March 2020</a></td>
			<td align="left" bgcolor="#FFFFFF"><a href="images/pdf/Disclosure-of-Related-Party-Transaction_31.03.2020.pdf" target="_blank"><img src="images/download.png" align="absmiddle" /> Download</a></td>
		</tr>


        <tr>
			<td align="left" bgcolor="#FFFFFF"><a href="images/pdf/Disclosure-of-Related-Party-Transaction_30.09.2019.pdf" target="_blank" style="color: #333333">Disclosure On Related Party Transactions For The Half Year Ended 30th September 2019</a></td>
			<td align="left" bgcolor="#FFFFFF"><a href="images/pdf/Disclosure-of-Related-Party-Transaction_30.09.2019.pdf" target="_blank"><img src="images/download.png" align="absmiddle" /> Download</a></td>
		</tr>

        <tr>
			<td align="left" bgcolor="#FFFFFF"><a href="images/pdf/Disclosure-of-RelatedParty-Transaction_31.03.2019.pdf" target="_blank" style="color: #333333">Disclosure On Related Party Transactions For The Half Year Ended 31st March 2019</a></td>
			<td align="left" bgcolor="#FFFFFF"><a href="images/pdf/Disclosure-of-RelatedParty-Transaction_31.03.2019.pdf" target="_blank"><img src="images/download.png" align="absmiddle" /> Download</a></td>
		</tr>

		<tr>
			<td align="left" bgcolor="#FFFFFF"><a href="images/pdf/Covering-letter_RPT Disclsoure_31.03.2022.pdf" target="_blank" style="color: #333333">Disclosure On Related Party Transactions For The Half Year Ended 31st March, 2022</a></td>
			<td align="left" bgcolor="#FFFFFF"><a href="images/pdf/Covering-letter_RPT Disclsoure_31.03.2022.pdf" target="_blank"><img src="images/download.png" align="absmiddle" /> Download</a></td>
		</tr>
		
		
	</tbody>
</table>

      </div>
      <div class="part3-inner"> <img src="images/download.jpg" /><br />
         <div class="battery-management-solution" style="background:#767676; margin-left:0px; width:165px; margin-top:10px;"><strong>CERTIFICATES</strong><br />
            <a href="about-us.php?pageId=5"><img src="images/iso-certificates.jpg" width="160" height="104" border="0" style=" margin-top:4px;" /></a></div>
      </div>
    </div>
    <? include('footer.php');?>
  </div>
</div>
<script type="text/javascript">
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
</script>
</body>
</html>
