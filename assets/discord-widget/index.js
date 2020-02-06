import Vue from 'vue';
import discordWidget from './components/discordWidget';

/**
 * @param {HTMLElement} el
 */
export default function mount(el) {
    new Vue({
        el: el,
        components: {
          discordWidget,
        },
        data() {
            const dataset = this.$options.el.dataset;
            return {
                url: dataset.url,
            };
        },
        render(createElement) {
            return createElement('discord-widget', {
                props: {
                    url: this.url,
                },
            });
        },
    });
}
