import axios from "axios";
import './styles/pages/posts.scss';
$(function(){


      $('input[type="file"]').addClass('upload__inputfile');
      $('label[for="posts_profil"]').addClass("upload__btn");
     
     
      $("textarea").css('height','9em');


      document.getElementById('myText').onkeyup = function(){
  
            var text_value = document.getElementById('myText').value;
            
            if (text_value.includes("word") === true) {
              document.getElementById('warning_text').innerHTML = "You are using the forbidden word";
            } else {
              document.getElementById('warning_text').innerHTML = "";
            }
         
         };
    

})