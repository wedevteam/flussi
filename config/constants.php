<?php

/* 
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

// ==============================================
// DEBUG ATTIVO
define('DEBUG_ATTIVO', true);
// ==============================================

// Hash per password
define('HASH_KEY', 'Altripedeggiando2018!');

// Hash generale
define('HASH_GENERAL_KEY', 'SviluppoMVCperPHP2018!');

// Routes
define ('ROUTE_CONTROLLER',0);
define ('ROUTE_ACTION',1);
define ('ROUTE_ARGS',2);

define ('OWNER','owner');

// Preferenze sessione
define ('SESSION_DURATION',20); // durata in minuti
define ('SESSION_LAST','session_lastactivity');

// Keys Sessione
define('PLATFORM','PLATFORM');
define('USER','USER');

// Preferenze grafica
define ('HEADER_LOGIN','headerAuth');
define ('HEADER_MAIN','headerMain');

// Errori
// codice = controller/azione/progressivo (tre cifre)

// LOGIN
define ('ERR_LOGIN',1);
define ('ERR_LOGIN_TEXT','Email o password non valide');
define ('ERROR_NOEMAIL',2);
define ('ERROR_NOEMAIL_TEXT',"L'Email inserita non è presente in archivio.");
define ('ERROR_HASH',3);
define ('ERROR_HASH_TEXT',"Il link non è valido.");
define ('ERROR_PASSWORD',4);
define ('ERROR_PASSWORD_TEXT',"Password errata.");
define ('ERROR_EMAIL',5);
define ('ERROR_EMAIL_TEXT',"Email già presente in archivio.");

// Agency - New
define ('ER_AGENCY_DUPLICATE',20);
define ('ER_AGENCY_DUPLICATE_TEXT',"Email o Id Agenzia già utilizzati");
define ('ER_AGENCY_COMUNE_INVALID',21);
define ('ER_AGENCY_COMUNE_INVALID_TEXT',"Inserire un Comune valido");
define ('ER_AGENCY_COORD_INVALID',22);
define ('ER_AGENCY_COORD_INVALID_TEXT',"Coordinate non trovate.");

// Imports
define ('ER_IMPORT_INVALID',50);
define ('ER_IMPORT_INVALID_TEXT',"Problemi durante l'importazione del File. Verifica che il tipo di estensione sia corretta (.csv)");
define ('ER_IMPORT_NORECORDS',51);
define ('ER_IMPORT_NORECORDS_TEXT',"Non ci sono records da importare.");
define ('ER_IMPORT_SAVE',52);
define ('ER_IMPORT_SAVE_TEXT',"Errore durante il salvataggio delle Aste. Riprova.");
define ('ER_IMPORT_INVALID_MOVEFILE',53);
define ('ER_IMPORT_INVALID_MOVEFILE_TEXT',"Problemi durante la lettura del file .csv.");


// Exports
define ('ER_EXPORT_INVALID',60);
define ('ER_EXPORT_INVALID_TEXT',"Problemi durante l'esportazione. Riprova.");
define ('ER_EXPORT_INVALID_SENDXML',61);
define ('ER_EXPORT_INVALID_SENDXML_TEXT',"Problemi durante dei files .xml a Getrix");
define ('ER_EXPORT_AGENZIE_NONPRESENTI',62);
define ('ER_EXPORT_AGENZIE_NONPRESENTI_TEXT',"Non ci sono Agenzie disponibili per l'esportazione.");
define ('ER_EXPORT_IMM_NONPRESENTI',63);
define ('ER_EXPORT_IMM_NONPRESENTI_TEXT',"Non ci sono Aste corrispondenti per l'esportazione.");

// Edit Asta (agenzia)
define ('ER_ASTA_EDIT_GENERIC',70);
define ('ER_ASTA_EDIT_GENERIC_TEXT',"Impossibile elaborare le modifiche richieste.");
define ('ER_ASTA_EDIT_DATAVISIONE',71);
define ('ER_ASTA_EDIT_DATAVISIONE_TEXT',"Inserire la Data di richiesta visione.");
define ('ER_UPLOADFILE_FILENONVALIDO',72);
define ('ER_UPLOADFILE_FILENONVALIDO_TEXT',"Problemi durante il caricamento del File. Riprova.");
define ('ER_UPLOADFILE_ESTENSIONENONVALIDA',73);
define ('ER_UPLOADFILE_ESTENSIONENONVALIDA_TEXT',"File non valido. Le estensioni accettate sono: .jpg, .jpeg, .png.");
define ('ER_UPLOADFILE_SIZENONVALIDA',74);
define ('ER_UPLOADFILE_SIZENONVALIDA_TEXT',"File non valido: la dimensione non deve essere superiore a 5MB.");
define ('ER_UPLOADFILE_PROBLEMAUPLOAD',75);
define ('ER_UPLOADFILE_PROBLEMAUPLOAD_TEXT',"Problemi durante l'upload del File. Riprova.");
define ('ER_ASTA_EDIT_ORAVISIONE',76);
define ('ER_ASTA_EDIT_ORAVISIONE_TEXT',"Inserire l'Ora di richiesta visione.");
define ('ER_ASTA_INVIOMAIL_VISIONE',77);
define ('ER_ASTA_INVIOMAIL_VISIONE_TEXT',"Errore durante l'invio dell'Email dell'Appuntamento.");




// Errore Generico
define ('ER_GENERICO',999);
define ('ER_GENERICO_TEXT',"Qualcosa è andato storto! Riprova...");


// Modifiche salvate
define ('MESS_MODIFICHE_SALVATE',99);
define ('MESS_MODIFICHE_SALVATE_TEXT',"Modifiche salvate con successo!");



//----------------------------
// OGGETTO EMAILS
//----------------------------
define ('EMAIL_RECUPERO_PW', 'Recupero Password');


//----------------------------
// PAGINE
//----------------------------
// Sezione Dashboard
define ('DASHBOARD_TITLE','Dashboard');
define ('DASHBOARD_BC_ROOT','');
define ('DASHBOARD_BC_CURRENT','Dashboard');

// Sezione Users->
define ('USERS_TITLE','Utenti');
define ('USERS_ALLIEVI_TITLE','Allievi');
define ('USERS_STAFF_TITLE','Staff');
define ('USERS_BC_ROOT','');
define ('USERS_BC_CURRENT','Utenti');



//----------------------------
// COSTANTI GETRIX
//----------------------------
define ("GX_PIANOFUORITERRA", serialize (array ("", "Terra", "Scantinato", "Seminterrato", "Rialzato", "Ammezzato", "Altro", "Ultimo Piano", "Intero Edificio", "Interrato")));
define ("GX_CUCINA", serialize (array ("", "Abitabile", "Angolo Cottura", "Cucinino", "Semiabitabile", "Tinello", "A vista")));
define ("GX_BOXAUTO", serialize (array ("", "Singolo", "Doppio", "Triplo")));
define ("GX_CANTINA", serialize (array ("Non specificato", "Presente", "Assente")));
define ("GX_GIARDINOPRIVATO", serialize (array ("Non specificato", "Presente", "Assente")));
define ("GX_ARIACONDIZIONATA", serialize (array ("","Autonomo", "Centralizzato", "Predisposizione Impianto")));
define ("GX_RISCALDAMENTO", serialize (array ("","Autonomo", "Centralizzato")));
define ("GX_TIMPOIMPIANTORISCALDAMENTO", serialize (array ("Non specificato","A radiatori", "A pavimento", "Ad aria", "A stufa")));
define ("GX_TIPORISCALDAMENTO", serialize (array ("","Metano","Gasolio", "Gpl", "Pannelli", "Aria", "Gas", "Pellet", "Legna", "Solare", "Fotovoltaico", "Teleriscaldamento", "Pompa di calore")));



