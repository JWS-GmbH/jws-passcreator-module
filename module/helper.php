<?php
// No direct access
defined('_JEXEC') or die;

class PassCreator
{
    
    //call in default.php
    public static function submit($apiKey, $array, $passUID) {
            $input = array(
                '5dc29d162176d1.51964649' => $array['5dc29d162176d1_51964649'],
                '5dc29da1360391.66363962' => $array['5dc29da1360391_66363962'],
                '5dc29da1360484.78958470' => $array["5dc29da1360484_78958470"],
                '5dcd0aec164964.03925150' => $array['5dcd0aec164964_03925150'],
                '5dcd0aec1649f5.39810123' => $array['5dcd0aec1649f5_39810123'],
            );
            $passLink = PassCreator::createPass($apiKey, $passUID, $input);
            
        return $passLink;
    }

    public static function getTokens($databasePrefix){
        $id = JFactory::getUser()->id;
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select($db->quoteName('profile_value'))
            //database-prefix
            ->from($db->quoteName($databasePrefix . 'user_profiles'))
            ->where($db->quoteName('user_id') . ' LIKE ' . $db->quote($id));

        $db->setQuery($query);
        // Load the row.
        $result = $db->loadResult();

        return $result;
    }

    public static function reduceTokens($databasePrefix, $tokens){
        $id = JFactory::getUser()->id;
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        //database-prefix
        $query->update($db->quoteName($databasePrefix . 'user_profiles'))
        ->set($db->quoteName('profile_value') . ' = ' . $db->quote($tokens -1))
        -> where( $db->quoteName('user_id') . ' LIKE ' . $db->quote($id));
        
        $db->setQuery($query);
        $result = $db->execute();

        return $result;
    }

    /* public static function getPassUID($apiKey){
        $opts = array( 'http' => array(
                'method'  => 'GET',
                'header'  => 'Authorization: '.$apiKey .'',
            )
        );
        $context = stream_context_create($opts);
        $json = file_get_contents('https://app.passcreator.com/api/pass-template', false, $context);
        //nimm den ersten identifier aus der Liste
        return json_decode($json, true)[2]['identifier'];
    } */

    public static function getPassFields($apiKey, $passUID){
        $opts = array( 'http' => array(
            'method'  => 'GET',
            'header'  => 'Authorization: '.$apiKey .'',
        )
        );
        $context = stream_context_create($opts);
        $json = file_get_contents('https://app.passcreator.com/api/pass-template/'.$passUID.'?zapierStyle=true', false, $context);
        
        return json_decode($json, true);
    }

    public static function generatePassForm($apiKey, $passUID){

        //get all passfields
        $array = PassCreator::getPassFields($apiKey, $passUID);
        $htmlString = "";

        //generate htmlString with html-form
        foreach ($array as $id){
            if( $id['key'] === 'userProvidedId'){
                $htmlString .= "";
            }  else {
            $htmlString .= "
            <div class='control-group'>
                <label class='control-label'>". $id['label']. ":</label>
                <div class='controls'>
                    <input type='text' required name=" . $id['key'] . " value='' />
                </div>
            </div>
            ";
            };
        }
        return $htmlString;
    }

    public static function createPass($apiKey, $passUID, $input){
        
        $opts = array( 'http' => array(
            'method'  => 'POST',
            'header'  => 'Authorization: '.$apiKey .'',
            'content' => json_encode($input),
          )
        );
        $context = stream_context_create($opts);
        $json = file_get_contents('https://app.passcreator.com/api/pass?passtemplate='.$passUID.'&zapierStyle=true', false, $context);
        $passURL = json_decode($json)->linkToPassPage;
        return $passURL;
    }
}