import './styles/pages/home.scss';




$(function () {


    $(".slider").owlCarousel({
        
        loop: true,
        autoplay: true,
        autoplayTimeout: 2000, //2000ms = 2s;
        autoplayHoverPause: true,
      });





    $(".search").keyup(function () {

        var searchTerm = $(".search").val();
        var listItem = $('.results tbody').children('tr');
        var searchSplit = searchTerm.replace(/ /g, "'):containsi('")

        $.extend($.expr[':'], {
            'containsi': function (elem, i, match, array) {
                return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
            }
        });

        $(".results tbody tr").not(":containsi('" + searchSplit + "')").each(function (e) {
            $(this).attr('visible', 'false');
        });

        $(".results tbody tr:containsi('" + searchSplit + "')").each(function (e) {
            $(this).attr('visible', 'true');
        });

        var jobCount = $('.results tbody tr[visible="true"]').length;
        //  $('.counter').text(jobCount + ' item');

        if (jobCount == '0') { $('.no-result').show(); }
        else { $('.no-result').hide(); }
    });
});




// $(function () {
//     $('.counter').counterUp({
//         delay: 10,
//         time: 1000
//     });
// });



// like publication
$("a.js-like").on("click", function (e) {

    e.preventDefault();
    const spanCount = $(this).find('span.js-likes')[0];
    const url = this.href;
    const icone = this.querySelector('i');
    axios.get(url).then(function (response) {
        const likes = response.data.likes;
        spanCount.textContent = likes;
        if (icone.classList.contains('fas')) {
            icone.classList.replace('fas', 'far');
        } else {
            icone.classList.replace('far', 'fas');
        }

    }).catch(function (error) {
        console.log(error)
        if (error.status == 403) {
            window.alert("il faut être connecté pour aimer un article");
        } else {
            window.alert("Une erreur s'est produite rézssayer plus tard");
        }
    })
})






(function() {
    "use strict";
  
  
    /**
     * Initiate Pure Counter 
     */
    new PureCounter();
  
  })()