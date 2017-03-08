<?php 
class export extends Controller{

	protected $clients;

	protected $PHPExcel;

	protected $status;

	private $sheet;

	private $today;

	private $month;

	public function __construct()
	{
		parent::__construct();
		$this->PHPExcel = $this->excel();
		$this->today = date("Y-m-d");
		$this->month = array('01' => "Janvier", "02" => "Février", "03" => "Mars", "04" => "Avril", "05" => "Mai",
			'06' => "Juin", "07" => "Juillet", "08" => "Août", "09" => "Septembre", "10" => "Octobre","11" => "Novembre", "12" => "Décembre");
	} 

	public function clients()
	{
		$titles = array(
	        array('A1' => "CLIENT ID", 'B1' => "NOM", 'C1' => "PRENOM",
	           	  'D1' => 'NIF', 'E1' => "TELEPHONE", 'F1' => "PLAFOND (HTG)",
				  'G1' => "STATUT", 'H1' => "SOLDE (HTG)"
	        ));
		$myWorkSheet = new PHPExcel_Worksheet($this->PHPExcel, "CLIENTS"); 
		$this->PHPExcel->addSheet($myWorkSheet, 0);
		$this->sheet = $this->PHPExcel->getSheetByName("CLIENTS");
		$this->setFile("CLIENTS", $titles);
		$clients = $this->clients->getClients();
		$this->setReport($clients);
		$this->save_file("SYNTHESE_CLIENTS", $this->today);
		// header("Location:".DIRECTORY_NAME."/clients");
	}

	public function synthese($type = "")
	{
		if($_SESSION['role'] == "A")
		{
			header('Location:'.DIRECTORY_NAME.'/clients');
		}
		$titles = array(
	        array('A2' => "ID TRANSACTION", 'B2' => "CLIENT", 'C2' => "DESCRIPTION",
	           	  'D2' => 'DATE', 'E2' => "RESPONSABLE", 'F2' => "TOTAL (HTG)",
				  'G2' => "COMISSION", 'H2' => "DEBIT (HTG)"
	        ));

		if($type == "journaliere")
		{
			$from = $_SESSION['daily']." 00:00:00";
			$to = $_SESSION['daily']." 23:59:59";
		}

		if($type == "mensuelle")
		{
			$from = $_SESSION['monthly']['year']."-".$_SESSION['monthly']['month']."-"."01 00:00:00";
			$to = $_SESSION['monthly']['year']."-".$_SESSION['monthly']['month']."-".date('t')." 00:00:00";
		}

		if($type == "personnalise")
		{
			$from = $_SESSION['pardate']['from']." 00:00:00";
			$to = $_SESSION['pardate']['to']." 23:59:59";
		}

		
		$myWorkSheet = new PHPExcel_Worksheet($this->PHPExcel, "SYNTHESE"); 
		$this->PHPExcel->addSheet($myWorkSheet, 0);
		$this->sheet = $this->PHPExcel->getSheetByName("SYNTHESE");
		$this->setSyntheseFile("SYNTHESE ". strtoupper($type), $titles, $from, $to);
		if(!empty($_SESSION['client']))
		{
			$balance = $this->transaction->getClientBalance($_SESSION['client']['id'])['balance'];
			$transactions = $this->transaction->getTransactionsByClient($from, $to, $_SESSION['client']['id']);
		}
		else
		{
			$balance = $this->transaction->getTotalBalance()['balance'];
			$transactions = $this->transaction->getTransactions($from, $to);
		}
		$this->setSyntheseReport($transactions, $balance, $type);
		$this->save_file("SYNTHESE_".strtoupper($type), $this->today);
	}

	public function setReport($report)
	{
		if(!empty($report) && $report != null && $report != false && count($report) != 0)
		{
			$i = 2;
			for($j=0;$j<count($report);$j++)
			{
				if(isset($report[$i]['actif']))
				{
					if(empty($report[$i]['actif']))
					{
						$report[$i]['actif'] = 0;
					}
				}
				if(isset($report[$j]['status']))
				{
					$report[$j]['status'] = $this->status[$report[$j]['status']];
				}
				$data = array(
	            array('id' => $report[$j]["id"], 'lastname' => $report[$j]["lastname"], 'firstname' => $report[$j]["firstname"],
	            	  'NIF' => $report[$j]["NIF"], 'telephone' => "+509 ".$report[$j]["telephone"], 'plafond' => number_format($report[$j]["plafond"], 2, ",", " "),
					  'statut' => $report[$j]["status"], 'solde' => number_format($report[$j]["balance"], 2, ",", " ")
	        	));
				$this->sheet->fromArray($data, null, 'A'.$i, true);
				$this->sheet->getRowDimension($i)->setRowHeight(20);
				$i = $i + 1;
				}
				$i = $i-1;
				$this->sheet->getStyle('A2:H'.$i)->getFont()->setSize(8);
				$styleArray = array( 'borders' => array( 'allborders' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'), ), ), ); 
				$this->sheet->getStyle('A1:H'.$i)->applyFromArray($styleArray);
				$this->sheet->getStyle('A1:H'.$i) ->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$this->sheet->getStyle('A0:H'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}
	}


	public function setSyntheseReport($report, $balance, $type)
	{
		if(!empty($report) && $report != null && $report != false && count($report) != 0)
		{
			$i = 3;
			$comission = 0;
			$debit = 0;
			$total = 0;
			for($j=0;$j<count($report);$j++)
			{
				$comission = $comission + $report[$j]["comission"];
				$total = $total + $report[$j]["total"] + $report[$j]["total_cash"];
				$debit = $debit + $report[$j]["debit"];
				if($report[$j]["comission"] == null)
				{
					$report[$j]["comission"] = "-";
				}
				else
				{
					$report[$j]["comission"] = number_format($report[$j]["comission"], 2, ",", " ");
				}

				if($report[$j]["total"] == null)
				{
					$report[$j]["total"] = '-';
					if($report[$j]['total_cash'] == null)
					{
						$report[$j]["total_cash"] = "-";
					}
					else
					{
						$report[$j]["total"] =  number_format($report[$j]["total_cash"], 2, ",", " ");
					}
					
				}
				else
				{
					$report[$j]["total"] = number_format($report[$j]["total"], 2, ",", " ");
				}

				if($report[$j]["debit"] == null)
				{
					$report[$j]["debit"] = "-";
				}
				else
				{
					$report[$j]["debit"] = number_format($report[$j]["debit"], 2, ",", " ");
				}

				if($report[$j]["description"] == null)
				{
					$report[$j]["description"] = "-";
				}

	        	$data = array(
	            array('id' => $report[$j]["id"], 'client' => $report[$j]["client_firstname"] . " " . $report[$j]["client_lastname"], 'description' => $report[$j]["description"],
	            	  'date' => $report[$j]["date"], 'responsable' => $report[$j]["user_firstname"] . " " . $report[$j]["user_lastname"], 'total' => $report[$j]["total"],
					  'comission' => $report[$j]["comission"], 'debit' => $report[$j]["debit"]
	        	));
				$this->sheet->fromArray($data, null, 'A'.$i, true);
				$this->sheet->getRowDimension($i)->setRowHeight(20);
				$i = $i + 1;
			}
			$this->sheet->mergeCells('A'.$i.':E'.$i);
			$this->sheet->getStyle('A'.$i.':H'.$i)->getFont()->setSize(11);
			$this->sheet->getStyle('A'.$i.':H'.$i)->getFont()->setBold(true);
			$this->sheet->setCellValue("A".$i, "TOTAL (HTG)");
			$this->sheet->setCellValue("F".$i, number_format($total, 2, ",", " "));
			$this->sheet->setCellValue("G".$i, number_format($comission, 2, ",", " "));
			$this->sheet->setCellValue("H".$i, number_format($debit, 2, ",", " "));

			$this->sheet->getStyle('A2:H'.$i)->getFont()->setSize(8);
			$styleArray = array( 'borders' => array( 'allborders' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'), ), ), ); 
			$this->sheet->getStyle('A1:H'.$i)->applyFromArray($styleArray);
			$this->sheet->getStyle('A1:H'.$i) ->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->sheet->getStyle('A0:H'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}
	}

	private function setFile($report_name, $titles)
	{
		$today = date('Y-m-d H:i:s');
		$this->PHPExcel->createSheet();
		$this->PHPExcel->getActiveSheet()->getPageSetup()
			 ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$this->sheet->getRowDimension('1')->setRowHeight(25);
		$this->sheet->getStyle('A1:H1')->getFont()->setBold(true);
		$this->sheet->getStyle('A1:H1')->getFont()->setSize(11);

		// Set width for each column
		$this->sheet->getColumnDimension('A')->setWidth(20);
		$this->sheet->getColumnDimension('B')->setWidth(20);
		$this->sheet->getColumnDimension('C')->setWidth(30);
		$this->sheet->getColumnDimension('D')->setWidth(20);
		$this->sheet->getColumnDimension('E')->setWidth(20);
		$this->sheet->getColumnDimension('F')->setWidth(20);
		$this->sheet->getColumnDimension('G')->setWidth(20);
		$this->sheet->getColumnDimension('H')->setWidth(20);

		$this->PHPExcel->getActiveSheet()->setAutoFilter('A1:H1');

		// Set to printing layout 
		$this->PHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		$this->PHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
		$this->PHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
		$this->PHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);

		// Set header titles
		$this->sheet->fromArray($titles, null, 'A1',true);

		// Set file parameters 
		$this->PHPExcel->getProperties()->setCreator("Tess Attie"); 
		$this->PHPExcel->getProperties()->setLastModifiedBy($today); 
		$this->PHPExcel->getProperties()->setTitle("SYNTHESE ".$report_name); 
		$this->PHPExcel->getProperties()->setSubject("Office 2005 XLS Report Document"); 
		$this->PHPExcel->getProperties()->setDescription("Report document for Office 2005 XLS, generated using PHP classes."); 
		$this->PHPExcel->getProperties()->setKeywords("office 2005 openxml php"); 
		$this->PHPExcel->getProperties()->setCategory("Report result file");
		$this->sheet->getPageMargins()->setTop(0.2); 
		$this->sheet->getPageMargins()->setRight(0); 
		$this->sheet->getPageMargins()->setLeft(0); 
		$this->sheet->getPageMargins()->setBottom(0.2);
	}

	private function setSyntheseFile($report_name, $titles, $from, $to)
	{
		$today = date('Y-m-d H:i:s');
		$this->PHPExcel->createSheet();
		$this->PHPExcel->getActiveSheet()->getPageSetup()
			 ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$this->sheet->getRowDimension('1')->setRowHeight(25);
		$this->sheet->mergeCells('A1:H1');
		if($report_name == "SYNTHESE JOURNALIERE")
		{
			$this->sheet->setCellValue("A1", $report_name . " : " . $_SESSION['daily']);
		}
		if($report_name == "SYNTHESE MENSUELLE")
		{
			$this->sheet->setCellValue("A1", $report_name . " : " . $this->month[$_SESSION['monthly']['month']] . " " . $_SESSION['monthly']['year']);
		}
		if($report_name == "SYNTHESE PERSONNALISE")
		{
			$this->sheet->setCellValue("A1", $report_name . " : " . $_SESSION['pardate']['from'] . " au " . $_SESSION['pardate']['to']);
		}
		
		$this->sheet->getStyle('A1:H2')->getFont()->setBold(true);
		$this->sheet->getStyle('A1:H2')->getFont() ->setSize(11);

		// Set width for each column
		$this->sheet->getColumnDimension('A')->setWidth(15);
		$this->sheet->getColumnDimension('B')->setWidth(20);
		$this->sheet->getColumnDimension('C')->setWidth(30);
		$this->sheet->getColumnDimension('D')->setWidth(20);
		$this->sheet->getColumnDimension('E')->setWidth(20);
		$this->sheet->getColumnDimension('F')->setWidth(15);
		$this->sheet->getColumnDimension('G')->setWidth(15);
		$this->sheet->getColumnDimension('H')->setWidth(15);

		$this->PHPExcel->getActiveSheet()->setAutoFilter('A2:H2');

		// Set to printing layout 
		$this->PHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		$this->PHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
		$this->PHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
		$this->PHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);

		// Set header titles
		$this->sheet->fromArray($titles, null, 'A2',true);

		// Set file parameters 
		$this->PHPExcel->getProperties()->setCreator("Tess Attie"); 
		$this->PHPExcel->getProperties()->setLastModifiedBy($today); 
		$this->PHPExcel->getProperties()->setTitle("SYNTHESE ".$report_name); 
		$this->PHPExcel->getProperties()->setSubject("Office 2005 XLS Report Document"); 
		$this->PHPExcel->getProperties()->setDescription("Report document for Office 2005 XLS, generated using PHP classes."); 
		$this->PHPExcel->getProperties()->setKeywords("office 2005 openxml php"); 
		$this->PHPExcel->getProperties()->setCategory("Report result file");
		$this->sheet->getPageMargins()->setTop(0.2); 
		$this->sheet->getPageMargins()->setRight(0); 
		$this->sheet->getPageMargins()->setLeft(0); 
		$this->sheet->getPageMargins()->setBottom(0.2);
	}

	private function save_file($name, $date)
	{
		header('Content-Type: application/vnd.ms-excel'); 
		$client = null;
		if(!empty($_SESSION['client']))
		{
			$client = "_".$_SESSION['client']['firstname']."_".$_SESSION['client']['lastname']."_";
		}
		header('Content-Disposition: attachment;filename="'.$name.$client.$date.'.xls"'); 
		header('Cache-Control: max-age=0'); $objWriter = PHPExcel_IOFactory::createWriter($this->PHPExcel, 'Excel5'); 
		$objWriter->save('php://output');
	}
}