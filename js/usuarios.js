$(document).ready(function(){
			load(1);
			
		});

function load(page){
	
	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/buscar_usuarios.php?action=ajax',
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

function agregar(page){
	
	document.getElementById("nuevousuario").style.display="none";

	

	$("#loader").fadeIn('slow');


	$.ajax({
		url:'./ajax/buscar_usuarios.php?action=nuevo',
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

 