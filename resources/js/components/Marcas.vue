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
                                <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID">
                            </input-container-component>
                        </div>

                        <div class="col-lg-6">
                            <input-container-component titulo="Nome da marca" id="inputNome" id-help="nomeHelp" texto-ajuda="Opcional. Informe o nome da marca">
                                <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Nome da marca">
                            </input-container-component>
                        </div>
                    </div>
                </template>

                <template v-slot:rodape>
                    <button type="submit" class="btn btn-primary btn-sm float-end">Pesquisar</button>
                </template>
            </card-component>
            <!-- Fim card de busca -->

            <!-- Card de listagem -->
            <card-component titulo="Relação de Marcas">
                <template v-slot:conteudo>
                    <table-component :dados="marcas.data" :titulos="{
                        id: { titulo: 'ID', tipo: 'text' },
                        nome: { titulo: 'Nome', tipo: 'text' },
                        imagem: { titulo: 'Imagem', tipo: 'imagem' },
                        created_at: { titulo: 'Data de criação', tipo: 'data' },
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
                            <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#modalMarca">Adicionar</button>
                        </div>
                    </div>
                </template>
            </card-component>
            <!-- Fim card de listagem -->
        </div>

        <!-- Modal -->
        <modal-component id="modalMarca" titulo="Adicionar Marca">
            <template v-slot:alertas>
                <alert-component :tipo="tipo" :detalhes="transacaoDetalhes" :titulo="titulo" v-if="tipo !== ''"></alert-component>
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
        <!-- Fim Modal -->
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
            nomeMarca: '',
            arquivoImagem: [],
            transacaoStatus: '',
            transacaoDetalhes: {},
            titulo: '',
            tipo: '',
            marcas: {
                data: []
            }
        }
    },
    methods: {
        paginacao(link) {
            if (link.url) {
                this.urlBase = link.url
                this.carregarLista()
            }
        },
        carregarLista() {
            let config = {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': this.token
                }
            }

            axios.get(this.urlBase, config)
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
                    this.tipo = 'success'
                    this.titulo = 'Marca cadastrada com sucesso'
                    this.transacaoDetalhes = {
                        mensagem: 'ID do registro: ' + response.data.id
                    }
                })
                .catch(errors => {
                    this.tipo = 'danger'
                    this.titulo = 'Erro ao tentar cadastrar a marca'
                    this.transacaoDetalhes = {
                        dados: errors.response.data.errors
                    }
                })
        }
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
