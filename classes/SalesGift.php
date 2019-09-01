<?php 


use setasign\Fpdi\Fpdi;

class SalesGift extends Sales {

	public function get_voucher($gift=false){

		if(!$gift) return false;

		define('FPDF_FONTPATH',PATH.'fonts'.DS);
		require_once PATH.'vendor/autoload.php';



		$pdf = new Fpdi();
		$pdf->setSourceFile(PATH.'assets/voucher.pdf'); 
		$tplidx = $pdf->importPage(1); 

		$pdf->addPage();
		$pdf->useTemplate($tplidx,0,0,238,423,true);

		$pdf->AddFont('ProximaNormal','','proxima-nova-600.php');
		$pdf->AddFont('ProximaBold','','proxima-nova-700.php');

		$pdf->SetFont('ProximaBold','',30); 
		$pdf->SetTextColor(1,0,0);

		$pdf->SetXY(15, 118);
		$pdf->Cell(208,20,utf8_decode($gift->to_user->name.' '.$gift->to_user->lastname),0,0,'C',false);

		$pdf->SetFont('ProximaBold','',20);
		$pdf->SetXY(15, 165);
		$pdf->MultiCell(208,10,utf8_decode($gift->promo->title),0,'C',false);


		$pdf->SetXY(15, 210);
		$pdf->Cell(208,20,$gift->sale->collection_id,0,0,'C',false);


		$pdf->SetFont('ProximaNormal','',18);
		$pdf->SetXY(15, 260);
		$pdf->Cell(208,20,utf8_decode($gift->promo->clientname),0,0,'C',false);
		
		$pdf->SetFont('ProximaNormal','',14);
		$pdf->SetXY(15, 270);
		$pdf->Cell(208,20,utf8_decode($gift->stores->address.' '.$gift->stores->city),0,0,'C',false);
		
		$pdf->SetXY(15, 280);
		$pdf->Cell(208,20,(empty($gift->stores->phones) ? '' : 'Tel: '.$gift->stores->phones.' ').(empty($gift->stores->whatsapp) ? '' : '- Whatsapp: '.$gift->stores->whatsapp),0,0,'C',false);

		return $pdf->Output('D','estilospa.com_voucher_'.$gift->sale->collection_id.'.pdf');
		//return $pdf->Output();


	}

	public function add_download($giftid=0){
		$this->_db->query(
			"UPDATE {sales_gift} 
			SET downloads=downloads+1 
			WHERE id=?",
			array($giftid)
		);
		return true;
	}

}