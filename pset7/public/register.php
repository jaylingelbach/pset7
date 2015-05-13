<?php

    // configuration
    require("../includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("register_form.php", ["title" => "Register"]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // TODO
       // validate submission
        if (empty($_POST["username"]))
        {
            apologize("You must provide your username.");
        }
        else if (empty($_POST["password"]))
        {
            apologize("You must provide your password.");
        }
        else if(empty($_POST["confirmation"]))
        {
            apologize("You must provide a confirmation of your password.");
        }
        else if ($_POST["password"] != $_POST["confirmation"])
        {
            apologize("Your passwords did not match, please try again");
        }
        
        // check to see if username exists
        $rows = query("SELECT * FROM users WHERE username = ?", $_POST["username"]);
        
        if($check_username == 1)
        {
            apologize("Sorry, that username already exists");
        }
        
        else
        {
            if (crypt($_POST["password"], $row["hash"]) == $row["hash"])
            //find user id
            $rows = query("SELECT LAST_INSERT_ID() AS id");
            $id = $rows[0]["id"];
            // log the user in
            $_SESSION["id"] = "id";
            $_SESSION["cash"] = "cash";
            redirect("index.php");
        }
         
    }

?>


