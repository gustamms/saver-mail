new Vue({
	el: "#formCriarConta",
	data(){
		return {
			nome: '',
			email: '',
			senha: ''
		}
	},
	mounted: function(){
		var app = this;
		window.addEventListener('resize', function(){
			app.tamanho();
		});

		var windowWidth = window.innerWidth;
	  var screenWidth = screen.width;

	  if(screenWidth < 850){
	  	$("div[name=info]").removeAttr("class");
	  	$("div[name=formUsuario]").removeAttr("class");
	  	$("div[name=formUsuario]").addClass("sixteen wide column");
	  	$("div[name=info]").addClass("sixteen wide column")
	  }else{
	  	$("div[name=info]").removeAttr("class");
	  	$("div[name=formUsuario]").removeAttr("class");
	  	$("div[name=formUsuario]").addClass("seven wide column");
	  	$("div[name=info]").addClass("nine wide column")
	  }
	},
	methods: {
		checkForm: function (e) {
			$('.big.fluid.button[name=submit]').hide();
			$('.big.fluid.loading.button').show();

			let post = JSON.stringify({
				'nome': this.nome,
				'email': this.email,
				'senha': this.senha
			});

			$.post("src/usuario-cadastro.php", {post: post}, (response)=>{
				if(!response.success){
					swal("Erro", response.error, "error");
				}else{
					swal("Sucesso", "Usuário criado com sucesso, foi encaminhado em seu e-mail um link e confirmação!", "success").then(()=>{
						location.href = 'login';
					});
				}
				$('.big.fluid.loading.button').hide();
				$('.big.fluid.button[name=submit]').show();
			});

      e.preventDefault();
    },
    tamanho:function(){
    	var windowWidth = window.innerWidth;
		  var screenWidth = screen.width;

		  if(screenWidth < 850){
		  	$("div[name=info]").removeAttr("class");
		  	$("div[name=formUsuario]").removeAttr("class");
		  	$("div[name=formUsuario]").addClass("sixteen wide column");
		  	$("div[name=info]").addClass("sixteen wide column")
		  }else{
		  	$("div[name=info]").removeAttr("class");
		  	$("div[name=formUsuario]").removeAttr("class");
		  	$("div[name=formUsuario]").addClass("seven wide column");
		  	$("div[name=info]").addClass("nine wide column")
		  }
    }
	}
})
