import './styles/pages/register.scss';

$(function(){
    
var state = false;
    $('.toggle-password').on('click',function(){
        if(state ==true){
            $('.password').attr("type","password");
            $('.toggle-password').removeClass('fa-eye-slash');
          $('.toggle-password').addClass('fa-eye');
        $('.toggle-password').css('color','#7a797e');
        state = false;
    }else{
         $('.password').attr("type","text");
          $('.toggle-password').removeClass('fa-eye');
            $('.toggle-password').addClass('fa-eye-slash');
         $('.toggle-password').css('color','#5887ef');
          
           
        state = true;
    }
    })


    // calcul de l'age

    $('#registration_form_naissance_year,#registration_form_naissance_day,#registration_form_naissance_month').on('change',function(){

     // get current date detail
       
            const date = new Date();
            var c_date=date.getDate();
            var c_month=date.getMonth() + 1;
            var c_year=date.getFullYear();
            if(Number.isInteger(parseInt($('#registration_form_naissance_year').find('option:selected').val())) && Number.isInteger(parseInt($('#registration_form_naissance_month').find('option:selected').val())) && Number.isInteger(parseInt($('#registration_form_naissance_day').find('option:selected').val())))
         
            {
               
                var year = parseInt($('#registration_form_naissance_year').find('option:selected').val());
                var age = c_year - year;
                $("legend ").html("");
                  
                      if(age >19){
                       $("legend ").html(" <span class='tag-green'><b> "  +age+" ans</b></span> <span><i class='bi bi-check-lg'></i></span>");
                      }else{
                        $("legend ").html(" <span class='tag-red'><b>"  +age+" ans</b></span> <span><i class='bi bi-x-circle'></i></span>");
                    }
             
                
            }else{
               
            }

                
})



    
})