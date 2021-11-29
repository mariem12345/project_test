<?php

namespace App\Controller;

use App\Entity\Prime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class PrimeController extends AbstractController
{
    /**
     * @Route("/api/v1/primenumbers", methods={"GET"})
     * @OA\Get (
     *     path="/api/v1/primenumbers",
     *     @OA\Response(
     *          response="200",
     *          description="The list of prime numbers we have",
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Prime")),
     *          @OA\XmlContent(type="array", @OA\Items(ref="#/components/schemas/Prime")),
     *          )
     *          )
     */
    public function primeNumbersAction()
    {
        $repository = $this->getDoctrine()->getRepository(Prime::class);
        $number = [];
        $primeNumbers = $repository->findAll();
        foreach ($primeNumbers as $primeNumber) {
            $number [] = $primeNumber->getNumber();
        }
        if (empty($number)) {
            throw $this->createNotFoundException('No hay números primos');

        }
        return new JsonResponse($number);
    }

    /**
     * @Route("/api/v1/prime/{number}", name="prime_number",  methods={"POST"})
     * @OA\Post (
     *     path="/api/v1/prime/{number}",
     *     @OA\Parameter (
     *      name="number",
     *      in="path",
     *     description="number es el número para ver si es primo o no. ",
     *     required=true,
     *     @OA\Schema(type="integer")
     *           ),
     *     @OA\Response(
     *          response="200",
     *          description="Verifique si un número es primo y persígalo si es cierto",
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Prime")),
     *          @OA\XmlContent(type="array", @OA\Items(ref="#/components/schemas/Prime")),
     *          )
     *          )
     */
    public function checkNumberAction(int $number)
    {

        if (!(is_int($number))) {
            throw new \Exception('El número proporcionado no es correcto');
        }
        $entityManager = $this->getDoctrine()->getManager();
        $prime = new Prime();
        $repository = $this->getDoctrine()->getRepository(Prime::class);

        if ($this->isPrime($number) or $number == 1) {
            $exist = $repository->findBy(['number' => $number]);
            if (!$exist) {
                $prime->setNumber($number);
                $prime->setPrime(true);
                $entityManager->persist($prime);
                $entityManager->flush();
            }
            return new JsonResponse(['status' => 'El número es primo.!']);
        }
        return new JsonResponse(['status' => 'el número no es primo.']);
    }

    /**
     * @Route("/api/v1/primenumbers/{number}", name="delete_prime", methods={"DELETE"})
     * @OA\Delete  (
     *     path="/api/v1/primenumbers/{number}",
     *     @OA\Parameter (
     *      name="number",
     *      in="path",
     *     description="number es el número para eliminar si es primo o no. ",
     *     required=true,
     *     @OA\Schema(type="integer")
     *           ),
     *     @OA\Response(
     *          response="200",
     *          description="Verifique si un número es primo y persígalo si es cierto",
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Prime")),
     *          @OA\XmlContent(type="array", @OA\Items(ref="#/components/schemas/Prime")),
     *          )
     *          )
     */
    public function deleteAction(int $number)
    {
        $repository = $this->getDoctrine()->getRepository(Prime::class);
        $primeNumber = $repository->findOneBy(['number' => $number]);
        if (!$primeNumber) {
            throw $this->createNotFoundException('Este numero no existe');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($primeNumber);
        $em->flush();

        return new JsonResponse(['success' => true]);


    }

    public function isPrime($number)
    {
        for ($i = 2; $i <= $number / 2; $i++) {
            if ($number % $i == 0)
                return 0;
        }
        return 1;
    }
}
