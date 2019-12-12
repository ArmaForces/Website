import './landing.scss';
import $ from 'jquery';

console.log('landing page init', window);

$(window).on("scroll", () => {
    console.log('scroll', $('.navbar'));
    $('.scrolling-navbar').toggleClass('top-nav-collapse', $('.navbar').offset().top > 50);
});
