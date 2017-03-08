<?php 
class produits extends Controller{

	protected $products;

	protected $status;

	protected $categories;

	protected $from;

	protected $to; 

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

		if(isset($_POST['submit']))
		{
			unset($_POST['submit']);
			$errormessage = $this->isEmpty($_POST, $errormessage);

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

			if(empty($_POST["price"]))
			{
				$errormessage .= "<p class='bg-danger'>Vous devez indiquer le prix du produit</p>";
			}
			else
			{
				if(!is_numeric($_POST['price']))
				{
					$errormessage .= "<p class='bg-danger'>Le prix doit être de type numérique</p>";
				}
				else
				{
					if($_POST['price'] < 0)
					{
						$errormessage .= "<p class='bg-danger'>Le prix doit être supérieur à 0</p>";
					}
				}
			}

			if($errormessage == null)
			{
				if(!empty($this->products->isProductUnique($_POST['name'], $_POST['category_id'])))
				{
					$errormessage .= "<p class='bg-danger'>Ce produit existe déjà</p>";
				}
				else
				{
					if($product = $this->products->setProduct($_POST))
					{
						$id = $product;
						$errormessage = "<p class='bg-success'>Produit créé</p>";
						$_POST['id'] = $id;
						$_POST['status'] = $this->status[$_POST['status']];
						$created = $_POST;
					}
					else
					{
						$errormessage = "<p class='bg-danger'>Something went wrong! Contact support...</p>";
					}
				}
				
			}
			else
			{
				$post = $_POST;
			}
		}
		$products = $this->products->getProduits();
		$categories = $this->categories->getCategories();
		$activeCat = $this->categories->getActiveCategories();
		for($i=0;$i<count($categories);$i++)
		{
			$categories[$i]['status'] = $this->status[$categories[$i]['status']];
		}
		for($i=0;$i<count($products);$i++)
		{
			$products[$i]['status'] = $this->status[$products[$i]['status']];
		}
		$this->view('produits/index', array("error" => $errormessage, "products" => $products, 'product' => $post, 'created' => $created, 'categories' => $categories, 'category' => $newcat, "active_cat" => $activeCat));
	}

	public function deleteProduct($id)
	{
		$this->products->deleteProduct($id);
		header('Location: '.DIRECTORY_NAME.'/produits');
	}

	public function deleteCategory($id)
	{
		$this->products->deleteCategory($id);
		header('Location: '.DIRECTORY_NAME.'/categories');
	}
}