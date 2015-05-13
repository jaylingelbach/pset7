<?php

    // configuration
    require("../includes/config.php"); 

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("forgot_form.php", ["title" => "Change password"]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // query database for user
        $rows = query("SELECT * FROM users WHERE username = ?", $_POST["username"]);
        
        // first (and only) row
        $row = $rows[0];
        
        // validate submission
        if (empty($_POST["username"]))
        {
            apologize("You must provide your username.");
        }
        else if (empty($_POST["current_password"]) || (empty($_POST["new_password"])) || (empty($_POST["confirmation"])))
        {
            apologize("You must provide your password.");
        }
        
        else if ($_POST["new_password"] != $_POST["confirmation"])
        {
            apologize("Your new passwords did not match, please try again");
        }

            

            // compare hash of user's input against hash that's in database
            if(crypt($_POST["current_password"], $row["hash"]) != $row["hash"])
            {
                apologize("Your current password doesn't match");
            }
     
             
            query("UPDATE users SET hash = ? WHERE username = ?", crypt($_POST["new_password"]), $_POST["username"]) === true;
            // redirect to login
            redirect("login.php");
                
                
                
            
    }
   
?>





