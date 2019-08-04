<?php
/**
 * Created by PhpStorm.
 * User: Refael-laptop
 * Date: 01/08/2019
 * Time: 16:47
 */

namespace App\Controller;


use App\Entity\User;
use App\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
	/**
	 * Matches /login exactly
	 *
	 * @Route("/login", name="loginPage")
	 */
	public function showLoginPage()
	{
		$userManager = new UserManager();

		if($userManager->user_id){
			return $this->redirectToRoute('homepage');
		}

		return $this->render('pages/login_page.twig');
	}

	/**
	 * @Route("/login/new", name="createNewUser", methods={"POST"})
	 */
	public function createNewUser(EntityManagerInterface $entityManager, Request $request){
		$user = new User();
		$time = time();
		$user->setUserName($request->get("username"));
		$queryBuilder = $entityManager->getRepository(User::class);
		$user = $queryBuilder->findOneBy(array('user_name' => $user->getUserName()));

		if($user->getId()){
			
			$userManager = new UserManager();
			$userManager->setUserExist(true);
		}
		else{
			$user->setPassword(UserManager::encryptPassword($request->get("password")));
			$user->setRegisteredTs($time);
			$user->setLastUpdate($time);
			$entityManager->persist($user);
			$entityManager->flush();
			$userManager = new UserManager($user->getId(),$user->getUserName());
		}

		return $this->redirectToRoute("homepage");
	}

	/**
	 * @Route("/api/doLogin", name="doLogin",methods={"POST"})
	 */
	public function doLogin(EntityManagerInterface $entityManager, Request $request){

		$user_name = strtolower($request->query->get('username'));
		$password = UserManager::encryptPassword($request->query->get('password'));
		$msg = $err = "";

		if(!!$user_name && !!$password)
		{
			$queryBuilder = $entityManager->getRepository(User::class);
			$user = $queryBuilder->findOneBy(['user_name' => $user_name, 'password' => $password]);

			if (!$user) {
				$err = 'user not found!';
			}
			else
			{
				$userManager = new UserManager($user->getId(), $user->getUserName());
				$msg = !!$userManager->user_id ? "user logged!" : "";
			}
		}
		else
		{
			$err = "invalid username or password!";
		}

		return new JsonResponse(array(
			'err' => $err,
			'msg' =>  $msg
		));
	}

	/**
	 * @Route("/Logout", name="doLogout")
	 */
	public function doLogout(){
		UserManager::logoutUser();
		return $this->render("base.html.twig");
	}
}