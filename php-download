#!/usr/bin/env php
<?php
//URLs please without trailing slash
define('DOWNLOAD_URL', 'https://backend.php-download.com');;
class Http
{
    public static function downloadFile($url, $localFilePath, $apiKey)
    {
        $options = array('http' => array('method' => 'GET', 'header' => 'Content-type: application/json
API-Key: ' . $apiKey, 'ignore_errors' => true));
        $streamContext = stream_context_create($options);
        $fp = fopen($url, 'r', false, $streamContext);
        $file = fopen($localFilePath, 'w');
        stream_copy_to_stream($fp, $file);
    }
    public static function call($url, $method, $apiKey, $data = null)
    {
        $options = array('http' => array('method' => strtoupper($method), 'header' => 'Content-type: application/json
API-Key: ' . $apiKey, 'ignore_errors' => true));
        if ($data) {
            $options['http']['content'] = json_encode($data);
        }
        $streamContext = stream_context_create($options);
        $result = file_get_contents($url, false, $streamContext);
        /**
         * @var array $http_response_header materializes out of thin air
         */
        $status_line = $http_response_header[0];
        preg_match('{HTTP\\/\\S*\\s(\\d{3})}', $status_line, $match);
        $status = $match[1];
        if ($status !== '200') {
            if ($result = json_decode($result, true)) {
                die(('Request failed: ' . $result['message']) . PHP_EOL);
            } else {
                echo $result;
                die("Request failed. Unknown error, please contact the support. [Status: {$status}]" . PHP_EOL);
            }
        }
        return $result;
    }
};
class CommandLine
{
    const FILE_NAME_CACHED_INPUT = '.php-download.com';
    const YES_NO_SELECTION = array('Yes', 'No');
    const SCRIPT_COMMAND = 'php-download';
    private $apiKey;
    private $projectId;
    private $scriptName;
    private $action;
    private $argument;
    public function __construct()
    {
        $this->parseArguments();
        $apiKey = $this->getCachedValue('apiKey');
        if (!$apiKey) {
            $this->apiKeyInsertion();
        } else {
            $this->apiKey = $apiKey;
            $response = Http::call(DOWNLOAD_URL . '/api/v1/users/login', 'post', $this->apiKey, array());
            if ($this->handleApiResponse($response) === false) {
                $this->error('Your cached API key is wrong. Please insert a new one.');
                $this->apiKeyInsertion();
            }
        }
        switch ($this->action) {
        case 'download':
            $this->downloadAction();
            break;
        case 'silent':
            $this->silentAction();
            break;
        case 'require':
            if (!$this->argument) {
                $this->error(('For the command \'require\' you need the package name as parameter. E. g. \'' . self::SCRIPT_COMMAND) . ' require phpoffice/phpspreadsheet\'.', true);
            }
            $this->handleComposerCommand();
            break;
        case 'install':
            if (!file_exists('composer.json')) {
                $this->error('There is no composer.json in the current folder.', true);
            }
            $this->handleComposerCommand();
            break;
        }
    }
    private function parseArguments()
    {
        $args = $_SERVER['argv'];
        $wrongParameterMessage = 'The first parameter should be one of the following commands: ' . PHP_EOL;
        foreach ($this->getActions() as $action => $description) {
            $wrongParameterMessage .= (((str_pad('', 4) . str_pad($action, 7)) . ' - ') . $description) . PHP_EOL;
        }
        foreach ($args as $index => $arg) {
            if ($index == 0) {
                $this->scriptName = $arg;
            } elseif ($index == 1 && !in_array($arg, array_keys($this->getActions()))) {
                $this->error($wrongParameterMessage, true);
            } elseif ($index == 1) {
                $this->action = $arg;
            } elseif ($index == 2) {
                $this->argument = $arg;
            }
        }
        if (!$this->action) {
            $this->error($wrongParameterMessage, true);
        }
    }
    public function deleteDir($dir)
    {
        if (substr($dir, 0, strlen('vendor')) !== 'vendor') {
            $this->error('Error on deleting the vendor folder', true);
        }
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if (is_dir(($dir . DIRECTORY_SEPARATOR) . $object) && !is_link((($dir . '/') . $object))) {
                        $this->deleteDir(($dir . DIRECTORY_SEPARATOR) . $object);
                    } else {
                        unlink(($dir . DIRECTORY_SEPARATOR) . $object);
                    }
                }
            }
            rmdir($dir);
        }
    }
    private function selectInput($message, $possibilities, callable $handleInput, $defaultValueIndex = null)
    {
        if ($defaultValueIndex !== null) {
            $this->message(('Enter without input to choose [' . $possibilities[$defaultValueIndex]) . ']');
        }
        $this->message($message);
        foreach ($possibilities as $index => $possibility) {
            echo ((($index + 1) . '. ') . $possibility) . PHP_EOL;
        }
        $input = readline('>> ');
        $this->lbr();
        if (empty($input) && $defaultValueIndex !== null) {
            return $handleInput($defaultValueIndex + 1);
        }
        if ($input === '' || !in_array(intval($input), array_keys($possibilities), true) && count($possibilities) != $input) {
            $this->error('Invalid input. Please try again!.');
            return $this->selectInput($message, $possibilities, $handleInput, $defaultValueIndex);
        }
        $handledInput = $handleInput($input);
        if ($handledInput === false) {
            $this->error('Something went wrong. Please try again!');
            return $this->selectInput($message, $possibilities, $handleInput, $defaultValueIndex);
        }
        return $handledInput;
    }
    private function textInput($message, callable $handleInput, $defaultInput = null)
    {
        if (!is_null($defaultInput)) {
            $this->message(('Enter without input to choose [' . $defaultInput) . ']');
        }
        $this->message($message);
        $input = readline('>> ');
        $this->lbr();
        if (empty($input) && !is_null($defaultInput)) {
            $handleInput($defaultInput);
            return;
        }
        if ($handleInput($input)) {
            $this->error('Please try again!');
            $this->textInput($message, $handleInput, $defaultInput);
        }
    }
    private function message($message)
    {
        echo $message . PHP_EOL;
    }
    private function lbr()
    {
        echo PHP_EOL;
    }
    private function error($message, $cancelScript = false)
    {
        //TODO add red color
        if ($cancelScript) {
            die($message . PHP_EOL);
        } else {
            echo $message . PHP_EOL;
        }
    }
    private function handleApiResponse($response, $stopOnError = false)
    {
        $response = json_decode($response, true);
        if (!isset($response['success']) || !$response['success']) {
            if (isset($response['message'])) {
                $this->error($response['message'], $stopOnError);
            }
            return false;
        }
        return $response['data'];
    }
    private function saveInput()
    {
        return file_put_contents(self::FILE_NAME_CACHED_INPUT, json_encode(array('apiKey' => $this->apiKey, 'projectId' => $this->projectId)));
    }
    private function handleComposerCommand()
    {
        if (!$this->apiKey) {
            $this->saveInputInsertion();
        }
        $response = Http::call(DOWNLOAD_URL . '/api/v1/projects/create-from-files', 'post', $this->apiKey, array('composerFile' => file_exists('composer.json') ? base64_encode(file_get_contents('composer.json')) : null, 'composerLockFile' => file_exists('composer.lock') ? base64_encode(file_get_contents('composer.lock')) : null, 'authJsonFile' => file_exists('auth.json') ? base64_encode(file_get_contents('composer.lock')) : null));
        $project = $this->handleApiResponse($response, true);
        $this->message("Working with project '{$project['id']} - {$project['name']}'.");
        $this->projectId = $project['id'];
        $this->downloadVendorInsertion();
        $projectResponse = Http::call((DOWNLOAD_URL . '/api/v1/projects/') . $this->projectId, 'get', $this->apiKey);
        $projectResponse = $this->handleApiResponse($projectResponse, true);
        $this->safeFileReplace('lock', 'composer.lock', $projectResponse);
        $this->safeFileReplace('json', 'composer.json', $projectResponse);
    }
    private function safeFileReplace($resultKey, $fileName, $downloadResult)
    {
        if (isset($downloadResult[$resultKey]) && !empty($downloadResult[$resultKey])) {
            if (file_exists($fileName)) {
                rename($fileName, $fileName . '.bak');
            }
            file_put_contents($fileName, $downloadResult[$resultKey]);
        }
    }
    private function silentAction()
    {
        $this->apiKey = $this->getCachedValue('apiKey');
        $this->projectId = $this->getCachedValue('projectId');
        if (!$this->apiKey || !$this->projectId) {
            $this->error(((('Error on loading the data from the ' . self::FILE_NAME_CACHED_INPUT) . ' file. Please remove the ') . self::FILE_NAME_CACHED_INPUT) . ' file and restart the install process.', true);
        }
        $this->downloadVendor(class_exists('ZipArchive'));
    }
    private function getCachedValue($key)
    {
        if (!file_exists(self::FILE_NAME_CACHED_INPUT)) {
            return false;
        }
        $data = json_decode(file_get_contents(self::FILE_NAME_CACHED_INPUT), true);
        if (!$data) {
            $this->error(('Corrupt ' . self::FILE_NAME_CACHED_INPUT) . ' file.', true);
        }
        if (!isset($data[$key])) {
            return false;
        }
        return $data[$key];
    }
    private function downloadAction()
    {
        $this->message('**************************************************');
        $this->message(' Welcome to the php-download.com download wizard! ');
        $this->message('**************************************************');
        $this->lbr();
        $this->selectInput('Is this PHP script exactly in the folder where the vendor folder should be created?', self::YES_NO_SELECTION, function ($input) {
            if ($input == 2) {
                $this->error('Please move this PHP script to another location.', true);
            }
        }, 0);
        $response = Http::call(DOWNLOAD_URL . '/api/v1/projects/index', 'get', $this->apiKey);
        $projects = $this->handleApiResponse($response);
        $possibilities = array();
        foreach ($projects as $project) {
            $possibilities[] = $project['name'];
        }
        $this->selectInput('Please select a project.', $possibilities, function ($input) use($projects) {
            $this->projectId = $projects[$input - 1]['id'];
        }, 0);
        $this->saveInputInsertion();
        $this->downloadVendorInsertion();
    }
    private function downloadVendorInsertion()
    {
        if (file_exists('vendor')) {
            $this->selectInput('Delete the old vendor folder and replace it by a new one from php-download.com?', self::YES_NO_SELECTION, function ($input) {
                return $this->downloadVendor($input == 1 && class_exists('ZipArchive'));
            }, 1);
        } else {
            $this->downloadVendor(true);
        }
    }
    private function apiKeyInsertion()
    {
        $this->textInput('Please insert your API Key from php-download.com.', function ($input) {
            $this->apiKey = $input;
            try {
                $response = Http::call(DOWNLOAD_URL . '/api/v1/users/login', 'post', $this->apiKey, array());
                if ($this->handleApiResponse($response) === false) {
                    return true;
                }
            } catch (Exception $e) {
                $this->error($e->getMessage(), true);
            }
            return false;
        });
    }
    private function saveInputInsertion()
    {
        $this->selectInput(('Save your input (API key etc.) in a file called ' . self::FILE_NAME_CACHED_INPUT) . ' to skip this dialog in the future?', self::YES_NO_SELECTION, function ($input) {
            if ($input == 1) {
                if (!$this->saveInput()) {
                    $this->error('Error on save input. Do you have enough permissions?');
                    return true;
                }
            }
        }, 1);
    }
    private function downloadVendor($replace)
    {
        $counter = 0;
        while (true) {
            if ($counter > 100) {
                $this->error('Timout. Please check for error messages on the website.', true);
            }
            $getParams = '';
            if ($counter === 0 && $this->action === 'require') {
                $getParams = '&downloadType=REQUIRE&requirePackage=' . $this->argument;
            } else {
                if ($this->action === 'require') {
                    $getParams = '&downloadType=REQUIRE';
                }
            }
            $response = Http::call(((DOWNLOAD_URL . '/api/v1/projects/start-download?projectId=') . $this->projectId) . $getParams, 'get', $this->apiKey);
            if (!$response) {
                $this->error('Error on the HTTP call. Please contact the support.', true);
            }
            $response = json_decode($response, true);
            if (!$response) {
                $this->error('Error on JSON decode. Please contact the support.');
                $this->error(var_export($response, true), true);
            }
            if (isset($response['success']) && !$response['success']) {
                $this->error($response['message'], true);
            }
            if ($response['status'] !== 'complete') {
                sleep(2);
                echo '.';
                $counter++;
                continue;
            }
            echo PHP_EOL;
            $this->message('Starting the download. This takes some time, please be patient.');
            $tmpVendorZipFile = ('vendor_' . date('Y_m_d_H_i_s')) . '.zip';
            Http::downloadFile((DOWNLOAD_URL . '/api/v1/projects/download?projectId=') . $this->projectId, $tmpVendorZipFile, $this->apiKey);
            $this->message('Download finished.');
            if (!$replace) {
                $this->message("The new vendor folder is downloaded. Please extract the file {$tmpVendorZipFile} manually.");
                break;
            }
            $this->deleteDir('vendor');
            $zip = new ZipArchive();
            $res = $zip->open($tmpVendorZipFile);
            if ($res === true) {
                $zip->extractTo('.');
                $zip->close();
            } else {
                $this->error('Error on extracting the zip file.');
            }
            unlink($tmpVendorZipFile);
            $this->message('The new vendor folder is downloaded.');
            break;
        }
    }
    private function getActions()
    {
        return array('download' => ('Downloads the vendor folder and creates a ' . self::FILE_NAME_CACHED_INPUT) . ' file, to download the vendor folder in silent mode.', 'silent' => ('Starts the vendor download without any interaction. A ' . self::FILE_NAME_CACHED_INPUT) . ' file is necessary.', 'require' => 'Add a package to the local composer.json, build it on our server and download the vendor folder.', 'install' => 'Uses the local composer.json to download the vendor folder.');
    }
};
if (php_sapi_name() !== 'cli') {
    die('This script is only callable from the command line.');
}
$commandLine = new CommandLine();