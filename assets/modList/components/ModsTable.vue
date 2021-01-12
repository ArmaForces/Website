<template>
    <table>
        <tr v-for="mod in mods" :key="mod.id">
            <td>{{ mod.id }}</td>
            <td>{{ mod.name }}</td>
        </tr>
    </table>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        name: "ModsTable",
        computed: {
            ...mapState(['mods']),
        },
    };
</script>
