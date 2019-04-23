class Questions {

	constructor(obj){
		$.extend(this,obj);
		this.init();
	}

	paginate(){
		if(this.results == false) return false;		
		$(this.container).append('<div class="load-more"><a href="#" data-page="'+this.page+'">Cargar más</a></div>');
	}


	get(){

		if(this.page==1) $(this.container).html('');
		
		ajax('site/questions/'+this.mode,{
			rowid:$(this.form).find('[name=rowid]').val(),
			page:this.page,
			limit:this.limit
		})
			.then(function(response){
				return response;
			})
			.then(data=>{

				Promise.all([get_template('questions/questions'),get_template('questions/questions-responses')])
				.then(templates=>{

					if(data.results == false){
						$(this.container).html('<p>Aún no se han hecho preguntas</p>'); 
						return false;
					}
					//console.log(page_maker(this.limit,data.total));
					this.total_loaded += data.results.length;
					this.results = data.results;
					$.each(data.results,(k,v)=>{
						var $module = $(templates[0]);
						$module.find('[data-content=message]').text(v.message);
						$module.find('[data-content=added]').text('Enviada: '+v.creado+' hs.');
						if(v.responses!=false){
							$.each(v.responses,(kr,vr)=>{
								let $mod_response = $(templates[1]);
								$mod_response.find('[data-content=response]').text(vr.message);
								$mod_response.find('[data-content=added]').text('Enviada: '+vr.creado+' hs.');
								$module.append($mod_response);
							});
						}
						$(this.container).append($module);

						$(this.container).find('.load-more').remove();
						if(this.total_loaded < parseInt(data.total)){
							this.paginate();
						}
						
					});

				});

				
			});
	}

	add(){

		var post = get_form($(this.form));
		ajax('site/questions/add',post)
			.then(data=>{
				$(this.form).find('textarea').val('');
				this.get('getbypromo');
			});

	}


	response(){

		var post = get_form($(this.form_response));
		ajax('site/questions/response',post)
			.then(data=>{
				$(this.form_response).find('textarea').val('');
				window.location.reload();
			});

	}

	init(){
		this.results = false;
		this.page = 'page' in this ? this.page : 1;
		this.limit = 'limit' in this ? this.limit : 8;
		this.total_loaded = 0;
		this.mode = 'mode' in this ? this.mode : 'getbypromo';
		this.form = 'form' in this ? this.form : '#form_null';
		this.form_response = 'form_response' in this ? this.form_response : '#form_null';
		this.container = 'container' in this ? this.container : '#container_null';
		
		$(this.container).on('click','.load-more a', e => {
			e.preventDefault();
			var page = parseInt($(e.currentTarget).attr('data-page'));
			$(e.currentTarget).attr({'data-page':(page+1)});
			this.page = page+1;
			this.get();
		});

		$(this.form).submit(e=>{
			e.preventDefault();
			this.add();
		});
		$(this.form_response).submit(e=>{
			e.preventDefault();
			this.response();
		});

	}


}