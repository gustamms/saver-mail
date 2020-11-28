new Vue({
	el: "#header",
	template: `
	<div>
		<div class="ui sidebar vertical left menu overlay visible">
		  <div class="item logo">
		    <img src="https://image.flaticon.com/icons/svg/866/866218.svg" /><img src="https://image.flaticon.com/icons/svg/866/866218.svg" style="display: none" />
		  </div>
		  <div class="ui accordion">
		    <a class="item" :href="url.url+'/dashboard'">
		      <b>Dashboard</b>
		    </a>
		    <div class="title item">
		      <i class="dropdown icon"></i> Gerenciamento de campanhas
		    </div>
		    <div class="content">
		      <a class="item" :href="url.url+'/campanhas'">Campanhas</a>
		      <a class="item" :href="url.url+'/contatos'">Contatos</a>
		    </div>
		  </div>
		</div>
		<div class="pusher">
		  <div class="ui menu asd borderless">
		    <a class="item openbtn">
		      <i class="icon content"></i>
		    </a>
		    <a class="item">Olá, {{usuario.nome}}</a>
		    <div class="right menu">
		      <div class="item">
		        <div class="ui red button" v-on:click="sair">Sair</div>
		      </div>
		    </div>
		  </div>
		</div>
	</div>
	`,
	data(){
		return {
			usuario: {
				cod: '',
				nome: ''
			},
			url: {
				url: ''
			}
		}
	},
	mounted: function(){
		this.url.url = $("#url").data("url");
		this.getUsuarioLogado().then((usuario)=>{
			this.usuario.cod = usuario.cod;
			this.usuario.nome = usuario.nome;
		}).catch((error)=>{console.log(error);});

	 	$(".openbtn").on("click", function() {
		  $(".ui.sidebar").toggleClass("very thin icon");
		  $(".asd").toggleClass("marginlefting");
		  $(".sidebar z").toggleClass("displaynone");
		  $(".ui.accordion").toggleClass("displaynone");
		  $(".ui.dropdown.item").toggleClass("displayblock");

		  $(".logo").find('img').toggle();
	 });
	 	
	 $(".ui.dropdown").dropdown({
	   allowCategorySelection: true,
	   transition: "fade up",
	   context: 'sidebar',
	   on: "hover"
	 });

	 $('.ui.accordion').accordion({
	   selector: {

	   }
	 });
	},
	methods: {
		getUsuarioLogado(){
			return new Promise((resolve, reject) => {
				$.post(this.url.url+"/src/usuario-logado.php", function(response){
					if(!response.success){
						reject(response.error);
					}else{
						resolve(response.usuario);
					}
				});
			});
		},
		sair(){
			$.post(this.url.url+"/src/sair.php", function(response){
				if(!response.success){
					swal("Erro", "Não foi possível deslogar", "error");
				}else{
					location.href = response.urlLogout;
				}
			});
		}
	}
})