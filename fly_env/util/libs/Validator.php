<?php namespace FLY\Libs;

abstract class Validator extends FLYFormValidator {

    protected ?Request $request;

    protected FLYFormValidator $validator;

    protected array $model    = [];

    protected array $response = [];
    
    public function __construct(?Request $request)
    {
        if($request <> null) {
            $this->validator = self::check($request, $this->error_report());
            $this->request   = $this->validator->get_request();
        }
    }

    abstract protected function error_report(): array;
}