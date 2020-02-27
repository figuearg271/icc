function busca_producto(id){
	
	var nombre= $("#producto").val();
	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/buscar_productos.php?action=catalogo&producto='+nombre,
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