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
			'state' => 1,
			'status'=>''
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
                        'state' => 2,
			'status'=>''
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
                        'state' => 3,
			'status'=>''
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
	
	protected function Update_Mysql($sql)
	{
		try{
			$dbh = new PDO("mysql:host=$this->hostname;dbname=$this->database",$this->username,$this->password);
			echo "connect success";
			$count = $dbh->exec($sql);
			echo $count;
			$dbh = null;
		}
		catch(PDOException $e)
		{
			$e->getMessage();
		}
	}
	
	protected function Remote_Asterisk_1()
	{
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
                        	function (VarSetEvent $event)
                        	{//get callid
					var_dump($event);
					$status='';
					switch($event->getValue())
					{
						case 'HUMAN':
						{
							$status = 'ANSWER';	
							break;
						}
						case 'MACHINE':
						{
							$status = 'NO ANSWER';
							break;
						}
						case 'NOTSURE':
						{
							$status = 'NO ANSWER';
							break;
						}
						case 'HANGUP':
						{
							$status = 'BUSY';
							break;
						}
						default:
						{
							$status = 'UNKNOWN';
							break;
						}
					}
                                        $number = $this->customer['phone'];
                                        $state = $this->asterisk[0]['state'];
					//write down status call 1
					$sql = "update $this->table set state=$state, status='$status',last_call=NOW()  where phonenumber=$number";
					$this->Update_Mysql($sql);
					//store status and callid
					$this->asterisk[0]['status'] = $status;
					$this->asterisk[0]['callid'] = $event->getChannel();
					return;
				
                        	},
                        	function (EventMessage $event)
                        	{
					if($event instanceof NewStateEvent)
					{
						$name = $this->customer['name'];
	                                        $number = $this->customer['phone'];
        	                                $channel = $event->getChannel();
                	                        $state = $this->asterisk[0]['state'];
						$sql = "insert into phone(Name,Number,Channel,State) values ('$name','$number','$channel','$state')";
						$this->Update_Mysql($sql);
						
					}
                                	if($event instanceof  VarSetEvent && $event->getVariableName() == 'AMDSTATUS')
                               		{
						
                                        	return true;
                                	}
                       		 }
                	);
		
			$running =true;
			while($running)
			{
				$pamiClient->process();
				//return  if get variable callid and status
			 	if(!empty($this->asterisk[0]['callid'])&& !empty($this->asterisk[0]['status']))
                                	return;

			}
		
			$pamiClient->close();
		}
		else//can not call number, may be phone is invalid 
		{
			 $number = $this->customer['phone'];
                         $sql = "update $this->table set state=3, status='INVALID',last_call=NOW()  where phonenumber=$number";
                        $this->Update_Mysql($sql);

			$pamiClient->close();
			exit();
		}		
	}
	


	 protected function Remote_Asterisk_2()
        {
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
                        	function (VarSetEvent $event)
                        	{//get callid
                                	 $status='';
                                        switch($event->getValue())
                                        {
                                                case 'HUMAN':
                                                {
                                                        $status = 'ANSWER';
                                                        break;
                                                }
                                                case 'MACHINE':
                                                {
                                                        $status = 'NO ANSWER';
                                                        break;
                                                }
                                                case 'NOTSURE':
                                                {
                                                        $status = 'NO ANSWER';
                                                        break;
                                                }
                                                case 'HANGUP':
                                                {
                                                        $status = 'BUSY';
                                                        break;
                                                }
                                                default:
                                                {
                                                        $status = 'UNKNOWN';
                                                        break;
                                                }
                                      }
                                        $number = $this->customer['phone'];
                                        $state = $this->asterisk[1]['state'];

                                        //write down status call 2
                                        $sql = "update $this->table set state=$state, status='$status',last_call=NOW()  where phonenumber=$number";
                                        $this->Update_Mysql($sql);
					  //store status and callid
                                        $this->asterisk[1]['status'] = $status;
                                        $this->asterisk[1]['callid'] = $event->getChannel();

                                        return ;


                        	},
                        	function (EventMessage $event)
                        	{
					$i = 1;
					 if($event instanceof NewStateEvent && $i==1)
                                        {
						$i++;
                                                $name = $this->customer['name'];
                                                $number = $this->customer['phone'];
                                                $channel = $event->getChannel();
                                                $state = $this->asterisk[1]['state'];
                                                $sql = "insert into phone(Name,Number,Channel,State) values ('$name','$number','$channel','$state')";
                                                $this->Update_Mysql($sql);

                                        }

                                	if($event instanceof  VarSetEvent && $event->getVariableName() == 'AMDSTATUS')
                                	{
                                        	return true;
                                	}
                        	}
                	);
                	$running =true;
                	while($running)
                	{
                        	$pamiClient->process();
                        	if(!empty($this->asterisk[1]['callid']) && !empty($this->asterisk[1]['status']))
					return;
                	}

                	$pamiClient->close();
		}
		else
		{
			$number = $this->customer['phone'];
			 $sql = "update $this->table set state=3, status='INVALID',last_call=NOW()  where phonenumber=$number";
			$this->Update_Mysql($sql);
			$pamiClient->close();
			exit();
		}
	}
	public function run()
	{
		for($i=0;$i<=2;$i++)
		{
			$pid = pcntl_fork();
			if($pid===0)
			{
				switch($i)
				{
					case 0:
						sleep(2);
						$this->Remote_Asterisk_1();
						exit();
					case 1:
						sleep(2);
						$this->Remote_Asterisk_2();
						exit();
					case 2:
						sleep(4);
						$this->voicemail();
						exit();
				}
			}
		
		}
		pcntl_wait($status);
		return;
	}
	protected function voicemail()
	{
		$phone = $this->customer['phone'];
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
		/**********Check phonenumber valid********/

		/****************************************/
		//Hangup call1 and call 2
		sleep(5);
		$this->Get_Channel();
		$this->Hangup_Call($this->asterisk[0]);
		$this->Hangup_Call($this->asterisk[1]);
		$pamiClient->close();
		//write drow status call3
		$number = $this->customer['phone'];
		 $sql = "update $this->table set state=3, status='BUSY',last_call=NOW(),is_active=0  where phonenumber=$number";
		$this->Update_Mysql($sql);
		sleep(5);
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
	//get all phone
	$array = array();
	$phone = array(
		'name'=> '',
		'phone'=>'',
		'email'=>''
	);
	try{
		$dbh = new PDO("mysql:host=127.0.0.1;dbname=asdb",'root','12345678@X');
		$sql = "select name,email,phonenumber,is_active from leads";
		$i=0;
		foreach ($dbh->query($sql) as $row)
                {
		     if($row['is_active']==1)
			{
                     $phone['name'] = $row['name'];
		     $phone['phone'] = $row['phonenumber'];
		     $phone['email'] = $row['email'];
		     $array[$i] = $phone;
		     $i++;          
			}
                }
		$dbh = null;
	}
	catch(PDOException $e)
	{
		$e->getMessage();
	}
	//
	foreach($array as $phone)
	{
		//$phone['phone'];
		$a->Get_Customer($phone['name'],$phone['phone'],$phone['email']);
		$a->run();
		sleep(20);
	}
	    	


                                          
				
	
         


?>
