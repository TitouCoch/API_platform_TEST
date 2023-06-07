<?php

namespace App\Tests\API;
use Ramsey\Uuid\Uuid;

class JsonTest extends AAMethodTest
{
    protected string $method;
    protected array $data = [];
    protected array $filters = [];
    protected array|string|null $format;
    protected string|array|null $json;
    protected string $requirement;

    protected array $incorrectPath = [ //Incorrect Path who can not be called
        //Method Not Allow
        '/api/ownership_requests/{uuid}', //Method not allow (PUT/DELETE)
        '/api/attributes/{uuid}', //Method now allow
        '/api/activities/{uuid}', //Method not allow
        '/api/webs/{uuid}', //DELETE Method not allowed
        '/api/configs/{id}', //Method not allow (PUT/DELETE)
        '/api/metadata/{uuid}', //Method not allow
        '/api/ownership_requests/{uuid}/accept', //Method not allow (PUT/DELETE)
        '/api/ownership_requests/{uuid}/refuse', //Method not allow (PUT/DELETE)

        //SQL Constraint (PUT/DELETE)
        '/api/directory_departments/{uuid}',// DELETE foreign key contraint
        '/api/media/{uuid}', //DELETE SQL constraint
        '/api/person_stat_views/{uuid}', //Error dataFixture (fullName SQL contraint not updatable)
        '/api/offmap_categories/{uuid}', //DELETE SQL contraint
        '/api/pictures/{uuid}', // Delete Constraint
        '/api/regions/{id}', //SQL contrainst
        '/api/people/random', //Error dataFixture (fullName SQL contraint not updatable)
        '/api/offmaps/{uuid}', //DELETE SQL contraint
        '/api/directory_countries/{uuid}', //DELETE SQL contraint
        '/api/people/{uuid}', //DELETE SQL contraint
        '/api/directory_countries',

        //Require Mock Action
        '/api/activities', //POST Format data multipart data required
        '/api/mobiles/{uuid}/click', // Need click on web browser
        '/api/mobiles/{uuid}/duplicate', // Need file in cache/default
        '/api/pictures/{uuid}/rotate', // Zero size image string passed
        '/api/point_of_interests/{uuid}', //Handling
        '/api/offmaps/{uuid}/pack', //Handling
        '/api/main_revisions', //Handling App\\Query\\Revision\\GetMainRevisionsSearchQuery failed
        '/api/media', //File required
        '/api/metadata/sync', //Need loaded file
        '/api/media/{uuid}/download', //Unable to check cache
        '/api/metadata/seo', //Unable to check cache
        '/api/trips/{uuid}/pdf', //Method GeneratePdfCommand failed
        '/api/payments/succeeded-sum', //Serialisation to pdf
        '/api/promo_codes/import', //POST required file
        '/api/press_releases/{uuid}', // Attribut problem (media, pdf)
        '/api/me/delete_account',

        //Local problem
        '/api/activities/translate', //No locale has been set and current locale is undefined
        '/api/comments', //POST Local attribut problem
        '/api/mobiles', //POST Local attribut problem
        '/api/press_releases',//POST Local attribut problem

        //Require fixture
        '/api/comments/{uuid}', //Need fixture
        '/api/comments/{uuid}/validate', //Need fixture
        '/api/comments/{uuid}/comments', //Need fixture
        '/api/people/{uuid}/comments', //Need fixture

        //Attribut not recognised
        '/api/directory_countries/slug',// POST attribut problem (media)
        '/api/configs', //POST attribut problem (require attribut name who is yet present)
        '/api/countries', //POST attribut problem (require attribut alpha2 who is yet present)
        '/api/locales', //POST attribut problem (require attribut alpha2 who is yet present)
        '/api/locale_messages', //POST attribut problem (require attribut locale who is yet present)
        '/api/partners', //POST attribut problem (require attribut locale who is yet present)
        '/api/partners/slug', //POST attribut problem (require attribut locale who is yet present)

        //Other
        '/api/ownership_request_views/{id}', // Not table Found
        '/api/mobiles/current', //Not found
        '/api/mobiles/{uuid}', //Command not found
        '/api/partners/{uuid}', //GET uuid problem
        '/api/trips', //Post attribute (comments
        '/api/trips/share', //Post attribute (title)
        '/api/revisions/by_department', //Undefined array key department
        '/api/point_of_interests', // Require Multipart form data
        '/api/promo_codes/use', //required string in a GET
        '/api/revisions/{uuid}', //Error: __clone method called on non-object
        '/api/pictures/actions', // POST attribut problem (media)
        '/api/revisions', //Item not found point of interest type
        '/api/pictos/{uuid}',
        '/api/pictos',
        '/api/revisions/{uuid}/share_email',
        '/api/revisions/{uuid}/validate',
        '/api/revisions/{uuid}/opening_hours',
        '/api/revisions/{uuid}/payment_types',
        '/api/revisions/{uuid}/pdf',
        '/api/revisions/{uuid}/revision_attributes',
        '/api/revisions/{uuid}/opening_dates',
        '/api/trip_map_points/{uuid}',
        '/api/revision_attributes/{uuid}',
        '/api/person_point_of_interest/{uuid}', //work need to cut the s
        '/api/person_promo_codes', //work need to cut the s
    ];

    protected array $filePath = [ //Correct Path who are generated in a file
        '/api/attributes',
        '/api/activities/{uuid}',
        '/api/activities/{uuid}/contact',
        '/api/activities/{uuid}/medias',
        '/api/activities/{uuid}/validate',
        '/api/attributes',
        '/api/camper_vans/{uuid}',
        '/api/countries/{id}',
        '/api/directory_countries/{uuid}',
        '/api/directory_countries/slug',
        '/api/directory_departments',
        '/api/elastic_activities',
        '/api/offmaps',
        '/api/offmaps/{uuid}/images',
        '/api/opening_dates',
        '/api/opening_hours',
        '/api/ownership_requests',
        '/api/partners/{uuid}',
        '/api/password/request',
        '/api/payments/account_premium/apple_pay',
        '/api/payments/account_premium/google_pay',
        '/api/payments/{uuid}',
        '/api/payments/{uuid}/pdf',
        '/api/payments/{uuid}/refund-keep',
        '/api/payments/{uuid}/refund-remove',
        '/api/person_point_of_interest',
        '/api/person_point_of_interest/{uuid}',
        '/api/people/{uuid}/substitute',
        '/api/person_promo_codes',
        '/api/pictures',
        '/api/point_of_interest_gps',
        '/api/point_of_interests/mine',
        '/api/point_of_interests/{uuid}/delete_notify',
        '/api/point_of_interests/{uuid}/main_revision',
        '/api/point_of_interests/{uuid}/transfer_comments',
        '/api/point_of_interests/{uuid}/pictures',
        '/api/point_of_interests/{uuid}/transfer_pictures',
        '/api/press_releases',
        '/api/promo_codes',
        '/api/person_promo_codes/{id}',
        '/api/promo_codes/{uuid}',
        '/api/public_revisions',
        '/api/register',
        '/api/revision_attributes',
        '/api/revisions/latest_favorites',
        '/api/revisions/latest_main_with_pictures',
        '/api/revisions//api/revisions/main_with_pictures',
        '/api/revisions/my_main',
        '/api/revisions/{uuid}/increment',
        '/api/revisions/{uuid}/select',
        '/api/steps',
        '/api/trip_countries',
        '/api/trip_categories',
        '/api/trip_map_points/{uuid}',
        '/api/trips/{uuid}',
        '/api/webs/{uuid}/click',
        '/api/webs/{uuid}/duplicate',
        '/api/awards/{uuid}', //Works in prod
        '/api/awards',  //Works in prod
        '/api/directory_countries', //work in prod POST
        '/api/directory_countries/{uuid}', //work in prod POST
        '/api/directory_departments', //work in prod
        '/api/person_point_of_interest', //work in prod
        '/api/people/{uuid}', //work in prod
    ];

    protected array $fileGeneration = //To generate path in a file (put true in the $testInSeparateFile variable
    [

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
                return preg_replace('/\{id}/', strval($unId), $path);
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
                    return preg_replace('/\{uuid}/', strval($uuid), $path);
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

    public function getFileName(string $component): string
    {
        $segments = explode('/', trim($component, '/'));
        $folderName = $segments[1];
        $directoryPath = __DIR__ . '/EndPoint/' . $folderName;

        $files = scandir($directoryPath, SCANDIR_SORT_DESCENDING);
        $highestNumber = 0;
        foreach ($files as $file) {
            if (preg_match('/(\d+)/', $file, $matches)) {
                $number = intval($matches[0]);
                if ($number > $highestNumber) {
                    $highestNumber = $number;
                }
            }
        }
        $highestNumber += 1;
        $filename = "";
        foreach ($segments as $segment) {
            $filename .= str_replace(array('_', '{', '}','-','.'), array('', '_', '_','_','_'), ucwords($segment, '_'));
        }
        return '_'.$highestNumber.'_'.$filename;
    }

    public function testAPI(): void
    {
        $json = file_get_contents(__DIR__ . '/openapi.json');
        $this->json = json_decode($json,true);

        //Get path
        foreach ($this->json['paths'] as $pathCurrent => $methodAction) {
            $segments = explode('/', trim($pathCurrent, '/'));
            $folderName = $segments[1];

            $file = fopen("./phpunit.xml.dist", "a");
            $filename = './phpunit.xml.dist';

            $fileContents = file_get_contents($filename);
            $position = strrpos($fileContents, '</testsuites>');
            $exist = strrpos($fileContents, 'tests/API/EndPoint/'.$folderName);

            if ($position !== false && !$exist) {
                $newContents = substr_replace($fileContents,
                    "        <testsuite name='{$folderName}'>\n            <directory>tests/API/EndPoint/{$folderName}</directory>\n        </testsuite>\n"
                    , $position, 0);
                file_put_contents($filename, $newContents);
            }
            fclose($file);


            $directoryPath = __DIR__ . '/EndPoint/' . $folderName;
            if (!is_dir($directoryPath)) {
                mkdir($directoryPath);
            }
            if(in_array($pathCurrent,$this->incorrectPath)){
                continue;
            }

            $path = $this->verifyPath($pathCurrent);

            if(!isset($path)){
                dump('ERROR : VerifyPath('.$pathCurrent. ') return null value ');
            }

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
                    $filename = $this->getFileName($pathCurrent);
                    $file = fopen("./tests/API/EndPoint/".$folderName."/".$filename."_".$this->method."_Test.php", "w");

                    fwrite($file, '<?php' . PHP_EOL);
                    fwrite($file, 'namespace App\Tests\API\EndPoint\\'.explode('/', trim($pathCurrent, '/'))[1] .';'. PHP_EOL);
                    fwrite($file, 'use App\Tests\API\AAMethodTest;' . PHP_EOL);
                    fwrite($file, 'class '.$filename.'_'.$this->method.'_Test extends AAMethodTest {' . PHP_EOL);
                    fwrite($file, '    public function test(): void {' . PHP_EOL);
                    fwrite($file, '        $method = "'.$this->method.'";' . PHP_EOL);
                    fwrite($file, '        $path = "'.$path.'";' . PHP_EOL);
                    fwrite($file, '        $requirement = "'.$this->requirement.'";' . PHP_EOL);
                    fwrite($file, '        $code = '.$code.';' . PHP_EOL);
                    fwrite($file, '        $data = '.str_replace(':', '=>',str_replace('}', ']',str_replace('{', '[',json_encode($this->data)))).';' . PHP_EOL);
                    fwrite($file, '        $filters = '.str_replace(':', '=>',str_replace('}', ']',str_replace('{', '[',json_encode($this->filters)))).';' . PHP_EOL);
                    fwrite($file, '        if ($requirement !== \'null\') {' . PHP_EOL);
                    fwrite($file, '            $segments = explode(\'/\', $path);' . PHP_EOL);
                    fwrite($file, '            $pathCut = \'/\'.$segments[1].\'/\'.$segments[2];' . PHP_EOL); //Recuperation du path pour un get (/api/...)
                    fwrite($file, '            $response = static::createClient()->request(\'GET\', $pathCut, [\'query\' => [\'deletedAt\'=>\'false\'], \'headers\' => [\'Accept\' => \'application/ld+json\', \'Authorization\' => \'Bearer \'. self::$token]]);' . PHP_EOL);
                    fwrite($file, '            $content = $response->toArray();' . PHP_EOL);
                    fwrite($file, '            $value = $content[\'hydra:member\'][count($content[\'hydra:member\'])-1][$requirement];' . PHP_EOL);
                    fwrite($file, '            if ($requirement == \'@id\') {' . PHP_EOL);
                    fwrite($file, '                $path = $value;' . PHP_EOL);
                    fwrite($file, '                if (preg_match(\'/\/[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i\', $path) !== 1 && $method != \'GET\') {' . PHP_EOL);
                    fwrite($file, '                    $path = substr($path, 0, strrpos($path, \'/\'));' . PHP_EOL);
                    fwrite($file, '                }' . PHP_EOL);
                    fwrite($file, '            }' . PHP_EOL);
                    fwrite($file, '            else {' . PHP_EOL);
                    fwrite($file, '                preg_replace(\'/\{' . $this->requirement . '}/\', strval($value), $path);' . PHP_EOL);
                    fwrite($file, '            }' . PHP_EOL);
                    fwrite($file, '        }' . PHP_EOL);
                    fwrite($file, '        dump(\'----------------------------------------\', $method . \' | \' . $path . \' | \' . $code . \' | \' . json_encode($data) . \' | \' . json_encode($filters));' . PHP_EOL);
                    fwrite($file, '        try {' . PHP_EOL);
                    fwrite($file, '             static::createClient()->request($method, $path, [\'query\' => [\'deletedAt\'=>\'false\'], \'headers\' => [\'Accept\' => \''.$this->format.'\', \'Authorization\' => \'Bearer \'.self::$token], \'json\' => $data]);'. PHP_EOL);
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
                        static::createClient()->request($this->method, $path, ['query' => ['deletedAt' => 'false'], 'headers' => ['Accept' => $this->format, 'Authorization' => 'Bearer ' . self::$token], 'json' => $this->data]);
                        $this->assertResponseStatusCodeSame($code, "Le code de statut de réponse HTTP devrait être égal à $code");
                    } catch (\Exception $e) {
                        $this->throwError($pathCurrent, $e->getMessage());
                    }
                }

            }
        }
    }
}

