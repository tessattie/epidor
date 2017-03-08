<?php 
class pdf extends Controller{

	private $fpdf;

	protected $transaction;

	public function __construct()
	{
		parent::__construct();
		$this->fpdf = $this->fpdf('P','mm','A6');
	}

	public function imprimer($id = false)
	{
		if($id == false)
		{
			echo "<script>window.close()</script>";
		}
		$purchases = $this->transaction->getCreditDetails($id);
		$balance = $this->transaction->getClientBalance($_SESSION['client']['id']);
		$balance = $balance['balance'];
		$this->fpdf->AddPage();
		$this->header($purchases);
		$this->body($purchases, $balance);
		$this->fpdf->Output();
	}

	public function imprimerRetrait($id = false)
	{
		if($id == false)
		{
			echo "<script>window.close()</script>";
		}
		$purchases = $this->transaction->getClientDebitById($id);
		$balance = $this->transaction->getClientBalance($_SESSION['client']['id']);
		$balance = $balance['balance'];
		$this->fpdf->AddPage();
		$this->headerRetrait($purchases);
		$this->bodyRetrait($purchases, $balance);

		$this->fpdf->AddPage();
		$this->headerRetraitCommercant($purchases);
		$this->bodyRetraitCommercant($purchases, $balance);
		$this->fpdf->Output();
	}

	public function imprimerSyntheseMensuelle()
	{
		$balance = 0;

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
		$from = $_SESSION['monthly']['year']."-".$_SESSION['monthly']['month']."-"."01";
		$to = $_SESSION['monthly']['year']."-".$_SESSION['monthly']['month']."-".date('t', strtotime($from));
		if(!empty($_SESSION['client']))
		{
			$balance = $this->transaction->getClientBalance($_SESSION['client']['id']);
		}
		else
		{
			$balance = $this->transaction->getTotalBalance();
		}
		$balance = $balance['balance'];
		$this->fpdf->AddPage();
		$this->headerMensuelle($transactions);
		$this->bodyMensuelle($transactions, $balance, $from, $to);
		$this->fpdf->Output();
	}

	public function imprimerSynthesePersonnalisee()
	{
		$balance = 0;

		if($_SESSION['role'] == "A")
		{
			header('Location:'.DIRECTORY_NAME.'/clients');
		}
		if(!empty($_SESSION['client']))
		{
			$transactions = $this->transaction->getTransactionsByClient($_SESSION['pardate']['from'], $_SESSION['pardate']['to'], $_SESSION['client']['id']);
		}
		else
		{
			$transactions = $this->transaction->getTransactions($_SESSION['pardate']['from'], $_SESSION['pardate']['to']);
		}
		if(!empty($_SESSION['client']))
		{
			$balance = $this->transaction->getClientBalance($_SESSION['client']['id']);
		}
		else
		{
			$balance = $this->transaction->getTotalBalance();
		}
		$balance = $balance['balance'];
		$this->fpdf->AddPage();
		$this->headerPersonnalisee($transactions);
		$this->bodyMensuelle($transactions, $balance, $_SESSION['pardate']['from'], $_SESSION['pardate']['to']);
		$this->fpdf->Output();
	}

	private function headerMensuelle($purchases)
	{
	    // Logo
	    $this->fpdf->Image(ROOT_DIRECTORY.'/img/logo.jpg',17,0,40);

	    $this->fpdf->Ln(25);
	    // Police Arial gras 15
	    $this->fpdf->SetFont('Courier','B',10);
	    // Décalage à droite

	    // Titre
	    $this->fpdf->Cell(55,10,"Autoroute de Delmas",0,0,'C');

	    $this->fpdf->Ln(5);

	    $this->fpdf->Cell(55,10,"Delmas #56",0,0,'C');

	    $this->fpdf->Ln(5);

	    $this->fpdf->Cell(55,10,"Port-au-Prince",0,0,'C');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"Haiti",0,0,'C');

	    $this->fpdf->SetFont('Courier','B',8);
	    // Saut de ligne
	    $this->fpdf->Ln(10);
	    
	    $this->fpdf->Cell(55,10,"SYNTHESE MENSUELLE",1,0,'C');
		$this->fpdf->Ln(10);
	    $this->fpdf->Cell(55,10,"MOIS : ".$_SESSION['monthly']['month']."-".$_SESSION['monthly']['year'],1,0,'C');
	    $this->fpdf->Ln(10);
	    $this->fpdf->Cell(55,10,"DATE : ".date("j/m/Y - H:i"),0,0,'C');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"RESPONSABLE : ".$_SESSION["firstname"]." ".$_SESSION["lastname"],0,0,'C');
	    if(!empty($_SESSION["client"]))
	    {
	    	$this->fpdf->Ln(5);
	    	$this->fpdf->Cell(55,10,"CLIENT : ".$_SESSION["client"]["firstname"]." ".$_SESSION["client"]["lastname"],0,0,'C');
	    }
	    $this->fpdf->Ln(5);
	    $this->fpdf->SetFont('Courier','B',9);
	    $this->fpdf->Cell(55,10,"=====================================================",0,0,'C');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"TRANSACTIONS",0,0,'C');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"=====================================================",0,0,'C');
	    $this->fpdf->Ln(5);
	}

	private function headerPersonnalisee($purchases)
	{
	    // Logo
	    $this->fpdf->Image(ROOT_DIRECTORY.'/img/logo.jpg',17,0,40);

	    $this->fpdf->Ln(25);
	    // Police Arial gras 15
	    $this->fpdf->SetFont('Courier','B',10);
	    // Décalage à droite

	    // Titre
	    $this->fpdf->Cell(55,10,"Autoroute de Delmas",0,0,'C');

	    $this->fpdf->Ln(5);

	    $this->fpdf->Cell(55,10,"Delmas #56",0,0,'C');

	    $this->fpdf->Ln(5);

	    $this->fpdf->Cell(55,10,"Port-au-Prince",0,0,'C');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"Haiti",0,0,'C');

	    $this->fpdf->SetFont('Courier','B',8);
	    // Saut de ligne
	    $this->fpdf->Ln(10);
	    
	    $this->fpdf->Cell(55,10,"SYNTHESE PERSONNALISEE",1,0,'C');
	    $this->fpdf->Ln(10);
	    $this->fpdf->Cell(55,10,"DU " . $_SESSION['pardate']['from']." AU ".$_SESSION['pardate']['to'],1,0,'C');
	    $this->fpdf->Ln(10);
	    $this->fpdf->Cell(55,10,"DATE : ".date("j/m/Y - H:i"),0,0,'C');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"RESPONSABLE : ".$_SESSION["firstname"]." ".$_SESSION["lastname"],0,0,'C');
	    if(!empty($_SESSION["client"]))
	    {
	    	$this->fpdf->Ln(5);
	    	$this->fpdf->Cell(55,10,"CLIENT : ".$_SESSION["client"]["firstname"]." ".$_SESSION["client"]["lastname"],0,0,'C');
	    }
	    $this->fpdf->Ln(5);
	    $this->fpdf->SetFont('Courier','B',9);
	    $this->fpdf->Cell(55,10,"=====================================================",0,0,'C');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"TRANSACTIONS",0,0,'C');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"=====================================================",0,0,'C');
	    $this->fpdf->Ln(5);
	}

	private function header($purchases)
	{
	    // Logo
	    $this->fpdf->Image(ROOT_DIRECTORY.'/img/logo.jpg',17,0,40);

	    $this->fpdf->Ln(25);
	    // Police Arial gras 15
	    $this->fpdf->SetFont('Courier','B',10);
	    // Décalage à droite

	    // Titre
	    $this->fpdf->Cell(55,10,"Autoroute de Delmas",0,0,'C');

	    $this->fpdf->Ln(5);

	    $this->fpdf->Cell(55,10,"Delmas #56",0,0,'C');

	    $this->fpdf->Ln(5);

	    $this->fpdf->Cell(55,10,"Port-au-Prince",0,0,'C');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"Haiti",0,0,'C');

	    $this->fpdf->SetFont('Courier','B',8);
	    // Saut de ligne
	    $this->fpdf->Ln(10);

	    
	    $this->fpdf->Cell(55,10,"TICKET : #".$purchases[0]["transaction_id"],0,0,'L');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"DATE : ".date("j/m/Y - H:i", strtotime($purchases[0]['date'])),0,0,'L');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"RESPONSABLE : ".$purchases[0]["u_firstname"]." ".$purchases[0]["u_lastname"],0,0,'L');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"CLIENT : ".$purchases[0]["c_firstname"]." ".$purchases[0]["c_lastname"],0,0,'L');
	    $this->fpdf->Ln(10);
	    $this->fpdf->Cell(55,10,"=====================================================",0,0,'C');
	    $this->fpdf->Ln(5);
	}

	private function headerRetrait($purchases)
	{
	    // Logo
	    $this->fpdf->Image(ROOT_DIRECTORY.'/img/logo.jpg',17,0,40);

	    $this->fpdf->Ln(25);
	    // Police Arial gras 15
	    $this->fpdf->SetFont('Courier','B',10);
	    // Décalage à droite

	    // Titre
	    $this->fpdf->Cell(55,10,"Autoroute de Delmas",0,0,'C');

	    $this->fpdf->Ln(5);

	    $this->fpdf->Cell(55,10,"Delmas #56",0,0,'C');

	    $this->fpdf->Ln(5);

	    $this->fpdf->Cell(55,10,"Port-au-Prince",0,0,'C');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"Haiti",0,0,'C');

	    $this->fpdf->SetFont('Courier','B',8);
	    // Saut de ligne
	    $this->fpdf->Ln(10);

	    $this->fpdf->Cell(55,10,"FICHE A CONSERVER PAR LE CLIENT",0,0,'C');
	    $this->fpdf->Ln(10);
	    $this->fpdf->Cell(55,10,"TICKET : #".$purchases[0]["transaction_id"],0,0,'L');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"DATE : ".date("j/m/Y - H:i", strtotime($purchases[0]['date'])),0,0,'L');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"RESPONSABLE : ".$purchases[0]["user_firstname"]." ".$purchases[0]["user_lastname"],0,0,'L');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"CLIENT : ".$purchases[0]["client_firstname"]." ".$purchases[0]["client_lastname"],0,0,'L');
	}

	private function bodyMensuelle($purchases, $balance, $from, $to)
	{
		$this->fpdf->SetFont('Courier','B',6);
	    $this->fpdf->Cell(45,10,"BALANCE AU ".$from." (HTG)",0,0,'L');
	    $this->fpdf->Cell(15,10,"+".number_format($purchases[0]['balance_from'], 2, ".", " "),0,0,'R');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"================================================================================",0,0,'C');
	    $this->fpdf->Ln(5);
		$this->fpdf->SetFont('Courier','B',8);
		$this->fpdf->Cell(20,10,"#",0,0,'L');
		$this->fpdf->Cell(25,10,"Date",0,0,'C');
		$this->fpdf->Cell(15,10,"Montant",0,0,'R');
		$this->fpdf->Ln(5);
		$this->fpdf->SetFont('Courier','',8);
	    $this->fpdf->Cell(55,10,"==========================================================================",0,0,'C');
	    $this->fpdf->Ln(5);
		$total = 0;
		for($i=0;$i<count($purchases);$i++)
		{
			if($purchases[$i]['comission'] > 0)
			{
				$this->fpdf->Cell(20,10,"#" . $purchases[$i]['transaction_id'],0,0,'L');
				$this->fpdf->Cell(25,10,date("j/m/Y", strtotime($purchases[$i]['date'])),0,0,'C');
				$this->fpdf->Cell(15,10,"+".number_format($purchases[$i]['comission'],2,"."," "),0,0,'R');
				$this->fpdf->Ln(5);
			}
			if($purchases[$i]['debit'] > 0)
			{
				$this->fpdf->Cell(20,10,"#" . $purchases[$i]['transaction_id'],0,0,'L');
				$this->fpdf->Cell(25,10,date("j/m/Y", strtotime($purchases[$i]['date'])),0,0,'C');
				$this->fpdf->Cell(15,10,"-".number_format($purchases[$i]['debit'],2,"."," "),0,0,'R');
				$this->fpdf->Ln(5);
			}
		}
		$this->fpdf->Cell(55,10,"=======================================================",0,0,'C');
		$this->fpdf->SetFont('Courier','B',6);
		$this->fpdf->Ln(5);
	    $this->fpdf->Cell(45,10,"BALANCE AU ".$to." (HTG)",0,0,'L');
	    $this->fpdf->Cell(15,10,"+".number_format($purchases[0]['balance_to'], 2, ".", " "),0,0,'R');
	    $this->fpdf->Ln(5);
		$this->fpdf->Cell(55,10,"==========================================================================",0,0,'C');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(45,10,"BALANCE COURANTE (HTG)",0,0,'L');
	    $this->fpdf->Cell(15,10,"+".number_format($balance, 2, ".", " "),0,0,'R');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"==========================================================================",0,0,'C');
	    $this->fpdf->Ln(5);
	    $this->fpdf->SetFont('Courier','B',8);
	    $this->fpdf->Cell(60,10,"Nous vous remercions pour votre fidelite",0,0,'');
		$this->fpdf->Ln(10);
		$this->fpdf->SetFont('Courier','B',11);
	    $this->fpdf->Cell(60,10,"EPI D'OR",0,0,'R');
	    $this->fpdf->Ln(20);
	}

	private function headerRetraitCommercant($purchases)
	{
	    // Logo
	    $this->fpdf->Image(ROOT_DIRECTORY.'/img/logo.jpg',17,0,40);

	    $this->fpdf->Ln(25);
	    // Police Arial gras 15
	    $this->fpdf->SetFont('Courier','B',10);
	    // Décalage à droite

	    // Titre
	    $this->fpdf->Cell(55,10,"Autoroute de Delmas",0,0,'C');

	    $this->fpdf->Ln(5);

	    $this->fpdf->Cell(55,10,"Delmas #56",0,0,'C');

	    $this->fpdf->Ln(5);

	    $this->fpdf->Cell(55,10,"Port-au-Prince",0,0,'C');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"Haiti",0,0,'C');

	    $this->fpdf->SetFont('Courier','B',8);
	    // Saut de ligne
	    $this->fpdf->Ln(10);

	    $this->fpdf->Cell(55,10,"FICHE A CONSERVER PAR LE COMMERCANT",0,0,'C');
	    $this->fpdf->Ln(10);
	    $this->fpdf->Cell(55,10,"TICKET : #".$purchases[0]["transaction_id"],0,0,'L');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"DATE : ".date("j/m/Y - H:i", strtotime($purchases[0]['date'])),0,0,'L');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"RESPONSABLE : ".$purchases[0]["user_firstname"]." ".$purchases[0]["user_lastname"],0,0,'L');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"CLIENT : ".$purchases[0]["client_firstname"]." ".$purchases[0]["client_lastname"],0,0,'L');
	}

	private function body($purchases, $balance)
	{
		$this->fpdf->SetFont('Courier','B',8);
		$this->fpdf->Cell(20,10,"Produit",0,0,'L');
		$this->fpdf->Cell(15,10,"Prix(G)",0,0,'R');
		$this->fpdf->Cell(10,10,"Qte",0,0,'R');
		$this->fpdf->Cell(15,10,"Total",0,0,'R');
		$this->fpdf->Ln(5);
		$this->fpdf->SetFont('Courier','B',8);
	    $this->fpdf->Cell(55,10,"=======================================================",0,0,'C');
	    $this->fpdf->Ln(5);
		$this->fpdf->SetFont('Courier','B',6);
		$total = 0;
		for($i=0;$i<count($purchases);$i++)
		{
			$this->fpdf->Cell(20,10,substr($purchases[$i]['name'], 0, 17),0,0,'L');
			$this->fpdf->Cell(15,10,number_format($purchases[$i]['price'],2,"."," "),0,0,'R');
			$this->fpdf->Cell(10,10,$purchases[$i]['quantity'],0,0,'R');
			$this->fpdf->Cell(15,10,number_format($purchases[$i]['price']*$purchases[$i]['quantity'], 2, ".", " "),0,0,'R');
			$this->fpdf->Ln(5);
			$total = $total + $purchases[$i]['price']*$purchases[$i]['quantity'];
		}
		$this->fpdf->SetFont('Courier','B',8);
	    $this->fpdf->Cell(55,10,"=======================================================",0,0,'C');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(45,10,"TOTAL ACHATS (HTG)",0,0,'L');
	    $this->fpdf->Cell(55,10,number_format($total, 2, ".", " "),0,0,'L');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"=======================================================",0,0,'C');
	    if($total > 200)
	    {
	    	$this->fpdf->Ln(5);
		    $this->fpdf->Cell(45,10,"COMMISSION ACHATS (HTG)",0,0,'L');
		    $this->fpdf->Cell(55,10,number_format(($total*15/100), 2, ".", " "),0,0,'L');
		    $this->fpdf->Ln(5);
		    $this->fpdf->Cell(60,10,"(COMMISSION A PARTIR DE 200HTG)",0,0,'L');
		    $this->fpdf->Ln(5);
		    $this->fpdf->Cell(55,10,"=======================================================",0,0,'C');
	    }
	    else
	    {
	    	$this->fpdf->Ln(5);
		    $this->fpdf->Cell(45,10,"COMMISSION (HTG)",0,0,'L');
		    $this->fpdf->Cell(55,10,number_format(0, 2, ".", " "),0,0,'L');
		    $this->fpdf->Ln(5);
		    $this->fpdf->Cell(55,10,"=======================================================",0,0,'C');
	    }
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(45,10,"BALANCE COURANTE (HTG)",0,0,'L');
	    $this->fpdf->Cell(55,10,number_format($balance, 2, ".", " "),0,0,'L');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"=======================================================",0,0,'C');
	    $this->fpdf->Ln(5);
	    $this->fpdf->SetFont('Courier','B',8);
	    $this->fpdf->Cell(60,10,"Aucun retour n'est accepte",0,0,'C');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(60,10,"Nous vous remercions pour votre fidelite",0,0,'C');
		$this->fpdf->Ln(10);
		$this->fpdf->SetFont('Courier','B',11);
	    $this->fpdf->Cell(60,10,"EPI D'OR",0,0,'R');
	    $this->fpdf->Ln(20);
	}

	private function bodyRetrait($retrait, $balance)
	{
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"DESC : ".$retrait[0]["description"],0,0,'L');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"MONTANT (HTG) : ".number_format($retrait[0]["debit"],2,","," "),0,0,'L');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"=====================================================",0,0,'C');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"BALANCE COURANTE (HTG) : ".number_format($balance,2,","," "),0,0,'L');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"=====================================================",0,0,'C');
	    $this->fpdf->Ln(5);
	    $this->fpdf->SetFont('Courier','B',8);
	    $this->fpdf->Cell(60,10,"Aucun retour n'est accepte",0,0,'C');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(60,10,"Nous vous remercions pour votre fidelite",0,0,'C');
		$this->fpdf->Ln(10);
		$this->fpdf->SetFont('Courier','B',11);
	    $this->fpdf->Cell(60,10,"EPI D'OR",0,0,'R');
	    $this->fpdf->Ln(20);
	}

	private function bodyRetraitCommercant($retrait, $balance)
	{
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"DESC : ".$retrait[0]["description"],0,0,'L');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"MONTANT (HTG) : ".number_format($retrait[0]["debit"],2,","," "),0,0,'L');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"=====================================================",0,0,'C');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"BALANCE COURANTE (HTG) : ".number_format($balance,2,","," "),0,0,'L');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(55,10,"=====================================================",0,0,'C');
	    $this->fpdf->Ln(5);
	    $this->fpdf->SetFont('Courier','B',8);
	    $this->fpdf->Cell(60,10,"Aucun retour n'est accepte",0,0,'C');
	    $this->fpdf->Ln(5);
	    $this->fpdf->Cell(60,10,"Nous vous remercions pour votre fidelite",0,0,'C');
	    $this->fpdf->Ln(20);
	    $this->fpdf->Cell(60,10,"SIGNATURE CLIENT : ___________________________",0,0,'C');
		$this->fpdf->Ln(20);
		$this->fpdf->SetFont('Courier','B',11);
	    $this->fpdf->Cell(60,10,"EPI D'OR",0,0,'R');
	    $this->fpdf->Ln(20);
	}
}

