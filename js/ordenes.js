function busca_cotizaciones(id) //ejecuta cuando abres cotizaciones
{
	var clave_vend= $("#cve_vend").val();

	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/ordenes.php?action=busca_cotizaciones&cve_vend='+clave_vend,
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

function busca_pedidos(id) //ejecuta al momento de abrir pedidos
{
	var clave_vend= $("#cve_vend").val();

	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/ordenes.php?action=busca_pedidos&cve_vend='+clave_vend,
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

function cancelar_pedido(id)
{
	var reply=confirm("¿Esta seguro de cancelar el pedido? "+id);
	if (reply==true) {		
		$.ajax({
		    url: './ajax/ordenes.php?action=cancelar_pedido&cve_doc='+id,
			beforeSend: function(objeto){
				$('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
			},
		    success: function(datos){
				$("#resultados").html(datos);
			}
		});
		$('#loader').html('');
		$("#notificacion").html("Se ha cancelado exitosamente el pedido "+id);
		setTimeout("location.href='pedidos.php'", 3000);		 
	}
}

function busca_listas(id)
{

	document.getElementById("n_pedido").style.display="none";
	document.getElementById("btn_precios").style.display="none";

	//document.getElementById("n_cliente").style.display="none";
	
	//document.getElementById("nclientes").innerHTML = "Facturas pendiente de pago";
	document.getElementById("producto").style.display="initial";	
	document.getElementById("backpedidos").style.display="initial";	
	document.getElementById("btn_buscar").style.display="initial";	

	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/ordenes.php?action=busca_listas',
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

function busca_listas_producto(id)
{
	document.getElementById("n_pedido").style.display="none";
	document.getElementById("btn_precios").style.display="none";

	//document.getElementById("n_cliente").style.display="none";
	
	document.getElementById("nombre_formulario").innerHTML = "Listas de precios";
	document.getElementById("producto").style.display="initial";	
	document.getElementById("backpedidos").style.display="initial";	
	document.getElementById("btn_buscar").style.display="initial";	

	var producto= $("#producto").val();

	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/ordenes.php?action=busca_listas_producto&producto='+producto,
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

// edicion de los pedidos en SAE

function carga_pedido(id) // carga productos al seleccionar pedido a modificar
{
	var cve_doc= $("#cve_doc").val();
	var tcont= $("#tipo_contribuyente").val();

	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/modifica_ped.php?action=carga_pedido&cve_doc='+cve_doc+'&tcont='+tcont,
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

function elimina_item(id) // carga productos al seleccionar pedido a modificar
{
	var cveart= document.getElementById("art_"+id).value;
	var cvedoc= document.getElementById("doc_"+id).value;

	var tcont= $("#tipo_contribuyente").val();

	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/modifica_ped.php?action=elimina_item&cve_doc='+cvedoc+'&art='+cveart+'&nl='+id+'&tcont='+tcont,
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

function modifica_item(e,id,cant) // carga productos al seleccionar pedido a modificar
{
	tecla = (document.all) ? e.keyCode : e.which;
	if (tecla==13)
	{
		var tcont= $("#tipo_contribuyente").val();
		var cveart= document.getElementById("art_"+id).value;
		var cvedoc= document.getElementById("doc_"+id).value;

		$("#loader").fadeIn('slow');
		$.ajax({
			url:'./ajax/modifica_ped.php?action=modifica_item&cve_doc='+cvedoc+'&art='+cveart+'&nl='+id+'&cantidad='+cant+'&tcont='+tcont,//+'&retencion='+retencion,//+'&imp3='+imp3+'&timp3='+timp3,
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
}

function actualiza_fecha_e(e,fecha) //actualiza fecha entrega
{	
	tecla = (document.all) ? e.keyCode : e.which;
	if (tecla==13)
	{
		var cve_doc= $("#cve_doc").val();
		var tcont= $("#tipo_contribuyente").val();

		$("#loader").fadeIn('slow');
		$.ajax({
			url:'./ajax/modifica_ped.php?action=mod_fecha&cve_doc='+cvedoc+'&fecha='+fecha+'&tcont='+tcont,//+'&retencion='+retencion,//+'&imp3='+imp3+'&timp3='+timp3,
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

}

function b_productos(id) // BUSCA PRODUCTO POR DESCRIPCION O TOP 10
{	
	var q= $("#n_art").val();
	var c= $("#cod_cliente").val();


	$("#loader_add").fadeIn('slow');
	$.ajax({
		url:'./ajax/modifica_ped.php?action=b_productos&nom_art='+q+'&cod_cliente='+c+'&tb='+id,
		beforeSend: function(objeto){
			$('#loader_add').html('<img src="./img/ajax-loader.gif"> Procesando, espere por favor...');
		},
		success:function(data){
			$(".outer_div_add").html(data).fadeIn('slow'); 
			$('#loader_add').html(''); 

		}
	});
	
}

function a_producto(id) //agregando producto a pedido
{
	var cve_doc= $("#cve_doc").val();
	var tcont= $("#tipo_contribuyente").val();

	var art=document.getElementById('producto_'+id).value;
	var cantidad=document.getElementById('cantidad_'+id).value;
	var precio=document.getElementById('precio_venta_'+id).value;
	var desc=document.getElementById('desc_'+id).value;
	var costo= document.getElementById('costo_'+id).value;
	var uni= document.getElementById('uni_alt_'+id).value;
	var tipo= document.getElementById('tipo_ele_'+id).value;

	//alert(desc);



	if (isNaN(cantidad)){
		alert('Esto no es un numero');
		document.getElementById('cantidad_'+id).focus();
		return false;
	}
	else if (isNaN(precio)){
		alert('Esto no es un numero');
		document.getElementById('precio_venta_'+id).focus();
		return false;
	}
	
	/*$.ajax({
    type: "GET",
    url: "./ajax/modifica_ped.php",
    data: '
	 beforeSend: function(objeto){
		$("#resultado").html("Mensaje: Cargando...");
	  },
    success: function(datos){
	$("#resultado").html(datos);
	}
		});*/




	$("#loader").fadeIn('slow');
		$.ajax({
			url:'./ajax/modifica_ped.php?&action=agrega_producto&cve_doc='+cve_doc+'&tcont='+tcont+'&precio='+precio+'&cantidad='+cantidad+'&costo='+costo+'&uni='+uni+'&tipo='+tipo+'&desc='+desc+'&art='+art,
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





function printDiv(nombreDiv) {
	
     var contenido= document.getElementById(nombreDiv).innerHTML;
     var contenidoOriginal= document.body.innerHTML;

     document.body.innerHTML = contenido;

     window.print();

     document.body.innerHTML = contenidoOriginal;
}


function onKeyDownHandler(event) {

    var codigo = event.which || event.keyCode;

    
     
    if(codigo === 13){
      	document.getElementById("n_pedido").style.display="none";
		document.getElementById("btn_precios").style.display="none";

		//document.getElementById("n_cliente").style.display="none";
		
		document.getElementById("nombre_formulario").innerHTML = "Listas de precios";
		document.getElementById("producto").style.display="initial";	
		document.getElementById("backpedidos").style.display="initial";	
		document.getElementById("btn_buscar").style.display="initial";	

		var producto= $("#producto").val();

		$("#loader").fadeIn('slow');
		$.ajax({
			url:'./ajax/ordenes.php?action=busca_listas_producto&producto='+producto,
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

    

     
}