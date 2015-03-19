<?php
require 'includes/PHPMailerAutoload.php';
if(isset($_POST["data_sent"])){
echo "<div class='container'><div class='row'>";
if ($_FILES["file"]["error"] > 0) {
  echo "Error: " . $_FILES["file"]["error"] . "<br>";
} else {
  echo "Upload: " . $_FILES["file"]["name"] . "<br>";
  echo "Type: " . $_FILES["file"]["type"] . "<br>";
  echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
}
//Create a new PHPMailer instance
$mail = new PHPMailer();
//Set who the message is to be sent from
$mail->setFrom($_POST["fromEmail"], $_POST["fromName"]);
//Set an alternative reply-to address
$mail->addReplyTo($_POST["fromEmail"], $_POST["fromName"]);
//Set who the message is to be sent to
$toArray = explode(";",$_POST["toEmail"]);
foreach ($toArray as $value) {
    $mail->addAddress($value, $_POST["toName"]);
}

if(isset($_POST["self"]))
$mail->addAddress($_POST["fromEmail"],  $_POST["toName"]);
//Set the subject line
$mail->Subject = $_POST["subject"];
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$textMail = $_POST["content"];
if($textMail !=  ''){
	$mail->msgHTML($_POST["content"]."<br/><br/><br/>".file_get_contents($_FILES["file"]["tmp_name"]), dirname(__FILE__));
}else{
	$mail->msgHTML(file_get_contents($_FILES["file"]["tmp_name"]), dirname(__FILE__));
}
//$mail->msgHTML($_POST["content"]);

//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
if(isset($_FILES["attachment"]["tmp_name"])){
	move_uploaded_file($_FILES["attachment"]["tmp_name"],"/tmp/".$_FILES["attachment"]["name"]);
	$mail->addAttachment("/tmp/".$_FILES["attachment"]["name"]);
}

//send the message, check for errors
if (!$mail->send()) {
	echo "<br/><br/><div class='alert alert-danger' role='alert'>Error Occured : ".$mail->ErrorInfo."</div>";
} else {
    echo "<br/><br/><div class='alert alert-success' role='alert'>Message Successfully Sent</div>";
}
echo "</div></div>";
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Testing Emailer</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- Bootstrap -->
<link href="includes/bootstrap.min.css" rel="stylesheet">
<!--[if lt IE 9]>
      <script src="includes/html5shiv.js"></script>
      <script src="includes/respond.min.js"></script>
    <![endif]-->
<script type="text/javascript" src="includes/jquery.min.js"></script>
<script type="text/javascript" src="includes/bootstrap.min.js"></script>
<style>
	body{margin-top:20px;}
	textarea{width:80%;height:100px;padding:5px;margin-top:5px;}
	form{margin-top:30px;}
</style>
</head>
<body>
	<div class="container well">
    	
        <h1 class="text-center text-muted">Test your HTML Emailer</h1>
    	<h4 class="text-center text-warning">HTML-Dev Effort</h4>
        
    	<form role="form-horizontal" enctype="multipart/form-data" action="index.php" method="post">
         <div class="form-group col-xs-12 col-sm-6">
            <div class="input-group">
              <div class="input-group-addon">From:</div>
              <input class="form-control" type="email" placeholder="Enter email coming from" name="fromEmail" required="required" />
            </div>
          </div>
		  <div class="form-group col-xs-12 col-sm-6">
            <div class="input-group">
              <div class="input-group-addon">Sender Name:</div>
              <input class="form-control" type="text" placeholder="Full Name" name="fromName" />
            </div>
          </div>
          <div class="form-group col-xs-12 col-sm-6">
            <div class="input-group">
              <div class="input-group-addon">To: </div>
              <input class="form-control" type="text" placeholder="Enter email whom to send" name="toEmail" required="required" />
            </div>
          </div>
		  <div class="form-group col-xs-12 col-sm-6">
            <div class="input-group">
              <div class="input-group-addon">Receiver Name: </div>
              <input class="form-control" type="text" placeholder="Full Name" name="toName" />
            </div>
          </div>
           <div class="form-group col-xs-12">
            <div class="input-group">
              <div class="input-group-addon">Subject: </div>
              <input class="form-control" type="text" placeholder="subject" name="subject"/>
            </div>
          </div>
          <div class="form-group col-xs-12">
            <div class="input-group">
              <div class="input-group-addon">HTML Content: </div>
              <textarea name="content"></textarea>
            </div>
          </div>
          <div class="form-group col-xs-12">
            <label for="exampleInputFile" class="help-block">Include file as a HTML mailer:</label>
            <input type="file" id="exampleInputFile" name="file">
          </div>
          <div class="form-group col-xs-12">
            <label for="exampleInputFile" class="help-block">Include file as a attachment to the mailer:</label>
            <input type="file" id="attachment" name="attachment">
          </div>
          <div class="checkbox col-xs-12">
            <label>
              <input type="checkbox" name="self"> Send me copy.
            </label>
          </div>
          <input type="hidden" name="data_sent" value="true"/>
          <span class="col-xs-12"><button type="submit" class="btn btn-info">Submit</button></span>
        </form>
    </div>

</body>
</html>
