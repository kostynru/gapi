<?php
// Getting API - Realise Version 
// Copyright (C) 2012 Shelko Konstantin
// If you want to help me, please, write:
// kostynru@ymail.com
// vk.com/shelko_kostya
// vk.com/gettingapi
// -----------------------------------------------------------
// It's main file of Getting API. Please don't edit it manualy.
// You may only edit database connection options.
error_reporting(E_ERROR | E_WARNING | E_PARSE);
define('BASE_CLASS', true);
class ApiException extends Exception
{
    private $error_num;

    public function __construct($error_num)
    {
        $this->error_num = $error_num;
    }

    private function _error_to_xml($error_code = '1', $type = 'xml')
    {
        $error_codes = array(
            1 => "Unknown error has been encountered",
            2 => "Wrong count of params",
            3 => "Invalid application id",
            4 => "Required method does not exist",
            5 => "The key has expired or session has been closed",
            6 => "Invalid application id or secret key",
            7 => "Required space does not exist",
            8 => "Hmm...you are donkey",
            9 => "Storable procedure does not exist",
            10 => "Storable procedure has expired");
        $err_msg = $error_codes[$error_code];
        $time = time();
        if ($type == null or empty($type) or $type == 'xml') {
            header('Content-type: application/xml');
            $xml = <<< EOXML
<?xml version="1.0" encoding="utf-8" ?>
	<api type="gettingapi">
		<error err_code="$error_code">
			<answer time="$time">$err_msg</answer>
		</error>
	</api>
EOXML;
            return trim($xml);
        } elseif ($type == 'json') {
            header('Content-type: application/json');
            $json = array('error' => $err_msg, 'error_code' => $error_code);
            $json = json_encode($json);
            return $json;
        } else {
            echo 'Wrong type of answer';
        }
    }

    public function getErrorString($type = 'xml')
    {
        return $this->_error_to_xml($this->error_num, $type);
    }
}

if (count($_GET) and isset($_GET['q'])) {
//Database connection configuration
    mysql_connect('localhost', 'root', '');
    mysql_select_db('api');
//End of user-configurable part!
//Warning!! Hardcode is being began here!!!
} else {
    header('Content-type: application/xml');
    $xml = <<<EOXML
<?xml version="1.0" encoding="utf-8" ?>
	<api type="gettingapi">
		<answer>Welcome to {$_SERVER['SERVER_NAME']}</answer>
	</api>
EOXML;
    echo $xml;
    exit;
}
$params = $_GET;
$spaceandmethod = explode(".", $params['q']);
$space = $spaceandmethod[0];
@$method = $spaceandmethod[1];
$time = time();
//Errors' visualising function
//------------------------------------
function error_to_xml($error_code = '1', $type = 'xml')
{
    $error_codes = array(
        1 => "Unknown error has been encountered",
        2 => "Wrong count of params",
        3 => "Invalid application id",
        4 => "Required method does not exist",
        5 => "The key has expired or session has been closed",
        6 => "Invalid application id or secret key",
        7 => "Required space does not exist",
        8 => "Hmm...you are donkey",
        9 => "Storable procedure does not exist",
        10 => "Storable procedure has expired");
    $err_msg = $error_codes[$error_code];
    $time = time();
    if ($type == null or empty($type) or $type == 'xml') {
        header('Content-type: application/xml');
        $xml = <<< EOXML
<?xml version="1.0" encoding="utf-8" ?>
	<api type="gettingapi">
		<error err_code="$error_code">
			<answer time="$time">$err_msg</answer>
		</error>
	</api>
EOXML;
        return trim($xml);
    } elseif ($type == 'json') {
        header('Content-type: application/json');
        $json = array('error' => $err_msg, 'error_code' => $error_code);
        $json = json_encode($json);
        return $json;
    } else {
        echo 'Wrong type of answer';
    }
}

//------------------------------------
function safe_param($string)
{
    if (!is_string($string)) {
        return false;
    }
    $string = preg_replace('/\s/', '', $string);
    return $string;
}

//If param is 'auth' - begin the auth process
if ($params['q'] == 'auth') {
    $app_key = $params['app_key'];
    $app_id = $params['app_id'];
    $app_key = safe_param($app_key);
    $response_type = $params['response'];
    $response_type = safe_param($response_type);
    if ($app_key == null or $app_id == null or $response_type == null) {
        echo error_to_xml(2, $response_type);
        exit;
    }
    if (!is_numeric($app_id)) {
        echo error_to_xml(3, $response_type);
        exit;
    }
    $query = "SELECT private_key FROM apps_access WHERE app_id = '$app_id'";
    $result = mysql_query($query) or die(mysql_error());
    while ($row = mysql_fetch_row($result)) {
        $app_secret = $row[0];
    }
    if (empty($app_secret)) {
        echo error_to_xml(3, $response_type);
        exit;
    }
    if ($app_key == $app_secret) {
        $app_token = str_shuffle(uniqid());
        $time = time();
        switch ($response_type) {
            case 'xml':
                $response = 1;
                break;
            case 'json':
                $response = 2;
                break;
            default:
                $response = 1;
        }
        $query = sprintf("INSERT INTO apps_sessions(app_id, datastamp, app_token, response_type) VALUES
		('%d', '%d', '%s', '%d')",
            mysql_real_escape_string($app_id),
            mysql_real_escape_string($time),
            mysql_real_escape_string($app_token),
            mysql_real_escape_string($response));
        mysql_query($query) or die(mysql_error());
        if ($response_type == 'xml') {
            header('Content-type: application/xml');
            echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>
			<api type=\"gettingapi\">
				<$space>
					<answer time=\"$time\">$app_token</answer>
				</$space>
			</api>";
            exit;
        } else {
            header('Content-type: application/json');
            echo ' { "token" : "' . $app_token . '" }';
            exit;
        }
    } else {
        error_to_xml(6, $response_type);
        exit;
    }
//Else, begin to call to the method
} else {
    $l_space = ucfirst($space);
    /*if ($space == 'store') {
        goto store; //костыль!
    }*/

    if (file_exists("spaces/$space.php")) {
        include_once '/spaces/' . $space . '.php';
        $method_ex = array($l_space, $method);
        if (!is_callable($method_ex, true) or !method_exists($space, $method) and !function_exists($method)) {
            echo error_to_xml(4, $response_type);
            exit;
        }
        $token = $_GET['token'];
        $token = safe_param($token);
        $query = sprintf("SELECT * FROM apps_sessions WHERE app_token = '%s'",
            mysql_real_escape_string($token));
        $result = mysql_query($query) or die(mysql_error());
        if ($result == false) {
            echo error_to_xml(5, $response_type);
            exit;
        }
        while ($row = mysql_fetch_assoc($result)) {
            $response = $row['response_type'];
            $date = $row['datastamp'];
            $app_id = $row['app_id'];
        }
        switch ($response) {
            case '1':
                $err_resp = 'xml';
                break;
            case '2':
                $err_resp = 'json';
                break;
            default:
                $err_resp = 'xml';
        }
        $now_date = time();
        if ($now_date - $date > 3600) {
            $query = sprintf("DELETE FROM apps_sessions WHERE app_id = '%d'",
                mysql_real_escape_string($app_id));
            mysql_query($query) or die(mysql_error());
            echo error_to_xml(5, $err_resp);
            exit;
        }
        /*if (isset($_GET['store'])) {
            include_once 'spaces\storable_methods.php';
            $st = new Storable();
            $st->store();
            exit;
        }
        if ($space = 'storable') {
            store:
            $query = sprintf("SELECT * FROM apps_sessions WHERE app_token = '%s'",
                mysql_real_escape_string($token));
            $result = mysql_query($query) or die(mysql_error());
            if ($result == false) {
                echo error_to_xml(5, $response_type);
                exit;
            }
            while ($row = mysql_fetch_assoc($result)) {
                $response = $row['response_type'];
                $date = $row['datastamp'];
                $app_id = $row['app_id'];
            }
            switch ($response) {
                case '1':
                    $err_resp = 'xml';
                    break;
                case '2':
                    $err_resp = 'json';
                    break;
                default:
                    $err_resp = 'xml';
            }
            include_once 'spaces\storable_methods.php';
            $st = new Storable();
            if ($method = 'expired') {
                $exp = $st->is_expired(safe_param($_GET['token']));
                if ($err_resp == 'xml') {
                    header('Content-type: application/xml');
                    echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>
                            <api type=\"gettingapi\">
                            <store>
                            <answer time=\"$time\">$exp</answer>
                            </store>
                            </api>";
                    exit;
                } else {
                    header('Content-type: application/json');
                    echo json_encode($exp);
                    exit;
                }
            }
        }*/
        if (class_exists($space)) {
            $process = new $l_space;
            $answer = $process->$method($params);
        } else {
            $answer = $method();
        }
        function multidem_array_to_xml(array $arr)
        {
            foreach ($arr as $key => $value) {
                if (is_array($value)) {
                    echo "<array key=\"$key\">";
                    multidem_array_to_xml($value);
                    echo "</array>";
                } else {
                    echo "<$key>$value</$key>";
                }
            }
            return null;
        }

        if (is_array($answer)) {
            if ($response == 1) {
                //Encoding answer in XML
                header('Content-type: application/xml');
                echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>
							<api type=\"gettingapi\">
							<$space>
								<$method>
								<answer time=\"$time\">";
                multidem_array_to_xml($answer);
                echo "
								</answer>
						</$method>
					</$space>	
				</api>";
                exit;
            } else {
                //Encoding answer in JSON
                header('Content-type: application/json');
                $content = array('api' => array('answer' => $answer));
                echo json_encode($content);
                exit;
            }
        } else {
            //Encoding answer in XML
            if ($response == 1) {
                header('Content-type: application/xml');
                echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>
						<api type=\"gettingapi\">
						<$space>
							<$method>
							<answer time=\"$time\">$answer</answer>
							</$method>
						</$space>	
						</api>";
                exit;
            } elseif ($response == 2) {
                //Encoding answer in JSON
                $content = array('api' => array('answer' => $answer));
                header('Content-type: application/json');
                echo json_encode($content);
                exit;
            }
        }
    } else {
        echo error_to_xml(7, $err_resp);
        exit;
    }
}

