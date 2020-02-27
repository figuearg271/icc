		<?php //include('./ajax/is_logged.php'); ?>
			<!-- Modal -->
			<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Buscar Facturas</h4>
				  </div>
				  <div class="modal-body">
					<form class="form-horizontal">
					  <div class="form-group">
						<div class="col-sm-6">

							<?php
						
						$tipo=trim($_SESSION['user_tipo']);
						
							if ($tipo=='M') {
								?>
								<input type="text" class="form-control" id="n_factura" placeholder="Ingresa el numero de factura a reclamar" autocomplete="off">

	                           	</div>
						<button type="button" class="btn btn-default" onclick="buscar_factura_matriz(1)"><span class='glyphicon glyphicon-search'></span> Buscar</button> 
								<?php }
							elseif ($tipo=='V') { 
								?>
									<input type="text" class="form-control" id="n_factura" placeholder="Ingresa el numero de factura a reclamar" autocomplete="off" onkeyup='llena_facturas_cliente_vendedor(1);' >   	
									<div id="suggesstion-box2"></div>
								</div>
						<button type="button" class="btn btn-default" onclick="buscar_factura_vendedor(1)"><span class='glyphicon glyphicon-search'></span> Buscar </button> 
						
	                           

								<?php
							}
							elseif ($tipo=='S') {
								?>
								<input type="text" class="form-control" id="n_factura" placeholder="Ingresa el numero de factura a reclamar" autocomplete="off">

	                           </div>
								<button type="button" class="btn btn-default" onclick="buscar_factura_sucursal(1)"><span class='glyphicon glyphicon-search'></span> Buscar </button> 
	                           

								<?php
							} ?>						
					  </div>
					</form>
					<div id="loader_bfactura" style="position:absolute; text-align:center;top:55px; width:100%; display:none;"></div><!-- Carga gif animado -->
					<div class="outer_div_bfactura" ></div><!-- Datos ajax Final -->
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" class="close" data-dismiss="modal" >Cerrar</button>
					
				  </div>
				</div>
			  </div>
			</div>

			<script type="text/javascript" src="./js/reclamos.js"></script>
	