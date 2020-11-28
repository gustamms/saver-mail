new Vue({
	el: "#operadores",
	template: `
	<div class="ui grid">
	  <div class="sixteen wide column">
	  	<a class="ui blue button" href="cadastrar-operador">Cadastrar operador</a>
			<table class="ui celled table">
			  <thead>
			    <tr>
			    	<th>Nome</th>
			    	<th>Usuário</th>
			    	<th>Status</th>
			    	<th>Data cadastro</th>
			    	<th></th>
			  	</tr>
			  </thead>
			  <tbody>
					<tr v-for="operador in operadores">
				    <td>{{operador.nome}}</td>
				    <td>{{operador.usuario}}</td>
				    <td>{{operador.status}}</td>
				    <td>{{operador.dtCadastro}}</td>
				    <td>
							<center>
							<a :href="operador.link">
								<i class="cog link icon" ></i>
							</a>								
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
			operadores: []
		}
	},
	mounted: function(){
		let values = [];
		this.getOperadores().then((operadores)=>{
			operadores.sort(function(a, b){return b.cod - a.cod});
			$.each(operadores, function(idx, val){
				values.push({
					cod: val.cod,
					nome: val.nome,
					usuario: val.usuario,
					status: val.status == "T" ? val.status = "Desativado" : val.status = "Ativo",
					dtCadastro: moment(val.dataCadastro).calendar(),
					link: 'alterar-operador/'+val.cod
				});
			})
			this.operadores = values;
		}).catch((error)=>{
			swal("Erro", error, "error");
		});
	},
	methods: {
		getOperadores(){
			return new Promise((resolve, reject) => {
				$.post("src/operadores.php", function(response){
					if(!response.success){
						reject(response.error);
					}else{
						resolve(response.operadores);
					}
				});
			});
		}
	}
})
