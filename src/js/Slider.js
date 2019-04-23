class Slider {
	constructor(obj){
		$.extend(this,obj);
		this.init();
	}

	actions(direction){
		let _this = this;
		_this.timer(false);
		switch(direction){
			case 'dot':				
				break;
			case 'next':
				_this.node = 0;
				if($(`${_this.slide}.active`).index() != $(_this.slide).length-1){
					_this.node = $(`${_this.slide}.active`).index()+1;
				}
				break;
			case 'prev':
				_this.node = 0;
				if($(`${_this.slide}.active`).index() != 0){
					_this.node = $(`${_this.slide}.active`).index()-1;
				}
				break;
		}
		_this.changer();
	}



	timer(active){
		let _this = this;
		if(active){			
			_this[`timer_${_this.instance}`] = setTimeout(()=>{
				$(`${_this.container} .next`).trigger('click');
			},_this.duration);

		}else{
			clearTimeout(_this[`timer_${_this.instance}`]);
		}

	}


	buttons(){
		let _this = this;
		$(_this.container).on('click','.dots i',function(){
			_this.node = $(this).index();
			_this.actions('dot');			
		});
		
		$(_this.container).on('click','.prev',function(){			
			_this.actions('prev');
		});
		$(_this.container).on('click','.next',function(){
			_this.actions('next');			
		});
	}

	changer(){
		let _this = this;
		$(_this.slide).removeClass('active').eq(_this.node).addClass('active')
		$(_this.container).find('.dots i').removeClass('active').eq(_this.node).addClass('active')
		
		if(_this.autoplay) _this.timer(true);
	}

	dom(el,classes){
		return $('<'+el+'>').clone().addClass(classes);
	}

	arrows(){
		let _this = this;
		let prev = _this.dom('div','prev');
		let next = _this.dom('div','next');
		let il = _this.dom('i','fa fa-angle-left');
		let ir = _this.dom('i','fa fa-angle-right');
		$(_this.container).append(prev.append(il),next.append(ir));
	}

	dots(){
		let _this = this;
		let dots = _this.dom('div','dots');
		$(_this.container).append(dots);
		if($(_this.slide).length > 1){
			$(_this.slide).each(function(){
				let i = _this.dom('i','fa fa-circle');
				$(_this.container).append(dots.append(i));
			});
		}
	}

	init(){
		let _this = this;
		_this.node = 0;
		_this.instance = Math.round(Math.random()*100000);
		_this.duration = 'duration' in _this ? _this.duration : 6000;
		_this.dots = 'dots' in _this ? _this.dots : true;
		_this.autoplay = 'autoplay' in _this ? _this.autoplay : true;
		_this.slide = 'slide' in _this ? _this.container+' '+_this.slide : _this.container+' .slide';

		if(_this.dots && $(_this.slide).length > 1) _this.dots();
		if($(_this.slide).length > 1) _this.arrows();

		_this.buttons();

		$(_this.slide+':first-of-type').addClass('active');
		_this.changer(0);


	}
}