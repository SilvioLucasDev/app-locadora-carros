<template>
<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col" v-for="(titulo, key) in titulos" :key="key">
                {{ titulo.titulo }}
            </th>
            <th v-if="visualizar.visivel || atualizar.visivel | remover.visivel"></th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(obj, key) in dadosFiltrados" :key="key">
            <td v-for="(valor, chave) in obj" :key="chave">
                <span v-html="formatarValor(valor, chave)"></span>
            </td>
            <td v-if="visualizar.visivel || atualizar.visivel | remover.visivel">
                <div class="row">
                    <div v-if="visualizar.visivel" class="col-sm-12 col-md-4 d-flex justify-content-center">
                        <button class="btn btn-outline-primary me-2 mb-sm-2 mb-md-0 btn-sm" :data-bs-toggle="visualizar.dataToggle" :data-bs-target="visualizar.dataTarget" @click="setStore(obj)"><i class="fa-regular fa-eye"></i></button>
                    </div>
                    <div v-if="atualizar.visivel" class="col-sm-12 col-md-4 d-flex justify-content-center">
                        <button class="btn btn-outline-success me-2 mb-sm-2 mb-md-0 btn-sm" :data-bs-toggle="atualizar.dataToggle" :data-bs-target="atualizar.dataTarget" @click="setStore(obj)"><i class="fa-regular fa-pen-to-square"></i></button>
                    </div>
                    <div v-if="remover.visivel" class="col-sm-12 col-md-4 d-flex justify-content-center">
                        <button class="btn btn-outline-danger me-2 mb-md-0 btn-sm" :data-bs-toggle="remover.dataToggle" :data-bs-target="remover.dataTarget" @click="setStore(obj)"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>
</template>

<script>
export default {
    props: ['dados', 'titulos', 'visualizar', 'atualizar', 'remover'],
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
        setStore(obj) {
            this.$store.state.transacao.status = ''
            this.$store.state.transacao.titulo = ''
            this.$store.state.transacao.mensagem = ''
            this.$store.state.transacao.dados = ''
            this.$store.state.item = obj
        },
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
