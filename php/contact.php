<?php
  $fname = $lname = $email = $message = "";

  $grecaptcha = $_POST['g-recaptcha-response'];
  $secret = '6Ldy064UAAAAAFPmLnkR12Uv1B90ZaQM69UZJJMC';
  $remoteIp = $_SERVER['REMOTE_ADDR'];
  $gurl = 'https://www.google.com/recaptcha/api/siteverify';

  $data = array('secret' => $secret, 'response' => $grecaptcha, 'remoteip' => $remoteIp);
  $data = http_build_query($data);

  $ch = curl_init();
  curl_setopt($ch,CURLOPT_URL, $gurl);
  curl_setopt($ch,CURLOPT_POST, true);
  curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
  $result = json_decode(curl_exec($ch),1);
  curl_close($ch);

  if ($result["success"] == 'true') {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fname = strip_tags(trim($_POST["fname"]));
        $lname = strip_tags(trim($_POST["lname"]));
        $lname = str_replace(array("\r","\n"),array(" "," "),$lname);
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $message = trim($_POST["message"]);

        if ( empty($lname) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo "Irgendetwas hat nicht geklappt! Bitte versuchen Sie es erneut!";
            exit;
        }

        $recipient = "service@pp-itservices.de";
        $subject = "Neue Anfrage von $fname $lname";
        $email_content = "Name: $fname $lname\n";
        $email_content .= "Email: $email\n\n";
        $email_content .= "Message:\n$message\n";
        $email_headers = "From: $fname $lname <$email>";

        if (mail($recipient, $subject, $email_content, $email_headers)) {
            http_response_code(200);
            echo "Danke! Ich habe Ihre Nachricht erhalten und werden mich mit Ihnen in Verbindung setzen.";
        } else {
            http_response_code(500);
            echo "Irgendetwas ist schiefgelaufen! Ihre Nachricht wurde nicht gesendet.";
        }

    } else {
        http_response_code(403);
        echo "Irgendetwas hat nicht geklappt! Bitte versuchen Sie es erneut!";
    }
  } else {
    http_response_code(403);
    echo "Irgendetwas hat nicht geklappt! Bitte versuchen Sie es erneut!";
    exit;
  }
 ?>
