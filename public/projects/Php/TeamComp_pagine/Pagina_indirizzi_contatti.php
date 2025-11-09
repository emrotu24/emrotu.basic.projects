<?php
require_once('config/config.php');
require_once('form_actions.php');
//  Include PHPExcel_IOFactory
require_once('assets/plugins/PHPExcel/Classes/PHPExcel.php');
$test_mode = 0;
ini_set('display_errors', $test_mode);
error_reporting(E_ALL | E_STRICT);

//require_once('verifica_utente.php');

// VARIABILI PER LA GESTIONE GRAFICA DELL'EVIDENZIAZIONE DELLA PAGINA CORRETTA NELLA SIDEBAR
$categoria = '_import_export';
$pagina = 'acquisizione_indirizzi_recapiti';

// VARIABILI PER LA GESTIONE DELL'ESECUZIONE EVENTI
$imported_practices = array();

$pratica_creata;

$debug = false;

function controllaCodiceFiscale($cf)
{
    $cf = trim($cf);

    if ($cf == '')
        return false;

    if (strlen($cf) != 16)
        return false;

    $cf = strtoupper($cf);
    if (!preg_match("/[A-Z0-9]+$/", $cf))
        return false;
    $s = 0;

    for ($i = 1; $i <= 13; $i += 2) {
        $c = $cf[$i];
        if ('0' <= $c and $c <= '9')
            $s += ord($c) - ord('0');
        else
            $s += ord($c) - ord('A');
    }

    for ($i = 0; $i <= 14; $i += 2) {
        $c = $cf[$i];

        switch ($c) {
            case '0':
                $s += 1;
                break;
            case '1':
                $s += 0;
                break;
            case '2':
                $s += 5;
                break;
            case '3':
                $s += 7;
                break;
            case '4':
                $s += 9;
                break;
            case '5':
                $s += 13;
                break;
            case '6':
                $s += 15;
                break;
            case '7':
                $s += 17;
                break;
            case '8':
                $s += 19;
                break;
            case '9':
                $s += 21;
                break;
            case 'A':
                $s += 1;
                break;
            case 'B':
                $s += 0;
                break;
            case 'C':
                $s += 5;
                break;
            case 'D':
                $s += 7;
                break;
            case 'E':
                $s += 9;
                break;
            case 'F':
                $s += 13;
                break;
            case 'G':
                $s += 15;
                break;
            case 'H':
                $s += 17;
                break;
            case 'I':
                $s += 19;
                break;
            case 'J':
                $s += 21;
                break;
            case 'K':
                $s += 2;
                break;
            case 'L':
                $s += 4;
                break;
            case 'M':
                $s += 18;
                break;
            case 'N':
                $s += 20;
                break;
            case 'O':
                $s += 11;
                break;
            case 'P':
                $s += 3;
                break;
            case 'Q':
                $s += 6;
                break;
            case 'R':
                $s += 8;
                break;
            case 'S':
                $s += 12;
                break;
            case 'T':
                $s += 14;
                break;
            case 'U':
                $s += 16;
                break;
            case 'V':
                $s += 10;
                break;
            case 'W':
                $s += 22;
                break;
            case 'X':
                $s += 25;
                break;
            case 'Y':
                $s += 24;
                break;
            case 'Z':
                $s += 23;
                break;
        }
    }

    if (chr($s % 26 + ord('A')) != $cf[15])
        return false;

    return true;
}

function converti_data_acquisita($input)
{
    if ($input != '') {
        if ($_POST['formato_data'] == 'ddmmYYYY') {
            if (strlen($input) > 6)
                return $input[4] . $input[5] . $input[6] . $input[7] . '-' . $input[2] . $input[3] . '-' . $input[0] . $input[1];
            else if ($input != '')
                return date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($input));
        } else if ($_POST['formato_data'] == 'dd mm YYYY') {
            if (strpos($input, ' ') > 0)
                return date('Y-m-d', strtotime(str_replace(' ', '-', $input)));
            else if ($input != '')
                return date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($input));
        } else if ($_POST['formato_data'] == 'dd-mm-YYYY') {
            if (strpos($input, '-') > 0)
                return date('Y-m-d', strtotime($input));
            else if ($input != '')
                return date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($input));
        } else if ($_POST['formato_data'] == 'dd/mm/YYYY') {
            if (strpos($input, '/') > 0)
                return date('Y-m-d', strtotime(str_replace('/', '-', $input)));
            else if ($input != '')
                return date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($input));
        } else if ($_POST['formato_data'] == 'YYYYmmdd') {
            if (strlen($input) > 6)
                return $input[0] . $input[1] . $input[2] . $input[3] . '-' . $input[4] . $input[5] . '-' . $input[6] . $input[7];
            else if ($input != '')
                return date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($input));
        } else if ($_POST['formato_data'] == 'YYYY mm dd') {
            if (strpos($input, ' ') > 0)
                return str_replace(' ', '-', $input);
            else if ($input != '')
                return date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($input));
        } else if ($_POST['formato_data'] == 'YYYY-mm-dd') {
            if (strpos($input, '-') > 0)
                return $input;
            else if ($input != '')
                return date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($input));
        } else if ($_POST['formato_data'] == 'YYYY/mm/dd') {
            if (strpos($input, '/') > 0)
                return str_replace('/', '-', $input);
            else if ($input != '')
                return date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($input));
        }
    } else {
        return '';
    }
}

function converti_importo($input)
{
    $input = trim(str_replace('€', '', str_replace(' €', '', str_replace('€ ', '', $input))));

    // INTERO
    if ($_POST['formato_valuta'] == '1') {
        return round($input, 2);
    } // INTERO DI CUI 1 DECIMALE
    else if ($_POST['formato_valuta'] == '2') {
        $length = strlen($input);
        return substr($input, 0, ($length - 1)) . '.' . substr($input, -1);
    } // INTERO DI CUI 2 DECIMALI
    else if ($_POST['formato_valuta'] == '3') {
        $length = strlen($input);
        return substr($input, 0, ($length - 2)) . '.' . substr($input, -2);
        return;
    } // DECIMALE SEPARATO DA PUNTO
    else if ($_POST['formato_valuta'] == '4') {
        return round($input, 2);
    } // DECIMALE SEPARATO DA VIRGOLA
    else if ($_POST['formato_valuta'] == '5') {
        return round(str_replace(',', '.', $input), 2);
    } // DECIMALE PUNTO - MIGLIAIA VIRGOLA
    else if ($_POST['formato_valuta'] == '6') {
        return round(str_replace(',', '', $input), 2);
    } // DECIMALE VIRGOLA - MIGLIAIA PUNTO
    else if ($_POST['formato_valuta'] == '7') {
        return round(str_replace(',', '.', str_replace('.', '', $input)), 2);
    }
}

function calcola_risultante($dati, $occorrenza, $funzioni, $colonne)
{
    if ($occorrenza >= 200) {
        if ($funzioni[$occorrenza - 200] == 'SOMMA') {
            $risultante = 0;
            $colonne_pers = explode(';', $colonne[$occorrenza - 200]);
            for ($g = 0; $g < count($colonne_pers); $g++) {
                if (mb_substr($colonne_pers[$g], 0, 1, 'UTF-8') == '-') {
                    $risultante -= $dati[str_replace('-', '', $colonne_pers[$g]) - 1];
                } else {
                    $risultante += $dati[$colonne_pers[$g] - 1];
                }
            }
        } else if ($funzioni[$occorrenza - 200] == 'RIUTILIZZA') {
            $risultante = $dati[$colonne[$occorrenza - 200] - 1];
        } else if ($funzioni[$occorrenza - 200] == 'CONCATENA') {
            $colonne_pers = explode(';', $colonne[$occorrenza - 200]);
            for ($g = 0; $g < count($colonne_pers); $g++) {
                if ($colonne_pers[$g] == '')
                    $risultante .= ' ';
                else
                    $risultante .= $dati[$colonne_pers[$g] - 1];
            }
            $risultante = trim($risultante);
        }
    } else {
        $risultante = $dati[$occorrenza];
    }

    return db_input($risultante);
}

function cambia_stato_pratica($id_pratica)
{
    global $stato_sostituto;
    global $stati_esclusi;

    if ($stati_esclusi == '')
        $stati_esclusi = (isset($_POST['stati_esclusi']) && $_POST['stati_esclusi'] != '') ? $_POST['stati_esclusi'] : '';

    if ($id_pratica > 0 && $stato_sostituto > 0) {
        $query = 'SELECT stato_corrente 
                            FROM pratiche 
                            WHERE id = "' . $id_pratica . '"';
        $pratica = mysql_fetch_assoc(db_query($query));

        if (!in_array($pratica['stato_corrente'], $stati_esclusi)) {
            $query_aggiornamento_stato_pratica = 'UPDATE pratiche
                                                              SET stato_corrente = "' . $stato_sostituto . '"
                                                              WHERE id = "' . $id_pratica . '"';
            $ris_query_aggiornamento_stato_pratica = db_query($query_aggiornamento_stato_pratica);

            $query_inserimento_stato_pratica = 'INSERT INTO pratiche_stati
                                                          SET id_stato = "' . $stato_sostituto . '",
                                                              id_pratica = "' . $id_pratica . '",
                                                              data_assegnazione = "' . date('Y-m-d H:i:s') . '"';
            $ris_inserimento_stato_pratica = db_query($query_inserimento_stato_pratica);
        }
    }
}

// ---------- CREARE CONNESSIONE ------------------------

$servername ='sql111.infinityfree.com';
$username = 'if0_40236975';
$password ='lfBW09DmpT81r';
$dbname = 'if0_40236975_emrotu_db';

$mysqli = new mysqli($servername, $username, $password, $dbname);

// --------- ABILITO ERRORI MYSQLI -----------------------

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   

// ---------- CONTROLLO CONNESSIONE -------------------------

if ($mysqli->connect_error) {
die("Connessione fallita: " . $msqli->connect_error);
}


function elaboraDati($dati, $riga, $id_acquisizione)
{
    //var_dump($dati); die();
    # /* TEST */ print_r($dati); echo '<br><br>'; print_r($riga); die();
    global $mysqli;
    global $debug;

    global $predefinito;
    global $corrispondenza;

    global $campi;
    global $posizioni;
    global $posizioni_indirizzi;
    global $rec_predefiniti;
    global $rec_attivi;
    global $tipi_indirizzo;
    /*		global $tipi_anagrafica;*/
    global $tipi_recapito_telefonico;

    global $utente;
    global $utente1;

    // OVERRIDE INFORMAZIONI INDIRIZZI E RECAPITI
    global $ind_predefinito;
    global $ind_corrispondenza;
    global $ind_corrispondenza_predefinito;
    global $ind_attivo;
    global $ind_tipo;
    global $sovrascrivi_recapito;
    global $sovrascrivi_indirizzo;

    global $rec_predefinito;
    global $rec_corrispondenza;
    global $rec_attivo;
    global $rec_tipo;

    global $fonte;
    global $data_validita;

    global $eventi_strutturati;

    global $evento_strutturato_predefinito_indirizzi;
    global $evento_strutturato_predefinito_recapiti;

    global $stato_sostituto;
    global $stati_esclusi;

    echo 'ID ACQUISIZIONE FILE: <strong>' . $id_acquisizione. '<br></strong>';

    $utente = array();
    $utente1 = array();

    global $rigaPartenza;

    $dett_univoco = array();

    $id_mandante = $_POST['mandante'];
    $id_utente = '';
    $id_pratica = '';

    $presence_id_mandante = false;
    $presence_id_pratica = false;
    $presence_id_anagrafica = false;
    $presence_codice_fiscale = false;
    $presence_codice_anagrafico_mandante = false;

    $indirizzo_predefinito = 0;
    $recapito_predefinito = 0;

    $occorrenze = array_keys($campi, 'altro*-*id_mandante');
    if (count($occorrenze) > 0) {
        $presence_id_mandante = true;

        for ($k = 0; $k < count($occorrenze); $k++) {
            $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

            $dett_univoco[$k]['id_mandante'] = $risultante;
        }
        $id_mandante = $dett_univoco[0]['id_mandante'];
    }
    echo 'id_mandante: ' . $id_mandante .'<br>';

    $occorrenze = array_keys($campi, 'altro*-*id_pratica');
    if (count($occorrenze) > 0) {
        $presence_id_pratica = true;
        for ($k = 0; $k < count($occorrenze); $k++) {
            $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

            $dett_univoco[$k]['id_pratica'] = $risultante;

            // $qryIdDeb = "SELECT id_debitore from pratiche where id='" . $risultante . "'";
            // $idDebTemp = db_fetch_array_assoc(db_query($qryIdDeb))[0]['id_debitore'];

            $mysqli->autocommit(FALSE);
            try{

                $query_id_debitore_pratica = $mysqli->prepare("SELECT id_debitore FROM pratiche WHERE id = ?");
                $query_id_debitore_pratica->bind_param("i", $risultante);
                $query_id_debitore_pratica->execute();
                $result = $query_id_debitore_pratica->get_result();
                $result_id_debitore_pratica = $result->fetch_assoc();
                $idDebTemp = $result_id_debitore_pratica['id_debitore'];
                                      
                $mysqli->commit();  

            }catch (mysqli_sql_exception $e){
                echo 'ERRORE SELEZIONE ID DEBITORE DA PRATICHE: ' . $e->getMessage() . '<br>';
                $mysqli->rollback();
            };
            $mysqli->autocommit(TRUE);
            $query_id_debitore_pratica->close();
            
            $dett_univoco[$k]['id_utente'] = $idDebTemp;
        }        
        
        $id_pratica = $dett_univoco[0]['id_pratica'];
        $id_utente = $dett_univoco[0]['id_utente'];
    }
    echo 'id_pratica: '.$risultante.'<br>';
    echo 'id_utente: '.$id_utente.'<br>';

    $occorrenze = array_keys($campi, 'altro*-*riferimento_mandante_1');
    if (count($occorrenze) > 0) {
        $presence_id_pratica = true;
        for ($k = 0; $k < count($occorrenze); $k++) {
            $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

            // if ($id_mandante > 0) {
            //     $qryIdDeb =  "SELECT id_debitore,id as id_pratica from pratiche where riferimento_mandante_1='" . $risultante . "' AND id_mandante='" . $id_mandante . "'";
            // } else {
            //     $qryIdDeb = "SELECT id_debitore,id as id_pratica from pratiche where riferimento_mandante_1='" . $risultante . "' order by id desc";

            // }
            // // $idDebTemp = db_fetch_array_assoc(db_query($qryIdDeb))[0];
            
            $mysqli->autocommit(FALSE);
            try{  

                if ($id_mandante > 0) {
                    $qryIdDeb = "SELECT id_debitore, id as id_pratica FROM pratiche WHERE riferimento_mandante_1 = ? AND id_mandante = ?";
                } else {
                    $qryIdDeb = "SELECT id_debitore, id as id_pratica FROM pratiche WHERE riferimento_mandante_1 = ? ORDER BY id DESC";
                }

                $query_id_debitore_id_pratica_rif_1 = $mysqli->prepare($qryIdDeb);
                
                if ($id_mandante > 0) {
                    $query_id_debitore_id_pratica_rif_1->bind_param("si", $risultante, $id_mandante);
                } else {
                    $query_id_debitore_id_pratica_rif_1->bind_param("s", $risultante);
                }
                
                $query_id_debitore_id_pratica_rif_1->execute();
                $result = $query_id_debitore_id_pratica_rif_1->get_result();
                $result_id_debitore_id_pratica_rif_1 = $result->fetch_assoc();
                                      
                $mysqli->commit();  

            }catch (mysqli_sql_exception $e){
                echo 'ERRORE SELEZIONE ID DEBITORE E ID PRATICA PER riferimento_mandante_1: ' . $e->getMessage() . '<br>';
                $mysqli->rollback();
            };
            $mysqli->autocommit(TRUE);
            $query_id_debitore_id_pratica_rif_1->close();

            

            $dett_univoco[$k]['id_utente'] = $result_id_debitore_id_pratica_rif_1['id_debitore'];
            $dett_univoco[$k]['id_pratica'] = $result_id_debitore_id_pratica_rif_1['id_pratica'];

            // $dett_univoco[$k]['id_utente'] = $idDebTemp['id_debitore'];
            // $dett_univoco[$k]['id_pratica'] = $idDebTemp['id_pratica'];
        }


        $id_pratica = $dett_univoco[0]['id_pratica'];
        $id_utente = $dett_univoco[0]['id_utente'];
    }
    echo 'riferimento_mandante_1: '.$risultante.'<br>';
    echo 'id_pratica_rif_1: '.$id_pratica.'<br>';
    echo 'id_utente_rif_1: '.$id_utente.'<br>';
    
    $occorrenze = array_keys($campi, 'altro*-*riferimento_mandante_2');
    if (count($occorrenze) > 0) {
        $presence_id_pratica = true;
        for ($k = 0; $k < count($occorrenze); $k++) {
            $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);
            
            // if ($id_mandante > 0) {
            //     $qryIdDeb = "SELECT id_debitore,id as id_pratica from pratiche where riferimento_mandante_2='" . $risultante . "' AND id_mandante='" . $id_mandante . "'";
            // } else {
            //     $qryIdDeb = "SELECT id_debitore,id as id_pratica from pratiche where riferimento_mandante_2='" . $risultante . "' order by id desc";
                
            // }
            // $idDebTemp = db_fetch_array_assoc(db_query($qryIdDeb))[0];
            $mysqli->autocommit(FALSE);
            try{ 

                if ($id_mandante > 0) {
                    $qryIdDeb = "SELECT id_debitore, id as id_pratica FROM pratiche WHERE riferimento_mandante_2 = ? AND id_mandante = ?";
                } else {
                    $qryIdDeb = "SELECT id_debitore, id as id_pratica FROM pratiche WHERE riferimento_mandante_2 = ? ORDER BY id DESC";
                }
                
                $query_id_debitore_id_pratica_rif_2 = $mysqli->prepare($qryIdDeb);
                
                if ($id_mandante > 0) {
                    $query_id_debitore_id_pratica_rif_2->bind_param("si", $risultante, $id_mandante);
                } else {
                    $query_id_debitore_id_pratica_rif_2->bind_param("s", $risultante);
                }
                
                $query_id_debitore_id_pratica_rif_2->execute();
                $result = $query_id_debitore_id_pratica_rif_2->get_result();
                $result_id_debitore_id_pratica_rif_2 = $result->fetch_assoc();
                                      
                $mysqli->commit();

            }catch (mysqli_sql_exception $e){
                echo 'ERRORE SELEZIONE ID DEBITORE E ID PRATICA PER riferimento_mandante_2: ' . $e->getMessage() . '<br>';
                $mysqli->rollback();
            };
            $mysqli->autocommit(TRUE);
            $query_id_debitore_id_pratica_rif_2->close();
            
            $dett_univoco[$k]['id_utente'] = $result_id_debitore_id_pratica_rif_2['id_debitore'];
            $dett_univoco[$k]['id_pratica'] = $result_id_debitore_id_pratica_rif_2['id_pratica'];

            // $dett_univoco[$k]['id_utente'] = $idDebTemp['id_debitore'];
            // $dett_univoco[$k]['id_pratica'] = $idDebTemp['id_pratica'];
        }
        
        $id_pratica = $dett_univoco[0]['id_pratica'];
        $id_utente = $dett_univoco[0]['id_utente'];
    }
    // echo 'riferimento_mandante_2: '.$qryIdDeb.'<br>';
    echo 'riferimento_mandante_2: '.$risultante.'<br>';
    echo 'id_pratica_rif_2: '.$id_pratica.'<br>';
    echo 'id_utente_rif_2: '.$id_utente.'<br>';
    
    $occorrenze = array_keys($campi, 'altro*-*id_anagrafica');
    if (count($occorrenze) > 0) {
        $presence_id_anagrafica = true;
        for ($k = 0; $k < count($occorrenze); $k++) {
            $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);
            
            $dett_univoco[$k]['id_utente'] = $risultante;
        }
        $id_utente = $dett_univoco[0]['id_utente'];
    }
    //echo 'id_anagrafica: '.$qryIdDeb.'<br>';
    
    $occorrenze = array_keys($campi, 'altro*-*codice_fiscale');
    if (count($occorrenze) > 0) {
        $presence_codice_fiscale = true;
        for ($k = 0; $k < count($occorrenze); $k++) {
            $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);
            
            // $query = 'SELECT id_utente FROM utente WHERE codice_fiscale = "' . $risultante . '" OR partita_iva = "' . $risultante . '"';
            // $row_codice_mandante = mysql_fetch_array(db_query($query));
            
            // $dett_univoco[$k]['id_utente'] = $row_codice_mandante['id_utente'];
            $mysqli->autocommit(FALSE);
            try{ 

                $query = 'SELECT id_utente FROM utente WHERE codice_fiscale = ? OR partita_iva = ?';
                $query_id_utente_da_cf = $mysqli->prepare($query);            
                $query_id_utente_da_cf->bind_param("ss", $risultante, $risultante);          
                
                $query_id_utente_da_cf->execute();
                $result = $query_id_utente_da_cf->get_result();
                $row_codice_mandante = $result->fetch_assoc();
                                      
                $mysqli->commit();

            }catch (mysqli_sql_exception $e){
                echo 'ERRORE SELEZIONE ID UTENTE DA UTENTE: ' . $e->getMessage() . '<br>';
                $mysqli->rollback();
            };
            $mysqli->autocommit(TRUE);
            $query_id_utente_da_cf->close();

            $dett_univoco[$k]['id_utente'] = $row_codice_mandante['id_utente'];

        }
        $id_utente = $dett_univoco[0]['id_utente'];
    }
    echo 'codice_fiscale: '.$risultante.'<br>';
    echo 'row_codice_mandante: '.$id_utente.'<br>';
    
    // $occorrenze = array_keys($campi, 'altro*-*codice_anagrafico_mandante'); // Emanuele ----- De commentare in seguito (dopo che gli utenti sono stati eliminati non viene trovato nella tab utente) TO-DO
    // if (count($occorrenze) > 0) {
    //     $presence_codice_anagrafico_mandante = true;
    //     for ($k = 0; $k < count($occorrenze); $k++) {
    //         $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

    //         $mysqli->autocommit(FALSE);
    //         try{     

    //             $query = 'SELECT id_collegato_pratica FROM anagrafica_collegati_mandante WHERE codice_anagrafico_mandante = ? AND id_mandante = ?';
    //             // $row_codice_mandante = mysql_fetch_array(db_query($query));
                
    //             $query_id_collegato_pratica = $mysqli->prepare($query);            
    //             $query_id_collegato_pratica->bind_param("si", $risultante, $id_mandante);          
                
    //             $query_id_collegato_pratica->execute();
    //             $result = $query_id_collegato_pratica->get_result();
    //             $row_codice_mandante = $result->fetch_assoc();
                                      
    //             $mysqli->commit();  

    //         }catch (mysqli_sql_exception $e){
    //             echo 'ERRORE SELEZIONE ID_COLLEGATO_PRATICA DA ANAGRAFICA_COLLEGATI_MANDANTE: ' . $e->getMessage() . '<br>';
    //             $mysqli->rollback();
    //         };
    //         $mysqli->autocommit(TRUE);
    //         $query_id_collegato_pratica->close();
            

    //         $dett_univoco[$k]['id_utente'] = $row_codice_mandante['id_collegato_pratica'];
    //     }
    //     $id_utente = $dett_univoco[0]['id_utente'];
    // }
    // echo 'row_codice_mandante: '.$id_utente.'<br>';



    // RECUPERO DELL'EVENTO DA SCATENARE E DELLE PRATICHE RELATIVE
    # Le pratiche sulle quali scatenare l’evento strutturato indicato a livello di testa o di riga, saranno:
    # 1. Tutte quelle collegate all’anagrafica importata, quando l’identificazione dell’anagrafica viene eseguita tramite, [Id] anagrafica Remida, o [Codice Fiscale] o [Partita Iva].
    # 2. Solo quelle collegate all’anagrafica importata relativa a quel mandante nel caso l’identificazione venga eseguita con [Codice_Anagrafico_Mandante] + [Id] Anagrafica Mandante.
    # 3. Solo la pratica indicata nel file nel caso l’identificazione venga eseguita con [IdPratica] + [Id] anagrafica o [IdPratica] + [Codice Fiscale] o [IdPratica] + [Partita Iva].
    if ($debug) echo 'ID EVENTO STRUTTURATO INDIRIZZI: ' . $evento_strutturato_predefinito_indirizzi . '<br>';
    if ($debug) echo 'ID EVENTO STRUTTURATO RECAPITI: ' . $evento_strutturato_predefinito_recapiti . '<br>';

    if ($evento_strutturato_predefinito_indirizzi > 0) {
        $risultante = $evento_strutturato_predefinito_indirizzi;

        $stringa_pratiche = '';


        if (isset($id_pratica) && $id_pratica > 0) {
            $stringa_pratiche = $id_pratica . ',';
        } else {

            if ($id_utente > 0) {
                // $query_selezione_pratiche = 'SELECT id_pratica FROM collegati_pratica WHERE id_collegato = "' . db_input($id_utente) . '"';
                // $ris_selezione_pratiche = db_query($query_selezione_pratiche);
                
                // if ($debug) echo "QUERY SELEZIONA PRATICHE" . $query_selezione_pratiche . '<br>';
                
                // while ($row = mysql_fetch_array($ris_selezione_pratiche)) {
                //     $stringa_pratiche .= $row['id_pratica'] . ',';
                // }

                $mysqli->autocommit(FALSE);
                try{ 

                    $query_selezione_pratiche = 'SELECT id_pratica FROM collegati_pratica WHERE id_collegato = ?';
    
                    $selezione_pratiche = $mysqli->prepare($query_selezione_pratiche);            
                    $selezione_pratiche->bind_param("i", $id_utente);          
                    
                    $selezione_pratiche->execute();
                    $ris_selezione_pratiche = $selezione_pratiche->get_result();
    
                    if ($debug) echo "QUERY SELEZIONA PRATICHE" . $query_selezione_pratiche . '<br>';
    
                    while ($row = $ris_selezione_pratiche->fetch_assoc()) {
                        $stringa_pratiche .= $row['id_pratica'] . ',';
                    }
                                        
                    $mysqli->commit();  

                }catch (mysqli_sql_exception $e){
                    echo 'ERRORE SELEZIONE ID PRATICA DA COLLEGATI PRATICA: ' . $e->getMessage() . '<br>';
                    $mysqli->rollback();
                };
                $mysqli->autocommit(TRUE);
                $selezione_pratiche->close();
            }
        }

        $eventi_strutturati[$risultante] .= $stringa_pratiche;
    }
    
    if ($evento_strutturato_predefinito_recapiti > 0) {
        $risultante = $evento_strutturato_predefinito_recapiti;
        
        $stringa_pratiche = '';
        
        if (isset($id_pratica) && $id_pratica > 0) {
            $stringa_pratiche = $id_pratica . ',';
        } else {
            if ($id_utente > 0) {
                
                // $query_selezione_pratiche = 'SELECT id_pratica FROM collegati_pratica WHERE id_collegato = "' . db_input($id_utente) . '"';
                // $ris_selezione_pratiche = db_query($query_selezione_pratiche);
                
                // $array_sel_pratiche = db_fetch_array_assoc($ris_selezione_pratiche);
                
                
                // if ($debug) echo $query_selezione_pratiche . '<br>';
                
                // foreach ($array_sel_pratiche as $sel_pratiche) {
                    //     $stringa_pratiche .= $sel_pratiche['id_pratica'] . ',';
                    // }
                    
                $mysqli->autocommit(FALSE);
                try{     
                    
                    $query_selezione_pratiche = 'SELECT id_pratica FROM collegati_pratica WHERE id_collegato = ?';
                    
                    $selezione_pratiche = $mysqli->prepare($query_selezione_pratiche);            
                    $selezione_pratiche->bind_param("i", $id_utente);          
                    
                    $selezione_pratiche->execute();
                    $ris_selezione_pratiche = $selezione_pratiche->get_result();
                    $array_sel_pratiche = $ris_selezione_pratiche->fetch_assoc();
                    
                    if ($debug) echo $query_selezione_pratiche . '<br>';
                    
                    foreach ($array_sel_pratiche as $sel_pratiche) {
                        $stringa_pratiche .= $sel_pratiche['id_pratica'] . ',';
                    }
                    
                    $mysqli->commit();  
                    
                }catch (mysqli_sql_exception $e){
                    echo 'ERRORE SELEZIONE ID PRATICA DA COLLEGATI PRATICA: ' . $e->getMessage() . '<br>';
                    $mysqli->rollback();
                };
                $mysqli->autocommit(TRUE);
                $selezione_pratiche->close();                
            }
        }
        $eventi_strutturati[$risultante] .= $stringa_pratiche;
    }

    if ($debug) {
        echo "PRATICHE EVENTI";
        print_r($eventi_strutturati);
        echo '<br>';
    }
    if (true) {
        echo "PRATICHE EVENTI";
        print_r($eventi_strutturati);
        echo '<br>';
    }

    $occorrenze = array_keys($campi, 'anagrafica*-*evento_strutturato');
    if (count($occorrenze) > 0) {
        $stringa_pratiche = '';

        for ($k = 0; $k < count($occorrenze); $k++) {
            $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

            $praticaEventoCambioStato = 0;


            if ($presence_id_pratica) {
                $stringa_pratiche = $id_pratica . ',';
                cambia_stato_pratica($id_pratica);

            } else if ($presence_id_anagrafica || $presence_codice_fiscale) {
                // $query_selezione_pratiche = 'SELECT id FROM pratiche WHERE id_debitore = "' . $id_utente . '" AND (scaricata = 0 OR scaricata IS NULL)';
                // $ris_selezione_pratiche = db_query($query_selezione_pratiche);
                
                // while ($row = mysql_fetch_array($ris_selezione_pratiche)) {
                    //     $stringa_pratiche .= $row['id'] . ',';
                    //     cambia_stato_pratica($row['id']);
                    // }

                $mysqli->autocommit(FALSE);
                try{ 

                    $query_selezione_pratiche = 'SELECT id FROM pratiche WHERE id_debitore = ? AND (scaricata = 0 OR scaricata IS NULL)';
                    $selezione_pratiche = $mysqli->prepare($query_selezione_pratiche);            
                    $selezione_pratiche->bind_param("i", $id_utente);          
                    
                    $selezione_pratiche->execute();
                    $ris_selezione_pratiche = $selezione_pratiche->get_result();
                    // $array_sel_pratiche = $ris_selezione_pratiche->fetch_assoc();
    
                    while ($row = $ris_selezione_pratiche->fetch_assoc()) {
                        $stringa_pratiche .= $row['id'] . ',';
                        cambia_stato_pratica($row['id']);
                    }
                                        
                    $mysqli->commit();  

                }catch (mysqli_sql_exception $e){
                    echo 'ERRORE SELEZIONE ID PRATICA DA PRATICHE PER UTENTE: ' . $e->getMessage() . '<br>';
                    $mysqli->rollback();
                };
                $mysqli->autocommit(TRUE);
                $selezione_pratiche->close(); 
                    
            } else if ($presence_codice_anagrafico_mandante && $presence_id_mandante) {
                // $query_selezione_pratiche = 'SELECT id FROM pratiche WHERE id_debitore = "' . $id_utente . '" AND (scaricata = 0 OR scaricata IS NULL) AND id_mandante = "' . $id_mandante . '"';
                // $ris_selezione_pratiche = db_query($query_selezione_pratiche);
                // while ($row = mysql_fetch_array($ris_selezione_pratiche)) {
                    //     $stringa_pratiche .= $row['id'] . ',';
                    
                    //     cambia_stato_pratica($row['id']);
                    // }

                $mysqli->autocommit(FALSE);
                try{ 

                    $query_selezione_pratiche = 'SELECT id FROM pratiche WHERE id_debitore = ? AND (scaricata = 0 OR scaricata IS NULL) AND id_mandante = ?';
                    $selezione_pratiche = $mysqli->prepare($query_selezione_pratiche);            
                    $selezione_pratiche->bind_param("ii", $id_utente, $id_mandante);          
                    
                    $selezione_pratiche->execute();
                    $ris_selezione_pratiche = $selezione_pratiche->get_result();
                    // $array_sel_pratiche = $ris_selezione_pratiche->fetch_assoc();
    
                    while ($row = $ris_selezione_pratiche->fetch_assoc()) {
                        $stringa_pratiche .= $row['id'] . ',';
                        cambia_stato_pratica($row['id']);
                    }

                    $mysqli->commit();  

                }catch (mysqli_sql_exception $e){
                    echo 'ERRORE SELEZIONE ID PRATICA DA PRATICHE PER UTENTE: ' . $e->getMessage() . '<br>';
                    $mysqli->rollback();
                };
                $mysqli->autocommit(TRUE);
                $selezione_pratiche->close(); 
                    
            }
            $eventi_strutturati[$risultante] .= $stringa_pratiche;

        }
    }


    $occorrenze = array_keys($campi, 'anagrafica*-*codifica_evento_strutturato');
    if (count($occorrenze) > 0) {
        for ($k = 0; $k < count($occorrenze); $k++) {
            $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

            // $query_seleziona_tipo_indirizzo = 'SELECT id_remida 
			// 										FROM decodifiche_dettagli DD
			// 											LEFT JOIN decodifiche D ON DD.id_decodifica = D.id
			// 										WHERE id_mandante = "' . db_input($id_mandante) . '"
			// 										AND D.tipo = "evento strutturato"
			// 										AND (codice_uno = "' . $risultante . '"
			// 										OR codice_due = "' . $risultante . '")';
            // $evento_strutturato = mysql_fetch_array(db_query($query_seleziona_tipo_indirizzo));

            $mysqli->autocommit(FALSE);
            try{ 

                $query_seleziona_tipo_indirizzo = 'SELECT id_remida 
                                                        FROM decodifiche_dettagli DD
                                                            LEFT JOIN decodifiche D ON DD.id_decodifica = D.id
                                                        WHERE id_mandante = ?
                                                        AND D.tipo = "evento strutturato"
                                                        AND (codice_uno = ?
                                                        OR codice_due = ?)';
                $selezione_tipo_indirizzo = $mysqli->prepare($query_seleziona_tipo_indirizzo);            
                $selezione_tipo_indirizzo->bind_param("is", $id_mandante, $risultante);          
                
                $selezione_tipo_indirizzo->execute();
                $ris_selezione_tipo_indirizzo = $selezione_tipo_indirizzo->get_result();
                $evento_strutturato = $ris_selezione_tipo_indirizzo->fetch_assoc();
                                    
                $mysqli->commit();  

            }catch (mysqli_sql_exception $e){
                echo 'ERRORE SELEZIONE TIPO INDIRIZZO DA DECODIFICHE DETTAGLI: ' . $e->getMessage() . '<br>';
                $mysqli->rollback();
            };
            $mysqli->autocommit(TRUE);
            $selezione_tipo_indirizzo->close(); 
                                                    

            $risultante = $evento_strutturato['id_remida'];

            
            
            $stringa_pratiche = '';
            
            if ($presence_id_pratica && ($presence_id_anagrafica || $presence_codice_fiscale)) {
                $stringa_pratiche = $id_pratica . ',';
                $eventi_strutturati[$risultante] .= $stringa_pratiche;
                
                cambia_stato_pratica($id_pratica);
            } else if ($presence_id_anagrafica || $presence_codice_fiscale) {
                // $query_selezione_pratiche = 'SELECT id FROM pratiche WHERE id_debitore = ' . $id_utente;
                // $ris_selezione_pratiche = db_query($query_selezione_pratiche);
                
                // while ($row = mysql_fetch_array($ris_selezione_pratiche)) {
                //     $stringa_pratiche = $row['id'] . ',';
                    
                //     cambia_stato_pratica($row['id']);
                // }

                $mysqli->autocommit(FALSE);
                try{ 

                    $query_selezione_pratiche = 'SELECT id FROM pratiche WHERE id_debitore = ?';

                    $selezione_pratiche = $mysqli->prepare($query_selezione_pratiche);            
                    $selezione_pratiche->bind_param("i", $id_utente);          
                    
                    $selezione_pratiche->execute();
                    $ris_selezione_pratiche = $selezione_pratiche->get_result();
                    // $array_sel_pratiche = $ris_selezione_pratiche->fetch_assoc();
    
                    while ($row = $ris_selezione_pratiche->fetch_assoc()) {
                        $stringa_pratiche .= $row['id'] . ',';
                        cambia_stato_pratica($row['id']);
                    }

                    $mysqli->commit();  

                }catch (mysqli_sql_exception $e){
                    echo 'ERRORE SELEZIONE ID PRATICA DA PRATICHE PER UTENTE: ' . $e->getMessage() . '<br>';
                    $mysqli->rollback();
                };
                $mysqli->autocommit(TRUE);
                $selezione_pratiche->close();
                
                $eventi_strutturati[$risultante] .= $stringa_pratiche;
            } else if ($presence_codice_anagrafico_mandante && $presence_id_mandante) {
                // $query_selezione_pratiche = 'SELECT id FROM pratiche WHERE id_debitore = ' . $id_utente . ' AND id_mandante = ' . $id_mandante;
                // $ris_selezione_pratiche = db_query($query_selezione_pratiche);
                
                // while ($row = mysql_fetch_array($ris_selezione_pratiche)) {
                //     $stringa_pratiche = $row['id'] . ',';
                    
                //     cambia_stato_pratica($row['id']);
                // }

                $mysqli->autocommit(FALSE);
                try{

                    $query_selezione_pratiche = 'SELECT id FROM pratiche WHERE id_debitore = ? AND id_mandante = ?';

                    $selezione_pratiche = $mysqli->prepare($query_selezione_pratiche);            
                    $selezione_pratiche->bind_param("ii", $id_utente, $id_utente);          
                    
                    $selezione_pratiche->execute();
                    $ris_selezione_pratiche = $selezione_pratiche->get_result();
                    // $array_sel_pratiche = $ris_selezione_pratiche->fetch_assoc();
    
                    while ($row = $ris_selezione_pratiche->fetch_assoc()) {
                        $stringa_pratiche .= $row['id'] . ',';
                        cambia_stato_pratica($row['id']);
                    }

                    $mysqli->commit();  

                }catch (mysqli_sql_exception $e){
                    echo 'ERRORE SELEZIONE ID PRATICA DA PRATICHE PER UTENTE: ' . $e->getMessage() . '<br>';
                    $mysqli->rollback();
                };
                $mysqli->autocommit(TRUE);
                $selezione_pratiche->close();
                
                
                $eventi_strutturati[$risultante] .= $stringa_pratiche;
            }
        }
    }

    if (trim($dett_univoco[0]['id_utente']) != '') {

        ?>

        <br><br>AGGIORNAMENTO UTENTE:<br>

        <?php
        if (true) {
            $query_utente = array();
            $query_anagrafica = array();
            $query_anagrafica_2 = array();
            $query_recapito = array();
            $query_recapito_telefonico = array();

            $dett_utente = array();
            $dett_anagrafica = array();
            $dett_anagrafica_2 = array();
            $dett_recapito = array();
            $dett_recapito_telefonico = array();

            $dett_recapito_predefinito = array();
            $dett_recapito_corrispondenza = array();
            $dett_recapito_attivo = array();

            if (true) {
                // UTENTE
                /*
						#anagrafica*-*codice_fiscale
						anagrafica*-*politicamente_esposto
						anagrafica*-*note
						*/
                $occorrenze = array_keys($campi, 'anagrafica*-*codice_fiscale');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    if ($risultante != '') {
                        if (controllaCodiceFiscale($risultante)) {
                            $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                            //$query_utente[$k] .= 'codice_fiscale = "'.$risultante.'", ';
                            //$dett_utente[$k]['codice_fiscale'] = $risultante;
                            $query_utente[0] .= 'codice_fiscale = "' . $risultante . '", ';
                            $dett_utente[0]['codice_fiscale'] = $risultante;
                        } else {
                            $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                            //$query_utente[$k] .= 'partita_iva = "'.$risultante.'", ';
                            //$dett_utente[$k]['partita_iva'] = $risultante;
                            $query_utente[0] .= 'partita_iva = "' . $risultante . '", ';
                            $dett_utente[0]['partita_iva'] = $risultante;
                        }
                    }
                }
                $occorrenze = array_keys($campi, 'anagrafica*-*politicamente_esposto');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_utente[$k] .= 'politicamente_esposto = "' . $risultante . '", ';
                    $dett_utente[$k]['politicamente_esposto'] = $risultante;
                }
                $occorrenze = array_keys($campi, 'anagrafica*-*note');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_utente[$k] .= 'note = "' . $risultante . '", ';
                    $dett_utente[$k]['note'] = $risultante;
                }

                // ANAGRAFICA
                /*
						anagrafica*-*sesso
						anagrafica*-*data_nascita
						anagrafica*-*lingua
						*/
                $occorrenze = array_keys($campi, 'anagrafica*-*sesso');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $risultante_sesso = $risultante == 'M' ? 0 : 1;

                    $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante_sesso;
                    $query_anagrafica[$k] .= 'sesso = "' . db_input($risultante_sesso) . '", ';
                    $dett_anagrafica[$k]['sesso'] = $risultante_sesso;
                }
                $occorrenze = array_keys($campi, 'anagrafica*-*data_nascita');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_anagrafica[$k] .= 'data_nascita = "' . converti_data_acquisita($risultante) . '", ';
                    $dett_anagrafica[$k]['data_nascita'] = converti_data_acquisita($risultante);
                }
                $occorrenze = array_keys($campi, 'anagrafica*-*data_decesso');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_anagrafica[$k] .= 'data_decesso = "' . converti_data_acquisita($risultante) . '", ';
                    $dett_anagrafica[$k]['data_decesso'] = converti_data_acquisita($risultante);
                }
                // $occorrenze = array_keys($campi, 'anagrafica*-*citta_nascita');
                // for ($k = 0; $k < count($occorrenze); $k++) {
                //     $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                //     $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                //     $query_anagrafica[$k] .= 'citta_nascita = "' . $risultante . '", ';
                //     $dett_anagrafica[$k]['citta_nascita'] = $risultante;
                // }
                // $occorrenze = array_keys($campi, 'anagrafica*-*provincia_nascita');
                // for ($k = 0; $k < count($occorrenze); $k++) {
                //     $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                //     $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                //     // $provincia = mysql_fetch_array(db_query('SELECT cod_provincia FROM province WHERE sigla = "' . addslashes($risultante) . '"'));
                //     // $query_anagrafica[$k] .= 'provincia_nascita = "' . db_input($provincia['cod_provincia']) . '", ';
                //     // $dett_anagrafica[$k]['provincia_nascita'] = $provincia['cod_provincia'];

                //     $mysqli->autocommit(FALSE);
                //     try{ 

                //         $query_seleziona_provincia = 'SELECT cod_provincia FROM province WHERE sigla = ?';
                //         $selezione_provincia = $mysqli->prepare($query_seleziona_provincia);            
                //         $selezione_provincia->bind_param("s", addslashes($risultante));          
                        
                //         $selezione_provincia->execute();
                //         $ris_selezione_provincia = $selezione_provincia->get_result();
                //         $provincia = $ris_selezione_provincia->fetch_assoc();
                                            
                //         $mysqli->commit();  

                //     }catch (mysqli_sql_exception $e){
                //         echo 'ERRORE SELEZIONE PROVINCIA: ' . $e->getMessage() . '<br>';
                //         $mysqli->rollback();
                //     };
                //     $mysqli->autocommit(TRUE);
                //     $selezione_provincia->close();
                // }
                // $occorrenze = array_keys($campi, 'anagrafica*-*nazione_nascita');
                // for ($k = 0; $k < count($occorrenze); $k++) {
                //     $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                //     $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                //     $query_anagrafica[$k] .= 'nazione_nascita = "' . $risultante . '", ';
                //     $dett_anagrafica[$k]['nazione_nascita'] = $risultante;
                // }
                
                $occorrenze = array_keys($campi, 'anagrafica*-*lingua');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);
                    
                    if ($risultante != '') {
                        // $query = 'SELECT id_lingua FROM lingua WHERE codice_lingua = "' . $risultante . '"';
                        // $result = db_query($query);
                        // $lingua = mysql_fetch_assoc($result);

                        $mysqli->autocommit(FALSE);
                        try{ 
                            
                            $query_lingua = 'SELECT id_lingua FROM lingua WHERE codice_lingua = ?';
                            $selezione_lingua = $mysqli->prepare($query_lingua);            
                            $selezione_lingua->bind_param("s", $risultante);          
                            
                            $selezione_lingua->execute();
                            $result = $selezione_lingua->get_result();
                            $lingua = $result->fetch_assoc();
                            
                            
                            $mysqli->commit();  
                            
                        }catch (mysqli_sql_exception $e){
                            echo 'ERRORE SELEZIONE LINGUA: ' . $e->getMessage() . '<br>';
                            $mysqli->rollback();
                        };
                        $mysqli->autocommit(TRUE);
                        $selezione_lingua->close();
                        
                        $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $lingua['id_lingua'];
                        $query_anagrafica[$k] .= 'codice_lingua = "' . db_input($lingua['id_lingua']) . '", ';
                        $dett_anagrafica[$k]['codice_lingua'] = $lingua['id_lingua'];
                    }
                }

                // ANAGRAFICA debitore
                /*
						anagrafica*-*protestato
						anagrafica*-*catasto_positivo
						*/
                $occorrenze = array_keys($campi, 'anagrafica*-*protestato');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_anagrafica_2[$k] .= 'protestato = "' . $risultante . '", ';
                    $dett_anagrafica_2[$k]['protestato'] = $risultante;
                }
                $occorrenze = array_keys($campi, 'anagrafica*-*catasto_positivo');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_anagrafica_2[$k] .= 'catasto_positivo = "' . $risultante . '", ';
                    $dett_anagrafica_2[$k]['catasto_positivo'] = $risultante;
                }
                $occorrenze = array_keys($campi, 'anagrafica*-*politicamente_esposto');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_anagrafica_2[$k] .= 'politicamente_esposto = "' . $risultante . '", ';
                    $dett_anagrafica_2[$k]['politicamente_esposto'] = $risultante;
                }

                // RECAPITO
                /*
						anagrafica*-*tipo_indirizzo
						anagrafica*-*indirizzo
						anagrafica*-*indirizzo_corrispondenza
						anagrafica*-*indirizzo_principale
						anagrafica*-*indirizzo_attivo
						anagrafica*-*citta
						anagrafica*-*provincia
						anagrafica*-*nazione

						global $ind_predefinito;
						global $ind_corrispondenza;
						global $ind_attivo;
						global $ind_tipo;
						*/

                $esistenza_recapito = false;

                $occorrenze = array_keys($campi, 'anagrafica*-*indirizzo');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    if ($risultante != '') {
                        $esistenza_recapito = true;

                        $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;

                        $query_recapito[$posizioni_indirizzi[$occorrenze[$k]]] .= 'indirizzo = "' . $risultante . '", ';
                        $dett_recapito[$posizioni_indirizzi[$occorrenze[$k]]]['indirizzo'] = $risultante;

                        if ($fonte > 0) {
                            $query_recapito[$posizioni_indirizzi[$occorrenze[$k]]] .= 'fonte = "' . db_input($fonte) . '", ';
                            $dett_recapito[$posizioni_indirizzi[$occorrenze[$k]]]['fonte'] = $fonte;
                        }

                        if ($data_validita != '') {
                            $query_recapito[$posizioni_indirizzi[$occorrenze[$k]]] .= 'valido_da = "' . db_input($data_validita) . '", ';
                            $dett_recapito[$posizioni_indirizzi[$occorrenze[$k]]]['valido_da'] = $data_validita;
                        }

                        /*
								if($ind_corrispondenza_predefinito>0) {
									$query_recapito[$posizioni_indirizzi[$occorrenze[$k]]] .= 'invio_corrispondenza = "'.db_input($ind_corrispondenza_predefinito).'", ';
									$dett_recapito[$posizioni_indirizzi[$occorrenze[$k]]]['invio_corrispondenza'] = $fonte;
								}
								*/

                        /* Ticket #522225 - 24-05-2017
								if(!$tipo_recapito_debitore) {
									$query_recapito[$posizioni_indirizzi[$occorrenze[$k]]] .= 'tipo_recapito = "'.db_input($tipi_indirizzo[$occorrenze[$k]]).'", ';
									$dett_recapito[$posizioni_indirizzi[$occorrenze[$k]]]['tipo_recapito'] = $tipi_indirizzo[$occorrenze[$k]];
								}
								*/
                        if ($tipi_indirizzo[$occorrenze[$k]] > 0 && count(array_keys($campi, 'anagrafica*-*tipo_indirizzo')) == 0) {
                            $query_recapito[$posizioni_indirizzi[$occorrenze[$k]]] .= 'tipo_recapito = "' . db_input($tipi_indirizzo[$occorrenze[$k]]) . '", ';
                            $dett_recapito[$posizioni_indirizzi[$occorrenze[$k]]]['tipo_recapito'] = $tipi_indirizzo[$occorrenze[$k]];

                        }
                    }
                }

                if ($esistenza_recapito) {
                    $tipo_recapito_debitore = false;
                    $occorrenze = array_keys($campi, 'anagrafica*-*tipo_indirizzo');
                    //if($ind_tipo > 0 || count($occorrenze)>0) {
                    if ($ind_tipo > 0) {

                        if ($ind_tipo > 0) {
                            $tipo_recapito_debitore = true;
                            $query_recapito[0] .= 'tipo_recapito = "' . db_input($ind_tipo) . '", ';
                            $dett_recapito[0]['tipo_recapito'] = $ind_tipo;
                        }
                    } else {
                        for ($k = 0; $k < count($occorrenze); $k++) {
                            $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                            if ($risultante != '') {
                                $tipo_recapito_debitore = true;

                                $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;

                                // $query_seleziona_tipo_indirizzo = 'SELECT id_remida 
								// 											FROM decodifiche_dettagli DD
								// 												LEFT JOIN decodifiche D ON DD.id_decodifica = D.id
								// 											WHERE id_mandante = "' . db_input($id_mandante) . '"
								// 											AND D.tipo = "tipo indirizzo"
								// 											AND (codice_uno = "' . $risultante . '"
								// 											OR codice_due = "' . $risultante . '")';
                                // $tipo_indirizzo = mysql_fetch_array(db_query($query_seleziona_tipo_indirizzo));

                                $mysqli->autocommit(FALSE);
                                try{ 

                                    $query_seleziona_tipo_indirizzo = 'SELECT id_remida 
                                                                            FROM decodifiche_dettagli DD
                                                                                LEFT JOIN decodifiche D ON DD.id_decodifica = D.id
                                                                            WHERE id_mandante = ?
                                                                            AND D.tipo = "tipo indirizzo"
                                                                            AND (codice_uno = ?
                                                                            OR codice_due = ?)';
                                    $selezione_tipo_indirizzo = $mysqli->prepare($query_seleziona_tipo_indirizzo);            
                                    $selezione_tipo_indirizzo->bind_param("iss", $id_mandante, $risultante, $risultante);          
                                    
                                    $selezione_tipo_indirizzo->execute();
                                    $ris_selezione_tipo_indirizzo = $selezione_tipo_indirizzo->get_result();
                                    $tipo_indirizzo = $ris_selezione_tipo_indirizzo->fetch_assoc();
                                                        
                                    $mysqli->commit();

                                }catch (mysqli_sql_exception $e){
                                    echo 'ERRORE SELEZIONE TIPO INDIRIZZO DA DECODIFICHE DETTAGLI: ' . $e->getMessage() . '<br>';
                                    $mysqli->rollback();
                                };
                                $mysqli->autocommit(TRUE);
                                $selezione_tipo_indirizzo->close();

                                $query_recapito[$posizioni_indirizzi[$occorrenze[$k]]] .= 'tipo_recapito = "' . db_input($tipo_indirizzo['id_remida']) . '", ';
                                $dett_recapito[$posizioni_indirizzi[$occorrenze[$k]]]['tipo_recapito'] = $tipo_indirizzo['id_remida'];
                            }
                        }

                    }

                    if ($ind_corrispondenza_predefinito > 0) {
                        for ($sk = 0; $sk < 50; $sk++) {
                            if (isset($query_recapito[$sk])) {
                                $query_recapito[$sk] .= 'invio_corrispondenza = "' . db_input($ind_corrispondenza_predefinito) . '", ';
                                $dett_recapito[$sk]['invio_corrispondenza'] = $ind_corrispondenza_predefinito;
                            }
                        }
                    } else {
                        $occorrenze = array_keys($campi, 'anagrafica*-*indirizzo_corrispondenza');
                        if (count($occorrenze)) {
                            for ($k = 0; $k < count($occorrenze); $k++) {
                                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                                if ($risultante != '') {
                                    $query_recapito[$posizioni_indirizzi[$occorrenze[$k]]] .= 'invio_corrispondenza = "' . $risultante . '", ';
                                    $dett_recapito[$posizioni_indirizzi[$occorrenze[$k]]]['invio_corrispondenza'] = $risultante;
                                }
                            }
                        } else {
                            for ($sk = 0; $sk < 50; $sk++) {
                                if (isset($query_recapito[$sk])) {
                                    $query_recapito[$sk] .= 'invio_corrispondenza = "0", ';
                                    $dett_recapito[$sk]['invio_corrispondenza'] = 0;
                                }
                            }
                        }
                    }

                    if ($ind_predefinito > 0) {
                        for ($sk = 0; $sk < 50; $sk++) {
                            if (isset($query_recapito[$sk])) {
                                $query_recapito[$sk] .= 'predefinito = "' . db_input($ind_predefinito) . '", ';
                                $dett_recapito[$sk]['predefinito'] = $ind_predefinito;
                            }
                        }

                        if ($ind_predefinito == 1)
                            $indirizzo_predefinito = 1;
                    } else {
                        $occorrenze = array_keys($campi, 'anagrafica*-*indirizzo_principale');
                        if (count($occorrenze)) {
                            for ($k = 0; $k < count($occorrenze); $k++) {
                                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                                if ($risultante != '') {
                                    $query_recapito[$posizioni_indirizzi[$occorrenze[$k]]] .= 'predefinito = "' . $risultante . '", ';
                                    $dett_recapito[$posizioni_indirizzi[$occorrenze[$k]]]['predefinito'] = $risultante;
                                }

                                if ($risultante == 1)
                                    $indirizzo_predefinito = 1;
                            }
                        } // ???
                        else {
                            for ($sk = 0; $sk < 50; $sk++) {
                                if (isset($query_recapito[$sk])) {
                                    $query_recapito[$sk] .= 'predefinito = "0", ';
                                    $dett_recapito[$sk]['predefinito'] = 0;
                                }
                            }
                        }
                    }

                    if ($ind_attivo > 0) {
                        for ($sk = 0; $sk < 50; $sk++) {
                            if (isset($query_recapito[$sk])) {
                                $query_recapito[$sk] .= 'attivo = "' . db_input($ind_attivo) . '", ';
                                $dett_recapito[$sk]['attivo'] = $ind_attivo;
                            }
                        }
                    } else {
                        $occorrenze = array_keys($campi, 'anagrafica*-*indirizzo_attivo');
                        if (count($occorrenze)) {
                            for ($k = 0; $k < count($occorrenze); $k++) {
                                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                                if ($risultante != '') {
                                    $query_recapito[$posizioni_indirizzi[$occorrenze[$k]]] .= 'attivo = "' . $risultante . '", ';
                                    $dett_recapito[$posizioni_indirizzi[$occorrenze[$k]]]['attivo'] = $risultante;
                                }
                            }
                        } else {
                            for ($sk = 0; $sk < 50; $sk++) {
                                if (isset($query_recapito[$sk])) {
                                    $query_recapito[$sk] .= 'attivo = "0", ';
                                    $dett_recapito[$sk]['attivo'] = 0;
                                }
                            }
                        }
                    }

                    $occorrenze = array_keys($campi, 'anagrafica*-*cap');
                    for ($k = 0; $k < count($occorrenze); $k++) {
                        $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                        if ($risultante != '') {
                            $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                            $query_recapito[$posizioni_indirizzi[$occorrenze[$k]]] .= 'cap = "' . $risultante . '", ';
                            $dett_recapito[$posizioni_indirizzi[$occorrenze[$k]]]['cap'] = $risultante;
                        }
                    }
                    $occorrenze = array_keys($campi, 'anagrafica*-*citta');
                    for ($k = 0; $k < count($occorrenze); $k++) {
                        $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                        if ($risultante != '') {
                            $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                            $query_recapito[$posizioni_indirizzi[$occorrenze[$k]]] .= 'citta = "' . $risultante . '", ';
                            $dett_recapito[$posizioni_indirizzi[$occorrenze[$k]]]['citta'] = $risultante;
                        }
                    }

                    $occorrenze = array_keys($campi, 'anagrafica*-*provincia');
                    for ($k = 0; $k < count($occorrenze); $k++) {
                        $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                        if ($risultante != '') {
                            $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                            // $provincia = mysql_fetch_array(db_query('SELECT cod_provincia FROM province WHERE sigla = "' . $risultante . '"'));

                            $mysqli->autocommit(FALSE);
                            try{ 

                                $query_seleziona_provincia = 'SELECT cod_provincia FROM province WHERE sigla = ?';
                                $selezione_provincia = $mysqli->prepare($query_seleziona_provincia);            
                                $selezione_provincia->bind_param("s", addslashes($risultante));          
                                
                                $selezione_provincia->execute();
                                $ris_selezione_provincia = $selezione_provincia->get_result();
                                $provincia = $ris_selezione_provincia->fetch_assoc();
                                                    
                                $mysqli->commit();  

                            }catch (mysqli_sql_exception $e){
                                echo 'ERRORE SELEZIONE PROVINCIA: ' . $e->getMessage() . '<br>';
                                $mysqli->rollback();
                            };
                            $mysqli->autocommit(TRUE);
                            $selezione_provincia->close();

                            $query_recapito[$posizioni_indirizzi[$occorrenze[$k]]] .= 'provincia = "' . db_input($provincia['cod_provincia']) . '", ';
                            $dett_recapito[$posizioni_indirizzi[$occorrenze[$k]]]['provincia'] = $provincia['cod_provincia'];                            
                        }
                    }

                    $occorrenze = array_keys($campi, 'anagrafica*-*nazione'); // Emanuele TO-DO ----- Non inserisce la nazione in anagrafica
                    if (count($occorrenze) > 0) {
                        for ($k = 0; $k < count($occorrenze); $k++) {
                            $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                            if ($risultante != '') {
                                $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                                $query_recapito[$posizioni_indirizzi[$occorrenze[$k]]] .= 'nazione = "' . $risultante . '", ';
                                $dett_recapito[$posizioni_indirizzi[$occorrenze[$k]]]['nazione'] = $risultante;
                            } else {
                                // $query_nazione_predefinita = 'SELECT codice FROM pf_stati WHERE predefinito = 1 LIMIT 0,1';
                                // $nazione_predefinita = mysql_fetch_array(db_query($query_nazione_predefinita));

                                $mysqli->autocommit(FALSE);
                                try{ 

                                    $query_nazione_predefinita = 'SELECT codice FROM pf_stati WHERE predefinito = 1 LIMIT 0,1';
                                    $selezione_nazione_predefinita = $mysqli->prepare($query_nazione_predefinita);            
                                    
                                    $selezione_nazione_predefinita->execute();
                                    $ris_selezione_nazione_predefinita = $selezione_nazione_predefinita->get_result();
                                    $nazione_predefinita = $ris_selezione_nazione_predefinita->fetch_assoc();
                                                        
                                    $mysqli->commit();  

                                }catch (mysqli_sql_exception $e){
                                    echo 'ERRORE SELEZIONE CODICE NAZIONE: ' . $e->getMessage() . '<br>';
                                    $mysqli->rollback();
                                };
                                $mysqli->autocommit(TRUE);
                                $selezione_nazione_predefinita->close();

                                $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $nazione_predefinita['codice'];
                                $query_recapito[$posizioni_indirizzi[$occorrenze[$k]]] .= 'nazione = "' . db_input($nazione_predefinita['codice']) . '", ';
                                $dett_recapito[$posizioni_indirizzi[$occorrenze[$k]]]['nazione'] = $nazione_predefinita['codice'];
                            }
                        }
                    } else {
                        // Conto le occorrenze dell'indirizzo e imposto la nazione di default
                        $occorrenze = array_keys($campi, 'anagrafica*-*indirizzo');

                        for ($k = 0; $k < count($occorrenze); $k++) {
                            // $query_nazione_predefinita = 'SELECT codice FROM pf_stati WHERE predefinito = 1 LIMIT 0,1';
                            // $nazione_predefinita = mysql_fetch_array(db_query($query_nazione_predefinita));

                            $mysqli->autocommit(FALSE);
                            try{ 

                                $query_nazione_predefinita = 'SELECT codice FROM pf_stati WHERE predefinito = 1 LIMIT 0,1';
                                $selezione_nazione_predefinita = $mysqli->prepare($query_nazione_predefinita);            
                                
                                $selezione_nazione_predefinita->execute();
                                $ris_selezione_nazione_predefinita = $selezione_nazione_predefinita->get_result();
                                $nazione_predefinita = $ris_selezione_nazione_predefinita->fetch_assoc();
                                                    
                                $mysqli->commit();  

                            }catch (mysqli_sql_exception $e){
                                echo 'ERRORE SELEZIONE CODICE NAZIONE: ' . $e->getMessage() . '<br>';
                                $mysqli->rollback();
                            };
                            $mysqli->autocommit(TRUE);
                            $selezione_nazione_predefinita->close();

                            $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $nazione_predefinita['codice'];
                            $query_recapito[$posizioni_indirizzi[$occorrenze[$k]]] .= 'nazione = "' . db_input($nazione_predefinita['codice']) . '", ';
                            $dett_recapito[$posizioni_indirizzi[$occorrenze[$k]]]['nazione'] = $nazione_predefinita['codice'];
                        }
                    }
                }

                // RECAPITO TELEFONICO
                /*
						anagrafica*-*tipo_recapito
						anagrafica*-*recapito
						anagrafica*-*recapito_corrispondenza
						anagrafica*-*recapito_principale
						anagrafica*-*recapito_attivo
						anagrafica*-*note_recapito

						global $rec_predefinito;
						global $rec_corrispondenza;
						global $rec_attivo;
						global $rec_tipo;
						*/

                $esistenza_recapito = false;

                $occorrenze = array_keys($campi, 'anagrafica*-*recapito');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    if (trim($risultante) != '') {
                        $esistenza_recapito = true;

                        $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;

                        if ($risultante != '' && str_replace(' ', '', $risultante) != '')
                            $query_recapito_telefonico[$posizioni[$occorrenze[$k]]] .= 'indirizzo = "' . $risultante . '", ';
                        else
                            $query_recapito_telefonico[$posizioni[$occorrenze[$k]]] .= '';
                        $dett_recapito_telefonico[$posizioni[$occorrenze[$k]]]['indirizzo'] = $risultante;

                        if ($fonte > 0) {
                            $query_recapito_telefonico[$posizioni[$occorrenze[$k]]] .= 'fonte = "' . db_input($fonte) . '", ';
                            $dett_recapito_telefonico[$posizioni[$occorrenze[$k]]]['fonte'] = $fonte;
                        }

                        if ($data_validita != '') {
                            $query_recapito_telefonico[$posizioni[$occorrenze[$k]]] .= 'valido_da = "' . db_input($data_validita) . '", ';
                            $dett_recapito_telefonico[$posizioni[$occorrenze[$k]]]['valido_da'] = $data_validita;
                        }

                        $occorrenze_tipo_recapito = array_keys($campi, 'anagrafica*-*tipo_recapito');
                        // SE NON è IMPOSTATO UN RECAPITO PREDEFINITO e SE LA TIPOLOGIA NON è DEFINITA TRAMITE DECODIFICA

                        if ($rec_tipo <= 0 && count($occorrenze_tipo_recapito) <= 0 && $tipi_recapito_telefonico[$occorrenze[$k]] >= 0 && $tipi_recapito_telefonico[$occorrenze[$k]] != '' && $tipi_recapito_telefonico[$occorrenze[$k]] < 9999) {
                            $query_recapito_telefonico[$posizioni[$occorrenze[$k]]] .= 'tipo_recapito_telefonico = "' . db_input($tipi_recapito_telefonico[$occorrenze[$k]]) . '", ';
                            $dett_recapito_telefonico[$posizioni[$occorrenze[$k]]]['tipo_recapito_telefonico'] = $tipi_recapito_telefonico[$occorrenze[$k]];
                        }
                    }

                }

                if ($esistenza_recapito) {
                    $tipo_recapito_telefonico_debitore = false;
                    $occorrenze = array_keys($campi, 'anagrafica*-*tipo_recapito');
                    if ($rec_tipo > 0 || count($occorrenze) > 0) {
                        $tipo_recapito_telefonico_debitore = true;

                        if ($ind_tipo > 0) {
                            $query_recapito_telefonico[0] .= 'tipo_recapito = "' . db_input($ind_tipo) . '", ';
                            $dett_recapito_telefonico[0]['tipo_recapito'] = $ind_tipo;
                        }
                    }

                    for ($k = 0; $k < count($occorrenze); $k++) {
                        if ($rec_tipo > 0) {
                            $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $rec_tipo;

                            $query_recapito_telefonico[$posizioni[$occorrenze[$k]]] .= 'tipo_recapito_telefonico = "' . db_input($rec_tipo) . '", ';
                            $dett_recapito_telefonico[$posizioni[$occorrenze[$k]]]['tipo_recapito_telefonico'] = $rec_tipo;
                        } else {

                            $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);
                            $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;


                            if ($risultante != '') {
                                // $query_seleziona_tipo_indirizzo = 'SELECT id_remida 
								// 											FROM decodifiche_dettagli DD
								// 												LEFT JOIN decodifiche D ON DD.id_decodifica = D.id
								// 											WHERE id_mandante = "' . db_input($id_mandante) . '"
								// 											AND D.tipo = "recapito"
								// 											AND (codice_uno = "' . $risultante . '"
								// 											OR codice_due = "' . $risultante . '")';
                                // $tipo_indirizzo = mysql_fetch_array(db_query($query_seleziona_tipo_indirizzo));

                                $mysqli->autocommit(FALSE);
                                try{
                                    $query_seleziona_tipo_indirizzo = 'SELECT id_remida 
                                                                            FROM decodifiche_dettagli DD
                                                                                LEFT JOIN decodifiche D ON DD.id_decodifica = D.id
                                                                            WHERE id_mandante = ?
                                                                            AND D.tipo = "recapito"
                                                                            AND (codice_uno = ?
                                                                            OR codice_due = ?)';
                                    $selezione_tipo_indirizzo = $mysqli->prepare($query_seleziona_tipo_indirizzo);            
                                    $selezione_tipo_indirizzo->bind_param("iss", $id_mandante, $risultante, $risultante);          
                                    
                                    $selezione_tipo_indirizzo->execute();
                                    $ris_selezione_tipo_indirizzo = $selezione_tipo_indirizzo->get_result();
                                    $tipo_indirizzo = $ris_selezione_tipo_indirizzo->fetch_assoc();
                                                        
                                    $mysqli->commit();  

                                }catch (mysqli_sql_exception $e){
                                    echo 'ERRORE SELEZIONE TIPO INDIRIZZO DA DECODIFICHE DETTAGLI: ' . $e->getMessage() . '<br>';
                                    $mysqli->rollback();
                                };
                                $mysqli->autocommit(TRUE);
                                $selezione_tipo_indirizzo->close();

                                // TODO: DA VERIFICARE
                                $query_recapito_telefonico[$posizioni[$occorrenze[$k]]] .= 'tipo_recapito_telefonico = "' . db_input($tipo_indirizzo['id_remida']) . '", ';
                                $dett_recapito_telefonico[$posizioni[$occorrenze[$k]]]['tipo_recapito_telefonico'] = $tipo_indirizzo['id_remida'];
                            }
                        }
                    }

                    if ($rec_corrispondenza > 0) {
                        for ($sk = 0; $sk < 50; $sk++) {
                            if (isset($query_recapito_telefonico[$sk])) {
                                $query_recapito_telefonico[$sk] .= 'usa_per_invio = "' . db_input($rec_corrispondenza) . '", ';
                                $dett_recapito_telefonico[$sk]['usa_per_invio'] = $rec_corrispondenza;
                            }
                        }
                    } else {
                        $occorrenze = array_keys($campi, 'anagrafica*-*recapito_corrispondenza');
                        if (count($occorrenze) > 0) {
                            for ($k = 0; $k < count($occorrenze); $k++) {
                                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                                if ($risultante != '') {
                                    $query_recapito_telefonico[$posizioni[$occorrenze[$k]]] .= 'usa_per_invio = "' . $risultante . '", ';
                                    $dett_recapito_telefonico[$posizioni[$occorrenze[$k]]]['usa_per_invio'] = $risultante;
                                }
                            }
                        } else {
                            for ($sk = 0; $sk < 50; $sk++) {
                                if (isset($query_recapito_telefonico[$sk])) {
                                    $query_recapito_telefonico[$sk] .= 'usa_per_invio = "0", ';
                                    $dett_recapito_telefonico[$sk]['usa_per_invio'] = 0;
                                }
                            }
                        }
                    }

                    if ($rec_predefinito > 0) {
                        for ($sk = 0; $sk < 50; $sk++) {
                            if (isset($query_recapito_telefonico[$sk])) {
                                $query_recapito_telefonico[$sk] .= 'principale = "' . db_input($rec_predefinito) . '", ';
                                $dett_recapito_telefonico[$sk]['principale'] = $rec_predefinito;
                            }
                        }

                        if ($rec_predefinito == 1)
                            $recapito_predefinito = 1;
                    } else {
                        $occorrenze = array_keys($campi, 'anagrafica*-*recapito_principale');
                        if (count($occorrenze) > 0) {
                            for ($k = 0; $k < count($occorrenze); $k++) {
                                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                                if ($risultante != '') {
                                    $query_recapito_telefonico[$posizioni[$occorrenze[$k]]] .= 'principale = "' . $risultante . '", ';
                                    $dett_recapito_telefonico[$posizioni[$occorrenze[$k]]]['principale'] = $risultante;

                                    if ($rec_predefinito == 1)
                                        $recapito_predefinito = 1;
                                }
                            }
                        } else {
                            for ($sk = 0; $sk < 50; $sk++) {
                                if (isset($query_recapito_telefonico[$sk])) {
                                    $query_recapito_telefonico[$sk] .= 'principale = "0", ';
                                    $dett_recapito_telefonico[$sk]['principale'] = 0;
                                }
                            }
                        }
                    }

                    if ($rec_attivo > 0) {
                        for ($sk = 0; $sk < 50; $sk++) {
                            if (isset($query_recapito_telefonico[$sk])) {
                                $query_recapito_telefonico[$sk] .= 'attivo = "' . db_input($rec_attivo) . '", ';
                                $dett_recapito_telefonico[$sk]['attivo'] = $rec_attivo;
                            }
                        }
                    } else {
                        $occorrenze = array_keys($campi, 'anagrafica*-*recapito_attivo');
                        if (count($occorrenze) > 0) {
                            for ($k = 0; $k < count($occorrenze); $k++) {
                                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                                if ($risultante != '') {
                                    $query_recapito_telefonico[$posizioni[$occorrenze[$k]]] .= 'attivo = "' . $risultante . '", ';
                                    $dett_recapito_telefonico[$posizioni[$occorrenze[$k]]]['attivo'] = $risultante;
                                }
                            }
                        } else {
                            for ($sk = 0; $sk < 50; $sk++) {
                                if (isset($query_recapito_telefonico[$sk])) {
                                    $query_recapito_telefonico[$sk] .= 'attivo = "0", ';
                                    $dett_recapito_telefonico[$sk]['attivo'] = 0;
                                }
                            }
                        }
                    }

                    $occorrenze = array_keys($campi, 'anagrafica*-*note_recapito');
                    for ($k = 0; $k < count($occorrenze); $k++) {
                        $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                        if ($risultante != '') {
                            $utente[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;

                            $query_recapito_telefonico[$posizioni[$occorrenze[$k]]] .= 'note = "' . $risultante . '", ';
                            $dett_recapito_telefonico[$posizioni[$occorrenze[$k]]]['note'] = $risultante;
                        }
                    }
                }
            }

        }    //echo 'ANAGRAFICA: '; print_r($utente); echo '<br><br>';


        $esistenza_utente = false;

        if ($id_utente > 0) {
            // $query_esistenza_id_utente = "SELECT * FROM utente WHERE id_utente = " . $id_utente;
            // $result_esistenza_id_utente = db_query($query_esistenza_id_utente);
            
            $mysqli->autocommit(FALSE);
            try{ 
                
                $query_esistenza_id_utente = 'SELECT * FROM utente WHERE id_utente = ?';
                $selezione_esistenza_id_utente = $mysqli->prepare($query_esistenza_id_utente);            
                $selezione_esistenza_id_utente->bind_param("i", $id_utente);          
                
                $selezione_esistenza_id_utente->execute();
                $result_esistenza_id_utente = $selezione_esistenza_id_utente->get_result();
                // $result_esistenza_id_utente->fetch_assoc();
                
                $mysqli->commit();  
                
            }catch (mysqli_sql_exception $e){
                echo 'ERRORE SELEZIONE VERIFICA ESISTENZA UTENTE: ' . $e->getMessage() . '<br>';
                $mysqli->rollback();
            };
            $mysqli->autocommit(TRUE);
            $selezione_esistenza_id_utente->close();
        }
        
        if ($debug) {
            echo "<br>UT ";
            print_r($utente);
            echo "<br>UT 2 ";
            print_r($utente1);
            echo "<br>ESISTENZA UTENTE ";
            print_r($result_esistenza_id_utente);
        }

        $queryArray = [];

        if (($utente != $utente1 || count($utente) == 0) && $result_esistenza_id_utente->num_rows > 0) {
            // INSERIMENTO UTENTE
            // $flag_update = 1;
            for ($v = 0; $v < count($query_utente); $v++) {
                // $query_aggiornamento = 'UPDATE utente SET ' . rtrim($query_utente[$v], ', ') . ', valido_da = "' . $data_validita . '" WHERE id_utente = ' . $id_utente;

                // if ($debug) {
                    // print_r($query_aggiornamento);
                    //     echo '<br><br>';
                    // }

                // db_query($query_aggiornamento) or die('ERRORE AGGIORNAMENTO UTENTE: ' . mysql_error());                        
                
                // ------- TEST ARRAY ----------
                $queryArray[] = [
                    'query_update_utente' => 'UPDATE utente SET ' . rtrim($query_utente[$v], ', ') . ', valido_da = ? WHERE id_utente = ?',
                    'params' => [$data_validita, $id_utente],
                    'message' => 'Errore durante l\'aggiornamento dell\'utente.'
                ];
                    
                // --------- TEST TABELLE PROVVISORIE ---------
                    // $mysqli->autocommit(FALSE);
                    // try{ 
                        
                    //     $query_aggiornamento = 'INSERT INTO ed_utente SET ' . rtrim($query_utente[$v], ', ') . ', valido_da = ?, id_acquisizione_utente = ?, flag_update_anagrafica = ?, id_acquisizione = ?';
                    //     $aggiornamento_utente = $mysqli->prepare($query_aggiornamento);            
                    //     $aggiornamento_utente->bind_param("siii",$data_validita, $id_utente, $flag_update, $id_acquisizione);                         
                    //     $aggiornamento_utente->execute();
                                            
                    //     $mysqli->commit();  
    
                    // }catch (mysqli_sql_exception $e){
                    //     echo 'ERRORE INSERIMENTO IN ED_UTENTE: ' . $e->getMessage() . '<br>';
                    //     $mysqli->rollback();
                    // };
                    // $mysqli->autocommit(TRUE);
                    // $aggiornamento_utente->close();
                // -----------------------------------------------

                $dettaglio_utente = mysql_fetch_array(db_query('SELECT * FROM utente WHERE id_utente = "' . db_input($id_utente) . '"'));

                // ------- TEST ARRAY ----------
                $queryArray[] = [
                    'query_select_utente' => 'SELECT * FROM utente WHERE id_utente = ?',
                    'params' => [$id_utente],
                    'message' => 'Errore durante la selezione del dettaglio utente.'
                ];

                // if ($dettaglio_utente['cognome'] != '' && $dettaglio_utente['partita_iva'] != '') echo '<strong>ID UTENTE: ' . $id_utente . ' - ' . $dettaglio_utente['cognome'] . ' (' . $dettaglio_utente['partita_iva'] . ')</strong>';
                // else if ($dettaglio_utente['cognome'] != '' && $dettaglio_utente['codice_fiscale'] != '') echo '<strong>ID UTENTE: ' . $id_utente . ' - ' . $dettaglio_utente['cognome'] . ' (' . $dettaglio_utente['codice_fiscale'] . ')</strong>';

                // echo '<br>';

                // INSERIMENTO DI CREDITORE E DEBITORE NELLA TABELLA DI ACQUISIZIONE
                /*
                        $query_acquisizione = 'INSERT INTO acquisizione_dati
                                                SET id_utente = "'.db_input($id_utente).'",
                                                    riferimento_mandante = "'.db_input($dett_univoco[0]['riferimento']).'",
                                                    data_inserimento = "'.db_input(date('Y-m-d H:i:s')).'"';
                        db_query($query_acquisizione) or die(mysql_error());
						*/
                echo '<strong>UTENTE AGGIORNATO</strong> [ID UTENTE: ' . $id_utente . ']<br>';
            }

            // INSERIMENTO/AGGIORNAMENTO ANAGRAFICA
            if (count($query_anagrafica) > 0) {
                for ($v = 0; $v < count($query_anagrafica); $v++) {
                    if (isset($dett_utente[$v]['codice_fiscale']) || $dett_utente[$v]['codice_fiscale'] != '') {
                        
                        if ($dett_utente['anagrafica'] > 0) { // Emanuele ---- $dett_utente['anagrafica] non è presente nell'array perchè? questo if non si verifica mai? TO-DO
                            // if ($debug) {
                            //     print_r('UPDATE anagrafica SET ' . rtrim($query_anagrafica[$v], ', ') . ' WHERE id_anagrafica = ' . $dettaglio_utente['anagrafica']);
                            //     echo '<br><br>';
                            // }

                            db_query('UPDATE anagrafica SET ' . rtrim($query_anagrafica[$v], ', ') . ' WHERE id_anagrafica = ' . $dettaglio_utente['anagrafica']) or die('ERRORE AGGIORNAMENTO ANAGRAFICA: ' . mysql_error());
                            
                            // ------- TEST ARRAY ----------
                            $queryArray[] = [
                                'query_update_anagrafica' => 'UPDATE anagrafica SET ' . rtrim($query_anagrafica[$v], ', ') . ' WHERE id_anagrafica = ?',
                                'params' => [null],
                                'message' => 'Errore durante l\'aggiornamento dell\'anagrafica.'
                            ];

                            // --------- TEST TABELLE PROVVISORIE ---------
                                // $mysqli->autocommit(FALSE);
                                // try{ 
                                    
                                //     $query_inserimento_anagrafica = 'INSERT INTO ed_anagrafica SET ' . rtrim($query_anagrafica[$v], ', ') . ', e_id_acquisizione_anag = ?, id_acquisizione = ?, flag_update_anagrafica = ?';
                                //     $inserimento_anagrafica = $mysqli->prepare($query_inserimento_anagrafica);            
                                //     $inserimento_anagrafica->bind_param("iii", $dettaglio_utente['anagrafica'],$id_acquisizione, $flag_update);                         
                                //     $inserimento_anagrafica->execute();
                                    
                                //     $mysqli->commit();  
                                    
                                // }catch (mysqli_sql_exception $e){
                                //     echo 'ERRORE INSERIMENTO IN ED ANAGRAFICA: ' . $e->getMessage() . '<br>';
                                //     $mysqli->rollback();
                                // };
                                // $mysqli->autocommit(TRUE);
                                // $inserimento_anagrafica->close();
                            // ---------------------------------------------------------

                            // $dettaglio_anagrafica = mysql_fetch_array(db_query('SELECT * FROM anagrafica WHERE id_anagrafica = "' . $dettaglio_utente['anagrafica'] . '"'));

                            // ------- TEST ARRAY ----------
                            $queryArray[] = [
                                'query_select_anagrafica' => 'SELECT * FROM anagrafica WHERE id_anagrafica = ?',
                                'params' => [null],// Il parametro sarà sostituito con l'ID inserito
                                'message' => 'Errore durante la selezione del dettaglio utente.'
                            ];

                            // echo '<strong>ID ANAGRAFICA: ' . $dettaglio_anagrafica['id_anagrafica'] . '</strong><br>';

                        } else {
                            // if ($debug) {
                            //     print_r('INSERT INTO anagrafica SET ' . rtrim($query_anagrafica[$v], ', '));
                            //     echo '<br><br>';
                            // }

                            // db_query('INSERT INTO anagrafica SET ' . rtrim($query_anagrafica[$v], ', ')) or die('ERRORE CREAZIONE ANAGRAFICA: ' . mysql_error());

                            // $dettaglio_anagrafica = mysql_fetch_array(db_query('SELECT * FROM anagrafica WHERE id_anagrafica = "' . mysql_insert_id() . '"'));


                            // db_query('UPDATE utente SET anagrafica = "' . db_input($dettaglio_anagrafica['id_anagrafica']) . '" WHERE id_utente = "' . db_input($id_utente) . '"') or die('ERRORE AGGIORNAMENTO ANAGRAFICA SU UTENTE: ' . mysql_error());

                            $queryArray[] = [
                                'type' => 'insert',
                                'query_insert_anagrafica' => 'INSERT INTO anagrafica SET ' . rtrim($query_anagrafica[$v], ', '),
                                'params' => [],
                                'message' => 'Errore durante l\'inserimento dell\'anagrafica.',
                                'update_params' => [
                                    'select_anagrafica' => 'id_anagrafica'
                                ]
                            ];
                        
                            $queryArray[] = [
                                'type' => 'select',
                                'query_select_anagrafica' => 'SELECT * FROM anagrafica WHERE id_anagrafica = ?',
                                'params' => [null],
                                'message' => 'Errore durante la selezione dell\'anagrafica.',
                                'update_params' => [
                                    'update_utente_anagrafica' => 'id_anagrafica'
                                ]
                            ];
                        
                            $queryArray[] = [
                                'type' => 'update',
                                'query_update_utente_anagrafica' => 'UPDATE utente SET anagrafica = ? WHERE id_utente = ?',
                                'params' => [null, $id_utente], // Il primo parametro sarà sostituito con la nuova anagrafica inserita
                                'message' => 'Errore durante l\'aggiornamento dell\'utente.'
                            ];

                            // --------- TEST TABELLE PROVVISORIE ---------
                                // $flag_update = 0;

                                // $mysqli->autocommit(FALSE);
                                // try{ 
                                    
                                //     $query_inserimento_anagrafica = 'INSERT INTO ed_anagrafica SET ' . rtrim($query_anagrafica[$v], ', ') . ', e_id_acquisizione_anag = ?, id_acquisizione = ?, flag_update_anagrafica = ?, id_utente = ?';
                                //     $inserimento_anagrafica = $mysqli->prepare($query_inserimento_anagrafica);            
                                //     $inserimento_anagrafica->bind_param("iiii", $dettaglio_utente['anagrafica'],$id_acquisizione, $flag_update, $id_utente);                         
                                //     $inserimento_anagrafica->execute();
                                    
                                //     $mysqli->commit();  
                                    
                                // }catch (mysqli_sql_exception $e){
                                //     echo 'ERRORE INSERIMENTO IN ED ANAGRAFICA: ' . $e->getMessage() . '<br>';
                                //     $mysqli->rollback();
                                // };
                                // $mysqli->autocommit(TRUE);
                                // $inserimento_anagrafica->close();
                            // ---------------------------------------------------
                        }
                    }
                }
            }

            // INSERIMENTO/AGGIORNAMENTO ANAGRAFICA DEBITORE
            if (count($query_anagrafica_2) > 0) {
                for ($v = 0; $v < count($query_anagrafica_2); $v++) {
                    if ($debug) print_r('INSERT INTO debitore SET id_utente = ' . $id_utente . ', ' . rtrim($query_anagrafica_2[$v], ', ') . ' ON DUPLICATE KEY UPDATE ' . rtrim($query_anagrafica_2[$v], ', '));
                    echo '<br><br>';

                    // db_query('INSERT INTO debitore SET id_utente = ' . $id_utente . ', ' . rtrim($query_anagrafica_2[$v], ', ') . ' ON DUPLICATE KEY UPDATE ' . rtrim($query_anagrafica_2[$v], ', ')) or die('ERRORE AGGIORNAMENTO ANAGRAFICA: ' . mysql_error());

                    // ------- TEST ARRAY ----------
                    $queryArray[] = [
                        'query_insert_debitore' => 'INSERT INTO debitore SET id_utente = ?, ' . rtrim($query_anagrafica_2[$v], ', ') . ' ON DUPLICATE KEY UPDATE ' . rtrim($query_anagrafica_2[$v], ', '),
                        'params' => [$id_utente],
                        'message' => 'Errore durante l\'inserimento del debitore.'
                    ];

                    // --------- TEST TABELLE PROVVISORIE ---------                    
                        // $mysqli->autocommit(FALSE);
                        // try{
                        //     $query_inserimento_debitore = $mysqli->prepare('INSERT INTO ed_debitore SET id_acquisizione = ' . $id_acquisizione . ', id_utente = ' . $id_utente . ', ' . rtrim($query_anagrafica_2[$v], ', '). ' ON DUPLICATE KEY UPDATE ' . rtrim($query_anagrafica_2[$v], ', '));
                        //     $query_inserimento_debitore->execute();             

                        //     $mysqli->commit();

                        // }catch (mysqli_sql_exception $e){
                        //     echo 'ERRORE INSERIMENTO IN ED DEBITORE: ' . $e->getMessage() . '<br> <br>';
                        //     $mysqli->rollback();
                        // };                            
                        // $mysqli->autocommit(TRUE);
                        // $query_inserimento_debitore->close();
                    // -------------------------------------------------------------

                    // echo '<strong>ANAGRAFICA DEBITORE AGGIORNATA</strong><br>';
                }
            }

            if ($debug) {
                echo "query recapiti <br>";
                print_r($query_recapito);
            }
            // INSERIMENTO RECAPITI
            $query_recapito = array_values($query_recapito);
            for ($v = 0; $v < count($query_recapito); $v++) {
                if ($query_recapito[$v] != '' && strpos($query_recapito[$v], 'indirizzo = ""') === FALSE && strpos($query_recapito[$v], 'ndirizzo') > 0) {
                    //if(strpos( $query_recapito[$v], 'predefinito = "1"' ) >= 0) {
                    if ($debug) echo 'indirizzo_predefinito = '.$indirizzo_predefinito.'<br>';
                    if ($debug) echo 'ind_predefinito = '.$ind_predefinito.'<br>';
                    if ($debug) echo 'v = '.$v.'<br>';

                    if (($indirizzo_predefinito == 1 || $ind_predefinito == 1) && $v == 0) {
                        if ($debug) echo 'RESET PREDEFINITO PER UTENTE '.$id_utente;
                        if ($debug) echo 'UPDATE recapito SET predefinito = 0 WHERE id_utente = "' . db_input($id_utente) . '"<br>';

                        // db_query('UPDATE recapito SET predefinito = 0 WHERE id_utente = "' . db_input($id_utente) . '"');

                        $queryArray[] = [
                            'query_update_recapito' => 'UPDATE recapito SET predefinito = 0 WHERE id_utente = ?',
                            'params' => [$id_utente],
                            'message' => 'Errore durante l\'aggiornamento del recapito.'
                        ];

                        // --------- TEST TABELLE PROVVISORIE ---------               
                            // $flag_update = 1;

                            // $mysqli->autocommit(FALSE);
                            // try{ 
                                
                            //     $query_inserimento_ed_recapito = 'INSERT INTO ed_recapito SET id_acquisizione = ?, predefinito = 0 , id_utente = ?, e_flag_update_recapito = ?';
                            //     $inserimento_ed_recapito = $mysqli->prepare($query_inserimento_ed_recapito);            
                            //     $inserimento_ed_recapito->bind_param("iii", $id_acquisizione, $id_utente, $flag_update);                       
                            //     $inserimento_ed_recapito->execute();
                                
                            //     $mysqli->commit();  
                            // }catch (mysqli_sql_exception $e){
                            //     echo 'ERRORE INSERIMENTO IN ED RECAPITO FLAG UPDATE A 1: ' . $e->getMessage() . '<br>';
                            //     $mysqli->rollback();
                            // };
                            // $mysqli->autocommit(TRUE);
                            // $inserimento_ed_recapito->close();
                        // -------------------------------------------------------------
                    }

                    if ($sovrascrivi_indirizzo == 1) {
                        if ($debug) print_r('INSERT INTO recapito SET id_utente = "' . db_input($id_utente) . '", ' . rtrim($query_recapito[$v], ', ') . ' ON DUPLICATE KEY UPDATE ' . rtrim($query_recapito[$v], ', '));
                        echo '<br><br>';

                        // $ris = db_query('INSERT INTO recapito SET id_utente = "' . db_input($id_utente) . '", ' . rtrim($query_recapito[$v], ', ') . ' ON DUPLICATE KEY UPDATE ' . rtrim($query_recapito[$v], ', ')) or die('ERRORE AGGIUNTA RECAPITO: ' . mysql_error());

                        $queryArray[] = [
                            'query_insert_recapito' => 'INSERT INTO recapito SET id_utente = ?, ' . rtrim($query_recapito[$v], ', ') . ' ON DUPLICATE KEY UPDATE ' . rtrim($query_recapito[$v], ', '),
                            'params' => [$id_utente],
                            'message' => 'Errore durante l\'inserimento ed aggiornamento sui duplicati del recapito.'
                        ];

                    // --------- TEST TABELLE PROVVISORIE ---------        
                        // $flag_update = 0;
                        // $error_string = 'ERRORE INSERIMENTO IN ED RECAPITO FLAG UPDATE A 0: ';

                        // $mysqli->autocommit(FALSE);
                        // try{
                        //     $query_inserimento_ed_recapito = $mysqli->prepare('INSERT INTO ed_recapito SET id_acquisizione = ?, id_utente = ? , e_flag_update_recapito = ? , ' . rtrim($query_recapito[$v], ', '));
                        //     $query_inserimento_ed_recapito->bind_param("iii", $id_acquisizione, $id_utente, $flag_update);                  

                        //     $query_inserimento_ed_recapito->execute();

                        //     $mysqli->commit();

                        // }catch (mysqli_sql_exception $e){
                        //     echo $error_string . $e->getMessage() . '<br> <br>';
                        //     $mysqli->rollback();
                        // };                            
                        // $mysqli->autocommit(TRUE);
                        // $query_inserimento_ed_recapito->close();
                    // ------------------------------------------------------

                    } else {
                        if ($debug) print_r('INSERT IGNORE INTO recapito SET id_utente = "' . db_input($id_utente) . '", ' . rtrim($query_recapito[$v], ', '));
                        echo '<br><br>';

                        // $ris = db_query('INSERT IGNORE INTO recapito SET id_utente = "' . db_input($id_utente) . '", ' . rtrim($query_recapito[$v], ', ')) or die('ERRORE AGGIUNTA RECAPITO: ' . mysql_error());

                        $queryArray[] = [
                            'query_insert_ignore_recapito' => 'INSERT IGNORE INTO recapito SET id_utente = ?, ' . rtrim($query_recapito[$v], ', '),
                            'params' => [$id_utente],
                            'message' => 'Errore durante l\'inserimento se non ci sono duplicati del recapito.'
                        ];

                        // ----------------- TEST TABELLE PROVVISORIE -----------------------
                        // $flag_update = 2; // ECCEZIONE ------ per insert ignore quando non si deve sovrascrivere in caso di chiave duplicata -----
                        // $error_string = 'ERRORE INSERIMENTO IN ED RECAPITO FLAG UPDATE A 2 [sovrascrivi_indirizzo != 1]: ';

                        // $mysqli->autocommit(FALSE);
                        // try{
                        //     $query_inserimento_ed_recapito = $mysqli->prepare('INSERT INTO ed_recapito SET id_acquisizione = ?, id_utente = ? , e_flag_update_recapito = ? , ' . rtrim($query_recapito[$v], ', '));
                        //     $query_inserimento_ed_recapito->bind_param("iii", $id_acquisizione, $id_utente, $flag_update);                  

                        //     $query_inserimento_ed_recapito->execute();

                        //     $mysqli->commit();

                        // }catch (mysqli_sql_exception $e){
                        //     echo $error_string . $e->getMessage() . '<br> <br>';
                        //     $mysqli->rollback();
                        // };                            
                        // $mysqli->autocommit(TRUE);
                        // $query_inserimento_ed_recapito->close();
                    }
                        // ----------------- TEST TABELLE PROVVISORIE -----------------------

                        // $mysqli->autocommit(FALSE);
                        // try{
                        //     $query_inserimento_ed_recapito = $mysqli->prepare('INSERT INTO ed_recapito SET id_acquisizione = ?, id_utente = ? , e_flag_update_recapito = ? , ' . rtrim($query_recapito[$v], ', '));
                        //     $query_inserimento_ed_recapito->bind_param("iii", $id_acquisizione, $id_utente, $flag_update);                  

                        //     $query_inserimento_ed_recapito->execute();

                        //     $mysqli->commit();

                        // }catch (mysqli_sql_exception $e){
                        //     echo $error_string . $e->getMessage() . '<br> <br>';
                        //     $mysqli->rollback();
                        // };                            
                        // $mysqli->autocommit(TRUE);
                        // $query_inserimento_ed_recapito->close();

                    // if ($mysqli->affected_rows > 0) {

                        // $dettaglio_recapito = mysql_fetch_array(db_query('SELECT * FROM recapito WHERE id_recapito = "' . mysql_insert_id() . '"'));
                        
                    $queryArray[] = [
                        'query_select_recapito' => 'SELECT * FROM recapito WHERE id_recapito = ?',
                        'params' => [null], // Il parametro sarà sostituito con l'ID inserito
                        'message' => 'Errore durante la selezione del recapito.'
                    ];
                        // ----------------- TEST TABELLE PROVVISORIE -----------------------
                            //     $mysqli->autocommit(FALSE);
                            //     try{
                            //         $query_selezione_recapito = $mysqli->prepare('SELECT * FROM recapito WHERE id_recapito =?');
                            //         $query_selezione_recapito->bind_param("i", $last_id_recapito);
                            //         $query_selezione_recapito->execute();

                            //         $selezione_recapito = $query_selezione_recapito->get_result();
                            //         $dettaglio_recapito = $selezione_recapito->fetch_assoc();

                            //         $mysqli->commit();

                            //     }catch (mysqli_sql_exception $e){
                            //         echo 'ERRORE AGGIUNTA RECAPITO: ' . $e->getMessage() . '<br> <br>';
                            //         $mysqli->rollback();
                            //     };                            
                            //     $mysqli->autocommit(TRUE);
                            //     $query_selezione_recapito->close();
                        // ---------------------------------------------------------------------

                    //     echo '<strong>ID RECAPITO: ' . $dettaglio_recapito['id_recapito'] . ' - ' . $dettaglio_recapito['indirizzo'] . ' - ' . $dettaglio_recapito['cap'] . '</strong><br>';
                    // } else {
                    //     echo '<strong>RECAPITO GI&Agrave; ESISTENTE</strong><br>';
                    // }
                }
            }
            if (($indirizzo_predefinito == 1 || $ind_predefinito == 1) && $sovrascrivi_indirizzo != 1) {
                $queryArray[] = [
                    'query_select_max_recapito' => 'SELECT MAX(id_recapito) as id FROM recapito WHERE  id_utente = ?',
                    'params' => [$id_utente], // Il parametro sarà sostituito con l'ID inserito
                    'message' => 'Errore durante la selezione del massimo id recapito.'
                ];

                // $idRecPred = db_fetch_array_assoc_single(db_query('SELECT MAX(id_recapito) as id FROM recapito WHERE  id_utente = "' . db_input($id_utente) . '"'))['id'];

                $queryArray[] = [
                    'query_update_max_recapito' => 'UPDATE recapito SET predefinito = 1 WHERE id_recapito = ?',
                    'params' => [null],
                    'message' => 'Errore durante l\'aggiornamento del recapito.'
                ];

                // db_query('UPDATE recapito SET predefinito = 1 WHERE id_recapito= "' . $idRecPred . '"');
            }

            // INSERIMENTO RECAPITI TELEFONICI E EMAIL
            $query_recapito_telefonico = array_values($query_recapito_telefonico);
            for ($v = 0; $v < count($query_recapito_telefonico); $v++) {
                if ($query_recapito_telefonico[$v] != '' && strpos($query_recapito_telefonico[$v], 'indirizzo = ""') === FALSE && strpos($query_recapito_telefonico[$v], 'tipo_') > 0 && strpos($query_recapito_telefonico[$v], 'ndirizzo') > 0) {
                    //if(strpos( $query_recapito[$v], 'predefinito = "1"' ) >= 0) {
                    if ($recapito_predefinito == 1 || $rec_predefinito == 1) {
                        if ($debug) echo 'UPDATE recapito_telefonico SET principale = 0 WHERE id_utente = "' . db_input($id_utente) . '"<br>';

                        // db_query('UPDATE recapito_telefonico SET principale = 0 WHERE id_utente = "' . db_input($id_utente) . '"');

                        $queryArray[] = [
                            'query_update_recapito_telefonico' => 'UPDATE recapito_telefonico SET principale = 0 WHERE id_utente = ?',
                            'params' => [$id_utente],
                            'message' => 'Errore durante l\'aggiornamento del recapito_telefonico.'
                        ];

                        // ----------------- TEST TABELLE PROVVISORIE -----------------------
                            // $flag_update = 1;

                            // $mysqli->autocommit(FALSE);
                            // try{ 
                                
                            //     $query_inserimento_ed_recapito_telefonico = 'INSERT INTO ed_recapito_telefonico SET id_acquisizione = ?, principale = 0 , id_utente = ?, e_flag_update_recapito_telefonico = ?';
                            //     $inserimento_ed_recapito_telefonico = $mysqli->prepare($query_inserimento_ed_recapito_telefonico);            
                            //     $inserimento_ed_recapito_telefonico->bind_param("iii", $id_acquisizione, $id_utente, $flag_update);                       
                            //     $inserimento_ed_recapito_telefonico->execute();
                                
                            //     $mysqli->commit();  
                            // }catch (mysqli_sql_exception $e){
                            //     echo 'ERRORE INSERIMENTO IN ED recapito_telefonico FLAG UPDATE A 1: ' . $e->getMessage() . '<br>';
                            //     $mysqli->rollback();
                            // };
                            // $mysqli->autocommit(TRUE);
                            // $inserimento_ed_recapito_telefonico->close();
                        // ---------------------------------------------------------------
                    }

                    if ($sovrascrivi_recapito == 1) {
                        //LA SOVRASCRITTURA DELLA FONTE FUNZIONA SOLO SE LA FONTE DA IMPOSTARE NON E' RINTRACCIO.
                        $queryUpdateRecapito = str_replace('fonte = "11",', '', $query_recapito_telefonico[$v]);

                        if ($debug) print_r('INSERT INTO recapito_telefonico SET id_utente = "' . db_input($id_utente) . '", ' . rtrim($query_recapito_telefonico[$v], ', ') . ' ON DUPLICATE KEY UPDATE ' . rtrim($queryUpdateRecapito, ', '));
                        echo '<br><br>';

                        // ----------------- TEST TABELLE PROVVISORIE -----------------------
                        // $flag_update = 0;
                        // $error_string = 'ERRORE INSERIMENTO IN ED RECAPITO TELEFONICO FLAG UPDATE A 0: ';

                        $queryArray[] = [
                            'query_insert_recapito_telefonico' => 'INSERT INTO recapito_telefonico SET id_utente = ?, ' . rtrim($query_recapito_telefonico[$v], ', ') . ' ON DUPLICATE KEY UPDATE ' . rtrim($queryUpdateRecapito, ', '),
                            'params' => [$id_utente],
                            'message' => 'Errore durante l\'inserimento ed aggiornamento sui duplicati del recapito.'
                        ];

                        // db_query('INSERT INTO recapito_telefonico SET id_utente = "' . db_input($id_utente) . '", ' . rtrim($query_recapito_telefonico[$v], ', ') . ' ON DUPLICATE KEY UPDATE ' . rtrim($queryUpdateRecapito, ', ')) or die('ERRORE AGGIUNTA RECAPITO TELEFONICO: ' . mysql_error());
                    } else {
                        if ($debug) print_r('INSERT IGNORE INTO recapito_telefonico SET id_utente = "' . db_input($id_utente) . '", ' . rtrim($query_recapito_telefonico[$v], ', '));
                        echo '<br><br>';

                        // ----------------- TEST TABELLE PROVVISORIE -----------------------
                        // $flag_update = 2; // ECCEZIONE ------ per insert ignore quando non si deve sovrascrivere in caso di chiave duplicata -----
                        // $error_string = 'ERRORE INSERIMENTO IN ED RECAPITO TELEFONICO FLAG UPDATE A 2 [sovrascrivi_indirizzo != 1]: ';

                        $queryArray[] = [
                            'query_insert_ignore_recapito_telefonico' => 'INSERT IGNORE INTO recapito_telefonico SET id_utente = ?, ' . rtrim($query_recapito_telefonico[$v], ', '),
                            'params' => [$id_utente],
                            'message' => 'Errore durante l\'inserimento se non ci sono duplicati del recapito.'
                        ];

                        // db_query('INSERT IGNORE INTO recapito_telefonico SET id_utente = "' . db_input($id_utente) . '", ' . rtrim($query_recapito_telefonico[$v], ', ')) or die('ERRORE AGGIUNTA RECAPITO TELEFONICO: ' . mysql_error());

                    }

                        // ----------------- TEST TABELLE PROVVISORIE -----------------------
                        // $mysqli->autocommit(FALSE);
                        // try{
                        //     $query_inserimento_ed_recapito_telefonico = $mysqli->prepare('INSERT INTO ed_recapito_telefonico SET id_acquisizione = ?, id_utente = ? , e_flag_update_recapito_telefonico = ? , ' . rtrim($query_recapito_telefonico[$v], ', '));
                        //     $query_inserimento_ed_recapito_telefonico->bind_param("iii", $id_acquisizione, $id_utente, $flag_update);                  

                        //     $query_inserimento_ed_recapito_telefonico->execute();

                        //     $mysqli->commit();

                        // }catch (mysqli_sql_exception $e){
                        //     echo $error_string . $e->getMessage() . '<br> <br>';
                        //     $mysqli->rollback();
                        // };                            
                        // $mysqli->autocommit(TRUE);
                        // $query_inserimento_ed_recapito_telefonico->close();


                    // $dettaglio_recapito = mysql_fetch_array(db_query('SELECT * FROM recapito_telefonico WHERE id_recapito_telefono = "' . mysql_insert_id() . '"'));

                    $queryArray[] = [
                        'query_select_recapito_telefonico' => 'SELECT * FROM recapito_telefonico WHERE id_recapito_telefono = ?',
                        'params' => [null], // Il parametro sarà sostituito con l'ID inserito
                        'message' => 'Errore durante la selezione del recapito_telefonico.'
                    ];

                    echo 'Tipo di dato di query_select_recapito_telefonico: ' . gettype($queryArray[count($queryArray) - 1]['query_select_recapito_telefonico']) . PHP_EOL;
                    echo 'Valore di query_select_recapito_telefonico: ' . $queryArray[count($queryArray) - 1]['query_select_recapito_telefonico'] . PHP_EOL;

                    // Verifica duplicati recapito
                    // if (substr($dettaglio_recapito['indirizzo'], 0, 3) == '+39') {
                    //     $queryVerificaDuplicati = "SELECT * 
                    //                                 FROM recapito_telefonico 
                    //                                 WHERE id_recapito_telefono <> '" . $dettaglio_recapito['id_recapito_telefono'] . "'
                    //                                 AND id_utente = '" . $dettaglio_recapito['id_utente'] . "'
                    //                                 AND indirizzo = '" . db_input(substr($dettaglio_recapito['indirizzo'], 3)) . "'";
                    // } else {
                    //     $queryVerificaDuplicati = "SELECT * 
                    //                                 FROM recapito_telefonico 
                    //                                 WHERE id_recapito_telefono <> '" . $dettaglio_recapito['id_recapito_telefono'] . "'
                    //                                     AND id_utente = '" . $dettaglio_recapito['id_utente'] . "'
                    //                                     AND indirizzo = '" . db_input($dettaglio_recapito['indirizzo']) . "'";
                    // }

                    $queryArray[] = [
                        'query_select_verifica_duplicati' => "SELECT * 
                                                    FROM recapito_telefonico 
                                                    WHERE id_recapito_telefono <> ?
                                                    AND id_utente = ?
                                                    AND indirizzo = ?",
                        'params' => [null, null, null],
                        'message' => 'Errore durante la selezione della verifica_duplicati.'
                    ];
                    // $queryArray[] = [
                    //     'query_select_recapito_telefonico' => 'SELECT * FROM recapito_telefonico WHERE id_recapito_telefono = ?',
                    //     'params' => [null, null, null], // Il parametro sarà sostituito con l'ID inserito
                    //     'message' => 'Errore durante la selezione del recapito_telefonico.'
                    // ];

                    // $risVerificaDuplicati = db_query($queryVerificaDuplicati);
                    // if (db_num_rows($risVerificaDuplicati) > 0) {
                        // $recapitiDuplicati = db_fetch_array_assoc($risVerificaDuplicati);

                        // imposto i flag impostati sul nuovo recapito anche su quelli duplicati
                        // foreach ($recapitiDuplicati as $recapitoDuplicato) {
                        //     $querySetProprietaRecapitoDuplicato = "UPDATE recapito_telefonico
                        //                                             SET usa_per_invio = '" . db_input($dettaglio_recapito['usa_per_invio']) . "',
                        //                                                 attivo = '" . db_input($dettaglio_recapito['attivo']) . "',
                        //                                                 principale = '" . db_input($dettaglio_recapito['principale']) . "'
                        //                                             WHERE id_recapito_telefono = '" . $recapitoDuplicato['id_recapito_telefono'] . "'";
                        //     db_query($querySetProprietaRecapitoDuplicato);
                        //     echo '<strong>RECAPITO DUPLICATO: ' . $recapitoDuplicato['id_recapito_telefono'] . ' - ' . $dettaglio_recapito['indirizzo'] . '</strong><br>';
                        // }

                        // cancella il recapito inserito
                        // $querySetProprietaRecapitoDuplicato = "DELETE FROM recapito_telefonico 
                        //                                             WHERE id_recapito_telefono = '" . $dettaglio_recapito['id_recapito_telefono'] . "'";
                        // db_query($querySetProprietaRecapitoDuplicato);

                        $queryArray[] = [
                            'query_delete_recapito_telefonico_duplicato' => "DELETE FROM recapito_telefonico WHERE id_recapito_telefono = ?",
                            'params' => [null],
                            'message' => 'Errore durante la eliminazione del recapito telefonico duplicato.'
                        ];

                    //     echo '<strong>NUOVO RECAPITO NON ACQUISITO - RECAPITO GI&Agrave; ESISTENTE.</strong><br>';
                    // } else {
                    //     echo '<strong>ID RECAPITO: ' . $dettaglio_recapito['id_recapito_telefono'] . ' - ' . $dettaglio_recapito['indirizzo'] . '</strong><br>';
                    // }
                }
            }
            if (($recapito_predefinito == 1 || $rec_predefinito == 1) && $sovrascrivi_recapito != 1) {
                // $idRecTelPred = db_fetch_array_assoc_single(db_query('SELECT MAX(id_recapito_telefono) as id FROM recapito_telefonico WHERE  id_utente = "' . db_input($id_utente) . '"'))['id'];
                // db_query('UPDATE recapito_telefonico SET principale = 1 WHERE id_recapito_telefono= "' . $idRecTelPred . '"');

                $queryArray[] = [
                    'query_select_max_recapito_telefonico' => "SELECT MAX(id_recapito_telefono) as id FROM recapito_telefonico WHERE id_utente = ?",
                    'params' => [$id_utente],
                    'message' => 'Errore durante la selezione del massimo id del recapito telefonico.'
                ];

                $queryArray[] = [
                    'query_update_max_recapito_telefonico' => 'UPDATE recapito_telefonico SET principale = 1 WHERE id_recapito_telefono = ?',
                    'params' => [null],
                    'message' => 'Errore durante l\'aggiornamento del recapito_telefonico.'
                ];
            }
        } else if (count($utente) != 0) {
            echo '<strong>COME PRECEDENTE</strong> - ID DEBITORE: <strong>' . $id_utente . '</strong><br>';
        } else {
            echo '<strong>NON SETTATO</strong><br>';
        }

        $serializedArray = serialize($queryArray);
        file_put_contents('test_emanuele_array_queries_contatti.php', $serializedArray);
        print_r($queryArray);

        $utente1 = $utente;
        ?>

        <div class="row" style="margin-bottom: 7px;">
            <div class="col-md-12">
                <hr>
            </div>
        </div>

        <?php
    }
}

?>

<!DOCTYPE html>
<!--
Name: New Management Interface Project - Responsive Admin Dashboard build with Twitter Bootstrap 3.2.0
Version: 2.0
Author: SolunicaNET
-->
<!--[if IE 8]>
<html lang="it" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="it" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="it" class="no-js">
<!--<![endif]-->

<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title>ReMida | Gestionale</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <meta name="MobileOptimized" content="320">

    <?php require_once('include/stylesheets.php'); ?>

    <style>
        .bootstrap-switch .bootstrap-switch-container {
            max-height: 32px !important;
        }
    </style>

    <?php //require_once('favicon.php'); ?>

</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="<?php echo $header_style . ' ' . $sidebar_style; ?>">
<!-- BEGIN HEADER -->
<?php require_once('elements/header.php') ?>
<!-- END HEADER -->
<div class="clearfix">
</div>

<!-- BEGIN CONTAINER -->
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <?php
    if ($sidebar_text == 1)
        require_once('elements/sidebar_new.php');
    else
        require_once('elements/sidebar.php');
    ?>
    <!-- END SIDEBAR -->

    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <div class="page-content">

            <!-- END CONTAINER -->
            <div style="position: fixed; width: 100%; height: 100%; display: none; top: 0px; left: 0px; background-color: rgba(0, 0, 0, 0.8); z-index: 100000000 ! important;"
                 id="loader1" align="center">
                <span style="position:relative;top:50%;margin-top:-45px; color:#FFF"/><b>ESECUZIONE EVENTI PER
                    INSERIMENTO PRATICHE... ATTENDERE</b></span>
            </div>

            <!-- LOADER -->


            <!-- APERTURA DELLE MODAL AJAX -->
            <div id="ajax-modal" class="modal container fade" tabindex="-1" style="height: 98vh"></div>
            <!-- FINE MODAL AJAX -->

            <!-- BEGIN STYLE CUSTOMIZER -->
            <?php require_once('elements/theme_customizer.php'); ?>
            <!-- END BEGIN STYLE CUSTOMIZER -->
            <!-- BEGIN PAGE HEADER-->
            <h3 class="page-title">
                Acquisizione Contatti
            </h3>
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <i class="fa fa-home"></i>
                        <a href="index.php">Home</a>
                        <i class="fa fa-angle-right"></i>
                    </li>
                    <li>
                        <a href="#">Acquisizione Contatti</a>
                    </li>
                </ul>
            </div>
            <!-- END PAGE HEADER-->
            <div class="page-toolbar">
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="tabbable tabbable-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_0" data-toggle="tab">Acquisizione Contatti</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_0">
                                <form name="formElementi" id="form_sample_22" method="POST"
                                      enctype="multipart/form-data" onSubmit="verifyCheckboxes()">
                                    <input type="hidden" name="test" value="1">
                                    <div class="form-body">
                                        <?php
                                        if (isset($_POST['test']) && $_POST['test'] != '') {
                                            if (isset($_POST['rigaPartenza']) && $_POST['rigaPartenza'] != '') {
                                                $rigaPartenza = $_POST['rigaPartenza'];
                                                $rigaPartenzaNonExcel = $_POST['rigaPartenza'] - 1;
                                            } else {
                                                $rigaPartenza = 1;
                                                $rigaPartenzaNonExcel = 0;
                                            }

                                            $delimiter = $_POST['delimitatore'];

                                            $timestamp = date('YmdHis');

                                            $file = $_FILES['file'];

                                            $campi = $_POST['campo'];
                                            $posizioni = $_POST['posizione'];
                                            $posizioni_indirizzi = $_POST['posizione_indirizzo'];

                                            $rec_predefiniti = $_POST['rec_predefinito'];
                                            $tipi_indirizzo = $_POST['address_type'];
                                            $tipi_recapito_telefonico = $_POST['rec_type'];
                                            /*												$tipi_anagrafica = $_POST['linked_type'];*/

                                            $nome_file = explode('.', $file["name"]);
                                            $posizioneLast = count($nome_file) - 1;
                                            $estensione = $nome_file[$posizioneLast];

                                            $success = false;
                                            $error_type = 0;

                                            if ($file['type'] == 'application/vnd.ms-excel' || $file['type'] == 'text/plain' || strtolower($estensione) == 'csv' || strtolower($estensione) == 'xlsx') {
                                                if ($file['error'] == UPLOAD_ERR_OK and is_uploaded_file($file['tmp_name'])) {
                                                    move_uploaded_file($file["tmp_name"], "assets/file_acquisiti/" . $timestamp . '.' . $estensione);
                                                    $success = true;
                                                } else {
                                                    $success = false;
                                                    $error_type = 2;
                                                }
                                            } else {
                                                $success = false;
                                                $error_type = 3;
                                            }

                                            ?>
                                            <div class="portlet">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-edit"></i>Acquisizione dati da file
                                                    </div>
                                                    <div class="tools">
                                                        <a href="javascript:;" class="collapse">
                                                        </a>
                                                        <a href="#portlet-config" data-toggle="modal"
                                                           class="config info"></a>
                                                        <!--<a href="javascript:;" class="remove">
                                                                </a>-->
                                                    </div>
                                                </div>
                                                <?php
                                                if ($success) {
                                                    ?>
                                                    <!-- ESECUZIONE EVENTI AUTOMATICI -->
                                                    <!-- NON SO DOVE E' IL FILE LOCALE -->
                                                    <script src="assets/plugins/jquery-1.11.0.min.js"></script>
                                                    <script src="assets/scripts/event_exec.js"></script>
                                                    <!-- /ESECUZIONE EVENTI AUTOMATICI -->

                                                    <!-- SULLA ESECUZIONE DI EVENTI -->
                                                    <script>$('#loader1').show();</script>

                                                    <div class="portlet-body">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                Tipologia di File Importato:
                                                                <strong>
                                                                    <?php
                                                                    switch ($_POST['tipo_file']) {
                                                                        case 1:
                                                                            echo 'Excel';
                                                                            break;
                                                                        case 2:
                                                                            echo 'Carattere separatore';
                                                                            break;
                                                                        case 3:
                                                                            echo 'Tabulazione';
                                                                            break;
                                                                        case 4:
                                                                            echo 'Lunghezza Fissa';
                                                                            break;
                                                                    }
                                                                    ?>
                                                                </strong>
                                                            </div>
                                                            <div class="col-md-3">
                                                                File Importato:
                                                                <strong>
                                                                    <?php
                                                                    echo $file["name"];
                                                                    ?>
                                                                </strong>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12" style="margin-bottom: 7px;">
                                                                <hr>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <?php
                                                                if ($debug) {
                                                                    print_r($_POST);
                                                                    echo '<br>';
                                                                }

                                                                $inputFileName = 'assets/file_acquisiti/' . $timestamp . '.' . $estensione;

                                                                $ind_predefinito = (isset($_POST['ind_predefinito']) && $_POST['ind_predefinito'] != '') ? $_POST['ind_predefinito'] : '0';
                                                                $ind_corrispondenza = (isset($_POST['ind_corrispondenza']) && $_POST['ind_corrispondenza'] != '') ? $_POST['ind_corrispondenza'] : '0';
                                                                $ind_corrispondenza_predefinito = (isset($_POST['ind_corrispondenza_predefinito']) && $_POST['ind_corrispondenza_predefinito'] != '') ? $_POST['ind_corrispondenza_predefinito'] : '0';
                                                                $ind_attivo = (isset($_POST['ind_primario_attivo']) && $_POST['ind_primario_attivo'] != '') ? $_POST['ind_primario_attivo'] : '0';
                                                                $ind_tipo = (isset($_POST['tipo_indirizzo_predefinito']) && $_POST['tipo_indirizzo_predefinito'] != '') ? $_POST['tipo_indirizzo_predefinito'] : '0';

                                                                $sovrascrivi_indirizzo = (isset($_POST['sovrascrivi_indirizzo']) && $_POST['sovrascrivi_indirizzo'] != '') ? $_POST['sovrascrivi_indirizzo'] : '0';

                                                                $sovrascrivi_recapito = (isset($_POST['sovrascrivi_recapito']) && $_POST['sovrascrivi_recapito'] != '') ? $_POST['sovrascrivi_recapito'] : '0';

                                                                $rec_predefinito = (isset($_POST['rec_predefinito']) && $_POST['rec_predefinito'] != '') ? $_POST['rec_predefinito'] : '0';

                                                                $rec_corrispondenza = (isset($_POST['rec_invio_predefinito']) && $_POST['rec_invio_predefinito'] != '') ? $_POST['rec_invio_predefinito'] : '0';

                                                                $rec_attivo = (isset($_POST['rec_primario_attivo']) && $_POST['rec_primario_attivo'] != '') ? $_POST['rec_primario_attivo'] : '0';
                                                                $rec_tipo = (isset($_POST['tipo_recapito_predefinito']) && $_POST['tipo_recapito_predefinito'] != '') ? $_POST['tipo_recapito_predefinito'] : '0';

                                                                $fonte = (isset($_POST['fonte_predefinito']) && $_POST['fonte_predefinito'] != '') ? $_POST['fonte_predefinito'] : '2';
                                                                $data_validita = (isset($_POST['fixed_data_validita']) && $_POST['fixed_data_validita'] != '') ? converti_data_meno($_POST['fixed_data_validita']) : date('Y-m-d');

                                                                $evento_strutturato_predefinito_indirizzi = (isset($_POST['evento_strutturato_predefinito_indirizzi']) && $_POST['evento_strutturato_predefinito_indirizzi'] != '') ? $_POST['evento_strutturato_predefinito_indirizzi'] : '';
                                                                $evento_strutturato_predefinito_recapiti = (isset($_POST['evento_strutturato_predefinito_recapiti']) && $_POST['evento_strutturato_predefinito_recapiti'] != '') ? $_POST['evento_strutturato_predefinito_recapiti'] : '';

                                                                $stato_sostituto = (isset($_POST['stato_sostituto']) && $_POST['stato_sostituto'] != '') ? $_POST['stato_sostituto'] : '';
                                                                $stati_esclusi = (isset($_POST['stati_esclusi']) && $_POST['stati_esclusi'] != '') ? $_POST['stati_esclusi'] : '';

                                                                $utente = array();
                                                                $utente1 = array();
                                                                $eventi_strutturati = array();
                                                                ?>

                                                                INSERIMENTO DEI DATI PER CONTO DI
                                                                <strong>
                                                                    <?php
                                                                    $mandante = mysql_fetch_array(db_query('SELECT * FROM utente WHERE id_utente = "' . $_POST['mandante'] . '"'));
                                                                    echo $mandante['nome'] . ' ' . $mandante['cognome'];
                                                                    ?>
                                                                </strong>
                                                                <br>

                                                                <?php

                                                                if (strtolower($estensione) == 'xls' || strtolower($estensione) == 'xlsx' || $_POST['tipo_file'] == 1) {

                                                                    //  Read your Excel workbook
                                                                    try {
                                                                        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                                                                        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                                                                        $objPHPExcel = $objReader->load($inputFileName);
                                                                    } catch (Exception $e) {
                                                                        die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
                                                                    }

                                                                    //  Get worksheet dimensions
                                                                    $sheet = $objPHPExcel->getSheet(0);
                                                                    $highestRow = $sheet->getHighestRow();
                                                                    $highestColumn = $sheet->getHighestColumn();
                                                                    //$highestColumn = $sheet->getHighestDataColumn();

                                                                    $id_utente = '';
                                                                    
                                                                    // --------- INSERISCO IN ED_ACQUISIZIONE_FILE OGNI VOLTA CHE VIENE FATTA UN'ACQUISIZIONE ---------------
                                                                    $mysqli->autocommit(FALSE);
                                                                    try{
                                                                        $id_operatore = db_input($_SESSION['user_admin_id']);
                                                                        $id_mandante = db_input($_POST['mandante']);
                                                                        
                                                                        
                                                                        $query_acquisizione_file = $mysqli->prepare("INSERT INTO `ed_acquisizioni_file` SET id_utente = '$id_operatore',
                                                                                                                                                            id_mandante = '$id_mandante',
                                                                                                                                                            n_pratiche = '$highestRow' - 1,
                                                                                                                                                            tipologia = 'CONTATTI',                                                                                                                                                           
                                                                                                                                                            data_acquisizione = CURRENT_TIMESTAMP");
                                                                        $query_acquisizione_file->execute();
                                                                        $id_acquisizione = $mysqli->insert_id;

                                                                        $mysqli->commit();

                                                                    }catch (mysqli_sql_exception $e){
                                                                        echo 'ERRORE INSERIMENTO ED_ACQUISIZIONI_FILE: ' . $e->getMessage() . '<br> <br>';
                                                                        $mysqli->rollback();
                                                                    };                            
                                                                    $mysqli->autocommit(TRUE);                                                                   
                                                                    $query_acquisizione_file->close();

                                                                    //  Loop through each row of the worksheet in turn
                                                                    $start_time=microtime(true);
                                                                    for ($row = $rigaPartenza; $row <= $highestRow; $row++) {
                                                                        //  Read a row of data into an array
                                                                        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                                                                        //  Insert row data array into your database of choice here

                                                                        $dati = $rowData[0];

                                                                        elaboraDati($dati, $row, $id_acquisizione);
                                                                    }
                                                                    $end_time=microtime(true);
                                                                    $execution_time = $end_time - $start_time;
                                                                    echo '<strong>Tempo esecuzione:' .$execution_time.'</strong><br><br>';

                                                                } else if ($_POST['tipo_file'] == 2 || $_POST['tipo_file'] == 3 || $_POST['tipo_file'] == 4) {
                                                                    $testo = file($inputFileName);
                                                                    $row = file($inputFileName);

                                                                    $id_utente = '';

                                                                    if ($_POST['tipo_file'] == 2 || $_POST['tipo_file'] == 3) {
                                                                        for ($j = $rigaPartenzaNonExcel; $j < count($row); $j++) {
                                                                            $dati = explode($delimiter, trim($row[$j]));

                                                                            elaboraDati($dati, $j, $id_acquisizione);
                                                                        }
                                                                    } else if ($_POST['tipo_file'] == 4) {
                                                                        //print_r($row);
                                                                        $arrayPositions = explode(',', $_POST['lunghezzaPosizioni']);

                                                                        for ($j = $rigaPartenzaNonExcel; $j < count($row); $j++) {
                                                                            $dati = array();
                                                                            for ($z = 0; $z < count($arrayPositions); $z++) {
                                                                                $dati[] = trim(mb_substr($row[$j], ($arrayPositions[$z] - 1), ($arrayPositions[$z + 1] - $arrayPositions[$z]), 'UTF-8'));
                                                                            }
                                                                            //print_r($dati);

                                                                            elaboraDati($dati, $j, $id_acquisizione);
                                                                        }
                                                                    }
                                                                }

                                                                $mysqli->autocommit(FALSE);
                                                                try{
                                                                    $id_operatore = db_input($_SESSION['user_admin_id']);
                                                                    $id_mandante = db_input($_POST['mandante']);
                                                                    
                                                                    
                                                                    $update_acquisizione_pending = $mysqli->prepare("UPDATE `ed_acquisizioni_file` SET stato = 'Pending' WHERE id = '$id_acquisizione' AND tipologia = 'CONTATTI'");
                                                                    $update_acquisizione_pending->execute();
                                                                    
                                                                    $query_stati_acquisizione = $mysqli->prepare("INSERT INTO `e_stati_acquisizioni` SET id_utente = '$id_operatore',
                                                                                                                                                    tipologia = 'CONTATTI',
                                                                                                                                                    id_acquisizione = '$id_acquisizione',
                                                                                                                                                    data_cambio_stato = CURRENT_TIMESTAMP");
                                                                    $query_stati_acquisizione->execute();

                                                                    $mysqli->commit();

                                                                }catch (mysqli_sql_exception $e){
                                                                    echo 'ERRORE INSERIMENTO ED_ACQUISIZIONI_FILE CONTATTI O LOG STATI: ' . $e->getMessage() . '<br> <br>';
                                                                    $mysqli->rollback();
                                                                };                            
                                                                $mysqli->autocommit(TRUE);                                                                   
                                                                $update_acquisizione_pending->close();
                                                                $query_stati_acquisizione->close();
                                                                

                                                                // $mysqli->autocommit(FALSE);
                                                                // try{ 
                                                                //     $copy_data_indirizzi_recapiti = $mysqli->prepare("CALL e_copy_data_indirizzi_recapiti(?)");
                                                                //     $copy_data_indirizzi_recapiti->bind_param('i', $id_acquisizione);
                                                                //     $copy_data_indirizzi_recapiti->execute(); 

                                                                //     $mysqli->commit();  

                                                                //     echo 'STORED PROCEDURE A BUON FINE <br>';


                                                                // }catch (mysqli_sql_exception $e){
                                                                //     echo 'ERRORE STORED PROCEDURE: ' . $e->getMessage() . '<br>';
                                                                //     $mysqli->rollback();
                                                                // };
                                                                // $mysqli->autocommit(TRUE);
                                                                // $copy_data_indirizzi_recapiti->close();

                                                                if (count($eventi_strutturati) > 0) {
                                                                    // ### GESTIONE EVENTI ######################################################################

                                                                    if ($debug) {
                                                                        echo 'ARRAY EVENTI STRUTTURATI: ';
                                                                        print_r($eventi_strutturati);
                                                                        echo '<br><br>';
                                                                    }

                                                                    foreach ($eventi_strutturati as $key => $value) {

                                                                        $value = implode(',', array_unique(explode(',', $value)));


                                                                        echo '<script type="text/javascript">';
                                                                        echo "executeEvent( '" . trim($key) . "', '" . rtrim($value, ',') . "' );";
                                                                        echo "$('#loader1').hide();";
                                                                        echo '</script>';
                                                                    }

                                                                    echo '<B>ESECUZIONE EVENTI ASSOCIATI ESEGUITA<B>';
                                                                    // ### GESTIONE EVENTI ######################################################################
                                                                }
                                                                ?>

                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">

                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                                else {
                                                ?>
                                                    <div class="portlet-body form">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="alert alert-block alert-danger fade in">
                                                                    <button data-dismiss="alert" class="close"
                                                                            type="button"></button>
                                                                    <h4 class="alert-heading">Errore!</h4>
                                                                    <p>
                                                                        <?php
                                                                        if ($error_type == 2) {
                                                                            $error_txt = 'Si è verificato un errore nel caricamento del file, si prega di riprovare. Grazie.';
                                                                        } else if ($error_type == 3) {
                                                                            $error_txt = 'Il file che si sta tentando di caricare non ha un formato corretto.<br><br>Si prega di accertarsi che il file che si sta tentando di caricare sia in uno dei seguenti formati: <strong>.xls .xlsx .csv .txt</strong>';
                                                                        } else {
                                                                            $error_txt = 'Errore generico nella procedura di caricamento del file. Si prega di riprovare. Grazie.';
                                                                        }

                                                                        echo $error_txt;
                                                                        ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <?php
                                        } else {
                                            if (isset($_GET['config']) && $_GET['config'] != '') {
                                                $query_recupero_configurazioni = 'SELECT * FROM template_acquisizione_indirizzi_recapiti WHERE id = "' . $_GET['config'] . '"';

                                                //echo $query_recupero_configurazioni;

                                                $ris_recupero_configurazioni = db_query($query_recupero_configurazioni) or die(mysql_error());
                                                $configurazione = mysql_fetch_array($ris_recupero_configurazioni);


                                                $configurazione_campi = explode(';', $configurazione['campi']);
                                                $configurazione_posizioni = explode(';', $configurazione['posizioni']);
                                                $configurazione_posizioni_indirizzo = explode(';', $configurazione['posizioni_indirizzo']);
                                                $configurazione_fnc = explode(';', $configurazione['funzione_pers']);
                                                $configurazione_val = explode(';', $configurazione['colonne_pers']);
                                            }
                                            ?>
                                            <div class="portlet">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-edit"></i>Impostazioni di Acquisizione
                                                    </div>
                                                    <div class="tools">
                                                        <a href="javascript:;" class="collapse">
                                                        </a>
                                                        <a href="#portlet-config" data-toggle="modal"
                                                           class="config info"></a>
                                                        <!--<a href="javascript:;" class="remove">
                                                                </a>-->
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h3>RECUPERO CONFIGURAZIONE</h3>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6" style="line-height:33px;">
                                                            Mandante
                                                            <div>
                                                                <input id="valore_mandante" name="mandante"
                                                                       class="form-control select2_mandanti" type="text"
                                                                       onChange="$('#dettaglio_mandante').attr('data-brand',$(this).val()); compila_configurazioni($(this).val());"
                                                                       value="<?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione['id_mandante'] > 0) {
                                                                           echo $configurazione['id_mandante'];
                                                                       } else {
                                                                           echo $_POST['mandante'];
                                                                       } ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6" style="line-height:33px;">
                                                            Carica Configurazione
                                                            <div>
                                                                <select id="inserimento_configurazione"
                                                                        class="form-control select2me"
                                                                        onChange="location.href = location.protocol + '//' + location.host + location.pathname + '?config=' + $(this).val()">
                                                                    <option></option>
                                                                    <?php
                                                                    if (isset($_GET['config']) && $_GET['config'] != '') {
                                                                        $query = 'SELECT *
                                                                                                FROM template_acquisizione_indirizzi_recapiti
                                                                                                WHERE id_mandante = "' . $configurazione['id_mandante'] . '"';
                                                                        $ris = db_query($query);

                                                                        while ($row = mysql_fetch_array($ris)) {
                                                                            ?>
                                                                            <option <?php if ($row['id'] == $_GET['config']) echo 'selected'; ?>
                                                                                    value="<?php echo $row['id']; ?>"><?php echo $row['descrizione']; ?></option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h3>SPECIFICHE CONFIGURAZIONE</h3>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            Tipologia di File Importato
                                                            <div>
                                                                <select required name="tipo_file" id="tipo_file"
                                                                        class="form-control select2me tipo_file"
                                                                        onChange="verifica_campi_required($(this).val());">
                                                                    <option></option>
                                                                    <option <?php if ($configurazione['tipo_file'] == 1) echo 'selected' ?>
                                                                            value="1">File Excel (non CSV)
                                                                    </option>
                                                                    <option <?php if ($configurazione['tipo_file'] == 2) echo 'selected' ?>
                                                                            value="2">Carattere separatore (CSV è con
                                                                        separatore ;)
                                                                    </option>
                                                                    <!--<option <?php if ($configurazione['tipo_file'] == 3) echo 'selected' ?> value="3">Tabulazione</option>-->
                                                                    <option <?php if ($configurazione['tipo_file'] == 4) echo 'selected' ?>
                                                                            value="4">Lunghezza Fissa
                                                                    </option>
                                                                </select>
                                                                <!--<small><strong>NB.</strong> I file CSV sono file con separatore "punto e virgola"</small>-->
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            File da Importare
                                                            <div class="fileinput fileinput-new"
                                                                 data-provides="fileinput" style="width: 100%;">
                                                                <div class="input-group">
                                                                    <div class="form-control uneditable-input span3"
                                                                         data-trigger="fileinput">
                                                                        <i class="fa fa-file fileinput-exists"></i>&nbsp;
                                                                        <span class="fileinput-filename">
                                                                                </span>
                                                                    </div>
                                                                    <span class="input-group-addon btn default btn-file">
                                                                                <span class="fileinput-new"> Seleziona file </span>
                                                                                <span class="fileinput-exists"><i
                                                                                            class="fa fa-exchange"></i></span>
                                                                                <input type="file" name="file" required>
                                                                            </span>
                                                                    <a href="#"
                                                                       class="input-group-addon btn btn-danger fileinput-exists"
                                                                       data-dismiss="fileinput"><i
                                                                                class="fa fa-times"></i></a>
                                                                </div>
                                                                <small></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="options riga" <?php if ($configurazione['tipo_file'] != 1) echo 'style="display:none"'; ?>>
                                                                Riga di partenza <small>(richiesta solo per file
                                                                    Excel)</small>
                                                                <div>
                                                                    <input id="rigaPartenza" type="text"
                                                                           class="form-control" name="rigaPartenza"
                                                                           value="<?php echo $configurazione['riga'] ?>">
                                                                    <small></small>
                                                                </div>
                                                            </div>
                                                            &nbsp;
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4 hidden">
                                                            Formato Valuta
                                                            <div>
                                                                <select required id="formato_valuta"
                                                                        name="formato_valuta"
                                                                        class="form-control select2me formato_valuta">
                                                                    <option></option>
                                                                    <option <?php if ($configurazione['formato_valuta'] == '1') echo 'selected' ?>
                                                                            value="1">999900 (intero)
                                                                    </option>
                                                                    <option <?php if ($configurazione['formato_valuta'] == '2') echo 'selected' ?>
                                                                            value="2">999900 (intero di cui 1 decimale)
                                                                    </option>
                                                                    <option <?php if ($configurazione['formato_valuta'] == '3') echo 'selected' ?>
                                                                            value="3">999900 (intero di cui 2 decimali)
                                                                    </option>
                                                                    <option selected <?php if ($configurazione['formato_valuta'] == '4') echo 'selected' ?>
                                                                            value="4">9999.00 (decimale - punto)
                                                                    </option>
                                                                    <option <?php if ($configurazione['formato_valuta'] == '5') echo 'selected' ?>
                                                                            value="5">9999,00 (decimale - virgola)
                                                                    </option>
                                                                    <option <?php if ($configurazione['formato_valuta'] == '6') echo 'selected' ?>
                                                                            value="6">9,999.00 (decimale - punto |
                                                                        migliaia - virgola)
                                                                    </option>
                                                                    <option <?php if ($configurazione['formato_valuta'] == '7') echo 'selected' ?>
                                                                            value="7">9.999,00 (decimale - virgola |
                                                                        migliaia - punto)
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            Formato Data
                                                            <div>
                                                                <select required id="formato_data" name="formato_data"
                                                                        class="form-control select2me formato_data">
                                                                    <option></option>
                                                                    <option <?php if ($configurazione['formato_data'] == 'ddmmYYYY') echo 'selected' ?>
                                                                            value="ddmmYYYY">ddmmYYYY
                                                                    </option>
                                                                    <option <?php if ($configurazione['formato_data'] == 'dd mm YYYY') echo 'selected' ?>
                                                                            value="dd mm YYYY">dd mm YYYY
                                                                    </option>
                                                                    <option <?php if ($configurazione['formato_data'] == 'dd-mm-YYYY') echo 'selected' ?>
                                                                            value="dd-mm-YYYY">dd-mm-YYYY
                                                                    </option>
                                                                    <option <?php if ($configurazione['formato_data'] == 'dd/mm/YYYY') echo 'selected' ?>
                                                                            value="dd/mm/YYYY">dd/mm/YYYY
                                                                    </option>
                                                                    <option <?php if ($configurazione['formato_data'] == 'YYYYmmdd') echo 'selected' ?>
                                                                            value="YYYYmmdd">YYYYmmdd
                                                                    </option>
                                                                    <option <?php if ($configurazione['formato_data'] == 'YYYY mm dd') echo 'selected' ?>
                                                                            value="YYYY mm dd">YYYY mm dd
                                                                    </option>
                                                                    <option <?php if ($configurazione['formato_data'] == 'YYYY-mm-dd') echo 'selected' ?>
                                                                            value="YYYY-mm-dd">YYYY-mm-dd
                                                                    </option>
                                                                    <option <?php if ($configurazione['formato_data'] == 'YYYY/mm/dd') echo 'selected' ?>
                                                                            value="YYYY/mm/dd">YYYY/mm/dd
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="options delimitatore" <?php if ($configurazione['tipo_file'] != 2) echo 'style="display:none"'; ?>>
                                                                Carattere delimitatore <small>(richiesta solo per file
                                                                    con carattere separatore)</small>
                                                                <div>
                                                                    <input id="delimitatore" type="text"
                                                                           class="form-control" name="delimitatore"
                                                                           value="<?php echo $configurazione['delimitatore'] ?>">
                                                                    <small></small>
                                                                </div>
                                                            </div>
                                                            <div class="options lunghezzaPosizioni" <?php if ($configurazione['tipo_file'] != 4) echo 'style="display:none"'; ?>>
                                                                Posizioni Campi <small>(richiesta solo per file con
                                                                    lunghezza fissa)</small>
                                                                <div>
                                                                    <input id="lunghezzaPosizioni" type="text"
                                                                           class="form-control"
                                                                           name="lunghezzaPosizioni"
                                                                           value="<?php echo $configurazione['lunghezzaPosizioni'] ?>">
                                                                    <small></small>
                                                                </div>
                                                            </div>
                                                            &nbsp;
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h3>OVERRIDE INFORMAZIONI E VALORI PREDEFINITI</h3>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            Evento Strutturato <small>indirizzi</small>
                                                            <div style="margin-bottom: 7px;">
                                                                <input id="evento_strutturato_predefinito_indirizzi"
                                                                       name="evento_strutturato_predefinito_indirizzi"
                                                                       class="form-control select2_eventi_strutturati"
                                                                       type="text"
                                                                       value="<?php if (isset($_GET['config']) && $_GET['config'] != '') {
                                                                           echo $configurazione['evento_strutturato_indirizzo'];
                                                                       } ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            Evento Strutturato <small>recapiti</small>
                                                            <div style="margin-bottom: 7px;">
                                                                <input id="evento_strutturato_predefinito_recapiti"
                                                                       name="evento_strutturato_predefinito_recapiti"
                                                                       class="form-control select2_eventi_strutturati"
                                                                       type="text"
                                                                       value="<?php if (isset($_GET['config']) && $_GET['config'] != '') {
                                                                           echo $configurazione['evento_strutturato_recapito'];
                                                                       } ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            Indirizzo Principale
                                                            <div style="margin-bottom: 7px;">
                                                                <input id="checkHiddenIndPred" type="hidden" value="0"
                                                                       name="ind_predefinito">
                                                                <input type="checkbox"
                                                                       class="make-switch ind_primario_predefinito"
                                                                       id="ind_predefinito" name="ind_predefinito"
                                                                       value="1" data-on-color="success"
                                                                       data-off-color="warning"
                                                                       data-on-text="&nbsp;<i class='fa fa-check-circle-o'></i>&nbsp;"
                                                                       data-off-text="&nbsp;<i class='fa fa-circle-o'></i>&nbsp;" <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione['indirizzo_predefinito'] == 1) echo 'checked'; ?>>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            Corrispondenza
                                                            <div style="margin-bottom: 7px;">
                                                                <input id="checkHiddenCorrisp" type="hidden" value="0"
                                                                       name="ind_corrispondenza_predefinito">
                                                                <input type="checkbox"
                                                                       class="make-switch ind_corrispondenza_predefinito"
                                                                       id="ind_corrispondenza_predefinito"
                                                                       name="ind_corrispondenza_predefinito" value="1"
                                                                       data-on-color="success" data-off-color="warning"
                                                                       data-on-text="&nbsp;<i class='fa fa-check-circle-o'></i>&nbsp;"
                                                                       data-off-text="&nbsp;<i class='fa fa-circle-o'></i>&nbsp;" <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione['indirizzo_corrispondenza'] == 1) echo 'checked'; ?>>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 hidden">
                                                            Tipo Indirizzo
                                                            <div style="margin-bottom: 7px;">
                                                                <select id="tipo_indirizzo_predefinito"
                                                                        name="tipo_indirizzo_predefinito"
                                                                        class="form-control select2me">
                                                                    <option></option>
                                                                    <?php
                                                                    $tipologie = recuperaTipologieIndirizzo();

                                                                    foreach ($tipologie as $tipologia) {
                                                                        ?>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione['tipo_indirizzo'] == $tipologia['id_tipo_indirizzo']) echo 'selected'; ?>
                                                                                value="<?php echo $tipologia['id_tipo_indirizzo']; ?>"><?php echo $tipologia['tipo']; ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            Indirizzi Attivi
                                                            <div style="margin-bottom: 7px;">
                                                                <input id="checkHiddenIndAct" type="hidden" value="0"
                                                                       name="ind_primario_attivo">
                                                                <input type="checkbox"
                                                                       class="make-switch ind_primario_attivo"
                                                                       id="ind_primario_attivo"
                                                                       name="ind_primario_attivo"
                                                                       value="1"
                                                                       data-on-color="success"
                                                                       data-off-color="warning"
                                                                       data-on-text="&nbsp;<i class='fa fa-check-circle-o'></i>&nbsp;"
                                                                       data-off-text="&nbsp;<i class='fa fa-circle-o'></i>&nbsp;" <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione['indirizzo_attivo'] == 1) echo 'checked'; ?>>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            Sovrascrivi Indirizzo
                                                            <div style="margin-bottom: 7px;">
                                                                <input id="checkHiddenIndSovrascrivi" type="hidden"
                                                                       value="0" name="sovrascrivi_indirizzo">
                                                                <input type="checkbox"
                                                                       class="make-switch sovrascrivi_indirizzo"
                                                                       id="sovrascrivi_indirizzo"
                                                                       name="sovrascrivi_indirizzo"
                                                                       value="1"
                                                                       data-on-color="success"
                                                                       data-off-color="warning"
                                                                       data-on-text="&nbsp;<i class='fa fa-check-circle-o'></i>&nbsp;"
                                                                       data-off-text="&nbsp;<i class='fa fa-circle-o'></i>&nbsp;" <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione['sovrascrivi_indirizzo'] == 1) echo 'checked'; ?>>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            Recapito Principale
                                                            <div style="margin-bottom: 7px;">
                                                                <input id="checkHiddenRecPred" type="hidden" value="0"
                                                                       name="rec_predefinito">
                                                                <input type="checkbox"
                                                                       class="make-switch rec_predefinito"
                                                                       id="rec_predefinito" name="rec_predefinito"
                                                                       value="1" data-on-color="success"
                                                                       data-off-color="warning"
                                                                       data-on-text="&nbsp;<i class='fa fa-check-circle-o'></i>&nbsp;"
                                                                       data-off-text="&nbsp;<i class='fa fa-circle-o'></i>&nbsp;" <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione['recapito_predefinito'] == 1) echo 'checked'; ?>>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            Usa per Invio
                                                            <div style="margin-bottom: 7px;">
                                                                <input id="checkHiddenRecCorrisp" type="hidden"
                                                                       value="0" name="rec_invio_predefinito">
                                                                <input type="checkbox"
                                                                       class="make-switch rec_invio_predefinito"
                                                                       id="rec_invio_predefinito"
                                                                       name="rec_invio_predefinito" value="1"
                                                                       data-on-color="success" data-off-color="warning"
                                                                       data-on-text="&nbsp;<i class='fa fa-check-circle-o'></i>&nbsp;"
                                                                       data-off-text="&nbsp;<i class='fa fa-circle-o'></i>&nbsp;" <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione['recapito_invio'] == 1) echo 'checked'; ?>>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 hidden">
                                                            Tipo Recapito
                                                            <div style="margin-bottom: 7px;">
                                                                <select id="tipo_recapito_predefinito"
                                                                        name="tipo_recapito_predefinito"
                                                                        class="form-control select2me tipologie_recapito">
                                                                    <option></option>
                                                                    <?php
                                                                    $tipologie = recuperaTipologieRecapito();

                                                                    foreach ($tipologie as $tipologia) {
                                                                        ?>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione['tipo_recapito'] == $tipologia['id_tipo_recapito_telefonico']) echo 'selected'; ?>
                                                                                value="<?php echo $tipologia['id_tipo_recapito_telefonico']; ?>"><?php echo $tipologia['tipo_recapito_telefonico']; ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            Recapiti Attivi
                                                            <div style="margin-bottom: 7px;">
                                                                <input id="checkHiddenRecAct" type="hidden" value="0"
                                                                       name="rec_primario_attivo">
                                                                <input type="checkbox"
                                                                       class="make-switch rec_primario_attivo"
                                                                       id="rec_primario_attivo"
                                                                       name="rec_primario_attivo" value="1"
                                                                       data-on-color="success" data-off-color="warning"
                                                                       data-on-text="&nbsp;<i class='fa fa-check-circle-o'></i>&nbsp;"
                                                                       data-off-text="&nbsp;<i class='fa fa-circle-o'></i>&nbsp;" <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione['recapito_attivo'] == 1) echo 'checked'; ?>>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            Sovrascrivi Recapito
                                                            <div style="margin-bottom: 7px;">
                                                                <input id="checkHiddenRecSovrascrivi" type="hidden"
                                                                       value="0" name="sovrascrivi_recapito">
                                                                <input type="checkbox"
                                                                       class="make-switch sovrascrivi_recapito"
                                                                       id="sovrascrivi_recapito"
                                                                       name="sovrascrivi_recapito" value="1"
                                                                       data-on-color="success" data-off-color="warning"
                                                                       data-on-text="&nbsp;<i class='fa fa-check-circle-o'></i>&nbsp;"
                                                                       data-off-text="&nbsp;<i class='fa fa-circle-o'></i>&nbsp;" <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione['sovrascrivi_recapito'] == 1) echo 'checked'; ?>>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            Fonte
                                                            <div style="margin-bottom: 7px;">
                                                                <select id="fonte_predefinito" name="fonte_predefinito"
                                                                        class="form-control select2me fonte_predefinito">
                                                                    <option></option>
                                                                    <?php
                                                                    $tipologie = recuperaFonti();

                                                                    foreach ($tipologie as $tipologia) {
                                                                        ?>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione['fonte_predefinita'] == $tipologia['id_tipo_fonte']) echo 'selected'; ?>
                                                                                value="<?php echo $tipologia['id_tipo_fonte']; ?>"><?php echo $tipologia['tipo_fonte']; ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            Data Inizio Inizio Validità
                                                            <div class="input-group date date-picker"
                                                                 data-date-format="dd-mm-yyyy"
                                                                 style="margin-bottom: 7px;">
                                                                <!--<input readonly onClick="$('#calendar0').click();" placeholder="Inserisci Data" id="fixed_data_validita" name="fixed_data_validita" type="text" class="form-control" value="<?php if ($configurazione['data_validita'] > '1970-01-01') echo date('d-m-Y', strtotime($configurazione['data_validita'])); else echo date('d-m-Y'); ?>">-->
                                                                <input readonly onClick="$('#calendar0').click();"
                                                                       placeholder="Inserisci Data"
                                                                       id="fixed_data_validita"
                                                                       name="fixed_data_validita" type="text"
                                                                       class="form-control"
                                                                       value="<?php echo date('d-m-Y'); ?>">
                                                                <span class="input-group-btn">
                                                                            <button id="calendar0"
                                                                                    style="padding: 6px 5px !important"
                                                                                    class="btn btn-info" type="button">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </button>
                                                                        </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php
                                                    $query_stati = "SELECT id, descrizione FROM stati_pratiche ORDER BY descrizione ASC";
                                                    $ris_query_stati = db_query($query_stati);
                                                    $stati_pratica = array();
                                                    while ($row_stati = mysql_fetch_assoc($ris_query_stati)) {
                                                        $stati_pratica[$row_stati['id']] = $row_stati['descrizione'];
                                                    }
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h3>CAMBIO STATO PRATICA ASSOCIATA AL DEBITORE</h3>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            Cambio stato automatico in
                                                            <div style="margin-bottom: 7px;">
                                                                <select id="stato_sostituto" name="stato_sostituto"
                                                                        class="form-control select2me stato_sostituto">
                                                                    <option></option>
                                                                    <?php
                                                                    foreach ($stati_pratica as $id_stato => $stato_pratica) {
                                                                        ?>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione['stato_sostituto'] == $id_stato) echo 'selected'; ?>
                                                                                value="<?php echo $id_stato; ?>"><?php echo $stato_pratica; ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                            Stati esclusi dal cambio stato automatico
                                                            <div style="margin-bottom: 7px;">
                                                                <select id="stati_esclusi" name="stati_esclusi[]"
                                                                        class="form-control select2me stati_esclusi"
                                                                        multiple>
                                                                    <option></option>
                                                                    <?php
                                                                    foreach ($stati_pratica as $id_stato => $stato_pratica) {
                                                                        ?>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && in_array($id_stato, explode(',', $configurazione['stati_esclusi']))) echo 'selected'; ?>
                                                                                value="<?php echo $id_stato; ?>"><?php echo $stato_pratica; ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h3>DETTAGLIO CONFIGURAZIONE</h3>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="margin-bottom: 10px;">
                                                        <?php
                                                        $line = 0;
                                                        for ($i = 0; $i < 200; $i++) {
                                                            if ($configurazione_campi[$i] != '')
                                                                $line = $i + 1;
                                                        }
                                                        if ($line < 10) $line = 10;

                                                        $configurazione_tipo_indirizzo = explode(';', $configurazione['tipologie_indirizzo']);
                                                        $configurazione_tipo_recapito = explode(';', $configurazione['tipologie_recapito']);

                                                        for ($i = 0; $i < 200; $i++) {
                                                            ?>
                                                            <span class="line"
                                                                  style=" <?php if ($i >= 10 && $i >= $line) echo 'display: none;'; ?>"
                                                                  data-line="<?php echo $i ?>">
                                                                            <div class="col-md-2"
                                                                                 style="line-height:33px;">
                                                                                COLONNA <?php echo($i + 1); ?>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div>
                                                                                    <select name="campo[]"
                                                                                            class="form-control <?php if ($i < $line) echo 'select2me'; ?> campi"
                                                                                            onChange="displayOptionalFields($(this),<?php echo $i ?>)">
                                                                                        <option <?php if (!isset($_GET['config']) || $_GET['config'] == '') echo 'selected'; ?>></option>
																						<optgroup label="Riferimenti">
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'altro*-*id_anagrafica') echo 'selected'; ?> value="altro*-*id_anagrafica">RIFERIMENTI - ID Anagrafica ReMida</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'altro*-*codice_fiscale') echo 'selected'; ?> value="altro*-*codice_fiscale">RIFERIMENTI - Codice Fiscale / P.IVA</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'altro*-*id_pratica') echo 'selected'; ?> value="altro*-*id_pratica">RIFERIMENTI - ID Pratica ReMida</option>
                                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'altro*-*riferimento_mandante_1') echo 'selected'; ?> value="altro*-*riferimento_mandante_1">RIFERIMENTI - Riferimento Mandante 1 ReMida</option
                                                                                            >
                                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'altro*-*riferimento_mandante_2') echo 'selected'; ?> value="altro*-*riferimento_mandante_2">RIFERIMENTI - Riferimento Mandante 2 ReMida</option
                                                                                            >
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'altro*-*codice_anagrafico_mandante') echo 'selected'; ?> value="altro*-*codice_anagrafico_mandante">RIFERIMENTI - Codice Anagrafico Mandante</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'altro*-*id_mandante') echo 'selected'; ?> value="altro*-*id_mandante">RIFERIMENTI - ID Mandante ReMida</option>
																						</optgroup>
																						<optgroup
                                                                                                label="Dati ANAGRAFICA">
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*politicamente_esposto') echo 'selected'; ?> value="anagrafica*-*politicamente_esposto">ANAGRAFICA - Politicamente Esposto</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*protestato') echo 'selected'; ?> value="anagrafica*-*protestato">ANAGRAFICA - Protestato</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*catasto_positivo') echo 'selected'; ?> value="anagrafica*-*catasto_positivo">ANAGRAFICA - Catasto Positivo</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*codice_fiscale') echo 'selected'; ?> value="anagrafica*-*codice_fiscale">ANAGRAFICA - Codice Fiscale /  P.IVA</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*lingua') echo 'selected'; ?> value="anagrafica*-*lingua">ANAGRAFICA - Lingua</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*sesso') echo 'selected'; ?> value="anagrafica*-*sesso">ANAGRAFICA - Sesso</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*data_nascita') echo 'selected'; ?> value="anagrafica*-*data_nascita">ANAGRAFICA - Data di Nascita</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*data_decesso') echo 'selected'; ?> value="anagrafica*-*data_decesso">ANAGRAFICA - Data di Decesso</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*nazione') echo 'selected'; ?> value="anagrafica*-*nazione">ANAGRAFICA - Nazione</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*citta') echo 'selected'; ?> value="anagrafica*-*citta">ANAGRAFICA - Citta</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*cap') echo 'selected'; ?> value="anagrafica*-*cap">ANAGRAFICA - CAP</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*provincia') echo 'selected'; ?> value="anagrafica*-*provincia">ANAGRAFICA - Provincia</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*note') echo 'selected'; ?> value="anagrafica*-*note">ANAGRAFICA - Note</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*tipo_indirizzo') echo 'selected'; ?> value="anagrafica*-*tipo_indirizzo">ANAGRAFICA - Tipologia Indirizzo</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*indirizzo') echo 'selected'; ?> value="anagrafica*-*indirizzo">ANAGRAFICA - Indirizzo</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*indirizzo_corrispondenza') echo 'selected'; ?> value="anagrafica*-*indirizzo_corrispondenza">ANAGRAFICA - Corrispondenza</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*indirizzo_principale') echo 'selected'; ?> value="anagrafica*-*indirizzo_principale">ANAGRAFICA - Indirizzo Principale</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*indirizzo_attivo') echo 'selected'; ?> value="anagrafica*-*indirizzo_attivo">ANAGRAFICA - Indirizzo Attivo</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*tipo_recapito') echo 'selected'; ?> value="anagrafica*-*tipo_recapito">ANAGRAFICA - Tipo Recapito</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*recapito') echo 'selected'; ?> value="anagrafica*-*recapito">ANAGRAFICA - Recapito</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*note_recapito') echo 'selected'; ?> value="anagrafica*-*note_recapito">ANAGRAFICA - Note Recapito</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*recapito_corrispondenza') echo 'selected'; ?> value="anagrafica*-*recapito_corrispondenza">ANAGRAFICA - Usa Per Invio</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*recapito_principale') echo 'selected'; ?> value="anagrafica*-*recapito_principale">ANAGRAFICA - Recapito Principale</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*recapito_attivo') echo 'selected'; ?> value="anagrafica*-*recapito_attivo">ANAGRAFICA - Recapito Attivo</option>
																							<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*evento_strutturato') echo 'selected'; ?> value="anagrafica*-*evento_strutturato">ANAGRAFICA - Evento RIGA</option>
																						</optgroup>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4" style="height: 35px;">
                                                                            	<?php
                                                                                $mostra_posizione = false;
                                                                                $mostra_posizione_indirizzo = false;
                                                                                $mostra_tipologie_indirizzo = false;
                                                                                $mostra_tipologie_recapito = false;

                                                                                if (isset($_GET['config']) && $_GET['config'] != '') {
                                                                                    if ($configurazione_campi[$i] == 'anagrafica*-*indirizzo' /*|| $configurazione_campi[$i] == 'anagrafica*-*recapito'*/) {
                                                                                        $mostra_tipologie_indirizzo = true;
                                                                                    }
                                                                                    if ($configurazione_campi[$i] == 'anagrafica*-*recapito' /*|| $configurazione_campi[$i] == 'anagrafica*-*recapito'*/) {
                                                                                        $mostra_tipologie_recapito = true;
                                                                                    }
                                                                                }

                                                                                if (
                                                                                    $configurazione_campi[$i] == 'anagrafica*-*tipo_indirizzo' ||
                                                                                    $configurazione_campi[$i] == 'anagrafica*-*indirizzo' ||
                                                                                    $configurazione_campi[$i] == 'anagrafica*-*indirizzo_corrispondenza' ||
                                                                                    $configurazione_campi[$i] == 'anagrafica*-*indirizzo_attivo' ||
                                                                                    $configurazione_campi[$i] == 'anagrafica*-*indirizzo_principale' ||
                                                                                    $configurazione_campi[$i] == 'anagrafica*-*cap' ||
                                                                                    $configurazione_campi[$i] == 'anagrafica*-*citta' ||
                                                                                    $configurazione_campi[$i] == 'anagrafica*-*provincia' ||
                                                                                    $configurazione_campi[$i] == 'anagrafica*-*nazione' ||
                                                                                    $configurazione_campi[$i] == 'anagrafica*-*tipo_recapito' ||
                                                                                    $configurazione_campi[$i] == 'anagrafica*-*recapito' ||
                                                                                    $configurazione_campi[$i] == 'anagrafica*-*recapito_attivo' ||
                                                                                    $configurazione_campi[$i] == 'anagrafica*-*recapito_corrispondenza' ||
                                                                                    $configurazione_campi[$i] == 'anagrafica*-*recapito_principale' ||
                                                                                    $configurazione_campi[$i] == 'anagrafica*-*note_recapito') {
                                                                                    $mostra_posizione = true;
                                                                                }

                                                                                if (
                                                                                    $configurazione_campi[$i] == 'anagrafica*-*tipo_indirizzo' ||
                                                                                    $configurazione_campi[$i] == 'anagrafica*-*indirizzo' ||
                                                                                    $configurazione_campi[$i] == 'anagrafica*-*indirizzo_corrispondenza' ||
                                                                                    $configurazione_campi[$i] == 'anagrafica*-*indirizzo_attivo' ||
                                                                                    $configurazione_campi[$i] == 'anagrafica*-*indirizzo_principale' ||
                                                                                    $configurazione_campi[$i] == 'anagrafica*-*cap' ||
                                                                                    $configurazione_campi[$i] == 'anagrafica*-*citta' ||
                                                                                    $configurazione_campi[$i] == 'anagrafica*-*provincia' ||
                                                                                    $configurazione_campi[$i] == 'anagrafica*-*nazione') {
                                                                                    //$mostra_posizione = true;
                                                                                    $mostra_posizione_indirizzo = true;
                                                                                }

                                                                                ?>

																				<div style="width: 40px; float: left; margin-right: 10px;"
                                                                                     class="extra-field-<?php echo $i ?> field_position-<?php echo $i ?> <?php if (!$mostra_posizione) echo 'hidden'; ?>">
																					<input name="posizione[]"
                                                                                           class="form-control posizioni"
                                                                                           value="<?php if (isset($_GET['config']) && $_GET['config'] != '') echo $configurazione_posizioni[$i]; else echo '0'; ?>"
                                                                                           placeholder="POS">
																				</div>
																				<div style="width: 40px; float: left; margin-right: 10px;"
                                                                                     class="extra-field-<?php echo $i ?> field_address_position-<?php echo $i ?> <?php if (!$mostra_posizione_indirizzo) echo 'hidden'; ?>">
																					<input name="posizione_indirizzo[]"
                                                                                           class="form-control posizioni_indirizzo"
                                                                                           value="<?php if (isset($_GET['config']) && $_GET['config'] != '') echo $configurazione_posizioni_indirizzo[$i]; ?>"
                                                                                           placeholder="I.POS">
																				</div>
																				<div style="float: left; width: 120px; margin-right: 10px;"
                                                                                     class="extra-field-<?php echo $i ?> field_address_type-<?php echo $i ?> <?php if (!$mostra_tipologie_indirizzo) echo 'hidden'; ?>">

																					<select name="address_type[]"
                                                                                            class="form-control <?php if ($i < $line) echo 'select2me'; ?> tipologie_indirizzo">
																						<option></option>
																						<?php
                                                                                        $tipologie = recuperaTipologieIndirizzo();

                                                                                        foreach ($tipologie as $tipologia) {
                                                                                            ?>
                                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_tipo_indirizzo[$i] == $tipologia['id_tipo_indirizzo']) echo 'selected'; ?> value="<?php echo $tipologia['id_tipo_indirizzo']; ?>"><?php echo $tipologia['tipo']; ?></option>
                                                                                            <?php
                                                                                        }
                                                                                        ?>
																					</select>
																				</div>
																				<div style="float: left; width: 120px; margin-right: 10px;"
                                                                                     class="extra-field-<?php echo $i ?> field_rec_type-<?php echo $i ?> <?php if (!$mostra_tipologie_recapito) echo 'hidden'; ?>">

																					<select name="rec_type[]"
                                                                                            class="form-control <?php if ($i < $line) echo 'select2me'; ?> tipologie_recapito_dett">
																						<option></option>
																						<?php
                                                                                        $tipologie = recuperaTipologieRecapito();

                                                                                        foreach ($tipologie as $tipologia) {
                                                                                            ?>
                                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_tipo_recapito[$i] == $tipologia['id_tipo_recapito_telefonico']) echo 'selected'; ?> value="<?php echo $tipologia['id_tipo_recapito_telefonico']; ?>"><?php echo $tipologia['tipo_recapito_telefonico']; ?></option>
                                                                                            <?php
                                                                                        }
                                                                                        ?>
																					</select>
																				</div>
                                                                            </div>
                                                                            <div class="col-md-2"
                                                                                 style="line-height:44px;">&nbsp;</div>
                                                                        </span>
                                                            <?php
                                                        }
                                                        ?>
                                                        <div class="col-md-6">
                                                            <button style="width:100%;" type="button"
                                                                    onClick="displayMoreLines()"
                                                                    class="btn btn-primary aggiungi-campi">AGGIUNGI
                                                                ALTRI CAMPI
                                                            </button>
                                                        </div>
                                                        <div class="col-md-6" style="line-height:44px;">&nbsp;</div>
                                                        <?php
                                                        for ($i = 200; $i < 210; $i++) {

                                                            $mostra_posizione = false;
                                                            $mostra_posizione_indirizzo = false;
                                                            $mostra_tipologie_indirizzo = false;
                                                            $mostra_tipologie_recapito = false;

                                                            if (isset($_GET['config']) && $_GET['config'] != '') {
                                                                if ($configurazione_campi[$i] == 'anagrafica*-*indirizzo' /*|| $configurazione_campi[$i] == 'anagrafica*-*recapito'*/) {
                                                                    $mostra_tipologie_indirizzo = true;
                                                                }
                                                                if ($configurazione_campi[$i] == 'anagrafica*-*recapito' /*|| $configurazione_campi[$i] == 'anagrafica*-*recapito'*/) {
                                                                    $mostra_tipologie_recapito = true;
                                                                }
                                                            }

                                                            if (
                                                                $configurazione_campi[$i] == 'anagrafica*-*tipo_indirizzo' ||
                                                                $configurazione_campi[$i] == 'anagrafica*-*indirizzo' ||
                                                                $configurazione_campi[$i] == 'anagrafica*-*indirizzo_corrispondenza' ||
                                                                $configurazione_campi[$i] == 'anagrafica*-*indirizzo_attivo' ||
                                                                $configurazione_campi[$i] == 'anagrafica*-*indirizzo_principale' ||
                                                                $configurazione_campi[$i] == 'anagrafica*-*cap' ||
                                                                $configurazione_campi[$i] == 'anagrafica*-*citta' ||
                                                                $configurazione_campi[$i] == 'anagrafica*-*provincia' ||
                                                                $configurazione_campi[$i] == 'anagrafica*-*nazione' ||
                                                                $configurazione_campi[$i] == 'anagrafica*-*tipo_recapito' ||
                                                                $configurazione_campi[$i] == 'anagrafica*-*recapito' ||
                                                                $configurazione_campi[$i] == 'anagrafica*-*recapito_corrispondenza' ||
                                                                $configurazione_campi[$i] == 'anagrafica*-*recapito_attivo' ||
                                                                $configurazione_campi[$i] == 'anagrafica*-*recapito_principale' ||
                                                                $configurazione_campi[$i] == 'anagrafica*-*note_recapito') {
                                                                $mostra_posizione = true;
                                                            }

                                                            if (
                                                                $configurazione_campi[$i] == 'anagrafica*-*tipo_indirizzo' ||
                                                                $configurazione_campi[$i] == 'anagrafica*-*indirizzo' ||
                                                                $configurazione_campi[$i] == 'anagrafica*-*indirizzo_corrispondenza' ||
                                                                $configurazione_campi[$i] == 'anagrafica*-*indirizzo_attivo' ||
                                                                $configurazione_campi[$i] == 'anagrafica*-*indirizzo_principale' ||
                                                                $configurazione_campi[$i] == 'anagrafica*-*cap' ||
                                                                $configurazione_campi[$i] == 'anagrafica*-*citta' ||
                                                                $configurazione_campi[$i] == 'anagrafica*-*provincia' ||
                                                                $configurazione_campi[$i] == 'anagrafica*-*nazione') {
                                                                //$mostra_posizione = true;
                                                                $mostra_posizione_indirizzo = true;
                                                            }

                                                            ?>
                                                            <div class="col-md-2" style="line-height:33px;">
                                                                COMPOSTO <?php echo($i + 1 - 200) ?>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div>
                                                                    <select name="campo[]"
                                                                            class="form-control select2me campi"
                                                                            onChange="displayOptionalFields($(this),<?php echo $i ?>)">
                                                                        <option <?php if (!isset($_GET['config']) || $_GET['config'] == '') echo 'selected'; ?>></option>
                                                                        <optgroup label="Riferimenti">
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'altro*-*id_anagrafica') echo 'selected'; ?>
                                                                                    value="altro*-*id_anagrafica">
                                                                                RIFERIMENTI - ID Anagrafica ReMida
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'altro*-*codice_fiscale') echo 'selected'; ?>
                                                                                    value="altro*-*codice_fiscale">
                                                                                RIFERIMENTI - Codice Fiscale / P.IVA
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'altro*-*id_pratica') echo 'selected'; ?>
                                                                                    value="altro*-*id_pratica">
                                                                                RIFERIMENTI - ID Pratica ReMida
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'altro*-*riferimento_mandante_1') echo 'selected'; ?>
                                                                                    value="altro*-*riferimento_mandante_1">
                                                                                RIFERIMENTI - Riferimento Mandante 1
                                                                                ReMida
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'altro*-*riferimento_mandante_2') echo 'selected'; ?>
                                                                                    value="altro*-*riferimento_mandante_2">
                                                                                RIFERIMENTI - Riferimento Mandante 2
                                                                                ReMida
                                                                            </option>

                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'altro*-*codice_anagrafico_mandante') echo 'selected'; ?>
                                                                                    value="altro*-*codice_anagrafico_mandante">
                                                                                RIFERIMENTI - Codice Anagrafico Mandante
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'altro*-*id_mandante') echo 'selected'; ?>
                                                                                    value="altro*-*id_mandante">
                                                                                RIFERIMENTI - ID Mandante ReMida
                                                                            </option>
                                                                        </optgroup>
                                                                        <optgroup label="Dati ANAGRAFICA">
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*politicamente_esposto') echo 'selected'; ?>
                                                                                    value="anagrafica*-*politicamente_esposto">
                                                                                ANAGRAFICA - Politicamente Esposto
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*protestato') echo 'selected'; ?>
                                                                                    value="anagrafica*-*protestato">
                                                                                ANAGRAFICA - Protestato
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*catasto_positivo') echo 'selected'; ?>
                                                                                    value="anagrafica*-*catasto_positivo">
                                                                                ANAGRAFICA - Catasto Positivo
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*codice_fiscale') echo 'selected'; ?>
                                                                                    value="anagrafica*-*codice_fiscale">
                                                                                ANAGRAFICA - Codice Fiscale / P.IVA
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*lingua') echo 'selected'; ?>
                                                                                    value="anagrafica*-*lingua">
                                                                                ANAGRAFICA - Lingua
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*sesso') echo 'selected'; ?>
                                                                                    value="anagrafica*-*sesso">
                                                                                ANAGRAFICA - Sesso
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*data_nascita') echo 'selected'; ?>
                                                                                    value="anagrafica*-*data_nascita">
                                                                                ANAGRAFICA - Data di Nascita
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*data_decesso') echo 'selected'; ?>
                                                                                    value="anagrafica*-*data_decesso">
                                                                                ANAGRAFICA - Data di Decesso
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*nazione') echo 'selected'; ?>
                                                                                    value="anagrafica*-*nazione">
                                                                                ANAGRAFICA - Nazione
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*citta') echo 'selected'; ?>
                                                                                    value="anagrafica*-*citta">
                                                                                ANAGRAFICA - Citta
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*cap') echo 'selected'; ?>
                                                                                    value="anagrafica*-*cap">ANAGRAFICA
                                                                                - CAP
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*provincia') echo 'selected'; ?>
                                                                                    value="anagrafica*-*provincia">
                                                                                ANAGRAFICA - Provincia
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*note') echo 'selected'; ?>
                                                                                    value="anagrafica*-*note">ANAGRAFICA
                                                                                - Note
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*tipo_indirizzo') echo 'selected'; ?>
                                                                                    value="anagrafica*-*tipo_indirizzo">
                                                                                ANAGRAFICA - Tipologia Indirizzo
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*indirizzo') echo 'selected'; ?>
                                                                                    value="anagrafica*-*indirizzo">
                                                                                ANAGRAFICA - Indirizzo
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*indirizzo_corrispondenza') echo 'selected'; ?>
                                                                                    value="anagrafica*-*indirizzo_corrispondenza">
                                                                                ANAGRAFICA - Corrispondenza
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*indirizzo_principale') echo 'selected'; ?>
                                                                                    value="anagrafica*-*indirizzo_principale">
                                                                                ANAGRAFICA - Indirizzo Principale
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*indirizzo_attivo') echo 'selected'; ?>
                                                                                    value="anagrafica*-*indirizzo_attivo">
                                                                                ANAGRAFICA - Indirizzo Attivo
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*tipo_recapito') echo 'selected'; ?>
                                                                                    value="anagrafica*-*tipo_recapito">
                                                                                ANAGRAFICA - Tipo Recapito
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*recapito') echo 'selected'; ?>
                                                                                    value="anagrafica*-*recapito">
                                                                                ANAGRAFICA - Recapito
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*note_recapito') echo 'selected'; ?>
                                                                                    value="anagrafica*-*note_recapito">
                                                                                ANAGRAFICA - Note Recapito
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*recapito_corrispondenza') echo 'selected'; ?>
                                                                                    value="anagrafica*-*recapito_corrispondenza">
                                                                                ANAGRAFICA - Usa Per Invio
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*recapito_principale') echo 'selected'; ?>
                                                                                    value="anagrafica*-*recapito_principale">
                                                                                ANAGRAFICA - Recapito Principale
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*recapito_attivo') echo 'selected'; ?>
                                                                                    value="anagrafica*-*recapito_attivo">
                                                                                ANAGRAFICA - Recapito Attivo
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'anagrafica*-*evento_strutturato') echo 'selected'; ?>
                                                                                    value="anagrafica*-*evento_strutturato">
                                                                                ANAGRAFICA - Evento RIGA
                                                                            </option>
                                                                        </optgroup>
                                                                    </select>
                                                                    <div style="width: 40px; float: left; margin-right: 10px;"
                                                                         class="extra-field-<?php echo $i ?> field_position-<?php echo $i ?> <?php if (!$mostra_posizione) echo 'hidden'; ?>">
                                                                        <input name="posizione[]"
                                                                               class="form-control posizioni"
                                                                               value="<?php if (isset($_GET['config']) && $_GET['config'] != '') echo $configurazione_posizioni[$i]; else echo '0'; ?>"
                                                                               placeholder="POS">
                                                                    </div>
                                                                    <!--<input class="form-control posizioni" value="<?php /*if(isset($_GET['config']) && $_GET['config']!='') echo $configurazione_posizioni[$i]; */ ?>" type="hidden" name="posizione[]">-->
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div>
                                                                    <select name="funzione_pers[]"
                                                                            class="form-control select2me funzione_pers">
                                                                        <option <?php if (!isset($_GET['config']) || $_GET['config'] == '') echo 'selected'; ?>></option>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_fnc[$i - 200] == 'RIUTILIZZA') echo 'selected'; ?>
                                                                                value="RIUTILIZZA">RIUTILIZZA
                                                                        </option>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_fnc[$i - 200] == 'SOMMA') echo 'selected'; ?>
                                                                                value="SOMMA">SOMMA
                                                                        </option>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_fnc[$i - 200] == 'CONCATENA') echo 'selected'; ?>
                                                                                value="CONCATENA">CONCATENA
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4" style="line-height:44px;">
                                                                <div>
                                                                    <input name="colonne_pers[]" type="text"
                                                                           value="<?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_val[$i - 200] != '') echo urldecode($configurazione_val[$i - 200]); ?>"
                                                                           placeholder="Colonne da concatenare (separate da ; e anticipate da - nel caso di una sottrazione)"
                                                                           class="form-control colonne_pers">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12" style="line-height: 10px;">&nbsp;
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="row" style="margin-bottom: 20px"></div>
                                                    <div>
                                                        <div class="form-actions">
                                                            <div class="row">
                                                                <div class="col-md-offset-1 col-md-3">
                                                                    <button id="submit_button" type="button"
                                                                            onclick="if(verificaFile()){ verificaVarieMand(); } else
                                                                            { alert(' Verificare l\'estensione del file '); return false;} "
                                                                            class="btn btn-success"><i
                                                                                class="fa fa-check"></i> Salva
                                                                    </button>
                                                                    <button type="reset" class="btn btn-warning">
                                                                        Annulla
                                                                    </button>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <input style="float: left; margin-right: 3px;"
                                                                           id="nome_configurazione"
                                                                           name="nome_configurazione" type="text"
                                                                           value="" placeholder="Nome Configurazione"
                                                                           class="form-control input-medium">
                                                                    <button style="display:inline-block"
                                                                            onClick="if($('#nome_configurazione').val() != '') salva_configurazione($('#nome_configurazione').val()); else alert('Inserisci un nome valido per questa configurazione')"
                                                                            type="button" class="btn btn-primary"><i
                                                                                class="fa fa-download"></i> Salva
                                                                        Configurazione
                                                                    </button>
                                                                    <?php
                                                                    if (isset($_GET['config']) && $_GET['config'] != '') {
                                                                        ?>
                                                                        <button style="display:inline-block"
                                                                                onClick="modifica_configurazione(<?php echo cleanInput($_GET['config']); ?>);"
                                                                                type="button" class="btn btn-info"><i
                                                                                    class="fa fa-download"></i> Modifica
                                                                            Configurazione
                                                                        </button>
                                                                        <button style="display:inline-block; margin-left: 3px"
                                                                                onClick="elimina_configurazione(<?php echo cleanInput($_GET['config']); ?>);"
                                                                                type="button" class="btn btn-danger"><i
                                                                                    class="fa fa-trash-o"></i> Elimina
                                                                            Configurazione
                                                                        </button>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<?php require_once('elements/footer.php') ?>
<!-- END FOOTER -->

<?php require_once('include/javascript-end.php') ?>

<script>
    jQuery(document).ready(function () {
        App.init();
        FormSamples.init();
        FormComponents.init();
    });

    function verificaFile() {
        var filename = $('input[type=file]').val().split('\\').pop();
        var filenameArry = filename.split(".");
        var extension = filenameArry[filenameArry.length - 1]
        if ($('#tipo_file').val() == 1) {
            if (extension == 'xls' || extension == 'xlsx') {
                return true;
            } else {
                return false;
            }
        } else if ($('#tipo_file').val() == 2) {
            if (extension == 'csv') {
                return true;
            } else {
                return false;
            }
        } else if ($('#tipo_file').val() == 4) {
            if (extension == 'txt') {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
        return true;
    }


    function verificaVarieMand() {
        if ($('#valore_mandante').val() == 0) {

            swalConfirm('<b><p>Per la scelta "Varie Mandanti" è necessario utilizzare come RIFERIMENTO uno dei seguenti campi:</p>' +
                '<p>ID Anagrafica ReMida</p>' +
                '<p>Codice Fiscale/P.IVA</p>' +
                '<p>ID Pratica ReMida</p>' +
                '<p>Qualsiasi altro riferimento utilizzato non garantisce una corretta importazione dei dati e relativa esecuzione di eventi</p></b>', 'ATTENZIONE', 'warning', function () {
                $('#form_sample_22').submit();
                ;
            }, function () {
                return false;
            });
        } else {
            $('#form_sample_22').submit();
            placePageLoader();
        }

    }

    var line = <?php echo (isset($line) and is_numeric($line)) ? "$line" : '0' ?>;

    function displayOptionalFields(elem, index) {
        if (elem.val() == 'anagrafica*-*recapito') {
            $('.extra-field-' + index).addClass('hidden');
            $('.field_predefinito-' + index).removeClass('hidden');
            $('.field_rec_type-' + index).removeClass('hidden');
        } else if (elem.val() == 'anagrafica*-*indirizzo') {
            $('.extra-field-' + index).addClass('hidden');
            $('.field_predefinito-' + index).removeClass('hidden');
            $('.field_address_type-' + index).removeClass('hidden');
        } else {
            $('.extra-field-' + index).addClass('hidden');
        }

        // RIMOSSO PERCHè C'è UNA SOLA ANAGRAFICA PER RIGA

        if (elem.val() == 'anagrafica*-*indirizzo' ||
            elem.val() == 'anagrafica*-*cap' ||
            elem.val() == 'anagrafica*-*citta' ||
            elem.val() == 'anagrafica*-*provincia' ||
            elem.val() == 'anagrafica*-*nazione' ||
            elem.val() == 'anagrafica*-*recapito' ||
            elem.val() == 'anagrafica*-*tipo_indirizzo' ||
            elem.val() == 'anagrafica*-*tipo_recapito' ||
            elem.val() == 'anagrafica*-*note_recapito') {
            $('.field_position-' + index).removeClass('hidden');
        }

        if (elem.val() == 'anagrafica*-*tipo_indirizzo' ||
            elem.val() == 'anagrafica*-*indirizzo' ||
            elem.val() == 'anagrafica*-*cap' ||
            elem.val() == 'anagrafica*-*citta' ||
            elem.val() == 'anagrafica*-*provincia' ||
            elem.val() == 'anagrafica*-*nazione'
        ) {
            $('.field_address_position-' + index).removeClass('hidden');
        } else {
            $('.field_address_position-' + index).addClass('hidden');
        }
    }

    function displayMoreLines() {
        $('.line').each(function (index, element) {
            if ($(this).attr('data-line') >= line && $(this).attr('data-line') < (line + 10)) {
                $(this).css('display', '');

                $(this).find('select').each(function (index, element) {
                    $(this).select2({
                        placeholder: "Seleziona",
                        allowClear: true
                    });
                });
            }
        });

        line += 10;

        if (line >= 200)
            $('.aggiungi-campi').css('display', 'none')
    }

    function verifica_campi_required(valore) {
        if (valore == 1)
            $('#rigaPartenza').attr('required', 'required');
        else
            $('#rigaPartenza').removeAttr('required');

        if (valore == 2)
            $('#delimitatore').attr('required', 'required');
        else
            $('#delimitatore').removeAttr('required');

        $('.options').css('display', 'none');
        if (valore == 1) {
            $('.options.riga').css('display', '');
        } else if (valore == 2) {
            $('.options.riga').css('display', '');
            $('.options.delimitatore').css('display', '');
        } else if (valore == 4) {
            $('.options.riga').css('display', '');
            $('.options.lunghezzaPosizioni').css('display', '');
        }
    }

    function salva_configurazione(desc) {
        var recapiti_predefiniti = '';
        $('.recapiti_predefiniti').each(function (index, element) {
            if ($(this).is(':checked') == true) {
                recapiti_predefiniti += '1;';
            } else {
                recapiti_predefiniti += '0;';
            }
        });

        var input_mandante = $('#valore_mandante').val();

        var input_tipo_file = $('#tipo_file').val();
        var input_riga = $('#rigaPartenza').val();
        var input_delimitatore = $('#delimitatore').val();
        var input_lunghezzaPosizioni = $('#lunghezzaPosizioni').val();
        var input_formato_valuta = $('#formato_valuta').val();
        var input_formato_data = $('#formato_data').val();

        var input_evento_strutturato_indirizzi = $('#evento_strutturato_predefinito_indirizzi').val();
        var input_evento_strutturato_recapiti = $('#evento_strutturato_predefinito_recapiti').val();
        var input_ind_predefinito = 0;
        if ($('#ind_predefinito').is(':checked'))
            input_ind_predefinito = 1;
        var ind_ind_corrispondenza = 0;
        if ($('#ind_corrispondenza_predefinito').is(':checked'))
            ind_ind_corrispondenza = 1;
        var input_tipo_ind_predefinito = $('#tipo_indirizzo_predefinito').val();
        var input_ind_attivo = 0;
        if ($('#ind_primario_attivo').is(':checked'))
            input_ind_attivo = 1;
        sovrascrivi_indirizzo = 0;
        if ($('#sovrascrivi_indirizzo').is(':checked'))
            sovrascrivi_indirizzo = 1;
        sovrascrivi_recapito = 0;
        if ($('#sovrascrivi_recapito').is(':checked'))
            sovrascrivi_recapito = 1;

        var input_rec_predefinito = 0;
        if ($('#rec_predefinito').is(':checked'))
            input_rec_predefinito = 1;
        var ind_rec_invio = 0;
        if ($('#rec_invio_predefinito').is(':checked'))
            ind_rec_invio = 1;
        var input_tipo_rec_predefinito = $('#tipo_recapito_predefinito').val();
        var input_rec_attivo = 0;
        if ($('#rec_primario_attivo').is(':checked'))
            input_rec_attivo = 1;
        var input_fonte_predefinito = $('#fonte_predefinito').val();
        var input_data_validita = $('#fixed_data_validita').val();
        var input_campi = $('.campi').serialize();
        var input_posizioni = $('.posizioni').serialize();
        var input_posizioni_indirizzo = $('.posizioni_indirizzo').serialize();
        var input_recapiti_predefiniti = recapiti_predefiniti;
        var input_tipologie_collegato = $('.tipologie_collegato').serialize();
        var input_tipologie_indirizzo = $('.tipologie_indirizzo').serialize();
        var input_tipologie_recapito = $('.tipologie_recapito_dett').serialize();
        var input_funzione_pers = $('.funzione_pers').serialize();
        var input_colonne_pers = $('.colonne_pers').serialize();
        var input_stato_sostituto = $('#stato_sostituto').val();
        var input_stati_esclusi = '';
        if ($('#stati_esclusi').val() != '' && $('#stati_esclusi').val() != null)
            input_stati_esclusi = $('#stati_esclusi').val().join(',');

        //console.log(input_campi);
        //console.log(input_posizioni);

        $.ajax({
            url: "form_actions.php",
            type: "POST",
            data: {
                action: 'salva-configurazione-acquisizione-indirizzi-recapiti',
                id_mandante: input_mandante,
                tipo_file: input_tipo_file,
                riga: input_riga,
                delimitatore: input_delimitatore,
                lunghezzaPosizioni: input_lunghezzaPosizioni,
                formato_valuta: input_formato_valuta,
                formato_data: input_formato_data,
                evento_strutturato_indirizzo: input_evento_strutturato_indirizzi,
                evento_strutturato_recapito: input_evento_strutturato_recapiti,
                indirizzo_predefinito: input_ind_predefinito,
                indirizzo_corrispondenza: ind_ind_corrispondenza,
                tipo_indirizzo: input_tipo_ind_predefinito,
                indirizzo_attivo: input_ind_attivo,
                sovrascrivi_indirizzo: sovrascrivi_indirizzo,
                sovrascrivi_recapito: sovrascrivi_recapito,
                recapito_predefinito: input_rec_predefinito,
                recapito_invio: ind_rec_invio,
                tipo_recapito: input_tipo_rec_predefinito,
                recapito_attivo: input_rec_attivo,
                fonte_predefinita: input_fonte_predefinito,
                data_validita: input_data_validita,
                campi: input_campi,
                posizioni: input_posizioni,
                posizioni_indirizzo: input_posizioni_indirizzo,
                recapiti_predefiniti: input_recapiti_predefiniti,
                tipologie_collegato: input_tipologie_collegato,
                tipologie_indirizzo: input_tipologie_indirizzo,
                tipologie_recapito: input_tipologie_recapito,
                funzione_pers: input_funzione_pers,
                colonne_pers: input_colonne_pers,
                descrizione: desc,
                stato_sostituto: input_stato_sostituto,
                stati_esclusi: input_stati_esclusi
            },
            success: function (result) {
                alert(result);
            },
            error: function (richiesta, stato, errori) {
                console.log('ERRORE NELL\'ESECUZIONE DEL FILTRO');
            }
        });
    }

    function modifica_configurazione(identity) {
        var recapiti_predefiniti = '';
        $('.recapiti_predefiniti').each(function (index, element) {
            if ($(this).is(':checked') == true) {
                recapiti_predefiniti += '1;';
            } else {
                recapiti_predefiniti += '0;';
            }
        });

        var input_mandante = $('#valore_mandante').val();

        var input_tipo_file = $('#tipo_file').val();
        var input_riga = $('#rigaPartenza').val();
        var input_delimitatore = $('#delimitatore').val();
        var input_lunghezzaPosizioni = $('#lunghezzaPosizioni').val();
        var input_formato_valuta = $('#formato_valuta').val();
        var input_formato_data = $('#formato_data').val();

        var input_evento_strutturato_indirizzi = $('#evento_strutturato_predefinito_indirizzi').val();
        var input_evento_strutturato_recapiti = $('#evento_strutturato_predefinito_recapiti').val();
        var input_ind_predefinito = 0;
        if ($('#ind_predefinito').is(':checked'))
            input_ind_predefinito = 1;
        var ind_ind_corrispondenza = 0;
        if ($('#ind_corrispondenza_predefinito').is(':checked'))
            ind_ind_corrispondenza = 1;
        var input_tipo_ind_predefinito = $('#tipo_indirizzo_predefinito').val();
        var input_ind_attivo = 0;
        if ($('#ind_primario_attivo').is(':checked'))
            input_ind_attivo = 1;
        sovrascrivi_indirizzo = 0;
        if ($('#sovrascrivi_indirizzo').is(':checked'))
            sovrascrivi_indirizzo = 1;
        sovrascrivi_recapito = 0;
        if ($('#sovrascrivi_recapito').is(':checked'))
            sovrascrivi_recapito = 1;
        var input_rec_predefinito = 0;
        if ($('#rec_predefinito').is(':checked'))
            input_rec_predefinito = 1;
        var ind_rec_invio = 0;
        if ($('#rec_invio_predefinito').is(':checked'))
            ind_rec_invio = 1;
        var input_tipo_rec_predefinito = $('#tipo_recapito_predefinito').val();
        var input_rec_attivo = 0;
        if ($('#rec_primario_attivo').is(':checked'))
            input_rec_attivo = 1;
        var input_fonte_predefinito = $('#fonte_predefinito').val();
        var input_data_validita = $('#fixed_data_validita').val();
        var input_campi = $('.campi').serialize();
        var input_posizioni = $('.posizioni').serialize();
        var input_posizioni_indirizzo = $('.posizioni_indirizzo').serialize();
        var input_recapiti_predefiniti = recapiti_predefiniti;
        var input_tipologie_collegato = $('.tipologie_collegato').serialize();
        var input_tipologie_indirizzo = $('.tipologie_indirizzo').serialize();
        var input_tipologie_recapito = $('.tipologie_recapito_dett').serialize();
        var input_funzione_pers = $('.funzione_pers').serialize();
        var input_colonne_pers = $('.colonne_pers').serialize();
        var input_stato_sostituto = $('#stato_sostituto').val();
        var input_stati_esclusi = '';
        if ($('#stati_esclusi').val() != '' && $('#stati_esclusi').val() != null)
            input_stati_esclusi = $('#stati_esclusi').val().join(',');

        //console.log(input_campi);
        //console.log(input_posizioni);

        $.ajax({
            url: "form_actions.php",
            type: "POST",
            data: {
                action: 'modifica-configurazione-acquisizione-indirizzi-recapiti',
                id: identity,
                id_mandante: input_mandante,
                tipo_file: input_tipo_file,
                riga: input_riga,
                delimitatore: input_delimitatore,
                lunghezzaPosizioni: input_lunghezzaPosizioni,
                formato_valuta: input_formato_valuta,
                formato_data: input_formato_data,
                evento_strutturato_indirizzo: input_evento_strutturato_indirizzi,
                evento_strutturato_recapito: input_evento_strutturato_recapiti,
                indirizzo_predefinito: input_ind_predefinito,
                indirizzo_corrispondenza: ind_ind_corrispondenza,
                tipo_indirizzo: input_tipo_ind_predefinito,
                indirizzo_attivo: input_ind_attivo,
                sovrascrivi_indirizzo: sovrascrivi_indirizzo,
                sovrascrivi_recapito: sovrascrivi_recapito,
                recapito_predefinito: input_rec_predefinito,
                recapito_invio: ind_rec_invio,
                tipo_recapito: input_tipo_rec_predefinito,
                recapito_attivo: input_rec_attivo,
                fonte_predefinita: input_fonte_predefinito,
                data_validita: input_data_validita,
                campi: input_campi,
                posizioni: input_posizioni,
                posizioni_indirizzo: input_posizioni_indirizzo,
                recapiti_predefiniti: input_recapiti_predefiniti,
                tipologie_collegato: input_tipologie_collegato,
                tipologie_indirizzo: input_tipologie_indirizzo,
                tipologie_recapito: input_tipologie_recapito,
                posizioni: input_posizioni,
                posizioni: input_posizioni,
                posizioni: input_posizioni,
                funzione_pers: input_funzione_pers,
                colonne_pers: input_colonne_pers,
                stato_sostituto: input_stato_sostituto,
                stati_esclusi: input_stati_esclusi
            },
            success: function (result) {
                alert(result);
            },
            error: function (richiesta, stato, errori) {
                console.log('ERRORE NELL\'ESECUZIONE DEL FILTRO');
            }
        });
    }

    function elimina_configurazione(identity) {
        if (confirm('Siete sicuri di voler eliminare la configurazione selezionata?')) {
            $.ajax({
                url: "form_actions.php",
                type: "POST",
                data: {
                    action: 'elimina-configurazione-acquisizione-indirizzi-recapiti',
                    id: identity
                },
                success: function (result) {
                    alert(result);
                },
                error: function (richiesta, stato, errori) {
                    console.log('ERRORE NELL\'ESECUZIONE DEL FILTRO');
                }
            });
        }
    }

    function compila_configurazioni(id) {
        $.ajax({
            url: "form_actions.php",
            type: "POST",
            data: {action: 'ricerca-configurazioni-da-mandante-indirizzi-recapiti', id_mandante: id},
            success: function (result) {
                //console.log(result);
                $('#inserimento_configurazione').html(result)
            },
            error: function (richiesta, stato, errori) {
                console.log('ERRORE NELL\'ESECUZIONE DEL FILTRO');
            }
        });
    }

    function verifyCheckboxes() {
        $('.recapiti_predefiniti').each(function (index, element) {
            var idVal = $(this).attr('data-value');

            if ($(this).is(':checked')) {
                $('#checkHidden' + idVal).attr('disabled', 'disabled');
            }
        });

        $('.recapiti_attivi').each(function (index, element) {
            var idVal = $(this).attr('data-value');

            if ($(this).is(':checked')) {
                $('#checkHiddenActive' + idVal).attr('disabled', 'disabled');
            }
        });

        $('.ind_primario_attivo').each(function (index, element) {
            if ($(this).is(':checked')) {
                $('#checkHiddenIndAct').attr('disabled', 'disabled');
            }
        });
        $('.sovrascrivi_indirizzo').each(function (index, element) {
            if ($(this).is(':checked')) {
                $('#checkHiddenIndSovrascrivi').attr('disabled', 'disabled');
            }
        });
        $('.sovrascrivi_recapito').each(function (index, element) {
            if ($(this).is(':checked')) {
                $('#checkHiddenRecSovrascrivi').attr('disabled', 'disabled');
            }
        });

        $('.rec_primario_attivo').each(function (index, element) {
            if ($(this).is(':checked')) {
                $('#checkHiddenRecAct').attr('disabled', 'disabled');
            }
        });
        $('.ind_primario_predefinito').each(function (index, element) {
            if ($(this).is(':checked')) {
                $('#checkHiddenIndPred').attr('disabled', 'disabled');
            }
        });

        $('.rec_primario_predefinito').each(function (index, element) {
            if ($(this).is(':checked')) {
                $('#checkHiddenRecPred').attr('disabled', 'disabled');
            }
        });

        $('.ind_corrispondenza_predefinito').each(function (index, element) {
            if ($(this).is(':checked')) {
                $('#checkHiddenCorrisp').attr('disabled', 'disabled');
            }
        });

        $('.rec_invio_predefinito').each(function (index, element) {
            if ($(this).is(':checked')) {
                $('#checkHiddenRecCorrisp').attr('disabled', 'disabled');
            }
        });
    }

    $("input.select2_mandanti").select2({
        placeholder: "Seleziona Mandante",
        allowClear: true,
        minimumInputLength: 3,
        query: function (query) {
            var data = {
                results: []
            };

            $.ajax({
                url: "form_actions.php",
                type: "POST",
                data: {action: 'ricerca-mandante-con-varie', utente: query.term},
                dataType: "json",
                success: function (result) {
                    //console.log(result);
                    var size = Object.keys(result).length
                    //console.log(size)
                    for (var k = 0; k < size; k++) {
                        console.log(result[k].id);
                        data.results.push({
                            id: result[k]['id'],
                            text: result[k]['text']
                        });
                    }
                    console.log(data)
                    query.callback(data);
                },
                error: function (richiesta, stato, errori) {
                    console.log('ERRORE NELL\'ESECUZIONE DEL FILTRO');
                    console.log(data);
                }
            });
        }
    });

    $("input.select2_eventi_strutturati").select2({
        placeholder: "Seleziona Mandante",
        allowClear: true,
        minimumInputLength: 3,
        query: function (query) {
            var data = {
                results: []
            };

            $.ajax({
                url: "form_actions.php",
                type: "POST",
                data: {action: 'ricerca-strutturati', evento: query.term},
                dataType: "json",
                success: function (result) {
                    //console.log(result);
                    var size = Object.keys(result).length
                    //console.log(size)
                    for (var k = 0; k < size; k++) {
                        console.log(result[k].id);
                        data.results.push({
                            id: result[k]['id'],
                            text: result[k]['text']
                        });
                    }
                    console.log(data)
                    query.callback(data);
                },
                error: function (richiesta, stato, errori) {
                    console.log('ERRORE NELL\'ESECUZIONE DEL FILTRO');
                    console.log(data);
                }
            });
        }
    });

    <?php
    if(isset($_GET['config']) && $_GET['config'] != '') {
    $mandante = mysql_fetch_array(db_query('SELECT * FROM utente WHERE id_utente = "' . $configurazione['id_mandante'] . '"'));
    ?>
    $(".select2_mandanti").find('span.select2-chosen').html('<?php echo $mandante['cognome'] . ' ' . $mandante['nome'] . ' (' . $mandante['codice_fiscale'] . ')' ?>');
    <?php
    }
    if(isset($_GET['config']) && $_GET['config'] != '') {
    $evento_strutturato_indirizzo = mysql_fetch_array(db_query('SELECT id, descrizione FROM eventi_strutturati WHERE id = "' . $configurazione['evento_strutturato_indirizzo'] . '"'));
    if( $configurazione['evento_strutturato_indirizzo'] > 0){
    ?>
    /*$("#s2id_evento_strutturato_predefinito_indirizzi").find('span.select2-chosen').html('<?php //echo $evento_strutturato_indirizzo['descrizione'] ?>');*/
    $("#s2id_evento_strutturato_predefinito_indirizzi").select2('data', {
        id:<?php echo $configurazione['evento_strutturato_indirizzo'];?>,
        text: '<?php echo $evento_strutturato_indirizzo['descrizione'] ?>'
    });
    <?php
    }
    }
    if(isset($_GET['config']) && $_GET['config'] != '') {
    $evento_strutturato_recapito = mysql_fetch_array(db_query('SELECT id, descrizione FROM eventi_strutturati WHERE id = "' . $configurazione['evento_strutturato_recapito'] . '"'));
    if( $configurazione['evento_strutturato_recapito'] > 0){

    ?>
    /*$("#s2id_evento_strutturato_predefinito_recapiti").find('span.select2-chosen').html('<?php //echo $evento_strutturato_recapito['descrizione'] ?>');*/
    $("#s2id_evento_strutturato_predefinito_recapiti").select2('data', {
        id:<?php echo $configurazione['evento_strutturato_recapito'];?>,
        text: '<?php echo $evento_strutturato_recapito['descrizione'] ?>'
    });
    <?php
    }
    }
    ?>

    setTimeout(function () {
        $('#loader1').hide()
    }, 1500);
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>