$(function(){

     document.addEventListener('turbo:submit-start', (event) => {
 const submitter = event.detail.formSubmission.submitter;
            submitter.toggleAttribute('disabled', true);
            submitter.classList.add('turbo-submit-disabled');
            document.querySelector(".loader").style.display = 'block';

       
        })


     document.addEventListener('turbo:submit-end', (event) => {
  document.querySelectorAll('.turbo-submit-disabled').forEach((button) => {
            button.toggleAttribute('disabled', false);
            button.classList.remove('turbo-submit-disabled');
             document.querySelector(".loader").style.display = 'none';
          
        });
        })



})
       
