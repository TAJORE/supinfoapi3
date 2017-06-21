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
class UserController extends FOSRestController
{


    /**
     * @Rest\Put("/auth/user/{id}")
     *  resource=true,
     *  description="User Update action ",
     *  statusCodes = {
     *      200 = "Updated (seems to be OK)",
     *      400 = "Bad request (see messages)",
     *      401 = "Unauthorized, you must login first",
     *      404 = "Not found",
     *  },
     *  parameters={
     *     {"name"="username", "dataType"="string", "required"=true, "description"="User  name  or email  adress"},
     *     {"name"="password", "dataType"="string", "required"=true, "description"="the password"}
     *  }
     * )
     */
    public function updateUserAction(Request $request)
    {

        //you  can continious if you have a good privileges
        $this->isgrantUser("ROLE_MEMBER");

        $helper = new HelpersController();
        $em =$this->getDoctrine()->getManager();
        /* @var $user User */
        $user =$em->getRepository('AppBundle:User')
            ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire


        if (empty($user)) {
            return $this->userNotFound();
        }

        // Si l'utilisateur veut changer son mot de passe
        if (!empty($user->getPlainPassword())) {
            $password = $this->encodePassword(new User(), $user->getPassword(), $user->getSalt());
            $user->setPassword($password);
        }
        $user = $helper->fillUser($request,$user);
        $em->merge($user);
        $em->flush();
        $em->detach($user);

        $token = $this->authenticateUser($user);

        return $this->json($user);
    }


    // action for lagout
    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/auth/auth-tokens/{id}")
     */
    public function removeAuthTokenAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $authToken = $em->getRepository('AppBundle:AuthToken')
            ->find($request->get('id'));
        /* @var $authToken AuthToken */

        $connectedUser = $this->get('security.token_storage')->getToken()->getUser();

        if ($authToken && $authToken->getUser()->getId() === $connectedUser->getId()) {
            $em->remove($authToken);
            $em->flush();

            /** @var Session $session */
            $session = $this->get('session');
            $session->set("auth-current",null);

        } else {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException();
        }
    }


    // Recuper le auth correspondant au  user app
    private function isgrantUser($role){
        if(!$this->getUser())
        {
            throw new  AuthenticationException();
        }
        $service = $this->get('security.authorization_checker');
        if ($service->isGranted($role) === FALSE) {
            throw new AccessDeniedException();
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
        $val = $request->request;
        $tab = explode("@",$val->get("email"));
        $username = $tab==null?null:$tab[0];

        // set  user with  application values
        $user->setEmail($val->get('email'))->setType($val->get('type'))->setGender($val->get('gender'))
            ->setBirthDate($val->get('birthDate'))->setFirstName($val->get('firstname'))->setCountry($val->get('country'))
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

}