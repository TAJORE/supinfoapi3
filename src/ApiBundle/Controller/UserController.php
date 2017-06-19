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
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use  Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserController extends FOSRestController
{
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
        try{
            $user =new User();
            $val = $request->request;
            $user = $this->fillUser($request, $user);
           // $password = $this->encodePassword(new User(), $user->getPassword(), $user->getSalt());
            $encoder = $this->get('security.password_encoder');
            // le mot de passe en claire est encodé avant la sauvegarde
            $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($encoded);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $em->detach($user);
            $credential = new Credentials();
            $credential->setLogin($user->getEmail());
            $credential->setPassword($user->getPlainPassword());
            $token = $this->authentification($credential);
            return $this->json($user);
        }
        catch(Exception $ex)
        {
            $this->json($ex);
        }

    }


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




    /**
     * @Rest\Get("/test")
     *  resource=true,
     *  description="test route ",
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
    public function testAction(Request $request)
    {

       try{
           return ['admin'=>"me"];
       }
       catch(Exception $ex)
       {
           return $this->json($ex);
       }
    }


    /**
     * @Rest\Put("/user/{id}")
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
        $em =$this->getDoctrine()->getManager();
        /* @var $user User */
        $user =$em->getRepository('AppBundle:User')
            ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire


        if (empty($user)) {
            return $this->userNotFound();
        }

        // Si l'utilisateur veut changer son mot de passe
        if (!empty($user->getPlainPassword())) {
            $encoder = $this->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($encoded);
        }
        $user = $this->fillUser($request,$user);
        $em->merge($user);
        $em->flush();
        $em->detach($user);
        $credential = new Credentials();
        $credential->setLogin($user->getEmail());
        $credential->setPassword($user->getPlainPassword());
        $token = $this->authentification($credential);

        return $this->json($user);
    }



    // exeception for user not  found
    private function userNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
    }



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
        $credentials = new Credentials();
        $credentials->setLogin($val->get('_username'));
        $credentials->setPassword($val->get('_password'));
        return $this->authentification($credentials);
    }


    // authentifcation function
    public function authentification(Credentials $credentials)
    {

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')
            ->findOneByEmail($credentials->getLogin());

        if (!$user) { // L'utilisateur n'existe pas
            $user = $em->getRepository('AppBundle:User')
                ->findOneByUsername($credentials->getLogin());
            if (!$user) {
                return $this->invalidCredentials();
               //return $user;
            }
        }

        $encoder = $this->get('security.password_encoder');
        $isPasswordValid = $encoder->isPasswordValid($user, $credentials->getPassword());

        if (!$isPasswordValid) { // Le mot de passe n'est pas correct
            return $this->invalidCredentials();
            //return $credentials->getPassword();
        }

        $authToken = new AuthToken();
        $authToken->setValue(base64_encode(random_bytes(50)));
        $authToken->setCreatedAt(new \DateTime('now'));
        $authToken->setUser($user);

        $em->persist($authToken);
        $em->flush();
        $em->detach($authToken);

        return $authToken;
    }

    private function invalidCredentials()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'Invalid credentials'], Response::HTTP_BAD_REQUEST);
    }



    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/auth-tokens/{id}")
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
        } else {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException();
        }
    }


}