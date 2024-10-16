<?php

namespace App\Services;

class JitsiTeleconsultationService
{
    /**
     * Génère une URL pour une téléconsultation via Jitsi.
     *
     * @param string $roomName Le nom de la salle (unique pour chaque consultation)
     * @return string L'URL complète de la salle de téléconsultation Jitsi
     */
    public function createJitsiRoom($roomName)
    {
        // Récupère l'URL de base de Jitsi depuis le fichier de configuration
        $baseUrl = config('services.jitsi.base_url', 'https://meet.jit.si/');

        // Crée l'URL complète avec le nom de la salle
        return $baseUrl . $roomName;
    }
}
