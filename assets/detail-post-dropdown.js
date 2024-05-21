import './styles/pages/custum-dropdown.scss';


$(function(){
    
    $('.choice-menu').on('click',function(e){
        e.preventDefault();
        var text = $(this).text();
   
    
    })
})


const dropdownBtn = document.getElementById("btn-dropdown");
const dropdownMenu = document.getElementById("dropdown-dropdown");
const toggleArrow = document.getElementById("arrow");

// Toggle dropdown function
const toggleDropdown = function () {
  dropdownMenu.classList.toggle("show");
  toggleArrow.classList.toggle("arrow");
};

// Toggle dropdown open/close when dropdown button is clicked
dropdownBtn.addEventListener("click", function (e) {
  e.stopPropagation();
  toggleDropdown();
});

// Close dropdown when dom element is clicked
document.documentElement.addEventListener("click", function () {
  if (dropdownMenu.classList.contains("show")) {
    toggleDropdown();
  }
});