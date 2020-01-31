# jws-passcreator-module
Custom module for Joomla to create exhibitor-ids with passcreator

# To-Do
## 1. blank component
You need an "blank component" that this modul can be shown on a page alone. "[Blank Component] (https://extensions.joomla.org/extension/blank-component/)" is a free component, you can easy download it.

After the installation you generate a new joomla-page with this blank component. Thus the page is empty, obviously. And you can show passcreator module on that page.

## 2. joomla profiles plugin

Make sure you activated the plugin "user - profile". 
I extendet the joomla profiles plugin by the field "tokens". The plugin-date can be fount in: `root > plugins > user > profile`.  
In `/profiles.xml` you can replace the `<config>` tag. `profiles/profiles.xml` can you replace completly.
  
## 3. language-files

There are language-files in `administator > language > (lang.) > plg_user_profile.ini`
Add these lines:  
PLG_USER_PROFILE_FIELD_TOKEN_LABEL="..."  
PLG_USER_PROFILE_FIELD_TOKEN_DESC="..."

## 4. upload-module
After all you have to zip [`/modules`](https://github.com/maadc/jws-passcreator-module/tree/master/module) and install it in joomla. 
Fill in all required data and you can display it on the blank page.  

## 5. fill in all the requied data
You will need:
* database-Prefix
* API-Key
* Pass-UID – Can be found out with an API call. [Get a list of pass templates](https://developer.passcreator.com/pass-templates/get-a-list-of-pass-templates.html)
* Choose what type of token you want to use
* Integration-Script – Can be generated on passcreator
* All sorts of Texts

Voilà
