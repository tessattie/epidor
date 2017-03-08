<?php 
class utilisateurs extends Controller{

	protected $utilisateurs;

	private $password;

	protected $access;

	protected $status;

	protected $from;

	protected $to; 

	public function __construct()
	{
		parent::__construct();
		$this->password = "01b307acba4f54f55aafc33bb06bbbf6ca803e9a";
	} 

	public function index($errormessage = '')
	{
		if($_SESSION['role'] == "A")
		{
			header('Location:'.DIRECTORY_NAME.'/clients');
		}
		// $this->checkSession();
		$errormessage = null;
		$post = null;
		$created = null;
		if(isset($_POST['submit']) && $_POST['form_id'] != $_SESSION['form_id'])
		{
			$_SESSION['form_id'] = $_POST['form_id'];
			unset($_POST['submit']);
			$errormessage = $this->isEmpty($_POST, $errormessage);
			
			if(!empty($this->utilisateurs->isUnique($_POST['username'])))
			{
				$errormessage .= "<p class='bg-danger'>Ce nom d'utilisateur est déjà pris</p>";
			}
			if(isset($_POST["access"]))
			{
				if(empty($this->access[$_POST["access"]]))
				{
					if(!empty($_POST["access"]))
					{
						$errormessage .= "<p class='bg-danger'>Ce type d'accès n'existe pas</p>";
					}
					else
					{
						$errormessage .= "<p class='bg-danger'>Vous devez choisir un type d'accès</p>";
					}
				}
			}
			else
			{
				$errormessage .= "<p class='bg-danger'>Vous devez choisir un type d'accès</p>";
			}
			if(isset($_POST["status"]))
			{
				if(empty($this->status[$_POST["status"]]))
				{
					if(!empty($_POST["status"]))
					{
						$errormessage .= "<p class='bg-danger'>Ce type de statut n'existe pas</p>";
					}
					else
					{
						$errormessage .= "<p class='bg-danger'>Vous devez choisir un statut</p>";
					}
				}
			}
			else
			{
				$errormessage .= "<p class='bg-danger'>Vous devez choisir un statut</p>";
			}
			$_POST['password'] = $this->password;
			if($errormessage == null)
			{
				if($utilisateur = $this->utilisateurs->setUser($_POST))
				{
					$id = $utilisateur;
					$errormessage = "<p class='bg-success'>Utilisateur sauvegardé</p>";
					$_POST['status'] = $this->status[$_POST['status']];
					$_POST['access'] = $this->access[$_POST['access']];
					$_POST['id'] = $id;
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
		$users = $this->utilisateurs->getUsers();
		for($i=0;$i<count($users);$i++)
		{
			if(empty($users[$i]['actif']))
			{
				$users[$i]['actif'] = 0;
			}
			$users[$i]['status'] = $this->status[$users[$i]['status']];
			$users[$i]['access'] = $this->access[$users[$i]['access']];
		}
		$this->view('utilisateurs/index', array('users' => $users, 'error' => $errormessage, 'user' => $post, 'created' => $created));
	}

	public function delete($userId)
	{
		if($_SESSION['role'] == "A")
		{
			header('Location:'.DIRECTORY_NAME.'/clients');
		}
		$this->utilisateurs->deleteUser($userId);
		header('Location: '.DIRECTORY_NAME.'/utilisateurs');
	}

	public function compte($error = "")
	{
		if($error == "false")
		{
			$error = "<p class='bg-danger'>Mauvais mot de passe. Réessayez...</p>";
		}
		$this->view('utilisateurs/compte', array("error" => $error));
	}

	public function refresh($userId)
	{
		if($_SESSION['role'] == "A")
		{
			header('Location:'.DIRECTORY_NAME.'/clients');
		}
		$password = "01b307acba4f54f55aafc33bb06bbbf6ca803e9a";
		$this->utilisateurs->setPassword($userId, $password);
		if($_SESSION['id'] == $userId)
		{
			session_unset();
			session_destroy();
			header('Location: '.DIRECTORY_NAME.'/login');
		}
		else
		{
			header('Location: '.DIRECTORY_NAME.'/utilisateurs');
		}
	}

	public function changePassword()
	{
		if(isset($_POST['oldpass']))
		{
			$oldpass = sha1($_POST['oldpass']);
			$user = $this->utilisateurs->getUser($_SESSION['username'], sha1($_POST['oldpass']));
			if(!empty($user))
			{
				if(isset($_POST['newpass']) && isset($_POST['newpass2']) && $_POST['newpass2'] == $_POST['newpass'])
				{
					$this->utilisateurs->setPassword($_SESSION['id'], sha1($_POST['newpass']));
					session_unset();
					session_destroy();
					header('Location: '.DIRECTORY_NAME.'/login');
				}
				else
				{
					header('Location: '.DIRECTORY_NAME.'/utilisateurs/compte');
				}
			}
			else
			{
				header('Location: '.DIRECTORY_NAME.'/utilisateurs/compte/false');
			}
		}
	}

	public function deconnexion()
	{
		session_destroy();	
		header("Location:".DIRECTORY_NAME."/login");
	}
}