<?php
// No direct access
defined('_JEXEC') or die;

class PassCreator
{
    public static function getTokens($databasePrefix, $tokenfield){
        $id = JFactory::getUser()->id;
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select($db->quoteName('profile_value'))
            //database-prefix
            ->from($db->quoteName($databasePrefix . 'user_profiles'))
            ->where($db->quoteName('user_id') . ' LIKE ' . $db->quote($id))
            ->where($db->quoteName('profile_key') . ' LIKE ' . $db->quote($tokenfield));

        $db->setQuery($query);
        // Load the row.
        $result = $db->loadResult();

        return $result;
    }

    public static function reduceTokens($databasePrefix, $tokens, $tokenfield){
        $id = JFactory::getUser()->id;
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        //database-prefix
        $query->update($db->quoteName($databasePrefix . 'user_profiles'))
        ->set($db->quoteName('profile_value') . ' = ' . $db->quote($tokens -1))
        ->where( $db->quoteName('user_id') . ' LIKE ' . $db->quote($id))
        ->where($db->quoteName('profile_key') . ' LIKE ' . $db->quote($tokenfield));
        
        $db->setQuery($query);
        $result = $db->execute();

        return $result;
    }

    public static function integrationScript($scriptString, $passID){
        return str_replace('pass-id', $passID, $scriptString);
    } 

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

    //call in default.php
    public static function submit($apiKey, $array, $passUID) {
        $fields = PassCreator::getPassFields($apiKey, $passUID);
        $input = array();
            
        //add to the $input-array:
        //key: every passfield, value: the submited value for each passfield
        //$array contains underscores instead of dotts. ->string replace
        foreach($fields as $i){
            $passfield = $i['key'];
            $input[$passfield] = $array[str_replace('.', '_', $passfield)];
        }
        $passObejct = PassCreator::createPass($apiKey, $passUID, $input);
        return $passObejct;
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
        //$passURL = json_decode($json)->linkToPassPage;
        return json_decode($json);
    }
}