<?php

namespace App\Tests\API;
use Ramsey\Uuid\Uuid;

class JsonTest extends MethodTest
{
    protected string $method;
    protected array $data = [];
    protected array $filters = [];
    protected array|string|null $format;
    protected mixed $json;
    protected string $requirement;

    protected array $incorrectPath = [
        '/api/activities', //POST Format data multipart data required
        '/api/activities/translate', //No locale has been set and current locale is undefined
        '/api/comments', //POST failed to reached data (Name of the attribute is wrong)
        '/api/comments/{uuid}', //Need fixture
        '/api/comments/{uuid}/validate', //Need fixture
        '/api/comments/{uuid}/comments', //Need fixture
        '/api/people/{uuid}/comments', //Need fixture
        '/api/directory_countries/slug',// POST attribut problem (media)
        '/api/configs', //POST attribut problem (require attribut name who is yet present)
        '/api/countries', //POST attribut problem (require attribut alpha2 who is yet present)
        '/api/directory_departments/{uuid}',// DELETE foreign key contraint
        '/api/activities/{uuid}', //Method not allow
        '/api/configs/{id}', //Method not allow (PUT/DELETE)
        '/api/metadata/{uuid}', //Method not allow

        '/api/awards/{uuid}', //Works in prod
        '/api/awards',  //Works in prod
        '/api/directory_countries', //work in prod POST
        '/api/directory_countries/{uuid}', //work in prod POST
        '/api/directory_departments', //work in prod
    ];

    protected array $filePath = [
        '/api/activities/{uuid}', //Need fixture
        '/api/activities/{uuid}/contact', //Need fixture
        '/api/activities/{uuid}/medias', //Need fixture
        '/api/activities/{uuid}/validate', //Need fixture
        '/api/attributes', ///Name of the attribute is wrong
        '/api/camper_vans/{uuid}', //Moved Permanently
        '/api/countries/{id}', //Cannot delete or update a parent row: a foreign key constraint fails
        '/api/directory_countries/{uuid}',//DELETE constraint foreign key
        '/api/directory_countries/slug',//End point NOT FOUND
        '/api/directory_departments',// POST attribut problem (media)
        '/api/elastic_activities',
    ];

    protected array $fileGeneration = [

        '/api/locale_messages',// POST attribut problem (local)

        '/api/locales',// Required parameter who is in the sent data

        "/api/main_revisions", //Handling \App\\Query\\Revision\\GetMainRevisionsSearchQuery failed: No mapping found for [type.position] in order to sort on [index: test_revisions] [reason: all shards failed]
        '/api/me', //Response is null
        '/api/me/delete_account', //Response is null

        '/api/media', //required Media data
        '/api/media/{uuid}', //required Media data
        '/api/media/{uuid}/download', //required Media data

        '/api/metadata/seo', // POST attribut problem (local)
        '/api/metadata/sync', // Failed to open stream

        '/api/mobiles', // Failed to open stream
        '/api/mobiles/{uuid}', // Failed to open stream

        '/api/mobiles/current', // Not found
        '/api/mobiles/{uuid}/click', // Need click on web browser
        '/api/mobiles/{uuid}/duplicate', // Need file in cache/default

        '/api/offmaps', // Need category attribute, nod found in component
        '/api/offmaps/{uuid}',
        '/api/offmaps/{uuid}/images', // Failed to open stream

        '/api/opening_dates', // Post missed attribute (revisions)
        '/api/opening_hours', // Post missed attribute (revisions)

        '/api/ownership_request_views/{id}', // Not table Found

        '/api/person_point_of_interest', //Called person table who's name is people
        '/api/person_point_of_interest/{uuid}',

        '/api/person_stat_views/{uuid}', //No route found to get the dataFixture (/api/person_stat_views)

        '/api/ownership_requests', //Attribute (person, pointOfInterest) not found (iri reference)

        '/api/partners/slug', //Not Found
        '/api/partners', //Attribut problem (media)
        '/api/partners/{uuid}', //Attribut problem (media)

        '/api/password/request', //Manage program to access to his component
        '/api/payments/account_premium/apple_pay', //Consider like a GET and not a POST
        '/api/payments/account_premium/google_pay', //Consider like a GET and not a POST
        '/api/payments/{uuid}', //Failed to search uuid in getDataFixture
        '/api/payments/{uuid}/pdf', //Failed to search uuid in getDataFixture
        '/api/payments/{uuid}/refund-keep', //Failed to search uuid in getDataFixture
        '/api/payments/{uuid}/refund-remove', //Failed to search uuid in getDataFixture

        '/api/people/random', //Warning: Undefined array key \"amount\
        '/api/people/{uuid}', //Warning: Undefined array key \"amount\
        '/api/people/{uuid}/substitute', //No user with email athome9762-solution6837@gmail.com not found

        '/api/person_point_of_interest', //Called person table who's name is people
        '/api/person_point_of_interest/{uuid}', //

        '/api/person_promo_codes', //No components reliable

        '/api/pictos', // POST attribut problem (media)
        '/api/pictos/{uuid}', // Can't delete foreign key constraint

        '/api/pictures', // POST attribut problem (media)

        '/api/pictures/actions', // POST attribut problem (media)
        '/api/pictures/{uuid}', // POST attribut problem (media)
        '/api/pictures/{uuid}/rotate', // Zero size image string passed

        '/api/point_of_interest_gps', // Post missed attribute (revisions)
        '/api/point_of_interests', // Post missed attribute (revisions)

        '/api/point_of_interests/mine', // Required Date filters
        '/api/point_of_interests/{uuid}/delete_notify', // Need fixtures
        '/api/point_of_interests/{uuid}/main_revision', // Need fixtures
        '/api/point_of_interests/{uuid}/transfer_comments', // Need fixtures
        '/api/point_of_interests/{uuid}/pictures', // Need fixtures
        '/api/point_of_interests/{uuid}/transfer_pictures', // Need fixtures

        '/api/press_releases', // POST attribut problem (media, pdf)
        '/api/press_releases/{uuid}', // Attribut problem (media, pdf)

        '/api/promo_codes', //POST required attribut missed in the component
        '/api/person_promo_codes/{id}', //POST required attribut missed in the component
        '/api/promo_codes/{uuid}', //Cannot delete or update a parent row: a foreign key constraint fails
        '/api/promo_codes/import', //POST required file
        '/api/promo_codes/use', //required string in a GET

        '/api/public_revisions', //No route found

        '/api/register', //application/ld+json missed (application/json instead)

        '/api/revision_attributes', //POST missed argument (revision)
        '/api/revisions', //POST missed argument (address)
        '/api/revisions/by_department', //Undefined array key department
        '/api/revisions/latest_favorites',
        '/api/revisions/latest_main_with_pictures',
        '/api/revisions//api/revisions/main_with_pictures',
        '/api/revisions/my_main',
        '/api/revisions/{uuid}', //Error: __clone method called on non-object
        '/api/revisions/{uuid}/increment',
        '/api/revisions/{uuid}/opening_dates',
        '/api/revisions/{uuid}/opening_hours',
        '/api/revisions/{uuid}/payment_types',
        '/api/revisions/{uuid}/pdf',
        '/api/revisions/{uuid}/revision_attributes',
        '/api/revisions/{uuid}/select',
        '/api/revisions/{uuid}/share_email',
        '/api/revisions/{uuid}/validate',

        '/api/steps', //POST steps

        '/api/trip_categories', //POST required photo parameter
        '/api/trip_countries', //POST required photo parameter
        '/api/trip_map_points/{uuid}', //No route found for this endpoint
        '/api/trips', //Post attribute (comments)
        '/api/trips/share', //Post attribute (title)
        '/api/trips/{uuid}', //Post attribute (comments)
        '/api/trips/{uuid}/pdf', //Method GeneratePdfCommand failed

        '/api/webs/{uuid}', //Failed to getUUID
        '/api/webs/{uuid}/click', //Failed to getUUID
        '/api/webs/{uuid}/duplicate', //Failed to getUUID
    ];


    public function verifyPath(string $path): ?string
    {
        try{
            $segments = explode('/', $path);
            $pathCut = '/'.$segments[1].'/'.$segments[2]; //Recuperation du path pour un get (/api/...)
            if (($position = array_search('{id}', $segments)) !== false) {
                $this->getFilters($pathCut,'get');
                $unId = $this->getDataFixture($pathCut,'@id', $this->filters);
                if (str_contains($unId, '/')) {
                    $unId = explode('/', $unId)[$position];
                }
                $this->requirement = 'id';
                return preg_replace('/\{id}/', $unId, $path);
            } elseif (preg_match('/\/me$}/', $path) || preg_match('/\/me\/}/', $path)) {
                    $this->getFilters($pathCut,'get');
                    $unId = basename($this->getDataFixture($pathCut, 'id', $this->filters));
                    $this->requirement = 'id';
                    return preg_replace('/me/', $unId, $path);
            } elseif (($position = array_search('{uuid}', $segments)) !== false) {
                    $uuid = $this->getDataFixture($pathCut, '@id', $this->filters, $path);
                    if (str_contains($uuid, '/')) {
                        $uuid = explode('/', $uuid)[$position];
                    }
                    $this->requirement = '@id';
                    return preg_replace('/\{uuid}/', $uuid, $path);
                }
            $this->requirement = 'null';
            return $path;
        }catch(\Exception $exception) {
            $this->throwError('ERROR : VerifyPath('.$path.')',$exception->getMessage());
            return null;
        }
    }

    public function verifyMethod(string $method): void
    {
        $method = strtoupper($method);
        if (in_array($method, $this->methodAccepted)) {
            $this->method = $method;
        }
    }

    public function getFilters(string $path, string $method): array|null
    {
        try{
        $parametersConfig = $this->json['paths'][$path][$method]['parameters'] ?? null;
        if (!$parametersConfig) {
            $this->filters = [];
            return null;
        }
        foreach ($parametersConfig as $parameter) {
            if (in_array($parameter['name'], $this->filterRequired)) {
                $this->filters[$parameter['name']] = $this->matchFilter($parameter['name']);
                return $this->filters;
            }

            if ($parameter['required'] && $parameter['name'] != 'id' && $parameter['name'] != 'uuid') {
                $this->filters[$parameter['name']] = $this->matchFilter($parameter['name']);
                return $this->filters;
            }
        }
        }catch(\Exception $exception) {
            $this->throwError('ERROR : GetFilters('.$path.','. $method.')',$exception->getMessage());
        }
        return null;
    }

    public function getData(string $component): void
    {
        try{
            foreach ($this->json['components']['schemas'][$component]['properties'] as $property => $definition) {
                if (isset($definition['readOnly']) && $definition['readOnly'] === true) {
                    continue;
                }
                if (isset($definition['anyOf'][0]['$ref'])) {
                    $this->getIriReference($property);
                    continue;
                }
                if ($property == 'uuid') {
                    $this->data[$property] = Uuid::uuid4();
                    continue;
                }
                switch ($definition['format'] ?? null) {
                    case 'iri-reference':
                        $this->getIriReference($property);
                        break;
                    case 'date-time':
                        $this->data[$property] = '2023-05-0'.rand(1,9);
                        break;
                    default:
                        $this->data[$property] = $this->getDefaultValue($definition);
                }
            }
        }catch(\Exception $exception) {
            $this->throwError('ERROR : GetFilters('.$component.')',$exception->getMessage());
        }
    }

    public function testAPI(): void
    {
        $json = file_get_contents(__DIR__ . '/openapi.json');
        $this->json = json_decode($json, true);


        $compteur = 1;

        //Get path
        foreach ($this->json['paths'] as $pathCurrent => $methodAction) {
            $path = $this->verifyPath($pathCurrent);
            //Get method
            foreach ($methodAction as $methodName => $methodDetails) {
                $this->verifyMethod($methodName);

                //Recuperation Code Répponse
                $code = ($methodDetails['responses']) ?? null;
                if (!$code) { continue; }
                $code = key($code);

                //Get filters
                try{
                    $this->getFilters($path, strtolower($this->method));
                }catch(\Exception $exception) {
                    $this->throwError('ERROR : TestApi->getFilters('.$path.', '.strtolower($this->method).')',$exception->getMessage());
                }

                //Get format
                $this->format = $methodDetails['responses'][$code]['content'] ?? null;
                if(isset($this->format)){
                    $this->format = key($this->format);
                }
                else{
                    $this->format = 'application/json';
                }

                //Get data
                $this->data = [];
                $component = $methodDetails['responses'][$code]['content']['application/ld+json']['schema']['$ref'] ?? null;
                if($path==$this->loginPath){
                    $this->data = $this->usersCredentials;
                }
                elseif(isset($component)){
                    $component = basename($component);
                    $component = $this->reachComponent($component);
                    $this->getData($component);
                }

                //Generation tests files
                if(in_array($pathCurrent, $this->fileGeneration) && $this->testInSeparateFile){
                    $segments = explode('/', $pathCurrent);
                    $filename = '';
                    foreach ($segments as $segment) {
                        $filename .= str_replace(array('_', '{', '}','-','.'), array('', '_', '_','_','_'), ucwords($segment, '_'));
                    }
                    $filename = str_replace(['{uuid}', '{me}', '{id}'], '_id_', $filename);
                    $file = fopen("./tests/API/EndPoint/_".$compteur.'_'.$filename.'_'.$this->method.'_'."Test.php", "w");
                    fwrite($file, '<?php' . PHP_EOL);
                    fwrite($file, 'namespace App\Tests\API\EndPoint;' . PHP_EOL);
                    fwrite($file, 'use App\Tests\API\MethodTest;' . PHP_EOL);
                    fwrite($file, 'class _'.$compteur.'_'.$filename.'_'.$this->method.'_'.'Test extends MethodTest {' . PHP_EOL);
                    fwrite($file, '    public function test(): void {' . PHP_EOL);
                    fwrite($file, '        $method = "'.$this->method.'";' . PHP_EOL);

                    fwrite($file, '        //$response = static::createClient()->request(\'POST\', \''.$this->loginPath.'\', [\'json\' => '.str_replace(':', '=>',str_replace('}', ']',str_replace('{', '[',json_encode($this->usersCredentials)))).']);'. PHP_EOL);
                    fwrite($file, '        //$response = json_decode($response->getContent());' . PHP_EOL);
                    fwrite($file, '        //try{$token = $response->id_token;}' . PHP_EOL);
                    fwrite($file, '        //catch(\Exception $exception) {dump(\'ERREUR : Token null (file: '.$filename.') -> \'.$exception->getMessage());}' . PHP_EOL);

                    fwrite($file, '        $path = "'.$path.'";' . PHP_EOL);
                    fwrite($file, '        $requirement = "'.$this->requirement.'";' . PHP_EOL);
                    fwrite($file, '        $code = '.$code.';' . PHP_EOL);
                    fwrite($file, '        $data = '.str_replace(':', '=>',str_replace('}', ']',str_replace('{', '[',json_encode($this->data)))).';' . PHP_EOL);
                    fwrite($file, '        $filters = '.str_replace(':', '=>',str_replace('}', ']',str_replace('{', '[',json_encode($this->filters)))).';' . PHP_EOL);
                    fwrite($file, '        if ($requirement !== \'null\') {' . PHP_EOL);
                    fwrite($file, '            $segments = explode(\'/\', $path);' . PHP_EOL);
                    fwrite($file, '            $pathCut = \'/\'.$segments[1].\'/\'.$segments[2];' . PHP_EOL); //Recuperation du path pour un get (/api/...)
                    fwrite($file, '            $response = static::createClient()->request(\'GET\', $pathCut, [\'query\' => [\'deletedAt\'=>\'false\'], \'headers\' => [\'Accept\' => \'application/ld+json\', \'Authorization\' => \'Bearer \'. $this->token]]);' . PHP_EOL);
                    fwrite($file, '            $content = $response->toArray();' . PHP_EOL);
                    fwrite($file, '            $value = $content[\'hydra:member\'][count($content[\'hydra:member\'])-1][$requirement];' . PHP_EOL);
                    fwrite($file, '            if ($requirement == \'@id\') {' . PHP_EOL);
                    fwrite($file, '                $path = $value;' . PHP_EOL);
                    fwrite($file, '                if (preg_match(\'/\/[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i\', $path) !== 1 && $method != \'GET\') {' . PHP_EOL);
                    fwrite($file, '                    $path = substr($path, 0, strrpos($path, \'/\'));' . PHP_EOL);
                    fwrite($file, '                }' . PHP_EOL);
                    fwrite($file, '            }' . PHP_EOL);
                    fwrite($file, '            else {' . PHP_EOL);
                    fwrite($file, '                preg_replace(\'/\{' . $this->requirement . '}/\', $value, $path);' . PHP_EOL);
                    fwrite($file, '            }' . PHP_EOL);
                    fwrite($file, '        }' . PHP_EOL);
                    fwrite($file, '        dump(\'----------------------------------------\', $method . \' | \' . $path . \' | \' . $code . \' | \' . json_encode($data) . \' | \' . json_encode($filters));' . PHP_EOL);
                    fwrite($file, '        try {' . PHP_EOL);
                    fwrite($file, '             static::createClient()->request($method, $path, [\'query\' => [\'deletedAt\'=>\'false\'], \'headers\' => [\'Accept\' => \''.$this->format.'\', \'Authorization\' => \'Bearer \'.$this->token], \'json\' => $data]);'. PHP_EOL);
                    fwrite($file, '             $this->assertResponseStatusCodeSame($code, "Le code de statut de réponse HTTP devrait être égal à $code");' . PHP_EOL);
                    fwrite($file, '        } catch (\Exception $e) {' . PHP_EOL);
                    fwrite($file, '             $this->throwError($path,$e->getMessage());' . PHP_EOL);
                    fwrite($file, '        }' . PHP_EOL);
                    fwrite($file, '    }' . PHP_EOL);
                    fwrite($file, '}' . PHP_EOL);
                    fclose($file);
                }

                //Tests END-POINTS
                if(!in_array($pathCurrent, $this->fileGeneration) && !in_array($pathCurrent, $this->incorrectPath) && !in_array($pathCurrent, $this->filePath)) {
                    dump('-------------------------------', $this->method . ' | ' . $path . ' | ' . $code . ' | ' . json_encode($this->filters) . ' | ' . json_encode($this->data));
                    try {
                        static::createClient()->request($this->method, $path, ['query' => ['deletedAt' => 'false'], 'headers' => ['Accept' => $this->format, 'Authorization' => 'Bearer ' . $this->token], 'json' => $this->data]);
                        $this->assertResponseStatusCodeSame($code, "Le code de statut de réponse HTTP devrait être égal à $code");
                    } catch (\Exception $e) {
                        $this->throwError($pathCurrent, $e->getMessage());
                    }
                }
                $compteur +=1;
            }
        }
    }
}

