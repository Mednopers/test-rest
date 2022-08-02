<?php

namespace App\Controller;

use App\Model\Common\Validation\ObjectValidationGuardInterface;
use App\Model\UseCase\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

#[Route(path: '/api/v1', name: 'api.users_')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ObjectValidationGuardInterface $validationGuard,
    ) {
    }

    #[OA\Post(summary: 'Add user.', tags: ['Users'])]
    #[OA\RequestBody(content: [new Model(type: User\Create\Command::class)])]
    #[OA\Response(response: 303, description: 'User has been created in database.')]
    #[OA\Response(response: 400, description: 'Illegal action.')]
    #[OA\Response(response: 409, description: 'Error during the data flushing.')]
    #[OA\Response(response: 422, description: 'Error with data during the validation.')]
    #[Route(path: '/users', name: 'user_create', methods: ['POST'], format: JsonEncoder::FORMAT)]
    public function create(Request $request, User\Create\Handler $handler): RedirectResponse
    {
        $command = $this->serializer->deserialize($request->getContent(), User\Create\Command::class, JsonEncoder::FORMAT);
        $this->validationGuard->validate($command);

        $userId = $handler->handle($command);

        return $this->redirectToRoute('api.users_user_show', [
            'id' => $userId,
        ], Response::HTTP_SEE_OTHER);
    }

    #[OA\Get(summary: 'Get user by id.', tags: ['Users'])]
    #[OA\Parameter(name: 'id', description: 'The `id` of the user.', in: 'path')]
    #[OA\Response(response: 200, description: 'Return user data.')]
    #[OA\Response(response: 404, description: "The user's `id` does not exist in database.")]
    #[OA\Response(response: 422, description: 'Error with data during the validation.')]
    #[Route(path: '/users/{id}', name: 'user_show', methods: ['GET'], format: JsonEncoder::FORMAT)]
    public function show(string $id, User\Show\Handler $handler): JsonResponse
    {
        $query = new User\Show\Query($id);
        $this->validationGuard->validate($query);

        $result = $handler->handle($query);

        return $this->json($result);
    }

    #[OA\Put(summary: 'Update user.', tags: ['Users'])]
    #[OA\RequestBody(content: [new Model(type: User\Change\Command::class)])]
    #[OA\Response(response: 303, description: 'User has been updated in database.')]
    #[OA\Response(response: 404, description: "The user's `id` does not exist in database.")]
    #[OA\Response(response: 409, description: 'Error during the data flushing.')]
    #[OA\Response(response: 422, description: 'Error with data during the validation.')]
    #[Route(path: '/users', name: 'user_change', methods: ['PUT'], format: JsonEncoder::FORMAT)]
    public function change(Request $request, User\Change\Handler $handler): RedirectResponse
    {
        $command = $this->serializer->deserialize($request->getContent(), User\Change\Command::class, JsonEncoder::FORMAT);
        $this->validationGuard->validate($command);

        $userId = $handler->handle($command);

        return $this->redirectToRoute('api.users_user_show', [
            'id' => $userId,
        ], Response::HTTP_SEE_OTHER);
    }

    #[OA\Delete(summary: 'Remove user by id.', tags: ['Users'])]
    #[OA\Parameter(name: 'id', description: 'The `id` of the user.', in: 'path')]
    #[OA\Response(response: 204, description: 'User removed.')]
    #[OA\Response(response: 404, description: "The user's `id` does not exist in database.")]
    #[OA\Response(response: 409, description: 'Error during the process.')]
    #[OA\Response(response: 422, description: 'Error with data during the validation.')]
    #[Route(path: '/users/{id}', name: 'user_remove', methods: ['DELETE'], format: JsonEncoder::FORMAT)]
    public function remove(string $id, User\Remove\Handler $handler): JsonResponse
    {
        $command = new User\Remove\Query($id);
        $this->validationGuard->validate($command);

        $handler->handle($command);

        return $this->json('', Response::HTTP_NO_CONTENT);
    }
}
