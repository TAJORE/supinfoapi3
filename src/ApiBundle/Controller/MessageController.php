<?php

namespace ApiBundle\Controller;


use AppBundle\Entity\AuthToken;
use AppBundle\Entity\Credentials;
use AppBundle\Entity\User;
use AppBundle\Tools\HelpersController;
use AppBundle\Tools\SecurityController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use  Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
class MessageController extends FOSRestController
{


    /**
     * @Rest\Get("/auth/Message")
     * @return Response
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Récupérer la liste des utilisateurs",
     *  statusCodes={
     *     200="the query is ok",
     *     401= "The connection is required",
     *     403= "Access Denied"
     *
     *  },
     *  parameters={
     *     {"name"="utilisateur_id", "dataType"="integer", "required"=true, "description"="Représente l'identifiant de l'administrateur à ajouter pour la classe"}
     *  }
     * )
     */
    public function indexAction(Request $request)
    {

        //you  can continious if you have a good privileges
        //$this->isgrantUser("ROLE_MODERATOR");

        //$request->headers->set("X-Auth-token",$security->getAppAuth()->setValue());

        $em = $this->getDoctrine()->getManager();
        $array = $em->getRepository("AppBundle:Message")->findAll();
        return $this->json($array);
    }

}