<?php
//global variable
require_once 'PAMI/Autoloader/Autoloader.php'; // Include PAMI autoloader.
\PAMI\Autoloader\Autoloader::register(); // Call autoloader register for PAMI autoloader.
use PAMI\Client\Impl\ClientImpl;
set_include_path(get_include_path() . PATH_SEPARATOR . "/usr/share/php/log4php");

//main

use PAMI\Client\Impl\ClientImpl as PamiClient;
use PAMI\Message\Event\EventMessage;
use PAMI\Listener\IEventListener;
use PAMI\Message\Event\HangupEvent;
use PAMI\Message\Event\DialEvent;
use PAMI\Message\Event\VarSetEvent;
use PAMI\Message\Action\OriginateAction;
use PAMI\Message\Event\OriginateResponseEvent;
use PAMI\Message\Action\HangupAction;
//init varibal
$host = "127.0.0.1";//host mysql server
$user = "root";//user mysql
$pass = "12345678@X"; //pass mysql
$database = "asdb"; //database mysql
$table = "leads";  //table mysql
//end init variable
$phone = array();
$number = "";
$state = "1";
$callid1="";
$callid2="";
$phone = Connect_database($GLOBALS['host'],$GLOBALS['user'],$GLOBALS['pass'],$GLOBALS['database'],$table);
$asterisk1 = array(
	'host' => '192.168.11.150',
	'scheme' => 'tcp://',
    	'port' => 5038,
    	'username' => 'admin',
    	'secret' => '123456',
    	'connect_timeout' => 30000,
    	'read_timeout' => 30000
	);
$asterisk2 = array(
        'host' => '192.168.11.200',
        'scheme' => 'tcp://',
        'port' => 5038,
        'username' => 'admin',
        'secret' => '123456',        
	'connect_timeout' => 30000,
        'read_timeout' => 30000,
        );
$asterisk3 = array(
        'host' => '192.168.11.100',
        'scheme' => 'tcp://',
        'port' => 5038,
        'username' => 'admin',
        'secret' => '123456',
        'connect_timeout' => 30000,
        'read_timeout' => 30000,
        );
$number = $phone[0];
Remote_Asterisk ($asterisk1,$number,$state);
$state=2;
Remote_Asterisk ($asterisk2,$number,$state);
$state=3;
Remote_Asterisk($asterisk3,$number,$state);


















function Remote_Asterisk($asterisk,$number,$state)
{
	if($state!=3)
		usleep(2000);
	//$GLOBALS['running']='true';
		
	
	$channel = 'SIP/'.$number.'@192.168.11.149'; 
	$context = 'amitest';
	$priority = '1';
	$callerid = $asterisk['host'];
	$exten = '6060';
	$pamiClientOptions = $asterisk;
	$pamiClient = new PamiClient($pamiClientOptions);
	//open connect to AMI
	$pamiClient->open();
	//init call	
	$originateMsg = new OriginateAction($channel);
	$originateMsg->setContext($context);
	$originateMsg->setExtension($exten);
	$originateMsg->setCallerId($callerid);
	$originateMsg->setAsync('yes');
	$response = $pamiClient->send($originateMsg);
	//check response call
	
		$pamiClient->registerEventListener(
			function (OriginateResponseEvent $event)
			{
				$Active=1;	
				switch($event->getReason())
				{
					case 0://no extension or number
						
						break;
					case 1://no answer or timeout
						 if($GLOBALS['state']===3)
        		                                $Active=0;
			Write_Database($GLOBALS['host'],$GLOBALS['user'],$GLOBALS['pass'],$GLOBALS['database'],$GLOBALS['table'],$GLOBALS['state'],'TIMEOUT',$GLOBALS['number'],$Active);
						break;
					case 4://answer
						
						switch($GLOBALS['state'])
                                		 {//get channel call1 and call2
                                        	case 1:
                                               		$GLOBALS['callid1']=$event->getChannel();
                                               		break;
                                        	case 2:
                                                	$GLOBALS['callid2']=$event->getChannel();
                                                	break;
	                                 	}
				Write_Database($GLOBALS['host'],$GLOBALS['user'],$GLOBALS['pass'],$GLOBALS['database'],$GLOBALS['table'],$GLOBALS['state'],'ANSWER',$GLOBALS['number'],$Active);
						break;
					case 5://busy
					 if($GLOBALS['state']===3)
	                                        $Active=0;
				Write_Database($GLOBALS['host'],$GLOBALS['user'],$GLOBALS['pass'],$GLOBALS['database'],$GLOBALS['table'],$GLOBALS['state'],'BUSY',$GLOBALS['number'],$Active);
						break;
					case 8://congested or not available
						  if($GLOBALS['state']==3)
                				        $Active=0;
        	         Write_Database($GLOBALS['host'],$GLOBALS['user'],$GLOBALS['pass'],$GLOBALS['database'],$GLOBALS['table'],$GLOBALS['state'],'OFFLINE',$GLOBALS['number'],$Active);
						break;
				}
			},
			function (EventMessage $event)
			{
				if($event instanceof  OriginateResponseEvent)
				{
					return true;
				}
			}
			
		);
	
	//$response = $pamiClient->send($originateMsg);

	//close connect 
	$running=true;
	while($running)
	{
		
		$pamiClient->process();
		usleep(2000);
		switch($GLOBALS['state'])
		{
			case 1:
				if(!empty($GLOBALS['callid1']))
					$running = false;
				break;	
			case 2:
				if(!empty($GLOBALS['callid2']))
					$running = false;
				break;
			case 3://terminal call1 and call2
				 TerminalCall($GLOBALS['asterisk1'],$GLOBALS['callid1']);
				TerminalCall($GLOBALS['asterisk2'],$GLOBALS['callid2']);	
				$running = false;
				usleep(100000);
				break;
					
		}

	}
	$pamiClient->close();
}
function TerminalCall ($asterisk,$channel)
{
	$PamiOption = $asterisk;
	$PamiClient = new PamiClient ($PamiOption);
	$PamiClient->open();
	$PamiClient->send(new HangupAction($channel));
	$PamiClient->close();	
}
function Write_Database($host,$user,$pass,$database,$table,$state,$status,$number,$active)
{
	
         $sql= "update $table set `state`=$state, `status`='$status', `is_active`='$active', `last_call`=NOW() where `phonenumber`=$number" ;
	

        echo $sql;

        $link = mysql_connect($host,$user,$pass,$database);
        if(!$link){
                die('could not connect:' .mysql_error());
        }
        mysql_select_db($database,$link);
        $retval = mysql_query( $sql, $link );
        if(! $retval )
        {
                die('Could not update data: ' . mysql_error());
        }
        echo "Updated data successfully\n";

        mysql_close($link);
}








	
function Connect_Database($host,$user,$pass,$database,$table)
{
	$link = mysql_connect($host,$user,$pass,$database);
	if(!$link){
        	die('could not connect:' .mysql_error());
	}
	mysql_select_db($database,$link);
	$sql = 'SELECT * FROM '.$table;
	$result = mysql_query($sql,$link) or die(mysql_error());
	$content = array();
	$i =0;
	while($row = mysql_fetch_assoc($result))
	{
		if($row['phonenumber']==5000)
		//if($row['Active']=='NO')
		
		{
			echo $row['phonenumber'];
			$content[$i] = $row['phonenumber']; 
			$i++;
			
		}
	}
	
	mysql_close();
	return $content;
	
}


?>
