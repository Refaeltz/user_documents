<?php
/**
 * Created by PhpStorm.
 * User: Refael-laptop
 * Date: 01/08/2019
 * Time: 12:02
 */

namespace App\Controller;

use App\Entity\Upload;
use App\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
	/**
	 * @Route("/", name="homepage")
	 */
	public function homepage(EntityManagerInterface $entityManager){
		$userManager = new UserManager();

		if($userManager->user_id){
			$queryBuilder = $entityManager->getRepository(Upload::class);
			$fileListArr = $queryBuilder->findBy(array('user_id' => $userManager->user_id), null, 10);
		}
		if(empty($fileListArr)){
			$fileListArr = array(
				array(
					'file_name' => 'file_name',
					'media_id' => 1,
					'insert_ts' => 1564860884
				),
				array(
					'file_name' => 'file_name',
					'media_id' => 2,
					'insert_ts' => 1564860884
				),
				array(
					'file_name' => 'file_name',
					'media_id' => 3,
					'insert_ts' => 1564860884
				),
				array(
					'file_name' => 'file_name',
					'media_id' => 4,
					'insert_ts' => 1564860884
				),
				array(
					'file_name' => 'file_name',
					'media_id' => 5,
					'insert_ts' => 1564860884
				),
			);
		}

		return $this->render("base.html.twig", array(
			'uploads' => $fileListArr
		));
	}
}