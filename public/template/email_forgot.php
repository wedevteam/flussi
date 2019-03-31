<?php

/* 
 * Concesso in licenza d'uso a LA FENICE IMMOBILIARE SRL
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

$msg='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="viewport" content="width=device-width" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>'.$siteTitle.' | Recupero Password</title>
        <link href="styles.css" media="all" rel="stylesheet" type="text/css" />
    </head>

    <body>

        <table class="body-wrap">
            <tr>
                <td></td>
                <td class="container" width="600">
                    <div class="content">
                        <table class="main" width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td class="content-wrap">
                                    <table  cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td>
                                                <img class="img-fluid" src="'.URL .'public/images/'.$logo.'" style="width:80px;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="content-block">
                                                <h3>Recupero Password</h3>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="content-block">
                                                Per impostare una nuova Password, fai click sul link che segue:
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td class="content-block aligncenter">
                                                <a href="'.$httpPath.'/login/resetPassword/'.$hash.'" 
                                                    class="btn-primary">Imposta Nuova Password
                                                </a>
                                                <br>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="content-block">
                                                '.$emailFirmaTesto.'
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <div class="footer">
                            
                        </div></div>
                </td>
                <td></td>
            </tr>
        </table>

    </body>
</html>
'
        ;
?>