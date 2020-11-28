new Vue({
	el: "#vFormCadastro",
	data(){
		return {
			nome: '',
			user: '',
			pass: ''
		}
	},
	mounted: function(){
		
		// let values = [];
	},
	methods: {
		checkForm: function (e) {

			let post = JSON.stringify({
				'nome': this.nome,
				'user': this.user,
				'pass': this.pass
			});

			$.post("src/operador-cad.php", {post: post}, (response)=>{
				if(!response.success){
					swal("Erro", response.error, "error");
				}else{
					swal("Sucesso", "Operador criado com sucesso!", "success").then(()=>{
						location.href = 'operadores';
					});
				}
			});

      e.preventDefault();
    }
	}
})
