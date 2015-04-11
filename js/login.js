if (typeof(Storage) != "undefined") {
    // Retrieve
    document.getElementById("user").value = localStorage.getItem("user");

    if(localStorage.getItem("user") != undefined){
        document.getElementById("remember").checked = true;
    }
} // If not supported leave the fields blank


function check(e){

    var user = document.getElementById("user").value.replace(/[^a-z0-9]/gi,'');
    var pass = document.getElementById("pass").value;
    var hash = CryptoJS.SHA3(pass, { outputLength: 512 });

    //Setting the sweet values...
    document.getElementById("user").value = user;
    document.getElementById("pass").value = hash;


    if(document.getElementById("remember").checked){
        if (typeof(Storage) != "undefined") {
            // Store
            localStorage.setItem("user", user);
        }

    } else {
        //Remove old traces if any
        localStorage.removeItem("org");

    }

    if(org.length < 3 || user.length < 4 || pass.length < 6){
        //We will do other checking too...
        e.preventDefault();
        document.getElementById("pass").value = pass;
        document.getElementById("message").innerHTML = "Login Failed. Check your credentials.";
    } else {
        document.getElementById("login").innerHTML = "<h3><img src='images/load.gif'/> Signing In...</h3>";
    }

}