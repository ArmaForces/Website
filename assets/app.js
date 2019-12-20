
import './app.scss';

import jQuery from 'jquery';
// Init Bootstrap jQuery plugins
import 'bootstrap';

$(() => {
    $('[data-toggle="tooltip"]').tooltip();

    // smooth scrolling for anchor links
    $('a.smooth-scroll').on('click', e => {
        console.log('smooth scrolling', e);
        e.preventDefault();

        let target = e.target.getAttribute('href');
        target = `#${target.split('#').pop()}`;

        document.querySelector(target).scrollIntoView({
            behavior: 'smooth'
        });
    });

});
