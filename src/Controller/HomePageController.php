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

			$uploadsArr = array_map(function ($upload){
				return array(
					'file_name' => $upload->getFileName(),
					'media_id' => $upload->getId(),
					'insert_ts' => $upload->getInsertTs()
					);
			}, $fileListArr);
		}

		return $this->render("base.html.twig", array(
			'uploads' => $uploadsArr
		));
	}
}