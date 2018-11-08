<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 10/9/17
 * Time: 2:27 PM
 */

namespace Monitoring\Smith;

use Dotenv\Dotenv;

/**
 * Class Client
 * @package monitoring/smithclient
 */
class Client
{
    /**
     * API_KEY
     *
     * @var string
     */
    protected $apikey;

    /**
     * API_URL
     *
     * @var string
     */
    protected $apiurl;

    /**
     * @var integer
     */
    private $recordid = null;

    /**
     * @var string
     */
    private $projectname;

    /**
     * @var string
     */
    private $taskname;

    /**
     * @var string
     */
    private $author;

    /**
     * Constructor
     *
     * @param string $author
     * @param string $projectname
     * @param string $taskname
     */
    public function __construct($author, $projectname, $taskname)
    {
        // Load configuration file .env
        if(!empty($_SERVER['DOCUMENT_ROOT']))
        {
            $dotenv = new Dotenv($_SERVER['DOCUMENT_ROOT']);
        }
        else
        {
            // Used for laravel projects
            $dotenv = new Dotenv(getcwd());
        }
        $dotenv->load();

        // Set apikey token
        $this->apikey = getenv('SMITH_API_KEY');
        $this->apiurl = getenv('SMITH_API_URL') . (substr(getenv('SMITH_API_URL'), -1) == '/' ? '' : '/');

        // Set projectname, name
        $this->author = $author;
        $this->projectname = $projectname;
        $this->taskname = $taskname;
    }

    /**
     * Initiating monitoring record with returning unique id
     *
     * @param int|string $expectedtime
     * @return bool|int
     */
    public function start($expectedtime)
    {
        // Initialize curl
        $curl = curl_init();

        // Generate url
        $url = $this->apiurl . 'start?';
        $params = [
            'author' => $this->author,
            'project' => $this->projectname,
            'taskname' => $this->taskname,
            'expected_time' => $expectedtime,
            'apikey' => $this->apikey
        ];
        $url .= http_build_query($params);

        // Set curl options
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json'
            ],
            CURLOPT_TIMEOUT => 30
        ]);

        // Send the request & save response to $resp
        $response = curl_exec($curl);

        // Get code
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // Close request to clear up some resources
        curl_close($curl);

        // Response to json
        $responsejson = json_decode($response);

        if($httpcode >= 200 && $httpcode < 300)
        {
            $this->recordid = $responsejson->id;
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Update comment of the record
     *
     * @param string $comment
     * @return bool|mixed
     */
    public function comment($comment)
    {
        // Initialize curl
        $curl = curl_init();

        // Generate url
        $url = $this->apiurl . 'comment?';
        $params = [
            'id' => $this->recordid,
            'comment' => $comment,
            'apikey' => $this->apikey
        ];
        $url .= http_build_query($params);

        // Set curl options
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json'
            ],
            CURLOPT_TIMEOUT => 30
        ]);

        // Send the request & save response to $resp
        curl_exec($curl);

        // Get code
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // Close request to clear up some resources
        curl_close($curl);

        if($httpcode >= 200 && $httpcode < 300)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Set record as finished
     *
     * @param string|null $comment
     * @return bool|mixed
     */
    public function finish($comment = null)
    {
        // Initialize curl
        $curl = curl_init();

        // Generate url
        $url = $this->apiurl . 'finish?';
        $params = [
            'id' => $this->recordid,
            'comment' => $comment,
            'apikey' => $this->apikey
        ];
        $url .= http_build_query($params);

        // Set curl options
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json'
            ],
            CURLOPT_TIMEOUT => 30
        ]);

        // Send the request & save response to $resp
        curl_exec($curl);

        // Get code
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // Close request to clear up some resources
        curl_close($curl);

        if($httpcode >= 200 && $httpcode < 300)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Set record as failed
     *
     * @param string|null $comment
     * @return bool|mixed
     */
    public function fail($comment = null)
    {
        // Initialize curl
        $curl = curl_init();

        // Generate url
        $url = $this->apiurl . 'fail?';
        $params = [
            'id' => $this->recordid,
            'comment' => $comment,
            'apikey' => $this->apikey
        ];
        $url .= http_build_query($params);

        // Set curl options
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json'
            ],
            CURLOPT_TIMEOUT => 30
        ]);

        // Send the request & save response to $resp
        curl_exec($curl);

        // Get code
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // Close request to clear up some resources
        curl_close($curl);

        if($httpcode >= 200 && $httpcode < 300)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Get current recordid
     *
     * @return int
     */
    public function getRecordId()
    {
        return $this->recordid;
    }
}