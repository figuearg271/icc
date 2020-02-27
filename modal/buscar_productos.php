<?php //include('./ajax/is_logged.php'); ?>
	<!-- Modal -->
	<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">Buscar productos</h4>
		  </div>
		 <div class="modal-body">
			
			<form class="form-horizontal"  onkeypress="return anular(event)">
			  <div class="form-group">
				<div class="col-sm-6"> <?php

					$tipo=trim($_SESSION['user_tipo']);

					if ($tipo=='M') { ?>
						<input type="text" class="form-control" id="n_art" placeholder="Ingresa descripcion del articulo" onkeyup="bproductosmatriz(1)" autocomplete="off">
                        <input type="text" id="codmatriz" value=" <?php echo $_SESSION['user_email']; ?>" >
                       	<input type="hidden" id="nsuc" value=" <?php echo base64_decode($_GET['mon']); ?>" >
                       	<input type="hidden" id="codsuc" value=" <?php echo base64_decode($_GET['eva']); ?>" >

                       	</div>
						<button type="button" class="btn btn-default" onclick="load3(1)"><span class='glyphicon glyphicon-search'></span> Top 10</button>
						<div id="loader" class="pull-right"></div><!-- Carga gif animado -->
						<div class="outer_div" ></div><!-- Datos ajax Final --> <?php
					}
					elseif ($tipo=='V') {
						if($action=='pedido'){ ?>
							<input type="text" class="form-control" id="n_art"   placeholder="Ingresa descripcion del articulo" autocomplete="off">
							<input type="hidden" id="cod_matriz" value=" <?php echo base64_decode($_GET['matriz']); ?>" >
	                       	<input type="hidden" id="n_suc" value=" <?php echo base64_decode($_GET['mon']); ?>" >
	                       	<input type="hidden" id="cod_suc" value=" <?php echo base64_decode($_GET['eva']); ?>" >

	                       	</div>

	                       	<button type="button" class="btn btn-default" disabled onclick='bproductosvendedor(1);' id="buscaclientes">
									<span class="glyphicon glyphicon-search" ></span> Buscar</button>

							<!--<button type="button" class="btn btn-default" onclick="top10v(1)"><span class='glyphicon glyphicon-plus'></span> Top 20</button>-->
                       	 <?php }
						if($action=='cotizaciones'){ ?>
							<input type="text" class="form-control" id="n_art" placeholder="Ingresa descripcion del articulo" autocomplete="off">

							<input type="hidden" id="cod_matriz" value=" <?php echo base64_decode($_GET['matriz']); ?>" >
	                       	<input type="hidden" id="n_suc" value=" <?php echo base64_decode($_GET['mon']); ?>" >
	                       	<input type="hidden" id="cod_suc" value=" <?php echo base64_decode($_GET['eva']); ?>" >

	                       	</div>

	                       	<button type="button" class="btn btn-default" onclick='b_productosvendedor_varios(1);' id="buscaclientes">
									<span class="glyphicon glyphicon-search" ></span> Buscar</button>

							<!--<button type="button" class="btn btn-default" onclick="top10v(1)"><span class='glyphicon glyphicon-plus'></span> Top 10</button>-->
	                    <?php }
						if($action=='cotizaciones_varios'){ ?>
							<input type="text" class="form-control" id="n_art" placeholder="Ingresa descripcion del articulo" autocomplete="off">

							<input type="hidden" id="cod_matriz" value=" <?php echo $_GET['matriz']; ?>" >
	                       	<input type="hidden" id="n_suc" value=" <?php echo $_GET['mon']; ?>" >
	                       	<input type="hidden" id="cod_suc" value=" <?php echo $_GET['eva']; ?>" >

	                       	</div>
	                       	<button type="button" class="btn btn-default" onclick='b_productosvendedor_varios(1);' id="buscaclientes">
									<span class="glyphicon glyphicon-search" ></span> Buscar</button>

							<!-- <button type="button" class="btn btn-default" onclick="top10v(1)"><span class='glyphicon glyphicon-plus'></span> Top 10</button> -->
                       	<?php }
							?>




						<div id="loader_modificando" class="pull-right"></div><!-- Carga gif animado -->
						<div class="outer_div_modificando" ></div><!-- Datos ajax Final --><?php
					}
					elseif ($tipo=='S') {
						?>
						<input type="text" class="form-control" id="n_art" placeholder="Ingresa descripcion del articulo" onkeyup="load2(1)" autocomplete="off">
                       <input type="hidden" id="codmatriz" value=" <?php echo $_SESSION['user_email']; ?>" >
                       <input type="hidden" id="nsuc" value=" <?php echo base64_decode($_GET['mon']); ?>" >
                       <input type="hidden" id="codsuc" value=" <?php echo base64_decode($_GET['eva']); ?>" >

                       </div>
					<button type="button" class="btn btn-default" onclick="load3(1)"><span class='glyphicon glyphicon-search'></span> Top 10</button>
					<div id="loader" class="pull-right"></div><!-- Carga gif animado -->
					<div class="outer_div" ></div><!-- Datos ajax Final --><?php
					} ?>
			  	</div>
			</form>

		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>

		  </div>
		</div>
	  </div>
	</div>

<script type="text/javascript" src="./js/newfactura.js"></script>
