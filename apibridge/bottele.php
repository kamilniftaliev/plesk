<?php 
date_default_timezone_set('asia/jakarta');
include 'konak.php';
define('BOT_TOKEN', 'bot17694430395:AAF3dNoD07QIcUqony9ti_CPz7i62w32qAE');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
require_once './config/config.php';
$content    = file_get_contents("php://input");
$update     = json_decode($content, true);
$chatID     = $update["message"]["chat"]["id"];
$message    = $update["message"]["text"];
$reply = "";

$arraycmd =  explode(' ',$message);
$cmd = $arraycmd[0];

switch ($cmd) { 
    
    case "/resetflash":
        $limitedl = 0;
        $limitfdl = 0;
        $limitfrp = 0;
        
        $pagelimit = 50;
        $page = 1;
        $db = getDbInstance();
        $select = array('limitedl','limitfrp','limitfdl','id');
        $db->where('status', 'ON');
        $db->pageLimit = $pagelimit;

        $rows = $db->arraybuilder()->paginate('server', $page, $select);
        $reply  = "";
        foreach ($rows as $row): 
            $limitedl = $row['limitedl'];

            $id = $row['id'];
            
            $data_to_update['limitleftedl'] = $limitedl;
 
            $db = getDbInstance();
            $db->where('id',$id);
            $stat = $db->update('server', $data_to_update);
            if ($stat){
                $reply .= "Reset limit flash server " . $id . " Success\n";  

            } else {
                
                $reply .= "Reset limit flash server " . $id . " Failed\n";     
                
            }

            
            
        endforeach;

        break;
    case "/resetfrp":
        $limitedl = 0;
        $limitfdl = 0;
        $limitfrp = 0;
        
        $pagelimit = 50;
        $page = 1;
        $db = getDbInstance();
        $select = array('limitedl','limitfrp','limitfdl','id');
        $db->where('status', 'ON');
        $db->pageLimit = $pagelimit;

        $rows = $db->arraybuilder()->paginate('server', $page, $select);
        $reply  = "";
        foreach ($rows as $row): 
       
            $limitfrp = $row['limitfrp'];
            $limitfdl = $row['limitfdl'];
            $id = $row['id'];
            
         
            $data_to_update['limitleftfrp'] = $limitfrp;
            $data_to_update['limitleftfdl'] = $limitfdl;
            $db = getDbInstance();
            $db->where('id',$id);
            $stat = $db->update('server', $data_to_update);
            if ($stat){
                $reply .= "Reset limit Frp server " . $id . " Success\n";  

            } else {
                
                $reply .= "Reset limit Frp server " . $id . " Failed\n";     
                
            }

            
            
        endforeach;

        break;
    case "/status": 
         $arcount = count($arraycmd);
        if (!$arcount >= 3 ){
           $reply = "Cmd Must /tool toolname on or off";  
           break;
        } 
        $username = $arraycmd[1];
        $yesno = $arraycmd[2];
        $where = "username='$username' ";
        $db = getDbInstance();
        $db->where($where);
        $jumlah = $db->getValue ("user", "count(*)");
        if($jumlah > 1){
             $reply = "Username More One Pelase Check"; 
             break;
        }
       if(!$jumlah == 1){
             $reply = "Username Nof Exist "; 
             break;
        }
        $yesno =  strtolower($yesno);
        $onoff = "yes";
        if($yesno != "on" ){
       
              if($yesno != "off"){
                        
          $reply = "3rd CMD is on / off"  ;
          break;
              } 
      
        }
        $statusnya = "";
        if($yesno == "on" ){
            $onoff = "yes";
            $statusnya = "enable";
        } else {
            $onoff = "no";
            $statusnya = "disable";
            
        }
        
        $data_to_update['statuson'] = $onoff;
        $db = getDbInstance();
        $db->where('username',$username);
        $stat = $db->update('user', $data_to_update);
        if($stat){
            $reply = "User " .$username . " status [" . $statusnya ."]" ; 
        }
        break;
        
        
        
    case "/backupflash": 
        $arcount = count($arraycmd);
        if (!$arcount >= 2 ){
           $reply = "Cmd Must /payall username";  
           break;
        } 
        $cmds = $arraycmd[1];
        if($cmds != "ON" ){
             if($cmds != "OFF" ){
                $reply = "cmd is ON/OFF" ; 
                break;
                 
             }

        } 
        $data_to_update['status'] = $cmds;
         
        $db = getDbInstance();
        $db->where('id',1);
        $stat = $db->update('backups', $data_to_update);
        
        $db = getDbInstance();
        $db->where('id',28);
        $stat = $db->update('server', $data_to_update);
        if($stat){
            $reply = "Sucess Set Backup SERVER FLASH " . $cmds ; 
        }
        break;
        
    case "/backupfrp": 
        $arcount = count($arraycmd);
        if (!$arcount >= 2 ){
           $reply = "Cmd Must /payall username";  
           break;
        } 
        $cmds = $arraycmd[1];
        if($cmds != "ON" ){
             if($cmds != "OFF" ){
                $reply = "cmd is ON/OFF" ; 
                break;
                 
             }

        } 
        $data_to_update['status'] = $cmds;
         
        $db = getDbInstance();
        $db->where('id',1);
        $stat = $db->update('backupsfrp', $data_to_update);
        
        $db = getDbInstance();
        $db->where('id',28);
        $stat = $db->update('server', $data_to_update);
        if($stat){
            $reply = "Sucess Set Backup SERVER FRP " . $cmds ; 
        }
        break;
        


                
    case "/payall":    
        $arcount = count($arraycmd);
        if (!$arcount >= 2 ){
           $reply = "Cmd Must /payall username";  
           break;
        } 
        $username = $arraycmd[1];
  
              
      
        $db = getDbInstance();
        $db->where("resellername",$username)->where("ispay",0);
        $jumlah = $db->getValue ("penjualancredit", "count(*)");
        if($jumlah == 0){
             $reply = "Reseller " .$username . " Don't have Unpaid Credit" ; 
             break;
        }
 
        

        $data_to_update['ispay'] = 1;
        $db = getDbInstance();
        $db->where('resellername',$username);
        $stat = $db->update('penjualancredit', $data_to_update);
        if($stat){
            $reply = "Reseller " .$username . " Unpaid Credit to Paid Success" ; 
        }
        break;
        
    case "/cekuser":
        $pagelimit = 500;
        $totalcredit = 0;
        $page = 1;
        $db = getDbInstance();
        $select = array('username','credit','id');
        $where = "credit>4";
        
        $db->where($where);
        $db->pageLimit = $pagelimit;
        $db->orderBy("credit", "desc");
        $rows = $db->arraybuilder()->paginate('user', $page, $select);
        $reply  = "";
        foreach ($rows as $row): 
            $user = $row['username']; //catherinerafael 
            if($user == "tamvah"  || $user == "mi-server" || $user == "multitool" || $user == "iv23" || $user == "cftools" || $user == "catherinerafael"){
                 $credit = 0;
            } else {
                $credit = $row['credit']; 
            }
           
            $totalcredit = $totalcredit + $credit;
            $id = $row['id'];
            $reply .= $user . ' Credit '  . $credit . " \n";  
        //   telebot($reply);

        endforeach;
        $reply .= "\n";
        $reply .= "TOTAL CREDIT " . $totalcredit;
        break;
        
        
      case "/cektotal":
        $pagelimit = 500;
        $totalcredit = 0;
        $page = 1;
        $db = getDbInstance();
        $select = array('username','credit','id');
        $where = "credit>0";
        
        $db->where($where);
        $db->pageLimit = $pagelimit;
        $db->orderBy("credit", "desc");
        $rows = $db->arraybuilder()->paginate('user', $page, $select);
        $reply  = "";
        foreach ($rows as $row): 
            $user = $row['username']; //catherinerafael 
            if($user == "tamvah"  || $user == "mi-server" || $user == "multitool" ||  $user == "Atillatool" || $user == "iv23" || $user == "cftools" || $user == "catherinerafael"){
                 $credit = 0;
            } else {
                $credit = $row['credit']; 
            }
           
            $totalcredit = $totalcredit + $credit;
            $id = $row['id'];
           // $reply .= $user . ' Credit '  . $credit . " \n";  
        //   telebot($reply);

        endforeach;
      //  $reply .= "\n";
        $reply .= "TOTAL CREDIT " . $totalcredit;
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
        
    case "/cekubl":    
           
        $pagelimit = 50;
        $page = 1;
        $db = getDbInstance();
        $select = array('limitleftubl','id');
        $db->where('status', 'ON');
        $db->pageLimit = $pagelimit;

        $rows = $db->arraybuilder()->paginate('server', $page, $select);
        $reply  = "";
        foreach ($rows as $row): 
            $limitfdl = $row['limitleftubl'];
            $id = $row['id'];
            if($id == 29) {
              
              if($limitfdl == 0 ){
                  $reply = "UBL USED";     
              }else{
                   $reply = "UBL UNUSED";     
                  
              }
              
            }

           

          

            
            
        endforeach;
        break;
    case "/cekflash":
           
        $pagelimit = 50;
        $page = 1;
        $db = getDbInstance();
        $select = array('limitleftedl','limitleftfrp','limitleftfdl','id');
        $db->where('status', 'ON');
        $db->pageLimit = $pagelimit;

        $rows = $db->arraybuilder()->paginate('server', $page, $select);
        $reply  = "";
        foreach ($rows as $row): 
            $limitedl = $row['limitleftedl'];
            $limitfrp = $row['limitleftfrp'];
            $limitfdl = $row['limitleftfdl'];
            $id = $row['id'];
            $reply .= "limit server " . $id . ' flash '  . $limitedl . " \n";  

          

            
            
        endforeach;

        break;
        
        
    case "/cekfrp":
           
        $pagelimit = 50;
        $page = 1;
        $db = getDbInstance();
        $select = array('limitleftedl','limitleftfrp','limitleftfdl','id');
        $db->where('status', 'ON');
        $db->pageLimit = $pagelimit;

        $rows = $db->arraybuilder()->paginate('server', $page, $select);
        $reply  = "";
        foreach ($rows as $row): 
            $limitedl = $row['limitleftedl'];
            $limitfrp = $row['limitleftfrp'];
            $limitfdl = $row['limitleftfdl'];
            $id = $row['id'];
            $reply .= "limit server " . $id . ' FRP '  . $limitfrp . " \n";  

          

            
            
        endforeach;

        break;
        
        
         
    
    case "/cekcredit":
        $arcount = count($arraycmd);
        if (!$arcount >= 2 ){
           $reply = "Cmd Must /cekcredit username";  
           break;
        } 
        $username = $arraycmd[1];
  
              
        $where = "username='$username' ";
        $db = getDbInstance();
        $db->where($where);
        $jumlah = $db->getValue ("user", "count(*)");
        if($jumlah > 1){
             $reply = "Username More One "; 
             break;
        }
       if(!$jumlah == 1){
             $reply = "Username Nof Exist "; 
             break;
        }
       
        $db = getDbInstance();
        $db->where('username', $username);
        $row = $db->getOne('user');
        $reply = $username . " Credits ".  $row['credit'] ;
        break;
    case "/setfrp":
         $arcount = count($arraycmd);
        if (!$arcount >= 3 ){
           $reply = "Cmd Must /setfrp username yes or no";  
           break;
        } 
        $username = $arraycmd[1];
        $yesno = $arraycmd[2];
        $where = "username='$username' ";
        $db = getDbInstance();
        $db->where($where);
        $jumlah = $db->getValue ("user", "count(*)");
        if($jumlah > 1){
             $reply = "Username More One Pelase Check"; 
             break;
        }
       if(!$jumlah == 1){
             $reply = "Username Nof Exist "; 
             break;
        }
        $yesno =  strtolower($yesno);
        
        if($yesno != "on" ){
              if($yesno != "off"){
                        
          $reply = "3rd CMD is on / off"  ;
          break;
              }
      
        }
        
        
        $intfrp = 0;
        
        $actdeact = "";
        if($yesno=="on"){ $intfrp = 1 ; $actdeact = "ACTIVATION"; }
        if($yesno=="off"){ $intfrp = 0 ; $actdeact = "DEACTIVATION"; }
        $data_to_update['frp'] = $intfrp;
        $db = getDbInstance();
        $db->where('username',$username);
        $stat = $db->update('user', $data_to_update);
        
        if($stat){ $reply = $actdeact ." FRP PACKET " .$username .  " SUCCESS" ; } else { $reply =  $actdeact ." FRP PACKET " .$username  . " FAILED" ;}
       
        break;
    case "/setpaketmtk":
         $arcount = count($arraycmd);
        if (!$arcount >= 3 ){
           $reply = "Cmd Must / setpaketmtk username jumlah paket";  
           break;
        } 
        $username = $arraycmd[1];
        $jumlahpaket = $arraycmd[2];
        
  
     
        if ( filter_var($jumlahpaket, FILTER_VALIDATE_INT) === false ) {
            $reply = "Make Sure Input Total is int / or on off" ;
            break;
        }  
    
        
        $where = "username='$username' ";
        $db = getDbInstance();
        $db->where($where);
        $jumlah = $db->getValue ("user", "count(*)");
        if($jumlah > 1){
             $reply = "Username More One Pelase Check"; 
             break;
        }
       if(!$jumlah == 1){
             $reply = "Username Nof Exist "; 
             break;
        }
       
        
   
        
        
        $db = getDbInstance();
        $db->where('username', $username);
        $row = $db->getOne('user');
        $isActive = $row['v6'] ;
        $jumlahnya = $row['jumlahv6'] ;
        $tanggalya= $row['activev6'] ;
        

 
        if($isActive == 1){  
            $reply = "Allready Active Packet Mtk " . $jumlahnya . " Devices \n" ;
            $reply .= "valid Start " . $tanggalya ;
            
            break;
        }
        
        
        
        
        $intfrp = 1;
        $actdeact = "";
        $sekarang = date('Y-m-d H:i:s');
        
        $data_to_update['v6'] = $intfrp;
        $data_to_update['jumlahv6'] = $jumlahpaket;
        $data_to_update['activev6'] = $sekarang;
        $db = getDbInstance();
        $db->where('username',$username);
        $stat = $db->update('user', $data_to_update);
        
        if($stat){ $reply = $actdeact ."MTK 6 PACKET " .$username . " " .$jumlahpaket .  " Devices  SUCCESS" ; } else { $reply =  $actdeact ." MTK PACKET " .$username  . " FAILED" ;}
       
        break;        
    case "/paketmtk":
        $arcount = count($arraycmd);
        if (!$arcount >= 3 ){
           $reply = "Cmd Must / setpaketmtk username jumlah paket";  
           break;
        } 
        $username = $arraycmd[1];
        $yesno = $arraycmd[2];
        
        $yesno =  strtolower($yesno);
        
        if($yesno != "on" ){
              if($yesno != "off"){
                        
                $reply = "3rd CMD is on / off"  ;
                break;
              }
      
        }


        
        $where = "username='$username' ";
        $db = getDbInstance();
        $db->where($where);
        $jumlah = $db->getValue ("user", "count(*)");
        if($jumlah > 1){
             $reply = "Username More One Pelase Check"; 
             break;
        }
       if(!$jumlah == 1){
             $reply = "Username Nof Exist "; 
             break;
        }
       
        
   
        
        
        $db = getDbInstance();
        $db->where('username', $username);
        $row = $db->getOne('user');
        $isActive = $row['v6'] ;
        $jumlahnya = $row['jumlahv6'] ;
        $tanggalya= $row['activev6'] ;
        

 

        $status = "";
         if($yesno == "on" ){ 
                $intfrp = 1;
                $status = "ReActivated ";
         }
        
            
         if($yesno == "off" ){ 
                $intfrp = 0;
                $status = "DeActivated ";
         }
        
        
        
        
        $actdeact = "";

        
        $data_to_update['v6'] = $intfrp;

        $db = getDbInstance();
        $db->where('username',$username);
        $stat = $db->update('user', $data_to_update);
        
        if($stat){ $reply = $actdeact ."MTK 6 PACKET " .$status. " Manually SUCCESS" ; } else { $reply =  $actdeact ." MTK PACKET " .$username  . " FAILED" ;}
       
        break;        
        
    case "/ubl":
       $arcount = count($arraycmd);
        if (!$arcount >= 2 ){
           $reply = "Cmd Must /ubl on/off";  
           break;
        } 
        $cmds = $arraycmd[1];

        $setlimit = 0;
        if($cmds == "ON" || $cmds == "on"){ 
           $setlimit = 1; 
            
        } elseif ($cmds == "OFF" || $cmds == "off") {
          $setlimit = 0; 
        } else {
            
                $reply = "cmd is ON/OFF" ; 
                break;
        }
        
    
        $data_to_update['limitleftubl'] = $setlimit;
         
        $db = getDbInstance();
        $db->where('id',29);
        $stat = $db->update('server', $data_to_update);
        if($stat){
            $reply = "Sucess SET UBL " . $cmds ; 
        }


        break;  
        
    case "/server":
       $arcount = count($arraycmd);
        if (!$arcount >= 2 ){
           $reply = "Cmd Must /server 1/2";  
           break;
        } 
        $cmds = $arraycmd[1];

        $setlimit = 0;
        if($cmds == "1" ){ 
           $setserver = 16; 
            
        } elseif ($cmds == "2" ) {
          $setserver = 17; 
        } else {
            
                $reply = "cmd is 1/2" ; 
                break;
        }
        
    
        $data_to_update['serverid'] = $setserver;
         
        $db = getDbInstance();
        $db->where('id',1);
        $stat = $db->update('active', $data_to_update);
        if($stat){
            $reply = "Sucess SET server " . $setserver  . " Unlimited\n" ; 
        }



         
            $data_to_updates['limitleftedl'] = '1000';
            $data_to_updates['limitedl'] = '1000';
            $db = getDbInstance();
            $db->where('id',$setserver);
            $stat = $db->update('server', $data_to_updates);
            if ($stat){
                $reply .= "Reset limit Flash server " . $setserver . " Success\n";  

            } else {
                
                $reply .= "Reset limit Flash server " . $setserver . " Failed\n";     
                
            }

        break;            
        
    case "/savemode":
       $arcount = count($arraycmd);
        if (!$arcount >= 2 ){
           $reply = "Cmd Must /payall username";  
           break;
        } 
        $cmds = $arraycmd[1];
        if($cmds == "ON" || $cmds == "on"){ 
           $cmds = "ON"; 
            
        } elseif ($cmds == "OFF" || $cmds == "off") {
             $cmds = "OFF"; 
        } else {
            
                $reply = "cmd is ON/OFF" ; 
                break;
        }
        $data_to_update['status'] = $cmds;
         
        $db = getDbInstance();
        $db->where('id',1);
        $stat = $db->update('savemode', $data_to_update);
        if($stat){
            $reply = "Sucess set Savemode  " . $cmds ; 
        }


        break;
                
    case "/miserver":
       $arcount = count($arraycmd);
        if (!$arcount >= 2 ){
           $reply = "Cmd Must /payall username";  
           break;
        } 
        $cmds = $arraycmd[1];
        if($cmds != "ON" ){
             if($cmds != "OFF" ){
                $reply = "cmd is ON/OFF" ; 
                break;
                 
             }

        } 
        $data_to_update['status'] = $cmds;
         
        $db = getDbInstance();
        $db->where('id',1);
        $stat = $db->update('miserver', $data_to_update);
        if($stat){
            $reply = "Sucess SET F-KEY service To " . $cmds ; 
        }


        break;        
    case "/transfer":
       $arcount = count($arraycmd);
        if (!$arcount >= 5 ){
           $reply = "Cmd Must  username credit";  
           break;
        } 
        $username = $arraycmd[1];
        $amount = $arraycmd[2];
        $price = $arraycmd[3];
        
        if($price == ""){
                $reply = "Make Sure price Is Inserted " ;
             break;
        }
        
        $db = getDbInstance();
        $db->where("username","$username");
        $jumlah = $db->getValue ("user", "count(*)");
        if($jumlah > 1){
             $reply = "Username More One Pelase Check"; 
             break;
        }
       if(!$jumlah == 1){
             $reply = "Username Nof Exist "; 
             break;
        }
   
        if ( filter_var($amount, FILTER_VALIDATE_INT) === false ) {
           $reply = "Make Sure Input Credit is int" ;
             break;
        }
        if ( filter_var($price, FILTER_VALIDATE_INT) === false ) {
           $reply = "Make Sure Input Price is int" ;
             break;
        }
        $db = getDbInstance();
        $db->where('username', $username);
        $row = $db->getOne('user');
        $credit = $row['credit'] ;
        $total = $amount + $credit;
        
        $data_to_update['credit'] = $total;
        $db = getDbInstance();
        $db->where('username',$username);
        $stat = $db->update('user', $data_to_update);
        $reply = "Transfer Credit To " . $username . " $amount" . "\n";
        $reply .= "Last Credit " . $username . " $credit" . "\n";
       
        
        $db = getDbInstance();
        $db->where('username', $username);
        $row = $db->getOne('user');
        $credit = $row['credit'] ;
        $reply .= "Remain Credit " . $username . " $credit" . "\n";
        $reply .= "With Price " . $price  ;
        
        
        
		$sqls = "INSERT INTO penjualancredit (jumlah,email,resellerid,resellername,price,ispay) VALUES ('$amount','$username','1','tamvah','$price','1')";
				if(mysqli_query($koneksi, $sqls))
				{ }else{	}
  
        
        
        break;
    
    case "/cekpenjualan": 
         $arcount = count($arraycmd);
        if (!$arcount >= 2 ){
           $reply = "Cmd Must /cekpenjualan username";  
           break;
        } 
        
        
        $pagelimit = 200;
        $resellername =  $arraycmd[1];
        $db = getDbInstance();
        $db->where('resellername', $resellername)->where('ispay', '0');
        $jumlah = $db->getValue ("penjualancredit", "count(*)");

       if($jumlah == 0){
             $reply = "Username Not Have Unpaid Credit or Reseller Not Valid"; 
             break;
        }
        
        
        $page = 1;
        $total = 0;

        $db = getDbInstance();
        $select = array('resellerid','jumlah');
        $db->where('resellername', $resellername)->where('ispay', '0');
        $db->pageLimit = $pagelimit;

        $rows = $db->arraybuilder()->paginate('penjualancredit', $page, $select);

        foreach ($rows as $row): 
            $total += $row['jumlah'];    
        endforeach;
        $reply = "Total Unpaid  Reseller " . $resellername . " ". $total . " Credits ";
        break;
    case "/cek":   
        
    $reply = "AVALIBALE CMD\n";
    $reply .= "/cekcredit username\n";
    $reply .= "/cekpenjualan resellername\n";
    $reply .= "/transfer username amount\n";
    $reply .= "/setfrp username on/off\n";
    $reply .= "/setpaketmtk username jumlahpaket\n";
    $reply .= "/paketmtk username on/off untuk stop/lanjut\n";
    $reply .= "/ubl on/off\n";
    $reply .= "/status username on/off\n";
    $reply .= "/payall username[set paid semua penjualan reseller name  ]\n";
    $reply .= "/resetflash[mengembalikan limit Flash]\n";
    $reply .= "/resetfrp[mengembalikan limit FRP]\n";
    $reply .= "/cekuser[cek user Credits]\n";
    $reply .= "/cekflash[ceklimit flash]\n";
    $reply .= "/cekfrp[ceklimit FRP]\n";
    $reply .= "/cekprice[cek harga]\n";
    $reply .= "/cektotal [akumulasi Total Credits]\n";
    
        
        
  default:


}









telebot($reply);


//checkJSON($chatID,$update);
function checkJSON($chatID,$update){

    $myFile = "log.txt";
    $updateArray = print_r($update,TRUE);
    $fh = fopen($myFile, 'a') or die("can't open file");
    
    fwrite($fh, $updateArray."nn");
    fclose($fh);
}

function telebot($message) {

$url = 'https://api.telegram.org/bot17694430395:AAF3dNoD07QIcUqony9ti_CPz7i62w32qAE/sendMessage?chat_id=-11002383474691&text='.urlencode($message)  ;
$result = file_get_contents($url, false, $context);
if ($result === false) {
    /* Handle error */
}
return $result;
}


