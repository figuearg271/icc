var rnit = new Array(4,6,3,1)
		var telefono = new Array(4,4)
		function mascara(d,sep,pat,nums){
		if(d.valant != d.value){
			val = d.value
			largo = val.length
			val = val.split(sep)
			val2 = ''
			for(r=0;r<val.length;r++){ val2 += val[r]; }
			if(nums){ for(z=0;z<val2.length;z++){ if(isNaN(val2.charAt(z))){ letra = new RegExp(val2.charAt(z),"g"); val2 = val2.replace(letra,""); } } }
			val = '';
			val3 = new Array();
			for(s=0; s<pat.length; s++){ val3[s] = val2.substring(0,pat[s]); val2 = val2.substr(pat[s]); } for(q=0;q<val3.length; q++){ if(q ==0){ val = val3[q]; } else{ if(val3[q] != ""){ val += sep + val3[q]; } } } d.value = val; d.valant = val; } }


function nuevasolicitud(page){

	location.href='formato_nuevo_cliente.php';
}

function cancelar_solicitud(id){

	location.href='solicitudclientes.php';
}

function carga_solicitudes(id){

	var vendedor= $("#vendedor").val();


	$("#loader").fadeIn('slow');
		$.ajax({
			url:'./ajax/solicitud_cliente.php?action=carga_soliciutdes&vendedor='+vendedor,
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
		
function graba_solicitud(id){

	/*alert("entra");*/

	var rfc= $("#regclie").val();
	var nit= $("#nit").val();
	var giro= $("#gclie").val();
	var nombre= $("#nclie").val();
	var dir= $("#dirclie").val();
	var tel= $("#telclie").val();
	var correo= $("#correo").val();
	var matriz= $("#matclie").val();
	var tipo_clie= $("#tcliente").val();
	var ruta= $("#rutclie").val();
	var req1= $("#req1").val();
	var tipo_cont= $("#tcontribuyente").val();
	var req2= $("#req2").val();
	var nsucursal= $("#numclie").val();
	var maneja_cred= $("#mcredito").val();
	var dias_cred= $("#dcredito").val();
	var limite_cred= $("#lcredito").val();
	var vendedor= $("#vasignado").val();
	var lista_prec= $("#lprecio").val();


	if (tipo_clie=="") {alert("Selecciona tipo de cliente");  }
	else if (tipo_cont=="") {alert("Selecciona el tipo de contribuyente");  }
	else if (ruta=="") {alert("Debe ingresar # de ruta");  }
	else if (dias_cred=="") {alert("Debe ingresa si maneja credito");  }
	else if (maneja_cred=="") {alert("Selecciona si maneja credito");  }
	else if (limite_cred=="") {alert("Debe de ingresar el limite de credito");  }

	else {
	
		$("#loader").fadeIn('slow');
		$.ajax({
			url:'./ajax/solicitud_cliente.php?action=grabasolicitud&rfc='+rfc+'&nit='+nit+'&giro='+giro+'&nombre='+nombre+'&dir='+dir+'&tel='+tel+'&correo='+correo+'&matriz='+matriz+'&tipo_clie='+tipo_clie+'&ruta='+ruta+'&req1='+req1+'&tipo_cont='+tipo_cont+'&req2='+req2+'&nsucursal='+nsucursal+'&maneja_cred='+maneja_cred+'&dias_cred='+dias_cred+'&limite_cred='+limite_cred+'&vendedor='+vendedor+'&lista_prec='+lista_prec,
			 beforeSend: function(objeto){
			 $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
		  },
			success:function(data){
				$(".outer_div").html(data).fadeIn('slow');
				$('#loader').html('');
				$('[data-toggle="tooltip"]').tooltip({html:true}); 
				
			}
		});

		setTimeout("location.href='solicitudclientes.php'", 3000);
	}
}

function validareg(id){
	var rfc= $("#regclie").val();



	$("#error_dui2").fadeIn('slow');
		$.ajax({
			url:'./ajax/solicitud_cliente.php?action=valida_registro&rfc='+rfc,
			 beforeSend: function(objeto){
			 $('#error_dui2').html('<img src="./img/ajax-loader.gif"> Cargando...');
		  },
			success:function(data){
				$(".outer_div2").html(data).fadeIn('slow');
				$('#error_dui2').html('');
				$('[data-toggle="tooltip"]').tooltip({html:true}); 
				
			}
		});

}



