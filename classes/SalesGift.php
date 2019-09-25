<?php 


use setasign\Fpdi\Fpdi;

class SalesGift extends Sales {

	public function __construct(){}

	public function get_voucher($gift=false){

		if(!$gift) return false;

		define('FPDF_FONTPATH',PATH.'fonts'.DS);
		require_once PATH.'vendor/autoload.php';

		$pdf = new Fpdi();		
		$pdf->setSourceFile(PATH.'assets/voucher-regalo.pdf');
		$tplidx = $pdf->importPage(1); 

		$pdf->addPage();
		$pdf->useTemplate($tplidx,0,0,210,297,true);

		$pdf->AddFont('ProximaNormal','','proxima-nova-600.php');
		$pdf->AddFont('ProximaBold','','proxima-nova-700.php');
		$pdf->AddFont('Oleo','','oleo-script-swash-caps.php');

		$pdf->SetFont('Oleo','',22); 
		$pdf->SetTextColor(247,170,172);
		
		/// To
		$pdf->SetXY(40,35);
		$pdf->Cell(150,10,utf8_decode($gift->to_user->name.' '.$gift->to_user->lastname),0,0,'L',false);
		
		/// From
		$pdf->SetXY(40,45);
		$pdf->Cell(150,10,utf8_decode($gift->from_user->name.' '.$gift->from_user->lastname),0,0,'L',false);
		
		/// Message
		$pdf->SetTextColor(90,90,90);
		$pdf->SetFont('ProximaNormal','',15); 
		$pdf->SetXY(18,59);
		$pdf->MultiCell(180,6,utf8_decode($gift->message),0,'L',false);
		$pdf->Ln();



		$pdf->SetFont('ProximaBold','',16);
		$pdf->SetTextColor(0,0,0);
		$pdf->SetXY(21,174);
		$pdf->MultiCell(180,6,utf8_decode($gift->promo->title),0,'L',false);
		


		$pdf->SetFont('ProximaNormal','',10); 
		$pdf->SetTextColor(0,0,0);
		$pdf->SetXY(21,181);
		$pdf->MultiCell(180,4,utf8_decode($gift->promo->includes),0,'L',false);
		$pdf->Ln();


		$pdf->SetFont('ProximaNormal','',12);
		$pdf->SetTextColor(255,255,255);
		$pdf->SetXY(21,220.1);
		$pdf->Cell(180,6,utf8_decode('Voucher N° ').$gift->sale->collection_id,0,0,'L',false);

		$pdf->SetFont('ProximaNormal','',10);
		$pdf->SetXY(21,225.5);
		$added = new DateTime($gift->sale->added);
		$pdf->Cell(180,6,utf8_decode('válido hasta 30 días a partir del ').$added->format('d/m/Y'),0,0,'L',false);



		$pdf->SetFont('ProximaNormal','',13);
		$pdf->SetXY(21, 232.5);
		$pdf->Cell(180,6,utf8_decode($gift->promo->clientname.' - '.$gift->stores->address.' '.$gift->stores->city),0,0,'L',false);

		$pdf->SetFont('ProximaNormal','',11);
		$pdf->SetXY(21, 238);
		$pdf->Cell(180,6,'Realizar la reserva del turno al '.(empty($gift->stores->phones) ? '' : 'Tel: '.$gift->stores->phones.' ').(empty($gift->stores->whatsapp) ? '' : '- Whatsapp: '.$gift->stores->whatsapp),0,0,'L',false);



		//Image 
		$img = IMG.'glossary/aumento-gluteos-8608.jpg';
		if(file_exists($img)){
			$image_size = getimagesize($img);
			$wd = 80*$image_size[0]/$image_size[1];
			$left = (210-$wd)/2;
			$pdf->Image($img,$left,88,0,80);
		}else{
			$pdf->Image(PATH.'assets/balloons.jpg',64,88,80);
		}
		

		//return $pdf->Output('D','estilospa.com_voucher_'.$gift->sale->collection_id.'.pdf');
		return $pdf->Output();


	}

	public function generate_voucher(){}
	public function generate_voucher_gift(){}



	public function add_download($voucherid=0){
		$this->_db->query(
			"UPDATE {sales_vouchers} 
			SET downloads=downloads+1 
			WHERE id=?",
			array($voucherid)
		);
		return true;
	}

}