<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card mb-3">
                <div class="card-header">Busca de Marcas</div>

                <div class="card-body">
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
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-sm float-end">Pesquisar</button>
                </div>
            </div>

        </div>
    </div>
</div>
</template>

<script>
import InputContainer from './InputContainer.vue'
export default {
  components: { InputContainer },
    mounted() {
        console.log('Component mounted.')
    }
}
</script>
