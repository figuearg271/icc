$(document).ready(function(){
			busca_reclamos_vendedores(1);
			
		});

$("#nombre_cliente" ).on( "keydown", function( event ) {
						if (event.keyCode== $.ui.keyCode.DELETE || event.keyCode== $.ui.keyCode.BACKSPACE )
						{
							$("#id_cliente" ).val("");
							$("#nombre_cliente" ).val("");
							document.getElementById("b_facturas").style.display="none";	
							document.getElementById("det_prod").style.display="none";										
						}
});

function llena_ncliente_vendedor(id){
	var vendedor= $("#vendedor").val();
	var nombre= $("#nombre_cliente").val();
	
	$.ajax({
		url:'./ajax/carga_nombres_clientes.php?action=busca_cliente_vendedor&vendedor='+vendedor+'&nombre='+nombre,
		 beforeSend: function(objeto){
		 $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
	  },		
		success: function(data){
			$("#suggesstion-box").show(); 
			$("#suggesstion-box").html(data);
			$("#nombre_cliente").css("background","#FFF");
		}
		});	
}

function llena_facturas_cliente_vendedor(id){	
	var vendedor= $("#vendedor").val();
	var idcliente= $("#id_cliente").val();
	var nfactura= $("#n_factura").val();
	
	$.ajax({
		url:'./ajax/carga_nombres_clientes.php?action=busca_facturas_cliente_vendedor&vendedor='+vendedor+'&idcliente='+idcliente+'&nfactura='+nfactura,

		 beforeSend: function(objeto){
		 $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
	  },		
		success: function(data){
			$("#suggesstion-box2").show();
			$("#suggesstion-box2").html(data);
			$("#n_factura").css("background","#FFF");
		}
		});
	if (nfactura == "") {
		$("#suggesstion-box2").hide();
	}	
}

function selectCountry(val) {

	$("#nombre_cliente").val(val);
	$("#suggesstion-box").hide();

	var idcliente= document.getElementById('id_cliente_'+val).value;
	$("#id_cliente").val(idcliente);
	document.getElementById("det_prod").style.display="initial";	
	document.getElementById("b_facturas").style.display="initial";

	var cve_clie= $("#id_cliente").val();	
	var usuario= $("#id_usuario").val();

	$.ajax({
    url: './ajax/add_reclamos.php?action=buscareclamospendientes&cveclie='+cve_clie+'&usuario='+usuario,
	 beforeSend: function(objeto){
		$("#resultados").html("Mensaje: Cargando...");
	  },
    success: function(datos){
	$("#resultados").html(datos);
	}
		});
}

function selectclave(val) {	

	$("#n_factura").val(val);
	$("#suggesstion-box2").hide();

	var nfactura= $("#n_factura").val();
	var idcliente= $("#id_cliente").val();
	//alert(idcliente);

	//Inicia validacion
	if (nfactura=='')
	{
		alert('Debe introducir el numero de factura ligada al cliente');
		document.getElementById("n_factura").focus();
		return false;
	}
	else
	{
		$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/carga_facturas_clientes.php?action=b_factura_cliente_vendedor&id_cliente='+idcliente+'&n_factura='+nfactura,
		 beforeSend: function(objeto){
		 $('#loader_bfactura').html('<img src="./img/ajax-loader.gif"> Cargando...');
	  },
		success:function(data){
			$(".outer_div_bfactura").html(data).fadeIn('slow');
			$('#loader_bfactura').html('');
			$('[data-toggle="tooltip"]').tooltip({html:true}); 
			
		}
	})
	}
}

function nuevo_reclamo(page){
	window.location.href = './nuevo_reclamo.php';
}

function buscar_factura_vendedor(id){
	//alert("llegaaa");
	var nfactura= $("#n_factura").val();
	var idcliente= $("#id_cliente").val();
	//alert(idcliente);

	//Inicia validacion
	if (nfactura=='')
	{
		alert('Debe introducir el numero de factura ligada al cliente');
		document.getElementById("n_factura").focus();
		return false;
	}
	else
	{
		$("#loader_bfactura").fadeIn('slow');
	$.ajax({
		url:'./ajax/carga_facturas_clientes.php?action=b_factura_cliente_vendedor&id_cliente='+idcliente+'&n_factura='+nfactura,
		 beforeSend: function(objeto){
		 $('#loader_bfactura').html('<img src="./img/ajax-loader.gif"> Cargando...');
	  },
		success:function(data){
			$(".outer_div_bfactura").html(data).fadeIn('slow');
			$('#loader_bfactura').html('');
			$('[data-toggle="tooltip"]').tooltip({html:true});			
		}
	})
	}
}

function agrega_producto_reclamo(id){
	
	var cve_art=document.getElementById('codigo_producto_'+id).value;
	var producto=document.getElementById('nombre_producto_'+id).value;
	var cantidad= document.getElementById('cantidad_'+id).value;
	var cantidadfacturada= document.getElementById('cantidad_tot'+id).value;
	var nfactura= document.getElementById('n_factura').value;	
	var reclamo= document.getElementById('reclamo_'+id).value;
	//var cantidadtmp= document.getElementById('cantidad_tmp'+id).value;	
		
	var cve_clie= $("#id_cliente").val();	
	var usuario= $("#id_usuario").val();

	var cantidadtmp= $("#cantidad_tmp"+id).val();
	var productotmp= $("#descripcion_tmp"+id).val();	

	//Inicia validacion
	if (isNaN(cantidad)){
		
		alert('Esto no es un numero');
		document.getElementById('cantidad_'+id).focus();
		return false;
	}
	else if (isNaN(reclamo)){
		
		alert('cantidad reclamo no puede ser 0');
		document.getElementById('reclamo_'+id).focus();
		return false;
	}
	else{
			$.ajax({
		    url: './ajax/add_reclamos.php?action=add_tmpprod_vendedor&cveart='+cve_art+'&producto='+producto+'&cantidad='+reclamo+'&cveclie='+cve_clie+'&usuario='+usuario+'&nfactura='+nfactura+'&treclamo='+cantidadtmp+'&tfactura='+cantidad+'&productotmp='+productotmp,
			 beforeSend: function(objeto){
				$("#resultados").html("Mensaje: Cargando...");
			  },
		    success: function(datos){
			$("#resultados").html(datos);
			}
				});

	}
}

function delet_prod_tmp_vendedores(id){

	var cve_clie= $("#id_cliente").val();	
	var usuario= $("#id_usuario").val();

	$.ajax({
    url: './ajax/add_reclamos.php?action=delet_prod_tmp_vendedores&codigo='+id+'&clie='+cve_clie+'&usuario='+usuario,
	 beforeSend: function(objeto){
		$("#resultados").html("Mensaje: Cargando...");
	  },
    success: function(datos){
	$("#resultados").html(datos);
	}
		});

}

function graba_reclamo_vendedores(id){	

	var cliente= $("#id_cliente").val();
	var vendedor= $("#vendedor").val();	
	var usuario= $("#id_usuario").val();
	var nombre= $("#nombre_cliente").val();
	var rdatos= $("#r_datos").val();
	var problema= $("#problema").val();

	var reply=confirm("¿Esta seguro de Grabar este reclamo?");
	if (reply==true) {
		var s = "no"; 
		var form = document.datosreclamos;
				
		for ( var i = 0; i < form.tdev.length; i++ ) { if (form.tdev[i].checked) { s= "si";  var vs =form.tdev[i].value;  break; } }  

		if (nombre=="") { alert("Debes de ingrsar el nombre del cliente"); }
		else if (rdatos=="") { alert("Debes ingresar el nombre de quien recibe el reclamo"); document.getElementById('r_datos').focus(); }
		else if (s == "no") { alert( "Debe seleccionar un tipo de devolucion" ) ;  }
		else if (problema=="") { alert("Debes ingresar el problema del reclamo"); document.getElementById('problema').focus(); }

		else{

			$.ajax({
			    url: './ajax/add_reclamos.php?action=agrega_reclamo_vendedor&idcliente='+cliente+'&idusuario='+usuario+'&idvendedor='+vendedor+'&nombre='+nombre+'&rdatos='+rdatos+'&problema='+problema+'&tproblema='+vs,
				beforeSend: function(objeto){
					$("#resultados").html("Mensaje: Cargando...");
				},
			    success: function(datos){
					$("#resultados").html(datos);
				}
			});

			setTimeout("location.href='reclamosproductos.php'", 8000);
		}
	}
}

function cancelar_reclamo(id){ //cancela pedido y regresa a ver facturas
	
	var reply=confirm("¿Esta seguro de salir sin guardar?")
		if (reply==true)
		{
			window.location.href = './reclamosproductos.php';
		}
}

function busca_reclamos_vendedores(id){	
	
	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/add_reclamos.php?action=busca_reclamos_vendedores',
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

function busca_reclamos_vendedores_nombre(id){	
	var nombre= $("#nombre_clie").val();

	//alert(nombre);
	
	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/add_reclamos.php?action=b_reclamos_v_nombre$nombre='+nombre,
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

function carga_reclamo(id){

	$("#loader4").fadeIn('slow');
	$.ajax({
		url:'./ajax/add_reclamos.php?action=carga_reclamos&idreclamo='+id,
		 beforeSend: function(objeto){
		 $('#loader4').html('<img src="./img/ajax-loader.gif"> Cargando...');
	  },
		success:function(data){
			$(".outer_div4").html(data).fadeIn('slow');
			$('#loader4').html('');
			$('[data-toggle="tooltip"]').tooltip({html:true}); 
			
		}
	})
} 

function imprimirDIV(contenido) {
        var ficha = document.getElementById(contenido);
        var ventanaImpresion = window.open(' ', 'popUp');
        ventanaImpresion.document.write(ficha.innerHTML);
        ventanaImpresion.document.close();
        ventanaImpresion.print();
        ventanaImpresion.close();
}

function printDiv(nombreDiv) {
	
     var contenido= document.getElementById(nombreDiv).innerHTML;
     var contenidoOriginal= document.body.innerHTML;

     document.body.innerHTML = contenido;

     window.print();

     document.body.innerHTML = contenidoOriginal;
}

function procesa_reclamo(idreclamo){ //muestra el reclamo para procesarlo esto departe de calidad

	/* $("#loader4").fadeIn('slow');
	$.ajax({
		url:'./ajax/add_reclamos.php?action=procesa_reclamos&idreclamo='+idreclamo,
		 beforeSend: function(objeto){
		 $('#loader4').html('<img src="./img/ajax-loader.gif"> Cargando...');
	  },
		success:function(data){
			$(".outer_div4").html(data).fadeIn('slow');
			$('#loader4').html('');
			$('[data-toggle="tooltip"]').tooltip({html:true}); 			
		}
	})*/
	
	var nreclamo= $("#numero_reclamo").val();

	$("#loader5").fadeIn('slow');
	$.ajax({
		url:'./ajax/add_reclamos.php?action=procesa_reclamos&idreclamo='+idreclamo,
		 beforeSend: function(objeto){
		 $('#loader5').html('<img src="./img/ajax-loader.gif"> Cargando...');
	  },
		success:function(data){
			$(".outer_div5").html(data).fadeIn('slow');
			$('#loader5').html('');
			$('[data-toggle="tooltip"]').tooltip({html:true}); 	


		}
	})

 }

 function graba_lote_destino(iditem){ //graba lote y destino de cada item del reclamo a procesar por parte de calidad

 	var lote = document.getElementById('lote42_'+iditem).value; 
 	var destino = document.getElementById('destino42_'+iditem).value;

 	var nreclamo= $("#numero_reclamo").val();
 	if (lote=="" || destino=="") { alert("Debes ingresar un lote o el destino de este item");}
 	
 	else{

 		var reply=confirm("¿Esta seguro de guardar este lote o destino?");
		if (reply==true) {
			$("#loader5").fadeIn('slow');
			$.ajax({
			url:'./ajax/add_reclamos.php?action=graba_lote_destino&iditemreclamo='+iditem+'&lote='+lote+'&destino='+destino+'&nreclamo='+nreclamo,
			 beforeSend: function(objeto){
			 $('#loader5').html('<img src="./img/ajax-loader.gif"> Cargando...');
		  		},
			success:function(data){
				$(".outer_div5").html(data).fadeIn('slow');
				$('#loader5').html('');
				$('[data-toggle="tooltip"]').tooltip({html:true}); 			
				}
			})
 		}	 	
 	}	
 }



 function graba_reclamo_procesado(iditem){ //graba lote y destino de cada item del reclamo a procesar por parte de calidad

 	var notas = document.getElementById('notas42').value;
 	var nreclamo= $("#numero_reclamo").val();
 	
	var s = "no"; 
	var form = document.muestra_datosreclamos;	
	for ( var i = 0; i < form.optradio.length; i++ ) { if (form.optradio[i].checked) { s= "si";  var procede_dev =form.optradio[i].value;  break; }  }		
	if (s == "no") { alert( "Debe seleccionar si procede como devolucion o no" ) ;  }
	
	else{

 		var reply=confirm("¿Desea guardar este reclamo?");
		if (reply==true) {
			$("#loader5").fadeIn('slow');
			$.ajax({
			url:'./ajax/add_reclamos.php?action=graba_reclamo_procesado&nreclamo='+nreclamo+'&nota='+notas+'&procede='+procede_dev,
			 beforeSend: function(objeto){
			 $('#loader4').html('<img src="./img/ajax-loader.gif"> Cargando...');
		  		},
			success:function(data){
				$(".outer_div5").html(data).fadeIn('slow');
				$('#loader5').html('');
				$('[data-toggle="tooltip"]').tooltip({html:true}); 			
				}
			});

			setTimeout("location.href='reclamosproductos.php'", 3000);
 		}
 	}
 		
 }




