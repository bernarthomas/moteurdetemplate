<?php

namespace Bt;

class MoteurDeTemplate
{

    /**
     * Code html
     *
     * @var string
     */
    private string $contenu;

    /**
     * Génère le code html et le stocke dans this->contenu
     *
     * @param string $nomDuTemplate
     * @param array $donnees
     *
     * @return $this
     */
    public function genereContenu(string $nomDuTemplate, array $donnees = [])
    {
        $hierarchy = [];
        $cache = [];
        $blocks = [];
        do {
            $hierarchy[] = $nomDuTemplate;
            $filename = __DIR__ . '/../templates/'.$nomDuTemplate.'.engine';
            $cache[$nomDuTemplate] = file_get_contents($filename);
            $re = '/extends \'([\w]{0,})\'/m';
            preg_match_all($re, $cache[$nomDuTemplate], $matches, PREG_SET_ORDER, 0);
            if ($matches != []) {
                $nomDuTemplate = $matches[0][1];
            }
        } while ($matches != []);
        foreach(array_reverse($hierarchy) as $template) {
            $re = '/ block ([\w<>\/ \n\s]{0,}) /m';
            $this->contenu = $cache[$template];
            preg_match_all($re, $this->contenu, $matches, PREG_SET_ORDER, 0);
            $blockList = array_column($matches, 1);
            foreach ($blockList as $block) {
                $re = '/{% block '.$block.' %}([\{\}\[\]\w=\"\<\/\> \n\.]{0,}){% endblock '.$block.' %}/m';
                preg_match_all($re, $this->contenu, $matches, PREG_SET_ORDER, 0);
                if ($matches != []) {
                    $blocks[$block] = $matches[0][1];
                }
            }
        }
        $this->contenu = end($cache);
        foreach($blocks as $name => $block) {
            $re = '/{% block '.$name.' %}([\{\}\[\]\w=\"\<\/\> \n\.]{0,}){% endblock '.$name.' %}/m';
            $this->contenu = preg_replace($re, $blocks[$name], $this->contenu);
        }
        $re = '/{{([\w]{0,})}}/m';
        preg_match_all($re, $this->contenu, $matches, PREG_SET_ORDER, 0);
        foreach(array_unique(array_column($matches, 1)) as $var) {
            $this->contenu = str_replace('{{'.$var.'}}', $donnees[$var], $this->contenu);
        }

        return $this;
    }

    /**
     * Rend le contenu html
     *
     * @return void
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