var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function showTab(n) {
  // This function will display the specified tab of the form ...
  var x = document.getElementsByClassName("tab");
  var largo = x.length;  
  if (n > (x.length - 1)) {
    $(x[0]).fadeIn()
    // x[0].style.display = "block";
  } else {
    $(x[n]).fadeIn()
    // x[n].style.display = "block";
  }
  // ... and fix the Previous/Next buttons:
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }
  if (n == (x.length - 1)) {
    document.getElementById("nextBtn").innerHTML = '<i class="fa fa-save"></i> Guardar';
  } else {
    document.getElementById("nextBtn").innerHTML = "Siguiente";
  }
  fixStepIndicator(n)                                               // ... and run a function that displays the correct step indicator:
}

function nextPrev(n) {                                              // This function will figure out which tab to display
  var x = document.getElementsByClassName("tab");  
  // if you have reached the end of the form... :
  if (currentTab + n >= x.length) {   
    document.getElementById("nextBtn").disabled = true;
    setTimeout(() => {  document.getElementById("nextBtn").disabled = false;  }, 5000)

    currentTab = x.length - 1;
    document.getElementById("nextBtn")
    document.getElementById("btn_submit_form").click();          //...the form gets submitted:
    return false;
  }else{
    x[currentTab].style.display = "none";                           // Hide the current tab:
    currentTab = currentTab + n;                                    // Increase or decrease the current tab by 1:
  }
  showTab(currentTab);                                              // Otherwise, display the correct tab:
}

function fixStepIndicator(n) {
  // This function removes the "active" class of all steps...
  var i, x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
  }
  //... and adds the "active" class to the current step:
  x[n].className += " active";
}