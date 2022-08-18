<template>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col" v-for="t, key in titulos" :key="key">{{t.titulo}}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="obj, key in listar" :key="key">
                <td v-for="val, key in obj" :key="key">
                    <span v-if="titulos[key].tipo=='img'">
                        <img :src="'/storage/'+val" width="30" height="30"/>
                    </span>
                    <span v-if="titulos[key].tipo=='date'">
                        --- {{ val }} ---
                    </span>
                    <span v-else>
                        {{val}}
                    </span>
                </td>
            </tr>

                <!--
            <tr v-for="obj in dados" :key="obj.id">
                <td v-if="titulos.includes(key)" v-for="val, key in obj" :key="key">
                </td>
                <th scope="row">{{d.id}}</th>
                <td>{{d.nome}}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>-->
        </tbody>
    </table>
</template>

<script>
    export default {
        props: ['dados', 'titulos'],
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
