<?php

/* 
 * Concesso in licenza d'uso a STAKING SCHOOL
 * Sviluppato da WeDev s.a.s di Ricci Stefano & C.
 */

class Hash {

    function __construct() {
        
    }
    public static function create($algo,$data,$salt) {
        $context = hash_init($algo, HASH_HMAC,$salt);
        hash_update($context, $data);
        return hash_final($context);
    }

}