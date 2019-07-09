<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
    <title>The Key - A PHP framework that just works</title>
</head>
<body style="text-align: center;">
    <h1>The Key</h1>
    <h3>The PHP Framework That Just Works</h3>
    <a href="https://documentation.comingsoon/documentation">Documentation</a>
    @forelse ($orders as $item)
        <p>{{$item}}</p>
    @empty
        <p>Array is empty</p>
    @endforelse
</body>
</html>
