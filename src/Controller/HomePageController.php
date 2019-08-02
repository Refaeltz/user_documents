<?php
/**
 * Created by PhpStorm.
 * User: Refael-laptop
 * Date: 01/08/2019
 * Time: 12:02
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
	/**
	 * @Route("/", name="homepage")
	 */
	public function homepage(){
		return $this->render("base.html.twig");
	}
}