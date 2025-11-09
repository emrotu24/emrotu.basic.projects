<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Abilito errori mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   

// --------------------- CREARE CONNESSIONE MYSQLI ---------------------------
$servername ='sql111.infinityfree.com';
$username = 'if0_40236975';
$password ='lfBW09DmpT81r';
$dbname = 'if0_40236975_emrotu_db';

try {
    // Connessione MySQLi con gestione eccezioni
    $msqli = new mysqli($servername, $username, $password, $dbname);
    // Imposta charset (opzionale ma consigliato)
    $msqli->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    // Mostra errore dettagliato
    die("Errore di connessione: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="it" class="no-js">
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title>My php jobs</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <meta name="MobileOptimized" content="320">


</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="<?php echo $header_style . ' ' . $sidebar_style; ?>">
<!-- BEGIN HEADER -->
<!-- END HEADER -->

<!-- BEGIN CONTAINER -->
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <?php
    // if ($sidebar_text == 1)
    //     require_once('elements/sidebar_new.php');
    // else
    //     require_once('elements/sidebar.php');
    // ?>
    <!-- END SIDEBAR -->

    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <div class="page-content">

            <!-- BEGIN PAGE HEADER-->
            <h3 class="page-title">
                Gestione Elenco Acquisizioni
            </h3>
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <i class="fa fa-home"></i>
                        <a href="index.php">Home</a>
                        <i class="fa fa-angle-right"></i>
                    </li>
                    <li>
                        <a href="#">Import/Export</a>
                        <i class="fa fa-angle-right"></i>
                    </li>
                    <li>
                        <a href="#">Elenco Acquisizioni</a>
                    </li>
                </ul>
            </div>
            <!-- END PAGE HEADER-->
            <div class="page-toolbar">
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_0">
                            <div class="row portlet">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-edit"></i>Elenco Acquisizioni
                                    </div>
                                    <div class="tools">
                                        <a href="javascript:;" class="collapse">
                                        </a>
                                        <a href="#portlet-config" data-toggle="modal"
                                            class="config info"></a>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="row_checkboxes text-center">
                                        <label for="checkbox">Mostra Terminate</label>
                                        <input id="checkbox_terminate" type="checkbox">
                                        <label for="checkbox">Mostra In coda</label>
                                        <input id="checkbox_in_coda" type="checkbox">
                                        <label for="checkbox">Mostra In lavorazione</label>
                                        <input id="checkbox_in_lavorazione" type="checkbox">
                                    </div>
                                    <div class="row_tipo text-right">
                                        <label for="search">Filtra per tipo:</label>
                                        <input id="seleziona_tipologia" class="input-inline form-control" type="search" placeholder="Tipo acquisizione">
                                    </div>
                                    <table id="sample_editable_1" class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="col-md-1 text-center">
                                                    ID ACQUISIZIONE
                                                </th>
                                                <th class="col-md-1 text-center">
                                                    DATA ACQUISIZIONE
                                                </th>
                                                <th class="col-md-1 text-center">
                                                    DATA IMPORTAZIONE
                                                </th>
                                                <th class="col-md-1 text-center">
                                                    ID OPERATORE
                                                </th>
                                                <th class="col-md-1 text-center">
                                                    TIPO
                                                </th>
                                                <th class="col-md-1 text-center">
                                                    STATO
                                                </th>
                                                <th class="col-md-1 text-center">
                                                    URGENZA
                                                </th>
                                                <th class="col-md-2 text-center">
                                                    AZIONI
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php

                                        $selectAcquisizione = $msqli->prepare("SELECT *
                                                                                FROM ed_acquisizioni_file
                                                                                WHERE id_utente = 623561
                                                                                AND (stato != '' AND stato IS NOT NULL)
                                                                                ORDER BY id desc
                                                                                ");
                                        $selectAcquisizione->execute();
                                        $risQueryAcquisizioni = $selectAcquisizione->get_result();
                                        
                                        while ($row = $risQueryAcquisizioni->fetch_assoc()) {
                                            
                                            $id_acquisizione = $row['id'];
                                            $stato = $row['stato'];                                       
                                            ?>
                                        
                                            <tr class="tableRow"
                                            data-line="<?php echo $id_acquisizione; ?>">
                                                <td class="text-center id_acquisizione"><?php echo $id_acquisizione; ?></td>
                                                <td class="text-center data-acquisizione"><?php echo $row['data_acquisizione']; ?></td>
                                                <td class="text-center data-fine"><?php echo $row['data_fine']; ?></td>

                                                <td class="text-center id-operatore"><?php echo $row['id_utente']; ?></td>
                                                <td class="text-center tipologia"><?php echo $row['tipologia']; ?></td>
                                                <td class="text-center stato"><?php echo $stato; ?></td>
                                                <td class="text-center priorita">
                                                    <select title="SELEZIONA URGENZA ACQUISIZIONE" class="form-control <?php if ($stato !== 'Pending') echo 'hidden';?>" name="Seleziona-priorita" id="select-priorita">
                                                        <option value="Alta">Alta</option>
                                                        <option value="Normale"selected>Normale</option>
                                                        <option value="Bassa">Bassa</option>
                                                    </select>
                                                    <span id="selected-text"<?php if ($stato == 'Pending') {echo 'style="display:none"';} else {echo 'style="display:"';}?>>
                                                        <?php if($row['priorita'] === 1) {echo 'Alta';} if($row['priorita'] === 2) {echo 'Normale';} if($row['priorita'] === 3) {echo 'Bassa';}?>
                                                    </span>
                                                </td>
                                                <td class="text-center azioni-acquisizione">
                                                    <div class="col-6">
                                                        <input
                                                            title="SPOSTA RECORD IN CODA"
                                                            class="btn btn-info"
                                                            id="btn_in_coda"
                                                            onClick="mettiInCoda(<?php echo $id_acquisizione; ?>)"
                                                            value="METTI IN CODA"
                                                            style="width: 50%;"
                                                            type="button">
                                                        <input
                                                            title="RIMUOVI DEFINITIVAMENTE RECORD"
                                                            class="btn btn-danger"
                                                            id="btn_rimuovi_acquisizione"
                                                            onClick="rimuoviAcquisizione(<?php echo $id_acquisizione; ?>)"
                                                            value="RIMUOVI"
                                                            style="width: 50%;"
                                                            type="button"
                                                            <?php if($stato === 'In coda') echo 'disabled'; ?>>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        $id_utente = $_SESSION['user_admin_id'];
                                        ?>
                                        </tbody>
                                    </table>

                                </div>
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
<!-- END FOOTER -->





<script>
    $(document).ready(function(){

        App.init();
        
        // TableEditable.init();

// --------------------- CONNESSIONE CON SSE --------------------------
        var source = new EventSource("test_pagina_elenco_acquisizioni_SSE_emanuele.php");

        // Chiamata funzione SSE quando riceve nuovi dati
        source.onmessage = function(event) {
            var data = JSON.parse(event.data);
            aggiornaTabella(data);
        };

        // Gestione errori SSE
        source.onerror = function(event) {
            console.error("Errore nella connessione SSE", event);
        };
    });
    
    changeState();

// ----------------------- INIZIALIZZAZIONE DATA TABLES ---------------------
    var table = $('#sample_editable_1').DataTable({
        order: [[0, 'desc']],
    });

// -------------------------- GESTIONE FILTRI ------------------------------------
    table.column(5).search('^(Pending$)', true, false).draw();

    $('#checkbox_terminate, #checkbox_in_coda, #checkbox_in_lavorazione').change(function() {
        aggiornaFiltri();
    });

    function aggiornaFiltri() {
        if ($('#checkbox_terminate').is(':checked') && $('#checkbox_in_coda').is(':checked')) {
            table.column(5).search('^(?!Pending$|In lavorazione$).*$', true, false).draw();
        } else if ($('#checkbox_terminate').is(':checked') && $('#checkbox_in_lavorazione').is(':checked')) {
            table.column(5).search('^(?!In coda$|Pending$).*$', true, false).draw();
        } else if ($('#checkbox_in_coda').is(':checked') && $('#checkbox_in_lavorazione').is(':checked')) {
            table.column(5).search('^(?!Done$|Pending$|Failed$).*$', true, false).draw();
        } else if ($('#checkbox_terminate').is(':checked')) {
            table.column(5).search('^(Done$|Failed$)', true, false).draw();
        } else if ($('#checkbox_in_coda').is(':checked')) {
            table.column(5).search('^(In coda$)', true, false).draw();        
        } else if ($('#checkbox_in_lavorazione').is(':checked')) {
            table.column(5).search('^(In lavorazione$)', true, false).draw();
        } else {
            table.column(5).search('^(Pending$)', true, false).draw();
        }
    }

    $('#sample_editable_1_filter').find('input').attr('placeholder', 'Cerca ID..');

    $('#sample_editable_1_filter').find('input').on('keyup', function() {
        table.column(0).search(this.value).draw();
        table.column(5).search('').draw();
        if (this.value === '') {
            aggiornaFiltri()
        }
    });

    $('.row_tipo').find('input').on('keyup', function() {
        table.column(4).search(this.value).draw();
        table.column(5).search('').draw();
        if (this.value === '') {
            aggiornaFiltri()
        }
    });

    // ------------------------ CAMBIO STATI COLONNA AZIONI E URGENZA ------------------------
    var select_priorita = 'Normale'
    var rowData = {}

    $('.tableRow').each(function() {
        const idAcquisizione = $(this).data('line');
        rowData[idAcquisizione] = select_priorita;
    });

    $('#sample_editable_1 tbody').on('change', '#select-priorita', function() {
        const row = $(this).closest('tr');
        const idAcquisizione = row.data('line');
        select_priorita = $(this).val();

        console.log('Riga: ' + idAcquisizione + ', Priorita: ' + select_priorita);

        rowData[idAcquisizione] = select_priorita;
    });

    function changeState(row) {
        $('.tableRow').each(function() {
            const row = $(this)
            const stato = row.find('td.stato').text();            

            if (stato == 'Done') {                                                
                row.find("td.azioni-acquisizione").html('<span style="color:green; font-weight: bold; font-size:20px">&#10004;</span>');
            } else if (stato == 'Failed') {
                row.find("td.azioni-acquisizione").html('<span style="color:red; font-weight: bold; font-size:20px">&#10006;</span>');
            } else if (stato == 'In coda') {
                var idAcquisizione = $(this).find('td.id_acquisizione').text()
                row.find(".azioni-acquisizione").html(`<input title="RIPRISTINA COME 'DA FARE'" class="btn btn-warning" id="btn_annulla_acquisizione" onClick="annullaInCoda(${idAcquisizione})" value="ANNULLA" style="width: 50%;" type="button">
                <input class="btn btn-danger" id="btn_rimuovi_acquisizione" value="RIMUOVI" style="width: 50%;" type="button" disabled>`);
            }       
            
        });
        
    }
    
// ---------------------------- FUNZIONI DI MODIFICA STATO E PULSANTI ----------------------------------
    var idUtente = <?php echo $id_utente ?>;
    console.log(idUtente);

    function mettiInCoda(idAcquisizione){
        if (confirm(`SICURO DI VOLER INSERIRE IL RECORD: ${idAcquisizione} IN CODA?`)) {

            const selected_priorita = rowData[idAcquisizione];

            $.ajax({
                url: 'form_actions_M_Emanuele.php',
                type: 'POST',
                data: {
                    action: 'righe_in_coda',
                    id: idAcquisizione,
                    user: idUtente,
                    stato: 'In coda',
                    priorita: selected_priorita
                },
                success: function(response) {
                    console.log(selected_priorita);

                    table.rows().eq(0).each(function(index) {
                        const row = table.row(index).node();
                            if ($(row).data('line') == idAcquisizione) {
                                $(row).find('td.stato').text('Inserendo record "In coda"');

                                $(row).css({"opacity": "0.5"});
                            }                    
                        });
                },
                error: function(xhr, status, error) {
                    alert('Errore durante l\'aggiornamento del record');
                }
            });
        }
    }    
    
    function annullaInCoda(idAcquisizione){
        if (confirm(`VUOI DAVVERO ANNULLARE L\'ESECUZIONE DEL RECORD: ${idAcquisizione}?`)) {
            $.ajax({
                url: 'form_actions_M_Emanuele.php',
                type: 'POST',
                data: {
                    action: 'annulla_righe_in_coda',
                    id: idAcquisizione,
                    user: idUtente,
                    stato: 'Pending'
                },
                success: function(response) {
                    table.rows().eq(0).each(function(index) {
                        const row = table.row(index).node();
                        if ($(row).data('line') == idAcquisizione) {
                            $(row).find('td.stato').text('Ripristinando in "Pending"');

                            $(row).css({"opacity": "0.5"});

                            $(row).find('#select-priorita').val('Normale');
                            rowData[idAcquisizione] = 'Normale';
                        }
                    });
                },
                error: function(xhr, status, error) {
                    alert('Errore durante l\'aggiornamento del record');
                }
            });
        }
    }
    
    function rimuoviAcquisizione(idAcquisizione){
        if (confirm(`IL RECORD ${idAcquisizione} SARA' ELIMINATO DEFINITIVAMENTE!! CONFERMI?`)) {
            $.ajax({
                url: 'form_actions_M_Emanuele.php',
                type: 'POST',
                data: {
                    action: 'rimuovi_acquisizione',
                    user: idUtente,
                    id: idAcquisizione
                },
                success: function(response) {
                    table.rows().eq(0).each(function(index) {
                            const row = table.row(index).node();
                            if ($(row).data('line') == idAcquisizione) {
                                $(row).css({"opacity": "0", "transition": "opacity 5s ease-in-out"});
                                setTimeout(function() {
                                    table.row($(row)).remove().draw();
                                }, 5000);
                            }
                    });
                },
                error: function(xhr, status, error) {
                    alert('Errore durante l\'aggiornamento del record');
                }
            });

        }
    }

// ----------------------- FUNZIONI MODIFICA TABELLA DATI AGGIORNATI CON SSE -----------------------------
    function aggiornaRiga(row, dato) {
        const statoRiga = row.find('td.stato');
        const azioniAcquisizione = row.find('td.azioni-acquisizione');
        const prioritaRiga = row.find('td.priorita');
        const dataFineRiga = row.find('td.data-fine');

        statoRiga.text(dato.stato);

        if (dato.stato !== 'Pending') {

            row.find('#select-priorita').hide();

            if(dato.priorita === 1) {prioritaRiga.text('Alta')}
            if(dato.priorita === 2) {prioritaRiga.text('Normale')}
            if(dato.priorita === 3) {prioritaRiga.text('Bassa')}
        }

        switch (dato.stato) {
            case 'In lavorazione':
                azioniAcquisizione.removeClass('btn btn-primary').text("Acquisizione in corso...");
                break;
            case 'Done':
                azioniAcquisizione.html('<span style="color:green; font-weight: bold; font-size:20px">&#10004;</span>');
                dataFineRiga.text(dato.data_fine);
                break;
            case 'Failed':
                azioniAcquisizione.html('<span style="color:red; font-weight: bold; font-size:20px">&#10006;</span>');
                dataFineRiga.text(dato.data_fine);
                break;
            case 'In coda':
                azioniAcquisizione.html(`
                    <input title="RIPRISTINA COME 'DA FARE''" class="btn btn-warning" onClick="annullaInCoda(${dato.id})" id="btn_annulla_acquisizione" value="ANNULLA" style="width: 50%;" type="button">
                    <input class="btn btn-danger" id="btn_rimuovi_acquisizione" value="RIMUOVI" style="width: 50%; cursor: not-allowed;" type="button" disabled>
                `);
                alert(`Id Acquisizione: ${dato.id} inserito in coda correttamente!`);
                row.css({"opacity": ""});
                break;
            case 'Pending':
                azioniAcquisizione.html(`
                    <input title="SPOSTA RECORD IN CODA" class="btn btn-info" id="btn_in_coda" onClick="mettiInCoda(${dato.id})" value="METTI IN CODA" style="width: 50%;" type="button">
                    <input title="RIMUOVI DEFINITIVAMENTE RECORD" class="btn btn-danger" id="btn_rimuovi_acquisizione" onClick="rimuoviAcquisizione(${dato.id})" value="RIMUOVI" style="width: 50%;" type="button">
                `);
                prioritaRiga.html(`
                    <select title="SELEZIONA URGENZA ACQUISIZIONE" class="form-control" name="Seleziona-priorita" id="select-priorita">
                        <option value="Alta">Alta</option>
                        <option value="Normale" selected>Normale</option>
                        <option value="Bassa">Bassa</option>
                    </select>
                    <span id="selected-text" style="display:none;"></span>
                `);
                alert(`Id Acquisizione: ${dato.id} ripristinato come in "Pending" correttamente!`);
                row.css({"opacity": ""});
                break;
        }

        table.row(row).invalidate().draw(false);
    }

    function creaNuovaRiga(dato) {
        if(dato.stato != '' && dato.stato != 'null') {
            const new_row_html =
             `<tr class="tableRow" data-line="${dato.id}">
                <td class="text-center id_acquisizione">${dato.id}</td>
                <td class="text-center data-acquisizione">${dato.data_acquisizione}</td>
                <td class="text-center data-fine">${(dato.stato == 'Done' || dato.stato == 'Failed') ? dato.data_fine : ''}</td>
                <td class="text-center id-operatore">${dato.id_utente}</td>
                <td class="text-center tipologia">${dato.tipologia}</td>
                <td class="text-center stato">${dato.stato}</td>
                <td class="text-center priorita">
                ${dato.stato === 'Pending'?'<select class="form-control" name="Seleziona-priorita" id="select-priorita">' +
                                                '<option value="Alta">Alta</option>' +
                                                '<option value="Normale"selected>Normale</option>' +
                                                '<option value="Bassa">Bassa</option>' +
                                            '</select>'
                                        :`<span id="selected-text">${dato.priorita}</span>`
                }
                </td>
                <td class="text-center azioni-acquisizione">
                    <input class="btn btn-info" id="btn_in_coda" onClick="mettiInCoda(${dato.id})" value="METTI IN CODA" style="width: 50%;" type="button">
                    <input class="btn btn-danger" id="btn_rimuovi_acquisizione" onClick="rimuoviAcquisizione(${dato.id})" value="RIMUOVI" style="width: 50%;" type="button">
                </td>
            </tr>`;
            const new_row = $(new_row_html);
            table.row.add(new_row).draw();

            rowData[dato.id] = 'Normale';
        }
    }

    function aggiornaTabella(data) {
        Object.entries(data).forEach(([key, value]) => {
            const dato = value;
            let rowFound = false;

            table.rows().eq(0).each(function(index) {
                const row = table.row(index).node();
                if ($(row).data('line') == dato.id) {
                    const currentState = $(row).find('td.stato').text();
                    if (currentState !== dato.stato) {
                        aggiornaRiga($(row), dato);
                    }
                    rowFound = true;
                }
            });

            if (!rowFound) {
                creaNuovaRiga(dato);
            }
        });
    }

</script>
</body>
<!-- END BODY -->
</html>