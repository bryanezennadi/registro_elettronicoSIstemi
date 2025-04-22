<?php
function inserisciDocenti($conn) {
    // Disabilita temporaneamente i vincoli di chiave esterna
    if (!$conn->query("SET FOREIGN_KEY_CHECKS = 0")) {
        die("Errore disabilitando i vincoli: " . $conn->error);
    }

    // Svuota le tabelle Docente e Credenziali
    if (!$conn->query("DELETE FROM RE.Docente")) {
        die("Errore cancellando i docenti: " . $conn->error);
    }
    if (!$conn->query("DELETE FROM RE.Credenziali")) {
        die("Errore cancellando le credenziali: " . $conn->error);
    }

    // Abilita i vincoli di chiave esterna
    if (!$conn->query("SET FOREIGN_KEY_CHECKS = 1")) {
        die("Errore abilitando i vincoli: " . $conn->error);
    }

    // Dati docenti da inserire
    $docenti = [
        [
            'nome' => 'Marco',
            'cognome' => 'Rossi',
            'data_nascita' => '1980-04-12',
            'residenza' => 'Via Roma 10, Milano',
            'codice_fiscale' => 'RSSMRC80D12F205X',
            'username' => 'm.rossi',
            'password' => 'docente123',
            'id_credenziale' => 1
        ],
        [
            'nome' => 'Laura',
            'cognome' => 'Bianchi',
            'data_nascita' => '1975-09-03',
            'residenza' => 'Via Dante 22, Firenze',
            'codice_fiscale' => 'BNCLRA75P43D612Z',
            'username' => 'l.bianchi',
            'password' => 'insegnante456',
            'id_credenziale' => 2
        ],
        [
            'nome' => 'Giovanni',
            'cognome' => 'Verdi',
            'data_nascita' => '1988-01-27',
            'residenza' => 'Piazza Garibaldi 5, Napoli',
            'codice_fiscale' => 'VRDGVN88A27F839A',
            'username' => 'g.verdi',
            'password' => 'scuola789',
            'id_credenziale' => 3
        ],
        [
            'nome' => 'Anna',
            'cognome' => 'Neri',
            'data_nascita' => '1990-11-15',
            'residenza' => 'Corso Vittorio 3, Torino',
            'codice_fiscale' => 'NRAANN90S55L219T',
            'username' => 'a.neri',
            'password' => 'classe101',
            'id_credenziale' => 4
        ]
    ];

    foreach ($docenti as $docente) {
        // Hash password
        $hashedPassword = password_hash($docente['password'], PASSWORD_DEFAULT);

        // Inserimento credenziale
        $stmtCred = $conn->prepare("INSERT INTO RE.Credenziali (username, password, id_credenziale) VALUES (?, ?, ?)");
        if (!$stmtCred) {
            die("Errore preparando la query per le credenziali: " . $conn->error);
        }
        $stmtCred->bind_param("ssi", $docente['username'], $hashedPassword, $docente['id_credenziale']);
        if (!$stmtCred->execute()) {
            die("Errore durante l'inserimento delle credenziali: " . $stmtCred->error);
        }
        $stmtCred->close();

        // Inserimento docente con riferimento alla credenziale appena creata
        $stmtDoc = $conn->prepare("INSERT INTO RE.Docente (nome, cognome, data_nascita, residenza, codice_fiscale, id_credenziale) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmtDoc) {
            die("Errore preparando la query per i docenti: " . $conn->error);
        }
        $stmtDoc->bind_param("sssssi", $docente['nome'], $docente['cognome'], $docente['data_nascita'], $docente['residenza'], $docente['codice_fiscale'], $docente['id_credenziale']);
        if (!$stmtDoc->execute()) {
            die("Errore durante l'inserimento dei docenti: " . $stmtDoc->error);
        }
        $stmtDoc->close();
    }

    echo "✅ Docenti e credenziali reinseriti correttamente!";
}


$conn = new mysqli("localhost", "root", "", "RE");
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

inserisciDocenti($conn);
