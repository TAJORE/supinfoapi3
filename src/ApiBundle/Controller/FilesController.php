<?php

namespace ApiBundle\Controller;


use AppBundle\Entity\AuthToken;
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
define('FILE_SIZE_MAX', 5*1024*1024);

class FilesController extends FOSRestController
{


    /**
     * @Rest\Post("/auth/upload")
     * @return Response
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Upload les photos de l'utilisateur",
     *  statusCodes={
     *     200="the query is ok",
     *     401= "The connection is required",
     *     403= "Access Denied"
     *
     *  },
     *  parameters={
     *     {"name"="id", "dataType"="integer", "required"=true, "description"="L'identifiant de l'utilisateur connecté "},
     *     {"name"="file", "dataType"="file", "required"=true, "description"="La photo"}
     *  }
     * )
     */
    public function uploadAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $id = $request->request->get("id");
        /** @var User $user */
        $user = $em->getRepository("AppBundle:User")->find($id);



        //$files = $request->files->get('file');

        //$files = $request->files->all()['file'];
        $files = $request->files->all();


        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $files['file'];



        $errors = null;

        if ($uploadedFile != null) {
            if ($uploadedFile->getClientSize() > FILE_SIZE_MAX) {
                $errors = 'file too  big ('.$uploadedFile->getClientSize().')';
            }
            $tab = explode('.', $uploadedFile->getClientOriginalName());
            $ext = $tab[count($tab) - 1];
            if (!preg_match("#pdf|docx|doc|png|jpg|gif|jpeg|bnp#", strtolower($ext))) {
                $errors = 'Extension   ('.$ext.') not  allow';;
            }
        }
        else
        {
            $errors ="File not  found";
        }




        if($errors==null)
        {
            try{

                //return $this->json(["resultat"=>$tab],400);
                $tab = explode('.', $uploadedFile->getClientOriginalName());
                $ext = $tab[count($tab) - 1];
                $file = new Files();
                $file->file = $uploadedFile;
                $fileExtension = $ext;
                $fileName = uniqid() .'.' .$fileExtension;
                $fileSize = $uploadedFile->getClientSize();
                $directory = "photo/user".$id;
                //$directory = "photo";
                $file->add($file->initialpath . $directory, $fileName);
                $photo = new UserPhoto();
                $photo->setCreateDate(new \DateTime());
                $photo->setHashname($fileName);
                $photo->setIsValid(true);
                $photo->setMimeType($fileExtension);
                $photo->setSize($fileSize);
                $photo->setName($uploadedFile->getClientOriginalName());
                $photo->setVisibility("private");
                $photo->setUser($user);
                $photo->setIsProfile(true);
                $src = $photo->path($id);
                $em->persist($photo);
                $em->flush();
                $em->detach($photo);

                $result = ["name" => $photo->getName(),"size" => $fileSize, "src"=> $src];
                return $this->json($result);
            }
            catch (Exception $ex)
            {
                return $this->json(["error"=>$ex->getMessage()],400);
            }

        }
         return $this->json(["error"=>$errors],400);
    }





    /**
     * @Rest\Post("/auth/webcam")
     * @return Response
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Upload les photos de l'utilisateur en utilisant la webcam",
     *  statusCodes={
     *     200="the query is ok",
     *     401= "The connection is required",
     *     403= "Access Denied"
     *
     *  },
     *  parameters={
     *     {"name"="id", "dataType"="integer", "required"=true, "description"="L'identifiant de l'utilisateur connecté "},
     *     {"name"="file", "dataType"="text", "required"=true, "description"="La photo"}
     *  }
     * )
     */
    public function webcamAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $id = $request->request->get("id");
        /** @var User $user */
        $user = $em->getRepository("AppBundle:User")->find($id);

        $contents = $request->request->get("file");
            try{

                $fileName = uniqid().".png";
                $file = new Files();
                $directory = "photo/user".$id;
                $initialDirectory = str_replace("//","/", str_replace("\\","/",$file->getAbsolutPath($file->initialpath).$directory));
                if(!is_dir($initialDirectory)){
                    if (false === @mkdir($initialDirectory, 0777, true)) {
                        throw new FileException(sprintf('Unable to create the "%s" directory', $directory));
                    }
                }

                $path = str_replace("\\","/",$file->getAbsolutPath($file->initialpath.$directory)).$fileName;
                $path = str_replace("//","/",$path);
                $encodedData = str_replace(' ','+',$contents);
                $decodedData = base64_decode($encodedData);
                $fp = fopen($path, 'w');
                fwrite($fp, $decodedData);
                fclose($fp);
                //"path"=>$path,

                $fileExtension = 'png';
                $fileSize = filesize($path);
                $photo = new UserPhoto();
                $photo->setCreateDate(new \DateTime());
                $photo->setHashname($fileName);
                $photo->setIsValid(true);
                $photo->setMimeType($fileExtension);
                $photo->setSize($fileSize);
                $photo->setName($fileName);
                $photo->setVisibility("private");
                $photo->setIsProfile(true);
                $photo->setUser($user);
                $src = $photo->path($id);
                $em->persist($photo);
                $em->flush();
                $em->detach($photo);
                $result = ["name" => $photo->getName(),"size" => $fileSize, "src"=> $src];
                return $this->json($result);
            }
            catch (Exception $ex)
            {
                return $this->json(["error"=>$ex->getMessage()],400);
            }
    }


}