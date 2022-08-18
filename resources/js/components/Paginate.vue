<template>
    <nav aria-label="Page navigation example">
        <ul class="pagination" style="cursor:pointer">
            <slot></slot>
        </ul>
    </nav>
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


