<?
error_reporting(E_ALL);
include("include/dbconfig.php");
require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/Writer/Excel2007.php';

$conn = mysql_connect($dbhost,$dbuser,$dbpassword) or die ("cannot connect host! ");
$db = mysql_select_db($database) or die ("cannot connect database! ");
$idpemilik = $_GET['idpemilik'];
$sql = "select t.idtower AS idtower,TRIM(REPLACE(t.lokasi,'\n','')) AS lokasi,TRIM(REPLACE(t.lokasi_sppt_pbb,'\n','')) AS lokasi_sppt_pbb,
t.kode_lokasi AS kode_lokasi,k.kecamatan AS kecamatan,ng.nagari AS nagari,p.nama AS pemilik_tower,n.njop_total,n.retribusi,n.retribusi AS ret,
IF(i.tgl_hbs_izin_ho='0000-00-00','',DATE_FORMAT(i.tgl_hbs_izin_ho,'%d-%m-%Y')) AS tgl_hbs_izin_ho 
from tower t left join izin i on t.idizin=i.idizin left join njop n on t.idnjop=n.idnjop LEFT JOIN pemilik p ON t.idpemilik=p.idpemilik
inner join kecamatan k on t.idkec=k.idkec inner join nagari ng on t.idnagari=ng.idnagari
order by t.idpemilik,t.idtower";

if($idpemilik > 0){
	$sql = "select t.idtower AS idtower,TRIM(REPLACE(t.lokasi,'\n','')) AS lokasi,TRIM(REPLACE(t.lokasi_sppt_pbb,'\n','')) AS lokasi_sppt_pbb,
	t.kode_lokasi AS kode_lokasi,k.kecamatan AS kecamatan,ng.nagari AS nagari,p.nama AS pemilik_tower,n.njop_total,n.retribusi,n.retribusi AS ret,
	IF(i.tgl_hbs_izin_ho='0000-00-00','',DATE_FORMAT(i.tgl_hbs_izin_ho,'%d-%m-%Y')) AS tgl_hbs_izin_ho 
	from tower t left join izin i on t.idizin=i.idizin left join njop n on t.idnjop=n.idnjop LEFT JOIN pemilik p ON t.idpemilik=p.idpemilik
	inner join kecamatan k on t.idkec=k.idkec inner join nagari ng on t.idnagari=ng.idnagari
	WHERE t.idpemilik = ".$idpemilik." 
	order by t.idpemilik,t.idtower";
}
$result = mysql_query($sql);

$excel = new PHPExcel();
$excel->setActiveSheetIndex(0);
$worksheet = $excel->getActiveSheet();

$worksheet->SetCellValue('A1', 'DAFTAR TAGIHAN RETRIBUSI PENGENDALIAN MENARA TELEKOMUNIKASI DI KABUPATEN TANAH DATAR');
$worksheet->SetCellValue('A2', 'SESUAI PERDA KAB.TANAH DATAR NO.12 TAHUN 2011');
$worksheet->SetCellValue('A5', 'NO');
$worksheet->SetCellValue('B5', 'LOKASI TOWER');
$worksheet->SetCellValue('D5', 'PEMILIK');
$worksheet->SetCellValue('E5', 'NJOP SESUAI');
$worksheet->SetCellValue('F5', 'JUMLAH');
$worksheet->SetCellValue('G5', 'JATUH TEMPO');
$worksheet->SetCellValue('H5', 'KETERANGAN');
$worksheet->SetCellValue('B6', 'YANG TERTERA PADA SPPT PBB');
$worksheet->SetCellValue('C6', 'SESUAI ALAMAT SEBENARNYA');
$worksheet->SetCellValue('E6', 'SPPT-PBB (Rp)');
$worksheet->SetCellValue('F6', 'RETRIBUSI (Rp)');
$worksheet->SetCellValue('A7', '1');
$worksheet->SetCellValue('B7', '2');
$worksheet->SetCellValue('C7', '3');
$worksheet->SetCellValue('D7', '4');
$worksheet->SetCellValue('E7', '5');
$worksheet->SetCellValue('F7', '6');
$worksheet->SetCellValue('G7', '7');
$worksheet->SetCellValue('H7', '8');

$worksheet->mergeCells('A1:H1');
$worksheet->mergeCells('A2:H2');
$worksheet->mergeCells('B5:C5');
$worksheet->mergeCells('A5:A6');
$worksheet->mergeCells('D5:D6');
$worksheet->mergeCells('G5:G6');
$worksheet->mergeCells('H5:H6');


$number = mysql_num_rows($result) + 7;
$no = 1;
$maxrow = 1;
$maxno = 1;
$total_ret = 0;
for ( $i=8,$j=8; $i<=$number; $i++,$j++ ) {
	$rows = mysql_fetch_array($result);
	$worksheet->SetCellValue('A'.$j,"");
	$worksheet->SetCellValue('B'.$j,"");
	$worksheet->SetCellValue('C'.$j,"");
	$worksheet->SetCellValue('D'.$j,"");
	$worksheet->SetCellValue('E'.$j,"");
	$worksheet->SetCellValue('F'.$j,"");
	$worksheet->SetCellValue('G'.$j,"");
	$worksheet->SetCellValue('H'.$j,"");
	$j++;
	$worksheet->SetCellValue('A'.$j,$no);
	$worksheet->SetCellValue('B'.$j,($rows['lokasi_sppt_pbb']."\n".$rows['nagari']."\n".$rows['kecamatan']));
	$worksheet->SetCellValue('C'.$j,($rows['lokasi']."\n".$rows['nagari']."\n".$rows['kecamatan']." ".$rows['kode_lokasi']));
	$worksheet->SetCellValue('D'.$j,($rows['pemilik_tower']));
	$worksheet->SetCellValue('E'.$j,($rows['njop_total']));
	$total_ret += $rows['retribusi'];
	$worksheet->SetCellValue('F'.$j,($rows['retribusi']));
	$worksheet->SetCellValue('G'.$j,($rows['tgl_hbs_izin_ho']));
	$worksheet->SetCellValue('H'.$j,"");
	$no++;
	$maxrow = $j;
	$maxno = $no;
}
$maxno--;
$summary = $maxrow+1;
$worksheet->SetCellValue('A'.$summary, '');
$worksheet->SetCellValue('B'.$summary, 'Jumlah Total point 1 s/d '.$maxno);
$worksheet->SetCellValue('F'.$summary, $total_ret);
$worksheet->SetCellValue('G'.$summary, '');
$worksheet->SetCellValue('H'.$summary, '');
$worksheet->mergeCells('B'.$summary.':E'.$summary);

$tgl = $summary + 2;
$worksheet->SetCellValue('E'.$tgl, 'Batusangkar, '.date('d F Y'));
$worksheet->mergeCells('E'.$tgl.':G'.$tgl);

$kepala = $tgl + 2;
$worksheet->SetCellValue('E'.$kepala, 'KEPALA DISHUBKOMINFO');
$worksheet->mergeCells('E'.$kepala.':G'.$kepala);

$kab = $kepala + 1;
$worksheet->SetCellValue('E'.$kab, 'KABUPATEN TANAH DATAR');
$worksheet->mergeCells('E'.$kab.':G'.$kab);

$nama = $kab + 3;
$worksheet->SetCellValue('E'.$nama, 'Ir. DARYANTO SABIR, M.Si');
$worksheet->mergeCells('E'.$nama.':G'.$nama);

$nip = $nama + 1;
$worksheet->SetCellValue('E'.$nip, 'Nip. 19610118 198903 1 003');
$worksheet->mergeCells('E'.$nip.':G'.$nip);

$note = $nip + 2;
/*$worksheet->SetCellValue('B'.$note, 'CATATAN:   JUMLAH  RETRIBUSI = NILAI NJOP SPPT-PBB x 2 %');
$worksheet->mergeCells('B'.$note.':G'.$note);*/

$excel->getActiveSheet()->setShowGridLines(false);
$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

$worksheet->getStyle('A1:H7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$worksheet->getStyle('A1:H7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$headerStyle = array(
 'font' => array(
	'name' => 'Arial',
	'size' => 12,
	'bold' => true
 )
);
$worksheet->getStyle('A1:H2')->applyFromArray($headerStyle);
$headerTableStyle = array(
 'font' => array(
	'name' => 'Arial',
	'size' => 9,
	'bold' => true
 ),
 'borders' => array(
	'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
 )
);
$headerTableStyle2 = array(
 'font' => array(
	'name' => 'Arial',
	'size' => 9,
	'bold' => true
 ),
 'borders' => array(
	'right' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
 )
);
$worksheet->getStyle('A5:D6')->applyFromArray($headerTableStyle);
$worksheet->getStyle('E5:E6')->applyFromArray($headerTableStyle2);
$worksheet->getStyle('F5:F6')->applyFromArray($headerTableStyle2);
$worksheet->getStyle('G5:H6')->applyFromArray($headerTableStyle);
$worksheet->getStyle('A7:H7')->applyFromArray($headerTableStyle);
$worksheet->getStyle('A4:H4')->applyFromArray(
	array(
		'borders' => array(
			'bottom' => array(
			  'style' => PHPExcel_Style_Border::BORDER_THIN
			)
		 )
	)
);
$worksheet->getStyle('A8:H8')->applyFromArray(
	array(
		'borders' => array(
			'top' => array(
			  'style' => PHPExcel_Style_Border::BORDER_THIN
			)
		 )
	)
);

$contentStyle = array(
 'font' => array(
	'name' => 'Arial',
	'size' => 9
 )
);
$worksheet->getStyle('A8:H'.$maxrow)->applyFromArray($contentStyle);
$contentAStyle = array(
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
$worksheet->getStyle('A8:A'.$maxrow)->applyFromArray($contentAStyle);
$contentBStyle = array(
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
$worksheet->getStyle('E'.$tgl.':G'.$nip)->applyFromArray($ttdStyle);

$worksheet->getColumnDimension('A')->setWidth(5);
$worksheet->getColumnDimension('B')->setWidth(32);
$worksheet->getColumnDimension('C')->setWidth(32);
$worksheet->getColumnDimension('D')->setWidth(10);
$worksheet->getColumnDimension('E')->setWidth(18);
$worksheet->getColumnDimension('F')->setWidth(18);
$worksheet->getColumnDimension('G')->setWidth(15);
$worksheet->getColumnDimension('H')->setWidth(15);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="laporan_retribusi.xlsx"');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$objWriter->save('php://output');

$excel->disconnectWorksheets();
unset($excel);

?>