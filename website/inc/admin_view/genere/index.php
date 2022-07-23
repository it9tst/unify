<div class="listaGenereViewAdmin">
	<div class="header_admin_ext">
		<span class="text_admin">Lista Generi</span>
		<div class="form-inline my-2 my-lg-0 search_admin">
			<input class="form-control mr-sm-2" id="add_genere" type="search" placeholder="Aggiungi genere">
			<i class="fas fa-plus-circle" id="add_genere_button"></i>
	  	</div>
	</div>

	<div class="table_genere_admin">
		<table class="table table-sm">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Nome</th>
					<th scope="col">Action</th>
				</tr>
			</thead>
			<tbody id="genere_row">
				<?php $i=1;?>
				<?php foreach($this->Generi as $k=>$v):?>
					<tr>
						<td><?php echo $i++;?></td>
						<td><?php echo $v++;?></td>
						<td><button type="button" class="btn btn-dark elimina_genere" data-id="<?php echo $k;?>">Elimina</button></td>
					</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
</div>