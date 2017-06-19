<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use FOS\OAuthServerBundle\Entity\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }


    /**
     * @Route("/int", name="app_oauth")
     */
    public function saveAppclientIdAction(Request $request)
    {
        /** @var User $user */
        $user =  $this->saveAppUser()['user'];
        $password =  $this->saveAppUser()['password'];
        $em =$this->getDoctrine()->getManager();
        /** @var Client $client */
        $client = $em->getRepository('AppBundle:Client')->findOneBy([]);
        if($client==null)
        {
            $clientManager = $this->container->get('fos_oauth_server.client_manager.default');
            /** @var Client $client */
            $client = $clientManager->createClient();
            $client->setRedirectUris(array('http://www.funglobe.com'));
            $client->setAllowedGrantTypes(array('token', 'authorization_code'));
            //$client->setAllowedGrantTypes(array('password', 'refresh_token'));
            $clientManager->updateClient($client);
            $client->getSecret();
        }

        $url =  $request->getSchemeAndHttpHost().$this->generateUrl('homepage');
        $loginroute = $url."oauth/v2/token?client_id=".$user->getId()."_".$client->getRandomId()."&client_secret=".$client->getSecret()."&grant_type=password&username=".$user->getUsername()."&password=".$password;
        //var_dump($loginroute);
        //die();
       return  $this->redirect($loginroute);

            /*$this->redirect($this->generateUrl('homepage', array(
            'client_id'     => $client->getPublicId(),
            'redirect_uri'  => 'http://www.example.com',
            'response_type' => 'code'
        )));*/

    }

    public function encodePassword($object, $password, $salt)
    {
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($object);
        $password = $encoder->encodePassword($password, $salt);

        return $password;
    }

    /**
     * @Route("/save/user", name="app_fos")
     */
    public function saveUserAction(Request $request)
    {
       $user = new User();
        $user->setPlainPassword("admin");
        $password = $this->encodePassword(new User(), $user->getPlainPassword(), $user->getSalt());
        $user->setPassword($password);
        $user->setEnabled(true)->setIsEmailVerified(false)->setEmail("contact@funglobe.com")->setBirthDate(new \DateTime())->setRoles(["ROLE_ADMIN"])
            ->setFirstName("Admin")->setGender("Male")->setIsOnline(false)->setIsVip(false)->setType("Normal")->setUsername("admin")->setJoinDate(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $exist = $em->getRepository('AppBundle:User')->findOneByemail($user->getEmail());
        if($exist==null)
        {
            $em->persist($user);
        }

        $em->flush();
        $em->detach($user);

        $user = new User();
        //$password = $this->encodePassword(new User(), "member", $user->getSalt());
        //$user->setPlainPassword("member");
        //$encoder = $this->get('security.password_encoder');
        // le mot de passe en claire est encodé avant la sauvegarde
        //$encoded = $encoder->encodePassword($user, $user->getPlainPassword());
        //$user->setPassword($encoded);
        $user->setPlainPassword("member");
        $password = $this->encodePassword(new User(), $user->getPlainPassword(), $user->getSalt());
        $user->setPassword($password);
        $user->setEnabled(true)->setIsEmailVerified(false)->setEmail("info@funglobe.com")->setBirthDate(new \DateTime())->setRoles(["ROLE_MEMBER"])->setUsername("member")
            ->setFirstName("Member")->setGender("Femele")->setIsOnline(false)->setIsVip(false)->setType("Normal")->setJoinDate(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $exist = $em->getRepository('AppBundle:User')->findOneByemail($user->getEmail());
        if($exist==null)
        {
            $em->persist($user);
        }

        $em->flush();
        $em->detach($user);
        return $this->json($user);
    }




    public function saveAppUser()
    {
        $user = new User();
        $originpassword = "app";
        $password = $this->encodePassword(new User(), $originpassword, $user->getSalt());
        $user->setEnabled(true)->setIsEmailVerified(true)->setEmail("app@funglobe.com")->setBirthDate(new \DateTime())->setRoles(["ROLE_ADMIN"])
            ->setFirstName("App")->setGender("Male")->setIsOnline(false)->setIsVip(true)->setType("Normal")->setPassword($password)->setUsername("app")->setJoinDate(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $exist = $em->getRepository('AppBundle:User')->findOneByemail($user->getEmail());
        if($exist==null)
        {
            $em->persist($user);
            $em->flush();
            $em->detach($user);
        }
        else
        {
            $user = $exist;
        }
        return ["user"=>$user,"password"=>$originpassword];
    }

    public function authenticateUser(UserInterface $user)
    {
        try {

            $tocken = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($tocken);
            $this->get('session')->set('_security_main',serialize($tocken));

        } catch (AccountStatusException $ex) {
            //var_dump($ex->getMessage());
        }
    }

    /**
     * @Route("/auth", name="admin_auth")
     */
    public function authAction(Request $request)
    {
        /** @var User $user */
        $user =  $this->saveAppUser()['user'];
        $this->authenticateUser($user);
       return $this->json($this->getUser());
    }





}
