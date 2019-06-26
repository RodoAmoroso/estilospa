class Reservations {

	constructor(obj){
		$.extend(this,obj);
		this.init();
	}


	get_hours(){

		$(this.container).find('.schedule .hours').html('');
		
		let activeday = $(this.container).find('.week .day[data-dayname].active').attr('data-dayname');
		let day = $(this.container).find('.week .day[data-day].active').attr('data-day');
		let month = $(this.container).find('[data-month]').attr('data-month');
		let year = $(this.container).find('[data-year]').attr('data-year');

		Promise.all([
			ajax('site/reservations/get_hours',{
				idclient:this.idclient,
				activeday:activeday,
				date:year+'-'+month+'-'+day
			}),
			get_template('reservations/module-hour')
		])
			.then(promises=>{
				
				let data = promises[0];

				if(data.hours.length == 0) return false;
		
				let min = '09:00';
				let max = '21:00';
				$.each(data.hours,(kk,vv)=>{
					if(kk==0){
						min = vv[0];
					}
					if(kk==data.hours.length-1){
						max = vv[1];
					}
				});

				let hourminmin = min.split(':');
				let hourminmax = max.split(':');

				for(let i=parseInt(hourminmin[0]); i<=parseInt(hourminmax[0]); i++){
					
					let $template = $(promises[1]);
					$template.attr('data-hour',i+':00').find('.number').text(i+':00 hs.');
					$(this.container).find('.schedule .hours').append($template);

					if(i<parseInt(hourminmax[0])){
						$template = $(promises[1]);
						$template.attr('data-hour',i+':30').find('.number').text(i+':30 hs.');
						$(this.container).find('.schedule .hours').append($template);						
					}
					
				}

				$.each(data.taken_days,(kk,vv)=>{
					$(this.container).find('.schedule .hours .hour[data-hour="'+vv.hora+':'+(vv.minutos==0 ? '00' : vv.minutos)+'"]').addClass('disabled');					
				});

				let today = new Date();

				let selected_date = new Date(year,month-1,day);
				let today_date = new Date(today.getFullYear(),today.getMonth(),today.getDate());

				if(selected_date.getTime()==today_date.getTime()){
					$.each($(this.container).find('.schedule .hours .hour'),function(kk,vv){
						let time = $(this).attr('data-hour');
						let arrtime = time.split(':');
						if(parseInt(arrtime[0]) <= today.getHours()+3){
							$(this).addClass('disabled');
						}
					});					
				}
				if(selected_date.getTime()<today_date.getTime()){
					$(this.container).find('.schedule .hours .hour').addClass('disabled');
				}
				


			});
	}


	change_days(month,year,action,firstday,lastday){
		ajax('site/reservations/change_days',{month:month,year:year,action:action,firstday:firstday,lastday:lastday})
			.then(data=>{
				$(this.container).find('[data-month]').attr('data-month',data.month).text(data.month_name);
				$(this.container).find('[data-year]').attr('data-year',data.year).text(data.year);
				$.each(data.days,(k,v)=>{
					$(this.container).find('.week .day[data-day]:eq('+k+')').attr({'data-day':v.day,'data-dayname':v.dayname}).text(v.name+' '+v.day);
				});
				this.get_hours();
			});
	}

	change_month(month,year,action){
		ajax('site/reservations/change_month',{month:month,year:year,action:action})
			.then(data=>{
				this.change_days(data.month,data.year,'');
			});
	}

	init(){

		this.idclient = 'idclient' in this ? this.idclient : null;
		this.datetime = null;

		$(this.container+' .month').on('click','.next,.prev', btn => {
			let month = $(this.container).find('[data-month]').attr('data-month');
			let year = $(this.container).find('[data-year]').attr('data-year');
			let action = $(btn.currentTarget).attr('data-action');
			this.change_month(month,year,action);
		});

		$(this.container+' .week').on('click','.next,.prev',btn => {
			let month = $(this.container).find('[data-month]').attr('data-month');
			let year = $(this.container).find('[data-year]').attr('data-year');
			let action = $(btn.currentTarget).attr('data-action');
			let firstday = $(this.container).find('.day[data-day]').first().attr('data-day');
			let lastday = $(this.container).find('.day[data-day]').last().attr('data-day');
			this.change_days(month,year,action,firstday,lastday);
		});

		$(this.container).on('click','.week .day[data-day]',btn => {
			$(this.container).find('.week .day[data-day]').removeClass('active');
			$(btn.currentTarget).addClass('active');
			this.get_hours();
		});

		$(this.container).on('click','.hours .hour:not(.disabled) .btn',btn => {
			$('.calendar-promo .hours .btn').removeClass('active');
			
			$(btn.currentTarget).addClass('active');

			let day = $(this.container).find('.week .day[data-day].active');
			let month = $(this.container).find('[data-month]');
			let year = $(this.container).find('[data-year]');
			let hour = $(this.container).find('[data-action=select].active');

			this.datetime = {
				text:day.text() + ' de ' + month.text() + ' ' + year.text() + ' ' + hour.parent().parent().attr('data-hour') + 'hs.',
				date:year.text()+'-'+month.attr('data-month')+'-'+day.attr('data-day')+' '+hour.parent().parent().attr('data-hour')+':00'
			};

			if('callback' in this){
				this.callback(this.datetime);
			}			

		});


		this.get_hours();

	}

}