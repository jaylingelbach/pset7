<?php
// configuration
require("../includes/config.php");




    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
       // lookup via lookup function/yahoo query
        $stock = lookup(strtoupper($_POST["code"]));
        $_POST["code"] = strtoupper($_POST["code"]);
        if (empty(($_POST["code"]) || (empty($_POST["quantity"])) || (!preg_match("/^\d+$/", $_POST["shares"]))))
        {
            apologize("Please enter a stock symbol, and whole number of shares you would like to sell.");
        }
        
            if($stock === false)
            {   
                apologize("Please enter a valid symbol");
            }
        
                
                    $transaction = "SELL";
                    // look up shares to be sold
                    $rows = query("SELECT shares FROM portfolio WHERE id = ? and symbol = ?", $_SESSION["id"], strtoupper($_POST["code"]));
                    //having trouble here!
                    // verify the stock exists in the portfolio
                    if(count($rows) !== 1)
                    {
                        apologize("Shares not found in your portfolio");
                    }
                    
                    //determine how much to pay
                    $value = $stock["price"] * $rows[0]["shares"];
                    // amount to subtract from portfolio
                   
                    // sell the stock
                    $delete = query("DELETE FROM portfolio WHERE id = ? and symbol = ?", $_SESSION["id"], $_POST["code"]);
                    
                    // add $value to cash
                    $update_cash = query("UPDATE users SET cash = cash + $value WHERE id = ?", $_SESSION["id"]);
                    $_SESSION["cash"] = $update_cash;
                    
                    // add to history
                    query("INSERT INTO history (id, transaction, symbol, shares, price, date) VALUES (?, ?, ?, ?, ?, now())", $_SESSION["id"], $transaction, $_POST["code"], $_POST["quantity"],$stock["price"] * $rows[0]["shares"] );
                                      
                    //redirect to portfolio
                    redirect("/");
                
    }
    else
    {
        
        render("sell_form.php", ["title" => "Sell"]);
    }
?>
