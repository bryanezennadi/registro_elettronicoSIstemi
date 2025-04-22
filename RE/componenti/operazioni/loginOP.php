<?php
session_start();
require_once 'connessione.php'; // connessione PDO al DB RE

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    die("Username o password mancanti.");
}

// 1. Recupera le credenziali
$sql = "SELECT * FROM RE.Credenziali WHERE username = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$username]);
$credenziali = $stmt->fetch();

if (!$credenziali || !password_verify($password, $credenziali['password'])) {
    die("Credenziali non valide.");
}

$id_cred = $credenziali['id_credenziale'];

// Funzione per cercare in una tabella
function trovaUtente(PDO $pdo, string $tabella, int $id_cred) {
    $id_col = match ($tabella) {
        'Docente' => 'id_docente',
        'Personale' => 'id_personale',
        'Genitore' => 'id_genitore',
        'Studente' => 'id_studente',
    };

    $sql = "SELECT $id_col AS id, nome, cognome FROM RE.$tabella WHERE id_credenziale = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_cred]);
    return $stmt->fetch();
}

// Ordine delle tabelle da controllare
$ruoli = ['Docente', 'Personale', 'Genitore', 'Studente'];

foreach ($ruoli as $ruolo) {
    $utente = trovaUtente($pdo, $ruolo, $id_cred);
    if ($utente) {
        $_SESSION['utente'] = [
            'id' => $utente['id'],
            'nome' => $utente['nome'],
            'cognome' => $utente['cognome'],
            'ruolo' => $ruolo
        ];

        // Invece di mostrare un messaggio, reindirizza alla dashboard
        header("Location: ../dashboard.php");
        exit;
    }
}

die("Utente trovato nelle credenziali ma non associato a Docente, Personale, Genitore o Studente.");
?>