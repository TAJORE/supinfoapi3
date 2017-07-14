<?php

namespace ApiBundle\Controller;


use AppBundle\Entity\AuthToken;
use AppBundle\Entity\CityFile;
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
     * @Rest\Get("/auth/user/base")
     * @return Response
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Retourne toutes les éléments de bases pour la partie user",
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
    public function baseprofileAction(Request $request)
    {

        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();

        /** @var User $user */
        $user = $em->getRepository("AppBundle:User")->find($id);
        $array =[];
       /* $array=[
                "applicants"=>$this->getApplicant($user,$em),
                "recievers"=>$this->getReceiver($user,$em),
                "recieveMessages"=>$this->getRecievedMessage($user,$em),
                "sendMessages"=>$this->getSendMessage($user,$em),
                "photos"=>$this->getPhotos($user,$em),
                "profilePhotos"=>$this->getProfilePhotos($user,$em),
                "config"=>$this->getConfig($user,$em),
                "users"=>$this->getCompleteProfile($em),
                "vips"=>$this->getCompleteProfileVips($em),
               ];
       */
        return $this->json($array);
    }


    /**
     * @Rest\Get("/auth/user/city")
     * @return Response
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Retourne la liste des villes d'un pays",
     *  statusCodes={
     *     200="the query is ok",
     *     401= "The connection is required",
     *     403= "Access Denied"
     *
     *  },
     *  parameters={
     *     {"name"="country", "dataType"="string", "required"=true, "description"="Le pays à filtrer"},
     *  }
     * )
     */
    public function matchCityAction(Request $request)
    {

        return $this->json(["test"=>"un text"]);
        $country = $request->get("country");
        $em = $this->getDoctrine()->getManager();

        $cityFile =new CityFile();
        $cityFile->fill("dist","city.csv");
        return $this->json($cityFile->getCityByCountry($country));
    }


    // retourne la liste des utilisateur vip
    public function getVips(\Doctrine\Common\Persistence\ObjectManager $em){
        $data = ["vip"=>true];
        $list = $em->getRepository("AppBundle:User")->getVips($data);
        return $list;
    }

    // retourne la liste des demandes d'amitier
    public function getApplicant(User $user,\Doctrine\Common\Persistence\ObjectManager $em){
        $list = $em->getRepository("AppBundle:Request")->findBy(["applicant"=>$user],["createDate"=>"DESC"]);
        return $list;
    }

    // retourne la liste des invitations
    public function getReceiver(User $user,\Doctrine\Common\Persistence\ObjectManager $em){
        $list = $em->getRepository("AppBundle:Request")->findBy(["receiver"=>$user],["createDate"=>"DESC"]);
        return $list;
    }


    // retourne la liste des messages recues du  user connecté
    public function getRecievedMessage(User $user,\Doctrine\Common\Persistence\ObjectManager $em){
        $list = $em->getRepository("AppBundle:UserMessage")->findBy(["receiver"=>$user],["readDate"=>"DESC"]);
        return $list;
    }


    // retourne la liste des messages envoyées du  user connecté
    public function getSendMessage(User $user,\Doctrine\Common\Persistence\ObjectManager $em){
        $data = ["sender_id"=>$user->getId()];
        $list = $em->getRepository("AppBundle:UserMessage")->getSendMessage($data);
        return $list;
    }


    // retourne la liste des photos du user connecté
    public function getPhotos(User $user,\Doctrine\Common\Persistence\ObjectManager $em){
        $list = $em->getRepository("AppBundle:UserPhoto")->findBy(["user"=>$user],["createDate"=>"DESC"]);
        return $list;
    }

    // retourne la liste des photos de profile du user connecté
    public function getProfilePhotos(User $user, \Doctrine\Common\Persistence\ObjectManager $em){
        $list = $em->getRepository("AppBundle:UserPhoto")->findBy(["user"=>$user,"isProfile"=>true],["updateDate"=>"DESC"]);
        return $list;
    }

    // retourne les paraemtres de recherches du  user connecté
    public function getConfig(User $user, \Doctrine\Common\Persistence\ObjectManager $em){
        $list = $em->getRepository("AppBundle:SearchCriteria")->findOneBy(["user"=>$user],["createDate"=>"DESC"]);
        return $list;
    }

    // retourne le profile  du  user connecté
    public function getProfile(User $user, \Doctrine\Common\Persistence\ObjectManager $em){
        $list = $em->getRepository("AppBundle:Profile")->findOneBy(["user"=>$user],["createDate"=>"DESC"]);
        return $list;
    }


    // retourne la liste des utilisateurs
    public function getUsers(\Doctrine\Common\Persistence\ObjectManager $em){
        $list = $em->getRepository("AppBundle:User")->findBy(["createDate"=>"DESC"]);
        return $list;
    }

    // retourne la liste des users avec leur profile et  leurs photos
    public function getCompleteProfile(\Doctrine\Common\Persistence\ObjectManager $em){

        $list =[];
        $users = $em->getRepository("AppBundle:User")->findBy(["joinDate"=>"DESC"]);

        /** @var User $user */
        foreach($users as $user)
        {
            $array = ["user"=>$user,
                "profile"=>$em->getRepository("AppBundle:Profile")->findOneBy(["user"=>$user],["createDate"=>"DESC"]),
                "photos"=>$em->getRepository("AppBundle:UserPhoto")->findBy(["user"=>$user],["createDate"=>"DESC"])];
            $list[] = $array;
        }
        return $list;
    }

    // retourne la liste des users vips avec leur profile et  leurs photos
    public function getCompleteProfileVips(\Doctrine\Common\Persistence\ObjectManager $em){

        $list =[];
        $data = ["vip"=>true];
        $users = $em->getRepository("AppBundle:User")->getVips($data);

        /** @var User $user */
        foreach($users as $user)
        {
            $array = ["user"=>$user,
                "profile"=>$em->getRepository("AppBundle:Profile")->findOneBy(["user"=>$user],["createDate"=>"DESC"]),
                "photos"=>$em->getRepository("AppBundle:UserPhoto")->findBy(["user"=>$user],["createDate"=>"DESC"])];
            $list[] = $array;
        }
        return $list;
    }

}