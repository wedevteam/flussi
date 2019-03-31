<?php
$emailText = '
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <title>Credenziali Accesso | '.$this->view->platformData["siteName"].'</title>
    <link rel="stylesheet" 
        href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i">
    
</head>

<body style="margin: 0; padding: 0;">
    <table align="center" cellpadding="0" cellspacing="0" width="600" style="text-align:center; line-height: 1.2;">
        <tr>
            <td align="center" style="padding-top:20px; padding-bottom:20px; background:#FFFFFF">
                <img src="'.URL.'/public/images/logo-flussi-aste.jpg" height="70" display="block" alt=""/><br/>
            </td>
        </tr>
    </table>
    <hr>
    <table align="center" cellpadding="0" cellspacing="0" width="600" style="text-align:center; font-family: sans-serif; color: #253645; font-size: 14px;">
        <tr>
            <td align="center" style="padding-top:20px; padding-bottom:20px; padding-left:20px; text-align:left; padding-right:20px; color: #253645;">
		Gentile Agenzia,<br/>
                siamo lieti di inviarti le credenziali di accesso a Flussiaste.com:<br/><br/>
            	Email: '.$this->view->data["email"].'<br/>
            	Password: '.$password.'<br/><br/>
                Puoi accedere cliccando sul link indicato di seguito:
            </td>
        </tr>
	<tr>
            <td align="center" style="padding:20px; text-align:center; color: #373736;">
		<a href="'.URL.'" target="_blank" 
                style="text-decoration:none; text-align:center; background:#6DC4E9; 
                    color:#253645; font-size:16px; padding-left:15px; padding-right:15px; padding-top:5px; padding-bottom:5px; border-radius:10px;">Accedi</a>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding-top:20px; padding-bottom:20px; padding-left:20px; text-align:left; padding-right:20px; color: #253645;">
		'.$this->view->platformData["siteName"].'
            </td>
        </tr>
    </table>
    <hr>
    
    

</body>
</html>'
			 	
            
?>
