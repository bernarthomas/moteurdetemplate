<?php

namespace Bt;

class RemplaceVariablesParValeurs
{
    /**
     * Remplace les variables par les valeurs
     *
     * @param string $contenu
     * @param array $donnees
     *
     * @return string
     */
    public function execute(string $contenu, array $donnees = []): string
    {
        preg_match_all(
            '/{{([ \w]{0,})}}/m',
            $contenu,
            $variables,
            PREG_SET_ORDER,
            0
        );
        foreach($variables as $var) {
            if (isset($donnees[trim($var[1])])) {
                $contenu = str_replace($var[0], $donnees[trim($var[1])], $contenu);
            }
        }

        return $contenu;
    }
}