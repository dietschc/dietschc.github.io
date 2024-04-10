/*
menu.js - JS file containing menu code for CSC235 Term Project
    Student: Coleman Dietsch
    Written:   11/23/21

When the user clicks on the button,
toggle between hiding and showing the dropdown content

JS code for drop down menu referenced from w3schools
https://www.w3schools.com/howto/howto_js_dropdown.asp
*/
function showMenu() {
    document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.menuButton')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}