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
        $user->setEnabled(true)->setConfirm(false)->setEmail("contact@funglobe.com")->setBirthDate(new \DateTime())->setRoles(["ROLE_ADMIN"])
            ->setFirstName("Admin")->setGender("Male")->setIsOnline(false)->setIsVip(false)->setType("Normal")->setPassword($password)->setUsername("admin");

        $em = $this->getDoctrine()->getManager();
        $exist = $em->getRepository('AppBundle:User')->findOneByemail($user->getEmail());
        if($exist==null)
        {
            $em->persist($user);
        }

        $em->flush();
        $em->detach($user);

        $user = new User();
        $password = $this->encodePassword(new User(), "member", $user->getSalt());
        $user->setEnabled(true)->setConfirm(false)->setEmail("info@funglobe.com")->setBirthDate(new \DateTime())->setRoles(["ROLE_MEMBER"])->setUsername("member")
            ->setFirstName("Member")->setGender("Femele")->setIsOnline(false)->setIsVip(false)->setType("Normal")->setPassword($password);

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

    /**
     * @Route("/reset-password", name="app_reset_password")
     *
     * @param string $email
     * @param string $token
     *
     * @return object
     */
    public function resetPasswordAction($email, $token)
    {
        $url = $this->get('request')->getSchemeAndHttpHost().$this->generateUrl('app_confirm_reset_password', ['token' => $token]);
        $translator = $this->get('translator');
        $subject = $translator->trans('resetting.email.subject', []);

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom(['support@funglobe.com' => "FunGlobe support"])
            ->setTo($email)
            ->setBody($this->renderView('email:reset-password.html.twig', array('resetUrl'=> $url)), 'text/html');

        $mailer = $this->get('mailer');

        $sent = $mailer->send($message);

        return $this->json(['message' => $sent], $sent ? 200 : 400);
    }

    /**
     * @Route("/confirm-reset-password", name="app_confirm_reset_password")
     *
     * @return object
     */
    public function confirmResetPasswordAction(Request $request)
    {
        $token = $request->get('token');

        return $this->json([], 200);
    }
}
