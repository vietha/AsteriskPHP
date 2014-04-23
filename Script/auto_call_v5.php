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
use PAMI\Message\Event\NewstateEvent;
//class asterisk
class Asterisk
{
	protected $account;
	protected $channel;
	protected $state;
	function __construct(){
		$this->account = array(
				'host' => '192.168.11.150',
                                'scheme' => 'tcp://',
                                'port' => 5038,
                                'username' => 'admin',
                                'secret' => '123456',
                                'connect_timeout' => 30000,
                                'read_timeout' => 30000
				);

	}
}
//declare class auto_call using manage asterisk
class Auto_Call
{
	//attribute
	protected $hostname;
	protected $username;
	protected $password;
	protected $database;
	protected $table;
	protected $customer;
	protected $callid1;
	protected $callid2;
	protected $Asterisk;
	protected $PamiClient;
	//method
	public function init()
	{
		$this->hostname = 'localhost';
		$this->username = 'root';
		$this->password = '12345678@X';
		$this->database = 'asdb';
		$this->table 	= 'leads';
		$this->Get_Asterisk_Info();
			
	}
	public function Get_Customer($name,$phone,$email)
	{
		$this->customer = array(
			'name' => '',
			'phone'=> '',
			'email'=>''
		);
		$this->customer['name'] = $name;
		$this->customer['phone'] = $phone;
		$this->customer['email'] = $email;
       	}
	
	
	protected function Get_Asterisk_Info()
	{
		$this->asterisk = array(
		array(
			'callid' =>     '',	
			'option' =>	array(
        			'host' => '192.168.11.150',
        			'scheme' => 'tcp://',
        			'port' => 5038,
        			'username' => 'admin',
        			'secret' => '123456',
        			'connect_timeout' => 30000,
        			'read_timeout' => 30000
       				 ),
			'state' => 1
		),
		
		
		   array(
                        'callid' =>     '',
                        'option' =>     array(
                                'host' => '192.168.11.200',
                                'scheme' => 'tcp://',
                                'port' => 5038,
                                'username' => 'admin',
                                'secret' => '123456',
                                'connect_timeout' => 30000,
                                'read_timeout' => 30000
                                 ),
                        'state' => 2
                ),
		   array(
                        'callid' =>     '',
                        'option' =>     array(
                                'host' => '192.168.11.100',
                                'scheme' => 'tcp://',
                                'port' => 5038,
                                'username' => 'admin',
                                'secret' => '123456',
                                'connect_timeout' => 30000,
                                'read_timeout' => 30000
                                 ),
                        'state' => 3
                ),


			);

	}
	protected function Set_Phone_Temp($name,$channel,$state,$number)
	{
		try{
			$dbh = new PDO("mysql:host=$this->hostname;dbname=$this->database",$this->username,$this->password);
			echo "conenct PHONE";
			$sql = "insert into phone(Name,Number,Channel,State) values ('$name','$number','$channel','$state')";
			$count = $dbh->exec($sql);
			echo $count;
			$dbh = null;//close connect mysql
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	protected function Write_Status($state)
	{
		try{
			$dbh = new PDO("mysql:host=$this->hostname;dbname=$this->database",$this->username,$this->password);
			echo "connect success LEADS";
			$phone = $this->customer['phone'];
			$sql = "update $this->table set state=$state, status='NO ANSWER',last_call=NOW()  where phonenumber=$phone";
			$count = $dbh->exec($sql);
			$dbh = null;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	protected function Remote_Asterisk_1()
	{
		usleep(50);
		$phone = $this->customer['phone'];
		$channel           = "SIP/$phone@192.168.11.149";
		$context           = "amitest";
		$priority          = '1';
		$callerid          = $this->asterisk[0]['option']['host'];
		$exten             = '6060';
		$pamiClientOptions = $this->asterisk[0]['option'];
		$pamiClient        = new PamiClient($pamiClientOptions);
		//open connect to AMI
		$pamiClient->open();
		//init call
		$originateMsg = new OriginateAction($channel);
		$originateMsg->setContext($context);
		$originateMsg->setExtension($exten);
		$originateMsg->setCallerId($callerid);
		$originateMsg->setAsync('true');
		$response = $pamiClient->send($originateMsg);//make call
		//get event
		var_dump($response);
                if($response->isSuccess())
		{
			$pamiClient->registerEventListener(
                        	function (NewstateEvent $event)
                        	{//get callid
					var_dump($event);
					if($event->getChannel()!='')
					{
						$channel = $event->getChannel();
						$this->asterisk[0]['callid']=$channel;
						echo $channel;
						$this->Set_Phone_Temp($this->customer['name'],$channel,$this->asterisk[0]['state'],$this->customer['phone']);
						$this->Write_Status($this->asterisk[0]['state']);
						return true;
					}
				
                        	},
                        	function (EventMessage $event)
                        	{
                                	if($event instanceof  NewstateEvent)
                               		{
                                        	return true;
                                	}
                       		 }
                	);
		
			$running =true;
			while($running)
			{
				$pamiClient->process();
			 	if($this->asterisk[0]['callid']!='')
                                	return;

			}
		
			$pamiClient->close();
		}
		else//can not call number, may be phone is invalid 
		{
			
			$pamiClient->close();
			exit("Phone not valid");
		}		
	}
	


	 protected function Remote_Asterisk_2()
        {
                usleep(50);
		$phone 		   = $this->customer['phone'];
                $channel           = "SIP/$phone@192.168.11.149";
                $context           = "amitest";
                $priority          = '1';
                $callerid          = $this->asterisk[1]['option']['host'];
                $exten             = '6060';
                $pamiClientOptions = $this->asterisk[1]['option'];
                $pamiClient        = new PamiClient($pamiClientOptions);
                //open connect to AMI
                $pamiClient->open();
                //init call
                $originateMsg = new OriginateAction($channel);
                $originateMsg->setContext($context);
                $originateMsg->setExtension($exten);
                $originateMsg->setCallerId($callerid);
                $originateMsg->setAsync('true');
                $response = $pamiClient->send($originateMsg);//make call
                //check response
                if($response->isSuccess())
		{//if success get event
			$pamiClient->registerEventListener(
                        	function (NewstateEvent $event)
                        	{//get callid
                                	var_dump($event);
                               		if($event->getChannel()!='')
                                	{
                                        	$channel = $event->getChannel();
                                        	$this->asterisk[1]['callid'] = $channel;
                                      		$this->Set_Phone_Temp($this->customer['name'],$channel,$this->asterisk[1]['state'],$this->customer['phone']);
						$this->Write_Status($this->asterisk[1]['state']);
                                        	return true;
                                	}

                        	},
                        	function (EventMessage $event)
                        	{
                                	if($event instanceof  NewstateEvent)
                                	{
                                        	return true;
                                	}
                        	}
                	);
                	$running =true;
                	while($running)
                	{
                        	$pamiClient->process();
                        	if($this->asterisk[1]['callid']!='')
					return;
                	}

                	$pamiClient->close();
		}
		else
		{
			$pamiClient->close();
			exit("Invalid Phone");
		}
	}
	public function voicemail($phone)
	{
			
		//make call1 and call2
		$pid = pcntl_fork();
		if ($pid == -1) {
     			die('could not fork');
		} else if ($pid) {
			$this->Remote_Asterisk_1();
			
     		// we are the parent
     		pcntl_wait($status); //Protect against Zombie children
		} else {
 		//child
			$this->Remote_Asterisk_2();
			exit();
		}	
		$channel           = "SIP/$phone@192.168.11.149";
                $context           = "amitest";
                $priority          = 1;
                $callerid          = $this->asterisk[2]['option']['host'];
                $exten             = 6060;
                $pamiClientOptions = $this->asterisk[2]['option'];
                $pamiClient        = new PamiClient($pamiClientOptions);
                //open connect to AMI
                $pamiClient->open();
		
                //init call
                $originateMsg = new OriginateAction($channel);
                $originateMsg->setContext($context);
                $originateMsg->setExtension($exten);
                $originateMsg->setCallerId($callerid);
                $originateMsg->setAsync('true');
		//$originateMsg->setTimeout(10000);
                $pamiClient->send($originateMsg);//make call
		//Hangup call1 and call 2
		$this->Get_Channel();
		sleep(10);
		$this->Hangup_Call($this->asterisk[0]);
		$this->Hangup_Call($this->asterisk[1]);
		$pamiClient->close();
		usleep(5000);
		$this->Reset_Temp_Data();
		
	}
	protected function Reset_Temp_Data()
	{
		 try{
                        $dbh = new PDO("mysql:host=$this->hostname;dbname=$this->database",$this->username,$this->password);
                        echo "connect Reset";
			$phone = $this->customer['phone'];
			$sql = "delete from phone where `Number`=$phone";
                        $dbh->query($sql);
                        $dbh = null;
                }
                catch(PDOException $e)
                {
                        echo $e->getMessage();
                }

	}
	protected function Get_Channel()
	{
		try{
			$dbh = new PDO("mysql:host=$this->hostname;dbname=$this->database",$this->username,$this->password);
			echo "Channel";
			$sql = "SELECT Channel,State from phone";
			foreach ($dbh->query($sql) as $row)
			{
				if($this->asterisk[0]['state'] == $row['State'])
					$this->asterisk[0]['callid'] = $row['Channel'];
				if($this->asterisk[1]['state'] == $row['State'])
					$this->asterisk[1]['callid'] = $row['Channel'];
			}
			$dbh = null;			 
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	protected function Hangup_Call($asterisk)
	{
		$PamiOption = $asterisk['option'];
	        $PamiClient = new PamiClient ($PamiOption);
        	$PamiClient->open();
		//$channel    = "SIP/$phone@192.168.11.149";
        	$PamiClient->send(new HangupAction($asterisk['callid']));
        	$PamiClient->close();
		
	}
	
	
}

	$a =new Auto_Call();
	$a->init();
	$a->Get_Customer('Phuc','5000','phuccntt1990@gmail.com');
	$a->voicemail(5000);    	


                                          
				
	
         


?>
