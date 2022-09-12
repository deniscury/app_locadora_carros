<template>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col" v-for="t, key in titulos" :key="key">{{t.titulo}}</th>
                    <th v-if="(visualizar.exibir || atualizar.exibir || excluir.exibir)">Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="obj, key in listar" :key="key">
                    <td v-for="val, key in obj" :key="key">
                        <span v-if="titulos[key].tipo=='img'">
                            <img :src="'/storage/'+val" width="30" height="30"/>
                        </span>
                        <span v-else-if="titulos[key].tipo=='date'">
                            {{ val | formataDataHora }}
                        </span>
                        <span v-else-if="titulos[key].tipo=='array'">
                            {{ val[titulos[key].campo]}}
                        </span>
                        <span v-else>
                            {{val}}
                        </span>
                    </td>
                    <td v-if="(visualizar.exibir || atualizar.exibir || excluir.exibir)">
                        <div class="btn-group">
                            <button v-if="visualizar.exibir" class="btn btn-primary btn-sm rounded mr-2" @click="setStore(obj)" :data-toggle="visualizar.dataToggle" :data-target="visualizar.dataTarget">
                                Visualizar
                            </button>
                            <button v-if="atualizar.exibir" class="btn btn-success btn-sm rounded mr-2" @click="setStore(obj)" :data-toggle="atualizar.dataToggle" :data-target="atualizar.dataTarget">
                                Atualizar
                            </button>
                            <button v-if="excluir.exibir" class="btn btn-danger btn-sm rounded mr-2" @click="setStore(obj)" :data-toggle="excluir.dataToggle" :data-target="excluir.dataTarget">
                                Excluir
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: ['dados', 'titulos', 'visualizar', 'atualizar', 'excluir'],
        methods:{
            setStore(obj){
                this.$store.state.alerta = false; 
                this.$store.state.item = obj;
            }
        },
        computed: {
            listar(){
                let campos = Object.keys(this.titulos);
                
                let retorno = [];
                
                this.dados.map((item, chave) => {
                    let filtro = {};
                    
                    campos.forEach(campo => {
                        filtro[campo] = item[campo];
                    });

                    retorno.push(filtro);
                });

                return retorno;
            }
        }
    }
</script>
