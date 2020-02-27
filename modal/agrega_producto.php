<?php //include('./ajax/is_logged.php'); ?>
	<!-- Modal -->
<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Agregar productos</h4>
			</div>
			<div class="modal-body">
			 	<form class="form-horizontal">
			 		<div class="form-group">
			 			<div class="col-sm-6">
			 				<?php
			 				$tipo=trim($_SESSION['user_tipo']);	
			 				if ($tipo=='M') 
			 				{ ?>
			 					<input type="text" class="form-control" id="n_art" placeholder="Ingresa descripcion del articulo" autocomplete="off">
			 					</div>
			 					<button type="button" class="btn btn-default" onclick=""><span class='glyphicon glyphicon-search'></span> Top 10</button><?php

			 				}
			 				
			 				elseif ($tipo=='V') 
			 				{ ?>
			 					<input type="text" class="form-control" id="n_art" placeholder="Ingresa descripcion del articulo" autocomplete="off" >
			 					</div>
			 					<button type="button" class="btn btn-default" onclick='b_productos(1);' id="buscaclientes"><span class="glyphicon glyphicon-search" ></span> Buscar</button>
			 					<button type="button" class="btn btn-default" onclick="b_productos(2)"><span class='glyphicon glyphicon-plus'></span> Top 10</button> <?php
			 				}

			 				elseif ($tipo=='S') 
			 				{ ?>
			 					<input type="text" class="form-control" id="n_art" placeholder="Ingresa descripcion del articulo" autocomplete="off">
			 					</div>
			 					<button type="button" class="btn btn-default" onclick=""><span class='glyphicon glyphicon-search'></span> Top 10</button> <?php
			 				} ?> 

			 			

						<div id="loader_add" class="pull-right"></div><!-- Carga gif animado -->
						<div class="outer_div_add" ></div><!-- Datos ajax Final -->
					</div>
				</form>
				
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
  	</div>
</div>

<script type="text/javascript" src="./js/ordenes.js"></script>