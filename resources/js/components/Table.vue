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
        <tr v-for="obj, chave in dadosFiltrados" :key="chave">
            <td v-for="valor, chaveValor in obj" :key="chaveValor">
                <span v-if="(tipo = titulos[chaveValor].tipo) === 'text'">{{ valor }}</span>
                <span v-else-if="tipo === 'data'">{{ $filters.formatDate(valor) }}</span>
                <img v-else-if="tipo === 'imagem'" :src="'/storage/' + valor" width="30" height="30" />
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
            let campos = Object.keys(this.titulos)
            let dadosFiltrados = []

            this.dados.map((item, chave) => {
                let itemFiltrado = {}

                campos.forEach(campo => {
                    itemFiltrado[campo] = item[campo]
                })
                dadosFiltrados.push(itemFiltrado)
            })
            return dadosFiltrados
        }
    }
};
</script>
