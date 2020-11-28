new Vue({
	el: "#alterarCampanha",
	data(){
		return {
			campanha:{
				cod: '',
				descricao: '',
				corpo: ''				
			},
			contatos:{
				ativo: [],
				selecionado:[]
			}
		}
	},
	mounted: function(){
		var app = this;
		this.campanha.cod = $("#codCampanha").data("campanha");

		this.getCampanha();

		let values = [];
		this.getContatos().then((contatos)=>{
			$.each(contatos, function(idx, val){
				values.push({
					cod: val.cod,
					nome: val.nome,
					email: val.email
				});
			})
			this.contatos.ativos = values;

			setTimeout(()=>{
				$(".checkbox").checkbox();
				$(".checkbox[name=todos]").checkbox({
					onChecked: function(){
						$(".checkbox").checkbox("check");
					},
					onUnchecked: function(){
						$(".checkbox").checkbox("uncheck");
					}
				});
				this.getContatosCampanha();
			}, 1500);

		}).catch((error)=>{
			swal("Erro", error, "error");
		});
	},
	methods: {
		checkForm: function (e) {
      e.preventDefault();

			let post = JSON.stringify({
				'cod'				: this.campanha.cod,
				'descricao'	: this.campanha.descricao,
				'corpo'			: this.campanha.corpo,
				'contatos' 	: this.contatos.selecionado
			});

			$.post("../src/campanha-alterar.php", {post: post}, (response)=>{
				if(!response.success){
					swal("Erro", response.error, "error");
				}else{
					swal("Sucesso", "Campanha alterada com sucesso!", "success").then(()=>{
						location.href = '../contatos';
					});
				}
			});
    },
    getCampanha(){
    	$.post('../src/campanhas.php', {campanha: this.campanha.cod}, (response) => {
    		if(response.success){
    			this.campanha.descricao = response.campanhas[0].descricao;
    			this.campanha.corpo = response.campanhas[0].conteudo;
    		}else{
    			swal("Erro", response.error, "error");
    		}
    	});
    },
    getContatos(){
			return new Promise((resolve, reject) => {
				$.post("../src/contatos.php", function(response){
					if(!response.success){
						reject(response.error);
					}else{
						resolve(response.contatos);
					}
				});
			});
		},
		getContatosCampanha(){
			$.post("../src/contatos-campanha.php", {campanha: this.campanha.cod}, function(response){
				if(!response.success){
					swal("Erro", response.error, "error");
				}else{
					$.each(response.contatos, function(idx, val){
						$("input[value="+val.cod+"]",".checkbox").parent().checkbox('check');
					});
				}
			});
		}
	}
})
