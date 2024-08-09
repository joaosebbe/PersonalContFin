<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificacaoController extends Controller
{
    public static function NotificaWhatsapp()
    {
        // Defina as variáveis necessárias
        $accessToken = env('API_WPP_TOKEN');
        $fromPhoneNumberId = env('API_WPP_PHONE_NUMBER_ID');
        $templateName = env('API_WPP_TEMPLATE');
        $languageCode = env('API_WPP_LANGUAGE_CODE');
        $toPhoneNumber = '5531994183060';
        $textString1 = 'João Vitor Sebbe';
        $textString2 = 'Cartão NuBank';
        $textString3 = '1.956,78';
        $textString4 = '10/08/2024';

        // Crie os dados a serem enviados
        $data = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $toPhoneNumber,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => [
                    'code' => $languageCode
                ],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => $textString1
                            ],
                            [
                                'type' => 'text',
                                'text' => $textString2
                            ],
                            [
                                'type' => 'text',
                                'text' => $textString3
                            ],
                            [
                                'type' => 'text',
                                'text' => $textString4
                            ],
                        ]
                    ]
                ]
            ]
        ];

        // Inicialize a sessão cURL
        $ch = curl_init("https://graph.facebook.com/v20.0/$fromPhoneNumberId/messages");

        // Configure as opções do cURL

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Retirar isso em produção
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $accessToken",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute a requisição e capture a resposta
        $response = curl_exec($ch);

        // Verifique se houve erro
        if (curl_errno($ch)) {
            echo $response = 'Erro no cURL: ' . curl_error($ch);
        } else {
            // Exiba a resposta
            echo $response;
        }

        // Feche a sessão cURL
        curl_close($ch);

        return $response;
    }
}
