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
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
class UserController extends FOSRestController
{



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
            $password = $this->encodePassword(new User(), $user->getPassword(), $user->getSalt());
            $user->setPassword($password);
        }
        $user = $this->fillUser($request,$user);
        $em->merge($user);
        $em->flush();
        $em->detach($user);

        $token = $this->authenticateUser($user);

        return $this->json($user);
    }



    // exeception for user not  found
    private function userNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
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


    private function invalidCredentials()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'Password or Login is bad'], Response::HTTP_BAD_REQUEST);
    }

}