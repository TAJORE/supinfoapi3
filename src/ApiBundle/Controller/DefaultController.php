<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\AuthToken;
use AppBundle\Entity\Credentials;
use AppBundle\Entity\User;
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
        $service = $this->get('security.authorization_checker');
        if ($service->isGranted('ROLE_MEMBER') === FALSE) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getManager();
        $array = $em->getRepository("AppBundle:User")->findAll();
        return $this->json($array);
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






    // fonction pour enregister un utilisateur

    /**
     * @Rest\Post("/register")
     * @Rest\View
     * @ApiDoc(
     *  resource=true,
     *  description="Save a user ",
     *  statusCodes = {
     *      200 = "Updated (seems to be OK)",
     *      400 = "Bad request (see messages)",
     *      401 = "Unauthorized, you must login first",
     *      404 = "Not found",
     *  },
     *  parameters={
     *     {"name"="firtsname", "dataType"="string", "required"=true, "description"="user firstname "},
     *     {"name"="email", "dataType"="string", "required"=true, "description"="User email adresse"},
     *     {"name"="password", "dataType"="string", "required"=true, "description"="User password"},
     *     {"name"="lastName", "dataType"="string", "required"=false, "description"="User  last name"},
     *     {"name"="isOnline", "dataType"="boolean", "required"=true, "description"="user current  statut"},
     *     {"name"="birthDate", "dataType"="date", "required"=true, "description"="User  current  prosession"},
     *     {"name"="profession", "dataType"="string", "required"=false, "description"="Nom d'un utilisateur"},
     *     {"name"="type", "dataType"="string", "required"=true, "description"="User  type"},
     *     {"name"="relationshipStatus", "dataType"="string", "required"=false, "description"="User  relationship Status"},
     *     {"name"="joinReason", "dataType"="string", "required"=false, "description"="User  join reason"},
     *     {"name"="joinDate", "dataType"="datetime", "required"=false, "description"="Date where user signUp"},
     *     {"name"="isEmailVerified", "dataType"="boolean", "required"=true, "description"="Verify  email  adresse "},
     *     {"name"="isVip", "dataType"="boolean", "required"=true, "description"="privilege for user"},
     *     {"name"="gender", "dataType"="string", "required"=true, "description"="User gender"},
     *     {"name"="phones", "dataType"="array", "required"=false, "description"="User phones number"},
     *     {"name"="profileVisibility", "dataType"="array", "required"=false, "description"="List  autorisation options"}
     *  }
     * )
     */
    public function registerAction(Request $request)
    {
        $user =new User();
        $val = $request->request;
        $user = $this->fillUser($request, $user);
        $user->setPassword($val->get("password"));
        $password = $this->encodePassword(new User(), $user->getPassword(), $user->getSalt());
        $user->setConfirmPassword(md5($user->getPassword()));
        $user->setPassword($password);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        $em->detach($user);

        /* @var $user User */
        $user =$em->getRepository('AppBundle:User')->findOneByemail($user->getEmail());
        $token = $this->authenticateUser($user);
        return $this->json($this->getUser());
    }







    //action  pour authentifier un utilisateur
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"auth-token"})
     * @Rest\Post("/auth-tokens")
     *  resource=true,
     *  description="authentificate use. the login can be : email adresse or username ",
     *  statusCodes = {
     *      200 = "Updated (seems to be OK)",
     *      400 = "Bad request (see messages)",
     *      401 = "Unauthorized, you must login first",
     *      404 = "Not found",
     *  },
     *  parameters={
     *     {"name"="_username", "dataType"="string", "required"=true, "description"="User  name  or email  adress"},
     *     {"name"="_password", "dataType"="string", "required"=true, "description"="the password"}
     *  }
     * )
     */
    public function postAuthTokensAction(Request $request)
    {
        $val  =$request->request;
        $user = new User();
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository("AppBundle:User")->findOneBy(["username"=>$val->get('_username'),"confirmPassword"=>md5($val->get("_password"))],["id"=>"DESC"]);
        if(!$user)
        {
            $user = $em->getRepository("AppBundle:User")->findOneBy(["email"=>$val->get('_username'),"confirmPassword"=>md5($val->get("_password"))],["id"=>"DESC"]);
        }

        if(!$user){
            return $this->invalidCredentials();
        }


        $this->authenticateUser($user);
        return $this->json($this->getUser());
    }





    // authentifie un utilisateur et  cree une cle pour lui
    public function authenticateUser(UserInterface $user)
    {
        try {

            $tocken = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($tocken);
            $this->get('session')->set('_security_main',serialize($tocken));

            $authToken = new AuthToken();
            $authToken->setValue(base64_encode(random_bytes(50)));
            $authToken->setCreatedAt(new \DateTime('now'));
            $authToken->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($authToken);
            $em->flush();
            $em->detach($authToken);

            /** @var Session $session */
            $session = $this->get('session');

            $session->set("auth-current",$authToken);
            return $authToken;

        } catch (AccountStatusException $ex) {
            return $this->json($ex->getMessage());
        }
    }



    // encode le mot  de passe
    public function encodePassword($object, $password, $salt)
    {
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($object);
        $password = $encoder->encodePassword($password, $salt);

        return $password;
    }


    // charge un utilisateur avec les informations envoyes dans l'application (a completer pour une modfification)
    public  function  fillUser(Request $request, User $user)
    {
        $val = $request->request;
        $tab = explode("@",$val->get("email"));
        $username = $tab==null?null:$tab[0];

        // set  user with  application values
        $user->setEmail($val->get('email'))->setType($val->get('type'))
            ->setBirthDate($val->get('birthDate'))->setFirstName($val->get('firstname'))
            ->setGender($val->get('profession'))->setUsernameCanonical($username)->setEmailCanonical($val->get('email'));

        $user->setEnabled(true)->setIsEmailVerified(false)->setBirthDate(new \DateTime())->setRoles(["ROLE_MEMBER"])
            ->setUsername($username)->setIsOnline(false)->setIsVip(false)->setJoinDate(new \DateTime());
        return $user;
    }


    // exeception for user not  found
    private function userNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
    }


    private function invalidCredentials()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'Password or Login is bad'], Response::HTTP_BAD_REQUEST);
    }



}