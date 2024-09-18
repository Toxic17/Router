<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<h1>this is string</h1>
<h2>Post number: <?php echo $ID ?></h2>

<form action="/main/form/send" method="post">
    Enter value <input type="text" name="name">
    <button>Send</button>
</form>
</body>
</html>