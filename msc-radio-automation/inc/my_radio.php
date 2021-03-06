<?php  
    
class my_radio {
    protected $CLIENT_KEY       = '';
    public $IS_DEGUG            = false;
    protected $LANG             = WP_MSCRA_LANG_DEF;
    protected $COOKIE_USER      = '';
    protected $URL_API        =  'http://api.msc-soft.com';    
    protected $API_VERSION      = 'V2';
    protected $URL_QUERY_API    = '';
    protected $TIME_CONNECTION  = '';

    Public $RESPOSTA_MESSAGE    = '';
    Public $RESPOSTA_STATUS     = '0';
    Public $RESPOSTA_CODE       = '0';
    Public $RESPOSTA_ROWS       = '0';

    Public $RESPOSTA_ROWS_PODCAST   = '0';
    Public $RESPOSTA_ROWS_MARKS     = '0';
    Public $RESPOSTA_ROWS_ADS       = '0';

    public $NomEmissora         = '';
    public $URLStreaming        = '';
    public $ProgramacioDefecte  = '';
    public $URL_FaceBook        = '';   
    public $USER_Twitter        = ''; 
    public $KEY_Twitter         = '';     
    public $NomAudio1           = '';
    public $NomAudio2           = '';    
    
    function __construct($client_key,$lang,$version, $debug= FALSE) {        
        $this->my_client_key  = $client_key;
        $this->IS_DEGUG  = ($debug== '')  ? FALSE : TRUE;
        $this->LANG  = $lang;
        
        if ($this->my_client_key == '') {
            $this->RESPOSTA_MESSAGE = 'No client';
            return;
        }
        
        if (!session_id()) {
            session_start();
        }
        if (isset($_COOKIE['mscra_usr'])) {
            $this->COOKIE_USER = $_COOKIE['mscra_usr'];                
        }else{
            //TODO:
            $this->COOKIE_USER = '';
        }

        
        $this->RESPOSTA_MESSAGE = 'OK' ;    
        $this->TIME_CONNECTION = date(datetime::ISO8601);
        // consultem els paràmetres de configuració
        $vars[0] = 'lang='.$this->LANG;        
        $vars[1] = 'ver='.$version ;        
        $this->QueryGetTable(seccions::ADMIN, sub_seccions::INIWORDPRESS,$vars);                 
    }
    
            //Funció finalitza
    function __destruct() {
        
    }
    
/**
 * Adds up two int numbers
 * @param string $seccion the first number to add
 * @param string $sub_seccion the second number to add
 * @param string $sub_seccion the second number to add
 * @return string[] the result of the operation
 */
    function QueryGetTable($seccion,$sub_seccion,$vars= NULL,$MSGonJS= FALSE){
        $url_vars = '?';
        //$url_vars = '';
        if($vars<> NULL){            
            $count = count($vars);            
            for ($i = 0; $i < $count; $i++) {
                $url_vars .= '&'.$vars[$i];
            }            
        }                      
        $url_vars .= '&user='.$this->COOKIE_USER.'&lang='.$this->LANG;
        $this->URL_QUERY_API = $this->URL_API.'/'.$this->API_VERSION.'/'.$this->my_client_key.'/'.$seccion.'/'.$sub_seccion.'/'.$url_vars;                        
        
        /*
        if ($sub_seccion==sub_seccions::LISTPODCAST_PRG){
            $response = wp_remote_get( $this->URL_QUERY_API );            
            $body     = wp_remote_retrieve_body( $response );
            $body     = $response['body'];
            $xml  = simplexml_load_string($body);
            $code = $xml->header->status->code;
            print_r($code);
        }         
        */      
        $xml = new DOMDocument();        
        if ($xml->load($this->URL_QUERY_API) == FALSE){                              
            $my_message = $this->URL_QUERY_API ;
            mscra_show_message( $my_message,message_type::DANGER);            
            $this->RESPOSTA_STATUS = 'KO';
            $this->RESPOSTA_CODE = SERVER_ERROR_NO_DEF;
            $this->RESPOSTA_MESSAGE = 'Error XML';
            $this->RESPOSTA_ROWS = 0;        
            return;             
        }    
        if ( $this->IS_DEGUG==TRUE){
             if ($MSGonJS==TRUE){
                $this->RESPOSTA_MESSAGE = $this->URL_QUERY_API ;
            }else{
                mscra_show_message( $this->URL_QUERY_API ,message_type::INFO);            
            } 
        }
        $status = $xml->getElementsByTagName( "status" );
        $code = $xml->getElementsByTagName( "code" );
        $message = $xml->getElementsByTagName( "message" );
        $rows = $xml->getElementsByTagName( "rows" );
        
        $this->RESPOSTA_STATUS = $status->item(0)->nodeValue;
        $this->RESPOSTA_CODE = $code->item(0)->nodeValue;
        $this->RESPOSTA_MESSAGE = $message->item(0)->nodeValue;
        $this->RESPOSTA_ROWS = $rows->item(0)->nodeValue;
        
        if($xml->getElementsByTagName( "row_pod" ).length == 0){
            $this->RESPOSTA_ROWS_PODCAST = 0;
        }else{
            $rows = $xml->getElementsByTagName( "row_pod" );
            $this->RESPOSTA_ROWS_PODCAST = $rows->item(0)->nodeValue;
        }

        
        if ($this->RESPOSTA_ROWS>0){                                                   
            switch (strtoupper($seccion)){
                case 'ADMIN':
                    switch  (strtoupper($sub_seccion)){                                                
                        case 'INIWORDPRESS':
                        case 'STREAM':
                            $this->get_params($xml); break;                            
                    }
                    break;
                default:
                    //Per defecte
                    $list_results =  $this->xml_to_array($xml);
                    return $list_results['msc-soft']['results'];                                        
                    break;                            
            }
        }else{
            switch (strtoupper($seccion)){
                case 'ADVERTISING':
                    switch  (strtoupper($sub_seccion)){                                                
                        case 'RADIATION':
                            $list_results =  $this->xml_to_array($xml);
                            return $list_results['msc-soft']['results'];                                        
                            break;                            
                    }                    
                    break;
            }
        }
    }
    
    function ExecuteNonQuery($seccion,$sub_seccion,$vars= NULL,$MSGonJS= FALSE){
        $url_vars = '?';
        //$url_vars = '';
        if($vars<> NULL){            
            $count = count($vars);            
            for ($i = 0; $i < $count; $i++) {
                $url_vars .= '&'.$vars[$i];
            }            
        }                      
        $url_vars .= '&user='.$this->COOKIE_USER.'&lang='.$this->LANG;
        $this->URL_QUERY_API = $this->URL_API.'/'.$this->API_VERSION.'/'.$this->my_client_key.'/'.$seccion.'/'.$sub_seccion.'/'.$url_vars;  
        $xml = new DOMDocument();
        if ($xml->load($this->URL_QUERY_API) == FALSE){                              
            $my_message = $this->URL_QUERY_API ;
            mscra_show_message( $my_message,message_type::DANGER);            
            $this->RESPOSTA_STATUS = 'KO';
            $this->RESPOSTA_CODE = SERVER_ERROR_NO_DEF;
            $this->RESPOSTA_MESSAGE = 'Error XML';
            $this->RESPOSTA_ROWS = 0;        
            return;             
        }    
        if ( $this->IS_DEGUG==TRUE){
             if ($MSGonJS==TRUE){
                $this->RESPOSTA_MESSAGE = $this->URL_QUERY_API ;
            }else{
                mscra_show_message( $this->URL_QUERY_API ,message_type::INFO);            
            } 
        }
        $status = $xml->getElementsByTagName( "status" );
        $code = $xml->getElementsByTagName( "code" );
        $message = $xml->getElementsByTagName( "message" );
        $rows = $xml->getElementsByTagName( "rows" );
        
        $this->RESPOSTA_STATUS = $status->item(0)->nodeValue;
        $this->RESPOSTA_CODE = $code->item(0)->nodeValue;
        $this->RESPOSTA_MESSAGE = $message->item(0)->nodeValue;
        $this->RESPOSTA_ROWS = $rows->item(0)->nodeValue;        
    }

    function get_params($xml){
        // establim variables               
        $counter = 0;
        $params = $xml->getElementsByTagName('param');
        foreach ($params as $param) {                                
            $value = $param ->textContent;             
            $name =  $param ->getAttribute('NAME');
            $id = $param ->getAttribute('ID');            
            switch ($id){
                case params::paramNomClient:  $this->NomEmissora  = $value; break;
                case params::paramUrlStreaming:  $this->URLStreaming  = $value ; break;
                case params::paramProgramacioDefecte:  $this->ProgramacioDefecte  = $value ;break;
                case params::paramFaceBooK:  $this->URL_FaceBook  = $value ; break;
                case params::paramTwitter:  $this->USER_Twitter  = $value ; break;
                case params::paramTwitterKey:  $this->KEY_Twitter  = $value ; break;                
            }            
        }
       
    }
    
    function xml_to_array($root) {
        $result = array();    
        if ($root->hasAttributes()) {
            $attrs = $root->attributes;
            foreach ($attrs as $attr) {
                $result[$attr->name] = $attr->value;
            }
        }
        if ($root->hasChildNodes()) {
            $children = $root->childNodes;            
            if ($children->length == 1) {
                $child = $children->item(0);
                if ($child->nodeType == XML_TEXT_NODE) {
                    $result['ID'] = $child->nodeValue;                
                    return count($result) == 1 ? $result['ID']: $result;                    
                }
            }
            $groups = array();
            foreach ($children as $child) {            
                if (!isset($result[$child->nodeName])) {
                    $result[$child->nodeName] = $this->xml_to_array($child);
                } else {
                    if (!isset($groups[$child->nodeName])) {
                        $result[$child->nodeName] = array($result[$child->nodeName]);
                        $groups[$child->nodeName] = 1;
                    }
                    $result[$child->nodeName][] = $this->xml_to_array($child);                
                }
            }
        }
        return $result;
    }
}

