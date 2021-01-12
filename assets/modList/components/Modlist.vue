<template>
    <form class="d-flex justify-content-center">
        <div v-if="isLoading" class="spinner-grow text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <template v-else>
            <ModsTable></ModsTable>
        </template>
    </form>
</template>

<script>
    import {mapState} from 'vuex';
    import ModsTable from './ModsTable';

    export default {
        name: "Modlist",
        components: {ModsTable},
        computed: {
            ...mapState(['isLoading'])
        },
    };
</script>
