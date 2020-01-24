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
        <!--         
        <p>Falls Sie noch weitere Tokens benötigen, melden Sie sich gerne bei der Messeleitung. 
        Per Mail: <a href="mailto: dillingen@jws.de>">dillingen@jws.de</a> oder Telefon: 09074 / 92207-0 </p>
        <p>Bitte erstellen Sie auf dieser Seite Ihren Ausstellerausweis.
        <b>Sie können so viele Ausweise erstellen, wie Ihnen aufgrund Ihrer Standgröße zustehen. </b></p>
        <p><b>Nach der Erstellung haben Sie zwei Möglichkeiten: </b><br>
            Fügen Sie den Ausweis Ihrem Handy-Wallet hinzu, oder drucken Sie Ihn aus.</p>
        <p>Auf dem Ausweis befindet sich ein QR-Code, welchen Sie beim Eintreten vorzeigen müssen. 
        Dieser wird von einem Mitarbeiter abgescannt und entwertet. &nbsp;
        Nach einem erfolgreichen Messetag werden alle Ausstellerausweise wieder für den nächsten Tag freigeschaltet
         und Sie können die selbe Karte vorzeigen.</p>
         -->

        <form class="form-horizontal" name="submit" method="REQUEST" enctype="multipart/form-data">
            <div class="control-group required">
                <label class="control-label">Aussteller oder Firma:</label>
                <div class="controls">
                    <input type="text" required name="5dc29d162176d1.51964649" value="" />
                </div>
                <label class="control-label">Vor- und Nachname:</label>
                <div class="controls">
                    <input type="text" required name="5dc29da1360391.66363962" value="" />
                </div>
                <label class="control-label">PLZ und Wohnort:</label>
                <div class="controls">
                    <input type="text" required name="5dc29da1360484.78958470" value="" />
                </div>
                <label class="control-label">Anschrift:</label>
                <div class="controls">
                    <input type="text" required name="5dcd0aec164964.03925150" value="" />
                </div>
                <label class="control-label">Geburtsdatum:</label>
                <div class="controls">
                    <input type="text" required name="5dcd0aec1649f5.39810123" value="" />
                </div>
            </div>
            <button class="btn" type="submit" name="submit" value="Submit" style="background-color: #3d8835; color: white; font-size: 15px; height: 30px; margin-left: 85px;">Submit</button>
        </form>

    <?php
} else {
    echo "Sie haben leider keinen Ausstellerausweis-Token über. <br> Falls Sie noch weitere Tokens benötigen, melden Sie sich gerne bei der Messeleitung. Per Mail: <a href='mailto: dillingen@jws.de>'>dillingen@jws.de</a> oder Telefon: 09074 / 92207-0 ";
};
?>


</div>

<?php
if (isset($post["submit"])) {
    $passLink = PassCreator::submit($apiKey, $post, $passUID);
    ?>
    <script type="text/javascript">
        function submit() {
            let modul = document.getElementById("modul")
            modul.innerHTML = ` <?php echo $endtext ?> `
        }
        submit();
        
        //Cleare Parameter after URL
        //Setze die neue URL zusammen, aus: dem Hauptteil der URl + URL-Pfad
        window.history.replaceState( {} , "", window.location.origin + window.location.pathname);
    </script>
    
    <?php 
    PassCreator::reduceTokens($tokens);
    echo 'Alternativ auch der Link zu Ihren Pass: ' .'<a href=' . $passLink . '>' . $passLink . '</a>';
    echo "<br>";
    echo "<a href='ausstellerausweis'>Falls Sie gleich noch einen Ausstellerausweis erstellen wollen, klicken Sie hier.</a>";
    echo "<br>";
    echo "<iframe style='margin-top: 20px;width: 100%; height: 500px' src='".$passLink."'</iframe>";
    
    
}

//echo json_encode($submit);

//echo json_encode($post);

// echo 'PassUID '.$passUID;
// echo '<br>';
//echo 'PassFields '.$passFields;
// echo '<br>';
/*
<p>Vielen Dank, dass Sie einen digitalen Ausstellerausweis gewählt haben!</p>
            <p><b>Jetzt haben Sie zwei Möglichkeiten. </b>Fügen Sie den Ausweis Ihrem Handy-Wallet hinzu, oder drucken Sie Ihn aus.&nbsp;</p>
            <p>Auf dem Ausweis befindet sich ein QR-Code, welchen Sie beim Eintreten vorzeigen müssen. Dieser wird von einem Mitarbeiter abgescannt und entwertet. 
            &nbsp;Nach einem erfolgreichen Messetag werden alle Ausstellerausweise wieder für den nächsten Tag freigeschaltet und Sie können die selbe Karte vorzeigen.</p>
*/

?>