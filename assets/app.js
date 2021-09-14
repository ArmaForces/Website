import './app.scss';
import yall from 'yall-js';

// Init Bootstrap jQuery plugins
import 'bootstrap';
import * as form from './form';

$(() => {
    $('[data-toggle="tooltip"]').tooltip({
        boundary: 'window'
    });

    // smooth scrolling for anchor links
    $('a.smooth-scroll').on('click', e => {
        e.preventDefault();

        let target = e.target.getAttribute('href');
        target = `#${target.split('#').pop()}`;

        document.querySelector(target).scrollIntoView({
            behavior: 'smooth'
        });
    });

    $('[data-row-action-url]').on('click', e => {
        if (e.target.nodeName === 'A') return;
        window.location = e.delegateTarget.dataset['rowActionUrl'];
    });

    $('[data-row-action-checkbox]').on('click', e => {
        if (e.target.nodeName === 'A') return;
        let $checkbox = $(e.delegateTarget).find('[type=checkbox]');
        $checkbox.prop('checked', !$checkbox.prop('checked')).trigger('change');
    });

    form.initConfirmModals();

    // Image lazy loading
    yall();
});
