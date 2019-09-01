<!-- MOD CARD -->
<div id="mod_card" class="mod-card col-xs-12 col-sm-4 dp-none">
	<div class="card-inner">
		<div class="thumb thumb-cover thumb-fullx180 bg-black" >
			<div class="buttons">
				<button class="btn btn-xs btn-default preview"><i class="fa fa-eye"></i></button>
				<button class="btn btn-xs btn-danger delete"><i class="fa fa-trash"></i></button>
				<button class="btn btn-xs btn-primary edit"><i class="fa fa-pencil"></i></button>
			</div>
		</div>
		<div class="caption">
			<h1></h1>
			<p class="description"></p>
			<div class="foot">
				<div class="selector clickable" data-value="0" data-toggle="selector"><small>Seleccionar</small> <i class="fa fa-circle-o"></i></div>
			</div>
			<div class="buttons">
				<button class="btn btn-xs btn-default preview"><i class="fa fa-eye"></i></button>
				<button class="btn btn-xs btn-danger delete"><i class="fa fa-trash"></i></button>
				<button class="btn btn-xs btn-primary edit"><i class="fa fa-pencil"></i></button>
			</div>
		</div>
	</div>
</div>

<!-- MOD LIST -->
<div id="mod_list_thumb" class="mod-list-thumb dp-none">
	<div class="thumb thumb-cover" ></div>
	<div class="data">
		<div class="mod-header">
			<h1></h1>
			<div class="buttons">
				<button class="btn btn-sm btn-danger delete" title="Borrar"><i class="fa fa-trash"></i></button>			 
				<button class="btn btn-success btn-sm edit" title="Editar"><i class="fa fa-pencil"></i> EDITAR</button>
			</div>
		</div>
		<p></p>
	</div>	
</div>

<!-- MOD LIST -->
<div id="mod_list" class="mod-list dp-none" >
	<h4></h4>
	<div class="buttons">
		<button class="btn btn-xs btn-danger delete"><i class="fa fa-trash"></i></button>
		<button class="btn btn-xs btn-primary edit"><i class="fa fa-pencil"></i></button>
		<button class="btn btn-xs btn-success add"><i class="fa fa-plus"></i></button>
	</div>
</div>

<div id="mod_list_caption" class="mod-list dp-none" >
	<h4 class="title"></h4>
	<p class="caption"></p>
	<div class="buttons">
		<button class="btn btn-xs btn-danger delete"><i class="fa fa-trash"></i></button>
		<button class="btn btn-xs btn-primary edit"><i class="fa fa-pencil"></i></button>
		<button class="btn btn-xs btn-success add"><i class="fa fa-plus"></i></button>
	</div>
</div>

<!-- MOD THUMB -->
<div id="mod_thumb" class="thumbnail bg-black thumb-150x150 thumb-cover dp-none">
	<div class="redbg">
		<div class="dp-table wd-100 hg-100">
			<div class="dp-table-cell text-center">
				<button class="edit btn btn-sm btn-primary" title="Editar"><i class="fa fa-pencil" ></i></button>
			</div>
		</div>		
		<i class="delete fa fa-times cl-white" title="Borrar"></i>
	</div>
</div>

<!-- MOD PANEL -->
<div id="mod_panel" class="panel panel-default panel-admin-mod dp-none">
	<div class="panel-heading"></div>
	<div class="panel-body">
		<div class="list-group"></div>
	</div>
</div>
<div id="mod_panel_group_item" class="list-group-item dp-none">
	<div class="item">
		<i class="fa fa-square"></i> 
		<span></span>
	</div>
	<div class="buttons">
		<button class="btn btn-xs btn-primary edit"><i class="fa fa-pencil"></i></button>
		<button class="btn btn-xs btn-danger delete"><i class="fa fa-trash"></i></button>
		<button class="btn btn-xs btn-white view"><i class="fa fa-eye"></i></button>
	</div>
</div>

<!-- MOD SALES -->
<div id="mod_sale" class="mod-sales dp-none">
	<div class="sale-header">
		<div class="thumb-container">
			<div class="thumb thumb-cover"></div>
		</div>
		<div class="caption">
			<h1 data-tag="ordernumber" class="title">Orden Nro.: </h1>
			<h4 data-tag="price" class="subtitle">$0000 - Cant. 11</h4>
			<h4 data-tag="title" class="title-promo">Cliente / Title Promo</h4>
			<small data-tag="date" class="date">Fecha de compra: 00/00/0000 10:00:00 hs.</small>

			<div class="status">
				<div class="payment-status"></div>
				<div class="sale-actions">
					<small>Servicio: </small>
					<div data-group="status" class="btn-group">
						<button type="button" class="btn btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span data-tag="status" >Pendiente</span> <span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<li><a data-value="1" href="#">Pendiente</a></li>
							<li><a data-value="2" href="#">Brindado</a></li>
							<li><a data-value="3" href="#">Cancelado</a></li>
						</ul>
					</div>

				</div>
			</div>
		
		</div>
		<div class="action">
			<button data-button="toggle" class="btn btn-primary btn-block btn-xs"><i class="fa fa-chevron-down"></i></button>
		</div>
	</div>
	<div class="sale-footer">
		<div class="sale-user">
			<div class="user">
				<div class="item">
					<div class="user-thumb thumb-cover"></div>
				</div>
				<div class="item">
					<div data-tag="username" class="name"></div>
					<a data-tag="mail" href="#" class="mail"></a>
				</div>
			</div>
			<div class="feedback" >
				<p data-tag="comment"><i>(El usuario aún no ha calificado)</i></p>
				<span class="stars">
					<i class="fa"></i>
					<i class="fa"></i>
					<i class="fa"></i>
					<i class="fa"></i>
					<i class="fa"></i>
				</span>										
			</div>
		</div>		
	</div>	
</div>