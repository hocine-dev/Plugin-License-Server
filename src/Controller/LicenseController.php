<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LicenseRepository;

class LicenseController extends AbstractController
{
    private string $secretKey;

    // On injecte la clé secrète depuis services.yaml
    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
    }

    #[Route('/api/check-license', name: 'check_license', methods: ['POST'])]
    public function checkLicense(Request $request, LicenseRepository $licenseRepository): JsonResponse
    {
        $licenseKey = $request->get('license_key');
        $clientUrl  = $request->get('site_url');

        // Recherche de la licence en base de données
        $license = $licenseRepository->findOneBy(['licenseKey' => $licenseKey]);

        if (!$license) {
            return $this->signedJson(['status' => 'invalid', 'message' => 'Invalid license key.']);
        }

        // Vérification de la date d’expiration
        if ($license->getExpiresAt() < new \DateTime()) {
            return $this->signedJson(['status' => 'invalid', 'message' => 'License expired.']);
        }

        // Vérification du nom de domaine
        if ($license->getClientUrl() !== $clientUrl) {
            return $this->signedJson(['status' => 'invalid', 'message' => 'Domain mismatch.']);
        }

        // Tout est valide !
        return $this->signedJson(['status' => 'valid', 'message' => 'License valid.']);
    }

    /**
     * Cette méthode ajoute une signature HMAC SHA-256 au tableau de réponse
     * pour que le client (WordPress) puisse vérifier l’authenticité.
     */
    private function signedJson(array $data): JsonResponse
    {
        $signature = hash_hmac('sha256', json_encode($data, JSON_UNESCAPED_UNICODE), $this->secretKey);
        $data['signature'] = $signature;

        return $this->json($data);
    }
}
