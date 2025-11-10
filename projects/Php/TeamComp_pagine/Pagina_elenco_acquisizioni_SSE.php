<?php
// ---------- CREARE CONNESSIONE ------------------------

$servername ='sql111.infinityfree.com';
$username = 'if0_40236975';
$password ='lfBW09DmpT81r';
$dbname = 'if0_40236975_emrotu_db';

$msqli = new mysqli($servername, $username, $password, $dbname);

// --------- ABILITO ERRORI MYSQLI -----------------------

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   

// ---------- CONTROLLO CONNESSIONE -------------------------

if ($msqli->connect_error) {
    die("Connessione fallita: " . $msqli->connect_error);
}

header('Content-Type: text/event-stream'); // Indica che il server invierà eventi
header('Cache-Control: no-cache');
header('Connection: keep-alive'); // Mantiene connessione aperta

$lastData = null;
$primoGiroDati = true;

while (true) { // Ciclo infinito per inviare aggiornamenti continui
    if (connection_aborted()) {
        break;
    }
    $data = array();

    $selectAcquisizione = $msqli->prepare("SELECT *
                                            FROM ed_acquisizioni_file
                                            WHERE id_utente = 623561
                                            ORDER BY id desc
                                            ");
    $selectAcquisizione->execute();

    $risQueryAcquisizioni = $selectAcquisizione->get_result();
    $data = $risQueryAcquisizioni->fetch_all(MYSQLI_ASSOC);

    if ($primoGiroDati) {

        $lastData = $data; // Inizializza $lastData con i dati attuali
        $primoGiroDati = false; // Disabilita il flag dopo il primo ciclo

    } else if ($data !== $lastData) {

        echo "data: " . json_encode($data) . "\n\n";
        // ob_flush();
        flush(); // Utilizzati per far inviare dati immediati al client
        $lastData = $data;
    }
    
    sleep(5); // Pausa per invio prossimo aggiornamento 5 secondi
}
?>