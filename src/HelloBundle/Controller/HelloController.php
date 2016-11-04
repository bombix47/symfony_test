<?php

namespace HelloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HelloController extends Controller
{
    public function indexAction($name,$sex)
    {
        switch ($sex) {
            case "h":
            $type="monsieur";
            break;
            case "f":
                $type="madame";
                break;
            default:
                $type="inconnu";
                break;
        }
        return $this->render('HelloBundle:Hello:index.html.twig',array('name'=>$name,'type'=>$type));
    }
}
