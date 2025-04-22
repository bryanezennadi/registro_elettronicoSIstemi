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
    <link rel="stylesheet" href="../componenti/style.css">
    <script>
        function mostraCampoFiglio() {
            const ruolo = document.querySelector('input[name="ruolo"]:checked').value;
            const campoFiglio = document.getElementById("campoFiglio");
            if (ruolo === "genitore") {
                campoFiglio.style.display = "block";
            } else {
                campoFiglio.style.display = "none";
            }
        }
    </script>
</head>
<body>
<div class="container">
    <form method="post" action="../componenti/operazioni/registrazione.php" class="form-registrazione">
        <h1 class="form-titolo">Registrazione</h1>

        <div class="form-gruppo">
            <label for="username" class="form-label">Inserisci username</label>
            <input type="text" name="username" id="username" class="form-input">
        </div>

        <div class="form-gruppo">
            <label for="password" class="form-label">Inserisci password</label>
            <input type="password" name="password" id="password" class="form-input">
        </div>

        <div class="form-gruppo">
            <label for="nome" class="form-label">Inserisci nome</label>
            <input type="text" name="nome" id="nome" class="form-input">
        </div>

        <div class="form-gruppo">
            <label for="cognome" class="form-label">Inserisci cognome</label>
            <input type="text" name="cognome" id="cognome" class="form-input">
        </div>

        <div class="form-gruppo">
            <label for="residenza" class="form-label">Inserisci residenza</label>
            <input type="text" name="residenza" id="residenza" class="form-input">
        </div>

        <div class="form-gruppo">
            <label for="data_nascita" class="form-label">Inserisci data di nascita</label>
            <input type="date" name="data_nascita" id="data_nascita" class="form-input">
        </div>

        <div class="form-gruppo">
            <label for="codice_fiscale" class="form-label">Inserisci codice fiscale</label>
            <input type="text" name="codice_fiscale" id="codice_fiscale" class="form-input">
        </div>

        <div class="form-gruppo">
            <label class="form-label">Registrati come:</label>
            <div class="radio-container">
                <label class="radio-label">
                    <input type="radio" name="ruolo" value="studente" onclick="mostraCampoFiglio()" checked class="radio-input">
                    <span class="radio-text">Studente</span>
                </label>
                <label class="radio-label">
                    <input type="radio" name="ruolo" value="genitore" onclick="mostraCampoFiglio()" class="radio-input">
                    <span class="radio-text">Genitore</span>
                </label>
            </div>
        </div>

        <div id="campoFiglio" class="campo-figlio" style="display: none;">
            <label for="username_figlio" class="form-label">Username del figlio</label>
            <input type="text" name="username_figlio" id="username_figlio" class="form-input">
        </div>

        <div class="form-gruppo">
            <input type="submit" value="Registrati" class="btn-submit">
        </div>

        <div class="form-gruppo link-login">
            <p>Hai già un account? <a href="login.php">Accedi ora</a></p>
        </div>
    </form>
</div>
</body>
</html>