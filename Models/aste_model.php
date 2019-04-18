<?php

/*
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class Aste_Model extends Model {

    //Proprietà Oggetto
    public $id;
    public $idImport;
    public $ComuneTribunale;
    public $SiglaProvTribunale;
    public $codiceComuneTribunale;
    public $linkTribunale;
    public $rge;
    public $lotto;
    public $tipoProcedura;
    public $rito;
    public $giudice;
    public $delegato;
    public $custode;
    public $curatore;
    public $valorePerizia;
    public $dataPubblicazione;
    public $noteGeneriche;
    public $datiCatastali;
    public $disponibilita;
    public $importoBaseAsta;
    public $importoOffertaMinima;
    public $noteAggiuntive;
    public $dataAsta;
    public $linkAllegati;
    public $CodiceNazione;
    public $CodiceComune;
    public $Comune;
    public $Provincia;
    public $ComuneProvinciaCompleto;
    public $CodiceQuartiere;
    public $CodiceLocalita;
    public $Strada;
    public $Strada_testo;
    public $Indirizzo;
    public $Civico;
    public $PubblicaCivico;
    public $Cap;
    public $PubblicaIndirizzo;
    public $Latitudine;
    public $Longitudine;
    public $PubblicaMappa;
    public $Contratto;
    public $DurataContratto;
    public $Categoria;
    public $IDTipologia;
    public $NrLocali;
    public $TrattativaRiservata;
    public $MQSuperficie;
    public $TipoProprieta;
    public $Asta;
    public $Pregio;
    public $SpeseMensili;
    public $URLPlanimetria;
    public $URLVirtualTour;
    public $URLVideo;
    public $DataInserimento;
    public $DataInserimento_d;
    public $DataModifica;
    public $DataModifica_d;
    public $ClasseCatastale;
    public $RenditaCatastale;
    public $Collaborazioni;
    public $Descrizioni_Lingua;
    public $Titolo;
    public $Testo;
    public $TestoBreve;
    public $StatoImmobile;
    public $Piano;
    public $PianoFuoriTerra;
    public $PianiEdificio;
    public $NrCamereLetto;
    public $NrAltreCamere;
    public $NrBagni;
    public $Cucina;
    public $NrTerrazzi;
    public $NrBalconi;
    public $Ascensore;
    public $NrAscensori;
    public $BoxAuto;
    public $BoxIncluso;
    public $NrBox;
    public $NrPostiAuto;
    public $Cantina;
    public $Portineria;
    public $GiardinoCondominiale;
    public $GiardinoPrivato;
    public $AriaCondizionata;
    public $Riscaldamento;
    public $TipoImpiantoRiscaldamento;
    public $TipoRiscaldamento;
    public $SpeseRiscaldamento;
    public $Arredamento;
    public $StatoArredamento;
    public $AnnoCostruzione;
    public $TipoCostruzione;
    public $StatoCostruzione;
    public $Allarme;
    public $Piscina;
    public $Tennis;
    public $Caminetto;
    public $Idromassaggio;
    public $VideoCitofono;
    public $FibraOttica;
    public $ClasseEnergetica;
    public $IndicePrestazioneEnergetica;
    public $EsenteClasseEnergetica;
    public $Energia;
    public $IDImmagine;
    public $immagine_URL;
    public $immagine_DataModifica;
    public $immagine_Posizione;
    public $immagine_TipoFoto;
    public $immagine_Titolo;
    public $status;
    public $adminNote;
    public $errorsText;
    public $altriBeni1;
    public $altriBeni2;
    public $altriBeni3;
    public $altriBeni4;
    public $altriBeni5;
    public $altriBeni6;
    public $altriBeni7;
    public $urlVisione;
    
    public $uploadValido;
    public $uploadErrorsTxt;
    public $isNew;
    public $uploadType;
    
    public $CucinaDesc;
    public $IndirizzoCompleto;
    
    
    // Costruttore
    function __construct() {
        parent::__construct();
    }

    // Get User list
    public function getAsteList($status,$orderBy) {
        $data = ' * ';
        $where = ' status!=:statusDeleted ';
        $parameters = array();
        $parameters[":statusDeleted"] = 'deleted';
        if ($status!=NULL ) {
            $where .= ' AND status=:statusFilter ';
            $parameters[":statusFilter"] = $status;
        }
        if ($orderBy==NULL) {
            $orderBy = " id DESC ";
        }
        $result = $this->db->selectWithOrder(TAB_ASTE, $data, $where, $parameters, false, $orderBy, NULL);
        
        // Return
        return $result;
    }

    // Get Lista ASTE PER FILTRI
    public function getAsteListByFiltering($data,$where,$parameters,$orderBy) {
        if ($orderBy==NULL) {
            $orderBy = " id DESC ";
        }

        $result = $this->db->selectWithOrder(TAB_ASTE, $data, $where, $parameters, false, $orderBy, NULL);

        // Return
        return $result;
    }

    
    
    // INSERT - Nuovo record (Return ID)
    public function create($data) {
        // Execute
        $this->db->insert(TAB_ASTE, $data);
        return $this->db->lastInsertId();
    }
    
    // UPDATE - Aggiorna dati da importazione
    public function updateDataFromImport($data,$parameters,$where) {
        // Execute
        $this->db->update(TAB_ASTE, $data, $where, $parameters);
        return true;
        
    }
    
    // UPDATE - Aggiorna dati
    public function updateData($data,$parameters,$where) {
        // Execute
        $this->db->update(TAB_ASTE, $data, $where, $parameters);
        return true;
        
    }
    
    // SELECT - Get Data from ID
    public function getDataFromId($id) {
        $data = ' * ';
        $where = '  id=:id AND status!=:statusDeleted AND status!=:statusError ';
        $parameters = array();
        $parameters[":id"] = $id;
        $parameters[":statusDeleted"] = "deleted";
        $parameters[":statusError"] = "error";
        $result = $this->db->select(TAB_ASTE, $data, $where, $parameters, true);
        return $result;
    }

    // SELECT COUNT - Num.Aste per CodiceComuneTribunale
    public function getNumAsteFromTribunale($codiceComuneTribunale){
        $data = ' id ';
        $where = '  codiceComuneTribunale=:codiceComuneTribunale AND status!=:statusDeleted AND status!=:statusError ';
        $parameters = array();
        $parameters[":codiceComuneTribunale"] = $codiceComuneTribunale;
        $parameters[":statusDeleted"] = "deleted";
        $parameters[":statusError"] = "error";
        $result = $this->db->selectCount(TAB_ASTE,$data,$where,$parameters);

        return $result;
    }


    
}

