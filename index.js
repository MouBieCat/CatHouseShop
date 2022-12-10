let shoppingButton = document.querySelector("#switch-shopping");

let shopping = document.querySelector("#shopping");

shoppingButton.onclick = () => {
    shopping.classList.toggle('active');
}

window.onscroll = () => {
    shopping.classList.remove('active');
}