<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
    <h2>Change Password</h2>

    <div>
        Hi {{$name}}, 
        <br/>
        <br/>
      <!--   Thanks for your interest in Practice Gauge! -->
        Please 
        <a href= {{ URL::to('register_chgNewPwd/password?confirmation_code='. $id) }} >click here</a>
        to change password.  You may also copy the URL below
        to your web browser, if the previous link has issues.
        <br/>
       <br/>
        {{ URL::to('register_chgNewPwd/password?confirmation_code='. $id) }}.
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