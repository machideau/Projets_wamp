<?php
class Flash {
    public static function set($type, $message) {
        if (!isset($_SESSION['flash_messages'])) {
            $_SESSION['flash_messages'] = [];
        }
        $_SESSION['flash_messages'][] = [
            'type' => $type,
            'message' => $message
        ];
    }

    public static function display() {
        if (isset($_SESSION['flash_messages'])) {
            foreach ($_SESSION['flash_messages'] as $message) {
                echo sprintf(
                    '<div class="alert alert-%s alert-dismissible fade show" role="alert">
                        %s
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>',
                    htmlspecialchars($message['type']),
                    htmlspecialchars($message['message'])
                );
            }
            unset($_SESSION['flash_messages']);
        }
    }
}

class CSRF {
    public static function generateToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyToken($token) {
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            Flash::set('danger', 'Invalid CSRF token');
            return false;
        }
        return true;
    }
}

class Logger {
    private static $logFile = ROOT_PATH . '/logs/app.log';

    public static function init() {
        $logDir = dirname(self::$logFile);
        if (!file_exists($logDir)) {
            mkdir($logDir, 0777, true);
        }
    }

    public static function log($level, $message, $context = []) {
        $date = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? json_encode($context) : '';
        $logMessage = "[$date] [$level] $message $contextStr\n";
        error_log($logMessage, 3, self::$logFile);
    }
}

class Validator {
    private $errors = [];

    public function validate($data, $rules) {
        foreach ($rules as $field => $rule) {
            if (!isset($data[$field])) {
                $this->errors[$field] = "Le champ $field est requis";
                continue;
            }

            $value = $data[$field];

            foreach ($rule as $validation => $constraint) {
                switch ($validation) {
                    case 'required':
                        if (empty($value)) {
                            $this->errors[$field] = "Le champ $field est requis";
                        }
                        break;
                    case 'email':
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $this->errors[$field] = "L'email n'est pas valide";
                        }
                        break;
                    case 'min':
                        if (strlen($value) < $constraint) {
                            $this->errors[$field] = "Le champ $field doit contenir au moins $constraint caractères";
                        }
                        break;
                    case 'max':
                        if (strlen($value) > $constraint) {
                            $this->errors[$field] = "Le champ $field doit contenir au maximum $constraint caractères";
                        }
                        break;
                }
            }
        }

        return empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }
}
?>