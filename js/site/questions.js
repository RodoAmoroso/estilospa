'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Questions = function () {
	function Questions(obj) {
		_classCallCheck(this, Questions);

		$.extend(this, obj);
		this.init();
	}

	_createClass(Questions, [{
		key: 'paginate',
		value: function paginate() {
			if (this.results == false) return false;
			$(this.container).append('<div class="load-more"><a href="#" data-page="' + this.page + '">Cargar más</a></div>');
		}
	}, {
		key: 'get',
		value: function get() {
			var _this = this;

			if (this.page == 1) $(this.container).html('');

			ajax('site/questions/' + this.mode, {
				rowid: $(this.form).find('[name=rowid]').val(),
				page: this.page,
				limit: this.limit
			}).then(function (response) {
				return response;
			}).then(function (data) {

				Promise.all([get_template('questions/questions'), get_template('questions/questions-responses')]).then(function (templates) {

					if (data.results == false) {
						$(_this.container).html('<p>Aún no se han hecho preguntas</p>');
						return false;
					}
					//console.log(page_maker(this.limit,data.total));
					_this.total_loaded += data.results.length;
					_this.results = data.results;
					$.each(data.results, function (k, v) {
						var $module = $(templates[0]);
						$module.find('[data-content=message]').text(v.message);
						$module.find('[data-content=added]').text('Enviada: ' + v.creado + ' hs.');
						if (v.responses != false) {
							$.each(v.responses, function (kr, vr) {
								var $mod_response = $(templates[1]);
								$mod_response.find('[data-content=response]').text(vr.message);
								$mod_response.find('[data-content=added]').text('Enviada: ' + vr.creado + ' hs.');
								$module.append($mod_response);
							});
						}
						$(_this.container).append($module);

						$(_this.container).find('.load-more').remove();
						if (_this.total_loaded < parseInt(data.total)) {
							_this.paginate();
						}
					});
				});
			});
		}
	}, {
		key: 'add',
		value: function add() {
			var _this2 = this;

			var post = get_form($(this.form));
			ajax('site/questions/add', post).then(function (data) {
				$(_this2.form).find('textarea').val('');
				_this2.get('getbypromo');
			});
		}
	}, {
		key: 'response',
		value: function response() {
			var _this3 = this;

			var post = get_form($(this.form_response));
			ajax('site/questions/response', post).then(function (data) {
				$(_this3.form_response).find('textarea').val('');
				window.location.reload();
			});
		}
	}, {
		key: 'init',
		value: function init() {
			var _this4 = this;

			this.results = false;
			this.page = 'page' in this ? this.page : 1;
			this.limit = 'limit' in this ? this.limit : 8;
			this.total_loaded = 0;
			this.mode = 'mode' in this ? this.mode : 'getbypromo';
			this.form = 'form' in this ? this.form : '#form_null';
			this.form_response = 'form_response' in this ? this.form_response : '#form_null';
			this.container = 'container' in this ? this.container : '#container_null';

			$(this.container).on('click', '.load-more a', function (e) {
				e.preventDefault();
				var page = parseInt($(e.currentTarget).attr('data-page'));
				$(e.currentTarget).attr({ 'data-page': page + 1 });
				_this4.page = page + 1;
				_this4.get();
			});

			$(this.form).submit(function (e) {
				e.preventDefault();
				_this4.add();
			});
			$(this.form_response).submit(function (e) {
				e.preventDefault();
				_this4.response();
			});
		}
	}]);

	return Questions;
}();