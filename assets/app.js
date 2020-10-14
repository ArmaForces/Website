import './app.scss';

import jQuery from 'jquery';
import yall from 'yall-js';

// Init Bootstrap jQuery plugins
import 'bootstrap';
import * as modal from './modal';

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

    modal.initConfirmModals();
    modal.initMissionModals();

    // Image lazy loading
    yall();
});
