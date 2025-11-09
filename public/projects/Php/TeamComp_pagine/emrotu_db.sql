-- -----------------------------------------------------
-- Tabella: ed_acquisizioni_file
-- Database: if0_40236975_emrotu_db
-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS ed_acquisizioni_file (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utente INT NOT NULL,
    data_acquisizione DATETIME DEFAULT NULL,
    data_fine DATETIME DEFAULT NULL,
    tipologia VARCHAR(255) DEFAULT NULL,
    stato VARCHAR(50) DEFAULT NULL,
    priorita INT DEFAULT 2, -- 1 = Alta, 2 = Normale, 3 = Bassa
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Dati di esempio (facoltativi, utili per testare il codice PHP)
INSERT INTO ed_acquisizioni_file (id_utente, data_acquisizione, data_fine, tipologia, stato, priorita)
VALUES
(623561, '2025-10-01 09:00:00', '2025-10-01 17:00:00', 'Documentazione tecnica', 'Pending', 1),
(623561, '2025-10-02 10:00:00', '2025-10-02 18:00:00', 'Analisi dati', 'In coda', 2),
(623561, '2025-10-03 11:00:00', '2025-10-03 19:00:00', 'Report finale', 'Completato', 3);
