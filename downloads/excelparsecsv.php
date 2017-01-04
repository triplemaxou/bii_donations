<?php

ini_set('display_errors', '1');
if (isset($_GET["file"])) {
	$file = $_GET["file"];
	if (file_exists($file)) {
		$woExtension = explode(".", $file);
		$woExtension = $woExtension[0];
		$filecsv = $woExtension . ".csv";
		if (file_exists($filecsv)) {
			include 'PHPExcel/IOFactory.php';

			$objReader = PHPExcel_IOFactory::createReader('CSV');

// If the files uses a delimiter other than a comma (e.g. a tab), then tell the reader
//$objReader->setDelimiter("\t");
			// If the files uses an encoding other than UTF-8 or ASCII, then tell the reader
			//			$objReader->setInputEncoding('UTF-16LE');

			$objPHPExcel = $objReader->load($filecsv);
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save("$woExtension.xls");
//			echo "fichier excel créé";
//			readfile("$woExtension.xls");
			header("Location: $woExtension.xls");
		}
	}
}


?>