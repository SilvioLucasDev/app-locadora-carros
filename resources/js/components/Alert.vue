<template>
<div :class="estilo" role="alert">
    {{ titulo }}
    <hr>
    <span v-if="detalhes.mensagem">{{ detalhes.mensagem }}</span>
    <ul v-if="detalhes.dados">
        <li v-for="error, key in detalhes.dados" :key="key">{{ error[0] }}</li>
    </ul>
</div>
</template>

<script>
export default {
    props: ['tipo', 'titulo', 'detalhes'],
    computed: {
        estilo() {
            return 'alert alert-' + this.tipo
        }
    }
}
</script>
