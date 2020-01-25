<?php
// No direct access
defined('_JEXEC') or die;
?>

<div id="modul">

    <?php
    if ($tokens >= 1) {
        echo "<h3> Ihr aktuelles Guthaben: " . $tokens . '</h3>';
        echo "<div>". $starttext ."</div>";
        ?>

        <form class="form-horizontal" name="submit" method="REQUEST" enctype="multipart/form-data">
            <div class="control-group required">
               <?php
                    echo PassCreator::generatePassForm($apiKey, $passUID);
               ?>
            </div>
            <button class="btn" type="submit" name="submit" value="Submit" style="background-color: #3d8835; color: white; font-size: 15px; height: 30px; margin-left: 85px;">Submit</button>
        </form>

        <?php
    
        } else {
            echo $noToken . "";
        };
        ?>
</div>

<!--Submit call -->
<?php
if (isset($post["submit"])) {
    $passLink = PassCreator::submit($apiKey, $post, $passUID);

    if ($passLink === null){

        ?>
        <script type="text/javascript">
        //Texteinblendung $errorText
        function submit() {
            let modul = document.getElementById("modul")
            modul.innerHTML = ` <?php echo $errorText ?> `
        }
        submit();
        
        //Cleare URL-Parameter
        window.history.replaceState( {} , "", window.location.origin + window.location.pathname);
    </script>

    <?php

    } else {

    ?>
    <script type="text/javascript">
        //Texteinblendung $endtext
        function submit() {
            let modul = document.getElementById("modul")
            modul.innerHTML = ` <?php echo $endtext ?> `
        }
        submit();
        
        //Cleare URL-Parameter
        window.history.replaceState( {} , "", window.location.origin + window.location.pathname);
    </script>
    
    <?php 
    PassCreator::reduceTokens($databasePrefix, $tokens);
    echo 'Alternativ auch der Link zu Ihren Pass: ' .'<a href=' . $passLink . '>' . $passLink . '</a>';
    echo "<br>";
    echo "<a href='ausstellerausweis'>Falls Sie gleich noch einen Wallet-Pass erstellen wollen, klicken Sie hier.</a>";
    echo "<br>";
    echo "<iframe style='margin-top: 20px;width: 100%; height: 500px' src='".$passLink."'</iframe>";
    
    }
}
?>