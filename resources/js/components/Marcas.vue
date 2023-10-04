<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Busca de Marcas</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 form-group">
                        <label for="inputId">ID</label>
                        <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID">
                        <small id="idHelp" class="form-text text-muted">Opcional. Informe o ID do registro</small>
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="inputNome">Nome</label>
                        <input type="text" class="form-control" id="inputNome" aria-describedby="idNome" placeholder="Nome da marca">
                        <small id="idNome" class="form-text text-muted">Opcional. Informe o nome da marca</small>
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
export default {
    mounted() {
        console.log('Component mounted.')
    }
}
</script>
