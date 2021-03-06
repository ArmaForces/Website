import './app.scss';

import jQuery from 'jquery';
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

    form.initConfirmModals();

    // Image lazy loading
    yall();
});
