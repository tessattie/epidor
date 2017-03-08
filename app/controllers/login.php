<?php
class login extends Controller{
	
	private $users;

	protected $from;

	protected $to; 

	public function __construct()
	{
		$this->users = $this->model('user');
	} 
	
	public function index($errorMessage = '')
	{
		if(!empty($_SESSION['id']))
		{
			header('Location:'.DIRECTORY_NAME.'/achats/nouveau');
		}
		if(isset($_POST['submit']))
		{
			$password = $this->users->isUsername($_POST['username']);
			if(empty($password))
			{
				$errorMessage = '<p class="bg-danger">Ce nom d\'utilisateur n\'existe pas ou n\'est pas actif</p>';
			}
			else
			{
				$user = $this->users->getUser($_POST['username'], sha1($_POST['password']));
				if(empty($user))
				{
					$errorMessage = '<p class="bg-danger">Le nom d\'utilisateur et le mot de passe ne correspondent pas</p>';
				}
				else
				{
					$this->startUserSession($user);
					header('Location: '.DIRECTORY_NAME.'/achats/nouveau');
				}
			}
		}
		$this->view('login/index', array('error' => $errorMessage));
	}

	private function startUserSession($user)
	{
		$_SESSION["id"] = $user['id'];
		$_SESSION["username"] = $user['username'];
		$_SESSION["firstname"] = $user['firstName'];
		$_SESSION["lastname"] = $user['lastName'];
		$_SESSION["role"] = $user['access'];
	}
}