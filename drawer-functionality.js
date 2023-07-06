function eraseCookie(name) {
    document.cookie = name + '=; Max-Age=0'
}

document.getElementById("sign-out-btn").addEventListener("click", (event) => {
    // Signs the user out 
    eraseCookie("username");
    eraseCookie("password");

    console.log("Signing out...");
    window.location.assign("endpoints/sign_out.php");  // redirect to this page, which will delete the login cookies
       

});

document.getElementById("contact-us-btn").addEventListener("click", (event) => {
    // Redirects the user to contact-us screen
    window.location.assign("./contact_us.php");
});