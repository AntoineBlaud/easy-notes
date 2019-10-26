<?php

namespace App\Controller;

# Includes the autoloader for libraries installed with composer
require __DIR__ . '/../../vendor/autoload.php';

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

# Imports the Google Cloud client library
use Google\Cloud\Speech\V1\SpeechClient;
use Google\Cloud\Speech\V1\RecognitionAudio;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;


class GoogleRequestController extends AbstractController
{


    /**
     * @Route("/sendGoogleAudioRequest", name="sendGoogleAudioRequest")
     */
    public function sendGoogleAudioRequest($audio)
    {

        # get contents of a file into a string
        $content = file_get_contents($audio);

        # set string as audio content
        $audio = (new RecognitionAudio())
            ->setContent($content);

        # The audio file's encoding, sample rate and language
        $config = new RecognitionConfig([
            'language_code' => 'fr-FR'

        ]);

        # Instantiates a client
        $client = new SpeechClient();

        # Detects speech in the audio file
        $response = $client->recognize($config, $audio);
        $transcript = "";

        # Print most likely transcription
        foreach ($response->getResults() as $result) {
            $alternatives = $result->getAlternatives();
            $mostLikely = $alternatives[0];
            $transcript .= " " . $mostLikely->getTranscript();
        }

        $client->close();
        return new Response($transcript);
    }

    /**
     * @Route("/sendGoogleImageToTextRequest", name="sendGoogleImageToTextRequest")
     */
    public function sendGoogleImageToTextRequest($image)
    {

        # instantiates a client
        $imageAnnotator = new ImageAnnotatorClient();
        $response = $imageAnnotator->labelDetection($image);
        $labels = $response->getLabelAnnotations();

        if ($labels) {
            echo ("Labels:" . PHP_EOL);
            foreach ($labels as $label) {
                echo ($label->getDescription() . PHP_EOL);
            }
        } else {
            echo ('No label found' . PHP_EOL);
        }
    }
}
