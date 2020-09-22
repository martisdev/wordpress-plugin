<?php  
    
class my_radio {
    protected $CLIENT_KEY       = '';
    //protected $MSC_KEY       = '';
    public $IS_DEGUG            = false;
    protected $LANG             = LANG_DEF;
    protected $COOKIE_USER      = '';
    protected $URL_API        =  'http://api.msc-soft.com';
    //protected $URL_API        =  'http://localhost/api';
    protected $API_VERSION      = 'V2';
    protected $URL_QUERY_API    = '';
    protected $TIME_CONNECTION  = '';

    Public $RESPOSTA_MESSAGE    = '';
    Public $RESPOSTA_STATUS     = '0';
    Public $RESPOSTA_CODE       = '0';
    Public $RESPOSTA_ROWS       = '0';

    public $NomEmissora         = '';
    public $URLStreaming        = '';
    public $ProgramacioDefecte  = '';
    public $URL_FaceBook        = '';   
    public $USER_Twitter        = ''; 
    public $KEY_Twitter         = '';     
    public $NomAudio1           = '';
    public $NomAudio2           = '';    
    
    function __construct($client_key,$lang,$debug= FALSE) {        
        $this->my_client_key  = $client_key;//(isset($_SESSION['msc_client_key']))  ? $_SESSION['msc_client_key'] : get_option('msc_client_key');        
        $this->IS_DEGUG  = ($debug== '')  ? FALSE : TRUE;// $debug; //(isset($_SESSION['msc_debug']))  ? $_SESSION['msc_debug'] : get_option('msc_debug');         
        $this->LANG  = $lang;//(isset($_SESSION['msc_lang']))  ? $_SESSION['msc_lang'] : get_locale();                                        
        
        if ($this->my_client_key == '') {
            $this->RESPOSTA_MESSAGE = 'No client';
            return;
        }
        
        if(!isset($_SESSION['msc_client_key'])){session_start();}
        $_SESSION['msc_client_key'] = $this->my_client_key;
        $_SESSION['msc_debug'] = $this->IS_DEGUG; 
        $_SESSION['msc_lang'] = $this->LANG; 
        
        //$this->COOKIE_USER = session_id();                
        $this->COOKIE_USER = $_COOKIE['msc_usr'];                
        $this->RESPOSTA_MESSAGE = 'OK' ;    
        $this->TIME_CONNECTION = date(datetime::ISO8601);
        // consultem els paràmetres de configuració
        $vars[0] = 'lang='.$this->LANG;        
        $vars[1] = 'ver='.MSC_PLUGIN_VERSION ;
        
        $this->QueryGetTable(seccions::ADMIN, sub_seccions::INIWORDPRESS,$vars); 
        if ( $this->IS_DEGUG==TRUE){
            //$message = 'Ini Connection API ('. $this->TIME_CONNECTION .')' ;            
        }        
    }
    
            //Funci� finalitza
    function __destruct() {
        if ( $this->IS_DEGUG==TRUE){
            //$message = 'End connection API ('. $this->TIME_CONNECTION .' / '.date(datetime::ISO8601).')' ;            
       }
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
        
        $xml = new DOMDocument();        
        if ($xml->load($this->URL_QUERY_API) == FALSE){                              
            $my_message = $this->URL_QUERY_API ;
            show_msc_message( $my_message,message_type::DANGER);            
            $this->RESPOSTA_STATUS = 'KO';
            $this->RESPOSTA_CODE = SERVER_ERROR_NO_DEF;
            $this->RESPOSTA_MESSAGE = 'Error XML';
            $this->RESPOSTA_ROWS = 0;        
            return;
        }elseif ( $this->IS_DEGUG==TRUE){
             if ($MSGonJS==TRUE){
                $this->RESPOSTA_MESSAGE = $this->URL_QUERY_API ;
            }else{
                show_msc_message( $this->URL_QUERY_API ,message_type::INFO);            
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
                    $list_general=  $this->xml_to_array($xml);
                    return $list_general['msc-soft']['results'];                                            
                    break;                            
            }
        }
    }
    
    function get_params($xml){
        // establim variables               
        $counter = 0;
        $params = $xml->getElementsByTagName('param');
        foreach ($params as $param) {                                
            $value = utf8_decode($param ->textContent);             
            $name =  $param ->getAttribute('NAME');
            $id = $param ->getAttribute('ID');            
            switch ($id){
                case params::paramNomClient:  $this->NomEmissora  = $value; break;
                case params::paramUrlStreaming:  $this->URLStreaming  = $value ; break;
                case params::paramProgramacioDefecte:  $this->ProgramacioDefecte  = $value ;break;
                case params::paramFaceBooK:  $this->URL_FaceBook  = $value ; break;
                case params::paramTwitter:  $this->USER_Twitter  = $value ; break;
                case params::paramTwitterKey:  $this->KEY_Twitter  = $value ; break;
                //case 0:$this->MSC_KEY  = $value ; break;                
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

