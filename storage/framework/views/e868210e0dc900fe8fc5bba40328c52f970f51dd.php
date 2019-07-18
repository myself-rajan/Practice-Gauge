<!DOCTYPE html>
<html lang="en-US">
  <head>
      <meta charset="utf-8">
  </head>
  <body>
    <div class="" id="">
      <p>Hi <span id="spnCName"><?php echo e($name); ?></span>,</p>
      <p>Congratulations!!</p>
      <p><?php echo e($client_name); ?> has been added to your account. You can configure the settings, map the accounts and prepare the dashboards as well. </p>
       <p>Program will enable to send the login access to your new practice once required configuration is done.</p>
        Please <a href="<?php echo e(URL::to('login')); ?>" target="_blank">click here</a> to login to the application to move forward.</p>

     <p>Thanks,<br />
      Practice Gauge Team!
      </p>
    </div>
  </body>
</html>