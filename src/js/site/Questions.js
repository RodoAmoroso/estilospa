class Questions {

	constructor(obj){
		$.extend(this,obj);
		this.init();
	}

	paginate(){
		if(this.results == false) return false;
		$(this.container).append(`
			<div class="load-more mt-4">
				<a href="#" data-page="${this.page}" class="btn btn-outline-dark btn-sm">
					<span>Cargar más</span> <i class="fal fa-angle-down fa-fw"></i>
				</a>
			</div>
		`);
	}


	async get(){

		if(this.page==1) $(this.container).html('');

		const questions = await ajax('site/questions/get',{
			rowid:$(this.form).find('[name=rowid]').val(),
			type:$(this.form).find('[name=type]').val(),
			page:this.page,
			limit:this.limit,
			limit_responses:this.limit_responses
		})

		if(questions.results == false){
			$(this.container).html('<p>Aún no se han hecho preguntas</p>');
			return false;
		}

		const promise = await Promise.all([
			get_template('questions/questions'),
			get_template('questions/questions-responses')
		])

		//console.log(data.results);
		this.total_loaded += questions.results.length
		this.results = questions.results

		questions.results.map(question=>{

			const $module = $(promise[0])
			$module.find('[data-content=message]').html(question.message)
			$module.find('[data-content=added]').text(`Enviada: ${question.creado} hs.`)

			if(question.responses!=false){

				$module.append('<h5 class="response-title text-gray-50">Respuestas:</h5>')

				question.responses.map(response=>{

					const $mod_response = $(promise[1])

					if(response.client){
						if(response.approved=='0') return false
						$mod_response.find('[data-content=client]').text(response.client.name)
						$mod_response.find('[data-content=client]').attr({href:`${ROOT}centros/${response.client.permalink}`})
						$mod_response.find('[data-content=thumb]').css({backgroundImage:`url(${response.client.imagery.logo})`})
					}else{
						$mod_response.find('[data-content=client]').text('EstiloSpa')
						$mod_response.find('[data-content=client]').attr({href:`#`})
						$mod_response.find('[data-content=thumb]').css({backgroundImage:`url(${ROOT}assets/estilospa-logo-square.jpg)`})
					}

					$mod_response.find('[data-content=response]').html(response.message)
					$mod_response.find('[data-content=added]').text(`Enviada: ${response.creado} hs.`)
					$module.append($mod_response)
				});

				if(question.total_responses>this.limit_responses){
					$module.append(`
						<div class="more-responses">
							<a href="${ROOT}pregunta/${question.id}">ver todas las respuestas de los centros</a>
						</div>`
					);
				}

			}

			$(this.container).append($module)

			$(this.container).find('.load-more').remove()
			if(this.total_loaded < parseInt(questions.total)){
				this.paginate()
			}

		})

	}

	async add(){

		const post = get_form($(this.form))
		const response = await ajax('site/questions/add',post)
		$(this.form).find('textarea').val('')
		this.mode = 'getbypromo'
		this.get()
	}


	async response(){

		const post = get_form($(this.form_response))
		const response = await ajax('site/questions/response',post)
		$(this.form_response).find('textarea').val('')
		window.location.reload()

	}

	init(){
		this.results = false;
		this.page = 'page' in this ? this.page : 1;
		this.limit = 'limit' in this ? this.limit : 8;
		this.limit_responses = 'limit_responses' in this ? this.limit_responses : 4;
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

		console.log('questions.class')

	}


}