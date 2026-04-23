const SwalWarn = Swal.mixin({
	type: 'warning',
	showCancelButton: true,
	cancelButtonText: 'Cancelar',
	reverseButtons: true,
	confirmButtonText: 'Si'
})

class Main {

	constructor(){
		
		this.init()
	}

	close_submenu(){

		$('[data-submenu]').removeClass('active')
		//$('nav.main-nav').removeClass('active')
		//$('nav.main-nav li.item').removeClass('active')
		//if($(window).width()<991) $('header.header ul.menu').addClass('active')

	}

	init(){

		console.log('main');
	
		// Tooltips
		$('[title]').tooltip();
	
		// Menú hamburguesa
		$('nav .fa-bars').click(() => {
			$('#main_menu').slideToggle();
		});
		$(window).resize(() => {
			if ($(window).width() > 767) {
				$('#main_menu').removeAttr('style').slideDown();
			}
		});
	
		// Slide toggles
		$('[data-toggle=slide]').click(e => {
			e.preventDefault();
			const ref = $(e.currentTarget).attr('href');
			$(ref).toggleClass('active');
		});
	
		// Search bar toggle
		$('#btn_search_bar').click(() => {
			$('#search_bar').slideToggle();
		});
	
		// Switch toggle
		$('[data-toggle="switch"]').click(function() {
			$(this).find('i').toggleClass('fa-toggle-off fa-toggle-on');
			$(this).find('span').text($(this).find('i').hasClass('fa-toggle-on') ? 'Si' : 'No');
		});
	
		// Checkbox toggle
		$('[data-toggle="btn-checkbox"]').click(function() {
			$(this).find('i').toggleClass('fa-square fa-check-square');
		});
	
		// ScrollUp
		$.scrollUp({ scrollText: '<i class="fa fa-angle-up"></i>' });
	
		// Form main search
		$('#form_main_search').submit(e => {
			e.preventDefault();
			const main = $('[name="main"]', e.currentTarget).val();
			const location = $('[name="location"]', e.currentTarget).val();
			const type = $('[name="type"]', e.currentTarget).val();
			window.location.href = `${ROOT}${type}/${main === '' ? '-' : permalink(main)}/${permalink(location)}`;
		});
		$('#form_main_search [name="type"]').change(() => {
			$('#form_main_search').trigger('submit');
		});
	
		// Favoritos
		$('[data-btn-action="fav"]').click(async form=>{
			form.preventDefault();
			const $this = $(form.currentTarget);
			const promoid = $this.attr('data-promoid') || 0;
			const clientid = $this.attr('data-clientid') || 0;
			try {
				const data = await ajax('site/users/favs', {
					promoid:promoid, 
					clientid:clientid
				});
				const $icon = $this.find('i');
				$icon.removeClass();
				if (data.is_fav == 1) {
					$icon.addClass('fa fa-heart active');
				} else {
					$icon.addClass('fa fa-heart-o');
				}
			} catch (err) {
				console.error(err);
			}
		});
	
		// Newsletter
		$('#form_newsletter').submit(async form=>{
			form.preventDefault();
			const post = get_form(form.currentTarget);
			try {
				const data = await ajax('site/forms/newsletter', post);
				$('#form_newsletter input').val('');
				Swal.fire({ type: 'success', html: data.message });
			} catch (err) {
				console.error(err);
			}
		});
	
		// Scroll to
		$('[data-toggle=scrollto]').click(e => {
			e.preventDefault();
			const target = $(e.currentTarget).attr('data-target');
			$('body,html').animate({ scrollTop: $(target).offset().top - 100 });
		});
	
		// Submenús
		$('[data-toggle="submenu"]').click(e => {
			e.preventDefault();
			e.stopPropagation();
			const target = $(e.currentTarget).attr('data-target');
			$(`[data-submenu]`).not(`[data-submenu="${target}"]`).removeClass('active');
			$(`[data-submenu="${target}"]`).toggleClass('active');
		});
		$('body').click(event=>{
			let wrapper = $(event.target).closest('.main-submenu')
			if(wrapper.length===0){
				this.close_submenu()
				///this.close_menu()
			}
		})
		$('[data-toggle="close-submenu"]').click(btn=>{
			this.close_submenu()
		})
	
		$('[data-toggle="logout"]').click(async btn=>{
			const response = await ajax('site/users/logout',{
				token:TOKEN
			})
			window.location.href = ROOT
		})

		setTimeout(()=>{
			$('.gift-balance-notification').addClass('active')
		},1000)
		$('.gift-balance-notification').on('click','.close-balance-notification',btn=>{
			$('.gift-balance-notification').removeClass('active')
		})


		$('body').on('click','[data-swal]',el=>{
			const text = $(el.currentTarget).attr('data-swal')
			Swal.fire({
				type:'info',
				html:text
			})
		})

	}

}

new Main