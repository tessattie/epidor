<?php

class Home extends Controller
{
	protected $utilisateurs;

	protected $from;

	protected $to; 

	protected $PHPExcel;

	protected $reader;

	private $sheet;

	protected $writter;

	public function __construct()
	{
		parent::__construct();
		require_once ROOT_DIRECTORY.'/app/vendors/PHPExcel/Classes/PHPExcel.php';
		$fileName = ROOT_DIRECTORY.'/tmp/commentaires_epidor.xlsx';
		$inputFileType = PHPExcel_IOFactory::identify($fileName);
		$this->reader = PHPExcel_IOFactory::createReader($inputFileType);
		$this->PHPExcel = $this->reader->load($fileName);
		$this->PHPExcel->setActiveSheetIndex(0);
		$this->sheet = $this->PHPExcel->getActiveSheet();
	} 
	
	public function index()
	{
		$this->view('home/index');
	}

	public function saveComment()
	{
		$highestRow = $this->sheet->getHighestRow() + 1;
		$this->sheet->setCellValue('A'.$highestRow, $_POST['ident']); 
		$this->sheet->setCellValue('B'.$highestRow, $_POST['firstname']); 
		$this->sheet->setCellValue('C'.$highestRow, $_POST['lastname']); 
		$this->sheet->setCellValue('D'.$highestRow, "https://www.espace-epidor.com/" . $_POST['url']); 
		$this->sheet->setCellValue('E'.$highestRow, $_POST['comment']); 
		$this->sheet->setCellValue('F'.$highestRow, date("Y-m-d H:i:s")); 
		$styleArray = array( 'borders' => array( 'allborders' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'), ), ), ); 
				$this->sheet->getStyle('A'.$highestRow.':F'.$highestRow)->applyFromArray($styleArray);
		$this->writter = PHPExcel_IOFactory::createWriter($this->PHPExcel, 'Excel2007');
		$this->writter->save(ROOT_DIRECTORY.'/tmp/commentaires_epidor.xlsx');
		die();
	}

	public function create()
	{
		$this->utilisateurs->createView();
	}

	public function setTo()
	{
		if($_POST['to'] < $_SESSION['from'])
		{

		}
		else
		{
			$_SESSION['to'] = $_POST['to'];
		}
		echo $_POST['to'];
		die();
	}

	public function setFrom()
	{
		if(!empty($_SESSION['to']) && $_POST['from'] > $_SESSION['to'])
		{

		}
		else
		{
			$_SESSION['from'] = $_POST['from'];
		}
		echo $_POST['from'];
		die();
	}

	public function resetClient()
	{
		unset($_SESSION['client']);
		header("Location:".DIRECTORY_NAME."/achats/nouveau");
	}

	public function resetDates()
	{
		unset($_SESSION['from']);
		unset($_SESSION['to']);
		header("Location:".DIRECTORY_NAME."/achats/nouveau");
	}
}