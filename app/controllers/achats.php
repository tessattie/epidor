<?php 

class achats extends Controller{

	protected $products;

	protected $categories;

	protected $transaction;

	protected $clients;

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
		if(empty($_SESSION['client']))
		{
			header('Location:'.DIRECTORY_NAME.'/clients');
		}
		$fiches = $this->transaction->getClientCredit($_SESSION['client']['id']);
		$this->view('achats/fiche', array("error" => $errormessage, "fiches" => $fiches));
	}

	public function editer($id = false)
	{
		$products = $this->products->getProduitsActif();
		$categories = $this->categories->getActiveCategories();
		$balance = $this->transaction->getClientBalance($_SESSION['client']['id']);
		$balance = $balance['balance'];
		$errormessage = null;
		if(isset($_POST['submit'])  && $_POST['form_id'] != $_SESSION['form_id'] && count($_POST['id']) > 0)
		{
			// print_r($_POST);die();
			$_SESSION['form_id'] = $_POST['form_id'];

			$total = 0;

			for($i=0;$i<count($_POST['id']);$i++)
			{
				$total = $total + $_POST['quantity'][$i]*$_POST['price'][$i];	
			}

			$transaction = $_POST['transaction'];
			$id = $_POST['transaction'];

			$futureBalance = $balance + round(($total*15)/100);

			if($futureBalance > $this->clients->getClientPlafond($_SESSION['client']['id']))
			{
				$errormessage .= "<p class='bg-danger'>Ce client a dépassé son plafond</p>";
			}

			if($errormessage == null)
			{
				$this->transaction->deleteCredit($_POST['transaction']);
				$this->transaction->deleteCash($_POST['transaction']);
				if($total > 200)
				{
					for($i=0;$i<count($_POST['id']);$i++)
					{
						if(!empty($_POST['id'][$i]) && !empty($_POST['quantity'][$i]))
						{
							if($product = $this->transaction->setCredit($transaction, $_POST['id'][$i], $_POST['quantity'][$i], $_POST['price'][$i]))
							{

							}
						}
						else
						{
							$errormessage .= "<p class='bg-danger'>Certains produits n'ont pas pu être sauvegardé. Vérifiez la fiche.</p>";
						}
					}
				}
				else
				{
					for($i=0;$i<count($_POST['id']);$i++)
					{
						if(!empty($_POST['id'][$i]) && !empty($_POST['quantity'][$i]))
						{
							if($product = $this->transaction->setCash($transaction, $_POST['id'][$i], $_POST['quantity'][$i], $_POST['price'][$i]))
							{

							}
						}
						else
						{
							$errormessage .= "<p class='bg-danger'>Certains produits n'ont pas pu être sauvegardé. Vérifiez la fiche.</p>";
						}
					}
				}
					
					$errormessage = "<p class='bg-success'>Fiche <a href='".DIRECTORY_NAME."/achats/single/".$transaction."'>#".$transaction_id."</a> sauvegardée</p>";
				}
				header("Location:".DIRECTORY_NAME."/achats/editer/".$_POST['transaction']);
			}
		$purchases = $this->transaction->getCreditDetails($id);
		$this->view('achats/editer', array("purchases" => $purchases, "categories" => $categories, "products" => $products));
	}

	public function single($id)
	{
		if(empty($id))
		{
			header('Location:'.DIRECTORY_NAME.'/achats/fiche');
		}
		$purchases = $this->transaction->getCreditDetails($id);
		$this->view('achats/single', array("purchases" => $purchases));
	}

	public function nouveau()
	{
		$products = $this->products->getProduitsActif();
		$categories = $this->categories->getActiveCategories();
		$errormessage = null;
		$balance = $this->transaction->getClientBalance($_SESSION['client']['id']);
		$balance = $balance['balance'];
		if(empty($_SESSION['client']))
		{
			header('Location:'.DIRECTORY_NAME.'/clients');
		}
		if(isset($_POST['submit'])  && $_POST['form_id'] != $_SESSION['form_id'] && !empty($_POST['id']))
		{
			// print_r($_POST);die();
			$_SESSION['form_id'] = $_POST['form_id'];
			$client_id = $_SESSION['client']['id'];

			$transaction_id = null;

			$utilisateur_id = $_SESSION['id'];

			do{
				$transaction_id = mt_rand(100000000, 999999999);
			}while(!empty($this->transaction->isUnique($transaction_id)));

			$total = 0;

			for($i=0;$i<count($_POST['id']);$i++)
			{
				$total = $total + $_POST['quantity'][$i]*$_POST['price'][$i];	
			}

			$futureBalance = $balance + round(($total*15)/100);

			if($futureBalance > $this->clients->getClientPlafond($_SESSION['client']['id']))
			{
				$errormessage .= "<p class='bg-danger'>Ce client a dépassé son plafond</p>";
			}
			if($errormessage == null)
			{
				if($transaction = $this->transaction->setTransaction($client_id, $utilisateur_id, $transaction_id))
				{
					if($total > 200)
					{
						for($i=0;$i<count($_POST['id']);$i++)
						{
							if(!empty($_POST['id'][$i]) && !empty($_POST['quantity'][$i]))
							{
								if($product = $this->transaction->setCredit($transaction, $_POST['id'][$i], $_POST['quantity'][$i], $_POST['price'][$i]))
								{

								}
							}
							else
							{
								$errormessage .= "<p class='bg-danger'>Certains produits n'ont pas pu être sauvegardé. Vérifiez la fiche.</p>";
							}
						}
					}
					else
					{
						for($i=0;$i<count($_POST['id']);$i++)
						{
							if(!empty($_POST['id'][$i]) && !empty($_POST['quantity'][$i]))
							{
								if($product = $this->transaction->setCash($transaction, $_POST['id'][$i], $_POST['quantity'][$i], $_POST['price'][$i]))
								{

								}
							}
							else
							{
								$errormessage .= "<p class='bg-danger'>Certains produits n'ont pas pu être sauvegardé. Vérifiez la fiche.</p>";
							}
						}
					}
					
					$errormessage = "<p class='bg-success'>Fiche <a href='".DIRECTORY_NAME."/achats/single/".$transaction."'>#".$transaction_id."</a> sauvegardée</p>";
				}
				else
				{
					$errormessage = "<p class='bg-danger'>Cette fiche n'a pas été sauvegardée</p>";
				}
			}
			// return purchaseID and save all products and quantity for each product
		}
		$this->view('achats/index', array("products" => $products, "categories" => $categories, "error" => $errormessage));
	}
}