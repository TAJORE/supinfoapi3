<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/save/client", name="app_oauth")
     */
    public function saveclientAction(Request $request)
    {
        $clientManager = $this->container->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setRedirectUris(array('http://www.funglobe.com'));
        $client->setAllowedGrantTypes(array('token', 'authorization_code'));
        $clientManager->updateClient($client);

        return $this->redirect($this->generateUrl('fos_oauth_server_authorize', array(
            'client_id'     => $client->getPublicId(),
            'redirect_uri'  => 'http://www.example.com',
            'response_type' => 'code'
        )));

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
    public function saveuserAction(Request $request)
    {
       $user = new User();
        $password = $this->encodePassword(new User(), "admin", $user->getSalt());
        $user->setEnabled(true)->setIsEmailVerified(false)->setEmail("contact@funglobe.com")->setBirthDate(new \DateTime())->setRoles(["ROLE_ADMIN"])
            ->setFirstName("Admin")->setGender("Male")->setIsOnline(false)->setIsVip(false)->setType("Normal")
            ->setPassword($password)->setUsername("admin")->setJoinDate(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $exist = $em->getRepository('AppBundle:User')->findOneByemail($user->getEmail());
        if($exist==null)
        {
            $em->persist($user);
        }

        $em->flush();
        $em->detach($user);

        //test user 2
        $user = new User();
        $password = $this->encodePassword(new User(), "Paul", $user->getSalt());
        $user->setEnabled(true)->setIsEmailVerified(false)->setEmail("contact2@funglobe.com")->setBirthDate(new \DateTime())->setRoles(["ROLE_ADMIN"])
            ->setFirstName("Paul")->setGender("Male")->setIsOnline(false)->setIsVip(false)->setType("Normal")
            ->setPassword($password)->setUsername("paul")->setJoinDate(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $exist = $em->getRepository('AppBundle:User')->findOneByemail($user->getEmail());
        if($exist==null)
        {
            $em->persist($user);
        }

        $em->flush();
        $em->detach($user);

        //test user 3
        $user = new User();
        $password = $this->encodePassword(new User(), "Melanie", $user->getSalt());
        $user->setEnabled(true)->setIsEmailVerified(false)->setEmail("contact3@funglobe.com")->setBirthDate(new \DateTime())->setRoles(["ROLE_ADMIN"])
            ->setFirstName("Melanie")->setGender("Female")->setIsOnline(false)->setIsVip(false)->setType("Normal")
            ->setPassword($password)->setUsername("melanie")->setJoinDate(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $exist = $em->getRepository('AppBundle:User')->findOneByemail($user->getEmail());
        if($exist==null)
        {
            $em->persist($user);
        }

        $em->flush();
        $em->detach($user);

        //test user 4
        $user = new User();
        $password = $this->encodePassword(new User(), "Adeline", $user->getSalt());
        $user->setEnabled(true)->setIsEmailVerified(false)->setEmail("contact4@funglobe.com")->setBirthDate(new \DateTime())->setRoles(["ROLE_ADMIN"])
            ->setFirstName("Adeline")->setGender("Female")->setIsOnline(false)->setIsVip(false)->setType("Normal")
            ->setPassword($password)->setUsername("adeline")->setJoinDate(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $exist = $em->getRepository('AppBundle:User')->findOneByemail($user->getEmail());
        if($exist==null)
        {
            $em->persist($user);
        }

        $em->flush();
        $em->detach($user);

        //test user 5
        $user = new User();
        $password = $this->encodePassword(new User(), "Lawrence", $user->getSalt());
        $user->setEnabled(true)->setIsEmailVerified(false)->setEmail("contact5@funglobe.com")->setBirthDate(new \DateTime())->setRoles(["ROLE_ADMIN"])
            ->setFirstName("Lawrence")->setGender("Male")->setIsOnline(false)->setIsVip(false)->setType("Normal")
            ->setPassword($password)->setUsername("lawrence")->setJoinDate(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $exist = $em->getRepository('AppBundle:User')->findOneByemail($user->getEmail());
        if($exist==null)
        {
            $em->persist($user);
        }

        $em->flush();
        $em->detach($user);

        //test user 6
        $user = new User();
        $password = $this->encodePassword(new User(), "Galadima", $user->getSalt());
        $user->setEnabled(true)->setIsEmailVerified(false)->setEmail("contact6@funglobe.com")->setBirthDate(new \DateTime())->setRoles(["ROLE_ADMIN"])
            ->setFirstName("Galadima")->setGender("Male")->setIsOnline(false)->setIsVip(false)->setType("System")
            ->setPassword($password)->setUsername("galadima")->setJoinDate(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $exist = $em->getRepository('AppBundle:User')->findOneByemail($user->getEmail());
        if($exist==null)
        {
            $em->persist($user);
        }

        $em->flush();
        $em->detach($user);


        //Test user6
        $user = new User();
        $password = $this->encodePassword(new User(), "member", $user->getSalt());
        $user->setEnabled(true)->setIsEmailVerified(false)->setEmail("info@funglobe.com")->setBirthDate(new \DateTime())->setRoles(["ROLE_MEMBER"])->setUsername("member")
            ->setFirstName("Member")->setGender("Female")->setIsOnline(false)->setIsVip(false)->setType("Normal")->setPassword($password);

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
}
