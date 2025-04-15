<?php
?>
<!doctype html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registrazione</title>
</head>
<body>
<form method="post" action="../componenti/operazioni/registrazione.php">
    <label for="username"> inserisci username</label>
    <br>
    <input type="text" name="username" id="username">
    <br>
    <label for="password">inserisci password</label>
    <br>
    <input type="password" name="password" id="password">
    <br>
    <label for="nome">inserisci nome</label>
    <br>
    <input type="text" name="nome" id="nome">
    <br>
    <label for="cognome">inserisci cognome</label>
    <br>
    <input type="text" name="cognome" id="cognome">
    <br>
    <label for="residenza">inserisci residenza</label>
    <br>
    <input type="text" name="residenza" id="residenza">
    <br>
    <label for="data_nascita">inserisci data_nascita</label>
    <br>
    <input type="date" name="data_nascita" id="data_nascita">
    <br>
    <label for="codice_fiscale">inserisci codice_fiscale</label>
    <br>
    <input type="text" name="codice_fiscale" id="codice_fiscale">
    <br>
    <input type="submit">


</form>
</body>
</html>
