const icone = document.querySelector("#icone");
const nom = document.querySelector("#nom");
const emailExpediteur = document.querySelector("#emailExpediteur");
const emailDestinataire = document.querySelector("#emailDestinataire");
const message = document.querySelector("#message");
const expressionReguliere = /^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,6}$/;
const btnSubmit = document.querySelector("#btnSubmit");

btnSubmit.addEventListener("click", function (e) {
    e.preventDefault();
    if (icone.value === "") {
        window.alert("Veuillez ajouter une image !");
    } else if (nom.value === "" || emailExpediteur.value === "" || emailDestinataire.value === "" || message.value === "") { 
        window.alert("Veuillez compléter tous les champs !");
    } else if (!expressionReguliere.test(emailExpediteur.value) || !expressionReguliere.test(emailDestinataire.value)) {
        window.alert("Veuillez entrer une adresse email valide !");
    }
});

