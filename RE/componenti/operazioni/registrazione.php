<?php
// Ottieni i valori dal form (POST)
$username = $_POST['username'];
$password = $_POST['password'];
$data_nascita = $_POST['data_nascita'];
$nome = $_POST['nome'];
$cognome = $_POST['cognome'];
$residenza = $_POST['residenza'];
$codice_fiscale = $_POST['codice_fiscale'];
$ruolo = $_POST['ruolo'];
$username_figlio = $_POST['username_figlio'] ?? null;

// Parametri di connessione
$servername = "localhost";
$dbname = 'RE';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    function generaNumeroUnico($pdo, $tabella, $colonna) {
        do {
            $numeroCasuale = random_int(1, 2147483646);
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM $tabella WHERE $colonna = ?");
            $stmt->execute([$numeroCasuale]);
            $count = $stmt->fetchColumn();
        } while ($count > 0);
        return $numeroCasuale;
    }

    $id_credenziale = generaNumeroUnico($pdo, "Credenziali", "id_credenziale");
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Inserisci nella tabella Credenziali
    $query = "INSERT INTO Credenziali (id_credenziale, username, password) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id_credenziale, $username, $hashedPassword]);

    if ($ruolo === 'studente') {
        // Inserimento come studente
        $query = "INSERT INTO Studente (nome, cognome, data_nascita, residenza, codice_fiscale, id_credenziale)
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$nome, $cognome, $data_nascita, $residenza, $codice_fiscale, $id_credenziale]);

    } elseif ($ruolo === 'genitore') {
        // Inserimento come genitore
        $id_genitore = generaNumeroUnico($pdo, "Genitore", "id_genitore");

        $query = "INSERT INTO Genitore (id_genitore, nome, cognome, data_nascita, residenza, codice_fiscale, id_credenziale)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_genitore, $nome, $cognome, $data_nascita, $residenza, $codice_fiscale, $id_credenziale]);

        // Collega il genitore allo studente tramite username
        if (!empty($username_figlio)) {
            $stmt = $pdo->prepare("
                UPDATE Studente
                SET id_genitore = ?
                WHERE id_credenziale = (
                    SELECT id_credenziale FROM Credenziali WHERE username = ?
                )");
            $stmt->execute([$id_genitore, $username_figlio]);
        }
    }

} catch (PDOException $e) {
    echo "Errore: " . $e->getMessage();
    die();
}
?>
<!doctype html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Registrazione Effettuata</title>
</head>
<body>
<p>Registrazione effettuata con successo!</p>
<a href="../../file_visualizzati/login.php">Vai al login</a>
</body>
</html>
