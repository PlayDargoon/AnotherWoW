<?php
// src/services/SoapService.php

class SoapService
{
    private $client;

    public function __construct($wsdlUrl)
    {
        $this->client = new SoapClient($wsdlUrl, [
            'trace' => true, // Включаем трассировку для отладки
            'exceptions' => true, // Включаем исключения для обработки ошибок
        ]);
    }

    /**
     * Выполняет SOAP-запрос
     *
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function call($method, $params)
    {
        try {
            return $this->client->__soapCall($method, [$params]);
        } catch (SoapFault $fault) {
            trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
        }
    }

    /**
     * Возвращает последний запрос и ответ
     */
    public function debugLastRequestResponse()
    {
        echo '<pre>';
        echo "Last Request:\n" . htmlspecialchars($this->client->__getLastRequest()) . "\n\n";
        echo "Last Response:\n" . htmlspecialchars($this->client->__getLastResponse());
        echo '</pre>';
    }
}