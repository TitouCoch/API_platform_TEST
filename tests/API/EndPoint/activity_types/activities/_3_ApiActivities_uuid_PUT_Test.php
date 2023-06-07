<?php
namespace App\Tests\API\EndPoint\activity_types\activities;
use App\Tests\API\AAMethodTest;

class _5_ApiActivities_uuid_PUT_Test extends AAMethodTest {
    public function test(): void {
        $method = "PUT";
        $path = "/api/activities/";
        $requirement = "@id";
        $code = 200;

        //Type recovery
        $response2 = static::createClient()->request('GET', '/api/activity_types',['query' => ['deletedAt'=>'false'], 'headers' => ['Accept' => 'application/ld+json', 'Authorization' => 'Bearer '.self::$token]]);
        $content = json_decode($response2->getContent(), true);
        $type = $content['hydra:member'][count($content['hydra:member'])-1]['@id'];

        $data = ["medias"=>[],"type"=>$type,"premiumStartDate"=>"2023-05-03","premiumEndDate"=>"2023-05-07","telephone"=>"t2","website"=>"t8","email"=>"t5","certified"=>true,"free"=>true,"translations"=>[],"uuid"=>"3f082855-bd3c-46a2-954e-13d1a12f6c20","createdAt"=>"2023-05-02","deletedAt"=>"2023-05-08","title"=>"t1","description"=>"t9"];
        $filters = [];
        if ($requirement !== 'null') {
            $segments = explode('/', $path);
            $pathCut = $segments[1].'/'.$segments[2];
            $response = static::createClient()->request('GET', $pathCut, ['query' => ['deletedAt'=>'false'], 'headers' => ['Accept' => 'application/ld+json', 'Authorization' => 'Bearer '. self::$token]]);
            $content = $response->toArray();
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
             static::createClient()->request($method, $path, ['query' => ['deletedAt'=>'false'], 'headers' => ['Accept' => 'application/ld+json', 'Authorization' => 'Bearer '.self::$token], 'json' => $data]);
             $this->assertResponseStatusCodeSame($code, "Le code de statut de réponse HTTP devrait être égal à $code");
        } catch (\Exception $e) {
             $this->throwError($path,$e->getMessage());
        }
    }
}
