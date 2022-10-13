<?php
function SendMail000wh($recipient, $subject, $body)
{
  $postdata = http_build_query(
    [
      'recipient' => $recipient,
      'subject' => $subject,
      'body' => $body
    ]
  );
  $opts = [
    'http' =>
    [
      'method' => 'POST',
      'header' => 'Content-type: application/x-www-form-urlencoded',
      'content' => $postdata
    ]
  ];
  $context = stream_context_create($opts);
  $result = file_get_contents('https://breezier-cares.000webhostapp.com/index.php', false, $context);

  return json_decode($result);
}
