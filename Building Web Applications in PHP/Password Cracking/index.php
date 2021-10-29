<!DOCTYPE html>
<head><title>Arsh Sharma MD5 Cracker</title></head>
<body>
<h1>MD5 cracker</h1>
<p>This application takes an MD5 hash of a four digit pin and check all 10,000 possible four digit PINs to determine the PIN.</p>
<pre>
Debug Output:
<?php
$goodText = "Not found";
// If there is no parameter, this code is all skipped
if ( isset($_GET['md5']) ) {
    $time_pre = microtime(true);
    $md5 = $_GET['md5'];

    // This is our alphabet
    $txt = "0123456789";
    $show = 15;
    $valCheck = 0;

    // Outer loop to go through the alphabet for the
    // first position in our "possible" pre-hash
    // text
    for($index1=0, $index1Max = strlen($txt); $index1< $index1Max; $index1++ ) {
        $num1 = $txt[$index1];   // The first of four pin

        // Our inner loop. Note the use of new variables
        // $j and $ch2 
        for($index2=0, $index2Max = strlen($txt); $index2< $index2Max; $index2++ ) {
            $num2 = $txt[$index2];  // Our second number

            for($index3 = 0, $index3Max = strlen($txt); $index3< $index3Max; $index3++){
                $num3 = $txt[$index3]; // Our third number

                for($index4 = 0, $index4Max = strlen($txt); $index4< $index4Max; $index4++){
                    $num4 = $txt[$index4]; // Our fourth number

                    // Concatenate the two characters together to
                    // form the "possible" pre-hash text
                    $try = $num1.$num2.$num3.$num4;

                    // Run the hash and then check to see if we match
                    $check = hash('md5', $try);
                    if ( $check === $md5 ) {
                        $goodText = $try;
                        break;   // Exit the inner loop
                    }

                    // Debug output until $show hits 0
                    if ( $show > 0 ) {
                        print "$check $try\n";
                        --$show;
                    }

                    $valCheck++;

                }
            }


        }
    }
    // Compute elapsed time
    echo "Total checks: " . $valCheck , "\n";
    $time_post = microtime(true);
    print "Elapsed time: ";
    print $time_post-$time_pre;
    print "\n";

}
?>
</pre>
<!-- Use the very short syntax and call htmlentities() -->
<p>PIN: <?= htmlentities($goodText); ?></p>
<form>
<input type="text" name="md5" size="60" />
<input type="submit" value="Crack MD5"/>
</form>
<ul>
<li><a href="index.php">Reset</a></li>
<li><a href="md5.php">MD5 Encoder</a></li>
<li><a href="makecode.php">MD5 Code Maker</a></li>
<li><a
href="https://github.com/csev/wa4e/tree/master/code/crack"
target="_blank">Source code for this application</a></li>
</ul>
</body>
</html>

