<?php

namespace Services;

class SoapClientService
{
    private $cfg;

    public function __construct(array $config)
    {
        $this->cfg = $config;
        if (empty($this->cfg['enabled'])) {
            throw new \RuntimeException('SOAP disabled in config');
        }
        if (!class_exists('SoapClient')) {
            throw new \RuntimeException('PHP SOAP extension is not enabled');
        }
    }

    private function createClient(): \SoapClient
    {
        $location = sprintf('http://%s:%d/', $this->cfg['host'], (int)$this->cfg['port']);
        $options = [
            'location' => $location,
            'uri' => $this->cfg['uri'] ?? 'urn:AC',
            'login' => $this->cfg['username'],
            'password' => $this->cfg['password'],
            'connection_timeout' => $this->cfg['timeout'] ?? 5,
            'style' => SOAP_RPC,
            'trace' => false,
            'exceptions' => true,
        ];
        return new \SoapClient(null, $options);
    }

    /**
     * Execute raw command string. Returns string output from worldserver.
     */
    public function execute(string $command): string
    {
        $client = $this->createClient();
        try {
            // Используем RPC-вызов с SoapParam как в рабочем CLI-хелпере
            $result = $client->executeCommand(new \SoapParam($command, 'command'));
            // Some cores return an object with return field, normalize to string
            if (is_object($result) && isset($result->return)) {
                return (string)$result->return;
            }
            return is_string($result) ? $result : json_encode($result, JSON_UNESCAPED_UNICODE);
        } catch (\SoapFault $e) {
            throw new \RuntimeException('SOAP Fault: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Execute a whitelisted command by key with sprintf-like formatting for arguments.
     */
    public function safeExecute(string $key, string ...$args): string
    {
        $tpl = $this->cfg['whitelist'][$key] ?? null;
        if (!$tpl) {
            throw new \InvalidArgumentException('Command not allowed: ' . $key);
        }
        $cmd = @vsprintf($tpl, $args);
        if ($cmd === false) {
            throw new \InvalidArgumentException('Invalid arguments for command ' . $key);
        }
        return $this->execute($cmd);
    }
}
