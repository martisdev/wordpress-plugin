<?php        
    interface seccions{
        const MUSIC = 'MUSIC';
        const CALENDAR = 'CALENDAR';
        const ADMIN = 'ADMIN';
        const PROGRAMS = 'PROGRAMS';
        const PODCAST = 'PODCAST';
        const ADVERTISING = 'ADVERTISING';
        const CLOUD = 'CLOUD';
    }

    interface sub_seccions {
        //common
        const LIKE = 'LIKE';
        const UNLIKE = 'UNLIKE';
        //music
        const LISTRADIA = 'LISTRADIA';
        const LISTRADIADATE = 'LISTRADIADATE';
        const LISTEXITS = 'LISTEXITS';
        const LISTOPCIONSWEB = 'LISTOPCIONSWEB';
        const VOTEOPCIONWEB = 'VOTEOPCIONWEB';
        const VOTE = 'VOTE';
        const SHOWINFO = 'SHOWINFO';
        const LASTALBUMS = 'LASTALBUMS';
        const SEARCHALBUM = 'SEARCHALBUM';
        const SEARCHSONG = 'SEARCHSONG';
        const PROGRAMSONG = 'PROGRAMSONG';
        //Calendar
        const GRIDDAY = 'GRIDDAY';
        const NOWONAIR = 'NOWONAIR';
        const NOWPLAYING = 'NOWPLAYING';
        //admin
        //const PARAMS = 'PARAMS';
        const INISTANDALONEWEB = 'INISTANDALONEWEB';
        const INIWORDPRESS = 'INIWORDPRESS';        
        const INISYSTEMSUITE = 'INISYSTEMSUITE';        
        const STREAM = 'STREAM';
        const INISTREAMSERVER = "INISTREAMSERVER";
        Const INIFTP  = "INIFTP";
        //programs
        const SHOWINFO_PRG = 'SHOWINFO_PRG';
        const LIST_PRGS = 'LIST_PRGS';        
        const LISTPODCAST_PRG = 'LISTPODCAST_PRG';        
        //podcast                       
        const SHOWINFO_PCAST = 'SHOWINFO_PCAST';
        const LISTEN = 'LISTEN';
        const DOWNLOAD = 'DOWNLOAD';  
        //ADVERTISING
        const LOGIN = 'LOGIN';  
        const RADIATION = 'RADIATION';  
        const CREDENTIALS = 'CREDENTIALS';
        //cloud
        const REGISTER = 'REGISTER';  
    }
    
    interface params{   
    // Parametres del sistema    
    const paramLogoEmpresa = 9;
    const paramNomClient = 10;    
    const paramIntervRitme = 12;
    const paramFaceBooK = 13;
    const paramTwitter = 14;
    const paramProgramacioDefecte = 15;    
    const paramUrlStreaming = 17;    
    const paramVersioDBS = 23;
    const paramTwitterKey = 24;    
    const paramIntentsUsers = 26;
    const paramTempPasPoxConex = 27;
    const paramAtvPSW = 28;    
    const paramMoneda = 30;
    const paramContraValor = 31;
    const paramCanvi = 32;
    const paramActvSrvEmergenci = 33;
    const paramErrEmail = 34;    
    const   paramDirRemote = 55;     
    const   paramNumTracsVotWeb = 64;
    const   paramNumVotWebTorn = 65;    
    const paramDIR_PODCAST      = '';
}   
//Status de la API
    const SUCCES = 'succes'; 
    const ERROR = 'error';    
    //Respostes API de la connexió XML al servidor de control (1 nivell servidor api)
    const SERVER_CONNEX_OK = 0;
    const SERVER_CONNEX_ERROR = 1;
    const SERVER_REGISTRE_CLI_ERROR = 2;
    const SERVER_CONF_CLI_ERROR = 3;
    const SERVER_DBS_CLI_ERROR = 4;
    const SERVER_CLIENT_EXPIRED = 5;
    const SERVER_ERROR_GET = 6;
    
    
    const SERVER_ERROR_NO_DEF = 99;
    //Respostes a la connexió a la dbs del client (100 nivell dbs client)
    const DBS_CONNECT_OK    = 100;
    const DBS_ERROR_CONNECT = 101; 
    const DBS_NO_RESULT = 102; 
    const DBS_ERROR_SQL = 103; 
    //respostes clients publicitat
    const ADV_LOGIN_OK = 500; 
    const ADV_LOGIN_KO = 501; 
    
    //Tipus d'emissió
    const TIP_AUTOMATIC     = 0;
    const TIP_CONEX_CENTRAL = 1;
    const TIP_DIRECTE       = 2;     
    
   //Tipus de programació emissió
    const TIP_AUTOMATIC_LLISTA          = 0;
    const TIP_AUTOMATIC_RADIOFORMULA    = 1;
    const TIP_AUTOMATIC_PROGRAMA        = 2;
    const TIP_DIRECTE_                  = 3;
    const TIP_CONEX_CENTRAL_             = 4; 
    
    
    const WP_MSCRA_TMP_IMG_DIR = 'msc-tmp-img';
    const WP_MSCRA_PODCAST_DIR = 'podcasting';
    CONST WP_MSCRA_ROWS_PER_PAGE = 20;
    Const WP_MSCRA_LANG_DEF = 'en';
    
    const WP_MSCRA_URL_JAMENDO_TRACK = 'https://www.jamendo.com/track/';