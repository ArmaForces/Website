import './app.scss';

import jQuery from 'jquery';
import yall from 'yall-js';

// Init Bootstrap jQuery plugins
import 'bootstrap';

$(() => {
    $('[data-toggle="tooltip"]').tooltip();

    // smooth scrolling for anchor links
    $('a.smooth-scroll').on('click', e => {
        e.preventDefault();

        let target = e.target.getAttribute('href');
        target = `#${target.split('#').pop()}`;

        document.querySelector(target).scrollIntoView({
            behavior: 'smooth'
        });
    });

    // Image lazy loading
    yall();
});
