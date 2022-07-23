<div class="listaAlbumViewAdmin">
	<div class="header_admin_ext">
		<span class="text_admin">Lista Album</span>
		<i class="fas fa-plus-circle nav_link" data-link="album" data-action="add"></i>
		<div class="form-inline my-2 my-lg-0 search_admin">
			<input class="form-control mr-sm-2" id="search_val" type="search" placeholder="Cerca" aria-label="Search">
			<button class="btn btn-dark my-2 my-sm-0" id="search_button" type="button">Cerca album</button>
	  	</div>
	</div>

	<div class="table_album_admin">
		<table class="table table-sm">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Nome</th>
					<th scope="col">Anno</th>
					<th scope="col">Action</th>
				</tr>
			</thead>
			<tbody id="table_row">
			</tbody>
		</table>
	</div>
</div>
