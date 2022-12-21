let searchButton = document.querySelector("#switch-search");
let shoppingButton = document.querySelector("#switch-shopping");
let userButton = document.querySelector("#switch-user");

let search = document.querySelector("#search");
let shopping = document.querySelector("#shopping");
let user = document.querySelector("#user");

searchButton.onclick = () => {
    search.classList.toggle('active');
    shopping.classList.remove('active');
    user.classList.remove('active');
}

shoppingButton.onclick = () => {
    search.classList.remove('active');
    shopping.classList.toggle('active');
    user.classList.remove('active');
}

userButton.onclick = () => {
    search.classList.remove('active');
    shopping.classList.remove('active');
    user.classList.toggle('active');
}

window.onscroll = () => {
    search.classList.remove('active');
    shopping.classList.remove('active');
    user.classList.remove('active');
}