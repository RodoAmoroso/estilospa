<?php 


use setasign\Fpdi\Fpdi;

class SalesVouchers extends Sales{

	private $_pdf,
					$_voucher;


	public function voucher($voucher=null){

		if(is_null($voucher)) return false;

		$this->_voucher = $voucher;

		if(!parent::find($voucher->saleid)) return false;
		$voucher->sale = parent::data();
		
		$User = new User();
		$User->find($voucher->sale->iduser);
		$voucher->user = $User->data();

		if($voucher->sale->iduser != $voucher->user->id) return false;


		$Promos = new Promos();
		if(!$Promos->find($voucher->sale->idpromo)) return false;
		$voucher->promo = $Promos->data();

		$voucher->promo->gallery = json_decode($voucher->promo->gallery);


		$Stores = new Stores();
		if(!$Stores->get($voucher->sale->idclient)) return false;
		$voucher->stores = $Stores->data()[0];

		define('FPDF_FONTPATH',PATH.'fonts'.DS);
		require_once PATH.'vendor/autoload.php';

		$this->_pdf = new Fpdi();	

		$this->_pdf->AddFont('ProximaNormal','','proxima-nova-600.php');
		$this->_pdf->AddFont('ProximaBold','','proxima-nova-700.php');
		$this->_pdf->AddFont('Oleo','','oleo-script-swash-caps.php');


		if($voucher->gift){
			$this->generate_voucher_gift($voucher);
		}else{
			$this->generate_voucher($voucher);
		}



		$this->_pdf->SetFont('ProximaBold','',16);
		$this->_pdf->SetTextColor(0,0,0);
		$this->_pdf->SetXY(21,179);
		$this->_pdf->MultiCell(180,6,utf8_decode($voucher->promo->title),0,'L',false);
		


		$this->_pdf->SetFont('ProximaNormal','',10); 
		$this->_pdf->SetTextColor(0,0,0);
		$this->_pdf->SetXY(21,186);
		$this->_pdf->MultiCell(180,4,utf8_decode($voucher->promo->includes),0,'L',false);
		$this->_pdf->Ln();


		$this->_pdf->SetFont('ProximaNormal','',12);
		$this->_pdf->SetTextColor(255,255,255);
		$this->_pdf->SetXY(21,220.1);
		$this->_pdf->Cell(180,6,utf8_decode('Voucher N° ').$voucher->sale->merchant_order_id.'-'.$voucher->id,0,0,'L',false);

		$this->_pdf->SetFont('ProximaNormal','',10);
		$this->_pdf->SetXY(21,225.5);
		$added = new DateTime($voucher->sale->added);
		$this->_pdf->Cell(180,6,utf8_decode('válido hasta 30 días a partir del ').$added->format('d/m/Y'),0,0,'L',false);



		$this->_pdf->SetFont('ProximaNormal','',13);
		$this->_pdf->SetXY(21, 232.5);
		$this->_pdf->Cell(180,6,utf8_decode($voucher->promo->clientname.' - '.$voucher->stores->address.' '.$voucher->stores->city),0,0,'L',false);

		$this->_pdf->SetFont('ProximaNormal','',11);
		$this->_pdf->SetXY(21, 238);
		$this->_pdf->Cell(180,6,'Realizar la reserva del turno al '.(empty($voucher->stores->phones) ? '' : 'Tel: '.$voucher->stores->phones.' ').(empty($voucher->stores->whatsapp) ? '' : '- Whatsapp: '.$voucher->stores->whatsapp),0,0,'L',false);

		///return $this->_pdf->Output('D','estilospa.com_voucher_'.$gift->sale->collection_id.'.pdf');
		return $this->_pdf->Output();
		//return true;
	}

	public function generate_voucher($voucher){

		$this->_pdf->setSourceFile(PATH.'assets/voucher.pdf');
		$tplidx = $this->_pdf->importPage(1); 

		$this->_pdf->addPage();
		$this->_pdf->useTemplate($tplidx,0,0,210,297,true);

		$img = $voucher->promo->gallery[0]->photoname.'-o.'.$voucher->promo->gallery[0]->extension;

		$imgw = 100;

		if(file_exists(IMG.'promos/'.$img)){
			$image_size = getimagesize(IMG.'promos/'.$img);
			$wd = $imgw*$image_size[0]/$image_size[1];
			$left = (210-$wd)/2;
			$this->_pdf->Image(IMG.'promos/'.$img,$left,60,0,$imgw);
		}else{
			$this->_pdf->Image(PATH.'assets/balloons.jpg',64,88,$imgw);
		}
		

	}

	public function generate_voucher_gift($voucher){

		$this->_pdf->setSourceFile(PATH.'assets/voucher-regalo.pdf');
		$tplidx = $this->_pdf->importPage(1); 

		$this->_pdf->addPage();
		$this->_pdf->useTemplate($tplidx,0,0,210,297,true);

		$this->_pdf->SetFont('Oleo','',22); 
		$this->_pdf->SetTextColor(247,170,172);
		
		/// To
		$this->_pdf->SetXY(40,35);
		$this->_pdf->Cell(150,10,utf8_decode($voucher->to_user),0,0,'L',false);
		
		/// From
		$this->_pdf->SetXY(40,45);
		$this->_pdf->Cell(150,10,utf8_decode($voucher->user->name.' '.$voucher->user->lastname),0,0,'L',false);
		
		/// Message
		$this->_pdf->SetTextColor(90,90,90);
		$this->_pdf->SetFont('ProximaNormal','',15); 
		$this->_pdf->SetXY(18,59);
		$this->_pdf->MultiCell(180,6,utf8_decode($voucher->message),0,'L',false);
		$this->_pdf->Ln();




		//Image 		
		if(!is_null($voucher->image) && file_exists($voucher->image->path)){
			$image_size = getimagesize($voucher->image->path);
			$wd = 80*$image_size[0]/$image_size[1];
			$left = (210-$wd)/2;
			$this->_pdf->Image($voucher->image->path,$left,88,0,80);
		}else{
			$this->_pdf->Image(PATH.'assets/balloons.jpg',64,88,80);
		}

	}


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