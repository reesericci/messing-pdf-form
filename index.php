<?php
// Config
require 'vendor/autoload.php';
use mikehaertl\pdftk\Pdf;

error_reporting(E_ALL | E_WARNING | E_NOTICE);
ini_set('display_errors', TRUE);

$email = new \SendGrid\Mail\Mail();
$email->setFrom(getenv('FROM_EMAIL'), getenv('FROM_NAME'));
$email->setSubject("New office form submission");
$email->addTo(getenv('TO_EMAIL'), getenv('TO_NAME'));
$email->addContent(
    "text/html", "<h2> There has been a new office form submission. Download the PDF below"
);
$email_api_key = getenv('SG_API_KEY');
// Redirect
// FDF stuff here
$data_file = tmpfile();
$data = file_get_contents('php://input');
fwrite($data_file,$data);

$pdf = new Pdf('TMJForm.pdf');
$pdf->fillForm('data.fdf')
    ->flatten()
    //->saveAs('filled.pdf');
    ->execute();
$file =  (string) $pdf->getTmpFile();
$filename =  'officeform_' . microtime();

// Send Email
$file_encoded = base64_encode(file_get_contents($file));
$email->addAttachment(
    $file_encoded,
    "application/pdf",
    $filename,
    "attachment"
);

$sendgrid = new \SendGrid($email_api_key);
try {
    $response = $sendgrid->send($email);
} catch (Exception $e) {
    echo 'Caught exception: '.  $e->getMessage(). "\n";
    echo '<h2 style="font-family: \'Arial\';"> There seems to be an error. Please report the issue (error report) with details of the error (screenshot maybe?) on our GitHub here: <a href="https://github.com/reesericci/messing-office-form/issues">https://github.com/reesericci/messing-office-form/issues</a> I\'ll be as quick as I can to solve the issue!</h1>';
    die();
};
// Wrap Up
fclose($data_file);
if (error_get_last() != null) {
  echo '<h2 style="font-family: \'Arial\';"> There seems to be an error. Please report the issue (error report) with details of the error (screenshot maybe?) on our GitHub here: <a href="https://github.com/reesericci/messing-office-form/issues">https://github.com/reesericci/messing-office-form/issues</a> I\'ll be as quick as I can to solve the issue!</h1>';
  die();
};
?>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <title></title>
  </head>
  <body>
    <div class="container mx-auto">
      <img class="mx-auto" style="display: block; height: 75px;" src="https://images.squarespace-cdn.com/content/5bb52abf3560c364c1a61e63/1592602227522-FO3T4F9A0JX6QC4BQ4VC/messingtmj.png?content-type=image%2Fpng">
      <h1 class="text-center"><b>Form Successful</b></h1>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  </body>
</html>
