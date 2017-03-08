<?php
class Controller
{
	protected $utilisateurs;

	protected $from;

	protected $debit;

	protected $to; 

	protected $clients;

	protected $PHPExcel;

	protected $access;

	protected $status;

	protected $products;

	protected $categories;

	protected $current_client;

	protected $transaction;

	public function __construct()
	{
		$this->access = array("SA" => "Super-admin", "A" => "Admin");
		$this->status = array(0 => "Inactif", 1 => "Actif");
		$this->utilisateurs = $this->model('user');
		$this->products = $this->model('produit');
		$this->transaction = $this->model('transaction');
		$this->clients = $this->model('client');
		$this->debit = $this->model('debit');
		$this->categories = $this->model('categorie');
		$this->verifyClientSession();

		if(empty($_SESSION['daily']))
		{
			$_SESSION['daily'] = date('Y-m-d');
		}
		// set dates if session is empty
		if(empty($_SESSION['to']))
		{
			$_SESSION['to'] = null;
		}
		if(empty($_SESSION['from']))
		{
			$_SESSION['from'] = null;
		}

		if(empty($_SESSION['monthly']))
		{
			$_SESSION['monthly'] = array('year' => date('Y'), 'month' => date('m'));
		}

		if(empty($_SESSION['pardate']['from']))
		{
			$_SESSION['pardate']['from'] = date("Y-m-01");
		}
		if(empty($_SESSION['pardate']['to']))
		{
			$_SESSION['pardate']['to'] = date("Y-m-t");
		}
		if(empty($_SESSION['form_id']))
		{
			$_SESSION['form_id'] = false;
		}

		if(empty($_SESSION['synthese_user']))
		{
			$_SESSION['synthese_user'] = $_SESSION['id'];
		}
	}

	public function model($model)
	{
		require_once ROOT_DIRECTORY.'/app/models/'.$model.'.php';
		return new $model();
	}

	public function excel()
	{
		require_once ROOT_DIRECTORY.'/app/vendors/PHPExcel/Classes/PHPExcel.php';
		date_default_timezone_set('America/Port-au-Prince');
		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory; 
		PHPExcel_Settings::setCacheStorageMethod($cacheMethod);
		return new PHPExcel();
	}

	public function fpdf()
	{
		require_once ROOT_DIRECTORY.'/app/vendors/fpdf/fpdf.php';
		return new FPDF();
	}

	public function view($view, $data = array())
	{
		$current_balance = 0;
		if(!empty($_SESSION['id']))
		{
			$current_balance = $this->transaction->getTotalBalance()['balance'];
		}
		$dates = array("from" => $this->from, "to" => $this->to);
		require_once ROOT_DIRECTORY.'/app/views/'.$view.'.php';
	}

	public function checkSession()
	{
		if(!isset($_SESSION['id']))
		{
			header('Location: '.DIRECTORY_NAME.'/public/login');
		}
	}

	public function update()
	{
		$_POST['model'] = str_replace(" odd", "", $_POST['model']);
		$_POST['model'] = str_replace("odd ", "", $_POST['model']);
		$_POST['model'] = str_replace(" even", "", $_POST['model']);
		$_POST['model'] = str_replace("even ", "", $_POST['model']);
		$this->$_POST['model']->update($_POST['model'], $_POST['field'], $_POST['ident'], $_POST['value']);
		if($_POST['field'] != "firstname" && $_POST['field'] != "lastname" && $_POST['field'] != "description"
			&& $_POST['field'] != "name")
		{
			$_POST['value'] = str_replace("-", "", $_POST['value']);
		}
		if($_POST['field'] == "access")
		{	
			if($_POST['value'] != "SA" && $_POST['value'] != "A")
			{
				$_POST['value'] = "A";
				$this->$_POST['model']->update($_POST['model'], $_POST['field'], $_POST['ident'], $_POST['value']);
				echo "Admin";
				die();
			}
		}
		if($_POST['field'] == "status")
		{	
			if($_POST['value'] != "1" && $_POST['value'] != "0")
			{
				$_POST['value'] = "1";
				echo "Actif";
				die();
			}
			$this->$_POST['model']->update($_POST['model'], $_POST['field'], $_POST['ident'], $_POST['value']);
		}
		if($_POST['field'] == "amount")
		{
			$_POST['value'] = str_replace(',', "", $_POST['value']);
			$_POST['value'] = str_replace(' ', "", $_POST['value']);
			$balance = $this->transaction->getClientBalance($_SESSION['client']['id']);
			$balance = $balance['balance'];
			if($_POST['value'] > $balance)
			{
				echo "false";die();
			}
			else
			{
				$this->$_POST['model']->update($_POST['model'], $_POST['field'], $_POST['ident'], $_POST['value']);
				echo number_format($this->transaction->getClientBalance($_SESSION['client']['id'])['balance'], 2, ".", " ");
				die();		
			}
		}
		
		
		if($_POST['model'] == "categories" && $_POST['field'] == "status")
		{
			$this->products->updateStatus($_POST['value'], $_POST['ident']);
		}
		if($_POST['model'] == "products" && $_POST['field'] == "category_id")
		{
			$this->$_POST['model']->update($_POST['model'], $_POST['field'], $_POST['ident'], $_POST['value']);
			$_POST['value'] = $this->categories->getCategoryName($_POST['value']);
		}
		echo $_POST['value'];die();
	}

	public function isEmpty($post, $errormessage)
	{
		foreach($post AS $key => $value)
		{
			if(empty($value) && $value != 0)
			{
				$errormessage = "<p class='bg-danger'>Vous devez compléter tous les champs</p>";
			}
		}
		return $errormessage;
	}

	public function verifyClientSession()
	{
		if(!empty($_SESSION['client']))
		{
			$client = $this->clients->getClient($_SESSION['client']['id']);
			$_SESSION['client'] = array('id' => $client['id'],
										'firstname' => $client['firstname'],
										'lastname' => $client['lastname'], 
										'status' => $client['status'],
										'NIF' => $client['NIF'],
										'telephone' => $client['telephone']);
		}
	}

	public function setToDate($to)
	{
		if(!empty($to))
		{
			$_SESSION['to'] = $to;
		}
	}

	public function setFromDate($from)
	{
		if(!empty($from))
		{
			$_SESSION['from'] = $from;
		}
	}
}