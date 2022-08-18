<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

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
                                            <input type="number" class="form-control" id="id" aria-describedby="idHelp" placeholder="ID">
                                    </input-container-component>
                                </div>
                                <div class="col mb-3">
                                    <input-container-component
                                        id="nome"
                                        label="Nome"
                                        help="nomeHelp"
                                        msg-help="Informe o nome da marca">
                                            <input type="text" class="form-control" id="nome" aria-describedby="nomeHelp" placeholder="Nome">
                                    </input-container-component>
                                </div>
                            </div>
                        </template>
                        <template v-slot:rodape>
                            <button type="submit" class="btn btn-primary btn-sm float-right">Buscar</button>
                        </template>
                </card-component>              
                <!-- Fim - Form de busca -->

                <!-- Ínicio - Tabela -->
                <card-component
                    titulo="Lista de Marcas">
                        <template v-slot:conteudo>
                            <table-component
                                :dados="marcas"
                                :titulos="{
                                    id : {titulo : 'ID', tipo : 'text'},
                                    nome : {titulo : 'Nome', tipo : 'text'},
                                    imagem : {titulo : 'Imagem', tipo : 'img'},
                                    created_at :{titulo : 'Data de Criação', tipo : 'date'},
                                    updated_at :{titulo : 'Data de Alteração', tipo : 'date'},
                                }"></table-component>
                        </template>
                        <template v-slot:rodape>
                            <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modal-marca">Adicionar</button>
                        </template>
                </card-component>  
                <!-- Fim - Tabela -->

                <!-- Ínicio - Modal -->
                <modal-component
                    titulo="Adicionar Marca"
                    id="modal-marca">
                        <template v-slot:alertas>
                            <alert-component v-if="alerta==true"
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
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
                        </template>
                </modal-component>
                <!-- Fim - Modal -->

            </div>
        </div>       
    </div>
</template>
<script>
    export default {
        data(){
            return{
                urlBase: 'http://localhost:8000/api/v1/marca',
                nomeMarca: '',
                arquivoImagem: [],
                alerta: false,
                retorno: false,
                marcas: []
            }
        },
        methods:{
            carregarImagem(e){
                this.arquivoImagem = e.target.files;
            },
            listar(){
                let config = {
                    headers : {
                        'Accept' : 'application/json',
                        'Authorization' : this.token     
                    }
                }

                axios.get(this.urlBase, config)
                    .then(response => {
                        this.marcas = response.data;
                    })
                    .catch(errors => {
                        console.log(errors);
                    }
                );
            },
            salvar(){
                let formData = new FormData();

                formData.append('nome', this.nomeMarca);
                formData.append('imagem', this.arquivoImagem[0]);

                let config = {
                    headers : {
                        'Content-Type' : 'multipart/form-data',
                        'Accept' : 'application/json',
                        'Authorization' : this.token     
                    }
                }

                axios.post(this.urlBase, formData, config)
                    .then(response => {
                        this.titulo = 'Cadastro realizado com sucesso!';
                        this.tipo = 'success';
                        this.retorno = {
                            mensagem: 'ID Cadastrado: '+response.data.id
                        };
                        this.alerta = true;
                    })
                    .catch(errors => {
                        this.titulo = 'Atenção! Erro ao tentar cadastrar a marca.';
                        this.tipo = 'danger';
                        this.retorno = {
                            mensagem: errors.response.data.message,
                            erros: errors.response.data.errors
                        };
                        this.alerta = true;
                    }
                );
            }
        },
        computed: {
            token(){
                let token = document.cookie.split(';').find(
                    indice => {
                        return indice.trim().startsWith('token=');
                    }
                );

                token = token.replace('token=', 'Bearer ');

                return token;
            }
        },
        mounted() {
            this.listar();
        }
    }
</script>

