new Vue({
	el: "#alterarContato",
	data(){
		return {
			contato:{
				cod: '',
				nome: '',
				email: ''
			}
		}
	},
	mounted: function(){
		this.contato.cod = $("#codContato").data("contato");
		this.getContato();

	},
	methods: {
		checkForm: function (e) {
      e.preventDefault();

			let post = JSON.stringify({
				'cod'		: this.contato.cod,
				'nome'	: this.contato.nome,
				'email'	: this.contato.email
			});

			$.post("../src/contato-alterar.php", {post: post}, (response)=>{
				if(!response.success){
					swal("Erro", response.error, "error");
				}else{
					swal("Sucesso", "Contato alterado com sucesso!", "success").then(()=>{
						location.href = '../contatos';
					});
				}
			});
    },
    getContato: function(){
    	$.post('../src/contatos.php', {contato: this.contato.cod}, (response) => {
    		if(response.success){
    			this.contato.nome = response.contatos[0].nome;
    			this.contato.email = response.contatos[0].email;
    		}else{
    			swal("Erro", response.error, "error");
    		}
    	});
    }
	}
})
