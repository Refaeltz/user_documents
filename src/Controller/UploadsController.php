<?php
/**
 * Created by PhpStorm.
 * User: Refael-laptop
 * Date: 01/08/2019
 * Time: 13:14
 */

namespace App\Controller;


use App\Entity\Upload;
use App\Manager\UserManager;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class UploadsController extends AbstractController
{
	public $err = '';
	public $msg = '';

	/**
	 * @Route("/upload/", name="uploadPage")
	 */
	public function showUploadsPage(EntityManagerInterface $entityManager)
	{
		return $this->render('pages/upload_page.twig', array(
			'msg' => $this->msg,
			'err' => $this->err
		));
	}

	/**
	 * @Route("/api/uploadUserFile", name="uploadFile", methods={"POST"})
	 */
	public function uploadFile(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader){
		$msg = 'success upload';
		$err = '';
		$fileToUpload = $request->files->get("fileToUpload");

		if (empty($fileToUpload))
		{
			$err = "No file specified";
		}
		else
		{
			$userManager = new UserManager();
			$userPath = "\\".$userManager->user_id."\\";
			$fileTitle = $fileToUpload->getClientOriginalName();
			$fileSize = $fileToUpload->getClientSize();
			$fileGuessExtension = $fileToUpload->guessExtension();
			$newName = $fileUploader->upload($fileToUpload, $userManager->user_id);
			$userPath .= $newName;

			$time = time();
			$upload = new Upload();
			$upload->setFile($fileToUpload);
			$upload->setFileName($newName);
			$upload->setFilePath($userPath);
			$upload->setInsertTs($time);
			$upload->setUserId($userManager->user_id);

			if(empty($err)){
				$entityManager->persist($upload);
				$entityManager->flush();
				$this->msg = $msg;
			}

			$this->err = $err;
		}
		return $this->redirectToRoute('uploadPage');
	}

	/**
	 * @Route("/api/downloadfile/{file_id}", name="downloadUserFile")
	 */
	public function downloadFile($file_id, EntityManagerInterface $entityManager, FileUploader $fileUploader)
	{
		$userManager = new UserManager();
		$queryBuilder = $entityManager->getRepository(Upload::class);
		$upload = $queryBuilder->findOneBy(['id' => $file_id, 'user_id' => $userManager->user_id]);
		$tmp_dir = $fileUploader->getTargetDirectory()."\\tmp\\";

		$file_path  = $fileUploader->download(new UploadedFile($fileUploader->getTargetDirectory().$upload->getMediaPath(), $upload->getFileName()),$upload->getMediaPath(), $tmp_dir);
		$response = new BinaryFileResponse($file_path . $upload->getFileName());
		$response->deleteFileAfterSend(true);

		return $response;
	}

	/**
	 * @Route("/api/deletefile/{file_id}", name="deleteFile")
	 */
	public function deleteFile($file_id ,EntityManagerInterface $entityManager, FileUploader $fileUploader){
		$userManager = new UserManager();
		$queryBuilder = $entityManager->getRepository(Upload::class);
		$upload = $queryBuilder->findOneBy(['id' => $file_id, 'user_id' => $userManager->user_id]);

		if($upload->getId()){
//			$fileUploader->deleteFile($upload->getMediaPath());
			$entityManager->remove($upload);
			$entityManager->flush();
		}

		return $this->redirectToRoute("homepage");
	}
}