<?php 
class clients extends Controller{

	protected $clients;

	protected $transaction;

	protected $from;

	protected $to; 

	public function __construct()
	{
		parent::__construct();
	} 

	public function index()
	{
		$errormessage = null;
		$post = null;
		$created = null;
		if(isset($_POST['submit']))
		{
			unset($_POST['submit']);
			$errormessage = $this->isEmpty($_POST, $errormessage);

			if(strlen($_POST['nif']) != 8 || !is_numeric($_POST['nif']))
			{
				$errormessage .= "<p class='bg-danger'>Le NIF doit contenir huit chiffres</p>";
			}

			if(!empty($this->clients->isUnique($_POST['nif'])))
			{
				$errormessage .= "<p class='bg-danger'>Ce NIF est déjà utilisé par un autre client</p>";
			}

			if(strlen($_POST['telephone']) != 8 || !is_numeric($_POST['telephone']))
			{
				$errormessage .= "<p class='bg-danger'>Le téléphone doit est un nombre de huit chiffres</p>";
			}

			if (!preg_match("/^[a-zA-Z'-éèçùàê]+$/", $_POST['firstname']) || !preg_match("/^[a-zA-Z'-]+$/", $_POST['lastname'])) 
			{ 
				$errormessage .= "<p class='bg-danger'>Les noms et prénoms ne peuvent contenir que des lettres, tirets et apostrophes</p>";
			}

			if(empty($this->status[$_POST["status"]]))
			{
				if(!empty($_POST["status"]))
				{
					$errormessage .= "<p class='bg-danger'>This type of status does not exist</p>";
				}
				else
				{
					$errormessage .= "<p class='bg-danger'>You must choose a status</p>";
				}
			}

			if($errormessage == null)
			{
				if($client = $this->clients->setClient($_POST))
				{
					$id = $client;
					$errormessage = "<p class='bg-success'>Client created</p>";
					$_POST['id'] = $id;
					$_POST['status'] = $this->status[$_POST['status']];
					$created = $_POST;
				}
				else
				{
					$errormessage = "<p class='bg-danger'>Something went wrong! Contact support...</p>";
				}
				
			}
			else
			{
				$post = $_POST;
			}
		}
		$clients = $this->clients->getClients();
		for($i=0;$i<count($clients);$i++)
		{
			if(empty($clients[$i]['actif']))
			{
				$clients[$i]['actif'] = 0;
			}
			$clients[$i]['status'] = $this->status[$clients[$i]['status']];
		}
		$this->view('clients/index', array("error" => $errormessage, "clients" => $clients, 'client' => $post, 'created' => $created));
	}

	public function delete($clientID)
	{
		if($_SESSION['role'] == "A")
		{
			header('Location:'.DIRECTORY_NAME.'/clients');
		}
		$this->clients->deleteClient($clientID);
		header('Location: '.DIRECTORY_NAME.'/clients');
	}

	public function setClientChoice($id)
	{
		$client = $this->clients->getClient($id);
		if(!empty($client))
		{
			$_SESSION['client'] = array('id' => $client['id'],
										'firstname' => $client['firstname'],
										'lastname' => $client['lastname'], 
										'status' => $client['status'],
										'NIF' => $client['NIF'],
										'telephone' => $client['telephone']);
		}
		header("location:".DIRECTORY_NAME."/syntheses");
	}

	public function choose()
	{
		$fistname = null;
		$lastname = null;
		$nif = null;
		if(strpos($_POST['client'], " "))
		{
			$split = split($_POST['client'], '');
			$firstname = $split[0];
			if(!empty($split[1]))
			{
				$lastname = $split[1];
			}
		}
		else
		{
			$split = $_POST['client'];
			$firstname = $split;
			$lastname = $split;
			$nif = $split;
		}
		$client = $this->clients->findClient($firstname, $lastname, $nif);
		echo json_encode($client);
		die();
	}
}