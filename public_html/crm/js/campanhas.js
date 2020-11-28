new Vue({
	el: "#campanhas",
	template: `
	<div class="ui grid">
	  <div class="sixteen wide column">
	  	<a class="ui blue button" href="cadastrar-campanha">Cadastrar nova campanha</a>
			<table class="ui celled table">
			  <thead>
			    <tr>
			    	<th>Assunto</th>
			    	<th>Data cadastro</th>
			    	<th>Status</th>
			    	<th>Total de visualizações</th>
			    	<th>Total de contatos</th>
			    	<th>Enviar campanha</th>
			    	<!--<th></th>-->
			  	</tr>
			  </thead>
			  <tbody>
					<tr v-for="campanha in campanhas">
				    <td>{{campanha.descricao}}</td>
				    <td>{{campanha.dtCadastro}}</td>
				    <td>{{campanha.status}}</td>
				    <td>{{campanha.totVisualizacao}}</td>
				    <td>{{campanha.totContatos}}</td>
				    <td>
							<center v-if="campanha.status == 'Ativo'">
							<a v-on:click="enviar(campanha.cod)">
								<i class="envelope link icon" ></i>
							</a>								
							</center>
				    </td>
				    <!--<td>
							<center ">
							<a :href="campanha.link">
								<i class="cog link icon" ></i>
							</a>								
							</center>
				    </td>-->
				  </tr>
			  </tbody>
			</table>
	  </div>
	</div>
	`,
	data(){
		return {
			campanhas: []
		}
	},
	mounted: function(){
		let values = [];
		this.getCampanha().then((campanhas)=>{
			$.each(campanhas, function(idx, val){
				let status = "Ativo";

				switch(val.status){
					case "A":
						status = "Ativo";
						break;
					case "D":
						status = "Desativado"
						break;
					case "E":
						status = "Enviado"
						break;
				}

				values.push({
					cod 				: val.cod,
					descricao 	: val.descricao,
					dtCadastro 	: moment(val.dataCadastro).format('L'),
					status 			: status,
					totVisualizacao	: val.totVisualizacao,
					totContatos	: val.totContatos,
					link: 'alterar-campanha/'+val.cod
				});
			})
			this.campanhas = values;
		}).catch((error)=>{
			swal("Erro", error, "error");
		});
	},
	methods: {
		getCampanha(){
			return new Promise((resolve, reject) => {
				$.post("src/campanhas.php", function(response){
					if(!response.success){
						reject(response.error);
					}else{
						resolve(response.campanhas);
					}
				});
			});
		},
		enviar(cod){
			console.log(cod);
			$.post("src/envia-campanha.php", {post: cod}, function(response){
				if(!response.success){
					swal("Erro", response.error, "error");
				}else{
					swal("Sucesso", "Campanha enviada com sucesso!", "success");
				}
			});
		}
	}
})
