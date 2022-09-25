<?php

namespace Bt;

class Template
{
    /**
     * Code html
     *
     * @var string
     */
    private string $contenu;

    /**
     * @param string $nomDuFichierDeTemplate
     * @param string $cheminDuFichierDeTemplate
     * @param array $donnees
     *
     * @return $this
     */
    public function genereContenu(
        string $nomDuFichierDeTemplate,
        string $cheminDuFichierDeTemplate,
        array $donnees = []
    ): Template {
        // Dans le tableau nous avons
        // Le template demandé, index 0
        // Les parents successifs s'il en a index 1, index 2 etc = ceux qui extends successivement
        // Le template avec l'index le plus haut est le premier parent
        $fichiersTemplate = (new RecupereFichiers())->execute($nomDuFichierDeTemplate, $cheminDuFichierDeTemplate);
        // Nous cherchons les blocks déclarés en partant du template demandé et en remontant dans les ancêtres.
        // Si un bloc est trouvé, il ne sera plus remplacé dans les ancêtres
        $blocks = (new RecupereBlocks())->execute($fichiersTemplate);
        // Met à jour le contenu des blocks
        $this->contenu = (new MetAJourContenuBlocks())->execute($blocks, $fichiersTemplate[0]);
        // Remplace les variables par les valeurs
        $this->contenu = (new RemplaceVariablesParValeurs())->execute($this->contenu, $donnees);

        return $this;
    }

    /**
     * Rend le contenu html
     */
    public function affiche()
    {
        echo $this->contenu;
    }

    /**
     * Accesseur code html
     *
     * @return string
     */
    public function getContenu(): string
    {
        return $this->contenu;
    }
}
