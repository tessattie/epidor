<?php 
class categories extends Controller{

	protected $products;

	protected $status;

	protected $categories;

	public function __construct()
	{
		parent::__construct();
	} 

	public function index()
	{
		if($_SESSION['role'] == "A")
		{
			header('Location:'.DIRECTORY_NAME.'/clients');
		}
		$errormessage = null;
		$post = null;
		$newcat = null;
		$created = null;
		if(isset($_POST['categorie']))
		{
			unset($_POST['categorie']);
			$errormessage = $this->isEmpty($_POST, $errormessage);

			if(empty($this->status[$_POST["status"]]))
			{
				if(!empty($_POST["status"]))
				{
					$errormessage .= "<p class='bg-danger'>Ce type de statut n'existe pas</p>";
				}
				else
				{
					$errormessage .= "<p class='bg-danger'>Choississez un statut</p>";
				}
			}

			if(!empty($this->categories->isUnique($_POST['name'])))
			{
				$errormessage .= "<p class='bg-danger'>Ce nom est déjà utilisé par une autre catégorie</p>";
			}
			
			if($errormessage == null)
			{
				$_POST["name"] = strtoupper($_POST["name"]);
				if($categorie = $this->categories->setCategory($_POST))
				{
					$id = $categorie;
					$errormessage = "<p class='bg-success'>Categorie créée</p>";
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
				$newcat = $_POST;
			}
		}
		$categories = $this->categories->getCategories();
		for($i=0;$i<count($categories);$i++)
		{
			$categories[$i]['status'] = $this->status[$categories[$i]['status']];
		}
		$this->view('categories/index', array("categories" => $categories, 'category' => $newcat, "error" => $errormessage));
	}

}