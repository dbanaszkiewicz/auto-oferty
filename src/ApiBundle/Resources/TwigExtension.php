<?php
/**
 * Created by PhpStorm.
 * User: damian
 * Date: 25.11.17
 * Time: 16:39
 */
namespace ApiBundle\Resources;

class TwigExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('price', array($this, 'priceFilter')),
            new \Twig_SimpleFilter('int', array($this, 'toInt')),
            new \Twig_SimpleFilter('array', array($this, 'toArray')),
        );
    }

    public function priceFilter($number)
    {
        $price = number_format($number, 2, ',', '.');

        return $price;
    }

    public function toInt($number)
    {
        return (int)$number;
    }

    public function toArray($object)
    {
        $a = [];
        foreach ($object as $k => $o) {
            if (is_object($o) or is_array($o)) {
                $a[$k] = $this->toArray($o);
            } else {
                $a[$k] = $o;
            }
        }
        return $a;
    }
}