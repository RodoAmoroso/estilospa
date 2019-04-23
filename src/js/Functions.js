Array.prototype.switchPosition = function(from, to) {
	return this.splice(to, 0, this.splice(from, 1)[0]);
};
Array.prototype.max = function() {
	return Math.max.apply(null, this);
};
Array.prototype.min = function() {
	return Math.min.apply(null, this);
};
Array.prototype.remove = function(value){
	return this.filter(function(el){
		return el != value;
	});
}

Number.prototype.numberFormat = function(c, d, t) {
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
	let target = this;
	return target.replace(new RegExp(search, 'g'), replacement);
};
String.prototype.permalink = function(){
	var str = this;
	if(str=='') return '';
	let acentos = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑñÇç,";
	let replacement = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuunncc-";
	for (let i=0; i<acentos.length; i++) {
		str = str.replace(acentos.charAt(i), replacement.charAt(i));
	}
	var str = str.replace(/&.*?;/g, '').replace(/\s+/g, '-').replace(/[^\w\-]/g, '').toLowerCase();
	return str;
}

let loading = obj => {
	let show = obj != undefined && 'show' in obj ? obj.show : true;
	let message = obj != undefined && 'message' in obj ? obj.message : '';
	if(show){
		$('#loading').addClass('active').find('.text').html(message);
	}else{
		$('#loading').removeClass('active');
		if(obj.callback){obj.callback();}
	}
}
let ajax = (url,obj) => {
	loading();
	return new Promise((resolve,reject) => {
		$.ajax({
			type:'POST',
			url:ROOT+'ajax/'+url,
			data:obj,
			dataType:'json',
			cache:false
		})
		.done(response => {
			if(response.status!='ok'){
				Swal.fire({type:'error',html:response.message});
				reject(response);
			} 
			resolve(response);
		})
		.always(response=>{
			loading({show:false});
		})
		.fail(response => {
			let error = response.responseText;
			console.log(response.responseText);			
			reject(error);
		});
	});
}
let get_form = f => {
	var fd = $(f).serializeArray();
	var d = {};
	d.required = [];
	$(fd).each(function(k,v) {
		if (d[this.name] !== undefined){
			if (!Array.isArray(d[this.name])) {
				d[this.name] = [d[this.name]];
			}
			d[this.name].push(this.value);
		}else{
			d[this.name] = this.value;
			if($(f).find(`[name="${this.name}"]`).prop('required')){
				d.required.push(this.name);
			}
		}
	});
	return d;
}
let logout = () => {
	ajax('site/users/logout',{})
	.then(data => {
		window.location.reload();
	});
}
let toggle_button = function(el,status){
	if(status==1){
		$(el).find('i').removeClass('fa-toggle-off').addClass('fa-toggle-on');
	}else{
		$(el).find('i').removeClass('fa-toggle-on').addClass('fa-toggle-off');
	}
}
let random = (min, max) => {
	return Math.floor(Math.random() * (max - min)) + min;
}
let random_letters = (num) => {
	let letters = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
	let code = '';
	for(let i=1; i<=num; i++){
		let rnd = letters[random(0,letters.length-1)];
		code += rnd;
	}
	return code;
}
let get_template = template => {
	return new Promise((resolve,reject)=>{
		var ajax = $.ajax({
			type:'GET',
			url:ROOT+'templates/'+template+'.php',
			cache:false
		})
		.done((data)=>{
			resolve(data);
		})
		.fail((data)=>{
			reject(data);
		});
	});

}
let page_maker = ($elements,$total)=>{
	let $split = $total/$elements;
	if($split % 1 !== 0) return Math.floor($split)+1;
	return $split;
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
			var ajx = $.ajax({type:'POST',url:ROOT+'ajax/'+PHP,data:{Mode:Mode,Keywords:input.val(),SearchMixed:1},dataType:'json',cache:true});
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