import './styles/pages/add-image-post.scss';
import axios from "axios";
import Swal from 'sweetalert2/dist/sweetalert2.js'


$(function(){
   
ImgUpload();
   
    
})

function ImgUpload() {
  var imgWrap = "";
  var imgArray = [];

  $('.upload__inputfile').each(function () {
    $(this).on('change', function (e) {
      imgWrap = $(this).closest('.upload__box').find('.upload__img-wrap');
      var maxLength = $(this).attr('data-max_length');

      var files = e.target.files;
  
      var filesArr = Array.prototype?.slice.call(files);
      var iterator = 0;
      filesArr.forEach(function (f, index) {

        if (!f.type.match('image.*')) {
          return;
        }

        if (imgArray.length > maxLength) {
          return false
        } else {
          var len = 0;
          for (var i = 0; i < imgArray.length; i++) {
            if (imgArray[i] !== undefined) {
              len++;
            }
          }
          if (len > maxLength) {
            return false;
          } else {
            imgArray.push(f);

            var reader = new FileReader();
            reader.onload = function (e) {
              var html = "<div class='upload__img-box'><div style='background-image: url(" + e.target.result + ")' data-number='" + $(".upload__img-close").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close'></div></div></div>";
              imgWrap.append(html);
              iterator++;
            }
            reader.readAsDataURL(f);
          }
        }
      });
    });
  });

  $('body').on('click', ".upload__img-close", function (e) {
    var file = $(this).parent().data("file");
    for (var i = 0; i < imgArray.length; i++) {
      if (imgArray[i].name === file) {
        imgArray.splice(i, 1);
        break;
      }
    }
    $(this).parent().parent().remove();
  });


   $("#images-submit").on("click",function(e){
        e.preventDefault();
       var  id = $('#addImageModal').attr('postid');
          
        if(imgArray.length >0){
         
           $('#images-submit').css('display','none');
           $('.patienter').css('display','block');
           const formData = new FormData();
           for(var i =0; i<imgArray.length; i++){
            formData.append("images[]", imgArray[i]);
           }
          
          formData.append('postId',id);
          $('#imageHelper').css('display','none');
          axios.post('/publication/add/images/'+id, formData, {
                   headers: {
                      "Content-Type": "multipart/form-data",
                        }
                  
                     }).then(function(response){
                    
                      $('#addImageModal').modal('hide');
                      window.location.reload();
                      Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Votre image est enregistrée avec success',
                        showConfirmButton: false,
                        timer: 1500
                      })

                     }).catch(function(error){
                      
                      
                      $('#imageHelper').css('display','block');
                      $('#images-submit').css('display','block');
                      $('.patienter').css('display','none');
                     
                     })
        }
       
    })
}

