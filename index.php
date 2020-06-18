<?php
// Config
require 'vendor/autoload.php';
use mikehaertl\pdftk\Pdf;

phpinfo();

$email = new \SendGrid\Mail\Mail();
$email->setFrom("messingartdoc@gmail.com", "Messing TMJ");
$email->setSubject("New office form submission");
$email->addTo("messingartdoc@gmail.com", "Michael Messing");
$email->addContent(
    "text/html", "<h2> There has been a new office form submission. Download the PDF below"
);
$email_api_key = "";
// FDF stuff here
$fdf = file_get_contents('php://input');


// Send Email
$file_encoded = base64_encode(file_get_contents($file));
$email->addAttachment(
    $file_encoded,
    "application/pdf",
    $file,
    "attachment"
);

$sendgrid = new \SendGrid($email_api_key);
try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: '.  $e->getMessage(). "\n";
}
header('messingtmj.com/form_sucessful');
?>
