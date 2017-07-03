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
     *  }
     * )
     */
    public function uploadAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        //$files = $request->files->get('file');

        $files = $request->files->all()['file'];
        return $this->json(["success"=>$files]);

        $errors = null;
        if (sizeof($files) > 0) {
            /** @var UploadedFile $uploadedFile */
            foreach ($files as $uploadedFile) {
                if ($uploadedFile != null) {
                    if ($uploadedFile->getClientSize() > FILE_SIZE_MAX) {
                        $errors = 'file too  big ('.$uploadedFile->getClientSize().')';
                        break;
                    }
                    $tab = explode('.', $uploadedFile->getClientOriginalName());
                    $ext = $tab[count($tab) - 1];
                    if (!preg_match("#pdf|docx|doc|png|jpg|gif|jpeg|bnp#", strtolower($ext))) {
                        $errors = 'Extension   ('.$ext.') not  allow';;
                        break;
                    }
                }
                else
                {
                    $errors ="File not  found";
                    break;
                }
            }
        }
        else
        {
            $errors ="File not  found";
        }

        if($errors==null)
        {
            $id = $this->getUser()->getId();
            /** @var User $user */
            $user = $em->getRepository("AppBundle:User")->find($id);

            $result=null;
            /** @var UploadedFile $uploadedFile */
            foreach ($files as $uploadedFile) {
                $tab = explode('.', $uploadedFile->getClientOriginalName());
                $ext = $tab[count($tab) - 1];
                $file = new Files();
                $file->file = $uploadedFile;
                    $fileExtension = $ext;
                    $fileName = uniqid() .'.' .$fileExtension;
                    $fileSize = $uploadedFile->getClientSize();
                    $directory = "photo/user".$id;
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
                    $src = $photo->path($id);
                    $em->persist($photo);
                    $em->flush();
                    $em->detach($photo);
                   $result[] = ["name" => $fileName,"size" => $fileSize, "src"=> $src];
            }

            return $this->json($request);

        }
        return \FOS\RestBundle\View\View::create(['message' => $errors], Response::HTTP_NOT_FOUND);
    }


}