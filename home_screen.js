function new_panel_open(){
    // open the new panel pop-ip
    document.getElementById("new_panel_popup").style.display = "block";
}

function new_panel_close(){
    // close the new panel pop-up
    document.getElementById("new_panel_popup").style.display = "none"; 
}

// Adds listener for the header "new panel"
// Exposed to chatgpt jan 9 version on 1/20/23
document.getElementById("header-new-panel-btn").addEventListener("click", new_panel_open);

// Set up the pop-up clickaway functionality
const popup = document.querySelector(".formPopup");
document.addEventListener("click", function(event){
    if (event.target.closest(".formPopup") || event.target.closest(".project-square") || event.target.closest("#header-new-panel-btn")){
        return;
    } else {
        new_panel_close();
    }
});
