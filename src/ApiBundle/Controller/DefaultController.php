<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use  Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
        $em = $this->getDoctrine()->getManager();
        $array = $em->getRepository("AppBundle:User")->findAll();
        return json_encode($array);
    }

    /**
     * @Rest\Get("/demo")
     * @return Response
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Petit demo  de l'api",
     *  statusCodes={
     *     200="Retourné quand tout est OK !"
     *  },
     *  parameters={
     *     {"name"="aatribut1", "dataType"="integer", "required"=true, "description"="description d'attribut  1"},
     *     {"name"="aatribut2", "dataType"="string", "required"=true, "description"="description d'attribut  2"},
     *     {"name"="aatribut3", "dataType"="array", "required"=true, "description"="description d'attribut  3"},
     *     {"name"="aatribut4", "dataType"="boolean", "required"=true, "description"="description d'attribut  4"}
     *  }
     * )
     */
    public function getDemosAction()
    {
        $data = array("hello" => "world");
        $view = $this->view($data);
        return $this->handleView($view);
    }


    /**
     * @Rest\Get("/login")
     * @return Response
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Petit demo  de l'api",
     *  statusCodes={
     *     200="Retourné quand tout est OK !"
     *  },
     *  parameters={
     *     {"name"="test", "dataType"="float", "required"=true, "description"="description d'attribut  1"}
     *  }
     * )
     */
    public function getuserconnectedction()
    {
        $user = $this->get('security.context')->getToken()->getUser(); //or
        $user = $this->getUser();

        //...
        // Do something with the fully authenticated user.
        // ...
    }

    //Check user grants

    /**
     * @Rest\Get("/grant")
     * @return Response
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Petit demo  de l'api",
     *  statusCodes={
     *     200="Retourné quand tout est OK !"
     *  },
     *  parameters={
     *     {"name"="user1", "dataType"="array", "required"=true, "description"="description d'attribut  1"}
     *  }
     * )
     */
    public function getGrantuserAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === FALSE) {
            throw new AccessDeniedException();
        }

        // ...
    }
}