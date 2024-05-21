import axios from "axios";
import './styles/pages/contact.scss';
import Swal from 'sweetalert2/dist/sweetalert2.js';
$(function () {



    // values datas

    const username = document.querySelector("#nom")
    const prenom = document.querySelector("#prenom")
    const submit = document.querySelector("#contact-me");
    const message = document.querySelector("#contenu")
    const phone = document.querySelector("#phone")
    const email = document.querySelector("#email")
    const form = document.querySelector("#form");
    const adresse = document.querySelector("#adresse");
    const url = document.querySelector("#url");



    // helpers
    const usernameHelper = document.querySelector(".nom-warning")
    const messageHelper = document.querySelector(".message-warning")
    const emailHelper = document.querySelector(".email-warning")
    const phoneHelper = document.querySelector(".phone-warning")
    const adresseHelper = document.querySelector(".adresse-warning")
    const prenomHelper = document.querySelector(".prenom-warning")
    var firtCharacterUsername = username.value.charAt(0);
    var firtCharacterPrenom = prenom.value.charAt(0);
    var patern = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

    // Vérification du username dès que l'utilisateur écrit un caractère
    function usernameValidation() {

        username.addEventListener("keyup", function (e) {

            if (isNaN(firtCharacterUsername)) {
                usernameHelper.style.display = "none"
            } else {
                usernameHelper.style.display = "block"
            }

            if (username.value.length < 4) {
                usernameHelper.style.display = "block"
            } else {
                usernameHelper.style.display = "none"
            }
        })
    }


    function prenomValidation() {

        prenom.addEventListener("keyup", function (e) {

            if (isNaN(firtCharacterPrenom)) {
                prenomHelper.style.display = "none"
            } else {
                prenomHelper.style.display = "block"
            }

            if (prenom.value.length < 4) {
                prenomHelper.style.display = "block"
            } else {
                prenomHelper.style.display = "none"
            }
        })
    }

    // Vérification de l'email dès que l'utilisateur écrit un caractère
    function emailValidation() {
        email.addEventListener("keyup", function () {

            if (!patern.test(email.value)) {
                emailHelper.style.display = "block"
            }
            else {
                emailHelper.style.display = "none"
            }
        })

    }

    function adresseValidation() {

        adresse.addEventListener("keyup", function (e) {



            if (adresse.value.length < 4) {
                adresseHelper.style.display = "block"
            } else {
                adresseHelper.style.display = "none"
            }
        })
    }

    // Vérification du numero de telephone dès que l'utilisateur écrit un caractère
    function phoneValidation() {
        phone.addEventListener("keyup", function () {
            if (phone.value.length < 5) {
                phoneHelper.style.display = "block"

            } else {
                phoneHelper.style.display = "none"
            }
        })
    }

    // Vérification du message dès que l'utilisateur écrit un caractère
    function messageValidation() {
        message.addEventListener("keyup", function () {
            if (message.value.length < 8) {
                messageHelper.style.display = "block"
            } else {
                messageHelper.style.display = "none"
            }
        })
    }

    usernameValidation();
    emailValidation();
    phoneValidation();
    messageValidation();
    prenomValidation();
    adresseValidation();


    // click sur le boutton soumission du formulaire
    submit.addEventListener("click", function (e) {
        e.preventDefault();
        if (username.value.length >= 4 && isNaN(firtCharacterUsername) == false && prenom.value.length >= 4 && isNaN(firtCharacterPrenom) == false && patern.test(email.value) && adresse.value.length >= 5 && phone.value.length >= 5 && message.value.length >= 8) {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Merci de nous avoir contacté',
                showConfirmButton: false,
                timer: 1500
            })
            form.reset();

        } else {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Vous devez remplir le formulaire avant de soumettre',
                showConfirmButton: false,
                timer: 1500
            })
        }
    })






})



