<?php

class syntheses extends Controller
{
	protected $transaction;

	protected $utilisateurs;

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
		$today = date('Y-m-d');
		$balance = 0;
		if(isset($_POST['today']))
		{
			$today = $_POST['today'];
			$_SESSION['daily'] = $_POST['today'];
		}
		if(!empty($_SESSION['daily']))
		{
			$today = $_SESSION['daily'];
		}
		$from = $today." 00:00:00";
		$to = $today." 23:59:59";
		if(!empty($_SESSION['client']))
		{
			$transactions = $this->transaction->getTransactionsByClient($from, $to, $_SESSION['client']['id']);
		}
		else
		{
			$transactions = $this->transaction->getTransactions($from, $to);
		}
		$this->view('syntheses/index', array('transactions' => $transactions, 'balance' => $balance));
	}

	public function mensuelle()
	{
		if($_SESSION['role'] == "A")
		{
			header('Location:'.DIRECTORY_NAME.'/clients');
		}
		$balance = 0;
		if(isset($_POST['year']))
		{
			$_SESSION['monthly']['year'] = $_POST['year'];
		}

		if(isset($_POST['month']))
		{
			$_SESSION['monthly']['month'] = $_POST['month'];
		}

		$from = $_SESSION['monthly']['year']."-".$_SESSION['monthly']['month']."-"."01 00:00:00";
		$to = $_SESSION['monthly']['year']."-".$_SESSION['monthly']['month']."-".date('t')." 00:00:00";

		if(!empty($_SESSION['client']))
		{
			$transactions = $this->transaction->getTransactionsByClient($from, $to, $_SESSION['client']['id']);
		}
		else
		{
			$transactions = $this->transaction->getTransactions($from, $to);
		}
		$this->view('syntheses/mensuelle', array('transactions' => $transactions, 'balance' => $balance));
	}

	public function parDate()
	{
		if($_SESSION['role'] == "A")
		{
			header('Location:'.DIRECTORY_NAME.'/clients');
		}
		$balance = 0;
		if(isset($_POST['from']) && !empty($_POST['from']))
		{
			$_SESSION['pardate']['from'] = $_POST['from']." 00:00:00";
		}
		if(isset($_POST['to']) && !empty($_POST['to']))
		{
			$_SESSION['pardate']['to'] = $_POST['to']." 23:59:59";
		}
		if(!empty($_SESSION['client']))
		{
			$transactions = $this->transaction->getTransactionsByClient($_SESSION['pardate']['from'], $_SESSION['pardate']['to'], $_SESSION['client']['id']);
		}
		else
		{
			$transactions = $this->transaction->getTransactions($_SESSION['pardate']['from'], $_SESSION['pardate']['to']);
		}

		if(isset($_POST['from']) && !empty($_POST['from']))
		{
			$_SESSION['pardate']['from'] = $_POST['from'];
		}
		if(isset($_POST['to']) && !empty($_POST['to']))
		{
			$_SESSION['pardate']['to'] = $_POST['to'];
		}

		$this->view('syntheses/date', array('transactions' => $transactions, 'balance' => $balance));
	}

	public function utilisateur()
	{
		$users = $this->utilisateurs->getUsers();
		if($_SESSION['role'] == "A")
		{
			header('Location:'.DIRECTORY_NAME.'/clients');
		}
		$balance = 0;
		if(isset($_POST['from']) && !empty($_POST['from']))
		{
			$_SESSION['pardate']['from'] = $_POST['from'];
		}
		if(isset($_POST['to']) && !empty($_POST['to']))
		{
			$_SESSION['pardate']['to'] = $_POST['to'];
		}
		if(isset($_POST['utilisateur']))
		{
			$_SESSION['synthese_user'] = $_POST['utilisateur'];
		}
		if(!empty($_SESSION['client']))
		{
			$transactions = $this->transaction->getTransactionsByClientAndUser($_SESSION['pardate']['from'], $_SESSION['pardate']['to'], $_SESSION['client']['id'], $_SESSION['synthese_user']);
		}
		else
		{
			$transactions = $this->transaction->getTransactionsByUser($_SESSION['pardate']['from'], $_SESSION['pardate']['to'], $_SESSION['synthese_user']);
		}

		$this->view('syntheses/utilisateur', array("users" => $users, 'transactions' => $transactions));
	}
}