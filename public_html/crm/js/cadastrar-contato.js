new Vue({
	el: "#cadastrarContato",
	data(){
		return {
			contato:{
				nome: '',
				email: ''
			}
		}
	},
	mounted: function(){

	},
	methods: {
		checkForm: function (e) {
      e.preventDefault();

			let post = JSON.stringify({
				'nome': this.contato.nome,
				'email': this.contato.email
			});

			$.post("src/contato-cadastro.php", {post: post}, (response)=>{
				if(!response.success){
					swal("Erro", response.error, "error");
				}else{
					swal("Sucesso", "Contato criado com sucesso!", "success").then(()=>{
						location.href = 'contatos';
					});
				}
			});
    }
	}
})
