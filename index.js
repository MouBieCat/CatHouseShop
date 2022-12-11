let searchButton = document.querySelector("#switch-search");
let shoppingButton = document.querySelector("#switch-shopping");

let search = document.querySelector("#search");
let shopping = document.querySelector("#shopping");

searchButton.onclick = () => {
    search.classList.toggle('active');
    shopping.classList.remove('active');
}

shoppingButton.onclick = () => {
    search.classList.remove('active');
    shopping.classList.toggle('active');
}

window.onscroll = () => {
    search.classList.remove('active');
    shopping.classList.remove('active');
}