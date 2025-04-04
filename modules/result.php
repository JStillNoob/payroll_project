<?php
class Result {

    public bool $succeeded;
    public string $message;
    public mixed $data; // Use mixed type for PHP 8+

    public function __construct(bool $succeeded, string $message, mixed $data = null) 
    {
        $this->succeeded = $succeeded;
        $this->message = $message;
        $this->data = $data;
    }
    
    public static function error(string $message): self {
        return new self(false, $message);
    }

    public static function success(?string $message = "Success", mixed $data = null): self {
        return new self(true, $message ?? "Success", $data);
    }
}

?>