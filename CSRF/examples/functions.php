<?php
session_start();

function store_in_session($key, $value)
{
    if (isset($_SESSION)) {
        $_SESSION[$key] = $value;
    }
}

function unset_session($key)
{
    $_SESSION[$key] = ' ';
    unset($_SESSION[$key]);
}

function get_from_session($key)
{
    if (isset($_SESSION[$key])) {
        return $_SESSION[$key];
    } else {
        return false;
    }
}

/**
 * @throws \Exception
 */
function csrfguard_generate_token($unique_form_name): string
{
    $token = md5(strtotime('now')); // PHP 7, or via paragonie/random_compat
    store_in_session($unique_form_name, $token);
    var_dump($unique_form_name, $token);
    return $token;
}

function csrfguard_validate_token($unique_form_name, $token_value): bool
{
    $token = get_from_session($unique_form_name);
    echo('start csrfguard_validate_token' . '<br>');
    echo $unique_form_name . ' = ' . $token . '<br>';
    echo('end csrfguard_validate_token' . '<br>');
    if (!is_string($token_value)) {
        return false;

    }

    $result = hash_equals($token, $token_value);
    unset_session($unique_form_name);
    return $result;
}

function csrfguard_replace_forms($form_data_html)
{
    $count = preg_match_all("/<form(.*?)>(.*?)<\\/form>/is", $form_data_html, $matches, PREG_SET_ORDER);
    if (is_array($matches)) {
        foreach ($matches as $m) {
            if (str_contains($m[1], "nocsrf")) {
                continue;
            }
            $name = "CSRFGuard_" . mt_rand(0, mt_getrandmax());
            $token = csrfguard_generate_token($name);
            $form_data_html = str_replace($m[0],
                "<form{$m[1]}>
<input type='hidden' name='CSRFName' value='{$name}' />
<input type='hidden' name='CSRFToken' value='{$token}' />{$m[2]}</form>", $form_data_html);
        }
    }
    return $form_data_html;
}

/**
 * @throws \Exception
 */
function csrf(): void
{
    $name = "CSRFGuard_" . mt_rand(0, mt_getrandmax());
    $token = csrfguard_generate_token($name);
    echo "<input type='hidden' name='CSRFName' value='{$name}' />";
    echo "<input type='hidden' name='CSRFToken' value='{$token}' />)";
}

function csrfguard_inject()
{
    $data = ob_get_clean();
    $data = csrfguard_replace_forms($data);
    echo $data;
}

function csrfguard_start()
{
    if (count($_POST)) {
        if (!isset($_POST['CSRFName']) or !isset($_POST['CSRFToken'])) {
            trigger_error("No CSRFName found, probable invalid request.", E_USER_ERROR);
        }
        $name = $_POST['CSRFName'];
        $token = $_POST['CSRFToken'];
        if (!csrfguard_validate_token($name, $token)) {
            throw new Exception("Invalid CSRF token.");
        }
    }
    ob_start();
    /* adding double quotes for "csrfguard_inject" to prevent:
          Notice: Use of undefined constant csrfguard_inject - assumed 'csrfguard_inject' */
    register_shutdown_function("csrfguard_inject");
}

try {
//    session_destroy();
//    csrfguard_start();
    if (count($_POST)) {
        if (!isset($_POST['CSRFName']) or !isset($_POST['CSRFToken'])) {
            trigger_error("No CSRFName found, probable invalid request.", E_USER_ERROR);
        }
        $name = $_POST['CSRFName'];
        $token = $_POST['CSRFToken'];
        if (!csrfguard_validate_token($name, $token)) {
            throw new Exception("Invalid CSRF token.");
        }
    }
} catch (Exception $e) {
    var_dump($e->getMessage());
}
