"use strict";

Array.prototype.switchPosition = function (from, to) {
	return this.splice(to, 0, this.splice(from, 1)[0]);
};
Array.prototype.max = function () {
	return Math.max.apply(null, this);
};
Array.prototype.min = function () {
	return Math.min.apply(null, this);
};
Array.prototype.remove = function (value) {
	return this.filter(function (el) {
		return el != value;
	});
};

Number.prototype.numberFormat = function (c, d, t) {
	var n = this,
	    c = isNaN(c = Math.abs(c)) ? 2 : c,
	    d = d == undefined ? "." : d,
	    t = t == undefined ? "," : t,
	    s = n < 0 ? "-" : "",
	    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
	    j = (j = i.length) > 3 ? j % 3 : 0;
	return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};

String.prototype.replaceAll = function (search, replacement) {
	var target = this;
	return target.replace(new RegExp(search, 'g'), replacement);
};
String.prototype.permalink = function () {
	var str = this;
	if (str == '') return '';
	var acentos = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑñÇç,";
	var replacement = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuunncc-";
	for (var i = 0; i < acentos.length; i++) {
		str = str.replace(acentos.charAt(i), replacement.charAt(i));
	}
	var str = str.replace(/&.*?;/g, '').replace(/\s+/g, '-').replace(/[^\w\-]/g, '').toLowerCase();
	return str;
};
String.prototype.ucfirst = function () {
	return this.charAt(0).toUpperCase() + this.slice(1);
};

var loading = function loading(obj) {
	var show = obj != undefined && 'show' in obj ? obj.show : true;
	var message = obj != undefined && 'message' in obj ? obj.message : '';
	if (show) {
		$('#loading').addClass('active').find('.loading-text').html(message);
	} else {
		$('#loading').removeClass('active');
		if (obj.callback) {
			obj.callback();
		}
	}
};
var ajax = function ajax(url, obj) {
	loading();
	if (obj == undefined) obj = {};
	return new Promise(function (resolve, reject) {
		$.ajax({
			type: 'POST',
			url: ROOT + 'ajax/index.php?uri=' + url,
			data: obj,
			dataType: 'json',
			cache: false
		}).done(function (response) {
			if (response.status != 'ok') {
				console.log(response, url, obj);
				Swal.fire({ type: 'error', html: response.message });
				reject(response);
			}
			resolve(response);
		}).always(function (response) {
			loading({ show: false });
		}).fail(function (response) {
			var error = response.responseText;
			console.log(response.responseText);
			reject(error);
		});
	});
};
var get_form = function get_form(f) {
	var fd = $(f).serializeArray();
	var d = {};
	d.required = [];
	$(fd).each(function (k, v) {
		if (d[this.name] !== undefined) {
			if (!Array.isArray(d[this.name])) {
				d[this.name] = [d[this.name]];
			}
			d[this.name].push(this.value);
		} else {
			d[this.name] = this.value;
			if ($(f).find("[name=\"" + this.name + "\"]").prop('required')) {
				d.required.push(this.name);
			}
		}
	});
	return d;
};
var logout = function logout() {
	ajax('site/users/logout', {}).then(function (data) {
		window.location.href = ROOT;
	});
};
var toggle_button = function toggle_button(el, status) {
	if (status == 1) {
		$(el).find('i').removeClass('fa-toggle-off').addClass('fa-toggle-on');
	} else {
		$(el).find('i').removeClass('fa-toggle-on').addClass('fa-toggle-off');
	}
};
var random = function random(min, max) {
	return Math.floor(Math.random() * (max - min)) + min;
};
var random_letters = function random_letters(num) {
	var letters = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
	var code = '';
	for (var i = 1; i <= num; i++) {
		var rnd = letters[random(0, letters.length - 1)];
		code += rnd;
	}
	return code;
};
var get_template = function get_template(template) {
	return new Promise(function (resolve, reject) {
		var ajax = $.ajax({
			type: 'GET',
			url: ROOT + 'templates/' + template + '.php',
			cache: false
		}).done(function (data) {
			resolve(data);
		}).fail(function (data) {
			reject(data);
		});
	});
};
var page_maker = function page_maker($elements, $total) {
	var $split = $total / $elements;
	if ($split % 1 !== 0) return Math.floor($split) + 1;
	return $split;
};
var convertDates = function convertDates(obj) {
	console.log(obj);
	switch (obj.from) {
		case 'mysql':
			var f = obj.date.split(' ');
			var d = f[0].split('-');
			return d[2] + '/' + d[1] + '/' + d[0] + ' ' + (f[1] == undefined ? '00:00:00' : f[1]);
			break;
		case 'regional':
			var f = obj.date.split(' ');
			var d = f[0].split('/');
			return d[2] + '-' + d[1] + '-' + d[0] + ' ' + (f[1] == undefined ? '00:00:00' : f[1]);
			break;
		default:
			return obj.date;
			break;
	}
};

var CheckFields = function CheckFields(arrFLD, FCTN) {
	$(".required").removeClass("required");
	var pass = true;
	$.each(arrFLD, function (k, v) {
		var fieldType = arrFLD[k].split(':');
		if ($(fieldType[0]).length == 0) {
			Messages(true, 'Hubo un error inesperado. Intenta más tarde');
			return;
		}
		if ($(fieldType[0]).val().length > 0) {
			///////////////////////////////////
			if (fieldType[1] == 'email') {
				var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				if (!regex.test($(fieldType[0]).val())) {
					$(fieldType[0]).addClass("required").effect('pulsate').focus();
					pass = false;
				}
			}
		} else {
			$(fieldType[0]).addClass("required").effect('pulsate').focus();
			pass = false;
		}
	});
	$('.required:eq(0)').focus();
	if (pass) {
		if (FCTN) {
			FCTN();
		}
	}
};
var SearchSuggestions = function SearchSuggestions(FORM, PHP, MODE, FNCT) {
	var nodesearchsuggestion = 0;
	$(FORM).find('input').unbind('keyup').keyup(function (e) {
		var strlen = $(this).val().length;
		var input = $(this);
		var dm = $(this).attr('data-mode');
		var nodeselect = 0;
		var mode = dm == undefined ? MODE : dm;
		if (strlen > 2) {
			if (e.keyCode == 13) {
				return false;
			}
			if (e.keyCode == 40 || e.keyCode == 38) {
				if (input.parent().find('.input-search-suggestions').length == 1) {
					if (e.keyCode == 40) {
						if (input.parent().find('.input-search-suggestions li').length == nodesearchsuggestion + 1) {
							nodesearchsuggestion = 0;
						} else {
							nodesearchsuggestion++;
						}
					} else {
						if (nodesearchsuggestion == 0) {
							nodesearchsuggestion = input.parent().find('.input-search-suggestions li').length - 1;
						} else {
							nodesearchsuggestion--;
						}
					}
					input.parent().find('.input-search-suggestions li').removeClass('active');
					input.parent().find('.input-search-suggestions li:eq(' + nodesearchsuggestion + ')').addClass('active');
					var tx = input.parent().find('.input-search-suggestions li:eq(' + nodesearchsuggestion + ')').text();
					input.val(tx);
				}
				return false;
			}
			var ajx = $.ajax({
				type: 'POST',
				url: ROOT + 'ajax/index.php?uri=' + PHP + (mode != '' ? '/' + mode : ''),
				data: {
					keywords: input.val(),
					search_mixed: 1,
					mode: mode
				},
				dataType: 'json',
				cache: true
			});
			ajx.done(function (DATA) {
				nodesearchsuggestion = 0;
				if (DATA.results != null && DATA.results.length > 0) {
					input.parent().find('.input-search-suggestions').remove();
					input.parent().append('<ul class="input-search-suggestions"></ul>');
					$.each(DATA.results, function (k, v) {
						var title = '';
						if (v.name != undefined) {
							title = v.name;
						}
						if (v.title != undefined) {
							title = v.title;
						}
						if (k < 5) {
							input.parent().find('.input-search-suggestions').append('<li data-id="' + v.id + '">' + title + '</li>');
						}
					});
					input.parent().find('.input-search-suggestions li').unbind('click').click(function () {
						input.val($(this).text());
						FNCT(input, this, DATA);
						input.parent().find('.input-search-suggestions').remove();
					});
					$(window).unbind('mouseup').mouseup(function (e) {
						if (!$(e.target).parent().hasClass('input-search-suggestions')) {
							input.parent().find('.input-search-suggestions').remove();
						}
					});
				} else {
					input.parent().find('.input-search-suggestions').remove();
					///console.log('nothing');
				}
			});
			ajx.fail(function (DATA) {
				console.log(DATA);
			});
		} else {
			input.parent().find('.input-search-suggestions').remove();
		}
	});
};
var char_count = function char_count(field) {
	var len = $(field).prop('maxlength');
	var rand = Math.floor(Math.random() * 10000);
	$(field).parent().append('<small id="charcount_' + rand + '" class="text-gray-50"></small>');
	var charcount = $('#charcount_' + rand);
	$(field).keyup(function () {
		var chars = len - $(this).val().length;
		charcount.text('-' + chars);
	});
};
var FormatDate = function FormatDate(date) {
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

	return year.toString() + "-" + month + "-" + day + ' ' + hours + ':' + minutes + ':' + seconds;
};
var GetIDVideo = function GetIDVideo(INPUT, SITE) {
	var arrURL = {};
	var arrYT = {};
	var idvideo = "";
	switch (SITE) {
		case 'vimeo':
			arrURL = INPUT.split("/");
			if (arrURL.length == 1) {
				return false;
			}
			idvideo = arrURL[arrURL.length - 1];
			break;
		case 'youtube':
			arrURL = INPUT.split("watch?v=");
			if (arrURL.length == 1) {
				return false;
			}
			arrYT = arrURL[1].split("&");
			idvideo = arrYT[0];
			break;
	}
	return idvideo;
};
var DragImages = function DragImages(DIV) {
	$(DIV + ' .th').css({ left: 0, top: 0 }).draggable({
		drag: function drag(e, ui) {
			$(ui.helper.context).parent().css({ backgroundPosition: ui.position.left + 'px ' + ui.position.top + 'px' });
		},
		stop: function stop(e, ui) {
			var left = ui.position.left;
			var top = ui.position.top;
			if (ui.position.left > 0) {
				left = 0;
			}
			if (ui.position.top > 0) {
				top = 0;
			}
			if (ui.position.left < -($(DIV + ' .th').width() - $(DIV).width())) {
				left = -($(DIV + ' .th').width() - $(DIV).width() - 8);
			}
			if (ui.position.top < -($(DIV + ' .th').height() - $(DIV).height())) {
				top = -($(DIV + ' .th').height() - $(DIV).height() - 8);
			}
			$(ui.helper.context).animate({ top: top, left: left }, { duration: 200 });
		}
	});
};
var ModViews = function ModViews() {
	ActiveGroup('[data-group="views"]', 'active', function (THS) {
		var view = $(THS).attr('data-toggle');
		$.each($('.mod-card'), function (k, v) {
			if (view == 'thumb') {
				$(this).addClass('col-sm-2').removeClass('col-sm-4 col-sm-3').find('.thumb').show();
				//$(this).find('h1').addClass('sm');
				$(this).find('.caption .buttons').hide();
				$(this).find('.caption p').hide();
			}
			if (view == 'large') {
				$(this).addClass('col-sm-4').removeClass('col-sm-2 col-sm-3').find('.thumb').show();
				//$(this).find('h1').removeClass('sm');
				$(this).find('.caption .buttons').hide();
				$(this).find('.caption p').show();
			}
			if (view == 'list') {
				$(this).removeClass('col-sm-2 col-sm-4').addClass('col-sm-12').find('.thumb').hide();
				//$(this).find('h1').removeClass('sm');
				$(this).find('.caption .buttons').show();
				$(this).find('.caption p').show();
			}
		});
	});
};
var ActiveGroup = function ActiveGroup(GROUP, CLSS, FNCT) {
	$(GROUP).unbind('click').click(function (e) {
		$(GROUP).removeClass(CLSS);
		$(this).addClass(CLSS);
		if (FNCT) {
			FNCT(this, e);
		}
	});
};