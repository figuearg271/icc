/*$(document).ready(function(){
			load(1);
			
		});*/

function load(page){
	
	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/buscar_clientes.php?action=ajax',
		 beforeSend: function(objeto){
		 $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
	  },
		success:function(data){
			$(".outer_div").html(data).fadeIn('slow');
			$('#loader').html('');
			$('[data-toggle="tooltip"]').tooltip({html:true}); 
			
		}
	})
}
//NUEVA FUNCION AUTORIZACION 
function loadAuto(page){
	
	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/buscar_clientes.php?action=autorizaciones',
		 beforeSend: function(objeto){
		 $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
	  },
		success:function(data){
			$(".outer_div").html(data).fadeIn('slow');
			$('#loader').html('');
			$('[data-toggle="tooltip"]').tooltip({html:true}); 
			
		}
	})
}

function load2(page){
	
	var nombrecliente= $("#nomclie").val();
	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/buscar_clientes.php?action=pornombre&clie='+nombrecliente,
		 beforeSend: function(objeto){
		 $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
	  },
		success:function(data){
			$(".outer_div").html(data).fadeIn('slow');
			$('#loader').html('');
			$('[data-toggle="tooltip"]').tooltip({html:true}); 
			
		}
	})
}

function viewfacturas(id){ //muestra las facturas que tiene pendiente de pago
	
 	var saldosuc= document.getElementById('saldosuc_'+id).value;
	
	if (saldosuc>0) {
		document.getElementById("buscaclientes").style.display="none";
		document.getElementById("nomclie").style.display="none";

		//document.getElementById("n_cliente").style.display="none";
		
		document.getElementById("nclientes").innerHTML = "Facturas pendiente de pago";
		document.getElementById("backclientes").style.display="initial";		

		$("#loader").fadeIn('slow');
		$.ajax({
		url:'./ajax/buscar_facturas_estado.php?action=vendedor&idsucursal='+id,
		beforeSend: function(objeto){
		$('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
		},
		success:function(data){
		$(".outer_div").html(data).fadeIn('slow');
		$('#loader').html('');
		$('[data-toggle="tooltip"]').tooltip({html:true}); 
		}
		});
	} 
	else { alert("No tiene documentos pendientes de pago") }	
}

function ver_abonos(id){
	
	//document.getElementById("backclientes").style.display="none";
	//document.getElementById("backfacturasclientes").style.display="initial";
	document.getElementById("nclientes").innerHTML = "Detalle de factura";

			var nfactura= document.getElementById('id_factura_'+id).value;
			var tfactura= document.getElementById('t_factura_'+id).value;
			var cclie= document.getElementById('cveclie_'+id).value;
			
			$("#loader").fadeIn('slow');
			$.ajax({
				url:'./ajax/buscar_facturasdetalle.php?action=vendedor&cve_factura='+nfactura+'&tip_factura='+tfactura+'&cveclie='+cclie,
				 beforeSend: function(objeto){
				 $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
			  },
				success:function(data){
					$(".abonos_aplicados").html(data).fadeIn('slow');
					$('#loader').html('');
					$('[data-toggle="tooltip"]').tooltip({html:true}); 
					
				}
			})
		}

function ver_detCliente(id){

	//document.getElementById("backclientes").style.display="none";
	//document.getElementById("backfacturasclientes").style.display="initial";
	//document.getElementById("nclientes").innerHTML = "Detalle de factura";
 
			var cclie= document.getElementById('cveclie_'+id).value;
			
			$("#loader2d").fadeIn('slow');
			$.ajax({
				url:'./ajax/buscar_facturasdetalle.php?action=detcliente&cveclie='+cclie,
					beforeSend: function(objeto){
					$('#loader2d').html('<img src="./img/ajax-loader.gif"> Cargando...');
				},
				success:function(data){
					$(".detclie").html(data).fadeIn('slow');
					$('#loader2d').html('');
					$('[data-toggle="tooltip"]').tooltip({html:true}); 
					
				}
			})
		}