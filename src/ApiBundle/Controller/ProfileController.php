<?php

namespace ApiBundle\Controller;


use AppBundle\Entity\AuthToken;
use AppBundle\Entity\Credentials;
use AppBundle\Entity\Files;
use AppBundle\Entity\User;
use AppBundle\Entity\UserPhoto;
use AppBundle\Tools\HelpersController;
use AppBundle\Tools\SecurityController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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

class ProfileController extends FOSRestController
{


    /**
     * @Rest\Post("/auth/user/matches")
     * @return Response
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Upload les photos de l'utilisateur",
     *  statusCodes={
     *     200="the query is ok",
     *     401= "The connection is required",
     *     403= "Access Denied"
     *
     *  },
     *  parameters={
     *     {"name"="id", "dataType"="integer", "required"=true, "description"="L'identifiant de l'utilisateur connecté "},
     *  }
     * )
     */
    public function matchesAction(Request $request)
    {

    }


    // retourne la liste des utilisateur vip
    public function getVips(){
        $em = $this->getDoctrine()->getManager();
        $data = ["vip"=>true];
        $list = $em->getRepository("AppBundle:User")->getVips($data);
        return $list;
    }

    // retourne la liste des demandes d'amitier
    public function getApplicant(User $user){
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository("AppBundle:Request")->findBy(["applicant"=>$user],["createDate"=>"DESC"]);
        return $list;
    }

    // retourne la liste des invitations
    public function getReceiver(User $user){
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository("AppBundle:Request")->findBy(["receiver"=>$user],["createDate"=>"DESC"]);
        return $list;
    }


    // retourne la liste des messages recues du  user connecté
    public function getRecievedMessage(User $user){
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository("AppBundle:UserMessage")->findBy(["receiver"=>$user],["readDate"=>"DESC"]);
        return $list;
    }


    // retourne la liste des messages envoyées du  user connecté
    public function getSendMessage(User $user){
        $em = $this->getDoctrine()->getManager();
        $data = ["sender_id"=>$user->getId()];
        $list = $em->getRepository("AppBundle:UserMessage")->getSendMessage($data);
        return $list;
    }


}