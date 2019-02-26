Array.prototype.SwitchPosition = function (from, to) {
	this.splice(to, 0, this.splice(from, 1)[0]);
};
Number.prototype.FormatMoney = function(c, d, t){
	var n = this, 
	c = isNaN(c = Math.abs(c)) ? 2 : c, 
	d = d == undefined ? "." : d, 
	t = t == undefined ? "," : t, 
	s = n < 0 ? "-" : "", 
	i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
	j = (j = i.length) > 3 ? j % 3 : 0;
	return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}
String.prototype.replaceAll = function(search, replacement) {
	var target = this;
	return target.replace(new RegExp(search, 'g'), replacement);
};
String.prototype.ucfirst = function() {
	return this.charAt(0).toUpperCase()+this.slice(1);
}
var Random = function(min, max) {
	return Math.floor(Math.random() * (max - min)) + min;
}
var RandomLetters = function(num){
	var letters = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
	var code = '';
	for(var i=1; i<=num; i++){
		var rnd = letters[Random(0,letters.length-1)];
		code += rnd;
	}
	return code;
}
var Loading = function(BOOL,FNCT){
	$('#messages').modal({'show':false});
	if(BOOL){
		$('#loading').fadeIn(500);       
	}else{
		if($('#loading').length>0){
			$('#loading').fadeOut(200,function(){
				if(FNCT){
					FNCT();
				}
			});
		}else{
			if(FNCT){
				FNCT();
			}
		}
	}
}
var Messages = function(BOOL,TXT,FNCT){
	Loading(false);
	if(BOOL){
		$('#messages').modal({'show':true});
		$('#messages .modal-body').html(TXT);
		if(FNCT || FNCT != undefined){
			$('#messages .btn-primary').show().unbind('click').click(function(){
				FNCT();
			});			
		}else{
			$('#messages .btn-primary').hide();
		}
	}else{
		$('#messages').modal({'show':false});
	}
}
var CheckFields = function(arrFLD, FCTN){
	$(".required").removeClass("required");
	var pass = true;
	$.each(arrFLD,function(k,v){
		var fieldType = arrFLD[k].split(':');
		if($(fieldType[0]).length == 0){
			Messages(true,'Hubo un error inesperado. Intenta más tarde');
			return;
		}
		if($(fieldType[0]).val().length > 0){
			///////////////////////////////////
			if(fieldType[1] == 'email'){
				var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				if(!regex.test($(fieldType[0]).val())){
					$(fieldType[0]).addClass("required").effect('pulsate').focus();
					pass = false;
				}
			}
		}else{
			$(fieldType[0]).addClass("required").effect('pulsate').focus();
			pass = false;
		}
	});
	$('.required:eq(0)').focus();
	if(pass){
		if(FCTN){
			FCTN();
		}
	}
}
var AjaxConnection = function(PHPURL, OBJECT, FCTN){
	Loading(true);
	var AJXLog = $.ajax({type:'POST',url:ROOTPATH+'ajax/'+PHPURL,data:OBJECT,dataType:'json',cache:false});
	AJXLog.done(function(data){
		Loading(false,function(){
			if(FCTN){
				FCTN(data);
			}
		});
	});
	AJXLog.fail(function(data){
		console.log(data);
		Loading(false);
		Messages(true,'Problemas con el servidor. Intente más tarde.');
	});
}
var PreloadImages = {
	Start:function(CONT,FUNCT){
		PreloadImages.ArrIMG = [];
		$.each($(CONT).find('img'),function(){
			var img = $(this).attr('src');
			PreloadImages.ArrIMG.push(img);
		});
		$.each($(CONT).find('[style*="background-image"]'),function(k,v){
			var img = $(this).css('backgroundImage').replace('url("','').replace('")','');
			PreloadImages.ArrIMG.push(img);
			///console.log($(this).css('backgroundImage'));
		});
		PreloadImages.Caching(PreloadImages.ArrIMG,FUNCT,0);
	},
	Caching:function(ARRIMG,FUNCT,NODO){
		var ND = NODO == undefined ? 0 : NODO
		///console.log(ND);
		var rnd = Math.floor(Math.random()*1000000);
		this["imgIndex"+rnd] = new Image();
		this["imgIndex"+rnd].onload = function(){
			if(ARRIMG.length-1 != ND){
				ND++;
				PreloadImages.Caching(ARRIMG,FUNCT,ND);
			}else{
				ND = 0;
				if(FUNCT){
					FUNCT();
				}
			}
		}
		this["imgIndex"+rnd].src = ARRIMG[ND];
	}
}
var SetSlider = {
	Node:0,
	TimerSlider:function(){},
	Seconds:6000,
	init:function(DIV){
		if($(DIV+' .slide').length > 1){
			$(DIV+' .slide').each(function(){
				$(DIV+' .navigation').append("<i class='fa fa-circle fa-fw clickable'></i>&nbsp;");
			});
			//////////////////////////////////////////////////////
			$(DIV+' .navigation > i').unbind('click').click(function(){
				SetSlider.timer(false,DIV,$(this).index());
				SetSlider.changer(DIV,$(this).index());
			});
			$(DIV+' .prev').unbind('click').click(function(){
				SetSlider.timer(false);
				if(SetSlider.Node == 0){
					SetSlider.Node = $(DIV+' .slide').length-1;
				}else{
					SetSlider.Node--;
				}
				SetSlider.changer(DIV,SetSlider.Node);
			});
			$(DIV+' .next').unbind('click').click(function(){
				SetSlider.timer(false);
				if(SetSlider.Node == $(DIV+' .slide').length-1){
					SetSlider.Node = 0;
				}else{
					SetSlider.Node++;
				}
				SetSlider.changer(DIV,SetSlider.Node);
			});
			//////////////////////////////////////////////////////
			//if($(DIV+' .slide').length > 1){
			SetSlider.changer(DIV);
			//}
		}
	},
	changer:function(DIV,ND){
		var NODE = ND == undefined ? 0 : ND;	
		$(DIV+' .slide:not('+NODE+')').fadeOut();
		$(DIV+' .slide:eq('+NODE+')').fadeIn(800);
		//////////////////////////////////////////////////////////////////////
		var tt = $(DIV+' .slide :eq('+NODE+')').attr('data-title');
		var tx = $(DIV+' .slide :eq('+NODE+')').attr('data-text');
		var url = $(DIV+' .slide :eq('+NODE+')').attr('data-url');
		$(DIV+' .data-container').find('.title').html(tt);
		$(DIV+' .data-container').find('.text').html(tx);
		/////////////////////////////////////////////
		if(tt == '' || tt == undefined){$(DIV+' .data-container').hide()}else{$(DIV+' .data-container').show()}
		//////////////////////////////////////////////////////////////////////
		if(url != ''){
			$(DIV+' .data-container').parent().attr('href',url);
		}
		//////////////////////////////////////////////////////////////////////
		$(DIV+' .navigation > i').removeClass("active");
		$(DIV+' .navigation > i:eq('+NODE+')').addClass('active');
		SetSlider.timer(true,DIV,NODE);
	},
	timer:function(ACTIVE,DIV,ND){
		var NODE = ND == undefined ? 0 : ND;
		if(ACTIVE){			
			SetSlider.TimerSlider = setTimeout(function(){
				$(DIV+' .next').trigger('click');
			},SetSlider.Seconds);
		}else{
			clearTimeout(SetSlider.TimerSlider);
		}
	}	
}
var Permalink = function(str) {
	if(str == ''){
		return '';
	}
	var acentos = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑñÇç,";
	var original = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuunncc_";
	for (var i=0; i<acentos.length; i++) {
		str = str.replace(acentos.charAt(i), original.charAt(i));
	}
	var str = str.replace(/&.*?;/g, '').replace(/\s+/g, '-').replace(/[^\w\-]/g, '').toLowerCase();
	return str;
}
var GetID = function(IDDIV){
	var arrName = $('#'+IDDIV).attr('id').split('_');
	return arrName[arrName.length-1];
}
var Logout = function(){
	AjaxConnection('jxUsers.php',{Mode:'logout'},function(){
		window.location.reload();
	});
}
var ActiveGroup = function(GROUP,CLSS,FNCT){
	$(GROUP).unbind('click').click(function(e){
		$(GROUP).removeClass(CLSS);
		$(this).addClass(CLSS);
		if(FNCT){
			FNCT(this,e);
		}
	});
}
var Pagination = {
	Pag:1,
	TotalPags:0,
	Total:0,
	MakeNumbers:function(FUNCT){
		if(Pagination.Total==0){return;}
		var disleft = Pagination.Pag == 1 ? 'disabled' : '';
		var disright = Pagination.Pag == Pagination.TotalPags ? 'disabled' : '';
		$('ul.pagination').html('<li class="'+disleft+'"><a href="#">&laquo;</a></li>');
		for(var i=1; i<=Pagination.TotalPags; i++){
			var active = i == Pagination.Pag ? 'active' : '';
			$('ul.pagination').append('<li data-page="'+i+'" class="'+active+'"><a href="#">'+i+'</a></li>');
		}
		$('ul.pagination').append('<li class="'+disright+'"><a href="#">&raquo;</a></li>');
		//////////////////////////////////////////////////////////
		if($('.breadcrumb').length != 0){
			var animtop = $('.breadcrumb').offset().top;
		}else{
			var animtop = 0;
		}
		//////////////////////////////////////////////////////////
		$('ul.pagination li[data-page]:not(:eq('+(Pagination.Pag-1)+'))').unbind('click').click(function(){
			Pagination.Pag = $(this).data('page');			
			$('body,html').animate({scrollTop:animtop},600,function(){
				if(FUNCT){
					FUNCT();
				}
			});			
		});
		$('ul.pagination li:first-of-type').unbind('click').click(function(){
			Pagination.Pag = Pagination.Pag == 1 ? Pagination.Pag : Pagination.Pag-1;
			$('body,html').animate({scrollTop:animtop},600,function(){
				if(FUNCT){
					FUNCT();
				}
			});	
		});
		$('ul.pagination li:last-of-type').unbind('click').click(function(){
			Pagination.Pag = Pagination.Pag == Pagination.TotalPags ? Pagination.Pag : Pagination.Pag+1;			
			$('body,html').animate({scrollTop:animtop},600,function(){
				if(FUNCT){
					FUNCT();
				}
			});	
		});
	}
}
/////////////////////////////
var LoadThumb = function(PHNM,TH){
	var WD = $(TH).width();
	var HG = $(TH).height();
	var imgTh = new Image();
	imgTh.src = PHNM;
	imgTh.onload = function(){
		$(TH).css("backgroundImage","url("+imgTh.src+")");
	}
}
/////////////////////////////
var UpFile = {
	Init:function(OBJ){		
		///UpImage.Upload(MODE,PHP,BTN,FORM);
		$(OBJ.BTN).click(function(){			
			$('.pop .loading-text').html('<br /><p>Subiendo archivo(s). <br />Esta operación puede durar varios minutos dependiendo del tamaño del/los archivo(s) y de la conexión.</p><p><span class="label label-success"></span></p>');
			$(OBJ.FORM+'> input[type="file"]').trigger('click');
		});
		$(OBJ.FORM).submit(function(event){
			event.stopPropagation();
			event.preventDefault();
			OBJ.NODE = 0;
			OBJ.ARRFILES = [];
			UpFile.Upload(OBJ);
		});
		$(OBJ.FORM+' input[type="file"]').unbind('click change').change(function(event){
			event.preventDefault();
			if(event.target.files.length > 0){
				if(event.target.files.length > MAXFILES){
					Messages(true,'La cantidad de archivos no debe superar los '+MAXFILES);
					return;
				}
				OBJ.FILES = event.target.files;
				if(OBJ.FILES[0].type.indexOf('image') != -1){
					var img = new Image();
					img.onload = function(){
						/*if(this.width < 1080 || this.height < 1080){
							Messages(true,"La imagen debe tener un tamaño mínimo de al menos 1080x1080 píxeles");
							return;
						}*/
						$(OBJ.FORM).trigger('submit');
					}
					img.src = window.URL.createObjectURL(OBJ.FILES[0]);
				}else{
					$(OBJ.FORM).trigger('submit');
				}
			}
		});		
	},
	Upload:function(OBJ){
		///var nd = OBJ.NODE == undefined ? 0 : OBJ.NODE;
		var dataForm = new FormData();
		dataForm.append(0,OBJ.FILES[OBJ.NODE]);
		dataForm.append('Node',OBJ.NODE);
		dataForm.append('Folder',OBJ.FOLDER);
		dataForm.append('Mode',OBJ.MODE);
		Loading(true);		
		var AJXLog = $.ajax({type:'POST',url:ROOTPATH+'ajax/'+OBJ.PHP,data:dataForm,cache:false,processData:false,contentType:false,dataType:'json'});
		AJXLog.done(function(DATA){
			if(DATA.Status == 'maxfiles'){
				Messages(true, 'La cantidad de archivos no debe superar los '+DATA.MaxSize);
			}else if(DATA.Status == 'size'){
				Messages(true, 'El tamaño del archivo no debe superar los '+DATA.MaxSize);
			}else if(DATA.Status == 'fail'){
				Messages(true, 'Hubo un error al procesar la solicitud. Recarga la página e inténtalo de nuevo.');
			}else if(DATA.Status == 'dir'){
				Messages(true, 'No se puede crear la carpeta en el servidor. Contacta con el administrador del sitio.');
			}else if(DATA.Status == 'error'){
				Messages(true, 'No se pudo subir el archivo en el servidor. Contacta con el administrador del sitio.');
			}else{				
				///////////////////////////////////////////
				$('.pop .loading-text').find('.label').text((OBJ.NODE+1)+' / '+OBJ.FILES.length);
				OBJ.ARRFILES.push({'photoname':DATA.Photoname,'extension':DATA.Extension});
				///////////////////////////////////////////
				if(OBJ.NODE < OBJ.FILES.length-1){
					OBJ.NODE = OBJ.NODE+1;
					UpFile.Upload(OBJ);
				}else{
					var sufix = OBJ.SX == undefined ? '' : OBJ.SX;
					if(OBJ.TH || OBJ.TH != ''){
						$(OBJ.TH).attr({'data-photoname':OBJ.ARRFILES[0].photoname,'data-extension':OBJ.ARRFILES[0].extension}).css('backgroundImage','url('+ROOTPATH+OBJ.FOLDER+OBJ.ARRFILES[0].photoname+sufix+'.'+OBJ.ARRFILES[0].extension+')');
					}
					if(OBJ.Callback){
						var idi = DATA.IDI == undefined ? 0 : DATA.IDI;						
						OBJ.Callback(OBJ.ARRFILES,sufix,idi);
					}
					$('.pop .loading-text').text('');
					Loading(false);
					$(OBJ.FORM).get(0).reset();
					OBJ.ARRFILES = [];
				}
			}
		});
		AJXLog.fail(function(DATA){
			Messages(true,'Hubo problemas la subir el archivo, '+DATA.responseText);
			Loading(false);
			console.log(DATA);
			$(OBJ.FORM).get(0).reset();
		});
	},
};
var FloatingParallax = function(OBJ){
	$(OBJ).each(function(k,v){
		var ths = $(this);
		var win = $(window);
		win.scroll(function(){
			var speed = ths.attr('data-speed') == undefined ? 10 : ths.attr('data-speed');
			var direction = ths.attr('data-direction') == undefined ? -1 : 1;
			var yPos = ((win.scrollTop())/speed);
			ths.css({'transform':'translate(0, '+(yPos*direction)+'px)'});
		});
	});
}
var SearchSuggestions = function(FORM,PHP,MODE,FNCT){
	var nodesearchsuggestion = 0;
	$(FORM).find('input').unbind('keyup').keyup(function(e){
		var strlen = $(this).val().length;
		var input = $(this);
		var dm = $(this).attr('data-mode');
		var nodeselect = 0;
		var Mode = dm == undefined ? MODE : dm;
		if(strlen > 2){
			if(e.keyCode == 13){return false;}
			if(e.keyCode == 40 || e.keyCode == 38){
				if(input.parent().find('.input-search-suggestions').length == 1){
					if(e.keyCode == 40){
						if(input.parent().find('.input-search-suggestions li').length == nodesearchsuggestion+1){
							nodesearchsuggestion = 0;
						}else{
							nodesearchsuggestion++;
						}
					}else{
						if(nodesearchsuggestion == 0){
							nodesearchsuggestion = input.parent().find('.input-search-suggestions li').length-1;
						}else{
							nodesearchsuggestion--;
						}
					}
					input.parent().find('.input-search-suggestions li').removeClass('active');
					input.parent().find('.input-search-suggestions li:eq('+nodesearchsuggestion+')').addClass('active');
					var tx = input.parent().find('.input-search-suggestions li:eq('+nodesearchsuggestion+')').text();
					input.val(tx);
				}
				return false;
			}
			var ajx = $.ajax({type:'POST',url:ROOTPATH+'ajax/'+PHP,data:{Mode:Mode,Keywords:input.val(),SearchMixed:1},dataType:'json',cache:true});
			ajx.done(function(DATA){
				nodesearchsuggestion = 0;
				if(DATA.Results != null && DATA.Results.length > 0){
					input.parent().find('.input-search-suggestions').remove();
					input.parent().append('<ul class="input-search-suggestions"></ul>');
					$.each(DATA.Results,function(k,v){
						var title = '';
						if(v.name != undefined){title = v.name;}
						if(v.title != undefined){title = v.title;}
						if(k<5){
							input.parent().find('.input-search-suggestions').append('<li data-id="'+v.id+'">'+title+'</li>');
						}
					});
					input.parent().find('.input-search-suggestions li').unbind('click').click(function(){
						input.val($(this).text());
						FNCT(input,this,DATA);
						input.parent().find('.input-search-suggestions').remove();
					});
					$(window).unbind('mouseup').mouseup(function(e){
						if(!$(e.target).parent().hasClass('input-search-suggestions')){
							input.parent().find('.input-search-suggestions').remove();
						}
					});					
				}else{
					input.parent().find('.input-search-suggestions').remove();
					///console.log('nothing');
				}
			});
			ajx.fail(function(DATA){
				console.log(DATA);
			});
		}else{
			input.parent().find('.input-search-suggestions').remove();
		}
	});
}
var GetYoutubeApi = function(ID,CALLBACK){
	var AJXLog = $.ajax({type:'GET',url:'https://www.googleapis.com/youtube/v3/videos?id='+ID+'&key=AIzaSyAq3a2AC4jXd9AVmt646ZP_45Vd3oLJn7g&part=snippet'});
	AJXLog.done(function(data){
		if(CALLBACK){
			return CALLBACK(data);
		}
	});
}
function GetIDVideo(INPUT,SITE){
	var arrURL = {};
	var arrYT = {};
	var idvideo = "";
	switch(SITE){
		case 'vimeo':
			arrURL = INPUT.split("/");
			if(arrURL.length==1){return false;}
			idvideo = arrURL[arrURL.length-1];
			break;
		case 'youtube':
			arrURL = INPUT.split("watch?v=");
			if(arrURL.length==1){return false;}           
			arrYT = arrURL[1].split("&");
			idvideo = arrYT[0];
			break;
	}
	return idvideo;
}
var ModViews = function(){
	ActiveGroup('[data-group="views"]','active',function(THS){
		var view = $(THS).attr('data-toggle');
		$.each($('.mod-card'),function(k,v){
			if(view == 'thumb'){
				$(this).addClass('col-sm-2').removeClass('col-sm-4 col-sm-3').find('.thumb').show();
				//$(this).find('h1').addClass('sm');
				$(this).find('.caption .buttons').hide();
				$(this).find('.caption p').hide();
			}
			if(view == 'large'){
				$(this).addClass('col-sm-4').removeClass('col-sm-2 col-sm-3').find('.thumb').show();
				//$(this).find('h1').removeClass('sm');
				$(this).find('.caption .buttons').hide();
				$(this).find('.caption p').show();
			}
			if(view == 'list'){
				$(this).removeClass('col-sm-2 col-sm-4').addClass('col-sm-12').find('.thumb').hide();
				//$(this).find('h1').removeClass('sm');
				$(this).find('.caption .buttons').show();
				$(this).find('.caption p').show();
			}
		});
	});
}
var DragImages = function(DIV){
	$(DIV+' .th').css({left:0,top:0}).draggable({
		drag:function(e,ui){			
			$(ui.helper.context).parent().css({backgroundPosition:ui.position.left+'px '+ui.position.top+'px'});
		},
		stop:function(e,ui){
			var left = ui.position.left;
			var top = ui.position.top;
			if(ui.position.left > 0){
				left = 0;
			}
			if(ui.position.top > 0){
				top = 0;
			}
			if(ui.position.left < -($(DIV+' .th').width()-$(DIV).width())){
				left = 	-($(DIV+' .th').width()-$(DIV).width()-8);
			}
			if(ui.position.top < -($(DIV+' .th').height()-$(DIV).height())){
				top = -($(DIV+' .th').height()-$(DIV).height()-8);
			}
			$(ui.helper.context).animate({top:top,left:left},{duration:200});
		}
	});
}
var CharCount = function(FD){
	$('[data-toogle="charcount"]').keyup(function(){
		var len = $(this).prop('maxlength');
		var id = $(this).attr('id');
		if($('label[for="'+id+'"]').find('span').length==0){
			$('label[for="'+id+'"]').append('<span></span>');
		}
		$('label[for="'+id+'"] span').text(' ('+(len-$(this).val().length)+')');
		if($(this).val().length==0){$('label[for="'+id+'"] span').remove();}
	});
}
var FormatDate = function(date) {
	var month = String(date.getMonth() + 1);
	var day = String(date.getDate());
	var year = String(date.getFullYear());
	var hours = String(date.getHours());
	var minutes = String(date.getMinutes());
	var seconds = String(date.getSeconds());

	if (month.length < 2) month = '0' + month;
	if (day.length < 2) day = '0' + day;
	if (hours.length < 2) hours = '0' + hours;
	if (minutes.length < 2) minutes = '0' + minutes;
	if (seconds.length < 2) seconds = '0' + seconds;

	return year.toString() + "-" + month + "-" + day+' '+hours+':'+minutes+':'+seconds;
}
var CloseModalCheckout = function(){
	$('#modal_mp').modal('hide');
	if( window.self !== window.top){
		window.location.reload();
	}else{
		window.location.href = ROOTPATH;
	}
}
var Subscribe = function(){
	$('#form_newsletter').submit(function(e){
		e.preventDefault();
		AjaxConnection('jxForms.php',{Mode:'subscribe',email:$(this).find('input[type="email"]').val()},function(data){
			$('#form_newsletter .status').html(data.message);
			if(data.status == 'ok'){
				$('#form_newsletter').find('input[type="email"]').val('');
			}
		});
	});
}
$(function(){
	$('[title]').tooltip();
	CharCount();
	Subscribe();
	//FloatingParallax('.floating-parallax');
	$('[dat-parallax="scroll"]').parallax({});
	$('nav .fa-bars').click(function(){
		$('#main_menu').slideToggle();
	});
	$(window).resize(function(){
		if($(window).width()>767){
			$('#main_menu').removeAttr('style').slideDown();
		}
	});
	$('a[data-toggle="slide"]').click(function(e){
		e.preventDefault();
		var ref = $(this).attr('href');
		$(ref).toggleClass('active');
	});
	$('#btn_search_bar').click(function(){
		$('#search_bar').slideToggle();
	});
	new WOW().init();
	$('[data-toggle="switch"]').click(function(){
		$(this).find('i').toggleClass('fa-toggle-off fa-toggle-on');
		$(this).find('span').text($(this).find('i').hasClass('fa-toggle-on') ? 'Si' : 'No');
	});
	$('[data-toggle="btn-checkbox"]').click(function(){
		$(this).find('i').toggleClass('fa-square fa-check-square');
	});
	$.scrollUp({scrollName:'scrollUp', scrollDistance:300, scrollFrom:'top', scrollSpeed:300, easingType:'linear', animation:'fade', animationSpeed:200, scrollTrigger:false, scrollText:'<i class="fa fa-angle-up"></i>', scrollTitle:false, scrollImg:false, activeOverlay:false, zIndex:2147483647});
	$.datepicker.regional['es'] = {closeText:'Cerrar',prevText:'<Ant',nextText:'Sig>',currentText:'Hoy',monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],monthNamesShort:['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],dayNames:['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],dayNamesShort:['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],dayNamesMin:['Do','Lu','Ma','Mi','Ju','Vi','Sá'],weekHeader: 'Sm',dateFormat:'dd/mm/yy',firstDay:1,isRTL:false,showMonthAfterYear:false,yearSuffix:''};
	$.datepicker.setDefaults($.datepicker.regional['es']);
	/////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////
	SearchSuggestions('#form_main_search','jxSearch.php','locations',function(INPUT,ths){});
	$('#form_main_search').submit(function(e){
		e.preventDefault();
		var main = $(this).find('[name="main"]').val();
		var location = $(this).find('[name="location"]').val();
		window.location.href=ROOTPATH+'busqueda/'+Permalink(main==''?'-':main)+'/'+Permalink(location);
	});


	$('[data-btn-action="fav"]').click(function(e){
		var _this = this;
		e.preventDefault();
		var promoid = $(this).attr('data-promoid') == undefined ? 0 : $(this).attr('data-promoid');
		var clientid = $(this).attr('data-clientid') == undefined ? 0 : $(this).attr('data-clientid');
		AjaxConnection('jxUsers.php',{Mode:'favs',IDP:promoid,IDC:clientid},function(DATA){
			$(_this).find('i').removeClass();
			if(DATA.IsFav==1){
				$(_this).find('i').addClass('fa fa-heart active');
			}else{
				$(_this).find('i').addClass('fa fa-heart-o');
			}
		});
	});

});