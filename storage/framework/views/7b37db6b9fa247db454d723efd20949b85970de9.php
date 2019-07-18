<html>
<head>
    <title>Test</title>
      <!-- jQuery CDN -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script>
</head>
<body>

<div style="text-align: center; font-family: sans-serif; font-weight: bold;">
    You're connected! Please wait...
</div>

<script type="text/javascript">
	var l      = window.location;
  var WEBURL = l.protocol + "//" + l.host ;
        
	var successurl = '<?php echo e(isset($successCallback) ? $successCallback : ""); ?>';
  /*	if(successurl == "")
		successurl = WEBURL + '/register/integrations?mapping=true';*/
  var newurl = successurl.replace(/&amp;/g, "&");
   window.opener.location.href = newurl;/*'http://local.pg.com/qbo/syncall'*/
   window.close();
</script>

</body>
</html>