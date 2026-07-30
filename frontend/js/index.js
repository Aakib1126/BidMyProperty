renderNav('navContainer');

const bgImages = [
    '../assets/images/bc3.jpg',
    '../assets/images/bc.jpg',
    '../assets/images/bc2.jpg'
];
let bgIndex = 0;
function changeBackground() {
    document.querySelector('.main-content').style.backgroundImage = `url('${bgImages[bgIndex]}')`;
    bgIndex = (bgIndex + 1) % bgImages.length;
}
setInterval(changeBackground, 3000);
window.addEventListener('load', changeBackground);
