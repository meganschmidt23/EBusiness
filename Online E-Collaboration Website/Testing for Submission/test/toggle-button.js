// add this as a script tag before the closing body tag in the html portion of the php file

// change the id based on the name of your button
addRowBtn = document.getElementById("add-user-btn");
addRowForm = document.getElementById("add-user-form");
addRowBtn.addEventListener("click", function() {
    // checks to see if the class is listed or not. Add/removes class
    addRowForm.classList.toggle("is-hidden");
});