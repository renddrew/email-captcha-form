<?php

$to = 'rend.drew@gmail.com'; // Your email address
$subject = 'Email reCaptcha Form'; // Email subject

$recaptcha = false; // enable and disable captcha test
$secret = ''; // add your reCaptcha secret here 


// function used for querying reCaptcha api 
function get_data($url, $post_fields) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

if( isset($_POST['name']) && $_POST['name'] && isset($_POST['email']) && $_POST['email'] && isset($_POST['message']) && $_POST['message']){

	$name = $_POST['name'];
	$name = filter_var($name, FILTER_SANITIZE_STRING);
	if(strlen($name) < 3){
		echo json_encode(array('success'=>'false', 'message'=>'Please add your full name.'));
		exit();
	}

	$email = $_POST['email'];
	if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false){
		//email ok
	}else{
		echo json_encode(array('success'=>'false', 'message'=>'Please enter a valid email address.'));
		exit();
	}

	$message = $_POST['message'];
	$message = filter_var($message, FILTER_SANITIZE_STRING);
	if(strlen($message) < 10){
		echo json_encode(array('success'=>'false', 'message'=>'Please add a more detailed message.'));
		exit();
	}

	if($recaptcha){

		if(isset($_POST['g-recaptcha-response']) && $_POST['g-recaptcha-response']){

			$captcha = $_POST['g-recaptcha-response'];
			$captcha = filter_var($captcha, FILTER_SANITIZE_STRING);

			$rr= get_data('https://www.google.com/recaptcha/api/siteverify', 'secret=' . $secret . '&response=' . $captcha);

			$arr = json_decode($rr, true);

			if(!$arr['success']){
				echo json_encode(array('success'=>'false', 'message'=>'reCaptcha bot test api response failed'));
				exit();
			}

		}else{
			echo json_encode(array('success'=>'false', 'message'=>'Please prove you\'re no stinkin bot!'));
			exit();
		}
	}
	
	$message = 
	 'First Name: ' . $_POST['name']. "\n" .
	 'E-mail: ' . $_POST['email']. "\n" .	 
	 'Message:' . $_POST['message']. "\n" . 
	 
	$headers = 'From: ' . $_POST['email']. "\r\n" . 'Reply-To: ' . $_POST['email'] . "\r\n" . 'X-Mailer: PHP/' . phpversion();
	
	$success = mail($to, $subject, $message, $headers);
	
	if($success){
		echo json_encode(array('success'=>'true'));
	}else{
		echo json_encode(array('success'=>'false', 'message'=>'Email failed to send, please try again'));
	}

}else{
	echo json_encode(array('success'=>'false'));
}




?>


