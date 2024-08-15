<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <style>
        .cont {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .login-form {
            display: flex;
            flex-direction: column;
            padding: 20px;
            border: 1px solid green;
        }
    </style>
    <div class="cont">
        <form action="/login" method="post" class="login-form">
            <label for="email">Email</label>
            <input id="email" type="text" placeholder="Password" name="email">

            <label for="password">Password</label>
            <input id="password" type="password" placeholder="Password" name="password">

            <button type="submit">Log in</button>
        </form>
    </div>
</body>
</html>