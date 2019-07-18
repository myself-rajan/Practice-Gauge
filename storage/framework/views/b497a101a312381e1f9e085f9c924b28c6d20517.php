<!DOCTYPE html>
<html lang="en-US">
  <head>
      <meta charset="utf-8">
  </head>
  <body>
    <div class="" id="">
      <p>Hi <span id="spnCName"><?php echo e($name); ?></span>,</p>
      <p><?php echo e($welcome_sep); ?>.</p>

       <p><?php echo e($prag); ?></p>
      <p ><?php echo e($req_practice); ?>

       </p>
       <?php echo e(URL::to('selfRegister/form?confirmation_code='.$id.'&practices='.$company_id)); ?>.

     <p>Thankyou,<br />
      <?php echo e($dName); ?><br/>
        <?php echo e($cName); ?><br/>
      </p>
    </div>
  </body>
</html>