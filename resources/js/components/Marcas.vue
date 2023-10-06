<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Card de busca -->
            <card-component titulo="Busca de Marcas">
                <template v-slot:conteudo>
                    <div class="row">
                        <div class="col-lg-6">
                            <input-container-component titulo="ID da marca" id="inputId" id-help="idHelp" texto-ajuda="Opcional. Informe o ID da marca">
                                <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID" v-model="busca.id">
                            </input-container-component>
                        </div>

                        <div class="col-lg-6">
                            <input-container-component titulo="Nome da marca" id="inputNome" id-help="nomeHelp" texto-ajuda="Opcional. Informe o nome da marca">
                                <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Nome da marca" v-model="busca.nome">
                            </input-container-component>
                        </div>
                    </div>
                </template>

                <template v-slot:rodape>
                    <button type="submit" class="btn btn-primary btn-sm float-end" @click="pesquisar()">Pesquisar</button>
                </template>
            </card-component>
            <!-- Fim card de busca -->

            <!-- Card de listagem -->
            <card-component titulo="Relação de Marcas">
                <template v-slot:conteudo>
                    <table-component :dados="marcas.data" :visualizar="{ visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaVisualizar'}" :atualizar="{ visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaAtualizar'}" :remover="{ visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaRemover'}" :titulos="{
                        id: { titulo: 'ID', tipo: 'text' },
                        nome: { titulo: 'Nome', tipo: 'text' },
                        imagem: { titulo: 'Imagem', tipo: 'imagem' },
                        created_at: { titulo: 'Data de Criação', tipo: 'data' },
                    }"></table-component>
                </template>

                <template v-slot:rodape>
                    <div class="row">
                        <div class="col-10">
                            <paginate-component>
                                <li v-for="(link, key) in marcas.links" :key="key" :class="link.active ? 'page-item active': 'page-item'" @click="paginacao(link)">
                                    <a class="page-link" v-html="link.label"></a>
                                </li>
                            </paginate-component>
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#modalMarca" @click="clearStore()">Adicionar</button>
                        </div>
                    </div>
                </template>
            </card-component>
            <!-- Fim card de listagem -->
        </div>

        <!-- Início modal inclusão de marca -->
        <modal-component id="modalMarca" titulo="Adicionar Marca">
            <template v-slot:alertas>
                <alert-component :tipo="this.$store.state.transacao.status" :detalhes="this.$store.state.transacao" :titulo="this.$store.state.transacao.titulo" v-if="this.$store.state.transacao.status !== ''"></alert-component>
            </template>

            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Nome da marca" id="novoNome" id-help="novoNomeHelp" texto-ajuda="Informe o nome da marca">
                        <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp" placeholder="Nome da marca" v-model="nomeMarca">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Imagem" id="novoImagem" id-help="novoImagemHelp" texto-ajuda="Selecione uma imagem no formato PNG">
                        <input type="file" class="form-control" id="novoImagem" aria-describedby="novoImagemHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>
        </modal-component>
        <!-- Fim modal inclusão de marca -->

        <!-- Início modal visualização de marca -->
        <modal-component id="modalMarcaVisualizar" titulo="Visualizar Marca">
            <template v-slot:conteudo>
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>

                <input-container-component titulo="Nome da marca">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>

                <input-container-component titulo="Imagem">
                    <img v-if="$store.state.item.imagem" :src="'storage/'+$store.state.item.imagem" />
                </input-container-component>

                <input-container-component titulo="Data de Criação">
                    <input type="text" class="form-control" :value="$filters.formatDate($store.state.item.created_at)" disabled>
                </input-container-component>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </template>
        </modal-component>
        <!-- Fim modal visualização de marca -->

        <!-- Início modal atualização de marca -->
        <modal-component id="modalMarcaAtualizar" titulo="Atualizar Marca">
            <template v-slot:alertas>
                <alert-component :tipo="this.$store.state.transacao.status" :detalhes="this.$store.state.transacao" :titulo="this.$store.state.transacao.titulo" v-if="this.$store.state.transacao.status !== ''"></alert-component>
            </template>

            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Nome da marca" id="atualizarNome" id-help="atualizarNomeHelp" texto-ajuda="Informe o nome da marca">
                        <input type="text" class="form-control" id="atualizarNome" aria-describedby="atualizarNomeHelp" placeholder="Nome da marca" v-model="$store.state.item.nome">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Imagem" id="atualizarImagem" id-help="atualizarImagemHelp" texto-ajuda="Selecione uma imagem no formato PNG">
                        <input type="file" class="form-control" id="atualizarImagem" aria-describedby="atualizarImagemHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
            </template>
        </modal-component>
        <!-- Fim modal atualização de marca -->

        <!-- Início modal remoção de marca -->
        <modal-component id="modalMarcaRemover" titulo="Remover Marca">
            <template v-slot:alertas>
                <alert-component :tipo="this.$store.state.transacao.status" :detalhes="this.$store.state.transacao" :titulo="this.$store.state.transacao.titulo" v-if="this.$store.state.transacao.status !== ''"></alert-component>
            </template>

            <template v-slot:conteudo v-if="$store.state.transacao.status != 'success'">
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>

                <input-container-component titulo="Nome da marca">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remover()" v-if="this.$store.state.transacao.status != 'success'">Remover</button>
            </template>
        </modal-component>
        <!-- Fim modal remoção de marca -->
    </div>
</div>
</template>

<script>
export default {
    mounted() {
        this.carregarLista()
    },
    data() {
        return {
            urlBase: 'http://localhost:8000/api/v1/marca',
            urlFiltro: '',
            urlPaginacao: '',
            nomeMarca: '',
            arquivoImagem: [],
            marcas: {
                data: []
            },
            busca: {
                id: '',
                nome: ''
            }
        }
    },
    methods: {
        clearStore() {
            this.$store.state.transacao.status = ''
            this.$store.state.transacao.titulo = ''
            this.$store.state.transacao.mensagem = ''
        },
        pesquisar() {
            let filtro = ''
            for (let chave in this.busca) {
                if (this.busca[chave]) {
                    if (filtro != '') filtro += ';'
                    filtro += chave + ':like:%' + this.busca[chave] + '%'
                }
            }
            if (filtro != '') {
                this.urlPaginacao = 'page=1'
                this.urlFiltro = '&filtro=' + filtro
            }
            this.carregarLista()
            this.urlFiltro = ''
        },
        paginacao(link) {
            if (link.url) {
                this.urlPaginacao = link.url.split('?')[1]
                this.carregarLista()
            }
        },
        carregarLista() {
            let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro
            let config = {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': this.token
                }
            }

            axios.get(url, config)
                .then(response => {
                    this.marcas = response.data
                })
                .catch(errors => {
                    console.log(errors)
                })
        },
        carregarImagem(e) {
            this.arquivoImagem = e.target.files
        },
        salvar() {
            let formData = new FormData();
            formData.append('nome', this.nomeMarca)
            formData.append('imagem', this.arquivoImagem[0])

            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Accept': 'application/json',
                    'Authorization': this.token
                }
            }

            axios.post(this.urlBase, formData, config)
                .then(response => {
                    this.$store.state.transacao.status = 'success'
                    this.$store.state.transacao.titulo = 'Marca cadastrada com sucesso'
                    this.$store.state.transacao.mensagem = 'ID do registro: ' + response.data.id
                    this.carregarLista()
                })
                .catch(errors => {
                    this.$store.state.transacao.status = 'danger'
                    this.$store.state.transacao.titulo = 'Erro ao tentar cadastrar a marca'
                    this.$store.state.transacao.mensagem = errors.response.data.erro
                    this.$store.state.transacao.dados = errors.response.data.errors
                })
        },
        atualizar() {
            let formData = new FormData();
            formData.append('_method', 'patch')
            formData.append('nome', this.$store.state.item.nome)
            if (this.arquivoImagem[0]) formData.append('imagem', this.arquivoImagem[0])

            let url = this.urlBase + '/' + this.$store.state.item.id

            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Accept': 'application/json',
                    'Authorization': this.token
                }
            }

            axios.post(url, formData, config)
                .then(response => {
                    this.$store.state.transacao.status = 'success'
                    this.$store.state.transacao.titulo = 'Marca atualizada com sucesso'
                    atualizarImagem.value = ''
                    this.carregarLista()
                })
                .catch(errors => {
                    console.log(errors.response)
                    this.$store.state.transacao.status = 'danger'
                    this.$store.state.transacao.titulo = 'Erro ao tentar atualizar a marca'
                    this.$store.state.transacao.mensagem = errors.response.data.erro
                    this.$store.state.transacao.dados = errors.response.data.errors
                })
        },
        remover() {
            let url = this.urlBase + '/' + this.$store.state.item.id

            let config = {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': this.token
                }
            }

            axios.delete(url, config)
                .then(response => {
                    this.$store.state.transacao.status = 'success'
                    this.$store.state.transacao.titulo = 'Transação realizada com sucesso'
                    this.$store.state.transacao.mensagem = response.data.msg
                    this.carregarLista()
                })
                .catch(errors => {
                    this.$store.state.transacao.status = 'danger'
                    this.$store.state.transacao.titulo = 'Erro na transação'
                    this.$store.state.transacao.mensagem = errors.response.data.erro
                })
        },
    },
    computed: {
        token() {
            let token = document.cookie.split(';').find(indice => {
                return indice.includes('token=')
            })
            token = token.split('=')[1]
            token = 'Bearer ' + token
            return token
        }
    }
}
</script>
