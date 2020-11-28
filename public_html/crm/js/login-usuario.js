new Vue({
	el: "#formLogin",
	data(){
		return {
			email: '',
			senha: ''
		}
	},
	mounted: function(){

	},
	methods: {
		checkForm: function (e) {

			let post = JSON.stringify({
				'email': this.email,
				'senha': this.senha
			});

			$.post("src/login-usuario.php", {post: post}, (response)=>{
				if(!response.success){
					swal("Erro", response.error, "error");
				}else{
					swal("Sucesso", "Logado com sucesso!", "success").then(()=>{
						location.href = 'dashboard';
					});
				}
			});

      e.preventDefault();
    }
	}
})
