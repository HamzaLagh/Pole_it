import { data } from 'jquery';
import './styles/pages/header.scss';
import axios from "axios";



// autocomplete

$('#recherche').on('keyup', function () {

  var query = $(this).val();
  if (query != '') {

    var url = "/find/publications";
    axios.post(url, {
      'recherche': query
    }).then(function (response) {
      console.log(response.data)
      var datas = response.data;
      $('#searchData').css('display', 'block');
      $('#searchData').html(datas.data.replace(/\*/g, ""));



    }).catch(function (error) {
      console.log(error)

    })


  }


});

$(document).on('click', '.post-reponse', function (e) {
  window.location.href = "/publication/detail/" + $(this).attr('post');

});





// search-box open close js code
let navbar = document.querySelector(".navbar");
let searchBox = document.querySelector(".search-box .bx-search");
// let searchBoxCancel = document.querySelector(".search-box .bx-x");

searchBox.addEventListener("click", () => {
  $('#searchData').html("");
  $('#recherche').val('');
  $('#searchData').css('display', 'none');

  navbar.classList.toggle("showInput");
  if (navbar.classList.contains("showInput")) {
    searchBox.classList.replace("bx-search", "bx-x");
  } else {
    searchBox.classList.replace("bx-x", "bx-search");
  }
});

// sidebar open close js code
let navLinks = document.querySelector(".nav-links");
let menuOpenBtn = document.querySelector(".navbar .bx-menu");
let menuCloseBtn = document.querySelector(".nav-links .bx-x");
menuOpenBtn.onclick = function () {
  navLinks.style.left = "0";
}
menuCloseBtn.onclick = function () {
  navLinks.style.left = "-100%";
}


// sidebar submenu open close js code
let htmlcssArrow = document.querySelector(".htmlcss-arrow");
htmlcssArrow.onclick = function () {
  navLinks.classList.toggle("show1");
}
let moreArrow = document.querySelector(".more-arrow");
moreArrow.onclick = function () {
  navLinks.classList.toggle("show2");
}
let jsArrow = document.querySelector(".js-arrow");
jsArrow.onclick = function () {
  navLinks.classList.toggle("show3");
}


document.addEventListener("click", () => {
  searchBox.classList.replace("bx-x", "bx-search");
});


