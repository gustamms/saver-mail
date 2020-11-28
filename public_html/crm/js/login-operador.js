$(document).ready(function(){
	$('.button[name=submit]').click(function(){
		var form = $("form");
		$.post("./src/login-operador.php", {post: form.serialize()}, (response)=>{
			if(response.success){
				if(window.innerWidth < 1000){
					window.location.href = "filas";
				}else{
					window.location.href = "usuarios";
				}				
			}else{
				Swal(response.error);
			}
		});
	});
})