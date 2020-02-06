<template>
    <div>
        {{ url }}
    </div>
</template>

<script>
  export default {
      name: "discordWidget",
      props: {
          url: {
              required: true,
          },
      },
      mounted() {
          console.log('discord widget', this.url);
      }
  };
</script>

<style scoped>

</style>
