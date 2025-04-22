<?php
session_start();

// Verifica che l'utente sia loggato e sia un genitore
if (!isset($_SESSION['utente']) || $_SESSION['utente']['ruolo'] !== 'Genitore') {
    header("Location: login.php");
    exit;
}

// Verifica che l'ID dello studente sia fornito
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id_studente = $_GET['id'];
$id_genitore = $_SESSION['utente']['id'];

require_once 'operazioni/connessione.php';

// Verifica che lo studente sia effettivamente figlio di questo genitore
$sql = "SELECT COUNT(*) as count FROM RE.Genitore_Studente 
        WHERE id_genitore = ? AND id_studente = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_genitore, $id_studente]);
$result = $stmt->fetch();

if ($result['count'] == 0) {
    // Se lo studente non è associato a questo genitore, reindirizza
    header("Location: dashboard.php");
    exit;
}

// Ottieni informazioni sullo studente
$sql = "SELECT nome, cognome FROM RE.Studente WHERE id_studente = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_studente]);
$studente = $stmt->fetch();

// Funzioni per recuperare i dati dello studente
function getValutazioni($pdo, $id_studente) {
    $sql = "SELECT v.voto, v.data, m.nome as materia, d.nome, d.cognome
            FROM RE.Valutazione v
            JOIN RE.Materia m ON v.id_materia = m.id_materia
            JOIN RE.Docente d ON v.id_docente = d.id_docente
            WHERE v.id_studente = ?
            ORDER BY v.data DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_studente]);
    return $stmt->fetchAll();
}

function getAssenze($pdo, $id_studente) {
    $sql = "SELECT a.data, a.giustificato
            FROM RE.Assenza a
            WHERE a.id_studente = ?
            ORDER BY a.data DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_studente]);
    return $stmt->fetchAll();
}

// Recupera i dati
$valutazioni = getValutazioni($pdo, $id_studente);
$assenze = getAssenze($pdo, $id_studente);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dettaglio Studente - Registro Elettronico</title>
    <link rel="stylesheet" href="componenti/style.css">
    <style>
        /* Stile specifico per la navbar */
        .navbar {
            background-color: #4a90e2;
            color: white;
            padding: 15px 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .navbar-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .navbar-brand {
            font-size: 22px;
            font-weight: 600;
        }

        .navbar-menu {
            display: flex;
            gap: 20px;
        }

        .navbar-item {
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .navbar-item:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
        }

        .user-role {
            font-size: 12px;
            opacity: 0.8;
        }

        .logout-btn {
            background-color: rgba(255, 255, 255, 0.2);
            padding: 8px 15px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }

        .content-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .student-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .back-link {
            color: #4a90e2;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
            margin-bottom: 30px;
        }

        .tab {
            padding: 12px 24px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            font-weight: 500;
        }

        .tab.active {
            border-bottom: 3px solid #4a90e2;
            color: #4a90e2;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 25px;
            margin-bottom: 20px;
        }

        .valutazione-item {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .valutazione-item:last-child {
            border-bottom: none;
        }

        .voto {
            font-size: 18px;
            font-weight: 600;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
        }

        .voto.sufficiente {
            background-color: #4caf50;
        }

        .voto.insufficiente {
            background-color: #f44336;
        }

        .valutazione-info {
            flex-grow: 1;
            padding: 0 20px;
        }

        .valutazione-materia {
            font-weight: 600;
        }

        .valutazione-docente {
            font-size: 14px;
            color: #666;
        }

        .valutazione-data {
            color: #888;
            font-size: 14px;
        }

        .assenza-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .assenza-item:last-child {
            border-bottom: none;
        }

        .assenza-data {
            font-weight: 500;
        }

        .assenza-stato {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
        }

        .giustificata {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .non-giustificata {
            background-color: #ffebee;
            color: #c62828;
        }

        .no-data {
            text-align: center;
            padding: 30px 0;
            color: #888;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-container {
                flex-direction: column;
                gap: 15px;
            }

            .tabs {
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>
<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            Registro Elettronico
        </div>

        <div class="navbar-user">
            <div class="user-info">
                <div class="user-name"><?php echo $_SESSION['utente']['nome'] . ' ' . $_SESSION['utente']['cognome']; ?></div>
                <div class="user-role"><?php echo $_SESSION['utente']['ruolo']; ?></div>
            </div>
            <a href="logout.php" class="logout-btn">Esci</a>
        </div>
    </div>
</nav>

<div class="content-container">
    <div class="student-header">
        <div>
            <a href="dashboard.php" class="back-link">← Torna alla dashboard</a>
            <h1><?php echo $studente['nome'] . ' ' . $studente['cognome']; ?></h1>
        </div>
    </div>

    <div class="tabs">
        <div class="tab active" data-tab="valutazioni">Valutazioni</div>
        <div class="tab" data-tab="assenze">Assenze</div>
        <div class="tab" data-tab="comunicazioni">Comunicazioni</div>
    </div>

    <div id="valutazioni" class="tab-content active">
        <div class="card">
            <h2>Valutazioni recenti</h2>

            <?php if (count($valutazioni) > 0): ?>
                <?php foreach ($valutazioni as $valutazione): ?>
                    <div class="valutazione-item">
                        <div class="voto <?php echo $valutazione['voto'] >= 6 ? 'sufficiente' : 'insufficiente'; ?>">
                            <?php echo $valutazione['voto']; ?>
                        </div>
                        <div class="valutazione-info">
                            <div class="valutazione-materia"><?php echo $valutazione['materia']; ?></div>
                            <div class="valutazione-docente">Prof. <?php echo $valutazione['nome'] . ' ' . $valutazione['cognome']; ?></div>
                        </div>
                        <div class="valutazione-data">
                            <?php echo date('d/m/Y', strtotime($valutazione['data'])); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-data">Nessuna valutazione disponibile</div>
            <?php endif; ?>
        </div>
    </div>

    <div id="assenze" class="tab-content">
        <div class="card">
            <h2>Assenze</h2>

            <?php if (count($assenze) > 0): ?>
                <?php foreach ($assenze as $assenza): ?>
                    <div class="assenza-item">
                        <div class="assenza-data">
                            <?php echo date('d/m/Y', strtotime($assenza['data'])); ?>
                        </div>
                        <div class="assenza-stato <?php echo $assenza['giustificato'] ? 'giustificata' : 'non-giustificata'; ?>">
                            <?php echo $assenza['giustificato'] ? 'Giustificata' : 'Non giustificata'; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-data">Nessuna assenza registrata</div>
            <?php endif; ?>
        </div>
    </div>

    <div id="comunicazioni" class="tab-content">
        <div class="card">
            <h2>Comunicazioni</h2>
            <div class="no-data">Nessuna comunicazione disponibile</div>
        </div>
    </div>
</div>

<script>
    // Script per la gestione delle tab
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.tab');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Rimuovi active da tutte le tab
                tabs.forEach(t => t.classList.remove('active'));

                // Aggiungi active alla tab cliccata
                this.classList.add('active');

                // Mostra il contenuto corrispondente
                const tabId = this.getAttribute('data-tab');
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                document.getElementById(tabId).classList.add('active');
            });
        });
    });
</script>
</body>
</html>