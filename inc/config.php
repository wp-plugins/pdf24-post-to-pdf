<?php

/*
 * DO NOT CHANGE THESE SETTINGS IF YOU DO NOT KNOW WHAT YOU DO.
 */

//default language
global $pdf24Plugin;

//default language
$pdf24Plugin['defaultLang'] = 'en';

//Priority to execute at last
$pdf24Plugin['contentFilterPriority'] = 999999;

//the default filter used to encode values
$pdf24Plugin['defaultFilter'] = 'base64';

//Url to pdf24.org service
$pdf24Plugin['serviceUrl'] = 'https://doc2pdf.pdf24.org/wordpress.php';

//target name for opened windows
$pdf24Plugin['targetName'] = 'pdf24PopWin';

//js code to open the target window
$pdf24Plugin['jsOpenTargetWin'] = "var pdf24Win = window.open('about:blank', 'pdf24PopWin', 'resizable=yes,scrollbars=yes,width=500,height=250,left='+(screen.width/2-250)+',top='+(screen.height/3-125)+''); pdf24Win.focus();";

//js code to do a callback when creating a PDF
$pdf24Plugin['jsCallbackCode'] = "if(typeof pdf24OnCreatePDF === 'function'){void(pdf24OnCreatePDF(this,pdf24Win));}";

//Document sizes of created PDF
$pdf24Plugin['docSizes'] = array(
	'A0' =>	'841x1189',
	'A1' => '594x841',
	'A2' => '420x594',
	'A3' => '297x420',
	'A4' => '210x297',
	'A5' => '148x210',
	
	'B0' =>	'1000x1414',
	'B1' =>	'707x1000',
	'B2' =>	'500x707',
	'B3' =>	'353x500',
	'B4' =>	'250x353',
	'B5' =>	'176x250',
	
	'C0' =>	'917x1297',
	'C1' =>	'648x917',
	'C2' =>	'458x648',
	'C3' =>	'324x458',
	'C4' =>	'229x324',
	'C5' =>	'162x229',
	
	'default' => 'A4'
);

//Document orientations
$pdf24Plugin['docOrientations'] = array(
	'portrait' => 'Portrait',
	'landscape' => 'Landscape',	
	'default' => 'portrait'
);

//default disabled on
$pdf24Plugin['defaultDisabledOn'] = array(
	'cp' => '',
	'sbp' => '',
	'tbp' => '',
	'lp' => ''
);

//Url for pdf24.org link query
$pdf24Plugin['linkQueryUrl'] = 'http://wordpress.pdf24.org/products/online-pdf-converter/plugins/wordpress/links';

?>