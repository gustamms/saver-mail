new Vue({
	el: "#dashboard",
	template: `
	<div class="ui centered grid" style="margin-left: 35%;">
	  <div class="sixteen wide column">
	  	<div class="ui statistics">
			  <div class="statistic">
			    <div class="value">
			      {{contatos}}
			    </div>
			    <div class="label">
			      Contatos
			    </div>
			  </div>
			  <div class="statistic">
			    <div class="value">
			      {{visualizacoes}}
			    </div>
			    <div class="label">
			      Visualizações
			    </div>
			  </div>
			  <div class="statistic">
			    <div class="value">
			      {{campanhas}}
			    </div>
			    <div class="label">
			      Campanhas ativas
			    </div>
			  </div>
			</div>
	  </div>
	</div>
	`,
	data(){
		return {
			contatos: '',
			visualizacoes: '',
			campanhas: ''
		}
	},
	mounted: function(){
		this.getContatos();
		this.getVisualizacoes();
		this.getCampanhas();
	},
	methods: {
		getContatos(){
			var app = this;
			$.post("src/contatos.php", function(response){
				if(!response.success){
					app.contatos = 0;
				}else{
					if(response.contatos[0] == undefined){
						app.contatos = 0;
					}else{
						app.contatos = response.contatos[0].qtdContatos;
					}
				}
			});
		},
		getVisualizacoes(){
			var app = this;
			$.post("src/visualizacoes.php", function(response){
				if(!response.success){
					app.visualizacoes = 0;
				}else{
					app.visualizacoes = response.visualizacoes;
				}
			});
		},
		getCampanhas(){
			var app = this;
			$.post("src/quantidade-campanhas.php", function(response){
				if(!response.success){
					app.campanhas = 0;
				}else{
					app.campanhas = response.campanhas;
				}
			});
		}
	}
})
