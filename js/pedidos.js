/*$(document).ready(function(){
			load(1);
			
		});*/
function anular(e) {
          tecla = (document.all) ? e.keyCode : e.which;
          return (tecla != 13);
         
     }
     

function load(page)
{
	var nombresuc= $("#nombresucursal").val();
	var clavemat= $("#clavematriz").val();
	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/buscar_sucursal_envios.php?action=ajax&page='+page+'&nombre_sucursal='+nombresuc+'&clave_matriz='+clavemat,
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

function bclientes_envios(page)
{
	var nombre= $("#nombre").val();
	var vendedor= $("#vendedor").val();



	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/buscar_sucursal_envios.php?action=bclientes_envios&nombre='+nombre+'&vendedor='+vendedor,
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


function busca_envios(id)
{
	
	var matriz= document.getElementById('id_matriz_'+id).value;
	var sucursal= document.getElementById('id_sucursal_'+id).value;
	var nsucursal= document.getElementById('n_sucursal_'+id).value;
	
	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/busca_envios.php?action=ajax&clave_matriz='+matriz+'&clave_sucursal='+sucursal+'&nombre_sucursal='+nsucursal,
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

function busca_envios_clientes_vendedor(id)
{
	
	var matriz= document.getElementById('id_matriz_'+id).value;
	var sucursal= document.getElementById('id_sucursal_'+id).value;
	var nsucursal= document.getElementById('n_sucursal_'+id).value;


	document.getElementById("nombre").style.display="none";
	document.getElementById("backpedidos").style.display="initial";
	document.getElementById("npedidos").innerHTML = "Cliente seleccionado";
	document.getElementById("b_cliente").style.display="none";
	
	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/busca_envios.php?action=ajax&clave_matriz='+matriz+'&clave_sucursal='+sucursal+'&nombre_sucursal='+nsucursal,
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

function muestraenviodetalle(id)
{

	var nfac= document.getElementById('id_fac_'+id).value;
	var nsuc= document.getElementById('id_suc_'+id).value;
	var nomsuc= document.getElementById('n_suc_'+id).value;

	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/busca_envio_detalle.php?action=ajax&cve_fac='+nfac+'&cve_suc='+nsuc+'&nombre_suc='+nomsuc,
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

function muestrafacturadetalle(id)
{

	var nfac= document.getElementById('id_fac_'+id).value;
	var nsuc= document.getElementById('id_suc_'+id).value;
	var nomsuc= document.getElementById('n_suc_'+id).value;

	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/busca_factura_detalle.php?action=ajax&cve_fac='+nfac+'&cve_suc='+nsuc+'&nombre_suc='+nomsuc,
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

function eliminar (id)
{
	var q= $("#q").val();
	if (confirm("Realmente deseas eliminar la factura")){	
	$.ajax({
	type: "GET",
	url: "./ajax/buscar_facturas.php",
	data: "id="+id,"q":q,
	 beforeSend: function(objeto){
		$("#resultados").html("Mensaje: Cargando...");
	  },
	success: function(datos){
	$("#resultados").html(datos);
	load(1);
	}
		});
	}
}

function nuevo_pedido(id)
{
	
	var matriz= document.getElementById('id_matriz_'+id).value;
	var sucursal= document.getElementById('id_sucursal_'+id).value;
	var nsucursal= document.getElementById('n_sucursal_'+id).value;

	window.location.href = './pedido.php?&cus='+sucursal+'&mon='+nsucursal;
	
	/*$("#loader").fadeIn('slow');
	$.ajax({
		url:'./pedido.php?clave_matriz='+matriz+'&clave_sucursal='+sucursal+'&nombre_sucursal='+nsucursal,
		 beforeSend: function(objeto){
		 $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
	  },
		success:function(data){
			$(".outer_div").html(data).fadeIn('slow');
			$('#loader').html('');
			$('[data-toggle="tooltip"]').tooltip({html:true}); 
			
		}
	})*/
}

function pedido_vendedor(id)
{	
	var clave= document.getElementById('clave_sae'+id).value;
	var nombre= document.getElementById('nombre_sae'+id).value;
	var tipo= document.getElementById('tipo_sae'+id).value;
	var matriz= document.getElementById('matriz_sae'+id).value;
	var disponible= document.getElementById('disponible_sae'+id).value;

	window.location.href ='./pedido.php?action=pedido&eva='+clave+'&mon='+nombre+'&tipo='+tipo+'&matriz='+matriz+'&disponible='+disponible;
}

function pedido_vendedor_cotizacion(id)
{	
	var clave= document.getElementById('clave_sae'+id).value;
	var nombre= document.getElementById('nombre_sae'+id).value;
	var tipo= document.getElementById('tipo_sae'+id).value;
	var matriz= document.getElementById('matriz_sae'+id).value;
	var disponible= document.getElementById('disponible_sae'+id).value;

	window.location.href ='./pedido.php?action=cotizaciones&eva='+clave+'&mon='+nombre+'&tipo='+tipo+'&matriz='+matriz+'&disponible='+disponible;
}

function cot_vend(id)
{	
	var clave= document.getElementById('clave_sae'+id).value;
	var nombre= document.getElementById('nombre_sae'+id).value;
	var tipo= document.getElementById('tipo_sae'+id).value;
	var matriz= document.getElementById('matriz_sae'+id).value;
	var disponible= document.getElementById('disponible_sae'+id).value;
	
	window.location.href ='./pedido.php?action=cotizaciones&eva='+clave+'&mon='+nombre+'&tipo='+tipo+'&matriz='+matriz+'&disponible='+disponible;
}

function cot_vend_varios(id)
{	
	
	var clave= 'N0101';
	var nombre= 'Clientes Varios';
	var tipo= 'M';
	var matriz= 'N0101';
	var disponible= '700';
	
	window.location.href ='./pedido.php?action=cotizaciones_varios&eva='+clave+'&mon='+nombre+'&tipo='+tipo+'&matriz='+matriz+'&disponible='+disponible;
}



function imprimir_factura(id_factura){
	VentanaCentrada('./pdf/documentos/ver_factura.php?id_factura='+id_factura,'Factura','','1024','768','true');
}
