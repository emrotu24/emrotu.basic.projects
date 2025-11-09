<?php
// ---------- CREARE CONNESSIONE ------------------------

$servername ='sql111.infinityfree.com';
$username = 'if0_40236975';
$password ='lfBW09DmpT81r';
$dbname = 'if0_40236975_emrotu_db';

$mysqli = new mysqli($servername, $username, $password, $dbname);

// --------- ABILITO ERRORI MYSQLI -----------------------

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

error_reporting(E_ALL);

// ---------- CONTROLLO CONNESSIONE -------------------------

if ($mysqli->connect_error) {
    die("Connessione fallita: " . $mysqli->connect_error);
}

// $id_acquisizione = $_POST['id'];
// $id_utente = $_POST['user'];

function customErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}


function eseguiAcquisizioneContatti($arrayQuery, $mysqli) {   
    
    $mysqli->begin_transaction();
    
    $mysqli->autocommit(FALSE);
    
    set_error_handler("customErrorHandler");

    try {
        foreach ($arrayQuery as $query) {
            $stmt = $mysqli->prepare($query['query']);

            if (!$stmt) {
                throw new Exception("Prepare failed: " . $mysqli->error);
            }

            if (!empty($query['params'])) {
                $types = '';
                foreach ($query['params'] as $param) {
                    if (is_int($param)) {
                        $types .= 'i';
                    } else if (is_string($param)) {
                        $types .= 's';
                    } else if (is_null($param)) {
                        $types .= 's';
                        $param == null;
                    }
                }
            }

            $stmt->bind_param($types, ...$query['params']);
            $stmt->execute();

            switch ($query['type']) {
                case 'insert':
                    $last_id = $mysqli->insert_id;
                    if (isset($query['update_params'])) {
                        foreach ($query['update_params'] as $key => $value) {
                            $queries[$key]['params'][$value] = $last_id;
                        }
                    }
                    break;
                case 'select':
                    $result = $stmt->get_result();
                    $data = $result->fetch_assoc();
                    if (isset($query['update_params'])) {
                        foreach ($query['update_params'] as $key => $value) {
                            $queries[$key]['params'][$value] = $data[$value];
                        }
                    }
                    break;
                case 'update':

                    break;
            }

            $stmt->close();
        }

    // try {
        $stmt = $mysqli->prepare($query);
        // foreach ($queryArray as $query) {
            if (isset($query['query_update_utente']) && $query['query_update_utente']) {
                $query_update_utente = $mysqli->prepare($query['query_update_utente']);
                $query_update_utente->bind_param('si', $query['params'][0], $query['params'][1]);
                $query_update_utente->execute();
                $query_update_utente->close();
            }
            if (isset($query['query_select_utente'])  && $query['query_select_utente']) {
                $query_select_utente = $mysqli->prepare($query['query_select_utente']);
                $query_select_utente->bind_param('i', $query['params'][0]);
                $query_select_utente->execute();
                $result = $query_select_utente->get_result();
                $dettaglio_utente = $result->fetch_assoc();
                $query_select_utente->close();
            }
            if (isset($query['query_update_anagrafica']) && $query['query_update_anagrafica']) {
                $query['params'][0] = $dettaglio_utente['anagrafica'];
                $query_update_anagrafica = $mysqli->prepare($query['query_select_utente']);
                $query_update_anagrafica->bind_param('ii', $query['params'][0], $query['params'][1]);
                $query_update_anagrafica->execute();
                $query_update_anagrafica->close();
            }
            if (isset($query['query_select_anagrafica']) && $query['query_select_anagrafica']) {
                $query_select_anagrafica = $mysqli->prepare($query['query_select_anagrafica']);
                $query_select_anagrafica->bind_param('i', $query['params'][0]);
                $query_select_anagrafica->execute();
                $result = $query_select_anagrafica->get_result();
                $dettaglio_anagrafica = $result->fetch_assoc();
                $query['params'][0] = $dettaglio_anagrafica['id_anagrafica'];
                $query_select_anagrafica->close();
            }
            if (isset($query['query_insert_anagrafica']) && $query['query_insert_anagrafica']) {
                $query_insert_anagrafica = $mysqli->prepare($query['query_insert_anagrafica']);
                $query_insert_anagrafica->execute();
                if ($query['params'][0] == null) {
                    $last_id = $mysqli->insert_id;
                    $query['params'][0] = $last_id;
                }
                $query_insert_anagrafica->close();
            }
            if (isset($query['query_update_utente_anagrafica']) && $query['query_update_utente_anagrafica']) {
                $query_update_utente_anagrafica = $mysqli->prepare($query['query_update_utente_anagrafica']);
                $query_update_utente_anagrafica->bind_param('ii', $query['params'][0], $query['params'][1]);
                $query_update_utente_anagrafica->execute();
                $query_update_utente_anagrafica->close();
            }
            if (isset($query['query_insert_debitore']) && $query['query_insert_debitore']) {
                $query_insert_debitore = $mysqli->prepare($query['query_insert_debitore']);
                $query_insert_debitore->bind_param('i', $query['params'][0]);
                $query_insert_debitore->execute();
                $query_insert_debitore->close();
            }
            if (isset($query['query_update_recapito']) && $query['query_update_recapito']) {
                $query_update_recapito = $mysqli->prepare($query['query_update_recapito']);
                $query_update_recapito->bind_param('i', $query['params'][0]);
                $query_update_recapito->execute();
                $query_update_recapito->close();
            }
            if (isset($query['query_insert_recapito']) && $query['query_insert_recapito']) {
                $query_insert_recapito = $mysqli->prepare($query['query_insert_recapito']);
                $query_insert_recapito->bind_param('i', $query['params'][0]);
                $query_insert_recapito->execute();
                $query_insert_recapito->close();
            }
            if (isset($query['query_insert_ignore_recapito']) && $query['query_insert_ignore_recapito']) {
                $query_insert_ignore_recapito = $mysqli->prepare($query['query_insert_ignore_recapito']);
                $query_insert_ignore_recapito->bind_param('i', $query['params'][0]);
                $query_insert_ignore_recapito->execute();
                $query_insert_ignore_recapito->close();
            }
            if ($mysqli->affected_rows > 0 && ($query_insert_recapito || $query_insert_ignore_recapito)) {
                if ($query['params'][0] == null) {
                    $last_id = $mysqli->insert_id;
                    $query['params'][0] = $last_id;
                }
                if (isset($query['query_select_recapito']) && $query['query_select_recapito']) {
                    $query_select_recapito = $mysqli->prepare($query['query_select_recapito']);
                    $query_select_recapito->bind_param('i', $query['params'][0]);
                    $query_select_recapito->execute();
                    $result = $query_select_recapito->get_result();
                    $dettaglio_recapito = $result->fetch_assoc();
                    $query_select_recapito->close();
                }

                echo '<strong>ID RECAPITO: ' . $dettaglio_recapito['id_recapito'] . ' - ' . $dettaglio_recapito['indirizzo'] . ' - ' . $dettaglio_recapito['cap'] . '</strong><br>';
            } else {
                echo '<strong>RECAPITO GI&Agrave; ESISTENTE</strong><br>';
            }
            if (isset($query['query_select_max_recapito']) && $query['query_select_max_recapito']) {
                $query_select_max_recapito = $mysqli->prepare($query['query_select_max_recapito']);
                $query_select_max_recapito->bind_param('i', $query['params'][0]);
                $query_select_max_recapito->execute();
                $result = $query_select_max_recapito->get_result();
                $max_id_recapito = $result->fetch_assoc();
                $query_select_max_recapito->close();
            }
            if (isset($query['query_update_max_recapito']) && $query['query_update_max_recapito']) {
                if ($query['params'][0] == null) {
                    $query['params'][0] = $max_id_recapito;                    
                }
                $query_update_max_recapito = $mysqli->prepare($query['query_update_max_recapito']);
                $query_update_max_recapito->bind_param('i', $query['params'][0]);
                $query_update_max_recapito->execute();
                $query_update_max_recapito->close();
            }
            if (isset($query['query_update_recapito_telefonico']) && $query['query_update_recapito_telefonico']) {
                $query_update_recapito_telefonico = $mysqli->prepare($query['query_update_recapito_telefonico']);
                $query_update_recapito_telefonico->bind_param('i', $query['params'][0]);
                $query_update_recapito_telefonico->execute();
                $query_update_recapito_telefonico->close();
            }
            if (isset($query['query_insert_recapito_telefonico']) && $query['query_insert_recapito_telefonico']) {
                $query_insert_recapito_telefonico = $mysqli->prepare($query['query_insert_recapito_telefonico']);
                $query_insert_recapito_telefonico->bind_param('i', $query['params'][0]);
                $query_insert_recapito_telefonico->execute();
                if ($query['params'][0] == null) {
                    $last_id = $mysqli->insert_id;
                    $query['params'][0] = $last_id;
                }
                $query_insert_recapito_telefonico->close();
            }
            if (isset($query['query_insert_ignore_recapito_telefonico']) && $query['query_insert_ignore_recapito_telefonico']) {
                $query_insert_ignore_recapito_telefonico = $mysqli->prepare($query['query_insert_ignore_recapito_telefonico']);
                $query_insert_ignore_recapito_telefonico->bind_param('i', $query['params'][0]);
                $query_insert_ignore_recapito_telefonico->execute();
                $query_insert_ignore_recapito_telefonico->close();
            }
            if (isset($query['query_select_recapito_telefonico'])) { // ---------- Va in errore perchè non entra in questo if ------
                // if ($query['params'][0] == null) {
                //     $last_id = $mysqli->insert_id;
                //     $query['params'][0] = $last_id;
                // }
                echo 'Entrato nel blocco query_select_recapito_telefonico' . PHP_EOL;
                var_dump($query['query_select_recapito_telefonico']);
                var_dump($query['params']);
                $query_select_recapito_telefonico = $mysqli->prepare($query['query_select_recapito_telefonico']);
                $query_select_recapito_telefonico->bind_param('i', $query['params'][0]);
                $query_select_recapito_telefonico->execute();
                $result = $query_select_recapito_telefonico->get_result();
                $dettaglio_recapito_telefonico = $result->fetch_assoc();
                // $query_select_recapito_telefonico->close();
                var_dump($dettaglio_recapito_telefonico);
            } else {
                echo 'Non è entrato nel blocco query_select_recapito_telefonico' . PHP_EOL;
            }
            if (substr($dettaglio_recapito_telefonico['indirizzo'], 0, 3) == '+39') {                                    
                $query['params'][2] = substr($dettaglio_recapito_telefonico['indirizzo'], 3);
            } else {
                $query['params'][2] = $dettaglio_recapito_telefonico['indirizzo'] . "'";
            }

            if (isset($query['query_select_verifica_duplicati']) && $query['query_select_verifica_duplicati']) {
                $query_select_verifica_duplicati = $mysqli->prepare($query['query_select_verifica_duplicati']);
                $query_select_verifica_duplicati->bind_param('iis', $query['params'][0], $query['params'][1], $query['params'][2]);
                $query_select_verifica_duplicati->execute();
                $result_verifica_duplicati = $query_select_verifica_duplicati->get_result();
            }
            if($result_verifica_duplicati->num_rows > 0) {
                $query_select_verifica_duplicati->close();
                if (isset($query['query_delete_recapito_telefonico_duplicato']) && $query['query_delete_recapito_telefonico_duplicato']) {
                    $query_delete_recapito_telefonico_duplicato = $mysqli->prepare($query['query_delete_recapito_telefonico_duplicato']);
                    $query_delete_recapito_telefonico_duplicato->bind_param('i', $query['params'][0]);
                    $query_delete_recapito_telefonico_duplicato->execute();
                    $query_delete_recapito_telefonico_duplicato->close();
                }
                echo '<strong>NUOVO RECAPITO NON ACQUISITO - RECAPITO GI&Agrave; ESISTENTE.</strong><br>';
            } else {
                echo '<strong>ID RECAPITO: ' . $dettaglio_recapito_telefonico['id_recapito_telefono'] . ' - ' . $dettaglio_recapito_telefonico['indirizzo'] . '</strong><br>';
            }
            if (isset($query['query_select_max_recapito_telefonico']) && $query['query_select_max_recapito_telefonico']) {
                $query_select_max_recapito_telefonico = $mysqli->prepare($query['query_select_max_recapito_telefonico']);
                $query_select_max_recapito_telefonico->bind_param('i', $query['params'][0]);
                $query_select_max_recapito_telefonico->execute();
                $result = $query_select_max_recapito_telefonico->get_result();
                $max_id_recapito_telefonico = $result->fetch_assoc();
                $query_select_max_recapito_telefonico->close();
            }
            if (isset($query['query_update_max_recapito_telefonico']) && $query['query_update_max_recapito_telefonico']) {
                if ($query['params'][0] == null) {
                    $query['params'][0] = $max_id_recapito_telefonico;                    
                }
                $query_update_max_recapito_telefonico = $mysqli->prepare($query['query_update_max_recapito_telefonico']);
                $query_update_max_recapito_telefonico->bind_param('i', $query['params'][0]);
                $query_update_max_recapito_telefonico->execute();
                $query_update_max_recapito_telefonico->close();
            }
        // }     
        $mysqli->commit();
    } catch (Exception $e) {
        $mysqli->rollback();
        echo $query['message'] . ': ' . $e->getMessage() . PHP_EOL . '<br>';
    } finally {
        restore_error_handler();
    }
    $mysqli->autocommit(TRUE);
}

function deleteTabProvvisorie($id_acquisizione) {

    global $mysqli;

    $tables = [
        'ed_lotti_mandante', 'ed_lotto_studio', 'ed_creditori', 'ed_utente', 
        'ed_anagrafica', 'ed_recapito', 'ed_recapito_telefonico', 'ed_pratiche', 
        'ed_acquisizione_dati', 'ed_collegati', 'e_collegati_pratica', 
        'ed_anagrafica_collegati_mandante', 'e_pratiche_insoluto', 'e_calcolo_compenso'
    ];

    $mysqli->autocommit(FALSE);
    try{
        foreach ($tables as $table) {
            $delete_record_provvisori = $mysqli->prepare("DELETE FROM $table WHERE id_acquisizione = ?");
            $delete_record_provvisori->bind_param("i", $id_acquisizione);
            $delete_record_provvisori->execute();
        }
        echo "Eliminazione record tabelle provvisorie effettuato!! ID ACQUISIZIONE: " . $id_acquisizione . "\n\n";
    }catch(mysqli_sql_exception $e){
        $mysqli->rollback();
        echo "ERRORE ELIMINAZIONE TABELLE PROVVISORIE " . $e->getMessage() . "\n\n" ;
    }
    $mysqli->autocommit(TRUE);
    $delete_record_provvisori->close();
}

if(isset($id_acquisizione) && isset($id_utente) && $id_utente != '') {
    // $select_stati_in_coda = $mysqli->prepare("SELECT id, id_utente FROM ed_acquisizioni_file WHERE stato = 'In coda' AND id = '$id_acquisizione' AND id_utente = '$id_utente' ORDER BY priorita, id");    
    // echo "Parametri passati regolarmente. \n\n";
    $select_stati_in_coda = $mysqli->prepare("SELECT id, id_utente, tipologia FROM ed_acquisizioni_file WHERE stato = 'In coda' AND id = ? AND id_utente = ? ORDER BY priorita, id");
    $select_stati_in_coda->bind_param("ii", $id_acquisizione, $id_utente);
    echo "Parametri passati regolarmente. \n\n";
} else {
    $select_stati_in_coda = $mysqli->prepare("SELECT id, id_utente, tipologia FROM ed_acquisizioni_file WHERE stato = 'In coda' ORDER BY priorita, id"); 
    echo "Parametri mancanti. Seleziono tutte le righe con stato 'In coda' e ricavo id & id_utente \n\n";
} 
$select_stati_in_coda->execute();
$result_stati_in_coda = $select_stati_in_coda->get_result();
// $select_stati_in_coda->close();

$query_check_procedura = $mysqli->prepare("SELECT flag_controllo FROM e_controllo_procedura_acquisizioni");
$query_check_procedura->execute();
$result_check_procedura = $query_check_procedura->get_result();
$flag_procedura = $result_check_procedura->fetch_assoc();
$query_check_procedura->close();

if ($flag_procedura['flag_controllo'] == 1) {

    echo "Flag_controllo a 1 inizializzazione...\n\n<br><br>";

    if ($result_stati_in_coda->num_rows > 0) {

        while ($row = $result_stati_in_coda->fetch_assoc()) {
            
            $id_acquisizione = $row['id'];
            $id_utente = $row['id_utente'];
            $tipologia_acquisizione = $row['tipologia'];            
                
            $query_check_in_lavorazione = $mysqli->prepare("SELECT id FROM ed_acquisizioni_file WHERE stato = 'In lavorazione'");
            $query_check_in_lavorazione->execute();
            $result_check_in_lavorazione = $query_check_in_lavorazione->get_result();
            $loading_rows = $result_check_in_lavorazione->fetch_assoc();
            $query_check_in_lavorazione->close();

            
            if (count($loading_rows) == 0) {

                echo "Non sono presenti acquisizione in lavorazione, inizio procedura per l'acquisizione: " . $id_acquisizione ."\n\n<br><br>";

                $mysqli->autocommit(FALSE);            
                try{
                    $mysqli->autocommit(FALSE);
                    try{
                        $insert_stati_acquisizioni = $mysqli->prepare("INSERT INTO e_stati_acquisizioni
                                                                            SET id_acquisizione = ?,
                                                                            id_utente = ?,
                                                                            stato = 'In lavorazione',
                                                                            tipologia = '$tipologia_acquisizione',
                                                                            data_cambio_stato = CURRENT_TIMESTAMP                                                 
                                                                            ");
                        $insert_stati_acquisizioni->bind_param("ii", $id_acquisizione, $id_utente);
                        $insert_stati_acquisizioni->execute();
                    }catch(mysqli_sql_exception $e){
                        $mysqli->rollback();
                        echo "ERRORE INSERT IN LAVORAZIONE TABELLA DI LOG, ID ACQUISIZIONE: " . $id_acquisizione .  " ERRORE: " . $e->getMessage() . "\n\n" ;
                    }
                    $mysqli->autocommit(TRUE);
                    $insert_stati_acquisizioni->close();

                    $mysqli->autocommit(FALSE);
                    try{
                        $update_ed_acquisizioni_file = $mysqli->prepare("UPDATE ed_acquisizioni_file
                                                                SET stato = 'In lavorazione',
                                                                data_inizio = CURRENT_TIMESTAMP
                                                                WHERE id = ?");
                        $update_ed_acquisizioni_file->bind_param("i", $id_acquisizione);
                        $update_ed_acquisizioni_file->execute();

                        echo "Acquisizione: " . $id_acquisizione . " modificata 'in lavorazione' \n\n<br><br>";

                    }catch(mysqli_sql_exception $e){
                        $mysqli->rollback();
                        echo "ERRORE UPDATE IN LAVORAZIONE TABELLA ELENCO, ID ACQUISIZIONE: " . $id_acquisizione .  " ERRORE: " . $e->getMessage() . "\n\n" ;
                    }
                    $mysqli->autocommit(TRUE);
                    $update_ed_acquisizioni_file->close();
                    
                    $start_time=microtime(TRUE);

                    if ($tipologia_acquisizione == 'DATI') {
                        $query_copy_data = $mysqli->prepare("CALL e_copy_data(?)");
                        $query_copy_data->bind_param('i', $id_acquisizione);
                        $query_copy_data->execute();
                        $query_copy_data->close();

                        deleteTabProvvisorie($id_acquisizione);

                    } 
                    if ($tipologia_acquisizione == 'CONTATTI') {
                        // $query_copy_data = $mysqli->prepare("CALL e_copy_data_indirizzi_recapiti(?)");
                        // $query_copy_data->bind_param('i', $id_acquisizione);
                        // $query_copy_data->execute();
                        // $query_copy_data->close();

                        // Carica l'array serializzato dal file
                        $serializedArray = file_get_contents('test_emanuele_array_queries_contatti.php');
                        echo 'Contenuto del file:' . PHP_EOL . '<br><br>';
                        var_dump($serializedArray);

                        // Deserializza l'array
                        $query = unserialize($serializedArray);
                        echo 'Array deserializzato:' . PHP_EOL . '<br><br>';
                        var_dump($query);

                        eseguiAcquisizioneContatti($query, $mysqli);


                    }
                    if ($tipologia_acquisizione == 'ANAG.COLLEGATI') {

                        // Carica l'array serializzato dal file
                        $serializedArray = file_get_contents('test_emanuele_queries_anag_collegati.php');

                        // Deserializza l'array
                        $queryArray = unserialize($serializedArray);

                        // Chiama la funzione di esecuzione
                        eseguiAcquisizioneContatti($queryArray, $mysqli);

                        print_r($queryArray);
                    }

                    echo "CARICAMENTO " . $tipologia_acquisizione . " IN TAB ORIGINALI EFFETTUATO!! ID ACQUISIZIONE: " . $id_acquisizione . ' EFFETTUATA DA UTENTE: ' . $id_utente ."\n\n<br><br>";

                    $end_time=microtime(true);
                    $execution_time = $end_time - $start_time;
                    echo '<strong>Tempo esecuzione SP: ' .$execution_time.'</strong><br><br>';

                    $mysqli->autocommit(FALSE);            
                    try{
                        $update_ed_acquisizioni_file = $mysqli->prepare("UPDATE ed_acquisizioni_file
                                                                        SET stato = 'Done',
                                                                        data_fine = CURRENT_TIMESTAMP
                                                                        WHERE id = ?");
                        $update_ed_acquisizioni_file->bind_param("i", $id_acquisizione);
                        $update_ed_acquisizioni_file->execute();
                    }catch(mysqli_sql_exception $e){
                        $mysqli->rollback();
                        echo "ERRORE UPDATE DONE TABELLA ELENCO, ID ACQUISIZIONE: " . $id_acquisizione .  " ERRORE: " . $e->getMessage() . "\n\n" ;
                    }
                    $mysqli->autocommit(TRUE);
                    $update_ed_acquisizioni_file->close();

                    $mysqli->autocommit(FALSE);            
                    try{
                        $insert_stati_acquisizioni = $mysqli->prepare("INSERT INTO e_stati_acquisizioni
                                                                        SET id_acquisizione = ?,
                                                                        id_utente = ?,
                                                                        stato = 'Done',
                                                                        tipologia = '$tipologia_acquisizione',
                                                                        data_cambio_stato = CURRENT_TIMESTAMP");
                        $insert_stati_acquisizioni->bind_param("ii", $id_acquisizione, $id_utente);
                        $insert_stati_acquisizioni->execute();
            
                        $mysqli->commit();
                    }catch(mysqli_sql_exception $e){
                        $mysqli->rollback();

                        echo "ERRORE UPDATE DONE TABELLA DI LOG, ID ACQUISIZIONE: " . $id_acquisizione .  " ERRORE: " . $e->getMessage() . "\n\n" ;
                    }
                    $mysqli->autocommit(TRUE);
                    $insert_stati_acquisizioni->close();

                }catch(Exception $e){
                    $mysqli->rollback();

                    echo "ERRORE CARICAMENTO ACQUISIZIONE " . $tipologia_acquisizione . "!! ID ACQUISIZIONE: " . $id_acquisizione .  " ERRORE: " . $e->getMessage() . PHP_EOL . "\n\n" ;

                    $tipo_errore = $e->getMessage();

                    $mysqli->autocommit(FALSE);            
                    try{
                        $insert_stati_acquisizioni = $mysqli->prepare("INSERT INTO e_stati_acquisizioni
                                                                            SET id_acquisizione = ?,
                                                                            id_utente = ?,
                                                                            data_cambio_stato = CURRENT_TIMESTAMP,
                                                                            tipologia = '$tipologia_acquisizione',
                                                                            stato = 'Failed',
                                                                            log_evento = ?
                                                                            ");
                        $insert_stati_acquisizioni->bind_param("iis", $id_acquisizione, $id_utente, $tipo_errore);
                        $insert_stati_acquisizioni->execute();
                    }catch(mysqli_sql_exception $e){
                        $mysqli->rollback();
                        echo "ERRORE UPDATE FAILED TABELLA ELENCO, ID ACQUISIZIONE: " . $id_acquisizione .  " ERRORE: " . $e->getMessage() . "\n\n" ;
                    }
                    $mysqli->autocommit(TRUE);
                    $insert_stati_acquisizioni->close();

                    $mysqli->autocommit(FALSE);            
                    try{
                        $update_ed_acquisizioni_file = $mysqli->prepare("UPDATE ed_acquisizioni_file
                                                                        SET stato = 'Failed',
                                                                            data_inizio = CURRENT_TIMESTAMP,
                                                                            data_fine = CURRENT_TIMESTAMP
                                                                        WHERE id = ?");
                        $update_ed_acquisizioni_file->bind_param("i", $id_acquisizione);
                        $update_ed_acquisizioni_file->execute();
                    }catch(mysqli_sql_exception $e){
                        $mysqli->rollback();
                        echo "ERRORE UPDATE FAILED TABELLA ELENCO, ID ACQUISIZIONE: " . $id_acquisizione .  " ERRORE: " . $e->getMessage() . PHP_EOL . "\n\n" ;
                    }
                    $mysqli->autocommit(TRUE);
                    $update_ed_acquisizioni_file->close();
                }
                $mysqli->autocommit(TRUE);

                echo "Processo di lavorazione id acquisizione: " . $id_acquisizione . ' terminato.'."\n\n<br><br>";
                echo "Passo alla successiva acquisizione \n\n";
            } else {
                echo "L'acquisizione: " . $id_acquisizione . ' è in lavorazione, aspettare il completamento per proseguire'."\n\n<br><br>";
            }
        }
    } else {
        echo "Non ci sono acquisizioni in coda da importare!<br><br>";
    }
    $select_stati_in_coda->close();
} else {
    echo "Imposta Flag_controllo a 1 per avviare acquisizioni! \n<br><br>";
    exit;
}
?>