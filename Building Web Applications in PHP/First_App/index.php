<?php

    $name = "Arsh Sharma";
    $hashValue =  hash("sha256", $name);
    $asciiArt = "
                *
               * *
              *   *
             *******
            *       *
           *         *
          *           *
    ";
    createHtml($name, $hashValue, $asciiArt);




    function createHtml($name, $hashValue, $asciiArt){
        echo <<< EOT
        <html lang="en">
        <head>
        <title>$name</title>
         </head>
         <body>
         <h1><strong>$name&nbsp;Request/Response</strong></h1>
         <p>
         The SHA256 hash of "$name" is <br>$hashValue
         <br><br>
         ASCII ART:
         <pre>$asciiArt</pre>
         <a href="fail.php">Click here to check the error setting</a>
         <br>
         <a href="check.php">Click here to cause a traceback</a>
         </p>
         </body>
        </html>
EOT;


        // The link to which it should compare to:https://www.wa4e.com/assn/php/?PHPSESSID=c2964b89de390ffd5257b9b7c020029a
    }

