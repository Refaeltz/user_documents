<?php
/**
 * Created by PhpStorm.
 * User: Refael-laptop
 * Date: 01/08/2019
 * Time: 13:14
 */

namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UploadsController extends AbstractController
{
	/**
	 * @Route("/upload/", name="uploadPage")
	 */
	public function showUploadsPage(EntityManagerInterface $entityManager)
	{
		return $this->render('pages/upload_page.twig');
	}

	/**
	 * @Route("/api/uploadUserFile", name="uploadFile", methods={"POST"})
	 */
	public function uploadFile(Request $request){
		$fileName = $request->get("fileName");
		$fileTitle = $request->get("fileToUpload");
		$msg = $err = '';
		dump('Here: ' . __LINE__ . ' at ' . __FILE__,$fileName, $fileTitle);die;
		
		return $this->render('pages/upload_page.twig', array(
			'msg' => $msg,
			'err' => $err
		));
	}

	/**
	 * @Route("/api/downloadfile/{file_id}", name="downloadUserFile")
	 */
	public function downloadFile($file_id)
	{
//		$pdfPath = $this->getParameter('dir.downloads').'/sample.pdf';
		dump('Here: ' . __LINE__ . ' at ' . __FILE__,$this->getParameter('kernel.public')."\images\computer-science.jpg");die;
		$file_path = $this->getParameter('kernel.project_dir')."\public"."\images\computer-science.jpg";
		return $this->file($file_path);
	}

	/**
	 * PHP encrypt and decrypt
	 *
	 * @param string $action Acceptable values are `encrypt` or `decrypt`.
	 * @param string $string The string value to encrypt or decrypt.
	 * @return string
	 */
	private function encrypt_decrypt($action, $string)
	{
		$output = false;

		$encrypt_method = "AES-256-CBC";
		$secret_key = 'eWave';
		$secret_iv = 'excersize';

		// hash
		$key = hash('sha256', $secret_key);

		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('sha256', $secret_iv), 0, 16);

		if ($action == 'encrypt')
		{
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = base64_encode($output);
		}
		else
		{
			if ($action == 'decrypt')
			{
				$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
			}
		}

		return $output;
	}
}