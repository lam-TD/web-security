<?php require 'functions.php'?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cross-site request forgery (CSRF) - Example</title>
</head>
<body>
<form method='post'>
    <input type='text' name='test' value='<?php echo "testing" ?>'/>
    <?php csrf()?>
    <input type='submit'/>
</form>

<form class='nocsrf'>
</form>
</body>
</html>
