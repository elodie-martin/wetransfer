//Récupération des l'url
let url = window.location.pathname;

//Test de la page en cours
if (url === '/home') {
    //vérification de la page upload

    const icone = document.querySelector("#icone");
    const nom = document.querySelector("#nom");
    const emailExpediteur = document.querySelector("#emailExpediteur");
    const emailDestinataire = document.querySelector("#emailDestinataire");
    const message = document.querySelector("#message");
    const expressionReguliere = /^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,6}$/;
    const btnSubmit = document.querySelector("#btnSubmit");

    btnSubmit.addEventListener("click", function (e) {
        if (icone.value === "") {
            e.preventDefault();
            window.alert("Veuillez ajouter une image !");
        } else if (nom.value === "" || emailExpediteur.value === "" || emailDestinataire.value === "" || message.value === "") {
            e.preventDefault();
            window.alert("Veuillez compléter tous les champs !");
        } else if (!expressionReguliere.test(emailExpediteur.value) || !expressionReguliere.test(emailDestinataire.value)) {
            e.preventDefault();
            window.alert("Veuillez entrer une adresse email valide !");
        }
    });
} else if (url === '/dashboard') {
    //vérification de la page login
    const identifiant = document.querySelector("#identifiant");
    const password = document.querySelector("#password")
    const btnLogin = document.querySelector("#btnLogin");

    btnLogin.addEventListener("click", function (e) {
        if (identifiant.value === "" || password.value === "") {
            e.preventDefault();
            window.alert("Veuillez remplir tous les champs");
        }
    });
}

const footer = document.querySelector("footer");
const footerCache = document.querySelector(".footer-cache");
const btnClose = document.querySelector(".button-close");

footer.addEventListener("click", function(e) {
    footerCache.style.display = "block";
});

btnClose.addEventListener("click", function(e) {
    footerCache.style.display = "none";
});
