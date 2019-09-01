'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var UpFile = function () {
	function UpFile(obj) {
		_classCallCheck(this, UpFile);

		$.extend(this, obj);
		this.init();
	}

	_createClass(UpFile, [{
		key: 'upload',
		value: function upload() {
			var _this = this;

			var dataForm = new FormData();
			dataForm.append('file', this.files[this.node]);
			dataForm.append('node', this.node);
			dataForm.append('folder', this.folder + '/');
			dataForm.append('mode', this.mode);

			if (this.node == 0) {
				loading({ message: '<p>Subiendo archivo(s). <br />Esta operación puede durar varios minutos dependiendo del tamaño del/los archivo(s) y de la conexión.</p><p><span class="label label-success">...</span></p>' });
			}

			return new Promise(function (resolve, reject) {

				$.ajax({
					type: 'POST',
					url: ROOT + 'ajax/' + _this.controller,
					data: dataForm,
					cache: false,
					processData: false,
					contentType: false,
					dataType: 'json'
				}).done(function (data) {
					if (data.status != 'ok') {
						Swal.fire({ text: data.message, type: 'error' });
						reject(data);
					}

					$('#loading .label').text(_this.node + 1 + ' / ' + _this.files.length);
					_this.arrfiles.push({ 'filename': data.filename, 'extension': data.extension });
					///////////////////////////////////////////
					if (_this.node < _this.files.length - 1) {
						_this.node = _this.node + 1;
						_this.upload();
					} else {
						var sufix = 'sufix' in _this ? _this.sufix : '';
						if ('thumbnail' in _this) {
							$(_this.thumbnail).attr({
								'data-filename': _this.arrfiles[0].filename,
								'data-extension': _this.arrfiles[0].extension
							}).css({
								'backgroundImage': 'url(' + ROOT + _this.folder + '/' + _this.arrfiles[0].filename + sufix + '.' + _this.arrfiles[0].extension + ')'
							});
						}
						if ('callback' in _this) {
							var idi = 'idi' in data ? data.idi : 0;
							_this.callback(_this.arrfiles, sufix, idi);
						}
						$('#loading .loading-text').text('');
						$(_this.container).find('input').val('');
						if ('gallery' in _this) {
							_this.build_gallery();
						}
						resolve(_this.arrfiles);
					}
				}).always(function (data) {
					loading({ show: false });
				}).fail(function (data) {
					Swal.fire({ text: 'Hubo problemas al subir el archivo. Intenta nuevamente.', type: 'error' });
					reject(data);
				});
			}).catch(function (data) {
				console.log(data);
			});
		}
	}, {
		key: 'build_gallery',
		value: function build_gallery() {
			var _this2 = this;

			get_template('site/gallery-thumbnail').then(function (template) {
				$.each(_this2.arrfiles, function (k, v) {
					var $module = $($(template));
					$module.attr({ 'data-filename': v.filename, 'data-extension': v.extension });
					$module.css({ backgroundImage: 'url(' + ROOT + _this2.folder + '/' + v.filename + '-t.' + v.extension + ')' });
					$(_this2.gallery).append($module);
				});
			});
		}
	}, {
		key: 'init',
		value: function init() {
			var _this3 = this;

			this.scope = 'scope' in this ? this.scope : 'admin';
			this.controller = 'controller' in this ? this.controller : 'upload';

			$(this.container).on('click', 'button', function () {
				$(_this3.container).find('input').trigger('click');
			});

			$(this.container).on('change', 'input', function (event) {
				event.preventDefault();
				_this3.node = 0;
				_this3.arrfiles = [];
				if (event.target.files.length > 0) {
					if (event.target.files.length > MAXFILES) {
						Swal.fire({
							text: 'La cantidad de archivos no debe superar los ' + MAXFILES,
							type: 'warning'
						});
						return false;
					}
					_this3.files = event.target.files;
					if (_this3.files[0].type.indexOf('image') != -1) {
						var img = new Image();
						img.onload = function () {
							_this3.upload();
						};
						img.src = window.URL.createObjectURL(_this3.files[0]);
					} else {
						_this3.upload();
					}
				}
			});

			if ('gallery' in this) {
				$(this.gallery).on('click', '.delete', function (btn) {
					$(btn.currentTarget).parent().parent().remove();
				});
				$(this.gallery).sortable();
			}
		}
	}]);

	return UpFile;
}();