import './landing.scss';
import $ from 'jquery';

console.log('landing page init', window);

$(window).on("scroll", () => {
    const offset = $('.navbar').offset().top;

    $('.scrolling-navbar').toggleClass('top-nav-collapse', offset > (window.innerHeight/18));
    $('.scrolling-navbar').toggleClass('bg-dark', offset > (window.innerHeight/8));
});
