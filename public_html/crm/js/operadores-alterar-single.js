new Vue({
	el: "#operadores",
	template: `
		<div class="ui centered align grid">
			<div class="six wide column">
				<h2 class="ui header">Alterar operador</h2>
				<div class="ui hidden divider"></div>
				<form class="ui big form" method="post" @submit="checkForm" id="vFormCadastro">
				  <div class="sixteen wide field">
				    <label>Nome</label>
				    <input type="text" v-model="operador.nome">
				  </div>
				  <div class="fields">
					  <div class="eight wide field">
					    <label>Usuário</label>
					    <input type="text" v-model="operador.user">
					  </div>
				  </div>
				  <div class="fields">
					  <div class="eight wide field">
					    <label>Senha nova</label>
					    <input type="password" v-model="operador.senhaNova">
					  </div>
					  <div class="eight wide field">
					    <label>Repita senha nova</label>
					    <input type="password" v-model="operador.senhaNovaRepete">
					  </div>
				  </div>
				  <div class="field">
				  	<label>Ativo</label>
					  <div class="ui toggle checkbox">					  	
						  <input type="checkbox" name="status">
						  <label>Não</label>
						</div>
					</div>
				  <button class="big fluid ui green button">Alterar</button>
				</form>
			</div>
		</div>
	`,
	data(){
		return {
			operador: {
				cod: '',
				nome: '',
				status: '',
				user: '',
				senhaNova: '',
				senhaNovaRepete: ''
			}
		}
	},
	mounted: function(){

		this.operador.cod = $("#codOperador").data("operador");

		let checkbox = $('.ui.toggle.checkbox');

		$(checkbox).checkbox({
      onChecked: function(){
        checkbox = $(this).parent();
        $('label', checkbox).text('Sim');
      },
      onUnchecked: function(){
        checkbox = $(this).parent();
        $('label', checkbox).text('Não');
      },
    });

		this.getOperador().then((operador)=>{
			this.operador.nome = operador.nome;
			this.operador.status = operador.status;
			this.operador.user = operador.usuario;

			if(this.operador.status == 'F'){
				checkbox.checkbox('set checked');
				$('label', checkbox).text('Sim')
			}else{
				checkbox.checkbox();
			}
		}).catch((error)=>{
			swal("Erro", error, "error");
		});
	},
	methods: {
		getOperador(){
			return new Promise((resolve, reject) => {
				$.post("../src/operadores.php", {operador:this.operador.cod}, function(response){
					if(!response.success){
						reject(response.error);
					}else{
						resolve(response.operadores[0]);
					}
				});
			});
		},
		checkForm: function (e) {

			this.operador.status = $('.ui.toggle.checkbox').checkbox('is checked');

			let post = JSON.stringify({
				'cod' : this.operador.cod,
				'nome' : this.operador.nome,
				'status' : this.operador.status,
				'usuario' : this.operador.user,
				'senhaNova' : this.operador.senhaNova,
				'senhaNovaRepete' : this.operador.senhaNovaRepete
			});

			$.post("../src/operador-alterar.php", {post: post}, (response)=>{
				if(!response.success){
					swal("Erro", response.error, "error");
				}else{
					swal("Sucesso", "Operador alterado com sucesso!", "success").then(()=>{
						location.href = '../operadores';
					});
				}
			});

      e.preventDefault();
    }
	}
});