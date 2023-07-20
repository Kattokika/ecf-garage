import './styles/garage.css';

import { Carousel } from 'bootstrap'

// const menu = document.querySelector(".menu")
// const navLinks = document.querySelector(".nav-links")

// menu.addEventListener('click', () => {
//     navLinks.classList.toggle('mobile-menu')
// })

const myCarouselElement = document.querySelector('#carouselGarage')
if (myCarouselElement) {
    new Carousel(myCarouselElement, {
        ride: "carousel",
        interval: 10000,
        touch: false
    })
}

