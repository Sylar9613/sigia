<?php
/**
 * Created by PhpStorm.
 * User: Arian-PC
 * Date: 24/01/2020
 * Time: 15:26 PM
 */

namespace App\Twig;


class Project_Twig_Extension extends \Twig\Extension\AbstractExtension implements \Twig\Extension\GlobalsInterface
{
    public function getGlobals()
    {
        return [
            'tema' => 'white',
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('lipsum', 'generate_lipsum'),
        ];
    }

    public function getFilters()
    {
        return [
            new \Twig\TwigFilter('rot13', 'str_rot13'),
        ];
    }
}