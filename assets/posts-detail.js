import './styles/pages/posts-detail.scss';
import axios from "axios";
import Swal from 'sweetalert2/dist/sweetalert2.js'


$(function () {

$( "#myTextarea" ).on( "keyup", function() {
  alert( "Handler for `keyup` called." );
} );

  $('textarea').on("keypress",function(){
    console.log("bonjjjj");
    alert("bonjoe")
    checkMentionUser();
  });
    

  function checkMentionUser(){
    alert("@nn")
    var text_value = $('#myTextarea').val();
    if (text_value.includes("@") === true){
        alert("@")
    }
    
  }


  function urlifyFn(text) {
    let urlRegex = /(https?:\/\/[^\s]+)/g;
    return text.replace(urlRegex, function (url) {
      return "<a href='" + url + "' target=_blank>" + url + "</a>";
    })

  }



  let text = document.querySelector("#pub-content").innerHTML;
  let html = urlifyFn(text);
  document.getElementById("pub-content").innerHTML = html;


  document.querySelectorAll(".commentaire").forEach(function (element) {
    console.log(element)
    element.innerHTML = urlifyFn(element.innerHTML);
  })





  // var id = window.location.pathname.split('/')[6];

  var topicId = $(".sujet").attr('topicId');
  axios({
    method: 'get',
    url: '/annonces/vue/' + topicId
  })
    .then(function (response) {
    });



  // marquer le sujet comme lu
  $('.close-forum > a').on('click', function (e) {
    var topicId = $(".sujet").attr('topicId');
    e.preventDefault();
    axios({
      method: 'get',
      url: '/publication/close/' + topicId
    })
      .then(function (response) {

        window.location.reload();
        Swal.fire({
          position: 'top-end',
          icon: 'success',
          title: 'Votre publication est fermée définitivement',
          showConfirmButton: false,
          timer: 1500
        })
      });




  });

  var topicId = $(".topic").attr('topicId');
  $('.delete-post > a').on('click', function (e) {
    e.preventDefault();
    var href = "/publication/close/";
    var id = $(this).attr('id');
    $('#confirmationDeletePost').attr('post', href);
    $('#confirmationDeletePost').attr('ids', id);
    $('#confirmationDeletePost').attr('postid', $(this).attr('post'));
    $('#confirmationDeletePost').modal('show');


  });


  // click sur le boutton supprimer une annonce
  $('.delete-topic > a').on('click', function (e) {
    e.preventDefault();
    $('#confirmationDeletePost').modal('show');


  });




  // click sur le boutton annuler la suppression d'une annonce
  $('.clearfix > #btnNo').on('click', function () {
    $('#confirmationDeletePost').modal('hide');
  });

  // confirmation et suppréssion définitive
  $('.clearfix > #btnYes').on('click', function () {
    var topicId = $(".sujet").attr('topicId');
    var url = "/publication/delete/" + topicId;
    $('#confirmationDeletePost').modal('hide');
    axios.get(url).then(function (response) {
      console.log(response);
      console.log(response.data.route);
      window.location.replace(response.data.route);
      Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Votre annonce est supprimée',
        showConfirmButton: false,
        timer: 1500
      })

    }).catch(function (error) {
      console.log(error)

    })

  });



})
