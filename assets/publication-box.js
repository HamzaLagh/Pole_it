import './styles/publication-box.scss';
import axios from "axios";


$(function(){

  
           $('#myTextarea').on("keyup",function(){
          
                if($('#myTextarea').val() =="" || $('#myTextarea').val().length <5){
                $('#contenuHelp').css({'display':'block'});
                $('#contenuHelp').css({"border-color": "red"});
              }else{
                 $('#contenuHelp').css({'display':'none'});
                 $('#contenuHelp').css({"border-color": "green"});

              }

          })



         // traitement de la validation de la publication
         $('#submitPublication').on('click',function(e){
              e.preventDefault();
              var contenu = $('#myTextarea').val();
              var image = $('#picture__input').prop('files')[0];
              $(".pub-error").css('display','none');

              if($('#myTextarea').val() =="" && $('#picture__input').prop('files')[0] == undefined){
               
                $('#contenuImageHelp').css({'display':'block'});
                $('#contenuImageHelp').css({"border-color": "red"});
              }else{
                 $('#contenuImageHelp').css({'display':'none'});
                $('#contenuImageHelp').css({"border-color": "green"});
              }
               if($('#myTextarea').val() !="" && $('#myTextarea').val().length <5){
                $('#contenuHelp').css({'display':'block'});
                $('#contenuHelp').css({"border-color": "red"});
              }else{
                 $('#contenuHelp').css({'display':'none'});
                 $('#contenuHelp').css({"border-color": "green"});

              }
            
             
              const formData = new FormData();
              var commentId = $(".sujet").attr('topicId');

              if((contenu.length >4 ||$('#picture__input').prop('files')[0] !=undefined ) ){
                 $('#matiereHelp').css({'display':'none'});
                $('#matiereHelp').css({"border-color": "green"});
      
                if(contenu.length >4){
                  formData.append("content", contenu);
                }
                if(image !=undefined){
                  formData.append("image", image);
                }
                
                $("#submitPublication").css('display','none');
                $(".patienter").css('display','block');
                axios.post('/publication/comment/'+commentId, formData, {
                   headers: {
                      "Content-Type": "multipart/form-data",
                        }
                  
                     }).then(function(response){
                console.log(response);
                $(".picture__img ").remove("img");
                $(".delete-image-publication-icon").css('display','none');
                $("#picture__input").val('');
               var form = document.querySelector("#posts-form");
               form.reset();
               window.location.reload(); 
               Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'commentaire ajouté avec success',
                        showConfirmButton: false,
                        timer: 1500
                      })     

            }).catch(function (erreur) {
              $("#submitPublication").css('display','block');
              $(".patienter").css('display','none');
               $(".pub-error").css('display','block');
                //On traite ici les erreurs éventuellement survenues
                 console.log(erreur);
});
              }else{
                 $('#matiereHelp').css({'display':'block'});
                $('#matiereHelp').css({"border-color": "red"});
              }

         })


         // suppression de l'image par le user
         $(".delete-image-publication-icon").on('click',function(){
          console.log('button click')
          //  $("img .picture__img ").remove();
           $(".picture__img ").css('display','none');
          
           $(".delete-image-publication-icon").css('display','none');
           $("#picture__input").val('');

         })
})




// gestion de l'image

// selectedFile: File;
//   handleFileInput(event) {
//     this.selectedFile = event.target.files[0];
//     this.fichierName =event.target.files[0].name;
  
//       if ( /\.(jpg||jpeg||png)$/i.test(this.selectedFile.name)){
//         this.imagePreviewPubli = event.target.files[0];
//         this.fichierName = this.selectedFile.name;
//         this.profilUpdate();

//       }else{
//         this.toastrService.error("vous devez choisir les fichiers jpg, jpeg ou png"); 
//         return false;
       
//       }
//   }





const inputFile = document.querySelector("#picture__input");
const pictureImage = document.querySelector(".picture__image");
const pictureImageTxt = "";
pictureImage.innerHTML = pictureImageTxt;

inputFile.addEventListener("change", function (e) {
  const inputTarget = e.target;
  const file = inputTarget.files[0];

  // if ( /\.(jpg||jpeg||png)$/i.test(file.name)){
  //       this.imagePreviewPubli = event.target.files[0];
  //        this.fichierName = this.selectedFile.name;
  //        this.profilUpdate();

  //      }else{
  //        this.toastrService.error("vous devez choisir les fichiers jpg, jpeg ou png"); 
  //        return false;
       
  //      }

  if (file && /\.(jpg||jpeg||png||PNG)$/i.test(file.name )) {
    const reader = new FileReader();

    reader.addEventListener("load", function (e) {
      const readerTarget = e.target;

      const img = document.createElement("img");
      img.src = readerTarget.result;
      img.classList.add("picture__img");
     
      $(".delete-image-publication-icon").css('display','block');
      $('#autorize-files').css("display","none");

      pictureImage.innerHTML = "";
      pictureImage.appendChild(img);
    });

    reader.readAsDataURL(file);
  } else {
    $(".delete-image-publication-icon").css('display','none');
    $('#autorize-files').css("display","block");
    // pictureImage.innerHTML = pictureImageTxt;
  }
});

