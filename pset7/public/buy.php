<?php
// configuration
require("../includes/config.php");
    
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
         // lookup via lookup function/yahoo query
        $stock = lookup($_POST["code"]);
       
        if (empty(($_POST["code"]) || (empty($_POST["quantity"])) || (!preg_match("/^\d+$/", $_POST["shares"]))))
        {
            apologize("Please enter a stock symbol, and whole number of shares you would like to purchase.");
        }

            if($stock === false)
            {   
                apologize("Please enter a valid symbol");
            }
               
                
    $transaction = "BUY";  
    $rows = query("SELECT shares FROM portfolio WHERE id = ? and symbol = ?", $_SESSION["id"],
    strtoupper($_POST["code"]));  
     // check to see if money is available
                
    if($_SESSION["cash"] < $stock["price"] * $rows[0]["shares"])
    {
        apologize("Sorry, you do not have enough cash monies!");
    }
    //BUY the stock, update data base and ensure that symbol is stored uppercase.
    $buy = query("INSERT INTO portfolio (id, symbol, shares) VALUES(?, ?, ?) ON DUPLICATE KEY UPDATE 
    shares = shares + VALUES(shares)", $_SESSION["id"], strtoupper($stock["symbol"]), $_POST["quantity"]);                            
    // if the query fails
    if($buy === false)
    {
    apologize("Sorry there was an error with the database, please try again.");    
    }
                                
    // update user's cash
    $update_cash = query("UPDATE users SET cash = cash - $value WHERE id = ?", $_SESSION["id"]);
    $_SESSION["cash"] = $update_cash;
    
    // add to history
    query("INSERT INTO history (id, transaction, symbol, shares, price, date) VALUES (?, ?, ?, ?, ?, now())", $_SESSION["id"], $transaction, $_POST["code"], $_POST["quantity"],$stock["price"] * $rows[0]["shares"] );
    //redirect to portfolio
    redirect("/");
    }
    
         else
         {
            render("buy_form.php", ["title" => "Buy"]);
         }  
           
?>
