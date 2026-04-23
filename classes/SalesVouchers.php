<?php
use setasign\Fpdi\Fpdi;

class SalesVouchers extends Core{

	protected 	$table='sales_vouchers',
							$sizes=['small'=>'','big'=>''],
							$folder='gift',
							$alias='sv';

	private 		$_pdf,
							$_voucher,
							$_filters;


	public function search_filters(){

		$this->_filters = parent::core_filters([
			'filters'=>[
				'id'=>"{$this->alias}.id=?",
				'exclude'=>"{$this->alias}.id!=?",
				'ids'=>"{$this->alias}.id IN ($)",
				
				'sale'=>"{$this->alias}.saleid=?"

			],
			'search'=>[
				"{$this->alias}.message"
			]
		]);

	}


	public function get(){
		$query = $this->set_query("{$this->alias}.*");
		if(!$data = parent::core_get($query,$this->_filters->values)) return false;
		foreach($data as $k=>$row){
			if($row->image) $data[$k]->image->path = IMG.$this->folder.DS.$row->image->f.'.'.$row->image->e;
		}
		return $data;
	}
	public function get_total(){
		$query = $this->set_query("COUNT({$this->alias}.id) total");
		$this->_db->query($query,$this->_filters->values);
		return $this->_db->first()->total;
	}
	protected function set_query($selects="*"){
		$this->search_filters();
		return "
			SELECT {$selects}
			FROM {{$this->table}} {$this->alias}
			{$this->_filters->where}
			{$this->_filters->sort}
			LIMIT {$this->limit}";
	}

	public function find($id=null){
		if(!$id) return false;
		$this->filters = ['id'=>$id];
		if(!$data = $this->get()) return false;
		return $data[0];
	}

	public function save(){	

		$values = [
			'saleid'=>Input::get('saleid'),
			'gift'=>Input::get('gift')
		];
		$values['to_user'] = Input::get('gift') ? Input::get('to_user','xss') : null;
		$values['message'] = Input::get('gift') ? Input::get('message','xss') : null;
		$values['image'] = Input::get('gift') ? Input::get('image','json|nullable') : null;		

		if(!parent::core_save(Input::get('id'),$values)) return false;
		$id = parent::core_lastid();
		return $id;
	}

	public function delete($id=null){
		if(!$id) return false;
		if(is_object($id)){
			$data = $id;
		}else{
			if(!$data = $this->find($id)) return false;
		}
		if(!parent::core_delete($data)) return false;
		return true;
	}


	////// PDF ////////
	public function voucher($voucher=null,$sale=null){

		if(is_null($voucher) || is_null($sale)) return false;


		$this->_voucher = $voucher;
		$voucher->sale = $sale;

		$User = new User();
		$User->find($voucher->sale->iduser);
		$voucher->user = $User->data();

		if($voucher->sale->iduser != $voucher->user->id) return false;


		$Promos = new Promos();
		if(!$Promos->find($voucher->sale->idpromo)) return false;
		$voucher->promo = $Promos->data();

		$voucher->promo->gallery = json_decode($voucher->promo->gallery);


		///$file_info = pathinfo($voucher->image->path);
		///$mime = mime_content_type($voucher->image->path);


		$Stores = new Stores();
		if(!$Stores->get($voucher->sale->idclient)) return false;
		$stores = $Stores->data();
		$stores_by_ids = array_column($stores, null, 'id');

		$promo_stores_ids = explode(',',$voucher->promo->stores);
		$voucher->stores = [];
		if($promo_stores_ids){
			foreach($promo_stores_ids as $store){
				$voucher->stores[] = $stores_by_ids[$store] ?? null;
			}
		}
		//echo_json($voucher->stores,true);
		//dd($voucher);

		define('FPDF_FONTPATH',PATH.'fonts'.DS);
		require_once PATH.'vendor/autoload.php';

		//dd($voucher);
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

		$includes = preg_replace('/\r|\n/',' ',$voucher->promo->includes);
		$this->_pdf->MultiCell(180,4,utf8_decode($includes),0,'L',false);
		$this->_pdf->Ln();


		$this->_pdf->SetFont('ProximaNormal','',12);
		$this->_pdf->SetTextColor(255,255,255);
		$this->_pdf->SetXY(21,218);
		$this->_pdf->Cell(180,6,utf8_decode('Voucher N°: ').$voucher->id,0,0,'L',false);

		$this->_pdf->SetFont('ProximaNormal','',10);
		$this->_pdf->SetXY(21,223.5);
		
		$this->_pdf->Cell(180,6,utf8_decode('válido hasta 60 días a partir del ').$voucher->sale->added_obj->format('d/m/Y'),0,0,'L',false);

		//$this->_pdf->SetFont('ProximaNormal','',9);
		//$this->_pdf->SetXY(21,228);
		//$this->_pdf->Cell(180,6,utf8_decode('*El tiempo para utilizar tu voucher se aplicará luego de las aperturas de los centros post cuarentena.'),0,0,'L',false);



		$this->_pdf->SetFont('ProximaNormal','',13);
		$this->_pdf->SetXY(21, 231.5);
		$this->_pdf->Cell(180,6,utf8_decode($voucher->promo->clientname.' - '.$voucher->stores[0]->address.' '.$voucher->stores[0]->city),0,0,'L',false);

		$this->_pdf->SetFont('ProximaNormal','',11);
		$this->_pdf->SetXY(21, 236);
		$this->_pdf->Cell(180,6,'Realizar la reserva del turno al '.(empty($voucher->stores[0]->phones) ? '' : 'Tel: '.$voucher->stores[0]->phones.' ').(empty($voucher->stores[0]->whatsapp) ? '' : '- Whatsapp: '.$voucher->stores[0]->whatsapp),0,0,'L',false);


		$this->_pdf->SetFont('ProximaNormal','',8);
		$this->_pdf->SetXY(21,243);
		$this->_pdf->Cell(180,6,utf8_decode('Política de cancelación: '.$voucher->promo->cancellation),0,0,'L',false);

		//return $this->_pdf->Output('D','estilospa.com_voucher_'.$voucher->sale->collection_id.'.pdf');
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

		//dd($voucher->image);

		$this->_pdf->setSourceFile(PATH.'assets/voucher-regalo.pdf');
		$tplidx = $this->_pdf->importPage(1);

		$this->_pdf->addPage();
		$this->_pdf->useTemplate($tplidx,0,0,210,297,true);

		//$this->_pdf->SetFont('Oleo','',22);
		$this->_pdf->SetFont('ProximaNormal','',13);
		$this->_pdf->SetTextColor(90,90,90);

		/// To
		$this->_pdf->SetXY(18,48);
		$this->_pdf->Cell(150,10,utf8_decode('Hola '.$voucher->to_user.'!'),0,0,'L',false);

		/// From
		$this->_pdf->SetXY(18,54);
		$this->_pdf->Cell(150,10,utf8_decode('Recibiste un Regalo de '.$voucher->user->name.' '.$voucher->user->lastname),0,0,'L',false);

		/// Message
		$this->_pdf->SetTextColor(90,90,90);
		$this->_pdf->SetFont('ProximaNormal','',12);
		$this->_pdf->SetXY(18,64);
		$this->_pdf->MultiCell(180,6,utf8_decode('"'.$voucher->message.'"'),0,'L',false);
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