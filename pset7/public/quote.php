<?php

    // configuration
    require("../includes/config.php");
    
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
         // lookup via lookup function/yahoo query
        $stock = lookup($_POST["code"]);
        
        if (empty($_POST["code"]))
        {
            apologize("Please enter a stock symbol");
        }
    
   
    
            if($stock === false)
            {
                apologize("Please enter a valid stock symbol");
            }
        
                else
            {
               render("quote_price.php", ["title" => "Quote", "symbol" => $stock["symbol"], "name" => $stock["name"],
                "price" => $stock["price"]]);  
            }
    }
    
     else
    {
        render("quote_form.php", ["title" => "Quote"]);
    }
?>
