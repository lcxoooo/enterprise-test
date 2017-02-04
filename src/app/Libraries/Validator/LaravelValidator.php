<?php namespace App\Libraries\Validator;

use App\Libraries\Validator\Contracts\ValidatorInterface;
use Illuminate\Validation\Factory;
use Illuminate\Support\MessageBag;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class LaravelValidator
 * @package Prettus\Validator
 */
class LaravelValidator implements ValidatorInterface
{
    /**
     * @var int
     */
    protected $id = null;

    /**
     * Data to be validated
     *
     * @var array
     */
    protected $data = array();

    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = array();

    /**
     * Validation errors
     *
     * @var MessageBag
     */
    protected $errors = array();

    /**
     * Validator
     *
     * @var \Illuminate\Validation\Factory
     */
    protected $validator;

    /**
     * @var array
     */
    protected $messages;


    /**
     * Construct
     *
     * @param \Illuminate\Validation\Factory $validator
     */
    public function __construct(Factory $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Set Id
     *
     * @param $id
     * @return $this
     */
    public function setId($id){
        $this->id = $id;
        return $this;
    }

    /**
     * Set data to validate
     *
     * @param array $data
     * @return $this
     */
    public function with(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Return errors
     *
     * @return array
     */
    public function errors()
    {
        return $this->errorsBag()->all();
    }

    /**
     * Errors
     *
     * @return MessageBag
     */
    public function errorsBag()
    {
        return $this->errors;
    }


    /**
     * Pass the data and the rules to the validator or throws ValidatorException
     *
     * @throws ValidatorException
     * @param string $action
     * @return boolean
     */
    public function passesOrFail($action = null)
    {
        if(  !$this->passes($action) ){
            throw new ValidatorException( $this->errorsBag() );
        }

        return true;
    }

    /**
     * Get rule for validation by action ValidatorInterface::RULE_CREATE or ValidatorInterface::RULE_UPDATE
     *
     * Default rule: ValidatorInterface::RULE_CREATE
     *
     * @param null $action
     * @return array
     */
    public function getRules($action = null){

        $rules = $this->rules;

        if( isset($this->rules[$action]) ){
            $rules = $this->rules[$action];
        }

        return $this->parserValidationRules($rules, $this->id);
    }

    /**
     * Set Rules for Validation
     *
     * @param array $rules
     * @return $this
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * Parser Validation Rules
     *
     * @param $rules
     * @param null $id
     * @return array
     */
    protected function parserValidationRules($rules, $id = null)
    {

        if($id === null)
        {
            return $rules;
        }

        array_walk($rules, function(&$rules, $field) use ($id)
        {
            if(!is_array($rules))
            {
                $rules = explode("|", $rules);
            }

            foreach($rules as $ruleIdx => $rule)
            {
                // get name and parameters
                @list($name, $params) = array_pad(explode(":", $rule), 2, null);

                // only do someting for the unique rule
                if(strtolower($name) != "unique") {
                    continue; // continue in foreach loop, nothing left to do here
                }

                $p = array_map("trim", explode(",", $params));

                // set field name to rules key ($field) (laravel convention)
                if(!isset($p[1])) {
                    $p[1] = $field;
                }

                // set 3rd parameter to id given to getValidationRules()
                $p[2] = $id;

                $params = implode(",", $p);
                $rules[$ruleIdx] = $name.":".$params;
            }
        });

        return $rules;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param $messages
     * @return $this
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * Pass the data and the rules to the validator
     *
     * @param string $action
     * @return bool
     */
    public function passes($action = null)
    {
        $rules     = $this->getRules($action);
        $validator = $this->validator->make($this->data, $rules, $this->messages);

        if( $validator->fails() )
        {
            $this->errors = $validator->errors();
            return false;
        }

        return true;
    }

}