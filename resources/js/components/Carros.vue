<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">

                <!-- Ínicio - Form de busca -->
                <card-component
                    titulo="Busca de Modelos">
                        <template v-slot:conteudo>
                            <div class="form-row">
                                <div class="col mb-3">
                                    <input-container-component
                                        id="id"
                                        label="ID"
                                        help="idHelp"
                                        msg-help="Informe o ID do modelo">
                                            <input type="number" class="form-control" id="id" aria-describedby="idHelp" placeholder="ID" v-model="busca.id">
                                    </input-container-component>
                                </div>
                                <div class="col mb-3">
                                    <input-container-component
                                        id="nome"
                                        label="Nome"
                                        help="nomeHelp"
                                        msg-help="Informe o nome do modelo">
                                            <input type="text" class="form-control" id="nome" aria-describedby="nomeHelp" placeholder="Nome" v-model="busca.nome">
                                    </input-container-component>
                                </div>
                                <div class="col mb-3">
                                    <input-container-component
                                        id="marca_id"
                                        label="Marca"
                                        help="marcaHelp"
                                        msg-help="Informe a marca">
                                            <select class="form-control" id="marca_id" aria-describedby="marcaHelp" v-model="busca.marca_id">
                                                <option value="">Todos</option>
                                                <option v-for="marca, key in marcas.data" :key="key" :value="marca.id">{{marca.nome}}</option>
                                            </select>
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
                    titulo="Lista de Modelos">
                        <template v-slot:conteudo>
                            <table-component
                                :dados="modelos.data"
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
                                    numero_portas : {titulo : 'Portas', tipo : 'text'},
                                    lugares : {titulo : 'Lugares', tipo : 'text'},
                                    air_bag : {titulo : 'Airbag', tipo : 'boolean'},
                                    abs : {titulo : 'ABS', tipo : 'boolean'},
                                    marca : {titulo : 'Marca', tipo : 'array', campo: 'nome'},
                                    created_at :{titulo : 'Data de Criação', tipo : 'date'},
                                    updated_at :{titulo : 'Data de Alteração', tipo : 'date'},
                                }"></table-component>
                        </template>

                        <template v-slot:rodape>
                            <div class="row">
                                <div class="col-10">
                                    <paginate-component> 
                                        <li :class="'page-item'+(link.active? ' active' : '')" v-for="link, key in modelos.links" :key="key" @click="paginacao(link)">
                                            <a class="page-link" v-html="link.label"></a>
                                        </li>
                                    </paginate-component> 
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-primary btn-sm float-right" @click="$store.state.alerta = false;" data-toggle="modal" data-target="#modal-modelo">Adicionar</button>
                                </div>
                            </div>
                        </template>
                </card-component>  
                <!-- Fim - Tabela -->

                <!-- Ínicio - Modal Inclusão de Modelo -->
                <modal-component
                    titulo="Adicionar Modelo"
                    id="modal-modelo">
                        <template v-slot:alertas>
                            <alert-component v-if="$store.state.alerta"
                                :titulo="titulo"
                                :tipo="tipo"
                                :retorno="retorno">
                            </alert-component>
                        </template>

                        <template v-slot:conteudo>
                            <div class="row justify-content-center">
                                <div class="col-6 mb-3">
                                    <input-container-component
                                        id="nomeModelo"
                                        label="Nome"
                                        help="nomeModeloHelp"
                                        msg-help="Informe o nome do modelo">
                                            <input type="text" class="form-control" id="nome" v-model="nomeModelo" aria-describedby="nomeModeloHelp" placeholder="Nome do Modelo">
                                    </input-container-component>
                                </div>

                                <div class="col-6 mb-3">
                                    <input-container-component
                                        id="marcaModelo"
                                        label="Marca"
                                        help="marcaModeloHelp"
                                        msg-help="Informe a marca">
                                            <select class="form-control" id="marca_id" aria-describedby="marcaModeloHelp" v-model="marcaModelo">
                                                <option 
                                                    v-for="marca, key in marcas.data" 
                                                    :key="key" 
                                                    :value="marca.id">{{marca.nome}}</option>
                                            </select>
                                    </input-container-component>
                                </div>

                                <div class="col-6 mb-3">
                                    <input-container-component
                                        id="portasModelo"
                                        label="Número de Portas"
                                        help="portasModeloHelp"
                                        msg-help="Quantas portas?">
                                            <select class="form-control" id="numero_portas" aria-describedby="portasModeloHelp" v-model="portasModelo">
                                                <option 
                                                    v-for="portas, key in [2, 3, 4, 5]" 
                                                    :key="key" 
                                                    :value="portas">{{portas}}</option>
                                            </select>
                                    </input-container-component>
                                </div>

                                <div class="col-6 mb-3">
                                    <input-container-component
                                        id="lugaresModelo"
                                        label="Lugares"
                                        help="lugaresModeloHelp"
                                        msg-help="Quantos lugares?">
                                            <select class="form-control" id="lugares" aria-describedby="lugaresModeloHelp" v-model="lugaresModelo">
                                                <option 
                                                    v-for="lugares, key in [2, 3, 4, 5, 6, 7]" 
                                                    :key="key" 
                                                    :value="lugares">{{lugares}}</option>
                                            </select>
                                    </input-container-component>
                                </div>

                                <div class="col-6 mb-3">
                                    <input-container-component
                                        id="airbagModelo"
                                        label="Airbag"
                                        help="airbagModeloHelp"
                                        msg-help="Tem airbag?">
                                            <select class="form-control" id="air_bag" aria-describedby="airbagModeloHelp" v-model="airbagModelo">
                                                <option 
                                                    v-for="airbag, key in [0, 1]" 
                                                    :key="key" 
                                                    :value="airbag">{{airbag | verificaBooleano}}</option>
                                            </select>
                                    </input-container-component>
                                </div>

                                <div class="col-6 mb-3">
                                    <input-container-component
                                        id="absModelo"
                                        label="ABS"
                                        help="absModeloHelp"
                                        msg-help="Tem ABS?">
                                            <select class="form-control" id="abs" aria-describedby="absModeloHelp" v-model="absModelo">
                                                <option 
                                                    v-for="abs, key in [0, 1]" 
                                                    :key="key" 
                                                    :value="abs">{{abs | verificaBooleano}}</option>
                                            </select>
                                    </input-container-component>
                                </div>

                                <div class="col-12 mb-3">
                                    <input-container-component
                                        id="novoImagem"
                                        label="Imagem"
                                        help="novoImagemHelp"
                                        msg-help="Selecione uma imagem no formato PNG">
                                            <input type="file" class="form-control-file" id="novoImagem" @change="carregarImagem($event)">
                                    </input-container-component>
                                </div>
                            </div>
                        </template>
                        <template v-slot:rodape>
                            <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        </template>
                </modal-component>
                <!-- Fim - Modal Inclusão de Modelo -->

                <!-- Ínicio - Modal Atualização de Modelo -->
                <modal-component
                    titulo="Atualizar Modelo"
                    id="modal-atualizar">
                        <template v-slot:alertas>
                            <alert-component v-if="$store.state.alerta"
                                :titulo="titulo"
                                :tipo="tipo"
                                :retorno="retorno">
                            </alert-component>
                        </template>

                        <template v-slot:conteudo>
                            <div class="row justify-content-center">
                                <div class="col-6 mb-3">
                                    <input-container-component
                                        id="atualizarNome"
                                        label="Nome"
                                        help="atualizarNomeHelp"
                                        msg-help="Informe o nome do modelo">
                                            <input type="text" class="form-control" id="nome" v-model="$store.state.item.nome" aria-describedby="atualizarNomeHelp" placeholder="Nome do Modelo">
                                    </input-container-component>
                                </div>

                                <div class="col-6 mb-3">
                                    <input-container-component
                                        id="atualizarMarca"
                                        label="Marca"
                                        help="atualizarMarcaHelp"
                                        msg-help="Informe a marca">
                                            <select class="form-control" id="marca_id" aria-describedby="atualizarMarcaHelp" v-model="$store.state.item.marca.id">
                                                <option 
                                                    v-for="marca, key in marcas.data" 
                                                    :key="key" 
                                                    :value="marca.id">{{marca.nome}}</option>
                                            </select>
                                    </input-container-component>
                                </div>

                                <div class="col-6 mb-3">
                                    <input-container-component
                                        id="atualizarPortas"
                                        label="Número de Portas"
                                        help="atualizarPortasHelp"
                                        msg-help="Quantas portas?">
                                            <select class="form-control" id="numero_portas" aria-describedby="atualizarPortasHelp" v-model="$store.state.item.numero_portas">
                                                <option 
                                                    v-for="portas, key in [2, 3, 4, 5]" 
                                                    :key="key" 
                                                    :value="portas">{{portas}}</option>
                                            </select>
                                    </input-container-component>
                                </div>

                                <div class="col-6 mb-3">
                                    <input-container-component
                                        id="atualizarLugares"
                                        label="Lugares"
                                        help="atualizarLugaresHelp"
                                        msg-help="Quantos lugares?">
                                            <select class="form-control" id="lugares" aria-describedby="atualizarLugaresHelp" v-model="$store.state.item.lugares">
                                                <option 
                                                    v-for="lugares, key in [2, 3, 4, 5, 6, 7]" 
                                                    :key="key" 
                                                    :value="lugares">{{lugares}}</option>
                                            </select>
                                    </input-container-component>
                                </div>

                                <div class="col-6 mb-3">
                                    <input-container-component
                                        id="atualizarAirbag"
                                        label="Airbag"
                                        help="atualizarAirbagHelp"
                                        msg-help="Tem airbag?">
                                            <select class="form-control" id="air_bag" aria-describedby="atualizarAirbagHelp" v-model="$store.state.item.air_bag">
                                                <option 
                                                    v-for="airbag, key in [0, 1]" 
                                                    :key="key" 
                                                    :value="airbag">{{airbag | verificaBooleano}}</option>
                                            </select>
                                    </input-container-component>
                                </div>

                                <div class="col-6 mb-3">
                                    <input-container-component
                                        id="atualizarAbs"
                                        label="ABS"
                                        help="atualizarAbsHelp"
                                        msg-help="Tem ABS?">
                                            <select class="form-control" id="abs" aria-describedby="atualizarAbsHelp" v-model="$store.state.item.abs">
                                                <option 
                                                    v-for="abs, key in [0, 1]" 
                                                    :key="key" 
                                                    :value="abs">{{abs | verificaBooleano}}</option>
                                            </select>
                                    </input-container-component>
                                </div>

                                <div class="col-12 mb-3">
                                    <input-container-component
                                        id="atualizarImagem"
                                        label="Imagem"
                                        help="atualizarImagemHelp"
                                        msg-help="Selecione uma imagem no formato PNG">
                                            <input type="file" class="form-control-file" id="atualizarImagem" @change="carregarImagem($event)">
                                    </input-container-component>
                                </div>
                            </div>
                        </template>

                        <template v-slot:rodape>
                            <button type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        </template>
                </modal-component>
                <!-- Fim - Modal Atualização de Modelo -->

                <!-- Ínicio - Modal Visualizar Modelo -->
                <modal-component
                    titulo="Visualizar Modelo"
                    id="modal-visualizar">
                        <template v-slot:alertas></template>
                        <template v-slot:conteudo>
                            <div class="form-row">
                                <div class="col-2 mb-3">
                                    <input-container-component label="ID">
                                        <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                                    </input-container-component>
                                </div>
                                <div class="col-6 mb-3">
                                    <input-container-component label="Nome">
                                        <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                                    </input-container-component>
                                </div>
                                <div class="col-4 mb-3">
                                    <input-container-component label="Marca">
                                        <input type="text" class="form-control" :value="$store.state.item.marca_id" disabled>
                                    </input-container-component>
                                </div><div class="col-3 mb-3">
                                    <input-container-component label="Portas">
                                        <input type="text" class="form-control" :value="$store.state.item.numero_portas" disabled>
                                    </input-container-component>
                                </div>
                                <div class="col-3 mb-3">
                                    <input-container-component label="Lugares">
                                        <input type="text" class="form-control" :value="$store.state.item.lugares" disabled>
                                    </input-container-component>
                                </div>
                                <div class="col-3 mb-3">
                                    <input-container-component label="Airbag">
                                        <input type="text" class="form-control" :value="$store.state.item.air_bag | verificaBooleano" disabled>
                                    </input-container-component>
                                </div>
                                <div class="col-3 mb-3">
                                    <input-container-component label="ABS">
                                        <input type="text" class="form-control" :value="$store.state.item.abs | verificaBooleano" disabled>
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
                <!-- Fim - Modal Visualizar Modelo -->

                <!-- Ínicio - Modal Excluir Modelo -->
                <modal-component
                    titulo="Excluir Modelo"
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
                <!-- Fim - Modal Excluir Modelo -->

            </div>
        </div>       
    </div>
</template>
<script>
    export default {
        data(){
            return{
                urlBase: 'http://localhost:8000/api/v1/carro',
                urlModelos: 'http://localhost:8000/api/v1/modelo?all=1',
                urlMarcas: 'http://localhost:8000/api/v1/marca?all=1',
                urlPaginacao: '',
                urlFiltro: '',
                nomeModelo: '',
                marcaModelo: 0,
                portasModelo: 2,
                lugaresModelo: 2,
                airbagModelo: 0,
                absModelo: 0,
                arquivoImagem: [],
                retorno: false,
                carros: { data : [] },
                marcas: { data : [] },
                busca: {id: '', nome: '', marca: ''}
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
                        console.log(response);
                        return;
                        this.carros = response.data;
                    })
                    .catch(errors => {
                        console.log(errors);
                    }
                );
            },
            listarMarcas(){
                let url = this.urlMarcas;

                axios.get(url)
                    .then(response => {
                        this.marcas = response;
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

                formData.append('nome', this.nomeModelo);
                formData.append('marca_id', this.marcaModelo);
                formData.append('numero_portas', this.portasModelo);
                formData.append('lugares', this.lugaresModelo);
                formData.append('air_bag', this.airbagModelo);
                formData.append('abs', this.absModelo);
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
                        this.titulo = 'Atenção! Erro ao tentar cadastrar o modelo.';
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
                formData.append('marca_id', this.$store.state.item.marca.id);
                formData.append('numero_portas', this.$store.state.item.numero_portas);
                formData.append('lugares', this.$store.state.item.lugares);
                formData.append('air_bag', this.$store.state.item.air_bag);
                formData.append('abs', this.$store.state.item.abs);
                formData.append('nomeModelo', this.$store.state.item.nomeModelo);

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
                        this.titulo = 'Atenção! Erro ao tentar atualizar o modelo.';
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
                        this.titulo = 'Atenção! Erro ao tentar excluir o modelo.';
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
            this.listarMarcas();
        }
    }
</script>

