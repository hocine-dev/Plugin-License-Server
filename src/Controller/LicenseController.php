<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LicenseRepository;

class LicenseController extends AbstractController
{
    private string $privateKeyPath;

    public function __construct(string $privateKeyPath)
    {
        // Chemin vers la clé privée
        $this->privateKeyPath = $privateKeyPath;
    }

    #[Route('/api/check-license', name: 'check_license', methods: ['POST'])]
    public function checkLicense(Request $request, LicenseRepository $licenseRepository): JsonResponse
    {
        $licenseKey = $request->get('license_key');
        $clientUrl = $request->get('site_url');

        $license = $licenseRepository->findOneBy(['licenseKey' => $licenseKey]);

        if (!$license) {
            return $this->signResponse([
                'status' => 'invalid',
                'message' => 'Invalid license key.'
            ]);
        }

        if ($license->getExpiresAt() < new \DateTime()) {
            return $this->signResponse([
                'status' => 'invalid',
                'message' => 'License expired.'
            ]);
        }

        if ($license->getClientUrl() !== $clientUrl) {
            return $this->signResponse([
                'status' => 'invalid',
                'message' => 'Domain mismatch.'
            ]);
        }

        return $this->signResponse([
            'status' => 'valid',
            'message' => 'License valid.'
        ]);
    }

    private function signResponse(array $data): JsonResponse
    {
        $privateKey = file_get_contents($this->privateKeyPath);
        $dataString = json_encode($data);
        $signature = '';

        // Signature avec clé privée RSA
        openssl_sign($dataString, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        $data['signature'] = base64_encode($signature);

        return new JsonResponse($data);
    }
}
