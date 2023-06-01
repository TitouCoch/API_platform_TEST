<?php
namespace App\Tests\API\EndPoint;
use App\Tests\API\MethodTest;

class _1_ApiActivities_GET_Test extends MethodTest {
    public function test(): void {
        $method = "GET";
//        $response = static::createClient()->request('POST', '/api/login_check', ['json' => ["email"=>"athome-solution@gmail.com","password"=>"Password123!"]]);
//        $response = json_decode($response->getContent());
//        try{$token = $response->id_token;}
//        catch(\Exception $exception) {dump('ERREUR : Token null (file: ApiActivities) -> '.$exception->getMessage());}
        $path = "/api/activities";
        $requirement = "null";
        $code = 200;
        $data = [];
        $filters = ["deletedAt"=>"false"];
        if ($requirement !== 'null') {
            $segments = explode('/', $path);
            $pathCut = $segments[1].'/'.$segments[2];
            $response = static::createClient()->request('GET', $pathCut, ['query' => ['deletedAt'=>'false'], 'headers' => ['Accept' => 'application/ld+json', 'Authorization' => 'Bearer '. $this->token]]);
            $content = $response->toArray();
            $value = $content['hydra:member'][count($content['hydra:member'])-1][$requirement];
            if ($requirement == '@id') {
                $path = $value;
                if (preg_match('/\/[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $path) !== 1 && $method != 'GET') {
                    $path = substr($path, 0, strrpos($path, '/'));
                }
            }
            else {
                preg_replace('/\{null}/', $value, $path);
            }
        }
        dump('----------------------------------------', $method . ' | ' . $path . ' | ' . $code . ' | ' . json_encode($data) . ' | ' . json_encode($filters));
        try {
             static::createClient()->request($method, $path, ['query' => ['deletedAt'=>'false'], 'headers' => ['Accept' => 'application/ld+json', 'Authorization' => 'Bearer '.$this->token], 'json' => $data]);
             $this->assertResponseStatusCodeSame($code, "Le code de statut de réponse HTTP devrait être égal à $code");
        } catch (\Exception $e) {
             $this->throwError($path,$e->getMessage());
        }
    }
}
