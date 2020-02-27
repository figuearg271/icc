function busca_empresas(page){
	
	
	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/buscar_empresas.php?action=buscando_empresas',
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