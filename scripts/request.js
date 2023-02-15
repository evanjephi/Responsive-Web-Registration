"use strict";

// This block will run when the DOM is loaded (once elements exist), it's really only necessary as a IE 9 fallback for lack of support for defer
window.addEventListener("DOMContentLoaded", () => {
  
  // This function is correct, don't mess with it. It uses a regular expression to return true if the provided gps is valid and false if it isn't.

function gpsIsValid (string){
  return /^(?:(?!\()(?!.+\))|\((?=.*\)))([+-]?\d{1,2}(?:\.\d+)?)\s*,\s*([+-]?\d{1,3}(?:\.\d+)?)\)?$/.test(string);
}
  /*******************************************************************/
  //Note: this is the same function as above defined in one line using arrow functions, if you prefer the clean approach.

  // const gpsIsValid = (string) => /^(?:(?!\()(?!.+\))|\((?=.*\)))([+-]?\d{1,2}(?:\.\d+)?)\s*,\s*([+-]?\d{1,3}(?:\.\d+)?)\)?$/.test(string);
    /*******************************************************************/



  //replace ??? within the selector to get the required element. Keep in mind that this file will be included on other pages as well...so don't select the form tag.
  const requestForm = document.querySelector("#requestform");

  //replace 'event name here' with the correct event
  requestForm.addEventListener("submit", (ev) => {

    //declare a boolean flag valid set to false for determining if there were any errors found below
    let error = false;

    //replace ??? with the selector to get the gps input, and use a traversal function from the input you just selected to get the the gps error message
    const gpsSelect = document.querySelector("#gps");
    const gpsError = gpsSelect.nextElementSibling; //traverse from the input selected above to get error message

    // check if gps is valid using the provided function and handle appropriately.  Remember that the value attribute provides access to what's inside the textbox.
    if (gpsIsValid(gpsSelect.value) == true){
      gpsError.classList.add("hidden");
    }
    else{
      gpsError.classList.remove("hidden");
      error=true;
    }

    //add selectors to get the necessary elements to validate a radio button was chosen, and the relevant error message. Don't add anything to the HTML. 
    const radioSelect = document.querySelectorAll("input[type='radio']");
    let radioError = radioSelect[1].parentElement.nextElementSibling;
    
    //validate that a radio button was selected. Remember that a radio button's checked attribute determines if it was selected
    if(radioSelect[0].checked==false && radioSelect[1].checked==false){
      radioError.classList.remove("hidden");
      error = true;
    }else
      radioError.classList.add("hidden");
    
    // add selectors to get the necessary element to get the dropdown box, and the relevant error message. 
    const messageSelect = document.querySelector("#primary");
    let dropError = messageSelect.nextElementSibling;

    //validate that something other then the default in the dropdown has been selected.
    if(messageSelect.value==""){
      dropError.classList.remove("hidden");
      error=true;
    }else
      dropError.classList.add("hidden");

  
    // Make this conditional on if there are errors.
    if(error==true){
   ev.preventDefault(); //STOP FORM SUBMISSION IF THERE ARE ERRORS
    }
  });
});
