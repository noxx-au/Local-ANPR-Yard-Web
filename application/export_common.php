<?php
	require(APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php');
 	$objPHPExcel = new PHPExcel();


    $objDrawing = new PHPExcel_Worksheet_Drawing();

    $objDrawing->setPath($server_root_path . '/assets/frontend/images/noXx-logo.png');
    $objDrawing->setCoordinates('A2');

    $objDrawing->setOffsetX(20);
    $objDrawing->setOffsetY(80);

    $objDrawing->setWidth(80);
    $objDrawing->setHeight(75);
    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

    $default_border = array(

        'font' => array(
            'size' => 11,
            'bold' => true,
            'name' => 'Cambria',
            'color' => array('rgb' => 'FFFFFFFF'),
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        )
    );
    $FontStyle = array(
        'font' => array(
            'size' => 12,
            'name' => 'Cambria',
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        )
    );

    $HeaderNameStyle = array(
        'font' => array(
            'bold' => true,
            'color' => array('argb' => 'FF000000'),
            'size' => 26,
            'name' => 'Cambria',
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        )
    );
    $rightStyle = array(
        'borders' => array(
            'right' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            ),
        )
    );
    $bottomStyle = array(
        'borders' => array(
            'bottom' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        )
    );
?>