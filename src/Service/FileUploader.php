<?php
/**
 * Created by PhpStorm.
 * User: Refael-laptop
 * Date: 04/08/2019
 * Time: 08:17
 */

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
	private $targetDirectory;

	public function __construct($targetDirectory)
	{
		$this->targetDirectory = $targetDirectory;
	}

	public function upload(UploadedFile $file, $user_id)
	{
		$fileExt = '';
		if(!!$user_id && intval($user_id)){
			$fileExt = "\\{$user_id}\\";
		}
		$originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
		$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
		$fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

		try {
//			$file->move($this->getTargetDirectory().$fileExt, $fileName);
			$this->encrypt_decrypt($file, "encrypt", $this->getTargetDirectory().$fileExt.$fileName);
		} catch (FileException $e) {
			// ... handle exception if something happens during file upload
		}

		return $fileName;
	}

	public function download($file, $full_path, $tmp_dir)
	{
		$decryptFile = null;

		try {
//			$file->move($this->getTargetDirectory().$fileExt, $fileName);
			$decryptFile = $this->encrypt_decrypt($file, "decrypt", $this->getTargetDirectory().$full_path, $tmp_dir);
		} catch (FileException $e) {
			// ... handle exception if something happens during file upload
		}

		return $decryptFile;
	}

	public function getTargetDirectory()
	{
		return $this->targetDirectory;
	}

	/**
	 * PHP encrypt and decrypt
	 *
	 * @param File $file
	 * @param string $action Acceptable values are `encrypt` or `decrypt`.
	 * @param string $full_path The path to save\open file
	 * @return string
	 */
	private function encrypt_decrypt($file, $action, $full_path, $tmp_dir = '')
	{
		$output = false;
		$secret_key = 'eWave';
		$secret_iv = 'excersize';
		$key = hash('sha256', $secret_key);
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
		$opts = array('iv' => $iv, 'key' => $key);
		$contents=null;

		if ($action == 'encrypt')
		{
			$handle = fopen($file,'rb');
			$contents = fread($handle, $file->getClientSize());
			$read_write = 'wb';
			$stream_filter = STREAM_FILTER_WRITE;
		}
		elseif($action == 'decrypt')
		{
			$read_write = 'rb';
			$stream_filter = STREAM_FILTER_READ;
		}

		$dirname = dirname($full_path);
		if (!is_dir($dirname))
		{
			mkdir($dirname, 0755, true);
		}

		$fp = fopen($full_path, $read_write);
		$stringRot13 = stream_filter_append($fp, 'string.rot13', $stream_filter, $opts);

		if($stream_filter == STREAM_FILTER_WRITE)
		{
			fwrite($fp,$contents);
			fclose($fp);
		}
		else{
			$tmpContent = fread($fp, $file->getClientSize());

			$dirname = dirname($tmp_dir.$file->getClientOriginalName());
			if (!is_dir($dirname))
			{
				mkdir($dirname, 0755, true);
			}

			$fileOpen = fopen($tmp_dir.$file->getClientOriginalName(),'wb');
			fwrite($fileOpen, $tmpContent);
			fclose($fileOpen);

			return $tmp_dir;
		}

		return $stringRot13;
	}

	public function deleteFile($path){
		unlink($this->targetDirectory.$path);
	}
}