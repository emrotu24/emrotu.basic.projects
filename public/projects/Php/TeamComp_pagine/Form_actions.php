<?php

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

$listaColonneExcel = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "AA", "AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI", "AJ", "AK", "AL", "AM", "AN", "AO", "AP", "AQ", "AR", "AS", "AT", "AU", "AV", "AW", "AX", "AY", "AZ", "BA", "BB", "BC", "BD", "BE", "BF", "BG", "BH", "BI", "BJ", "BK", "BL", "BM", "BN", "BO", "BP", "BQ", "BR", "BS", "BT", "BU", "BV", "BW", "BX", "BY", "BZ", "CA", "CB", "CC", "CD", "CE", "CF", "CG", "CH", "CI", "CJ", "CK", "CL", "CM", "CN", "CO", "CP", "CQ", "CR", "CS", "CT", "CU", "CV", "CW", "CX", "CY", "CZ", "DA", "DB", "DC", "DD", "DE", "DF", "DG", "DH", "DI", "DJ", "DK", "DL", "DM", "DN", "DO", "DP", "DQ", "DR", "DS", "DT", "DU", "DV", "DW", "DX", "DY", "DZ", "EA", "EB", "EC", "ED", "EE", "EF", "EG", "EH", "EI", "EJ", "EK", "EL", "EM", "EN", "EO", "EP", "EQ", "ER", "ES", "ET", "EU", "EV", "EW", "EX", "EY", "EZ", "FA", "FB", "FC", "FD", "FE", "FF", "FG", "FH", "FI", "FJ", "FK", "FL", "FM", "FN", "FO", "FP", "FQ", "FR", "FS", "FT", "FU", "FV", "FW", "FX", "FY", "FZ", "GA", "GB", "GC", "GD", "GE", "GF", "GG", "GH", "GI", "GJ", "GK", "GL", "GM", "GN", "GO", "GP", "GQ", "GR", "GS", "GT", "GU", "GV", "GW", "GX", "GY", "GZ", "HA", "HB", "HC", "HD", "HE", "HF", "HG", "HH", "HI", "HJ", "HK", "HL", "HM", "HN", "HO", "HP", "HQ", "HR", "HS", "HT", "HU", "HV", "HW", "HX", "HY", "HZ", "IA", "IB", "IC", "ID", "IE", "IF", "IG", "IH", "II", "IJ", "IK", "IL", "IM", "IN", "IO", "IP", "IQ", "IR", "IS", "IT", "IU", "IV", "IW", "IX", "IY", "IZ", "JA", "JB", "JC", "JD", "JE", "JF", "JG", "JH", "JI", "JJ", "JK", "JL", "JM", "JN", "JO", "JP", "JQ", "JR", "JS", "JT", "JU", "JV", "JW", "JX", "JY", "JZ", "KA", "KB", "KC", "KD", "KE", "KF", "KG", "KH", "KI", "KJ", "KK", "KL", "KM", "KN", "KO", "KP", "KQ", "KR", "KS", "KT", "KU", "KV", "KW", "KX", "KY", "KZ", "LA", "LB", "LC", "LD", "LE", "LF", "LG", "LH", "LI", "LJ", "LK", "LL", "LM", "LN", "LO", "LP", "LQ", "LR", "LS", "LT", "LU", "LV", "LW", "LX", "LY", "LZ", "MA", "MB", "MC", "MD", "ME", "MF", "MG", "MH", "MI", "MJ", "MK", "ML", "MM", "MN", "MO", "MP", "MQ", "MR", "MS", "MT", "MU", "MV", "MW", "MX", "MY", "MZ", "NA", "NB", "NC", "ND", "NE", "NF", "NG", "NH", "NI", "NJ", "NK", "NL", "NM", "NN", "NO", "NP", "NQ", "NR", "NS", "NT", "NU", "NV", "NW", "NX", "NY", "NZ", "OA", "OB", "OC", "OD", "OE", "OF", "OG", "OH", "OI", "OJ", "OK", "OL", "OM", "ON", "OO", "OP", "OQ", "OR", "OS", "OT", "OU", "OV", "OW", "OX", "OY", "OZ", "PA", "PB", "PC", "PD", "PE", "PF", "PG", "PH", "PI", "PJ", "PK", "PL", "PM", "PN", "PO", "PP", "PQ", "PR", "PS", "PT", "PU", "PV", "PW", "PX", "PY", "PZ", "QA", "QB", "QC", "QD", "QE", "QF", "QG", "QH", "QI", "QJ", "QK", "QL", "QM", "QN", "QO", "QP", "QQ", "QR", "QS", "QT", "QU", "QV", "QW", "QX", "QY", "QZ", "RA", "RB", "RC", "RD", "RE", "RF", "RG", "RH", "RI", "RJ", "RK", "RL", "RM", "RN", "RO", "RP", "RQ", "RR", "RS", "RT", "RU", "RV", "RW", "RX", "RY", "RZ", "SA", "SB", "SC", "SD", "SE", "SF", "SG", "SH", "SI", "SJ", "SK", "SL", "SM", "SN", "SO", "SP", "SQ", "SR", "SS", "ST", "SU", "SV", "SW", "SX", "SY", "SZ", "TA", "TB", "TC", "TD", "TE", "TF", "TG", "TH", "TI", "TJ", "TK", "TL", "TM", "TN", "TO", "TP", "TQ", "TR", "TS", "TT", "TU", "TV", "TW", "TX", "TY", "TZ", "UA", "UB", "UC", "UD", "UE", "UF", "UG", "UH", "UI", "UJ", "UK", "UL", "UM", "UN", "UO", "UP", "UQ", "UR", "US", "UT", "UU", "UV", "UW", "UX", "UY", "UZ", "VA", "VB", "VC", "VD", "VE", "VF", "VG", "VH", "VI", "VJ", "VK", "VL", "VM", "VN", "VO", "VP", "VQ", "VR", "VS", "VT", "VU", "VV", "VW", "VX", "VY", "VZ", "WA", "WB", "WC", "WD", "WE", "WF", "WG", "WH", "WI", "WJ", "WK", "WL", "WM", "WN", "WO", "WP", "WQ", "WR", "WS", "WT", "WU", "WV", "WW", "WX", "WY", "WZ", "XA", "XB", "XC", "XD", "XE", "XF", "XG", "XH", "XI", "XJ", "XK", "XL", "XM", "XN", "XO", "XP", "XQ", "XR", "XS", "XT", "XU", "XV", "XW", "XX", "XY", "XZ", "YA", "YB", "YC", "YD", "YE", "YF", "YG", "YH", "YI", "YJ", "YK", "YL", "YM", "YN", "YO", "YP", "YQ", "YR", "YS", "YT", "YU", "YV", "YW", "YX", "YY", "YZ", "ZA", "ZB", "ZC", "ZD", "ZE", "ZF", "ZG", "ZH", "ZI", "ZJ", "ZK", "ZL", "ZM", "ZN", "ZO", "ZP", "ZQ", "ZR", "ZS", "ZT", "ZU", "ZV", "ZW", "ZX", "ZY", "ZZ");
switch ($_POST['action']) {

    //===========================================================================================================================================//
    //========================================================== THEME CUSTOMIZER ===============================================================//
    //===========================================================================================================================================//
    case 'cambia-tema':
        {
            writeSession('user_admin_theme', $_POST['theme']);

            $query = 'UPDATE utente
					SET Theme_Customizer = "' . db_input($_POST['theme']) . '"
					WHERE id_utente = "' . $_SESSION['user_admin_id'] . '"';

            $ris = db_query($query);

            echo "Tema per l'utente cambiato correttamente";
        }
        break;
    //===========================================================================================================================================//
    //====================================================== ACQUISIZIONE DOCUMENTI =============================================================//
    //===========================================================================================================================================//

    // ANAGRAFICA - PRATICHE - TITOLI
    case 'salva-configurazione-acquisizione':
        {
            $_POST['azione_post_exec'] = isset($_POST['azione_post_exec']) ? $_POST['azione_post_exec'] : '0';
            $campi = str_replace('campo%5B%5D=', '', str_replace('&campo%5B%5D=', ';', $_POST['campi']));
            // $campi_pers = str_replace('campo%5B%5D=', '', str_replace('&campo%5B%5D=', ';', $_POST['campi_pers']));
            // $array_campi = explode(';', $campi);
            // for($i=count($array_campi); $i < 300; $i++) {
            //     $array_campi[]= '';
            // }


            
            // $campi = implode(';', $array_campi).';'.$campi_pers;



            $query = 'INSERT INTO template_acquisizione
					SET id_mandante = "' . db_input($_POST['id_mandante']) . '",
						campi = "' . $campi . '",
						posizioni = "' . str_replace('posizione%5B%5D=', '', str_replace('&posizione%5B%5D=', ';', $_POST['posizioni'])) . '",
						posizioni_indirizzo = "' . str_replace('posizione_indirizzo%5B%5D=', '', str_replace('&posizione_indirizzo%5B%5D=', ';', $_POST['posizioni_indirizzo'])) . '",
						recapiti_predefiniti = "' . db_input(trim($_POST['recapiti_predefiniti'], ';')) . '",
						tipologie_collegato = "' . str_replace('linked_type%5B%5D=', '', str_replace('&linked_type%5B%5D=', ';', $_POST['tipologie_collegato'])) . '",
						tipologie_indirizzo = "' . str_replace('address_type%5B%5D=', '', str_replace('&address_type%5B%5D=', ';', $_POST['tipologie_indirizzo'])) . '",
						descrizione = "' . db_input($_POST['descrizione']) . '",
						applica_quando_contratto = "' . str_replace('applica_quando_contratto%5B%5D=', '', str_replace('&applica_quando_contratto%5B%5D=', ';', $_POST['applica_quando_contratto'])) . '",
						applica_quando_campo = "' . str_replace('applica_quando_campo%5B%5D=', '', str_replace('&applica_quando_campo%5B%5D=', ';', $_POST['applica_quando_campo'])) . '",
						applica_quando_operatore = "' . str_replace('applica_quando_operatore%5B%5D=', '', str_replace('&applica_quando_operatore%5B%5D=', ';', $_POST['applica_quando_operatore'])) . '",
						applica_quando_valore = "' . str_replace('applica_quando_valore%5B%5D=', '', str_replace('&applica_quando_valore%5B%5D=', ';', $_POST['applica_quando_valore'])) . '",
						funzione_pers = "' . str_replace('funzione_pers%5B%5D=', '', str_replace('&funzione_pers%5B%5D=', ';', $_POST['funzione_pers'])) . '",
						colonne_pers = "' . str_replace('colonne_pers%5B%5D=', '', str_replace('&colonne_pers%5B%5D=', ';', $_POST['colonne_pers'])) . '",
						contratto = "' . $_POST['contratto'] . '",
						tipo_file = "' . $_POST['tipo_file'] . '",
						riga = "' . $_POST['riga'] . '",
						delimitatore = "' . $_POST['delimitatore'] . '",
						lunghezzaPosizioni = "' . $_POST['lunghezzaPosizioni'] . '",
						formato_valuta = "' . $_POST['formato_valuta'] . '",
						formato_data = "' . $_POST['formato_data'] . '",
						natura_fissa = "' . $_POST['natura_fissa'] . '",
						evento_strutturato_predefinito_new = "' . $_POST['evento_strutturato_predefinito_new'] . '",
						azione_post_exec = "' . $_POST['azione_post_exec'] . '",
						id_filiale = "' . $_POST['id_filiale'] . '",
						aggiorna_lotto_studio = "' . $_POST['aggiorna_lotto_studio'] . '",
						acquiszione_riprendi_pratiche = "' . $_POST['acquiszione_riprendi_pratiche'] . '",
						indirizzi_predefiniti = "' . $_POST['indirizzi_predefiniti'] . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['descrizione']) . " correttamente salvata";
        }
        break;
    case 'modifica-configurazione-acquisizione':
        {
            $_POST['azione_post_exec'] = isset($_POST['azione_post_exec']) ? $_POST['azione_post_exec'] : '0';
            
            $campi = str_replace('campo%5B%5D=', '', str_replace('&campo%5B%5D=', ';', $_POST['campi']));
            // $campi_pers = str_replace('campo%5B%5D=', '', str_replace('&campo%5B%5D=', ';', $_POST['campi_pers']));
            // $array_campi = explode(';', $campi);
            // for($i=count($array_campi); $i < 300; $i++) {
            //     $array_campi[]= '';
            // }


            
            // $campi = implode(';', $array_campi).';'.$campi_pers;

            $query = 'UPDATE template_acquisizione
					SET id_mandante = "' . db_input($_POST['id_mandante']) . '",
						campi = "' . $campi . '",
						posizioni = "' . str_replace('posizione%5B%5D=', '', str_replace('&posizione%5B%5D=', ';', $_POST['posizioni'])) . '",
						posizioni_indirizzo = "' . str_replace('posizione_indirizzo%5B%5D=', '', str_replace('&posizione_indirizzo%5B%5D=', ';', $_POST['posizioni_indirizzo'])) . '",
						recapiti_predefiniti = "' . db_input(trim($_POST['recapiti_predefiniti'], ';')) . '",
						tipologie_collegato = "' . str_replace('linked_type%5B%5D=', '', str_replace('&linked_type%5B%5D=', ';', $_POST['tipologie_collegato'])) . '",
						tipologie_indirizzo = "' . str_replace('address_type%5B%5D=', '', str_replace('&address_type%5B%5D=', ';', $_POST['tipologie_indirizzo'])) . '",
						applica_quando_contratto = "' . str_replace('applica_quando_contratto%5B%5D=', '', str_replace('&applica_quando_contratto%5B%5D=', ';', $_POST['applica_quando_contratto'])) . '",
						applica_quando_campo = "' . str_replace('applica_quando_campo%5B%5D=', '', str_replace('&applica_quando_campo%5B%5D=', ';', $_POST['applica_quando_campo'])) . '",
						applica_quando_operatore = "' . str_replace('applica_quando_operatore%5B%5D=', '', str_replace('&applica_quando_operatore%5B%5D=', ';', $_POST['applica_quando_operatore'])) . '",
						applica_quando_valore = "' . str_replace('applica_quando_valore%5B%5D=', '', str_replace('&applica_quando_valore%5B%5D=', ';', $_POST['applica_quando_valore'])) . '",
						funzione_pers = "' . str_replace('funzione_pers%5B%5D=', '', str_replace('&funzione_pers%5B%5D=', ';', $_POST['funzione_pers'])) . '",
						colonne_pers = "' . str_replace('colonne_pers%5B%5D=', '', str_replace('&colonne_pers%5B%5D=', ';', $_POST['colonne_pers'])) . '",
						contratto = "' . $_POST['contratto'] . '",
						tipo_file = "' . $_POST['tipo_file'] . '",
						riga = "' . $_POST['riga'] . '",
						delimitatore = "' . $_POST['delimitatore'] . '",
						lunghezzaPosizioni = "' . $_POST['lunghezzaPosizioni'] . '",
						formato_valuta = "' . $_POST['formato_valuta'] . '",
						formato_data = "' . $_POST['formato_data'] . '",
						natura_fissa = "' . $_POST['natura_fissa'] . '",
						evento_strutturato_predefinito_new = "' . $_POST['evento_strutturato_predefinito_new'] . '",
						azione_post_exec = "' . $_POST['azione_post_exec'] . '",
						id_filiale = "' . $_POST['id_filiale'] . '",
						aggiorna_lotto_studio = "' . $_POST['aggiorna_lotto_studio'] . '",
						acquiszione_riprendi_pratiche = "' . $_POST['acquiszione_riprendi_pratiche'] . '",
						indirizzi_predefiniti = "' . $_POST['indirizzi_predefiniti'] . '"
					WHERE 
						id = "' . db_input($_POST['id']) . '"';
            db_query($query);
            echo "Configurazione " . db_input($_POST['id']) . " correttamente modiifcata";
        }
        break;
    case 'elimina-configurazione-acquisizione':
        {
            $query = 'DELETE FROM template_acquisizione
					WHERE id = "' . db_input($_POST['id']) . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['id']) . " correttamente eliminata";
        }
        break;
    // INCASSI
    case 'salva-configurazione-acquisizione-incassi':
        {
            $query = 'INSERT INTO template_acquisizione_incassi
					SET id_mandante = "' . db_input($_POST['id_mandante']) . '",
						campi = "' . str_replace('campo%5B%5D=', '', str_replace('&campo%5B%5D=', ';', $_POST['campi'])) . '",
						posizioni = "' . str_replace('posizione%5B%5D=', '', str_replace('&posizione%5B%5D=', ';', $_POST['posizioni'])) . '",
						descrizione = "' . db_input($_POST['descrizione']) . '",
						funzione_pers = "' . str_replace('funzione_pers%5B%5D=', '', str_replace('&funzione_pers%5B%5D=', ';', $_POST['funzione_pers'])) . '",
						colonne_pers = "' . str_replace('colonne_pers%5B%5D=', '', str_replace('&colonne_pers%5B%5D=', ';', $_POST['colonne_pers'])) . '",
						tipo_file = "' . $_POST['tipo_file'] . '",
						riga = "' . $_POST['riga'] . '",
						delimitatore = "' . $_POST['delimitatore'] . '",
						lunghezzaPosizioni = "' . $_POST['lunghezzaPosizioni'] . '",
						formato_valuta = "' . $_POST['formato_valuta'] . '",
						formato_data = "' . $_POST['formato_data'] . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['descrizione']) . " correttamente salvata";
        }
        break;
    case 'modifica-configurazione-acquisizione-incassi':
        {
            $query = 'UPDATE template_acquisizione_incassi
					SET id_mandante = "' . db_input($_POST['id_mandante']) . '",
						campi = "' . str_replace('campo%5B%5D=', '', str_replace('&campo%5B%5D=', ';', $_POST['campi'])) . '",
						posizioni = "' . str_replace('posizione%5B%5D=', '', str_replace('&posizione%5B%5D=', ';', $_POST['posizioni'])) . '",
						funzione_pers = "' . str_replace('funzione_pers%5B%5D=', '', str_replace('&funzione_pers%5B%5D=', ';', $_POST['funzione_pers'])) . '",
						colonne_pers = "' . str_replace('colonne_pers%5B%5D=', '', str_replace('&colonne_pers%5B%5D=', ';', $_POST['colonne_pers'])) . '",
						tipo_file = "' . $_POST['tipo_file'] . '",
						riga = "' . $_POST['riga'] . '",
						delimitatore = "' . $_POST['delimitatore'] . '",
						lunghezzaPosizioni = "' . $_POST['lunghezzaPosizioni'] . '",
						formato_valuta = "' . $_POST['formato_valuta'] . '",
						formato_data = "' . $_POST['formato_data'] . '"
					WHERE 
						id = "' . db_input($_POST['id']) . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['id']) . " correttamente modiifcata";
        }
        break;
    case 'elimina-configurazione-acquisizione-incassi':
        {
            $query = 'DELETE FROM template_acquisizione_incassi
					WHERE id = "' . db_input($_POST['id']) . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['id']) . " correttamente eliminata";
        }
        break;
    // ACQUISIZIONE CONFIGURABILE
    case 'salva-configurazione-acquisizione-config':
        {

            if (!convalidaVerificaDuepassaggi($_SESSION['user_admin_id'], $_POST['pagina'], $_POST['id_codice_verifica'])) {
                echo "Codice Verifica Errato ";
            }

            $query = 'INSERT INTO template_acquisizione_config
					SET id_mandante = "' . db_input($_POST['id_mandante']) . '",
						descrizione = "' . db_input($_POST['descrizione']) . '",
						tipo_file = "' . $_POST['tipo_file'] . '",
						riga = "' . $_POST['riga'] . '",
						lunghezzaPosizioni = "' . $_POST['lunghezzaPosizioni'] . '",
						configImportazionePratiche = "' . $_POST['configImportazionePratiche'] . '",
						configNomeLotto = "' . $_POST['configNomeLotto'] . '",
						separaFile = "' . db_input($_POST['separazioneFile']) . '",
						percorso = "' . db_input($_POST['percorsoAssoluto']) . '",
						campoSeparazione = "' . db_input($_POST['campoSeparazione']) . '",
						nomeFile = "' . db_input($_POST['nome_file']) . '",
						zip = "' . db_input($_POST['zipFile']) . '",
						timestamp = "' . db_input($_POST['timestamp']) . '",
						ggValidita = "' . db_input($_POST['ggValidita']) . '",
						eventiDaEseguire = "' . db_input($_POST['eventi_pratica']) . '",
						eventiDaEseguireDue = "' . db_input($_POST['eventi_pratica_due']) . '",
						eventiDaEseguireTre = "' . db_input($_POST['eventi_pratica_tre']) . '",
						eventiDaEseguireQuattro = "' . db_input($_POST['eventi_pratica_quattro']) . '",
                        eventiDaEseguireCinque = "' . db_input($_POST['eventi_pratica_cinque']) . '",
                        eventiDaEseguireSei = "' . db_input($_POST['eventi_pratica_sei']) . '",
                        eventiDaEseguireSette = "' . db_input($_POST['eventi_pratica_sette']) . '",
                        eventiDaEseguireOtto = "' . db_input($_POST['eventi_pratica_otto']) . '",
                        eventiDaEseguireNove = "' . db_input($_POST['eventi_pratica_nove']) . '",
                        eventiDaEseguireDieci = "' . db_input($_POST['eventi_pratica_dieci']) . '",
                        eventiDaEseguireUndici = "' . db_input($_POST['eventi_pratica_undici']) . '",
                        eventiDaEseguireDodici = "' . db_input($_POST['eventi_pratica_dodici']) . '",
                        eventiDaEseguireTredici = "' . db_input($_POST['eventi_pratica_tredici']) . '",
						delimitatore = "' . $_POST['delimitatore'] . '"';
            db_query($query);

            $id_insert = mysql_insert_id();


            $array_pos = UnserializeDataForm($_POST['posizioni'])['posColonne'];
            $array_campi = UnserializeDataForm($_POST['campi'])['campoalias'];
            $array_tipi = UnserializeDataForm($_POST['tipi'])['campotipo'];
            $array_indici = UnserializeDataForm($_POST['indici'])['indice'];
            $array_ripeti = UnserializeDataForm($_POST['ripeti'])['ripeti_campo'];


            for ($i = 0; $i < count($array_pos); $i++) {

                if ($array_campi[$i] != '') {
                    $query = 'INSERT INTO acquisizione_config_campi
					SET pos = "' . db_input($array_pos[$i]) . '",
						nome = "' . db_input($array_campi[$i]) . '",
						tipo = "' . db_input($array_tipi[$i]) . '",
						indice = "' . db_input($array_indici[$i]) . '",
						ripeti_campo = "' . db_input($array_ripeti[$i]) . '",
						id_acquisizione = "' . $id_insert . '"';
                    db_query($query);
                }

            }

            $array_query = UnserializeDataForm($_POST['query'])['query'];


            for ($j = 0; $j < count($array_query); $j++) {

                if ($array_query[$j] != "") {
                    $query = 'INSERT INTO acquisizione_config_query
                        SET ordinamento="' . $j . '",
                            query="' . db_input($array_query[$j]) . '",
                            id_acquisizione="' . $id_insert . '"';
                    db_query($query);

                }
            }

            if (isset($_POST['queryExport']) && $_POST['queryExport'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_export
                        SET ordinamento=1,
                            query="' . db_input($_POST['queryExport']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }

            if (isset($_POST['queryEvento']) && $_POST['queryEvento'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=1,
                            query="' . db_input($_POST['queryEvento']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }

            if (isset($_POST['queryEventoDue']) && $_POST['queryEventoDue'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=2,
                            query="' . db_input($_POST['queryEventoDue']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }

            if (isset($_POST['queryEventoTre']) && $_POST['queryEventoTre'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=3,
                            query="' . db_input($_POST['queryEventoTre']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoQuattro']) && $_POST['queryEventoQuattro'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=4,
                            query="' . db_input($_POST['queryEventoQuattro']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoCinque']) && $_POST['queryEventoCinque'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=5,
                            query="' . db_input($_POST['queryEventoCinque']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoSei']) && $_POST['queryEventoSei'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=6,
                            query="' . db_input($_POST['queryEventoSei']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoSette']) && $_POST['queryEventoSette'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=7,
                            query="' . db_input($_POST['queryEventoSette']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoOtto']) && $_POST['queryEventoOtto'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=8,
                            query="' . db_input($_POST['queryEventoOtto']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoNove']) && $_POST['queryEventoNove'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=9,
                            query="' . db_input($_POST['queryEventoNove']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoDieci']) && $_POST['queryEventoDieci'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=10,
                            query="' . db_input($_POST['queryEventoDieci']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoUndici']) && $_POST['queryEventoUndici'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=11,
                            query="' . db_input($_POST['queryEventoUndici']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoDodici']) && $_POST['queryEventoDodici'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=12,
                            query="' . db_input($_POST['queryEventoDodici']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoTredici']) && $_POST['queryEventoTredici'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=13,
                            query="' . db_input($_POST['queryEventoTredici']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }

            echo "Configurazione " . db_input($_POST['descrizione']) . " correttamente salvata";
            header('Location: pagina_acquisizione_Configurabile.php');
        }
        break;
    case 'salva-configurazione-acquisizione-config-copia':
        {

            $query = 'INSERT INTO template_acquisizione_config
					SET id_mandante = "' . db_input($_POST['id_mandante']) . '",
						descrizione = "' . db_input($_POST['descrizione']) . '",
						tipo_file = "' . $_POST['tipo_file'] . '",
						riga = "' . $_POST['riga'] . '",
						lunghezzaPosizioni = "' . $_POST['lunghezzaPosizioni'] . '",
						configImportazionePratiche = "' . $_POST['configImportazionePratiche'] . '",
						configNomeLotto = "' . $_POST['configNomeLotto'] . '",
						separaFile = "' . db_input($_POST['separazioneFile']) . '",
						percorso = "' . db_input($_POST['percorsoAssoluto']) . '",
						campoSeparazione = "' . db_input($_POST['campoSeparazione']) . '",
						nomeFile = "' . db_input($_POST['nome_file']) . '",
						zip = "' . db_input($_POST['zipFile']) . '",
						timestamp = "' . db_input($_POST['timestamp']) . '",
						ggValidita = "' . db_input($_POST['ggValidita']) . '",
						eventiDaEseguire = "' . db_input($_POST['eventi_pratica']) . '",
						eventiDaEseguireDue = "' . db_input($_POST['eventi_pratica_due']) . '",
						eventiDaEseguireTre = "' . db_input($_POST['eventi_pratica_tre']) . '",
						eventiDaEseguireQuattro = "' . db_input($_POST['eventi_pratica_quattro']) . '",
                        eventiDaEseguireCinque = "' . db_input($_POST['eventi_pratica_cinque']) . '",
                        eventiDaEseguireSei = "' . db_input($_POST['eventi_pratica_sei']) . '",
                        eventiDaEseguireSette = "' . db_input($_POST['eventi_pratica_sette']) . '",
                        eventiDaEseguireOtto = "' . db_input($_POST['eventi_pratica_otto']) . '",
                        eventiDaEseguireNove = "' . db_input($_POST['eventi_pratica_nove']) . '",
                        eventiDaEseguireDieci = "' . db_input($_POST['eventi_pratica_dieci']) . '",
                        eventiDaEseguireUndici = "' . db_input($_POST['eventi_pratica_undici']) . '",
                        eventiDaEseguireDodici = "' . db_input($_POST['eventi_pratica_dodici']) . '",
                        eventiDaEseguireTredici = "' . db_input($_POST['eventi_pratica_tredici']) . '",
						delimitatore = "' . $_POST['delimitatore'] . '"';
            db_query($query);

            $id_insert = mysql_insert_id();


            $array_pos = UnserializeDataForm($_POST['posizioni'])['posColonne'];
            $array_campi = UnserializeDataForm($_POST['campi'])['campoalias'];
            $array_tipi = UnserializeDataForm($_POST['tipi'])['campotipo'];
            $array_indici = UnserializeDataForm($_POST['indici'])['indice'];
            $array_ripeti = UnserializeDataForm($_POST['ripeti'])['ripeti_campo'];


            for ($i = 0; $i < count($array_pos); $i++) {

                if ($array_campi[$i] != '') {
                    $query = 'INSERT INTO acquisizione_config_campi
					SET pos = "' . db_input($array_pos[$i]) . '",
						nome = "' . db_input($array_campi[$i]) . '",
						tipo = "' . db_input($array_tipi[$i]) . '",
						indice = "' . db_input($array_indici[$i]) . '",
						ripeti_campo = "' . db_input($array_ripeti[$i]) . '",
						id_acquisizione = "' . $id_insert . '"';
                    db_query($query);
                }

            }

            $array_query = UnserializeDataForm($_POST['query'])['query'];


            for ($j = 0; $j < count($array_query); $j++) {

                if ($array_query[$j] != "") {
                    $query = 'INSERT INTO acquisizione_config_query
                        SET ordinamento="' . $j . '",
                            query="' . db_input($array_query[$j]) . '",
                            id_acquisizione="' . $id_insert . '"';
                    db_query($query);

                }
            }

            if (isset($_POST['queryExport']) && $_POST['queryExport'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_export
                        SET ordinamento=1,
                            query="' . db_input($_POST['queryExport']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }

            if (isset($_POST['queryEvento']) && $_POST['queryEvento'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=1,
                            query="' . db_input($_POST['queryEvento']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }

            if (isset($_POST['queryEventoDue']) && $_POST['queryEventoDue'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=2,
                            query="' . db_input($_POST['queryEventoDue']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }

            if (isset($_POST['queryEventoTre']) && $_POST['queryEventoTre'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=3,
                            query="' . db_input($_POST['queryEventoTre']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoQuattro']) && $_POST['queryEventoQuattro'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=4,
                            query="' . db_input($_POST['queryEventoQuattro']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoCinque']) && $_POST['queryEventoCinque'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=5,
                            query="' . db_input($_POST['queryEventoCinque']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoSei']) && $_POST['queryEventoSei'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=6,
                            query="' . db_input($_POST['queryEventoSei']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoSette']) && $_POST['queryEventoSette'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=7,
                            query="' . db_input($_POST['queryEventoSette']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoOtto']) && $_POST['queryEventoOtto'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=8,
                            query="' . db_input($_POST['queryEventoOtto']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoNove']) && $_POST['queryEventoNove'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=9,
                            query="' . db_input($_POST['queryEventoNove']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoDieci']) && $_POST['queryEventoDieci'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=10,
                            query="' . db_input($_POST['queryEventoDieci']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoUndici']) && $_POST['queryEventoUndici'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=11,
                            query="' . db_input($_POST['queryEventoUndici']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoDodici']) && $_POST['queryEventoDodici'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=12,
                            query="' . db_input($_POST['queryEventoDodici']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoTredici']) && $_POST['queryEventoTredici'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=13,
                            query="' . db_input($_POST['queryEventoTredici']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }

            echo "Configurazione " . db_input($_POST['descrizione']) . " correttamente salvata";
            header('Location: pagina_acquisizione_Configurabile.php');
        }
        break;
    case 'modifica-configurazione-acquisizione-config':
        {
            $id_insert = db_input($_POST['id']);


            if (!convalidaVerificaDuepassaggi($_SESSION['user_admin_id'], $_POST['pagina'], $_POST['id_codice_verifica'])) {
                echo "Codice Verifica Errato ";
            }

            $query = 'UPDATE template_acquisizione_config
					SET id_mandante = "' . db_input($_POST['id_mandante']) . '",
						tipo_file = "' . $_POST['tipo_file'] . '",
						riga = "' . $_POST['riga'] . '",
						lunghezzaPosizioni = "' . $_POST['lunghezzaPosizioni'] . '",
						configImportazionePratiche = "' . $_POST['configImportazionePratiche'] . '",
						configNomeLotto = "' . $_POST['configNomeLotto'] . '",
						separaFile = "' . db_input($_POST['separazioneFile']) . '",
						percorso = "' . db_input($_POST['percorsoAssoluto']) . '",
						campoSeparazione = "' . db_input($_POST['campoSeparazione']) . '",
						nomeFile = "' . db_input($_POST['nome_file']) . '",
						zip = "' . db_input($_POST['zipFile']) . '",
						timestamp = "' . db_input($_POST['timestamp']) . '",
						ggValidita = "' . db_input($_POST['ggValidita']) . '",
						eventiDaEseguire = "' . db_input($_POST['eventi_pratica']) . '",
						eventiDaEseguireDue = "' . db_input($_POST['eventi_pratica_due']) . '",
						eventiDaEseguireTre = "' . db_input($_POST['eventi_pratica_tre']) . '",
						eventiDaEseguireQuattro = "' . db_input($_POST['eventi_pratica_quattro']) . '",
                        eventiDaEseguireCinque = "' . db_input($_POST['eventi_pratica_cinque']) . '",
                        eventiDaEseguireSei = "' . db_input($_POST['eventi_pratica_sei']) . '",
                        eventiDaEseguireSette = "' . db_input($_POST['eventi_pratica_sette']) . '",
                        eventiDaEseguireOtto = "' . db_input($_POST['eventi_pratica_otto']) . '",
                        eventiDaEseguireNove = "' . db_input($_POST['eventi_pratica_nove']) . '",
                        eventiDaEseguireDieci = "' . db_input($_POST['eventi_pratica_dieci']) . '",
                        eventiDaEseguireUndici = "' . db_input($_POST['eventi_pratica_undici']) . '",
                        eventiDaEseguireDodici = "' . db_input($_POST['eventi_pratica_dodici']) . '",
                        eventiDaEseguireTredici = "' . db_input($_POST['eventi_pratica_tredici']) . '",
						delimitatore = "' . $_POST['delimitatore'] . '"
					WHERE 
						id = "' . $id_insert . '"';
            db_query($query);


            $query = 'DELETE FROM acquisizione_config_query
					WHERE id_acquisizione = "' . $id_insert . '"';
            db_query($query);
            $query = 'DELETE FROM acquisizione_config_query_export
					WHERE id_acquisizione = "' . $id_insert . '"';
            db_query($query);

            $query = 'DELETE FROM acquisizione_config_campi
					WHERE id_acquisizione = "' . $id_insert . '"';
            db_query($query);


            $query = 'DELETE FROM acquisizione_config_query_eventi
					WHERE id_acquisizione = "' . $id_insert . '"';
            db_query($query);

            $array_pos = UnserializeDataForm($_POST['posizioni'])['posColonne'];
            $array_campi = UnserializeDataForm($_POST['campi'])['campoalias'];
            $array_tipi = UnserializeDataForm($_POST['tipi'])['campotipo'];
            $array_indici = UnserializeDataForm($_POST['indici'])['indice'];
            $array_ripeti = UnserializeDataForm($_POST['ripeti'])['ripeti_campo'];

            for ($i = 0; $i < count($array_pos); $i++) {

                if ($array_campi[$i] != '') {
                    $query = 'INSERT INTO acquisizione_config_campi
					SET pos = "' . db_input($array_pos[$i]) . '",
						nome = "' . db_input($array_campi[$i]) . '",
						tipo = "' . db_input($array_tipi[$i]) . '",
						indice = "' . db_input($array_indici[$i]) . '",
						ripeti_campo = "' . db_input($array_ripeti[$i]) . '",
						id_acquisizione = "' . $id_insert . '"';
                    db_query($query);
                }

            }

            $array_query = UnserializeDataForm($_POST['query'])['query'];


            for ($j = 0; $j < count($array_query); $j++) {

                if ($array_query[$j] != "") {
                    $query = 'INSERT INTO acquisizione_config_query
                        SET ordinamento="' . $j . '",
                            query="' . db_input($array_query[$j]) . '",
                            id_acquisizione="' . $id_insert . '"';
                    db_query($query);

                }
            }

            if (isset($_POST['queryExport']) && $_POST['queryExport'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_export
                        SET ordinamento=1,
                            query="' . db_input($_POST['queryExport']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }

            if (isset($_POST['queryEvento']) && $_POST['queryEvento'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=1,
                            query="' . db_input($_POST['queryEvento']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoDue']) && $_POST['queryEventoDue'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=2,
                            query="' . db_input($_POST['queryEventoDue']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoTre']) && $_POST['queryEventoTre'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=3,
                            query="' . db_input($_POST['queryEventoTre']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoQuattro']) && $_POST['queryEventoQuattro'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=4,
                            query="' . db_input($_POST['queryEventoQuattro']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoCinque']) && $_POST['queryEventoCinque'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=5,
                            query="' . db_input($_POST['queryEventoCinque']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoSei']) && $_POST['queryEventoSei'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=6,
                            query="' . db_input($_POST['queryEventoSei']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoSette']) && $_POST['queryEventoSette'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=7,
                            query="' . db_input($_POST['queryEventoSette']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoOtto']) && $_POST['queryEventoOtto'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=8,
                            query="' . db_input($_POST['queryEventoOtto']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoNove']) && $_POST['queryEventoNove'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=9,
                            query="' . db_input($_POST['queryEventoNove']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoDieci']) && $_POST['queryEventoDieci'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=10,
                            query="' . db_input($_POST['queryEventoDieci']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoUndici']) && $_POST['queryEventoUndici'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=11,
                            query="' . db_input($_POST['queryEventoUndici']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoDodici']) && $_POST['queryEventoDodici'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=12,
                            query="' . db_input($_POST['queryEventoDodici']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }
            if (isset($_POST['queryEventoTredici']) && $_POST['queryEventoTredici'] != "") {
                $query = 'INSERT INTO acquisizione_config_query_eventi
                        SET ordinamento=13,
                            query="' . db_input($_POST['queryEventoTredici']) . '",
                            id_acquisizione="' . $id_insert . '"';
                db_query($query);
            }

            echo "Configurazione " . db_input($_POST['id']) . " correttamente modiifcata";
        }
        break;
    case 'elimina-configurazione-acquisizione-config':
        {
            $query = 'DELETE FROM acquisizione_config_query
					WHERE id_acquisizione = "' . db_input($_POST['id']) . '"';
            db_query($query);
            $query = 'DELETE FROM acquisizione_config_query_export
					WHERE id_acquisizione = "' . db_input($_POST['id']) . '"';
            db_query($query);
            $query = 'DELETE FROM acquisizione_config_query_eventi
					WHERE id_acquisizione = "' . db_input($_POST['id']) . '"';
            db_query($query);

            $query = 'DELETE FROM acquisizione_config_campi
					WHERE id_acquisizione = "' . db_input($_POST['id']) . '"';
            db_query($query);

            $query = 'DELETE FROM template_acquisizione_config
					WHERE id = "' . db_input($_POST['id']) . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['id']) . " correttamente eliminata";
        }
        break;
    // PREFATTURE
    case 'salva-configurazione-acquisizione-prefatture':
        {
            $query = 'INSERT INTO template_acquisizione_prefatture
					SET id_mandante = "' . db_input($_POST['id_mandante']) . '",
						campi = "' . str_replace('campo%5B%5D=', '', str_replace('&campo%5B%5D=', ';', $_POST['campi'])) . '",
						posizioni = "' . str_replace('posizione%5B%5D=', '', str_replace('&posizione%5B%5D=', ';', $_POST['posizioni'])) . '",
						descrizione = "' . db_input($_POST['descrizione']) . '",
						funzione_pers = "' . str_replace('funzione_pers%5B%5D=', '', str_replace('&funzione_pers%5B%5D=', ';', $_POST['funzione_pers'])) . '",
						colonne_pers = "' . str_replace('colonne_pers%5B%5D=', '', str_replace('&colonne_pers%5B%5D=', ';', $_POST['colonne_pers'])) . '",
						tipo_file = "' . $_POST['tipo_file'] . '",
						riga = "' . $_POST['riga'] . '",
						delimitatore = "' . $_POST['delimitatore'] . '",
						lunghezzaPosizioni = "' . $_POST['lunghezzaPosizioni'] . '",
						formato_valuta = "' . $_POST['formato_valuta'] . '",
						formato_data = "' . $_POST['formato_data'] . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['descrizione']) . " correttamente salvata";
        }
        break;
    case 'modifica-configurazione-acquisizione-prefatture':
        {
            $query = 'UPDATE template_acquisizione_prefatture
					SET id_mandante = "' . db_input($_POST['id_mandante']) . '",
						campi = "' . str_replace('campo%5B%5D=', '', str_replace('&campo%5B%5D=', ';', $_POST['campi'])) . '",
						posizioni = "' . str_replace('posizione%5B%5D=', '', str_replace('&posizione%5B%5D=', ';', $_POST['posizioni'])) . '",
						funzione_pers = "' . str_replace('funzione_pers%5B%5D=', '', str_replace('&funzione_pers%5B%5D=', ';', $_POST['funzione_pers'])) . '",
						colonne_pers = "' . str_replace('colonne_pers%5B%5D=', '', str_replace('&colonne_pers%5B%5D=', ';', $_POST['colonne_pers'])) . '",
						tipo_file = "' . $_POST['tipo_file'] . '",
						riga = "' . $_POST['riga'] . '",
						delimitatore = "' . $_POST['delimitatore'] . '",
						lunghezzaPosizioni = "' . $_POST['lunghezzaPosizioni'] . '",
						formato_valuta = "' . $_POST['formato_valuta'] . '",
						formato_data = "' . $_POST['formato_data'] . '"
					WHERE 
						id = "' . db_input($_POST['id']) . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['id']) . " correttamente modiifcata";
        }
        break;
    case 'elimina-configurazione-acquisizione-prefatture':
        {
            $query = 'DELETE FROM template_acquisizione_prefatture
					WHERE id = "' . db_input($_POST['id']) . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['id']) . " correttamente eliminata";
        }
        break;
    // INDIRIZZI E RECAPITI
    case 'salva-configurazione-acquisizione-indirizzi-recapiti':
        {
            $query = 'INSERT INTO template_acquisizione_indirizzi_recapiti
					SET id_mandante = "' . db_input($_POST['id_mandante']) . '",
						tipo_file = "' . db_input($_POST['tipo_file']) . '",
						riga = "' . db_input($_POST['riga']) . '",
						delimitatore = "' . db_input($_POST['delimitatore']) . '",
						lunghezzaPosizioni = "' . db_input($_POST['lunghezzaPosizioni']) . '",
						formato_valuta = "' . db_input($_POST['formato_valuta']) . '",
						formato_data = "' . db_input($_POST['formato_data']) . '",
						evento_strutturato_indirizzo = "' . db_input($_POST['evento_strutturato_indirizzo']) . '",
						evento_strutturato_recapito = "' . db_input($_POST['evento_strutturato_recapito']) . '",
						indirizzo_predefinito = "' . db_input($_POST['indirizzo_predefinito']) . '",
						indirizzo_corrispondenza = "' . db_input($_POST['indirizzo_corrispondenza']) . '",
						tipo_indirizzo = "' . db_input($_POST['tipo_indirizzo']) . '",
						tipologie_indirizzo = "' . str_replace('address_type%5B%5D=', '', str_replace('&address_type%5B%5D=', ';', $_POST['tipologie_indirizzo'])) . '",
						tipologie_recapito = "' . str_replace('rec_type%5B%5D=', '', str_replace('&rec_type%5B%5D=', ';', $_POST['tipologie_recapito'])) . '",
						indirizzo_attivo = "' . db_input($_POST['indirizzo_attivo']) . '",
						sovrascrivi_indirizzo = "' . db_input($_POST['sovrascrivi_indirizzo']) . '",
						sovrascrivi_recapito = "' . db_input($_POST['sovrascrivi_recapito']) . '",
						recapito_predefinito = "' . db_input($_POST['recapito_predefinito']) . '",
						recapito_invio = "' . db_input($_POST['recapito_invio']) . '",
						tipo_recapito = "' . db_input($_POST['tipo_recapito']) . '",
						recapito_attivo = "' . db_input($_POST['recapito_attivo']) . '",
						fonte_predefinita = "' . db_input($_POST['fonte_predefinita']) . '",
						data_validita = "' . date('Y-m-d', strtotime($_POST['data_validita'])) . '",
						campi = "' . str_replace('campo%5B%5D=', '', str_replace('&campo%5B%5D=', ';', $_POST['campi'])) . '",
						posizioni = "' . str_replace('posizione%5B%5D=', '', str_replace('&posizione%5B%5D=', ';', $_POST['posizioni'])) . '",
						posizioni_indirizzo = "' . str_replace('posizione_indirizzo%5B%5D=', '', str_replace('&posizione_indirizzo%5B%5D=', ';', $_POST['posizioni_indirizzo'])) . '",
						funzione_pers = "' . str_replace('funzione_pers%5B%5D=', '', str_replace('&funzione_pers%5B%5D=', ';', $_POST['funzione_pers'])) . '",
						colonne_pers = "' . str_replace('colonne_pers%5B%5D=', '', str_replace('&colonne_pers%5B%5D=', ';', $_POST['colonne_pers'])) . '",
						descrizione = "' . db_input($_POST['descrizione']) . '",
						stato_sostituto = "' . db_input($_POST['stato_sostituto']) . '",
						stati_esclusi = "' . db_input($_POST['stati_esclusi']) . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['descrizione']) . " correttamente salvata";
        }
        break;
    case 'modifica-configurazione-acquisizione-indirizzi-recapiti':
        {
            $query = 'UPDATE template_acquisizione_indirizzi_recapiti
					SET id_mandante = "' . db_input($_POST['id_mandante']) . '",
						tipo_file = "' . db_input($_POST['tipo_file']) . '",
						riga = "' . db_input($_POST['riga']) . '",
						delimitatore = "' . db_input($_POST['delimitatore']) . '",
						lunghezzaPosizioni = "' . db_input($_POST['lunghezzaPosizioni']) . '",
						formato_valuta = "' . db_input($_POST['formato_valuta']) . '",
						formato_data = "' . db_input($_POST['formato_data']) . '",
						evento_strutturato_indirizzo = "' . db_input($_POST['evento_strutturato_indirizzo']) . '",
						evento_strutturato_recapito = "' . db_input($_POST['evento_strutturato_recapito']) . '",
						indirizzo_predefinito = "' . db_input($_POST['indirizzo_predefinito']) . '",
						indirizzo_corrispondenza = "' . db_input($_POST['indirizzo_corrispondenza']) . '",
						tipo_indirizzo = "' . db_input($_POST['tipo_indirizzo']) . '",
						tipologie_indirizzo = "' . str_replace('address_type%5B%5D=', '', str_replace('&address_type%5B%5D=', ';', $_POST['tipologie_indirizzo'])) . '",
						tipologie_recapito = "' . str_replace('rec_type%5B%5D=', '', str_replace('&rec_type%5B%5D=', ';', $_POST['tipologie_recapito'])) . '",
						indirizzo_attivo = "' . db_input($_POST['indirizzo_attivo']) . '",
						sovrascrivi_indirizzo = "' . db_input($_POST['sovrascrivi_indirizzo']) . '",
						sovrascrivi_recapito = "' . db_input($_POST['sovrascrivi_recapito']) . '",
						recapito_predefinito = "' . db_input($_POST['recapito_predefinito']) . '",
						recapito_invio = "' . db_input($_POST['recapito_invio']) . '",
						tipo_recapito = "' . db_input($_POST['tipo_recapito']) . '",
						recapito_attivo = "' . db_input($_POST['recapito_attivo']) . '",
						fonte_predefinita = "' . db_input($_POST['fonte_predefinita']) . '",
						data_validita = "' . date('Y-m-d', strtotime($_POST['data_validita'])) . '",
						campi = "' . str_replace('campo%5B%5D=', '', str_replace('&campo%5B%5D=', ';', $_POST['campi'])) . '",
						posizioni = "' . str_replace('posizione%5B%5D=', '', str_replace('&posizione%5B%5D=', ';', $_POST['posizioni'])) . '",
						posizioni_indirizzo = "' . str_replace('posizione_indirizzo%5B%5D=', '', str_replace('&posizione_indirizzo%5B%5D=', ';', $_POST['posizioni_indirizzo'])) . '",
						funzione_pers = "' . str_replace('funzione_pers%5B%5D=', '', str_replace('&funzione_pers%5B%5D=', ';', $_POST['funzione_pers'])) . '",
						colonne_pers = "' . str_replace('colonne_pers%5B%5D=', '', str_replace('&colonne_pers%5B%5D=', ';', $_POST['colonne_pers'])) . '",
						stato_sostituto = "' . db_input($_POST['stato_sostituto']) . '",
						stati_esclusi = "' . db_input($_POST['stati_esclusi']) . '"
					WHERE 
						id = "' . db_input($_POST['id']) . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['id']) . " correttamente modiifcata";
        }
        break;
    case 'elimina-configurazione-acquisizione-indirizzi-recapiti':
        {
            $query = 'DELETE FROM template_acquisizione_indirizzi_recapiti
					WHERE id = "' . db_input($_POST['id']) . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['id']) . " correttamente eliminata";
        }
        break;
    // COLLEGATI
    case 'salva-configurazione-acquisizione-collegati':
        {
            $query = 'INSERT INTO template_acquisizione_collegati
					SET id_mandante = "' . db_input($_POST['id_mandante']) . '",
						tipo_file = "' . db_input($_POST['tipo_file']) . '",
						riga = "' . db_input($_POST['riga']) . '",
						delimitatore = "' . db_input($_POST['delimitatore']) . '",
						lunghezzaPosizioni = "' . db_input($_POST['lunghezzaPosizioni']) . '",
						formato_valuta = "' . db_input($_POST['formato_valuta']) . '",
						formato_data = "' . db_input($_POST['formato_data']) . '",
						evento_strutturato_indirizzo = "' . db_input($_POST['evento_strutturato_indirizzo']) . '",
						evento_strutturato_recapito = "' . db_input($_POST['evento_strutturato_recapito']) . '",
						indirizzo_predefinito = "' . db_input($_POST['indirizzo_predefinito']) . '",
						indirizzo_corrispondenza = "' . db_input($_POST['indirizzo_corrispondenza']) . '",
						tipologie_collegato_pratica = "' . str_replace('linked_type%5B%5D=', '', str_replace('&linked_type%5B%5D=', ';', $_POST['tipologie_collegato_pratica'])) . '",
						tipologie_collegato_anagrafica = "' . str_replace('linked_user_type%5B%5D=', '', str_replace('&linked_user_type%5B%5D=', ';', $_POST['tipologie_collegato_anagrafica'])) . '",
						tipo_indirizzo = "' . db_input($_POST['tipo_indirizzo']) . '",
						tipologie_indirizzo = "' . str_replace('address_type%5B%5D=', '', str_replace('&address_type%5B%5D=', ';', $_POST['tipologie_indirizzo'])) . '",
						tipologie_recapito = "' . str_replace('rec_type%5B%5D=', '', str_replace('&rec_type%5B%5D=', ';', $_POST['tipologie_recapito'])) . '",
						indirizzo_attivo = "' . db_input($_POST['indirizzo_attivo']) . '",
						recapito_predefinito = "' . db_input($_POST['recapito_predefinito']) . '",
						recapito_invio = "' . db_input($_POST['recapito_invio']) . '",
						tipo_recapito = "' . db_input($_POST['tipo_recapito']) . '",
						recapito_attivo = "' . db_input($_POST['recapito_attivo']) . '",
						fonte_predefinita = "' . db_input($_POST['fonte_predefinita']) . '",
						data_validita = "' . date('Y-m-d', strtotime($_POST['data_validita'])) . '",
						campi = "' . str_replace('campo%5B%5D=', '', str_replace('&campo%5B%5D=', ';', $_POST['campi'])) . '",
						posizioni = "' . str_replace('posizione%5B%5D=', '', str_replace('&posizione%5B%5D=', ';', $_POST['posizioni'])) . '",
						posizioni_indirizzo = "' . str_replace('posizione_indirizzo%5B%5D=', '', str_replace('&posizione_indirizzo%5B%5D=', ';', $_POST['posizioni_indirizzo'])) . '",
						funzione_pers = "' . str_replace('funzione_pers%5B%5D=', '', str_replace('&funzione_pers%5B%5D=', ';', $_POST['funzione_pers'])) . '",
						colonne_pers = "' . str_replace('colonne_pers%5B%5D=', '', str_replace('&colonne_pers%5B%5D=', ';', $_POST['colonne_pers'])) . '",
						descrizione = "' . db_input($_POST['descrizione']) . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['descrizione']) . " correttamente salvata";
        }
        break;
    case 'modifica-configurazione-acquisizione-collegati':
        {
            $query = 'UPDATE template_acquisizione_collegati
					SET id_mandante = "' . db_input($_POST['id_mandante']) . '",
						tipo_file = "' . db_input($_POST['tipo_file']) . '",
						riga = "' . db_input($_POST['riga']) . '",
						delimitatore = "' . db_input($_POST['delimitatore']) . '",
						lunghezzaPosizioni = "' . db_input($_POST['lunghezzaPosizioni']) . '",
						formato_valuta = "' . db_input($_POST['formato_valuta']) . '",
						formato_data = "' . db_input($_POST['formato_data']) . '",
						evento_strutturato_indirizzo = "' . db_input($_POST['evento_strutturato_indirizzo']) . '",
						evento_strutturato_recapito = "' . db_input($_POST['evento_strutturato_recapito']) . '",
						indirizzo_predefinito = "' . db_input($_POST['indirizzo_predefinito']) . '",
						indirizzo_corrispondenza = "' . db_input($_POST['indirizzo_corrispondenza']) . '",
						tipologie_collegato_pratica = "' . str_replace('linked_type%5B%5D=', '', str_replace('&linked_type%5B%5D=', ';', $_POST['tipologie_collegato_pratica'])) . '",
						tipologie_collegato_anagrafica = "' . str_replace('linked_user_type%5B%5D=', '', str_replace('&linked_user_type%5B%5D=', ';', $_POST['tipologie_collegato_anagrafica'])) . '",
						tipo_indirizzo = "' . db_input($_POST['tipo_indirizzo']) . '",
						tipo_indirizzo = "' . db_input($_POST['tipo_indirizzo']) . '",
						tipologie_indirizzo = "' . str_replace('address_type%5B%5D=', '', str_replace('&address_type%5B%5D=', ';', $_POST['tipologie_indirizzo'])) . '",
						tipologie_recapito = "' . str_replace('rec_type%5B%5D=', '', str_replace('&rec_type%5B%5D=', ';', $_POST['tipologie_recapito'])) . '",
						indirizzo_attivo = "' . db_input($_POST['indirizzo_attivo']) . '",
						recapito_predefinito = "' . db_input($_POST['recapito_predefinito']) . '",
						recapito_invio = "' . db_input($_POST['recapito_invio']) . '",
						tipo_recapito = "' . db_input($_POST['tipo_recapito']) . '",
						recapito_attivo = "' . db_input($_POST['recapito_attivo']) . '",
						fonte_predefinita = "' . db_input($_POST['fonte_predefinita']) . '",
						data_validita = "' . date('Y-m-d', strtotime($_POST['data_validita'])) . '",
						campi = "' . str_replace('campo%5B%5D=', '', str_replace('&campo%5B%5D=', ';', $_POST['campi'])) . '",
						posizioni = "' . str_replace('posizione%5B%5D=', '', str_replace('&posizione%5B%5D=', ';', $_POST['posizioni'])) . '",
						posizioni_indirizzo = "' . str_replace('posizione_indirizzo%5B%5D=', '', str_replace('&posizione_indirizzo%5B%5D=', ';', $_POST['posizioni_indirizzo'])) . '",
						funzione_pers = "' . str_replace('funzione_pers%5B%5D=', '', str_replace('&funzione_pers%5B%5D=', ';', $_POST['funzione_pers'])) . '",
						colonne_pers = "' . str_replace('colonne_pers%5B%5D=', '', str_replace('&colonne_pers%5B%5D=', ';', $_POST['colonne_pers'])) . '"
					WHERE 
						id = "' . db_input($_POST['id']) . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['id']) . " correttamente modiifcata";
        }
        break;
    case 'elimina-configurazione-acquisizione-collegati':
        {
            $query = 'DELETE FROM template_acquisizione_collegati
					WHERE id = "' . db_input($_POST['id']) . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['id']) . " correttamente eliminata";
        }
        break;

    // COLLEGATI
    case 'salva-configurazione-collegati':
        {
            $query = 'INSERT INTO template_acq_collegati
					SET id_mandante = "' . db_input($_POST['id_mandante']) . '",
						tipo_file = "' . db_input($_POST['tipo_file']) . '",
						riga = "' . db_input($_POST['riga']) . '",
						delimitatore = "' . db_input($_POST['delimitatore']) . '",
						lunghezzaPosizioni = "' . db_input($_POST['lunghezzaPosizioni']) . '",
						formato_valuta = "' . db_input($_POST['formato_valuta']) . '",
						formato_data = "' . db_input($_POST['formato_data']) . '",
						evento_strutturato_indirizzo = "' . db_input($_POST['evento_strutturato_indirizzo']) . '",
						evento_strutturato_recapito = "' . db_input($_POST['evento_strutturato_recapito']) . '",
						indirizzo_predefinito = "' . db_input($_POST['indirizzo_predefinito']) . '",
						indirizzo_corrispondenza = "' . db_input($_POST['indirizzo_corrispondenza']) . '",
						tipologie_collegato_pratica = "' . str_replace('linked_type%5B%5D=', '', str_replace('&linked_type%5B%5D=', ';', $_POST['tipologie_collegato_pratica'])) . '",
						tipologie_collegato_anagrafica = "' . str_replace('linked_user_type%5B%5D=', '', str_replace('&linked_user_type%5B%5D=', ';', $_POST['tipologie_collegato_anagrafica'])) . '",
						tipo_indirizzo = "' . db_input($_POST['tipo_indirizzo']) . '",
						tipologie_indirizzo = "' . str_replace('address_type%5B%5D=', '', str_replace('&address_type%5B%5D=', ';', $_POST['tipologie_indirizzo'])) . '",
						tipologie_recapito = "' . str_replace('rec_type%5B%5D=', '', str_replace('&rec_type%5B%5D=', ';', $_POST['tipologie_recapito'])) . '",
						indirizzo_attivo = "' . db_input($_POST['indirizzo_attivo']) . '",
						recapito_predefinito = "' . db_input($_POST['recapito_predefinito']) . '",
						recapito_invio = "' . db_input($_POST['recapito_invio']) . '",
						tipo_recapito = "' . db_input($_POST['tipo_recapito']) . '",
						recapito_attivo = "' . db_input($_POST['recapito_attivo']) . '",
						fonte_predefinita = "' . db_input($_POST['fonte_predefinita']) . '",
						data_validita = "' . date('Y-m-d', strtotime($_POST['data_validita'])) . '",
						campi = "' . str_replace('campo%5B%5D=', '', str_replace('&campo%5B%5D=', ';', $_POST['campi'])) . '",
						posizioni = "' . str_replace('posizione%5B%5D=', '', str_replace('&posizione%5B%5D=', ';', $_POST['posizioni'])) . '",
						posizioni_indirizzo = "' . str_replace('posizione_indirizzo%5B%5D=', '', str_replace('&posizione_indirizzo%5B%5D=', ';', $_POST['posizioni_indirizzo'])) . '",
						funzione_pers = "' . str_replace('funzione_pers%5B%5D=', '', str_replace('&funzione_pers%5B%5D=', ';', $_POST['funzione_pers'])) . '",
						colonne_pers = "' . str_replace('colonne_pers%5B%5D=', '', str_replace('&colonne_pers%5B%5D=', ';', $_POST['colonne_pers'])) . '",
						descrizione = "' . db_input($_POST['descrizione']) . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['descrizione']) . " correttamente salvata";
        }
        break;
    case 'modifica-configurazione-collegati':
        {
            $query = 'UPDATE template_acq_collegati
					SET id_mandante = "' . db_input($_POST['id_mandante']) . '",
						tipo_file = "' . db_input($_POST['tipo_file']) . '",
						riga = "' . db_input($_POST['riga']) . '",
						delimitatore = "' . db_input($_POST['delimitatore']) . '",
						lunghezzaPosizioni = "' . db_input($_POST['lunghezzaPosizioni']) . '",
						formato_valuta = "' . db_input($_POST['formato_valuta']) . '",
						formato_data = "' . db_input($_POST['formato_data']) . '",
						evento_strutturato_indirizzo = "' . db_input($_POST['evento_strutturato_indirizzo']) . '",
						evento_strutturato_recapito = "' . db_input($_POST['evento_strutturato_recapito']) . '",
						indirizzo_predefinito = "' . db_input($_POST['indirizzo_predefinito']) . '",
						indirizzo_corrispondenza = "' . db_input($_POST['indirizzo_corrispondenza']) . '",
						tipologie_collegato_pratica = "' . str_replace('linked_type%5B%5D=', '', str_replace('&linked_type%5B%5D=', ';', $_POST['tipologie_collegato_pratica'])) . '",
						tipologie_collegato_anagrafica = "' . str_replace('linked_user_type%5B%5D=', '', str_replace('&linked_user_type%5B%5D=', ';', $_POST['tipologie_collegato_anagrafica'])) . '",
						tipo_indirizzo = "' . db_input($_POST['tipo_indirizzo']) . '",
						tipo_indirizzo = "' . db_input($_POST['tipo_indirizzo']) . '",
						tipologie_indirizzo = "' . str_replace('address_type%5B%5D=', '', str_replace('&address_type%5B%5D=', ';', $_POST['tipologie_indirizzo'])) . '",
						tipologie_recapito = "' . str_replace('rec_type%5B%5D=', '', str_replace('&rec_type%5B%5D=', ';', $_POST['tipologie_recapito'])) . '",
						indirizzo_attivo = "' . db_input($_POST['indirizzo_attivo']) . '",
						recapito_predefinito = "' . db_input($_POST['recapito_predefinito']) . '",
						recapito_invio = "' . db_input($_POST['recapito_invio']) . '",
						tipo_recapito = "' . db_input($_POST['tipo_recapito']) . '",
						recapito_attivo = "' . db_input($_POST['recapito_attivo']) . '",
						fonte_predefinita = "' . db_input($_POST['fonte_predefinita']) . '",
						data_validita = "' . date('Y-m-d', strtotime($_POST['data_validita'])) . '",
						campi = "' . str_replace('campo%5B%5D=', '', str_replace('&campo%5B%5D=', ';', $_POST['campi'])) . '",
						posizioni = "' . str_replace('posizione%5B%5D=', '', str_replace('&posizione%5B%5D=', ';', $_POST['posizioni'])) . '",
						posizioni_indirizzo = "' . str_replace('posizione_indirizzo%5B%5D=', '', str_replace('&posizione_indirizzo%5B%5D=', ';', $_POST['posizioni_indirizzo'])) . '",
						funzione_pers = "' . str_replace('funzione_pers%5B%5D=', '', str_replace('&funzione_pers%5B%5D=', ';', $_POST['funzione_pers'])) . '",
						colonne_pers = "' . str_replace('colonne_pers%5B%5D=', '', str_replace('&colonne_pers%5B%5D=', ';', $_POST['colonne_pers'])) . '"
					WHERE 
						id = "' . db_input($_POST['id']) . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['id']) . " correttamente modiifcata";
        }
        break;
    case 'elimina-configurazione-collegati':
        {
            $query = 'DELETE FROM template_acq_collegati
					WHERE id = "' . db_input($_POST['id']) . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['id']) . " correttamente eliminata";
        }
        break;
    // NOTE
    case 'salva-configurazione-acquisizione-note':
        {
            $query = 'INSERT INTO template_acquisizione_note
					SET id_mandante = "' . db_input($_POST['id_mandante']) . '",
						campi = "' . str_replace('campo%5B%5D=', '', str_replace('&campo%5B%5D=', ';', $_POST['campi'])) . '",
						posizioni = "' . str_replace('posizione%5B%5D=', '', str_replace('&posizione%5B%5D=', ';', $_POST['posizioni'])) . '",
						descrizione = "' . db_input($_POST['descrizione']) . '",
						funzione_pers = "' . str_replace('funzione_pers%5B%5D=', '', str_replace('&funzione_pers%5B%5D=', ';', $_POST['funzione_pers'])) . '",
						colonne_pers = "' . str_replace('colonne_pers%5B%5D=', '', str_replace('&colonne_pers%5B%5D=', ';', $_POST['colonne_pers'])) . '",
						tipo_file = "' . $_POST['tipo_file'] . '",
						riga = "' . $_POST['riga'] . '",
						delimitatore = "' . $_POST['delimitatore'] . '",
						lunghezzaPosizioni = "' . $_POST['lunghezzaPosizioni'] . '",
						formato_valuta = "' . $_POST['formato_valuta'] . '",
						formato_data = "' . $_POST['formato_data'] . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['descrizione']) . " correttamente salvata";
        }
        break;
    case 'modifica-configurazione-acquisizione-note':
        {
            $query = 'UPDATE template_acquisizione_note
					SET id_mandante = "' . db_input($_POST['id_mandante']) . '",
						campi = "' . str_replace('campo%5B%5D=', '', str_replace('&campo%5B%5D=', ';', $_POST['campi'])) . '",
						posizioni = "' . str_replace('posizione%5B%5D=', '', str_replace('&posizione%5B%5D=', ';', $_POST['posizioni'])) . '",
						funzione_pers = "' . str_replace('funzione_pers%5B%5D=', '', str_replace('&funzione_pers%5B%5D=', ';', $_POST['funzione_pers'])) . '",
						colonne_pers = "' . str_replace('colonne_pers%5B%5D=', '', str_replace('&colonne_pers%5B%5D=', ';', $_POST['colonne_pers'])) . '",
						tipo_file = "' . $_POST['tipo_file'] . '",
						riga = "' . $_POST['riga'] . '",
						delimitatore = "' . $_POST['delimitatore'] . '",
						lunghezzaPosizioni = "' . $_POST['lunghezzaPosizioni'] . '",
						formato_valuta = "' . $_POST['formato_valuta'] . '",
						formato_data = "' . $_POST['formato_data'] . '"
					WHERE 
						id = "' . db_input($_POST['id']) . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['id']) . " correttamente modiifcata";
        }
        break;
    case 'elimina-configurazione-acquisizione-note':
        {
            $query = 'DELETE FROM template_acquisizione_note
					WHERE id = "' . db_input($_POST['id']) . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['id']) . " correttamente eliminata";
        }
        break;
    // ALLEGATI
    case 'salva-configurazione-acquisizione-allegati':
        {
            $query = 'INSERT INTO template_acquisizione_allegati
					SET id_mandante = "' . db_input($_POST['id_mandante']) . '",
						campo = "' . db_input($_POST['campo']) . '",
						descrizione = "' . db_input($_POST['descrizione']) . '",
						posizione = "' . db_input($_POST['posizione']) . '",
						separatore = "' . db_input($_POST['separatore']) . '",
						offset = "' . db_input($_POST['offset']) . '",
						lunghezza = "' . db_input($_POST['lunghezza']) . '",
						classe = "' . db_input($_POST['classe']) . '",
						tipo_file = "' . db_input($_POST['tipo_file']) . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['descrizione']) . " correttamente salvata";
        }
        break;
    case 'modifica-configurazione-acquisizione-allegati':
        {
            $query = 'UPDATE template_acquisizione_allegati
					SET id_mandante = "' . db_input($_POST['id_mandante']) . '",
						campo = "' . db_input($_POST['campo']) . '",
						posizione = "' . db_input($_POST['posizione']) . '",
						separatore = "' . db_input($_POST['separatore']) . '",
						offset = "' . db_input($_POST['offset']) . '",
						lunghezza = "' . db_input($_POST['lunghezza']) . '",
						classe = "' . db_input($_POST['classe']) . '",
						tipo_file = "' . db_input($_POST['tipo_file']) . '"
					WHERE 
						id = "' . db_input($_POST['id']) . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['id']) . " correttamente modiifcata";
        }
        break;
    case 'elimina-configurazione-acquisizione-allegati':
        {
            $query = 'DELETE FROM template_acquisizione_allegati
					WHERE id = "' . db_input($_POST['id']) . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['id']) . " correttamente eliminata";
        }
        break;
    // EXPORT INCASSI
    case 'salva-configurazione-export':
        {
            $query = 'INSERT INTO template_esportazione
					SET id_mandante = "' . db_input($_POST['id_mandante']) . '",
						campi = "' . str_replace('campo%5B%5D=', '', str_replace('&campo%5B%5D=', ';', $_POST['campi'])) . '",
						descrizione = "' . db_input($_POST['descrizione']) . '",
						tipo_file = "' . $_POST['tipo_file'] . '",
						riga = "' . $_POST['riga'] . '",
						delimitatore = "' . $_POST['delimitatore'] . '",
						lunghezzaPosizioni = "' . $_POST['lunghezzaPosizioni'] . '",
						formato_valuta = "' . $_POST['formato_valuta'] . '",
						formato_data = "' . $_POST['formato_data'] . '",
						distinta = "' . $_POST['distinta'] . '",
						nome_file = "' . $_POST['nome_file'] . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['descrizione']) . " correttamente salvata";
        }
        break;
    case 'modifica-configurazione-export':
        {
            $query = 'UPDATE template_esportazione
					SET id_mandante = "' . db_input($_POST['id_mandante']) . '",
						campi = "' . str_replace('campo%5B%5D=', '', str_replace('&campo%5B%5D=', ';', $_POST['campi'])) . '",
						tipo_file = "' . $_POST['tipo_file'] . '",
						riga = "' . $_POST['riga'] . '",
						delimitatore = "' . $_POST['delimitatore'] . '",
						lunghezzaPosizioni = "' . $_POST['lunghezzaPosizioni'] . '",
						formato_valuta = "' . $_POST['formato_valuta'] . '",
						formato_data = "' . $_POST['formato_data'] . '",
						distinta = "' . $_POST['distinta'] . '",
						nome_file = "' . $_POST['nome_file'] . '"
					WHERE 
						id = "' . db_input($_POST['id']) . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['id']) . " correttamente modiifcata";
        }
        break;
    // EXPORT NOTE
    case 'salva-configurazione-export-note':
        {
            $query = 'INSERT INTO template_esportazione_note
					SET id_mandante = "' . db_input($_POST['id_mandante']) . '",
						campi = "' . db_input(str_replace('campo%5B%5D=', '', str_replace('&campo%5B%5D=', ';', $_POST['campi']))) . '",
						descrizione = "' . db_input($_POST['descrizione']) . '",
						tipo_file = "' . db_input($_POST['tipo_file']) . '",
						riga = "' . db_input($_POST['riga']) . '",
						delimitatore = "' . db_input($_POST['delimitatore']) . '",
						lunghezzaPosizioni = "' . db_input($_POST['lunghezzaPosizioni']) . '",
						formato_valuta = "' . db_input($_POST['formato_valuta']) . '",
						formato_data = "' . db_input($_POST['formato_data']) . '",
						soloNote = "' . db_input($_POST['soloNote']) . '",
						noteEscluse = "' . db_input($_POST['noteEscluse']) . '",
						queryEnabled = "' . db_input($_POST['switchQuery']) . '",
						query = "' . db_input($_POST['txtQuery']) . '",
						nome_file = "' . db_input($_POST['nome_file']) . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['descrizione']) . " correttamente salvata";
        }
        break;
    case 'modifica-configurazione-export-note':
        {
            $query = 'UPDATE template_esportazione_note
					SET id_mandante = "' . db_input($_POST['id_mandante']) . '",
						campi = "' . db_input(str_replace('campo%5B%5D=', '', str_replace('&campo%5B%5D=', ';', $_POST['campi']))) . '",
						tipo_file = "' . db_input($_POST['tipo_file']) . '",
						riga = "' . db_input($_POST['riga']) . '",
						delimitatore = "' . db_input($_POST['delimitatore']) . '",
						lunghezzaPosizioni = "' . db_input($_POST['lunghezzaPosizioni']) . '",
						formato_valuta = "' . db_input($_POST['formato_valuta']) . '",
						formato_data = "' . db_input($_POST['formato_data']) . '",
						soloNote = "' . db_input($_POST['soloNote']) . '",
						noteEscluse = "' . db_input($_POST['noteEscluse']) . '",
						queryEnabled = "' . db_input($_POST['switchQuery']) . '",
						query = "' . db_input($_POST['txtQuery']) . '",
						nome_file = "' . db_input($_POST['nome_file']) . '"
					WHERE 
						id = "' . db_input($_POST['id']) . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['id']) . " correttamente modiifcata";
        }
        break;
    // EXPORT ESITI
    case 'salva-configurazione-export-esiti':
        {
            $query = 'INSERT INTO template_esportazione_esiti
					SET id_mandante = "' . db_input($_POST['id_mandante']) . '",
						campi = "' . str_replace('campo%5B%5D=', '', str_replace('&campo%5B%5D=', ';', $_POST['campi'])) . '",
						descrizione = "' . db_input($_POST['descrizione']) . '",
						tipo_file = "' . $_POST['tipo_file'] . '",
						riga = "' . $_POST['riga'] . '",
						delimitatore = "' . $_POST['delimitatore'] . '",
						lunghezzaPosizioni = "' . $_POST['lunghezzaPosizioni'] . '",
						formato_valuta = "' . $_POST['formato_valuta'] . '",
						formato_data = "' . $_POST['formato_data'] . '",
						queryEnabled = "' . $_POST['switchQuery'] . '",
						query = "' . $_POST['txtQuery'] . '",
						nome_file = "' . $_POST['nome_file'] . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['descrizione']) . " correttamente salvata";
        }
        break;
    case 'modifica-configurazione-export-esiti':
        {
            $query = 'UPDATE template_esportazione_esiti 
					SET id_mandante = "' . db_input($_POST['id_mandante']) . '",
						campi = "' . str_replace('campo%5B%5D=', '', str_replace('&campo%5B%5D=', ';', $_POST['campi'])) . '",
						tipo_file = "' . $_POST['tipo_file'] . '",
						riga = "' . $_POST['riga'] . '",
						delimitatore = "' . $_POST['delimitatore'] . '",
						lunghezzaPosizioni = "' . $_POST['lunghezzaPosizioni'] . '",
						formato_valuta = "' . $_POST['formato_valuta'] . '",
						formato_data = "' . $_POST['formato_data'] . '",
						queryEnabled = "' . $_POST['switchQuery'] . '",
						query = "' . $_POST['txtQuery'] . '", 
						nome_file = "' . $_POST['nome_file'] . '" 
					WHERE 
						id = "' . db_input($_POST['id']) . '"';
            db_query($query);

            echo "Configurazione " . db_input($_POST['id']) . " correttamente modiifcata";
        }
        break;
    //===========================================================================================================================================//
    //============================================== CALCOLO COMPENSI FATTURATO POTENZIALE ======================================================//
    //===========================================================================================================================================//
    case 'inserisci-calcolo-compensi-fatturato-potenziale':
        {

            $id_utente = $_SESSION['user_admin_id'];
            $dati_export = $_POST['dati_export'];

            $query = 'INSERT INTO calcolo_compenso_fatturato_potenziale
					SET id_utente = "' . db_input($id_utente) . '",
						dati_export = "' . db_input($dati_export) . '",
						azienda_1 = "' . db_input($_POST['azienda_1']) . '",
						azienda_2 = "' . db_input($_POST['azienda_2']) . '",
						azienda_3 = "' . db_input($_POST['azienda_3']) . '",
						azienda_4 = "' . db_input($_POST['azienda_4']) . '",
						azienda_5 = "' . db_input($_POST['azienda_5']) . '",
						capoarea_1 = "' . db_input($_POST['capoarea_1']) . '",
						capoarea_2 = "' . db_input($_POST['capoarea_2']) . '",
						capoarea_3 = "' . db_input($_POST['capoarea_3']) . '",
						capoarea_4 = "' . db_input($_POST['capoarea_4']) . '",
						capoarea_5 = "' . db_input($_POST['capoarea_5']) . '",
						esattore_1 = "' . db_input($_POST['esattore_1']) . '",
						esattore_2 = "' . db_input($_POST['esattore_2']) . '",
						esattore_3 = "' . db_input($_POST['esattore_3']) . '",
						esattore_4 = "' . db_input($_POST['esattore_4']) . '",
						esattore_5 = "' . db_input($_POST['esattore_5']) . '",
						phc_1 = "' . db_input($_POST['phc_1']) . '",
						phc_2 = "' . db_input($_POST['phc_2']) . '",
						phc_3 = "' . db_input($_POST['phc_3']) . '",
						phc_4 = "' . db_input($_POST['phc_4']) . '",
						phc_5 = "' . db_input($_POST['phc_5']) . '",
						tutor_1 = "' . db_input($_POST['tutor_1']) . '",
						tutor_2 = "' . db_input($_POST['tutor_2']) . '",
						tutor_3 = "' . db_input($_POST['tutor_3']) . '",
						tutor_4 = "' . db_input($_POST['tutor_4']) . '",
						tutor_5 = "' . db_input($_POST['tutor_5']) . '",
						venditore_1 = "' . db_input($_POST['venditore_1']) . '",
						venditore_2 = "' . db_input($_POST['venditore_2']) . '",
						venditore_3 = "' . db_input($_POST['venditore_3']) . '",
						venditore_4 = "' . db_input($_POST['venditore_4']) . '",
						venditore_5 = "' . db_input($_POST['venditore_5']) . '",
						debitore_1 = "' . db_input($_POST['debitore_1']) . '",
						debitore_2 = "' . db_input($_POST['debitore_2']) . '",
						debitore_3 = "' . db_input($_POST['debitore_3']) . '",
						debitore_4 = "' . db_input($_POST['debitore_4']) . '",
						debitore_5 = "' . db_input($_POST['debitore_5']) . '"';
            $ris = db_query($query);

            echo 'PREVISIONE COMPENSI CORRETTAMENTE SALVATA';
        }
        break;
    case 'lancia-query-compensi-maturati':
        {
            $query = $_POST['query'];

            if (strpos(strtolower($query), 'delete') !== false || strpos(strtolower($query), 'alter') !== false || strpos(strtolower($query), 'table') !== false || strpos(strtolower($query), 'drop') !== false || strpos(strtolower($query), 'update') !== false || strpos(strtolower($query), 'truncate') !== false) {
                return false;
            } else {
                $ris = db_query($query);
                $row = db_fetch_array_assoc($ris);

                print_r(json_encode($row));
            }
        }
        break;
    //===========================================================================================================================================//
    //============================================================ AFFIDAMENTI ==================================================================//
    //===========================================================================================================================================//
    case 'inserisci-affidamento':
        {
            $query = 'INSERT INTO affidamenti
					SET data_affidamento = "' . db_input(date('Y-m-d', strtotime($_POST['data_affidamento']))) . '",
						id_collector = "' . db_input($_POST['id_collector']) . '",
						phc = "' . db_input($_POST['phc']) . '",
						id_lotto = "' . db_input($_POST['id_lotto']) . '",
						data_creazione = "' . date('Y-m-d H:i:s') . '"';

            $ris = db_query($query);

            $id_lotto_affidamento = mysql_insert_id();

            $query = 'UPDATE pratiche
					SET id_lotto_affidamento = "' . $id_lotto_affidamento . '",
						id_collector = "' . db_input($_POST['id_collector']) . '"
					WHERE id_lotto_mandante = "' . db_input($_POST['id_lotto']) . '"';

            $ris = db_query($query);

            $query = 'SELECT id FROM pratiche WHERE id_lotto_affidamento = "' . $id_lotto_affidamento . '"';
            $ris = db_query($query);
            while ($row = mysql_fetch_array($ris)) {
                $query_inserimento_storico_pratica = 'INSERT INTO pratiche_affidamenti
													SET id_pratica = "' . $row['id'] . '",
														id_affidamento = "' . $id_lotto_affidamento . '"';
                $ris_inserimento_storico_pratica = db_query($query_inserimento_storico_pratica);
            }

        }
        break;
    case 'modifica-affidamento':
        {
            $query = 'UPDATE affidamenti
					SET data_affidamento = "' . db_input(date('Y-m-d', strtotime($_POST['data_affidamento']))) . '",
						id_collector = "' . db_input($_POST['id_collector']) . '",
						phc = "' . db_input($_POST['phc']) . '",
						id_lotto = "' . db_input($_POST['id_lotto']) . '",
						data_modifica = "' . date('Y-m-d H:i:s') . '"
					WHERE id = "' . db_input($_POST['id']) . '"';

            $ris = db_query($query);

            $query = 'UPDATE pratiche
					SET id_lotto_affidamento = "' . db_input($_POST['id']) . '",
						id_collector = "' . db_input($_POST['id_collector']) . '"
					WHERE id_lotto_mandante = "' . db_input($_POST['id_lotto']) . '"';

            $ris = db_query($query);
        }
        break;
    case 'elimina-affidamento':
        {
            $query = 'DELETE FROM affidamenti WHERE id = "' . db_input($_POST['id']) . '"';

            $ris = db_query($query) or die(db_error());

            $query = 'UPDATE pratiche SET id_collector = NULL, id_lotto_affidamento = NULL WHERE id IN ( SELECT id_pratica FROM pratiche_affidamenti WHERE id_affidamento = "' . db_input($_POST['id']) . '" )';

            $ris = db_query($query) or die(db_error());

            $query = 'DELETE FROM pratiche_affidamenti WHERE id_affidamento = "' . db_input($_POST['id']) . '"';

            $ris = db_query($query) or die(db_error());
        }
        break;
    case 'elimina-affidamento-legale':
        {
            $query = 'DELETE FROM affidamenti_legale WHERE id = "' . db_input($_POST['id']) . '"';

            $ris = db_query($query) or die(db_error());

            $query = 'UPDATE pratiche SET id_legale = NULL,id_lotto_affidamento_legale=NULL WHERE id IN ( SELECT id_pratica FROM pratiche_affidamenti_legale WHERE id_affidamento = "' . db_input($_POST['id']) . '" )';

            $ris = db_query($query) or die(db_error());

            $query = 'DELETE FROM pratiche_affidamenti_legale WHERE id_affidamento = "' . db_input($_POST['id']) . '"';

            $ris = db_query($query) or die(db_error());
        }
        break;
    case 'elimina-affidamento-tutor':
        {
            $query = 'DELETE FROM affidamenti_tutor WHERE id = "' . db_input($_POST['id']) . '"';

            $ris = db_query($query) or die(db_error());

            $query = 'UPDATE pratiche SET id_tutor = NULL,id_lotto_affidamento_tutor=NULL WHERE id IN ( SELECT id_pratica FROM pratiche_affidamenti_tutor WHERE id_affidamento = "' . db_input($_POST['id']) . '" )';

            $ris = db_query($query) or die(db_error());

            $query = 'DELETE FROM pratiche_affidamenti_tutor WHERE id_affidamento = "' . db_input($_POST['id']) . '"';

            $ris = db_query($query) or die(db_error());
        }
        break;
    //===========================================================================================================================================//
    //======================================================= GENERAZIONE AFFIDAMENTI ===========================================================//
    //===========================================================================================================================================//
    case 'affidamenti-sposta-in-remidabox':
        {
            $pratiche = explode(',', $_POST['p']);

            $pratiche_elaborate = 0;

            for ($i = 0; $i < count($pratiche); $i++) {
                aggiungiPraticaInBox($pratiche[$i]);
                $pratiche_elaborate++;
            }

            echo $pratiche_elaborate;
        }
        break;
    case 'generazione-affidamento':
        {
            //db_query('DELETE FROM affidamenti WHERE id > 28');
            //db_query('DELETE FROM pratiche_affidamenti WHERE id > 29');
            //db_query('UPDATE pratiche SET stato_corrente = 1, area_corrente = 2, id_lotto_affidamento = NULL, id_collector = NULL WHERE id = 43283');

            //$collectors = strpos($_POST['c'],',') > 0 ? explode(',',$_POST['c']) : array($_POST['c']);
            $collectors = explode(',', trim($_POST['c'], ','));
            //$pratiche = strpos($_POST['p'],',') > 0 ? explode(',',$_POST['p']) : array($_POST['p']);
            $pratiche = explode(',', trim($_POST['p']));

            $team = isset($_POST['team']) ? $_POST['team'] : '0';

            $data_affidamento = date('Y-m-d');

            $affidamenti_creati = 0;
            $pratiche_elaborate = 0;

            $array_id_affidamenti = array();
            $array_affidamenti = array();
            $elenco_pratiche_affidate_successo = '';

            for ($i = 0; $i < count($collectors); $i++) {
                if ($collectors[$i] != '' && $collectors[$i] > 0) {
                    $query_phc = 'SELECT id_utente
								FROM utente
								WHERE (gruppi_base = "6"
										OR gruppi_base LIKE "%,6,%"
										OR gruppi_base LIKE "%,6"
										OR gruppi_base LIKE "6,%"
										OR gruppi_base = "7"
										OR gruppi_base LIKE "%,7,%"
										OR gruppi_base LIKE "%,7"
										OR gruppi_base LIKE "7,%")
								AND id_utente = "' . $collectors[$i] . '"';
                    if (mysql_num_rows(db_query($query_phc)) > 0) {
                        $phc = 0;
                    } else {
                        $phc = 1;
                    }

                    $query_esistenza_affidamento_pratica = 'SELECT id_lotto_affidamento, id_contratto, data_fine_mandato
															FROM pratiche 
															WHERE (id_lotto_affidamento IS NULL OR id_lotto_affidamento = 0)
																AND id = "' . $pratiche[$i] . '"';
                    $ris_esistenza_affidamento_pratica = db_query($query_esistenza_affidamento_pratica);

                    if (mysql_num_rows($ris_esistenza_affidamento_pratica) > 0 && $praticaDETT = mysql_fetch_array($ris_esistenza_affidamento_pratica)) {
                        $query_esistenza_lotto_affidato = 'SELECT id 
															FROM affidamenti
															WHERE data_affidamento = "' . $data_affidamento . '"
																AND id_collector = "' . $collectors[$i] . '"';
                        $ris_esistenza_lotto_affidato = db_query($query_esistenza_lotto_affidato);

                        // RECUPERO TUTTI I DATI NECESSARI PER CALCOLARE LA DATA DI FINE MANDATO DEL COLLECTOR
                        if ($phc == 1) {
                            $query_dettagli_collector = 'SELECT U.*, P.*, P.cat_prof AS categoria_professionale
														FROM utente U LEFT JOIN phone_collector P ON U.id_utente = P.id_utente
														WHERE U.id_utente = "' . $collectors[$i] . '"';
                            $ris_collectorDETT = db_query($query_dettagli_collector);
                            $collectorDETT = mysql_fetch_array($ris_collectorDETT);

                            $query_dettagli_affidamento = 'SELECT giorni_lavorazione
															FROM contratto_durata_affidamento
															WHERE id_contratto = "' . $praticaDETT['id_contratto'] . '"
															AND tipo_collaboratore = "Phone_Collector"
															AND categoria = "' . $collectorDETT['categoria_professionale'] . '"
															ORDER BY id DESC
															LIMIT 0,1';
                            $ris_affidamentoDETT = db_query($query_dettagli_affidamento);
                            if (mysql_num_rows($ris_affidamentoDETT) > 0) {
                                $affidamentoDETT = mysql_fetch_array($ris_affidamentoDETT);
                                $giorni_lavorazione = $affidamentoDETT['giorni_lavorazione'];
                            } else {
                                $giorni_lavorazione = 0;
                            }
                        } else {
                            $query_dettagli_collector = 'SELECT U.*, E.*, E.cat_prof AS categoria_professionale
														FROM utente U LEFT JOIN esattore E ON U.id_utente = E.id_utente
														WHERE U.id_utente = "' . $collectors[$i] . '"';
                            $ris_collectorDETT = db_query($query_dettagli_collector);
                            $collectorDETT = mysql_fetch_array($ris_collectorDETT);

                            $query_dettagli_affidamento = 'SELECT giorni_lavorazione
															FROM contratto_durata_affidamento
															WHERE id_contratto = "' . $praticaDETT['id_contratto'] . '"
															AND tipo_collaboratore = "Esattore"
															AND categoria = "' . $collectorDETT['categoria_professionale'] . '"
															ORDER BY id DESC
															LIMIT 0,1';
                            $ris_affidamentoDETT = db_query($query_dettagli_affidamento);
                            if (mysql_num_rows($ris_affidamentoDETT) > 0) {
                                $affidamentoDETT = mysql_fetch_array($ris_affidamentoDETT);
                                $giorni_lavorazione = $affidamentoDETT['giorni_lavorazione'];
                            } else {
                                $giorni_lavorazione = 0;
                            }
                        }

                        $query_dettagli_contratto = 'SELECT *
													FROM contratto
													WHERE id = "' . $praticaDETT['id_contratto'] . '"';
                        $ris_contrattoDETT = db_query($query_dettagli_contratto);
                        $contrattoDETT = mysql_fetch_array($ris_contrattoDETT);


                        if ($giorni_lavorazione == 0) {
                            $data_fine_mandato_collector = $praticaDETT['data_fine_mandato'];
                        } else {
                            // TRELLO#00048
                            $data_di_partenza = $data_fine_mandato_collector_calcolata = date('Y-m-d', strtotime($data_affidamento));

                            $giorni = 0;
                            $giorni_lavorativi = 0;

                            if (strpos($giorni_lavorazione, '-') === 0) {
                                for (; $giorni > -100; $giorni--) {
                                    if ($giorni_lavorativi < $giorni_lavorazione) break;
                                    if (date('N', strtotime($data_di_partenza . ' ' . $giorni . ' days')) < 6) $giorni_lavorativi--;
                                }
                                $data_fine_mandato_collector_calcolata = date('Y-m-d', strtotime($data_di_partenza . ' ' . ($giorni + 2) . ' days'));
                            } else {
                                for (; $giorni < 100; $giorni++) {
                                    if ($giorni_lavorativi > $giorni_lavorazione) break;
                                    if (date('N', strtotime($data_di_partenza . ' ' . $giorni . ' days')) < 6) $giorni_lavorativi++;
                                }
                                $data_fine_mandato_collector_calcolata = date('Y-m-d', strtotime($data_di_partenza . ' ' . ($giorni - 2) . ' days'));
                            }

                            //echo '<br>';
                            //$data_fine_mandato_collector_calcolata = date('Y-m-d',strtotime($data_affidamento.' +'.$giorni_lavorazione.' days'));
                            // FINE - TRELLO#00048

                            //echo $data_affidamento.' + '.$giorni_lavorazione.' = '.$data_fine_mandato_collector_calcolata;
                            //echo '<br>';
                            if (strpos($contrattoDETT['blocca_scadenze'], '+') >= 0 || strpos($contrattoDETT['blocca_scadenze'], '-') >= 0)
                                $giorni_blocco_scadenze = $contrattoDETT['blocca_scadenze'];
                            else
                                $giorni_blocco_scadenze = '+' . $contrattoDETT['blocca_scadenze'];

                            // TRELLO#00048
                            $data_di_partenza = $data_fine_mandato_collector_max = date('Y-m-d', strtotime($praticaDETT['data_fine_mandato']));

                            $giorni = 0;
                            $giorni_lavorativi = 0;

                            if (strpos($giorni_blocco_scadenze, '-') === 0) {
                                for (; $giorni > -100; $giorni--) {
                                    if ($giorni_lavorativi < $giorni_blocco_scadenze) break;
                                    if (date('N', strtotime($data_di_partenza . ' ' . $giorni . ' days')) < 6) $giorni_lavorativi--;
                                }
                                $data_fine_mandato_collector_max = date('Y-m-d', strtotime($data_di_partenza . ' ' . ($giorni + 2) . ' days'));
                            } else {
                                for (; $giorni < 100; $giorni++) {
                                    if ($giorni_lavorativi > $giorni_blocco_scadenze) break;
                                    if (date('N', strtotime($data_di_partenza . ' ' . $giorni . ' days')) < 6) $giorni_lavorativi++;
                                }
                                $data_fine_mandato_collector_max = date('Y-m-d', strtotime($data_di_partenza . ' ' . ($giorni - 2) . ' days'));
                            }

                            // $data_fine_mandato_collector_max = date('Y-m-d',strtotime($praticaDETT['data_fine_mandato'].' '.$giorni_blocco_scadenze.' days'));
                            // FINE - TRELLO#00048

                            //echo $praticaDETT['data_fine_mandato'].' - '.$contrattoDETT['blocca_scadenze'].' = '.$data_fine_mandato_collector_max;
                            //echo '<br>';
                            $data_fine_mandato_collector = $data_fine_mandato_collector_calcolata > $data_fine_mandato_collector_max ? $data_fine_mandato_collector_max : $data_fine_mandato_collector_calcolata;
                            //echo $data_fine_mandato_collector; die();
                        }
                        // FINE - RECUPERO TUTTI I DATI NECESSARI PER CALCOLARE LA DATA DI FINE MANDATO DEL COLLECTOR


                        if (mysql_num_rows($ris_esistenza_lotto_affidato)) {
                            $affidamento = mysql_fetch_array($ris_esistenza_lotto_affidato);
                            $id = $affidamento['id'];
                        } else {
                            $query_inserimento_affidamento = 'INSERT INTO affidamenti
															SET data_affidamento = "' . $data_affidamento . '",
																id_collector = "' . $collectors[$i] . '",
																phc = "' . $phc . '",
																data_creazione = "' . date('Y-m-d H:i:s') . '"';
                            $ris_inserimento_affidamento = db_query($query_inserimento_affidamento);
                            $affidamenti_creati++;
                            $id = mysql_insert_id();
                            $array_id_affidamenti[] = $id;
                        }

                        $query_inserimento_pratica = 'UPDATE pratiche
													SET id_lotto_affidamento = "' . $id . '",
														id_collector = "' . $collectors[$i] . '",
														id_anagrafica_candidato_affido = NULL
													WHERE id = "' . $pratiche[$i] . '"';
                        $ris_inserimento_pratica = db_query($query_inserimento_pratica);

                        $array_affidamenti[$id][] = $pratiche[$i];

                        $query_inserimento_storico_pratica = 'INSERT INTO pratiche_affidamenti
															SET id_pratica = "' . $pratiche[$i] . '",
																id_affidamento = "' . $id . '",
																id_team_affido = "' . $team . '",
																data_fine_mandato_collector = "' . $data_fine_mandato_collector . '"';
                        $ris_inserimento_storico_pratica = db_query($query_inserimento_storico_pratica);

                        $pratiche_elaborate++;
                        $elenco_pratiche_affidate_successo .= $pratiche[$i] . ',';

                        if (isset($_POST['phc']) && $_POST['phc'] == 1) {
                            /* $query_delete_scadenze = "DELETE FROM scadenze WHERE schedulazione=1 AND id_pratica = '" . db_input($pratiche[$i]) . "'";
                             db_query($query_delete_scadenze);

                             $query_dettaglio_pratica = "SELECT id_lotto_mandante, id_lotto_studio FROM pratiche WHERE id = '" . $pratiche[$i] . "'";
                             $ris_dettaglio_pratica = db_query($query_delete_scadenze);
                             $dett_pratica = mysql_fetch_assoc($ris_dettaglio_pratica);

                             $query_insert_scadenze = "INSERT INTO scadenze
                                     SET id_tipo_scadenza = 2,
                                         stato = 1,
                                         schedulazione = 1,
                                         nuova_pratica = 1,
                                         descrizione = 'Nuova Schedulazione Pratica',
                                         note = 'Nuova Schedulazione Pratica',
                                         data = CURRENT_DATE,
                                         ora = CURRENT_TIME,
                                         id_mittente = '" . $_SESSION['user_admin_id'] . "',
                                         id_destinatario = '" . $collectors[$i] . "',
                                         id_pratica = '" . $pratiche[$i] . "',
                                         id_lotto_mandante = '" . $dett_pratica['id_lotto_mandante'] . "',
                                         id_lotto_studio = '" . $dett_pratica['id_lotto_studio'] . "'";
                             db_query($query_insert_scadenze);*/
                        }


                    }
                } else {
                    continue;
                }
            }


            $stringa_pratiche_affidate = '';
            for ($i = 0; $i < count($array_id_affidamenti); $i++) {
                global $array_affidamenti;
                global $array_id_affidamenti;

                //print_r($array_id_affidamenti[$i]);
                //print_r($array_affidamenti[$array_id_affidamenti[$i]]);
                //print_r(implode(',',$array_affidamenti[$array_id_affidamenti[$i]]));

                $stringa = implode(',', $array_affidamenti[$array_id_affidamenti[$i]]);
                if ($i != 0)
                    $stringa_pratiche_affidate .= "::";
                $stringa_pratiche_affidate .= $stringa;
            }

            echo $affidamenti_creati . '*-*' . $pratiche_elaborate . '*-*' . $stringa_pratiche_affidate . '*-*' . trim(trim($elenco_pratiche_affidate_successo, ','));
        }
        break;
    case 'proposizione-affidamento':
        {
            $affidamenti = array();

            $append_gruppo_base = '';

            if (isset($_POST['esa']) && $_POST['esa'] == 'ESA') {
                $append_gruppo_base = "AND (gruppi_base = '6'
										OR gruppi_base LIKE '%,6,%'
										OR gruppi_base LIKE '%,6'
										OR gruppi_base LIKE '6,%'
										OR gruppi_base = '7'
										OR gruppi_base LIKE '%,7,%'
										OR gruppi_base LIKE '%,7'
										OR gruppi_base LIKE '7,%')";
            } else if (isset($_POST['esa']) && $_POST['esa'] == 'PHC') {
                $append_gruppo_base = "AND (gruppi_base = '3'
										OR gruppi_base LIKE '%,3,%'
										OR gruppi_base LIKE '%,3'
										OR gruppi_base LIKE '3,%'
										OR gruppi_base = '12'
										OR gruppi_base LIKE '%,12,%'
										OR gruppi_base LIKE '%,12'
										OR gruppi_base LIKE '12,%')";
            } else {
                $append_gruppo_base = "AND (gruppi_base = '6'
										OR gruppi_base LIKE '%,6,%'
										OR gruppi_base LIKE '%,6'
										OR gruppi_base LIKE '6,%'
										OR gruppi_base = '3'
										OR gruppi_base LIKE '%,3,%'
										OR gruppi_base LIKE '%,3'
										OR gruppi_base LIKE '3,%'
										OR gruppi_base = '7'
										OR gruppi_base LIKE '%,7,%'
										OR gruppi_base LIKE '%,7'
										OR gruppi_base LIKE '7,%'
										OR gruppi_base = '12'
										OR gruppi_base LIKE '%,12,%'
										OR gruppi_base LIKE '%,12'
										OR gruppi_base LIKE '12,%')";
            }

            // INIZIALIZZO DEI CONTATORI CHE MI TENGANO TRACCIA DEL NUMERO DI PROPOSIZIONI FATTE PER OGNI COLLECTOR
            $n_affidamenti_collector = array();
            $array_query_carichi = array();
            $array_collector_assegnati_debitore = array();

            $pratiche = explode(',', str_replace(array('[', ']', '"'), '', $_POST['id_pratiche']));

            $array_performances = array();
            if (str_replace(array('[', ']', '"'), '', $_POST['id_pratiche']) != '') {
                $query_performance = "SELECT * FROM collector_performance WHERE id_contratto IN (SELECT id_contratto FROM pratiche WHERE id IN (" . str_replace(array('[', ']', '"'), '', $_POST['id_pratiche']) . "))";
                $ris_performance = db_query($query_performance);
                if (mysql_num_rows($ris_performance) > 0) {
                    while ($result_performance = mysql_fetch_assoc($ris_performance)) {
                        $array_performances[$result_performance['id_contratto']][$result_performance['id_collector']]['performance'] = $result_performance['performance'];
                        $array_performances[$result_performance['id_contratto']][$result_performance['id_collector']]['lavorazione'] = $result_performance['lavorazione'];
                    }
                }
            }
            session_start([
                'read_and_close' => true,
            ]);

            foreach ($pratiche as $pratica) {

                //db_close();

                //CONNESSIONE AL DB (IN CASO DI ERRORE DIE CON MESSAGGIO DAL SERVER)
                $link = db_connect($db_server, $username, $password);
                $sqlconnect = db_select_db($nomeDb, $link);

                # RECUPERO LA LISTA DI TUTTI I COLLECTOR CHE POSSONO GESTIRE LE PRATICHE
                $lista_collector = array();
                $lista_collector_max_pratiche = array();
                $lista_collector_carichi = array();

                $ruoloUtente = $_SESSION['user_role'];
                $append_vincoli_capoarea_collector = '';

                if ($ruoloUtente == CAPO_ESATTORE) {
                    $append_vincoli_capoarea_collector = ' AND (U.id_utente = ' . $_SESSION['user_admin_id'] . ' 
														OR U.id_utente IN (SELECT id_collegato FROM collegati_esattore WHERE id_utente = ' . $_SESSION['user_admin_id'] . '))';
                }

                $query_collectors = "SELECT U.id_utente, cognome, nome, codice_fiscale
															FROM utente U 
															LEFT JOIN (
																		(SELECT id_utente, assente, assente_da, assente_a FROM phone_collector) 
																			UNION 
																		(SELECT id_utente, assente, assente_da, assente_a FROM esattore)
																	   ) AS C ON U.id_utente = C.id_utente
															WHERE U.attivo = 1 " . $append_gruppo_base . $append_vincoli_capoarea_collector;

                # RECUPERO LA PRIORITA' DI AFFIDO PER ORDINARE LA LISTA DEI COLLECTOR
                $ordinamento_priorita_affido = '';
                $query_lista_collector = '';

                $query_recupero_priorita_affido = 'SELECT priorita_affido FROM impostazioni_base WHERE id = 1';
                $result_recupero_priorita_affido = db_query($query_recupero_priorita_affido);
                $priorita_affido = mysql_fetch_array($result_recupero_priorita_affido);

                if ($priorita_affido['priorita_affido'] != '') {
                    # IN BASE ALLA PRIORITA RECUPERATA CREO LA LISTA ORDINATA DEI COLLECTOR
                    if ($priorita_affido['priorita_affido'] == 'PHC') {
                        $query_lista_collector = "SELECT PC.id_utente, PC.max_num_prat, PC.assente, PC.assente_da, PC.assente_a, 1 AS PHC
												  FROM phone_collector PC 
													  INNER JOIN utente U on PC.id_utente = U.id_utente
													  LEFT JOIN pratiche P on PC.id_utente = P.id_collector
												  WHERE (U.attivo = 1 OR U.attivo IS NULL) " . $append_gruppo_base . $append_vincoli_capoarea_collector . "
												  GROUP BY PC.id_utente, PC.max_num_prat
												  HAVING ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) = 0) OR ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) > COUNT(*))
											  
												  UNION
											  
												  SELECT E.id_utente, E.max_num_prat, E.assente, E.assente_da, E.assente_a, 0 AS PHC
												  FROM esattore E 
													  INNER JOIN utente U on E.id_utente = U.id_utente
													  LEFT JOIN pratiche P on E.id_utente = P.id_collector
												  WHERE (U.attivo = 1 OR U.attivo IS NULL) " . $append_gruppo_base . $append_vincoli_capoarea_collector . "
												  GROUP BY E.id_utente, E.max_num_prat
												  HAVING ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) = 0) OR ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) > COUNT(*))";
                    } else if ($priorita_affido['priorita_affido'] == 'ESA') {
                        $query_lista_collector = "SELECT E.id_utente, E.max_num_prat, E.assente, E.assente_da, E.assente_a, 0 AS PHC
												  FROM esattore E 
													  INNER JOIN utente U on E.id_utente = U.id_utente
													  LEFT JOIN pratiche P on E.id_utente = P.id_collector
												  WHERE (U.attivo = 1 OR U.attivo IS NULL) " . $append_gruppo_base . $append_vincoli_capoarea_collector . "
												  GROUP BY E.id_utente, E.max_num_prat
												  HAVING ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) = 0) OR ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) > COUNT(*))
												  UNION
												  
												  SELECT PC.id_utente, PC.max_num_prat, PC.assente, PC.assente_da, PC.assente_a, 1 AS PHC
												  FROM phone_collector PC 
													  INNER JOIN utente U on PC.id_utente = U.id_utente
													  LEFT JOIN pratiche P on PC.id_utente = P.id_collector
												  WHERE (U.attivo = 1 OR U.attivo IS NULL) " . $append_gruppo_base . $append_vincoli_capoarea_collector . "
												  GROUP BY PC.id_utente, PC.max_num_prat
												  HAVING ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) = 0) OR ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) > COUNT(*))";
                    }
                } else {
                    $query_lista_collector = "SELECT PC.id_utente, PC.max_num_prat, PC.assente, PC.assente_da, PC.assente_a, 1 AS PHC
											  FROM phone_collector PC 
												  INNER JOIN utente U on PC.id_utente = U.id_utente
												  LEFT JOIN pratiche P on PC.id_utente = P.id_collector
											  WHERE (U.attivo = 1 OR U.attivo IS NULL) " . $append_gruppo_base . $append_vincoli_capoarea_collector . "
											  GROUP BY PC.id_utente, PC.max_num_prat
											  HAVING ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) = 0) OR ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) > COUNT(*))
										  
											  UNION
										  
											  SELECT E.id_utente, E.max_num_prat, E.assente, E.assente_da, E.assente_a, 0 AS PHC
											  FROM esattore E 
												  INNER JOIN utente U on E.id_utente = U.id_utente
												  LEFT JOIN pratiche P on E.id_utente = P.id_collector
											  WHERE (U.attivo = 1 OR U.attivo IS NULL) " . $append_gruppo_base . $append_vincoli_capoarea_collector . "
											  GROUP BY E.id_utente, E.max_num_prat
											  HAVING ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) = 0) OR ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) > COUNT(*))";
                }

                /*
			echo 'QUERY LISTA COLLECTOR:';
			echo PHP_EOL;
			echo $query_lista_collector;
			echo PHP_EOL;
			echo PHP_EOL;
			*/

                $ris_lista_collector = db_query($query_lista_collector);

                if (strpos($_POST['collectors_esclusi'], ',') > 0)
                    $collector_esclusi_da_proposizione = explode(',', $_POST['collectors_esclusi']);
                else
                    $collector_esclusi_da_proposizione = $_POST['collectors_esclusi'];

                while ($row_lista_collector = mysql_fetch_array($ris_lista_collector)) {
                    if (!in_array($row_lista_collector['id_utente'], $collector_esclusi_da_proposizione)) {
                        $lista_collector[] = $row_lista_collector['id_utente'];
                        $lista_collector_max_pratiche[$row_lista_collector['id_utente']] = $row_lista_collector['max_num_prat'];
                    }
                }
                # FINE RECUPERO LISTA COLLECTOR POSSIBILI

                // RECUPERO ALCUNI DETTAGLI DELLA PRATICA CHE POI SERVIRANNO NEI CONTROLLI DI AFFIDABILITA'
                $query_pratica = "SELECT C.id_tipo_credito, CR.codice, P.id_contratto
											FROM pratiche P 
												LEFT JOIN contratto C ON P.id_contratto = C.id
												LEFT JOIN credito CR ON CR.id = C.id_tipo_credito
											WHERE P.id = '" . db_input($pratica) . "'";
                $row_pratica = mysql_fetch_array(db_query($query_pratica));

                $collector_storico_positivo = 0;

                // VERIFICO LE CONDIZIONI DI CARICO DI OGNI COLLECTOR
                for ($z = 0; $z < count($lista_collector); $z++) {
                    $col_id = $lista_collector[$z];

                    // CONTROLLO SE IL COLLECTOR PUO' GESTIRE DELLE TIPOLOGIE DI CREDITO
                    $query_carico_tipologie_credito = "SELECT gg_lavorabili AS n FROM prodotti_lavorabili WHERE id_utente = '" . $col_id . "'";
                    $ris_carico_tipologie_credito = db_query($query_carico_tipologie_credito);

                    if (db_num_rows($ris_carico_tipologie_credito) > 0) {
                        // RECUPERO IL NUMERO MASSIMO DI PRATICHE DI QUESTA TIPOLOGIA CHE OGNI COLLECTOR PUO' GESTIRE
                        $query_max_carico = "SELECT gg_lavorabili AS n FROM prodotti_lavorabili WHERE id_utente = '" . $col_id . "' AND id_prodotto = '" . $row_pratica['codice'] . "'";
                        $ris_max_carico = db_query($query_max_carico);

                        if (db_num_rows($ris_max_carico) > 0) {
                            $array_query_carichi[$col_id]['max'] = $query_max_carico;
                            $row_max_carico = mysql_fetch_array($ris_max_carico);
                            $max_carico = $row_max_carico['n'];
                            $carico_massimo = $max_carico;

                            // RECUPERO IL CARICO ATTUALE DEL COLLECTOR PER QUESTA TIPOLOGIA
                            $carico_attuale = 0;
                            $query_carico_attuale = "SELECT carico AS n FROM carico_collector  WHERE id_collector = '" . $col_id . "' AND codice = '" . $row_pratica['codice'] . "'";
                            $ris_carico_attuale = db_query($query_carico_attuale);
                            $array_query_carichi[$col_id]['act'] = $query_carico_attuale;
                            if (db_num_rows($ris_carico_attuale) > 0) {
                                $carico_att = mysql_fetch_array($ris_carico_attuale);
                                $carico_attuale = $carico_att['n'];
                            }

                            $carico_proposto = $n_affidamenti_collector[$col_id][$row_pratica['codice']] > 0 ? $n_affidamenti_collector[$col_id][$row_pratica['codice']] : 0;

                            if ($carico_massimo == ($carico_attuale + $carico_proposto)) {  // || $max_carico == 0
                                if (($key = array_search($col_id, $lista_collector)) !== false) {
                                    unset($lista_collector[$key]);
                                }
                            }

                            $lista_collector_carichi[$col_id]['max'] = $carico_massimo;
                            $lista_collector_carichi[$col_id]['act'] = $carico_attuale;
                            $lista_collector_carichi[$col_id]['pro'] = $carico_proposto;
                        } else {
                            if (($key = array_search($col_id, $lista_collector)) !== false) {
                                unset($lista_collector[$key]);
                            }
                        }
                    } else {
                        // RECUPERO IL NUMERO MASSIMO DI PRATICHE DI QUESTA TIPOLOGIA CHE OGNI COLLECTOR PUO' GESTIRE
                        $array_query_carichi[$col_id]['max'] = $lista_collector_max_pratiche[$col_id];
                        $max_carico = $lista_collector_max_pratiche[$col_id];

                        $carico_massimo = $max_carico;

                        // RECUPERO IL CARICO ATTUALE DEL COLLECTOR PER QUESTA TIPOLOGIA
                        $carico_attuale = 0;
                        $query_carico_attuale = "SELECT SUM(carico) AS n FROM carico_collector WHERE id_collector = '" . $col_id . "'";
                        $ris_carico_attuale = db_query($query_carico_attuale);
                        $array_query_carichi[$col_id]['act'] = $query_carico_attuale;
                        if (db_num_rows($ris_carico_attuale) > 0) {
                            $carico_att = mysql_fetch_array($ris_carico_attuale);
                            $carico_attuale = $carico_att['n'];
                        }

                        $carico_proposto = 0;
                        foreach ($n_affidamenti_collector[$col_id] as $cpg) {
                            $carico_proposto += $cpg;
                        }

                        if ($carico_massimo == ($carico_attuale + $carico_proposto)) {
                            if (($key = array_search($col_id, $lista_collector)) !== false) {
                                unset($lista_collector[$key]);
                            }
                        }

                        $lista_collector_carichi[$col_id]['max'] = $carico_massimo;
                        $lista_collector_carichi[$col_id]['act'] = $carico_attuale;
                        $lista_collector_carichi[$col_id]['pro'] = $carico_proposto;
                    }
                }
                //print_r($lista_collector_carichi);

                #6 (FUNZIONANTE) SE SULLA PRATICA E' PRESENTE UN'ANAGRAFICA AFFIDO, SI FORZA QUEST'ULTIMA SENZA FARE NESSUN CONTROLLO (ZONA, CARICO DI LAVORO, TIPOLOGIA DI CREDITO ...)
                $query_cadidato_affido = "SELECT id_anagrafica_candidato_affido as id_utente, id_debitore
										FROM pratiche 
										WHERE id = '" . db_input($pratica) . "' 
										AND id_anagrafica_candidato_affido > 0";

                $ris_cadidato_affido = db_query($query_cadidato_affido);
                if (mysql_num_rows($ris_cadidato_affido)) {
                    $candidato = mysql_fetch_array($ris_cadidato_affido);

                    // VERIFICO SE IL COLLECTOR è ASSENTE
                    $query_assenza = "(SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM phone_collector WHERE id_utente = '" . $candidato['id_utente'] . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01'))))) 
									  UNION 
								  (SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM esattore WHERE id_utente = '" . $candidato['id_utente'] . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01')))))";
                    $ris_assenza = db_query($query_assenza);

                    if (mysql_num_rows($ris_assenza) > 0) {
                        $sostituto = mysql_fetch_array($ris_assenza);
                        // PROPONGO IL SOSTITUTO DEL COLLECTOR
                        $affidamenti[] = array(
                            'pratica' => $pratica,
                            'candidato' => $sostituto['id_oper_sost'],
                            'tipo' => 'DIRETTO',
                            //'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                            //'query' => $array_query_carichi[$candidato['id_utente']]
                        );

                        if ($sostituto['id_oper_sost'] > 0)
                            $array_collector_assegnati_debitore[$candidato['id_debitore']] = $sostituto['id_oper_sost'];
                    } else {
                        // PROPONGO IL COLLECTOR
                        $affidamenti[] = array(
                            'pratica' => $pratica,
                            'candidato' => $candidato['id_utente'],
                            'tipo' => 'DIRETTO',
                            //'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                            //'query' => $array_query_carichi[$candidato['id_utente']]
                        );

                        if ($candidato['id_utente'] > 0)
                            $array_collector_assegnati_debitore[$candidato['id_debitore']] = $candidato['id_utente'];
                    }

                    /* I CARICHI PROPOSTI FORZATAMENTE NON VENGONO CONTEGGIATI SUL CARICO GLOBALE
				if($n_affidamenti_collector[$candidato['id_utente']][$row_pratica['codice']] > 0) {
					$n_affidamenti_collector[$candidato['id_utente']][$row_pratica['codice']]++;
				}
				else {
					$n_affidamenti_collector[$candidato['id_utente']][$row_pratica['codice']] = 1;
				}
				*/
                    // FINE - PROPONGO IL COLLECTOR
                } else if ($collector_storico_positivo > 0) {
                    $candidato = array();
                    $candidato['id_utente'] = $collector_storico_positivo;
                    $query_debitore = "SELECT id_debitore
									FROM pratiche
									WHERE id = '" . db_input($pratica) . "'";
                    $ris_debitore = db_query($query_debitore);
                    $debitore = mysql_fetch_array($ris_debitore);
                    $candidato['id_debitore'] = $debitore['id_debitore'];

                    // VERIFICO SE IL COLLECTOR è ASSENTE
                    $query_assenza = "(SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM phone_collector WHERE id_utente = '" . $candidato['id_utente'] . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01'))))) 
									  UNION 
								  (SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM esattore WHERE id_utente = '" . $candidato['id_utente'] . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01')))))";
                    $ris_assenza = db_query($query_assenza);

                    if (mysql_num_rows($ris_assenza) > 0) {
                        $sostituto = mysql_fetch_array($ris_assenza);
                        // PROPONGO IL SOSTITUTO DEL COLLECTOR
                        $affidamenti[] = array(
                            'pratica' => $pratica,
                            'candidato' => $sostituto['id_oper_sost'],
                            'tipo' => 'DIRETTO DA STORICO PER SOSTITUZIONE',
                            //'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                            //'query' => $array_query_carichi[$candidato['id_utente']]
                        );

                        if ($sostituto['id_oper_sost'] > 0)
                            $array_collector_assegnati_debitore[$candidato['id_debitore']] = $sostituto['id_oper_sost'];
                    } else {
                        // PROPONGO IL COLLECTOR
                        $affidamenti[] = array(
                            'pratica' => $pratica,
                            'candidato' => $candidato['id_utente'],
                            'tipo' => 'DIRETTO DA STORICO',
                            //'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                            //'query' => $array_query_carichi[$candidato['id_utente']]
                        );

                        if ($candidato['id_utente'] > 0)
                            $array_collector_assegnati_debitore[$candidato['id_debitore']] = $candidato['id_utente'];
                    }
                    $affidamenti[] = array(
                        'pratica' => $pratica,
                        'candidato' => $collector_storico_positivo,
                        'tipo' => 'DIRETTO DA STORICO',
                        //'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                        //'query' => $array_query_carichi[$candidato['id_utente']]
                    );
                } else {
                    $go_ahead = false;
                    $collectors_da_escludere = array();

                    #7 SE LA PRATICA SI RIFERISCE AD UN DEBITORE RECIDIVO E VI E' UNA PRATICA COLLEGATA ATTIVA IL COLLECTOR DA PROPORRE E' LO STESSO DELLA PRATICA ATTIVA
                    #  NEL CASO IN CUI TUTTE LE PRATICHE SIANO STATE CHIUSE I CASI POSSONO ESSERE 2:
                    #  SE LA PRATICA HA ESITO DI SCARICO COLLECTOR POSITIVO PROPONE QUEL COLLECTOR
                    #  SE LA PRATICA HA ESITO DI SCARICO NEGATIVO ALLORA ESCLUDE QUEL COLLECTOR

                    #  STEP 1: RECUPERO IL DEBITORE DELLA PRATICA DA ASSEGNARE
                    $query_debitore = "SELECT id_debitore
									FROM pratiche
									WHERE id = '" . db_input($pratica) . "'";
                    $ris_debitore = db_query($query_debitore);
                    $debitore = mysql_fetch_array($ris_debitore);

                    if ($array_collector_assegnati_debitore[$debitore['id_debitore']] > 0) {
                        // VERIFICO SE IL COLLECTOR è ASSENTE
                        $query_assenza = "(SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM phone_collector WHERE id_utente = '" . $array_collector_assegnati_debitore[$debitore['id_debitore']] . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01')))))
										  UNION 
									  (SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM esattore WHERE id_utente = '" . $array_collector_assegnati_debitore[$debitore['id_debitore']] . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01')))))";
                        $ris_assenza = db_query($query_assenza);

                        if (mysql_num_rows($ris_assenza) > 0) {
                            $sostituto = mysql_fetch_array($ris_assenza);
                            // PROPONGO IL SOSTITUTO DEL COLLECTOR
                            $affidamenti[] = array(
                                'pratica' => $pratica,
                                'candidato' => $sostituto['id_oper_sost'],
                                'tipo' => 'RIAFFIDO DA PROPOSIZIONE',
                                //'collector_assegnati_debitore' => $array_collector_assegnati_debitore,
                                //'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                                //'query' => $array_query_carichi[$candidato['id_utente']]
                            );
                        } else {
                            $affidamenti[] = array(
                                'pratica' => $pratica,
                                'candidato' => $array_collector_assegnati_debitore[$debitore['id_debitore']],
                                'tipo' => 'RIAFFIDO DA PROPOSIZIONE',
                                //'collector_assegnati_debitore' => $array_collector_assegnati_debitore,
                                //'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                                //'query' => $array_query_carichi[$candidato['id_utente']]
                            );
                        }
                    } else {
                        #  STEP 2: RECUPERO L'ULTIMA PRATICA ASSOCIATA AL DEBITORE ESCLUDENDO QUELLA IN ESAME
                        #  STEP 3: RECUPERO L'EVENTUALE COLLECTOR DA ASSEGNARE ALLA NUOVA PRATICA
                        $query_cadidato_affido_2 = "SELECT id_collector as id_utente, EP.tipo, PA.data_scarico, PA.id_pratica
													FROM pratiche_affidamenti PA
														LEFT JOIN affidamenti A ON PA.id_affidamento = A.id
														LEFT JOIN esiti_pratica EP ON EP.id = PA.id_esito_collector
													WHERE PA.id_pratica IN (SELECT id 
																				FROM pratiche 
																				WHERE id <> '" . db_input($pratica) . "' 
																				AND id_debitore = '" . db_input($debitore['id_debitore']) . "' 
																				ORDER BY id DESC)
													AND id_collector IS NOT NULL
													AND id_collector IN (SELECT id_utente FROM utente WHERE attivo = 1)
													ORDER BY PA.id DESC
													LIMIT 0,1";
                        $ris_cadidato_affido_2 = db_query($query_cadidato_affido_2);
                        if (db_num_rows($ris_cadidato_affido_2)) {
                            $candidato = mysql_fetch_array($ris_cadidato_affido_2);

                            if ($candidato['tipo'] == 'POSITIVA' || $candidato['data_scarico'] == '') {
                                // VERIFICO SE IL COLLECTOR è ASSENTE
                                $query_assenza = "(SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM phone_collector WHERE id_utente = '" . $candidato['id_utente'] . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01'))))) 
												  UNION 
											  (SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM esattore WHERE id_utente = '" . $candidato['id_utente'] . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01'))))) ";
                                $ris_assenza = db_query($query_assenza);

                                if (mysql_num_rows($ris_assenza) > 0) {
                                    $sostituto = mysql_fetch_array($ris_assenza);
                                    // PROPONGO IL SOSTITUTO DEL COLLECTOR
                                    if ($sostituto['id_oper_sost'] > 0) {
                                        $affidamenti[] = array(
                                            'pratica' => $pratica,
                                            'candidato' => $sostituto['id_oper_sost'],
                                            'tipo' => 'RIAFFIDO',
                                            //'collector_assegnati_debitore' => $array_collector_assegnati_debitore,
                                            //'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                                            //'query' => $array_query_carichi[$candidato['id_utente']]
                                        );

                                        $array_collector_assegnati_debitore[$debitore['id_debitore']] = $sostituto['id_oper_sost'];
                                    } else if ($candidato['id_utente'] > 0) {
                                        $affidamenti[] = array(
                                            'pratica' => $pratica,
                                            'candidato' => $candidato['id_utente'],
                                            'tipo' => 'RIAFFIDO',
                                            //'collector_assegnati_debitore' => $array_collector_assegnati_debitore,
                                            //'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                                            //'query' => $array_query_carichi[$candidato['id_utente']]
                                        );

                                        $array_collector_assegnati_debitore[$debitore['id_debitore']] = $candidato['id_utente'];
                                    } else {
                                        $go_ahead = true;
                                    }
                                } else {
                                    // PROPONGO IL COLLECTOR
                                    if ($candidato['id_utente'] > 0) {
                                        $affidamenti[] = array(
                                            'pratica' => $pratica,
                                            'candidato' => $candidato['id_utente'],
                                            'tipo' => 'RIAFFIDO',
                                            //'collector_assegnati_debitore' => $array_collector_assegnati_debitore,
                                            //'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                                            //'query' => $array_query_carichi[$candidato['id_utente']]
                                        );

                                        $array_collector_assegnati_debitore[$debitore['id_debitore']] = $candidato['id_utente'];
                                    } else {
                                        $go_ahead = true;
                                    }
                                }

                                /* I CARICHI PROPOSTI DAL RIAFFIDO NON VENGONO CONTEGGIATI SUL CARICO GLOBALE
							if($n_affidamenti_collector[$candidato['id_utente']][$row_pratica['codice']] > 0) {
								$n_affidamenti_collector[$candidato['id_utente']][$row_pratica['codice']]++;
							}
							else {
								$n_affidamenti_collector[$candidato['id_utente']][$row_pratica['codice']] = 1;
							}
							*/
                                // FINE - PROPONGO IL COLLECTOR
                            } else {
                                $go_ahead = true;
                                //$collector_da_escludere = $candidato['id_utente'];
                            }
                        } else {
                            $go_ahead = true;
                        }
                    }

                    //RECUPERO TUTTI I CANDIDATI ASSEGNATI A PRATICHE COLLEGATE CHE ABBIANO UN ESITO NON POSITIVO
                    $query_pratiche_collegate = "SELECT id
												FROM pratiche
												WHERE id_debitore = '" . db_input($debitore['id_debitore']) . "'
												ORDER BY id DESC";
                    $ris_pratiche_collegate = db_query($query_pratiche_collegate);

                    if (db_num_rows($ris_pratiche_collegate) > 0) {
                        while ($pratica_collegate = mysql_fetch_array($ris_pratiche_collegate)) {
                            $query_cadidati_da_escludere = "SELECT id_collector as id_utente
															FROM pratiche_affidamenti PA
																LEFT JOIN affidamenti A ON PA.id_affidamento = A.id
																LEFT JOIN esiti_pratica EP ON EP.id = PA.id_esito_collector
															WHERE PA.id_pratica = '" . db_input($pratica_collegate['id']) . "'
																AND PA.data_scarico IS NOT NULL 
																AND EP.tipo <> 'POSITIVA'
															ORDER BY PA.id DESC";
                            $ris_cadidati_da_escludere = db_query($query_cadidati_da_escludere);
                            if (db_num_rows($ris_cadidati_da_escludere) > 0) {
                                while ($candidato_escluso = mysql_fetch_array($ris_cadidati_da_escludere)) {
                                    $collectors_da_escludere[] = $candidato_escluso['id_utente'];
                                }
                            }
                        }
                    }

                    if ($go_ahead) {

                        // CONTROLLO SE LA TABELLA DELLO STORICO AFFIDAMENTI ESISTE E CONTIENE UN RECORD CON IL COLLECTOR SELEZIONATO E IL DEBITORE SELEZIONATO
                        // SE TALE RIGA HA ESITO = 2 IL COLLECTOR è DA ESCLUDERE DALLA PROPOSIZIONE, ALTRIMENTI è DA PROPORRE PER DEFAULT
                        $verifica_storico = false;

                        if ($verifica_storico) {
                            $query_esistenza_storico = "SHOW TABLES LIKE 'tab_storico_affidamenti';";
                            $ris_esistenza_storico = db_query($query_esistenza_storico);
                            if (mysql_num_rows($ris_esistenza_storico) > 0) {
                                $query_storico = "SELECT esito 
												FROM tab_storico_affidamenti 
												WHERE id_collector = '" . $col_id . "'
													AND id_debitore = (SELECT id_debitore
																		FROM pratiche
																		WHERE id = '" . db_input($pratica) . "')";
                                $ris_storico = db_query($query_storico);
                                if (mysql_num_rows($ris_storico) > 0) {
                                    $storico = mysql_fetch_assoc($ris_storico);
                                    if ($storico['esito'] == 2) {
                                        if (($key = array_search($col_id, $lista_collector)) !== false) {
                                            unset($lista_collector[$key]);
                                        }
                                    } else {
                                        $collector_storico_positivo = $col_id;
                                    }
                                }
                            }
                        }

                        # RECUPERO I DATI NECESSARI ALLA VERIFICA DEL CRITERIO DI AFFIDAMENTO #5 RIGUARDO IL NUMERO MASSIMO DI AFFIDI PER TIPOLOGIA DI COLLECTOR
                        $limiti = false;
                        $limite_phc = 0;
                        $limite_esattore = 0;
                        $limite_totale = 0;

                        $query_pratica_e_parametri_affidamento_contratto = "SELECT PA.phc, PA.esattore, PA.totale, P.*
																			FROM pratiche P 
																			INNER JOIN contratto_parametro_affidamento PA ON P.id_contratto =  PA.id_contratto
																			WHERE P.id = '" . db_input($pratica) . "'
																			AND (PA.importo_fino > P.affidato_capitale OR PA.importo_fino IS NULL)
																			ORDER BY PA.importo_fino ASC
																			LIMIT 0,1";
                        $ris_dettaglio_pratica = db_query($query_pratica_e_parametri_affidamento_contratto);
                        $dettaglio_pratica = mysql_fetch_array($ris_dettaglio_pratica);

                        if ($dettaglio_pratica['phc'] != '' && $dettaglio_pratica['esattore'] != '' && $dettaglio_pratica['totale'] != '') {
                            $limiti = true;
                            $limite_phc = $dettaglio_pratica['phc'];
                            $limite_esattore = $dettaglio_pratica['esattore'];
                            $limite_totale = $dettaglio_pratica['totale'];
                        } else {
                            // RECUPERO I PARAMETRI DI AFFIDAMENTO DALLA TABELLA IMPOSTAZIONI DI BASE
                            $query_parametri_affidamento_base = "SELECT parametri_affidamento, parametri_affidamento_phc, parametri_affidamento_esattore
																FROM impostazioni_base";
                            $ris_parametri_affidamento_base = db_query($query_parametri_affidamento_base);
                            $parametri_affidamento_base = mysql_fetch_array($ris_parametri_affidamento_base);

                            if ($parametri_affidamento_base['parametri_affidamento_phc'] >= 0 && $parametri_affidamento_base['parametri_affidamento_esattore'] >= 0 && $parametri_affidamento_base['parametri_affidamento'] >= 0) {
                                $limiti = true;
                                $limite_phc = $parametri_affidamento_base['parametri_affidamento_phc'];
                                $limite_esattore = $parametri_affidamento_base['parametri_affidamento_esattore'];
                                //$limite_totale = $parametri_affidamento_base['parametri_affidamento'];
                                $limite_totale = $parametri_affidamento_base['parametri_affidamento_phc'] + $parametri_affidamento_base['parametri_affidamento_esattore'];
                            }
                        }
                        # FINE PREPARAZIONE CRITERIO DI AFFIDAMENTO #5

                        # CREAZIONE DELLA QUERY DI PROPOSIZIONE IN BASE AI CRITERI DI AFFIDAMENTO
                        $query = '';
                        {
                            // 2017-04-20: Aggiunto "AND PC.max_num_prat > 0" e "E.max_num_prat > 0"
                            $query = "SELECT U.*
								FROM (
									SELECT PC.id, PC.id_utente, 1 AS PHC, (CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) AS max_num_prat, COUNT(*) AS numero_pratiche, U.gruppi_base
									FROM phone_collector PC 
										INNER JOIN utente U on PC.id_utente = U.id_utente
										LEFT JOIN pratiche P on PC.id_utente = P.id_collector
									WHERE (U.attivo = 1 OR U.attivo IS NULL) AND PC.max_num_prat > 0
									GROUP BY PC.id_utente, PC.max_num_prat
									HAVING ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) = 0) OR ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) > COUNT(*))
								
									UNION
								
									SELECT E.id, E.id_utente, 0 AS PHC, (CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) AS max_num_prat, COUNT(*) AS numero_pratiche, U.gruppi_base
									FROM esattore E 
										INNER JOIN utente U on E.id_utente = U.id_utente
										LEFT JOIN pratiche P on E.id_utente = P.id_collector
									WHERE (U.attivo = 1 OR U.attivo IS NULL) AND E.max_num_prat > 0
									GROUP BY E.id_utente, E.max_num_prat
									HAVING ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) = 0) OR ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) > COUNT(*))
								) AS U  
								  
								LEFT JOIN zona_geografica_competenza UZ ON U.id_utente = UZ.id_utente  
								LEFT JOIN prodotti_lavorabili UP ON U.id_utente = UP.id_utente 
								LEFT JOIN credito C ON UP.id_prodotto = C.codice 
								LEFT JOIN carico_collector CC ON CC.id_collector = U.id_utente
								LEFT JOIN prodotti_lavorabili PL ON PL.id_prodotto = CC.codice
									 
								WHERE 1=1 " . $append_gruppo_base;
                        }

                        #4 PER I COLLECTOR CHE NON SONO PHC VERIFICARE NELLA SEZIONE [Zona Geografica Di Competenza] DELL'ANAGRAFICA ESATTORE QUELLE CON FLAG [Zona Di Competenza] = 1
                        # TRA QUESTE DEVE ESSERE PRESENTE LA ZONA DEL DEBITORE DELLA PRATICA DA AFFIDARE. L'INDIRIZZO DEL DEBITORE DA VERIFICARE E' QUELLO CON FLAG [Principale] = 1.
                        # SE LA ZONA DEL DEBITORE NON è PRESENTE TRA QUELLE INDICATE NELL'ANAGRAFICA ESATTORE, ALLORA L'ESATTORE VERRA' ESCLUSO DAI RISULTATI.
                        {
                            $query_dettagli_zona_competenza = "SELECT PR.nazione, PP.cod_regione, PR.provincia, PR.cap, PC.cod_istat 
														FROM pratiche P 
															INNER JOIN recapito PR ON P.id_debitore = PR.id_utente 
															INNER JOIN province PP ON PR.provincia = PP.cod_provincia
															INNER JOIN comuni PC ON (PR.citta = PC.comune AND PR.provincia = PC.cod_provincia)
														WHERE P.id = '" . db_input($pratica) . "' AND PR.predefinito = 1";
                            $dettaglio_zona_competenza = mysql_fetch_array(db_query($query_dettagli_zona_competenza));

                            $query .= " AND (U.PHC = 1 
										OR (U.PHC = 0 
												AND UZ.zona_esatt = 1 AND (
													(UZ.tipo_zona = 'Nazione' AND UZ.da = '" . db_input($dettaglio_zona_competenza['nazione']) . "')
													
												OR  (UZ.tipo_zona = 'Regione' AND UZ.da = '" . db_input($dettaglio_zona_competenza['cod_regione']) . "')
												
												OR  (UZ.tipo_zona = 'Provincia' AND UZ.da = '" . db_input($dettaglio_zona_competenza['provincia']) . "')
												
												OR  (UZ.tipo_zona = 'Cap' AND '" . db_input($dettaglio_zona_competenza['cap']) . "' BETWEEN UZ.da AND UZ.a)
												
												OR  (UZ.tipo_zona = 'Citta' AND UZ.da = '" . db_input($dettaglio_zona_competenza['cod_istat']) . "')
												)
											)   
										)";
                        }

                        #5 VERIFICARE SE LA PRATICA RIENTRA NEI LIMITI DI AFFIDAMENTO DEFINITI DA CONTRATTO O DA IMPOSTAZIONI DI BASE
                        # Se PHC verificare il numero massimo di affidameti a PHC
                        # Se ESATTORE verificare il numero massimo di affidamenti a ESATTORE
                        # La somma delle 2 tipologie di affidamento deve comunque rientrare nel limite massimo
                        if ($limiti) {
                            $query .= " AND (
										((U.PHC = 1 AND (SELECT COUNT(*) FROM affidamenti A LEFT JOIN pratiche_affidamenti PA ON PA.id_affidamento = A.id WHERE A.phc = 1 AND id_pratica = '" . db_input($pratica) . "') < '" . $limite_phc . "'))
										OR
										((U.PHC = 0 AND (SELECT COUNT(*) FROM affidamenti A LEFT JOIN pratiche_affidamenti PA ON PA.id_affidamento = A.id WHERE A.phc <> 1 AND id_pratica = '" . db_input($pratica) . "') < '" . $limite_esattore . "'))
									)
									AND ((
										(SELECT COUNT(*) FROM affidamenti A LEFT JOIN pratiche_affidamenti PA ON PA.id_affidamento = A.id WHERE A.phc <> 1 AND id_pratica = '" . db_input($pratica) . "') +
										(SELECT COUNT(*) FROM affidamenti A LEFT JOIN pratiche_affidamenti PA ON PA.id_affidamento = A.id WHERE A.phc = 1 AND id_pratica = '" . db_input($pratica) . "')
									) < '" . $limite_totale . "')";
                        }

                        #1 VERIFICARE NELL'ANAGRAFICA ESATTORE/PHC SE NELLA SEZIONE [PRODOTTI LAVORABILI] E' PRESENTE UNA TIPOLOGIA DI CREDITO DELLA PRATICA
                        # (reperibile dal [Contratto]->[Descrizione]->[Tipologia Credito]). SE LA TIIPOLOGIA DI CREDITO E' DIVERSA DA NULL E DIVERSA DALLA
                        # TIPOLOGIA DI CREDITO DELLA PRATICA ESCLUDERE IL COLLECTOR DAI RISULTATI
                        $query .= " AND ( C.id IS NULL OR C.id IN (SELECT id_tipo_credito FROM contratto INNER JOIN pratiche P1 ON contratto.id = P1.id_contratto WHERE P1.id = '" . db_input($pratica) . "'))";

                        #2 VERIFICARE CHE IL CARICO DEL COLLECTOR PRESO IN ESAME SIA COMPATIBILE CON LA PRATICA
                        # IL COLLECTOR NON DEVE AVER RAGGIUNTO IL NUMERO MASSIMO DI PRATICHE LAVORABILI PER LA TIPOLOGIA DI CREDITO DELLA PRATICA
                        $query .= " AND U.id_utente IN (" . implode(',', $lista_collector) . ")";

                        #3 VERIFICARE NEL [Contratto] DELLA PRATICA SE IL COLLECTOR E' PRESENTE NELLA SEZIONE [Affidamento]->[Agenti Esclusi],
                        # SE L'ESATTORE E' PRESENTE NELLA SEZIONE DEGLI AGENTI ESCLUSI DEVE ESSERE ESCLUSO DAI RISULTATI
                        $query .= " AND U.id_utente NOT IN (SELECT (CASE WHEN value_id IS NULL THEN 0 ELSE value_id END) AS value_id FROM pratiche Pbis LEFT JOIN view_agenti_esclusi Cbis ON Cbis.id = Pbis.id_contratto WHERE Pbis.id = '" . db_input($pratica) . "')";

                        #7 SE L'ESATTORE ERA STATO ASSEGNATO IN PRECEDENZA ALLA PRATICA, MA LA SUA PERFORMANCE HA AVUTO UNO SCARICO NEGATIVO LO ESCLUDO DAI RISULTATI
                        if (count($collectors_da_escludere) > 0)
                            $query .= " AND U.id_utente NOT IN (" . db_input(implode(',', $collectors_da_escludere)) . ")";

                        $query .= ' GROUP BY U.id_utente';

                        $query_affidamento = $query;

                        # PREPARO I CRITERI DI ORDINAMENTO DEI RISULTATI
                        $ordinamento_esattore = " ORDER BY ";
                        $ordinamento_phc = " ORDER BY ";

                        $rendimento_id_contratto = $row_pratica['id_contratto'];
                        $rendimento_id_pratica = $pratica;

                        $id_candidato_miglior_performance = 0;
                        $miglior_performance = 0;
                        $performances = array();

                        //die($query);

                        $ris_candidato = db_query($query);

                        //if(mysql_num_rows($ris_candidato) > 0) {
                        //	print_r($query); die();
                        //}
                        //if(false) {
                        while ($candidato = mysql_fetch_array($ris_candidato)) {
                            // INIZIO CALCOLO RENDIMENTO COLLECTORS PROPOSTI

                            // CONTROLLO IL CAMPO "CALCOLO RENDIMENTO COLLECTOR" e "MINIMO LAVORATO" PRESENTE A LIVELLO DI CONTRATTO
                            $query_dettagli_contratto = "SELECT * FROM contratto WHERE id = '" . $rendimento_id_contratto . "'";

                            $dettaglio_contratto = mysql_fetch_array(db_query($query_dettagli_contratto));

                            //echo 'MINIMO LAVORATO DA CONTRATTO: '.$dettaglio_contratto['minimo_lavorato'].' - ';

                            $minimo_lavorato = '';
                            if ($dettaglio_contratto['minimo_lavorato'] == '') {
                                $query_recupero_minimo_lavorato = 'SELECT minimo_lavorato FROM impostazioni_base WHERE id = 1';
                                $result_recupero_minimo_lavorato = db_query($query_recupero_minimo_lavorato);
                                if (db_num_rows($result_recupero_minimo_lavorato) > 0) {
                                    $minimo_lavorato_field = mysql_fetch_array($result_recupero_minimo_lavorato);
                                    $minimo_lavorato = $minimo_lavorato_field['minimo_lavorato'];
                                }
                            } else {
                                $minimo_lavorato = $dettaglio_contratto['minimo_lavorato'];
                            }

                            # 2.4 - VERIFICA CONDIZIONE CONTRATTO "ESCLUDI PRATICHE REVOCATE"
                            $query_append = '';
                            $escludi_pratiche_revocate = $dettaglio_contratto['escludi_pratiche_revocate'] == 0 ? false : true;
                            if ($escludi_pratiche_revocate) {
                                $query_append .= ' AND (EP.tipo <> "REVOCATA" OR EP.tipo IS NULL)';
                            }

                            $storicizzazione_dati = explode(':', $dettaglio_contratto['storicizzazione_dati']);

                            if ($candidato['PHC'] == 1) {
                                if ($storicizzazione_dati[5] == 1) {
                                    //$query_append_2 = ' AND A.data_scarico >= PR.data_registrazione';
                                    $query_append_2 = ' AND (SELECT COUNT(data_scarico) FROM pratiche_affidamenti WHERE id_affidamento = A.id AND scaricata <> 1) = 0';
                                } else if ($storicizzazione_dati[9] == 1) {
                                    $query_append_2 = ' AND P.data_fine_mandato >= PR.data_registrazione';
                                } else if ($storicizzazione_dati[13] == 1) {
                                    $query_append_2 = ' AND P.data_scarico >= PR.data_registrazione';
                                } else if ($storicizzazione_dati[17] == 1) {
                                    $query_append_2 = ' AND P.data_fine_mandato >= PR.data_registrazione';
                                } else if ($storicizzazione_dati[21] == 1) {
                                    $query_append_2 = ' AND DATE_ADD(A.data_affidamento,INTERVAL ' . db_input($storicizzazione_dati[21]) . ' DAY) >= PR.data_registrazione';
                                } else {
                                    $query_append_2 = '';
                                }
                            } else if ($candidato['PHC'] == 0) {
                                if ($storicizzazione_dati[4] == 1) {
                                    //$query_append_2 = ' AND A.data_scarico >= PR.data_registrazione';
                                    $query_append_2 = ' AND (SELECT COUNT(data_scarico) FROM pratiche_affidamenti WHERE id_affidamento = A.id AND scaricata <> 1) = 0';
                                } else if ($storicizzazione_dati[8] == 1) {
                                    $query_append_2 = ' AND P.data_fine_mandato >= PR.data_registrazione';
                                } else if ($storicizzazione_dati[12] == 1) {
                                    $query_append_2 = ' AND P.data_scarico >= PR.data_registrazione';
                                } else if ($storicizzazione_dati[16] == 1) {
                                    $query_append_2 = ' AND (PR.data_registrazione <= PA.data_fine_mandato_collector OR PA.data_fine_mandato_collector IS NULL)';
                                } else if ($storicizzazione_dati[20] != '') {
                                    $query_append_2 = ' AND DATE_ADD(A.data_affidamento,INTERVAL ' . db_input($storicizzazione_dati[20]) . ' DAY) >= PR.data_registrazione';
                                } else {
                                    $query_append_2 = '';
                                }
                            }

                            # RECUPERO I PARAMETRI DI RENDIMENTO E NE CALCOLO I RISULTATI
                            $calcolo_rendimento = '';
                            if ($dettaglio_contratto['calcolo_rendimento'] == '') {
                                $query_recupero_calcolo_rendimento = 'SELECT calcolo_rendimento FROM impostazioni_base WHERE id = 1';
                                $result_recupero_calcolo_rendimento = db_query($query_recupero_calcolo_rendimento);
                                if (db_num_rows($result_recupero_calcolo_rendimento) > 0) {
                                    $calcolo_rendimento_field = mysql_fetch_array($result_recupero_calcolo_rendimento);
                                    $calcolo_rendimento = $calcolo_rendimento_field['calcolo_rendimento'];
                                }
                            } else {
                                $calcolo_rendimento = $dettaglio_contratto['calcolo_rendimento'];
                            }

                            $calcolo_performance = true;

                            if ($calcolo_performance) {
                                if ($calcolo_rendimento == 'MOVIMENTATE') {
                                    # IL CALCOLO DEL RENDIMENTO DEL CONTRATTO PER IL COLLECTOR VIENE EFFETTUATO SUL NUMERO DI PRATICHE CHE HANNO IL CAMPO MOVIMENTATA > 0

                                    # RECUPERO IL NUMERO TOTALE DI PRATICHE COLLEGATE A QUESTO CONTRATTO
                                    $query_pratiche_totali = "SELECT COUNT(DISTINCT P.id) AS n
                                                            FROM affidamenti A
                                                                LEFT JOIN pratiche_affidamenti PA ON PA.id_affidamento = A.id
                                                                LEFT JOIN pratiche P ON P.id = PA.id_pratica
                                                                LEFT JOIN esiti_pratica EP ON EP.id = PA.id_esito_collector
                                                            WHERE P.id_contratto = '" . $rendimento_id_contratto . "'
                                                                AND P.id <> '" . $rendimento_id_pratica . "'
                                                                AND A.id_collector = '" . $candidato['id_utente'] . "'
                                                                /*AND PA.data_scarico IS NOT NULL*/";
                                    $pratiche_totali = mysql_fetch_array(db_query($query_pratiche_totali . $query_append));

                                    if ($pratiche_totali > 0) {
                                        // RECUPERO IL NUMERO TOTALE DI PRATICHE MOVIMENTATE DAL COLLECTOR CON LO STESSO CONTRATTO
                                        $query_pratiche_positive = "SELECT COUNT(DISTINCT P.id) AS n
                                                                    FROM affidamenti A
                                                                        LEFT JOIN pratiche_affidamenti PA ON PA.id_affidamento = A.id
                                                                        LEFT JOIN pratiche P ON P.id = PA.id_pratica
                                                                        LEFT JOIN esiti_pratica EP ON EP.id = PA.id_esito_collector
                                                                        LEFT JOIN storico_movimentazioni SM ON (SM.id_collector = A.id_collector AND SM.id_pratica = P.id)
                                                                    WHERE P.id_contratto = '" . $rendimento_id_contratto . "'
                                                                        AND P.id <> '" . $rendimento_id_pratica . "'
                                                                        AND A.id_collector = '" . $candidato['id_utente'] . "'
                                                                        /*AND PA.data_scarico IS NOT NULL*/
                                                                        AND SM.stato = 1";
                                        $pratiche_positive = mysql_fetch_array(db_query($query_pratiche_positive . $query_append));

                                        // INSERISCO I VALORI IN UN ARRAY PER DEBUG
                                        $performances[] = array('collector' => 1, 'performance' => 1);
                                        $performances[] = array('collector' => $candidato['id_utente'], 'performance' => $pratiche_positive['n'] * 100 / $pratiche_totali['n']);

                                        // VERIFICO IL RENDIMENTO E NEL CASO SIA MAGGIORE DI QUELLI VERIFICATI IN PRECEDENZA LO CONTRASSEGNO COME MIGLIORE
                                        //echo 'MOVIMENTATE | '.$candidato['id_utente'].' | '.($pratiche_positive['n']*100/$pratiche_totali['n']).' | '.$minimo_lavorato;
                                        if (($pratiche_positive['n'] * 100 / $pratiche_totali['n'] >= $miglior_performance || $miglior_performance == 0) && $pratiche_positive['n'] * 100 / $pratiche_totali['n'] >= $minimo_lavorato) {
                                            $posizione_collector_da_proporre = array_keys($lista_collector, $candidato['id_utente']);
                                            $posizione_collector_migliore = array_keys($lista_collector, $id_candidato_miglior_performance);

                                            if ($posizione_collector_da_proporre[0] <= $posizione_collector_migliore[0] || $id_candidato_miglior_performance == 0) {
                                                $miglior_performance = $pratiche_positive['n'] * 100 / $pratiche_totali['n'];
                                                $id_candidato_miglior_performance = $candidato['id_utente'];
                                            }
                                        }
                                    } else {
                                        // POICHE' NON VI SONO ALTRE PRATICHE GIA' ELABRATE CON LO STESSO CONTRATTO FACCIO IL CALCOLO DEL RENDIMENTO SULLA STESSA TIPOLOGIA DI CREDITO
                                        $query_pratiche_totali = "SELECT COUNT(DISTINCT P.id) AS n
                                                                FROM affidamenti A
                                                                    LEFT JOIN pratiche_affidamenti PA ON PA.id_affidamento = A.id
                                                                    LEFT JOIN pratiche P ON P.id = PA.id_pratica
                                                                    LEFT JOIN contratto C ON C.id = P.id_contratto
                                                                WHERE C.id_tipo_credito = '" . $row_pratica['id_tipo_credito'] . "'
                                                                    AND P.id <> '" . $rendimento_id_pratica . "'
                                                                    AND A.id_collector = '" . $candidato['id_utente'] . "'
                                                                    /*AND PA.data_scarico IS NOT NULL*/";
                                        $pratiche_totali = mysql_fetch_array(db_query($query_pratiche_totali));

                                        $query_pratiche_positive = "SELECT COUNT(DISTINCT P.id) AS n
                                                                    FROM affidamenti A
                                                                        LEFT JOIN pratiche_affidamenti PA ON PA.id_affidamento = A.id
                                                                        LEFT JOIN pratiche P ON P.id = PA.id_pratica
                                                                        LEFT JOIN contratto C ON C.id = P.id_contratto
                                                                        LEFT JOIN storico_movimentazioni SM ON (SM.id_collector = A.id_collector AND SM.id_pratica = P.id)	
                                                                    WHERE C.id_tipo_credito = '" . $row_pratica['id_tipo_credito'] . "'
                                                                        AND P.id <> '" . $rendimento_id_pratica . "'
                                                                        AND A.id_collector = '" . $candidato['id_utente'] . "'
                                                                        /*AND PA.data_scarico IS NOT NULL*/
                                                                        AND SM.stato = 1";
                                        $pratiche_positive = mysql_fetch_array(db_query($query_pratiche_positive));

                                        // VERIFICO IL RENDIMENTO E NEL CASO SIA MAGGIORE DI QUELLI VERIFICATI IN PRECEDENZA LO CONTRASSEGNO COME MIGLIORE
                                        //echo 'MOVIMENTATE | '.$candidato['id_utente'].' | '.($pratiche_positive['n']*100/$pratiche_totali['n']).' | '.$minimo_lavorato;
                                        if ($pratiche_positive['n'] * 100 / $pratiche_totali['n'] >= $miglior_performance || $miglior_performance == 0) {
                                            $posizione_collector_da_proporre = array_keys($lista_collector, $candidato['id_utente']);
                                            $posizione_collector_migliore = array_keys($lista_collector, $id_candidato_miglior_performance);

                                            if ($posizione_collector_da_proporre[0] <= $posizione_collector_migliore[0] || $id_candidato_miglior_performance == 0) {
                                                $miglior_performance = $pratiche_positive['n'] * 100 / $pratiche_totali['n'];
                                                $id_candidato_miglior_performance = $candidato['id_utente'];
                                            }
                                        }
                                    }
                                } else if ($calcolo_rendimento == 'SU_IMPORTO') {
                                    /*
                                # IL CALCOLO DEL RENDIMENTO DEL CONTRATTO VIENE CALCOLATO IN BASE ALLA FORMULA PRESENTE IN CALCOLO DA EFFETTUARE (specificato a livello di contratto)

                                # RECUPERO LA FORMULA DA UTILIZZARE PER IL CALCOLO DEL RENDIMENTO (nel caso non sia specificata sul contratto la ricerco a livello di impostazioni di base)
                                $contratto_formula = 0;
                                if($dettaglio_contratto['calcolo_da_effettuare'] != '') {
                                    $contratto_formula = $dettaglio_contratto['calcolo_da_effettuare'];
                                }
                                else {
                                    $query_recupero_calcolo_rendimento = 'SELECT calcolo_da_effettuare FROM impostazioni_base WHERE id = 1';
                                    $result_recupero_calcolo_rendimento = db_query($query_recupero_calcolo_rendimento);
                                    if(db_num_rows($result_recupero_calcolo_rendimento) > 0) {
                                        $calcolo_da_effettuare = mysql_fetch_array($result_recupero_calcolo_rendimento);
                                        $contratto_formula = $calcolo_da_effettuare['calcolo_da_effettuare'];
                                    }
                                }

                                //echo 'FORMULA CALCOLO: '.$contratto_formula.' - ';

                                # RECUPERO TUTTE LE SOMME DEI VALORI SU TUTTE LE PRATICHE DEL CONTRATTO
                                $array_value = pratiche_getSingoleQuoteContrattoCollector($rendimento_id_contratto, $candidato['id_utente'], $query_append, $query_append_2);

                                if($candidato['id_utente'] == 19370 || $candidato['id_utente'] == 19371) {
                                    //echo $candidato['id_utente'];
                                    //print_r($_SESSION['query1']);
                                    //print_r($_SESSION['query2']);
                                }

                                $array_value_quote_totali = pratiche_getSingoleQuoteContratto($rendimento_id_contratto);

                                // calcolo il dato di % di rendimento del collector
                                $ret = calcolatore($contratto_formula, $array_value);

                                // calcolo la % di lavorazione (basandomi sulla quota di affidato specificata nella formula del contratto) del collector
                                if(strpos($contratto_formula,'AFFCAP')!== FALSE) {
                                    $ret_quota_affidato = calcolatore('AFFCAP', $array_value)*100/calcolatore('AFFCAP', $array_value_quote_totali);
                                }
                                else if(strpos($contratto_formula,'AFFINT')!== FALSE) {
                                    $ret_quota_affidato = calcolatore('AFFINT', $array_value)*100/calcolatore('AFFINT', $array_value_quote_totali);
                                }
                                else if(strpos($contratto_formula,'AFFSPE')!== FALSE) {
                                    $ret_quota_affidato = calcolatore('AFFSPE', $array_value)*100/calcolatore('AFFSPE', $array_value_quote_totali);
                                }
                                else if(strpos($contratto_formula,'AFFAF1')!== FALSE) {
                                    $ret_quota_affidato = calcolatore('AFFAF1', $array_value)*100/calcolatore('AFFAF1', $array_value_quote_totali);
                                }
                                else if(strpos($contratto_formula,'AFFAF2')!== FALSE) {
                                    $ret_quota_affidato = calcolatore('AFFAF2', $array_value)*100/calcolatore('AFFAF2', $array_value_quote_totali);
                                }
                                else if(strpos($contratto_formula,'AFFAF3')!== FALSE) {
                                    $ret_quota_affidato = calcolatore('AFFAF3', $array_value)*100/calcolatore('AFFAF3', $array_value_quote_totali);
                                }
                                else if(strpos($contratto_formula,'AFFCPS')!== FALSE) {
                                    $ret_quota_affidato = calcolatore('AFFCPS', $array_value)*100/calcolatore('AFFCPS', $array_value_quote_totali);
                                }
                                else if(strpos($contratto_formula,'AFFCPI')!== FALSE) {
                                    $ret_quota_affidato = calcolatore('AFFCPI', $array_value)*100/calcolatore('AFFCPI', $array_value_quote_totali);
                                }
                                else if(strpos($contratto_formula,'TOTAFF')!== FALSE) {
                                    $ret_quota_affidato = calcolatore('TOTAFF', $array_value)*100/calcolatore('TOTAFF', $array_value_quote_totali);
                                }

                                //echo 'CONTRATTO: '.$rendimento_id_contratto.' - COLLECTOR: '.$candidato['id_utente'].' - ';
                                //echo 'VALORI PRATICA: '; print_r($array_value); echo ' - ';
                                */

                                    $ret = 0;
                                    $ret_quota_affidato = 0;

                                    if (isset($array_performances[$rendimento_id_contratto][$candidato['id_utente']]) && $array_performances[$rendimento_id_contratto][$candidato['id_utente']] != '') {
                                        $ret = $array_performances[$rendimento_id_contratto][$candidato['id_utente']]['performance'];
                                        $ret_quota_affidato = $array_performances[$rendimento_id_contratto][$candidato['id_utente']]['lavorazione'];
                                    }
                                    /*
                                $query_performance = "SELECT performance FROM collector_performance WHERE id_collector = '".$candidato['id_utente']."' AND id_contratto = '".$rendimento_id_contratto."'";
                                $ris_performance = db_query($query_performance);
                                if(mysql_num_rows($ris_performance)>0) {
                                    $result_performance = mysql_fetch_assoc($ris_performance);

                                    $ret = $result_performance['performance'];
                                }
                                */

                                    # INSERISCO I VALORI IN UN ARRAY PER DEBUG
                                    //$performances[] = array('collector' => 2, 'performance' => 2);
                                    $performances[] = array('collector' => $candidato['id_utente'], 'performance' => $ret, 'minimo_lavorato' => $minimo_lavorato, 'lavorazione' => $ret_quota_affidato);

                                    // VERIFICO IL RENDIMENTO E NEL CASO SIA MAGGIORE DI QUELLI VERIFICATI IN PRECEDENZA LO CONTRASSEGNO COME MIGLIORE
                                    //echo 'SU_IMPORTO | '.$candidato['id_utente'].' | '.($ret).' | '.$minimo_lavorato;

                                    if (($ret >= $miglior_performance || $miglior_performance == 0) && $ret_quota_affidato >= $minimo_lavorato) {
                                        $posizione_collector_da_proporre = array_keys($lista_collector, $candidato['id_utente']);
                                        $posizione_collector_migliore = array_keys($lista_collector, $id_candidato_miglior_performance);

                                        if ((($posizione_collector_da_proporre[0] <= $posizione_collector_migliore[0]) && $ret == $miglior_performance) || $id_candidato_miglior_performance == 0) {
                                            $miglior_performance = $ret;
                                            $id_candidato_miglior_performance = $candidato['id_utente'];
                                        } else if ($ret > $miglior_performance) {
                                            $miglior_performance = $ret;
                                            $id_candidato_miglior_performance = $candidato['id_utente'];
                                        }
                                        # MANUEL vecchia versione
                                        /*
                                    if($posizione_collector_da_proporre[0] <= $posizione_collector_migliore[0] || $id_candidato_miglior_performance == 0) {
                                        $miglior_performance = $ret;
                                        $id_candidato_miglior_performance = $candidato['id_utente'];
                                    }
                                    */
                                    }
                                }
                            } else {
                                $miglior_performance = 0;
                                $id_candidato_miglior_performance = 0;
                            }
                        }

                        //print_r($minimo_lavorato);
                        //print_r($performances); die();

                        // VERIFICO SE IL COLLECTOR è ASSENTE
                        $query_assenza = "(SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM phone_collector WHERE id_utente = '" . $id_candidato_miglior_performance . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01'))))) 
										  UNION 
									  (SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM esattore WHERE id_utente = '" . $id_candidato_miglior_performance . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01'))))) ";
                        $ris_assenza = db_query($query_assenza);

                        if (mysql_num_rows($ris_assenza) > 0) {
                            $sostituto = mysql_fetch_array($ris_assenza);
                            // PROPONGO IL SOSTITUTO DEL COLLECTOR
                            if ($sostituto['id_oper_sost'] > 0) {
                                $affidamenti[] = array(
                                    'pratica' => $pratica,
                                    'candidato' => $sostituto['id_oper_sost'],
                                    'tipo' => 'AFFIDO PER SOSTITUZIONE',
                                    //'collector_assegnati_debitore' => $array_collector_assegnati_debitore,
                                    //'performances' => $performances,
                                    //'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                                    //'query' => $array_query_carichi[$candidato['id_utente']]
                                );

                                if ($sostituto['id_oper_sost'] > 0)
                                    $array_collector_assegnati_debitore[$debitore['id_debitore']] = $sostituto['id_oper_sost'];
                            } else {
                                // PROPONGO IL COLLECTOR
                                $affidamenti[] = array(
                                    'pratica' => $pratica,
                                    //'candidato' => $candidato['id_utente']
                                    'candidato' => $id_candidato_miglior_performance,
                                    'tipo' => 'PROPOSIZIONE',
                                    //'carichi' => $lista_collector_carichi,
                                    //'collector_assegnati_debitore' => $array_collector_assegnati_debitore,
                                    //'performances' => $performances
                                );

                                if ($id_candidato_miglior_performance)
                                    $array_collector_assegnati_debitore[$debitore['id_debitore']] = $id_candidato_miglior_performance;
                            }
                        } else {
                            // PROPONGO IL COLLECTOR
                            $affidamenti[] = array(
                                'pratica' => $pratica,
                                //'candidato' => $candidato['id_utente']
                                'candidato' => $id_candidato_miglior_performance,
                                'tipo' => 'PROPOSIZIONE',
                                //'carichi' => $lista_collector_carichi,
                                //'collector_assegnati_debitore' => $array_collector_assegnati_debitore,
                                //'performances' => $performances,
                                //'query' => $array_query_carichi[$id_candidato_miglior_performance]
                            );

                            if ($id_candidato_miglior_performance)
                                $array_collector_assegnati_debitore[$debitore['id_debitore']] = $id_candidato_miglior_performance;
                        }

                        if ($n_affidamenti_collector[$id_candidato_miglior_performance][$row_pratica['codice']] > 0) {
                            $n_affidamenti_collector[$id_candidato_miglior_performance][$row_pratica['codice']]++;
                        } else {
                            $n_affidamenti_collector[$id_candidato_miglior_performance][$row_pratica['codice']] = 1;
                        }
                        //}
                        // FINE - PROPONGO IL COLLECTOR
                    }
                }
            }

            //print_r($array_collector_assegnati_debitore);

            // CICLO NUOVAMENTE LE PRATICHE PER VEDERE QUALI POSSONO, DOPO IL PRIMO PASSAGGIO, RICADERE NELLA CONDIZIONE N.7 DI "GESTIONE RIAFFIDO"
            foreach ($pratiche as $pratica) {

                //db_close();

                //CONNESSIONE AL DB (IN CASO DI ERRORE DIE CON MESSAGGIO DAL SERVER)
                $link = db_connect($db_server, $username, $password);
                $sqlconnect = db_select_db($nomeDb, $link);

                //echo 'PRATICA: '.$pratica;

                $query_debitore = "SELECT id_debitore
								FROM pratiche
								WHERE id = '" . db_input($pratica) . "'";
                $ris_debitore = db_query($query_debitore);
                $debitore = mysql_fetch_array($ris_debitore);

                //echo PHP_EOL;
                //echo $debitore['id_debitore'];

                if ($array_collector_assegnati_debitore[$debitore['id_debitore']] > 0) {
                    $found = false;

                    for ($i = 0; $i < count($affidamenti); $i++) {
                        //if($affidamenti[$i]['pratica'] == $pratica && $affidamenti[$i]['candidato']==0) {
                        if ($affidamenti[$i]['pratica'] == $pratica && $affidamenti[$i]['tipo'] != 'DIRETTO') {
                            $affidamenti[$i]['candidato'] = $array_collector_assegnati_debitore[$debitore['id_debitore']];
                            //$affidamenti[$i]['tipo'] = 'RIAFFIDO DA DIR O PROP';
                        }
                    }
                }
            }

            //die();

            print_r(json_encode($affidamenti));
        }
        break;
    case 'proposizione-affidamento-home':
        {
            $affidamenti = array();
            $array_esa_prt = [];

            $append_gruppo_base = '';

            if (isset($_POST['esa']) && $_POST['esa'] == 'ESA') {
                $append_gruppo_base = "AND (gruppi_base = '6'
										OR gruppi_base LIKE '%,6,%'
										OR gruppi_base LIKE '%,6'
										OR gruppi_base LIKE '6,%'
										OR gruppi_base = '7'
										OR gruppi_base LIKE '%,7,%'
										OR gruppi_base LIKE '%,7'
										OR gruppi_base LIKE '7,%')";
            } else if (isset($_POST['esa']) && $_POST['esa'] == 'PHC') {
                $append_gruppo_base = "AND (gruppi_base = '3'
										OR gruppi_base LIKE '%,3,%'
										OR gruppi_base LIKE '%,3'
										OR gruppi_base LIKE '3,%'
										OR gruppi_base = '12'
										OR gruppi_base LIKE '%,12,%'
										OR gruppi_base LIKE '%,12'
										OR gruppi_base LIKE '12,%')";
            } else {
                $append_gruppo_base = "AND (gruppi_base = '6'
										OR gruppi_base LIKE '%,6,%'
										OR gruppi_base LIKE '%,6'
										OR gruppi_base LIKE '6,%'
										OR gruppi_base = '3'
										OR gruppi_base LIKE '%,3,%'
										OR gruppi_base LIKE '%,3'
										OR gruppi_base LIKE '3,%'
										OR gruppi_base = '7'
										OR gruppi_base LIKE '%,7,%'
										OR gruppi_base LIKE '%,7'
										OR gruppi_base LIKE '7,%'
										OR gruppi_base = '12'
										OR gruppi_base LIKE '%,12,%'
										OR gruppi_base LIKE '%,12'
										OR gruppi_base LIKE '12,%')";
            }

            // INIZIALIZZO DEI CONTATORI CHE MI TENGANO TRACCIA DEL NUMERO DI PROPOSIZIONI FATTE PER OGNI COLLECTOR
            $n_affidamenti_collector = array();
            $array_query_carichi = array();
            $array_collector_assegnati_debitore = array();

            $pratiche = explode(',', str_replace(array('[', ']', '"'), '', $_POST['id_pratiche']));

            $array_performances = array();
            if (str_replace(array('[', ']', '"'), '', $_POST['id_pratiche']) != '') {
                $query_performance = "SELECT * FROM collector_performance WHERE id_contratto IN (SELECT id_contratto FROM pratiche WHERE id IN (" . str_replace(array('[', ']', '"'), '', $_POST['id_pratiche']) . "))";
                $ris_performance = db_query($query_performance);
                if (mysql_num_rows($ris_performance) > 0) {
                    while ($result_performance = mysql_fetch_assoc($ris_performance)) {
                        $array_performances[$result_performance['id_contratto']][$result_performance['id_collector']]['performance'] = $result_performance['performance'];
                        $array_performances[$result_performance['id_contratto']][$result_performance['id_collector']]['lavorazione'] = $result_performance['lavorazione'];
                    }
                }
            }
            session_start([
                'read_and_close' => true,
            ]);


            $query_prt_mappa = "SELECT PROV.cod_provincia as prov,IFNULL(PRRG.tot_pratiche,0) as tot_pratiche,PROV.provincia as den  from province PROV Left JOIN (
SELECT count(P.id) as tot_pratiche, PR.cod_provincia as provCod
FROM pratiche P
         RIGHT JOIN recapito R ON (P.id_debitore = R.id_utente AND R.predefinito = 1)
         RIGHT JOIN province PR ON R.provincia = PR.cod_provincia
WHERE P.id IN (" . implode(',', $pratiche) . ")
GROUP BY PR.cod_provincia) as PRRG ON PRRG.provCod=PROV.cod_provincia";


            $praticheMappa = db_fetch_array_assoc(db_query($query_prt_mappa));
            $array_regione_prt = [];
            foreach ($praticheMappa as $row) {
                $array_regione_prt[$row['prov']] = $row['den'] . ' (' . $row['tot_pratiche'] . ')';
                $array_regione_prt_val[$row['prov']] = $row['tot_pratiche'];
            }


            foreach ($pratiche as $pratica) {

                //db_close();

                //CONNESSIONE AL DB (IN CASO DI ERRORE DIE CON MESSAGGIO DAL SERVER)
                $link = db_connect($db_server, $username, $password);
                $sqlconnect = db_select_db($nomeDb, $link);

                # RECUPERO LA LISTA DI TUTTI I COLLECTOR CHE POSSONO GESTIRE LE PRATICHE
                $lista_collector = array();
                $lista_collector_max_pratiche = array();
                $lista_collector_carichi = array();

                $ruoloUtente = $_SESSION['user_role'];
                $append_vincoli_capoarea_collector = '';

                if ($ruoloUtente == CAPO_ESATTORE) {
                    $append_vincoli_capoarea_collector = ' AND (U.id_utente = ' . $_SESSION['user_admin_id'] . ' 
														OR U.id_utente IN (SELECT id_collegato FROM collegati_esattore WHERE id_utente = ' . $_SESSION['user_admin_id'] . '))';
                }

                $query_collectors = "SELECT U.id_utente, cognome, nome, codice_fiscale
															FROM utente U 
															LEFT JOIN (
																		(SELECT id_utente, assente, assente_da, assente_a FROM phone_collector) 
																			UNION 
																		(SELECT id_utente, assente, assente_da, assente_a FROM esattore)
																	   ) AS C ON U.id_utente = C.id_utente
															WHERE U.attivo = 1 " . $append_gruppo_base . $append_vincoli_capoarea_collector;

                # RECUPERO LA PRIORITA' DI AFFIDO PER ORDINARE LA LISTA DEI COLLECTOR
                $ordinamento_priorita_affido = '';
                $query_lista_collector = '';

                $query_recupero_priorita_affido = 'SELECT priorita_affido FROM impostazioni_base WHERE id = 1';
                $result_recupero_priorita_affido = db_query($query_recupero_priorita_affido);
                $priorita_affido = mysql_fetch_array($result_recupero_priorita_affido);

                if ($priorita_affido['priorita_affido'] != '') {
                    # IN BASE ALLA PRIORITA RECUPERATA CREO LA LISTA ORDINATA DEI COLLECTOR
                    if ($priorita_affido['priorita_affido'] == 'PHC') {
                        $query_lista_collector = "SELECT PC.id_utente, PC.max_num_prat, PC.assente, PC.assente_da, PC.assente_a, 1 AS PHC
												  FROM phone_collector PC 
													  INNER JOIN utente U on PC.id_utente = U.id_utente
													  LEFT JOIN pratiche P on PC.id_utente = P.id_collector
												  WHERE (U.attivo = 1 OR U.attivo IS NULL) " . $append_gruppo_base . $append_vincoli_capoarea_collector . "
												  GROUP BY PC.id_utente, PC.max_num_prat
												  HAVING ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) = 0) OR ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) > COUNT(*))
											  
												  UNION
											  
												  SELECT E.id_utente, E.max_num_prat, E.assente, E.assente_da, E.assente_a, 0 AS PHC
												  FROM esattore E 
													  INNER JOIN utente U on E.id_utente = U.id_utente
													  LEFT JOIN pratiche P on E.id_utente = P.id_collector
												  WHERE (U.attivo = 1 OR U.attivo IS NULL) " . $append_gruppo_base . $append_vincoli_capoarea_collector . "
												  GROUP BY E.id_utente, E.max_num_prat
												  HAVING ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) = 0) OR ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) > COUNT(*))";
                    } else if ($priorita_affido['priorita_affido'] == 'ESA') {
                        $query_lista_collector = "SELECT E.id_utente, E.max_num_prat, E.assente, E.assente_da, E.assente_a, 0 AS PHC
												  FROM esattore E 
													  INNER JOIN utente U on E.id_utente = U.id_utente
													  LEFT JOIN pratiche P on E.id_utente = P.id_collector
												  WHERE (U.attivo = 1 OR U.attivo IS NULL) " . $append_gruppo_base . $append_vincoli_capoarea_collector . "
												  GROUP BY E.id_utente, E.max_num_prat
												  HAVING ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) = 0) OR ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) > COUNT(*))
												  UNION
												  
												  SELECT PC.id_utente, PC.max_num_prat, PC.assente, PC.assente_da, PC.assente_a, 1 AS PHC
												  FROM phone_collector PC 
													  INNER JOIN utente U on PC.id_utente = U.id_utente
													  LEFT JOIN pratiche P on PC.id_utente = P.id_collector
												  WHERE (U.attivo = 1 OR U.attivo IS NULL) " . $append_gruppo_base . $append_vincoli_capoarea_collector . "
												  GROUP BY PC.id_utente, PC.max_num_prat
												  HAVING ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) = 0) OR ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) > COUNT(*))";
                    }
                } else {
                    $query_lista_collector = "SELECT PC.id_utente, PC.max_num_prat, PC.assente, PC.assente_da, PC.assente_a, 1 AS PHC
											  FROM phone_collector PC 
												  INNER JOIN utente U on PC.id_utente = U.id_utente
												  LEFT JOIN pratiche P on PC.id_utente = P.id_collector
											  WHERE (U.attivo = 1 OR U.attivo IS NULL) " . $append_gruppo_base . $append_vincoli_capoarea_collector . "
											  GROUP BY PC.id_utente, PC.max_num_prat
											  HAVING ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) = 0) OR ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) > COUNT(*))
										  
											  UNION
										  
											  SELECT E.id_utente, E.max_num_prat, E.assente, E.assente_da, E.assente_a, 0 AS PHC
											  FROM esattore E 
												  INNER JOIN utente U on E.id_utente = U.id_utente
												  LEFT JOIN pratiche P on E.id_utente = P.id_collector
											  WHERE (U.attivo = 1 OR U.attivo IS NULL) " . $append_gruppo_base . $append_vincoli_capoarea_collector . "
											  GROUP BY E.id_utente, E.max_num_prat
											  HAVING ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) = 0) OR ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) > COUNT(*))";
                }

                /*
            echo 'QUERY LISTA COLLECTOR:';
            echo PHP_EOL;
            echo $query_lista_collector;
            echo PHP_EOL;
            echo PHP_EOL;
            */

                $ris_lista_collector = db_query($query_lista_collector);

                if (strpos($_POST['collectors_esclusi'], ',') > 0)
                    $collector_esclusi_da_proposizione = explode(',', $_POST['collectors_esclusi']);
                else
                    $collector_esclusi_da_proposizione = $_POST['collectors_esclusi'];

                while ($row_lista_collector = mysql_fetch_array($ris_lista_collector)) {
                    if (!in_array($row_lista_collector['id_utente'], $collector_esclusi_da_proposizione)) {
                        $lista_collector[] = $row_lista_collector['id_utente'];
                        $lista_collector_max_pratiche[$row_lista_collector['id_utente']] = $row_lista_collector['max_num_prat'];
                    }
                }
                # FINE RECUPERO LISTA COLLECTOR POSSIBILI

                // RECUPERO ALCUNI DETTAGLI DELLA PRATICA CHE POI SERVIRANNO NEI CONTROLLI DI AFFIDABILITA'
                $query_pratica = "SELECT C.id_tipo_credito, CR.codice, P.id_contratto
											FROM pratiche P 
												LEFT JOIN contratto C ON P.id_contratto = C.id
												LEFT JOIN credito CR ON CR.id = C.id_tipo_credito
											WHERE P.id = '" . db_input($pratica) . "'";
                $row_pratica = mysql_fetch_array(db_query($query_pratica));

                $collector_storico_positivo = 0;

                // VERIFICO LE CONDIZIONI DI CARICO DI OGNI COLLECTOR
                for ($z = 0; $z < count($lista_collector); $z++) {
                    $col_id = $lista_collector[$z];

                    // CONTROLLO SE IL COLLECTOR PUO' GESTIRE DELLE TIPOLOGIE DI CREDITO
                    $query_carico_tipologie_credito = "SELECT gg_lavorabili AS n FROM prodotti_lavorabili WHERE id_utente = '" . $col_id . "'";
                    $ris_carico_tipologie_credito = db_query($query_carico_tipologie_credito);

                    if (db_num_rows($ris_carico_tipologie_credito) > 0) {
                        // RECUPERO IL NUMERO MASSIMO DI PRATICHE DI QUESTA TIPOLOGIA CHE OGNI COLLECTOR PUO' GESTIRE
                        $query_max_carico = "SELECT gg_lavorabili AS n FROM prodotti_lavorabili WHERE id_utente = '" . $col_id . "' AND id_prodotto = '" . $row_pratica['codice'] . "'";
                        $ris_max_carico = db_query($query_max_carico);

                        if (db_num_rows($ris_max_carico) > 0) {
                            $array_query_carichi[$col_id]['max'] = $query_max_carico;
                            $row_max_carico = mysql_fetch_array($ris_max_carico);
                            $max_carico = $row_max_carico['n'];
                            $carico_massimo = $max_carico;

                            // RECUPERO IL CARICO ATTUALE DEL COLLECTOR PER QUESTA TIPOLOGIA
                            $carico_attuale = 0;
                            $query_carico_attuale = "SELECT carico AS n FROM carico_collector  WHERE id_collector = '" . $col_id . "' AND codice = '" . $row_pratica['codice'] . "'";
                            $ris_carico_attuale = db_query($query_carico_attuale);
                            $array_query_carichi[$col_id]['act'] = $query_carico_attuale;
                            if (db_num_rows($ris_carico_attuale) > 0) {
                                $carico_att = mysql_fetch_array($ris_carico_attuale);
                                $carico_attuale = $carico_att['n'];
                            }

                            $carico_proposto = $n_affidamenti_collector[$col_id][$row_pratica['codice']] > 0 ? $n_affidamenti_collector[$col_id][$row_pratica['codice']] : 0;

                            if ($carico_massimo == ($carico_attuale + $carico_proposto)) {  // || $max_carico == 0
                                if (($key = array_search($col_id, $lista_collector)) !== false) {
                                    unset($lista_collector[$key]);
                                }
                            }

                            $lista_collector_carichi[$col_id]['max'] = $carico_massimo;
                            $lista_collector_carichi[$col_id]['act'] = $carico_attuale;
                            $lista_collector_carichi[$col_id]['pro'] = $carico_proposto;
                        } else {
                            if (($key = array_search($col_id, $lista_collector)) !== false) {
                                unset($lista_collector[$key]);
                            }
                        }
                    } else {
                        // RECUPERO IL NUMERO MASSIMO DI PRATICHE DI QUESTA TIPOLOGIA CHE OGNI COLLECTOR PUO' GESTIRE
                        $array_query_carichi[$col_id]['max'] = $lista_collector_max_pratiche[$col_id];
                        $max_carico = $lista_collector_max_pratiche[$col_id];

                        $carico_massimo = $max_carico;

                        // RECUPERO IL CARICO ATTUALE DEL COLLECTOR PER QUESTA TIPOLOGIA
                        $carico_attuale = 0;
                        $query_carico_attuale = "SELECT SUM(carico) AS n FROM carico_collector WHERE id_collector = '" . $col_id . "'";
                        $ris_carico_attuale = db_query($query_carico_attuale);
                        $array_query_carichi[$col_id]['act'] = $query_carico_attuale;
                        if (db_num_rows($ris_carico_attuale) > 0) {
                            $carico_att = mysql_fetch_array($ris_carico_attuale);
                            $carico_attuale = $carico_att['n'];
                        }

                        $carico_proposto = 0;
                        foreach ($n_affidamenti_collector[$col_id] as $cpg) {
                            $carico_proposto += $cpg;
                        }

                        if ($carico_massimo == ($carico_attuale + $carico_proposto)) {
                            if (($key = array_search($col_id, $lista_collector)) !== false) {
                                unset($lista_collector[$key]);
                            }
                        }

                        $lista_collector_carichi[$col_id]['max'] = $carico_massimo;
                        $lista_collector_carichi[$col_id]['act'] = $carico_attuale;
                        $lista_collector_carichi[$col_id]['pro'] = $carico_proposto;
                    }
                }
                //print_r($lista_collector_carichi);

                #6 (FUNZIONANTE) SE SULLA PRATICA E' PRESENTE UN'ANAGRAFICA AFFIDO, SI FORZA QUEST'ULTIMA SENZA FARE NESSUN CONTROLLO (ZONA, CARICO DI LAVORO, TIPOLOGIA DI CREDITO ...)
                $query_cadidato_affido = "SELECT id_anagrafica_candidato_affido as id_utente, id_debitore
										FROM pratiche 
										WHERE id = '" . db_input($pratica) . "' 
										AND id_anagrafica_candidato_affido > 0";

                $ris_cadidato_affido = db_query($query_cadidato_affido);
                if (mysql_num_rows($ris_cadidato_affido)) {
                    $candidato = mysql_fetch_array($ris_cadidato_affido);

                    // VERIFICO SE IL COLLECTOR è ASSENTE
                    $query_assenza = "(SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM phone_collector WHERE id_utente = '" . $candidato['id_utente'] . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01'))))) 
									  UNION 
								  (SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM esattore WHERE id_utente = '" . $candidato['id_utente'] . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01')))))";
                    $ris_assenza = db_query($query_assenza);

                    if (mysql_num_rows($ris_assenza) > 0) {
                        $sostituto = mysql_fetch_array($ris_assenza);
                        // PROPONGO IL SOSTITUTO DEL COLLECTOR
                        $affidamenti[] = array(
                            'pratica' => $pratica,
                            'candidato' => $sostituto['id_oper_sost'],
                            'tipo' => 'DIRETTO',
                            //'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                            //'query' => $array_query_carichi[$candidato['id_utente']]
                        );

                        $array_esa_prt[$sostituto['id_oper_sost']][] = $pratica;

                        if ($sostituto['id_oper_sost'] > 0)
                            $array_collector_assegnati_debitore[$candidato['id_debitore']] = $sostituto['id_oper_sost'];
                    } else {
                        // PROPONGO IL COLLECTOR
                        $affidamenti[] = array(
                            'pratica' => $pratica,
                            'candidato' => $candidato['id_utente'],
                            'tipo' => 'DIRETTO',
                            //'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                            //'query' => $array_query_carichi[$candidato['id_utente']]
                        );
                        $array_esa_prt[$candidato['id_utente']][] = $pratica;

                        if ($candidato['id_utente'] > 0)
                            $array_collector_assegnati_debitore[$candidato['id_debitore']] = $candidato['id_utente'];
                    }

                    /* I CARICHI PROPOSTI FORZATAMENTE NON VENGONO CONTEGGIATI SUL CARICO GLOBALE
                if($n_affidamenti_collector[$candidato['id_utente']][$row_pratica['codice']] > 0) {
                    $n_affidamenti_collector[$candidato['id_utente']][$row_pratica['codice']]++;
                }
                else {
                    $n_affidamenti_collector[$candidato['id_utente']][$row_pratica['codice']] = 1;
                }
                */
                    // FINE - PROPONGO IL COLLECTOR
                } else if ($collector_storico_positivo > 0) {
                    $candidato = array();
                    $candidato['id_utente'] = $collector_storico_positivo;
                    $query_debitore = "SELECT id_debitore
									FROM pratiche
									WHERE id = '" . db_input($pratica) . "'";
                    $ris_debitore = db_query($query_debitore);
                    $debitore = mysql_fetch_array($ris_debitore);
                    $candidato['id_debitore'] = $debitore['id_debitore'];

                    // VERIFICO SE IL COLLECTOR è ASSENTE
                    $query_assenza = "(SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM phone_collector WHERE id_utente = '" . $candidato['id_utente'] . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01'))))) 
									  UNION 
								  (SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM esattore WHERE id_utente = '" . $candidato['id_utente'] . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01')))))";
                    $ris_assenza = db_query($query_assenza);

                    if (mysql_num_rows($ris_assenza) > 0) {
                        $sostituto = mysql_fetch_array($ris_assenza);
                        // PROPONGO IL SOSTITUTO DEL COLLECTOR
                        $affidamenti[] = array(
                            'pratica' => $pratica,
                            'candidato' => $sostituto['id_oper_sost'],
                            'tipo' => 'DIRETTO DA STORICO PER SOSTITUZIONE',
                            //'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                            //'query' => $array_query_carichi[$candidato['id_utente']]
                        );
                        $array_esa_prt[$sostituto['id_oper_sost']][] = $pratica;


                        if ($sostituto['id_oper_sost'] > 0)
                            $array_collector_assegnati_debitore[$candidato['id_debitore']] = $sostituto['id_oper_sost'];
                    } else {
                        // PROPONGO IL COLLECTOR
                        $affidamenti[] = array(
                            'pratica' => $pratica,
                            'candidato' => $candidato['id_utente'],
                            'tipo' => 'DIRETTO DA STORICO',
                            //'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                            //'query' => $array_query_carichi[$candidato['id_utente']]
                        );
                        $array_esa_prt[$candidato['id_utente']][] = $pratica;


                        if ($candidato['id_utente'] > 0)
                            $array_collector_assegnati_debitore[$candidato['id_debitore']] = $candidato['id_utente'];
                    }
                    $affidamenti[] = array(
                        'pratica' => $pratica,
                        'candidato' => $collector_storico_positivo,
                        'tipo' => 'DIRETTO DA STORICO',
                        //'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                        //'query' => $array_query_carichi[$candidato['id_utente']]
                    );
                    $array_esa_prt[$collector_storico_positivo][] = $pratica;

                } else {
                    $go_ahead = false;
                    $collectors_da_escludere = array();

                    #7 SE LA PRATICA SI RIFERISCE AD UN DEBITORE RECIDIVO E VI E' UNA PRATICA COLLEGATA ATTIVA IL COLLECTOR DA PROPORRE E' LO STESSO DELLA PRATICA ATTIVA
                    #  NEL CASO IN CUI TUTTE LE PRATICHE SIANO STATE CHIUSE I CASI POSSONO ESSERE 2:
                    #  SE LA PRATICA HA ESITO DI SCARICO COLLECTOR POSITIVO PROPONE QUEL COLLECTOR
                    #  SE LA PRATICA HA ESITO DI SCARICO NEGATIVO ALLORA ESCLUDE QUEL COLLECTOR

                    #  STEP 1: RECUPERO IL DEBITORE DELLA PRATICA DA ASSEGNARE
                    $query_debitore = "SELECT id_debitore
									FROM pratiche
									WHERE id = '" . db_input($pratica) . "'";
                    $ris_debitore = db_query($query_debitore);
                    $debitore = mysql_fetch_array($ris_debitore);

                    if ($array_collector_assegnati_debitore[$debitore['id_debitore']] > 0) {
                        // VERIFICO SE IL COLLECTOR è ASSENTE
                        $query_assenza = "(SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM phone_collector WHERE id_utente = '" . $array_collector_assegnati_debitore[$debitore['id_debitore']] . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01')))))
										  UNION 
									  (SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM esattore WHERE id_utente = '" . $array_collector_assegnati_debitore[$debitore['id_debitore']] . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01')))))";
                        $ris_assenza = db_query($query_assenza);

                        if (mysql_num_rows($ris_assenza) > 0) {
                            $sostituto = mysql_fetch_array($ris_assenza);
                            // PROPONGO IL SOSTITUTO DEL COLLECTOR
                            $affidamenti[] = array(
                                'pratica' => $pratica,
                                'candidato' => $sostituto['id_oper_sost'],
                                'tipo' => 'RIAFFIDO DA PROPOSIZIONE',
                                //'collector_assegnati_debitore' => $array_collector_assegnati_debitore,
                                //'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                                //'query' => $array_query_carichi[$candidato['id_utente']]
                            );
                            $array_esa_prt[$sostituto['id_oper_sost']][] = $pratica;

                        } else {
                            $affidamenti[] = array(
                                'pratica' => $pratica,
                                'candidato' => $array_collector_assegnati_debitore[$debitore['id_debitore']],
                                'tipo' => 'RIAFFIDO DA PROPOSIZIONE',
                                //'collector_assegnati_debitore' => $array_collector_assegnati_debitore,
                                //'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                                //'query' => $array_query_carichi[$candidato['id_utente']]
                            );
                            $array_esa_prt[$array_collector_assegnati_debitore[$debitore['id_debitore']]][] = $pratica;

                        }
                    } else {
                        #  STEP 2: RECUPERO L'ULTIMA PRATICA ASSOCIATA AL DEBITORE ESCLUDENDO QUELLA IN ESAME
                        #  STEP 3: RECUPERO L'EVENTUALE COLLECTOR DA ASSEGNARE ALLA NUOVA PRATICA
                        $query_cadidato_affido_2 = "SELECT id_collector as id_utente, EP.tipo, PA.data_scarico, PA.id_pratica
													FROM pratiche_affidamenti PA
														LEFT JOIN affidamenti A ON PA.id_affidamento = A.id
														LEFT JOIN esiti_pratica EP ON EP.id = PA.id_esito_collector
													WHERE PA.id_pratica IN (SELECT id 
																				FROM pratiche 
																				WHERE id <> '" . db_input($pratica) . "' 
																				AND id_debitore = '" . db_input($debitore['id_debitore']) . "' 
																				ORDER BY id DESC)
													AND id_collector IS NOT NULL
													AND id_collector IN (SELECT id_utente FROM utente WHERE attivo = 1)
													ORDER BY PA.id DESC
													LIMIT 0,1";
                        $ris_cadidato_affido_2 = db_query($query_cadidato_affido_2);
                        if (db_num_rows($ris_cadidato_affido_2)) {
                            $candidato = mysql_fetch_array($ris_cadidato_affido_2);

                            if ($candidato['tipo'] == 'POSITIVA' || $candidato['data_scarico'] == '') {
                                // VERIFICO SE IL COLLECTOR è ASSENTE
                                $query_assenza = "(SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM phone_collector WHERE id_utente = '" . $candidato['id_utente'] . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01'))))) 
												  UNION 
											  (SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM esattore WHERE id_utente = '" . $candidato['id_utente'] . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01'))))) ";
                                $ris_assenza = db_query($query_assenza);

                                if (mysql_num_rows($ris_assenza) > 0) {
                                    $sostituto = mysql_fetch_array($ris_assenza);
                                    // PROPONGO IL SOSTITUTO DEL COLLECTOR
                                    if ($sostituto['id_oper_sost'] > 0) {
                                        $affidamenti[] = array(
                                            'pratica' => $pratica,
                                            'candidato' => $sostituto['id_oper_sost'],
                                            'tipo' => 'RIAFFIDO',
                                            //'collector_assegnati_debitore' => $array_collector_assegnati_debitore,
                                            //'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                                            //'query' => $array_query_carichi[$candidato['id_utente']]
                                        );

                                        $array_esa_prt[$sostituto['id_oper_sost']][] = $pratica;


                                        $array_collector_assegnati_debitore[$debitore['id_debitore']] = $sostituto['id_oper_sost'];
                                    } else if ($candidato['id_utente'] > 0) {
                                        $affidamenti[] = array(
                                            'pratica' => $pratica,
                                            'candidato' => $candidato['id_utente'],
                                            'tipo' => 'RIAFFIDO',
                                            //'collector_assegnati_debitore' => $array_collector_assegnati_debitore,
                                            //'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                                            //'query' => $array_query_carichi[$candidato['id_utente']]
                                        );

                                        $array_esa_prt[$candidato['id_utente']][] = $pratica;


                                        $array_collector_assegnati_debitore[$debitore['id_debitore']] = $candidato['id_utente'];
                                    } else {
                                        $go_ahead = true;
                                    }
                                } else {
                                    // PROPONGO IL COLLECTOR
                                    if ($candidato['id_utente'] > 0) {
                                        $affidamenti[] = array(
                                            'pratica' => $pratica,
                                            'candidato' => $candidato['id_utente'],
                                            'tipo' => 'RIAFFIDO',
                                            //'collector_assegnati_debitore' => $array_collector_assegnati_debitore,
                                            //'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                                            //'query' => $array_query_carichi[$candidato['id_utente']]
                                        );
                                        $array_esa_prt[$candidato['id_utente']][] = $pratica;

                                        $array_collector_assegnati_debitore[$debitore['id_debitore']] = $candidato['id_utente'];
                                    } else {
                                        $go_ahead = true;
                                    }
                                }

                                /* I CARICHI PROPOSTI DAL RIAFFIDO NON VENGONO CONTEGGIATI SUL CARICO GLOBALE
                            if($n_affidamenti_collector[$candidato['id_utente']][$row_pratica['codice']] > 0) {
                                $n_affidamenti_collector[$candidato['id_utente']][$row_pratica['codice']]++;
                            }
                            else {
                                $n_affidamenti_collector[$candidato['id_utente']][$row_pratica['codice']] = 1;
                            }
                            */
                                // FINE - PROPONGO IL COLLECTOR
                            } else {
                                $go_ahead = true;
                                //$collector_da_escludere = $candidato['id_utente'];
                            }
                        } else {
                            $go_ahead = true;
                        }
                    }

                    //RECUPERO TUTTI I CANDIDATI ASSEGNATI A PRATICHE COLLEGATE CHE ABBIANO UN ESITO NON POSITIVO
                    $query_pratiche_collegate = "SELECT id
												FROM pratiche
												WHERE id_debitore = '" . db_input($debitore['id_debitore']) . "'
												ORDER BY id DESC";
                    $ris_pratiche_collegate = db_query($query_pratiche_collegate);

                    if (db_num_rows($ris_pratiche_collegate) > 0) {
                        while ($pratica_collegate = mysql_fetch_array($ris_pratiche_collegate)) {
                            $query_cadidati_da_escludere = "SELECT id_collector as id_utente
															FROM pratiche_affidamenti PA
																LEFT JOIN affidamenti A ON PA.id_affidamento = A.id
																LEFT JOIN esiti_pratica EP ON EP.id = PA.id_esito_collector
															WHERE PA.id_pratica = '" . db_input($pratica_collegate['id']) . "'
																AND PA.data_scarico IS NOT NULL 
																AND EP.tipo <> 'POSITIVA'
															ORDER BY PA.id DESC";
                            $ris_cadidati_da_escludere = db_query($query_cadidati_da_escludere);
                            if (db_num_rows($ris_cadidati_da_escludere) > 0) {
                                while ($candidato_escluso = mysql_fetch_array($ris_cadidati_da_escludere)) {
                                    $collectors_da_escludere[] = $candidato_escluso['id_utente'];
                                }
                            }
                        }
                    }

                    if ($go_ahead) {

                        // CONTROLLO SE LA TABELLA DELLO STORICO AFFIDAMENTI ESISTE E CONTIENE UN RECORD CON IL COLLECTOR SELEZIONATO E IL DEBITORE SELEZIONATO
                        // SE TALE RIGA HA ESITO = 2 IL COLLECTOR è DA ESCLUDERE DALLA PROPOSIZIONE, ALTRIMENTI è DA PROPORRE PER DEFAULT
                        $verifica_storico = false;

                        if ($verifica_storico) {
                            $query_esistenza_storico = "SHOW TABLES LIKE 'tab_storico_affidamenti';";
                            $ris_esistenza_storico = db_query($query_esistenza_storico);
                            if (mysql_num_rows($ris_esistenza_storico) > 0) {
                                $query_storico = "SELECT esito 
												FROM tab_storico_affidamenti 
												WHERE id_collector = '" . $col_id . "'
													AND id_debitore = (SELECT id_debitore
																		FROM pratiche
																		WHERE id = '" . db_input($pratica) . "')";
                                $ris_storico = db_query($query_storico);
                                if (mysql_num_rows($ris_storico) > 0) {
                                    $storico = mysql_fetch_assoc($ris_storico);
                                    if ($storico['esito'] == 2) {
                                        if (($key = array_search($col_id, $lista_collector)) !== false) {
                                            unset($lista_collector[$key]);
                                        }
                                    } else {
                                        $collector_storico_positivo = $col_id;
                                    }
                                }
                            }
                        }

                        # RECUPERO I DATI NECESSARI ALLA VERIFICA DEL CRITERIO DI AFFIDAMENTO #5 RIGUARDO IL NUMERO MASSIMO DI AFFIDI PER TIPOLOGIA DI COLLECTOR
                        $limiti = false;
                        $limite_phc = 0;
                        $limite_esattore = 0;
                        $limite_totale = 0;

                        $query_pratica_e_parametri_affidamento_contratto = "SELECT PA.phc, PA.esattore, PA.totale, P.*
																			FROM pratiche P 
																			INNER JOIN contratto_parametro_affidamento PA ON P.id_contratto =  PA.id_contratto
																			WHERE P.id = '" . db_input($pratica) . "'
																			AND (PA.importo_fino > P.affidato_capitale OR PA.importo_fino IS NULL)
																			ORDER BY PA.importo_fino ASC
																			LIMIT 0,1";
                        $ris_dettaglio_pratica = db_query($query_pratica_e_parametri_affidamento_contratto);
                        $dettaglio_pratica = mysql_fetch_array($ris_dettaglio_pratica);

                        if ($dettaglio_pratica['phc'] != '' && $dettaglio_pratica['esattore'] != '' && $dettaglio_pratica['totale'] != '') {
                            $limiti = true;
                            $limite_phc = $dettaglio_pratica['phc'];
                            $limite_esattore = $dettaglio_pratica['esattore'];
                            $limite_totale = $dettaglio_pratica['totale'];
                        } else {
                            // RECUPERO I PARAMETRI DI AFFIDAMENTO DALLA TABELLA IMPOSTAZIONI DI BASE
                            $query_parametri_affidamento_base = "SELECT parametri_affidamento, parametri_affidamento_phc, parametri_affidamento_esattore
																FROM impostazioni_base";
                            $ris_parametri_affidamento_base = db_query($query_parametri_affidamento_base);
                            $parametri_affidamento_base = mysql_fetch_array($ris_parametri_affidamento_base);

                            if ($parametri_affidamento_base['parametri_affidamento_phc'] >= 0 && $parametri_affidamento_base['parametri_affidamento_esattore'] >= 0 && $parametri_affidamento_base['parametri_affidamento'] >= 0) {
                                $limiti = true;
                                $limite_phc = $parametri_affidamento_base['parametri_affidamento_phc'];
                                $limite_esattore = $parametri_affidamento_base['parametri_affidamento_esattore'];
                                //$limite_totale = $parametri_affidamento_base['parametri_affidamento'];
                                $limite_totale = $parametri_affidamento_base['parametri_affidamento_phc'] + $parametri_affidamento_base['parametri_affidamento_esattore'];
                            }
                        }
                        # FINE PREPARAZIONE CRITERIO DI AFFIDAMENTO #5

                        # CREAZIONE DELLA QUERY DI PROPOSIZIONE IN BASE AI CRITERI DI AFFIDAMENTO
                        $query = '';
                        {
                            // 2017-04-20: Aggiunto "AND PC.max_num_prat > 0" e "E.max_num_prat > 0"
                            $query = "SELECT U.*
								FROM (
									SELECT PC.id, PC.id_utente, 1 AS PHC, (CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) AS max_num_prat, COUNT(*) AS numero_pratiche, U.gruppi_base
									FROM phone_collector PC 
										INNER JOIN utente U on PC.id_utente = U.id_utente
										LEFT JOIN pratiche P on PC.id_utente = P.id_collector
									WHERE (U.attivo = 1 OR U.attivo IS NULL) AND PC.max_num_prat > 0
									GROUP BY PC.id_utente, PC.max_num_prat
									HAVING ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) = 0) OR ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) > COUNT(*))
								
									UNION
								
									SELECT E.id, E.id_utente, 0 AS PHC, (CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) AS max_num_prat, COUNT(*) AS numero_pratiche, U.gruppi_base
									FROM esattore E 
										INNER JOIN utente U on E.id_utente = U.id_utente
										LEFT JOIN pratiche P on E.id_utente = P.id_collector
									WHERE (U.attivo = 1 OR U.attivo IS NULL) AND E.max_num_prat > 0
									GROUP BY E.id_utente, E.max_num_prat
									HAVING ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) = 0) OR ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) > COUNT(*))
								) AS U  
								  
								LEFT JOIN zona_geografica_competenza UZ ON U.id_utente = UZ.id_utente  
								LEFT JOIN prodotti_lavorabili UP ON U.id_utente = UP.id_utente 
								LEFT JOIN credito C ON UP.id_prodotto = C.codice 
								LEFT JOIN carico_collector CC ON CC.id_collector = U.id_utente
								LEFT JOIN prodotti_lavorabili PL ON PL.id_prodotto = CC.codice
									 
								WHERE 1=1 " . $append_gruppo_base;
                        }

                        #4 PER I COLLECTOR CHE NON SONO PHC VERIFICARE NELLA SEZIONE [Zona Geografica Di Competenza] DELL'ANAGRAFICA ESATTORE QUELLE CON FLAG [Zona Di Competenza] = 1
                        # TRA QUESTE DEVE ESSERE PRESENTE LA ZONA DEL DEBITORE DELLA PRATICA DA AFFIDARE. L'INDIRIZZO DEL DEBITORE DA VERIFICARE E' QUELLO CON FLAG [Principale] = 1.
                        # SE LA ZONA DEL DEBITORE NON è PRESENTE TRA QUELLE INDICATE NELL'ANAGRAFICA ESATTORE, ALLORA L'ESATTORE VERRA' ESCLUSO DAI RISULTATI.
                        {
                            $query_dettagli_zona_competenza = "SELECT PR.nazione, PP.cod_regione, PR.provincia, PR.cap, PC.cod_istat 
														FROM pratiche P 
															INNER JOIN recapito PR ON P.id_debitore = PR.id_utente 
															INNER JOIN province PP ON PR.provincia = PP.cod_provincia
															INNER JOIN comuni PC ON (PR.citta = PC.comune AND PR.provincia = PC.cod_provincia)
														WHERE P.id = '" . db_input($pratica) . "' AND PR.predefinito = 1";
                            $dettaglio_zona_competenza = mysql_fetch_array(db_query($query_dettagli_zona_competenza));

                            $query .= " AND (U.PHC = 1 
										OR (U.PHC = 0 
												AND UZ.zona_esatt = 1 AND (
													(UZ.tipo_zona = 'Nazione' AND UZ.da = '" . db_input($dettaglio_zona_competenza['nazione']) . "')
													
												OR  (UZ.tipo_zona = 'Regione' AND UZ.da = '" . db_input($dettaglio_zona_competenza['cod_regione']) . "')
												
												OR  (UZ.tipo_zona = 'Provincia' AND UZ.da = '" . db_input($dettaglio_zona_competenza['provincia']) . "')
												
												OR  (UZ.tipo_zona = 'Cap' AND '" . db_input($dettaglio_zona_competenza['cap']) . "' BETWEEN UZ.da AND UZ.a)
												
												OR  (UZ.tipo_zona = 'Citta' AND UZ.da = '" . db_input($dettaglio_zona_competenza['cod_istat']) . "')
												)
											)   
										)";
                        }

                        #5 VERIFICARE SE LA PRATICA RIENTRA NEI LIMITI DI AFFIDAMENTO DEFINITI DA CONTRATTO O DA IMPOSTAZIONI DI BASE
                        # Se PHC verificare il numero massimo di affidameti a PHC
                        # Se ESATTORE verificare il numero massimo di affidamenti a ESATTORE
                        # La somma delle 2 tipologie di affidamento deve comunque rientrare nel limite massimo
                        if ($limiti) {
                            $query .= " AND (
										((U.PHC = 1 AND (SELECT COUNT(*) FROM affidamenti A LEFT JOIN pratiche_affidamenti PA ON PA.id_affidamento = A.id WHERE A.phc = 1 AND id_pratica = '" . db_input($pratica) . "') < '" . $limite_phc . "'))
										OR
										((U.PHC = 0 AND (SELECT COUNT(*) FROM affidamenti A LEFT JOIN pratiche_affidamenti PA ON PA.id_affidamento = A.id WHERE A.phc <> 1 AND id_pratica = '" . db_input($pratica) . "') < '" . $limite_esattore . "'))
									)
									AND ((
										(SELECT COUNT(*) FROM affidamenti A LEFT JOIN pratiche_affidamenti PA ON PA.id_affidamento = A.id WHERE A.phc <> 1 AND id_pratica = '" . db_input($pratica) . "') +
										(SELECT COUNT(*) FROM affidamenti A LEFT JOIN pratiche_affidamenti PA ON PA.id_affidamento = A.id WHERE A.phc = 1 AND id_pratica = '" . db_input($pratica) . "')
									) < '" . $limite_totale . "')";
                        }

                        #1 VERIFICARE NELL'ANAGRAFICA ESATTORE/PHC SE NELLA SEZIONE [PRODOTTI LAVORABILI] E' PRESENTE UNA TIPOLOGIA DI CREDITO DELLA PRATICA
                        # (reperibile dal [Contratto]->[Descrizione]->[Tipologia Credito]). SE LA TIIPOLOGIA DI CREDITO E' DIVERSA DA NULL E DIVERSA DALLA
                        # TIPOLOGIA DI CREDITO DELLA PRATICA ESCLUDERE IL COLLECTOR DAI RISULTATI
                        $query .= " AND ( C.id IS NULL OR C.id IN (SELECT id_tipo_credito FROM contratto INNER JOIN pratiche P1 ON contratto.id = P1.id_contratto WHERE P1.id = '" . db_input($pratica) . "'))";

                        #2 VERIFICARE CHE IL CARICO DEL COLLECTOR PRESO IN ESAME SIA COMPATIBILE CON LA PRATICA
                        # IL COLLECTOR NON DEVE AVER RAGGIUNTO IL NUMERO MASSIMO DI PRATICHE LAVORABILI PER LA TIPOLOGIA DI CREDITO DELLA PRATICA
                        $query .= " AND U.id_utente IN (" . implode(',', $lista_collector) . ")";

                        #3 VERIFICARE NEL [Contratto] DELLA PRATICA SE IL COLLECTOR E' PRESENTE NELLA SEZIONE [Affidamento]->[Agenti Esclusi],
                        # SE L'ESATTORE E' PRESENTE NELLA SEZIONE DEGLI AGENTI ESCLUSI DEVE ESSERE ESCLUSO DAI RISULTATI
                        $query .= " AND U.id_utente NOT IN (SELECT (CASE WHEN value_id IS NULL THEN 0 ELSE value_id END) AS value_id FROM pratiche Pbis LEFT JOIN view_agenti_esclusi Cbis ON Cbis.id = Pbis.id_contratto WHERE Pbis.id = '" . db_input($pratica) . "')";

                        #7 SE L'ESATTORE ERA STATO ASSEGNATO IN PRECEDENZA ALLA PRATICA, MA LA SUA PERFORMANCE HA AVUTO UNO SCARICO NEGATIVO LO ESCLUDO DAI RISULTATI
                        if (count($collectors_da_escludere) > 0)
                            $query .= " AND U.id_utente NOT IN (" . db_input(implode(',', $collectors_da_escludere)) . ")";

                        $query .= ' GROUP BY U.id_utente';

                        $query_affidamento = $query;

                        # PREPARO I CRITERI DI ORDINAMENTO DEI RISULTATI
                        $ordinamento_esattore = " ORDER BY ";
                        $ordinamento_phc = " ORDER BY ";

                        $rendimento_id_contratto = $row_pratica['id_contratto'];
                        $rendimento_id_pratica = $pratica;

                        $id_candidato_miglior_performance = 0;
                        $miglior_performance = 0;
                        $performances = array();

                        //die($query);

                        $ris_candidato = db_query($query);

                        //if(mysql_num_rows($ris_candidato) > 0) {
                        //	print_r($query); die();
                        //}
                        //if(false) {
                        while ($candidato = mysql_fetch_array($ris_candidato)) {
                            // INIZIO CALCOLO RENDIMENTO COLLECTORS PROPOSTI

                            // CONTROLLO IL CAMPO "CALCOLO RENDIMENTO COLLECTOR" e "MINIMO LAVORATO" PRESENTE A LIVELLO DI CONTRATTO
                            $query_dettagli_contratto = "SELECT * FROM contratto WHERE id = '" . $rendimento_id_contratto . "'";

                            $dettaglio_contratto = mysql_fetch_array(db_query($query_dettagli_contratto));

                            //echo 'MINIMO LAVORATO DA CONTRATTO: '.$dettaglio_contratto['minimo_lavorato'].' - ';

                            $minimo_lavorato = '';
                            if ($dettaglio_contratto['minimo_lavorato'] == '') {
                                $query_recupero_minimo_lavorato = 'SELECT minimo_lavorato FROM impostazioni_base WHERE id = 1';
                                $result_recupero_minimo_lavorato = db_query($query_recupero_minimo_lavorato);
                                if (db_num_rows($result_recupero_minimo_lavorato) > 0) {
                                    $minimo_lavorato_field = mysql_fetch_array($result_recupero_minimo_lavorato);
                                    $minimo_lavorato = $minimo_lavorato_field['minimo_lavorato'];
                                }
                            } else {
                                $minimo_lavorato = $dettaglio_contratto['minimo_lavorato'];
                            }

                            # 2.4 - VERIFICA CONDIZIONE CONTRATTO "ESCLUDI PRATICHE REVOCATE"
                            $query_append = '';
                            $escludi_pratiche_revocate = $dettaglio_contratto['escludi_pratiche_revocate'] == 0 ? false : true;
                            if ($escludi_pratiche_revocate) {
                                $query_append .= ' AND (EP.tipo <> "REVOCATA" OR EP.tipo IS NULL)';
                            }

                            $storicizzazione_dati = explode(':', $dettaglio_contratto['storicizzazione_dati']);

                            if ($candidato['PHC'] == 1) {
                                if ($storicizzazione_dati[5] == 1) {
                                    //$query_append_2 = ' AND A.data_scarico >= PR.data_registrazione';
                                    $query_append_2 = ' AND (SELECT COUNT(data_scarico) FROM pratiche_affidamenti WHERE id_affidamento = A.id AND scaricata <> 1) = 0';
                                } else if ($storicizzazione_dati[9] == 1) {
                                    $query_append_2 = ' AND P.data_fine_mandato >= PR.data_registrazione';
                                } else if ($storicizzazione_dati[13] == 1) {
                                    $query_append_2 = ' AND P.data_scarico >= PR.data_registrazione';
                                } else if ($storicizzazione_dati[17] == 1) {
                                    $query_append_2 = ' AND P.data_fine_mandato >= PR.data_registrazione';
                                } else if ($storicizzazione_dati[21] == 1) {
                                    $query_append_2 = ' AND DATE_ADD(A.data_affidamento,INTERVAL ' . db_input($storicizzazione_dati[21]) . ' DAY) >= PR.data_registrazione';
                                } else {
                                    $query_append_2 = '';
                                }
                            } else if ($candidato['PHC'] == 0) {
                                if ($storicizzazione_dati[4] == 1) {
                                    //$query_append_2 = ' AND A.data_scarico >= PR.data_registrazione';
                                    $query_append_2 = ' AND (SELECT COUNT(data_scarico) FROM pratiche_affidamenti WHERE id_affidamento = A.id AND scaricata <> 1) = 0';
                                } else if ($storicizzazione_dati[8] == 1) {
                                    $query_append_2 = ' AND P.data_fine_mandato >= PR.data_registrazione';
                                } else if ($storicizzazione_dati[12] == 1) {
                                    $query_append_2 = ' AND P.data_scarico >= PR.data_registrazione';
                                } else if ($storicizzazione_dati[16] == 1) {
                                    $query_append_2 = ' AND (PR.data_registrazione <= PA.data_fine_mandato_collector OR PA.data_fine_mandato_collector IS NULL)';
                                } else if ($storicizzazione_dati[20] != '') {
                                    $query_append_2 = ' AND DATE_ADD(A.data_affidamento,INTERVAL ' . db_input($storicizzazione_dati[20]) . ' DAY) >= PR.data_registrazione';
                                } else {
                                    $query_append_2 = '';
                                }
                            }

                            # RECUPERO I PARAMETRI DI RENDIMENTO E NE CALCOLO I RISULTATI
                            $calcolo_rendimento = '';
                            if ($dettaglio_contratto['calcolo_rendimento'] == '') {
                                $query_recupero_calcolo_rendimento = 'SELECT calcolo_rendimento FROM impostazioni_base WHERE id = 1';
                                $result_recupero_calcolo_rendimento = db_query($query_recupero_calcolo_rendimento);
                                if (db_num_rows($result_recupero_calcolo_rendimento) > 0) {
                                    $calcolo_rendimento_field = mysql_fetch_array($result_recupero_calcolo_rendimento);
                                    $calcolo_rendimento = $calcolo_rendimento_field['calcolo_rendimento'];
                                }
                            } else {
                                $calcolo_rendimento = $dettaglio_contratto['calcolo_rendimento'];
                            }

                            $calcolo_performance = true;

                            if ($calcolo_performance) {
                                if ($calcolo_rendimento == 'MOVIMENTATE') {
                                    # IL CALCOLO DEL RENDIMENTO DEL CONTRATTO PER IL COLLECTOR VIENE EFFETTUATO SUL NUMERO DI PRATICHE CHE HANNO IL CAMPO MOVIMENTATA > 0

                                    # RECUPERO IL NUMERO TOTALE DI PRATICHE COLLEGATE A QUESTO CONTRATTO
                                    $query_pratiche_totali = "SELECT COUNT(DISTINCT P.id) AS n
                                                            FROM affidamenti A
                                                                LEFT JOIN pratiche_affidamenti PA ON PA.id_affidamento = A.id
                                                                LEFT JOIN pratiche P ON P.id = PA.id_pratica
                                                                LEFT JOIN esiti_pratica EP ON EP.id = PA.id_esito_collector
                                                            WHERE P.id_contratto = '" . $rendimento_id_contratto . "'
                                                                AND P.id <> '" . $rendimento_id_pratica . "'
                                                                AND A.id_collector = '" . $candidato['id_utente'] . "'
                                                                /*AND PA.data_scarico IS NOT NULL*/";
                                    $pratiche_totali = mysql_fetch_array(db_query($query_pratiche_totali . $query_append));

                                    if ($pratiche_totali > 0) {
                                        // RECUPERO IL NUMERO TOTALE DI PRATICHE MOVIMENTATE DAL COLLECTOR CON LO STESSO CONTRATTO
                                        $query_pratiche_positive = "SELECT COUNT(DISTINCT P.id) AS n
                                                                    FROM affidamenti A
                                                                        LEFT JOIN pratiche_affidamenti PA ON PA.id_affidamento = A.id
                                                                        LEFT JOIN pratiche P ON P.id = PA.id_pratica
                                                                        LEFT JOIN esiti_pratica EP ON EP.id = PA.id_esito_collector
                                                                        LEFT JOIN storico_movimentazioni SM ON (SM.id_collector = A.id_collector AND SM.id_pratica = P.id)
                                                                    WHERE P.id_contratto = '" . $rendimento_id_contratto . "'
                                                                        AND P.id <> '" . $rendimento_id_pratica . "'
                                                                        AND A.id_collector = '" . $candidato['id_utente'] . "'
                                                                        /*AND PA.data_scarico IS NOT NULL*/
                                                                        AND SM.stato = 1";
                                        $pratiche_positive = mysql_fetch_array(db_query($query_pratiche_positive . $query_append));

                                        // INSERISCO I VALORI IN UN ARRAY PER DEBUG
                                        $performances[] = array('collector' => 1, 'performance' => 1);
                                        $performances[] = array('collector' => $candidato['id_utente'], 'performance' => $pratiche_positive['n'] * 100 / $pratiche_totali['n']);

                                        // VERIFICO IL RENDIMENTO E NEL CASO SIA MAGGIORE DI QUELLI VERIFICATI IN PRECEDENZA LO CONTRASSEGNO COME MIGLIORE
                                        //echo 'MOVIMENTATE | '.$candidato['id_utente'].' | '.($pratiche_positive['n']*100/$pratiche_totali['n']).' | '.$minimo_lavorato;
                                        if (($pratiche_positive['n'] * 100 / $pratiche_totali['n'] >= $miglior_performance || $miglior_performance == 0) && $pratiche_positive['n'] * 100 / $pratiche_totali['n'] >= $minimo_lavorato) {
                                            $posizione_collector_da_proporre = array_keys($lista_collector, $candidato['id_utente']);
                                            $posizione_collector_migliore = array_keys($lista_collector, $id_candidato_miglior_performance);

                                            if ($posizione_collector_da_proporre[0] <= $posizione_collector_migliore[0] || $id_candidato_miglior_performance == 0) {
                                                $miglior_performance = $pratiche_positive['n'] * 100 / $pratiche_totali['n'];
                                                $id_candidato_miglior_performance = $candidato['id_utente'];
                                            }
                                        }
                                    } else {
                                        // POICHE' NON VI SONO ALTRE PRATICHE GIA' ELABRATE CON LO STESSO CONTRATTO FACCIO IL CALCOLO DEL RENDIMENTO SULLA STESSA TIPOLOGIA DI CREDITO
                                        $query_pratiche_totali = "SELECT COUNT(DISTINCT P.id) AS n
                                                                FROM affidamenti A
                                                                    LEFT JOIN pratiche_affidamenti PA ON PA.id_affidamento = A.id
                                                                    LEFT JOIN pratiche P ON P.id = PA.id_pratica
                                                                    LEFT JOIN contratto C ON C.id = P.id_contratto
                                                                WHERE C.id_tipo_credito = '" . $row_pratica['id_tipo_credito'] . "'
                                                                    AND P.id <> '" . $rendimento_id_pratica . "'
                                                                    AND A.id_collector = '" . $candidato['id_utente'] . "'
                                                                    /*AND PA.data_scarico IS NOT NULL*/";
                                        $pratiche_totali = mysql_fetch_array(db_query($query_pratiche_totali));

                                        $query_pratiche_positive = "SELECT COUNT(DISTINCT P.id) AS n
                                                                    FROM affidamenti A
                                                                        LEFT JOIN pratiche_affidamenti PA ON PA.id_affidamento = A.id
                                                                        LEFT JOIN pratiche P ON P.id = PA.id_pratica
                                                                        LEFT JOIN contratto C ON C.id = P.id_contratto
                                                                        LEFT JOIN storico_movimentazioni SM ON (SM.id_collector = A.id_collector AND SM.id_pratica = P.id)	
                                                                    WHERE C.id_tipo_credito = '" . $row_pratica['id_tipo_credito'] . "'
                                                                        AND P.id <> '" . $rendimento_id_pratica . "'
                                                                        AND A.id_collector = '" . $candidato['id_utente'] . "'
                                                                        /*AND PA.data_scarico IS NOT NULL*/
                                                                        AND SM.stato = 1";
                                        $pratiche_positive = mysql_fetch_array(db_query($query_pratiche_positive));

                                        // VERIFICO IL RENDIMENTO E NEL CASO SIA MAGGIORE DI QUELLI VERIFICATI IN PRECEDENZA LO CONTRASSEGNO COME MIGLIORE
                                        //echo 'MOVIMENTATE | '.$candidato['id_utente'].' | '.($pratiche_positive['n']*100/$pratiche_totali['n']).' | '.$minimo_lavorato;
                                        if ($pratiche_positive['n'] * 100 / $pratiche_totali['n'] >= $miglior_performance || $miglior_performance == 0) {
                                            $posizione_collector_da_proporre = array_keys($lista_collector, $candidato['id_utente']);
                                            $posizione_collector_migliore = array_keys($lista_collector, $id_candidato_miglior_performance);

                                            if ($posizione_collector_da_proporre[0] <= $posizione_collector_migliore[0] || $id_candidato_miglior_performance == 0) {
                                                $miglior_performance = $pratiche_positive['n'] * 100 / $pratiche_totali['n'];
                                                $id_candidato_miglior_performance = $candidato['id_utente'];
                                            }
                                        }
                                    }
                                } else if ($calcolo_rendimento == 'SU_IMPORTO') {
                                    /*
                                # IL CALCOLO DEL RENDIMENTO DEL CONTRATTO VIENE CALCOLATO IN BASE ALLA FORMULA PRESENTE IN CALCOLO DA EFFETTUARE (specificato a livello di contratto)

                                # RECUPERO LA FORMULA DA UTILIZZARE PER IL CALCOLO DEL RENDIMENTO (nel caso non sia specificata sul contratto la ricerco a livello di impostazioni di base)
                                $contratto_formula = 0;
                                if($dettaglio_contratto['calcolo_da_effettuare'] != '') {
                                    $contratto_formula = $dettaglio_contratto['calcolo_da_effettuare'];
                                }
                                else {
                                    $query_recupero_calcolo_rendimento = 'SELECT calcolo_da_effettuare FROM impostazioni_base WHERE id = 1';
                                    $result_recupero_calcolo_rendimento = db_query($query_recupero_calcolo_rendimento);
                                    if(db_num_rows($result_recupero_calcolo_rendimento) > 0) {
                                        $calcolo_da_effettuare = mysql_fetch_array($result_recupero_calcolo_rendimento);
                                        $contratto_formula = $calcolo_da_effettuare['calcolo_da_effettuare'];
                                    }
                                }

                                //echo 'FORMULA CALCOLO: '.$contratto_formula.' - ';

                                # RECUPERO TUTTE LE SOMME DEI VALORI SU TUTTE LE PRATICHE DEL CONTRATTO
                                $array_value = pratiche_getSingoleQuoteContrattoCollector($rendimento_id_contratto, $candidato['id_utente'], $query_append, $query_append_2);

                                if($candidato['id_utente'] == 19370 || $candidato['id_utente'] == 19371) {
                                    //echo $candidato['id_utente'];
                                    //print_r($_SESSION['query1']);
                                    //print_r($_SESSION['query2']);
                                }

                                $array_value_quote_totali = pratiche_getSingoleQuoteContratto($rendimento_id_contratto);

                                // calcolo il dato di % di rendimento del collector
                                $ret = calcolatore($contratto_formula, $array_value);

                                // calcolo la % di lavorazione (basandomi sulla quota di affidato specificata nella formula del contratto) del collector
                                if(strpos($contratto_formula,'AFFCAP')!== FALSE) {
                                    $ret_quota_affidato = calcolatore('AFFCAP', $array_value)*100/calcolatore('AFFCAP', $array_value_quote_totali);
                                }
                                else if(strpos($contratto_formula,'AFFINT')!== FALSE) {
                                    $ret_quota_affidato = calcolatore('AFFINT', $array_value)*100/calcolatore('AFFINT', $array_value_quote_totali);
                                }
                                else if(strpos($contratto_formula,'AFFSPE')!== FALSE) {
                                    $ret_quota_affidato = calcolatore('AFFSPE', $array_value)*100/calcolatore('AFFSPE', $array_value_quote_totali);
                                }
                                else if(strpos($contratto_formula,'AFFAF1')!== FALSE) {
                                    $ret_quota_affidato = calcolatore('AFFAF1', $array_value)*100/calcolatore('AFFAF1', $array_value_quote_totali);
                                }
                                else if(strpos($contratto_formula,'AFFAF2')!== FALSE) {
                                    $ret_quota_affidato = calcolatore('AFFAF2', $array_value)*100/calcolatore('AFFAF2', $array_value_quote_totali);
                                }
                                else if(strpos($contratto_formula,'AFFAF3')!== FALSE) {
                                    $ret_quota_affidato = calcolatore('AFFAF3', $array_value)*100/calcolatore('AFFAF3', $array_value_quote_totali);
                                }
                                else if(strpos($contratto_formula,'AFFCPS')!== FALSE) {
                                    $ret_quota_affidato = calcolatore('AFFCPS', $array_value)*100/calcolatore('AFFCPS', $array_value_quote_totali);
                                }
                                else if(strpos($contratto_formula,'AFFCPI')!== FALSE) {
                                    $ret_quota_affidato = calcolatore('AFFCPI', $array_value)*100/calcolatore('AFFCPI', $array_value_quote_totali);
                                }
                                else if(strpos($contratto_formula,'TOTAFF')!== FALSE) {
                                    $ret_quota_affidato = calcolatore('TOTAFF', $array_value)*100/calcolatore('TOTAFF', $array_value_quote_totali);
                                }

                                //echo 'CONTRATTO: '.$rendimento_id_contratto.' - COLLECTOR: '.$candidato['id_utente'].' - ';
                                //echo 'VALORI PRATICA: '; print_r($array_value); echo ' - ';
                                */

                                    $ret = 0;
                                    $ret_quota_affidato = 0;

                                    if (isset($array_performances[$rendimento_id_contratto][$candidato['id_utente']]) && $array_performances[$rendimento_id_contratto][$candidato['id_utente']] != '') {
                                        $ret = $array_performances[$rendimento_id_contratto][$candidato['id_utente']]['performance'];
                                        $ret_quota_affidato = $array_performances[$rendimento_id_contratto][$candidato['id_utente']]['lavorazione'];
                                    }
                                    /*
                                $query_performance = "SELECT performance FROM collector_performance WHERE id_collector = '".$candidato['id_utente']."' AND id_contratto = '".$rendimento_id_contratto."'";
                                $ris_performance = db_query($query_performance);
                                if(mysql_num_rows($ris_performance)>0) {
                                    $result_performance = mysql_fetch_assoc($ris_performance);

                                    $ret = $result_performance['performance'];
                                }
                                */

                                    # INSERISCO I VALORI IN UN ARRAY PER DEBUG
                                    //$performances[] = array('collector' => 2, 'performance' => 2);
                                    $performances[] = array('collector' => $candidato['id_utente'], 'performance' => $ret, 'minimo_lavorato' => $minimo_lavorato, 'lavorazione' => $ret_quota_affidato);

                                    // VERIFICO IL RENDIMENTO E NEL CASO SIA MAGGIORE DI QUELLI VERIFICATI IN PRECEDENZA LO CONTRASSEGNO COME MIGLIORE
                                    //echo 'SU_IMPORTO | '.$candidato['id_utente'].' | '.($ret).' | '.$minimo_lavorato;

                                    if (($ret >= $miglior_performance || $miglior_performance == 0) && $ret_quota_affidato >= $minimo_lavorato) {
                                        $posizione_collector_da_proporre = array_keys($lista_collector, $candidato['id_utente']);
                                        $posizione_collector_migliore = array_keys($lista_collector, $id_candidato_miglior_performance);

                                        if ((($posizione_collector_da_proporre[0] <= $posizione_collector_migliore[0]) && $ret == $miglior_performance) || $id_candidato_miglior_performance == 0) {
                                            $miglior_performance = $ret;
                                            $id_candidato_miglior_performance = $candidato['id_utente'];
                                        } else if ($ret > $miglior_performance) {
                                            $miglior_performance = $ret;
                                            $id_candidato_miglior_performance = $candidato['id_utente'];
                                        }
                                        # MANUEL vecchia versione
                                        /*
                                    if($posizione_collector_da_proporre[0] <= $posizione_collector_migliore[0] || $id_candidato_miglior_performance == 0) {
                                        $miglior_performance = $ret;
                                        $id_candidato_miglior_performance = $candidato['id_utente'];
                                    }
                                    */
                                    }
                                }
                            } else {
                                $miglior_performance = 0;
                                $id_candidato_miglior_performance = 0;
                            }
                        }

                        //print_r($minimo_lavorato);
                        //print_r($performances); die();

                        // VERIFICO SE IL COLLECTOR è ASSENTE
                        $query_assenza = "(SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM phone_collector WHERE id_utente = '" . $id_candidato_miglior_performance . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01'))))) 
										  UNION 
									  (SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM esattore WHERE id_utente = '" . $id_candidato_miglior_performance . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01'))))) ";


                        $ris_assenza = db_query($query_assenza);

                        if (mysql_num_rows($ris_assenza) > 0) {
                            $sostituto = mysql_fetch_array($ris_assenza);
                            // PROPONGO IL SOSTITUTO DEL COLLECTOR
                            if ($sostituto['id_oper_sost'] > 0) {
                                $affidamenti[] = array(
                                    'pratica' => $pratica,
                                    'candidato' => $sostituto['id_oper_sost'],
                                    'tipo' => 'AFFIDO PER SOSTITUZIONE',
                                    //'collector_assegnati_debitore' => $array_collector_assegnati_debitore,
                                    //'performances' => $performances,
                                    //'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                                    //'query' => $array_query_carichi[$candidato['id_utente']]
                                );
                                $array_esa_prt[$sostituto['id_oper_sost']][] = $pratica;


                                if ($sostituto['id_oper_sost'] > 0)
                                    $array_collector_assegnati_debitore[$debitore['id_debitore']] = $sostituto['id_oper_sost'];
                            } else {
                                // PROPONGO IL COLLECTOR
                                $affidamenti[] = array(
                                    'pratica' => $pratica,
                                    //'candidato' => $candidato['id_utente']
                                    'candidato' => $id_candidato_miglior_performance,
                                    'tipo' => 'PROPOSIZIONE',
                                    //'carichi' => $lista_collector_carichi,
                                    //'collector_assegnati_debitore' => $array_collector_assegnati_debitore,
                                    //'performances' => $performances
                                );

                                $array_esa_prt[$id_candidato_miglior_performance][] = $pratica;


                                if ($id_candidato_miglior_performance)
                                    $array_collector_assegnati_debitore[$debitore['id_debitore']] = $id_candidato_miglior_performance;
                            }
                        } else {
                            // PROPONGO IL COLLECTOR
                            $affidamenti[] = array(
                                'pratica' => $pratica,
                                //'candidato' => $candidato['id_utente']
                                'candidato' => $id_candidato_miglior_performance,
                                'tipo' => 'PROPOSIZIONE',
                                //'carichi' => $lista_collector_carichi,
                                //'collector_assegnati_debitore' => $array_collector_assegnati_debitore,
                                //'performances' => $performances,
                                //'query' => $array_query_carichi[$id_candidato_miglior_performance]
                            );
                            $array_esa_prt[$id_candidato_miglior_performance][] = $pratica;


                            if ($id_candidato_miglior_performance)
                                $array_collector_assegnati_debitore[$debitore['id_debitore']] = $id_candidato_miglior_performance;
                        }

                        if ($n_affidamenti_collector[$id_candidato_miglior_performance][$row_pratica['codice']] > 0) {
                            $n_affidamenti_collector[$id_candidato_miglior_performance][$row_pratica['codice']]++;
                        } else {
                            $n_affidamenti_collector[$id_candidato_miglior_performance][$row_pratica['codice']] = 1;
                        }
                        //}
                        // FINE - PROPONGO IL COLLECTOR
                    }
                }
            }

            //print_r($array_collector_assegnati_debitore);

            // CICLO NUOVAMENTE LE PRATICHE PER VEDERE QUALI POSSONO, DOPO IL PRIMO PASSAGGIO, RICADERE NELLA CONDIZIONE N.7 DI "GESTIONE RIAFFIDO"
            foreach ($pratiche as $pratica) {

                //db_close();

                //CONNESSIONE AL DB (IN CASO DI ERRORE DIE CON MESSAGGIO DAL SERVER)
                $link = db_connect($db_server, $username, $password);
                $sqlconnect = db_select_db($nomeDb, $link);

                //echo 'PRATICA: '.$pratica;

                $query_debitore = "SELECT id_debitore
								FROM pratiche
								WHERE id = '" . db_input($pratica) . "'";
                $ris_debitore = db_query($query_debitore);
                $debitore = mysql_fetch_array($ris_debitore);

                //echo PHP_EOL;
                //echo $debitore['id_debitore'];

                if ($array_collector_assegnati_debitore[$debitore['id_debitore']] > 0) {
                    $found = false;

                    for ($i = 0; $i < count($affidamenti); $i++) {
                        //if($affidamenti[$i]['pratica'] == $pratica && $affidamenti[$i]['candidato']==0) {
                        if ($affidamenti[$i]['pratica'] == $pratica && $affidamenti[$i]['tipo'] != 'DIRETTO') {
                            $affidamenti[$i]['candidato'] = $array_collector_assegnati_debitore[$debitore['id_debitore']];
                            //$affidamenti[$i]['tipo'] = 'RIAFFIDO DA DIR O PROP';
                            $array_esa_prt[$array_collector_assegnati_debitore[$debitore['id_debitore']]][] = $pratica;

                        }
                    }
                }
            }


            $array_prt_aff_esa = [];


            $ret = '  <div class="table-responsive col-md-12">
						<table class="table table-striped table-hover" style="border:1px solid #DDD">
							<thead>
								<tr>
									<th>
										 Collector
									</th>
									<th>
										 N° Pratiche
									</th>
									<th>
										 Cash Balance
									</th>
									<th>
										 Capitale Affidato
									</th>
									<th>
										 Spese Affidate
									</th>
									<th>
										 Interessi Affidati
									</th>
									<th>
										 Affidato 1
									</th>
									<th>
										 Affidato 2
									</th>
									<th>
										 Affidato 3
									</th>
									<th>
										 Totale Affidato
									</th>
									<th>
									</th>
																
								</tr>
							</thead>
							<tbody>';
            krsort($array_esa_prt);

            $ik = 0;
            foreach ($array_esa_prt as $esa => $pratiche) {


                $queryPrtTot = 'SELECT group_concat(P.id) as ids, count(P.id) as tot_pratiche, FORMAT(SUM(P.affidato_capitale+P.affidato_spese+P.affidato_interessi+P.affidato_1+P.affidato_2+P.affidato_3+P.competenze_oneri_recupero+P.competenze_spese_incasso),2,"de_DE") totaleAffidato,FORMAT(SUM(P.cash_balance),2,"de_DE") as cashBalance,FORMAT(SUM(P.affidato_capitale),2,"de_DE") as capitaleAffidato,FORMAT(SUM(P.affidato_interessi),2,"de_DE") as interessiAffidati,FORMAT(SUM(P.affidato_spese),2,"de_DE") as speseAffidate,FORMAT(SUM(P.affidato_1),2,"de_DE") as affidato1,FORMAT(SUM(P.affidato_2),2,"de_DE") as affidato2,FORMAT(SUM(P.affidato_3),2,"de_DE") as affidato3 FROM pratiche P WHERE P.id IN (' . implode(',', $pratiche) . ')';

                $risPrtTot = db_query($queryPrtTot);
                $prtDatTot = db_fetch_array_assoc($risPrtTot)[0];

                if ($esa == 0) {
                    $nomeUtente = "Pratiche non Affidate";
                } else {
                    $query_getUT = "SELECT concat (cognome,' ',nome) as denominazione from utente where id_utente = " . $esa;
                    $nomeUtente = db_fetch_array_assoc(db_query($query_getUT))[0]['denominazione'];

                }


                $array_prt_aff_esa[$ik]['nome_esa'] = $nomeUtente;
                $array_prt_aff_esa[$ik]['prtAff'] = $prtDatTot['tot_pratiche'];

                $ik++;

                if ($_POST['flag_mand_prov'] == 1) {
                    $modal = '<a href="javascript:void(0);" class="dynamic-modal-ajax-request" id="visualizza_prt_prov' . $esa . '" data-modal-level="1" data-modal-page="ajax_visualizza_prt_prov_esa" data-id="' . $prtDatTot['ids'] . '" data-params="' . $nomeUtente . ',' . $esa . '" >' . $nomeUtente . '</a>';
                } else {
                    $modal = '<a href="javascript:void(0);" class="dynamic-modal-ajax-request" id="visualizza_prt_mand' . $esa . '" data-modal-level="1" data-modal-page="ajax_visualizza_prt_mand_esa" data-id="' . $prtDatTot['ids'] . '" data-params="' . $nomeUtente . ',' . $esa . '" >' . $nomeUtente . '</a>';

                }

                if (stripos($nomeUtente, 'non Affidate') !== false) {
                    $eliminaAffEsa = "";
                } else {
                    $eliminaAffEsa = '<button type="button" class="btn-danger btn" name="eliminaAffEsa" onclick="eliminaAffEsa(\'' . $prtDatTot['ids'] . '\',' . $_POST['flag_mand_prov'] . ')"><i class="fa fa-trash-o"></i></button>';
                }

                $ret .= '<tr class="row-esa-' . $esa . '">
                                                <td>' . $modal . '</td>
												<td>' . $prtDatTot['tot_pratiche'] . '</td>
												<td>' . $prtDatTot['cashBalance'] . '</td>
												<td>' . $prtDatTot['capitaleAffidato'] . '</td>
												<td>' . $prtDatTot['speseAffidate'] . '</td>
												<td>' . $prtDatTot['interessiAffidati'] . '</td>
												<td>' . $prtDatTot['affidato1'] . '</td>
												<td>' . $prtDatTot['affidato2'] . '</td>
												<td>' . $prtDatTot['affidato3'] . '</td>
												<td>' . $prtDatTot['totaleAffidato'] . '</td>
												<td>' . $eliminaAffEsa . '</td>
											</tr>';

                //print_r($row);
                //echo '<br>';


            }
            $ret .= '</tbody>
						</table>
					</div>';


            $return['tblHtml'] = $ret;
            $return['arrayAff'] = $array_esa_prt;
            $return['prtAffEsa'] = $array_prt_aff_esa;
            $return['regionePrt'] = $array_regione_prt;
            $return['regionePrtVal'] = $array_regione_prt_val;

            print_r(json_encode($return));
        }
        break;
    case 'proposizione-affidamento-manuale':
        {
            $collectors = explode(',', $_POST['collectors']);
            $pratiche = explode(',', $_POST['pratiche']);


            $query_prt_mappa = "SELECT PROV.cod_provincia as prov,IFNULL(PRRG.tot_pratiche,0) as tot_pratiche,PROV.provincia as den  from province PROV Left JOIN (
SELECT count(P.id) as tot_pratiche, PR.cod_provincia as provCod
FROM pratiche P
         RIGHT JOIN recapito R ON (P.id_debitore = R.id_utente AND R.predefinito = 1)
         RIGHT JOIN province PR ON R.provincia = PR.cod_provincia
WHERE P.id IN (" . implode(',', $pratiche) . ")
GROUP BY PR.cod_provincia) as PRRG ON PRRG.provCod=PROV.cod_provincia";

            $praticheMappa = db_fetch_array_assoc(db_query($query_prt_mappa));
            $array_regione_prt = [];
            foreach ($praticheMappa as $row) {
                $array_regione_prt[$row['prov']] = $row['den'] . ' (' . $row['tot_pratiche'] . ')';
                $array_regione_prt_val[$row['prov']] = $row['tot_pratiche'];
            }


            for ($i = 0; $i < count($collectors); $i++) {
                $array_esa_prt[$collectors[$i]][] = $pratiche[$i];
            }

            $array_prt_aff_esa = [];


            $ret = '  <div class="table-responsive col-md-12">
						<table class="table table-striped table-hover" style="border:1px solid #DDD">
							<thead>
								<tr>
									<th>
										 Collector
									</th>
									<th>
										 N° Pratiche
									</th>
									<th>
										 Cash Balance
									</th>
									<th>
										 Capitale Affidato
									</th>
									<th>
										 Spese Affidate
									</th>
									<th>
										 Interessi Affidati
									</th>
									<th>
										 Affidato 1
									</th>
									<th>
										 Affidato 2
									</th>
									<th>
										 Affidato 3
									</th>
									<th>
										 Totale Affidato
									</th>
									<th> 
									</th>
																
								</tr>
							</thead>
							<tbody>';
            krsort($array_esa_prt);

            $ik = 0;
            foreach ($array_esa_prt as $esa => $pratiche) {


                $queryPrtTot = 'SELECT group_concat(P.id) as ids, count(P.id) as tot_pratiche, FORMAT(SUM(P.affidato_capitale+P.affidato_spese+P.affidato_interessi+P.affidato_1+P.affidato_2+P.affidato_3+P.competenze_oneri_recupero+P.competenze_spese_incasso),2,"de_DE") totaleAffidato,FORMAT(SUM(P.cash_balance),2,"de_DE") as cashBalance,FORMAT(SUM(P.affidato_capitale),2,"de_DE") as capitaleAffidato,FORMAT(SUM(P.affidato_interessi),2,"de_DE") as interessiAffidati,FORMAT(SUM(P.affidato_spese),2,"de_DE") as speseAffidate,FORMAT(SUM(P.affidato_1),2,"de_DE") as affidato1,FORMAT(SUM(P.affidato_2),2,"de_DE") as affidato2,FORMAT(SUM(P.affidato_3),2,"de_DE") as affidato3 FROM pratiche P WHERE P.id IN (' . implode(',', $pratiche) . ')';

                $risPrtTot = db_query($queryPrtTot);
                $prtDatTot = db_fetch_array_assoc($risPrtTot)[0];

                if ($esa == 0) {
                    $nomeUtente = "Pratiche non Affidate";
                } else {
                    $query_getUT = "SELECT concat (cognome,' ',nome) as denominazione from utente where id_utente = " . $esa;
                    $nomeUtente = db_fetch_array_assoc(db_query($query_getUT))[0]['denominazione'];

                }


                $array_prt_aff_esa[$ik]['nome_esa'] = $nomeUtente;
                $array_prt_aff_esa[$ik]['prtAff'] = $prtDatTot['tot_pratiche'];

                $ik++;

                if ($_POST['flag_mand_prov'] == 1) {
                    $modal = '<a href="javascript:void(0);" class="dynamic-modal-ajax-request" id="visualizza_prt_prov' . $esa . '" data-modal-level="1" data-modal-page="ajax_visualizza_prt_prov_esa" data-id="' . $prtDatTot['ids'] . '" data-params="' . $nomeUtente . ',' . $esa . '" >' . $nomeUtente . '</a>';
                } else {
                    $modal = '<a href="javascript:void(0);" class="dynamic-modal-ajax-request" id="visualizza_prt_mand' . $esa . '" data-modal-level="1" data-modal-page="ajax_visualizza_prt_mand_esa" data-id="' . $prtDatTot['ids'] . '" data-params="' . $nomeUtente . ',' . $esa . '" >' . $nomeUtente . '</a>';

                }

                if ($esa == 0) {
                    $eliminaAffEsa = "";
                } else {
                    $eliminaAffEsa = '<button type="button" class="btn-danger btn" name="eliminaAffEsa" onclick="eliminaAffEsa(\'' . $prtDatTot['ids'] . '\',' . $_POST['flag_mand_prov'] . ')"><i class="fa fa-trash-o"></i></button>';
                }


                $ret .= '<tr class="row-esa-' . $esa . '">
                                                <td>' . $modal . '</td>
												<td>' . $prtDatTot['tot_pratiche'] . '</td>
												<td>' . $prtDatTot['cashBalance'] . '</td>
												<td>' . $prtDatTot['capitaleAffidato'] . '</td>
												<td>' . $prtDatTot['speseAffidate'] . '</td>
												<td>' . $prtDatTot['interessiAffidati'] . '</td>
												<td>' . $prtDatTot['affidato1'] . '</td>
												<td>' . $prtDatTot['affidato2'] . '</td>
												<td>' . $prtDatTot['affidato3'] . '</td>
												<td>' . $prtDatTot['totaleAffidato'] . '</td>
												<td>' . $eliminaAffEsa . '</td>
											</tr>';

                //print_r($row);
                //echo '<br>';


            }
            $ret .= '</tbody>
						</table>
					</div>';


            $return['tblHtml'] = $ret;
            $return['arrayAff'] = $array_esa_prt;
            $return['prtAffEsa'] = $array_prt_aff_esa;
            $return['regionePrt'] = $array_regione_prt;
            $return['regionePrtVal'] = $array_regione_prt_val;

            print_r(json_encode($return));
        }
        break;
    case 'calcola-pratiche-escluse':
        {
            $affidamenti = array();

            $append_gruppo_base = '';

            if (isset($_POST['esa']) && $_POST['esa'] == 'ESA') {
                $append_gruppo_base = "AND (gruppi_base = '6'
										OR gruppi_base LIKE '%,6,%'
										OR gruppi_base LIKE '%,6'
										OR gruppi_base LIKE '6,%'
										OR gruppi_base = '7'
										OR gruppi_base LIKE '%,7,%'
										OR gruppi_base LIKE '%,7'
										OR gruppi_base LIKE '7,%')";
            } else if (isset($_POST['esa']) && $_POST['esa'] == 'PHC') {
                $append_gruppo_base = "AND (gruppi_base = '3'
										OR gruppi_base LIKE '%,3,%'
										OR gruppi_base LIKE '%,3'
										OR gruppi_base LIKE '3,%'
										OR gruppi_base = '12'
										OR gruppi_base LIKE '%,12,%'
										OR gruppi_base LIKE '%,12'
										OR gruppi_base LIKE '12,%')";
            } else {
                $append_gruppo_base = "AND (gruppi_base = '6'
										OR gruppi_base LIKE '%,6,%'
										OR gruppi_base LIKE '%,6'
										OR gruppi_base LIKE '6,%'
										OR gruppi_base = '3'
										OR gruppi_base LIKE '%,3,%'
										OR gruppi_base LIKE '%,3'
										OR gruppi_base LIKE '3,%'
										OR gruppi_base = '7'
										OR gruppi_base LIKE '%,7,%'
										OR gruppi_base LIKE '%,7'
										OR gruppi_base LIKE '7,%'
										OR gruppi_base = '12'
										OR gruppi_base LIKE '%,12,%'
										OR gruppi_base LIKE '%,12'
										OR gruppi_base LIKE '12,%')";
            }

            // INIZIALIZZO DEI CONTATORI CHE MI TENGANO TRACCIA DEL NUMERO DI PROPOSIZIONI FATTE PER OGNI COLLECTOR
            $n_affidamenti_collector = array();
            $array_query_carichi = array();
            $array_collector_assegnati_debitore = array();

            $pratiche = explode(',', str_replace(array('[', ']', '"'), '', $_POST['id_pratiche']));

            $array_performances = array();
            if (str_replace(array('[', ']', '"'), '', $_POST['id_pratiche']) != '') {
                $query_performance = "SELECT * FROM collector_performance WHERE id_contratto IN (SELECT id_contratto FROM pratiche WHERE id IN (" . str_replace(array('[', ']', '"'), '', $_POST['id_pratiche']) . "))";
                $ris_performance = db_query($query_performance);
                if (mysql_num_rows($ris_performance) > 0) {
                    while ($result_performance = mysql_fetch_assoc($ris_performance)) {
                        $array_performances[$result_performance['id_contratto']][$result_performance['id_collector']]['performance'] = $result_performance['performance'];
                        $array_performances[$result_performance['id_contratto']][$result_performance['id_collector']]['lavorazione'] = $result_performance['lavorazione'];
                    }
                }
            }

            foreach ($pratiche as $pratica) {
                session_start([
                    'read_and_close' => true,
                ]);

                # RECUPERO LA LISTA DI TUTTI I COLLECTOR CHE POSSONO GESTIRE LE PRATICHE
                $lista_collector = array();
                $lista_collector_max_pratiche = array();
                $lista_collector_carichi = array();

                $ruoloUtente = $_SESSION['user_role'];
                $append_vincoli_capoarea_collector = '';

                if ($ruoloUtente == CAPO_ESATTORE) {
                    $append_vincoli_capoarea_collector = ' AND (U.id_utente = ' . $_SESSION['user_admin_id'] . ' 
														OR U.id_utente IN (SELECT id_collegato FROM collegati_esattore WHERE id_utente = ' . $_SESSION['user_admin_id'] . '))';
                }

                $query_collectors = "SELECT U.id_utente, cognome, nome, codice_fiscale
															FROM utente U 
															LEFT JOIN (
																		(SELECT id_utente, assente, assente_da, assente_a FROM phone_collector) 
																			UNION 
																		(SELECT id_utente, assente, assente_da, assente_a FROM esattore)
																	   ) AS C ON U.id_utente = C.id_utente
															WHERE U.attivo = 1 " . $append_gruppo_base . " 
															AND (((C.assente = 0 OR C.assente IS NULL) AND (C.assente_da = '' OR C.assente_da IS NULL OR C.assente_da = '0000-00-00' OR C.assente_da = '1970-01-01') AND (C.assente_a = '' OR C.assente_a IS NULL OR C.assente_a = '0000-00-00' OR C.assente_a = '1970-01-01')) OR (C.assente_da > '" . date('Y-m-d') . "' OR (C.assente_da < '" . date('Y-m-d') . "' AND C.assente_a < '" . date('Y-m-d') . "') OR ((C.assente_da = '' OR C.assente_da IS NULL OR C.assente_da = '0000-00-00' OR C.assente_da = '1970-01-01') AND C.assente_a < '" . date('Y-m-d') . "')))" . $append_vincoli_capoarea_collector;

                # RECUPERO LA PRIORITA' DI AFFIDO PER ORDINARE LA LISTA DEI COLLECTOR
                $ordinamento_priorita_affido = '';
                $query_lista_collector = '';

                $query_recupero_priorita_affido = 'SELECT priorita_affido FROM impostazioni_base WHERE id = 1';
                $result_recupero_priorita_affido = db_query($query_recupero_priorita_affido);
                $priorita_affido = mysql_fetch_array($result_recupero_priorita_affido);

                if ($priorita_affido['priorita_affido'] != '') {
                    # IN BASE ALLA PRIORITA RECUPERATA CREO LA LISTA ORDINATA DEI COLLECTOR
                    if ($priorita_affido['priorita_affido'] == 'PHC') {
                        $query_lista_collector = "SELECT PC.id_utente, PC.max_num_prat, PC.assente, PC.assente_da, PC.assente_a, 1 AS PHC
												  FROM phone_collector PC 
													  INNER JOIN utente U on PC.id_utente = U.id_utente
													  LEFT JOIN pratiche P on PC.id_utente = P.id_collector
												  WHERE (U.attivo = 1 OR U.attivo IS NULL) " . $append_gruppo_base . "
												  GROUP BY PC.id_utente, PC.max_num_prat
												  HAVING ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) = 0) OR ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) > COUNT(*))
											  
												  UNION
											  
												  SELECT E.id_utente, E.max_num_prat, E.assente, E.assente_da, E.assente_a, 0 AS PHC
												  FROM esattore E 
													  INNER JOIN utente U on E.id_utente = U.id_utente
													  LEFT JOIN pratiche P on E.id_utente = P.id_collector
												  WHERE (U.attivo = 1 OR U.attivo IS NULL) " . $append_gruppo_base . "
												  GROUP BY E.id_utente, E.max_num_prat
												  HAVING ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) = 0) OR ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) > COUNT(*))";
                    } else if ($priorita_affido['priorita_affido'] == 'ESA') {
                        $query_lista_collector = "SELECT E.id_utente, E.max_num_prat, E.assente, E.assente_da, E.assente_a, 0 AS PHC
												  FROM esattore E 
													  INNER JOIN utente U on E.id_utente = U.id_utente
													  LEFT JOIN pratiche P on E.id_utente = P.id_collector
												  WHERE (U.attivo = 1 OR U.attivo IS NULL) " . $append_gruppo_base . "
												  GROUP BY E.id_utente, E.max_num_prat
												  HAVING ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) = 0) OR ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) > COUNT(*))
												  UNION
												  
												  SELECT PC.id_utente, PC.max_num_prat, PC.assente, PC.assente_da, PC.assente_a, 1 AS PHC
												  FROM phone_collector PC 
													  INNER JOIN utente U on PC.id_utente = U.id_utente
													  LEFT JOIN pratiche P on PC.id_utente = P.id_collector
												  WHERE (U.attivo = 1 OR U.attivo IS NULL) " . $append_gruppo_base . "
												  GROUP BY PC.id_utente, PC.max_num_prat
												  HAVING ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) = 0) OR ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) > COUNT(*))";
                    }
                } else {
                    $query_lista_collector = "SELECT PC.id_utente, PC.max_num_prat, PC.assente, PC.assente_da, PC.assente_a, 1 AS PHC
											  FROM phone_collector PC 
												  INNER JOIN utente U on PC.id_utente = U.id_utente
												  LEFT JOIN pratiche P on PC.id_utente = P.id_collector
											  WHERE (U.attivo = 1 OR U.attivo IS NULL) " . $append_gruppo_base . "
											  GROUP BY PC.id_utente, PC.max_num_prat
											  HAVING ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) = 0) OR ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) > COUNT(*))
										  
											  UNION
										  
											  SELECT E.id_utente, E.max_num_prat, E.assente, E.assente_da, E.assente_a, 0 AS PHC
											  FROM esattore E 
												  INNER JOIN utente U on E.id_utente = U.id_utente
												  LEFT JOIN pratiche P on E.id_utente = P.id_collector
											  WHERE (U.attivo = 1 OR U.attivo IS NULL) " . $append_gruppo_base . "
											  GROUP BY E.id_utente, E.max_num_prat
											  HAVING ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) = 0) OR ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) > COUNT(*))";
                }
                /*
			echo 'QUERY LISTA COLLECTOR:';
			echo PHP_EOL;
			echo $query_lista_collector;
			echo PHP_EOL;
			echo PHP_EOL;
			*/

                $ris_lista_collector = db_query($query_lista_collector);

                if (strpos($_POST['collectors_esclusi'], ',') > 0)
                    $collector_esclusi_da_proposizione = explode(',', $_POST['collectors_esclusi']);
                else
                    $collector_esclusi_da_proposizione = $_POST['collectors_esclusi'];

                while ($row_lista_collector = mysql_fetch_array($ris_lista_collector)) {
                    if (!in_array($row_lista_collector['id_utente'], $collector_esclusi_da_proposizione)) {
                        $lista_collector[] = $row_lista_collector['id_utente'];
                        $lista_collector_max_pratiche[$row_lista_collector['id_utente']] = $row_lista_collector['max_num_prat'];
                    }
                }
                # FINE RECUPERO LISTA COLLECTOR POSSIBILI

                // RECUPERO ALCUNI DETTAGLI DELLA PRATICA CHE POI SERVIRANNO NEI CONTROLLI DI AFFIDABILITA'
                $query_pratica = "SELECT C.id_tipo_credito, CR.codice, P.id_contratto
											FROM pratiche P 
												LEFT JOIN contratto C ON P.id_contratto = C.id
												LEFT JOIN credito CR ON CR.id = C.id_tipo_credito
											WHERE P.id = '" . db_input($pratica) . "'";
                $row_pratica = mysql_fetch_array(db_query($query_pratica));

                $collector_storico_positivo = 0;

                // VERIFICO LE CONDIZIONI DI CARICO DI OGNI COLLECTOR
                for ($z = 0; $z < count($lista_collector); $z++) {
                    $col_id = $lista_collector[$z];

                    // CONTROLLO SE LA TABELLA DELLO STORICO AFFIDAMENTI ESISTE E CONTIENE UN RECORD CON IL COLLECTOR SELEZIONATO E IL DEBITORE SELEZIONATO
                    // SE TALE RIGA HA ESITO = 2 IL COLLECTOR è DA ESCLUDERE DALLA PROPOSIZIONE, ALTRIMENTI è DA PROPORRE PER DEFAULT
                    $verifica_storico = true;

                    if ($verifica_storico) {
                        $query_esistenza_storico = "SHOW TABLES LIKE 'tab_storico_affidamenti';";
                        $ris_esistenza_storico = db_query($query_esistenza_storico);
                        if (mysql_num_rows($ris_esistenza_storico) > 0) {
                            $query_storico = "SELECT esito 
											FROM tab_storico_affidamenti 
											WHERE id_collector = '" . $col_id . "'
												AND id_debitore = (SELECT id_debitore
																	FROM pratiche
																	WHERE id = '" . db_input($pratica) . "')";
                            $ris_storico = db_query($query_storico);
                            if (mysql_num_rows($ris_storico) > 0) {
                                $storico = mysql_fetch_assoc($ris_storico);
                                if ($storico['esito'] == 2) {
                                    if (($key = array_search($col_id, $lista_collector)) !== false) {
                                        unset($lista_collector[$key]);
                                    }
                                } else {
                                    $collector_storico_positivo = $col_id;
                                }
                            }
                        }
                    }

                    // CONTROLLO SE IL COLLECTOR PUO' GESTIRE DELLE TIPOLOGIE DI CREDITO
                    $query_carico_tipologie_credito = "SELECT gg_lavorabili AS n FROM prodotti_lavorabili WHERE id_utente = '" . $col_id . "'";
                    $ris_carico_tipologie_credito = db_query($query_carico_tipologie_credito);

                    if (db_num_rows($ris_carico_tipologie_credito) > 0) {
                        // RECUPERO IL NUMERO MASSIMO DI PRATICHE DI QUESTA TIPOLOGIA CHE OGNI COLLECTOR PUO' GESTIRE
                        $query_max_carico = "SELECT gg_lavorabili AS n FROM prodotti_lavorabili WHERE id_utente = '" . $col_id . "' AND id_prodotto = '" . $row_pratica['codice'] . "'";
                        $ris_max_carico = db_query($query_max_carico);

                        if (db_num_rows($ris_max_carico) > 0) {
                            $array_query_carichi[$col_id]['max'] = $query_max_carico;
                            $row_max_carico = mysql_fetch_array($ris_max_carico);
                            $max_carico = $row_max_carico['n'];

                            $carico_massimo = $max_carico;

                            // RECUPERO IL CARICO ATTUALE DEL COLLECTOR PER QUESTA TIPOLOGIA
                            $carico_attuale = 0;
                            $query_carico_attuale = "SELECT carico AS n FROM carico_collector  WHERE id_collector = '" . $col_id . "' AND codice = '" . $row_pratica['codice'] . "'";
                            $ris_carico_attuale = db_query($query_carico_attuale);
                            $array_query_carichi[$col_id]['act'] = $query_carico_attuale;
                            if (db_num_rows($ris_carico_attuale) > 0) {
                                $carico_att = mysql_fetch_array($ris_carico_attuale);
                                $carico_attuale = $carico_att['n'];
                            }

                            $carico_proposto = $n_affidamenti_collector[$col_id][$row_pratica['codice']] > 0 ? $n_affidamenti_collector[$col_id][$row_pratica['codice']] : 0;

                            if ($carico_massimo == ($carico_attuale + $carico_proposto)) {  // || $max_carico == 0
                                if (($key = array_search($col_id, $lista_collector)) !== false) {
                                    unset($lista_collector[$key]);
                                }
                            }

                            $lista_collector_carichi[$col_id]['max'] = $carico_massimo;
                            $lista_collector_carichi[$col_id]['act'] = $carico_attuale;
                            $lista_collector_carichi[$col_id]['pro'] = $carico_proposto;
                        } else {
                            if (($key = array_search($col_id, $lista_collector)) !== false) {
                                unset($lista_collector[$key]);
                            }
                        }
                    } else {
                        // RECUPERO IL NUMERO MASSIMO DI PRATICHE DI QUESTA TIPOLOGIA CHE OGNI COLLECTOR PUO' GESTIRE
                        $array_query_carichi[$col_id]['max'] = $lista_collector_max_pratiche[$col_id];
                        $max_carico = $lista_collector_max_pratiche[$col_id];

                        $carico_massimo = $max_carico;

                        // RECUPERO IL CARICO ATTUALE DEL COLLECTOR PER QUESTA TIPOLOGIA
                        $carico_attuale = 0;
                        $query_carico_attuale = "SELECT SUM(carico) AS n FROM carico_collector WHERE id_collector = '" . $col_id . "'";
                        $ris_carico_attuale = db_query($query_carico_attuale);
                        $array_query_carichi[$col_id]['act'] = $query_carico_attuale;
                        if (db_num_rows($ris_carico_attuale) > 0) {
                            $carico_att = mysql_fetch_array($ris_carico_attuale);
                            $carico_attuale = $carico_att['n'];
                        }

                        $carico_proposto = 0;
                        foreach ($n_affidamenti_collector[$col_id] as $cpg) {
                            $carico_proposto += $cpg;
                        }

                        if ($carico_massimo == ($carico_attuale + $carico_proposto)) {
                            if (($key = array_search($col_id, $lista_collector)) !== false) {
                                unset($lista_collector[$key]);
                            }
                        }

                        $lista_collector_carichi[$col_id]['max'] = $carico_massimo;
                        $lista_collector_carichi[$col_id]['act'] = $carico_attuale;
                        $lista_collector_carichi[$col_id]['pro'] = $carico_proposto;
                    }
                }
                //print_r($lista_collector_carichi);

                #6 (FUNZIONANTE) SE SULLA PRATICA E' PRESENTE UN'ANAGRAFICA AFFIDO, SI FORZA QUEST'ULTIMA SENZA FARE NESSUN CONTROLLO (ZONA, CARICO DI LAVORO, TIPOLOGIA DI CREDITO ...)
                $query_cadidato_affido = "SELECT id_anagrafica_candidato_affido as id_utente, id_debitore
										FROM pratiche 
										WHERE id = '" . db_input($pratica) . "' 
										AND id_anagrafica_candidato_affido > 0";

                $ris_cadidato_affido = db_query($query_cadidato_affido);
                if (mysql_num_rows($ris_cadidato_affido)) {
                    $candidato = mysql_fetch_array($ris_cadidato_affido);

                    // VERIFICO SE IL COLLECTOR Ã¨ ASSENTE
                    $query_assenza = "(SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM phone_collector WHERE id_utente = '" . $candidato['id_utente'] . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01'))))) 
									  UNION 
								  (SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM esattore WHERE id_utente = '" . $candidato['id_utente'] . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01')))))";
                    $ris_assenza = db_query($query_assenza);

                    if (mysql_num_rows($ris_assenza) > 0) {
                        $sostituto = mysql_fetch_array($ris_assenza);
                        // PROPONGO IL SOSTITUTO DEL COLLECTOR
                        $affidamenti[] = array(
                            'pratica' => $pratica,
                            'candidato' => $sostituto['id_oper_sost'],
                            'tipo' => 'DIRETTO',
                            'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                            'query' => $array_query_carichi[$candidato['id_utente']]
                        );

                        if ($sostituto['id_oper_sost'] > 0)
                            $array_collector_assegnati_debitore[$candidato['id_debitore']] = $sostituto['id_oper_sost'];
                    } else {
                        // PROPONGO IL COLLECTOR
                        $affidamenti[] = array(
                            'pratica' => $pratica,
                            'candidato' => $candidato['id_utente'],
                            'tipo' => 'DIRETTO',
                            'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                            'query' => $array_query_carichi[$candidato['id_utente']]

                        );

                        if ($candidato['id_utente'] > 0)
                            $array_collector_assegnati_debitore[$candidato['id_debitore']] = $candidato['id_utente'];
                    }

                    /* I CARICHI PROPOSTI FORZATAMENTE NON VENGONO CONTEGGIATI SUL CARICO GLOBALE
				if($n_affidamenti_collector[$candidato['id_utente']][$row_pratica['codice']] > 0) {
					$n_affidamenti_collector[$candidato['id_utente']][$row_pratica['codice']]++;
				}
				else {
					$n_affidamenti_collector[$candidato['id_utente']][$row_pratica['codice']] = 1;
				}
				*/
                    // FINE - PROPONGO IL COLLECTOR
                } else if ($collector_storico_positivo > 0) {
                    $affidamenti[] = array(
                        'pratica' => $pratica,
                        'candidato' => $collector_storico_positivo,
                        'tipo' => 'DIRETTO DA STORICO',
                        //'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                        //'query' => $array_query_carichi[$candidato['id_utente']]
                    );

                    $query_debitore = "SELECT id_debitore
									FROM pratiche
									WHERE id = '" . db_input($pratica) . "'";
                    $ris_debitore = db_query($query_debitore);
                    $debitore = mysql_fetch_array($ris_debitore);

                    $array_collector_assegnati_debitore[$debitore['id_debitore']] = $collector_storico_positivo;
                } else {
                    $go_ahead = false;
                    $collectors_da_escludere = array();

                    #7 SE LA PRATICA SI RIFERISCE AD UN DEBITORE RECIDIVO E VI E' UNA PRATICA COLLEGATA ATTIVA IL COLLECTOR DA PROPORRE E' LO STESSO DELLA PRATICA ATTIVA
                    #  NEL CASO IN CUI TUTTE LE PRATICHE SIANO STATE CHIUSE I CASI POSSONO ESSERE 2:
                    #  SE LA PRATICA HA ESITO DI SCARICO COLLECTOR POSITIVO PROPONE QUEL COLLECTOR
                    #  SE LA PRATICA HA ESITO DI SCARICO NEGATIVO ALLORA ESCLUDE QUEL COLLECTOR

                    #  STEP 1: RECUPERO IL DEBITORE DELLA PRATICA DA ASSEGNARE
                    $query_debitore = "SELECT id_debitore
									FROM pratiche
									WHERE id = '" . db_input($pratica) . "'";
                    $ris_debitore = db_query($query_debitore);
                    $debitore = mysql_fetch_array($ris_debitore);

                    if ($array_collector_assegnati_debitore[$debitore['id_debitore']] > 0) {
                        // VERIFICO SE IL COLLECTOR Ã¨ ASSENTE
                        $query_assenza = "(SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM phone_collector WHERE id_utente = '" . $array_collector_assegnati_debitore[$debitore['id_debitore']] . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01')))))
										  UNION 
									  (SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM esattore WHERE id_utente = '" . $array_collector_assegnati_debitore[$debitore['id_debitore']] . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01')))))";
                        $ris_assenza = db_query($query_assenza);

                        if (mysql_num_rows($ris_assenza) > 0) {
                            $sostituto = mysql_fetch_array($ris_assenza);
                            // PROPONGO IL SOSTITUTO DEL COLLECTOR
                            $affidamenti[] = array(
                                'pratica' => $pratica,
                                'candidato' => $sostituto['id_oper_sost'],
                                'tipo' => 'RIAFFIDO DA PROPOSIZIONE',
                                'collector_assegnati_debitore' => $array_collector_assegnati_debitore,
                                'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                                'query' => $array_query_carichi[$candidato['id_utente']]
                            );
                        } else {
                            $affidamenti[] = array(
                                'pratica' => $pratica,
                                'candidato' => $array_collector_assegnati_debitore[$debitore['id_debitore']],
                                'tipo' => 'RIAFFIDO DA PROPOSIZIONE',
                                'collector_assegnati_debitore' => $array_collector_assegnati_debitore,
                                'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                                'query' => $array_query_carichi[$candidato['id_utente']]
                            );
                        }
                    } else {
                        #  STEP 2: RECUPERO L'ULTIMA PRATICA ASSOCIATA AL DEBITORE ESCLUDENDO QUELLA IN ESAME
                        #  STEP 3: RECUPERO L'EVENTUALE COLLECTOR DA ASSEGNARE ALLA NUOVA PRATICA
                        $query_cadidato_affido_2 = "SELECT id_collector as id_utente, EP.tipo, PA.data_scarico, PA.id_pratica
													FROM pratiche_affidamenti PA
														LEFT JOIN affidamenti A ON PA.id_affidamento = A.id
														LEFT JOIN esiti_pratica EP ON EP.id = PA.id_esito_collector
													WHERE PA.id_pratica IN (SELECT id 
																				FROM pratiche 
																				WHERE id <> '" . db_input($pratica) . "' 
																				AND id_debitore = '" . db_input($debitore['id_debitore']) . "' 
																				ORDER BY id DESC)
													AND id_collector IS NOT NULL
													ORDER BY PA.id DESC
													LIMIT 0,1";
                        $ris_cadidato_affido_2 = db_query($query_cadidato_affido_2);
                        if (db_num_rows($ris_cadidato_affido_2)) {
                            $candidato = mysql_fetch_array($ris_cadidato_affido_2);

                            if ($candidato['tipo'] == 'POSITIVA' || $candidato['data_scarico'] == '') {
                                // VERIFICO SE IL COLLECTOR Ã¨ ASSENTE
                                $query_assenza = "(SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM phone_collector WHERE id_utente = '" . $candidato['id_utente'] . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01'))))) 
												  UNION 
											  (SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM esattore WHERE id_utente = '" . $candidato['id_utente'] . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01'))))) ";
                                $ris_assenza = db_query($query_assenza);

                                if (mysql_num_rows($ris_assenza) > 0) {
                                    $sostituto = mysql_fetch_array($ris_assenza);
                                    // PROPONGO IL SOSTITUTO DEL COLLECTOR
                                    if ($sostituto['id_oper_sost'] > 0) {
                                        $affidamenti[] = array(
                                            'pratica' => $pratica,
                                            'candidato' => $sostituto['id_oper_sost'],
                                            'tipo' => 'RIAFFIDO',
                                            'collector_assegnati_debitore' => $array_collector_assegnati_debitore,
                                            'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                                            'query' => $array_query_carichi[$candidato['id_utente']]
                                        );

                                        $array_collector_assegnati_debitore[$debitore['id_debitore']] = $sostituto['id_oper_sost'];
                                    } /*
								else if($candidato['id_utente']>0) {
									$affidamenti[] = array(
										'pratica' => $pratica,
										'candidato' => $candidato['id_utente'],
										'tipo' => 'RIAFFIDO',
										'collector_assegnati_debitore' => $array_collector_assegnati_debitore,
										'carichi' => $lista_collector_carichi[$candidato['id_utente']],
										'query' => $array_query_carichi[$candidato['id_utente']]
									);

									$array_collector_assegnati_debitore[$debitore['id_debitore']] = $candidato['id_utente'];
								}
								*/
                                    else {
                                        $go_ahead = true;
                                    }
                                } else {
                                    // PROPONGO IL COLLECTOR
                                    if ($candidato['id_utente'] > 0) {
                                        $affidamenti[] = array(
                                            'pratica' => $pratica,
                                            'candidato' => $candidato['id_utente'],
                                            'tipo' => 'RIAFFIDO',
                                            'collector_assegnati_debitore' => $array_collector_assegnati_debitore,
                                            'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                                            'query' => $array_query_carichi[$candidato['id_utente']]
                                        );

                                        $array_collector_assegnati_debitore[$debitore['id_debitore']] = $candidato['id_utente'];
                                    } else {
                                        $go_ahead = true;
                                    }
                                }

                                /* I CARICHI PROPOSTI DAL RIAFFIDO NON VENGONO CONTEGGIATI SUL CARICO GLOBALE
							if($n_affidamenti_collector[$candidato['id_utente']][$row_pratica['codice']] > 0) {
								$n_affidamenti_collector[$candidato['id_utente']][$row_pratica['codice']]++;
							}
							else {
								$n_affidamenti_collector[$candidato['id_utente']][$row_pratica['codice']] = 1;
							}
							*/
                                // FINE - PROPONGO IL COLLECTOR
                            } else {
                                $go_ahead = true;
                                //$collector_da_escludere = $candidato['id_utente'];
                            }
                        } else {
                            $go_ahead = true;
                        }
                    }

                    //RECUPERO TUTTI I CANDIDATI ASSEGNATI A PRATICHE COLLEGATE CHE ABBIANO UN ESITO NON POSITIVO
                    $query_pratiche_collegate = "SELECT id
												FROM pratiche
												WHERE id_debitore = '" . db_input($debitore['id_debitore']) . "'
												ORDER BY id DESC";
                    $ris_pratiche_collegate = db_query($query_pratiche_collegate);

                    if (db_num_rows($ris_pratiche_collegate) > 0) {
                        while ($pratica_collegate = mysql_fetch_array($ris_pratiche_collegate)) {
                            $query_cadidati_da_escludere = "SELECT id_collector as id_utente
															FROM pratiche_affidamenti PA
																LEFT JOIN affidamenti A ON PA.id_affidamento = A.id
																LEFT JOIN esiti_pratica EP ON EP.id = PA.id_esito_collector
															WHERE PA.id_pratica = '" . db_input($pratica_collegate['id']) . "'
																AND PA.data_scarico IS NOT NULL 
																AND EP.tipo <> 'POSITIVA'
															ORDER BY PA.id DESC";
                            $ris_cadidati_da_escludere = db_query($query_cadidati_da_escludere);
                            if (db_num_rows($ris_cadidati_da_escludere) > 0) {
                                while ($candidato_escluso = mysql_fetch_array($ris_cadidati_da_escludere)) {
                                    $collectors_da_escludere[] = $candidato_escluso['id_utente'];
                                }
                            }
                        }
                    }

                    if ($go_ahead) {
                        # RECUPERO I DATI NECESSARI ALLA VERIFICA DEL CRITERIO DI AFFIDAMENTO #5 RIGUARDO IL NUMERO MASSIMO DI AFFIDI PER TIPOLOGIA DI COLLECTOR
                        $limiti = false;
                        $limite_phc = 0;
                        $limite_esattore = 0;
                        $limite_totale = 0;

                        $query_pratica_e_parametri_affidamento_contratto = "SELECT PA.phc, PA.esattore, PA.totale, P.*
																			FROM pratiche P 
																			LEFT JOIN contratto_parametro_affidamento PA ON P.id_contratto =  PA.id_contratto
																			WHERE P.id = '" . db_input($pratica) . "'
																			AND (PA.importo_fino > P.affidato_capitale OR PA.importo_fino IS NULL)
																			ORDER BY PA.importo_fino ASC
																			LIMIT 0,1";
                        $ris_dettaglio_pratica = db_query($query_pratica_e_parametri_affidamento_contratto);
                        $dettaglio_pratica = mysql_fetch_array($ris_dettaglio_pratica);

                        if ($dettaglio_pratica['phc'] != '' && $dettaglio_pratica['esattore'] != '' && $dettaglio_pratica['totale'] != '') {
                            $limiti = true;
                            $limite_phc = $dettaglio_pratica['phc'];
                            $limite_esattore = $dettaglio_pratica['esattore'];
                            $limite_totale = $dettaglio_pratica['totale'];
                        } else {
                            // RECUPERO I PARAMETRI DI AFFIDAMENTO DALLA TABELLA IMPOSTAZIONI DI BASE
                            $query_parametri_affidamento_base = "SELECT parametri_affidamento, parametri_affidamento_phc, parametri_affidamento_esattore
																FROM impostazioni_base";
                            $ris_parametri_affidamento_base = db_query($query_parametri_affidamento_base);
                            $parametri_affidamento_base = mysql_fetch_array($ris_parametri_affidamento_base);

                            if ($parametri_affidamento_base['parametri_affidamento_phc'] >= 0 && $parametri_affidamento_base['parametri_affidamento_esattore'] >= 0 && $parametri_affidamento_base['parametri_affidamento'] >= 0) {
                                $limiti = true;
                                $limite_phc = $parametri_affidamento_base['parametri_affidamento_phc'];
                                $limite_esattore = $parametri_affidamento_base['parametri_affidamento_esattore'];
                                //$limite_totale = $parametri_affidamento_base['parametri_affidamento'];
                                $limite_totale = $parametri_affidamento_base['parametri_affidamento_phc'] + $parametri_affidamento_base['parametri_affidamento_esattore'];
                            }
                        }
                        # FINE PREPARAZIONE CRITERIO DI AFFIDAMENTO #5

                        # CREAZIONE DELLA QUERY DI PROPOSIZIONE IN BASE AI CRITERI DI AFFIDAMENTO
                        $query = '';
                        {
                            // 2017-04-20: Aggiunto "AND PC.max_num_prat > 0" e "E.max_num_prat > 0"
                            $query = "SELECT U.*
								FROM (
									SELECT PC.id, PC.id_utente, 1 AS PHC, (CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) AS max_num_prat, COUNT(*) AS numero_pratiche, U.gruppi_base
									FROM phone_collector PC 
										INNER JOIN utente U on PC.id_utente = U.id_utente
										LEFT JOIN pratiche P on PC.id_utente = P.id_collector
									WHERE (U.attivo = 1 OR U.attivo IS NULL) AND PC.max_num_prat > 0
									GROUP BY PC.id_utente, PC.max_num_prat
									HAVING ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) = 0) OR ((CASE WHEN PC.max_num_prat IS NULL THEN 0 ELSE PC.max_num_prat END) > COUNT(*))
								
									UNION
								
									SELECT E.id, E.id_utente, 0 AS PHC, (CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) AS max_num_prat, COUNT(*) AS numero_pratiche, U.gruppi_base
									FROM esattore E 
										INNER JOIN utente U on E.id_utente = U.id_utente
										LEFT JOIN pratiche P on E.id_utente = P.id_collector
									WHERE (U.attivo = 1 OR U.attivo IS NULL) AND E.max_num_prat > 0
									GROUP BY E.id_utente, E.max_num_prat
									HAVING ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) = 0) OR ((CASE WHEN E.max_num_prat IS NULL THEN 0 ELSE E.max_num_prat END) > COUNT(*))
								) AS U  
								  
								LEFT JOIN zona_geografica_competenza UZ ON U.id_utente = UZ.id_utente  
								LEFT JOIN prodotti_lavorabili UP ON U.id_utente = UP.id_utente 
								LEFT JOIN credito C ON UP.id_prodotto = C.codice 
								LEFT JOIN carico_collector CC ON CC.id_collector = U.id_utente
								LEFT JOIN prodotti_lavorabili PL ON PL.id_prodotto = CC.codice
									 
								WHERE 1=1 " . $append_gruppo_base;
                        }

                        #4 PER I COLLECTOR CHE NON SONO PHC VERIFICARE NELLA SEZIONE [Zona Geografica Di Competenza] DELL'ANAGRAFICA ESATTORE QUELLE CON FLAG [Zona Di Competenza] = 1
                        # TRA QUESTE DEVE ESSERE PRESENTE LA ZONA DEL DEBITORE DELLA PRATICA DA AFFIDARE. L'INDIRIZZO DEL DEBITORE DA VERIFICARE E' QUELLO CON FLAG [Principale] = 1.
                        # SE LA ZONA DEL DEBITORE NON Ã¨ PRESENTE TRA QUELLE INDICATE NELL'ANAGRAFICA ESATTORE, ALLORA L'ESATTORE VERRA' ESCLUSO DAI RISULTATI.
                        {
                            $query_dettagli_zona_competenza = "SELECT PR.nazione, PP.cod_regione, PR.provincia, PR.cap, PC.cod_istat 
														FROM pratiche P 
															INNER JOIN recapito PR ON P.id_debitore = PR.id_utente 
															INNER JOIN province PP ON PR.provincia = PP.cod_provincia
															INNER JOIN comuni PC ON (PR.citta = PC.comune AND PR.provincia = PC.cod_provincia)
														WHERE P.id = '" . db_input($pratica) . "' AND PR.predefinito = 1";
                            $dettaglio_zona_competenza = mysql_fetch_array(db_query($query_dettagli_zona_competenza));

                            $query .= " AND (U.PHC = 1 
										OR (U.PHC = 0 
												AND UZ.zona_esatt = 1 AND (
													(UZ.tipo_zona = 'Nazione' AND UZ.da = '" . db_input($dettaglio_zona_competenza['nazione']) . "')
													
												OR  (UZ.tipo_zona = 'Regione' AND UZ.da = '" . db_input($dettaglio_zona_competenza['cod_regione']) . "')
												
												OR  (UZ.tipo_zona = 'Provincia' AND UZ.da = '" . db_input($dettaglio_zona_competenza['provincia']) . "')
												
												OR  (UZ.tipo_zona = 'Cap' AND '" . db_input($dettaglio_zona_competenza['cap']) . "' BETWEEN UZ.da AND UZ.a)
												
												OR  (UZ.tipo_zona = 'Citta' AND UZ.da = '" . db_input($dettaglio_zona_competenza['cod_istat']) . "')
												)
											)   
										)";
                        }

                        #5 VERIFICARE SE LA PRATICA RIENTRA NEI LIMITI DI AFFIDAMENTO DEFINITI DA CONTRATTO O DA IMPOSTAZIONI DI BASE
                        # Se PHC verificare il numero massimo di affidameti a PHC
                        # Se ESATTORE verificare il numero massimo di affidamenti a ESATTORE
                        # La somma delle 2 tipologie di affidamento deve comunque rientrare nel limite massimo
                        if ($limiti) {
                            $query .= " AND (
										((U.PHC = 1 AND (SELECT COUNT(*) FROM affidamenti A LEFT JOIN pratiche_affidamenti PA ON PA.id_affidamento = A.id WHERE A.phc = 1 AND id_pratica = '" . db_input($pratica) . "') < '" . $limite_phc . "'))
										OR
										((U.PHC = 0 AND (SELECT COUNT(*) FROM affidamenti A LEFT JOIN pratiche_affidamenti PA ON PA.id_affidamento = A.id WHERE A.phc <> 1 AND id_pratica = '" . db_input($pratica) . "') < '" . $limite_esattore . "'))
									)
									AND ((
										(SELECT COUNT(*) FROM affidamenti A LEFT JOIN pratiche_affidamenti PA ON PA.id_affidamento = A.id WHERE A.phc <> 1 AND id_pratica = '" . db_input($pratica) . "') +
										(SELECT COUNT(*) FROM affidamenti A LEFT JOIN pratiche_affidamenti PA ON PA.id_affidamento = A.id WHERE A.phc = 1 AND id_pratica = '" . db_input($pratica) . "')
									) < '" . $limite_totale . "')";
                        }

                        #1 VERIFICARE NELL'ANAGRAFICA ESATTORE/PHC SE NELLA SEZIONE [PRODOTTI LAVORABILI] E' PRESENTE UNA TIPOLOGIA DI CREDITO DELLA PRATICA
                        # (reperibile dal [Contratto]->[Descrizione]->[Tipologia Credito]). SE LA TIIPOLOGIA DI CREDITO E' DIVERSA DA NULL E DIVERSA DALLA
                        # TIPOLOGIA DI CREDITO DELLA PRATICA ESCLUDERE IL COLLECTOR DAI RISULTATI
                        $query .= " AND ( C.id IS NULL OR C.id IN (SELECT id_tipo_credito FROM contratto INNER JOIN pratiche P1 ON contratto.id = P1.id_contratto WHERE P1.id = '" . db_input($pratica) . "'))";

                        #2 VERIFICARE CHE IL CARICO DEL COLLECTOR PRESO IN ESAME SIA COMPATIBILE CON LA PRATICA
                        # IL COLLECTOR NON DEVE AVER RAGGIUNTO IL NUMERO MASSIMO DI PRATICHE LAVORABILI PER LA TIPOLOGIA DI CREDITO DELLA PRATICA
                        $query .= " AND U.id_utente IN (" . implode(',', $lista_collector) . ")";

                        #3 VERIFICARE NEL [Contratto] DELLA PRATICA SE IL COLLECTOR E' PRESENTE NELLA SEZIONE [Affidamento]->[Agenti Esclusi],
                        # SE L'ESATTORE E' PRESENTE NELLA SEZIONE DEGLI AGENTI ESCLUSI DEVE ESSERE ESCLUSO DAI RISULTATI
                        $query .= " AND U.id_utente NOT IN (SELECT (CASE WHEN value_id IS NULL THEN 0 ELSE value_id END) AS value_id FROM pratiche Pbis LEFT JOIN view_agenti_esclusi Cbis ON Cbis.id = Pbis.id_contratto WHERE Pbis.id = '" . db_input($pratica) . "')";

                        #7 SE L'ESATTORE ERA STATO ASSEGNATO IN PRECEDENZA ALLA PRATICA, MA LA SUA PERFORMANCE HA AVUTO UNO SCARICO NEGATIVO LO ESCLUDO DAI RISULTATI
                        if (count($collectors_da_escludere) > 0)
                            $query .= " AND U.id_utente NOT IN (" . db_input(implode(',', $collectors_da_escludere)) . ")";

                        $query .= ' GROUP BY U.id_utente';

                        $query_affidamento = $query;

                        # PREPARO I CRITERI DI ORDINAMENTO DEI RISULTATI
                        $ordinamento_esattore = " ORDER BY ";
                        $ordinamento_phc = " ORDER BY ";

                        $rendimento_id_contratto = $row_pratica['id_contratto'];
                        $rendimento_id_pratica = $pratica;

                        $id_candidato_miglior_performance = 0;
                        $miglior_performance = 0;
                        $performances = array();

                        die($query);

                        $ris_candidato = db_query($query);

                        //if(mysql_num_rows($ris_candidato) > 0) {
                        //	print_r($query); die();
                        //}

                        while ($candidato = mysql_fetch_array($ris_candidato)) {
                            // INIZIO CALCOLO RENDIMENTO COLLECTORS PROPOSTI

                            // CONTROLLO IL CAMPO "CALCOLO RENDIMENTO COLLECTOR" e "MINIMO LAVORATO" PRESENTE A LIVELLO DI CONTRATTO
                            $query_dettagli_contratto = "SELECT * FROM contratto WHERE id = '" . $rendimento_id_contratto . "'";

                            $dettaglio_contratto = mysql_fetch_array(db_query($query_dettagli_contratto));

                            //echo 'MINIMO LAVORATO DA CONTRATTO: '.$dettaglio_contratto['minimo_lavorato'].' - ';

                            $minimo_lavorato = '';
                            if ($dettaglio_contratto['minimo_lavorato'] == '') {
                                $query_recupero_minimo_lavorato = 'SELECT minimo_lavorato FROM impostazioni_base WHERE id = 1';
                                $result_recupero_minimo_lavorato = db_query($query_recupero_minimo_lavorato);
                                if (db_num_rows($result_recupero_minimo_lavorato) > 0) {
                                    $minimo_lavorato_field = mysql_fetch_array($result_recupero_minimo_lavorato);
                                    $minimo_lavorato = $minimo_lavorato_field['minimo_lavorato'];
                                }
                            } else {
                                $minimo_lavorato = $dettaglio_contratto['minimo_lavorato'];
                            }

                            # 2.4 - VERIFICA CONDIZIONE CONTRATTO "ESCLUDI PRATICHE REVOCATE"
                            $query_append = '';
                            $escludi_pratiche_revocate = $dettaglio_contratto['escludi_pratiche_revocate'] == 0 ? false : true;
                            if ($escludi_pratiche_revocate) {
                                $query_append .= ' AND (EP.tipo <> "REVOCATA" OR EP.tipo IS NULL)';
                            }

                            $storicizzazione_dati = explode(':', $dettaglio_contratto['storicizzazione_dati']);

                            if ($candidato['PHC'] == 1) {
                                if ($storicizzazione_dati[5] == 1) {
                                    //$query_append_2 = ' AND A.data_scarico >= PR.data_registrazione';
                                    $query_append_2 = ' AND (SELECT COUNT(data_scarico) FROM pratiche_affidamenti WHERE id_affidamento = A.id AND scaricata <> 1) = 0';
                                } else if ($storicizzazione_dati[9] == 1) {
                                    $query_append_2 = ' AND P.data_fine_mandato >= PR.data_registrazione';
                                } else if ($storicizzazione_dati[13] == 1) {
                                    $query_append_2 = ' AND P.data_scarico >= PR.data_registrazione';
                                } else if ($storicizzazione_dati[17] == 1) {
                                    $query_append_2 = ' AND P.data_fine_mandato >= PR.data_registrazione';
                                } else if ($storicizzazione_dati[21] == 1) {
                                    $query_append_2 = ' AND DATE_ADD(A.data_affidamento,INTERVAL ' . db_input($storicizzazione_dati[21]) . ' DAY) >= PR.data_registrazione';
                                } else {
                                    $query_append_2 = '';
                                }
                            } else if ($candidato['PHC'] == 0) {
                                if ($storicizzazione_dati[4] == 1) {
                                    //$query_append_2 = ' AND A.data_scarico >= PR.data_registrazione';
                                    $query_append_2 = ' AND (SELECT COUNT(data_scarico) FROM pratiche_affidamenti WHERE id_affidamento = A.id AND scaricata <> 1) = 0';
                                } else if ($storicizzazione_dati[8] == 1) {
                                    $query_append_2 = ' AND P.data_fine_mandato >= PR.data_registrazione';
                                } else if ($storicizzazione_dati[12] == 1) {
                                    $query_append_2 = ' AND P.data_scarico >= PR.data_registrazione';
                                } else if ($storicizzazione_dati[16] == 1) {
                                    $query_append_2 = ' AND (PR.data_registrazione <= PA.data_fine_mandato_collector OR PA.data_fine_mandato_collector IS NULL)';
                                } else if ($storicizzazione_dati[20] != '') {
                                    $query_append_2 = ' AND DATE_ADD(A.data_affidamento,INTERVAL ' . db_input($storicizzazione_dati[20]) . ' DAY) >= PR.data_registrazione';
                                } else {
                                    $query_append_2 = '';
                                }
                            }

                            # RECUPERO I PARAMETRI DI RENDIMENTO E NE CALCOLO I RISULTATI
                            $calcolo_rendimento = '';
                            if ($dettaglio_contratto['calcolo_rendimento'] == '') {
                                $query_recupero_calcolo_rendimento = 'SELECT calcolo_rendimento FROM impostazioni_base WHERE id = 1';
                                $result_recupero_calcolo_rendimento = db_query($query_recupero_calcolo_rendimento);
                                if (db_num_rows($result_recupero_calcolo_rendimento) > 0) {
                                    $calcolo_rendimento_field = mysql_fetch_array($result_recupero_calcolo_rendimento);
                                    $calcolo_rendimento = $calcolo_rendimento_field['calcolo_rendimento'];
                                }
                            } else {
                                $calcolo_rendimento = $dettaglio_contratto['calcolo_rendimento'];
                            }

                            if ($calcolo_rendimento == 'MOVIMENTATE') {
                                # IL CALCOLO DEL RENDIMENTO DEL CONTRATTO PER IL COLLECTOR VIENE EFFETTUATO SUL NUMERO DI PRATICHE CHE HANNO IL CAMPO MOVIMENTATA > 0

                                # RECUPERO IL NUMERO TOTALE DI PRATICHE COLLEGATE A QUESTO CONTRATTO
                                $query_pratiche_totali = "SELECT COUNT(DISTINCT P.id) AS n
														FROM affidamenti A
															LEFT JOIN pratiche_affidamenti PA ON PA.id_affidamento = A.id
															LEFT JOIN pratiche P ON P.id = PA.id_pratica
															LEFT JOIN esiti_pratica EP ON EP.id = PA.id_esito_collector
														WHERE P.id_contratto = '" . $rendimento_id_contratto . "'
															AND P.id <> '" . $rendimento_id_pratica . "'
															AND A.id_collector = '" . $candidato['id_utente'] . "'
															/*AND PA.data_scarico IS NOT NULL*/";
                                $pratiche_totali = mysql_fetch_array(db_query($query_pratiche_totali . $query_append));

                                if ($pratiche_totali > 0) {
                                    // RECUPERO IL NUMERO TOTALE DI PRATICHE MOVIMENTATE DAL COLLECTOR CON LO STESSO CONTRATTO
                                    $query_pratiche_positive = "SELECT COUNT(DISTINCT P.id) AS n
																FROM affidamenti A
																	LEFT JOIN pratiche_affidamenti PA ON PA.id_affidamento = A.id
																	LEFT JOIN pratiche P ON P.id = PA.id_pratica
																	LEFT JOIN esiti_pratica EP ON EP.id = PA.id_esito_collector
																	LEFT JOIN storico_movimentazioni SM ON (SM.id_collector = A.id_collector AND SM.id_pratica = P.id)
																WHERE P.id_contratto = '" . $rendimento_id_contratto . "'
																	AND P.id <> '" . $rendimento_id_pratica . "'
																	AND A.id_collector = '" . $candidato['id_utente'] . "'
																	/*AND PA.data_scarico IS NOT NULL*/
																	AND SM.stato = 1";
                                    $pratiche_positive = mysql_fetch_array(db_query($query_pratiche_positive . $query_append));

                                    // INSERISCO I VALORI IN UN ARRAY PER DEBUG
                                    $performances[] = array('collector' => 1, 'performance' => 1);
                                    $performances[] = array('collector' => $candidato['id_utente'], 'performance' => $pratiche_positive['n'] * 100 / $pratiche_totali['n']);

                                    // VERIFICO IL RENDIMENTO E NEL CASO SIA MAGGIORE DI QUELLI VERIFICATI IN PRECEDENZA LO CONTRASSEGNO COME MIGLIORE
                                    //echo 'MOVIMENTATE | '.$candidato['id_utente'].' | '.($pratiche_positive['n']*100/$pratiche_totali['n']).' | '.$minimo_lavorato;
                                    if (($pratiche_positive['n'] * 100 / $pratiche_totali['n'] >= $miglior_performance || $miglior_performance == 0) && $pratiche_positive['n'] * 100 / $pratiche_totali['n'] >= $minimo_lavorato) {
                                        $posizione_collector_da_proporre = array_keys($lista_collector, $candidato['id_utente']);
                                        $posizione_collector_migliore = array_keys($lista_collector, $id_candidato_miglior_performance);

                                        if ($posizione_collector_da_proporre[0] <= $posizione_collector_migliore[0] || $id_candidato_miglior_performance == 0) {
                                            $miglior_performance = $pratiche_positive['n'] * 100 / $pratiche_totali['n'];
                                            $id_candidato_miglior_performance = $candidato['id_utente'];
                                        }
                                    }
                                } else {
                                    // POICHE' NON VI SONO ALTRE PRATICHE GIA' ELABRATE CON LO STESSO CONTRATTO FACCIO IL CALCOLO DEL RENDIMENTO SULLA STESSA TIPOLOGIA DI CREDITO
                                    $query_pratiche_totali = "SELECT COUNT(DISTINCT P.id) AS n
															FROM affidamenti A
																LEFT JOIN pratiche_affidamenti PA ON PA.id_affidamento = A.id
																LEFT JOIN pratiche P ON P.id = PA.id_pratica
																LEFT JOIN contratto C ON C.id = P.id_contratto
															WHERE C.id_tipo_credito = '" . $row_pratica['id_tipo_credito'] . "'
																AND P.id <> '" . $rendimento_id_pratica . "'
																AND A.id_collector = '" . $candidato['id_utente'] . "'
																/*AND PA.data_scarico IS NOT NULL*/";
                                    $pratiche_totali = mysql_fetch_array(db_query($query_pratiche_totali));

                                    $query_pratiche_positive = "SELECT COUNT(DISTINCT P.id) AS n
																FROM affidamenti A
																	LEFT JOIN pratiche_affidamenti PA ON PA.id_affidamento = A.id
																	LEFT JOIN pratiche P ON P.id = PA.id_pratica
																	LEFT JOIN contratto C ON C.id = P.id_contratto
																	LEFT JOIN storico_movimentazioni SM ON (SM.id_collector = A.id_collector AND SM.id_pratica = P.id)	
																WHERE C.id_tipo_credito = '" . $row_pratica['id_tipo_credito'] . "'
																	AND P.id <> '" . $rendimento_id_pratica . "'
																	AND A.id_collector = '" . $candidato['id_utente'] . "'
																	/*AND PA.data_scarico IS NOT NULL*/
																	AND SM.stato = 1";
                                    $pratiche_positive = mysql_fetch_array(db_query($query_pratiche_positive));

                                    // VERIFICO IL RENDIMENTO E NEL CASO SIA MAGGIORE DI QUELLI VERIFICATI IN PRECEDENZA LO CONTRASSEGNO COME MIGLIORE
                                    //echo 'MOVIMENTATE | '.$candidato['id_utente'].' | '.($pratiche_positive['n']*100/$pratiche_totali['n']).' | '.$minimo_lavorato;
                                    if ($pratiche_positive['n'] * 100 / $pratiche_totali['n'] >= $miglior_performance || $miglior_performance == 0) {
                                        $posizione_collector_da_proporre = array_keys($lista_collector, $candidato['id_utente']);
                                        $posizione_collector_migliore = array_keys($lista_collector, $id_candidato_miglior_performance);

                                        if ($posizione_collector_da_proporre[0] <= $posizione_collector_migliore[0] || $id_candidato_miglior_performance == 0) {
                                            $miglior_performance = $pratiche_positive['n'] * 100 / $pratiche_totali['n'];
                                            $id_candidato_miglior_performance = $candidato['id_utente'];
                                        }
                                    }
                                }
                            } else if ($calcolo_rendimento == 'SU_IMPORTO') {
                                /*
							# IL CALCOLO DEL RENDIMENTO DEL CONTRATTO VIENE CALCOLATO IN BASE ALLA FORMULA PRESENTE IN CALCOLO DA EFFETTUARE (specificato a livello di contratto)

							# RECUPERO LA FORMULA DA UTILIZZARE PER IL CALCOLO DEL RENDIMENTO (nel caso non sia specificata sul contratto la ricerco a livello di impostazioni di base)
							$contratto_formula = 0;
							if($dettaglio_contratto['calcolo_da_effettuare'] != '') {
								$contratto_formula = $dettaglio_contratto['calcolo_da_effettuare'];
							}
							else {
								$query_recupero_calcolo_rendimento = 'SELECT calcolo_da_effettuare FROM impostazioni_base WHERE id = 1';
								$result_recupero_calcolo_rendimento = db_query($query_recupero_calcolo_rendimento);
								if(db_num_rows($result_recupero_calcolo_rendimento) > 0) {
									$calcolo_da_effettuare = mysql_fetch_array($result_recupero_calcolo_rendimento);
									$contratto_formula = $calcolo_da_effettuare['calcolo_da_effettuare'];
								}
							}

							//echo 'FORMULA CALCOLO: '.$contratto_formula.' - ';

							# RECUPERO TUTTE LE SOMME DEI VALORI SU TUTTE LE PRATICHE DEL CONTRATTO
							$array_value = pratiche_getSingoleQuoteContrattoCollector($rendimento_id_contratto, $candidato['id_utente'], $query_append, $query_append_2);

							if($candidato['id_utente'] == 19370 || $candidato['id_utente'] == 19371) {
								//echo $candidato['id_utente'];
								//print_r($_SESSION['query1']);
								//print_r($_SESSION['query2']);
							}

							$array_value_quote_totali = pratiche_getSingoleQuoteContratto($rendimento_id_contratto);

							// calcolo il dato di % di rendimento del collector
							$ret = calcolatore($contratto_formula, $array_value);

							// calcolo la % di lavorazione (basandomi sulla quota di affidato specificata nella formula del contratto) del collector
							if(strpos($contratto_formula,'AFFCAP')!== FALSE) {
								$ret_quota_affidato = calcolatore('AFFCAP', $array_value)*100/calcolatore('AFFCAP', $array_value_quote_totali);
							}
							else if(strpos($contratto_formula,'AFFINT')!== FALSE) {
								$ret_quota_affidato = calcolatore('AFFINT', $array_value)*100/calcolatore('AFFINT', $array_value_quote_totali);
							}
							else if(strpos($contratto_formula,'AFFSPE')!== FALSE) {
								$ret_quota_affidato = calcolatore('AFFSPE', $array_value)*100/calcolatore('AFFSPE', $array_value_quote_totali);
							}
							else if(strpos($contratto_formula,'AFFAF1')!== FALSE) {
								$ret_quota_affidato = calcolatore('AFFAF1', $array_value)*100/calcolatore('AFFAF1', $array_value_quote_totali);
							}
							else if(strpos($contratto_formula,'AFFAF2')!== FALSE) {
								$ret_quota_affidato = calcolatore('AFFAF2', $array_value)*100/calcolatore('AFFAF2', $array_value_quote_totali);
							}
							else if(strpos($contratto_formula,'AFFAF3')!== FALSE) {
								$ret_quota_affidato = calcolatore('AFFAF3', $array_value)*100/calcolatore('AFFAF3', $array_value_quote_totali);
							}
							else if(strpos($contratto_formula,'AFFCPS')!== FALSE) {
								$ret_quota_affidato = calcolatore('AFFCPS', $array_value)*100/calcolatore('AFFCPS', $array_value_quote_totali);
							}
							else if(strpos($contratto_formula,'AFFCPI')!== FALSE) {
								$ret_quota_affidato = calcolatore('AFFCPI', $array_value)*100/calcolatore('AFFCPI', $array_value_quote_totali);
							}
							else if(strpos($contratto_formula,'TOTAFF')!== FALSE) {
								$ret_quota_affidato = calcolatore('TOTAFF', $array_value)*100/calcolatore('TOTAFF', $array_value_quote_totali);
							}

							//echo 'CONTRATTO: '.$rendimento_id_contratto.' - COLLECTOR: '.$candidato['id_utente'].' - ';
							//echo 'VALORI PRATICA: '; print_r($array_value); echo ' - ';
							*/

                                $ret = 0;
                                $ret_quota_affidato = 0;

                                if (isset($array_performances[$rendimento_id_contratto][$candidato['id_utente']]) && $array_performances[$rendimento_id_contratto][$candidato['id_utente']] != '') {
                                    $ret = $array_performances[$rendimento_id_contratto][$candidato['id_utente']]['performance'];
                                    $ret_quota_affidato = $array_performances[$rendimento_id_contratto][$candidato['id_utente']]['lavorazione'];
                                }

                                # INSERISCO I VALORI IN UN ARRAY PER DEBUG
                                //$performances[] = array('collector' => 2, 'performance' => 2);
                                $performances[] = array('collector' => $candidato['id_utente'], 'performance' => $ret, 'minimo_lavorato' => $minimo_lavorato, 'lavorazione' => $ret_quota_affidato);

                                // VERIFICO IL RENDIMENTO E NEL CASO SIA MAGGIORE DI QUELLI VERIFICATI IN PRECEDENZA LO CONTRASSEGNO COME MIGLIORE
                                //echo 'SU_IMPORTO | '.$candidato['id_utente'].' | '.($ret).' | '.$minimo_lavorato;
                                if (($ret >= $miglior_performance || $miglior_performance == 0) && $ret_quota_affidato >= $minimo_lavorato) {
                                    $posizione_collector_da_proporre = array_keys($lista_collector, $candidato['id_utente']);
                                    $posizione_collector_migliore = array_keys($lista_collector, $id_candidato_miglior_performance);

                                    if ((($posizione_collector_da_proporre[0] <= $posizione_collector_migliore[0]) && $ret == $miglior_performance) || $id_candidato_miglior_performance == 0) {
                                        $miglior_performance = $ret;
                                        $id_candidato_miglior_performance = $candidato['id_utente'];
                                    } else if ($ret > $miglior_performance) {
                                        $miglior_performance = $ret;
                                        $id_candidato_miglior_performance = $candidato['id_utente'];
                                    }
                                    # MANUEL vechia versione

                                    /*
								if($posizione_collector_da_proporre[0] <= $posizione_collector_migliore[0] || $id_candidato_miglior_performance == 0) {
									$miglior_performance = $ret;
									$id_candidato_miglior_performance = $candidato['id_utente'];
								}
								*/
                                }
                            }
                        }

                        // VERIFICO SE IL COLLECTOR Ã¨ ASSENTE
                        $query_assenza = "(SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM phone_collector WHERE id_utente = '" . $id_candidato_miglior_performance . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01')))))
										  UNION 
									  (SELECT id_utente, assente, assente_da, assente_a, id_oper_sost FROM esattore WHERE id_utente = '" . $id_candidato_miglior_performance . "' AND (assente = 1 AND ((assente_da <= '" . date('Y-m-d') . "' AND assente_a >= '" . date('Y-m-d') . "') OR ((assente_da = '' OR assente_da IS NULL OR assente_da = '0000-00-00' OR assente_da = '1970-01-01') AND (assente_a = '' OR assente_a IS NULL OR assente_a = '0000-00-00' OR assente_a = '1970-01-01'))))) ";
                        $ris_assenza = db_query($query_assenza);

                        if (mysql_num_rows($ris_assenza) > 0) {

                            $sostituto = mysql_fetch_array($ris_assenza);
                            // PROPONGO IL SOSTITUTO DEL COLLECTOR
                            if ($sostituto['id_oper_sost'] > 0) {
                                $affidamenti[] = array(
                                    'pratica' => $pratica,
                                    'candidato' => $sostituto['id_oper_sost'],
                                    'tipo' => 'AFFIDO PER SOSTITUZIONE',
                                    'collector_assegnati_debitore' => $array_collector_assegnati_debitore,
                                    'performances' => $performances,
                                    'carichi' => $lista_collector_carichi[$candidato['id_utente']],
                                    'query' => $array_query_carichi[$candidato['id_utente']]
                                );

                                if ($sostituto['id_oper_sost'] > 0)
                                    $array_collector_assegnati_debitore[$debitore['id_debitore']] = $sostituto['id_oper_sost'];
                            } else {
                                // PROPONGO IL COLLECTOR
                                $affidamenti[] = array(
                                    'pratica' => $pratica,
                                    //'candidato' => $candidato['id_utente']
                                    'candidato' => $id_candidato_miglior_performance,
                                    'tipo' => 'PROPOSIZIONE',
                                    'carichi' => $lista_collector_carichi,
                                    'collector_assegnati_debitore' => $array_collector_assegnati_debitore,
                                    'performances' => $performances
                                );

                                if ($id_candidato_miglior_performance)
                                    $array_collector_assegnati_debitore[$debitore['id_debitore']] = $id_candidato_miglior_performance;
                            }
                        } else {
                            // PROPONGO IL COLLECTOR
                            $affidamenti[] = array(
                                'pratica' => $pratica,
                                //'candidato' => $candidato['id_utente']
                                'candidato' => $id_candidato_miglior_performance,
                                'tipo' => 'PROPOSIZIONE',
                                'carichi' => $lista_collector_carichi,
                                'collector_assegnati_debitore' => $array_collector_assegnati_debitore,
                                'performances' => $performances,
                                'query' => $array_query_carichi[$id_candidato_miglior_performance]
                            );

                            if ($id_candidato_miglior_performance)
                                $array_collector_assegnati_debitore[$debitore['id_debitore']] = $id_candidato_miglior_performance;
                        }

                        if ($n_affidamenti_collector[$id_candidato_miglior_performance][$row_pratica['codice']] > 0) {
                            $n_affidamenti_collector[$id_candidato_miglior_performance][$row_pratica['codice']]++;
                        } else {
                            $n_affidamenti_collector[$id_candidato_miglior_performance][$row_pratica['codice']] = 1;
                        }
                        // FINE - PROPONGO IL COLLECTOR
                    }
                }
            }

            //print_r($array_collector_assegnati_debitore);

            // CICLO NUOVAMENTE LE PRATICHE PER VEDERE QUALI POSSONO, DOPO IL PRIMO PASSAGGIO, RICADERE NELLA CONDIZIONE N.7 DI "GESTIONE RIAFFIDO"
            foreach ($pratiche as $pratica) {
                //echo 'PRATICA: '.$pratica;

                $query_debitore = "SELECT id_debitore
								FROM pratiche
								WHERE id = '" . db_input($pratica) . "'";
                $ris_debitore = db_query($query_debitore);
                $debitore = mysql_fetch_array($ris_debitore);

                //echo PHP_EOL;
                //echo $debitore['id_debitore'];

                if ($array_collector_assegnati_debitore[$debitore['id_debitore']] > 0) {
                    $found = false;

                    for ($i = 0; $i < count($affidamenti); $i++) {
                        //if($affidamenti[$i]['pratica'] == $pratica && $affidamenti[$i]['candidato']==0) {
                        if ($affidamenti[$i]['pratica'] == $pratica && $affidamenti[$i]['tipo'] != 'DIRETTO') {
                            $affidamenti[$i]['candidato'] = $array_collector_assegnati_debitore[$debitore['id_debitore']];
                            //$affidamenti[$i]['tipo'] = 'RIAFFIDO DA DIR O PROP';
                        }
                    }
                }
            }

            //die();
            //if(count($affidamenti)>0)
            print_r(json_encode($affidamenti));
            //else
            //print_r(json_encode(array('errore','no-result')));
        }
        break;

    //===========================================================================================================================================//
    //================================================================ FILTRI ===================================================================//
    //===========================================================================================================================================//
    case 'filtro-generazione-affidamento':
        {
            $ruoloUtente = $_SESSION['user_role'];

            # CREAZIONE DELLA QUERY BASE DI RECUPERO PRATICHE AFFIDABILI
            # I CRITERI DI AFFIDABILITà SONO:
            # 1 - LA PRATICA NON DEVE ESSERE STATA SCARICATA 							=> scaricata = 0 OR scaricata IS NULL
            # 2 - LA PRATICA NON DEVE ESSERE STATA ARCHIVIATA 							=> archiviata = 0 OR archiviata IS NULL
            # 3 - LA PRATICA DEVE AVERE COME POSIZIONE "AZIENDA"						=> area_corrente = 2
            # 4 - LA PRATICA NON DEVE AVERE UNO ESITO									=> esito_corrente IS NULL
            # 5 - LA PRATICA NON DEVE ESSERE GIà STATA AFFIDATA AD UN COLLECTOR			=> id_lotto_affidamento IS NULL
            # 6 - LA PRATICA DEVE AVERE UNO STATO COMPATIBILE CON L'AFFIDAMENTO			=> consenti_affidamento = 1

            # 7 - SE L'UTENTE CONNESSO è UN CAPOAREA COLLECTOR VERIFICARE TIPOLOGIA DI CREDITO
            # 8 - SE L'UTENTE CONNESSO è UN CAPOAREA COLLECTOR VERIFICARE LA ZONA ESATTIVA DI COMPETENZA

            if ($ruoloUtente == CAPO_ESATTORE) {
                $query = 'SELECT P.*, Ud.nome d_nome, Ud.cognome d_cognome, Ud.codice_fiscale d_codice_fiscale, Um.nome m_nome, Um.cognome m_cognome, Um.codice_fiscale m_codice_fiscale, Ut.nome t_nome, Ut.cognome t_cognome, Ut.codice_fiscale t_codice_fiscale, L.descrizione l_descrizione, L.codice l_codice, S.consenti_affidamento, S.descrizione stato, St.descrizione gruppo_stato, C.priorita priorita, C.id_tipo_credito, R.citta zona, Ti.tipo zona_tipo, PR.provincia, PR.sigla
						FROM pratiche P 
							LEFT JOIN utente 			Ud ON P.id_debitore = Ud.id_utente 
							LEFT JOIN utente 			Um ON P.id_mandante = Um.id_utente 
							LEFT JOIN utente 			Ut ON P.id_tutor = Ut.id_utente 
							LEFT JOIN utente 			Ca ON P.id_anagrafica_candidato_affido = Ca.id_utente 
							LEFT JOIN utente 			Tu ON P.id_tutor = Tu.id_utente 
							LEFT JOIN lotti_mandante 	L  ON P.id_lotto_mandante = L.id 
							LEFT JOIN stati_pratiche 	S  ON P.stato_corrente = S.id 
							LEFT JOIN stato_pratica 	St ON S.classe_stato_pratica = St.id 
							LEFT JOIN contratto 		C  ON P.id_contratto = C.id 
							LEFT JOIN recapito 			R  ON (P.id_debitore = R.id_utente AND R.predefinito = 1) 
							LEFT JOIN province 			PR ON R.provincia = PR.cod_provincia 
							LEFT JOIN regioni 			RG ON RG.cod_regione = PR.cod_regione 
							LEFT JOIN tipo_indirizzo 	Ti ON (R.tipo_recapito = Ti.id_tipo_indirizzo AND R.predefinito = 1) 
						WHERE (scaricata = 0 OR scaricata IS NULL)
							AND (archiviata = 0 OR archiviata IS NULL)
							AND area_corrente = 9
							AND (esito_corrente IS NULL OR esito_corrente <= 0)
							AND (id_collector IS NULL OR id_collector = 0)
							AND consenti_affidamento = 1';
                // RIMOSSA LA CONDIZIONE: AND (id_lotto_affidamento IS NULL OR id_lotto_affidamento = 0)
            } else if ($ruoloUtente == CAPO_PHONE_COLLECTOR) {
                $query = 'SELECT P.*, Ud.nome d_nome, Ud.cognome d_cognome, Ud.codice_fiscale d_codice_fiscale, Um.nome m_nome, Um.cognome m_cognome, Um.codice_fiscale m_codice_fiscale, Ut.nome t_nome, Ut.cognome t_cognome, Ut.codice_fiscale t_codice_fiscale, L.descrizione l_descrizione, L.codice l_codice, S.consenti_affidamento, S.descrizione stato, St.descrizione gruppo_stato, C.priorita priorita, C.id_tipo_credito, R.citta zona, Ti.tipo zona_tipo, PR.provincia, PR.sigla
						FROM pratiche P 
							LEFT JOIN utente 			Ud ON P.id_debitore = Ud.id_utente 
							LEFT JOIN utente 			Um ON P.id_mandante = Um.id_utente 
							LEFT JOIN utente 			Ut ON P.id_tutor = Ut.id_utente 
							LEFT JOIN utente 			Ca ON P.id_anagrafica_candidato_affido = Ca.id_utente 
							LEFT JOIN utente 			Tu ON P.id_tutor = Tu.id_utente 
							LEFT JOIN lotti_mandante 	L  ON P.id_lotto_mandante = L.id 
							LEFT JOIN stati_pratiche 	S  ON P.stato_corrente = S.id 
							LEFT JOIN stato_pratica 	St ON S.classe_stato_pratica = St.id 
							LEFT JOIN contratto 		C  ON P.id_contratto = C.id 
							LEFT JOIN recapito 			R  ON (P.id_debitore = R.id_utente AND R.predefinito = 1) 
							LEFT JOIN province 			PR ON R.provincia = PR.cod_provincia 
							LEFT JOIN regioni 			RG ON RG.cod_regione = PR.cod_regione 
							LEFT JOIN tipo_indirizzo 	Ti ON (R.tipo_recapito = Ti.id_tipo_indirizzo AND R.predefinito = 1) 
						WHERE (scaricata = 0 OR scaricata IS NULL)
							AND (archiviata = 0 OR archiviata IS NULL)
							AND area_corrente = 10
							AND (esito_corrente IS NULL OR esito_corrente <= 0)
							AND (id_collector IS NULL OR id_collector = 0)
							AND consenti_affidamento = 1';
                // RIMOSSA LA CONDIZIONE: AND (id_lotto_affidamento IS NULL OR id_lotto_affidamento = 0)
            } else {
                $query = 'SELECT P.*, Ud.nome d_nome, Ud.cognome d_cognome, Ud.codice_fiscale d_codice_fiscale, Um.nome m_nome, Um.cognome m_cognome, Um.codice_fiscale m_codice_fiscale, Ut.nome t_nome, Ut.cognome t_cognome, Ut.codice_fiscale t_codice_fiscale, L.descrizione l_descrizione, L.codice l_codice, S.consenti_affidamento, S.descrizione stato, St.descrizione gruppo_stato, C.priorita priorita, C.id_tipo_credito, R.citta zona, Ti.tipo zona_tipo, PR.provincia, PR.sigla
						FROM pratiche P 
							LEFT JOIN utente 			Ud ON P.id_debitore = Ud.id_utente 
							LEFT JOIN utente 			Um ON P.id_mandante = Um.id_utente 
							LEFT JOIN utente 			Ut ON P.id_tutor = Ut.id_utente 
							LEFT JOIN utente 			Ca ON P.id_anagrafica_candidato_affido = Ca.id_utente 
							LEFT JOIN utente 			Tu ON P.id_tutor = Tu.id_utente 
							LEFT JOIN lotti_mandante 	L  ON P.id_lotto_mandante = L.id 
							LEFT JOIN stati_pratiche 	S  ON P.stato_corrente = S.id 
							LEFT JOIN stato_pratica 	St ON S.classe_stato_pratica = St.id 
							LEFT JOIN contratto 		C  ON P.id_contratto = C.id 
							LEFT JOIN recapito 			R  ON (P.id_debitore = R.id_utente AND R.predefinito = 1) 
							LEFT JOIN province 			PR ON R.provincia = PR.cod_provincia 
							LEFT JOIN regioni 			RG ON RG.cod_regione = PR.cod_regione 
							LEFT JOIN tipo_indirizzo 	Ti ON (R.tipo_recapito = Ti.id_tipo_indirizzo AND R.predefinito = 1) 
						WHERE (scaricata = 0 OR scaricata IS NULL)
							AND (archiviata = 0 OR archiviata IS NULL)
							AND area_corrente = 2
							AND (esito_corrente IS NULL OR esito_corrente <= 0)
							AND (id_collector IS NULL OR id_collector = 0)
							AND consenti_affidamento = 1';
                // RIMOSSA LA CONDIZIONE: AND (id_lotto_affidamento IS NULL OR id_lotto_affidamento = 0)
            }

            /* PER TEST */
            //query +=        ' AND (consenti_affidamento = 1 OR consenti_affidamento IS NULL) '; // DA RIMUOVERE LA CONDIZIONE "IS NULL"
            //query +=        ' AND (area_corrente = 2 OR area_corrente IS NULL) '; 		// DA RIMUOVERE LA CONDIZIONE "IS NULL"


            $n_filtro = $_POST['n'] + 1;

            if ($_POST['q'] != '')
                $query .= ' AND ' . $_POST['q'];

            if ($_POST['p_coll'] > 0)
                $query .= ' AND P.id_debitore IN ( SELECT id_collegato FROM collegati_pratica WHERE id_pratica <> P.id ) ';
            if ($_POST['gest_pass'] > 0)
                $query .= ' AND P.id_debitore IN ( SELECT id_debitore FROM pratiche_dati_affidamenti_collector pdac join pratiche on pdac.id=pratiche.id WHERE numero_affidi>0 AND pdac.id <> P.id ) ';

            // CREAZIONE DELLA QUERY DI COPERTURA ESATTORIALE (Non è stato possibile creare una vista a causa delle sottoquery nel FORM)
            $query_copertura_esattoriale = '';

            $append_copertura_esattoriale_capoarea_collector = '';
            $append_prodotti_lavorabili_capoarea_collector = '';

            if ($ruoloUtente == CAPO_ESATTORE) {
                $append_copertura_esattoriale_capoarea_collector = ' AND (U.id_utente = ' . $_SESSION['user_admin_id'] . ' OR U.id_utente IN (SELECT id_collegato FROM collegati_esattore WHERE id_utente = ' . $_SESSION['user_admin_id'] . '))'; // AGGIUNTA NELLA $query_copertura_esattoriale

                $query_count_prodotti_lavorabili = 'SELECT id_prodotto
													FROM prodotti_lavorabili 
													WHERE id_utente = ' . $_SESSION['user_admin_id'] . ' 
													OR id_utente IN (SELECT id_collegato FROM collegati_esattore WHERE id_utente = ' . $_SESSION['user_admin_id'] . ')';
                if (db_num_rows(db_query($query_count_prodotti_lavorabili)) > 0) {
                    $append_prodotti_lavorabili_capoarea_collector = ' AND id_tipo_credito IN (SELECT CR.id FROM prodotti_lavorabili PL LEFT JOIN credito CR ON CR.codice = PL.id_prodotto WHERE id_utente = ' . $_SESSION['user_admin_id'] . ' OR id_utente IN (SELECT id_collegato FROM collegati_esattore WHERE id_utente = ' . $_SESSION['user_admin_id'] . '))';
                }
            }

            {
                $query_copertura_esattoriale .= "SELECT P0.id 
												FROM pratiche P0 
												INNER JOIN recapito PR0 ON P0.id_debitore = PR0.id_utente 
												LEFT JOIN province PP0 ON PR0.provincia = PP0.cod_provincia
												LEFT JOIN comuni PC0 ON (PR0.citta = PC0.comune AND PR0.provincia = PC0.cod_provincia)
												WHERE PR0.predefinito = 1
												AND (
													( SELECT COUNT(U.id_utente)
														FROM (
															SELECT *
															FROM utente U
															WHERE U.attivo = 1 OR U.attivo IS NULL
														) AS U  

														LEFT JOIN zona_geografica_competenza UZ1 ON U.id_utente = UZ1.id_utente

														WHERE

														UZ1.zona_esatt = 1 AND UZ1.tipo_zona = 'Nazione' AND UZ1.da = PR0.nazione
														" . $append_copertura_esattoriale_capoarea_collector . "
													) > 0
													
													OR
													
													( SELECT COUNT(U.id_utente)
														FROM (
															SELECT *
															FROM utente U
															WHERE U.attivo = 1 OR U.attivo IS NULL
														) AS U  

														LEFT JOIN zona_geografica_competenza UZ1 ON U.id_utente = UZ1.id_utente  

														WHERE

														UZ1.zona_esatt = 1 AND UZ1.tipo_zona = 'Regione' AND UZ1.da = PP0.cod_regione
														" . $append_copertura_esattoriale_capoarea_collector . "
													) > 0
													
													OR
													
													( SELECT COUNT(U.id_utente)
														FROM (
															SELECT *
															FROM utente U
															WHERE U.attivo = 1 OR U.attivo IS NULL
														) AS U  

														LEFT JOIN zona_geografica_competenza UZ1 ON U.id_utente = UZ1.id_utente  

														WHERE

														UZ1.zona_esatt = 1 AND UZ1.tipo_zona = 'Provincia' AND UZ1.da = PR0.provincia
														" . $append_copertura_esattoriale_capoarea_collector . "
													) > 0
													
													OR
													
													( SELECT COUNT(U.id_utente)
														FROM (
															SELECT *
															FROM utente U
															WHERE U.attivo = 1 OR U.attivo IS NULL
														) AS U  

														LEFT JOIN zona_geografica_competenza UZ1 ON U.id_utente = UZ1.id_utente  

														WHERE

														UZ1.zona_esatt = 1 AND UZ1.tipo_zona = 'Cap' AND UZ1.da >= PR0.cap AND UZ1.a <= PR0.cap
														" . $append_copertura_esattoriale_capoarea_collector . "
													) > 0
													
													OR
													
													( SELECT COUNT(U.id_utente)
														FROM (
															SELECT *
															FROM utente U
															WHERE U.attivo = 1 OR U.attivo IS NULL
														) AS U  

														LEFT JOIN zona_geografica_competenza UZ1 ON U.id_utente = UZ1.id_utente  

														WHERE

														UZ1.zona_esatt = 1 AND UZ1.tipo_zona = 'Citta' AND UZ1.da = PC0.cod_istat
														" . $append_copertura_esattoriale_capoarea_collector . "
													) > 0
													) ";
            }

            if ($ruoloUtente == CAPO_ESATTORE) {
                ////$query .= ' AND P.id NOT IN ('.$query_copertura_esattoriale.')';
                $query .= ' AND P.id IN (' . $query_copertura_esattoriale . ')';
                $query .= $append_prodotti_lavorabili_capoarea_collector;
            }
            //die($query_copertura_esattoriale);
            $query_criteri_ordinamento = "SELECT criteri_di_affidamento FROM impostazioni_base WHERE id = 1";
            $campo = 'criteri_di_affidamento';

            if ($_POST['esa'] != 'TUTTI' && $_POST['esa'] != '') {
                if ($_POST['esa'] == 'PHC') {
                    //$query .= ' AND P.id NOT IN ('.$query_copertura_esattoriale.')';
                    $query .= ' AND P.id NOT IN (' . $query_copertura_esattoriale . ')';
                    $query .= $append_tipo_capoarea_collector;
                    $query .= $append_prodotti_lavorabili_capoarea_collector;
                    $query_criteri_ordinamento = "SELECT criteri_di_affidamento_phc FROM impostazioni_base WHERE id = 1";
                    $campo = 'criteri_di_affidamento_phc';
                } else if ($_POST['esa'] == 'ESA') {
                    $query .= ' AND P.id IN (' . $query_copertura_esattoriale . ')';
                    $query_criteri_ordinamento = "SELECT criteri_di_affidamento_esattore FROM impostazioni_base WHERE id = 1";
                    $campo = 'criteri_di_affidamento_esattore';
                }
            }

            $criteri_ordinamento = mysql_fetch_array(db_query($query_criteri_ordinamento));
            $criteri_ordinamento_array = explode(',', $criteri_ordinamento[$campo]);


            $i = 1;
            $ordinamento = '';
            foreach ($criteri_ordinamento_array as $criterio) {
                if ($criterio == 4) {
                    $ordinamento .= ' P.affidato_capitale DESC,';
                } else if ($criterio == 3) {
                    $ordinamento .= ' C.priorita DESC,';
                } else if ($criterio == 5) {
                    $ordinamento .= ' P.data_fine_mandato ASC,';
                }
            }
            if (isset($_POST['ord'])) {
                $ordinamento = $_POST['ord'];

                $query .= ' ORDER BY ' . $ordinamento;
            } else {
                if ($ordinamento != '')
                    $query .= ' ORDER BY' . trim($ordinamento, ',');
            }
            //die($query);

            $ris = db_query($query);

            # CONTROLLO DA ABILITARE NEL CASO IN CUI I RISULTATI SIANO TROPPI E VOGLIAMO OBBLIGARE L'UTENTE A MIRARE MEGLIO LA RICERCA
            if (db_num_rows($ris) > 5000) {
                $errore = '<div class="note note-danger">
					       <h4 class="block" style="color:#ED4E2A"><strong style="color:#ED4E2A">ATTENZIONE!</strong> La richiesta effettuata è troppo generica.</h4>
						   <p style="color:#ED4E2A; margin-bottom: 10px">
								 Si prega di modificare la richiesta in modo da restituire risultati più precisi.
						   </p>
					   </div>';
                die($errore);
            }

            # GESTIONE DELL'ERRORE NELLA QUERY (proponiamo un avviso all'utente riportante l'errore SQL)
            if (db_error()) {
                $db_error = db_error();
                $array_errore = explode(' at line ', $db_error);

                $errore = '<div class="note note-danger">
					       <h4 class="block" style="color:#ED4E2A"><strong style="color:#ED4E2A">ATTENZIONE!</strong> Hai generato una richiesta con sintassi non corretta.</h4>
						   <p style="color:#ED4E2A; margin-bottom: 10px">
								 L\'avviso rilevato segnala un errore nei pressi di ' . str_replace('You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near', '', $array_errore[0]) . '
						   </p>
					   </div>';
                die($errore);
            }

            # CREO L'OUTPUT HTML CHE ANDRA' APPESO ALLA PAGINA
            {
                echo '  <div class="table-responsive col-md-12">
						<table class="table table-striped table-hover" style="border:1px solid #DDD">
							<thead>
								<tr>
									<th>
										<input value="999" onChange="select_all_switches($(this))" type="checkbox" class="make-switchMODAL" data-size="normal" data-on-color="success" data-off-color="warning" data-on-text="&nbsp;<i class=\'fa fa-check-circle-o\'></i>&nbsp;" data-off-text="&nbsp;<i class=\'fa fa-circle-o\'></i>&nbsp;">
									</th>
									<th>
										 Id Pratica
									</th>
									<th>
										 Debitore
									</th>
									<th>
										 Mandante
									</th>
									<th>
										 Lotto
									</th>
									<th>
										 Stato
									</th>
									<th>
										 Fine Mandato
									</th>
									<th>
										 Priorità
									</th>
									<th>
										 Zona
									</th>
									<th>
										 Provincia
									</th>
									<th>
										 Scaduto
									</th>
									<th>
										 Collector
									</th>
									<th>
										 Tutor
									</th>
								</tr>
							</thead>
							<tbody>';
                if (mysql_num_rows($ris) > 0) {
                    if ($ruoloUtente == CAPO_ESATTORE) {
                        $query_collectors = 'SELECT U.id_utente, cognome, nome, codice_fiscale
																FROM utente U LEFT JOIN (
																  (SELECT id_utente, assente, assente_da, assente_a FROM phone_collector)
																UNION
																  (SELECT id_utente, assente, assente_da, assente_a FROM esattore)
																) AS C ON U.id_utente = C.id_utente
																WHERE U.attivo = 1 
																	AND (gruppi_base = "6"
																		OR gruppi_base LIKE "%,6,%"
																		OR gruppi_base LIKE "%,6"
																		OR gruppi_base LIKE "6,%"
																		OR gruppi_base = "3"
																		OR gruppi_base LIKE "%,3,%"
																		OR gruppi_base LIKE "%,3"
																		OR gruppi_base LIKE "3,%"
																		OR gruppi_base = "7"
																		OR gruppi_base LIKE "%,7,%"
																		OR gruppi_base LIKE "%,7"
																		OR gruppi_base LIKE "7,%"
																		OR gruppi_base = "12"
																		OR gruppi_base LIKE "%,12,%"
																		OR gruppi_base LIKE "%,12"
																		OR gruppi_base LIKE "12,%")
																	AND (((C.assente = 0 OR C.assente IS NULL) AND (C.assente_da = "" OR C.assente_da IS NULL OR C.assente_da = "0000-00-00" OR C.assente_da = "1970-01-01") AND (C.assente_a = "" OR C.assente_a IS NULL OR C.assente_a = "0000-00-00" OR C.assente_a = "1970-01-01")) OR (C.assente_da > "' . date('Y-m-d') . '" OR (C.assente_da < "' . date('Y-m-d') . '" AND C.assente_a < "' . date('Y-m-d') . '") OR ((C.assente_da = "" OR C.assente_da IS NULL OR C.assente_da = "0000-00-00" OR C.assente_da = "1970-01-01") AND C.assente_a < "' . date('Y-m-d') . '")))
																	AND (U.id_utente = ' . $_SESSION['user_admin_id'] . ' OR U.id_utente IN (SELECT id_collegato FROM collegati_esattore WHERE id_utente = ' . $_SESSION['user_admin_id'] . '))';
                    } else if ($ruoloUtente == CAPO_PHONE_COLLECTOR) {
                        $query_collectors = 'SELECT U.id_utente, cognome, nome, codice_fiscale
																FROM utente U LEFT JOIN (
																  (SELECT id_utente, assente, assente_da, assente_a FROM phone_collector)
																UNION
																  (SELECT id_utente, assente, assente_da, assente_a FROM esattore)
																) AS C ON U.id_utente = C.id_utente
																WHERE U.attivo = 1 
																	AND (gruppi_base = "6"
																		OR gruppi_base LIKE "%,6,%"
																		OR gruppi_base LIKE "%,6"
																		OR gruppi_base LIKE "6,%"
																		OR gruppi_base = "3"
																		OR gruppi_base LIKE "%,3,%"
																		OR gruppi_base LIKE "%,3"
																		OR gruppi_base LIKE "3,%"
																		OR gruppi_base = "7"
																		OR gruppi_base LIKE "%,7,%"
																		OR gruppi_base LIKE "%,7"
																		OR gruppi_base LIKE "7,%"
																		OR gruppi_base = "12"
																		OR gruppi_base LIKE "%,12,%"
																		OR gruppi_base LIKE "%,12"
																		OR gruppi_base LIKE "12,%")
																	AND (((C.assente = 0 OR C.assente IS NULL) AND (C.assente_da = "" OR C.assente_da IS NULL OR C.assente_da = "0000-00-00" OR C.assente_da = "1970-01-01") AND (C.assente_a = "" OR C.assente_a IS NULL OR C.assente_a = "0000-00-00" OR C.assente_a = "1970-01-01")) OR (C.assente_da > "' . date('Y-m-d') . '" OR (C.assente_da < "' . date('Y-m-d') . '" AND C.assente_a < "' . date('Y-m-d') . '") OR ((C.assente_da = "" OR C.assente_da IS NULL OR C.assente_da = "0000-00-00" OR C.assente_da = "1970-01-01") AND C.assente_a < "' . date('Y-m-d') . '")))
																	AND (U.id_utente = ' . $_SESSION['user_admin_id'] . ' OR U.id_utente IN (SELECT id_collegato FROM collegati_phc WHERE id_utente = ' . $_SESSION['user_admin_id'] . '))';
                    } else {
                        $query_collectors = 'SELECT U.id_utente, cognome, nome, codice_fiscale
																FROM utente U LEFT JOIN (
																  (SELECT id_utente, assente, assente_da, assente_a FROM phone_collector)
																UNION
																  (SELECT id_utente, assente, assente_da, assente_a FROM esattore)
																) AS C ON U.id_utente = C.id_utente
																WHERE U.attivo = 1 
																	AND (gruppi_base = "6"
																		OR gruppi_base LIKE "%,6,%"
																		OR gruppi_base LIKE "%,6"
																		OR gruppi_base LIKE "6,%"
																		OR gruppi_base = "3"
																		OR gruppi_base LIKE "%,3,%"
																		OR gruppi_base LIKE "%,3"
																		OR gruppi_base LIKE "3,%"
																		OR gruppi_base = "7"
																		OR gruppi_base LIKE "%,7,%"
																		OR gruppi_base LIKE "%,7"
																		OR gruppi_base LIKE "7,%"
																		OR gruppi_base = "12"
																		OR gruppi_base LIKE "%,12,%"
																		OR gruppi_base LIKE "%,12"
																		OR gruppi_base LIKE "12,%") 
																	AND (((C.assente = 0 OR C.assente IS NULL) AND (C.assente_da = "" OR C.assente_da IS NULL OR C.assente_da = "0000-00-00" OR C.assente_da = "1970-01-01") AND (C.assente_a = "" OR C.assente_a IS NULL OR C.assente_a = "0000-00-00" OR C.assente_a = "1970-01-01")) OR (C.assente_da > "' . date('Y-m-d') . '" OR (C.assente_da < "' . date('Y-m-d') . '" AND C.assente_a < "' . date('Y-m-d') . '") OR ((C.assente_da = "" OR C.assente_da IS NULL OR C.assente_da = "0000-00-00" OR C.assente_da = "1970-01-01") AND C.assente_a < "' . date('Y-m-d') . '")))';
                    }

                    $ris_collectors = db_query($query_collectors);
                    $ris_collectors2 = db_query($query_collectors);

                    $collectors = '<optgroup label="Collectors">';
                    while ($row_collectors = mysql_fetch_array($ris_collectors)) {
                        $collectors .= '<option value="' . $row_collectors['id_utente'] . '">' . $row_collectors['cognome'] . ' ' . $row_collectors['nome'] . ' (' . $row_collectors['codice_fiscale'] . ')</option>';
                    }
                    $collectors .= '</optgroup>';

                    while ($row = mysql_fetch_array($ris)) {
                        $data_fine_mandato = '';
                        if ($row['data_fine_mandato'] != '')
                            $data_fine_mandato = date('d-m-Y', strtotime($row['data_fine_mandato']));

                        $lotto = '';
                        if ($row['l_codice'] != '')
                            $lotto = $row['l_descrizione'] . ' (' . $row['l_codice'] . ')';

                        $query_affidato = "SELECT SUM(affidato_capitale+affidato_spese+affidato_interessi+affidato_1+affidato_2+affidato_3+competenze_oneri_recupero+competenze_spese_incasso) importo FROM pratiche WHERE id = '" . $row['id'] . "'";
                        $affidato = mysql_fetch_array(db_query($query_affidato));

                        $query_recuperato = "SELECT SUM(recuperato_capitale+recuperato_spese+recuperato_interessi+recuperato_affidato_1+recuperato_affidato_2+recuperato_affidato_3+recuperato_oneri_recupero+recuperato_spese_incasso+recuperato_surplus) importo FROM pratiche WHERE id = '" . $row['id'] . "'";
                        $recuperato = mysql_fetch_array(db_query($query_recuperato));

                        $scaduto = $affidato['importo'] - $recuperato['importo'];

                        $tutor_nome = ($row['t_cognome'] != '' || $row['t_nome'] != '') ? $row['t_cognome'] . ' ' . $row['t_nome'] : '';
                        $tutor_cf = $row['t_codice_fiscale'] != '' ? '(' . $row['t_codice_fiscale'] . ')' : '';

                        echo '<tr class="pratica-' . $row['id'] . ' row-affidamento">
												<td><input data-pratica="' . $row['id'] . '" type="checkbox" onChange="select_switch($(this),' . $row['id'] . ')" class="make-switchMODAL selectedItem checkbox_' . $row['id'] . '" data-size="small" name="selected[]" value="' . $row['id'] . '" data-on-color="success" data-off-color="warning" data-on-text="&nbsp;<i class=\'fa fa-check-circle-o\'></i>&nbsp;" data-off-text="&nbsp;<i class=\'fa fa-circle-o\'></i>&nbsp;"><input type="hidden" value="' . $row['id'] . '" class="pratica_lista"></td>
												<td><input type="hidden" class="affidamento-pratica id-pratica-' . $row['id'] . '" value="' . $row['id'] . '">' . $row['id'] . ' <i class=\'fa fa-star check-direct-' . $row['id'] . ' hidden\'></i></td>
												<td>' . $row['d_cognome'] . ' ' . $row['d_nome'] . '<br>(' . $row['d_codice_fiscale'] . ')</td>
												<td>' . $row['m_cognome'] . ' ' . $row['m_nome'] . '<br>(' . $row['m_codice_fiscale'] . ')</td>
												<td>' . $lotto . '</td>
												<td>' . $row['gruppo_stato'] . '<br>' . $row['stato'] . '</td>
												<td>' . $data_fine_mandato . '</td>
												<td>' . $row['priorita'] . '</td>
												<td>' . $row['zona'] . '<br>( ' . $row['zona_tipo'] . ' )' . '</td>
												<td>' . $row['provincia'] . ' (' . $row['sigla'] . ')' . '</td>
												<td>€ ' . number_format($scaduto, 2, ',', '.') . '</td>
												<td><select id="select_collector_pratica_' . $row['id'] . '" class="form-control select2me affidamento-collector select-affidamento-collector input-medium" data-pratica="' . $row['id'] . '" onChange="select_row($(this),' . $row['id'] . '); cambia_duplicati($(this))"><option></option>' . $collectors . '</select><div id="tipo_proposizione_' . $row['id'] . '"></div></td>
												<td>' . $tutor_nome . '<br>(' . $tutor_cf . ')</td>
											</tr>';

                        //print_r($row);
                        //echo '<br>';
                    }
                } else {
                    echo '<tr>
											<td colspan="12" style="text-align:center"><strong>Nessun risultato presente con i parametri di ricerca impostati</strong></td>
										</tr>';
                }
                echo '			</tbody>
						</table>
					</div>';
            }
        }
        break;
    case 'filtro-generazione-affidamento-home':
        {
            $ruoloUtente = $_SESSION['user_role'];

            $array_prt = [];
            $flag_mand_prov = 0;

            # CREAZIONE DELLA QUERY BASE DI RECUPERO PRATICHE AFFIDABILI
            # I CRITERI DI AFFIDABILITà SONO:
            # 1 - LA PRATICA NON DEVE ESSERE STATA SCARICATA 							=> scaricata = 0 OR scaricata IS NULL
            # 2 - LA PRATICA NON DEVE ESSERE STATA ARCHIVIATA 							=> archiviata = 0 OR archiviata IS NULL
            # 3 - LA PRATICA DEVE AVERE COME POSIZIONE "AZIENDA"						=> area_corrente = 2
            # 4 - LA PRATICA NON DEVE AVERE UNO ESITO									=> esito_corrente IS NULL
            # 5 - LA PRATICA NON DEVE ESSERE GIà STATA AFFIDATA AD UN COLLECTOR			=> id_lotto_affidamento IS NULL
            # 6 - LA PRATICA DEVE AVERE UNO STATO COMPATIBILE CON L'AFFIDAMENTO			=> consenti_affidamento = 1

            # 7 - SE L'UTENTE CONNESSO è UN CAPOAREA COLLECTOR VERIFICARE TIPOLOGIA DI CREDITO
            # 8 - SE L'UTENTE CONNESSO è UN CAPOAREA COLLECTOR VERIFICARE LA ZONA ESATTIVA DI COMPETENZA

            $tipoFiltro = $_POST['tipoFiltro'];
            if ($tipoFiltro == "mandante") {
                $query = 'SELECT Um.nome m_nome, Um.cognome m_cognome,P.id_mandante ';
                $queryTot = 'SELECT "Totale" as m_nome,"" as m_cognome, "" as id_mandante';
            } else {
                $query = 'SELECT RG.regione,PR.provincia,PR.id ';
                $queryTot = 'SELECT "Totale" as regione,"" as provincia, "" as id';


            }

            $query .= ',group_concat(P.id) as ids, count(P.id) as tot_pratiche, FORMAT(SUM(P.affidato_capitale+P.affidato_spese+P.affidato_interessi+P.affidato_1+P.affidato_2+P.affidato_3+P.competenze_oneri_recupero+P.competenze_spese_incasso),2,"de_DE") totaleAffidato,FORMAT(SUM(P.cash_balance),2,"de_DE") as cashBalance,FORMAT(SUM(P.affidato_capitale),2,"de_DE") as capitaleAffidato,FORMAT(SUM(P.affidato_interessi),2,"de_DE") as interessiAffidati,FORMAT(SUM(P.affidato_spese),2,"de_DE") as speseAffidate,FORMAT(SUM(P.affidato_1),2,"de_DE") as affidato1,FORMAT(SUM(P.affidato_2),2,"de_DE") as affidato2,FORMAT(SUM(P.affidato_3),2,"de_DE") as affidato3 ';
            $queryTot .= ',group_concat(P.id) as ids, count(P.id) as tot_pratiche, FORMAT(SUM(P.affidato_capitale+P.affidato_spese+P.affidato_interessi+P.affidato_1+P.affidato_2+P.affidato_3+P.competenze_oneri_recupero+P.competenze_spese_incasso),2,"de_DE") totaleAffidato,FORMAT(SUM(P.cash_balance),2,"de_DE") as cashBalance,FORMAT(SUM(P.affidato_capitale),2,"de_DE") as capitaleAffidato,FORMAT(SUM(P.affidato_interessi),2,"de_DE") as interessiAffidati,FORMAT(SUM(P.affidato_spese),2,"de_DE") as speseAffidate,FORMAT(SUM(P.affidato_1),2,"de_DE") as affidato1,FORMAT(SUM(P.affidato_2),2,"de_DE") as affidato2,FORMAT(SUM(P.affidato_3),2,"de_DE") as affidato3 ';


            if ($ruoloUtente == CAPO_ESATTORE) {

                $query .= 'FROM pratiche P 
							LEFT JOIN utente 			Ud ON P . id_debitore = Ud . id_utente 
							LEFT JOIN utente 			Um ON P . id_mandante = Um . id_utente 
							LEFT JOIN utente 			Ut ON P . id_tutor = Ut . id_utente 
							LEFT JOIN utente 			Ca ON P . id_anagrafica_candidato_affido = Ca . id_utente 
							LEFT JOIN utente 			Tu ON P . id_tutor = Tu . id_utente 
							LEFT JOIN lotti_mandante 	L  ON P . id_lotto_mandante = L . id 
							LEFT JOIN stati_pratiche 	S  ON P . stato_corrente = S . id 
							LEFT JOIN stato_pratica 	St ON S . classe_stato_pratica = St . id 
							LEFT JOIN contratto 		C  ON P . id_contratto = C . id 
							LEFT JOIN recapito 			R  ON(P . id_debitore = R . id_utente AND R . predefinito = 1) 
							LEFT JOIN province 			PR ON R . provincia = PR . cod_provincia 
							LEFT JOIN regioni 			RG ON RG . cod_regione = PR . cod_regione 
							LEFT JOIN tipo_indirizzo 	Ti ON(R . tipo_recapito = Ti . id_tipo_indirizzo AND R . predefinito = 1) 
							LEFT JOIN ( SELECT count(id) as contaPrtDeb,id_debitore from pratiche WHERE (scaricata = 0 OR scaricata IS NULL)
							AND (archiviata = 0 OR archiviata IS NULL)
							AND area_corrente = 2
                AND (esito_corrente IS NULL OR esito_corrente <= 0)
							AND (id_collector IS NULL OR id_collector = 0)
							 group by id_debitore) DBPRT ON DBPRT.id_debitore=P . id_debitore
						WHERE(scaricata = 0 OR scaricata IS NULL)
							AND (archiviata = 0 OR archiviata IS NULL)
							AND area_corrente = 9
                AND (esito_corrente IS NULL OR esito_corrente <= 0)
							AND (id_collector IS NULL OR id_collector = 0)
							AND consenti_affidamento = 1';
                // RIMOSSA LA CONDIZIONE: AND (id_lotto_affidamento IS NULL OR id_lotto_affidamento = 0)
                $queryTot .= 'FROM pratiche P 
							LEFT JOIN utente 			Ud ON P . id_debitore = Ud . id_utente 
							LEFT JOIN utente 			Um ON P . id_mandante = Um . id_utente 
							LEFT JOIN utente 			Ut ON P . id_tutor = Ut . id_utente 
							LEFT JOIN utente 			Ca ON P . id_anagrafica_candidato_affido = Ca . id_utente 
							LEFT JOIN utente 			Tu ON P . id_tutor = Tu . id_utente 
							LEFT JOIN lotti_mandante 	L  ON P . id_lotto_mandante = L . id 
							LEFT JOIN stati_pratiche 	S  ON P . stato_corrente = S . id 
							LEFT JOIN stato_pratica 	St ON S . classe_stato_pratica = St . id 
							LEFT JOIN contratto 		C  ON P . id_contratto = C . id 
							LEFT JOIN recapito 			R  ON(P . id_debitore = R . id_utente AND R . predefinito = 1) 
							LEFT JOIN province 			PR ON R . provincia = PR . cod_provincia 
							LEFT JOIN regioni 			RG ON RG . cod_regione = PR . cod_regione 
							LEFT JOIN tipo_indirizzo 	Ti ON(R . tipo_recapito = Ti . id_tipo_indirizzo AND R . predefinito = 1) 
							LEFT JOIN ( SELECT count(id) as contaPrtDeb,id_debitore from pratiche  WHERE (scaricata = 0 OR scaricata IS NULL)
							AND (archiviata = 0 OR archiviata IS NULL)
							AND area_corrente = 2
                AND (esito_corrente IS NULL OR esito_corrente <= 0)
							AND (id_collector IS NULL OR id_collector = 0)
							 group by id_debitore) DBPRT ON DBPRT.id_debitore=P . id_debitore
						WHERE(scaricata = 0 OR scaricata IS NULL)
							AND (archiviata = 0 OR archiviata IS NULL)
							AND area_corrente = 9
                AND (esito_corrente IS NULL OR esito_corrente <= 0)
							AND (id_collector IS NULL OR id_collector = 0)
							AND consenti_affidamento = 1';
                // RIMOSSA LA CONDIZIONE: AND (id_lotto_affidamento IS NULL OR id_lotto_affidamento = 0)


            } else if ($ruoloUtente == CAPO_PHONE_COLLECTOR) {
                $query .= 'FROM pratiche P 
							LEFT JOIN utente 			Ud ON P . id_debitore = Ud . id_utente 
							LEFT JOIN utente 			Um ON P . id_mandante = Um . id_utente 
							LEFT JOIN utente 			Ut ON P . id_tutor = Ut . id_utente 
							LEFT JOIN utente 			Ca ON P . id_anagrafica_candidato_affido = Ca . id_utente 
							LEFT JOIN utente 			Tu ON P . id_tutor = Tu . id_utente 
							LEFT JOIN lotti_mandante 	L  ON P . id_lotto_mandante = L . id 
							LEFT JOIN stati_pratiche 	S  ON P . stato_corrente = S . id 
							LEFT JOIN stato_pratica 	St ON S . classe_stato_pratica = St . id 
							LEFT JOIN contratto 		C  ON P . id_contratto = C . id 
							LEFT JOIN recapito 			R  ON(P . id_debitore = R . id_utente AND R . predefinito = 1) 
							LEFT JOIN province 			PR ON R . provincia = PR . cod_provincia 
							LEFT JOIN regioni 			RG ON RG . cod_regione = PR . cod_regione 
							LEFT JOIN tipo_indirizzo 	Ti ON(R . tipo_recapito = Ti . id_tipo_indirizzo AND R . predefinito = 1)							
							LEFT JOIN ( SELECT count(id) as contaPrtDeb,id_debitore from pratiche  WHERE (scaricata = 0 OR scaricata IS NULL)
							AND (archiviata = 0 OR archiviata IS NULL)
							AND area_corrente = 2
                AND (esito_corrente IS NULL OR esito_corrente <= 0)
							AND (id_collector IS NULL OR id_collector = 0)
							 group by id_debitore) DBPRT ON DBPRT.id_debitore=P . id_debitore
						WHERE(scaricata = 0 OR scaricata IS NULL)
							AND (archiviata = 0 OR archiviata IS NULL)
							AND area_corrente = 10
                AND (esito_corrente IS NULL OR esito_corrente <= 0)
							AND (id_collector IS NULL OR id_collector = 0)
							AND consenti_affidamento = 1';

                $queryTot .= 'FROM pratiche P 
							LEFT JOIN utente 			Ud ON P . id_debitore = Ud . id_utente 
							LEFT JOIN utente 			Um ON P . id_mandante = Um . id_utente 
							LEFT JOIN utente 			Ut ON P . id_tutor = Ut . id_utente 
							LEFT JOIN utente 			Ca ON P . id_anagrafica_candidato_affido = Ca . id_utente 
							LEFT JOIN utente 			Tu ON P . id_tutor = Tu . id_utente 
							LEFT JOIN lotti_mandante 	L  ON P . id_lotto_mandante = L . id 
							LEFT JOIN stati_pratiche 	S  ON P . stato_corrente = S . id 
							LEFT JOIN stato_pratica 	St ON S . classe_stato_pratica = St . id 
							LEFT JOIN contratto 		C  ON P . id_contratto = C . id 
							LEFT JOIN recapito 			R  ON(P . id_debitore = R . id_utente AND R . predefinito = 1) 
							LEFT JOIN province 			PR ON R . provincia = PR . cod_provincia 
							LEFT JOIN regioni 			RG ON RG . cod_regione = PR . cod_regione 
							LEFT JOIN tipo_indirizzo 	Ti ON(R . tipo_recapito = Ti . id_tipo_indirizzo AND R . predefinito = 1) 
							LEFT JOIN ( SELECT count(id) as contaPrtDeb,id_debitore from pratiche  WHERE (scaricata = 0 OR scaricata IS NULL)
							AND (archiviata = 0 OR archiviata IS NULL)
							AND area_corrente = 2
                AND (esito_corrente IS NULL OR esito_corrente <= 0)
							AND (id_collector IS NULL OR id_collector = 0)
							 group by id_debitore) DBPRT ON DBPRT.id_debitore=P . id_debitore
                          WHERE(scaricata = 0 OR scaricata IS NULL)
							AND (archiviata = 0 OR archiviata IS NULL)
							AND area_corrente = 10
                AND (esito_corrente IS NULL OR esito_corrente <= 0)
							AND (id_collector IS NULL OR id_collector = 0)
							AND consenti_affidamento = 1';
                // RIMOSSA LA CONDIZIONE: AND (id_lotto_affidamento IS NULL OR id_lotto_affidamento = 0)
            } else {
                $query .= 'FROM pratiche P 
							LEFT JOIN utente 			Ud ON P . id_debitore = Ud . id_utente 
							LEFT JOIN utente 			Um ON P . id_mandante = Um . id_utente 
							LEFT JOIN utente 			Ut ON P . id_tutor = Ut . id_utente 
							LEFT JOIN utente 			Ca ON P . id_anagrafica_candidato_affido = Ca . id_utente 
							LEFT JOIN utente 			Tu ON P . id_tutor = Tu . id_utente 
							LEFT JOIN lotti_mandante 	L  ON P . id_lotto_mandante = L . id 
							LEFT JOIN stati_pratiche 	S  ON P . stato_corrente = S . id 
							LEFT JOIN stato_pratica 	St ON S . classe_stato_pratica = St . id 
							LEFT JOIN contratto 		C  ON P . id_contratto = C . id 
							LEFT JOIN recapito 			R  ON(P . id_debitore = R . id_utente AND R . predefinito = 1) 
							LEFT JOIN province 			PR ON R . provincia = PR . cod_provincia 
							LEFT JOIN regioni 			RG ON RG . cod_regione = PR . cod_regione 
							LEFT JOIN tipo_indirizzo 	Ti ON(R . tipo_recapito = Ti . id_tipo_indirizzo AND R . predefinito = 1)
							LEFT JOIN ( SELECT count(id) as contaPrtDeb,id_debitore from pratiche  WHERE (scaricata = 0 OR scaricata IS NULL)
							AND (archiviata = 0 OR archiviata IS NULL)
							AND area_corrente = 2
                AND (esito_corrente IS NULL OR esito_corrente <= 0)
							AND (id_collector IS NULL OR id_collector = 0)
						 group by id_debitore) DBPRT ON DBPRT.id_debitore=P . id_debitore 
						WHERE(scaricata = 0 OR scaricata IS NULL)
							AND (archiviata = 0 OR archiviata IS NULL)
							AND area_corrente = 2
                AND (esito_corrente IS NULL OR esito_corrente <= 0)
							AND (id_collector IS NULL OR id_collector = 0)
							AND consenti_affidamento = 1';

                $queryTot .= 'FROM pratiche P 
							LEFT JOIN utente 			Ud ON P . id_debitore = Ud . id_utente 
							LEFT JOIN utente 			Um ON P . id_mandante = Um . id_utente 
							LEFT JOIN utente 			Ut ON P . id_tutor = Ut . id_utente 
							LEFT JOIN utente 			Ca ON P . id_anagrafica_candidato_affido = Ca . id_utente 
							LEFT JOIN utente 			Tu ON P . id_tutor = Tu . id_utente 
							LEFT JOIN lotti_mandante 	L  ON P . id_lotto_mandante = L . id 
							LEFT JOIN stati_pratiche 	S  ON P . stato_corrente = S . id 
							LEFT JOIN stato_pratica 	St ON S . classe_stato_pratica = St . id 
							LEFT JOIN contratto 		C  ON P . id_contratto = C . id 
							LEFT JOIN recapito 			R  ON(P . id_debitore = R . id_utente AND R . predefinito = 1) 
							LEFT JOIN province 			PR ON R . provincia = PR . cod_provincia 
							LEFT JOIN regioni 			RG ON RG . cod_regione = PR . cod_regione 
							LEFT JOIN tipo_indirizzo 	Ti ON(R . tipo_recapito = Ti . id_tipo_indirizzo AND R . predefinito = 1)
							LEFT JOIN ( SELECT count(id) as contaPrtDeb,id_debitore from pratiche  WHERE(scaricata = 0 OR scaricata IS NULL)
							AND (archiviata = 0 OR archiviata IS NULL)
							AND area_corrente = 2
                AND (esito_corrente IS NULL OR esito_corrente <= 0)
							AND (id_collector IS NULL OR id_collector = 0)
							 group by id_debitore) DBPRT ON DBPRT.id_debitore=P . id_debitore 
						WHERE(scaricata = 0 OR scaricata IS NULL)
							AND (archiviata = 0 OR archiviata IS NULL)
							AND area_corrente = 2
                AND (esito_corrente IS NULL OR esito_corrente <= 0)
							AND (id_collector IS NULL OR id_collector = 0)
							AND consenti_affidamento = 1';
                // RIMOSSA LA CONDIZIONE: AND (id_lotto_affidamento IS NULL OR id_lotto_affidamento = 0)
            }

            /* PER TEST */
            //query +=        ' AND (consenti_affidamento = 1 OR consenti_affidamento IS NULL) '; // DA RIMUOVERE LA CONDIZIONE "IS NULL"
            //query +=        ' AND (area_corrente = 2 OR area_corrente IS NULL) '; 		// DA RIMUOVERE LA CONDIZIONE "IS NULL"


            $n_filtro = $_POST['n'] + 1;

            if ($_POST['q'] != '') {
                $query .= ' AND ' . $_POST['q'];
                $queryTot .= ' AND ' . $_POST['q'];
            }

            if ($_POST['p_coll'] > 0) {
                $query .= ' AND P . id_debitore IN(SELECT id_collegato FROM collegati_pratica WHERE id_pratica <> P . id ) ';
                $queryTot .= ' AND P . id_debitore IN(SELECT id_collegato FROM collegati_pratica WHERE id_pratica <> P . id ) ';
            }
            if ($_POST['gest_pass'] == 1) {
                $query .= ' AND P . id_debitore IN(SELECT id_debitore FROM pratiche_dati_affidamenti_collector pdac join pratiche on pdac . id = pratiche . id WHERE numero_affidi > 0 AND pdac . id <> P . id ) ';
                $queryTot .= ' AND P . id_debitore IN(SELECT id_debitore FROM pratiche_dati_affidamenti_collector pdac join pratiche on pdac . id = pratiche . id WHERE numero_affidi > 0 AND pdac . id <> P . id ) ';
            } else if ($_POST['gest_pass'] == 2) {
                $query .= ' AND P . id_debitore NOT IN(SELECT id_debitore FROM pratiche_dati_affidamenti_collector pdac join pratiche on pdac . id = pratiche . id WHERE numero_affidi > 0 AND pdac . id <> P . id ) ';
                $queryTot .= ' AND P . id_debitore NOT IN(SELECT id_debitore FROM pratiche_dati_affidamenti_collector pdac join pratiche on pdac . id = pratiche . id WHERE numero_affidi > 0 AND pdac . id <> P . id ) ';
            }

            //GESTIONE PRATICHE UTENTE OPERATIVO E RESPONSABILE PER FILIALE
            if (($_SESSION['user_role'] == OPERATIVO || $_SESSION['user_role'] == OPERATIVO_RESPONSABILE_DATI)
                && (isset($separazionePraticheFilialeFlag) && $separazionePraticheFilialeFlag == 1)) {
                $filiali = db_fetch_array_assoc(db_query("SELECT id_filiale FROM operatore WHERE id_utente = " . $_SESSION['user_admin_id']))[0]['id_filiale'];
                $condFiliale = " AND P.id_filiale_origine IN ( " . $filiali . ") ";
                $queryTot .= $condFiliale;
                $query .= $condFiliale;
            }

            if ($_POST['praticheMultiple'] != 0) {
                $query .= ' AND DBPRT.contaPrtDeb=' . $_POST['praticheMultiple'];
                $queryTot .= ' AND DBPRT.contaPrtDeb=' . $_POST['praticheMultiple'];
            }


            $append_copertura_esattoriale_capoarea_collector = '';
            $append_prodotti_lavorabili_capoarea_collector = '';

            if ($ruoloUtente == CAPO_ESATTORE) {
                $append_copertura_esattoriale_capoarea_collector = ' AND (U . id_utente = ' . $_SESSION['user_admin_id'] . ' OR U . id_utente IN(SELECT id_collegato FROM collegati_esattore WHERE id_utente = ' . $_SESSION['user_admin_id'] . '))'; // AGGIUNTA NELLA $query_copertura_esattoriale

                $query_count_prodotti_lavorabili = 'SELECT id_prodotto
													FROM prodotti_lavorabili 
													WHERE id_utente = ' . $_SESSION['user_admin_id'] . '
                OR id_utente IN(SELECT id_collegato FROM collegati_esattore WHERE id_utente = ' . $_SESSION['user_admin_id'] . ')';
                if (db_num_rows(db_query($query_count_prodotti_lavorabili)) > 0) {
                    $append_prodotti_lavorabili_capoarea_collector = ' AND id_tipo_credito IN(SELECT CR . id FROM prodotti_lavorabili PL LEFT JOIN credito CR ON CR . codice = PL . id_prodotto WHERE id_utente = ' . $_SESSION['user_admin_id'] . ' OR id_utente IN(SELECT id_collegato FROM collegati_esattore WHERE id_utente = ' . $_SESSION['user_admin_id'] . '))';
                }
            }


            if ($ruoloUtente == CAPO_ESATTORE) {
                ////$query .= ' AND P . id NOT IN('.$query_copertura_esattoriale.')';
                $query .= $append_prodotti_lavorabili_capoarea_collector;


                $queryTot .= $append_prodotti_lavorabili_capoarea_collector;


            }
            //die($query_copertura_esattoriale);
            $query_criteri_ordinamento = "SELECT criteri_di_affidamento FROM impostazioni_base WHERE id = 1";
            $campo = 'criteri_di_affidamento';

            if ($_POST['esa'] != 'TUTTI' && $_POST['esa'] != '') {
                if ($_POST['esa'] == 'PHC') {
                    $query .= $append_tipo_capoarea_collector;
                    $query .= $append_prodotti_lavorabili_capoarea_collector;
                    $query_criteri_ordinamento = "SELECT criteri_di_affidamento_phc FROM impostazioni_base WHERE id = 1";

                    $campo = 'criteri_di_affidamento_phc';
                } else if ($_POST['esa'] == 'ESA') {
                    $query_criteri_ordinamento = "SELECT criteri_di_affidamento_esattore FROM impostazioni_base WHERE id = 1";
                    $campo = 'criteri_di_affidamento_esattore';

                }
            }

            $criteri_ordinamento = mysql_fetch_array(db_query($query_criteri_ordinamento));
            $criteri_ordinamento_array = explode(',', $criteri_ordinamento[$campo]);


            //die($query);

            if ($tipoFiltro == 'mandante') {
                $query .= " GROUP BY P.id_mandante";
            } else {
                $query .= " GROUP BY PR.id ";

            }


            if ($tipoFiltro == 'mandante') {
                $ordinamento = ' ORDER BY  m_cognome, m_nome';
            } else {
                $ordinamento = 'ORDER BY RG.regione,PR.provincia';

            }


            $i = 1;
            /* foreach ($criteri_ordinamento_array as $criterio) {
                 if ($criterio == 4) {
                     $ordinamento .= ' P.affidato_capitale DESC,';
                 } else if ($criterio == 3) {
                     $ordinamento .= ' C.priorita DESC,';
                 } else if ($criterio == 5) {
                     $ordinamento .= ' P.data_fine_mandato ASC,';
                 }
             }*/

            if ($ordinamento != '')
                $query .= $ordinamento;

            $ris = db_query($query);
            $risTot = db_query($queryTot);

            # CONTROLLO DA ABILITARE NEL CASO IN CUI I RISULTATI SIANO TROPPI E VOGLIAMO OBBLIGARE L'UTENTE A MIRARE MEGLIO LA RICERCA
            /*  if (db_num_rows($ris) > 5000) {
                  $errore = '<div class="note note-danger">
                             <h4 class="block" style="color:#ED4E2A"><strong style="color:#ED4E2A">ATTENZIONE!</strong> La richiesta effettuata è troppo generica.</h4>
                             <p style="color:#ED4E2A; margin-bottom: 10px">
                                   Si prega di modificare la richiesta in modo da restituire risultati più precisi.
                             </p>
                         </div>';
                  die($errore);
              }*/

            # GESTIONE DELL'ERRORE NELLA QUERY (proponiamo un avviso all'utente riportante l'errore SQL)
            if (db_error()) {
                $db_error = db_error();
                $array_errore = explode(' at line ', $db_error);

                $errore = '<div class="note note-danger">
					       <h4 class="block" style="color:#ED4E2A"><strong style="color:#ED4E2A">ATTENZIONE!</strong> Hai generato una richiesta con sintassi non corretta.</h4>
						   <p style="color:#ED4E2A; margin-bottom: 10px">
								 L\'avviso rilevato segnala un errore nei pressi di ' . str_replace('You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near', '', $array_errore[0]) . '
						   </p>
					   </div>';
                die($errore);
            }

            # CREO L'OUTPUT HTML CHE ANDRA' APPESO ALLA PAGINA
            {
                if ($tipoFiltro == "mandante") {
                    $flag_mand_prov = 0;

                    $ret = '  <div class="table-responsive col-md-12">
						<table class="table table-striped table-hover" style="border:1px solid #DDD">
							<thead>
								<tr>
									<th>
								    <input  type = "checkbox" onchange="selTuttiCheck($(this))" class="checkbox" >
									</th>
									<th>
										 Mandante
									</th>
									<th>
										 N° Pratiche
									</th>
									<th>
										 Cash Balance
									</th>
									<th>
										 Capitale Affidato
									</th>
									<th>
										 Spese Affidate
									</th>
									<th>
										 Interessi Affidati
									</th>
									<th>
										 Affidato 1
									</th>
									<th>
										 Affidato 2
									</th>
									<th>
										 Affidato 3
									</th>
									<th>
										 Totale Affidato
									</th>
																</tr>
							</thead>
							<tbody>';
                    if (mysql_num_rows($ris) > 0) {

                        while ($row = mysql_fetch_array($ris)) {

                            $ret .= '<tr class="row-mandante-' . $row['id_mandante'] . '">
                                                <td>
                                                <input type = "checkbox" class="checkbox chkBoxMandanti" name = "mandanti[]" value = "' . $row['id_mandante'] . '" ></td>
												<td>
												<a href="javascript:void(0);" class="dynamic-modal-ajax-request" id="visualizza_prt_prov' . $row['id_mandante'] . '" data-modal-level="1" data-modal-page="ajax_visualizza_prt_prov" data-id="' . $row['ids'] . '" data-params="' . $row['m_cognome'] . ' ' . $row['m_nome'] . '" >' . $row['m_cognome'] . ' ' . $row['m_nome'] . '</a>
												</td>
												<td>' . $row['tot_pratiche'] . '</td>
												<td>' . $row['cashBalance'] . '</td>
												<td>' . $row['capitaleAffidato'] . '</td>
												<td>' . $row['speseAffidate'] . '</td>
												<td>' . $row['interessiAffidati'] . '</td>
												<td>' . $row['affidato1'] . '</td>
												<td>' . $row['affidato2'] . '</td>
												<td>' . $row['affidato3'] . '</td>
												<td>' . $row['totaleAffidato'] . '</td>
											</tr>';


                            $array_prt[$row['id_mandante']] = explode(',', $row['ids']);
                            //print_r($row);
                            //echo '<br>';
                        }

                        $rowTOT = mysql_fetch_array($risTot);

                        $ret .= '<tr class="row-mandante-' . $rowTOT['id_mandante'] . '">
                                                <td colspan="2">' . $rowTOT['m_cognome'] . ' ' . $rowTOT['m_nome'] . '</td>
												<td>' . $rowTOT['tot_pratiche'] . '</td>
												<td>' . $rowTOT['cashBalance'] . '</td>
												<td>' . $rowTOT['capitaleAffidato'] . '</td>
												<td>' . $rowTOT['speseAffidate'] . '</td>
												<td>' . $rowTOT['interessiAffidati'] . '</td>
												<td>' . $rowTOT['affidato1'] . '</td>
												<td>' . $rowTOT['affidato2'] . '</td>
												<td>' . $rowTOT['affidato3'] . '</td>
												<td>' . $rowTOT['totaleAffidato'] . '</td>
											</tr>';

                    } else {
                        $ret .= '<tr>
											<td colspan="12" style="text-align:center"><strong>Nessun risultato presente con i parametri di ricerca impostati</strong></td>
										</tr>';
                    }
                    $ret .= '</tbody>
						</table>
					</div>';

                } else {
                    $flag_mand_prov = 1;

                    $ret = '  <div class="table-responsive col-md-12">
						<table class="table table-striped table-hover" style="border:1px solid #DDD">
							<thead>
								<tr>
									<th>
								    <input  type = "checkbox" onchange="selTuttiCheck($(this))" class="checkbox" >
									</th>
									<th>
										 Regione
									</th>
									<th>
										 Provincia
									</th>
									<th>
										 N° Pratiche
									</th>
									<th>
										 Cash Balance
									</th>
									<th>
										 Capitale Affidato
									</th>
									<th>
										 Spese Affidate
									</th>
									<th>
										 Interessi Affidati
									</th>
									<th>
										 Affidato 1
									</th>
									<th>
										 Affidato 2
									</th>
									<th>
										 Affidato 3
									</th>
									<th>
										 Totale Affidato
									</th>
																</tr>
							</thead>
							<tbody>';
                    if (mysql_num_rows($ris) > 0) {
                        while ($row = mysql_fetch_array($ris)) {

                            $ret .= '<tr class="row-mandante-' . $row['id'] . '">
                                                <td><input type = "checkbox" class="checkbox chkBoxProv" name = "prov[]" value = "' . $row['id'] . '" ></td>
												<td>' . $row['regione'] . '</td>
												<td><a href="javascript:void(0);" class="dynamic-modal-ajax-request" id="visualizza_prt_mand' . $row['id'] . '" data-modal-level="1" data-modal-page="ajax_visualizza_prt_mand" data-id="' . $row['ids'] . '" data-params="' . $row['provincia'] . '" >' . $row['provincia'] . '</a></td>
												<td>' . $row['tot_pratiche'] . '</td>
												<td>' . $row['cashBalance'] . '</td>
												<td>' . $row['capitaleAffidato'] . '</td>
												<td>' . $row['speseAffidate'] . '</td>
												<td>' . $row['interessiAffidati'] . '</td>
												<td>' . $row['affidato1'] . '</td>
												<td>' . $row['affidato2'] . '</td>
												<td>' . $row['affidato3'] . '</td>
												<td>' . $row['totaleAffidato'] . '</td>
											</tr>';

                            $array_prt[$row['id']] = explode(',', $row['ids']);
                            //print_r($row);
                            //echo '<br>';
                        }

                        $rowTOT = mysql_fetch_array($risTot);

                        $ret .= '<tr class="row-mandante-' . $rowTOT['id'] . '">
                                                <td colspan="3">' . $rowTOT['regione'] . '</td>
												<td>' . $rowTOT['tot_pratiche'] . '</td>
												<td>' . $rowTOT['cashBalance'] . '</td>
												<td>' . $rowTOT['capitaleAffidato'] . '</td>
												<td>' . $rowTOT['speseAffidate'] . '</td>
												<td>' . $rowTOT['interessiAffidati'] . '</td>
												<td>' . $rowTOT['affidato1'] . '</td>
												<td>' . $rowTOT['affidato2'] . '</td>
												<td>' . $rowTOT['affidato3'] . '</td>
												<td>' . $rowTOT['totaleAffidato'] . '</td>
											</tr>';

                    } else {
                        $ret .= '<tr>
											<td colspan="12" style="text-align:center"><strong>Nessun risultato presente con i parametri di ricerca impostati</strong></td>
										</tr>';
                    }
                    $ret .= '</tbody>
						</table>
					</div>';

                }
            }
            $ret .= '<div class="row" ><div class="col-md-12 text-center"><button type="button" class="btn btn-primary" onclick="proponi_collector($(this))"><i class="fa fa-user" aria-hidden="true"></i>
 Proponi Collector</button></div> </div>';

            $return['htmlTable'] = $ret;
            $return['arrayPrt'] = $array_prt;
            $return['flag_mand_prov'] = $flag_mand_prov;
            print_r(json_encode($return));
        }
        break;
    case 'filtro-scarico-lotto-mandante':
        {

            $n_filtro = $_POST['n'] + 1;

            $query = $_POST['q'];
            $ris = db_query($query);

            if (db_error()) {
                $db_error = db_error();
                $array_errore = explode(' at line ', $db_error);

                $errore = ' < div class="note note-danger" >
					       <h4 class="block" style = "color:#ED4E2A" ><strong style = "color:#ED4E2A" > ATTENZIONE!</strong > Hai generato una richiesta con sintassi non corretta .</h4 >
						   <p style = "color:#ED4E2A; margin-bottom: 10px" >
                        L\'avviso rilevato segnala un errore nei pressi di ' . str_replace('You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near', '', $array_errore[0]) . '
						   </p>
					   </div>';
                die($errore);
            }

            echo '<div class="row">
				<div class="table-responsive col-md-12">
					<table class="table table-striped table-hover" style="border:1px solid #DDD">
						<thead>
							<tr>
								<th>
									<input value="ALL" onChange="select_all_switches($(this))" type="checkbox" class="make-switchMODAL" data-size="normal" data-on-color="success" data-off-color="warning" data-on-text="&nbsp;<i class=\'fa fa-check-circle-o\'></i>&nbsp;" data-off-text="&nbsp;<i class=\'fa fa-circle-o\'></i>&nbsp;">
								</th>
								<th>
									 Id Pratica
								</th>
								<th>
									 Debitore
								</th>
								<th>
									 Mandante
								</th>
								<th>
									 Lotto
								</th>
								<th>
									 Stato
								</th>
								<th>
									 Esito
								</th>
							</tr>
						</thead>
						<tbody>';
            if (mysql_num_rows($ris) > 0) {
                while ($row = mysql_fetch_array($ris)) {
                    $data_fine_mandato = '';
                    if ($row['data_fine_mandato'] != '')
                        $data_fine_mandato = date('d-m-Y', strtotime($row['data_fine_mandato']));

                    $lotto = '';
                    if ($row['l_codice'] != '')
                        $lotto = $row['l_descrizione'] . ' (' . $row['l_codice'] . ')';

                    echo '<tr class="pratica-' . $row['id'] . ' row-scarico">
											<td><input type="checkbox" class="make-switchMODAL selectedItem" data-size="small" name="selected[]" value="' . $row['id'] . '" data-on-color="success" data-off-color="warning" data-on-text="&nbsp;<i class=\'fa fa-check-circle-o\'></i>&nbsp;" data-off-text="&nbsp;<i class=\'fa fa-circle-o\'></i>&nbsp;"><input type="hidden" value="' . $row['id'] . '" class="pratica_lista"></td>
											<td><input type="hidden" class="scarico-pratica" value="' . $row['id'] . '">' . $row['id'] . '</td>
											<td>' . $row['d_cognome'] . ' ' . $row['d_nome'] . '<br>(' . $row['d_codice_fiscale'] . ')</td>
											<td>' . $row['m_cognome'] . ' ' . $row['m_nome'] . '<br>(' . $row['m_codice_fiscale'] . ')</td>
											<td><input type="hidden" class="lotto-mandante" value="' . $row['l_id'] . '">' . $lotto . '</td>
											<td>' . $row['stato_corrente'] . '</td>
											<td>' . $row['esito_corrente'] . '</td>
										</tr>';

                    //print_r($row);
                    //echo '<br>';
                }
            } else {
                echo '<tr>
										<td colspan="13" style="text-align:center"><strong>Nessun risultato presente con i parametri di ricerca impostati</strong></td>
									</tr>';
            }
            echo '			</tbody>
					</table>
				</div>
			</div>';
        }
        break;
    case 'filtro-kpi':
        {
            $id_kpi = $_POST['elemento'];

            $query_elemento = 'SELECT filtri, group_order
							FROM kpi
							WHERE id = "' . $id_kpi . '"
							ORDER BY nome, data_ultima_modifica DESC';
            $ris_elemento = db_query($query_elemento);
            $row_elemento = mysql_fetch_array($ris_elemento);
            $elenco_filtri = trim($row_elemento['filtri'], ';');
            $elenco_ordine = trim($row_elemento['group_order'], ';');

            if (strpos($row_elemento['filtri'], '];[') >= 0)
                $filtri = explode('];[', $elenco_filtri);
            else
                $filtri = $elenco_filtri;

            if (strpos($row_elemento['group_order'], '];[') >= 0)
                $ordini = explode('];[', $elenco_ordine);
            else
                $ordini = $elenco_ordine;

            ?>
            <div id="filtro" style="margin-bottom: 15px;" data-id="<?php echo $n; ?>" class="filtro_kpi">
                <div class="row">
                    <div class="col-md-1">
                        &nbsp;
                        <div class="input-icon right">
                            <select class="filtro form-control select2me select2me_kpi">
                                <option value=""></option>
                                <option value="(">(</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        Campo
                        <div class="input-icon right">
                            <select class="filtro form-control select2me select2me_kpi">
                                <option value=""></option>
                                <?php
                                foreach ($filtri as $filtro) {
                                    $filtro = trim(trim($filtro, ']'), '[');
                                    $elem_filtro = explode(' AS ', $filtro);
                                    $key = trim($elem_filtro[0]);
                                    $value = trim($elem_filtro[1]);

                                    ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        Operatore
                        <div class="input-icon right">
                            <select class="filtro form-control select2me select2me_kpi"
                                    onChange="genera_stringa_like($(this))">
                                <option value=""></option>
                                <option value=" = ">UGUALE A</option>
                                <option value=" <> ">DIVERSO DA</option>
                                <option value=" LIKE ">SIMILE A</option>
                                <option value=" > ">MAGGIORE DI</option>
                                <option value=" < ">MINORE DI</option>
                                <option value=" >= ">MAGGIORE O UGUALE A</option>
                                <option value=" <= ">MINORE O UGUALE A</option>
                                <option value=" IS NULL ">IS NULL</option>
                                <option value=" IN ">CONTENUTO (id1,id2,..)</option>
                                <option value=" NOT IN ">NON CONTENUTO (id1,id2,..)</option>

                            </select>
                        </div>
                    </div>
                    <input type="hidden" class="filtro like1" value=''>
                    <div class="col-md-2">
                        Valore
                        <div id="filtro_val">
                            <input type="text" class="form-control filtro" placeholder="Inserisci Valore">
                        </div>
                    </div>
                    <input type="hidden" class="filtro like2" value=''>
                    <div class="col-md-1">
                        &nbsp;
                        <div class="input-icon right">
                            <select class="filtro form-control select2me select2me_kpi">
                                <option value=""></option>
                                <option value=")">)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3" style="padding-top: 17px; text-align: left;">
                        <input id="input-query" type="hidden" class="form-control form-filter" value="0" name="query">

                        <button data-html="true" data-toggle="tooltip" title="Aggiungi Filtro" type="button"
                                class="btn btn-info tooltip-info" onClick="add_condition()"><i
                                    class="fa fa-plus-square-o"></i></button>

                        <button data-html="true" data-toggle="tooltip" title="Esegui Filtro Completo" type="button"
                                class="btn btn-primary tooltip-info"
                                onClick="anteprima=0;placeLoader($(this)); crea_query('filtro');"><i
                                    class="fa fa-coffee"></i></button>

                        <button data-html="true" data-toggle="tooltip" title="Esegui Filtro Anteprima" type="button"
                                class="btn btn-success tooltip-info"
                                onClick="anteprima=1;placeLoader($(this)); crea_query('filtro');"><i
                                    class="fa fa-file-excel-o"></i></button>

                        <button style="display:none" id="search-button" class='btn btn-primary filter-submit'
                                onClick="placeLoader($(this));" type='button'><i class="fa fa-search"></i></button>

                        <button data-html="true" data-toggle="tooltip" title="Svuota Filtri" type="button"
                                class="btn btn-danger tooltip-info" onClick="reset_form()"><i class="fa fa-times"></i>
                        </button>
                        <button style="display:none" class="btn btn-danger filter-cancel"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php
            $foundGroupFields = false;
            foreach ($ordini as $ordine) {
                $ordine = trim(trim($ordine, ']'), '[');
                $elem_ordine = explode(' AS ', $ordine);
                $key = trim($elem_ordine[0]);
                $value = trim($elem_ordine[1]);
                if ($key != '') {
                    $foundGroupFields = true;
                }
            }
            ?>
            <div id="group_order" style="margin-bottom: 15px; <?php if (!$foundGroupFields) {
                echo 'display: none;';
            } ?>">
                <div class="row">
                    <div class="col-md-3">
                        Raggruppa per
                        <div class="input-icon right">
                            <select id="filtro_group" class="filtro_group form-control select2me select2me_kpi">
                                <option value=""></option>
                                <?php
                                foreach ($ordini as $ordine) {
                                    $ordine = trim(trim($ordine, ']'), '[');
                                    $elem_ordine = explode(' AS ', $ordine);
                                    $key = trim($elem_ordine[0]);
                                    $value = trim($elem_ordine[1]);

                                    ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        Ordina per
                        <div class="input-icon right">
                            <select id="filtro_order" class="filtro_order form-control select2me select2me_kpi">
                                <option value=""></option>
                                <?php // TRELLO #00069
                                foreach ($ordini as $ordine) {
                                    $ordine = trim(trim($ordine, ']'), '[');
                                    $elem_ordine = explode(' AS ', $ordine);
                                    $key = trim($elem_ordine[0]);
                                    $value = trim($elem_ordine[1]);

                                    ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        &nbsp;
                        <div class="input-icon right">
                            <select id="filtro_order_direction"
                                    class="filtro_order_direction form-control select2me select2me_kpi">
                                <option value=""></option>
                                <option value="ASC">CRESCENTE</option>
                                <option value="DESC">DECRESCENTE</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        break;
    case 'filtro-kpi-add-row':
        {
            $id_kpi = $_POST['elemento'];
            $n = $_POST['i'] + 1;

            $query_elemento = 'SELECT filtri
							FROM kpi
							WHERE id = "' . $id_kpi . '"
							ORDER BY nome, data_ultima_modifica DESC';
            $ris_elemento = db_query($query_elemento);
            $row_elemento = mysql_fetch_array($ris_elemento);
            $elenco_filtri = trim($row_elemento['filtri'], ';');
            if (strpos($row_elemento['filtri'], '];[') >= 0)
                $filtri = explode('];[', $elenco_filtri);
            else
                $filtri = $elenco_filtri;

            ?>
            <div class="row righe-aggiuntive">
                <div class="col-md-1">
                    &nbsp;
                    <div class="input-icon right">
                        <select class="filtro form-control select2me select2me_kpi">
                            <option value=""></option>
                            <option value="(">(</option>
                            <option value=")">)</option>
                            <option value=" AND "> AND</option>
                            <option value=" OR "> OR</option>
                            <option selected="selected" value=" AND ("> AND (</option>
                            <option value=" OR ("> OR (</option>
                            <option value=") AND ">) AND</option>
                            <option value=") OR ">) OR</option>
                            <option value=") AND (">) AND (</option>
                            <option value=") OR (">) OR (</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    &nbsp;
                    <div class="input-icon right">
                        <select class="filtro form-control select2me select2me_kpi">
                            <option value=""></option>
                            <?php
                            foreach ($filtri as $filtro) {
                                $filtro = trim(trim($filtro, ']'), '[');
                                $elem_filtro = explode(' AS ', $filtro);
                                $key = trim($elem_filtro[0]);
                                $value = trim($elem_filtro[1]);

                                ?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    &nbsp;
                    <div class="input-icon right">
                        <select class="filtro form-control select2me select2me_kpi"
                                onChange="genera_stringa_like($(this))">
                            <option value=""></option>
                            <option value=" = ">UGUALE A</option>
                            <option value=" <> ">DIVERSO DA</option>
                            <option value=" LIKE ">SIMILE A</option>
                            <option value=" > ">MAGGIORE DI</option>
                            <option value=" < ">MINORE DI</option>
                            <option value=" >= ">MAGGIORE O UGUALE A</option>
                            <option value=" <= ">MINORE O UGUALE A</option>
                            <option value=" IS NULL ">IS NULL</option>
                            <option value=" IN ">CONTENUTO (id1,id2,..)</option>
                            <option value=" NOT IN ">NON CONTENUTO (id1,id2,..)</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" class="filtro like1" value=''>
                <div class="col-md-2">
                    &nbsp;
                    <div id="filtro_val">
                        <input type="text" class="form-control filtro" placeholder="">
                    </div>
                </div>
                <input type="hidden" class="filtro like2" value=''>
                <div class="col-md-1">
                    &nbsp;
                    <div class="input-icon right">
                        <select class="filtro form-control select2me select2me_kpi">
                            <option value=""></option>
                            <option SELECTED value=")">)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
                    &nbsp;
                </div>
                <div class="col-md-2" style="padding-top: 17px;">
                    <input id="input-query" type="hidden" class="form-control form-filter" value="0" name="query">
                    <button type="button" class="btn btn-warning" onClick="remove_row($(this))"><i
                                class="fa fa-times"></i></button>
                    <button style="display:none" class="btn btn-danger filter-cancel"><i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            <?php
        }
        break;
    case 'filtro-kpi-query':
        {
            $rowOutputCounter = 0;

            $id_kpi = $_POST['elemento'];
            $where = $_POST['where'];


            $lower_where = strtolower($where);
            if (strpos($lower_where, 'delete ') !== false || strpos($lower_where, 'update ') !== false || strpos($lower_where, 'truncate ') !== false || strpos($lower_where, 'drop ') !== false || strpos($lower_where, 'table ') !== false || strpos($lower_where, 'alter ') !== false || strpos($lower_where, 'view ') !== false) {
                echo '';
                return;
            }

            $query_kpi = 'SELECT *
							FROM kpi
							WHERE id = "' . $id_kpi . '"';
            $ris_kpi = db_query($query_kpi);
            $row_kpi = mysql_fetch_array($ris_kpi);
            $siPuntoVirgola = false;

            $nomeFileExport = preg_replace('/[^A-Za-z0-9_\-]/', '_', $row_kpi['nome']);

            $presenzaQueryMultiple = strpos($row_kpi['query'], '[QUERY]') > 0 ? true : false;
            if ($presenzaQueryMultiple) {
                $queryDaEseguire = explode('[QUERY]', $row_kpi['query']);

                for ($i = 0; $i < count($queryDaEseguire) - 1; $i++) {
                    $q = $queryDaEseguire[$i];

                    if (StartWith($q, 'SELECT') || StartWith($q, 'CALL')) {
                        $log = "INSERT INTO `log_export`( `id_utente`, `data`, `query`) VALUES ('" . $_SESSION['user_admin_id'] . "',NOW(),'" . db_input($q) . "')";

                        db_query($log);

                        if (strpos($q, '#####') > 0) {
                            $q = str_replace('#####', ' ' . str_replace('IS NULL ""', 'IS NULL', $where), $q);
                        }

                        if (strpos($q, 'SIPUNTOVIRGOLA') !== false) {
                            $q = str_replace('SIPUNTOVIRGOLA', ' ', $q);
                        }

                        if (strpos($q, '#UTENTELOGGATO') > 0) {
                            $q = str_replace('#UTENTELOGGATO', $_SESSION['user_admin_id'], $q);
                        }

                        if (strpos($q, '#TEXTEXPORT') > 0) {
                            $q = str_replace('#TEXTEXPORT', ' ', $q);
                        }

                        db_query($q);
                    }
                }

                $row_kpi['query'] = trim($queryDaEseguire[count($queryDaEseguire) - 1], ';');
            }

            if (strpos($row_kpi['query'], '#####') > 0) {
                $query = str_replace('#####', ' ' . str_replace('IS NULL ""', 'IS NULL', $where), $row_kpi['query']);
            } else {
                if (!$presenzaQueryMultiple) {
                    $query = $row_kpi['query'] . ' WHERE ' . str_replace('IS NULL ""', 'IS NULL', $where);
                } else {
                    $query = $row_kpi['query'];
                }
            }

            if (strpos($query, 'SIPUNTOVIRGOLA') !== false) {
                $query = str_replace('SIPUNTOVIRGOLA', ' ', $query);
                $siPuntoVirgola = true;
            }
            /* if($_SESSION['user_role'] != OPERATIVO_RESPONSABILE_DATI && strpos($query,'pratiche')){
                 $where .= " AND (archiviata=0 OR archiviata is NULL)";
             }
             if($_SESSION['user_role'] != OPERATIVO_RESPONSABILE_DATI && strpos($query,'utente')){
                 $where .= " AND archiviato=0 ";
             }*/


            if (strpos($row_kpi['query'], '#UTENTELOGGATO') > 0) {
                $query = str_replace('#UTENTELOGGATO', $_SESSION['user_admin_id'], $query);
            }
            $exportTxt = false;
            if (strpos($row_kpi['query'], '#TEXTEXPORT') > 0) {
                $query = str_replace('#TEXTEXPORT', ' ', $query);
                $exportTxt = true;
            }

            $exportTabulazione = false;
            if (strpos($row_kpi['query'], '#TABEXPORT') > 0) {
                $query = str_replace('#TABEXPORT', ' ', $query);
                $exportTabulazione = true;
            }

            /*EXPORT DATI LOG SALVATAGGIO*/
            $log = "INSERT INTO `log_export`( `id_utente`, `data`, `query`) VALUES ('" . $_SESSION['user_admin_id'] . "',NOW(),'" . db_input($query) . "')";

            db_query($log);

            $logStorico = "DELETE FROM log_export where DATE_ADD(data, interval 1 month) <NOW()";
            db_query($logStorico);

            //echo $query; die();

            if ($_POST['anteprima'] == 1) {
                $query .= ' LIMIT 0,10';
            }
            $ris = db_query($query);

            $search_accents = array('à', 'á', 'â', 'ã', 'ä', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'À', '‘', '’');
            $replace_accents = array('a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'A', '\'', '\'');
            $search_chars = array("&nbsp;", "'");
            $replace_chars = array(' ', ' ');

            if (mysql_num_rows($ris) > 0) {
                $i = 0;

                ?>
                <div style="display: flex; flex-direction: column-reverse; width: 100%; overflow-x: auto;">
                    <table class="table table-striped table-hover" style="border:1px solid #DDD; display: block;">
                        <?php

                        function validateDate($date, $format = 'Y-m-d')
                        {
                            $d = DateTime::createFromFormat($format, $date);
                            // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
                            return $d && $d->format($format) === $date;
                        }

                        $string = '';
                        require_once('assets/plugins/PHPExcel/Classes/PHPExcel.php');


                        $objPHPExcel = new PHPExcel();
                        $objPHPExcel->setActiveSheetIndex(0);

                        while ($risultato = mysql_fetch_array($ris, MYSQL_ASSOC)) {
                        if ($i == 0) {
                        ?>
                        <thead>
                        <tr>
                            <?php
                            $ki = 0;
                            foreach ($risultato as $key => $value) {
                                if ($exportTxt) {
                                    if (strpos($key, "#ROWNUMBER") !== false) {
                                        if (strpos($key, "#ROWNUMBER_") !== false) {
                                            $valuePieces1 = explode('ROWNUMBER_', $key);
                                            $rowOutputRifField = $valuePieces1[1];

                                            foreach ($risultato as $key1 => $value1) {
                                                if ($key1 == $rowOutputRifField) {
                                                    if ($rowOutputRifValuePrec == '' || $rowOutputRifValuePrec != $value1) {
                                                        $rowOutputRifValuePrec = $key1;
                                                        $rowOutputCounter = 0;
                                                    }
                                                }
                                            }
                                            $rowOutputCounter++;
                                            $key = str_replace('#ROWNUMBER_' . $rowOutputRifField, str_pad(str_pad($rowOutputCounter, 7, "0", STR_PAD_LEFT), 15, " ", STR_PAD_LEFT), $key);
                                        } else {
                                            $rowOutputCounter++;
                                            $key = str_replace('#ROWNUMBER', str_pad(str_pad($rowOutputCounter, 7, "0", STR_PAD_LEFT), 15, " ", STR_PAD_LEFT), $key);
                                        }
                                        $string .= strip_tags($key);
                                    } else {
                                        $string .= strip_tags(ltrim($key));
                                    }
                                } else {
                                    $string .= strip_tags($key) . ';';
                                    $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$ki] . '1', strip_tags($key));

                                    $ki++;
                                }
                                ?>
                                <th><?php echo $key; ?></th>
                                <?php
                            }

                            $rowCount = 2;
                            $string .= PHP_EOL;
                            ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        }
                        if ($_POST['anteprima'] == 1) {
                            $kj = 0;

                            if ($i < 10) {
                                ?>
                                <tr style="max-height: 50px; overflow-y: auto;">
                                    <?php
                                    foreach ($risultato as $key => $value) {
                                        //$string .= strip_tags(trim(preg_replace('/\s+/', ' ', (str_replace(';',',',$value))))).';';
                                        //$value = str_replace($search_accents, $replace_accents, strip_tags(trim(preg_replace('/\s+/', ' ', (str_replace(';',',',$value))))));
                                        $value = str_replace($search_accents, $replace_accents, strip_tags(trim(str_replace(';', ',', $value))));

                                        if (!$exportTxt) {
                                            if ($siPuntoVirgola == false) {
                                                $string .= utf8_decode(strip_tags(ltrim(str_replace(';', ',', $value)))) . ';';
                                            } else {
                                                $string .= utf8_decode(strip_tags(ltrim($value))) . ';';
                                            }

                                            if (validateDate(strip_tags(ltrim($value)), 'Y-m-d')) {

                                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date("d/m/Y", strtotime(strip_tags(ltrim($value)))))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy');

                                            } else if (validateDate(strip_tags(ltrim($value)), 'Y-m-d H:i:s')) {
                                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date('d/m/Y H:i', strtotime(strip_tags(ltrim($value)))))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy' . ' ' . PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3);
                                            } else if (validateDate(strip_tags(ltrim($value)), 'd-m-Y')) {

                                                $arrayDate = explode('-', strip_tags(ltrim($value)));
                                                $dat = $arrayDate[2] . '-' . $arrayDate[1] . '-' . $arrayDate[0];
                                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date("d/m/Y", strtotime(strip_tags(ltrim($dat)))))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy');

                                            } else if (validateDate(strip_tags(ltrim($value)), 'd-m-Y H:i:s')) {
                                                $arrayOraDat = explode(' ', strip_tags(ltrim($value)));
                                                $arrayDate = explode('-', strip_tags(ltrim($arrayOraDat[0])));
                                                $dat = $arrayDate[2] . '-' . $arrayDate[1] . '-' . $arrayDate[0] . ' ' . $arrayOraDat[1];
                                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date('d/m/Y H:i', strtotime($dat)))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy' . ' ' . PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3);

                                            } else if (validateDate(strip_tags(ltrim($value)), 'Y/m/d')) {

                                                $dat = str_replace('/', '-', strip_tags(ltrim($value)));
                                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date("d/m/Y", strtotime(strip_tags(ltrim($dat)))))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy');

                                            } else if (validateDate(strip_tags(ltrim($value)), 'Y/m/d H:i:s')) {
                                                $dat = str_replace('/', '-', strip_tags(ltrim($value)));
                                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date('d/m/Y H:i', strtotime($dat)))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy' . ' ' . PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3);
                                            } else if (validateDate(strip_tags(ltrim($value)), 'd/m/Y')) {
                                                $arrayDate = explode('-', str_replace('/', '-', strip_tags(ltrim($value))));
                                                $dat = $arrayDate[2] . '-' . $arrayDate[1] . '-' . $arrayDate[0];
                                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date("d/m/Y", strtotime(strip_tags(ltrim($dat)))))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy');
                                            } else if (validateDate(strip_tags(ltrim($value)), 'd/m/Y H:i:s')) {
                                                $arrayOraDat = explode(' ', str_replace('/', '-', strip_tags(ltrim($value))));
                                                $arrayDate = explode('-', strip_tags(ltrim($arrayOraDat[0])));
                                                $dat = $arrayDate[2] . '-' . $arrayDate[1] . '-' . $arrayDate[0] . ' ' . $arrayOraDat[1];
                                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date('d/m/Y H:i', strtotime($dat)))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy' . ' ' . PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3);
                                            } else {
                                                $tempNum = str_replace(',', '.', str_replace('.', '', strip_tags(ltrim($value))));
                                                if (is_numeric($tempNum)) {
                                                    //$objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, strip_tags(ltrim($value)));
                                                    $objPHPExcel->getActiveSheet()->setCellValueExplicit($listaColonneExcel[$kj] . $rowCount, $tempNum, PHPExcel_Cell_DataType::TYPE_NUMERIC);

                                                } else {
                                                    $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, strip_tags(ltrim($value)));
                                                }
                                            }

                                            $kj++;

                                        } else {
                                            if (strpos($value, "#ROWNUMBER") !== false) {
                                                if (strpos($value, "#ROWNUMBER_") !== false) {
                                                    $valuePieces1 = explode('ROWNUMBER_', $value);
                                                    $rowOutputRifField = $valuePieces1[1];

                                                    foreach ($risultato as $key1 => $value1) {
                                                        if ($key1 == $rowOutputRifField) {
                                                            if ($rowOutputRifValuePrec == '' || $rowOutputRifValuePrec != $value1) {
                                                                $rowOutputRifValuePrec = $value1;
                                                                $rowOutputCounter = 0;
                                                            }
                                                        }
                                                    }
                                                    $rowOutputCounter++;
                                                    $value = str_replace('#ROWNUMBER_' . $rowOutputRifField, str_pad(str_pad($rowOutputCounter, 7, "0", STR_PAD_LEFT), 15, " ", STR_PAD_LEFT), $value);
                                                } else {
                                                    $rowOutputCounter++;
                                                    $value = str_replace('#ROWNUMBER', str_pad(str_pad($rowOutputCounter, 7, "0", STR_PAD_LEFT), 15, " ", STR_PAD_LEFT), $value);
                                                }
                                                $string .= utf8_decode(strip_tags($value));
                                            } else {
                                                $string .= utf8_decode(strip_tags(ltrim($value)));
                                            }
                                        }
                                        ?>
                                        <td><?php echo $value; ?></td>
                                        <?php
                                    }
                                    ?>
                                </tr>
                                <?php
                            } else {
                                foreach ($risultato as $key => $value) {
                                    if ($kj < 10) {
                                        //$string .= strip_tags(trim(preg_replace('/\s+/', ' ', (str_replace(';',',',$value))))).';';
                                        //$value = str_replace($search_accents, $replace_accents, strip_tags(trim(preg_replace('/\s+/', ' ', (str_replace(';',',',$value))))));
                                        $value = str_replace($search_accents, $replace_accents, strip_tags(trim(str_replace(';', ',', $value))));

                                        if (!$exportTxt) {
                                            if ($siPuntoVirgola == false) {
                                                $string .= strip_tags(ltrim(str_replace(';', ',', $value))) . ';';
                                            } else {
                                                $string .= strip_tags(ltrim($value)) . ';';

                                            }

                                            if (validateDate(strip_tags(ltrim($value)), 'Y-m-d')) {

                                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date("d/m/Y", strtotime(strip_tags(ltrim($value)))))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy');

                                            } else if (validateDate(strip_tags(ltrim($value)), 'Y-m-d H:i:s')) {
                                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date('d/m/Y H:i', strtotime(strip_tags(ltrim($value)))))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy' . ' ' . PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3);
                                            } else if (validateDate(strip_tags(ltrim($value)), 'd-m-Y')) {

                                                $arrayDate = explode('-', strip_tags(ltrim($value)));
                                                $dat = $arrayDate[2] . '-' . $arrayDate[1] . '-' . $arrayDate[0];
                                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date("d/m/Y", strtotime(strip_tags(ltrim($dat)))))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy');

                                            } else if (validateDate(strip_tags(ltrim($value)), 'd-m-Y H:i:s')) {
                                                $arrayOraDat = explode(' ', strip_tags(ltrim($value)));
                                                $arrayDate = explode('-', strip_tags(ltrim($arrayOraDat[0])));
                                                $dat = $arrayDate[2] . '-' . $arrayDate[1] . '-' . $arrayDate[0] . ' ' . $arrayOraDat[1];
                                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date('d/m/Y H:i', strtotime($dat)))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy' . ' ' . PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3);

                                            } else if (validateDate(strip_tags(ltrim($value)), 'Y/m/d')) {

                                                $dat = str_replace('/', '-', strip_tags(ltrim($value)));
                                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date("d/m/Y", strtotime(strip_tags(ltrim($dat)))))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy');

                                            } else if (validateDate(strip_tags(ltrim($value)), 'Y/m/d H:i:s')) {
                                                $dat = str_replace('/', '-', strip_tags(ltrim($value)));
                                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date('d/m/Y H:i', strtotime($dat)))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy' . ' ' . PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3);
                                            } else if (validateDate(strip_tags(ltrim($value)), 'd/m/Y')) {
                                                $arrayDate = explode('-', str_replace('/', '-', strip_tags(ltrim($value))));
                                                $dat = $arrayDate[2] . '-' . $arrayDate[1] . '-' . $arrayDate[0];
                                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date("d/m/Y", strtotime(strip_tags(ltrim($dat)))))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy');
                                            } else if (validateDate(strip_tags(ltrim($value)), 'd/m/Y H:i:s')) {
                                                $arrayOraDat = explode(' ', str_replace('/', '-', strip_tags(ltrim($value))));
                                                $arrayDate = explode('-', strip_tags(ltrim($arrayOraDat[0])));
                                                $dat = $arrayDate[2] . '-' . $arrayDate[1] . '-' . $arrayDate[0] . ' ' . $arrayOraDat[1];
                                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date('d/m/Y H:i', strtotime($dat)))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy' . ' ' . PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3);
                                            } else {
                                                $tempNum = str_replace(',', '.', str_replace('.', '', strip_tags(ltrim($value))));
                                                if (is_numeric($tempNum)) {
                                                    //$objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, strip_tags(ltrim($value)));
                                                    $objPHPExcel->getActiveSheet()->setCellValueExplicit($listaColonneExcel[$kj] . $rowCount, $tempNum, PHPExcel_Cell_DataType::TYPE_NUMERIC);

                                                } else {
                                                    $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, strip_tags(ltrim($value)));
                                                }
                                            }

                                            $kj++;
                                        } else {
                                            if (strpos($value, "#ROWNUMBER") !== false) {
                                                if (strpos($value, "#ROWNUMBER_") !== false) {
                                                    $valuePieces1 = explode('ROWNUMBER_', $value);
                                                    $rowOutputRifField = $valuePieces1[1];

                                                    foreach ($risultato as $key1 => $value1) {
                                                        if ($key1 == $rowOutputRifField) {
                                                            if ($rowOutputRifValuePrec == '' || $rowOutputRifValuePrec != $value1) {
                                                                $rowOutputRifValuePrec = $value1;
                                                                $rowOutputCounter = 0;
                                                            }
                                                        }
                                                    }
                                                    $rowOutputCounter++;
                                                    $value = str_replace('#ROWNUMBER_' . $rowOutputRifField, str_pad(str_pad($rowOutputCounter, 7, "0", STR_PAD_LEFT), 15, " ", STR_PAD_LEFT), $value);
                                                } else {
                                                    $rowOutputCounter++;
                                                    $value = str_replace('#ROWNUMBER', str_pad(str_pad($rowOutputCounter, 7, "0", STR_PAD_LEFT), 15, " ", STR_PAD_LEFT), $value);
                                                }
                                                $string .= strip_tags($value);
                                            } else {
                                                $string .= strip_tags(ltrim($value));
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        else {
                            $kj = 0;

                            ?>
                            <tr style="max-height: 50px; overflow-y: auto;">
                                <?php
                                foreach ($risultato as $key => $value) {
                                    //$string .= strip_tags(trim(preg_replace('/\s+/', ' ', (str_replace(';',',',$value))))).';';
                                    //$value = str_replace($search_accents, $replace_accents, strip_tags(trim(preg_replace('/\s+/', ' ', (str_replace(';',',',$value))))));
                                    $value = str_replace($search_accents, $replace_accents, strip_tags(trim(str_replace(';', ',', $value))));

                                    if (!$exportTxt) {
                                        if ($siPuntoVirgola == false) {
                                            $string .= strip_tags(ltrim(str_replace(';', ',', $value))) . ';';
                                        } else {
                                            $string .= strip_tags(ltrim($value)) . ';';

                                        }

                                        if (validateDate(strip_tags(ltrim($value)), 'Y-m-d')) {

                                            $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date("d/m/Y", strtotime(strip_tags(ltrim($value)))))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy');

                                        } else if (validateDate(strip_tags(ltrim($value)), 'Y-m-d H:i:s')) {
                                            $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date('d/m/Y H:i', strtotime(strip_tags(ltrim($value)))))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy' . ' ' . PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3);
                                        } else if (validateDate(strip_tags(ltrim($value)), 'd-m-Y')) {

                                            $arrayDate = explode('-', strip_tags(ltrim($value)));
                                            $dat = $arrayDate[2] . '-' . $arrayDate[1] . '-' . $arrayDate[0];
                                            $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date("d/m/Y", strtotime(strip_tags(ltrim($dat)))))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy');

                                        } else if (validateDate(strip_tags(ltrim($value)), 'd-m-Y H:i:S')) {
                                            $arrayOraDat = explode(' ', strip_tags(ltrim($value)));
                                            $arrayDate = explode('-', strip_tags(ltrim($arrayOraDat[0])));
                                            $dat = $arrayDate[2] . '-' . $arrayDate[1] . '-' . $arrayDate[0] . ' ' . $arrayOraDat[1];
                                            $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date('d/m/Y H:i', strtotime($dat)))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy' . ' ' . PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3);

                                        } else if (validateDate(strip_tags(ltrim($value)), 'Y/m/d')) {

                                            $dat = str_replace('/', '-', strip_tags(ltrim($value)));
                                            $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date("d/m/Y", strtotime(strip_tags(ltrim($dat)))))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy');

                                        } else if (validateDate(strip_tags(ltrim($value)), 'Y/m/d H:i:s')) {
                                            $dat = str_replace('/', '-', strip_tags(ltrim($value)));
                                            $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date('d/m/Y H:i', strtotime($dat)))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy' . ' ' . PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3);
                                        } else if (validateDate(strip_tags(ltrim($value)), 'd/m/Y')) {
                                            $arrayDate = explode('-', str_replace('/', '-', strip_tags(ltrim($value))));
                                            $dat = $arrayDate[2] . '-' . $arrayDate[1] . '-' . $arrayDate[0];
                                            $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date("d/m/Y", strtotime(strip_tags(ltrim($dat)))))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy');
                                        } else if (validateDate(strip_tags(ltrim($value)), 'd/m/Y H:i:s')) {
                                            $arrayOraDat = explode(' ', str_replace('/', '-', strip_tags(ltrim($value))));
                                            $arrayDate = explode('-', strip_tags(ltrim($arrayOraDat[0])));
                                            $dat = $arrayDate[2] . '-' . $arrayDate[1] . '-' . $arrayDate[0] . ' ' . $arrayOraDat[1];
                                            $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date('d/m/Y H:i', strtotime($dat)))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy' . ' ' . PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3);
                                        } else {
                                            $tempNum = str_replace(',', '.', str_replace('.', '', strip_tags(ltrim($value))));
                                            if (is_numeric($tempNum)) {
                                                //$objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, strip_tags(ltrim($value)));
                                                $objPHPExcel->getActiveSheet()->setCellValueExplicit($listaColonneExcel[$kj] . $rowCount, $tempNum, PHPExcel_Cell_DataType::TYPE_NUMERIC);

                                            } else {
                                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, strip_tags(ltrim($value)));
                                            }
                                        }

                                        $kj++;
                                    } else {
                                        if (strpos($value, "#ROWNUMBER") !== false) {
                                            if (strpos($value, "#ROWNUMBER_") !== false) {
                                                $valuePieces1 = explode('ROWNUMBER_', $value);
                                                $rowOutputRifField = $valuePieces1[1];

                                                foreach ($risultato as $key1 => $value1) {
                                                    if ($key1 == $rowOutputRifField) {
                                                        if ($rowOutputRifValuePrec == '' || $rowOutputRifValuePrec != $value1) {
                                                            $rowOutputRifValuePrec = $value1;
                                                            $rowOutputCounter = 0;
                                                        }
                                                    }
                                                }
                                                $rowOutputCounter++;
                                                $value = str_replace('#ROWNUMBER_' . $rowOutputRifField, str_pad(str_pad($rowOutputCounter, 7, "0", STR_PAD_LEFT), 15, " ", STR_PAD_LEFT), $value);
                                            } else {
                                                $rowOutputCounter++;
                                                $value = str_replace('#ROWNUMBER', str_pad(str_pad($rowOutputCounter, 7, "0", STR_PAD_LEFT), 15, " ", STR_PAD_LEFT), $value);
                                            }
                                            $string .= strip_tags($value);
                                        } else {
                                            $string .= strip_tags(ltrim($value));
                                        }
                                    }
                                    ?>
                                    <td><?php echo $value; ?></td>
                                    <?php
                                }

                                ?>
                            </tr>

                        <?php }

                        $string .= PHP_EOL;
                        $rowCount++;


                        $i++;
                        }

                        if ($exportTabulazione) {
                            $string = str_replace(';', '	', $string);
                        }
                        writeSession('file_export_dati', $string);
                        ?>
                        </tbody>
                    </table>

                    <div style="width: 100%; text-align: right; margin-bottom: 20px;">
                        <div id="resoconto-kpi" style="float: left; text-align: left;"></div>
                        <!--VECCHIA GESTIONE DEL BOTTONE SCARICA RISULTATI ( NON FUNZIONA CON TROPPI DATI SU CHROME)-->
                        <!--                        <a download="export_<?php /*echo date('Y-m-d'); */ ?>.csv" target="_blank" class="btn btn-primary"
                           href="data:text/octet-stream;base64,<?php /*echo base64_encode($string) */ ?>">Download
                            Risultati</a>
                        <br>
                        <br>
                        <br>-->
                        <?php

                        if ($exportTxt) {
                            ?>
                            <a target="_blank" class="btn btn-primary"
                               href="./scarica_export_dati.php?name=<?php echo $nomeFileExport . '_' . date('Y-m-d_H-i-s'); ?>.txt">Download
                                Text</a>
                        <?php } elseif ($exportTabulazione) {
                            ?>
                            <a target="_blank" class="btn btn-primary"
                               href="./scarica_export_dati.php?name=<?php echo $nomeFileExport . '_' . date('Y-m-d_H-i-s'); ?>.txt">Download
                                Tabulato</a>
                            <?php
                        } else {

                            $percorso = "report/files/";
                            $file = rtrim($percorso, '/') . '/' . $nomeFileExport . '_' . date('Y-m-d') . '.xlsx';

                            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                            $objWriter->save($file);
                            chmod($file, 0777);

                            if ($_POST['anteprima'] == 1) {
                                $_POST['reportType'] = 'csv';
                                ?>
                                <a class="btn btn-primary"
                                   href="#"
                                   onclick="scaricaReportKpi('<?php echo base64_encode(json_encode($_POST)); ?>')">Download
                                    CSV</a>
                                <?php
                                $_POST['reportType'] = 'excel';
                                ?>
                                <a class="btn btn-success" href="#"
                                   onclick="scaricaReportKpi('<?php echo base64_encode(json_encode($_POST)); ?>')">Download
                                    EXCEL</a>
                                <?php
                            } else {
                                ?>
                                <a target="_blank" class="btn btn-primary"
                                   href="./scarica_export_dati.php?name=<?php echo $nomeFileExport . '_' . date('Y-m-d_H-i-s'); ?>.csv">Download
                                    CSV</a>
                                <a target="_blank" class="btn btn-success" href="./<?php echo $file; ?>">Download
                                    EXCEL</a>
                                <?php
                            }
                        } ?>
                    </div>
                </div>
                <?php
            } else {

            }
        }
        break;
    case 'filtro-kpi-query-download-doc':
        {
            $rowOutputCounter = 0;
            $rowOutputRifField = '';
            $rowOutputRifValuePrec = '';

            $id_kpi = $_POST['elemento'];
            $where = $_POST['where'];

            $lower_where = strtolower($where);
            if (strpos($lower_where, 'delete ') !== false || strpos($lower_where, 'update ') !== false || strpos($lower_where, 'truncate ') !== false || strpos($lower_where, 'drop ') !== false || strpos($lower_where, 'table ') !== false || strpos($lower_where, 'alter ') !== false || strpos($lower_where, 'view ') !== false) {
                echo '';
                return;
            }

            $query_kpi = 'SELECT *
							FROM kpi
							WHERE id = "' . $id_kpi . '"';
            $ris_kpi = db_query($query_kpi);
            $row_kpi = mysql_fetch_array($ris_kpi);
            $siPuntoVirgola = false;

            $nomeFileExport = preg_replace('/[^A-Za-z0-9_\-]/', '_', $row_kpi['nome']);

            $presenzaQueryMultiple = strpos($row_kpi['query'], '[QUERY]') > 0 ? true : false;
            if ($presenzaQueryMultiple) {
                $queryDaEseguire = explode('[QUERY]', $row_kpi['query']);

                for ($i = 0; $i < count($queryDaEseguire) - 1; $i++) {
                    $q = $queryDaEseguire[$i];

                    if (StartWith($q, 'SELECT') || StartWith($q, 'CALL')) {
                        $log = "INSERT INTO `log_export`( `id_utente`, `data`, `query`) VALUES ('" . $_SESSION['user_admin_id'] . "',NOW(),'" . db_input($q) . "')";

                        db_query($log);

                        if (strpos($q, '#####') > 0) {
                            $q = str_replace('#####', ' ' . str_replace('IS NULL ""', 'IS NULL', $where), $q);
                        }

                        if (strpos($q, 'SIPUNTOVIRGOLA') !== false) {
                            $q = str_replace('SIPUNTOVIRGOLA', ' ', $q);
                        }

                        if (strpos($q, '#UTENTELOGGATO') > 0) {
                            $q = str_replace('#UTENTELOGGATO', $_SESSION['user_admin_id'], $q);
                        }

                        if (strpos($q, '#TEXTEXPORT') > 0) {
                            $q = str_replace('#TEXTEXPORT', ' ', $q);
                        }

                        db_query($q);
                    }
                }

                $row_kpi['query'] = trim($queryDaEseguire[count($queryDaEseguire) - 1], ';');
            }

            if (strpos($row_kpi['query'], '#####') > 0) {
                $query = str_replace('#####', ' ' . str_replace('IS NULL ""', 'IS NULL', $where), $row_kpi['query']);
            } else {
                if (!$presenzaQueryMultiple) {
                    $query = $row_kpi['query'] . ' WHERE ' . str_replace('IS NULL ""', 'IS NULL', $where);
                } else {
                    $query = $row_kpi['query'];
                }
            }

            if (strpos($query, 'SIPUNTOVIRGOLA') !== false) {
                $query = str_replace('SIPUNTOVIRGOLA', ' ', $query);
                $siPuntoVirgola = true;
            }

            if (strpos($row_kpi['query'], '#UTENTELOGGATO') > 0) {
                $query = str_replace('#UTENTELOGGATO', $_SESSION['user_admin_id'], $query);
            }
            $exportTxt = false;
            if (strpos($row_kpi['query'], '#TEXTEXPORT') > 0) {
                $query = str_replace('#TEXTEXPORT', ' ', $query);
                $exportTxt = true;
            }

            $exportTabulazione = false;
            if (strpos($row_kpi['query'], '#TABEXPORT') > 0) {
                $query = str_replace('#TABEXPORT', ' ', $query);
                $exportTabulazione = true;
            }

            /*EXPORT DATI LOG SALVATAGGIO*/
            $log = "INSERT INTO `log_export`( `id_utente`, `data`, `query`) VALUES ('" . $_SESSION['user_admin_id'] . "',NOW(),'" . db_input($query) . "')";

            db_query($log);

            $logStorico = "DELETE FROM log_export where DATE_ADD(data, interval 1 month) <NOW()";
            db_query($logStorico);

            //echo $query; die();

            $ris = db_query($query);

            $search_accents = array('à', 'á', 'â', 'ã', 'ä', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'À', '‘', '’');
            $replace_accents = array('a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'A', '\'', '\'');
            $search_chars = array("&nbsp;", "'");
            $replace_chars = array(' ', ' ');

            if (mysql_num_rows($ris) > 0) {
                $i = 0;

                function validateDate($date, $format = 'Y-m-d')
                {
                    $d = DateTime::createFromFormat($format, $date);
                    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
                    return $d && $d->format($format) === $date;
                }

                if ($_POST['reportType'] == 'excel') {
                    require_once('assets/plugins/PHPExcel/Classes/PHPExcel.php');

                    $objPHPExcel = new PHPExcel();
                    $objPHPExcel->setActiveSheetIndex(0);

                    while ($risultato = mysql_fetch_array($ris, MYSQL_ASSOC)) {
                        if ($i == 0) {

                            $ki = 0;
                            foreach ($risultato as $key => $value) {
                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$ki] . '1', strip_tags($key));
                                $ki++;
                            }

                            $rowCount = 2;
                        }

                        $kj = 0;

                        foreach ($risultato as $key => $value) {
                            $value = str_replace($search_accents, $replace_accents, strip_tags(trim(str_replace(';', ',', $value))));

                            if (validateDate(strip_tags(ltrim($value)), 'Y-m-d')) {

                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date("d/m/Y", strtotime(strip_tags(ltrim($value)))))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy');

                            } else if (validateDate(strip_tags(ltrim($value)), 'Y-m-d H:i:s')) {
                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date('d/m/Y H:i', strtotime(strip_tags(ltrim($value)))))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy' . ' ' . PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3);
                            } else if (validateDate(strip_tags(ltrim($value)), 'd-m-Y')) {

                                $arrayDate = explode('-', strip_tags(ltrim($value)));
                                $dat = $arrayDate[2] . '-' . $arrayDate[1] . '-' . $arrayDate[0];
                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date("d/m/Y", strtotime(strip_tags(ltrim($dat)))))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy');

                            } else if (validateDate(strip_tags(ltrim($value)), 'd-m-Y H:i:S')) {
                                $arrayOraDat = explode(' ', strip_tags(ltrim($value)));
                                $arrayDate = explode('-', strip_tags(ltrim($arrayOraDat[0])));
                                $dat = $arrayDate[2] . '-' . $arrayDate[1] . '-' . $arrayDate[0] . ' ' . $arrayOraDat[1];
                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date('d/m/Y H:i', strtotime($dat)))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy' . ' ' . PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3);

                            } else if (validateDate(strip_tags(ltrim($value)), 'Y/m/d')) {

                                $dat = str_replace('/', '-', strip_tags(ltrim($value)));
                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date("d/m/Y", strtotime(strip_tags(ltrim($dat)))))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy');

                            } else if (validateDate(strip_tags(ltrim($value)), 'Y/m/d H:i:s')) {
                                $dat = str_replace('/', '-', strip_tags(ltrim($value)));
                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date('d/m/Y H:i', strtotime($dat)))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy' . ' ' . PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3);
                            } else if (validateDate(strip_tags(ltrim($value)), 'd/m/Y')) {
                                $arrayDate = explode('-', str_replace('/', '-', strip_tags(ltrim($value))));
                                $dat = $arrayDate[2] . '-' . $arrayDate[1] . '-' . $arrayDate[0];
                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date("d/m/Y", strtotime(strip_tags(ltrim($dat)))))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy');
                            } else if (validateDate(strip_tags(ltrim($value)), 'd/m/Y H:i:s')) {
                                $arrayOraDat = explode(' ', str_replace('/', '-', strip_tags(ltrim($value))));
                                $arrayDate = explode('-', strip_tags(ltrim($arrayOraDat[0])));
                                $dat = $arrayDate[2] . '-' . $arrayDate[1] . '-' . $arrayDate[0] . ' ' . $arrayOraDat[1];
                                $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, date('d/m/Y H:i', strtotime($dat)))->getStyle($listaColonneExcel[$kj] . $rowCount)->getNumberFormat()->setFormatCode('dd/mm/yyyy' . ' ' . PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3);
                            } else {
                                $tempNum = str_replace(',', '.', str_replace('.', '', strip_tags(ltrim($value))));
                                if (is_numeric($tempNum)) {
                                    //$objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, strip_tags(ltrim($value)));
                                    $objPHPExcel->getActiveSheet()->setCellValueExplicit($listaColonneExcel[$kj] . $rowCount, $tempNum, PHPExcel_Cell_DataType::TYPE_NUMERIC);

                                } else {
                                    $objPHPExcel->getActiveSheet()->SetCellValue($listaColonneExcel[$kj] . $rowCount, strip_tags(ltrim($value)));
                                }
                            }

                            $kj++;
                        }
                        $rowCount++;

                        $i++;
                    }

                    $percorso = "report/files/";
                    $file = rtrim($percorso, '/') . '/' . $nomeFileExport . '_' . date('Y-m-d') . '.xlsx';

                    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                    $objWriter->save($file);
                    chmod($file, 0777);

                    echo './' . $file;
                } else if ($_POST['reportType'] == 'csv') {
                    $string = '';

                    while ($risultato = mysql_fetch_array($ris, MYSQL_ASSOC)) {
                        if ($i == 0) {

                            $ki = 0;
                            foreach ($risultato as $key => $value) {
                                if ($exportTxt) {
                                    if (strpos($value, "#ROWNUMBER") !== false) {
                                        if (strpos($value, "#ROWNUMBER_") !== false) {
                                            $valuePieces1 = explode('ROWNUMBER_', $value);
                                            $rowOutputRifField = $valuePieces1[1];

                                            foreach ($risultato as $key1 => $value1) {
                                                if ($key1 == $rowOutputRifField) {
                                                    if ($rowOutputRifValuePrec == '' || $rowOutputRifValuePrec != $value1) {
                                                        $rowOutputRifValuePrec = $value1;
                                                        $rowOutputCounter = 0;
                                                    }
                                                }
                                            }
                                            $rowOutputCounter++;
                                            $value = str_replace('#ROWNUMBER_' . $rowOutputRifField, str_pad(str_pad($rowOutputCounter, 7, "0", STR_PAD_LEFT), 15, " ", STR_PAD_LEFT), $value);
                                        } else {
                                            $rowOutputCounter++;
                                            $value = str_replace('#ROWNUMBER', str_pad(str_pad($rowOutputCounter, 7, "0", STR_PAD_LEFT), 15, " ", STR_PAD_LEFT), $value);
                                        }
                                    }
                                    $string .= strip_tags($key);

                                } else {
                                    $string .= strip_tags($key) . ';';

                                    $ki++;
                                }
                            }

                            $rowCount = 2;
                            $string .= PHP_EOL;

                        }

                        $kj = 0;

                        foreach ($risultato as $key => $value) {
                            //$string .= strip_tags(trim(preg_replace('/\s+/', ' ', (str_replace(';',',',$value))))).';';
                            //$value = str_replace($search_accents, $replace_accents, strip_tags(trim(preg_replace('/\s+/', ' ', (str_replace(';',',',$value))))));
                            $value = str_replace($search_accents, $replace_accents, strip_tags(trim(str_replace(';', ',', $value))));

                            if (!$exportTxt) {
                                if ($siPuntoVirgola == false) {
                                    $string .= strip_tags(ltrim(str_replace(';', ',', $value))) . ';';
                                } else {
                                    $string .= strip_tags(ltrim($value)) . ';';
                                }

                                $kj++;
                            } else {
                                if (strpos($value, "#ROWNUMBER") !== false) {
                                    if (strpos($value, "#ROWNUMBER_") !== false) {
                                        $valuePieces1 = explode('ROWNUMBER_', $value);
                                        $rowOutputRifField = $valuePieces1[1];

                                        foreach ($risultato as $key1 => $value1) {
                                            if ($key1 == $rowOutputRifField) {
                                                if ($rowOutputRifValuePrec == '' || $rowOutputRifValuePrec != $value1) {
                                                    $rowOutputRifValuePrec = $value1;
                                                    $rowOutputCounter = 0;
                                                }
                                            }
                                        }
                                        $rowOutputCounter++;
                                        $value = str_replace('#ROWNUMBER_' . $rowOutputRifField, str_pad(str_pad($rowOutputCounter, 7, "0", STR_PAD_LEFT), 15, " ", STR_PAD_LEFT), $value);
                                    } else {
                                        $rowOutputCounter++;
                                        $value = str_replace('#ROWNUMBER', str_pad(str_pad($rowOutputCounter, 7, "0", STR_PAD_LEFT), 15, " ", STR_PAD_LEFT), $value);
                                    }

                                    $string .= strip_tags($value);
                                } else {
                                    $string .= strip_tags(ltrim($value));
                                }
                            }
                        }
                        $string .= PHP_EOL;
                        $i++;
                    }

                    writeSession('file_export_dati', $string);

                    echo './scarica_export_dati.php?name=' . $nomeFileExport . '_' . date('Y-m-d_H-i-s') . '.csv';
                }
            }
        }
        break;
    case 'filtro-kpi-query-remidabox':
        {
            $rowOutputCounter = 0;

            $id_kpi = $_POST['elemento'];
            $pratiche = $_POST['pratiche'];

            $query_kpi = 'SELECT *
							FROM kpi
							WHERE id = "' . $id_kpi . '"';
            $ris_kpi = db_query($query_kpi);
            $row_kpi = mysql_fetch_array($ris_kpi);
            $nomeFileExport = preg_replace('/[^A-Za-z0-9_\-]/', '_', $row_kpi['nome']);


            $siPuntoVirgola = false;

            if (strpos($row_kpi['query'], '#####') > 0) {
                $query = str_replace('#####', '  P.id IN ( ' . $pratiche . ' )', $row_kpi['query']);
            } else {
                $query = $row_kpi['query'] . ' WHERE P.id IN (' . $pratiche . ')';
            }

            if (strpos($query, 'SIPUNTOVIRGOLA') !== false) {
                $query = str_replace('SIPUNTOVIRGOLA', ' ', $query);
                $siPuntoVirgola = true;
            }

            if (strpos($row_kpi['query'], '#UTENTELOGGATO') > 0) {
                $query = str_replace('#UTENTELOGGATO', $_SESSION['user_admin_id'], $query);
            }

            $exportTxt = false;
            if (strpos($row_kpi['query'], '#TEXTEXPORT') > 0) {
                $query = str_replace('#TEXTEXPORT', ' ', $query);
                $exportTxt = true;
            }

            /*EXPORT DATI LOG SALVATAGGIO*/
            $log = "INSERT INTO `log_export`( `id_utente`, `data`, `query`) VALUES ('" . $_SESSION['user_admin_id'] . "',NOW(),'" . db_input($query) . "')";

            db_query($log);

            $logStorico = "DELETE FROM log_export where DATE_ADD(data, interval 1 month) <NOW()";
            db_query($logStorico);

            $ris = db_query($query);

            if (mysql_num_rows($ris) > 0) {
                $i = 0;
                $string = '';
                while ($risultato = mysql_fetch_array($ris, MYSQL_ASSOC)) {
                    if ($i == 0) {
                        ?>

                        <?php
                        foreach ($risultato as $key => $value) {
                            if ($exportTxt) {
                                if (strpos($value, "#ROWNUMBER") !== false) {
                                    if (strpos($value, "#ROWNUMBER_") !== false) {
                                        $valuePieces1 = explode('ROWNUMBER_', $value);
                                        $rowOutputRifField = $valuePieces1[1];

                                        foreach ($risultato as $key1 => $value1) {
                                            if ($key1 == $rowOutputRifField) {
                                                if ($rowOutputRifValuePrec == '' || $rowOutputRifValuePrec != $value1) {
                                                    $rowOutputRifValuePrec = $value1;
                                                    $rowOutputCounter = 0;
                                                }
                                            }
                                        }
                                        $rowOutputCounter++;
                                        $value = str_replace('#ROWNUMBER_' . $rowOutputRifField, str_pad(str_pad($rowOutputCounter, 7, "0", STR_PAD_LEFT), 15, " ", STR_PAD_LEFT), $key);
                                    } else {
                                        $rowOutputCounter++;
                                        $value = str_replace('#ROWNUMBER', str_pad(str_pad($rowOutputCounter, 7, "0", STR_PAD_LEFT), 15, " ", STR_PAD_LEFT), $key);
                                    }
                                }
                                $string .= strip_tags($key);

                            } else {
                                $string .= strip_tags($key) . ';';
                            }
                        }

                        $string .= PHP_EOL;

                    }
                    ?>
                    <?php
                    foreach ($risultato as $key => $value) {
                        if ($exportTxt) {
                            if (strpos($value, "#ROWNUMBER") !== false) {
                                if (strpos($value, "#ROWNUMBER_") !== false) {
                                    $valuePieces1 = explode('ROWNUMBER_', $value);
                                    $rowOutputRifField = $valuePieces1[1];

                                    foreach ($risultato as $key1 => $value1) {
                                        if ($key1 == $rowOutputRifField) {
                                            if ($rowOutputRifValuePrec == '' || $rowOutputRifValuePrec != $value) {
                                                $rowOutputRifValuePrec = $value1;
                                                $rowOutputCounter = 0;
                                            }
                                        }
                                    }
                                    $rowOutputCounter++;
                                    $value = str_replace('#ROWNUMBER_' . $rowOutputRifField, str_pad(str_pad($rowOutputCounter, 7, "0", STR_PAD_LEFT), 15, " ", STR_PAD_LEFT), $value);
                                } else {
                                    $rowOutputCounter++;
                                    $value = str_replace('#ROWNUMBER', str_pad(str_pad($rowOutputCounter, 7, "0", STR_PAD_LEFT), 15, " ", STR_PAD_LEFT), $value);
                                }
                                $string .= strip_tags($value);
                            } else {
                                $string .= strip_tags(ltrim($value));
                            }

                        } else {
                            if ($siPuntoVirgola == false) {
                                $string .= strip_tags(ltrim(str_replace(';', ',', $value))) . ';';
                            } else {
                                $string .= strip_tags(ltrim($value)) . ';';

                            }
                            //$string .= strip_tags(ltrim(str_replace(';', ',', $value))) . ';';
                        }

                    }

                    $string .= PHP_EOL;

                    $i++;
                }

                writeSession('file_export_dati', $string);

                $ret['res'] = true;
                $ret['name'] = $nomeFileExport;;
                $ret['txt'] = $exportTxt;
            } else {
                $ret['res'] = false;

            }

            print_r(json_encode($ret));

        }
        break;
    //===========================================================================================================================================//
    //===================================================== CRYSTAL REPORT FILE CREATION ========================================================//
    //===========================================================================================================================================//
    case 'crystal-report-file-creation':
        {
            global $msg;                // notes
            global $debug_mode;            // debug
            global $rollback_info;        // rollback
            global $models_repository;    // model
            global $files_repository;    // files
            global $print_repository;    // stampe
            global $path_absolute;
            global $responseFile;

            $action = json_encode($_POST);
            logAction($action);

            ini_set('display_errors', false);
            error_reporting(E_ALL | E_STRICT);
            /*if (isset($sottocartella_xampp) && $sottocartella_xampp != '')
                $percorso = "C:\\" . $sottocartella_xampp . "\\htdocs\\" . $sottocartella_sito . "\\stampe\\";
            else
                $percorso = "C:\\xampp\\htdocs\\" . $sottocartella_sito . "\\stampe\\";*/

            $percorso = $path_absolute . "stampe\\";
            $percorsoFile = $path_absolute . str_replace('/', '\\', $files_repository) . "\\";                        //"C:\\xampp5\\htdocs\\ReMida\\stampe\\";
            $percorsoModelli = $path_absolute . str_replace('/', '\\', $models_repository) . "\\";                    //"C:\\xampp5\\htdocs\\ReMida\\stampe\\";

            $program = "crexport.exe";                                                // Nome del programma addetto alla chiamata del report su Crystal Report

            $query = 'SELECT nome FROM reports_models WHERE sezione = "' . db_input($_POST['spec']) . '"';
            $dettagli_report = mysql_fetch_array(db_query($query));
            $report = $dettagli_report['nome'];

            # REPORT GENERICO CON PARAMETRO ID PRATICA

            if ($_POST['spec'] == 'report') {
                $pdf = 'Report_' . date('Ymd_His') . '.pdf';

                if (is_numeric($_POST['p'])) {
                    $execScript = $percorso . $program . ' -F ' . $percorso . 'models\\' . $report . ' -O ' . $percorso . 'reports\\' . $pdf . ' -E pdf -a "Parametro:' . $_POST['p'] . '"';
                    $responsePath = 'stampe/reports/' . $pdf;

                    $response[] = json_decode(crystal_report_curl_call($execScript, $responsePath));
                } else {
                    $response = array(
                        'error' => 1,
                        'script_response' => 'IL PARAMETRO INSERITO NON &Egrave; VALIDO',
                        'file_path' => ''
                    );
                }
            }

            # 10.1 REPORT PER PRATICHE -> SCARICO LOTTO MANDANTE
            // La stampa deve essere lanciata dalla sezione [Pratiche Scaricate].
            // Il report deve generare un pdf per [Lotto Mandante] con le informazioni relative a tutte le righe ricercate con i filtri indicati.
            // I pdf, oltre al modello di report creato, deve riportare anche tutti i
            // documenti allegati alla [Pratica] che appartengono alle [Classi Allegato] che hanno il flag su [R.SCARICO].
            // L'allegato dovrebbe essere successivo alla sua relazione (relazione + allegato)
            else if ($_POST['spec'] == 'report-1') {
                require_once('assets/plugins/pdfmerger/PDFMerger.php');

                $response = array();

                $lotti = json_decode($_POST['lotti']);

                foreach ($lotti as $lotto => $csv_pratiche) {
                    if (preg_match('/^[0-9,]+$/i', $csv_pratiche) && is_numeric($lotto)) {
                        $pdf = 'RP_SCARICO_L' . $lotto . '_' . date('Ymd_His') . '.pdf';
                        $pdf_temp = $pdf . '_TEMP.pdf';
                        $pdf = $pdf . '.pdf';

                        $additionalParams = array(
                            'lotto' => $lotto
                        );


                        $view_query = "CREATE OR REPLACE VIEW `report1` AS   
                                        SELECT pratiche.id,
                                               pratiche.id_lotto_mandante,
                                               recapito_filiale.citta FilialeCitta,
                                               pratiche.riferimento_mandante_1,
                                               recapito_filiale.predefinito as FilialePredefinito,
                                               recapito.predefinito as RecapitoPredefinito,
                                               utente_debitore.cognome as DebCognome,
                                               utente_debitore.nome as DebNome,
                                               esiti_pratica.descrizione  as Esito,
                                               utente_mandante.nome as MandNome,
                                               utente_mandante.cognome as MandCognome,
                                               recapito.indirizzo as MandIndirizzo,
                                               recapito.cap as MandCap,
                                               recapito.citta as MandCitta,
                                               province.sigla as MandProv,
                                               pratiche.relazione_conclusiva,
                                               lotti_mandante.descrizione as LottoMandante,
                                               pratiche.data_affidamento,
                                               pratiche.data_fine_mandato,
                                               stati_pratiche.descrizione as Stato,
                                               pratiche.affidato_capitale,
                                               pratiche.affidato_spese,
                                               pratiche.affidato_interessi,
                                               pratiche.affidato_1,
                                               pratiche.affidato_2,
                                               pratiche.affidato_3
                                        FROM pratiche
                                                 INNER JOIN utente utente_debitore ON pratiche.id_debitore = utente_debitore.id_utente
                                        INNER JOIN utente utente_mandante ON pratiche.id_mandante = utente_mandante.id_utente
                                        LEFT OUTER JOIN esiti_pratica ON pratiche.esito_corrente = esiti_pratica.id
                                        LEFT OUTER JOIN lotti_mandante ON pratiche.id_lotto_mandante = lotti_mandante.id
                                        LEFT OUTER JOIN recapito_filiale recapito_filiale
                                                 LEFT OUTER JOIN filiali ON recapito_filiale.id_fililale = filiali.id
                                        ON pratiche.id_filiale_origine = filiali.id
                                        LEFT OUTER JOIN recapito recapito ON utente_mandante.id_utente = recapito.id_utente
                                        LEFT OUTER JOIN province province ON recapito.provincia = province.cod_provincia
                                        LEFT OUTER JOIN stati_pratiche ON pratiche.stato_corrente = stati_pratiche.id
                                        WHERE Pratiche.id in (" . trim($csv_pratiche, ',') . ") ORDER BY pratiche.id_lotto_mandante";

                        $wait_rnd = rand(100, 500) / 100;
                        sleep($wait_rnd);
                        $ris_query = db_query($view_query);


                        //$execScript = $percorso . $program . ' -F ' . $percorso . 'models\\' . $report . ' -O ' . $percorso . 'reports\\' . $pdf_temp . ' -E pdf -a "Lotto:' . $lotto . '" -a "Pratiche:' . trim($csv_pratiche, ',') . '"';

                        $execScript = $percorso . $program . ' -F ' . $percorso . 'models\\' . $report . ' -O ' . $percorso . 'reports\\' . $pdf_temp . ' -E pdf ';
                        $responsePath = 'reports/' . $pdf;

                        $response[] = json_decode(crystal_report_curl_call($execScript, $responsePath, $additionalParams));

                        $pratiche = explode(',', $csv_pratiche);

                        foreach ($pratiche as $pratica) {
                            if ($pratica != '') {
                                $query_elemento = 'SELECT A.id as id, data, nome, A.provenienza, A.riferimento, A.descrizione as descrizione, classe, tipologia, A.file as file
													FROM allegati A 
													LEFT JOIN composizione_allegati CA ON A.tipologia = CA.id
													WHERE A.riferimento = "' . db_input($pratica) . '" 
													AND A.provenienza = "pratica" 
													AND A.type = "pdf"
													AND CA.allegabile_relazione_scarico = 1
													ORDER BY id';
                                $ris_elemento = db_query($query_elemento);
                                if (mysql_num_rows($ris_elemento) > 0) {
                                    while ($elemento = mysql_fetch_array($ris_elemento)) {
                                        // CREARE IL FILE PDF DAL BASE64 CHE è SALVATO SUL DB
                                        // $pdf_allegato_decoded = base64_decode($elemento['file']);

                                        // DARWIN : GESTIONE FILE ON FS

                                        $pdf_allegato_decoded = allegati_getFile($elemento['nome'], $elemento['provenienza'], $elemento['riferimento']);

                                        // END

                                        $pdf_allegato = fopen('report/temp/' . $elemento['nome'], 'w');
                                        fwrite($pdf_allegato, $pdf_allegato_decoded);
                                        fclose($pdf_allegato);

                                        if (file_exists('stampe/reports/' . $pdf)) {
                                            unlink('stampe/reports/' . $pdf);
                                        }

                                        $unione_pdf = new PDFMerger;

                                        try {
                                            $file = $unione_pdf->addPDF('stampe/reports/' . $pdf_temp, 'all')
                                                ->addPDF('report/temp/' . $elemento['nome'], 'all')
                                                ->merge('file', 'stampe/reports/' . $pdf);
                                        } catch (Exception $e) {
                                        }
                                    }
                                } else {
                                    rename('stampe/reports/' . $pdf_temp, 'stampe/reports/' . $pdf);
                                }
                            }
                        }
                    } else {
                        $response[] = array(
                            'error' => 1,
                            'script_response' => 'IL PARAMETRO INSERITO NON &Egrave; VALIDO',
                            'lotto' => $lotto,
                            'file_path' => ''
                        );
                    }
                }
            }

            # 10.1 REPORT PER INCASSI E PDP -> CAMBIALI E BOLLETTINI -> GENERAZIONE RIGHE
            // La stampa deve essere lanciata dalla sezione [Generazione Righe].
            // Il report deve essere un unico pdf contenente le informazioni relative a tutte le righe ricercate con i filtri indicati (aggiungere filtri alla pagina).
            else if ($_POST['spec'] == 'report-2') {
                $response = array();

                $arrayPratiche = explode(',', $_POST['pratiche']);
                $arrayPratiche2 = array_unique($arrayPratiche, SORT_NUMERIC);

                $pratiche = implode(',', $arrayPratiche2);

                if (preg_match('/^[0-9,]+$/i', $pratiche) && trim($pratiche, ',') != '') {
                    $pdf = 'RI_CAMBIALI_' . date('Ymd_His') . '.pdf';

                    $execScript = $percorso . $program . ' -F ' . $percorso . 'models\\' . $report . ' -O ' . $percorso . 'reports\\' . $pdf . ' -E pdf -a "Pratiche:' . trim($pratiche, ',') . '" -a "Operatore:' . $_SESSION['user_admin_id'] . '"';
                    $responsePath = 'reports/' . $pdf;

                    $response[] = json_decode(crystal_report_curl_call($execScript, $responsePath));
                } else {
                    $response[] = array(
                        'error' => 1,
                        'script_response' => 'IL PARAMETRO INSERITO NON &Egrave; VALIDO',
                        'file_path' => ''
                    );
                }
            } else if ($_POST['spec'] == 'report-2bis') {
                $response = array();

                $arrayPratiche = explode(',', $_POST['pratiche']);
                $arrayPratiche2 = array_unique($arrayPratiche, SORT_NUMERIC);

                $pratiche = implode(',', $arrayPratiche2);

                $ometti1 = $_POST['ometti1'];
                $ometti2 = $_POST['ometti2'];
                $ometti3 = $_POST['ometti3'];
                $ometti4 = $_POST['ometti4'];
                $ometti5 = $_POST['ometti5'];
                $ometti6 = $_POST['ometti6'];
                $ometti7 = $_POST['ometti7'];
                $ometti8 = $_POST['ometti8'];
                $ometti9 = $_POST['ometti9'];
                $ometti10 = $_POST['ometti10'];
                $ometti11 = $_POST['ometti11'];
                $ometti12 = $_POST['ometti12'];
                $ometti13 = $_POST['ometti13'];
                $ometti14 = $_POST['ometti14'];
                $ometti15 = $_POST['ometti15'];
                $ometti16 = $_POST['ometti16'];
                $ometti17 = $_POST['ometti17'];
                $ometti18 = $_POST['ometti18'];
                $ometti19 = $_POST['ometti19'];

                if (preg_match('/^[0-9,]+$/i', $pratiche) && trim($pratiche, ',') != '') {
                    $pdf = 'RI_CAMBIALI_' . date('Ymd_His') . '.pdf';

                    $execScript = $percorso . $program . ' -F ' . $percorso . 'models\\' . $_POST['spec'] . '.rpt -O ' . $percorso . 'reports\\' . $pdf . ' -E pdf -a "Pratiche:' . trim($pratiche, ',') . '" -a "Operatore:' . trim($_SESSION['user_admin_id']) . '" -a "Cifra:' . trim($ometti1) . '" -a "Lettere:' . trim($ometti2) . '" -a "Beneficiario:' . trim($ometti3) . '" -a "Rif_Mandante:' . trim($ometti4) . '" -a "LuogoEmissione:' . trim($ometti5) . '" -a "DataEmissione:' . trim($ometti6) . '" -a "DataScadenza:' . trim($ometti7) . '" -a "Banca:' . trim($ometti8) . '" -a "Agenzia:' . trim($ometti9) . '" -a "BancaIndirizzo:' . trim($ometti10) . '" -a "AbiCab:' . trim($ometti11) . '" -a "Debitore:' . trim($ometti12) . '" -a "CodFiscale:' . trim($ometti13) . '" -a "DebitoreIndirizzo:' . trim($ometti14) . '" -a "Paghero:' . trim($ometti15) . '" -a "Margine:' . trim($ometti16) . '" -a "RiduciRagSoc:' . trim($ometti17) . '" -a "RiduciIndirizzo:' . trim($ometti18) . '" -a "RiduciLuogo:' . trim($ometti19) . '" -a "Operatore:' . $_SESSION['user_admin_id'] . '"';
                    $responsePath = 'reports/' . $pdf;

                    $response[] = json_decode(crystal_report_curl_call($execScript, $responsePath));
                } else {
                    $response[] = array(
                        'error' => 1,
                        'script_response' => 'IL PARAMETRO INSERITO NON &Egrave; VALIDO',
                        'file_path' => ''
                    );
                }
            }
            # 10.1 REPORT AREA COLLECTOR -> AFFIDAMENTI
            // La stampa deve essere lanciata dalla sezione [Ricerca Affidamenti].
            // Il report deve generare un pdf per [Id Affidamento] contenente le informazioni relative a tutte le righe ricercate con i filtri indicati.
            else if ($_POST['spec'] == 'report-3') {
                $response = array();

                $lotti = explode(',', $_POST['lotti']);

                foreach ($lotti as $lotto) {
                    if (is_numeric($lotto)) {
                        $pdf = 'RC_AFFIDAMENTI_L' . $lotto . '_' . date('Ymd_His') . '.pdf';

                        $additionalParams = array(
                            'lotto' => $lotto
                        );

                        $execScript = $percorso . $program . ' -F ' . $percorso . 'models\\' . $report . ' -O ' . $percorso . 'reports\\' . $pdf . ' -E pdf -a "Lotto:' . $lotto . '"';
                        $responsePath = 'reports/' . $pdf;

                        $response[] = json_decode(crystal_report_curl_call($execScript, $responsePath, $additionalParams));
                    } else {
                        $response[] = array(
                            'error' => 1,
                            'script_response' => 'IL PARAMETRO INSERITO NON &Egrave; VALIDO',
                            'file_path' => ''
                        );
                    }
                }
            } else if ($_POST['spec'] == 'report-3L') {
                $response = array();

                $lotti = explode(',', $_POST['lotti']);

                foreach ($lotti as $lotto) {
                    if (is_numeric($lotto)) {
                        $pdf = 'RC_AFFIDAMENTI_L' . $lotto . '_' . date('Ymd_His') . '.pdf';

                        $additionalParams = array(
                            'lotto' => $lotto
                        );

                        $execScript = $percorso . $program . ' -F ' . $percorso . 'models\\' . $report . ' -O ' . $percorso . 'reports\\' . $pdf . ' -E pdf -a "Lotto:' . $lotto . '"';
                        $responsePath = 'reports/' . $pdf;

                        $response[] = json_decode(crystal_report_curl_call($execScript, $responsePath, $additionalParams));
                    } else {
                        $response[] = array(
                            'error' => 1,
                            'script_response' => 'IL PARAMETRO INSERITO NON &Egrave; VALIDO',
                            'file_path' => ''
                        );
                    }
                }
            }

            # 10.1 REPORT AREA COLLECTOR -> RICEVUTE -> BOLLETTARI -> ELENCO BOLLETTARI ATTIVI
            // La stampa deve essere lanciata dalla sezione [Bollettari].
            // Il report deve generare un pdf per [Codice] bollettario contenente le informazioni relative a tutte le righe ricercate con i filtri indicati.

            else if ($_POST['spec'] == 'report-4') {
                $response = array();

                //$codici = trim($_POST['codici', ',');

                $codici = explode(',', trim($_POST['codici'], ','));

                foreach ($codici as $codice) {
                    if ($codice != '') {
                        if (strlen($codice) < 20) {
                            $pdf = 'RC_BOLLETTARI_L' . $codice . '_' . date('Ymd_His') . '.pdf';

                            $additionalParams = array(
                                'codice' => $codice
                            );

                            $execScript = $percorso . $program . ' -F ' . $percorso . 'models\\' . $report . ' -O ' . $percorso . 'reports\\' . $pdf . ' -E pdf -a "Codice:' . $codice . '"';
                            $responsePath = 'reports/' . $pdf;

                            $response[] = json_decode(crystal_report_curl_call($execScript, $responsePath, $additionalParams));
                        } else {
                            $response[] = array(
                                'error' => 1,
                                'script_response' => 'IL PARAMETRO INSERITO NON &Egrave; VALIDO',
                                'file_path' => ''
                            );
                        }
                    }
                }
            }

            # 10.1 AMMINISTRAZIONE -> COMPENSI -> COMPENSI MATURATI
            // Il report deve generare un pdf per anagrafica [Controparte] contenente le informazioni relative a tutte le righe ricercate con i filtri indicati.
            else if ($_POST['spec'] == 'report-5') {
                $response = array();

                $ids = $_POST['ids'];

                if (preg_match('/^[0-9,]+$/i', $ids) && trim($ids, ',') != '') {


                    $view_query = "CREATE OR REPLACE VIEW `report5` AS
                                    SELECT `calcolo_compenso`.`controparte`,
                                           `calcolo_compenso`.`codice_pratica`,
                                           `calcolo_compenso`.`id` AS id_calcolo,
                                           `calcolo_compenso`.`lotto`,
                                           `calcolo_compenso`.`tipo_compenso`,
                                           `calcolo_compenso`.`anticipo`,
                                           `calcolo_compenso`.`importo_incasso`,
                                           `calcolo_compenso`.`compenso_percepito`,
                                           `calcolo_compenso`.`storno_percepito`,
                                           `calcolo_compenso`.`data_competenza`,
                                           `calcolo_compenso`.`descrizione`,
                                           `pratiche`.`id` AS id_pratica,
                                           `pratiche`.`riferimento_mandante_1`,
                                           `pratiche`.`id_mandante`,
                                           `pratiche_recuperato`.`data_scadenza`,
										   `pratiche_recuperato`.`data_bf`,
                                           `pratiche_recuperato`.`importo`,
                                           `pratiche_recuperato`.`data_emissione`,
                                           `utente_debitore`.`cognome` AS cognome_debitore,
                                           `utente_debitore`.`nome` AS nome_debitore,
                                           `utente_beneficiario`.`id_utente`,
                                           `utente_beneficiario`.`cognome` AS cognome_beneficiario,
                                           `utente_beneficiario`.`nome` AS nome_beneficiario,
                                           `tipo_pagamento`.`descrizione_visibile`,
                                           `tipo_pagamento`.`intestata`,
                                           `lotti_mandante`.`descrizione` AS descrizione_lotto,
                                           `credito`.`descrizione` AS descrizione_credito,
                                           (`pratiche`.`recuperato_capitale` + `pratiche`.`recuperato_spese` + `pratiche`.`recuperato_interessi` + `pratiche`.`recuperato_affidato_1` + `pratiche`.`recuperato_affidato_2` + `pratiche`.`recuperato_affidato_3` + `pratiche`.`recuperato_oneri_recupero` + `pratiche`.`recuperato_spese_incasso` + `pratiche`.`recuperato_surplus`) AS `totale_recuperato`,
                                           `contratto`.`quote_azienda` AS `quote_azienda`,
                                           `pratiche`.`affidato_capitale` AS `affidato_capitale`,
                                           `pratiche`.`affidato_1` AS `affidato_1`,
                                           `pratiche`.`affidato_2` AS `affidato_2`,
                                           `pratiche`.`affidato_3` AS `affidato_3`,
                                           `pratiche`.`affidato_spese` AS `affidato_spese`,
                                           `pratiche`.`affidato_interessi` AS `affidato_interessi`, 
                                           SUBSTRING(calcolo_compenso.descrizione,   INSTR (calcolo_compenso.descrizione, 'CAPITALE E')+13, INSTR (calcolo_compenso.descrizione, '<BR>IL COMPENSO SUGLI INTERESSI')-INSTR (calcolo_compenso.descrizione, 'CAPITALE E')-13) as capitale_compenso ,
                                           SUBSTRING(calcolo_compenso.descrizione,   INSTR (calcolo_compenso.descrizione, 'INTERESSI E')+14, INSTR (calcolo_compenso.descrizione, '<BR>IL COMPENSO SULLE SPESE')-INSTR (calcolo_compenso.descrizione, 'INTERESSI E')-14) as interessi_compenso ,
                                           SUBSTRING(calcolo_compenso.descrizione,   INSTR (calcolo_compenso.descrizione, 'SPESE E')+10, INSTR (calcolo_compenso.descrizione, 'AFFIDATO1 E')-INSTR (calcolo_compenso.descrizione, 'SPESE E')-10-21) as spese_compenso ,
                                           SUBSTRING(calcolo_compenso.descrizione,   INSTR (calcolo_compenso.descrizione, 'AFFIDATO1 E')+14, INSTR (calcolo_compenso.descrizione, 'AFFIDATO2 E')-INSTR (calcolo_compenso.descrizione, 'AFFIDATO1 E')-14-21) as aff1_compenso 

                                    
                                    FROM calcolo_compenso `calcolo_compenso`
                                        LEFT JOIN `utente` `utente_beneficiario` ON `calcolo_compenso`.`id_rif`=`utente_beneficiario`.`id_utente`
                                        LEFT JOIN `pratiche_recuperato` `pratiche_recuperato` ON `calcolo_compenso`.`id_pratica_recuperata`=`pratiche_recuperato`.`id`
                                        LEFT JOIN `contratto_pagamento_previsto` `contratto_pagamento_previsto` ON `pratiche_recuperato`.`id_tipologia_pagamento`=`contratto_pagamento_previsto`.`id`
                                        LEFT JOIN `pratiche` `pratiche` ON `calcolo_compenso`.`codice_pratica`=`pratiche`.`id`
                                        LEFT JOIN `tipo_pagamento` `tipo_pagamento` ON `contratto_pagamento_previsto`.`tipo_pagamento`=`tipo_pagamento`.`id`
                                        LEFT JOIN `utente` `utente_debitore` ON `pratiche`.`id_debitore`=`utente_debitore`.`id_utente`
                                        LEFT JOIN `contratto` `contratto` ON `pratiche`.`id_contratto`=`contratto`.`id`
                                        LEFT JOIN `credito` `credito` ON `contratto`.`id_tipo_credito`=`credito`.`id`
                                        LEFT JOIN `lotti_mandante` `lotti_mandante` ON `pratiche`.`id_lotto_mandante`=`lotti_mandante`.`id`
                                    
                                    WHERE calcolo_compenso.id IN (" . trim($ids, ',') . ")
                                    ORDER BY `utente_beneficiario`.`id_utente`, `calcolo_compenso`.`lotto`";


                    if ($clienteGlobaleReMida == 'REGIE') {
                        $view_query = "CREATE OR REPLACE VIEW `report5` AS
                                    SELECT `calcolo_compenso`.`controparte`,
                                           `calcolo_compenso`.`codice_pratica`,
                                           `calcolo_compenso`.`id` AS id_calcolo,
                                           `calcolo_compenso`.`lotto`,
                                           `calcolo_compenso`.`tipo_compenso`,
                                           `calcolo_compenso`.`anticipo`,
                                           `calcolo_compenso`.`importo_incasso`,
                                           `calcolo_compenso`.`compenso_percepito`,
                                           `calcolo_compenso`.`storno_percepito`,
                                           `calcolo_compenso`.`data_competenza`,
                                           `calcolo_compenso`.`descrizione`,
                                           `pratiche`.`id` AS id_pratica,
                                           `pratiche`.`riferimento_mandante_1`,
                                           `pratiche`.`id_mandante`,
                                           `pratiche_recuperato`.`data_scadenza`,
                                           `pratiche_recuperato`.`importo`,
                                           `pratiche_recuperato`.`data_emissione`,
                                           `utente_debitore`.`cognome` AS cognome_debitore,
                                           `utente_debitore`.`nome` AS nome_debitore,
                                           `utente_beneficiario`.`id_utente`,
                                           `utente_beneficiario`.`cognome` AS cognome_beneficiario,
                                           `utente_beneficiario`.`nome` AS nome_beneficiario,
                                           `tipo_pagamento`.`descrizione_visibile`,
                                           `tipo_pagamento`.`intestata`,
                                           `lotti_mandante`.`descrizione` AS descrizione_lotto,
                                           `credito`.`descrizione` AS descrizione_credito,
                                           (`pratiche`.`recuperato_capitale` + `pratiche`.`recuperato_spese` + `pratiche`.`recuperato_interessi` + `pratiche`.`recuperato_affidato_1` + `pratiche`.`recuperato_affidato_2` + `pratiche`.`recuperato_affidato_3` + `pratiche`.`recuperato_oneri_recupero` + `pratiche`.`recuperato_spese_incasso` + `pratiche`.`recuperato_surplus`) AS `totale_recuperato`,
                                           if(`contratto`.`quote_azienda`='',0,`contratto`.`quote_azienda`) AS `quote_azienda`,
                                           `pratiche`.`affidato_capitale` AS `affidato_capitale`,
                                           `pratiche`.`affidato_1` AS `affidato_1`,
                                           `pratiche`.`affidato_2` AS `affidato_2`,
                                           `pratiche`.`affidato_3` AS `affidato_3`,
                                           `pratiche`.`affidato_spese` AS `affidato_spese`,
                                           `pratiche`.`affidato_interessi` AS `affidato_interessi`
                                   
                                    FROM   calcolo_compenso `calcolo_compenso`
                                        LEFT JOIN `utente` `utente_beneficiario` ON `calcolo_compenso`.`id_rif`=`utente_beneficiario`.`id_utente`
                                        LEFT JOIN `pratiche_recuperato` `pratiche_recuperato` ON `calcolo_compenso`.`id_pratica_recuperata`=`pratiche_recuperato`.`id`
                                        LEFT JOIN `contratto_pagamento_previsto` `contratto_pagamento_previsto` ON `pratiche_recuperato`.`id_tipologia_pagamento`=`contratto_pagamento_previsto`.`id`
                                        LEFT JOIN `pratiche` `pratiche` ON `calcolo_compenso`.`codice_pratica`=`pratiche`.`id`
                                        LEFT JOIN `tipo_pagamento` `tipo_pagamento` ON `contratto_pagamento_previsto`.`tipo_pagamento`=`tipo_pagamento`.`id`
                                        LEFT JOIN `utente` `utente_debitore` ON `pratiche`.`id_debitore`=`utente_debitore`.`id_utente`
                                        LEFT JOIN `contratto` `contratto` ON `pratiche`.`id_contratto`=`contratto`.`id`
                                        LEFT JOIN `credito` `credito` ON `contratto`.`id_tipo_credito`=`credito`.`id`
                                        LEFT JOIN `lotti_mandante` `lotti_mandante` ON `pratiche`.`id_lotto_mandante`=`lotti_mandante`.`id`
                                   
                                    WHERE calcolo_compenso.id IN (" . trim($ids, ',') . ")
                                    ORDER BY `utente_beneficiario`.`id_utente`, `calcolo_compenso`.`lotto`";

                    }

                    $wait_rnd = rand(100, 500) / 100;
                    sleep($wait_rnd);
                    $ris_query = db_query($view_query);

                    $pdf = 'RA_COMPENSI_L_' . date('Ymd_His') . '.pdf';

                    $execScript = $percorso . $program . ' -F ' . $percorso . 'models\\' . $report . ' -O ' . $percorso . 'reports\\' . $pdf . ' -E pdf';
                    $responsePath = 'reports/' . $pdf;

                    $zip = 'RA_COMPENSI_L_' . date('Ymd_His') . '.zip';

                    $responsePathZip = '/reports/' . $zip;

                    $zipPath = __DIR__ . '/stampe/reports/' . $zip;

                    $responseT = json_decode(crystal_report_curl_call($execScript, $responsePath));


                    if (isset($_POST['allega_documenti']) && $_POST['allega_documenti'] == 1) {
                        $queryGetAllegatiNote = "SELECT A.id                                                                   AS id,
                        A.type,
                        A.file,
                       A.nome as name, 
                       A.descrizione                                                          AS description,
                       IF(A.provenienza like 'pratica',CONCAT('" . addslashes($_SESSION["FILE_PATH"]) . "',A.provenienza,'/',P.id_mandante,'/',A.riferimento,'/',A.nome) , CONCAT('" . addslashes($_SESSION["FILE_PATH"]) . "',A.provenienza,'/',A.riferimento,'/',A.nome))                                                                           AS path
                        FROM calcolo_compenso CC  JOIN note_su_pratica NP ON NP.id=CC.id_nota JOIN pratiche P ON P.id=NP.id_pratica JOIN allegati A ON A.id=NP.id_allegato WHERE cc.id IN (" . trim($ids, ',') . ")";
                        $risAllegatiNote = db_query($queryGetAllegatiNote);
                        $allegatiNote = db_fetch_array_assoc($risAllegatiNote);

                        $zip = new ZipArchive();
                        if (file_exists($zipPath)) {
                            $zip->open($zipPath);
                        } else {
                            $zip->open($zipPath, ZIPARCHIVE::CREATE | ZipArchive::OVERWRITE);
                        }

                        $zip->addFile(realpath($percorso . 'reports\\' . $pdf), $pdf);


                        foreach ($allegatiNote as $allegato) {

                            $zip->addFile(realpath($allegato['path']), $allegato['name']);
                        }
                        $zip->close();
                        //chiudo il file zip e salvo tutte le modifiche fatte ad esso
                        chmod($zipPath, 0777);

                        $response[] = array(
                            'file_path' => $responsePathZip
                        );

                    } else {
                        $response[] = $responseT;
                    }
                } else {
                    $response[] = array(
                        'error' => 1,
                        'script_response' => 'IL PARAMETRO INSERITO NON &Egrave; VALIDO',
                        'file_path' => ''
                    );
                }
            } else if ($_POST['spec'] == 'report-5-xls') {
                $response = array();

                $ids = $_POST['ids'];

                if (preg_match('/^[0-9,]+$/i', $ids) && trim($ids, ',') != '') {
                    $query = "SELECT   `utente_beneficiario`.`cognome` AS cognome_beneficiario,
                                       `utente_beneficiario`.`nome` AS nome_beneficiario,
                                       SUM(`calcolo_compenso`.`compenso_percepito`-ABS(`calcolo_compenso`.`storno_percepito`)) compenso_percepito,
                                       SUM(`calcolo_compenso`.`compenso_percepito`) compensi,
                                       SUM(ABS(`calcolo_compenso`.`storno_percepito`)) storni,
                                       DATE_FORMAT(`calcolo_compenso`.`data_competenza`,'%m-%Y') competenza
                                    FROM calcolo_compenso `calcolo_compenso`
                                        LEFT JOIN `utente` `utente_beneficiario` ON `calcolo_compenso`.`id_rif`=`utente_beneficiario`.`id_utente`
                                        LEFT JOIN `pratiche_recuperato` `pratiche_recuperato` ON `calcolo_compenso`.`id_pratica_recuperata`=`pratiche_recuperato`.`id`
                                        LEFT JOIN `contratto_pagamento_previsto` `contratto_pagamento_previsto` ON `pratiche_recuperato`.`id_tipologia_pagamento`=`contratto_pagamento_previsto`.`id`
                                        LEFT JOIN `pratiche` `pratiche` ON `calcolo_compenso`.`codice_pratica`=`pratiche`.`id`
                                        LEFT JOIN `tipo_pagamento` `tipo_pagamento` ON `contratto_pagamento_previsto`.`tipo_pagamento`=`tipo_pagamento`.`id`
                                        LEFT JOIN `utente` `utente_debitore` ON `pratiche`.`id_debitore`=`utente_debitore`.`id_utente`
                                        LEFT JOIN `contratto` `contratto` ON `pratiche`.`id_contratto`=`contratto`.`id`
                                        LEFT JOIN `credito` `credito` ON `contratto`.`id_tipo_credito`=`credito`.`id`
                                        LEFT JOIN `lotti_mandante` `lotti_mandante` ON `pratiche`.`id_lotto_mandante`=`lotti_mandante`.`id`
                                    
                                    WHERE calcolo_compenso.id IN (" . trim($ids, ',') . ")
                                    GROUP BY `utente_beneficiario`.`id_utente`, DATE_FORMAT(`calcolo_compenso`.`data_competenza`,'%Y%m')
                                    ORDER BY DATE_FORMAT(`calcolo_compenso`.`data_competenza`,'%Y%m'), `utente_beneficiario`.`id_utente`, `calcolo_compenso`.`lotto`";

                    $compensi = db_fetch_array_assoc(db_query($query));

                    if (count($compensi) > 0) {

                        require_once(__DIR__ . '/assets/plugins/PHPExcel/Classes/PHPExcel.php');


                        $objPHPExcel = new PHPExcel();
                        $objPHPExcel->setActiveSheetIndex(0);

                        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'COMPETENZA');
                        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'BENEFICIARIO');
                        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'COMPENSI');
                        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'STORNI');
                        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'IMPORTO PERCEPITO');

                        $rowCount = 2;

                        foreach ($compensi as $compenso) {
                            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, trim(strtoupper($compenso['competenza'])));
                            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, strtoupper($compenso['cognome_beneficiario'] . ' ' . $compenso['nome_beneficiario']));
                            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, strtoupper($compenso['compensi']));
                            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, strtoupper($compenso['storni']));
                            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, strtoupper($compenso['compenso_percepito']));

                            $rowCount++;
                        }

                        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
                        $objWriter->save(__DIR__ . '/stampe/reports/Report_Compensi_' . date('YmdHis') . '.xlsx');

                        chmod(__DIR__ . '/stampe/reports/Report_Compensi_' . date('YmdHis') . '.xlsx', 0777);

                        $response[] = array(
                            'error' => 0,
                            'script_response' => 'Report Creato correttamente',
                            'file_path' => 'reports/Report_Compensi_' . date('YmdHis') . '.xlsx'
                        );
                    } else {
                        $response[] = array(
                            'error' => 1,
                            'script_response' => 'Nessun record presente',
                            'file_path' => ''
                        );
                    }
                } else {
                    $response[] = array(
                        'error' => 1,
                        'script_response' => 'IL PARAMETRO INSERITO NON &Egrave; VALIDO',
                        'file_path' => ''
                    );
                }
            }
            /*
            else if($_POST['spec'] == 'report-5'){
                $response = array ();

                    $ids = explode(',', $_POST['ids']);
                    $ids = $_POST['ids'];

                    if (preg_match('/^[0-9,]+$/i', $ids) && trim($ids, ',') != '') {
                        $pdf = 'RA_COMPENSI_L' . $codice . '_' . date('Ymd_His') . '.pdf';

                        //echo $percorso.$program.' -F '.$percorso.'models\\'.$report.' -O '.$percorso.'reports\\'.$pdf.' -E pdf -a "Ids:'.$ids.'"';

                        $idsArray = explode(',', $ids);
                        $idsChunkArray = array_chunk($idsArray, 900);

                        if (count($idsChunkArray) >= 1) $ids_1 = implode(',', $idsChunkArray[0]); else $ids_1 = '0';
                        if (count($idsChunkArray) >= 2) $ids_2 = implode(',', $idsChunkArray[1]); else $ids_2 = '0';
                        if (count($idsChunkArray) >= 3) $ids_3 = implode(',', $idsChunkArray[2]); else $ids_3 = '0';
                        if (count($idsChunkArray) >= 4) $ids_4 = implode(',', $idsChunkArray[3]); else $ids_4 = '0';
                        if (count($idsChunkArray) >= 5) $ids_5 = implode(',', $idsChunkArray[4]); else $ids_5 = '0';
                        if (count($idsChunkArray) >= 6) $ids_6 = implode(',', $idsChunkArray[5]); else $ids_6 = '0';
                        if (count($idsChunkArray) >= 7) $ids_7 = implode(',', $idsChunkArray[6]); else $ids_7 = '0';

                        $descriptorspec = array(
                            0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
                            1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
                            2 => array("pipe", "w")
                        );

                        $options = array('bypass_shell' => TRUE);

                        $cmd = $percorso . $program . ' -F ' . $percorso . 'models\\' . $report . ' -O ' . $percorso . 'reports\\' . $pdf . ' -E pdf -a "Ids1:' . $ids_1 . '" -a "Ids2:' . $ids_2 . '" -a "Ids3:' . $ids_3 . '" -a "Ids4:' . $ids_4 . '" -a "Ids5:' . $ids_5 . '" -a "Ids6:' . $ids_6 . '" -a "Ids7:' . $ids_7 . '"';

                        $process = proc_open($cmd, $descriptorspec, $pipes, NULL, NULL, $options);

                        if (is_resource($process)) {
                            $output = stream_get_contents($pipes[1]);

                            fclose($pipes[0]);
                            fclose($pipes[1]);
                            fclose($pipes[2]);

                            proc_close($process);
                        }

                        $response[] = array(
                            'error' => 0,
                            'script_response' => $output,
                            'file_path' => 'reports/' . $pdf
                        );
                }
                else {
                        $response[] = array(
                            'error' => 1,
                            'script_response' => 'IL PARAMETRO INSERITO NON &Egrave; VALIDO',
                            'file_path' => ''
                        );
                    }
            }
            */

            # 10.1 AMMINISTRAZIONE -> COMPENSI -> PREVISIONE COMPENSI
            else if ($_POST['spec'] == 'report-6') {
                $response = array();

                $ids = explode(',', $_POST['ids']);

                foreach ($ids as $id) {
                    if (is_numeric($id)) {
                        $pdf = 'RA_FATT_POT_' . $id . '_' . date('Ymd_His') . '.pdf';

                        $additionalParams = array(
                            'id' => $id
                        );

                        $execScript = $percorso . $program . ' -F ' . $percorso . 'models\\' . $report . ' -O ' . $percorso . 'reports\\' . $pdf . ' -E pdf -a "Ids:' . $id . '"';
                        $responsePath = 'reports/' . $pdf;

                        $response[] = json_decode(crystal_report_curl_call($execScript, $responsePath, $additionalParams));
                    } else if ($id != '') {
                        $response[] = array(
                            'error' => 1,
                            'script_response' => 'IL PARAMETRO INSERITO NON &Egrave; VALIDO',
                            'file_path' => ''
                        );
                    }
                }
            }

            # 10.1 AMMINISTRAZIONE -> DISTINTE DI VERSAMENTO
            // La stampa deve essere lanciata dalla sezione [Elenco Distinte].
            // Il report deve generare un pdf per [Id] distinta con le informazioni relative ai dettagli presenti in tutte le righe ricercate con i filtri indicati.
            else if ($_POST['spec'] == 'report-7') {
                $response = array();

                $distinte = explode(',', $_POST['distinte']);

                foreach ($distinte as $distinta) {
                    if (is_numeric($distinta)) {
                        $pdf = 'RA_DISTINTE_L' . $distinta . '_' . date('Ymd_His') . '.pdf';

                        $additionalParams = array(
                            'id' => $distinta
                        );

                        $execScript = $percorso . $program . ' -F ' . $percorso . 'models\\' . $report . ' -O ' . $percorso . 'reports\\' . $pdf . ' -E pdf -a "Distinte:' . $distinta . '"';
                        $responsePath = 'reports/' . $pdf;

                        $response[] = json_decode(crystal_report_curl_call($execScript, $responsePath, $additionalParams));
                    } else if ($distinta != '') {
                        $response[] = array(
                            'error' => 1,
                            'script_response' => 'IL PARAMETRO INSERITO NON &Egrave; VALIDO',
                            'file_path' => ''
                        );
                    }
                }
            }

            # 10.1 AMMINISTRAZIONE -> RIMESSE
            // La stampa deve essere lanciata dalla sezione [Elenco Rimesse].
            // Il report deve generare un pdf per [Id] rimessa con le informazioni relative ai dettagli presenti in tutte le righe ricercate con i filtri indicati.
            else if ($_POST['spec'] == 'report-8') {
                $response = array();

                $rimesse = explode(',', $_POST['rimesse']);

                foreach ($rimesse as $rimessa) {
                    if (is_numeric($rimessa)) {
                        $pdf = 'RA_RIMESSE_L' . $rimessa . '_' . date('Ymd_His') . '.pdf';

                        $additionalParams = array(
                            'id' => $rimessa
                        );

                        $execScript = $percorso . $program . ' -F ' . $percorso . 'models\\' . $report . ' -O ' . $percorso . 'reports\\' . $pdf . ' -E pdf -a "Rimessa:' . $rimessa . '"';
                        $responsePath = 'reports/' . $pdf;

                        $response[] = json_decode(crystal_report_curl_call($execScript, $responsePath, $additionalParams));
                    } else if ($rimessa != '') {
                        $response[] = array(
                            'error' => 1,
                            'script_response' => 'IL PARAMETRO INSERITO NON &Egrave; VALIDO',
                            'file_path' => ''
                        );
                    }
                }
            }

            # 10.1 AUI -> ATTIVITA'
            // La stampa deve essere lanciata da [Elenco Attivita AUI].
            // Il report deve essere un unico pdf contenente le informazioni relative a tutte le righe ricercate con i filtri indicati (aggiungere filtri alla pagina).
            else if ($_POST['spec'] == 'report-9') {
                $response = array();

                $ids = $_POST['ids'];

                if (preg_match('/^[0-9,]+$/i', $ids) && trim($ids, ',') != '') {

                    db_query('DROP TABLE IF EXISTS report9_8001;');
                    db_query('DROP TABLE IF EXISTS report9_8099;');
                    db_query('DROP TABLE IF EXISTS report9_due;');

                    $tblTemp1 = "create table report9_8001 as SELECT IdPratica, RQ_Pattuito, MIN(DataRegistrazione) as DataRegistrazione FROM aui_attivita WHERE RQ_Pattuito >0 AND A24_Causale=8001 GROUP BY Idpratica ORDER BY id";
                    db_query($tblTemp1);

                    $tblTemp1Index = "ALTER TABLE report9_8001 ADD INDEX (IdPratica)";
                    db_query($tblTemp1Index);


                    $tblTemp3 = "create table report9_8099 as SELECT IdPratica, RQ_Pattuito, MIN(DataRegistrazione) as DataRegistrazione FROM aui_attivita WHERE RQ_Pattuito >0 AND A24_Causale=8099 GROUP BY Idpratica ORDER BY id";
                    db_query($tblTemp3);
                    $tblTemp3Index = "ALTER TABLE report9_8099 ADD INDEX (IdPratica)";
                    db_query($tblTemp3Index);

                    $tblTemp2 = "create table report9_due as SELECT cc.codice_pratica   AS id_pratica,
                           SUM(cc.compenso_percepito) AS compenso_percepito
                    FROM calcolo_compenso cc
                             
                    WHERE cc.controparte LIKE '%azienda%'
                      AND cc.fatturata = 1
                    GROUP BY cc.codice_pratica";
                    db_query($tblTemp2);

                    $tblTemp2Index = "ALTER TABLE report9_due ADD INDEX (id_pratica)";
                    db_query($tblTemp2Index);

                    $view_query = "CREATE OR REPLACE VIEW `report9` AS  SELECT DISTINCT aus.IdUtente   AS id_rappleg,
                aa.DataOperazione           AS DataOperazione,
                aa.A03_Identificativo       AS A03_Identificativo,
                m.cognome                   AS MandanteCognome,
                m.nome                      AS MandanteNome,
                rm.indirizzo                AS MandanteIndizzo,
                rm.cap                      AS MandanteCap,
                rm.citta                    AS MandanteCitta,
                pm.sigla                    AS MandanteSigla,
                rm.predefinito              AS MandantePredefinto,
                aus.D11_RagioneSociale      AS D11_RagioneSociale,
                doc.descrizione             AS DocDescrizione,
                aus.D42_DocNumero           AS D42_DocNumero,
                aus.D43_DocRilascioDoc      AS D43_DocRilascioDoc,
                ep.descrizione              AS EsitoDescrizione,
                ep.tipo                     AS EsitoTipo,
                aa.RQ_Percepito             AS RQ_Percepito,
                aa2.RQ_Pattuito             AS RQ_Pattuito,
                ACM.ragione_sociale_collegato                   AS DebitoreCognome,
                ''                      AS DebitoreNome,
                if(`d`.`codice_fiscale` is null or `d`.`codice_fiscale`='', `d`.`partita_iva`,`d`.`codice_fiscale`) AS DebitoreCF,
                rd.indirizzo                AS DebitoreIndirizzo,
                rd.citta                    AS DebitoreCitta,
                rd.cap                      AS DebitoreCap,
                pd.sigla                    AS DebitoreSigla,
                rd.predefinito              AS DebitorePredefinito,
                aa.IdEsito                  AS IdEsito,
                f.descrizione               AS FilialeDescrizione,
                rf.indirizzo                AS FilialeIndirizzo,
                rf.citta                    AS FilialeCitta,
                rf.cap                      AS FilialeCap,
                pf.sigla                    AS FilialeSigla,
                rf.predefinito              AS FilialePredefinito,
                aa.id                       AS id,
                aa.A24_Causale              AS A24_CAUSale,
                ifnull(p.affidato_capitale,0) + ifnull(p.affidato_interessi,0) + ifnull(p.affidato_spese,0) + ifnull(p.affidato_1,0) +
                ifnull(p.affidato_2,0) + ifnull(p.affidato_3,0) AS Affidato_Totale,
                0   AS compenso_percepito,
                aa.IdRecuperato             AS IdRecuperato,
                aup.D11_RagioneSociale      AS PaganteRagSoc,
                aup.D14B_ResidenzaComune    AS PaganteCitta,
                aup.D15_ResidenzaIndirizzo  AS PaganteIndirizzo,
                aup.D16_ResidenzaCap        AS PaganteCap,
                aup.D17_CodiceFiscale       AS PaganteCF,
                dp.descrizione              AS PaganteTipoDoc,
                aup.D44_DocRilascioChi      AS PaganteEnte,
                aup.D42_DocNumero           AS PaganteDocNum,
                aup.D43_DocRilascioDoc      AS PaganteDataDoc,
                p.data_affidamento          AS DataAffido,
                p.data_fine_mandato         AS FineMandato,
				p.id 						AS id_pratica,
				p.riferimento_mandante_1 	AS riferimento_mandante1,
				cred.descrizione 			AS Creditore,
				aus.D44_DocRilascioChi      AS SoggettiEnte
FROM aui_attivita aa
        

         LEFT JOIN utente m ON aa.IdMandante = m.id_utente

         LEFT JOIN aui_soggetti aus ON aa.AnagraficaMandante = aus.IdUtente AND (aus.D54A_CodiceStato=1 OR aus.D54A_CodiceStato=0)

		LEFT JOIN report9_8001  aa2  ON aa.IdPratica = aa2.IdPratica AND aa2.DataRegistrazione=aa.DataRegistrazione
         

         LEFT JOIN aui_soggetti aup ON aa.AnagraficaPagante = aup.IdUtente AND (aup.D54A_CodiceStato=1 OR aup.D54A_CodiceStato=0)

         LEFT JOIN pratiche_recuperato pr ON aa.IdRecuperato = pr.id

         LEFT JOIN documento dp ON aup.D41_DocTipo = dp.codice_aui

         LEFT JOIN utente d ON aa.AnagraficaDebitore = d.id_utente
                  
         LEFT JOIN anagrafica_collegati_mandante ACM ON aa.AnagraficaDebitore = ACM.id_collegato_pratica AND aa.IdPratica=ACM.id_pratica

         LEFT JOIN esiti_pratica ep ON aa.IdEsito = ep.id
         
         LEFT JOIN filiali f ON aa.IdFiliale = f.id

         LEFT JOIN recapito_filiale rf ON f.id = rf.id_fililale AND rf.predefinito = 1

         LEFT JOIN province pf ON rf.provincia = pf.cod_provincia

         LEFT JOIN recapito rm ON m.id_utente = rm.id_utente  AND rm.predefinito = 1

         LEFT JOIN province pm ON rm.provincia = pm.cod_provincia

         LEFT JOIN documento doc ON aus.D41_DocTipo = doc.codice_aui

         LEFT JOIN recapito rd ON d.id_utente = rd.id_utente AND rd.predefinito = 1

         LEFT JOIN province pd ON rd.provincia = pd.cod_provincia

         LEFT JOIN pratiche p ON aa.IdPratica = p.id

         LEFT JOIN creditori cred on p.id_creditore = cred.id


WHERE AA.id IN (" . trim($ids, ',') . ")
   
  AND (aa.A24_Causale = '8001' AND (aa.A54A_CodiceStato=0 OR aa.A54A_CodiceStato=1))   
         
UNION 

SELECT DISTINCT aus.IdUtente                AS id_rappleg,
                aa.DataOperazione           AS DataOperazione,
                aa.A03_Identificativo       AS A03_Identificativo,
                m.cognome                   AS MandanteCognome,
                m.nome                      AS MandanteNome,
                rm.indirizzo                AS MandanteIndizzo,
                rm.cap                      AS MandanteCap,
                rm.citta                    AS MandanteCitta,
                pm.sigla                    AS MandanteSigla,
                rm.predefinito              AS MandantePredefinto,
                aus.D11_RagioneSociale      AS D11_RagioneSociale,
                doc.descrizione             AS DocDescrizione,
                aus.D42_DocNumero           AS D42_DocNumero,
                aus.D43_DocRilascioDoc      AS D43_DocRilascioDoc,
                ep.descrizione              AS EsitoDescrizione,
                ep.tipo                     AS EsitoTipo,
                aa.RQ_Percepito             AS RQ_Percepito,
                aa2.RQ_Pattuito             AS RQ_Pattuito,
                ACM.ragione_sociale_collegato                   AS DebitoreCognome,
                ''                      AS DebitoreNome,
                if(`d`.`codice_fiscale` is null or `d`.`codice_fiscale`='', `d`.`partita_iva`,`d`.`codice_fiscale`) AS DebitoreCF,
                rd.indirizzo                AS DebitoreIndirizzo,
                rd.citta                    AS DebitoreCitta,
                rd.cap                      AS DebitoreCap,
                pd.sigla                    AS DebitoreSigla,
                rd.predefinito              AS DebitorePredefinito,
                aa.IdEsito                  AS IdEsito,
                f.descrizione               AS FilialeDescrizione,
                rf.indirizzo                AS FilialeIndirizzo,
                rf.citta                    AS FilialeCitta,
                rf.cap                      AS FilialeCap,
                pf.sigla                    AS FilialeSigla,
                rf.predefinito              AS FilialePredefinito,
                aa.id                       AS id,
                aa.A24_Causale              AS A24_CAUSale,
                ifnull(p.affidato_capitale,0) + ifnull(p.affidato_interessi,0) + ifnull(p.affidato_spese,0) + ifnull(p.affidato_1,0) +
                ifnull(p.affidato_2,0) + ifnull(p.affidato_3,0) AS Affidato_Totale,
                (select compenso_percepito  from report9_due where id_pratica=p.id)   AS compenso_percepito,
                aa.IdRecuperato             AS IdRecuperato,
                aup.D11_RagioneSociale      AS PaganteRagSoc,
                aup.D14B_ResidenzaComune    AS PaganteCitta,
                aup.D15_ResidenzaIndirizzo  AS PaganteIndirizzo,
                aup.D16_ResidenzaCap        AS PaganteCap,
                aup.D17_CodiceFiscale       AS PaganteCF,
                dp.descrizione              AS PaganteTipoDoc,
                aup.D44_DocRilascioChi      AS PaganteEnte,
                aup.D42_DocNumero           AS PaganteDocNum,
                aup.D43_DocRilascioDoc      AS PaganteDataDoc,
                p.data_affidamento 			AS DataAffido,
                p.data_fine_mandato 		AS FineMandato,
				p.id 						AS id_pratica,
				p.riferimento_mandante_1 	AS riferimento_mandante1,
				cred.descrizione 			AS Creditore,
				aus.D44_DocRilascioChi      AS SoggettiEnte
FROM aui_attivita aa
		LEFT JOIN utente m ON aa.IdMandante = m.id_utente
		LEFT JOIN aui_soggetti aus ON aa.AnagraficaMandante = aus.IdUtente AND (aus.D54A_CodiceStato=1 OR aus.D54A_CodiceStato=0)
		LEFT JOIN report9_8099 aa2  ON aa.IdPratica = aa2.IdPratica AND aa2.DataRegistrazione=aa.DataRegistrazione  
		LEFT JOIN aui_soggetti aup ON aa.AnagraficaPagante = aup.IdUtente AND (aup.D54A_CodiceStato=1 OR aup.D54A_CodiceStato=0)

         LEFT JOIN pratiche_recuperato pr ON aa.IdRecuperato = pr.id

         LEFT JOIN documento dp ON aup.D41_DocTipo = dp.codice_aui

         LEFT JOIN utente d ON aa.AnagraficaDebitore = d.id_utente
         
         LEFT JOIN anagrafica_collegati_mandante ACM ON aa.AnagraficaDebitore = ACM.id_collegato_pratica AND aa.IdPratica=ACM.id_pratica


         LEFT JOIN esiti_pratica ep ON aa.IdEsito = ep.id
         
         LEFT JOIN filiali f ON aa.IdFiliale = f.id

         LEFT JOIN recapito_filiale rf ON f.id = rf.id_fililale AND rf.predefinito = 1

         LEFT JOIN province pf ON rf.provincia = pf.cod_provincia

         LEFT JOIN recapito rm ON m.id_utente = rm.id_utente  AND rm.predefinito = 1

         LEFT JOIN province pm ON rm.provincia = pm.cod_provincia

         LEFT JOIN documento doc ON aus.D41_DocTipo = doc.codice_aui

         LEFT JOIN recapito rd ON d.id_utente = rd.id_utente AND rd.predefinito = 1

         LEFT JOIN province pd ON rd.provincia = pd.cod_provincia

         LEFT JOIN pratiche p ON aa.IdPratica = p.id
		 
		 LEFT JOIN creditori cred on p.id_creditore = cred.id
         
WHERE AA.id IN (" . trim($ids, ',') . ")
  AND (aa.A24_Causale = '8099' AND (aa.A54A_CodiceStato=0 OR aa.A54A_CodiceStato=1))";

                    if ($clienteGlobaleReMida == "2B") {

                        $view_query = "CREATE OR REPLACE VIEW `report9` AS  SELECT DISTINCT aus.IdUtente   AS id_rappleg,
                aa.DataOperazione           AS DataOperazione,
                aa.A03_Identificativo       AS A03_Identificativo,
                m.cognome                   AS MandanteCognome,
                m.nome                      AS MandanteNome,
                rm.indirizzo                AS MandanteIndizzo,
                rm.cap                      AS MandanteCap,
                rm.citta                    AS MandanteCitta,
                pm.sigla                    AS MandanteSigla,
                rm.predefinito              AS MandantePredefinto,
                aus.D11_RagioneSociale      AS D11_RagioneSociale,
                doc.descrizione             AS DocDescrizione,
                aus.D42_DocNumero           AS D42_DocNumero,
                aus.D43_DocRilascioDoc      AS D43_DocRilascioDoc,
                ep.descrizione              AS EsitoDescrizione,
                ep.tipo                     AS EsitoTipo,
                aa.RQ_Percepito             AS RQ_Percepito,
                aa2.RQ_Pattuito             AS RQ_Pattuito,
                ACM.ragione_sociale_collegato                   AS DebitoreCognome,
                ''                      AS DebitoreNome,
                IFNULL(d.codice_fiscale,d.partita_iva)              AS DebitoreCF,
                rd.indirizzo                AS DebitoreIndirizzo,
                rd.citta                    AS DebitoreCitta,
                rd.cap                      AS DebitoreCap,
                pd.sigla                    AS DebitoreSigla,
                rd.predefinito              AS DebitorePredefinito,
                aa.IdEsito                  AS IdEsito,
                f.descrizione               AS FilialeDescrizione,
                rf.indirizzo                AS FilialeIndirizzo,
                rf.citta                    AS FilialeCitta,
                rf.cap                      AS FilialeCap,
                pf.sigla                    AS FilialeSigla,
                rf.predefinito              AS FilialePredefinito,
                aa.id                       AS id,
                aa.A24_Causale              AS A24_CAUSale,
                p.affidato_capitale + p.affidato_interessi + p.affidato_spese + p.affidato_1 +
                p.affidato_2 + p.affidato_3 AS Affidato_Totale,
                cp.compenso_percepito       AS compenso_percepito,
                aa.IdRecuperato             AS IdRecuperato,
                aup.D11_RagioneSociale      AS PaganteRagSoc,
                aup.D14B_ResidenzaComune    AS PaganteCitta,
                aup.D15_ResidenzaIndirizzo  AS PaganteIndirizzo,
                aup.D16_ResidenzaCap        AS PaganteCap,
                aup.D17_CodiceFiscale       AS PaganteCF,
                dp.descrizione              AS PaganteTipoDoc,
                aup.D44_DocRilascioChi      AS PaganteEnte,
                aup.D42_DocNumero           AS PaganteDocNum,
                aup.D43_DocRilascioDoc      AS PaganteDataDoc
FROM aui_attivita aa
        

         LEFT JOIN utente m ON aa.IdMandante = m.id_utente

         LEFT JOIN aui_soggetti aus ON aa.AnagraficaMandante = aus.IdUtente AND (aus.D54A_CodiceStato=1 OR aus.D54A_CodiceStato=0)

		LEFT JOIN report9_8001  aa2  ON aa.IdPratica = aa2.IdPratica AND aa2.DataRegistrazione=aa.DataRegistrazione
         

         LEFT JOIN aui_soggetti aup ON aa.AnagraficaPagante = aup.IdUtente AND (aup.D54A_CodiceStato=1 OR aup.D54A_CodiceStato=0)

         LEFT JOIN pratiche_recuperato pr ON aa.IdRecuperato = pr.id

         LEFT JOIN documento dp ON aup.D41_DocTipo = dp.codice_aui

         LEFT JOIN utente d ON aa.AnagraficaDebitore = d.id_utente
        
         LEFT JOIN anagrafica_collegati_mandante ACM ON aa.AnagraficaDebitore = ACM.id_collegato_pratica AND aa.IdPratica=ACM.id_pratica

         LEFT JOIN esiti_pratica ep ON aa.IdEsito = ep.id
         
         LEFT JOIN filiali f ON aa.IdFiliale = f.id

         LEFT JOIN recapito_filiale rf ON f.id = rf.id_fililale AND rf.predefinito = 1

         LEFT JOIN province pf ON rf.provincia = pf.cod_provincia

         LEFT JOIN recapito rm ON m.id_utente = rm.id_utente  AND rm.predefinito = 1

         LEFT JOIN province pm ON rm.provincia = pm.cod_provincia

         LEFT JOIN documento doc ON aus.D41_DocTipo = doc.codice_aui

         LEFT JOIN recapito rd ON d.id_utente = rd.id_utente AND rd.predefinito = 1

         LEFT JOIN province pd ON rd.provincia = pd.cod_provincia

         LEFT JOIN pratiche p ON aa.IdPratica = p.id

         LEFT JOIN report9_due cp ON aa.IdPratica = cp.id_pratica


WHERE AA.id IN (" . trim($ids, ',') . ")
   
  AND (aa.A24_Causale = '8001' AND (aa.A54A_CodiceStato=0 OR aa.A54A_CodiceStato=1))   
         
UNION 

SELECT DISTINCT aus.IdUtente                AS id_rappleg,
                aa.DataOperazione           AS DataOperazione,
                aa.A03_Identificativo       AS A03_Identificativo,
                m.cognome                   AS MandanteCognome,
                m.nome                      AS MandanteNome,
                rm.indirizzo                AS MandanteIndizzo,
                rm.cap                      AS MandanteCap,
                rm.citta                    AS MandanteCitta,
                pm.sigla                    AS MandanteSigla,
                rm.predefinito              AS MandantePredefinto,
                aus.D11_RagioneSociale      AS D11_RagioneSociale,
                doc.descrizione             AS DocDescrizione,
                aus.D42_DocNumero           AS D42_DocNumero,
                aus.D43_DocRilascioDoc      AS D43_DocRilascioDoc,
                ep.descrizione              AS EsitoDescrizione,
                ep.tipo                     AS EsitoTipo,
                aa.RQ_Percepito             AS RQ_Percepito,
                aa2.RQ_Pattuito             AS RQ_Pattuito,
                ACM.ragione_sociale_collegato                   AS DebitoreCognome,
                ''                      AS DebitoreNome,
                IFNULL(d.codice_fiscale,d.partita_iva)              AS DebitoreCF,
                rd.indirizzo                AS DebitoreIndirizzo,
                rd.citta                    AS DebitoreCitta,
                rd.cap                      AS DebitoreCap,
                pd.sigla                    AS DebitoreSigla,
                rd.predefinito              AS DebitorePredefinito,
                aa.IdEsito                  AS IdEsito,
                f.descrizione               AS FilialeDescrizione,
                rf.indirizzo                AS FilialeIndirizzo,
                rf.citta                    AS FilialeCitta,
                rf.cap                      AS FilialeCap,
                pf.sigla                    AS FilialeSigla,
                rf.predefinito              AS FilialePredefinito,
                aa.id                       AS id,
                aa.A24_Causale              AS A24_CAUSale,
                p.affidato_capitale + p.affidato_interessi + p.affidato_spese + p.affidato_1 +
                p.affidato_2 + p.affidato_3 AS Affidato_Totale,
                cp.compenso_percepito       AS compenso_percepito,
                aa.IdRecuperato             AS IdRecuperato,
                aup.D11_RagioneSociale      AS PaganteRagSoc,
                aup.D14B_ResidenzaComune    AS PaganteCitta,
                aup.D15_ResidenzaIndirizzo  AS PaganteIndirizzo,
                aup.D16_ResidenzaCap        AS PaganteCap,
                aup.D17_CodiceFiscale       AS PaganteCF,
                dp.descrizione              AS PaganteTipoDoc,
                aup.D44_DocRilascioChi      AS PaganteEnte,
                aup.D42_DocNumero           AS PaganteDocNum,
                aup.D43_DocRilascioDoc      AS PaganteDataDoc
FROM aui_attivita aa		
		left join utente m on aa.IdMandante = m.id_utente
         left join aui_soggetti aus on aa.AnagraficaMandante = aus.IdUtente AND (aus.D54A_CodiceStato=1 OR aus.D54A_CodiceStato=0)
         LEFT join report9_8099 aa2 on aa.IdPratica = aa2.IdPratica and aa2.DataRegistrazione = aa.DataRegistrazione
         left join (select aa3.AnagraficaPagante, aa3.IdPratica
                    from aui_attivita aa3
                    where aa3.A24_Causale = '8002'
                    group by aa3.IdPratica
                    order by aa3.id desc) dp on dp.IdPratica=aa.IdPratica
                   
                   
         left join aui_soggetti aup on dp.AnagraficaPagante = aup.IdUtente AND (aup.D54A_CodiceStato=1 OR aup.D54A_CodiceStato=0)
		
         LEFT JOIN pratiche_recuperato pr ON aa.IdRecuperato = pr.id

         LEFT JOIN documento dp ON aup.D41_DocTipo = dp.codice_aui

         LEFT JOIN utente d ON aa.AnagraficaDebitore = d.id_utente
                  
         LEFT JOIN anagrafica_collegati_mandante ACM ON aa.AnagraficaDebitore = ACM.id_collegato_pratica AND aa.IdPratica=ACM.id_pratica

         LEFT JOIN esiti_pratica ep ON aa.IdEsito = ep.id
         
         LEFT JOIN filiali f ON aa.IdFiliale = f.id

         LEFT JOIN recapito_filiale rf ON f.id = rf.id_fililale AND rf.predefinito = 1

         LEFT JOIN province pf ON rf.provincia = pf.cod_provincia

         LEFT JOIN recapito rm ON m.id_utente = rm.id_utente  AND rm.predefinito = 1

         LEFT JOIN province pm ON rm.provincia = pm.cod_provincia

         LEFT JOIN documento doc ON aus.D41_DocTipo = doc.codice_aui

         LEFT JOIN recapito rd ON d.id_utente = rd.id_utente AND rd.predefinito = 1

         LEFT JOIN province pd ON rd.provincia = pd.cod_provincia

         LEFT JOIN pratiche p ON aa.IdPratica = p.id

         LEFT JOIN report9_due cp ON aa.IdPratica = cp.id_pratica
WHERE AA.id IN (" . trim($ids, ',') . ")
  AND (aa.A24_Causale = '8099' AND (aa.A54A_CodiceStato=0 OR aa.A54A_CodiceStato=1))";


                    }


                    $wait_rnd = rand(100, 500) / 100;
                    sleep($wait_rnd);
                    $ris_query = db_query($view_query);

                    $pdf = 'AUI_' . date('Ymd_His') . '.pdf';

                    $execScript = $percorso . $program . ' -F ' . $percorso . 'models\\' . $report . ' -O ' . $percorso . 'reports\\' . $pdf . ' -E pdf';
                    $responsePath = 'reports/' . $pdf;

                    $response[] = json_decode(crystal_report_curl_call($execScript, $responsePath));
                } else {
                    $response[] = array(
                        'error' => 1,
                        'script_response' => 'IL PARAMETRO INSERITO NON &Egrave; VALIDO',
                        'file_path' => ''
                    );
                }
            }

            # 10.1 AUI -> SOGGETTI
            // La stampa deve essere lanciata da [Elenco Soggetti AUI].
            // Il report deve essere un unico pdf contenente le informazioni relative a tutte le righe ricercate con i filtri indicati (aggiungere filtri alla pagina).
            else if ($_POST['spec'] == 'report-10') {
                $response = array();

                $ids = $_POST['ids'];

                if (preg_match('/^[0-9,]+$/i', $ids) && trim($ids, ',') != '') {
                    $pdf = 'RAUI_SOGGETTI_L_' . date('Ymd_His') . '.pdf';

                    $execScript = $percorso . $program . ' -F ' . $percorso . 'models\\' . $report . ' -O ' . $percorso . 'reports\\' . $pdf . ' -E pdf -a "Ids:' . $ids . '"';
                    $responsePath = 'reports/' . $pdf;

                    $response[] = json_decode(crystal_report_curl_call($execScript, $responsePath));
                } else {
                    $response[] = array(
                        'error' => 1,
                        'script_response' => 'IL PARAMETRO INSERITO NON &Egrave; VALIDO',
                        'file_path' => ''
                    );
                }
            } # 10.1 AREA MANAGER -> KPI
            else if ($_POST['spec'] == 'report-11') { // area-manager -> KPI
            } # 10.1 AREA MANAGER -> REDDITIVITA' MANDATO
            else if ($_POST['spec'] == 'report-12') { // area-manager -> redditività-mandato
            } // TRELLO #00082
            else if ($_POST['spec'] == 'report-13') {
                $response = array();

                $lotto = $_POST['lotto'];

                if (is_numeric($lotto)) {
                    $pdf = 'RA_LOTTO_MANDANTE_L' . $lotto . '_' . date('Ymd_His') . '.pdf';

                    $execScript = $percorso . $program . ' -F ' . $percorso . 'models\\' . $report . ' -O ' . $percorso . 'reports\\' . $pdf . ' -E pdf -a "Lotto:' . $lotto . '"';
                    $responsePath = 'reports/' . $pdf;

                    $response[] = json_decode(crystal_report_curl_call($execScript, $responsePath));
                } else if ($rimessa != '') {
                    $response[] = array(
                        'error' => 1,
                        'script_response' => 'IL PARAMETRO INSERITO NON &Egrave; VALIDO',
                        'file_path' => ''
                    );
                }
            }

            print_r(json_encode($response));
        }
        break;
    case 'salva-report-background':
        {
            if (isset($_POST['ids']) && $_POST['ids'] != "") {
                $ids = $_POST['ids'];
                $query = 'INSERT INTO report_compensi_background (ids,allega_documenti) VALUES ( "' . db_input($ids) . '","' . db_input($_POST['allega_documenti']) . '")';
                if (db_query($query)) {
                    echo 1;
                } else {
                    echo 0;
                }
            } else {
                echo "PARAMETRI MANCANTI";
                die();
            }
        }
        break;
    case 'visualizza-report-background':
        {
            $reponse = [];
            $percorso_file = "C:\\xampp\\htdocs\\" . $sottocartella_sito . "\\stampe\\reports\\";
            $array = [];
            for ($i = 0; $i >= -7; $i--) {
                $percorso_completo = $percorso_file . date('Ymd', strtotime($i . ' days'));
                if (is_dir($percorso_completo)) {
                    $nome_zip = "Report_" . date('Ymd', strtotime($i . ' days')) . ".zip";
                    $rootPath = realpath($percorso_completo);
                    $zip = new ZipArchive();
                    $zip->open($percorso_file . $nome_zip, ZipArchive::CREATE | ZipArchive::OVERWRITE);
                    $files = new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($rootPath),
                        RecursiveIteratorIterator::LEAVES_ONLY
                    );
                    foreach ($files as $name => $file) {
                        if (!$file->isDir()) {
                            $filePath = $file->getRealPath();
                            $relativePath = substr($filePath, strlen($rootPath) + 1);

                            $zip->addFile($filePath, $relativePath);
                        }
                    }
                    $zip->close();
                    $response[] = array(
                        'error' => 0,
                        'file_path' => 'reports/' . $nome_zip,
                        'date' => date('d-m-Y', strtotime($i . ' days'))
                    );

                }
            }

            print_r(json_encode($response));
        }
        break;
    //===========================================================================================================================================//
    //=================================================== SALVATAGGIO FILTRI TABELLE AJAX =======================================================//
    //===========================================================================================================================================//
    case 'save-ajax-tables-filter':
        {
            $url = $_POST['url'];
            $name = $_POST['n'];
            $value = $_POST['v'];
            $label = $_POST['l'];
            $type = $_POST['t'];

            if ($url != '') {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION[$url][] = array(
                    'name' => $name,
                    'value' => $value,
                    'label' => $label,
                    'type' => $type
                );
                session_write_close();
            }

            print_r('FILTRO SALVATO CORRETTAMENTE');
        }
        break;
    case 'remove-ajax-tables-filter':
        {
            $url = $_POST['url'];

            writeSession($url, array());

            print_r('FILTRO RESETTATO CORRETTAMENTE');
        }
        break;
    case 'recover-ajax-tables-filter':
        {
            $url = $_POST['url'];

            print_r(json_encode($_SESSION[$url]));
        }
        break;
    //===========================================================================================================================================//
    //================================================= AGGIORNAMENTO COMPENSI DA PREFATTURE ====================================================//
    //===========================================================================================================================================//
    case 'compensi-assegna-fattura':
        {
            $compensi = explode(',', $_POST['c']);
            $numero_fattura = $_POST['n'];
            $data_fattura = date('Y-m-d', strtotime($_POST['d']));

            $query = 'UPDATE calcolo_compenso
					SET fatturata = 1,
						data_fattura = "' . db_input($data_fattura) . '",
						numero_fattura = "' . db_input($numero_fattura) . '"
					WHERE id IN (' . db_input($_POST['c']) . ')';
            $ris = db_query($query);

            echo cleanInput($_POST['c']);
        }
        break;
    case 'compensi-assegna-importi':
        {
            $compenso = $_POST['c'];
            $importo_riconosciuto = $_POST['ir'];
            $importo_stornato = str_replace('-', '', $_POST['is']);

            $query = 'UPDATE calcolo_compenso
					SET compenso_percepito = "' . db_input($importo_riconosciuto) . '",
						storno_percepito = "' . db_input($importo_stornato) . '"
					WHERE id = ' . db_input($compenso);
            $ris = db_query($query);

            echo 'COMPENSO ' . $compenso;
        }
        break;
    //===========================================================================================================================================//
    //======================================================= RICERCA ZONE GEOGRAFICHE ==========================================================//
    //===========================================================================================================================================//
    case 'ricerca-cap-pagina-citta':
        {
            $cards = array();

            $query1 = 'SELECT cod_istat, cap
					FROM cap
					WHERE cap LIKE "%' . db_input($_POST['query']) . '%"';
            //die($query);
            $ris = db_query($query1);
            while ($row = mysql_fetch_array($ris)) {
                $cards[] = array(
                    'id' => $row['cod_istat'],
                    'text' => $row['cap']
                );
            }

            print_r(json_encode($cards));
        }
        break;
    case 'ricerca-provincia-pagina-provincia':
        {
            $cards = array();

            $query1 = 'SELECT cod_provincia, provincia
					FROM province
					WHERE provincia LIKE "%' . db_input($_POST['query']) . '%"';
            //die($query);
            $ris = db_query($query1);
            while ($row = mysql_fetch_array($ris)) {
                $cards[] = array(
                    'id' => $row['cod_provincia'],
                    'text' => $row['provincia']
                );
            }

            print_r(json_encode($cards));
        }
        break;
    case 'ricerca-regione-pagina-citta':
        {
            $cards = array();

            $query1 = 'SELECT cod_regione, regione
					FROM regioni
					WHERE regione LIKE "%' . db_input($_POST['query']) . '%"';
            //die($query);
            $ris = db_query($query1);
            while ($row = mysql_fetch_array($ris)) {
                $cards[] = array(
                    'id' => $row['cod_regione'],
                    'text' => ucwords($row['regione'])
                );
            }

            print_r(json_encode($cards));
        }
        break;
    //===========================================================================================================================================//
    //================================================================= VARIE ===================================================================//
    //===========================================================================================================================================//
    case 'get-contratto-durata-mandato':
        {
            $cards = array();

            $query1 = 'SELECT durata_mandato
					FROM contratto
					WHERE id = ' . db_input($_POST['idContratto']);
            //die($query);
            $ris = db_query($query1);
            $row = mysql_fetch_array($ris);

            echo date('d-m-Y', strtotime('+' . $row['durata_mandato'] . ' days'));
        }
        break;
    case 'set-lotto-scaricato':
        {
            echo 'BEGIN AJAX SERVER ACTION';

            $query = "UPDATE lotti_mandante 
					SET scaricato='1',
						data_scarico_lotto_mandante = '" . date('Y-m-d H:i:s') . "'
					WHERE id = " . $_POST['idLotto'];
            db_query($query);

            echo 'END AJAX SERVER ACTION';
        }
        break;
    case 'get-evento-scarico-lotto':
        {
            $query = "SELECT evento_strutturato
					FROM pratiche P
						LEFT JOIN iter_lavoro I ON I.id = P.id_iter
						LEFT JOIN eventi_strutturati E ON E.id = I.evento_scarico_mandato
					WHERE P.id = " . $_POST['idPratica'];
            $ris = db_query($query);
            $evento = mysql_fetch_array($ris);
            echo $evento['evento_strutturato'];
        }
        break;
    case 'richiesta-informazioni':
        {
            switch ($_POST['dettaglio']) {
                case 'nome-utente':
                    {
                        echo getUserNameAndSurname($_POST['id']);
                    }
                    break;
                case 'id-controparte-collector':
                    {
                        if ($_POST['id_recuperato'] > 0) {
                            $query = "SELECT id_collector
								FROM pratiche_recuperato PR
									LEFT JOIN utente U ON U.id_utente = PR.id_collector
								WHERE id = '" . $_POST['id_recuperato'] . "'";
                            $collector = mysql_fetch_assoc(db_query($query));

                            echo $collector['id_collector'];
                        } else
                            echo cleanInput($_POST['id_collector']);
                    }
                    break;
                case 'nome-collector':
                    {
                        if ($_POST['id_recuperato'] > 0) {
                            $query = "SELECT CONCAT(IFNULL(nome,\"\"),' ',IFNULL(cognome,\"\")) AS nome, gruppi_base
								FROM pratiche_recuperato PR
									LEFT JOIN utente U ON U.id_utente = PR.id_collector
								WHERE id = '" . $_POST['id_recuperato'] . "'";
                            $collector = mysql_fetch_assoc(db_query($query));

                            if ($collector['gruppi_base'] == 3 || $collector['gruppi_base'] == 12)
                                echo 'PHC - ' . $collector['nome'];
                            else if ($collector['gruppi_base'] == 6 || $collector['gruppi_base'] == 7)
                                echo 'ESA - ' . $collector['nome'];
                            else
                                echo $collector['nome'];
                        } else if ($_POST['id_collector'] > 0) {
                            $query = "SELECT CONCAT(IFNULL(nome,\"\"),' ',IFNULL(cognome,\"\")) AS nome, gruppi_base
								FROM utente
								WHERE id_utente = '" . $_POST['id_collector'] . "'";
                            $collector = mysql_fetch_assoc(db_query($query));

                            if ($collector['gruppi_base'] == 3 || $collector['gruppi_base'] == 12)
                                echo 'PHC - ' . $collector['nome'];
                            else if ($collector['gruppi_base'] == 6 || $collector['gruppi_base'] == 7)
                                echo 'ESA - ' . $collector['nome'];
                            else
                                echo $collector['nome'];
                        }
                    }
                    break;
                case 'nome-collector-rif':
                    {
                        $query = "SELECT CONCAT(IFNULL(nome,\"\"),' ',IFNULL(cognome,\"\")) AS nome, gruppi_base
							FROM utente U
							WHERE id_utente = '" . $_POST['id_collector'] . "'";
                        $collector = mysql_fetch_assoc(db_query($query));

                        if ($collector['gruppi_base'] == 3 || $collector['gruppi_base'] == 12)
                            echo 'PHC - ' . $collector['nome'];
                        else if ($collector['gruppi_base'] == 6 || $collector['gruppi_base'] == 7)
                            echo 'ESA - ' . $collector['nome'];
                        else
                            echo $collector['nome'];
                    }
                    break;
                case 'id-controparte-venditore':
                    {
                        if ($_POST['id_recuperato'] > 0) {
                            $query = "SELECT U.id_utente
								FROM pratiche_recuperato PR
									LEFT JOIN pratiche P ON P.id=PR.id_pratica
									LEFT JOIN collegati C ON C.id_utente = P.id_mandante AND id_tipo = 14
									LEFT JOIN utente U ON U.id_utente = C.id_collegato
								WHERE PR.id = '" . $_POST['id_recuperato'] . "'";
                            $venditore = mysql_fetch_assoc(db_query($query));

                            echo $venditore['id_utente'];
                        }
                    }
                    break;
                case 'nome-venditore':
                    {
                        if ($_POST['id_recuperato'] > 0) {
                            $query = "SELECT CONCAT(IFNULL(nome,\"\"),' ',IFNULL(cognome,\"\")) AS nome
								FROM pratiche_recuperato PR
									LEFT JOIN pratiche P ON P.id=PR.id_pratica
									LEFT JOIN collegati C ON C.id_utente = P.id_mandante AND id_tipo = 14
									LEFT JOIN utente U ON U.id_utente = C.id_collegato
								WHERE PR.id = '" . $_POST['id_recuperato'] . "'";
                            $venditore = mysql_fetch_assoc(db_query($query));

                            echo 'V - ' . $venditore['nome'];
                        }
                    }
                    break;
                case 'nome-venditore-pratica':
                    {
                        $query = "SELECT CONCAT(IFNULL(nome,\"\"),' ',IFNULL(cognome,\"\")) AS nome
							FROM pratiche P
								LEFT JOIN collegati C ON C.id_utente = P.id_mandante AND id_tipo = 14
								LEFT JOIN utente U ON U.id_utente = C.id_collegato
							WHERE P.id = '" . $_POST['id_pratica'] . "'";
                        $venditore = mysql_fetch_assoc(db_query($query));

                        echo 'V - ' . $venditore['nome'];
                    }
                    break;
                case 'id-controparte-capovenditore':
                    {
                        if ($_POST['id_recuperato'] > 0) {
                            $query = "SELECT U.id_utente
								FROM pratiche_recuperato PR
									LEFT JOIN pratiche P ON P.id=PR.id_pratica
									LEFT JOIN collegati C ON C.id_utente = P.id_mandante AND id_tipo = 14
									LEFT JOIN collegati_venditore CV ON CV.id_collegato = C.id_collegato
									LEFT JOIN utente U ON U.id_utente = CV.id_utente
								WHERE PR.id = '" . $_POST['id_recuperato'] . "'";
                            $venditore = mysql_fetch_assoc(db_query($query));

                            echo $venditore['id_utente'];
                        }
                    }
                    break;
                case 'id-controparte-capovenditore-esito':
                    {
                        if ($_POST['id_utente'] > 0) {
                            $query = "SELECT U.id_utente
								FROM collegati_venditore CV
									LEFT JOIN utente U ON U.id_utente = CV.id_utente
								WHERE CV.id_collegato = '" . $_POST['id_utente'] . "'";
                            $venditore = mysql_fetch_assoc(db_query($query));

                            echo $venditore['id_utente'];
                        }
                    }
                    break;
                case 'nome-capo-venditore':
                    {
                        if ($_POST['id_recuperato'] > 0) {
                            $query = "SELECT CONCAT(IFNULL(nome,\"\"),' ',IFNULL(cognome,\"\")) AS nome
								FROM pratiche_recuperato PR
									LEFT JOIN pratiche P ON P.id=PR.id_pratica
									LEFT JOIN collegati C ON C.id_utente = P.id_mandante AND id_tipo = 14
									LEFT JOIN collegati_venditore CV ON CV.id_collegato = C.id_collegato
									LEFT JOIN utente U ON U.id_utente = CV.id_utente
								WHERE PR.id = '" . $_POST['id_recuperato'] . "'";
                            $venditore = mysql_fetch_assoc(db_query($query));

                            echo 'CAV - ' . $venditore['nome'];
                        }
                    }
                    break;
                case 'nome-capo-venditore-rif':
                    {
                        /*
				$query = "SELECT CONCAT(IFNULL(nome,\"\"),' ',IFNULL(cognome,\"\")) AS nome
							FROM pratiche P
								LEFT JOIN collegati C ON C.id_utente = P.id_mandante AND id_tipo = 14
								LEFT JOIN collegati_venditore CV ON CV.id_collegato = C.id_collegato
								LEFT JOIN utente U ON U.id_utente = CV.id_utente
							WHERE P.id = '".$_POST['id_pratica']."'";*/
                        $query = "SELECT CONCAT(IFNULL(nome,\"\"),' ',IFNULL(cognome,\"\")) AS nome
							FROM utente 
							WHERE id_utente = '" . $_POST['id_collector'] . "'";
                        $venditore = mysql_fetch_assoc(db_query($query));

                        if ($venditore['nome'] != '')
                            echo 'CAV - ' . $venditore['nome'];
                        else
                            echo ' ';
                    }
                    break;
                case 'id-controparte-capoesattore':
                    {
                        if ($_POST['id_recuperato'] > 0) {
                            $query = "SELECT U.id_utente
								FROM collegati_esattore CE
									LEFT JOIN utente U ON U.id_utente = CE.id_utente
								WHERE CE.id_collegato = '" . $_POST['id_recuperato'] . "'";
                            $esattore = mysql_fetch_assoc(db_query($query));

                            echo $esattore['id_utente'];
                        }
                    }
                    break;
                case 'id-controparte-capoesattore-esito':
                    {
                        if ($_POST['id_utente'] > 0) {
                            $query = "SELECT U.id_utente
								FROM pratiche_recuperato PR
									LEFT JOIN collegati_esattore CE ON CE.id_collegato = PR.id_collector
									LEFT JOIN utente U ON U.id_utente = CE.id_utente
								WHERE PR.id = '" . $_POST['id_utente'] . "'";
                            $esattore = mysql_fetch_assoc(db_query($query));

                            echo $esattore['id_utente'];
                        }
                    }
                    break;
                case 'nome-capo-esattore':
                    {
                        if ($_POST['id_recuperato'] > 0) {
                            $query = "SELECT CONCAT(IFNULL(nome,\"\"),' ',IFNULL(cognome,\"\")) AS nome
								FROM pratiche_recuperato PR
									LEFT JOIN collegati_esattore CE ON CE.id_collegato = PR.id_collector
									LEFT JOIN utente U ON U.id_utente = CE.id_utente
								WHERE PR.id = '" . $_POST['id_recuperato'] . "'";
                            $esattore = mysql_fetch_assoc(db_query($query));

                            echo 'CAE - ' . $esattore['nome'];
                        }
                    }
                    break;
                case 'nome-capo-esattore-rif':
                    {
                        /*$query = "SELECT CONCAT(IFNULL(nome,\"\"),' ',IFNULL(cognome,\"\")) AS nome
							FROM collegati_esattore CE
								LEFT JOIN utente U ON U.id_utente = CE.id_utente
							WHERE CE.id_collegato = '".$_POST['id_collector']."'";*/
                        $query = "SELECT CONCAT(IFNULL(nome,\"\"),' ',IFNULL(cognome,\"\")) AS nome
							FROM utente 
							WHERE id_utente = '" . $_POST['id_collector'] . "'";
                        $esattore = mysql_fetch_assoc(db_query($query));

                        if ($esattore['nome'] != '')
                            echo 'CAE - ' . $esattore['nome'];
                        else
                            echo ' ';
                    }
                    break;
                case 'id-controparte-capophc':
                    {
                        if ($_POST['id_recuperato'] > 0) {
                            $query = "SELECT U.id_utente
								FROM pratiche_recuperato PR
									LEFT JOIN collegati_phc CP ON CP.id_collegato = PR.id_collector
									LEFT JOIN utente U ON U.id_utente = CP.id_utente
								WHERE PR.id = '" . $_POST['id_recuperato'] . "'";
                            $phc = mysql_fetch_assoc(db_query($query));

                            echo $phc['id_utente'];
                        }
                    }
                    break;
                case 'id-controparte-capophc-esito':
                    {
                        if ($_POST['id_utente'] > 0) {
                            $query = "SELECT U.id_utente
								FROM collegati_phc CP
									LEFT JOIN utente U ON U.id_utente = CP.id_utente
								WHERE CP.id_collegato = '" . $_POST['id_utente'] . "'";
                            $phc = mysql_fetch_assoc(db_query($query));

                            echo $phc['id_utente'];
                        }
                    }
                    break;
                case 'nome-capo-phc':
                    {
                        if ($_POST['id_recuperato'] > 0) {
                            $query = "SELECT CONCAT(IFNULL(nome,\"\"),' ',IFNULL(cognome,\"\")) AS nome
								FROM pratiche_recuperato PR
									LEFT JOIN collegati_phc CP ON CP.id_collegato = PR.id_collector
									LEFT JOIN utente U ON U.id_utente = CP.id_utente
								WHERE PR.id = '" . $_POST['id_recuperato'] . "'";
                            $phc = mysql_fetch_assoc(db_query($query));

                            echo 'CAP - ' . $phc['nome'];
                        }
                    }
                    break;
                case 'nome-capo-phc-rif':
                    {
                        /*$query = "SELECT CONCAT(IFNULL(nome,\"\"),' ',IFNULL(cognome,\"\")) AS nome
							FROM collegati_esattore CE
								LEFT JOIN utente U ON U.id_utente = CE.id_utente
							WHERE CE.id_collegato = '".$_POST['id_collector']."'";*/
                        $query = "SELECT CONCAT(IFNULL(nome,\"\"),' ',IFNULL(cognome,\"\")) AS nome
							FROM utente 
							WHERE id_utente = '" . $_POST['id_collector'] . "'";
                        $phc = mysql_fetch_assoc(db_query($query));

                        if ($phc['nome'] != '')
                            echo 'CAP - ' . $phc['nome'];
                        else
                            echo ' ';
                    }
                    break;
                case 'nome-collector-esito':
                    {
                        $query = "SELECT CONCAT(IFNULL(nome,\"\"),' ',IFNULL(cognome,\"\")) AS nome, gruppi_base
							FROM utente
							WHERE id_utente = '" . $_POST['id_collector'] . "'";
                        $collector = mysql_fetch_assoc(db_query($query));

                        if ($collector['gruppi_base'] == 3)
                            echo 'PHC - ' . $collector['nome'];
                        else if ($collector['gruppi_base'] == 6)
                            echo 'ESA - ' . $collector['nome'];
                        else if ($collector['gruppi_base'] == 12)
                            echo 'CAP - ' . $collector['nome'];
                        else if ($collector['gruppi_base'] == 7)
                            echo 'CAE - ' . $collector['nome'];
                        else
                            echo $collector['nome'];
                    }
                    break;
                case 'tipo-pagamento':
                    {
                        $query = "SELECT TP.descrizione
							FROM contratto_pagamento_previsto CPP
							LEFT JOIN tipo_pagamento TP ON TP.id = CPP.tipo_pagamento
							WHERE CPP.id = '" . $_POST['id_tipo_pagamento'] . "'";
                        $tipo_pagamento = mysql_fetch_assoc(db_query($query));

                        echo strip_tags($tipo_pagamento['descrizione']);
                    }
                    break;
                case 'tipo-pagamento-tipo':
                    {
                        $query = "SELECT TP.tipo
							FROM contratto_pagamento_previsto CPP
							LEFT JOIN tipo_pagamento TP ON TP.id = CPP.tipo_pagamento
							WHERE CPP.id = '" . $_POST['id_tipo_pagamento'] . "'";
                        $tipo_pagamento = mysql_fetch_assoc(db_query($query));

                        echo strip_tags($tipo_pagamento['tipo']);
                    }
                    break;
                case 'tipo-pagamento-pdr':
                    {
                        $query = "SELECT tipo_incasso
							FROM piani_di_rientro
							WHERE id = '" . $_POST['id_pdr'] . "'";
                        $tipo_pagamento = mysql_fetch_assoc(db_query($query));

                        echo strip_tags($tipo_pagamento['tipo_incasso']);
                    }
                    break;
                case 'tipo-pagamento-pdr-esa':
                    {
                        $query = "SELECT tipo_incasso 
							FROM piani_di_rientro_esattore
							WHERE id = '" . $_POST['id_pdr'] . "'";
                        $tipo_pagamento = mysql_fetch_assoc(db_query($query));

                        echo strip_tags($tipo_pagamento['tipo_incasso']);
                    }
                    break;
                case 'id-mandante':
                    {
                        $query = "SELECT id_mandante
							FROM pratiche
							WHERE id = '" . $_POST['id_pratica'] . "'";
                        $mandante = mysql_fetch_assoc(db_query($query));

                        echo $mandante['id_mandante'];
                    }
                    break;
            }
        }
        break;
    case 'invia-email-conferma-calcolo-compensi':
        {
            // RECUPERO DETTAGLI IMPOSTAZIONI
            $get_mail_details = 'SELECT mail_username, mail_password, mail_nome_visibile, mail_smtp FROM impostazioni_base WHERE id="1"';
            $mail_details_scr = db_query($get_mail_details);

            $message = 'La Procedura Notturna di Calcolo Compensi, iniziata alle ' . $_REQUEST['inizio'] . ', è Terminata con Successo alle ' . date('Y-m-d H:i');
            $subject = 'ReMida HPS - Calcolo Compensi Scheduled Task';

            require_once('assets/plugins/phpmailer/class.phpmailer.php');
            require_once('assets/plugins/phpmailer/class.smtp.php');

            /*
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->SMTPDebug  = 2; // enables SMTP debug information (for testing)
			$mail->SMTPSecure = 'tls'; //ssl
			$mail->SMTPAuth   = true;
			$mail->Host = 'smtp.gmail.com';
			$mail->Port = 587; // 465
			$mail->Username   = "";
			$mail->Password   = "";
			$mail->AddReplyTo("","ReMida HPS");
			$mail->SetFrom("", 'ReMida HPS');
			$mail->Subject = $subject;
			$mail->MsgHTML('<!DOCTYPE html><head><meta charset="utf-8"/><title>ReMida</title><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta content="width=device-width, initial-scale=1.0" name="viewport"/><meta content="" name="description"/><meta content="" name="author"/></head><body>'.html_entity_decode($message).'</body></html>');
			$mail->CharSet = 'UTF-8';
			$address = "";
			$user = "";
			$mail->AddAddress($address, $user);
			$mail->Send();
		*/

            if (mysql_num_rows($mail_details_scr) > 0) {
                $mail_details = mysql_fetch_assoc($mail_details_scr);

                // INVIO CON PHP MAILER
                // USARE CREDENZIALI SE IMPOSTATE FORZA UTILIZZO CONFIGURAZIONE SMTP
                if (isset($mail_details['mail_smtp']) and $mail_details['mail_smtp'] != '') {
                    if ($mail_details['mail_password'] != "") {
                        $msg = '. Utilizzate Impostazioni SMTP configurate.';
                        $authentication = true;
                        $host_port = explode(':', $mail_details['mail_smtp']);

                        $host = $host_port[0];
                        $port = (isset($host_port[1])) ? $host_port[1] : '25';
                        $username = $mail_details['mail_username'];
                        $password = $mail_details['mail_password'];
                    } else {
                        $msg = '. Utilizzate Impostazioni SMTP configurate.';
                        $authentication = false;
                        $host_port = explode(':', $mail_details['mail_smtp']);

                        $smtpSecure = "tls";
                        $host = $host_port[0];
                        $port = (isset($host_port[1])) ? $host_port[1] : '25';
                        $username = '';
                        $password = '';
                    }
                } else {
                    $msg = '. Utilizzate Impostazioni SMTP di base.';
                    $authentication = false;
                    $host = 'localhost';
                    $port = '25';
                    $username = '';
                    $password = '';
                }

                require_once('assets/plugins/phpmailer/class.phpmailer.php');
                require_once('assets/plugins/phpmailer/class.smtp.php');

                $mail = new PHPMailer();
                $mail->IsSMTP();
                $mail->IsHTML(true);
                //$mail->SMTPDebug  = 1; 									// enable/disable SMTP debug information (for testing) 1=errors & message 2=only message
                if ($port == 587 || (strpos($host,'mail.protection.outlook')>0)) {
                    $mail->SMTPSecure = 'tls';
                } else if ($port == 465) {
                    $mail->SMTPSecure = 'ssl';
                } else {
                    $mail->SMTPSecure = 'none';
                }
                if (isset($smtpSecure) && $smtpSecure != "") {
                    $mail->SMTPSecure = $smtpSecure;

                }
                $mail->SMTPAuth = $authentication;                    // enable/disable SMTP authentication
                $mail->Host = $host;
                $mail->Port = $port;
                if ($authentication) {
                    $mail->Username = $username;                            // SMTP account username
                    $mail->Password = $password;                            // SMTP account password
                }
                $mail->AddReplyTo('supporto@remidahps.it', 'ReMidaHPS');
                $mail->SetFrom($mail_details['mail_username'], 'ReMidaHPS');                            // MITTENTE
                $mail->Subject = $subject;                                    // OGGETTO
                $mail->MsgHTML('<!DOCTYPE html><head><meta charset="utf-8"/><title>ReMida</title><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta content="width=device-width, initial-scale=1.0" name="viewport"/><meta content="" name="description"/><meta content="" name="author"/></head><body>' . html_entity_decode($message) . '</body></html>');                                    // MESSAGGIO HTML/TEXT
                $mail->CharSet = 'UTF-8';                                    // ENCODING

                $mail->AddAddress('supporto@remidahps.it', 'ReMidaHPS');                            // DESTINATARIO

                // COPIE
                // $mail->AddBCC('d.garces@SolunicaNET-pro.com');
                //$mail->AddBCC('manuelbertolucci@gmail.com', 'Manuel Bertolucci');

                // ALLEGATI
                // $mail->AddAttachment('path/to/file.ext'); 					// ALLEGATI DA FILE

                // MANUEL: Modifica Allegati 2017/10/09
                //if( $attachment ) $mail->AddAttachment( $file_content );		// ALLEGATI DA FILE

                $sent = $mail->Send();

                // $sent = true;  // DEBUG

                // AGGIUNTA REGISTRAZIONE STORICO MAIL
                if ($sent) {
                }
            }
        }
        break;
    case 'cambia-importi-percepiti':
        {
            $query_impostazione_preappro = " select compensi_preapprovazione from impostazioni_base where id=1";
            $ris_preappro = db_query($query_impostazione_preappro);
            $impostazione_preappro = db_fetch_array($ris_preappro)[0]['compensi_preapprovazione'];


            if ($impostazione_preappro == 1) {
                $tabella_salvataggio = " calcolo_compenso_preapprovazione ";

            } else {
                $tabella_salvataggio = " calcolo_compenso ";
            }

            if ($_POST['tipo'] == 1) {
                $query = "UPDATE " . $tabella_salvataggio . " 
						SET compenso_percepito = '" . db_input(str_replace(',', '.', $_POST['importo'])) . "'
						WHERE id = " . $_POST['idCompenso'];
                db_query($query);
                echo 'SUCCESS';
                die();
            } else if ($_POST['tipo'] == 2) {
                $query = "UPDATE " . $tabella_salvataggio . " 
						SET storno_percepito = '" . db_input(str_replace(',', '.', $_POST['importo'])) . "'
						WHERE id = " . $_POST['idCompenso'];
                db_query($query);
                echo 'SUCCESS';
                die();
            } else {
                echo 'FAILED';
                die();
            }
        }
        break;
    // INCASSI E PDP MASSIVA
    case 'seleziona-incassi-massiva':
        {
            if ($_POST['selezione'] > 0) {
                $query = $_SESSION['query-incassi-massivi'];
                $ris_incassi = db_query($query);

                $incassi = '';
                while ($incasso = mysql_fetch_assoc($ris_incassi)) {
                    $incassi .= $incasso['id'] . ',';
                }

                writeSession('selection-incassi-massivi', trim($incassi, ','));
            } else {
                writeSession('selection-incassi-massivi', '');
            }

            echo $_SESSION['selection-incassi-massivi'];
        }
        break;
    case 'seleziona-incasso-massiva':
        {
            if ($_SESSION['selection-incassi-massivi'] != '') {
                if (strpos($_SESSION['selection-incassi-massivi'], ',') > 0) {
                    $incassi = explode(',', $_SESSION['selection-incassi-massivi']);

                    if (($key = array_search($_POST['id_incasso'], $incassi)) !== false) {
                        if ($_POST['selection'] == '0') {
                            unset($incassi[$key]);
                        }
                    } else {
                        if ($_POST['selection'] != '0') {
                            $incassi[] = $_POST['id_incasso'];
                        }
                    }

                    writeSession('selection-incassi-massivi', implode($incassi, ','));
                } else {
                    if ($_POST['selection'] == '0') {
                        writeSession('selection-incassi-massivi', '');

                    } else {
                        writeSession('selection-incassi-massivi', $_SESSION['selection-incassi-massivi'] . ',' . $_POST['id_incasso']);
                    }
                }
            } else {
                writeSession('selection-incassi-massivi', $_POST['id_incasso']);
            }

            echo $_SESSION['selection-incassi-massivi'];
        }
        break;
    case 'verifica-utilizzo-cf':
        {
            $query = "SELECT *
					FROM utente 
					WHERE codice_fiscale = '" . $_POST['cf'] . "'
						AND (gruppi_base = 8 OR gruppi_base = 10)";
            $ris = db_query($query);

            if (db_num_rows($ris) > 0)
                echo 'USED';
            else
                echo 'NOT USED';
        }
        break;
    case 'svuota-export-incassi':
        {
            $query = "UPDATE pratiche_recuperato
					SET id_export = 0 
					WHERE id_export = '" . $_POST['id_export'] . "'";
            $ris = db_query($query);
        }
        break;
    case 'svuota-export-esiti':
        {
            $query = "UPDATE pratiche
					SET id_export = 0 
					WHERE id_export = '" . $_POST['id_export'] . "'";
            $ris = db_query($query);
        }
        break;
    case 'svuota-export-note':
        {
            $query = "UPDATE note_su_pratica
					SET id_export = 0 
					WHERE id_export = '" . $_POST['id_export'] . "'";
            $ris = db_query($query);
        }
        break;
    case 'modifica-massiva-incassi':
        {
            $arrayEvPrt = [];
            //print_r($_POST); die();

            $recuperatiSelezionati = array_unique(explode(',', $_POST['id_pratiche']));

            foreach ($recuperatiSelezionati as $idRecuperato) {
                eliminaFlagPotenziale($idRecuperato);

                $query_update = '';

                if (isset($_POST['data_emissione']) && $_POST['data_emissione'] != '') {
                    $query_update .= ' data_emissione = "' . db_input(date('Y-m-d', strtotime($_POST['data_emissione']))) . '", ';
                }
                if (isset($_POST['estremi']) && $_POST['estremi'] != '') {
                    $query_update .= ' estremi = "' . db_input($_POST['estremi']) . '", ';
                }
                if (isset($_POST['data_scadenza']) && $_POST['data_scadenza'] != '') {
                    $query_update .= ' data_scadenza = "' . db_input(date('Y-m-d', strtotime($_POST['data_scadenza']))) . '", ';
                }
                if (isset($_POST['descrizione']) && $_POST['descrizione'] != '') {
                    $query_update .= ' descrizione = "' . db_input($_POST['descrizione']) . '", ';
                }
                if (isset($_POST['id_anagrafica_pagante']) && $_POST['id_anagrafica_pagante']) {
                    $query_update .= ' id_anagrafica_pagante = "' . db_input($_POST['id_anagrafica_pagante']) . '", ';
                }
                if (isset($_POST['codice_iban']) && $_POST['codice_iban'] != '') {
                    $query_update .= ' codice_iban = "' . db_input($_POST['codice_iban']) . '", ';
                }
                if (isset($_POST['codice_cin']) && $_POST['codice_cin'] != '') {
                    $query_update .= ' codice_cin = "' . db_input($_POST['codice_cin']) . '", ';
                }
                if (isset($_POST['codice_abi']) && $_POST['codice_abi'] != '') {
                    $query_update .= ' codice_abi = "' . db_input($_POST['codice_abi']) . '", ';
                }
                if (isset($_POST['codice_cab']) && $_POST['codice_cab'] != '') {
                    $query_update .= ' codice_cab = "' . db_input($_POST['codice_cab']) . '", ';
                }
                if (isset($_POST['codice_piazza']) && $_POST['codice_piazza'] != '') {
                    $query_update .= ' codice_piazza = "' . db_input($_POST['codice_piazza']) . '", ';
                }
                if (isset($_POST['flag_non_trasmettere']) && $_POST['flag_non_trasmettere'] != '0') {
                    $query_update .= ' non_trasmettere = "' . db_input($_POST['non_trasmettere']) . '", ';
                }
                if (isset($_POST['flag_aui_grosso_taglio']) && $_POST['flag_aui_grosso_taglio'] != '0') {
                    $query_update .= ' aui_grosso_taglio = "' . db_input($_POST['aui_grosso_taglio']) . '", ';
                }
                if (isset($_POST['flag_aui_estero']) && $_POST['flag_aui_estero'] != '0') {
                    $query_update .= ' aui_estero = "' . db_input($_POST['aui_estero']) . '", ';
                }
                if (isset($_POST['riferimento_mandante1']) && $_POST['riferimento_mandante1'] != '') {
                    $query_update .= ' riferimento_mandante1 = "' . db_input($_POST['riferimento_mandante1']) . '", ';
                }
                if (isset($_POST['id_operatore']) && $_POST['id_operatore'] != '') {
                    $query_update .= ' id_operatore = "' . db_input($_POST['id_operatore']) . '", ';
                }
//TRELLO #00118
                $importoNew = false;
                if (isset($_POST['modifica_importo']) && $_POST['modifica_importo'] != '0' && isset($_POST['importo']) && $_POST['importo'] > '0.00' && $_POST['importo'] != '') {
                    $query_update .= ' importo = "' . db_input($_POST['importo']) . '", ';
                    $importoNew = $_POST['importo'];
                }


                $insolutaNew = false;
                if (isset($_POST['flag_insoluta']) && $_POST['flag_insoluta'] != '0') {
                    {
                        $insolutaNew = $_POST['insoluta'];

                        if ($_POST['insoluta'] == 1) {
                            $query_update .= ' insoluta = "' . db_input($_POST['insoluta']) . '", data_bf_old=data_bf, data_bf="", buon_fine = "0", ';
                        }


                        if ($_POST['insoluta'] == 0) {
                            $query_update .= ' insoluta = "' . db_input($_POST['insoluta']) . '", data_bf=data_bf_old, data_bf_old=null, buon_fine = "0", ';

                            if (isset($_POST['data_bf']) && $_POST['data_bf'] != '') {
                                $query_update .= ' data_bf = "' . db_input(date('Y-m-d', strtotime($_POST['data_bf']))) . '", ';
                            }

                            if (isset($_POST['data_bf']) && $_POST['data_bf'] != '' && date('Y-m-d', strtotime($_POST['data_bf'])) <= date('Y-m-d')) {
                                $query_update .= ' buon_fine = "1", ';
                            } else if (isset($_POST['data_bf']) && $_POST['data_bf'] != '') {
                                $query_update .= ' buon_fine = "0", ';
                            }

                        }

                    }
                }
                else {
                    if (isset($_POST['data_bf']) && $_POST['data_bf'] != '') {
                        $query_update .= ' data_bf = "' . db_input(date('Y-m-d', strtotime($_POST['data_bf']))) . '", ';
                    }

                    if (isset($_POST['data_bf']) && $_POST['data_bf'] != '' && date('Y-m-d', strtotime($_POST['data_bf'])) <= date('Y-m-d')) {
                        $query_update .= ' buon_fine = "1", ';
                    } else if (isset($_POST['data_bf']) && $_POST['data_bf'] != '') {
                        $query_update .= ' buon_fine = "0", ';
                    }
                }

                $tipologiaPagamentoNew = false;
                if (isset($_POST['id_tipologia_pagamento']) && $_POST['id_tipologia_pagamento'] > 0) {
                    $query_recupero_id_pagamento_predefinito = "SELECT CPP.id AS idTipoPag,TP.tipo
																FROM pratiche_recuperato PR
																	LEFT JOIN pratiche P ON P.id = PR.id_pratica
																	LEFT JOIN contratto_pagamento_previsto CPP ON CPP.id_contratto = P.id_contratto
																	LEFT JOIN tipo_pagamento TP ON TP.id = CPP.tipo_pagamento
																WHERE PR.id = '" . db_input($idRecuperato) . "' 
																	AND CPP.tipo_pagamento = '" . db_input($_POST['id_tipologia_pagamento']) . "'
																LIMIT 0,1";

                    //echo $query_recupero_id_pagamento_predefinito;
                    $ris_recupero_id_pagamento_predefinito = db_query($query_recupero_id_pagamento_predefinito);
                    $tipologiaPagamento = mysql_fetch_array($ris_recupero_id_pagamento_predefinito);

                    $query_update .= ' id_tipologia_pagamento = "' . $tipologiaPagamento['idTipoPag'] . '", ';
                    $query_update .= ' tipo = "' . $tipologiaPagamento['tipo'] . '", ';

                    $tipologiaPagamentoNew = $tipologiaPagamento['idTipoPag'];
                }


                if ($query_update != '') {
                    calcolaStorniEricacoloRecuperati($idRecuperato, $importoNew, $tipologiaPagamentoNew, $insolutaNew);

                    if (isset($_POST['esito_recuperato']) && $_POST['esito_recuperato'] > 0) {
                        $modifica = 'UPDATE pratiche_recuperato SET ' . trim($query_update, ', ') . ',esito_recuperato="' . $_POST['esito_recuperato'] . '" WHERE id = "' . db_input($idRecuperato) . '"';
                    } else {
                        $modifica = 'UPDATE pratiche_recuperato SET ' . trim($query_update, ', ') . ' WHERE id = "' . db_input($idRecuperato) . '"';
                    }
                    //echo $modifica;
                    db_query($modifica) or die(mysql_error());


                    if (isset($_POST['esito_recuperato']) && $_POST['esito_recuperato'] > 0) {
                        $arrayPrt = [];
                        $eventoDaEseguire = 0;
                        $id_prtuno = db_fetch_array_assoc(db_query("SELECT id_pratica FROM pratiche_recuperato WHERE id='" . db_input($idRecuperato) . "'"))[0]['id_pratica'];

                        $arrayPrt[] = $id_prtuno;

                        $risGetNewEvento = db_query("SELECT evento_remida FROM esiti_recuperato WHERE id='" . $_POST['esito_recuperato'] . "'");
                        if (db_num_rows($risGetNewEvento) > 0) {
                            $newEvento = db_fetch_array_assoc($risGetNewEvento)[0];

                            if (isset($newEvento['evento_remida']) && $newEvento['evento_remida'] > 0) {
                                $eventoDaEseguire = $newEvento['evento_remida'];
                            }
                        }
                    }

                    if (isset($_POST['data_buonfine_modificata']) && $_POST['data_buonfine_modificata'] > 0) {
                        $id_prt = db_fetch_array_assoc(db_query("SELECT id_pratica FROM pratiche_recuperato WHERE id='" . db_input($idRecuperato) . "'"))[0]['id_pratica'];

                        $risGetNewEsito = db_query("SELECT esito_buonfine FROM esiti_recuperato WHERE id=(SELECT esito_recuperato FROM pratiche_recuperato WHERE id='" . db_input($idRecuperato) . "' )");
                        if (db_num_rows($risGetNewEsito) > 0) {
                            $newEsito = db_fetch_array_assoc($risGetNewEsito)[0];
                            $esitoDaImpostare = $newEsito['esito_buonfine'];
                            if ($esitoDaImpostare > 0) {
                                $query_aggiornamento_lingua = '	UPDATE pratiche_recuperato
													SET esito_precedente= esito_recuperato,esito_recuperato = "' . db_input($esitoDaImpostare) . '"
													WHERE id = "' . db_input($idRecuperato) . '"';


                                $ris_aggiornamento_lingua = db_query($query_aggiornamento_lingua) or die(mysql_error());

                                $risGetNewEvento = db_query("SELECT evento_remida FROM esiti_recuperato WHERE id='" . $esitoDaImpostare . "'");

                                if (db_num_rows($risGetNewEvento) > 0) {
                                    $newEvento = db_fetch_array_assoc($risGetNewEvento)[0];
                                    if (isset($newEvento['evento_remida']) && $newEvento['evento_remida'] > 0) {
                                        $eventoDaEseguireDue = $newEvento['evento_remida'];
                                        if (isset($arrayEvPrt[$id_prt][$eventoDaEseguireDue]) && $arrayEvPrt[$id_prt][$eventoDaEseguireDue] == 1) {
                                            $arrayEvPrt[$id_prt][$eventoDaEseguireDue] = 1;
                                        } else {
                                            esegui_evento_strutturato($id_prt, $eventoDaEseguireDue);
                                            $arrayEvPrt[$id_prt][$eventoDaEseguireDue] = 1;
                                        }

                                    }

                                }
                            }
                        }
                    }

                }


                $queryCollectorInGestione = "SELECT id_collector FROM pratiche_recuperato WHERE id = '" . $idRecuperato . "'";
                $collectorInGestione = mysql_fetch_assoc(db_query($queryCollectorInGestione));

                // RIMOSSO PER PERFORMACE
                // pratiche_calcoloPerformanceSuContratto($collectorInGestione['id_collector'],'','');

                $queryPratica = "SELECT id_pratica FROM pratiche_recuperato WHERE id = '" . $idRecuperato . "'";
                $praticaRelativa = mysql_fetch_assoc(db_query($queryPratica));

                /* TODO: effettuare ricalcoli */

                //AGGIORNO I TOTALI SULLA TABELLA PRATICHE DEI NON BUON FINE
                $query = 'SELECT quota_ca,quota_si,quota_int,quota_affidato_1,quota_affidato_2,quota_affidato_3,quota_ors,quota_oi,quota_surplus
                          FROM pratiche_recuperato
                          WHERE id_pratica = "' . $praticaRelativa['id_pratica'] . '" AND (insoluta=0 OR insoluta IS NULL) AND ( tipo="DIR" OR tipo="IND" )';
                $ris_ins = db_query($query);

                $recuperato_capitale = 0;
                $recuperato_spese = 0;
                $recuperato_interessi = 0;
                $recuperato_affidato_1 = 0;
                $recuperato_affidato_2 = 0;
                $recuperato_affidato_3 = 0;
                $recuperato_oneri_recupero = 0;
                $recuperato_spese_incasso = 0;
                $recuperato_surplus = 0;

                while ($ris = mysql_fetch_array($ris_ins)) {

                    $recuperato_capitale += $ris['quota_ca'];
                    $recuperato_spese += $ris['quota_si'];
                    $recuperato_interessi += $ris['quota_int'];
                    $recuperato_affidato_1 += $ris['quota_affidato_1'];
                    $recuperato_affidato_2 += $ris['quota_affidato_2'];
                    $recuperato_affidato_3 += $ris['quota_affidato_3'];
                    $recuperato_oneri_recupero += $ris['quota_ors'];
                    $recuperato_spese_incasso += $ris['quota_oi'];
                    $recuperato_surplus += $ris['quota_surplus'];
                }

                $query_inserimento_insoluto = 'UPDATE pratiche
                                               SET recuperato_capitale = "' . db_input($recuperato_capitale) . '",
                                                     recuperato_spese = "' . db_input($recuperato_spese) . '",
                                                     recuperato_interessi = "' . db_input($recuperato_interessi) . '",
                                                     recuperato_affidato_1 = "' . db_input($recuperato_affidato_1) . '",
                                                     recuperato_affidato_2 = "' . db_input($recuperato_affidato_2) . '",
                                                     recuperato_affidato_3 = "' . db_input($recuperato_affidato_3) . '",
                                                     recuperato_oneri_recupero = "' . db_input($recuperato_oneri_recupero) . '",
                                                     recuperato_spese_incasso = "' . db_input($recuperato_spese_incasso) . '",
                                                     recuperato_surplus = "' . db_input($recuperato_surplus) . '"
                                                 WHERE id = "' . $praticaRelativa['id_pratica'] . '"';

                $ris_inserimento_insoluto = db_query($query_inserimento_insoluto);
                //FINE AGGIORNAMENTO

                //AGGIORNO I TOTALI SULLA TABELLA PRATICHE BUON FINE
                $query = 'SELECT quota_ca,quota_si,quota_int,quota_affidato_1,quota_affidato_2,quota_affidato_3,quota_ors,quota_oi,quota_surplus
                  FROM pratiche_recuperato
                  WHERE id_pratica = "' . $praticaRelativa['id_pratica'] . '" AND (insoluta=0 OR insoluta IS NULL) AND buon_fine=1 AND ( tipo="DIR" OR tipo="IND" )';
                $ris_ins = db_query($query);

                $recuperato_capitale_bf = 0;
                $recuperato_spese_bf = 0;
                $recuperato_interessi_bf = 0;
                $recuperato_affidato_1_bf = 0;
                $recuperato_affidato_2_bf = 0;
                $recuperato_affidato_3_bf = 0;
                $recuperato_oneri_recupero_bf = 0;
                $recuperato_spese_incasso_bf = 0;
                $recuperato_surplus_bf = 0;

                while ($ris = mysql_fetch_array($ris_ins)) {

                    $recuperato_capitale_bf += $ris['quota_ca'];
                    $recuperato_spese_bf += $ris['quota_si'];
                    $recuperato_interessi_bf += $ris['quota_int'];
                    $recuperato_affidato_1_bf += $ris['quota_affidato_1'];
                    $recuperato_affidato_2_bf += $ris['quota_affidato_2'];
                    $recuperato_affidato_3_bf += $ris['quota_affidato_3'];
                    $recuperato_oneri_recupero_bf += $ris['quota_ors'];
                    $recuperato_spese_incasso_bf += $ris['quota_oi'];
                    $recuperato_surplus_bf += $ris['quota_surplus'];

                }

                $query_inserimento_insoluto = 'UPDATE pratiche
                                               SET recuperato_capitale_bf = "' . db_input($recuperato_capitale_bf) . '",
                                                 recuperato_spese_bf = "' . db_input($recuperato_spese_bf) . '",
                                                 recuperato_interessi_bf = "' . db_input($recuperato_interessi_bf) . '",
                                                 recuperato_affidato_1_bf = "' . db_input($recuperato_affidato_1_bf) . '",
                                                 recuperato_affidato_2_bf = "' . db_input($recuperato_affidato_2_bf) . '",
                                                 recuperato_affidato_3_bf = "' . db_input($recuperato_affidato_3_bf) . '",
                                                 recuperato_oneri_recupero_bf = "' . db_input($recuperato_oneri_recupero_bf) . '",
                                                 recuperato_spese_incasso_bf = "' . db_input($recuperato_spese_incasso_bf) . '",
                                                 recuperato_surplus_bf = "' . db_input($recuperato_surplus_bf) . '"
                                                 WHERE id = "' . $praticaRelativa['id_pratica'] . '"';

                $ris_inserimento_insoluto = db_query($query_inserimento_insoluto);


                // VERIFICA DEGLI INSOLUTI, POICHè NEL CASO NON SUSSISTANO PIù I REQUISITI DELLA TOMBALIZZAZIONE, VANNO STORNATI INSOLUTI PRECEDENTI
                $queryPrtRecInsoluti = "SELECT id from pratiche_recuperato WHERE insoluta=1 AND id_pratica = '" . $praticaRelativa['id_pratica'] . "' AND id<>" . $idRecuperato . " AND id IN (SELECT id_pratica_recuperata FROM calcolo_compenso WHERE tombale = 1)";
                $risPrtRecInsoluti = db_query($queryPrtRecInsoluti);
                if (db_num_rows($risPrtRecInsoluti)) {
                    $prtRecInsoluti = db_fetch_array($risPrtRecInsoluti);
                    foreach ($prtRecInsoluti as $prtRecInsoluto) {
                        calcolaStorniEricacoloRecuperati($prtRecInsoluto['id'], false, false, false, true);
                    }
                }

            }

            if (count($arrayPrt) > 0) {
                $arrayEvtPrt = array_unique($arrayPrt);
                if ($eventoDaEseguire > 0) {
                    esegui_evento_strutturato($arrayEvtPrt, $eventoDaEseguire);
                }
            }

            //[id_operatore] => 2
            //[id_pratiche] => 3732,3731,3730,3729,3728
            //[data_emissione] => 06-03-2018
            //[data_scadenza] => 14-03-2018
            //[data_bf] => 19-03-2018
            //[id_tipologia_pagamento] => 3
            //[estremi] => Pdp
            //[descrizione] => DESC
            //[buon_fine] => 0
            //[insoluta] => 1
            //[non_trasmettere] => 1
            //[flag_insoluta] => 1
            //[flag_non_trasmettere] => 0
            //[non_frazionare] => 1
            //[aui_grosso_taglio] => 0
            //[aui_estero] => 0
            //[flag_aui_grosso_taglio] => 1
            //[flag_aui_estero] => 0
            //[id_anagrafica_pagante] => 49
            //[riferimento_mandante1] => RIF
            //[codice_iban] => IBAN
            //[codice_cin] => C
            //[codice_abi] => ABI
            //[codice_cab] => CAB

            writeSession('selection-incassi-massivi', '');
            unset($_SESSION['selection-incassi-massivi']);

            echo 'OK';
        }
        break;
    case 'crea-zip-allegati':
        { //TRELLO #00030

            if (isset($_POST['percorso'])) {
                $percorso = $_POST['percorso'];
                $destinazione = substr($percorso, 0, -1) . ".zip";
                if (zipData($percorso, $destinazione)) {
                    array_map('unlink', glob("$percorso/*.*"));
                    rmdir($percorso);
                    if (file_exists($destinazione)) {
                        $type = "application/x-zip-compressed";
                        $name = pathinfo($destinazione, PATHINFO_FILENAME) . ".zip";
                        $contents = base64_encode(file_get_contents($destinazione));
                        $file = array('nome_file_zip' => $name, 'type_file' => $type, 'contenuto' => $contents);
                        echo json_encode($file);

                    } else {
                        die("ERRORE RICERCA FILE ZIP");
                    }
                } else {
                    die ("ERRORE CREAZIONE ZIP");
                }

            } else {
                die("MANCANO PARAMETRI");
            }
        }
        break;
    case 'upload-allegati-collector':
        {

            if (isset($_POST['percorso'])) {
                $percorso = $_POST['percorso'];
                $giorniValidita = $_POST['numGiorni'];
                $esa = $_POST['esa'];
                if ($esa == 1) {
                    $path = $path_allegatiEsa;
                } else {
                    $path = $path_allegatiPhc;
                }

                $date = date("Y-m-d");// current date

                $date = date('Ymd', strtotime($date . "+" . $giorniValidita . " day"));

                if ($handle = opendir($percorso)) {

                    while (false !== ($entry = readdir($handle))) {

                        if ($entry != "." && $entry != "..") {
                            $temp = $entry;
                            $tempArray = explode('.', $temp);
                            $extension = $tempArray[count($tempArray) - 1];
                            array_pop($tempArray);
                            $nomeFile = implode('.', $tempArray);
                            $newFileName = $nomeFile . '-' . $date . '.' . $extension;

                            rename($percorso . $entry, $path . '\\' . $newFileName);
                        }
                    }


                    closedir($handle);
                }
                print_r(json_encode(1));


            } else {
                die("MANCANO PARAMETRI");
            }
        }
        break;
    case 'upload-allegati-book-master':
        {

            if (isset($_POST['percorso'])) {
                $percorso = $_POST['percorso'];
                $path = $path_allegatiBookMaster;

                if ($handle = opendir($percorso)) {

                    while (false !== ($entry = readdir($handle))) {

                        if ($entry != "." && $entry != "..") {
                            $temp = $entry;
                            $tempArray = explode('.', $temp);
                            $extension = $tempArray[count($tempArray) - 1];
                            array_pop($tempArray);
                            $nomeFile = implode('.', $tempArray);
                            $newFileName = $nomeFile . '.' . $extension;

                            rename($percorso . $entry, $path . '\\' . $newFileName);
                        }
                    }


                    closedir($handle);
                }
                print_r(json_encode(1));


            } else {
                die("MANCANO PARAMETRI");
            }
        }
        break;


    case 'elimina-cartella-allegati':
        { //TRELLO #00030

            if (isset($_POST['percorso'])) {
                $percorso = $_POST['percorso'];
                $zip = substr($percorso, 0, -1) . ".zip";
                array_map('unlink', glob("$percorso/*.*"));
                rmdir($percorso);
                unlink($zip);
            } else {
                die("MANCANO PARAMETRI");
            }
        }
        break;

    case 'elimina-allegato-collector-path':
        {
            if (isset($_POST['percorso'])) {
                $percorso = $_POST['percorso'];
                unlink($percorso);
                print_r(json_encode(1));
            } else {
                die("MANCANO PARAMETRI");
            }
        }
        break;


    case 'recupera-copertura-titoli':
        {
            $result = array();

            if ($_POST['id'] > 0) {
                $querySelezioneCopertura = "SELECT *
											FROM dettaglio_copertura_titoli
										WHERE id_recuperato = '" . db_input($_POST['id']) . "'
										ORDER BY id";
                $risSelezioneCopertura = db_query($querySelezioneCopertura);

                $n_records = mysql_num_rows($risSelezioneCopertura);

                $result['error'] = 0;
                $result['counter'] = $n_records;
                while ($dettaglio = mysql_fetch_assoc($risSelezioneCopertura)) {
                    $result['copertura'][] = $dettaglio['id_insoluto'] . '_' . $dettaglio['tipo_quota'];
                }
            } else {
                $result['error'] = 1;
            }

            print_r(json_encode($result));
        }
        break;
    case 'recupera-copertura-titoli-validazione-incassi':
        {
            $result = array();

            if ($_POST['id'] > 0) {
                $querySelezioneCopertura = "SELECT *
											FROM dettaglio_copertura_titoli_validazione_incassi
										WHERE id_recuperato = '" . db_input($_POST['id']) . "'
										ORDER BY id";
                $risSelezioneCopertura = db_query($querySelezioneCopertura);

                $n_records = mysql_num_rows($risSelezioneCopertura);

                $result['error'] = 0;
                $result['counter'] = $n_records;
                while ($dettaglio = mysql_fetch_assoc($risSelezioneCopertura)) {
                    $result['copertura'][] = $dettaglio['id_insoluto'] . '_' . $dettaglio['tipo_quota'];
                }
            } else {
                $result['error'] = 1;
            }

            print_r(json_encode($result));
        }
        break;
    case 'salva-combi-capo-esa':
        { //RESOCONTO PER CAPOESATTORI & ESATTORI

//            print_r($_POST['combinazioni']);
//            die();
            if (isset($_POST['combinazioni'])) {
                if ($_POST['combinazioni'] != "") {
                    $comibinazione = $_POST['combinazioni'];
                    $svuota_resocnoto = "DELETE from collegati_esattore ";
                    db_query($svuota_resocnoto);

                    foreach ($comibinazione as $comb) {
                        $capoEsa = $comb[0];
                        $esa = explode(',', $comb[1]);
                        for ($i = 0; $i < count($esa); $i++) {
                            $query_resoconto = "INSERT INTO collegati_esattore ( id_utente, id_collegato) VALUES ('" . $capoEsa . "','" . $esa[$i] . "')";
                            db_query($query_resoconto);
                        }
                    }

                    echo 1;
                } else {
                    $svuota_resocnoto = "DELETE from collegati_esattore ";
                    db_query($svuota_resocnoto);
                    echo 2;

                }
            } else {
                die("MANCANO PARAMETRI");
            }
        }
        break;
    case 'salva-impostazione-stat-monitoraggio':
        { //RESOCONTO PER CAPOESATTORI & ESATTORI

//            print_r($_POST['combinazioni']);
//            die();
            if (isset($_POST['combinazioni'])) {
                if ($_POST['combinazioni'] != "") {
                    $comibinazione = $_POST['combinazioni'];
                    $svuota_resocnoto = "DELETE from statistica_monitoraggio_affido ";
                    db_query($svuota_resocnoto);

                    foreach ($comibinazione as $comb) {
                        $campo = $comb[0];
                        $descrizone = $comb[1];
                        $id_nota_ab = $comb[2];
                        $query_resoconto = "INSERT INTO statistica_monitoraggio_affido ( campo, descrizione,id_note_abituali) VALUES ('" . $campo . "','" . $descrizone . "','" . $id_nota_ab . "')";
                        db_query($query_resoconto);
                    }

                    echo 1;
                } else {
                    $svuota_resocnoto = "DELETE from statistica_monitoraggio_affido ";
                    db_query($svuota_resocnoto);
                    echo 2;

                }
            } else {
                die("MANCANO PARAMETRI");
            }
        }
        break;
    case 'salva-mail-resoconto':
        { //RESOCONTO MAIL PER CAPOESATTORI

            $nome = db_input($_POST['nome']);
            $mail = db_input($_POST['mail']);
            $mailcc = db_input($_POST['mailcc']);
            $mailccn = db_input($_POST['mailccn']);
            $mailogg = db_input($_POST['oggetto']);
            $mailsign = db_input($_POST['mailsign']);

            $query_imp_base = "UPDATE impostazioni_base SET mail_resoconto_firma= '" . $mailsign . "',mail_nome_resoconto='" . $nome . "',mail_mittente_resoconto='" . $mail . "',mail_cc_resoconto='" . $mailcc . "',mail_ccn_resoconto='" . $mailccn . "',mail_resoconto_oggetto='" . $mailogg . "' WHERE id=1";

            if (db_query($query_imp_base)) {
                echo 1;
            } else {
                die("salvataggio non avvenuto");
            }
        }
        break;
    //**************************************** INIZIO - EVENTI CENTRALINO ****************************************
    case 'inserimento-nuova-scadenza-phc':
        {
            $pratica = $_POST['pratica'];
            $chiamataPuntuale = $_POST['chiamataPuntuale'];
            $data = $_POST['data'];
            $ora = $_POST['ora'];
            $phc = $_POST['phc'] != '' ? $_POST['phc'] : $_SESSION['user_admin_id'];
            $gruppo = 0;
            $gruppo = $ultimaScad['id_gruppo_destinatario'];

            $queryTeamPhc = "SELECT group_concat(id_team) FROM team_composizione WHERE id_operatore=" . db_input($_SESSION['user_admin_id']);
            $teamPhc = db_fetch_array(db_query($queryTeamPhc))[0][0];
            if ($teamPhc == "") {
                $teamPhc = -1;
            }

            //$queryScadOggi =" AND  ((PS.id_destinatario = '".$_SESSION['user_admin_id']."') OR  (PS.id_gruppo_destinatario in (".$teamPhc.") )) ORDER BY IF((IFNULL(PS.chiamata_puntuale, 0) = 1
            //                                    AND data_schedulazione_alias <= NOW() AND data_schedulazione_alias >= CONCAT(CURRENT_DATE, ' ', '00:00')), 1, 0) DESC, IF((IFNULL(PS.chiamata_puntuale, 0) = 1 AND PS.data < CURRENT_DATE), 1, 0) DESC,  if(data_schedulazione_alias = '' or data_schedulazione_alias is null, 1, 0), data_schedulazione_alias ASC,  PS.nuova_pratica DESC ";

            $queryScadOggi = " SELECT PS.id, PS.id_gruppo_destinatario,PS.id_destinatario, CONCAT(PS.data, ' ', PS.ora) as data_schedulazione_alias 
                                    FROM  scadenze PS 
                                    WHERE PS.schedulazione=1 
                                    AND PS.data <= CURDATE() AND PS.data <> '' 
                                    AND  PS.id IN (SELECT id FROM scadenze WHERE stato <> 2 AND id_pratica = '" . $pratica . "')
                                    AND  (PS.stato = 0 OR PS.stato = 1) ";


            $scadenzeList = db_fetch_array(db_query($queryScadOggi));

            foreach ($scadenzeList as $scadenza) {
                if ($scadenza['id'] > 0) {
                    $evadiScad = "UPDATE scadenze SET stato = 2 WHERE id =" . $scadenza['id'];
                    db_query($evadiScad);
                }
            }

            $evadiScadenzeOperatore = "UPDATE scadenze SET stato=2 WHERE id_pratica='" . $pratica . "' AND id_destinatario='" . $phc . "' AND stato=1 AND schedulazione=1";
            db_query($evadiScadenzeOperatore);

            if ($gruppo > 0) {
                $phc = 0;
            }

            $query_dettaglio_pratica = "SELECT id_lotto_mandante, id_lotto_studio, id_mandante, id_debitore FROM pratiche WHERE id = '" . $pratica . "'";
            $ris_dettaglio_pratica = db_query($query_dettaglio_pratica);
            $dett_pratica_principale = mysql_fetch_assoc($ris_dettaglio_pratica);

            $query_pratiche = "SELECT id, id_lotto_mandante, id_lotto_studio FROM pratiche WHERE id_debitore = '" . $dett_pratica_principale['id_debitore'] . "' AND id_mandante = '" . $dett_pratica_principale['id_mandante'] . "'";
            $ris_pratiche = db_query($query_pratiche);
            if (db_num_rows($ris_pratiche) > 0) {
                while ($dett_pratica = mysql_fetch_assoc($ris_pratiche)) {
                    $pratica = $dett_pratica['id'];

                    /* $query_delete_scadenze = "DELETE FROM scadenze WHERE id_pratica = '" . db_input($pratica) . "'";
                     db_query($query_delete_scadenze);*/

                    $query_insert_scadenze = "INSERT INTO scadenze 
                                                SET id_tipo_scadenza = 2,
                                                    stato = 1,
                                                    descrizione = 'Nuova Schedulazione Pratica',
                                                    note = 'Nuova Schedulazione Pratica',
                                                    data = '" . date('Y-m-d', strtotime($data)) . "', 
                                                    ora = '" . $ora . "', 
                                                    id_mittente = '" . $_SESSION['user_admin_id'] . "',
                                                    id_destinatario = '" . $phc . "',
                                                    id_gruppo_destinatario = '" . $gruppo . "',
                                                    id_pratica = '" . $pratica . "',
                                                    id_lotto_mandante = '" . $dett_pratica['id_lotto_mandante'] . "',
                                                    chiamata_puntuale = '" . $chiamataPuntuale . "',
                                                    schedulazione = 1,
                                                    id_lotto_studio = '" . $dett_pratica['id_lotto_studio'] . "'";
                    db_query($query_insert_scadenze);

                    /* $query_update_prima_scadenza = "UPDATE pratiche SET id_tutor='" . $phc . "', data_prima_schedulazione = '" . $data . " " . $ora . "' WHERE id = '" . db_input($pratica) . "'";
                    db_query($query_update_prima_scadenza);*/
                }
            }

            $queryGetFlagAffAuto = "SELECT id from impostazioni_base where id=1 and affido_automatico=1";
            if (db_num_rows(db_query($queryGetFlagAffAuto)) > 0) {
                // AFFIDAMENTO
                $query_esistenza_affidamento_pratica = 'SELECT id_lotto_affidamento, id_contratto, data_fine_mandato
                                                                FROM pratiche 
                                                                WHERE (id_lotto_affidamento IS NULL OR id_lotto_affidamento = 0)
                                                                    AND id = "' . $_POST['pratica'] . '"';
                $ris_esistenza_affidamento_pratica = db_query($query_esistenza_affidamento_pratica);

                if (mysql_num_rows($ris_esistenza_affidamento_pratica) > 0 && $praticaDETT = mysql_fetch_array($ris_esistenza_affidamento_pratica)) {
                    $query_esistenza_lotto_affidato = 'SELECT id 
                                                                FROM affidamenti
                                                                WHERE data_affidamento = "' . date('Y-m-d') . '"
                                                                    AND id_collector = "' . $phc . '"';
                    $ris_esistenza_lotto_affidato = db_query($query_esistenza_lotto_affidato);

                    // RECUPERO TUTTI I DATI NECESSARI PER CALCOLARE LA DATA DI FINE MANDATO DEL COLLECTOR
                    $query_dettagli_collector = 'SELECT U.*, P.*, P.cat_prof AS categoria_professionale
                                                        FROM utente U LEFT JOIN phone_collector P ON U.id_utente = P.id_utente
                                                        WHERE U.id_utente = "' . $phc . '"';
                    $ris_collectorDETT = db_query($query_dettagli_collector);
                    $collectorDETT = mysql_fetch_array($ris_collectorDETT);

                    $query_dettagli_affidamento = 'SELECT giorni_lavorazione
                                                            FROM contratto_durata_affidamento
                                                            WHERE id_contratto = "' . $praticaDETT['id_contratto'] . '"
                                                            AND tipo_collaboratore = "Phone_Collector"
                                                            AND categoria = "' . $collectorDETT['categoria_professionale'] . '"
                                                            ORDER BY id DESC
                                                            LIMIT 0,1';
                    $ris_affidamentoDETT = db_query($query_dettagli_affidamento);
                    if (mysql_num_rows($ris_affidamentoDETT) > 0) {
                        $affidamentoDETT = mysql_fetch_array($ris_affidamentoDETT);
                        $giorni_lavorazione = $affidamentoDETT['giorni_lavorazione'];
                    } else {
                        $giorni_lavorazione = 0;
                    }

                    $query_dettagli_contratto = 'SELECT *
                                                        FROM contratto
                                                        WHERE id = "' . $praticaDETT['id_contratto'] . '"';
                    $ris_contrattoDETT = db_query($query_dettagli_contratto);
                    $contrattoDETT = mysql_fetch_array($ris_contrattoDETT);

                    if ($giorni_lavorazione == 0) {
                        $data_fine_mandato_collector = $praticaDETT['data_fine_mandato'];
                    } else {
                        // TRELLO#00048
                        $data_di_partenza = $data_fine_mandato_collector_calcolata = date('Y-m-d', strtotime($data_affidamento));

                        $giorni = 0;
                        $giorni_lavorativi = 0;

                        if (strpos($giorni_lavorazione, '-') === 0) {
                            for (; $giorni > -100; $giorni--) {
                                if ($giorni_lavorativi < $giorni_lavorazione) break;
                                if (date('N', strtotime($data_di_partenza . ' ' . $giorni . ' days')) < 6) $giorni_lavorativi--;
                            }
                            $data_fine_mandato_collector_calcolata = date('Y-m-d', strtotime($data_di_partenza . ' ' . ($giorni + 2) . ' days'));
                        } else {
                            for (; $giorni < 100; $giorni++) {
                                if ($giorni_lavorativi > $giorni_lavorazione) break;
                                if (date('N', strtotime($data_di_partenza . ' ' . $giorni . ' days')) < 6) $giorni_lavorativi++;
                            }
                            $data_fine_mandato_collector_calcolata = date('Y-m-d', strtotime($data_di_partenza . ' ' . ($giorni - 2) . ' days'));
                        }

                        //echo '<br>';
                        //$data_fine_mandato_collector_calcolata = date('Y-m-d',strtotime($data_affidamento.' +'.$giorni_lavorazione.' days'));
                        // FINE - TRELLO#00048

                        //echo $data_affidamento.' + '.$giorni_lavorazione.' = '.$data_fine_mandato_collector_calcolata;
                        //echo '<br>';
                        if (strpos($contrattoDETT['blocca_scadenze'], '+') >= 0 || strpos($contrattoDETT['blocca_scadenze'], '-') >= 0)
                            $giorni_blocco_scadenze = $contrattoDETT['blocca_scadenze'];
                        else
                            $giorni_blocco_scadenze = '+' . $contrattoDETT['blocca_scadenze'];

                        // TRELLO#00048
                        $data_di_partenza = $data_fine_mandato_collector_max = date('Y-m-d', strtotime($praticaDETT['data_fine_mandato']));

                        $giorni = 0;
                        $giorni_lavorativi = 0;

                        if (strpos($giorni_blocco_scadenze, '-') === 0) {
                            for (; $giorni > -100; $giorni--) {
                                if ($giorni_lavorativi < $giorni_blocco_scadenze) break;
                                if (date('N', strtotime($data_di_partenza . ' ' . $giorni . ' days')) < 6) $giorni_lavorativi--;
                            }
                            $data_fine_mandato_collector_max = date('Y-m-d', strtotime($data_di_partenza . ' ' . ($giorni + 2) . ' days'));
                        } else {
                            for (; $giorni < 100; $giorni++) {
                                if ($giorni_lavorativi > $giorni_blocco_scadenze) break;
                                if (date('N', strtotime($data_di_partenza . ' ' . $giorni . ' days')) < 6) $giorni_lavorativi++;
                            }
                            $data_fine_mandato_collector_max = date('Y-m-d', strtotime($data_di_partenza . ' ' . ($giorni - 2) . ' days'));
                        }

                        // $data_fine_mandato_collector_max = date('Y-m-d',strtotime($praticaDETT['data_fine_mandato'].' '.$giorni_blocco_scadenze.' days'));
                        // FINE - TRELLO#00048

                        //echo $praticaDETT['data_fine_mandato'].' - '.$contrattoDETT['blocca_scadenze'].' = '.$data_fine_mandato_collector_max;
                        //echo '<br>';
                        $data_fine_mandato_collector = $data_fine_mandato_collector_calcolata > $data_fine_mandato_collector_max ? $data_fine_mandato_collector_max : $data_fine_mandato_collector_calcolata;
                        //echo $data_fine_mandato_collector; die();
                    }
                    // FINE - RECUPERO TUTTI I DATI NECESSARI PER CALCOLARE LA DATA DI FINE MANDATO DEL COLLECTOR

                    if (mysql_num_rows($ris_esistenza_lotto_affidato)) {
                        $affidamento = mysql_fetch_array($ris_esistenza_lotto_affidato);
                        $id = $affidamento['id'];
                    } else {
                        $query_inserimento_affidamento = 'INSERT INTO affidamenti
                                                                SET data_affidamento = "' . date('Y-m-d') . '",
                                                                    id_collector = "' . $phc . '",
                                                                    phc = "1",
                                                                    data_creazione = "' . date('Y-m-d H:i:s') . '"';
                        $ris_inserimento_affidamento = db_query($query_inserimento_affidamento);
                        $affidamenti_creati++;
                        $id = mysql_insert_id();
                        $array_id_affidamenti[] = $id;
                    }

                    $query_inserimento_pratica = 'UPDATE pratiche
                                                            SET id_lotto_affidamento = "' . $id . '",
                                                                id_collector = "' . $phc . '",
                                                                id_anagrafica_candidato_affido = NULL
                                                            WHERE id = "' . $_POST['pratica'] . '"';
                    $ris_inserimento_pratica = db_query($query_inserimento_pratica);

                    $array_affidamenti[$id][] = $_POST['pratica'];

                    $query_inserimento_storico_pratica = 'INSERT INTO pratiche_affidamenti
                                                                    SET id_pratica = "' . $_POST['pratica'] . '",
                                                                        id_affidamento = "' . $id . '",
                                                                        data_fine_mandato_collector = "' . $data_fine_mandato_collector . '"';
                    $ris_inserimento_storico_pratica = db_query($query_inserimento_storico_pratica);

                    $pratiche_elaborate++;
                    $elenco_pratiche_affidate_successo .= $_POST['pratica'] . ',';
                }
            }

            echo 'OK';
            die();
        }
        break;

    case 'non-risponde-phc':
        {
            $pratica = $_POST['pratica'];
            $data = date('Y-m-d', strtotime('+1 day'));
            $ora = '09:00';
            $phc = $_SESSION['user_admin_id'];

            imposta_esito_pratica_centralino($pratica, 5, $phc);

            $queryGetFlagAffAuto = "SELECT id from impostazioni_base where id=1 and affido_automatico=1";
            if (db_num_rows(db_query($queryGetFlagAffAuto)) > 0) {
                $query_esistenza_affidamento_pratica = 'SELECT id_lotto_affidamento, id_contratto, data_fine_mandato
                                                        FROM pratiche 
                                                        WHERE (id_lotto_affidamento IS NULL OR id_lotto_affidamento = 0)
                                                            AND id = "' . $pratica . '"';
                $ris_esistenza_affidamento_pratica = db_query($query_esistenza_affidamento_pratica);

                if (mysql_num_rows($ris_esistenza_affidamento_pratica) > 0 && $praticaDETT = mysql_fetch_array($ris_esistenza_affidamento_pratica)) {
                    $query_esistenza_lotto_affidato = 'SELECT id 
                                                        FROM affidamenti
                                                        WHERE data_affidamento = "' . date('Y-m-d') . '"
                                                            AND id_collector = "' . $phc . '"';
                    $ris_esistenza_lotto_affidato = db_query($query_esistenza_lotto_affidato);

                    // RECUPERO TUTTI I DATI NECESSARI PER CALCOLARE LA DATA DI FINE MANDATO DEL COLLECTOR
                    $query_dettagli_collector = 'SELECT U.*, P.*, P.cat_prof AS categoria_professionale
                                                FROM utente U LEFT JOIN phone_collector P ON U.id_utente = P.id_utente
                                                WHERE U.id_utente = "' . $phc . '"';
                    $ris_collectorDETT = db_query($query_dettagli_collector);
                    $collectorDETT = mysql_fetch_array($ris_collectorDETT);

                    $query_dettagli_affidamento = 'SELECT giorni_lavorazione
                                                    FROM contratto_durata_affidamento
                                                    WHERE id_contratto = "' . $praticaDETT['id_contratto'] . '"
                                                    AND tipo_collaboratore = "Phone_Collector"
                                                    AND categoria = "' . $collectorDETT['categoria_professionale'] . '"
                                                    ORDER BY id DESC
                                                    LIMIT 0,1';
                    $ris_affidamentoDETT = db_query($query_dettagli_affidamento);
                    if (mysql_num_rows($ris_affidamentoDETT) > 0) {
                        $affidamentoDETT = mysql_fetch_array($ris_affidamentoDETT);
                        $giorni_lavorazione = $affidamentoDETT['giorni_lavorazione'];
                    } else {
                        $giorni_lavorazione = 0;
                    }

                    $query_dettagli_contratto = 'SELECT *
                                                FROM contratto
                                                WHERE id = "' . $praticaDETT['id_contratto'] . '"';
                    $ris_contrattoDETT = db_query($query_dettagli_contratto);
                    $contrattoDETT = mysql_fetch_array($ris_contrattoDETT);

                    if ($giorni_lavorazione == 0) {
                        $data_fine_mandato_collector = $praticaDETT['data_fine_mandato'];
                    } else {
                        // TRELLO#00048
                        $data_di_partenza = $data_fine_mandato_collector_calcolata = date('Y-m-d', strtotime($data_affidamento));

                        $giorni = 0;
                        $giorni_lavorativi = 0;

                        if (strpos($giorni_lavorazione, '-') === 0) {
                            for (; $giorni > -100; $giorni--) {
                                if ($giorni_lavorativi < $giorni_lavorazione) break;
                                if (date('N', strtotime($data_di_partenza . ' ' . $giorni . ' days')) < 6) $giorni_lavorativi--;
                            }
                            $data_fine_mandato_collector_calcolata = date('Y-m-d', strtotime($data_di_partenza . ' ' . ($giorni + 2) . ' days'));
                        } else {
                            for (; $giorni < 100; $giorni++) {
                                if ($giorni_lavorativi > $giorni_lavorazione) break;
                                if (date('N', strtotime($data_di_partenza . ' ' . $giorni . ' days')) < 6) $giorni_lavorativi++;
                            }
                            $data_fine_mandato_collector_calcolata = date('Y-m-d', strtotime($data_di_partenza . ' ' . ($giorni - 2) . ' days'));
                        }

                        //echo '<br>';
                        //$data_fine_mandato_collector_calcolata = date('Y-m-d',strtotime($data_affidamento.' +'.$giorni_lavorazione.' days'));
                        // FINE - TRELLO#00048

                        //echo $data_affidamento.' + '.$giorni_lavorazione.' = '.$data_fine_mandato_collector_calcolata;
                        //echo '<br>';
                        if (strpos($contrattoDETT['blocca_scadenze'], '+') >= 0 || strpos($contrattoDETT['blocca_scadenze'], '-') >= 0)
                            $giorni_blocco_scadenze = $contrattoDETT['blocca_scadenze'];
                        else
                            $giorni_blocco_scadenze = '+' . $contrattoDETT['blocca_scadenze'];

                        // TRELLO#00048
                        $data_di_partenza = $data_fine_mandato_collector_max = date('Y-m-d', strtotime($praticaDETT['data_fine_mandato']));

                        $giorni = 0;
                        $giorni_lavorativi = 0;

                        if (strpos($giorni_blocco_scadenze, '-') === 0) {
                            for (; $giorni > -100; $giorni--) {
                                if ($giorni_lavorativi < $giorni_blocco_scadenze) break;
                                if (date('N', strtotime($data_di_partenza . ' ' . $giorni . ' days')) < 6) $giorni_lavorativi--;
                            }
                            $data_fine_mandato_collector_max = date('Y-m-d', strtotime($data_di_partenza . ' ' . ($giorni + 2) . ' days'));
                        } else {
                            for (; $giorni < 100; $giorni++) {
                                if ($giorni_lavorativi > $giorni_blocco_scadenze) break;
                                if (date('N', strtotime($data_di_partenza . ' ' . $giorni . ' days')) < 6) $giorni_lavorativi++;
                            }
                            $data_fine_mandato_collector_max = date('Y-m-d', strtotime($data_di_partenza . ' ' . ($giorni - 2) . ' days'));
                        }

                        // $data_fine_mandato_collector_max = date('Y-m-d',strtotime($praticaDETT['data_fine_mandato'].' '.$giorni_blocco_scadenze.' days'));
                        // FINE - TRELLO#00048

                        //echo $praticaDETT['data_fine_mandato'].' - '.$contrattoDETT['blocca_scadenze'].' = '.$data_fine_mandato_collector_max;
                        //echo '<br>';
                        $data_fine_mandato_collector = $data_fine_mandato_collector_calcolata > $data_fine_mandato_collector_max ? $data_fine_mandato_collector_max : $data_fine_mandato_collector_calcolata;
                        //echo $data_fine_mandato_collector; die();
                    }
                    // FINE - RECUPERO TUTTI I DATI NECESSARI PER CALCOLARE LA DATA DI FINE MANDATO DEL COLLECTOR

                    if (mysql_num_rows($ris_esistenza_lotto_affidato)) {
                        $affidamento = mysql_fetch_array($ris_esistenza_lotto_affidato);
                        $id = $affidamento['id'];
                    } else {
                        $query_inserimento_affidamento = 'INSERT INTO affidamenti
                                                        SET data_affidamento = "' . date('Y-m-d') . '",
                                                            id_collector = "' . $phc . '",
                                                            phc = "1",
                                                            data_creazione = "' . date('Y-m-d H:i:s') . '"';
                        $ris_inserimento_affidamento = db_query($query_inserimento_affidamento);
                        $affidamenti_creati++;
                        $id = mysql_insert_id();
                        $array_id_affidamenti[] = $id;
                    }

                    $query_inserimento_pratica = 'UPDATE pratiche
                                                SET id_lotto_affidamento = "' . $id . '",
                                                    id_collector = "' . $phc . '",
                                                    id_anagrafica_candidato_affido = NULL
                                                WHERE id = "' . $pratica . '"';
                    $ris_inserimento_pratica = db_query($query_inserimento_pratica);

                    $array_affidamenti[$id][] = $pratica;

                    $query_inserimento_storico_pratica = 'INSERT INTO pratiche_affidamenti
                                                        SET id_pratica = "' . $pratica . '",
                                                            id_affidamento = "' . $id . '",
                                                            data_fine_mandato_collector = "' . $data_fine_mandato_collector . '"';
                    $ris_inserimento_storico_pratica = db_query($query_inserimento_storico_pratica);

                    $pratiche_elaborate++;
                    $elenco_pratiche_affidate_successo .= $pratica . ',';
                }
            }
            echo 'OK';
            die();
        }
        break;
    case 'occupato-phc':
        {
            $pratica = $_POST['pratica'];
            $data = date('Y-m-d', strtotime('+1 day'));
            $ora = '09:00';
            $phc = $_SESSION['user_admin_id'];

            imposta_esito_pratica_centralino($pratica, 7, $phc);

            $queryGetFlagAffAuto = "SELECT id from impostazioni_base where id=1 and affido_automatico=1";
            if (db_num_rows(db_query($queryGetFlagAffAuto)) > 0) {
                // AFFIDAMENTO
                $query_esistenza_affidamento_pratica = 'SELECT id_lotto_affidamento, id_contratto, data_fine_mandato
                                                        FROM pratiche 
                                                        WHERE (id_lotto_affidamento IS NULL OR id_lotto_affidamento = 0)
                                                            AND id = "' . $pratica . '"';
                $ris_esistenza_affidamento_pratica = db_query($query_esistenza_affidamento_pratica);

                if (mysql_num_rows($ris_esistenza_affidamento_pratica) > 0 && $praticaDETT = mysql_fetch_array($ris_esistenza_affidamento_pratica)) {
                    $query_esistenza_lotto_affidato = 'SELECT id 
                                                        FROM affidamenti
                                                        WHERE data_affidamento = "' . date('Y-m-d') . '"
                                                            AND id_collector = "' . $phc . '"';
                    $ris_esistenza_lotto_affidato = db_query($query_esistenza_lotto_affidato);

                    // RECUPERO TUTTI I DATI NECESSARI PER CALCOLARE LA DATA DI FINE MANDATO DEL COLLECTOR
                    $query_dettagli_collector = 'SELECT U.*, P.*, P.cat_prof AS categoria_professionale
                                                FROM utente U LEFT JOIN phone_collector P ON U.id_utente = P.id_utente
                                                WHERE U.id_utente = "' . $phc . '"';
                    $ris_collectorDETT = db_query($query_dettagli_collector);
                    $collectorDETT = mysql_fetch_array($ris_collectorDETT);

                    $query_dettagli_affidamento = 'SELECT giorni_lavorazione
                                                    FROM contratto_durata_affidamento
                                                    WHERE id_contratto = "' . $praticaDETT['id_contratto'] . '"
                                                    AND tipo_collaboratore = "Phone_Collector"
                                                    AND categoria = "' . $collectorDETT['categoria_professionale'] . '"
                                                    ORDER BY id DESC
                                                    LIMIT 0,1';
                    $ris_affidamentoDETT = db_query($query_dettagli_affidamento);
                    if (mysql_num_rows($ris_affidamentoDETT) > 0) {
                        $affidamentoDETT = mysql_fetch_array($ris_affidamentoDETT);
                        $giorni_lavorazione = $affidamentoDETT['giorni_lavorazione'];
                    } else {
                        $giorni_lavorazione = 0;
                    }

                    $query_dettagli_contratto = 'SELECT *
                                                FROM contratto
                                                WHERE id = "' . $praticaDETT['id_contratto'] . '"';
                    $ris_contrattoDETT = db_query($query_dettagli_contratto);
                    $contrattoDETT = mysql_fetch_array($ris_contrattoDETT);

                    if ($giorni_lavorazione == 0) {
                        $data_fine_mandato_collector = $praticaDETT['data_fine_mandato'];
                    } else {
                        // TRELLO#00048
                        $data_di_partenza = $data_fine_mandato_collector_calcolata = date('Y-m-d', strtotime($data_affidamento));

                        $giorni = 0;
                        $giorni_lavorativi = 0;

                        if (strpos($giorni_lavorazione, '-') === 0) {
                            for (; $giorni > -100; $giorni--) {
                                if ($giorni_lavorativi < $giorni_lavorazione) break;
                                if (date('N', strtotime($data_di_partenza . ' ' . $giorni . ' days')) < 6) $giorni_lavorativi--;
                            }
                            $data_fine_mandato_collector_calcolata = date('Y-m-d', strtotime($data_di_partenza . ' ' . ($giorni + 2) . ' days'));
                        } else {
                            for (; $giorni < 100; $giorni++) {
                                if ($giorni_lavorativi > $giorni_lavorazione) break;
                                if (date('N', strtotime($data_di_partenza . ' ' . $giorni . ' days')) < 6) $giorni_lavorativi++;
                            }
                            $data_fine_mandato_collector_calcolata = date('Y-m-d', strtotime($data_di_partenza . ' ' . ($giorni - 2) . ' days'));
                        }

                        //echo '<br>';
                        //$data_fine_mandato_collector_calcolata = date('Y-m-d',strtotime($data_affidamento.' +'.$giorni_lavorazione.' days'));
                        // FINE - TRELLO#00048

                        //echo $data_affidamento.' + '.$giorni_lavorazione.' = '.$data_fine_mandato_collector_calcolata;
                        //echo '<br>';
                        if (strpos($contrattoDETT['blocca_scadenze'], '+') >= 0 || strpos($contrattoDETT['blocca_scadenze'], '-') >= 0)
                            $giorni_blocco_scadenze = $contrattoDETT['blocca_scadenze'];
                        else
                            $giorni_blocco_scadenze = '+' . $contrattoDETT['blocca_scadenze'];

                        // TRELLO#00048
                        $data_di_partenza = $data_fine_mandato_collector_max = date('Y-m-d', strtotime($praticaDETT['data_fine_mandato']));

                        $giorni = 0;
                        $giorni_lavorativi = 0;

                        if (strpos($giorni_blocco_scadenze, '-') === 0) {
                            for (; $giorni > -100; $giorni--) {
                                if ($giorni_lavorativi < $giorni_blocco_scadenze) break;
                                if (date('N', strtotime($data_di_partenza . ' ' . $giorni . ' days')) < 6) $giorni_lavorativi--;
                            }
                            $data_fine_mandato_collector_max = date('Y-m-d', strtotime($data_di_partenza . ' ' . ($giorni + 2) . ' days'));
                        } else {
                            for (; $giorni < 100; $giorni++) {
                                if ($giorni_lavorativi > $giorni_blocco_scadenze) break;
                                if (date('N', strtotime($data_di_partenza . ' ' . $giorni . ' days')) < 6) $giorni_lavorativi++;
                            }
                            $data_fine_mandato_collector_max = date('Y-m-d', strtotime($data_di_partenza . ' ' . ($giorni - 2) . ' days'));
                        }

                        // $data_fine_mandato_collector_max = date('Y-m-d',strtotime($praticaDETT['data_fine_mandato'].' '.$giorni_blocco_scadenze.' days'));
                        // FINE - TRELLO#00048

                        //echo $praticaDETT['data_fine_mandato'].' - '.$contrattoDETT['blocca_scadenze'].' = '.$data_fine_mandato_collector_max;
                        //echo '<br>';
                        $data_fine_mandato_collector = $data_fine_mandato_collector_calcolata > $data_fine_mandato_collector_max ? $data_fine_mandato_collector_max : $data_fine_mandato_collector_calcolata;
                        //echo $data_fine_mandato_collector; die();
                    }
                    // FINE - RECUPERO TUTTI I DATI NECESSARI PER CALCOLARE LA DATA DI FINE MANDATO DEL COLLECTOR

                    if (mysql_num_rows($ris_esistenza_lotto_affidato)) {
                        $affidamento = mysql_fetch_array($ris_esistenza_lotto_affidato);
                        $id = $affidamento['id'];
                    } else {
                        $query_inserimento_affidamento = 'INSERT INTO affidamenti
                                                        SET data_affidamento = "' . date('Y-m-d') . '",
                                                            id_collector = "' . $phc . '",
                                                            phc = "1",
                                                            data_creazione = "' . date('Y-m-d H:i:s') . '"';
                        $ris_inserimento_affidamento = db_query($query_inserimento_affidamento);
                        $affidamenti_creati++;
                        $id = mysql_insert_id();
                        $array_id_affidamenti[] = $id;
                    }

                    $query_inserimento_pratica = 'UPDATE pratiche
                                                SET id_lotto_affidamento = "' . $id . '",
                                                    id_collector = "' . $phc . '",
                                                    id_anagrafica_candidato_affido = NULL
                                                WHERE id = "' . $pratica . '"';
                    $ris_inserimento_pratica = db_query($query_inserimento_pratica);

                    $array_affidamenti[$id][] = $pratica;

                    $query_inserimento_storico_pratica = 'INSERT INTO pratiche_affidamenti
                                                        SET id_pratica = "' . $pratica . '",
                                                            id_affidamento = "' . $id . '",
                                                            data_fine_mandato_collector = "' . $data_fine_mandato_collector . '"';
                    $ris_inserimento_storico_pratica = db_query($query_inserimento_storico_pratica);

                    $pratiche_elaborate++;
                    $elenco_pratiche_affidate_successo .= $pratica . ',';
                }
            }
            echo 'OK';
            die();
        }
        break;

    case 'chiusura-contatto-phc':
        {
            $pratica = $_POST['pratica'];

            $query_dettaglio_pratica = "SELECT id_lotto_mandante, id_lotto_studio, id_mandante, id_debitore FROM pratiche WHERE id = '" . $pratica . "'";
            $ris_dettaglio_pratica = db_query($query_dettaglio_pratica);
            $dett_pratica_principale = mysql_fetch_assoc($ris_dettaglio_pratica);

            $query_pratiche = "SELECT id, id_lotto_mandante, id_lotto_studio FROM pratiche WHERE id_debitore = '" . $dett_pratica_principale['id_debitore'] . "' AND id_mandante = '" . $dett_pratica_principale['id_mandante'] . "'";
            $ris_pratiche = db_query($query_pratiche);
            if (db_num_rows($ris_pratiche) > 0) {
                while ($dett_pratica = mysql_fetch_assoc($ris_pratiche)) {
                    $pratica = $dett_pratica['id'];


                    $queryScad = " SELECT PS.id, PS.id_gruppo_destinatario,PS.id_destinatario, CONCAT(PS.data, ' ', PS.ora) as data_schedulazione_alias 
                                    FROM  scadenze PS 
                                    WHERE PS.schedulazione=1 
                                    AND  PS.id IN (SELECT id FROM scadenze WHERE stato <> 2 AND id_pratica = '" . db_input($pratica) . "')
                                    AND  (PS.stato = 0 OR PS.stato = 1) ";

                    $scadenzeList = db_fetch_array(db_query($queryScad));

                    foreach ($scadenzeList as $scadenza) {
                        if ($scadenza['id'] > 0) {
                            $evadiScad = "UPDATE scadenze SET stato = 2 WHERE id =" . $scadenza['id'];
                            db_query($evadiScad);
                        }
                    }

                    /*
                      $query_delete_scadenze = "DELETE FROM scadenze WHERE id_pratica = '" . db_input($pratica) . "'";
                      db_query($query_delete_scadenze);*/

                    /* $query_update_prima_scadenza = "UPDATE pratiche SET data_prima_schedulazione = NULL WHERE id = '" . db_input($pratica) . "'";
                     db_query($query_update_prima_scadenza);*/

                    imposta_esito_pratica_centralino($pratica, 6, $phc);
                }
            }

            echo 'OK';
            die();
        }
        break;

    case 'aggiorna-operatore-predictive':
        {

            $query_update_operatore = 'UPDATE centralino_chiamate 
                                        SET id_operatore = "' . db_input($_SESSION['user_admin_id']) . '", 
                                            interno_operatore = "' . db_input($_POST['operator']) . '",
                                            stato="999" 
                                        WHERE tel_primario = "' . db_input(str_replace('+39', '', $_POST['number'])) . '" 
                                            AND campaign <> "" 
                                        ORDER BY id DESC 
                                        LIMIT 1';
            db_query($query_update_operatore);

            echo $query_update_operatore;

            die();
        }
        break;
    case 'statistica-chiamate-abbattute':
        {
            $campaignID = $_POST['campaignID'];
            $membersCount = $_POST['members'];
            $totalMembersCount = $_POST['totalMembers'];
            $reserv = $_POST['reserv'];

            $percDefault = 10 / 100;
            $perc1 = 7 / 100;
            $perc2 = 4 / 100;
            $perc3 = 0 / 100;
            $limitStartCheck = $membersCount * 3;

            $query_chiamate_effettuate = 'SELECT COUNT(id) TOT
                                        FROM centralino_chiamate
                                        WHERE (stato = 999 OR stato = 0)
                                          AND campaign = "' . $campaignID . '"';
            $ris_chiamate_effettuate = db_query($query_chiamate_effettuate);
            $row_chiamate_effettuate = mysql_fetch_assoc($ris_chiamate_effettuate);
            $chiamate_effettuate = $row_chiamate_effettuate['TOT'];

            if ($chiamate_effettuate <= $limitStartCheck) {
                echo ceil($totalMembersCount * $percDefault);
                die();
            }

            $query_chiamate_abbattute = 'SELECT COUNT(id) TOT
                                        FROM centralino_chiamate
                                        WHERE stato = 0
                                          AND campaign = "' . $campaignID . '"';
            $ris_chiamate_abbattute = db_query($query_chiamate_abbattute);
            $row_chiamate_abbattute = mysql_fetch_assoc($ris_chiamate_abbattute);
            $chiamate_abbattute = $row_chiamate_abbattute['TOT'];

            $percAbbattute = $chiamate_abbattute * 100 / $chiamate_effettuate;

            if ($percAbbattute < 0.7) {
                echo($reserv + 1);
            } else if ($percAbbattute > 0.7) {
                echo floor($totalMembersCount * $perc1);
            } else if ($percAbbattute > 1.5) {
                echo floor($totalMembersCount * $perc2);
            } else if ($percAbbattute > 2.5) {
                echo floor($totalMembersCount * $perc3);
            } else {
                echo ceil($totalMembersCount * $percDefault);
            }
            die();
        }
        break;
    //***************************************** FINE - EVENTI CENTRALINO *****************************************
    case 'get-lista-pratiche-phc':
        {

            echo $_SESSION['lista_pratiche_phc'];
            die();
        }
        break;
    case  'id-export-trasmettere':
        {
            $id_pratica = $_POST['id_pratica'];
            if ($_POST['chekbox'] == 1) {
                $id_export = 0;
            } else {
                $id_export = -1;
            }

            $query_update_id_export = "update pratiche set id_export ='" . $id_export . "' where id ='" . $id_pratica . "'";
            db_query($query_update_id_export);

            echo 'OK';
            die();
        }
        break;
    case  'put-in-session':
        {
            $_SESSION[$_POST['label']] = $_POST['values'];
            die();
        }
        break;
    case 'cambia-esito-insoluto':
        {
            $query_cambio_esito = "UPDATE pratiche_insoluto 
                                  SET id_esito = '" . db_input($_POST['id_esito']) . "' 
                                    WHERE id = '" . db_input($_POST['id_insoluto']) . "'";
            if (db_query($query_cambio_esito))
                echo 'OK';
            else
                echo 'KO';

        }
        break;
    case 'cambia-esito-recuperato':
        {
            $query_cambio_esito = "UPDATE pratiche_recuperato 
                                  SET 	esito_recuperato = '" . db_input($_POST['id_esito']) . "' 
                                    WHERE id = '" . db_input($_POST['id_recuperato']) . "'";
            if (db_query($query_cambio_esito)) {

                if (isset($_POST['id_esito']) && $_POST['id_esito'] > 0) {

                    $id_prt = db_fetch_array_assoc(db_query("SELECT id_pratica FROM pratiche_recuperato WHERE id='" . db_input($_POST['id_recuperato']) . "'"))[0]['id_pratica'];

                    $risGetNewEvento = db_query("SELECT evento_remida,insoluto FROM esiti_recuperato WHERE id='" . $_POST['id_esito'] . "'");
                    if (db_num_rows($risGetNewEvento) > 0) {
                        $newEvento = db_fetch_array_assoc($risGetNewEvento)[0];

                        if ($newEvento['insoluto'] == 1) {
                            db_query("UPDATE pratiche_recuperato SET buon_fine=0, insoluta=1 WHERE id='" . db_input($_POST['id_recuperato']) . "'");
                        }

                        if (isset($newEvento['evento_remida']) && $newEvento['evento_remida'] > 0) {
                            esegui_evento_strutturato($id_prt, $newEvento['evento_remida']);
                        }
                    }
                }

                $querGetRecCollegato = "SELECT tipo,id_pdp_associata,id_pratica FROM pratiche_recuperato WHERE id='" . db_input($_POST['id_recuperato']) . "' ";
                $recCollegato = db_fetch_array_assoc(db_query($querGetRecCollegato))[0];
                if (verificaGestioneNPL($recCollegato['id_pratica'])) {
                    if ($recCollegato['tipo'] == "PDP") {

                        $recNew = db_fetch_array_assoc(db_query("SELECT id FROM pratiche_recuperato WHERE id_pdp_associata='" . $_POST['id_recuperato'] . "'"))[0];
                        if ($recNew['id'] > 0) {
                            $query_cambio_esito = "UPDATE pratiche_recuperato SET esito_recuperato = '" . db_input($_POST['id_esito']) . "' WHERE id = '" . db_input($recNew['id']) . "'";
                            db_query($query_cambio_esito);
                        }
                    } else {
                        if ($recCollegato['id_pdp_associata'] > 0) {
                            $query_cambio_esito = "UPDATE pratiche_recuperato SET esito_recuperato = '" . db_input($_POST['id_esito']) . "' WHERE id = '" . db_input($recCollegato['id_pdp_associata']) . "'";
                            db_query($query_cambio_esito);
                        }
                    }
                }


                echo 'OK';
            } else
                echo 'KO';

        }
        break;
    case 'cambia-note-insoluto':
        {
            $query_cambio_esito = "UPDATE pratiche_insoluto 
                                  SET note = '" . db_input($_POST['note']) . "' 
                                    WHERE id = '" . db_input($_POST['id_insoluto']) . "'";
            if (db_query($query_cambio_esito))
                echo 'OK';
            else
                echo 'KO';

        }
        break;
    case 'verifica-accettazione-pratiche':
        { // TRELLO #00151
            $id_utente = $_POST['id_utente'];
            $query_accettazione = "SELECT IFNULL(count(PA.id_pratica),0) as pratiche_da_accettare FROM pratiche_affidamenti  
                              PA join affidamenti A on PA.id_affidamento = A.id where A.id_collector = '" . $id_utente . "' AND PA.accettazione = 0";
            $ris = db_query($query_accettazione);
            $num_pratiche = mysql_fetch_assoc($ris);
            echo json_encode($num_pratiche['pratiche_da_accettare']);
            die();

        }
        break;
    case 'accetta-pratiche':
        { // TRELLO #00151
            $id_utente = $_SESSION['user_admin_id'];
            $query_accettazione = "UPDATE pratiche_affidamenti SET accettazione=1 WHERE id_affidamento in (SELECT id from affidamenti where id_collector = '" . $_SESSION['user_admin_id'] . "' )";
            if (db_query($query_accettazione)) {
                echo json_encode(1);
            } else {
                echo json_encode(0);
            }

            die();
        }
        break;
    case 'verifica-accettazione-privacy':
        {
            $id_utente = $_SESSION['user_admin_id'];
            $query_accettazione_privacy = "SELECT privacy FROM utente where id_utente = '" . $id_utente . "'";
            $ris = db_query($query_accettazione_privacy);
            $privacy = mysql_fetch_assoc($ris);
            echo json_encode($privacy['privacy']);
            die();

        }
        break;
    case 'accetta-privacy':
        {
            $id_utente = $_SESSION['user_admin_id'];
            $query_accettazione_privacy = "UPDATE utente SET privacy=1 WHERE  id_utente = '" . $id_utente . "'";
            if (db_query($query_accettazione_privacy)) {
                echo json_encode(1);
            } else {
                echo json_encode(0);
            }
            die();
        }
        break;
    case 'aggiorna-pagante-pdr':
        {
            $id_utente = $_POST['utente'];
            $id_pdr = $_POST['id_pdr'];
            $query_pdr = "UPDATE piani_di_rientro SET id_utente_debitore='" . db_input($id_utente) . "',id_operatore='" . db_input($_SESSION['user_admin_id']) . "' WHERE id='" . db_input($id_pdr) . "'";
            $query_recuperati = "UPDATE pratiche_recuperato SET id_anagrafica_pagante='" . db_input($id_utente) . "' WHERE id_piano_rientro='" . db_input($id_pdr) . "'";
            if (db_query($query_pdr)) {
                if (db_query($query_recuperati)) {
                    echo json_encode(1);
                } else {
                    echo json_encode(0);

                }
            } else {
                echo json_encode(0);
            }

            die();
        }
        break;
    case 'aggiorna-collector-pdr':
        {
            $id_utente = $_POST['utente'];
            $id_pdr = $_POST['id_pdr'];
            $query_pdr = "UPDATE piani_di_rientro SET id_utente_collector='" . db_input($id_utente) . "',id_operatore='" . db_input($_SESSION['user_admin_id']) . "' WHERE id='" . db_input($id_pdr) . "'";
            $query_recuperati = "UPDATE pratiche_recuperato SET id_collector='" . db_input($id_utente) . "',id_operatore='" . db_input($_SESSION['user_admin_id']) . "' WHERE id_piano_rientro='" . db_input($id_pdr) . "'";
            if (db_query($query_pdr)) {
                if (db_query($query_recuperati)) {
                    echo json_encode(1);
                } else {
                    echo json_encode(0);

                }
            } else {
                echo json_encode(0);
            }

            die();
        }
        break;
    //////////////////////////////////////////// TRELLO #00164 ///////////////////////////////////////////////////////////////////////////////////////
    case 'verifica-compenso-aggiuntivo':
        {
            $id_pratica = $_POST['id_pratica'];
            if (verifica_prime_tre_rate_pratica($id_pratica)) {
                echo 1;
            } else {
                echo 0;
            }

            die();
        }
        break;
    //////////////////////////////////////////// TRELLO #00165 ///////////////////////////////////////////////////////////////////////////////////////
    case 'inserisci-aui-data-esecuzione':
        {
            $dataInizio = $_POST['data_inzio'];
            $dataFine = $_POST['data_fine'];
            $inserisci_data_aui = "INSERT INTO `aui_report_esecuzione`(`data_esecuzione`, `data_inizio`, `data_fine`, `id_operatore`) VALUES (NOW(),'" . $dataInizio . "','" . $dataFine . "','" . $_SESSION['user_admin_id'] . "')";
            if (db_query($inserisci_data_aui)) {
                echo 1;
            } else {
                echo 0;
            }

            die();
        }
        break;
    case 'imposta-flag-statistiche':
        {
            $valore_flag = $_POST['flag'];
            $id_pratica = $_POST['id_pratica'];
            $update_pratiche_flag = "UPDATE pratiche SET esito_statistiche='" . db_input($valore_flag) . "' WHERE id = '" . $id_pratica . "'";
            if (db_query($update_pratiche_flag)) {
                echo 1;
            } else {
                echo 0;
            }

            die();
        }
        break;
    case 'stato-accettazione-pratica':
        {
            $mysql = "SELECT *
                        FROM pratiche 
                        WHERE id = '" . $_POST['id'] . "'";
            $ris = db_query($mysql);

            $result = mysql_fetch_array($ris);

            print_r(json_encode($result));
            die();
        }
        break;
    //***************************************** EVENTI SCHEDULATI *****************************************
    case 'elimina-sms-schedulato':
        {
            if (isset($_POST['id']) && $_POST['id'] != "") {

                $id_elemento = $_POST['id'];
                $queryDel = "Delete from eventi_pratiche_sms_schedulati where id = '" . db_input($id_elemento) . "'";
                $result = db_query($queryDel);
                echo $result;
            }
        }
        break;
    case 'elimina-mail-schedulata':
        {

            if (isset($_POST['id']) && $_POST['id'] != "") {

                $id_elemento = $_POST['id'];
                $queryDel = "Delete from eventi_pratiche_mail_schedulate where id = '" . db_input($id_elemento) . "'";
                $result = db_query($queryDel);
                echo $result;
            }
        }
        break;
    case 'elimina-mail-sms-schedulata':
        {

            if (isset($_POST['id']) && $_POST['id'] != "") {

                $id_elemento = $_POST['id'];
                $queryDel = "Delete from eventi_pratiche_mail_schedulate_sms where id = '" . db_input($id_elemento) . "'";
                $result = db_query($queryDel);
                echo $result;
            }
        }
        break;
    case 'carica-mail-allegato-sessione':
        {

            if (isset($_POST['id']) && $_POST['id'] != "") {

                $id_elemento = $_POST['id'];
                $queryDel = "SELECT file_allegato from eventi_pratiche_mail_schedulate where id = '" . db_input($id_elemento) . "'";
                $result = db_fetch_array(db_query($queryDel))[0]['file_allegato'];

                writeSession('contenutoAllegatoMail', $result);

                echo $result;
            }
        }
        break;
    //***************************************** GESTIONE AFFIDAMENTI PHC *****************************************
    case 'filtro-generazione-affidamento-phc':
        {
            $ruoloUtente = $_SESSION['user_role'];

            # CREAZIONE DELLA QUERY BASE DI RECUPERO PRATICHE AFFIDABILI
            # I CRITERI DI AFFIDABILITà SONO:
            # 1 - LA PRATICA NON DEVE ESSERE STATA SCARICATA 							=> scaricata = 0 OR scaricata IS NULL
            # 2 - LA PRATICA NON DEVE ESSERE STATA ARCHIVIATA 							=> archiviata = 0 OR archiviata IS NULL
            # 3 - LA PRATICA DEVE AVERE COME POSIZIONE "AZIENDA"						=> area_corrente = 2
            # 4 - LA PRATICA NON DEVE AVERE UNO ESITO									=> esito_corrente IS NULL
            # 5 - LA PRATICA NON DEVE ESSERE GIà STATA AFFIDATA AD UN COLLECTOR			=> id_lotto_affidamento IS NULL
            # 6 - LA PRATICA DEVE AVERE UNO STATO COMPATIBILE CON L'AFFIDAMENTO			=> consenti_affidamento = 1

            # 7 - SE L'UTENTE CONNESSO è UN CAPOAREA COLLECTOR VERIFICARE TIPOLOGIA DI CREDITO
            # 8 - SE L'UTENTE CONNESSO è UN CAPOAREA COLLECTOR VERIFICARE LA ZONA ESATTIVA DI COMPETENZA

            if ($ruoloUtente == CAPO_ESATTORE) {
                $query = 'SELECT group_concat(P.id) as ids, count(P.id) as tot_pratiche, FORMAT(SUM(P.affidato_capitale+P.affidato_spese+P.affidato_interessi+P.affidato_1+P.affidato_2+P.affidato_3+P.competenze_oneri_recupero+P.competenze_spese_incasso),2,"de_DE") totaleAffidato,FORMAT(SUM(P.cash_balance),2,"de_DE") as cashBalance,FORMAT(SUM(P.affidato_capitale),2,"de_DE") as capitaleAffidato,FORMAT(SUM(P.affidato_interessi),2,"de_DE") as interessiAffidati,FORMAT(SUM(P.affidato_spese),2,"de_DE") as speseAffidate,FORMAT(SUM(P.affidato_1),2,"de_DE") as affidato1,FORMAT(SUM(P.affidato_2),2,"de_DE") as affidato2,FORMAT(SUM(P.affidato_3),2,"de_DE") as affidato3
						FROM pratiche P
                        LEFT JOIN utente Ud ON P.id_debitore = Ud.id_utente
                        LEFT JOIN utente Um ON P.id_mandante = Um.id_utente
                        LEFT JOIN lotti_mandante L ON P.id_lotto_mandante = L.id
                        LEFT JOIN stati_pratiche S ON P.stato_corrente = S.id
                        LEFT JOIN stato_pratica St ON S.classe_stato_pratica = St.id
                        LEFT JOIN contratto C ON P.id_contratto = C.id
                        LEFT JOIN recapito R ON (P.id_debitore = R.id_utente AND R.predefinito = 1) 
						WHERE (scaricata = 0 OR scaricata IS NULL)
							AND (archiviata = 0 OR archiviata IS NULL)
							AND area_corrente = 9
							AND (esito_corrente IS NULL OR esito_corrente <= 0)
							AND (id_collector IS NULL OR id_collector = 0)
							AND consenti_affidamento = 1';
                // RIMOSSA LA CONDIZIONE: AND (id_lotto_affidamento IS NULL OR id_lotto_affidamento = 0)
            } else if ($ruoloUtente == CAPO_PHONE_COLLECTOR) {
                $query = 'SELECT group_concat(P.id) as ids, count(P.id) as tot_pratiche, FORMAT(SUM(P.affidato_capitale+P.affidato_spese+P.affidato_interessi+P.affidato_1+P.affidato_2+P.affidato_3+P.competenze_oneri_recupero+P.competenze_spese_incasso),2,"de_DE") totaleAffidato,FORMAT(SUM(P.cash_balance),2,"de_DE") as cashBalance,FORMAT(SUM(P.affidato_capitale),2,"de_DE") as capitaleAffidato,FORMAT(SUM(P.affidato_interessi),2,"de_DE") as interessiAffidati,FORMAT(SUM(P.affidato_spese),2,"de_DE") as speseAffidate,FORMAT(SUM(P.affidato_1),2,"de_DE") as affidato1,FORMAT(SUM(P.affidato_2),2,"de_DE") as affidato2,FORMAT(SUM(P.affidato_3),2,"de_DE") as affidato3
							FROM pratiche P
                            LEFT JOIN utente Ud ON P.id_debitore = Ud.id_utente
                            LEFT JOIN utente Um ON P.id_mandante = Um.id_utente
                            LEFT JOIN lotti_mandante L ON P.id_lotto_mandante = L.id
                            LEFT JOIN stati_pratiche S ON P.stato_corrente = S.id
                            LEFT JOIN stato_pratica St ON S.classe_stato_pratica = St.id
                            LEFT JOIN contratto C ON P.id_contratto = C.id
                            LEFT JOIN recapito R ON (P.id_debitore = R.id_utente AND R.predefinito = 1) 
							LEFT JOIN tipo_indirizzo 	Ti ON (R.tipo_recapito = Ti.id_tipo_indirizzo AND R.predefinito = 1) 
						WHERE (scaricata = 0 OR scaricata IS NULL)
							AND (archiviata = 0 OR archiviata IS NULL)
							AND area_corrente = 2
							AND (esito_corrente IS NULL OR esito_corrente <= 0)
							AND (id_collector IS NULL OR id_collector = 0)
							AND consenti_affidamento = 1';

                $tuttiTeam = implode(',', getTeamListFromUser($_SESSION['user_admin_id']));

                $whereApp .= ' AND C.id_team IN(' . $tuttiTeam . ')';
                $query .= $whereApp;
                // RIMOSSA LA CONDIZIONE: AND (id_lotto_affidamento IS NULL OR id_lotto_affidamento = 0)
            } else {
                $query = 'SELECT group_concat(P.id) as ids, count(P.id) as tot_pratiche, FORMAT(SUM(P.affidato_capitale+P.affidato_spese+P.affidato_interessi+P.affidato_1+P.affidato_2+P.affidato_3+P.competenze_oneri_recupero+P.competenze_spese_incasso),2,"de_DE") totaleAffidato,FORMAT(SUM(P.cash_balance),2,"de_DE") as cashBalance,FORMAT(SUM(P.affidato_capitale),2,"de_DE") as capitaleAffidato,FORMAT(SUM(P.affidato_interessi),2,"de_DE") as interessiAffidati,FORMAT(SUM(P.affidato_spese),2,"de_DE") as speseAffidate,FORMAT(SUM(P.affidato_1),2,"de_DE") as affidato1,FORMAT(SUM(P.affidato_2),2,"de_DE") as affidato2,FORMAT(SUM(P.affidato_3),2,"de_DE") as affidato3
						FROM pratiche P
                        LEFT JOIN utente Ud ON P.id_debitore = Ud.id_utente
                        LEFT JOIN utente Um ON P.id_mandante = Um.id_utente
                        LEFT JOIN lotti_mandante L ON P.id_lotto_mandante = L.id
                        LEFT JOIN stati_pratiche S ON P.stato_corrente = S.id
                        LEFT JOIN stato_pratica St ON S.classe_stato_pratica = St.id
                        LEFT JOIN contratto C ON P.id_contratto = C.id
                        LEFT JOIN recapito R ON (P.id_debitore = R.id_utente AND R.predefinito = 1)  
						WHERE (scaricata = 0 OR scaricata IS NULL)
							AND (archiviata = 0 OR archiviata IS NULL)
							AND area_corrente = 2
							AND (esito_corrente IS NULL OR esito_corrente <= 0)
							AND (id_collector IS NULL OR id_collector = 0)
							AND consenti_affidamento = 1';
                // RIMOSSA LA CONDIZIONE: AND (id_lotto_affidamento IS NULL OR id_lotto_affidamento = 0)
            }

            /* PER TEST */
            //query +=        ' AND (consenti_affidamento = 1 OR consenti_affidamento IS NULL) '; // DA RIMUOVERE LA CONDIZIONE "IS NULL"
            //query +=        ' AND (area_corrente = 2 OR area_corrente IS NULL) '; 		// DA RIMUOVERE LA CONDIZIONE "IS NULL"

            $n_filtro = $_POST['n'] + 1;

            if ($_POST['q'] != '')
                $query .= ' AND ' . $_POST['q'];


            // CREAZIONE DELLA QUERY DI COPERTURA ESATTORIALE (Non è stato possibile creare una vista a causa delle sottoquery nel FORM)
            $query_copertura_esattoriale = '';

            $append_copertura_esattoriale_capoarea_collector = '';
            $append_prodotti_lavorabili_capoarea_collector = '';

            if ($ruoloUtente == CAPO_ESATTORE) {
                $append_copertura_esattoriale_capoarea_collector = ' AND (U . id_utente = ' . $_SESSION['user_admin_id'] . ' OR U . id_utente IN(SELECT id_collegato FROM collegati_esattore WHERE id_utente = ' . $_SESSION['user_admin_id'] . '))'; // AGGIUNTA NELLA $query_copertura_esattoriale

                $query_count_prodotti_lavorabili = 'SELECT id_prodotto
													FROM prodotti_lavorabili 
													WHERE id_utente = ' . $_SESSION['user_admin_id'] . '
                OR id_utente IN(SELECT id_collegato FROM collegati_esattore WHERE id_utente = ' . $_SESSION['user_admin_id'] . ')';
                if (db_num_rows(db_query($query_count_prodotti_lavorabili)) > 0) {
                    $append_prodotti_lavorabili_capoarea_collector = ' AND id_tipo_credito IN(SELECT CR . id FROM prodotti_lavorabili PL LEFT JOIN credito CR ON CR . codice = PL . id_prodotto WHERE id_utente = ' . $_SESSION['user_admin_id'] . ' OR id_utente IN(SELECT id_collegato FROM collegati_esattore WHERE id_utente = ' . $_SESSION['user_admin_id'] . '))';
                }
            }

            {
                $query_copertura_esattoriale .= "SELECT P0.id 
												FROM pratiche P0 
												INNER JOIN recapito PR0 ON P0.id_debitore = PR0.id_utente 
												LEFT JOIN province PP0 ON PR0.provincia = PP0.cod_provincia
												LEFT JOIN comuni PC0 ON (PR0.citta = PC0.comune AND PR0.provincia = PC0.cod_provincia)
												WHERE PR0.predefinito = 1
												AND (
													( SELECT COUNT(U.id_utente)
														FROM (
															SELECT *
															FROM utente U
															WHERE U.attivo = 1 OR U.attivo IS NULL
														) AS U  

														LEFT JOIN zona_geografica_competenza UZ1 ON U.id_utente = UZ1.id_utente

														WHERE

														UZ1.zona_esatt = 1 AND UZ1.tipo_zona = 'Nazione' AND UZ1.da = PR0.nazione
														" . $append_copertura_esattoriale_capoarea_collector . "
													) > 0
													
													OR
													
													( SELECT COUNT(U.id_utente)
														FROM (
															SELECT *
															FROM utente U
															WHERE U.attivo = 1 OR U.attivo IS NULL
														) AS U  

														LEFT JOIN zona_geografica_competenza UZ1 ON U.id_utente = UZ1.id_utente  

														WHERE

														UZ1.zona_esatt = 1 AND UZ1.tipo_zona = 'Regione' AND UZ1.da = PP0.cod_regione
														" . $append_copertura_esattoriale_capoarea_collector . "
													) > 0
													
													OR
													
													( SELECT COUNT(U.id_utente)
														FROM (
															SELECT *
															FROM utente U
															WHERE U.attivo = 1 OR U.attivo IS NULL
														) AS U  

														LEFT JOIN zona_geografica_competenza UZ1 ON U.id_utente = UZ1.id_utente  

														WHERE

														UZ1.zona_esatt = 1 AND UZ1.tipo_zona = 'Provincia' AND UZ1.da = PR0.provincia
														" . $append_copertura_esattoriale_capoarea_collector . "
													) > 0
													
													OR
													
													( SELECT COUNT(U.id_utente)
														FROM (
															SELECT *
															FROM utente U
															WHERE U.attivo = 1 OR U.attivo IS NULL
														) AS U  

														LEFT JOIN zona_geografica_competenza UZ1 ON U.id_utente = UZ1.id_utente  

														WHERE

														UZ1.zona_esatt = 1 AND UZ1.tipo_zona = 'Cap' AND UZ1.da >= PR0.cap AND UZ1.a <= PR0.cap
														" . $append_copertura_esattoriale_capoarea_collector . "
													) > 0
													
													OR
													
													( SELECT COUNT(U.id_utente)
														FROM (
															SELECT *
															FROM utente U
															WHERE U.attivo = 1 OR U.attivo IS NULL
														) AS U  

														LEFT JOIN zona_geografica_competenza UZ1 ON U.id_utente = UZ1.id_utente  

														WHERE

														UZ1.zona_esatt = 1 AND UZ1.tipo_zona = 'Citta' AND UZ1.da = PC0.cod_istat
														" . $append_copertura_esattoriale_capoarea_collector . "
													) > 0
													) ";
            }

            if ($ruoloUtente == CAPO_ESATTORE) {
                ////$query .= ' AND P . id NOT IN('.$query_copertura_esattoriale.')';
                $query .= ' AND P . id IN(' . $query_copertura_esattoriale . ')';
                $query .= $append_prodotti_lavorabili_capoarea_collector;
            }

            //GESTIONE PRATICHE UTENTE OPERATIVO E RESPONSABILE PER FILIALE
            if (($_SESSION['user_role'] == OPERATIVO || $_SESSION['user_role'] == OPERATIVO_RESPONSABILE_DATI)
                && (isset($separazionePraticheFilialeFlag) && $separazionePraticheFilialeFlag == 1)) {
                $filiali = db_fetch_array_assoc(db_query("SELECT id_filiale FROM operatore WHERE id_utente = " . $_SESSION['user_admin_id']))[0]['id_filiale'];
                $condFiliale = " AND P.id_filiale_origine IN ( " . $filiali . ") ";
                $query .= $condFiliale;
            }

            $query_criteri_ordinamento = "SELECT criteri_di_affidamento FROM impostazioni_base WHERE id = 1";
            $campo = 'criteri_di_affidamento';


            // $query .= ' AND P . id NOT IN(' . $query_copertura_esattoriale . ')';
            $query .= $append_tipo_capoarea_collector;
            $query .= $append_prodotti_lavorabili_capoarea_collector;

            //  echo $query;
            $ris = db_query($query);


            # GESTIONE DELL'ERRORE NELLA QUERY (proponiamo un avviso all'utente riportante l'errore SQL)
            if (db_error()) {
                $db_error = db_error();
                $array_errore = explode(' at line ', $db_error);

                $errore = '<div class="note note-danger">
					       <h4 class="block" style="color:#ED4E2A"><strong style="color:#ED4E2A">ATTENZIONE!</strong> Hai generato una richiesta con sintassi non corretta.</h4>
						   <p style="color:#ED4E2A; margin-bottom: 10px">
								 L\'avviso rilevato segnala un errore nei pressi di ' . str_replace('You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near', '', $array_errore[0]) . '
						   </p>
					   </div>';
                die($errore);
            }

            # CREO L'OUTPUT HTML CHE ANDRA' APPESO ALLA PAGINA
            {
                $retHtml = '  <div class="table-responsive col-md-12">
						<table class="table table-striped table-hover" style="border:1px solid #DDD">
							<thead>
								<tr>
									<th>
										 N° PRATICHE
									</th>
									<th>
										 CASH BALANCE
									</th>
									<th>
										 CAPITALE AFFIDATO
									</th>
									<th>
										 SPESE AFFIDATE
									</th>
									<th>
										 INTERESSI AFFIDATI
									</th>
									<th>
										 AFFIDATO 1
									</th>
									<th>
										 AFFIDATO 2
									</th>
									<th>
										 AFFIDATO 3
									</th>
									<th>
										 TOTALE AFFIDATO
									</th>
								</tr>
							</thead>
							<tbody>';
                if (mysql_num_rows($ris) > 0) {
                    while ($row = mysql_fetch_array($ris)) {
                        $retHtml .= '<tr><td>' . $row['tot_pratiche'] . '</td>
												<td> &euro; ' . $row['cashBalance'] . '</td>
												<td> &euro; ' . $row['capitaleAffidato'] . '</td>
												<td> &euro; ' . $row['speseAffidate'] . '</td>
												<td> &euro; ' . $row['interessiAffidati'] . '</td>
												<td> &euro; ' . $row['affidato1'] . '</td>
												<td> &euro; ' . $row['affidato2'] . '</td>
												<td> &euro; ' . $row['affidato3'] . '</td>
												<td> &euro; ' . $row['totaleAffidato'] . '</td>
											</tr>';

                        $ret['ids_prt'] = explode(',', $row['ids']);

                    }
                } else {
                    $retHtml .= '<tr>
											<td colspan="12" style="text-align:center"><strong>Nessun risultato presente con i parametri di ricerca impostati</strong></td>
										</tr>';
                }
                $retHtml .= '			</tbody>
						</table>
					</div>';
            }
            $ret['html'] = $retHtml;

            print_r(json_encode($ret));
            die();

        }
        break;
    case 'carica-membri-team':
        $cards = array();
        $id_team = $_POST['id_team'];
        if (!isset($_POST['id_team']) || $_POST['id_team'] == "") {
            print_r("");
            die();
        }

        $query = "SELECT distinct U.id_utente as id, concat(IFNULL(U.cognome, ''), ' ', IFNULL(U.nome, '')) as denominazione,FORMAT(IFNULL(AFFIDATO_TOTALE.capitale_affidato,0),2,'de_DE') as capitale_affidato, FORMAT(IFNULL(RECUPERATO_TOTALE.recuperato_totale,0),2,'de_DE') as capitale_recuperato, IFNULL(PRATICHEAFFIDATE.pratiche_affidate,0) as pratiche_affidate, IFNULL(PHC.max_num_prat,0) as max_num_prat
FROM `team_composizione` Tc
         LEFT JOIN utente U ON U.id_utente = TC.id_operatore
         LEFT JOIN phone_collector PHC ON PHC.id_utente = U.id_utente
         LEFT JOIN (SELECT id_collector,
                           sum(IFNULL(affidato_capitale,0) + IFNULL(affidato_spese,0) + IFNULL(affidato_interessi,0) + IFNULL(affidato_1,0) + IFNULL(affidato_2,0) +
                               IFNULL(affidato_3,0) +
                               IFNULL(competenze_oneri_recupero,0) + IFNULL(competenze_spese_incasso,0)) as capitale_affidato
                    FROM pratiche
                    GROUP by id_collector) AFFIDATO_TOTALE ON AFFIDATO_TOTALE.id_collector = U.id_utente 
         LEFT JOIN (SELECT id_collector, count(id) as pratiche_affidate 
                    FROM pratiche
                    GROUP by id_collector) PRATICHEAFFIDATE ON PRATICHEAFFIDATE.id_collector = U.id_utente
         LEFT JOIN (SELECT PR2.id_collector, sum(PR2.importo) as recuperato_totale
                    from pratiche_recuperato PR2 WHERE PR2.tipo<>'PDP' AND PR2.id_pratica IN (SELECT id from pratiche WHERE pratiche.id_collector=PR2.id_collector) 
                    group by PR2.id_collector) RECUPERATO_TOTALE ON RECUPERATO_TOTALE.id_collector = U.id_utente
where (U.gruppi_base = 3 OR U.gruppi_base = 12)
  AND TC.id_team = '" . db_input($id_team) . "'
   AND PHC.assente=0";

        $ris = db_query($query);
        $ret = "<div class='table-responsive col-md-12' ><table class='table table-striped table-hover' style='border:1px solid #DDD'>
							<thead>
								<tr>
								    <th>
								    <input  type = 'checkbox' onchange='selTuttiCheck($(this))' class='checkbox' >
									</th>
									<th>
										  Denominazione
									</th>
									<th>
										  Prt. Affidate
									</th>
									<th>
										 Totale Affidato
									</th>
									<th>
										 Totale Recuperato
									</th>
									
								</tr>
							</thead>
							<tbody>";

        while ($row = mysql_fetch_array($ris)) {
            $ret .= "<tr><td><input type = 'checkbox' class='checkbox chkBoxCollector' name = 'phcId[]' value = '" . $row['id'] . "' ></td><td><label > " . $row['denominazione'] . " </label ></td><td><div class=\"visual\">
							<div style=\"display: inline; width: 50px; height: 50px;\"><input class=\"knobify knobifyReadonly\" data-width=\"115\" data-thickness=\".2\" data-skin=\"tron\" data-displayprevious=\"true\" value=\"" . $row['pratiche_affidate'] . "\" data-max=\"" . $row['max_num_prat'] . "\" data-min=\"0\" readonly=\"readonly\" style=\"width: 54px; height: 33px; position: absolute; vertical-align: middle; margin-top: 10px; margin-left: 0px; border: 0px; background: none; text-align: center; color: rgb(135, 206, 235); padding: 0px; -webkit-appearance: none;\"></div>
						</div> </td><td><label > &euro; " . $row['capitale_affidato'] . " </label ></td><td><label > &euro; " . $row['capitale_recuperato'] . " </label ></td> </tr> ";
        }

        $ret .= "</tbody></table>";
        print_r($ret);
        break;
    case 'bilancia_e_proponi_affido':
        {
            if (isset($_POST['array_prt']) && isset($_POST['campo_bilanciamento']) && isset($_POST['array_collector'])) {
                $array_collector = $_POST['array_collector'];
                $array_prt = $_POST['array_prt'];
                $campo_bilanciamento = $_POST['campo_bilanciamento'];
                $id_team = $_POST['id_team'];
                $alias_campo_bil = "";

                switch ($campo_bilanciamento) {
                    case 1:
                        {
                            $alias_campo_bil = "cash_balance";
                        }
                        break;
                    case 2:
                        {
                            $alias_campo_bil = "affidato_capitale";

                        }
                        break;
                    case 3:
                        {
                            $alias_campo_bil = "affidato_spese";

                        }
                        break;
                    case 4:
                        {
                            $alias_campo_bil = "affidato_interessi";

                        }
                        break;
                    case 5:
                        {
                            $alias_campo_bil = "affidato_1";

                        }
                        break;
                    case 6:
                        {
                            $alias_campo_bil = "affidato_2";

                        }
                        break;
                    case 7:
                        {
                            $alias_campo_bil = "affidato_3";

                        }
                        break;
                    case 8:
                        {
                            $alias_campo_bil = "totale_affidato";

                        }
                        break;

                    default:
                        $alias_campo_bil = "totale_affidato";
                        break;


                }

                $array_esa_prt_bil = bilancia_affidamento_phc($array_prt, $array_collector, $alias_campo_bil);

                $cards = array();

                $query = "SELECT distinct U.id_utente as id, concat(IFNULL(U.cognome, ''), ' ', IFNULL(U.nome, '')) as denominazione,IFNULL(U.avatar,'M.jpg') as avatar,FORMAT(IFNULL(AFFIDATO_TOTALE.capitale_affidato,0),2,'de_DE') as capitale_affidato, FORMAT(IFNULL(RECUPERATO_TOTALE.recuperato_totale,0),2,'de_DE') as capitale_recuperato, IFNULL(PRATICHEAFFIDATE.pratiche_affidate,0) as pratiche_affidate, IFNULL(PHC.max_num_prat,0) as max_num_prat
FROM `team_composizione` Tc
         LEFT JOIN utente U ON U.id_utente = TC.id_operatore
         LEFT JOIN phone_collector PHC ON PHC.id_utente = U.id_utente
         LEFT JOIN (SELECT id_collector,
                           sum(IFNULL(affidato_capitale,0) + IFNULL(affidato_spese,0) + IFNULL(affidato_interessi,0) + IFNULL(affidato_1,0) + IFNULL(affidato_2,0) +
                               IFNULL(affidato_3,0) +
                               IFNULL(competenze_oneri_recupero,0) + IFNULL(competenze_spese_incasso,0)) as capitale_affidato
                    FROM pratiche
                    GROUP by id_collector) AFFIDATO_TOTALE ON AFFIDATO_TOTALE.id_collector = U.id_utente 
         LEFT JOIN (SELECT id_collector, count(id) as pratiche_affidate
                    FROM pratiche
                    GROUP by id_collector) PRATICHEAFFIDATE ON PRATICHEAFFIDATE.id_collector = U.id_utente
         LEFT JOIN (SELECT id_collector, sum(importo) as recuperato_totale
                    from pratiche_recuperato
                    group by id_collector) RECUPERATO_TOTALE ON RECUPERATO_TOTALE.id_collector = U.id_utente
where (U.gruppi_base = 3 OR U.gruppi_base = 12)
  AND TC.id_team = '" . db_input($id_team) . "' AND PHC.assente=0 ";

                $ris = db_query($query);
                $prt_affidate = 0;
                $prt_totali = db_num_rows($ris);

                $ret = "<div class='table-responsive col-md-12' ><table class='table table-striped table-hover' style='border:1px solid #DDD'>
							<thead>
								<tr>
								    <th>
								    <input  type = 'checkbox' onchange='selTuttiCheck($(this))' class='checkbox' >
									</th>
									<th>
										  Denominazione
									</th>
									<th>
										  Prt. Affidate
									</th>
									<th>
										  Prt. Proposte
									</th>
									<th>
										 Totale Affidato
									</th>
									<th>
										 Totale Affidato Proposto
									</th>
									<th>
										 Totale Recuperato
									</th>
									
								</tr>
							</thead>
							<tbody>";

                $array_num_prt_per_esa = [];
                $array_tot_affido_per_esa = [];

                $k = 0;
                while ($row = mysql_fetch_array($ris)) {
                    $checked = "";
                    if (count($array_esa_prt_bil[$row['id']]) > 0) {
                        $checked = "checked";

                        $array_num_prt_per_esa[$k]['denominazione'] = $row['denominazione'];
                        $array_num_prt_per_esa[$k]['prt'] = count($array_esa_prt_bil[$row['id']]['prt']);
                        $array_num_prt_per_esa[$k]['img'] = $row['avatar'];

                        $array_tot_affido_per_esa[$k]['denominazione'] = $row['denominazione'];
                        $array_tot_affido_per_esa[$k]['tot'] = $array_esa_prt_bil[$row['id']][$alias_campo_bil];
                        $array_tot_affido_per_esa[$k]['img'] = $row['avatar'];


                        $new_prt = "<td><div class=\"visual\"> <div  style=\"display: inline; width: 50px; height: 50px;\"><input data-esa=\"" . $row['id'] . "\" class=\"knobify knobifyaff\" data-width=\"115\"  data-fgColor=\"#FFA500\" data-thickness=\".2\" data-skin=\"tron\" data-displayprevious=\"true\" value=\"" . count($array_esa_prt_bil[$row['id']]['prt']) . "\" data-max=\"" . $row['max_num_prat'] . "\" data-min=\"0\" style=\"width: 54px; height: 33px; position: absolute; vertical-align: middle; margin-top: 10px; margin-left: 0px; border: 0px; background: none; text-align: center; color: rgb(253, 187, 57)!important; padding: 0px; -webkit-appearance: none;\"></div></div></td>";

                        $k++;
                    } else {

                        $checked = "";

                        $new_prt = "<td></td>";
                    }


                    $ret .= "<tr id='" . $row['id'] . "'>
                                <td><input " . $checked . " type = 'checkbox' onchange=\"$('.prmBilanciamento').trigger('change');\" class='checkbox chkBoxCollector' name = 'phcId[]' value = '" . $row['id'] . "' ></td>
                                <td><label > " . $row['denominazione'] . " </label ></td>
                                <td><div class=\"visual\"> <div style=\"display: inline; width: 50px; height: 50px;\"><input class=\"knobify knobifyReadonly\" data-width=\"115\" data-thickness=\".2\" data-skin=\"tron\" data-displayprevious=\"true\" value=\"" . $row['pratiche_affidate'] . "\" data-max=\"" . $row['max_num_prat'] . "\" data-min=\"0\" readonly=\"readonly\" style=\"width: 54px; height: 33px; position: absolute; vertical-align: middle; margin-top: 10px; margin-left: 0px; border: 0px; background: none; text-align: center; color: rgb(135, 206, 235)!important; padding: 0px; -webkit-appearance: none;\"></div></div></td>" . $new_prt . "<td><label > &euro; " . $row['capitale_affidato'] . " </label ></td>
                                <td data-totale=\"" . $array_esa_prt_bil[$row['id']]['tot_affidato'] . "\" id='td-tot-" . $row['id'] . "' ><label > &euro; " . number_format($array_esa_prt_bil[$row['id']]['tot_affidato'], 2, ',', '.') . " </label ></td>
                                <td><label > &euro; " . $row['capitale_recuperato'] . " </label ></td> 
                              </tr> ";


                    $prt_affidate = '<a href="#">Pratiche Affidate <span class="badge badge-success bdgprt_affidate">' . $array_esa_prt_bil['prt_affidate'] . '</span ></a> ';
                    $prt_rimaste = '<a href="#">Pratiche Residue <span class="badge badge-danger bdgprt_rimaste" > ' . ($array_esa_prt_bil['tot_prt'] - $array_esa_prt_bil['prt_affidate']) . ' </span ></a> ';
                }

                $ret .= "</tbody></table>";

                $return ['tableHtml'] = $ret;
                $return ['prtRimaste'] = $prt_rimaste;
                $return ['prtAffidate'] = $prt_affidate;
                $return ['collector_prt'] = $array_esa_prt_bil;
                $return['pratiche_aff_esa'] = $array_num_prt_per_esa;
                $return['tot_aff_esa'] = $array_tot_affido_per_esa;

                print_r(json_encode($return));


            }

        }
        break;
    case 'avvia-chiamate-predictive':
        {
            $query_elemento_ind = "SELECT * FROM ( 
                SELECT REPLACE(aui_str_replace(RECTEL.indirizzo),\" \",\"\") as indirizzo ,
                    REPLACE(RECTEL2.indirizzo_secondario,\" \",\"\") as indirizzo_secondario ,
                    P.id as id_pratica,
                    CONCAT(IFNULL(D.cognome,''),' ',IFNULL(D.nome,' ')) as debitore,
                    CONCAT(IFNULL(M.cognome,''),' ',IFNULL(M.nome,' ')) as mandante, 
                    P.id_debitore as id_debitore,
                    PS.id_destinatario,
                    IFNULL(PS.data,data_prima_schedulazione) as scadenza,
                    Ce.id_utente,
                    Ce.numero_interno
                FROM pratiche P 
                    LEFT JOIN utente D ON P.id_debitore = D.id_utente
                    LEFT JOIN utente M ON P.id_mandante = M.id_utente
                    LEFT JOIN lotti_mandante LM ON P.id_lotto_mandante = LM.id
                    LEFT JOIN lotto_studio LS ON P.id_lotto_studio = LS.id
                    LEFT JOIN contratto C ON P.id_contratto = C.id
                    LEFT JOIN stati_pratiche S ON P.stato_corrente = S.id
                    LEFT JOIN esiti_pratica E ON P.esito_corrente = E.id
                    LEFT JOIN recapito REC on P.id_debitore = REC.id_utente and REC.predefinito =1
                    LEFT JOIN province DRP on REC.provincia = DRP.cod_provincia 
                    LEFT JOIN recapito_telefonico RECTEL on D.id_utente = RECTEL.id_utente and RECTEL.principale =1 
                        AND TRIM(RECTEL.indirizzo) <> ''  
                        AND RECTEL.indirizzo IS NOT NULL
                        AND RECTEL.indirizzo NOT LIKE '%@%' 
                    LEFT JOIN (SELECT id_utente, GROUP_CONCAT(aui_str_replace(indirizzo)) AS indirizzo_secondario 
                                    FROM recapito_telefonico 
                                    WHERE principale <> 1 
                                      AND TRIM(recapito_telefonico.indirizzo) <> '' 
                                      AND recapito_telefonico.indirizzo IS NOT NULL
                                      AND recapito_telefonico.indirizzo NOT LIKE '%@%'
                                    GROUP BY id_utente) AS RECTEL2 ON RECTEL2.id_utente=D.id_utente
                    JOIN pratiche_dati_totali PdT on P.id =PdT.id
                    LEFT JOIN scadenze PS on P.id = PS.id_pratica and PS.id=(select max(id) from scadenze where id_pratica=P.id) 
                    LEFT JOIN centralino Ce ON Ce.id_utente = PS.id_destinatario
				
				WHERE RECTEL.attivo=1 
				  AND P.area_corrente = 5
				  AND IFNULL(PS.data,data_prima_schedulazione) <=CURDATE()
				  AND (IFNULL(PS.data,data_prima_schedulazione) <= \"" . date('Y-m-d 23:59:00') . "\") 
				  AND (IFNULL(PS.data,data_prima_schedulazione) <> \"\") 
				  AND (IFNULL(PS.data,data_prima_schedulazione) IS NOT NULL)
				  AND (data_fine_mandato >= \"" . date('Y-m-d') . "\")
				ORDER BY PS.id_destinatario ASC, IFNULL(PS.data,data_prima_schedulazione) ASC) AS A";

            $ris = db_query($query_elemento_ind);
            $return = array();

            $phc = 0;
            $phcInterno = 0;
            $campaignCounter = 0;

            $campaign = array();

            $campaignTimestamp = date('YmdHis');

            while ($row = mysql_fetch_assoc($ris)) {
                if ($phc != 0 && $phc != $row['id_destinatario']) {
                    $nome_file = "files_pbx/campaign_" . $campaignTimestamp . $campaignCounter . ".json";

                    $fp = fopen($nome_file, 'w');
                    fwrite($fp, json_encode($campaign));
                    fclose($fp);

                    $return[$phc]['nome'] = $campaignTimestamp . $campaignCounter;
                    $return[$phc]['interno'] = $phcInterno;
                    $return[$phc]['campaign'] = $campaign;
                    $campaign = [];
                    $campaignCounter++;
                }

                $gruppo_lotto = strtotime("NOW");
                $query_inserimento_centralino = "INSERT INTO centralino_chiamate(id_pratica,id_debitore,tel_primario,tel_secondario,lotto,stato, campaign) VALUES ( '" . db_input($row['id_pratica']) . "','" . db_input($row['debitore']) . "','" . db_input($row['indirizzo']) . "','" . db_input($row['indirizzo_secondario']) . "','" . $gruppo_lotto . "',-5,'" . $campaignTimestamp . $campaignCounter . "')";
                db_query($query_inserimento_centralino);

                $campaign[]['id'] = $row['id_pratica'];
                $campaign[count($campaign) - 1]['name'] = $row['debitore'];
                $campaign[count($campaign) - 1]['number'] = $row['indirizzo'];
                $campaign[count($campaign) - 1]['result'] = "";

                $phc = $row['id_destinatario'];
                $phcInterno = $row['numero_interno'];
            }

            $nome_file = "files_pbx/campaign_" . $campaignTimestamp . $campaignCounter . ".json";

            $fp = fopen($nome_file, 'w');
            fwrite($fp, json_encode($campaign));
            fclose($fp);

            $return[$phc]['nome'] = $campaignTimestamp . $campaignCounter;
            $return[$phc]['interno'] = $phcInterno;
            $return[$phc]['campaign'] = $campaign;
            $campaign = [];

            print_r(json_encode($return));
        }
        break;
    case 'diasttiva-recapito':
        {
            $idRecapito = $_POST['id_rec'];
            $query = "UPDATE `recapito_telefonico` SET attivo=0 WHERE id_recapito_telefono= " . db_input($idRecapito);
            db_query($query);
            $return['success'] = 1;

            $query_dati_rec = 'SELECT id_recapito_telefono,replace(aui_str_replace(indirizzo)," ","") as indirizzo FROM `recapito_telefonico` WHERE  (tipo_recapito_telefonico = 2 OR tipo_recapito_telefonico = 6) and attivo = 1 and `id_utente`=(select RT.id_utente from recapito_telefonico RT where id_recapito_telefono ="' . $idRecapito . '")';

            $result_rec = db_query($query_dati_rec);
            $idRec = [];
            $valRec = [];

            while ($riga_rec = mysql_fetch_assoc($result_rec)) {
                if ($riga_rec['indirizzo'] != "") {
                    $idRec [] = $riga_rec['id_recapito_telefono'];
                    $valRec[] = $riga_rec['indirizzo'];
                }
            }
            $stampa_dati = trim($stampa_dati, ",");


            $return['idRec'] = $idRec;
            $return['valRec'] = $valRec;


            print_r(json_encode($return));

        }
        break;

    case 'imposta-recapito-principale':
        {
            $idRecapito = $_POST['id_rec'];
            $query = "UPDATE `recapito_telefonico` SET principale=1,attivo=1 WHERE id_recapito_telefono= " . db_input($idRecapito);
            db_query($query);
            $query = "SELECT id_utente FROM `recapito_telefonico` WHERE id_recapito_telefono= " . db_input($idRecapito);
            $risRec = db_query($query);
            $rec = mysql_fetch_assoc($result_rec);
            $query = "UPDATE `recapito_telefonico` SET principale=0 WHERE id_recapito_telefono != " . db_input($idRecapito) . " AND id_utente = " . $rec['id_utente'];
            db_query($query);
            $return['success'] = 1;

            print_r(json_encode($return));
        }
        break;
    case 'verifica-esistenza-telefono':
        {
            $phone = str_replace(array('+39', ' '), array(' ', ''), $_POST['phone']);

            $query_elemento_ind = "SELECT id
                                        FROM pratiche
                                        WHERE id_debitore IN (select id_utente from recapito_telefonico where replace(aui_str_replace(indirizzo),' ','') LIKE '" . $phone . "')
                                        OR id_debitore IN (SELECT id_utente FROM recapito_telefonico WHERE replace(aui_str_replace(indirizzo),' ','') LIKE '39" . $phone . "')
                                        ORDER BY id ASC";

            $ris = db_query($query_elemento_ind);

            $return = array(
                'error' => 0,
                'countRows' => 0,
                'query' => $query_elemento_ind
            );

            if (db_num_rows($ris) > 0) {
                $return['countRows'] = db_num_rows($ris);
            } else {
                $return['error'] = 1;
            }

            print_r(json_encode($return));
            die();
        }
        break;
    //******************************************* KPI ****************************************//
    //******************************************* KPI ****************************************//
    case 'filtro-monitoraggio-prt':
        {
            $ruoloUtente = $_SESSION['user_role'];

            $where = '';
            if ($_SESSION['user_role'] == CAPO_PHONE_COLLECTOR) {
                $tuttiTeam = implode(',', getTeamListFromUser($_SESSION['user_admin_id']));
                $where .= ' AND (P.id_collector IN (' . $_SESSION['user_admin_id'] . ')';
                $where .= ' OR C.id_team IN(' . $tuttiTeam . '))';
                $where .= ' AND (archiviata = 0 OR archiviata IS NULL)';
            }

            if ($_SESSION['user_role'] == MANDANTE) {
                $where .= ' AND (P.id_mandante =' . $_SESSION['user_admin_id'] . ') ';
            }


            $query = "
SELECT PRTAFF.mandante                                                                                  as MANDANTE,

       IFNULL(PRTAFF.pratiche_affidate, 0)                                                              as 'SENT',

       IFNULL(PRTBLOC.pratiche_bloccate, 0)                                                             as 'DISCARDEDgest',

       (IFNULL(PRTAFF.pratiche_affidate, 0) -
        IFNULL(PRTBLOC.pratiche_bloccate, 0))                                                           as 'MANAGEBLE',

       IFNULL(PRTGEST.pratiche_gestite, 0)                                                              as 'MANAGEDgest',

       IFNULL(PRTTENTATIVICONTATTOIN.prt_tentativi_contatto_in, 0)                                      as 'CALL ATTEMPS IN',

       IFNULL(PRTTENTATIVICONTATTOOUT.prt_tentativi_contatto_out, 0)                                    as 'CALL ATTEMPS OUT',

       IFNULL(PRTCONTATTIVALIDIIN.contatti_validi_in, 0)                                                as 'CONTACTED IN',

       IFNULL(PRTCONTATTIVALIDIOUT.contatti_validi_out, 0)                                              as 'CONTACTED OUT',

       IFNULL(PRTCONTATTIUTILIIN.contatti_utili_in, 0)                                                  as 'RPC IN',

       IFNULL(PRTCONTATTIUTILIOUT.contatti_utili_out, 0)                                                as 'RPC OUT',

       IFNULL(PRTPDP.pratiche_pdp, 0)                                                                   as 'PTP',

       IFNULL(PRTPDPBF.pratiche_pdp_bf, 0)                                                              as 'PTP KEPT',

       (IFNULL(PRTGEST.pratiche_gestite, 0) / (IFNULL(PRTAFF.pratiche_affidate, 0) -
                                               IFNULL(PRTBLOC.pratiche_bloccate, 0)))                   as 'COVERAGE',

       ((IFNULL(PRTCONTATTIVALIDIIN.contatti_validi_in, 0) +
         IFNULL(PRTCONTATTIVALIDIOUT.contatti_validi_out, 0)) /
        IFNULL(PRTGEST.pratiche_gestite, 0))                                                            as 'NET CONTACT',

       ((IFNULL(PRTCONTATTIUTILIIN.contatti_utili_in, 0) +
         IFNULL(PRTCONTATTIUTILIOUT.contatti_utili_out, 0)) /
        (IFNULL(PRTCONTATTIVALIDIIN.contatti_validi_in, 0) +
         IFNULL(PRTCONTATTIVALIDIOUT.contatti_validi_out, 0)))                                          as 'USEFUL CONTACT',

       (IFNULL(PRTPDP.pratiche_pdp, 0) /
        (IFNULL(PRTCONTATTIUTILIIN.contatti_utili_in, 0) +
         IFNULL(PRTCONTATTIUTILIOUT.contatti_utili_out, 0)))                                            as 'NEGOTIATION',

       (IFNULL(PRTPDP.pratiche_pdp, 0) /
        (IFNULL(PRTCONTATTIUTILIIN.contatti_utili_in, 0) +
         IFNULL(PRTCONTATTIUTILIOUT.contatti_utili_out, 0)))                                            as 'GLOBAL AFFECTIVENESS',

       (IFNULL(PRTBLOC.pratiche_bloccate, 0) / IFNULL(PRTAFF.pratiche_affidate, 0))                     as 'DISCARDED',

       ((IFNULL(PRTAFF.pratiche_affidate, 0) -
         IFNULL(PRTBLOC.pratiche_bloccate, 0)) / IFNULL(PRTAFF.pratiche_affidate, 0))                   as 'TO MANAGE',

       (IFNULL(PRTGEST.pratiche_gestite, 0) / IFNULL(PRTAFF.pratiche_affidate, 0))                      as 'MANAGED',

       ((IFNULL(PRTCONTATTIVALIDIIN.contatti_validi_in, 0) +
         IFNULL(PRTCONTATTIVALIDIOUT.contatti_validi_out, 0)) / IFNULL(PRTAFF.pratiche_affidate, 0))    as 'CONTACTED',

       ((IFNULL(PRTCONTATTIVALIDIIN.contatti_validi_in, 0) +
         IFNULL(PRTCONTATTIVALIDIOUT.contatti_validi_out, 0)) / (IFNULL(PRTAFF.pratiche_affidate, 0) -
                                                                 IFNULL(PRTBLOC.pratiche_bloccate, 0))) as 'CONTACTED/MANAGEABLE',
       ((IFNULL(PRTCONTATTIUTILIIN.contatti_utili_in, 0) +
         IFNULL(PRTCONTATTIUTILIOUT.contatti_utili_out, 0)) / (IFNULL(PRTAFF.pratiche_affidate, 0) -
                                                               IFNULL(PRTBLOC.pratiche_bloccate, 0)))   as 'RPC/MANAGEABLE',
       (IFNULL(PRTPDP.pratiche_pdp, 0) / (IFNULL(PRTAFF.pratiche_affidate, 0) -
                                          IFNULL(PRTBLOC.pratiche_bloccate, 0)))                        as 'PTP/MANAGEABLE',

       (IFNULL(PRTPDPBF.pratiche_pdp_bf, 0) /
        IFNULL(PRTPDP.pratiche_pdp, 0))                                                                 as 'EFFECTIVENESS'


FROM (SELECT GROUP_CONCAT(DISTINCT CONCAT(COALESCE(M.cognome, ''),
                                          \" \", COALESCE(M.nome,
                                                        ''))) AS mandante,
             count(DISTINCT P.id)                             AS pratiche_affidate
      FROM pratiche P
               LEFT JOIN utente M ON P.id_mandante = M.id_utente
               LEFT JOIN lotti_mandante LM on LM.id = P.id_lotto_mandante WHERE CONDIZIONI_NUOVE_DA_AGGIUNGERE
      group by P.id_mandante) PRTAFF
         LEFT JOIN (SELECT GROUP_CONCAT(DISTINCT
                                        CONCAT(COALESCE(M.cognome,
                                                        ''),
                                               \" \", COALESCE(M.nome,
                                                             ''))) AS mandante,
                           count(DISTINCT P.id)                    AS pratiche_bloccate
                    FROM pratiche P
                             LEFT JOIN contratto C ON P.id_contratto = C.id
                             LEFT JOIN utente M ON P.id_mandante = M.id_utente
                             LEFT JOIN lotti_mandante LM on LM.id = P.id_lotto_mandante
                    WHERE P.data_fine_mandato > CURDATE()
                      AND P.area_corrente = 3 AND CONDIZIONI_NUOVE_DA_AGGIUNGERE
                    group by P.id_mandante) PRTBLOC
                   ON PRTAFF.mandante = PRTBLOC.mandante
         LEFT JOIN (SELECT GROUP_CONCAT(DISTINCT
                                        CONCAT(COALESCE(M.cognome, ''), \" \", COALESCE(M.nome, ''))) AS mandante,
                           count(DISTINCT P.id)                                                     AS pratiche_gestite
                    FROM pratiche P
                             LEFT JOIN contratto C ON P.id_contratto = C.id
                             LEFT JOIN utente M ON P.id_mandante = M.id_utente
                             LEFT JOIN lotti_mandante LM on LM.id = P.id_lotto_mandante
                             LEFT JOIN (SELECT count(id) as numNote, id_pratica
                                        from note_su_pratica
                                        group by id_pratica) NSP ON NSP.id_pratica = P.id
                    WHERE NSP.numNote > 1 AND CONDIZIONI_NUOVE_DA_AGGIUNGERE
                    group by P.id_mandante) PRTGEST ON PRTGEST.mandante = PRTAFF.mandante
         LEFT JOIN ( /*############## TENTATIVI DI CONTATTO IN  #################### controllo nella tabella statistiche_monitoraggio.. e controllo quali sono le noteabituali inerenti al tentativo di contantto campo like call_attemps ( uso find in set visto che nel campo tengo tutti id nota abituale separati da ,)*/
    SELECT GROUP_CONCAT(DISTINCT CONCAT(COALESCE(M.cognome, ''), \" \", COALESCE(M.nome, ''))) AS mandante,
           count(DISTINCT P.id)                                                              AS prt_tentativi_contatto_in
    FROM pratiche P
             LEFT JOIN contratto C ON P.id_contratto = C.id
             LEFT JOIN utente M ON P.id_mandante = M.id_utente
             LEFT JOIN lotti_mandante LM on LM.id = P.id_lotto_mandante
             LEFT JOIN (SELECT count(id) as numNote, id_pratica
                        from note_su_pratica
                        where find_in_set(id_nota_abituale, (select id_note_abituali
                                                             from statistica_monitoraggio_affido
                                                             where campo like 'call_attemps_IN')) > 0
                        group by id_pratica) NSP
                       ON NSP.id_pratica = P.id
    WHERE NSP.numNote > 0 AND CONDIZIONI_NUOVE_DA_AGGIUNGERE
    group by P.id_mandante) PRTTENTATIVICONTATTOIN ON PRTTENTATIVICONTATTOIN.mandante = PRTAFF.mandante
         LEFT JOIN (SELECT GROUP_CONCAT(DISTINCT
                                        CONCAT(COALESCE(M.cognome, ''), \" \", COALESCE(M.nome, ''))) AS mandante,
                           count(DISTINCT P.id)                                                     AS prt_tentativi_contatto_out
                    FROM pratiche P             
                             LEFT JOIN contratto C ON P.id_contratto = C.id
                             LEFT JOIN utente M ON P.id_mandante = M.id_utente
                             LEFT JOIN lotti_mandante LM on LM.id = P.id_lotto_mandante
                             LEFT JOIN (SELECT count(id) as numNote, id_pratica
                                        from note_su_pratica
                                        where find_in_set(id_nota_abituale, (select id_note_abituali
                                                                             from statistica_monitoraggio_affido
                                                                             where campo like 'call_attemps_OUT')) > 0
                                        group by id_pratica) NSP
                                       ON NSP.id_pratica = P.id
                    WHERE NSP.numNote > 0 AND CONDIZIONI_NUOVE_DA_AGGIUNGERE
                    group by P.id_mandante) PRTTENTATIVICONTATTOOUT
                   ON PRTTENTATIVICONTATTOOUT.mandante = PRTAFF.mandante
         LEFT JOIN (/*#################### CONTATTI VALIDI IN  #################### Simile a precedente*/
    SELECT GROUP_CONCAT(DISTINCT CONCAT(COALESCE(M.cognome, ''), \" \", COALESCE(M.nome, ''))) AS mandante,
           count(DISTINCT P.id)                                                              AS contatti_validi_in
    FROM pratiche P             
             LEFT JOIN contratto C ON P.id_contratto = C.id 
             LEFT JOIN utente M ON P.id_mandante = M.id_utente
             LEFT JOIN lotti_mandante LM on LM.id = P.id_lotto_mandante
             LEFT JOIN (SELECT count(id) as numNote, id_pratica
                        from note_su_pratica
                        where find_in_set(id_nota_abituale, (select id_note_abituali
                                                             from statistica_monitoraggio_affido
                                                             where campo like 'contacted_IN')) > 0
                        group by id_pratica) NSP
                       ON NSP.id_pratica = P.id
    WHERE NSP.numNote > 0 AND CONDIZIONI_NUOVE_DA_AGGIUNGERE
    group by P.id_mandante) PRTCONTATTIVALIDIIN ON PRTCONTATTIVALIDIIN.mandante = PRTAFF.mandante
         LEFT JOIN (/*#################### CONTATTI VALIDI OUT  #################### Simile a precedente*/
    SELECT GROUP_CONCAT(DISTINCT CONCAT(COALESCE(M.cognome, ''), \" \", COALESCE(M.nome, ''))) AS mandante,
           count(DISTINCT P.id)                                                              AS contatti_validi_out
    FROM pratiche P
             LEFT JOIN contratto C ON P.id_contratto = C.id
             LEFT JOIN utente M ON P.id_mandante = M.id_utente
             LEFT JOIN lotti_mandante LM on LM.id = P.id_lotto_mandante
             LEFT JOIN (SELECT count(id) as numNote, id_pratica
                        from note_su_pratica
                        where find_in_set(id_nota_abituale, (select id_note_abituali
                                                             from statistica_monitoraggio_affido
                                                             where campo like 'contacted_OUT')) > 0
                        group by id_pratica) NSP
                       ON NSP.id_pratica = P.id
    WHERE NSP.numNote > 0 AND CONDIZIONI_NUOVE_DA_AGGIUNGERE
    group by P.id_mandante) PRTCONTATTIVALIDIOUT ON PRTCONTATTIVALIDIOUT.mandante = PRTAFF.mandante
         LEFT JOIN (/*#################### CONTATTI UTILI IN #################### Simile a precedente*/
    SELECT GROUP_CONCAT(DISTINCT CONCAT(COALESCE(M.cognome, ''), \" \", COALESCE(M.nome, ''))) AS mandante,
           count(DISTINCT P.id)                                                              AS contatti_utili_in
    FROM pratiche P
             LEFT JOIN contratto C ON P.id_contratto = C.id
             LEFT JOIN utente M ON P.id_mandante = M.id_utente
             LEFT JOIN lotti_mandante LM on LM.id = P.id_lotto_mandante
             LEFT JOIN (SELECT count(id) as numNote, id_pratica
                        from note_su_pratica
                        where find_in_set(id_nota_abituale, (select id_note_abituali
                                                             from statistica_monitoraggio_affido
                                                             where campo like 'RPC_IN')) > 0
                        group by id_pratica) NSP
                       ON NSP.id_pratica = P.id
    WHERE NSP.numNote > 0 AND CONDIZIONI_NUOVE_DA_AGGIUNGERE
    group by P.id_mandante) PRTCONTATTIUTILIIN ON PRTCONTATTIUTILIIN.mandante = PRTAFF.mandante
         LEFT JOIN (
/*#################### CONTATTI UTILI OUT #################### Simile a precedente*/
    SELECT GROUP_CONCAT(DISTINCT CONCAT(COALESCE(M.cognome, ''), \" \", COALESCE(M.nome, ''))) AS mandante,
           count(DISTINCT P.id)                                                              AS contatti_utili_out
    FROM pratiche P
             LEFT JOIN contratto C ON P.id_contratto = C.id
             LEFT JOIN utente M ON P.id_mandante = M.id_utente
             LEFT JOIN lotti_mandante LM on LM.id = P.id_lotto_mandante
             LEFT JOIN (SELECT count(id) as numNote, id_pratica
                        from note_su_pratica
                        where find_in_set(id_nota_abituale, (select id_note_abituali
                                                             from statistica_monitoraggio_affido
                                                             where campo like 'RPC_OUT')) > 0
                        group by id_pratica) NSP
                       ON NSP.id_pratica = P.id
    WHERE NSP.numNote > 0 AND CONDIZIONI_NUOVE_DA_AGGIUNGERE
    group by P.id_mandante) PRTCONTATTIUTILIOUT ON PRTCONTATTIUTILIOUT.mandante = PRTAFF.mandante
         LEFT JOIN (/*#################### PTP #################### tutte le pratiche che hanno almeno un PDP*/
    SELECT GROUP_CONCAT(DISTINCT CONCAT(COALESCE(M.cognome, ''), \" \", COALESCE(M.nome, ''))) AS mandante,
           count(DISTINCT P.id)                                                              AS pratiche_pdp
    FROM pratiche P
             LEFT JOIN contratto C ON P.id_contratto = C.id
             LEFT JOIN utente M ON P.id_mandante = M.id_utente
             LEFT JOIN lotti_mandante LM on LM.id = P.id_lotto_mandante
    WHERE P.id in (SELECT distinct id_pratica from pratiche_recuperato where tipo LIKE 'PDP') AND CONDIZIONI_NUOVE_DA_AGGIUNGERE
    group by P.id_mandante) PRTPDP ON PRTPDP.mandante = PRTAFF.mandante
         LEFT JOIN (/*#################### PTP BF #################### tutte le pratiche che hanno almeno un PDP*/
    SELECT GROUP_CONCAT(DISTINCT CONCAT(COALESCE(M.cognome, ''), \" \", COALESCE(M.nome, ''))) AS mandante,
           count(DISTINCT P.id)                                                              AS pratiche_pdp_bf
    FROM pratiche P
             LEFT JOIN contratto C ON P.id_contratto = C.id
             LEFT JOIN utente M ON P.id_mandante = M.id_utente
             LEFT JOIN lotti_mandante LM on LM.id = P.id_lotto_mandante
    WHERE P.id in (SELECT distinct id_pratica
                   from pratiche_recuperato
                   where tipo LIKE 'PDP'
                     AND id_pratica not in
                         (SELECT distinct id_pratica from pratiche_recuperato where buon_fine = 0 AND tipo LIKE 'PDP')
    )
    group by P.id_mandante AND CONDIZIONI_NUOVE_DA_AGGIUNGERE) PRTPDPBF ON PRTPDPBF.mandante = PRTAFF.mandante";

            $arrayGraficoUNO = [];
            $arrayGraficoDUE = [];

            if ($_POST['q'] != '' && $_POST['q'] != '()') {
                $query = str_replace('CONDIZIONI_NUOVE_DA_AGGIUNGERE', $_POST['q'] . $where, $query);
            } else {
                $query = str_replace('CONDIZIONI_NUOVE_DA_AGGIUNGERE', ' 1=1 ' . $where, $query);

            }

            $ret = ' <div class="table-responsive col-md-12">
						<table class="table table-striped table-hover" style="border:1px solid #DDD">
							<thead>
								<tr>
									<th>MANDANTE</th>						
									<th>SENT</th>						
									<th>DISCARDED</th>						
									<th>MANAGEABLE</th>						
									<th>MANAGED</th>						
									<th>CALL ATTEMPS IN</th>						
									<th>CALL ATTEMPS OUT</th>						
									<th>CONTACTED IN</th>						
									<th>CONTACTED OUT</th>						
									<th>RPC IN</th>						
									<th>RPC OUT</th>						
									<th>PTP</th>						
									<th>PTP KEPT</th>						
									<th>COVERAGE</th>						
									<th>NET CONTACT</th>						
									<th>USEFUL CONTACT</th>						
									<th>NEGOTIATION</th>						
									<th>GLOBAL AFFECTIVENESS</th>						
									<th>DISCARDED</th>						
									<th>TO MANAGE</th>						
									<th>MANAGED</th>						
									<th>CONTACTED</th>						
									<th>CONTACTED/MANAGEABLE</th>						
									<th>RPC/MANAGEABLE</th>						
								</tr>
							</thead>
							<tbody>';

            $arrayGraficoUNO[0][] = '';
            $arrayGraficoUNO[1][] = 'SENT';
            $arrayGraficoUNO[2][] = 'DISCARDED';
            $arrayGraficoUNO[3][] = 'MANAGEABLE';
            $arrayGraficoUNO[4][] = 'MANAGED';
            $arrayGraficoUNO[5][] = 'CALL ATT. IN';
            $arrayGraficoUNO[6][] = 'CALL ATT. OUT';
            $arrayGraficoUNO[7][] = 'CONTACTED IN';
            $arrayGraficoUNO[8][] = 'CONTACTED OUT';
            $arrayGraficoUNO[9][] = 'RPC IN';
            $arrayGraficoUNO[10][] = 'RPC OUT';
            $arrayGraficoUNO[11][] = 'PTP';
            $arrayGraficoUNO[12][] = 'PTP KEPT';

            $arrayGraficoDUE[0][] = '';
            $arrayGraficoDUE[1][] = 'COVERAGE';
            $arrayGraficoDUE[2][] = 'NET CONTACT';
            $arrayGraficoDUE[3][] = 'USEFUL CONTACT';
            $arrayGraficoDUE[4][] = 'NEGOTIATION';
            $arrayGraficoDUE[5][] = 'GLOBAL AFFECTIVEN.';
            $arrayGraficoDUE[6][] = 'DISCARDED';
            $arrayGraficoDUE[7][] = 'TO MANAGE';
            $arrayGraficoDUE[8][] = 'MANAGED';
            $arrayGraficoDUE[9][] = 'CONTACTED';
            $arrayGraficoDUE[10][] = 'CONT./MANAG.';
            $arrayGraficoDUE[11][] = 'RPC/MANAG.';

            $string = "MANDANTE;SENT;DISCARDED;MANAGEABLE;MANAGED;CALL ATTEMPS IN;CALL ATTEMPS OUT;CONTACTED IN;CONTACTED OUT;RPC IN;RPC OUT;PTP;PTP KEPT;COVERAGE;NET CONTACT;USEFUL CONTACT;NEGOTIATION;GLOBAL AFFECTIVENESS;DISCARDED;TO MANAGE;MANAGED;CONTACTED;CONTACTED/MANAGEABLE;RPC/MANAGEABLE;";
            $string .= PHP_EOL;

            $ris = db_query($query);
            if (mysql_num_rows($ris) > 0) {
                while ($row = mysql_fetch_array($ris)) {
                    $string .= $row['MANDANTE'] . ";" . $row['SENT'] . ";" . $row['DISCARDEDgest'] . ";" . $row['MANAGEABLE'] . ";" . $row['MANAGEDgest'] . ";" . $row['CALL ATTEMPS IN'] . ";" . $row['CALL ATTEMPS OUT'] . ";" . $row['CONTACTED IN'] . ";" . $row['CONTACTED OUT'] . ";" . $row['RPC IN'] . ";" . $row['RPC OUT'] . ";" . $row['PTP'] . ";" . $row['PTP KEPT'] . ";" . str_replace('.', ',', ($row['COVERAGE'] * 100)) . ";" . str_replace('.', ',', ($row['NET CONTACT'] * 100)) . ";" . str_replace('.', ',', ($row['USEFUL CONTACT'] * 100)) . ";" . str_replace('.', ',', ($row['NEGOTIATION'] * 100)) . ";" . str_replace('.', ',', ($row['GLOBAL AFFECTIVENESS'] * 100)) . ";" . str_replace('.', ',', ($row['DISCARDED'] * 100)) . ";" . str_replace('.', ',', ($row['TO MANAGE'] * 100)) . ";" . str_replace('.', ',', ($row['MANAGED'] * 100)) . ";" . str_replace('.', ',', ($row['CONTACTED'] * 100)) . ";" . str_replace('.', ',', ($row['CONTACTED/MANAGEABLE'] * 100)) . ";" . str_replace('.', ',', ($row['RPC/MANAGEABLE'] * 100)) . ";";

                    $ret .= '<tr>
												<td>' . $row['MANDANTE'] . '</td>
												<td>' . $row['SENT'] . '</td>
												<td>' . $row['DISCARDEDgest'] . '</td>
												<td>' . $row['MANAGEABLE'] . '</td>
												<td>' . $row['MANAGEDgest'] . '</td>
												<td>' . $row['CALL ATTEMPS IN'] . '</td>
												<td>' . $row['CALL ATTEMPS OUT'] . '</td>
												<td>' . $row['CONTACTED IN'] . '</td>
												<td>' . $row['CONTACTED OUT'] . '</td>
												<td>' . $row['RPC IN'] . '</td>
												<td>' . $row['RPC OUT'] . '</td>
												<td>' . $row['PTP'] . '</td>
												<td>' . $row['PTP KEPT'] . '</td>
												<td>' . $row['COVERAGE'] * 100 . ' % </td>
												<td>' . $row['NET CONTACT'] * 100 . ' % </td>
												<td>' . $row['USEFUL CONTACT'] * 100 . ' % </td>
												<td>' . $row['NEGOTIATION'] * 100 . ' % </td>
												<td>' . $row['GLOBAL AFFECTIVENESS'] * 100 . ' % </td>
												<td>' . $row['DISCARDED'] * 100 . ' % </td>
												<td>' . $row['TO MANAGE'] * 100 . ' % </td>
												<td>' . $row['MANAGED'] * 100 . ' % </td>
												<td>' . $row['CONTACTED'] * 100 . ' % </td>
												<td>' . $row['CONTACTED/MANAGEABLE'] * 100 . ' % </td>
												<td>' . $row['RPC/MANAGEABLE'] * 100 . ' % </td>
											</tr>';


                    $arrayGraficoUNO[0][] = $row['MANDANTE'];
                    $arrayGraficoUNO[1][] = floatval($row['SENT']);
                    $arrayGraficoUNO[2][] = floatval($row['DISCARDEDgest']);
                    $arrayGraficoUNO[3][] = floatval($row['MANAGEABLE']);
                    $arrayGraficoUNO[4][] = floatval($row['MANAGEDgest']);
                    $arrayGraficoUNO[5][] = floatval($row['CALL ATTEMPS IN']);
                    $arrayGraficoUNO[6][] = floatval($row['CALL ATTEMPS OUT']);
                    $arrayGraficoUNO[7][] = floatval($row['CONTACTED IN']);
                    $arrayGraficoUNO[8][] = floatval($row['CONTACTED OUT']);
                    $arrayGraficoUNO[9][] = floatval($row['RPC IN']);
                    $arrayGraficoUNO[10][] = floatval($row['RPC OUT']);
                    $arrayGraficoUNO[11][] = floatval($row['PTP']);
                    $arrayGraficoUNO[12][] = floatval($row['PTP KEPT']);

                    $arrayGraficoDUE[0][] = $row['MANDANTE'];
                    $arrayGraficoDUE[1][] = floatval($row['COVERAGE']);
                    $arrayGraficoDUE[2][] = floatval($row['NET CONTACT']);
                    $arrayGraficoDUE[3][] = floatval($row['USEFUL CONTACT']);
                    $arrayGraficoDUE[4][] = floatval($row['NEGOTIATION']);
                    $arrayGraficoDUE[5][] = floatval($row['GLOBAL AFFECTIVENESS']);
                    $arrayGraficoDUE[6][] = floatval($row['DISCARDED']);
                    $arrayGraficoDUE[7][] = floatval($row['TO MANAGE']);
                    $arrayGraficoDUE[8][] = floatval($row['MANAGED']);
                    $arrayGraficoDUE[9][] = floatval($row['CONTACTED']);
                    $arrayGraficoDUE[10][] = floatval($row['CONTACTED/MANAGEABLE']);
                    $arrayGraficoDUE[11][] = floatval($row['RPC/MANAGEABLE']);

                    $string .= PHP_EOL;

                }
            }
            $string .= PHP_EOL;

            writeSession('file_export_dati', $string);
            $return['csv'] = ' <a target="_blank" class="btn btn-primary"
                           href="./scarica_export_dati.php?name=kpi_' . date('Y-m-d') . '.csv"><i class=" fa fa-download"></i>  DOWNLOAD RISULTATI</a>';
            $ret .= '</tbody>
						</table>
					</div> ';


            $return['graficoUNO'] = $arrayGraficoUNO;
            $return['graficoDUE'] = $arrayGraficoDUE;
            $return['htmlTable'] = $ret;

            print_r(json_encode($return));
        }
        break;
    case 'filtro-workable-accounts-operatore':
        {
            $ruoloUtente = $_SESSION['user_role'];

            $where = '';
            if ($_SESSION['user_role'] == CAPO_PHONE_COLLECTOR) {
                $tuttiTeam = implode(',', getTeamListFromUser($_SESSION['user_admin_id']));
                $where .= ' AND (P.id_collector IN (' . $_SESSION['user_admin_id'] . ')';
                $where .= ' OR C.id_team IN(' . $tuttiTeam . '))';
                $where .= ' AND (archiviata = 0 OR archiviata IS NULL)';
            }

            if ($_SESSION['user_role'] == MANDANTE) {
                $where .= ' AND (P.id_mandante =' . $_SESSION['user_admin_id'] . ') ';
            }

            $ruoloUtente = $_SESSION['user_role'];
            $query = "SELECT *
FROM (SELECT group_concat(IF(scadenzaSospesa=0,P.id,0))                                            as idswa,
             GROUP_CONCAT(DISTINCT CONCAT(M.cognome, ' ', M.nome) SEPARATOR '<br>') as mandante,
             IFNULL(CONCAT(phc.cognome, ' ', phc.nome), 'SENZA OPERATORE')            as operatore,
             GROUP_CONCAT(DISTINCT concat(LM.descrizione, '(', CONCAT(ML.cognome, ' ', ML.nome), ')') SEPARATOR
                          '<br>')                                                   as lotto,
             SUM(IF(scadenzaSospesa=0,1,0))                                                   as WA,
             SUM(IF(scadenzaSospesa=0,PS.chiamata_puntuale,0))                                                   as CHPT,
             SUM(IF(scadenzaSospesa=0,PS.nuova_pratica,0))                                                   as NVPT,
             SUM(IF(PS.chiamata_puntuale=0 AND PS.nuova_pratica=0 AND scadenzaSospesa=0,1,0))                                                   as SCHD,
             SUM(IF(scadenzaSospesa=1,1,0))                                                   as SOPS
      FROM pratiche P
               LEFT JOIN contratto C ON P.id_contratto = C.id
               LEFT JOIN utente M ON P.id_mandante = M.id_utente        
               LEFT JOIN scadenze PS on P.id = PS.id_pratica and PS.id IN (select id from scadenze where stato <>2 AND id_pratica=P.id) 
                                                    and CONCAT(PS.data,' ',PS.ora) = (select MIN(CONCAT(data,' ',ora)) from scadenze where stato <>2 AND id_pratica=P.id AND schedulazione =1) 
                                                    AND ( PS.stato =0 OR PS.stato =1)
               LEFT JOIN lotti_mandante LM on P.id_lotto_mandante = LM.id
               LEFT JOIN utente ML ON ML.id_utente = LM.id_mandante
               LEFT JOIN utente phc ON phc.id_utente = PS.id_destinatario 
      WHERE  PS.schedulazione=1 AND PS.stato<>2 AND DATE(CONCAT(PS.data, ' ', IFNULL(PS.ora, ''))) <= NOW()
        AND  PS.data > '0000-00-00'
        AND (phc.gruppi_base = 3 OR phc.gruppi_base = 12) CONDIZIONI_NUOVE_DA_AGGIUNGERE


      GROUP BY phc.id_utente
      order by operatore
     ) WATBL
         LEFT JOIN (SELECT group_concat(DISTINCT P.id)      as idsmanaged,
                           CONCAT(phc.cognome, ' ', phc.nome) as operatoremanaged,
                           count(DISTINCT P.id)             as MANAGED
                    FROM pratiche P
                            LEFT JOIN contratto C ON P.id_contratto = C.id
                             JOIN note_su_pratica NP ON NP.id_pratica = P.id AND NP.data = CURDATE() AND NP.id_operatore IN (SELECT id_utente FROM utente WHERE gruppi_base = 3 OR gruppi_base = 12)
                             LEFT JOIN utente M ON P.id_mandante = M.id_utente           
                             LEFT JOIN lotti_mandante LM on P.id_lotto_mandante = LM.id
                             LEFT JOIN utente phc ON phc.id_utente = NP.id_operatore 
                    WHERE   (phc.gruppi_base = 3 OR phc.gruppi_base = 12) CONDIZIONI_NUOVE_DA_AGGIUNGERE
                    GROUP BY phc.id_utente
                    order by operatoremanaged) MANAGEDTBL ON WATBL.operatore = MANAGEDTBL.operatoremanaged ";


            if ($_POST['q'] != '' && $_POST['q'] != '()') {
                $query = str_replace('CONDIZIONI_NUOVE_DA_AGGIUNGERE', ' AND ' . $_POST['q'] . $where, $query);
            } else {
                $query = str_replace('CONDIZIONI_NUOVE_DA_AGGIUNGERE', ' ' . $where, $query);

            }


            $ret = ' <div class="table-responsive col-md-12">
						<table class="table table-striped table-hover" style="border:1px solid #DDD">
							<thead>
								<tr>
									<th>OPERATORE</th>								
									<th style="text-align: center;">WORKABLE ACCOUNTS</th>						
									<th style="text-align: center;">CHIAMATE PUNTUALI</th>						
									<th style="text-align: center;">NUOVE PRATICHE</th>						
									<th style="text-align: center;">SCHEDULAZIONI</th>						
									<th style="text-align: center;">SOSPESE</th>						
									<th style="text-align: center;">MANAGED</th>										
								</tr>
							</thead>
							<tbody>';


            $string = "OPERATORE;WORKABLE ACCOUNTS;CHIAMATE PUNTUALI;NUOVE PRATICHE;SCHEDULAZIONI;SOSPESE;MANAGED;";
            $string .= PHP_EOL;


            $ris = db_query($query);
            if (mysql_num_rows($ris) > 0) {

                while ($row = mysql_fetch_array($ris)) {
                    $string .= $row['operatore'] . ";" . $row['WA'] . ";" . $row['CHPT'] . ";" . $row['NVPT'] . ";" . $row['SCHD'] . ";" . $row['SOPS'] . ";" . $row['MANAGED'] . ";";

                    $ret .= '<tr>
												<td>' . $row['operatore'] . '</td>
												<td style="text-align: center;"><b><a href="javascript:void(0);" class="dynamic-modal-ajax-request"  data-modal-level="1" data-modal-page="ajax_visualizza_prt" data-id="' . $row['idswa'] . '" data-params="" >' . $row['WA'] . '</a></b></td>
												<td style="text-align: center;">' . $row['CHPT'] . '</td>
												<td style="text-align: center;">' . $row['NVPT'] . '</td>
												<td style="text-align: center;">' . $row['SCHD'] . '</td>
												<td style="text-align: center;">' . $row['SOPS'] . '</td>
												<td style="text-align: center;"><b><a href="javascript:void(0);" class="dynamic-modal-ajax-request" data-modal-level="1" data-modal-page="ajax_visualizza_prt" data-id="' . $row['idsmanaged'] . '" data-params="" >' . $row['MANAGED'] . '</a></b></td>
												</tr>';


                    $string .= PHP_EOL;

                }

            }
            $string .= PHP_EOL;

            writeSession('file_export_dati', $string);
            $return['csv'] = ' <a target="_blank" class="btn btn-primary"
                           href="./scarica_export_dati.php?name=kpi_' . date('Y-m-d') . '.csv"><i class=" fa fa-download"></i>  DOWNLOAD RISULTATI</a>';
            $ret .= '</tbody>
						</table>
					</div> ';


            $arrayGraficoUNO = [];

            $queryGraficoUNO = "SELECT * FROM (SELECT IFNULL(CONCAT(phc.cognome, ' ', phc.nome),'SENZA OPERATORE')   as operatore,
                       SUM(IF(scadenzaSospesa=0,1,0))                                                   as WA,
             SUM(IF(scadenzaSospesa=0,PS.chiamata_puntuale,0))                                                   as CHPT,
             SUM(IF(scadenzaSospesa=0,PS.nuova_pratica,0))                                                   as NVPT,
             SUM(IF(PS.chiamata_puntuale=0 AND PS.nuova_pratica=0 AND scadenzaSospesa=0,1,0))                                                   as SCHD,
             SUM(IF(scadenzaSospesa=1,1,0))                                                   as SOPS
               FROM pratiche P
                        LEFT JOIN utente M ON P.id_mandante = M.id_utente
                        LEFT JOIN scadenze PS on P.id = PS.id_pratica and PS.id IN (select id from scadenze where stato <>2 AND id_pratica=P.id) 
                                                    and CONCAT(PS.data,' ',PS.ora) = (select MIN(CONCAT(data,' ',ora)) from scadenze where stato <>2 AND id_pratica=P.id AND schedulazione =1) 
                                                    AND ( PS.stato =0 OR PS.stato =1)                    
                        LEFT JOIN lotti_mandante LM on P.id_lotto_mandante = LM.id
                        LEFT JOIN utente phc ON phc.id_utente = PS.id_destinatario 
               WHERE P.scadenzaSospesa=0 AND PS.schedulazione=1 AND PS.stato<>2 AND DATE(CONCAT(PS.data, ' ', IFNULL(PS.ora, ''))) <= NOW()
                 AND PS.data > '0000-00-00'
                 AND (phc.gruppi_base = 3 OR phc.gruppi_base = 12) CONDIZIONI_NUOVE_DA_AGGIUNGERE
               GROUP BY  phc.id_utente
               ORDER BY operatore

              ) WATBL
                  LEFT JOIN (SELECT CONCAT(phc.cognome, ' ', phc.nome)   as operatoremanaged,
                                    count(DISTINCT P.id)                      as MANAGED
                             FROM pratiche P
                             JOIN note_su_pratica NP ON NP.id_pratica = P.id AND NP.data = CURDATE() AND NP.id_operatore IN (SELECT id_utente FROM utente WHERE gruppi_base = 3 OR gruppi_base = 12)
                             LEFT JOIN utente M ON P.id_mandante = M.id_utente           
                             LEFT JOIN lotti_mandante LM on P.id_lotto_mandante = LM.id
                             LEFT JOIN utente phc ON phc.id_utente = NP.id_operatore 
                             WHERE (phc.gruppi_base = 3 OR phc.gruppi_base = 12) CONDIZIONI_NUOVE_DA_AGGIUNGERE
                             GROUP BY phc.id_utente ORDER BY operatoremanaged ) MANAGEDTBL ON WATBL.operatore=MANAGEDTBL.operatoremanaged ";


            if ($_POST['q'] != '' && $_POST['q'] != '()') {
                $queryGraficoUNO = str_replace('CONDIZIONI_NUOVE_DA_AGGIUNGERE', ' AND ' . $_POST['q'], $queryGraficoUNO);
            } else {
                $queryGraficoUNO = str_replace('CONDIZIONI_NUOVE_DA_AGGIUNGERE', ' ', $queryGraficoUNO);

            }


            $arrayGraficoUNO[0][] = 'OPERATORI';
            $arrayGraficoUNO[0][] = "WORKABLE ACCOUNTS";
            $arrayGraficoUNO[0][] = "CHIAMATE PUNTUALI";
            $arrayGraficoUNO[0][] = "NUOVE PRATICHE";
            $arrayGraficoUNO[0][] = "SCHEDULAZIONI";
            $arrayGraficoUNO[0][] = "SOSPESE";
            $arrayGraficoUNO[0][] = "MANAGED";

            $ris = db_query($queryGraficoUNO);
            $i = 1;
            while ($row = mysql_fetch_array($ris)) {

                $arrayGraficoUNO[$i][] = $row['operatore'];
                $arrayGraficoUNO[$i][] = floatval($row['WA']);
                $arrayGraficoUNO[$i][] = floatval($row['CHPT']);
                $arrayGraficoUNO[$i][] = floatval($row['NVPT']);
                $arrayGraficoUNO[$i][] = floatval($row['SCHD']);
                $arrayGraficoUNO[$i][] = floatval($row['SOPS']);
                $arrayGraficoUNO[$i][] = floatval($row['MANAGED']);
                $i++;
            }


            $return['graficoUNO'] = $arrayGraficoUNO;
            $return['htmlTable'] = $ret;
            $return['query1'] = $query;
            $return['queryGraficoUno'] = $queryGraficoUNO;

            print_r(json_encode($return));
        }
        break;
    case 'filtro-workable-accounts-team':
        {

            $where = '';
            if ($_SESSION['user_role'] == CAPO_PHONE_COLLECTOR) {
                $tuttiTeam = implode(',', getTeamListFromUser($_SESSION['user_admin_id']));
                $where .= ' AND (P.id_collector IN (' . $_SESSION['user_admin_id'] . ')';
                $where .= ' OR C.id_team IN(' . $tuttiTeam . '))';
                $where .= ' AND (archiviata = 0 OR archiviata IS NULL)';
            }


            if ($_SESSION['user_role'] == MANDANTE) {
                $where .= ' AND (P.id_mandante =' . $_SESSION['user_admin_id'] . ') ';
            }


            $ruoloUtente = $_SESSION['user_role'];
            $query = "SELECT * FROM ( SELECT group_concat(DISTINCT P.id)               as idswa,
                       GROUP_CONCAT(DISTINCT CONCAT(M.cognome, ' ', M.nome) SEPARATOR '<br>')   as mandante,
                       T.descrizione  as team,
                       GROUP_CONCAT(DISTINCT IFNULL(CONCAT(phc.cognome, ' ', phc.nome),'SENZA OPERATORE') SEPARATOR'<br>') as operatori,
                       count(DISTINCT P.id)                      as WA
                FROM pratiche P
                        LEFT JOIN contratto C ON P.id_contratto = C.id
                        LEFT JOIN utente M ON P.id_mandante = M.id_utente
                            LEFT JOIN scadenze PS on P.id = PS.id_pratica and PS.id IN (select id from scadenze where stato <>2 AND id_pratica=P.id) 
                                                    and CONCAT(PS.data,' ',PS.ora) = (select MIN(CONCAT(data,' ',ora)) from scadenze where stato <>2 AND id_pratica=P.id AND schedulazione =1) 
                                                    AND ( PS.stato =0 OR PS.stato =1)                    
                    
                         LEFT JOIN team_composizione TC on Tc.id_operatore = PS.id_destinatario OR Tc.id_operatore= P.id_collector
                         LEFT JOIN lotti_mandante LM on P.id_lotto_mandante = LM.id
                         LEFT JOIN team T on T.id = TC.id_team
                         LEFT JOIN utente phc ON phc.id_utente=PS.id_destinatario OR phc.id_utente=P.id_collector
                WHERE P.scadenzaSospesa=0 AND PS.schedulazione=1 AND IF(PS.data IS NULL, DATE(P.data_prima_schedulazione), DATE(CONCAT(PS.data, ' ', IFNULL(PS.ora, '')))) <= NOW()
                  AND IF(PS.data IS NULL, IFNULL(P.data_prima_schedulazione, '0000-00-00'), PS.data > '0000-00-00') > '0000-00-00'
                  AND (phc.gruppi_base = 3 OR phc.gruppi_base = 12)    CONDIZIONI_NUOVE_DA_AGGIUNGERE            GROUP BY T.id
                order by T.descrizione

              ) WATBL
                  LEFT JOIN (SELECT group_concat(DISTINCT P.id)               as idsmanaged,
                                    T.descrizione  as teammanaged,
                                    count(DISTINCT P.id)                      as MANAGED
                             FROM pratiche P
                                      LEFT JOIN contratto C ON P.id_contratto = C.id
                                      JOIN note_su_pratica NP ON NP.id_pratica = P.id AND NP.data = CURDATE()
                                      LEFT JOIN utente M ON P.id_mandante = M.id_utente
                                      LEFT JOIN scadenze PS on P.id = PS.id_pratica and PS.id IN (select id from scadenze where stato <>2 AND id_pratica=P.id) 
                                                    and CONCAT(PS.data,' ',PS.ora) = (select MIN(CONCAT(data,' ',ora)) from scadenze where stato <>2 AND id_pratica=P.id AND schedulazione =1) 
                                                    AND ( PS.stato =0 OR PS.stato =1)                    
                                      LEFT JOIN team_composizione TC on Tc.id_operatore = PS.id_destinatario OR Tc.id_operatore= P.id_collector
                                      LEFT JOIN lotti_mandante LM on P.id_lotto_mandante = LM.id
                                      LEFT JOIN team T on T.id = TC.id_team
                                      LEFT JOIN utente phc ON phc.id_utente=PS.id_destinatario OR phc.id_utente=P.id_collector
                             WHERE P.scadenzaSospesa=0 AND PS.schedulazione=1 AND (phc.gruppi_base = 3 OR phc.gruppi_base = 12)      CONDIZIONI_NUOVE_DA_AGGIUNGERE                       GROUP BY T.id
                             order by T.descrizione ) MANAGEDTBL ON WATBL.team=MANAGEDTBL.teammanaged ";


            if ($_POST['q'] != '' && $_POST['q'] != '()') {
                $query = str_replace('CONDIZIONI_NUOVE_DA_AGGIUNGERE', ' AND ' . $_POST['q'] . $where, $query);
            } else {
                $query = str_replace('CONDIZIONI_NUOVE_DA_AGGIUNGERE', ' ' . $where, $query);

            }


            $ret = ' <div class="table-responsive col-md-12">
						<table class="table table-striped table-hover" style="border:1px solid #DDD">
							<thead>
								<tr>
									<th>TEAM</th>						
									<th>WORKABLE ACCOUNTS</th>						
									<th>MANAGED</th>										
								</tr>
							</thead>
							<tbody>';


            $string = "TEAM;WORKABLE ACCOUNTS;MANAGED;";
            $string .= PHP_EOL;

            $ris = db_query($query);
            if (mysql_num_rows($ris) > 0) {

                while ($row = mysql_fetch_array($ris)) {
                    $string .= $row['team'] . ";" . $row['WA'] . ";" . $row['MANAGED'] . ";";

                    $ret .= '<tr>
												<td>' . $row['team'] . '</td>
												<td><a href="javascript:void(0);" class="dynamic-modal-ajax-request"  data-modal-level="1" data-modal-page="ajax_visualizza_prt" data-id="' . $row['idswa'] . '" data-params="" >' . $row['WA'] . '</a></td>
												<td><a href="javascript:void(0);" class="dynamic-modal-ajax-request" data-modal-level="1" data-modal-page="ajax_visualizza_prt" data-id="' . $row['idsmanaged'] . '" data-params="" >' . $row['MANAGED'] . '</a></td>
												</tr>';


                    $string .= PHP_EOL;

                }

            }
            $string .= PHP_EOL;

            writeSession('file_export_dati', $string);
            $return['csv'] = ' <a target="_blank" class="btn btn-primary"
                           href="./scarica_export_dati.php?name=kpi_' . date('Y-m-d') . '.csv"><i class=" fa fa-download"></i>  DOWNLOAD RISULTATI</a>';
            $ret .= '</tbody>
						</table>
					</div> ';


            $arrayGraficoUNO = [];

            $queryGraficoUNO = "SELECT * FROM (SELECT  T.descrizione  as team,
                         count(DISTINCT P.id)                      as WA
                  FROM pratiche P
                           LEFT JOIN utente M ON P.id_mandante = M.id_utente
                            LEFT JOIN scadenze PS on P.id = PS.id_pratica and PS.id IN (select id from scadenze where stato <>2 AND id_pratica=P.id) 
                                                    and CONCAT(PS.data,' ',PS.ora) = (select MIN(CONCAT(data,' ',ora)) from scadenze where stato <>2 AND id_pratica=P.id AND schedulazione =1) 
                                                    AND ( PS.stato =0 OR PS.stato =1)                    
                           LEFT JOIN team_composizione TC on Tc.id_operatore = PS.id_destinatario OR Tc.id_operatore= P.id_collector
                         LEFT JOIN lotti_mandante LM on P.id_lotto_mandante = LM.id
                         LEFT JOIN team T on T.id = TC.id_team
                         LEFT JOIN utente phc ON phc.id_utente=PS.id_destinatario OR phc.id_utente=P.id_collector
                WHERE P.scadenzaSospesa=0 AND PS.schedulazione=1 AND IF(PS.data IS NULL, DATE(P.data_prima_schedulazione), DATE(CONCAT(PS.data, ' ', IFNULL(PS.ora, '')))) <= NOW()
                  AND IF(PS.data IS NULL, IFNULL(P.data_prima_schedulazione, '0000-00-00'), PS.data > '0000-00-00') > '0000-00-00'
                  AND (phc.gruppi_base = 3 OR phc.gruppi_base = 12)    CONDIZIONI_NUOVE_DA_AGGIUNGERE
                  GROUP BY  T.descrizione ORDER BY team 

                  ) WATBL
LEFT JOIN (SELECT   T.descrizione  as teammanaged, count(DISTINCT P.id)  as MANAGED
           FROM pratiche P
                    JOIN note_su_pratica NP ON NP.id_pratica = P.id AND NP.data = CURDATE()
                    LEFT JOIN utente M ON P.id_mandante = M.id_utente
                    LEFT JOIN scadenze PS on P.id = PS.id_pratica and PS.id IN (select id from scadenze where stato <>2 AND id_pratica=P.id) 
                                                    and CONCAT(PS.data,' ',PS.ora) = (select MIN(CONCAT(data,' ',ora)) from scadenze where stato <>2 AND id_pratica=P.id AND schedulazione =1) 
                                                    AND ( PS.stato =0 OR PS.stato =1)                    
                   LEFT JOIN team_composizione TC on Tc.id_operatore = PS.id_destinatario OR Tc.id_operatore= P.id_collector
                                      LEFT JOIN lotti_mandante LM on P.id_lotto_mandante = LM.id
                                      LEFT JOIN team T on T.id = TC.id_team
                                      LEFT JOIN utente phc ON phc.id_utente=PS.id_destinatario OR phc.id_utente=P.id_collector
                             WHERE P.scadenzaSospesa=0 AND PS.schedulazione=1 AND  (phc.gruppi_base = 3 OR phc.gruppi_base = 12)      CONDIZIONI_NUOVE_DA_AGGIUNGERE
                             GROUP BY  T.descrizione ORDER BY teammanaged 
 ) MANAGEDTBL ON WATBL.team=MANAGEDTBL.teammanaged ";


            if ($_POST['q'] != '' && $_POST['q'] != '()') {
                $queryGraficoUNO = str_replace('CONDIZIONI_NUOVE_DA_AGGIUNGERE', ' AND ' . $_POST['q'], $queryGraficoUNO);
            } else {
                $queryGraficoUNO = str_replace('CONDIZIONI_NUOVE_DA_AGGIUNGERE', ' ', $queryGraficoUNO);

            }


            $arrayGraficoUNO[0][] = 'TEAM';
            $arrayGraficoUNO[0][] = "WORKABLE ACCOUNTS";
            $arrayGraficoUNO[0][] = "MANAGED";

            $ris = db_query($queryGraficoUNO);
            $i = 1;
            while ($row = mysql_fetch_array($ris)) {

                $arrayGraficoUNO[$i][] = $row['team'];
                $arrayGraficoUNO[$i][] = floatval($row['WA']);
                $arrayGraficoUNO[$i][] = floatval($row['MANAGED']);
                $i++;
            }


            $return['graficoUNO'] = $arrayGraficoUNO;
            $return['htmlTable'] = $ret;

            print_r(json_encode($return));
        }
        break;
    case 'filtro-workable-accounts-mandante':
        {
            $ruoloUtente = $_SESSION['user_role'];

            $where = '';
            if ($_SESSION['user_role'] == MANDANTE) {
                $where = ' AND (P.id_mandante =' . $_SESSION['user_admin_id'] . ') ';
            }
            $query = "SELECT * FROM ( SELECT group_concat(DISTINCT P.id)               as idswa,
                       CONCAT(M.cognome, ' ', M.nome)    as mandante,
                       GROUP_CONCAT(DISTINCT IFNULL(CONCAT(phc.cognome, ' ', phc.nome),'SENZA OPERATORE')SEPARATOR '<br>')   as operatori,
                       GROUP_CONCAT(DISTINCT LM.descrizione SEPARATOR'<br>') as lotto,
                       count(DISTINCT P.id)                      as WA
                FROM pratiche P
                         LEFT JOIN utente M ON P.id_mandante = M.id_utente
                         LEFT JOIN scadenze PS on P.id = PS.id_pratica and PS.id IN (select id from scadenze where stato <>2 AND id_pratica=P.id) 
                                                    and CONCAT(PS.data,' ',PS.ora) = (select MIN(CONCAT(data,' ',ora)) from scadenze where stato <>2 AND id_pratica=P.id AND schedulazione =1) 
                                                    AND ( PS.stato =0 OR PS.stato =1)                    
                         LEFT JOIN team_composizione TC on Tc.id_operatore = PS.id_destinatario OR TC.id_operatore=P.id_collector
                         LEFT JOIN lotti_mandante LM on P.id_lotto_mandante = LM.id
                         LEFT JOIN team T on T.id = TC.id_team
                         LEFT JOIN utente phc ON phc.id_utente=PS.id_destinatario OR  phc.id_utente=P.id_collector
                WHERE P.scadenzaSospesa=0 AND PS.schedulazione=1 AND  IF(PS.data IS NULL, DATE(P.data_prima_schedulazione), DATE(CONCAT(PS.data, ' ', IFNULL(PS.ora, '')))) <= NOW()
                  AND IF(PS.data IS NULL, IFNULL(P.data_prima_schedulazione, '0000-00-00'), PS.data > '0000-00-00') > '0000-00-00'
                  AND (phc.gruppi_base = 3 OR phc.gruppi_base = 12)    CONDIZIONI_NUOVE_DA_AGGIUNGERE      GROUP BY P.id_mandante
                order by mandante
              ) WATBL
                  LEFT JOIN (SELECT group_concat(DISTINCT P.id)               as idsmanaged,
                                    CONCAT(M.cognome, ' ', M.nome)   as mandantemanaged,
                                    count(DISTINCT P.id)                      as MANAGED
                             FROM pratiche P
                                      JOIN note_su_pratica NP ON NP.id_pratica = P.id AND NP.data = CURDATE()
                                      LEFT JOIN utente M ON P.id_mandante = M.id_utente
                                      LEFT JOIN scadenze PS on P.id = PS.id_pratica and PS.id IN (select id from scadenze where stato <>2 AND id_pratica=P.id) 
                                                    and CONCAT(PS.data,' ',PS.ora) = (select MIN(CONCAT(data,' ',ora)) from scadenze where stato <>2 AND id_pratica=P.id AND schedulazione =1) 
                                                    AND ( PS.stato =0 OR PS.stato =1)                    
                             LEFT JOIN team_composizione TC on Tc.id_operatore = PS.id_destinatario OR TC.id_operatore=P.id_collector
                         LEFT JOIN lotti_mandante LM on P.id_lotto_mandante = LM.id
                         LEFT JOIN team T on T.id = TC.id_team
                         LEFT JOIN utente phc ON phc.id_utente=PS.id_destinatario OR  phc.id_utente=P.id_collector
                WHERE P.scadenzaSospesa=0 AND PS.schedulazione=1 AND  (phc.gruppi_base = 3 OR phc.gruppi_base = 12) " . $where . " CONDIZIONI_NUOVE_DA_AGGIUNGERE
                             GROUP BY P.id_mandante
                             order by mandantemanaged ) MANAGEDTBL ON WATBL.mandante=MANAGEDTBL.mandantemanaged ";


            if ($_POST['q'] != '' && $_POST['q'] != '()') {
                $query = str_replace('CONDIZIONI_NUOVE_DA_AGGIUNGERE', ' AND ' . $_POST['q'], $query);
            } else {
                $query = str_replace('CONDIZIONI_NUOVE_DA_AGGIUNGERE', ' ', $query);

            }

            $ret = ' <div class="table-responsive col-md-12">
						<table class="table table-striped table-hover" style="border:1px solid #DDD">
							<thead>
								<tr>
									<th>MANDANTE</th>										
									<th>WORKABLE ACCOUNTS</th>						
									<th>MANAGED</th>										
								</tr>
							</thead>
							<tbody>';


            $string = "MANDANTE;WORKABLE ACCOUNTS;MANAGED;";
            $string .= PHP_EOL;

            $ris = db_query($query);
            if (mysql_num_rows($ris) > 0) {

                while ($row = mysql_fetch_array($ris)) {
                    $string .= $row['mandante'] . ";" . $row['WA'] . ";" . $row['MANAGED'] . ";";

                    $ret .= '<tr>
												<td>' . $row['mandante'] . '</td>
												<td><a href="javascript:void(0);" class="dynamic-modal-ajax-request"  data-modal-level="1" data-modal-page="ajax_visualizza_prt" data-id="' . $row['idswa'] . '" data-params="" >' . $row['WA'] . '</a></td>
												<td><a href="javascript:void(0);" class="dynamic-modal-ajax-request" data-modal-level="1" data-modal-page="ajax_visualizza_prt" data-id="' . $row['idsmanaged'] . '" data-params="" >' . $row['MANAGED'] . '</a></td>
												</tr>';


                    $string .= PHP_EOL;

                }

            }
            $string .= PHP_EOL;

            writeSession('file_export_dati', $string);
            $return['csv'] = ' <a target="_blank" class="btn btn-primary"
                           href="./scarica_export_dati.php?name=kpi_' . date('Y-m-d') . '.csv"><i class=" fa fa-download"></i>  DOWNLOAD RISULTATI</a>';
            $ret .= '</tbody>
						</table>
					</div> ';


            $arrayGraficoUNO = [];

            $queryGraficoUNO = "SELECT * FROM (SELECT CONCAT(M.cognome, ' ', M.nome)    as mandante,
                         count(DISTINCT P.id)                      as WA
                  FROM pratiche P
                           LEFT JOIN utente M ON P.id_mandante = M.id_utente
                            LEFT JOIN scadenze PS on P.id = PS.id_pratica and PS.id IN (select id from scadenze where stato <>2 AND id_pratica=P.id) 
                                                    and CONCAT(PS.data,' ',PS.ora) = (select MIN(CONCAT(data,' ',ora)) from scadenze where stato <>2 AND id_pratica=P.id AND schedulazione =1) 
                                                    AND ( PS.stato =0 OR PS.stato =1)                    
                           LEFT JOIN team_composizione TC on Tc.id_operatore = PS.id_destinatario OR TC.id_operatore=P.id_collector
                         LEFT JOIN lotti_mandante LM on P.id_lotto_mandante = LM.id
                         LEFT JOIN team T on T.id = TC.id_team
                         LEFT JOIN utente phc ON phc.id_utente=PS.id_destinatario OR  phc.id_utente=P.id_collector
                WHERE P.scadenzaSospesa=0 AND PS.schedulazione=1 AND  IF(PS.data IS NULL, DATE(P.data_prima_schedulazione), DATE(CONCAT(PS.data, ' ', IFNULL(PS.ora, '')))) <= NOW()
                  AND IF(PS.data IS NULL, IFNULL(P.data_prima_schedulazione, '0000-00-00'), PS.data > '0000-00-00') > '0000-00-00'
                  AND (phc.gruppi_base = 3 OR phc.gruppi_base = 12)    CONDIZIONI_NUOVE_DA_AGGIUNGERE
                  GROUP BY  P.id_mandante 

                  ) WATBL
LEFT JOIN (SELECT CONCAT(M.cognome, ' ', M.nome)    as mandantemanaged,
                  count(DISTINCT P.id)                      as MANAGED
           FROM pratiche P
                    JOIN note_su_pratica NP ON NP.id_pratica = P.id AND NP.data = CURDATE()
                    LEFT JOIN utente M ON P.id_mandante = M.id_utente
                     LEFT JOIN scadenze PS on P.id = PS.id_pratica and PS.id IN (select id from scadenze where stato <>2 AND id_pratica=P.id) 
                                                    and CONCAT(PS.data,' ',PS.ora) = (select MIN(CONCAT(data,' ',ora)) from scadenze where stato <>2 AND id_pratica=P.id AND schedulazione =1) 
                                                    AND ( PS.stato =0 OR PS.stato =1)                    
                   LEFT JOIN team_composizione TC on Tc.id_operatore = PS.id_destinatario OR TC.id_operatore=P.id_collector
                         LEFT JOIN lotti_mandante LM on P.id_lotto_mandante = LM.id
                         LEFT JOIN team T on T.id = TC.id_team
                         LEFT JOIN utente phc ON phc.id_utente=PS.id_destinatario OR  phc.id_utente=P.id_collector
                WHERE P.scadenzaSospesa=0 AND PS.schedulazione=1 AND  (phc.gruppi_base = 3 OR phc.gruppi_base = 12)  " . $where . "  CONDIZIONI_NUOVE_DA_AGGIUNGERE
           GROUP BY P.id_mandante  ) MANAGEDTBL ON WATBL.mandante=MANAGEDTBL.mandantemanaged ";


            if ($_POST['q'] != '' && $_POST['q'] != '()') {
                $queryGraficoUNO = str_replace('CONDIZIONI_NUOVE_DA_AGGIUNGERE', ' AND ' . $_POST['q'], $queryGraficoUNO);
            } else {
                $queryGraficoUNO = str_replace('CONDIZIONI_NUOVE_DA_AGGIUNGERE', ' ', $queryGraficoUNO);

            }


            $arrayGraficoUNO[0][] = 'MANDANTI';
            $arrayGraficoUNO[0][] = "WORKABLE ACCOUNTS";
            $arrayGraficoUNO[0][] = "MANAGED";

            $ris = db_query($queryGraficoUNO);
            $i = 1;
            while ($row = mysql_fetch_array($ris)) {

                $arrayGraficoUNO[$i][] = $row['mandante'];
                $arrayGraficoUNO[$i][] = floatval($row['WA']);
                $arrayGraficoUNO[$i][] = floatval($row['MANAGED']);
                $i++;
            }


            $return['graficoUNO'] = $arrayGraficoUNO;
            $return['htmlTable'] = $ret;

            print_r(json_encode($return));
        }
        break;
    case 'filtro-workable-accounts-lotto':
        {


            $where = '';
            if ($_SESSION['user_role'] == MANDANTE) {
                $where = ' AND (P.id_mandante =' . $_SESSION['user_admin_id'] . ')  ';
            }

            $ruoloUtente = $_SESSION['user_role'];
            $query = "SELECT * FROM ( SELECT group_concat(IF(scadenzaSospesa=0,P.id,0))               as idswa,
                          CONCAT(M.cognome, ' ', M.nome)    as mandante,
                        GROUP_CONCAT( DISTINCT IFNULL(CONCAT(phc.cognome, ' ', phc.nome),'SENZA OPERATORE') SEPARATOR'<br>')   as operatore,
                         LM.descrizione as lotto,
                         SUM(IF(scadenzaSospesa=0,1,0))                       as WA
                  FROM pratiche P
                            LEFT JOIN contratto C ON P.id_contratto = C.id
                           LEFT JOIN utente M ON P.id_mandante = M.id_utente
                           LEFT JOIN scadenze PS on P.id = PS.id_pratica and PS.id IN (select id from scadenze where stato <>2 AND id_pratica=P.id)  and CONCAT(PS.data,' ',PS.ora) = (select MIN(CONCAT(data,' ',ora)) from scadenze where stato <>2 AND id_pratica=P.id AND schedulazione =1) 
                                                    AND ( PS.stato =0 OR PS.stato =1)                    
                         LEFT JOIN lotti_mandante LM on P.id_lotto_mandante = LM.id
                         LEFT JOIN utente ML ON ML.id_utente = LM.id_mandante
                        LEFT JOIN utente phc ON phc.id_utente = PS.id_destinatario 
                WHERE PS.schedulazione=1 AND PS.stato<>2 AND DATE(CONCAT(PS.data, ' ', IFNULL(PS.ora, ''))) <= NOW()
        AND  PS.data > '0000-00-00'
        AND (phc.gruppi_base = 3 OR phc.gruppi_base = 12)  CONDIZIONI_NUOVE_DA_AGGIUNGERE
                  GROUP BY LM.descrizione
                  order by LM.descrizione

                  ) WATBL
LEFT JOIN (SELECT group_concat(DISTINCT P.id)               as idsmanaged,
                  LM.descrizione as lottomanaged,
                  count(DISTINCT P.id)                      as MANAGED
           FROM pratiche P
            LEFT JOIN contratto C ON P.id_contratto = C.id
         JOIN note_su_pratica NP ON NP.id_pratica = P.id AND NP.data = CURDATE() AND NP.id_operatore IN (SELECT id_utente FROM utente WHERE gruppi_base = 3 OR gruppi_base = 12)
                             LEFT JOIN utente M ON P.id_mandante = M.id_utente           
                             LEFT JOIN lotti_mandante LM on P.id_lotto_mandante = LM.id
                             LEFT JOIN utente phc ON phc.id_utente = NP.id_operatore 
                    WHERE   (phc.gruppi_base = 3 OR phc.gruppi_base = 12)  CONDIZIONI_NUOVE_DA_AGGIUNGERE
           GROUP BY LM.descrizione
           order by LM.descrizione ) MANAGEDTBL ON WATBL.lotto=MANAGEDTBL.lottomanaged ";


            if ($_POST['q'] != '' && $_POST['q'] != '()') {
                $query = str_replace('CONDIZIONI_NUOVE_DA_AGGIUNGERE', ' AND ' . $_POST['q'] . $where, $query);
            } else {
                $query = str_replace('CONDIZIONI_NUOVE_DA_AGGIUNGERE', ' ' . $where, $query);

            }


            $ret = ' <div class="table-responsive col-md-12">
						<table class="table table-striped table-hover" style="border:1px solid #DDD">
							<thead>
								<tr>
									<th>LOTTO</th>						
									<th>MANDANTE</th>						
									<th>WORKABLE ACCOUNTS</th>						
									<th>MANAGED</th>										
								</tr>
							</thead>
							<tbody>';


            $string = "LOTTO;MANDANTE;WORKABLE ACCOUNTS;MANAGED;";
            $string .= PHP_EOL;

            $ris = db_query($query);
            if (mysql_num_rows($ris) > 0) {

                while ($row = mysql_fetch_array($ris)) {
                    $string .= $row['lotto'] . ";" . $row['mandante'] . ";" . $row['WA'] . ";" . $row['MANAGED'] . ";";

                    $ret .= '<tr>
												<td>' . $row['lotto'] . '</td>
												<td><div>' . $row['mandante'] . '</div></td>
												<td><a href="javascript:void(0);" class="dynamic-modal-ajax-request"  data-modal-level="1" data-modal-page="ajax_visualizza_prt" data-id="' . $row['idswa'] . '" data-params="" >' . $row['WA'] . '</a></td>
												<td><a href="javascript:void(0);" class="dynamic-modal-ajax-request" data-modal-level="1" data-modal-page="ajax_visualizza_prt" data-id="' . $row['idsmanaged'] . '" data-params="" >' . $row['MANAGED'] . '</a></td>
												</tr>';


                    $string .= PHP_EOL;

                }

            }
            $string .= PHP_EOL;

            writeSession('file_export_dati', $string);
            $return['csv'] = ' <a target="_blank" class="btn btn-primary"
                           href="./scarica_export_dati.php?name=kpi_' . date('Y-m-d') . '.csv"><i class=" fa fa-download"></i>  DOWNLOAD RISULTATI</a>';
            $ret .= '</tbody>
						</table>
					</div> ';


            $arrayGraficoUNO = [];

            $queryGraficoUNO = "SELECT * FROM (SELECT LM.descrizione   as lotto,
                          SUM(IF(scadenzaSospesa=0,1,0))                         as WA
                  FROM pratiche P
                            LEFT JOIN utente M ON P.id_mandante = M.id_utente
                        LEFT JOIN scadenze PS on P.id = PS.id_pratica and PS.id IN (select id from scadenze where stato <>2 AND id_pratica=P.id) 
                                                    and CONCAT(PS.data,' ',PS.ora) = (select MIN(CONCAT(data,' ',ora)) from scadenze where stato <>2 AND id_pratica=P.id AND schedulazione =1) 
                                                    AND ( PS.stato =0 OR PS.stato =1)                    
                        LEFT JOIN lotti_mandante LM on P.id_lotto_mandante = LM.id
                        LEFT JOIN utente phc ON phc.id_utente = PS.id_destinatario 
               WHERE P.scadenzaSospesa=0 AND PS.schedulazione=1 AND PS.stato<>2 AND DATE(CONCAT(PS.data, ' ', IFNULL(PS.ora, ''))) <= NOW()
                 AND PS.data > '0000-00-00'
                 AND (phc.gruppi_base = 3 OR phc.gruppi_base = 12) CONDIZIONI_NUOVE_DA_AGGIUNGERE
                  GROUP BY  LM.descrizione 

                  ) WATBL
LEFT JOIN (SELECT LM.descrizione   as lottomanaged,
                  count(DISTINCT P.id)                      as MANAGED
          FROM pratiche P
                             JOIN note_su_pratica NP ON NP.id_pratica = P.id AND NP.data = CURDATE() AND NP.id_operatore IN (SELECT id_utente FROM utente WHERE gruppi_base = 3 OR gruppi_base = 12)
                             LEFT JOIN utente M ON P.id_mandante = M.id_utente           
                             LEFT JOIN lotti_mandante LM on P.id_lotto_mandante = LM.id
                             LEFT JOIN utente phc ON phc.id_utente = NP.id_operatore 
                             WHERE (phc.gruppi_base = 3 OR phc.gruppi_base = 12)   CONDIZIONI_NUOVE_DA_AGGIUNGERE
           GROUP BY LM.descrizione  ) MANAGEDTBL ON WATBL.lotto=MANAGEDTBL.lottomanaged ";


            if ($_POST['q'] != '' && $_POST['q'] != '()') {
                $queryGraficoUNO = str_replace('CONDIZIONI_NUOVE_DA_AGGIUNGERE', ' AND ' . $_POST['q'] . $where, $queryGraficoUNO);
            } else {
                $queryGraficoUNO = str_replace('CONDIZIONI_NUOVE_DA_AGGIUNGERE', ' ' . $where, $queryGraficoUNO);

            }


            $arrayGraficoUNO[0][] = 'LOTTO';
            $arrayGraficoUNO[0][] = "WORKABLE ACCOUNTS";
            $arrayGraficoUNO[0][] = "MANAGED";

            $ris = db_query($queryGraficoUNO);
            $i = 1;
            while ($row = mysql_fetch_array($ris)) {

                $arrayGraficoUNO[$i][] = $row['lotto'];
                $arrayGraficoUNO[$i][] = floatval($row['WA']);
                $arrayGraficoUNO[$i][] = floatval($row['MANAGED']);
                $i++;
            }


            $return['graficoUNO'] = $arrayGraficoUNO;
            $return['htmlTable'] = $ret;


            print_r(json_encode($return));
        }
        break;


    case 'salva-eventi-automatici':
        { //RESOCONTO PER CAPOESATTORI & ESATTORI

            //print_r($_POST['combinazioni']);
            //die();
            if (isset($_POST['combinazioni'])) {
                if ($_POST['combinazioni'] != "") {
                    $comibinazione = $_POST['combinazioni'];
                    $svuota_resocnoto = "DELETE from eventi_automatici ";
                    db_query($svuota_resocnoto);

                    foreach ($comibinazione as $comb) {

                        if ($comb[10] != "") {
                            $dataInizioTemp = explode('/', $comb[10]);
                            $dataIN = date('Y-m-d', strtotime($dataInizioTemp[2] . '-' . $dataInizioTemp[1] . '-' . $dataInizioTemp[0]));
                        } else {
                            $dataIN = "0000-00-00";
                        }
                        if ($comb[11] != "") {
                            $dataFineTemp = explode('/', $comb[11]);
                            $dataF = date('Y-m-d', strtotime($dataFineTemp[2] . '-' . $dataFineTemp[1] . '-' . $dataFineTemp[0]));

                        } else {
                            $dataF = "0000-00-00";
                        }


                        $query_resoconto = "INSERT INTO `eventi_automatici`
                                                        (`id_evento_strutturato`, `query_id_prt`, `lun`, `mar`, `mer`, `gio`, `ven`, `sab`, `dom`, `data_inizio`, `data_fine`, `attivo`,`descrizione`,`ora`) 
                                                VALUES ('" . db_input($comb[0]) . "','" . db_input($comb[1]) . "','" . db_input($comb[2]) . "','" . db_input($comb[3]) . "','" . db_input($comb[4]) . "','" . db_input($comb[5]) . "','" . db_input($comb[6]) . "','" . db_input($comb[7]) . "','" . db_input($comb[8]) . "','" . $dataIN . "','" . $dataF . "' ,'" . db_input($comb[9]) . "','" . db_input($comb[12]) . "','" . db_input($comb[13]) . "' )";

                        db_query($query_resoconto);
                    }

                    echo 1;
                } else {
                    $svuota_resocnoto = "DELETE from eventi_automatici ";
                    db_query($svuota_resocnoto);
                    echo 2;

                }
            } else {
                die("MANCANO PARAMETRI");
            }
        }
        break;

    case 'salva-report-automatici':
        { //RESOCONTO PER CAPOESATTORI & ESATTORI

            //print_r($_POST['combinazioni']);
            //die();
            if (isset($_POST['combinazioni'])) {
                if ($_POST['combinazioni'] != "") {
                    $comibinazione = $_POST['combinazioni'];
                    $svuota_resocnoto = "DELETE from report_automatici ";
                    db_query($svuota_resocnoto);

                    foreach ($comibinazione as $comb) {

                        if ($comb[10] != "") {
                            $dataInizioTemp = explode('/', $comb[10]);
                            $dataIN = date('Y-m-d', strtotime($dataInizioTemp[2] . '-' . $dataInizioTemp[1] . '-' . $dataInizioTemp[0]));
                        } else {
                            $dataIN = "0000-00-00";
                        }
                        if ($comb[11] != "") {
                            $dataFineTemp = explode('/', $comb[11]);
                            $dataF = date('Y-m-d', strtotime($dataFineTemp[2] . '-' . $dataFineTemp[1] . '-' . $dataFineTemp[0]));

                        } else {
                            $dataF = "0000-00-00";

                        }


                        $query_resoconto = "INSERT INTO `report_automatici`
                                                        (`id_export`, `mail_dest`, `lun`, `mar`, `mer`, `gio`, `ven`, `sab`, `dom`, `data_inizio`, `data_fine`, `attivo`, `descrizione`, `ora`) 
                                                VALUES ('" . db_input($comb[0]) . "','" . db_input($comb[1]) . "','" . db_input($comb[2]) . "','" . db_input($comb[3]) . "','" . db_input($comb[4]) . "','" . db_input($comb[5]) . "','" . db_input($comb[6]) . "','" . db_input($comb[7]) . "','" . db_input($comb[8]) . "','" . $dataIN . "','" . $dataF . "' ,'" . db_input($comb[9]) . "','" . db_input($comb[12]) . "' ,'" . db_input($comb[13]) . "' )";

                        db_query($query_resoconto);
                    }

                    echo 1;
                } else {
                    $svuota_resocnoto = "DELETE from report_automatici ";
                    db_query($svuota_resocnoto);
                    echo 2;

                }
            } else {
                die("MANCANO PARAMETRI");
            }
        }
        break;

    case 'salva-config-statistiche':
        { //RESOCONTO PER CAPOESATTORI & ESATTORI

            if (isset($_POST['combinazioni'])) {
                if ($_POST['combinazioni'] != "") {
                    $comibinazione = $_POST['combinazioni'];
                    $svuota_resocnoto = "DELETE from config_statistiche ";
                    db_query($svuota_resocnoto);

                    foreach ($comibinazione as $comb) {

                        $query_resoconto = "INSERT INTO `config_statistiche`
                                                        (`query_titolo`, `query_lotti`, `query_totali`, `query_collector`) 
                                                VALUES ('" . db_input($comb[0]) . "','" . db_input($comb[1]) . "','" . db_input($comb[2]) . "','" . db_input($comb[3]) . "' )";

                        db_query($query_resoconto);
                    }

                    echo 1;
                } else {
                    $svuota_resocnoto = "DELETE from config_statistiche ";
                    db_query($svuota_resocnoto);
                    echo 2;

                }
            } else {
                die("MANCANO PARAMETRI");
            }
        }
        break;

    case 'salva-config-statistiche-utente':
        { //RESOCONTO PER CAPOESATTORI & ESATTORI

            if (isset($_POST['combinazioni'])) {
                if ($_POST['combinazioni'] != "") {
                    $comibinazione = $_POST['combinazioni'];
                    $svuota_resocnoto = "DELETE from config_statistiche_utente ";
                    db_query($svuota_resocnoto);

                    foreach ($comibinazione as $comb) {

                        if (is_array($comb[4])) {
                            $visibi = implode(',', $comb[4]);
                        } else {
                            $visibi = $comb[4];
                        }
                        $query_resoconto = "INSERT INTO `config_statistiche_utente`
                                                        (`query_titolo`, `query_lotti`, `query_totali`, `query_collector`,`visibilita`,`colonne`) 
                                                VALUES ('" . db_input($comb[0]) . "','" . db_input($comb[1]) . "','" . db_input($comb[2]) . "','" . db_input($comb[3]) . "','" . db_input($visibi) . "','" . db_input($comb[5]) . "' )";

                        db_query($query_resoconto);
                    }

                    echo 1;
                } else {
                    $svuota_resocnoto = "DELETE from config_statistiche_utente ";
                    db_query($svuota_resocnoto);
                    echo 2;

                }
            } else {
                die("MANCANO PARAMETRI");
            }
        }
        break;

    case 'prossima-pratica-schedulata':
        {
            $ris['id_pratica'] = 0;

            $queryImpostazioni = "SELECT priorita_schedulazione
                                                FROM impostazioni_base I
                                                WHERE id=1";
            $risImpostazioni = db_query($queryImpostazioni);
            $impostazioni = mysql_fetch_assoc($risImpostazioni);

            $queryTeamPhc = "SELECT group_concat(id_team) FROM team_composizione WHERE id_operatore=" . db_input($_SESSION['user_admin_id']);

            $teamPhc = db_fetch_array(db_query($queryTeamPhc))[0][0];
            if ($teamPhc == "") {
                $teamPhc = -1;
            }
            $query = "SELECT IFNULL(P.id,0) as id_pratica,IF(PS.data IS NULL,data_prima_schedulazione,CONCAT(PS.data,' ',IF(POSITION(':' IN PS.ora)=2,CONCAT('0',PS.ora),PS.ora))) as data_schedulazione_alias
                                                FROM pratiche P";
            /*LEFT JOIN pratiche_affidamenti PA ON P.id = PA.id_pratica AND PA.id_affidamento IN (SELECT id FROM affidamenti WHERE id_collector = '" . db_input($_SESSION['user_admin_id']) . "' ORDER BY id DESC)
            LEFT JOIN affidamenti A ON A.id = PA.id_affidamento*/
            $query .= "  LEFT JOIN scadenze PS on P.id = PS.id_pratica and PS.id IN (select id from scadenze where stato <>2 AND id_pratica=P.id) 
                                                    and CONCAT(PS.data,' ',PS.ora) = (select MIN(CONCAT(data,' ',ora)) from scadenze where stato <>2 AND id_pratica=P.id  AND schedulazione =1)  AND ( PS.stato =0 OR PS.stato =1)
                                                    JOIN pratiche_dati_totali PdT on P.id =PdT.id
                                                    LEFT JOIN pratiche_mutex PRM ON PRM.id_pratica=P.id AND PRM.id_utente <> '" . db_input($_SESSION['user_admin_id']) . "'
                                                    WHERE PS.schedulazione=1 AND P.scadenzaSospesa=0 AND (DATE_ADD(PRM.lock_time, INTERVAL 15 minute) < NOW() OR PRM.lock_time is null)
                                                        AND 
                                                       (
                                                        (PS.id_destinatario = '" . db_input($_SESSION['user_admin_id']) . "' AND P.area_corrente = 5)
                                                            
                                                        OR
                                                        (PS.id_gruppo_destinatario in ( " . db_input($teamPhc) . ") AND P.area_corrente = 5)
                                                        )
                                                        AND IFNULL(PS.data,data_prima_schedulazione) <=CURDATE() 
                                                        AND IFNULL(PS.data,data_prima_schedulazione) <>'' AND IF(IFNULL(PS.chiamata_puntuale,0)=1,CONCAT(PS.data,' ',PS.ora)<=DATE_ADD(NOW() , INTERVAL 5 MINUTE),1=1)";

            if ($clienteGlobaleReMida != 'TC') {
                $query .= " AND (P.scaricata = 0 OR P.scaricata IS NULL) ";
            }

            $orderNP = 'DESC';
            if ($impostazioni['priorita_schedulazione'] == 'S') {
                $orderNP = 'ASC';
            }

            $query .= "GROUP BY P.id  
            ORDER BY 
            IF((IFNULL(PS.chiamata_puntuale,0) =1 AND data_schedulazione_alias <= NOW() AND data_schedulazione_alias >=CONCAT( CURRENT_DATE ,' ' ,'00:00')),1,0) DESC,
            IF((IFNULL(PS.chiamata_puntuale,0) =1 AND PS.data < CURRENT_DATE ),1,0) DESC,
            PS.nuova_pratica " . $orderNP . ", 
            if(data_schedulazione_alias = '' or data_schedulazione_alias is null,1,0),
            data_schedulazione_alias ASC, 
            (PdT.totale_da_recuperare-PdT.totale_recuperato) DESC 
            LIMIT 1";


            $ris_phc_prat = db_query($query);
            $elemento_phc_prat = mysql_fetch_assoc($ris_phc_prat);
            $ris['id_pratica'] = $elemento_phc_prat['id_pratica'];

            print_r(json_encode($ris));


        }
        break;

    case 'leggi-comunicazione':
        {
            comunicazioni_setStatoLetta($_POST['id_comunicazione']);
            $res['done'] = 1;
            print_r(json_encode($res));
        }
        break;
    case 'associa-allegati-pratica':
        {
            $cartellaAllegatiPrt = $_POST['percorso'];
            $id_classe = $_POST['classe'];
            $id_tipo = $_POST['tipo'];
            $id_pratica = explode('_', $cartellaAllegatiPrt)[2];
            $descrizione = "allegati Massivi";
            $reponse['done'] = true;
            $reponse['msg'] = "Allegati Caricati Correttamente";


            $provenienza_all = "pratica";
            $riferimento_all = $id_pratica;

            $query_tipologia = "Select allegabile_debitore as allDeb, allegabile_pratica as allPra from composizione_allegati where id='" . $id_tipo . "'";
            $result_tipo = db_query($query_tipologia);
            $tipologia_all = mysql_fetch_array($result_tipo);
            if ($tipologia_all['allDeb'] == '1' && $tipologia_all['allPra'] == '0') {
                $provenienza_all = "debitore";
                $query_id_utente_rif = "select id_debitore from pratiche where id='" . $id_pratica . "'";
                $resul_id_ut_rif = db_query($query_id_utente_rif);
                $id_ut_rif = mysql_fetch_array($resul_id_ut_rif);
                $riferimento_all = $id_ut_rif['id_debitore'];
            }


            if ($handle = opendir($cartellaAllegatiPrt)) {
                while (false !== ($file = readdir($handle))) {
                    if ('.' === $file) continue;
                    if ('..' === $file) continue;

                    $nome = $file;
                    // do something with the file
                    $fileBase64 = base64_encode(file_get_contents($cartellaAllegatiPrt . $file));

                    // UPDATING
                    // SALVATAGGIO INTESTAZIONE
                    if ($nome != '') {
                        $pieces_name = explode('.', $nome);
                        $ext = array_pop($pieces_name);
                        $name = implode('.', $pieces_name);
                        $nome = str_replace(' ', '_', $name . "_" . date('Ymd_His') . '.' . $ext);

                        $query_inserimento = "INSERT INTO allegati
						  SET data 			= CURDATE(),
						  	  nome			= '" . db_input($nome) . "',
							  descrizione	= '" . db_input($descrizione) . "',
							  classe		= '" . db_input($id_classe) . "',
							  tipologia		= '" . db_input($id_tipo) . "',
							  provenienza	= '" . db_input($provenienza_all) . "',
							  riferimento	= '" . db_input($riferimento_all) . "',
							  type			= '" . db_input($type) . "'";

                        $result = executeQuery($query_inserimento);
                    } else {
                        $reponse['done'] = false;
                        $reponse['msg'] = "Allegato non impostato";
                    }

                    if ($result === false) {
                        $reponse['done'] = false;
                        $reponse['msg'] = "Errore nel caricamento del file";
                    } else {
                        $result = allegati_salvaFile($nome, $fileBase64, $provenienza_all, $riferimento_all, true); // nome file aggiornato
                    }    ////////////// FINE trello#00031

                }
                closedir($handle);
            }
            print_r(json_encode($reponse));
        }
        break;

    case 'sposta-pratiche-rmbox-evento':
        {
            if (isset($_POST['id_evento'])) {
                $id_evento = $_POST['id_evento'];
                $id_utente = $_SESSION['user_admin_id'];

                $ripulisci_remidabox = "DELETE FROM `pratiche_remidabox` WHERE id_utente='" . $id_utente . "'";
                db_query($ripulisci_remidabox);


                //$queryGetPrt="SELECT pratiche_allowed FROM `eventi_pratiche_strutturati` WHERE id='".$id_evento."'";
                $queryGetPrt = "SELECT IF(pratiche NOT LIKE '%:%',pratiche,pratiche_allowed) AS pratiche_allowed FROM `eventi_pratiche_strutturati` WHERE id='" . $id_evento . "'";
                $listaPratiche = db_fetch_array_assoc(db_query($queryGetPrt))[0]['pratiche_allowed'];


                $arrayPratiche = explode(',', $listaPratiche);

                $stringInsert = "INSERT INTO `pratiche_remidabox`(`id_pratica`, `id_utente`) VALUES ";

                for ($i = 0; $i < count($arrayPratiche); $i++) {
                    $stringInsert .= '(' . $arrayPratiche[$i] . ',' . $id_utente . '),';

                }

                $ris = db_query(trim($stringInsert, ','));
                if ($ris) {
                    echo '1';
                } else {
                    echo '0';
                }
            }
        }
        break;
    case 'carica-statistica-utente':
        {
            $data = date('Y-m-d');
            $contaQuery = $_POST['contaQuery'];
            $contaLotto = $_POST['contaLotto'];
            $response = [];
            $numTotali = 0;
            $numCollector = 0;


            $query = "SELECT * FROM config_statistiche_utente WHERE   visibilita LIKE '" . $_SESSION['user_role'] . "' OR  visibilita like '" . $_SESSION['user_role'] . ",%'  OR visibilita like '%," . $_SESSION['user_role'] . "'  OR visibilita  like '%," . $_SESSION['user_role'] . ",%' ";
            $arrayStat = db_fetch_array(db_query(stripslashes($query), true));


            $numStat = count($arrayStat);
            $stat = $arrayStat[$contaQuery];

            $arrayTitoli = explode(';', $stat['colonne']);

            // echo str_replace('#UTENTELOGGATO',$_SESSION['user_admin_id'],str_replace('&nbsp;',' ',stripslashes($stat['query_lotti'])));

            $arrayLotti = db_fetch_array_assoc(db_query(str_replace('#UTENTELOGGATO', $_SESSION['user_admin_id'], str_replace('&nbsp;', ' ', stripslashes($stat['query_lotti']))), true));
            $numLotti = count($arrayLotti);

            if ($numLotti > 0) {

                $lotto = $arrayLotti[$contaLotto];

                $arrayLottiKeys = array_keys($lotto);

                $keyValore = [];
                $queryTotaliPronta = $stat['query_totali'];
                $queryCollectorPronta = $stat['query_collector'];
                foreach ($arrayLottiKeys as $key) {
                    $queryTotaliPronta = str_replace('#UTENTELOGGATO', $_SESSION['user_admin_id'], str_replace('&nbsp;', ' ', stripslashes(str_replace($key, $lotto[$key], $queryTotaliPronta))));
                    $queryCollectorPronta = str_replace('#UTENTELOGGATO', $_SESSION['user_admin_id'], str_replace('&nbsp;', ' ', stripslashes(str_replace($key, $lotto[$key], $queryCollectorPronta))));
                }

                $risTotali = db_query($queryTotaliPronta, true);
                $risCollector = db_query($queryCollectorPronta, true);

                $numTotali = db_num_rows($risTotali);
                $numCollector = db_num_rows($risCollector);

                $queryTotali = db_fetch_array_assoc($risTotali);
                $queryCollector = db_fetch_array_assoc($risCollector);

                $tcolumnCollector = '<tbody>';
                $theadCollector = "";
                $contaHeadCollector = 0;

                foreach ($queryCollector as $collector) {
                    if ($contaHeadCollector == 0) {
                        $theadCollector = '<thead><tr>';
                        foreach ($collector as $key => $value) {
                            $theadCollector .= '<td>' . $key . '</td>';
                        }
                        $theadCollector .= '</tr></thead>';
                        $contaHeadCollector = 1;
                    }
                    $tcolumnCollector .= '<tr>';
                    foreach ($collector as $value) {
                        $tcolumnCollector .= '<td>' . $value . '</td>';
                    }
                    $tcolumnCollector .= '</tr>';
                }

                $tcolumnCollector .= '</tbody>';


                $tcolumnTot = '<tbody style="max-height:500px; overflow-y: auto;">';

                $contaHeadTot = 0;
                $theadTot = "";

                foreach ($queryTotali as $tot) {
                    if ($contaHeadTot == 0) {
                        $theadTot = '<thead><tr>';
                        foreach ($tot as $key => $value) {
                            $theadTot .= '<td >' . $key . '</td>';
                        }
                        $theadTot .= '</tr></thead>';
                        $contaHeadTot = 1;
                    }
                    $tcolumnTot .= '<tr>';
                    foreach ($tot as $value) {
                        $tcolumnTot .= '<td>' . $value . '</td>';
                    }
                    $tcolumnTot .= '</tr>';
                }

                $tcolumnTot .= '</tbody>';

                $response['numStat'] = $numStat;
                $response['numLotti'] = $numLotti;
                $response['tblCollector'] = $theadCollector . $tcolumnCollector;
                $response['tblTot'] = $theadTot . $tcolumnTot;
                $response['detail_lotto'] = $lotto;
                $response['detail_stat'] = $stat;
                $response['data'] = $data;
                $response['numTot'] = $numTotali;
                $response['numColl'] = $numCollector;
                $response['arrayTitoli'] = $arrayTitoli;
                $response['query1'] = $queryTotaliPronta;
                $response['query2'] = $queryCollectorPronta;
                $response['chiaveValore'] = $keyValore;
            } else {
                $response['lottoVuoto'] = true;
            }
            $json = json_encode($response);
            print_r($json);

            die();

        }
        break;
    case 'richiedi-codice-verifica':
        {
            $response['response'] = inziaVerificaDuepassaggi($_POST['pagina']);

            print_r(json_encode($response));
        }
        break;

    case 'controlla-codice-verifica':
        {
            $retInput = "";
            $response['response'] = convalidaVerificaDuepassaggi($_SESSION['user_admin_id'], $_POST['pagina'], $_POST['codice']);
            if ($reponse['response']) {
                $retInput = "<input type='hidden' name='codice_verifica' id='id_codice_verifica' value='" . sha1($_POST['codice']) . "' />";
            } else {
                $retInput = "";
            }
            $response['ret'] = $retInput;
            print_r(json_encode($response));
        }
        break;
    case 'rilancia-pagopa':
        {
            $queryPDPErrori = "SELECT id_pdp FROM errori_pagopa_pdp WHERE 1";
            $arrayPdp = db_fetch_array_assoc(db_query($queryPDPErrori));
            foreach ($arrayPdp as $pdp) {
                if (ricercaPosizionePerIdentificativo($pdp['id_pdp'])['sucess'] > 0) {
                    $eliminaPDPErrori = "DELETE  FROM errori_pagopa_pdp WHERE id_pdp='" . $pdp['id_pdp'] . "'";
                    db_query($eliminaPDPErrori);
                } else {
                    $result = caricaPosizionePuntuale($pdp['id_pdp']);
                    if ($result !== false) {
                        $eliminaPDPErrori = "DELETE  FROM errori_pagopa_pdp WHERE id_pdp='" . $pdp['id_pdp'] . "'";
                        db_query($eliminaPDPErrori);
                    }
                }
            }
            echo "OK";
        }
        break;
    case 'anteprima-report':
        {

            if (isset($_POST['report_code']) && isset($_POST['id_pratica'])) {
                $params = [];

                $params['id_classe_report'] = $_POST['id_classe_report'];
                $params['id_tipologia_report'] = $_POST['id_tipologia_report'];
                $params['report_code'] = $_POST['report_code'];
                $params['data_stampa'] = $_POST['data_stampa'];
                $params['word'] = $_POST['word'];
                $params['bollettino_451'] = $_POST['bollettino_451'];
                $params['bollettino'] = $_POST['bollettino'];
                $params['pagopa'] = 0;
                $params['ID_PRATICA'] = $_POST['id_pratica'];

                if (isset($_POST['arrayCampiTesto']) && count($_POST['arrayCampiTesto']) > 0) {
                    $arrTest = $_POST['arrayCampiTesto'];
                    $arrVal = $_POST['arrayCampiTestoVal'];
                    for ($i = 0; $i < count($arrTest); $i++) {
                        $nameArr = explode('_', $arrTest[$i]);
                        $name = $nameArr[count($nameArr) - 1];
                        $params[$name] = $arrVal[$i];
                    }
                }

                $query_contratto = "SELECT id_contratto from pratiche WHERE id='" . db_input($params['ID_PRATICA']) . "'";
                $id_contratto = db_fetch_array_assoc(db_query($query_contratto))[0]['id_contratto'];

                $array_prt = [];
                $array_prt[] = $_POST['id_pratica'];
                $params['contratti'][$id_contratto] = $array_prt;

                $return['success'] = false;
                $msg = '';
                $ret = _lancia_report_singolo_pdf_anteprima($params, $msg);
                if (!$ret) {
                    $return['success'] = false;
                    $return['msg'] = $msg;
                } else {
                    $return['success'] = true;
                    $return['msg'] = $ret;
                    $return['html'] = '<iframe style="width: 100%; height: 67vh; margin-top: 10px;" src="data:application/pdf;base64,' . base64_encode(file_get_contents($ret)) . '">';
                    //$return['html'] = '<iframe id="iframe-anteprima" class="is-word" style="display: none;" src="data:application/msword;base64,' . base64_encode(file_get_contents($ret)) . '">';
                }

                print_r(json_encode($return));
            }
        }
        break;
    case 'elimina-campo-testo-report':
        {

            if (isset($_POST['id'])) {

                $return['success'] = false;
                $qryTxtRpt = "DELETE FROM campi_testo_report WHERE id='" . db_input($_POST['id']) . "'";
                if (db_query($qryTxtRpt)) {
                    $return['success'] = true;
                }
                print_r(json_encode($return));
            }
        }
        break;

    case 'modifica-campo-testo-report':
        {

            if (isset($_POST['id']) && isset($_POST['val'])) {

                $return['success'] = false;
                $qryTxtRpt = "UPDATE campi_testo_report SET nome_campo='" . db_input($_POST['val']) . "' WHERE id='" . db_input($_POST['id']) . "'";
                if (db_query($qryTxtRpt)) {
                    $return['success'] = true;
                }
                print_r(json_encode($return));
            }
        }
        break;

    case 'aggiungi-campo-testo-report':
        {

            if (isset($_POST['report']) && isset($_POST['val'])) {

                $return['success'] = false;
                $qryTxtRpt = "INSERT INTO campi_testo_report SET nome_campo='" . db_input($_POST['val']) . "',id_report='" . db_input($_POST['report']) . "'";
                if (db_query($qryTxtRpt)) {
                    $return['success'] = true;
                    $return['id'] = db_last_insert_id();
                }
                print_r(json_encode($return));
            }
        }
        break;
    case 'convalida-pdp':
        {
            if (isset($_POST['arrayPdp']) && count($_POST['arrayPdp']) > 0) {
                $retVal = false;
                $arrayEvPrt = [];
                $arrayPDP = $_POST['arrayPdp'];
                foreach ($arrayPDP as $pdpDaConvalidare) {
                    $queryCercaPdp = "SELECT * FROM pratiche_recuperato WHERE tipo ='PDP' AND (insoluta<>1 OR insoluta is null) AND buon_fine<>1 AND id='" . $pdpDaConvalidare . "'";
                    $risQueryCercaPdp = db_query($queryCercaPdp);
                    //TROVO PDP DI QUELLA DIFFERNZA OF MANDO A BUONFINE QUELLA SE NON TROVO PDP METTO IN RECUPERATO DI TIPO BONIFICO CON LA DIFFERENZA
                    if (db_num_rows($risQueryCercaPdp) > 0) {


                        $pdp_trovata = db_fetch_array($risQueryCercaPdp)[0];

                        $id_pdp = $pdp_trovata['id'];

                        $pratica = db_fetch_array_assoc(db_query("SELECT * FROM pratiche WHERE id='" . $pdp_trovata['id_pratica'] . "'"))[0];


                        $query_id_pagamento = 'SELECT CPP.id as id, CPP.gg_bf,cpp.evento_inc,cpp.tipo_pagamento_pdp_bf FROM  contratto_pagamento_previsto CPP 
                                                            LEFT JOIN tipo_pagamento TP ON CPP.tipo_pagamento=TP.id	
                                                                            WHERE CPP.id = "' . $pdp_trovata['id_tipologia_pagamento'] . '"';


                        $ris_tipo_pagamento = db_fetch_array_assoc(db_query($query_id_pagamento))[0];
                        $id_tipo_pagamento_bf = $ris_tipo_pagamento['tipo_pagamento_pdp_bf'];


                        $queryPag = 'SELECT CPP.id,CPP.evento_inc,CPP.estremi,CPP.gg_bf, TP.tipo FROM contratto_pagamento_previsto  CPP LEFT JOIN  tipo_pagamento TP ON CPP.tipo_pagamento=TP.id	 WHERE CPP.tipo_pagamento = "' . $id_tipo_pagamento_bf . '" AND CPP.id_contratto =  "' . $pratica['id_contratto'] . '"';


                        $risPag = db_query($queryPag);


                        if (db_num_rows($risPag) > 0) {
                            $updatePdpBf = "UPDATE pratiche_recuperato SET buon_fine =1, data_bf_old=data_bf,data_bf=CURDATE(),id_operatore=" . $_SESSION['user_admin_id'] . " WHERE id=" . $id_pdp;
                            db_query($updatePdpBf);


                            $risPagAssociato = db_fetch_array_assoc($risPag)[0];
                            $idPagAssociato = $risPagAssociato['id'];
                            $estremi = $risPagAssociato['estremi'];
                            $eventoIncassoRec = $risPagAssociato['evento_inc'];
                            $ggBuonfine = $risPagAssociato['gg_bf'];

                            $dataBuonFine = $newDate = date('Y-m-d', strtotime($pdp_trovata['data_scadenza'] . " + {$ggBuonfine} days"));

                            $flagBF = 0;
                            if ($dataBuonFine > date('Y-m-d')) {
                                $flagBF = 0;
                            } else {
                                $flagBF = 1;
                            }


                            $risGetNewEsitoPdp = db_query("SELECT esito_buonfine_pdp FROM esiti_recuperato WHERE id='" . db_input($pdp_trovata['esito_recuperato']) . "' ");
                            if (db_num_rows($risGetNewEsitoPdp) > 0) {
                                $newEsitoPdp = db_fetch_array_assoc($risGetNewEsitoPdp)[0]['esito_buonfine_pdp'];
                            } else {
                                $newEsitoPdp = 1;
                            }


                            $query_inserimento_recuperato = 'INSERT INTO pratiche_recuperato
                                             SET data_registrazione = CURDATE(),
                                                 data_emissione = "' . db_input($pdp_trovata['data_emissione']) . '",
                                                 data_scadenza = "' . db_input($pdp_trovata['data_scadenza']) . '",
                                                 data_bf = "' . db_input($dataBuonFine) . '",
                                                 id_pratica = "' . db_input($pratica['id']) . '",
                                                 id_operatore = "' . $_SESSION['user_admin_id'] . '",
                                                 id_operatore_inserimento = "' . $_SESSION['user_admin_id'] . '",
                                                 tipo = "' . $risPagAssociato['tipo'] . '",
                                                 id_tipologia_pagamento = "' . db_input($idPagAssociato) . '",
                                                 estremi = "' . db_input($estremi) . '",
                                                 importo = "' . db_input($pdp_trovata['importo']) . '",
                                                 quota_ca = "' . db_input($pdp_trovata['quota_ca']) . '",
                                                 id_collector = "' . db_input($pdp_trovata['id_collector']) . '",
                                                 id_tutor = "' . db_input($pdp_trovata['id_tutor']) . '",
                                                 id_legale = "' . db_input($pdp_trovata['id_legale']) . '",
                                                 buon_fine = "' . $flagBF . '",
                                                 esito_recuperato = "' . db_input($newEsitoPdp) . '",
                                                 insoluta = "0",
                                                 id_anagrafica_pagante = "' . db_input($pratica['id_debitore']) . '",
                                                 id_pdp_associata = "' . db_input($id_pdp) . '",
                                                 id_piano_rientro = "' . db_input($pdp_trovata['id_piano_rientro']) . '",
                                                 data_competenza_collector = CURDATE()';


                            //die($query_inserimento_recuperato);
                            $ris_inserimento_recuperato = db_query($query_inserimento_recuperato);

                            $id_recuperatoNuovo = db_last_insert_id();


                            $esitoDaImpostarePdp = $newEsitoPdp;

                            if ($esitoDaImpostarePdp > 0) {

                                $risGetNewEventoPdp = db_query("SELECT evento_remida FROM esiti_recuperato WHERE id='" . $esitoDaImpostarePdp . "'");
                                if (db_num_rows($risGetNewEventoPdp) > 0) {
                                    $newEventoPdp = db_fetch_array_assoc($risGetNewEventoPdp)[0];

                                    if (isset($newEventoPdp['evento_remida']) && $newEventoPdp['evento_remida'] > 0) {

                                        if (isset($arrayEvPrt[$pratica['id']][$newEventoPdp['evento_remida']]) && $arrayEvPrt[$pratica['id']][$newEventoPdp['evento_remida']] == 1) {
                                            $arrayEvPrt[$pratica['id']][$newEventoPdp['evento_remida']] = 1;
                                        } else {
                                            esegui_evento_strutturato($pratica['id'], $newEventoPdp['evento_remida']);
                                            $arrayEvPrt[$pratica['id']][$newEventoPdp['evento_remida']] = 1;

                                        }
                                    }
                                }
                            }


                            $queryVerificaCambiale = "SELECT tp.*, cp.descrizione AS are  FROM tipo_pagamento tp 
                                                                        LEFT JOIN  classi_pagamento cp ON   tp.area = cp.codice 
                                                                        LEFT JOIN contratto_pagamento_previsto cpp ON cpp.tipo_pagamento=tp.id
																	   WHERE cpp.id='" . $idPagAssociato . "' AND cp.contatore_cambiali=1 ";


                            if (db_num_rows(db_query($queryVerificaCambiale)) > 0) {
                                $queryidCambialePDP = 'SELECT id_cambiale from pratiche_recuperato WHERE id= "' . db_input($id_pdp) . '"';
                                $risidCambialePDP = db_query($queryidCambialePDP);
                                if (db_num_rows($risidCambialePDP) > 0) {
                                    $idCambiale = db_fetch_array_assoc($risidCambialePDP)[0]['id_cambiale'];
                                    if ($idCambiale > 0) {
                                        db_query("UPDATE  cambiali_recuperato SET id_recuperato='" . $id_recuperatoNuovo . "' WHERE id= '" . db_input($idCambiale) . "'");

                                        db_query("UPDATE  pratiche_recuperato SET id_cambiale='" . $idCambiale . "' WHERE id= '" . db_input($id_recuperatoNuovo) . "'");

                                    } else {
                                        db_query("INSERT IGNORE INTO cambiali_recuperato(id_recuperato) VALUES ('" . $id_recuperatoNuovo . "') ");

                                        $idCambialeNuova = mysql_insert_id();
                                        db_query("UPDATE  pratiche_recuperato SET id_cambiale='" . $idCambialeNuova . "' WHERE id= '" . db_input($id_recuperatoNuovo) . "'");

                                    }

                                } else {
                                    db_query("INSERT IGNORE INTO cambiali_recuperato(id_recuperato) VALUES ('" . $id_recuperatoNuovo . "') ");
                                    $idCambialeNuova = mysql_insert_id();
                                    db_query("UPDATE  pratiche_recuperato SET id_cambiale='" . $idCambialeNuova . "' WHERE id= '" . db_input($id_recuperatoNuovo) . "'");
                                }
                            }


                            $idPdpBf = $id_pdp;
                            $query_aggiornamento_pdp = 'UPDATE pratiche_recuperato set buon_fine = 1, data_bf=CURDATE() WHERE id = "' . db_input($id_pdp) . '"';
                            db_query($query_aggiornamento_pdp);


                            if ($flagBF == 1) {
                                $risGetNewEsito = db_query("SELECT esito_buonfine FROM esiti_recuperato WHERE id='" . db_input($newEsitoPdp) . "' ");
                                if (db_num_rows($risGetNewEsito) > 0) {
                                    $newEsito = db_fetch_array_assoc($risGetNewEsito)[0];

                                    $esitoDaImpostare = $newEsito['esito_buonfine'];

                                    if ($esitoDaImpostare > 0) {
                                        $query_aggiornamento_lingua = '	UPDATE pratiche_recuperato
													SET esito_precedente= esito_recuperato,esito_recuperato = "' . db_input($esitoDaImpostare) . '"
													WHERE id = "' . db_input($id_recuperatoNuovo) . '"';
                                        $ris_aggiornamento_lingua = db_query($query_aggiornamento_lingua) or die(mysql_error());

                                        $risGetNewEvento = db_query("SELECT evento_remida FROM esiti_recuperato WHERE id='" . $esitoDaImpostare . "'");
                                        if (db_num_rows($risGetNewEvento) > 0) {
                                            $newEvento = db_fetch_array_assoc($risGetNewEvento)[0];

                                            if (isset($newEvento['evento_remida']) && $newEvento['evento_remida'] > 0) {

                                                if (isset($arrayEvPrt[$pratica['id']][$newEvento['evento_remida']]) && $arrayEvPrt[$pratica['id']][$newEvento['evento_remida']] == 1) {
                                                    $arrayEvPrt[$pratica['id']][$newEvento['evento_remida']] = 1;
                                                } else {
                                                    esegui_evento_strutturato($pratica['id'], $newEvento['evento_remida']);
                                                    $arrayEvPrt[$pratica['id']][$newEvento['evento_remida']] = 1;

                                                }

                                            }
                                        }
                                    }
                                }
                            }


                            $idPdpBf = $id_pdp;

                            //AGGIORNO I TOTALI SULLA TABELLA PRATICHE DEI NON BUON FINE
                            $query = 'SELECT quota_ca,quota_si,quota_int,quota_affidato_1,quota_affidato_2,quota_affidato_3,quota_ors,quota_oi,quota_surplus
                      FROM pratiche_recuperato
                      WHERE id_pratica = "' . $pratica['id'] . '" AND (insoluta=0 OR insoluta IS NULL) AND ( tipo="DIR" OR tipo="IND" )';
                            $ris_ins = db_query($query);

                            $recuperato_capitale = 0;
                            $recuperato_spese = 0;
                            $recuperato_interessi = 0;
                            $recuperato_affidato_1 = 0;
                            $recuperato_affidato_2 = 0;
                            $recuperato_affidato_3 = 0;
                            $recuperato_oneri_recupero = 0;
                            $recuperato_spese_incasso = 0;
                            $recuperato_surplus = 0;

                            while ($ris = mysql_fetch_array($ris_ins)) {

                                $recuperato_capitale += $ris['quota_ca'];
                                $recuperato_spese += $ris['quota_si'];
                                $recuperato_interessi += $ris['quota_int'];
                                $recuperato_affidato_1 += $ris['quota_affidato_1'];
                                $recuperato_affidato_2 += $ris['quota_affidato_2'];
                                $recuperato_affidato_3 += $ris['quota_affidato_3'];
                                $recuperato_oneri_recupero += $ris['quota_ors'];
                                $recuperato_spese_incasso += $ris['quota_oi'];
                                $recuperato_surplus += $ris['quota_surplus'];

                            }

                            $query_inserimento_insoluto = 'UPDATE pratiche
                                           SET recuperato_capitale = "' . db_input($recuperato_capitale) . '",
                                             recuperato_spese = "' . db_input($recuperato_spese) . '",
                                             recuperato_interessi = "' . db_input($recuperato_interessi) . '",
                                             recuperato_affidato_1 = "' . db_input($recuperato_affidato_1) . '",
                                             recuperato_affidato_2 = "' . db_input($recuperato_affidato_2) . '",
                                             recuperato_affidato_3 = "' . db_input($recuperato_affidato_3) . '",
                                             recuperato_oneri_recupero = "' . db_input($recuperato_oneri_recupero) . '",
                                             recuperato_spese_incasso = "' . db_input($recuperato_spese_incasso) . '",
                                             recuperato_surplus = "' . db_input($recuperato_surplus) . '"
                                             WHERE id = "' . $pratica['id'] . '"';

                            $ris_inserimento_insoluto = db_query($query_inserimento_insoluto);
                            //FINE AGGIORNAMENTO

                            //AGGIORNO I TOTALI SULLA TABELLA PRATICHE BUON FINE
                            $query = 'SELECT quota_ca,quota_si,quota_int,quota_affidato_1,quota_affidato_2,quota_affidato_3,quota_ors,quota_oi,quota_surplus
                      FROM pratiche_recuperato
                      WHERE id_pratica = "' . $pratica['id'] . '" AND (insoluta=0 OR insoluta IS NULL) AND buon_fine=1 AND ( tipo="DIR" OR tipo="IND" )';
                            $ris_ins = db_query($query);

                            $recuperato_capitale_bf = 0;
                            $recuperato_spese_bf = 0;
                            $recuperato_interessi_bf = 0;
                            $recuperato_affidato_1_bf = 0;
                            $recuperato_affidato_2_bf = 0;
                            $recuperato_affidato_3_bf = 0;
                            $recuperato_oneri_recupero_bf = 0;
                            $recuperato_spese_incasso_bf = 0;
                            $recuperato_surplus_bf = 0;

                            while ($ris = mysql_fetch_array($ris_ins)) {

                                $recuperato_capitale_bf += $ris['quota_ca'];
                                $recuperato_spese_bf += $ris['quota_si'];
                                $recuperato_interessi_bf += $ris['quota_int'];
                                $recuperato_affidato_1_bf += $ris['quota_affidato_1'];
                                $recuperato_affidato_2_bf += $ris['quota_affidato_2'];
                                $recuperato_affidato_3_bf += $ris['quota_affidato_3'];
                                $recuperato_oneri_recupero_bf += $ris['quota_ors'];
                                $recuperato_spese_incasso_bf += $ris['quota_oi'];
                                $recuperato_surplus_bf += $ris['quota_surplus'];

                            }

                            $query_inserimento_insoluto = 'UPDATE pratiche
                                           SET recuperato_capitale_bf = "' . db_input($recuperato_capitale_bf) . '",
                                             recuperato_spese_bf = "' . db_input($recuperato_spese_bf) . '",
                                             recuperato_interessi_bf = "' . db_input($recuperato_interessi_bf) . '",
                                             recuperato_affidato_1_bf = "' . db_input($recuperato_affidato_1_bf) . '",
                                             recuperato_affidato_2_bf = "' . db_input($recuperato_affidato_2_bf) . '",
                                             recuperato_affidato_3_bf = "' . db_input($recuperato_affidato_3_bf) . '",
                                             recuperato_oneri_recupero_bf = "' . db_input($recuperato_oneri_recupero_bf) . '",
                                             recuperato_spese_incasso_bf = "' . db_input($recuperato_spese_incasso_bf) . '",
                                             recuperato_surplus_bf = "' . db_input($recuperato_surplus_bf) . '"
                                             WHERE id = "' . $pratica['id'] . '"';

                            $ris_inserimento_insoluto = db_query($query_inserimento_insoluto);

                            # UPGRADE 2016/10/17 - 2.4 AMMINISTRAZIONE – CONTRATTI
                            // SE IL RECUPERATO DELLA PRATICA SODDISFA IL CALCOLO IMPOSTATO IN [CRITERIO DI MOVIMENTAZIONE],
                            // ALLORA NELLA [PRATICA], NELLA SEZIONE [INFORMAZIONI PRATICA], VIENE ATTIVATO IN AUTOMATICO IL CAMPO [MOVIMENTATA] = SI.
                            $isMovimentata = pratiche_updateMovimentazione($pratica['id']);

                            $copertura_titoli_rec = "INSERT INTO `dettaglio_copertura_titoli`(`id_recuperato`, `id_insoluto`, `importo`, `tipo_quota`) SELECT " . $id_recuperatoNuovo . ",id_insoluto,importo,tipo_quota from dettaglio_copertura_titoli where id_recuperato='" . $id_pdp . "'";

                            db_query($copertura_titoli_rec);

                            if ($eventoIncassoRec > 0) {
                                esegui_evento_strutturato($pratica['id'] . ':' . $id_recuperatoNuovo, $eventoIncassoRec);
                            }
                            $retVal = true;

                        } else {
                            $retVal = false;
                        }

                    }
                }

                $res['success'] = $retVal;
                print_r(json_encode($res));
            }
        }
        break;

    case 'cambia-nome-affido-legale':
        {

            if (isset($_POST['id_aff']) && isset($_POST['val'])) {

                $return['success'] = false;
                $qryTxtRpt = "update affidamenti_legale  SET nome='" . db_input($_POST['val']) . "' WHERE id='" . db_input($_POST['id_aff']) . "'";
                if (db_query($qryTxtRpt)) {
                    $return['success'] = true;
                }
                print_r(json_encode($return));
            }
        }
        break;
    case 'cambia-nome-affido-tutor':
        {

            if (isset($_POST['id_aff']) && isset($_POST['val'])) {

                $return['success'] = false;
                $qryTxtRpt = "update affidamenti_tutor  SET nome='" . db_input($_POST['val']) . "' WHERE id='" . db_input($_POST['id_aff']) . "'";
                if (db_query($qryTxtRpt)) {
                    $return['success'] = true;
                }
                print_r(json_encode($return));
            }
        }
        break;

    case 'cambia-nome-affido':
        {


            if (isset($_POST['id_aff']) && isset($_POST['val'])) {

                $return['success'] = false;
                $qryTxtRpt = "update affidamenti  SET nome='" . db_input($_POST['val']) . "' WHERE id='" . db_input($_POST['id_aff']) . "'";
                if (db_query($qryTxtRpt)) {
                    $return['success'] = true;
                }
                print_r(json_encode($return));
            }
        }
        break;
    case 'cambia-nome-cluster':
        {


            if (isset($_POST['id_aff']) && isset($_POST['val'])) {

                $return['success'] = false;
                $qryTxtRpt = "update affidamenti_cluster  SET descrizione='" . db_input($_POST['val']) . "' WHERE id='" . db_input($_POST['id_aff']) . "'";
                if (db_query($qryTxtRpt)) {
                    $return['success'] = true;
                }
                print_r(json_encode($return));
            }
        }
        break;

    case 'ricerca-cluster-affidamento':
        {
            $cards = array();

            $query1 = 'SELECT id, descrizione
					FROM affidamenti_cluster
					WHERE descrizione LIKE "%' . db_input($_POST['query']) . '%"';
            //die($query);
            $ris = db_query($query1);
            while ($row = mysql_fetch_array($ris)) {
                $cards[] = array(
                    'id' => $row['id'],
                    'text' => $row['descrizione']
                );
            }

            print_r(json_encode($cards));
        }
        break;

    case 'salva-smtp-profilo':
        {
            if (isset($_POST['table']) && $_POST['table'] != "") {

                $queryUpdate = " UPDATE " . db_input($_POST['table']) . " SET mail_smtp='" . db_input($_POST['mail_smtp']) . "', mittente_mail='" . db_input($_POST['mittente_mail']) . "',mail_password='" . db_input($_POST['mail_password']) . "',mail_username='" . db_input($_POST['mail_username']) . "' WHERE id_utente='" . db_input($_SESSION['user_admin_id']) . "'";
                db_query($queryUpdate);

            }

        }
        break;

    case 'salva-data-blocco':
        {

            if (isset($_POST['data_incassi']) && $_POST['data_incassi'] != "") {

                $arrayDataBlocco = explode('-', $_POST['data_incassi']);

                $queryUpdate = " UPDATE impostazioni_base SET blocca_incassi='" . db_input($arrayDataBlocco[2] . '-' . $arrayDataBlocco[1] . '-' . $arrayDataBlocco[0]) . "' WHERE id='1'";
                db_query($queryUpdate);

            } else {

                $queryUpdate = " UPDATE impostazioni_base SET blocca_incassi=null WHERE id='1'";
                db_query($queryUpdate);
            }

        }
        break;

    case 'verifica-codice-elemento':
        {
            $tabella = cleanInput($_POST['table']);
            $codice = cleanInput($_POST['codice']);

            $query = "SELECT * 
                        FROM " . db_input($tabella) . " 
                        WHERE codice = '" . db_input($codice) . "'";
            $ris_query = db_query($query);

            $response = array(
                'error' => true,
                'found' => 0
            );

            if (db_num_rows($ris_query) > 0) {
                $response['error'] = false;
                $response['found'] = db_num_rows($ris_query);
            }

            print_r(json_encode($response));
        }
        break;

    case 'valida-allegati-esa':
        {
            if (isset($_POST['id']) && isset($_POST['type'])) {

                if ($_POST['type'] == "PDR") {
                    db_query("UPDATE piani_di_rientro_esattore SET allegati_verificati=1 WHERE id='" . db_input($_POST['id']) . "' ");
                } else {

                    db_query("UPDATE pratiche_recuperato_esattore SET allegati_verificati=1 WHERE id='" . db_input($_POST['id']) . "' ");
                }
                $response['error'] = false;
                print_r(json_encode($response));
            }
        }
        break;
    case 'ko-allegati-esa':
        {
            if (isset($_POST['id']) && isset($_POST['type'])) {

                if ($_POST['type'] == "PDR") {
                    db_query("UPDATE piani_di_rientro_esattore SET allegati_verificati=0 WHERE id='" . db_input($_POST['id']) . "' ");
                } else {

                    db_query("UPDATE pratiche_recuperato_esattore SET allegati_verificati=0 WHERE id='" . db_input($_POST['id']) . "' ");
                }
                $response['error'] = false;
                print_r(json_encode($response));
            }
        }
        break;
    case 'filtra-pdr-massivi':
        {

            $queryGetPDR = "SELECT PDR.id FROM piani_di_rientro PDR LEFT JOIN pratiche P ON P.id=PDR.id_pratica WHERE 1=1 ";
            if (isset($_POST['id_select_collector']) && $_POST['id_select_collector'] != "") {
                $queryGetPDR .= " AND PDR.id_utente_collector= '" . db_input($_POST['id_select_collector']) . "'";
            }
            if (isset($_POST['id_select_pagante']) && $_POST['id_select_pagante'] != "") {
                $queryGetPDR .= " AND PDR.id_utente_debitore= '" . db_input($_POST['id_select_pagante']) . "'";
            }
            if (isset($_POST['id_select_mandante']) && $_POST['id_select_mandante'] != "") {
                $queryGetPDR .= " AND P.id_mandante= '" . db_input($_POST['id_select_mandante']) . "'";
            }
            if (isset($_POST['id_select_debitore']) && $_POST['id_select_debitore'] != "") {
                $queryGetPDR .= " AND P.id_debitore= '" . db_input($_POST['id_select_debitore']) . "'";
            }
            if (isset($_POST['esitoRecuperato']) && $_POST['esitoRecuperato'] != "" && $_POST['esitoRecuperato'] > 0) {
                $queryGetPDR .= " AND PDR.stato= '" . db_input($_POST['esitoRecuperato']) . "'";
            }
            if (isset($_POST['a_data_emissione']) && $_POST['a_data_emissione'] != "") {
                $queryGetPDR .= " AND PDR.data_emissione <= '" . db_input($_POST['a_data_emissione']) . "'";
            }
            if (isset($_POST['da_data_emissione']) && $_POST['da_data_emissione'] != "") {
                $queryGetPDR .= " AND PDR.data_emissione >= '" . db_input($_POST['da_data_emissione']) . "'";
            }
            if (isset($_POST['a_id_pratica']) && $_POST['a_id_pratica'] != "") {
                $queryGetPDR .= " AND PDR.id_pratica <= '" . db_input($_POST['a_id_pratica']) . "'";
            }
            if (isset($_POST['da_id_pratica']) && $_POST['da_id_pratica'] != "") {
                $queryGetPDR .= " AND PDR.id_pratica >= '" . db_input($_POST['da_id_pratica']) . "'";
            }
            if (isset($_POST['id_select_tutor']) && $_POST['id_select_tutor'] != "") {
                $queryGetPDR .= " AND P.id_tutor >= '" . db_input($_POST['id_select_tutor']) . "'";
            }
            if (isset($_POST['id_select_legale']) && $_POST['id_select_legale'] != "") {
                $queryGetPDR .= " AND P.id_legale >= '" . db_input($_POST['id_select_legale']) . "'";
            }
            $idsPDR = db_fetch_array_assoc(db_query($queryGetPDR));
            $ret = "";
            foreach ($idsPDR as $id) {
                $ret .= $id['id'] . ',';
            }

            $response['data'] = trim($ret, ',');
            $response['error'] = false;
            print_r(json_encode($response));
        }
        break;
    case 'esecuzione-eventi-pdr':
        {

            if (isset($_POST['eventi']) && isset($_POST['pdr'])) {
                $arrayEventi = explode(',', $_POST['eventi']);
                $arrayPDR = explode(',', $_POST['pdr']);
                for ($i = 0; $i < count($arrayEventi); $i++) {
                    $id_pratica = db_fetch_array_assoc(db_query("SELECT id_pratica FROM piani_di_rientro WHERE id='" . db_input($arrayPDR[$i]) . "'"))[0]['id_pratica'];
                    if ($arrayEventi[$i] > 0) {
                        esegui_evento_strutturato($id_pratica, $arrayEventi[$i], 0, null, $arrayPDR[$i]);
                    }
                }
                $ret['error'] = false;
            } else {
                $ret['error'] = true;
            }

            print_r(json_encode($ret));
        }
        break;
    case 'salva-debitore-da-pratica':
        {

            if (isset($_POST['id_pratica'])) {
                //$nome=$_POST['nome'];
                $cognome = $_POST['cognome'];
                $id_pratica = $_POST['id_pratica'];

                $risDtlPrt = db_fetch_array_assoc_single(db_query("SELECT id_mandante,id_debitore FROM pratiche WHERe id='" . db_input($id_pratica) . "'"));

                $id_mandante = $risDtlPrt['id_mandante'];
                $id_debitore = $risDtlPrt['id_debitore'];

                //$updateDeb="UPDATE utente SET cognome='".db_input(trim($cognome))."',nome='".db_input(trim($nome))."' WHERE id_utente='".db_input($id_debitore)."'";
                $updateACM = "UPDATE anagrafica_collegati_mandante SET ragione_sociale_collegato='" . db_input(trim($cognome)) . "' WHERE id_mandante='" . $id_mandante . "' AND id_collegato_pratica='" . $id_debitore . "'";
                //db_query($updateDeb);
                db_query($updateACM);
                $ret['error'] = false;
            } else {
                $ret['error'] = true;
            }

            print_r(json_encode($ret));
        }
        break;
    case 'salva-debitore-da-pratica-pratica':
        {

            if (isset($_POST['id_pratica'])) {
                //$nome=$_POST['nome'];
                $cognome = $_POST['cognome'];
                $id_pratica = $_POST['id_pratica'];

                $risDtlPrt = db_fetch_array_assoc_single(db_query("SELECT id_mandante,id_debitore FROM pratiche WHERe id='" . db_input($id_pratica) . "'"));

                $id_mandante = $risDtlPrt['id_mandante'];
                $id_debitore = $risDtlPrt['id_debitore'];

                //$updateDeb="UPDATE utente SET cognome='".db_input(trim($cognome))."',nome='".db_input(trim($nome))."' WHERE id_utente='".db_input($id_debitore)."'";
                $updateACM = "UPDATE anagrafica_collegati_mandante SET ragione_sociale_collegato='" . db_input(trim($cognome)) . "' WHERE id_mandante='" . $id_mandante . "' AND id_collegato_pratica='" . $id_debitore . "' AND id_pratica='" . db_input($id_pratica) . "'";
                //db_query($updateDeb);
                db_query($updateACM);
                $ret['error'] = false;
            } else {
                $ret['error'] = true;
            }

            print_r(json_encode($ret));
        }
        break;

    case 'anteprima-sms':
        {
            if (isset($_POST['destinatario']) && isset($_POST['id_pratica'])) {
                $params = [];

                $params['destinatario'] = decode($_POST['destinatario'], $_POST['id_pratica']);
                $params['body'] = decode($_POST['body'], $_POST['id_pratica']);
                $params['ID_PRATICA'] = $_POST['id_pratica'];

                $return['success'] = false;
                $msg = '';
                $ret = _invia_sms_anteprima($params, $msg);
                if (!$ret) {
                    $return['success'] = false;
                    $return['msg'] = $msg;
                } else {
                    $return['success'] = true;
                    $return['msg'] = $ret;
                }

                print_r(json_encode($return));
            }
        }
        break;
    case 'anteprima-mail':
        {
            if (isset($_POST['body']) && isset($_POST['id_pratica'])) {
                $params = [];
                $params['mittente'] = decode($_POST['mittente'], $_POST['id_pratica']);
                $params['mittente_visibile'] = decode($_POST['mittente_visibile'], $_POST['id_pratica']);
                $params['cc'] = decode($_POST['cc'], $_POST['id_pratica']);
                $params['destinatario'] = decode($_POST['destinatario'], $_POST['id_pratica']);
                $params['ccn'] = decode($_POST['ccn'], $_POST['id_pratica']);
                $params['oggetto'] = decode($_POST['oggetto'], $_POST['id_pratica']);
                $params['body'] = decode($_POST['body'], $_POST['id_pratica']);
                $params['id_tracker'] = $_POST['id_tracker'];
                $params['quick_file_type'] = $_POST['quick_file_type'];
                $params['quick_file_data'] = $_POST['quick_file_data'];
                $params['report_code'] = $_POST['report_code'];
                $params['handle'] = $_POST['handle'];
                $params['input_file'] = $_POST['input_file'];
                $params['quick'] = $_POST['quick'];
                $params['handlex'] = $_POST['handlex'];
                $params['allegato_report'] = $_POST['allegato_report'];
                $params['allegato_statico'] = $_POST['allegato_statico'];
                $params['ID_PRATICA'] = $_POST['id_pratica'];

                $return['success'] = false;
                $msg = '';
                $ret = _invia_mail_anteprima($params, $msg);
                if (!$ret) {
                    $return['success'] = false;
                    $return['msg'] = $msg;
                } else {
                    $return['success'] = true;
                    $return['msg'] = $ret;
                }

                print_r(json_encode($return));
            }
        }
        break;
    case 'anteprima-mail-sms':
        {
            if (isset($_POST['body']) && isset($_POST['id_pratica'])) {

                foreach ($translations as $key => $value) {        // TRADUZIONE DEI VALORI DEI CAMPI IN FUZIONE DELLA PRATICA
                    if (strpos($key, 'quick_')) continue;

                    $valore_campo_temp = $value;
                    if (trim($value) != "") {
                        if (strpos(decode($value, $pratica . $id2[$pratica]), 'encoded') === false) {
                            $valore_campo_temp = decode($value, $pratica . $id2[$pratica]);
                        }
                    }

                    $translations[$key] = $valore_campo_temp;    // GESTIONE -1 NON NECESSARIA PER EVENTI NORMALI
                }
                $params = [];
                $params['mittente'] = decode($_POST['mittente'], $_POST['id_pratica']);
                $params['mittente_visibile'] = decode($_POST['mittente_visibile'], $_POST['id_pratica']);
                $params['cc'] = decode($_POST['cc'], $_POST['id_pratica']);
                $params['destinatario'] = decode($_POST['destinatario'], $_POST['id_pratica']);
                $params['ccn'] = decode($_POST['ccn'], $_POST['id_pratica']);
                $params['oggetto'] = decode($_POST['oggetto'], $_POST['id_pratica']);
                $params['body'] = decode($_POST['body'], $_POST['id_pratica']);
                $params['ID_PRATICA'] = $_POST['id_pratica'];

                $return['success'] = false;
                $msg = '';
                $ret = _invia_mail_sms_anteprima($params, $msg);
                if (!$ret) {
                    $return['success'] = false;
                    $return['msg'] = $msg;
                } else {
                    $return['success'] = true;
                    $return['msg'] = $ret;
                }

                print_r(json_encode($return));
            }
        }
        break;

    case 'anteprima-report-postel':
        {

            if (isset($_POST['report_code']) && isset($_POST['id_pratica'])) {
                $params = [];

                $params['id_classe_report'] = $_POST['id_classe_report'];
                $params['id_tipologia_report'] = $_POST['id_tipologia_report'];
                $params['report_code'] = $_POST['report_code'];
                $params['bollettino'] = $_POST['bollettino'];
                $params['ID_PRATICA'] = $_POST['id_pratica'];

                if (isset($_POST['arrayCampiTesto']) && count($_POST['arrayCampiTesto']) > 0) {
                    $arrTest = $_POST['arrayCampiTesto'];
                    $arrVal = $_POST['arrayCampiTestoVal'];
                    for ($i = 0; $i < count($arrTest); $i++) {
                        $nameArr = explode('_', $arrTest[$i]);
                        $name = $nameArr[count($nameArr) - 1];
                        $params[$name] = $arrVal[$i];
                    }
                }

                $query_contratto = "SELECT id_contratto from pratiche WHERE id='" . db_input($params['ID_PRATICA']) . "'";
                $id_contratto = db_fetch_array_assoc(db_query($query_contratto))[0]['id_contratto'];

                $array_prt = [];
                $array_prt[] = $_POST['id_pratica'];
                $params['contratti'][$id_contratto] = $array_prt;

                $return['success'] = false;
                $msg = '';
                $ret = _lancia_report_singolo_pdf_anteprima($params, $msg);
                if (!$ret) {
                    $return['success'] = false;
                    $return['msg'] = $msg;
                } else {
                    $return['success'] = true;
                    $return['msg'] = $ret;
                    $return['html'] = '<iframe style="width: 100%; height: 67vh; margin-top: 10px;" src="data:application/pdf;base64,' . base64_encode(file_get_contents($ret)) . '">';
                }

                print_r(json_encode($return));
            }
        }
        break;

    case 'modifica-nota-postalizzazione':
        {
            $return = [];

            $query = "UPDATE note_postel SET descrizione = '" . db_input($_POST['descrizione']) . "' WHERE id = '" . db_input($_POST['id']) . "'";
            db_query($query);

            $return['error'] = false;

            print_r(json_encode($return));
        }
        break;

    case 'modifica-costi-postalizzazione':
        {
            $return = [];

            $query = "UPDATE costi_postalizzazione 
                        SET costo_1 = '" . db_input($_POST['costo_1']) . "',
                            costo_2 = '" . db_input($_POST['costo_2']) . "',
                            costo_3 = '" . db_input($_POST['costo_3']) . "',
                            costo_4 = '" . db_input($_POST['costo_4']) . "',
                            costo_5 = '" . db_input($_POST['costo_5']) . "',
                            costo_6 = '" . db_input($_POST['costo_6']) . "' ,
                            id_tipo_spesa_1 = '" . db_input($_POST['id_tipo_spesa_1']) . "',
                            id_tipo_spesa_2 = '" . db_input($_POST['id_tipo_spesa_2']) . "',
                            id_tipo_spesa_3 = '" . db_input($_POST['id_tipo_spesa_3']) . "',
                            id_tipo_spesa_4 = '" . db_input($_POST['id_tipo_spesa_4']) . "',
                            id_tipo_spesa_5 = '" . db_input($_POST['id_tipo_spesa_5']) . "',
                            id_tipo_spesa_6 = '" . db_input($_POST['id_tipo_spesa_6']) . "',
                            id_nota_abituale = '" . db_input($_POST['id_nota_abituale']) . "' 
                        WHERE id = '1'";
            db_query($query);

            header('location: pagina_gestione_multidialogo.php');
        }
        break;


    case 'aggiungi-log-anteprima':
        {
            if (isset($_POST['evento']) && isset($_POST['pratiche'])) {
                $query = "INSERT INTO log_anteprime_utente(id_utente,anteprime_mancanti,evento_strutturato,pratiche) 
                                                        VALUES ('" . $_SESSION['user_id'] . "','" . $_POST['anteprime'] . "','" . $_POST['evento'] . "','" . implode(',', $_POST['pratiche']) . "')";
                db_query($query);

                $return['success'] = true;
                print_r(json_encode($return));
            }
        }
        break;

    case 'verifica-query':
        global $connection;

        $response = array(
            'error' => true,
            'msg' => '',
        );

        $queryReport = "EXPLAIN " . $_POST['query'];
        $risReportDashboard = db_query(str_replace('[**UTENTIESCLUSI**]', '1,2', $queryReport));

        $response['error'] = false;
        $response['msg'] = 'Query corretta';

        print_r(json_encode($response));
        die();

        break;

    case 'inserisci-previsione':
        //die('QUI');

        $verAnno = "SELECT id FROM previsione WHERE anno ='" . db_input($_POST['anno']) . "'";
        if (db_num_rows(db_query($verAnno)) > 0) {
            $response['error'] = true;
            $response['msg'] = 'Anno Presente';
            print_r(json_encode($response));
        } else {

            for ($i = 1; $i < 13; $i++) {
                $query_inserimento_lingua = 'INSERT INTO previsione
											SET anno = "' . db_input(db_input($_POST['anno'])) . '", mese="' . $i . '", importo="' . (db_input(str_replace(',', '.', $_POST['importo'])) / 12) . '"';
                $ris_inserimento_lingua = db_query($query_inserimento_lingua);

            }

            $response['error'] = false;
            $response['msg'] = 'Inserimento Effettualo';
            print_r(json_encode($response));
        }

        break;

    case 'elimina-previsione':
        //AGGIORNO LA TABELLA TIPO FONTE
        $query_eliminazione_lingua = 'DELETE FROM previsione
											WHERE anno = "' . db_input($_POST['id']) . '"';
        $ris_eliminazione_lignua = db_query($query_eliminazione_lingua);
        break;

    case 'modifica-importo-previsione':
        //AGGIORNO LA TABELLA TIPO FONTE
        $query_eliminazione_lingua = 'UPDATE  previsione SET importo="' . db_input(str_replace(',', '.', $_POST['importo'])) . '"
											WHERE id = "' . db_input($_POST['id']) . '"';
        $ris_eliminazione_lignua = db_query($query_eliminazione_lingua);

        $result['error'] = false;
        print_r(json_encode($result));
        break;

    /*
     * =======================================
     *               RAPPORTI
     * =======================================
    */

    case 'inserisci-rapporto':
        $result['error'] = false;
        $result['message'] = 'Rapporto inserito correttamente';

        $query = 'INSERT INTO pratiche_rapporti
                    SET id_pratica          = "'.db_input($_POST['id_pratica']).'",
                        importo             = "'.db_input(str_replace(',','.',$_POST['importo'])).'",
                        descrizione         = "'.db_input($_POST['descrizione']).'",
                        data_rapporto       = "'.db_input(date('Y-m-d',strtotime($_POST['data_rapporto']))).'",
                        data_inserimento    = "'.date('Y-m-d H:i:s').'",
                        id_utente           = "'.$_SESSION['user_admin_id'].'"';
        db_query($query);

        print_r(json_encode($result));
        break;

    case 'modifica-rapporto':
        $result['error'] = false;
        $result['message'] = 'Rapporto modificato correttamente';

        $query = 'UPDATE pratiche_rapporti
                    SET importo             = "'.db_input(str_replace(',','.',$_POST['importo'])).'",
                        descrizione         = "'.db_input($_POST['descrizione']).'",
                        data_rapporto       = "'.db_input(date('Y-m-d',strtotime($_POST['data_rapporto']))).'",
                        data_modifica    = "'.date('Y-m-d H:i:s').'",
                        id_utente           = "'.$_SESSION['user_admin_id'].'"
                        WHERE id = "'.db_input($_POST['id']).'"';
        db_query($query);

        print_r(json_encode($result));
        break;

    case 'elimina-rapporto':
        $result['error'] = false;
        $result['message'] = 'Rapporto eliminato correttamente';

        $query = 'DELETE FROM pratiche_rapporti
                    WHERE id = "'.db_input($_POST['id']).'"';
        db_query($query);

        print_r(json_encode($result));
        break;

    /*
     * =======================================
     *          AUTORITA' GIUDIZIARIA
     * =======================================
    */

    case 'inserisci-autorita-giudiziaria':
        $result['error'] = false;
        $result['message'] = 'Autorità giudiziaria inserita correttamente';

        $query = 'INSERT INTO autorita_giudiziarie
                    SET descrizione             = "'.db_input($_POST['descrizione']).'",
                        pec         = "'.db_input($_POST['pec']).'"';
        db_query($query);

        //print_r(json_encode($result));
        break;

    case 'modifica-autorita-giudiziaria':
        $result['error'] = false;
        $result['message'] = 'Autorità giudiziaria modificata correttamente';

        $query = 'UPDATE autorita_giudiziarie
                    SET descrizione             = "'.db_input($_POST['descrizione']).'",
                        pec         = "'.db_input($_POST['pec']).'"
                    WHERE id = "'.db_input($_POST['id']).'"';
        db_query($query);

        //print_r(json_encode($result));
        break;

    case 'elimina-autorita-giudiziaria':
        $result['error'] = false;
        $result['message'] = 'Autorità giudiziaria eliminata correttamente';

        $query = 'DELETE FROM autorita_giudiziarie
                    WHERE id = "'.db_input($_POST['id']).'"';
        db_query($query);

        //print_r(json_encode($result));
        break;

    /*
     * =======================================
     *          REGISTRO LEGAL
     * =======================================
    */

    case 'inserisci-registro-legal':
        $result['error'] = false;
        $result['message'] = 'Registro inserito correttamente';

        $query = 'INSERT INTO registri_legal
                    SET descrizione             = "'.db_input($_POST['descrizione']).'"';
        db_query($query);

        //print_r(json_encode($result));
        break;

    case 'modifica-registro-legal':
        $result['error'] = false;
        $result['message'] = 'Registro modificato correttamente';

        $query = 'UPDATE registri_legal
                    SET descrizione             = "'.db_input($_POST['descrizione']).'"
                    WHERE id = "'.db_input($_POST['id']).'"';
        db_query($query);

        //print_r(json_encode($result));
        break;

    case 'elimina-registro-legal':
        $result['error'] = false;
        $result['message'] = 'Registro eliminato correttamente';

        $query = 'DELETE FROM registri_legal
                    WHERE id = "'.db_input($_POST['id']).'"';
        db_query($query);

        //print_r(json_encode($result));
        break;

    /* UTILS */

    case 'verifica-scadenza-con-iter':
        $result = array(
            'error' => false,
            'msg' => ''
        );

        $idPratica = $_POST['pratica'];
        $iter = pratica_getIterDetails($idPratica);

        $dataImpostata = date('Y-m-d', strtotime($_POST['dateset']));

        $dataMaxIter = date('Y-m-d', strtotime('+' . $iter['dd_scad_prima_pdp'] . ' days'));

        if ($dataImpostata > $dataMaxIter) {
            $result['error'] = true;
            $result['date'] = date('d-m-Y', strtotime($dataMaxIter));
            $result['msg'] = "La data inserita supera il limite imposto dall'iter della pratica.<br>La data massima impostabile è il " . date('d-m-Y', strtotime($dataMaxIter));
        }

        print_r(json_encode($result));
        break;

    case 'e_acquisisci-dati-file':
        { 

            $id_acquisizione = $_POST['id_acquisizione'];    
            $id_utente = $_POST['id_utente'];
            

            $ch = curl_init();
            $url = "https://demo.remidahps.it/remida/test_pagina_elenco_acquisizioni_emanuele_curl.php";
            $params = array(
                'user' => $id_utente,
                'id' => $id_acquisizione,
            );

            curl_setopt($ch, CURLOPT_TIMEOUT, 1); // per debug ? 0 : 1
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
            $curlResponse = curl_exec($ch);

            if ($curlResponse === false) {
                echo 'Errore cURL: ' . curl_error($ch);
            }            
            curl_close($ch);
        }
        break;

    // case 'rows_update':
    //     {    
    //         global $msqli;

    //         $data = array();

    //         $selectAcquisizione = $msqli->prepare("SELECT *
    //                                                 FROM ed_acquisizioni_file
    //                                                 WHERE id_utente = 623561
    //                                                 ORDER BY id desc
    //                                                 ");
    //         $selectAcquisizione->execute();

    //         $risQueryAcquisizioni = $selectAcquisizione->get_result();
    //         $data = $risQueryAcquisizioni->fetch_all(MYSQLI_ASSOC);
            
    //         echo json_encode($data);            

    //     }
    //     break;

    case 'righe_in_coda':
        {    
            global $msqli;

            $id_acquisizione = $_POST['id'];
            $stato = $_POST['stato'];
            $id_utente = $_POST['user'];
            $valore_priorita = $_POST['priorita'];

            if($valore_priorita == 'Alta') {$valore_priorita = 1;}
            if($valore_priorita == 'Normale') {$valore_priorita = 2;}          
            if($valore_priorita == 'Bassa') {$valore_priorita = 3;}

            try {
                $selectAcquisizione = $msqli->prepare("UPDATE ed_acquisizioni_file SET stato = ?, priorita = ? WHERE id = ?");
                $selectAcquisizione->bind_param('sii', $stato, $valore_priorita, $id_acquisizione);
                $selectAcquisizione->execute();

                $insert_log_stati_in_coda = $msqli->prepare("INSERT INTO e_stati_acquisizioni (id_acquisizione, id_utente, data_cambio_stato, stato) VALUES (?, ?, CURRENT_TIMESTAMP, ?)");
                $insert_log_stati_in_coda->bind_param('iis', $id_acquisizione, $id_utente, $stato);
                $insert_log_stati_in_coda->execute();

            } catch (mysqli_sql_exception $e) {
                echo 'ERRORE: ' . $e->getMessage();
            }
            $selectAcquisizione->close();
            $insert_log_stati_in_coda->close();
        }
        break;

    case 'rimuovi_acquisizione':
        {    
            global $msqli;

            $id_acquisizione = $_POST['id'];
            $id_utente = $_POST['user'];

            try {
                $selectAcquisizione = $msqli->prepare("DELETE FROM ed_acquisizioni_file WHERE id = $id_acquisizione");
                $selectAcquisizione->execute();

                $insert_log_stati_deleted = $msqli->prepare("INSERT INTO e_stati_acquisizioni (id_acquisizione, id_utente, data_cambio_stato, stato) VALUES (?, ?, CURRENT_TIMESTAMP,'Deleted')");
                $insert_log_stati_deleted->bind_param('ii', $id_acquisizione, $id_utente);
                $insert_log_stati_deleted->execute();

            } catch (mysqli_sql_exception $e) {
                echo 'ERRORE: ' . $e->getMessage();
            }         
            $selectAcquisizione->close();
            $insert_log_stati_deleted->close();
        }
        break;

    case 'annulla_righe_in_coda':
        {    
            global $msqli;

            $id_acquisizione = $_POST['id'];
            $id_utente = $_POST['user'];
            $stato = $_POST['stato'];

            try {
                $selectAcquisizione = $msqli->prepare("UPDATE ed_acquisizioni_file SET stato = '$stato', priorita = DEFAULT WHERE id = $id_acquisizione");
                $selectAcquisizione->execute();

                $insert_log_stati_ripristina_pending = $msqli->prepare("INSERT INTO e_stati_acquisizioni (id_acquisizione, id_utente, data_cambio_stato, stato) VALUES (?, ?, CURRENT_TIMESTAMP, ?)");
                $insert_log_stati_ripristina_pending->bind_param('iis', $id_acquisizione, $id_utente, $stato);
                $insert_log_stati_ripristina_pending->execute();

            } catch (mysqli_sql_exception $e) {
                echo 'ERRORE: ' . $e->getMessage();
            }
            $selectAcquisizione->close();
            $insert_log_stati_ripristina_pending->close();
        }
        break;
}