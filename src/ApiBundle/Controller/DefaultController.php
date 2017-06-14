<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends FOSRestController
{
    /**
     * @Rest\Get("/users")
     * @return Response
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Récupérer la liste des utilisateurs",
     *  statusCodes={
     *     200="Retourné quand tout est OK !"
     *  },
     *  parameters={
     *     {"name"="utilisateur_id", "dataType"="integer", "required"=true, "description"="Représente l'identifiant de l'administrateur à ajouter pour la classe"}
     *  }
     * )
     */
    public function indexAction()
    {
        $array = ['id' => 1, 'name' => 'Danick  takam', 'email' => 'tericcabrel@yahoo.com'];

        return $array;
    }
}