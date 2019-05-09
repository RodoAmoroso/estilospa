'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Reservations = function () {
	function Reservations(obj) {
		_classCallCheck(this, Reservations);

		$.extend(this, obj);
		this.init();
	}

	_createClass(Reservations, [{
		key: 'get_hours',
		value: function get_hours() {
			var _this = this;

			$(this.container).find('.schedule .hours').html('');

			var activeday = $(this.container).find('.week .day[data-dayname].active').attr('data-dayname');
			var day = $(this.container).find('.week .day[data-day].active').attr('data-day');
			var month = $(this.container).find('[data-month]').attr('data-month');
			var year = $(this.container).find('[data-year]').attr('data-year');

			Promise.all([ajax('site/reservations/get_hours', {
				idclient: this.idclient,
				activeday: activeday,
				date: year + '-' + month + '-' + day
			}), get_template('reservations/module-hour')]).then(function (promises) {

				var data = promises[0];

				if (data.hours.length == 0) return false;

				var min = '09:00';
				var max = '21:00';
				$.each(data.hours, function (kk, vv) {
					if (kk == 0) {
						min = vv[0];
					}
					if (kk == data.hours.length - 1) {
						max = vv[1];
					}
				});

				var hourminmin = min.split(':');
				var hourminmax = max.split(':');

				for (var i = parseInt(hourminmin[0]); i <= parseInt(hourminmax[0]); i++) {

					var $template = $(promises[1]);
					$template.attr('data-hour', i + ':00').find('.number').text(i + ':00 hs.');
					$(_this.container).find('.schedule .hours').append($template);

					if (i < parseInt(hourminmax[0])) {
						$template = $(promises[1]);
						$template.attr('data-hour', i + ':30').find('.number').text(i + ':30 hs.');
						$(_this.container).find('.schedule .hours').append($template);
					}
				}

				$.each(data.taken_days, function (kk, vv) {
					$(_this.container).find('.schedule .hours .hour[data-hour="' + vv.hora + ':' + (vv.minutos == 0 ? '00' : vv.minutos) + '"]').addClass('disabled');
				});
			});
		}
	}, {
		key: 'change_days',
		value: function change_days(month, year, action, firstday, lastday) {
			var _this2 = this;

			ajax('site/reservations/change_days', { month: month, year: year, action: action, firstday: firstday, lastday: lastday }).then(function (data) {
				$(_this2.container).find('[data-month]').attr('data-month', data.month).text(data.month_name);
				$(_this2.container).find('[data-year]').attr('data-year', data.year).text(data.year);
				$.each(data.days, function (k, v) {
					$(_this2.container).find('.week .day[data-day]:eq(' + k + ')').attr({ 'data-day': v.day, 'data-dayname': v.dayname }).text(v.name + ' ' + v.day);
				});
				_this2.get_hours();
			});
		}
	}, {
		key: 'change_month',
		value: function change_month(month, year, action) {
			var _this3 = this;

			ajax('site/reservations/change_month', { month: month, year: year, action: action }).then(function (data) {
				_this3.change_days(data.month, data.year, '');
			});
		}
	}, {
		key: 'init',
		value: function init() {
			var _this4 = this;

			this.idclient = 'idclient' in this ? this.idclient : null;
			this.datetime = null;

			$(this.container + ' .month').on('click', '.next,.prev', function (btn) {
				var month = $(_this4.container).find('[data-month]').attr('data-month');
				var year = $(_this4.container).find('[data-year]').attr('data-year');
				var action = $(btn.currentTarget).attr('data-action');
				_this4.change_month(month, year, action);
			});

			$(this.container + ' .week').on('click', '.next,.prev', function (btn) {
				var month = $(_this4.container).find('[data-month]').attr('data-month');
				var year = $(_this4.container).find('[data-year]').attr('data-year');
				var action = $(btn.currentTarget).attr('data-action');
				var firstday = $(_this4.container).find('.day[data-day]').first().attr('data-day');
				var lastday = $(_this4.container).find('.day[data-day]').last().attr('data-day');
				_this4.change_days(month, year, action, firstday, lastday);
			});

			$(this.container).on('click', '.week .day[data-day]', function (btn) {
				$(_this4.container).find('.week .day[data-day]').removeClass('active');
				$(btn.currentTarget).addClass('active');
				_this4.get_hours();
			});

			$(this.container).on('click', '.hours .hour:not(.disabled) .btn', function (btn) {
				$('.calendar-promo .hours .btn').removeClass('active');

				$(btn.currentTarget).addClass('active');

				var day = $(_this4.container).find('.week .day[data-day].active');
				var month = $(_this4.container).find('[data-month]');
				var year = $(_this4.container).find('[data-year]');
				var hour = $(_this4.container).find('[data-action=select].active');

				_this4.datetime = {
					text: day.text() + ' de ' + month.text() + ' ' + year.text() + ' ' + hour.parent().parent().attr('data-hour') + 'hs.',
					date: year.text() + '-' + month.attr('data-month') + '-' + day.attr('data-day') + ' ' + hour.parent().parent().attr('data-hour') + ':00'
				};

				if ('callback' in _this4) {
					_this4.callback(_this4.datetime);
				}
			});

			this.get_hours();
		}
	}]);

	return Reservations;
}();