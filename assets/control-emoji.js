import $ from "jquery"

import './styles/emojionearea.min.scss';


$(function () {



   $("#myTextarea").emojioneArea({
      pickerPosition: "bottom",
      recentEmojis: true

   });

   $("#myChallengeTextarea").emojioneArea({
      pickerPosition: "bottom",
      recentEmojis: true

   });


   $("#myCommentsTextarea").emojioneArea({
      pickerPosition: "bottom",
      recentEmojis: true

   });


})