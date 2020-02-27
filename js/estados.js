		$(document).ready(function(){
			load(1);
			
		});

		function load(page){
			var nombresuc= $("#nombresucursal").val();
			var clavemat= $("#clavematriz").val();
			$("#loader").fadeIn('slow');
			$.ajax({
				url:'./ajax/buscar_sucursal.php?action=ajax&page='+page+'&nombre_sucursal='+nombresuc+'&clave_matriz='+clavemat,
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

		function muestrafacturas(id){
			
			var matriz= document.getElementById('id_matriz_'+id).value;
			var sucursal= document.getElementById('id_sucursal_'+id).value;
			
			$("#loader").fadeIn('slow');
			$.ajax({
				url:'./ajax/buscar_facturas_estado.php?action=ajax&clave_matriz='+matriz+'&clave_sucursal='+sucursal,
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

		function muestrafacturasdetalle(id){

			var nfactura= document.getElementById('id_factura_'+id).value;
			var tfactura= document.getElementById('t_factura_'+id).value;
			
			$("#loader").fadeIn('slow');
			$.ajax({
				url:'./ajax/buscar_facturasdetalle.php?action=ajax&cve_factura='+nfactura+'&tip_factura='+tfactura,
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
		
		function imprimir_factura(id_factura){
			VentanaCentrada('./pdf/documentos/ver_factura.php?id_factura='+id_factura,'Factura','','1024','768','true');
		}
