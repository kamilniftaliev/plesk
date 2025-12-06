<?php 
define('BOT_TOKEN', 'bot18152473637:AAFhS9QAbvcYgHBpfZrFeh4YxumNx1Vl4kQ');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
require_once './config/config.php';
$content    = file_get_contents("php://input");
$update     = json_decode($content, true);
$chatID     = $update["message"]["chat"]["id"];
$message    = $update["message"]["text"];
$reply = "";
$limitfrp = 0;
$limitfdl = 0;
$limitqcomMtknew = 0;
$limitmtk6 = "unlimited";
$arraycmd =  explode(' ',$message);
$cmd = $arraycmd[0];

switch ($cmd) { 
    
   
        
    case "/ceklimit":
           
        $pagelimit = 50;
        $page = 1;
        $db = getDbInstance();
        $select = array('limitleftedl','limitleftfrp','limitleftfdl','id');
        
        $db->where('status', 'ON');
        $db->pageLimit = $pagelimit;

        $rows = $db->arraybuilder()->paginate('server', $page, $select);
        $reply  = "";
        foreach ($rows as $row): 
            $limitedl += $row['limitleftedl'];
            $limitfrp += $row['limitleftfrp'];
            $limitfdl += $row['limitleftfdl'];
       
            

          

            
            
        endforeach;
        
        $pagelimit = 50;
        $statusqcom = "";
        $page = 1;
        $db = getDbInstance();
        $select = array('status','id');
        
        $db->where('id', 1);
        $db->pageLimit = $pagelimit;

        $rows = $db->arraybuilder()->paginate('savemode', $page, $select);
        $reply  = "";
        foreach ($rows as $row): 
            $statusqcom = $row['status'];

       
        

          

            
            
        endforeach;
        $qcom = "ON";
        if($statusqcom== "ON") {
            
            $qcom = "OFF";
        }
        $frptatus = "OFF";
        if($limitfrp > 0){
            $frptatus = "ON";
            
        }
        $fdlstatus = "OFF";
        if($limitfdl > 0){
            $fdlstatus = "ON";
            
        }
      
        $reply .= "MTK 6 OLD STATUS [OFF] \n" ;
        $reply .= "MTK New / v5 / QCOM [OFF]\n" ; // [" . $qcom ."]\n";
        $reply .= "FRP STATUS [" . $frptatus."]\n";
        $reply .= "FDL STATUS [OFF]" ;// . $fdlstatus."]\n";
        break;
      
        
        
        
    case "/cekprice":
                $pagelimit = 200;
        $page = 1;
        $db = getDbInstance();
        $select = array('harga','servicename','id');
      //  $where = "credit>0";
        
      //  $db->where($where);
        $db->pageLimit = $pagelimit;
     //   $db->orderBy("credit", "desc");
        $rows = $db->arraybuilder()->paginate('price', $page, $select);
        $reply  = "";
        foreach ($rows as $row): 
            $servicename = $row['servicename'];
            $price = $row['harga'];
            $id = $row['id'];
            $reply .= 'Service ' . $servicename  . ' Price '  . $price . " \n";  

          

            
            
        endforeach;

        break;        

        
  default:


}


telebot($reply);


//checkJSON($chatID,$update);
function checkJSON($chatID,$update){

    $myFile = "logd.txt";
    $updateArray = print_r($update,TRUE);
    $fh = fopen($myFile, 'a') or die("can't open file");
    
    fwrite($fh, $updateArray."nn");
    fclose($fh);
}

function telebot($message) {

$url = 'https://api.telegram.org/bot18152473637:AAFhS9QAbvcYgHBpfZrFeh4YxumNx1Vl4kQ/sendMessage?chat_id=-11002474821511&text='.urlencode($message)  ;
$result = file_get_contents($url, false, $context);
if ($result === false) {
    /* Handle error */
}
return $result;
}


