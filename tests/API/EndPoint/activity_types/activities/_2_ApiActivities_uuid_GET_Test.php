<?php
namespace App\Tests\API\EndPoint\activity_types\activities;
use App\Tests\API\AAMethodTest;

class _2_ApiActivities_uuid_GET_Test extends AAMethodTest {
    public function test(): void {
        $method = "GET";
        $path = "/api/activities/{uuid}";
        $requirement = "@id";
        $code = 200;

        //type recovery
        $response2 = static::createClient()->request('GET', '/api/activity_types',['query' => ['deletedAt'=>'false'], 'headers' => ['Accept' => 'application/ld+json', 'Authorization' => 'Bearer '.self::$token]]);
        $content = json_decode($response2->getContent(), true);
        $type = $content['hydra:member'][count($content['hydra:member'])-1]['@id'];

        $data = ["type"=>$type];
        $filters = [];
        if ($requirement !== 'null') {
            $segments = explode('/', $path);
            $pathCut = '/'.$segments[1].'/'.$segments[2];
            $response = static::createClient()->request('GET', '/api/activities', ['query' => ['deletedAt'=>'false'], 'headers' => ['Accept' => 'application/ld+json', 'Authorization' => 'Bearer '. self::$token]]);
            $content = json_decode($response->getContent(), true);

            $value = $content['hydra:member'][count($content['hydra:member'])-1][$requirement];
            if ($requirement == '@id') {
                $path = $value;
                if (preg_match('/\/[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $path) !== 1 && $method != 'GET') {
                    $path = substr($path, 0, strrpos($path, '/'));
                }
            }
            else {
                preg_replace('/\{@id}/', $value, $path);
            }
        }
        dump('----------------------------------------', $method . ' | ' . $path . ' | ' . $code . ' | ' . json_encode($data) . ' | ' . json_encode($filters));
        try {
             static::createClient()->request($method, $path, ['query' => ['deletedAt'=>'false'], 'headers' => ['Accept' => 'application/ld+json', 'Authorization' => 'Bearer '.self::$token]]);
             $this->assertResponseStatusCodeSame($code, "Le code de statut de réponse HTTP devrait être égal à $code");
        } catch (\Exception $e) {
             $this->throwError($path,$e->getMessage());
        }
    }
}
