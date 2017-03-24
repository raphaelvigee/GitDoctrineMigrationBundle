<?php

namespace RaphaelVigee\GitDoctrineMigrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('RaphaelVigeeGitDoctrineMigrationBundle:Default:index.html.twig');
    }
}
