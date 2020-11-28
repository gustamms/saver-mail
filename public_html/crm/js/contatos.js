new Vue({
	el: "#contatos",
	template: `
	<div class="ui grid">
	  <div class="sixteen wide column">
	  	<a class="ui blue button" href="cadastrar-contato">Cadastrar novo contato</a>
			<table class="ui celled table">
			  <thead>
			    <tr>
			    	<th>Nome</th>
			    	<th>Email</th>
			    	<th>Data Cadastro</th>
			    	<th></th>
			  	</tr>
			  </thead>
			  <tbody>
					<tr v-for="contato in contatos">
				    <td>{{contato.nome}}</td>
				    <td>{{contato.email}}</td>
				    <td>{{contato.dtCadastro}}</td>
				    <td>
							<center>
							<a :href="contato.link">
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
			contatos: []
		}
	},
	mounted: function(){
		let values = [];
		this.getContatos().then((contatos)=>{
			$.each(contatos, function(idx, val){
				values.push({
					cod: val.cod,
					nome: val.nome,
					email: val.email,
					dtCadastro: moment(val.dataCadastro).calendar(),
					link: 'alterar-contato/'+val.cod
				});
			})
			this.contatos = values;
		}).catch((error)=>{
			swal("Erro", error, "error");
		});
	},
	methods: {
		getContatos(){
			return new Promise((resolve, reject) => {
				$.post("src/contatos.php", function(response){
					if(!response.success){
						reject(response.error);
					}else{
						resolve(response.contatos);
					}
				});
			});
		}
	}
})
