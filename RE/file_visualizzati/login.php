<?php
?>
<!doctype html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../componenti/style.css">
    <title>Login</title>
</head>
<body>
<div class="container">
    <form method="post" action="../componenti/operazioni/loginOP.php" class="form-registrazione">
        <h1 class="form-titolo">Accedi</h1>

        <div class="form-gruppo">
            <label for="username" class="form-label">Inserisci username</label>
            <input type="text" name="username" id="username" class="form-input">
        </div>

        <div class="form-gruppo">
            <label for="password" class="form-label">Inserisci password</label>
            <input type="password" name="password" id="password" class="form-input">
        </div>

        <div class="form-gruppo">
            <input type="submit" value="Accedi" class="btn-submit">
        </div>

        <div class="form-gruppo link-registrazione">
            <p>Non hai un account? <a href="form.php">Registrati ora</a></p>
        </div>
    </form>
</div>
</body>
</html>