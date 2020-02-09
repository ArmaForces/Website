<template>
    <div v-if="error">
        Error occured during widget load
    </div>
    <div v-else>
        {{ url }}
    </div>
</template>

<script>
  export default {
      name: "DiscordWidget",
      props: {
          url: {
              required: true,
          },
      },
      data() {
          return {
              error: false,
              data: [],
          };
      },
      async mounted() {
          try {
              const response = await fetch(this.url);
              if (!response.ok) {
                  this.error = true;
                  return;
              }

              this.data = await response.json();
          } catch (e) {
              this.error = true;
              throw e;
          }
      },
      methods: {
          async getGuildData() {}
      },
  };
</script>

<style scoped>

</style>
