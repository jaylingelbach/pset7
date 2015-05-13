<?php

    // configuration
    require("../includes/config.php"); 
    
    $rows = query("SELECT * FROM portfolio WHERE id = ?", $_SESSION["id"]);
   
   $positions = [];
   
   foreach ($rows as $row)
   {
        $stock = lookup($row["symbol"]);
        
        
        
        if ($stock !== false)
        {
            $positions[] = [
            "name" => $stock["name"],
            "price" => number_format($stock["price"] , 2 , "." , ","),
            "shares" => $row["shares"],
            "symbol" => $row["symbol"],
            "value" => number_format($row["shares"] * $stock["price"], 2 , "." , ",")           
            ];
           
         
        }
       
    }
    
    // query cash for template
    $users = query("SELECT * FROM users WHERE id = ?", $_SESSION["id"]);
    
    render("portfolio.php", ["positions" => $positions, "title" => "Portfolio","shares" => $positions, "users" => $users]);

?>
