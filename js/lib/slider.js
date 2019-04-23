'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Slider = function () {
	function Slider(obj) {
		_classCallCheck(this, Slider);

		$.extend(this, obj);
		this.init();
	}

	_createClass(Slider, [{
		key: 'actions',
		value: function actions(direction) {
			var _this = this;
			_this.timer(false);
			switch (direction) {
				case 'dot':
					break;
				case 'next':
					_this.node = 0;
					if ($(_this.slide + '.active').index() != $(_this.slide).length - 1) {
						_this.node = $(_this.slide + '.active').index() + 1;
					}
					break;
				case 'prev':
					_this.node = 0;
					if ($(_this.slide + '.active').index() != 0) {
						_this.node = $(_this.slide + '.active').index() - 1;
					}
					break;
			}
			_this.changer();
		}
	}, {
		key: 'timer',
		value: function timer(active) {
			var _this = this;
			if (active) {
				_this['timer_' + _this.instance] = setTimeout(function () {
					$(_this.container + ' .next').trigger('click');
				}, _this.duration);
			} else {
				clearTimeout(_this['timer_' + _this.instance]);
			}
		}
	}, {
		key: 'buttons',
		value: function buttons() {
			var _this = this;
			$(_this.container).on('click', '.dots i', function () {
				_this.node = $(this).index();
				_this.actions('dot');
			});

			$(_this.container).on('click', '.prev', function () {
				_this.actions('prev');
			});
			$(_this.container).on('click', '.next', function () {
				_this.actions('next');
			});
		}
	}, {
		key: 'changer',
		value: function changer() {
			var _this = this;
			$(_this.slide).removeClass('active').eq(_this.node).addClass('active');
			$(_this.container).find('.dots i').removeClass('active').eq(_this.node).addClass('active');

			if (_this.autoplay) _this.timer(true);
		}
	}, {
		key: 'dom',
		value: function dom(el, classes) {
			return $('<' + el + '>').clone().addClass(classes);
		}
	}, {
		key: 'arrows',
		value: function arrows() {
			var _this = this;
			var prev = _this.dom('div', 'prev');
			var next = _this.dom('div', 'next');
			var il = _this.dom('i', 'fa fa-angle-left');
			var ir = _this.dom('i', 'fa fa-angle-right');
			$(_this.container).append(prev.append(il), next.append(ir));
		}
	}, {
		key: 'dots',
		value: function dots() {
			var _this = this;
			var dots = _this.dom('div', 'dots');
			$(_this.container).append(dots);
			if ($(_this.slide).length > 1) {
				$(_this.slide).each(function () {
					var i = _this.dom('i', 'fa fa-circle');
					$(_this.container).append(dots.append(i));
				});
			}
		}
	}, {
		key: 'init',
		value: function init() {
			var _this = this;
			_this.node = 0;
			_this.instance = Math.round(Math.random() * 100000);
			_this.duration = 'duration' in _this ? _this.duration : 6000;
			_this.dots = 'dots' in _this ? _this.dots : true;
			_this.autoplay = 'autoplay' in _this ? _this.autoplay : true;
			_this.slide = 'slide' in _this ? _this.container + ' ' + _this.slide : _this.container + ' .slide';

			if (_this.dots && $(_this.slide).length > 1) _this.dots();
			if ($(_this.slide).length > 1) _this.arrows();

			_this.buttons();

			$(_this.slide + ':first-of-type').addClass('active');
			_this.changer(0);
		}
	}]);

	return Slider;
}();