<?php

namespace Bt;

class RecupereBlocks
{
    /**
     * Nous cherchons les blocks déclarés en partant du template demandé et en remontant dans les ancêtres.
     * Si un bloc est trouvé, il ne sera plus remplacé dans les ancêtres
     *
     * @param array $fichiersTemplate
     *
     * @return array
     */
    public function execute(array $fichiersTemplate): array
    {
        $blocks = [];
        foreach( $fichiersTemplate as $template) {
            preg_match_all(
                '/ block ([\w<>\/ \n\s]{0,}) /m',
                $template,
                $blocksTrouves,
                PREG_SET_ORDER,
                0
            );
            foreach ($blocksTrouves as $block) {
                preg_match_all(
                    '/{% block ' . $block[1] . ' %}([\{\}\[\]\w=\"\<\/\> \n\.\-]{0,}){% endblock ' . $block[1] . ' %}/m',
                    $template,
                    $contenuBlock,
                    PREG_SET_ORDER,
                    0);
                if (!isset($blocks[$block[1]])) {
                    $blocks[$block[1]] = $contenuBlock[0][1];
                }
            }
        }

        return $blocks;
    }
}