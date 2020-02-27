
		/*$(document).ready(function(){
			busca_pendientes(1);
			
		});*/

		$(document).ready(function(){
  $('#movil').mask('9999-9999');
  $('#costo').mask('###,999.99');
  $('#f_adq').mask('9999-99-99');
  $('#f_ins').mask('9999-99-99');
  $('#f_ent').mask('9999-99-99');
  $('#val_res').mask('###,999.99');
});


function anular(e) {
          tecla = (document.all) ? e.keyCode : e.which;
          return (tecla != 13);
          
     }


function cargaclientes_vendedor(page){
	var nombre= $("#nombre").val();
	var vendedor= $("#vendedor").val();
	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/buscar_sucursal_envios.php?action=clientes_vendedor&nombre='+nombre+'&vendedor='+vendedor,
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

function cargaclientes_vendedor_cotizacion(page){
	var nombre= $("#nombre").val();
	var vendedor= $("#vendedor").val();
	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/buscar_sucursal_envios.php?action=clientes_vendedor_cotizaciones&nombre='+nombre+'&vendedor='+vendedor,
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


function cargamatriz(page){
	var nombresuc= $("#nmat").val();
	var clavemat= $("#clavematriz").val();
	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/buscar_sucursal_envios.php?action=redireccionanuevopedido&page='+page+'&nombre_sucursal='+nombresuc+'&clave_matriz='+clavemat,
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

function busca_pendientes(id){
	
	var nombresuc= $("#nombresucursal").val();
	var clavemat= $("#clavematriz").val();
	
	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/busca_facturas_incompletas.php?action=ajax&nombre_sucursal='+nombresuc+'&clave_matriz='+clavemat,
		 beforeSend: function(objeto){
		 $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando..........');
	  },
		success:function(data){
			$(".outer_div").html(data).fadeIn('slow');
			$('#loader').html('');
			$('[data-toggle="tooltip"]').tooltip({html:true}); 
			
		}
	});
}

function busca_pendientes_vendedor(id){
	
	var vendedor= $("#vendedor").val();
	
	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/busca_facturas_incompletas.php?action=busca_pendientes_vendedor&vendedor='+vendedor,
		 beforeSend: function(objeto){
		 $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando..........');
	  },
		success:function(data){
			$(".outer_div").html(data).fadeIn('slow');
			$('#loader').html('');
			$('[data-toggle="tooltip"]').tooltip({html:true}); 
			
		}
	});
}

function busca_pendientes_vendedor_cotizaciones(id){
	
	var vendedor= $("#vendedor").val();
	
	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/busca_facturas_incompletas.php?action=busca_pendientes_vendedor_cotizacion&vendedor='+vendedor,
		 beforeSend: function(objeto){
		 $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando..........');
	  },
		success:function(data){
			$(".outer_div").html(data).fadeIn('slow');
			$('#loader').html('');
			$('[data-toggle="tooltip"]').tooltip({html:true}); 
			
		}
	});
}


function bproductosmatriz(id){
	var q= $("#n_art").val();
	var w= $("#cod_matriz").val();
	var c= $("#cod_suc").val();
	var n= $("#n_suc").val();

	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/productos_pedidos.php?action=ajax&q='+q+'&w='+w+'&c='+c+'&n='+n,
		beforeSend: function(objeto){
			$('#loader').html('<img src="./img/ajax-loader.gif"> Procesando, espere por favor...');
		},
		success:function(data){
			$(".outer_div").html(data).fadeIn('slow'); 
			$('#loader').html(''); 

		}
	});
}

function bproductosvendedor(id){ 
	
	var q= $("#n_art").val();
	var w= $("#cod_matriz").val();
	var c= $("#cod_suc").val();
	var n= $("#n_suc").val();

	//alert(q);

	$("#loader_modificando").fadeIn('slow');
	$.ajax({
		url:'./ajax/productos_pedidos.php?action=bproductos_vendedor&q='+q+'&c='+c+'&n='+n+'&w='+w,
		beforeSend: function(objeto){
			$('#loader_modificando').html('<img src="./img/ajax-loader.gif"> Procesando, espere por favor...');
		},
		success:function(data){
			$(".outer_div_modificando").html(data).fadeIn('slow'); 
			$('#loader_modificando').html(''); 

		}
	});
}

function b_productosvendedor_varios(id){ 
	
	var q= $("#n_art").val();
	var w= $("#cod_matriz").val();
	var c= $("#cod_suc").val();
	var n= $("#n_suc").val();

	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/productos_pedidos.php?action=bproductos_vendedor_varios&q='+q+'&c='+c+'&n='+n+'&w='+w,
		beforeSend: function(objeto){
			$('#loader').html('<img src="./img/ajax-loader.gif"> Procesando, espere por favor...');
		},
		success:function(data){
			$(".outer_div").html(data).fadeIn('slow'); 
			$('#loader').html(''); 

		}
	});
}

function load3(id){
	var q= $("#q").val();
	var w= $("#codmatriz").val();
	var c= $("#codsuc").val();
	var n= $("#nsuc").val();

	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/productos_pedidos.php?action=top10&q='+q+'&w='+w+'&c='+c+'&n='+n,
		beforeSend: function(objeto){
			$('#loader').html('<img src="./img/ajax-loader.gif"> Procesando, espere por favor...');
		},
		success:function(data){
			$(".outer_div").html(data).fadeIn('slow'); 
			$('#loader').html(''); 

		}
	});
}
/*
function top10v(id){ // top 10 de desde vendedor a cliente		
	var w= $("#cod_matriz").val();
	var c= $("#cod_suc").val();
	var n= $("#n_suc").val();	

	$("#loader_busca_productos").fadeIn('slow');
	$.ajax({
		url:'./ajax/productos_pedidos.php?action=top10v&w='+w+'&c='+c+'&n='+n,
		beforeSend: function(objeto){
			$('#loader_busca_productos').html('<img src="./img/ajax-loader.gif"> Procesando, espere por favor... &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
		},
		success:function(data){
			$(".outer_div_busca_productos").html(data).fadeIn('slow'); 
			$('#loader_busca_productos').html(''); 
		}
	});
}*/

function top10v(id){ // top 10 de desde vendedor a cliente		
	var w= $("#cod_matriz").val();
	var c= $("#cod_suc").val();
	var n= $("#n_suc").val();	

	if (id==1)
	{
		$("#loader_busca_producto").fadeIn('slow');
	$.ajax({
		url:'./ajax/productos_pedidos.php?action=top10v&w='+w+'&c='+c+'&n='+n,
		beforeSend: function(objeto){
			$('#loader_busca_producto').html('<img src="./img/ajax-loader.gif"> Procesando, espere por favor... &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
		},
		success:function(data){
			$(".outer_div_busca_producto").html(data).fadeIn('slow'); 
			$('#loader_busca_producto').html(''); 
		}
	});
	}
	else
		//alert("entra");	
		$("#loader_modificando").fadeIn('slow');
	$.ajax({
		url:'./ajax/productos_pedidos.php?action=top10v&w='+w+'&c='+c+'&n='+n,
		beforeSend: function(objeto){
			$('#loader_modificando').html('<img src="./img/ajax-loader.gif"> Procesando, espere por favor... &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
		},
		success:function(data){
			$(".outer_div_modificando").html(data).fadeIn('slow'); 
			$('#loader_modificando').html(''); 
		}
	});

	
}

function addproductos(id)
{
	var cant=document.getElementById('cantidad_'+id).value;
	var p_venta=document.getElementById('precio_venta_'+id).value;
	var des= document.getElementById('descripcion_'+id).value;		
	var cli= document.getElementById('clie_'+id).value;		
	var pro= document.getElementById('producto_'+id).value;
	var ma= document.getElementById('mat_'+id).value;
	var desc= document.getElementById('desc_'+id).value;

	var ncli= document.getElementById('nclie_'+id).value;

	//Inicia validacion
	if (isNaN(cant)){
		alert('Esto no es un numero');
		document.getElementById('cantidad_'+id).focus();
		return false;
	}
	else if (isNaN(p_venta)){
		alert('Esto no es un numero');
		document.getElementById('precio_venta_'+id).focus();
		return false;
	}

	$.ajax({
    type: "GET",
    url: "./ajax/addprodpedido.php",
    data: '&action=addproductosvendedor&precventa='+p_venta+'&canti='+cant+'&cvecli='+cli+'&descrip='+des+'&cvepro='+pro+'&nombreclie='+ncli+'&matrizsuc='+ma+'&descuento='+desc,
	 beforeSend: function(objeto){
		$("#resultado").html("Mensaje: Cargando...");
	  },
    success: function(datos){
	$("#resultado").html(datos);
	}
		});
}

function addproductos_vendedor(id){

	var cantidad=document.getElementById('cantidad_'+id).value;
	var precio_venta=document.getElementById('precio_venta_'+id).value;
	var descripcion= document.getElementById('descripcion_'+id).value;		
	var cve_cliente= document.getElementById('clie_'+id).value;		
	var producto= document.getElementById('producto_'+id).value;
	var nombre_cliente= document.getElementById('nclie_'+id).value;
	var vendedor= document.getElementById('vend_'+id).value;
	var matriz= document.getElementById('mat_'+id).value;
	var descuento= 0;

	var comentario = document.getElementById('comentarios').value;
	var condicion = $("#tipc").val()+'-'+$("#tipc2").val(); 

	var contribuyente= $("#tipo_contribuyente").val();
	//alert(descripcion);
	//Inicia validacion
	if (isNaN(cantidad)){
		alert('Esto no es un numero');
		document.getElementById('cantidad_'+id).focus();
		return false;
	}
	else if (isNaN(precio_venta)){
		alert('Esto no es un numero');
		document.getElementById('precio_venta_'+id).focus();
		return false;
	}

	$.ajax({
    type: "GET",
    url: "./ajax/addprodpedido.php",
    data: '&action=add_propducto_vendedor&precio_venta='+precio_venta+'&cantidad='+cantidad+'&cve_cliente='+cve_cliente+'&descripcion='+descripcion+'&cve_producto='+producto+'&nombre_cliente='+nombre_cliente+'&vendedor='+vendedor+'&cve_matriz='+matriz+'&contribuyente='+contribuyente+'&desc='+descuento+'&comment='+comentario+'&condi='+condicion,
	 beforeSend: function(objeto){
		$("#resultado").html("Mensaje: Cargando...");
	  },
    success: function(datos){
	$("#resultado").html(datos);
	}
		});
}

function addbonificacion(id){

	var cantidad=document.getElementById('cantidad_'+id).value;
	var precio_venta=0;
	var descripcion= document.getElementById('descripcion_'+id).value;		
	var cve_cliente= document.getElementById('clie_'+id).value;		
	var producto= document.getElementById('producto_'+id).value;
	var nombre_cliente= document.getElementById('nclie_'+id).value;
	var vendedor= document.getElementById('vend_'+id).value;
	var matriz= document.getElementById('mat_'+id).value;
	var descuento= 0;

	var comentario = document.getElementById('comentarios').value;
	var condicion = $("#tipc").val()+'-'+$("#tipc2").val(); 

	var contribuyente= $("#tipo_contribuyente").val();
	//alert(descripcion);
	//Inicia validacion
	if (isNaN(cantidad)){
		alert('Esto no es un numero');
		document.getElementById('cantidad_'+id).focus();
		return false;
	}
	else if (isNaN(precio_venta)){
		alert('Esto no es un numero');
		document.getElementById('precio_venta_'+id).focus();
		return false;
	}

	$.ajax({
    type: "GET",
    url: "./ajax/addprodpedido.php",
    data: '&action=add_propducto_vendedor&precio_venta='+precio_venta+'&cantidad='+cantidad+'&cve_cliente='+cve_cliente+'&descripcion='+descripcion+'&cve_producto='+producto+'&nombre_cliente='+nombre_cliente+'&vendedor='+vendedor+'&cve_matriz='+matriz+'&contribuyente='+contribuyente+'&desc='+descuento+'&bono=1&comment='+comentario+'&condi='+condicion,
	 beforeSend: function(objeto){
		$("#resultado").html("Mensaje: Cargando...");
	  },
    success: function(datos){
	$("#resultado").html(datos);
	}
		});
}

function addproductos_vendedor_varios(id){

	var cantidad=document.getElementById('cantidad_'+id).value;
	var precio_venta=document.getElementById('precio_venta_'+id).value;
	var descripcion= document.getElementById('descripcion_'+id).value;		
	var cve_cliente= document.getElementById('clie_'+id).value;		
	var producto= document.getElementById('producto_'+id).value;
	var nombre_cliente= document.getElementById('nclie_'+id).value;
	var vendedor= document.getElementById('vend_'+id).value;
	var matriz= document.getElementById('mat_'+id).value;
	var descuento= document.getElementById('desc_'+id).value;

	var contribuyente= $("#tipo_contribuyente").val();
	//alert(descripcion);
	//Inicia validacion
	if (isNaN(cantidad)){
		alert('Esto no es un numero');
		document.getElementById('cantidad_'+id).focus();
		return false;
	}
	else if (isNaN(precio_venta)){
		alert('Esto no es un numero');
		document.getElementById('precio_venta_'+id).focus();
		return false;
	}

	$.ajax({
    type: "GET",
    url: "./ajax/addprodpedido.php",
    data: '&action=add_propducto_vendedor_varios&precio_venta='+precio_venta+'&cantidad='+cantidad+'&cve_cliente='+cve_cliente+'&descripcion='+descripcion+'&cve_producto='+producto+'&nombre_cliente='+nombre_cliente+'&vendedor='+vendedor+'&cve_matriz='+matriz+'&contribuyente='+contribuyente+'&desc='+descuento,
	 beforeSend: function(objeto){
		$("#resultado").html("Mensaje: Cargando...");
	  },
    success: function(datos){
	$("#resultado").html(datos);
	}
		});
}

function elimina_item(id,p){
	var clie=document.getElementById('clie_'+id).value;

	$.ajax({
		type: "GET",
		url: "./ajax/addprodpedido.php",
		data: "action=elimina_item&id="+id+"&clie="+clie+"&pre="+p,
		beforeSend: function(objeto){
			$("#resultado").html("Mensaje: Cargando...");
		},
		success:function(datos){
			$("#resultado").html(datos);
		}
	});
}

function elimina_pedido(id){
	var clie=document.getElementById('clie_'+id).value;
	
	var reply=confirm("¿Esta seguro de eliminar por completo este pedido?")
		if (reply==true)
		{
			$.ajax({
				type: "GET",
				url: "./ajax/addprodpedido.php",
				data: "action=elimina_orden&clie="+clie,
				beforeSend: function(objeto){
					$("#resultado").html("Mensaje: Cargando...");
				},
				success:function(datos){
					$("#resultado").html(datos);
					window.location.href = './ver_facturas.php?action=pedidos';
				}
			});
		}
}

function elimina_cotizacion(id){
	var clie=document.getElementById('clie_'+id).value;
	
	var reply=confirm("¿Esta seguro de eliminar por completo este pedido?")
		if (reply==true)
		{
			$.ajax({
				type: "GET",
				url: "./ajax/addprodpedido.php",
				data: "action=elimina_orden&clie="+clie,
				beforeSend: function(objeto){
					$("#resultado").html("Mensaje: Cargando...");
				},
				success:function(datos){
					$("#resultado").html(datos);
					window.location.href = './ver_facturas.php?action=cotizaciones';
				}
			});
		}
}

function pedidocancelar(id){ //cancela pedido y regresa a ver facturas

	
	var reply=confirm("¿Esta seguro de salir sin guardar el documento?")
		if (reply==true)
		{
			window.location.href = './ver_facturas.php?action=pedidos';
		}
}

function cotizacion_cancelar(id){ //cancela pedido y regresa a ver facturas

	
	var reply=confirm("¿Esta seguro de salir sin guardar el documento?")
		if (reply==true)
		{
			window.location.href = './ver_facturas.php?action=cotizaciones';
		}
}


/*function grabarpedido(id,tdoc){
	
	if (tdoc==1) {
		var reply=confirm("se guardara un agregado. Desea continuar?")
		if (reply==true)
		{
			var clie=document.getElementById('clie_'+id).value;
			var descu= $("#desc_tot").val();
			var felab= $("#fecha").val();
			var fent= $("#fechaent").val();
			var tot= $("#cantot").val();
			var ivatot= $("#ivatot").val();
			var importe= $("#importe").val();
			var nlin= $("#nlin").val();
			var comen= $("#comentarios").val();
			var disponible= $("#disponible").val();

			var lali= $("#alimento").val();


			if (importe>disponible) { 
				alert("Excede limite de credito");
				$.ajax({
				type: "GET",
				url: "./ajax/addprodpedido.php",
				data: "action=add_cotizacion&cli="+clie+"&fecha="+felab+"&fechaent="+fent+"&tot="+tot+"&ivat="+ivatot+"&importe="+importe+"&nlin="+nlin+"&com="+comen+"&descuento="+descu+"&lalimen="+lali+"&tdoc="+tdoc,
				beforeSend: function(objeto){
					$("#resultado").html("Mensaje: Cargando...");
				},
				success:function(datos){
					$("#resultado").html(datos);
				}
				});
			}
			else
			{
				$.ajax({
				type: "GET",
				url: "./ajax/addprodpedido.php",
				data: "action=add_pedido&cli="+clie+"&fecha="+felab+"&fechaent="+fent+"&tot="+tot+"&ivat="+ivatot+"&importe="+importe+"&nlin="+nlin+"&com="+comen+"&descuento="+descu+"&lalimen="+lali+"&tdoc="+tdoc,
				beforeSend: function(objeto){
					$("#resultado").html("Mensaje: Cargando...");
				},
				success:function(datos){
					$("#resultado").html(datos);
				}
				});
			}

			
			

			var frm = document.forms['datos_factura'];
		    for(i=0; ele=frm.elements[i]; i++)
		    ele.disabled=true;

			setTimeout("location.href='pedidos.php'", 3000);

			
		}

	}
	 if (tdoc==2) {

		var reply=confirm("se guardara un pedido. Desea continuar?")
		if (reply==true)
		{
			var clie=document.getElementById('clie_'+id).value;
			var descu= $("#desc_tot").val();
			var felab= $("#fecha").val();
			var fent= $("#fechaent").val();
			var tot= $("#cantot").val();
			var ivatot= $("#ivatot").val();
			var importe= $("#importe").val();
			var nlin= $("#nlin").val();
			var comen= $("#comentarios").val();
			var disponible= $("#disponible").val();

			var lali= $("#alimento").val();

			if (importe>disponible) 
			{ 
				alert("Excede limite de credito");
				$.ajax({
				type: "GET",
				url: "./ajax/addprodpedido.php",
				data: "action=add_cotizacion&cli="+clie+"&fecha="+felab+"&fechaent="+fent+"&tot="+tot+"&ivat="+ivatot+"&importe="+importe+"&nlin="+nlin+"&com="+comen+"&descuento="+descu+"&lalimen="+lali+"&tdoc="+tdoc,
				beforeSend: function(objeto){
					$("#resultado").html("Mensaje: Cargando...");
				},
				success:function(datos){
					$("#resultado").html(datos);
				}
				});
			}
			else
			{
				$.ajax({
				type: "GET",
				url: "./ajax/addprodpedido.php",
				data: "action=add_pedido&cli="+clie+"&fecha="+felab+"&fechaent="+fent+"&tot="+tot+"&ivat="+ivatot+"&importe="+importe+"&nlin="+nlin+"&com="+comen+"&descuento="+descu+"&lalimen="+lali+"&tdoc="+tdoc,
				beforeSend: function(objeto){
					$("#resultado").html("Mensaje: Cargando...");
				},
				success:function(datos){
					$("#resultado").html(datos);
				}
			});
			}
			
		

			var frm = document.forms['datos_factura'];
		    for(i=0; ele=frm.elements[i]; i++)
		    ele.disabled=true;

			setTimeout("location.href='ver_facturas.php?action=pedidos'", 3000);

			
		}
	}	
}*/

function grabarpedido(id,tdoc){
	
	if (tdoc==1) {
		var reply=confirm("se guardara un agregado. Desea continuar?")
		if (reply==true)
		{
			var clie=document.getElementById('clie_'+id).value;
			var descu= $("#desc_tot").val();
			var felab= $("#fecha").val();
			var fent= $("#fechaent").val();
			var tot= $("#cantot").val();
			var ivatot= $("#ivatot").val();
			var importe= $("#importe").val();
			var nlin= $("#nlin").val();
			var comen= $("#comentarios").val();
			var disponible= $("#disponible").val();

			var lali= $("#alimento").val();
			//AGREGACION DE CAMPOS CREDITO, CREDIO ,VENDEDOR O REPARTO
			var refernew = $("#tipc").val()+'-'+$("#tipc2").val(); 
			//if(parseInt(importe)>parseInt(disponible)) { 
				//alert("Excede limite de credito");
				$.ajax({
				type: "GET",
				url: "./ajax/addprodpedido.php",
				data: "action=add_cotizacion&cli="+clie+"&fecha="+felab+"&fechaent="+fent+"&tot="+tot+"&ivat="+ivatot+"&importe="+importe+"&nlin="+nlin+"&com="+comen+"&descuento="+descu+"&lalimen="+lali+"&tdoc="+tdoc+"&nref="+refernew,
				beforeSend: function(objeto){
					$("#resultado").html("Mensaje: Cargando...");
				},
				success:function(datos){
					$("#resultado").html(datos);
				}
				});
			//}
			/*else
			{
				$.ajax({
				type: "GET",
				url: "./ajax/addprodpedido.php",
				data: "action=add_pedido&cli="+clie+"&fecha="+felab+"&fechaent="+fent+"&tot="+tot+"&ivat="+ivatot+"&importe="+importe+"&nlin="+nlin+"&com="+comen+"&descuento="+descu+"&lalimen="+lali+"&tdoc="+tdoc,
				beforeSend: function(objeto){
					$("#resultado").html("Mensaje: Cargando...");
				},
				success:function(datos){
					$("#resultado").html(datos);
				}
				});
			}*/

			
			

			var frm = document.forms['datos_factura'];
		    for(i=0; ele=frm.elements[i]; i++)
		    ele.disabled=true;

			setTimeout("location.href='pedidos.php'", 3000);

			
		}

	}
	 if (tdoc==2) {

		//var reply=confirm("se guardara un pedido. Desea continuar?")
		//if (reply==true)
		//{
			var clie=document.getElementById('clie_'+id).value;
			var descu= $("#desc_tot").val();
			var felab= $("#fecha").val();
			var fent= $("#fechaent").val();
			var tot= $("#cantot").val();
			var ivatot= $("#ivatot").val();
			var importe= $("#importe").val();
			var nlin= $("#nlin").val();
			var comen= $("#comentarios").val();
			var disponible= $("#disponible").val();

			var lali= $("#alimento").val();

			var refernew = $("#tipc").val()+'-'+$("#tipc2").val(); 

			//if(parseInt(importe)>parseInt(disponible))
			//{ 
			//	alert("Excede limite de credito");
				$.ajax({
				type: "GET",
				url: "./ajax/addprodpedido.php",
				data: "action=add_cotizacion&cli="+clie+"&fecha="+felab+"&fechaent="+fent+"&tot="+tot+"&ivat="+ivatot+"&importe="+importe+"&nlin="+nlin+"&com="+comen+"&descuento="+descu+"&lalimen="+lali+"&tdoc="+tdoc+"&nref="+refernew,
				beforeSend: function(objeto){
					$("#resultado").html("Mensaje: Cargando...");
				},
				success:function(datos){
					$("#resultado").html(datos);
				}
				});
			/*}
			else
			{
				$.ajax({
				type: "GET",
				url: "./ajax/addprodpedido.php",
				data: "action=add_pedido&cli="+clie+"&fecha="+felab+"&fechaent="+fent+"&tot="+tot+"&ivat="+ivatot+"&importe="+importe+"&nlin="+nlin+"&com="+comen+"&descuento="+descu+"&lalimen="+lali+"&tdoc="+tdoc,
				beforeSend: function(objeto){
					$("#resultado").html("Mensaje: Cargando...");
				},
				success:function(datos){
					$("#resultado").html(datos);
				}
			});
			}*/
			
		

			var frm = document.forms['datos_factura'];
		    for(i=0; ele=frm.elements[i]; i++)
		    ele.disabled=true;

			setTimeout("location.href='ver_facturas.php?action=pedidos'", 3000);

			
		//}
	}	
}


function grabarpedido_varios(id,tdoc){
	
		

		var reply=confirm("se guardara una cotización. Desea continuar?")
		if (reply==true)
		{
			var clie=document.getElementById('clie_'+id).value;
			var descu= $("#desc_tot").val();
			var felab= $("#fecha").val();
			var fent= $("#fechaent").val();
			var tot= $("#cantot").val();
			var ivatot= $("#ivatot").val();
			var importe= $("#importe").val();
			var nlin= $("#nlin").val();
			var comen= $("#comentarios").val();
			var disponible= $("#disponible").val();
			var n_clie=$("#nsucursal").val();

			var direccion=$("#direccion").val();
			var cve_vendedor=$("#vendedor").val();
		
			/*alert(n_clie);
			alert(importe);
			alert(disponible);*/


			var lali= $("#alimento").val();


			if (importe>disponible) { alert("Excede limite de credito"); }

			
			$.ajax({
				type: "GET",
				url: "./ajax/addprodpedido.php",
				data: "action=addorden_varios&cli="+clie+"&fecha="+felab+"&fechaent="+fent+"&tot="+tot+"&ivat="+ivatot+"&importe="+importe+"&nlin="+nlin+"&com="+comen+"&descuento="+descu+"&lalimen="+lali+"&tdoc="+tdoc+"&n_clie="+n_clie+"&direccion="+direccion+"&cve_vendedor="+cve_vendedor,
				beforeSend: function(objeto){
					$("#resultado").html("Mensaje: Cargando...");
				},
				success:function(datos){
					$("#resultado").html(datos);
				}
			});

			var frm = document.forms['datos_factura'];
		    for(i=0; ele=frm.elements[i]; i++)
		    ele.disabled=true;

			setTimeout("location.href='cotizaciones.php'", 3000);

			
		}

	
}


function cargaprod(id){ //al iniciar pedido carga los productos si tiene un perido sin completar

			var codclie= $("#cod_cliente").val();
			
			$.ajax({
				type: "GET",
				url: "./ajax/addprodpedido.php",
				data: "action=carga_articulos&codc="+codclie,
				beforeSend: function(objeto){
					$("#resultado").html("Mensaje: Cargando...");
				},
				success:function(datos){
					$("#resultado").html(datos);
				}
			});			
}




	



