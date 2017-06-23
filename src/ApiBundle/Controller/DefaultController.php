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
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class DefaultController extends FOSRestController
{


    // Fonction pour initialiser le user systeme et le token de base

    /**
     * @Rest\Get("/app")
     * @return Response
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Récupérer le token de base pour l'aplication ",
     *  statusCodes={
     *     200="Retourné quand tout est OK !"
     *  }
     * )
     */
    public function getAppAuthAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        /** @var User $app */
        $app = $em->getRepository("AppBundle:User")->findOneByemail("app@funglobe.com");
        if(!$app)
        {
            $app =  $this->init()->getUser();
        }
        /** @var AuthToken $authtoken */
        $authtoken = $em->getRepository("AppBundle:AuthToken")->findOneBy(["user"=>$app],["id"=>"DESC"]);

        $authtoken->setCreatedAt(new \DateTime());

        $em->flush();

        return $this->json($authtoken);
    }



    /**
     * @Rest\Get("/admin/test")
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
    public function testAction()
    {
        $em = $this->getDoctrine()->getManager();
        $array = $em->getRepository("AppBundle:User")->findAll();
        return $this->json($array);
    }




    // fonction pour enregister un utilisateur

    /**
     * @Rest\Post("/auth/register")
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
        $this->authenticateUser($user);
        return $this->json($this->getUser());
    }







    //action  pour authentifier un utilisateur
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"auth-token"})
     * @Rest\Post("/auth/login")
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
    public function loginAction(Request $request)
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


    // Recuper le auth correspondant au  user app
    private function isgrantUser($role){

        $service = $this->get('security.authorization_checker');
        if ($service->isGranted($role) === FALSE) {
            throw new AccessDeniedException();
        }
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

            $auths = $em->getRepository("AppBundle:AuthToken")->findBy(["user"=>$this->getUser()],["id"=>"DESC"]);
            if($auths!=null)
            {
                /** @var AuthToken $auth */
                foreach($auths as $auth)
                {
                    $em->remove($auth);
                    $em->flush();
                }
            }

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
    private  function  fillUser(Request $request, User $user)
    {
        $log = $logger = $this->get('logger');

        $val = $request->request;
        $tab = explode("@",$val->get("email"));
        $username = $tab==null?null:$tab[0];
        // set  user with  application values
        $user->setEmail($val->get('email'))->setType($val->get('type'))
            ->setBirthDate($val->get('birthDate'))->setFirstName($val->get('firstname'))->setCountry($val->get('country'))
            ->setGender($val->get('profession'))->setUsernameCanonical($username)->setEmailCanonical($val->get('email'));

        $user->setEnabled(true)->setIsEmailVerified(false)->setBirthDate(new \DateTime())->setRoles(["ROLE_MEMBER"])
            ->setUsername($username)->setIsOnline(true)->setIsVip(false)->setJoinDate(new \DateTime());

        $user->setGender($val->get('gender'));

        //quelques logs pour verifier les valeurs des parametres
        $log->debug("The user gender is ".$val->get('gender'));
        $log->debug("The user email is ".$val->get('email'));
        $log->debug("The user himself is is ".$user);

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



    // Initialise  l'utilisateur  système
    public function  init()
    {
        $user = new User();
        $user->setPlainPassword("app");
        $password = $this->encodePassword(new User(), $user->getPlainPassword(), $user->getSalt());
        $user->setConfirmPassword(md5($user->getPassword()));
        $user->setPassword($password);
        $user->setConfirmPassword(md5($user->getPlainPassword()))->setCountry("Belgique");
        $user->setEnabled(true)->setIsEmailVerified(true)->setEmail("app@funglobe.com")->setBirthDate(new \DateTime())->setRoles(["ROLE_APP"])
            ->setFirstName("App")->setGender("M")->setIsOnline(false)->setIsVip(true)->setType("System")->setUsername("app")->setJoinDate(new \DateTime());

        $authToken = new AuthToken();
        $authToken->setValue(base64_encode(random_bytes(50)));
        $authToken->setCreatedAt(new \DateTime('now'));

        $em = $this->getDoctrine()->getManager();
        $exist = $em->getRepository('AppBundle:User')->findOneByemail($user->getEmail());
        if($exist !=null)
        {
            $user =$exist;
        }
        $authToken->setUser($user);

        $em->persist($authToken);
        $em->flush();
        $em->detach($authToken);
        return $authToken;
    }


}