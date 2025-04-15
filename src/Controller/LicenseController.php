<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LicenseRepository;

class LicenseController extends AbstractController
{
    #[Route('/api/check-license', name: 'check_license', methods: ['POST'])]
    public function checkLicense(Request $request, LicenseRepository $licenseRepository): JsonResponse
    {
        $licenseKey = $request->get('license_key');
        $clientUrl = $request->get('site_url');

        $license = $licenseRepository->findOneBy(['licenseKey' => $licenseKey]);

        if (!$license) {
            return $this->json(['status' => 'invalid', 'message' => 'Invalid license key.']);
        }

        if ($license->getExpiresAt() < new \DateTime()) {
            return $this->json(['status' => 'invalid', 'message' => 'License expired.']);
        }

        if ($license->getClientUrl() !== $clientUrl) {
            return $this->json(['status' => 'invalid', 'message' => 'Domain mismatch.']);
        }

        return $this->json(['status' => 'valid', 'message' => 'License valid.']);
    }
}
