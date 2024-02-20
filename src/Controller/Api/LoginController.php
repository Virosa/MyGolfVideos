<?php
namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


class LoginController extends AbstractController
{
    /**
     * @return JsonResponse*
     */
    #[Route(path:"/api/login", name: "api_login", methods: ["POST"])]
    public function ApiLogin(){
        $user = $this->getUser();

        $userData = [
            'email'=>$user->getEmail(),
            'name'=>$user->getName(),

        ];
        /*return new JsonResponse(json_encode($userData, JSON_THROW_ON_ERROR));
        Pour retourner une réponse plus lisible dans le test connexion de l'API j'appelle, on retourne $this->json
        qui convertit directement notre tableau un json.
        */
        return $this->json($userData);

    }
}