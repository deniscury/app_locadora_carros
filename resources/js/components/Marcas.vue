<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">

                <!-- Ínicio - Form de busca -->
                <card-component
                    titulo="Busca de Marcas">
                        <template v-slot:conteudo>
                            <div class="form-row">
                                <div class="col mb-3">
                                    <input-container-component
                                        id="id"
                                        label="ID"
                                        help="idHelp"
                                        msg-help="Informe o ID da marca">
                                            <input type="number" class="form-control" id="id" aria-describedby="idHelp" placeholder="ID" v-model="busca.id">
                                    </input-container-component>
                                </div>
                                <div class="col mb-3">
                                    <input-container-component
                                        id="nome"
                                        label="Nome"
                                        help="nomeHelp"
                                        msg-help="Informe o nome da marca">
                                            <input type="text" class="form-control" id="nome" aria-describedby="nomeHelp" placeholder="Nome" v-model="busca.nome">
                                    </input-container-component>
                                </div>
                            </div>
                        </template>
                        <template v-slot:rodape>
                            <button type="submit" class="btn btn-primary btn-sm float-right" @click="buscar()">Buscar</button>
                        </template>
                </card-component>              
                <!-- Fim - Form de busca -->

                <!-- Ínicio - Tabela -->
                <card-component
                    titulo="Lista de Marcas">
                        <template v-slot:conteudo>
                            <table-component
                                :dados="marcas.data"
                                :visualizar="{
                                    exibir: true,
                                    dataToggle: 'modal',
                                    dataTarget: '#modal-visualizar'
                                }"
                                :atualizar="{
                                    exibir: true,
                                    dataToggle: 'modal',
                                    dataTarget: '#modal-atualizar'
                                }"
                                :excluir="{
                                    exibir: true,
                                    dataToggle: 'modal',
                                    dataTarget: '#modal-excluir'
                                }"
                                :titulos="{
                                    id : {titulo : 'ID', tipo : 'text'},
                                    nome : {titulo : 'Nome', tipo : 'text'},
                                    imagem : {titulo : 'Imagem', tipo : 'img'},
                                    created_at :{titulo : 'Data de Criação', tipo : 'date'},
                                    updated_at :{titulo : 'Data de Alteração', tipo : 'date'},
                                }"></table-component>
                        </template>

                        <template v-slot:rodape>
                            <div class="row">
                                <div class="col-10">
                                    <paginate-component> 
                                        <li :class="'page-item'+(link.active? ' active' : '')" v-for="link, key in marcas.links" :key="key" @click="paginacao(link)">
                                            <a class="page-link" v-html="link.label"></a>
                                        </li>
                                    </paginate-component> 
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-primary btn-sm float-right" @click="$store.state.alerta = false;" data-toggle="modal" data-target="#modal-marca">Adicionar</button>
                                </div>
                            </div>
                        </template>
                </card-component>  
                <!-- Fim - Tabela -->

                <!-- Ínicio - Modal Inclusão de Marca -->
                <modal-component
                    titulo="Adicionar Marca"
                    id="modal-marca">
                        <template v-slot:alertas>
                            <alert-component v-if="$store.state.alerta"
                                :titulo="titulo"
                                :tipo="tipo"
                                :retorno="retorno">
                            </alert-component>
                        </template>

                        <template v-slot:conteudo>
                            <div class="col mb-3">
                                <input-container-component
                                    id="novoNome"
                                    label="Nome"
                                    help="novoNomeHelp"
                                    msg-help="Informe o nome da marca">
                                        <input type="text" class="form-control" id="novoNome" v-model="nomeMarca" aria-describedby="novoNomeHelp" placeholder="Nome da Marca">
                                </input-container-component>
                            </div>

                            <div class="col mb-3">
                                <input-container-component
                                    id="novoImagem"
                                    label="Imagem"
                                    help="novoImagemHelp"
                                    msg-help="Selecione uma imagem no formato PNG">
                                        <input type="file" class="form-control-file" id="novoImagem" @change="carregarImagem($event)">
                                </input-container-component>
                            </div>
                        </template>
                        <template v-slot:rodape>
                            <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        </template>
                </modal-component>
                <!-- Fim - Modal Inclusão de Marca -->

                <!-- Ínicio - Modal Atualização de Marca -->
                <modal-component
                    titulo="Atualizar Marca"
                    id="modal-atualizar">
                        <template v-slot:alertas>
                            <alert-component v-if="$store.state.alerta"
                                :titulo="titulo"
                                :tipo="tipo"
                                :retorno="retorno">
                            </alert-component>
                        </template>

                        <template v-slot:conteudo>
                            <div class="col mb-3">
                                <input-container-component
                                    id="atualizarNome"
                                    label="Nome"
                                    help="atualizarNomeHelp"
                                    msg-help="Informe o nome da marca">
                                        <input type="text" class="form-control" id="atualizarNome" v-model="$store.state.item.nome" aria-describedby="atualizarNomeHelp" placeholder="Nome da Marca">
                                </input-container-component>
                            </div>

                            <div class="col mb-3">
                                <input-container-component
                                    id="atualizarImagem"
                                    label="Imagem"
                                    help="atualizarImagemHelp"
                                    msg-help="Selecione uma imagem no formato PNG">
                                        <input type="file" class="form-control-file" id="atualizarImagem" aria-describedby="atualizarImagemHelp" @change="carregarImagem($event)">
                                </input-container-component>
                            </div>
                        </template>
                        <template v-slot:rodape>
                            <button type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        </template>
                </modal-component>
                <!-- Fim - Modal Atualização de Marca -->

                <!-- Ínicio - Modal Visualizar Marca -->
                <modal-component
                    titulo="Visualizar Marca"
                    id="modal-visualizar">
                        <template v-slot:alertas></template>
                        <template v-slot:conteudo>
                            <div class="form-row">
                                <div class="col-2 mb-3">
                                    <input-container-component label="ID">
                                        <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                                    </input-container-component>
                                </div>
                                <div class="col-10 mb-3">
                                    <input-container-component label="Nome">
                                        <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                                    </input-container-component>
                                </div>
                                <div class="col-6 mb-3">
                                    <input-container-component label="Data de Criação">
                                        <input type="text" class="form-control" :value="$store.state.item.created_at | formataDataHora" disabled>
                                    </input-container-component>
                                </div>
                                <div class="col-6 mb-3">
                                    <input-container-component label="Data de Alteração">
                                        <input type="text" class="form-control" :value="$store.state.item.updated_at | formataDataHora" disabled>
                                    </input-container-component>
                                </div>
                            </div>
                            <div class="form-row text-center">
                                <div class="col-12 mb-3">
                                    <input-container-component>
                                        <img v-if="$store.state.item.imagem" :src="'/storage/'+$store.state.item.imagem" width="300" height="300"/>
                                    </input-container-component>
                                </div>
                            </div>
                        </template>
                        <template v-slot:rodape>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        </template>
                </modal-component>
                <!-- Fim - Modal Visualizar Marca -->

                <!-- Ínicio - Modal Excluir Marca -->
                <modal-component
                    titulo="Excluir Marca"
                    id="modal-excluir">
                        <template v-slot:alertas>
                            <alert-component v-if="$store.state.alerta"
                                :titulo="titulo"
                                :tipo="tipo"
                                :retorno="retorno">
                            </alert-component>
                        </template>
                        <template v-slot:conteudo>
                            <div class="form-row">
                                <div class="col-4 mb-3">
                                    <input-container-component label="ID">
                                        <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                                    </input-container-component>
                                </div>
                                <div class="col-4 mb-3">
                                    <input-container-component label="Nome">
                                        <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                                    </input-container-component>
                                </div>
                            </div>
                        </template>
                        <template v-slot:rodape>
                            <button type="button" class="btn btn-danger" @click="excluir()">Excluir</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        </template>
                </modal-component>
                <!-- Fim - Modal Excluir Marca -->

            </div>
        </div>       
    </div>
</template>
<script>
    export default {
        data(){
            return{
                urlBase: 'http://localhost:8000/api/v1/marca',
                urlPaginacao: '',
                urlFiltro: '',
                nomeMarca: '',
                arquivoImagem: [],
                retorno: false,
                marcas: { data : [] },
                busca: {id: '', nome: ''}
            }
        },
        methods:{
            carregarImagem(e){
                this.arquivoImagem = e.target.files;
            },
            listar(){
                let url = this.urlBase+'?'+this.urlPaginacao+this.urlFiltro;

                axios.get(url)
                    .then(response => {
                        this.marcas = response.data;
                    })
                    .catch(errors => {
                        console.log(errors);
                    }
                );
            },
            paginacao(link){
                if (link.url){
                    let parametro = link.url.split('?');

                    this.urlPaginacao = parametro[1];

                    this.listar();
                }
            },
            buscar(){
                let filtro = '';

                for(let chave in this.busca){
                    if (this.busca[chave]){
                        filtro += (filtro == '' ? '' : ';')+chave+':like:'+this.busca[chave];
                    }
                }

                if (filtro !== ''){  
                    this.urlPaginacao =  'page=1'             
                    this.urlFiltro = '&filtros='+filtro;
                }
                else{
                    this.urlFiltro = '';
                }

                this.listar();
            },
            salvar(){
                let formData = new FormData();

                formData.append('nome', this.nomeMarca);
                formData.append('imagem', this.arquivoImagem[0]);

                let config = {
                    headers : {
                        'Content-Type' : 'multipart/form-data'
                    }
                }

                axios.post(this.urlBase, formData, config)
                    .then(response => {
                        this.titulo = 'Cadastro realizado com sucesso!';
                        this.tipo = 'success';
                        this.retorno = {
                            mensagem: 'ID Cadastrado: '+response.data.id
                        };
                        this.$store.state.alerta = true;
                        novoImagem.value = '';
                        this.listar();
                    })
                    .catch(errors => {
                        this.titulo = 'Atenção! Erro ao tentar cadastrar a marca.';
                        this.tipo = 'danger';
                        this.retorno = {
                            mensagem: errors.response.data.message,
                            erros: errors.response.data.errors
                        };
                        this.$store.state.alerta = true;
                    }
                );
            },
            atualizar(){
                let url = this.urlBase + '/' + this.$store.state.item.id;

                let formData = new FormData();

                formData.append('_method', 'PATCH');
                formData.append('nome', this.$store.state.item.nome);

                if (this.arquivoImagem[0]){
                    formData.append('imagem', this.arquivoImagem[0]);
                }

                let config = {
                    headers : {
                        'Content-Type' : 'multipart/form-data'   
                    }
                }

                axios.post(url, formData, config)
                    .then(response => {
                        this.titulo = 'Cadastro atualizado com sucesso!';
                        this.tipo = 'success';
                        this.retorno = {
                            mensagem: 'ID Atualizado: '+response.data.id
                        };
                        this.$store.state.alerta = true;
                        atualizarImagem.value = '';
                        this.listar();
                    })
                    .catch(errors => {
                        this.titulo = 'Atenção! Erro ao tentar atualizar a marca.';
                        this.tipo = 'danger';
                        this.retorno = {
                            mensagem: errors.response.data.erro,
                            erros: errors.response.data.errors
                        };
                        this.$store.state.alerta = true;
                    }
                );
            },
            excluir(){
                let confirmacao = confirm('Tem certeza de que deseja excluir esse registro?');

                if (!confirmacao){
                    return false;
                }

                let url = this.urlBase + '/' + this.$store.state.item.id;

                let formData = new FormData();

                formData.append('_method', 'DELETE');

                axios.post(url, formData)
                    .then(response => {
                        this.titulo = 'Cadastro excluído com sucesso!';
                        this.tipo = 'success';
                        this.retorno = {
                            mensagem: response.data.msg
                        };
                        this.$store.state.alerta = true;
                        this.listar();
                    })
                    .catch(errors => {
                        this.titulo = 'Atenção! Erro ao tentar excluir a marca.';
                        this.tipo = 'danger';
                        this.retorno = {
                            mensagem: errors.response.data.erro
                        };
                        this.$store.state.alerta = true;
                    }
                );
            }
        },
        mounted() {
            this.listar();
        }
    }
</script>

