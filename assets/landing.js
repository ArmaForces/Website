import './landing.scss';
import $ from 'jquery';

console.log('landing page init', window);

$(window).on("scroll", () => {
    const offset = $('.navbar').offset().top;

    $('.scrolling-navbar').toggleClass('top-nav-collapse', offset > (window.innerHeight/18));
    $('.scrolling-navbar').toggleClass('bg-dark', offset > (window.innerHeight/8));
});

$(() => {
    $('.fa-user-friends').on('click', e => {
        const el = e.target;
        let clicks = parseInt(el.dataset.egg || 0);
        if(clicks >= 5) {
            $(el).toggleClass(['fa-user-friends', 'fa-wheelchair']);
            $(el).off('click');
        } else {
            el.dataset.egg = clicks + 1
        }
    })
});
