new Vue({
	el: "#cadastrarCampanha",
	data(){
		return {
			descricao: '',
			corpo: '',
			contatos: {
				ativos: [],
				selecionado:[]
			}
		}
	},
	mounted: function(){

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
			}, 1500);

		}).catch((error)=>{
			swal("Erro", error, "error");
		});

	},
	methods: {
		checkForm: function (e) {

      e.preventDefault();

			let post = JSON.stringify({
				'descricao'	: this.descricao,
				'corpo'			: this.corpo,
				'contatos'	: this.contatos.selecionado
			});

			$.post("src/campanha-cadastro.php", {post: post}, (response)=>{
				if(!response.success){
					swal("Erro", response.error, "error");
				}else{
					swal("Sucesso", "Campanha criada com sucesso!", "success").then(()=>{
						location.href = 'campanhas';
					});
				}
			});
    },
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
