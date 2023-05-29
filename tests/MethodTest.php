<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use PDO;
use Ramsey\Uuid\Uuid;

class MethodTest extends ApiTestCase
{
    protected ?string $token = null;
    protected string $loginPath = '/api/login_check';
    protected array $methodAccepted = ['GET', 'POST', 'PUT', 'DELETE'];
    protected array $filterRequired = [];
    public array $usersCredentials = [
            'email' => 'athome-solution@gmail.com',
            'password' => 'Password123!'
    ];
    protected array $componentReplacements = [
        '-enumeration_write' => '/-translations/',
        '-destination_read' => '/-translations/',
        '-revision_read' => '/-opening_date_read/'
    ];

    protected function setUp(): void
    {
        self::bootKernel();
        $this->getToken();
    }

    protected function getToken(): string|null
    {
        if ($this->token) {
            return $this->token;
        }
        $response = static::createClient()->request('POST', $this->loginPath, ['json' => $this->usersCredentials]);
        $this->assertResponseIsSuccessful();
        $data = json_decode($response->getContent());
        try{
            $this->token = $data->id_token;
            return $data->id_token;
        } catch(\Exception $exception) {
            $this->throwError('ERROR : GetToken()',$exception->getMessage());
            return null;
        }
    }

    protected function getDataFixture(string $pathCut, string $key, array $filters, ?string $path = null): string|null
    {
        $response = static::createClient()->request('GET', $pathCut, [
            'headers' => ['Accept' => 'application/ld+json','Authorization' => 'Bearer '. $this->token],
            'query' => ['deletedAt' => 'false'],
        ]);
        $data = json_decode($response->getContent(), true);
        $nb = count($data['hydra:member']);
        try{
            if($key == '@id' && isset($path)){
                if(isset($data['hydra:member'][$nb-1]['uuid'])){
                    return $data['hydra:member'][$nb-1]['uuid'] ?? null;
                }
            }
            return $data['hydra:member'][$nb-1][$key] ?? null;
        } catch(\Exception $exception) {
            $this->throwError('ERROR : GetDataFixture('.$path.', '.$key.')',$exception->getMessage());
            return null;
        }
    }

    protected function getDefaultValue($definition): bool|object|array|int|string|null
    {
        try{
            $type = $definition['type'] ?? null;
            if (!$type) {
                return (object)array();
            }

            $default = $definition['default'] ?? null;
            if (isset($default)) {
                return $default;
            }

            return match ($type) {
                'string' => 't'.rand(1,9),
                'integer', 'number' => rand(0,10),
                'boolean' => true,
                'array' => array(),
                'object' => (object)array(),
                default => null,
            };
        }catch(\Exception $exception) {
            $this->throwError('ERROR : GetDefaultValue('.$definition.')',$exception->getMessage());
            return null;
        }
    }

    protected function matchFilter($name): ?string
    {
        try{
            return match ($name) {
                "deletedAt" => 'false',
                default => null,
            };
        }catch(\Exception $exception) {
            $this->throwError('ERROR : MatchFilter('.$name.')',$exception->getMessage());
            return null;
        }
    }

    protected function tranformPath($path): string|null //exemple : transform path from /api/local_countries to localCountry
    {
        try {
            $pathParts = explode('/', $path);
            $lastPart = end($pathParts);
            $singularPart = preg_replace('/s$/', '', $lastPart);
            $singularPart = preg_replace('/ie$/', 'y', $singularPart);
            $underscoreSeparatedParts = explode('_', $singularPart);
            $camelCasePart = $underscoreSeparatedParts[count($underscoreSeparatedParts) - 1];
            return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $camelCasePart))));
        } catch (\Exception $exception) {
            $this->throwError('ERROR : tranformPath('.$path.')',$exception->getMessage());
            return null;
        }
    }

    protected function getIriReference(string $property): bool
    {
        try{
            foreach ($this->json['paths'] as $path => $values) {
                if ($property === $this->tranformPath($path)) {
                    $this->data[$property] = $this->getDataFixture($path, '@id', $this->filters);
                    return false;
                }
            }
            return true;
        }catch(\Exception $exception) {
            $this->throwError('ERROR : GetIriReference('.$property.')',$exception->getMessage());
            return false;
        }
    }

    protected function reachComponent(string $component): array|string|null
    {
        foreach ($this->componentReplacements as $replacement  => $pattern) {
            $new_component = $this->json['components']['schemas'][preg_replace($pattern,$replacement,$component)]['properties'] ?? null;
            if (isset($new_component)) {
                return preg_replace($pattern,$replacement,$component);
            }
        }
        return $component;
    }

    protected function throwError(string $path, string $message): void
    {
        $errorFile = fopen("./tests/Errors_log/error.json", "a");
        fwrite($errorFile, '// '.$path . PHP_EOL);
        $ligneMessage = explode('\n', $message);
        foreach ($ligneMessage as $ligne){
            fwrite($errorFile, $ligne . PHP_EOL);
        }
        fwrite($errorFile, PHP_EOL);
        fclose($errorFile);
    }
}


