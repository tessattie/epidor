<?php

class transactions extends Controller
{

	protected $purchases; 

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
		$post = null;
		$errormessage = null;
		$transaction = null;
		$transactions = null;
		if(empty($_SESSION['client']))
		{
			header('Location:'.DIRECTORY_NAME.'/clients');
		}
		$balance = $this->transaction->getClientBalance($_SESSION['client']['id']);
		$balance = $balance['balance'];
		if(isset($_POST['submit']) && $_POST['form_id'] != $_SESSION['form_id'])
		{
			$_SESSION['form_id'] = $_POST['form_id'];
			$_POST['amount'] = str_replace("-", "", $_POST['amount']);
			unset($_POST['submit']);
			$errormessage = $this->isEmpty($_POST, $errormessage);
			if(!is_numeric($_POST['amount']))
			{
				$errormessage .= "<p class='bg-danger'>Le montant doit forcément être un nombre. Pas d'espaces ou de virgules autorisées</p>";
			}
			else
			{
				if($_POST['amount'] > $balance)
				{
					$errormessage .= "<p class='bg-danger'>Ce client n'a pas les fonds pour ce retrait</p>";
				}
			}
			if($errormessage == null)
			{
				do{
					$transaction_id = mt_rand(100000000, 999999999);
				}while(!empty($this->transaction->isUnique($transaction_id)));
				
				if($transaction = $this->transaction->setTransaction($_SESSION['client']['id'], $_SESSION['id'], $transaction_id))
				{
					if($debit = $this->transaction->setDebit($transaction, $_POST['amount'], $_POST['description']))
					{
						$errormessage = "<p class='bg-success'>Transaction saved</p>";
						require_once('pdf.php');
						$printer = new pdf();
						$printer->imprimerRetrait($transaction);
					}
					else
					{
						$this->deleteTransaction($transaction);
						$errormessage = "<p class='bg-danger'>Un problème est survenu ! Contactez le support...</p>";
					}
					$_POST['id'] = $transaction;
				}
				else
				{
					$errormessage = "<p class='bg-danger'>Un problème est survenu ! Contactez le support...</p>";
				}
			}
			else
			{
				$post = $_POST;
			}
		}

		$transactions = $this->transaction->getClientDebit($_SESSION['client']['id']);

		$this->view('transactions/index', array("balance"=> $balance, "transactions" => $transactions, "error" => $errormessage, 'transaction' => $post));
	}

	public function deleteTransaction($id)
	{
		if($_SESSION['role'] == "A")
		{
			header('Location:'.DIRECTORY_NAME.'/clients');
		}
		$this->transaction->delete($id);
		header('Location: '.DIRECTORY_NAME.'/transactions');
	}
}