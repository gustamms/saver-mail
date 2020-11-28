new Vue({
	el: "#usuarios",
	template: `
	<div class="ui grid">
	  <div class="sixteen wide column">
	  	<a class="ui blue button" href="cadastrar-usuario">Cadastrar usuário</a>
			<table class="ui celled table">
			  <thead>
			    <tr>
			    	<th>Nome</th>
			    	<th>Telefone</th>
			    	<th>Expiração</th>
			    	<th></th>
			  	</tr>
			  </thead>
			  <tbody>
					<tr v-for="usuario in usuarios">
				    <td>{{usuario.nome}}</td>
				    <td>{{usuario.num}}</td>
				    <td>{{usuario.dtExpiracao}}</td>
				    <td>
							<center>
								<i class="trash red link icon" v-on:click="remover(usuario.cod)"></i>
							</center>
				    </td>
				  </tr>
			  </tbody>
			</table>
	  </div>
	</div>
	`,
	data(){
		return {
			usuarios: []
		}
	},
	mounted: function(){
		let values = [];
		this.getUsuarios().then((usuarios)=>{
			$.each(usuarios, function(idx, val){
				values.push({
					cod: val.cod,
					nome: val.nome,
					num: val.numTelefone,
					dtExpiracao: val.dataExpiracao ? moment(val.dataExpiracao).calendar() : "Não expira"
				});
			})
			this.usuarios = values;
		}).catch((error)=>{
			swal("Erro", error, "error");
		});
	},
	methods: {
		getUsuarios(){
			return new Promise((resolve, reject) => {
				$.post("src/usuarios.php", function(response){
					if(!response.success){
						reject(response.error);
					}else{
						resolve(response.usuarios);
					}
				});
			});
		},
		remover(usuario){
			$.post("src/usuario-del.php", {usuario: usuario}, function(response){
				if(!response.success){
					swal("Erro", response.error, "error");
				}else{
					swal("Sucesso", "Usuário removido com sucesso!", "success").then(()=>{
						location.href = location.href;
					});
				}
			});
		}
	}
})
