<template>
<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col" v-for="(titulo, key) in titulos" :key="key">
                {{ titulo.titulo }}
            </th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(obj, key) in dadosFiltrados" :key="key">
            <td v-for="(valor, chave) in obj" :key="chave">
                <span v-html="formatarValor(valor, chave)"></span>
            </td>
        </tr>
    </tbody>
</table>
</template>

<script>
export default {
    props: ["dados", "titulos"],
    computed: {
        dadosFiltrados() {
            return this.dados.map((item) => {
                const itemFiltrado = {};
                for (const campo in this.titulos) {
                    itemFiltrado[campo] = item[campo];
                }
                return itemFiltrado;
            });
        },
    },
    methods: {
        formatarValor(valor, chave) {
            const tipo = this.titulos[chave].tipo;
            if (tipo === 'text') {
                return valor;
            } else if (tipo === 'data') {
                return this.$filters.formatDate(valor);
            } else if (tipo === 'imagem') {
                return `<img src="/storage/${valor}" width="30" height="30" />`;
            }
            return this.$filters.escapeHTML(valor);
        },
    },
};
</script>
