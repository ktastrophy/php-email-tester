<?php
$success = false;
$error = false;
function sendEmail() {
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$from = $_POST['from'];
		$to = $_POST['to'];
		$subject = $_POST['subject'];
		$body = $_POST['body'];
		if(filter_var($from, FILTER_VALIDATE_EMAIL ) === false) {
			throw new Exception('Please provide a valid "from" email.');
		}
		if(filter_var($to, FILTER_VALIDATE_EMAIL ) === false) {
			throw new Exception('Please provide a valid "to" email.');
		}
		if(empty($subject)) {
			throw new Exception('Please provide a valid subject');
		}
		if(empty($body)) {
			throw new Exception('Please provide a valid body');
		}
		$headers = 'From: ' . "$from" . "\r\n" .
			'Reply-To: ' . "$from" . "\r\n" .
			"MIME-Version: 1.0\r\n" .
			"Content-Type: text/html; charset=ISO-8859-1\r\n" .
			'X-Mailer: PHP/' . phpversion();
		$sent = mail($to, $subject, $body, $headers);
		if($sent) {
			return true;
		}
	}
	throw new Exception("There was an error sending the email.", 1);
			
}
function getPostValue($key) {
	if(!empty($_POST[$key])) {
		return $_POST[$key];
	}
	return '';
}
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	try {
		$success = sendEmail();
	} catch(Exception $e) {
		$error = $e->getMessage();
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" />
	</head>
	<body>
		<div class="container">
			<div class="row-fluid">
				<div class="span6 offset3 well">
					<h1>PHP Email Tester</h1>
					<p>Send test emails quickly from your server.</p>
					<br />
					<br />
					<?php if($error !== false):?>
						<div class="alert alert-error"><?php echo $error;?></div>
					<?php endif;?>
					<?php if($success !== false):?>
						<div class="alert alert-success">Email sent successfully!</div>
					<?php endif;?>
					<form class="form-horizontal" method="post">
						<div class="control-group">
							<label class="control-label" for="email-from">From:</label>
							<div class="controls">
								<input name="from" type="text" id="email-from" placeholder="From Email" value="<?php echo getPostValue('from');?>">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="email-to">To:</label>
							<div class="controls">
								<input name="to" type="text" id="email-to" placeholder="To Email" value="<?php echo getPostValue('to');?>">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="email-subject">Subject:</label>
							<div class="controls">
								<input name="subject" type="text" id="email-subject" placeholder="Subject" value="<?php echo getPostValue('subject');?>">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="email-body">Body:</label>
							<div class="controls">
								<textarea name="body" id="email-body"><?php echo getPostValue('body');?></textarea>
							</div>
						</div>
						<div class="control-group">
							<div class="controls">
								<button type="submit" class="btn">Send Email</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>



