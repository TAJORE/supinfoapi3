<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AuthToken;
use AppBundle\Entity\Files;
use AppBundle\Entity\Geolite;
use AppBundle\Entity\PasswordReset;
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

class DefaultController extends FOSRestController
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
     * @Route("/test/{email}", name="testpage")
     */
    public function testAction(Request $request, $email)
    {

        $code = $this->sendMail($email, $this->getParameter('mailer_user'), "I am  just  a test", "good work");
        // replace this example code with whatever you need
        return $this->json("Veillez consulter la boite mail <a href=''>". $email."<a>");
    }



    public  function sendMail($to, $from, $body,$subjet)
    {
        // ->setReplyTo('xxx@xxx.xxx')

        $message = \Swift_Message::newInstance()
            ->setSubject($subjet)
            ->setFrom($from) // 'info@achgroupe.com' => 'Achgroupe : Course en ligne '
            ->setTo($to)
            ->setBody($body)
            //'MyBundle:Default:mail.html.twig'
            ->setContentType('text/html');
        return $this->get('mailer')->send($message);

    }
    //fonction pour inialiser quelques utilisateurs
    public function saveUser()
    {

       $user = new User();
        $user->setPlainPassword("admin");
        $password = $this->encodePassword(new User(), $user->getPlainPassword(), $user->getSalt());
        $user->setConfirmPassword(hash('sha256',$user->getPassword()));
        $user->setPassword($password);
        $user->setConfirmPassword(hash('sha256',$user->getPlainPassword()))->setCountry("BE");
        $user->setEnabled(true)->setIsEmailVerified(false)->setEmail("contact@funglobe.com")->setBirthDate(new \DateTime())->setRoles(["ROLE_ADMIN"])
            ->setFirstName("Admin")->setGender("M")->setIsOnline(false)->setIsVip(false)->setType("System")->setUsername("admin")->setJoinDate(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $exist = $em->getRepository('AppBundle:User')->findOneByemail($user->getEmail());
        if($exist==null)
        {
            $em->persist($user);
        }

        $em->flush();
        $em->detach($user);

        $user = new User();
        $user->setPlainPassword("moderator");
        $password = $this->encodePassword(new User(), $user->getPlainPassword(), $user->getSalt());
        $user->setConfirmPassword(hash('sha256',$user->getPassword()));
        $user->setPassword($password);
        $user->setConfirmPassword(hash('sha256',$user->getPlainPassword()))->setCountry("CA");
        $user->setEnabled(true)->setIsEmailVerified(false)->setEmail("info@funglobe.com")->setBirthDate(new \DateTime())->setRoles(["ROLE_MODERATOR"])->setUsername("moderator")
            ->setFirstName("Moderator")->setGender("F")->setIsOnline(false)->setIsVip(false)->setType("System")->setJoinDate(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $exist = $em->getRepository('AppBundle:User')->findOneByemail($user->getEmail());
        if($exist==null)
        {
            $em->persist($user);
        }

        $em->flush();
        $em->detach($user);
    }

    // fonction  pour creer l'application et le token de base ET Creer quelques user
    /**
     * @Route("/init", name="app_init")
     */
    public function initAction(Request $request)
    {
        $authtoken = $this->init();
        $this->saveUser();
        return $this->json($authtoken);
    }


    // Initialise l'utilisateur systèmes
    public function  init()
    {
        $user = new User();
        $user->setPlainPassword("app");
        $password = $this->encodePassword(new User(), $user->getPlainPassword(), $user->getSalt());
        $user->setConfirmPassword(hash('sha256',$user->getPassword()));
        $user->setPassword($password);
        $user->setConfirmPassword(hash('sha256',$user->getPlainPassword()))->setCountry("DE");
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



    // encode le mot  de passe
    public function encodePassword($object, $password, $salt)
    {
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($object);
        $password = $encoder->encodePassword($password, $salt);

        return $password;
    }


    //Charge toutes les villes et pays contenu dans le fichier passe en parametre dans une liste
    /**
     * @Route("/init/location", name="geolitblock")
     */
    public function GeolitLocation()
    {
        $files = new Files();
        $directory = "Geolite";
        $initialDirectory = str_replace("//","/", str_replace("\\","/",$files->getAbsolutPath($directory)));
        $file_name  =$initialDirectory."GeoLiteCity-Location.csv";
        $file = fopen($file_name, "r+");
        $em = $this->getDoctrine()->getManager();
        //$countRow = substr_count( $file, "\n" );
        $begin =1;
        while ($row = fgets($file)) {
            if($begin>2)
            {
                $geolite =new Geolite();
                $tab = explode(",",$row);
                $geolite->setCountry($tab[1]);
                $geolite->setRegion($tab[2]);
                $geolite->setCity($tab[3]);
                $geolite->setPostalCode($tab[4]);
                $geolite->setLartitude($tab[5]);
                $geolite->setLongitude($tab[6]);
                $exit = $em->getRepository("AppBundle:Geolite")->findOneBycity($geolite->getCity());
                if(!is_object($exit))
                {
                    $em->persist($geolite);
                    $em->flush();
                }
            }
            //var_dump("city : ".$geolite->getCity()." | region :".$geolite->getRegion())
            $begin++;
        }
        fclose($file);
        $array =[];


        $array['items'] = $em->getRepository("AppBundle:Geolite")->findAll();
        return $this->render("AppBundle:Default:index.html.twig",$array);
    }


}
