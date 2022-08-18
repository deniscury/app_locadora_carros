<template>
    <div :class="estilo" role="alert">
        <h5>{{titulo}}</h5>
        <hr/>
        <span>{{ retorno.mensagem }}</span>
        <br/>
        <ul v-if="retorno.erros">
            <li v-for="e, key in retorno.erros" :key="key" v-text="e[0]"></li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: ['tipo', 'titulo', 'retorno'],
        computed:{
            estilo(){
                return 'alert alert-'+this.tipo;
            }
        }
    }
</script>
