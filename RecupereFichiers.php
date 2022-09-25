<?php

namespace Bt;

class RecupereFichiers
{
    /**
     * Met dans un tableau le contenu du template demandé et ses fichiers parents
     *
     * @param string $nomDuFichierDeTemplate
     * @param string $cheminDuFichierDeTemplate
     *
     * @return array
     */
    public function execute(
        string $nomDuFichierDeTemplate,
        string $cheminDuFichierDeTemplate
    ) :array {
        // Met dans un tableau le contenu du template demandé
        $fichiersTemplate[] = file_get_contents(
            $cheminDuFichierDeTemplate . $nomDuFichierDeTemplate
        );
        // Ajoute dans le tableau les fichiers parents du template
        preg_match_all(
            '/extends \'([.|\w|\/]{0,})/m',
            $fichiersTemplate[0],
            $nomsParents,
            PREG_SET_ORDER,
            0
        );
        if (!empty($nomsParents)) {
            foreach($nomsParents as $nomParent) {
                $fichiersTemplate[] = file_get_contents(
                    $cheminDuFichierDeTemplate . '/' . $nomParent[1]
                );
            }
        }

        return $fichiersTemplate;
    }
}
