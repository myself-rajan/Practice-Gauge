<!DOCTYPE html>
<html lang="en-US">
  <head>
      <meta charset="utf-8">
  </head>
  <body>
    <div class="" id="">
      <p>Hi <span id="spnCName">{{$name}}</span>,</p>
      <p>{{$welcome_sep}}.</p>

       <p>{{$prag}}</p>
      <p >{{$req_practice}}
       </p>
       {{ URL::to('selfRegister/form?confirmation_code='.$id.'&practices='.$company_id) }}.

     <p>Thankyou,<br />
      {{$dName}}<br/>
        {{$cName}}<br/>
      </p>
    </div>
  </body>
</html>