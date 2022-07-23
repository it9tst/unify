<div class="listaArtistiViewAdmin">
	<div class="header_admin_ext">
		<span class="text_admin">Lista Artisti</span>
		<i class="fas fa-plus-circle nav_link" data-link="artista" data-action="add"></i>
		<div class="form-inline my-2 my-lg-0 search_admin">
			<input class="form-control mr-sm-2" id="search_val" type="search" placeholder="Cerca" aria-label="Search">
			<button class="btn btn-dark my-2 my-sm-0" id="search_button" type="button">Cerca artista</button>
	  	</div>
	</div>
	<div class="table_artist_admin">
		<table class="table table-sm">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Nome</th>
					<th scope="col">Data di nascita</th>
					<th scope="col">Informazioni</th>
					<th scope="col">Action</th>
				</tr>
			</thead>
			<tbody id="table_row">
			</tbody>
		</table>
	</div>
</div>
