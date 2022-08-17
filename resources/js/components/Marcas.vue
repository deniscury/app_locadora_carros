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
                                        id="inputId"
                                        label="ID"
                                        help="idHelp"
                                        msg-help="Informe o ID da marca">
                                            <input type="number" class="form-control" id="id" aria-describedby="idHelp" placeholder="ID">
                                    </input-container-component>
                                </div>
                                <div class="col mb-3">
                                    <input-container-component
                                        id="inputNome"
                                        label="Nome"
                                        type="text"
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
                            <table-component></table-component>
                        </template>
                        <template v-slot:rodape>
                            <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#exampleModal">Adicionar</button>
                        </template>
                </card-component>  
                <!-- Fim - Tabela -->

                
            </div>
        </div>       
    </div>
</template>
<script>
</script>

