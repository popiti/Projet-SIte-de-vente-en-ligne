<?php

namespace App\Service;

class PasswordStrength
{
    public function pswdStrength(string $pswd) : string
    {
        //$message = array();
        $size = strlen($pswd);
        $tmp = 0;

        for($i = 1;$i<$size;$i++) // Ici je vérifie que le mot de passe de l'utilisateur n'est pas une suite de nombre
        {
            $resultat = ord($pswd[$i])-ord($pswd[$i-1]);
            if( ($resultat == 1) && ($tmp == 0))
            {
                $tmp=0;
            }
            else
            {
                $tmp = 1;
            }
        }
        if ($size < 6 )
        {
                $message="Votre mot de passe doit contenir au moins 6 caractères veuillez changez votre mot de passe";
        }
        else if(!(strcmp($pswd,"qwerty")) || !(strcmp($pswd,"azerty")) || !(strcmp($pswd,"password")) || !(strcmp($pswd,"motdepasse")) )
        {
            $message="Veuillez changer de mot de passe et n'utilisez pas des mots de passe simples comme azerty qwerty ou une série de nombre";
        }
        else if($tmp==0)
        {
            $message="Veuillez changer de mot de passe et n'utilisez pas des mots de passe simples comme une série de nombre";
        }
        else
        {
                $message="Votre mot de passe est fort et fiable";
        }
        return $message;
    }
}