<?php
session_start();

// Verifica che l'utente sia loggato
if (!isset($_SESSION['utente'])) {
    header("Location: login.php");
    exit;
}

$utente = $_SESSION['utente'];
$nome = $utente['nome'];
$cognome = $utente['cognome'];
$ruolo = $utente['ruolo'];

// Recupero dei figli se l'utente è un genitore
$figli = [];
if ($ruolo === 'Genitore') {
    require_once 'operazioni/connessione.php';

    $id_genitore = $utente['id'];
    // Query corretta: lo studente ha id_genitore nella sua tabella
    $sql = "SELECT id_studente, nome, cognome 
            FROM RE.Studente 
            WHERE id_genitore = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_genitore]);
    $figli = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Registro Elettronico</title>
    <link rel="stylesheet" href="dashboardSTYLE.css">
</head>
<body>
<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            Registro Elettronico
        </div>

        <div class="navbar-menu">
            <?php if ($ruolo === 'Docente'): ?>
                <!-- Menu specifico per i docenti -->
                <a href="classi.php" class="navbar-item">Le mie classi</a>
                <a href="valutazioni.php" class="navbar-item">Valutazioni</a>
                <a href="registro_presenze.php" class="navbar-item">Registro presenze</a>
                <a href="comunicazioni.php" class="navbar-item">Comunicazioni</a>

            <?php elseif ($ruolo === 'Studente'): ?>
                <!-- Menu specifico per gli studenti -->
                <a href="anno_precedente.php" class="navbar-item">Anno precedente</a>
                <a href="oggi.php" class="navbar-item">Oggi</a>
                <a href="valutazioni.php" class="navbar-item">Valutazioni</a>
                <a href="assenze.php" class="navbar-item">Assenze</a>

            <?php elseif ($ruolo === 'Genitore'): ?>
                <!-- Menu specifico per i genitori con dropdown -->
                <div class="dropdown">
                    <a href="#" class="navbar-item">I tuoi figli</a>
                    <div class="dropdown-content">
                        <?php foreach ($figli as $figlio): ?>
                            <a href="dettaglio_studente.php?id=<?php echo $figlio['id_studente']; ?>" class="figlio-item">
                                <?php echo $figlio['nome'] . ' ' . $figlio['cognome']; ?>
                            </a>
                        <?php endforeach; ?>

                        <?php if (empty($figli)): ?>
                            <span class="figlio-item">Nessun figlio associato</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="navbar-user">
            <div class="user-info">
                <div class="user-name"><?php echo "$nome $cognome"; ?></div>
                <div class="user-role"><?php echo $ruolo; ?></div>
            </div>
            <a href="logout.php" class="logout-btn">Esci</a>
        </div>
    </div>
</nav>

<div class="welcome-container">
    <h1>Benvenuto, <?php echo "$nome $cognome"; ?>!</h1>
    <p>Sei connesso come <?php echo $ruolo; ?></p>

    <?php if ($ruolo === 'Docente'): ?>
        <div class="dashboard-content">
            <h2>Riepilogo</h2>
            <p>Da qui puoi gestire le tue classi, inserire valutazioni e registrare le presenze degli studenti.</p>
            <!-- Contenuto specifico per docente -->
        </div>

    <?php elseif ($ruolo === 'Studente'): ?>
        <div class="dashboard-content">
            <h2>Riepilogo</h2>
            <p>Da qui puoi visualizzare le tue valutazioni, controllare le assenze e vedere cosa è previsto per oggi.</p>
            <!-- Contenuto specifico per studente -->
        </div>

    <?php elseif ($ruolo === 'Genitore'): ?>
        <div class="dashboard-content">
            <h2>I tuoi figli</h2>
            <p>Seleziona un figlio per visualizzare i dettagli del suo andamento scolastico.</p>

            <div class="figli-lista">
                <?php if (!empty($figli)): ?>
                    <?php foreach ($figli as $figlio): ?>
                        <div class="figlio-card">
                            <div class="figlio-info">
                                <h3><?php echo $figlio['nome'] . ' ' . $figlio['cognome']; ?></h3>
                            </div>
                            <a href="dettaglio_studente.php?id=<?php echo $figlio['id_studente']; ?>" class="visualizza-btn">
                                Visualizza
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Non ci sono figli associati al tuo account. Contatta la segreteria per maggiori informazioni.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    // Script per gestire il menu dropdown su dispositivi touch
    document.addEventListener('DOMContentLoaded', function() {
        const dropdowns = document.querySelectorAll('.dropdown');

        dropdowns.forEach(dropdown => {
            dropdown.addEventListener('click', function(e) {
                // Toggle la visibilità solo se si clicca sul link principale
                if (e.target.classList.contains('navbar-item')) {
                    e.preventDefault();
                    const content = this.querySelector('.dropdown-content');
                    content.style.display = content.style.display === 'block' ? 'none' : 'block';
                }
            });

            // Nascondi il dropdown quando si clicca altrove
            document.addEventListener('click', function(e) {
                if (!dropdown.contains(e.target)) {
                    const content = dropdown.querySelector('.dropdown-content');
                    if (content) content.style.display = 'none';
                }
            });
        });
    });
</script>
</body>
</html>