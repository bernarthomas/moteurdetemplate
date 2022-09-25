<?php

namespace Bt;

class MetAJourContenuBlocks
{
    /**
     * Met à jour le contenu html des blocks
     *
     * @param array $blocks
     * @param string $contenu
     *
     * @return string
     */
    public function execute(array $blocks, string $contenu): string
    {
        foreach($blocks as $name => $block) {
            $contenu = preg_replace(
                '/{% block '.$name.' %}([\{\}\[\]\w=\"\<\/\> \n\.\-]{0,}){% endblock '.$name.' %}/m',
                $blocks[$name],
                $contenu
            );
        }

        return $contenu;
    }
}