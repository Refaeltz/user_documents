<?php
/**
 * Created by PhpStorm.
 * User: Refael-laptop
 * Date: 01/08/2019
 * Time: 16:47
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
	/**
	 * Matches /login exactly
	 *
	 * @Route("/login", name="loginPage")
	 */
//	public function showLoginPage()
//	{
//		return $this->render('pages/login_page.twig');
//		//return new Response($html);
//	}
//
//	/**
//	 * @Route("/login/new", name="createNewUser", methods={"POST"})
//	 */
//	public function createNewUser(){
//		$user = new User();
//		$time = time();
//		$user->setUserName('rafi');
//		$user->setPassword('aa23456');
//		$user->setRegisteredTs($time);
//		$user->setLastUpdate($time);
//
//		$em = $this->getDoctrine()->getManager();
//		$em->persist($user);
//		$user_id = $_SESSION['user_id'] = $user->getId();
//		$em->flush();
//
//		return new JsonResponse(array($user_id));
//	}
//
//	/**
//	 * @Route("api/login/", name="doLogin",methods={"POST"})
//	 */
//	public function doLogin(){
//		$user_name = $_REQUEST['username'];
//		$password = $_REQUEST['password'];
//		return new JsonResponse(array($user_name, $password));
//
//		$em = $this->getDoctrine()->getManager();
//		$qb = $em->getRepository(User::class);
//		$user = $qb->findOneBy(array('user_name'=>$user_name));
//
//
////		$fileListArr = $qb->findOneBy(array('id' => $id, 'password' => $password));
//	}
}