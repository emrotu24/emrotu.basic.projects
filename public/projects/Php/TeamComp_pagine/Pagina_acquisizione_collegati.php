<?php
$flagEventoAutomatico = 1;

if(isset($_POST['file_esistente']) && $_POST['file_esistente'] != '') {
    if (!file_exists(__DIR__.'/no-sync/log_acquisizione_dati_ext')) {
        mkdir(__DIR__.'/no-sync/log_acquisizione_dati_ext', 0755, TRUE);
    }
    $myfile = fopen(__DIR__."/no-sync/log_acquisizione_dati_ext/log_".date('YmdHis').".txt", "w") or die("Unable to open file!");
    $txt = json_encode($_POST);
    fwrite($myfile, $txt);
    fclose($myfile);

    $colonnePers = explode(';',$_POST['colonne_pers']);
    for($i=0; $i<count($colonnePers); $i++) {
        $colonnePers[$i] = urldecode($colonnePers[$i]);
    }

    $_POST = array(
        'test' =>                                   $_POST['test'],
        'mandante' =>                               $_POST['mandante'],
        'evento_strutturato_predefinito_new' =>     $_POST['evento_strutturato_predefinito_new'],
        'tipo_file' =>                              $_POST['tipo_file'],
        'rigaPartenza' =>                           $_POST['rigaPartenza'],
        'formato_valuta' =>                         $_POST['formato_valuta'],
        'formato_data' =>                           $_POST['formato_data'],
        'delimitatore' =>                           $_POST['delimitatore'],
        'lunghezzaPosizioni' =>                     $_POST['lunghezzaPosizioni'],
        'natura_credito' =>                         $_POST['natura_credito'],
        'id_filiale' =>                             $_POST['id_filiale'],
        'ind_predefinito' =>                        $_POST['ind_predefinito'],
        'aggiorna_lotto_studio' =>                  $_POST['aggiorna_lotto_studio'],
        'acquiszione_riprendi_pratiche' =>          $_POST['acquiszione_riprendi_pratiche'],
        'contratto' =>                              $_POST['contratto'],
        'applica_quando_contratto' =>               explode(';',$_POST['applica_quando_contratto']),
        'applica_quando_campo' =>                   explode(';',$_POST['applica_quando_campo']),
        'applica_quando_operatore' =>               explode(';',urldecode($_POST['applica_quando_operatore'])),
        'applica_quando_valore' =>                  explode(';',$_POST['applica_quando_valore']),
        'lotto_mandante' =>                         $_POST['lotto_mandante'],
        'campo' =>                                  explode(';',$_POST['campo']),
        'posizione' =>                              explode(';',$_POST['posizione']),
        'posizione_indirizzo' =>                    explode(';',$_POST['posizione_indirizzo']),
        'rec_predefinito' =>                        explode(';',$_POST['rec_predefinito']),
        'address_type' =>                           explode(';',$_POST['address_type']),
        'linked_type' =>                            explode(';',$_POST['linked_type']),
        'funzione_pers' =>                          explode(';',$_POST['funzione_pers']),
        'colonne_pers' =>                           $colonnePers,
        'azione_post_exec' =>                       $_POST['azione_post_exec'],
        'file_esistente' =>                         $_POST['file_esistente']
    );
}

// TODO: Verificare funzionamento di $countAnagraficaCollegatiPratica e $countAnagraficaCollegatiMandante
ini_set('precision', 20);

require_once('config/config.php');
require_once('form_actions.php');

// if (isAcquisizioneDatiInstalled() && isset($_GET['t']) && $_GET['t'] == 1) {
//     require_once(__DIR__ . '/../remida-acquisizione-dati/acquisizione_dati.php');
//     die();
// }


//require_once('verifica_utente.php');
//  Include PHPExcel_IOFactory
require_once('assets/plugins/PHPExcel/Classes/PHPExcel.php');

if (isset($_POST['evento_strutturato_predefinito_new']) && $_POST['evento_strutturato_predefinito_new'] > 0) {
    writeSession('evento_strutturato_predefinito_new', $_POST['evento_strutturato_predefinito_new']);
}

$globalLottoMandante = 0;

$test_mode = 0;
ini_set('display_errors', $test_mode);
error_reporting(E_ALL | E_STRICT);

$session = $_SESSION;

// VARIABILI PER LA GESTIONE GRAFICA DELL'EVIDENZIAZIONE DELLA PAGINA CORRETTA NELLA SIDEBAR
$categoria = '_import_export';
$pagina = 'acquisizione';

// VARIABILI PER LA GESTIONE DELL'ESECUZIONE EVENTI
$imported_practices = array();
$updated_practices = array();

$pratica_creata;

$debug = false;

$lotto_studio;


// Array destinato al rollback di tutte le modifiche effettuate dall'operazione
// L'array dovrà essere ciclato al contrario eseguendo le query salvate
$array_rollback_acquisizione = array();

function printMessage($message)
{
    echo ' ';
    print_r($message);
    echo '<br>';
}

function rollbackOperations($errorString)
{
    global $array_rollback_acquisizione;
    global $imported_practices;
    global $updated_practices;
    global $id_pratica;

    echo '<div class="rollback">';
    echo '<br><br>';
    echo 'ERRORE RILEVATO DURANTE L\'ACQUISIZIONE.<br>';
    echo 'L\'ERRORE RIPORTA IL SEGUENTE MESSAGGIO:<br>';

    print_r($errorString);

    echo '<br><br>';
    echo 'AVVIO DELLE QUERY DI ROLLBACK:<br>';

    for ($i = count($array_rollback_acquisizione) - 1; $i >= 0; $i--) {
        $query_rollback = $array_rollback_acquisizione[$i];
        echo '&nbsp;&nbsp;&nbsp;&nbsp;' . $query_rollback . '<br>';
        db_query($query_rollback) or printMessage(mysql_error());
    }

    echo 'ROLLBACK EFFETTUATO CORRETTAMENTE';

    echo '<script type="text/javascript">$("#loader1").hide();</script>';
    echo '</div>';

    $imported_practices = array();
    $updated_practices = array();

    $id_pratica = '';

    die();
}

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
                return substr($input, 4, 1) . substr($input, 5, 1) . substr($input, 6, 1) . substr($input, 7, 1) . '-' . substr($input, 2, 1) . substr($input, 3, 1) . '-' . substr($input, 0, 1) . substr($input, 1, 1);
            else if ($input != '')
                return date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($input));
        } else if ($_POST['formato_data'] == 'dd mm YYYY') {
            if (strpos($input, ' ') > 0)
                return date('Y-m-d', strtotime(str_replace(' ', '-', $input)));
            else if ($input != '')
                return date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($input));
        } else if ($_POST['formato_data'] == 'ddmmyy') {
            if (strlen($input) < 6) {
                $input = '0' . $input;
            }
            $chunks = str_split($input, 2);
            $giorno = $chunks[0];
            $mese = $chunks[1];
            $anno = $chunks[2] > date('y', strtotime('+10 years')) ? '19' . $chunks[2] : '20' . $chunks[2];


            if (strlen($input) == 6)
                return $anno . '-' . $mese . '-' . $giorno;
            else if ($input != '')
                return date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($input));
        } else if ($_POST['formato_data'] == 'yymmdd') {
            $chunks = str_split($input, 2);

            $giorno = $chunks[2];
            $mese = $chunks[1];
            $anno = $chunks[0] > date('y', strtotime('+10 years')) ? '19' . $chunks[0] : '20' . $chunks[0];

            if (strlen($input) == 6)
                return $anno . '-' . $mese . '-' . $giorno;
            else if ($input != '')
                return date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($input));
        } else if ($_POST['formato_data'] == 'dd-mm-YYYY') {
            if (strpos($input, '-') > 0)
                return date('Y-m-d', strtotime($input));
            else if ($input != '')
                return date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($input));
        } else if ($_POST['formato_data'] == 'dd.mm.YYYY') {
            if (strpos($input, '.') > 0)
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
                return substr($input, 0, 1) . substr($input, 1, 1) . substr($input, 2, 1) . substr($input, 3, 1) . '-' . substr($input, 4, 1) . substr($input, 5, 1) . '-' . substr($input, 6, 1) . substr($input, 7, 1);
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

function containsDecimal($value)
{
    if (strpos($value, ".") !== false) {
        return true;
    }
    return false;
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
        if (containsDecimal($input)) {
            return $input;
        } else {
            return substr($input, 0, ($length - 1)) . '.' . substr($input, -1);
        }
    } // INTERO DI CUI 2 DECIMALI
    else if ($_POST['formato_valuta'] == '3') {
        $length = strlen($input);
        if (containsDecimal($input)) {
            return $input;
        } else {

            return substr($input, 0, ($length - 2)) . '.' . substr($input, -2);
        }
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

global $funzioni;
function calcola_risultante($dati, $occorrenza, $funzioni, $colonne)
{
    if ($occorrenza >= 300) {
        $risultante = '';
        
        if ($funzioni[$occorrenza - 300] == 'SOMMA') {
            $risultante = 0;
            $colonne_pers = explode(';', $colonne[$occorrenza - 300]);
            for ($g = 0; $g < count($colonne_pers); $g++) {
                if (mb_substr($colonne_pers[$g], 0, 1, 'UTF-8') == '-') {
                    $risultante -= converti_importo($dati[str_replace('-', '', $colonne_pers[$g]) - 1]);
                } else {
                    $risultante += converti_importo($dati[$colonne_pers[$g] - 1]);
                }
            }

            if (!containsDecimal($risultante)) {
                if ($_POST['formato_valuta'] == '2') {
                    $risultante .= '0';
                    
                } // INTERO DI CUI 2 DECIMALI
                else if ($_POST['formato_valuta'] == '3') {
                    $risultante .= '00';

                }
            }
        } else if ($funzioni[$occorrenza - 300] == 'RIUTILIZZA') {
            $risultante = $dati[$colonne[$occorrenza - 300] - 1];
        } else if ($funzioni[$occorrenza - 300] == 'CONCATENA') {
            $colonne_pers = explode(';', $colonne[$occorrenza - 300]);
            for ($g = 0; $g < count($colonne_pers); $g++) {
                if ($colonne_pers[$g] == '')
                    $risultante .= ' ';
                else if (!is_numeric($colonne_pers[$g]))
                    $risultante .= $colonne_pers[$g];
                else
                $risultante .= $dati[$colonne_pers[$g] - 1];
        }
        $risultante = trim($risultante);
        }
    } else {
        $risultante = $dati[$occorrenza];
    }
    
    return db_input(trim(trim($risultante),'"'));
}

print_r($funzioni[$occorrenza - 300]);

function aggiornaTotaliPratica($id_pratica, $dett_pratica)
{
    // AGGIORNO I TOTALI DELLA PRATICA PRIMA DEL CAMBIO PRATICA E CALCOLO GLI ONERI SUI TITOLI INSERITI

    // RECUPERO TUTTI I VALORI DEI TITOLI PER POI RICALCOLARE GLI IMPORTI
    $queryAGG = 'SELECT capitale,spese,interessi,affidato_1,affidato_2,affidato_3,oneri_studio,spese_incasso
						  FROM pratiche_insoluto
						  WHERE id_pratica = "' . $id_pratica . '" AND data_scadenza <= "' . date("Y-m-d") . '"';

    $ris_insAGG = db_query($queryAGG);

    // RECUPERO I VALORI DELLA PRATICA CHE ANDRO' A SOMMARE CON QUELLI DEI TITOLI
    $affidato_capitaleAGG = (isset($dett_pratica[0]['affidato_capitale']) && $dett_pratica[0]['affidato_capitale'] > 0) ? $dett_pratica[0]['affidato_capitale'] : 0;
    $affidato_speseAGG = (isset($dett_pratica[0]['affidato_spese']) && $dett_pratica[0]['affidato_spese'] > 0) ? $dett_pratica[0]['affidato_spese'] : 0;
    $affidato_interessiAGG = (isset($dett_pratica[0]['affidato_interessi']) && $dett_pratica[0]['affidato_interessi'] > 0) ? $dett_pratica[0]['affidato_interessi'] : 0;
    $affidato_1AGG = (isset($dett_pratica[0]['affidato_1']) && $dett_pratica[0]['affidato_1'] > 0) ? $dett_pratica[0]['affidato_1'] : 0;
    $affidato_2AGG = (isset($dett_pratica[0]['affidato_2']) && $dett_pratica[0]['affidato_2'] > 0) ? $dett_pratica[0]['affidato_2'] : 0;
    $affidato_3AGG = (isset($dett_pratica[0]['affidato_3']) && $dett_pratica[0]['affidato_3'] > 0) ? $dett_pratica[0]['affidato_3'] : 0;
    $competenze_oneri_recuperoAGG = (isset($dett_pratica[0]['competenze_oneri_recupero']) && $dett_pratica[0]['competenze_oneri_recupero'] > 0) ? $dett_pratica[0]['competenze_oneri_recupero'] : 0;
    $competenze_spese_incassoAGG = (isset($dett_pratica[0]['competenze_spesse_incasso']) && $dett_pratica[0]['competenze_spesse_incasso'] > 0) ? $dett_pratica[0]['competenze_spesse_incasso'] : 0;

    while ($risAGG = mysql_fetch_array($ris_insAGG)) {
        $affidato_capitaleAGG += $risAGG['capitale'];
        $affidato_speseAGG += $risAGG['spese'];
        $affidato_interessiAGG += $risAGG['interessi'];
        $affidato_1AGG += $risAGG['affidato_1'];
        $affidato_2AGG += $risAGG['affidato_2'];
        $affidato_3AGG += $risAGG['affidato_3'];
        $competenze_oneri_recuperoAGG += $risAGG['oneri_studio'];
        $competenze_spese_incassoAGG += $risAGG['spese_incasso'];
    }

    $query_aggiornamento_importi_praticaAGG = 'UPDATE pratiche
													   SET affidato_capitale = "' . db_input($affidato_capitaleAGG) . '",
														   affidato_spese = "' . db_input($affidato_speseAGG) . '",
														   affidato_interessi = "' . db_input($affidato_interessiAGG) . '",
														   affidato_1 = "' . db_input($affidato_1AGG) . '",
														   affidato_2 = "' . db_input($affidato_2AGG) . '",
														   affidato_3 = "' . db_input($affidato_3AGG) . '",
														   competenze_oneri_recupero = "' . db_input($competenze_oneri_recuperoAGG) . '",
														   competenze_spese_incasso = "' . db_input($competenze_spese_incassoAGG) . '"
														   WHERE id = "' . db_input($id_pratica) . '"';
    // echo $query_aggiornamento_importi_praticaAGG.'<br>';

    $ris_aggiornamento_importi_praticaAGG = db_query($query_aggiornamento_importi_praticaAGG);

    // INSERIMENTO QUOTE PERSONALIZZATE
    praticheInsoluto_insertInsolutoFromQuotePersonalizzate($id_pratica, true);
    praticheInsoluto_insertInsolutoFromFrazionateQuotePersonalizzate($id_pratica, true);

    // AGGIORNO NUOVAMENTE LA PRATICA
    {
        $queryAGG = 'SELECT capitale,spese,interessi,affidato_1,affidato_2,affidato_3,oneri_studio,spese_incasso
							  FROM pratiche_insoluto
							  WHERE id_pratica = "' . $id_pratica . '" AND data_scadenza <= "' . date("Y-m-d") . '"';
        //echo $queryAGG.'<br>';

        $ris_insAGG = db_query($queryAGG);

        // RECUPERO I VALORI DELLA PRATICA CHE ANDRO' A SOMMARE CON QUELLI DEI TITOLI
        $affidato_capitaleAGG = (isset($dett_pratica[0]['affidato_capitale']) && $dett_pratica[0]['affidato_capitale'] > 0) ? $dett_pratica[0]['affidato_capitale'] : 0;
        $affidato_speseAGG = (isset($dett_pratica[0]['affidato_spese']) && $dett_pratica[0]['affidato_spese'] > 0) ? $dett_pratica[0]['affidato_spese'] : 0;
        $affidato_interessiAGG = (isset($dett_pratica[0]['affidato_interessi']) && $dett_pratica[0]['affidato_interessi'] > 0) ? $dett_pratica[0]['affidato_interessi'] : 0;
        $affidato_1AGG = (isset($dett_pratica[0]['affidato_1']) && $dett_pratica[0]['affidato_1'] > 0) ? $dett_pratica[0]['affidato_1'] : 0;
        $affidato_2AGG = (isset($dett_pratica[0]['affidato_2']) && $dett_pratica[0]['affidato_2'] > 0) ? $dett_pratica[0]['affidato_2'] : 0;
        $affidato_3AGG = (isset($dett_pratica[0]['affidato_3']) && $dett_pratica[0]['affidato_3'] > 0) ? $dett_pratica[0]['affidato_3'] : 0;
        $competenze_oneri_recuperoAGG = (isset($dett_pratica[0]['competenze_oneri_recupero']) && $dett_pratica[0]['competenze_oneri_recupero'] > 0) ? $dett_pratica[0]['competenze_oneri_recupero'] : 0;
        $competenze_spese_incassoAGG = (isset($dett_pratica[0]['competenze_spesse_incasso']) && $dett_pratica[0]['competenze_spesse_incasso'] > 0) ? $dett_pratica[0]['competenze_spesse_incasso'] : 0;

        while ($risAGG = mysql_fetch_array($ris_insAGG)) {
            $affidato_capitaleAGG += $risAGG['capitale'];
            $affidato_speseAGG += $risAGG['spese'];
            $affidato_interessiAGG += $risAGG['interessi'];
            $affidato_1AGG += $risAGG['affidato_1'];
            $affidato_2AGG += $risAGG['affidato_2'];
            $affidato_3AGG += $risAGG['affidato_3'];
            $competenze_oneri_recuperoAGG += $risAGG['oneri_studio'];
            $competenze_spese_incassoAGG += $risAGG['spese_incasso'];
        }

        $query_aggiornamento_importi_praticaAGG = 'UPDATE pratiche
														   SET affidato_capitale = "' . db_input($affidato_capitaleAGG) . '",
															   affidato_spese = "' . db_input($affidato_speseAGG) . '",
															   affidato_interessi = "' . db_input($affidato_interessiAGG) . '",
															   affidato_1 = "' . db_input($affidato_1AGG) . '",
															   affidato_2 = "' . db_input($affidato_2AGG) . '",
															   affidato_3 = "' . db_input($affidato_3AGG) . '",
															   competenze_oneri_recupero = "' . db_input($competenze_oneri_recuperoAGG) . '",
															   competenze_spese_incasso = "' . db_input($competenze_spese_incassoAGG) . '"
															   WHERE id = "' . $id_pratica . '"';
        // echo $query_aggiornamento_importi_praticaAGG.'<br>';

        $ris_aggiornamento_importi_praticaAGG = db_query($query_aggiornamento_importi_praticaAGG);
    }

    //FINE AGGIORNAMENTO TOTALI DELLA PRATICA PRIMA DEL CAMBIO PRATICA
    valutaObiettiviPraticaContratto($id_pratica);

}

function recuperaAzioni($idMandante) {
    $azioni = [];

    $query = "SELECT * 
                FROM acquisizione_dati_azioni 
                WHERE attivo = 1 
                  AND id_mandante = '0'";

    if($idMandante>0) {
        $query = "SELECT * 
                FROM acquisizione_dati_azioni 
                WHERE attivo = 1 
                  AND (id_mandante = '0' OR id_mandante = '".db_input($idMandante)."')";
    }

    $ris_azioni = db_query($query);

    if(db_num_rows($ris_azioni)>0) {
        $azioni = db_fetch_array_assoc($ris_azioni);
    }
    return $azioni;
}

$codiceLotto = "";

$praticheElaborate = [];

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
die("Connessione fallita: " . $mysqli->connect_error);
}

function elaboraDati($dati, $riga, $id_acquisizione)
{
    
    global $codiceLotto;
    global $debug;
    global $array_rollback_acquisizione;
    global $imported_practices;
    global $updated_practices;
    global $predefinito;
    global $corrispondenza;
    global $count_contratti;

    global $campi;
    global $posizioni;
    global $posizioni_indirizzi;
    global $rec_predefiniti;
    global $tipi_indirizzo;
    global $tipi_anagrafica;
    global $creditore1;
    global $debitore1;
    global $garante1;
    global $collegato1;
    global $pratica1;
    global $titolo1;
    
    global $creditore;
    global $debitore;
    global $garante;
    global $collegato;
    global $pratica;
    global $id_ed_pratica;
    global $titolo;

    global $id_creditore;
    global $id_debitore;
    global $id_garante;
    global $id_collegato;
    global $id_pratica;
    global $lotto_mandante;
    global $id_lotto_studio;
    global $vincolo_lotto_contratto;

    global $rigaPartenza;
    global $rigaPartenzaNonExcel;
    global $pratica_creata;

    global $lotto_studio;

    global $praticheElaborate;
    global $id_acquisizione;

    echo 'ID ACQUISIZIONE FILE: <strong>' . $id_acquisizione. '<br></strong>';

    // ---------- CREARE CONNESSIONE ------------------------

    $servername ='hps.solunicanet.it';
    $username = 'demo';
    $password ='wF5eIb1N7SZlw0yi';
    $dbname = 'demo_remida_hps';

    $mysqli = new mysqli($servername, $username, $password, $dbname);

    // --------- ABILITO ERRORI MYSQLI -----------------------

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   

    // ---------- CONTROLLO CONNESSIONE -------------------------

    if ($mysqli->connect_error) {
    die("Connessione fallita: " . $mysqli->connect_error);
    }



    $flagRiprendiPratiche = isset($_POST['acquiszione_riprendi_pratiche']) && $_POST['acquiszione_riprendi_pratiche']>0 ? true : false;

    
    $dett_univoco = array();
    $occorrenze = array_keys($campi, 'pratica*-*riferimento_mandante_1');
    for ($k = 0; $k < count($occorrenze); $k++) {
        $dett_univoco[$k]['riferimento'] = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);
    }

    $occorrenze = array_keys($campi, 'pratica*-*riferimento_mandante_2');
    for ($k = 0; $k < count($occorrenze); $k++) {
        $dett_univoco[$k]['riferimento2'] = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);
    }

    $dett_univoco_debitore = array();
    $occorrenze = array_keys($campi, 'debitore*-*cognome');
    for ($k = 0; $k < count($occorrenze); $k++) {
        $dett_univoco_debitore[$k]['cognome'] = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);
    }


    // RECUPERO LA NAZIONE PREDEFINITA PER GLI INDIRIZZI
    $query_nazione_predefinita = $mysqli->prepare("SELECT codice FROM pf_stati WHERE predefinito = 1 LIMIT 1");
    $query_nazione_predefinita->execute();
    $result = $query_nazione_predefinita->get_result();
    $nazione_predefinita = $result->fetch_assoc();
    $query_nazione_predefinita->close();

    // -------------------------- LOTTI MANDANTE ------------------------------------------------------

    if ((trim($dett_univoco[0]['riferimento']) != '' || trim($dett_univoco[0]['riferimento2']) != '') && ($_POST['contratto'] > 0 || $count_contratti > 0)) {

        if ((($_POST['tipo_file'] == 1 && $riga == $rigaPartenza) || ($_POST['tipo_file'] != 1 && $riga == $rigaPartenzaNonExcel))) {
            ?>
            CREAZIONE LOTTO MANDANTE:
            <?php
            $count_contratti = 0;
            for ($m = 0; $m < count($_POST['applica_quando_contratto']); $m++) {
                if ($_POST['applica_quando_contratto'][$m] != '')
                $count_contratti++;
        }
        // --------------- INSERIMENTO IN ED_LOTTI_MANDANTE ---------------------------
        
        if (($_POST['contratto'] > 0 || $count_contratti > 0) && $_POST['aggiorna_lotto_studio'] == 0) {
            
            $codiceLotto = date('YmdHis') . '-' . $_POST['mandante'];
            $input_codiceLotto = db_input($codiceLotto);
            $descrizione = db_input($_POST['lotto_mandante']);
            $id_mandante = db_input($_POST['mandante']);
            $data = db_input(date('Y-m-d'));

            $mysqli->autocommit(FALSE);
            try{
                $query_inserimento_lotto_mandante = $mysqli->prepare('INSERT INTO ed_lotti_mandante
                                                                    SET codice = ?,
                                                                        descrizione = ?,
                                                                        id_mandante = ?,
                                                                        acquisizione_in_corso = 1,
                                                                        data = ?,
                                                                        id_acquisizione = ?');
                $query_inserimento_lotto_mandante->bind_param('ssisi', $input_codiceLotto, $descrizione, $id_mandante, $data, $id_acquisizione);
                $query_inserimento_lotto_mandante->execute();
                
                $query_ed_lotti_mandante = $mysqli->prepare("SELECT * FROM ed_lotti_mandante WHERE id_acquisizione = $id_acquisizione LIMIT 1");
                $query_ed_lotti_mandante->execute();                
                $result = $query_ed_lotti_mandante->get_result();
                $lotto_mandante = $result->fetch_assoc();

                $mysqli->commit();

            }catch (mysqli_sql_exception $e){
                echo 'ERRORE INSERIMENTO ed_lotti_mandante: ' . $e->getMessage() . '<br>';
                $mysqli->rollback();
            };

            $mysqli->autocommit(TRUE);
            $query_inserimento_lotto_mandante->close();
            
            echo '<strong>' . $lotto_mandante['descrizione'] . ' (' . $lotto_mandante['codice'] . ')</strong><br>';
        } else {
            echo 'NESSUN LOTTO MANDANTE CREATO';
        }
        
        // ------------------------- LOTTI STUDIO --------------------------------------------------

            ?>
            <?php
            if ($_POST['contratto'] > 0 || $count_contratti > 0 && $_POST['aggiorna_lotto_studio'] == 0) {
                ?>
                CREAZIONE n.
                <?php
                if ($count_contratti > 0) echo $count_contratti;
                else echo '1';
                ?>
                LOTTI STUDIO:<br>
                <?php                

                // ------------ SP INSERIMENTO IN ED_LOTTO_STUDIO -------------------------------------------

                $codiceLottoStudio = "s" . (date('YmdHis') . '-' . $_POST['contratto']);

                $input_codiceLottostudio = db_input($codiceLottoStudio);
                $data = db_input(date('Y-m-d'));
                $id_contratto = db_input($_POST['contratto']);

                $mysqli->autocommit(FALSE);
                try{                        

                    $query_inserimento_lotto_studio = $mysqli->prepare('INSERT INTO ed_lotto_studio
                                                                        SET codice = ?,
                                                                            data = ?,
                                                                            id_contratto = ?,
                                                                            id_acquisizione = ?,
                                                                            id_lotto_mandante = ?');
                    $query_inserimento_lotto_studio->bind_param('ssiii', $input_codiceLottostudio, $data, $id_contratto, $id_acquisizione, $lotto_mandante['id']);
                    $query_inserimento_lotto_studio->execute();

                    $mysqli->commit();

                }catch (mysqli_sql_exception $e){
                    echo 'ERRORE INSERIMENTO ed_lotto_studio: ' . $e->getMessage() . '<br>';
                    $mysqli->rollback();
                };
                
                $mysqli->autocommit(TRUE);
                $query_inserimento_lotto_studio->close();

                               
                // $vincolo_lotto_contratto[$_POST['contratto']] = $lotto_studio[0]['id_acquisizione'];
                echo '<strong>' . $input_codiceLottostudio . '</strong><br>'; 
                
                // -------------- SP INSERT ED_LOTTO_STUDIO SE SONO STATI INSERITI CONTRATTI MULTIPLI -------------------------------

                if ($count_contratti > 0) {
                    for ($n = 0; $n < count($_POST['applica_quando_contratto']); $n++) {
                        if ($_POST['applica_quando_contratto'][$n] != '') {

                            $codiceLottoStudio = "s" . (date('YmdHis') . $n . '-' . $_POST['applica_quando_contratto'][$n]);                  
                            $input_codiceLottostudio = db_input($codiceLottoStudio);
                            $data = db_input(date('Y-m-d'));
                            $id_contratto = db_input($_POST['applica_quando_contratto'][$n]);

                            $mysqli->autocommit(FALSE);
                            try{ 
                                
                                $query_inserimento_lotto_studio_contratto = $mysqli->prepare('INSERT INTO ed_lotto_studio
                                                                        SET codice = ?,
                                                                            data = ?,
                                                                            id_contratto = ?,
                                                                            id_acquisizione = ?,
                                                                            id_lotto_mandante = ?');
                                $query_inserimento_lotto_studio_contratto->bind_param('ssiii', $input_codiceLottostudio, $data, $id_contratto, $id_acquisizione, $lotto_mandante['id']);
                                $query_inserimento_lotto_studio_contratto->execute();

                            }catch (mysqli_sql_exception $e){
                                echo 'ERRORE INSERIMENTO ed_lotto_studio: ' . $e->getMessage() . '<br>';
                                $mysqli->rollback();
                            };

                            $mysqli->autocommit(TRUE);
                            $query_inserimento_lotto_studio_contratto->close();
                    
                            $vincolo_lotto_contratto[$_POST['applica_quando_contratto'][$n]] = $lotto_studio[($n + 1)]['id_acquisizione'];
                            echo '<strong>' . $lotto_studio[($n + 1)]['codice'] . '</strong><br>';  
                            
                        }
                    }                    
                }                   
            }
        }                 

        echo '<div class="row" style="margin-bottom: 7px;"><div class="col-md-12"><hr></div></div>';

        // --------- SELEZIONO DATI MANDANTE CON PREPARE STATEMENT ------------------------------

        $query_mandante = $mysqli->prepare("SELECT * FROM utente WHERE id_utente = ?");
        $query_mandante->bind_param('i', $_POST['mandante']);
        $query_mandante->execute();
        $result = $query_mandante->get_result();
        $mandante = $result->fetch_assoc();
        $query_mandante->close();                
        
        //  ---------------------------- CREDITORE ----------------------------------------------------

        ?>
        INSERIMENTO CREDITORE:<br>
        <?php
        {
            $query_creditore = array();
            $dett_creditore = array();

            $occorrenze = array_keys($campi, 'creditore*-*descrizione');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $creditore[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                $query_creditore[$k] .= 'descrizione = "' . $risultante . '", ';
                $dett_creditore[$k]['descrizione'] = $risultante;  
            }
        }

        if ($creditore != $creditore1) {
            $id_creditore = '';           
            
            for ($v = 0; $v < count($query_creditore); $v++) {
                
                // ------------- SELEZIONO DESCRIZIONE CON PREPARE STATEMENT ------------------------------

                $query_select_descrizione_creditore = $mysqli->prepare('SELECT * FROM creditori WHERE descrizione = "' . $dett_creditore[$v]['descrizione'] . '"' );
                $query_select_descrizione_creditore->execute();
                $result = $query_select_descrizione_creditore->get_result();
                $dett_creditore = $result->fetch_assoc();
                
                $query_select_descrizione_creditore->close();

                // ------------- INSERIMENTO IN ED_CREDITORE SE LA DESCRIZIONE E' DIVERSA---------------------  

                $mysqli->autocommit(FALSE);
                try{
                    $query_inserimento_creditore = $mysqli->prepare('INSERT INTO ed_creditori SET id_acquisizione = '. $id_acquisizione .',
                        ' . rtrim($query_creditore[$v], ', '));
                    $query_inserimento_creditore->execute();                        
                    $ed_id_creditore = $mysqli->insert_id;

                    $mysqli->commit();

                    // echo '<strong>INSERIMENTO ED_CREDITORI EFFETTUATO</strong><br>';

                }catch (mysqli_sql_exception $e){
                    echo 'ERRORE INSERIMENTO ED_CREDITORI: ' . $e->getMessage() . '<br> <br>';
                    $mysqli->rollback();
                };
                
                $mysqli->autocommit(TRUE);
                $query_inserimento_creditore->close();

                if ($dett_creditore) {
                    $id_creditore = $dett_creditore['id'];
                    echo '<br><strong>ID CREDITORE: ' . $id_creditore . ' - ' . $dett_creditore['descrizione'] .'</strong> [CREDITORE GIA ESISTENTE]<br>';
                }
            }  
        } else {
            echo '<strong>COME PRECEDENTE</strong><br>';
            }
        

        $creditore1 = $creditore;
        ?>

        <br><br>INSERIMENTO DEBITORE:<br>
        <?php
        // DEBITORE
        {
            $query_utente_debitore = array();
            $query_anagrafica_debitore = array();
            $query_anagrafica_mandante = array();
            $query_debitore = array();
            $query_recapito = array();
            $query_recapito_telefonico = array();
            $query_recapito_telefonico1 = array();
            $query_recapito_telefonico1a = array();
            $query_recapito_telefonico2 = array();

            $dett_utente_debitore = array();
            $dett_anagrafica_debitore = array();
            $dett_anagrafica_mandante = array();
            $dett_debitore = array();
            $dett_recapito = array();
            $dett_recapito_telefonico = array();
            $dett_recapito_telefonico1 = array();
            $dett_recapito_telefonico1a = array();
            $dett_recapito_predefinito = array();

            {
                // UTENTE
                $occorrenze = array_keys($campi, 'debitore*-*codice_fiscale');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    if (trim($risultante, "0") != '') {
                        if (controllaCodiceFiscale($risultante)) {
                            $debitore[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                            //$query_utente_debitore[$k] .= 'codice_fiscale = "'.$risultante.'", ';
                            //$dett_utente_debitore[$k]['codice_fiscale'] = $risultante;
                            if (strpos($query_utente_debitore[0], '_fiscale = ') === false)
                                $query_utente_debitore[0] .= 'codice_fiscale = "' . $risultante . '", ';
                            $dett_utente_debitore[0]['codice_fiscale'] = $risultante;
                        } else {
                            $debitore[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                            //$query_utente_debitore[$k] .= 'partita_iva = "'.$risultante.'", ';
                            //$dett_utente_debitore[$k]['partita_iva'] = $risultante;
                            if (strpos($query_utente_debitore[0], '_iva = ') === false)
                                $query_utente_debitore[0] .= 'partita_iva = "' . $risultante . '", ';
                            $dett_utente_debitore[0]['partita_iva'] = $risultante;
                        }
                    }
                }
                $occorrenze = array_keys($campi, 'debitore*-*nome');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $debitore[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_utente_debitore[$k] .= 'nome = "' . $risultante . '", ';
                    $dett_utente_debitore[$k]['nome'] = $risultante;
                }
                $occorrenze = array_keys($campi, 'debitore*-*cognome');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $debitore[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_utente_debitore[$k] .= 'cognome = "' . $risultante . '", ';
                    $dett_utente_debitore[$k]['cognome'] = $risultante;
                }

                // ANAGRAFICA
                $occorrenze = array_keys($campi, 'debitore*-*codice_anagrafico_mandante');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $debitore[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $dett_univoco[$k]['codice_anagrafico_mandante'] = $risultante;
                }
                $occorrenze = array_keys($campi, 'debitore*-*ragione_sociale_collegato');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $debitore[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $dett_univoco[$k]['ragione_sociale_collegato'] = $risultante;
                }
                $occorrenze = array_keys($campi, 'debitore*-*sesso');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $debitore[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_anagrafica_debitore[$k] .= 'sesso = "' . $risultante . '", ';
                    $dett_anagrafica_debitore[$k]['sesso'] = $risultante;
                }
                $occorrenze = array_keys($campi, 'debitore*-*data_nascita');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $debitore[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_anagrafica_debitore[$k] .= 'data_nascita = "' . converti_data_acquisita($risultante) . '", ';
                    $dett_anagrafica_debitore[$k]['data_nascita'] = converti_data_acquisita($risultante);
                }
                $occorrenze = array_keys($campi, 'debitore*-*citta_nascita');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $debitore[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_anagrafica_debitore[$k] .= 'citta_nascita = "' . $risultante . '", ';
                    $dett_anagrafica_debitore[$k]['citta_nascita'] = $risultante;
                }
                $occorrenze = array_keys($campi, 'debitore*-*provincia_nascita');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $debitore[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;

                    // ------------- SELEZIONO COD_PROVINCIA CON PREPARE STATEMENT --------------------------

                    $query_provincia = $mysqli->prepare("SELECT cod_provincia FROM province WHERE sigla = ?");
                    $query_provincia->bind_param('s', $risultante);
                    $query_provincia->execute();
                    $result = $query_provincia->get_result();
                    $provincia = $result->fetch_assoc();
                    $query_provincia->close();
                    $query_anagrafica_debitore[$k] .= 'provincia_nascita = "' . db_input($provincia['cod_provincia']) . '", ';
                    $dett_anagrafica_debitore[$k]['provincia_nascita'] = $provincia['cod_provincia'];
                }
                $occorrenze = array_keys($campi, 'debitore*-*nazione_nascita');

                if (count($occorrenze) > 0) {
                    for ($k = 0; $k < count($occorrenze); $k++) {
                        $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                        if ($risultante != '') {
                            $debitore[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                            $query_anagrafica_debitore[$k] .= 'nazione_nascita = "' . $risultante . '", ';
                            $dett_anagrafica_debitore[$k]['nazione_nascita'] = $risultante;
                        } else {
                            
                            // -------- SELEZIONO CODICE STATO CON PREPARE STATEMENT -----------------------

                            $query_nazione_predefinita = $mysqli->prepare("SELECT codice FROM pf_stati WHERE predefinito = 1 LIMIT 1");
                            $query_nazione_predefinita->execute();
                            $result = $query_nazione_predefinita->get_result();
                            $nazione_predefinita = $result->fetch_assoc();
                            $query_nazione_predefinita->close();

                            $debitore[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $nazione_predefinita['codice'];
                            $query_anagrafica_debitore[$k] .= 'nazione_nascita = "' . db_input($nazione_predefinita['codice']) . '", ';
                            $dett_anagrafica_debitore[$k]['nazione_nascita'] = $nazione_predefinita['codice'];
                        }
                    }
                } else {
                    // Conto le occorrenze dell'indirizzo e imposto la nazione di default
                    $occorrenze = array_keys($campi, 'debitore*-*citta_nascita');

                    for ($k = 0; $k < count($occorrenze); $k++) {

                        // ------------- SELEZIONO CODICE STATO CON PREPARE STATEMENT -----------------------

                        $query_nazione_predefinita = $mysqli->prepare("SELECT codice FROM pf_stati WHERE predefinito = 1 LIMIT 1");
                        $query_nazione_predefinita->execute();
                        $result = $query_nazione_predefinita->get_result();
                        $nazione_predefinita = $result->fetch_assoc();
                        $query_nazione_predefinita->close();

                        $debitore[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $nazione_predefinita['codice'];
                        $query_anagrafica_debitore[$k] .= 'nazione_nascita = "' . db_input($nazione_predefinita['codice']) . '", ';
                        $dett_anagrafica_debitore[$k]['nazione_nascita'] = $nazione_predefinita['codice'];
                    }
                }

                // ----------------- RECAPITO ------------------------------------------

                $tipo_recapito_debitore = false;
                $occorrenze = array_keys($campi, 'debitore*-*tipo_indirizzo');
                if (count($occorrenze) > 0) {
                    $tipo_recapito_debitore = true;
                }
                
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);
                    
                    $debitore[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    
                    $id_mandante = db_input($_POST['mandante']);
                    
                    // ------------- SP SELEZIONE TIPO INDIRIZZO [SP SERVE NO SERVE] FARE SELECT CON PREPARE--------------------                   

                    try{
                        $query_seleziona_tipo_indirizzo = $mysqli->prepare('SELECT id_remida 
																FROM decodifiche_dettagli DD
																	LEFT JOIN decodifiche D ON DD.id_decodifica = D.id
																WHERE id_mandante = "' . $id_mandante . '"
																AND D.tipo = "tipo indirizzo"
																AND (codice_uno = "' . $risultante . '"
																OR codice_due = "' . $risultante . '")');
                    $query_seleziona_tipo_indirizzo->execute();

                    $result = $query_seleziona_tipo_indirizzo->get_result();
                    $tipo_indirizzo = $result->fetch_assoc();
                    }catch(mysqli_sql_exception $e){
                        echo 'ERRORE SELECT DECODIFICHE DETTAGLI: ' . $e->getMessage();
                    }

                    $query_recapito[$posizioni_indirizzi[$occorrenze[$k]]] .= 'tipo_recapito = "' . db_input($tipo_indirizzo['id_remida']) . '", ';
                    $dett_recapito[$posizioni_indirizzi[$occorrenze[$k]]]['tipo_recapito'] = $tipo_indirizzo['id_remida'];
                    
                    $query_seleziona_tipo_indirizzo->close();
                }

                $occorrenze = array_keys($campi, 'debitore*-*indirizzo');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $debitore[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;

                    if (isset($rec_predefiniti[$occorrenze[$k]]) && $rec_predefiniti[$occorrenze[$k]] == 1)
                        $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] = 1;
                    else
                        $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] = 0;

                    $query_recapito[$posizioni_indirizzi[$occorrenze[$k]]] .= 'indirizzo = "' . $risultante . '", invio_corrispondenza = "' . $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] . '", ';
                    $dett_recapito[$posizioni_indirizzi[$occorrenze[$k]]]['indirizzo'] = $risultante;

                    if (!$tipo_recapito_debitore) {
                        $query_recapito[$posizioni_indirizzi[$occorrenze[$k]]] .= 'tipo_recapito = "' . db_input($tipi_indirizzo[$occorrenze[$k]]) . '", ';
                        $dett_recapito[$posizioni_indirizzi[$occorrenze[$k]]]['tipo_recapito'] = $tipi_indirizzo[$occorrenze[$k]];
                    }
                }

                $occorrenze = array_keys($campi, 'debitore*-*cap');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $debitore[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = str_pad($risultante, 5, '0', STR_PAD_LEFT);
                    $query_recapito[$posizioni_indirizzi[$occorrenze[$k]]] .= 'cap = "' . str_pad($risultante, 5, '0', STR_PAD_LEFT) . '", ';
                    $dett_recapito[$posizioni_indirizzi[$occorrenze[$k]]]['cap'] = str_pad($risultante, 5, '0', STR_PAD_LEFT);
                }
                $occorrenze = array_keys($campi, 'debitore*-*citta');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);


                    $debitore[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_recapito[$posizioni_indirizzi[$occorrenze[$k]]] .= 'citta = "' . $risultante . '", ';
                    $dett_recapito[$posizioni_indirizzi[$occorrenze[$k]]]['citta'] = $risultante;
                }
                $occorrenze = array_keys($campi, 'debitore*-*provincia');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    // ------------- SELEZIONO COD_PROVINCIA CON PREPARE STATEMENT -------------------------

                    $query_provincia = $mysqli->prepare("SELECT cod_provincia FROM province WHERE sigla = ?");
                    $query_provincia->bind_param('s', $risultante);
                    $query_provincia->execute();
                    $result = $query_provincia->get_result();
                    $provincia = $result->fetch_assoc();
                    $query_provincia->close();

                    $query_recapito[$posizioni_indirizzi[$occorrenze[$k]]] .= 'provincia = "' . db_input($provincia['cod_provincia']) . '", ';
                    $dett_recapito[$posizioni_indirizzi[$occorrenze[$k]]]['provincia'] = $provincia['cod_provincia'];
                }
                $occorrenze = array_keys($campi, 'debitore*-*nazione');
                if (count($occorrenze) > 0) {
                    for ($k = 0; $k < count($occorrenze); $k++) {
                        $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                        if ($risultante != '') {
                            $debitore[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                            $query_recapito[$posizioni_indirizzi[$occorrenze[$k]]] .= 'nazione = "' . $risultante . '", ';
                            $dett_recapito[$posizioni_indirizzi[$occorrenze[$k]]]['nazione'] = $risultante;
                        } else {

                            // ------------- SELEZIONO CODICE STATO CON PREPARE STATEMENT ------------------
                            $query_nazione_predefinita = $mysqli->prepare("SELECT codice FROM pf_stati WHERE predefinito = 1 LIMIT 1");
                            $query_nazione_predefinita->execute();
                            $result = $query_nazione_predefinita->get_result();
                            $nazione_predefinita = $result->fetch_assoc();
                            $query_nazione_predefinita->close();

                            $debitore[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $nazione_predefinita['codice'];
                            $query_recapito[$posizioni_indirizzi[$occorrenze[$k]]] .= 'nazione = "' . db_input($nazione_predefinita['codice']) . '", ';
                            $dett_recapito[$posizioni_indirizzi[$occorrenze[$k]]]['nazione'] = $nazione_predefinita['codice'];
                        }
                    }
                } else {
                    // Conto le occorrenze dell'indirizzo e imposto la nazione di default
                    $occorrenze = array_keys($campi, 'debitore*-*indirizzo');

                    for ($k = 0; $k < count($occorrenze); $k++) {

                        // ------------- SELEZIONO CODICE STATO CON PREPARE STATEMENT -------------------

                        $query_nazione_predefinita = $mysqli->prepare("SELECT codice FROM pf_stati WHERE predefinito = 1 LIMIT 1");
                        $query_nazione_predefinita->execute();
                        $result = $query_nazione_predefinita->get_result();
                        $nazione_predefinita = $result->fetch_assoc();
                        $query_nazione_predefinita->close();
                        

                        $debitore[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $nazione_predefinita['codice'];
                        $query_recapito[$posizioni_indirizzi[$occorrenze[$k]]] .= 'nazione = "' . db_input($nazione_predefinita['codice']) . '", ';
                        $dett_recapito[$posizioni_indirizzi[$occorrenze[$k]]]['nazione'] = $nazione_predefinita['codice'];
                    }
                }

                // RECAPITO TELEFONICO
                $occorrenze = array_keys($campi, 'debitore*-*indirizzo-telefono');
                $principale_debitore = -1;

                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    //VERIFICO CHE SIANO TUTTI NUMERI OPPURE IL SEGNO +
                    $risultante = preg_replace("/[^\+0-9]/", "", $risultante);
                    if (preg_match('/^\+?\d+$/', $risultante)) {

                        $debitore[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;

                        if ($principale_debitore == -1 && $posizioni[$occorrenze[$k]] == 0) {
                            $principale_debitore = 0;

                        }


                        if (isset($rec_predefiniti[$occorrenze[$k]]) && $rec_predefiniti[$occorrenze[$k]] == 1)
                            $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] = 1;
                        else
                            $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] = 0;

                        if ($risultante != '' && str_replace(' ', '', $risultante) != '')
                            if ($principale_debitore == 0) {
                                $query_recapito_telefonico[$k] .= 'indirizzo = "' . $risultante . '", usa_per_invio = "' . $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] . '",principale = 1, ';
                                $principale_debitore = 1;
                            } else {
                                $query_recapito_telefonico[$k] .= 'indirizzo = "' . $risultante . '", usa_per_invio = "' . $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] . '", ';
                            }
                        else
                            $query_recapito_telefonico[$k] .= '';
                        $dett_recapito_telefonico[$k]['indirizzo'] = $risultante;
                        if ($dati[$occorrenze[$k]] != '' && str_replace(' ', '', $dati[$occorrenze[$k]]) != '')
                            $query_recapito_telefonico[$k] .= 'tipo_recapito_telefonico = "2", ';
                        $dett_recapito_telefonico[$k]['tipo_recapito_telefonico'] = '2';
                    } else {
                        $query_recapito_telefonico[$k] .= '';

                    }
                }

                $occorrenze = array_keys($campi, 'debitore*-*indirizzo-cellulare');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    //VERIFICO CHE SIANO TUTTI NUMERI OPPURE IL SEGNO +
                    $risultante = preg_replace("/[^\+0-9]/", "", $risultante);
                    if (preg_match('/^\+?\d+$/', $risultante)) {

                        $debitore[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;

                        if ($principale_debitore == -1 && $posizioni[$occorrenze[$k]] == 0) {
                            $principale_debitore = 0;

                        }


                        if (isset($rec_predefiniti[$occorrenze[$k]]) && $rec_predefiniti[$occorrenze[$k]] == 1)
                            $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] = 1;
                        else
                            $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] = 0;

                        if ($risultante != '' && str_replace(' ', '', $risultante) != '')
                            if ($principale_debitore == 0) {

                                $query_recapito_telefonico1a[$k] .= 'indirizzo = "' . $risultante . '", usa_per_invio = "' . $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] . '",principale = 1, ';
                                $principale_debitore = 1;
                            } else {
                                $query_recapito_telefonico1a[$k] .= 'indirizzo = "' . $risultante . '", usa_per_invio = "' . $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] . '", ';
                            }
                        else
                            $query_recapito_telefonico1a[$k] .= '';
                        $dett_recapito_telefonico1a[$k]['indirizzo'] = $risultante;
                        if ($dati[$occorrenze[$k]] != '' && str_replace(' ', '', $dati[$occorrenze[$k]]) != '')
                            $query_recapito_telefonico1a[$k] .= 'tipo_recapito_telefonico = "6", ';
                        $dett_recapito_telefonico1a[$k]['tipo_recapito_telefonico'] = '6';
                    } else {
                        $query_recapito_telefonico1a[$k] .= '';

                    }
                }

                $occorrenze = array_keys($campi, 'debitore*-*indirizzo-email');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $debitore[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;


                    if ($principale_debitore == -1 && $posizioni[$occorrenze[$k]] == 0) {
                        $principale_debitore = 0;
                    }


                    if (isset($rec_predefiniti[$occorrenze[$k]]) && $rec_predefiniti[$occorrenze[$k]] == 1)
                        $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] = 1;
                    else
                        $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] = 0;

                    if ($dati[$occorrenze[$k]] != '' && str_replace(' ', '', $dati[$occorrenze[$k]]) != '')
                        if ($principale_debitore == 0) {
                            $query_recapito_telefonico1[$k] .= 'indirizzo = "' . $risultante . '", usa_per_invio = "' . $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] . '",principale = 1, ';
                            $principale_debitore = 1;
                        } else {
                            $query_recapito_telefonico1[$k] .= 'indirizzo = "' . $risultante . '", usa_per_invio = "' . $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] . '", ';
                        }
                    else
                        $query_recapito_telefonico1[$k] .= '';
                    $dett_recapito_telefonico1[$k]['indirizzo'] = $risultante;
                    if ($dati[$occorrenze[$k]] != '' && str_replace(' ', '', $dati[$occorrenze[$k]]) != '')
                        $query_recapito_telefonico1[$k] .= 'tipo_recapito_telefonico = "1", ';
                    $dett_recapito_telefonico1[$k]['tipo_recapito_telefonico'] = '1';
                }

                $occorrenze = array_keys($campi, 'debitore*-*indirizzo-pec');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $debitore[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;

                    if ($principale_debitore == -1 && $posizioni[$occorrenze[$k]] == 0) {
                        $principale_debitore = 0;
                    }


                    if (isset($rec_predefiniti[$occorrenze[$k]]) && $rec_predefiniti[$occorrenze[$k]] == 1)
                        $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] = 1;
                    else
                        $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] = 0;

                    if ($dati[$occorrenze[$k]] != '' && str_replace(' ', '', $dati[$occorrenze[$k]]) != '')
                        if ($principale_debitore == 0) {
                            $query_recapito_telefonico2[$k] .= 'indirizzo = "' . $risultante . '", usa_per_invio = "' . $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] . '",principale = 1, ';
                            $principale_debitore = 1;
                        } else {
                            $query_recapito_telefonico2[$k] .= 'indirizzo = "' . $risultante . '", usa_per_invio = "' . $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] . '", ';
                        }
                    else
                        $query_recapito_telefonico2[$k] .= '';
                    $dett_recapito_telefonico2[$k]['indirizzo'] = $risultante;
                    if ($dati[$occorrenze[$k]] != '' && str_replace(' ', '', $dati[$occorrenze[$k]]) != '')
                        $query_recapito_telefonico2[$k] .= 'tipo_recapito_telefonico = "0", ';
                    $dett_recapito_telefonico2[$k]['tipo_recapito_telefonico'] = '0';
                }
            }
        } 
        //    echo 'DEBITORE: '; print_r($debitore); echo '<br><br>';
        //    echo 'DEBITORE1: '; print_r($debitore1); echo '<br><br>';
           
            
        $esistenza_utente = false;
    
        if ($debitore != $debitore1) {
            $id_debitore = '';

            
            // TODO: IMPLEMENTARE VERIFICA ESISTENZA SU DB

            // INSERIMENTO UTENTE NEL DB + INSERIMENTO NELLA TABELLA DI ACQUISIZIONE
            for ($v = 0; $v < count($query_utente_debitore); $v++) {
                //echo 'INSERT INTO utente SET '.rtrim($query_utente_debitore[$v],', ').'<br><br>';

                $cf = db_input(str_replace(' ', '', $dett_utente_debitore[$v]['codice_fiscale']));
                $pi = db_input(str_replace(' ', '', $dett_utente_debitore[$v]['partita_iva']));

                //echo 'CF: '.$cf;
                //echo '<br>';
                // echo 'PI: '.$pi;
                // echo '<br>';              
                

                if ($pi != '' && $cf != '')
                    $query_esistenza_utente = 'SELECT * FROM utente WHERE (codice_fiscale = "' . $cf . '" OR partita_iva = "' . $pi . '") AND (codice_fiscale <> "" OR partita_iva <> "")';
                else if ($pi != '')
                    $query_esistenza_utente = 'SELECT * FROM utente WHERE partita_iva = "' . $pi . '" AND partita_iva <> ""';
                else if ($cf != '')
                    $query_esistenza_utente = 'SELECT * FROM utente WHERE codice_fiscale = "' . $cf . '" AND codice_fiscale <> ""';
                else
                    $query_esistenza_utente = '';

                
                
                if ($query_esistenza_utente != '') {
                    
                    // ------------ QUERY SELEZIONE DATI DA UTENTE CON PREPARE STATEMENT -------------------------
                    $select_esistenza_utente = $mysqli->prepare($query_esistenza_utente);
                    $select_esistenza_utente->execute();
                    $result = $select_esistenza_utente->get_result();
                    $ris_esistenza_utente = $result->fetch_assoc();
                    $select_esistenza_utente->close();   
                }                               
            
                if ($query_esistenza_utente != '' && count($ris_esistenza_utente) > 0) $esistenza_utente = true;
                
                if ($esistenza_utente) {
                    $dettaglio_debitore = $ris_esistenza_utente;
                    $id_debitore = $dettaglio_debitore['id_utente'];                         
                    
                }
                $forza_update = 0;
                if($dettaglio_debitore) $forza_update = 1;
                
                    // ------------ INSERIMENTO DETT_DEBITORE IN ED_UTENTE -----------------------------
                   
                $mysqli->autocommit(FALSE);
                try{                        
                    $query_insert_utente = $mysqli->prepare('INSERT INTO ed_utente SET id_acquisizione = '.$id_acquisizione.', gruppi_base = 8,' . rtrim($query_utente_debitore[$v], ', '). ', forza_update = ' . $forza_update);
                    $query_insert_utente->execute();
                    $ed_id_utente = $mysqli->insert_id;                    
                    $query_insert_utente->close();
                    $mysqli->commit();

                    // echo '<strong> INSERIMENTO IN ED_UTENTE EFFETTUATO</strong> <br>';                   
                    
                }catch (mysqli_sql_exception $e){
                    echo 'ERRORE CREAZIONE UTENTE: ' . $e->getMessage() . '<br>';
                    $mysqli->rollback();
                };
                $mysqli->autocommit(TRUE);
                
                if (trim($dettaglio_debitore['cognome']) != '' && trim($dettaglio_debitore['partita_iva']) != '') echo '<br><strong>ID UTENTE: ' . $id_debitore . ' - ' . $dettaglio_debitore['cognome'] . ' (' . $dettaglio_debitore['partita_iva'] . ')</strong>';
                else if (trim($dettaglio_debitore['cognome']) != '' && trim($dettaglio_debitore['codice_fiscale']) != '') echo '<br><strong>ID UTENTE: ' . $id_debitore . ' - ' . $dettaglio_debitore['cognome'] . ' (' . $dettaglio_debitore['codice_fiscale'] . ')</strong>';
                
                if ($esistenza_utente) echo ' [UTENTE GIA ESISTENTE]<br>';
                echo '<br>';
                
                // --------------- INSERIMENTO IN ED_ACQUISIZIONE_DATI ----------------------------------   
                
                if (isset($dett_univoco[0]['riferimento'])) {
                    $riferimento_mandante = $dett_univoco[0]['riferimento'];
                    $data_inserimento = db_input(date('Y-m-d H:i:s'));
                    $mysqli->autocommit(FALSE);
                    try{
                        $query_acquisizione_dati = $mysqli->prepare("INSERT INTO ed_acquisizione_dati
                                                                    SET id_utente = ?,
                                                                        riferimento_mandante = ?,
                                                                        data_inserimento = ?,
                                                                        id_acquisizione = ?");
                        $query_acquisizione_dati->bind_param('issi', $ed_id_utente, $riferimento_mandante, $data_inserimento, $id_acquisizione );
                        $query_acquisizione_dati->execute();

                        $mysqli->commit();                        
                        // echo '<strong>INSERIMENTO TAB ED_ACQUISIZIONE_DATI EFFETTUATO</strong><br>';

    
                    }catch (mysqli_sql_exception $e){
                        echo 'ERRORE INSERIMENTO TAB ED_ACQUISIZIONE_DATI: ' . $e->getMessage() . '<br>';
                        $mysqli->rollback();                        
                    };
                    $mysqli->autocommit(TRUE);
                    $query_acquisizione_dati->close();

                }else if (isset($dett_univoco[0]['riferimento2'])) {
                    $riferimento_mandante = $dett_univoco[0]['riferimento2'];
                    $data_inserimento = db_input(date('Y-m-d H:i:s'));

                    $mysqli->autocommit(FALSE);
                    try{
                        $query_acquisizione_dati = $mysqli->prepare("INSERT INTO ed_acquisizione_dati
                                                                    SET id_utente = ?,
                                                                        riferimento_mandante = ?,
                                                                        data_inserimento = ?,
                                                                        id_acquisizione = ?");
                        $query_acquisizione_dati->bind_param('issi', $ed_id_utente, $riferimento_mandante, $data_inserimento, $id_acquisizione );
                        $query_acquisizione_dati->execute();
                        $mysqli->commit();
                        // echo '<strong>INSERIMENTO TAB ED_ACQUISIZIONE_DATI EFFETTUATO</strong><br>';

    
                    }catch (mysqli_sql_exception $e){
                        echo 'ERRORE INSERIMENTO ED_TAB ACQUISIZIONE_DATI: ' . $e->getMessage() . '<br>';
                        $mysqli->rollback();                        
                    };
                    $mysqli->autocommit(TRUE); 
                    $query_acquisizione_dati->close(); 
                }
            }


            // INSERIMENTO ANAGRAFICA & AGGIORNO id_utente SU utente
            if (!$esistenza_utente) {
                if (count($query_anagrafica_debitore) > 0) {
                    for ($v = 0; $v < count($query_anagrafica_debitore); $v++) {
                        if (!isset($dett_utente_debitore[$v]['partita_iva']) || trim($dett_utente_debitore[$v]['partita_iva']) == '') {
                            // if ($debug) echo 'INSERT INTO anagrafica SET ' . rtrim($query_anagrafica_debitore[$v], ', ');

                            // --------------- INSERT DETT_ANAGRAFICA IN ED_ANAGRAFICA ---------------------
                            
                            $mysqli->autocommit(FALSE);
                            try{
                                $query_insert_ed_anagrafica = $mysqli->prepare('INSERT INTO ed_anagrafica SET id_utente = '.$ed_id_utente.', id_acquisizione = '.$id_acquisizione.', ' . rtrim($query_anagrafica_debitore[$v], ', '));
                                $query_insert_ed_anagrafica->execute();
                                $mysqli->commit();
                                // echo '<strong>INSERIMENTO DEBITORE IN ED_ANAGRAFICA EFFETTUATO</strong><br>';

                            }catch (mysqli_sql_exception $e){
                                echo 'ERRORE INSERIMENTO DEBITORE TAB ED_ANAGRAFICA: ' . $e->getMessage() . '<br>';
                                $mysqli->rollback();                        
                            };
                            $mysqli->autocommit(TRUE);
                            $query_insert_ed_anagrafica->close();
                        }
                    }
                } else if (!isset($dett_utente_debitore[0]['partita_iva']) || trim($dett_utente_debitore[0]['partita_iva']) == '') {
                    // if ($debug) echo 'INSERT INTO anagrafica SET nazione_nascita = "IT"';

                    $mysqli->autocommit(FALSE);
                    try{
                        $query_insert_ed_anagrafica = $mysqli->prepare("INSERT INTO ed_anagrafica SET  
                                                                                id_utente = '$ed_id_utente',
                                                                                id_acquisizione = '$id_acquisizione',
                                                                                nazione_nascita = 'IT' ");
                        $query_insert_ed_anagrafica->execute();
                        $mysqli->commit();

                        // echo '<strong>INSERIMENTO DEBITORE IN ED_ANAGRAFICA EFFETTUATO</strong><br>';

                    }catch (mysqli_sql_exception $e){
                        echo 'ERRORE INSERIMENTO DEBITORE TAB ED_ANAGRAFICA: ' . $e->getMessage() . '<br>';
                        $mysqli->rollback();                        
                    };
                    $mysqli->autocommit(TRUE);
                    $query_insert_ed_anagrafica->close();
                }
            }
            
            // --------------- INSERT ED_ANAGRAFICA_COLLEGATI_MANDANTE ---------------------


            $codice_anagrafico_mandante = (isset($dett_univoco[0]['codice_anagrafico_mandante']) && $dett_univoco[0]['codice_anagrafico_mandante'] != '') ? $dett_univoco[0]['codice_anagrafico_mandante'] : $dett_univoco[0]['riferimento'];


            $ragione_sociale_collegato = (isset($dett_univoco[0]['ragione_sociale_collegato']) && $dett_univoco[0]['ragione_sociale_collegato'] != '') ? $dett_univoco[0]['ragione_sociale_collegato'] : ($dett_utente_debitore[0]['nome'] . ' ' . $dett_utente_debitore[0]['cognome']);

            $mysqli->autocommit(FALSE);
            try{
                $insert_anagrafica_collegati_mandante = $mysqli->prepare(('INSERT INTO ed_anagrafica_collegati_mandante SET
                                                                            id_acquisizione = '. $id_acquisizione .',
                                                                            id_mandante = "' . $_POST['mandante'] . '",
                                                                            id_collegato_pratica = "' . $ed_id_utente . '", 
                                                                            id_pratica = NULL,
                                                                            data_inserimento = "' . date('Y-m-d H:i:s') . '", 
                                                                            ragione_sociale_collegato = "' . db_input($ragione_sociale_collegato) . '",
                                                                            codice_anagrafico_mandante = "' . db_input($codice_anagrafico_mandante) . '"'));
                $insert_anagrafica_collegati_mandante->execute();
                $mysqli->commit();  
                
                // echo '<strong>INSERIMENTO DEBITORE IN ED_ANAGRAFICA_COLLEGATI_MANDANTE EFFETTUATO</strong><br>';
                
            }catch(mysqli_sql_exception $e){
                echo 'ERRORE INSERIMENTO DEBITORE ED_ANAGRAFICA_COLLEGATI_MANDANTE: ' . $e->getMessage() . '<br>';
                $mysqli->rollback();
            };
            $mysqli->autocommit(TRUE);
            $insert_anagrafica_collegati_mandante->close();

            // ------------------------------------------INSERIMENTO RECAPITI------------------------------
            for ($v = 0; $v < count($query_recapito); $v++) {
                if (strpos($query_recapito[$v], 'indirizzo = ""') === FALSE) {

                    // echo 'query_recapito: <br>';
                    // print_r($query_recapito[$v]);
                    // echo '<br>';
                    
                    $query_verifica_fonte_operatore = $mysqli->prepare('SELECT * FROM recapito WHERE id_utente = "' . db_input($id_debitore) . '" AND fonte <> 2 AND predefinito = 1');
                    $query_verifica_fonte_operatore->execute();
                    $result = $query_verifica_fonte_operatore->get_result();
                    $esistenza_fonte_operatore = $result->fetch_assoc();
                    $query_verifica_fonte_operatore->close();

                    $forza_update = 0;
                    $valore_predefinito = 0;
                    if ($predefinito == 1 && $v == 0){$valore_predefinito = 1;}
                    if (count($esistenza_fonte_operatore) > 0) {$valore_predefinito = 0;}
                    if ( $valore_predefinito == 1 ){$forza_update = 1;}                
                    
                    $mysqli->autocommit(FALSE);
                    try{
                        $query_insert_ed_recapito = $mysqli->prepare('INSERT INTO ed_recapito SET
                                                                     id_acquisizione = '. $id_acquisizione .',
                                                                     id_utente = "' . $ed_id_utente . '",
                                                                     fonte = 2,
                                                                     predefinito = "' . db_input($valore_predefinito) . '",
                                                                     attivo = 1,
                                                                    ' . rtrim($query_recapito[$v], ', ') .',
                                                                    forza_update =' . $forza_update);
                        $query_insert_ed_recapito->execute();
                        $mysqli->commit();

                        // echo '<strong>INSERIMENTO RECAPITO DEBITORE ED_RECAPITO EFFETTUATO</strong><br>';

                    }catch(mysqli_sql_exception $e){
                        echo 'ERRORE INSERIMENTO RECAPITO DEBITORE ED_RECAPITO: ' . $e->getMessage() . '<br>';
                        $mysqli->rollback();
                    }
                    $mysqli->autocommit(TRUE);
                    $query_insert_ed_recapito->close();
                }
            }

            // INSERIMENTO RECAPITI TELEFONICI E EMAIL            
           
            for ($v = 0; $v < count($query_recapito_telefonico); $v++) {
                if ($query_recapito_telefonico[$v] != '' && strpos($query_recapito_telefonico[$v], 'indirizzo = ""') === FALSE && $id_debitore != '') {

                    try{
                        $query_verifica_fonte_operatore = $mysqli->prepare('SELECT * FROM recapito_telefonico WHERE id_utente = "' . db_input($id_debitore) . '" AND fonte <> 2 AND      principale = 1 AND tipo_recapito_telefonico = 2');
                        $query_verifica_fonte_operatore->execute();
                        $mysqli->commit();
                        $result = $query_verifica_fonte_operatore->get_result();
                        $esistenza_fonte_operatore = $result->fetch_assoc();

                    }catch(mysqli_sql_exception $e){
                        echo 'Errore select recapito telefonico: ' . $e->getMessage() . '<br>';
                        $mysqli->rollback();}
                    $query_verifica_fonte_operatore->close();
                   
                    // echo '<br>';
                    // print_r($esistenza_fonte_operatore);
                    // print_r($query_recapito_telefonico[$v]);
                    // echo '<br>';
                    $forza_update = 0;
                    if (strpos($query_recapito_telefonico[$v], 'principale = 1') !== FALSE)$forza_update = 1;
                    if (count($esistenza_fonte_operatore) > 0){
                        $forza_update = 0;
                        $imposta_principale_0 =str_replace('principale = 1', 'principale = 0', $query_recapito_telefonico[$v]);
                        $query_recapito_telefonico[$v] = $imposta_principale_0;
                    }
                    // print_r($query_recapito_telefonico[$v]);
                    $mysqli->autocommit(FALSE);
                    try{
                        $query_insert_ed_recapito_telefonico = $mysqli->prepare('INSERT INTO ed_recapito_telefonico SET
                                                                                            id_utente = "' . $ed_id_utente . '",
                                                                                            fonte = 2, attivo = 1,
                                                                                            ' . rtrim($query_recapito_telefonico[$v], ', ')
                                                                                            .', forza_update = ' . $forza_update .
                                                                                            ',id_acquisizione = ' . $id_acquisizione .';');
                        $query_insert_ed_recapito_telefonico->execute();
                        $mysqli->commit();     
                        
                        // echo '<strong>INSERIMENTO RECAPITO DEBITORE ED_RECAPITO_TELEFONICO EFFETTUATO</strong><br>';
                        
                    }catch(mysqli_sql_exception $e){
                        echo 'ERRORE INSERIMENTO RECAPITO DEBITORE ED_RECAPITO_TELEFONICO: ' . $e->getMessage() . '<br>';
                        $mysqli->rollback();
                    }
                    $mysqli->autocommit(TRUE);
                    $query_insert_ed_recapito_telefonico->close();
           
                }
            }
            for ($v = 0; $v < count($query_recapito_telefonico1a); $v++) {
                if ($query_recapito_telefonico1a[$v] != '' && strpos($query_recapito_telefonico1a[$v], 'indirizzo = ""') === FALSE && $id_debitore != '') {

                    try{
                        $query_verifica_fonte_operatore = $mysqli->prepare('SELECT * FROM recapito_telefonico WHERE id_utente = "' . db_input($id_debitore) . '" AND fonte <> 2 AND      principale = 1 AND tipo_recapito_telefonico = 1');
                        $query_verifica_fonte_operatore->execute();
                        $mysqli->commit();
                        $result = $query_verifica_fonte_operatore->get_result();
                        $esistenza_fonte_operatore = $result->fetch_assoc();

                    }catch(mysqli_sql_exception $e){
                        echo 'ERRORE SELECT $dett_recapito_telefonico1A: ' . $e->getMessage() . '<br>';
                        $mysqli->rollback();}
                    $query_verifica_fonte_operatore->close();
                   
                    // echo '<br>';
                    // print_r($esistenza_fonte_operatore);
                    // print_r($query_recapito_telefonico1a[$v]);
                    // echo '<br>';
                    $forza_update = 0;
                    if (strpos($query_recapito_telefonico1a[$v], 'principale = 1') !== FALSE)$forza_update = 1;
                    if (count($esistenza_fonte_operatore) > 0){
                        $forza_update = 0;
                        $imposta_principale_0 =str_replace('principale = 1', 'principale = 0', $query_recapito_telefonico1a[$v]);
                        $query_recapito_telefonico1a[$v] = $imposta_principale_0;
                    }
                    // print_r($query_recapito_telefonico1a[$v]);
                    $mysqli->autocommit(FALSE);
                    try{
                        $query_insert_ed_recapito_telefonico = $mysqli->prepare('INSERT INTO ed_recapito_telefonico SET
                                                                                            id_utente = "' . $ed_id_utente . '",
                                                                                            fonte = 2, attivo = 1,
                                                                                            ' . rtrim($query_recapito_telefonico1a[$v], ', ')
                                                                                            .', forza_update = ' . $forza_update .
                                                                                            ',id_acquisizione = ' . $id_acquisizione .';');
                        $query_insert_ed_recapito_telefonico->execute();
                        $mysqli->commit();     
                        
                        // echo '<strong>INSERIMENTO ED_RECAPITO_TELEFONICO1A EFFETTUATO</strong><br>';
                        
                    }catch(mysqli_sql_exception $e){
                        echo 'ERRORE INSERIMENTO ED_RECAPITO_TELEFONICO1A: ' . $e->getMessage() . '<br>';
                        $mysqli->rollback();
                    }
                    $mysqli->autocommit(TRUE);
                    $query_insert_ed_recapito_telefonico->close();
           
                }
            }
            for ($v = 0; $v < count($query_recapito_telefonico1); $v++) {
                if ($query_recapito_telefonico1[$v] != '' && strpos($query_recapito_telefonico1[$v], 'indirizzo = ""') === FALSE && $id_debitore != '') {

                    try{
                        $query_verifica_fonte_operatore = $mysqli->prepare('SELECT * FROM recapito_telefonico WHERE id_utente = "' . db_input($id_debitore) . '" AND fonte <> 2 AND      principale = 1 AND tipo_recapito_telefonico = 1');
                        $query_verifica_fonte_operatore->execute();
                        $mysqli->commit();
                        $result = $query_verifica_fonte_operatore->get_result();
                        $esistenza_fonte_operatore = $result->fetch_assoc();

                    }catch(mysqli_sql_exception $e){
                        echo 'ERRORE SELECT RECAPITO_TELEFONICO1: ' . $e->getMessage() . '<br>';
                        $mysqli->rollback();}
                    $query_verifica_fonte_operatore->close();
                   
                    // echo '<br>';
                    // print_r($esistenza_fonte_operatore);
                    // print_r($query_recapito_telefonico1[$v]);
                    // echo '<br>';
                    $forza_update = 0;
                    if (strpos($query_recapito_telefonico1[$v], 'principale = 1') !== FALSE)$forza_update = 1;
                    if (count($esistenza_fonte_operatore) > 0){
                        $forza_update = 0;
                        $imposta_principale_0 =str_replace('principale = 1', 'principale = 0', $query_recapito_telefonico1[$v]);
                        $query_recapito_telefonico1[$v] = $imposta_principale_0;
                    }
                    // print_r($query_recapito_telefonico1[$v]);
                    $mysqli->autocommit(FALSE);
                    try{
                        $query_insert_ed_recapito_telefonico = $mysqli->prepare('INSERT INTO ed_recapito_telefonico SET
                                                                                            id_utente = "' . $ed_id_utente . '",
                                                                                            fonte = 2, attivo = 1,
                                                                                            ' . rtrim($query_recapito_telefonico1[$v], ', ')
                                                                                            .', forza_update = ' . $forza_update .
                                                                                            ',id_acquisizione = ' . $id_acquisizione .';');
                        $query_insert_ed_recapito_telefonico->execute();
                        $mysqli->commit();     
                        
                        // echo '<strong>INSERIMENTO ED_RECAPITO_TELEFONICO1 EFFETTUATO</strong><br>';
                        
                    }catch(mysqli_sql_exception $e){
                        echo 'ERRORE INSERIMENTO ED_RECAPITO_TELEFONICO1A: ' . $e->getMessage() . '<br>';
                        $mysqli->rollback();
                    }
                    $mysqli->autocommit(TRUE);
                    $query_insert_ed_recapito_telefonico->close();
           
                }
            }
            for ($v = 0; $v < count($query_recapito_telefonico2); $v++) {
                if ($query_recapito_telefonico2[$v] != '' && strpos($query_recapito_telefonico2[$v], 'indirizzo = ""') === FALSE && $id_debitore != '') {

                    try{
                        $query_verifica_fonte_operatore = $mysqli->prepare('SELECT * FROM recapito_telefonico WHERE id_utente = "' . db_input($id_debitore) . '" AND fonte <> 2 AND      principale = 1 AND tipo_recapito_telefonico = 1');
                        $query_verifica_fonte_operatore->execute();
                        $mysqli->commit();
                        $result = $query_verifica_fonte_operatore->get_result();
                        $esistenza_fonte_operatore = $result->fetch_assoc();

                    }catch(mysqli_sql_exception $e){
                        echo 'ERRORE SELECT RECAPITO_TELEFONICO2: ' . $e->getMessage() . '<br>';
                        $mysqli->rollback();}
                    $query_verifica_fonte_operatore->close();
                   
                    // echo '<br>';
                    // print_r($esistenza_fonte_operatore);
                    // print_r($query_recapito_telefonico2[$v]);
                    // echo '<br>';
                    $forza_update = 0;
                    if (strpos($query_recapito_telefonico2[$v], 'principale = 1') !== FALSE)$forza_update = 1;
                    if (count($esistenza_fonte_operatore) > 0){
                        $forza_update = 0;
                        $imposta_principale_0 =str_replace('principale = 1', 'principale = 0', $query_recapito_telefonico2[$v]);
                        $query_recapito_telefonico2[$v] = $imposta_principale_0;
                    }
                    // print_r($query_recapito_telefonico2[$v]);
                    $mysqli->autocommit(FALSE);
                    try{
                        $query_insert_ed_recapito_telefonico = $mysqli->prepare('INSERT INTO ed_recapito_telefonico SET
                                                                                            id_utente = "' . $ed_id_utente . '",
                                                                                            fonte = 2, attivo = 1,
                                                                                            ' . rtrim($query_recapito_telefonico2[$v], ', ')
                                                                                            .', forza_update = ' . $forza_update .
                                                                                            ',id_acquisizione = ' . $id_acquisizione .';');
                        $query_insert_ed_recapito_telefonico->execute();
                        $mysqli->commit();     
                        
                        // echo '<strong>INSERIMENTO ED_RECAPITO_TELEFONICO2 EFFETTUATO</strong><br>';
                        
                    }catch(mysqli_sql_exception $e){
                        echo 'ERRORE INSERIMENTO ED_RECAPITO_TELEFONICO2: ' . $e->getMessage() . '<br>';
                        $mysqli->rollback();
                    }
                    $mysqli->autocommit(TRUE);
                    $query_insert_ed_recapito_telefonico->close();
           
                }
            }
        } else if (count($debitore) != 0) {
            echo '<strong>COME PRECEDENTE</strong> - ID DEBITORE: <strong>' . $id_debitore . '</strong><br>';
        } else {
            echo '<strong>NON SETTATO</strong><br>';
        }

        $debitore1 = $debitore;
        ?>

        <br><br>INSERIMENTO COLLEGATO PRATICA:<br>
        <?php
        // COLLEGATO PRATICA
        {
            $query_utente_garante = array();
            $query_anagrafica_garante = array();
            $query_garante = array();
            $query_recapito = array();
            $query_recapito_telefonico = array();
            $query_recapito_telefonico1 = array();

            $dett_utente_garante = array();
            $dett_anagrafica_garante = array();
            $dett_debitore = array();
            $dett_recapito = array();
            $dett_recapito_telefonico = array();
            $dett_recapito_telefonico1 = array();
            $dett_recapito_predefinito = array();

            $garante = array();
            {
                // UTENTE
                $tipo_anagrafica_collegato_pratica = false;
                $occorrenze = array_keys($campi, 'garante*-*tipologia');
                if (count($occorrenze) > 0) {
                    $tipo_anagrafica_collegato_pratica = true;
                }
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $garante[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;

                    try{
                        $query_seleziona_tipo_garante = $mysqli->prepare('SELECT id_remida 
																FROM decodifiche_dettagli DD
																	LEFT JOIN decodifiche D ON DD.id_decodifica = D.id
																WHERE id_mandante = "' . db_input($_POST['mandante']) . '"
																AND D.tipo = "tipo collegato"
																AND (codice_uno = "' . $risultante . '"
																OR codice_due = "' . $risultante . '")');
                    $query_seleziona_tipo_garante->execute();

                    $result = $query_seleziona_tipo_garante->get_result();
                    $tipo_collegato = $result->fetch_assoc();
                    }catch(mysqli_sql_exception $e){
                        echo 'ERRORE SELECT COLLEGATO DECODIFICHE DETTAGLI: ' . $e->getMessage();
                    }
                    $query_seleziona_tipo_garante->close();
                    // print_r($tipo_collegato);
                    $dett_utente_garante[$posizioni[$occorrenze[$k]]][$k]['tipologia'] = $tipo_collegato['id_remida'];
                }
                $occorrenze = array_keys($campi, 'garante*-*nome');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $garante[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_utente_garante[$posizioni[$occorrenze[$k]]][$k] .= 'nome = "' . addslashes($risultante) . '", ';
                    $dett_utente_garante[$posizioni[$occorrenze[$k]]][$k]['nome'] = $risultante;
                }
                $occorrenze = array_keys($campi, 'garante*-*cognome');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $garante[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_utente_garante[$posizioni[$occorrenze[$k]]][$k] .= 'cognome = "' . addslashes($risultante) . '", ';
                    $dett_utente_garante[$posizioni[$occorrenze[$k]]][$k]['cognome'] = $risultante;

                    if (!$tipo_anagrafica_collegato_pratica) {
                        $dett_utente_garante[$posizioni[$occorrenze[$k]]][$k]['tipologia'] = $tipi_anagrafica[$occorrenze[$k]];
                    }
                }

                $occorrenze = array_keys($campi, 'garante*-*codice_fiscale');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    if (trim($risultante) != '') {
                        if (controllaCodiceFiscale($risultante)) {
                            $garante[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                            if (strpos($query_utente_garante[$posizioni[$occorrenze[$k]]][$k], '_fiscale = ') === false)
                                $query_utente_garante[$posizioni[$occorrenze[$k]]][$k] .= 'codice_fiscale = "' . $risultante . '", ';
                            $dett_utente_garante[$posizioni[$occorrenze[$k]]][$k]['codice_fiscale'] = $risultante;
                        } else {
                            $garante[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                            if (strpos($query_utente_garante[$posizioni[$occorrenze[$k]]][$k], '_iva = ') === false)
                                $query_utente_garante[$posizioni[$occorrenze[$k]]][$k] .= 'partita_iva = "' . $risultante . '", ';
                            $dett_utente_garante[$posizioni[$occorrenze[$k]]][$k]['partita_iva'] = $risultante;
                        }
                    }
                }


                // ANAGRAFICA
                $occorrenze = array_keys($campi, 'garante*-*codice_anagrafico_mandante');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $garante[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $dett_anagrafica_garante[$posizioni[$occorrenze[$k]]][$k]['codice_anagrafico_mandante'] = $risultante;
                }
                $occorrenze = array_keys($campi, 'garante*-*ragione_sociale_collegato');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $garante[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $dett_anagrafica_garante[$posizioni[$occorrenze[$k]]][$k]['ragione_sociale_collegato'] = $risultante;
                }
                $occorrenze = array_keys($campi, 'garante*-*sesso');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $garante[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_anagrafica_garante[$posizioni[$occorrenze[$k]]][$k] .= 'sesso = "' . $risultante . '", ';
                    $dett_anagrafica_garante[$posizioni[$occorrenze[$k]]][$k]['sesso'] = $risultante;
                }
                $occorrenze = array_keys($campi, 'garante*-*data_nascita');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $garante[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_anagrafica_garante[$posizioni[$occorrenze[$k]]][$k] .= 'data_nascita = "' . converti_data_acquisita($risultante) . '", ';
                    $dett_anagrafica_garante[$posizioni[$occorrenze[$k]]][$k]['data_nascita'] = converti_data_acquisita($risultante);
                }
                $occorrenze = array_keys($campi, 'garante*-*citta_nascita');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $garante[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_anagrafica_garante[$posizioni[$occorrenze[$k]]][$k] .= 'citta_nascita = "' . $risultante . '", ';
                    $dett_anagrafica_garante[$posizioni[$occorrenze[$k]]][$k]['citta_nascita'] = $risultante;
                }
                $occorrenze = array_keys($campi, 'garante*-*provincia_nascita');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $garante[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_provincia = $mysqli->prepare("SELECT cod_provincia FROM province WHERE sigla = ?");
                    $query_provincia->bind_param('s', $risultante);
                    $query_provincia->execute();
                    $result = $query_provincia->get_result();
                    $provincia = $result->fetch_assoc();
                    $query_provincia->close();
                    $query_anagrafica_garante[$posizioni[$occorrenze[$k]]][$k] .= 'provincia_nascita = "' . db_input($provincia['cod_provincia']) . '", ';
                    $dett_anagrafica_garante[$posizioni[$occorrenze[$k]]][$k]['provincia_nascita'] = $provincia['cod_provincia'];
                }
                $occorrenze = array_keys($campi, 'garante*-*nazione_nascita');

                if (count($occorrenze) > 0) {
                    for ($k = 0; $k < count($occorrenze); $k++) {
                        $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                        if ($risultante != '') {
                            $garante[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                            $query_anagrafica_garante[$posizioni[$occorrenze[$k]]][$k] .= 'nazione_nascita = "' . $risultante . '", ';
                            $dett_anagrafica_garante[$posizioni[$occorrenze[$k]]][$k]['nazione_nascita'] = $risultante;
                        } else {
                            $query_nazione_predefinita = $mysqli->prepare("SELECT codice FROM pf_stati WHERE predefinito = 1 LIMIT 1");
                            $query_nazione_predefinita->execute();
                            $result = $query_nazione_predefinita->get_result();
                            $nazione_predefinita = $result->fetch_assoc();
                            $query_nazione_predefinita->close();

                            $garante[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $nazione_predefinita['codice'];
                            $query_anagrafica_garante[$posizioni[$occorrenze[$k]]][$k] .= 'nazione_nascita = "' . db_input($nazione_predefinita['codice']) . '", ';
                            $dett_anagrafica_garante[$posizioni[$occorrenze[$k]]][$k]['nazione_nascita'] = $nazione_predefinita['codice'];
                        }
                    }
                } else {
                    // Conto le occorrenze dell'indirizzo e imposto la nazione di default
                    $occorrenze = array_keys($campi, 'garante*-*citta_nascita');

                    for ($k = 0; $k < count($occorrenze); $k++) {
                        $query_nazione_predefinita = $mysqli->prepare("SELECT codice FROM pf_stati WHERE predefinito = 1 LIMIT 1");
                        $query_nazione_predefinita->execute();
                        $result = $query_nazione_predefinita->get_result();
                        $nazione_predefinita = $result->fetch_assoc();
                        $query_nazione_predefinita->close();

                        $garante[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $nazione_predefinita['codice'];
                        $query_anagrafica_garante[$posizioni[$occorrenze[$k]]][$k] .= 'nazione_nascita = "' . db_input($nazione_predefinita['codice']) . '", ';
                        $dett_anagrafica_garante[$posizioni[$occorrenze[$k]]][$k]['nazione_nascita'] = $nazione_predefinita['codice'];
                    }
                }

                // RECAPITO 1
                $tipo_recapito_garante = false;
                $occorrenze = array_keys($campi, 'garante*-*tipo_indirizzo');
                if (count($occorrenze) > 0) {
                    $tipo_recapito_garante = true;
                }
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $garante[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;

                    try{
                        $query_seleziona_tipo_indirizzo = $mysqli->prepare('SELECT id_remida 
																FROM decodifiche_dettagli DD
																	LEFT JOIN decodifiche D ON DD.id_decodifica = D.id
																WHERE id_mandante = "' . db_input($_POST['mandante']) . '"
																AND D.tipo = "tipo indirizzo"
																AND (codice_uno = "' . $risultante . '"
																OR codice_due = "' . $risultante . '")');
                        $query_seleziona_tipo_indirizzo->execute();

                        $result = $query_seleziona_tipo_indirizzo->get_result();
                        $tipo_indirizzo = $result->fetch_assoc();
                    }catch(mysqli_sql_exception $e){
                        echo 'ERRORE SELECT INDIRIZZO COLLEGATO DECODIFICHE DETTAGLI: ' . $e->getMessage();
                    }
                    $query_seleziona_tipo_indirizzo->close();

                    $query_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]] .= 'tipo_recapito = "' . addslashes($tipo_indirizzo['id_remida']) . '", ';
                    $dett_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]]['tipo_recapito'] = $tipo_indirizzo['id_remida'];
                }
                $occorrenze = array_keys($campi, 'garante*-*indirizzo');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $garante[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;

                    if (isset($rec_predefiniti[$occorrenze[$k]]) && $rec_predefiniti[$occorrenze[$k]] == 1)
                        $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]] = 1;
                    else
                        $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]] = 0;

                    $query_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]] .= 'indirizzo = "' . $risultante . '", invio_corrispondenza = "' . $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]] . '", ';
                    $dett_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]]['indirizzo'] = $risultante;

                    if (!$tipo_recapito_garante) {
                        $query_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]] .= 'tipo_recapito = "' . db_input($tipi_indirizzo[$occorrenze[$k]]) . '", ';
                        $dett_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]]['tipo_recapito'] = $tipi_indirizzo[$occorrenze[$k]];
                    }
                }
                $occorrenze = array_keys($campi, 'garante*-*cap');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $garante[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = str_pad($risultante, 5, '0', STR_PAD_LEFT);
                    $query_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]] .= 'cap = "' . str_pad($risultante, 5, '0', STR_PAD_LEFT) . '", ';
                    $dett_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]]['cap'] = str_pad($risultante, 5, '0', STR_PAD_LEFT);
                }
                $occorrenze = array_keys($campi, 'garante*-*citta');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $garante[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]] .= 'citta = "' . $risultante . '", ';
                    $dett_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]]['citta'] = $risultante;
                }
                $occorrenze = array_keys($campi, 'garante*-*provincia');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $garante[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_provincia = $mysqli->prepare("SELECT cod_provincia FROM province WHERE sigla = ?");
                    $query_provincia->bind_param('s', $risultante);
                    $query_provincia->execute();
                    $result = $query_provincia->get_result();
                    $provincia = $result->fetch_assoc();
                    $query_provincia->close();
                    $query_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]] .= 'provincia = "' . db_input($provincia['cod_provincia']) . '", ';
                    $dett_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]]['provincia'] = $provincia['cod_provincia'];
                }
                $occorrenze = array_keys($campi, 'garante*-*nazione');
                if (count($occorrenze) > 0) {
                    for ($k = 0; $k < count($occorrenze); $k++) {
                        $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                        if ($risultante != '') {
                            $garante[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                            $query_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]] .= 'nazione = "' . $risultante . '", ';
                            $dett_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]]['nazione'] = $risultante;
                        } else {
                            $query_nazione_predefinita = $mysqli->prepare("SELECT codice FROM pf_stati WHERE predefinito = 1 LIMIT 1");
                            $query_nazione_predefinita->execute();
                            $result = $query_nazione_predefinita->get_result();
                            $nazione_predefinita = $result->fetch_assoc();
                            $query_nazione_predefinita->close();

                            $garante[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $nazione_predefinita['codice'];
                            $query_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]] .= 'nazione = "' . db_input($nazione_predefinita['codice']) . '", ';
                            $dett_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]]['nazione'] = $nazione_predefinita['codice'];
                        }
                    }
                } else {
                    // Conto le occorrenze dell'indirizzo e imposto la nazione di default
                    $occorrenze = array_keys($campi, 'garante*-*indirizzo');

                    for ($k = 0; $k < count($occorrenze); $k++) {
                        $garante[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $nazione_predefinita['codice'];
                        $query_recapito[$posizioni[$occorrenze[$k]]][$k] .= 'nazione = "' . db_input($nazione_predefinita['codice']) . '", ';
                        $dett_recapito[$posizioni[$occorrenze[$k]]][$k]['nazione'] = $nazione_predefinita['codice'];
                    }
                    $query_nazione_predefinita = $mysqli->prepare("SELECT codice FROM pf_stati WHERE predefinito = 1 LIMIT 1");
                    $query_nazione_predefinita->execute();
                    $result = $query_nazione_predefinita->get_result();
                    $nazione_predefinita = $result->fetch_assoc();
                    $query_nazione_predefinita->close();;


                }

                // RECAPITO TELEFONICO 1
                $occorrenze = array_keys($campi, 'garante*-*indirizzo-telefono');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $garante[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;

                    if (isset($rec_predefiniti[$occorrenze[$k]]) && $rec_predefiniti[$occorrenze[$k]] == 1)
                        $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] = 1;
                    else
                        $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] = 0;

                    if ($dati[$occorrenze[$k]] != '')
                        $query_recapito_telefonico[$posizioni[$occorrenze[$k]]][$k] .= 'indirizzo = "' . $risultante . '", usa_per_invio = "' . $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] . '", ';
                    else
                        $query_recapito_telefonico[$posizioni[$occorrenze[$k]]][$k] .= '';
                    $dett_recapito_telefonico[$posizioni[$occorrenze[$k]]][$k]['indirizzo'] = $risultante;
                    if ($dati[$occorrenze[$k]] != '')
                        $query_recapito_telefonico[$posizioni[$occorrenze[$k]]][$k] .= 'tipo_recapito_telefonico = "2", ';
                    $dett_recapito_telefonico[$posizioni[$occorrenze[$k]]][$k]['tipo_recapito_telefonico'] = '2';
                }
                $occorrenze = array_keys($campi, 'garante*-*indirizzo-email');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $garante[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;

                    if (isset($rec_predefiniti[$occorrenze[$k]]) && $rec_predefiniti[$occorrenze[$k]] == 1)
                        $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] = 1;
                    else
                        $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] = 0;

                    if ($dati[$occorrenze[$k]] != '')
                        $query_recapito_telefonico1[$posizioni[$occorrenze[$k]]][$k] .= 'indirizzo = "' . $risultante . '", usa_per_invio = "' . $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] . '", ';
                    else
                        $query_recapito_telefonico1[$posizioni[$occorrenze[$k]]][$k] .= '';
                    $dett_recapito_telefonico1[$posizioni[$occorrenze[$k]]][$k]['indirizzo'] = $risultante;
                    if ($dati[$occorrenze[$k]] != '')
                        $query_recapito_telefonico1[$posizioni[$occorrenze[$k]]][$k] .= 'tipo_recapito_telefonico = "1", ';
                    $dett_recapito_telefonico1[$posizioni[$occorrenze[$k]]][$k]['tipo_recapito_telefonico'] = '1';
                }
            }
        }    //echo 'GARANTE: '; print_r($garante); echo '<br><br>';

        $esistenza_utente = false;

        if ($garante != '') {
            for ($z = 0; $z < count($garante); $z++) {
                $id_garante = '';

                // INSERIMENTO UTENTE
                $query_garante_imploded = implode(' ', $query_utente_garante[$z]);
                //for ($v = 0; $v < count($query_utente_garante); $v++) {
                // MANUEL 20201124
                //echo 'INSERT INTO utente SET '.rtrim($query_garante_imploded,', ').'<br><br>';

                $cf = '';
                $pi = '';
                $tipologia = '';
                $cognome = '';
                $nome = '';
                $codiceAnagraficoMandante = '';
                $ragioneSocialeCollegato = '';

                if ($debug) {
                    echo " <br> DETTAGLI GARANTE - <br> ";
                    print_r($dett_utente_garante[$z]);
                }

                foreach ($dett_utente_garante[$z] as $dett_garante) {
                    if (trim($dett_garante['codice_fiscale']) != '') $cf = trim(db_input($dett_garante['codice_fiscale']));
                    if (trim($dett_garante['partita_iva']) != '') $pi = trim(db_input($dett_garante['partita_iva']));
                    if (trim($dett_garante['tipologia']) != '') $tipologia = trim(db_input($dett_garante['tipologia']));
                    if (trim($dett_garante['cognome']) != '') $cognome = trim(db_input($dett_garante['cognome']));
                    if (trim($dett_garante['nome']) != '') $nome = trim(db_input($dett_garante['nome']));

                }

                foreach ($dett_anagrafica_garante[$z] as $dett_garante_anag) {
                    if (trim($dett_garante_anag['codice_anagrafico_mandante']) != '') $codiceAnagraficoMandante = trim(db_input($dett_garante_anag['codice_anagrafico_mandante']));
                    if (trim($dett_garante_anag['ragione_sociale_collegato']) != '') $ragioneSocialeCollegato = trim(db_input($dett_garante_anag['ragione_sociale_collegato']));
                }

                $codice_anagrafico_mandante = $codiceAnagraficoMandante != '' ? $codiceAnagraficoMandante : $dett_univoco[0]['riferimento'];
                $ragione_sociale_collegato = $ragioneSocialeCollegato != '' ? $ragioneSocialeCollegato : ($nome . ' ' . $cognome);

                if (strpos($query_garante_imploded, 'cognome = ""') === FALSE && strpos($query_garante_imploded, 'cognome = " "') === FALSE) {

                    if ($cognome != '') {
                        $query_esistenza_utente = '';
                        if ($pi != '' && $cf != '') {
                            $query_esistenza_utente = 'SELECT * FROM utente WHERE (codice_fiscale = "' . $cf . '" OR partita_iva = "' . $pi . '") AND partita_iva <> "" AND codice_fiscale <> ""';
                        } else if ($pi != '') {
                            $query_esistenza_utente = 'SELECT * FROM utente WHERE partita_iva = "' . $pi . '" AND partita_iva <> ""';
                        } else if ($cf != '') {
                            $query_esistenza_utente = 'SELECT * FROM utente WHERE codice_fiscale = "' . $cf . '" AND codice_fiscale <> ""';
                        }
                        
                        if ($query_esistenza_utente != '') {

                            // ------------ QUERY SELEZIONE DATI DA UTENTE CON PREPARE STATEMENT -------------------------
                            $select_esistenza_utente = $mysqli->prepare($query_esistenza_utente);
                            $select_esistenza_utente->execute();
                            $result = $select_esistenza_utente->get_result();
                            $ris_esistenza_utente = $result->fetch_assoc();
                            $select_esistenza_utente->close();   
                        }                               
                        // print_r($ris_esistenza_utente);
                        if ($query_esistenza_utente != '' && count($ris_esistenza_utente) > 0) $esistenza_utente = true;
                        
                        if ($esistenza_utente) {
                            $dettaglio_garante = $ris_esistenza_utente;
                            $id_garante = $dettaglio_garante['id_utente'];                       
                        }
                        // print_r($id_garante);

                        $forza_update = 0;
                        if($dettaglio_garante) $forza_update = 1;
                        
                            // ------------ INSERIMENTO DETT_GARANTE IN ED_UTENTE -----------------------------
                        
                        $mysqli->autocommit(FALSE);
                        try{                        
                            $query_insert_utente = $mysqli->prepare('INSERT INTO ed_utente SET id_acquisizione = '.$id_acquisizione.', gruppi_base = 8,' . rtrim($query_garante_imploded, ', '). ', forza_update = ' . $forza_update);
                            $query_insert_utente->execute();
                            $ed_id_garante = $mysqli->insert_id;                    
                            $query_insert_utente->close();
                            $mysqli->commit();

                            // echo '<strong> INSERIMENTO GARANTE IN ED_UTENTE EFFETTUATO</strong> <br>';                            
                            
                        }catch (mysqli_sql_exception $e){
                            echo 'ERRORE CREAZIONE GARANTE: ' . $e->getMessage() . '<br>';
                            $mysqli->rollback();
                        };
                        $mysqli->autocommit(TRUE);
                        
                        if (trim($dettaglio_garante['cognome']) != '' && trim($dettaglio_garante['partita_iva']) != '') echo '<br><strong>ID UTENTE: ' . $id_garante . ' - ' . $dettaglio_garante['cognome'] . ' (' . $dettaglio_garante['partita_iva'] . ')</strong>';
                        else if (trim($dettaglio_garante['cognome']) != '' && trim($dettaglio_garante['codice_fiscale']) != '') echo '<br><strong>ID UTENTE: ' . $id_garante . ' - ' . $dettaglio_garante['cognome'] . ' (' . $dettaglio_garante['codice_fiscale'] . ')</strong>';
                        else if (trim($dettaglio_garante['cognome']) != '') echo '<br><strong>ID UTENTE: ' . $id_garante . ' - ' . $dettaglio_garante['cognome'] . '</strong>';
                        
                        if ($esistenza_utente) echo ' [ GARANTE GIA ESISTENTE]<br>';
                        echo '<br>';

                        $array_collegati_pratica[] = array('id' => $ed_id_garante, 'tipo' => $tipologia);
                        
                        // INSERIMENTO GARANTE IN ED_ANAGRAFICA_COLLEGATI_MANDANTE

                        $mysqli->autocommit(FALSE);
                        try{
                            $insert_anagrafica_collegati_mandante = $mysqli->prepare(('INSERT IGNORE INTO ed_anagrafica_collegati_mandante SET
                                                                                        id_acquisizione = '. $id_acquisizione .',
                                                                                        id_mandante = "' . $_POST['mandante'] . '",
                                                                                        id_collegato_pratica = "' . $ed_id_garante . '", 
                                                                                        id_pratica = NULL,
                                                                                        data_inserimento = "' . date('Y-m-d H:i:s') . '", 
                                                                                        ragione_sociale_collegato = "' . db_input($ragione_sociale_collegato) . '",
                                                                                        codice_anagrafico_mandante = "' . db_input($codice_anagrafico_mandante) . '"'));
                            $insert_anagrafica_collegati_mandante->execute();
                            $mysqli->commit();  
                            
                            // echo '<strong>INSERIMENTO GARANTE IN ED_ANAGRAFICA_COLLEGATI_MANDANTE EFFETTUATO</strong><br>';
                            
                        }catch(mysqli_sql_exception $e){
                            echo 'ERRORE INSERIMENTO GARANTE IN ED_ANAGRAFICA_COLLEGATI_MANDANTE: ' . $e->getMessage() . '<br>';
                            $mysqli->rollback();
                        };
                        $mysqli->autocommit(TRUE);
                        $insert_anagrafica_collegati_mandante->close();
                    }
                }
                
                // INSERIMENTO ALTRI DETTAGLI
                // INSERIMENTO ANAGRAFICA & AGGIORNO id_utente SU utente          

                if (!$esistenza_utente) {   
                    
                    $es_pi = '';
                    foreach ($dett_utente_garante[$z] as $dett_garante) {
                        if (isset($dett_garante['partita_iva']) && trim($dett_garante['partita_iva']) != '') {
                            $es_pi = $dett_garante['partita_iva'];
                        }
                    }
                    
                    if (count($query_anagrafica_garante[$z]) > 0) {
                        foreach ($query_anagrafica_garante[$z] as $qryAnafGar) {
                           
                            if ($es_pi == '' && $qryAnafGar != '') {

                                $mysqli->autocommit(FALSE);
                                try{
                                    $query_insert_ed_anagrafica = $mysqli->prepare('INSERT INTO ed_anagrafica SET  id_utente = '. $ed_id_garante .', id_acquisizione = '.$id_acquisizione.', ' . rtrim($qryAnafGar, ', '));
                                    $query_insert_ed_anagrafica->execute();
                                    $mysqli->commit();
                                    // echo '<strong>INSERIMENTO GARANTE IN ED_ANAGRAFICA EFFETTUATO</strong><br>';
    
                                }catch (mysqli_sql_exception $e){
                                    echo 'ERRORE INSERIMENTO GARANTE IN ED_ANAGRAFICA: ' . $e->getMessage() . '<br>';
                                    $mysqli->rollback();                        
                                };
                                $mysqli->autocommit(TRUE);
                                $query_insert_ed_anagrafica->close();
                            }
                        }

                    } else if ($es_pi == '') {

                        $mysqli->autocommit(FALSE);
                        try{
                            $query_insert_ed_anagrafica = $mysqli->prepare("INSERT INTO ed_anagrafica SET  
                                                                                    id_utente = '$ed_id_garante',
                                                                                    id_acquisizione = '$id_acquisizione',
                                                                                    nazione_nascita = 'IT' ");
                            $query_insert_ed_anagrafica->execute();
                            $mysqli->commit();
    
                            // echo '<strong>INSERIMENTO GARANTE IN ED_ANAGRAFICA EFFETTUATO</strong><br>';
    
                        }catch (mysqli_sql_exception $e){
                            echo 'ERRORE INSERIMENTO GARANTE TAB ED_ANAGRAFICA: ' . $e->getMessage() . '<br>';
                            $mysqli->rollback();                        
                        };
                        $mysqli->autocommit(TRUE);
                        $query_insert_ed_anagrafica->close();
                    }
                }

                // INSERIMENTO RECAPITI
                for ($v = 0; $v < count($query_recapito); $v++) {
                    if (rtrim($query_recapito[$z][$v], ', ') != '' && $dett_recapito[$z][$v]['indirizzo'] != '') {
                        
                        // print_r($id_garante);
                        $query_verifica_fonte_operatore = $mysqli->prepare('SELECT * FROM recapito WHERE id_utente = "' . db_input($id_garante) . '" AND fonte <> 2 AND predefinito = 1');
                        $query_verifica_fonte_operatore->execute();
                        $result = $query_verifica_fonte_operatore->get_result();
                        $esistenza_fonte_operatore = $result->fetch_assoc();
                        $query_verifica_fonte_operatore->close();

                        $forza_update = 0;
                        $valore_predefinito = 0;
                        if ($predefinito == 1 && $v == 0){$valore_predefinito = 1;}
                        if (count($esistenza_fonte_operatore) > 0) {$valore_predefinito = 0;}
                        if ( $valore_predefinito == 1 ){$forza_update = 1;}                
                        
                        $mysqli->autocommit(FALSE);
                        try{
                            $query_insert_ed_recapito = $mysqli->prepare('INSERT INTO ed_recapito SET
                                                                        id_acquisizione = '. $id_acquisizione .',
                                                                        id_utente = "' . $ed_id_garante . '",
                                                                        fonte = 2,
                                                                        predefinito = "' . db_input($valore_predefinito) . '",
                                                                        attivo = 1,
                                                                        ' . rtrim($query_recapito[$z][$v], ', ') .',
                                                                        forza_update =' . $forza_update);
                            $query_insert_ed_recapito->execute();
                            $mysqli->commit();

                            // echo '<strong>INSERIMENTO RECAPITO GARANTE IN ED_RECAPITO EFFETTUATO</strong><br>';

                        }catch(mysqli_sql_exception $e){
                            echo 'ERRORE INSERIMENTO RECAPITO GARANTE IN ED_RECAPITO: ' . $e->getMessage() . '<br>';
                            $mysqli->rollback();
                        }
                        $mysqli->autocommit(TRUE);
                        $query_insert_ed_recapito->close();
                    }
                }

                // $debug = true;
                if ($debug) {
                    echo "<br>CIAOO T";
                    print_r($query_recapito_telefonico);
                }
                // INSERIMENTO RECAPITI TELEFONICI E EMAIL
                for ($v = 0; $v < count($query_recapito_telefonico[$z]); $v++) {
                    if ($debug) {
                        echo "<br>CIAOO TEST 2";

                        echo "<br> T1 " . $z . " - - " . $v;
                        echo "<br> T1 " . $query_recapito_telefonico[$z][$v];
                        echo "<br> T2 " . $dett_recapito_telefonico[$z][$v]['indirizzo'];
                        echo "<br> T3 " . $id_garante;
                        echo "<br> T4 " . $dett_recapito_predefinito[$z][$v];
                        
                    }
                    if ($query_recapito_telefonico[$z][$v] != '' && $dett_recapito_telefonico[$z][$v]['indirizzo'] != '' && $id_garante > 0) {

                        try{
                            $query_verifica_fonte_operatore = $mysqli->prepare('SELECT * FROM recapito_telefonico WHERE id_utente = "' . db_input($id_garante) . '" AND fonte <> 2 AND      principale = 1 AND tipo_recapito_telefonico = 2');
                            $query_verifica_fonte_operatore->execute();
                            $mysqli->commit();
                            $result = $query_verifica_fonte_operatore->get_result();
                            $esistenza_fonte_operatore = $result->fetch_assoc();
    
                        }catch(mysqli_sql_exception $e){
                            echo 'ERRORE SELECT RECAPITO TELEFONICO: ' . $e->getMessage() . '<br>';
                            $mysqli->rollback();
                        }
                        $query_verifica_fonte_operatore->close();
                       
                        $forza_update = 0;
                        $attivo = 0;
                        if (strpos($query_recapito_telefonico[$z][$v], 'usa_per_invio = "1"') !== FALSE) $attivo = 1;
                        if (count($esistenza_fonte_operatore) > 0){
                            $forza_update = 1;
                        }
                        // print_r($query_recapito_telefonico[$z][$v]);
                        $mysqli->autocommit(FALSE);
                        try{
                            $query_insert_ed_recapito_telefonico = $mysqli->prepare('INSERT INTO ed_recapito_telefonico SET
                                                                                                id_utente = "' . $ed_id_garante . '",
                                                                                                fonte = 2,
                                                                                                attivo = ' . $attivo . ', 
                                                                                                principale = 0,  
                                                                                                ' . rtrim($query_recapito_telefonico[$z][$v], ', ')
                                                                                                .', forza_update = ' . $forza_update .
                                                                                                ',id_acquisizione = ' . $id_acquisizione .';');
                            $query_insert_ed_recapito_telefonico->execute();
                            $mysqli->commit();     
                            
                            // echo '<strong>INSERIMENTO RECAPITO GARANTE ED_RECAPITO_TELEFONICO EFFETTUATO</strong><br>';
                            
                        }catch(mysqli_sql_exception $e){
                            echo 'ERRORE INSERIMENTO RECAPITO GARANTE ED_RECAPITO_TELEFONICO: ' . $e->getMessage() . '<br>';
                            $mysqli->rollback();
                        }
                        $mysqli->autocommit(TRUE);
                        $query_insert_ed_recapito_telefonico->close();

                    }
                }

                for ($v = 0; $v < count($query_recapito_telefonico1); $v++) {
                    if ($query_recapito_telefonico1[$z][$v] != '' && $dett_recapito_telefonico1[$z][$v]['indirizzo'] != '' && $id_garante > 0) {

                        try{
                            $query_verifica_fonte_operatore = $mysqli->prepare('SELECT * FROM recapito_telefonico WHERE id_utente = "' . db_input($id_garante) . '" AND fonte <> 2 AND      principale = 1 AND tipo_recapito_telefonico = 1');
                            $query_verifica_fonte_operatore->execute();
                            $mysqli->commit();
                            $result = $query_verifica_fonte_operatore->get_result();
                            $esistenza_fonte_operatore = $result->fetch_assoc();
    
                        }catch(mysqli_sql_exception $e){
                            echo 'ERRORE SELECT RECAPITO TELEFONICO: ' . $e->getMessage() . '<br>';
                            $mysqli->rollback();
                        }
                        $query_verifica_fonte_operatore->close();
                       
                        $forza_update = 0;
                        $attivo = 0;
                        if (strpos($query_recapito_telefonico1[$z][$v], 'usa_per_invio = "1"') !== FALSE) $attivo = 1;
                        if (count($esistenza_fonte_operatore) > 0){
                            $forza_update = 1;
                        }
                        // print_r($query_recapito_telefonico[$z][$v]);
                        $mysqli->autocommit(FALSE);
                        try{
                            $query_insert_ed_recapito_telefonico1 = $mysqli->prepare('INSERT INTO ed_recapito_telefonico SET
                                                                                                id_utente = "' . $ed_id_garante . '",
                                                                                                fonte = 2,
                                                                                                attivo = ' . $attivo . ', 
                                                                                                principale = 0,  
                                                                                                ' . rtrim($query_recapito_telefonico1[$z][$v], ', ')
                                                                                                .', forza_update = ' . $forza_update .
                                                                                                ',id_acquisizione = ' . $id_acquisizione .';');
                            $query_insert_ed_recapito_telefonico1->execute();
                            $mysqli->commit();     
                            
                            // echo '<strong>INSERIMENTO RECAPITO1 GARANTE ED_RECAPITO_TELEFONICO EFFETTUATO</strong><br>';
                            
                        }catch(mysqli_sql_exception $e){
                            echo 'ERRORE INSERIMENTO RECAPITO1 GARANTE ED_RECAPITO_TELEFONICO: ' . $e->getMessage() . '<br>';
                            $mysqli->rollback();
                        }
                        $mysqli->autocommit(TRUE);
                        $query_insert_ed_recapito_telefonico1->close();                       
                    }
                }
            }
        } else if ($garante == '') {
            echo '<strong>NON SETTATO</strong><br>';
        } else if (count($garante) != 0) {
            echo '<strong>COME PRECEDENTE</strong><br>';
        } else {
            echo '<strong>NON SETTATO</strong><br>';
        }

        $garante1 = $garante;
        ?>

        <br><br>INSERIMENTO ANAGRAFICA COLLEGATA DEBITORE:<br>
        <?php
        // ANAGRAFICA COLLEGATA DEBITORE
        {
            $query_utente_collegato = array();
            $query_anagrafica_collegato = array();
            $query_collegato = array();
            $query_recapito = array();
            $query_recapito_telefonico = array();
            $query_recapito_telefonico1 = array();

            $dett_utente_collegato = array();
            $dett_anagrafica_collegato = array();
            $dett_debitore = array();
            $dett_recapito = array();
            $dett_recapito_telefonico = array();
            $dett_recapito_telefonico1 = array();
            $dett_recapito_predefinito = array();

            {
                // UTENTE
                $tipo_anagrafica_collegato = false;
                $occorrenze = array_keys($campi, 'collegato*-*tipologia');
                if (count($occorrenze) > 0) {
                    $tipo_anagrafica_collegato = true;
                }
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $collegato[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;

                    try{
                        $query_seleziona_tipo_indirizzo = $mysqli->prepare('SELECT id_remida 
																FROM decodifiche_dettagli DD
																	LEFT JOIN decodifiche D ON DD.id_decodifica = D.id
																WHERE id_mandante = "' . db_input($_POST['mandante']) . '"
																AND D.tipo = "tipo indirizzo"
																AND (codice_uno = "' . $risultante . '"
																OR codice_due = "' . $risultante . '")');
                        $query_seleziona_tipo_indirizzo->execute();

                        $result = $query_seleziona_tipo_indirizzo->get_result();
                        $tipo_collegato = $result->fetch_assoc();
                    }catch(mysqli_sql_exception $e){
                        echo 'ERRORE SELECT TIPO COLLEGATO DECODIFICHE DETTAGLI: ' . $e->getMessage();
                    }
                    $query_seleziona_tipo_indirizzo->close();

                    $dett_utente_collegato[$posizioni[$occorrenze[$k]]][$k]['tipologia'] = $tipo_collegato['id_remida'];
                }
                $occorrenze = array_keys($campi, 'collegato*-*nome');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $collegato[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_utente_collegato[$posizioni[$occorrenze[$k]]][$k] .= 'nome = "' . $risultante . '", ';
                    $dett_utente_collegato[$posizioni[$occorrenze[$k]]][$k]['nome'] = $risultante;

                    if (!$tipo_anagrafica_collegato) {
                        $dett_utente_collegato[$posizioni[$occorrenze[$k]]][$k]['tipologia'] = $_POST['linked_type'][$occorrenze[$k]];
                    }
                }
                $occorrenze = array_keys($campi, 'collegato*-*cognome');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $collegato[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_utente_collegato[$posizioni[$occorrenze[$k]]][$k] .= 'cognome = "' . $risultante . '", ';
                    $dett_utente_collegato[$posizioni[$occorrenze[$k]]][$k]['cognome'] = $risultante;

                    if (!$tipo_anagrafica_collegato) {
                        $dett_utente_collegato[$posizioni[$occorrenze[$k]]][$k]['tipologia'] = $tipi_anagrafica[$occorrenze[$k]];
                    }
                }
                $occorrenze = array_keys($campi, 'collegato*-*ragione_sociale_collegato');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $collegato[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $dett_utente_collegato[$posizioni[$occorrenze[$k]]][$k]['ragione_sociale_collegato'] = $risultante;
                }
                $occorrenze = array_keys($campi, 'collegato*-*codice_fiscale');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    if (trim($risultante) != '') {
                        if (controllaCodiceFiscale($risultante)) {
                            $collegato[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                            if (strpos($query_utente_collegato[$posizioni[$occorrenze[$k]]][$k], '_fiscale = ') === false)
                                $query_utente_collegato[$posizioni[$occorrenze[$k]]][$k] .= 'codice_fiscale = "' . $risultante . '", ';
                            $dett_utente_collegato[$posizioni[$occorrenze[$k]]][$k]['codice_fiscale'] = $risultante;
                        } else {
                            $collegato[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                            if (strpos($query_utente_collegato[$posizioni[$occorrenze[$k]]][$k], '_iva = ') === false)
                                $query_utente_collegato[$posizioni[$occorrenze[$k]]][$k] .= 'partita_iva = "' . $risultante . '", ';
                            $dett_utente_collegato[$posizioni[$occorrenze[$k]]][$k]['partita_iva'] = $risultante;
                        }
                    }
                }


                // ANAGRAFICA
                $occorrenze = array_keys($campi, 'collegato*-*codice_anagrafico_mandante');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $collegato[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $dett_anagrafica_collegato[$posizioni[$occorrenze[$k]]][$k]['codice_anagrafico_mandante'] = $risultante;
                }
                $occorrenze = array_keys($campi, 'collegato*-*sesso');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $collegato[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_anagrafica_collegato[$posizioni[$occorrenze[$k]]][$k] .= 'sesso = "' . $risultante . '", ';
                    $dett_anagrafica_collegato[$posizioni[$occorrenze[$k]]][$k]['sesso'] = $risultante;
                }
                $occorrenze = array_keys($campi, 'collegato*-*data_nascita');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $collegato[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_anagrafica_collegato[$posizioni[$occorrenze[$k]]][$k] .= 'data_nascita = "' . converti_data_acquisita($risultante) . '", ';
                    $dett_anagrafica_collegato[$posizioni[$occorrenze[$k]]][$k]['data_nascita'] = $risultante;
                }
                $occorrenze = array_keys($campi, 'collegato*-*citta_nascita');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $collegato[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_anagrafica_collegato[$posizioni[$occorrenze[$k]]][$k] .= 'citta_nascita = "' . $risultante . '", ';
                    $dett_anagrafica_collegato[$posizioni[$occorrenze[$k]]][$k]['citta_nascita'] = $risultante;
                }
                $occorrenze = array_keys($campi, 'collegato*-*provincia_nascita');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $collegato[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_provincia = $mysqli->prepare("SELECT cod_provincia FROM province WHERE sigla = ?");
                    $query_provincia->bind_param('s', $risultante);
                    $query_provincia->execute();
                    $result = $query_provincia->get_result();
                    $provincia = $result->fetch_assoc();
                    $query_provincia->close();
                    $query_anagrafica_collegato[$posizioni[$occorrenze[$k]]][$k] .= 'provincia_nascita = "' . db_input($provincia['cod_provincia']) . '", ';
                    $dett_anagrafica_collegato[$posizioni[$occorrenze[$k]]][$k]['provincia_nascita'] = $provincia['cod_provincia'];
                }
                $occorrenze = array_keys($campi, 'collegato*-*nazione_nascita');

                if (count($occorrenze) > 0) {
                    for ($k = 0; $k < count($occorrenze); $k++) {
                        $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                        if ($risultante != '') {
                            $collegato[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                            $query_anagrafica_collegato[$posizioni[$occorrenze[$k]]][$k] .= 'nazione_nascita = "' . $risultante . '", ';
                            $dett_anagrafica_collegato[$posizioni[$occorrenze[$k]]][$k]['nazione_nascita'] = $risultante;
                        } else {
                            $query_nazione_predefinita = $mysqli->prepare("SELECT codice FROM pf_stati WHERE predefinito = 1 LIMIT 1");
                            $query_nazione_predefinita->execute();
                            $result = $query_nazione_predefinita->get_result();
                            $nazione_predefinita = $result->fetch_assoc();
                            $query_nazione_predefinita->close();

                            $collegato[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $nazione_predefinita['codice'];
                            $query_anagrafica_collegato[$posizioni[$occorrenze[$k]]][$k] .= 'nazione_nascita = "' . db_input($nazione_predefinita['codice']) . '", ';
                            $dett_anagrafica_collegato[$posizioni[$occorrenze[$k]]][$k]['nazione_nascita'] = $nazione_predefinita['codice'];
                        }
                    }
                } else {
                    // Conto le occorrenze dell'indirizzo e imposto la nazione di default
                    $occorrenze = array_keys($campi, 'collegato*-*citta_nascita');

                    for ($k = 0; $k < count($occorrenze); $k++) {
                        $query_nazione_predefinita = $mysqli->prepare("SELECT codice FROM pf_stati WHERE predefinito = 1 LIMIT 1");
                        $query_nazione_predefinita->execute();
                        $result = $query_nazione_predefinita->get_result();
                        $nazione_predefinita = $result->fetch_assoc();
                        $query_nazione_predefinita->close();

                        $collegato[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $nazione_predefinita['codice'];
                        $query_anagrafica_collegato[$posizioni[$occorrenze[$k]]][$k] .= 'nazione_nascita = "' . db_input($nazione_predefinita['codice']) . '", ';
                        $dett_anagrafica_collegato[$posizioni[$occorrenze[$k]]][$k]['nazione_nascita'] = $nazione_predefinita['codice'];
                    }
                }


                // RECAPITO 1
                $tipo_recapito_collegato = false;
                $occorrenze = array_keys($campi, 'collegato*-*tipo_indirizzo');
                if (count($occorrenze) > 0) {
                    $tipo_recapito_collegato = true;
                }
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $collegato[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;


                    try{
                        $query_seleziona_tipo_indirizzo = $mysqli->prepare('SELECT id_remida 
																FROM decodifiche_dettagli DD
																	LEFT JOIN decodifiche D ON DD.id_decodifica = D.id
																WHERE id_mandante = "' . db_input($_POST['mandante']) . '"
																AND D.tipo = "tipo indirizzo"
																AND (codice_uno = "' . $risultante . '"
																OR codice_due = "' . $risultante . '")');
                        $query_seleziona_tipo_indirizzo->execute();

                        $result = $query_seleziona_tipo_indirizzo->get_result();
                        $tipo_indirizzo = $result->fetch_assoc();
                    }catch(mysqli_sql_exception $e){
                        echo 'ERRORE SELECT INDIRIZZO COLLEGATO DECODIFICHE DETTAGLI: ' . $e->getMessage();
                    }
                    $query_seleziona_tipo_indirizzo->close();

                    $query_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]] .= 'tipo_recapito = "' . db_input($tipo_indirizzo['id_remida']) . '", ';
                    $dett_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]]['tipo_recapito'] = $tipo_indirizzo['id_remida'];
                }
                $occorrenze = array_keys($campi, 'collegato*-*indirizzo');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $collegato[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;

                    if (isset($rec_predefiniti[$occorrenze[$k]]) && $rec_predefiniti[$occorrenze[$k]] == 1)
                        $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]] = 1;
                    else
                        $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]] = 0;

                    $query_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]] .= 'indirizzo = "' . $risultante . '", invio_corrispondenza = "' . $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]] . '", ';
                    $dett_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]]['indirizzo'] = $risultante;

                    if (!$tipo_recapito_collegato) {
                        $query_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]] .= 'tipo_recapito = "' . db_input($tipi_indirizzo[$occorrenze[$k]]) . '", ';
                        $dett_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]]['tipo_recapito'] = $tipi_indirizzo[$occorrenze[$k]];
                    }
                }
                $occorrenze = array_keys($campi, 'collegato*-*cap');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $collegato[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = str_pad($risultante, 5, '0', STR_PAD_LEFT);
                    $query_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]] .= 'cap = "' . str_pad($risultante, 5, '0', STR_PAD_LEFT) . '", ';
                    $dett_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]]['cap'] = str_pad($risultante, 5, '0', STR_PAD_LEFT);
                }
                $occorrenze = array_keys($campi, 'collegato*-*citta');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $collegato[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]] .= 'citta = "' . $risultante . '", ';
                    $dett_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]]['citta'] = $risultante;
                }
                $occorrenze = array_keys($campi, 'collegato*-*provincia');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $collegato[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_provincia = $mysqli->prepare("SELECT cod_provincia FROM province WHERE sigla = ?");
                    $query_provincia->bind_param('s', $risultante);
                    $query_provincia->execute();
                    $result = $query_provincia->get_result();
                    $provincia = $result->fetch_assoc();
                    $query_provincia->close();
                    $query_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]] .= 'provincia = "' . db_input($provincia['cod_provincia']) . '", ';
                    $dett_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]]['provincia'] = $provincia['cod_provincia'];
                }
                $occorrenze = array_keys($campi, 'collegato*-*nazione');
                if (count($occorrenze) > 0) {
                    for ($k = 0; $k < count($occorrenze); $k++) {
                        $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                        if ($risultante != '') {
                            $collegato[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                            $query_recapito[$posizioni[$occorrenze[$k]]][$k] .= 'nazione = "' . $risultante . '", ';
                            $dett_recapito[$posizioni[$occorrenze[$k]]][$k]['nazione'] = $risultante;
                        } else {
                            $query_nazione_predefinita = $mysqli->prepare("SELECT codice FROM pf_stati WHERE predefinito = 1 LIMIT 1");
                            $query_nazione_predefinita->execute();
                            $result = $query_nazione_predefinita->get_result();
                            $nazione_predefinita = $result->fetch_assoc();
                            $query_nazione_predefinita->close();

                            $collegato[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $nazione_predefinita['codice'];
                            $query_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]] .= 'nazione = "' . db_input($nazione_predefinita['codice']) . '", ';
                            $dett_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]]['nazione'] = $nazione_predefinita['codice'];
                        }
                    }
                } else {
                    // Conto le occorrenze dell'indirizzo e imposto la nazione di default
                    $occorrenze = array_keys($campi, 'collegato*-*indirizzo');

                    for ($k = 0; $k < count($occorrenze); $k++) {
                        $collegato[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $nazione_predefinita['codice'];
                        $query_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]] .= 'nazione = "' . db_input($nazione_predefinita['codice']) . '", ';
                        $dett_recapito[$posizioni[$occorrenze[$k]]][$posizioni_indirizzi[$occorrenze[$k]]]['nazione'] = $nazione_predefinita['codice'];
                    }
                    $query_nazione_predefinita = $mysqli->prepare("SELECT codice FROM pf_stati WHERE predefinito = 1 LIMIT 1");
                    $query_nazione_predefinita->execute();
                    $result = $query_nazione_predefinita->get_result();
                    $nazione_predefinita = $result->fetch_assoc();
                    $query_nazione_predefinita->close();
                }

                // RECAPITO TELEFONICO 1
                $occorrenze = array_keys($campi, 'collegato*-*indirizzo-telefono');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $collegato[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;

                    if (isset($rec_predefiniti[$occorrenze[$k]]) && $rec_predefiniti[$occorrenze[$k]] == 1)
                        $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] = 1;
                    else
                        $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] = 0;

                    if ($dati[$occorrenze[$k]] != '')
                        $query_recapito_telefonico[$posizioni[$occorrenze[$k]]][$k] .= 'indirizzo = "' . $risultante . '", usa_per_invio = "' . $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] . '", ';
                    else
                        $query_recapito_telefonico[$posizioni[$occorrenze[$k]]][$k] .= '';
                    $dett_recapito_telefonico[$posizioni[$occorrenze[$k]]][$k]['indirizzo'] = $risultante;
                    if ($dati[$occorrenze[$k]] != '')
                        $query_recapito_telefonico[$posizioni[$occorrenze[$k]]][$k] .= 'tipo_recapito_telefonico = "2", ';
                    $dett_recapito_telefonico[$posizioni[$occorrenze[$k]]][$k]['tipo_recapito_telefonico'] = '2';
                }
                $occorrenze = array_keys($campi, 'collegato*-*indirizzo-email');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $collegato[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;


                    if (isset($rec_predefiniti[$occorrenze[$k]]) && $rec_predefiniti[$occorrenze[$k]] == 1)
                        $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] = 1;
                    else
                        $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] = 0;

                    if ($dati[$occorrenze[$k]] != '')
                        $query_recapito_telefonico1[$posizioni[$occorrenze[$k]]][$k] .= 'indirizzo = "' . $risultante . '", usa_per_invio = "' . $dett_recapito_predefinito[$posizioni[$occorrenze[$k]]][$k] . '", ';
                    else
                        $query_recapito_telefonico1[$posizioni[$occorrenze[$k]]][$k] .= '';
                    $dett_recapito_telefonico1[$posizioni[$occorrenze[$k]]][$k]['indirizzo'] = $risultante;
                    if ($dati[$occorrenze[$k]] != '')
                        $query_recapito_telefonico1[$posizioni[$occorrenze[$k]]][$k] .= 'tipo_recapito_telefonico = "1", ';
                    $dett_recapito_telefonico1[$posizioni[$occorrenze[$k]]][$k]['tipo_recapito_telefonico'] = '1';
                }
            }
        }    //echo 'ANAGRAFICA COLLEGATA: '; print_r($collegato); echo '<br><br>';

        $esistenza_utente = false;
        $array_collegati = array();

        if ($collegato != $collegato1) {
            for ($z = 0; $z < count($collegato); $z++) {
                $id_collegato = '';

                // TODO: IMPLEMENTARE VERIFICA ESISTENZA SU DB

                // INSERIMENTO UTENTE
                $query_collegato_imploded = implode(' ', $query_utente_collegato[$z]);
                //for ($v = 0; $v < count($query_utente_collegato); $v++) {
                //echo 'INSERT INTO utente SET '.rtrim($query_collegato_imploded,', ').'<br><br>';

                $cf = '';
                $pi = '';
                $tipologia = '';
                $cognome = '';
                $codice_anagrafico_mandante = '';
                $ragione_sociale_collegato = '';

                foreach ($dett_utente_collegato[$z] as $dett_collegato) {
                    if (trim($dett_collegato['codice_fiscale']) != '') $cf = db_input($dett_collegato['codice_fiscale']);
                    if (trim($dett_collegato['partita_iva']) != '') $pi = db_input($dett_collegato['partita_iva']);
                    if (trim($dett_collegato['tipologia']) != '') $tipologia = db_input($dett_collegato['tipologia']);
                    if (trim($dett_collegato['cognome']) != '') $cognome = db_input($dett_collegato['cognome']);
                    if (trim($dett_collegato['nome']) != '') $nome = trim(db_input($dett_collegato['nome']));
                    if (trim($dett_collegato['codice_anagrafico_mandante']) != '') $codiceAnagraficoMandante = trim(db_input($dett_collegato['codice_anagrafico_mandante']));
                    if (trim($dett_collegato['ragione_sociale_collegato']) != '') $ragioneSocialeCollegato = trim(db_input($dett_collegato['ragione_sociale_collegato']));
                }

                $codice_anagrafico_mandante = $codiceAnagraficoMandante != '' ? $codiceAnagraficoMandante : $dett_univoco[0]['riferimento'];
                $ragione_sociale_collegato = $ragioneSocialeCollegato != '' ? $ragioneSocialeCollegato : ($nome . ' ' . $cognome);

                if (strpos($query_collegato_imploded, 'cognome = ""') === FALSE && strpos($query_collegato_imploded, 'cognome = " "') === FALSE) {
                    if (trim($cognome) != '') {
                        $query_esistenza_utente = '';
                        if ($pi != '' && $cf != '') {
                            $query_esistenza_utente = 'SELECT * FROM utente WHERE codice_fiscale = "' . $cf . '" OR partita_iva = "' . $pi . '"';
                        } else if ($pi != '') {
                            $query_esistenza_utente = 'SELECT * FROM utente WHERE partita_iva = "' . $pi . '"';
                        } else if ($cf != '') {
                            $query_esistenza_utente = 'SELECT * FROM utente WHERE codice_fiscale = "' . $cf . '"';
                        }

                        if ($query_esistenza_utente != '') {

                            // ------------ QUERY SELEZIONE DATI DA UTENTE CON PREPARE STATEMENT -------------------------
                            $esistenza_utente = $mysqli->prepare($query_esistenza_utente);
                            $esistenza_utente->execute();
                            $result = $esistenza_utente->get_result();
                            $ris_esistenza_utente = $result->fetch_assoc();
                            $esistenza_utente->close();   
                        }                               
                        // print_r($ris_esistenza_utente);
                        if ($query_esistenza_utente != '' && count($ris_esistenza_utente) > 0) $esistenza_utente = true;
                        
                        if ($esistenza_utente) {
                            $dettaglio_collegato = $ris_esistenza_utente;
                            $id_collegato = $dettaglio_collegato['id_utente'];                       
                        }
                        // print_r($id_collegato);

                        $forza_update = 0;
                        if($dettaglio_collegato) $forza_update = 1;
                        
                        // ------------ INSERIMENTO DETT_COLLEGATO IN ED_UTENTE -----------------------------
                        
                        $mysqli->autocommit(FALSE);
                        try{                        
                            $query_insert_utente = $mysqli->prepare('INSERT INTO ed_utente SET id_acquisizione = '.$id_acquisizione.', gruppi_base = 10,' . rtrim($query_collegato_imploded, ', '). ', forza_update = ' . $forza_update);
                            $query_insert_utente->execute();
                            $ed_id_collegato = $mysqli->insert_id;                    
                            $query_insert_utente->close();
                            $mysqli->commit();

                            // echo '<strong> INSERIMENTO COLLEGATO IN ED_UTENTE EFFETTUATO</strong> <br>';
                            
                        }catch (mysqli_sql_exception $e){
                            echo 'ERRORE CREAZIONE COLLEGATO: ' . $e->getMessage() . '<br>';
                            $mysqli->rollback();
                        };
                        $mysqli->autocommit(TRUE);
                        
                        if (trim($dettaglio_collegato['cognome']) != '' && trim($dettaglio_collegato['partita_iva']) != '') echo '<br><strong>ID UTENTE: ' . $id_collegato . ' - ' . $dettaglio_collegato['cognome'] . ' (' . $dettaglio_collegato['partita_iva'] . ')</strong>';
                        else if (trim($dettaglio_collegato['cognome']) != '' && $dettaglio_collegato['codice_fiscale'] != '') echo '<br><strong>ID UTENTE: ' . $id_collegato . ' - ' . $dettaglio_collegato['cognome'] . ' (' . $dettaglio_collegato['codice_fiscale'] . ')</strong>';
                        else if (trim($dettaglio_collegato['cognome']) != '') echo '<strong>ID UTENTE: ' . $id_collegato . ' - ' . $dettaglio_collegato['cognome'] . '</strong>';
                        
                        if ($esistenza_utente) echo ' [ COLLEGATO GIA ESISTENTE]<br><br>';

                        // INSERIMENTO DEL COLLEGATO NELLA TABELLA DEI COLLEGATI
                        if ($id_collegato > 0) {

                            $mysqli->autocommit(FALSE);
                            try{
                                $query_aggiornamento_legame = $mysqli->prepare("INSERT INTO ed_collegati SET id_utente = ?,
                                                                                                         id_tipo = ?,
                                                                                                         id_collegato = ?,
                                                                                                         id_acquisizione = ?");
                                $query_aggiornamento_legame->bind_param('iiii', $ed_id_collegato, $tipologia, $ed_id_utente, $id_acquisizione);
                                $query_aggiornamento_legame->execute();
                                $query_aggiornamento_legame->close();
                                $mysqli->commit();

                                $query_aggiornamento_legame = $mysqli->prepare("INSERT INTO ed_collegati SET id_utente = ?,
                                                                                                         id_tipo = ?,
                                                                                                         id_collegato = ?,
                                                                                                         id_acquisizione = ?");
                                $query_aggiornamento_legame->bind_param('iiii', $ed_id_utente, $tipologia, $ed_id_collegato, $id_acquisizione);
                                $query_aggiornamento_legame->execute();
                                $query_aggiornamento_legame->close();
                                $mysqli->commit();

                                // echo '<strong>INSERIMENTO COLLEGAMENTI IN ED_COLLEGATI EFFETTUATO</strong><br>';

                            }catch (mysqli_sql_exception $e){
                                echo 'ERRORE INSERIMENTO COLLEGAMENTI IN ED_COLLEGATI: '. $e->getMessage() . '<br>';
                                $mysqli->rollback();
                            }
                            $mysqli->autocommit(TRUE);                           
                        }

                        // INSERIMENTO COLLEGATO DEBITORE IN ED_ANAGRAFICA_COLLEGATI_MANDANTE

                        $mysqli->autocommit(FALSE);
                        try{
                            $insert_anagrafica_collegati_mandante = $mysqli->prepare(('INSERT IGNORE INTO ed_anagrafica_collegati_mandante SET
                                                                                        id_acquisizione = '. $id_acquisizione .',
                                                                                        id_mandante = "' . $_POST['mandante'] . '",
                                                                                        id_collegato_pratica = "' . $ed_id_collegato . '", 
                                                                                        id_pratica = NULL,
                                                                                        data_inserimento = "' . date('Y-m-d H:i:s') . '", 
                                                                                        ragione_sociale_collegato = "' . db_input($ragione_sociale_collegato) . '",
                                                                                        codice_anagrafico_mandante = "' . db_input($codice_anagrafico_mandante) . '"'));
                            $insert_anagrafica_collegati_mandante->execute();
                            $mysqli->commit();  
                            
                            // echo '<strong>INSERIMENTO COLLEGATO IN ED_ANAGRAFICA_COLLEGATI_MANDANTE EFFETTUATO</strong><br>';
                            
                        }catch(mysqli_sql_exception $e){
                            echo 'ERRORE INSERIMENTO COLLEGATO IN ED_ANAGRAFICA_COLLEGATI_MANDANTE: ' . $e->getMessage() . '<br>';
                            $mysqli->rollback();
                        };
                        $mysqli->autocommit(TRUE);
                        $insert_anagrafica_collegati_mandante->close();

                    }
                }
                //}

                // INSERIMENTO ALTRI DETTAGLI
                // INSERIMENTO ANAGRAFICA & AGGIORNO id_utente SU utente
                if (!$esistenza_utente) {
                    if (count($query_anagrafica_collegato[$z]) > 0) {
                        
                        for ($v = 0; $v < count($query_anagrafica_collegato[$z]); $v++) {
                            echo 'query_anagrafica_collegato<br>';
                            print_r($query_anagrafica_collegato[$z]);
                            if ($pi == '' && $query_anagrafica_collegato[$z][$v] != '') {

                                $mysqli->autocommit(FALSE);
                                try{
                                    $query_insert_ed_anagrafica = $mysqli->prepare('INSERT INTO ed_anagrafica SET  
                                                                                                    id_utente = '.$ed_id_collegato.', 
                                                                                                    id_acquisizione = '.$id_acquisizione.', ' . 
                                                                                                    rtrim($query_anagrafica_collegato[$z][$v], ', '));
                                    $query_insert_ed_anagrafica->execute();
                                    $mysqli->commit();
                                    // echo '<strong>INSERIMENTO COLLEGATO IN ED_ANAGRAFICA EFFETTUATO</strong><br>';
    
                                }catch (mysqli_sql_exception $e){
                                    echo 'ERRORE INSERIMENTO COLLEGATO IN ED_ANAGRAFICA: ' . $e->getMessage() . '<br>';
                                    $mysqli->rollback();                        
                                };
                                $mysqli->autocommit(TRUE);
                                $query_insert_ed_anagrafica->close();
                            }
                        }
                    } else if ($pi == '') {

                        $mysqli->autocommit(FALSE);
                        try{
                            $query_insert_ed_anagrafica = $mysqli->prepare("INSERT INTO ed_anagrafica SET  
                                                                                    id_utente = '$ed_id_collegato',
                                                                                    id_acquisizione = '$id_acquisizione',
                                                                                    nazione_nascita = 'IT' ");
                            $query_insert_ed_anagrafica->execute();
                            $mysqli->commit();

                            // echo '<strong>INSERIMENTO COLLEGATO IN ED_ANAGRAFICA EFFETTUATO</strong><br>';

                        }catch (mysqli_sql_exception $e){
                            echo 'ERRORE INSERIMENTO COLLEGATO TAB ED_ANAGRAFICA: ' . $e->getMessage() . '<br>';
                            $mysqli->rollback();                        
                        };
                        $mysqli->autocommit(TRUE);
                        $query_insert_ed_anagrafica->close();
                    }
                }

                // INSERIMENTO RECAPITI
                for ($v = 0; $v < count($query_recapito); $v++) {
                    if ($query_recapito[$z][$v] != '' && $dett_recapito[$z][$v]['indirizzo'] != '') {

                        $query_verifica_fonte_operatore = $mysqli->prepare('SELECT * FROM recapito WHERE id_utente = "' . db_input($id_collegato) . '" AND fonte <> 2 AND predefinito = 1');
                        $query_verifica_fonte_operatore->execute();
                        $result = $query_verifica_fonte_operatore->get_result();
                        $esistenza_fonte_operatore = $result->fetch_assoc();
                        $query_verifica_fonte_operatore->close();

                        $forza_update = 0;
                        $valore_predefinito = 0;
                        if ($predefinito == 1 && $v == 0){$valore_predefinito = 1;}
                        if (count($esistenza_fonte_operatore) > 0) {$valore_predefinito = 0;}
                        if ( $valore_predefinito == 1 ){$forza_update = 1;}                
                        
                        $mysqli->autocommit(FALSE);
                        try{
                            $query_insert_ed_recapito = $mysqli->prepare('INSERT INTO ed_recapito SET
                                                                        id_acquisizione = '. $id_acquisizione .',
                                                                        id_utente = "' . $ed_id_collegato . '",
                                                                        fonte = 2,
                                                                        predefinito = "' . db_input($valore_predefinito) . '",
                                                                        attivo = 1,
                                                                        ' . rtrim($query_recapito[$z][$v], ', ') .',
                                                                        forza_update =' . $forza_update);
                            $query_insert_ed_recapito->execute();
                            $mysqli->commit();

                            // echo '<strong>INSERIMENTO RECAPITO COLLEGATO IN ED_RECAPITO EFFETTUATO</strong><br>';

                        }catch(mysqli_sql_exception $e){
                            echo 'ERRORE INSERIMENTO RECAPITO COLLEGATO IN ED_RECAPITO: ' . $e->getMessage() . '<br>';
                            $mysqli->rollback();
                        }
                        $mysqli->autocommit(TRUE);
                        $query_insert_ed_recapito->close();
                    }
                }

                // INSERIMENTO RECAPITI TELEFONICI E EMAIL
                // $debug = true;
                if ($debug) print_r($query_recapito_telefonico);
                if ($debug) print_r($dett_recapito_predefinito);

                for ($v = 0; $v < count($query_recapito_telefonico); $v++) {
                    if ($debug) {
                        echo '<br><br>RECAPITO TELEFONICO, INDICI ' . $z . ' ' . $v . ':<br>';
                        print_r($query_recapito_telefonico[$z][$v]);
                        echo '<br>INDIRIZZO:<br>';
                        print_r($dett_recapito_telefonico[$z][$v]['indirizzo']);
                        echo '<br><br>';
                    }

                    //if($query_recapito_telefonico[$z][$v] != '' && $dett_recapito_telefonico[$z][$v]['indirizzo'] != '') {
                    if ($query_recapito_telefonico[$v][$v] != '' && $dett_recapito_telefonico[$v][$v]['indirizzo'] != '') {

                        try{
                            $query_verifica_fonte_operatore = $mysqli->prepare('SELECT * FROM recapito_telefonico WHERE id_utente = "' . db_input($id_collegato) . '" AND fonte <> 2 AND      principale = 1 AND tipo_recapito_telefonico = 2');
                            $query_verifica_fonte_operatore->execute();
                            $mysqli->commit();
                            $result = $query_verifica_fonte_operatore->get_result();
                            $esistenza_fonte_operatore = $result->fetch_assoc();
    
                        }catch(mysqli_sql_exception $e){
                            echo 'ERRORE SELECT RECAPITO TELEFONICO: ' . $e->getMessage() . '<br>';
                            $mysqli->rollback();
                        }
                        $query_verifica_fonte_operatore->close();
                       
                        $forza_update = 0;
                        $attivo = 0;
                        if (strpos($query_recapito_telefonico[$z][$v], 'usa_per_invio = "1"') !== FALSE) $attivo = 1;
                        // if (count($esistenza_fonte_operatore) > 0){
                        //     $forza_update = 1;
                        // }
                        // print_r($query_recapito_telefonico[$v][$v]);
                        $mysqli->autocommit(FALSE);
                        try{
                            $query_insert_ed_recapito_telefonico = $mysqli->prepare('INSERT INTO ed_recapito_telefonico SET
                                                                                                id_utente = "' . $ed_id_collegato . '",
                                                                                                fonte = 2,
                                                                                                attivo = ' . $attivo . ', 
                                                                                                principale = 0,  
                                                                                                ' . rtrim($query_recapito_telefonico[$v][$v], ', ')
                                                                                                .', forza_update = ' . $forza_update .
                                                                                                ',id_acquisizione = ' . $id_acquisizione .';');
                            $query_insert_ed_recapito_telefonico->execute();
                            $mysqli->commit();     
                            
                            // echo '<strong>INSERIMENTO RECAPITO COLLEGATO ED_RECAPITO_TELEFONICO EFFETTUATO</strong><br>';
                            
                        }catch(mysqli_sql_exception $e){
                            echo 'ERRORE INSERIMENTO RECAPITO COLLEGATO ED_RECAPITO_TELEFONICO: ' . $e->getMessage() . '<br>';
                            $mysqli->rollback();
                        }
                        $mysqli->autocommit(TRUE);
                        $query_insert_ed_recapito_telefonico->close();
                    }
                }

                if ($debug) print_r($query_recapito_telefonico1);

                for ($v = 0; $v < count($query_recapito_telefonico1); $v++) {
                    if ($debug) {
                        echo '<br><br>RECAPITO TELEFONICO 1, INDICI ' . $z . ' ' . $v . ': ';
                        print_r($query_recapito_telefonico1[$z][$v]);
                        echo '<br>INDIRIZZO: ';
                        print_r($dett_recapito_telefonico1[$z][$v]['indirizzo']);
                        echo '<br><br>';
                    }

                    if ($query_recapito_telefonico1[$v][$v] != '' && $dett_recapito_telefonico1[$v][$v]['indirizzo'] != '') {
                        //echo 'INSERT INTO recapito_telefonico SET '.rtrim($query_recapito_telefonico1[$z][$v],', ').'<br><br>';

                        $mysqli->autocommit(FALSE);
                        try{
                            $query_verifica_fonte_operatore = $mysqli->prepare('SELECT * FROM recapito_telefonico WHERE id_utente = "' . db_input($id_collegato) . '" AND fonte <> 2 AND      principale = 1 AND tipo_recapito_telefonico = 1');
                            $query_verifica_fonte_operatore->execute();
                            $mysqli->commit();
                            $result = $query_verifica_fonte_operatore->get_result();
                            $esistenza_fonte_operatore = $result->fetch_assoc();
    
                        }catch(mysqli_sql_exception $e){
                            echo 'ERRORE SELECT RECAPITO_TELEFONICO1: ' . $e->getMessage() . '<br>';
                            $mysqli->rollback();
                        }
                        $query_verifica_fonte_operatore->close();
                        $mysqli->autocommit(TRUE);
                       
                        $forza_update = 0;
                        $attivo = 0;
                        if (strpos($query_recapito_telefonico[$z][$v], 'usa_per_invio = "1"') !== FALSE) $attivo = 1;
                        // if (count($esistenza_fonte_operatore) > 0){
                        //     $forza_update = 1;
                        // }
                        // print_r($query_recapito_telefonico[$v][$v]);
                        $mysqli->autocommit(FALSE);
                        try{
                            $query_insert_ed_recapito_telefonico = $mysqli->prepare('INSERT INTO ed_recapito_telefonico SET
                                                                                                id_utente = "' . $ed_id_collegato . '",
                                                                                                fonte = 2,
                                                                                                attivo = ' . $attivo . ', 
                                                                                                principale = 0,  
                                                                                                ' . rtrim($query_recapito_telefonico1[$v][$v], ', ')
                                                                                                .', forza_update = ' . $forza_update .
                                                                                                ',id_acquisizione = ' . $id_acquisizione .';');
                            $query_insert_ed_recapito_telefonico->execute();
                            $mysqli->commit();     
                            
                            // echo '<strong>INSERIMENTO RECAPITO1 COLLEGATO ED_RECAPITO_TELEFONICO EFFETTUATO</strong><br>';
                            
                        }catch(mysqli_sql_exception $e){
                            echo 'ERRORE INSERIMENTO RECAPITO1 COLLEGATO ED_RECAPITO_TELEFONICO: ' . $e->getMessage() . '<br>';
                            $mysqli->rollback();
                        }
                        $mysqli->autocommit(TRUE);
                        $query_insert_ed_recapito_telefonico->close();

                    }
                }
            }
        } else if (count($collegato) != 0) {
            echo '<strong>COME PRECEDENTE</strong><br>';
        } else {
            echo '<strong>NON SETTATO</strong><br>';
        }

        $collegato1 = $collegato;
        ?>

        <br><br>INSERIMENTO PRATICA:<br>    
        <?php
        // PRATICA
        {
            //SALVO EVENTO DA ESEGUIRE

            $query_pratica = array();

            $dett_pratica = array();
            $dett_pratica_campi_personalizzati = array();

            if (isset($_POST['fixed_data_affidamento']) && $_POST['fixed_data_affidamento'] != '') {
                $query_pratica[0] .= 'data_affidamento = "' . db_input($_POST['fixed_data_affidamento']) . '", ';
                $dett_pratica[0]['data_affidamento'] = $_POST['fixed_data_affidamento'];
            } else {
                $occorrenze = array_keys($campi, 'altro*-*data_affidamento');
                if (count($occorrenze) > 0) {
                    for ($k = 0; $k < count($occorrenze); $k++) {
                        $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                        $altro[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                        $query_pratica[$k] .= 'data_affidamento = "' . converti_data_acquisita($risultante) . '", ';
                        $dett_pratica[$k]['data_affidamento'] = converti_data_acquisita($risultante);
                    }
                } else {
                    $query_pratica[0] .= 'data_affidamento = "' . date('Y-m-d') . '", ';
                    $dett_pratica[0]['data_affidamento'] = date('Y-m-d');
                }
            }
            if (isset($_POST['fixed_data_fine_mandato']) && $_POST['fixed_data_fine_mandato'] != '') {
                $query_pratica[0] .= 'data_fine_mandato = "' . db_input($_POST['fixed_data_fine_mandato']) . '", ';
                $dett_pratica[0]['data_fine_mandato'] = $_POST['fixed_data_fine_mandato'];
            } else {
                $occorrenze = array_keys($campi, 'altro*-*data_fine_mandato');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $altro[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_pratica[$k] .= 'data_fine_mandato = "' . converti_data_acquisita($risultante) . '", ';
                    $dett_pratica[$k]['data_fine_mandato'] = converti_data_acquisita($risultante);
                }
            }
            $occorrenze = array_keys($campi, 'pratica*-*affidato_capitale');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $pratica[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                $query_pratica[$k] .= 'affidato_capitale = "' . converti_importo($risultante) . '", ';
                $dett_pratica[$k]['affidato_capitale'] = converti_importo($risultante);
            }
            $occorrenze = array_keys($campi, 'pratica*-*affidato_spese');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $pratica[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                $query_pratica[$k] .= 'affidato_spese = "' . converti_importo($risultante) . '", ';
                $dett_pratica[$k]['affidato_spese'] = converti_importo($risultante);
            }
            $occorrenze = array_keys($campi, 'pratica*-*affidato_interessi');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $pratica[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                $query_pratica[$k] .= 'affidato_interessi = "' . converti_importo($risultante) . '", ';
                $dett_pratica[$k]['affidato_interessi'] = converti_importo($risultante);
            }
            $occorrenze = array_keys($campi, 'pratica*-*affidato_1');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $pratica[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                $query_pratica[$k] .= 'affidato_1 = "' . converti_importo($risultante) . '", ';
                $dett_pratica[$k]['affidato_1'] = converti_importo($risultante);
            }
            $occorrenze = array_keys($campi, 'pratica*-*affidato_2');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $pratica[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                $query_pratica[$k] .= 'affidato_2 = "' . converti_importo($risultante) . '", ';
                $dett_pratica[$k]['affidato_2'] = converti_importo($risultante);
            }
            $occorrenze = array_keys($campi, 'pratica*-*affidato_3');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $pratica[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                $query_pratica[$k] .= 'affidato_3 = "' . converti_importo($risultante) . '", ';
                $dett_pratica[$k]['affidato_3'] = converti_importo($risultante);
            }
            $occorrenze = array_keys($campi, 'pratica*-*competenze_oneri_recupero');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $pratica[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                $query_pratica[$k] .= 'competenze_oneri_recupero = "' . converti_importo($risultante) . '", ';
                $dett_pratica[$k]['competenze_oneri_recupero'] = converti_importo($risultante);
            }
            $occorrenze = array_keys($campi, 'pratica*-*competenze_spesse_incasso');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $pratica[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                $query_pratica[$k] .= 'competenze_spese_incasso = "' . converti_importo($risultante) . '", ';
                $dett_pratica[$k]['competenze_spese_incasso'] = converti_importo($risultante);
            }
            $occorrenze = array_keys($campi, 'pratica*-*cash_balance');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $pratica[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                $query_pratica[$k] .= 'cash_balance = "' . converti_importo($risultante) . '", ';
                $dett_pratica[$k]['cash_balance'] = converti_importo($risultante);
            }
            $occorrenze = array_keys($campi, 'pratica*-*riferimento_mandante_1');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $pratica[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                $query_pratica[$k] .= 'riferimento_mandante_1 = "' . $risultante . '", ';
                $dett_pratica[$k]['riferimento_mandante_1'] = $risultante;
            }
            $occorrenze = array_keys($campi, 'pratica*-*riferimento_mandante_2');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $pratica[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                $query_pratica[$k] .= 'riferimento_mandante_2 = "' . $risultante . '", ';
                $dett_pratica[$k]['riferimento_mandante_2'] = $risultante;
            }
            $occorrenze = array_keys($campi, 'pratica*-*riferimento_mandante_3');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $pratica[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                $query_pratica[$k] .= 'riferimento_mandante_3 = "' . $risultante . '", ';
                $dett_pratica[$k]['riferimento_mandante_3'] = $risultante;
            }
            $occorrenze = array_keys($campi, 'pratica*-*annotazioni');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $pratica[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                $query_pratica[$k] .= 'annotazioni = "' . $risultante . '", ';
                $dett_pratica[$k]['annotazioni'] = $risultante;
            }
            $occorrenze = array_keys($campi, 'pratica*-*data_inizio_interessi');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $pratica[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                $query_pratica[$k] .= 'data_inizio_interessi = "' . converti_data_acquisita($risultante) . '", ';
                $dett_pratica[$k]['data_inizio_interessi'] = converti_data_acquisita($risultante);
            }
            $occorrenze = array_keys($campi, 'pratica*-*cp_valuta');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $pratica[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                //$query_pratica[$k] .= 'cp_valuta = "'.converti_importo($risultante).'", ';
                $dett_pratica_campi_personalizzati[$k]['cp_valuta'] = converti_importo($risultante);
            }
            $occorrenze = array_keys($campi, 'pratica*-*cp_testo');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $pratica[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                //$query_pratica[$k] .= 'cp_testo = "'.addslashes($risultante).'", ';
                $dett_pratica_campi_personalizzati[$k]['cp_testo'] = $risultante;
            }
            $occorrenze = array_keys($campi, 'pratica*-*cp_data');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $pratica[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                //$query_pratica[$k] .= 'cp_data = "'.converti_data_acquisita($risultante).'", ';
                $dett_pratica_campi_personalizzati[$k]['cp_data'] = converti_data_acquisita($risultante);
            }

            $occorrenze = array_keys($campi, 'pratica*-*id_filiale_origine');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $pratica[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                $query_pratica[$k] .= 'id_filiale_origine = "' . $risultante . '", ';
                $dett_pratica[$k]['id_filiale_origine'] = $risultante;
            }

            $occorrenze = array_keys($campi, 'pratica*-*gbv');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $pratica[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                $query_pratica[$k] .= 'gbv	 = "' . $risultante . '", ';
                $dett_pratica[$k]['gbv'] = $risultante;
            }

            $occorrenze = array_keys($campi, 'pratica*-*prezzo_acquisto');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $pratica[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                $query_pratica[$k] .= 'prezzo_di_acquisto		 = "' . $risultante . '", ';
                $dett_pratica[$k]['prezzo_di_acquisto'] = $risultante;
            }
        } 
        //    echo 'PRATICA: '; print_r(count($pratica)); echo '<br><br>';
        //    echo 'PRATICA1: '; print_r($pratica1); echo '<br><br>';

        $pratica_creata = $dett_pratica;

        if ($pratica != $pratica1) {

            if ($pratica1 != '' && $id_pratica != '')
                e_aggiornaIterPratica($id_ed_pratica);

            // INSERIMENTO PRATICA
            for ($v = 0; $v < count($query_pratica); $v++) {
                // $debug = true;
                if ($debug) {
                    echo '<strong>AVVIO INSERIMENTO PRATICA</strong><br>';
                    echo 'DETTAGLIO UNIVOCO DEBITORE: ';
                    print_r($dett_univoco_debitore);
                    echo '<br>';
                    echo 'LOTTO MANDANTE ID ' . $lotto_mandante['id'] . '<br>';
                    echo 'QUERY PRT ' . $query_pratica[$v] . '<br>';
                }

                if ($dett_univoco_debitore[0]['cognome'] != '' && ($_POST['contratto'] > 0 || $count_contratti > 0)) {
                    if ($debug) echo 'SONO PRESENTI SIA COGNOME CHE CONTRATTO<br>';

                    if ($_POST['contratto'] > 0) {
                        $query_accettaz_auto = 'SELECT accettazione_auto
                                              FROM contratto
                                              WHERE id = "' . db_input($_POST['contratto']) . '"';
                    } else {
                        $query_accettaz_auto = 'SELECT accettazione_auto
                                              FROM contratto
                                              WHERE id = "' . db_input($id_contratto) . '"';
                    }

                    $select_accettaz_auto = $mysqli->prepare($query_accettaz_auto);
                    $select_accettaz_auto->execute();
                    $result = $select_accettaz_auto->get_result();
                    $contratto_accettaz_auto = $result->fetch_assoc();
                    $select_accettaz_auto->close();                  


                    if (stripos($query_pratica[$v], 'id_filiale_origine') !== false) {
                        $query_inserimento_pratica = 'INSERT INTO ed_pratiche 
                                                            SET id_debitore = "' . $ed_id_utente . '",
                                                                id_acquisizione = '. $id_acquisizione .',
                                                                id_creditore = "' . $ed_id_creditore . '",
                                                                id_mandante = "' . db_input($_POST['mandante']) . '",
                                        						' .
                    // AGGIUNTO PER ERRORE SULLA CHIAVE.. NON ERANO DISABILITATE LE CHIAVI DURANTE GLI IMPORT ?
                            ((isset($lotto_mandante['id']) and $lotto_mandante['id'] != null and $lotto_mandante['id'] != '') ? ('id_lotto_mandante = "' . db_input($lotto_mandante['id']) . '",') : '')
                            . '
                                                                id_operatore = "' . db_input($_SESSION['user_admin_id']) . '",
                                                                id_contratto = "' . db_input($id_contratto) . '",
                                                                accettata_mandante = "' . db_input($contratto_accettaz_auto['accettazione_auto']) . '",
                                                                ultima_modifica = "' . db_input(date('Y-m-d H:i:s')) . '"';

                        if ($query_pratica[$v] != '') {
                            $query_inserimento_pratica .= ', ' . rtrim($query_pratica[$v], ', ');
                        }
                    } else {
                        $query_inserimento_pratica = 'INSERT INTO ed_pratiche 
                                                            SET id_filiale_origine = "' . $_POST["id_filiale"] . '",
                                                                id_acquisizione = '. $id_acquisizione .',
                                                                id_debitore = "' . $ed_id_utente . '",
                                                                id_creditore = "' . $ed_id_creditore . '",
                                                                id_mandante = "' . db_input($_POST['mandante']) . '",
                                        						' .
                    // AGGIUNTO PER ERRORE SULLA CHIAVE.. NON ERANO DISABILITATE LE CHIAVI DURANTE GLI IMPORT ?
                            ((isset($lotto_mandante['id']) and $lotto_mandante['id'] != null and $lotto_mandante['id'] != '') ? ('id_lotto_mandante = "' . db_input($lotto_mandante['id']) . '",') : '')
                            . '
                                                                id_operatore = "' . db_input($_SESSION['user_admin_id']) . '",
                                                                id_contratto = "' . db_input($id_contratto) . '",
                                                                accettata_mandante = "' . db_input($contratto_accettaz_auto['accettazione_auto']) . '",
                                                                ultima_modifica = "' . db_input(date('Y-m-d H:i:s')) . '"';

                        if ($query_pratica[$v] != '') {
                            $query_inserimento_pratica .= ', ' . rtrim($query_pratica[$v], ', ');
                        }
                    }

                    $queryVerificaEsistenzaPratica = "SELECT id from pratiche WHERE riferimento_mandante_1='" . $dett_univoco[0]['riferimento'] . "' AND id_mandante='" . $_POST['mandante'] . "' AND data_chiusura is null";
                    $selectVerificaEsistenzaPratica = $mysqli->prepare($queryVerificaEsistenzaPratica);
                    $selectVerificaEsistenzaPratica->execute();
                    $risVerificaEsistenzaPratica = $selectVerificaEsistenzaPratica->get_result();
                    $dati_pratica = $risVerificaEsistenzaPratica->fetch_assoc();                  
                    $id_pratica = $dati_pratica['id'];
                    // ------- SE LA SELECT NON TROVA RISULTATI L'ID PRATICA NON C'È E VA IN ERRORE... VERIFICARE!! ----------
                    $selectVerificaEsistenzaPratica->close();
                    
                    if ($flagRiprendiPratiche && $risVerificaEsistenzaPratica->num_rows > 0) {
                        $id_pratica = $dati_pratica['id'];
                        $imported_practices[] = $id_pratica;               
                                            
                    } else {
                        $mysqli->autocommit(FALSE);
                        try {
                            $inserimento_pratica = $mysqli->prepare($query_inserimento_pratica);
                            $inserimento_pratica->execute();
                            $id_ed_pratica = $mysqli->insert_id;
                            $mysqli->commit();
                    
                            // echo '<strong>INSERIMENTO PRATICA IN ED_PRATICHE EFFETTUATO</strong><br>';
                            
                        }catch(mysqli_sql_exception $e) {
                            echo 'ERRORE INSERIMENTO PRATICA IN ED_PRATICHE' . $e->getMessage() . '<br>';
                            $mysqli->rollback();
                        }
                        $inserimento_pratica->close();
                        $mysqli->autocommit(TRUE);

                        //-------- VERIFICHE E UPDATE ACQUISIZIONE_DATI, PRATICHE, UTENTE NELLA SP -------

                        $imported_practices[] = $id_ed_pratica;                   
                    }                      
                }
                else {
                    if ($debug) echo 'NON SONO PRESENTI TUTTI I DETTAGLI NECESSARI, PROVO A RICERCARE UNA PRATICA ESISTENTE<br>';
                    if ($debug) echo 'DETTAGLIO UNIVOCO DEBITORE = ' . $dett_univoco_debitore[0]['cognome'] . '<br>';
                    if ($debug) echo 'CONTRATTO UNIVOCO = ' . $_POST['contratto'] . '<br>';
                    if ($debug) echo 'CONTATORE ALTRI CONTRATTI = ' . $count_contratti . '<br>';

                    // $query_selezione_pratica = 'SELECT id_pratica FROM acquisizione_dati WHERE id_debitore > 0 AND riferimento_mandante = "'.db_input($dett_univoco[0]['riferimento']).'" ORDER BY data_inserimento DESC';
                    if (isset($dett_univoco[0]['riferimento'])) {
                        $query_selezione_pratica = 'SELECT id FROM pratiche WHERE riferimento_mandante_1 = "' . db_input($dett_univoco[0]['riferimento']) . '" AND id_mandante = "' . $_POST['mandante'] . '" ORDER BY id DESC';
                    } else if (isset($dett_univoco[0]['riferimento2'])) {
                        $query_selezione_pratica = 'SELECT id FROM pratiche WHERE riferimento_mandante_2 = "' . db_input($dett_univoco[0]['riferimento2']) . '" AND id_mandante = "' . $_POST['mandante'] . '" ORDER BY id DESC';
                    }

                    $selezione_pratica = $mysqli->prepare($query_selezione_pratica);
                    $selezione_pratica->execute();
                    $ris_selezione_pratica = $selezione_pratica->get_result();
                    $ris_selezione_pratica->fetch_array();
             
                    if ($ris_selezione_pratica->num_rows > 0) {                      
                        
                        $id_pratica = $ris_selezione_pratica['id'];

                        if ($debug) echo 'HO TROVATO UNA PRATICA ESISTENTE CON ID ' . $id_pratica . '<br>';

                        $updated_practices[] = $id_pratica;       
                        
                        //-------- VERIFICHE E UPDATE ACQUISIZIONE_DATI, PRATICHE, UTENTE NELLA SP -------
                    } 
                    else {

                        if ($debug) echo 'NON HO TROVATO UNA PRATICA ESISTENTE<br>';
                        if ($debug) echo 'IL DEBITORE è ID ' . $id_debitore . '<br>';
                        if ($debug) echo 'LOTTO MANDANTE ID ' . $lotto_mandante['id'] . '<br>';

                        $id_debitore = 0;
                        $id_creditore = 0;

                        if ($_POST['contratto'] > 0) {
                            $query_accettaz_auto = 'SELECT accettazione_auto
                                              FROM contratto
                                              WHERE id = "' . db_input($_POST['contratto']) . '"';
                        } else {
                            $query_accettaz_auto = 'SELECT accettazione_auto
                                              FROM contratto
                                              WHERE id = "' . db_input($id_contratto) . '"';
                        }

                        $select_accettaz_auto = $mysqli->prepare($query_accettaz_auto);
                        $select_accettaz_auto->execute();
                        $result = $select_accettaz_auto->get_result();
                        $contratto_accettaz_auto = $result->fetch_assoc();
                        $select_accettaz_auto->close();                       


                        if (stripos($query_pratica[$v], 'id_filiale_origine') !== false) {
                            $query_inserimento_pratica = 'INSERT INTO ed_pratiche 
																SET id_acquisizione = '. $id_acquisizione .',
                                                                    id_debitore = "' . $id_debitore . '",
																	id_creditore = "' . db_input($id_creditore) . '",
																	id_mandante = "' . db_input($_POST['mandante']) . '",' .
                                // AGGIUNTO PER ERRORE SULLA CHIAVE.. NON ERANO DISABILITATE LE CHIAVI DURANTE GLI IMPORT ?
                                ((isset($lotto_mandante['id']) and $lotto_mandante['id'] != null and $lotto_mandante['id'] != '') ? ('id_lotto_mandante = "' . db_input($lotto_mandante['id']) . '",') : '')
                                . 'id_operatore = "' . db_input($_SESSION['user_admin_id']) . '",
																	id_contratto = "' . db_input($id_contratto) . '",
																	accettata_mandante = "' . db_input($contratto_accettaz_auto['accettazione_auto']) . '",
																	ultima_modifica = "' . db_input(date('Y-m-d H:i:s')) . '"';

                            if ($query_pratica[$v] != '') {
                                $query_inserimento_pratica .= ', ' . rtrim($query_pratica[$v], ', ');
                            }

                        } else {
                            $query_inserimento_pratica = 'INSERT INTO ed_pratiche 
																SET id_filiale_origine = "' . $_POST["id_filiale"] . '",   
																	id_acquisizione = '. $id_acquisizione .',
                                                                    id_debitore = "' . $id_debitore . '",
																	id_creditore = "' . db_input($id_creditore) . '",
																	id_mandante = "' . db_input($_POST['mandante']) . '",' .
                                // AGGIUNTO PER ERRORE SULLA CHIAVE.. NON ERANO DISABILITATE LE CHIAVI DURANTE GLI IMPORT ?
                                ((isset($lotto_mandante['id']) and $lotto_mandante['id'] != null and $lotto_mandante['id'] != '') ? ('id_lotto_mandante = "' . db_input($lotto_mandante['id']) . '",') : '')
                                . 'id_operatore = "' . db_input($_SESSION['user_admin_id']) . '",
																	id_contratto = "' . db_input($id_contratto) . '",
																	accettata_mandante = "' . db_input($contratto_accettaz_auto['accettazione_auto']) . '",
																	ultima_modifica = "' . db_input(date('Y-m-d H:i:s')) . '"';

                            if ($query_pratica[$v] != '') {
                                $query_inserimento_pratica .= ', ' . rtrim($query_pratica[$v], ', ');
                            }
                        }

                        if ($debug) echo 'AZZERO IL DEBITORE ED ESEGUO LA QUERY:<br>' . $query_inserimento_pratica . '<br>';

                        $queryVerificaEsistenzaPratica = "SELECT id from pratiche WHERE riferimento_mandante_1='" . $dett_univoco[0]['riferimento'] . "' AND id_mandante='" . $_POST['mandante'] . "' AND data_chiusura is null";
                        $selectVerificaEsistenzaPratica = $mysqli->prepare($queryVerificaEsistenzaPratica);
                        $selectVerificaEsistenzaPratica->execute();
                        $risVerificaEsistenzaPratica = $selectVerificaEsistenzaPratica->get_result();
                        $dati_pratica = $risVerificaEsistenzaPratica->fetch_assoc();                  
                        $id_pratica = $dati_pratica['id'];
                        // ------- SE LA SELECT NON TROVA RISULTATI L'ID PRATICA NON C'È E VA IN ERRORE... VERIFICARE!! ----------
                        $selectVerificaEsistenzaPratica->close();
                       
                        if ($flagRiprendiPratiche && $risVerificaEsistenzaPratica->num_rows > 0) {                        
                            $id_pratica = $dati_pratica['id'];
                            $imported_practices[] = $id_pratica;

                        } else {
                            $mysqli->autocommit(FALSE);
                            try {
                                $inserimento_pratica = $mysqli->prepare($query_inserimento_pratica);
                                $inserimento_pratica->execute();
                                $id_ed_pratica = $mysqli->insert_id;
                                $mysqli->commit();

                                // echo '<strong>INSERIMENTO PRATICA IN ED_PRATICHE EFFETTUATO</strong><br>';
                                
                            }catch(mysqli_sql_exception $e) {
                                echo 'ERRORE INSERIMENTO PRATICA IN ED_PRATICHE ' . $e->getMessage() . '<br>';
                                $mysqli->rollback();
                            }
                            $inserimento_pratica->close();
                            $mysqli->autocommit(TRUE);

                            $imported_practices[] = $id_ed_pratica;

                            //-------- VERIFICHE E UPDATE ACQUISIZIONE_DATI, PRATICHE, UTENTE NELLA SP -------
                            
                        }
                    }
                }

                $query_pratiche_elaborate = 'SELECT * FROM ed_pratiche WHERE id = "' . $id_ed_pratica . '" AND id_acquisizione = "' . $id_acquisizione . '"';
                $select_pratiche_elaborate = $mysqli->prepare($query_pratiche_elaborate);
                $select_pratiche_elaborate->execute();
                $ris_pratiche_elaborate = $select_pratiche_elaborate->get_result();
                $dettaglio_pratica = $ris_pratiche_elaborate->fetch_array();

                $praticheElaborate[] = $dettaglio_pratica['id'];

                // echo 'ID ED PRATICA: '.$id_ed_pratica . '<br>';
                // echo 'ID DEBITORE: '.$id_debitore . '<br>';

                // INSERIMENTO DEBITORE IN COLLEGATI PRATICA
                if ($id_debitore > 0) {

                    $select_id_collegati_pratica = $mysqli->prepare('SELECT COUNT(id) AS n FROM collegati_pratica WHERE id_pratica = "' . $id_pratica . '" AND id_collegato = "' . $id_debitore . '" AND id_tipo = "8"');
                    $select_id_collegati_pratica->execute();
                    $result = $select_id_collegati_pratica->get_result();
                    $countAnagraficaCollegatiPratica = $result->fetch_assoc();
                    $select_id_collegati_pratica->close();
                                
                    if ($countAnagraficaCollegatiPratica['n'] <= 0) {
                        $mysqli->autocommit(FALSE);
                        try{
                            $query_inserimento_collegati_pratica = $mysqli->prepare('INSERT INTO e_collegati_pratica 
                                                                                    SET id_pratica = "' . $id_ed_pratica . '",
                                                                                        id_acquisizione = '. $id_acquisizione .',
                                                                                        id_tipo = "8",
                                                                                        id_collegato = "' . db_input($ed_id_utente) . '"');                                                          
                            $query_inserimento_collegati_pratica->execute();
                            $mysqli->commit();

                            // echo '<strong>INSERIMENTO DEBITORE IN E_COLLEGATI_PRATICA EFFETTUATO</strong><br>';

                        }catch(mysqli_sql_exception $e) {
                            echo 'ERRORE INSERIMENTO DEBITORE IN E_COLLEGATI_PRATICA ' . $e->getMessage() . '<br>';
                            $mysqli->rollback();                            
                        }
                        $mysqli->autocommit(TRUE);
                        $query_inserimento_collegati_pratica->close();

                        $selectCheck = "SELECT id 
                                        from ed_anagrafica_collegati_mandante 
                                        WHERE  (id_pratica='' OR id_pratica is null OR id_pratica=0) 
                                          AND id_mandante=(SELECT id_mandante from ed_pratiche WHERE id=" . $id_ed_pratica . ") 
                                          AND id_collegato_pratica='" . $ed_id_utente . "'
                                          AND id_acquisizione = '" . $id_acquisizione . "'";
                        $queryCheck = $mysqli->prepare($selectCheck);
                        $queryCheck->execute();
                        $result = $queryCheck->get_result();
                        $risCheck = $result->fetch_assoc();

                        //TODO --- ANAGRAFICA COLLEGATI MANDANTE MODIFICO ID PRATICA SU TAB PROVVISORIE VERIFICANDO LE RIGHE INSERITE PRECEDENTEMENTE DEGLI UTENTI, VA BENE? O INSERIRE FORZA UPDATE PER MODIFICARLO POI NELLA SP??                                             
                    
                        if (count($risCheck) > 0) {

                            // $forza_update = 1;

                            $mysqli->autocommit(FALSE);
                            try{
                                $queryUpdateAnagMand = $mysqli->prepare("UPDATE ed_anagrafica_collegati_mandante 
                                                            SET id_pratica='" . $id_ed_pratica . "'
                                                            WHERE (id_pratica='' OR id_pratica is null OR id_pratica=0) 
                                                            AND id_mandante=(SELECT id_mandante from ed_pratiche WHERE id=" . $id_ed_pratica . ") 
                                                            AND id_collegato_pratica='" . $ed_id_utente . "'
                                                            AND id_acquisizione = '" . $id_acquisizione . "'");
                                $queryUpdateAnagMand->execute();
                                $mysqli->commit();

                                // echo '<strong>AGGIORNAMENTO ID_PRATICA ED_ANAGRAFICA_COLLEGATI_MANDANTE EFFETTUATO</strong><br>';
    
                            }catch(mysqli_sql_exception $e) {
                                echo 'ERRORE AGGIORNAMENTO ID_PRATICA ED_ANAGRAFICA_COLLEGATI_MANDANTE '. $e->getMessage() . '<br>';
                                $mysqli->rollback();                                
                            }
                            $mysqli->autocommit(TRUE);
                            $queryUpdateAnagMand->close();

                        } else {
                            // $forza_update = 0;

                            $mysqli->autocommit(FALSE);
                            try{
                                $queryInsertColl = $mysqli->prepare("INSERT IGNORE INTO ed_anagrafica_collegati_mandante(id_mandante, id_collegato_pratica, codice_anagrafico_mandante, ragione_sociale_collegato, id_pratica, data_inserimento, id_acquisizione) 
                                                                    SELECT id_mandante, id_collegato_pratica, codice_anagrafico_mandante, ragione_sociale_collegato, 0, now(), id_acquisizione 
                                                                    FROM ed_anagrafica_collegati_mandante 
                                                                    WHERE id_mandante=(SELECT id_mandante from ed_pratiche WHERE id=" . $id_ed_pratica . ") 
                                                                    AND id_collegato_pratica='" . $ed_id_utente . "' 
                                                                    LIMIT 0,1");
                                $queryInsertColl->execute();
                                $mysqli->commit();

                                // echo '<strong>INSERIMENTO ED_ANAGRAFICA_COLLEGATI_MANDANTE EFFETTUATO</strong><br>';
    
                            }catch(mysqli_sql_exception $e) {
                                echo 'ERRORE INSERIMENTO ED_ANAGRAFICA_COLLEGATI_MANDANTE '. $e->getMessage() . '<br>';
                                $mysqli->rollback();                                
                            }
                            $mysqli->autocommit(TRUE);
                            $queryInsertColl->close();

                            if ($dett_univoco_debitore[0]['cognome'] != "") {

                                // $forza_update = 1;

                                $mysqli->autocommit(FALSE);
                                try{
                                    $queryUpdateAnagMand = $mysqli->prepare("UPDATE ed_anagrafica_collegati_mandante 
                                                                SET id_pratica='" . $id_ed_pratica . "'
                                                                    ragione_sociale_collegato='" . db_input($dett_univoco_debitore[0]['cognome']) . "' 
                                                                    WHERE (id_pratica='' OR id_pratica is null OR id_pratica=0) 
                                                                    AND id_mandante=(SELECT id_mandante from ed_pratiche WHERE id=" . $id_ed_pratica . ") 
                                                                    AND id_collegato_pratica='" . $ed_id_utente . "'
                                                                    AND id_acquisizione = '" . $id_acquisizione . "'");
                                    $queryUpdateAnagMand->execute();
                                    $mysqli->commit();

                                    // echo '<strong>AGGIORNAMENTO COGNOME ED_ANAGRAFICA_COLLEGATI_MANDANTE EFFETTUATO</strong><br>';
        
                                }catch(mysqli_sql_exception $e) {
                                    echo 'ERRORE AGGIORNAMENTO COGNOME ED_ANAGRAFICA_COLLEGATI_MANDANTE '. $e->getMessage() . '<br>';
                                    $mysqli->rollback();                                
                                }
                                $mysqli->autocommit(TRUE);
                                $queryUpdateAnagMand->close();

                            } else {
                                // $forza_update = 1;

                                $mysqli->autocommit(FALSE);
                                try{
                                    $queryUpdateAnagMand = $mysqli->prepare("UPDATE ed_anagrafica_collegati_mandante 
                                                                SET id_pratica='" . $id_ed_pratica . "'
                                                                WHERE (id_pratica='' OR id_pratica is null OR id_pratica=0) 
                                                                AND id_mandante=(SELECT id_mandante from ed_pratiche WHERE id=" . $id_ed_pratica . ") 
                                                                AND id_collegato_pratica='" . $ed_id_utente . "'
                                                                AND id_acquisizione = '" . $id_acquisizione . "'");
                                    $queryUpdateAnagMand->execute();
                                    $mysqli->commit();

                                    // echo '<strong>AGGIORNAMENTO ID_PRATICA SE NON E PRESENTE COGNOME ED_ANAGRAFICA_COLLEGATI_MANDANTE EFFETTUATO</strong><br>';
        
                                }catch(mysqli_sql_exception $e) {
                                    echo 'ERRORE AGGIORNAMENTO ID_PRATICA SE NON E PRESENTE COGNOME ED_ANAGRAFICA_COLLEGATI_MANDANTE '. $e->getMessage() . '<br>';
                                    $mysqli->rollback();                                
                                }
                                $mysqli->autocommit(TRUE);
                                $queryUpdateAnagMand->close();
                            }
                        }
                    }
                    $mysqli->autocommit(FALSE);
                    try{
                        $query_inserimento_collegati_pratica = $mysqli->prepare('UPDATE ed_pratiche 
                                                                                SET id_debitore = "' . $ed_id_utente . '"
                                                                                WHERE id = "' . $id_ed_pratica . '"
                                                                                AND id_acquisizione = "' . $id_acquisizione . '"');
                        $query_inserimento_collegati_pratica->execute();
                        $mysqli->commit();

                        // echo '<strong>AGGIORNAMENTO ID_DEBITORE IN ED_PRATICHE EFFETTUATO</strong><br>';
                        
                    }catch(mysqli_sql_exception $e) {
                        echo 'ERRORE AGGIORNAMENTO ID_DEBITORE IN ED_PRATICHE '. $e->getMessage() . '<br>';
                        $mysqli->rollback();                                
                    }
                    $mysqli->autocommit(TRUE);
                    $query_inserimento_collegati_pratica->close();
                }

                print_r($array_collegati_pratica['id']);

                if (isset($array_collegati_pratica) && count($array_collegati_pratica) > 0) {
                    for ($y = 0; $y < count($array_collegati_pratica); $y++) {

                        $select_id_collegati_pratica = $mysqli->prepare('SELECT COUNT(id) AS n FROM collegati_pratica WHERE id_pratica = "' . $id_pratica . '" AND id_collegato = "' . db_input($array_collegati_pratica[$y]['id']) . '" AND id_tipo = "' . db_input($array_collegati_pratica[$y]['tipo']) . '"');
                        $select_id_collegati_pratica->execute();
                        $result = $select_id_collegati_pratica->get_result();
                        $countAnagraficaCollegatiPratica = $result->fetch_assoc();
                        $select_id_collegati_pratica->close();

                        if ($countAnagraficaCollegatiPratica['n'] <= 0 && $array_collegati_pratica[$y]['id'] > 0) {

                            $mysqli->autocommit(FALSE);
                            try{
                                $query_inserimento_collegati_pratica = $mysqli->prepare('INSERT INTO e_collegati_pratica 
                                                                                        SET id_pratica = "' . $id_ed_pratica . '",
                                                                                            id_acquisizione = '. $id_acquisizione .',
                                                                                            id_tipo = "' . db_input($array_collegati_pratica[$y]['tipo']) . '",
                                                                                            id_collegato = "' . db_input($array_collegati_pratica[$y]['id']) . '"');                                                          
                                $query_inserimento_collegati_pratica->execute();
                                $mysqli->commit();

                                // echo '<strong>INSERIMENTO COLLEGATO IN E_COLLEGATI_PRATICA EFFETTUATO</strong><br>';

                            }catch(mysqli_sql_exception $e) {
                                echo 'ERRORE INSERIMENTO COLLEGATO IN E_COLLEGATI_PRATICA ' . $e->getMessage() . '<br>';
                                $mysqli->rollback();                            
                            }
                            $mysqli->autocommit(TRUE);
                            $query_inserimento_collegati_pratica->close();

                            $mysqli->autocommit(FALSE);
                            try{
                                $queryUpdateAnagMand = $mysqli->prepare("UPDATE ed_anagrafica_collegati_mandante 
                                                            SET id_pratica='" . $id_ed_pratica . "'
                                                            WHERE (id_pratica='' OR id_pratica is null OR id_pratica=0) 
                                                            AND id_mandante=(SELECT id_mandante from ed_pratiche WHERE id=" . $id_ed_pratica . ") 
                                                            AND id_collegato_pratica='" . $ed_id_garante . "'
                                                            AND id_acquisizione = '" . $id_acquisizione . "'");
                                $queryUpdateAnagMand->execute();
                                $mysqli->commit();

                                // echo '<strong>AGGIORNAMENTO ID_PRATICA ED_ANAGRAFICA_COLLEGATI_MANDANTE EFFETTUATO</strong><br>';
    
                            }catch(mysqli_sql_exception $e) {
                                echo 'ERRORE AGGIORNAMENTO ID_PRATICA ED_ANAGRAFICA_COLLEGATI_MANDANTE '. $e->getMessage() . '<br>';
                                $mysqli->rollback();                                
                            }
                            $mysqli->autocommit(TRUE);
                            $queryUpdateAnagMand->close();
                        }
                    }
                }

                //echo $query_inserimento_collegati_pratica;

                // AGGIORNAMENTO DEL CONTRATTO E DEL LOTTO STUDIO IN BASE ALLE IMPOSTAZIONI DEFINITE
                $valore = $_POST['applica_quando_valore'];
                $campo = $_POST['applica_quando_campo'];
                $operatore = $_POST['applica_quando_operatore'];
                $contratto = $_POST['applica_quando_contratto'];
                $id_contratto = '';
                $n_lotto = '';

                $count_contratti_2 = 0;
                for ($m = 0; $m < count($_POST['applica_quando_contratto']); $m++) {
                    if ($_POST['applica_quando_contratto'][$m] != '')
                        $count_contratti_2++;
                }

                if ($_POST['contratto'] > 0 || $count_contratti_2 > 0) {
                    $found = false;

                    if ($count_contratti_2 > 0) {
                        for ($r = 0; $r < count($operatore); $r++) {
                            if ($operatore[$r] == '=') {
                                if ($debug) {
                                    echo 'DETTAGLIO PRATICA ' . $campo[$r] . ' = ' . $dett_pratica[0][str_replace('pratica*-*', '', $campo[$r])] . ' = ' . $valore[$r] . '<br>';
                                }

                                if ($dett_pratica[0][str_replace('pratica*-*', '', $campo[$r])] == $valore[$r]) {
                                    if ($contratto[$r] > 0) {
                                        $id_contratto = $contratto[$r];
                                        $n_lotto = $r + 1;
                                        $found = true;
                                    }
                                }
                                if ($debug) {
                                    echo 'DETTAGLIO PRATICA PERSONALIZZATI ' . $campo[$r] . ' = ' . $dett_pratica_campi_personalizzati[0][str_replace('pratica*-*', '', $campo[$r])] . ' = ' . $valore[$r] . '<br>';
                                }
                                if ($dett_pratica_campi_personalizzati[0][str_replace('pratica*-*', '', $campo[$r])] == $valore[$r]) {
                                    if ($contratto[$r] > 0) {
                                        $id_contratto = $contratto[$r];
                                        $n_lotto = $r + 1;
                                        $found = true;
                                    }
                                }
                            } else if ($operatore[$r] == '>') {
                                if (isset($dett_pratica[0][str_replace('pratica*-*', '', $campo[$r])]) && $dett_pratica[0][str_replace('pratica*-*', '', $campo[$r])] != "")
                                    if ($dett_pratica[0][str_replace('pratica*-*', '', $campo[$r])] > $valore[$r]) {
                                        if ($contratto[$r] > 0) {
                                            $id_contratto = $contratto[$r];
                                            $n_lotto = $r + 1;
                                            $found = true;
                                        }
                                    }
                                if (isset($dett_pratica_campi_personalizzati[0][str_replace('pratica*-*', '', $campo[$r])]) && $dett_pratica_campi_personalizzati[0][str_replace('pratica*-*', '', $campo[$r])] != "")
                                    if ($dett_pratica_campi_personalizzati[0][str_replace('pratica*-*', '', $campo[$r])] > $valore[$r]) {
                                        if ($contratto[$r] > 0) {
                                            $id_contratto = $contratto[$r];
                                            $n_lotto = $r + 1;
                                            $found = true;
                                        }
                                    }
                            } else if ($operatore[$r] == '<') {

                                if (isset($dett_pratica[0][str_replace('pratica*-*', '', $campo[$r])]) && $dett_pratica[0][str_replace('pratica*-*', '', $campo[$r])] != "")
                                    if ($dett_pratica[0][str_replace('pratica*-*', '', $campo[$r])] < $valore[$r]) {
                                        if ($contratto[$r] > 0) {
                                            $id_contratto = $contratto[$r];
                                            $n_lotto = $r + 1;
                                            $found = true;
                                        }
                                    }
                                if (isset($dett_pratica_campi_personalizzati[0][str_replace('pratica*-*', '', $campo[$r])]) && $dett_pratica_campi_personalizzati[0][str_replace('pratica*-*', '', $campo[$r])] != "")
                                    if ($dett_pratica_campi_personalizzati[0][str_replace('pratica*-*', '', $campo[$r])] < $valore[$r]) {
                                        if ($contratto[$r] > 0) {
                                            $id_contratto = $contratto[$r];
                                            $n_lotto = $r + 1;
                                            $found = true;
                                        }
                                    }
                            } else if ($operatore[$r] == '!=') {
                                if (isset($dett_pratica[0][str_replace('pratica*-*', '', $campo[$r])]) && $dett_pratica[0][str_replace('pratica*-*', '', $campo[$r])] != "")
                                    if ($dett_pratica[0][str_replace('pratica*-*', '', $campo[$r])] != $valore[$r]) {
                                        if ($contratto[$r] > 0) {
                                            $id_contratto = $contratto[$r];
                                            $n_lotto = $r + 1;
                                            $found = true;
                                        }
                                    }

                                if (isset($dett_pratica_campi_personalizzati[0][str_replace('pratica*-*', '', $campo[$r])]) && $dett_pratica_campi_personalizzati[0][str_replace('pratica*-*', '', $campo[$r])] != "")
                                    if ($dett_pratica_campi_personalizzati[0][str_replace('pratica*-*', '', $campo[$r])] != $valore[$r]) {
                                        if ($contratto[$r] > 0) {
                                            $id_contratto = $contratto[$r];
                                            $n_lotto = $r + 1;
                                            $found = true;
                                        }
                                    }
                            }
                        }
                    } else if (isset($_POST['contratto']) && $_POST['contratto'] != '') {
                        $id_contratto = $_POST['contratto'];
                        $n_lotto = 0;
                        $found = true;
                    }

                    if (!$found) {
                        $id_contratto = $_POST['contratto'];
                        $n_lotto = 0;
                    }
                }

                $aggiornamento_data_fine_mandato = '';
                if (isset($dett_pratica[0]['data_fine_mandato']) && $dett_pratica[0]['data_fine_mandato'] > '1970-01-01 00:00:00') {
                    $aggiornamento_data_fine_mandato = $dett_pratica[0]['data_fine_mandato'];
                } else {
                    if ($debug) {
                        echo 'SELECT durata_mandato FROM contratto WHERE id = "' . db_input($id_contratto) . '"<br>';
                    }

                    $query_recupero_durata = $mysqli->prepare('SELECT durata_mandato FROM contratto WHERE id = "' . db_input($id_contratto) . '"');
                    $query_recupero_durata->execute();
                    $result = $query_recupero_durata->get_result();
                    $contratto = $result->fetch_array();
                    $query_recupero_durata->close();

                    $aggiornamento_data_fine_mandato = date('Y-m-d', strtotime($dett_pratica[0]['data_affidamento'] . ' +' . $contratto['durata_mandato'] . ' days'));
                    if ($debug) {
                        echo 'DATA FINE MANDATO: ' . db_input($aggiornamento_data_fine_mandato) . '"<br>';
                    }
                }

                # INDIVIDUAZIONE DELL'ITER DI APPARTENENZA DELLA PRATICA
                {
                    # VERIFICA DELL'ESISTENZA DI COPERTURA ESATTORIALE (necessaria per attribuire il corretto iter)
                    $query_copertura_esattoriale = "";

                    $copertura_esattoriale = false;

                    {
                        $query_copertura_esattoriale = $mysqli->prepare("SELECT DISTINCT U.*
																	FROM (
																		SELECT PC.id, PC.id_utente, 1 AS PHC, (CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) AS max_num_prat, COUNT(*) AS numero_pratiche
																		FROM phone_collector PC 
																			INNER JOIN utente U on PC.id_utente = U.id_utente
																			LEFT JOIN pratiche P on PC.id_utente = P.id_collector
																		WHERE U.attivo = 1 OR U.attivo IS NULL
																		GROUP BY PC.id_utente, PC.max_num_prat
																		HAVING ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) = 0) OR ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) > COUNT(*))
																	
																		UNION
																	
																		SELECT E.id, E.id_utente, 0 AS PHC, (CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) AS max_num_prat, COUNT(*) AS numero_pratiche
																		FROM esattore E 
																			INNER JOIN utente U on E.id_utente = U.id_utente
																			LEFT JOIN pratiche P on E.id_utente = P.id_collector
																		WHERE U.attivo = 1 OR U.attivo IS NULL
																		GROUP BY E.id_utente, E.max_num_prat
																		HAVING ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) = 0) OR ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) > COUNT(*))
																	) AS U  
																	  
																	LEFT JOIN zona_geografica_competenza UZ ON U.id_utente = UZ.id_utente  
																	LEFT JOIN prodotti_lavorabili UP ON U.id_utente = UP.id_utente 
																	LEFT JOIN credito C ON UP.id_prodotto = C.codice 
																	LEFT JOIN carico_collector CC ON CC.id_collector = U.id_utente
																	LEFT JOIN prodotti_lavorabili PL ON PL.id_prodotto = CC.codice
																		 
																	WHERE (U.PHC = 1 
																			OR (U.PHC = 0 
																					AND (
																						(UZ.zona_esatt = 1 AND UZ.tipo_zona = 'Nazione' AND UZ.da in (SELECT PR.nazione FROM pratiche P INNER JOIN recapito PR ON P.id_debitore = PR.id_utente WHERE P.id = '" . db_input($id_pratica) . "' AND PR.predefinito = 1))
																					OR  (UZ.zona_esatt = 1 AND UZ.tipo_zona = 'Regione' AND UZ.da in (SELECT PP.cod_regione FROM pratiche P INNER JOIN recapito PR ON P.id_debitore = PR.id_utente INNER JOIN province PP ON PR.provincia = PP.cod_provincia WHERE P.id = '" . db_input($id_pratica) . "' AND PR.predefinito = 1))
																					OR  (UZ.zona_esatt = 1 AND UZ.tipo_zona = 'Provincia' AND UZ.da in (SELECT PR.provincia FROM pratiche P INNER JOIN recapito PR ON P.id_debitore = PR.id_utente WHERE P.id = '" . db_input($id_pratica) . "' AND PR.predefinito = 1))
																					OR  (UZ.zona_esatt = 1 AND UZ.tipo_zona = 'Cap' AND UZ.da >= (SELECT MIN(PR.cap) FROM pratiche P INNER JOIN recapito PR ON P.id_debitore = PR.id_utente WHERE P.id = '" . db_input($id_pratica) . "' AND PR.predefinito = 1) AND UZ.a <= (SELECT MAX(PR.cap) FROM pratiche P INNER JOIN recapito PR ON P.id_debitore = PR.id_utente WHERE P.id = '" . db_input($id_pratica) . "' AND PR.predefinito = 1))
																					OR  (UZ.zona_esatt = 1 AND UZ.tipo_zona = 'Citta' AND UZ.da in (SELECT PC.cod_istat FROM pratiche P INNER JOIN recapito PR ON P.id_debitore = PR.id_utente INNER JOIN comuni PC ON PR.citta = PC.comune AND PR.provincia=PC.cod_provincia WHERE P.id = '" . db_input($id_pratica) . "' AND PR.predefinito = 1))
																					)
																				)   
																			)
																	AND ( C.id IS NULL OR C.id IN (SELECT id_tipo_credito FROM contratto WHERE id = '" . db_input($id_contratto) . "'))
																	AND U.id_utente NOT IN (SELECT (CASE WHEN value_id IS NULL THEN 0 ELSE value_id END) AS value_id FROM pratiche Pbis LEFT JOIN view_agenti_esclusi Cbis ON Cbis.id = Pbis.id_contratto WHERE Pbis.id = '" . db_input($id_pratica) . "')
																	AND U.PHC = 0");

                        // SE DOVESSIMO INCLUDERE I PHC RIMUOVERE DALLA WHERE "AND PHC = 0"
                    }

                    $query_copertura_esattoriale->execute();
                    $result = $query_copertura_esattoriale->get_result();
                    $ris_copertura_esattoriale = $result->fetch_assoc();

                    $risTrovati = count($ris_copertura_esattoriale);

                    if ($debug) echo '<br><br>' . $query_copertura_esattoriale . '<br><br>';
                    if ($debug) echo 'RISULTATI TROVATI = ' . $risTrovati . '<br>';

                    if ($risTrovati > 0) {
                        $copertura_esattoriale = true;
                    }
                    $id_iter = 0;
                    # Recupero i dettagli del contratto per verificare la condizione "ITER LAVORO" della scheda 12.1
                    if ($copertura_esattoriale)
                        $query_recupero_iter_contratto = 'SELECT copertura, soglia_composizione, import_fino, tipo_report FROM contratto_iter_lavoro WHERE id_contratto = "' . db_input($id_contratto) . '" AND (copertura = "si" OR copertura = "non_influenza") ORDER BY import_fino ASC, copertura DESC';
                    else
                        $query_recupero_iter_contratto = 'SELECT copertura, soglia_composizione, import_fino, tipo_report FROM contratto_iter_lavoro WHERE id_contratto = "' . db_input($id_contratto) . '" AND (copertura = "no" OR copertura = "non_influenza") ORDER BY import_fino ASC, copertura ASC';
                        
                        
                    if ($debug) echo $query_recupero_iter_contratto . '<br>';
                        
                    $select_recupero_iter_contratto = $mysqli->prepare($query_recupero_iter_contratto);
                    $select_recupero_iter_contratto->execute();
                    $result_recupero_iter_contratto = $select_recupero_iter_contratto->get_result();

                    if ($result_recupero_iter_contratto->num_rows > 0) {
                        while ($iter_contratto = $result_recupero_iter_contratto->fetch_array()) {
                            $ret = calcolatore($iter_contratto['soglia_composizione'], e_pratiche_getSingoleQuote($id_ed_pratica));
                            if ($ret <= $iter_contratto['import_fino']) {
                                $id_iter = $iter_contratto['tipo_report'];
                                break;
                            }
                        }
                    } else {
                        # Recupero i dati dell'iter dalla sezione IMPOSTAZIONI DI BASE
                        $query_recupero_iter_base ='SELECT iter_di_lavoro FROM impostazioni_base WHERE id = 1';
                        $select_recupero_iter_base = $mysqli->prepare($query_recupero_iter_base);
                        $select_recupero_iter_base->execute();
                        $result_recupero_iter_base = $select_recupero_iter_base->get_result();

                        if ($result_recupero_iter_base->num_rows > 0) {
                            $iter_contratto = $result_recupero_iter_base->fetch_array();
                            $id_iter = $iter_contratto['iter_di_lavoro'];
                        }
                    }
                }

                if ($debug) echo 'AGGIORNAMENTO DATI PRATICA -> ID CONTRATTO: ' . $id_contratto . '<br>';

                if ($id_contratto > 0) {

                    if ($debug) {
                        echo '<br>';
                        print_r($lotto_studio[$n_lotto]);
                        echo '<br>';
                    }

                    if ($debug) {
                        echo 'UPDATE pratiche SET id_contratto = "' . db_input($id_contratto) . '", id_lotto_studio = "' . db_input($lotto_studio[$n_lotto]['id']) . '", id_iter = "' . db_input($id_iter) . '", data_fine_mandato = "' . db_input($aggiornamento_data_fine_mandato) . '", stato_corrente = stato_corrente WHERE id = "' . db_input($dettaglio_pratica['id']) . '" AND id_acquisizione = "' . $id_acquisizione . '"<br>';
                    }

                    if ($_POST['aggiorna_lotto_studio'] == 0) {
                        $mysqli->autocommit(FALSE);
                        try{
                            $update_dati_contratto = $mysqli->prepare('UPDATE ed_pratiche SET id_contratto = "' . db_input($id_contratto) . '", id_lotto_studio = "' . db_input($lotto_studio[$n_lotto]['id']) . '", id_iter = "' . db_input($id_iter) . '", data_fine_mandato = "' . db_input($aggiornamento_data_fine_mandato) . '", stato_corrente = stato_corrente WHERE id = "' . db_input($dettaglio_pratica['id']) . '" AND id_acquisizione = "' . $id_acquisizione . '"');
                            $update_dati_contratto->execute();

                            $mysqli->commit();
                            
                            // echo '<strong>AGGIORNAMENTO DATI CONTRATTO IN ED_PRATICHE EFFETTUATO</strong><br>';
    
                        }catch(mysqli_sql_exception $e) {
                            echo 'ERRORE AGGIORNAMENTO DATI CONTRATTO IN ED_PRATICHE '. $e->getMessage() . '<br>';
                            $mysqli->rollback();                             
                        }
                        $mysqli->autocommit(TRUE);
                        $update_dati_contratto->close();
                    }
                    // AGGIORNAMENTO DATE SU LOTTI

                    $query_selezione_date_affidamento = 'SELECT id_lotto_mandante, id_lotto_studio, data_affidamento, data_fine_mandato FROM ed_pratiche WHERE id = "' . db_input($dettaglio_pratica['id']) . '"';
                    $select_date_affidamento = $mysqli->prepare($query_selezione_date_affidamento);
                    $select_date_affidamento->execute();
                    $ris_select_date_affidamento = $select_date_affidamento->get_result();
                    $dett_agg_pratica = $ris_select_date_affidamento->fetch_array();

                    if ($debug) echo 'UPDATE lotti_mandante SET data = "' . db_input($dett_agg_pratica['data_affidamento']) . '" WHERE id = "' . db_input($dett_agg_pratica['id_lotto_mandante']) . '"';

                    $mysqli->autocommit(FALSE);
                    try{
                        $update_data_aff_lotti_mandante = $mysqli->prepare('UPDATE ed_lotti_mandante SET data = "' . db_input($dett_agg_pratica['data_affidamento']) . '" WHERE id = "' . db_input($dett_agg_pratica['id_lotto_mandante']) . '"');
                        $update_data_aff_lotti_mandante->execute();

                        $mysqli->commit();

                        // echo '<strong>AGGIORNAMENTO DATA AFFIDAMENTO IN ED_LOTTI_MANDANTE EFFETTUATO</strong><br>';
    
                    }catch(mysqli_sql_exception $e) {
                        echo '<span style="color:red;font-weight:bold;">ERRORE AGGIORNAMENTO DATA AFFIDAMENTO IN ED_LOTTI_MANDANTE</span> '. $e->getMessage() . '<br>';
                        $mysqli->rollback();                                
                    }
                    $mysqli->autocommit(TRUE);
                    $update_data_aff_lotti_mandante->close();

                    if ($debug) echo 'UPDATE lotto_studio SET data = "' . db_input($dett_agg_pratica['data_fine_mandato']) . '" WHERE id = "' . db_input($dett_agg_pratica['id_lotto_studio']) . '"';

                    $mysqli->autocommit(FALSE);
                    try{
                        $update_data_aff_lotto_studio = $mysqli->prepare('UPDATE ed_lotto_studio SET data = "' . db_input($dett_agg_pratica['data_affidamento']) . '" WHERE id = "' . db_input($dett_agg_pratica['id_lotto_mandante']) . '"');
                        $update_data_aff_lotto_studio->execute();

                        $mysqli->commit();

                        // echo '<strong>AGGIORNAMENTO DATA AFFIDAMENTO IN ED_LOTTO_STUDIO EFFETTUATO</strong><br>';
    
                    }catch(mysqli_sql_exception $e) {
                        echo 'ERRORE AGGIORNAMENTO DATA AFFIDAMENTO IN ED_LOTTO_STUDIO '. $e->getMessage() . '<br>';
                        $mysqli->rollback();                                
                    }
                    $mysqli->autocommit(TRUE);
                    $update_data_aff_lotto_studio->close();
                }

                if (count($dett_pratica_campi_personalizzati) > 0) {
                    $campi_valuta = '';
                    $campi_testo = '';
                    $campi_data = '';

                    for ($vv = 0; $vv < count($dett_pratica_campi_personalizzati); $vv++) {
                        $campi_valuta .= $dett_pratica_campi_personalizzati[$vv]['cp_valuta'] . '::';
                        $campi_testo .= $dett_pratica_campi_personalizzati[$vv]['cp_testo'] . '::';
                        $campi_data .= $dett_pratica_campi_personalizzati[$vv]['cp_data'] . '::';
                    }

                    $mysqli->autocommit(FALSE);
                    try{
                        $update_campi_personalizzati_pratiche = $mysqli->prepare('UPDATE ed_pratiche SET cp_valuta_concat = "' . db_input($campi_valuta) . '", cp_testo_concat = "' . db_input($campi_testo) . '", cp_data_concat = "' . db_input($campi_data) . '" WHERE id = "' . db_input($dettaglio_pratica['id']) . '"');
                        $update_campi_personalizzati_pratiche->execute();

                        $mysqli->commit();

                        // echo '<strong>AGGIORNAMENTO CAMPI PERSONALIZZATI IN ED_PRATICHE EFFETTUATO</strong><br>';
    
                    }catch(mysqli_sql_exception $e) {
                        echo 'ERRORE AGGIORNAMENTO CAMPI PERSONALIZZATI IN ED_PRATICHE '. $e->getMessage() . '<br>';
                        $mysqli->rollback();                                
                    }
                    $mysqli->autocommit(TRUE);
                    $update_campi_personalizzati_pratiche->close();
                }

                echo '<strong>ID PRATICA: ' . $dettaglio_pratica['id'] . ' - Capitale: ' . number_format($dettaglio_pratica['affidato_capitale'], 2, ',', '.') . ' [associazione a contratto ID ' . $id_contratto . ']</strong><br>';

                /*
                $query_pratiche_acq = 'SELECT id, id_debitore
												FROM pratiche WHERE id = "' . $dettaglio_pratica['id'] . '"';
                $ris_pratiche_acq = db_query($query_pratiche_acq);

                while ($pratica_acq = mysql_fetch_assoc($ris_pratiche_acq)) {
                    $query_pratiche_collegate_acq = 'SELECT id
															FROM pratiche 
															WHERE id <> "' . $pratica_acq['id'] . '"
															AND id_debitore = "' . $pratica_acq['id_debitore'] . '"';
                    $ris_pratiche_collegate_acq = db_query($query_pratiche_collegate_acq);

                    while ($pratica_collegata_acq = mysql_fetch_assoc($ris_pratiche_collegate_acq)) {
                        $query_agg_1 = 'INSERT IGNORE INTO pratiche_collegate
												  SET id_pratica = "' . db_input($pratica_acq['id']) . '",
												  id_pratica_coll = "' . db_input($pratica_collegata_acq['id']) . '"';
                        $ris_agg_1 = db_query($query_agg_1);

                        $query_agg_2 = 'INSERT IGNORE INTO pratiche_collegate
												  SET id_pratica = "' . db_input($pratica_collegata_acq['id']) . '",
												  id_pratica_coll = "' . db_input($pratica_acq['id']) . '"';
                        $ris_agg_2 = db_query($query_agg_2);
                    }
                }
                */
            }
        } else if (count($pratica) != 0) {
            echo '<strong>COME PRECEDENTE</strong> - ID PRATICA: <strong>' . $id_pratica . '</strong><br>';

            if (isset($array_collegati_pratica) && count($array_collegati_pratica) > 0) {
                for ($y = 0; $y < count($array_collegati_pratica); $y++) {

                    $select_id_collegati_pratica = $mysqli->prepare('SELECT COUNT(id) AS n FROM collegati_pratica WHERE id_pratica = "' . $id_pratica . '" AND id_collegato = "' . db_input($array_collegati_pratica[$y]['id']) . '" AND id_tipo = "' . db_input($array_collegati_pratica[$y]['tipo']) . '"');
                    $select_id_collegati_pratica->execute();
                    $result = $select_id_collegati_pratica->get_result();
                    $countAnagraficaCollegatiPratica = $result->fetch_assoc();
                    $select_id_collegati_pratica->close();

                    if ($countAnagraficaCollegatiPratica['n'] <= 0 && $array_collegati_pratica[$y]['id'] > 0) {


                        $forza_update = 1;

                        $mysqli->autocommit(FALSE);
                        try{
                            $query_inserimento_collegati_pratica = $mysqli->prepare('INSERT INTO e_collegati_pratica 
                                                                                    SET id_pratica = "' . $id_ed_pratica . '",
                                                                                        id_acquisizione = '. $id_acquisizione .',
                                                                                        id_tipo = "' . db_input($array_collegati_pratica[$y]['tipo']) . '",
                                                                                        id_collegato = "' . db_input($array_collegati_pratica[$y]['id']) . '"');                                                          
                            $query_inserimento_collegati_pratica->execute();
                            $mysqli->commit();

                            // echo '<strong>INSERIMENTO COLLEGATO IN E_COLLEGATI_PRATICA EFFETTUATO</strong><br>';

                        }catch(mysqli_sql_exception $e) {
                            echo 'ERRORE INSERIMENTO COLLEGATO IN E_COLLEGATI_PRATICA ' . $e->getMessage() . '<br>';
                            $mysqli->rollback();                            
                        }
                        $mysqli->autocommit(TRUE);
                        $query_inserimento_collegati_pratica->close();

                        $mysqli->autocommit(FALSE);
                        try{
                            $queryUpdateAnagMand = $mysqli->prepare("UPDATE ed_anagrafica_collegati_mandante 
                                                        SET id_pratica='" . $id_ed_pratica . "',
                                                            e_forza_update='" . $forza_update . "' 
                                                        WHERE (id_pratica='' OR id_pratica is null OR id_pratica=0) 
                                                        AND id_mandante=(SELECT id_mandante from ed_pratiche WHERE id=" . $id_ed_pratica . ") 
                                                        AND id_collegato_pratica='" . db_input($array_collegati_pratica[$y]['id']) . "'
                                                        AND id_acquisizione = '" . $id_acquisizione . "'");
                            $queryUpdateAnagMand->execute();
                            $mysqli->commit();

                            // echo '<strong>AGGIORNAMENTO ID_PRATICA ED_ANAGRAFICA_COLLEGATI_MANDANTE EFFETTUATO</strong><br>';

                        }catch(mysqli_sql_exception $e) {
                            echo 'ERRORE AGGIORNAMENTO ID_PRATICA ED_ANAGRAFICA_COLLEGATI_MANDANTE '. $e->getMessage() . '<br>';
                            $mysqli->rollback();                                
                        }
                        $mysqli->autocommit(TRUE);
                        $queryUpdateAnagMand->close();
                    }
                }
            }
        } else {
            echo '<strong>NON PRESENTE</strong><br>';
        }

        $pratica1 = $pratica;
        ?>

        <br><br>INSERIMENTO TITOLO:<br>
        <?php

        // TITOLO
        {
            $query_titolo = array();
            $numero_titolo_array = array();
            $dett_titolo = array();

            $occorrenze = array_keys($campi, 'titolo*-*numero');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $titolo[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                $query_titolo[$k] .= 'numero = "' . $risultante . '", ';
                $numero_titolo_array[$k] = $risultante;

                $dett_titolo[$k]['numero'] = $risultante;
            }
            $occorrenze = array_keys($campi, 'titolo*-*data_affidamento');
            if (count($occorrenze) > 0) {
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $titolo[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_titolo[$k] .= 'data_affidamento = "' . converti_data_acquisita($risultante) . '", ';
                    $dett_titolo[$k]['data_affidamento'] = converti_data_acquisita($risultante);
                }
            } else {
                if (strlen($dettaglio_pratica['data_affidamento']) > 6) {
                    $query_titolo[0] .= 'data_affidamento = "' . db_input($dettaglio_pratica['data_affidamento']) . '", ';
                    $dett_titolo[0]['data_affidamento'] = $dettaglio_pratica['data_affidamento'];
                } else {
                    $query_titolo[0] .= 'data_affidamento = "' . date('Y-m-d') . '", ';
                    $dett_titolo[0]['data_affidamento'] = date('Y-m-d');
                }
            }
            $occorrenze = array_keys($campi, 'titolo*-*descrizione');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $titolo[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                $query_titolo[$k] .= 'descrizione = "' . $risultante . '", ';
                $dett_titolo[$k]['descrizione'] = $risultante;
            }
            $occorrenze = array_keys($campi, 'titolo*-*data_emissione');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $titolo[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                $query_titolo[$k] .= 'data_emissione = "' . converti_data_acquisita($risultante) . '", ';
                $dett_titolo[$k]['data_emissione'] = converti_data_acquisita($risultante);
            }
            $occorrenze = array_keys($campi, 'titolo*-*data_scadenza');
            if (count($occorrenze) > 0) {
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $titolo[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_titolo[$k] .= 'data_scadenza = "' . converti_data_acquisita($risultante) . '", ';
                    $dett_titolo[$k]['data_scadenza'] = converti_data_acquisita($risultante);
                }
            } else {
                $query_titolo[$k] .= 'data_scadenza = "' . db_input($dett_pratica[0]['data_affidamento']) . '", ';
                $dett_titolo[$k]['data_scadenza'] = $dett_titolo[0]['data_affidamento'];
            }
            //NATURA CREDITO
            $naturaCredito = 0;
            if (isset($_POST['natura_credito']) && $_POST['natura_credito'] != '') {
                $query_titolo[0] .= 'id_natura_credito = "' . db_input($_POST['natura_credito']) . '", ';
                $dett_titolo[0]['id_natura_credito'] = $_POST['natura_credito'];

                $naturaCredito = $_POST['natura_credito'];
            }
            else {
                $occorrenze = array_keys($campi, 'titolo*-*natura_credito');
                if (count($occorrenze) > 0) {
                    for ($k = 0; $k < count($occorrenze); $k++) {
                        if ($occorrenze[$k] >= 300) {
                            $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);


                            $query_seleziona_tipo_pagamento = $mysqli->prepare('SELECT id_remida 
																FROM decodifiche_dettagli DD
																	LEFT JOIN decodifiche D ON DD.id_decodifica = D.id
																WHERE id_mandante = "' . db_input($_POST['mandante']) . '"
																AND D.tipo = "nature del credito"
																AND (codice_uno = "' . $risultante . '"
																OR codice_due = "' . $risultante . '")');
                            $query_seleziona_tipo_pagamento->execute();

                            $result = $query_seleziona_tipo_pagamento->get_result();
                            $tipo_pagamento = $result->fetch_assoc();
                            $query_seleziona_tipo_pagamento->close();

                            $titolo[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $tipo_pagamento['id_remida'];
                            //$query_titolo[$k] .= 'natura_credito = "'.addslashes($risultante).'", ';
                            //$dett_titolo[$k]['natura_credito'] = $risultante;

                            // FORZO LA NATURA DEL CREDITO A QUELLA FISSA IMPOSTATA
                            $query_titolo[$k] .= 'id_natura_credito = "' . db_input($tipo_pagamento['id_remida']) . '", ';
                            $dett_titolo[$k]['id_natura_credito'] = $tipo_pagamento['id_remida'];

                            $naturaCredito = $tipo_pagamento['id_remida'];
                        } else {

                            $query_seleziona_tipo_pagamento = $mysqli->prepare('SELECT id_remida 
																FROM decodifiche_dettagli DD
																	LEFT JOIN decodifiche D ON DD.id_decodifica = D.id
																WHERE id_mandante = "' . db_input($_POST['mandante']) . '"
																AND D.tipo = "nature del credito"
																AND (codice_uno = "' . db_input($dati[$occorrenze[$k]]) . '"
																OR codice_due = "' . db_input($dati[$occorrenze[$k]]) . '")');
                            $query_seleziona_tipo_pagamento->execute();

                            $result = $query_seleziona_tipo_pagamento->get_result();
                            $tipo_pagamento = $result->fetch_assoc();
                            $query_seleziona_tipo_pagamento->close();

                            $titolo[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $tipo_pagamento['id_remida'];
                            //$query_titolo[$k] .= 'natura_credito = "'.($dati[$occorrenze[$k]]).'", ';
                            //$dett_titolo[$k]['natura_credito'] = ($dati[$occorrenze[$k]]);

                            // FORZO LA NATURA DEL CREDITO A QUELLA FISSA IMPOSTATA
                            $query_titolo[$k] .= 'id_natura_credito = "' . db_input($tipo_pagamento['id_remida']) . '", ';
                            $dett_titolo[$k]['id_natura_credito'] = $tipo_pagamento['id_remida'];

                            $naturaCredito = $tipo_pagamento['id_remida'];                        
                        }
                    }
                }
            }

            if ($naturaCredito > 0) {
                $occorrenze = array_keys($campi, 'titolo*-*importo_iniziale');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $titolo[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_titolo[$k] .= 'importo_iniziale = "' . converti_importo($risultante) . '", ';
                    $dett_titolo[$k]['importo_iniziale'] = converti_importo($risultante);
                }
                $occorrenze = array_keys($campi, 'titolo*-*acconto');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $titolo[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_titolo[$k] .= 'acconto = "' . converti_importo($risultante) . '", ';
                    $dett_titolo[$k]['acconto'] = converti_importo($risultante);
                }
                $occorrenze = array_keys($campi, 'titolo*-*capitale');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $titolo[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_titolo[$k] .= 'capitale = "' . converti_importo($risultante) . '", ';
                    $dett_titolo[$k]['capitale'] = converti_importo($risultante);
                }
                $occorrenze = array_keys($campi, 'titolo*-*spese');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $titolo[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_titolo[$k] .= 'spese = "' . converti_importo($risultante) . '", ';
                    $dett_titolo[$k]['spese'] = converti_importo($risultante);
                }
                $occorrenze = array_keys($campi, 'titolo*-*interessi');
                if (count($occorrenze) > 0) {
                    for ($k = 0; $k < count($occorrenze); $k++) {
                        $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                        $titolo[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                        $query_titolo[$k] .= 'interessi = "' . converti_importo($risultante) . '", ';
                        $dett_titolo[$k]['interessi'] = converti_importo($risultante);
                    }
                } else {
                    if ($debug) echo '<br><strong>INIZIO CALCOLO INTERESSI</strong><br>';

                    $capitale = $dett_titolo[0]['capitale'];


                    // • SE LA SELECT È [SEMPRE]: DEVE ESEGUIRE IL CALCOLO DEGLI INTERESSI AL MOMENTO DEL CARICAMENTO DI UNA
                    //   NUOVA PRATICA (SIA MANUALE CHE DA IMPORTAZIONE DATI) ED AGGIORNARE AUTOMATICAMENTE IL DATO
                    //   QUOTIDIANAMENTE.
                    // • SE LA SELECT È [A RICHIESTA]: DEVE ESEGUIRE IL CALCOLO DEGLI INTERESSI AL MOMENTO DEL CARICAMENTO DI UNA
                    //   NUOVA PRATICA (SIA MANUALE CHE DA IMPORTAZIONE DATI) MA NON AGGIORNARE DINAMICAMENTE IL DATO.
                    //   SARÀ L’UTENTE A STABILIRE SE E QUANDO AGGIORNARE LA QUOTA [AFFIDATO INTERESSI] DIRETTAMENTE DALLA RIGA
                    //   PRESENTE NELLA SEZIONE [INSOLUTI] DELLA [PRATICA].

                    $query_interessi_contratto = 'SELECT T.tasso, T.da_data
															FROM ed_pratiche P LEFT JOIN contratto C ON P.id_contratto = C.id
																			LEFT JOIN tasso_interesse T ON T.id_interesse = C.tasso_interesse_new
															WHERE P.id = "' . $id_ed_pratica . '"
																AND (C.calcola_new LIKE "%SEMPRE%" OR C.calcola_new LIKE "%RICHIESTA%")
																ORDER BY da_data ASC';
                                            
                    $mysqli->autocommit(FALSE);                    
                    try {
                        $select_interessi_contratto = $mysqli->prepare($query_interessi_contratto);
                        $select_interessi_contratto->execute();

                        $mysqli->commit();

                    }catch(mysqli_sql_exception $e){
                        $mysqli->rollback();                                
                    }
                    $mysqli->autocommit(TRUE);
                    
                    $ris_interessi_contratto = $select_interessi_contratto->get_result();

                    if ($ris_interessi_contratto->num_rows > 0) {
                        $dataScadenza = $dett_titolo[0]['data_scadenza'];
                        $dataAffidamento = $dett_titolo[0]['data_affidamento'] > '0' ? $dett_titolo[0]['data_affidamento'] : date('Y-m-d');
                        $interessi_calcolati = 0;
                        $array_tassi = array();

                        while ($tassi_interesse = $ris_interessi_contratto->fetch_array()){
                            $array_tassi[] = $tassi_interesse;
                        }       

                        //if($debug) echo 'ARRAY TASSI:<br>';
                        //if($debug) print_r($array_tassi);
                        //if($debug) echo '<br><br>';

                        if ($debug) echo 'DATA SCADENZA: ' . $dataScadenza . '<br>DATA AFFIDAMENTO: ' . $dataAffidamento . '<br>';

                        if (count($array_tassi) > 1) {
                            for ($g = 0; $g < count($array_tassi) - 1; $g++) {
                                if ($dataScadenza > date('Y-m-d') || $capitale < 0)
                                    continue;

                                if ($dataScadenza >= $array_tassi[$g + 1]['da_data'] && $g != count($array_tassi) - 2)
                                    continue;

                                if ($dataScadenza >= $array_tassi[$g]['da_data']) {
                                    //if($debug) echo 'SCADENZA MAGGIORE - SET DATA MIN = DATA SCADENZA<br>'.$dataScadenza.'<br>'.$array_tassi[$g]['da_data'].'<br>';
                                    $dataLimiteMin = $dataScadenza;
                                } else if ($dataScadenza < $array_tassi[$g]['da_data']) {
                                    //if($debug) echo 'SCADENZA MINORE - SET DATA MIN = DATA INIZIO TASSO<br>'.$dataScadenza.'<br>'.$array_tassi[$g]['da_data'].'<br>';
                                    $dataLimiteMin = $array_tassi[$g]['da_data'];
                                }

                                if ($dataAffidamento >= $array_tassi[$g + 1]['da_data'])
                                    $dataLimiteMax = $array_tassi[$g + 1]['da_data'];
                                else if ($dataAffidamento < $array_tassi[$g + 1]['da_data'] && $dataAffidamento >= $array_tassi[$g]['da_data'])
                                    $dataLimiteMax = $dataAffidamento;

                                if ($debug) echo 'DATA LIMITE MIN: ' . $dataLimiteMin . '<br>' . 'DATA LIMITE MAX: ' . $dataLimiteMax . '<br>';

                                $interval = strtotime($dataLimiteMax) - strtotime($dataLimiteMin);
                                $giorni = $interval / (60 * 60 * 24);

                                if ($debug) echo 'CAPITALE:<br>' . $capitale . '<br>GIORNI:<br>' . $giorni . '<br>TASSO:<br>' . $array_tassi[$g]['tasso'] . '<br>';

                                if ($giorni > 0)
                                    $interessi_calcolati += $capitale * $giorni * $array_tassi[$g]['tasso'] / 36500;

                                if ($g == count($array_tassi) - 2) {
                                    if ($dataScadenza >= $array_tassi[$g + 1]['da_data']) {
                                        //if($debug) echo 'SCADENZA MAGGIORE - SET DATA MIN = DATA SCADENZA<br>'.$dataScadenza.'<br>'.$array_tassi[$g]['da_data'].'<br>';
                                        $dataLimiteMin = $dataScadenza;
                                    } else if ($dataScadenza < $array_tassi[$g + 1]['da_data']) {
                                        //if($debug) echo 'SCADENZA MINORE - SET DATA MIN = DATA INIZIO TASSO<br>'.$dataScadenza.'<br>'.$array_tassi[$g]['da_data'].'<br>';
                                        $dataLimiteMin = $array_tassi[$g + 1]['da_data'];
                                    }

                                    if ($dataAffidamento >= $array_tassi[$g + 1]['da_data'])
                                        $dataLimiteMax = $dataAffidamento;
                                    else
                                        continue;

                                    if ($debug) echo 'DATA LIMITE MIN: ' . $dataLimiteMin . '<br>' . 'DATA LIMITE MAX: ' . $dataLimiteMax . '<br>';

                                    $interval = strtotime($dataLimiteMax) - strtotime($dataLimiteMin);
                                    $giorni = $interval / (60 * 60 * 24);

                                    if ($debug) echo 'CAPITALE:<br>' . $capitale . '<br>GIORNI:<br>' . $giorni . '<br>TASSO:<br>' . $array_tassi[$g]['tasso'] . '<br>';

                                    if ($giorni > 0)
                                        $interessi_calcolati += $capitale * $giorni * $array_tassi[$g + 1]['tasso'] / 36500;
                                }
                            }
                        } else {
                            if ($dataScadenza > date('Y-m-d') || $capitale < 0) {
                                $interessi_calcolati = 0;
                            } else {
                                $dataLimiteMin = $dataScadenza;
                                $dataLimiteMax = $dataAffidamento;

                                if ($debug) echo 'DATA LIMITE MIN: ' . $dataLimiteMin . '<br>' . 'DATA LIMITE MAX: ' . $dataLimiteMax . '<br>';

                                $interval = strtotime($dataLimiteMax) - strtotime($dataLimiteMin);
                                $giorni = $interval / (60 * 60 * 24);

                                if ($debug) echo 'CAPITALE:<br>' . $capitale . '<br>GIORNI:<br>' . $giorni . '<br>TASSO:<br>' . $array_tassi[0]['tasso'] . '<br>';

                                if ($giorni > 0)
                                    $interessi_calcolati += $capitale * $giorni * $array_tassi[0]['tasso'] / 36500;
                            }
                        }

                        $query_titolo[0] .= 'interessi = "' . $interessi_calcolati . '", ';
                        $dett_titolo[0]['interessi'] = $interessi_calcolati;
                    } else {
                        $query_titolo[0] .= 'interessi = "0.00", ';
                        $dett_titolo[0]['interessi'] = 0;
                    }

                    if ($debug) echo 'DETTAGLIO TITOLO';
                    if ($debug) echo '<br>';
                    if ($debug) print_r($dett_titolo);
                    if ($debug) echo '<br>';
                    if ($debug) echo 'FINE CALCOLO INTERESSI';
                    if ($debug) echo '<br>';
                }
                $occorrenze = array_keys($campi, 'titolo*-*affidato_1');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $titolo[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_titolo[$k] .= 'affidato_1 = "' . converti_importo($risultante) . '", ';
                    $dett_titolo[$k]['affidato_1'] = converti_importo($risultante);
                }
                $occorrenze = array_keys($campi, 'titolo*-*affidato_2');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $titolo[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_titolo[$k] .= 'affidato_2 = "' . converti_importo($risultante) . '", ';
                    $dett_titolo[$k]['affidato_2'] = converti_importo($risultante);
                }
                $occorrenze = array_keys($campi, 'titolo*-*affidato_3');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $titolo[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_titolo[$k] .= 'affidato_3 = "' . converti_importo($risultante) . '", ';
                    $dett_titolo[$k]['affidato_3'] = converti_importo($risultante);
                }
                $occorrenze = array_keys($campi, 'titolo*-*oneri_studio');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $titolo[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_titolo[$k] .= 'oneri_studio = "' . converti_importo($risultante) . '", ';
                    $dett_titolo[$k]['oneri_studio'] = converti_importo($risultante);
                }
                $occorrenze = array_keys($campi, 'titolo*-*spese_incasso');
                for ($k = 0; $k < count($occorrenze); $k++) {
                    $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                    $titolo[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                    $query_titolo[$k] .= 'spese_incasso = "' . converti_importo($risultante) . '", ';
                    $dett_titolo[$k]['spese_incasso'] = converti_importo($risultante);
                }
            }
            else {
                $occorrenze = array_keys($campi, 'titolo*-*quota_natura');

                $query_seleziona_quota = $mysqli->prepare('SELECT quota FROM nature_credito WHERE id = "' . db_input($naturaCredito) . '"');
                $query_seleziona_quota->execute();

                $result = $query_seleziona_quota->get_result();
                $selezione = $result->fetch_assoc();
                $query_seleziona_quota->close();

                if ($selezione['quota'] == 1) {
                    $query_titolo[$k] .= 'acconto = "' . converti_importo($dati[$occorrenze[0]]) . '", ';
                    $dett_titolo[$k]['acconto'] = converti_importo($dati[$occorrenze[0]]);
                } else if ($selezione['quota'] == 2) {
                    $query_titolo[$k] .= 'capitale = "' . converti_importo($dati[$occorrenze[0]]) . '", ';
                    $dett_titolo[$k]['capitale'] = converti_importo($dati[$occorrenze[0]]);
                } else if ($selezione['quota'] == 3) {
                    $query_titolo[$k] .= 'spese = "' . converti_importo($dati[$occorrenze[0]]) . '", ';
                    $dett_titolo[$k]['spese'] = converti_importo($dati[$occorrenze[0]]);
                } else if ($selezione['quota'] == 4) {
                    $query_titolo[$k] .= 'interessi = "' . converti_importo($dati[$occorrenze[0]]) . '", ';
                    $dett_titolo[$k]['interessi'] = converti_importo($dati[$occorrenze[0]]);
                } else if ($selezione['quota'] == 5) {
                    $query_titolo[$k] .= 'affidato_1 = "' . converti_importo($dati[$occorrenze[0]]) . '", ';
                    $dett_titolo[$k]['affidato_1'] = converti_importo($dati[$occorrenze[0]]);
                } else if ($selezione['quota'] == 6) {
                    $query_titolo[$k] .= 'affidato_2 = "' . converti_importo($dati[$occorrenze[0]]) . '", ';
                    $dett_titolo[$k]['affidato_2'] = converti_importo($dati[$occorrenze[0]]);
                } else if ($selezione['quota'] == 7) {
                    $query_titolo[$k] .= 'affidato_3 = "' . converti_importo($dati[$occorrenze[0]]) . '", ';
                    $dett_titolo[$k]['affidato_3'] = converti_importo($dati[$occorrenze[0]]);
                } else if ($selezione['quota'] == 8) {
                    $query_titolo[$k] .= 'oneri_studio = "' . converti_importo($dati[$occorrenze[0]]) . '", ';
                    $dett_titolo[$k]['oneri_studio'] = converti_importo($dati[$occorrenze[0]]);
                } else if ($selezione['quota'] == 9) {
                    $query_titolo[$k] .= 'spese_incasso = "' . converti_importo($dati[$occorrenze[0]]) . '", ';
                    $dett_titolo[$k]['spese_incasso'] = converti_importo($dati[$occorrenze[0]]);
                }
            }

            $occorrenze = array_keys($campi, 'titolo*-*cp_valuta');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $dett_titolo_campi_personalizzati[$k]['cp_valuta'] = converti_importo($risultante);
            }
            $occorrenze = array_keys($campi, 'titolo*-*cp_testo');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $dett_titolo_campi_personalizzati[$k]['cp_testo'] = $risultante;
            }
            $occorrenze = array_keys($campi, 'titolo*-*cp_data');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $dett_titolo_campi_personalizzati[$k]['cp_data'] = converti_data_acquisita($risultante);
            }
            $occorrenze = array_keys($campi, 'titolo*-*recapito_insoluto');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $titolo[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                $query_titolo[$k] .= 'recapito_insoluto = "' . $risultante . '", ';
                $dett_titolo[$k]['recapito_insoluto'] = $risultante;
            }
            $occorrenze = array_keys($campi, 'titolo*-*note');
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);

                $titolo[$posizioni[$occorrenze[$k]]][$occorrenze[$k]] = $risultante;
                $query_titolo[$k] .= 'note = "' . $risultante . '", ';
                $dett_titolo[$k]['note'] = $risultante;
            }
        }    //echo 'TITOLO: '; print_r($titolo); echo '<br><br>';

        //if($titolo != $titolo1) {
        if ($id_pratica == '') {
            if (isset($dett_univoco[0]['riferimento'])) {
                $query_selezione_pratica = 'SELECT id_pratica FROM acquisizione_dati WHERE id_pratica > 0 AND riferimento_mandante = "' . $dett_univoco[0]['riferimento'] . '" ORDER BY data_inserimento DESC';
            } else if (isset($dett_univoco[0]['riferimento2'])) {
                $query_selezione_pratica = 'SELECT id_pratica FROM acquisizione_dati WHERE id_pratica > 0 AND riferimento_mandante = "' . $dett_univoco[0]['riferimento2'] . '" ORDER BY data_inserimento DESC';
            }

            $select_seleziona_pratica = $mysqli->prepare($query_selezione_pratica);
            $select_seleziona_pratica->execute();
            $result = $select_seleziona_pratica->get_result();
            $row_selezione_pratica = $result->fetch_assoc();
            $id_pratica = $row_selezione_pratica['id_pratica'];
            $select_seleziona_pratica->close();
        }

        $array_rollback_acquisizione[] = 'DELETE FROM pratiche_insoluto WHERE id_pratica = "' . db_input($id_pratica) . '" AND id_pratica IN (SELECT id FROM pratiche WHERE data_creazione >= "' . date('Y-m-d') . '")';
        $array_rollback_acquisizione[] = 'DELETE FROM calcolo_compenso WHERE codice_pratica = "' . db_input($id_pratica) . '" AND codice_pratica IN (SELECT id FROM pratiche WHERE data_creazione >= "' . date('Y-m-d') . '")';

        // INSERIMENTO TITOLO
        for ($v = 0; $v < count($query_titolo); $v++) {

            $queryVerificaEsistenzaTitolo = $mysqli->prepare("SELECT id FROM pratiche_insoluto WHERE numero='" . $numero_titolo_array[$v] . "' AND id_pratica='" . $id_pratica . "' AND numero <> ''");
            $queryVerificaEsistenzaTitolo->execute();
            $risVerificaEsistenzaTitolo = $queryVerificaEsistenzaTitolo->get_result();
            $verificaEsistenzaTitolo = !$flagRiprendiPratiche || ($flagRiprendiPratiche && ($risVerificaEsistenzaTitolo->num_rows == 0 || $numero_titolo_array[$v]=='')) ? true : false;
            // $verificaEsistenzaTitolo = !$flagRiprendiPratiche || ($flagRiprendiPratiche && (db_num_rows(db_query($queryVerificaEsistenzaTitolo)) == 0 || $numero_titolo_array[$v]=='')) ? true : false;

            // AGGIUNGERE CONTROLLO DATA SCADENZA
            if (isset($dett_titolo[$v]['id_natura_credito']) && $dett_titolo[$v]['id_natura_credito'] > 0 && $verificaEsistenzaTitolo) {
                // if ($debug) echo 'INSERT INTO pratiche_insoluto SET id_pratica = "' . db_input($id_pratica) . '", data_inserimento = "' . date('Y-m-d') . '", ' . rtrim($query_titolo[$v], ', ');

                $mysqli->autocommit(FALSE);
                try{
                    $query_inserimento_pratiche_insoluto = $mysqli->prepare('INSERT INTO e_pratiche_insoluto SET id_acquisizione = '. $id_acquisizione .', id_pratica = "' . $id_ed_pratica . '", data_inserimento = "' . date('Y-m-d') . '", ' . rtrim($query_titolo[$v], ', '));                                                          
                    $query_inserimento_pratiche_insoluto->execute();
                    $e_id_titolo = $mysqli->insert_id;             

                    $mysqli->commit();

                    // echo '<strong>INSERIMENTO TITOLO IN E_PRATICHE_INSOLUTO EFFETTUATO</strong><br>';

                }catch(mysqli_sql_exception $e) {
                    echo 'ERRORE INSERIMENTO TITOLO IN E_PRATICHE_INSOLUTO ' . $e->getMessage() . '<br>';
                    $mysqli->rollback();                            
                }
                $mysqli->autocommit(TRUE);
                $query_inserimento_pratiche_insoluto->close();

                // $id_titolo = db_last_insert_id();
                e_compensoSuInsolutoAzienda($e_id_titolo);

                if (isset($dett_titolo_campi_personalizzati) && count($dett_titolo_campi_personalizzati) > 0) {
                    $campi_valuta = '';
                    $campi_testo = '';
                    $campi_data = '';

                    for ($vv = 0; $vv < count($dett_titolo_campi_personalizzati); $vv++) {
                        $campi_valuta .= $dett_titolo_campi_personalizzati[$vv]['cp_valuta'] . '::';
                        $campi_testo .= $dett_titolo_campi_personalizzati[$vv]['cp_testo'] . '::';
                        $campi_data .= $dett_titolo_campi_personalizzati[$vv]['cp_data'] . '::';
                    }

                    $mysqli->autocommit(FALSE);
                    try{
                        $query_update_pratiche_insoluto = $mysqli->prepare('UPDATE e_pratiche_insoluto SET id_acquisizione = '. $id_acquisizione .', cp_valuta_concat = "' . db_input($campi_valuta) . '", cp_testo_concat = "' . db_input($campi_testo) . '", cp_data_concat = "' . db_input($campi_data) . '" WHERE id = "' . db_input($e_id_titolo) . '"');                                                          
                        $query_update_pratiche_insoluto->execute();                   

                        $mysqli->commit();

                        // echo '<strong>UPDATE CAMPI PERSONALIZZATI IN E_PRATICHE_INSOLUTO EFFETTUATO</strong><br>';

                    }catch(mysqli_sql_exception $e) {
                        echo 'ERRORE UPDATE CAMPI PERSONALIZZATI IN E_PRATICHE_INSOLUTO ' . $e->getMessage() . '<br>';
                        $mysqli->rollback();                            
                    }
                    $mysqli->autocommit(TRUE);
                    $query_update_pratiche_insoluto->close();
                }

                // $dettaglio_titolo = mysql_fetch_array(db_query('SELECT * FROM pratiche_insoluto WHERE id = "' . $id_titolo . '"'));

                // echo '<strong>ID TITOLO: ' . $dettaglio_titolo['id'] . ' - Numero: ' . $dettaglio_titolo['numero'] . ' </strong><br>';
            }
        }

        echo '<strong>AGGIORNA TOTALI PRATICA IN ED_PRATICHE: ' . $id_ed_pratica . ' </strong><br>';
        /*
						aggiornaTotaliPratica($id_pratica, $pratica_creata); // la variabile $pratica_creata contiene tutti i dettagli della pratica
					*/

        {
            // RICORDA: SE SI CAMBIA QUESTO SCRIPT è NECESSARIO CAMBIARE ANCHE LO STESSO SCRIPT PRESENTE IN form_action_A.php NELLA 'action' 'modifica-insoluto-pratica'
            $affidato_statico = pratiche_getAffidatoStatico($id_pratica);
            if ($affidato_statico == 0) {
                # UPGRADE 2016/10/20 - [2.2 AMMINISTRAZIONE – CONTRATTI (sez. GENERALI)]
                // SE [AFFIDATO STATICO] = NO, NEL CASO IN CUI SU UNA [PRATICA] VENGA APPORTATA UNA MODIFICA DELLE QUOTE
                // PRESENTI NELLA SEZIONE [INSOLUTI], EVENTUALI RIGHE DI INSOLUTO GENERATE IN AUTOMATICO DAL GESTIONALE IN
                // FASE DI INSERIMENTO PRATICA (VEDI SEZIONE [PERSONALIZZAZIONE QUOTE] DEL [CONTRATTO]) VERRANNO RICALCOLATE

                // PASSI:
                // 1. RICALCOLIAMO (E SALVIAMO) I TOTALI AFFIDATI DELLA PRATICA SENZA CONSIDERARE GLI INSOLUTI CHE HANNO IL CAMPO [flag_personalizzazione_quote] = 1
                // 2. RIMUOVIAMO (PER LA PRATICA SEGUENTE) GLI INSOLUTI CHE HANNO [flag_personalizzazione_quote] = 1
                // 3. INSERIAMO NUOVAMENTE LA RIGA\E DI INSOLUTO IN BASE ALLE QUOTE PERSONALIZZATE


                // QUOTE AFFIDATO (AGGIORNAMENTO IN BASE ALLA SCADENZA)
                $condition = 'id_pratica = "' . db_input($id_ed_pratica) . '" AND data_scadenza <= "' . db_input(date("Y-m-d")) . '" AND flag_personalizzazione_quote = 0';
                $array_quote = e_praticheInsoluto_getSumSingoleQuote($condition);

                $mysqli->autocommit(FALSE);
                try{
                    $query_inserimento_insoluto = $mysqli->prepare('UPDATE ed_pratiche
														   SET affidato_capitale = "' . db_input($array_quote['affidato_capitale']) . '",
															 affidato_spese = "' . db_input($array_quote['affidato_spese']) . '",
															 affidato_interessi = "' . db_input($array_quote['affidato_interessi']) . '",
															 affidato_1 = "' . db_input($array_quote['affidato_1']) . '",
															 affidato_2 = "' . db_input($array_quote['affidato_2']) . '",
															 affidato_3 = "' . db_input($array_quote['affidato_3']) . '",
															 competenze_oneri_recupero = "' . db_input($array_quote['competenze_oneri_recupero']) . '",
															 competenze_spese_incasso = "' . db_input($array_quote['competenze_spese_incasso']) . '"
															 WHERE id = "' . $id_ed_pratica . '"');                                                          
                    $query_inserimento_insoluto->execute();                   

                    $mysqli->commit();

                    // echo '<strong>UPDATE QUOTE AFFIDATO IN ED_PRATICHE EFFETTUATO</strong><br>';

                }catch(mysqli_sql_exception $e) {
                    echo 'ERRORE UPDATE QUOTE AFFIDATO IN ED_PRATICHE ' . $e->getMessage() . '<br>';
                    $mysqli->rollback();                            
                }
                $mysqli->autocommit(TRUE);
                $query_inserimento_insoluto->close();

                // QUOTE AFFIDATO A SCADERE (AGGIORNAMENTO IN BASE ALLA SCADENZA)
                $condition = 'id_pratica = "' . db_input($id_ed_pratica) . '" AND data_scadenza > "' . db_input(date("Y-m-d")) . '" AND flag_personalizzazione_quote = 0';
                $array_quote_a_scadere = e_praticheInsoluto_getSumSingoleQuote($condition);

                $mysqli->autocommit(FALSE);
                try{
                    $query_inserimento_insoluto = $mysqli->prepare('UPDATE ed_pratiche
														   SET affidato_capitale_a_scadere = "' . db_input($array_quote_a_scadere['affidato_capitale']) . '",
															 affidato_spese_a_scadere = "' . db_input($array_quote_a_scadere['affidato_spese']) . '",
															 affidato_interessi_a_scadere = "' . db_input($array_quote_a_scadere['affidato_interessi']) . '",
															 affidato_1_a_scadere = "' . db_input($array_quote_a_scadere['affidato_1']) . '",
															 affidato_2_a_scadere = "' . db_input($array_quote_a_scadere['affidato_2']) . '",
															 affidato_3_a_scadere = "' . db_input($array_quote_a_scadere['affidato_3']) . '",
															 competenze_oneri_recupero_a_scadere = "' . db_input($array_quote_a_scadere['competenze_oneri_recupero']) . '",
															 competenze_spesse_incasso_a_scadere = "' . db_input($array_quote_a_scadere['competenze_spese_incasso']) . '"
															 WHERE id = "' . $id_ed_pratica . '"');                                                          
                    $query_inserimento_insoluto->execute();                   

                    $mysqli->commit();

                    // echo '<strong>UPDATE QUOTE AFFIDATO A SCADERE IN ED_PRATICHE EFFETTUATO</strong><br>';

                }catch(mysqli_sql_exception $e) {
                    echo 'ERRORE UPDATE QUOTE AFFIDATO A SCADERE IN ED_PRATICHE ' . $e->getMessage() . '<br>';
                    $mysqli->rollback();                            
                }
                $mysqli->autocommit(TRUE);
                $query_inserimento_insoluto->close();

                //GGN GETIONE NUOVA QUOTE FRAZIONATE IN AGGIUNTA ALLA PRECEDENTE
                if (count($array_quote) == 0 && count($array_quote_a_scadere) == 0) {
                    // QUOTE AFFIDATO (AGGIORNAMENTO IN BASE ALLA SCADENZA)
                    $condition = 'id_pratica = "' . db_input($id_ed_pratica) . '" AND data_scadenza <= "' . db_input(date("Y-m-d")) . '" AND flag_personalizzazione_quote = 0';
                    $array_quote = e_praticheInsoluto_getSumSingoleFrazionateQuote($condition);


                    $mysqli->autocommit(FALSE);
                    try{
                        $query_inserimento_insoluto = $mysqli->prepare('UPDATE ed_pratiche
                                                            SET affidato_capitale = "' . db_input($array_quote['affidato_capitale']) . '",
                                                                affidato_spese = "' . db_input($array_quote['affidato_spese']) . '",
                                                                affidato_interessi = "' . db_input($array_quote['affidato_interessi']) . '",
                                                                affidato_1 = "' . db_input($array_quote['affidato_1']) . '",
                                                                affidato_2 = "' . db_input($array_quote['affidato_2']) . '",
                                                                affidato_3 = "' . db_input($array_quote['affidato_3']) . '",
                                                                competenze_oneri_recupero = "' . db_input($array_quote['competenze_oneri_recupero']) . '",
                                                                competenze_spese_incasso = "' . db_input($array_quote['competenze_spese_incasso']) . '"
                                                                WHERE id = "' . $id_ed_pratica . '"');                                                          
                        $query_inserimento_insoluto->execute();                  

                        $mysqli->commit();

                        // echo '<strong>UPDATE QUOTE AFFIDATO IN ED_PRATICHE EFFETTUATO</strong><br>';

                    }catch(mysqli_sql_exception $e) {
                        echo 'ERRORE UPDATE QUOTE AFFIDATO IN ED_PRATICHE ' . $e->getMessage() . '<br>';
                        $mysqli->rollback();                            
                    }
                    $mysqli->autocommit(TRUE);
                    $query_inserimento_insoluto->close();

                    // QUOTE AFFIDATO A SCADERE (AGGIORNAMENTO IN BASE ALLA SCADENZA)
                    $condition = 'id_pratica = "' . db_input($id_ed_pratica) . '" AND data_scadenza > "' . db_input(date("Y-m-d")) . '" AND flag_personalizzazione_quote = 0';
                    $array_quote_a_scadere = e_praticheInsoluto_getSumSingoleFrazionateQuote($condition);

                    $mysqli->autocommit(FALSE);
                    try{
                        $query_inserimento_insoluto = $mysqli->prepare('UPDATE ed_pratiche
                                                            SET affidato_capitale_a_scadere = "' . db_input($array_quote_a_scadere['affidato_capitale']) . '",
                                                                affidato_spese_a_scadere = "' . db_input($array_quote_a_scadere['affidato_spese']) . '",
                                                                affidato_interessi_a_scadere = "' . db_input($array_quote_a_scadere['affidato_interessi']) . '",
                                                                affidato_1_a_scadere = "' . db_input($array_quote_a_scadere['affidato_1']) . '",
                                                                affidato_2_a_scadere = "' . db_input($array_quote_a_scadere['affidato_2']) . '",
                                                                affidato_3_a_scadere = "' . db_input($array_quote_a_scadere['affidato_3']) . '",
                                                                competenze_oneri_recupero_a_scadere = "' . db_input($array_quote_a_scadere['competenze_oneri_recupero']) . '",
                                                                competenze_spesse_incasso_a_scadere = "' . db_input($array_quote_a_scadere['competenze_spese_incasso']) . '"
                                                                WHERE id = "' . $id_ed_pratica . '"');                                                          
                        $query_inserimento_insoluto->execute();                   

                        $mysqli->commit();

                        // echo '<strong>UPDATE QUOTE AFFIDATO A SCADERE IN ED_PRATICHE EFFETTUATO</strong><br>';

                    }catch(mysqli_sql_exception $e) {
                        echo 'ERRORE UPDATE QUOTE AFFIDATO A SCADERE IN ED_PRATICHE ' . $e->getMessage() . '<br>';
                        $mysqli->rollback();                            
                    }
                    $mysqli->autocommit(TRUE);
                    $query_inserimento_insoluto->close();
                }
            }
            
            // PUNTO 2: RIMUOVIAMO GLI INSOLUTI CON IL FLAG_PERSONALIZZAZIONE_QUOTE = 1 PER POI RICREARLI DI NUOVI AGGIORNATI

            $query_delete_insoluti = $mysqli->prepare('DELETE 
                                            FROM e_pratiche_insoluto
                                            WHERE id_pratica = "' . db_input($id_ed_pratica) . '" AND flag_personalizzazione_quote = 1');
            $query_delete_insoluti->execute();                   
            $query_delete_insoluti->close();

            // PUNTO 3: PROCEDIAMO AD INSERIRE NUOVAMENTE LA RIGA\E DI INSOLUTO IN BASE ALLE QUOTE PERSONALIZZATE
            e_praticheInsoluto_insertInsolutoFromQuotePersonalizzate($id_ed_pratica);
            //GGN GETIONE NUOVA QUOTE FRAZIONATE IN AGGIUNTA ALLA PRECEDENTE

            e_praticheInsoluto_insertInsolutoFromFrazionateQuotePersonalizzate($id_ed_pratica);


            # INIZIO RICALCOLO AFFIDATO - [2.2 AMMINISTRAZIONE – CONTRATTI (sez. GENERALI)]

            // QUOTE AFFIDATO (AGGIORNAMENTO IN BASE ALLA SCADENZA)
            $condition = 'id_pratica = "' . db_input($id_ed_pratica) . '" AND data_scadenza <= "' . db_input(date("Y-m-d")) . '"';
            $array_quote = e_praticheInsoluto_getSumSingoleQuote($condition);

            $mysqli->autocommit(FALSE);
            try{
                $query_inserimento_insoluto = $mysqli->prepare('UPDATE ed_pratiche
                                                    SET affidato_capitale = "' . db_input($array_quote['affidato_capitale']) . '",
                                                        affidato_spese = "' . db_input($array_quote['affidato_spese']) . '",
                                                        affidato_interessi = "' . db_input($array_quote['affidato_interessi']) . '",
                                                        affidato_1 = "' . db_input($array_quote['affidato_1']) . '",
                                                        affidato_2 = "' . db_input($array_quote['affidato_2']) . '",
                                                        affidato_3 = "' . db_input($array_quote['affidato_3']) . '",
                                                        competenze_oneri_recupero = "' . db_input($array_quote['competenze_oneri_recupero']) . '",
                                                        competenze_spese_incasso = "' . db_input($array_quote['competenze_spese_incasso']) . '"
                                                        WHERE id = "' . $id_ed_pratica . '"');                                                          
                $query_inserimento_insoluto->execute();                  

                $mysqli->commit();

                // echo '<strong>UPDATE QUOTE AFFIDATO IN ED_PRATICHE EFFETTUATO</strong><br>';

            }catch(mysqli_sql_exception $e) {
                echo 'ERRORE UPDATE QUOTE AFFIDATO IN ED_PRATICHE ' . $e->getMessage() . '<br>';
                $mysqli->rollback();                            
            }
            $mysqli->autocommit(TRUE);
            $query_inserimento_insoluto->close();

            // QUOTE AFFIDATO A SCADERE (AGGIORNAMENTO IN BASE ALLA SCADENZA)
            $condition = 'id_pratica = "' . db_input($id_ed_pratica) . '" AND data_scadenza > "' . db_input(date("Y-m-d")) . '"';
            $array_quote_a_scadere = e_praticheInsoluto_getSumSingoleQuote($condition);

            $mysqli->autocommit(FALSE);
            try{
                $query_inserimento_insoluto = $mysqli->prepare('UPDATE ed_pratiche
                                                    SET affidato_capitale_a_scadere = "' . db_input($array_quote_a_scadere['affidato_capitale']) . '",
                                                        affidato_spese_a_scadere = "' . db_input($array_quote_a_scadere['affidato_spese']) . '",
                                                        affidato_interessi_a_scadere = "' . db_input($array_quote_a_scadere['affidato_interessi']) . '",
                                                        affidato_1_a_scadere = "' . db_input($array_quote_a_scadere['affidato_1']) . '",
                                                        affidato_2_a_scadere = "' . db_input($array_quote_a_scadere['affidato_2']) . '",
                                                        affidato_3_a_scadere = "' . db_input($array_quote_a_scadere['affidato_3']) . '",
                                                        competenze_oneri_recupero_a_scadere = "' . db_input($array_quote_a_scadere['competenze_oneri_recupero']) . '",
                                                        competenze_spesse_incasso_a_scadere = "' . db_input($array_quote_a_scadere['competenze_spese_incasso']) . '"
                                                        WHERE id = "' . $id_ed_pratica . '"');                                                          
                $query_inserimento_insoluto->execute();                   

                $mysqli->commit();

                // echo '<strong>UPDATE QUOTE AFFIDATO A SCADERE IN ED_PRATICHE EFFETTUATO</strong><br>';

            }catch(mysqli_sql_exception $e) {
                echo 'ERRORE UPDATE QUOTE AFFIDATO A SCADERE IN ED_PRATICHE ' . $e->getMessage() . '<br>';
                $mysqli->rollback();                            
            }
            $mysqli->autocommit(TRUE);
            $query_inserimento_insoluto->close();

            # FINE RICALCOLO AFFIDATO

            //GGN GESTIONE NUOVA QUOTE FRAZIONATE IN AGGIUNTA ALLA PRECEDENTE
            if (count($array_quote) == 0 && count($array_quote_a_scadere) == 0) {

                # INIZIO RICALCOLO AFFIDATO - [2.2 AMMINISTRAZIONE – CONTRATTI (sez. GENERALI)]

                // QUOTE AFFIDATO (AGGIORNAMENTO IN BASE ALLA SCADENZA)
                $condition = 'id_pratica = "' . db_input($id_ed_pratica) . '" AND data_scadenza <= "' . db_input(date("Y-m-d")) . '"';
                $array_quote = e_praticheInsoluto_getSumSingoleFrazionateQuote($condition);

                $mysqli->autocommit(FALSE);
                try{
                    $query_inserimento_insoluto = $mysqli->prepare('UPDATE ed_pratiche
                                                        SET affidato_capitale = "' . db_input($array_quote['affidato_capitale']) . '",
                                                            affidato_spese = "' . db_input($array_quote['affidato_spese']) . '",
                                                            affidato_interessi = "' . db_input($array_quote['affidato_interessi']) . '",
                                                            affidato_1 = "' . db_input($array_quote['affidato_1']) . '",
                                                            affidato_2 = "' . db_input($array_quote['affidato_2']) . '",
                                                            affidato_3 = "' . db_input($array_quote['affidato_3']) . '",
                                                            competenze_oneri_recupero = "' . db_input($array_quote['competenze_oneri_recupero']) . '",
                                                            competenze_spese_incasso = "' . db_input($array_quote['competenze_spese_incasso']) . '"
                                                            WHERE id = "' . $id_ed_pratica . '"');                                                          
                    $query_inserimento_insoluto->execute();                  

                    $mysqli->commit();

                    // echo '<strong>UPDATE QUOTE AFFIDATO IN ED_PRATICHE EFFETTUATO</strong><br>';

                }catch(mysqli_sql_exception $e) {
                    echo 'ERRORE UPDATE QUOTE AFFIDATO IN ED_PRATICHE ' . $e->getMessage() . '<br>';
                    $mysqli->rollback();                            
                }
                $mysqli->autocommit(TRUE);
                $query_inserimento_insoluto->close();

                // QUOTE AFFIDATO A SCADERE (AGGIORNAMENTO IN BASE ALLA SCADENZA)
                $condition = 'id_pratica = "' . db_input($id_ed_pratica) . '" AND data_scadenza > "' . db_input(date("Y-m-d")) . '"';
                $array_quote_a_scadere = e_praticheInsoluto_getSumSingoleFrazionateQuote($condition);

                $mysqli->autocommit(FALSE);
                try{
                    $query_inserimento_insoluto = $mysqli->prepare('UPDATE ed_pratiche
                                                        SET affidato_capitale_a_scadere = "' . db_input($array_quote_a_scadere['affidato_capitale']) . '",
                                                            affidato_spese_a_scadere = "' . db_input($array_quote_a_scadere['affidato_spese']) . '",
                                                            affidato_interessi_a_scadere = "' . db_input($array_quote_a_scadere['affidato_interessi']) . '",
                                                            affidato_1_a_scadere = "' . db_input($array_quote_a_scadere['affidato_1']) . '",
                                                            affidato_2_a_scadere = "' . db_input($array_quote_a_scadere['affidato_2']) . '",
                                                            affidato_3_a_scadere = "' . db_input($array_quote_a_scadere['affidato_3']) . '",
                                                            competenze_oneri_recupero_a_scadere = "' . db_input($array_quote_a_scadere['competenze_oneri_recupero']) . '",
                                                            competenze_spesse_incasso_a_scadere = "' . db_input($array_quote_a_scadere['competenze_spese_incasso']) . '"
                                                            WHERE id = "' . $id_ed_pratica . '"');                                                          
                    $query_inserimento_insoluto->execute();                   

                    $mysqli->commit();

                    // echo '<strong>UPDATE QUOTE AFFIDATO A SCADERE IN ED_PRATICHE EFFETTUATO</strong><br>';

                }catch(mysqli_sql_exception $e) {
                    echo 'ERRORE UPDATE QUOTE AFFIDATO A SCADERE IN ED_PRATICHE ' . $e->getMessage() . '<br>';
                    $mysqli->rollback();                            
                }
                $mysqli->autocommit(TRUE);
                $query_inserimento_insoluto->close();

                valutaObiettiviPraticaContratto($id_pratica);

                # FINE RICALCOLO AFFIDATO
            }
        }

        # AGGIORNA ITER DI LAVORO IN BASE AI NUOVI IMPORTI CALCOLATI
        {
            # VERIFICA DELL'ESISTENZA DI COPERTURA ESATTORIALE (necessaria per attribuire il corretto iter)
            $query_copertura_esattoriale = "";

            $copertura_esattoriale = false;

            {
                $query_copertura_esattoriale = $mysqli->prepare("SELECT DISTINCT U.*
																FROM (
																	SELECT PC.id, PC.id_utente, 1 AS PHC, (CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) AS max_num_prat, COUNT(*) AS numero_pratiche
																	FROM phone_collector PC 
																		INNER JOIN utente U on PC.id_utente = U.id_utente
																		LEFT JOIN pratiche P on PC.id_utente = P.id_collector
																	WHERE U.attivo = 1 OR U.attivo IS NULL
																	GROUP BY PC.id_utente, PC.max_num_prat
																	HAVING ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) = 0) OR ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) > COUNT(*))

																	UNION

																	SELECT E.id, E.id_utente, 0 AS PHC, (CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) AS max_num_prat, COUNT(*) AS numero_pratiche
																	FROM esattore E 
																		INNER JOIN utente U on E.id_utente = U.id_utente
																		LEFT JOIN pratiche P on E.id_utente = P.id_collector
																	WHERE U.attivo = 1 OR U.attivo IS NULL
																	GROUP BY E.id_utente, E.max_num_prat
																	HAVING ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) = 0) OR ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) > COUNT(*))
																) AS U  

																LEFT JOIN zona_geografica_competenza UZ ON U.id_utente = UZ.id_utente  
																LEFT JOIN prodotti_lavorabili UP ON U.id_utente = UP.id_utente 
																LEFT JOIN credito C ON UP.id_prodotto = C.codice 
																LEFT JOIN carico_collector CC ON CC.id_collector = U.id_utente
																LEFT JOIN prodotti_lavorabili PL ON PL.id_prodotto = CC.codice

																WHERE (U.PHC = 1 
																		OR (U.PHC = 0 
																				AND (
																					(UZ.zona_esatt = 1 AND UZ.tipo_zona = 'Nazione' AND UZ.da in (SELECT PR.nazione FROM pratiche P INNER JOIN recapito PR ON P.id_debitore = PR.id_utente WHERE P.id = '" . db_input($id_pratica) . "' AND PR.predefinito = 1))
																				OR  (UZ.zona_esatt = 1 AND UZ.tipo_zona = 'Regione' AND UZ.da in (SELECT PP.cod_regione FROM pratiche P INNER JOIN recapito PR ON P.id_debitore = PR.id_utente INNER JOIN province PP ON PR.provincia = PP.cod_provincia WHERE P.id = '" . db_input($id_pratica) . "' AND PR.predefinito = 1))
																				OR  (UZ.zona_esatt = 1 AND UZ.tipo_zona = 'Provincia' AND UZ.da in (SELECT PR.provincia FROM pratiche P INNER JOIN recapito PR ON P.id_debitore = PR.id_utente WHERE P.id = '" . db_input($id_pratica) . "' AND PR.predefinito = 1))
																				OR  (UZ.zona_esatt = 1 AND UZ.tipo_zona = 'Cap' AND UZ.da >= (SELECT MIN(PR.cap) FROM pratiche P INNER JOIN recapito PR ON P.id_debitore = PR.id_utente WHERE P.id = '" . db_input($id_pratica) . "' AND PR.predefinito = 1) AND UZ.a <= (SELECT MAX(PR.cap) FROM pratiche P INNER JOIN recapito PR ON P.id_debitore = PR.id_utente WHERE P.id = '" . db_input($id_pratica) . "' AND PR.predefinito = 1))
																				OR  (UZ.zona_esatt = 1 AND UZ.tipo_zona = 'Citta' AND UZ.da in (SELECT PC.cod_istat FROM pratiche P INNER JOIN recapito PR ON P.id_debitore = PR.id_utente INNER JOIN comuni PC ON PR.citta = PC.comune AND PR.provincia=PC.cod_provincia WHERE P.id = '" . db_input($id_pratica) . "' AND PR.predefinito = 1))
																				)
																			)   
																		)
																AND ( C.id IS NULL OR C.id IN (SELECT id_tipo_credito FROM contratto WHERE id = '" . db_input($id_contratto) . "'))
																AND U.id_utente NOT IN (SELECT (CASE WHEN value_id IS NULL THEN 0 ELSE value_id END) AS value_id FROM pratiche Pbis LEFT JOIN view_agenti_esclusi Cbis ON Cbis.id = Pbis.id_contratto WHERE Pbis.id = '" . db_input($id_pratica) . "')
																AND U.PHC = 0");

                // SE DOVESSIMO INCLUDERE I PHC RIMUOVERE DALLA WHERE "AND PHC = 0"

            }

            $query_copertura_esattoriale->execute();
            $result = $query_copertura_esattoriale->get_result();
            $ris_copertura_esattoriale = $result->fetch_assoc();

            $risTrovati = count($ris_copertura_esattoriale);

            if ($debug) echo '<br><br>' . $query_copertura_esattoriale . '<br><br>';
            if ($debug) echo 'RISULTATI TROVATI = ' . $risTrovati . '<br>';

            if ($risTrovati > 0) {
                $copertura_esattoriale = true;
            }

            $id_iter = 0;
            # Recupero i dettagli del contratto per verificare la condizione "ITER LAVORO" della scheda 12.1
            if ($copertura_esattoriale)
                $query_recupero_iter_contratto = 'SELECT copertura, soglia_composizione, import_fino, tipo_report FROM contratto_iter_lavoro WHERE id_contratto = "' . db_input($id_contratto) . '" AND (copertura = "si" OR copertura = "non_influenza") ORDER BY import_fino ASC, copertura DESC';
            else
                $query_recupero_iter_contratto = 'SELECT copertura, soglia_composizione, import_fino, tipo_report FROM contratto_iter_lavoro WHERE id_contratto = "' . db_input($id_contratto) . '" AND (copertura = "no" OR copertura = "non_influenza") ORDER BY import_fino ASC, copertura ASC';

            $select_recupero_iter_contratto = $mysqli->prepare($query_recupero_iter_contratto);
            $select_recupero_iter_contratto->execute();
            $result_recupero_iter_contratto = $select_recupero_iter_contratto->get_result();

            if ($result_recupero_iter_contratto->num_rows > 0) {
                while ($iter_contratto = $result_recupero_iter_contratto->fetch_array()) {
                    $ret = calcolatore($iter_contratto['soglia_composizione'], e_pratiche_getSingoleQuote($id_ed_pratica));
                    if ($ret <= $iter_contratto['import_fino']) {
                        $id_iter = $iter_contratto['tipo_report'];
                        break;
                    }
                }
            } else {
                # Recupero i dati dell'iter dalla sezione IMPOSTAZIONI DI BASE
                $query_recupero_iter_base ='SELECT iter_di_lavoro FROM impostazioni_base WHERE id = 1';
                $select_recupero_iter_base = $mysqli->prepare($query_recupero_iter_base);
                $select_recupero_iter_base->execute();
                $result_recupero_iter_base = $select_recupero_iter_base->get_result();

                if ($result_recupero_iter_base->num_rows > 0) {
                    $iter_contratto = $result_recupero_iter_base->fetch_array();
                    $id_iter = $iter_contratto['iter_di_lavoro'];
                }
            }

            if ($debug) echo $query_recupero_iter_contratto . '<br>';

            if ($id_contratto > 0) {
                if ($debug) echo 'UPDATE pratiche SET id_iter = "' . db_input($id_iter) . '" WHERE id = "' . db_input($id_pratica) . '"<br>';


                $mysqli->autocommit(FALSE);
                try{
                    $query_update_iter_pratiche = $mysqli->prepare('UPDATE ed_pratiche SET id_iter = "' . db_input($id_iter) . '" WHERE id = "' . db_input($id_ed_pratica) . '"');                                                          
                    $query_update_iter_pratiche->execute();                   

                    $mysqli->commit();

                    // echo '<strong>UPDATE ITER PRATICHE IN ED_PRATICHE EFFETTUATO</strong><br>';

                }catch(mysqli_sql_exception $e) {
                    echo 'ERRORE UPDATE ITER PRATICHE IN ED_PRATICHE ' . $e->getMessage() . '<br>';
                    $mysqli->rollback();                            
                }
                $mysqli->autocommit(TRUE);
                $query_update_iter_pratiche->close();
            }
        }
        //}
        //else if(count($titolo)!=0) {
        //    echo '<strong>COME PRECEDENTE</strong><br>';
        //}
        //else {
        //    echo '<strong>NON SETTATO</strong><br>';
        //}

        $titolo1 = $titolo;

        $delete_anag = $mysqli->prepare("DELETE FROM anagrafica WHERE id_anagrafica NOT IN (SELECT DISTINCT(anagrafica) FROM utente WHERE anagrafica IS NOT NULL)");
        $delete_anag->execute();                   
        $delete_anag->close();
        ?>
        <?php

        echo '<div class="row" style="margin-bottom: 7px;"><div class="col-md-12"><hr></div></div>';
    }
    
    global $globalLottoMandante;
    $globalLottoMandante = $lotto_mandante['id'];
    
}

// TRELLO #00165
function verifica_data_affidamento_aui($dati)
{
    global $campi;
    $query_data_aui = "SELECT data_fine FROM aui_report_esecuzione WHERE 1 ORDER BY data_fine DESC LIMIT 1";
    $ris = db_query($query_data_aui);
    if (db_num_rows($ris) > 0) {
        $data_fine_aui = db_fetch_array($ris)[0]['data_fine'];
    } else {
        $data_fine_aui = null;
    }
    if (isset($_POST['fixed_data_affidamento']) && $_POST['fixed_data_affidamento'] != '') {
        $data_affidamento = $_POST['fixed_data_affidamento'];
    } else {
        $occorrenze = array_keys($campi, 'altro*-*data_affidamento');
        if (count($occorrenze) > 0) {
            for ($k = 0; $k < count($occorrenze); $k++) {
                $risultante = calcola_risultante($dati, $occorrenze[$k], $_POST['funzione_pers'], $_POST['colonne_pers']);
                if ($risultante != "") {
                    $data_affidamento = converti_data_acquisita($risultante);
                } else {
                    $data_affidamento = date('Y-m-d');
                }

            }
        } else {
            $data_affidamento = date('Y-m-d');
        }
    }
    if ($data_affidamento <= $data_fine_aui && $data_fine_aui != null) {
        echo " DATA ULTIMA ESECUZIONE AUI : " . date('d-m-Y', strtotime($data_fine_aui));
        echo " DATA AFFIDAMENTO: " . date('d-m-Y', strtotime($data_affidamento)) . " ";
        return false;
    } else {
        return true;

    }
}

$idFilialeDefault = 1;

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

        .rollback {
            color: #F00;
            font-weight: 600;
            font-size: 15px;
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
            <div style="position: fixed; width: 100%; height: 100%; display: none; top: 0px; left: 0px; background-color: rgba(0, 0, 0, 0.8); z-index: 100000000 ! important;"
                 id="loader0" align="center">
                <img src="assets/img/loader.png" style="position:relative;top:50%;margin-top:-45px"/>
            </div>

            <!-- APERTURA DELLE MODAL AJAX -->
            <div id="ajax-modal" class="modal container fade" tabindex="-1" style="height: 98vh"></div>
            <!-- FINE MODAL AJAX -->

            <!-- BEGIN STYLE CUSTOMIZER -->
            <?php require_once('elements/theme_customizer.php'); ?>
            <!-- END BEGIN STYLE CUSTOMIZER -->
            <!-- BEGIN PAGE HEADER-->
            <h3 class="page-title">
                Acquisizione Dati
            </h3>
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <i class="fa fa-home"></i>
                        <a href="index.php">Home</a>
                        <i class="fa fa-angle-right"></i>
                    </li>
                    <li>
                        <a href="#">Acquisizione Dati</a>
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
                                <a href="#tab_0" data-toggle="tab">Acquisizione Dati</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_0">
                                <form name="formElementi" id="form_sample_22" method="POST"
                                      enctype="multipart/form-data" onSubmit="return verifica_indirizzo_aui($(this));">
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

                                            $campi = $_POST['campo'];

                                            $posizioni = $_POST['posizione'];
                                            $posizioni_indirizzi = $_POST['posizione_indirizzo'];

                                            $rec_predefiniti = $_POST['rec_predefinito'];
                                            $tipi_indirizzo = $_POST['address_type'];
                                            $tipi_anagrafica = $_POST['linked_type'];

                                            if(isset($_POST['file_esistente']) && $_POST['file_esistente']!='') {
                                                $inputFileName = $_POST['file_esistente'];

                                                $nome_file = basename($inputFileName);
                                                $estensione = pathinfo($nome_file, PATHINFO_EXTENSION);

                                                $success = true;
                                                $error_type = 0;
                                            }
                                            else {
                                                $file = $_FILES['file'];

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

                                                $inputFileName = 'assets/file_acquisiti/' . $timestamp . '.' . $estensione;
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
                                                    <script>//$('#loader1').show();</script>

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
                                                                    echo basename($inputFileName);
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

                                                                $predefinito = (isset($_POST['ind_predefinito']) && $_POST['ind_predefinito'] != '') ? '1' : '0';
                                                                $corrispondenza = (isset($_POST['ind_corrispondenza']) && $_POST['ind_corrispondenza'] != '') ? '1' : '0';

                                                                $creditore1 = array();
                                                                $debitore1 = array();
                                                                $garante1 = array();
                                                                $collegato1 = array();
                                                                $pratica1 = array();
                                                                $titolo1 = array();

                                                                $creditore = array();
                                                                $debitore = array();
                                                                $garante = array();
                                                                $collegato = array();
                                                                $pratica = array();
                                                                $titolo = array();

                                                                $count_contratti = 0;

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

                                                                    $lotto_mandante = '';
                                                                    $id_lotto_studio = '';
                                                                    $vincolo_lotto_contratto = array();
                                                                    $id_creditore = '';
                                                                    $id_debitore = '';
                                                                    $id_garante = '';
                                                                    $id_collegato = '';
                                                                    $id_pratica = '';

                                                                    //TRELLO #00165
                                                                    $prosegui_importazione = true;
                                                                    // CICLO CHE CONTROLLA SE LA DATA AFFIDAMENTO E' MAGGIORE DELL' ULTIMA DATA AUI
                                                                    for ($row_temp = $rigaPartenza; $row_temp <= $highestRow; $row_temp++) {
                                                                        //  Read a row of data into an array
                                                                        $rowData_temp = $sheet->rangeToArray('A' . $row_temp . ':' . $highestColumn . $row_temp, NULL, TRUE, FALSE);
                                                                        //  Insert row data array into your database of choice here

                                                                        $dati_temp = $rowData_temp[0];
                                                                        if (!verifica_data_affidamento_aui($dati_temp)) {
                                                                            echo "<p style='color: red'>ATTENZIONE, La Data Di affidamento per la <b style='font-size: larger'> Riga -> " . $row_temp . " </b> è antecedente all'ultima stampa dell'AUI.</p><br>";
                                                                            $prosegui_importazione = false;
                                                                        }
                                                                    }

                                                                    if (!$prosegui_importazione) {
                                                                        echo "<script> $('#loader1').hide(); </script>";

                                                                    }

                                                                    // --------- INSERISCO IN ED_ACQUISIZIONE_FILE OGNI VOLTA CHE VIENE FATTA UN'ACQUISIZIONE ---------------
                                                                    $mysqli->autocommit(FALSE);
                                                                    try{
                                                                        $id_operatore = db_input($_SESSION['user_admin_id']);
                                                                        $id_mandante = db_input($_POST['mandante']);

                                                                        $query_acquisizione_file = $mysqli->prepare("INSERT INTO `ed_acquisizioni_file` SET id_utente = '$id_operatore',
                                                                                                                                                            id_mandante = '$id_mandante',
                                                                                                                                                            n_pratiche = '$highestRow' - 1,
                                                                                                                                                            tipologia = 'DATI',
                                                                                                                                                            data_acquisizione = CURRENT_TIMESTAMP");
                                                                        $query_acquisizione_file->execute();
                                                                        $id_acquisizione = $mysqli->insert_id;

                                                                        $mysqli->commit();

                                                                    }catch (mysqli_sql_exception $e){
                                                                        echo 'ERRORE INSERIMENTO ED_ACQUISIZIONI_FILE DATI: ' . $e->getMessage() . '<br> <br>';
                                                                        $mysqli->rollback();
                                                                    };                            
                                                                    $mysqli->autocommit(TRUE);                                                                   
                                                                    $query_acquisizione_file->close();                                                                                                                                     

                                                                    // CICLO CHE CONTROLLA SE LA DATA AFFIDAMENTO E' MAGGIORE DELL' ULTIMA DATA AUI
                                                                    $start_time=microtime(true);
                                                                    //  Loop through each row of the worksheet in turn
                                                                    for ($row = $rigaPartenza; $row <= $highestRow && $prosegui_importazione; $row++) {
                                                                        //  Read a row of data into an array
                                                                        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                                                                        //  Insert row data array into your database of choice here

                                                                        $dati = $rowData[0];                                                                       
                                                                        
                                                                        elaboraDati($dati, $row, $id_acquisizione);
                                                                    }
                                                                    $end_time=microtime(true);
                                                                    $execution_time = $end_time - $start_time;
                                                                    echo '<strong>Tempo esecuzione: ' .$execution_time.'</strong> <br><br>';
                                                                    // ------------ STORED PROCEDURE INSERIMENTO TAB ORIGINALI ---------------------
                                                                    // e_populateTabs();

                                                                } else if ($_POST['tipo_file'] == 2 || $_POST['tipo_file'] == 3 || $_POST['tipo_file'] == 4) {
                                                                    $testo = file($inputFileName);
                                                                    $row = file($inputFileName);

                                                                    $riferimento = '';
                                                                    $cf_piva = '';

                                                                    $lotto_mandante = '';
                                                                    $id_lotto_studio = '';
                                                                    $vincolo_lotto_contratto = array();
                                                                    $id_creditore = '';
                                                                    $id_debitore = '';
                                                                    $id_garante = '';
                                                                    $id_collegato = '';
                                                                    $id_pratica = '';

                                                                    if ($_POST['tipo_file'] == 2 || $_POST['tipo_file'] == 3) {
                                                                        //TRELLO #00165
                                                                        $prosegui_importazione = true;
                                                                        // CICLO CHE CONTROLLA SE LA DATA AFFIDAMENTO E' MAGGIORE DELL' ULTIMA DATA AUI

                                                                        for ($j = $rigaPartenzaNonExcel; $j < count($row); $j++) {
                                                                            $dati_temp = explode($delimiter, $row[$j]);
                                                                            if (!verifica_data_affidamento_aui($dati_temp)) {
                                                                                echo "<p style='color: red'>ATTENZIONE, La Data Di affidamento per la <b style='font-size: larger'> Riga -> " . $j . " </b> è antecedente all'ultima stampa dell'AUI.</p><br>";
                                                                                $prosegui_importazione = false;
                                                                            }
                                                                        }
                                                                        if (!$prosegui_importazione) {
                                                                            echo "<script> $('#loader1').hide(); </script>";

                                                                        }
                                                                        // CICLO CHE CONTROLLA SE LA DATA AFFIDAMENTO E' MAGGIORE DELL' ULTIMA DATA AUI
                                                                        for ($j = $rigaPartenzaNonExcel; $j < count($row) && $prosegui_importazione; $j++) {
                                                                            $dati = explode($delimiter, $row[$j]);

                                                                            elaboraDati($dati, $j, $id_acquisizione);
                                                                        }
                                                                        $end_time=microtime(true);
                                                                        $execution_time = $end_time - $start_time;
                                                                        echo '<strong>microtime' .$execution_time.'</strong>';
                                                                    } else if ($_POST['tipo_file'] == 4) {
                                                                        $arrayPositions = explode(',', $_POST['lunghezzaPosizioni']);
                                                                        //TRELLO #00165
                                                                        $prosegui_importazione = true;
                                                                        // CICLO CHE CONTROLLA SE LA DATA AFFIDAMENTO E' MAGGIORE DELL' ULTIMA DATA AUI
                                                                        for ($j = $rigaPartenzaNonExcel; $j < count($row); $j++) {
                                                                            $dati_temp = array();
                                                                            for ($z = 0; $z < count($arrayPositions); $z++) {
                                                                                $dati_temp[] = trim(mb_substr($row[$j], ($arrayPositions[$z] - 1), ($arrayPositions[$z + 1] - $arrayPositions[$z]), 'UTF-8'));
                                                                            }
                                                                            //print_r($dati);
                                                                            if (!verifica_data_affidamento_aui($dati_temp)) {
                                                                                echo "<p style='color: red'>ATTENZIONE, La Data Di affidamento per la <b style='font-size: larger'> Riga -> " . $j . " </b> è antecedente all'ultima stampa dell'AUI.</p><br>";
                                                                                $prosegui_importazione = false;
                                                                            }
                                                                        }
                                                                        if (!$prosegui_importazione) {
                                                                            echo "<script> $('#loader1').hide(); </script>";

                                                                        }
                                                                        // CICLO CHE CONTROLLA SE LA DATA AFFIDAMENTO E' MAGGIORE DELL' ULTIMA DATA AUI


                                                                        for ($j = $rigaPartenzaNonExcel; $j < count($row) && $prosegui_importazione; $j++) {
                                                                            $dati = array();
                                                                            for ($z = 0; $z < count($arrayPositions); $z++) {
                                                                                $dati[] = trim(mb_substr($row[$j], ($arrayPositions[$z] - 1), ($arrayPositions[$z + 1] - $arrayPositions[$z]), 'UTF-8'));
                                                                            }
                                                                            elaboraDati($dati, $j, $id_acquisizione);
                                                                        }
                                                                    }
                                                                }

                                                                $mysqli->autocommit(FALSE);
                                                                try{
                                                                    $update_acquisizione_pending = $mysqli->prepare("UPDATE `ed_acquisizioni_file` SET stato = 'Pending' WHERE id = '$id_acquisizione' AND tipologia = 'DATI'");
                                                                    $update_acquisizione_pending->execute();

                                                                    $query_stati_acquisizione = $mysqli->prepare("INSERT INTO `e_stati_acquisizioni` SET id_utente = '$id_operatore',
                                                                                                                                                        id_acquisizione = '$id_acquisizione',
                                                                                                                                                        tipologia = 'DATI',
                                                                                                                                                        data_cambio_stato = CURRENT_TIMESTAMP");
                                                                    $query_stati_acquisizione->execute();
                                                                }catch (mysqli_sql_exception $e){
                                                                    echo 'ERRORE INSERIMENTO ED_ACQUISIZIONI_FILE DATI O LOG STATI: ' . $e->getMessage() . '<br> <br>';
                                                                    $mysqli->rollback();
                                                                };                            
                                                                $mysqli->autocommit(TRUE);                                                                   
                                                                $update_acquisizione_pending->close();
                                                                $query_stati_acquisizione->close();


                                                                writeSession($_SESSION, $session);

                                                                if ($id_pratica != '')
                                                                    aggiornaIterPratica($id_pratica);

                                                                // ### GESTIONE EVENTI ######################################################################

                                                                $csv_imported = implode(',', $imported_practices);
                                                                $x = implode(',', $pratica);

                                                                ?>
                                                                <?php
                                                                //GESTIONE PAGOPA INSERIMENTO

                                                                if ($csv_imported != '') {
                                                                    // ASSUNTO CHE TUTTE LE PRATICHE DEL FILE SIANO DELLO STESSO LOTTO, SE NON E' COSI VANNO
                                                                    // CERCATI I LOTTI E CHIAMATA QUESTA FUNZIONE PER LE PRATICHE DEL LOTTO
                                                                    echo '<script type="text/javascript">';
                                                                    //echo "$('#loader1').show();";
                                                                    //echo "var lottoMandante = '".$codiceLotto."';";
                                                                    // echo "eventoCreazioneLotto( '$csv_imported', false );";
                                                                    // echo "$('#loader1').hide();";
                                                                    echo '</script>';
                                                                    echo '<B>ESECUZIONE EVENTI ASSOCIATI ESEGUITA<B>';
                                                                    eventiStrutturatiAcquisizionePrt($csv_imported, $_POST['evento_strutturato_predefinito_new']);

                                                                    foreach ($imported_practices as $prtPA) {
                                                                        creaPdpdaPrt($prtPA);

                                                                    }


                                                                } else {
                                                                    // ### GESTIONE EVENTI AGGIORNAMENTO PRATICHE VIENE ESEGUITO SOLO EVENTO STRUTTURATO NEW NNT ALTRO ######################################################################

                                                                    $csv_updated = implode(',', $updated_practices);
                                                                    ?>
                                                                    <?php

                                                                    if ($csv_updated != '' && $_POST['evento_strutturato_predefinito_new'] > 0) {
                                                                        // ASSUNTO CHE TUTTE LE PRATICHE DEL FILE SIANO DELLO STESSO LOTTO, SE NON E' COSI VANNO
                                                                        // CERCATI I LOTTI E CHIAMATA QUESTA FUNZIONE PER LE PRATICHE DEL LOTTO
                                                                        echo '<script type="text/javascript">';
                                                                        //echo "$('#loader1').show();";
                                                                        //echo "var lottoMandante = '".$codiceLotto."';";
                                                                        // echo "eventoCreazioneLotto( '$csv_imported', false );";
                                                                        // echo "$('#loader1').hide();";
                                                                        echo '</script>';
                                                                        echo '<B>ESECUZIONE EVENTI ASSOCIATI PER AGGIORNAMENTO ESEGUITA<B>';
                                                                        eventiStrutturatiAcquisizionePrt($csv_updated, $_POST['evento_strutturato_predefinito_new']);


                                                                        foreach ($updated_practices as $prtPA) {
                                                                            creaPdpdaPrt($prtPA);

                                                                        }


                                                                    }
                                                                    // ### GESTIONE EVENTI AGGIORNAMENTO PRATICHE VIENE ESEGUITO SOLO EVENTO STRUTTURATO NEW NNT ALTRO ######################################################################

                                                                }
                                                                // ### GESTIONE EVENTI ######################################################################

                                                                db_query("UPDATE lotti_mandante SET acquisizione_in_corso=0 WHERE id='" . $globalLottoMandante . "'");

                                                                db_query("DELETE FROM lotti_mandante WHERE id ='" . $globalLottoMandante . "' AND  id NOT IN (SELECT DISTINCT id_lotto_mandante FROM pratiche WHERE id_lotto_mandante='" . $globalLottoMandante . "' )");

                                                                if(isset($_POST['azione_post_exec']) && $_POST['azione_post_exec']>0) {
                                                                    $query_get_azione = "SELECT azione as exec_query 
                                                                                        FROM acquisizione_dati_azioni 
                                                                                        WHERE id = '".db_input($_POST['azione_post_exec'])."'";
                                                                    $ris_get_azione = db_query($query_get_azione);

                                                                    if(db_num_rows($ris_get_azione)>0) {
                                                                        $exec_azione = db_fetch_array_assoc_single($ris_get_azione);
                                                                        if(strpos($exec_azione['exec_query'],'[[ID_PRATICHE]]')>0) {
                                                                            $query_exec_azione = str_replace('[[ID_PRATICHE]]','"'.implode(',',$praticheElaborate).'"',$exec_azione['exec_query']);
                                                                            echo $query_exec_azione.'<br>';
                                                                            $ris_SP = db_query($query_exec_azione);
                                                                            print_r(db_fetch_array_assoc($ris_SP));
                                                                        }
                                                                    }
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
                                                $query_recupero_configurazioni = 'SELECT * FROM template_acquisizione WHERE id = "' . $_GET['config'] . '"';
                                                $ris_recupero_configurazioni = db_query($query_recupero_configurazioni) or die(mysql_error());
                                                $configurazione = mysql_fetch_array($ris_recupero_configurazioni);

                                                $idFilialeDefault = $configurazione['id_filiale']>'0' ? $configurazione['id_filiale'] : '1';

                                                $configurazione_campi = explode(';', $configurazione['campi']);
                                                $configurazione_posizioni = explode(';', $configurazione['posizioni']);
                                                $configurazione_posizioni_indirizzo = explode(';', $configurazione['posizioni_indirizzo']);
                                                $configurazione_rec_predefinito = explode(';', $configurazione['recapiti_predefiniti']);
                                                $configurazione_tipo_indirizzo = explode(';', $configurazione['tipologie_indirizzo']);
                                                $configurazione_tipo_collegato = explode(';', $configurazione['tipologie_collegato']);
                                                $configurazione_applica_quando_contratto = explode(';', $configurazione['applica_quando_contratto']);
                                                $configurazione_applica_quando_campo = explode(';', $configurazione['applica_quando_campo']);
                                                $configurazione_applica_quando_operatore = explode(';', $configurazione['applica_quando_operatore']);
                                                $configurazione_applica_quando_valore = explode(';', $configurazione['applica_quando_valore']);
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

                                                    <?php require_once('include/table-loader.php'); ?>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h3>RECUPERO CONFIGURAZIONE</h3>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4" style="line-height:33px;">
                                                            Mandante
                                                            <div>
                                                                <?php
                                                                $bloccaModMand = "";
                                                                if ($_SESSION['user_role'] == MANDANTE) {
                                                                    $bloccaModMand = "readonly";
                                                                }
                                                                ?>
                                                                <input id="valore_mandante"
                                                                       name="mandante" <?php echo $bloccaModMand; ?>
                                                                       class="form-control select2_mandanti" type="text"
                                                                       onChange="$('#dettaglio_mandante').attr('data-brand',$(this).val()); compila_configutazioni($(this).val()); compila_contratti($(this).val())"
                                                                       value="<?php if (isset($_GET['config']) && $_GET['config'] != '') {
                                                                           echo $configurazione['id_mandante'];
                                                                       } elseif ($_SESSION['user_role'] == MANDANTE) {
                                                                           echo $_SESSION['user_admin_id'];
                                                                       } ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4" style="line-height:33px;">
                                                            Carica Configurazione
                                                            <div>
                                                                <select id="inserimento_configurazione"
                                                                        class="form-control select2me"
                                                                        onChange="location.href = location.protocol + '//' + location.host + location.pathname + '?config=' + $(this).val()">
                                                                    <option></option>

                                                                    <?php
                                                                    if (isset($_GET['config']) && $_GET['config'] != '') {
                                                                        $query = 'SELECT *
                                                                                                FROM template_acquisizione
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
                                                        <div class="col-md-4" style="line-height:33px;">
                                                            Evento Strutturato
                                                            <div style="margin-bottom: 7px;">
                                                                <input id="evento_strutturato_predefinito_new"
                                                                       name="evento_strutturato_predefinito_new"
                                                                       class="form-control select2_eventi_strutturati"
                                                                       type="text"
                                                                       value="<?php if (isset($_GET['config']) && $_GET['config'] != '') {
                                                                           echo $configurazione['evento_strutturato_predefinito_new'];
                                                                       } ?>">
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
                                                            <div class="options riga" <?php /*if($configurazione['tipo_file'] != 1) echo 'style="display:none"';*/ ?>>
                                                                Riga di partenza
                                                                <small>(richiesta solo per file Excel)</small>
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
                                                        <div class="col-md-4">
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
                                                                    <option <?php if ($configurazione['formato_valuta'] == '4') echo 'selected' ?>
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
                                                                    <option <?php if ($configurazione['formato_data'] == 'dd.mm.YYYY') echo 'selected' ?>
                                                                            value="dd.mm.YYYY">dd.mm.YYYY
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
                                                                    <option <?php if ($configurazione['formato_data'] == 'ddmmyy') echo 'selected' ?>
                                                                            value="ddmmyy">ddmmyy
                                                                    </option>
                                                                    <option <?php if ($configurazione['formato_data'] == 'yymmdd') echo 'selected' ?>
                                                                            value="yymmdd">yymmdd
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="options delimitatore" <?php if ($configurazione['tipo_file'] != 2) echo 'style="display:none"'; ?>>
                                                                Carattere delimitatore
                                                                <small>(richiesta solo per file con carattere
                                                                    separatore)
                                                                </small>
                                                                <div>
                                                                    <input id="delimitatore" type="text"
                                                                           class="form-control" name="delimitatore"
                                                                           value="<?php echo $configurazione['delimitatore'] ?>">
                                                                    <small></small>
                                                                </div>
                                                            </div>
                                                            <div class="options lunghezzaPosizioni" <?php if ($configurazione['tipo_file'] != 4) echo 'style="display:none"'; ?>>
                                                                Posizioni Campi
                                                                <small>(richiesta solo per file con lunghezza fissa)
                                                                </small>
                                                                <div>
                                                                    <input id="lunghezzaPosizioni" type="text"
                                                                           class="form-control"
                                                                           name="lunghezzaPosizioni"
                                                                           value="<?php echo $configurazione['lunghezzaPosizioni'] ?>">
                                                                    <small></small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h3>OVERRIDE INFORMAZIONI E VALORI PREDEFINITI</h3>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            Data di Affidamento Pratica<br>
                                                            <small>(se inserita verrà ignorato il valore nel file)
                                                            </small>
                                                            <div class="input-group date date-picker"
                                                                 data-date-format="yyyy-mm-dd">
                                                                <input readonly onClick="$('#calendar0').click();"
                                                                       placeholder="Inserisci Data Affidamento"
                                                                       name="fixed_data_affidamento" type="text"
                                                                       class="form-control">
                                                                <span class="input-group-btn">
                                                                    <button id="calendar0"
                                                                            style="padding: 6px 5px !important"
                                                                            class="btn btn-info" type="button">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            Data di Fine Mandato Pratica<br>
                                                            <small>(se inserita verrà ignorato il valore nel file)
                                                            </small>
                                                            <div class="input-group date date-picker"
                                                                 data-date-format="yyyy-mm-dd">
                                                                <input readonly onClick="$('#calendar1').click();"
                                                                       placeholder="Inserisci Data Fine Mandato"
                                                                       name="fixed_data_fine_mandato" type="text"
                                                                       class="form-control">
                                                                <span class="input-group-btn">
                                                                    <button id="calendar1"
                                                                            style="padding: 6px 5px !important"
                                                                            class="btn btn-info" type="button">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            Natura del Credito Fissa<br>
                                                            <small>(se inserita verrà ignorata la decodifica)</small>
                                                            <div>
                                                                <select name="natura_credito" id="natura_credito_fissa"
                                                                        class="form-control select2me">
                                                                    <option></option>
                                                                    <?php
                                                                    $ris_nature = db_query('SELECT * FROM nature_credito ORDER BY codice ASC');
                                                                    while ($nature = mysql_fetch_array($ris_nature)) {
                                                                        ?>
                                                                        <option <?php if ($configurazione['natura_fissa'] == $nature['id']) echo 'selected' ?>
                                                                                value="<?php echo $nature['id'] ?>"><?php echo $nature['descrizione'] . ' (' . $nature['codice'] . ')' ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="tab-content">
                                                                <div class="tab-pane fade active in" id="tab_1_1">
                                                                    Filiale <br>
                                                                    <small>&nbsp;</small>
                                                                    <div class="input-icon right">
                                                                        <i class="fa"></i>
                                                                        <select id="id_select_filiale" name="id_filiale" class="form-control select2me">
                                                                            <?php
                                                                                $queryFiliali = 'SELECT id, codice, descrizione
                                                                                                    FROM filiali
                                                                                                    ORDER BY id ASC';
                                                                                $risFiliali = db_query($queryFiliali);
                                                                                while($filiale = db_fetch_array_assoc_single($risFiliali)) {
                                                                                    ?>
                                                                                    <option <?php if($filiale['id']==$idFilialeDefault) { echo 'selected'; } ?> value="<?php echo $filiale['id']; ?>"><?php echo $filiale['descrizione']; ?></option>
                                                                                    <?php
                                                                                }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            ind. primari predef.<br>
                                                            &nbsp;
                                                            <div>
                                                                <input type="checkbox"
                                                                       class="make-switch ind_primario_predefinito"
                                                                       name="ind_predefinito" value="1"
                                                                       data-on-color="success" data-off-color="warning"
                                                                       data-on-text="&nbsp;<i class='fa fa-check-circle-o'></i>&nbsp;"
                                                                       data-off-text="&nbsp;<i class='fa fa-circle-o'></i>&nbsp;" <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione['indirizzi_predefiniti'] == 1) echo 'checked'; ?>><input
                                                                        id="checkHiddenIndPred" type="hidden" value="0"
                                                                        name="ind_predefinito">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            Aggiorna Lotto Studio<br>
                                                            &nbsp;
                                                            <div>
                                                                <input type="checkbox"
                                                                       class="make-switch aggiorna_lotto_studio"
                                                                       name="aggiorna_lotto_studio" value="1"
                                                                       data-on-color="success" data-off-color="warning"
                                                                       data-on-text="&nbsp;<i class='fa fa-check-circle-o'></i>&nbsp;"
                                                                       data-off-text="&nbsp;<i class='fa fa-circle-o'></i>&nbsp;" <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione['aggiorna_lotto_studio'] == 1) echo 'checked'; ?>><input
                                                                        id="checkHiddenaggiorna_lotto_studio"
                                                                        type="hidden" value="0"
                                                                        name="aggiorna_lotto_studio">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1 hidden">
                                                            Associa Pratica Esistente<br>
                                                            &nbsp;
                                                            <div>
                                                                <input type="checkbox"
                                                                       class="make-switch acquiszione_riprendi_pratiche"
                                                                       name="acquiszione_riprendi_pratiche" value="1"
                                                                       data-on-color="success" data-off-color="warning"
                                                                       data-on-text="&nbsp;<i class='fa fa-check-circle-o'></i>&nbsp;"
                                                                       data-off-text="&nbsp;<i class='fa fa-circle-o'></i>&nbsp;" <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione['acquiszione_riprendi_pratiche'] == 1) echo 'checked'; ?>><input
                                                                        id="checkHiddenacquiszione_riprendi_pratiche"
                                                                        type="hidden" value="0"
                                                                        name="acquiszione_riprendi_pratiche">
                                                            </div>
                                                        </div>
                                                        <!--
                                                                <div class="col-md-2">
                                                                    Utilizza per corrispondenza
                                                                    <div>
                                                                        <input type="checkbox" class="make-switch" name="ind_corrispondenza" value="1" data-on-color="success" data-off-color="warning" data-on-text="&nbsp;<i class='fa fa-check-circle-o'></i>&nbsp;" data-off-text="&nbsp;<i class='fa fa-circle-o'></i>&nbsp;">
                                                                    </div>
                                                                </div>
                                                                -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h3>DETTAGLIO CONFIGURAZIONE</h3>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="margin-bottom: 10px;">
                                                        <div class="col-md-1" style="line-height:33px;">
                                                            Contratto Fisso
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div>
                                                                <select id="contratto" name="contratto"
                                                                        class="form-control select2me inserimento_contratto contratto">
                                                                    <option></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row" style="margin-bottom: 10px;">
                                                        <div class="col-md-offset-1 col-md-1" style="line-height:33px;">
                                                            Oppure applica:
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div>
                                                                <select name="applica_quando_contratto[]"
                                                                        id="applica_contratto_0"
                                                                        class="form-control select2me inserimento_contratto applica_contratti">
                                                                    <option></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <div style="line-height:33px;text-align:right">
                                                                se
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div>
                                                                <select name="applica_quando_campo[]"
                                                                        class="form-control select2me applica_campi">
                                                                    <optgroup label="Dati PRATICA">
                                                                        <!-- <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[0] == 'pratica*-*affidato_capitale') echo 'selected'; ?>
                                                                                value="pratica*-*affidato_capitale">
                                                                            PRATICA - Affidato Capitale
                                                                        </option>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[0] == 'pratica*-*affidato_spese') echo 'selected'; ?>
                                                                                value="pratica*-*affidato_spese">PRATICA
                                                                            - Affidato Spese
                                                                        </option>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[0] == 'pratica*-*affidato_interessi') echo 'selected'; ?>
                                                                                value="pratica*-*affidato_interessi">
                                                                            PRATICA - Affidato Interessi
                                                                        </option>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[0] == 'pratica*-*affidato_1') echo 'selected'; ?>
                                                                                value="pratica*-*affidato_1">PRATICA -
                                                                            Affidato 1
                                                                        </option>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[0] == 'pratica*-*affidato_2') echo 'selected'; ?>
                                                                                value="pratica*-*affidato_2">PRATICA -
                                                                            Affidato 2
                                                                        </option>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[0] == 'pratica*-*affidato_3') echo 'selected'; ?>
                                                                                value="pratica*-*affidato_3">PRATICA -
                                                                            Affidato 3
                                                                        </option>
                                                                        -->
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[0] == 'pratica*-*competenze_oneri_recupero') echo 'selected'; ?>
                                                                                value="pratica*-*competenze_oneri_recupero">
                                                                            PRATICA - Competenze Oneri Recupero
                                                                        </option>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[0] == 'pratica*-*competenze_spesse_incasso') echo 'selected'; ?>
                                                                                value="pratica*-*competenze_spesse_incasso">
                                                                            PRATICA - Competenze Spese Incasso
                                                                        </option>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[0] == 'pratica*-*cash_balance') echo 'selected'; ?>
                                                                                value="pratica*-*cash_balance">PRATICA -
                                                                            Cash Balance
                                                                        </option>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[0] == 'pratica*-*riferimento_mandante_1') echo 'selected'; ?>
                                                                                value="pratica*-*riferimento_mandante_1">
                                                                            PRATICA - Riferimento Mandante 1
                                                                        </option>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[0] == 'pratica*-*riferimento_mandante_2') echo 'selected'; ?>
                                                                                value="pratica*-*riferimento_mandante_2">
                                                                            PRATICA - Riferimento Mandante 2
                                                                        </option>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[0] == 'pratica*-*riferimento_mandante_3') echo 'selected'; ?>
                                                                                value="pratica*-*riferimento_mandante_3">
                                                                            PRATICA - Riferimento Mandante 3
                                                                        </option>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[0] == 'pratica*-*annotazioni') echo 'selected'; ?>
                                                                                value="pratica*-*annotazioni">PRATICA -
                                                                            Annotazioni
                                                                        </option>
                                                                        <!-- <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[0] == 'pratica*-*data_inizio_interessi') echo 'selected'; ?>
                                                                                value="pratica*-*data_inizio_interessi">
                                                                            PRATICA - Data Inizio Interessi
                                                                        </option> -->
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[0] == 'pratica*-*cp_valuta') echo 'selected'; ?>
                                                                                value="pratica*-*cp_valuta">PRATICA -
                                                                            Personalizzato Valuta
                                                                        </option>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[0] == 'pratica*-*cp_testo') echo 'selected'; ?>
                                                                                value="pratica*-*cp_testo">PRATICA -
                                                                            Personalizzato Testo
                                                                        </option>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[0] == 'pratica*-*cp_data') echo 'selected'; ?>
                                                                                value="pratica*-*cp_data">PRATICA -
                                                                            Personalizzato Data
                                                                        </option>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[0] == 'pratica*-*id_filiale_origine') echo 'selected'; ?>
                                                                                value="pratica*-*id_filiale_origine">
                                                                            PRATICA
                                                                            - ID Filiale
                                                                        </option>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[0] == 'pratica*-*gbv') echo 'selected'; ?>
                                                                                value="pratica*-*gbv">PRATICA
                                                                            - gbv
                                                                        </option>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[0] == 'pratica*-*prezzo_acquisto') echo 'selected'; ?>
                                                                                value="pratica*-*prezzo_acquisto">
                                                                            PRATICA
                                                                            - PREZZO D'ACQUISTO
                                                                        </option>
                                                                    </optgroup>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div>
                                                                <select name="applica_quando_operatore[]"
                                                                        class="form-control applica_operatori select2me">
                                                                    <option <?php if ($configurazione_applica_quando_operatore[0] == '%3D') echo 'selected' ?>
                                                                            value="=">UGUALE
                                                                    </option>
                                                                    <option <?php if ($configurazione_applica_quando_operatore[0] == '%3E') echo 'selected' ?>
                                                                            value=">">MAGGIORE
                                                                    </option>
                                                                    <option <?php if ($configurazione_applica_quando_operatore[0] == '%3C') echo 'selected' ?>
                                                                            value="<">MINORE
                                                                    </option>
                                                                    <option <?php if ($configurazione_applica_quando_operatore[0] == '%3C%3E') echo 'selected' ?>
                                                                            value="<>">DIVERSO
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div>
                                                                <input type="text" name="applica_quando_valore[]"
                                                                       class="form-control applica_valori"
                                                                       value="<?php echo urldecode($configurazione_applica_quando_valore[0]) ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php
                                                    $limiteContrattiStudio = 15;
                                                    for ($k = 1; $k < $limiteContrattiStudio; $k++) { ?>
                                                        <div class="row" style="margin-bottom: 10px;">
                                                            <div class="col-md-offset-1 col-md-1"
                                                                 style="line-height:33px;">
                                                                &nbsp;
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div>
                                                                    <select name="applica_quando_contratto[]"
                                                                            id="applica_contratto_<?php echo $k; ?>"
                                                                            class="form-control select2me inserimento_contratto applica_contratti">
                                                                        <option></option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <div style="line-height:33px;text-align:right">
                                                                    se
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div>
                                                                    <select name="applica_quando_campo[]"
                                                                            class="form-control select2me applica_campi">
                                                                        <optgroup label="Dati PRATICA">
                                                                            <!-- <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[$k] == 'pratica*-*affidato_capitale') echo 'selected'; ?>
                                                                                    value="pratica*-*affidato_capitale">
                                                                                PRATICA - Affidato Capitale
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[$k] == 'pratica*-*affidato_spese') echo 'selected'; ?>
                                                                                    value="pratica*-*affidato_spese">
                                                                                PRATICA
                                                                                - Affidato Spese
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[$k] == 'pratica*-*affidato_interessi') echo 'selected'; ?>
                                                                                    value="pratica*-*affidato_interessi">
                                                                                PRATICA - Affidato Interessi
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[$k] == 'pratica*-*affidato_1') echo 'selected'; ?>
                                                                                    value="pratica*-*affidato_1">PRATICA
                                                                                -
                                                                                Affidato 1
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[$k] == 'pratica*-*affidato_2') echo 'selected'; ?>
                                                                                    value="pratica*-*affidato_2">PRATICA
                                                                                -
                                                                                Affidato 2
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[$k] == 'pratica*-*affidato_3') echo 'selected'; ?>
                                                                                    value="pratica*-*affidato_3">PRATICA
                                                                                -
                                                                                Affidato 3
                                                                            </option>
                                                                            -->
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[$k] == 'pratica*-*competenze_oneri_recupero') echo 'selected'; ?>
                                                                                    value="pratica*-*competenze_oneri_recupero">
                                                                                PRATICA - Competenze Oneri Recupero
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[$k] == 'pratica*-*competenze_spesse_incasso') echo 'selected'; ?>
                                                                                    value="pratica*-*competenze_spesse_incasso">
                                                                                PRATICA - Competenze Spese Incasso
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[$k] == 'pratica*-*cash_balance') echo 'selected'; ?>
                                                                                    value="pratica*-*cash_balance">
                                                                                PRATICA -
                                                                                Cash Balance
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[$k] == 'pratica*-*riferimento_mandante_1') echo 'selected'; ?>
                                                                                    value="pratica*-*riferimento_mandante_1">
                                                                                PRATICA - Riferimento Mandante 1
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[$k] == 'pratica*-*riferimento_mandante_2') echo 'selected'; ?>
                                                                                    value="pratica*-*riferimento_mandante_2">
                                                                                PRATICA - Riferimento Mandante 2
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[$k] == 'pratica*-*riferimento_mandante_3') echo 'selected'; ?>
                                                                                    value="pratica*-*riferimento_mandante_3">
                                                                                PRATICA - Riferimento Mandante 3
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[$k] == 'pratica*-*annotazioni') echo 'selected'; ?>
                                                                                    value="pratica*-*annotazioni">
                                                                                PRATICA -
                                                                                Annotazioni
                                                                            </option>
                                                                            <!-- <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[$k] == 'pratica*-*data_inizio_interessi') echo 'selected'; ?>
                                                                                    value="pratica*-*data_inizio_interessi">
                                                                                PRATICA - Data Inizio Interessi
                                                                            </option>
                                                                            -->
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[$k] == 'pratica*-*cp_valuta') echo 'selected'; ?>
                                                                                    value="pratica*-*cp_valuta">PRATICA
                                                                                -
                                                                                Personalizzato Valuta
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[$k] == 'pratica*-*cp_testo') echo 'selected'; ?>
                                                                                    value="pratica*-*cp_testo">PRATICA -
                                                                                Personalizzato Testo
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[$k] == 'pratica*-*cp_data') echo 'selected'; ?>
                                                                                    value="pratica*-*cp_data">PRATICA -
                                                                                Personalizzato Data
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[$k] == 'pratica*-*id_filiale_origine') echo 'selected'; ?>
                                                                                    value="pratica*-*id_filiale_origine">
                                                                                PRATICA
                                                                                - ID Filiale
                                                                            </option>

                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[$k] == 'pratica*-*gbv') echo 'selected'; ?>
                                                                                    value="pratica*-*gbv">PRATICA
                                                                                - gbv
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_applica_quando_campo[$k] == 'pratica*-*prezzo_acquisto') echo 'selected'; ?>
                                                                                    value="pratica*-*prezzo_acquisto">
                                                                                PRATICA
                                                                                - PREZZO D'ACQUISTO
                                                                            </option>
                                                                        </optgroup>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div>
                                                                    <select name="applica_quando_operatore[]"
                                                                            class="form-control applica_operatori select2me">
                                                                        <option <?php if ($configurazione_applica_quando_operatore[$k] == '%3D') echo 'selected' ?>
                                                                                value="=">UGUALE
                                                                        </option>
                                                                        <option <?php if ($configurazione_applica_quando_operatore[$k] == '%3E') echo 'selected' ?>
                                                                                value=">">MAGGIORE
                                                                        </option>
                                                                        <option <?php if ($configurazione_applica_quando_operatore[$k] == '%3C') echo 'selected' ?>
                                                                                value="<">MINORE
                                                                        </option>
                                                                        <option <?php if ($configurazione_applica_quando_operatore[$k] == '%3C%3E') echo 'selected' ?>
                                                                                value="<>">DIVERSO
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div>
                                                                    <input type="text"
                                                                           class="form-control applica_valori"
                                                                           name="applica_quando_valore[]"
                                                                           value="<?php echo urldecode($configurazione_applica_quando_valore[$k]) ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>


                                                    <div class="row" style="margin-bottom: 10px;">
                                                        <div class="col-md-2" style="line-height:33px;">
                                                            Lotto Mandante
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div>
                                                                <input required class="form-control"
                                                                        name="lotto_mandante" id="nome_lotto_mandante">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row" style="margin-bottom: 10px;">
                                                        <div class="container-select">
                                                            <?php
                                                            $line = 0;
                                                            for ($i = 0; $i < 300; $i++) {
                                                                if ($configurazione_campi[$i] != '')
                                                                    $line = $i + 1;
                                                            }
                                                            if ($line < 10) $line = 10;
                                                            for ($i = 0; $i < $line; $i++) {
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
                                                                                <!--
                                                                                            <optgroup label="Dati MANDANTE">
                                                                                                <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'mandante*-*riferimento_mandante_1') echo 'selected'; ?> value="mandante*-*riferimento_mandante_1">MANDANTE - Riferimento Mandante</option>
                                                                                            </optgroup>
                                                                                            -->
                                                                                <optgroup label="Affidamento">
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'altro*-*data_affidamento') echo 'selected'; ?> value="altro*-*data_affidamento">AFFIDAMENTO - Data Affidamento</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'altro*-*data_fine_mandato') echo 'selected'; ?> value="altro*-*data_fine_mandato">AFFIDAMENTO - Data Fine Mandato</option>
                                                                                </optgroup>
                                                                                <optgroup label="Dati DEBITORE">
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*nome') echo 'selected'; ?> value="debitore*-*nome">DEBITORE - Nome</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*cognome') echo 'selected'; ?> value="debitore*-*cognome">DEBITORE - Cognome (o Ragione Sociale)</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*codice_fiscale') echo 'selected'; ?> value="debitore*-*codice_fiscale">DEBITORE - Codice Fiscale/P.IVA</option>
                                                                                    <!--<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*partita_iva') echo 'selected'; ?> value="debitore*-*partita_iva">DEBITORE - Partita IVA</option>-->
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*sesso') echo 'selected'; ?> value="debitore*-*sesso">DEBITORE - Sesso</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*data_nascita') echo 'selected'; ?> value="debitore*-*data_nascita">DEBITORE - Data di Nascita</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*citta_nascita') echo 'selected'; ?> value="debitore*-*citta_nascita">DEBITORE - Citta di Nascita</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*provincia_nascita') echo 'selected'; ?> value="debitore*-*provincia_nascita">DEBITORE - Provincia di Nascita</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*nazione_nascita') echo 'selected'; ?> value="debitore*-*nazione_nascita">DEBITORE - Nazione di Nascita</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*tipo_indirizzo') echo 'selected'; ?> value="debitore*-*tipo_indirizzo">DEBITORE - Tipologia Indirizzo</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*indirizzo') echo 'selected'; ?> value="debitore*-*indirizzo">DEBITORE - Indirizzo</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*cap') echo 'selected'; ?> value="debitore*-*cap">DEBITORE - CAP</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*citta') echo 'selected'; ?> value="debitore*-*citta">DEBITORE - Citta</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*provincia') echo 'selected'; ?> value="debitore*-*provincia">DEBITORE - Provincia</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*nazione') echo 'selected'; ?> value="debitore*-*nazione">DEBITORE - Nazione</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*indirizzo-telefono') echo 'selected'; ?> value="debitore*-*indirizzo-telefono">DEBITORE - Telefono</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*indirizzo-cellulare') echo 'selected'; ?> value="debitore*-*indirizzo-cellulare">DEBITORE - Cellulare</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*indirizzo-email') echo 'selected'; ?> value="debitore*-*indirizzo-email">DEBITORE - Email</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*indirizzo-pec') echo 'selected'; ?> value="debitore*-*indirizzo-pec">DEBITORE - PEC</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*tipo-recapito') echo 'selected'; ?> value="debitore*-*tipo-recapito">DEBITORE - Tipo Recapito</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*codice_anagrafico_mandante') echo 'selected'; ?> value="debitore*-*codice_anagrafico_mandante">DEBITORE - Codice Anagrafico Mandante</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*ragione_sociale_collegato') echo 'selected'; ?> value="debitore*-*ragione_sociale_collegato">DEBITORE - Ragione Sociale Collegato</option>
                                                                                </optgroup>
                                                                                <optgroup
                                                                                        label="Dati ANAGRAFICA COLLEGATA DEBITORE">
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*tipologia') echo 'selected'; ?> value="collegato*-*tipologia">COLLEGATA DEBITORE - Tipologia Collegato</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*nome') echo 'selected'; ?> value="collegato*-*nome">COLLEGATA DEBITORE - Nome</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*cognome') echo 'selected'; ?> value="collegato*-*cognome">COLLEGATA DEBITORE - Cognome (o Ragione Sociale)</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*codice_fiscale') echo 'selected'; ?> value="collegato*-*codice_fiscale">COLLEGATA DEBITORE - Codice Fiscale/P.IVA</option>
                                                                                    <!--<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*partita_iva') echo 'selected'; ?> value="collegato*-*partita_iva">COLLEGATA DEBITORE - Partita IVA</option>-->
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*sesso') echo 'selected'; ?> value="collegato*-*sesso">COLLEGATA DEBITORE - Sesso</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*data_nascita') echo 'selected'; ?> value="collegato*-*data_nascita">COLLEGATA DEBITORE - Data di Nascita</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*citta_nascita') echo 'selected'; ?> value="collegato*-*citta_nascita">COLLEGATA DEBITORE - Citta di Nascita</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*provincia_nascita') echo 'selected'; ?> value="collegato*-*provincia_nascita">COLLEGATA DEBITORE - Provincia di Nascita</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*nazione_nascita') echo 'selected'; ?> value="collegato*-*nazione_nascita">COLLEGATA DEBITORE - Nazione di Nascita</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*tipo_indirizzo') echo 'selected'; ?> value="collegato*-*tipo_indirizzo">COLLEGATA DEBITORE - Tipologia Indirizzo</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*indirizzo') echo 'selected'; ?> value="collegato*-*indirizzo">COLLEGATA DEBITORE - Indirizzo</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*cap') echo 'selected'; ?> value="collegato*-*cap">COLLEGATA DEBITORE - CAP</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*citta') echo 'selected'; ?> value="collegato*-*citta">COLLEGATA DEBITORE - Citta</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*provincia') echo 'selected'; ?> value="collegato*-*provincia">COLLEGATA DEBITORE - Provincia</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*nazione') echo 'selected'; ?> value="collegato*-*nazione">COLLEGATA DEBITORE - Nazione</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*indirizzo-telefono') echo 'selected'; ?> value="collegato*-*indirizzo-telefono">COLLEGATA DEBITORE - Telefono</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*indirizzo-email') echo 'selected'; ?> value="collegato*-*indirizzo-email">COLLEGATA DEBITORE - Email</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*tipo-recapito') echo 'selected'; ?> value="collegato*-*tipo-recapito">COLLEGATA DEBITORE - Tipo Recapito</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*codice_anagrafico_mandante') echo 'selected'; ?> value="collegato*-*codice_anagrafico_mandante">COLLEGATA DEBITORE - Codice Anagrafico Mandante</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*ragione_sociale_collegato') echo 'selected'; ?> value="collegato*-*ragione_sociale_collegato">COLLEGATA DEBITORE - Ragione Sociale Collegato</option>
                                                                                </optgroup>
                                                                                <optgroup
                                                                                        label="Dati COLLEGATO PRATICA">
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*tipologia') echo 'selected'; ?> value="garante*-*tipologia">COLLEGATO PRATICA - Tipologia Collegato</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*nome') echo 'selected'; ?> value="garante*-*nome">COLLEGATO PRATICA - Nome</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*cognome') echo 'selected'; ?> value="garante*-*cognome">COLLEGATO PRATICA - Cognome (o Ragione Sociale)</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*codice_fiscale') echo 'selected'; ?> value="garante*-*codice_fiscale">COLLEGATO PRATICA - Codice Fiscale/P.IVA</option>
                                                                                    <!--<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*partita_iva') echo 'selected'; ?> value="garante*-*partita_iva">GARANTE - Partita IVA</option>-->
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*sesso') echo 'selected'; ?> value="garante*-*sesso">COLLEGATO PRATICA - Sesso</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*data_nascita') echo 'selected'; ?> value="garante*-*data_nascita">COLLEGATO PRATICA - Data di Nascita</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*citta_nascita') echo 'selected'; ?> value="garante*-*citta_nascita">COLLEGATO PRATICA - Citta di Nascita</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*provincia_nascita') echo 'selected'; ?> value="garante*-*provincia_nascita">COLLEGATO PRATICA - Provincia di Nascita</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*nazione_nascita') echo 'selected'; ?> value="garante*-*nazione_nascita">COLLEGATO PRATICA - Nazione di Nascita</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*tipo_indirizzo') echo 'selected'; ?> value="garante*-*tipo_indirizzo">COLLEGATO PRATICA - Tipologia Indirizzo</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*indirizzo') echo 'selected'; ?> value="garante*-*indirizzo">COLLEGATO PRATICA - Indirizzo</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*cap') echo 'selected'; ?> value="garante*-*cap">COLLEGATO PRATICA - CAP</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*citta') echo 'selected'; ?> value="garante*-*citta">COLLEGATO PRATICA - Citta</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*provincia') echo 'selected'; ?> value="garante*-*provincia">COLLEGATO PRATICA - Provincia</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*nazione') echo 'selected'; ?> value="garante*-*nazione">COLLEGATO PRATICA - Nazione</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*indirizzo-telefono') echo 'selected'; ?> value="garante*-*indirizzo-telefono">COLLEGATO PRATICA - Telefono</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*indirizzo-email') echo 'selected'; ?> value="garante*-*indirizzo-email">COLLEGATO PRATICA - Email</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*tipo-recapito') echo 'selected'; ?> value="garante*-*tipo-recapito">COLLEGATO PRATICA - Tipo Recapito</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*codice_anagrafico_mandante') echo 'selected'; ?> value="garante*-*codice_anagrafico_mandante">COLLEGATO PRATICA - Codice Anagrafico Mandante</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*ragione_sociale_collegato') echo 'selected'; ?> value="garante*-*ragione_sociale_collegato">COLLEGATO PRATICA - Ragione Sociale Collegato</option>
                                                                                </optgroup>
                                                                                <optgroup
                                                                                        label="Dati CREDITORE">
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'creditore*-*descrizione') echo 'selected'; ?> value="creditore*-*descrizione">CREDITORE - Ragione Sociale</option>
                                                                                </optgroup>
                                                                                <optgroup label="Dati PRATICA">
                                                                                    <!-- <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*affidato_capitale') echo 'selected'; ?> value="pratica*-*affidato_capitale">PRATICA - Affidato Capitale</option>
                                                                                                <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*affidato_spese') echo 'selected'; ?> value="pratica*-*affidato_spese">PRATICA - Affidato Spese</option>
                                                                                                <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*affidato_interessi') echo 'selected'; ?> value="pratica*-*affidato_interessi">PRATICA - Affidato Interessi</option>
                                                                                                <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*affidato_1') echo 'selected'; ?> value="pratica*-*affidato_1">PRATICA - Affidato 1</option>
                                                                                                <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*affidato_2') echo 'selected'; ?> value="pratica*-*affidato_2">PRATICA - Affidato 2</option>
                                                                                                <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*affidato_3') echo 'selected'; ?> value="pratica*-*affidato_3">PRATICA - Affidato 3</option> -->
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*competenze_oneri_recupero') echo 'selected'; ?> value="pratica*-*competenze_oneri_recupero">PRATICA - Competenze Oneri Recupero</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*competenze_spesse_incasso') echo 'selected'; ?> value="pratica*-*competenze_spesse_incasso">PRATICA - Competenze Spese Incasso</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*cash_balance') echo 'selected'; ?> value="pratica*-*cash_balance">PRATICA - Cash Balance</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*riferimento_mandante_1') echo 'selected'; ?> value="pratica*-*riferimento_mandante_1">PRATICA - Riferimento Mandante 1</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*riferimento_mandante_2') echo 'selected'; ?> value="pratica*-*riferimento_mandante_2">PRATICA - Riferimento Mandante 2</option><option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*riferimento_mandante_3') echo 'selected'; ?> value="pratica*-*riferimento_mandante_3">PRATICA - Riferimento Mandante 3</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*annotazioni') echo 'selected'; ?> value="pratica*-*annotazioni">PRATICA - Annotazioni</option>
                                                                                    <!--   <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*data_inizio_interessi') echo 'selected'; ?> value="pratica*-*data_inizio_interessi">PRATICA - Data Inizio Interessi</option> -->
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*cp_valuta') echo 'selected'; ?> value="pratica*-*cp_valuta">PRATICA - Personalizzato Valuta</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*cp_testo') echo 'selected'; ?> value="pratica*-*cp_testo">PRATICA - Personalizzato Testo</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*cp_data') echo 'selected'; ?> value="pratica*-*cp_data">PRATICA - Personalizzato Data</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*id_filiale_origine') echo 'selected'; ?> value="pratica*-*id_filiale_origine">PRATICA - ID Filiale</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*gbv') echo 'selected'; ?> value="pratica*-*gbv">PRATICA - gbv</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*prezzo_acquisto') echo 'selected'; ?> value="pratica*-*prezzo_acquisto">PRATICA - PREZZO D'ACQUISTO</option>


                                                                                </optgroup>
                                                                                <optgroup label="Dati TITOLO">
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*numero') echo 'selected'; ?> value="titolo*-*numero">TITOLO - Numero</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*data_affidamento') echo 'selected'; ?> value="titolo*-*data_affidamento">TITOLO - Data Affidamento</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*descrizione') echo 'selected'; ?> value="titolo*-*descrizione">TITOLO - Descrizione</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*data_emissione') echo 'selected'; ?> value="titolo*-*data_emissione">TITOLO - Data Emissione</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*data_scadenza') echo 'selected'; ?> value="titolo*-*data_scadenza">TITOLO - Data Scadenza</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*natura_credito') echo 'selected'; ?> value="titolo*-*natura_credito">TITOLO - Natura Credito</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*importo_iniziale') echo 'selected'; ?> value="titolo*-*importo_iniziale">TITOLO - Importo Iniziale</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*acconto') echo 'selected'; ?> value="titolo*-*acconto">TITOLO - Acconto</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*capitale') echo 'selected'; ?> value="titolo*-*capitale">TITOLO - Capitale</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*spese') echo 'selected'; ?> value="titolo*-*spese">TITOLO - Spese</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*interessi') echo 'selected'; ?> value="titolo*-*interessi">TITOLO - Interessi</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*affidato_1') echo 'selected'; ?> value="titolo*-*affidato_1">TITOLO - Affidato 1</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*affidato_2') echo 'selected'; ?> value="titolo*-*affidato_2">TITOLO - Affidato 2</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*affidato_3') echo 'selected'; ?> value="titolo*-*affidato_3">TITOLO - Affidato 3</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*oneri_studio') echo 'selected'; ?> value="titolo*-*oneri_studio">TITOLO - Oneri Studio</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*spese_incasso') echo 'selected'; ?> value="titolo*-*spese_incasso">TITOLO - Spese Incasso</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*cp_valuta') echo 'selected'; ?> value="titolo*-*cp_valuta">TITOLO - Personalizzato Valuta</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*cp_testo') echo 'selected'; ?> value="titolo*-*cp_testo">TITOLO - Personalizzato Testo</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*cp_data') echo 'selected'; ?> value="titolo*-*cp_data">TITOLO - Personalizzato Data</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*recapito_insoluto') echo 'selected'; ?> value="titolo*-*recapito_insoluto">TITOLO - Recapito Insoluto</option>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*note') echo 'selected'; ?> value="titolo*-*note">TITOLO - Note</option>
                                                                                    <!-- <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*quota_natura') echo 'selected'; ?> value="titolo*-*quota_natura">TITOLO - QUOTA NATURA</option> -->
                                                                                </optgroup>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4" style="height: 35px;">
                                                                        <?php
                                                                        $mostra_posizione = false;
                                                                        $mostra_posizione_indirizzo = false;
                                                                        $mostra_rec_predefiniti = false;
                                                                        $mostra_tipologie_indirizzo = false;
                                                                        $mostra_tipologie_collegato = false;

                                                                        if (isset($_GET['config']) && $_GET['config'] != '') {
                                                                            if ($configurazione_campi[$i] == 'collegato*-*cognome' || $configurazione_campi[$i] == 'garante*-*cognome') {
                                                                                $mostra_tipologie_collegato = true;
                                                                            }
                                                                            if ($configurazione_campi[$i] == 'debitore*-*indirizzo' || $configurazione_campi[$i] == 'garante*-*indirizzo' || $configurazione_campi[$i] == 'collegato*-*indirizzo') {
                                                                                $mostra_tipologie_indirizzo = true;
                                                                                $mostra_rec_predefiniti = true;
                                                                            }
                                                                            if ($configurazione_campi[$i] == 'debitore*-*indirizzo-telefono' || $configurazione_campi[$i] == 'debitore*-*indirizzo-cellulare' || $configurazione_campi[$i] == 'debitore*-*indirizzo-email' || $configurazione_campi[$i] == 'debitore*-*indirizzo-pec' || $configurazione_campi[$i] == 'garante*-*indirizzo-telefono' || $configurazione_campi[$i] == 'garante*-*indirizzo-email' || $configurazione_campi[$i] == 'collegato*-*indirizzo-telefono' || $configurazione_campi[$i] == 'collegato*-*indirizzo-email') {
                                                                                $mostra_rec_predefiniti = true;
                                                                            }

                                                                        }

                                                                        if (strpos($configurazione_campi[$i], 'collegato*-*') !== FALSE || strpos($configurazione_campi[$i], 'garante*-*') !== FALSE || $configurazione_campi[$i] == 'debitore*-*indirizzo' || $configurazione_campi[$i] == 'debitore*-*cap' || $configurazione_campi[$i] == 'debitore*-*citta' || $configurazione_campi[$i] == 'debitore*-*provincia' || $configurazione_campi[$i] == 'debitore*-*nazione' || $configurazione_campi[$i] == 'debitore*-*indirizzo-email' || $configurazione_campi[$i] == 'debitore*-*indirizzo-pec' || $configurazione_campi[$i] == 'debitore*-*indirizzo-telefono' || $configurazione_campi[$i] == 'debitore*-*indirizzo-cellulare' || strpos($configurazione_campi[$i], '*-*tipo_indirizzo') > 0 || strpos($configurazione_campi[$i], '*-*tipo-recapito') > 0) {
                                                                            $mostra_posizione = true;
                                                                        }

                                                                        if ($configurazione_campi[$i] == 'debitore*-*tipo_indirizzo' || $configurazione_campi[$i] == 'debitore*-*indirizzo' || $configurazione_campi[$i] == 'debitore*-*cap' || $configurazione_campi[$i] == 'debitore*-*citta' || $configurazione_campi[$i] == 'debitore*-*provincia' || $configurazione_campi[$i] == 'debitore*-*nazione' || $configurazione_campi[$i] == 'collegato*-*tipo_indirizzo' || $configurazione_campi[$i] == 'collegato*-*indirizzo' || $configurazione_campi[$i] == 'collegato*-*cap' || $configurazione_campi[$i] == 'collegato*-*citta' || $configurazione_campi[$i] == 'collegato*-*provincia' || $configurazione_campi[$i] == 'collegato*-*nazione' || $configurazione_campi[$i] == 'garante*-*tipo_indirizzo' || $configurazione_campi[$i] == 'garante*-*indirizzo' || $configurazione_campi[$i] == 'garante*-*cap' || $configurazione_campi[$i] == 'garante*-*citta' || $configurazione_campi[$i] == 'garante*-*provincia' || $configurazione_campi[$i] == 'garante*-*nazione') {
                                                                            $mostra_posizione_indirizzo = true;
                                                                        }

                                                                        $configurazione_rec_predefinito = explode(';', $configurazione['recapiti_predefiniti']);
                                                                        $configurazione_tipo_indirizzo = explode(';', $configurazione['tipologie_indirizzo']);
                                                                        $configurazione_tipo_collegato = explode(';', $configurazione['tipologie_collegato']);
                                                                        ?>

                                                                        <div style="width: 40px; float: left; margin-right: 10px;"
                                                                            class="extra-field-<?php echo $i ?> field_position-<?php echo $i ?> <?php if (!$mostra_posizione) echo 'hidden'; ?>">
                                                                            <input name="posizione[]" data-html="true"
                                                                                data-toggle="tooltip" title="Posizione"
                                                                                class="form-control posizioni"
                                                                                value="<?php if (isset($_GET['config']) && $_GET['config'] != '') echo $configurazione_posizioni[$i]; else echo '0'; ?>"
                                                                                placeholder="POS">
                                                                        </div>
                                                                        <div style="width: 40px; float: left; margin-right: 10px;"
                                                                            class="extra-field-<?php echo $i ?> field_address_position-<?php echo $i ?> <?php if (!$mostra_posizione_indirizzo) echo 'hidden'; ?>">
                                                                            <input name="posizione_indirizzo[]"
                                                                                data-html="true" data-toggle="tooltip"
                                                                                title="Posizione Indirizzo"
                                                                                class="form-control posizioni_indirizzo"
                                                                                value="<?php if (isset($_GET['config']) && $_GET['config'] != '') echo $configurazione_posizioni_indirizzo[$i]; ?>"
                                                                                placeholder="I.POS">
                                                                        </div>
                                                                        <div style="float: left; margin-right: 10px;"
                                                                            data-html="true" data-toggle="tooltip"
                                                                            title="Usa per Invio"
                                                                            class="extra-field-<?php echo $i ?> field_predefinito-<?php echo $i ?> <?php if (!$mostra_rec_predefiniti) echo 'hidden'; ?>">
                                                                            <input name="rec_predefinito[]" data-html="true"
                                                                                data-toggle="tooltip"
                                                                                title="Usa Per Invio"
                                                                                type="checkbox"
                                                                                class="make-switch recapiti_predefiniti"
                                                                                value="1"
                                                                                data-on-color="success"
                                                                                data-off-color="warning"
                                                                                data-on-text="&nbsp;<i class='fa fa-check-circle-o'></i>&nbsp;"
                                                                                data-off-text="&nbsp;<i class='fa fa-circle-o'></i>&nbsp;" <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_rec_predefinito[$i] == 1) echo 'checked'; ?> data-value="<?php echo $i ?>"><input
                                                                                    id="checkHidden<?php echo $i ?>"
                                                                                    type="hidden" value="0"
                                                                                    name="rec_predefinito[]">
                                                                        </div>
                                                                        <div style="float: left; width: 120px; margin-right: 10px;"
                                                                            data-html="true" data-toggle="tooltip"
                                                                            title="Tipologia Indirizzo"
                                                                            class="extra-field-<?php echo $i ?> field_address_type-<?php echo $i ?> <?php if (!$mostra_tipologie_indirizzo) echo 'hidden'; ?>">
                                                                            <select name="address_type[]" data-html="true"
                                                                                    data-toggle="tooltip"
                                                                                    title="Tipologia Indirizzo"
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
                                                                        <div style="float: left; width: 230px; margin-right: 10px;"
                                                                            data-html="true" data-toggle="tooltip"
                                                                            title="Tipologia Collegato"
                                                                            class="extra-field-<?php echo $i ?> field_linked_type-<?php echo $i ?> <?php if (!$mostra_tipologie_collegato) echo 'hidden'; ?>">
                                                                            <select name="linked_type[]"
                                                                                    class="form-control <?php if ($i < $line) echo 'select2me'; ?> tipologie_collegato">
                                                                                <option></option>
                                                                                <?php
                                                                                $tipologie = recuperaTipologieCollegato();

                                                                                foreach ($tipologie as $tipologia) {
                                                                                    ?>
                                                                                    <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_tipo_collegato[$i] == $tipologia['id']) echo 'selected'; ?> value="<?php echo $tipologia['id']; ?>"><?php echo $tipologia['descrizione']; ?></option>
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
                                                        </div>

                                                        <div class="col-md-6">
                                                            <button type="button"
                                                                    style="width:100%;"
                                                                    onClick="displayMoreLines()"
                                                                    class="btn btn-primary btn-aggiungi-linea">AGGIUNGI
                                                                ALTRI CAMPI
                                                            </button>
                                                        </div>
                                                        
                                                        
                                                        <div class="col-md-6" style="line-height:44px;">&nbsp;</div>
                                                        <?php
                                                        for ($i = 300; $i < 310; $i++) {
                                                            ?>
                                                            <div class="col-md-2" style="line-height:33px;">
                                                                COMPOSTO <?php echo($i + 1 - 300) ?>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div>
                                                                    <select name="campo[]"
                                                                            class="form-control select2me campi_composti"
                                                                            onChange="displayOptionalFields($(this),<?php echo $i ?>)">
                                                                        <option <?php if (!isset($_GET['config']) || $_GET['config'] == '') echo 'selected'; ?>></option>
                                                                        <optgroup label="Affidamento">
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'altro*-*data_affidamento') echo 'selected'; ?>
                                                                                    value="altro*-*data_affidamento">
                                                                                AFFIDAMENTO - Data Affidamento
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'altro*-*data_fine_mandato') echo 'selected'; ?>
                                                                                    value="altro*-*data_fine_mandato">
                                                                                AFFIDAMENTO - Data Fine Mandato
                                                                            </option>
                                                                        </optgroup>
                                                                        <optgroup label="Dati DEBITORE">
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*nome') echo 'selected'; ?>
                                                                                    value="debitore*-*nome">DEBITORE -
                                                                                Nome
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*cognome') echo 'selected'; ?>
                                                                                    value="debitore*-*cognome">DEBITORE
                                                                                - Cognome (o Ragione Sociale)
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*codice_fiscale') echo 'selected'; ?>
                                                                                    value="debitore*-*codice_fiscale">
                                                                                DEBITORE - Codice Fiscale/P.IVA
                                                                            </option>
                                                                            <!--<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*partita_iva') echo 'selected'; ?> value="debitore*-*partita_iva">DEBITORE - Partita IVA</option>-->
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*sesso') echo 'selected'; ?>
                                                                                    value="debitore*-*sesso">DEBITORE -
                                                                                Sesso
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*data_nascita') echo 'selected'; ?>
                                                                                    value="debitore*-*data_nascita">
                                                                                DEBITORE - Data di Nascita
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*citta_nascita') echo 'selected'; ?>
                                                                                    value="debitore*-*citta_nascita">
                                                                                DEBITORE - Citta di Nascita
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*provincia_nascita') echo 'selected'; ?>
                                                                                    value="debitore*-*provincia_nascita">
                                                                                DEBITORE - Provincia di Nascita
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*nazione_nascita') echo 'selected'; ?>
                                                                                    value="debitore*-*nazione_nascita">
                                                                                DEBITORE - Nazione di Nascita
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*tipo_indirizzo') echo 'selected'; ?>
                                                                                    value="debitore*-*tipo_indirizzo">
                                                                                DEBITORE - Tipologia Indirizzo
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*indirizzo') echo 'selected'; ?>
                                                                                    value="debitore*-*indirizzo">
                                                                                DEBITORE - Indirizzo
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*cap') echo 'selected'; ?>
                                                                                    value="debitore*-*cap">DEBITORE -
                                                                                CAP
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*citta') echo 'selected'; ?>
                                                                                    value="debitore*-*citta">DEBITORE -
                                                                                Citta
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*provincia') echo 'selected'; ?>
                                                                                    value="debitore*-*provincia">
                                                                                DEBITORE - Provincia
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*nazione') echo 'selected'; ?>
                                                                                    value="debitore*-*nazione">DEBITORE
                                                                                - Nazione
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*indirizzo-telefono') echo 'selected'; ?>
                                                                                    value="debitore*-*indirizzo-telefono">
                                                                                DEBITORE - Telefono
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*indirizzo-cellulare') echo 'selected'; ?>
                                                                                    value="debitore*-*indirizzo-cellulare">
                                                                                DEBITORE - Cellulare
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*indirizzo-email') echo 'selected'; ?>
                                                                                    value="debitore*-*indirizzo-email">
                                                                                DEBITORE - Email
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*indirizzo-pec') echo 'selected'; ?>
                                                                                    value="debitore*-*indirizzo-pec">
                                                                                DEBITORE - PEC
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*tipo-recapito') echo 'selected'; ?>
                                                                                    value="debitore*-*tipo-recapito">
                                                                                DEBITORE - Tipo Recapito
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*codice_anagrafico_mandante') echo 'selected'; ?>
                                                                                    value="debitore*-*codice_anagrafico_mandante">
                                                                                DEBITORE - Codice Anagrafico Mandante
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'debitore*-*ragione_sociale_collegato') echo 'selected'; ?>
                                                                                    value="debitore*-*ragione_sociale_collegato">
                                                                                DEBITORE - Ragione Sociale Collegato
                                                                            </option>
                                                                        </optgroup>
                                                                        <optgroup
                                                                                label="Dati ANAGRAFICA COLLEGATA DEBITORE">
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*tipologia') echo 'selected'; ?>
                                                                                    value="collegato*-*tipologia">
                                                                                COLLEGATA DEBITORE - Tipologia Collegato
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*nome') echo 'selected'; ?>
                                                                                    value="collegato*-*nome">COLLEGATA
                                                                                DEBITORE - Nome
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*cognome') echo 'selected'; ?>
                                                                                    value="collegato*-*cognome">
                                                                                COLLEGATA DEBITORE - Cognome (o Ragione
                                                                                Sociale)
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*codice_fiscale') echo 'selected'; ?>
                                                                                    value="collegato*-*codice_fiscale">
                                                                                COLLEGATA DEBITORE - Codice
                                                                                Fiscale/P.IVA
                                                                            </option>
                                                                            <!--<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*partita_iva') echo 'selected'; ?> value="collegato*-*partita_iva">COLLEGATA DEBITORE - Partita IVA</option>-->
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*sesso') echo 'selected'; ?>
                                                                                    value="collegato*-*sesso">COLLEGATA
                                                                                DEBITORE - Sesso
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*data_nascita') echo 'selected'; ?>
                                                                                    value="collegato*-*data_nascita">
                                                                                COLLEGATA DEBITORE - Data di Nascita
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*citta_nascita') echo 'selected'; ?>
                                                                                    value="collegato*-*citta_nascita">
                                                                                COLLEGATA DEBITORE - Citta di Nascita
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*provincia_nascita') echo 'selected'; ?>
                                                                                    value="collegato*-*provincia_nascita">
                                                                                COLLEGATA DEBITORE - Provincia di
                                                                                Nascita
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*nazione_nascita') echo 'selected'; ?>
                                                                                    value="collegato*-*nazione_nascita">
                                                                                COLLEGATA DEBITORE - Nazione di Nascita
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*tipo_indirizzo') echo 'selected'; ?>
                                                                                    value="collegato*-*tipo_indirizzo">
                                                                                COLLEGATA DEBITORE - Tipologia Indirizzo
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*indirizzo') echo 'selected'; ?>
                                                                                    value="collegato*-*indirizzo">
                                                                                COLLEGATA DEBITORE - Indirizzo
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*cap') echo 'selected'; ?>
                                                                                    value="collegato*-*cap">COLLEGATA
                                                                                DEBITORE - CAP
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*citta') echo 'selected'; ?>
                                                                                    value="collegato*-*citta">COLLEGATA
                                                                                DEBITORE - Citta
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*provincia') echo 'selected'; ?>
                                                                                    value="collegato*-*provincia">
                                                                                COLLEGATA DEBITORE - Provincia
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*nazione') echo 'selected'; ?>
                                                                                    value="collegato*-*nazione">
                                                                                COLLEGATA DEBITORE - Nazione
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*indirizzo-telefono') echo 'selected'; ?>
                                                                                    value="collegato*-*indirizzo-telefono">
                                                                                COLLEGATA DEBITORE - Telefono
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*indirizzo-email') echo 'selected'; ?>
                                                                                    value="collegato*-*indirizzo-email">
                                                                                COLLEGATA DEBITORE - Email
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*tipo-recapito') echo 'selected'; ?>
                                                                                    value="collegato*-*tipo-recapito">
                                                                                COLLEGATA DEBITORE - Tipo Recapito
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*codice_anagrafico_mandante') echo 'selected'; ?>
                                                                                    value="collegato*-*codice_anagrafico_mandante">
                                                                                COLLEGATA DEBITORE - Codice Anagrafico
                                                                                Mandante
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'collegato*-*ragione_sociale_collegato') echo 'selected'; ?>
                                                                                    value="collegato*-*ragione_sociale_collegato">
                                                                                COLLEGATA DEBITORE - Ragione Sociale
                                                                                Collegato
                                                                            </option>
                                                                        </optgroup>
                                                                        <optgroup label="Dati COLLEGATO PRATICA">
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*tipologia') echo 'selected'; ?>
                                                                                    value="garante*-*tipologia">
                                                                                COLLEGATO PRATICA - Tipologia Collegato
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*nome') echo 'selected'; ?>
                                                                                    value="garante*-*nome">COLLEGATO
                                                                                PRATICA - Nome
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*cognome') echo 'selected'; ?>
                                                                                    value="garante*-*cognome">COLLEGATO
                                                                                PRATICA - Cognome (o Ragione Sociale)
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*codice_fiscale') echo 'selected'; ?>
                                                                                    value="garante*-*codice_fiscale">
                                                                                COLLEGATO PRATICA - Codice Fiscale/P.IVA
                                                                            </option>
                                                                            <!--<option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*partita_iva') echo 'selected'; ?> value="garante*-*partita_iva">GARANTE - Partita IVA</option>-->
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*sesso') echo 'selected'; ?>
                                                                                    value="garante*-*sesso">COLLEGATO
                                                                                PRATICA - Sesso
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*data_nascita') echo 'selected'; ?>
                                                                                    value="garante*-*data_nascita">
                                                                                COLLEGATO PRATICA - Data di Nascita
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*citta_nascita') echo 'selected'; ?>
                                                                                    value="garante*-*citta_nascita">
                                                                                COLLEGATO PRATICA - Citta di Nascita
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*provincia_nascita') echo 'selected'; ?>
                                                                                    value="garante*-*provincia_nascita">
                                                                                COLLEGATO PRATICA - Provincia di Nascita
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*nazione_nascita') echo 'selected'; ?>
                                                                                    value="garante*-*nazione_nascita">
                                                                                COLLEGATO PRATICA - Nazione di Nascita
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*tipo_indirizzo') echo 'selected'; ?>
                                                                                    value="garante*-*tipo_indirizzo">
                                                                                COLLEGATO PRATICA - Tipologia Indirizzo
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*indirizzo') echo 'selected'; ?>
                                                                                    value="garante*-*indirizzo">
                                                                                COLLEGATO PRATICA - Indirizzo
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*cap') echo 'selected'; ?>
                                                                                    value="garante*-*cap">COLLEGATO
                                                                                PRATICA - CAP
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*citta') echo 'selected'; ?>
                                                                                    value="garante*-*citta">COLLEGATO
                                                                                PRATICA - Citta
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*provincia') echo 'selected'; ?>
                                                                                    value="garante*-*provincia">
                                                                                COLLEGATO PRATICA - Provincia
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*nazione') echo 'selected'; ?>
                                                                                    value="garante*-*nazione">COLLEGATO
                                                                                PRATICA - Nazione
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*indirizzo-telefono') echo 'selected'; ?>
                                                                                    value="garante*-*indirizzo-telefono">
                                                                                COLLEGATO PRATICA - Telefono
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*indirizzo-email') echo 'selected'; ?>
                                                                                    value="garante*-*indirizzo-email">
                                                                                COLLEGATO PRATICA - Email
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*tipo-recapito') echo 'selected'; ?>
                                                                                    value="garante*-*tipo-recapito">
                                                                                COLLEGATO PRATICA - Tipo Recapito
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*codice_anagrafico_mandante') echo 'selected'; ?>
                                                                                    value="garante*-*codice_anagrafico_mandante">
                                                                                COLLEGATO PRATICA - Codice Anagrafico
                                                                                Mandante
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'garante*-*ragione_sociale_collegato') echo 'selected'; ?>
                                                                                    value="garante*-*ragione_sociale_collegato">
                                                                                COLLEGATO PRATICA - Ragione Sociale
                                                                                Collegato
                                                                            </option>
                                                                        </optgroup>
                                                                        <optgroup label="Dati CREDITORE">
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'creditore*-*descrizione') echo 'selected'; ?>
                                                                                    value="creditore*-*descrizione">
                                                                                CREDITORE - Ragione Sociale
                                                                            </option>
                                                                        </optgroup>
                                                                        <optgroup label="Dati PRATICA">
                                                                            <!-- <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*affidato_capitale') echo 'selected'; ?>
                                                                                    value="pratica*-*affidato_capitale">
                                                                                PRATICA - Affidato Capitale
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*affidato_spese') echo 'selected'; ?>
                                                                                    value="pratica*-*affidato_spese">
                                                                                PRATICA - Affidato Spese
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*affidato_interessi') echo 'selected'; ?>
                                                                                    value="pratica*-*affidato_interessi">
                                                                                PRATICA - Affidato Interessi
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*affidato_1') echo 'selected'; ?>
                                                                                    value="pratica*-*affidato_1">PRATICA
                                                                                - Affidato 1
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*affidato_2') echo 'selected'; ?>
                                                                                    value="pratica*-*affidato_2">PRATICA
                                                                                - Affidato 2
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*affidato_3') echo 'selected'; ?>
                                                                                    value="pratica*-*affidato_3">PRATICA
                                                                                - Affidato 3
                                                                            </option>
                                                                            -->
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*competenze_oneri_recupero') echo 'selected'; ?>
                                                                                    value="pratica*-*competenze_oneri_recupero">
                                                                                PRATICA - Competenze Oneri Recupero
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*competenze_spesse_incasso') echo 'selected'; ?>
                                                                                    value="pratica*-*competenze_spesse_incasso">
                                                                                PRATICA - Competenze Spese Incasso
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*cash_balance') echo 'selected'; ?>
                                                                                    value="pratica*-*cash_balance">
                                                                                PRATICA - Cash Balance
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*riferimento_mandante_1') echo 'selected'; ?>
                                                                                    value="pratica*-*riferimento_mandante_1">
                                                                                PRATICA - Riferimento Mandante 1
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*riferimento_mandante_2') echo 'selected'; ?>
                                                                                    value="pratica*-*riferimento_mandante_2">
                                                                                PRATICA - Riferimento Mandante 2
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*riferimento_mandante_3') echo 'selected'; ?>
                                                                                    value="pratica*-*riferimento_mandante_3">
                                                                                PRATICA - Riferimento Mandante 3
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*annotazioni') echo 'selected'; ?>
                                                                                    value="pratica*-*annotazioni">
                                                                                PRATICA - Annotazioni
                                                                            </option>
                                                                            <!--  <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*data_inizio_interessi') echo 'selected'; ?>
                                                                                    value="pratica*-*data_inizio_interessi">
                                                                                PRATICA - Data Inizio Interessi
                                                                            </option>
                                                                            -->
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*cp_valuta') echo 'selected'; ?>
                                                                                    value="pratica*-*cp_valuta">PRATICA
                                                                                - Personalizzato Valuta
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*cp_testo') echo 'selected'; ?>
                                                                                    value="pratica*-*cp_testo">PRATICA -
                                                                                Personalizzato Testo
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*cp_data') echo 'selected'; ?>
                                                                                    value="pratica*-*cp_data">PRATICA -
                                                                                Personalizzato Data
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*id_filiale_origine') echo 'selected'; ?>
                                                                                    value="pratica*-*id_filiale_origine">
                                                                                PRATICA - ID Filiale
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*gbv') echo 'selected'; ?>
                                                                                    value="pratica*-*gbv">
                                                                                PRATICA - gbv
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'pratica*-*prezzo_acquisto') echo 'selected'; ?>
                                                                                    value="pratica*-*prezzo_acquisto">
                                                                                PRATICA - PREZZO D'ACQUISTO
                                                                            </option>
                                                                        </optgroup>
                                                                        <optgroup label="Dati TITOLO">
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*numero') echo 'selected'; ?>
                                                                                    value="titolo*-*numero">TITOLO -
                                                                                Numero
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*data_affidamento') echo 'selected'; ?>
                                                                                    value="titolo*-*data_affidamento">
                                                                                TITOLO - Data Affidamento
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*descrizione') echo 'selected'; ?>
                                                                                    value="titolo*-*descrizione">TITOLO
                                                                                - Descrizione
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*data_emissione') echo 'selected'; ?>
                                                                                    value="titolo*-*data_emissione">
                                                                                TITOLO - Data Emissione
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*data_scadenza') echo 'selected'; ?>
                                                                                    value="titolo*-*data_scadenza">
                                                                                TITOLO - Data Scadenza
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*natura_credito') echo 'selected'; ?>
                                                                                    value="titolo*-*natura_credito">
                                                                                TITOLO - Natura Credito
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*importo_iniziale') echo 'selected'; ?>
                                                                                    value="titolo*-*importo_iniziale">
                                                                                TITOLO - Importo Iniziale
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*acconto') echo 'selected'; ?>
                                                                                    value="titolo*-*acconto">TITOLO -
                                                                                Acconto
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*capitale') echo 'selected'; ?>
                                                                                    value="titolo*-*capitale">TITOLO -
                                                                                Capitale
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*spese') echo 'selected'; ?>
                                                                                    value="titolo*-*spese">TITOLO -
                                                                                Spese
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*interessi') echo 'selected'; ?>
                                                                                    value="titolo*-*interessi">TITOLO -
                                                                                Interessi
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*affidato_1') echo 'selected'; ?>
                                                                                    value="titolo*-*affidato_1">TITOLO -
                                                                                Affidato 1
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*affidato_2') echo 'selected'; ?>
                                                                                    value="titolo*-*affidato_2">TITOLO -
                                                                                Affidato 2
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*affidato_3') echo 'selected'; ?>
                                                                                    value="titolo*-*affidato_3">TITOLO -
                                                                                Affidato 3
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*oneri_studio') echo 'selected'; ?>
                                                                                    value="titolo*-*oneri_studio">TITOLO
                                                                                - Oneri Studio
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*spese_incasso') echo 'selected'; ?>
                                                                                    value="titolo*-*spese_incasso">
                                                                                TITOLO - Spese Incasso
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*cp_valuta') echo 'selected'; ?>
                                                                                    value="titolo*-*cp_valuta">TITOLO -
                                                                                Personalizzato Valuta
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*cp_testo') echo 'selected'; ?>
                                                                                    value="titolo*-*cp_testo">TITOLO -
                                                                                Personalizzato Testo
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*cp_data') echo 'selected'; ?>
                                                                                    value="titolo*-*cp_data">TITOLO -
                                                                                Personalizzato Data
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*recapito_insoluto') echo 'selected'; ?>
                                                                                    value="titolo*-*recapito_insoluto">
                                                                                TITOLO - Recapito Insoluto
                                                                            </option>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*note') echo 'selected'; ?>
                                                                                    value="titolo*-*note">TITOLO - Note
                                                                            </option>
                                                                            <!-- <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_campi[$i] == 'titolo*-*quota_natura') echo 'selected'; ?>
                                                                                    value="titolo*-*quota_natura">TITOLO
                                                                                - QUOTA NATURA
                                                                            </option> -->
                                                                        </optgroup>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div>
                                                                    <select name="funzione_pers[]"
                                                                            class="form-control select2me funzione_pers">
                                                                        <option <?php if (!isset($_GET['config']) || $_GET['config'] == '') echo 'selected'; ?>></option>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_fnc[$i - 300] == 'RIUTILIZZA') echo 'selected'; ?>
                                                                                value="RIUTILIZZA">RIUTILIZZA
                                                                        </option>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_fnc[$i - 300] == 'SOMMA') echo 'selected'; ?>
                                                                                value="SOMMA">SOMMA
                                                                        </option>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_fnc[$i - 300] == 'CONCATENA') echo 'selected'; ?>
                                                                                value="CONCATENA">CONCATENA
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2" style="line-height:44px;">
                                                                <div>
                                                                    <input name="colonne_pers[]" type="text"
                                                                            value="<?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_val[$i - 300] != '') echo urldecode($configurazione_val[$i - 300]); ?>"
                                                                            placeholder="Colonne da concatenare**"
                                                                            class="form-control colonne_pers">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2" style="line-height:44px;">
                                                                <?php
                                                                $mostra_posizione = false;
                                                                $mostra_posizione_indirizzo = false;
                                                                $mostra_rec_predefiniti = false;
                                                                $mostra_tipologie_indirizzo = false;
                                                                $mostra_tipologie_collegato = false;

                                                                if (isset($_GET['config']) && $_GET['config'] != '') {
                                                                    if ($configurazione_campi[$i] == 'collegato*-*cognome' || $configurazione_campi[$i] == 'garante*-*cognome') {
                                                                        $mostra_tipologie_collegato = true;
                                                                    }
                                                                    if ($configurazione_campi[$i] == 'debitore*-*indirizzo' || $configurazione_campi[$i] == 'garante*-*indirizzo' || $configurazione_campi[$i] == 'collegato*-*indirizzo') {
                                                                        $mostra_tipologie_indirizzo = true;
                                                                        $mostra_rec_predefiniti = true;
                                                                    }
                                                                    if ($configurazione_campi[$i] == 'debitore*-*indirizzo-telefono' || $configurazione_campi[$i] == 'debitore*-*indirizzo-cellulare' || $configurazione_campi[$i] == 'debitore*-*indirizzo-email' || $configurazione_campi[$i] == 'debitore*-*indirizzo-pec' || $configurazione_campi[$i] == 'garante*-*indirizzo-telefono' || $configurazione_campi[$i] == 'garante*-*indirizzo-email' || $configurazione_campi[$i] == 'collegato*-*indirizzo-telefono' || $configurazione_campi[$i] == 'collegato*-*indirizzo-email') {
                                                                        $mostra_rec_predefiniti = true;
                                                                    }

                                                                }

                                                                if (strpos($configurazione_campi[$i], 'collegato*-*') !== FALSE || strpos($configurazione_campi[$i], 'garante*-*') !== FALSE || $configurazione_campi[$i] == 'debitore*-*indirizzo' || $configurazione_campi[$i] == 'debitore*-*cap' || $configurazione_campi[$i] == 'debitore*-*citta' || $configurazione_campi[$i] == 'debitore*-*provincia' || $configurazione_campi[$i] == 'debitore*-*nazione' || $configurazione_campi[$i] == 'debitore*-*indirizzo-email' || $configurazione_campi[$i] == 'debitore*-*indirizzo-pec' || $configurazione_campi[$i] == 'debitore*-*indirizzo-telefono' || $configurazione_campi[$i] == 'debitore*-*indirizzo-cellulare' || strpos($configurazione_campi[$i], '*-*tipo_indirizzo') > 0 || strpos($configurazione_campi[$i], '*-*tipo-recapito') > 0) {
                                                                    $mostra_posizione = true;
                                                                }

                                                                if ($configurazione_campi[$i] == 'debitore*-*tipo_indirizzo' || $configurazione_campi[$i] == 'debitore*-*indirizzo' || $configurazione_campi[$i] == 'debitore*-*cap' || $configurazione_campi[$i] == 'debitore*-*citta' || $configurazione_campi[$i] == 'debitore*-*provincia' || $configurazione_campi[$i] == 'debitore*-*nazione' || $configurazione_campi[$i] == 'collegato*-*tipo_indirizzo' || $configurazione_campi[$i] == 'collegato*-*indirizzo' || $configurazione_campi[$i] == 'collegato*-*cap' || $configurazione_campi[$i] == 'collegato*-*citta' || $configurazione_campi[$i] == 'collegato*-*provincia' || $configurazione_campi[$i] == 'collegato*-*nazione' || $configurazione_campi[$i] == 'garante*-*tipo_indirizzo' || $configurazione_campi[$i] == 'garante*-*indirizzo' || $configurazione_campi[$i] == 'garante*-*cap' || $configurazione_campi[$i] == 'garante*-*citta' || $configurazione_campi[$i] == 'garante*-*provincia' || $configurazione_campi[$i] == 'garante*-*nazione') {
                                                                    $mostra_posizione_indirizzo = true;
                                                                }

                                                                $configurazione_rec_predefinito = explode(';', $configurazione['recapiti_predefiniti']);
                                                                $configurazione_tipo_indirizzo = explode(';', $configurazione['tipologie_indirizzo']);
                                                                $configurazione_tipo_collegato = explode(';', $configurazione['tipologie_collegato']);
                                                                ?>

                                                                <div style="width: 40px; float: left; margin-right: 10px;"
                                                                        class="extra-field-<?php echo $i ?> field_position-<?php echo $i ?> <?php if (!$mostra_posizione) echo 'hidden'; ?>">
                                                                    <input name="posizione[]" data-html="true"
                                                                            data-toggle="tooltip" title="Posizione"
                                                                            class="form-control posizioni"
                                                                            value="<?php if (isset($_GET['config']) && $_GET['config'] != '') echo $configurazione_posizioni[$i]; else echo '0'; ?>"
                                                                            placeholder="POS">
                                                                </div>
                                                                <div style="width: 40px; float: left; margin-right: 10px;"
                                                                        class="extra-field-<?php echo $i ?> field_address_position-<?php echo $i ?> <?php if (!$mostra_posizione_indirizzo) echo 'hidden'; ?>">
                                                                    <input name="posizione_indirizzo[]" data-html="true"
                                                                            data-toggle="tooltip"
                                                                            title="Posizione Indirizzo"
                                                                            class="form-control posizioni_indirizzo"
                                                                            value="<?php if (isset($_GET['config']) && $_GET['config'] != '') echo $configurazione_posizioni_indirizzo[$i]; ?>"
                                                                            placeholder="I.POS">
                                                                </div>
                                                                <div style="float: left; margin-right: 10px;"
                                                                        data-html="true" data-toggle="tooltip"
                                                                        title="Usa per invio"
                                                                        class="extra-field-<?php echo $i ?> field_predefinito-<?php echo $i ?> <?php if (!$mostra_rec_predefiniti) echo 'hidden'; ?>">
                                                                    <input name="rec_predefinito[]" type="checkbox"
                                                                            class="make-switch recapiti_predefiniti"
                                                                            value="1" data-on-color="success"
                                                                            data-off-color="warning"
                                                                            data-on-text="&nbsp;<i class='fa fa-check-circle-o'></i>&nbsp;"
                                                                            data-off-text="&nbsp;<i class='fa fa-circle-o'></i>&nbsp;" <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_rec_predefinito[$i] == 1) echo 'checked'; ?>
                                                                            data-value="<?php echo $i ?>"><input
                                                                            id="checkHidden<?php echo $i ?>"
                                                                            type="hidden" value="0"
                                                                            name="rec_predefinito[]">
                                                                </div>
                                                                <div style="float: left; width: 120px; margin-right: 10px;"
                                                                        data-html="true" data-toggle="tooltip"
                                                                        title="Tipologia Indirizzo"
                                                                        class="extra-field-<?php echo $i ?> field_address_type-<?php echo $i ?> <?php if (!$mostra_tipologie_indirizzo) echo 'hidden'; ?>">
                                                                    <select name="address_type[]"
                                                                            class="form-control select2me tipologie_indirizzo">
                                                                        <option></option>
                                                                        <?php

                                                                        $tipologie = recuperaTipologieIndirizzo();

                                                                        foreach ($tipologie as $tipologia) {
                                                                            ?>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_tipo_indirizzo[$i] == $tipologia['id_tipo_indirizzo']) echo 'selected'; ?>
                                                                                    value="<?php echo $tipologia['id_tipo_indirizzo']; ?>"><?php echo $tipologia['tipo']; ?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div style="float: left; width: 230px; margin-right: 10px;"
                                                                        data-html="true" data-toggle="tooltip"
                                                                        title="Tipologia Collegato"
                                                                        class="extra-field-<?php echo $i ?> field_linked_type-<?php echo $i ?> <?php if (!$mostra_tipologie_collegato) echo 'hidden'; ?>">
                                                                    <select name="linked_type[]"
                                                                            class="form-control select2me tipologie_collegato">
                                                                        <option></option>
                                                                        <?php
                                                                        $tipologie = recuperaTipologieCollegato();

                                                                        foreach ($tipologie as $tipologia) {
                                                                            ?>
                                                                            <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione_tipo_collegato[$i] == $tipologia['id']) echo 'selected'; ?>
                                                                                    value="<?php echo $tipologia['id']; ?>"><?php echo $tipologia['descrizione']; ?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12" style="line-height: 10px;">&nbsp;
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                        <div class="col-md-2" style="line-height:33px;">
                                                            &nbsp;
                                                        </div>
                                                        <div class="col-md-4">
                                                            &nbsp;
                                                        </div>
                                                        <div class="col-md-2">
                                                            &nbsp;
                                                        </div>
                                                        <div class="col-md-4" style="line-height:44px;">
                                                            <div>
                                                                <small>** Colonne da concatenare (separate da ; e
                                                                    anticipate da - nel caso di una sottrazione)
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>

                                                        <?php
                                                        $mandanteAzioni =  isset($_GET['config']) && $_GET['config'] != '' ? $configurazione['id_mandante'] : '0';

                                                        $azioni = recuperaAzioni($mandanteAzioni);
                                                        ?>
                                                    <div class="<?php if(count($azioni)==0) echo 'hidden'; ?>">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <h3>AZIONI POST ESECUZIONE</h3>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <select name="azione_post_exec" id="azione_post_exec"
                                                                        class="form-control select2me">
                                                                    <option></option>
                                                                    <?php

                                                                    foreach ($azioni as $azione) {
                                                                        ?>
                                                                        <option <?php if (isset($_GET['config']) && $_GET['config'] != '' && $configurazione['azione_post_exec'] == $azione['id']) echo 'selected'; ?>
                                                                                value="<?php echo $azione['id']; ?>"><?php echo $azione['nome']; ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row" style="margin-bottom: 20px"></div>

                                                    <div>
                                                        <div class="form-actions">
                                                            <div class="row">
                                                                <div class="col-md-offset-1 col-md-3">
                                                                    <button id="submit_button" type="submit"
                                                                            onclick="if(verificaFile()){ if($('#nome_lotto_mandante').val() != '' ) $('#loader0').show();} else
                                                                            { alert(' Verificare l\'estensione del file '); return false;} "
                                                                            class="btn btn-success"><i
                                                                                class="fa fa-check"></i> Salva
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
                                                                                onClick="modifica_configurazione(<?php echo $_GET['config'] ?>);"
                                                                                type="button" class="btn btn-info"><i
                                                                                    class="fa fa-download"></i> Modifica
                                                                            Configurazione
                                                                        </button>
                                                                        <button style="display:inline-block; margin-left: 3px"
                                                                                onClick="elimina_configurazione(<?php echo $_GET['config'] ?>);"
                                                                                type="button" class="btn btn-danger"><i
                                                                                    class="fa fa-trash-o"></i> Elimina
                                                                            Configurazione
                                                                        </button>
                                                                        <?php
                                                                    }
                                                                    ?>

                                                                    <button style="display:inline-block" type="reset"
                                                                            class="btn btn-warning"><i
                                                                                class="fa fa-times-circle"></i>
                                                                        Annulla Modifiche
                                                                    </button>
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
<div id="template-select-row" style="display: none;">
    <span class="line"
            data-line="">   
        <div class="col-md-2" style="line-height:33px;">
            COLONNA <span class="text-i"></span>
        </div>
        <div class="col-md-4">
            <div>
                <select name="campo[]"
                        data-count=""
                        class="form-control MoreSelect2me no-init campi"
                        onChange="displayOptionalFields($(this),$(this).attr('data-count'))">
                    <option selected=""></option>                                                                     
                                
                    <optgroup label="Affidamento">
                        <option value="altro*-*data_affidamento">AFFIDAMENTO - Data Affidamento</option>
                        <option value="altro*-*data_fine_mandato">AFFIDAMENTO - Data Fine Mandato</option>
                    </optgroup>                                                                            
                    <optgroup label="Dati DEBITORE">
                        <option value="debitore*-*nome">DEBITORE - Nome</option>
                        <option value="debitore*-*cognome">DEBITORE - Cognome (o Ragione Sociale)</option>
                        <option value="debitore*-*codice_fiscale">DEBITORE - Codice Fiscale/P.IVA</option>
                        <!--<option  value="debitore*-*partita_iva">DEBITORE - Partita IVA</option>-->
                        <option value="debitore*-*sesso">DEBITORE - Sesso</option>
                        <option value="debitore*-*data_nascita">DEBITORE - Data di Nascita</option>
                        <option value="debitore*-*citta_nascita">DEBITORE - Citta di Nascita</option>
                        <option value="debitore*-*provincia_nascita">DEBITORE - Provincia di Nascita</option>
                        <option value="debitore*-*nazione_nascita">DEBITORE - Nazione di Nascita</option>
                        <option value="debitore*-*tipo_indirizzo">DEBITORE - Tipologia Indirizzo</option>
                        <option value="debitore*-*indirizzo">DEBITORE - Indirizzo</option>
                        <option value="debitore*-*cap">DEBITORE - CAP</option>
                        <option value="debitore*-*citta">DEBITORE - Citta</option>
                        <option value="debitore*-*provincia">DEBITORE - Provincia</option>
                        <option value="debitore*-*nazione">DEBITORE - Nazione</option>
                        <option value="debitore*-*indirizzo-telefono">DEBITORE - Telefono</option>
                        <option value="debitore*-*indirizzo-cellulare">DEBITORE - Cellulare</option>
                        <option value="debitore*-*indirizzo-email">DEBITORE - Email</option>
                        <option value="debitore*-*indirizzo-pec">DEBITORE - PEC</option>
                        <option value="debitore*-*tipo-recapito">DEBITORE - Tipo Recapito</option>
                        <option value="debitore*-*codice_anagrafico_mandante">DEBITORE - Codice Anagrafico Mandante</option>
                        <option value="debitore*-*ragione_sociale_collegato">DEBITORE - Ragione Sociale Collegato</option>
                    </optgroup>                                                                            
                    <optgroup label="Dati ANAGRAFICA COLLEGATA DEBITORE">
                        <option value="collegato*-*tipologia">COLLEGATA DEBITORE - Tipologia Collegato</option>
                        <option value="collegato*-*nome">COLLEGATA DEBITORE - Nome</option>
                        <option value="collegato*-*cognome">COLLEGATA DEBITORE - Cognome (o Ragione Sociale)</option>
                        <option value="collegato*-*codice_fiscale">COLLEGATA DEBITORE - Codice Fiscale/P.IVA</option>
                        <!--<option  value="collegato*-*partita_iva">COLLEGATA DEBITORE - Partita IVA</option>-->
                        <option value="collegato*-*sesso">COLLEGATA DEBITORE - Sesso</option>
                        <option value="collegato*-*data_nascita">COLLEGATA DEBITORE - Data di Nascita</option>
                        <option value="collegato*-*citta_nascita">COLLEGATA DEBITORE - Citta di Nascita</option>
                        <option value="collegato*-*provincia_nascita">COLLEGATA DEBITORE - Provincia di Nascita</option>
                        <option value="collegato*-*nazione_nascita">COLLEGATA DEBITORE - Nazione di Nascita</option>
                        <option value="collegato*-*tipo_indirizzo">COLLEGATA DEBITORE - Tipologia Indirizzo</option>
                        <option value="collegato*-*indirizzo">COLLEGATA DEBITORE - Indirizzo</option>
                        <option value="collegato*-*cap">COLLEGATA DEBITORE - CAP</option>
                        <option value="collegato*-*citta">COLLEGATA DEBITORE - Citta</option>
                        <option value="collegato*-*provincia">COLLEGATA DEBITORE - Provincia</option>
                        <option value="collegato*-*nazione">COLLEGATA DEBITORE - Nazione</option>
                        <option value="collegato*-*indirizzo-telefono">COLLEGATA DEBITORE - Telefono</option>
                        <option value="collegato*-*indirizzo-email">COLLEGATA DEBITORE - Email</option>
                        <option value="collegato*-*tipo-recapito">COLLEGATA DEBITORE - Tipo Recapito</option>
                        <option value="collegato*-*codice_anagrafico_mandante">COLLEGATA DEBITORE - Codice Anagrafico Mandante</option>
                        <option value="collegato*-*ragione_sociale_collegato">COLLEGATA DEBITORE - Ragione Sociale Collegato</option>
                    </optgroup>                                                                            
                    <optgroup label="Dati COLLEGATO PRATICA">
                        <option value="garante*-*tipologia">COLLEGATO PRATICA - Tipologia Collegato</option>
                        <option value="garante*-*nome">COLLEGATO PRATICA - Nome</option>
                        <option value="garante*-*cognome">COLLEGATO PRATICA - Cognome (o Ragione Sociale)</option>
                        <option value="garante*-*codice_fiscale">COLLEGATO PRATICA - Codice Fiscale/P.IVA</option>
                        <!--<option  value="garante*-*partita_iva">GARANTE - Partita IVA</option>-->
                        <option value="garante*-*sesso">COLLEGATO PRATICA - Sesso</option>
                        <option value="garante*-*data_nascita">COLLEGATO PRATICA - Data di Nascita</option>
                        <option value="garante*-*citta_nascita">COLLEGATO PRATICA - Citta di Nascita</option>
                        <option value="garante*-*provincia_nascita">COLLEGATO PRATICA - Provincia di Nascita</option>
                        <option value="garante*-*nazione_nascita">COLLEGATO PRATICA - Nazione di Nascita</option>
                        <option value="garante*-*tipo_indirizzo">COLLEGATO PRATICA - Tipologia Indirizzo</option>
                        <option value="garante*-*indirizzo">COLLEGATO PRATICA - Indirizzo</option>
                        <option value="garante*-*cap">COLLEGATO PRATICA - CAP</option>
                        <option value="garante*-*citta">COLLEGATO PRATICA - Citta</option>
                        <option value="garante*-*provincia">COLLEGATO PRATICA - Provincia</option>
                        <option value="garante*-*nazione">COLLEGATO PRATICA - Nazione</option>
                        <option value="garante*-*indirizzo-telefono">COLLEGATO PRATICA - Telefono</option>
                        <option value="garante*-*indirizzo-email">COLLEGATO PRATICA - Email</option>
                        <option value="garante*-*tipo-recapito">COLLEGATO PRATICA - Tipo Recapito</option>
                        <option value="garante*-*codice_anagrafico_mandante">COLLEGATO PRATICA - Codice Anagrafico Mandante</option>
                        <option value="garante*-*ragione_sociale_collegato">COLLEGATO PRATICA - Ragione Sociale Collegato</option>
                    </optgroup>                                                                            
                    <optgroup label="Dati CREDITORE">
                            <option value="creditore*-*descrizione">CREDITORE - Ragione Sociale</option>
                    </optgroup>                                                                            
                    <optgroup label="Dati PRATICA">
                        <!-- <option  value="pratica*-*affidato_capitale">PRATICA - Affidato Capitale</option>
                                    <option  value="pratica*-*affidato_spese">PRATICA - Affidato Spese</option>
                                    <option  value="pratica*-*affidato_interessi">PRATICA - Affidato Interessi</option>
                                    <option  value="pratica*-*affidato_1">PRATICA - Affidato 1</option>
                                    <option  value="pratica*-*affidato_2">PRATICA - Affidato 2</option>
                                    <option  value="pratica*-*affidato_3">PRATICA - Affidato 3</option> -->
                        <option value="pratica*-*competenze_oneri_recupero">PRATICA - Competenze Oneri Recupero</option>
                        <option value="pratica*-*competenze_spesse_incasso">PRATICA - Competenze Spese Incasso</option>
                        <option value="pratica*-*cash_balance">PRATICA - Cash Balance</option>
                        <option value="pratica*-*riferimento_mandante_1">PRATICA - Riferimento Mandante 1</option>
                        <option value="pratica*-*riferimento_mandante_2">PRATICA - Riferimento Mandante 2</option><option value="pratica*-*riferimento_mandante_3">PRATICA - Riferimento Mandante 3</option>
                        <option value="pratica*-*annotazioni">PRATICA - Annotazioni</option>
                        <!--   <option  value="pratica*-*data_inizio_interessi">PRATICA - Data Inizio Interessi</option> -->
                        <option value="pratica*-*cp_valuta">PRATICA - Personalizzato Valuta</option>
                        <option value="pratica*-*cp_testo">PRATICA - Personalizzato Testo</option>
                        <option value="pratica*-*cp_data">PRATICA - Personalizzato Data</option>
                        <option value="pratica*-*id_filiale_origine">PRATICA - ID Filiale</option>
                        <option value="pratica*-*gbv">PRATICA - gbv</option>
                        <option value="pratica*-*prezzo_acquisto">PRATICA - PREZZO D'ACQUISTO</option>
                    </optgroup>                                                                            
                    <optgroup label="Dati TITOLO">
                        <option value="titolo*-*numero">TITOLO - Numero</option>
                        <option value="titolo*-*data_affidamento">TITOLO - Data Affidamento</option>
                        <option value="titolo*-*descrizione">TITOLO - Descrizione</option>
                        <option value="titolo*-*data_emissione">TITOLO - Data Emissione</option>
                        <option value="titolo*-*data_scadenza">TITOLO - Data Scadenza</option>
                        <option value="titolo*-*natura_credito">TITOLO - Natura Credito</option>
                        <option value="titolo*-*importo_iniziale">TITOLO - Importo Iniziale</option>
                        <option value="titolo*-*acconto">TITOLO - Acconto</option>
                        <option value="titolo*-*capitale">TITOLO - Capitale</option>
                        <option value="titolo*-*spese">TITOLO - Spese</option>
                        <option value="titolo*-*interessi">TITOLO - Interessi</option>
                        <option value="titolo*-*affidato_1">TITOLO - Affidato 1</option>
                        <option value="titolo*-*affidato_2">TITOLO - Affidato 2</option>
                        <option value="titolo*-*affidato_3">TITOLO - Affidato 3</option>
                        <option value="titolo*-*oneri_studio">TITOLO - Oneri Studio</option>
                        <option value="titolo*-*spese_incasso">TITOLO - Spese Incasso</option>
                        <option value="titolo*-*cp_valuta">TITOLO - Personalizzato Valuta</option>
                        <option value="titolo*-*cp_testo">TITOLO - Personalizzato Testo</option>
                        <option value="titolo*-*cp_data">TITOLO - Personalizzato Data</option>
                        <option value="titolo*-*recapito_insoluto">TITOLO - Recapito Insoluto</option>
                        <option value="titolo*-*note">TITOLO - Note</option>
                        <!-- <option  value="titolo*-*quota_natura">TITOLO - QUOTA NATURA</option> -->
                    </optgroup>                                                                        
                </select>
            </div>
        </div>

        <div class="col-md-4" style="height: 35px;">

            <div style="width: 40px; float: left; margin-right: 10px;" 
                    id="pos"                    
                    class="hidden">
                <input name="posizione[]" 
                    data-html="true" 
                    data-toggle="tooltip" 
                    title="Posizione" 
                    class="form-control posizioni" 
                    value="0" 
                    placeholder="POS">
            </div>
            <div style="width: 40px; float: left; margin-right: 10px;" 
                    id="i_pos" 
                    class="hidden">
                <input name="posizione_indirizzo[]" 
                    data-html="true" 
                    data-toggle="tooltip" 
                    title="Posizione Indirizzo" 
                    class="form-control posizioni_indirizzo" 
                    value="" 
                    placeholder="I.POS">
            </div>
            <div style="float: left; margin-right: 10px;" 
                    id="usa_invio" 
                    data-html="true" 
                    data-toggle="tooltip" 
                    title="Usa per Invio" 
                    class="hidden">
                <input name="rec_predefinito[]" data-html="true"
                    data-toggle="tooltip"
                    title="Usa Per Invio"
                    type="checkbox"
                    class="make-switch Moremake-switch recapiti_predefiniti"
                    value="1"
                    data-on-color="success"
                    data-off-color="warning"
                    data-on-text="&nbsp;<i class='fa fa-check-circle-o'></i>&nbsp;"
                    data-off-text="&nbsp;<i class='fa fa-circle-o'></i>&nbsp;">
                <input            
                    type="hidden" 
                    value="0"
                    name="rec_predefinito[]">
            </div>
            <div style="float: left; width: 120px; margin-right: 10px;" 
                    id="address_type" 
                    data-html="true" 
                    data-toggle="tooltip" 
                    title="Tipologia Indirizzo" 
                    class="hidden">
                <select name="address_type[]" 
                        data-html="true" 
                        data-toggle="tooltip" 
                        title="Tipologia Indirizzo" 
                        class="form-control MoreSelect2me no-init tipologie_indirizzo">
                    <option></option>
                    <option value="1">ATTIVITA'</option>
                    <option value="8">DOMICILIO</option>
                    <option value="5">INDIRIZZO INVIO FATTURAZIONE</option>
                    <option value="6">NON SPECIFICATO</option>
                    <option value="7">POSTO DI LAVORO</option>
                    <option value="4">RESIDENZA</option>
                    <option value="10">SEDE LEGALE</option>
                    <option value="9">SEDE OPERATIVA</option>
                </select>
            </div>
            <div style="float: left; width: 230px; margin-right: 10px;" 
                    id="linked_type" 
                    data-html="true" 
                    data-toggle="tooltip" 
                    title="Tipologia Collegato" 
                    class="hidden">
                <select name="linked_type[]" 
                        class="form-control MoreSelect2me no-init tipologie_collegato"
                        title="Tipologia Collegato">
                    <option></option>
                    <option value="3">ALTRO</option>
                    <option value="34">ASS</option>
                    <option value="26">AVALLANTE</option>
                    <option value="25">COINTESTATARIO</option>
                    <option value="16">COMPAGNO/A</option>
                    <option value="12">CONIUGE</option>
                    <option value="2">COOBLIGATO</option>
                    <option value="22">COOBLIGATO SENZA FIRMA</option>
                    <option value="19">DATORE DI LAVORO</option>
                    <option value="20">DATORE DI LAVORO COLLEGATO</option>
                    <option value="30">DATORE DI LAVORO FIDEJUSSORE 1</option>
                    <option value="32">DATORE DI LAVORO FIDEJUSSORE 2</option>
                    <option value="28">DATORE DI LAVORO FIRMATARIO</option>
                    <option value="8">DEBITORE PRINCIPALE</option>
                    <option value="29">FIDEJUSSORE 1</option>
                    <option value="31">FIDEJUSSORE 2</option>
                    <option value="17">FIGLIO/A</option>
                    <option value="27">FIRMATARIO</option>
                    <option value="1">GARANTE</option>
                    <option value="23">GARANTE SENZA FIRMA</option>
                    <option value="13">INTERNO</option>
                    <option value="24">PARENTE</option>
                    <option value="10">RAPPRESENTANTE LEGALE</option>
                    <option value="11">REFERENTE OPERATIVO</option>
                    <option value="35">SOC</option>
                    <option value="36">TEST</option>
                    <option value="15">TITOLARE</option>
                    <option value="18">TITOLARE EFFETTIVO</option>
                    <option value="33">TPT</option>
                    <option value="14">VENDITORE</option>
                </select>
            </div>
        </div>
        <div class="col-md-2" style="line-height:44px;">&nbsp;</div>
    </span>
</div>
<!-- BEGIN FOOTER -->
<?php require_once('elements/footer.php') ?>
<!-- END FOOTER -->

<?php require_once('include/javascript-end.php') ?>

<script>
    placeLoader();

    jQuery(document).ready(function () {
        clearInterval(dynamicTimer);

        App.init();
        FormSamples.init();
        FormComponents.init();

        <?php
        if(isset($_GET['config']) && $_GET['config'] != '') {
        ?>
        compila_contratti('<?php echo $configurazione['id_mandante'] ?>');
        <?php
        }
        ?>

        removeLoader();
    });

    var line = <?php echo (isset($line) and is_numeric($line)) ? "$line" : '0' ?>;

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
            if (extension == 'csv' || extension == 'txt') {
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

    function displayOptionalFields(elem, index) {
        if (elem.val() == 'debitore*-*indirizzo-telefono' ||
            elem.val() == 'debitore*-*indirizzo-cellulare' ||
            elem.val() == 'debitore*-*indirizzo-email' ||
            elem.val() == 'debitore*-*indirizzo-pec' ||
            elem.val() == 'garante*-*indirizzo-telefono' ||
            elem.val() == 'garante*-*indirizzo-email' ||
            elem.val() == 'collegato*-*indirizzo-telefono' ||
            elem.val() == 'collegato*-*indirizzo-email') {
            $('.extra-field-' + index).addClass('hidden');
            $('.field_predefinito-' + index).removeClass('hidden');
        } else if (elem.val() == 'debitore*-*indirizzo' ||
            elem.val() == 'garante*-*indirizzo' ||
            elem.val() == 'collegato*-*indirizzo') {
            $('.extra-field-' + index).addClass('hidden');
            $('.field_predefinito-' + index).removeClass('hidden');
            $('.field_address_type-' + index).removeClass('hidden');
        } else if (elem.val() == 'collegato*-*cognome' || elem.val() == 'garante*-*cognome') {
            $('.extra-field-' + index).addClass('hidden');
            $('.field_linked_type-' + index).removeClass('hidden');
        } else {
            $('.extra-field-' + index).addClass('hidden');
        }

        if (elem.val().indexOf('collegato*-*') == 0 ||
            elem.val().indexOf('garante*-*') == 0 ||
            elem.val() == 'debitore*-*indirizzo' ||
            elem.val() == 'debitore*-*cap' ||
            elem.val() == 'debitore*-*citta' ||
            elem.val() == 'debitore*-*provincia' ||
            elem.val() == 'debitore*-*nazione' ||
            elem.val() == 'debitore*-*indirizzo-email' ||
            elem.val() == 'debitore*-*indirizzo-pec' ||
            elem.val() == 'debitore*-*indirizzo-telefono' ||
            elem.val() == 'debitore*-*indirizzo-cellulare' ||
            elem.val().indexOf('*-*tipo_indirizzo') > 0 ||
            elem.val().indexOf('*-*tipo-recapito') > 0) {
            $('.field_position-' + index).removeClass('hidden');
        }

        if (elem.val() == 'debitore*-*tipo_indirizzo' ||
            elem.val() == 'debitore*-*indirizzo' ||
            elem.val() == 'debitore*-*cap' ||
            elem.val() == 'debitore*-*citta' ||
            elem.val() == 'debitore*-*provincia' ||
            elem.val() == 'debitore*-*nazione' ||
            elem.val() == 'collegato*-*tipo_indirizzo' ||
            elem.val() == 'collegato*-*indirizzo' ||
            elem.val() == 'collegato*-*cap' ||
            elem.val() == 'collegato*-*citta' ||
            elem.val() == 'collegato*-*provincia' ||
            elem.val() == 'collegato*-*nazione' ||
            elem.val() == 'garante*-*tipo_indirizzo' ||
            elem.val() == 'garante*-*indirizzo' ||
            elem.val() == 'garante*-*cap' ||
            elem.val() == 'garante*-*citta' ||
            elem.val() == 'garante*-*provincia' ||
            elem.val() == 'garante*-*nazione'
        ) {
            $('.field_address_position-' + index).removeClass('hidden');
        } else {
            $('.field_address_position-' + index).addClass('hidden');
        }
    }
    
    function displayMoreLines() {
        for(i=line; i<(parseInt(line)+10); i++) {
            
            $('.container-select').append($('#template-select-row').html())
            let newLine = $('.container-select').find('.line:last');
            newLine.find('select.no-init').attr('data-count',i)
            newLine.attr('data-line',i)
            newLine.find('.text-i').text(i + 1)

            // Aggiunge classi
            newLine.find('#pos').addClass('field_position-'+ i)
            newLine.find('#pos').addClass('extra-field-'+ i)
            newLine.find('#i_pos').addClass('extra-field-'+ i)
            newLine.find('#i_pos').addClass('field_address_position-'+ i)
            newLine.find('#usa_invio').addClass('extra-field-'+ i)
            newLine.find('#usa_invio').addClass('field_predefinito-'+ i)
            newLine.find('#address_type').addClass('extra-field-'+ i)
            newLine.find('#address_type').addClass('field_address_type-'+ i)
            newLine.find('#linked_type').addClass('extra-field-'+ i)
            newLine.find('#linked_type').addClass('field_linked_type-'+ i)
            newLine.find('#usa_invio').attr('id','checkHidden' + i)
        }
        
        $('.container-select').find('select.no-init').each(function (index, element) {
            $(this).select2({
                placeholder: "Seleziona",
                allowClear: true
            })
        });
        
        $('.container-select').each(function (index, element) {
            $(this).find('.Moremake-switch').bootstrapSwitch();
        });

        $('.container-select').find('.no-init').removeClass('no-init');

        line += 10

        if (line >= 299){
            $('.btn-aggiungi-linea').css('display', 'none')
        }
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

        var ret = verifica_indirizzo_aui_no_submit()
        if (!ret) {
            return false;
        }

        var ret2 = verifica_indirizzo_collegati_pratica()
        if (!ret2) {
            return false;
        }

        var recapiti_predefiniti = '';
        $('.recapiti_predefiniti').each(function (index, element) {
            if ($(this).is(':checked') == true) {
                recapiti_predefiniti += '1;';
            } else {
                recapiti_predefiniti += '0;';
            }
        });

        let arrayCampi = [];
        $('select.campi').each(function (index, element ) {
            arrayCampi.push($(element).val());
        })
        for (let i = arrayCampi.length; i < 300; i++) {
            arrayCampi.push('');            
        }
        $('select.campi_composti').each(function (index, element ) {
            arrayCampi.push($(element).val());
        })
        let input_campi = arrayCampi.join(';');

        var input_mandante = $('#valore_mandante').val();
        // var input_campi = $('.campi').serialize();
        var input_posizioni = $('.posizioni').serialize();
        var input_posizioni_indirizzo = $('.posizioni_indirizzo').serialize();
        //var input_recapiti_predefiniti = $('.recapiti_predefiniti').serialize();
        var input_recapiti_predefiniti = recapiti_predefiniti;
        var input_tipologie_collegato = $('.tipologie_collegato').serialize();
        var input_tipologie_indirizzo = $('.tipologie_indirizzo').serialize();
        var input_applica_quando_contratto = $('.applica_contratti').serialize();
        var input_applica_quando_campo = $('.applica_campi').serialize();
        var input_applica_quando_operatore = $('.applica_operatori').serialize();
        var input_applica_quando_valore = $('.applica_valori').serialize();
        
        // var input_campi_composti = $('.campi_composti').serialize();
        var input_funzione_pers = $('.funzione_pers').serialize();
        var input_colonne_pers = $('.colonne_pers').serialize();

        var input_contratto = $('#contratto').val();
        var input_tipo_file = $('#tipo_file').val();
        var input_riga = $('#rigaPartenza').val();
        var input_delimitatore = $('#delimitatore').val();
        var input_lunghezzaPosizioni = $('#lunghezzaPosizioni').val();
        var input_formato_valuta = $('#formato_valuta').val();
        var input_formato_data = $('#formato_data').val();
        var input_natura_fissa = $('#natura_credito_fissa').val();
        var evento_strutturato_predefinito_new = $('#evento_strutturato_predefinito_new').val();
        var azione_post_exec = $('#azione_post_exec').val();
        var id_filiale = $('#id_select_filiale').val();

        var input_ind_predefinito = 0;
        $('.ind_primario_predefinito').each(function (index, element) {
            if ($(this).is(':checked'))
                input_ind_predefinito = 1
        });
        var aggiorna_lotto_studio = 0;
        $('.aggiorna_lotto_studio').each(function (index, element) {
            if ($(this).is(':checked'))
                aggiorna_lotto_studio = 1
        });

        var acquiszione_riprendi_pratiche = 0;
        $('.acquiszione_riprendi_pratiche').each(function (index, element) {
            if ($(this).is(':checked'))
                acquiszione_riprendi_pratiche = 1
        });

        //console.log(input_campi);
        //console.log(input_posizioni);

        $.ajax({
            url: "form_actions.php",
            type: "POST",
            data: {
                action: 'salva-configurazione-acquisizione',
                id_mandante: input_mandante,
                campi: input_campi,
                posizioni: input_posizioni,
                posizioni_indirizzo: input_posizioni_indirizzo,
                recapiti_predefiniti: input_recapiti_predefiniti,
                tipologie_collegato: input_tipologie_collegato,
                tipologie_indirizzo: input_tipologie_indirizzo,
                descrizione: desc,
                applica_quando_contratto: input_applica_quando_contratto,
                applica_quando_campo: input_applica_quando_campo,
                applica_quando_operatore: input_applica_quando_operatore,
                applica_quando_valore: input_applica_quando_valore,
                // composti: input_campi_composti,
                funzione_pers: input_funzione_pers,
                colonne_pers: input_colonne_pers,
                contratto: input_contratto,
                tipo_file: input_tipo_file,
                riga: input_riga,
                delimitatore: input_delimitatore,
                lunghezzaPosizioni: input_lunghezzaPosizioni,
                formato_valuta: input_formato_valuta,
                formato_data: input_formato_data,
                indirizzi_predefiniti: input_ind_predefinito,
                aggiorna_lotto_studio: aggiorna_lotto_studio,
                acquiszione_riprendi_pratiche: acquiszione_riprendi_pratiche,
                natura_fissa: input_natura_fissa,
                evento_strutturato_predefinito_new: evento_strutturato_predefinito_new,
                azione_post_exec: azione_post_exec,
                id_filiale: id_filiale
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

        let arrayCampi = [];
        $('select.campi').each(function (index, element ) {
            arrayCampi.push($(element).val());
        })
        for (let i = arrayCampi.length; i < 300; i++) {
            arrayCampi.push('');            
        }
        $('select.campi_composti').each(function (index, element ) {
            arrayCampi.push($(element).val());
        })
        let input_campi = arrayCampi.join(';');
        

        var input_mandante = $('#valore_mandante').val();
        // var input_campi = $('.campi').serialize();
        var input_posizioni = $('.posizioni').serialize();
        var input_posizioni_indirizzo = $('.posizioni_indirizzo').serialize();
        //var input_recapiti_predefiniti = $('.recapiti_predefiniti').serialize();
        var input_recapiti_predefiniti = recapiti_predefiniti;
        var input_tipologie_collegato = $('.tipologie_collegato').serialize();
        var input_tipologie_indirizzo = $('.tipologie_indirizzo').serialize();
        var input_applica_quando_contratto = $('.applica_contratti').serialize();
        var input_applica_quando_campo = $('.applica_campi').serialize();
        var input_applica_quando_operatore = $('.applica_operatori').serialize();
        var input_applica_quando_valore = $('.applica_valori').serialize();

        // var input_campi_composti = $('.campi_composti').serialize();
        var input_funzione_pers = $('.funzione_pers').serialize();
        var input_colonne_pers = $('.colonne_pers').serialize();

        var input_contratto = $('#contratto').val();
        var input_tipo_file = $('#tipo_file').val();
        var input_riga = $('#rigaPartenza').val();
        var input_delimitatore = $('#delimitatore').val();
        var input_lunghezzaPosizioni = $('#lunghezzaPosizioni').val();
        var input_formato_valuta = $('#formato_valuta').val();
        var input_formato_data = $('#formato_data').val();
        var input_natura_fissa = $('#natura_credito_fissa').val();
        var evento_strutturato_predefinito_new = $('#evento_strutturato_predefinito_new').val();
        var azione_post_exec = $('#azione_post_exec').val();
        var id_filiale = $('#id_select_filiale').val();

        var input_ind_predefinito = 0;
        $('.ind_primario_predefinito').each(function (index, element) {
            if ($(this).is(':checked'))
                input_ind_predefinito = 1
        });

        var aggiorna_lotto_studio = 0;
        $('.aggiorna_lotto_studio').each(function (index, element) {
            if ($(this).is(':checked'))
                aggiorna_lotto_studio = 1
        });
        var acquiszione_riprendi_pratiche = 0;
        $('.acquiszione_riprendi_pratiche').each(function (index, element) {
            if ($(this).is(':checked'))
                acquiszione_riprendi_pratiche = 1
        });

        //console.log(input_campi);
        //console.log(input_posizioni);

        $.ajax({
            url: "form_actions.php",
            type: "POST",
            data: {
                action: 'modifica-configurazione-acquisizione',
                id: identity,
                id_mandante: input_mandante,
                campi: input_campi,
                posizioni: input_posizioni,
                posizioni_indirizzo: input_posizioni_indirizzo,
                recapiti_predefiniti: input_recapiti_predefiniti,
                tipologie_collegato: input_tipologie_collegato,
                tipologie_indirizzo: input_tipologie_indirizzo,
                applica_quando_contratto: input_applica_quando_contratto,
                applica_quando_campo: input_applica_quando_campo,
                applica_quando_operatore: input_applica_quando_operatore,
                applica_quando_valore: input_applica_quando_valore,
                // composti: input_campi_composti,
                funzione_pers: input_funzione_pers,
                colonne_pers: input_colonne_pers,
                contratto: input_contratto,
                tipo_file: input_tipo_file,
                riga: input_riga,
                delimitatore: input_delimitatore,
                lunghezzaPosizioni: input_lunghezzaPosizioni,
                formato_valuta: input_formato_valuta,
                formato_data: input_formato_data,
                indirizzi_predefiniti: input_ind_predefinito,
                aggiorna_lotto_studio: aggiorna_lotto_studio,
                acquiszione_riprendi_pratiche: acquiszione_riprendi_pratiche,
                natura_fissa: input_natura_fissa,
                evento_strutturato_predefinito_new: evento_strutturato_predefinito_new,
                azione_post_exec: azione_post_exec,
                id_filiale: id_filiale
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
                    action: 'elimina-configurazione-acquisizione',
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

    function compila_lotti_studio(id) {
        $.ajax({
            url: "form_actions.php",
            type: "POST",
            data: {action: 'ricerca-lotto-studio-da-lotto-studio', id_lotto_mandante: id},
            success: function (result) {
                //console.log(result);
                $('#inserimento_lotto_studio').html(result)
            },
            error: function (richiesta, stato, errori) {
                console.log('ERRORE NELL\'ESECUZIONE DEL FILTRO');
            }
        });
    }

    function compila_configutazioni(id) {
        $.ajax({
            url: "form_actions.php",
            type: "POST",
            data: {action: 'ricerca-configurazioni-da-mandante', id_mandante: id},
            success: function (result) {
                //console.log(result);
                $('#inserimento_configurazione').html(result)
            },
            error: function (richiesta, stato, errori) {
                console.log('ERRORE NELL\'ESECUZIONE DEL FILTRO');
            }
        });
    }

    function compila_contratti(id) {
        $.ajax({
            url: "form_actions.php",
            type: "POST",
            data: {action: 'ricerca-contratti-da-mandante', id_mandante: id},
            success: function (result) {
                //console.log(result);
                $('select.inserimento_contratto').html(result)
                <?php
                if($configurazione['contratto'] > 0) {
                ?>
                $('#contratto').val('<?php echo $configurazione['contratto'] ?>');
                $("#s2id_contratto").find('span.select2-chosen').css('color', '#000');
                $("#s2id_contratto").find('span.select2-chosen').html($('#contratto option[value="<?php echo $configurazione['contratto'] ?>"]').html());
                <?php
                }

                if(count($configurazione_applica_quando_contratto) > 0 && $configurazione_applica_quando_contratto[0] > 0) {
                ?>
                setTimeout(function () {
                    $('#applica_contratto_0').val('<?php echo $configurazione_applica_quando_contratto[0] ?>');
                    $('#applica_contratto_0 option[value="<?php echo $configurazione_applica_quando_contratto[0] ?>"]').attr('selected', 'selected');
                    $("#s2id_applica_contratto_0").find('span.select2-chosen').css('color', '#000');
                    $("#s2id_applica_contratto_0").find('span.select2-chosen').html($('#applica_contratto_0 option[value="<?php echo $configurazione_applica_quando_contratto[0] ?>"]').html());

                    <?php for($k = 1; $k < $limiteContrattiStudio;$k++) { ?>

                    $('#applica_contratto_<?php echo $k; ?>').val('<?php echo $configurazione_applica_quando_contratto[$k] ?>');
                    $('#applica_contratto_<?php echo $k; ?> option[value="<?php echo $configurazione_applica_quando_contratto[$k] ?>"]').attr('selected', 'selected');
                    $("#s2id_applica_contratto_<?php echo $k; ?>").find('span.select2-chosen').css('color', '#000');
                    $("#s2id_applica_contratto_<?php echo $k; ?>").find('span.select2-chosen').html($('#applica_contratto_1 option[value="<?php echo $configurazione_applica_quando_contratto[$k] ?>"]').html());
                    <?php  } ?>

                }, 3000);
                <?php
                }
                ?>
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

        $('.ind_primario_predefinito').each(function (index, element) {
            if ($(this).is(':checked')) {
                $('#checkHiddenIndPred').attr('disabled', 'disabled');
            }
        });

        $('.aggiorna_lotto_studio').each(function (index, element) {
            if ($(this).is(':checked')) {
                $('#checkHiddenaggiorna_lotto_studio').attr('disabled', 'disabled');
            }
        });

        $('.acquiszione_riprendi_pratiche').each(function (index, element) {
            if ($(this).is(':checked')) {
                $('#checkHiddenacquiszione_riprendi_pratiche').attr('disabled', 'disabled');
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
                data: {action: 'ricerca-mandante', utente: query.term},
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

    $("input.select2_lotto").select2({
        placeholder: "Seleziona Lotto",
        allowClear: true,
        minimumInputLength: 3,
        query: function (query) {
            var data = {
                results: []
            };

            $.ajax({
                url: "form_actions.php",
                type: "POST",
                data: {action: 'ricerca-lotto-mandante', utente: query.term},
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
    $(".select2_mandanti").find('span.select2-chosen').html('<?php echo addslashes($mandante['cognome']) . ' ' . addslashes($mandante['nome']) . ' (' . $mandante['codice_fiscale'] . ')' ?>');
    <?php
    }


    if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == MANDANTE) {
    $mandante = mysql_fetch_array(db_query('SELECT * FROM utente WHERE id_utente = "' . $_SESSION['user_admin_id'] . '"'));
    ?>
    $(".select2_mandanti").find('span.select2-chosen').html('<?php echo addslashes($mandante['cognome']) . ' ' . addslashes($mandante['nome']) . ' (' . $mandante['codice_fiscale'] . ')' ?>');

    setTimeout(function () {
        $(".select2_mandanti").trigger('change');
    }, 200);
    <?php
    }
    ?>






    $("input.select2_eventi_strutturati").select2({
        placeholder: "Seleziona Evento",
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

    /*$(".select2_filiale").attr('value', '<?php echo $idFilialeDefault; ?>');
    $(".select2_filiale").select2({
        placeholder: "Seleziona Filiale",
        allowClear: true,
        minimumInputLength: 3,
        initSelection: function (element, callback) {
            return $.ajax({
                url: 'form_actions.php', type: "POST", dataType: "json",
                data: {id: <?php echo $idFilialeDefault; ?>, action: 'ricerca-filiale-default'}
            }).success(function (result) {
                callback({id: result[0].id, text: result[0].text});
            });
        },
        query: function (query) {
            var data = {
                results: []
            };

            $.ajax({
                url: "form_actions.php",
                type: "POST",
                data: {action: 'ricerca-filiale', descrizione: query.term},
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
    });*/



    <?php
    if(isset($_GET['config']) && $_GET['config'] != '') {


    $eventoDesc = mysql_fetch_array(db_query('SELECT descrizione FROM eventi_strutturati WHERE id = "' . $configurazione['evento_strutturato_predefinito_new'] . '"'));

    if(isset($configurazione['evento_strutturato_predefinito_new'])){
    ?>
    // $(".select2_eventi_strutturati").find('span.select2-chosen').html('<?php echo $eventoDesc['descrizione'] . ' ' . ' (' . $configurazione['evento_strutturato_predefinito_new'] . ')' ?>');


    $('.select2_eventi_strutturati').select2("data", {
        'id': <?php echo $configurazione['evento_strutturato_predefinito_new']; ?>,
        'text': '<?php  echo $eventoDesc['descrizione'] . ' ' . ' (' . $configurazione['evento_strutturato_predefinito_new'] . ')';  ?>'
    });
    <?php
    }
    }
    ?>

    enableSubmit = false;

    function verifica_indirizzo_aui(elem) {

        verifyCheckboxes();

        var campoIndirizzoTrovato = 0;
        $('.tipologie_indirizzo').each(function (index) {
            if (!$(this).parent().hasClass('hidden')) {
                if ($(this).val() == 4 || $(this).val() == 8) {
                    campoIndirizzoTrovato++;
                }
            }
        });

        if (!verifica_indirizzo_collegati_pratica()) {
            $('#loader1').hide();
            $('#loader0').hide();
            return false;
        }

        if (campoIndirizzoTrovato == 0) {
            $('#loader1').hide();
            $('#loader0').hide();

            if (!enableSubmit) {
                swalConfirm("Non è stato selezionato nessun indirizzo di Residenza/Domicilio. Vuoi proseguire?", 'ATTENZIONE', 'warning', function () {

                    placePageLoader();

                    setTimeout(function () {
                        enableSubmit = true;
                        elem.submit()
                    }, 500);

                }, function () {
                    enableSubmit = false;
                    return false;

                });
                return false;
            } else {
                enableSubmit = false;
                return true;
            }
        } else {
            return true;
        }


    }


    function verifica_indirizzo_aui_no_submit() {

        var ret = false;

        var campoIndirizzoTrovato = 0;
        $('.tipologie_indirizzo').each(function (index) {
            if (!$(this).parent().hasClass('hidden')) {
                if ($(this).val() == 4 || $(this).val() == 8) {
                    campoIndirizzoTrovato++;
                }
            }
        });

        if (campoIndirizzoTrovato == 0) {
            if (confirm("Non è stato selezionato nessun indirizzo di Residenza/Domicilio. Vuoi proseguire?")) {
                ret = true;
            } else {
                ret = false;
            }
        } else {
            ret = true;
        }

        return ret;
    }


    function verifica_indirizzo_collegati_pratica() {

        var ret = true;

        var collegati_pratica_ind = 0;
        var array_campi_indirizzi = [];
        $('.campi').each(function (index) {
            if ($(this).val() == 'garante*-*indirizzo') {
                array_campi_indirizzi.push($(this).closest('span').attr('data-line'));
                collegati_pratica_ind++;
            }
        });


        var verificaPosIndirizzo = 0;
        var array_campi_indirizzi_prin = [];
        var verificaDomicilioResidenza = 0;

        if (collegati_pratica_ind > 0) {
            for (let i = 0; i < collegati_pratica_ind; i++) {
                if ($('.field_address_position-' + array_campi_indirizzi[i]).find('.posizioni_indirizzo').val() == '0') {
                    verificaPosIndirizzo++;
                    array_campi_indirizzi_prin.push(array_campi_indirizzi[i]);
                }
            }


            if (verificaPosIndirizzo == 0) {
                $('#loader1').hide();
                $('#loader0').hide();
                alert("Assegnare indirizzo principale al collegato pratica!");
                ret = false;
            } else {

                for (let i = 0; i < verificaPosIndirizzo; i++) {
                    /*  console.warn("T");
                       console.warn('field_address_type-'+array_campi_indirizzi_prin[i]);
                      console.warn($('.field_address_type-'+array_campi_indirizzi_prin[i]).find('select').val());
   */
                    if ($('.field_address_type-' + array_campi_indirizzi_prin[i]).find('select').val() == '4' ||
                        $('.field_address_type-' + array_campi_indirizzi_prin[i]).find('select').val() == '8') {
                        verificaDomicilioResidenza++;
                    }
                }


            }


            if (verificaDomicilioResidenza == verificaPosIndirizzo) {
                ret = true;

            } else {
                $('#loader1').hide();
                $('#loader0').hide();
                alert("L'indirizzo principale del collegato pratica deve essere di tipo domicilio oppure residenza !");
                ret = false;
            }

        }

        return ret;
    }


</script>
<!-- END JAVASCRIPTS -->

</body>
<!-- END BODY -->
</html>