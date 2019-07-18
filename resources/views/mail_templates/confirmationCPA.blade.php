<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Please verify your email address</h2>

<div>
    Hi {{$name}}, 
    <br/>
    <br/>
    Thanks for your interest in Practice Gauge!
    Please 
    <a href= {{ URL::to('registerCPA/verify?confirmation_code='. $confirmation_code) }} >click here</a>
    to verify your email address.  You may also copy the URL below
    to your web browser, if the previous link has issues.
    <br/>
   <br/>
    {{ URL::to('registerCPA/verify?confirmation_code='. $confirmation_code) }}.
    <br/>
</div>
<div>
    <br/>
    <br/>
    Sincerely,
    <br/>
    The  Practice Gauge!
    <br/>
</div>

</body>
</html>