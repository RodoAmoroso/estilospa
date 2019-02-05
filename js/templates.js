var Templates = {
	div:$('<div>'),
	button:$('<button>'),
	h1:$('<h1>'),
	h2:$('<h2>'),
	h3:$('<h3>'),
	h4:$('<h4>'),
	span:$('<span>'),
	small:$('<small>'),
	i:$('<i>'),
	p:$('<p>'),
	hr:$('<hr />'),
	a:$('<a>'),
	card:function(){
		return this.button.addClass('btn btn-success');
	},
	list_group_btn_check:function(){
		var mod = this.button.clone().addClass('list-group-item').append(this.i.clone().addClass('fa fa-square fa-fw'),this.span.clone());
		return mod;
	},
	list_group_item:function(){
		var mod = this.div.clone().addClass('list-group-item').append(this.span.clone(),this.span.clone().addClass('label label-success pull-right'));
		return mod;
	},
	list_group_btn:function(){
		var mod = this.button.clone().addClass('list-group-item').append(this.span.clone());
		return mod;
	},
	mod_list:function(){
		var btndelete = this.button.clone().addClass('btn btn-xs btn-danger delete').append(this.i.clone().addClass('fa fa-trash'));
		var btnedit = this.button.clone().addClass('btn btn-xs btn-success edit').append(this.i.clone().addClass('fa fa-pencil'),' ',this.span.clone().text('Editar'));
		var mod = this.div.clone().addClass('mod-list').append(
			this.h4.clone(),
			this.p.clone(),
			this.div.clone().addClass('buttons').append(
				btndelete,' ',
				btnedit,
			)
		);
		return mod;
	}
}