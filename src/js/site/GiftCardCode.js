$(()=>{


  $('[data-form="giftcard-code"]').submit(async form=>{
    form.preventDefault()
    const post = get_form(form.currentTarget)

    console.log(post)
  })
  $('[data-form="giftcard-code"] input').on('keyup',input=>{
    const wrapper = $(input.currentTarget).closest('.giftcard-code-input')
    const index = $(input.currentTarget).index()
    if(wrapper.find('input').length-1==index) return false 

    wrapper.find(`input:eq(${index+1})`).focus()

  })


})