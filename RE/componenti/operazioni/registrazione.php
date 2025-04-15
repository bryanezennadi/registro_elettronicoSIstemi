<?php
// Ottieni i valori dal form (POST)
$username = $_POST['username'];
$password = $_POST['password'];
$data_nascita = $_POST['data_nascita'];
$nome = $_POST['nome'];
$cognome = $_POST['cognome'];
$residenza = $_POST['residenza'];
$codice_fiscale = $_POST['codice_fiscale'];

// Parametri di connessione
$servername = "localhost";
$dbname = 'RE';

try {
    // Connessione al database MySQL
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Funzione per generare un numero casuale unico
    function generaNumeroUnico($pdo) {
        do {
            $numeroCasuale = rand(1, PHP_INT_MAX); // Genera un numero casuale
            // Verifica se il numero esiste già nel database
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM Credenziali WHERE id_credenziale = :id_credenziale");
            $stmt->bindParam(':id_credenziale', $numeroCasuale);
            $stmt->execute();
            $count = $stmt->fetchColumn();
        } while ($count > 0); // Se il numero esiste già, rigenera il numero
        return $numeroCasuale;
    }

    // Genera un numero unico
    $id_credenziale = generaNumeroUnico($pdo);

    // Hash della password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Preparazione della query SQL per inserire le credenziali
    $query = "INSERT INTO RE.Credenziali (id_credenziale, username, password) VALUES (:id_credenziale, :username, :password)";
    $stmt = $pdo->prepare($query);

    // Associa i parametri
    $stmt->bindParam(':id_credenziale', $id_credenziale);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashedPassword);

    // Esegui la query
    $stmt->execute();

    // Inserisci i dati dello studente
    $query = "INSERT INTO RE.Studente (nome, cognome, data_nascita, residenza, codice_fiscale, id_credenziale) 
              VALUES (:nome, :cognome, :data_nascita, :residenza, :codicefiscale, :id_credenziale)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cognome', $cognome);
    $stmt->bindParam(':data_nascita', $data_nascita);
    $stmt->bindParam(':residenza', $residenza);
    $stmt->bindParam(':codicefiscale', $codice_fiscale);
    $stmt->bindParam(':id_credenziale', $id_credenziale);
    $stmt->execute();

} catch (PDOException $e) {
    // Gestisci eventuali errori di connessione o esecuzione
    echo "Error: " . $e->getMessage();
    die();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registrazione Effettuata</title>
</head>
<body>
<p>Registrazione effettuata con successo!</p>
</body>
</html>
