<?
error_reporting(E_ALL);
include("include/dbconfig.php");
require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/Writer/Excel2007.php';

$conn = mysql_connect($dbhost,$dbuser,$dbpassword) or die ("cannot connect host! ");
$db = mysql_select_db($database) or die ("cannot connect database! ");

$sql = "select t.idtower AS idtower,i.no_imb AS no_imb,IF(i.tgl_imb='0000-00-00','',DATE_FORMAT(i.tgl_imb,'%d-%m-%Y')) AS tgl_imb,TRIM(REPLACE(t.lokasi,'\n','')) AS lokasi,t.kode_lokasi AS kode_lokasi,k.kecamatan AS kecamatan,n.nagari AS nagari,t.tinggi,p.nama AS pemilik_tower from tower t left join izin i on t.idizin=i.idizin LEFT JOIN pemilik p ON t.idpemilik=p.idpemilik left join kecamatan k on t.idkec=k.idkec left join nagari n on t.idnagari=n.idnagari order by t.idpemilik,t.idtower";
$result = mysql_query($sql);

$excel = new PHPExcel();
$excel->setActiveSheetIndex(0);
$worksheet = $excel->getActiveSheet();

$worksheet->SetCellValue('A1', 'DATA TOWER');
$worksheet->SetCellValue('A2', 'NO');
$worksheet->SetCellValue('B2', 'NOMOR IMB');
$worksheet->SetCellValue('C2', 'TANGGAL IMB');
$worksheet->SetCellValue('D2', 'LOKASI');
$worksheet->SetCellValue('E2', 'NAGARI');
$worksheet->SetCellValue('F2', 'KECAMATAN');
$worksheet->SetCellValue('G2', 'TINGGI');
$worksheet->SetCellValue('H2', 'NAMA PEMILIK');

$worksheet->mergeCells('A1:H1');

$number = mysql_num_rows($result) + 2;
$no = 1;

for ( $i=3; $i<=$number; $i++ ) {
	$rows = mysql_fetch_array($result);
	$worksheet->SetCellValue('A'.$i,$no);
	$worksheet->SetCellValue('B'.$i,($rows['no_imb']));
	$worksheet->SetCellValue('C'.$i,($rows['tgl_imb']));
	$worksheet->SetCellValue('D'.$i,($rows['lokasi']." ".$rows['kode_lokasi']));
	$worksheet->SetCellValue('E'.$i,($rows['kecamatan']));
	$worksheet->SetCellValue('F'.$i,($rows['nagari']));
	$worksheet->SetCellValue('G'.$i,($rows['tinggi']));
	$worksheet->SetCellValue('H'.$i,($rows['pemilik_tower']));
	$no++;
}

$excel->getActiveSheet()->setShowGridLines(false);
$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

$worksheet->getStyle('A1:H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$worksheet->getStyle('A1:H2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$headerStyle = array(
 'font' => array(
	'name' => 'Calibri',
	'size' => 16,
	'bold' => true
 )
);
$worksheet->getStyle('A1:H1')->applyFromArray($headerStyle);

$headerTableStyle = array(
	'font' => array(
		'name' => 'Calibri',
		'size' => 11,
		'bold' => true
	),
	'borders' => array(
		'allborders' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN
		)
	)
);
$worksheet->getStyle('A2:H2')->applyFromArray($headerTableStyle);

$contentStyle = array(
	'font' => array(
		'name' => 'Calibri',
		'size' => 11
	),
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
		'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		'wrap'       => true
	),
	'borders' => array(
		'allborders' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN
		)
    )
);
$worksheet->getStyle('A3:H'.$number)->applyFromArray($contentStyle);

$contentAStyle = array(
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
	)
);
$worksheet->getStyle('A3:A'.$number)->applyFromArray($contentAStyle);

$contentGStyle = array(
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
	)
);
$worksheet->getStyle('G3:G'.$number)->applyFromArray($contentGStyle);

/*$contentBStyle = array(
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
		'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		'wrap'       => true
	),
	'borders' => array(
		'left' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN
		),
		'right' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN
		)
	)
);
$worksheet->getStyle('B8:B'.$maxrow)->applyFromArray($contentBStyle);
$worksheet->getStyle('C8:C'.$maxrow)->applyFromArray($contentBStyle);
$worksheet->getStyle('D8:D'.$maxrow)->applyFromArray($contentBStyle);
$worksheet->getStyle('G8:G'.$maxrow)->applyFromArray($contentBStyle);
$worksheet->getStyle('H8:H'.$maxrow)->applyFromArray($contentBStyle);
$contentCStyle = array(
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
		'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		'wrap'       => true
	),
	'borders' => array(
		'left' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN
		),
		'right' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN
		)
	)
);
$worksheet->getStyle('E8:E'.$maxrow)->applyFromArray($contentCStyle);
$worksheet->getStyle('F8:F'.$maxrow)->applyFromArray($contentCStyle);

$worksheet->getStyle('E8:F'.$maxrow)->getNumberFormat()->setFormatCode('#,##0;[Red](#,##0)');
$worksheet->getStyle('G8:G'.$maxrow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DMYSLASH);

$summaryStyle = array(
	'font' => array(
		'name' => 'Arial',
		'size' => 9,
		'bold' => true
	),
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
		'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		'wrap'       => true
	),
	'borders' => array(
		'allborders' => array(
		  'style' => PHPExcel_Style_Border::BORDER_THIN
		)
	)
);
$worksheet->getStyle('A'.$summary.':H'.$summary)->applyFromArray($summaryStyle);
$worksheet->getStyle('F'.$summary)->getNumberFormat()->setFormatCode('#,##0;[Red](#,##0)');

$ttdStyle = array(
	'font' => array(
		'name' => 'Arial',
		'size' => 9,
		'bold' => true
	),
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		'wrap'       => true
	)
);
$worksheet->getStyle('E'.$tgl.':G'.$nip)->applyFromArray($ttdStyle);*/

$worksheet->getColumnDimension('A')->setWidth(5);
$worksheet->getColumnDimension('B')->setWidth(25);
$worksheet->getColumnDimension('C')->setWidth(15);
$worksheet->getColumnDimension('D')->setWidth(30);
$worksheet->getColumnDimension('E')->setWidth(20);
$worksheet->getColumnDimension('F')->setWidth(20);
$worksheet->getColumnDimension('G')->setWidth(10);
$worksheet->getColumnDimension('H')->setWidth(20);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="laporan_datatower.xlsx"');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$objWriter->save('php://output');

//$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
//$objWriter->save('tes.xls');
//$objWriter->save('php://output');
$excel->disconnectWorksheets();
unset($excel);

?>